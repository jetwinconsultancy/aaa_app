<?php defined('BASEPATH') OR exit('No direct script access allowed');

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
    }

    public function index()
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $bc = array(array('link' => '#', 'page' => lang('Our Firm')));
        $meta = array('page_title' => lang('Our Firm'), 'bc' => $bc, 'page_name' => 'Our Firm');
		/*$this->data['sharetype'] = $this->master_model->get_all_share_type();
		$this->data['currency'] = $this->master_model->get_all_currency();
		$this->data['kolom'] = $this->master_model->get_all_kolom();
		$this->data['citizen'] = $this->master_model->get_all_citizen();
		$this->data['typeofdoc'] = $this->master_model->get_all_typeofdoc();
		$this->data['doccategory'] = $this->master_model->get_all_doccategory();*/
		/*$this->data['sharetype'] = $this->master_model->get_all_share_type();*/
		

		//echo json_encode($_SESSION['group_id']);
        if (isset($_POST['search'])) 
        {
            $term = $_POST['search'];
            
        }

        if($_SESSION['group_id'] ==2 || $_SESSION['group_id'] ==3 || $_SESSION['group_id'] ==4) 
        {   
            if($term != null)
            {
                $this->data['all_firm'] = $this->master_model->get_all_firm_info($term);
            }
            else
            {
                $this->data['all_firm'] = $this->master_model->get_all_firm_info();
            }
            //echo json_encode($this->data['all_firm']);
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

        $p = [];

        $p[0]->local_status = 'checked';
        $p[0]->address_type = 'Local';
        $p[0]->gst_checkbox = null;

        $this->data['firm'] = $p;

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Our Firm', base_url('our_firm'));
        $this->mybreadcrumb->add('Create Our Firm', base_url());
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('our_firm.php', $meta, $this->data);
    }

    public function edit($id, $tab = null)
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $bc = array(array('link' => '#', 'page' => lang('Our Firm')));
        $meta = array('page_title' => lang('Our Firm'), 'bc' => $bc, 'page_name' => 'Our Firm');

        $this->data['firm'] = $this->master_model->edit_firm_info($id);
        $this->data['bank_info'] = $this->master_model->get_bank_info($id);
        $this->data['template'] = $this->db_model->get_all_template();
        $this->data['firm_id'] = $id;
        $this->data['tab'] = $tab;

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

                    echo json_encode(array("Status" => 1, 'message' => 'Must at least one comapny is in use company.', 'title' => 'Error'));
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

    public function deleteFile($id)
    {
        //if (count($this->session->userdata('files_id')) == 0)
        //{
            $files_id = array();
            array_push($files_id, $id);
            $this->session->set_userdata(array(
                'logo_id'  =>  $files_id,
            ));
            //array_push($this->session->userdata('files_id'), $id);
        //}
        /*else
        {

        }*/
        /*$files_id = array();
        $this->session->set_userdata(array(
            'files_id'  =>  $files_id,
        ));*/

        echo json_encode($this->session->userdata('logo_id'));
    }

    public function add_firm()
    {
    	

    	$insert_id = '';
    	/*$fileData = array();
	        // File upload script

        $config['upload_path']   = './uploads/logo';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']      = '1000';
        $config['max_width']     = '1920';
        $config['max_height']    = '1080';
        $config['overwrite']     = true;*/

        //$this->load->library('upload', $config);
        $this->form_validation->set_rules('registration_no', 'Registration No', 'required');
		$this->form_validation->set_rules('name', 'Name', 'required');
		// $this->form_validation->set_rules('postal_code', 'Postal Code', 'required');
		// $this->form_validation->set_rules('street_name', 'Street Name', 'required');
		$this->form_validation->set_rules('email[]', 'Email', 'valid_email');
		/*$this->form_validation->set_rules('username', 'Username', 'required');*/
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

        foreach (array_values($_POST['hidden_telephone']) as $key => $value) {
            //echo json_encode($value);
            if(count($_POST['hidden_telephone']) == 1)
            {

                    //echo json_encode($value[1]);
                if($value==null){
                    
                    $validate_telephone = FALSE;
                    break;
                }
            }
            $validate_telephone = TRUE;
        }

        foreach ($_POST['email'] as $key => $value) {
            if(count($_POST['email']) == 1)
            {
                if($value==null){
                    //echo json_encode($mobile_no);
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

        if(isset($_POST["no_gst_date"]))
        {
            $this->form_validation->set_rules('no_gst_date', 'GST Date', 'required');
        }
        if(isset($_POST["gst_value"]))
        {
            $this->form_validation->set_rules('gst_value', 'GST', 'required');
        }
        if(isset($_POST["gst_date"]))
        {
            $this->form_validation->set_rules('gst_date', 'GST Date', 'required');
        }
		//$this->form_validation->set_rules('url', 'Url', 'required');

		//echo json_encode(empty($_POST['gst_checkbox']));
		if ($this->form_validation->run() == FALSE || $validate_telephone == FALSE || $validate_email == FALSE || $validate_telephone_primary == FALSE ||  $validate_fax_primary == FALSE || $validate_email_primary == FALSE)
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
            //echo json_encode($validate_telephone);
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


			$arr = array(
                'registration_no' => strip_tags(form_error('registration_no')),
                'name' => strip_tags(form_error('name')),
                'email' => $validate_email_field,
                'postal_code' => strip_tags(form_error('postal_code')),
                'street_name' => strip_tags(form_error('street_name')),
                'telephone' => $validate_telephone_field,
                'fax' => $validate_fax_field,
                'no_gst_date' => strip_tags(form_error('no_gst_date')),
                'gst_value' => strip_tags(form_error('gst_value')),
                'gst_date' => strip_tags(form_error('gst_date')),
                'foreign_address1' => strip_tags(form_error('foreign_address1')),
                //'url' => strip_tags(form_error('url')),
            );
            //$this->page_construct('addpersonprofile.php', $meta, $this->data);


            echo json_encode(array("Status" => 0, "error" => $arr, 'message' => 'Please complete all required field', 'title' => 'Error'));
		}
		else
		{	//echo json_encode($_POST);
			//echo json_encode($this->upload->do_upload('logo'));
				/*if($this->upload->do_upload('logo'))
				{
					$data = $this->upload->data(); // Get the file data

	                $fileData[] = $data; // It's an array with many data
	                // Interate throught the data to work with them
	                foreach ($fileData as $file) {
	                    $file_data = $file;
	                }

	                $firm['file_name']=$file_data['file_name'];
                	$firm['file_ext']=$file_data['file_ext'];	
				}
				else
				{
					$firm['file_name']="";
                	$firm['file_ext']="";
				}*/

                //echo json_encode($data);
                $firm['registration_no']=$_POST['registration_no'];
                $firm['name']=$_POST['name'];
                // $firm['telephone']=$_POST['telephone'];
                // $firm['fax']=$_POST['fax'];
                // $firm['email']=$_POST['email'];
                $firm['url']=$_POST['url'];
                // $firm['postal_code']=$_POST['postal_code'];
                // $firm['street_name']=$_POST['street_name'];
                $firm['address_type'] = $_POST['address_type'];
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

                if(isset($_POST['no_gst_date']))
                {
                    $firm['no_gst_date']=$_POST['no_gst_date'];
                }
                else
                {
                    $firm['no_gst_date']="";
                }

                if(isset($_POST['gst_value']))
                {
                    $firm['gst']=$_POST['gst_value'];
                }
                else
                {
                    $firm['gst']="";
                }
                	
                if(isset($_POST['gst_date']))
                {
                    $firm['gst_date']=$_POST['gst_date'];
                }
                else
                {
                    $firm['gst_date']="";
                }

                if(!empty($_POST['gst_checkbox']))
                {
                	$firm['gst_checkbox']=($_POST['gst_checkbox']=="1") ? 1 : 0;
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

                            // $query = $this->db->query('select id, firm_id from billing_template where firm_id = "'.$check_in_use_user_firm[0]["firm_id"].'"');

                            // if (!$query->num_rows())
                            // {
                            //     $master_billing_query = $this->db->query('select * from billing_master_template');

                            //     $master_billing_query = $master_billing_query->result_array();

                            //     for($y = 0; $y < count($master_billing_query); $y++)
                            //     {
                            //         $billing_template["firm_id"] = $check_in_use_user_firm[0]["firm_id"];
                            //         $billing_template["service"] = $master_billing_query[$y]["service"];
                            //         $billing_template["invoice_description"] = $master_billing_query[$y]["invoice_description"];
                            //         $billing_template["amount"] = $master_billing_query[$y]["amount"];
                            //         $billing_template["frequency"] = $master_billing_query[$y]["frequency"];

                            //         $this->db->insert("billing_template",$billing_template);
                            //     }
                            // }

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
                    if ($_SESSION['group_id'] ==2 || $_SESSION['group_id'] ==3) 
                    {
                        $old_gst = $q->result()[0]->gst;
                        $old_gst_date = $q->result()[0]->gst_date;
                        $previous_gst = $q->result()[0]->previous_gst;
                        $previous_gst_date = $q->result()[0]->previous_gst_date;
                        
                        if(isset($_POST['gst_date']) && isset($_POST['gst_value']))
                        {
                            if($_POST['gst_value'] != null && $_POST['gst_date'] != null)
                            {
                                /*if($old_gst != $_POST['gst_value'] && $old_gst_date == $_POST['gst_date'])
                                {
                                    $firm['previous_gst'] = $old_gst;   
                                }
                                
                                if($old_gst == $_POST['gst_value'] && $old_gst_date != $_POST['gst_date'])
                                {
                                    $firm['previous_gst_date'] = $old_gst_date;
                                }*/

                                if($old_gst != $_POST['gst_value'] && $old_gst_date != $_POST['gst_date'])
                                {
                                    $firm['previous_gst'] = $old_gst;  
                                    $firm['previous_gst_date'] = $old_gst_date;
                                }
                                elseif($previous_gst_date == $_POST['gst_date'] || $firm['gst_checkbox'] == 0)
                                {
                                    $firm['previous_gst']=0;   
                                    $firm['previous_gst_date']="";
                                }  
                                

                            }
                        }
                        else
                        {
                            if($firm['gst_checkbox'] == 0)
                            {
                                $firm['previous_gst']=0;   
                                $firm['previous_gst_date']="";
                            }  
                        }

                        // $query_address = $this->db->query("select * from client where registered_address = '1' AND firm_id = '".$_POST["firm_id"]."'");

                        // if ($query_address->num_rows() > 0) 
                        // {
                        //     $change_address['postal_code']=$_POST['postal_code'];
                        //     $change_address['street_name']=$_POST['street_name'];
                        //     $change_address['building_name']=$_POST['building_name'];
                        //     $change_address['unit_no1']=$_POST['unit_no1'];
                        //     $change_address['unit_no2']=$_POST['unit_no2'];

                        //     $query_address = $query_address->result_array();

                        //     for($r = 0; $r < count($query_address); $r++)
                        //     {
                        //         if($query_address[$r]["postal_code"] != $_POST['postal_code'] || $query_address[$r]["street_name"] != $_POST['street_name'] || $query_address[$r]["building_name"] != $_POST['building_name'] || $query_address[$r]["unit_no1"] != $_POST['unit_no1'] || $query_address[$r]["unit_no2"] != $_POST['unit_no2'])
                        //         {
                        //             /*require_once('Masterclient.php');
                        //             $masterclient = new Masterclient();
                        //             $masterclient->create_document("change_address", $query_address[$r]['company_code']);*/
                        //             $this->db->update("client",$change_address,array("id" => $query_address[$r]["id"]));

                        //             $result = $this->db->query("select * from client_billing_info where service = 3 AND company_code='".$query_address[$r]["company_code"]."'");

                        //             $company_code = $query_address[$r]["company_code"];
                        //             //$billing['company_code'] = $query_address[$r]["company_code"];

                        //             $result = $result->result_array();
                        //             //echo json_encode($result);
                        //             if($result) 
                        //             {
                        //                 //echo json_encode($result[0]['service']);
                        //                 $now = getDate();

                        //                 $current_date = DATE("Y-m-d",now());

                        //                 $billing_result = $this->db->query("select * from billing where date_format(created_at, '%Y-%m-%d') = '".$current_date."' AND company_code='".$company_code."' AND status != 1");

                        //                 $billing_result = $billing_result->result_array();

                        //                 $client = $this->db->query("select * from client where company_code='".$company_code."'");

                        //                 $client = $client->result_array();

                        //                 $firm_info = $this->db->query("select * from firm where id = '".$client[0]["firm_id"]."'");

                        //                 $firm_info = $firm_info->result_array();

                        //                 //echo json_encode($billing_result);

                        //                 if($firm_info[0]["gst_checkbox"] == 1)
                        //                 {
                        //                     if($firm_info[0]["gst_date"] != null)
                        //                     {
                        //                         $array = explode('/', $firm_info[0]["gst_date"]);
                        //                         $tmp = $array[0];
                        //                         $array[0] = $array[1];
                        //                         $array[1] = $tmp;
                        //                         unset($tmp);
                        //                         $gst_date = implode('/', $array);
                        //                         $time = strtotime($gst_date);
                        //                         $gst_date = date('Y-m-d',$time);
                        //                         $gst_date = strtotime($gst_date);
                        //                     }

                        //                     if($firm_info[0]["previous_gst_date"] != null)
                        //                     {
                        //                         $array = explode('/', $firm_info[0]["previous_gst_date"]);
                        //                         $tmp = $array[0];
                        //                         $array[0] = $array[1];
                        //                         $array[1] = $tmp;
                        //                         unset($tmp);
                        //                         $previous_gst_date = implode('/', $array);
                        //                         $time = strtotime($previous_gst_date);
                        //                         $previous_gst_date = date('Y-m-d',$time);
                        //                         $previous_gst_date = strtotime($gst_date);
                        //                     }

                        //                     /*echo json_encode($firm_info[0]["previous_gst_date"]);
                        //                     echo json_encode($firm_info[0]["gst_date"]);*/
                        //                     $invoice_date = DATE("Y-m-d",now());
                        //                     $invoice_date = strtotime($invoice_date);

                        //                     if($previous_gst_date == null && $gst_date != null)
                        //                     {
                        //                         if($invoice_date >= $gst_date)
                        //                         {
                        //                             $billing_service['gst_rate'] = $firm_info[0]["gst"];
                        //                         }
                        //                         else
                        //                         {
                        //                             $billing_service['gst_rate'] = 0;
                        //                         }
                        //                     }
                        //                     else
                        //                     {
                        //                         if($previous_gst_date == $gst_date)
                        //                         {
                        //                             $billing_service['gst_rate'] = $firm_info[0]["gst"];
                        //                         }
                        //                         else if($previous_gst_date > $gst_date)
                        //                         {
                        //                             if($previous_gst_date > $invoice_date && $invoice_date >= $gst_date)
                        //                             {
                        //                                 $billing_service['gst_rate'] = $firm_info[0]["gst"];
                        //                             }
                        //                             else if($invoice_date >= $previous_gst_date)
                        //                             {
                        //                                 $billing_service['gst_rate'] = $firm_info[0]["previous_gst"];
                        //                             }
                        //                             else
                        //                             {
                        //                                 $billing_service['gst_rate'] = 0;
                        //                             }
                        //                         }
                        //                         else if($gst_date > $previous_gst_date)
                        //                         {
                        //                             if($gst_date > $invoice_date && $invoice_date >= $previous_gst_date)
                        //                             {
                        //                                 $billing_service['gst_rate'] = $firm_info[0]["previous_gst"];
                        //                             }
                        //                             else if($invoice_date >= $gst_date)
                        //                             {
                        //                                 $billing_service['gst_rate'] = $firm_info[0]["gst"];
                        //                             }
                        //                             else
                        //                             {
                        //                                 $billing_service['gst_rate'] = 0;
                        //                             }
                        //                         }
                        //                     }
                                            
                        //                 }
                        //                 else
                        //                 {
                        //                     $billing_service['gst_rate'] = 0;
                        //                 }
                        //                 //echo json_encode($company_code);
                        //                 if($billing_result)
                        //                 {

                        //                     $billing['amount'] = $billing_result[0]['amount'] + ((1+($billing_service['gst_rate'] / 100)) * $result[0]['amount']);
                        //                     $billing['outstanding'] = $billing_result[0]['outstanding'] + ((1+($billing_service['gst_rate'] / 100)) * $result[0]['amount']);

                        //                     $this->db->update("billing",$billing,array("id" => $billing_result[0]['id']));

                        //                     /*$billing_receipt_record['previous_outstanding'] = $billing_result[0]['amount'] + ((1+($billing_service['gst_rate'] / 100)) * $result[0]['amount']);
                        //                     $this->db->update("billing_receipt_record",$billing_receipt_record,array("billing_id" => $billing_result[0]['id']));*/

                        //                     $billing_service['billing_id'] = $billing_result[0]['id'];
                        //                 }
                        //                 else
                        //                 {
                        //                     //$num_row_billing_table = $this->db->query("select COUNT(*) from billing where company_code='".$company_code."'");
                        //                     //echo json_encode($num_row_billing_table->result_array());
                        //                     $query_invoice_no = $this->db->query("select MAX(CAST(SUBSTRING(invoice_no,10, length(invoice_no)-9) AS UNSIGNED)) as invoice_no from billing");

                        //                     //echo json_encode($query_test);

                        //                     if ($query_invoice_no->num_rows() > 0) 
                        //                     {
                        //                         $query_invoice_no = $query_invoice_no->result_array();
                        //                         //$array_invoice_no = explode('-', $query_invoice_no[0]["invoice_no"]);
                        //                         $last_section_invoice_no = (int)$query_invoice_no[0]["invoice_no"] + 1;
                        //                         $number = date("Y")."-ABC-".$last_section_invoice_no;

                        //                     }
                        //                     else
                        //                     {
                        //                         $number = date("Y")."-ABC-1";
                        //                     }

                        //                     /*$number = sprintf('%02d', $now[0]);
                        //                     $number = 'INV - '.$number;*/
                        //                     $billing['firm_id'] = $client[0]["firm_id"];
                        //                     $billing['rate'] = 1.0000;
                        //                     $billing['invoice_no'] = $number;
                        //                     $billing['currency_id'] = 1;
                        //                     $billing['company_code'] = $company_code;
                        //                     $billing['invoice_date'] = DATE("d/m/Y",now());
                        //                     $billing['amount'] = ((1+($billing_service['gst_rate'] / 100)) * $result[0]['amount']);
                        //                     $billing['outstanding'] = ((1+($billing_service['gst_rate'] / 100)) * $result[0]['amount']);

                        //                     //$billing_service['client_billing_info_id'] = $result[0]['client_billing_info_id'];
                        //                     //echo json_encode($billing);
                        //                     $this->db->insert("billing",$billing);
                        //                     $billing_service['billing_id'] = $this->db->insert_id();

                                            

                        //                 }

                        //                 $billing_service['invoice_date'] = DATE("d/m/Y",now());
                        //                 $billing_service['client_billing_info_id'] = $result[0]['client_billing_info_id'];
                        //                 $billing_service['invoice_description'] = $result[0]['invoice_description'];
                        //                 $billing_service['amount'] = $result[0]['amount'];

                        //                 $this->db->insert("billing_service",$billing_service);
                                        

                        //                 //echo true;

                        //             }
                        //         }
                        //     }
                        // }
                    }

					$this->db->update("firm",$firm,array("id" => $_POST["firm_id"]));
                    $insert_id = $_POST["firm_id"];
					$this->session->set_userdata(array(
                        'submit_firm_id'  =>  $_POST['firm_id'],
                    ));	

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
                if (count($this->session->userdata('logo_id')) != 0)
                {
                    //echo json_encode($this->session->userdata('logo_id'));
                    $logo_id = $this->session->userdata('logo_id');
                    for($i = 0; $i < count($logo_id); $i++)
                    {
                        $files = $this->db->query("select * from firm where id='".$logo_id[$i]."'");
                        $file_info = $files->result_array();

                        //$this->db->where('id', $logo_id[$i]);

                        unlink("./uploads/images_or_pdf/".$file_info[0]["file_name"]);

                        $logo_info["file_name"] = '';

                        $this->db->update('firm', $logo_info, array('id' => $logo_id[$i]));

                        //echo json_encode(unlink("./uploads/images_or_pdf/".$file_info[0]["file_name"]));
                    }
                }


            if ($_SESSION['group_id'] ==2 || $_SESSION['group_id'] ==3) 
            {
                $this->db->select('firm.*')
                        ->from('firm')
                        ->where('firm.id = '.$_POST["firm_id"]);
                        //->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
                        //->where('user_firm.user_id = '.$this->session->userdata('user_id'))
                        //->where('user_firm.in_use = 1');

                //$firm = $this->db->query("select * from firm where user_id = '".$this->session->userdata('user_id')."'");
                $firm = $this->db->get();
                $firm = $firm->result_array();

                if($firm[0]["gst_checkbox"] == 1)
                {
                    if($firm[0]["gst_date"] != null)
                    {
                        $array = explode('/', $firm[0]["gst_date"]);
                        $tmp = $array[0];
                        $array[0] = $array[1];
                        $array[1] = $tmp;
                        unset($tmp);
                        $gst_date = implode('/', $array);
                        $time = strtotime($gst_date);
                        $gst_date = date('Y-m-d',$time);
                        $str_to_gst_date = strtotime($gst_date);
                    }

                    if($firm[0]["previous_gst_date"] != null)
                    {
                        $array = explode('/', $firm[0]["previous_gst_date"]);
                        $tmp = $array[0];
                        $array[0] = $array[1];
                        $array[1] = $tmp;
                        unset($tmp);
                        $previous_gst_date = implode('/', $array);
                        $time = strtotime($previous_gst_date);
                        $previous_gst_date = date('Y-m-d',$time);
                        $str_to_previous_gst_date = strtotime($previous_gst_date);
                    }

                    $take_billing_id = $this->db->query("select id from billing where firm_id = '".$firm[0]["id"]."'");

                    $take_billing_id = $take_billing_id->result_array();

                    if($str_to_previous_gst_date == null && $str_to_gst_date != null)
                    {
                        /*$this->db->select('client_charges.*, currency.currency as currency_name');
                        $this->db->from('client_charges');
                        $this->db->join('currency', 'currency.id = client_charges.currency', 'left');
                        $this->db->where('company_code', $company_code);
                        $this->db->where('STR_TO_DATE(invoice_date,"%d/%m/%Y") >= STR_TO_DATE("'. $gst_date. '","%d/%m/%Y")');*/
                        $data["gst_rate"] = $firm[0]["gst"];

                        //$this->db->join('billing', 'billing.id = billing_service.billing_id');
                        for($t = 0; $t < count($take_billing_id); $t++)
                        {
                            $this->db->set($data);
                            $this->db->where('STR_TO_DATE(invoice_date,"%d/%m/%Y") >= STR_TO_DATE("'. $firm[0]["gst_date"]. '","%d/%m/%Y")');
                            $this->db->where('billing_id = '.$take_billing_id[$t]["id"]);
                            $this->db->update('billing_service');
                        }
                        
                        /*if($invoice_date >= $gst_date)
                        {
                            $get_gst_rate = $firm[0]["gst"];
                        }
                        else
                        {
                            $get_gst_rate = 0;
                        }*/
                    }
                    else
                    {
                        if($str_to_previous_gst_date == $str_to_gst_date)
                        {
                            /*$before["gst_rate"] = $firm[0]["gst"];
                            
                            $this->db->set($before);
                            $this->db->where('STR_TO_DATE(invoice_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'.$firm[0]["gst_date"]. '","%d/%m/%Y") and STR_TO_DATE("'.$firm[0]["previous_gst_date"].'","%d/%m/%Y")');
                            $this->db->update('billing_service');*/


                            for($t = 0; $t < count($take_billing_id); $t++)
                            {
                                $after["gst_rate"] = $firm[0]["previous_gst"];
                                
                                $this->db->set($after);
                                $this->db->where('STR_TO_DATE(invoice_date,"%d/%m/%Y") >= STR_TO_DATE("'.$firm[0]["previous_gst_date"].'","%d/%m/%Y")');
                                $this->db->where('billing_id = '.$take_billing_id[$t]["id"]);
                                $this->db->update('billing_service');
                            }


                        }
                        else if($str_to_previous_gst_date > $str_to_gst_date)
                        {
                            for($t = 0; $t < count($take_billing_id); $t++)
                            {
                                $before["gst_rate"] = $firm[0]["gst"];
                                
                                $this->db->set($before);
                                $this->db->where('STR_TO_DATE(invoice_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'.$firm[0]["gst_date"]. '","%d/%m/%Y") and STR_TO_DATE("'.$firm[0]["previous_gst_date"].'","%d/%m/%Y")');
                                $this->db->where('billing_id = '.$take_billing_id[$t]["id"]);
                                //$this->db->join('billing', 'billing.id = billing_service.billing_id');
                                $this->db->update('billing_service');

                                /*$update_sql_before = "UPDATE billing_service SET gst_rate = ".$firm[0]['gst']." WHERE STR_TO_DATE(invoice_date,'%d/%m/%Y') BETWEEN STR_TO_DATE(".$firm[0]["gst_date"]. ",'%d/%m/%Y') and STR_TO_DATE(".$firm[0]["previous_gst_date"].",'%d/%m/%Y')";

                                $this->db->query($update_sql_before);*/

                                $after["gst_rate"] = $firm[0]["previous_gst"];
                                
                                $this->db->set($after);
                                $this->db->where('STR_TO_DATE(invoice_date,"%d/%m/%Y") >= STR_TO_DATE("'.$firm[0]["previous_gst_date"].'","%d/%m/%Y")');
                                $this->db->where('billing_id = '.$take_billing_id[$t]["id"]);
                                //$this->db->join('billing', 'billing.id = billing_service.billing_id');
                                $this->db->update('billing_service');
                            }

                            /*$update_sql_after = "UPDATE billing_service SET gst_rate = ".$firm[0]['previous_gst']." WHERE STR_TO_DATE(invoice_date,'%d/%m/%Y') >= STR_TO_DATE(".$firm[0]["previous_gst_date"].",'%d/%m/%Y')";

                            $this->db->query($update_sql_after);*/
                        }
                        else if($str_to_gst_date > $str_to_previous_gst_date)
                        {
                            for($t = 0; $t < count($take_billing_id); $t++)
                            {
                                $before["gst_rate"] = $firm[0]["previous_gst"];
                                //$this->db->join('billing', 'billing.id = billing_service.billing_id');
                                //$this->db->set($before);
                                $this->db->set($before);
                                $this->db->where('STR_TO_DATE(invoice_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'.$firm[0]["previous_gst_date"]. '","%d/%m/%Y") and STR_TO_DATE("'.$firm[0]["gst_date"].'","%d/%m/%Y")');
                                $this->db->where('billing_id = '.$take_billing_id[$t]["id"]);
                                $this->db->update('billing_service');
                                /*$update_sql_before = "UPDATE billing JOIN billing_service ON billing.id = billing_service.billing_id SET gst_rate = ".$firm[0]['previous_gst']." WHERE STR_TO_DATE(billing.invoice_date,'%d/%m/%Y') BETWEEN STR_TO_DATE(".$firm[0]["previous_gst_date"].",'%d/%m/%Y') and STR_TO_DATE(".$firm[0]["gst_date"].",'%d/%m/%Y')";

                                $this->db->query($update_sql_before);*/

                                //$after["gst_rate"] = $firm[0]["gst"];
                                //$this->db->join('billing', 'billing.id = billing_service.billing_id');
                                //$this->db->set($after);
                                $this->db->set('gst_rate', $firm[0]["gst"]);
                                $this->db->where('STR_TO_DATE(invoice_date,"%d/%m/%Y") >= STR_TO_DATE("'.$firm[0]["gst_date"].'","%d/%m/%Y")');
                                $this->db->where('billing_id = '.$take_billing_id[$t]["id"]);
                                $this->db->update('billing_service');
                            }

                            /*$update_sql_after = "UPDATE billing_service JOIN billing ON billing.id = billing_service.billing_id SET gst_rate = ".$firm[0]['gst']." WHERE STR_TO_DATE(invoice_date,'%d/%m/%Y') >= STR_TO_DATE(".$firm[0]["gst_date"].",'%d/%m/%Y')";

                            $this->db->query($update_sql_after);*/
                        }
                    }

                    $billing_id = $this->db->query("select id, amount, outstanding from billing where firm_id = '".$firm[0]["id"]."'");

                    $billing_id = $billing_id->result_array();

                    //echo json_encode(count($billing_id));

                    for($k = 0; $k < count($billing_id); $k++)
                    {
                        $billing_service_info = $this->db->query("SELECT billing_id, SUM(amount * (1+(gst_rate / 100))) AS totalAmount FROM billing_service WHERE billing_id = ".$billing_id[$k]['id']." GROUP BY billing_id");

                        $billing_service_info = $billing_service_info->result_array();

                        $new_amount = (float)$billing_service_info[0]["totalAmount"];
                        $new_outstanding = (float)$billing_service_info[0]["totalAmount"] - ((float)$billing_id[$k]['amount'] - (float)$billing_id[$k]['outstanding']);

                        $update_billing['amount'] = $new_amount;
                        $update_billing['outstanding'] = $new_outstanding;
                        $this->db->update("billing",$update_billing,array("id" => $billing_id[$k]['id']));
                        
                    }
                }
                else
                {
                    
                    $take_billing_id = $this->db->query("select id from billing where firm_id = '".$firm[0]["id"]."'");

                    $take_billing_id = $take_billing_id->result_array();

                    for($t = 0; $t < count($take_billing_id); $t++)
                    {
                        $this->db->set('gst_rate', 0);
                        $this->db->where('STR_TO_DATE(invoice_date,"%d/%m/%Y") >= STR_TO_DATE("'.$firm[0]["no_gst_date"].'","%d/%m/%Y")');
                        $this->db->where('billing_id = '.$take_billing_id[$t]["id"]);
                        $this->db->update('billing_service');
                    }

                    $billing_id = $this->db->query("select id, amount, outstanding from billing where firm_id = '".$firm[0]["id"]."'");

                    $billing_id = $billing_id->result_array();

                    //echo json_encode(count($billing_id));

                    for($k = 0; $k < count($billing_id); $k++)
                    {
                        $billing_service_info = $this->db->query("SELECT billing_id, SUM(amount * (1+(gst_rate / 100))) AS totalAmount FROM billing_service WHERE billing_id = ".$billing_id[$k]['id']." GROUP BY billing_id");

                        $billing_service_info = $billing_service_info->result_array();

                        $new_amount = (float)$billing_service_info[0]["totalAmount"];
                        $new_outstanding = (float)$billing_service_info[0]["totalAmount"] - ((float)$billing_id[$k]['amount'] - (float)$billing_id[$k]['outstanding']);

                        $update_billing['amount'] = $new_amount;
                        $update_billing['outstanding'] = $new_outstanding;
                        $this->db->update("billing",$update_billing,array("id" => $billing_id[$k]['id']));
                        
                    }
                }
            }
            
            
			echo json_encode(array("Status" => 1,'message' => 'Information Updated', 'title' => 'Updated', 'firm_id' => $insert_id));
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
                //echo json_encode($row["id"]);
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
        /*if(isset($insert_id))
        {*/
            //echo ($this->session->userdata('officer_id'));
           //$filesCount = count($_FILES['uploadimages']['name']);
            //echo json_encode(count($_FILES['uploadimages']['name']));
            //for($i = 0; $i < $filesCount; $i++)
            //{   
            	
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

                //echo json_encode($_FILES['logo']);
                //echo json_encode($this->upload->do_upload('logo'));
                if($this->upload->do_upload('logo'))
                {
                    $fileData = $this->upload->data();
                    //echo json_encode($fileData);
                    $firm_id = $this->session->userdata('submit_firm_id');
                    $uploadData['file_name'] = $fileData['file_name'];

                    $files = $this->db->query("select * from firm where id='".$firm_id."'");
                    $file_info = $files->result_array();

                    unlink("./uploads/logo/".$file_info[0]["file_name"]);
                    /*$uploadData[$i]['created'] = date("Y-m-d H:i:s");
                    $uploadData[$i]['modified'] = date("Y-m-d H:i:s");*/
                }

            //}
            
            if(!empty($uploadData))
            {
            	$this->db->update("firm",$uploadData,array("id" => $firm_id));
                //$this->db->insert_batch('officer_files',$uploadData);
                
            }
            echo json_encode($fileData['file_name']);
            //redirect("personprofile");
            /*$this->session->unset_userdata('officer_id');*/
        //}
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
                );

                echo json_encode(array("Status" => 0, 'message' => 'Please complete all required field.', 'title' => 'Error', "error" => $error));
            }
            else
            {
                $data['firm_id'] = $_POST['firm_id'][$i];
                $data['bank_id']=$_POST['bank_id'][$i];
                $data['banker']=$_POST['banker'][$i];
                $data['account_number']=$_POST['account_number'][$i];
                $data['bank_code']=$_POST['bank_code'][$i];
                $data['swift_code']=$_POST['swift_code'][$i];
                $data['currency']=$_POST['currency'][$i];

                $q = $this->db->get_where("bank_info", array("id" => $_POST['bank_info_id'][$i]));

                if (!$q->num_rows())
                {   
                    $check_bank_id = $this->db->get_where("bank_info", array("bank_id" => $_POST['bank_id'][$i], "firm_id" => $_POST['firm_id'][$i]));

                    if (!$check_bank_id->num_rows())
                    {
                        $this->db->insert("bank_info",$data);
                        $insert_bank_info_id = $this->db->insert_id();

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

                    echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
                }

                
            }
            
        }
    }

    public function delete_bank_info ()
    {
        $id = $_POST["bank_info_id"];

        $this->db->delete("bank_info",array('id'=>$id));

        echo json_encode(array("Status" => 1));
                
    }

}
