<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        $this->load->library(array('session', 'form_validation'));
        $this->load->model('db_model');
    }

    public function index()
    {
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        // $this->data['sales'] = $this->db_model->getLatestSales();
        // $this->data['quotes'] = $this->db_model->getLastestQuotes();
        // $this->data['purchases'] = $this->db_model->getLatestPurchases();
        // $this->data['transfers'] = $this->db_model->getLatestTransfers();
        // $this->data['customers'] = $this->db_model->getLatestCustomers();
        // $this->data['suppliers'] = $this->db_model->getLatestSuppliers();
        // $this->data['chatData'] = $this->db_model->getChartData();
        // $this->data['stock'] = $this->db_model->getStockValue();
        // $this->data['bs'] = $this->db_model->getBestSeller();
		//$this->sma->print_arrays($_SESSION);
        if ($_SESSION['group_id'] == 2 || $_SESSION['group_id'] == 3 || $_SESSION['group_id'] == 4) 
        {
            /*$this->data['users'] = $this->db_model->getUserList();
            $this->data['uncomplete_task'] = 0;
    		if ($this->db_model->getUncompleteTask($_SESSION['user_id'])->total) $this->data['uncomplete_task'] = $this->db_model->getUncompleteTask($_SESSION['user_id'])->total;

    		$this->data['complete_task'] = 0;
    		if ($this->db_model->getcompleteTask($_SESSION['user_id'])->total) $this->data['complete_task'] = $this->db_model->getcompleteTask($_SESSION['user_id'])->total;*/

    		/*if ($_SESSION['group_id'] ==2 || $_SESSION['group_id'] ==3) 
    		{
    			$this->data['user_task'] = $this->db_model->getTask();
    		} 
            else  
            {
    			$this->data['user_task'] = $this->db_model->getTask($_SESSION['user_id']);
    		}*/

            //STR_TO_DATE(date_of_appointment,"%d/%m/%Y")
            $array_year = array();
            $array_unpaid = array();
            $array_paid = array();

            $unpaid_bill = $this->db->query(" SELECT sum(outstanding) as unpaid_bill, DATE_FORMAT(STR_TO_DATE(invoice_date,'%d/%m/%Y'),'%Y') as year FROM billing WHERE firm_id = '".$this->session->userdata("firm_id")."' AND billing.status = '0' GROUP BY DATE_FORMAT(STR_TO_DATE(invoice_date,'%d/%m/%Y'),'%Y')");

            $unpaid_bill = $unpaid_bill->result_array();

            for($t = 0; $t < count($unpaid_bill); $t++)
            {
                array_push($array_unpaid, (float)$unpaid_bill[$t]["unpaid_bill"]);
                array_push($array_year, $unpaid_bill[$t]["year"]);
            }

            $this->data['unpaid_bill'] = $array_unpaid;
            $this->data['year'] = $array_year;

            $paid_bill = $this->db->query(" SELECT sum(received) as paid_bill, DATE_FORMAT(STR_TO_DATE(receipt.receipt_date,'%d/%m/%Y'),'%Y') as year FROM billing_receipt_record left join receipt on receipt.id = billing_receipt_record.receipt_id WHERE billing_receipt_record.firm_id = '".$this->session->userdata("firm_id")."' GROUP BY DATE_FORMAT(STR_TO_DATE(receipt.receipt_date,'%d/%m/%Y'),'%Y')");

            $paid_bill = $paid_bill->result_array();

            for($t = 0; $t < count($paid_bill); $t++)
            {
                array_push($array_paid, (float)$paid_bill[$t]["paid_bill"]);
            }

            $this->data['paid_bill'] = $array_paid;

            $pending_documents = $this->db->query("select pending_documents.*, client.company_name, users.first_name, users.last_name from pending_documents left join client on client.id = pending_documents.client_id left join users on users.id = pending_documents.created_by where pending_documents.firm_id='".$this->session->userdata('firm_id')."' AND pending_documents.received_on = '' ORDER BY pending_documents.created_at DESC limit 10");

            foreach (($pending_documents->result_array()) as $row) 
            {
                $data_pending_documents[] = $row;
            }
            $this->data['pending_documents'] = $data_pending_documents;

            $this->db->select('client.*');
            $this->db->from('client');
            $this->db->where('client.firm_id', $this->session->userdata("firm_id"));
            $this->db->where('client.deleted = 0');
            $this->db->order_by('client.id', 'desc');
            $q = $this->db->get();

            if ($q->num_rows() > 0) 
            {
                $client_info = $q->result_array();

                for($i = 0; $i < count($client_info); $i++)
                {
                    $query = $this->db->query("select year_end, agm, due_date_175, 175_extended_to, due_date_201, 201_extended_to from filing where agm = '' AND company_code='".$client_info[$i]["company_code"]."'");

                    $filing_info = $query->result_array();

                    if ($query->num_rows() > 0) {
                        $client_info[$i] = array_merge($client_info[$i], $filing_info[0]);
                    }

                    if($client_info[$i]["175_extended_to"] != "0")
                    {
                        $due_date_175 = strtotime($client_info[$i]["175_extended_to"]);
                    }
                    else if($client_info[$i]["due_date_175"] != "Not Applicable")
                    {
                        $due_date_175 = strtotime($client_info[$i]["due_date_175"]);
                    }
                    else if($client_info[$i]["due_date_175"] == "Not Applicable")
                    {
                        $due_date_175 = $client_info[$i]["due_date_175"];
                    }

                    if($client_info[$i]["201_extended_to"] != "0")
                    {
                        $due_date_201 = strtotime($client_info[$i]["201_extended_to"]);
                    }
                    else
                    {
                        $due_date_201 = strtotime($client_info[$i]["due_date_201"]);
                    }

                    if($due_date_175 == "Not Applicable")
                    {
                        $now = time(); // or your date as well
                        $your_date = $due_date_201;
                        $datediff = $your_date - $now;

                        $client_info[$i]["days"] = round($datediff / (60 * 60 * 24));
                    }
                    else if($due_date_175 > $due_date_201)
                    {
                        $now = time(); // or your date as well
                        $your_date = $due_date_201;
                        $datediff = $your_date - $now;

                        $client_info[$i]["days"] = round($datediff / (60 * 60 * 24));
                    }
                    else if($due_date_201 > $due_date_175)
                    {
                        $now = time(); // or your date as well
                        $your_date = $due_date_175;
                        $datediff = $your_date - $now;

                        $client_info[$i]["days"] = round($datediff / (60 * 60 * 24));
                    }

                    //$latest_agm = date('Y-m-d', strtotime($_POST['agm']));
                }

                foreach (($client_info) as $row) {
                    if($row["days"] != null)
                    {
                        $data[] = $row;
                    }
                    
                }
                $this->data['due_date'] = $data;
            }

            $recent_add_company = $this->db->query(" SELECT * FROM client WHERE firm_id = '".$this->session->userdata("firm_id")."' AND deleted = 0 ORDER BY created_at DESC limit 10");
            
            foreach (($recent_add_company->result_array()) as $row) 
            {
                $data_recent_add_company[] = $row;
            }
            $this->data['recent_add_company'] = $data_recent_add_company;
            /*$lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
            $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';*/
            // $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
            $bc = array(array('link' => '#', 'page' => lang('dashboard')));
            $meta = array('page_title' => lang('dashboard'), 'bc' => $bc);

            $this->page_construct('dashboard', $meta, $this->data);
        }
        elseif($_SESSION['group_id'] ==1)
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

                    /*$this->db->select("users.id")
                    ->from("users")
                    ->join('groups', 'users.group_id = groups.id', 'inner')
                    ->join('user_firm as a', 'a.user_id = "'.$row["id"].'"', 'inner')
                    ->join('user_firm as b', 'a.firm_id=b.firm_id', 'inner')
                    ->where('b.user_id = users.id')
                    ->where('b.user_id != "'.$row["id"].'"')
                    ->group_by('users.id');
                    $test = $this->db->get();
                    $test = $test->result_array();



                    $this->db->select("firm_id")
                    ->from("user_firm")
                    ->where('user_id = "'.$row["id"].'"');

                    $firm_id = $this->db->get();
                    $firm_id = $firm_id->result_array();

                    $data_firm_id = array();

                    foreach ($firm_id as $rows) {
                        array_push($data_firm_id, $rows["firm_id"]);
                    }

                    $user["no_of_user"] = count($test);
                    $user["no_of_firm"] = count($data_firm_id);
                    $this->db->where('id', $row["id"]);
                    $this->db->update('users',$user);

                    if(count($data_firm_id) != 0)
                    {
                        

                        $this->db->select('id');
                        $this->db->from('client');
                        $this->db->where_in('firm_id', $data_firm_id);

                        $num_client = $this->db->get();
                        $num_client = $num_client->result_array();

                        if(count($num_client) != 0)
                        {   
                            //echo json_encode($row["id"]);
                            $users["no_of_client"] = count($num_client);

                        }
                        else
                        {
                            $users["no_of_client"] = 0;
                        }

                        $this->db->where('id', $row["id"]);
                        $this->db->update('users',$users);

                        $data_user_id = array();

                        foreach ($test as $r) {
                            array_push($data_user_id, $r["id"]);
                        }

                        $this->db->where_in('id', $data_user_id);
                        $this->db->update('users',$users);
                    }*/
                    
                    

                    $data[] = $row;


                }
                $this->data['user_info'] = $data;


            }

            $this->page_construct('admin_page', $meta, $this->data);
        }

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
