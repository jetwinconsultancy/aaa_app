<?php define( 'APPLICATION_LOADED', true );

class CheckBirthdayOff extends CI_Controller {

    public function message($to = 'World') {
        echo "halo {$to}!" . PHP_EOL;
    }

    public function birthday_day_off_notification() {

        $query = $this->db->query('SELECT payroll_employee.id,payroll_employee.name,users.email,payroll_employee.dob FROM payroll_employee 
        LEFT JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id
        LEFT JOIN users ON users.id = payroll_user_employee.user_id
        WHERE employee_status_id = 2 AND MONTH(payroll_employee.dob) = MONTH(CURRENT_DATE) AND DAY(payroll_employee.dob) = DAY(CURRENT_DATE)
        AND birthday_leave_this_year = 0');

        if($query->num_rows() > 0)
        {
            foreach($query->result() as $people)
            {
                $AL = $this->get_leave_balance($people->id,1);

                foreach($AL as $item)
                {
                    $employee_id       = $item->employee_id;
                    $annual_leave_days = $item->annual_leave_days;
                }

                $final_data = array(
                    'employee_id'       => $employee_id,
                    'type_of_leave_id'  => 1,
                    'annual_leave_days' => $annual_leave_days + 1
                );

                $result = $this->db->insert('payroll_employee_annual_leave', $final_data);

                $q2 = $this->db->where('id', $employee_id);
                $result = $q2->update('payroll_employee', array('birthday_leave_this_year' => 1));

                // EMAIL NOTIFICATION
                $this->load->library('parser');
                $parse_data         = array('user_name' => $people->name);
                $msg                = file_get_contents('./application/modules/leave/email_templates/birthday_off_email.html');
                $subject            = 'Birthday Leave Awarded';
                $from_email         = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
                $credential_email   = json_encode(array(array("email"=> $people->email)));
                $message            = $this->parser->parse_string($msg, $parse_data,true);
                $this->sma->send_by_sendinblue($subject, $from_email, $credential_email, null, $message, null);
            }
        }
    }

    public function get_leave_balance($id,$leave){
        $list = $this->db->query(" SELECT * FROM payroll_employee_annual_leave WHERE last_updated = (SELECT MAX(last_updated) FROM `payroll_employee_annual_leave` WHERE employee_id = '".$id."' AND type_of_leave_id = '".$leave."') AND employee_id='".$id."' AND type_of_leave_id = '".$leave."' ");

        return $list->result();
    }

    public function reset_all_birthday_leave(){
        $this->db->update('payroll_employee', array('birthday_leave_this_year' => 0));
    }
}