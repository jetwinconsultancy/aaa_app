<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Jose\Component\Core\JWK;
use Jose\Component\Core\JWKSet;
use Jose\Easy\Build;
use Jose\Component\KeyManagement\JWKFactory;

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Signature\Algorithm\ES512;
use Jose\Component\Signature\JWSBuilder;

use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;


class Welcome extends MY_Controller
{
    //public static $storage;
    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            $authorization_code = $this->input->get('code', TRUE);
            if($authorization_code)
            {
                redirect('singpass_login/'.$authorization_code);
            }
            else
            {
                redirect('login');
            }
        }

        $this->load->library(array('encryption', 'session', 'form_validation'));
        $this->load->model(array('transaction_model', 'master_model', 'db_model'));
        // $this->load->model('db_model');
    }

    // function token_endpoint($jwk,$authorization_code)
    // {
    //     $time = time(); // The current time

    //     $jws = Build::jws() // We build a JWS
    //         ->header('typ', 'JWT')
    //         ->alg('ES512') // The signature algorithm. A string or an algorithm class.
    //         ->sub('ANnWPio7eU2t8SYWHnmnh2Unpnd6Oqlh')
    //         //->aud('https://stg-id.singpass.gov.sg')
    //         ->claim('aud', 'https://stg-id.singpass.gov.sg')
    //         ->iss('ANnWPio7eU2t8SYWHnmnh2Unpnd6Oqlh') // The "iss" claim
    //         ->iat($time) // The "iat" claim
    //         ->exp($time + 120) // The "exp" claim
    //         ->sign($jwk) // Compute the token with the given JWK
    //     ;
    //     //---------------------------------------------------------------------------------------------

    //     //------- Token endpoint ( Authorization Code Grant - Authenticated with Client Assertion JWT) ------
    //     /* API URL */
    //     $url = 'https://stg-id.singpass.gov.sg/token';

    //     /* Init cURL resource */
    //     $ch = curl_init();

    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     /* Array Parameter Data */
    //     $data = ['client_id' => 'ANnWPio7eU2t8SYWHnmnh2Unpnd6Oqlh', 'redirect_uri'=>'https://acumenbizcorp.com.sg/test_secretary/welcome', 'grant_type' => 'authorization_code', 'code' => $authorization_code, 'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',  'client_assertion' => $jws];

    //     curl_setopt($ch, CURLOPT_POST, true);

    //     /* pass encoded JSON string to the POST fields */
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            
    //     /* set the content type json */
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //         'Content-Type:application/x-www-form-urlencoded'));
            
    //     /* set return type json */
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
    //     /* execute request */
    //     $result = json_decode(curl_exec($ch));
        
    //     /* close cURL resource */
    //     curl_close($ch);

    //     return $result;
    // }

    
    public function index()
    {

        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

        if ($_SESSION['group_id'] == 2 || $_SESSION['group_id'] == 3 || $_SESSION['group_id'] == 4 || $_SESSION['group_id'] == 5 || $_SESSION['group_id'] == 6) 
        {
            $array_year = array();
            $array_unpaid = array();
            $array_paid = array();

            $array_client_name_list = array();

            if($this->session->userdata("firm_id") == 18 || $this->session->userdata("firm_id") == 26)
            {
                $where = '(firm_id = 18 or firm_id = 26)';
                $pending_documents_where = '(pending_documents.firm_id = 18 or pending_documents.firm_id = 26)';
            }
            else
            {
                $where = "firm_id = '".$this->session->userdata('firm_id')."'";
                $pending_documents_where = "pending_documents.firm_id = '".$this->session->userdata('firm_id')."'";
            }
            

            $unpaid_bill = $this->db->query(" SELECT sum(outstanding) as unpaid_bill, DATE_FORMAT(STR_TO_DATE(invoice_date,'%d/%m/%Y'),'%Y') as year FROM billing WHERE ".$where." AND billing.status = '0' GROUP BY DATE_FORMAT(STR_TO_DATE(invoice_date,'%d/%m/%Y'),'%Y')");

            $unpaid_bill = $unpaid_bill->result_array();

            for($t = 0; $t < count($unpaid_bill); $t++)
            {
                array_push($array_unpaid, (float)$unpaid_bill[$t]["unpaid_bill"]);
                array_push($array_year, $unpaid_bill[$t]["year"]);
            }

            $this->data['unpaid_bill'] = $array_unpaid;
            $this->data['year'] = $array_year;

            $paid_bill = $this->db->query("SELECT sum(received) as paid_bill, DATE_FORMAT(STR_TO_DATE(receipt.receipt_date,'%d/%m/%Y'),'%Y') as year FROM billing_receipt_record left join receipt on receipt.id = billing_receipt_record.receipt_id WHERE ".$where." AND receipt.receipt_date != '' GROUP BY DATE_FORMAT(STR_TO_DATE(receipt.receipt_date,'%d/%m/%Y'),'%Y')");

            $paid_bill = $paid_bill->result_array();

            for($t = 0; $t < count($paid_bill); $t++)
            {
                array_push($array_paid, (float)$paid_bill[$t]["paid_bill"]);
            }

            $this->data['paid_bill'] = $array_paid;

            $pending_documents = $this->db->query("select pending_documents.*, client.company_name, users.first_name, users.last_name from pending_documents left join client on client.id = pending_documents.client_id left join users on users.id = pending_documents.created_by where ".$pending_documents_where." AND pending_documents.received_on = '' ORDER BY pending_documents.created_at DESC limit 10");

            if ($pending_documents->num_rows() > 0) 
            {
                foreach (($pending_documents->result_array()) as $row) 
                {
                    $row['company_name'] = $this->encryption->decrypt($row["company_name"]);
                    $data_pending_documents[] = $row;
                }
            }
            else
            {
                $data_pending_documents = array();
            }

            $this->data['pending_documents'] = $data_pending_documents;

           
            $this->data['unpaid_billings'] = $this->db_model->get_all_unpaid_billings_for_dashboard();
            $this->data['transaction'] = $this->transaction_model->get_all_transaction($_SESSION['group_id']);
            $this->data['acknowledgement'] = $this->db_model->check_acknowledgement($_SESSION['group_id']);
            
            
            //echo json_encode($this->data['acknowledgement']);

            $bc = array(array('link' => '#', 'page' => lang('dashboard')));
            $meta = array('page_title' => lang('dashboard'), 'bc' => $bc, 'page_name' => lang('dashboard'));

            $this->page_construct('dashboard', $meta, $this->data);
        }
        elseif($_SESSION['group_id'] == 1)
        {
            $bc = array(array('link' => '#', 'page' => lang('dashboard')));
            $meta = array('page_title' => lang('dashboard'), 'bc' => $bc, 'page_name' => lang('dashboard'));

            $this->db->select('*');
            $this->db->from('users');
            $this->db->where('group_id = 2');
            $this->db->order_by('id');
            $q = $this->db->get();

            if ($q->num_rows() > 0) 
            {
                $user_info = $q->result_array();

                foreach ($user_info as $row) {
                    $data[] = $row;
                }
                $this->data['user_info'] = $data;


            }

            $this->page_construct('admin_page', $meta, $this->data);
        }
    }

    function update_acknowledgement()
    {
        $acknowledgement["user_id"] = $this->session->userdata('user_id');
        $acknowledgement["read_and_understood"] = $_POST["understood"];

        $q = $this->db->get_where("acknowledgement", array('user_id' => $this->session->userdata('user_id')));

        if ($q->num_rows() > 0) 
        {
            $this->db->update("acknowledgement",$acknowledgement,array("user_id" => $this->session->userdata('user_id')));
        }
        else
        {
            $this->db->insert("acknowledgement",$acknowledgement);
        }

        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
    }

    function promotions()
    {
        $this->load->view($this->theme . 'promotions', $this->data);
    }

    function image_upload()
    {
        if (DEMO) {
            $error = array('error' => $this->lang->line('disabled_in_demo'));
            echo json_encode($error);
            exit;
        }
        $this->security->csrf_verify();
        if (isset($_FILES['file'])) {
            $this->load->library('upload');
            $config['upload_path'] = 'assets/uploads/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = '500';
            $config['max_width'] = $this->Settings->iwidth;
            $config['max_height'] = $this->Settings->iheight;
            $config['encrypt_name'] = TRUE;
            $config['overwrite'] = FALSE;
            $config['max_filename'] = 25;
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('file')) {
                $error = $this->upload->display_errors();
                $error = array('error' => $error);
                echo json_encode($error);
                exit;
            }
            $photo = $this->upload->file_name;
            $array = array(
                'filelink' => base_url() . 'assets/uploads/images/' . $photo
            );
            echo stripslashes(json_encode($array));
            exit;

        } else {
            $error = array('error' => 'No file selected to upload!');
            echo json_encode($error);
            exit;
        }
    }

    function set_data($ud, $value)
    {
        $this->session->set_userdata($ud, $value);
        echo true;
    }

    function hideNotification($id = NULL)
    {
        $this->session->set_userdata('hidden' . $id, 1);
        echo true;
    }

    function language($lang = false)
    {
        if ($this->input->get('lang')) {
            $lang = $this->input->get('lang');
        }
        //$this->load->helper('cookie');
        $folder = 'sma/language/';
        $languagefiles = scandir($folder);
        if (in_array($lang, $languagefiles)) {
            $cookie = array(
                'name' => 'language',
                'value' => $lang,
                'expire' => '31536000',
                'prefix' => 'sma_',
                'secure' => false
            );

            $this->input->set_cookie($cookie);
        }
        redirect($_SERVER["HTTP_REFERER"]);
    }

    function download($file)
    {
        $this->load->helper('download');
        force_download('./files/'.$file, NULL);
        exit();
    }

	function save_task(){
		// print_r($_POST);
		$user_id = $_SESSION['user_id'];
		$task = $this->input->post('task_todolist');
		$date = $this->sma->fsd($this->input->post('date_todolist'));
		$assign_to = implode('|',$this->input->post('assign_to'));
		$urgent = 0;
		if ($this->input->post('urgent') == "on") $urgent = 1;
		// print_r($this->sma->fsd($date));
		$this->db->query("insert into user_task(task,task_date,assign_to,assign_by,urgent,date_created) values('".$task."','".$date."','|".$assign_to."|',".$user_id.",".$urgent.",now())");
	}
	function get_task(){
		$task = $this->input->post('task_todolist');
		$date = $this->sma->fsd($this->input->post('date_todolist'));
		$assign_to = implode('|',$this->input->post('assign_to'));
		$urgent = 0;
		if ($this->input->post('urgent') == "on") $urgent = 1;
		// print_r($this->sma->fsd($date));
		$this->db->query("insert into user_task(task,task_date,assign_to,assign_by,urgent,date_created) values('".$task."','".$date."','|".$assign_to."|',".$user_id.",".$urgent.",now())");
	}
}

