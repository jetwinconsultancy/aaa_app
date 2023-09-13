<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
include 'application/js/random_alphanumeric_generator.php';

class Timesheet_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function get_all_timesheet(){
        $q = $this->db->query("SELECT t.*, e.name AS `employee_name` FROM timesheet t LEFT JOIN payroll_employee e ON e.id = t.employee_id WHERE year(t.month)= year(CURRENT_TIMESTAMP) ORDER BY month(t.month) DESC");

        return $q->result();
    }

    public function get_timesheet($timesheet_id){
        $q = $this->db->query("SELECT t.*, e.name AS `employee_name` FROM timesheet t LEFT JOIN payroll_employee e ON e.id = t.employee_id WHERE t.id=". $timesheet_id);

        return $q->result();
    }

    public function get_employee_timesheet($employee_id){
        $q = $this->db->query("SELECT t.*, e.name AS `employee_name` FROM timesheet t LEFT JOIN payroll_employee e ON e.id = t.employee_id WHERE t.employee_id=". $employee_id ." AND year(t.month) = year(CURRENT_DATE) ORDER BY month(t.month) DESC");

        return $q->result();
    }

    public function get_employee_timesheet2($employee_id){
        $q = $this->db->query("SELECT t.*, e.name AS `employee_name` FROM timesheet t LEFT JOIN payroll_employee e ON e.id = t.employee_id WHERE t.employee_id=". $employee_id ." ORDER BY month(t.month) DESC");

        return $q->result();
    }

    public function create_timesheet($data){
        $data['timesheet_no'] = random_code(8);

        $q = $this->db->query("SELECT * FROM timesheet WHERE employee_id ='".$data['employee_id']."' AND month ='".$data['month']."'");

        if ($q->num_rows() > 0)
        {
            return false;
        }
        else
        {
            $result = $this->db->insert('timesheet', $data);  // insert new record to database
            return true;
        }

    }

    public function get_years(){
        $q = $this->db->query("SELECT YEAR(month) AS `year` FROM timesheet GROUP BY YEAR(month) ORDER BY YEAR(month) DESC");

        $years = array();

        foreach($q->result() as $row){
            $years[$row->year] = $row->year; 
        }

        return $years;
    }

    public function get_month(){
        $q = $this->db->query("SELECT Month(month) AS `month` FROM timesheet GROUP BY Month(month) ORDER BY Month(month) DESC");

        $month = array();

        foreach($q->result() as $row){
            
            $month[$row->month] = date("F", mktime(0, 0, 0,$row->month, 10));
        }

        return $month;
    }

    public function get_status(){
        $q = $this->db->query("SELECT status_id FROM timesheet GROUP BY status_id ORDER BY status_id");

        $status['0'] = 'All Status';

        foreach($q->result() as $row){
            $status[$row->status_id] = $this->employment_json_model->get_timesheet_action_name($row->status_id);
        }

        return $status;
    }

    public function get_employee(){
        $q = $this->db->query("SELECT e.id AS id, e.name AS `employee_name` FROM timesheet t INNER JOIN payroll_employee e ON e.id = t.employee_id GROUP BY employee_name ORDER BY employee_name ASC");

        foreach($q->result() as $row){
            $employee[$row->id] = $row->employee_name;
        }

        return $employee;
    }

    public function get_months_from_this_year($year){
        $q = $this->db->query("SELECT MONTH(month) AS `month` FROM timesheet WHERE YEAR(month) = ". $year ." GROUP BY MONTH(month)");

        return $q->result();
    }

    public function get_list_from_year_month($year, $month){
        $q = $this->db->query("SELECT t.*,  e.name AS `employee_name` FROM timesheet t LEFT JOIN payroll_employee e ON e.id = t.employee_id  WHERE YEAR(t.month) = ". $year ." AND MONTH(t.month) = ". $month);

        return $q->result();
    }

    public function edit_timesheet($data, $timesheet_id){
        $this->db->where('id', $timesheet_id);
        $result = $this->db->update('timesheet', $data);

        return $result;
    }

    public function get_assignment($timesheet_id){
        $q = $this->db->query("SELECT t.*, e.name AS `employee_name` FROM timesheet t LEFT JOIN payroll_employee e ON e.id = t.employee_id WHERE t.id=". $timesheet_id);

        $result = $q->result();
        $employee_id = $result[0]->employee_id;
        $timesheet_month = $result[0]->month;
        $timesheet_month_end = date("Y-m-t", strtotime($timesheet_month));

        $q2 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users LEFT JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id WHERE payroll_user_employee.employee_id = '".$employee_id."'");

        $result2 = $q2->result();
        $emp_name = json_encode($result2[0]->name);

        // $q3 = $this->db->query(" SELECT t1.*, payroll_assignment_jobs.type_of_job AS job FROM (SELECT * FROM payroll_assignment WHERE MONTH('".$timesheet_month."') = MONTH(payroll_assignment.create_on) AND ((((payroll_assignment.status NOT IN (10) AND payroll_assignment.type_of_job NOT IN(13)) OR (payroll_assignment.status NOT IN (15,17) AND payroll_assignment.type_of_job IN(13))) AND payroll_assignment.complete_date IS NULL) OR (((payroll_assignment.status IN (10) AND payroll_assignment.type_of_job NOT IN(13)) OR (payroll_assignment.status IN (15,17) AND payroll_assignment.type_of_job IN(13))) AND payroll_assignment.complete_date != 'NULL')) AND payroll_assignment.PIC LIKE '%".$emp_name."%' AND payroll_assignment.deleted = 0 UNION ALL SELECT * FROM payroll_assignment WHERE ((MONTH('".$timesheet_month."') > MONTH(payroll_assignment.create_on) AND (((payroll_assignment.status NOT IN (10) AND payroll_assignment.type_of_job NOT IN(13)) OR (payroll_assignment.status NOT IN (15,17) AND payroll_assignment.type_of_job IN(13))) AND payroll_assignment.complete_date IS NULL)) OR (((MONTH('".$timesheet_month."') = MONTH(payroll_assignment.complete_date)) OR (MONTH('".$timesheet_month."') > MONTH(payroll_assignment.create_on) AND MONTH('".$timesheet_month."') < MONTH(payroll_assignment.complete_date)))  AND (((payroll_assignment.status IN (10) AND payroll_assignment.type_of_job NOT IN(13)) OR (payroll_assignment.status IN (15,17) AND payroll_assignment.type_of_job IN(13))) AND payroll_assignment.complete_date != 'NULL'))) AND payroll_assignment.PIC LIKE '%".$emp_name."%' AND payroll_assignment.deleted = 0) t1 LEFT JOIN payroll_assignment_jobs ON t1.type_of_job = payroll_assignment_jobs.id GROUP BY t1.id ORDER BY t1.client_name ASC ");

        $q3 = $this->db->query("

            SELECT t1.*, payroll_assignment_jobs.type_of_job AS job FROM 
            (
                SELECT * FROM payroll_assignment WHERE 
                    (
                        MONTH('".$timesheet_month."') = MONTH(payroll_assignment.create_on)
                        AND
                        YEAR('".$timesheet_month."') = YEAR(payroll_assignment.create_on)
                    )
                    AND 
                    (
                        (
                            (
                                (payroll_assignment.status NOT IN (10) AND payroll_assignment.type_of_job NOT IN(13)) 
                                OR 
                                (payroll_assignment.status NOT IN (15,17) AND payroll_assignment.type_of_job IN(13))
                            ) 
                            AND 
                            payroll_assignment.complete_date IS NULL
                        ) 
                        OR 
                        (
                            (
                                (payroll_assignment.status IN (10) AND payroll_assignment.type_of_job NOT IN(13)) 
                                OR 
                                (payroll_assignment.status IN (15,17) AND payroll_assignment.type_of_job IN(13))
                            ) 
                            AND 
                            payroll_assignment.complete_date != 'NULL'
                        )
                    ) 
                    AND payroll_assignment.PIC LIKE '%".$emp_name."%' AND payroll_assignment.deleted = 0 
                    
                UNION ALL 
                
                SELECT * FROM payroll_assignment WHERE 
                    (
                        (
                            DATE('".$timesheet_month."') > DATE(payroll_assignment.create_on) 
                            AND 
                            (
                                (
                                    (payroll_assignment.status NOT IN (10) AND payroll_assignment.type_of_job NOT IN(13)) 
                                    OR 
                                    (payroll_assignment.status NOT IN (15,17) AND payroll_assignment.type_of_job IN(13))
                                ) 
                                AND payroll_assignment.complete_date IS NULL
                            )
                        ) 
                        OR 
                        (
                            (
                                (
                                    MONTH('".$timesheet_month."') = MONTH(payroll_assignment.complete_date) 
                                    AND 
                                    YEAR('".$timesheet_month."') = YEAR(payroll_assignment.complete_date)
                                ) 
                                OR 
                                (
                                    DATE('".$timesheet_month."') > DATE(payroll_assignment.create_on) 
                                    AND 
                                    DATE('".$timesheet_month."') < DATE(payroll_assignment.complete_date)
                                )
                            )  
                            AND 
                            (
                                (
                                    (payroll_assignment.status IN (10) AND payroll_assignment.type_of_job NOT IN(13)) 
                                    OR 
                                    (payroll_assignment.status IN (15,17) AND payroll_assignment.type_of_job IN(13))
                                ) 
                                AND payroll_assignment.complete_date != 'NULL'
                            )
                        )
                    ) 
                    AND 
                    payroll_assignment.PIC LIKE '%".$emp_name."%' AND payroll_assignment.deleted = 0
            ) t1 
            LEFT JOIN payroll_assignment_jobs ON t1.type_of_job = payroll_assignment_jobs.id 
            GROUP BY t1.id 
            ORDER BY t1.client_name ASC

        ");

        foreach($q3->result() as $result){
            $result->client_name = '*'.$result->client_name;
        }

        return $q3->result();
    }

    public function get_bf_timesheet($employee_id,$timesheet_date){

        $timesheet_date = date('Y-m-d', strtotime($timesheet_date."-1 month"));

        $q = $this->db->query("SELECT t.* FROM timesheet t WHERE t.employee_id = '".$employee_id."' AND month(t.month) = month('".$timesheet_date."') AND year(t.month) = year('".$timesheet_date."')");

        return $q->result();
    }

    public function get_leave_details($emp_id,$date){
        $q = $this->db->query("SELECT payroll_leave.* from payroll_leave WHERE (month(start_date) = month('".$date."') OR month(end_date) = month('".$date."')) AND status = 2 AND employee_id = '".$emp_id."' ");

         return $q->result();
    }

    public function get_this_month_leave(){

        $last_month = date("Y-m-d", strtotime("last month"));

        $q = $this->db->query("SELECT payroll_leave.* from payroll_leave WHERE (month(start_date) = month('".$last_month."') OR month(end_date) = month('".$last_month."')) AND status = 2");
        // $q = $this->db->query("SELECT payroll_leave.* from payroll_leave WHERE (month(start_date) = month(CURRENT_DATE) OR month(end_date) = month(CURRENT_DATE)) AND status = 2");

         return $q->result();
    }

    public function timesheet_filter($office, $department, $employee , $month , $year , $status){
         $q = $this->db->query("SELECT timesheet.*, payroll_employee.name AS name FROM timesheet LEFT JOIN payroll_employee ON timesheet.employee_id = payroll_employee.id WHERE payroll_employee.office LIKE '".$office."' AND payroll_employee.department LIKE '".$department."' AND (".$employee.") AND month(timesheet.month) = '".$month."' AND  year(timesheet.month) = '".$year."' AND timesheet.status_id LIKE '".$status."'");

        foreach($q->result() as $row){
            $row->status_id = $this->employment_json_model->get_timesheet_action_name($row->status_id);
        }

        return $q->result();
    }

    public function record_filter($record,$employee_id)
    {
        if($record)
        {
            $q1 = $this->db->query("SELECT t.*, e.name AS `employee_name` FROM timesheet t LEFT JOIN payroll_employee e ON e.id = t.employee_id WHERE t.employee_id=". $employee_id ." AND year(t.month) NOT IN (year(CURRENT_DATE)) ORDER BY month(t.month) DESC");

            foreach($q1->result() as $row){
                $row->status_id = $this->employment_json_model->get_timesheet_action_name($row->status_id);
            }

            return $q1->result();
        }
        else
        {
            $q2 = $this->db->query("SELECT t.*, e.name AS `employee_name` FROM timesheet t LEFT JOIN payroll_employee e ON e.id = t.employee_id WHERE t.employee_id=". $employee_id ." AND year(t.month) = year(CURRENT_DATE) ORDER BY month(t.month) DESC");

            foreach($q2->result() as $row){
                $row->status_id = $this->employment_json_model->get_timesheet_action_name($row->status_id);
            }

            return $q2->result();
        }

    }

    public function record_filter_admin($record)
    {
        if($record)
        {
            $q1 = $this->db->query("SELECT t.*, e.name AS `employee_name` FROM timesheet t LEFT JOIN payroll_employee e ON e.id = t.employee_id WHERE year(t.month) NOT IN (year(CURRENT_TIMESTAMP)) ORDER BY month(t.month) DESC");

            foreach($q1->result() as $row){
                $row->status_id = $this->employment_json_model->get_timesheet_action_name($row->status_id);
            }

            return $q1->result();
        }
        else
        {
            $q2 = $this->db->query("SELECT t.*, e.name AS `employee_name` FROM timesheet t LEFT JOIN payroll_employee e ON e.id = t.employee_id WHERE year(t.month)= year(CURRENT_TIMESTAMP) ORDER BY month(t.month) DESC");

            foreach($q2->result() as $row){
                $row->status_id = $this->employment_json_model->get_timesheet_action_name($row->status_id);
            }

            return $q2->result();
        }

    }

    // public function Submition_Notification(){
    //     $today = date("Y-m-d", strtotime("today"));
    //     $last_date = date("Y-m-d", strtotime("last day of this month"));
    //     $last_day = date('w', strtotime($last_date));
    //     $email = [];

    //     if($last_day == 6){
    //         $last_date = date('Y-m-d', strtotime($last_date. ' - 1 days'));
    //     }
    //     else if($last_day == 0){
    //         $last_date = date('Y-m-d', strtotime($last_date. ' - 2 days'));
    //     }

    //     $q = $this->db->query("SELECT users.email FROM timesheet LEFT JOIN payroll_user_employee ON payroll_user_employee.employee_id = timesheet.employee_id LEFT JOIN users ON users.id = payroll_user_employee.user_id WHERE month(timesheet.month) = month(CURRENT_TIMESTAMP) AND timesheet.status_id = 1 GROUP BY users.email");

    //     foreach($q->result() as $row){
    //         array_push($email,$row->email);
    //     }

    //     if($today>=$last_date)
    //     { 
    //         $this->load->library('parser');
    //         $parse_data = array(
    //             'month'  => date("F", strtotime("today"))
    //         );

    //         $msg = file_get_contents('./application/modules/timesheet/email_templates/timesheet_submition_notification.html');
    //         $message = $this->parser->parse_string($msg, $parse_data);

    //         $subject = 'Timesheet Submition Notification';
    //         $this->sma->send_email($email, $subject, $message,"" ,"" ,"" ,"");
    //         // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)}
    //     }
    // }

    public function get_third_working_date(){

        $first_date = date("Y-m-d", strtotime("first day of this month"));
        $first_day   = date('w', strtotime($first_date));

        if($first_day == 6){
            $first_date = date('Y-m-d', strtotime($first_date. ' + 2 days'));
        }
        else if($first_day == 0){
            $first_date = date('Y-m-d', strtotime($first_date. ' + 1 days'));
        }

        $thirt_date = date('Y-m-d', strtotime($first_date. ' + 3 weekdays'));

        return $thirt_date;
    }

    public function is_holiday(){

        $last_month = date("Y-m-d", strtotime("last month"));

        $q = $this->db->query("SELECT holiday_date FROM payroll_block_holiday WHERE month(holiday_date) = month('".$last_month."') GROUP by holiday_date");
        // $q = $this->db->query("SELECT holiday_date FROM payroll_block_holiday WHERE month(holiday_date) = month(CURRENT_DATE) GROUP by holiday_date");

        return $q->result();
    }

    // public function is_holiday2($id,$date){

    //     $query = $this->db->query(" SELECT * FROM payroll_employee WHERE id = '".$id."' ");

    //     foreach($query->result()as $item){
    //         $department_id = $item->department;
    //         $office_id     = $item->office; 
    //     }

    //     $q = $this->db->query("SELECT holiday_date FROM payroll_block_holiday WHERE month(holiday_date) = month('".$date."') AND offices_id = '".$office_id."' AND department_id = '".$department_id."' GROUP by holiday_date");

    //     return $q->result();
    // }

    public function get_holiday($id){

        $query = $this->db->query(" SELECT * FROM payroll_employee WHERE id = '".$id."' ");

        foreach($query->result()as $item){
            $department_id = $item->department;
            $office_id     = $item->office; 
        }

        $last_month = date("Y-m-d", strtotime("last month"));

        $q = $this->db->query("SELECT holiday_date FROM payroll_block_holiday WHERE month(holiday_date) = month('".$last_month."') AND offices_id = '".$office_id."' AND department_id = '".$department_id."' GROUP by holiday_date");

        return $q->result();
    }

    public function get_holiday2($id,$month){

        $query = $this->db->query(" SELECT * FROM payroll_employee WHERE id = '".$id."' ");

        foreach($query->result()as $item){
            $department_id = $item->department;
            $office_id     = $item->office; 
        }

        $q = $this->db->query("SELECT holiday_date FROM payroll_block_holiday WHERE month(holiday_date) = month('".$month."') AND offices_id = '".$office_id."' AND department_id = '".$department_id."' GROUP by holiday_date");

        // $q = $this->db->query("SELECT holiday_date FROM payroll_block_holiday WHERE month(holiday_date) = month('".$month."') AND department_id = '".$department_id."' GROUP by holiday_date");

        return $q->result();
    }

    public function timesheet_submition_check(){

        $last_month = date("Y-m-d", strtotime("last month"));

        $q = $this->db->query(" SELECT * FROM timesheet WHERE status_id = 1 AND month(month) = month('".$last_month."') ");
        // $q = $this->db->query(" SELECT * FROM timesheet WHERE status_id = 1 AND month(month) = month(CURRENT_DATE) ");

        return $q->result();
    }

    public function Timesheet_Submition($data,$id){

        if($id != null)
        {
            $this->db->where('id', $id);

            $result = $this->db->update('timesheet', $data);
        }
        else
        {
            $result = $this->db->insert('timesheet', $data); 
        }

        return $result;
    }

    public function Check_Timesheet($emp_id){

        $q1 = $this->db->query(" SELECT * FROM timesheet WHERE status_id = 1 AND employee_id = '".$emp_id."' ");

        if ($q1->num_rows() == 0) 
        {
            $q2 = $this->db->query(" SELECT * FROM timesheet WHERE MONTH(month) = MONTH(CURRENT_DATE) AND YEAR(month) = YEAR(CURRENT_DATE) AND employee_id = '".$emp_id."' ");

            if ($q2->num_rows() > 0) {
                return false;
            }
            else{
                return true;
            }
        }
        else
        {
            return false;
        }

    }

    public function get_office(){
        // $q = $this->db->query("SELECT office.id AS id,office.office_name  FROM timesheet t LEFT JOIN payroll_employee e ON e.id = t.employee_id LEFT JOIN payroll_offices office ON office.id = e.office GROUP BY office_name ORDER BY office_name ASC");
        $q = $this->db->query("SELECT office.id AS id,office.office_name  FROM timesheet t LEFT JOIN payroll_employee e ON e.id = t.employee_id INNER JOIN payroll_offices office ON office.id = e.office GROUP BY office_name ORDER BY office_name ASC");

        $office['0'] = 'All Offices';

        foreach($q->result() as $row){
            $office[$row->id] = $row->office_name;
        }

        return $office;
    }

    public function get_department(){
        $q = $this->db->query("SELECT department.id AS id,department.department_name  FROM timesheet t INNER JOIN payroll_employee e ON e.id = t.employee_id LEFT JOIN department ON department.id = e.department GROUP BY department.department_name ORDER BY department.list_order ASC");

        $department['0'] = 'All Departments';

        foreach($q->result() as $row){
            $department[$row->id] = $row->department_name;
        }

        return $department;
    }

    public function check_assignment_status($assignment_list){

        $result = array();

        foreach ($assignment_list as $key => $value)
        {
            $q = $this->db->query(" SELECT payroll_assignment.client_name,payroll_assignment_jobs.type_of_job,payroll_assignment.FYE,payroll_assignment_status_log.date FROM payroll_assignment_status_log LEFT JOIN payroll_assignment ON payroll_assignment_status_log.assignment_id = payroll_assignment.assignment_id LEFT JOIN payroll_assignment_jobs ON payroll_assignment_jobs.id = payroll_assignment.type_of_job WHERE to_status IN (4,5,19) AND payroll_assignment_status_log.assignment_id = '".$value['assignment_id']."' ORDER BY payroll_assignment_status_log.date DESC LIMIT 1 ");

            if ($q->num_rows() > 0)
            {
                $q->result()[0]->client_name = '*'.$q->result()[0]->client_name;
                array_push($result,$q->result());
            }
        }

        return $result;
    }

    public function stocktake_assignment_list($timesheet_id) {

        $query1 = $this->db->query(" SELECT payroll_user_employee.user_id AS user_id, timesheet.month AS timesheetMonth FROM timesheet LEFT JOIN payroll_user_employee ON payroll_user_employee.employee_id = timesheet.employee_id WHERE timesheet.id = '".$timesheet_id."' ");
        $query1Result = $query1->result();

        $query2 = $this->db->query(" SELECT client.company_name,audit_stocktake_reminder.fye_date FROM audit_stocktake_arrangement_info 
            LEFT JOIN audit_stocktake_arrangement ON audit_stocktake_arrangement.id = audit_stocktake_arrangement_info.stocktake_arrangement_id 
            LEFT JOIN audit_stocktake_reminder ON audit_stocktake_reminder.id = audit_stocktake_arrangement.reminder_id 
            LEFT JOIN client ON client.company_code = audit_stocktake_reminder.company_code 
            WHERE FIND_IN_SET('".$query1Result[0]->user_id."',audit_stocktake_arrangement_info.auditor_id)
            AND MONTH(audit_stocktake_arrangement_info.stocktake_date) = MONTH('".$query1Result[0]->timesheetMonth."')
            AND YEAR(audit_stocktake_arrangement_info.stocktake_date) = YEAR('".$query1Result[0]->timesheetMonth."')
            AND audit_stocktake_arrangement.deleted = 0
            AND audit_stocktake_arrangement_info.deleted = 0");

        $query2Result = $query2->result();

        foreach($query2Result as $result){
            $result->company_name = $this->encryption->decrypt($result->company_name);
            $result->company_name = '*'.$result->company_name;
        }

        return $query2Result;
    }

    // public function list_to_generate_pdf($office, $department, $employee , $month , $year , $status){
    //      $q = $this->db->query("SELECT payroll_employee.id AS id FROM timesheet LEFT JOIN payroll_employee ON timesheet.employee_id = payroll_employee.id WHERE payroll_employee.office LIKE '".$office."' AND payroll_employee.department LIKE '".$department."' AND (".$employee.") AND month(timesheet.month) = '".$month."' AND  year(timesheet.month) = '".$year."' AND timesheet.status_id LIKE '".$status."'");

    //     return $q->result();
    // }
}
?>