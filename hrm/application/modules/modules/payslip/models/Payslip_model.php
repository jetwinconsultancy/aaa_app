<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payslip_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
        $this->load->model('employee/employee_model');
        $this->load->model('setting/setting_model');


    }

    public function get_all_employee_list($data){


        // $q = $this->db->query("SELECT p.*, e.name, e.nric_fin_no FROM payslip p LEFT JOIN employee e ON e.id = p.employee_id WHERE p.shown = 1 AND year(p.payslip_for) = year('" . $data['date'] . "') AND month(p.payslip_for) = month('" . $data['date'] . "')");
        if(isset($data['department']) && $data['department'] != 0)
        {
            $q = $this->db->query("SELECT p.*, e.name, e.nric_fin_no, d.department_name FROM payroll_payslip p LEFT JOIN payroll_employee e ON e.id = p.employee_id LEFT JOIN department d on d.id = p.department WHERE year(p.payslip_for) = year('" . $data['date'] . "') AND month(p.payslip_for) = month('" . $data['date'] . "') AND e.department = ".$data['department']);
        }
        else
        {
            $q = $this->db->query("SELECT p.*, e.name, e.nric_fin_no, d.department_name FROM payroll_payslip p LEFT JOIN payroll_employee e ON e.id = p.employee_id LEFT JOIN department d on d.id = p.department WHERE year(p.payslip_for) = year('" . $data['date'] . "') AND month(p.payslip_for) = month('" . $data['date'] . "')");

        }

        return $q->result();
    }

    public function get_all_bonus_list($data){
        $q = $this->db->query("SELECT p.* FROM payroll_payslip p WHERE year(p.payslip_for) = year('" . $data['date'] . "') AND month(p.payslip_for) = month('" . $data['date'] . "')  AND (p.aws > 0 OR p.bonus > 0 OR p.commission > 0 AND p.health_incentive > 0 AND p.other_allowance > 0)");

        return $q->result();
    }

    public function get_all_months(){
        $q = $this->db->query("SELECT DISTINCT p.payslip_for FROM payroll_payslip p ORDER BY YEAR(p.payslip_for), MONTH(p.payslip_for)");

        $list = array();

        foreach ($q->result() as $key=>$item) {
            $list[$key] = $item->payslip_for;
        }
        // echo json_encode($list);
        return $list;
    }

    /* get employee */
    public function get_list($user_id){
        $q = $this->db->query("SELECT payroll_payslip.* FROM payroll_payslip, payroll_user_employee WHERE payroll_user_employee.user_id = '".$user_id."' AND payroll_payslip.employee_id = payroll_user_employee.employee_id");

        return $q->result();
    }

    public function get_payslip_months($employee_id){
        $q = $this->db->query("SELECT generate_by FROM payroll_payslip WHERE employee_id = '". $employee_id ."'");

        $month_list = array();
        $month_list[''] = "-- Select month --";

        // echo json_encode($q->result());

        foreach($q->result() as $date){
            $month_year = date('M Y', strtotime($date->generate_by));
            $month_list[$month_year] = $month_year;
            // echo date('YYYY/MM/DD', strtotime($date));
        }

        return $month_list;
    }

    public function view_payslip($payslip_id){
        $q = $this->db->query("SELECT p.*, e.name, e.nric_fin_no, e.department, e.firm_id, e.designation, department.department_name, currency.currency as currency_shortform FROM payroll_payslip p LEFT JOIN payroll_employee e ON e.id = p.employee_id LEFT JOIN department on e.department = department.id LEFT JOIN currency on currency.id = p.currency_id WHERE p.id ='". $payslip_id ."'");

        return $q->result()[0];
    }

    public function get_previous_payables($selected_month, $employee_id)
    {
        $q = $this->db->query("SELECT p.*, e.name, e.nric_fin_no, e.department, e.firm_id, e.designation, department.department_name, currency.currency as currency_shortform FROM payroll_payslip p LEFT JOIN payroll_employee e ON e.id = p.employee_id LEFT JOIN department on e.department = department.id LEFT JOIN currency on currency.id = p.currency_id WHERE MONTH('".$selected_month."') > MONTH(payslip_for) AND YEAR('".$selected_month."') = YEAR(payslip_for) AND employee_id = '".$employee_id."'");

        $previous_payslips = $q->result();

        $sum = 0;

        foreach($previous_payslips as $each)
        {
            $sum += $each->salary_cpf_payable;
            $sum += $each->bonus_cpf_payable;
        }

        return $sum;
    }

    public function set_bonus_payslip($data){

        $return_result = array(
            'result' => 0,
            'data'   => array()
        );

        $temp = array();

        foreach($data as $item){

            if(!($item['aws'] == 0 && $item['bonus'] == 0.00 && $item['commission'] == 0.00 && $item['health_incentive'] == 0 && $item['other_allowance'] == 0.00)){

                $q = $this->db->query("SELECT * FROM payroll_payslip p WHERE p.id ='". $item['id'] ."' OR (p.employee_id='". $item['employee_id'] ."' AND p.payslip_for ='". $item['payslip_for'] ."')");

                // echo json_encode($q->result());

                if(!$q->num_rows()){
                    $result = $this->db->insert('payroll_payslip', $item);    // insert new payslip to database
                    $item['id'] = $this->db->insert_id();

                    if(!($result > 0)) {
                        $return_result['result'] = 0;
                        return $return_result;
                    }else{
                        array_push($temp, $item);
                    }
                } 
                else{
                    $item['id'] = $q->result()[0]->id;

                    $q2 = $this->db->where('id', $q->result()[0]->id);
                    $result = $q2->update('payroll_payslip', $item);

                    if(!($result > 0)) {
                        $return_result['result'] = 0;
                        return $return_result;
                    }else{
                        array_push($temp, $item);
                    }
                }
            }
        }

        $return_result['result'] = 1;
        $return_result['data']   = $temp;

        return $return_result;
    }

    public function get_payslip_settings(){
        $q = $this->db->query("SELECT * FROM payroll_payslip_setting");

         return $q->result()[0];
    }

    public function save_payslip_setting($data){
        $q = $this->db->select('*')->where('id', $data['id'])->get('payroll_payslip_setting');

        // echo json_encode($q->result());

        if(!$q->num_rows()){
            $this->db->insert('payroll_payslip_setting', $data); 
            $payslip_setting_id = $this->db->insert_id();

            return $payslip_setting_id;
        } 
        else{
            $this->db->where('id', $q->result()[0]->id);
            $result = $this->db->update('payslip_setting', $data);

            if($result){
                return $q->result()[0]->id; // retrieve id
            }
        }
    }

    public function generate_all_payslip($selected_month, $user_id=NULL, $is_manager=NULL){

    
        // $employee_list   = $this->db->query("SELECT * FROM payroll_employee")->result();
        if($user_id == NULL) // ADMIN
        {
            $employee_list = $this->db->query("SELECT e.* FROM payroll_employee e WHERE e.employee_status_id IN (1,2) OR (e.employee_status_id IN (3,4) AND DATE(date_cessation) >= DATE('".$selected_month."')) GROUP BY e.id ORDER BY e.name");
        }
        else if($user_id != NULL && $is_manager == 'true') // MANAGER
        {
            $employee_list = $this->db->query("SELECT e.*, ue.user_id AS `user_id` FROM payroll_employee e LEFT JOIN payroll_user_employee ue ON ue.employee_id = e.id LEFT JOIN users u ON ue.user_id = u.id  WHERE (e.employee_status_id IN (1,2) AND (ue.user_id ='". $user_id ."' or u.manager_in_charge ='". $user_id ."')) OR (e.employee_status_id IN (3,4) AND DATE(date_cessation) >=  DATE('".$selected_month."') AND (ue.user_id ='". $user_id ."' or u.manager_in_charge ='". $user_id ."')) GROUP BY e.id ORDER BY e.name");

        }
        else  // NORMAL USER
        {
            $employee_list = $this->db->query("SELECT e.*, ue.user_id AS `user_id` FROM payroll_employee e LEFT JOIN payroll_user_employee ue ON ue.employee_id = e.id LEFT JOIN users u ON ue.user_id = u.id  WHERE ue.user_id ='". $user_id ."' GROUP BY e.id ORDER BY e.name");
        }

        $employee_list = $employee_list->result();

        // $payslip_setting = $this->db->query("SELECT * FROM payroll_payslip_setting")->result()[0];

        $payslip_bundle = array();

        $age_group_period = $this->get_selected_month_age_group_period($selected_month);
        $age_group_period_id = $age_group_period['id'];
        $age_groups = $this->setting_model->get_age_group($age_group_period_id);

        $nationality_period = $this->get_selected_month_nationality_period($selected_month);
        $nationality_period_id = $nationality_period['id'];
        $nationality_groups = $this->setting_model->get_nationality($nationality_period_id);

        $salary_cap = $this->get_selected_month_salary_cap($selected_month);

        foreach($employee_list as $employee){

            // print_r($employee);

            $salary = $this->employee_model->get_salary_info($employee->id, $selected_month);
            $bond   = $this->employee_model->get_bond_info($employee->id,  $selected_month);

            $date1 = new DateTime($selected_month);
            $dob = new DateTime($employee->dob);
            $interval = $date1->diff($dob);
            $employee_age_year  = $interval->y;
            $employee_age_month = $interval->m;

            // echo "year: ".$employee_age_year.", month: ".$employee_age_month;

            if($employee->singapore_pr || $employee->nationality_id == 165)
            {
                $date1 = new DateTime($selected_month);
                $date2 = new DateTime($employee->pr_issued_date);
                $interval = $date1->diff($date2);
                $pr_year  = $interval->y; 
                // $pr_month = $interval->m;
            
                if($employee->singapore_pr && $pr_year < 2)
                {
                    $nationality_group = $nationality_groups[array_search(1, array_column($nationality_groups, 'nationality_type'))];
                    // print_r(array('pr year1'));
                    // print_r($nationality_group);
                    // print_r(array_column($nationality_groups, 'nationality_type'));

                    $employer_percent = $nationality_group['employer_percent'];
                    $employee_percent = $nationality_group['employee_percent'];
                }
                elseif($employee->singapore_pr && $pr_year < 3)
                {
                    $nationality_group = $nationality_groups[array_search(2, array_column($nationality_groups, 'nationality_type'))];
                    // print_r($nationality_group);
                    // print_r(array('pr year2'));m
                    $employer_percent = $nationality_group['employer_percent'];
                    $employee_percent = $nationality_group['employee_percent'];
                }
                elseif($employee->nationality_id == 165 || $pr_year >= 3)
                {
                    foreach($age_groups as $age_group)
                    {
                        $condition = $age_group['age_years']."-".$age_group['age_months']."-01";
                        $condition = date("Y-m", strtotime($condition));

                        $employee_age = $employee_age_year."-".$employee_age_month."-01";;
                        $employee_age = date("Y-m", strtotime($employee_age));

                        // echo $condition . "           " . $employee_age;

                        if($employee_age < $condition)
                        {
                            $employee_percent = $age_group['employee_percent'];
                            $employer_percent = $age_group['employer_percent'];
                            break;
                        }
                    }
                    
                }
                
            }
            else
            {
                $employee_percent = 0;
                $employer_percent = 0;
            }



            // print_r($salary);
            // print_r($salary_cap);


        
            $temp_salary = $salary?is_object($salary[0])?$salary[0]->salary:0:0;
            $cpf_employee = (float)$temp_salary * (float)$employee_percent / 100;
            $cpf_employer = (float)$temp_salary * (float)$employer_percent / 100;

            // print_r(array($cpf_employee));

            $payslip_item = array(
                'id'             => '',
                'employee_id'    => $employee->id,
                'payslip_for'    => $selected_month,
                'date'           => date('Y-m-d H:i:s'),
                'department'     => $employee->department,
                'basic_salary'   => $salary?is_object($salary[0])?$salary[0]->salary:'':'',
                'cdac'           => $employee->cdac,
                'bond_allowance' => $bond?is_object($bond[0])?$bond[0]->bond_allowance:'':'',
                'cpf_employee'   => $cpf_employee,
                'cpf_employer'   => $cpf_employer,
                // 'sd_levy'     => $payslip_setting->sdl,
                'generate_by'    => $this->session->userdata("user_id"),
                'remaining_al'   => $employee->remaining_annual_leave,
                'currency_id'    => $salary?is_object($salary[0])?$salary[0]->currency_id:1:1,
                'shown'          => 0
            );

            // print_r($payslip_item);

            array_push($payslip_bundle, $payslip_item);

            $q = $this->db->query("SELECT * FROM payroll_payslip p WHERE p.employee_id='". $employee->id ."' AND p.payslip_for ='". $selected_month ."'");

            if(!$q->num_rows()){
                $result = $this->db->insert('payroll_payslip', $payslip_item);    // insert new payslip to database
                $payslip_id = $this->db->insert_id();

                if(!($result > 0)) {
                    return false;
                }
            } 
            else{
                $payslip_item['id'] = $q->result()[0]->id;

                $q2 = $this->db->where('id', $q->result()[0]->id);
                $result = $q2->update('payroll_payslip', $payslip_item);

                if(!($result > 0)) {
                    return false;
                }
            }
        }

        return true;

        // echo json_encode($payslip_bundle);
    }

    public function check_all_payslip($selected_month,$department=NULL, $user_id=NULL, $is_manager=NULL){

    
        // $employee_list   = $this->db->query("SELECT * FROM payroll_employee")->result();
        if($user_id == NULL) // ADMIN
        {
            if($department)
            {
                $employee_list = $this->db->query("SELECT e.* FROM payroll_employee e WHERE e.department = '".$department."' AND e.employee_status_id IN (1,2) OR (e.employee_status_id IN (3,4) AND DATE(date_cessation) >= DATE('".$selected_month."'))  GROUP BY e.id ORDER BY e.name");

            }
            else
            {
                $employee_list = $this->db->query("SELECT e.* FROM payroll_employee e WHERE e.employee_status_id IN (1,2) OR (e.employee_status_id IN (3,4) AND DATE(date_cessation) >= DATE('".$selected_month."'))  GROUP BY e.id ORDER BY e.name");

            }
        }
        else if($user_id != NULL && $is_manager == 'true') // MANAGER
        {
            $employee_list = $this->db->query("SELECT e.*, ue.user_id AS `user_id` FROM payroll_employee e LEFT JOIN payroll_user_employee ue ON ue.employee_id = e.id LEFT JOIN users u ON ue.user_id = u.id  WHERE (e.employee_status_id IN (1,2) AND (ue.user_id ='". $user_id ."' or u.manager_in_charge ='". $user_id ."')) OR (e.employee_status_id IN (3,4) AND DATE(date_cessation) >=  DATE('".$selected_month."') AND (ue.user_id ='". $user_id ."' or u.manager_in_charge ='". $user_id ."')) GROUP BY e.id ORDER BY e.name");

        }
        else  // NORMAL USER
        {
            $employee_list = $this->db->query("SELECT e.*, ue.user_id AS `user_id` FROM payroll_employee e LEFT JOIN payroll_user_employee ue ON ue.employee_id = e.id LEFT JOIN users u ON ue.user_id = u.id  WHERE ue.user_id ='". $user_id ."' GROUP BY e.id ORDER BY e.name");
        }

        $employee_list = $employee_list->result();

        // $payslip_setting = $this->db->query("SELECT * FROM payroll_payslip_setting")->result()[0];

        $payslip_bundle = array();

        $age_group_period = $this->get_selected_month_age_group_period($selected_month);
        $age_group_period_id = $age_group_period['id'];
        $age_groups = $this->setting_model->get_age_group($age_group_period_id);

        $nationality_period = $this->get_selected_month_nationality_period($selected_month);
        $nationality_period_id = $nationality_period['id'];
        $nationality_groups = $this->setting_model->get_nationality($nationality_period_id);

        // $salary_cap = $this->get_selected_month_salary_cap($selected_month);


        // print_r($employee_list);

        foreach($employee_list as $employee){

            // print_r($employee);

            $salary = $this->employee_model->get_salary_info($employee->id, $selected_month);
            $bond   = $this->employee_model->get_bond_info($employee->id,  $selected_month);
            
            $date1 = new DateTime($selected_month);
            $dob = new DateTime($employee->dob);
            $interval = $date1->diff($dob);
            $employee_age_year  = $interval->y;
            $employee_age_month = $interval->m;

            // echo "year: ".$employee_age_year.", month: ".$employee_age_month;

            if($employee->singapore_pr || $employee->nationality_id == 165)
            {
                $date1 = new DateTime($selected_month);
                $date2 = new DateTime($employee->pr_issued_date);
                $interval = $date1->diff($date2);
                $pr_year  = $interval->y; 
                // $pr_month = $interval->m;
            
                if($employee->singapore_pr && $pr_year < 2)
                {
                    $nationality_group = $nationality_groups[array_search(1, array_column($nationality_groups, 'nationality_type'))];
                    // print_r(array('pr year1'));
                    // print_r($nationality_group);
                    // print_r(array_column($nationality_groups, 'nationality_type'));

                    $employer_percent = $nationality_group['employer_percent'];
                    $employee_percent = $nationality_group['employee_percent'];
                }
                elseif($employee->singapore_pr && $pr_year < 3)
                {
                    $nationality_group = $nationality_groups[array_search(2, array_column($nationality_groups, 'nationality_type'))];
                    // print_r($nationality_group);
                    // print_r(array('pr year2'));m
                    $employer_percent = $nationality_group['employer_percent'];
                    $employee_percent = $nationality_group['employee_percent'];
                }
                elseif($employee->nationality_id == 165 || $pr_year >= 3)
                {
                    foreach($age_groups as $age_group)
                    {
                        $condition = $age_group['age_years']."-".$age_group['age_months']."-01";
                        $condition = date("Y-m", strtotime($condition));

                        $employee_age = $employee_age_year."-".$employee_age_month."-01";;
                        $employee_age = date("Y-m", strtotime($employee_age));

                        // echo $condition . "           " . $employee_age;

                        if($employee_age < $condition)
                        {
                            $employee_percent = $age_group['employee_percent'];
                            $employer_percent = $age_group['employer_percent'];
                            break;
                        }
                    }
                }
                
            }
            else
            {
                $employee_percent = 0;
                $employer_percent = 0;
            }

            // echo "employee_percent: ".$employee_percent.", employer_percent: ".$employer_percent;

            // print_r($salary_cap);
 
            $cpf_employee = (float)$employee->salary * $employee_percent / 100;
            $cpf_employer = (float)$employee->salary * $employer_percent / 100;

            $payslip_item = array(
                'id'             => '',
                'employee_name'  => $employee->name,
                'employee_id'    => $employee->id,
                'payslip_for'    => $selected_month,
                'date'           => date('Y-m-d H:i:s'),
                'department'     => $employee->department,
                'basic_salary'   => $salary?is_object($salary[0])?$salary[0]->salary:'':'',
                'cdac'           => $employee->cdac,
                'bond_allowance' => $bond?is_object($bond[0])?$bond[0]->bond_allowance:'':'',
                'cpf_employee'   => $cpf_employee,
                'cpf_employer'   => $cpf_employer,
                // 'sd_levy'     => $payslip_setting->sdl,
                'generate_by'    => date('Y-m-d H:i:s'),
                'remaining_al'   => $employee->remaining_annual_leave,
                'currency_id'    => $salary?is_object($salary[0])?$salary[0]->currency_id:1:1,
                'shown'          => 0
            );

            // print_r($payslip_item);

            array_push($payslip_bundle, $payslip_item);

        }

        return $payslip_bundle;

        // echo json_encode($payslip_bundle);
    }

    public function save_and_calculate_cpf($selected_month, $payslips)
    {
        $payslip_bundle = array();

        $age_group_period = $this->get_selected_month_age_group_period($selected_month);
        $age_group_period_id = $age_group_period['id'];
        $age_groups = $this->setting_model->get_age_group($age_group_period_id);

        $nationality_period = $this->get_selected_month_nationality_period($selected_month);
        $nationality_period_id = $nationality_period['id'];
        $nationality_groups = $this->setting_model->get_nationality($nationality_period_id);

        $salary_cap = $this->get_selected_month_salary_cap($selected_month);

        foreach ($payslips as $payslip)
        {
    
            $employee = $this->db->query("SELECT e.*, ue.user_id AS `user_id` FROM payroll_employee e LEFT JOIN payroll_user_employee ue ON ue.employee_id = e.id LEFT JOIN users u ON ue.user_id = u.id  WHERE ue.employee_id ='". $payslip['employee_id'] ."' GROUP BY e.id ORDER BY e.name");
            $employee = $employee->result()[0];

            $q = $this->db->query("SELECT * FROM payroll_payslip p WHERE p.employee_id='". $payslip['employee_id'] ."' AND p.payslip_for ='". $selected_month ."'");

            $salary = $this->employee_model->get_salary_info($payslip['employee_id'], $selected_month);

            $salary = $payslip['basic_salary'];
            $bond   = $payslip['bond_allowance'];
            
            $date1 = new DateTime($selected_month);
            $dob = new DateTime($employee->dob);
            $interval = $date1->diff($dob);
            $employee_age_year  = $interval->y;
            $employee_age_month = $interval->m;

            // echo "year: ".$employee_age_year.", month: ".$employee_age_month;

            if($employee->singapore_pr || $employee->nationality_id == 165)
            {
                $date1 = new DateTime($selected_month);
                $date2 = new DateTime($employee->pr_issued_date);
                $interval = $date1->diff($date2);
                $pr_year  = $interval->y; 
                // $pr_month = $interval->m;
            
                if($employee->singapore_pr && $pr_year < 2)
                {
                    $nationality_group = $nationality_groups[array_search(1, array_column($nationality_groups, 'nationality_type'))];
                    // print_r(array('pr year1'));
                    // print_r($nationality_group);
                    // print_r(array_column($nationality_groups, 'nationality_type'));

                    $employer_percent = $nationality_group['employer_percent'];
                    $employee_percent = $nationality_group['employee_percent'];
                }
                elseif($employee->singapore_pr && $pr_year < 3)
                {
                    $nationality_group = $nationality_groups[array_search(2, array_column($nationality_groups, 'nationality_type'))];
                    // print_r($nationality_group);
                    // print_r(array('pr year2'));m
                    $employer_percent = $nationality_group['employer_percent'];
                    $employee_percent = $nationality_group['employee_percent'];
                }
                elseif($employee->nationality_id == 165 || $pr_year >= 3)
                {
                    foreach($age_groups as $age_group)
                    {
                        $condition = $age_group['age_years']."-".$age_group['age_months']."-01";
                        $condition = date("Y-m", strtotime($condition));

                        $employee_age = $employee_age_year."-".$employee_age_month."-01";;
                        $employee_age = date("Y-m", strtotime($employee_age));

                        // echo $condition . "           " . $employee_age;

                        if($employee_age < $condition)
                        {
                            $employee_percent = $age_group['employee_percent'];
                            $employer_percent = $age_group['employer_percent'];
                            break;
                        }
                    }
                }
                
            }
            else
            {
                $employee_percent = 0;
                $employer_percent = 0;
            }

            // echo "employee_percent: ".$employee_percent.", employer_percent: ".$employer_percent;

            // print_r($salary_cap);
            $monthly_salary_cap = $salary_cap['monthly_cap_value'];
            $annual_salary_cap = $salary_cap['annual_cap_value'];

            $temp_salary = (float)$payslip['basic_salary'];
            if($temp_salary > $monthly_salary_cap)
            {
                $temp_salary = $monthly_salary_cap;
            }

            $temp_bonus = (float)$payslip['bonus'] + (float)$payslip['bond_allowance'];
            $month = date("m",strtotime($selected_month));
            $month_left = 13 - (int)$month;

            $previous_payables = $this->get_previous_payables($selected_month, $employee->id);
            $estimate_quota_left = $annual_salary_cap - (($payslip['basic_salary']  * $month_left) + $previous_payables);

            if($temp_bonus > $estimate_quota_left)
            {
                $temp_bonus = $estimate_quota_left;
            }

            // $payslip['salary_cpf_payable'] = $temp_salary;
            // $payslip['bonus_cpf_payable'] = $temp_bonus;


            // print_r($estimate_quota_left." ");
            // print_r($employee);
            

            

            $salary_cpf_employee = $temp_salary * $employee_percent / 100;
            $salary_cpf_employer = $temp_salary * $employer_percent / 100;

            $additional_cpf_employee = $temp_bonus * $employee_percent / 100;
            $additional_cpf_employer = $temp_bonus * $employer_percent / 100;

            $cpf_employee = $salary_cpf_employee + $additional_cpf_employee;
            $cpf_employer = $salary_cpf_employer + $additional_cpf_employer;

            // $cpf_employee = (float)$payslip['basic_salary'] * $employee_percent / 100;
            // $cpf_employer = (float)$payslip['basic_salary'] * $employer_percent / 100;

            $payslip['department'] = $employee->department;
            $payslip['currency_id'] = $salary?is_object($salary[0])?$salary[0]->currency_id:1:1;

            if(!$q->num_rows()){
                $result = $this->db->insert('payroll_payslip', $payslip);    // insert new payslip to database
                $payslip_id = $this->db->insert_id();

            } 
            else{
                $payslip['id'] = $q->result()[0]->id;
                $payslip_id = $q->result()[0]->id;

                $q2 = $this->db->where('id', $q->result()[0]->id);
                $result = $q2->update('payroll_payslip', $payslip);
            }

            $payslip_item = array(
                'id'             => $payslip_id,
                'employee_name'  => $employee->name,
                'employee_id'    => $employee->id,
                'payslip_for'    => $selected_month,
                'date'           => date('Y-m-d H:i:s'),
                'department'     => $employee->department,
                'cdac'           => $employee->cdac,
                'cpf_employee'   => $cpf_employee,
                'cpf_employer'   => $cpf_employer,
                'salary_cpf_payable' => $temp_salary,
                'bonus_cpf_payable'  => $temp_bonus,
                'employee_percent' => $employee_percent,
                'employer_percent' => $employer_percent,
                // 'sd_levy'     => $payslip_setting->sdl,
                'generate_by'    => date('Y-m-d H:i:s'),
                'remaining_al'   => $employee->remaining_annual_leave,
                'currency_id'    => $salary?is_object($salary[0])?$salary[0]->currency_id:1:1,
            );

            

            array_push($payslip_bundle, $payslip_item);

        }
        return $payslip_bundle;
        
    }

    public function remove_bonus($payslip_id){
        $bonus = array(
            'aws' => 0,
            'bonus' => 0,
            'commission' => 0,
            'health_incentive' => 0,
            'other_incentive' => 0
        );

        $q      = $this->db->where('id', $payslip_id);
        $result = $q->update('payroll_payslip', $bonus);

        return $result;
        // $q = $this->db->query("UPDATE payslip p SET p.aws = 0, p.bonus = 0, p.commission = 0, p.health_incentive = 0, p.other_incentive = 0 WHERE id'". $payslip ."'");
    }

    public function get_selected_month_nationality_period($selected_month)
    {
        $nationality_period = $this->db->query("SELECT * FROM payroll_cpf_nationality_period WHERE (DATE('".$selected_month."') >= DATE(period_start_date)) AND period_end_date is NULL");
        if($nationality_period->num_rows() < 1)
        {
            $nationality_period = $this->db->query("SELECT * FROM payroll_cpf_nationality_period WHERE (DATE('".$selected_month."') between DATE(period_start_date) AND DATE(period_end_date))");
            if($nationality_period->num_rows())
            {
                $nationality_period = $nationality_period-> result_array();
                $nationality_period = $nationality_period[0];
            }
        }
        else
        {
            $nationality_period = $nationality_period-> result_array();
            $nationality_period = $nationality_period[0];
        }

        return $nationality_period;
    }

    public function get_selected_month_age_group_period($selected_month)
    {
        $age_group_period = $this->db->query("SELECT * FROM payroll_cpf_age_group_period WHERE (DATE('".$selected_month."') >= DATE(period_start_date)) AND period_end_date is NULL");
        if($age_group_period->num_rows() < 1)
        {
            $age_group_period = $this->db->query("SELECT * FROM payroll_cpf_age_group_period WHERE (DATE('".$selected_month."') between DATE(period_start_date) AND DATE(period_end_date))");
            if($age_group_period->num_rows())
            {
                $age_group_period = $age_group_period->result_array();
                $age_group_period = $age_group_period[0];
            }
        }
        else
        {
            $age_group_period = $age_group_period->result_array();
            $age_group_period = $age_group_period[0];
        }

        return $age_group_period;
    }

    public function get_selected_month_salary_cap($selected_month)
    {
        $salary_cap = $this->db->query("SELECT * FROM payroll_cpf_salary_cap WHERE (DATE('".$selected_month."') >= DATE(cap_start_date)) AND cap_end_date is NULL");
        if($salary_cap->num_rows() < 1)
        {
            $salary_cap = $this->db->query("SELECT * FROM payroll_cpf_salary_cap WHERE (DATE('".$selected_month."') between DATE(cap_start_date) AND DATE(cap_end_date))");
            if($salary_cap->num_rows())
            {
                $salary_cap = $salary_cap-> result_array();
                $salary_cap = $salary_cap[0];
            }
        }
        else
        {
            $salary_cap = $salary_cap-> result_array();
            $salary_cap = $salary_cap[0];
        }

        return $salary_cap;
    }

    public function update_payslip($payslip_bundle)
    {
        foreach($payslip_bundle as $item)
        {
            $q = $this->db->where('id', $item['id']);
            $result = $q->update('payroll_payslip', $item);
        }

        return $result;
    }

    public function get_payslip_data($current_month){ 

        $result = $this->db->query("SELECT payroll_employee.*,payroll_payslip.*,nationality.*,firm.name AS firmName,payroll_offices.office_name,department.department_name FROM payroll_payslip
            LEFT JOIN payroll_employee ON payroll_payslip.employee_id = payroll_employee.id
            LEFT JOIN nationality ON nationality.id = payroll_employee.nationality_id
            LEFT JOIN firm ON firm.id = payroll_employee.firm_id
            LEFT JOIN payroll_offices ON payroll_offices.id = payroll_employee.office
            LEFT JOIN department ON department.id = payroll_employee.department
            WHERE payroll_payslip.payslip_for = '".$current_month."'
        ");

        $result = $result->result_array();
        return $result;
    }
}
?>