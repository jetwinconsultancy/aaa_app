<?php defined('BASEPATH') OR exit('No direct script access allowed');
include 'application/js/random_alphanumeric_generator.php';

class Budget_hours_model extends CI_Model
{
	public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function get_assignment_list(){
        $q = $this->db->query(" SELECT * FROM payroll_assignment PA WHERE PA.type_of_job != 13 AND PA.status != 10 AND PA.deleted = 0 ");

        foreach($q->result() as $row)
        {
            if($row->type_of_job == 1)
            {
                
                $query2 = $this->db->query(' SELECT * FROM payroll_assignment WHERE type_of_job = 13 AND deleted = 0 AND client_name = "'.$row->client_name.'" AND FYE = "'.$row->FYE.'" AND assignment_id != "'.$row->assignment_id.'" ');

                if ($query2->num_rows() > 0)
                {
                   foreach($query2->result() as $row2)
                   {
                        $row->budget_hour = $row->budget_hour + $row2->budget_hour;
                   }
                }
            }
        }

        return $q->result();
    }

    public function get_completed_assignment_list(){
        $q = $this->db->query(" SELECT * FROM payroll_assignment PA WHERE PA.type_of_job != 13 AND PA.status = 10 AND PA.deleted = 0 ");

        foreach($q->result() as $row)
        {
            if($row->type_of_job == 1)
            {
                
                $query2 = $this->db->query(' SELECT * FROM payroll_assignment WHERE type_of_job = 13 AND deleted = 0 AND client_name = "'.$row->client_name.'" AND FYE = "'.$row->FYE.'" AND assignment_id != "'.$row->assignment_id.'" ');

                if ($query2->num_rows() > 0)
                {
                   foreach($query2->result() as $row2)
                   {
                        $row->budget_hour = $row->budget_hour + $row2->budget_hour;
                   }
                }
            }
        }

        return $q->result();
    }

    public function get_employee_assignment_list($id){

        $q = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");
        $userName = $q->result();
        $userName = json_encode($userName[0]->name);

        $query = $this->db->query(" SELECT PA.* FROM payroll_assignment PA WHERE PA.type_of_job != 13 AND PA.status != 10 AND PA.deleted = 0 AND PA.PIC LIKE '%".$userName."%' ");

        foreach($query->result() as $row)
        {
            if($row->type_of_job == 1)
            {
                
                $query2 = $this->db->query(' SELECT * FROM payroll_assignment WHERE type_of_job = 13 AND deleted = 0 AND client_name = "'.$row->client_name.'" AND FYE = "'.$row->FYE.'" AND assignment_id != "'.$row->assignment_id.'" ');

                if ($query2->num_rows() > 0)
                {
                   foreach($query2->result() as $row2)
                   {
                        $row->budget_hour = $row->budget_hour + $row2->budget_hour;
                   }
                }
            }
        }

        return $query->result();
    }

    public function get_assignment_details($assignment_id){
        $q = $this->db->query(" SELECT PA.* FROM payroll_assignment PA WHERE PA.assignment_id = '".$assignment_id."' ");

        foreach($q->result() as $row)
        {
            if($row->type_of_job == 1)
            {
                
                $query2 = $this->db->query(' SELECT * FROM payroll_assignment WHERE type_of_job = 13 AND deleted = 0 AND client_name = "'.$row->client_name.'" AND FYE = "'.$row->FYE.'" AND assignment_id != "'.$row->assignment_id.'" ');

                if ($query2->num_rows() > 0)
                {
                   foreach($query2->result() as $row2)
                   {
                        $row->budget_hour = $row->budget_hour + $row2->budget_hour;
                   }
                }
            }
        }

        return $q->result();
    }

    public function get_budget($assignment_id){
        $q = $this->db->query(" SELECT budget FROM payroll_budget WHERE assignment_no = '".$assignment_id."' ");

        return $q->result();
    }

    public function get_actual_budget($assignment_id){
        $q = $this->db->query(" SELECT actual FROM payroll_budget WHERE assignment_no = '".$assignment_id."' ");

        return $q->result();
    }

