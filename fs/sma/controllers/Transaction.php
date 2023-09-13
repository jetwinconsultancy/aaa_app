<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        $this->load->library('form_validation');
        $this->load->model(array('transaction_model', 'master_model'));
    }

    public function index()
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $bc = array(array('link' => '#', 'page' => 'Services'));
        $meta = array('page_title' => 'Services', 'bc' => $bc, 'page_name' => 'Services');
		$this->data['transaction'] = $this->transaction_model->get_all_transaction($_SESSION['group_id']);
        $this->page_construct('transaction/transaction_home.php', $meta, $this->data);

    }

    public function add ()
	{
        $bc = array(array('link' => '#', 'page' => 'Create Services'));
        $meta = array('page_title' => 'Create Services', 'bc' => $bc, 'page_name' => 'Create Services');


        $this->session->set_userdata(array(
            'transaction_id'  => null,
        ));

		$this->session->set_userdata(array(
            'transaction_company_code'  => null,
        ));

        $this->session->set_userdata(array(
            'company_type'  => null,
        ));

        //$this->data['transaction_code'] = $this->getTransactionCode();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Services', base_url('transaction'));
		$this->mybreadcrumb->add('Create Services', base_url());

		$this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('transaction/edit_transaction.php', $meta, $this->data);
		
	}

	public function edit ($id = null)
	{
		$bc = array(array('link' => '#', 'page' => 'Edit Services'));
        $meta = array('page_title' => 'Edit Services', 'bc' => $bc, 'page_name' => 'Edit Services');

		$this->data['transaction_master'] = $this->transaction_model->getTransactionMaster($id);
		$this->data['transaction_client'] = $this->transaction_model->getTransactionClient($id, $this->data['transaction_master'][0]->company_code);

		//echo json_encode($this->data['transaction_client']);
		// $transaction_company_code =$this->data['transaction_client'][0]->company_code;
		// $this->session->set_userdata('transaction_company_code', $transaction_company_code);
		$this->data['transaction_master_id'] = $this->data['transaction_master'][0]->id;
		$this->data['transaction_code'] = $this->data['transaction_master'][0]->transaction_code;
		$this->data['registration_no'] = $this->data['transaction_master'][0]->registration_no;
		$this->data['status'] = $this->data['transaction_master'][0]->status;
		$this->data['transaction_task_id'] = $this->data['transaction_master'][0]->transaction_task_id;
		$this->session->set_userdata(array(
            'transaction_company_code'  => $this->data['transaction_client'][0]->company_code,
        ));
        $this->session->set_userdata(array(
            'transaction_id'  => $this->data['transaction_master'][0]->id,
        ));

        $this->session->unset_userdata('transaction_chairman');
        $this->session->unset_userdata('transaction_director_signature_1');
        $this->session->unset_userdata('transaction_director_signature_2');

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Services', base_url('transaction'));

        if($this->data['transaction_master'][0]->transaction_task_id == "29" || $this->data['transaction_master'][0]->transaction_task_id == "30")
        {
        	$this->session->set_userdata(array(
	            'transaction_company_code'  => $this->data['transaction_master'][0]->company_code,
	        ));
        	$this->mybreadcrumb->add('Edit Services - '.$this->data['transaction_master'][0]->client_name.'', base_url());
        }
        else
        {
        	$this->session->set_userdata(array(
	            'transaction_company_code'  => $this->data['transaction_client'][0]->company_code,
	        ));
			$this->mybreadcrumb->add('Edit Services - '.$this->data['transaction_client'][0]->company_name.'', base_url());
        }
		$this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('transaction/edit_transaction.php', $meta, $this->data);

	}

	public function getTransactionCode()
	{
		$query_transaction_code = $this->db->query("select MAX(CAST(SUBSTRING(transaction_code,5, length(transaction_code)-4) AS UNSIGNED)) as transaction_code from transaction_master"); //invoice_number excluding the 9 first characters, converts to int, and selects max from it.

        if ($query_transaction_code->num_rows() > 0) 
        {
            $query_transaction_code = $query_transaction_code->result_array();

            $last_section_transaction_code = (int)$query_transaction_code[0]["transaction_code"] + 1;
            $transaction_code = date("Y").str_pad($last_section_transaction_code,3,"0",STR_PAD_LEFT);

        }
        else
        {
            $transaction_code = date("Y").str_pad(1,3,"0",STR_PAD_LEFT);
        }

        return $transaction_code;
	}

	public function get_all_el_client()
	{
		$result = $this->db->query('select * from client where deleted = 0 and firm_id = "'.$this->session->userdata('firm_id').'"');

		$result = $result->result_array();
		//echo json_encode($result);
		if(!$result) {
			throw new exception("Client not found.");
		}

		$res = array();

		foreach($result as $row) {
			$res[$row['company_code']] = $row['company_name'];
		}

		$trans_master_result = $this->db->query('select transaction_master.* from transaction_master left join transaction_service_proposal_info on transaction_master.id = transaction_service_proposal_info.transaction_id where transaction_master.service_status != 2 and transaction_master.service_status != 4 and transaction_master.transaction_task_id = 29 and transaction_service_proposal_info.potential_client = 1 and firm_id = "'.$this->session->userdata('firm_id').'"');

		$trans_master_result = $trans_master_result->result_array();
		//echo json_encode($result);
		// if(!$trans_master_result) {
		// 	throw new exception("Potential Client not found.");
		// }

		foreach($trans_master_result as $row) {
			$res[$row['company_code']] = $row['client_name'].' (Potential Client)';
		}

		$data = array('status'=>'success', 'tp'=>1, 'msg'=>"Client fetched successfully.", 'result'=>$res);

	    echo json_encode($data);
	}

	public function get_all_client()
	{
		$result = $this->db->query('select * from client where deleted = 0 and firm_id = "'.$this->session->userdata('firm_id').'"');

		$result = $result->result_array();
		//echo json_encode($result);
		if(!$result) {
			throw new exception("Client not found.");
		}
		$res = array();
		foreach($result as $row) {
			$res[$row['id']] = $row['company_name'];
		}

		$data = array('status'=>'success', 'tp'=>1, 'msg'=>"Client fetched successfully.", 'result'=>$res);

	    echo json_encode($data);
	}

	public function get_client_type()
	{
		$result = $this->db->query("select * from client_type");

		$result = $result->result_array();
		//echo json_encode($result);
		if(!$result) {
			throw new exception("Client Type not found.");
		}
		$res = array();
		foreach($result as $row) {
			$res[$row['id']] = $row['client_type'];
		}

		$data = array('status'=>'success', 'tp'=>1, 'msg'=>"Client Type fetched successfully.", 'result'=>$res);

	    echo json_encode($data);
	}

	public function get_transaction_task()
	{
		if($_SESSION['group_id'] != 4)
		{
			$result = $this->db->query("select * from transaction_tasks where id != 13 && id != 14 && id != 16 && id != 17 && id != 18 && id != 19 && id != 21 && id != 22 && id != 23 && id != 25");
		}
		else
		{
			$result = $this->db->query("select * from transaction_tasks where id != 1 && id != 13 && id != 14 && id != 16 && id != 17 && id != 18 && id != 19 && id != 21 && id != 22 && id != 23 && id != 25");
		}


		$result = $result->result_array();
		//echo json_encode($result);
		if(!$result) {
			throw new exception("Transaction Task not found.");
		}
		$res = array();
		foreach($result as $row) {
			$res[$row['id']] = $row['transaction_task'];
		}

		$data = array('status'=>'success', 'tp'=>1, 'msg'=>"Transaction Task fetched successfully.", 'result'=>$res);

	    echo json_encode($data);
	}

	public function get_share_allotment_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->db->select('effective_date, service_status');
        $this->db->from('transaction_master');
        $this->db->where('id', $transaction_master_id);
		$transaction_master = $this->db->get();
		$transaction_master = $transaction_master->result_array();

		$this->data['effective_date'] = $transaction_master[0]['effective_date'];
		$this->data['service_status'] = $transaction_master[0]['service_status'];

		$this->data['transaction_share_allotment_date'] = $this->transaction_model->getTransactionShareAllotmentDate($transaction_master_id);

		$this->data['transaction_member'] = $this->transaction_model->getTransactionClientMember($transaction_master_id, $_POST["company_code"]);

		$this->data['follow_up_history'] = $this->transaction_model->getFollowUpHistory($transaction_master_id, $_POST["company_code"]);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$this->data['follow_up_outcome'] = $this->transaction_model->get_follow_up_outcome();

		$this->data['follow_up_action'] = $this->transaction_model->get_follow_up_action();

		$this->data['transaction_service_status'] = $this->transaction_model->get_transaction_service_status();

		//print($transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_share_allotment.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function add_follow_up_info()
	{
		$follow['id'] = $_POST['follow_up_history_id'];
		$follow['firm_id'] = $this->session->userdata('firm_id');
		$follow['transaction_master_id'] = $_POST['transaction_master_id'];
		$follow['company_code'] = $_POST['company_code'];
		$follow['date_of_follow_up'] = $_POST['date_of_follow_up'];
		$follow['time_of_follow_up'] = $_POST['time_of_follow_up'];
		$follow['follow_up_remark'] = $_POST['follow_up_remark'];
		$follow['follow_up_outcome_id'] = $_POST['follow_up_outcome'];
		$follow['follow_by'] = $this->session->userdata('user_id');
		$follow['deleted'] = 0;

		if(isset($_POST['follow_up_action']))
		{
			$follow['follow_up_action_id'] = $_POST['follow_up_action'];
		}
		else
		{
			$follow['follow_up_action_id'] = "";
		}

		if(isset($_POST['next_follow_up_date']))
		{
			$follow['next_follow_up_date'] = $_POST['next_follow_up_date'];
		}
		else
		{
			$follow['next_follow_up_date'] = "";
		}

		if(isset($_POST['next_follow_up_time']))
		{
			$follow['next_follow_up_time'] = $_POST['next_follow_up_time'];
		}
		else
		{
			$follow['next_follow_up_time'] = "";
		}

		$q = $this->db->get_where("follow_up_history", array("company_code" => $_POST['company_code'], "id" => $_POST['follow_up_history_id']));

		if (!$q->num_rows())
		{		
			$follow["follow_up_id"] = "F-".mt_rand(100000,999999); 
			$this->db->insert("follow_up_history",$follow);
			//$insert_share_capital_id = $this->db->insert_id();
		} 
		else 
		{
			$this->db->where(array("company_code" => $_POST['company_code'], "id" => $_POST['follow_up_history_id']));
			$this->db->update("follow_up_history",$follow);
		}

		$get_follow_up_data = $this->db->query("select follow_up_history.*, users.first_name, users.last_name from follow_up_history left join users on users.id = follow_up_history.follow_by where follow_up_history.company_code='".$_POST['company_code']."' AND follow_up_history.transaction_master_id = '".$_POST['transaction_master_id']."' AND follow_up_history.deleted = 0 order by id");

        if ($get_follow_up_data->num_rows() > 0) {
            foreach (($get_follow_up_data->result()) as $row) {
                $data[] = $row;
            }
        }

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', 'follow_up_history' => $data));
	}

	public function delete_follow_up_history()
	{
		$follow['deleted'] = 1;
		$this->db->where(array("company_code" => $_POST['company_code'], "id" => $_POST['follow_up_history_id']));

		$this->db->update("follow_up_history",$follow);

		$get_follow_up_data = $this->db->query("select follow_up_history.*, users.first_name, users.last_name from follow_up_history left join users on users.id = follow_up_history.follow_by where follow_up_history.company_code='".$_POST['company_code']."' AND follow_up_history.transaction_master_id = '".$_POST['transaction_master_id']."' AND follow_up_history.deleted = 0 order by id");

        if ($get_follow_up_data->num_rows() > 0) {
            foreach (($get_follow_up_data->result()) as $row) {
                $data[] = $row;
            }
        }

        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', 'follow_up_history' => $data));
	}

	public function get_share_transfer_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->db->select('effective_date');
        $this->db->from('transaction_master');
        $this->db->where('id', $transaction_master_id);
		$transaction_master = $this->db->get();
		$transaction_master = $transaction_master->result_array();

		$this->data['effective_date'] = $transaction_master[0]['effective_date'];

		$this->data['transaction_member'] = $this->transaction_model->getTransactionClientTransferMemberInfo($transaction_master_id);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_share_transfer.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_all_take_secretarial_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('transaction_client');
        $this->db->where('company_code', $transaction_company_code);
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['transaction_client'] = $this->transaction_model->getTransactionClient($transaction_master_id, $transaction_company_code);
		$this->data['transaction_client_officers'] = $this->transaction_model->getTransactionClientOfficer($transaction_master_id, $transaction_company_code);
		$this->data['transaction_filing'] = $this->transaction_model->getTransactionClientFiling($transaction_master_id, $transaction_company_code);
		$this->data['transaction_billing'] = $this->transaction_model->getTransactionClientBilling($transaction_master_id, $transaction_company_code);
		$this->data['transaction_previous_secretarial'] = $this->transaction_model->getTransactionPreviousSecretarial($transaction_master_id, $transaction_company_code);
		
		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_take_over_secretarial.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_all_transaction_incorporation_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('transaction_client');
        $this->db->where('company_code', $transaction_company_code);
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['transaction_client'] = $this->transaction_model->getTransactionClient($transaction_master_id, $transaction_company_code);
		$this->data['transaction_client_officers'] = $this->transaction_model->getTransactionClientOfficer($transaction_master_id, $transaction_company_code);
		$this->data['transaction_member'] = $this->transaction_model->getTransactionClientMember($transaction_master_id, $transaction_company_code);
		$this->data['transaction_client_controller'] = $this->transaction_model->getTransactionClientController($transaction_master_id, $transaction_company_code);
		$this->data['transaction_filing'] = $this->transaction_model->getTransactionClientFiling($transaction_master_id, $transaction_company_code);
		$this->data['transaction_billing'] = $this->transaction_model->getTransactionClientBilling($transaction_master_id, $transaction_company_code);
		$this->data['transaction_client_signing_info'] = $this->transaction_model->getTransactionClientSigningInfo($transaction_master_id, $transaction_company_code);
		$this->data['transaction_contact_person_info'] = $this->transaction_model->getTransactionClientContactInfo($transaction_master_id, $transaction_company_code);
		$this->data['transaction_client_selected_reminder'] = $this->transaction_model->getTransactionClientReminderInfo($transaction_master_id, $transaction_company_code);
		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_incorporation_new_company.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function check_lodge_status()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->data['get_lodge_status'] = $this->transaction_model->get_lodge_status($transaction_master_id, $transaction_company_code);

		echo json_encode(array($this->data));

	}

	public function get_all_appoint_new_secretarial_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('firm_id', $this->session->userdata('firm_id'));
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['transaction_client_officers'] = $this->transaction_model->getTransactionClientOfficer($transaction_master_id, $_POST['company_code']);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_appoint_new_secretarial.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_all_appoint_new_director_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('firm_id', $this->session->userdata('firm_id'));
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['transaction_client_officers'] = $this->transaction_model->getTransactionClientOfficer($transaction_master_id, $_POST['company_code']);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_appoint_new_director.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_all_appoint_resign_auditor_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('firm_id', $this->session->userdata('firm_id'));
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['transaction_client_officers'] = $this->transaction_model->getTransactionResignClientOfficer($transaction_master_id);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_appt_resign_auditor.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_all_resign_director_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('firm_id', $this->session->userdata('firm_id'));
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['transaction_client_officers'] = $this->transaction_model->getTransactionResignClientOfficer($transaction_master_id);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_resign_director.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_change_regis_office_address_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('firm_id', $this->session->userdata('firm_id'));
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['transaction_change_regis_ofis_address'] = $this->transaction_model->getTransactionChangeRegOfisAddress($transaction_master_id);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_change_reg_ofis_address.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_change_FYE_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('firm_id', $this->session->userdata('firm_id'));
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['transaction_change_FYE'] = $this->transaction_model->getTransactionChangeFYE($transaction_master_id);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_change_FYE.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_change_biz_activity_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('firm_id', $this->session->userdata('firm_id'));
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['transaction_change_biz_activity'] = $this->transaction_model->getTransactionChangeBizActivity($transaction_master_id);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_change_biz_activity.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_issue_director_fee_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('firm_id', $this->session->userdata('firm_id'));
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['transaction_issue_director_fee'] = $this->transaction_model->getTransactionIssueDirectorFee($transaction_master_id);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_issue_director_fee.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_issue_dividend_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('firm_id', $this->session->userdata('firm_id'));
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['transaction_issue_dividend'] = $this->transaction_model->getTransactionIssueDividend($transaction_master_id);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_issue_dividend.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_incorp_subsidiary_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('firm_id', $this->session->userdata('firm_id'));
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['transaction_incorporation_subsidiary'] = $this->transaction_model->getTransactionIncorporationSubsidiary($transaction_master_id);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_incorp_subsidiary.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_engagement_letter_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('firm_id', $this->session->userdata('firm_id'));
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->db->select('effective_date, service_status');
        $this->db->from('transaction_master');
        $this->db->where('id', $transaction_master_id);
		$transaction_master = $this->db->get();
		$transaction_master = $transaction_master->result_array();

		$this->data['effective_date'] = $transaction_master[0]['effective_date'];
		$this->data['service_status'] = $transaction_master[0]['service_status'];

		// $this->data['transaction_service_proposal'] = $this->transaction_model->getTransactionServiceProposal($transaction_master_id);

		// $this->data['transaction_service_proposal_contact_person'] = $this->transaction_model->getTransactionClientContactInfo($transaction_master_id, $transaction_company_code);

		// $this->data['transaction_service_proposal_service_info'] = $this->transaction_model->getTransactionServiceProposalServiceInfo($transaction_master_id);

		// $this->data['transaction_our_service_list'] = $this->transaction_model->getTransactionOurServiceList();
		$this->data['transaction_engagement_letter_additional_info'] = $this->transaction_model->getTransactionEngagementLetterAdditionalInfo($transaction_master_id);

		$this->data['transaction_engagement_letter_service_info'] = $this->transaction_model->getTransactionEngagementLetter($transaction_master_id);

		$this->data['transaction_engagement_letter_list'] = $this->transaction_model->getTransactionEngagementLetterList();

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$this->data['transaction_engagement_letter_status'] = $this->transaction_model->get_transaction_engagement_letter_status();

		$interface = $this->load->view('/views/transaction/confirmation_engagement_letter.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_service_proposal_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('firm_id', $this->session->userdata('firm_id'));
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->db->select('transaction_task_id, effective_date, service_status');
        $this->db->from('transaction_master');
        $this->db->where('id', $transaction_master_id);
		$transaction_master = $this->db->get();
		$transaction_master = $transaction_master->result_array();

		$this->data['effective_date'] = $transaction_master[0]['effective_date'];
		$this->data['service_status'] = $transaction_master[0]['service_status'];

		$this->data['transaction_service_proposal'] = $this->transaction_model->getTransactionServiceProposal($transaction_master_id);

		$this->data['transaction_service_proposal_contact_person'] = $this->transaction_model->getTransactionClientContactInfo($transaction_master_id, $transaction_company_code);

		$this->data['transaction_service_proposal_service_info'] = $this->transaction_model->getTransactionServiceProposalServiceInfo($transaction_master_id);

		$this->data['transaction_our_service_list'] = $this->transaction_model->getTransactionOurServiceList();

		if(count($transaction_client) > 0)
		{
			$client_id = $transaction_client[0]["id"];
		}
		else
		{
			$client_id = NULL;
		}
		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $client_id, $transaction_master[0]['transaction_task_id']);

		$this->data['transaction_service_proposal_status'] = $this->transaction_model->get_transaction_service_proposal_status();

		$interface = $this->load->view('/views/transaction/confirmation_service_proposal.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_strike_off_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('firm_id', $this->session->userdata('firm_id'));
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['transaction_strike_off'] = $this->transaction_model->getTransactionStrikeOff($transaction_master_id);

		$result_status_company = $this->db->query("select * from status where id != 3");
		$result_status_company = $result_status_company->result_array();
		for($j = 0; $j < count($result_status_company); $j++)
		{
			$res[$result_status_company[$j]['id']] = $result_status_company[$j]['status'];
		}
		$this->data["status_company"] = $res;

		//$this->data['status_company'] = $this->transaction_model->getStatusCompany();

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_strike_off.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_change_company_name_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('firm_id', $this->session->userdata('firm_id'));
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['transaction_change_company_name'] = $this->transaction_model->getTransactionChangeCompanyName($transaction_master_id);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_change_company_name.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_agm_ar_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('firm_id', $this->session->userdata('firm_id'));
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['transaction_agm_ar'] = $this->transaction_model->getTransactionAgmAr($transaction_master_id, $transaction_company_code);

		$this->data['transaction_agm_ar_director_fee'] = $this->transaction_model->getTransactionDirectorFee($transaction_master_id, $transaction_company_code);

		$this->data['transaction_agm_ar_dividend'] = $this->transaction_model->getTransactionDividend($transaction_master_id, $transaction_company_code);

		$this->data['transaction_agm_ar_amount_due'] = $this->transaction_model->getTransactionAmountDue($transaction_master_id, $transaction_company_code);

		$this->data['transaction_agm_ar_director_retire'] = $this->transaction_model->getTransactionDirectorRetire($transaction_master_id, $transaction_company_code);

		$this->data['transaction_agm_ar_reappoint_auditor'] = $this->transaction_model->getTransactionReappointAuditor($transaction_master_id, $transaction_company_code);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_agm_ar.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_latest_document()
	{
		$transaction_task_id = $_POST["transaction_task_id"];
		$transaction_company_code = $_POST["company_code"];
		$second_transaction_task_id = $_POST["second_transaction_task_id"];
		$hidden_selected_el_id = $_POST["hidden_selected_el_id"];

		if($transaction_task_id == 30)
		{
			if(isset($_POST["transaction_master_id"]))
			{
				$transaction_master_id = $_POST["transaction_master_id"];
			}
			else
			{
				$transaction_master_id = NULL;
			}

			//$this->data['firm_name'] = $this->transaction_model->get_firm_name($transaction_master_id, $hidden_selected_el_id);
		}

		
		//echo json_encode($hidden_selected_el_id);
		$this->data['document'] = $this->transaction_model->get_all_document($transaction_task_id, $transaction_company_code, $second_transaction_task_id, $hidden_selected_el_id, $transaction_master_id);

		

		echo json_encode(array($this->data));
	}

	public function get_take_over_of_secretarial_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];

		if($transaction_id != null)
		{
			$this->data['transaction_client'] = $this->transaction_model->getTransactionClient($transaction_id, $company_code);
			$this->data['transaction_client_officers'] = $this->transaction_model->getTransactionClientOfficer($transaction_id, $_POST['company_code']);
			//$this->data['transaction_client_controller'] = $this->transaction_model->getTransactionClientController($transaction_id, $company_code);
			$this->data['transaction_filing'] = $this->transaction_model->getTransactionClientFiling($transaction_id, $company_code);
			$this->data['transaction_billing'] = $this->transaction_model->getTransactionClientBilling($transaction_id, $company_code);
			$this->data['transaction_previous_secretarial'] = $this->transaction_model->getTransactionPreviousSecretarial($transaction_id, $company_code);
			//$this->data['transaction_member'] = $this->transaction_model->getTransactionClientMember($transaction_id, $company_code);
			//$this->data['transaction_client_signing_info'] = $this->transaction_model->getTransactionClientSigningInfo($transaction_id, $company_code);
			//$this->data['transaction_contact_person_info'] = $this->transaction_model->getTransactionClientContactInfo($transaction_id, $company_code);
			//$this->data['transaction_client_selected_reminder'] = $this->transaction_model->getTransactionClientReminderInfo($transaction_id, $company_code);

			$this->data['document'] = $this->transaction_model->get_all_document($transaction_task_id, $company_code, '');

			$transaction_company_code = $this->data['transaction_client'][0]->company_code;
			$this->session->set_userdata('transaction_company_code', $transaction_company_code);
			$this->session->set_userdata(array(
                'company_type'  => $this->data['transaction_client'][0]->company_type,
            ));
            $this->session->set_userdata(array(
                'transaction_id'  => $transaction_id,
            ));
		}
		
        $this->db->select('our_service_registration_address.id, our_service_info.service_name, our_service_registration_address.postal_code, our_service_registration_address.street_name, our_service_registration_address.building_name, our_service_registration_address.unit_no1, our_service_registration_address.unit_no2')
                ->from('firm')
                ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
                ->join('our_service_info', 'our_service_info.user_admin_code_id = '.$this->session->userdata('user_admin_code_id').' and service_type = 7', 'left')
                ->join('our_service_registration_address', 'our_service_registration_address.our_service_info_id = our_service_info.id', 'left')
                ->where('user_firm.user_id = '.$this->session->userdata('user_id'))
                ->where('user_firm.in_use = 1');
        $registered_address = $this->db->get();

        $registered_address_info = $registered_address->result_array();

        $this->data['first_time'] = false;
        $this->data['registered_address_info'] = $registered_address_info;

		$interface = $this->load->view('/views/transaction/take_over_of_secretarial.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_incorporation_new_company_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];

		if($transaction_id != null)
		{
			$this->data['transaction_client'] = $this->transaction_model->getTransactionClient($transaction_id, $company_code);
			$this->data['transaction_client_officers'] = $this->transaction_model->getTransactionClientOfficer($transaction_id, $_POST['company_code']);
			$this->data['transaction_client_controller'] = $this->transaction_model->getTransactionClientController($transaction_id, $company_code);
			$this->data['transaction_filing'] = $this->transaction_model->getTransactionClientFiling($transaction_id, $company_code);
			$this->data['transaction_billing'] = $this->transaction_model->getTransactionClientBilling($transaction_id, $company_code);
			$this->data['transaction_member'] = $this->transaction_model->getTransactionClientMember($transaction_id, $company_code);
			$this->data['transaction_client_signing_info'] = $this->transaction_model->getTransactionClientSigningInfo($transaction_id, $company_code);
			$this->data['transaction_contact_person_info'] = $this->transaction_model->getTransactionClientContactInfo($transaction_id, $company_code);
			$this->data['transaction_client_selected_reminder'] = $this->transaction_model->getTransactionClientReminderInfo($transaction_id, $company_code);

			$this->data['document'] = $this->transaction_model->get_all_document($transaction_task_id, $company_code, '');

			$transaction_company_code = $this->data['transaction_client'][0]->company_code;
			$this->session->set_userdata('transaction_company_code', $transaction_company_code);
			$this->session->set_userdata(array(
                'company_type'  => $this->data['transaction_client'][0]->company_type,
            ));
            $this->session->set_userdata(array(
                'transaction_id'  => $transaction_id,
            ));
		}
		
        $this->db->select('our_service_registration_address.id, our_service_info.service_name, our_service_registration_address.postal_code, our_service_registration_address.street_name, our_service_registration_address.building_name, our_service_registration_address.unit_no1, our_service_registration_address.unit_no2')
                ->from('firm')
                ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
                ->join('our_service_info', 'our_service_info.user_admin_code_id = '.$this->session->userdata('user_admin_code_id').' and service_type = 7', 'left')
                ->join('our_service_registration_address', 'our_service_registration_address.our_service_info_id = our_service_info.id', 'left')
                ->where('user_firm.user_id = '.$this->session->userdata('user_id'))
                ->where('user_firm.in_use = 1');
        $registered_address = $this->db->get();

        $registered_address_info = $registered_address->result_array();

        $this->data['first_time'] = false;
        $this->data['registered_address_info'] = $registered_address_info;

		$interface = $this->load->view('/views/transaction/incorporation_new_company.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_appointment_of_secretarial_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registra_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where registration_no = "'.$registra_no.'" and firm_id = "'.$this->session->userdata('firm_id').'" and deleted = 0');

		if ($q->num_rows() > 0) 
        {
			if($transaction_id != null)
			{
				$this->data['transaction_appoint_new_secretarial'] = $this->transaction_model->getTransactionClientOfficer($transaction_id, $_POST['company_code']);

				$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
			}

			$interface = $this->load->view('/views/transaction/appoint_new_secretarial.php', '', TRUE);

			echo json_encode(array("interface" => $interface, $this->data));
		}
		else
		{
			echo json_encode(array("error" => "Please enter correct registration number."));
		}
	}

	public function get_appointment_of_director_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registra_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where registration_no = "'.$registra_no.'" and firm_id = "'.$this->session->userdata('firm_id').'" and deleted = 0');

		if ($q->num_rows() > 0) 
        {
			if($transaction_id != null)
			{
				$this->data['transaction_appoint_new_director'] = $this->transaction_model->getTransactionClientOfficer($transaction_id, $_POST['company_code']);

				$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
			}

			$interface = $this->load->view('/views/transaction/appoint_new_director.php', '', TRUE);

			echo json_encode(array("interface" => $interface, $this->data));
		}
		else
		{
			echo json_encode(array("error" => "Please enter correct registration number."));
		}
	}

	public function get_appointment_of_auditor_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registra_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where registration_no = "'.$registra_no.'" and firm_id = "'.$this->session->userdata('firm_id').'" and deleted = 0');

		if ($q->num_rows() > 0) 
        {
			if($transaction_id != null)
			{
				$this->data['transaction_appoint_new_auditor'] = $this->transaction_model->getTransactionResignClientOfficer($transaction_id);

				$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
			}

			$interface = $this->load->view('/views/transaction/appoint_new_auditor.php', '', TRUE);

			echo json_encode(array("interface" => $interface, $this->data));
		}
		else
		{
			echo json_encode(array("error" => "Please enter correct registration number."));
		}
	}

	public function get_transfer_people()
	{
		$client_member_share_capital_id = $_POST["client_member_share_capital_id"];
		$company_code = $_POST["company_code"];
		// $transaction_id = $_POST["transaction_id"];
		// $transaction_date = $_POST["transaction_date"];

		//echo json_encode($client_member_share_capital_id);
		
		$q = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" and client.firm_id = "'.$this->session->userdata('firm_id').'" and client.deleted = 0 where member_shares.company_code="'.$company_code.'" AND member_shares.client_member_share_capital_id = "'.$client_member_share_capital_id.'" GROUP BY member_shares.field_type, member_shares.officer_id HAVING sum(member_shares.number_of_share) != 0');

		 /*left join member_shares on member_shares.transaction_id = certificate.transaction_id and member_shares.officer_id = certificate.officer_id and member_shares.field_type = certificate.field_type*/
		  /*AND 0 > member_shares.number_of_share*/

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        echo (FALSE);
	}

	public function edit_share_transfer_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registra_no = strtoupper($_POST["registration_no"]);
		$transaction_share_member_id = $_POST["transaction_share_member_id"];

		$this->data['transaction_share_transfer'] = $this->transaction_model->getTransactionClientTransferMember($transaction_id, $transaction_share_member_id);

		echo json_encode(array($this->data));
	}

	public function get_director_info()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registra_no = strtoupper($_POST["registration_no"]);
	}

	public function get_share_transfer_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registra_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where registration_no = "'.$registra_no.'" and firm_id = "'.$this->session->userdata('firm_id').'" and deleted = 0');

		if ($q->num_rows() > 0) 
        {
	        $client_company_code =  $this->db->query("select company_code from client where registration_no='".$registra_no."' and firm_id = '".$this->session->userdata('firm_id')."' and deleted = 0");

	        $client_company_code = $client_company_code->result_array();

			if($transaction_id != null)
			{
				$this->data['transaction_share_transfer'] = $this->transaction_model->getTransactionClientTransferMemberInfo($transaction_id);

				$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
			}

			$this->data['company_class'] = $this->master_model->get_all_company_share_type($client_company_code[0]["company_code"]);

			$interface = $this->load->view('/views/transaction/share_transfer.php', '', TRUE);

			echo json_encode(array("interface" => $interface, $this->data));
		}
		else
		{
			echo json_encode(array("error" => "Please enter correct registration number."));
		}
	}

	public function get_share_allot_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registra_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where registration_no = "'.$registra_no.'" and firm_id = "'.$this->session->userdata('firm_id').'" and deleted = 0');

		if ($q->num_rows() > 0) 
        {

	        $client_company_code =  $this->db->query("select company_code, postal_code, street_name, building_name, unit_no1, unit_no2 from client where registration_no='".$registra_no."' and firm_id = '".$this->session->userdata('firm_id')."' and deleted = 0");

	        $client_company_code = $client_company_code->result_array();

			if($transaction_id != null)
			{
				$this->data['transaction_share_allotment_date'] = $this->transaction_model->getTransactionShareAllotmentDate($transaction_id);

				$this->data['transaction_share_allotment'] = $this->transaction_model->getTransactionClientMember($transaction_id, $company_code);

				$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
			}

			$this->data['company_class'] = $this->master_model->get_all_company_share_type($client_company_code[0]["company_code"]);

			$this->data['postal_code'] = $client_company_code[0]["postal_code"];
			$this->data['street_name'] = $client_company_code[0]["street_name"];
			$this->data['building_name'] = $client_company_code[0]["building_name"];
			$this->data['unit_no1'] = $client_company_code[0]["unit_no1"];
			$this->data['unit_no2'] = $client_company_code[0]["unit_no2"];

			$interface = $this->load->view('/views/transaction/share_allotment.php', '', TRUE);

			echo json_encode(array("interface" => $interface, $this->data, "error" => null));
		}
		else
		{
			echo json_encode(array("error" => "Please enter correct registration number."));
		}
	}

	public function get_agm_ar_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registra_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where registration_no = "'.$registra_no.'" and firm_id = "'.$this->session->userdata('firm_id').'" and deleted = 0');

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();

        	$member_info = $this->db->query('select member_shares.*, LENGTH(sum(member_shares.number_of_share)) AS LengthOfMemberShare, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer.field_type as officer_field_type, officer_company.field_type as officer_company_field_type, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" where member_shares.company_code="'.$client_info[0]['company_code'].'" GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id HAVING sum(member_shares.number_of_share) != 0');

        	$member_info = $member_info->result_array();

        	$this->data['epc_status_value'] = 1;
        	//echo json_encode(20 > $member_info[0]['LengthOfMemberShare']);
        	if(20 >= $member_info[0]['LengthOfMemberShare'])
        	{
        		for($r = 0; $r < count($member_info); $r++)
        		{
        			if($member_info[$r]["officer_field_type"] == null)
        			{
        				$this->data['epc_status_value'] = 2;
        				break;
        			}
        		}
        	}
        	else if($member_info[0]['LengthOfMemberShare'] > 20)
        	{
        		$this->data['epc_status_value'] = 2;
        	}

	        if($transaction_id != null)
			{
				$this->data['transaction_agm_ar'] = $this->transaction_model->getTransactionAgmAr($transaction_id, $company_code);

				$this->data['transaction_agm_ar_director_fee'] = $this->transaction_model->getTransactionDirectorFee($transaction_id, $company_code);

				$this->data['transaction_agm_ar_dividend'] = $this->transaction_model->getTransactionDividend($transaction_id, $company_code);

				$this->data['transaction_agm_ar_amount_due'] = $this->transaction_model->getTransactionAmountDue($transaction_id, $company_code);

				$this->data['transaction_agm_ar_director_retire'] = $this->transaction_model->getTransactionDirectorRetire($transaction_id, $company_code);

				$this->data['transaction_agm_ar_reappoint_auditor'] = $this->transaction_model->getTransactionReappointAuditor($transaction_id, $company_code);

				$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
			}
			$this->data['client_signing_info'] = $this->transaction_model->get_all_client_signing_info($registra_no);

			$this->data['first_agm'] = $this->transaction_model->get_all_first_agm();

			$this->data['agm_share_transfer'] = $this->transaction_model->get_all_agm_share_transfer();

			$this->data['consent_for_shorter_notice'] = $this->transaction_model->get_all_consent_for_shorter_notice();

			$this->data['activity_status'] = $this->transaction_model->get_all_activity_status();

			$this->data['solvency_status'] = $this->transaction_model->get_all_solvency_status();

			$this->data['epc_status'] = $this->transaction_model->get_all_epc_status();

			$this->data['small_company'] = $this->transaction_model->get_all_small_company();

			$this->data['audited_financial_statement'] = $this->transaction_model->get_all_audited_financial_statement();

			$this->data['filing_info'] = $this->transaction_model->check_filing_info($registra_no);

			$interface = $this->load->view('/views/transaction/agm_ar.php', '', TRUE);

			echo json_encode(array("interface" => $interface, $this->data, "error" => null));
		}
		else
		{
			echo json_encode(array("error" => "Please enter correct registration number."));
		}
	}

	public function get_engagement_letter_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$company_name = $_POST["company_name"];

		$this->data['client_info'] = $this->transaction_model->check_client_info($company_code);

		if($transaction_id != null)
		{
			$this->data['transaction_engagement_letter_additional_info'] = $this->transaction_model->getTransactionEngagementLetterAdditionalInfo($transaction_id);

			$this->data['transaction_engagement_letter'] = $this->transaction_model->getTransactionEngagementLetter($transaction_id);

			$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
		}
		else
		{
			$query_previous_el = $this->db->query("SELECT transaction_master.* FROM transaction_master where id = (SELECT max(id) FROM transaction_master where transaction_task_id = '30' and company_code = '".$company_code."')");

	        if ($query_previous_el->num_rows() > 0) 
	        {
	        	$query_previous_el = $query_previous_el->result_array();

	        	$this->data['transaction_engagement_letter_additional_info'] = $this->transaction_model->getTransactionEngagementLetterAdditionalInfo($query_previous_el[0]["id"]);

				$this->data['transaction_engagement_letter'] = $this->transaction_model->getTransactionEngagementLetter($query_previous_el[0]["id"]);
	        }
	        else
	        {
				$this->data['get_service_proposal_service_info'] = $this->transaction_model->get_service_proposal_service_info($company_code);

				$this->data['get_service_proposal_service_info_id'] = $this->transaction_model->get_service_proposal_service_info_id($company_code);

				$director_result_1 = $this->db->query("select officer.* from client_officers left join officer on officer.id = client_officers.officer_id and officer.field_type = client_officers.field_type where client_officers.id='".$this->data['client_info'][0]->director_signature_1."'");

	            $director_result_1 = $director_result_1->result_array();

	            $this->data['director_result_1'] = $director_result_1[0]["name"];
	        }
		}

		$this->data['transaction_engagement_letter_list'] = $this->transaction_model->getTransactionEngagementLetterList();

		$interface = $this->load->view('/views/transaction/engagement_letter.php', '', TRUE);

		$get_all_firm_info = $this->transaction_model->getAllFirmInfo();
		for($j = 0; $j < count($get_all_firm_info); $j++)
		{
			$res_firm[$get_all_firm_info[$j]->id] = $get_all_firm_info[$j]->name;
		}
		$this->data['get_all_firm_info'] = $res_firm;

		$result_currency = $this->db->query("select * from currency order by currency");
		$result_currency = $result_currency->result_array();
		for($j = 0; $j < count($result_currency); $j++)
		{
			$res[$result_currency[$j]['id']] = $result_currency[$j]['currency'];
		}
		$this->data["currency"] = $res;

		$result_unit_pricing = $this->db->query("select * from unit_pricing");
		$result_unit_pricing = $result_unit_pricing->result_array();
		for($j = 0; $j < count($result_unit_pricing); $j++)
		{
			$res_unit_pricing[$result_unit_pricing[$j]['id']] = $result_unit_pricing[$j]['unit_pricing_name'];
		}
		$this->data["unit_pricing_name"] = $res_unit_pricing;

		echo json_encode(array("interface" => $interface, $this->data));


	}

	public function get_service_proposal_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$company_name = $_POST["company_name"];

		$q = $this->db->query('select * from client where company_name = "'.$company_name.'" and firm_id = "'.$this->session->userdata('firm_id').'" and deleted = 0');

		if ($q->num_rows() > 0)   	
		{
			$q = $q->result_array();
			$this->data["company_code"] = $q[0]["company_code"];
			$this->data['client_detail'] = $q;
			$this->data['client_contact_person'] = $this->transaction_model->getClientContactInfo($q[0]["company_code"]);
		}
		else
		{
			$this->data["company_code"] = false;
			$this->data['client_detail'] = false;
			$this->data['client_contact_person'] = false;
		}
		// if ($q->num_rows() > 0) 
  //       {
			if($transaction_id != null)
			{
				$this->data['transaction_service_proposal'] = $this->transaction_model->getTransactionServiceProposal($transaction_id);

				$this->data['transaction_service_proposal_contact_person'] = $this->transaction_model->getTransactionClientContactInfo($transaction_id, $company_code);

				$this->data['transaction_service_proposal_service_info'] = $this->transaction_model->getTransactionServiceProposalServiceInfo($transaction_id);

				$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
			}

			$this->data['transaction_our_service_list'] = $this->transaction_model->getTransactionOurServiceList();

			$interface = $this->load->view('/views/transaction/service_proposal.php', '', TRUE);

			$get_all_firm_info = $this->transaction_model->getAllFirmInfo();
			for($j = 0; $j < count($get_all_firm_info); $j++)
			{
				$res_firm[$get_all_firm_info[$j]->id] = $get_all_firm_info[$j]->name;
			}
			$this->data['get_all_firm_info'] = $res_firm;

			$result_currency = $this->db->query("select * from currency order by currency");
			$result_currency = $result_currency->result_array();
			for($j = 0; $j < count($result_currency); $j++)
			{
				$res[$result_currency[$j]['id']] = $result_currency[$j]['currency'];
			}
			$this->data["currency"] = $res;

			$result_unit_pricing = $this->db->query("select * from unit_pricing");
			$result_unit_pricing = $result_unit_pricing->result_array();
			for($j = 0; $j < count($result_unit_pricing); $j++)
			{
				$res_unit_pricing[$result_unit_pricing[$j]['id']] = $result_unit_pricing[$j]['unit_pricing_name'];
			}
			$this->data["unit_pricing_name"] = $res_unit_pricing;

			

			echo json_encode(array("interface" => $interface, $this->data));
		// }
		// else
		// {
		// 	echo json_encode(array("error" => "Please enter correct registration number."));
		// }
	}

	public function get_change_of_FYE_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registra_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where registration_no = "'.$registra_no.'" and firm_id = "'.$this->session->userdata('firm_id').'" and deleted = 0');

		if ($q->num_rows() > 0) 
        {
			if($transaction_id != null)
			{
				$this->data['transaction_change_FYE'] = $this->transaction_model->getTransactionChangeFYE($transaction_id);

				$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
			}

			$interface = $this->load->view('/views/transaction/change_of_FYE.php', '', TRUE);

			echo json_encode(array("interface" => $interface, $this->data));
		}
		else
		{
			echo json_encode(array("error" => "Please enter correct registration number."));
		}
	}

	public function get_change_of_biz_activity_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registra_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where registration_no = "'.$registra_no.'" and firm_id = "'.$this->session->userdata('firm_id').'" and deleted = 0');

		if ($q->num_rows() > 0) 
        {
			if($transaction_id != null)
			{
				$this->data['transaction_change_biz_activity'] = $this->transaction_model->getTransactionChangeBizActivity($transaction_id);

				$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
			}

			$interface = $this->load->view('/views/transaction/change_of_biz_activity.php', '', TRUE);

			echo json_encode(array("interface" => $interface, $this->data));
		}
		else
		{
			echo json_encode(array("error" => "Please enter correct registration number."));
		}
	}

	public function get_before_cut_off_date_member()
	{
		$devidend_of_cut_off_date = $_POST["devidend_of_cut_off_date"];
		$company_code = $_POST["company_code"];

		$where = 'STR_TO_DATE("'. $devidend_of_cut_off_date. '","%d/%m/%Y") >= STR_TO_DATE(member_shares.transaction_date,"%d/%m/%Y") AND';

		$query = $this->db->query('select member_shares.*, member_shares.number_of_share as number_of_share, member_shares.amount_share as amount_share, member_shares.no_of_share_paid as no_of_share_paid, member_shares.amount_paid as amount_paid, member_shares.transaction_type, DATE_FORMAT(STR_TO_DATE(member_shares.transaction_date, "%d/%m/%Y"), "%d/%m/%Y") as transaction_date, STR_TO_DATE(member_shares.transaction_date, "%d/%m/%Y") as trans_date, officer.field_type as officer_field_type, officer.identification_no, officer.name, officer.address_type as officer_address_type, officer.postal_code1, officer.street_name1, officer.building_name1, officer.unit_no1, officer.unit_no2, officer.foreign_address1, officer.foreign_address2, officer.foreign_address3, officer_company.field_type as officer_company_field_type, officer_company.register_no, officer_company.company_name, officer_company.address_type as officer_company_address_type, officer_company.company_postal_code, officer_company.company_street_name, officer_company.company_building_name, officer_company.company_unit_no1, officer_company.company_unit_no2, officer_company.company_foreign_address1, officer_company.company_foreign_address2, officer_company.company_foreign_address3, officer_company.country_of_incorporation, client.registration_no, "client" as client_field_type, client.company_name as client_company_name, client.postal_code as client_postal_code, client.street_name as client_street_name, client.building_name as client_building_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency, certificate.certificate_no, certificate.status, nationality.nationality as nationality_name from member_shares left join certificate on certificate.officer_id = member_shares.officer_id and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join nationality on nationality.id = officer.nationality where '.$where.' member_shares.company_code="'.$company_code.'" ORDER BY officer_company.company_name, officer.name, trans_date');

		if ($query->num_rows() > 0) {
            foreach (($query->result()) as $row) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        echo (FALSE);
	}

	public function get_issue_dividend()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registra_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where registration_no = "'.$registra_no.'" and firm_id = "'.$this->session->userdata('firm_id').'" and deleted = 0');

		if ($q->num_rows() > 0) 
        {
        	$client_array = $q->result_array();

        	$this->data["company_code"] = $client_array[0]['company_code'];

			if($transaction_id != null)
			{
				$this->data['transaction_issue_dividend'] = $this->transaction_model->getTransactionIssueDividend($transaction_id);

				$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
			}

			$filing_info = $this->db->query("select company_code, year_end, agm, due_date_175, 175_extended_to, due_date_201, 201_extended_to from filing where company_code='".$q->result_array()[0]["company_code"]."' order by filing.id DESC LIMIT 2");

            if ($filing_info->num_rows() > 0) 
            {
            	$filing_info = $filing_info->result_array();
            	$this->data["year_end"] = $filing_info[0]['year_end'];
            }
            else
            {
            	$this->data["year_end"] = "";
            }

            // $officer_info = $this->db->query('select client_officers.*, officer.field_type as officer_field_type, officer.identification_no, officer.name, officer_company.field_type as officer_company_field_type, officer_company.register_no, officer_company.company_name, officer_company.address_type as officer_company_address_type, client_officers_position.position as position_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type left join client_officers_position on client_officers.position = client_officers_position.id where company_code ="'.$q->result_array()[0]["company_code"].'" and date_of_cessation = "" and client_officers.position = 1');

            // if ($officer_info->num_rows() > 0) 
            // {
            // 	$officer_info = $officer_info->result_array();
            // 	$this->data["officer_info"] = $officer_info;
            // }
            // else
            // {
            // 	$this->data["officer_info"] = "";
            // }

            $result_currency = $this->db->query("select * from currency order by currency");
			$result_currency = $result_currency->result_array();
			for($j = 0; $j < count($result_currency); $j++)
			{
				$res[$result_currency[$j]['id']] = $result_currency[$j]['currency'];
			}
			$this->data["currency"] = $res;

			$result_nature = $this->db->query("select * from nature");
			$result_nature = $result_nature->result_array();
			for($j = 0; $j < count($result_nature); $j++)
			{
				$res_nature[$result_nature[$j]['id']] = $result_nature[$j]['nature_name'];
			}
			$this->data["nature"] = $res_nature;

			$interface = $this->load->view('/views/transaction/issue_dividend.php', '', TRUE);

			echo json_encode(array("interface" => $interface, $this->data));
		}
		else
		{
			echo json_encode(array("error" => "Please enter correct registration number."));
		}
	}

	public function get_issue_director_fee()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registra_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where registration_no = "'.$registra_no.'" and firm_id = "'.$this->session->userdata('firm_id').'" and deleted = 0');

		if ($q->num_rows() > 0) 
        {
			if($transaction_id != null)
			{
				$this->data['transaction_issue_director_fee'] = $this->transaction_model->getTransactionIssueDirectorFee($transaction_id);

				$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
			}

			$filing_info = $this->db->query("select company_code, year_end, agm, due_date_175, 175_extended_to, due_date_201, 201_extended_to from filing where company_code='".$q->result_array()[0]["company_code"]."' order by filing.id DESC LIMIT 2");

            if ($filing_info->num_rows() > 0) 
            {
            	$filing_info = $filing_info->result_array();
            	$this->data["year_end"] = $filing_info[0]['year_end'];
            }
            else
            {
            	$this->data["year_end"] = "";
            }

            $officer_info = $this->db->query('select client_officers.*, officer.field_type as officer_field_type, officer.identification_no, officer.name, officer_company.field_type as officer_company_field_type, officer_company.register_no, officer_company.company_name, officer_company.address_type as officer_company_address_type, client_officers_position.position as position_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type left join client_officers_position on client_officers.position = client_officers_position.id where company_code ="'.$q->result_array()[0]["company_code"].'" and date_of_cessation = "" and client_officers.position = 1');

            if ($officer_info->num_rows() > 0) 
            {
            	$officer_info = $officer_info->result_array();
            	$this->data["officer_info"] = $officer_info;
            }
            else
            {
            	$this->data["officer_info"] = "";
            }

            $result_currency = $this->db->query("select * from currency order by currency");
			$result_currency = $result_currency->result_array();
			for($j = 0; $j < count($result_currency); $j++)
			{
				$res[$result_currency[$j]['id']] = $result_currency[$j]['currency'];
			}
			$this->data["currency"] = $res;

			$interface = $this->load->view('/views/transaction/issue_director_fee.php', '', TRUE);

			echo json_encode(array("interface" => $interface, $this->data));
		}
		else
		{
			echo json_encode(array("error" => "Please enter correct registration number."));
		}
	}

	public function get_incorporation_subsidiary_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registra_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where registration_no = "'.$registra_no.'" and firm_id = "'.$this->session->userdata('firm_id').'" and deleted = 0');

		if ($q->num_rows() > 0) 
        {
			if($transaction_id != null)
			{
				$this->data['transaction_incorporation_subsidiary'] = $this->transaction_model->getTransactionIncorporationSubsidiary($transaction_id);

				$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
			}

			$interface = $this->load->view('/views/transaction/incorporation_subsidiary.php', '', TRUE);

			echo json_encode(array("interface" => $interface, $this->data));
		}
		else
		{
			echo json_encode(array("error" => "Please enter correct registration number."));
		}
	}

	public function get_opening_bank_account_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registra_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where registration_no = "'.$registra_no.'" and firm_id = "'.$this->session->userdata('firm_id').'" and deleted = 0');

		if ($q->num_rows() > 0) 
        {
			if($transaction_id != null)
			{
				$this->data['transaction_opening_bank_acc'] = $this->transaction_model->getTransactionOpeningBankAccount($transaction_id);

				$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
			}

			$this->data['bank_name'] = $this->transaction_model->getBankName();
			$this->data['manner_of_operation'] = $this->transaction_model->getMannerOfOperation();

			$interface = $this->load->view('/views/transaction/opening_bank_account.php', '', TRUE);

			echo json_encode(array("interface" => $interface, $this->data));
		}
		else
		{
			echo json_encode(array("error" => "Please enter correct registration number."));
		}
	}

	public function select_banker_info()
	{
		$bank_id = $_POST["bank_id"];
		
		$q = $this->db->query('select bank_contact_person_info.*, bank_name.bank_name from bank_contact_person_info left join bank_name on bank_name.id = bank_contact_person_info.bank_id where bank_id = "'.$bank_id.'" AND user_admin_code_id = "'.$this->session->userdata("user_admin_code_id").'"');

		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        echo (FALSE);
	}

	public function get_strike_off_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registra_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where registration_no = "'.$registra_no.'" and firm_id = "'.$this->session->userdata('firm_id').'" and deleted = 0');

		if ($q->num_rows() > 0) 
        {
			if($transaction_id != null)
			{
				$this->data['transaction_strike_off'] = $this->transaction_model->getTransactionStrikeOff($transaction_id);

				$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
			}

			$interface = $this->load->view('/views/transaction/strike_off.php', '', TRUE);

			$result_reason_for_application = $this->db->query("select * from reason_for_application");
			$result_reason_for_application = $result_reason_for_application->result_array();
			for($j = 0; $j < count($result_reason_for_application); $j++)
			{
				$res[$result_reason_for_application[$j]['id']] = $result_reason_for_application[$j]['reason_for_application_content'];
			}
			$this->data["reason_for_application"] = $res;

			echo json_encode(array("interface" => $interface, $this->data));
		}
		else
		{
			echo json_encode(array("error" => "Please enter correct registration number."));
		}
	}

	public function get_change_of_company_name_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registra_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where registration_no = "'.$registra_no.'" and firm_id = "'.$this->session->userdata('firm_id').'" and deleted = 0');

		if ($q->num_rows() > 0) 
        {
			if($transaction_id != null)
			{
				$this->data['transaction_change_company_name'] = $this->transaction_model->getTransactionChangeCompanyName($transaction_id);

				$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
			}

			$interface = $this->load->view('/views/transaction/change_of_company_name.php', '', TRUE);

			echo json_encode(array("interface" => $interface, $this->data));
		}
		else
		{
			echo json_encode(array("error" => "Please enter correct registration number."));
		}
	}

	public function get_change_of_reg_ofis_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registra_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where registration_no = "'.$registra_no.'" and firm_id = "'.$this->session->userdata('firm_id').'" and deleted = 0');

		if ($q->num_rows() > 0) 
        {
			if($transaction_id != null)
			{
				$this->data['transaction_change_reg_ofis'] = $this->transaction_model->getTransactionChangeRegOfisAddress($transaction_id);

				$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
			}

			$this->db->select('our_service_registration_address.id, our_service_info.service_name, our_service_registration_address.postal_code, our_service_registration_address.street_name, our_service_registration_address.building_name, our_service_registration_address.unit_no1, our_service_registration_address.unit_no2')
                ->from('firm')
                ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
                ->join('our_service_info', 'our_service_info.user_admin_code_id = '.$this->session->userdata('user_admin_code_id').' and service_type = 7', 'left')
                ->join('our_service_registration_address', 'our_service_registration_address.our_service_info_id = our_service_info.id', 'left')
                ->where('user_firm.user_id = '.$this->session->userdata('user_id'))
                ->where('user_firm.in_use = 1');
        	$registered_address = $this->db->get();

	        $registered_address_info = $registered_address->result_array();

	        $this->data['registered_address_info'] = $registered_address_info;

			$interface = $this->load->view('/views/transaction/change_of_reg_ofis.php', '', TRUE);

			echo json_encode(array("interface" => $interface, $this->data));
		}
		else
		{
			echo json_encode(array("error" => "Please enter correct registration number."));
		}
	}

	public function get_resign_of_director_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registra_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where registration_no = "'.$registra_no.'" and firm_id = "'.$this->session->userdata('firm_id').'" and deleted = 0');

		if ($q->num_rows() > 0) 
        {
			if($transaction_id != null)
			{
				$this->data['transaction_resign_director'] = $this->transaction_model->getTransactionResignClientOfficer($transaction_id);

				$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
			}

			$interface = $this->load->view('/views/transaction/resign_director.php', '', TRUE);

			echo json_encode(array("interface" => $interface, $this->data));
		}
		else
		{
			echo json_encode(array("error" => "Please enter correct registration number."));
		}
	}

	public function save_company_info()
	{
    	// $check_unique_client_code = $this->db->get_where("transaction_client", array("client_code != ''", "client_code" => $_POST['client_code'], "company_code !=" => $_POST['transaction_company_code']));

  //   	if (!$check_unique_client_code->num_rows())
		// {

		

			$transaction['firm_id'] = $this->session->userdata('firm_id');
			$transaction['company_code'] = $_POST['transaction_company_code'];
			$transaction['transaction_code'] = $this->getTransactionCode();
			$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
			$transaction['remarks'] = "";
			$transaction['status'] = 1;
			$transaction['effective_date'] = "";
			$transaction['last_edited_by'] = $this->session->userdata('user_id');
			


			$data['created_by'] = $this->session->userdata('user_id');
			$data['firm_id'] = $this->session->userdata('firm_id');
			$data['acquried_by'] = 1;
			$data['company_code'] = $_POST['transaction_company_code'];
			$company_code = $data['company_code'];
			$data['client_code'] = strtoupper($_POST['client_code']);
			$data['registration_no'] = strtoupper($_POST['registration_no']);
			$registration_no = $data['registration_no'];
			$data['company_name'] = strtoupper($_POST['company_name']);
			$data['former_name'] = "";
			$data['incorporation_date'] = "";
			$data['company_type'] = $_POST['company_type'];
			$data['status'] = 1;
			$data['activity1'] = strtoupper($_POST['activity1']);
			$data['activity2'] = strtoupper($_POST['activity2']);
			$data['registered_address'] = (isset($_POST['use_registered_address'])) ? 1 : 0;
			$data['our_service_regis_address_id'] = $_POST['service_reg_off'];
			$data['postal_code'] = strtoupper($_POST['postal_code']);
			$data['street_name'] = strtoupper($_POST['street_name']);
			$data['building_name'] = strtoupper($_POST['building_name']);
			$data['unit_no1'] = strtoupper($_POST['unit_no1']);
			$data['unit_no2'] = strtoupper($_POST['unit_no2']);
			//$data['listed_company']=(isset($_POST['listedcompany'])) ? 1 : 0;
			
			$q = $this->db->get_where("transaction_client", array("company_code" => $_POST['transaction_company_code']));

			if (!$q->num_rows())
			{
				$transaction['created_by'] = $this->session->userdata('user_id');

				$this->db->insert("transaction_master",$transaction);
				$transaction_id = $this->db->insert_id();
				$this->session->set_userdata(array(
	                'transaction_id'  => $transaction_id,
	            ));

				$data['transaction_id'] = $transaction_id;
				$this->db->insert("transaction_client",$data);
				$transaction_code = $transaction['transaction_code'];
				
			} 
			else 
			{
				$q = $q->result_array();
				$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
				$this->db->update("transaction_master",$edit_transaction,array("id" => $this->session->userdata('transaction_id')));

				$this->db->update("transaction_client",$data,array("company_code" =>  $_POST['transaction_company_code']));
				$transaction_id = $this->session->userdata('transaction_id');

				$transaction_code = $_POST['transaction_code'];
				
			}

			$this->data['document'] = $this->transaction_model->get_all_document($_POST['transaction_task_id'], $_POST['transaction_company_code'], '');

			/*-------cancel_by_system-----*/
			$previous_transaction_client_query = $this->db->get_where("transaction_client", array("company_name" => strtoupper($_POST['company_name']), "transaction_id !=" => $transaction_id));

			if ($previous_transaction_client_query->num_rows())
			{
				$previous_transaction_client_query = $previous_transaction_client_query->result_array();

				for($f = 0; $f < count($previous_transaction_client_query); $f++)
				{
					$cancel_by_system["status"] = 4;
					$this->db->update("transaction_master",$cancel_by_system,array("id" => $previous_transaction_client_query[$f]["transaction_id"], "status" => "1"));
				}
			}
			/*------------------*/
	        echo json_encode(array("Status" => 1,'message' => 'Information Updated', 'title' => 'Updated', 'document' => $this->data['document'], "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
		    

	    // }
	    // else
	    // {
	    // 	echo json_encode(array("Status" => 2,'message' => 'The client code already in the system.', 'title' => 'Error'));
	    // }
	}

	public function get_client_officers_position()
	{
		$position = $_POST['position'];

		$result = $this->db->query("select * from client_officers_position where id != 7");

		/*$result = $this->db->query("select * from client_officers AS A where position = 'Director' AND company_code='".$company_code."' AND NOT EXISTS (SELECT alternate_of from client_officers AS B where A.id = B.alternate_of)");*/

		$result = $result->result_array();
		//echo json_encode($result);
		if(!$result) {
			throw new exception("Client officers position not found.");
		}
		$res = array();
		foreach($result as $row) {
			$res[$row['id']] = $row['position'];
		}
		
		/*$ci =& get_instance();*/
		if ($position != "")
		{
			$selected_client_officers_position = $position;
		}
		else
		{
			$selected_client_officers_position = null;
		}
       	

		$data = array('status'=>'success', 'tp'=>1, 'msg'=>"Client officers position fetched successfully.", 'result'=>$res, 'selected_client_officers_position'=>$selected_client_officers_position);

	    echo json_encode($data);
	}
	
	public function get_director()
	{
		$company_code = $_POST['company_code'];
		$alternate_of = $_POST['alternate_of'];

		$result = $this->db->query("select transaction_client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id and transaction_client_officers.field_type = officer.field_type left join officer_company on transaction_client_officers.officer_id = officer_company.id and transaction_client_officers.field_type = officer_company.field_type where position = '1' AND company_code='".$company_code."'");

		$result = $result->result_array();

		$res = array();
		foreach($result as $row) {
			if($row['name'] != null)
			{
				$res[$row['id']] = $row['name'];
			}
			else if ($row['company_name'] != null)
			{
				$res[$row['id']] = $row['company_name'];
			}
			
		}
		
		if ($alternate_of != "")
		{
			$selected_director = $alternate_of;
		}
		else
		{
			$selected_director = null;
		}
       	

		$data = array('status'=>'success', 'tp'=>1, 'msg'=>"Director fetched successfully.", 'result'=>$res, 'selected_director'=>$selected_director);

	    echo json_encode($data);

	}

	public function get_transaction_person()
	{
		// echo "A";
		$identification_register_no = $_POST['identification_register_no'];
		$company_code = $_POST['company_code'];

		/*$query = "(select id, field_type, identification_no, name from officer where YEAR(CURDATE()) - YEAR(date_of_birth) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(date_of_birth), '-', DAY(date_of_birth)) ,'%Y-%c-%e') > CURDATE(), 1, 0) > 18 AND identification_no LIKE '%".$identification_register_no."%') 
		           UNION
		           (select id, field_type, company_name, register_no from officer_company where register_no LIKE '%".$identification_register_no."%')";*/

		$query = "(select id, field_type, identification_no, name from officer where YEAR(CURDATE()) - YEAR(date_of_birth) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(date_of_birth), '-', DAY(date_of_birth)) ,'%Y-%c-%e') > CURDATE(), 1, 0) > 18 AND identification_no = '".$identification_register_no."' AND user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."') 
		           UNION
		           (select officer_company.id, officer_company.field_type, officer_company.register_no, officer_company.company_name from officer_company where register_no = '".$identification_register_no."' AND user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."')
		           	UNION
		           	(select id, 'client' AS field_type, registration_no, company_name from client where registration_no = '".$identification_register_no."' AND client.firm_id = '".$this->session->userdata("firm_id")."' AND deleted = 0)";

		$q = $this->db->query($query);
			//echo json_encode($q->result_array());
		if ($q->num_rows() > 0) {

			$y = $q->result_array();

			if($y[0]["field_type"] == "company")
			{
				$t = $this->db->query("select * from transaction_client_officers where officer_id = '".$y[0]["id"]."' AND field_type = '".$y[0]["field_type"]."' AND company_code = '".$company_code."'");

				if ($t->num_rows() > 0) 
				{
					echo json_encode(array("status" => 2));
				}
				else
				{
					echo json_encode(array("status" => 1, "info" => $q->result()[0]));
				}

			}
			else
			{
				echo json_encode(array("status" => 1, "info" => $q->result()[0]));
			}
			
            // foreach (($q->result()) as $row) {
                // $data[] = $row;
            // }
            // return $data;
        } else echo json_encode(array("status" => 1, "info" => $q->result()[0]));

		
		
		// echo $gid;
		// $this->sma->print_arrays($_POST);
	}

	public function delete_transaction_officer ()
	{
		$id = $_POST["client_officer_id"];

		if($id != "")
		{
			$query = $this->db->get_where("transaction_client_officers", array("alternate_of" => $_POST['client_officer_id']));

			if ($query->num_rows())//if don't have anythings
			{
				echo json_encode(array("Status" => 2));
			}
			else
			{
				$this->db->delete("transaction_client_officers",array('id'=>$id));

				$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
				$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

				echo json_encode(array("Status" => 1));
				
			}
		}
		else
		{
			echo json_encode(array("Status" => 1));
		}
	}

	public function get_officer()
	{

		$officer_id = $_POST['officer_id'];
		$identification_register_no = $_POST['identification_register_no'];
		$position = $_POST['position'];
		$company_code = $_POST['company_code'];

		if ($position == "5")
		{
			$q = $this->db->query("select * from officer_company where register_no='".$identification_register_no."' AND user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."' ");
			//echo json_encode($q);
			if ($q->num_rows() > 0) {

				$check_member_share = $q->result_array();

				$chk_member = $this->db->query("select * from member_shares where officer_id='".$check_member_share[0]["id"]."' AND field_type = '".$check_member_share[0]["field_type"]."' AND company_code = '".$company_code."'");

				if ($chk_member->num_rows() > 0) {
					echo json_encode(array("status" => 5, "message" => "This person is a member for this company.", "title" => "Error"));
				}
				else
				{
					echo json_encode(array("status" => 1, "info" => $q->result()[0]));
				}

				
	        } 
	        else 
	        {
	        	$q = $this->db->query("select * from officer where YEAR(CURDATE()) - YEAR(date_of_birth) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(date_of_birth), '-', DAY(date_of_birth)) ,'%Y-%c-%e') > CURDATE(), 1, 0) > 18 AND identification_no='".$identification_register_no."' AND user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");
				
				if ($q->num_rows() > 0) {
					echo json_encode(array("status" => 4, "message" => "This person should be a company.", "title" => "Error"));

		        } else echo json_encode(array("status" => 3));
	        }
		}
		else
		{
			if($officer_id == null)
			{
				$q = $this->db->query("select * from officer where YEAR(CURDATE()) - YEAR(date_of_birth) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(date_of_birth), '-', DAY(date_of_birth)) ,'%Y-%c-%e') > CURDATE(), 1, 0) > 18 AND identification_no='".$identification_register_no."' AND user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");
				
				if ($q->num_rows() > 0) {
					echo json_encode(array("status" => 1, "info" => $q->result()[0]));
		        } 
		        else 
		        {
		        	$q = $this->db->query("select * from officer_company where register_no='".$identification_register_no."' AND user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");

					if ($q->num_rows() > 0) {
						echo json_encode(array("status" => 4, "message" => "This person should be an individual.", "title" => "Error"));

			        } 
			        else echo json_encode(array("status" => 3));
		        }
			}
			else
			{
				$result = $this->db->query("select * from transaction_client_officers where id = '".$officer_id."'");

				$result = $result->result_array();

				$q = $this->db->query("select * from officer where YEAR(CURDATE()) - YEAR(date_of_birth) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(date_of_birth), '-', DAY(date_of_birth)) ,'%Y-%c-%e') > CURDATE(), 1, 0) > 18 AND identification_no='".$identification_register_no."' AND user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");

				$officer_result = $q->result_array();

				if($result[0]["officer_id"] == $officer_result[0]["id"])
				{
					echo json_encode(array("status" => 2, "message" => "He/She can not be the alternate for his/her own.", "title" => "Error"));
				}
				else
				{
					if ($q->num_rows() > 0) {
						echo json_encode(array("status" => 1, "info" => $q->result()[0]));
			        } 
			        else 
			        {
			        	$q = $this->db->query("select * from officer_company where register_no='".$identification_register_no."' AND user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");

						if ($q->num_rows() > 0) {
							echo json_encode(array("status" => 4, "message" => "This person should be an individual.", "title" => "Error"));
				        } 
				        else echo json_encode(array("status" => 3));
			        }
				}
			}
			
		}
	}

	public function add_officer ()
	{
		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		for($i = 0; $i < count($_POST['identification_register_no']); $i++ )
		{
			$data['transaction_id']=$this->session->userdata('transaction_id');
			$data['company_code']=$_POST['company_code'];
			$data['officer_id']=$_POST['officer_id'][$i];
			$data['field_type']=$_POST['officer_field_type'][$i];
			$data['position']=$_POST['position'][$i];
			if($_POST['alternate_of'][$i] == null)
			{
				$data['alternate_of']=' ';
				$check_alternate_of = ' ';
			}
			else
			{
				$data['alternate_of']=$_POST['alternate_of'][$i];
				$check_alternate_of = $_POST['alternate_of'][$i];;
			}
			
			$data['date_of_appointment'] = "";
			$data['date_of_cessation'] = "";

			$data['retiring'] = 0;
			
			$q = $this->db->get_where("transaction_client_officers", array("id" => $_POST['client_officer_id'][$i]));

			if (!$q->num_rows())
			{
				$this->db->insert("transaction_client_officers",$data);
				$insert_client_officers_id = $this->db->insert_id();
				
			} 
			else 
			{
				$this->db->update("transaction_client_officers",$data,array("id" => $_POST['client_officer_id'][$i]));
				
			}

		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
				$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		$this->data['transaction_client_officers'] = $this->transaction_model->getTransactionClientOfficer($this->session->userdata('transaction_id'), $_POST['company_code']);

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_client_officers" => $this->data['transaction_client_officers']));
	}

	public function add_controller ()
	{
		
		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		for($i = 0; $i < count($_POST['identification_register_no']); $i++ )
		{
			$data['transaction_id']=$this->session->userdata('transaction_id');
			$data['company_code']=$_POST['company_code'];
			$data['officer_id']=$_POST['officer_id'][$i];
			$data['field_type']=$_POST['officer_field_type'][$i];
			$data['date_of_birth']=$_POST['date_of_birth'][$i];
			$data['nationality_name']=strtoupper($_POST['nationality'][$i]);
			$data['address']=strtoupper($_POST['address'][$i]);

			$q = $this->db->get_where("transaction_client_controller", array("id" => $_POST['client_controller_id'][$i]));

			if (!$q->num_rows())
			{
				$this->db->insert("transaction_client_controller",$data);
				$insert_client_controller_id = $this->db->insert_id();

				
			} 
			else 
			{
				$this->db->update("transaction_client_controller",$data,array("id" => $_POST['client_controller_id'][$i]));
				
			}
		
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
				$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		$this->data['transaction_client_controller'] = $this->transaction_model->getTransactionClientController($this->session->userdata('transaction_id'), $_POST['company_code']);

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_client_controller" => $this->data['transaction_client_controller']));
	}

	public function delete_controller ()
	{
		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		$id = $_POST["client_controller_id"];
		echo $this->db->delete("transaction_client_controller",array('id'=>$id));
	}

	public function save_previous_secretarial_info()
	{
		$data['company_code'] = $_POST['company_code'];
		$data['transaction_id'] = $this->session->userdata('transaction_id');
		$data['company_name'] = strtoupper($_POST['previous_secretarial_company_name']);
		$data['postal_code'] = strtoupper($_POST['previous_secretarial_postal_code']);
		$data['street_name'] = strtoupper($_POST['previous_secretarial_street_name']);
		$data['building_name'] = strtoupper($_POST['previous_secretarial_building_name']);
		$data['unit_no1'] = strtoupper($_POST['previous_secretarial_unit_no1']);
		$data['unit_no2'] = strtoupper($_POST['previous_secretarial_unit_no2']);
		
		$q = $this->db->get_where("transaction_previous_secretarial", array("transaction_id" => $this->session->userdata('transaction_id')));

		if (!$q->num_rows())
		{

			$this->db->insert("transaction_previous_secretarial",$data);
			
		} 
		else 
		{
			

			$this->db->update("transaction_previous_secretarial",$data,array("transaction_id" => $this->session->userdata('transaction_id')));
			
			
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" => $this->session->userdata('transaction_id')));

        echo json_encode(array("Status" => 1,'message' => 'Information Updated', 'title' => 'Updated'));
	}

	public function save_filing_info()
	{
		$data['company_code'] = $_POST['company_code'];
		$data['transaction_id'] = $this->session->userdata('transaction_id');
		$data['year_end'] = $_POST['year_end'];
		$data['financial_year_period'] = $_POST['financial_year_period'];
		
		$q = $this->db->get_where("transaction_filing", array("transaction_id" => $this->session->userdata('transaction_id')));

		if (!$q->num_rows())
		{

			$this->db->insert("transaction_filing",$data);
			
		} 
		else 
		{
			

			$this->db->update("transaction_filing",$data,array("transaction_id" => $this->session->userdata('transaction_id')));
			
			
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" => $this->session->userdata('transaction_id')));

        echo json_encode(array("Status" => 1,'message' => 'Information Updated', 'title' => 'Updated'));
	}

	public function add_client_billing_info()
	{
		$_POST['client_billing_info_id'] = array_values($_POST['client_billing_info_id']);
		$_POST['service'] = array_values($_POST['service']);
		$_POST['invoice_description'] = array_values($_POST['invoice_description']);
		$_POST['amount'] = array_values($_POST['amount']);
		$_POST['currency'] = array_values($_POST['currency']);
		$_POST['unit_pricing'] = array_values($_POST['unit_pricing']);
		//$_POST['frequency'] = array_values($_POST['frequency']);
		// $_POST['from'] = array_values($_POST['from']);
		// $_POST['to'] = array_values($_POST['to']);
		// $_POST['type_of_day'] = array_values($_POST['type_of_day']);
		// $_POST['days'] = array_values($_POST['days']);
		// $_POST['from_billing_cycle'] = array_values($_POST['from_billing_cycle']);
		// $_POST['to_billing_cycle'] = array_values($_POST['to_billing_cycle']);

		$this->db->delete('transaction_client_billing_info', array("company_code" => $_POST['company_code']));

		for($i = 0; $i < count($_POST['client_billing_info_id']); $i++ )
		{
			
			$transaction_client_billing_info['transaction_id'] = $this->session->userdata('transaction_id');
			$transaction_client_billing_info['company_code'] = $_POST['company_code'];
			$transaction_client_billing_info['client_billing_info_id'] = $_POST['client_billing_info_id'][$i];
			$transaction_client_billing_info['service'] = $_POST['service'][$i];
			$transaction_client_billing_info['invoice_description'] = $_POST['invoice_description'][$i];
			//(int)str_replace(',', '', $amount[$p]);
			$transaction_client_billing_info['amount'] = (float)str_replace(',', '', $_POST['amount'][$i]);
			$transaction_client_billing_info['currency'] = (float)str_replace(',', '', $_POST['currency'][$i]);
			$transaction_client_billing_info['unit_pricing'] = (float)str_replace(',', '', $_POST['unit_pricing'][$i]);
			// $transaction_client_billing_info['frequency'] = $_POST['frequency'][$i];
			// $transaction_client_billing_info["from"] = "";
			// $transaction_client_billing_info["to"] = "";

			// $transaction_client_billing_info["type_of_day"] = "";
			// $transaction_client_billing_info["days"] = "";
			// $transaction_client_billing_info["from_billing_cycle"] = $_POST['from_billing_cycle'][$i];
			// $transaction_client_billing_info["to_billing_cycle"] = $_POST['to_billing_cycle'][$i];

			$this->db->insert("transaction_client_billing_info",$transaction_client_billing_info);
			


		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		$this->data['transaction_billing'] = $this->transaction_model->getTransactionClientBilling($this->session->userdata('transaction_id'), $_POST['company_code']);

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', 'transaction_client_billing' => $this->data['transaction_billing']));

	}

	public function get_billing_info_service()
	{

		// $service = $_POST['service'];
		// $company_code = $_POST['company_code'];

		// $ci =& get_instance();

		// $query = "select billing_info_service.*, billing_info_service_category.category_description from billing_info_service left join billing_info_service_category on billing_info_service_category.category_code = billing_info_service.category_code order by billing_info_service.id";

		// $selected_query = "select A.id from billing_info_service AS A WHERE EXISTS (SELECT service from transaction_client_billing_info AS B WHERE company_code = '".$company_code."' AND A.id = B.service)";

		// $result = $ci->db->query($query);
		// $selected_result = $ci->db->query($selected_query);
		
  //       //echo json_encode($result->result_array());
  //       $result = $result->result_array();
  //       $selected_result = $selected_result->result_array();


  //       if(!$result) {
  //         throw new exception("Service not found.");
  //       }

  //       $selected_res = array();
  //       foreach($selected_result as $key => $row) {
  //           $selected_res[$key] = $row['id'];
  //       }

  //       //$ci =& get_instance();
  //       if($service != "")
  //       {
  //       	$select_service = $service;
  //       }
  //       else
  //       {
  //       	$select_service = null;
  //       }
        

  //       $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Service fetched successfully.", 'result'=>$result, 'selected_service'=>$select_service, 'selected_query'=> $selected_res);

  //       echo json_encode($data);



        $service = $_POST['service'];
		$company_code = $_POST['company_code'];

		$ci =& get_instance();

		$query = "select our_service_info.*, billing_info_service_category.category_description from our_service_info left join billing_info_service_category on billing_info_service_category.id = our_service_info.service_type where our_service_info.user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."' order by our_service_info.id";

		$selected_query = "select A.id from our_service_info AS A WHERE EXISTS (SELECT service from client_billing_info AS B WHERE company_code = '".$company_code."' AND A.id = B.service)";
		

		$selected_billing_info_service_category = "select billing_info_service_category.* from billing_info_service_category";

		$result = $ci->db->query($query);
		$selected_result = $ci->db->query($selected_query);
		$selected_billing_info_service_category = $ci->db->query($selected_billing_info_service_category);
		
        //echo json_encode($result->result_array());
        $result = $result->result_array();
        $selected_result = $selected_result->result_array();
        $selected_billing_info_service_category = $selected_billing_info_service_category->result_array();

        if (count($selected_result) == 0) {
            $selected_querys = "select A.id from our_service_info AS A WHERE EXISTS (SELECT service from billing_template AS B WHERE A.id = B.service)";

            $selected_result = $ci->db->query($selected_querys);

            $selected_result = $selected_result->result_array();
        }

        if(!$result) {
          throw new exception("Service not found.");
        }

        // $res = array();
        // foreach($result as $row) {
        //     $res[$row['id']] = $row['service'];
        // }

        $selected_res = array();
        foreach($selected_result as $key => $row) {
            $selected_res[$key] = $row['id'];
        }

        //$ci =& get_instance();
        if($service != "")
        {
        	$select_service = $service;
        }
        else
        {
        	$select_service = null;
        }
        

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Service fetched successfully.", 'result'=>$result, 'selected_service'=>$select_service, 'selected_query'=> $selected_res, 'selected_billing_info_service_category' => $selected_billing_info_service_category, 'firm_id'=>$this->session->userdata("firm_id"));

        echo json_encode($data);
	}

	// public function add_share_capital ()
	// {
		
	// 	//$class = $_POST['class'];

	// 	for($i = 0; $i < count($_POST['class']); $i++ )
	// 	{		
	// 		$data['transaction_id']=$this->session->userdata("transaction_id");
 //        	$data['company_code']=$_POST['company_code'];
	// 		$data['class_id']=$_POST['class'][$i];

	// 		if($_POST['other_class'][$i] == null)
	// 		{
	// 			$data['other_class']=' ';
	// 		}
	// 		else
	// 			$data['other_class']=$_POST['other_class'][$i];

	// 		$data['currency_id']=$_POST['currency'][$i];


	// 		$q = $this->db->get_where("transaction_client_member_share_capital", array("id" => $_POST['share_capital_id'][$i]));

	// 		if (!$q->num_rows())
	// 		{				
	// 			$this->db->insert("transaction_client_member_share_capital",$data);
	// 			$insert_share_capital_id = $this->db->insert_id();
	// 		} 
	// 		else 
	// 		{	
	// 			$this->db->update("transaction_client_member_share_capital",$data,array("id" => $_POST['share_capital_id'][$i]));

	// 		}
	// 		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_share_capital_id" => $insert_share_capital_id));
			
	// 	}	
	// }

	public function save_share_transfer()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = strtoupper($_POST['registration_no']);
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = "";
		$transaction['last_edited_by'] = $this->session->userdata('user_id');

		$transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code" => $_POST['transaction_code']));

		if (!$transaction_master_query->num_rows())
		{
			$transaction['created_by'] = $this->session->userdata('user_id');

			$this->db->insert("transaction_master",$transaction);
			$transaction_master_id = $this->db->insert_id();
			$this->session->set_userdata(array(
                'transaction_id'  => $transaction_master_id,
            ));
            $transaction_code = $transaction['transaction_code'];

		} 
		else 
		{
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $this->session->userdata('transaction_id')));
			$transaction_master_id = $this->session->userdata('transaction_id');
			$transaction_code = $_POST["transaction_code"];
			
		}

		//From(buyback)
		$_POST['transfer_id'] = array_values($_POST['transfer_id']); //buyback_id
		$_POST['officer_id'] = array_values($_POST['officer_id']);//officer_id
		$_POST['field_type'] = array_values($_POST['field_type']);//field_type
		$_POST['person_name'] = array_values($_POST['person_name']);//member_name
		$_POST['share_transfer'] = array_values($_POST['share_transfer']);//share_buyback
		

		$_POST['current_share'] = array_values($_POST['current_share']);//current_number_of_share
		$_POST['amount_share'] = array_values($_POST['amount_share']);//current_amount_share
		$_POST['no_of_share_paid'] = array_values($_POST['no_of_share_paid']);
		$_POST['amount_paid'] = array_values($_POST['amount_paid']);
		$_POST['consideration'] = array_values($_POST['consideration']);
		$_POST['from_certificate'] = array_values($_POST['from_certificate']);//new_certificate_no

		//To(Allotment)
		
		$_POST['to_officer_id'] = array_values($_POST['to_officer_id']);//officer_id
		$_POST['number_of_share_to'] = array_values($_POST['number_of_share_to']); //number_of_share
		$_POST['to_field_type'] = array_values($_POST['to_field_type']);//field_type
		$_POST['to_certificate'] = array_values($_POST['to_certificate']);//new_certificate_no
		$_POST['to_id'] = array_values($_POST['to_id']); //member_share_id
		$_POST['to_person_name'] = array_values($_POST['to_person_name']);
		$_POST['id_to'] = array_values($_POST['id_to']);

		$total_no_of_share = 0;
		$total_amount_share = 0;
		$per_share = 0;

		for($p = 0; $p < count($_POST['current_share']); $p++ )
		{
			$total_no_of_share += (int)str_replace(',', '',$_POST['current_share'][$p]);
		}

		for($q = 0; $q < count($_POST['amount_share']); $q++ )
		{
			$total_amount_share += (int)str_replace(',', '',$_POST['amount_share'][$q]);
		}

		$per_share = $total_amount_share/$total_no_of_share;

		$transaction_id = "TR-".mt_rand(100000000, 999999999);

		for($i = 0; $i < count($_POST['officer_id']); $i++ )
		{
			$new_number_of_share_from = (int)str_replace(',', '', $_POST['current_share'][$i]) - (int)str_replace(',', '', $_POST['share_transfer'][$i]);

			$buyback["transaction_page_id"] = $this->session->userdata("transaction_id"); 
			$buyback["company_code"] = $_POST['company_code'];
			$buyback["client_member_share_capital_id"] = $_POST['client_member_share_capital_id'];
			$buyback["officer_id"] = $_POST['officer_id'][$i];
			$buyback["field_type"] = $_POST['field_type'][$i];
			$buyback["transaction_type"] = $_POST['transaction_type'];
			$buyback["number_of_share"] = -((int)str_replace(',', '', $_POST['share_transfer'][$i]));
			$buyback["amount_share"] = -((float)str_replace(',', '', $_POST['share_transfer'][$i]) * $per_share);
			$buyback["no_of_share_paid"] = -((int)str_replace(',', '', $_POST['share_transfer'][$i]));
			$buyback["amount_paid"] = -((float)str_replace(',', '', $_POST['share_transfer'][$i]) * $per_share);
			$buyback["consideration"] = ((float)str_replace(',', '', $_POST['consideration'][$i]));
			//$buyback["cert_status"] = 1;

			$q = $this->db->get_where("transaction_member_shares", array("id" => $_POST['transfer_id'][$i]));

			$this->db->delete('transaction_transfer_member_id', array("transfer_from_id" => $_POST['transfer_id'][$i]));

			$previous_member_share_info = $q->result_array();

			if (!$q->num_rows())
			{				
				$buyback["transaction_id"] = $transaction_id;
				$buyback["cert_status"] = 1;
				$this->db->insert("transaction_member_shares",$buyback);
				$insert_transfer_from_id = $this->db->insert_id();

			} 
			else 
			{	
				$this->db->delete('transaction_member_shares', array("id" => $_POST['transfer_id'][$i]));

				$buyback["transaction_id"] = $previous_member_share_info[0]["transaction_id"];
				$buyback["cert_status"] = $previous_member_share_info[0]["cert_status"];
				$this->db->insert("transaction_member_shares",$buyback);
				$insert_transfer_from_id = $this->db->insert_id();
				
			}

			$buyback_certificate["transaction_page_id"] = $this->session->userdata("transaction_id"); 
			$buyback_certificate["company_code"] = $_POST['company_code'];
			$buyback_certificate["client_member_share_capital_id"] = $_POST['client_member_share_capital_id'];
			$buyback_certificate["officer_id"] = $_POST['officer_id'][$i];
			$buyback_certificate["field_type"] = $_POST['field_type'][$i];

			$buyback_certificate["number_of_share"] = -((int)str_replace(',', '', $_POST['share_transfer'][$i]));
			$buyback_certificate["amount_share"] = -((float)str_replace(',', '', $_POST['share_transfer'][$i]) * $per_share);
			$buyback_certificate["no_of_share_paid"] = -((int)str_replace(',', '', $_POST['share_transfer'][$i]));
			$buyback_certificate["amount_paid"] = -((float)str_replace(',', '', $_POST['share_transfer'][$i]) * $per_share);

			$buyback_certificate["certificate_no"] = $_POST['from_certificate'][$i];
			$buyback_certificate["new_certificate_no"] = $_POST['from_certificate'][$i];
			$buyback_certificate["status"] = 1;	

			$q = $this->db->get_where("transaction_certificate", array("id" => $_POST['cert_id'][$i]));

			$previous_buyback_cert_info = $q->result_array();

			if (!$q->num_rows())
			{				
				$buyback_certificate["transaction_id"] = $transaction_id;
				$this->db->insert("transaction_certificate",$buyback_certificate);
				

			} 
			else 
			{	
				$this->db->delete('transaction_certificate', array("id" => $_POST['cert_id'][$i]));

				$buyback_certificate["transaction_id"] = $previous_buyback_cert_info[0]["transaction_id"];
				$this->db->insert("transaction_certificate",$buyback_certificate);
				
			}
		}

		for($i = 0; $i < count($_POST['to_officer_id']); $i++ )
		{
			$allotment["transaction_page_id"] = $this->session->userdata("transaction_id"); 
			$allotment["company_code"] = $_POST['company_code'];
			$allotment["client_member_share_capital_id"] = $_POST['client_member_share_capital_id'];
			$allotment["officer_id"] = $_POST['to_officer_id'][$i];
			$allotment["field_type"] = $_POST['to_field_type'][$i];
			$allotment["transaction_type"] = $_POST['transaction_type'];

			$allotment["number_of_share"] = (int)str_replace(',', '', $_POST['number_of_share_to'][$i]);
			$allotment["amount_share"] = $allotment["number_of_share"] * $per_share;
			$allotment["no_of_share_paid"] = (int)str_replace(',', '', $_POST['number_of_share_to'][$i]);
			$allotment["amount_paid"] = $allotment["no_of_share_paid"] * $per_share;
			//$allotment["cert_status"] = 1;

			$q = $this->db->get_where("transaction_member_shares", array("id" => $_POST['to_id'][$i]));

			$previous_member_share_info = $q->result_array();

			if (!$q->num_rows())
			{				
				$allotment["transaction_id"] = $transaction_id;
				$allotment["cert_status"] = 1;
				$this->db->insert("transaction_member_shares",$allotment);
				$insert_transfer_to_id = $this->db->insert_id();

			} 
			else 
			{	
				
				$this->db->delete('transaction_member_shares', array("id" => $_POST['to_id'][$i]));

				$allotment["cert_status"] = $previous_member_share_info[0]["cert_status"];
				$allotment["transaction_id"] = $previous_member_share_info[0]["transaction_id"];
				$this->db->insert("transaction_member_shares",$allotment);
				$insert_transfer_to_id = $this->db->insert_id();
			}

			$this->db->delete('transaction_transfer_member_id', array("transaction_id" => $this->session->userdata("transaction_id"), "transfer_from_id" => $_POST['transfer_id'][$i], "transfer_to_id" => $_POST['to_id'][$i]));

			$transaction_transfer_member_id['transaction_id'] = $this->session->userdata("transaction_id");
			$transaction_transfer_member_id['transfer_from_id'] = $insert_transfer_from_id;
			$transaction_transfer_member_id['transfer_to_id'] = $insert_transfer_to_id;

			$this->db->insert("transaction_transfer_member_id",$transaction_transfer_member_id);

			$certificate["transaction_page_id"] = $this->session->userdata("transaction_id"); 
			$certificate["company_code"] = $_POST['company_code'];
			$certificate["client_member_share_capital_id"] = $_POST['client_member_share_capital_id'];
			$certificate["officer_id"] = $_POST['to_officer_id'][$i];
			$certificate["field_type"] = $_POST['to_field_type'][$i];
			
			$certificate["number_of_share"] = (int)str_replace(',', '', $_POST['number_of_share_to'][$i]);
			$certificate["amount_share"] = $allotment["number_of_share"] * $per_share;
			$certificate["no_of_share_paid"] = (int)str_replace(',', '', $_POST['number_of_share_to'][$i]);
			$certificate["amount_paid"] = $allotment["no_of_share_paid"] * $per_share;

			$certificate["certificate_no"] = $_POST['to_certificate'][$i];
			$certificate["new_certificate_no"] = $_POST['to_certificate'][$i];
			$certificate["status"] = 1;	

			$q = $this->db->get_where("transaction_certificate", array("id" => $_POST['to_cert_id'][$i]));

			$previous_buyback_cert_info = $q->result_array();

			if (!$q->num_rows())
			{				
				$certificate["transaction_id"] = $transaction_id;
				$this->db->insert("transaction_certificate",$certificate);
				

			} 
			else 
			{	
				$this->db->delete('transaction_certificate', array("id" => $_POST['to_cert_id'][$i]));

				$certificate["transaction_id"] = $previous_buyback_cert_info[0]["transaction_id"];
				$this->db->insert("transaction_certificate",$certificate);

				
			}
		}
		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		$this->data['transaction_member'] = $this->transaction_model->getTransactionClientTransferMemberInfo($this->session->userdata("transaction_id"));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', 'transaction_member' => $this->data['transaction_member'], "transaction_master_id" => $transaction_master_id, "transaction_code" => $transaction_code));
	}

	public function save_share_allotment()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = strtoupper($_POST['registration_no']);
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = "";
		$transaction['last_edited_by'] = $this->session->userdata('user_id');

		$transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code" => $_POST['transaction_code']));

		if (!$transaction_master_query->num_rows())
		{
			$transaction['created_by'] = $this->session->userdata('user_id');

			$this->db->insert("transaction_master",$transaction);
			$transaction_id = $this->db->insert_id();
			$this->session->set_userdata(array(
                'transaction_id'  => $transaction_id,
            ));
            $transaction_code = $transaction['transaction_code'];

		} 
		else 
		{
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $this->session->userdata('transaction_id')));
			$transaction_id = $this->session->userdata('transaction_id');
			$transaction_code = $_POST["transaction_code"];
		}

		$transaction_share_allotment_date['transaction_master_id'] = $transaction_id;
		$transaction_share_allotment_date['director_meeting_date'] = $_POST['director_meeting_date'];
		$transaction_share_allotment_date['director_meeting_time'] = $_POST['director_meeting_time'];
		$transaction_share_allotment_date['member_meeting_date'] = $_POST['member_meeting_date'];
		$transaction_share_allotment_date['member_meeting_time'] = $_POST['member_meeting_time'];
		$transaction_share_allotment_date['address_type'] = $_POST['address_type'];
		//registered_offis_address
		if(isset($_POST['registered_postal_code1']))
        {
            $transaction_share_allotment_date['registered_postal_code1'] = strtoupper($_POST['registered_postal_code1']);
        }
        else
        {
            $transaction_share_allotment_date['registered_postal_code1'] = "";
        }

        if(isset($_POST['registered_street_name1']))
        {
            $transaction_share_allotment_date['registered_street_name1'] = strtoupper($_POST['registered_street_name1']);
        }
        else
        {
            $transaction_share_allotment_date['registered_street_name1'] = "";
        }

        if(isset($_POST['registered_building_name1']))
        {
            $transaction_share_allotment_date['registered_building_name1'] = strtoupper($_POST['registered_building_name1']);
        }
        else
        {
            $transaction_share_allotment_date['registered_building_name1'] = "";
        }

        if(isset($_POST['registered_unit_no1']))
        {
            $transaction_share_allotment_date['registered_unit_no1'] = strtoupper($_POST['registered_unit_no1']);
        }
        else
        {
            $transaction_share_allotment_date['registered_unit_no1'] = "";
        }

        if(isset($_POST['registered_unit_no2']))
        {
            $transaction_share_allotment_date['registered_unit_no2'] = strtoupper($_POST['registered_unit_no2']);
        }
        else
        {
            $transaction_share_allotment_date['registered_unit_no2'] = "";
        }
        //Local_address
		if(isset($_POST['postal_code1']))
        {
            $transaction_share_allotment_date['postal_code1'] = strtoupper($_POST['postal_code1']);
        }
        else
        {
            $transaction_share_allotment_date['postal_code1'] = "";
        }

        if(isset($_POST['street_name1']))
        {
            $transaction_share_allotment_date['street_name1'] = strtoupper($_POST['street_name1']);
        }
        else
        {
            $transaction_share_allotment_date['street_name1'] = "";
        }
		$transaction_share_allotment_date['building_name1'] = strtoupper($_POST['building_name1']);
        $transaction_share_allotment_date['unit_no1'] = strtoupper($_POST['unit_no1']);
        $transaction_share_allotment_date['unit_no2'] = strtoupper($_POST['unit_no2']);
        //foreign_address
        if(isset($_POST['foreign_address1']))
        {
            $transaction_share_allotment_date['foreign_address1'] = strtoupper($_POST['foreign_address1']);
        }
        else
        {
            $transaction_share_allotment_date['foreign_address1'] = "";
        }
        if(isset($_POST['foreign_address2']))
        {
            $transaction_share_allotment_date['foreign_address2'] = strtoupper($_POST['foreign_address2']);
        }
        else
        {
            $transaction_share_allotment_date['foreign_address2'] = "";
        }
        if(isset($_POST['foreign_address3']))
        {
            $transaction_share_allotment_date['foreign_address3'] = strtoupper($_POST['foreign_address3']);
        }
        else
        {
            $transaction_share_allotment_date['foreign_address3'] = "";
        }

		$transaction_share_allotment_date_query = $this->db->get_where("transaction_share_allotment_date", array("transaction_master_id" => $transaction_id));

		if (!$transaction_share_allotment_date_query->num_rows())
		{
			$this->db->insert("transaction_share_allotment_date",$transaction_share_allotment_date);

		} 
		else 
		{
			$this->db->update("transaction_share_allotment_date",$transaction_share_allotment_date,array("transaction_master_id" => $transaction_id));

		}

		$_POST['officer_id'] = array_values($_POST['officer_id']);
		$_POST['other_class'] = array_values($_POST['other_class']);
		$_POST['class'] = array_values($_POST['class']);
		$_POST['currency'] = array_values($_POST['currency']);
		$_POST['number_of_share'] = array_values($_POST['number_of_share']);
		$_POST['amount_share'] = array_values($_POST['amount_share']);
		$_POST['amount_paid'] = array_values($_POST['amount_paid']);
		$_POST['field_type'] = array_values($_POST['field_type']);
		$_POST['no_of_share_paid'] = array_values($_POST['no_of_share_paid']);
		$_POST['member_share_id'] = array_values($_POST['member_share_id']);
		$_POST['certificate'] = array_values($_POST['certificate']);
		$allot_transaction_id = "TR-".mt_rand(100000000, 999999999);

		$this->db->delete('transaction_client_member_share_capital', array("transaction_id" => $this->session->userdata("transaction_id")));

		for($i = 0; $i < count($_POST['officer_id']); $i++ )
		{
			if(isset($_POST['other_class'][$i]))
			{
				$share_capital["other_class"] = $_POST['other_class'][$i];
			}
			else
			{	
				$share_capital["other_class"] = "";
			}

			$transaction_share_capital = $this->db->get_where("transaction_client_member_share_capital", array("transaction_id" => $this->session->userdata("transaction_id"), "company_code" => $_POST['company_code'], "class_id" => $_POST['class'][$i], "currency_id" => $_POST['currency'][$i], "other_class" => $share_capital["other_class"]));

			if (!$transaction_share_capital->num_rows())
			{
				$share_capital["transaction_id"] = $this->session->userdata("transaction_id");
				$share_capital["company_code"] = $_POST['company_code'];
				$share_capital["class_id"] = $_POST['class'][$i];
				
				
				$share_capital["currency_id"] = $_POST['currency'][$i];

				$this->db->insert("transaction_client_member_share_capital",$share_capital);
				$transaction_share_capital_id = $this->db->insert_id();
			}
			else
			{
				$transaction_share_capital_array = $transaction_share_capital->result_array();
				$transaction_share_capital_id = $transaction_share_capital_array[0]["id"];
			}

			$allotment["transaction_page_id"] = $this->session->userdata("transaction_id"); 
			$allotment["company_code"] = $_POST['company_code'];
			$allotment["client_member_share_capital_id"] = $transaction_share_capital_id;
			$allotment["officer_id"] = $_POST['officer_id'][$i];
			$allotment["field_type"] = $_POST['field_type'][$i];
			$allotment["transaction_type"] = $_POST['transaction_type'];
			$allotment["number_of_share"] = (int)str_replace(',', '', $_POST['number_of_share'][$i]);
			$allotment["amount_share"] = (float)str_replace(',', '', $_POST['amount_share'][$i]);
			$allotment["no_of_share_paid"] = (int)str_replace(',', '', $_POST['no_of_share_paid'][$i]);
			$allotment["amount_paid"] = (float)str_replace(',', '', $_POST['amount_paid'][$i]);


			$certificate["transaction_page_id"] = $this->session->userdata("transaction_id"); 
			$certificate["company_code"] = $_POST['company_code'];
			$certificate["client_member_share_capital_id"] = $transaction_share_capital_id;
			$certificate["officer_id"] = $_POST['officer_id'][$i];
			$certificate["field_type"] = $_POST['field_type'][$i];
			
			$certificate["number_of_share"] = (int)str_replace(',', '', $_POST['number_of_share'][$i]);
			$certificate["amount_share"] = (float)str_replace(',', '', $_POST['amount_share'][$i]);
			$certificate["no_of_share_paid"] = (int)str_replace(',', '', $_POST['no_of_share_paid'][$i]);
			$certificate["amount_paid"] = (float)str_replace(',', '', $_POST['amount_paid'][$i]);

			$certificate["certificate_no"] = $_POST['certificate'][$i];
			$certificate["new_certificate_no"] = $_POST['certificate'][$i];
			$certificate["status"] = 1;	

			$this->db->delete('transaction_certificate', array("id" => $_POST['cert_id'][$i]));

			$this->db->delete('transaction_member_shares', array("id" => $_POST['member_share_id'][$i]));

			
			$allotment["transaction_id"] = $allot_transaction_id;
			$allotment["cert_status"] = 1;
			$this->db->insert("transaction_member_shares",$allotment);

			
			$certificate["transaction_id"] = $allot_transaction_id;
			$this->db->insert("transaction_certificate",$certificate);

		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		$this->data['transaction_member'] = $this->transaction_model->getTransactionClientMember($this->session->userdata("transaction_id"), $_POST['company_code']);

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', 'transaction_member' => $this->data['transaction_member'], "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function save_allotment()
	{
		$_POST['officer_id'] = array_values($_POST['officer_id']);
		//$_POST['other_class'] = array_values($_POST['other_class']);
		$_POST['class'] = array_values($_POST['class']);
		$_POST['currency'] = array_values($_POST['currency']);
		$_POST['number_of_share'] = array_values($_POST['number_of_share']);
		$_POST['amount_share'] = array_values($_POST['amount_share']);
		$_POST['amount_paid'] = array_values($_POST['amount_paid']);
		$_POST['field_type'] = array_values($_POST['field_type']);
		$_POST['no_of_share_paid'] = array_values($_POST['no_of_share_paid']);
		$_POST['member_share_id'] = array_values($_POST['member_share_id']);
		$_POST['certificate'] = array_values($_POST['certificate']);
		$transaction_id = "TR-".mt_rand(100000000, 999999999);


		for($i = 0; $i < count($_POST['officer_id']); $i++ )
		{
			if(isset($_POST['other_class'][$i]))
			{
				$share_capital["other_class"] = $_POST['other_class'][$i];
			}
			else
			{	
				$share_capital["other_class"] = "";
			}

			$transaction_share_capital = $this->db->get_where("transaction_client_member_share_capital", array("transaction_id" => $this->session->userdata("transaction_id"), "company_code" => $_POST['company_code'], "class_id" => $_POST['class'][$i], "currency_id" => $_POST['currency'][$i], "other_class" => $share_capital["other_class"]));

			if (!$transaction_share_capital->num_rows())
			{
				$share_capital["transaction_id"] = $this->session->userdata("transaction_id");
				$share_capital["company_code"] = $_POST['company_code'];
				$share_capital["class_id"] = $_POST['class'][$i];
				
				
				$share_capital["currency_id"] = $_POST['currency'][$i];

				$this->db->insert("transaction_client_member_share_capital",$share_capital);
				$transaction_share_capital_id = $this->db->insert_id();
			}
			else
			{
				$transaction_share_capital_array = $transaction_share_capital->result_array();
				$transaction_share_capital_id = $transaction_share_capital_array[0]["id"];
			}

			$allotment["transaction_page_id"] = $this->session->userdata("transaction_id"); 
			$allotment["company_code"] = $_POST['company_code'];
			$allotment["client_member_share_capital_id"] = $transaction_share_capital_id;
			$allotment["officer_id"] = $_POST['officer_id'][$i];
			$allotment["field_type"] = $_POST['field_type'][$i];
			$allotment["transaction_type"] = $_POST['transaction_type'];
			$allotment["number_of_share"] = (int)str_replace(',', '', $_POST['number_of_share'][$i]);
			$allotment["amount_share"] = (float)str_replace(',', '', $_POST['amount_share'][$i]);
			$allotment["no_of_share_paid"] = (int)str_replace(',', '', $_POST['no_of_share_paid'][$i]);
			$allotment["amount_paid"] = (float)str_replace(',', '', $_POST['amount_paid'][$i]);


			$certificate["transaction_page_id"] = $this->session->userdata("transaction_id"); 
			$certificate["company_code"] = $_POST['company_code'];
			//$certificate["transaction_date"] = $_POST['date'];
			$certificate["client_member_share_capital_id"] = $transaction_share_capital_id;
			$certificate["officer_id"] = $_POST['officer_id'][$i];
			$certificate["field_type"] = $_POST['field_type'][$i];
			
			$certificate["number_of_share"] = (int)str_replace(',', '', $_POST['number_of_share'][$i]);
			$certificate["amount_share"] = (float)str_replace(',', '', $_POST['amount_share'][$i]);
			$certificate["no_of_share_paid"] = (int)str_replace(',', '', $_POST['no_of_share_paid'][$i]);
			$certificate["amount_paid"] = (float)str_replace(',', '', $_POST['amount_paid'][$i]);

			$certificate["certificate_no"] = $_POST['certificate'][$i];
			$certificate["new_certificate_no"] = $_POST['certificate'][$i];
			$certificate["status"] = 1;	

			$this->db->delete('transaction_certificate', array("id" => $_POST['cert_id'][$i]));

			$this->db->delete('transaction_member_shares', array("id" => $_POST['member_share_id'][$i]));

			
			$allotment["transaction_id"] = $transaction_id;
			$allotment["cert_status"] = 1;
			$this->db->insert("transaction_member_shares",$allotment);

			
			$certificate["transaction_id"] = $transaction_id;
			$this->db->insert("transaction_certificate",$certificate);

		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		$this->data['transaction_member'] = $this->transaction_model->getTransactionClientMember($this->session->userdata("transaction_id"), $_POST['company_code']);

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', 'transaction_member' => $this->data['transaction_member']));
	}

	public function delete_member_transfer ()
	{
		$transaction_transfer_member_id = $_POST["transaction_transfer_member_id"];
		$transaction_id = $_POST["transaction_id"];
		$from_transfer_member_id = $_POST["from_transfer_member_id"];
		$to_transfer_member_id = $_POST["to_transfer_member_id"];
		$from_cert_id = $_POST["from_cert_id"];
		$to_cert_id = $_POST["to_cert_id"];

		$cert_id = $this->transaction_model->checkCertIdInfo($transaction_id, $from_transfer_member_id);

		//echo json_encode($cert_id);

		$this->db->delete("transaction_transfer_member_id",array('transaction_id'=>$transaction_id, 'transfer_from_id'=>$from_transfer_member_id));

		for($m = 0; $m < count($cert_id); $m++)
		{
			$this->db->delete("transaction_member_shares",array('id'=>$cert_id[$m]->from_transfer_member_id));
			$this->db->delete("transaction_member_shares",array('id'=>$cert_id[$m]->to_transfer_member_id));
			$this->db->delete("transaction_certificate",array('id'=>$cert_id[$m]->from_cert_id));
			$this->db->delete("transaction_certificate",array('id'=>$cert_id[$m]->to_cert_id));
		}
		

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		//echo json_encode(array("Status" => 1));

		$this->data['transaction_member'] = $this->transaction_model->getTransactionClientTransferMemberInfo($transaction_id);

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', 'transaction_member' => $this->data['transaction_member']));
	}

	public function delete_member ()
	{
		$member_share_id = $_POST["member_share_id"];
		$cert_id = $_POST["cert_id"];

		$member_query = $this->db->get_where("transaction_member_shares", array("id" => $member_share_id));
		$member_query = $member_query->result_array();

		$this->db->delete("transaction_member_shares",array('id'=>$member_share_id));
		$this->db->delete("transaction_certificate",array('id'=>$cert_id));

		
		$check_share_capital_query = $this->db->get_where("transaction_member_shares", array("client_member_share_capital_id" => $member_query[0]['client_member_share_capital_id']));

		if (!$check_share_capital_query->num_rows())
		{
			$this->db->delete('transaction_client_member_share_capital', array("id" => $member_query[0]['client_member_share_capital_id']));
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		echo json_encode(array("Status" => 1));
	}

	public function delete_billing()
	{
		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		$id = $_POST["client_billing_info_id"];
		echo $this->db->delete("transaction_client_billing_info",array('client_billing_info_id'=>$id));
	}

	public function add_setup_info()
	{
		$transaction_client_signing_info["transaction_id"] = $this->session->userdata("transaction_id"); 
		$transaction_client_signing_info['company_code'] = $_POST['company_code'];
		$transaction_client_signing_info['chairman'] = $_POST['chairman'];
		$transaction_client_signing_info['director_signature_1'] = $_POST['director_signature_1'];

		if(isset($_POST['director_signature_2']))
        {
            $transaction_client_signing_info['director_signature_2'] = $_POST['director_signature_2'];
        }
        else
        {
        	$transaction_client_signing_info['director_signature_2'] = 0;
        }
		

		$transaction_client_contact_info["transaction_id"] = $this->session->userdata("transaction_id"); 
		$transaction_client_contact_info['company_code'] = $_POST['company_code'];
		$transaction_client_contact_info['name'] = $_POST['contact_name'];


		// for($i = 0; $i < count($_POST['director_retiring_client_officer_id']); $i++ )
		// {
		// 	$director_retiring['retiring'] = $_POST['hidden_director_retiring_checkbox'][$i];
		// 	$this->db->where(array("id" => $_POST['director_retiring_client_officer_id'][$i]));
		// 	$this->db->update("client_officers",$director_retiring);
		// }

		

		$p = $this->db->get_where("transaction_client_signing_info", array("company_code" => $_POST['company_code'], "transaction_id" => $this->session->userdata("transaction_id")));

		if (!$p->num_rows())
		{				
			$this->db->insert("transaction_client_signing_info",$transaction_client_signing_info);
			//$insert_share_capital_id = $this->db->insert_id();
		} 
		else 
		{	
			$this->db->where(array("company_code" => $_POST['company_code'], "transaction_id" => $this->session->userdata("transaction_id")));
			$this->db->update("transaction_client_signing_info",$transaction_client_signing_info);
		}

		$query = $this->db->get_where("transaction_client_contact_info", array("company_code" => $_POST['company_code'], "transaction_id" => $this->session->userdata("transaction_id")));

		if (!$query->num_rows())
		{				
			$this->db->insert("transaction_client_contact_info",$transaction_client_contact_info);
			$client_contact_info_id = $this->db->insert_id();
			for($g = 0; $g < count($_POST['hidden_contact_phone']); $g++)
            {
                if($_POST['hidden_contact_phone'][$g] != "")
                {
                    $contactPhone['client_contact_info_id'] = $client_contact_info_id;
                    $contactPhone['phone'] = strtoupper($_POST['hidden_contact_phone'][$g]);
                    if($_POST['contact_phone_primary'] == $_POST['hidden_contact_phone'][$g])
                    {
                        $contactPhone['primary_phone'] = 1;
                    }
                    else
                    {
                        $contactPhone['primary_phone'] = 0;
                    }
                    $this->db->insert('transaction_client_contact_info_phone', $contactPhone);
                }
            }

            for($g = 0; $g < count($_POST['contact_email']); $g++)
            {
                if($_POST['contact_email'][$g] != "")
                {
                    $contactEmail['client_contact_info_id'] = $client_contact_info_id;
                    $contactEmail['email'] = strtoupper($_POST['contact_email'][$g]);
                    if($_POST['contact_email_primary'] == $_POST['contact_email'][$g])
                    {
                        $contactEmail['primary_email'] = 1;
                    }
                    else
                    {
                        $contactEmail['primary_email'] = 0;
                    }
                    $this->db->insert('transaction_client_contact_info_email', $contactEmail);
                }
            }
		} 
		else 
		{	
			$this->db->where(array("company_code" => $_POST['company_code'], "transaction_id" => $this->session->userdata("transaction_id")));
			$this->db->update("transaction_client_contact_info",$transaction_client_contact_info);
			$client_contact_information = $query->result_array(); 
			$client_contact_info_id = $client_contact_information[0]["id"];

			$this->db->delete("transaction_client_contact_info_phone",array('client_contact_info_id'=>$client_contact_info_id));

			for($g = 0; $g < count($_POST['hidden_contact_phone']); $g++)
            {
                if($_POST['hidden_contact_phone'][$g] != "")
                {
                    $contactPhone['client_contact_info_id'] = $client_contact_info_id;
                    $contactPhone['phone'] = strtoupper($_POST['hidden_contact_phone'][$g]);
                    if($_POST['contact_phone_primary'] == $_POST['hidden_contact_phone'][$g])
                    {
                        $contactPhone['primary_phone'] = 1;
                    }
                    else
                    {
                        $contactPhone['primary_phone'] = 0;
                    }
                    $this->db->insert('transaction_client_contact_info_phone', $contactPhone);
                }
            }

            $this->db->delete("transaction_client_contact_info_email",array('client_contact_info_id'=>$client_contact_info_id));

            for($g = 0; $g < count($_POST['contact_email']); $g++)
            {
                if($_POST['contact_email'][$g] != "")
                {
                    $contactEmail['client_contact_info_id'] = $client_contact_info_id;
                    $contactEmail['email'] = strtoupper($_POST['contact_email'][$g]);
                    if($_POST['contact_email_primary'] == $_POST['contact_email'][$g])
                    {
                        $contactEmail['primary_email'] = 1;
                    }
                    else
                    {
                        $contactEmail['primary_email'] = 0;
                    }
                    $this->db->insert('transaction_client_contact_info_email', $contactEmail);
                }
            }
		}

		$this->db->delete("transaction_client_setup_reminder",array('company_code'=>$_POST['company_code'], "transaction_id" => $this->session->userdata("transaction_id")));

		if($_POST['select_reminder'] != null)
		{
			for($g = 0; $g < count($_POST['select_reminder']); $g++)
            {
            	$reminder["transaction_id"] = $this->session->userdata("transaction_id"); 
            	$reminder['company_code'] = $_POST['company_code'];
            	$reminder['selected_reminder'] = $_POST['select_reminder'][$g];

            	$this->db->insert('transaction_client_setup_reminder', $reminder);
            }
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
		
	}

	public function lodge_transaction()
	{
		// //echo 'hi';
		$transaction_master_id = $_POST['transaction_master_id'];
		$company_code = $_POST['company_code'];
		$transaction_code = $this->getTransactionCode();
		$lodgement_date = $_POST['lodgement_date'];
		$registration_no = strtoupper($_POST['registration_no']);
		$transaction_task = $_POST['transaction_task'];
		$transaction_status = $_POST['tran_status'];
		$cancellation_reason = $_POST['cancellation_reason'];

		//echo json_encode($transaction_task == 30);
		if($transaction_task == 1)
		{
			//client
			$check_client = $this->db->get_where("client", array("company_code" => $company_code, "firm_id" => $this->session->userdata('firm_id'), "deleted" => 0));

			$this->db->select('firm_id, acquried_by, company_code, client_code, registration_no, company_name, former_name, incorporation_date, company_type, status, activity1, activity2, registered_address, our_service_regis_address_id, postal_code, street_name, building_name, unit_no1, unit_no2, auto_generate, deleted, created_by, created_at');
	        $this->db->from('transaction_client');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_client = $this->db->get();

			if (!$check_client->num_rows())
			{	//echo json_encode(value)
				foreach($check_transaction_client->result() as $r) {
					$r->registration_no = $registration_no;
					$r->incorporation_date = $lodgement_date;
			        $this->db->insert("client",$r);
			    }
			} 
			else 
			{
				foreach($check_transaction_client->result() as $r) {
					$r->registration_no = $registration_no;
					$r->incorporation_date = $lodgement_date;
			    	$this->db->update("client",$r,array("company_code" =>  $company_code, "firm_id" => $this->session->userdata('firm_id'), "deleted" => 0));
			    }
				
			}
			//officer
			$check_client_officers = $this->db->get_where("client_officers", array("company_code" => $company_code));

			$this->db->select('id, company_code, position, alternate_of, officer_id, field_type, date_of_appointment, date_of_cessation, retiring, created_at');
	        $this->db->from('transaction_client_officers');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_client_officers = $this->db->get();

			$this->db->select('company_code, chairman, director_signature_1, director_signature_2, created_at');
	        $this->db->from('transaction_client_signing_info');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_client_signing_info = $this->db->get();

			$this->db->delete("client_officers",array('company_code'=>$company_code));

			foreach($check_transaction_client_officers->result() as $r) {

				$r->date_of_appointment = $lodgement_date;
				$k['company_code'] = $r->company_code;
				$k['position'] = $r->position;
				$k['alternate_of'] = $r->alternate_of;
				$k['officer_id'] = $r->officer_id;
				$k['field_type'] = $r->field_type;
				$k['date_of_appointment'] = $r->date_of_appointment;
				$k['date_of_cessation'] = $r->date_of_cessation;
				$k['retiring'] = $r->retiring;
				$k['created_at'] = $r->created_at;

		        $this->db->insert("client_officers",$k);
		        //echo json_encode($check_transaction_client_signing_info->result_array()[0]['director_signature_1']);
		        if($r->id == $check_transaction_client_signing_info->result_array()[0]['director_signature_1'])
		        {
		        	$director_signature_1 = $this->db->insert_id();
		        }
		        elseif($r->id == $check_transaction_client_signing_info->result_array()[0]['director_signature_2'])
		        {
		        	$director_signature_2 = $this->db->insert_id();
		        }
		    }

		    //member
		    $check_client_member_share_capital = $this->db->get_where("client_member_share_capital", array("company_code" => $company_code));

			$this->db->select('company_code, class_id, other_class, currency_id, created_at');
	        $this->db->from('transaction_client_member_share_capital');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_client_member_share_capital = $this->db->get();

			$this->db->delete("client_member_share_capital",array('company_code'=>$company_code));

			foreach($check_transaction_client_member_share_capital->result() as $r) {
		        $this->db->insert("client_member_share_capital",$r);
		        $client_member_share_capital_id = $this->db->insert_id();
		    }

		    $check_client_member_shares = $this->db->get_where("member_shares", array("company_code" => $company_code));

			$this->db->select('company_code, transaction_date, client_member_share_capital_id, officer_id, field_type, transaction_id, transaction_type, number_of_share, amount_share, no_of_share_paid, amount_paid, consideration, merge, cert_status, created_at');
	        $this->db->from('transaction_member_shares');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_page_id', $transaction_master_id);
			$check_transaction_member_shares = $this->db->get();

			$this->db->delete("member_shares",array('company_code'=>$company_code));

			foreach($check_transaction_member_shares->result() as $r) {
				$r->transaction_date = $lodgement_date;
				$r->client_member_share_capital_id = $client_member_share_capital_id;
		        $this->db->insert("member_shares",$r);
		    }

		    $check_client_certificate = $this->db->get_where("certificate", array("company_code" => $company_code));

			$this->db->select('company_code, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, amount_share, no_of_share_paid, amount_paid, certificate_no, new_certificate_no, status, created_at');
	        $this->db->from('transaction_certificate');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_page_id', $transaction_master_id);
			$check_transaction_certificate = $this->db->get();

			$this->db->delete("certificate",array('company_code'=>$company_code));

			foreach($check_transaction_certificate->result() as $r) {
				$r->client_member_share_capital_id = $client_member_share_capital_id;
		        $this->db->insert("certificate",$r);
		    }

		    $check_client_certificate_merge = $this->db->get_where("certificate_merge", array("company_code" => $company_code));

			$this->db->select('company_code, merge_date, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, amount_share, no_of_share_paid, amount_paid, certificate_no, new_certificate_no, created_at');
	        $this->db->from('transaction_certificate_merge');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_page_id', $transaction_master_id);
			$check_transaction_certificate_merge = $this->db->get();

			$this->db->delete("certificate_merge",array('company_code'=>$company_code));

			foreach($check_transaction_certificate_merge->result() as $r) {
				$r->client_member_share_capital_id = $client_member_share_capital_id;
		        $this->db->insert("certificate_merge",$r);
		    }

		    //client_controller
		    $check_client_controller = $this->db->get_where("client_controller", array("company_code" => $company_code));

			$this->db->select('company_code, officer_id, field_type, date_of_birth, nationality_name, address, date_of_registration, date_of_notice, confirmation_received_date, date_of_entry, date_of_cessation');
	        $this->db->from('transaction_client_controller');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_client_controller = $this->db->get();

			$this->db->delete("client_controller",array('company_code'=>$company_code));

			foreach($check_transaction_client_controller->result() as $r) {
				$r->date_of_registration = $lodgement_date;
		        $this->db->insert("client_controller",$r);
		    }

		    //Filing
		    $this->db->select('company_code, transaction_id, year_end, financial_year_period');
	        $this->db->from('transaction_filing');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_filing = $this->db->get();

			$check_transaction_filing = $check_transaction_filing->result_array();

			$new_filing['company_code'] = $company_code;
			//$futureDate=date('d-m-Y', strtotime('+1 year', strtotime($_POST['year_end'])) );
			$new_filing['year_end'] = date('d F Y', strtotime($check_transaction_filing[0]['year_end']));
			$new_filing['ar_filing_date'] = "";
			$new_filing['financial_year_period_id'] = $check_transaction_filing[0]['financial_year_period'];
			$new_filing['175_extended_to'] = 0;
			$new_filing["201_extended_to"] = 0;
			//$new_filing["197_extended_to"] = 0;

			$latest_year_end = date('Y-m-d', strtotime($check_transaction_filing[0]['year_end']));

			$year_end_date = new DateTime($latest_year_end);

			if(date('Y-m-d', strtotime("8/31/2018")) > date('Y-m-d', strtotime($check_transaction_filing[0]['year_end']))) 
			{

				$array = explode('/',$lodgement_date);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				$date_2 = implode('/', $array);
				$time = strtotime($date_2);
				$newformat = date('m/d/Y',$time);

				$new_format_lodgement_date = new DateTime($newformat);
				// We extract the day of the month as $start_day
			    $new_due_date_175 = $this->MonthShifter($new_format_lodgement_date,15)->format(('Y-m-d'));
				$new_filing['due_date_175'] = date('d F Y', strtotime($new_due_date_175));

				// We extract the day of the month as $start_day
			    $date_201 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

				$new_filing['due_date_201'] =  date("t F Y", strtotime($date_201));

				// $date_197 = $this->MonthShifter($year_end_date,7)->format(('Y-m-d'));

				// $new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));

				$new_format_due_date_175 = new DateTime($new_filing['due_date_175']);
				$new_format_due_date_201 = new DateTime($new_filing['due_date_201']);
				if($new_format_due_date_175 >= $new_format_due_date_201)
				{
					$date_197 = $this->MonthShifter($new_format_due_date_201,1)->format(('Y-m-d'));

					$new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
				}
				else if($new_format_due_date_201 > $new_format_due_date_175)
				{
					$date_197 = $this->MonthShifter($new_format_due_date_175,1)->format(('Y-m-d'));

					$new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
				}
				
				//$new_filing['due_date_197'] = date('d F Y', strtotime('+30 days', strtotime($new_filing['due_date_175'])));
			}
			else
			{
				$new_filing['agm'] = "";

				$date_175 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

				$new_filing['due_date_175'] = date('d F Y', strtotime($date_175));

				$date_201 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

				$new_filing['due_date_201'] = date('d F Y', strtotime($date_201));

				$date_197 = $this->MonthShifter($year_end_date,7)->format(('Y-m-d'));

				$new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
			}
			

			$filing = $this->db->get_where("filing", array("company_code" => $company_code));

			if (!$filing->num_rows())
			{
				$this->db->insert("filing",$new_filing);
			} 
			else 
			{
				$this->db->update("filing",$new_filing,array("company_code" =>  $company_code));
			}
			


		    //billing
		    $check_client_billing_info = $this->db->get_where("client_billing_info", array("company_code" => $company_code));

			$this->db->select('client_billing_info_id, company_code, service, invoice_description, amount, currency, unit_pricing, created_at');
			$this->db->from('transaction_client_billing_info');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_client_billing_info = $this->db->get();

			$this->db->delete("client_billing_info",array('company_code'=>$company_code));

			foreach($check_transaction_client_billing_info->result() as $r) {
		        $this->db->insert("client_billing_info",$r);
		    }

		    //setup
		    $check_client_signing_info = $this->db->get_where("client_signing_info", array("company_code" => $company_code));

			$this->db->select('company_code, chairman, director_signature_1, director_signature_2, created_at');
	        $this->db->from('transaction_client_signing_info');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_client_signing_info = $this->db->get();

			if (!$check_client_signing_info->num_rows())
			{
				foreach($check_transaction_client_signing_info->result() as $r) {
					$r->director_signature_1 = $director_signature_1;
					if($director_signature_2 != null)
					{
						$r->director_signature_2 = $director_signature_2;
					}
					else
					{
						$r->director_signature_2 = 0;
					}
			        $this->db->insert("client_signing_info",$r);
			    }
			} 
			else 
			{
				foreach($check_transaction_client_signing_info->result() as $r) {
					$r->director_signature_1 = $director_signature_1;
					if($director_signature_2 != null)
					{
						$r->director_signature_2 = $director_signature_2;
					}
					else
					{
						$r->director_signature_2 = 0;
					}
			    	$this->db->update("client_signing_info",$r,array("company_code" =>  $company_code));
			    }
				
			}

			$check_client_contact_info = $this->db->get_where("client_contact_info", array("company_code" => $company_code));

			$this->db->select('id, company_code, name, created_at');
	        $this->db->from('transaction_client_contact_info');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_client_contact_info = $this->db->get();
			
			foreach($check_transaction_client_contact_info->result() as $g) {
				$f['company_code'] = $g->company_code;
				$f['name'] = $g->name;
				$f['created_at'] = $g->created_at;
			}
			
			//echo json_encode($latest_contact_info);
			if($check_transaction_client_contact_info->num_rows())
			{
				if (!$check_client_contact_info->num_rows())
				{
						
				        $this->db->insert("client_contact_info",$f);
				        $client_contact_info_id = $this->db->insert_id();
				    //
				} 
				else 
				{
					//foreach($check_transaction_client_contact_info->result() as $r) {
				    	$this->db->update("client_contact_info",$f,array("company_code" =>  $company_code));
				    	$client_contact_info_id = $check_client_contact_info->result_array()[0]["id"];
				    //}
					
				}
			}


			$check_client_contact_info_email = $this->db->get_where("client_contact_info_email", array("client_contact_info_id" => $client_contact_info_id));

			$this->db->select('client_contact_info_id, email, primary_email');
	        $this->db->from('transaction_client_contact_info_email');
	        $this->db->where('client_contact_info_id', $check_transaction_client_contact_info->result_array()[0]["id"]);
			$check_transaction_client_contact_info_email = $this->db->get();

			$this->db->delete("client_contact_info_email",array('client_contact_info_id'=>$client_contact_info_id));

			foreach($check_transaction_client_contact_info_email->result() as $r) {
				$r->client_contact_info_id = $client_contact_info_id;
		        $this->db->insert("client_contact_info_email",$r);
		    }
			  

			$check_client_contact_info_phone = $this->db->get_where("client_contact_info_phone", array("client_contact_info_id" => $client_contact_info_id));

			$this->db->select('client_contact_info_id, phone, primary_phone');
	        $this->db->from('transaction_client_contact_info_phone');
	        $this->db->where('client_contact_info_id', $check_transaction_client_contact_info->result_array()[0]["id"]);
			$check_transaction_client_contact_info_phone = $this->db->get();

			$this->db->delete("client_contact_info_phone",array('client_contact_info_id'=>$client_contact_info_id));

			foreach($check_transaction_client_contact_info_phone->result() as $r) {
				$r->client_contact_info_id = $client_contact_info_id;
		        $this->db->insert("client_contact_info_phone",$r);
		    }

		    $check_client_setup_reminder = $this->db->get_where("client_setup_reminder", array("company_code" => $company_code));

			$this->db->select('company_code, selected_reminder, created_at');
	        $this->db->from('transaction_client_setup_reminder');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_client_setup_reminder = $this->db->get();
			
			//echo json_encode($latest_contact_info);
			if (!$check_client_setup_reminder->num_rows())
			{
				foreach($check_transaction_client_setup_reminder->result() as $g) {
			        $this->db->insert("client_setup_reminder",$g);
			    }
			    //
			} 
			else 
			{
				foreach($check_transaction_client_setup_reminder->result() as $r) {
			    	$this->db->update("client_setup_reminder",$r,array("company_code" =>  $company_code));
			    }
				
			}
		}
		else if($transaction_task == 2)
		{
			//officer
			$check_client_officers = $this->db->get_where("client_officers", array("company_code" => $company_code));

			$this->db->select('id, company_code, position, alternate_of, officer_id, field_type, date_of_appointment, date_of_cessation, retiring, created_at');
	        $this->db->from('transaction_client_officers');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_client_officers = $this->db->get();

			//$this->db->delete("client_officers",array('company_code'=>$company_code));

			foreach($check_transaction_client_officers->result() as $r) {

				$r->date_of_appointment = $lodgement_date;
				$k['company_code'] = $r->company_code;
				$k['position'] = $r->position;
				$k['alternate_of'] = $r->alternate_of;
				$k['officer_id'] = $r->officer_id;
				$k['field_type'] = $r->field_type;
				$k['date_of_appointment'] = $r->date_of_appointment;
				$k['date_of_cessation'] = $r->date_of_cessation;
				$k['retiring'] = $r->retiring;
				$k['created_at'] = $r->created_at;

		        $this->db->insert("client_officers",$k);
		    }
		}
		else if($transaction_task == 3)
		{
			//officer
			$check_client_officers = $this->db->get_where("client_officers", array("company_code" => $company_code));

			$this->db->select('id, company_code, position, alternate_of, officer_id, field_type, date_of_appointment, date_of_cessation, retiring, created_at');
	        $this->db->from('transaction_client_officers');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_client_officers = $this->db->get();

			//$this->db->delete("client_officers",array('company_code'=>$company_code));

			foreach($check_transaction_client_officers->result() as $r) {

				//$r->date_of_appointment = $lodgement_date;
				$k['company_code'] = $r->company_code;
				$k['position'] = $r->position;
				$k['alternate_of'] = $r->alternate_of;
				$k['officer_id'] = $r->officer_id;
				$k['field_type'] = $r->field_type;
				$k['date_of_appointment'] = $r->date_of_appointment;
				$k['date_of_cessation'] = $r->date_of_cessation;
				$k['retiring'] = $r->retiring;
				$k['created_at'] = $r->created_at;

				if($r->date_of_appointment == "")
				{
					$this->db->insert("client_officers",$k);
				}
				else if($r->date_of_appointment != "")
				{
					$this->db->update("client_officers",$k,array("company_code" => $r->company_code, "officer_id" => $r->officer_id, "field_type" => $r->field_type, "date_of_cessation" => ''));
				}
		    }
		}
		else if($transaction_task == 4)
		{
			$this->db->select('transaction_change_regis_ofis_address.*');
	        $this->db->from('transaction_change_regis_ofis_address');
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_change_regis_ofis_address = $this->db->get();

			$check_transaction_change_regis_ofis_address = $check_transaction_change_regis_ofis_address->result_array();

			$data["registered_address"] = $check_transaction_change_regis_ofis_address[0]["registered_address"];
			$data["our_service_regis_address_id"] = $check_transaction_change_regis_ofis_address[0]["our_service_regis_address_id"];
			$data["postal_code"] = $check_transaction_change_regis_ofis_address[0]["postal_code"];
			$data["street_name"] = $check_transaction_change_regis_ofis_address[0]["street_name"];
			$data["building_name"] = $check_transaction_change_regis_ofis_address[0]["building_name"];
			$data["unit_no1"] = $check_transaction_change_regis_ofis_address[0]["unit_no1"];
			$data["unit_no2"] = $check_transaction_change_regis_ofis_address[0]["unit_no2"];

			$this->db->update("client", $data,array("company_code" => $company_code, "firm_id" => $this->session->userdata('firm_id'), "deleted" => 0));
		}
		else if($transaction_task == 5)
		{
			$this->db->select('transaction_change_biz_activity.*');
	        $this->db->from('transaction_change_biz_activity');
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_change_biz_activity = $this->db->get();

			$check_transaction_change_biz_activity = $check_transaction_change_biz_activity->result_array();

			$data["activity1"] = $check_transaction_change_biz_activity[0]["activity1"];
			$data["activity2"] = $check_transaction_change_biz_activity[0]["activity2"];

			$this->db->update("client", $data,array("company_code" => $company_code, "firm_id" => $this->session->userdata('firm_id'), "deleted" => 0));
		}
		else if($transaction_task == 6)
		{
			$this->db->select('transaction_change_fye.*');
	        $this->db->from('transaction_change_fye');
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_change_FYE = $this->db->get();

			$check_transaction_change_FYE = $check_transaction_change_FYE->result_array();

			//$data["year_end"] = $check_transaction_change_FYE[0]["new_year_end"];

			$new_filing['company_code'] = $company_code;
			//$futureDate=date('d-m-Y', strtotime('+1 year', strtotime($_POST['year_end'])) );
			$new_filing['year_end'] = date('d F Y', strtotime($check_transaction_change_FYE[0]['new_year_end']));
			$new_filing['ar_filing_date'] = "";
			$new_filing['financial_year_period_id'] = $check_transaction_change_FYE[0]['financial_year_period'];
			$new_filing['175_extended_to'] = 0;
			$new_filing["201_extended_to"] = 0;
			//$new_filing["197_extended_to"] = 0;

			$latest_year_end = date('Y-m-d', strtotime($check_transaction_change_FYE[0]['new_year_end']));

			$year_end_date = new DateTime($latest_year_end);

			if(date('Y-m-d', strtotime("8/31/2018")) > date('Y-m-d', strtotime($check_transaction_change_FYE[0]['new_year_end'])))
			{

				$array = explode('/',$lodgement_date);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				$date_2 = implode('/', $array);
				$time = strtotime($date_2);
				$newformat = date('m/d/Y',$time);

				$new_format_lodgement_date = new DateTime($newformat);
				// We extract the day of the month as $start_day
			    $new_due_date_175 = $this->MonthShifter($new_format_lodgement_date,15)->format(('Y-m-d'));
				$new_filing['due_date_175'] = date('d F Y', strtotime($new_due_date_175));

				// We extract the day of the month as $start_day
			    $date_201 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

				$new_filing['due_date_201'] =  date("t F Y", strtotime($date_201));

				// $date_197 = $this->MonthShifter($year_end_date,7)->format(('Y-m-d'));

				// $new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
				$new_format_due_date_175 = new DateTime($new_filing['due_date_175']);
				$new_format_due_date_201 = new DateTime($new_filing['due_date_201']);
				if($new_format_due_date_175 >= $new_format_due_date_201)
				{
					$date_197 = $this->MonthShifter($new_format_due_date_201,1)->format(('Y-m-d'));

					$new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
				}
				else if($new_format_due_date_201 > $new_format_due_date_175)
				{
					$date_197 = $this->MonthShifter($new_format_due_date_175,1)->format(('Y-m-d'));

					$new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
				}
				
				//$new_filing['due_date_197'] = date('d F Y', strtotime('+30 days', strtotime($new_filing['due_date_175'])));
			}
			else
			{
				$new_filing['agm'] = "";

				$date_175 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

				$new_filing['due_date_175'] = date('d F Y', strtotime($date_175));

				$date_201 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

				$new_filing['due_date_201'] = date('d F Y', strtotime($date_201));

				$date_197 = $this->MonthShifter($year_end_date,7)->format(('Y-m-d'));

				$new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
			}
			

			// $filing = $this->db->get_where("filing", array("company_code" => $company_code));

			// if (!$filing->num_rows())
			// {
			// 	$this->db->insert("filing",$new_filing);
			// } 
			// else 
			// {
			// 	$this->db->update("filing",$new_filing,array("company_code" =>  $company_code));
			// }

			$last_row_filing = $this->db->select('*')->where("company_code", $company_code)->order_by('id',"desc")->limit(1)->get('filing')->result_array();

			$this->db->update("filing", $new_filing,array("id" => $last_row_filing[0]["id"], "company_code" => $company_code));
		}
		else if($transaction_task == 7)
		{
			$check_client_officers = $this->db->get_where("client_officers", array("company_code" => $company_code));

			$this->db->select('id, company_code, position, alternate_of, officer_id, field_type, date_of_appointment, date_of_cessation, retiring, created_at');
	        $this->db->from('transaction_client_officers');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_client_officers = $this->db->get();

		    foreach($check_transaction_client_officers->result() as $r) {

				//$r->date_of_appointment = $lodgement_date;
				$k['company_code'] = $r->company_code;
				$k['position'] = $r->position;
				$k['alternate_of'] = $r->alternate_of;
				$k['officer_id'] = $r->officer_id;
				$k['field_type'] = $r->field_type;
				$k['date_of_cessation'] = $r->date_of_cessation;
				$k['retiring'] = $r->retiring;
				$k['created_at'] = $r->created_at;

				if($r->date_of_appointment == "")
				{
					$k['date_of_appointment'] = $lodgement_date;

					$this->db->insert("client_officers",$k);
				}
				else if($r->date_of_appointment != "")
				{
					$k['date_of_appointment'] = $r->date_of_appointment;

					$this->db->update("client_officers",$k,array("company_code" => $r->company_code, "officer_id" => $r->officer_id, "field_type" => $r->field_type, "date_of_cessation" => ''));
				}
		    }
		}
		else if($transaction_task == 8)
		{
			$this->db->select('transaction_issue_dividend.*');
	        $this->db->from('transaction_issue_dividend');
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_issue_dividend = $this->db->get();
			//echo json_encode($check_transaction_incorp_subsidiary->result_array());
		    foreach($check_transaction_issue_dividend->result() as $r) {

				//$r->date_of_appointment = $lodgement_date;
				$k['registration_no'] = $r->registration_no;
				$k['currency'] = $r->currency;
				$k['total_dividend_amount'] = $r->total_dividend_amount;
				$k['declare_of_fye'] = $r->declare_of_fye;
				$k['devidend_of_cut_off_date'] = $r->devidend_of_cut_off_date;
				$k['devidend_payment_date'] = $r->devidend_payment_date;
				$k['nature'] = $r->nature;
				$k['devidend_per_share'] = $r->devidend_per_share;
				$k['total_number_of_share'] = $r->total_number_of_share;
				$k['total_devidend_paid'] = $r->total_devidend_paid;

				$this->db->insert("issue_dividend",$k);
		    }
		    $transaction_issue_dividend = $check_transaction_issue_dividend->result_array();
		    $transaction_issue_dividend_id = $transaction_issue_dividend[0]['id'];

		    $this->db->select('transaction_dividend_list.*');
	        $this->db->from('transaction_dividend_list');
	        $this->db->where('transaction_issue_dividend_id', $transaction_issue_dividend_id);
			$check_transaction_dividend_list = $this->db->get();
			//echo json_encode($check_transaction_incorp_subsidiary->result_array());
		    foreach($check_transaction_dividend_list->result() as $r) {

				//$r->date_of_appointment = $lodgement_date;
				$b['issue_dividend_id'] = $r->transaction_issue_dividend_id;
				$b['payment_voucher_no'] = $r->payment_voucher_no;
				$b['officer_id'] = $r->officer_id;
				$b['field_type'] = $r->field_type;
				$b['shareholder_name'] = $r->shareholder_name;
				$b['number_of_share'] = $r->number_of_share;
				$b['devidend_paid'] = $r->devidend_paid;

				$this->db->insert("issue_dividend_list",$b);
		    }
		}
		else if($transaction_task == 9)
		{
			$this->db->select('transaction_issue_director_fee.*');
	        $this->db->from('transaction_issue_director_fee');
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_issue_director_fee = $this->db->get();
			//echo json_encode($check_transaction_incorp_subsidiary->result_array());
		    foreach($check_transaction_issue_director_fee->result() as $r) {

				//$r->date_of_appointment = $lodgement_date;
				$k['registration_no'] = $r->registration_no;
				$k['declare_of_fye'] = $r->declare_of_fye;
				$k['resolution_date'] = $r->resolution_date;
				$k['meeting_date'] = $r->meeting_date;
				$k['notice_date'] = $r->notice_date;

				$this->db->insert("issue_director_fee",$k);
		    }
		    $transaction_issue_director_fee = $check_transaction_issue_director_fee->result_array();
		    $transaction_issue_director_fee_id = $transaction_issue_director_fee[0]['id'];

		    $this->db->select('transaction_director_fee_list.*');
	        $this->db->from('transaction_director_fee_list');
	        $this->db->where('transaction_issue_director_fee_id', $transaction_issue_director_fee_id);
			$check_transaction_issue_director_fee_list = $this->db->get();
			//echo json_encode($check_transaction_incorp_subsidiary->result_array());
		    foreach($check_transaction_issue_director_fee_list->result() as $r) {

				//$r->date_of_appointment = $lodgement_date;
				$b['issue_director_fee_id'] = $r->transaction_issue_director_fee_id;
				$b['officer_id'] = $r->officer_id;
				$b['officer_field_type'] = $r->officer_field_type;
				$b['identification_register_no'] = $r->identification_register_no;
				$b['director_name'] = $r->director_name;
				$b['date_of_appointment'] = $r->date_of_appointment;
				$b['currency'] = $r->currency;
				$b['director_fee'] = $r->director_fee;

				$this->db->insert("issue_director_fee_list",$b);
		    }

		}
		else if($transaction_task == 10)
		{
			$this->db->select('transfer_from_id, transfer_to_id');
	        $this->db->from('transaction_transfer_member_id');
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_transfer_member_id = $this->db->get();

			foreach($check_transaction_transfer_member_id->result() as $r) {
				$this->db->insert("transfer_member_id",$r);
			}


			$this->db->select('id, company_code, transaction_date, client_member_share_capital_id, officer_id, field_type, transaction_id, transaction_type, number_of_share, amount_share, no_of_share_paid, amount_paid, consideration, merge, cert_status, created_at');
	        $this->db->from('transaction_member_shares');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_page_id', $transaction_master_id);
			$check_transaction_member_shares = $this->db->get();

			foreach($check_transaction_member_shares->result() as $r) {
				$r->transaction_date = $lodgement_date;
				// $r->client_member_share_capital_id = $client_member_share_capital_id;
				$k['company_code'] = $r->company_code;
				$k['transaction_date'] = $r->transaction_date;
				$k['client_member_share_capital_id'] = $r->client_member_share_capital_id;
				$k['officer_id'] = $r->officer_id;
				$k['field_type'] = $r->field_type;
				$k['transaction_id'] = $r->transaction_id;
				$k['transaction_type'] = $r->transaction_type;
				$k['number_of_share'] = $r->number_of_share;
				$k['amount_share'] = $r->amount_share;
				$k['no_of_share_paid'] = $r->no_of_share_paid;
				$k['amount_paid'] = $r->amount_paid;
				$k['consideration'] = $r->consideration;
				$k['merge'] = $r->merge;
				$k['cert_status'] = $r->cert_status;
				$k['created_at'] = $r->created_at;

		        $this->db->insert("member_shares",$k);
		        $new_member_share_id = $this->db->insert_id();

		        if(0 > $r->number_of_share)
		        {
		        	$y['transfer_from_id'] = $new_member_share_id;
		        	$this->db->update("transfer_member_id",$y,array("transfer_from_id" => $r->id));
		        }
		        else if($r->number_of_share > 0)
		        {
		        	$y['transfer_to_id'] = $new_member_share_id;

		        	$this->db->update("transfer_member_id",$y,array("transfer_to_id" => $r->id));
		        }

		        
		    }

			$this->db->select('company_code, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, amount_share, no_of_share_paid, amount_paid, certificate_no, new_certificate_no, status, created_at');
	        $this->db->from('transaction_certificate');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_page_id', $transaction_master_id);
			$check_transaction_certificate = $this->db->get();

			foreach($check_transaction_certificate->result() as $r) {
				//$r->client_member_share_capital_id = $client_member_share_capital_id;
		        $this->db->insert("certificate",$r);
		    }


			$this->db->select('company_code, merge_date, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, amount_share, no_of_share_paid, amount_paid, certificate_no, new_certificate_no, created_at');
	        $this->db->from('transaction_certificate_merge');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_page_id', $transaction_master_id);
			$check_transaction_certificate_merge = $this->db->get();

			foreach($check_transaction_certificate_merge->result() as $r) {
				//$r->client_member_share_capital_id = $client_member_share_capital_id;
		        $this->db->insert("certificate_merge",$r);
		    }
		}
		else if($transaction_task == 11)
		{
			if($transaction_status == 2)
			{
				//member
				$this->db->select('company_code, class_id, other_class, currency_id, created_at');
		        $this->db->from('transaction_client_member_share_capital');
		        $this->db->where('company_code', $company_code);
		        $this->db->where('transaction_id', $transaction_master_id);
				$check_transaction_client_member_share_capital = $this->db->get();

				$transaction_client_member_share_capital = $check_transaction_client_member_share_capital->result_array();

				$check_client_member_share_capital = $this->db->get_where("client_member_share_capital", array("company_code" => $transaction_client_member_share_capital[0]['company_code'], "class_id" => $transaction_client_member_share_capital[0]["class_id"], "other_class" => $transaction_client_member_share_capital[0]["other_class"], "currency_id" => $transaction_client_member_share_capital[0]["currency_id"]));

				//echo json_encode($check_client_member_share_capital->result_array());

				if (!$check_client_member_share_capital->num_rows())
				{
					foreach($check_transaction_client_member_share_capital->result() as $r) {
				        $this->db->insert("client_member_share_capital",$r);

				        $client_member_share_capital_id = $this->db->insert_id();
				    }
				}
				else
				{
					$check_client_member_share_capital = $check_client_member_share_capital->result_array();

					$client_member_share_capital_id = $check_client_member_share_capital[0]["id"];
				}

				$this->db->select('company_code, transaction_date, client_member_share_capital_id, officer_id, field_type, transaction_id, transaction_type, number_of_share, amount_share, no_of_share_paid, amount_paid, consideration, merge, cert_status, created_at');
		        $this->db->from('transaction_member_shares');
		        $this->db->where('company_code', $company_code);
		        $this->db->where('transaction_page_id', $transaction_master_id);
				$check_transaction_member_shares = $this->db->get();

				foreach($check_transaction_member_shares->result() as $r) {
					$r->transaction_date = $lodgement_date;
					$r->client_member_share_capital_id = $client_member_share_capital_id;
			        $this->db->insert("member_shares",$r);
			    }

				$this->db->select('company_code, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, amount_share, no_of_share_paid, amount_paid, certificate_no, new_certificate_no, status, created_at');
		        $this->db->from('transaction_certificate');
		        $this->db->where('company_code', $company_code);
		        $this->db->where('transaction_page_id', $transaction_master_id);
				$check_transaction_certificate = $this->db->get();

				foreach($check_transaction_certificate->result() as $r) {
					$r->client_member_share_capital_id = $client_member_share_capital_id;
			        $this->db->insert("certificate",$r);
			    }


				$this->db->select('company_code, merge_date, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, amount_share, no_of_share_paid, amount_paid, certificate_no, new_certificate_no, created_at');
		        $this->db->from('transaction_certificate_merge');
		        $this->db->where('company_code', $company_code);
		        $this->db->where('transaction_page_id', $transaction_master_id);
				$check_transaction_certificate_merge = $this->db->get();

				foreach($check_transaction_certificate_merge->result() as $r) {
					$r->client_member_share_capital_id = $client_member_share_capital_id;
			        $this->db->insert("certificate_merge",$r);
			    }
			}
		}
		else if($transaction_task == 12)
		{
			//transaction
			$this->db->select('transaction_change_company_name.*');
	        $this->db->from('transaction_change_company_name');
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_change_company_name = $this->db->get();

			$check_transaction_change_company_name = $check_transaction_change_company_name->result_array();

			$data["company_name"] = $check_transaction_change_company_name[0]["new_company_name"];


			//client
			$this->db->select('client.*');
	        $this->db->from('client');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('firm_id', $this->session->userdata('firm_id'));
	        $this->db->where('deleted = 0');
			$check_client_old_company_name = $this->db->get();

			$check_client_old_company_name = $check_client_old_company_name->result_array();

			//change_subsidiary_name
			$change_company_name['subsidiary_name'] = strtoupper($check_transaction_change_company_name[0]['new_company_name']);;

			$this->db->update("corporate_representative",$change_company_name,array("subsidiary_name" => $check_client_old_company_name[0]["company_name"]));

			$data['former_name'] = $check_client_old_company_name[0]["company_name"]."\r\n".$check_client_old_company_name[0]['former_name'];

			$this->db->update("client", $data,array("company_code" => $company_code, "firm_id" => $this->session->userdata('firm_id'), "deleted" => 0));
		}
		else if($transaction_task == 15)
		{
			$this->db->select('transaction_agm_ar.*, transaction_master.company_code');
	        $this->db->from('transaction_agm_ar');
	        $this->db->join('transaction_master', 'transaction_master.id = transaction_agm_ar.transaction_id', 'left');
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_agm_ar = $this->db->get();
			$check_transaction_agm_ar_array = $check_transaction_agm_ar->result_array();

			foreach($check_transaction_agm_ar->result() as $r) {

				$k['transaction_id'] = $r->transaction_id;
				$k['is_first_agm_id'] = $r->is_first_agm_id;
				$k['year_end_date'] = $r->year_end_date;
				$k['agm_date'] = $r->agm_date;
				$k['reso_date'] = $r->reso_date;
				$k['activity_status'] = $r->activity_status;
				$k['solvency_status'] = $r->solvency_status;
				$k['epc_status_id'] = $r->epc_status_id;
				$k['small_company'] = $r->small_company;
				$k['audited_fs'] = $r->audited_fs;
				$k['agm_share_transfer_id'] = $r->agm_share_transfer_id;
				$k['shorter_notice'] = $r->shorter_notice;
				$k['chairman'] = $r->chairman;

		        $this->db->insert("agm_ar",$k);
		        $agm_ar_id = $this->db->insert_id();
		    }

		    $this->db->select('filing.*');
	        $this->db->from('filing');
	        $this->db->where('filing.company_code', $check_transaction_agm_ar_array[0]['company_code']);
	        $this->db->where('filing.year_end', $check_transaction_agm_ar_array[0]['year_end_date']);
			$check_filing = $this->db->get();
			$check_filing_array = $check_filing->result_array();

			//echo json_encode($check_filing_array);
			//dsdjhsajhkdsjhksadjkhsdjkhdsahjk
			$array = explode('/',$lodgement_date);
			$tmp = $array[0];
			$array[0] = $array[1];
			$array[1] = $tmp;
			unset($tmp);
			$date_2 = implode('/', $array);
			$time = strtotime($date_2);
			$newformat_logde_date = date('d F Y',$time);

			$new_filing_info['agm'] = $check_transaction_agm_ar_array[0]['agm_date'];
			$new_filing_info['ar_filing_date'] = $newformat_logde_date;

			$this->db->where(array("id" => $check_filing_array[0]['id']));
			//$this->db->where(array("company_code" => $check_filing_array[0]['company_code']));
			$this->db->update("filing",$new_filing_info);

		    if($check_transaction_agm_ar_array[0]['agm_date'] != null && $check_transaction_agm_ar_array[0]['agm_date'] != "dispensed")
			{
				$new_filing['company_code'] = $check_transaction_agm_ar_array[0]['company_code'];
				$new_filing['year_end'] = date('d F Y', strtotime('+1 year', strtotime($check_transaction_agm_ar_array[0]['year_end_date'])));
				$new_filing['ar_filing_date'] = "";
				$new_filing['financial_year_period_id'] = 1;
				$new_filing['175_extended_to'] = 0;
				$new_filing["201_extended_to"] = 0;
				//$new_filing["197_extended_to"] = 0;

				$latest_year_end = date('Y-m-d', strtotime($new_filing['year_end']));

				$year_end_date = new DateTime($latest_year_end);
				if(date('Y-m-d', strtotime("8/31/2018")) > date('Y-m-d', strtotime('+1 year', strtotime($check_transaction_agm_ar_array[0]['year_end_date'])))) 
				{
					$two_digit_year_previous_agm = date('y', strtotime($check_transaction_agm_ar_array[0]['agm_date']));
					$two_digit_year_latest_agm = date('y', strtotime('+15 month', strtotime($check_transaction_agm_ar_array[0]['agm_date'])));
					$new_filing['agm'] = "";

					$latest_agm = date('Y-m-d', strtotime($check_transaction_agm_ar_array[0]['agm_date']));

					$agm_date = new DateTime($latest_agm);
					// We extract the day of the month as $start_day
				    $agm_date = $this->MonthShifter($agm_date,15)->format(('Y-m-d'));
					$new_filing['due_date_175'] = date('d F Y', strtotime($agm_date));

					$new_format_due_date_175 = new DateTime($new_filing['due_date_175']);
					
					// We extract the day of the month as $start_day
				    $date_201 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

					$new_filing['due_date_201'] =  date("t F Y", strtotime($date_201));

					$new_format_due_date_201 = new DateTime($new_filing['due_date_201']);

					if($new_format_due_date_175 >= $new_format_due_date_201)
					{
						$date_197 = $this->MonthShifter($new_format_due_date_201,1)->format(('Y-m-d'));

						$new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
					}
					else if($new_format_due_date_201 > $new_format_due_date_175)
					{
						$date_197 = $this->MonthShifter($new_format_due_date_175,1)->format(('Y-m-d'));

						$new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
					}
				}
				else
				{
					$new_filing['agm'] = "";

					$date_175 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

					$new_filing['due_date_175'] = date('d F Y', strtotime($date_175));

					$date_201 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

					$new_filing['due_date_201'] = date('d F Y', strtotime($date_201));

					$date_197 = $this->MonthShifter($year_end_date,7)->format(('Y-m-d'));

					$new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
				}
				
				// if($latest_id->result()[0]->id != $_POST['filing_id'] && $_POST['filing_id'] != "")
				// {
				// 	$this->db->where(array("id" => $latest_id->result()[0]->id));
				// 	$this->db->update("filing",$new_filing);
				// }
				// else
				// {
					$this->db->insert("filing",$new_filing);
				//}
			}
			elseif($check_transaction_agm_ar_array[0]['agm_date'] != null && $check_transaction_agm_ar_array[0]['agm_date'] == "dispensed")
			{
				$new_filing['company_code'] = $check_transaction_agm_ar_array[0]['company_code'];
				$new_filing['year_end'] = date('d F Y', strtotime('+1 year', strtotime($check_transaction_agm_ar_array[0]['year_end_date'])));
				$new_filing['ar_filing_date'] = "";
				$new_filing['financial_year_period_id'] = 1;
				$new_filing['175_extended_to'] = 0;
				$new_filing["201_extended_to"] = 0;
				$new_filing['due_date_175'] = "Not Applicable";

				$latest_year_end = date('Y-m-d', strtotime($new_filing['year_end']));

				$year_end_date = new DateTime($latest_year_end);

				if(date('Y-m-d', strtotime("8/31/2018")) > date('Y-m-d', strtotime('+1 year', strtotime($check_transaction_agm_ar_array[0]['year_end_date'])))) 
				{
					$new_filing['agm'] = "";

					$latest_due_date_201 = date('Y-m-d', strtotime($new_filing['year_end']));

					$date1 = new DateTime($latest_due_date_201);
					// We extract the day of the month as $start_day
				    $date1 = $this->MonthShifter($date1,6)->format(('Y-m-d'));

					$new_filing['due_date_201'] =  date("t F Y", strtotime($date1));

					if($new_filing['due_date_175'] == "Not Applicable")
					{
						$new_filing['due_date_197'] = "Not Applicable";
					}
				}
				else
				{
					$new_filing['agm'] = "";

					$date_175 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

					$new_filing['due_date_175'] = date('d F Y', strtotime($date_175));

					$date_201 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

					$new_filing['due_date_201'] = date('d F Y', strtotime($date_201));

					$date_197 = $this->MonthShifter($year_end_date,7)->format(('Y-m-d'));

					$new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
				}

				// if($latest_id->result()[0]->id != $_POST['filing_id'] && $_POST['filing_id'] != "")
				// {
				// 	$this->db->where(array("id" => $latest_id->result()[0]->id));
				// 	$this->db->update("filing",$new_filing);
				// }
				// else
				// {
					$this->db->insert("filing",$new_filing);
				//}
			}

		    $this->db->select('*');
	        $this->db->from('transaction_agm_ar_director_fee');
	        $this->db->where('transaction_agm_ar_id', $agm_ar_id);
			$check_transaction_agm_ar_director_fee = $this->db->get();

			foreach($check_transaction_agm_ar_director_fee->result() as $r) {
		        $this->db->insert("agm_ar_director_fee",$r);
		    }

		    $this->db->select('*');
	        $this->db->from('transaction_agm_ar_dividend');
	        $this->db->where('transaction_agm_ar_id', $agm_ar_id);
			$check_transaction_agm_ar_dividend = $this->db->get();

			foreach($check_transaction_agm_ar_dividend->result() as $r) {
		        $this->db->insert("agm_ar_dividend",$r);
		    }

		    $this->db->select('*');
	        $this->db->from('transaction_agm_ar_total_dividend');
	        $this->db->where('transaction_agm_ar_id', $agm_ar_id);
			$check_transaction_agm_ar_total_dividend = $this->db->get();

			foreach($check_transaction_agm_ar_total_dividend->result() as $r) {
		        $this->db->insert("agm_ar_total_dividend",$r);
		    }

		    $this->db->select('*');
	        $this->db->from('transaction_agm_ar_amount_due');
	        $this->db->where('transaction_agm_ar_id', $agm_ar_id);
			$check_transaction_agm_ar_amount_due = $this->db->get();

			foreach($check_transaction_agm_ar_amount_due->result() as $r) {
		        $this->db->insert("agm_ar_amount_due",$r);
		    }

		    $this->db->select('*');
	        $this->db->from('transaction_agm_ar_director_retire');
	        $this->db->where('transaction_agm_ar_id', $agm_ar_id);
			$check_transaction_agm_ar_director_retire = $this->db->get();

			foreach($check_transaction_agm_ar_director_retire->result() as $r) {
		        $this->db->insert("agm_ar_director_retire",$r);
		    }

		    $this->db->select('transaction_agm_ar_director_retire.director_retire_officer_id, transaction_agm_ar_director_retire.director_retire_field_type, transaction_master.company_code, transaction_agm_ar_director_retire.director_retiring_checkbox');
	        $this->db->from('transaction_agm_ar_director_retire');
	        $this->db->join('transaction_agm_ar', 'transaction_agm_ar.id = transaction_agm_ar_director_retire.transaction_agm_ar_id', 'left');
	        $this->db->join('transaction_master', 'transaction_master.id = transaction_agm_ar.transaction_id', 'left');
	        $this->db->where('transaction_agm_ar_director_retire.transaction_agm_ar_id', $agm_ar_id);
			$transaction_agm_ar_director_retire = $this->db->get();

			foreach($transaction_agm_ar_director_retire->result() as $r) {
				$retire["retiring"] = $r->director_retiring_checkbox;
		        $this->db->update("client_officers", $retire,array("company_code" => $r->company_code, "officer_id" => $r->director_retire_officer_id, "field_type" => $r->director_retire_field_type, "date_of_cessation != ''"));
		    }


		    $this->db->select('*');
	        $this->db->from('transaction_agm_ar_reappoint_auditor');
	        $this->db->where('transaction_agm_ar_id', $agm_ar_id);
			$check_transaction_agm_ar_reappoint_auditor = $this->db->get();

			foreach($check_transaction_agm_ar_reappoint_auditor->result() as $r) {
		        $this->db->insert("agm_ar_reappoint_auditor",$r);
		    }
		}
		else if($transaction_task == 20)
		{
			$this->db->select('transaction_corporate_representative.*');
	        $this->db->from('transaction_corporate_representative');
	        //$this->db->where('registration_no', $registration_no);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_incorp_subsidiary = $this->db->get();
			//echo json_encode($check_transaction_incorp_subsidiary->result_array());
		    foreach($check_transaction_incorp_subsidiary->result() as $r) {

				//$r->date_of_appointment = $lodgement_date;
				$k['registration_no'] = $r->registration_no;
				$k['subsidiary_name'] = $r->subsidiary_name;
				$k['name_of_corp_rep'] = $r->name_of_corp_rep;
				$k['identity_number'] = $r->identity_number;
				$k['effective_date'] = $lodgement_date;


				$this->db->insert("corporate_representative",$k);
				// if($r->date_of_appointment == "")
				// {
				// 	$k['date_of_appointment'] = $lodgement_date;

				// 	$this->db->insert("client_officers",$k);
				// }
				// else if($r->date_of_appointment != "")
				// {
				// 	$k['date_of_appointment'] = $r->date_of_appointment;

				// 	$this->db->update("client_officers",$k,array("company_code" => $r->company_code, "officer_id" => $r->officer_id, "field_type" => $r->field_type, "date_of_cessation" => ''));
				// }
		    }
		}
		else if($transaction_task == 24)
		{
			//officer
			$check_client_officers = $this->db->get_where("client_officers", array("company_code" => $company_code));

			$this->db->select('id, company_code, position, alternate_of, officer_id, field_type, date_of_appointment, date_of_cessation, retiring, created_at');
	        $this->db->from('transaction_client_officers');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_client_officers = $this->db->get();

			//$this->db->delete("client_officers",array('company_code'=>$company_code));
			

		    // if($check_valid_officer == true)
		    // {
		    	foreach($check_transaction_client_officers->result() as $r) {

					$r->date_of_appointment = $lodgement_date;
					$k['company_code'] = $r->company_code;
					$k['position'] = $r->position;
					$k['alternate_of'] = $r->alternate_of;
					$k['officer_id'] = $r->officer_id;
					$k['field_type'] = $r->field_type;
					$k['date_of_appointment'] = $r->date_of_appointment;
					$k['date_of_cessation'] = $r->date_of_cessation;
					$k['retiring'] = $r->retiring;
					$k['created_at'] = $r->created_at;

					$this->db->insert("client_officers",$k);
			    }
		    //}
		}
		else if($transaction_task == 26)
		{
			$this->db->select('transaction_strike_off.*');
	        $this->db->from('transaction_strike_off');
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_strike_off = $this->db->get();

		    foreach($check_transaction_strike_off->result() as $r) {
				$k['registration_no'] = $r->registration_no;
				$k['reason_for_application_id'] = $r->reason_for_application_id;
				$k['ceased_date'] = $r->ceased_date;

				$this->db->insert("strike_off",$k);
		    }

		    $status_company['transaction_id'] = $transaction_master_id;
		    $status_company['status_of_the_company_id'] = $_POST['status_of_the_company'];

		    $transaction_status_of_the_company = $this->db->get_where("transaction_status_of_the_company", array("transaction_id" => $transaction_master_id));

			if (!$transaction_status_of_the_company->num_rows())
			{
				$this->db->insert("transaction_status_of_the_company",$status_company);
			} 
			else 
			{
				$this->db->update("transaction_status_of_the_company",$status_company,array("transaction_id" =>  $transaction_master_id));
			}

		    $check_transaction_strike_off_array = $check_transaction_strike_off->result_array();

		    if($_POST['status_of_the_company'] == 2)
		    {
		    	$client['status'] = 2;
		    	$client['acquried_by'] = 2;

		    	$this->db->update("client", $client, array("registration_no" => $check_transaction_strike_off_array[0]["registration_no"], "deleted = " => 0));
			}
			else if($_POST['status_of_the_company'] == 4)
		    {
		    	$client['status'] = 4;
		    	$client['acquried_by'] = 2;

		    	$this->db->update("client", $client, array("registration_no" => $check_transaction_strike_off_array[0]["registration_no"], "deleted = " => 0));
			}

			
		}
		else if($transaction_task == 28)
		{
			//client
			$check_client = $this->db->get_where("client", array("company_code" => $company_code, "firm_id" => $this->session->userdata('firm_id'), "deleted" => 0));

			$this->db->select('firm_id, acquried_by, company_code, client_code, registration_no, company_name, former_name, incorporation_date, company_type, status, activity1, activity2, registered_address, our_service_regis_address_id, postal_code, street_name, building_name, unit_no1, unit_no2, auto_generate, deleted, created_by, created_at');
	        $this->db->from('transaction_client');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_client = $this->db->get();

			if (!$check_client->num_rows())
			{	//echo json_encode(value)
				foreach($check_transaction_client->result() as $r) {
					$r->registration_no = $registration_no;
					$r->incorporation_date = $lodgement_date;
			        $this->db->insert("client",$r);
			    }
			} 
			else 
			{
				foreach($check_transaction_client->result() as $r) {
					$r->registration_no = $registration_no;
					$r->incorporation_date = $lodgement_date;
			    	$this->db->update("client",$r,array("company_code" =>  $company_code, "firm_id" => $this->session->userdata('firm_id'), "deleted" => 0));
			    }
				
			}
			//officer
			$check_client_officers = $this->db->get_where("client_officers", array("company_code" => $company_code));

			$this->db->select('id, company_code, position, alternate_of, officer_id, field_type, date_of_appointment, date_of_cessation, retiring, created_at');
	        $this->db->from('transaction_client_officers');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_client_officers = $this->db->get();

			$this->db->select('company_code, chairman, director_signature_1, director_signature_2, created_at');
	        $this->db->from('transaction_client_signing_info');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_client_signing_info = $this->db->get();

			$this->db->delete("client_officers",array('company_code'=>$company_code));

			foreach($check_transaction_client_officers->result() as $r) {

				$r->date_of_appointment = $lodgement_date;
				$k['company_code'] = $r->company_code;
				$k['position'] = $r->position;
				$k['alternate_of'] = $r->alternate_of;
				$k['officer_id'] = $r->officer_id;
				$k['field_type'] = $r->field_type;
				$k['date_of_appointment'] = $r->date_of_appointment;
				$k['date_of_cessation'] = $r->date_of_cessation;
				$k['retiring'] = $r->retiring;
				$k['created_at'] = $r->created_at;

		        $this->db->insert("client_officers",$k);
		        //echo json_encode($check_transaction_client_signing_info->result_array()[0]['director_signature_1']);
		        if($r->id == $check_transaction_client_signing_info->result_array()[0]['director_signature_1'])
		        {
		        	$director_signature_1 = $this->db->insert_id();
		        }
		        elseif($r->id == $check_transaction_client_signing_info->result_array()[0]['director_signature_2'])
		        {
		        	$director_signature_2 = $this->db->insert_id();
		        }
		    }

		    //Filing
		    $this->db->select('company_code, transaction_id, year_end, financial_year_period');
	        $this->db->from('transaction_filing');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_filing = $this->db->get();

			$check_transaction_filing = $check_transaction_filing->result_array();

			$new_filing['company_code'] = $company_code;
			//$futureDate=date('d-m-Y', strtotime('+1 year', strtotime($_POST['year_end'])) );
			$new_filing['year_end'] = date('d F Y', strtotime($check_transaction_filing[0]['year_end']));
			$new_filing['ar_filing_date'] = "";
			$new_filing['financial_year_period_id'] = $check_transaction_filing[0]['financial_year_period'];
			$new_filing['175_extended_to'] = 0;
			$new_filing["201_extended_to"] = 0;
			//$new_filing["197_extended_to"] = 0;

			$latest_year_end = date('Y-m-d', strtotime($check_transaction_filing[0]['year_end']));

			$year_end_date = new DateTime($latest_year_end);

			if(date('Y-m-d', strtotime("8/31/2018")) > date('Y-m-d', strtotime($check_transaction_filing[0]['year_end']))) 
			{

				$array = explode('/',$lodgement_date);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				$date_2 = implode('/', $array);
				$time = strtotime($date_2);
				$newformat = date('m/d/Y',$time);

				$new_format_lodgement_date = new DateTime($newformat);
				// We extract the day of the month as $start_day
			    $new_due_date_175 = $this->MonthShifter($new_format_lodgement_date,15)->format(('Y-m-d'));
				$new_filing['due_date_175'] = date('d F Y', strtotime($new_due_date_175));

				// We extract the day of the month as $start_day
			    $date_201 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

				$new_filing['due_date_201'] =  date("t F Y", strtotime($date_201));

				// $date_197 = $this->MonthShifter($year_end_date,7)->format(('Y-m-d'));

				// $new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));

				$new_format_due_date_175 = new DateTime($new_filing['due_date_175']);
				$new_format_due_date_201 = new DateTime($new_filing['due_date_201']);
				if($new_format_due_date_175 >= $new_format_due_date_201)
				{
					$date_197 = $this->MonthShifter($new_format_due_date_201,1)->format(('Y-m-d'));

					$new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
				}
				else if($new_format_due_date_201 > $new_format_due_date_175)
				{
					$date_197 = $this->MonthShifter($new_format_due_date_175,1)->format(('Y-m-d'));

					$new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
				}
				
				//$new_filing['due_date_197'] = date('d F Y', strtotime('+30 days', strtotime($new_filing['due_date_175'])));
			}
			else
			{
				$new_filing['agm'] = "";

				$date_175 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

				$new_filing['due_date_175'] = date('d F Y', strtotime($date_175));

				$date_201 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

				$new_filing['due_date_201'] = date('d F Y', strtotime($date_201));

				$date_197 = $this->MonthShifter($year_end_date,7)->format(('Y-m-d'));

				$new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
			}
			

			$filing = $this->db->get_where("filing", array("company_code" => $company_code));

			if (!$filing->num_rows())
			{
				$this->db->insert("filing",$new_filing);
			} 
			else 
			{
				$this->db->update("filing",$new_filing,array("company_code" =>  $company_code));
			}
			
		    //billing
		    $check_client_billing_info = $this->db->get_where("client_billing_info", array("company_code" => $company_code));

			$this->db->select('client_billing_info_id, company_code, service, invoice_description, amount, currency, unit_pricing, created_at');
			$this->db->from('transaction_client_billing_info');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_client_billing_info = $this->db->get();

			$this->db->delete("client_billing_info",array('company_code'=>$company_code));

			foreach($check_transaction_client_billing_info->result() as $r) {
		        $this->db->insert("client_billing_info",$r);
		    }

		    //billing
		    $check_previous_secretarial_info = $this->db->get_where("previous_secretarial_info", array("company_code" => $company_code));

			$this->db->select('company_code, company_name, postal_code, street_name, building_name, unit_no1, unit_no2');
			$this->db->from('transaction_previous_secretarial');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_previous_secretarial_info= $this->db->get();

			$this->db->delete("previous_secretarial_info",array('company_code'=>$company_code));

			foreach($check_transaction_previous_secretarial_info->result() as $r) {
		        $this->db->insert("previous_secretarial_info",$r);
		    }
		}
		else if($transaction_task == 29)
		{
			if($transaction_status == 3)
		 	{
		 		$this->db->select('transaction_service_proposal_info.*, transaction_master.*, transaction_service_proposal_service_info.*, our_service_info.invoice_description, our_service_info.el_required_id');
		        $this->db->from('transaction_service_proposal_info');
		        $this->db->join('transaction_master', 'transaction_service_proposal_info.transaction_id = transaction_master.id', 'left');
		        $this->db->join('transaction_service_proposal_service_info', 'transaction_service_proposal_info.transaction_id = transaction_service_proposal_service_info.transaction_id', 'left');
		        $this->db->join('our_service_info', 'our_service_info.id = transaction_service_proposal_service_info.our_service_id', 'left');
		        // $this->db->join('transaction_client_contact_info', 'transaction_client_contact_info.transaction_id = transaction_service_proposal_info.transaction_id', 'left');
		        // $this->db->join('transaction_client_contact_info_email', 'transaction_client_contact_info.id = transaction_client_contact_info_email.client_contact_info_id', 'left');
		        // $this->db->join('transaction_client_contact_info_phone', 'transaction_client_contact_info.id = transaction_client_contact_info_phone.client_contact_info_id', 'left');
		        $this->db->where('transaction_service_proposal_info.transaction_id', $transaction_master_id);
				$check_is_potential_client = $this->db->get();

				if ($check_is_potential_client->num_rows())
				{	//echo json_encode(value)
					$check_is_potential_client_array = $check_is_potential_client->result_array();

					$gotEngagement = false;

					for($i = 0; $i < count($check_is_potential_client_array); $i++ )
					{
						if($check_is_potential_client_array[$i]['el_required_id'] == '1')
						{
							$gotEngagement = true;
							break;
						}
					}

					if(!$gotEngagement && $check_is_potential_client_array[0]['potential_client'] == '1')
					{
						$new_client["firm_id"] = $this->session->userdata('firm_id');
						$new_client["acquried_by"] = 1;
						$new_client["company_type"] = 1;
						$new_client["status"] = 1;
						$new_client["company_code"] = $check_is_potential_client_array[0]['company_code'];
						$new_client["registration_no"] = strtoupper($check_is_potential_client_array[0]['uen']);
						$new_client["company_name"] = $check_is_potential_client_array[0]['client_name'];
						$new_client["activity1"] = $check_is_potential_client_array[0]['activity1'];
						$new_client["activity2"] = $check_is_potential_client_array[0]['activity2'];
						$new_client["postal_code"] = $check_is_potential_client_array[0]['postal_code'];
						$new_client["street_name"] = $check_is_potential_client_array[0]['street_name'];
						$new_client["building_name"] = $check_is_potential_client_array[0]['building_name'];
						$new_client["unit_no1"] = $check_is_potential_client_array[0]['unit_no1'];
						$new_client["unit_no2"] = $check_is_potential_client_array[0]['unit_no2'];
						$new_client["created_by"] = $this->session->userdata('user_id');

						$this->db->insert("client",$new_client);

						for($i = 0; $i < count($check_is_potential_client_array); $i++ )
						{
							$client_billing_info['company_code'] = $check_is_potential_client_array[$i]['company_code'];
							$client_billing_info['client_billing_info_id'] = $i + 1;
							$client_billing_info['service'] = $check_is_potential_client_array[$i]['our_service_id'];
							$client_billing_info['invoice_description'] = $check_is_potential_client_array[$i]['invoice_description'];
							//(int)str_replace(',', '', $amount[$p]);
							$client_billing_info['amount'] = (float)str_replace(',', '', $check_is_potential_client_array[$i]['fee']);
							$client_billing_info['currency'] = $check_is_potential_client_array[$i]['currency_id'];
							$client_billing_info['unit_pricing'] = $check_is_potential_client_array[$i]['unit_pricing'];

							$this->db->insert("client_billing_info",$client_billing_info);
						}

						$this->db->select('id, company_code, name, created_at');
				        $this->db->from('transaction_client_contact_info');
				        $this->db->where('company_code', $check_is_potential_client_array[0]['company_code']);
				        $this->db->where('transaction_id', $check_is_potential_client_array[0]['transaction_id']);
						$check_transaction_client_contact_info = $this->db->get();
						
						foreach($check_transaction_client_contact_info->result() as $g) {
							$f['company_code'] = $g->company_code;
							$f['name'] = $g->name;
							$f['created_at'] = $g->created_at;
						}
						
						//echo json_encode($latest_contact_info);
						if($check_transaction_client_contact_info->num_rows())
						{
							$this->db->insert("client_contact_info",$f);
							$client_contact_info_id = $this->db->insert_id();
						}

						$this->db->select('client_contact_info_id, email, primary_email');
				        $this->db->from('transaction_client_contact_info_email');
				        $this->db->where('client_contact_info_id', $check_transaction_client_contact_info->result_array()[0]["id"]);
						$check_transaction_client_contact_info_email = $this->db->get();

						foreach($check_transaction_client_contact_info_email->result() as $r) {
							$r->client_contact_info_id = $client_contact_info_id;
					        $this->db->insert("client_contact_info_email",$r);
					    }
						 

						$this->db->select('client_contact_info_id, phone, primary_phone');
				        $this->db->from('transaction_client_contact_info_phone');
				        $this->db->where('client_contact_info_id', $check_transaction_client_contact_info->result_array()[0]["id"]);
						$check_transaction_client_contact_info_phone = $this->db->get();

						foreach($check_transaction_client_contact_info_phone->result() as $r) {
							$r->client_contact_info_id = $client_contact_info_id;
					        $this->db->insert("client_contact_info_phone",$r);
					    }
					}
					
				}
		 	}
		}
		else if($transaction_task == 30)
		{	//echo json_encode($transaction_master_id);
			if($transaction_status == 2)
		 	{
				$this->db->select('transaction_engagement_letter_info.*, transaction_master.*, transaction_service_proposal_info.*, transaction_engagement_letter_additional_info.*, transaction_service_proposal_service_info.*, our_service_info.invoice_description');
		        $this->db->from('transaction_engagement_letter_info');
		        $this->db->join('transaction_master', 'transaction_engagement_letter_info.transaction_id = transaction_master.id', 'left');
		        $this->db->join('transaction_service_proposal_info', 'transaction_engagement_letter_info.transaction_master_id = transaction_service_proposal_info.transaction_id', 'left');
		        $this->db->join('transaction_service_proposal_service_info', 'transaction_engagement_letter_info.transaction_master_id = transaction_service_proposal_service_info.transaction_id', 'left');
		        $this->db->join('transaction_engagement_letter_additional_info', 'transaction_engagement_letter_info.transaction_id = transaction_engagement_letter_additional_info.transaction_id', 'left');
		        $this->db->join('our_service_info', 'our_service_info.id = transaction_service_proposal_service_info.our_service_id', 'left');
		        $this->db->where('transaction_engagement_letter_info.transaction_id', $transaction_master_id);
		        $this->db->where('transaction_engagement_letter_info.deleted', 0);
				$check_is_potential_client = $this->db->get();

				echo json_encode($check_is_potential_client->result_array());
				if ($check_is_potential_client->num_rows())
				{	//echo json_encode(value)
					$check_is_potential_client_array = $check_is_potential_client->result_array();

					if($check_is_potential_client_array[0]['potential_client'] == '1')
					{
						//echo json_encode($check_is_potential_client->result_array());
						$new_client["firm_id"] = $this->session->userdata('firm_id');
						$new_client["acquried_by"] = 1;
						$new_client["company_type"] = 1;
						$new_client["status"] = 1;
						$new_client["company_code"] = $check_is_potential_client_array[0]['company_code'];
						$new_client["registration_no"] = strtoupper($check_is_potential_client_array[0]['uen']);
						$new_client["company_name"] = $check_is_potential_client_array[0]['client_name'];
						$new_client["activity1"] = $check_is_potential_client_array[0]['activity1'];
						$new_client["activity2"] = $check_is_potential_client_array[0]['activity2'];
						$new_client["postal_code"] = $check_is_potential_client_array[0]['postal_code'];
						$new_client["street_name"] = $check_is_potential_client_array[0]['street_name'];
						$new_client["building_name"] = $check_is_potential_client_array[0]['building_name'];
						$new_client["unit_no1"] = $check_is_potential_client_array[0]['unit_no1'];
						$new_client["unit_no2"] = $check_is_potential_client_array[0]['unit_no2'];
						$new_client["created_by"] = $this->session->userdata('user_id');

						$this->db->insert("client",$new_client);

						for($i = 0; $i < count($check_is_potential_client_array); $i++ )
						{
							$client_billing_info['company_code'] = $check_is_potential_client_array[$i]['company_code'];
							$client_billing_info['client_billing_info_id'] = $i + 1;
							$client_billing_info['service'] = $check_is_potential_client_array[$i]['our_service_id'];
							$client_billing_info['invoice_description'] = $check_is_potential_client_array[$i]['invoice_description'];
							//(int)str_replace(',', '', $amount[$p]);
							$client_billing_info['amount'] = (float)str_replace(',', '', $check_is_potential_client_array[$i]['fee']);
							$client_billing_info['currency'] = $check_is_potential_client_array[$i]['currency_id'];
							$client_billing_info['unit_pricing'] = $check_is_potential_client_array[$i]['unit_pricing'];

							$this->db->insert("client_billing_info",$client_billing_info);
						}

						$this->db->select('id, company_code, name, created_at');
				        $this->db->from('transaction_client_contact_info');
				        $this->db->where('company_code', $check_is_potential_client_array[0]['company_code']);
				        $this->db->where('transaction_id', $check_is_potential_client_array[0]['transaction_master_id']);
						$check_transaction_client_contact_info = $this->db->get();
						
						foreach($check_transaction_client_contact_info->result() as $g) {
							$f['company_code'] = $g->company_code;
							$f['name'] = $g->name;
							$f['created_at'] = $g->created_at;
						}
						
						//echo json_encode($latest_contact_info);
						if($check_transaction_client_contact_info->num_rows())
						{
							$this->db->insert("client_contact_info",$f);
							$client_contact_info_id = $this->db->insert_id();
						}

						$this->db->select('client_contact_info_id, email, primary_email');
				        $this->db->from('transaction_client_contact_info_email');
				        $this->db->where('client_contact_info_id', $check_transaction_client_contact_info->result_array()[0]["id"]);
						$check_transaction_client_contact_info_email = $this->db->get();

						foreach($check_transaction_client_contact_info_email->result() as $r) {
							$r->client_contact_info_id = $client_contact_info_id;
					        $this->db->insert("client_contact_info_email",$r);
					    }
						 

						$this->db->select('client_contact_info_id, phone, primary_phone');
				        $this->db->from('transaction_client_contact_info_phone');
				        $this->db->where('client_contact_info_id', $check_transaction_client_contact_info->result_array()[0]["id"]);
						$check_transaction_client_contact_info_phone = $this->db->get();

						foreach($check_transaction_client_contact_info_phone->result() as $r) {
							$r->client_contact_info_id = $client_contact_info_id;
					        $this->db->insert("client_contact_info_phone",$r);
					    }
					}
					else
					{
						$this->update_service_engagment($check_is_potential_client_array);

					}
				}
				else
				{
					$this->db->select('transaction_engagement_letter_service_info.*, currency.currency as currency_name, unit_pricing.unit_pricing_name, firm.name as firm_name, transaction_master.company_code, our_service_info.id as our_service_id, our_service_info.invoice_description');
			        $this->db->from('transaction_engagement_letter_service_info');
			        $this->db->join('transaction_master', 'transaction_engagement_letter_service_info.transaction_id = transaction_master.id', 'left');
			        $this->db->join('unit_pricing', 'unit_pricing.id = transaction_engagement_letter_service_info.unit_pricing', 'left');
			        $this->db->join('currency', 'currency.id = transaction_engagement_letter_service_info.currency_id ', 'left');
			        $this->db->join('firm', 'firm.id = transaction_engagement_letter_service_info.servicing_firm ', 'left');
			        $this->db->join('our_service_info', 'our_service_info.engagement_letter_list_id = transaction_engagement_letter_service_info.engagement_letter_list_id', 'left');
			        $this->db->where('transaction_id', $transaction_master_id);
			        $this->db->order_by("transaction_engagement_letter_service_info.id", "asc");

			        $q = $this->db->get();

			        $check_is_potential_client_array = $q->result_array();

			       // echo json_encode($check_is_potential_client_array);

			        $this->update_service_engagment($check_is_potential_client_array);

						
					//}
				} 
			}
			
		}

		if($transaction_task == 29)
		{
			if($transaction_status == 3)
			{
				$master["status"] = 2;
				$master["effective_date"] = $lodgement_date;
				$master["service_status"] = $transaction_status;
				$master["remarks"] = $cancellation_reason;
			}
			else if($transaction_status == 4)
			{
				$master["status"] = 5;
				$master["effective_date"] = $lodgement_date;
				$master["service_status"] = $transaction_status;
				$master["remarks"] = $cancellation_reason;

				$this->db->delete("transaction_pending_documents",array('transaction_id'=>$this->session->userdata('transaction_id')));

				$this->db->delete("transaction_pending_documents_file",array('transaction_id'=>$this->session->userdata('transaction_id')));

				$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
				$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));
			}
			else
			{
				$master["status"] = 1;
				$master["effective_date"] = $lodgement_date;
				$master["service_status"] = $transaction_status;
				$master["remarks"] = $cancellation_reason;
			}
			$this->db->update("transaction_master", $master,array("id" => $_POST['transaction_master_id']));
		
		}
		else if($transaction_task == 11 || $transaction_task == 30)
		{
			if($transaction_status == 2)
			{
				if($transaction_task == 30)
				{
					$master["status"] = 2;
					$master["effective_date"] = $lodgement_date;
					$master["service_status"] = $transaction_status;
					$master["remarks"] = $cancellation_reason;
				}
				else
				{
					$master["status"] = 3;
					$master["effective_date"] = $lodgement_date;
					$master["service_status"] = $transaction_status;
					$master["remarks"] = $cancellation_reason;
				}
			}
			else if($transaction_status == 3)
			{
				$master["status"] = 5;
				$master["effective_date"] = $lodgement_date;
				$master["service_status"] = $transaction_status;
				$master["remarks"] = $cancellation_reason;

				$this->db->delete("transaction_pending_documents",array('transaction_id'=>$this->session->userdata('transaction_id')));

				$this->db->delete("transaction_pending_documents_file",array('transaction_id'=>$this->session->userdata('transaction_id')));

				$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
				$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

				if($transaction_task == 30)
				{
					$transaction_engagement_letter_info_query = $this->db->get_where("transaction_engagement_letter_info", array("transaction_id" => $this->session->userdata("transaction_id"), "deleted" => '0'));

					if ($transaction_engagement_letter_info_query->num_rows())
					{	
						$transaction_engagement_letter_info_array = $transaction_engagement_letter_info_query->result_array();

						$transaction_engagement_letter_info_array_info["deleted"] = 1;
						$this->db->update("transaction_engagement_letter_info",$transaction_engagement_letter_info_array_info,array("id" => $transaction_engagement_letter_info_array[0]["id"]));

						$master_el["service_status"] = 1;
						$master_el["status"] = 1;
						$this->db->update("transaction_master",$master_el,array("id" => $transaction_engagement_letter_info_array[0]["transaction_master_id"]));
					}
				}
			}
			else
			{
				$master["status"] = 1;
				$master["effective_date"] = $lodgement_date;
				$master["service_status"] = $transaction_status;
				$master["remarks"] = $cancellation_reason;
			}
			$this->db->update("transaction_master", $master,array("id" => $_POST['transaction_master_id']));
		}
		else
		{
			$master["status"] = 3;
			if($transaction_task == 1 || $transaction_task == 28)
			{
				$master["registration_no"] = $registration_no;
			}
			$master["effective_date"] = $lodgement_date;
			$this->db->update("transaction_master", $master,array("id" => $_POST['transaction_master_id']));
		}

		redirect("transaction");
	}

	public function update_service_engagment($check_is_potential_client_array)
	{
		$this->db->select('client_billing_info.*');
        $this->db->from('client_billing_info');
        $this->db->where('company_code', $check_is_potential_client_array[0]['company_code']);
		$client_billing_info_data = $this->db->get();

		//echo json_encode($client_billing_info_data->result_array());

		$this->db->select('MAX(client_billing_info_id) as max_client_billing_id');
        $this->db->from('client_billing_info');
        $this->db->where('company_code', $check_is_potential_client_array[0]['company_code']);
		$row = $this->db->get();
		$row_max_id = $row->result_array();

		if (!$row->num_rows())
		{	
			$max_id = 0;
		} 
		else 
		{
			$max_id = (int)$row_max_id[0]['max_client_billing_id'];
		}

		//echo json_encode($max_id);

		// for($i = 0; $i < count($check_is_potential_client_array); $i++ )
		// {
		$haveService = false;
		for($i = 0; $i < count($check_is_potential_client_array); $i++ )
		{
			foreach($client_billing_info_data->result() as $g) 
			{
				if($g->service == $check_is_potential_client_array[$i]['our_service_id'])
				{
					$service_id = $g->service;
					$haveService = true;
					break;
				}

			}

			if($haveService)
			{
				$client_billing_info['amount'] = (float)str_replace(',', '', $check_is_potential_client_array[$i]['fee']);
				$client_billing_info['currency'] = $check_is_potential_client_array[$i]['currency_id'];
				$client_billing_info['unit_pricing'] = $check_is_potential_client_array[$i]['unit_pricing'];
				$this->db->update("client_billing_info",$client_billing_info,array("company_code" =>  $check_is_potential_client_array[0]['company_code'], "service" => $service_id));
				$haveService = false;
			}
			else
			{
				$client_billing_info['company_code'] = $check_is_potential_client_array[$i]['company_code'];
				$client_billing_info['client_billing_info_id'] = $max_id + 1;
				$client_billing_info['service'] = $check_is_potential_client_array[$i]['our_service_id'];
				$client_billing_info['invoice_description'] = $check_is_potential_client_array[$i]['invoice_description'];
				//(int)str_replace(',', '', $amount[$p]);
				$client_billing_info['amount'] = (float)str_replace(',', '', $check_is_potential_client_array[$i]['fee']);
				$client_billing_info['currency'] = $check_is_potential_client_array[$i]['currency_id'];
				$client_billing_info['unit_pricing'] = $check_is_potential_client_array[$i]['unit_pricing'];

				$this->db->insert("client_billing_info",$client_billing_info);

				$max_id = $max_id + 1;
			}
		}
	}

	public function check_valid_officer()
	{
		$check_valid_officer = true;
		$transaction_master_id = $_POST['transaction_master_id'];
		$company_code = $_POST['company_code'];

		$this->db->select('id, company_code, position, alternate_of, officer_id, field_type, date_of_appointment, date_of_cessation, retiring, created_at');
	        $this->db->from('transaction_client_officers');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('transaction_id', $transaction_master_id);
			$check_transaction_client_officers = $this->db->get();

		foreach($check_transaction_client_officers->result() as $r) {

			$r->date_of_appointment = $lodgement_date;
			$k['company_code'] = $r->company_code;
			$k['position'] = $r->position;
			$k['alternate_of'] = $r->alternate_of;
			$k['officer_id'] = $r->officer_id;
			$k['field_type'] = $r->field_type;
			$k['date_of_appointment'] = $r->date_of_appointment;
			$k['date_of_cessation'] = $r->date_of_cessation;
			$k['retiring'] = $r->retiring;
			$k['created_at'] = $r->created_at;

			$check_date = $this->db->query("select * from client_officers where position='".$k['position']."' AND officer_id = '".$k['officer_id']."' AND field_type = '".$k['field_type']."' AND company_code = '".$k['company_code']."' ORDER BY STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') DESC LIMIT 2");

			$check_date = $check_date->result_array();
			// echo json_encode($check_date);
			if(count($check_date) > 0){
				$date_of_cessation = $check_date[0]["date_of_cessation"];
				$date_of_appointment = $check_date[0]["date_of_appointment"];

				if($date_of_cessation == null && $k['date_of_cessation'] == null)
				{
					//echo json_encode($k['date_of_cessation'] != null);
					$check_valid_officer = false;
					break;
					
				}
			}else{
				$check_valid_officer = true;
			}

			
	    }

	    if($check_valid_officer)
	    {
	    	echo json_encode(array("Status" => 1));
	    }
	    else
	    {
	    	echo json_encode(array("Status" => 2, 'message' => 'This person has not ceased to hold the position in the company.', 'title' => 'Error'));
	    }
	}

	public function MonthShifter (DateTime $aDate,$months){
        $dateA = clone($aDate);
        $dateB = clone($aDate);
        $plusMonths = clone($dateA->modify($months . ' Month'));
        //check whether reversing the month addition gives us the original day back
        if($dateB != $dateA->modify($months*-1 . ' Month')){ 
            $result = $plusMonths->modify('last day of last month');
        } elseif($aDate == $dateB->modify('last day of this month')){
            $result =  $plusMonths->modify('last day of this month');
        } else {
            $result = $plusMonths;
        }
        return $result;
    }

    public function check_client_info()
	{
		$registration_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query("select * from client where registration_no = '".$registration_no."' AND firm_id = '".$this->session->userdata('firm_id')."' and deleted = 0");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        echo FALSE;

	}

	public function check_filing_info()
	{
		$registration_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query("select client.*, filing.year_end, financial_year_period.period from client left join filing on filing.id = (select MAX(id) as filing_id from filing where filing.company_code = client.company_code) left join financial_year_period on financial_year_period.id = filing.financial_year_period_id where client.registration_no = '".$registration_no."' and client.firm_id = '".$this->session->userdata('firm_id')."' and client.deleted = 0");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        echo FALSE;

	}

	public function get_all_member(){
        //$client_member_share_capital_id = $_POST["client_member_share_capital_id"];
        $company_code = $_POST["company_code"];

        //echo json_encode($company_code);
        
        $q = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" and client.firm_id = "'.$this->session->userdata('firm_id').'" and client.deleted = 0 where member_shares.company_code="'.$company_code.'"and share_capital.class_id = 1 GROUP BY member_shares.field_type, member_shares.officer_id, member_shares.client_member_share_capital_id HAVING sum(member_shares.number_of_share) > 0');
        //,member_shares.client_member_share_capital_id
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        echo FALSE;
    }

	public function get_resign_director_info()
	{
		$registration_no = strtoupper($_POST["registration_no"]);

		// $client_query = $this->db->query("select * from client where registration_no = '".$registration_no."'");

		// $client_query = $client_query->result_array();

		$q = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where client_officers.company_code = (select client.company_code from client where client.registration_no = '".$registration_no."' and client.firm_id = '".$this->session->userdata('firm_id')."' and client.deleted = 0) AND client_officers.position = '1' AND date_of_cessation = '' order by client_officers.id");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        echo FALSE;

        //echo $client_query[0]["company_code"]

	}
	public function get_resign_auditor_info()
	{
		$registration_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where client_officers.company_code = (select client.company_code from client where client.registration_no = '".$registration_no."' and client.firm_id = '".$this->session->userdata('firm_id')."' and client.deleted = 0) AND client_officers.position = '5' AND date_of_cessation = '' order by client_officers.id");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        echo FALSE;
	}

	public function get_all_director_retiring()
    {
    	$company_code = $_POST["company_code"];

        $q = $this->db->query('select client_officers.*, officer.identification_no, officer.name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type where position = 1 AND  date_of_cessation = "" AND company_code ="'.$company_code.'"');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            echo json_encode($data);
        }

        echo false;
    }

	public function add_appoint_resign_auditor()
	{
		//echo json_encode($_POST);
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = strtoupper($_POST['registration_no']);
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = "";
		$transaction['last_edited_by'] = $this->session->userdata('user_id');

		$transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code" => $_POST['transaction_code']));

		if (!$transaction_master_query->num_rows())
		{
			$transaction['created_by'] = $this->session->userdata('user_id');

			$this->db->insert("transaction_master",$transaction);
			$transaction_id = $this->db->insert_id();
			$this->session->set_userdata(array(
                'transaction_id'  => $transaction_id,
            ));

            $transaction_code = $transaction['transaction_code'];
		} 
		else 
		{
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $this->session->userdata('transaction_id')));
			$transaction_id = $this->session->userdata('transaction_id');
			$transaction_code = $_POST["transaction_code"];
		}

		if(count($_POST['identification_register_no']) > 0)
		{
			$this->db->delete("transaction_client_officers",array('company_code'=>$_POST['company_code'], 'transaction_id'=>$transaction_id, 'date_of_appointment'=>""));
			for($i = 0; $i < count($_POST['identification_register_no']); $i++ )
			{
				$data['transaction_id']=$this->session->userdata('transaction_id');
				$data['company_code']=$_POST['company_code'];
				$data['officer_id']=$_POST['officer_id'][$i];
				$data['field_type']=$_POST['officer_field_type'][$i];
				$data['position'] = 5;
				$data['alternate_of']=' ';
				$data['date_of_appointment'] = "";
				$data['date_of_cessation'] = "";
				$data['retiring'] = 0;

				$date["meeting_date"] = $_POST['meeting_date'];
				$date["notice_date"] = $_POST['notice_date'];
				
				$q = $this->db->get_where("transaction_client_officers", array("id" => $_POST['client_officer_id'][$i]));

				if (!$q->num_rows())
				{
					$this->db->insert("transaction_client_officers",$data);
					$insert_client_officers_id = $this->db->insert_id();

					$date["transaction_client_officers_id"] = $insert_client_officers_id;

					$this->db->insert("transaction_appoint_auditor_date",$date);
					
				} 
				else 
				{
					$this->db->update("transaction_client_officers",$data,array("id" => $_POST['client_officer_id'][$i]));
					
					$this->db->update("transaction_appoint_auditor_date",$date,array("id" => $_POST['client_officer_id'][$i]));
				}

			}
		}

		if(count($_POST['hidden_resign_identification_register_no']) > 0)
		{
			for($i = 0; $i < count($_POST['hidden_resign_identification_register_no']); $i++ )
			{
				$data['transaction_id']=  $this->session->userdata('transaction_id');
				$data['company_code'] = $_POST['company_code'];
				$data['officer_id'] = $_POST['resign_officer_id'][$i];
				$data['field_type'] = $_POST['resign_officer_field_type'][$i];
				$data['position'] = 5;
				$data['alternate_of']=' ';
				$data['date_of_appointment'] = $_POST['hidden_date_of_appointment'][$i];
				$data['date_of_cessation'] = $_POST['hidden_date_of_cessation'][$i];
				$data['retiring'] = 0;

				if($_POST["hidden_resign_auditor_reason"][$i] == undefined)
				{
					$reason["reason"] = "";
				}
				else
				{
					$reason["reason"] = strtoupper($_POST["hidden_resign_auditor_reason"][$i]);
				}
				
				$q = $this->db->get_where("transaction_client_officers", array("id" => $_POST['resign_client_officer_id'][$i]));

				if (!$q->num_rows())
				{
					$this->db->insert("transaction_client_officers",$data);
					$insert_client_officers_id = $this->db->insert_id();

					$reason["transaction_client_officers_id"] = $insert_client_officers_id;

					$this->db->insert("transaction_resign_officer_reason",$reason);
				} 
				else 
				{
					$this->db->update("transaction_client_officers",$data,array("id" => $_POST['resign_client_officer_id'][$i]));

					$this->db->update("transaction_resign_officer_reason",$reason,array("transaction_client_officers_id" => $_POST['resign_client_officer_id'][$i]));
					
				}
			}
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		$this->data['transaction_client_officers'] = $this->transaction_model->getTransactionResignClientOfficer($this->session->userdata('transaction_id'));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_client_officers" => $this->data['transaction_client_officers'], "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function add_resign_director()
	{
		//echo json_encode($_POST);
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = strtoupper($_POST['registration_no']);
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = "";
		$transaction['last_edited_by'] = $this->session->userdata('user_id');

		$transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code" => $_POST['transaction_code']));

		if (!$transaction_master_query->num_rows())
		{
			$transaction['created_by'] = $this->session->userdata('user_id');

			$this->db->insert("transaction_master",$transaction);
			$transaction_id = $this->db->insert_id();
			$this->session->set_userdata(array(
                'transaction_id'  => $transaction_id,
            ));

            $transaction_code = $transaction['transaction_code'];

		} 
		else 
		{
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $this->session->userdata('transaction_id')));
			$transaction_id = $this->session->userdata('transaction_id');
			$transaction_code = $_POST['transaction_code'];
		}

		for($i = 0; $i < count($_POST['hidden_resign_identification_register_no']); $i++ )
		{
			$data['transaction_id']=  $this->session->userdata('transaction_id');
			$data['company_code'] = $_POST['company_code'];
			$data['officer_id'] = $_POST['resign_officer_id'][$i];
			$data['field_type'] = $_POST['resign_officer_field_type'][$i];
			$data['position'] = 1;
			$data['alternate_of']=' ';
			$data['date_of_appointment'] = $_POST['hidden_date_of_appointment'][$i];
			$data['date_of_cessation'] = $_POST['hidden_date_of_cessation'][$i];
			$data['retiring'] = 0;

			$reason["is_resign"] = $_POST['is_director_withdraw'][$i];
			
			if($_POST["hidden_resign_director_reason"][$i] == undefined)
			{
				$reason["reason"] = "";
			}
			else
			{
				$reason["reason"] = strtoupper($_POST["hidden_resign_director_reason"][$i]);
			}
			
			$q = $this->db->get_where("transaction_client_officers", array("id" => $_POST['resign_client_officer_id'][$i]));

			if (!$q->num_rows())
			{
				$this->db->insert("transaction_client_officers",$data);
				$insert_client_officers_id = $this->db->insert_id();

				$reason["transaction_client_officers_id"] = $insert_client_officers_id;

				$this->db->insert("transaction_resign_officer_reason",$reason);
			} 
			else 
			{
				$this->db->update("transaction_client_officers",$data,array("id" => $_POST['resign_client_officer_id'][$i]));

				$transaction_resign_officer_reason_info = $this->db->get_where("transaction_resign_officer_reason", array("transaction_client_officers_id" => $_POST['resign_client_officer_id'][$i]));

				if (!$transaction_resign_officer_reason_info->num_rows())
				{
					$reason["transaction_client_officers_id"] = $_POST['resign_client_officer_id'][$i];

					$this->db->insert("transaction_resign_officer_reason",$reason);
				}
				else
				{
					$this->db->update("transaction_resign_officer_reason",$reason,array("transaction_client_officers_id" => $_POST['resign_client_officer_id'][$i]));
				}

				
				
			}


		}
		$this->db->delete("transaction_client_officers",array('company_code'=>$_POST['company_code'], 'transaction_id'=>$transaction_id, 'date_of_appointment'=>""));

		for($i = 0; $i < count($_POST['identification_register_no']); $i++ )
		{
			$data['transaction_id']=$this->session->userdata('transaction_id');
			$data['company_code']=$_POST['company_code'];
			$data['officer_id']=$_POST['officer_id'][$i];
			$data['field_type']=$_POST['officer_field_type'][$i];
			$data['position']=1;
			
			if($_POST['alternate_of'][$i] == null)
			{
				$data['alternate_of']=' ';
				$check_alternate_of = ' ';
			}
			else
			{
				$data['alternate_of']=$_POST['alternate_of'][$i];
				$check_alternate_of = $_POST['alternate_of'][$i];;
			}
			//$data['date_of_appointment']=$_POST['date_of_appointment'][$i];
			$data['date_of_appointment'] = "";
			$data['date_of_cessation'] = "";

			$data['retiring'] = 0;
			
			$q = $this->db->get_where("transaction_client_officers", array("id" => $_POST['client_officer_id'][$i]));

			if (!$q->num_rows())
			{
				$this->db->insert("transaction_client_officers",$data);
				$insert_client_officers_id = $this->db->insert_id();
				
			} 
			else 
			{
				$this->db->update("transaction_client_officers",$data,array("id" => $_POST['client_officer_id'][$i]));
				
			}

		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		$this->data['transaction_client_officers'] = $this->transaction_model->getTransactionResignClientOfficer($this->session->userdata('transaction_id'));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_client_officers" => $this->data['transaction_client_officers'], "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function add_appoint_new_secretarial()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = strtoupper($_POST['registration_no']);
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = "";
		$transaction['last_edited_by'] = $this->session->userdata('user_id');

		$transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code" => $_POST['transaction_code']));

		if (!$transaction_master_query->num_rows())
		{
			$transaction['created_by'] = $this->session->userdata('user_id');

			$this->db->insert("transaction_master",$transaction);
			$transaction_id = $this->db->insert_id();
			$this->session->set_userdata(array(
                'transaction_id'  => $transaction_id,
            ));
            $transaction_code = $transaction['transaction_code'];
		} 
		else 
		{
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $this->session->userdata('transaction_id')));
			$transaction_id = $this->session->userdata('transaction_id');
			$transaction_code = $_POST['transaction_code'];
			
		}

		for($i = 0; $i < count($_POST['identification_register_no']); $i++ )
		{
			$data['transaction_id']=$this->session->userdata('transaction_id');
			$data['company_code']=$_POST['company_code'];
			$data['officer_id']=$_POST['officer_id'][$i];
			$data['field_type']=$_POST['officer_field_type'][$i];
			$data['position']=$_POST['position'][$i];
			$data['alternate_of']=' ';
			$data['date_of_appointment'] = "";
			$data['date_of_cessation'] = "";

			$data['retiring'] = 0;
			
			$q = $this->db->get_where("transaction_client_officers", array("id" => $_POST['client_officer_id'][$i]));

			if (!$q->num_rows())
			{
				$this->db->insert("transaction_client_officers",$data);
				$insert_client_officers_id = $this->db->insert_id();
				
			} 
			else 
			{
				$this->db->update("transaction_client_officers",$data,array("id" => $_POST['client_officer_id'][$i]));
				
			}

		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		$this->data['transaction_client_officers'] = $this->transaction_model->getTransactionClientOfficer($this->session->userdata('transaction_id'), $_POST['company_code']);

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_client_officers" => $this->data['transaction_client_officers'], "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function add_appoint_new_director()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = strtoupper($_POST['registration_no']);
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = "";
		$transaction['last_edited_by'] = $this->session->userdata('user_id');

		$transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code" => $_POST['transaction_code']));

		if (!$transaction_master_query->num_rows())
		{
			$transaction['created_by'] = $this->session->userdata('user_id');

			$this->db->insert("transaction_master",$transaction);
			$transaction_id = $this->db->insert_id();
			$this->session->set_userdata(array(
                'transaction_id'  => $transaction_id,
            ));
            $transaction_code = $transaction['transaction_code'];
		} 
		else 
		{
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $this->session->userdata('transaction_id')));
			$transaction_id = $this->session->userdata('transaction_id');
			$transaction_code = $_POST['transaction_code'];
			
		}

		for($i = 0; $i < count($_POST['identification_register_no']); $i++ )
		{
			$data['transaction_id']=$this->session->userdata('transaction_id');
			$data['company_code']=$_POST['company_code'];
			$data['officer_id']=$_POST['officer_id'][$i];
			$data['field_type']=$_POST['officer_field_type'][$i];
			$data['position']=$_POST['position'][$i];
			
			if($_POST['alternate_of'][$i] == null)
			{
				$data['alternate_of']=' ';
				$check_alternate_of = ' ';
			}
			else
			{
				$data['alternate_of']=$_POST['alternate_of'][$i];
				$check_alternate_of = $_POST['alternate_of'][$i];;
			}
			//$data['date_of_appointment']=$_POST['date_of_appointment'][$i];
			$data['date_of_appointment'] = "";
			$data['date_of_cessation'] = "";

			$data['retiring'] = 0;
			
			$q = $this->db->get_where("transaction_client_officers", array("id" => $_POST['client_officer_id'][$i]));

			if (!$q->num_rows())
			{
				$this->db->insert("transaction_client_officers",$data);
				$insert_client_officers_id = $this->db->insert_id();
				
			} 
			else 
			{
				$this->db->update("transaction_client_officers",$data,array("id" => $_POST['client_officer_id'][$i]));
				
			}

		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		$this->data['transaction_client_officers'] = $this->transaction_model->getTransactionClientOfficer($this->session->userdata('transaction_id'), $_POST['company_code']);

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_client_officers" => $this->data['transaction_client_officers'], "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function add_engagement_letter()
	{
		//echo json_encode($_POST);
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = strtoupper($_POST['registration_no']);
		// $transaction['client_type_id'] = $_POST['client_type'];
		$transaction['client_name'] = str_replace("(Potential Client)", "", $_POST['client_name']);;
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = "";
		$transaction['last_edited_by'] = $this->session->userdata('user_id');

		$transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code" => $_POST['transaction_code']));

		if (!$transaction_master_query->num_rows())
		{
			$transaction['created_by'] = $this->session->userdata('user_id');

			$this->db->insert("transaction_master",$transaction);
			$transaction_id = $this->db->insert_id();
			$this->session->set_userdata(array(
                'transaction_id'  => $transaction_id,
            ));
            $transaction_code = $transaction['transaction_code'];
		} 
		else 
		{
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $this->session->userdata('transaction_id')));
			$transaction_id = $this->session->userdata('transaction_id');
			$transaction_code = $_POST["transaction_code"];
		}

		if($_POST["trans_master_service_proposal_id"] != "")
		{
			$transaction_engagement_letter_info['transaction_id'] = $transaction_id;
			$transaction_engagement_letter_info['transaction_master_id'] = $_POST["trans_master_service_proposal_id"];
			// $transaction_engagement_letter_info['uen'] = $_POST["uen"];
			// $transaction_engagement_letter_info['fye_date'] = $_POST["fye_date"];
			// $transaction_engagement_letter_info['director_signing'] = $_POST["director_signing"];

			$transaction_engagement_letter_info_query = $this->db->get_where("transaction_engagement_letter_info", array("transaction_id" => $transaction_id));

			if (!$transaction_engagement_letter_info_query->num_rows())
			{
				$this->db->insert("transaction_engagement_letter_info",$transaction_engagement_letter_info);
			}
			else
			{
				$this->db->update("transaction_engagement_letter_info",$transaction_engagement_letter_info,array("transaction_id" => $transaction_id));
			}

			$master["service_status"] = 2;
			$master["status"] = 6;
			$this->db->update("transaction_master",$master,array("id" => $_POST["trans_master_service_proposal_id"]));
		}

		$transaction_engagement_letter_additional_info['transaction_id'] = $transaction_id;
		$transaction_engagement_letter_additional_info['engagement_letter_date'] = $_POST["engagement_letter_date"];
		$transaction_engagement_letter_additional_info['uen'] = strtoupper($_POST["uen"]);
		$transaction_engagement_letter_additional_info['fye_date'] = $_POST["fye_date"];
		$transaction_engagement_letter_additional_info['director_signing'] = strtoupper($_POST["director_signing"]);

		$transaction_engagement_letter_additional_info_query = $this->db->get_where("transaction_engagement_letter_additional_info", array("transaction_id" => $transaction_id));

		if (!$transaction_engagement_letter_additional_info_query->num_rows())
		{
			$this->db->insert("transaction_engagement_letter_additional_info",$transaction_engagement_letter_additional_info);
		}
		else
		{
			$this->db->update("transaction_engagement_letter_additional_info",$transaction_engagement_letter_additional_info,array("transaction_id" => $transaction_id));
		}

		$this->db->delete("transaction_engagement_letter_service_info",array('transaction_id'=>$this->session->userdata("transaction_id")));

		for($h = 0; $h < count($_POST['hidden_selected_el_id']); $h++)
        {
        	if($_POST['hidden_selected_el_id'][$h] != "")
        	{
        		$transaction_engagement_letter_service_info['transaction_id'] = $this->session->userdata('transaction_id');
				$transaction_engagement_letter_service_info['engagement_letter_list_id'] = $_POST['hidden_selected_el_id'][$h];
				$transaction_engagement_letter_service_info['currency_id'] = $_POST['currency'][$h];
				$transaction_engagement_letter_service_info['fee'] = str_replace(',', '', $_POST['fee'][$h]);
				$transaction_engagement_letter_service_info['unit_pricing'] = $_POST['unit_pricing'][$h];
				$transaction_engagement_letter_service_info['servicing_firm'] = $_POST['servicing_firm'][$h];

				$this->db->insert('transaction_engagement_letter_service_info', $transaction_engagement_letter_service_info);
        	}
        }

        $edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function add_service_proposal()
	{
		//echo json_encode($_POST);
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "client_name" => $_POST['client_name'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "client_name" => $_POST['client_name'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = strtoupper($_POST['registration_no']);
		$transaction['client_type_id'] = $_POST['client_type'];
		$transaction['client_name'] = $_POST['client_name'];
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = "";
		$transaction['last_edited_by'] = $this->session->userdata('user_id');

		$transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code" => $_POST['transaction_code']));

		if (!$transaction_master_query->num_rows())
		{
			$transaction['created_by'] = $this->session->userdata('user_id');

			$this->db->insert("transaction_master",$transaction);
			$transaction_id = $this->db->insert_id();
			$this->session->set_userdata(array(
                'transaction_id'  => $transaction_id,
            ));
            $transaction_code = $transaction['transaction_code'];
		} 
		else 
		{
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $this->session->userdata('transaction_id')));
			$transaction_id = $this->session->userdata('transaction_id');
			$transaction_code = $_POST["transaction_code"];
		}

		$transaction_service_proposal_info['transaction_id']=$this->session->userdata('transaction_id');
		$transaction_service_proposal_info['proposal_date']=$_POST['proposal_date'];
		$transaction_service_proposal_info['activity1']=$_POST['activity1'];
		$transaction_service_proposal_info['activity2']= $_POST['activity2'];
		$transaction_service_proposal_info['postal_code']= $_POST['postal_code'];
		$transaction_service_proposal_info['street_name']= $_POST['street_name'];
		$transaction_service_proposal_info['building_name']= $_POST['building_name'];
		$transaction_service_proposal_info['unit_no1']= $_POST['unit_no1'];
		$transaction_service_proposal_info['unit_no2']= $_POST['unit_no2'];
		if($_POST['client_type'] == "1")
		{
			$transaction_service_proposal_info['potential_client'] = 0 ;
		}

		$q = $this->db->get_where("transaction_service_proposal_info", array("transaction_id" => $transaction_id));

		if (!$q->num_rows())
		{
			$this->db->insert("transaction_service_proposal_info",$transaction_service_proposal_info);
		} 
		else 
		{
			$this->db->update("transaction_service_proposal_info",$transaction_service_proposal_info,array("transaction_id" => $transaction_id));
		}

		$transaction_client_contact_info["transaction_id"] = $this->session->userdata("transaction_id"); 
		$transaction_client_contact_info['company_code'] = $_POST['company_code'];
		$transaction_client_contact_info['name'] = $_POST['contact_name'];

		$query = $this->db->get_where("transaction_client_contact_info", array("company_code" => $_POST['company_code'], "transaction_id" => $this->session->userdata("transaction_id")));

		if (!$query->num_rows())
		{				
			$this->db->insert("transaction_client_contact_info",$transaction_client_contact_info);
			$client_contact_info_id = $this->db->insert_id();
			for($g = 0; $g < count($_POST['hidden_contact_phone']); $g++)
            {
                if($_POST['hidden_contact_phone'][$g] != "")
                {
                    $contactPhone['client_contact_info_id'] = $client_contact_info_id;
                    $contactPhone['phone'] = strtoupper($_POST['hidden_contact_phone'][$g]);
                    if($_POST['contact_phone_primary'] == $_POST['hidden_contact_phone'][$g])
                    {
                        $contactPhone['primary_phone'] = 1;
                    }
                    else
                    {
                        $contactPhone['primary_phone'] = 0;
                    }
                    $this->db->insert('transaction_client_contact_info_phone', $contactPhone);
                }
            }

            for($g = 0; $g < count($_POST['contact_email']); $g++)
            {
                if($_POST['contact_email'][$g] != "")
                {
                    $contactEmail['client_contact_info_id'] = $client_contact_info_id;
                    $contactEmail['email'] = strtoupper($_POST['contact_email'][$g]);
                    if($_POST['contact_email_primary'] == $_POST['contact_email'][$g])
                    {
                        $contactEmail['primary_email'] = 1;
                    }
                    else
                    {
                        $contactEmail['primary_email'] = 0;
                    }
                    $this->db->insert('transaction_client_contact_info_email', $contactEmail);
                }
            }
		} 
		else 
		{	
			$this->db->where(array("company_code" => $_POST['company_code'], "transaction_id" => $this->session->userdata("transaction_id")));
			$this->db->update("transaction_client_contact_info",$transaction_client_contact_info);
			$client_contact_information = $query->result_array(); 
			$client_contact_info_id = $client_contact_information[0]["id"];

			$this->db->delete("transaction_client_contact_info_phone",array('client_contact_info_id'=>$client_contact_info_id));

			for($g = 0; $g < count($_POST['hidden_contact_phone']); $g++)
            {
                if($_POST['hidden_contact_phone'][$g] != "")
                {
                    $contactPhone['client_contact_info_id'] = $client_contact_info_id;
                    $contactPhone['phone'] = strtoupper($_POST['hidden_contact_phone'][$g]);
                    if($_POST['contact_phone_primary'] == $_POST['hidden_contact_phone'][$g])
                    {
                        $contactPhone['primary_phone'] = 1;
                    }
                    else
                    {
                        $contactPhone['primary_phone'] = 0;
                    }
                    $this->db->insert('transaction_client_contact_info_phone', $contactPhone);
                }
            }

            $this->db->delete("transaction_client_contact_info_email",array('client_contact_info_id'=>$client_contact_info_id));

            for($g = 0; $g < count($_POST['contact_email']); $g++)
            {
                if($_POST['contact_email'][$g] != "")
                {
                    $contactEmail['client_contact_info_id'] = $client_contact_info_id;
                    $contactEmail['email'] = strtoupper($_POST['contact_email'][$g]);
                    if($_POST['contact_email_primary'] == $_POST['contact_email'][$g])
                    {
                        $contactEmail['primary_email'] = 1;
                    }
                    else
                    {
                        $contactEmail['primary_email'] = 0;
                    }
                    $this->db->insert('transaction_client_contact_info_email', $contactEmail);
                }
            }
		}

		$transaction_engagement_letter_info_query = $this->db->get_where("transaction_engagement_letter_info", array("transaction_master_id" => $this->session->userdata("transaction_id"), "deleted" => '0'));

		if ($transaction_engagement_letter_info_query->num_rows())
		{	
			$transaction_engagement_letter_info_array = $transaction_engagement_letter_info_query->result_array();

			$this->db->delete("transaction_engagement_letter_service_info",array('transaction_id' => $transaction_engagement_letter_info_array[0]["transaction_id"]));
		}

		$this->db->delete("transaction_service_proposal_service_info",array('transaction_id'=>$this->session->userdata("transaction_id")));

		for($h = 0; $h < count($_POST['hidden_selected_service_id']); $h++)
        {
        	if($_POST['hidden_selected_service_id'][$h] != "")
        	{
        		$transaction_service_proposal_service_info['transaction_id'] = $this->session->userdata('transaction_id');
				$transaction_service_proposal_service_info['our_service_id'] = $_POST['hidden_selected_service_id'][$h];
				$transaction_service_proposal_service_info['currency_id'] = $_POST['currency'][$h];
				$transaction_service_proposal_service_info['fee'] = str_replace(',', '', $_POST['fee'][$h]);
				$transaction_service_proposal_service_info['unit_pricing'] = $_POST['unit_pricing'][$h];
				$transaction_service_proposal_service_info['servicing_firm'] = $_POST['servicing_firm'][$h];

				$this->db->insert('transaction_service_proposal_service_info', $transaction_service_proposal_service_info);

				if ($transaction_engagement_letter_info_query->num_rows())
				{
					$transaction_engagement_letter_info["transaction_id"] = $transaction_engagement_letter_info_array[0]["transaction_id"];
					$transaction_engagement_letter_info["engagement_letter_list_id"] = $_POST["engagement_letter_list_id"][$h];
					$transaction_engagement_letter_info['currency_id'] = $_POST['currency'][$h];
					$transaction_engagement_letter_info['fee'] = str_replace(',', '', $_POST['fee'][$h]);
					$transaction_engagement_letter_info['unit_pricing'] = $_POST['unit_pricing'][$h];
					$transaction_engagement_letter_info['servicing_firm'] = $_POST['servicing_firm'][$h];

					$this->db->insert('transaction_engagement_letter_service_info', $transaction_engagement_letter_info);
				}
        	}
        }

        $edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function add_new_fye()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = strtoupper($_POST['registration_no']);
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = "";
		$transaction['last_edited_by'] = $this->session->userdata('user_id');

		$transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code" => $_POST['transaction_code']));

		if (!$transaction_master_query->num_rows())
		{
			$transaction['created_by'] = $this->session->userdata('user_id');

			$this->db->insert("transaction_master",$transaction);
			$transaction_id = $this->db->insert_id();
			$this->session->set_userdata(array(
                'transaction_id'  => $transaction_id,
            ));
            $transaction_code = $transaction['transaction_code'];
		} 
		else 
		{
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $this->session->userdata('transaction_id')));
			$transaction_id = $this->session->userdata('transaction_id');
			$transaction_code = $_POST["transaction_code"];
		}

		$data['transaction_id']=$this->session->userdata('transaction_id');
		$data['company_name']=$_POST['company_name'];
		$data['old_year_end']= $_POST['old_FYE'];
		$data['old_financial_year_period']= $_POST['old_period'];
		$data['new_year_end']= $_POST['new_FYE'];
		$data['financial_year_period']= $_POST['financial_year_period'];

		
		$q = $this->db->get_where("transaction_change_fye", array("id" => $_POST['transaction_change_FYE_id']));

		if (!$q->num_rows())
		{
			$this->db->insert("transaction_change_fye",$data);
			$insert_transaction_change_FYE_id = $this->db->insert_id();
			
		} 
		else 
		{
			$this->db->update("transaction_change_fye",$data,array("id" => $_POST['transaction_change_FYE_id']));
			$insert_transaction_change_FYE_id = $_POST['transaction_change_FYE_id'];
			
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_change_FYE_id" => $insert_transaction_change_FYE_id, "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function add_new_biz_activity()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = strtoupper($_POST['registration_no']);
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = "";
		$transaction['last_edited_by'] = $this->session->userdata('user_id');

		$transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code" => $_POST['transaction_code']));

		if (!$transaction_master_query->num_rows())
		{
			$transaction['created_by'] = $this->session->userdata('user_id');

			$this->db->insert("transaction_master",$transaction);
			$transaction_id = $this->db->insert_id();
			$this->session->set_userdata(array(
                'transaction_id'  => $transaction_id,
            ));
            $transaction_code = $transaction['transaction_code'];
		} 
		else 
		{
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $this->session->userdata('transaction_id')));
			$transaction_id = $this->session->userdata('transaction_id');
			$transaction_code = $_POST['transaction_code'];
			
		}

		$data['transaction_id']=$this->session->userdata('transaction_id');
		$data['company_name']=$_POST['company_name'];
		$data['old_activity1']= strtoupper($_POST['old_activity1']);
		$data['old_activity2']= strtoupper($_POST['old_activity2']);
		$data['activity1']= strtoupper($_POST['new_activity1']);
		$data['remove_activity_2']= (isset($_POST['remove_activity_2'])) ? 1 : 0;
		$data['activity2']= strtoupper($_POST['new_activity2']);
		
		$q = $this->db->get_where("transaction_change_biz_activity", array("id" => $_POST['transaction_change_biz_activity_id']));

		if (!$q->num_rows())
		{
			$this->db->insert("transaction_change_biz_activity",$data);
			$insert_transaction_change_biz_activity_id = $this->db->insert_id();
			
		} 
		else 
		{
			$this->db->update("transaction_change_biz_activity",$data,array("id" => $_POST['transaction_change_biz_activity_id']));
			$insert_transaction_change_biz_activity_id = $_POST['transaction_change_biz_activity_id'];
			
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_change_biz_activity_id" => $insert_transaction_change_biz_activity_id, "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function save_agm_ar()
	{
		//echo json_encode($_POST);

		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = strtoupper($_POST['registration_no']);
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = "";
		$transaction['last_edited_by'] = $this->session->userdata('user_id');

		$transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code" => $_POST['transaction_code']));

		if (!$transaction_master_query->num_rows())
		{
			$transaction['created_by'] = $this->session->userdata('user_id');

			$this->db->insert("transaction_master",$transaction);
			$transaction_id = $this->db->insert_id();
			$this->session->set_userdata(array(
                'transaction_id'  => $transaction_id,
            ));
            $transaction_code = $transaction['transaction_code'];
		} 
		else 
		{
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $this->session->userdata('transaction_id')));
			$transaction_id = $this->session->userdata('transaction_id');
			$transaction_code = $_POST["transaction_code"];
		}

		$data['transaction_id'] = $this->session->userdata('transaction_id');
		$data['is_first_agm_id'] = $_POST['first_agm'];
		$data['year_end_date'] = $_POST['fye_date'];
		$data['agm_date'] = $_POST['agm_date'];
		$data['reso_date'] = $_POST['reso_date'];
		$data['activity_status'] = $_POST['activity_status'];
		$data['solvency_status'] = $_POST['solvency_status'];
		$data['epc_status_id'] = $_POST['epc_status'];
		$data['small_company'] = $_POST['small_company'];
		$data['audited_fs'] = $_POST['audited_fs'];
		$data['agm_share_transfer_id'] = $_POST['share_transfer'];
		$data['shorter_notice'] = $_POST['shorter_notice'];
		$data['chairman'] = $_POST['chairman'];

		$transaction_agm_ar_query = $this->db->get_where("transaction_agm_ar", array("id" => $_POST['transaction_agm_ar_id']));

		if (!$transaction_agm_ar_query->num_rows())
		{
			$this->db->insert("transaction_agm_ar",$data);
			$transaction_agm_ar_id = $this->db->insert_id();
		}
		else
		{
			$this->db->update("transaction_agm_ar",$data,array("id" => $_POST['transaction_agm_ar_id']));
			$transaction_agm_ar_id = $_POST['transaction_agm_ar_id'];
		}

		$this->db->delete("transaction_agm_ar_director_fee",array('transaction_agm_ar_id'=>$transaction_agm_ar_id));

		for($r = 0; $r < count($_POST['director_fee_name']); $r++)
		{
			$transaction_agm_ar_director_fee['transaction_agm_ar_id'] = $transaction_agm_ar_id;
			$transaction_agm_ar_director_fee['director_fee_name'] = $_POST['director_fee_name'][$r];
			$transaction_agm_ar_director_fee['director_fee_identification_no'] = $_POST['director_fee_identification_register_no'][$r];
			$transaction_agm_ar_director_fee['director_fee_officer_id'] = $_POST['director_fee_officer_id'][$r];
			$transaction_agm_ar_director_fee['director_fee_officer_field_type'] = $_POST['director_fee_officer_field_type'][$r];
			$transaction_agm_ar_director_fee['director_fee'] = str_replace(",", "", $_POST['director_fee'][$r]);

			$this->db->insert("transaction_agm_ar_director_fee",$transaction_agm_ar_director_fee);
		}

		$this->db->delete("transaction_agm_ar_dividend",array('transaction_agm_ar_id'=>$transaction_agm_ar_id));

		for($r = 0; $r < count($_POST['dividend_name']); $r++)
		{
			$transaction_agm_ar_dividend['transaction_agm_ar_id'] = $transaction_agm_ar_id;
			$transaction_agm_ar_dividend['dividend_name'] = $_POST['dividend_name'][$r];
			$transaction_agm_ar_dividend['dividend_identification_no'] = $_POST['dividend_identification_register_no'][$r];
			$transaction_agm_ar_dividend['dividend_officer_id'] = $_POST['dividend_officer_id'][$r];
			$transaction_agm_ar_dividend['dividend_officer_field_type'] = $_POST['dividend_officer_field_type'][$r];
			$transaction_agm_ar_dividend['dividend_fee'] = str_replace(",", "", $_POST['dividend'][$r]);
			$transaction_agm_ar_dividend['number_of_share'] = $_POST['number_of_share'][$r];

			$this->db->insert("transaction_agm_ar_dividend",$transaction_agm_ar_dividend);
		}

		$transaction_agm_ar_total_dividend['transaction_agm_ar_id'] = $transaction_agm_ar_id;		
		$transaction_agm_ar_total_dividend['total_dividend_declared'] = str_replace(",", "", $_POST['total_dividend']);

		$this->db->delete("transaction_agm_ar_total_dividend",array('transaction_agm_ar_id'=>$transaction_agm_ar_id));

		$this->db->insert("transaction_agm_ar_total_dividend",$transaction_agm_ar_total_dividend);

		$this->db->delete("transaction_agm_ar_amount_due",array('transaction_agm_ar_id'=>$transaction_agm_ar_id));

		for($r = 0; $r < count($_POST['amount_due_name']); $r++)
		{
			$transaction_agm_ar_amount_due['transaction_agm_ar_id'] = $transaction_agm_ar_id;
			$transaction_agm_ar_amount_due['amount_due_from_director_name'] = $_POST['amount_due_name'][$r];
			$transaction_agm_ar_amount_due['amount_due_from_director_identification_no'] = $_POST['amount_due_identification_register_no'][$r];
			$transaction_agm_ar_amount_due['amount_due_from_director_officer_id'] = $_POST['amount_due_officer_id'][$r];
			$transaction_agm_ar_amount_due['amount_due_from_director_officer_field_type'] = $_POST['amount_due_officer_field_type'][$r];
			$transaction_agm_ar_amount_due['amount_due_from_director_fee'] = str_replace(",", "", $_POST['amount_due'][$r]);

			$this->db->insert("transaction_agm_ar_amount_due",$transaction_agm_ar_amount_due);
		}

		$this->db->delete("transaction_agm_ar_director_retire",array('transaction_agm_ar_id'=>$transaction_agm_ar_id));

		for($r = 0; $r < count($_POST['director_retiring_name']); $r++)
		{
			$transaction_agm_ar_director_retire['transaction_agm_ar_id'] = $transaction_agm_ar_id;
			$transaction_agm_ar_director_retire['director_retiring_client_officer_id'] = $_POST['director_retiring_client_officer_id'][$r];
			$transaction_agm_ar_director_retire['director_retire_identification_no'] = $_POST['director_retiring_identification_no'][$r];
			$transaction_agm_ar_director_retire['director_retire_name'] = $_POST['director_retiring_name'][$r];
			$transaction_agm_ar_director_retire['director_retire_officer_id'] = $_POST['director_retiring_officer_id'][$r];
			$transaction_agm_ar_director_retire['director_retire_field_type'] = $_POST['director_retiring_field_type'][$r];
			$transaction_agm_ar_director_retire['director_retiring_checkbox'] = $_POST['hidden_director_retiring_checkbox'][$r];

			$this->db->insert("transaction_agm_ar_director_retire",$transaction_agm_ar_director_retire);
		}

		$this->db->delete("transaction_agm_ar_reappoint_auditor",array('transaction_agm_ar_id'=>$transaction_agm_ar_id));

		for($r = 0; $r < count($_POST['reappointment_auditor_name']); $r++)
		{
			$transaction_agm_ar_reappoint_auditor['transaction_agm_ar_id'] = $transaction_agm_ar_id;
			$transaction_agm_ar_reappoint_auditor['reappoint_auditor_name'] = $_POST['reappointment_auditor_name'][$r];
			$transaction_agm_ar_reappoint_auditor['reappoint_auditor_identification_no'] = $_POST['reappointment_auditor_identification_register_no'][$r];
			$transaction_agm_ar_reappoint_auditor['reappoint_auditor_officer_id'] = $_POST['reappointment_auditor_officer_id'][$r];
			$transaction_agm_ar_reappoint_auditor['reappoint_field_type'] = $_POST['reappointment_auditor_officer_field_type'][$r];

			$this->db->insert("transaction_agm_ar_reappoint_auditor",$transaction_agm_ar_reappoint_auditor);
		}

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_agm_ar_id" => $transaction_agm_ar_id, "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function add_new_company_name()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = strtoupper($_POST['registration_no']);
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = "";
		$transaction['last_edited_by'] = $this->session->userdata('user_id');

		$transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code" => $_POST['transaction_code']));

		if (!$transaction_master_query->num_rows())
		{
			$transaction['created_by'] = $this->session->userdata('user_id');

			$this->db->insert("transaction_master",$transaction);
			$transaction_id = $this->db->insert_id();
			$this->session->set_userdata(array(
                'transaction_id'  => $transaction_id,
            ));
            $transaction_code = $transaction['transaction_code'];


		} 
		else 
		{
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $this->session->userdata('transaction_id')));
			$transaction_id = $this->session->userdata('transaction_id');
			$transaction_code = $_POST["transaction_code"];
		}

		$data['transaction_id']=$this->session->userdata('transaction_id');
		$data['company_name']=$_POST['company_name'];
		$data['new_company_name']= strtoupper($_POST['new_company_name']);
		
		$q = $this->db->get_where("transaction_change_company_name", array("id" => $_POST['transaction_change_company_name_id']));

		if (!$q->num_rows())
		{
			$this->db->insert("transaction_change_company_name",$data);
			$insert_transaction_change_company_name_id = $this->db->insert_id();
			
		} 
		else 
		{
			$this->db->update("transaction_change_company_name",$data,array("id" => $_POST['transaction_change_company_name_id']));
			$insert_transaction_change_company_name_id = $_POST['transaction_change_company_name_id'];
			
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_change_company_name_id" => $insert_transaction_change_company_name_id, "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function add_strike_off()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = strtoupper($_POST['registration_no']);
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = "";
		$transaction['last_edited_by'] = $this->session->userdata('user_id');

		$transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code" => $_POST['transaction_code']));

		if (!$transaction_master_query->num_rows())
		{
			$transaction['created_by'] = $this->session->userdata('user_id');

			$this->db->insert("transaction_master",$transaction);
			$transaction_id = $this->db->insert_id();
			$this->session->set_userdata(array(
                'transaction_id'  => $transaction_id,
            ));
            $transaction_code = $transaction['transaction_code'];
		} 
		else 
		{
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $this->session->userdata('transaction_id')));
			$transaction_id = $this->session->userdata('transaction_id');
			$transaction_code = $_POST["transaction_code"];
		}

		$strike_off['transaction_id'] = $transaction_id;
        $strike_off['registration_no'] = strtoupper($_POST['registration_no']);
        $strike_off['reason_for_application_id'] = $_POST['reason_for_appication'];
        if(isset($_POST['ceased_date']))
		{
			$strike_off['ceased_date'] = $_POST['ceased_date'];
		}
		else
		{	
			$strike_off['ceased_date'] = "";
		}
        //$strike_off['ceased_date'] = $_POST['ceased_date'];

        $transaction_strike_off_query = $this->db->get_where("transaction_strike_off", array("transaction_id" => $transaction_id));

		if (!$transaction_strike_off_query->num_rows())
		{
			$this->db->insert("transaction_strike_off",$strike_off);
			//$transaction_issue_dividend_id = $this->db->insert_id();
		}
		else
		{
			$this->db->update("transaction_strike_off",$strike_off,array("transaction_id" => $transaction_id));
			//$transaction_issue_dividend_id = $_POST["transaction_issue_dividend_id"];
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function add_issue_dividend()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "registration_no" =>strtoupper( $_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = strtoupper($_POST['registration_no']);
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = "";
		$transaction['last_edited_by'] = $this->session->userdata('user_id');

		$transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code" => $_POST['transaction_code']));

		if (!$transaction_master_query->num_rows())
		{
			$transaction['created_by'] = $this->session->userdata('user_id');

			$this->db->insert("transaction_master",$transaction);
			$transaction_id = $this->db->insert_id();
			$this->session->set_userdata(array(
                'transaction_id'  => $transaction_id,
            ));
            $transaction_code = $transaction['transaction_code'];
		} 
		else 
		{
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $this->session->userdata('transaction_id')));
			$transaction_id = $this->session->userdata('transaction_id');
			$transaction_code = $_POST["transaction_code"];
		}

		$issue_dividend['transaction_id'] = $transaction_id;
        $issue_dividend['registration_no'] = strtoupper($_POST['registration_no']);
        $issue_dividend['currency'] = $_POST['currency'];
        $issue_dividend['total_dividend_amount'] = str_replace(',', '', $_POST['total_dividend_amount']);
        $issue_dividend['declare_of_fye'] = $_POST['declare_of_fye'];
        $issue_dividend['devidend_of_cut_off_date'] = $_POST['devidend_of_cut_off_date'];
        $issue_dividend['devidend_payment_date'] = $_POST['devidend_payment_date'];
        $issue_dividend['nature'] = $_POST['nature'];
        $issue_dividend['devidend_per_share'] = $_POST['devidend_per_share'];
        $issue_dividend['total_number_of_share'] = $_POST['total_balance'];
        $issue_dividend['total_devidend_paid'] = $_POST['total_devidend_paid'];

        $transaction_issue_dividend_query = $this->db->get_where("transaction_issue_dividend", array("transaction_id" => $transaction_id));

		if (!$transaction_issue_dividend_query->num_rows())
		{
			$this->db->insert("transaction_issue_dividend",$issue_dividend);
			$transaction_issue_dividend_id = $this->db->insert_id();
		}
		else
		{
			$this->db->update("transaction_issue_dividend",$issue_dividend,array("transaction_id" => $transaction_id));
			$transaction_issue_dividend_id = $_POST["transaction_issue_dividend_id"];
		}

		$this->db->delete("transaction_dividend_list",array('transaction_issue_dividend_id'=>$transaction_issue_dividend_id));

        for($g = 0; $g < count($_POST['shareholder_name']); $g++)
        {
            if($_POST['shareholder_name'][$g] != "")
            {
            	$issue_dividend_list['transaction_issue_dividend_id'] = $transaction_issue_dividend_id;
            	$issue_dividend_list['payment_voucher_no'] = mt_rand(10000, 99999)." / ".date("Y");
            	$issue_dividend_list['officer_id'] = $_POST['officer_id'][$g];
            	$issue_dividend_list['field_type'] = $_POST['field_type'][$g];
            	$issue_dividend_list['shareholder_name'] = $_POST['shareholder_name'][$g];
            	$issue_dividend_list['number_of_share'] = $_POST['balance'][$g];
            	$issue_dividend_list['devidend_paid'] = $_POST['devidend_paid'][$g];

                $this->db->insert('transaction_dividend_list', $issue_dividend_list);
            }
        }


		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code, 'transaction_issue_dividend_id' => $transaction_issue_dividend_id));
	}

	public function add_issue_director_fee()
	{
		//echo json_encode($_POST);
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = strtoupper($_POST['registration_no']);
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = "";
		$transaction['last_edited_by'] = $this->session->userdata('user_id');

		$transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code" => $_POST['transaction_code']));

		if (!$transaction_master_query->num_rows())
		{
			$transaction['created_by'] = $this->session->userdata('user_id');

			$this->db->insert("transaction_master",$transaction);
			$transaction_id = $this->db->insert_id();
			$this->session->set_userdata(array(
                'transaction_id'  => $transaction_id,
            ));
            $transaction_code = $transaction['transaction_code'];
		} 
		else 
		{
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $this->session->userdata('transaction_id')));
			$transaction_id = $this->session->userdata('transaction_id');
			$transaction_code = $_POST["transaction_code"];
		}

		$issue_director_fee['transaction_id'] = $transaction_id;
        $issue_director_fee['registration_no'] = strtoupper($_POST['registration_no']);
        $issue_director_fee['declare_of_fye'] = $_POST['declare_of_fye'];
        $issue_director_fee['resolution_date'] = $_POST['resolution_date'];
        $issue_director_fee['meeting_date'] = $_POST['meeting_date'];
        $issue_director_fee['notice_date'] = $_POST['notice_date'];

        $transaction_issue_director_fee_query = $this->db->get_where("transaction_issue_director_fee", array("transaction_id" => $transaction_id));

		if (!$transaction_issue_director_fee_query->num_rows())
		{
			$this->db->insert("transaction_issue_director_fee",$issue_director_fee);
			$transaction_issue_director_fee_id = $this->db->insert_id();
		}
		else
		{
			$this->db->update("transaction_issue_director_fee",$issue_director_fee,array("transaction_id" => $transaction_id));
			$transaction_issue_director_fee_id = $_POST["transaction_issue_director_fee_id"];
		}

		$this->db->delete("transaction_director_fee_list",array('transaction_issue_director_fee_id'=>$transaction_issue_director_fee_id));

        for($g = 0; $g < count($_POST['officer_id']); $g++)
        {
            if($_POST['officer_id'][$g] != "")
            {
            	$issue_director_fee_list['transaction_issue_director_fee_id'] = $transaction_issue_director_fee_id;
            	$issue_director_fee_list['officer_id'] = $_POST['officer_id'][$g];
            	$issue_director_fee_list['officer_field_type'] = $_POST['officer_field_type'][$g];
            	$issue_director_fee_list['identification_register_no'] = $_POST['identification_register_no'][$g];
                $issue_director_fee_list['director_name'] = $_POST['director_name'][$g];
                $issue_director_fee_list['date_of_appointment'] = $_POST['date_of_appointment'][$g];
                $issue_director_fee_list['currency'] = $_POST['currency'][$g];
                $issue_director_fee_list['director_fee'] = str_replace(',', '', $_POST['director_fee'][$g]);

                $this->db->insert('transaction_director_fee_list', $issue_director_fee_list);
            }
        }


		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code, 'transaction_issue_director_fee_id' => $transaction_issue_director_fee_id));
	}

	public function add_incorp_subsidiary()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = strtoupper($_POST['registration_no']);
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = "";
		$transaction['last_edited_by'] = $this->session->userdata('user_id');

		$transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code" => $_POST['transaction_code']));

		if (!$transaction_master_query->num_rows())
		{
			$transaction['created_by'] = $this->session->userdata('user_id');

			$this->db->insert("transaction_master",$transaction);
			$transaction_id = $this->db->insert_id();
			$this->session->set_userdata(array(
                'transaction_id'  => $transaction_id,
            ));
            $transaction_code = $transaction['transaction_code'];
		} 
		else 
		{
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $this->session->userdata('transaction_id')));
			$transaction_id = $this->session->userdata('transaction_id');
			$transaction_code = $_POST["transaction_code"];
		}

		//$this->db->delete("transaction_corporate_representative",array('registration_no'=>$_POST['registration_no'], 'transaction_id'=>$transaction_id));

        // for($g = 0; $g < count($_POST['subsidiary_name']); $g++)
        // {
        //     if($_POST['subsidiary_name'][$g] != "")
        //     {
        //     	$corp_rep['transaction_id'] = $transaction_id;
        //         $corp_rep['registration_no'] = $_POST['registration_no'];
        //         $corp_rep['subsidiary_name'] = strtoupper($_POST['subsidiary_name'][$g]);
        //         $corp_rep['name_of_corp_rep'] = strtoupper($_POST['corp_rep_name'][$g]);
        //         $corp_rep['identity_number'] = strtoupper($_POST['corp_rep_identity_number'][$g]);
        //         $corp_rep['corp_rep_effective_date'] = $_POST['date_of_appointment'][$g];
        //         $corp_rep['cessation_date'] = $_POST['date_of_cessation'][$g];

        //         $this->db->insert('transaction_corporate_representative', $corp_rep);
        //     }
        // }
        $corp_rep['transaction_id'] = $transaction_id;
        $corp_rep['registration_no'] = strtoupper($_POST['registration_no']);
        $corp_rep['subsidiary_name'] = strtoupper($_POST['subsidiary_name']);

        $corp_rep['country_of_incorporation'] = strtoupper($_POST['country_of_incorporation']);
        $corp_rep['currency'] = strtoupper($_POST['currency']);
        $corp_rep['total_investment_amount'] = str_replace(',', '', $_POST['total_investment_amount']);

        $corp_rep['name_of_corp_rep'] = strtoupper($_POST['corp_rep_name']);
        $corp_rep['identity_number'] = strtoupper($_POST['corp_rep_identity_number']);
        $corp_rep['propose_effective_date'] = $_POST['propose_effective_date'];

        $transaction_corporate_representative_query = $this->db->get_where("transaction_corporate_representative", array("transaction_id" => $transaction_id));

		if (!$transaction_corporate_representative_query->num_rows())
		{
			$this->db->insert("transaction_corporate_representative",$corp_rep);
		}
		else
		{
			$this->db->update("transaction_corporate_representative",$corp_rep,array("transaction_id" => $transaction_id));
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function add_new_regis_office_address()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "registration_no" => strtoupper($_POST['registration_no']), "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = strtoupper($_POST['registration_no']);
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = "";
		$transaction['last_edited_by'] = $this->session->userdata('user_id');

		$transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code" => $_POST['transaction_code']));

		if (!$transaction_master_query->num_rows())
		{
			$transaction['created_by'] = $this->session->userdata('user_id');

			$this->db->insert("transaction_master",$transaction);
			$transaction_id = $this->db->insert_id();
			$this->session->set_userdata(array(
                'transaction_id'  => $transaction_id,
            ));
            $transaction_code = $transaction['transaction_code'];
		} 
		else 
		{
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $this->session->userdata('transaction_id')));
			$transaction_id = $this->session->userdata('transaction_id');
			$transaction_code = $_POST["transaction_code"];
		}

		$data['transaction_id']=$this->session->userdata('transaction_id');
		$data['company_name']=$_POST['company_name'];
		$data['old_registration_address']=$_POST['old_registration_address'];
		$data['registered_address'] = (isset($_POST['use_registered_address'])) ? 1 : 0;
		$data['our_service_regis_address_id']=$_POST['service_reg_off'];
		$data['postal_code']=$_POST['postal_code'];
		$data['postal_code']=$_POST['postal_code'];
		$data['street_name']=$_POST['street_name'];
		$data['building_name']=$_POST['building_name'];
		$data['unit_no1']=$_POST['unit_no1'];
		$data['unit_no2']=$_POST['unit_no2'];
		
		$q = $this->db->get_where("transaction_change_regis_ofis_address", array("id" => $_POST['transaction_change_regis_ofis_address_id']));

		if (!$q->num_rows())
		{
			$this->db->insert("transaction_change_regis_ofis_address",$data);
			$insert_transaction_change_regis_ofis_address_id = $this->db->insert_id();
			
		} 
		else 
		{
			$this->db->update("transaction_change_regis_ofis_address",$data,array("id" => $_POST['transaction_change_regis_ofis_address_id']));
			$insert_transaction_change_regis_ofis_address_id = $_POST['transaction_change_regis_ofis_address_id'];
			
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_change_regis_ofis_address_id" => $insert_transaction_change_regis_ofis_address_id, "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}
	
	public function cancel_transaction_by_user()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code" => $_POST['transaction_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_user["status"] = 5;
			$cancel_by_user["remarks"] = $_POST['cancel_reason'];
			$this->db->update("transaction_master",$cancel_by_user,array("transaction_code" => $_POST['transaction_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$this->db->delete("transaction_pending_documents",array('transaction_id'=>$this->session->userdata('transaction_id')));
		$this->db->delete("transaction_pending_documents_file",array('transaction_id'=>$this->session->userdata('transaction_id')));

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
	}
}

			