//-----------------------Backup Code for SingPass Login-----------------------------------------------
// $time = time(); // The current time
        
//         //------------------------- 1. Retrieve sig key --------------------------------------
//         // create curl resource
//         $ch = curl_init();

//         // set url
//         curl_setopt($ch, CURLOPT_URL, "https://acumenbizcorp.com.sg/test_secretary/jwks/key");

//         //return the transfer as a string
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//         // $output contains the output string
//         $jwks_output = json_decode(curl_exec($ch));

//         // close curl resource to free up system resources
//         curl_close($ch); 
//         //-------------------------------------------------------------------------------

//         //--------- create a signed token (JWS) with a set of standard and custom claims and headers ------------
//         $jwk = new JWK((array)$jwks_output->keys[0]);

//         $jws = Build::jws() // We build a JWS
//             ->header('typ', 'JWT')
//             ->alg('ES512') // The signature algorithm. A string or an algorithm class.
//             ->sub('ANnWPio7eU2t8SYWHnmnh2Unpnd6Oqlh')
//             //->aud('https://stg-id.singpass.gov.sg')
//             ->claim('aud', 'https://stg-id.singpass.gov.sg')
//             ->iss('ANnWPio7eU2t8SYWHnmnh2Unpnd6Oqlh') // The "iss" claim
//             ->iat($time) // The "iat" claim
//             ->exp($time + 120) // The "exp" claim
//             ->sign($jwk) // Compute the token with the given JWK
//         ;
//         //---------------------------------------------------------------------------------------------
    