    public function get_others($assignment_id){
        $q = $this->db->query(" SELECT review_and_supervision,partner_review,fees_raised,variance FROM payroll_budget WHERE assignment_no = '".$assignment_id."' ");

        return $q->result();
    }

    public function get_report_type($assignment_id){
        $q = $this->db->query(" SELECT report_type FROM payroll_budget WHERE assignment_no = '".$assignment_id."' ");

        return $q->result();
    }

    public function get_log($id){

        $q = $this->db->query(" SELECT * FROM payroll_budget_log WHERE payroll_budget_log.assignment_id = '".$id."' ORDER BY payroll_budget_log.date ");
        // print_r($q);
        return $q->result();
    }

    public function get_department(){
        $q = $this->db->query("SELECT department.id AS id,department.department_name  FROM timesheet t INNER JOIN payroll_employee e ON e.id = t.employee_id LEFT JOIN department ON department.id = e.department GROUP BY department.department_name ORDER BY department.list_order ASC");

        $department['0'] = 'All Departments';

        foreach($q->result() as $row){
            $department[$row->id] = $row->department_name;
        }

        return $department;
    }

    public function save_budget($data){

        $q = $this->db->query(" SELECT * FROM payroll_budget WHERE payroll_budget.assignment_no = '".$data['assignment_no']."' ");

        if ($q->num_rows() > 0){
            $q2 = $this->db->where('assignment_no', $data['assignment_no']);
            $result = $q2->update('payroll_budget', $data);
        }
        else
        {
            $data["budget_id"] = random_code(8);
            $result = $this->db->insert('payroll_budget', $data);
        }

        return $result;
    }

    public function submit_log($data){

        $query = $this->db->insert('payroll_budget_log', $data); 

        return $query;
    }

    public function priorYear_Actual_Check($data){

        $q = $this->db->query(" SELECT * FROM payroll_budget WHERE client_id ='".$data['client_id']."' AND year(fye) = year('".$data['fye']."')-1 AND type_of_job = '".$data['type_of_job']."' ");

        if ($q->num_rows() > 0)
        {
            $sq = $this->db->query(" SELECT * FROM payroll_budget WHERE client_id ='".$data['client_id']."' AND year(fye) = year('".$data['fye']."') AND type_of_job = '".$data['type_of_job']."' ");

            $q_result    = $q->result();
            $subq_result = $sq->result();

            $prior = array(
                'prior_actual' => $q_result[0]->actual,
                'prior_rns'    => $q_result[0]->review_and_supervision,
                'prior_pr'     => $q_result[0]->partner_review,
                'prior_fr'     => $q_result[0]->fees_raised
            );

            $updt_query = $this->db->where('id', $subq_result[0]->id);
            $updt_query->update('payroll_budget', $prior);

            return true;
        }
        else
        {
            $sq = $this->db->query(" SELECT * FROM payroll_budget WHERE client_id ='".$data['client_id']."' AND year(fye) = year('".$data['fye']."') AND type_of_job = '".$data['type_of_job']."' ");

            $subq_result = $sq->result();

            if($sq->num_rows() > 0)
            {
                if($subq_result[0]->prior_actual == null && $subq_result[0]->prior_rns == null && $subq_result[0]->prior_pr == null && $subq_result[0]->prior_fr == null)
                {
                    return false;
                }
                else
                {
                    return true;
                }
            }
        }

    }

    public function priorYear_Rate_Check($data){

        $sq = $this->db->query(" SELECT * FROM payroll_budget WHERE client_id ='".$data['client_id']."' AND assignment_no = '".$data['assignment_id']."' ");

        $subq_result = $sq->result();

        if($sq->num_rows() > 0)
        {
            $q = $this->db->query(" SELECT * FROM payroll_budget WHERE client_id ='".$data['client_id']."' AND year(fye) = year('".$data['fye']."')-1 AND type_of_job = '".$data['type_of_job']."' ");

            if($q->num_rows() > 0)
            {
                if($subq_result[0]->prior_rate == null)
                {
                    return false;
                }
                else
                {
                    return true;
                }
            }
            else
            {
                return true;
            }
        }

    }

