<?php define( 'APPLICATION_LOADED', true );

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
require_once('assets/vendor/tcpdf/tcpdf.php');

class AssignmentEmailNotification extends CI_Controller {

    public function message($to = 'World') {
        echo "OLA" . PHP_EOL;
    }

    public function testing($to = 'World') {
        $to_email   = json_encode(array(array("email"=> "woellywilliam@aaa-global.com"),array("email"=> "penny@aaa-global.com")));
        print_r($to_email);

        $emails = array();
        $manager_email = array("email"=> "woellywilliam@aaa-global.com");
        array_push($emails, $manager_email);
        $manager_email = array("email"=> "penny@aaa-global.com");
        array_push($emails, $manager_email);

        print_r(json_encode($emails));
    }


// COMPILE REPORT (EACH STAFF JOBS COMPLETED FROM JUNE TO NOW, WITH FEE AND ACTUAL HOURS SPEND) ------------------------------------
    public function compile_report() {

        $spreadsheet = new Spreadsheet();
        $form_data = $this->input->post();
        // $spreadsheet->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold( true );
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("./excel/Compile_Report.xlsx");
        $sheet = $spreadsheet->getActiveSheet();


        $employee_list = $this->db->query(" SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users INNER JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id WHERE payroll_employee.employee_status_id NOT IN (3,4) AND payroll_employee.id NOT IN (5) ORDER BY name ");

        $employee_list = $employee_list->result_array();

        $i = 2;
        $result = array();


        for($a=0 ; $a<count($employee_list) ; $a++){

            $assignment_list = $this->db->query("SELECT payroll_assignment.assignment_id,payroll_assignment.client_name,payroll_assignment.PIC,payroll_assignment_completed.audit_fee,payroll_assignment.complete_date,payroll_assignment_jobs.type_of_job, payroll_assignment.fye
                                                    FROM payroll_assignment 
                                                    LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
                                                    LEFT JOIN payroll_assignment_completed ON payroll_assignment_completed.payroll_assignment_id = payroll_assignment.id
                                                    LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id  
                                                    WHERE payroll_assignment.deleted = '0' 
                                                    AND payroll_assignment.status = '10'
                                                    AND payroll_assignment.complete_date >= date('2020-06-01')
                                                    AND payroll_assignment.PIC LIKE '%".$employee_list[$a]['name']."%'");


            foreach($assignment_list->result() as $data){

                $hours = $this->get_hour_spend($employee_list[$a]['name'], $data->client_name, $data->type_of_job, $data->fye, $data->complete_date);

                json_encode($hours);

                foreach( range('A', 'F') as $v ){
                    switch( $v ) {
                        case 'A': {
                            $value = $data->assignment_id;
                            break;
                        }
                        case 'B': {
                            $value = $data->client_name;
                            break;
                        }
                        case 'C': {
                            $value = $employee_list[$a]['name'];
                            break;
                        }
                        case 'D': {
                            $value = $data->audit_fee;
                            break;
                        }
                        case 'E': {
                            $value = $hours;
                            break;
                        }
                        case 'F': {
                            $value = $data->complete_date;
                            break;
                        }
                    }
                    $spreadsheet->getActiveSheet()->setCellValue($v.$i, $value);
                }
                $i++;
            }

        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'excel/assignment/Report.xlsx';
        $response = $filename;

        $writer->save($filename);
        chmod($_SERVER['DOCUMENT_ROOT'].'hrm/excel/assignment/Report.xlsx',0644);
        echo $response;
    }

    public function get_hour_spend($name, $client, $type_of_job, $fye, $date) {

        $hours = "";

        if($fye != '')
        {
            $fye = strtoupper(date("d M Y", strtotime($fye)));
        }
        else
        {
            $fye = '';
        }

        $timesheet = $this->db->query(" SELECT timesheet.content FROM users LEFT JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id LEFT JOIN timesheet ON timesheet.employee_id = payroll_employee.id WHERE CONCAT(users.first_name , ' ' , users.last_name) = '".$name."' AND month(timesheet.month) = month('".$date."')");

        if($timesheet->num_rows() > 0)
        {
            $timesheet_result = $timesheet->result();
            $timesheet_content = json_decode($timesheet_result[0]->content);

            for($a = 0; $a<count($timesheet_content); $a++)
            {
                if($timesheet_content[$a][0] == '*'.$client && $timesheet_content[$a][1] == $type_of_job && $timesheet_content[$a][2] == $fye)
                {
                    $hours = $timesheet_content[$a][count($timesheet_content[$a])-1];
                    return $hours;
                    break;
                }
            }
        }

        return $hours;
    }
// COMPILE REPORT (EACH STAFF JOBS COMPLETED FROM JUNE TO NOW, WITH FEE AND ACTUAL HOURS SPEND) ------------------------------------

    public function job_due_notification() 
    {
        $yesterday_date = date('Y-m-d', strtotime('yesterday'));
        $check_assignment_overdue = $this->db->query(" SELECT * FROM payroll_assignment WHERE expected_completion_date = '".$yesterday_date."' AND deleted = '0' AND status NOT IN (10) ");

        if($check_assignment_overdue->num_rows())
        {
            $assignment_overdue = $check_assignment_overdue->result_array();

            for($a = 0; $a < count($assignment_overdue); $a++)
            {   
                $manager_name = json_decode($assignment_overdue[$a]['PIC'])->manager;
                $leader_name  = json_decode($assignment_overdue[$a]['PIC'])->leader;
                $assistant_name_list  = json_decode($assignment_overdue[$a]['PIC'])->assistant;

                $manager_info = $this->db->query(" SELECT * FROM users WHERE concat(first_name, ' ', last_name) LIKE '".$manager_name."' ");
                $manager_info = $manager_info->result_array();

                $leader_info = $this->db->query(" SELECT * FROM users WHERE concat(first_name, ' ', last_name) LIKE '".$leader_name."' ");
                $leader_info = $leader_info->result_array();

                $assistant_info = array();

                for($b = 0; $b < sizeof($assistant_name_list); $b++){
                    $assistant_list = $this->db->query(" SELECT * FROM users WHERE concat(first_name, ' ', last_name) LIKE '".$assistant_name_list[$b]."' ");
                    array_push($assistant_info, $assistant_list->result_array());
                }

                $to_list = array();
                $cc_list = array();

                $manager_email = array("email"=> $manager_info[0]['email']);
                array_push($cc_list, $manager_email);

                if(json_encode($manager_email['email']) != '"penny@aaa-global.com"'){
                    $manager_email = array("email"=> 'penny@aaa-global.com');
                    array_push($cc_list, $manager_email);
                }

                $leader_email  = $leader_info[0]['email'];
                $assistant_email = "";

                for($c = 0 ; $c < sizeof($assistant_info); $c++){
                    if($c + 1 == sizeof($assistant_info)){
                        $assistant_email .= $assistant_info[$c][0]['email'];
                    }else{ 
                        $assistant_email .= $assistant_info[$c][0]['email'] .",";
                    }
                }

                $to_email = $leader_email .",". $assistant_email;
                $to_email = implode(',',array_unique(explode(',', $to_email)));
                $temp = explode(',', $to_email);
                for($d = 0 ; $d < sizeof($temp); $d++){
                    array_push($to_list, array("email"=> $temp[$d]));
                }

                $this->load->library('parser');
                $parse_data = array(
                    'assignment_code'  => $assignment_overdue[$a]['assignment_id'],
                    'client_name'      => $assignment_overdue[$a]['client_name'],
                );
                $msg = file_get_contents('./application/modules/assignment/email_templates/job_over_due_notification.html');
                $subject = 'Assignment Overdue Notification - '.$assignment_overdue[$a]['client_name'].'';
                $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
                $to_email   = json_encode($to_list);
                $cc         = json_encode($cc_list);
                $message    = $this->parser->parse_string($msg, $parse_data, true);
                $this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null);
            }
        }
    }

    public function three_days_remaining_notification() {

        $three_days_remain = date('Y-m-d', strtotime('today + 3 days'));
        $check_assignment_overdue = $this->db->query(" SELECT * FROM payroll_assignment WHERE expected_completion_date = '".$three_days_remain."' AND deleted = '0' AND status NOT IN (10) ");

        if($check_assignment_overdue->num_rows())
        {
            $assignment_overdue = $check_assignment_overdue->result_array();

            for($a = 0; $a < count($assignment_overdue); $a++)
            {
                $manager_name = json_decode($assignment_overdue[$a]['PIC'])->manager;
                $leader_name  = json_decode($assignment_overdue[$a]['PIC'])->leader;
                $assistant_name_list  = json_decode($assignment_overdue[$a]['PIC'])->assistant;

                $manager_info = $this->db->query(" SELECT * FROM users WHERE concat(first_name, ' ', last_name) LIKE '".$manager_name."' ");
                $manager_info = $manager_info->result_array();

                $leader_info = $this->db->query(" SELECT * FROM users WHERE concat(first_name, ' ', last_name) LIKE '".$leader_name."' ");
                $leader_info = $leader_info->result_array();

                $assistant_info = array();

                for($b = 0; $b < sizeof($assistant_name_list); $b++){
                    $assistant_list = $this->db->query(" SELECT * FROM users WHERE concat(first_name, ' ', last_name) LIKE '".$assistant_name_list[$b]."' ");
                    $assistant_info[$b] = $assistant_list->result_array();
                }

                $to_list = array();
                $cc_list = array();

                $manager_email = array("email"=> $manager_info[0]['email']);
                array_push($cc_list, $manager_email);

                if(json_encode($manager_email['email']) != '"penny@aaa-global.com"'){
                    $manager_email = array("email"=> 'penny@aaa-global.com');
                    array_push($cc_list, $manager_email);
                }

                $leader_email  = $leader_info[0]['email'];
                $assistant_email = "";

                for($c = 0 ; $c < sizeof($assistant_info); $c++){
                    if($c + 1 == sizeof($assistant_info)){
                        $assistant_email .= $assistant_info[$c][0]['email'];
                    }else{ 
                        $assistant_email .= $assistant_info[$c][0]['email'] .",";
                    }
                }

                $to_email = $leader_email .",". $assistant_email;
                $to_email = implode(',',array_unique(explode(',', $to_email)));
                $temp = explode(',', $to_email);
                for($d = 0 ; $d < sizeof($temp); $d++){
                    array_push($to_list, array("email"=> $temp[$d]));
                }


                $this->load->library('parser');
                $parse_data = array(
                    'assignment_code'  => $assignment_overdue[$a]['assignment_id'],
                    'client_name'      => $assignment_overdue[$a]['client_name'],
                );
                $msg = file_get_contents('./application/modules/assignment/email_templates/completion_date_notification.html');
                $subject = 'Assignment Completion Date Notification - '.$assignment_overdue[$a]['client_name'].'';
                $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
                $to_email   = json_encode($to_list);
                $cc         = json_encode($cc_list);
                $message    = $this->parser->parse_string($msg, $parse_data,true);
                $this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null);
            }
        }
    }

