<?php
class Cron_reminder_renewal extends CI_Controller {

	//php index.php cron_billing message
    public function message($to = 'Reminder_renewal')
    {
        echo "Hello {$to}!".PHP_EOL;
    }

    //php index.php cron_billing check_recurring
   	public function check_reminder_renewal()
   	{
   		$today = strtotime(date('Y-m-d'));
   		//echo ($today);

   		$reminder_renewal_info = $this->db->query('select * from users where group_id = 2 AND active = 1');

   		if ($reminder_renewal_info->num_rows() > 0) 
        {
        	$reminder_renewal_info = $reminder_renewal_info->result_array();

        	for($v = 0; $v < count($reminder_renewal_info); $v++)
	        {
	        	$date_of_expiry = $reminder_renewal_info[$v]["date_of_expiry"];

	        	//$day_reminder = strtotime($date_of_expiry) - $today;
	        	if($date_of_expiry != NULL)
	        	{
		        	$diff = abs(strtotime($date_of_expiry) - $today);

		        	$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

		        	//echo ($days."   ");
		        	$this->load->library('parser');
		        	if($days == 90)
	            	{
	                    $parse_data = array(
	                        'first_name' => $reminder_renewal_info[$v]["first_name"],
	                        'last_name' => $reminder_renewal_info[$v]["last_name"],
	                        'days' => $days
	                    );
  
	            	}
	            	elseif($days == 30)
	            	{
	                    $parse_data = array(
	                        'first_name' => $reminder_renewal_info[$v]["first_name"],
	                        'last_name' => $reminder_renewal_info[$v]["last_name"],
	                        'days' => $days
	                    );
	            	}
	            	elseif($days == 7)
	            	{
	                    $parse_data = array(
	                        'first_name' => $reminder_renewal_info[$v]["first_name"],
	                        'last_name' => $reminder_renewal_info[$v]["last_name"],
	                        'days' => $days
	                    );
	            	}
	            	elseif($days == 1)
	            	{
	                    $parse_data = array(
	                        'first_name' => $reminder_renewal_info[$v]["first_name"],
	                        'last_name' => $reminder_renewal_info[$v]["last_name"],
	                        'days' => $days
	                    );
	            	}

	            	$msg = file_get_contents('./themes/default/views/email_templates/reminder_renewal.html');
                    $message = $this->parser->parse_string($msg, $parse_data);


                    $subject = "Reminder Account Renewal";
                    $this->sma->send_email($reminder_renewal_info[$v]["email"], $subject, $message);
		        }
		    }
	    }
   	}
}