    public function priorYear_Data_Check($data){

        $result = null;

        $sq = $this->db->query(" SELECT * FROM payroll_budget WHERE client_id ='".$data['client_id']."' AND assignment_no = '".$data['assignment_id']."' ");

        $subq_result = $sq->result();

        if($sq->num_rows() > 0)
        {
            if($subq_result[0]->prior_actual != null && $subq_result[0]->prior_rns != null && $subq_result[0]->prior_pr != null && $subq_result[0]->prior_fr != null)
            {
                $q = $this->db->query(" SELECT * FROM payroll_budget WHERE client_id ='".$data['client_id']."' AND year(fye) = year('".$data['fye']."')-1 AND type_of_job = '".$data['type_of_job']."' ");

                if ($q->num_rows() == 0)
                {
                    $result = 'user_key';
                }
            }
            
            if($subq_result[0]->prior_rate != null || $subq_result[0]->prior_rate != 0)
            {
                $result = 'system_data';
            }

            return $result;
        }
        else
        {
            return $result;
        }

    }

    public function actual_hours_check($people,$complete_date){

        $last_month = date("Y-m-d", strtotime("last month"));
        $content    = ''; 

        if($complete_date != "")
        {
            $q5 = $this->db->query(" SELECT timesheet.content FROM users LEFT JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id LEFT JOIN timesheet ON timesheet.employee_id = payroll_employee.id WHERE CONCAT(users.first_name , ' ' , users.last_name) = '".$people."' AND month(timesheet.month) = month('".$complete_date."') ");

            if($q5->num_rows() > 0)
            {
                foreach($q5->result() as $row5)
                {
                    if(json_encode($row5->content) != '""')
                    {
                        $content = $row5->content;
                    }
                }
            }
        }
        else
        {
            $q = $this->db->query(" SELECT timesheet.content FROM users LEFT JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id LEFT JOIN timesheet ON timesheet.employee_id = payroll_employee.id WHERE CONCAT(users.first_name , ' ' , users.last_name) = '".$people."' AND month(timesheet.month) = month(CURDATE()) ");

            if($q->num_rows() > 0)
            {
                foreach($q->result() as $row)
                {
                    if(json_encode($row->content) != '""')
                    {
                        $content = $row->content;
                    }
                    else
                    {
                        $q2 = $this->db->query(" SELECT timesheet.content FROM users LEFT JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id LEFT JOIN timesheet ON timesheet.employee_id = payroll_employee.id WHERE CONCAT(users.first_name , ' ' , users.last_name) = '".$people."' AND month(timesheet.month) = month('".$last_month."') ");

                        if($q2->num_rows() > 0)
                        {
                            foreach($q2->result() as $row2)
                            {
                                if(json_encode($row2->content) != '""')
                                {
                                    $content = $row2->content;
                                }
                            }
                        }
                    }
                }
            }
        }

        // $q = $this->db->query(" SELECT timesheet.content FROM users LEFT JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id LEFT JOIN timesheet ON timesheet.employee_id = payroll_employee.id WHERE CONCAT(users.first_name , ' ' , users.last_name) = '".$people."' AND month(timesheet.month) = month(CURDATE()) ");

        // if($q->num_rows() > 0)
        // {
        //     foreach($q->result() as $row)
        //     {
        //         if(json_encode($row->content) != '""')
        //         {
        //             $content = $row->content;
        //         }
        //         else
        //         {
        //             $q2 = $this->db->query(" SELECT timesheet.content FROM users LEFT JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id LEFT JOIN timesheet ON timesheet.employee_id = payroll_employee.id WHERE CONCAT(users.first_name , ' ' , users.last_name) = '".$people."' AND month(timesheet.month) = month('".$last_month."') ");

        //             if($q2->num_rows() > 0)
        //             {
        //                 foreach($q2->result() as $row2)
        //                 {
        //                     if(json_encode($row2->content) != '""')
        //                     {
        //                         $content = $row2->content;
        //                     }
        //                     else
        //                     {
        //                         $q4 = $this->db->query(" SELECT timesheet.content FROM users LEFT JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id LEFT JOIN timesheet ON timesheet.employee_id = payroll_employee.id WHERE CONCAT(users.first_name , ' ' , users.last_name) = '".$people."' AND month(timesheet.month) = month('".$complete_date."') ");

        //                         if($q4->num_rows() > 0)
        //                         {
        //                             foreach($q4->result() as $row4)
        //                             {
        //                                 if(json_encode($row4->content) != '""')
        //                                 {
        //                                     $content = $row4->content;
        //                                 }
        //                             }
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }
        // else
        // {
        //     $q3 = $this->db->query(" SELECT timesheet.content FROM users LEFT JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id LEFT JOIN timesheet ON timesheet.employee_id = payroll_employee.id WHERE CONCAT(users.first_name , ' ' , users.last_name) = '".$people."' AND month(timesheet.month) = month('".$last_month."') ");