//         //------- Token endpoint ( Authorization Code Grant - Authenticated with Client Assertion JWT) ------
//         //$authorization_code = $this->input->get('code', TRUE);
//         $authorization_code = $auth_code;
//         /* API URL */
//         $url = 'https://stg-id.singpass.gov.sg/token';

//         /* Init cURL resource */
//         $ch = curl_init();

//         curl_setopt($ch, CURLOPT_URL, $url);
//         /* Array Parameter Data */
//         $data = ['client_id' => 'ANnWPio7eU2t8SYWHnmnh2Unpnd6Oqlh', 'redirect_uri'=>'https://acumenbizcorp.com.sg/test_secretary/welcome', 'grant_type' => 'authorization_code', 'code' => $authorization_code, 'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',  'client_assertion' => $jws];

//         curl_setopt($ch, CURLOPT_POST, true);

//         /* pass encoded JSON string to the POST fields */
//         curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            
//         /* set the content type json */
//         curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//             'Content-Type:application/x-www-form-urlencoded'));
            
//         /* set return type json */
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
//         /* execute request */
//         $result = json_decode(curl_exec($ch));
        
//         /* close cURL resource */
//         curl_close($ch);

//         // Our enc key
//         $ec_jwk = new JWK((array)$jwks_output->keys[1]);

