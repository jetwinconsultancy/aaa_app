<?php define( 'APPLICATION_LOADED', true );


class TimesheetEmailNotification extends CI_Controller {

    public function message($to = 'World') {
        echo "Hello {$to}!" . PHP_EOL;
    }

    public function submission_date_notification() {

        $today = date("Y-m-d", strtotime("today"));
        $last_date = date("Y-m-d", strtotime("last day of this month"));
        $last_day = date('w', strtotime($last_date));
        // $email = [];

        if($last_day == 6){
            $last_date = date('Y-m-d', strtotime($last_date. ' - 1 days'));
        }
        else if($last_day == 0){
            $last_date = date('Y-m-d', strtotime($last_date. ' - 2 days'));
        }

        $q = $this->db->query("SELECT users.email FROM timesheet LEFT JOIN payroll_user_employee ON payroll_user_employee.employee_id = timesheet.employee_id LEFT JOIN users ON users.id = payroll_user_employee.user_id WHERE month(timesheet.month) = month(CURRENT_TIMESTAMP) AND timesheet.status_id = 1 GROUP BY users.email");

        if($today==$last_date)
        { 
            foreach($q->result() as $row)
            {
                // SENDINBLUE EMAIL
                $this->load->library('parser');
                $parse_data = array('month'  => date("F", strtotime("today")));
                $msg        = file_get_contents('./application/modules/timesheet/email_templates/timesheet_submition_notification.html');
                $subject    = 'Timesheet Submition Notification';
                $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
                $to_email   = json_encode(array(array("email"=> $row->email)));
                $cc         = null;
                $message    = $this->parser->parse_string($msg, $parse_data,true);
                $this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null);
            }
        }
    }
}