        //     if($q3->num_rows() > 0)
        //     {
        //         foreach($q3->result() as $row3)
        //         {
        //             if(json_encode($row3->content) != '""')
        //             {
        //                 $content = $row3->content;
        //             }
        //             else
        //             {
        //                 $q5 = $this->db->query(" SELECT timesheet.content FROM users LEFT JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id LEFT JOIN timesheet ON timesheet.employee_id = payroll_employee.id WHERE CONCAT(users.first_name , ' ' , users.last_name) = '".$people."' AND month(timesheet.month) = month('".$complete_date."') ");

        //                 if($q5->num_rows() > 0)
        //                 {
        //                     foreach($q5->result() as $row5)
        //                     {
        //                         if(json_encode($row5->content) != '""')
        //                         {
        //                             $content = $row5->content;
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }

        return $content;

    }

    public function get_type_of_job($type_of_job){

        $q = $this->db->query(" SELECT type_of_job FROM payroll_assignment_jobs WHERE id = '".$type_of_job."' ");

        foreach($q->result() as $row){
            $job = $row->type_of_job; 
        }

        return $job;
    }

    public function get_employee(){
        $q = $this->db->query("SELECT e.id AS id, e.name AS `employee_name` FROM timesheet t LEFT JOIN payroll_employee e ON e.id = t.employee_id GROUP BY employee_name ORDER BY employee_name ASC");

        $employee['0'] = 'All Employee';

        foreach($q->result() as $row){
            $employee[$row->id] = $row->employee_name;
        }

        return $employee;
    }

    public function get_employee_bvsa_data($data)
    {
        $q = $this->db->query(" SELECT CONCAT(users.first_name , ' ' , users.last_name) AS name FROM payroll_employee INNER JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id LEFT JOIN users ON users.id = payroll_user_employee.user_id WHERE payroll_employee.id = '".$data['employee_id']."' ");

        $query_result = $q->result();

        $emp_name = $query_result[0]->name;

        if($data['from'] != '' && $data['to'] != '')
        {
            $q2 = $this->db->query(" SELECT * FROM payroll_assignment WHERE (PIC LIKE '%leader%:%".$emp_name."%,%' OR PIC LIKE '%assistant%:%".$emp_name."%,%') AND complete_date >= '".date('Y-m-d', strtotime($data['from']))."' AND complete_date <= '".date('Y-m-d', strtotime($data['to']))."' ");
        }
        else if($data['from'] == '' && $data['to'] != '')
        {
            $q2 = $this->db->query(" SELECT * FROM payroll_assignment WHERE (PIC LIKE '%leader%:%".$emp_name."%,%' OR PIC LIKE '%assistant%:%".$emp_name."%,%') AND complete_date <= '".date('Y-m-d', strtotime($data['to']))."' ");
        }
        else if($data['from'] != '' && $data['to'] == '')
        {
            $q2 = $this->db->query(" SELECT * FROM payroll_assignment WHERE (PIC LIKE '%leader%:%".$emp_name."%,%' OR PIC LIKE '%assistant%:%".$emp_name."%,%') AND complete_date >= '".date('Y-m-d', strtotime($data['from']))."' ");
        }
        else
        {
            $q2 = $this->db->query(" SELECT * FROM payroll_assignment WHERE (PIC LIKE '%leader%:%".$emp_name."%,%' OR PIC LIKE '%assistant%:%".$emp_name."%,%') AND complete_date IS NOT NULL ");
        }

        $assignment = $q2->result();

        $result = array();

        for($a = 0 ; $a < count($assignment) ; $a++ )
        {
            $bvsa = $this->get_bvsa_data($assignment[$a]->assignment_id,$assignment[$a]->client_name,$assignment[$a]->complete_date,$emp_name);

            if(count($bvsa) > 0)
            {
                array_push($result, $bvsa);
            }
        }

        return $result;
    }

    public function get_bvsa_data($assignment_id,$client_name,$complete_date,$emp_name)
    {
        $result = array();

        $q = $this->db->query(" SELECT * FROM payroll_budget WHERE assignment_no = '".$assignment_id."' ");

        if($q->num_rows() > 0)
        {
            $budget = $q->result();
            $budget_hour = json_decode($budget[0]->budget);
            $actual_hour = json_decode($budget[0]->actual);

            $result['assignment_id'] = $assignment_id;
            $result['client_name']   = $client_name;
            $result['complete_date'] = $complete_date;
            $result['actual_hour']   = 0;
            $result['budget_hour']   = 0;
            $result['employee_name'] = $emp_name;

            // if(count($budget_hour) == 2)
            // {
            //     if($budget_hour[0][0] == $emp_name)
            //     {
            //         $result['budget_hour'] = $budget_hour[0][count($budget_hour[0]) -1 ];
            //     }
            // }
            // else
            // {
            //     $total_budget = 0;

            //     for($a = 0 ; $a < count($budget_hour) ; $a++)
            //     {
            //         $total_budget = $total_budget + (int)$budget_hour[$a][count($budget_hour[$a]) -1 ];

            //         if($budget_hour[0][0] == $emp_name)
            //         {
            //             $result['budget_hour'] = $budget_hour[0][count($budget_hour[0]) -1 ];
            //         }
            //     }

            //     $result['budget_hour'] = $total_budget;
            // }

            for($a = 0 ; $a < count($budget_hour) ; $a++)
            {
                if($budget_hour[$a][0] == $emp_name)
                {
                    $result['budget_hour'] = $budget_hour[$a][count($budget_hour[$a]) -1 ];
                }
            }

            for($b = 0 ; $b < count($actual_hour) ; $b++)
            {
                if($actual_hour[$b][0] == $emp_name)
                {
                    $result['actual_hour'] = $actual_hour[$b][count($actual_hour[$b]) -1 ];
                }
            }
        }

        return $result;
    }

    public function get_prior_year_rate($data){

        $result = '';

        $q = $this->db->query(" SELECT prior_rate FROM payroll_budget WHERE client_id ='".$data['client_id']."' AND assignment_no = '".$data['assignment_id']."' ");

        $q_result = $q->result();

        if($q->num_rows() > 0)
        {
            $result = $q_result[0]->prior_rate;
        }

        return $result;

    }

    public function get_prior_year_actual($data){

        $result = array();

        $q = $this->db->query(" SELECT * FROM payroll_budget WHERE client_id ='".$data['client_id']."' AND assignment_no = '".$data['assignment_id']."' ");

        $q_result = $q->result();

        if($q->num_rows() > 0)
        {
            array_push($result, $q_result[0]->prior_actual);
            array_push($result, $q_result[0]->prior_rns);
            array_push($result, $q_result[0]->prior_pr);
            array_push($result, $q_result[0]->prior_fr);
        }

        return $result;

    }

    public function get_stock_take_hours($client_id,$fye,$people){

        $user_list = array();
        $stock_take_list = array();
        $result = array();

        foreach ($people as $value) {
            $query1 = $this->db->query(" SELECT users.id FROM users WHERE CONCAT(users.first_name , ' ' , users.last_name) = '".$value."'");
            $query1 = $query1->result();
            array_push($user_list, $query1[0]->id);
        }

        foreach ($user_list as $value) {
            $query2 = $this->db->query(" SELECT audit_stocktake_arrangement_info.stocktake_date FROM audit_stocktake_reminder 
                INNER JOIN audit_stocktake_arrangement ON audit_stocktake_arrangement.reminder_id = audit_stocktake_reminder.id 
                LEFT JOIN audit_stocktake_arrangement_info ON audit_stocktake_arrangement_info.stocktake_arrangement_id  = audit_stocktake_arrangement.id 
                WHERE audit_stocktake_reminder.company_code ='".$client_id."' 
                AND audit_stocktake_reminder.fye_date = '".$fye."'
                AND FIND_IN_SET('".$value."',audit_stocktake_arrangement_info.auditor_id)
                AND audit_stocktake_arrangement.deleted = 0
                AND audit_stocktake_arrangement_info.deleted = 0
                ORDER BY audit_stocktake_arrangement_info.stocktake_date DESC LIMIT 1");

            if($query2->num_rows() > 0)
            {
                $query2 = $query2->result();
                for($a=0;$a<count($query2);$a++) {
                    array_push($stock_take_list, array('user_id'=>$value, "stock_take_date"=>$query2[$a]->stocktake_date));
                }
            }
        }

        foreach ($stock_take_list as $value) {
            $query3 = $this->db->query(" SELECT CONCAT(users.first_name , ' ' , users.last_name) AS name, timesheet.content FROM timesheet 
                LEFT JOIN payroll_user_employee ON payroll_user_employee.employee_id = timesheet.employee_id 
                LEFT JOIN users ON users.id = payroll_user_employee.user_id
                WHERE payroll_user_employee.user_id = '".$value['user_id']."' 
                AND MONTH(timesheet.month) = MONTH('".$value['stock_take_date']."')
                AND YEAR(timesheet.month) = YEAR('".$value['stock_take_date']."')");
            $query3 = $query3->result();
            array_push($result, array('name'=>$query3[0]->name, "content"=>$query3[0]->content));
        }

        return $result;
    }

    public function get_assignment_status(){
        $q = $this->db->query("SELECT * FROM payroll_assignment_status ORDER BY list_order ASC");

        $status[0] = "All Status";

        foreach($q->result() as $row){
            $status[$row->id] = $row->assignment_status;
        }

        return $status;
    }

    // public function get_report_data($data)
    // {
    //     if($data['status'] === '0') {
    //         $status = "payroll_assignment.status LIKE '%%'";
    //     } else {
    //         $status = "payroll_assignment.status = '".$data['status']."'";
    //     }

    //     if($data['report_dateFrom'] != '') {
    //         $report_dateFrom = "AND payroll_assignment.create_on >= '".date('Y-m-d', strtotime($data['report_dateFrom']))."'";
    //     } else {
    //         $report_dateFrom = "";
    //     }

    //     if($data['report_dateTo'] != '') {
    //         $report_dateTo = "AND payroll_assignment.complete_date <= '".date('Y-m-d', strtotime($data['report_dateTo']))."'";
    //     } else {
    //         $report_dateTo = "";
    //     }

    //     $query = $this->db->query(" SELECT payroll_budget.client_name, payroll_budget.fye, payroll_assignment_jobs.type_of_job, payroll_assignment_status.assignment_status, payroll_budget.actual, payroll_assignment.create_on, payroll_assignment.complete_date
    //         FROM payroll_budget 
    //         LEFT JOIN payroll_assignment ON payroll_assignment.assignment_id = payroll_budget.assignment_no
    //         LEFT JOIN payroll_assignment_jobs ON payroll_assignment_jobs.id = payroll_budget.type_of_job
    //         LEFT JOIN payroll_assignment_status ON payroll_assignment_status.id = payroll_assignment.status 
    //         WHERE ".$status."
    //         ".$report_dateFrom."
    //         ".$report_dateTo."");

    //     $result = $query->result();
    //     return $result;
    // }

}
?>