    public function assignment_weekly_notification() {

        $office_list = $this->db->query(" SELECT * FROM payroll_offices WHERE id != 1 AND office_deleted = 0 ");
        $office_list = $office_list->result();

        foreach ($office_list as $key => $value) {

            $html = '';
            $office_id = $value->id;
            $office_name = $value->office_name;

            $employee_list = $this->db->query(" SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users INNER JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id WHERE payroll_employee.employee_status_id NOT IN (3,4) AND payroll_employee.id NOT IN (5) AND payroll_employee.office = '".$office_id."' ORDER BY name ");

            $employee_list = $employee_list->result_array();

            for($a=0 ; $a<count($employee_list) ; $a++){

                // ASSIGNMENT IN-PROGRESS -------------------------------------------------------------------------------------------------------------------------------
                $A_list     = array();
                $table_list_1 = '';
                $two_week_before = date('Y-m-d', strtotime('today - 14 days'));

                $assignment_list = $this->db->query("SELECT payroll_assignment.*,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                         LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
                         LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id 
                         LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                         WHERE payroll_assignment.deleted = '0' 
                         AND payroll_assignment.status != '10'
                         AND payroll_assignment.PIC LIKE '%assistant%".$employee_list[$a]['name']."%]%'
                         AND payroll_assignment.last_updated > '".$two_week_before."'");

                if($assignment_list->num_rows())
                {
                    $assignment_list = $assignment_list->result_array();

                    $table_list_1 .= '<p><strong><u>'.$employee_list[$a]['name'].'</u></strong></p>';

                    for($b=0 ; $b<count($assignment_list) ; $b++)
                    {
                        $id     = $assignment_list[$b]['assignment_id'];
                        $client = $assignment_list[$b]['client_name'];
                        $status = $assignment_list[$b]['assignment_status'];
                        $remark = $assignment_list[$b]['remark'];

                        $list = array(
                            'id'         => $id,
                            'client'     => $client,
                            'status'     => $status,
                            'remark'     => $remark,
                        );

                        array_push($A_list, $list);
                    }

                    $table = '<lable>Assignment In-Progress :</lable><table><tr><th>ID</th><th>Client</th><th>Status</th><th>Remark</th></tr>';

                    for($c=0 ; $c<count($A_list) ; $c++)
                    {
                        $table .= '<tr>';

                        $table .= '<td>';
                        $table .= $A_list[$c]['id'];
                        $table .= '</td>';

                        $table .= '<td>';
                        $table .= $A_list[$c]['client'];
                        $table .= '</td>';

                        $table .= '<td>';
                        $table .= $A_list[$c]['status'];
                        $table .= '</td>';

                        $table .= '<td>';
                        $table .= $A_list[$c]['remark'];
                        $table .= '</td>';

                        $table .= '</tr>';
                    }

                    $table .= '</table>';

                    $table_list_1 .= $table;
                    $html .= $table_list_1;
                }

                // COMPLETED ASSIGNMENT ---------------------------------------------------------------------------------------------------------------------------------
                $B_list     = array();
                $table_list_2 = '';
                $today = date('Y-m-d', strtotime('today'));
                $one_week_before = date('Y-m-d', strtotime('today - 7 days'));

                $completed_list = $this->db->query("SELECT payroll_assignment.*,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                         LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
                         LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id 
                         LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                         WHERE payroll_assignment.deleted = '0' 
                         AND payroll_assignment.status = '10'
                         AND payroll_assignment.PIC LIKE '%assistant%".$employee_list[$a]['name']."%]%'
                         AND payroll_assignment.complete_date >= '".$one_week_before."'
                         AND payroll_assignment.complete_date <= '".$today."'");

                if($completed_list->num_rows())
                {
                    $completed_list = $completed_list->result_array();

                    if(strpos($html, $employee_list[$a]['name']) == false)
                    {
                        $table_list_2 .= '<p><strong><u>'.$employee_list[$a]['name'].'</u></strong></p>';
                        $table = '<lable>Assignment Completed :</lable><table><tr><th>ID</th><th>Client</th></tr>';
                    }
                    else{
                        $table = '<br><lable>Assignment Completed :</lable><table><tr><th>ID</th><th>Client</th><th>Date</th></tr>';
                    }

                    for($b=0 ; $b<count($completed_list) ; $b++)
                    {
                        $id     = $completed_list[$b]['assignment_id'];
                        $client = $completed_list[$b]['client_name'];
                        $status = $completed_list[$b]['assignment_status'];
                        $remark = $completed_list[$b]['remark'];
                        $date   = date('d F Y', strtotime($completed_list[$b]['last_updated']));

                        $list = array(
                            'id'         => $id,
                            'client'     => $client,
                            'status'     => $status,
                            'remark'     => $remark,
                            'date'       => $date
                        );

                        array_push($B_list, $list);
                    }

                    for($c=0 ; $c<count($B_list) ; $c++)
                    {
                        $table .= '<tr>';

                        $table .= '<td>';
                        $table .= $B_list[$c]['id'];
                        $table .= '</td>';

                        $table .= '<td>';
                        $table .= $B_list[$c]['client'];
                        $table .= '</td>';

                        $table .= '<td>';
                        $table .= $B_list[$c]['date'];
                        $table .= '</td>';

                        $table .= '</tr>';
                    }

                    $table .= '</table>';

                    $table_list_2 .= $table;
                    $html .= $table_list_2;
                }

                // STATUS CHANGED ---------------------------------------------------------------------------------------------------------------------------------------
                $C_list     = array();
                $table_list_3 = '';
                $today = date('Y-m-d', strtotime('today'));
                $one_week_before = date('Y-m-d', strtotime('today - 7 days'));

                $status_changed_list = $this->db->query("SELECT payroll_assignment_status_log.*,payroll_assignment.client_name FROM payroll_assignment_status_log 
                    LEFT JOIN payroll_assignment ON payroll_assignment.assignment_id = payroll_assignment_status_log.assignment_id 
                    WHERE payroll_assignment.deleted = '0' 
                    AND payroll_assignment.PIC LIKE '%assistant%".$employee_list[$a]['name']."%]%'
                    AND date(payroll_assignment_status_log.date) >= '".$one_week_before."'
                    AND date(payroll_assignment_status_log.date) <= '".$today."'");

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

                    if(strpos($html, $employee_list[$a]['name']) == false)
                    {
                        $table_list_3 .= '<p><strong><u>'.$employee_list[$a]['name'].'</u></strong></p>';
                        $table = '<lable>Status Changed :</lable><table><tr><th>ID</th><th>Client</th></tr>';
                    }
                    else{
                        $table = '<br><lable>Status Changed :</lable><table><tr><th>ID</th><th>Client</th><th>Status Before</th><th>Status After</th><th>Date</th></tr>';
                    }

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

                        array_push($C_list, $list);
                    }

                    for($c=0 ; $c<count($C_list) ; $c++)
                    {
                        $table .= '<tr>';

                        $table .= '<td>';
                        $table .= $C_list[$c]['id'];
                        $table .= '</td>';

                        $table .= '<td>';
                        $table .= $C_list[$c]['client'];
                        $table .= '</td>';

                        $table .= '<td>';
                        $table .= $C_list[$c]['from_status'];
                        $table .= '</td>';

                        $table .= '<td>';
                        $table .= $C_list[$c]['to_status'];
                        $table .= '</td>';

                        $table .= '<td>';
                        $table .= $C_list[$c]['date'];
                        $table .= '</td>';

                        $table .= '</tr>';
                    }

                    $table .= '</table>';

                    $table_list_3 .= $table;
                    $html .= $table_list_3;
                }

                // REMARK CHANGED ---------------------------------------------------------------------------------------------------------------------------------------
                $D_list     = array();
                $table_list_4 = '';
                $today = date('Y-m-d', strtotime('today'));
                $one_week_before = date('Y-m-d', strtotime('today - 7 days'));

                $remark_changed_list = $this->db->query("SELECT payroll_assignment_remark_log.*,payroll_assignment.client_name FROM payroll_assignment_remark_log 
                    LEFT JOIN payroll_assignment ON payroll_assignment.assignment_id = payroll_assignment_remark_log.assignment_id 
                    WHERE payroll_assignment.deleted = '0' 
                    AND payroll_assignment.PIC LIKE '%assistant%".$employee_list[$a]['name']."%]%'
                    AND date(payroll_assignment_remark_log.date) >= '".$one_week_before."'
                    AND date(payroll_assignment_remark_log.date) <= '".$today."'");


                if($remark_changed_list->num_rows())
                {
                    $remark_changed_list = $remark_changed_list->result_array();

                    if(strpos($html, $employee_list[$a]['name']) == false)
                    {
                        $table_list_4 .= '<p><strong><u>'.$employee_list[$a]['name'].'</u></strong></p>';
                        $table = '<lable>Remark Changed :</lable><table><tr><th>ID</th><th>Client</th></tr>';
                    }
                    else{
                        $table = '<br><lable>Remark Changed :</lable><table><tr><th>ID</th><th>Client</th><th>Remark Before</th><th>Remark After</th><th>Date</th></tr>';
                    }

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

                        array_push($D_list, $list);
                    }

                    for($c=0 ; $c<count($D_list) ; $c++)
                    {
                        $table .= '<tr>';

                        $table .= '<td>';
                        $table .= $D_list[$c]['id'];
                        $table .= '</td>';

                        $table .= '<td>';
                        $table .= $D_list[$c]['client'];
                        $table .= '</td>';

                        $table .= '<td>';
                        $table .= $D_list[$c]['from_remark'];
                        $table .= '</td>';

                        $table .= '<td>';
                        $table .= $D_list[$c]['to_remark'];
                        $table .= '</td>';

                        $table .= '<td>';
                        $table .= $D_list[$c]['date'];
                        $table .= '</td>';

                        $table .= '</tr>';
                    }

                    $table .= '</table>';

                    $table_list_4 .= $table;
                    $html .= $table_list_4;
                }

                // ASSIGNMENT NOT CHANGE --------------------------------------------------------------------------------------------------------------------------------
                $E_list     = array();
                $table_list_5 = '';
                $two_week_before = date('Y-m-d', strtotime('today - 14 days'));

                $notChanged_list = $this->db->query("SELECT payroll_assignment.*,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                         LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
                         LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id 
                         LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                         WHERE payroll_assignment.deleted = '0' 
                         AND payroll_assignment.status != '10'
                         AND payroll_assignment.PIC LIKE '%assistant%".$employee_list[$a]['name']."%]%'
                         AND payroll_assignment.last_updated <= '".$two_week_before."'");

                if($notChanged_list->num_rows())
                {
                    $notChanged_list = $notChanged_list->result_array();

                    if(strpos($html, $employee_list[$a]['name']) == false)
                    {
                        $table_list_5 .= '<p><strong><u>'.$employee_list[$a]['name'].'</u></strong></p>';
                        $table = '<lable>Assignment Not Changed Past 2 Week :</lable><table><tr><th>ID</th><th>Client</th><th>Status</th><th>Remark</th></tr>';
                    }
                    else{
                        $table = '<br><lable>Assignment Not Changed Past 2 Week :</lable><table><tr><th>ID</th><th>Client</th><th>Status</th><th>Remark</th></tr>';
                    }

                    for($b=0 ; $b<count($notChanged_list) ; $b++)
                    {
                        $id     = $notChanged_list[$b]['assignment_id'];
                        $client = $notChanged_list[$b]['client_name'];
                        $status = $notChanged_list[$b]['assignment_status'];
                        $remark = $notChanged_list[$b]['remark'];

                        $list = array(
                            'id'         => $id,
                            'client'     => $client,
                            'status'     => $status,
                            'remark'     => $remark,
                        );

                        array_push($E_list, $list);
                    }

                    for($c=0 ; $c<count($E_list) ; $c++)
                    {
                        $table .= '<tr>';

                        $table .= '<td>';
                        $table .= $E_list[$c]['id'];
                        $table .= '</td>';

                        $table .= '<td>';
                        $table .= $E_list[$c]['client'];
                        $table .= '</td>';

                        $table .= '<td>';
                        $table .= $E_list[$c]['status'];
                        $table .= '</td>';

                        $table .= '<td>';
                        $table .= $E_list[$c]['remark'];
                        $table .= '</td>';

                        $table .= '</tr>';
                    }

                    $table .= '</table>';

                    $table_list_5 .= $table;
                    $html .= $table_list_5;
                }
            }

            $this->load->library('parser');
            $parse_data = array(
                'in_progress'  => $html
            );

            $msg = file_get_contents('./application/modules/assignment/email_templates/assignment_weekly_report.html');
            $message = $this->parser->parse_string($msg, $parse_data);

            $subject_toDate = date('d F Y', strtotime('today'));
            $subject_fromDate = date('d F Y', strtotime('today - 7 days'));
            $subject = ''.$office_name.' : Assignment Weekly Report ('.$subject_fromDate.' - '.$subject_toDate.')';

            $email_detail['email'] = array(array("email"=> "woellywilliam@aaa-global.com"), array("email" => "penny@aaa-global.com"));
            $email_detail['from_email'] = array("name" => 'HRM System', "email" => "admin@bizfiles.com.sg");
            // $email_detail['cc'] = json_encode(array(array("email" => $cc_email)));

            $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');

              $apiInstance = new SendinBlue\Client\Api\SMTPApi(
                  new GuzzleHttp\Client(),
                  $config
              );

              $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail(); // \SendinBlue\Client\Model\SendSmtpEmail | Values to send a transactional email
              $sendSmtpEmail['subject'] = $subject;
              $sender_email = $email_detail['from_email'];
              $sendSmtpEmail['sender'] = $sender_email;
              $sendSmtpEmail['to'] = $email_detail['email'];
              // if($email_queue_info[$i]['cc'] != null)
              // {
              //   $sendSmtpEmail['cc'] = json_decode($email_queue_info[$i]['cc'], true);
              // }
              $sendSmtpEmail['htmlContent'] = $message;

              // $attachment['content'] = base64_encode(file_get_contents($_SERVER["DOCUMENT_ROOT"] .'/secretary/pdf/invoice/AA-20200013.pdf'));
              // $attachment['name'] = "AA-20200013.pdf";
              //array_push($pdfDocPath, json_decode($email_queue_info[$i]['attachment'], true));
              // $sendSmtpEmail['attachment'] = json_decode($email_queue_info[$i]['attachment'], true);
              
              try {
                  $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
                  if ($result) 
                  {
                      // $email_queue['sended'] = 1;
                      $email_queue['sendInBlueResult'] = $result;
                      // $this->db->update("email_queue",$email_queue,array("id" => $email_queue_info[$i]['id']));
                      echo 'Your Email has successfully been sent.';
                  }
              } catch (Exception $e) {
                  echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
              }

        }
    }
}