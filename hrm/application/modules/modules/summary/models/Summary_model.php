<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Summary_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }


    public function get_updated_or_completed_list()
    {
        $updated_or_completed_list = array();

        $sun = strtotime("last sunday");
        $sun = date('w', $sun)==date('w') ? $sun+7*86400 : $sun;
        $sat = strtotime(date("Y-m-d",$sun)." +6 days");
        $this_week_sd = date("Y-m-d",$sun);
        $this_week_ed = date("Y-m-d",$sat);

        $employee_list = $this->db->query(" SELECT CONCAT(first_name , ' ' , last_name) AS name, payroll_employee.id AS emp_id FROM users 
        INNER JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id 
        LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id 
        WHERE payroll_employee.employee_status_id NOT IN (3,4) AND payroll_employee.id NOT IN (5) AND payroll_employee.department NOT IN (5) ORDER BY name ");

        $employee_list = $employee_list->result_array();

        for($a=0 ; $a<count($employee_list) ; $a++){

            // PUSH NAME TO ARRAY LIST
            $updated_or_completed_list[$a]['emp_id'] = $employee_list[$a]['emp_id'];
            $updated_or_completed_list[$a]['name'] = $employee_list[$a]['name'];

            // INITIAL NUMBER 
            $updated_or_completed_list[$a]['number_status_updated'] = 0;
            $updated_or_completed_list[$a]['number_remark_updated'] = 0;
            $updated_or_completed_list[$a]['number_completed'] = 0;


            // GET STATUS UPDATED LIST
            $status_changed_list = $this->db->query("SELECT count(*) AS number_status_updated FROM payroll_assignment_status_log 
            LEFT JOIN payroll_assignment ON payroll_assignment.assignment_id = payroll_assignment_status_log.assignment_id 
            WHERE payroll_assignment.deleted = '0' 
            AND payroll_assignment.PIC LIKE '%assistant%".$employee_list[$a]['name']."%]%'
            AND date(payroll_assignment_status_log.date) >= '".$this_week_sd."'
            AND date(payroll_assignment_status_log.date) <= '".$this_week_ed."'");

            // PUSH STATUS UPDATED LIST
            if($status_changed_list->num_rows()){
                foreach ($status_changed_list->result() as $key => $value) {
                    if($value->number_status_updated != 0){
                        $updated_or_completed_list[$a]['number_status_updated'] = $value->number_status_updated;
                    }
                    else{
                        $updated_or_completed_list[$a]['number_status_updated'] = 0;
                    }
                }
            }


            // GET REMARK UPDATED LIST
            $remark_changed_list = $this->db->query("SELECT count(*) AS number_remark_updated FROM payroll_assignment_remark_log 
            LEFT JOIN payroll_assignment ON payroll_assignment.assignment_id = payroll_assignment_remark_log.assignment_id 
            WHERE payroll_assignment.deleted = '0' 
            AND payroll_assignment.PIC LIKE '%assistant%".$employee_list[$a]['name']."%]%'
            AND date(payroll_assignment_remark_log.date) >= '".$this_week_sd."'
            AND date(payroll_assignment_remark_log.date) <= '".$this_week_ed."'");

            // PUSH REMARK UDPATED LIST
            if($remark_changed_list->num_rows()){
                foreach ($remark_changed_list->result() as $key => $value) {
                    if($value->number_remark_updated != 0){
                        $updated_or_completed_list[$a]['number_remark_updated'] = $value->number_remark_updated;
                    }
                    else{
                        $updated_or_completed_list[$a]['number_remark_updated'] = 0;
                    }
                }
            }


            // GET COMPLETED LIST
            $completed_list = $this->db->query("SELECT count(*) AS number_completed FROM payroll_assignment 
            WHERE payroll_assignment.deleted = '0' 
            AND (
                    (payroll_assignment.status IN (10) AND payroll_assignment.type_of_job NOT IN(13)) 
                    OR 
                    (payroll_assignment.status IN (15,17) AND payroll_assignment.type_of_job IN(13))
                )
            AND payroll_assignment.PIC LIKE '%assistant%".$employee_list[$a]['name']."%]%'
            AND payroll_assignment.complete_date >= '".$this_week_sd."'
            AND payroll_assignment.complete_date <= '".$this_week_ed."'");

            // PUSH COMPLETED LIST TO ARRAY LIST
            if($completed_list->num_rows()){
                foreach ($completed_list->result() as $key => $value) {
                    if($value->number_completed != 0){
                        $updated_or_completed_list[$a]['number_completed'] = $value->number_completed;
                    }
                    else{
                        $updated_or_completed_list[$a]['number_completed'] = 0;
                    }
                }
            }

        }


        // REMOVE NO UPDATED/COMPLETED EMPLOYEE
        foreach ($updated_or_completed_list as $key => $value) {
            if($value['number_status_updated'] == 0 && $value['number_remark_updated'] == 0 && $value['number_completed'] == 0){
                unset($updated_or_completed_list[$key]);
            }
        }


        // RETURN SEQUENCE ARRAY
        return array_values($updated_or_completed_list);
    }


    public function get_job_remain_list()
    {
        $job_remain_list = array();

        $employee_list = $this->db->query(" SELECT CONCAT(first_name , ' ' , last_name) AS name, payroll_employee.id AS emp_id FROM users 
        INNER JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id 
        LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id 
        WHERE payroll_employee.employee_status_id NOT IN (3,4) AND payroll_employee.id NOT IN (5) AND payroll_employee.department NOT IN (5) ORDER BY name ");

        $employee_list = $employee_list->result_array();

        for($a=0 ; $a<count($employee_list) ; $a++){

            // PUSH NAME TO ARRAY LIST
            $job_remain_list[$a]['emp_id'] = $employee_list[$a]['emp_id'];
            $job_remain_list[$a]['name'] = $employee_list[$a]['name'];

            // INITIAL JOB
            $job_remain_list[$a]['total'] = 0;
            $job_remain_list[$a]['ACCOUNT_NOT_IN'] = 0;
            $job_remain_list[$a]['YET_TO_START'] = 0;
            $job_remain_list[$a]['PLANNING_WITHOUT_ACCOUNT'] = 0;
            $job_remain_list[$a]['PLANNING_COMPLETED_WITHOUT_ACCOUNT'] = 0;
            $job_remain_list[$a]['PLANNING_WITH_ACCOUNT'] = 0;
            $job_remain_list[$a]['PLANNING_COMPLETED_WITH_ACCOUNT'] = 0;
            $job_remain_list[$a]['INTERIM_COMPLETED'] = 0;
            $job_remain_list[$a]['WIP'] = 0;
            $job_remain_list[$a]['KIV'] = 0;
            $job_remain_list[$a]['FINALIZING'] = 0;
            $job_remain_list[$a]['REVIEWING_TEAM_LEAD'] = 0;
            $job_remain_list[$a]['REVIEWING_MANAGER'] = 0;
            $job_remain_list[$a]['REVIEWING_PARTNER'] = 0;
            $job_remain_list[$a]['CLEARING_REVIEW_POINTS'] = 0;
            $job_remain_list[$a]['SENT_OUT_FOR_ADOPTION'] = 0;
            $job_remain_list[$a]['SIGNED'] = 0;
            $job_remain_list[$a]['PENDING_DOCS_PAYMENT'] = 0;
            $job_remain_list[$a]['PENDING_DOCS'] = 0;
            $job_remain_list[$a]['PENDING_PAYMENT'] = 0;

            // GET JOB REMAIN LIST
            $result_list = $this->db->query("SELECT payroll_assignment.*,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
            LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
            LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id 
            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id
            WHERE payroll_assignment.deleted = '0' 
            AND (
                    (payroll_assignment.status NOT IN (10) AND payroll_assignment.type_of_job NOT IN(13)) 
                    OR 
                    (payroll_assignment.status NOT IN (15,17) AND payroll_assignment.type_of_job IN(13))
                )

            AND payroll_assignment.PIC LIKE '%assistant%".$employee_list[$a]['name']."%]%'");

            if($result_list->num_rows()){
                foreach ($result_list->result() as $key => $value) {

                    $job_remain_list[$a]['total'] = $job_remain_list[$a]['total'] + 1;

                    if($value->assignment_status == 'ACCOUNT NOT IN'){
                        $job_remain_list[$a]['ACCOUNT_NOT_IN'] = $job_remain_list[$a]['ACCOUNT_NOT_IN'] + 1;
                    }
                    if($value->assignment_status == 'YET TO START'){
                        $job_remain_list[$a]['YET_TO_START'] = $job_remain_list[$a]['YET_TO_START'] + 1;
                    }
                    if($value->assignment_status == 'PLANNING WITHOUT ACCOUNT'){
                        $job_remain_list[$a]['PLANNING_WITHOUT_ACCOUNT'] = $job_remain_list[$a]['PLANNING_WITHOUT_ACCOUNT'] + 1;
                    }
                    if($value->assignment_status == 'PLANNING COMPLETED - WITHOUT ACCOUNT'){
                        $job_remain_list[$a]['PLANNING_COMPLETED_WITHOUT_ACCOUNT'] = $job_remain_list[$a]['PLANNING_COMPLETED_WITHOUT_ACCOUNT'] + 1;
                    }
                    if($value->assignment_status == 'PLANNING WITH ACCOUNT'){
                        $job_remain_list[$a]['PLANNING_WITH_ACCOUNT'] = $job_remain_list[$a]['PLANNING_WITH_ACCOUNT'] + 1;
                    }
                    if($value->assignment_status == 'PLANNING COMPLETED - WITH ACCOUNT'){
                        $job_remain_list[$a]['PLANNING_COMPLETED_WITH_ACCOUNT'] = $job_remain_list[$a]['PLANNING_COMPLETED_WITH_ACCOUNT'] + 1;
                    }
                    if($value->assignment_status == 'INTERIM COMPLETED'){
                        $job_remain_list[$a]['INTERIM_COMPLETED'] = $job_remain_list[$a]['INTERIM_COMPLETED'] + 1;
                    }
                    if($value->assignment_status == 'WIP'){
                        $job_remain_list[$a]['WIP'] = $job_remain_list[$a]['WIP'] + 1;
                    }
                    if($value->assignment_status == 'KIV'){
                        $job_remain_list[$a]['KIV'] = $job_remain_list[$a]['KIV'] + 1;
                    }
                    if($value->assignment_status == 'FINALIZING'){
                        $job_remain_list[$a]['FINALIZING'] = $job_remain_list[$a]['FINALIZING'] + 1;
                    }
                    if($value->assignment_status == 'REVIEWING - TEAM LEAD'){
                        $job_remain_list[$a]['REVIEWING_TEAM_LEAD'] = $job_remain_list[$a]['REVIEWING_TEAM_LEAD'] + 1;
                    }
                    if($value->assignment_status == 'REVIEWING - MANAGER'){
                        $job_remain_list[$a]['REVIEWING_MANAGER'] = $job_remain_list[$a]['REVIEWING_MANAGER'] + 1;
                    }
                    if($value->assignment_status == 'REVIEWING - PARTNER'){
                        $job_remain_list[$a]['REVIEWING_PARTNER'] = $job_remain_list[$a]['REVIEWING_PARTNER'] + 1;
                    }
                    if($value->assignment_status == 'CLEARING REVIEW POINTS'){
                        $job_remain_list[$a]['CLEARING_REVIEW_POINTS'] = $job_remain_list[$a]['CLEARING_REVIEW_POINTS'] + 1;
                    }
                    if($value->assignment_status == 'SENT OUT FOR ADOPTION'){
                        $job_remain_list[$a]['SENT_OUT_FOR_ADOPTION'] = $job_remain_list[$a]['SENT_OUT_FOR_ADOPTION'] + 1;
                    }
                    if($value->assignment_status == 'SIGNED'){
                        $job_remain_list[$a]['SIGNED'] = $job_remain_list[$a]['SIGNED'] + 1;
                    }
                    if($value->assignment_status == 'PENDING DOCS & PAYMENT'){
                        $job_remain_list[$a]['PENDING_DOCS_PAYMENT'] = $job_remain_list[$a]['PENDING_DOCS_PAYMENT'] + 1;
                    }
                    if($value->assignment_status == 'PENDING DOCS'){
                        $job_remain_list[$a]['PENDING_DOCS'] = $job_remain_list[$a]['PENDING_DOCS'] + 1;
                    }
                    if($value->assignment_status == 'PENDING PAYMENT'){
                        $job_remain_list[$a]['PENDING_PAYMENT'] = $job_remain_list[$a]['PENDING_PAYMENT'] + 1;
                    }
                }
            }
        }

        // RETURN ARRAY
        return $job_remain_list;
    }


    public function get_emp_name($emp_id = NULL)
    {
        $employee_list = $this->db->query(" SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users INNER JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id WHERE payroll_employee.id = '".$emp_id."' ");

        $employee_list = $employee_list->result_array();

        return $employee_list[0]['name'];
    }


    public function get_status_updated_list($emp_id = NULL)
    {
        $result_list = array();

        $sun = strtotime("last sunday");
        $sun = date('w', $sun)==date('w') ? $sun+7*86400 : $sun;
        $sat = strtotime(date("Y-m-d",$sun)." +6 days");
        $this_week_sd = date("Y-m-d",$sun);
        $this_week_ed = date("Y-m-d",$sat);

        $employee_list = $this->db->query(" SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users INNER JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id WHERE payroll_employee.id = '".$emp_id."' ");

        $employee_list = $employee_list->result_array();

        $status_changed_list = $this->db->query("SELECT payroll_assignment_status_log.*,payroll_assignment.client_name FROM payroll_assignment_status_log 
            LEFT JOIN payroll_assignment ON payroll_assignment.assignment_id = payroll_assignment_status_log.assignment_id 
            WHERE payroll_assignment.deleted = '0' 
            AND payroll_assignment.PIC LIKE '%assistant%".$employee_list[0]['name']."%]%'
            AND date(payroll_assignment_status_log.date) >= '".$this_week_sd."'
            AND date(payroll_assignment_status_log.date) <= '".$this_week_ed."'
            ORDER BY date DESC ");

        foreach($status_changed_list->result() as $row){
            $from = $this->db->query(" SELECT payroll_assignment_status.assignment_status FROM payroll_assignment_status WHERE payroll_assignment_status.id = '".$row->from_status."' ");

            $from = $from->result();
            $row->from_status = $from[0]->assignment_status;

            $to = $this->db->query(" SELECT payroll_assignment_status.assignment_status FROM payroll_assignment_status WHERE payroll_assignment_status.id = '".$row->to_status."' ");

            $to = $to->result();
            $row->to_status = $to[0]->assignment_status;

        }

        if($status_changed_list->num_rows())
        {
            $status_changed_list = $status_changed_list->result_array();

            for($b=0 ; $b<count($status_changed_list) ; $b++)
            {
                $id          = $status_changed_list[$b]['assignment_id'];
                $client      = $status_changed_list[$b]['client_name'];
                $from_status = $status_changed_list[$b]['from_status'];
                $to_status   = $status_changed_list[$b]['to_status'];
                $date        = date('d F Y', strtotime($status_changed_list[$b]['date']));

                $list = array(
                    'id'          => $id,
                    'client'      => $client,
                    'from_status' => $from_status,
                    'to_status'   => $to_status,
                    'date'        => $date
                );

                array_push($result_list, $list);
            }
        }

        return $result_list;
    }


    public function get_remark_updated_list($emp_id = NULL)
    {
        $result_list = array();

        $sun = strtotime("last sunday");
        $sun = date('w', $sun)==date('w') ? $sun+7*86400 : $sun;
        $sat = strtotime(date("Y-m-d",$sun)." +6 days");
        $this_week_sd = date("Y-m-d",$sun);
        $this_week_ed = date("Y-m-d",$sat);

        $employee_list = $this->db->query(" SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users INNER JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id WHERE payroll_employee.id = '".$emp_id."' ");

        $employee_list = $employee_list->result_array();

        $remark_changed_list = $this->db->query("SELECT payroll_assignment_remark_log.*,payroll_assignment.client_name FROM payroll_assignment_remark_log 
                    LEFT JOIN payroll_assignment ON payroll_assignment.assignment_id = payroll_assignment_remark_log.assignment_id 
                    WHERE payroll_assignment.deleted = '0' 
                    AND payroll_assignment.PIC LIKE '%assistant%".$employee_list[0]['name']."%]%'
                    AND date(payroll_assignment_remark_log.date) >= '".$this_week_sd."'
                    AND date(payroll_assignment_remark_log.date) <= '".$this_week_ed."'
                    ORDER BY date DESC");

        if($remark_changed_list->num_rows())
        {
            $remark_changed_list = $remark_changed_list->result_array();

            for($b=0 ; $b<count($remark_changed_list) ; $b++)
            {
                $id          = $remark_changed_list[$b]['assignment_id'];
                $client      = $remark_changed_list[$b]['client_name'];
                $from_remark = $remark_changed_list[$b]['from_remark'];
                $to_remark   = $remark_changed_list[$b]['to_remark'];
                $date        = date('d F Y', strtotime($remark_changed_list[$b]['date']));

                $list = array(
                    'id'          => $id,
                    'client'      => $client,
                    'from_remark' => $from_remark,
                    'to_remark'   => $to_remark,
                    'date'        => $date
                );

                array_push($result_list, $list);
            }
        }

        return $result_list;
    }


    public function get_completed_list($emp_id = NULL)
    {
        $result_list = array();

        $sun = strtotime("last sunday");
        $sun = date('w', $sun)==date('w') ? $sun+7*86400 : $sun;
        $sat = strtotime(date("Y-m-d",$sun)." +6 days");
        $this_week_sd = date("Y-m-d",$sun);
        $this_week_ed = date("Y-m-d",$sat);

        $employee_list = $this->db->query(" SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users INNER JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id WHERE payroll_employee.id = '".$emp_id."' ");

        $employee_list = $employee_list->result_array();

        $completed_list = $this->db->query("SELECT payroll_assignment.*,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                         LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
                         LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id 
                         LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                         WHERE payroll_assignment.deleted = '0' 
                         AND payroll_assignment.status = '10'
                         AND payroll_assignment.PIC LIKE '%assistant%".$employee_list[0]['name']."%]%'
                         AND payroll_assignment.complete_date >= '".$this_week_sd."'
                         AND payroll_assignment.complete_date <= '".$this_week_ed."'");

        if($completed_list->num_rows())
        {
            $completed_list = $completed_list->result_array();

            for($b=0 ; $b<count($completed_list) ; $b++)
            {
                $id     = $completed_list[$b]['assignment_id'];
                $client = $completed_list[$b]['client_name'];
                $FYE    = date('d F Y', strtotime($completed_list[$b]['FYE']));
                $job    = $completed_list[$b]['job'];
                $date   = date('d F Y', strtotime($completed_list[$b]['last_updated']));

                $list = array(
                    'id'         => $id,
                    'client'     => $client,
                    'fye'        => $FYE,
                    'job'        => $job,
                    'date'       => $date
                );

                array_push($result_list, $list);
            }
        }

        return $result_list;
    }


    public function get_emp_job_remain_list($emp_id = NULL)
    {
        $emp_job_remain_list = array();

        $employee_list = $this->db->query(" SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users INNER JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id WHERE payroll_employee.id = '".$emp_id."' ");

        $employee_list = $employee_list->result_array();

        $result_list = $this->db->query("SELECT payroll_assignment.*,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
            LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
            LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id 
            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id
            WHERE payroll_assignment.deleted = '0' 
            AND (
                    (payroll_assignment.status NOT IN (10) AND payroll_assignment.type_of_job NOT IN(13)) 
                    OR 
                    (payroll_assignment.status NOT IN (15,17) AND payroll_assignment.type_of_job IN(13))
                )

            AND payroll_assignment.PIC LIKE '%assistant%".$employee_list[0]['name']."%]%'
            ORDER BY payroll_assignment_status.list_order");

        if($result_list->num_rows())
        {
            $result_list = $result_list->result_array();

            for($b=0 ; $b<count($result_list) ; $b++)
            {
                $id     = $result_list[$b]['assignment_id'];
                $client = $result_list[$b]['client_name'];
                $FYE    = date('d F Y', strtotime($result_list[$b]['FYE']));
                $job    = $result_list[$b]['job'];
                $status = $result_list[$b]['assignment_status'];
                $remark = $result_list[$b]['remark'];

                $list = array(
                    'id'         => $id,
                    'client'     => $client,
                    'fye'        => $FYE,
                    'job'        => $job,
                    'status'     => $status,
                    'remark'     => $remark,

                );

                array_push($emp_job_remain_list, $list);
            }
        }

        return $emp_job_remain_list;
    }
    
}
?>