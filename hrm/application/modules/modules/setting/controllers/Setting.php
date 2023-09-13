<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends MX_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }
        
        $this->load->library(array('session','parser'));
        $this->load->model('setting_model');
        $this->load->model('leave/leave_model');
        $this->load->model('employee/employee_model');

    }

    public function index()
    {   
        $bc   = array(array('link' => '#', 'page' => 'Setting'));
        $meta = array('page_title' => 'Setting', 'bc' => $bc, 'page_name' => 'Setting');

        $this->meta['page_name'] = 'Setting';

        $this->data['active_tab'] = $this->session->userdata('tab_active');
        $this->session->unset_userdata('tab_active');

        $this->data['holiday_list'] = $this->setting_model->get_holiday_list();
        $this->data['partner_list'] = $this->setting_model->get_partner_list();
        $this->data['type_of_leave_list'] = $this->setting_model->get_type_of_leave_list();
        $this->data['leave_cycle_list'] = $this->setting_model->get_leave_cycle_list();
        $this->data['carry_forward_period_list'] = $this->setting_model->get_carry_forward_period_list();
        $this->data['block_leave_list'] = $this->setting_model->get_block_leave_list();
        $this->data['approval_cap_list'] = $this->setting_model->get_approval_cap_list();
        $this->data['choose_carry_forward_list'] = $this->setting_model->get_choose_carry_forward_list();

        $this->data['year_list'] = $this->setting_model->get_year_list();//jw
        $this->data['department_list'] = $this->setting_model->get_department_list();//jw
        $this->data['office_list'] = $this->setting_model->get_office_list();//jw
        $this->data['department_filter'] = $this->setting_model->get_department_filter();//jw
        $this->data['offices_filter'] = $this->setting_model->get_offices_filter();//jw
        $this->data['event_list'] = $this->setting_model->get_event_list();
        $this->data['job_list'] = $this->setting_model->get_job_list();
        $this->data['institution_list'] = $this->setting_model->get_institution_list();

        $this->data['departments'] = $this->setting_model->get_departments();
        $this->data['offices'] = $this->setting_model->get_offices();

        $this->data['approvalCap_year_filter'] = $this->setting_model->get_approvalCap_year_filter();//jw
        $this->data['approvalCap_offices_filter'] = $this->setting_model->get_approvalCap_offices_filter();//jw
        $this->data['approvalCap_department_filter'] = $this->setting_model->get_approvalCap_department_filter();//jw

        $this->data['block_leave_year_filter'] = $this->setting_model->get_block_leave_year_filter();//jw
        $this->data['block_leave_offices_filter'] = $this->setting_model->get_block_leave_offices_filter();//jw
        $this->data['block_leave_department_filter'] = $this->setting_model->get_block_leave_department_filter();//jw

        $this->data['country'] = $this->setting_model->get_country_name();//jw
        
        $this->data['charge_out_rate'] = $this->setting_model->get_charge_out_rate();//jw
        $this->data['team'] = $this->setting_model->get_team();//jw

        $this->data['partner_email'] = $this->setting_model->get_partner_email();//jw

        $this->data['jurisdiction_list'] = $this->setting_model->get_jurisdiction_info($this->session->userdata("user_admin_code_id"));//cpf
        $this->data['salary_cap_list'] = $this->setting_model->get_salary_cap();//cpf
        $this->data['currency_list']    = $this->employee_model->get_currency_dropdown();
        $this->data['age_group_period_list'] = $this->setting_model->get_age_group_period();//cpf
        $this->data['nationality_period_list'] = $this->setting_model->get_nationality_period();//cpf
        $this->data['nationality_type_list'] = $this->setting_model->get_nationality_type();//cpf





        // $this->page_construct('index.php', $this->meta, $this->data);
        $this->page_construct('index.php', $meta, $this->data);
    }

    public function submit_type_of_leave(){
        $this->session->set_userdata("tab_active", "type_of_leave");

        $form_data = $this->input->post();

        $data = array(
            'leave_name'                => $form_data['leave_name'],
            'days'                      => $form_data['leave_days'],
            'second_condition'          => $form_data['leave_days_second_condition'],
            'third_condition'           => $form_data['leave_days_third_condition'],
            'fourth_condition'          => $form_data['leave_days_fourth_condition'],
            'choose_carry_forward_id'   => $form_data['choose_carry_forward_id']
        );

        $result = $this->setting_model->submit_type_of_leave($data, $form_data['type_of_leave_id']);

        echo $result;
    }

    public function submit_offices(){
        $this->session->set_userdata("tab_active", "offices");

        $form_data = $this->input->post();

        $data = array(
            'office_name' => strtoupper($form_data['offices_office_name']),
            'office_country'  => $form_data['offices_country']
        );

        $result = $this->setting_model->submit_offices($data, $form_data['office_id']);

        echo $result;
    }

    public function submit_department(){
        $this->session->set_userdata("tab_active", "department");

        $form_data = $this->input->post();

        $data = array(
            'department_name' => strtoupper($form_data['dpt_department_name']),
        );

        $result = $this->setting_model->submit_department($data, $form_data['department_id']);

        echo $result;
    }

    public function delete_type_of_leave(){
        $this->session->set_userdata("tab_active", "type_of_leave");

        $form_data = $this->input->post();

        $result = $this->setting_model->delete_type_of_leave($form_data['type_of_leave_id']);

        echo $result;
    }

    public function submit_holiday(){

        $this->session->set_userdata("tab_active", "block_holiday");

        $form_data = $this->input->post();

        $data = array(
            'holiday_date'  => date('Y-m-d', strtotime($form_data['block_holiday'])),
            'description'   => $form_data['holiday_description'],
            'offices_id'    => $form_data['office_list'],
            'department_id' => $form_data['department_list']
        );

        $result = $this->setting_model->submit_holiday($data, $form_data['block_holiday_id']);
        echo $result;
    }//jw

    public function submit_approval_cap(){

        $this->session->set_userdata("tab_active", "approval_cap");

        $form_data = $this->input->post();

        $data = array(
            'approval_cap_date_from' => date('Y-m-d', strtotime($form_data['from'])),
            'approval_cap_date_to'   => date('Y-m-d', strtotime($form_data['to'])),
            'offices_id'             => $form_data['approvalCap_office_list'],
            'department_id'          => $form_data['approvalCap_department_list'],
            'number_of_employee'     => $form_data['number_of_employee']
        );

        $result = $this->setting_model->submit_approval_cap($data, $form_data['approval_cap_id']);

        echo $result;
    }

    public function submit_partner(){

        $this->session->set_userdata("tab_active", "partner_list");

        $form_data = $this->input->post();

        $new_string ="";

        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $form_data['partner']))
        {
            $string = $form_data['partner'];
            $array = str_split($string);

            foreach($array as $key => $letter){
               if($letter == "'"){
                  $new_string.= "%";
               }
               else{
                  $new_string.= $letter;  
               }
            }

            $search = $new_string;
        }
        else
        {
            $search = $form_data['partner'];
        }

        $data = array(
            'user_id'      => $form_data['partner_email_id'],
            'partner_name' => $form_data['partner']
        );

        $result = $this->setting_model->submit_partner($data, $search, $form_data['partner_id']);

        echo $result;
    }//jw

    public function delete_holiday(){
        
        $this->session->set_userdata("tab_active", "block_holiday");

        $form_data = $this->input->post();

        $result = $this->setting_model->delete_holiday($form_data['holiday_id']);

        echo $result;
    }

    public function delete_partner(){
        
        $this->session->set_userdata("tab_active", "partner_list");

        $form_data = $this->input->post();

        $result = $this->setting_model->delete_partner($form_data['partner_id']);

        echo $result;
    }

    public function delete_event(){
        
        $this->session->set_userdata("tab_active", "event");

        $form_data = $this->input->post();

        $result = $this->setting_model->delete_event($form_data['event_id']);

        echo $result;
    }

    public function delete_job(){
        
        $this->session->set_userdata("tab_active", "type_of_jobs");

        $form_data = $this->input->post();

        $result = $this->setting_model->delete_job($form_data['job_id']);

        echo $result;
    }

    public function delete_office(){
        
        $this->session->set_userdata("tab_active", "offices");

        $form_data = $this->input->post();

        $result = $this->setting_model->delete_office($form_data['office_id']);

        echo $result;
    }

    public function delete_department(){
        
        $this->session->set_userdata("tab_active", "department");

        $form_data = $this->input->post();

        $result = $this->setting_model->delete_department($form_data['department_id']);

        echo $result;
    }

    public function delete_institution(){
        
        $this->session->set_userdata("tab_active", "institution");

        $form_data = $this->input->post();

        $result = $this->setting_model->delete_institution($form_data['institution_id']);

        echo $result;
    }

    public function submit_leave_cycle(){

        $this->session->set_userdata("tab_active", "leave_cycle");

        $form_data = $this->input->post();

        $data = array(
            'leave_cycle_date_from' => date('m-d', strtotime($form_data['from'])),
            'leave_cycle_date_to'  => date('m-d', strtotime($form_data['to']))
        );

        $result = $this->setting_model->submit_leave_cycle($data, $form_data['leave_cycle_id']);

        echo $result;
    }

    public function submit_carry_forward_period(){
        $this->session->set_userdata("tab_active", "carry_forward_period");

        $form_data = $this->input->post();

        $data = array(
            'carry_forward_period_date' => date('m-d', strtotime($form_data['carry_forward_period_date']." 2019"))
        );

        $result = $this->setting_model->submit_carry_forward_period($data, $form_data['carry_forward_period_id']);

        echo $result;
    }

    public function submit_block_leave(){
        $this->session->set_userdata("tab_active", "block_leave");

        $form_data = $this->input->post();

        $query = $this->db->query(" SELECT payroll_leave.* from payroll_leave 
                                    LEFT JOIN payroll_employee ON payroll_leave.employee_id = payroll_employee.id 
                                    WHERE ((payroll_leave.start_date BETWEEN '".date('Y-m-d', strtotime($form_data['from']))."'AND '".date('Y-m-d', strtotime($form_data['to']))."') OR (payroll_leave.end_date BETWEEN '".date('Y-m-d', strtotime($form_data['from']))."'AND '".date('Y-m-d', strtotime($form_data['to']))."') OR (payroll_leave.start_date <= '".date('Y-m-d', strtotime($form_data['from']))."' AND payroll_leave.end_date >= '".date('Y-m-d', strtotime($form_data['to']))."')) 
                                    AND payroll_employee.office = '".$form_data['block_leave_office_list']."' 
                                    AND payroll_employee.department = '".$form_data['block_leave_department_list']."' 
                                    AND payroll_leave.status = 1 ");

        if($query->num_rows())
        {
            $query = $query->result_array();

            for($t = 0; $t < count($query); $t++)
            {
                // To get the last remaining annual leave left
                $q = $this->db->query("SELECT * FROM payroll_employee_annual_leave eal_1 WHERE eal_1.last_updated = (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id=" . $query[$t]['employee_id'] . " AND eal_2.type_of_leave_id = ".$query[$t]['type_of_leave_id'].") AND eal_1.type_of_leave_id = ".$query[$t]['type_of_leave_id']." AND eal_1.employee_id=" . $query[$t]['employee_id'] . "");

                $data['status'] = 3;
                $data['status_updated_by'] = date('Y-m-d H:i:s');
                $data['al_left_before'] = $q->result()[0]->annual_leave_days;
                $data['al_left_after'] = $q->result()[0]->annual_leave_days;

                $q2 = $this->db->where('id', $query[$t]['id']);
                $result2 = $q2->update('payroll_leave', $data);
            }
        }

        $data = array(
            'block_leave_date_from' => date('Y-m-d', strtotime($form_data['from'])),
            'block_leave_date_to'  => date('Y-m-d', strtotime($form_data['to'])),
            'department_id'  => $form_data['block_leave_department_list'],
            'offices_id'  => $form_data['block_leave_office_list']
        );

        $result = $this->setting_model->submit_block_leave($data, $form_data['block_leave_id']);

        echo $result;
       
    }//jw

    public function delete_block_leave(){
        
        $this->session->set_userdata("tab_active", "block_leave");

        $form_data = $this->input->post();

        $result = $this->setting_model->delete_block_leave($form_data['block_leave_id']);

        echo $result;
    }

    public function delete_approval_cap(){
        
        $this->session->set_userdata("tab_active", "approval_cap");

        $form_data = $this->input->post();

        $result = $this->setting_model->delete_approval_cap($form_data['approval_cap_id']);

        echo $result;
    }

    public function delete_charge_out_rate(){
        
        $this->session->set_userdata("tab_active", "charge_out_rate");

        $form_data = $this->input->post();

        $result = $this->setting_model->delete_charge_out_rate($form_data['charge_out_rate_id']);

        echo $result;
    }

    // public function holiday_year_filter(){

    //     // $this->session->set_userdata("tab_active", "year_of_holiday");

    //     $form_data = $this->input->post();

    //     $result = $this->setting_model->get_holiday_filter($form_data['offices_filter'],$form_data['department_filter'],$form_data['year_list']);

    //     echo json_encode($result);
    // }//JW

    // public function holiday_offices_filter(){

    //     // $this->session->set_userdata("tab_active", "department_of_holiday");

    //     $form_data = $this->input->post();

    //     $result = $this->setting_model->get_holiday_filter($form_data['offices_filter'],$form_data['department_filter'],$form_data['year_list']);

    //     echo json_encode($result);
    // }//JW

    public function holiday_filter(){

        $form_data = $this->input->post();

        $result = $this->setting_model->get_holiday_filter($form_data['offices_filter'],$form_data['department_filter'],$form_data['year_list']);

        echo json_encode($result);
    }//JW

    public function cap_filter(){

        $form_data = $this->input->post();

        $result = $this->setting_model->get_cap_filter($form_data['offices_filter'],$form_data['department_filter'],$form_data['year_list']);

        echo json_encode($result);
    }//JW

    public function block_leave_filter(){

        $form_data = $this->input->post();

        $result = $this->setting_model->get_block_leave_filter($form_data['offices_filter'],$form_data['department_filter'],$form_data['year_list']);

        echo json_encode($result);
    }//JW

    public function submit_event(){

        $this->session->set_userdata("tab_active", "event");

        $form_data = $this->input->post();

        $new_string ="";

        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $form_data['event']))
        {
            $string = $form_data['event'];
            $array = str_split($string);

            foreach($array as $key => $letter){
               if($letter == "'"){
                  $new_string.= "%";
               }
               else{
                  $new_string.= $letter;  
               }
            }

            $search = $new_string;
        }
        else
        {
             $search = $form_data['event'];
        }

        $data = array(
            'event' => $form_data['event']
        );

        $result = $this->setting_model->submit_event($data, $search, $form_data['event_id']);

        echo $result;
    }//jw

    public function submit_job(){

        $this->session->set_userdata("tab_active", "type_of_jobs");

        $form_data = $this->input->post();

        $new_string ="";

        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $form_data['job']))
        {
            $string = $form_data['job'];
            $array = str_split($string);

            foreach($array as $key => $letter){
               if($letter == "'"){
                  $new_string.= "%";
               }
               else{
                  $new_string.= $letter;  
               }
            }

            $search = $new_string;
        }
        else
        {
            $search = $form_data['job'];
        }

        $data = array(
            'type_of_job' => $form_data['job']
        );

        $result = $this->setting_model->submit_job($data, $search, $form_data['job_id']);

        echo $result;
    }//jw

    public function submit_institution(){

        $this->session->set_userdata("tab_active", "institution");

        $form_data = $this->input->post();

        $new_string ="";

        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $form_data['institution']))
        {
            $string = $form_data['institution'];
            $array = str_split($string);

            foreach($array as $key => $letter){
               if($letter == "'"){
                  $new_string.= "%";
               }
               else{
                  $new_string.= $letter;  
               }
            }

            $search = $new_string;
        }
        else
        {
            $search = $form_data['institution'];
        }

        $data = array(
            'institution_name' => $form_data['institution']
        );

        $result = $this->setting_model->submit_institution($data, $search, $form_data['institution_id']);

        echo $result;
    }//jw

    public function get_designation(){

        $form_data = $this->input->post();

        $department = $form_data['department'];

        if($department == '7'){

            $department = '%%';
        }

        $result = $this->setting_model->get_designation($department);

        echo json_encode($result);
    }//JW

    public function submit_charge_out_rate(){
        $this->session->set_userdata("tab_active", "charge_out_rate");

        $form_data = $this->input->post();

        $data = array(
            'office_id'      => $form_data['charge_out_rate_office_list'],
            'department_id'  => $form_data['charge_out_rate_department_list'],
            'designation_id' => $form_data['charge_out_rate_designation'],
            'rate'           => $form_data['charge_out_rate']
        );

        $result = $this->setting_model->submit_charge_out_rate($data, $form_data['charge_out_rate_id']);

        echo $result;
    }

    public function team_filter(){
        $this->session->set_userdata("tab_active", "team_shifts");

        $form_data = $this->input->post();

        if($form_data['team_id'] == 'A')
        {
            $team = '%%';
        }
        else
        {
            $team = $form_data['team_id'];
        }

        $result = $this->setting_model->get_team_filter($team);

        echo json_encode($result);
    }

    public function update_team(){
        $this->session->set_userdata("tab_active", "team_shifts");

        $form_data = $this->input->post();

        $data = array(
            'team_shift' => $form_data['team_shift'],
        );

        $result = $this->setting_model->update_team($form_data['id'], $data);

        echo json_encode($result);
    }

    public function add_salary_cap()
    {
        $this->session->set_userdata("tab_active", "cpf");

        $form_data = $this->input->post();

        $data = $form_data["data"];

        $salary_cap_info = array(
            'id'                => $data[0],
            'cap_start_date'    => date('Y-m-d', strtotime(str_replace('/', '-', $data[1]))),
            'cap_end_date'      => $data[2]?date('Y-m-d', strtotime(str_replace('/', '-', $data[2]))):null,
            'currency'          => $data[3],
            'monthly_cap_value' => $data[4],
            'annual_cap_value'  => $data[5],
        );

        if($salary_cap_info['id'] !== "")
        {
            $result = $this->setting_model->update_salary_cap($salary_cap_info['id'], $salary_cap_info);
        }
        else
        {
            $result = $this->setting_model->insert_salary_cap($salary_cap_info);
        }

        echo json_encode($result);

        // print_r($salary_cap_info);
    }

    public function add_age_group_period()
    {
        $this->session->set_userdata("tab_active", "cpf");

        $form_data = $this->input->post();

        $data = $form_data["data"];

        $age_group_period = array(
            'id'                => $data[0],
            'period_start_date'    => date('Y-m-d', strtotime(str_replace('/', '-', $data[1]))),
            'period_end_date'      => $data[2]?date('Y-m-d', strtotime(str_replace('/', '-', $data[2]))):null,
            'last_update_by'       => $this->session->userdata('user_id')
        );

        if($age_group_period['id'] !== "")
        {
            $result = $this->setting_model->update_age_group_period($age_group_period['id'], $age_group_period);
        }
        else
        {
            $result = $this->setting_model->insert_age_group_period($age_group_period);
        }

        echo json_encode($result);

        // print_r($salary_cap_info);
    }

    public function get_age_group(){
        // $this->session->set_userdata("tab_active", "team_shifts");

        $form_data = $this->input->post();

        $period_id = $form_data['id'];

        $result = $this->setting_model->get_age_group($period_id);

        echo json_encode($result);
    }

    public function save_age_group()
    {
        // $this->session->set_userdata("tab_active", "cpf");

        $form_data = $this->input->post();

        // print_r($form_data);
        $age_group_ids = $form_data['age_group_id'];
        $age_years = $form_data['age_years'];
        $age_months = $form_data['age_months'];
        $employer_percent = $form_data['employer_percent'];
        $employee_percent = $form_data['employee_percent'];

        

        foreach ($age_group_ids as $key => $age_group_id) 
        {
            $age_group_arr = array(
                    'id'                        => $age_group_id,
                    'age_group_period_id'       => $form_data['age_group_period_id'],
                    'age_years'                 => $age_years[$key],
                    'age_months'                => $age_months[$key],
                    'employer_percent'          => $employer_percent[$key],
                    'employee_percent'          => $employee_percent[$key],
                    'created_by'       => $this->session->userdata('user_id')

                );

            $result = $this->setting_model->insert_age_group($age_group_arr, $age_group_id);
            

        }

        echo json_encode($result);
    }

    public function delete_age_group ()
    {
        $id = $_POST["age_group_id"];

        $data["deleted"] = 1;

        $this->db->update("payroll_cpf_age_group", $data, array('id'=>$id));

        echo json_encode(array("Status" => 1));
                
    }

    public function add_nationality_period()
    {
        $this->session->set_userdata("tab_active", "cpf");

        $form_data = $this->input->post();

        $data = $form_data["data"];

        $nationality_period = array(
            'id'                => $data[0],
            'period_start_date'    => date('Y-m-d', strtotime(str_replace('/', '-', $data[1]))),
            'period_end_date'      => $data[2]?date('Y-m-d', strtotime(str_replace('/', '-', $data[2]))):null,
            'last_update_by'       => $this->session->userdata('user_id')
        );

        if($nationality_period['id'] !== "")
        {
            $result = $this->setting_model->update_nationality_period($nationality_period['id'], $nationality_period);
        }
        else
        {
            $result = $this->setting_model->insert_nationality_period($nationality_period);
        }

        echo json_encode($result);

        // print_r($salary_cap_info);
    }

    public function get_nationality(){
        // $this->session->set_userdata("tab_active", "team_shifts");

        $form_data = $this->input->post();

        $period_id = $form_data['id'];

        $result = $this->setting_model->get_nationality($period_id);

        echo json_encode($result);
    }

    public function save_nationality()
    {
        // $this->session->set_userdata("tab_active", "cpf");

        $form_data = $this->input->post();

        // print_r($form_data);
        $nationality_ids = $form_data['nationality_id'];
        $nationality_type = $form_data['nationality_type'];
        $employer_percent = $form_data['employer_percent'];
        $employee_percent = $form_data['employee_percent'];

        foreach ($nationality_ids as $key => $nationality_id) 
        {
            $nationality_arr = array(
                    'id'                        => $nationality_id,
                    'nationality_period_id'     => $form_data['nationality_period_id'],
                    'nationality_type'          => $nationality_type[$key],
                    'employer_percent'          => $employer_percent[$key],
                    'employee_percent'          => $employee_percent[$key],
                    'last_update_by'            => $this->session->userdata('user_id')

                );

            $result = $this->setting_model->insert_nationality($nationality_arr, $nationality_id);
            

        }

        echo json_encode($result);
    }

}