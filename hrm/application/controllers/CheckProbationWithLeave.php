<?php define( 'APPLICATION_LOADED', true );


class CheckProbationWithLeave extends CI_Controller {

    public function message($to = 'World') {
        echo "Hello {$to}!" . PHP_EOL;
    }

    public function check_latest_leave() {

    	$q4 = $this->db->query("SELECT * FROM payroll_leave_cycle");
        $q4 = $q4->result_array();

    	$payroll_employee_query = $this->db->query("SELECT * FROM payroll_employee");

        if($payroll_employee_query->num_rows())
        {
        	$payroll_employee_query = $payroll_employee_query->result_array();

        	for($g = 0; $g < count($payroll_employee_query); $g++)
            {
            	//echo json_encode($payroll_employee_query[$g]['status_date']);
            	if($payroll_employee_query[$g]['employee_status_id'] == 1 && $payroll_employee_query[$g]['status_date'] == null)
            	{
            		$date_join_after_three_month = date("Y-m-d", strtotime(date("Y-m-d", strtotime($payroll_employee_query[$g]['date_joined'])) . " +3 month"));
            		//echo json_encode($date_join_after_three_month);
            		if($date_join_after_three_month == date("Y-m-d"))
            		{
				    	$q6 = $this->db->query("SELECT * FROM payroll_employee_type_of_leave WHERE employee_id = ".$payroll_employee_query[$g]['id']);
				                
				        if($q6->num_rows())
				        {
				            $q6 = $q6->result_array();

				            for($t = 0; $t < count($q6); $t++)
				            {
				                $q5 = $this->db->query("SELECT * FROM payroll_employee_type_of_leave WHERE type_of_leave_id = ".$q6[$t]['type_of_leave_id']." AND employee_id = ".$payroll_employee_query[$g]['id']);

				                $annual_leave_result = $q5->result_array();

				                $annual_leave_result_day = $annual_leave_result[0]['days'];
				                 
				                $date1 = new DateTime($payroll_employee_query[$g]['date_joined']);
				                $date2 = new DateTime(date("Y").'-'.$q4[0]["leave_cycle_date_to"]);

				                $interval = $date1->diff($date2);

				                $years = $interval->y;
				                $months = $interval->m;
				                $days = $interval->d;

				                $balance_for_annual_leave_days = $annual_leave_result_day * ($months/12);

				                $q7 = $this->db->query("SELECT * FROM payroll_employee_annual_leave WHERE employee_id='". $payroll_employee_query[$g]['id'] ."' AND type_of_leave_id = '". $q6[$t]['type_of_leave_id'] ."' AND year(last_updated) = YEAR(CURDATE()) AND last_updated = (SELECT MAX(last_updated) FROM `payroll_employee_annual_leave` WHERE employee_id = ". $payroll_employee_query[$g]['id'] ." AND type_of_leave_id = ".$q6[$t]['type_of_leave_id'].")");
				                
				                if(!$q7->num_rows())
				                {
				                    $total_annual_leave = floor($balance_for_annual_leave_days * 2) / 2;

				                }
				                else
				                {
				                    $q7_query = $q7->result_array();

				                    $q7_query = $q7_query[0]['annual_leave_days'];

				                    $total_annual_leave = (floor($balance_for_annual_leave_days * 2) / 2) + $q7_query;
				                }

				                $final_data = array(
				                    'employee_id' => $payroll_employee_query[$g]['id'],
				                    'type_of_leave_id' => $q6[$t]['type_of_leave_id'],
				                    'annual_leave_days' => $total_annual_leave
				                );

				                $this->db->insert('payroll_employee_annual_leave', $final_data);
				            }
				        }
				    }
			    }
		    }
	    }
    }
}