<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Offer_letter extends MX_Controller
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
        $this->load->model('offer_letter_model');
        $this->load->model('employment_json_model');
        $this->load->model('Day_time_json_model');
    }

    public function index()
    {   
        // $this->load->library('mybreadcrumb');
        // $this->mybreadcrumb->add('Applicant', base_url('Applicant'));

        // $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        // $this->data['interview_list'] = $this->interview_model->get_interviewList();
        // $this->page_construct('interview/index.php', $meta, $this->data);
        $this->page_construct('index.php', $this->meta, $this->data);
        // $meta['logo']       = 'assets/logo/logo.png';
        // $meta['page_name']  = '';

        // $footer['project_name'] = "PAYROLL SYSTEM";

        // echo modules::run('interview/index'); 

        // $this->_render_page('interview/index', $this->data);
        
        // $this->load->view('header', $meta);
        // $this->load->view('index');
        // $this->load->view('footer', $footer);
    }

    // public function sendOfferLetter($id){
    //     $this->page_construct('sendOfferLetter.php', $this->data);
    // }
    
    public function sendOL_NewEmployee(){
        $data = $this->input->post();

        $this->data['employee_data']    = $this->offer_letter_model->getApplicantData($data['id']);
        $this->data['salary']           = 0;
        $this->data['expected_salary']  = $this->data['employee_data'][0]->expected_salary;
        // $this->data['employment_type']  = $this->employment_json_model->getEmployment_dropdown();
        $this->data['new_employee']     = true;
        $this->data['time_in_am_pm']    = $this->Day_time_json_model->getTime_dropdown();
        $this->data['time_in_day']      = $this->Day_time_json_model->getDay_dropdown();
        $this->data['is_pr_singaporean']= 0;
        $this->data['employee_data'][0]->employee_applicant_id = $this->data['employee_data'][0]->applicant_id;

        if($this->data['employee_data'][0]->nationality_id == 165){
            $this->data['is_pr_singaporean'] = 1;
        }

        $this->data['department_list']  = $this->offer_letter_model->get_employeeDepartment();
        $this->data['currency_list']    = $this->offer_letter_model->get_currency_dropdown();

        $this->load->view('sendOfferLetter', $this->data);
    }

    public function sendOL_ExistingEmployee(){
        $data = $this->input->post();

        $this->data['employee_data']    = $this->offer_letter_model->getEmployeeData($data['id']);
        // $this->data['salary']           = $this->data['employee_data'][0]->salary;
        $this->data['employment_type']  = $this->employment_json_model->get_workpass_details();
        $this->data['new_employee']     = false;
        $this->data['time_in_am_pm']    = $this->Day_time_json_model->getTime_dropdown();
        $this->data['time_in_day']      = $this->Day_time_json_model->getDay_dropdown();
        
        $this->data['employee_data'][0]->employee_applicant_id = isset($this->data['employee_data'][0]->employee_id)?$this->data['employee_data'][0]->employee_id: '';
        // if($this->data['employee_data'][0]->workpass == "SP" || $this->data['employee_data'][0]->workpass == "EP") 
        // {
        //     $this->data['pass_given'] = "SP/EP";
        // }
        // else if($this->data['employee_data'][0]->workpass == "PR" || $this->data['employee_data'][0]->workpass == "Singaporean") 
        // {
        //     $this->data['pass_given'] = "PR/Singaporean";
        // }

        $this->load->view('sendOfferLetter', $this->data);
    }

    public function save_offer_letter(){
        $form_data = $this->input->post();

        $applicant_id = $form_data['applicant_id'];
        $employee_id = $form_data['employee_id'];

        $offer_letter = array(
            'id'                 => $form_data['offer_letter_id'],
            'effective_from'     => date('Y-m-d', strtotime($form_data['ol_effective_from'])),
            'department'         => $form_data['staff_department'],
            'designation'        => $form_data['staff_designation'],
            'job_title'          => $form_data['ol_job_title'],
            'work_hour'          => $form_data['ol_work_hour'],
            'vacation_leave'     => $form_data['ol_vacation_leave'],
            'salary_currency'    => $form_data['salary_form_currency'],
            'salary'             => $form_data['new_salary'],
            'bond'               => $form_data['hidden_bond'],
            'bond_period'        => $form_data['hidden_bond']?$form_data['bond_period']:0,
            'bond_currency'      => $form_data['hidden_bond']?$form_data['bond_form_currency']:0,
            'bond_allowance'     => $form_data['hidden_bond']?$form_data['bond_allowance']:"",
        );

        $data_ol = '';

        if(!empty($applicant_id)){
            $data_ol = $this->offer_letter_model->create_save_offer_letter_new_employee($offer_letter, $applicant_id);
        }
        else if(!empty($employee_id)){
            $data_ol = $this->offer_letter_model->create_save_offer_letter_employee($offer_letter, $employee_id);
        }

        echo $data_ol;
    }

    public function info($data){
        
        $offer_letter_info = array(
            'firm_id'                 => $data[0]->firm_id,
            'name'                    => $data[0]->name,
            'nric/passport'           => $data[0]->ic_passport_no,
            'job_title'               => $data[0]->job_title,
            'date_of_commencement'    => date('d F Y', strtotime($data[0]->effective_from)),
            'work_hour'               => $data[0]->work_hour,
            'vacation_leave'          => $data[0]->vacation_leave,
            'date_of_offer'           => date('d F Y', strtotime($data[0]->date_offer)),
            'salary_currency_code'    => $data[0]->salary_currency_code?$data[0]->salary_currency_code:"$",
            'salary'                  => $data[0]->salary?$data[0]->salary:"0",
            'bond_period'             => $data[0]->bond?$data[0]->bond_period:"0",
            'bond_currency_code'      => $data[0]->bond?$data[0]->bond_currency_code:"-",
            'bond_allowance'          => $data[0]->bond?$data[0]->bond_allowance:"-",
        );
        $offer_letter_pdf = modules::load('offer_letter/CreateEmploymentContractPdf/');
        // $return_data      = $offer_letter_pdf->create_document_pdf($offer_letter_info);
        $return_data      = $offer_letter_pdf->create_employment_contract_pdf($offer_letter_info);

        echo $return_data;
    }

    public function applicant_info($data){
        // $data = $this->offer_letter_model->getApplicant_OL(11);

        // $data[0]->is_employee = false;

        $offer_letter_info = array(
            'firm'                    => $data[0]->firm_name,
            'name'                    => $data[0]->name,
            'ic_passport_no'          => $data[0]->ic_passport_no,
            'effective_from'          => $data[0]->effective_from,
            'is_pr_singaporean'       => $data[0]->is_pr_singaporean,
            'probationary_period'     => $data[0]->probationary_period,
            'working_hour_time_start' => $data[0]->working_hour_time_start,
            'working_hour_time_end'   => $data[0]->working_hour_time_end,
            'working_hour_day_start'  => $data[0]->working_hour_day_start,
            'working_hour_day_end'    => $data[0]->working_hour_day_end,
            'given_salary'            => $data[0]->given_salary,
            'termination_notice'      => $data[0]->termination_notice,
            'employer'                => $data[0]->employer,
            'is_employee'             => $data[0]->is_employee
        );

        // echo json_encode($data[0]);

        $offer_letter_pdf = modules::load('offer_letter/CreateEmploymentContractPdf/');
        $return_data      = $offer_letter_pdf->create_document_pdf($offer_letter_info);

        echo $return_data;
    }

    public function get_departmentName(){

        $form_data = $this->input->post();

        $department = $form_data['department'];

        $data = $this->offer_letter_model->get_departmentName($department);

        echo json_encode($data);
    }
}