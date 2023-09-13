<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
        $this->load->library(array('encryption'));

    }

    public function get_employee_id_from_user_id($user_id){
        $q = $this->db->query("SELECT ue.employee_id FROM payroll_user_employee ue WHERE ue.user_id ='". $user_id ."'");

        if ($q->num_rows() > 0) {
            return $q->result()[0]->employee_id;
        }else{
            return FALSE;
        }
    }

    public function get_employeeList($user_id = NULL,$is_manager = NULL){

        if($user_id == NULL) // ADMIN
        {
            $q = $this->db->query("SELECT e.*, ue.user_id AS `user_id`, u.email AS `user_email`,payroll_offices.office_name , department.department_name,payroll_employee_telephone.telephone FROM payroll_employee e LEFT JOIN payroll_user_employee ue ON ue.employee_id = e.id LEFT JOIN users u ON ue.user_id = u.id LEFT JOIN department ON department.id = e.department LEFT JOIN payroll_offices ON payroll_offices.id = e.office LEFT JOIN payroll_employee_telephone ON payroll_employee_telephone.employee_id = e.id AND payroll_employee_telephone.primary_telephone = 1 WHERE e.employee_status_id IN (1,2) OR (e.employee_status_id IN (3,4) AND DATE(date_cessation) >= DATE(CURRENT_DATE)) GROUP BY e.id ORDER BY e.name");
        }
        else if($user_id != NULL && $is_manager == 'true') // MANAGER
        {
            $q = $this->db->query("SELECT e.*, ue.user_id AS `user_id`, u.email AS `user_email`,payroll_offices.office_name, department.department_name ,payroll_employee_telephone.telephone FROM payroll_employee e LEFT JOIN payroll_user_employee ue ON ue.employee_id = e.id LEFT JOIN users u ON ue.user_id = u.id LEFT JOIN department ON department.id = e.department LEFT JOIN payroll_offices ON payroll_offices.id = e.office LEFT JOIN payroll_employee_telephone ON payroll_employee_telephone.employee_id = e.id AND payroll_employee_telephone.primary_telephone = 1 WHERE (e.employee_status_id IN (1,2) AND (ue.user_id ='". $user_id ."' or u.manager_in_charge ='". $user_id ."')) OR (e.employee_status_id IN (3,4) AND DATE(date_cessation) >= DATE(CURRENT_DATE) AND (ue.user_id ='". $user_id ."' or u.manager_in_charge ='". $user_id ."')) GROUP BY e.id ORDER BY e.name");

        }
        else  // NORMAL USER
        {
            $q = $this->db->query("SELECT e.*, ue.user_id AS `user_id`, u.email AS `user_email`,payroll_offices.office_name, department.department_name ,payroll_employee_telephone.telephone FROM payroll_employee e LEFT JOIN payroll_user_employee ue ON ue.employee_id = e.id LEFT JOIN users u ON ue.user_id = u.id LEFT JOIN department ON department.id = e.department LEFT JOIN payroll_offices ON payroll_offices.id = e.office LEFT JOIN payroll_employee_telephone ON payroll_employee_telephone.employee_id = e.id AND payroll_employee_telephone.primary_telephone = 1 WHERE ue.user_id ='". $user_id ."' GROUP BY e.id ORDER BY e.name");
        }

        return $q->result();
    }

    public function get_past_employeeList($user_id = NULL,$is_manager = NULL){

        if($user_id == NULL) // ADMIN
        {
            $q = $this->db->query("SELECT e.*, ue.user_id AS `user_id`, u.email AS `user_email`,payroll_offices.office_name , department.department_name,payroll_employee_telephone.telephone FROM payroll_employee e LEFT JOIN payroll_user_employee ue ON ue.employee_id = e.id LEFT JOIN users u ON ue.user_id = u.id LEFT JOIN department ON department.id = e.department LEFT JOIN payroll_offices ON payroll_offices.id = e.office LEFT JOIN payroll_employee_telephone ON payroll_employee_telephone.employee_id = e.id AND payroll_employee_telephone.primary_telephone = 1 WHERE e.employee_status_id IN (3,4) AND DATE(date_cessation) < DATE(CURRENT_DATE) GROUP BY e.id ORDER BY e.name");
        }
        else if($user_id != NULL && $is_manager == 'true') // MANAGER
        {
            $q = $this->db->query("SELECT e.*, ue.user_id AS `user_id`, u.email AS `user_email`,payroll_offices.office_name, department.department_name ,payroll_employee_telephone.telephone FROM payroll_employee e LEFT JOIN payroll_user_employee ue ON ue.employee_id = e.id LEFT JOIN users u ON ue.user_id = u.id LEFT JOIN department ON department.id = e.department LEFT JOIN payroll_offices ON payroll_offices.id = e.office LEFT JOIN payroll_employee_telephone ON payroll_employee_telephone.employee_id = e.id AND payroll_employee_telephone.primary_telephone = 1 WHERE e.employee_status_id IN (3,4) AND DATE(date_cessation) < DATE(CURRENT_DATE) AND u.manager_in_charge ='". $user_id ."'  GROUP BY e.id ORDER BY e.name");
        }

        return $q->result();
    }

    public function get_employeeStatusList()
    {
        $list = $this->db->query("SELECT * FROM payroll_employee_status");

        $employee_status_list = array();
        $employee_status_list[''] = 'Select a Employee Status';

        foreach($list->result()as $item){
            $employee_status_list[$item->id] = $item->employee_status; 
        }

        return $employee_status_list;
    }

    public function get_employeeDepartment()
    {
        $list = $this->db->query("SELECT * FROM department ORDER BY list_order ASC");

        $employee_department_list = array();
        $employee_department_list[''] = 'Select a Employee Department';

        foreach($list->result()as $item){
            $employee_department_list[$item->id] = $item->department_name; 
        }

        return $employee_department_list;
    }

    public function get_employeeOffice()
    {
        $list = $this->db->query("SELECT * FROM payroll_offices");

        $employee_office_list = array();
        $employee_office_list[''] = 'Select a Employee Office';

        foreach($list->result()as $item){
            $employee_office_list[$item->id] = $item->office_name; 
        }

        return $employee_office_list;
    }

    public function get_employeeList_dropdown($selected_month, $user_id=NULL, $is_manager=NULL){
        // $q = $this->db->query("SELECT * FROM payroll_employee");

        if($user_id == NULL) // ADMIN
        {
            $employee_list = $this->db->query("SELECT e.* FROM payroll_employee e WHERE e.employee_status_id IN (1,2) OR (e.employee_status_id IN (3,4) AND DATE(date_cessation) >= DATE(".$selected_month.")) GROUP BY e.id ORDER BY e.name");
        }
        else if($user_id != NULL && $is_manager == 'true') // MANAGER
        {
            $employee_list = $this->db->query("SELECT e.*, ue.user_id AS `user_id` FROM payroll_employee e LEFT JOIN payroll_user_employee ue ON ue.employee_id = e.id LEFT JOIN users u ON ue.user_id = u.id  WHERE (e.employee_status_id IN (1,2) AND (ue.user_id ='". $user_id ."' or u.manager_in_charge ='". $user_id ."')) OR (e.employee_status_id IN (3,4) AND DATE(date_cessation) >=  DATE(".$selected_month.") AND (ue.user_id ='". $user_id ."' or u.manager_in_charge ='". $user_id ."')) GROUP BY e.id ORDER BY e.name");

        }

        $employee_names     = array();

        foreach($employee_list->result() as $employee){
            $employee_names[$employee->id] = $employee->name; 
        }

        return $employee_names;
    }

    public function get_currency_dropdown(){
        $q = $this->db->query("SELECT * FROM currency ORDER BY id");

        $currencies     = array();
        $currencies[''] = "Select Currency";
        foreach($q->result() as $currency){
            $currencies[$currency->id] = $currency->currency; 
        }
        
        
        return $currencies;
    }

    public function get_staff_info($staff_id){
        // $q = $this->db->query(" SELECT payroll_employee.*, GROUP_CONCAT(DISTINCT CONCAT(payroll_employee_telephone.id,',', payroll_employee_telephone.telephone, ',', payroll_employee_telephone.primary_telephone)SEPARATOR ';') AS 'employee_telephone' FROM payroll_employee LEFT JOIN payroll_employee_telephone ON payroll_employee_telephone.employee_id = payroll_employee.id WHERE payroll_employee.id = '".$staff_id."' ORDER BY payroll_employee_telephone.primary_telephone DESC ");
        
         $q = $this->db->query(" SELECT payroll_employee.*, GROUP_CONCAT(DISTINCT CONCAT(payroll_employee_telephone.id,',', payroll_employee_telephone.telephone, ',', payroll_employee_telephone.primary_telephone)SEPARATOR ';') AS 'employee_telephone', payroll_offices.office_country FROM payroll_employee LEFT JOIN payroll_employee_telephone ON payroll_employee_telephone.employee_id = payroll_employee.id LEFT JOIN payroll_offices ON payroll_offices.id = payroll_employee.office WHERE payroll_employee.id = '".$staff_id."' ORDER BY payroll_employee_telephone.primary_telephone DESC ");

        if($q->result()[0]->employee_telephone != null)
        {
            $q->result()[0]->employee_telephone = explode(';', $q->result()[0]->employee_telephone);
        }

        

        if ($q->num_rows() > 0) {
            return $q->result();
        }else{
            return FALSE;
        }
    }

    public function get_family_info($staff_id){
        $q = $this->db->query("SELECT payroll_family_info.*, payroll_user_employee.user_id FROM payroll_family_info LEFT JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_family_info.employee_id WHERE payroll_family_info.employee_id='".$staff_id."' AND payroll_family_info.deleted=0");

        if ($q->num_rows() > 0) {
            return $q->result_array();
        }else{
            return FALSE;
        }
    }

    public function get_salary_info($staff_id, $selected_month = NULL){
        
        if($selected_month)
        {
            $q = $this->db->query("SELECT *, currency.id as currency_id, payroll_employee_salary.id as salary_info_id FROM payroll_employee_salary LEFT JOIN currency ON currency.id = payroll_employee_salary.currency WHERE employee_id='".$staff_id."' AND DATE('".$selected_month."') >= DATE(effective_start_date) AND last_drawn_date is NULL AND deleted=0 ORDER BY payroll_employee_salary.effective_start_date DESC");

            if(!$q->num_rows())
            {
                $q = $this->db->query("SELECT *, currency.id as currency_id, payroll_employee_salary.id as salary_info_id FROM payroll_employee_salary LEFT JOIN currency ON currency.id = payroll_employee_salary.currency WHERE employee_id='".$staff_id."' AND (DATE('".$selected_month."') between DATE(effective_start_date) AND DATE(last_drawn_date)) AND deleted=0 ORDER BY payroll_employee_salary.effective_start_date DESC");

            }

        }
        else
        {
            $q = $this->db->query("SELECT *, currency.id as currency_id, payroll_employee_salary.id as salary_info_id FROM payroll_employee_salary LEFT JOIN currency ON currency.id = payroll_employee_salary.currency WHERE employee_id='".$staff_id."' AND deleted=0 ORDER BY payroll_employee_salary.effective_start_date DESC");

        }

        $q = $q->result();

        for ($i = 0; $i < count($q); $i++) {
            if($q[$i]->salary != null)
            {
                $q[$i]->salary = $this->encryption->decrypt($q[$i]->salary);
            } 
        }
        
        if (count($q) > 0) {
            return $q;
        }else{
            return FALSE;
        }
    }

    public function get_bond_info($staff_id, $selected_month = NULL){

        if($selected_month)
        {
            $q = $this->db->query("SELECT *, currency.id as currency_id, payroll_employee_bond.id as bond_info_id FROM payroll_employee_bond LEFT JOIN currency ON currency.id = payroll_employee_bond.currency WHERE employee_id='".$staff_id."' AND (DATE('".$selected_month."') between DATE(bond_start_date) AND DATE(bond_end_date)) AND deleted = 0 ORDER BY payroll_employee_bond.bond_start_date DESC");

        }
        else
        {
            $q = $this->db->query("SELECT *, currency.id as currency_id, payroll_employee_bond.id as bond_info_id FROM payroll_employee_bond LEFT JOIN currency ON currency.id = payroll_employee_bond.currency WHERE employee_id='".$staff_id."' and deleted = 0 ORDER BY payroll_employee_bond.bond_start_date DESC");

        }

    
        // $q = $this->db->query("SELECT * FROM payroll_employee_bond WHERE employee_id='".$staff_id."' AND (DATE('".$selected_month."') between DATE(bond_start_date) AND DATE(bond_end_date)) ORDER BY payroll_employee_bond.bond_start_date DESC");
       

        $q = $q->result();

        for ($i = 0; $i < count($q); $i++) {
            if($q[$i]->bond_allowance != null)
            {
                $q[$i]->bond_allowance = $this->encryption->decrypt($q[$i]->bond_allowance);
            } 
        }
        

        if (count($q) > 0) {
            return $q;
        }else{
            return FALSE;
        }
    }

    public function get_active_type_of_leave($staff_id){
        $q = $this->db->query("SELECT payroll_employee_type_of_leave.* FROM payroll_employee_type_of_leave LEFT JOIN payroll_type_of_leave ON payroll_type_of_leave.id = payroll_employee_type_of_leave.type_of_leave_id WHERE employee_id='".$staff_id."' ORDER BY payroll_type_of_leave.leave_name");

        if ($q->num_rows() > 0) {
            return $q->result();
        }else{
            return FALSE;
        }
    }

    public function create_employee($data, $annual_leave, $annual_leave_days, $previous_staff_status, $staff_status, $previous_status_date, $user_id,$telephone){
        $form_data = $this->input->post();
        $q = $this->db->get_where('payroll_employee', array('id' => $data['id']));    // check if customer existed before.
        // $q = $this->db->where('id', $data['id']);
        $q4 = $this->db->query("SELECT * FROM payroll_leave_cycle");
        $q4 = $q4->result_array();

        if(!$q->num_rows())
        {
            $this->db->insert('payroll_employee', $data);    // insert new customer to database
            $employee_id = $this->db->insert_id(); 

            $user_employee = array(
            'employee_id'=> $employee_id,
            'user_id'    => $user_id
            );// JW

            $this->db->insert('payroll_user_employee', $user_employee);//jw

            for($i = 0; $i < count($telephone); $i++)
            {
                $telephone[$i]['employee_id'] = $employee_id;
                $this->db->insert('payroll_employee_telephone', $telephone[$i]);
            }


            for($r = 0; $r < count($annual_leave); $r++)
            {
                $employee_type_of_leave["employee_id"] = $employee_id;
                $employee_type_of_leave["type_of_leave_id"] = $annual_leave[$r];
                $employee_type_of_leave["days"] = $annual_leave_days[$r];

                $this->db->insert('payroll_employee_type_of_leave', $employee_type_of_leave);


                // CODE WITHOUT USE
                // if($previous_staff_status == 1 && $previous_status_date == null)
                // {
                //     $q5 = $this->db->query("SELECT * FROM payroll_employee_type_of_leave WHERE type_of_leave_id = ".$annual_leave[$r]." AND employee_id = ".$q->result()[0]->id);
                //     $q5 = $q5->result_array();
                //     $date1 = new DateTime($data['date_joined']);
                //     $date2 = new DateTime(date("Y").'-'.$q4[0]["leave_cycle_date_to"]);
                //     $interval = $date1->diff($date2);
                //     $years = $interval->y;
                //     $months = $interval->m;
                //     $days = $interval->d;
                //     $balance_for_annual_leave_days = $q5[0]['days'] * ($months/12);
                //     $total_annual_leave = floor($balance_for_annual_leave_days * 2) / 2;
                //     $final_data = array(
                //         'employee_id' => $employee_id,
                //         'type_of_leave_id' => $annual_leave[$r],
                //         'annual_leave_days' => $total_annual_leave
                //     );
                //     $this->db->insert('payroll_employee_annual_leave', $final_data);
                // }
                // CODE WITHOUT USE
            }

            $q2 = $this->db->query(" SELECT * FROM users WHERE users.id = '".$user_id."' ");
            $q2_result = $q2->result();

            // $this->load->library('parser');
            // $parse_data = array('employee_name'  => $data['name']);
            // $msg = file_get_contents('./application/modules/employee/email_templates/declaration_email_notification.html');
            // $message = $this->parser->parse_string($msg, $parse_data,true);
            // $subject = 'New Declaration Notification';
            // $this->sma->send_email($q2_result[0]->email, $subject, $message,"" ,"" ,"" ,"");
            
            // SENDINBLUE EMAIL
            $this->load->library('parser');
            $parse_data = array('employee_name'  => $data['name']);
            $msg        = file_get_contents('./application/modules/employee/email_templates/declaration_email_notification.html');
            $subject    = 'New Declaration Notification';
            $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
            $to_email   = json_encode(array(array("email"=> $q2_result[0]->email)));
            $cc         = null;
            $message    = $this->parser->parse_string($msg, $parse_data,true);
            $this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null);

            return array("status" => "created", "employee_id" => $employee_id);
        }
        else
        {
            $this->db->where('id', $q->result()[0]->id);
            $this->db->update('payroll_employee', $data);
            $this->db->delete('payroll_employee_type_of_leave', array('employee_id' => $q->result()[0]->id));

            for($r = 0; $r < count($annual_leave); $r++)
            {
                $employee_type_of_leave["employee_id"] = $q->result()[0]->id;
                $employee_type_of_leave["type_of_leave_id"] = $annual_leave[$r];
                $employee_type_of_leave["days"] = $annual_leave_days[$r];

                $this->db->insert('payroll_employee_type_of_leave', $employee_type_of_leave);
            }


            $this->db->delete("payroll_employee_telephone",array('employee_id'=>$telephone[0]['employee_id']));

            for($i = 0; $i < count($telephone); $i++)
            {
                $this->db->insert('payroll_employee_telephone', $telephone[$i]);
            }


            if($previous_staff_status == 1 && $previous_status_date == null)
            {
                if($staff_status != 1 && $staff_status == 2)
                {
                    $q6 = $this->db->query("SELECT * FROM payroll_employee_type_of_leave WHERE employee_id = ".$q->result()[0]->id);
                
                    if($q6->num_rows())
                    {
                        $q6 = $q6->result_array();

                        for($t = 0; $t < count($q6); $t++)
                        {
                            $q5 = $this->db->query("SELECT * FROM payroll_employee_type_of_leave WHERE type_of_leave_id = ".$q6[$t]['type_of_leave_id']." AND employee_id = ".$q->result()[0]->id);

                            $annual_leave_result = $q5->result_array();

                            $annual_leave_result_day = $annual_leave_result[0]['days'];

                            $date1 = $data['date_joined'];
                            $date2 = date("Y").'-'.$q4[0]["leave_cycle_date_to"];

                            // $interval = abs(strtotime($date2) - strtotime($date1));
                            // $years = floor($interval / (365*60*60*24));
                            // $months = floor(($interval - $years * 365*60*60*24) / (30*60*60*24));
                            $ts1 = strtotime($date1);
                            $ts2 = strtotime($date2);
                            $year1 = date('Y', $ts1);
                            $year2 = date('Y', $ts2);
                            $month1 = date('m', $ts1);
                            $month2 = date('m', $ts2);
                            $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
                            $months = $diff;


                            if(date('Y', strtotime($data['status_date'])) != date('Y', strtotime($data['date_joined'])))
                            {
                                if($q6[$t]['type_of_leave_id'] == '1')
                                {
                                    // $balance_for_annual_leave_days = ($annual_leave_result_day * ($months/12))+$annual_leave_result_day;
                                    $balance_for_annual_leave_days = ($annual_leave_result_day * ($months/12));
                                }
                                else
                                {
                                    $balance_for_annual_leave_days = $annual_leave_result_day;
                                }
                            }
                            else
                            {
                                $balance_for_annual_leave_days = $annual_leave_result_day * ($months/12);
                            }

                            $q7 = $this->db->query("SELECT * FROM payroll_employee_annual_leave WHERE employee_id='". $q->result()[0]->id ."' AND type_of_leave_id = '". $q6[$t]['type_of_leave_id'] ."' AND year(last_updated) = YEAR(CURDATE()) AND last_updated = (SELECT MAX(last_updated) FROM `payroll_employee_annual_leave` WHERE employee_id = ". $q->result()[0]->id ." AND type_of_leave_id = ".$q6[$t]['type_of_leave_id'].")");

                            if(!$q7->num_rows())
                            {
                                // $total_annual_leave = floor($balance_for_annual_leave_days * 2) / 2;
                                $total_annual_leave = round($balance_for_annual_leave_days);
                            }
                            else
                            {
                                $q7_query = $q7->result_array();
                                $q7_query = $q7_query[0]['annual_leave_days'];
                                // $total_annual_leave = (floor($balance_for_annual_leave_days * 2) / 2) + $q7_query;
                                $total_annual_leave = round($balance_for_annual_leave_days) + $q7_query;
                            }

                            $final_data = array(
                                'employee_id' => $q->result()[0]->id,
                                'type_of_leave_id' => $q6[$t]['type_of_leave_id'],
                                'annual_leave_days' => $total_annual_leave
                            );
                            
                            $leave = $q7->result_array();

                            // Checking for [CheckProbationWithLeave]
                            // if($leave[0]['annual_leave_days'] <= 0){
                                $this->db->insert('payroll_employee_annual_leave', $final_data);
                            // }
                        }
                    }
                }
            }

            // return $q->result()[0]->id; // retrieve id

            $PassValitation = $this->db->query(" SELECT CONCAT(first_name , ' ' , last_name) AS fullname , payroll_employee.workpass AS pass , payroll_employee.pass_expire AS expiry_date, datediff(payroll_employee.pass_expire,CURDATE()) AS remaining_days, users.email, users.manager_in_charge
            FROM payroll_employee 
            LEFT JOIN payroll_user_employee ON payroll_employee.id = payroll_user_employee.employee_id 
            LEFT JOIN users ON payroll_user_employee.user_id = users.id 
            WHERE DATE_ADD(CURDATE(), INTERVAL 1 month) >= payroll_employee.pass_expire 
            AND (payroll_employee.employee_status_id IN (1,2) OR (payroll_employee.employee_status_id IN (3,4) AND DATE(payroll_employee.date_cessation) >= DATE(CURRENT_DATE)))
            AND payroll_employee.id ='".$data['id']."' ");

            if($PassValitation->num_rows() > 0) 
            {
                $this->session->set_userdata('work_pass_expired_denial', true);
            }
            else
            {
                $this->session->set_userdata('work_pass_expired_denial', false);
            }
                
            return array("status" => "updated");
        }

        return array("status" => "failed");
    }

    public function create_new_employee($data){
        $result = $this->db->insert('payroll_employee', $data);    // insert new customer to database
        $employee_id = $this->db->insert_id();

        $return_result = array(
            'result'      => 1,
            'employee_id' => $employee_id
        );
        
        return $return_result;
    }

    public function get_event_info($staff_id){
        $q = $this->db->query("SELECT payroll_event_info.*, payroll_user_employee.user_id FROM payroll_event_info LEFT JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_event_info.employee_id WHERE payroll_event_info.employee_id='".$staff_id."' AND payroll_event_info.deleted=0");

        if ($q->num_rows() > 0) {
            return $q->result_array();
        }else{
            return FALSE;
        }
    }

    public function getEventType(){
        $query = $this->db->query("SELECT * FROM payroll_event_type");

        foreach($query->result() as $item){
            $event_list[$item->id] = $item->event; 
        }

        return $event_list;
    }

    public function get_staff_telephone($staff_id){
        $q = $this->db->query("SELECT * FROM payroll_employee_telephone WHERE employee_id='".$staff_id."'");

        if ($q->num_rows() > 0) {
            return $q->result();
        }else{
            return FALSE;
        }
    }

    // public function get_designation($department){

    //     $list = $this->db->query(' SELECT * FROM payroll_designation WHERE department_id LIKE "'.$department.'" GROUP BY designation ORDER BY sorting ');
    //     return $list->result();

    // }//JW

    public function submit_resignation($data){


        $q = $this->db->query("SELECT * FROM payroll_employee_resignation WHERE employee_id = '".$data['employee_id']."'");

        if ($q->num_rows() > 0) 
        {
            $this->db->where('id', $q->result()[0]->id);
            $result = $this->db->update('payroll_employee_resignation', $data);
        }
        else
        {
            $result = $this->db->insert('payroll_employee_resignation', $data);
        }

        return $result;
    }

    public function get_type_of_leave_list(){

        $list = $this->db->query('SELECT payroll_type_of_leave.*, payroll_choose_carry_forward.choose_carry_forward_name FROM payroll_type_of_leave LEFT JOIN payroll_choose_carry_forward ON payroll_choose_carry_forward.id = payroll_type_of_leave.choose_carry_forward_id WHERE deleted = 0 AND payroll_type_of_leave.id IN (1,2,3) ORDER BY leave_name');

        return $list->result();
    }

    public function get_other_type_of_leave_list(){

        $list = $this->db->query('SELECT payroll_type_of_leave.*, payroll_choose_carry_forward.choose_carry_forward_name FROM payroll_type_of_leave LEFT JOIN payroll_choose_carry_forward ON payroll_choose_carry_forward.id = payroll_type_of_leave.choose_carry_forward_id WHERE deleted = 0 AND payroll_type_of_leave.id NOT IN (1,2,3) ORDER BY leave_name');

        return $list->result();
    }

    public function resignation_email_notification($data){

        $q = $this->db->query("SELECT payroll_employee.*, users.email FROM payroll_employee LEFT JOIN payroll_user_employee ON payroll_employee.id = payroll_user_employee.employee_id LEFT JOIN users ON users.id = payroll_user_employee.user_id WHERE payroll_employee.id = '".$data['employee_id']."'");

        $query_result = $q->result_array();

        if($query_result[0]['gender'])
        {
            $His_Her = 'his';
        }
        else
        {
            $His_Her = 'her';
        }

        $path = json_decode($data['resignation_letter'])->file_name;

        $this->load->library('parser');
        $parse_data = array(
            'employee_name' => $query_result[0]['name'],
            'his_her'       => $His_Her
        );
        // htmlentities(file_get_contents("localfile.html"));
        $msg = file_get_contents('./application/modules/employee/email_templates/resignation_email_notification.html');
        $message = $this->parser->parse_string($msg, $parse_data, TRUE);

        // $email_detail['email'] = array(array("email"=> "woellywilliam@aaa-global.com"));
        $email_detail['email'] = array(array("email"=> "woellywilliam@aaa-global.com"), array("email"=> "penny@aaa-global.com"));
        $email_detail['from_email'] = array("name" => 'HRM System', "email" => "admin@bizfiles.com.sg");
        $email_detail['cc'] = array(array("email" => $query_result[0]['email']));

        $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');

          $apiInstance = new SendinBlue\Client\Api\SMTPApi(
              new GuzzleHttp\Client(),
              $config
          );

          $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail(); // \SendinBlue\Client\Model\SendSmtpEmail | Values to send a transactional email
          $sendSmtpEmail['subject'] = 'Employee Resignation Notification';
          $sender_email = $email_detail['from_email'];
          $sendSmtpEmail['sender'] = $sender_email;
          $sendSmtpEmail['to'] = $email_detail['email'];
          $sendSmtpEmail['cc'] = $email_detail['cc'];
          $sendSmtpEmail['htmlContent'] = $message;

          $file['content'] = base64_encode(file_get_contents($_SERVER["DOCUMENT_ROOT"] .'hrm/uploads/resignation_letter/'.$path.''));
          $file['name'] = $path;
          $attachment = array();
          array_push($attachment, $file);
          $sendSmtpEmail['attachment'] = $attachment;
          
          try {
              $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
              if ($result) 
              {
                  // $email_queue['sended'] = 1;
                  $email_queue['sendInBlueResult'] = $result;
                  // $this->db->update("email_queue",$email_queue,array("id" => $email_queue_info[$i]['id']));
                  // echo 'Your Email has successfully been sent.';
              }
          } catch (Exception $e) {
              // echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
          }
    }

    public function resignation_date_approvals_email_notification($data){

        $q = $this->db->query("SELECT payroll_employee.*, users.email, payroll_employee_resignation.last_day FROM payroll_employee LEFT JOIN payroll_user_employee ON payroll_employee.id = payroll_user_employee.employee_id LEFT JOIN users ON users.id = payroll_user_employee.user_id LEFT JOIN payroll_employee_resignation ON payroll_employee.id = payroll_employee_resignation.employee_id WHERE payroll_employee.id = '".$data['employee_id']."'");

        $query_result = $q->result_array();

        if($data['status'])
        {
            $status = 'Approved';
        }
        else
        {
            $status = 'Rejected. Last working date change to '.$query_result[0]['last_day'].'';
        }

        $this->load->library('parser');
        $parse_data = array(
            'employee_name' => $query_result[0]['name'],
            'status'        => $status
        );
        // htmlentities(file_get_contents("localfile.html"));
        $msg = file_get_contents('./application/modules/employee/email_templates/resignation_date_approvals_email_notification.html');
        $message = $this->parser->parse_string($msg, $parse_data, TRUE);

        $email_detail['email'] = array(array("email"=>  $query_result[0]['email']));
        $email_detail['from_email'] = array("name" => 'HRM System', "email" => "admin@bizfiles.com.sg");
        // $email_detail['cc'] = array(array("email" => $query_result[0]['email']));

        $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');

          $apiInstance = new SendinBlue\Client\Api\SMTPApi(
              new GuzzleHttp\Client(),
              $config
          );

          $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail(); // \SendinBlue\Client\Model\SendSmtpEmail | Values to send a transactional email
          $sendSmtpEmail['subject'] = 'Resignation Date Approvals Notification';
          $sender_email = $email_detail['from_email'];
          $sendSmtpEmail['sender'] = $sender_email;
          $sendSmtpEmail['to'] = $email_detail['email'];
          // $sendSmtpEmail['cc'] = $email_detail['cc'];
          $sendSmtpEmail['htmlContent'] = $message;

          // $file['content'] = base64_encode(file_get_contents($_SERVER["DOCUMENT_ROOT"] .'hrm/uploads/resignation_letter/'.$path.''));
          // $file['name'] = $path;
          // $attachment = array();
          // array_push($attachment, $file);
          // $sendSmtpEmail['attachment'] = $attachment;
          
          try {
              $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
              if ($result) 
              {
                  // $email_queue['sended'] = 1;
                  $email_queue['sendInBlueResult'] = $result;
                  // $this->db->update("email_queue",$email_queue,array("id" => $email_queue_info[$i]['id']));
                  // echo 'Your Email has successfully been sent.';
              }
          } catch (Exception $e) {
              // echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
          }
    }
}
?>