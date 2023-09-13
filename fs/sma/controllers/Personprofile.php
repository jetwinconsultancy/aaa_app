<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Personprofile extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        $this->load->library('form_validation');
        /*$this->load->library('uploadhandler');*/
        $this->load->model('db_model');
        $this->load->model('master_model');
        $this->load->library(array('session'));
    }

    public function index()
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $bc = array(array('link' => '#', 'page' => lang('Person')));
        $meta = array('page_title' => lang('Person'), 'bc' => $bc, 'page_name' => 'Person');
		$term = ''; $type = '';

        $files_id = array();
        $this->session->set_userdata(array(
            'files_id'  =>  $files_id,
        ));

        $company_files_id = array();
        $this->session->set_userdata(array(
            'company_files_id'  =>  $company_files_id,
        ));

        //echo json_encode(count($this->session->userdata('files_id')));

		if (isset($_POST['type'])) $type = $_POST['type'];
		if (isset($_POST['search'])) $term = $_POST['search'];
        if($type == null)
        {
            $type = "all";
        }
        else 
        {
            $type = $_POST['type'];
        }
		$this->data['person'] = $this->master_model->get_all_person($term,$type);
        $this->data['type'] = $type;
        /*echo json_encode($this->data);
        echo $type;*/
		// $this->sma->print_arrays($_POST);
        $this->page_construct('personprofile.php', $meta, $this->data);

    }

    public function add($close_page = null)
    {
        $files_id = array();
        $this->session->set_userdata(array(
            'files_id'  =>  $files_id,
        ));

        $company_files_id = array();
        $this->session->set_userdata(array(
            'company_files_id'  =>  $company_files_id,
        ));

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $bc = array(array('link' => '#', 'page' => lang('Create Person')));
        $meta = array('page_title' => lang('Create Person'), 'bc' => $bc, 'page_name' => 'Create Person');
        $p = [];
       
        $p[0]->individual_status = 'checked';
        $p[0]->local_status = 'checked';
        $p[0]->alternate_address_status = '';
        $p[0]->alternate_text_status = 'style="display: none;"';
        $p[0]->address_type = 'Local';
        $this->session->set_userdata(array(
                    'nationality'  => 165,
                ));
        $p[0]->individual_table = 'style="display: table;"';
        $p[0]->company_table = 'style="display: none;"';
        
        //echo json_encode($p[0]->individual_status);
        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Person', base_url('personprofile'));
        $this->mybreadcrumb->add('Create Person', base_url());
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        
        $this->data['person'] = $p[0];
        $this->data['close_page'] = $close_page;
        $this->page_construct('addpersonprofile.php', $meta, $this->data);

    }

    public function edit($identification_no)
    {
        /*$files_id = array();
        $this->session->set_userdata(array(
            'files_id'  =>  $files_id,
        ));*/
        //echo urldecode($identification_no);

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $bc = array(array('link' => '#', 'page' => lang('Edit Person')));
        $meta = array('page_title' => lang('Edit Person'), 'bc' => $bc, 'page_name' => 'Edit Person');
        
		/*$this->data['citizen'] = $this->master_model->get_all_citizen();*/
        $files_id = array();
        $this->session->set_userdata(array(
            'files_id'  =>  $files_id,
        ));

        //one to many relationship
		$q = $this->db->query("select officer.*, GROUP_CONCAT(DISTINCT CONCAT(officer_mobile_no.id,',', officer_mobile_no.mobile_no, ',', officer_mobile_no.primary_mobile_no)SEPARATOR ';') AS 'officer_mobile_no', GROUP_CONCAT(DISTINCT CONCAT(officer_fixed_line_no.id,',', officer_fixed_line_no.fixed_line_no, ',', officer_fixed_line_no.primary_fixed_line_no)SEPARATOR ';') AS 'officer_fixed_line_no', GROUP_CONCAT(DISTINCT CONCAT(officer_email.id,',', officer_email.email, ',', officer_email.primary_email)SEPARATOR ';') AS 'officer_email', GROUP_CONCAT(DISTINCT CONCAT(officer_files.id,',', officer_files.file_name)SEPARATOR ';') AS 'files' from officer LEFT JOIN officer_files ON officer_files.officer_id = officer.id left join officer_mobile_no on officer_mobile_no.officer_id = officer.id left join officer_fixed_line_no on officer_fixed_line_no.officer_id = officer.id left join officer_email on officer_email.officer_id = officer.id where identification_no='".addslashes(urldecode($identification_no))."' GROUP BY officer.id ORDER BY officer_mobile_no.primary_mobile_no DESC, officer_fixed_line_no.primary_fixed_line_no DESC, officer_email.primary_email DESC");

        
        if($q->result()[0]->files != null)
        {
            $q->result()[0]->files = explode(';', $q->result()[0]->files);
        }
        //echo json_encode($q->result()[0]->files);
        if($q->result()[0]->officer_mobile_no != null)
        {
            $q->result()[0]->officer_mobile_no = explode(';', $q->result()[0]->officer_mobile_no);
        }

        if($q->result()[0]->officer_fixed_line_no != null)
        {
            $q->result()[0]->officer_fixed_line_no = explode(';', $q->result()[0]->officer_fixed_line_no);
        }

        if($q->result()[0]->officer_email != null)
        {
            $q->result()[0]->officer_email = explode(';', $q->result()[0]->officer_email);
        }
       
       
       
        

		if ($q->num_rows() > 0) { 
            $this->session->set_userdata(array(
                    'nationality'  => $q->result()[0]->nationality,
                ));
            
            $dateArr = explode("-", $q->result()[0]->date_of_birth);
            $q->result()[0]->date_of_birth = $dateArr[2] . '/' . $dateArr[1] . '/' . $dateArr[0];

            if ($q->result()[0]->field_type == "individual")
            {
                $q->result()[0]->individual_status = 'checked';
                $q->result()[0]->individual_disabled = 'false';
                $q->result()[0]->company_disabled = 'true';
            } 
            else if ($q->result()[0]->field_type == "company")
            {
                $q->result()[0]->company_status = 'checked';
                $q->result()[0]->individual_disabled = 'true';
                $q->result()[0]->company_disabled = 'false';
            }
            if ($q->result()[0]->address_type == "Local")
            {
                $q->result()[0]->local_status = 'checked';
            }
            else
            {
                $q->result()[0]->foreign_status = 'checked';
            }
            if ($q->result()[0]->alternate_address == "1")
            {
                $q->result()[0]->alternate_address_status = 'checked="checked"';
                $q->result()[0]->alternate_text_status = 'style="display: block;"';
                 
            }
            else
            {
                $q->result()[0]->alternate_address_status = '';
                $q->result()[0]->alternate_text_status = 'style="display: none;"';
            }
            $q->result()[0]->individual_table = 'style="display: table;"';
            $q->result()[0]->company_table = 'style="display: none;"';
			$this->data['person'] = $q->result()[0];
        }

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Person', base_url('personprofile'));
        $this->mybreadcrumb->add('Edit Person - '.$q->result()[0]->name.'', base_url());
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        //echo json_encode($q);
        $this->page_construct('addpersonprofile.php', $meta, $this->data);

    }
	
    public function editCompany($register_no)
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $bc = array(array('link' => '#', 'page' => lang('Edit Company')));
        $meta = array('page_title' => lang('Edit Company'), 'bc' => $bc, 'page_name' => 'Edit Company');
        
        $company_files_id = array();
        $this->session->set_userdata(array(
            'company_files_id'  =>  $company_files_id,
        ));

        //one to many relationship
        $q = $this->db->query("select officer_company.*, GROUP_CONCAT(DISTINCT CONCAT(officer_company_files.id,',', officer_company_files.file_name)SEPARATOR ';') AS 'company_files', GROUP_CONCAT(DISTINCT CONCAT(officer_company_phone_number.id,',', officer_company_phone_number.phone_number,',', officer_company_phone_number.primary_phone_number)SEPARATOR ';') AS 'officer_company_phone_number', GROUP_CONCAT(DISTINCT CONCAT(officer_company_email.id,',', officer_company_email.email,',', officer_company_email.primary_email)SEPARATOR ';') AS 'officer_company_email' from officer_company LEFT JOIN officer_company_files ON officer_company_files.officer_company_id = officer_company.id LEFT JOIN officer_company_phone_number ON officer_company_phone_number.officer_company_id = officer_company.id LEFT JOIN officer_company_email ON officer_company_email.officer_company_id = officer_company.id where register_no='".addslashes(urldecode($register_no))."' GROUP BY officer_company.id");

        $corp_rep_info = $this->db->query("select corporate_representative.* from corporate_representative where corporate_representative.registration_no = '".$q->result()[0]->register_no."' ORDER BY id");

        if ($corp_rep_info->num_rows() > 0) {
            foreach (($corp_rep_info->result()) as $row) {
                $data[] = $row;
            }
        }
        else
        {
            $data = false;
        }

        $this->data['corp_rep_data'] = $data;

        if($q->result()[0]->company_files != null)
        {
            $q->result()[0]->company_files = explode(';', $q->result()[0]->company_files);
        }

        if($q->result()[0]->officer_company_phone_number != null)
        {
            $q->result()[0]->officer_company_phone_number = explode(';', $q->result()[0]->officer_company_phone_number);
        }

        if($q->result()[0]->officer_company_email != null)
        {
            $q->result()[0]->officer_company_email = explode(';', $q->result()[0]->officer_company_email);
        }

        /*$this->data['citizen'] = $this->master_model->get_all_citizen();*/
        //$q = $this->db->query("select * from officer_company where register_no='".$register_no."'");
        //echo json_encode($q->result());
        if ($q->num_rows() > 0) { 
            if($q->result()[0]->date_of_incorporation != null)
            {
                $dateArr = explode("-", $q->result()[0]->date_of_incorporation);
                $q->result()[0]->date_of_incorporation = $dateArr[2] . '/' . $dateArr[1] . '/' . $dateArr[0];
            }
            else
            {
                $q->result()[0]->date_of_incorporation = "";
            }

            if ($q->result()[0]->field_type == "individual")
            {
                $q->result()[0]->individual_status = 'checked';
                $q->result()[0]->individual_disabled = 'false';
                $q->result()[0]->company_disabled = 'true';
            } 
            else
            {
                $q->result()[0]->company_status = 'checked';
                $q->result()[0]->individual_disabled = 'true';
                $q->result()[0]->company_disabled = 'false';
            }

            if ($q->result()[0]->address_type == "Local")
            {
                $q->result()[0]->local_status = 'checked';
            }
            else
            {
                $q->result()[0]->foreign_status = 'checked';
            }
            //$q->result()[0]->address_type = "Local";
            $q->result()[0]->local_status = 'checked';
            $q->result()[0]->alternate_address_status = '';
            $q->result()[0]->alternate_text_status = 'style="display: none;"';
            $q->result()[0]->individual_table = 'style="display: none;"';
            $q->result()[0]->company_table = 'style="display: table;"';
            
            
            $this->data['person'] = $q->result()[0];
        }

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Person', base_url('personprofile'));
        $this->mybreadcrumb->add('Edit Company - '.$q->result()[0]->company_name.'', base_url());
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        //echo json_encode($this->data);
        $this->page_construct('addpersonprofile.php', $meta, $this->data);
    }

    public function mobile_no_validate($mobile_no)
    {
        foreach ($mobile_no as $key => $value) {
            if($key != 0 || count($mobile_no) == 1)
            {
                if($value[$key]==null){
                    //echo json_encode($mobile_no);
                    $this->form_validation->set_message('mobile_no_validate', 'The Mobile No field is required.');
                    return FALSE;
                }
            }
            
        }
        return TRUE;
    }

	public function update()
	{
        //$fileData = array();
        // File upload script

        /*$config['upload_path']   = './uploads/images_or_pdf';
        $config['allowed_types'] = 'gif|jpg|png|pdf';
        $config['max_size']      = '1000000000';
        $config['max_width']     = '1920';
        $config['max_height']    = '1080';
        $config['overwrite']     = true;

        $this->load->library('upload', $config);

        
        print_r($_FILES);*/
        //echo json_encode($_POST);
        $insert_id = '';
       //echo json_encode(isset($_POST['foreign_address1']));
        //echo json_encode(isset($_POST['postal_code1']));

		// $this->sma->print_arrays($_POST);
        $this->form_validation->set_rules('identification_no', 'Identification No', 'required');
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('date_of_birth', 'Date Of Birth', 'required');
        if(isset($_POST['postal_code1']))
        {
            $this->form_validation->set_rules('postal_code1', 'Postal Code', 'required|numeric');
        }
        if(isset($_POST['street_name1']))
        {
            $this->form_validation->set_rules('street_name1', 'Street Name', 'required');
        }

        if(isset($_POST['postal_code2']))
        {
            $this->form_validation->set_rules('postal_code2', 'Postal Code', 'required|numeric');
        }
        if(isset($_POST['street_name2']))
        {
            $this->form_validation->set_rules('street_name2', 'Street Name', 'required');
        }
        /*$this->form_validation->set_rules('street_name1', 'Street name', 'required');
        $this->form_validation->set_rules('building_name1', 'Building name', 'required');
        $this->form_validation->set_rules('unit_no1', 'Unit no', 'required');
        $this->form_validation->set_rules('unit_no2', 'Unit no', 'required');
        $this->form_validation->set_rules('postal_code2', 'Postal code', 'required');
        $this->form_validation->set_rules('street_name2', 'Street name', 'required');
        $this->form_validation->set_rules('building_name2', 'Building name', 'required');
        $this->form_validation->set_rules('unit_no3', 'Unit no', 'required');
        $this->form_validation->set_rules('unit_no4', 'Unit no', 'required');*/
        if(isset($_POST['foreign_address1']))
        {
            $this->form_validation->set_rules('foreign_address1', 'Foreign Address', 'required');
        }
        /*if(isset($_POST['foreign_address2']))
        {
            $this->form_validation->set_rules('foreign_address2', 'Foreign Address', 'required');
        }*/
        $this->form_validation->set_rules('local_fix_line[]', 'Fixed Line No', 'numeric');
        $this->form_validation->set_rules('local_mobile[]', 'Mobile No', 'numeric');
        $this->form_validation->set_rules('email[]', 'Email', 'valid_emails');

        // foreach ($_POST['hidden_local_mobile'] as $key => $value) {
        //     if($key != 0 || count($_POST['hidden_local_mobile']) == 1)
        //     {
        //         if($value[$key]==null){
        //             //echo json_encode($mobile_no);
        //             $validate_mobile_no = FALSE;
        //             break;
        //         }
        //     }
        //     $validate_mobile_no = TRUE;
        // }

        foreach (array_values($_POST['hidden_local_mobile']) as $key => $value) {
            //echo json_encode($value);
            if(count($_POST['hidden_local_mobile']) == 1)
            {

                    //echo json_encode($value[1]);
                if($value==null){
                    
                    $validate_mobile_no = FALSE;
                    break;
                }
            }
            $validate_mobile_no = TRUE;
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

        if(count($_POST['hidden_local_fix_line']) > 1 && $_POST['fixed_line_no_primary'] == null)
        {
            $validate_fix_line_primary = FALSE;
        }
        else
        {
            $validate_fix_line_primary = TRUE;
        }

        if(count($_POST['hidden_local_mobile']) > 1 && $_POST['local_mobile_primary'] == null)
        {
            $validate_local_mobile_primary = FALSE;
        }
        else
        {
            $validate_local_mobile_primary = TRUE;
        }

        if(count($_POST['email']) > 1 && $_POST['email_primary'] == null)
        {
            $validate_email_primary = FALSE;
        }
        else
        {
            $validate_email_primary = TRUE;
        }

        //echo json_encode($validate_fix_line_primary);
/*$_POST['fixed_line_no_primary']
$_POST['local_mobile_primary']
$_POST['email_primary']*/
        //echo json_encode($this->form_validation->run());
        if ($this->form_validation->run() == FALSE || $_POST['nationality'] == "0" || $validate_mobile_no == FALSE || $validate_email == FALSE || $validate_local_mobile_primary == FALSE ||  $validate_fix_line_primary == FALSE || $validate_email_primary == FALSE)
        {
            $validate_nationality = "";
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $bc = array(array('link' => '#', 'page' => lang('Add Person')));
            $meta = array('page_title' => lang('Add Person'), 'bc' => $bc, 'page_name' => 'Add Person');
            $p = [];
           
            $p[0]->individual_status = 'checked';
            $p[0]->local_status = 'checked';
            $p[0]->alternate_address_status = '';
            $p[0]->alternate_text_status = 'style="display: none;"';
            $p[0]->address_type = 'Local';
            $this->session->set_userdata(array(
                        'nationality'  => null,
                    ));
            $p[0]->individual_table = 'style="display: table;"';
            $p[0]->company_table = 'style="display: none;"';
            
            /*echo json_encode($p[0]);*/
            $this->data['person'] = $p[0];

            if($_POST['nationality'] == "0")
            {
                $validate_nationality = "The Nationality field is required.";
            }
            else
            {
                $validate_nationality = "";
            }

            if($validate_fix_line_primary == FALSE)
            {
                $validate_fix_line_field = "Please set the primary field.";
            }
            else
            {
                $validate_fix_line_field = strip_tags(form_error('local_fix_line[]'));
            }

            if($validate_mobile_no == FALSE || $validate_local_mobile_primary == FALSE)
            {
                if($validate_mobile_no == FALSE)
                {
                    $validate_mobile_no_field = "The Mobile No field is required.";
                }
                else if($validate_local_mobile_primary == FALSE)
                {
                    $validate_mobile_no_field = "Please set the primary field.";
                }
            }
            else
            {
                $validate_mobile_no_field = strip_tags(form_error('local_mobile[]'));
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
                'identification_no' => strip_tags(form_error('identification_no')),
                'name' => strip_tags(form_error('name')),
                'date_of_birth' => strip_tags(form_error('date_of_birth')),
                'nationality' => $validate_nationality,
                'postal_code1' => strip_tags(form_error('postal_code1')),
                'street_name1' => strip_tags(form_error('street_name1')),
                'postal_code2' => strip_tags(form_error('postal_code2')),
                'street_name2' => strip_tags(form_error('street_name2')),
                'local_fix_line' => $validate_fix_line_field,
                'local_mobile' => $validate_mobile_no_field,
                'foreign_address1' => strip_tags(form_error('foreign_address1')),
                /*'foreign_address2' => strip_tags(form_error('foreign_address2')),*/
                'email' => $validate_email_field,
            );
            //$this->page_construct('addpersonprofile.php', $meta, $this->data);


            echo json_encode(array("Status" => 0, "error" => $arr));

            
        }
        else
        {
            
            /*if($this->upload->do_upload('uploadimage'))
            {
                $data = $this->upload->data(); // Get the file data

                $fileData[] = $data; // It's an array with many data
                // Interate throught the data to work with them
                foreach ($fileData as $file) {
                    $file_data = $file;
                }

                var_dump($file_data);*/

                $check_identification_no = $this->db->query("select identification_no from officer where identification_no='".addslashes($_POST['identification_no'])."' AND user_admin_code_id = '".$this->session->userdata('user_admin_code_id')."'");

                if ($check_identification_no->num_rows() > 0 && $_POST['identification_no'] != $_POST['old_identification_no']) {   
                    echo json_encode(array("Status" => 2));
                }
                else
                {
                    if($_POST['postal_code1'] != $_POST['postal_code2'] && $_POST['street_name1'] != $_POST['street_name2'] || $_POST['address_type'] == "Foreign")
                    {
                        $officer = [];
                        $officer['created_by']=$this->session->userdata('user_id');
                        $officer['user_admin_code_id']=$this->session->userdata("user_admin_code_id");
                        //$officer['user_id']=$this->session->userdata('user_id');
                        $officer['field_type'] = $_POST['field_type'];
                        $officer['identification_type'] = $_POST['identification_type'];
                        $officer['identification_no'] = strtoupper($_POST['identification_no']);
                        $officer['name'] = strtoupper($_POST['name']);
                        $dateArr = explode("/", $_POST['date_of_birth']);
                        $newDate = $dateArr[2] . '-' . $dateArr[1] . '-' . $dateArr[0];
                        $officer['date_of_birth'] = $newDate;
                        $officer['address_type'] = $_POST['address_type'];
                        if(isset($_POST['postal_code1']))
                        {
                            $officer['postal_code1'] = strtoupper($_POST['postal_code1']);
                        }
                        else
                        {
                            $officer['postal_code1'] = "";
                        }

                        if(isset($_POST['street_name1']))
                        {
                            $officer['street_name1'] = strtoupper($_POST['street_name1']);
                        }
                        else
                        {
                            $officer['street_name1'] = "";
                        }

                        if(isset($_POST['hidden_non_verify_checkbox']))
                        {
                            $officer['non_verify'] = strtoupper($_POST['hidden_non_verify_checkbox']);
                        }
                        else
                        {
                            $officer['non_verify'] = 0;
                        }

                        //$officer['street_name1'] = $_POST['street_name1'];
                        $officer['building_name1'] = strtoupper($_POST['building_name1']);
                        $officer['unit_no1'] = strtoupper($_POST['unit_no1']);
                        $officer['unit_no2'] = strtoupper($_POST['unit_no2']);
                        $officer['alternate_address'] = $_POST['alternate_address'];
                        if(isset($_POST['postal_code2']))
                        {
                            $officer['postal_code2'] = strtoupper($_POST['postal_code2']);
                        }
                        else
                        {
                            $officer['postal_code2'] = "";
                        }

                        if(isset($_POST['street_name2']))
                        {
                            $officer['street_name2'] = strtoupper($_POST['street_name2']);
                        }
                        else
                        {
                            $officer['street_name2'] = "";
                        }
                        $officer['building_name2'] = strtoupper($_POST['building_name2']);
                        $officer['unit_no3'] = strtoupper($_POST['unit_no3']);
                        $officer['unit_no4'] = strtoupper($_POST['unit_no4']);
                        if(isset($_POST['foreign_address1']))
                        {
                            $officer['foreign_address1'] = strtoupper($_POST['foreign_address1']);
                        }
                        else
                        {
                            $officer['foreign_address1'] = "";
                        }
                        if(isset($_POST['foreign_address2']))
                        {
                            $officer['foreign_address2'] = strtoupper($_POST['foreign_address2']);
                        }
                        else
                        {
                            $officer['foreign_address2'] = "";
                        }
                        if(isset($_POST['foreign_address3']))
                        {
                            $officer['foreign_address3'] = strtoupper($_POST['foreign_address3']);
                        }
                        else
                        {
                            $officer['foreign_address3'] = "";
                        }
                        $officer['nationality'] = $_POST['nationality'];
                        
                        /*$officer['local_fix_line'] = $_POST['local_fix_line'];
                        $officer['local_mobile'] = $_POST['local_mobile'];
                        $officer['email'] = $_POST['email'];*/

                        /*$officer['file_name'] = $file_data['file_name'];
                        $officer['file_ext'] = $file_data['file_ext'];*/


                        /*$officer['gid'] = $_POST['gid'];
                        $officer['nama'] = $_POST['nama'];
                        $officer['date_of_birth'] = $_POST['date_of_birth'];
                        $officer['addresstype'] = $_POST['addresstype'];
                        $officer['address'] = '<ZC>'.$_POST['zipcode'].'</ZC><ST>'.$_POST['street'].'</ST><B>'.$_POST['buildingname'].'</B><UN1>'.$_POST['unit_no1'].'</UN1><UN2>'.$_POST['unit_no2'].'</UN2><AA>'.$_POST['alternate_address']."</AA>";
                        $officer['addresstype'] = $_POST['addresstype'];
                        $officer['citizen'] = $_POST['citizen'];
                        $officer['local_fix_line'] = $_POST['local_fix_line'];
                        $officer['phone'] = $_POST['phone'];
                        $officer['email'] = $_POST['email'];*/
                        // print_r($officer);
                        
                        $q = $this->db->query("select identification_no from officer where identification_no='".addslashes($_POST['old_identification_no'])."' AND user_admin_code_id = '".$this->session->userdata('user_admin_code_id')."'");
                        //echo json_encode($q);
                        if ($q->num_rows() > 0) {          
                            //echo "A";
                            $this->db->where('identification_no', $_POST['old_identification_no']);
                            $this->db->update('officer', $officer);
                            $this->session->set_userdata(array(
                                'officer_id'  =>  $_POST['officer_id'],
                            ));

                            $this->db->delete("officer_fixed_line_no",array('officer_id'=>$_POST['officer_id']));

                            for($g = 0; $g < count($_POST['hidden_local_fix_line']); $g++)
                            {
                                if($_POST['hidden_local_fix_line'][$g] != "")
                                {
                                    $localFixLine['officer_id'] = $_POST['officer_id'];
                                    $localFixLine['fixed_line_no'] = strtoupper($_POST['hidden_local_fix_line'][$g]);
                                    if($_POST['fixed_line_no_primary'] == $_POST['hidden_local_fix_line'][$g])
                                    {
                                        $localFixLine['primary_fixed_line_no'] = 1;
                                    }
                                    else
                                    {
                                        $localFixLine['primary_fixed_line_no'] = 0;
                                    }
                                    $this->db->insert('officer_fixed_line_no', $localFixLine);
                                }
                            }

                            $this->db->delete("officer_mobile_no",array('officer_id'=>$_POST['officer_id']));

                            for($g = 0; $g < count($_POST['hidden_local_mobile']); $g++)
                            {
                                if($_POST['hidden_local_mobile'][$g] != "")
                                {
                                    $localMobile['officer_id'] = $_POST['officer_id'];
                                    $localMobile['mobile_no'] = strtoupper($_POST['hidden_local_mobile'][$g]);
                                    if($_POST['local_mobile_primary'] == $_POST['hidden_local_mobile'][$g])
                                    {
                                        $localMobile['primary_mobile_no'] = 1;
                                    }
                                    else
                                    {
                                        $localMobile['primary_mobile_no'] = 0;
                                    }
                                    $this->db->insert('officer_mobile_no', $localMobile);
                                }
                            }

                            $this->db->delete("officer_email",array('officer_id'=>$_POST['officer_id']));

                            for($g = 0; $g < count($_POST['email']); $g++)
                            {
                                if($_POST['email'][$g] != "")
                                {
                                    $localEmail['officer_id'] = $_POST['officer_id'];
                                    $localEmail['email'] = strtoupper($_POST['email'][$g]);
                                    if($_POST['email_primary'] == $_POST['email'][$g])
                                    {
                                        $localEmail['primary_email'] = 1;
                                    }
                                    else
                                    {
                                        $localEmail['primary_email'] = 0;
                                    }
                                    $this->db->insert('officer_email', $localEmail);
                                }
                            }
                        } 
                        else 
                        {
                            if($_SESSION['group_id'] == 4)
                            {
                                $officer['non_verify'] = 1;
                            }
                            $this->db->insert('officer', $officer);
                            $insert_id = $this->db->insert_id();
                            $this->session->set_userdata(array(
                                'officer_id'  =>  $insert_id,
                            ));
                            for($g = 0; $g < count($_POST['hidden_local_fix_line']); $g++)
                            {
                                if($_POST['hidden_local_fix_line'][$g] != "")
                                {
                                    $localFixLine['officer_id'] = $insert_id;
                                    $localFixLine['fixed_line_no'] = strtoupper($_POST['hidden_local_fix_line'][$g]);
                                    if($_POST['fixed_line_no_primary'] == $_POST['hidden_local_fix_line'][$g])
                                    {
                                        $localFixLine['primary_fixed_line_no'] = 1;
                                    }
                                    else
                                    {
                                        $localFixLine['primary_fixed_line_no'] = 0;
                                    }
                                    $this->db->insert('officer_fixed_line_no', $localFixLine);
                                }
                            }

                            for($g = 0; $g < count($_POST['hidden_local_mobile']); $g++)
                            {
                                if($_POST['hidden_local_mobile'][$g] != "")
                                {
                                    $localMobile['officer_id'] = $insert_id;
                                    $localMobile['mobile_no'] = strtoupper($_POST['hidden_local_mobile'][$g]);
                                    if($_POST['local_mobile_primary'] == $_POST['hidden_local_mobile'][$g])
                                    {
                                        $localMobile['primary_mobile_no'] = 1;
                                    }
                                    else
                                    {
                                        $localMobile['primary_mobile_no'] = 0;
                                    }
                                    $this->db->insert('officer_mobile_no', $localMobile);
                                }
                            }

                            for($g = 0; $g < count($_POST['email']); $g++)
                            {
                                if($_POST['email'][$g] != "")
                                {
                                    $localEmail['officer_id'] = $insert_id;
                                    $localEmail['email'] = strtoupper($_POST['email'][$g]);
                                    if($_POST['email_primary'] == $_POST['email'][$g])
                                    {
                                        $localEmail['primary_email'] = 1;
                                    }
                                    else
                                    {
                                        $localEmail['primary_email'] = 0;
                                    }
                                    $this->db->insert('officer_email', $localEmail);
                                }
                            }

                        }
                        if (isset($_POST['company_register_no']))
                        {
                            $this->db->delete("officer_company",array('register_no'=>$_POST['company_register_no']));
                        }

                        if (count($this->session->userdata('files_id')) != 0)
                        {
                            $files_id = $this->session->userdata('files_id');
                            for($i = 0; $i < count($files_id); $i++)
                            {
                                $files = $this->db->query("select * from officer_files where id='".$files_id[$i]."'");
                                $file_info = $files->result_array();

                                $this->db->where('id', $files_id[$i]);

                                unlink("./uploads/images_or_pdf/".$file_info[0]["file_name"]);

                                $this->db->delete('officer_files', array('id' => $files_id[$i]));

                                //echo json_encode(unlink("./uploads/images_or_pdf/".$file_info[0]["file_name"]));
                            }
                        }
                        //echo json_encode($this->session->userdata('files_id'));
                        echo json_encode(array("Status" => 1));
                    }
                    else
                    {
                        echo json_encode(array("Status" => 3));
                    }
                }
                

                //redirect("personprofile");
            //}
        }

        
	}

    public function uploadFile()
    {
        /*if(isset($insert_id))
        {*/
            //echo ($this->session->userdata('officer_id'));
            $filesCount = count($_FILES['uploadimages']['name']);
            //echo json_encode(count($_FILES['uploadimages']['name']));
            for($i = 0; $i < $filesCount; $i++)
            {   //echo json_encode($_FILES['uploadimages']);
                $_FILES['uploadimage']['name'] = $_FILES['uploadimages']['name'][$i];
                $_FILES['uploadimage']['type'] = $_FILES['uploadimages']['type'][$i];
                $_FILES['uploadimage']['tmp_name'] = $_FILES['uploadimages']['tmp_name'][$i];
                $_FILES['uploadimage']['error'] = $_FILES['uploadimages']['error'][$i];
                $_FILES['uploadimage']['size'] = $_FILES['uploadimages']['size'][$i];

                $uploadPath = './uploads/images_or_pdf';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('uploadimage'))
                {
                    $fileData = $this->upload->data();
                    //echo json_encode($fileData);
                    $uploadData[$i]['officer_id'] = $this->session->userdata('officer_id');
                    $uploadData[$i]['file_name'] = $fileData['file_name'];
                    /*$uploadData[$i]['created'] = date("Y-m-d H:i:s");
                    $uploadData[$i]['modified'] = date("Y-m-d H:i:s");*/
                }

            }
            //echo json_encode($uploadData);
            if(!empty($uploadData))
            {
                $this->db->insert_batch('officer_files',$uploadData);
                
            }
            //redirect("personprofile");
            /*$this->session->unset_userdata('officer_id');*/
        //}
    }

    public function deleteFile($id)
    {
        //if (count($this->session->userdata('files_id')) == 0)
        //{
            $files_id = $this->session->userdata('files_id');
            array_push($files_id, $id);
            $this->session->set_userdata(array(
                'files_id'  =>  $files_id,
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

        //echo json_encode($this->session->userdata('files_id'));
    }

    public function updateCompany()
    {

        $this->form_validation->set_rules('company_name', 'Company Name', 'required');
        $this->form_validation->set_rules('register_no', 'Register No', 'required');
        $this->form_validation->set_rules('company_email[]', 'Email', 'valid_email');
        $this->form_validation->set_rules('company_phone_number[]', 'Phone Number', 'numeric');
        /*$this->form_validation->set_rules('date_of_incorporation', 'Date of Incorporation', 'required');
        $this->form_validation->set_rules('country_of_incorporation', 'Country of Incorporation', 'required');*/
        //$this->form_validation->set_rules('company_corporate_representative', 'Corporate Representative', 'required');
        //$this->form_validation->set_rules('company_postal_code', 'Postal code', 'required');

        if(isset($_POST['company_postal_code']))
        {
            $this->form_validation->set_rules('company_postal_code', 'Postal Code', 'required|numeric');
        }
        if(isset($_POST['company_street_name']))
        {
            $this->form_validation->set_rules('company_street_name', 'Street Name', 'required');
        }
        if(isset($_POST['company_foreign_address1']))
        {
            $this->form_validation->set_rules('company_foreign_address1', 'Foreign Address', 'required');
        }
        /*if(isset($_POST['company_foreign_address2']))
        {
            $this->form_validation->set_rules('company_foreign_address2', 'Foreign Address', 'required');
        }*/

        if(count($_POST['hidden_company_phone_number']) > 1 && $_POST['company_phone_number_primary'] == null)
        {
            $validate_company_phone_number_primary = FALSE;
        }
        else
        {
            $validate_company_phone_number_primary = TRUE;
        }

        if(count($_POST['company_email']) > 1 && $_POST['company_email_primary'] == null)
        {
            $validate_company_email_primary = FALSE;
        }
        else
        {
            $validate_company_email_primary = TRUE;
        }

        //echo json_encode($this->form_validation->run());
        if ($this->form_validation->run() == FALSE || $validate_company_phone_number_primary == FALSE || $validate_company_email_primary == FALSE)
        {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $bc = array(array('link' => '#', 'page' => lang('Add Person')));
            $meta = array('page_title' => lang('Add Person'), 'bc' => $bc, 'page_name' => 'Add Person');
            $p = [];
           
            $p[0]->individual_status = 'checked';
            $p[0]->local_status = 'checked';
            $p[0]->alternate_address_status = '';
            $p[0]->alternate_text_status = 'style="display: none;"';
            $p[0]->address_type = 'Local';
            $this->session->set_userdata(array(
                        'nationality'  => null,
                    ));
            $p[0]->company_table = 'style="display: table;"';
            $p[0]->individual_table = 'style="display: none;"';

            $p[0]->company_status = 'checked';

            if($validate_company_phone_number_primary == FALSE)
            {
                $validate_company_phone_number = "Please set the primary field.";
            }
            else
            {
                $validate_company_phone_number = strip_tags(form_error('company_phone_number[]'));
            }

            if($validate_company_email_primary == FALSE)
            {
                $validate_company_email_primary = "Please set the primary field.";
            }
            else
            {
                $validate_company_email_primary = strip_tags(form_error('company_email[]'));
            }
       
            $arr = array(
                'register_no' => strip_tags(form_error('register_no')),
                'company_name' => strip_tags(form_error('company_name')),
                /*'date_of_incorporation' => strip_tags(form_error('date_of_incorporation')),
                'country_of_incorporation' => strip_tags(form_error('country_of_incorporation')),*/
                'company_postal_code' => strip_tags(form_error('company_postal_code')),
                'company_street_name' => strip_tags(form_error('company_street_name')),
                'company_foreign_address1' => strip_tags(form_error('company_foreign_address1')),
                'company_email' => $validate_company_email_primary,
                'company_phone_number' => $validate_company_phone_number,
                'company_corporate_representative' => strip_tags(form_error('company_corporate_representative')),
                /*'company_foreign_address2' => strip_tags(form_error('company_foreign_address2')),*/
            );

            echo json_encode(array("Status" => 0, "error" => $arr));
            //echo json_encode($p[0]->individual_status);
            /*$this->data['person'] = $p[0];
            $this->page_construct('addpersonprofile.php', $meta, $this->data);*/
        }
        else
        {
            $check_register_no = $this->db->query("select register_no from officer_company where register_no='".addslashes($_POST['register_no'])."' AND user_admin_code_id = '".$this->session->userdata('user_admin_code_id')."'");

            if ($check_register_no->num_rows() > 0 && $_POST['register_no'] != $_POST['old_register_no']) {   
                echo json_encode(array("Status" => 2));
            }
            else
            { 
                $officerCompany = [];
                $officerCompany['created_by'] = $this->session->userdata('user_id');
                $officerCompany['user_admin_code_id'] = $this->session->userdata("user_admin_code_id");
                $officerCompany['field_type'] = $_POST['field_type'];
                $officerCompany['company_name'] = strtoupper($_POST['company_name']);
                $officerCompany['company_former_name'] = strtoupper($_POST['company_former_name']);
                if($_POST['date_of_incorporation'] != "")
                {
                    $dateArr = explode("/", $_POST['date_of_incorporation']);
                    $newDate = $dateArr[2] . '-' . $dateArr[1] . '-' . $dateArr[0];
                    $officerCompany['date_of_incorporation']=$newDate;
                }
                else
                {
                    $officerCompany['date_of_incorporation'] = NULL;
                }
                

                $officerCompany['country_of_incorporation'] = strtoupper($_POST['country_of_incorporation']);
                $officerCompany['register_no'] = strtoupper($_POST['register_no']);

                // $officerCompany['company_corporate_representative'] = strtoupper($_POST['company_corporate_representative']);
                // $officerCompany['identity_number'] = strtoupper($_POST['company_identity_number']);

                //$officerCompany['company_postal_code']=$_POST['company_postal_code'];
                $officerCompany['address_type'] = $_POST['address_type'];
                if(isset($_POST['company_postal_code']))
                {
                    $officerCompany['company_postal_code'] = strtoupper($_POST['company_postal_code']);
                }
                else
                {
                    $officerCompany['company_postal_code'] = "";
                }
                if(isset($_POST['company_street_name']))
                {
                    $officerCompany['company_street_name'] = strtoupper($_POST['company_street_name']);
                }
                else
                {
                    $officerCompany['company_street_name'] = "";
                }
                //$officerCompany['company_street_name']=$_POST['company_street_name'];
                $officerCompany['company_building_name'] = strtoupper($_POST['company_building_name']);
                $officerCompany['company_unit_no1'] = strtoupper($_POST['company_unit_no1']);
                $officerCompany['company_unit_no2'] = strtoupper($_POST['company_unit_no2']);
                if(isset($_POST['company_foreign_address1']))
                {
                    $officerCompany['company_foreign_address1'] = strtoupper($_POST['company_foreign_address1']);
                }
                else
                {
                    $officerCompany['company_foreign_address1'] = "";
                }
                if(isset($_POST['company_foreign_address2']))
                {
                    $officerCompany['company_foreign_address2'] = strtoupper($_POST['company_foreign_address2']);
                }
                else
                {
                    $officerCompany['company_foreign_address2'] = "";
                }
                if(isset($_POST['company_foreign_address3']))
                {
                    $officerCompany['company_foreign_address3'] = strtoupper($_POST['company_foreign_address3']);
                }
                else
                {
                    $officerCompany['company_foreign_address3'] = "";
                }

                if(isset($_POST['hidden_non_verify_checkbox']))
                {
                    $officerCompany['non_verify'] = strtoupper($_POST['hidden_non_verify_checkbox']);
                }
                else
                {
                    $officerCompany['non_verify'] = 0;
                }

                $q = $this->db->query("select register_no from officer_company where register_no='".addslashes($_POST['old_register_no'])."' AND user_admin_code_id = '".$this->session->userdata('user_admin_code_id')."'");
                if ($q->num_rows() > 0) {          
                    //echo "A";
                    $this->db->where('register_no', $_POST['old_register_no']);
                    $this->db->update('officer_company', $officerCompany);
                    $this->session->set_userdata(array(
                            'officer_company_id'  =>  $_POST['officer_company_id'],
                        ));

                    $this->db->delete("officer_company_phone_number",array('officer_company_id'=>$_POST['officer_company_id']));

                    for($g = 0; $g < count($_POST['hidden_company_phone_number']); $g++)
                    {
                        if($_POST['hidden_company_phone_number'][$g] != "")
                        {
                            $companyPhoneMobile['officer_company_id'] = $_POST['officer_company_id'];
                            $companyPhoneMobile['phone_number'] = strtoupper($_POST['hidden_company_phone_number'][$g]);
                            if($_POST['company_phone_number_primary'] == $_POST['hidden_company_phone_number'][$g])
                            {
                                $companyPhoneMobile['primary_phone_number'] = 1;
                            }
                            else
                            {
                                $companyPhoneMobile['primary_phone_number'] = 0;
                            }
                            $this->db->insert('officer_company_phone_number', $companyPhoneMobile);
                        }
                    }

                    $this->db->delete("officer_company_email",array('officer_company_id'=>$_POST['officer_company_id']));

                    for($g = 0; $g < count($_POST['company_email']); $g++)
                    {
                        if($_POST['company_email'][$g] != "")
                        {
                            $companyEmail['officer_company_id'] = $_POST['officer_company_id'];
                            $companyEmail['email'] = strtoupper($_POST['company_email'][$g]);
                            if($_POST['company_email_primary'] == $_POST['company_email'][$g])
                            {
                                $companyEmail['primary_email'] = 1;
                            }
                            else
                            {
                                $companyEmail['primary_email'] = 0;
                            }
                            $this->db->insert('officer_company_email', $companyEmail);
                        }
                    }

                    $this->db->delete("corporate_representative",array('registration_no'=>$_POST['old_register_no']));

                    // $_POST['subsidiary_id'] = array_values($_POST['subsidiary_id']);
                    // $_POST['corp_rep_name'] = array_values($_POST['corp_rep_name']);
                    // $_POST['corp_rep_identity_number'] = array_values($_POST['corp_rep_identity_number']);
                    // $_POST['date_of_appointment'] = array_values($_POST['date_of_appointment']);
                    // $_POST['date_of_cessation'] = array_values($_POST['date_of_cessation']);
                    //echo json_encode($_POST['subsidiary_id']);
                    for($g = 0; $g < count($_POST['subsidiary_name']); $g++)
                    {
                        if($_POST['subsidiary_name'][$g] != "")
                        {
                            $corp_rep['registration_no'] = $_POST['register_no'];
                            $corp_rep['subsidiary_name'] = strtoupper($_POST['subsidiary_name'][$g]);
                            $corp_rep['name_of_corp_rep'] = strtoupper($_POST['corp_rep_name'][$g]);
                            $corp_rep['identity_number'] = strtoupper($_POST['corp_rep_identity_number'][$g]);
                            $corp_rep['effective_date'] = $_POST['date_of_appointment'][$g];
                            $corp_rep['cessation_date'] = $_POST['date_of_cessation'][$g];

                            $this->db->insert('corporate_representative', $corp_rep);
                        }
                    }
                } 
                else 
                {
                    if($_SESSION['group_id'] == 4)
                    {
                        $officerCompany['non_verify'] = 1;
                    }
                    $this->db->insert('officer_company', $officerCompany);
                    $insert_id = $this->db->insert_id();
                        $this->session->set_userdata(array(
                            'officer_company_id'  =>  $insert_id,
                        ));

                    for($g = 0; $g < count($_POST['hidden_company_phone_number']); $g++)
                    {
                        if($_POST['hidden_company_phone_number'][$g] != "")
                        {
                            $companyPhoneMobile['officer_company_id'] = $insert_id;
                            $companyPhoneMobile['phone_number'] = strtoupper($_POST['hidden_company_phone_number'][$g]);
                            if($_POST['company_phone_number_primary'] == $_POST['hidden_company_phone_number'][$g])
                            {
                                $companyPhoneMobile['primary_phone_number'] = 1;
                            }
                            else
                            {
                                $companyPhoneMobile['primary_phone_number'] = 0;
                            }
                            $this->db->insert('officer_company_phone_number', $companyPhoneMobile);
                        }
                    }

                    for($g = 0; $g < count($_POST['company_email']); $g++)
                    {
                        if($_POST['company_email'][$g] != "")
                        {
                            $companyEmail['officer_company_id'] = $insert_id;
                            $companyEmail['email'] = strtoupper($_POST['company_email'][$g]);
                            if($_POST['company_email_primary'] == $_POST['company_email'][$g])
                            {
                                $companyEmail['primary_email'] = 1;
                            }
                            else
                            {
                                $companyEmail['primary_email'] = 0;
                            }
                            $this->db->insert('officer_company_email', $companyEmail);
                        }
                    }

                    for($g = 0; $g < count($_POST['subsidiary_id']); $g++)
                    {
                        if($_POST['subsidiary_id'][$g] != "")
                        {
                            $corp_rep['registration_no'] = $_POST['register_no'];
                            $corp_rep['client_id'] = $_POST['subsidiary_id'][$g];
                            $corp_rep['name_of_corp_rep'] = strtoupper($_POST['corp_rep_name'][$g]);
                            $corp_rep['identity_number'] = strtoupper($_POST['corp_rep_identity_number'][$g]);
                            $corp_rep['effective_date'] = $_POST['date_of_appointment'][$g];
                            $corp_rep['cessation_date'] = $_POST['date_of_cessation'][$g];

                            $this->db->insert('corporate_representative', $corp_rep);
                        }
                    }

                    
                }
                if (isset($_POST['individual_identification_no']))
                {
                    $this->db->delete("officer",array('identification_no'=>$_POST['individual_identification_no']));
                }

                if (count($this->session->userdata('company_files_id')) != 0)
                {
                    $company_files_id = $this->session->userdata('company_files_id');
                    for($i = 0; $i < count($company_files_id); $i++)
                    {
                        $files = $this->db->query("select * from officer_company_files where id='".$company_files_id[$i]."'");
                        $file_info = $files->result_array();

                        $this->db->where('id', $company_files_id[$i]);

                        unlink("./uploads/company_images_or_pdf/".$file_info[0]["file_name"]);

                        $this->db->delete('officer_company_files', array('id' => $company_files_id[$i]));

                        //echo json_encode(unlink("./uploads/images_or_pdf/".$file_info[0]["file_name"]));
                    }
                }
                echo json_encode(array("Status" => 1));
                /*if($_POST["close_page"] == 1)
                {
                    echo "<script>window.close();</script>";
                }
                else
                {
                    echo json_encode(array("Status" => 1));
                }*/
            }
            
        }
    }

    public function uploadCompanyFile()
    {
        /*if(isset($insert_id))
        {*/
            //echo ($this->session->userdata('officer_id'));
            $filesCount = count($_FILES['uploadcompanyimages']['name']);
            //echo json_encode(count($_FILES['uploadimages']['name']));
            for($i = 0; $i < $filesCount; $i++)
            {   //echo json_encode($_FILES['uploadimages']);
                $_FILES['uploadcompanyimage']['name'] = $_FILES['uploadcompanyimages']['name'][$i];
                $_FILES['uploadcompanyimage']['type'] = $_FILES['uploadcompanyimages']['type'][$i];
                $_FILES['uploadcompanyimage']['tmp_name'] = $_FILES['uploadcompanyimages']['tmp_name'][$i];
                $_FILES['uploadcompanyimage']['error'] = $_FILES['uploadcompanyimages']['error'][$i];
                $_FILES['uploadcompanyimage']['size'] = $_FILES['uploadcompanyimages']['size'][$i];

                $uploadPath = './uploads/company_images_or_pdf';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('uploadcompanyimage'))
                {
                    $fileData = $this->upload->data();
                    //echo json_encode($fileData);
                    $uploadData[$i]['officer_company_id'] = $this->session->userdata('officer_company_id');
                    $uploadData[$i]['file_name'] = $fileData['file_name'];
                    /*$uploadData[$i]['created'] = date("Y-m-d H:i:s");
                    $uploadData[$i]['modified'] = date("Y-m-d H:i:s");*/
                }

            }
            //echo json_encode($uploadData);
            if(!empty($uploadData))
            {
                $this->db->insert_batch('officer_company_files',$uploadData);
                
            }
            //redirect("personprofile");
            /*$this->session->unset_userdata('officer_id');*/
        //}
    }

    public function deleteCompanyFile($id)
    {
        //if (count($this->session->userdata('files_id')) == 0)
        //{
            $company_files_id = $this->session->userdata('company_files_id');
            array_push($company_files_id, $id);
            $this->session->set_userdata(array(
                'company_files_id'  =>  $company_files_id,
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

        //echo json_encode($this->session->userdata('files_id'));
    }
}
