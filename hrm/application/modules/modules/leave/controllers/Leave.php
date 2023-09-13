<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Leave extends MX_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }
        
        //$this->load->library('form_validation');
        $this->load->library(array('session','parser'));
        $this->load->model('leave_model');
        $this->load->model('holiday_model');
        $this->load->model('employee/employee_model');
        $this->load->model('employment_json_model');
        //$this->load->model('setting/setting_model');

        if(!$this->data['Admin']){

            $this->employee_id  = $this->employee_model->get_employee_id_from_user_id($this->user_id);

            $employee_data      = $this->employee_model->get_staff_info($this->employee_id)[0];

            if($employee_data != null)
            {
                $this->remaining_al = $employee_data->remaining_annual_leave;
            }
            else
            {
                $this->remaining_al = FALSE;
            }
        }

        // check if it is new year or employee has empty leave record, then create new record for leave remaining record.
        $this->leave_model->reset_number_of_leave();
        //echo json_encode($result);
        $this->meta['page_name'] = 'Leave';
    }
    
    public function test_round(){
        echo round(0 + 7,1);
    }

    public function index()
    {
        $bc   = array(array('link' => '#', 'page' => 'Leave'));
        $meta = array('page_title' => 'Leave', 'bc' => $bc, 'page_name' => 'Leave');

        if(!$this->data['Admin'] && !$this->data['Manager']){
            $this->data['leave_list'] = $this->leave_model->get_employee_leaveList($this->employee_id);
        
            // $this->page_construct('index.php', $this->meta, $this->data);
            $this->page_construct('index.php', $meta, $this->data);
        }
        elseif($this->data['Manager'])
        {
            $this->data['employee_leave_list'] = $this->leave_model->get_employee_leaveList($this->employee_id);
            $this->data['leave_list']   = $this->leave_model->get_leaveList2($this->user_id); //this is for manager
            $this->data['action_list']  = $this->employment_json_model->get_action_result();
            $this->data['history_list'] = $this->leave_model->get_history_leaveList2($this->user_id);
            $this->data['calender_list'] = $this->leave_model->get_calender_leaveList2($this->user_id);
            $this->data['holiday_list'] = $this->leave_model->get_calender_holidayList();

            $this->data['latest_leave_list'] = $this->leave_model->get_latest_leave_list2($this->user_id);

            foreach($this->data['history_list'] as $row){
                $row->status = $this->employment_json_model->get_action_name($row->status);
            }

            // $this->page_construct('index_manager.php', $this->meta, $this->data);
            $this->page_construct('index_manager.php', $meta, $this->data);
        }else{
            $this->data['employee_list']     = $this->leave_model->get_employeeList2();
            $this->data['leave_list']        = $this->leave_model->get_leaveList(); //this is for admin
            $this->data['action_list']       = $this->employment_json_model->get_action_result();
            $this->data['history_list']      = $this->leave_model->get_history_leaveList();
            $this->data['calender_list']     = $this->leave_model->get_calender_leaveList();
            $this->data['holiday_list']      = $this->leave_model->get_calender_holidayList();
            $this->data['latest_leave_list'] = $this->leave_model->get_latest_leave_list();
            $this->data['department']        = $this->leave_model->get_department_list();
            $this->data['office']            = $this->leave_model->get_office_list();

            foreach($this->data['history_list'] as $row){
                $row->status = $this->employment_json_model->get_action_name($row->status);
            }

            // $this->page_construct('index_admin.php', $this->meta, $this->data);
            $this->page_construct('index_admin.php', $meta, $this->data);
        }
        
    }

    public function get_the_balance()
    {
        $form_data = $this->input->post();

        $employee_id = $form_data['employee_id'];
        $type_of_leave_id = $form_data['type_of_leave_id'];

        if($type_of_leave_id == 1 || $type_of_leave_id == 2 || $type_of_leave_id == 3)
        {
            $q2 = $this->db->query("SELECT * FROM payroll_employee_annual_leave WHERE employee_id='". $employee_id ."' AND type_of_leave_id = '". $type_of_leave_id ."' AND year(last_updated) = YEAR(CURDATE()) AND last_updated = (SELECT MAX(last_updated) FROM `payroll_employee_annual_leave` WHERE employee_id = ". $employee_id ." AND type_of_leave_id = ".$type_of_leave_id.")");

            if($q2->num_rows())
            {
                $annual_leave_days = $q2->result()[0];
            }
            else
            {
                $annual_leave_days = false;
            }

            echo json_encode(array($annual_leave_days));
        }

    }

    public function apply_leave($leave_id = NULL, $status = NULL)
    {   
        // $this->load->library('mybreadcrumb');
        // $this->mybreadcrumb->add('Create Interview', base_url('Create_Interview'));

        // $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $bc = array(array('link' => '#', 'page' => 'Apply Leave'));
        $meta = array('page_title' => 'Apply Leave', 'bc' => $bc, 'page_name' => 'Apply Leave');

        $this->meta['page_name'] = 'Apply Leave';

        if(!$leave_id == NULL){
            $leave_details = $this->leave_model->get_leave_details($leave_id);
        }
        
        // echo json_encode($this->employee_id);
        // echo json_encode($this->user_id);

        if($leave_id == NULL){
            $leave_array = array(
                'employee_id'=> $this->employee_id,
                'user_id'    => $this->user_id,
                'leave_id'   => '',
                'start_date' => '',
                'start_time' => '',
                'end_date'   => '',
                'end_time'   => '',
                'total_leave_apply'  => 0,
                'status'     => '',
                // 'days_left_before'   => 
                // 'total_remaining_al' => $this->remaining_al
            );
        }else{
            $leave_array = array(
                'employee_id'               => $leave_details[0]->employee_id,
                'user_id'                   => $leave_details[0]->user_id,
                'leave_id'                  => $leave_details[0]->id,
                'type_of_leave_id'          => $leave_details[0]->type_of_leave_id,
                'balance_before_approve'    => $leave_details[0]->balance_before_approve,
                'start_date'                => date('m/d/Y', strtotime($leave_details[0]->start_date)),
                'start_time'                => $leave_details[0]->start_time,
                'end_date'                  => date('m/d/Y', strtotime($leave_details[0]->end_date)),
                'end_time'                  => $leave_details[0]->end_time,
                'total_leave_apply'         => $leave_details[0]->total_days,
                'status'                    => $leave_details[0]->status,
                'medical_cert'              => $leave_details[0]->medical_cert,
                'birth_cert'                => $leave_details[0]->birth_cert,
                'death_cert'                => $leave_details[0]->death_cert,
                'married_cert'              => $leave_details[0]->married_cert,
                'exam_schedule'             => $leave_details[0]->exam_schedule,
                'relation'                  => $leave_details[0]->relation,
                'institution'               => $leave_details[0]->institution,
                'num_of_subject'            => $leave_details[0]->num_of_subject,
                'child_dob'                 => $leave_details[0]->child_dob,
                'child_is'                  => $leave_details[0]->child_is,
                // 'total_remaining_al' => $leave_details->days_left_after
            );
        }

        $this->data['leave_data']               = $leave_array;
        $this->data['employee_list']            = $this->leave_model->get_employeeList();
        $this->data['start_time_list']          = $this->holiday_model->get_Leave_StartTime($leave_array['employee_id']);
        $this->data['end_time_list']            = $this->holiday_model->get_Leave_EndTime($leave_array['employee_id']);
        $this->data['active_type_of_leave']     = $this->leave_model->get_active_type_of_leave_list($leave_array['employee_id']);
        $this->data['block_leave_list']         = $this->leave_model->get_calander_block_leave_list($leave_array['employee_id']);
        $this->data['other_type_of_leave_list'] = $this->leave_model->get_other_type_of_leave_list();
        $this->data['staff']                    = $this->leave_model->get_staff_info($leave_array['employee_id']);
        $this->data['relationship']             = $this->leave_model->get_all_relationship();
        $this->data['institution']              = $this->leave_model->get_institution();
        $this->data['status']                   = $status;

        $this->load->library('mybreadcrumb');

        if(!$leave_id == NULL){
            $this->mybreadcrumb->add('Leave', base_url('leave'));
            $this->mybreadcrumb->add('Apply Leave - '.$leave_details[0]->name, base_url());
        }else{
            $this->mybreadcrumb->add('Leave', base_url('leave'));
            $this->mybreadcrumb->add('Apply Leave', base_url());
        }

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        $this->page_construct('leave/apply_leave.php', $meta, $this->data);
    }

    public function apply_leave_admin($leave_id = NULL, $status = NULL)
    {   
        $bc = array(array('link' => '#', 'page' => 'Apply Leave'));
        $meta = array('page_title' => 'Apply Leave', 'bc' => $bc, 'page_name' => 'Apply Leave');
        $this->meta['page_name'] = 'Apply Leave';

        $this->data['employee_list']            = $this->leave_model->get_employeeList();
        $this->data['other_type_of_leave_list'] = $this->leave_model->get_other_type_of_leave_list();
        $this->data['relationship']             = $this->leave_model->get_all_relationship();
        $this->data['institution']              = $this->leave_model->get_institution();
        $this->data['active_type_of_leave']     = "";
        $this->data['start_time_list']          = "";
        $this->data['end_time_list']            = "";

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Leave', base_url('leave'));
        $this->mybreadcrumb->add('Apply Leave', base_url());
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        $this->page_construct('leave/apply_leave_admin.php', $meta, $this->data);
    }

    public function get_Staff_info()
    {
        $form_data = $this->input->post();
        $result = $this->leave_model->get_staff_info($form_data['employee_id']);
        echo json_encode($result);
    }

    public function submit_leave(){

        $form_data = $this->input->post();

        if($form_data['type_of_leave_id'] == 2)
        {
            $attachment = '';

            if(isset($_FILES['attachment_medical_cert']['name'])?TRUE:FALSE)
            {
                $filesCount = count((array)$_FILES['attachment_medical_cert']['name']);
                $letter_attachment = array();
                for($i = 0; $i < $filesCount; $i++)
                {   
                    $_FILES['uploadimage_medical_cert']['name']     = $_FILES['attachment_medical_cert']['name'];
                    $_FILES['uploadimage_medical_cert']['type']     = $_FILES['attachment_medical_cert']['type'];
                    $_FILES['uploadimage_medical_cert']['tmp_name'] = $_FILES['attachment_medical_cert']['tmp_name'];
                    $_FILES['uploadimage_medical_cert']['error']    = $_FILES['attachment_medical_cert']['error'];
                    $_FILES['uploadimage_medical_cert']['size']     = $_FILES['attachment_medical_cert']['size'];

                    $uploadPath = './uploads/leave_attachment';
                    $config['upload_path'] = $uploadPath;
                    $config['overwrite'] = TRUE;
                    $config['allowed_types'] = '*';
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('uploadimage_medical_cert'))
                    {
                        $fileData = $this->upload->data();
                        $letter_attachment[] = $fileData['file_name'];
                    }
                }

                $attachment = json_encode($letter_attachment);
            }

            $leave_data = array(
                'id'         => $form_data['leave_id'],
                'employee_id'=> $form_data['employee_id'],
                'type_of_leave_id'=> $form_data['type_of_leave_id'],
                'balance_before_approve'=> $form_data['balance'],
                'start_date' => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_start_date']))),
                'start_time' => $form_data['leave_start_time'],
                'end_date'   => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_end_date']))),
                'end_time'   => $form_data['leave_end_time'],
                'total_days' => $form_data['leave_total_days'],
                'status'     => 1,
                'medical_cert' => $attachment
            );
        }
        else if($form_data['type_of_leave_id'] == 4)
        {
            $this->db->where('id', $form_data['relation']);
            $this->db->update('payroll_family_info', array('funeral_flag' => 1));

            $attachment = '';

            if(isset($_FILES['attachment_death_certificate']['name'])?TRUE:FALSE)
            {
                $filesCount = count((array)$_FILES['attachment_death_certificate']['name']);
                $letter_attachment = array();
                for($i = 0; $i < $filesCount; $i++)
                {   
                    $_FILES['uploadimage_death_certificate']['name']     = $_FILES['attachment_death_certificate']['name'];
                    $_FILES['uploadimage_death_certificate']['type']     = $_FILES['attachment_death_certificate']['type'];
                    $_FILES['uploadimage_death_certificate']['tmp_name'] = $_FILES['attachment_death_certificate']['tmp_name'];
                    $_FILES['uploadimage_death_certificate']['error']    = $_FILES['attachment_death_certificate']['error'];
                    $_FILES['uploadimage_death_certificate']['size']     = $_FILES['attachment_death_certificate']['size'];

                    $uploadPath = './uploads/leave_attachment';
                    $config['upload_path'] = $uploadPath;
                    $config['overwrite'] = TRUE;
                    $config['allowed_types'] = '*';
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('uploadimage_death_certificate'))
                    {
                        $fileData = $this->upload->data();
                        $letter_attachment[] = $fileData['file_name'];
                    }
                }

                $attachment = json_encode($letter_attachment);
            }

            $leave_data = array(
                'id'         => $form_data['leave_id'],
                'employee_id'=> $form_data['employee_id'],
                'type_of_leave_id'=> $form_data['type_of_leave_id'],
                'balance_before_approve'=> $form_data['balance'],
                'start_date' => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_start_date']))),
                'start_time' => $form_data['leave_start_time'],
                'end_date'   => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_end_date']))),
                'end_time'   => $form_data['leave_end_time'],
                'total_days' => $form_data['leave_total_days'],
                'status'     => 1,
                'relation'   => $form_data['relation'],
                'death_cert' => $attachment
            );
        }
        else if($form_data['type_of_leave_id'] == 5)
        {
            $attachment1 = '';

            if(isset($_FILES['attachment_birth_cert']['name'])?TRUE:FALSE)
            {
                $filesCount = count((array)$_FILES['attachment_birth_cert']['name']);
                $letter_attachment = array();
                for($i = 0; $i < $filesCount; $i++)
                {   
                    $_FILES['uploadimage_birth_cert']['name']     = $_FILES['attachment_birth_cert']['name'];
                    $_FILES['uploadimage_birth_cert']['type']     = $_FILES['attachment_birth_cert']['type'];
                    $_FILES['uploadimage_birth_cert']['tmp_name'] = $_FILES['attachment_birth_cert']['tmp_name'];
                    $_FILES['uploadimage_birth_cert']['error']    = $_FILES['attachment_birth_cert']['error'];
                    $_FILES['uploadimage_birth_cert']['size']     = $_FILES['attachment_birth_cert']['size'];

                    $uploadPath = './uploads/leave_attachment';
                    $config['upload_path'] = $uploadPath;
                    $config['overwrite'] = TRUE;
                    $config['allowed_types'] = '*';
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('uploadimage_birth_cert'))
                    {
                        $fileData = $this->upload->data();
                        $letter_attachment[] = $fileData['file_name'];
                    }
                }

                $attachment1 = json_encode($letter_attachment);
            }

            // $attachment2 = '';

            // if(isset($_FILES['attachment_married_cert']['name'])?TRUE:FALSE)
            // {
            //     $filesCount = count($_FILES['attachment_married_cert']['name']);
            //     $letter_attachment = array();
            //     for($i = 0; $i < $filesCount; $i++)
            //     {   
            //         $_FILES['uploadimage_married_cert']['name']     = $_FILES['attachment_married_cert']['name'];
            //         $_FILES['uploadimage_married_cert']['type']     = $_FILES['attachment_married_cert']['type'];
            //         $_FILES['uploadimage_married_cert']['tmp_name'] = $_FILES['attachment_married_cert']['tmp_name'];
            //         $_FILES['uploadimage_married_cert']['error']    = $_FILES['attachment_married_cert']['error'];
            //         $_FILES['uploadimage_married_cert']['size']     = $_FILES['attachment_married_cert']['size'];

            //         $uploadPath = './uploads/leave_attachment';
            //         $config['upload_path'] = $uploadPath;
            //         $config['overwrite'] = TRUE;
            //         $config['allowed_types'] = '*';
                    
            //         $this->load->library('upload', $config);
            //         $this->upload->initialize($config);

            //         if($this->upload->do_upload('uploadimage_married_cert'))
            //         {
            //             $fileData = $this->upload->data();
            //             $letter_attachment[] = $fileData['file_name'];
            //         }
            //     }

            //     $attachment2 = json_encode($letter_attachment);
            // }

            $leave_data = array(
                'id'         => $form_data['leave_id'],
                'employee_id'=> $form_data['employee_id'],
                'type_of_leave_id'=> $form_data['type_of_leave_id'],
                'balance_before_approve'=> $form_data['balance'],
                'start_date' => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_start_date']))),
                'start_time' => $form_data['leave_start_time'],
                'end_date'   => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_end_date']))),
                'end_time'   => $form_data['leave_end_time'],
                'total_days' => $form_data['leave_total_days'],
                'status'     => 1,
                'birth_cert' => $attachment1,
                // 'married_cert' =>$attachment2,
                'child_dob'    => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_child_dob']))),
                'child_is'     => $form_data['child_is'],
            );

            if(isset($form_data['perious_child_dob'])?TRUE:FALSE)
            {
                $leave_data['perious_child_dob'] = $form_data['perious_child_dob'];
            }
            else
            {
                $leave_data['perious_child_dob'] = '';
            }
            
        }
        else if($form_data['type_of_leave_id'] == 6)
        {
            $attachment1 = '';

            if(isset($_FILES['attachment_birth_cert']['name'])?TRUE:FALSE)
            {
                $filesCount = count((array)$_FILES['attachment_birth_cert']['name']);
                $letter_attachment = array();
                for($i = 0; $i < $filesCount; $i++)
                {   
                    $_FILES['uploadimage_birth_cert']['name']     = $_FILES['attachment_birth_cert']['name'];
                    $_FILES['uploadimage_birth_cert']['type']     = $_FILES['attachment_birth_cert']['type'];
                    $_FILES['uploadimage_birth_cert']['tmp_name'] = $_FILES['attachment_birth_cert']['tmp_name'];
                    $_FILES['uploadimage_birth_cert']['error']    = $_FILES['attachment_birth_cert']['error'];
                    $_FILES['uploadimage_birth_cert']['size']     = $_FILES['attachment_birth_cert']['size'];

                    $uploadPath = './uploads/leave_attachment';
                    $config['upload_path'] = $uploadPath;
                    $config['overwrite'] = TRUE;
                    $config['allowed_types'] = '*';
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('uploadimage_birth_cert'))
                    {
                        $fileData = $this->upload->data();
                        $letter_attachment[] = $fileData['file_name'];
                    }
                }

                $attachment1 = json_encode($letter_attachment);
            }

            $attachment2 = '';

            if(isset($_FILES['attachment_married_cert']['name'])?TRUE:FALSE)
            {
                $filesCount = count((array)$_FILES['attachment_married_cert']['name']);
                $letter_attachment = array();
                for($i = 0; $i < $filesCount; $i++)
                {   
                    $_FILES['uploadimage_married_cert']['name']     = $_FILES['attachment_married_cert']['name'];
                    $_FILES['uploadimage_married_cert']['type']     = $_FILES['attachment_married_cert']['type'];
                    $_FILES['uploadimage_married_cert']['tmp_name'] = $_FILES['attachment_married_cert']['tmp_name'];
                    $_FILES['uploadimage_married_cert']['error']    = $_FILES['attachment_married_cert']['error'];
                    $_FILES['uploadimage_married_cert']['size']     = $_FILES['attachment_married_cert']['size'];

                    $uploadPath = './uploads/leave_attachment';
                    $config['upload_path'] = $uploadPath;
                    $config['overwrite'] = TRUE;
                    $config['allowed_types'] = '*';
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('uploadimage_married_cert'))
                    {
                        $fileData = $this->upload->data();
                        $letter_attachment[] = $fileData['file_name'];
                    }
                }

                $attachment2 = json_encode($letter_attachment);
            }

            $leave_data = array(
                'id'         => $form_data['leave_id'],
                'employee_id'=> $form_data['employee_id'],
                'type_of_leave_id'=> $form_data['type_of_leave_id'],
                'balance_before_approve'=> $form_data['balance'],
                'start_date' => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_start_date']))),
                'start_time' => $form_data['leave_start_time'],
                'end_date'   => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_end_date']))),
                'end_time'   => $form_data['leave_end_time'],
                'total_days' => $form_data['leave_total_days'],
                'status'     => 1,
                'birth_cert' => $attachment1,
                'married_cert' =>$attachment2
            );
        }
        else if($form_data['type_of_leave_id'] == 7)
        {
            $attachment = '';

            if(isset($_FILES['attachment_birth_cert']['name'])?TRUE:FALSE)
            {
                $filesCount = count((array)$_FILES['attachment_birth_cert']['name']);
                $letter_attachment = array();
                for($i = 0; $i < $filesCount; $i++)
                {   
                    $_FILES['uploadimage_birth_cert']['name']     = $_FILES['attachment_birth_cert']['name'];
                    $_FILES['uploadimage_birth_cert']['type']     = $_FILES['attachment_birth_cert']['type'];
                    $_FILES['uploadimage_birth_cert']['tmp_name'] = $_FILES['attachment_birth_cert']['tmp_name'];
                    $_FILES['uploadimage_birth_cert']['error']    = $_FILES['attachment_birth_cert']['error'];
                    $_FILES['uploadimage_birth_cert']['size']     = $_FILES['attachment_birth_cert']['size'];

                    $uploadPath = './uploads/leave_attachment';
                    $config['upload_path'] = $uploadPath;
                    $config['overwrite'] = TRUE;
                    $config['allowed_types'] = '*';
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('uploadimage_birth_cert'))
                    {
                        $fileData = $this->upload->data();
                        $letter_attachment[] = $fileData['file_name'];
                    }
                }

                $attachment = json_encode($letter_attachment);
            }

            $leave_data = array(
                'id'         => $form_data['leave_id'],
                'employee_id'=> $form_data['employee_id'],
                'type_of_leave_id'=> $form_data['type_of_leave_id'],
                'balance_before_approve'=> $form_data['balance'],
                'start_date' => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_start_date']))),
                'start_time' => $form_data['leave_start_time'],
                'end_date'   => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_end_date']))),
                'end_time'   => $form_data['leave_end_time'],
                'total_days' => $form_data['leave_total_days'],
                'status'     => 1,
                'birth_cert' => $attachment
            );
        }
        else if($form_data['type_of_leave_id'] == 8)
        {
            $attachment = '';

            if(isset($_FILES['attachment_exam_schedule']['name'])?TRUE:FALSE)
            {
                $filesCount = count((array)$_FILES['attachment_exam_schedule']['name']);
                $letter_attachment = array();
                for($i = 0; $i < $filesCount; $i++)
                {   
                    $_FILES['uploadimage_exam_schedule']['name']     = $_FILES['attachment_exam_schedule']['name'];
                    $_FILES['uploadimage_exam_schedule']['type']     = $_FILES['attachment_exam_schedule']['type'];
                    $_FILES['uploadimage_exam_schedule']['tmp_name'] = $_FILES['attachment_exam_schedule']['tmp_name'];
                    $_FILES['uploadimage_exam_schedule']['error']    = $_FILES['attachment_exam_schedule']['error'];
                    $_FILES['uploadimage_exam_schedule']['size']     = $_FILES['attachment_exam_schedule']['size'];

                    $uploadPath = './uploads/leave_attachment';
                    $config['upload_path'] = $uploadPath;
                    $config['overwrite'] = TRUE;
                    $config['allowed_types'] = '*';
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('uploadimage_exam_schedule'))
                    {
                        $fileData = $this->upload->data();
                        $letter_attachment[] = $fileData['file_name'];
                    }
                }

                $attachment = json_encode($letter_attachment);
            }

            $leave_data = array(
                'id'         => $form_data['leave_id'],
                'employee_id'=> $form_data['employee_id'],
                'type_of_leave_id'=> $form_data['type_of_leave_id'],
                'balance_before_approve'=> $form_data['balance'],
                'start_date' => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_start_date']))),
                'start_time' => $form_data['leave_start_time'],
                'end_date'   => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_end_date']))),
                'end_time'   => $form_data['leave_end_time'],
                'total_days' => $form_data['leave_total_days'],
                'status'     => 1,
                'exam_schedule' => $attachment,
                'institution'   => $form_data['institution'],
                'num_of_subject'=> $form_data['num_of_subject']
            );
        }
        else if($form_data['type_of_leave_id'] == 9)
        {
            $attachment = '';

            if(isset($_FILES['attachment_married_cert']['name'])?TRUE:FALSE)
            {
                $filesCount = count((array)$_FILES['attachment_married_cert']['name']);
                $letter_attachment = array();
                for($i = 0; $i < $filesCount; $i++)
                {   
                    $_FILES['uploadimage_married_cert']['name']     = $_FILES['attachment_married_cert']['name'];
                    $_FILES['uploadimage_married_cert']['type']     = $_FILES['attachment_married_cert']['type'];
                    $_FILES['uploadimage_married_cert']['tmp_name'] = $_FILES['attachment_married_cert']['tmp_name'];
                    $_FILES['uploadimage_married_cert']['error']    = $_FILES['attachment_married_cert']['error'];
                    $_FILES['uploadimage_married_cert']['size']     = $_FILES['attachment_married_cert']['size'];

                    $uploadPath = './uploads/leave_attachment';
                    $config['upload_path'] = $uploadPath;
                    $config['overwrite'] = TRUE;
                    $config['allowed_types'] = '*';
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('uploadimage_married_cert'))
                    {
                        $fileData = $this->upload->data();
                        $letter_attachment[] = $fileData['file_name'];
                    }
                }

                $attachment = json_encode($letter_attachment);
            }

            $leave_data = array(
                'id'         => $form_data['leave_id'],
                'employee_id'=> $form_data['employee_id'],
                'type_of_leave_id'=> $form_data['type_of_leave_id'],
                'balance_before_approve'=> $form_data['balance'],
                'start_date' => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_start_date']))),
                'start_time' => $form_data['leave_start_time'],
                'end_date'   => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_end_date']))),
                'end_time'   => $form_data['leave_end_time'],
                'total_days' => $form_data['leave_total_days'],
                'status'     => 1,
                'married_cert' =>$attachment
            );
        }
        else
        {
            $leave_data = array(
                'id'         => $form_data['leave_id'],
                'employee_id'=> $form_data['employee_id'],
                'type_of_leave_id'=> $form_data['type_of_leave_id'],
                'balance_before_approve'=> $form_data['balance'],
                'start_date' => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_start_date']))),
                'start_time' => $form_data['leave_start_time'],
                'end_date'   => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_end_date']))),
                'end_time'   => $form_data['leave_end_time'],
                'total_days' => $form_data['leave_total_days'],
                'status'     => 1
            );
        }
        
        if($form_data['leave_id'] == '')
        {
            $email = $this->leave_model->leave_application_email($this->user_id,$leave_data);
        }

        if(isset($form_data['status'])?TRUE:FALSE)
        {
            $leave_data['status'] = $form_data['status'];

            if(isset($form_data['relation'])?TRUE:FALSE && ($leave_data['status'] == 3 || $leave_data['status'] == 4))
            {
                $this->db->where('id', $form_data['relation']);
                $this->db->update('payroll_family_info', array('funeral_flag' => 0));
            }
        }
        
        $result = $this->leave_model->apply_leave($leave_data);
        echo json_encode($result[0]);
    }

    public function submit_leave_for_employee()
    {
        $form_data = $this->input->post();

        if($form_data['type_of_leave_id'] == 2)
        {
            $attachment = '';

            if(isset($_FILES['attachment_medical_cert']['name'])?TRUE:FALSE)
            {
                $filesCount = count((array)$_FILES['attachment_medical_cert']['name']);
                $letter_attachment = array();
                for($i = 0; $i < $filesCount; $i++)
                {   
                    $_FILES['uploadimage_medical_cert']['name']     = $_FILES['attachment_medical_cert']['name'];
                    $_FILES['uploadimage_medical_cert']['type']     = $_FILES['attachment_medical_cert']['type'];
                    $_FILES['uploadimage_medical_cert']['tmp_name'] = $_FILES['attachment_medical_cert']['tmp_name'];
                    $_FILES['uploadimage_medical_cert']['error']    = $_FILES['attachment_medical_cert']['error'];
                    $_FILES['uploadimage_medical_cert']['size']     = $_FILES['attachment_medical_cert']['size'];

                    $uploadPath = './uploads/leave_attachment';
                    $config['upload_path'] = $uploadPath;
                    $config['overwrite'] = TRUE;
                    $config['allowed_types'] = '*';
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('uploadimage_medical_cert'))
                    {
                        $fileData = $this->upload->data();
                        $letter_attachment[] = $fileData['file_name'];
                    }
                }

                $attachment = json_encode($letter_attachment);
            }

            $leave_data = array(
                'id'         => $form_data['leave_id'],
                'employee_id'=> $form_data['employee_id'],
                'type_of_leave_id'=> $form_data['type_of_leave_id'],
                'balance_before_approve'=> $form_data['balance'],
                'start_date' => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_start_date']))),
                'start_time' => $form_data['leave_start_time'],
                'end_date'   => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_end_date']))),
                'end_time'   => $form_data['leave_end_time'],
                'total_days' => $form_data['leave_total_days'],
                'status'     => 1,
                'medical_cert' => $attachment
            );
        }
        else if($form_data['type_of_leave_id'] == 4)
        {
            $this->db->where('id', $form_data['relation']);
            $this->db->update('payroll_family_info', array('funeral_flag' => 1));

            $attachment = '';

            if(isset($_FILES['attachment_death_certificate']['name'])?TRUE:FALSE)
            {
                $filesCount = count((array)$_FILES['attachment_death_certificate']['name']);
                $letter_attachment = array();
                for($i = 0; $i < $filesCount; $i++)
                {   
                    $_FILES['uploadimage_death_certificate']['name']     = $_FILES['attachment_death_certificate']['name'];
                    $_FILES['uploadimage_death_certificate']['type']     = $_FILES['attachment_death_certificate']['type'];
                    $_FILES['uploadimage_death_certificate']['tmp_name'] = $_FILES['attachment_death_certificate']['tmp_name'];
                    $_FILES['uploadimage_death_certificate']['error']    = $_FILES['attachment_death_certificate']['error'];
                    $_FILES['uploadimage_death_certificate']['size']     = $_FILES['attachment_death_certificate']['size'];

                    $uploadPath = './uploads/leave_attachment';
                    $config['upload_path'] = $uploadPath;
                    $config['overwrite'] = TRUE;
                    $config['allowed_types'] = '*';
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('uploadimage_death_certificate'))
                    {
                        $fileData = $this->upload->data();
                        $letter_attachment[] = $fileData['file_name'];
                    }
                }

                $attachment = json_encode($letter_attachment);
            }

            $leave_data = array(
                'id'         => $form_data['leave_id'],
                'employee_id'=> $form_data['employee_id'],
                'type_of_leave_id'=> $form_data['type_of_leave_id'],
                'balance_before_approve'=> $form_data['balance'],
                'start_date' => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_start_date']))),
                'start_time' => $form_data['leave_start_time'],
                'end_date'   => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_end_date']))),
                'end_time'   => $form_data['leave_end_time'],
                'total_days' => $form_data['leave_total_days'],
                'status'     => 1,
                'relation'   => $form_data['relation'],
                'death_cert' => $attachment
            );
        }
        else if($form_data['type_of_leave_id'] == 5)
        {
            $attachment1 = '';

            if(isset($_FILES['attachment_birth_cert']['name'])?TRUE:FALSE)
            {
                $filesCount = count((array)$_FILES['attachment_birth_cert']['name']);
                $letter_attachment = array();
                for($i = 0; $i < $filesCount; $i++)
                {   
                    $_FILES['uploadimage_birth_cert']['name']     = $_FILES['attachment_birth_cert']['name'];
                    $_FILES['uploadimage_birth_cert']['type']     = $_FILES['attachment_birth_cert']['type'];
                    $_FILES['uploadimage_birth_cert']['tmp_name'] = $_FILES['attachment_birth_cert']['tmp_name'];
                    $_FILES['uploadimage_birth_cert']['error']    = $_FILES['attachment_birth_cert']['error'];
                    $_FILES['uploadimage_birth_cert']['size']     = $_FILES['attachment_birth_cert']['size'];

                    $uploadPath = './uploads/leave_attachment';
                    $config['upload_path'] = $uploadPath;
                    $config['overwrite'] = TRUE;
                    $config['allowed_types'] = '*';
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('uploadimage_birth_cert'))
                    {
                        $fileData = $this->upload->data();
                        $letter_attachment[] = $fileData['file_name'];
                    }
                }

                $attachment1 = json_encode($letter_attachment);
            }

            // $attachment2 = '';

            // if(isset($_FILES['attachment_married_cert']['name'])?TRUE:FALSE)
            // {
            //     $filesCount = count($_FILES['attachment_married_cert']['name']);
            //     $letter_attachment = array();
            //     for($i = 0; $i < $filesCount; $i++)
            //     {   
            //         $_FILES['uploadimage_married_cert']['name']     = $_FILES['attachment_married_cert']['name'];
            //         $_FILES['uploadimage_married_cert']['type']     = $_FILES['attachment_married_cert']['type'];
            //         $_FILES['uploadimage_married_cert']['tmp_name'] = $_FILES['attachment_married_cert']['tmp_name'];
            //         $_FILES['uploadimage_married_cert']['error']    = $_FILES['attachment_married_cert']['error'];
            //         $_FILES['uploadimage_married_cert']['size']     = $_FILES['attachment_married_cert']['size'];

            //         $uploadPath = './uploads/leave_attachment';
            //         $config['upload_path'] = $uploadPath;
            //         $config['overwrite'] = TRUE;
            //         $config['allowed_types'] = '*';
                    
            //         $this->load->library('upload', $config);
            //         $this->upload->initialize($config);

            //         if($this->upload->do_upload('uploadimage_married_cert'))
            //         {
            //             $fileData = $this->upload->data();
            //             $letter_attachment[] = $fileData['file_name'];
            //         }
            //     }

            //     $attachment2 = json_encode($letter_attachment);
            // }

            $leave_data = array(
                'id'         => $form_data['leave_id'],
                'employee_id'=> $form_data['employee_id'],
                'type_of_leave_id'=> $form_data['type_of_leave_id'],
                'balance_before_approve'=> $form_data['balance'],
                'start_date' => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_start_date']))),
                'start_time' => $form_data['leave_start_time'],
                'end_date'   => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_end_date']))),
                'end_time'   => $form_data['leave_end_time'],
                'total_days' => $form_data['leave_total_days'],
                'status'     => 1,
                'birth_cert' => $attachment1,
                // 'married_cert' =>$attachment2
                'child_dob'    => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_child_dob']))),
                'child_is'     => $form_data['child_is'],
            );
        }
        else if($form_data['type_of_leave_id'] == 6)
        {
            $attachment1 = '';

            if(isset($_FILES['attachment_birth_cert']['name'])?TRUE:FALSE)
            {
                $filesCount = count((array)$_FILES['attachment_birth_cert']['name']);
                $letter_attachment = array();
                for($i = 0; $i < $filesCount; $i++)
                {   
                    $_FILES['uploadimage_birth_cert']['name']     = $_FILES['attachment_birth_cert']['name'];
                    $_FILES['uploadimage_birth_cert']['type']     = $_FILES['attachment_birth_cert']['type'];
                    $_FILES['uploadimage_birth_cert']['tmp_name'] = $_FILES['attachment_birth_cert']['tmp_name'];
                    $_FILES['uploadimage_birth_cert']['error']    = $_FILES['attachment_birth_cert']['error'];
                    $_FILES['uploadimage_birth_cert']['size']     = $_FILES['attachment_birth_cert']['size'];

                    $uploadPath = './uploads/leave_attachment';
                    $config['upload_path'] = $uploadPath;
                    $config['overwrite'] = TRUE;
                    $config['allowed_types'] = '*';
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('uploadimage_birth_cert'))
                    {
                        $fileData = $this->upload->data();
                        $letter_attachment[] = $fileData['file_name'];
                    }
                }

                $attachment1 = json_encode($letter_attachment);
            }

            $attachment2 = '';

            if(isset($_FILES['attachment_married_cert']['name'])?TRUE:FALSE)
            {
                $filesCount = count((array)$_FILES['attachment_married_cert']['name']);
                $letter_attachment = array();
                for($i = 0; $i < $filesCount; $i++)
                {   
                    $_FILES['uploadimage_married_cert']['name']     = $_FILES['attachment_married_cert']['name'];
                    $_FILES['uploadimage_married_cert']['type']     = $_FILES['attachment_married_cert']['type'];
                    $_FILES['uploadimage_married_cert']['tmp_name'] = $_FILES['attachment_married_cert']['tmp_name'];
                    $_FILES['uploadimage_married_cert']['error']    = $_FILES['attachment_married_cert']['error'];
                    $_FILES['uploadimage_married_cert']['size']     = $_FILES['attachment_married_cert']['size'];

                    $uploadPath = './uploads/leave_attachment';
                    $config['upload_path'] = $uploadPath;
                    $config['overwrite'] = TRUE;
                    $config['allowed_types'] = '*';
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('uploadimage_married_cert'))
                    {
                        $fileData = $this->upload->data();
                        $letter_attachment[] = $fileData['file_name'];
                    }
                }

                $attachment2 = json_encode($letter_attachment);
            }

            $leave_data = array(
                'id'         => $form_data['leave_id'],
                'employee_id'=> $form_data['employee_id'],
                'type_of_leave_id'=> $form_data['type_of_leave_id'],
                'balance_before_approve'=> $form_data['balance'],
                'start_date' => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_start_date']))),
                'start_time' => $form_data['leave_start_time'],
                'end_date'   => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_end_date']))),
                'end_time'   => $form_data['leave_end_time'],
                'total_days' => $form_data['leave_total_days'],
                'status'     => 1,
                'birth_cert' => $attachment1,
                'married_cert' =>$attachment2
            );
        }
        else if($form_data['type_of_leave_id'] == 7)
        {
            $attachment = '';

            if(isset($_FILES['attachment_birth_cert']['name'])?TRUE:FALSE)
            {
                $filesCount = count((array)$_FILES['attachment_birth_cert']['name']);
                $letter_attachment = array();
                for($i = 0; $i < $filesCount; $i++)
                {   
                    $_FILES['uploadimage_birth_cert']['name']     = $_FILES['attachment_birth_cert']['name'];
                    $_FILES['uploadimage_birth_cert']['type']     = $_FILES['attachment_birth_cert']['type'];
                    $_FILES['uploadimage_birth_cert']['tmp_name'] = $_FILES['attachment_birth_cert']['tmp_name'];
                    $_FILES['uploadimage_birth_cert']['error']    = $_FILES['attachment_birth_cert']['error'];
                    $_FILES['uploadimage_birth_cert']['size']     = $_FILES['attachment_birth_cert']['size'];

                    $uploadPath = './uploads/leave_attachment';
                    $config['upload_path'] = $uploadPath;
                    $config['overwrite'] = TRUE;
                    $config['allowed_types'] = '*';
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('uploadimage_birth_cert'))
                    {
                        $fileData = $this->upload->data();
                        $letter_attachment[] = $fileData['file_name'];
                    }
                }

                $attachment = json_encode($letter_attachment);
            }

            $leave_data = array(
                'id'         => $form_data['leave_id'],
                'employee_id'=> $form_data['employee_id'],
                'type_of_leave_id'=> $form_data['type_of_leave_id'],
                'balance_before_approve'=> $form_data['balance'],
                'start_date' => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_start_date']))),
                'start_time' => $form_data['leave_start_time'],
                'end_date'   => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_end_date']))),
                'end_time'   => $form_data['leave_end_time'],
                'total_days' => $form_data['leave_total_days'],
                'status'     => 1,
                'birth_cert' => $attachment
            );
        }
        else if($form_data['type_of_leave_id'] == 8)
        {
            $attachment = '';

            if(isset($_FILES['attachment_exam_schedule']['name'])?TRUE:FALSE)
            {
                $filesCount = count((array)$_FILES['attachment_exam_schedule']['name']);
                $letter_attachment = array();
                for($i = 0; $i < $filesCount; $i++)
                {   
                    $_FILES['uploadimage_exam_schedule']['name']     = $_FILES['attachment_exam_schedule']['name'];
                    $_FILES['uploadimage_exam_schedule']['type']     = $_FILES['attachment_exam_schedule']['type'];
                    $_FILES['uploadimage_exam_schedule']['tmp_name'] = $_FILES['attachment_exam_schedule']['tmp_name'];
                    $_FILES['uploadimage_exam_schedule']['error']    = $_FILES['attachment_exam_schedule']['error'];
                    $_FILES['uploadimage_exam_schedule']['size']     = $_FILES['attachment_exam_schedule']['size'];

                    $uploadPath = './uploads/leave_attachment';
                    $config['upload_path'] = $uploadPath;
                    $config['overwrite'] = TRUE;
                    $config['allowed_types'] = '*';
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('uploadimage_exam_schedule'))
                    {
                        $fileData = $this->upload->data();
                        $letter_attachment[] = $fileData['file_name'];
                    }
                }

                $attachment = json_encode($letter_attachment);
            }

            $leave_data = array(
                'id'         => $form_data['leave_id'],
                'employee_id'=> $form_data['employee_id'],
                'type_of_leave_id'=> $form_data['type_of_leave_id'],
                'balance_before_approve'=> $form_data['balance'],
                'start_date' => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_start_date']))),
                'start_time' => $form_data['leave_start_time'],
                'end_date'   => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_end_date']))),
                'end_time'   => $form_data['leave_end_time'],
                'total_days' => $form_data['leave_total_days'],
                'status'     => 1,
                'exam_schedule' => $attachment,
                'institution'   => $form_data['institution'],
                'num_of_subject'=> $form_data['num_of_subject']
            );
        }
        else if($form_data['type_of_leave_id'] == 9)
        {
            $attachment = '';

            if(isset($_FILES['attachment_married_cert']['name'])?TRUE:FALSE)
            {
                $filesCount = count((array)$_FILES['attachment_married_cert']['name']);
                $letter_attachment = array();
                for($i = 0; $i < $filesCount; $i++)
                {   
                    $_FILES['uploadimage_married_cert']['name']     = $_FILES['attachment_married_cert']['name'];
                    $_FILES['uploadimage_married_cert']['type']     = $_FILES['attachment_married_cert']['type'];
                    $_FILES['uploadimage_married_cert']['tmp_name'] = $_FILES['attachment_married_cert']['tmp_name'];
                    $_FILES['uploadimage_married_cert']['error']    = $_FILES['attachment_married_cert']['error'];
                    $_FILES['uploadimage_married_cert']['size']     = $_FILES['attachment_married_cert']['size'];

                    $uploadPath = './uploads/leave_attachment';
                    $config['upload_path'] = $uploadPath;
                    $config['overwrite'] = TRUE;
                    $config['allowed_types'] = '*';
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('uploadimage_married_cert'))
                    {
                        $fileData = $this->upload->data();
                        $letter_attachment[] = $fileData['file_name'];
                    }
                }

                $attachment = json_encode($letter_attachment);
            }

            $leave_data = array(
                'id'         => $form_data['leave_id'],
                'employee_id'=> $form_data['employee_id'],
                'type_of_leave_id'=> $form_data['type_of_leave_id'],
                'balance_before_approve'=> $form_data['balance'],
                'start_date' => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_start_date']))),
                'start_time' => $form_data['leave_start_time'],
                'end_date'   => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_end_date']))),
                'end_time'   => $form_data['leave_end_time'],
                'total_days' => $form_data['leave_total_days'],
                'status'     => 1,
                'married_cert' =>$attachment
            );
        }
        else
        {
            $leave_data = array(
                'id'         => $form_data['leave_id'],
                'employee_id'=> $form_data['employee_id'],
                'type_of_leave_id'=> $form_data['type_of_leave_id'],
                'balance_before_approve'=> $form_data['balance'],
                'start_date' => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_start_date']))),
                'start_time' => $form_data['leave_start_time'],
                'end_date'   => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_end_date']))),
                'end_time'   => $form_data['leave_end_time'],
                'total_days' => $form_data['leave_total_days'],
                'status'     => 1
            );
        }
        
        $email = $this->leave_model->leave_application_email_admin($form_data['employee_id'],$this->user_id,$leave_data);
        
        $result = $this->leave_model->apply_leave($leave_data);
        echo json_encode($result[0]);
    }

    public function calculate_working_days(){
        
        $form_data = $this->input->post();

        $start_date   = strtotime($form_data['start_date']);
        $end_date     = strtotime($form_data['end_date']);

        $start_time = $form_data['start_time'];
        $end_time   = $form_data['end_time'];

        $employee_id  = $form_data['employee_id'];

        $total_days = date_diff(date_create($form_data['start_date']), date_create($form_data['end_date']))->days + 1;

        $total_working_days = 0;

        $date     = $start_date;

        $addDay   = 86400;    // 1 day = 86400 seconds
        $holidays = $this->holiday_model->getAllHolidays($employee_id);     // get all defined holiday. New holiday can be added in block_holiday module.
        $department = $this->leave_model->get_department($employee_id); 
        $block_leave_list = $this->leave_model->get_calander_block_leave_list($employee_id);

        foreach($department as $item)
        {
            $department_id = $item->department;

            $team = $item->team_shift;
        }

        for($i=0; $i<$total_days; $i++)
        {
            $d = date('w', ($date));
            $weeknum = date('W', ($date));

                if($weeknum % 2 == 0)
                {
                    // even
                    if($team == 1)
                    {
                        $x = 1;
                    }
                    else if($team == 2)
                    {
                        $x = 6;
                    }
                    else
                    {
                        $x = 6;
                    }
                }
                else
                {
                    //odd
                    if($team == 1)
                    {
                        $x = 6;
                    }
                    else if($team == 2)
                    {
                        $x = 1;
                    }
                    else
                    {
                        $x = 6;
                    }
                }

            if($d != 0 && $d != $x) {
                $total_working_days++;

                foreach($holidays as $holiday){
                    if(strtotime(date_format(date_create($holiday->holiday_date), 'Y-m-d')) == $date)
                    {
                        $total_working_days--;
                    }
                }

                // foreach($block_leave_list as $block_leave){
                //     foreach($holidays as $holiday){
                //         if($date >= strtotime(date_format(date_create($block_leave->block_leave_date_from), 'Y-m-d')) && 
                //             $date <= strtotime(date_format(date_create($block_leave->block_leave_date_to), 'Y-m-d')) && 
                //             $date != strtotime(date_format(date_create($holiday->holiday_date), 'Y-m-d')))
                //         {
                //             $total_working_days--;
                //         }
                //     }
                // }
            }

            $date = $date + $addDay;
        }

        if(date('w', ($start_date))!= 0 && date('w', ($start_date))!= $x)
        {
            if(!strcmp($start_time, '13:00')){
                $total_working_days -= 0.5;
            }

            if(!strcmp($end_time, '13:00')){
                $total_working_days -= 0.5;
            }

        }

        $return_data = (object)array(
            'total_working_days' => $total_working_days,
            'total_remaining_al' => $this->remaining_al - $total_working_days    
        );

        echo json_encode($return_data);
    }

    public function change_status(){
        $form_data = $this->input->post();
        $employee_id = $form_data['employee_id'];
        $type_of_leave_id = $form_data['type_of_leave_id'];
        $reason = $form_data['reason'];
        
        // To get the last remaining annual leave left
        $q = $this->db->query("SELECT * FROM payroll_employee_annual_leave eal_1 WHERE eal_1.last_updated = (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id=" . $employee_id . " AND eal_2.type_of_leave_id = ".$type_of_leave_id.") AND eal_1.type_of_leave_id = ".$type_of_leave_id." AND eal_1.employee_id=" . $employee_id . "");

        $data = array();

        if($form_data['is_approve']){

            array_push($data, array(
                'id' => $form_data['leave_id'],
                'status' => 2,
                'status_updated_by' => date('Y-m-d H:i:s'),
                'al_left_before' => $q->result()[0]->annual_leave_days,
                'al_left_after' => $q->result()[0]->annual_leave_days
            ));

            // $this->leave_model->team_leave_notification($employee_id,$data);
        }
        else 
        {
            array_push($data, array(
                'id' => $form_data['leave_id'],
                'status' => 3,
                'reason' => $form_data['reason'],
                'status_updated_by' => date('Y-m-d H:i:s'),
                'al_left_before' => $q->result()[0]->annual_leave_days,
                'al_left_after' => $q->result()[0]->annual_leave_days
            ));

            if($type_of_leave_id == 4)
            {
                $deatils = $this->leave_model->get_leave_details($form_data['leave_id']);

                $this->db->where('id', $deatils[0]->relation);
                $this->db->update('payroll_family_info', array('funeral_flag' => 0));
            }

            if($type_of_leave_id == 5)
            {
                $deatils = $this->leave_model->get_leave_details($form_data['leave_id']);

                $this->db->where('leave_no', $deatils[0]->leave_no);
                $this->db->update('payroll_employee_others_leave', array('expired_flag' => 1));
            }
        }

        $email = $this->leave_model->leave_change_status_email($this->user_id,$employee_id,$data);

        $result = $this->leave_model->update_status($data, $form_data['is_approve'], $employee_id, $type_of_leave_id);

        echo $result;
    }

    public function cash_out(){
        $form_data = $this->input->post();
        $employee_id = $form_data['employee_id'];
        $type_of_leave_id = $form_data['type_of_leave_id'];
        $reason = $form_data['reason'];
        
        // To get the last remaining annual leave left
        $q = $this->db->query("SELECT * FROM payroll_employee_annual_leave eal_1 WHERE eal_1.last_updated = (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id=" . $employee_id . " AND eal_2.type_of_leave_id = ".$type_of_leave_id.") AND eal_1.type_of_leave_id = ".$type_of_leave_id." AND eal_1.employee_id=" . $employee_id . "");

        $data = array();

        if($form_data['is_approve']){

            array_push($data, array(
                'id' => $form_data['leave_id'],
                'status' => 2,
                'status_updated_by' => date('Y-m-d H:i:s'),
                'al_left_before' => $q->result()[0]->annual_leave_days,
                'al_left_after' => $q->result()[0]->annual_leave_days
            ));

            // $this->leave_model->team_leave_notification($employee_id,$data);
        }

        $email = $this->leave_model->leave_change_status_email($this->user_id,$employee_id,$data);

        $result = $this->leave_model->update_status($data, $form_data['is_approve'], $employee_id, $type_of_leave_id);

        echo $result;
    }

    public function withdraw_leave()
    {
        $form_data = $this->input->post();
        $leave_id = $form_data['leave_id'];
        $employee_id = $form_data['employee_id'];
        $total_days = $form_data['total_days'];
        $type_of_leave_id = $form_data['type_of_leave_id'];
        $status_id = $form_data['status_id'];

        if($type_of_leave_id == 4)
        {
            $deatils = $this->leave_model->get_leave_details($leave_id);

            $this->db->where('id', $deatils[0]->relation);
            $this->db->update('payroll_family_info', array('funeral_flag' => 0));
        }

        if($type_of_leave_id == 5)
        {
            $deatils = $this->leave_model->get_leave_details($leave_id);

            $this->db->where('leave_no', $deatils[0]->leave_no);
            $this->db->update('payroll_employee_others_leave', array('expired_flag' => 1));
        }

        if($status_id == 2)
        {
            $q = $this->db->query("SELECT * FROM payroll_employee_annual_leave eal_1 WHERE eal_1.last_updated = (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id=" . $employee_id . " AND eal_2.type_of_leave_id = ".$form_data['type_of_leave_id'].") AND eal_1.type_of_leave_id = ".$form_data['type_of_leave_id']." AND eal_1.employee_id=" . $employee_id . "");

            if($q->num_rows())
            {
                $q = $q->result_array();

                $final_data = array(
                    'employee_id' => $employee_id,
                    'type_of_leave_id' => $type_of_leave_id,
                    'annual_leave_days' => round($q[0]["annual_leave_days"] + $total_days, 1)
                );

                $this->db->insert('payroll_employee_annual_leave', $final_data);
                // $this->leave_model->team_leave_withdraw_email($employee_id,$leave_id);
            }

            $email = $this->leave_model->leave_withdraw_email($leave_id);
        }

        $payroll_leave_data = array(
            'status' => 4,
        );

        $this->db->where('id', $leave_id);
        $this->db->update('payroll_leave', $payroll_leave_data);

        echo json_encode("success");
    }

    public function submit_day_off(){

        $form_data = $this->input->post();
        $id = $form_data['id'];
        $leave = $form_data['leave'];
        $dayoff_total_days = $form_data['dayoff_total_days'];

        for($a = 0; $a < count($id); $a++)
        {
            $AL = $this->leave_model->get_leave_balance($id[$a],$leave);

            foreach($AL as $item)
            {
                $employee_id       = $item->employee_id;
                $annual_leave_days = $item->annual_leave_days;
            }

            $final_data = array(
                'employee_id'       => $employee_id,
                'type_of_leave_id'  => $leave,
                'annual_leave_days' => $annual_leave_days + $dayoff_total_days
            );

            $result = $this->db->insert('payroll_employee_annual_leave', $final_data);
            $this->leave_model->day_off_email($id[$a],$dayoff_total_days);
        }

        echo json_encode($result);
    }

    public function check_remainAL(){

        $form_data = $this->input->post();

        $id = $form_data['employee_id'];
        $leave = "1";
        
        $result = $this->leave_model->get_leave_balance($id, $leave);

        echo json_encode($result);
    }

    public function get_Leave_info(){

        $form_data = $this->input->post();

        $id = $form_data['employee_id'];

        $type_of_leave = $this->leave_model->get_Leave_info($id);

        echo json_encode($type_of_leave);
        
    }

    public function get_Leave_Balance_info(){

        $form_data = $this->input->post();

        $id         = $form_data['employee_id'];
        $leave_type = $form_data['type_of_leave'];

        $result = $this->leave_model->get_leave_balance($id,$leave_type);

        echo json_encode($result);
    }

    public function get_start_time_info(){

        $form_data = $this->input->post();

        $id = $form_data['employee_id'];

        $result = $this->leave_model->get_start_time_info($id);

        echo json_encode($result); 
    }

    public function calculate_working_days2(){
        
        $form_data = $this->input->post();

        $start_date   = strtotime($form_data['start_date']);
        $end_date     = strtotime($form_data['end_date']);

        $start_time = $form_data['start_time'];
        $end_time   = $form_data['end_time'];

        $employee_id  = $form_data['employee_id'];
        $remaining_al = $form_data['remaining_al'];

        $total_days = date_diff(date_create($form_data['start_date']), date_create($form_data['end_date']))->days + 1;

        $total_working_days = 0;

        $date     = $start_date;

        $addDay   = 86400;    // 1 day = 86400 seconds
        $holidays = $this->holiday_model->getAllHolidays($employee_id);     // get all defined holiday. New holiday can be added in block_holiday module.
        $department = $this->leave_model->get_department($employee_id); 
        $block_leave_list = $this->leave_model->get_calander_block_leave_list($employee_id);

        foreach($department as $item)
        {
            $department_id = $item->department;

            $team = $item->team_shift;
        }

        for($i=0; $i<$total_days; $i++){
            $d = date('w', ($date));
            $weeknum = date('W', ($date));

            if($weeknum % 2 == 0)
            {
                // even
                if($team == 1)
                {
                    $x = 1;
                }
                else if($team == 2)
                {
                    $x = 6;
                }
                else
                {
                    $x = 6;
                }
            }
            else
            {
                //odd
                if($team == 1)
                {
                    $x = 6;
                }
                else if($team == 2)
                {
                    $x = 1;
                }
                else
                {
                    $x = 6;
                }
            }

            if($d != 0 && $d != $x) {
                $total_working_days++;

                foreach($holidays as $holiday){
                    if(strtotime(date_format(date_create($holiday->holiday_date), 'Y-m-d')) == $date)
                    {
                        $total_working_days--;
                    }
                }
            }

            $date = $date + $addDay;
        }

        if(date('w', ($start_date))!= 0 && date('w', ($start_date))!= $x)
        {
            if(!strcmp($start_time, '13:00')){
                $total_working_days -= 0.5;
            }

            if(!strcmp($end_time, '13:00')){
                $total_working_days -= 0.5;
            }
        }

        $return_data = (object)array(
            'total_working_days' => $total_working_days,
            'total_remaining_al' => $remaining_al - $total_working_days    
        );

        echo json_encode($return_data);
    }

    public function get_leave_day(){

        $form_data = $this->input->post();

        $id = $form_data['id'];

        $result = $this->leave_model->get_leave_day($id);

        echo json_encode($result); 
    }

    public function get_relationship(){

        $form_data = $this->input->post();

        $id = $form_data['id'];

        $result = $this->leave_model->get_relationship($id);

        echo json_encode($result); 
    }

    public function search_for_matenity(){

        $form_data = $this->input->post();

        $employee_id = $form_data['employee_id'];
        $date = date('Y-m-d', strtotime(str_replace('/', '-', $form_data['date'])));

        $result = $this->leave_model->search_for_matenity($employee_id,$date);

        echo json_encode($result); 
    }

}