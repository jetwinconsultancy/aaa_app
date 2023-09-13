<?php define( 'APPLICATION_LOADED', true );


class WorkPassExpiringCheck extends CI_Controller {

    public function message($to = 'World') {
        echo "OLA TEST {$to}!" . PHP_EOL;
    }

    public function PassExpiring() {

        // PASS EXPIRING CHECK
        $query = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS fullname , payroll_employee.workpass AS pass , payroll_employee.pass_expire AS expiry_date, datediff(payroll_employee.pass_expire,CURDATE()) AS remaining_days, users.email, users.manager_in_charge
            FROM payroll_employee 
            LEFT JOIN payroll_user_employee ON payroll_employee.id = payroll_user_employee.employee_id 
            LEFT JOIN users ON payroll_user_employee.user_id = users.id 
            WHERE DATE_ADD(CURDATE(), INTERVAL 6 month) >= payroll_employee.pass_expire 
            AND (payroll_employee.employee_status_id IN (1,2) OR (payroll_employee.employee_status_id IN (3,4) AND DATE(payroll_employee.date_cessation) >= DATE(CURRENT_DATE)))");

        if($query->num_rows() > 0) 
        {
            foreach($query->result_array() as $employee)
            {
                if($employee['manager_in_charge'] == 0){
                    $manager_id = 67;
                }else if($employee['manager_in_charge'] == 91){
                    $manager_id = 79;
                }else{
                    $manager_id = $employee['manager_in_charge'];
                }

                $manager_email = $this->db->query(" SELECT *,CONCAT(first_name , ' ' , last_name) AS fullname FROM users WHERE id = '".$manager_id."' ");
                $manager_email = $manager_email->result();
                $manager_email = $manager_email[0]->email;

                $d1 = new DateTime(date('Y-m-d'));
                $d2 = new DateTime($employee['expiry_date']);
                $interval     = $d1->diff($d2);
                $diffInDays   = $interval->d;
                $diffInMonths = $interval->m;

                if($diffInMonths <= 6 && $diffInMonths >= 3)
                {
                    if(date('d') == 1)
                    {
                        // SENDINBLUE EMAIL
                        $this->load->library('parser');

                        $parse_data = array(
                            'employee_name' => $employee['fullname'],
                            'expire_date'   => date('d F Y', strtotime(str_replace('/', '-', $employee['expiry_date']))), 
                            'remain_Month'  => $diffInMonths,
                            'remain_day'    => $diffInDays,
                        );

                        $msg        = file_get_contents('./application/modules/employee/email_templates/work_pass_expiring_notification.html');
                        $subject    = 'Work Pass Expiring';
                        $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
                        $to_email   = json_encode(array(array("email"=> $employee['email'])));
                        $cc         = json_encode(array(array("email"=> $manager_email)));
                        $message    = $this->parser->parse_string($msg, $parse_data,true);
                        $this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null);
                        // send_by_sendinblue($subject, $from_email, $to_email, $cc = null, $message, $attachment = null)
                    }
                }
                else if($diffInMonths <= 3 && $diffInMonths >= 2)
                {
                    if(date('Y-m-d') == date('Y-m-d', strtotime("this week")))
                    {
                        // SENDINBLUE EMAIL
                        $this->load->library('parser');

                        $parse_data = array(
                            'employee_name' => $employee['fullname'],
                            'expire_date'   => date('d F Y', strtotime(str_replace('/', '-', $employee['expiry_date']))), 
                            'remain_Month'  => $diffInMonths,
                            'remain_day'    => $diffInDays,
                        );

                        $msg        = file_get_contents('./application/modules/employee/email_templates/work_pass_expiring_notification.html');
                        $subject    = 'Work Pass Expiring';
                        $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
                        $to_email   = json_encode(array(array("email"=> $employee['email'])));
                        $cc         = json_encode(array(array("email"=> $manager_email)));
                        $message    = $this->parser->parse_string($msg, $parse_data,true);
                        $this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null);
                        // send_by_sendinblue($subject, $from_email, $to_email, $cc = null, $message, $attachment = null)
                    }
                }
                else if($diffInMonths <= 2)
                {
                    // SENDINBLUE EMAIL
                    $this->load->library('parser');

                    $parse_data = array(
                        'employee_name' => $employee['fullname'],
                        'expire_date'   => date('d F Y', strtotime(str_replace('/', '-', $employee['expiry_date']))), 
                        'remain_Month'  => $diffInMonths,
                        'remain_day'    => $diffInDays,
                    );

                    $msg        = file_get_contents('./application/modules/employee/email_templates/work_pass_expiring_notification.html');
                    $subject    = 'Work Pass Expiring';
                    $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
                    $to_email   = json_encode(array(array("email"=> $employee['email'])));
                    $cc         = json_encode(array(array("email"=> $manager_email)));
                    $message    = $this->parser->parse_string($msg, $parse_data,true);
                    $this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null);
                    // send_by_sendinblue($subject, $from_email, $to_email, $cc = null, $message, $attachment = null)
                }
            }
        }


        // EMPTY PASS EXPIRE DATE CHECK
        $query2 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS fullname , payroll_employee.workpass AS pass , payroll_employee.pass_expire AS expiry_date, datediff(payroll_employee.pass_expire,CURDATE()) AS remaining_days, users.email, users.manager_in_charge
            FROM payroll_employee 
            LEFT JOIN payroll_user_employee ON payroll_employee.id = payroll_user_employee.employee_id 
            LEFT JOIN users ON payroll_user_employee.user_id = users.id 
            WHERE payroll_employee.workpass != 'Not Applicable' AND payroll_employee.pass_expire IS NULL 
            AND (payroll_employee.employee_status_id IN (1,2) OR (payroll_employee.employee_status_id IN (3,4) AND DATE(payroll_employee.date_cessation) >= DATE(CURRENT_DATE)))");

        if($query2->num_rows() > 0) 
        {
            foreach($query2->result_array() as $employee)
            {
                // SENDINBLUE EMAIL
                $this->load->library('parser');
                $parse_data = array('employee_name' => $employee['fullname']);
                $msg        = file_get_contents('./application/modules/employee/email_templates/work_pass_empty_notification.html');
                $subject    = 'Please Update Your Work Pass Expiry Date';
                $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
                $to_email   = json_encode(array(array("email"=> $employee['email'])));
                $message    = $this->parser->parse_string($msg, $parse_data,true);
                $this->sma->send_by_sendinblue($subject, $from_email, $to_email, null, $message, null);
                // send_by_sendinblue($subject, $from_email, $to_email, $cc = null, $message, $attachment = null)
            }
        }
    }
}