<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
require_once('assets/vendor/tcpdf/tcpdf.php');

class Employee extends MX_Controller
{
    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }

        // if(!$this->data['Admin']){
        //     redirect('welcome');
        // }
        $this->load->library(array('encryption', 'session', 'form_validation'));
        $this->load->library('form_validation');
        $this->load->library(array('session','parser'));
        $this->load->model('employee/employee_model');
        $this->load->model('employment_json_model');
        $this->load->model('country_json_model');
        $this->load->model('firm/master_model');
        $this->load->model('offer_letter/offer_letter_model');
        $this->load->model('auth/auth_model');
        $this->load->model('setting/setting_model');
        $this->load->model('action/action_model');


    }

    public function index()
    {   
        $bc   = array(array('link' => '#', 'page' => 'Employee'));
        $meta = array('page_title' => 'Employee', 'bc' => $bc, 'page_name' => 'Employee');

        $this->meta['page_name'] = 'Employee';

        if(!$this->data['Admin'] && !$this->data['Manager']){
            $this->data['staff_list'] = $this->employee_model->get_employeeList($this->user_id);
        }
        else if($this->data['Manager'] && $this->user_id != 79)
        {
            $this->data['staff_list'] = $this->employee_model->get_employeeList($this->user_id,'true');
            $this->data['past_staff_list'] = $this->employee_model->get_past_employeeList($this->user_id,'true');
        }
        else
        {
            $this->data['staff_list'] = $this->employee_model->get_employeeList();
            $this->data['past_staff_list'] = $this->employee_model->get_past_employeeList();
        }

        // $this->page_construct('index.php', $this->meta, $this->data);
        $this->page_construct('index.php', $meta, $this->data);
    }

    public function create()
    {   
        $bc   = array(array('link' => '#', 'page' => 'Create Employee'));
        $meta = array('page_title' => 'Create Employee', 'bc' => $bc, 'page_name' => 'Create Employee');

        $this->meta['page_name'] = 'Create Employee';
        $this->data['nationality_list'] = $this->country_json_model->getNationality();
        $this->data['workpass_list']    = $this->employment_json_model->get_workpass_details();
        $this->data['firm_list']        = $this->master_model->get_firm_dropdown_list();
        $this->data['status_list']      = $this->employee_model->get_employeeStatusList();
        $this->data['department_list']  = $this->employee_model->get_employeeDepartment();
        $this->data['office_list']      = $this->employee_model->get_employeeOffice();
        $this->data['create']           = true;
        $this->data['type_of_leave_list'] = $this->employee_model->get_type_of_leave_list();
        $this->data['other_type_of_leave_list'] = $this->employee_model->get_other_type_of_leave_list();
        // $this->data['secretary_name_list'] = $this->employee_model->get_Secretary_Name_List($this->user_id); // JW
        
        // if($staff_id != null){
        //     $this->data['staff'] = $this->employee_model->get_staff_info($staff_id);
        //     // echo json_encode($this->employee_model->get_staff_info($staff_id));
        // }
        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Employee', base_url('employee'));
        $this->mybreadcrumb->add('Create Employee', base_url());
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        // $this->page_construct('employee/create.php', $this->meta, $this->data);
        $this->page_construct('employee/create.php', $meta, $this->data);
    }

    public function edit($staff_id = NULL)
    {   
        $this->meta['page_name'] = 'Edit Employee';
        $bc   = array(array('link' => '#', 'page' => 'Employee'));
        $meta = array('page_title' => 'Employee', 'bc' => $bc, 'page_name' => 'Employee');

        // print_r($this->session);

        $this->data['nationality_list'] = $this->country_json_model->getNationality();
        $this->data['currency_list']    = $this->employee_model->get_currency_dropdown();
        $this->data['workpass_list']    = $this->employment_json_model->get_workpass_details();
        $this->data['firm_list']        = $this->master_model->get_firm_dropdown_list();
        $this->data['status_list']      = $this->employee_model->get_employeeStatusList();
        $this->data['department_list']  = $this->employee_model->get_employeeDepartment();
        $this->data['office_list']      = $this->employee_model->get_employeeOffice();
        $this->data['create']           = false;
        $this->data['type_of_leave_list'] = $this->employee_model->get_type_of_leave_list();
        $this->data['other_type_of_leave_list'] = $this->employee_model->get_other_type_of_leave_list();
        // $this->data['secretary_name_list'] = $this->employee_model->get_Secretary_Name_List($this->user_id); // JW

        if($staff_id != null){
            
            $this->data['staff'] = $this->employee_model->get_staff_info($staff_id);
            $this->data['family_info'] = $this->employee_model->get_family_info($staff_id);
            $this->data['active_type_of_leave'] = $this->employee_model->get_active_type_of_leave($staff_id);
            // $this->data['telephone'] = $this->employee_model->get_staff_telephone($staff_id);
            $this->data['event_info'] = $this->employee_model->get_event_info($staff_id);
            $this->data['salary'] = $this->employee_model->get_salary_info($staff_id);
            $this->data['bond'] = $this->employee_model->get_bond_info($staff_id);

            // $this->data['bank_info'] = $this->employee_model->get_open_bank_info($staff_id);
        }

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Employee', base_url('employee'));
        $this->mybreadcrumb->add('Edit Employee - '.$this->data['staff'][0]->name, base_url());
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        // $this->page_construct('create.php', $this->meta, $this->data);

        if(!$this->data['Admin'])
        {
            //check for employee under Manager !!!
            $authenticated = $this->auth_model->check_authentication($staff_id, $this->data['Manager']);
        }
        else
        {
            $authenticated = true;
        }
        
        if($authenticated)
        {
            $this->page_construct('create.php', $meta, $this->data);
        }   
    }

    public function create_employee(){
        $form_data = $this->input->post();
        //Attach Singapore PR
        if(isset($_FILES['attachment_singapore_pr']))
        {
            $filesCount = count((array)$_FILES['attachment_singapore_pr']['name']);
            $singapore_pr_attachment = array();
            for($i = 0; $i < $filesCount; $i++)
            {   
                $_FILES['uploadimage_singapore_pr']['name'] = $_FILES['attachment_singapore_pr']['name'][$i];
                $_FILES['uploadimage_singapore_pr']['type'] = $_FILES['attachment_singapore_pr']['type'][$i];
                $_FILES['uploadimage_singapore_pr']['tmp_name'] = $_FILES['attachment_singapore_pr']['tmp_name'][$i];
                $_FILES['uploadimage_singapore_pr']['error'] = $_FILES['attachment_singapore_pr']['error'][$i];
                $_FILES['uploadimage_singapore_pr']['size'] = $_FILES['attachment_singapore_pr']['size'][$i];

                $uploadPath = './uploads/singapore_pr';
                $config['upload_path'] = $uploadPath;
                $config['overwrite'] = TRUE;
                $config['allowed_types'] = '*';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('uploadimage_singapore_pr'))
                {
                    $fileData = $this->upload->data();
                    $singapore_pr_attachment[] = $fileData['file_name'];
                }

                $attachment = json_encode($singapore_pr_attachment);
            }
            if($form_data['hidden_attachment_singapore_pr'] != "")
            {
                $attachment_singapore_pr = $form_data['hidden_attachment_singapore_pr'];
            }
            else
            {
                $attachment_singapore_pr = $attachment;
            }
        }
        else
        {
            $attachment_singapore_pr = '[]';
        }
        
        //Attach NRIC/Passport No
        if(isset($_FILES['attachment_nric']))
        {
            $filesCount = count((array)$_FILES['attachment_nric']['name']);
            $nric_attachment = array();
            for($i = 0; $i < $filesCount; $i++)
            {   
                $_FILES['uploadimage_nric']['name'] = $_FILES['attachment_nric']['name'][$i];
                $_FILES['uploadimage_nric']['type'] = $_FILES['attachment_nric']['type'][$i];
                $_FILES['uploadimage_nric']['tmp_name'] = $_FILES['attachment_nric']['tmp_name'][$i];
                $_FILES['uploadimage_nric']['error'] = $_FILES['attachment_nric']['error'][$i];
                $_FILES['uploadimage_nric']['size'] = $_FILES['attachment_nric']['size'][$i];

                $uploadPath = './uploads/nric';
                $config['upload_path'] = $uploadPath;
                $config['overwrite'] = TRUE;
                $config['allowed_types'] = '*';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('uploadimage_nric'))
                {
                    $fileData = $this->upload->data();
                    $nric_attachment[] = $fileData['file_name'];
                }

                $attachment = json_encode($nric_attachment);
            }
            if($form_data['hidden_attachment_nric'] != "")
            {
                $attachment_nric = $form_data['hidden_attachment_nric'];
            }
            else
            {
                $attachment_nric = $attachment;
            } 
        }
        else
        {
            $attachment_nric = '[]';
        }
        
        //Attach Marital Status
        if(isset($_FILES['attachment_marital_status']))
        {
            $filesCount = count((array)$_FILES['attachment_marital_status']['name']);
            $marital_status_attachment = array();
            for($i = 0; $i < $filesCount; $i++)
            {   
                $_FILES['uploadimage_marital_status']['name'] = $_FILES['attachment_marital_status']['name'][$i];
                $_FILES['uploadimage_marital_status']['type'] = $_FILES['attachment_marital_status']['type'][$i];
                $_FILES['uploadimage_marital_status']['tmp_name'] = $_FILES['attachment_marital_status']['tmp_name'][$i];
                $_FILES['uploadimage_marital_status']['error'] = $_FILES['attachment_marital_status']['error'][$i];
                $_FILES['uploadimage_marital_status']['size'] = $_FILES['attachment_marital_status']['size'][$i];

                $uploadPath = './uploads/marital_status';
                $config['upload_path'] = $uploadPath;
                $config['overwrite'] = TRUE;
                $config['allowed_types'] = '*';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('uploadimage_marital_status'))
                {
                    $fileData = $this->upload->data();
                    $marital_status_attachment[] = $fileData['file_name'];
                }

                $attachment = json_encode($marital_status_attachment);
            }
            if($form_data['hidden_attachment_marital_status'] != "")
            {
                $attachment_marital_status = $form_data['hidden_attachment_marital_status'];
            }
            else
            {
                $attachment_marital_status = $attachment;
            }
        }
        else
        {
            $attachment_marital_status = '[]';
        }
        

        // if($form_data['phone_code']!="" || $form_data['phone_code']!=null){
        //     $hp= '+'.$form_data['phone_code'].$form_data['staff_phoneno'];
        // }else{
        //     $hp= $form_data['staff_phoneno'];
        // }

        if(isset($_POST['hidden_telephone']))
        {
            for($g = 0; $g < count($_POST['hidden_telephone']); $g++)
            {
                if($_POST['hidden_telephone'][$g] != "")
                {
                    $telephone[$g]['employee_id'] = $form_data['staff_id'];
                    $telephone[$g]['telephone'] = strtoupper($_POST['hidden_telephone'][$g]);
                    if($_POST['telephone_primary'] == $_POST['hidden_telephone'][$g])
                    {
                        $telephone[$g]['primary_telephone'] = 1;
                    }
                    else
                    {
                        $telephone[$g]['primary_telephone'] = 0;
                    }
                }
            }
        }
        else
        {
            $telephone = [];
        }

        if($form_data['staff_workpass'] == 'Not Applicable')
        {
            $form_data['staff_pass_expire'] = "";
        }

        $employee = array(
            'id'                        => $form_data['staff_id'],
            'name'                      => strtoupper($form_data['staff_name']),
            'nric_fin_no'               => strtoupper($form_data['staff_nric_finno']),
            'singapore_pr'              => $form_data['hidden_singapore_pr'],
            'attachment_singapore_pr'   => $attachment_singapore_pr,
            'attachment_nric'           => $attachment_nric,
            'address'                   => strtoupper($form_data['staff_address']),
            'nationality_id'            => $form_data['staff_nationality'],
            'dob'                       => date('Y-m-d', strtotime($form_data['staff_DOB'])),
            'gender'                    => $form_data['hidden_gender'],
            'marital_status'            => $form_data['hidden_marital_status'],
            'attachment_marital_status' => $attachment_marital_status,
            'firm_id'                   => $form_data['firm_id'],
            'date_joined'               => empty($form_data['staff_joined'])? NULL:date('Y-m-d', strtotime($form_data['staff_joined'])),
            'date_cessation'            => empty($form_data['staff_cessation'])? NULL:date('Y-m-d', strtotime($form_data['staff_cessation'])),
            'designation'               => strtoupper($form_data['staff_designation']),
            'department'                => $form_data['staff_department'],
            'office'                    => $form_data['staff_office'],
            'workpass'                  => $form_data['staff_workpass'],
            'pass_expire'               => empty($form_data['staff_pass_expire'])? NULL:date('Y-m-d', strtotime($form_data['staff_pass_expire'])),
            'aws_given'                 => $form_data['hidden_staff_aws_given'],
            'cpf_employee'              => $form_data['staff_cpf_employee'],
            'cpf_employer'              => $form_data['staff_cpf_employer'],
            // 'cdac'                      => $form_data['staff_cdac'],
            'remark'                    => $form_data['staff_remark'],
            'supervisor'                => $form_data['staff_supervisor'],
            'employee_status_id'        => $form_data['staff_status'],
            'date_of_letter'            => empty($form_data['date_of_letter'])? NULL:date('Y-m-d', strtotime($form_data['date_of_letter'])),
            'status_date'               => empty($form_data['status_date'])? NULL:date('Y-m-d', strtotime($form_data['status_date'])),
            'pic'                       => empty($form_data['applicant_preview_pic'])?'':$form_data['applicant_preview_pic'],
            'bond'                      => empty($form_data['hidden_bond'])?'':$form_data['hidden_bond'],
            'start_bond'                => empty($form_data['start_bond'])? NULL:date('Y-m-d', strtotime($form_data['start_bond'])),
            'bond_period'               => empty($form_data['bond_period'])?0:$form_data['bond_period'][0],
            'wp_fin_no'                 => empty($form_data['wp_fin_no'])?NULL:$form_data['wp_fin_no'],
            'salary'                    => empty($form_data['staff_salary'])?NULL:$this->encryption->encrypt(trim(strtoupper($form_data['staff_salary'][0]))),
            'bond_allowance'            => empty($form_data['bond_allowance'])?NULL:$this->encryption->encrypt(trim(strtoupper($form_data['bond_allowance'][0]))),
            'pr_issued_date'            => empty($form_data['pr_issued_date'])? NULL:date('Y-m-d', strtotime($form_data['pr_issued_date'])),
        );

        if(gettype($form_data['active']) == 'string')
        {
            $annual_leave = json_decode($form_data['active']);
        }
        else
        {
            $annual_leave = $form_data['active'];
        }

        if(gettype($form_data['leave_days']) == 'string')
        {
            $annual_leave_days = json_decode($form_data['leave_days']);
        }
        else
        {
            $annual_leave_days = $form_data['leave_days'];
        }

        $previous_staff_status = $form_data['previous_staff_status'];
        $staff_status = $form_data['staff_status'];
        $previous_status_date = $form_data['previous_status_date'];

        if(isset($form_data['user_id']))
        {
            $user_id = $form_data['user_id'];
        }
        else
        {
            $user_id = $this->user_id;
        }

        $result = $this->employee_model->create_employee($employee, $annual_leave, $annual_leave_days, $previous_staff_status, $staff_status, $previous_status_date,$user_id,$telephone);

        echo json_encode(array($result));
    }

    public function create_user($employee_id){ 

        $this->meta['page_name'] = 'Create User';
        $this->session->set_flashdata('employee_id', $employee_id);
        $this->data['result_status'] = false;

        $this->data['users_group_dropdown'] = $this->auth_model->get_users_group_dropdown();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Employee', base_url('employee'));
        $this->mybreadcrumb->add('Create User', base_url());
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('auth/create_user_account.php', $this->meta, $this->data);
    }

    public function view_offer_letter(){
        $form_data = $this->input->post();
        // echo json_encode($form_data);
        $employee_id = $form_data['employee_id'];

        $data = $this->offer_letter_model->getEmployee_OL($employee_id);

        $count_offer_letter = $this->db->query("SELECT * FROM payroll_offer_letter_employee ole WHERE ole.employee_id='". $employee_id ."'");
        
        if(count($count_offer_letter->result()) > 1){
            $data[0]->is_employee = true;
        }else{
            $data[0]->is_employee = false;
        }
        
        $data[0]->is_pr_singaporean = false;

        if($data[0]->nationality_id == 165){
            $data[0]->is_pr_singaporean = true;
        }

        $offer_letter_pdf = modules::load('offer_letter/Offer_letter/');
        $return_data      = $offer_letter_pdf->info($data);

        echo $return_data;
        // echo json_encode($data);
    }

    public function get_nationality(){
        $data = $this->country_json_model->getNationality();

        echo json_encode($data);
    }

    public function get_family_relationship(){
        $data = $this->country_json_model->getFamilyRelationship();

        echo json_encode($data);
    }

    public function check_verify_family(){
        $checked = $_POST["checked"];
        $staff_id = $_POST["staff_id"];
        $family_info_id = $_POST["family_info_id"];

        if($checked == "false")
        {
            $data["verify"] = 0;

            $this->db->update("payroll_family_info", $data, array("employee_id" => $staff_id, "id" => $family_info_id));

            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
        }
        else
        {
            $data["verify"] = 1;

            $this->db->update("payroll_family_info", $data, array("employee_id" => $staff_id, "id" => $family_info_id));

            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
        }
    }

    public function add_family_info(){
        for($i = 0; $i < count($_POST['family_info_id']); $i++ )
        {   
            //Proof of Document
            $filesCount = count((array)$_FILES['attachment_proof_of_document']['name']);
            $proof_of_document_attachment = array();
            for($a = 0; $a < $filesCount; $a++)
            {   
                $_FILES['uploadimage_proof_of_document']['name'] = $_FILES['attachment_proof_of_document']['name'][$a];
                $_FILES['uploadimage_proof_of_document']['type'] = $_FILES['attachment_proof_of_document']['type'][$a];
                $_FILES['uploadimage_proof_of_document']['tmp_name'] = $_FILES['attachment_proof_of_document']['tmp_name'][$a];
                $_FILES['uploadimage_proof_of_document']['error'] = $_FILES['attachment_proof_of_document']['error'][$a];
                $_FILES['uploadimage_proof_of_document']['size'] = $_FILES['attachment_proof_of_document']['size'][$a];

                $uploadPath = './uploads/proof_of_document';
                $config['upload_path'] = $uploadPath;
                $config['overwrite'] = TRUE;
                $config['allowed_types'] = '*';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('uploadimage_proof_of_document'))
                {
                    $fileData = $this->upload->data();
                    $proof_of_document_attachment[] = $fileData['file_name'];
                }

                $attachment = json_encode($proof_of_document_attachment);
            }

            if($_POST['hidden_attachment_proof_of_document'] != "")
            {
                $attachment_proof_of_document = $_POST['hidden_attachment_proof_of_document'];
            }
            else
            {
                $attachment_proof_of_document = $attachment;
            }

            $data['employee_id'] = $_POST['employee_id'][$i];
            //$data['family_info_id']=$_POST['family_info_id'][$i];
            $data['family_name']=strtoupper($_POST['family_name'][$i]);
            $data['nric']=strtoupper($_POST['nric'][$i]);
            $data['dob']=empty($_POST['dob'][$i])? NULL:date('Y-m-d', strtotime($_POST['dob'][$i]));
            $data['nationality']=$_POST['nationality'][$i];
            $data['relationship']=$_POST['relationship'][$i];
            $data['contact']=$_POST['contact'][$i];
            $data['proof_of_document']=$attachment_proof_of_document;

            $q = $this->db->get_where("payroll_family_info", array("id" => $_POST['family_info_id'][$i]));

            if (!$q->num_rows())
            {   
                $this->db->insert("payroll_family_info",$data);
                $insert_family_info_id = $this->db->insert_id();

                echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_family_info_id" => $insert_family_info_id));
            }
            else
            {
                $this->db->update("payroll_family_info",$data,array("id" => $_POST['family_info_id'][$i]));

                echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
            }
        }
    }

    public function delete_family_info ()
    {
        $id = $_POST["family_info_id"];

        $data["deleted"] = 1;

        $this->db->update("payroll_family_info", $data, array('id'=>$id));

        echo json_encode(array("Status" => 1));
                
    }

    public function add_salary()
    {

        $form_data = $this->input->post();
        $data = $form_data["data"];
        $event_ids = array();

        $statement_letter_info = array(
            'firm_id'                 => $data[0],
            'name'                    => $data[1],
            'nric/passport'           => $data[2],
            'job_title'               => $data[3],
            'date_of_commencement'    => $data[4],
            'work_hour'               => $data[5],
            'vacation_leave'          => $data[6],
            'date_of_offer'           => $data[7],
            'currency'                => $data[8],
            'new_salary'              => $data[9],
            'effective_date'          => $data[10],
            'promotion_flag'          => $data[11],
            'employee_id'             => $data[12],
            'designation'             => $data[13]
        );

        $today_date = date("d F Y");

        // $offer_letter_pdf = modules::load('offer_letter/CreateEmploymentContractPdf/');

        // $return_data      = $offer_letter_pdf->create_employment_contract_pdf($offer_letter_info);
        // echo $return_data;
        $result = $this->generate_new_ips($statement_letter_info);
        $temp_result = json_decode($result);

        if($statement_letter_info['promotion_flag'])
        {
            $promotion_pdf_info = array($today_date, $statement_letter_info['job_title'], $statement_letter_info['effective_date'], $statement_letter_info['name'], $statement_letter_info['firm_id']);
            $action_controller = modules::load('action/Action/');
            $promotion_pdf_return_data = $action_controller->promotion_progression($promotion_pdf_info);

            $temp_result->link2 = $promotion_pdf_return_data['link'];

            $this->db->where('id', $statement_letter_info['employee_id']);
            $this->db->update("payroll_employee", array("designation" => $statement_letter_info['designation']));

            $payroll_event_info = array('employee_id'=>$statement_letter_info['employee_id'], 'date'=>date('Y-m-d', strtotime($today_date)), 'event'=>11, 'attachment'=>$promotion_pdf_return_data['filename']);
            $this->db->insert("payroll_event_info",$payroll_event_info);
            $event_id = $this->db->insert_id();

            array_push($event_ids, $event_id);


            $result = json_encode($temp_result);

        }

        $payroll_event_info = array('employee_id'=>$statement_letter_info['employee_id'], 'date'=>date('Y-m-d', strtotime($today_date)), 'event'=>13, 'attachment'=>$temp_result->filename);
        $this->db->insert("payroll_event_info",$payroll_event_info);

        $event_id = $this->db->insert_id();
        array_push($event_ids, $event_id);

        $effective_start_date = date('Y-m-d', strtotime($statement_letter_info['effective_date']));
        $last_drawn_date = date('Y-m-d', strtotime($statement_letter_info['effective_date']. ' -1 day'));
        $new_last_drawn_date = null;

        
        $q = $this->db->query("SELECT * FROM payroll_employee_salary WHERE employee_id='".$statement_letter_info['employee_id']."' AND (DATE('".$effective_start_date."') between DATE(effective_start_date) AND DATE(last_drawn_date)) AND deleted=0 AND last_drawn_date IS NOT NULL ORDER BY payroll_employee_salary.effective_start_date DESC");
        if($q->num_rows())
        {
            //if insert salary slotted in between
            $q = $q->result();
            $new_last_drawn_date = $q[0]->last_drawn_date;
            $this->db->update("payroll_employee_salary",array('last_drawn_date'=>$last_drawn_date),array("id" => $q[0]->id));

        }
        else
        {
            $q = $this->db->query("SELECT * FROM payroll_employee_salary WHERE employee_id='".$statement_letter_info['employee_id']."' AND deleted=0 ORDER BY payroll_employee_salary.effective_start_date DESC");
            
            if ($q->num_rows())
            {   
                $q = $q->result();

                if($q[count($q)-1]->last_drawn_date > $effective_start_date)
                {
                    $new_last_drawn_date = date('Y-m-d', strtotime($q[count($q)-1]->effective_start_date. ' -1 day'));
                }
                else if($q[0]->effective_start_date > $effective_start_date)
                {
                    $new_last_drawn_date = date('Y-m-d', strtotime($q[0]->effective_start_date. ' -1 day'));
                }
                else{

                    $new_last_drawn_date = null;
                    $this->db->update("payroll_employee_salary",array('last_drawn_date'=>$last_drawn_date),array("id" => $q[0]->id));         
                }
               
            }
        }
            
        
        $salary_info = array('employee_id'=>$statement_letter_info['employee_id'], 'currency'=>$statement_letter_info['currency'],'salary'=>$this->encryption->encrypt($statement_letter_info['new_salary']), 'effective_start_date'=> $effective_start_date, 'event_ids' => implode(',', $event_ids), 'last_drawn_date' => $new_last_drawn_date);
        $this->db->insert("payroll_employee_salary",$salary_info);


        echo $result;
    }

    public function delete_salary_info ()
    {
        $id = $_POST["salary_info_id"];

        $this_info = $this->db->query("SELECT * FROM payroll_employee_salary WHERE id=".$id);
        $this_info = $this_info->result();
        $this_info = $this_info[0];


        $previous_last_drawn_salary_date = date('Y-m-d', strtotime($this_info->effective_start_date. ' -1 day'));
        $q = $this->db->query("SELECT * FROM payroll_employee_salary WHERE employee_id='".$this_info->employee_id."' AND last_drawn_date='".$previous_last_drawn_salary_date."' AND deleted=0 ORDER BY payroll_employee_salary.last_drawn_date DESC LIMIT 1");

        if($this_info->last_drawn_date == NULL)
        {
            if ($q->num_rows())
            {   
                $q = $q->result();
                $this->db->update("payroll_employee_salary",array('last_drawn_date'=>null),array("id" => $q[0]->id));
            }
        }
        else
        {
            if ($q->num_rows())
            {   
                $q = $q->result();
                $this->db->update("payroll_employee_salary",array('last_drawn_date'=>$this_info->last_drawn_date),array("id" => $q[0]->id));
            }
        }


        $data["deleted"] = 1;

        $event_ids = explode (",", $this_info->event_ids); 

        // delete event(s) attached with salary 
        for ($x = 0; $x <= count($event_ids); $x++) {
            if(isset($event_ids[$x])){
                $this->db->update("payroll_event_info", $data, array('id'=>$event_ids[$x]));
            }
        }
        
        // delete salary 
        $this->db->update("payroll_employee_salary", $data, array('id'=>$id));

        echo json_encode(array("Status" => 1));
    }

    public function add_bond()
    {

        $form_data = $this->input->post();
        $data = $form_data["data"];
        $event_ids = array();

        $statement_letter_info = array(
            'firm_id'                 => $data[0],
            'name'                    => $data[1],
            'nric/passport'           => $data[2],
            'job_title'               => $data[3],
            'bond_period'             => $data[4],
            'currency'                => $data[5],
            'bond_allowance'          => $data[6],
            'bond_start_date'         => $data[7],
            'employee_id'             => $data[8],
        );

        $today_date = date("d F Y");


        $result = $this->generate_new_bond_stmnt($statement_letter_info);
        $temp_result = json_decode($result);
        
        //uncomment
        $payroll_event_info = array('employee_id'=>$statement_letter_info['employee_id'], 'date'=>date('Y-m-d', strtotime($today_date)), 'event'=>14, 'attachment'=>$temp_result->filename);
        $this->db->insert("payroll_event_info",$payroll_event_info);

        $event_id = $this->db->insert_id();
        array_push($event_ids, $event_id);
        //uncomment end

        $bond_start_date = date('Y-m-d', strtotime($statement_letter_info['bond_start_date']));
        $bond_end_date = date('Y-m-d', strtotime($statement_letter_info['bond_start_date']. ' +'.$statement_letter_info['bond_period'].' month'));

     
        
        //uncomment
        $salary_info = array('employee_id'=>$statement_letter_info['employee_id'], 'currency'=>$statement_letter_info['currency'],'bond_allowance'=>$this->encryption->encrypt($statement_letter_info['bond_allowance']), 'bond_start_date'=> $bond_start_date, 'bond_end_date'=>$bond_end_date, 'bond_period'=>$statement_letter_info['bond_period'], 'event_ids' => implode(',', $event_ids));
        $this->db->insert("payroll_employee_bond",$salary_info);
        //uncomment end

        // echo $result;

        echo $result;
    }

    public function delete_bond_info ()
    {
        $id = $_POST["bond_info_id"];

        $this_info = $this->db->query("SELECT * FROM payroll_employee_bond WHERE id=".$id);
        $this_info = $this_info->result();
        $this_info = $this_info[0];

        $data["deleted"] = 1;

        $event_ids = explode (",", $this_info->event_ids); 

        // delete event(s) attached with bond 
        for ($x = 0; $x < count($event_ids); $x++) {
            $this->db->update("payroll_event_info", $data, array('id'=>$event_ids[$x]));
        }
        
        // delete salary 
        $this->db->update("payroll_employee_bond", $data, array('id'=>$id));

        echo json_encode(array("Status" => 1));
    }


    public function get_event_type(){
        $data = $this->employee_model->getEventType();

        echo json_encode($data);
    }

    public function get_designation(){

        $form_data = $this->input->post();

        $department = $form_data['department'];

        if($department == '7'){

            $department = '%%';
        }

        $result = $this->setting_model->get_designation($department);
        echo json_encode($result);

    }

    public function get_notice_period(){

        $form_data = $this->input->post();

        $last_month = date("d F Y", strtotime('today +'. $form_data['notice_period'].'days'));

        echo $last_month;

    }

    public function reemployed(){
        $id = $this->input->post("id");

        $this->db->where('id', $id);
        $this->db->update('payroll_employee', array('employee_status_id' => 2, 'date_cessation' => null));

        echo json_encode(array('Status' => 1 ));
    }
    public function submit_resignation(){

        $form_data = $this->input->post();

        if($form_data['attachment_flag'])
        {
            $filesCount = count((array)$_FILES['attachment_resign_letter']['name']);
            $resign_letter_attachment = array();
            for($i = 0; $i < $filesCount; $i++)
            {   
                $_FILES['uploadimage_resign_letter']['name']     = $_FILES['attachment_resign_letter']['name'];
                $_FILES['uploadimage_resign_letter']['type']     = $_FILES['attachment_resign_letter']['type'];
                $_FILES['uploadimage_resign_letter']['tmp_name'] = $_FILES['attachment_resign_letter']['tmp_name'];
                $_FILES['uploadimage_resign_letter']['error']    = $_FILES['attachment_resign_letter']['error'];
                $_FILES['uploadimage_resign_letter']['size']     = $_FILES['attachment_resign_letter']['size'];

                $uploadPath = './uploads/resignation_letter';
                $config['upload_path'] = $uploadPath;
                $config['overwrite'] = TRUE;
                $config['allowed_types'] = '*';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('uploadimage_resign_letter'))
                {
                    $fileData = $this->upload->data();
                    $resign_letter_attachment[] = $fileData['file_name'];
                }

                $attachment = json_encode($fileData);
            }
        }

        if($form_data['hidden_attachment_resign_letter'] != "")
        {
            $attachment_resign_letter = $form_data['hidden_attachment_resign_letter'];
        }
        else
        {
            $attachment_resign_letter = $attachment;
        }

        $employee = array(
            'employee_id'        => $form_data['employee_id'],
            'notice_period'      => $form_data['notice_period'],
            'resignation_letter' => $attachment_resign_letter,
            'last_day'           => empty($form_data['resign_last_day'])? NULL:date('Y-m-d', strtotime($form_data['resign_last_day'])),
            'last_day_confirmed' => $form_data['last_day_confirmed']
        );

        $this->db->where('id', $form_data['employee_id']);
        $this->db->update('payroll_employee', array('employee_status_id' => 3));

        if($form_data['last_day_confirmed'])
        {
            $this->db->where('id', $form_data['employee_id']);
            $this->db->update('payroll_employee', array('date_cessation' => date('Y-m-d', strtotime($form_data['resign_last_day']))));
        } 

        $result = $this->employee_model->submit_resignation($employee);
        $test = $this->employee_model->resignation_email_notification($employee);

        echo json_encode($result);
    }

    public function get_resignation_details(){

        $form_data = $this->input->post();
        $employee_id = $form_data['employee_id'];

        $query = $this->db->query("SELECT * FROM payroll_employee_resignation WHERE employee_id = ".$employee_id."");
        $query = $query->result_array();

        echo json_encode($query);
        
    }

    public function approve_or_reject_resignation_date(){

        $form_data = $this->input->post();

        $this->db->where('employee_id', $form_data['employee_id']);
        $result = $this->db->update('payroll_employee_resignation', array('last_day_confirmed' => 1));
        $result = $this->db->update('payroll_employee_resignation', array('last_day' => date('Y-m-d', strtotime($form_data['resign_last_day']))));

        $this->db->where('id', $form_data['employee_id']);
        $this->db->update('payroll_employee', array('date_cessation' => date('Y-m-d', strtotime($form_data['resign_last_day']))));

        $data = array(
            'employee_id' => $form_data['employee_id'],
            'status'      => $form_data['status']
        );

        $result = $this->employee_model->resignation_date_approvals_email_notification($data);
        echo json_encode($result);
    }

    public function generate_new_ips($data)
	{
        $array_link = [];
        $obj_pdf= new MYPDFEMP(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title  = "PRINCIPAL STATEMENT";
        $obj_pdf->SetTitle($title);
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetHeaderMargin(10);
        // $obj_pdf->SetFooterMargin(5);
        $obj_pdf->SetMargins(20, 40, 15);
        $obj_pdf->SetFont('helvetica', '', 10);
        $obj_pdf->setFontSubsetting(false);
        // $header_company_info = $this->write_header($data['firm_id']);
        $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$title, $tc=array(0,0,0), $lc=array(0,0,0));
        
        $content = '';
        $obj_pdf->AddPage();

        $doc_content =  $this->db->query("select * from payroll_pending_documents where id = 3");
        $doc_content = $doc_content->result_array();

        $currency_detail = $this->db->query("select * from currency where id =".$data['currency']);
        $currency_detail = $currency_detail->result_array();


        $page = $doc_content[0]['template'];
        $contents_info = $doc_content[0]['template'];

        $pattern = "/{{[^}}]*}}/";
        $subject = $doc_content[0]['template'];
        preg_match_all($pattern, $subject, $matches);

        $toggle_array = $matches[0];

        if(count($toggle_array) != 0)
        {
            for($r = 0; $r < count($toggle_array); $r++)
            {
                $string1 = (str_replace('{{', '',$toggle_array[$r]));
                $string2 = (str_replace('}}', '',$string1));
                
                if($string2 == "job_title")
                {
                    $replace_string = $toggle_array[$r];

                    // $content = $document_info_query[0]["do_number"];
                    $content = $data['job_title'];
                }
                elseif($string2 == "effective_date")
                {
                    $replace_string = $toggle_array[$r];

                    // $date = DateTime::createFromFormat('Y-m-d', $data["date_of_commencement"]);
                    // $commencement_date = $date->format('d F Y');

                    $content = $data["effective_date"];
                }
                elseif($string2 == "work_hour")
                {
                    $replace_string = $toggle_array[$r];

                    $content = $data["work_hour"];
                }
                elseif($string2 == "vacation_leave")
                {
                    $replace_string = $toggle_array[$r];
                    // echo $bank_add;

                    // $content = $document_info_query[0]["order_code"];
                    $content = $data["vacation_leave"];
                }
                elseif($string2 == "salary_w_bond")
                {
                    $replace_string = $toggle_array[$r];

                    $content = $data["new_salary"];

                    // if($detail['former_name'] == "") 
                    // {
                    //     $content = $detail['company_name'];
                    // }
                    // else
                    // {
                    //     $content = $detail['company_name'].'('.$detail['former_name'].')';
                    // }    
                }
                elseif($string2 == "salary_wo_bond")
                {
                    $replace_string = $toggle_array[$r];

                    $content = $data["new_salary"];


                    // if($detail['former_name'] == "") 
                    // {
                    //     $content = $detail['company_name'];
                    // }
                    // else
                    // {
                    //     $content = $detail['company_name'].'('.$detail['former_name'].')';
                    // }    
                }
                elseif($string2 == "currency")
                {
                    $replace_string = $toggle_array[$r];

                    // $content = $document_info_query[0]["method"];
                    $content = $currency_detail[0]['currency'];
                }
    

                $contents_info = str_replace($replace_string, $content, $contents_info);


            }
        }

        $new_content_info = $contents_info;
        // $content .= $page;
        $obj_pdf->writeHTML($new_content_info, true, false, false, false, '');

        


        // $uts = time('Y.m.d H:i:s');
        $uts = time();



        $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/PrincipalStatement - ('.$data['name'].') UTS'.$uts.'.pdf', 'F');

		chmod($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/PrincipalStatement - ('.$data['name'].') UTS'.$uts.'.pdf',0644);

        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

        array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/hrm/pdf/document/PrincipalStatement - ('.$data['name'].') UTS'.$uts.'.pdf');

        return json_encode(array("link" => $array_link, "filename" => "PrincipalStatement - (".$data['name'].") UTS".$uts.".pdf", "data" => $data));
	}

    public function generate_new_bond_stmnt($data)
	{
        $array_link = [];
        $obj_pdf= new MYPDFEMP(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title  = "BOND STATEMENT";
        $obj_pdf->SetTitle($title);
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetHeaderMargin(10);
        // $obj_pdf->SetFooterMargin(5);
        $obj_pdf->SetMargins(20, 40, 15);
        $obj_pdf->SetFont('helvetica', '', 10);
        $obj_pdf->setFontSubsetting(false);
        // $header_company_info = $this->write_header($data['firm_id']);
        $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs='STATEMENT FOR BOND',$tc=array(0,0,0), $lc=array(0,0,0));
        
        $content = '';
        $obj_pdf->AddPage();

        $doc_content =  $this->db->query("select * from payroll_pending_documents where id = 4");
        $doc_content = $doc_content->result_array();

        $currency_detail = $this->db->query("select * from currency where id =".$data['currency']);
        $currency_detail = $currency_detail->result_array();


        $page = $doc_content[0]['template'];
        $contents_info = $doc_content[0]['template'];

        $pattern = "/{{[^}}]*}}/";
        $subject = $doc_content[0]['template'];
        preg_match_all($pattern, $subject, $matches);

        $toggle_array = $matches[0];

        if(count($toggle_array) != 0)
        {
            for($r = 0; $r < count($toggle_array); $r++)
            {
                $string1 = (str_replace('{{', '',$toggle_array[$r]));
                $string2 = (str_replace('}}', '',$string1));
                
                if($string2 == "bond_allowance")
                {
                    $replace_string = $toggle_array[$r];

                    // $content = $document_info_query[0]["do_number"];
                    $content = $data['bond_allowance'];
                }
                elseif($string2 == "bond_period")
                {
                    $replace_string = $toggle_array[$r];

                    // $date = DateTime::createFromFormat('Y-m-d', $data["date_of_commencement"]);
                    // $commencement_date = $date->format('d F Y');

                    $content = $data["bond_period"];
                }
                elseif($string2 == "bond_start_date")
                {
                    $replace_string = $toggle_array[$r];

                    $content = $data["bond_start_date"];
                }
                elseif($string2 == "currency")
                {
                    $replace_string = $toggle_array[$r];

                    // $content = $document_info_query[0]["method"];
                    $content = $currency_detail[0]['currency'];
                }
    
               
    

                $contents_info = str_replace($replace_string, $content, $contents_info);


            }
        }

        $new_content_info = $contents_info;
        // $content .= $page;
        $obj_pdf->writeHTML($new_content_info, true, false, false, false, '');

        


        // $uts = time('Y.m.d H:i:s');
        $uts = time();



        $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/BondStatement - ('.$data['name'].') UTS'.$uts.'.pdf', 'F');

		chmod($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/BondStatement - ('.$data['name'].') UTS'.$uts.'.pdf',0644);

        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

        array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/hrm/pdf/document/BondStatement - ('.$data['name'].') UTS'.$uts.'.pdf');

        return json_encode(array("link" => $array_link, "filename" => "BondStatement - (".$data['name'].") UTS".$uts.".pdf", "data" => $data));
	}


    public function generate_new_declaration(){

        $form_data = $this->input->post();

        $emp_id = $form_data["emp_id"];

        $today = date("Y-m-d", strtotime("today"));
        $thisYear = date("Y", strtotime("today"));

        if($thisYear != '2020')
        {
            $q = $this->db->query(" SELECT * FROM payroll_employee 
                                    INNER JOIN payroll_event_info ON payroll_event_info.employee_id = payroll_employee.id 
                                    INNER JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id 
                                    LEFT JOIN users ON users.id = payroll_user_employee.user_id 
                                    WHERE payroll_employee.id = '".$emp_id."' 
                                    AND payroll_event_info.event = '10'
                                    AND payroll_event_info.deleted = '0'
                                    AND YEAR(payroll_event_info.date) = '".$thisYear."' ");

            // $q = $this->db->query(" SELECT * FROM payroll_employee 
            //                         INNER JOIN payroll_event_info ON payroll_event_info.employee_id = payroll_employee.id 
            //                         INNER JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id 
            //                         LEFT JOIN users ON users.id = payroll_user_employee.user_id 
            //                         WHERE (payroll_employee.date_cessation is null OR DATE(payroll_employee.date_cessation) > DATE('".$today."')) 
            //                         AND payroll_employee.id = '".$emp_id."' 
            //                         AND payroll_event_info.event = '10'
            //                         AND YEAR(payroll_event_info.date) = '".$thisYear."' ");

            if ($q->num_rows() == 0)
            {
                $q2 = $this->db->query(" SELECT * FROM payroll_employee WHERE payroll_employee.id = '".$emp_id."' ");

                $query2 = $q2->result();

                if($query2[0]->employee_status_id != 3 && $query2[0]->employee_status_id != 4)
                {
                    if($query2[0]->date_cessation != null)
                    {
                        $date1 = new DateTime($query2[0]->date_cessation);
                        $date2 = new DateTime($today);

                        if($date1 > $date2)
                        {
                            $result = $this->declaration_letter(date("d F Y", strtotime($today)),$query2[0]->name,$query2[0]->firm_id);
                            echo $result;
                        }
                    }
                    else
                    {
                        $result = $this->declaration_letter(date("d F Y", strtotime($today)),$query2[0]->name,$query2[0]->firm_id);
                        echo $result;
                    }
                }
            }
        }
        else
        {
            $q = $this->db->query(" SELECT * FROM payroll_employee 
                                    INNER JOIN payroll_event_info ON payroll_event_info.employee_id = payroll_employee.id 
                                    INNER JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id 
                                    LEFT JOIN users ON users.id = payroll_user_employee.user_id 
                                    WHERE payroll_employee.id = '".$emp_id."' 
                                    AND payroll_event_info.event = '10'
                                    AND payroll_event_info.deleted = '0'
                                    AND YEAR(payroll_event_info.date) = '".$thisYear."'");

            if ($q->num_rows() == 0)
            {
                $q2 = $this->db->query(" SELECT * FROM payroll_employee WHERE payroll_employee.id = '".$emp_id."' AND YEAR(payroll_employee.created_at) = '2020' AND MONTH(payroll_employee.created_at) >= '7'");

                $query2 = $q2->result();

                if($query2[0]->employee_status_id != 3 && $query2[0]->employee_status_id != 4)
                {
                    if($query2[0]->date_cessation != null)
                    {
                        $date1 = new DateTime($query2[0]->date_cessation);
                        $date2 = new DateTime($today);

                        if($date1 > $date2)
                        {
                            $result = $this->declaration_letter(date("d F Y", strtotime($today)),$query2[0]->name,$query2[0]->firm_id);
                            echo $result;
                        }
                    }
                    else
                    {
                        $result = $this->declaration_letter(date("d F Y", strtotime($today)),$query2[0]->name,$query2[0]->firm_id);
                        echo $result;
                    }
                }
            }
        }
    }

    public function add_event_info()
    {
        $form_data = $this->input->post();

        $data['employee_id'] = $form_data['employee_id'];
        $data['date']        = date('Y-m-d', strtotime('today'));
        $data['event']       = '10';
        $data['attachment']  = $form_data['attachment'];

        $result = $this->db->insert("payroll_event_info",$data);

        echo $result;
    }

    public function declaration_letter($date,$emp_name,$firm_id){

        $thisYear = date("Y");

        $array_link = [];
        $content = '';
        $obj_pdf= new MYPDFEMP(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title  = "Declaration Letter (".$thisYear.")";
        $obj_pdf->SetTitle($title);
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetHeaderMargin(10);
        // $obj_pdf->SetFooterMargin(5);
        $obj_pdf->SetMargins(20, 35, 10);
        $obj_pdf->SetFont('helvetica', '', 10);
        $obj_pdf->setFontSubsetting(false);
        $header_company_info = $this->write_header($firm_id);
        $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_company_info,$tc=array(0,0,0), $lc=array(0,0,0));
        $obj_pdf->AddPage();

        $table_content_start = '<table style="border-collapse: collapse; width: 100%;padding-right:15px;" border="0">';
        $table_content_end = '</table>';

        $content .= $table_content_start;

        $title_content = '<tr><td><p><strong>DECLARATION</strong></p></td></tr><br><br>';
        $content .= $title_content;

        $body_content =' 
                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;"><u>Independence</u></span></p></td></tr><br>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">I confirm to the best of my knowledge and belief that I am in compliance with the independence requirements of Code of Professional Conduct and Ethics under the Fourth Schedule of Accountants (Public Accountants) Rules and the company’s policy on independence. </span></p></td></tr><br>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">To the best of my knowledge and belief, the following matters might affect the independence of the company in providing professional services to its clients. The matters which are required to be listed may include the following, but not limited to:</span></p></td></tr><br>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">
                            <table>
                                <tr><td style="width:12px">a)</td><td style="width:450px">Financial interest in the client.</td></tr>
                                <tr><td style="width:12px">b)</td><td style="width:450px">Loans or guarantees obtained from the client.</td></tr>
                                <tr><td style="width:12px">c)</td><td style="width:450px">Gifts and hospitality received from the client.</td></tr>
                                <tr><td style="width:12px">d)</td><td style="width:450px">Family or personal relationships with the client.</td></tr>
                                <tr><td style="width:12px">e)</td><td style="width:450px">Employment with the client.</td></tr>
                                <tr><td style="width:12px">f)</td><td style="width:450px">Close business relationship with the client.</td></tr>
                                <tr><td style="width:12px">g)</td><td style="width:450px">Others stated in Code of Professional Conduct and Ethics under the Fourth Schedule of Accountants (Public Accountants) Rules.</td></tr>
                            </table>
                        </span></p></td></tr><br>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;"><u>Confidentiality</u></span></p></td></tr><br>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">To ensure continued compliance with the confidentiality requirements of Code of Professional Conduct and Ethics under the Fourth Schedule of Accountants (Public Accountants) Rules and the policy on confidentiality detailed in the Rules, with regards to our professional responsibilities and the protection of our clients, it is essential that the affairs of our clients remain confidential. Confidential information refers to any information about our clients, which comes to an individual’s attention as a result of his or her association with the company, unless such information is publicly available.</span></p></td></tr><br>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">I have read, understood and will ensure compliance with confidentiality requirements of Code of Professional Conduct and Ethics under the Fourth Schedule of Accountants (Public Accountants) Rules and the company’s policy on confidentiality regarding the affairs of the company’s clients.</span></p></td></tr><br>

                        ';

        $content .= $body_content;

        $signature_content ='
                             <p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Confirmed by:</span></p></td></tr>
                             <p>&nbsp;</p>
                             <p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Name: '.$emp_name.'</span></p></td></tr>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Date: '.$date.'</span></p></td></tr>
                            ';
        $content .= $signature_content;


        $content .= $table_content_end;


        $obj_pdf->writeHTML($content, true, false, false, false, '');

        $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Declaration Letter ('.$thisYear.') - '.$emp_name.'.pdf', 'F');

        chmod($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Declaration Letter ('.$thisYear.') - '.$emp_name.'.pdf',0644);

        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

        array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/hrm/pdf/document/Declaration Letter ('.$thisYear.') - '.$emp_name.'.pdf');

        echo json_encode(array("link" => $array_link, "filename" => "Declaration Letter (".$thisYear.") - ".$emp_name.".pdf"));
    }

    public function write_header($firm_id)
    {
        $query = $this->db->query("select firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax from firm 
                                                LEFT JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
                                                LEFT JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
                                                LEFT JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
                                                where firm.id = '".$firm_id."'");
        $query = $query->result_array();

        // Calling getimagesize() function 
        list($width, $height, $type, $attr) = getimagesize(base_url('../secretary/uploads/logo/'.$query[0]["file_name"].'')); 

        $different_w_h = (float)$width - (float)$height;

        if((float)$width > (float)$height && $different_w_h > 100)
        {
            //before width is 25, height is 73.75
            $td_width = 25;
            $td_height = 73.75;
        }
        else
        {
            $td_width = 15;
            $td_height = 83.75;
        }

        if(!empty($query[0]["file_name"]))
        {
            $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
            $img = '<img src="'.$protocol . $_SERVER['SERVER_NAME'].'/secretary/uploads/logo/'. $query[0]["file_name"] .'" height="55" />';
            
            // $img = '<img src="/secretary/uploads/logo/'. $query[0]["file_name"] .'" height="55" />';
        }
        else
        {
            $img = '';
        }

        if( $query[0]["address_type"] == 'Foreign')
        {
            $fax = $query[0]["fax"];

            if(empty($fax))
            {
                $fax = '-';
            }

            $header_content = '<table style="width: 100%; border-collapse: collapse; height: 60px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
                    <tbody>
                    <tr style="height: 60px;">
                        <td style="width: '. $td_width .'%; text-align: left; height: 60px; padding: 5%;" align="center">
                            <table style="border-collapse: collapse; width: 100%;" border="0">
                            <tbody>
                            <tr>
                            <td style="text-align: left; height: 60px;" align="center"><p>'. $img .'  </p></td>
                            </tr>
                            </tbody>
                            </table>
                        </td>
                        <td style="width: 1.25%; text-align: left;">&nbsp;</td>
                        <td style="width: '. $td_height .'%; height: 60px;"><span style="font-size: 18pt;">'.$query[0]["name"].'</span><br /><span style="font-size: 8pt; text-align: left;">UEN: '. $query[0]["registration_no"] .'<br />Address: '. $query[0]["foreign_address2"] .' '.$query[0]["foreign_address3"].'<br />Tel: '. $query[0]["telephone"] .' &nbsp; Fax: '. $fax .'&nbsp;</span></td>
                    </tr>
                    </tbody>
                    </table>';
        }
        else
        {
            $header_content = '<table style="width: 100%; border-collapse: collapse; height: 60px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
                    <tbody>
                    <tr style="height: 60px;">
                        <td style="width: '. $td_width .'%; text-align: left; height: 60px; padding: 5%;" align="center">
                            <table style="border-collapse: collapse; width: 100%;" border="0">
                            <tbody>
                            <tr>
                            <td style="text-align: left; height: 60px;" align="center"><p>'. $img .'  </p></td>
                            </tr>
                            </tbody>
                            </table>
                        </td>
                        <td style="width: 1.25%; text-align: left;">&nbsp;</td>
                        <td style="width: '. $td_height .'%; height: 60px;"><span style="font-size: 18pt;">'.$query[0]["name"].'</span><br /><span style="font-size: 8pt; text-align: left;">UEN: '. $query[0]["registration_no"] .'<br />Address: '. $query[0]["street_name"] .', #'. $query[0]["unit_no1"] .'-'.$query[0]["unit_no2"].' '. $query[0]["building_name"] .', Singapore '. $query[0]["postal_code"] .'<br />Tel: '. $query[0]["telephone"] .' &nbsp; Fax: '. $query[0]["fax"] .'&nbsp;</span></td>
                    </tr>
                    </tbody>
                    </table>';
        }

        return $header_content;
    }

    public function principalStatement(){
        $data = $this->input->post();

        $this->data['employee_data']    = $this->action_model->getEmployeeData($data['id']);
        // print_r($this->data['employee_data']);
        $this->data['department_list']  = $this->action_model->get_employeeDepartment();
        $this->data['currency_list']    = $this->employee_model->get_currency_dropdown();


        $this->load->view('principalStatement', $this->data);
    }

    public function bondStatement(){
        $data = $this->input->post();

        $this->data['employee_data']    = $this->action_model->getEmployeeData($data['id']);
        $this->data['department_list']  = $this->action_model->get_employeeDepartment();
        $this->data['currency_list']    = $this->employee_model->get_currency_dropdown();
        $this->data['bonds']            = $this->employee_model->get_bond_info($data['id']);


        $this->load->view('bondStatement', $this->data);
    }
}