//         $ec_token = $result->id_token;
        
//         $tokenParts = explode(".", $ec_token);  
//         $tokenHeader = base64_decode(strtr($tokenParts[0], '-_', '+/'), true);
//         $tokenEncryptedKey = $tokenParts[1];
//         $tokenInitVector = $tokenParts[2];
//         $tokenCiphertext = $tokenParts[3];
//         $tokenAuthTag = $tokenParts[4];
//         $jwtHeader = json_decode($tokenHeader);
//         //----------------------------------------------------------------------------------------------

//         //---------------------- Decrypt id_token in Nodejs --------------------------------------------
//         /* API URL */
//         $decrypt_url = 'http://ec2-52-77-231-6.ap-southeast-1.compute.amazonaws.com:8080/decrypt/getDecodeToken';

//         /* Init cURL resource */
//         $ch = curl_init();

//         curl_setopt($ch, CURLOPT_URL, $decrypt_url);
//         /* Array Parameter Data */
//         $data = ['jwe' => $ec_token, 'jwt'=> [$jwks_output->keys[1]], 'alg' => $jwtHeader->alg];

//         curl_setopt($ch, CURLOPT_POST, true);

//         /* pass encoded JSON string to the POST fields */
//         curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            
//         /* set the content type json */
//         curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//             'Content-Type:application/x-www-form-urlencoded'));
            
//         /* set return type json */
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
//         /* execute request */
//         $final_jwt_result = json_decode(curl_exec($ch));
        
//         /* close cURL resource */
//         curl_close($ch);

//         $final_jwt_result_parts = explode(".", $final_jwt_result->data);  
//         $final_jwt_claims = json_decode((base64_decode($final_jwt_result_parts[1])));
//         $final_jwt_claims_sub = $final_jwt_claims->sub;
//         $final_jwt_claims_sub_parts = explode(',', $final_jwt_claims_sub);
//         $extract_user_id_parts = explode('=', $final_jwt_claims_sub_parts[0]);
//         $identification_no = $extract_user_id_parts[1];
//         //------------------------------------------------------------------------------------------
