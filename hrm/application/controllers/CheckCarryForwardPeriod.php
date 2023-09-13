<?php define( 'APPLICATION_LOADED', true );


class CheckCarryForwardPeriod extends CI_Controller {

    public function message($to = 'World') {
        echo "Hi {$to}!" . PHP_EOL;
    }

    public function check_latest_annual_leave() {

        $payroll_carry_forward_period_query = $this->db->query("SELECT * FROM payroll_carry_forward_period");

        if($payroll_carry_forward_period_query->num_rows())
        {
            $payroll_carry_forward_period_query = $payroll_carry_forward_period_query->result_array();

            $carry_forward_period_date = date('Y-m-d', strtotime(date("Y").'-'.$payroll_carry_forward_period_query[0]["carry_forward_period_date"]));

            if($carry_forward_period_date == date("Y-m-d"))
            {
                $payroll_employee_query = $this->db->query("SELECT payroll_employee.*, payroll_employee_type_of_leave.days FROM payroll_employee LEFT JOIN payroll_employee_type_of_leave ON payroll_employee.id = payroll_employee_type_of_leave.employee_id WHERE payroll_employee_type_of_leave.type_of_leave_id = 1");

                if($payroll_employee_query->num_rows())
                {
                    $payroll_employee_query = $payroll_employee_query->result_array();

                    for($g = 0; $g < count($payroll_employee_query); $g++)
                    {
                        $payroll_employee_annual_leave_query = $this->db->query("SELECT * FROM payroll_employee_annual_leave WHERE employee_id='". $payroll_employee_query[$g]['id'] ."' AND type_of_leave_id = 1 AND year(last_updated) = YEAR(CURDATE()) AND last_updated = (SELECT MAX(last_updated) FROM `payroll_employee_annual_leave` WHERE employee_id = ". $payroll_employee_query[$g]['id'] ." AND type_of_leave_id = 1)");

                        if($payroll_employee_annual_leave_query->num_rows())
                        {
                            $payroll_employee_annual_leave_query = $payroll_employee_annual_leave_query->result_array();

                            $n = 0;

                            // THIS CONDITION ONLY USE FOR 2022 !!!!!
                            if(date("Y") == '2022') {
                                if($payroll_employee_query[$g]['office'] == 2 || $payroll_employee_query[$g]['office'] == 3) {
                                    $n+=1;
                                }
                                if($payroll_employee_query[$g]['id'] == 11 || $payroll_employee_query[$g]['id'] == 34 || $payroll_employee_query[$g]['id'] == 64 || $payroll_employee_query[$g]['id'] == 67) {
                                    $n+=1;
                                }
                            }

                            if(date('n',strtotime($payroll_employee_query[$g]['dob'])) <=3 ) {
                                $n+=1;
                            }

                            if(($payroll_employee_query[$g]['days'] + $n) < $payroll_employee_annual_leave_query[0]["annual_leave_days"]) {

                                $data = array(
                                    'employee_id'       => $payroll_employee_query[$g]['id'],
                                    'type_of_leave_id'  => 1,
                                    'annual_leave_days' => $payroll_employee_query[$g]['days'] + $n
                                );
        
                                $result = $this->db->insert('payroll_employee_annual_leave', $data);
                            }
                        }
                    }
                }
            }
        }
    }
}