class MYPDFEMP extends TCPDF 
{
    protected $last_page_flag = false;
    protected $total_page = 1;
    protected $one_page_only = false;

    public function Close() {
        $this->last_page_flag = true;

        if($this->total_page == 1){
            $this->one_page_only = true;
        }

        parent::Close();
    }

    public function Header() {
        $headerData = $this->getHeaderData();
        // $this->SetFont('helvetica', 'B', 23);
        // // $this->writeHTMLCell(0, 0, '', '', $headerData['string'], 0, 0, false, "L", true);
        // $this->writeHTML($headerData['string']);

        $this->Cell(175, 0, $headerData['string'], 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    public function Footer() {
        $this->SetY(-18);
        $this->Ln();
        
        // Page number
        if (empty($this->pagegroups)) {
            $pagenumtxt = 'Page '.' '.$this->getAliasNumPage().'/'.$this->getAliasNbPages();
        } else {
            $pagenumtxt = 'Page '.' '.$this->getPageNumGroupAlias().'/'.$this->getPageGroupAlias();
        }

        if(!$this->one_page_only){
            $this->SetY(-18);
            $this->SetFont('helvetica', '', 8);
            $this->Cell(0, 10, $pagenumtxt, 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }
        
        if(!$this->last_page_flag){
           $this->SetY(-18);
        }

        $this->total_page++;

        // FOOTER IMG
        $logoX = 130;
        // $logoFileName = '../secretary/uploads/logo/ISCA_CA.png';
        // $logoFileName = base_url('../secretary/uploads/logo/ISCA_CA.png');
        $logoFileName = base_url().'uploads/logo/ISCA_CA.PNG';
        $logoWidth = 70;
        $logo = $this->Image($logoFileName, $logoX, $this->GetY(), $logoWidth);
    }
}
