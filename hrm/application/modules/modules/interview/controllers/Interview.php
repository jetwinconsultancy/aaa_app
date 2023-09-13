<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Interview extends MX_Controller
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
        
        $this->load->helper(array('form', 'url', 'security'));
        $this->load->library('form_validation');
        $this->load->library(array('session','parser'));
        $this->load->model('interview_model');
        $this->load->model('applicant/applicant_model');
        $this->load->model('firm/master_model');
        $this->load->model('actions_json_model');
        $this->load->model('day_time_json_model');
        $this->load->model('country_json_model');
        $this->load->model('personal_json_model');
        
        $this->load->model('auth/auth_model');
        $this->load->library('ion_auth');

        $this->meta['page_name'] = 'Interview';
    }

    public function index()
    {   
        // $this->load->library('mybreadcrumb');
        // $this->mybreadcrumb->add('Applicant', base_url('Applicant'));

        // $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $bc   = array(array('link' => '#', 'page' => 'Interview'));
        $meta = array('page_title' => 'Interview', 'bc' => $bc, 'page_name' => 'Interview');

        $this->data['interview_list'] = $this->interview_model->get_interviewList();
        $this->data['interview_status'] = $this->actions_json_model->get_interview_status();
        $this->data['interview_result'] = $this->actions_json_model->get_interview_result();
        // foreach($this->data['interview_list'] as $item){
        //     $item->status = $this->actions_json_model->get_interview_status_name($item->status);
        // }

        // $this->page_construct('interview/index.php', $meta, $this->data);
        $this->page_construct('index.php', $meta, $this->data);
        // $meta['logo']       = 'assets/logo/logo.png';
        // $meta['page_name']  = '';

        // $footer['project_name'] = "PAYROLL SYSTEM";

        // echo modules::run('interview/index'); 

        // $this->_render_page('interview/index', $this->data);
        
        // $this->load->view('header', $meta);
        // $this->load->view('index');
        // $this->load->view('footer', $footer);
    }

    public function applicant_profile($applicant_id){

        $bc   = array(array('link' => '#', 'page' => 'Applicant Profile'));
        $meta = array('page_title' => 'Applicant Profile', 'bc' => $bc, 'page_name' => 'Applicant Profile');

        $this->data['applicant_id'] = $applicant_id;

        $this->data['applicant_profile'] = $this->applicant_model->get_applicant($applicant_id);
        $this->data['education']         = $this->applicant_model->get_applicant_education($applicant_id);
        $this->data['experience']        = $this->applicant_model->get_applicant_experience($applicant_id);
        $this->data['professional']      = $this->applicant_model->get_applicant_professional($applicant_id);
        $this->data['referral']          = $this->applicant_model->get_applicant_referral($applicant_id);
        $this->data['language']          = $this->applicant_model->get_applicant_language($applicant_id);

        foreach($this->data['education'] as $row){
            $row->graduate_month   = $this->day_time_json_model->getMonth_name($row->graduate_month);
            $row->uni_country      = $this->country_json_model->get_country_name($row->uni_country);
            $row->uni_fieldOfStudy = $this->personal_json_model->getFieldOfStudy_name($row->uni_fieldOfStudy);
        }

        foreach($this->data['experience'] as $row){
            $row->join_month     = $this->day_time_json_model->getMonth_name($row->join_month);
            $row->country        = $this->country_json_model->get_country_name($row->country);
            $row->position_level = $this->personal_json_model->getPosition_level_name($row->position_level);
        }

        // $this->page_construct('view_applicant_profile.php', $this->meta, $this->data);
        $this->page_construct('applicant/applicant_profile/index', $meta, $this->data);
    }

    public function interviewList(){
        echo json_encode($this->interview_model->get_interviewList());
    }

    public function create()
    {   
        // $this->load->library('mybreadcrumb');
        // $this->mybreadcrumb->add('Create Interview', base_url('Create_Interview'));

        // $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        // $this->meta['page_name'] = 'Create Interview';
        $bc   = array(array('link' => '#', 'page' => 'Interview'));
        $meta = array('page_title' => 'Interview', 'bc' => $bc, 'page_name' => 'Interview');

        $this->data['firm_list'] = $this->master_model->get_firm_dropdown_list();

        $this->page_construct('interview/create_interview.php', $meta, $this->data);
    }

    // public function edit($applicant_id = NULL)
    // {
    //     // if($applicant_id != null){
    //     //     // $this->data['staff'] = $this->employee_model->get_staff_info($staff_id);
    //     // }

    //     $this->interview_model->edit_interview($applicant_id);
        
    //     $this->page_construct('interview/create_interview.php', $this->meta, $this->data);
    // }

    public function edit_interview($interview_id){

        // $this->meta['page_name'] = 'Edit Interview';
        $bc   = array(array('link' => '#', 'page' => 'Edit Interview'));
        $meta = array('page_title' => 'Edit Interview', 'bc' => $bc, 'page_name' => 'Edit Interview');

        $data = $this->interview_model->edit_interview($interview_id);

        $interview_data = array(
            'id' => $data->id,
            'applicant_id' => $data->applicant_id,
            'firm' => $data->firm,
            'applicant_name'    => $data->applicant_name,
            'applicant_email'   => $data->applicant_email,
            'interview_time'    => date('d F Y - h:s a', strtotime($data->interview_time)),
            'venue'   => $data->venue,
            'interview_num_valid_until'    => date('d F Y - h:s a', strtotime($data->expired_at)),
            'interview_no'      => $data->interview_no
        );

        $this->data['interview_detail'] = $interview_data;
        $this->data['firm_list'] = $this->master_model->get_firm_dropdown_list();

        // echo json_encode($this->data['interview_detail']);

        $this->page_construct('interview/create_interview.php', $meta, $this->data);
    }

    public function interview_datetime($str)
    {
        $date_time = explode(' ',$str);
        if(sizeof($date_time)==2)
        {
        $date = $date_time[0];
        $date_values = explode('-',$date);
        if((sizeof($date_values)!=3) || !checkdate( (int) $date_values[1], (int) $date_values[2], (int) $date_values[0]))
        {
          $this->form_validation->set_message('interview_datetime', 'The date inside the Date of interview field is not valid.');
          return FALSE;
        }
        $time = $date_time[1];
        $time_values = explode(':',$time);
        if((int) $time_values[0]>23 || (int) $time_values[1]>59 || (int) $time_values[2]>59)
        {
          $this->form_validation->set_message('interview_datetime', 'The time inside the Date of interview field is not valid.');
          return FALSE;
        }
        return TRUE;
        }
        $this->form_validation->set_message('interview_datetime', 'The Date of interview field must have a DATETIME format.');
        return FALSE;
    }

    public function create_applicant(){
        $this->form_validation->set_rules('interview_company_name', 'Firm', 'required');
        $this->form_validation->set_rules('applicant_name', 'Name', 'required');
        $this->form_validation->set_rules('applicant_email', 'Email', 'required|is_unique[users.email]');
        $this->form_validation->set_rules('interview_datetime', 'Date of interview', 'trim|required');
        $this->form_validation->set_rules('venue', 'Venue', 'required');
        $this->form_validation->set_rules('interview_valid_datetime', 'Interview number valid until', 'required');

        if ($this->form_validation->run() == true)
        {
            $form_data = $this->input->post();

            // echo json_encode($form_data);

            $time = explode('-', $form_data['interview_datetime']);

            $interview_time = $time[0] . str_replace("", "", $time[1]);

            $interview_valid_datetime = explode('-', $form_data['interview_valid_datetime']);

            $interview_valid_date_time = $interview_valid_datetime[0] . str_replace(" ", "", $interview_valid_datetime[1]);

            $applicant = array(
                'id'    => $form_data['applicant_id'],
                'name'  => $form_data['applicant_name'],
                'email' => $form_data['applicant_email']
            );

            if($form_data['interview_id'] != "") 
            {
                $interview = array(
                    'id'            => $form_data['interview_id'],
                    'interview_no'  => $form_data['interview_no'],
                    'interview_time'=> date('Y-m-d H:i:s', strtotime($interview_time)),
                    'venue' => $form_data['venue'],
                    // 'interview_num_valid_until' => date('Y-m-d H:i:s', strtotime($interview_valid_date_time)),
                    'expired_at' => date('Y-m-d H:i:s', strtotime($interview_valid_date_time)),
                    'firm'          => $form_data['interview_company_name']
                );
            }
            else
            {
                $interview = array(
                    'id'            => '',
                    'interview_no'  => uniqid(),    // generate random alphanumeric
                    'interview_time'=> date('Y-m-d H:i:s', strtotime($interview_time)),
                    'venue' => $form_data['venue'],
                    // 'interview_num_valid_until' => date('Y-m-d H:i:s', strtotime($interview_valid_date_time)),
                    'expired_at' => date('Y-m-d H:i:s', strtotime($interview_valid_date_time)),
                    'firm'          => $form_data['interview_company_name'],
                    //'expired_at'    => date('Y-m-d H:i:s', strtotime(date("Y-m-d") . " +48 hours")),
                    'status'        => '1',
                    'result'        => '1',
                    'interviewer'   => $this->user_id
                );
            }
            // echo $interview['interview_time'];

            $applicant_id = $this->interview_model->create_applicant($applicant); 

            // echo $applicant_id;

            if($applicant_id > 0){
                $interview_id = $this->interview_model->create_interview($interview);
            }

            if($interview_id > 0){
                $applicant_interview = array(
                    'applicant_id'  => $applicant_id,
                    'interview_id'  => $interview_id,
                    'status'        => 'pending'
                );

                $interview_id = $this->interview_model->create_applicant_interview($applicant_interview);
            }

            $parse_data = array(
                'name'           => $applicant['name'],
                'link'           => base_url()."applicant/",
                'interview_no'   => $interview['interview_no'],
                'expired_by'     => date('d F Y g:i a', strtotime($interview_valid_date_time)),
                'firm_id'        => $form_data['interview_company_name'],
                'interviewer_id' => $this->user_id,
                'date'           => date('d F Y', strtotime($interview_time)),
                'time'           => date('g:i a', strtotime($interview_time)),
                'address'        => $form_data['venue'],
            );

            $sendEmailStatus = $this->interview_model->sendInvitationEmail($parse_data, $applicant['email']);
            
            // if($sendEmailStatus){
            //     echo $interview['interview_no'];
            // }
            $error = array(
                'result'=> false
            );
            
            echo json_encode($error);
        }
        else
        {
            $error = array(
                'result'=> true,
                'interview_company_name' => strip_tags(form_error('interview_company_name')),
                'applicant_name' => strip_tags(form_error('applicant_name')),
                'applicant_email' => strip_tags(form_error('applicant_email')),
                'interview_datetime' => strip_tags(form_error('interview_datetime')),
                'venue' => strip_tags(form_error('venue')),
                'interview_valid_datetime' => strip_tags(form_error('interview_valid_datetime')),
            );

            echo json_encode($error);
        }

        // echo $interview['interview_no'];
    }

    public function change_interview_status(){
        $form_data = $this->input->post();

        $data = array(
            'status' => $form_data['status']
        );

        echo $result = $this->interview_model->change_interview_status($data, $form_data['interview_id']);
    }

    public function change_interview_result(){
        $form_data = $this->input->post();

        $data = array(
            'result' => $form_data['result']
        );

        echo $result = $this->interview_model->change_interview_result($data, $form_data['interview_id']);
    }

    // public function move_to_employee(){
    //     $form_data = $this->input->post();

    //     $interview_id = $form_data['interview_id'];
    //     $email = $form_data['email'];

    //     $result = $this->interview_model->move_to_employee($interview_id,$email);

    //     echo json_encode($result);
    // }

    public function get_applicant_data(){
        $form_data = $this->input->post();

        $interview_id = $form_data['interview_id'];

        $result = $this->interview_model->get_applicant_data($interview_id);

        echo json_encode($result);
    }

    public function get_user_data(){
        $form_data = $this->input->post();

        $email = $form_data['email'];

        $result = $this->interview_model->get_user_data($email);

        echo json_encode($result);
    }

    public function create_employee_details(){
        $form_data = $this->input->post();
        $interview_id = $form_data['interview_id'];
        $email = $form_data['email'];
        $result = $form_data['result'];

        $applicant_data = $this->interview_model->get_applicant_data($interview_id);
        $user_data = $this->interview_model->get_user_data($email);

        $data = array(
            'user_id'               	=> $user_data[0]->id,
            'staff_id'              	=> "",
            'staff_name'            	=> $applicant_data[0]->name,
            'staff_nric_finno'      	=> $applicant_data[0]->ic_passport_no,
            'hidden_singapore_pr'   	=> $applicant_data[0]->is_pr_singaporean,
            'staff_address'         	=> $applicant_data[0]->address,
            'staff_nationality'     	=> $applicant_data[0]->nationality_id,
            'staff_DOB'             	=> $applicant_data[0]->dob,
            'applicant_preview_pic' 	=> $applicant_data[0]->pic,
            'hidden_gender'         	=> $applicant_data[0]->gender=='Male'?1:0,
            'hidden_marital_status' 	=> "",
            'firm_id'               	=> "",
            'staff_joined'          	=> $applicant_data[0]->effective_from,
            'staff_cessation'       	=> "",
            'staff_designation'     	=> $applicant_data[0]->designation,
            'staff_department'      	=> $applicant_data[0]->department,
            'staff_office'          	=> "",
            'staff_workpass'        	=> "",
            'staff_pass_expire'     	=> "",
            'hidden_staff_aws_given'	=> "",
            'staff_cpf_employee'    	=> "",
            'staff_cpf_employer'    	=> "",
            'staff_cdac'            	=> "",
            'staff_remark'          	=> "",
            'staff_supervisor'      	=> "",
            'date_of_letter'        	=> $applicant_data[0]->date_offer,
            'status_date'           	=> "",
            'active'                	=> array(1,2,3),
            'leave_days'            	=> array($applicant_data[0]->vacation_leave,60.0,14.0),
            'previous_staff_status' 	=> "",
            'staff_status'          	=> "1",
            'previous_status_date'  	=> "",
        );

        $change_status_data = array(
            'result'       => $result
        );

        $this->create_employee($data);
        $this->interview_model->change_interview_result($change_status_data, $form_data['interview_id']);
    }

    public function create_employee($data){
        $form_data = $data;
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
            'salary'                    => empty($form_data['staff_salary'])?0:$this->encryption->encrypt(trim(strtoupper($form_data['staff_salary'][0]))),
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

    public function view_offer_letter(){
        $form_data = $this->input->post();
        $applicant_id = $form_data['applicant_id'];

        $data = $this->offer_letter_model->getApplicant_OL($applicant_id);

        $data[0]->is_employee = false;
        $data[0]->is_pr_singaporean = false;

        if($data[0]->nationality_id == 165)
        {
            $data[0]->is_pr_singaporean = true;
        }   

        $offer_letter_pdf = modules::load('offer_letter/Offer_letter/');
        $return_data      = $offer_letter_pdf->info($data);

        echo $return_data;
    }

    public function save_offer_letter_attachment(){
        $form_data = $this->input->post();
        $offer_letter_id = $form_data['offer_letter_id'];
        $attachment = $form_data['attachment'];

        $data = array(
            'attachment' => $attachment
        );

        $this->db->where('id', $offer_letter_id);
        $this->db->update('payroll_offer_letter', $data);
    }

    public function get_department()
    {
        $department = "";
        // $department = $_POST['department'];

        $result_department = $this->db->query("select * from department");

        $result = $result_department->result_array();

        if(!$result_department) {
            throw new exception("Department not found.");
        }
        $res = array();

        for($j = 0; $j < count($result); $j++)
        {
            $res[$result[$j]['id']] = $result[$j]['department_name'];
        }
        
        if ($department != "")
        {
            $selected_department = $department;
        }
        else
        {
            $selected_department = null;
        }
        
        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Department fetched successfully.", 'result'=>$res, 'selected_department'=>$selected_department);

        echo json_encode($data);
    }

    public function get_firm()
    {

        //$currency = $_POST['currency'];
        $this->db->select('firm.*')
                ->from('firm')
                ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
                ->where('user_firm.user_id = '.$this->session->userdata('user_id'));

        //$get_all_firm = $this->db->get_where('firm',array('user_id'=>$this->session->userdata('user_id')));
        $get_all_firm = $this->db->get();
        $result = $get_all_firm->result_array();
        //echo json_encode($result);
        if(!$get_all_firm) {
            throw new exception("Firm not found.");
        }
        $res = array();

        for($j = 0; $j < count($result); $j++)
        {
            $res[$result[$j]['id']] = $result[$j]['name'];
        }    

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Firm fetched successfully.", 'result'=>$res);

        echo json_encode($data);

    }

    public function get_group()
    {

        //$currency = $_POST['currency'];
        $this->db->select('groups.*')
                ->from('groups')
                ->order_by('id', 'DESC' )
                ->where('id != 1')
                ->where('id != 4');
                //->limit(2);

        //$get_all_firm = $this->db->get_where('firm',array('user_id'=>$this->session->userdata('user_id')));
        $get_all_group = $this->db->get();
        $result = $get_all_group->result_array();
        //echo json_encode($result);
        if(!$get_all_group) {
            throw new exception("Group not found.");
        }
        $res = array();

        for($j = 0; $j < count($result); $j++)
        {
            $res[$result[$j]['id']] = $result[$j]['description'];
        }    

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Group fetched successfully.", 'result'=>$res);

        echo json_encode($data);

    }

    public function get_manager_name()
    {
        $query = 'SELECT users.id, users.last_name, users.first_name, users.group_id FROM users left join user_firm as a on a.user_id = "'.$this->session->userdata("user_id").'" left join user_firm as b on b.firm_id = a.firm_id where b.user_id = users.id AND users.id != 1 AND  users.user_deleted = 0 AND users.active = 1 AND users.group_id = 5 GROUP BY users.id';

        $result = $this->db->query($query);

        if ($result->num_rows() > 0) 
        {

            $result = $result->result_array();

            if(!$result) {
              throw new exception("Users not found.");
            }

            $res = array();
            foreach($result as $row) {
                if($row['first_name'] != null)
                {
                    $res[$row['id']] = $row['last_name']." ".$row['first_name'];
                }
              
            }

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"User fetched successfully.", 'result'=>$res);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_vendor_name'=>'');

            echo json_encode($data);
        }
    }

    function create_user()
    {
        if ((!$this->Admin && $this->Admin != null) || (!$this->Manager && $this->Manager != null)) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->data['title'] = "Create User";
        //$this->form_validation->set_rules('username', lang("username"), 'trim|is_unique[users.username]');
        $this->form_validation->set_rules('email', lang("email"), 'trim');

        if ($this->form_validation->run() == true) {

            //$username = strtolower($this->input->post('username'));
            list($username, $domain) = explode("@", $this->input->post('email'));
            $user_type_id = 1;
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');
            $notify = $this->input->post('notify');
            //$manager_in_charge = $this->input->post("manager_in_charge");
            $selected_firm = $this->input->post("selected_firm");
            $term_and_condition = $this->input->post("terms");
            //$admin_id = $this->input->post('user_id');

            $additional_data = array(
                'first_name' => strtoupper($this->input->post('first_name')),
                'last_name' => strtoupper($this->input->post('last_name')),
                /*'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone'),*/
                //'gender' => $this->input->post('gender'),
                'group_id' => $this->input->post('group') ? $this->input->post('group') : $this->input->post('role'),
                'manager_in_charge' => $this->input->post("manager_in_charge") ? $this->input->post("manager_in_charge") : 0,
                'department_id' => $this->input->post('department'),
                //'admin_id' => $this->input->post('admin_id'),
/*                'biller_id' => $this->input->post('biller'),
                'warehouse_id' => $this->input->post('warehouse'),*/
                'interview_flag' => true,
            );
            $active = "1";
            //$groupData = $this->ion_auth->in_group('super-admin') ? array($this->input->post('group')) : NULL;
            //$this->sma->print_arrays($data);
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $user_type_id, $password, $email, $additional_data, $term_and_condition, $active, $notify, $selected_firm)) {

            $this->session->set_flashdata('message', $this->ion_auth->messages());
            // redirect("employee");
            echo true;
        } else {

            //$this->data['error'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('error')));
            $this->data['error'] = 'This email register under this system already.';
            //echo json_encode($this->session->flashdata('error'));
            $this->data['users_group_dropdown'] = $this->auth_model->get_users_group_dropdown();

            $this->session->set_flashdata('employee_id', $this->input->post('employee_id'));

            $this->data['user_id'] = $this->session->userdata("user_id");
            //$this->_render_page('auth/create_user', $this->data);
            $bc = array(array('link' => site_url('home'), 'page' => lang('home')), array('link' => site_url('auth/users'), 'page' => lang('users')), array('link' => '#', 'page' => lang('create_user')));
            $meta = array('page_title' => lang('users'), 'bc' => $bc, 'page_name' => "Create User");

            $this->load->library('mybreadcrumb');
            $this->mybreadcrumb->add('Employee', base_url('employee'));
            $this->mybreadcrumb->add('Create User', base_url());
            $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

            $this->page_construct('auth/create_user_account', $meta, $this->data);
        }
    }

    public function email_duplicate_validation()
    {
        $form_data = $this->input->post();
        $isAvailable = TRUE;

        $query = $this->db->query("SELECT * FROM users WHERE user_deleted = 0 AND users.email = '".$form_data['email']."'");

        if ($query->num_rows() > 0)
        {
            $isAvailable = FALSE;
        }

        echo json_encode(array('valid' => $isAvailable));
    }
}