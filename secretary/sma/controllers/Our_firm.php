<?php defined('BASEPATH') OR exit('No direct script access allowed');

// use Aws\S3\S3Client;  
// use Aws\Exception\AwsException;

class Our_firm extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        $this->load->library(array('session', 'form_validation'));
        $this->load->model(array('master_model', 'db_model'));
        $this->config->load('aws_php'); //loading of config file
    }

    public function index()
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $bc = array(array('link' => '#', 'page' => lang('Our Firm')));
        $meta = array('page_title' => lang('Our Firm'), 'bc' => $bc, 'page_name' => 'Our Firm');

        if (isset($_POST['search'])) 
        {
            $term = $_POST['search'];   
        }
        else
        {
            $term = null;
        }

        if($_SESSION['group_id'] ==2 || $_SESSION['group_id'] ==3 || $_SESSION['group_id'] ==4  || $_SESSION['group_id'] == 5 || $_SESSION['group_id'] == 6) 
        {   
            if($term != null)
            {
                $this->data['all_firm'] = $this->master_model->get_all_firm_info($term);
            }
            else
            {
                $this->data['all_firm'] = $this->master_model->get_all_firm_info();
            }

            $this->page_construct('firm.php', $meta, $this->data);
        }
        else if ($_SESSION['group_id'] ==1)
        {
            $this->data['firm'] = $this->master_model->get_firm_info();
            $this->page_construct('our_firm.php', $meta, $this->data);
        }
    }

    public function add()
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $bc = array(array('link' => '#', 'page' => lang('Create Our Firm')));
        $meta = array('page_title' => lang('Create Our Firm'), 'bc' => $bc, 'page_name' => 'Create Our Firm');

        $p[0] = new stdClass;
        
        $p[0]->local_status = 'checked';
        $p[0]->address_type = 'Local';
        $p[0]->gst_checkbox = null;

        $this->data['firm'] = $p;
        $this->data['firm_currency'] = $this->db_model->get_currency();
        $this->data['firm_jurisdiction'] = $this->db_model->get_jurisdiction_info($this->session->userdata("user_admin_code_id"));

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Our Firm', base_url('our_firm'));
        $this->mybreadcrumb->add('Create Our Firm', base_url());
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('our_firm.php', $meta, $this->data);
    }

    public function edit($id, $tab = null)
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $bc = array(array('link' => '#', 'page' => 'Edit Our Firm'));
        $meta = array('page_title' => 'Edit Our Firm', 'bc' => $bc, 'page_name' => 'Edit Our Firm');

        $this->data['firm'] = $this->master_model->edit_firm_info($id);
        $this->data['gst_firm'] = $this->master_model->edit_gst_firm_info($id);
        $this->data['bank_info'] = $this->master_model->get_bank_info($id);
        $this->data['template'] = $this->db_model->get_all_template();
        $this->data['firm_id'] = $id;
        $this->data['tab'] = $tab;
        $this->data['firm_currency'] = $this->db_model->get_currency();
        $this->data['firm_jurisdiction'] = $this->db_model->get_jurisdiction_info($this->session->userdata("user_admin_code_id"));

        if ($this->data['firm'][0]->address_type == "Local")
        {
            $this->data['firm'][0]->local_status = 'checked';
        }
        else
        {
            $this->data['firm'][0]->foreign_status = 'checked';
        }

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Our Firm', base_url('our_firm'));
        $this->mybreadcrumb->add('Edit Our Firm - '.$this->data['firm'][0]->name.'', base_url());
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('our_firm.php', $meta, $this->data);
    }

    public function check_default_company()
    {
        $checked = $_POST["checked"];
        $firm_id = $_POST["firm_id"];
        $user_id = $_POST["user_id"];

        if(!$checked)
        {
            $data_checked["default_company"] = 0;
            $this->db->update("user_firm",$data_checked,array("firm_id" => $firm_id, "user_id" => $user_id));

            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
        }
        else
        {
           

            $q = $this->db->get_where("user_firm", array("user_id" => $user_id, "default_company" => 1, "firm_id != " => $firm_id));

            if (!$q->num_rows())
            {
                if($checked == "false")
                {
                    $data["default_company"] = 1;
                     $this->db->update("user_firm", $data, array("firm_id" => $firm_id, "user_id" => $user_id));

                    echo json_encode(array("Status" => 1, 'message' => 'Must at least one comapny is default company.', 'title' => 'Error'));
                }
                else
                {
                    $data["default_company"] = 1;
                    $this->db->update("user_firm", $data, array("firm_id" => $firm_id, "user_id" => $user_id));

                    echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
                }

               
            }
            else
            {
                echo json_encode(array("Status" => 0));
            }
        }
    }
    public function check_in_use_bank()
    {
        $checked = $_POST["checked"];
        $firm_id = $_POST["firm_id"];
        $bank_info_id = $_POST["bank_info_id"];

        if(!$checked)
        {
            $data_checked["in_use"] = 0;
            $this->db->update("bank_info",$data_checked,array("firm_id" => $firm_id, "id" => $bank_info_id));

            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
        }
        else
        {
            $q = $this->db->get_where("bank_info", array("id !=" => $bank_info_id, "in_use" => 1, "firm_id" => $firm_id));

            if (!$q->num_rows())
            {
                if($checked == "false")
                {
                    $data["in_use"] = 1;
                    $this->db->update("bank_info", $data, array("firm_id" => $firm_id, "id" => $bank_info_id));

                    echo json_encode(array("Status" => 1, 'message' => 'Must at least one comapny is in use company.', 'title' => 'Error'));
                }
                else
                {
                    $data["in_use"] = 1;

                    $this->db->update("bank_info", $data, array("firm_id" => $firm_id, "id" => $bank_info_id));

                    echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
                }
            }
            else
            {
                echo json_encode(array("Status" => 0));
            }
        }
    }

    public function check_in_use_company()
    {
        $checked = $_POST["checked"];
        $firm_id = $_POST["firm_id"];
        $user_id = $_POST["user_id"];

        if(!$checked)
        {
            $data_checked["in_use"] = 0;
            $this->db->update("user_firm",$data_checked,array("firm_id" => $firm_id, "user_id" => $user_id));

            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
        }
        else
        {
            $q = $this->db->get_where("user_firm", array("user_id" => $user_id, "in_use" => 1, "firm_id != " => $firm_id));

            if (!$q->num_rows())
            {
                if($checked == "false")
                {
                    $data["in_use"] = 1;
                    $this->db->update("user_firm", $data, array("firm_id" => $firm_id, "user_id" => $user_id));

                    echo json_encode(array("Status" => 1, 'message' => 'Must at least one company is in use company.', 'title' => 'Error'));
                }
                else
                {
                    $data["in_use"] = 1;

                    $this->db->update("user_firm", $data, array("firm_id" => $firm_id, "user_id" => $user_id));

                    $check_in_use_user_firm = $this->db->get_where("user_firm", array("user_id" => $this->session->userdata('user_id'), "in_use =" => 1));

                    if ($check_in_use_user_firm->num_rows())
                    {
                        $check_in_use_user_firm = $check_in_use_user_firm->result_array();

                        $this->session->set_userdata('firm_id', $check_in_use_user_firm[0]["firm_id"]);

                        $query = $this->db->query('select id, firm_id from billing_template where firm_id = "'.$check_in_use_user_firm[0]["firm_id"].'"');

                        if (!$query->num_rows())
                        {
                            $master_billing_query = $this->db->query('select * from billing_master_template');

                            $master_billing_query = $master_billing_query->result_array();

                            for($y = 0; $y < count($master_billing_query); $y++)
                            {
                                $billing_template["firm_id"] = $check_in_use_user_firm[0]["firm_id"];
                                $billing_template["service"] = $master_billing_query[$y]["service"];
                                $billing_template["invoice_description"] = $master_billing_query[$y]["invoice_description"];
                                $billing_template["amount"] = $master_billing_query[$y]["amount"];
                                $billing_template["frequency"] = $master_billing_query[$y]["frequency"];

                                $this->db->insert("billing_template",$billing_template);
                            }
                        }
                    }

                    echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
                }

                
            }
            else
            {
                echo json_encode(array("Status" => 0));
            }
        }
    }

    public function change_default_company()
    {
        $checked = $_POST["checked"];
        $firm_id = $_POST["firm_id"];
        $user_id = $_POST["user_id"];

        $data_checked["default_company"] = 0;
        $this->db->update("user_firm" ,$data_checked, array("user_id" => $user_id, "default_company" => 1));

        $data["default_company"] = 1;
        $this->db->update("user_firm", $data, array("user_id" => $user_id, "firm_id" => $firm_id));

        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
    }

    public function change_in_use_bank()
    {
        $checked = $_POST["checked"];
        $firm_id = $_POST["firm_id"];
        $bank_info_id = $_POST["bank_info_id"];

        $data_checked["in_use"] = 0;
        $this->db->update("bank_info" ,$data_checked, array("firm_id" => $firm_id));

        $data["in_use"] = 1;
        $this->db->update("bank_info", $data, array("id" => $bank_info_id, "firm_id" => $firm_id));

        $firm_result = $this->db->query("select * from firm where id = '".$_POST['firm_id']."'");
        $firm_array = $firm_result->result_array();

        if($firm_array[0]["branch_name"] != "")
        {
            $branch_name = "(".$firm_array[0]["branch_name"].")";
        }
        else
        {
            $branch_name = "";
        }

        $this->save_audit_trail("Our Firm", "Bank Info", $firm_array[0]["name"].$branch_name." default bank info is changed.");

        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
    }

    public function change_in_use_company()
    {
        $checked = $_POST["checked"];
        $firm_id = $_POST["firm_id"];
        $user_id = $_POST["user_id"];

        $data_checked["in_use"] = 0;
        $this->db->update("user_firm" ,$data_checked, array("user_id" => $user_id, "in_use" => 1));

        $data["in_use"] = 1;
        $this->db->update("user_firm", $data, array("user_id" => $user_id, "firm_id" => $firm_id));

        //$check_in_use_user_firm = $this->db->get_where("user_firm", array("user_id" => $this->session->userdata('user_id'), "in_use =" => 1));

        $this->db->select('firm.*, user_firm.firm_id, user_firm.default_company, user_firm.in_use')
                    ->from('firm')
                    ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
                    ->where('user_firm.user_id = '.$this->session->userdata('user_id'))
                    ->where('user_firm.in_use = 1');
        $check_in_use_user_firm = $this->db->get();
        // echo "user_id"; print_r($this->session->userdata('user_id')); echo "<br/>";
        // echo "check_in_use_user_firm"; print_r($check_in_use_user_firm);

        if ($check_in_use_user_firm->num_rows())
        {
            $check_in_use_user_firm = $check_in_use_user_firm->result_array();

            $this->session->set_userdata('firm_id', $check_in_use_user_firm[0]["firm_id"]);
            if($check_in_use_user_firm[0]["qb_company_id"] == null)
            {
                $qb_company_id = "";
            }
            else
            {
                $qb_company_id = $check_in_use_user_firm[0]["qb_company_id"];
            }
            $this->session->set_userdata('qb_company_id', $qb_company_id);

            $query = $this->db->query('select id, firm_id from billing_template where firm_id = "'.$check_in_use_user_firm[0]["firm_id"].'"');

            if (!$query->num_rows())
            {
                $master_billing_query = $this->db->query('select * from billing_master_template');

                $master_billing_query = $master_billing_query->result_array();

                for($y = 0; $y < count($master_billing_query); $y++)
                {
                    $billing_template["firm_id"] = $check_in_use_user_firm[0]["firm_id"];
                    $billing_template["service"] = $master_billing_query[$y]["service"];
                    $billing_template["invoice_description"] = $master_billing_query[$y]["invoice_description"];
                    $billing_template["amount"] = $master_billing_query[$y]["amount"];
                    $billing_template["frequency"] = $master_billing_query[$y]["frequency"];

                    $this->db->insert("billing_template",$billing_template);
                }
            }
        }

        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
    }

    public function deleteFile($id)
    {
        $files_id = array();
        array_push($files_id, $id);
        $this->session->set_userdata(array(
            'logo_id'  =>  $files_id,
        ));

        echo json_encode($this->session->userdata('logo_id'));
    }

    public function add_firm()
    {
    	$insert_id = '';
    	
        $this->form_validation->set_rules('registration_no', 'Registration No', 'required');
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('email[]', 'Email', 'valid_email');
		$this->form_validation->set_rules('telephone[]', 'Telephone', 'numeric');
		$this->form_validation->set_rules('fax[]', 'Fax', 'numeric');

        if(isset($_POST['postal_code']))
        {
            $this->form_validation->set_rules('postal_code', 'Postal Code', 'required|numeric');
        }
        if(isset($_POST['street_name']))
        {
            $this->form_validation->set_rules('street_name', 'Street Name', 'required');
        }

        if(isset($_POST['foreign_address1']))
        {
            $this->form_validation->set_rules('foreign_address1', 'Foreign Address', 'required');
        }

        foreach (array_values($_POST['hidden_telephone']) as $key => $value) 
        {
            if(count($_POST['hidden_telephone']) == 1)
            {
                if($value==null){
                    
                    $validate_telephone = FALSE;
                    break;
                }
            }
            $validate_telephone = TRUE;
        }

        foreach ($_POST['email'] as $key => $value) 
        {
            if(count($_POST['email']) == 1)
            {
                if($value==null)
                {
                    $validate_email = FALSE;
                    break;
                }
            }
            $validate_email = TRUE;
        }

        if(count($_POST['hidden_fax']) > 1 && $_POST['fax_primary'] == null)
        {
            $validate_fax_primary = FALSE;
        }
        else
        {
            $validate_fax_primary = TRUE;
        }

        if(count($_POST['hidden_telephone']) > 1 && $_POST['telephone_primary'] == null)
        {
            $validate_telephone_primary = FALSE;
        }
        else
        {
            $validate_telephone_primary = TRUE;
        }

        if(count($_POST['email']) > 1 && $_POST['email_primary'] == null)
        {
            $validate_email_primary = FALSE;
        }
        else
        {
            $validate_email_primary = TRUE;
        }

		if ($this->form_validation->run() == FALSE || $validate_telephone == FALSE || $validate_email == FALSE || $validate_telephone_primary == FALSE ||  $validate_fax_primary == FALSE || $validate_email_primary == FALSE || $_POST['firm_currency'] == 0 || $_POST['firm_jurisdiction'] == 0)
		{
			$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$bc = array(array('link' => '#', 'page' => lang('Add Clients')));
	        $meta = array('page_title' => lang('Add Clients'), 'bc' => $bc, 'page_name' => 'Add Clients');

            if($validate_fax_primary == FALSE)
            {
                $validate_fax_field = "Please set the primary field.";
            }
            else
            {
                $validate_fax_field = strip_tags(form_error('fax[]'));
            }

            if($validate_telephone == FALSE || $validate_telephone_primary == FALSE)
            {
                if($validate_telephone == FALSE)
                {
                    $validate_telephone_field = "The Telephone field is required.";
                }
                else if($validate_telephone_primary == FALSE)
                {
                    $validate_telephone_field = "Please set the primary field.";
                }
            }
            else
            {
                $validate_telephone_field = strip_tags(form_error('telephone[]'));
            }

            if($validate_email == FALSE || $validate_email_primary == FALSE)
            {
                if($validate_email == FALSE)
                {
                    $validate_email_field = "The Email field is required.";
                }
                else
                {
                    $validate_email_field = "Please set the primary field.";
                }
            }
            else
            {
                $validate_email_field = strip_tags(form_error('email[]'));
            }

            if($_POST['firm_currency'] == 0)
            {
                $validate_currency_field = "The Currency field is required.";
            }
            else
            {
                $validate_currency_field = '';
            }

            if($_POST['firm_jurisdiction'] == 0)
            {
                $validate_jurisdiction_field = "The Jurisdiction field is required.";
            }
            else
            {
                $validate_jurisdiction_field = '';
            }

			$arr = array(
                'registration_no' => strip_tags(form_error('registration_no')),
                'name' => strip_tags(form_error('name')),
                'email' => $validate_email_field,
                'postal_code' => strip_tags(form_error('postal_code')),
                'street_name' => strip_tags(form_error('street_name')),
                'telephone' => $validate_telephone_field,
                'fax' => $validate_fax_field,
                'foreign_address1' => strip_tags(form_error('foreign_address1')),
                'firm_currency' => $validate_currency_field,
                'firm_jurisdiction' => $validate_jurisdiction_field,
            );

            echo json_encode(array("Status" => 0, "error" => $arr, 'message' => 'Please complete all required field', 'title' => 'Error'));
		}
		else
		{	
            $firm['registration_no'] = $_POST['registration_no'];
            $firm['name'] = $_POST['name'];
            $firm['branch_name'] = $_POST['branch_name'];
            $firm['url'] = $_POST['url'];
            $firm['address_type'] = $_POST['address_type'];
            $firm['firm_currency'] = $_POST['firm_currency'];
            $firm['jurisdiction_id'] = $_POST['firm_jurisdiction'];

            $refresh = false;
            $gst_firm_id = $_POST["gst_firm_id"];
            $register_date = $_POST["register_date"];
            $deregister_date = $_POST["deregister_date"];

            if($_POST['branch_name'] != "")
            {
                $branch_name = "(".$_POST['branch_name'].")";
            }
            else
            {
                $branch_name = "";
            }

            if(isset($_POST['postal_code']))
            {
                $firm['postal_code'] = $_POST['postal_code'];
            }
            else
            {
                $firm['postal_code'] = "";
            }

            if(isset($_POST['street_name']))
            {
                $firm['street_name'] = $_POST['street_name'];
            }
            else
            {
                $firm['street_name'] = "";
            }
            $firm['building_name']=$_POST['building_name'];
            $firm['unit_no1']=$_POST['unit_no1'];
            $firm['unit_no2']=$_POST['unit_no2'];

            if(isset($_POST['foreign_address1']))
            {
                $firm['foreign_address1'] = $_POST['foreign_address1'];
            }
            else
            {
                $firm['foreign_address1'] = "";
            }
            if(isset($_POST['foreign_address2']))
            {
                $firm['foreign_address2'] = $_POST['foreign_address2'];
            }
            else
            {
                $firm['foreign_address2'] = "";
            }
            if(isset($_POST['foreign_address3']))
            {
                $firm['foreign_address3'] = $_POST['foreign_address3'];
            }
            else
            {
                $firm['foreign_address3'] = "";
            }

            if(!empty($_POST['gst_checkbox']))
            {
                $number_of_array = count($register_date) - 1;
                if($deregister_date[$number_of_array] == "")
                {
            	   $firm['gst_checkbox']=($_POST['gst_checkbox']=="1") ? 1 : 0;
                }
                else
                {
                    $firm['gst_checkbox'] = 0;
                    $refresh = true;
                }
            }
            else
            {
            	$firm['gst_checkbox'] = 0;
            }

            $q = $this->db->get_where("firm", array("id" => $_POST["firm_id"]));

            if (!$q->num_rows())
			{
                $firm['previous_gst']=0;   
                $firm['previous_gst_date']="";

				$this->db->insert("firm",$firm);
				$insert_id = $this->db->insert_id();

                $this->save_audit_trail("Our Firm", "Our Firm", $_POST['name'].$branch_name." firm is added.");

                if($firm['gst_checkbox'] == 1)
                {
                    if(count($register_date) > 0)
                    {   
                        for($p = 0; $p < count($register_date); $p++)
                        {
                            $gst_firm['firm_id'] = $insert_id;
                            if($register_date[$p] != "")
                            {
                                $registerDateArr = explode("/", $register_date[$p]);
                                $newRegisterDate = $registerDateArr[2] . '-' . $registerDateArr[1] . '-' . $registerDateArr[0];
                                $gst_firm['register_date'] = $newRegisterDate;
                                if($deregister_date[$p] != "")
                                {
                                    $deregisterDateArr = explode("/", $deregister_date[$p]);
                                    $newDeregisterDate = $deregisterDateArr[2] . '-' . $deregisterDateArr[1] . '-' . $deregisterDateArr[0];
                                    $gst_firm['deregister_date'] = $newDeregisterDate;
                                }
                                $this->db->insert('gst_firm', $gst_firm);
                            }
                        }
                    }
                }

                for($g = 0; $g < count($_POST['hidden_telephone']); $g++)
                {
                    if($_POST['hidden_telephone'][$g] != "")
                    {
                        $telephone['firm_id'] = $insert_id;
                        $telephone['telephone'] = strtoupper($_POST['hidden_telephone'][$g]);
                        if($_POST['telephone_primary'] == $_POST['hidden_telephone'][$g])
                        {
                            $telephone['primary_telephone'] = 1;
                        }
                        else
                        {
                            $telephone['primary_telephone'] = 0;
                        }
                        $this->db->insert('firm_telephone', $telephone);
                    }
                }

                for($g = 0; $g < count($_POST['hidden_fax']); $g++)
                {
                    if($_POST['hidden_fax'][$g] != "")
                    {
                        $fax['firm_id'] = $insert_id;
                        $fax['fax'] = strtoupper($_POST['hidden_fax'][$g]);
                        if($_POST['fax_primary'] == $_POST['hidden_fax'][$g])
                        {
                            $fax['primary_fax'] = 1;
                        }
                        else
                        {
                            $fax['primary_fax'] = 0;
                        }
                        $this->db->insert('firm_fax', $fax);
                    }
                }

                for($g = 0; $g < count($_POST['email']); $g++)
                {
                    if($_POST['email'][$g] != "")
                    {
                        $email['firm_id'] = $insert_id;
                        $email['email'] = $_POST['email'][$g];
                        if($_POST['email_primary'] == $_POST['email'][$g])
                        {
                            $email['primary_email'] = 1;
                        }
                        else
                        {
                            $email['primary_email'] = 0;
                        }
                        $this->db->insert('firm_email', $email);
                    }
                }

                $this->session->set_userdata(array(
                    'firm_id'  =>  $insert_id,
                ));

                $this->session->set_userdata(array(
                    'submit_firm_id'  =>  $insert_id,
                )); 

                if ($_SESSION['group_id'] ==2 || $_SESSION['group_id'] ==3) 
                {
                    $user_firm['user_admin_code_id']=$this->session->userdata('user_admin_code_id');
                    $user_firm['user_id']=$this->session->userdata('user_id');
                    $user_firm['firm_id']=$insert_id;
                    $user_firm["client_module"] = "full";
                    $user_firm["person_module"] = "full";
                    $user_firm["document_module"] = "full";
                    $user_firm["report_module"] = "full";
                    $user_firm["billing_module"] = "full";

                    $check_user_firm = $this->db->get_where("user_firm", array("user_id" => $this->session->userdata('user_id')));

                    if (!$check_user_firm->num_rows())
                    {
                        $user_firm['default_company']=1;
                        $user_firm['in_use']=1;
                    }

                    $this->db->insert("user_firm",$user_firm);
                
                    $check_in_use_user_firm = $this->db->get_where("user_firm", array("user_id" => $this->session->userdata('user_id'), "in_use = " => 1));

                    if ($check_in_use_user_firm->num_rows())
                    {
                        $check_in_use_user_firm = $check_in_use_user_firm->result_array();

                        $this->session->set_userdata('firm_id', $check_in_use_user_firm[0]["firm_id"]);

                        $document_master_query = $this->db->query('select id, firm_id from document_master where firm_id = "'.$insert_id.'"');

                        if (!$document_master_query->num_rows())
                        {
                            $document_master_template_query = $this->db->query('select * from document_master_template');

                            $document_master_template_query = $document_master_template_query->result_array();

                            for($y = 0; $y < count($document_master_template_query); $y++)
                            {
                                $document_master["firm_id"] = $insert_id;
                                $document_master["document_name"] = $document_master_template_query[$y]["document_name"];
                                $document_master["triggered_by"] = $document_master_template_query[$y]["triggered_by"];
                                $document_master["document_content"] = $document_master_template_query[$y]["document_content"];

                                $this->db->insert("document_master",$document_master);
                            }
                        }
                    }
                }

                $this->recalculate();
			} 
			else 
			{
				$this->db->update("firm",$firm,array("id" => $_POST["firm_id"]));
                $insert_id = $_POST["firm_id"];
				$this->session->set_userdata(array(
                    'submit_firm_id'  =>  $_POST['firm_id'],
                ));	

                $this->save_audit_trail("Our Firm", "Our Firm", $_POST['name'].$branch_name." firm is edited.");

                $this->db->delete("gst_firm",array('firm_id'=>$_POST['firm_id']));

                if(count($register_date) > 0)
                {
                    for($p = 0; $p < count($register_date); $p++)
                    {
                        $gst_firm['firm_id'] = $_POST['firm_id'];
                        if($register_date[$p] != "")
                        {
                            $registerDateArr = explode("/", $register_date[$p]);
                            $newRegisterDate = $registerDateArr[2] . '-' . $registerDateArr[1] . '-' . $registerDateArr[0];
                            $gst_firm['register_date'] = $newRegisterDate;
                            if($deregister_date[$p] != "")
                            {
                                $deregisterDateArr = explode("/", $deregister_date[$p]);
                                $newDeregisterDate = $deregisterDateArr[2] . '-' . $deregisterDateArr[1] . '-' . $deregisterDateArr[0];
                                $gst_firm['deregister_date'] = $newDeregisterDate;
                            }
                            else
                            {
                                $gst_firm['deregister_date'] = NULL;
                            }
                            $this->db->insert('gst_firm', $gst_firm);
                        }
                    }
                }
                

                $this->db->delete("firm_telephone",array('firm_id'=>$_POST['firm_id']));

                for($g = 0; $g < count($_POST['hidden_telephone']); $g++)
                {
                    if($_POST['hidden_telephone'][$g] != "")
                    {
                        $telephone['firm_id'] = $_POST['firm_id'];
                        $telephone['telephone'] = strtoupper($_POST['hidden_telephone'][$g]);
                        if($_POST['telephone_primary'] == $_POST['hidden_telephone'][$g])
                        {
                            $telephone['primary_telephone'] = 1;
                        }
                        else
                        {
                            $telephone['primary_telephone'] = 0;
                        }
                        $this->db->insert('firm_telephone', $telephone);
                    }
                }

                $this->db->delete("firm_fax",array('firm_id'=>$_POST['firm_id']));

                for($g = 0; $g < count($_POST['hidden_fax']); $g++)
                {
                    if($_POST['hidden_fax'][$g] != "")
                    {
                        $fax['firm_id'] = $_POST['firm_id'];
                        $fax['fax'] = strtoupper($_POST['hidden_fax'][$g]);
                        if($_POST['fax_primary'] == $_POST['hidden_fax'][$g])
                        {
                            $fax['primary_fax'] = 1;
                        }
                        else
                        {
                            $fax['primary_fax'] = 0;
                        }
                        $this->db->insert('firm_fax', $fax);
                    }
                }

                $this->db->delete("firm_email",array('firm_id'=>$_POST['firm_id']));

                for($g = 0; $g < count($_POST['email']); $g++)
                {
                    if($_POST['email'][$g] != "")
                    {
                        $email['firm_id'] = $_POST['firm_id'];
                        $email['email'] = $_POST['email'][$g];
                        if($_POST['email_primary'] == $_POST['email'][$g])
                        {
                            $email['primary_email'] = 1;
                        }
                        else
                        {
                            $email['primary_email'] = 0;
                        }
                        $this->db->insert('firm_email', $email);
                    }
                }
			}

            if($this->session->userdata('logo_id') != null)
            {
                if (count($this->session->userdata('logo_id')) != 0)
                {
                    $logo_id = $this->session->userdata('logo_id');
                    for($i = 0; $i < count($logo_id); $i++)
                    {
                        $files = $this->db->query("select * from firm where id='".$logo_id[$i]."'");
                        $file_info = $files->result_array();

                        unlink("./uploads/images_or_pdf/".$file_info[0]["file_name"]);

                        $logo_info["file_name"] = '';

                        $this->db->update('firm', $logo_info, array('id' => $logo_id[$i]));
                    }
                }
            }
            
			echo json_encode(array("Status" => 1, 'refresh' => $refresh, 'message' => 'Information Updated', 'title' => 'Updated', 'firm_id' => $insert_id));
		}
    }

    public function recalculate()
    {
        $this->db->select("users.id")
                ->from("users")
                ->join('groups', 'users.group_id = groups.id', 'inner')
                ->join('user_firm as a', 'a.user_id = "'.$this->session->userdata("user_id").'"', 'inner')
                ->join('user_firm as b', 'a.firm_id=b.firm_id', 'inner')
                ->where('b.user_id = users.id')
                ->where('b.user_id != "'.$this->session->userdata("user_id").'"')
                ->group_by('users.id');
        $test = $this->db->get();
        $test = $test->result_array();

        $data_user_id = array();

        foreach ($test as $rr) {
            array_push($data_user_id, $rr["id"]);
        }

        $this->db->select("firm_id")
        ->from("user_firm")
        ->where('user_id = "'.$this->session->userdata("user_id").'"');

        $firm_id = $this->db->get();
        $firm_id = $firm_id->result_array();

        $data_firm_id = array();

        foreach ($firm_id as $rows) {
            array_push($data_firm_id, $rows["firm_id"]);
        }

        $user["no_of_user"] = count($data_user_id);
        $user["no_of_firm"] = count($data_firm_id);
        $this->db->where('id', $this->session->userdata("user_id"));
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
                $users["no_of_client"] = count($num_client);
            }
            else
            {
                $users["no_of_client"] = 0;
            }

            $this->db->where('id', $this->session->userdata("user_id"));
            $this->db->update('users',$users);

            $data_user_id = array();

            foreach ($test as $r) {
                array_push($data_user_id, $r["id"]);
            }

            if(count($data_user_id) != 0)
            {  
                $this->db->where_in('id', $data_user_id);
                $this->db->update('users',$users);
            }
        }
    }

    public function uploadFile()
    {
        // //retreive post variables
        // $fileName = $_FILES['uploadlogo']['name'];
        // $fileTempName = $_FILES['uploadlogo']['tmp_name'];

        // $accessKey = $this->config->item('accessKey');
        // $secretKey = $this->config->item('secretKey');

        // $credentials = new Aws\Credentials\Credentials($accessKey, $secretKey);
        // try {
        //     //Create a S3Client
        //     $s3Client = new S3Client([
        //         'credentials' => $credentials,
        //         'region' => 'ap-southeast-1',
        //         'version' => 'latest'
        //     ]);

        //     $result = $s3Client->putObject([
        //         'Bucket' => 'store-upload-file',
        //         'Key' => 'logo/'.$fileName,
        //         'SourceFile' => $fileTempName,
        //     ]);
        // } catch (S3Exception $e) {
        //     echo $e->getMessage() . "\n";
        // }
 

        //Listing all S3 Bucket
        // $buckets = $s3Client->listBuckets();
        // foreach ($buckets['Buckets'] as $bucket) {
        //     echo $bucket['Name'] . "\n";
        // }
        $_FILES['logo']['name'] = $_FILES['uploadlogo']['name'];
        $_FILES['logo']['type'] = $_FILES['uploadlogo']['type'];
        $_FILES['logo']['tmp_name'] = $_FILES['uploadlogo']['tmp_name'];
        $_FILES['logo']['error'] = $_FILES['uploadlogo']['error'];
        $_FILES['logo']['size'] = $_FILES['uploadlogo']['size'];

        $uploadPath = './uploads/logo';
        $config['upload_path'] = $uploadPath;
        $config['allowed_types'] = 'gif|jpg|jpeg|png|ico|icon|image|image|ico';
        
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if($this->upload->do_upload('logo'))
        {
            $fileData = $this->upload->data();
            $firm_id = $this->session->userdata('submit_firm_id');
            $uploadData['file_name'] = $fileData['file_name'];

            $files = $this->db->query("select * from firm where id='".$firm_id."'");
            $file_info = $files->result_array();

            unlink("./uploads/logo/".$file_info[0]["file_name"]);
        }
    
        if(!empty($uploadData))
        {
        	$this->db->update("firm",$uploadData,array("id" => $firm_id));
        }

        if(!isset($fileData))
        {
            $fileData['file_name'] = false;
        }

        echo json_encode($fileData['file_name']);
    }

    /*
     * file value and type check during validation
     */
    public function file_check($str){
        $allowed_mime_type_arr = array('image/jpeg','image/pjpeg','image/png','image/x-png'); //'application/pdf','image/gif',
        $mime = get_mime_by_extension($_FILES['attachment']['name'][0]);
        if(isset($_FILES['attachment']['name'][0]) && $_FILES['attachment']['name'][0]!=""){
            if(in_array($mime, $allowed_mime_type_arr)){
                return true;
            }else{
                $this->form_validation->set_message('file_check', 'Please select only jpg or png file.');
                return false;
            }
        }
    }

    public function add_bank_info()
    {
        for($i = 0; $i < count($_POST['bank_id']); $i++ )
        {   
            $this->form_validation->set_rules('bank_id['.$i.']', 'Bank Id', 'required');
            $this->form_validation->set_rules('banker['.$i.']', 'Banker', 'required');
            $this->form_validation->set_rules('account_number['.$i.']', 'Account Number', 'required');
            $this->form_validation->set_rules('bank_code['.$i.']', 'Bank Code', 'required');
            $this->form_validation->set_rules('swift_code['.$i.']', 'Swift Code', 'required');
            $this->form_validation->set_rules('attachment[]', '', 'callback_file_check');

            if ($this->form_validation->run() == FALSE || $_POST['currency'][$i] == 0)
            {
                $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

                if($_POST['currency'][$i] == 0)
                {
                    $currency_error = "The Currency field is required.";
                }
                else
                {
                    $currency_error = "";
                }

                $error = array(
                    'bank_id' => strip_tags(form_error('bank_id['.$i.']')),
                    'banker' => strip_tags(form_error('banker['.$i.']')),
                    'account_number' => strip_tags(form_error('account_number['.$i.']')),
                    'bank_code' => strip_tags(form_error('bank_code['.$i.']')),
                    'swift_code' => strip_tags(form_error('swift_code['.$i.']')),
                    'currency' => $currency_error,
                    'attachment' => strip_tags(form_error('attachment[]')),
                );

                echo json_encode(array("Status" => 0, 'message' => 'Please complete all required field.', 'title' => 'Error', "error" => $error));
            }
            else
            {
                $firm_result = $this->db->query("select * from firm where id = '".$_POST['firm_id'][$i]."'");
                $firm_array = $firm_result->result_array();

                if($firm_array[0]["branch_name"] != "")
                {
                    $branch_name = "(".$firm_array[0]["branch_name"].")";
                }
                else
                {
                    $branch_name = "";
                }

                $pv_attachment = array();
                $data['firm_id'] = $_POST['firm_id'][$i];
                $data['bank_id']=$_POST['bank_id'][$i];
                $data['banker']=$_POST['banker'][$i];
                $data['account_number']=$_POST['account_number'][$i];
                $data['bank_code']=$_POST['bank_code'][$i];
                $data['swift_code']=$_POST['swift_code'][$i];
                $data['currency']=$_POST['currency'][$i];

                $_FILES['uploadimage']['name'] = $_FILES['attachment']['name'][$i];
                $_FILES['uploadimage']['type'] = $_FILES['attachment']['type'][$i];
                $_FILES['uploadimage']['tmp_name'] = $_FILES['attachment']['tmp_name'][$i];
                $_FILES['uploadimage']['error'] = $_FILES['attachment']['error'][$i];
                $_FILES['uploadimage']['size'] = $_FILES['attachment']['size'][$i];

                $uploadPath = './uploads/billing_qr_code';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = '*';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('uploadimage'))
                {
                    $fileData = $this->upload->data();

                    $pv_attachment[] = $fileData['file_name'];
                }

                $attachment = json_encode($pv_attachment);

                if($_POST["hidden_attachment"][$i] != "")
                {
                    $data['qr_code'] = $_POST["hidden_attachment"][$i];
                }
                else
                {
                    $data['qr_code'] = $attachment;
                }

                $q = $this->db->get_where("bank_info", array("id" => $_POST['bank_info_id'][$i]));

                if (!$q->num_rows())
                {   
                    $check_bank_id = $this->db->get_where("bank_info", array("bank_id" => $_POST['bank_id'][$i], "firm_id" => $_POST['firm_id'][$i]));

                    if (!$check_bank_id->num_rows())
                    {
                        $this->db->insert("bank_info",$data);
                        $insert_bank_info_id = $this->db->insert_id();

                        $this->save_audit_trail("Our Firm", "Bank Info", $firm_array[0]["name"].$branch_name." firm bank info is added.");

                        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_bank_info_id" => $insert_bank_info_id));
                    }
                    else
                    {
                        echo json_encode(array("Status" => 2, 'message' => 'Cannot have same Bank ID under this firm.', 'title' => 'Error'));
                    }

                }
                else
                {
                    $this->db->update("bank_info",$data,array("id" => $_POST['bank_info_id'][$i]));

                    $this->save_audit_trail("Our Firm", "Bank Info", $firm_array[0]["name"].$branch_name." firm bank info is edited.");

                    echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
                }
            }
        }
    }

    public function delete_bank_info ()
    {
        $id = $_POST["bank_info_id"];

        $firm_result = $this->db->query("select firm.* from bank_info left join firm on firm.id = bank_info.firm_id where bank_info.id = '".$_POST["bank_info_id"]."'");
        $firm_array = $firm_result->result_array();

        if($firm_array[0]["branch_name"] != "")
        {
            $branch_name = "(".$firm_array[0]["branch_name"].")";
        }
        else
        {
            $branch_name = "";
        }

        $this->db->delete("bank_info",array('id'=>$id));

        $this->save_audit_trail("Our Firm", "Bank Info", $firm_array[0]["name"].$branch_name." firm bank info is deleted.");

        echo json_encode(array("Status" => 1));         
    }

    public function save_audit_trail($modules, $events, $actions)
    {
        $secretary_audit_trail["user_id"] = $this->session->userdata("user_id");
        $secretary_audit_trail["modules"] = $modules;
        $secretary_audit_trail["events"] = $events;
        $secretary_audit_trail["actions"] = $actions;

        $this->db->insert("secretary_audit_trail",$secretary_audit_trail);
    }
}
