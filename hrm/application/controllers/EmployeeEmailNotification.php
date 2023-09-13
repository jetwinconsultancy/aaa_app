<?php define( 'APPLICATION_LOADED', true );


class EmployeeEmailNotification extends CI_Controller {

    public function message($to = 'World') {
        echo "OLA";
    }

    public function new_declaration_notification() {

        $today = date("Y-m-d", strtotime("today"));

        // EXCEPT YEO KARN LEE & TAY CHEW SEE
        $q = $this->db->query("SELECT payroll_employee.id, payroll_employee.name, users.email FROM payroll_employee INNER JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id LEFT JOIN users ON users.id = payroll_user_employee.user_id WHERE payroll_employee.id NOT IN (38,44) AND date_cessation is null OR DATE(date_cessation) > DATE('".$today."')");

        foreach($q->result() as $row)
        {
            // SENDINBLUE EMAIL
            $this->load->library('parser');
            $parse_data = array('employee_name'  => $row->name);
            $msg        = file_get_contents('./application/modules/employee/email_templates/declaration_email_notification.html');
            $subject    = 'New Declaration Notification';
            $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
            $to_email   = json_encode(array(array("email"=> $row->email)));
            $cc         = null;
            $message    = $this->parser->parse_string($msg, $parse_data,true);
            $this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null);
        }
    }

    public function new_declaration_check() {

        $thisYear = date("Y");

        if($thisYear != '2020')
        {
            // $date = date("Y-m-d", strtotime($thisYear."-01-01"));
            $date = date("Y-m-d");

            $not_confirm_list = array();
            $html = '';

            // EXCEPT YEO KARN LEE & TAY CHEW SEE
            $q = $this->db->query("SELECT payroll_employee.id, payroll_employee.name FROM payroll_employee INNER JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id LEFT JOIN users ON users.id = payroll_user_employee.user_id WHERE payroll_employee.id NOT IN (38,44) AND payroll_employee.employee_status_id IN (1,2) OR (payroll_employee.employee_status_id IN (3,4) AND DATE(date_cessation) > DATE('".$date."'))");

            foreach($q->result() as $row)
            {
                $q2 = $this->db->query("SELECT * FROM payroll_event_info WHERE deleted = '0' AND employee_id = '".$row->id."' AND event = '10' AND year(date) = '".$thisYear."'");

                if ($q2->num_rows() == 0)
                {
                    array_push($not_confirm_list, $row->name);
                }
            }

            if(count($not_confirm_list) != 0)
            {
                $table = '<lable><strong>Pending Confirm :</strong></lable><table>';

                for($a=0;$a<count($not_confirm_list);$a++)
                {
                    $table .= '<tr>';

                    $table .= '<td>';
                    $table .= $not_confirm_list[$a];
                    $table .= '</td>';

                    $table .= '</tr>';
                }

                $table .= '</table>';

                $html .= $table;

                // SENDINBLUE EMAIL
                $this->load->library('parser');
                $parse_data = array('html'  => $html);
                $msg        = file_get_contents('./application/modules/employee/email_templates/declaration_check_notification.html');
                $subject    = 'New Declaration Check (Not Confirm List)';
                $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
                $to_email   = json_encode(array(array("email"=> "woellywilliam@aaa-global.com"),array("email"=> "penny@aaa-global.com")));
                $cc         = null;
                $message    = $this->parser->parse_string($msg, $parse_data,true);
                $this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null);
            }
        }
    }

    public function manual_email_function() {

        $today = date("Y-m-d", strtotime("today"));

        $q = $this->db->query("SELECT payroll_employee.id, payroll_employee.name, users.email FROM payroll_employee INNER JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id LEFT JOIN users ON users.id = payroll_user_employee.user_id WHERE date_cessation is null OR DATE(date_cessation) > DATE('".$today."')");

        foreach($q->result() as $row)
        {
            // SENDINBLUE EMAIL
            $this->load->library('parser');
            $parse_data = array('employee_name'  => $row->name);
            $msg        = file_get_contents('./application/modules/employee/email_templates/manual_email_notification.html');
            $subject    = '';
            $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
            $to_email   = json_encode(array(array("email"=> $row->email)));
            $cc         = null;
            $message    = $this->parser->parse_string($msg, $parse_data, true);
            $this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null);
        }
    }
}