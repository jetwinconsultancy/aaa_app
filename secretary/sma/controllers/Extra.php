<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Extra extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model(array('extra_model'));
    }

    public function message($to = 'World')
    {
    	echo "Hello {$to}!".PHP_EOL;
    }

    public function approval($our_services_id = null)
    {
    	if($our_services_id != null)
    	{
    		$our_service_info['approved'] = 1;
            $our_service_info['click_button_approve_or_reject'] = 1;
			$this->db->update("our_service_info", $our_service_info, array("id" => $our_services_id, "click_button_approve_or_reject" => 0));

            if ($this->db->affected_rows() == '1') {
			 $this->extra_model->send_approval_result($our_services_id, "Approved");
            }
			$this->close_method();
    	}
    }

    public function reject($our_services_id = null)
    {
    	if($our_services_id != null)
    	{
    		$our_service_info['approved'] = 0;
            $our_service_info['click_button_approve_or_reject'] = 1;
			$this->db->update("our_service_info", $our_service_info, array("id" => $our_services_id, "click_button_approve_or_reject" => 0));
            
            if ($this->db->affected_rows() == '1') {
			 $this->extra_model->send_approval_result($our_services_id, "Rejected");
            }
			$this->close_method();
    	}
    }

    public function close_method(){
	    echo  "<script type='text/javascript'>";
	    echo "window.close();";
	    echo "</script>";
	}
}