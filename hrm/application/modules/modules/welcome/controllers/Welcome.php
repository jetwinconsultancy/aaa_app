<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MX_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct() {
        parent::__construct();

        $this->load->model('welcome_model');

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }
    }

	public function index()
	{
		$bc = array(array('link' => '#', 'page' => 'Dashboard'));
        $meta = array('page_title' => 'Dashboard', 'bc' => $bc, 'page_name' => 'Dashboard');

        if(!$this->data['Admin'] && !$this->data['Manager']){

        	$this->data['jobs_due_list']          = $this->welcome_model->jobs_due_list2($this->user_id);
        }
        else if($this->data['Manager'] && $this->user_id != '79')
        {
        	$this->data['leave_pending_list']     = $this->welcome_model->get_leave_pending_list2($this->user_id);
        	$this->data['on_leave_list']          = $this->welcome_model->get_on_leave_list2($this->user_id);
        	$this->data['pass_expiry_list']       = $this->welcome_model->get_pass_expiry_list2($this->user_id);
        	$this->data['jobs_due_list']          = $this->welcome_model->jobs_due_list2($this->user_id);
            $this->data['ECD_list']               = $this->welcome_model->ECD_list2($this->user_id);
        }
        else if($this->data['Manager'] && $this->user_id == '79')
        {
            $this->data['leave_pending_list']     = $this->welcome_model->get_leave_pending_list2($this->user_id);
            $this->data['on_leave_list']          = $this->welcome_model->get_on_leave_list2($this->user_id);
            $this->data['pass_expiry_list']       = $this->welcome_model->get_pass_expiry_list2($this->user_id);
            $this->data['jobs_due_list']          = $this->welcome_model->jobs_due_list();
            $this->data['ECD_list']               = $this->welcome_model->ECD_list();
        }
        else
        {
        	$this->data['leave_pending_list']     = $this->welcome_model->get_leave_pending_list();
        	$this->data['on_leave_list']          = $this->welcome_model->get_on_leave_list();
        	$this->data['pass_expiry_list']       = $this->welcome_model->get_pass_expiry_list();
        	$this->data['jobs_due_list']          = $this->welcome_model->jobs_due_list();
            $this->data['ECD_list']               = $this->welcome_model->ECD_list();
        }

        $this->data['user'] = $this->user_id;
        if($this->user_id == 78) // JAMES
        {
        	$this->data['member_on_leave_list'] = $this->welcome_model->J_member_on_leave_list();
        }
        else if($this->user_id == 107)// FELICIA
        {
        	$this->data['member_on_leave_list'] = $this->welcome_model->F_member_on_leave_list();
        }

		// $this->page_construct('welcome_message', $this->meta, $this->data);

        $this->data['acknowledgement'] = $this->welcome_model->check_acknowledgement();

		$this->page_construct('index.php', $meta, $this->data);
	}

	// WORKPASS EXPIRE DATE CHECK
	public function workpass_expire_date_check()
	{
		$result = $this->welcome_model->workpass_expire_date_check($this->user_id);

		echo json_encode($result);
	}

    public function update_acknowledgement()
    {
        $acknowledgement["user_id"] = $this->session->userdata('user_id');
        $acknowledgement["read_and_understood"] = $_POST["understood"];

        $q = $this->db->get_where("acknowledgement", array('user_id' => $this->session->userdata('user_id')));

        if ($q->num_rows() > 0) 
        {
            $this->db->update("acknowledgement",$acknowledgement,array("user_id" => $this->session->userdata('user_id')));
        }
        else
        {
            $this->db->insert("acknowledgement",$acknowledgement);
        }

        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
    }
}
