<?php defined('BASEPATH') OR exit('No direct script access allowed');

use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Customer;

class Transaction extends MY_Controller
{
    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        $this->load->library(array('form_validation', 'encryption', 'session'));
        $this->load->model(array('transaction_model', 'master_model', 'db_model', 'transaction_word_model', 'document_model', 'quickbook_auth_model'));
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

        if($this->data['transaction_master'][0]->transaction_task_id == "29" || $this->data['transaction_master'][0]->transaction_task_id == "30" || $this->data['transaction_master'][0]->transaction_task_id == "35")
        {
        	$this->session->set_userdata(array(
	            'transaction_company_code'  => $this->data['transaction_master'][0]->company_code,
	        ));

        	$this->mybreadcrumb->add('Edit Services - '.$this->data['transaction_master'][0]->client_name.'', base_url());
        }
        else if($this->data['transaction_master'][0]->transaction_task_id == "36")
        {
        	$this->mybreadcrumb->add('Edit Services', base_url());
        }
        else
        {
        	$this->session->set_userdata(array(
	            'transaction_company_code'  => $this->data['transaction_client'][0]->company_code,
	        ));

			$this->mybreadcrumb->add('Edit Services - '.$this->data['transaction_client'][0]->company_name.'', base_url());
        }
		$this->data['breadcrumbs'] = $this->mybreadcrumb->render();
		$encode_client_name = utf8_encode($this->data['transaction_master'][0]->client_name);
		$this->data['transaction_master'][0]->client_name = $encode_client_name;

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
		$ci =& get_instance();
		
		$result = $this->db->query('select client.id, client.company_code, client.company_name from client left join user_firm on user_id = "'.$ci->session->userdata("user_id").'" where deleted = 0 and user_firm.firm_id = client.firm_id');

		$result = $result->result_array();

		if(!$result) {
			throw new exception("Client not found.");
		}

		$res = array();
		$master_client_name_info = array();
		foreach($result as $row) {
			$row['company_name'] = $this->encryption->decrypt($row['company_name']);
			array_push($master_client_name_info, trim($row['company_name']));
			$res[$row['company_code']] = $row['company_name'];
		}

		$trans_master_result = $this->db->query('select transaction_master.* from transaction_master left join transaction_service_proposal_info on transaction_master.id = transaction_service_proposal_info.transaction_id where transaction_master.service_status = 3 and transaction_master.transaction_task_id = 29 and transaction_service_proposal_info.potential_client = 1');

		$trans_master_result = $trans_master_result->result_array();

		foreach($trans_master_result as $row) {
			$row['client_name'] = $this->encryption->decrypt($row['client_name']);

			if(!(in_array(trim($row['client_name']), $master_client_name_info))) {
				$res[$row['company_code']] = $row['client_name'].' (Potential Client)';
			}
		}

		$data = array('status'=>'success', 'tp'=>1, 'msg'=>"Client fetched successfully.", 'result'=>$res);

	    echo json_encode($data);
	}

	public function get_all_client()
	{
		$ci =& get_instance();

		$result = $this->db->query('select client.id, client.company_code, client.company_name from client left join user_firm on user_id = "'.$ci->session->userdata("user_id").'" where deleted = 0 and user_firm.firm_id = client.firm_id');

		$result = $result->result_array();

		if(!$result) {
			throw new exception("Client not found.");
		}
		$res = array();
		foreach($result as $row) {
			$res[$row['id']] = $this->encryption->decrypt($row['company_name']);
		}

		$data = array('status'=>'success', 'tp'=>1, 'msg'=>"Client fetched successfully.", 'result'=>$res);

	    echo json_encode($data);
	}

	public function get_client_type()
	{
		$result = $this->db->query("select * from client_type");

		$result = $result->result_array();

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
		$transaction_task_id = $_POST["transaction_task_id"];

		if($transaction_task_id == null)
		{
			$delete_query = " AND deleted = 0";
		}
		else
		{
			$delete_query = "";
		}

		if($_SESSION['group_id'] != 4)
		{
			$result = $this->db->query("select * from transaction_tasks where id != 13 && id != 14 && id != 16 && id != 17 && id != 18 && id != 19 && id != 21 && id != 22 && id != 23 && id != 25".$delete_query);
		}
		else
		{
			$result = $this->db->query("select * from transaction_tasks where id != 1 && id != 13 && id != 14 && id != 16 && id != 17 && id != 18 && id != 19 && id != 21 && id != 22 && id != 23 && id != 25".$delete_query);
		}

		$result = $result->result_array();

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

		$this->db->select('id, registration_no, company_name');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();
		$transaction_client[0]["registration_no"] = $this->encryption->decrypt($transaction_client[0]["registration_no"]);
		$transaction_client[0]["company_name"] = $this->encryption->decrypt($transaction_client[0]["company_name"]);

		$this->data['transaction_client'] = $transaction_client;

		$this->data['transaction_meeting_date'] = $this->transaction_model->getTransactionMeetingDate($transaction_master_id);

		$this->data['transaction_member'] = $this->transaction_model->getTransactionClientMember($transaction_master_id, $_POST["company_code"]);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_share_allotment.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function add_lodgement_info()
	{
		$transaction_master_id = $_POST['transaction_master_id'];
		$transaction_task_name = $_POST['transaction_task_name'];
		$company_code = $_POST['company_code'];
		$transaction_code = $this->getTransactionCode();
		$effective_date = $_POST['effective_date'];
		$lodgement_date = $_POST['lodgement_date'];
		$client_code = strtoupper($_POST['client_code']);
		$registration_no = $this->encryption->encrypt(trim(strtoupper($_POST['registration_no'])));
		$transaction_task = $_POST['transaction_task'];
		$transaction_status = $_POST['tran_status'];
		$cancellation_reason = $_POST['cancellation_reason'];
		//Controller
		$radio_confirm_registrable_controller = $_POST['radio_confirm_registrable_controller'];
		$date_of_the_conf_received = $_POST['date_of_the_conf_received'];
		$date_of_entry_or_update = $_POST['date_of_entry_or_update'];

		if($transaction_task == 29)
		{
			if($transaction_status == 3)
			{
				$master["status"] = 2;
				$master["lodgement_date"] = $lodgement_date;
				$master["service_status"] = $transaction_status;
				$master["remarks"] = $cancellation_reason;
			}
			else if($transaction_status == 4)
			{
				$master["status"] = 5;
				$master["lodgement_date"] = $lodgement_date;
				$master["service_status"] = $transaction_status;
				$master["remarks"] = $cancellation_reason;

				$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
				$this->db->update("transaction_master",$edit_transaction,array("id" =>  $_POST['transaction_master_id']));
			}
			else if($transaction_status == 5)
			{
				$master["status"] = 7;
				$master["lodgement_date"] = $lodgement_date;
				$master["service_status"] = $transaction_status;
				$master["remarks"] = $cancellation_reason;
			}
			else
			{
				$master["status"] = 1;
				$master["lodgement_date"] = $lodgement_date;
				$master["service_status"] = $transaction_status;
				$master["remarks"] = $cancellation_reason;
			}
			$this->db->update("transaction_master", $master,array("id" => $_POST['transaction_master_id']));
		
		}
		else if($transaction_task == 1 || $transaction_task == 2 || $transaction_task == 3 || $transaction_task == 4 || $transaction_task == 5 || $transaction_task == 6 || $transaction_task == 7 || $transaction_task == 8 || $transaction_task == 9 || $transaction_task == 10 || $transaction_task == 11 || $transaction_task == 12  || $transaction_task == 15 || $transaction_task == 24 || $transaction_task == 26 || $transaction_task == 30 || $transaction_task == 31 || $transaction_task == 32 || $transaction_task == 33 || $transaction_task == 34 || $transaction_task == 35)
		{
			if($transaction_status == 2)
			{
				if($transaction_task == 30)
				{
					$master["status"] = 2;
					$master["lodgement_date"] = $lodgement_date;
					$master["service_status"] = $transaction_status;
					$master["remarks"] = $cancellation_reason;
				}
				else
				{
					$master["status"] = 3;
					if($transaction_task == 31)
					{
						$master["radio_confirm_registrable_controller"] = $radio_confirm_registrable_controller;
			    		$master["date_of_the_conf_received"] = $date_of_the_conf_received;
			    		$master["date_of_entry_or_update"] = $date_of_entry_or_update;
					}
					else if($transaction_task == 32)
					{
			    		$master["date_of_entry_or_update"] = $date_of_entry_or_update;
					}
					else
					{
						$master["lodgement_date"] = $lodgement_date;
					}
					$master["service_status"] = $transaction_status;
					$master["remarks"] = $cancellation_reason;
				}
			}
			else if($transaction_status == 3)
			{
				$master["status"] = 5;
				$master["lodgement_date"] = $lodgement_date;
				$master["service_status"] = $transaction_status;
				$master["remarks"] = $cancellation_reason;

				$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
				$this->db->update("transaction_master",$edit_transaction,array("id" => $_POST['transaction_master_id']));

				if($transaction_task == 30)
				{
					$transaction_engagement_letter_info_query = $this->db->get_where("transaction_engagement_letter_info", array("transaction_id" => $_POST['transaction_master_id'], "deleted" => '0'));

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
			else if($transaction_status == 4)
			{
				$master["status"] = 7;
				$master["lodgement_date"] = $lodgement_date;
				$master["service_status"] = $transaction_status;
				$master["remarks"] = $cancellation_reason;
			}
			else
			{
				$master["status"] = 1;
				$master["lodgement_date"] = $lodgement_date;
				$master["service_status"] = $transaction_status;
				$master["remarks"] = $cancellation_reason;
			}
			if($transaction_task == 1)
			{
				$master["registration_no"] = $registration_no;
			}
			$this->db->update("transaction_master", $master,array("id" => $_POST['transaction_master_id']));
		}
		else
		{
			$master["status"] = 3;
			if($transaction_task == 28)
			{
				$master["registration_no"] = $registration_no;
			}
			$master["lodgement_date"] = $lodgement_date;
			$this->db->update("transaction_master", $master,array("id" => $_POST['transaction_master_id']));
		}

		$this->save_audit_trail("Services", $transaction_task_name, "Logdement info is updated.");

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
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
			$this->save_audit_trail("Services", $_POST['transaction_task_name'], "Follow up is added.");
		} 
		else 
		{
			$this->db->where(array("company_code" => $_POST['company_code'], "id" => $_POST['follow_up_history_id']));
			$this->db->update("follow_up_history",$follow);
			$this->save_audit_trail("Services", $_POST['transaction_task_name'], "Follow up is edited.");
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

		$this->save_audit_trail("Services", $_POST['transaction_task_name'], "Follow up is deleted.");

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

		$this->db->select('effective_date, service_status');
        $this->db->from('transaction_master');
        $this->db->where('id', $transaction_master_id);
		$transaction_master = $this->db->get();
		$transaction_master = $transaction_master->result_array();

		$this->data['effective_date'] = $transaction_master[0]['effective_date'];
		$this->data['service_status'] = $transaction_master[0]['service_status'];

		$this->db->select('client_member_share_capital_id');
        $this->db->from('transaction_member_shares');
        $this->db->where('transaction_page_id', $transaction_master_id);
		$transaction_member_shares = $this->db->get();
		$transaction_member_shares = $transaction_member_shares->result_array();

		//-------------------------------------TransactionShareTransfer-----------------------------------
		$this->data['last_cert_no'] = $this->transaction_model->get_last_cert_no($transaction_company_code, $transaction_member_shares[0]['client_member_share_capital_id']);

		$this->data['share_number_for_cert_record'] = $this->transaction_model->getLatestShareNumberForCertRecord($transaction_company_code, $transaction_member_shares[0]['client_member_share_capital_id'], $transaction_master_id);

    	$this->data['latest_share_number_for_cert'] = $this->transaction_model->getLatestShareNumberForCert($transaction_company_code, $transaction_member_shares[0]['client_member_share_capital_id'], $transaction_master_id);

    	$this->data['transaction_share_transfer_record'] = $this->transaction_model->getTransactionSharetransferRecord($transaction_master_id);

    	//-------------------------------------TransactionShareTransfer-----------------------------------

		$this->data['transaction_member'] = $this->transaction_model->getTransactionClientTransferMemberInfo($transaction_master_id);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$this->data['follow_up_history'] = $this->transaction_model->getFollowUpHistory($transaction_master_id, $_POST["company_code"]);

		$this->data['follow_up_outcome'] = $this->transaction_model->get_follow_up_outcome();

		$this->data['follow_up_action'] = $this->transaction_model->get_follow_up_action();

		$this->data['transaction_service_status'] = $this->transaction_model->get_transaction_service_status();

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

	public function get_status_and_follow_up_detail()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];

		$this->db->select('effective_date, lodgement_date, service_status, date_of_the_conf_received, date_of_entry_or_update');
        $this->db->from('transaction_master');
        $this->db->where('id', $transaction_master_id);
		$transaction_master = $this->db->get();
		$transaction_master = $transaction_master->result_array();

		if(count($transaction_master) > 0)
		{
			$this->data['effective_date'] = $transaction_master[0]['effective_date'];
			$this->data['lodgement_date'] = $transaction_master[0]['lodgement_date'];
			$this->data['service_status'] = $transaction_master[0]['service_status'];
			$this->data['date_of_the_conf_received'] = $transaction_master[0]['date_of_the_conf_received'];
			$this->data['date_of_entry_or_update'] = $transaction_master[0]['date_of_entry_or_update'];
		}
		else
		{
			$this->data['effective_date'] = '';
			$this->data['lodgement_date'] = '';
			$this->data['service_status'] = '';
			$this->data['date_of_the_conf_received'] = null;
			$this->data['date_of_entry_or_update'] = null;
		}

		$this->data['transaction_client'] = $this->transaction_model->getTransactionClient($transaction_master_id, $transaction_company_code);

		$this->data['follow_up_history'] = $this->transaction_model->getFollowUpHistory($transaction_master_id, $_POST["company_code"]);
		$this->data['follow_up_outcome'] = $this->transaction_model->get_follow_up_outcome();

		$this->data['follow_up_action'] = $this->transaction_model->get_follow_up_action();

		if($transaction_master_id != "" && $transaction_master_id != null)
		{
			$this->data['all_documents'] = $this->document_model->get_all_document($_SESSION['group_id'], NULL, NULL, NULL, NULL, $transaction_master_id);
		}
		else
		{
			$this->data['all_documents'] = false;
		}

		if($transaction_task_id == 1)
		{
			if(isset($this->data['transaction_client'][0]->company_name))
			{
	        	$this->data['latest_client_code'] = $this->transaction_model->detect_client_code($this->data['transaction_client'][0]->company_name);
			}
			else
			{
				$this->data['latest_client_code'] = '';
			}
	    }

		if($transaction_task_id == 29)
		{
			$this->data['transaction_service_proposal_status'] = $this->transaction_model->get_transaction_service_proposal_status();
		}
		else if($transaction_task_id == 30)
		{
			$this->data['transaction_engagement_letter_status'] = $this->transaction_model->get_transaction_engagement_letter_status();
		}
		else
		{
			$this->data['transaction_service_status'] = $this->transaction_model->get_transaction_service_status();
		}
		echo json_encode(array($this->data));
	}

	public function get_upload_document_list()
	{
		$transaction_master_id = $_POST["transaction_master_id"];

		if($transaction_master_id != "" && $transaction_master_id != null)
		{
			$this->data['all_documents'] = $this->document_model->get_all_document($_SESSION['group_id'], NULL, NULL, NULL, NULL, $transaction_master_id);
		}
		else
		{
			$this->data['all_documents'] = false;
		}
		
		echo json_encode(array($this->data));
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

		$this->db->select('id, registration_no, company_name');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();
		$transaction_client[0]["registration_no"] = $this->encryption->decrypt($transaction_client[0]["registration_no"]);
		$transaction_client[0]["company_name"] = $this->encryption->decrypt($transaction_client[0]["company_name"]);

		$this->data['transaction_client'] = $transaction_client;

		$this->data['transaction_client_officers'] = $this->transaction_model->getTransactionResignClientOfficer($transaction_master_id);

		$this->data['transaction_resignation_of_company_secretary'] = $this->transaction_model->getTransactionResignOfCompanySecretary($transaction_master_id);

		$this->data['transaction_billing'] = $this->transaction_model->getTransactionClientBilling($transaction_master_id, $transaction_company_code);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_appoint_new_secretarial.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_all_appoint_new_director_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id, registration_no, company_name');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();
		$transaction_client[0]["registration_no"] = $this->encryption->decrypt($transaction_client[0]["registration_no"]);
		$transaction_client[0]["company_name"] = $this->encryption->decrypt($transaction_client[0]["company_name"]);

		$this->data['transaction_client'] = $transaction_client;
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
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['transaction_meeting_date'] = $this->transaction_model->getTransactionAuditorMeetingDate($transaction_master_id);

		$this->data['transaction_client_officers'] = $this->transaction_model->getTransactionResignClientOfficer($transaction_master_id);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_appt_resign_auditor.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_all_resign_director_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id, registration_no, company_name');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();
		$transaction_client[0]["registration_no"] = $this->encryption->decrypt($transaction_client[0]["registration_no"]);
		$transaction_client[0]["company_name"] = $this->encryption->decrypt($transaction_client[0]["company_name"]);

		$this->data['transaction_client'] = $transaction_client;

		$this->data['transaction_client_officers'] = $this->transaction_model->getTransactionResignClientOfficer($transaction_master_id);
		$this->data['transaction_billing'] = $this->transaction_model->getTransactionClientBilling($transaction_master_id, $transaction_company_code);
		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$this->data['get_latest_client_nominee_director_data'] = $this->transaction_model->getLatestClientNomineeDirector($transaction_company_code, $transaction_master_id);

		$interface = $this->load->view('/views/transaction/confirmation_resign_director.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_change_regis_office_address_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id, registration_no, company_name');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();
		$transaction_client[0]["registration_no"] = $this->encryption->decrypt($transaction_client[0]["registration_no"]);
		$transaction_client[0]["company_name"] = $this->encryption->decrypt($transaction_client[0]["company_name"]);

		$this->data['transaction_client'] = $transaction_client;

		$this->data['transaction_change_regis_ofis_address'] = $this->transaction_model->getTransactionChangeRegOfisAddress($transaction_master_id);

		$this->data['transaction_billing'] = $this->transaction_model->getTransactionClientBilling($transaction_master_id, $transaction_company_code);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_change_reg_ofis_address.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_change_FYE_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id, registration_no, company_name');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();
		$transaction_client[0]["registration_no"] = $this->encryption->decrypt($transaction_client[0]["registration_no"]);
		$transaction_client[0]["company_name"] = $this->encryption->decrypt($transaction_client[0]["company_name"]);

		$this->data['transaction_client'] = $transaction_client;

		$this->data['transaction_change_FYE'] = $this->transaction_model->getTransactionChangeFYE($transaction_master_id);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_change_FYE.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_change_biz_activity_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id, registration_no, company_name');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();
		$transaction_client[0]["registration_no"] = $this->encryption->decrypt($transaction_client[0]["registration_no"]);
		$transaction_client[0]["company_name"] = $this->encryption->decrypt($transaction_client[0]["company_name"]);

		$this->data['transaction_client'] = $transaction_client;

		$this->data['transaction_change_biz_activity'] = $this->transaction_model->getTransactionChangeBizActivity($transaction_master_id);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_change_biz_activity.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_issue_director_fee_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id, company_name');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['client_name'] = $this->encryption->decrypt($transaction_client[0]["company_name"]);

		$this->data['transaction_issue_director_fee'] = $this->transaction_model->getTransactionIssueDirectorFee($transaction_master_id);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$this->data['transaction_meeting_date'] = $this->transaction_model->getTransactionMeetingDate($transaction_master_id);

		$interface = $this->load->view('/views/transaction/confirmation_issue_director_fee.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_issue_dividend_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id, company_name');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['client_name'] = $this->encryption->decrypt($transaction_client[0]["company_name"]);

		$this->data['transaction_issue_dividend'] = $this->transaction_model->getTransactionIssueDividend($transaction_master_id);

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$this->data['transaction_meeting_date'] = $this->transaction_model->getTransactionMeetingDate($transaction_master_id);

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

		$this->data['transaction_engagement_letter_additional_info'] = $this->transaction_model->getTransactionEngagementLetterAdditionalInfo($transaction_master_id);

		$this->data['transaction_engagement_letter_service_info'] = $this->transaction_model->getTransactionEngagementLetter($transaction_master_id);

		$this->data['transaction_engagement_letter_list'] = $this->transaction_model->getTransactionEngagementLetterList();

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$this->data['transaction_engagement_letter_status'] = $this->transaction_model->get_transaction_engagement_letter_status();

		$interface = $this->load->view('/views/transaction/confirmation_engagement_letter.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_conf_register_nominee_director_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['conf_get_current_client_nominee_director_data'] = $this->transaction_model->getCurrentClientNomineeDirector($transaction_company_code, $transaction_master_id);

		$this->data['conf_get_latest_client_nominee_director_data'] = $this->transaction_model->getLatestClientNomineeDirector($transaction_company_code, $transaction_master_id);

		$interface = $this->load->view('/views/transaction/confirmation_register_nominee_director.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_conf_register_controller_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['conf_get_current_client_controller_data'] = $this->transaction_model->getCurrentClientController($transaction_company_code, $transaction_master_id);

		$this->data['conf_get_latest_client_controller_data'] = $this->transaction_model->getLatestClientController($transaction_company_code, $transaction_master_id);

		$interface = $this->load->view('/views/transaction/confirmation_register_controller.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_omp_grant_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['transaction_omp_grant'] = $this->transaction_model->getTransactionOMPGrant($transaction_master_id);

		if(count($transaction_client) > 0)
		{
			$client_id = $transaction_client[0]["id"];
		}
		else
		{
			$client_id = NULL;
		}

		$interface = $this->load->view('/views/transaction/confirmation_omp_grant.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_service_proposal_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['transaction_service_proposal'] = $this->transaction_model->getTransactionServiceProposal($transaction_master_id);

		$this->data['transaction_service_proposal_contact_person'] = $this->transaction_model->getTransactionClientContactInfo($transaction_master_id, $transaction_company_code);

		$this->data['transaction_service_proposal_service_info'] = $this->transaction_model->getTransactionServiceProposalServiceInfo($transaction_master_id);

		$this->data['transaction_service_proposal_sub_service_info'] = $this->transaction_model->getTransactionServiceProposalSubServiceInfo($transaction_master_id);

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

		$this->data['transaction_pending_documents'] = $this->transaction_model->get_all_pending_doc($transaction_master_id, $transaction_client[0]['id']);

		$interface = $this->load->view('/views/transaction/confirmation_strike_off.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_change_company_name_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_company_code = $_POST["company_code"];

		$this->db->select('id, registration_no, company_name');
        $this->db->from('client');
        $this->db->where('company_code', $transaction_company_code);
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();
		$transaction_client[0]["registration_no"] = $this->encryption->decrypt($transaction_client[0]["registration_no"]);
		$transaction_client[0]["company_name"] = $this->encryption->decrypt($transaction_client[0]["company_name"]);

		$this->data['transaction_client'] = $transaction_client;

		$this->data['transaction_meeting_date'] = $this->transaction_model->getTransactionMeetingDate($transaction_master_id);

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
        $this->db->where('deleted = 0');
		$transaction_client = $this->db->get();
		$transaction_client = $transaction_client->result_array();

		$this->data['transaction_agm_ar'] = $this->transaction_model->getTransactionAgmAr($transaction_master_id, $transaction_company_code);

		$this->data['transaction_agm_ar_director_fee'] = $this->transaction_model->getTransactionDirectorFee($transaction_master_id, $transaction_company_code);

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
		$audited_fs = isset($_POST["audited_fs"])?$_POST["audited_fs"]:null;
		$activity_status = isset($_POST["activity_status"])?$_POST["activity_status"]:null;
		$shorter_notice = isset($_POST["shorter_notice"])?$_POST["shorter_notice"]:'';
		$require_hold_agm_list = isset($_POST["require_hold_agm_list"])?$_POST["require_hold_agm_list"]:null;
		$transaction_master_id = null;

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
		}

		$this->data['document'] = $this->transaction_model->get_all_document($transaction_task_id, $transaction_company_code, $second_transaction_task_id, $hidden_selected_el_id, $transaction_master_id, $audited_fs, $activity_status, $shorter_notice, $require_hold_agm_list);

		$this->data['document_categoty_list'] = $this->transaction_model->get_all_document_category_list();

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
			$this->data['transaction_filing'] = $this->transaction_model->getTransactionClientFiling($transaction_id, $company_code);
			$this->data['transaction_billing'] = $this->transaction_model->getTransactionClientBilling($transaction_id, $company_code);
			$this->data['transaction_previous_secretarial'] = $this->transaction_model->getTransactionPreviousSecretarial($transaction_id, $company_code);

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
		
        $this->db->select('our_service_registration_address.id, our_service_registration_address.our_service_info_id, our_service_info.service_name, our_service_registration_address.postal_code, our_service_registration_address.street_name, our_service_registration_address.building_name, our_service_registration_address.unit_no1, our_service_registration_address.unit_no2')
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

		$q = $this->db->query('select * from client where deleted = 0');// and firm_id = "'.$this->session->userdata('firm_id').'" // registration_no = "'.$registra_no.'" and

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();

        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registra_no)
	            {
	            	$client_info_row["registration_no"] = $this->encryption->decrypt($client_info_row["registration_no"]);
	            	$client_info_row["company_name"] = $this->encryption->decrypt($client_info_row["company_name"]);
	            	$this->data["transaction_client"] = array($client_info_row);
	                $client_info_data = $client_info_row;
	            }
	        }
	        if($client_info_data != null)
	        {
	        	if($transaction_id != null)
				{
					$this->data['transaction_appoint_new_secretarial'] = $this->transaction_model->getTransactionResignClientOfficer($transaction_id);

					$this->data['transaction_resignation_of_company_secretary'] = $this->transaction_model->getTransactionResignOfCompanySecretary($transaction_id);

					$this->data['transaction_billing'] = $this->transaction_model->getTransactionClientBilling($transaction_id, $company_code);

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

		$q = $this->db->query('select * from client where deleted = 0');//and firm_id = "'.$this->session->userdata('firm_id').'" //registration_no = "'.$registra_no.'" and 

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registra_no)
	            {
	                $client_info_data = $client_info_row;
	            }
	        }
	        if($client_info_data != null)
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

		$q = $this->db->query('select * from client where deleted = 0');// and firm_id = "'.$this->session->userdata('firm_id').'" // registration_no = "'.$registra_no.'" and

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registra_no)
	            {
	                $client_company_code = array($client_info_row);
	            }
	        }
	        if($client_company_code != null)
	        {
				if($transaction_id != null)
				{
					$this->data['transaction_meeting_date'] = $this->transaction_model->getTransactionAuditorMeetingDate($transaction_id);

					$this->data['transaction_appoint_new_auditor'] = $this->transaction_model->getTransactionResignClientOfficer($transaction_id);

					$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
				}

				$this->data['company_class'] = $this->master_model->get_all_company_share_type($client_company_code[0]["company_code"]);

				$this->data['postal_code'] = $client_company_code[0]["postal_code"];
				$this->data['street_name'] = $client_company_code[0]["street_name"];
				$this->data['building_name'] = $client_company_code[0]["building_name"];
				$this->data['unit_no1'] = $client_company_code[0]["unit_no1"];
				$this->data['unit_no2'] = $client_company_code[0]["unit_no2"];

				$interface = $this->load->view('/views/transaction/appoint_new_auditor.php', '', TRUE);

				echo json_encode(array("interface" => $interface, $this->data));
			}
	        else
			{
				echo json_encode(array("error" => "Please enter correct registration number."));
			}
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
		
		$q = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" and client.deleted = 0 where member_shares.company_code="'.$company_code.'" AND member_shares.client_member_share_capital_id = "'.$client_member_share_capital_id.'" GROUP BY member_shares.field_type, member_shares.officer_id HAVING sum(member_shares.number_of_share) != 0 AND member_shares.cert_status = 1'); // and client.firm_id = "'.$this->session->userdata('firm_id').'"

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
            	if($row->field_type == "individual")
                {
                    $row->identification_no = $this->encryption->decrypt($row->identification_no);
                    $row->name = $this->encryption->decrypt($row->name);
                }
                elseif($row->field_type == "company")
                {
                    $row->register_no = $this->encryption->decrypt($row->register_no);
                    $row->company_name = $this->encryption->decrypt($row->company_name);
                }
                else
                {
                    $row->registration_no = $this->encryption->decrypt($row->registration_no);
                    $row->client_company_name = $this->encryption->decrypt($row->client_company_name);
                }
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

		$q = $this->db->query('select * from client where deleted = 0'); // and firm_id = "'.$this->session->userdata('firm_id').'" // registration_no = "'.$registra_no.'" and

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registra_no)
	            {
	                $client_company_code = $client_info_row["company_code"];
	            }
	        }
	        if($client_company_code != null)
	        {
	        	// $client_company_code =  $this->db->query("select company_code, postal_code, street_name, building_name, unit_no1, unit_no2 from client where registration_no='".$registra_no."' and firm_id = '".$this->session->userdata('firm_id')."' and deleted = 0");

		        // $client_company_code = $client_company_code->result_array();
		        
				if($transaction_id != null)
				{
					//$this->data['transaction_meeting_date'] = $this->transaction_model->getTransactionMeetingDate($transaction_id);

					$this->data['transaction_share_transfer'] = $this->transaction_model->getTransactionClientTransferMemberInfo($transaction_id);

					$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
				}
				$this->data['company_class'] = $this->master_model->get_all_company_share_type($client_company_code);

				$interface = $this->load->view('/views/transaction/share_transfer.php', '', TRUE);

				echo json_encode(array("interface" => $interface, $this->data));
			}
	        else
			{
				echo json_encode(array("error" => "Please enter correct registration number."));
			}
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

		$q = $this->db->query('select * from client where deleted = 0'); // and firm_id = "'.$this->session->userdata('firm_id').'" // registration_no = "'.$registra_no.'" and

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registra_no)
	            {
	                $client_company_code = array($client_info_row);
	            }
	        }
	        if($client_company_code != null)
	        {
	        	// $client_company_code =  $this->db->query("select company_code, postal_code, street_name, building_name, unit_no1, unit_no2 from client where registration_no='".$registra_no."' and firm_id = '".$this->session->userdata('firm_id')."' and deleted = 0");

		        // $client_company_code = $client_company_code->result_array();

				if($transaction_id != null)
				{
					$this->data['transaction_meeting_date'] = $this->transaction_model->getTransactionMeetingDate($transaction_id);

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

		// $q = $this->db->query('select * from client left join user_firm on user_firm.firm_id = client.firm_id where client.deleted = 0 and user_firm.user_id = "'.$this->session->userdata('user_id').'" order by client.id'); // and firm_id = "'.$this->session->userdata('firm_id').'" // where registration_no = "'.$registra_no.'" and
		$q = $this->db->query('select * from client where client.deleted = 0 order by client.id');
		
		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registra_no)
	            {
	                $client_company_code = array($client_info_row);
	                //break;
	            }
	        }
	        if($client_company_code != null)
	        {
	        	//$client_info = $q->result_array();

	        	$member_info = $this->db->query('select member_shares.*, LENGTH(sum(member_shares.number_of_share)) AS LengthOfMemberShare, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer.field_type as officer_field_type, officer_company.field_type as officer_company_field_type, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" where member_shares.company_code="'.$client_company_code[0]['company_code'].'" GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id HAVING sum(member_shares.number_of_share) != 0');

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

	        	// $client_company_code =  $this->db->query("select company_code, postal_code, street_name, building_name, unit_no1, unit_no2 from client where registration_no='".$registra_no."' and firm_id = '".$this->session->userdata('firm_id')."' and deleted = 0");

		        // $client_company_code = $client_company_code->result_array();
				$this->data['client_postal_code'] = $client_company_code[0]["postal_code"];
				$this->data['client_street_name'] = $client_company_code[0]["street_name"];
				$this->data['client_building_name'] = $client_company_code[0]["building_name"];
				$this->data['client_unit_no1'] = $client_company_code[0]["unit_no1"];
				$this->data['client_unit_no2'] = $client_company_code[0]["unit_no2"];

		        if($transaction_id != null)
				{
					$this->data['transaction_agm_ar'] = $this->transaction_model->getTransactionAgmAr($transaction_id, $company_code);

					$this->data['transaction_agm_ar_director_fee'] = $this->transaction_model->getTransactionDirectorFee($transaction_id, $company_code);

					$this->data['currency'] = $this->transaction_model->get_currency_list();

					//$this->data['transaction_agm_ar_dividend'] = $this->transaction_model->getTransactionDividend($transaction_id, $company_code);

					$this->data['transaction_agm_ar_amount_due'] = $this->transaction_model->getTransactionAmountDue($transaction_id, $company_code);

					$this->data['transaction_agm_ar_director_retire'] = $this->transaction_model->getTransactionDirectorRetire($transaction_id, $company_code);

					$this->data['transaction_agm_ar_reappoint_auditor'] = $this->transaction_model->getTransactionReappointAuditor($transaction_id, $company_code);

					$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
				}
				else
				{
					$query_previous_agm_ar = $this->db->query("SELECT transaction_master.* FROM transaction_master where id = (SELECT max(id) FROM transaction_master where (status = '1' or status = '2' or status = '3') and transaction_task_id = '15' and company_code = '".$client_company_code[0]['company_code']."')");

			        if ($query_previous_agm_ar->num_rows() > 0) 
			        {
			        	$query_previous_agm_ar = $query_previous_agm_ar->result_array();

			        	$this->data['previous_transaction_agm_ar'] = $this->transaction_model->getPreviousTransactionAgmAr($query_previous_agm_ar[0]["id"], $query_previous_agm_ar[0]["company_code"]);
			        }
				}
				$this->data['exemption'] = $this->transaction_model->get_exemption();

				$this->data['regis_controller_is_kept'] = $this->transaction_model->get_regis_controller_is_kept();

				$this->data['regis_nominee_dir_is_kept'] = $this->transaction_model->get_regis_nominee_dir_is_kept();

				$this->data['client_signing_info'] = $this->transaction_model->get_all_client_signing_info($client_company_code[0]['company_code']);

				$this->data['company_type'] = $this->transaction_model->get_company_type();

				$this->data['first_agm'] = $this->transaction_model->get_all_first_agm();

				$this->data['xbrl_list'] = $this->transaction_model->get_xbrl_list();

				$this->data['agm_share_transfer'] = $this->transaction_model->get_all_agm_share_transfer();

				$this->data['consent_for_shorter_notice'] = $this->transaction_model->get_all_consent_for_shorter_notice();

				$this->data['activity_status'] = $this->transaction_model->get_all_activity_status();

				$this->data['solvency_status'] = $this->transaction_model->get_all_solvency_status();

				$this->data['epc_status'] = $this->transaction_model->get_all_epc_status();

				$this->data['small_company'] = $this->transaction_model->get_all_small_company();

				$this->data['audited_financial_statement'] = $this->transaction_model->get_all_audited_financial_statement();

				$this->data['filing_info'] = $this->transaction_model->check_filing_info($client_company_code[0]['company_code']);

				$this->data['check_is_first_agm'] = $this->transaction_model->check_is_first_agm($client_company_code[0]['company_code']);

				$this->data['check_have_share_transfer'] = $this->transaction_model->check_have_share_transfer($client_company_code[0]['company_code']);

				$this->data['require_hold_agm_list'] = $this->transaction_model->get_require_hold_agm_list();

				$interface = $this->load->view('/views/transaction/agm_ar.php', '', TRUE);

				echo json_encode(array("interface" => $interface, $this->data, "error" => null));
			}
	        else
			{
				echo json_encode(array("error" => "Please enter correct registration number."));
			}     	
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
			$query_previous_el = $this->db->query("SELECT transaction_master.* FROM transaction_master where id = (SELECT max(id) FROM transaction_master where transaction_task_id = '30' and firm_id = '".$this->session->userdata("firm_id")."' and status = 1 and company_code = '".$company_code."')");

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

	            $this->data['director_result_1'] = $this->encryption->decrypt($director_result_1[0]["name"]);
	        }
		}

		$this->data['transaction_engagement_letter_list'] = $this->transaction_model->getTransactionEngagementLetterList();

		$interface = $this->load->view('/views/transaction/engagement_letter.php', '', TRUE);

		$get_all_firm_info = $this->transaction_model->getAllFirmInfo();
		for($j = 0; $j < count($get_all_firm_info); $j++)
		{
			if($get_all_firm_info[$j]->branch_name != null)
			{
				$res_firm[$get_all_firm_info[$j]->id] = $get_all_firm_info[$j]->name.' ('.$get_all_firm_info[$j]->branch_name.')';
			}
			else
			{
				$res_firm[$get_all_firm_info[$j]->id] = $get_all_firm_info[$j]->name;
			}
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

	public function get_ml_quarterly_statements_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$company_name = $_POST["company_name"];

		$q = $this->db->query('select * from client where company_name = "'.$company_name.'" and deleted = 0');

		if ($q->num_rows() > 0)   	
		{
			$q = $q->result_array();
			$this->data["company_code"] = $q[0]["company_code"];
			$this->data['client_detail'] = $q;
		}
		else
		{
			$this->data["company_code"] = false;
			$this->data['client_detail'] = false;
		}

		if($transaction_id != null)
		{
			$this->data['transaction_ml_quarterly_statements'] = $this->transaction_model->getTransactionMlQuarterlyStatements($transaction_id);

			$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
		}

		$interface = $this->load->view('/views/transaction/ml_quarterly_statements.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_omp_grant_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$company_name = $_POST["company_name"];

		$q = $this->db->query('select * from client where deleted = 0');

		if ($q->num_rows() > 0)   	
		{
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["company_name"]) == $company_name)
	            {
	                $client_info_info = array($client_info_row);
	            }
	        }
	        if($client_info_info != null)
	        {
				$this->data["company_code"] = $client_info_info[0]["company_code"];
				$this->data['client_detail'] = $client_info_info;
				//$this->data['client_contact_person'] = $this->transaction_model->getClientContactInfo($client_info_info[0]["company_code"]);
			}
	        else
			{
				$this->data["company_code"] = false;
				$this->data['client_detail'] = false;
				//$this->data['client_contact_person'] = false;
			}     	

		}
		else
		{
			$this->data["company_code"] = false;
			$this->data['client_detail'] = false;
			///$this->data['client_contact_person'] = false;
		}

		if($transaction_id != null)
		{
			$this->data['transaction_omp_grant'] = $this->transaction_model->getTransactionOMPGrant($transaction_id);

			// $this->data['transaction_service_proposal_contact_person'] = $this->transaction_model->getTransactionClientContactInfo($transaction_id, $company_code);

			// $this->data['transaction_service_proposal_service_info'] = $this->transaction_model->getTransactionServiceProposalServiceInfo($transaction_id);

			// $this->data['transaction_service_proposal_sub_service_info'] = $this->transaction_model->getTransactionServiceProposalSubServiceInfo($transaction_id);

			$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
		}

		//$this->data['transaction_our_service_list'] = $this->transaction_model->getTransactionOurServiceList();

		$interface = $this->load->view('/views/transaction/omp_grant_page.php', '', TRUE);
		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function get_service_proposal_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$company_name = $_POST["company_name"];

		$q = $this->db->query('select * from client where deleted = 0');
		// company_name = "'.$company_name.'" and
		if ($q->num_rows() > 0)   	
		{
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["company_name"]) == $company_name)
	            {
	                $client_info_info = array($client_info_row);
	            }
	        }
	        if($client_info_info != null)
	        {
				$this->data["company_code"] = $client_info_info[0]["company_code"];
				$this->data['client_detail'] = $client_info_info;
				$this->data['client_contact_person'] = $this->transaction_model->getClientContactInfo($client_info_info[0]["company_code"]);
			}
	        else
			{
				$this->data["company_code"] = false;
				$this->data['client_detail'] = false;
				$this->data['client_contact_person'] = false;
			}     	

		}
		else
		{
			$this->data["company_code"] = false;
			$this->data['client_detail'] = false;
			$this->data['client_contact_person'] = false;
		}

		if($transaction_id != null)
		{
			$this->data['transaction_service_proposal'] = $this->transaction_model->getTransactionServiceProposal($transaction_id);

			$this->data['transaction_service_proposal_contact_person'] = $this->transaction_model->getTransactionClientContactInfo($transaction_id, $company_code);

			$this->data['transaction_service_proposal_service_info'] = $this->transaction_model->getTransactionServiceProposalServiceInfo($transaction_id);

			$this->data['transaction_service_proposal_sub_service_info'] = $this->transaction_model->getTransactionServiceProposalSubServiceInfo($transaction_id);

			$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
		}

		$this->data['transaction_our_service_list'] = $this->transaction_model->getTransactionOurServiceList();

		$interface = $this->load->view('/views/transaction/service_proposal.php', '', TRUE);

		$get_all_firm_info = $this->transaction_model->getAllFirmInfo();
		for($j = 0; $j < count($get_all_firm_info); $j++)
		{
			if($get_all_firm_info[$j]->branch_name != "")
			{
				$branch_name = ' ('.$get_all_firm_info[$j]->branch_name.')';
			}
			else
			{
				$branch_name = "";
			}
			$res_firm[$get_all_firm_info[$j]->id] = $get_all_firm_info[$j]->name.$branch_name;
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

	public function get_change_of_FYE_page()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registra_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where deleted = 0'); // and firm_id = "'.$this->session->userdata('firm_id').'" // registration_no = "'.$registra_no.'" and

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registra_no)
	            {
	                $client_company_code = array($client_info_row);
	            }
	        }
	        if($client_company_code != null)
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

		$q = $this->db->query('select * from client where deleted = 0'); // and firm_id = "'.$this->session->userdata('firm_id').'" // registration_no = "'.$registra_no.'" and

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registra_no)
	            {
	                $client_company_code = array($client_info_row);
	            }
	        }
	        if($client_company_code != null)
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
            	if($row->field_type == "individual")
                {
                    $row->identification_no = $this->encryption->decrypt($row->identification_no);
                    $row->name = $this->encryption->decrypt($row->name);
                }
                elseif($row->field_type == "company")
                {
                    $row->register_no = $this->encryption->decrypt($row->register_no);
                    $row->company_name = $this->encryption->decrypt($row->company_name);
                }
                else
                {
                    $row->registration_no = $this->encryption->decrypt($row->registration_no);
                    $row->client_company_name = $this->encryption->decrypt($row->client_company_name);
                }
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

		$q = $this->db->query('select * from client where deleted = 0'); // and firm_id = "'.$this->session->userdata('firm_id').'" // where registration_no = "'.$registra_no.'" and

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registra_no)
	            {
	                $client_company_code = array($client_info_row);
	            }
	        }
	        if($client_company_code != null)
	        {
	        	//$client_array = $q->result_array();

	        	$this->data["company_code"] = $client_company_code[0]['company_code'];

				if($transaction_id != null)
				{
					$this->data['transaction_meeting_date'] = $this->transaction_model->getTransactionMeetingDate($transaction_id);

					$this->data['transaction_issue_dividend'] = $this->transaction_model->getTransactionIssueDividend($transaction_id);

					$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
				}

				$filing_info = $this->db->query("select company_code, year_end, agm, due_date_175, 175_extended_to, due_date_201, 201_extended_to from filing where company_code='".$client_company_code[0]['company_code']."' order by filing.id DESC LIMIT 2");

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

				// $client_company_code =  $this->db->query("select company_code, postal_code, street_name, building_name, unit_no1, unit_no2 from client where registration_no='".$registra_no."' and firm_id = '".$this->session->userdata('firm_id')."' and deleted = 0");

		  //       $client_company_code = $client_company_code->result_array();

				$this->data['postal_code'] = $client_company_code[0]["postal_code"];
				$this->data['street_name'] = $client_company_code[0]["street_name"];
				$this->data['building_name'] = $client_company_code[0]["building_name"];
				$this->data['unit_no1'] = $client_company_code[0]["unit_no1"];
				$this->data['unit_no2'] = $client_company_code[0]["unit_no2"];

				$interface = $this->load->view('/views/transaction/issue_dividend.php', '', TRUE);

				echo json_encode(array("interface" => $interface, $this->data));
			}
	        else
			{
				echo json_encode(array("error" => "Please enter correct registration number."));
			} 

        	
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

		$q = $this->db->query('select * from client where deleted = 0'); // and firm_id = "'.$this->session->userdata('firm_id').'" //registration_no = "'.$registra_no.'" and 

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registra_no)
	            {
	                $client_company_code = array($client_info_row);
	            }
	        }
	        if($client_company_code != null)
	        {
	        	if($transaction_id != null)
				{
					$this->data['transaction_meeting_date'] = $this->transaction_model->getTransactionMeetingDate($transaction_id);

					$this->data['transaction_issue_director_fee'] = $this->transaction_model->getTransactionIssueDirectorFee($transaction_id);

					$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
				}

				$filing_info = $this->db->query("select company_code, year_end, agm, due_date_175, 175_extended_to, due_date_201, 201_extended_to from filing where company_code='".$client_company_code[0]["company_code"]."' order by filing.id DESC LIMIT 2");

	            if ($filing_info->num_rows() > 0) 
	            {
	            	$filing_info = $filing_info->result_array();
	            	$this->data["year_end"] = $filing_info[0]['year_end'];
	            }
	            else
	            {
	            	$this->data["year_end"] = "";
	            }

	            $officer_info = $this->db->query('select client_officers.*, officer.field_type as officer_field_type, officer.identification_no, officer.name, officer_company.field_type as officer_company_field_type, officer_company.register_no, officer_company.company_name, officer_company.address_type as officer_company_address_type, client_officers_position.position as position_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type left join client_officers_position on client_officers.position = client_officers_position.id where company_code ="'.$client_company_code[0]["company_code"].'" and date_of_cessation = "" and client_officers.position = 1');

	            if ($officer_info->num_rows() > 0) 
	            {
	            	$officer_info = $officer_info->result_array();
	            	for($r = 0; $r < count($officer_info); $r++)
	            	{
		            	if($officer_info[$r]["field_type"] == "individual")
		                {
		                    $officer_info[$r]["identification_no"] = $this->encryption->decrypt($officer_info[$r]["identification_no"]);
		                    $officer_info[$r]["name"] = $this->encryption->decrypt($officer_info[$r]["name"]);
		                }
		                elseif($officer_info[$r]["field_type"] == "company")
		                {
		                    $officer_info[$r]["register_no"] = $this->encryption->decrypt($officer_info[$r]["register_no"]);
		                    $officer_info[$r]["company_name"] = $this->encryption->decrypt($officer_info[$r]["company_name"]);
		                }
		            }
	            	$this->data["officer_info"] = $officer_info;
	            }
	            else
	            {
	            	$this->data["officer_info"] = "";
	            }

	            $this->data["currency"] = $this->transaction_model->get_currency_list();

				// $client_company_code =  $this->db->query("select company_code, postal_code, street_name, building_name, unit_no1, unit_no2 from client where registration_no='".$registra_no."' and firm_id = '".$this->session->userdata('firm_id')."' and deleted = 0");

		  //       $client_company_code = $client_company_code->result_array();

				$this->data['postal_code'] = $client_company_code[0]["postal_code"];
				$this->data['street_name'] = $client_company_code[0]["street_name"];
				$this->data['building_name'] = $client_company_code[0]["building_name"];
				$this->data['unit_no1'] = $client_company_code[0]["unit_no1"];
				$this->data['unit_no2'] = $client_company_code[0]["unit_no2"];

				$interface = $this->load->view('/views/transaction/issue_director_fee.php', '', TRUE);

				echo json_encode(array("interface" => $interface, $this->data));
			}
	        else
			{
				echo json_encode(array("error" => "Please enter correct registration number."));
			} 
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

		$q = $this->db->query('select * from client where deleted = 0'); // and firm_id = "'.$this->session->userdata('firm_id').'" // registration_no = "'.$registra_no.'" and

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registra_no)
	            {
	                $client_company_code = array($client_info_row);
	            }
	        }
	        if($client_company_code != null)
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

		$q = $this->db->query('select * from client where deleted = 0'); // and firm_id = "'.$this->session->userdata('firm_id').'" // registration_no = "'.$registra_no.'" and

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registra_no)
	            {
	                $client_company_code = array($client_info_row);
	            }
	        }
	        if($client_company_code != null)
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

		$q = $this->db->query('select * from client where deleted = 0'); // and firm_id = "'.$this->session->userdata('firm_id').'" // registration_no = "'.$registra_no.'" and

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registra_no)
	            {
	            	$client_info_row["registration_no"] = $this->encryption->decrypt($client_info_row["registration_no"]);
	            	$client_info_row["company_name"] = $this->encryption->decrypt($client_info_row["company_name"]);
	                $client_info_data = $client_info_row;
	            }
	        }
	        if($client_info_data != null)
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
				$registra_no = strtoupper($_POST["registration_no"]);

				$this->data['client_postal_code'] = $client_info_data["postal_code"];
				$this->data['client_street_name'] = $client_info_data["street_name"];
				$this->data['client_building_name'] = $client_info_data["building_name"];
				$this->data['client_unit_no1'] = $client_info_data["unit_no1"];
				$this->data['client_unit_no2'] = $client_info_data["unit_no2"];

				$this->data['consent_for_shorter_notice'] = $this->transaction_model->get_all_consent_for_shorter_notice();
				$this->data["reason_for_application"] = $res;

				echo json_encode(array("interface" => $interface, $this->data));
			}
	        else
			{
				echo json_encode(array("error" => "Please enter correct registration number."));
			}
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

		$q = $this->db->query('select * from client where deleted = 0'); // and firm_id = "'.$this->session->userdata('firm_id').'" // where registration_no = "'.$registra_no.'" and 

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registra_no)
	            {
	                $client_company_code = array($client_info_row);
	            }
	        }
	        if($client_company_code != null)
	        {
	        	if($transaction_id != null)
				{
					$this->data['transaction_meeting_date'] = $this->transaction_model->getTransactionMeetingDate($transaction_id);

					$this->data['transaction_change_company_name'] = $this->transaction_model->getTransactionChangeCompanyName($transaction_id);

					$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
				}

				$this->data['postal_code'] = $client_company_code[0]["postal_code"];
				$this->data['street_name'] = $client_company_code[0]["street_name"];
				$this->data['building_name'] = $client_company_code[0]["building_name"];
				$this->data['unit_no1'] = $client_company_code[0]["unit_no1"];
				$this->data['unit_no2'] = $client_company_code[0]["unit_no2"];

				$interface = $this->load->view('/views/transaction/change_of_company_name.php', '', TRUE);

				echo json_encode(array("interface" => $interface, $this->data));
			}
	        else
			{
				echo json_encode(array("error" => "Please enter correct registration number."));
			}
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

		$q = $this->db->query('select * from client where deleted = 0'); // and firm_id = "'.$this->session->userdata('firm_id').'" // registration_no = "'.$registra_no.'" and

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registra_no)
	            {
	            	$client_info_row["registration_no"] = $this->encryption->decrypt($client_info_row["registration_no"]);
	            	$client_info_row["company_name"] = $this->encryption->decrypt($client_info_row["company_name"]);
	            	
	                $client_info_data = $client_info_row;
	            }
	        }
	        if($client_info_data != null)
	        {
	        	if($transaction_id != null)
				{
					$this->data['transaction_change_reg_ofis'] = $this->transaction_model->getTransactionChangeRegOfisAddress($transaction_id);

					$this->data['transaction_billing'] = $this->transaction_model->getTransactionClientBilling($transaction_id, $company_code);

					$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
				}

				$this->db->select('our_service_registration_address.id, our_service_info.service_name, our_service_registration_address.postal_code, our_service_registration_address.street_name, our_service_registration_address.building_name, our_service_registration_address.unit_no1, our_service_registration_address.unit_no2,
					our_service_registration_address.our_service_info_id')
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

		$q = $this->db->query('select * from client where deleted = 0'); // and firm_id = "'.$this->session->userdata('firm_id').'" // registration_no = "'.$registra_no.'" and

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registra_no)
	            {
	            	$client_info_row["registration_no"] = $this->encryption->decrypt($client_info_row["registration_no"]);
	            	$client_info_row["company_name"] = $this->encryption->decrypt($client_info_row["company_name"]);
	            	$this->data["transaction_client"] = array($client_info_row);
	                $client_info_data = array($client_info_row);
	            }
	        }
	        // print_r($client_info_data);
	        if($client_info_data != null)
	        {
	        	if($transaction_id != null)
				{
					$this->data['transaction_resign_director'] = $this->transaction_model->getTransactionResignClientOfficer($transaction_id);
					$this->data['get_latest_client_nominee_director_data'] = $this->transaction_model->getLatestClientNomineeDirector($company_code, $transaction_id);
					$this->data['transaction_billing'] = $this->transaction_model->getTransactionClientBilling($transaction_id, $company_code);
					$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
				}

				$this->data['postal_code'] = $client_info_data[0]["postal_code"];
				$this->data['street_name'] = $client_info_data[0]["street_name"];
				$this->data['building_name'] = $client_info_data[0]["building_name"];
				$this->data['unit_no1'] = $client_info_data[0]["unit_no1"];
				$this->data['unit_no2'] = $client_info_data[0]["unit_no2"];

				$interface = $this->load->view('/views/transaction/resign_director.php', '', TRUE);
				// $interface = $this->load->view('/views/transaction/appoint_new_director.php', '', TRUE);

				echo json_encode(array("interface" => $interface, $this->data));
			}
	        else
			{
				echo json_encode(array("error" => "Please enter correct registration number."));
			}
		}
		else
		{
			echo json_encode(array("error" => "Please enter correct registration number."));
		}
	}

	public function get_update_register_of_nominee_director()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registra_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where deleted = 0'); 

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registra_no)
	            {
	            	$client_info_row["registration_no"] = $this->encryption->decrypt($client_info_row["registration_no"]);
	            	$client_info_row["company_name"] = $this->encryption->decrypt($client_info_row["company_name"]);
	            	$this->data["transaction_client"] = array($client_info_row);
	                $client_info_data = $client_info_row;
	            }
	        }
	        if($client_info_data != null)
	        {
	        	//-----------------update & edit--------------
	        	if($transaction_id != null)
				{
					$this->data['get_current_client_nominee_director_data'] = $this->transaction_model->getCurrentClientNomineeDirector($company_code, $transaction_id);

					$this->data['get_latest_client_nominee_director_data'] = $this->transaction_model->getLatestClientNomineeDirector($company_code, $transaction_id);

					$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
				}
				//-------------------------------------------
				$interface = $this->load->view('/views/transaction/update_register_of_nominee_director.php', '', TRUE);

				echo json_encode(array("interface" => $interface, $this->data));
			}
	        else
			{
				echo json_encode(array("error" => "Please enter correct registration number."));
			}
		}
		else
		{
			echo json_encode(array("error" => "Please enter correct registration number."));
		}
	}

	public function get_update_register_of_controller()
	{
		$transaction_id = $_POST["id"];
		$company_code = $_POST["company_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registra_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where deleted = 0'); // and firm_id = "'.$this->session->userdata('firm_id').'" // registration_no = "'.$registra_no.'" and

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registra_no)
	            {
	            	$client_info_row["registration_no"] = $this->encryption->decrypt($client_info_row["registration_no"]);
	            	$client_info_row["company_name"] = $this->encryption->decrypt($client_info_row["company_name"]);
	            	$this->data["transaction_client"] = array($client_info_row);
	                $client_info_data = $client_info_row;
	            }
	        }
	        if($client_info_data != null)
	        {
	        	//-----------------update & edit--------------
	        	if($transaction_id != null)
				{
					$this->data['get_current_client_controller_data'] = $this->transaction_model->getCurrentClientController($company_code, $transaction_id);

					$this->data['get_latest_client_controller_data'] = $this->transaction_model->getLatestClientController($company_code, $transaction_id);

					$this->data['document'] = $this->transaction_model->get_document($transaction_task_id, $company_code);
				}
				//-------------------------------------------
				$interface = $this->load->view('/views/transaction/update_register_of_controller.php', '', TRUE);

				echo json_encode(array("interface" => $interface, $this->data));
			}
	        else
			{
				echo json_encode(array("error" => "Please enter correct registration number."));
			}
		}
		else
		{
			echo json_encode(array("error" => "Please enter correct registration number."));
		}
	}

	public function save_company_info()
	{		
		$transaction_master_id = $_POST['transaction_master_id'];
		$company_code = $_POST['transaction_company_code'];
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
		$data['company_name'] = $this->encryption->encrypt(trim(strtoupper($_POST['company_name'])));
		$data['former_name'] = "";
		$data['incorporation_date'] = "";
		$data['company_type'] = $_POST['company_type'];
		$data['status'] = 1;
		$data['activity1'] = strtoupper($_POST['activity1']);
		$data['description1'] = strtoupper($_POST['description1']);
		$data['activity2'] = strtoupper($_POST['activity2']);
		$data['description2'] = strtoupper($_POST['description2']);
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
			
			$this->save_audit_trail("Services", "Incorporation of new company", "Company information is added in incorporation services.");
		} 
		else 
		{
			$q = $q->result_array();
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $transaction_master_id)); //$this->session->userdata('transaction_id')

			$this->db->update("transaction_client",$data,array("company_code" =>  $_POST['transaction_company_code']));
			$transaction_id = $transaction_master_id;

			$transaction_code = $_POST['transaction_code'];
			
			$this->save_audit_trail("Services", "Incorporation of new company", "Company information is edited in incorporation services.");
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
				$cancel_by_system["completed"] = 1;
				$this->db->update("transaction_master",$cancel_by_system,array("id" => $previous_transaction_client_query[$f]["transaction_id"], "status" => "1"));
			}
		}
		/*------------------*/
		$client_query = $this->db->query("SELECT transaction_client.* FROM transaction_client WHERE transaction_client.company_code = '".$company_code."'");
		$client_array = $client_query->result_array();
		// $qb_status = $this->import_trans_client_to_quickbook($client_array[0]["id"]);

		$currency_result = $this->db->query("select * from currency order by id");
		$currency_result = $currency_result->result_array();
		$currency_res = array();
        foreach($currency_result as $row) {
            $currency_res[$row['currency']] = $row['currency'];
        }

        $check_client_qb_info = $this->db->query("select transaction_client.*, transaction_client_qb_id.currency_name, transaction_client_qb_id.qb_customer_id from transaction_client_qb_id left join transaction_client on transaction_client_qb_id.company_code = transaction_client.company_code where transaction_client_qb_id.company_code = '".$company_code."' and transaction_client.deleted = 0");
        $check_client_qb_info = $check_client_qb_info->result_array();
        if(count($check_client_qb_info) > 0)
        {
	        foreach($check_client_qb_info as $key => $row) {
	            $check_client_qb_info[$key]["company_name"] = $this->encryption->decrypt($row["company_name"]);
	        }
	    }

        echo json_encode(array("Status" => 1,'message' => "Save Successfully", 'title' => "Success", 'document' => $this->data['document'], "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code, 'client_id' => $client_array[0]["id"], "currency" => $currency_res, "check_client_qb_info" => $check_client_qb_info, "qb_company_id" => $this->session->userdata('qb_company_id')));
	}

	public function import_qb_client_to_quickbook()
	{
		$client_id = $_POST["client_id"];
		$currency_name = $_POST["client_qb_currency"];

		$qb_status = $this->import_trans_client_to_quickbook($client_id, $currency_name);

		echo json_encode($qb_status);
	}

	public function import_trans_client_to_quickbook($client_id, $currency_name = null)
    {
    	if($this->session->userdata('refresh_token_value'))
    	{
    		try {
		    	// Prep Data Services
				$dataService = DataService::Configure(array(
					'auth_mode' => 'oauth2',
					'ClientID' => $this->quickbook_clientID,
					'ClientSecret' => $this->quickbook_clientSecret,
					'accessTokenKey' => $this->session->userdata('access_token_value'),
					'refreshTokenKey' => $this->session->userdata('refresh_token_value'),
					'QBORealmID' => $this->session->userdata('qb_company_id'), //"The Company ID which the app wants to access"
					'baseUrl' => $this->quickbookBaseUrl
				));

				$dataService->throwExceptionOnError(true);

				$client_query = $this->db->query("SELECT transaction_client.*, transaction_client_contact_info_email.email, transaction_client_qb_id.qb_customer_id FROM transaction_client LEFT JOIN transaction_client_contact_info ON transaction_client_contact_info.company_code = transaction_client.company_code LEFT JOIN transaction_client_contact_info_email ON transaction_client_contact_info_email.client_contact_info_id = transaction_client_contact_info.id AND transaction_client_contact_info_email.primary_email = 1 LEFT JOIN transaction_client_qb_id ON transaction_client_qb_id.company_code = transaction_client.company_code AND transaction_client_qb_id.currency_name = '".$currency_name."' WHERE transaction_client.deleted = 0 AND transaction_client.postal_code != '' AND transaction_client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."' AND transaction_client.id = '".$client_id."'");
				if ($client_query->num_rows() == 0) 
		  		{
		  			$client_query = $this->db->query("SELECT transaction_client.*, transaction_client_contact_info_email.email FROM transaction_client LEFT JOIN transaction_client_contact_info ON transaction_client_contact_info.company_code = transaction_client.company_code LEFT JOIN transaction_client_contact_info_email ON transaction_client_contact_info_email.client_contact_info_id = transaction_client_contact_info.id AND transaction_client_contact_info_email.primary_email = 1 WHERE transaction_client.deleted = 0 AND transaction_client.postal_code != '' AND transaction_client.id = '".$client_id."'");
		  		}
				if ($client_query->num_rows() > 0) 
		        {
					$client_query = $client_query->result_array();
					foreach ($client_query as $row) 
					{
						// Add unit
						if(!empty($row['unit_no1']) && !empty($row['unit_no2']))
						{
							$unit = '#' . $row['unit_no1'] . '-' . $row['unit_no2'];
						}

						// Add building
						if(!empty($row['building_name']) && !empty($unit))
						{
							$unit_building_name = $unit . ' ' . $row['building_name'];
						}
						elseif(!empty($unit))
						{
							$unit_building_name = $unit;
						}
						elseif(!empty($row['building_name']))
						{
							$unit_building_name = $row['building_name'];
						}

						if(!empty($row["currency_name"]))
						{
							$qb_currency_name = $row["currency_name"];
						}
						else
						{
							$qb_currency_name = $currency_name;
						}

						$customer_info = [
							    "BillAddr" => [
							        "Line1" => strtoupper(trim($row["street_name"])),
							        "City" => strtoupper(trim($unit_building_name)),
							        "Country" => "",
							        "CountrySubDivisionCode" => "SINGAPORE",
							        "PostalCode" => strtoupper(trim($row["postal_code"]))
							    ],
							    "CurrencyRef" => [
									"value" => $qb_currency_name
								],
							    "Notes" => "",
							    "Title" => "",
							    "GivenName" => "",
							    "MiddleName" => "",
							    "FamilyName" => "",
							    "Suffix" => "",
							    "FullyQualifiedName" => trim($this->encryption->decrypt($row["company_name"])." (".$qb_currency_name.")"),
							    "CompanyName" => trim($this->encryption->decrypt($row["company_name"])." (".$qb_currency_name.")"),
							    "DisplayName" => str_replace(':', "", trim($this->encryption->decrypt($row["company_name"])." (".$qb_currency_name.")"))
							];

						if(!empty($row['email']))
						{
							$email_to = explode(';',$row["email"]);
					        if(count($email_to) > 0)
					        {
					        	for($t = 0; $t < count($email_to); $t++)
					        	{
									$isvalid = filter_var(trim($email_to[$t]), FILTER_VALIDATE_EMAIL);
									if($isvalid != false)
									{
										if($t == 0)
										{
											$email_in_qb = trim($email_to[$t]);
										}
										else
										{
											$email_in_qb = $email_in_qb.', '.trim($email_to[$t]);
										}
									}
								}
							}

							$email_add = ["PrimaryEmailAddr" => [
									      	"Address" => $email_in_qb
									    ]];
							$customer_info = array_merge($customer_info, $email_add);
						}

						if($row["qb_customer_id"] != 0)
						{
							$customer = $dataService->FindbyId('customer', $row["qb_customer_id"]);
							$theResourceObj = Customer::update($customer, $customer_info);
							$resultingObj = $dataService->Update($theResourceObj);
						}
						else
						{
							//Add a new Vendor
							$theResourceObj = Customer::create($customer_info);
							$resultingObj = $dataService->Add($theResourceObj);
						}

						$error = $dataService->getLastError();

						if ($error) {
						    if($error->getHttpStatusCode() == "401")
						    {
						    	$refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting();
						    	if($refresh_token_status)
						    	{
						    		$this->import_trans_client_to_quickbook($client_id, $currency_name);
						    	}
						    }
						    else
						    {
						    	return array("Status" => 3, 'message' => $error->getIntuitErrorMessage(), 'title' => 'Error');
						    }
						}
						else {
							$data["qb_company_id"] = $this->session->userdata('qb_company_id');
						    $data["company_code"] = $row["company_code"];
						    $data["qb_customer_id"] = $resultingObj->Id;
						    $data["currency_name"] = $qb_currency_name;
						    $data["qb_json_data"] = json_encode($resultingObj);

						    if(!empty($row["qb_customer_id"]))
							{
								$this->db->update("transaction_client_qb_id",$data,array("qb_customer_id" => $row["qb_customer_id"], "qb_company_id" => $this->session->userdata('qb_company_id')));
							}
							else
							{
								$this->db->insert("transaction_client_qb_id",$data);
							}

							$this->save_audit_trail("Incorporation of new company", "QuickBooks", $this->session->userdata('first_name'). " " . $this->session->userdata('last_name') . " import ".trim($this->encryption->decrypt($row["company_name"])." (".$qb_currency_name.")")." client to QuickBooks Online.");
						}
					}

					return array("Status" => 1, 'message' => "Import Successfully", 'title' => 'Success');
				}
			}
			catch (Exception $e){
				$refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting();
		    	if($refresh_token_status)
		    	{
		    		$this->import_trans_client_to_quickbook($client_id, $currency_name);
		    	}
			}
		}
		else
		{
			return array("Status" => 2, 'message' => 'If you want to import this client to Quickbook Online, please login to Quickbook Online first before proceed this step.', 'title' => 'Warning');
		}
    }

    public function update_client_to_quickbook($client_id, $currency_name = null)
    {
    	if($this->session->userdata('refresh_token_value'))
    	{
    		try {
		    	// Prep Data Services
				$dataService = DataService::Configure(array(
					'auth_mode' => 'oauth2',
					'ClientID' => $this->quickbook_clientID,
					'ClientSecret' => $this->quickbook_clientSecret,
					'accessTokenKey' => $this->session->userdata('access_token_value'),
					'refreshTokenKey' => $this->session->userdata('refresh_token_value'),
					'QBORealmID' => $this->session->userdata('qb_company_id'), //"The Company ID which the app wants to access"
					'baseUrl' => $this->quickbookBaseUrl
				));

				$dataService->throwExceptionOnError(true);

				$client_query = $this->db->query("SELECT client.*, client_contact_info_email.email, client_qb_id.qb_customer_id FROM client LEFT JOIN client_contact_info ON client_contact_info.company_code = client.company_code LEFT JOIN client_contact_info_email ON client_contact_info_email.client_contact_info_id = client_contact_info.id AND client_contact_info_email.primary_email = 1 LEFT JOIN client_qb_id ON client_qb_id.company_code = client.company_code AND client_qb_id.currency_name = '".$currency_name."' WHERE client.deleted = 0 AND client.postal_code != '' AND client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."' AND client.id = '".$client_id."'");

				if ($client_query->num_rows() > 0) 
		        {
					$client_query = $client_query->result_array();
					foreach ($client_query as $row) 
					{
						// Add unit
						if(!empty($row['unit_no1']) && !empty($row['unit_no2']))
						{
							$unit = '#' . $row['unit_no1'] . '-' . $row['unit_no2'];
						}

						// Add building
						if(!empty($row['building_name']) && !empty($unit))
						{
							$unit_building_name = $unit . ' ' . $row['building_name'];
						}
						elseif(!empty($unit))
						{
							$unit_building_name = $unit;
						}
						elseif(!empty($row['building_name']))
						{
							$unit_building_name = $row['building_name'];
						}

						if(!empty($row["currency_name"]))
						{
							$qb_currency_name = $row["currency_name"];
						}
						else
						{
							$qb_currency_name = $currency_name;
						}

						$customer_info = [
							    "BillAddr" => [
							        "Line1" => strtoupper(trim($row["street_name"])),
							        "City" => strtoupper(trim($unit_building_name)),
							        "Country" => "",
							        "CountrySubDivisionCode" => "SINGAPORE",
							        "PostalCode" => strtoupper(trim($row["postal_code"]))
							    ],
							    "CurrencyRef" => [
									"value" => $qb_currency_name
								],
							    "Notes" => "",
							    "Title" => "",
							    "GivenName" => "",
							    "MiddleName" => "",
							    "FamilyName" => "",
							    "Suffix" => "",
							    "FullyQualifiedName" => trim($this->encryption->decrypt($row["company_name"])." (".$qb_currency_name.")"),
							    "CompanyName" => trim($this->encryption->decrypt($row["company_name"])." (".$qb_currency_name.")"),
							    "DisplayName" => str_replace(':', "", trim($this->encryption->decrypt($row["company_name"])." (".$qb_currency_name.")"))
							];

						if(!empty($row['email']))
						{
							$email_to = explode(';',$row["email"]);
					        if(count($email_to) > 0)
					        {
					        	for($t = 0; $t < count($email_to); $t++)
					        	{
									$isvalid = filter_var(trim($email_to[$t]), FILTER_VALIDATE_EMAIL);
									if($isvalid != false)
									{
										if($t == 0)
										{
											$email_in_qb = trim($email_to[$t]);
										}
										else
										{
											$email_in_qb = $email_in_qb.', '.trim($email_to[$t]);
										}
									}
								}
							}

							$email_add = ["PrimaryEmailAddr" => [
									      	"Address" => $email_in_qb
									    ]];
							$customer_info = array_merge($customer_info, $email_add);
						}

						if($row["qb_customer_id"] != 0)
						{
							$customer = $dataService->FindbyId('customer', $row["qb_customer_id"]);
							$theResourceObj = Customer::update($customer, $customer_info);
							$resultingObj = $dataService->Update($theResourceObj);
						}
						else
						{
							//Add a new Vendor
							$theResourceObj = Customer::create($customer_info);
							$resultingObj = $dataService->Add($theResourceObj);
						}

						$error = $dataService->getLastError();

						if ($error) {
						    if($error->getHttpStatusCode() == "401")
						    {
						    	$refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting();
						    	if($refresh_token_status)
						    	{
						    		$this->update_client_to_quickbook($client_id, $currency_name);
						    	}
						    }
						    else
						    {
						    	return array("Status" => 3, 'message' => $error->getIntuitErrorMessage(), 'title' => 'Error');
						    }
						}
						else {
							$data["qb_company_id"] = $this->session->userdata('qb_company_id');
						    $data["company_code"] = $row["company_code"];
						    $data["qb_customer_id"] = $resultingObj->Id;
						    $data["currency_name"] = $qb_currency_name;
						    $data["qb_json_data"] = json_encode($resultingObj);

						    if(!empty($row["qb_customer_id"]))
							{
								$this->db->update("client_qb_id",$data,array("qb_customer_id" => $row["qb_customer_id"], "qb_company_id" => $this->session->userdata('qb_company_id')));
							}
							else
							{
								$this->db->insert("transaction_client_qb_id",$data);
							}
						}
					}

					return array("Status" => 1, 'message' => "Import Successfully", 'title' => 'Success');
				}
			}
			catch (Exception $e){
				$refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting();
		    	if($refresh_token_status)
		    	{
		    		$this->update_client_to_quickbook($client_id, $currency_name);
		    	}
			}
		}
		else
		{
			return array("Status" => 2, 'message' => 'If you want to import this client to Quickbook Online, please login to Quickbook Online first before proceed this step.', 'title' => 'Warning');
		}
    }

	public function get_client_officers_position()
	{
		$position = isset($_POST['position']) ? $_POST['position'] : '';

		$result = $this->db->query("select * from client_officers_position where id != 7");

		/*$result = $this->db->query("select * from client_officers AS A where position = 'Director' AND company_code='".$company_code."' AND NOT EXISTS (SELECT alternate_of from client_officers AS B where A.id = B.alternate_of)");*/

		$result = $result->result_array();

		if(!$result) {
			throw new exception("Client officers position not found.");
		}
		$res = array();
		foreach($result as $row) {
			$res[$row['id']] = $row['position'];
		}

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
		$identification_register_no = strtoupper($_POST['identification_register_no']);
		$company_code = $_POST['company_code'];

		$query = "(select id, 
		field_type, 
		identification_no, 
		name 
		from officer 
		where YEAR(CURDATE()) - YEAR(date_of_birth) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(date_of_birth), '-', DAY(date_of_birth)) ,'%Y-%c-%e') > CURDATE(), 1, 0) > 18 AND user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."')
				UNION
		           	(select id, 'client' AS field_type, registration_no, company_name from client) 
		        UNION
		           (select officer_company.id, officer_company.field_type, officer_company.register_no, officer_company.company_name from officer_company where user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."')";

		$q = $this->db->query($query);

		if ($q->num_rows() > 0) {
			if($identification_register_no != "")
			{
				$y = $q->result_array();

				$officer_info_data = array();

	            foreach ($y as $key => $officer_info_row) 
	            {
	            	$identification_no = $this->encryption->decrypt($officer_info_row["identification_no"]);
					$register_no = $this->encryption->decrypt($officer_info_row["register_no"]);
					$registration_no = $this->encryption->decrypt($officer_info_row["registration_no"]);

					$name = $this->encryption->decrypt($officer_info_row["name"]);
					$company_name = $this->encryption->decrypt($officer_info_row["company_name"]);
	                
	                if($identification_no == $identification_register_no)
	                {
	                	$officer_info_row["identification_no"] = $identification_no;
	                	$officer_info_row["name"] = $name;
	                	$officer_info_row["test"] = $identification_no;
	                    $officer_info_data = $officer_info_row;
	                    break;
	                }

	                if($register_no == $identification_register_no)
	                {
	                	$officer_info_row["register_no"] = $register_no;
	                	$officer_info_row["company_name"] = $company_name;
	                    $officer_info_data = $officer_info_row;
	                    break;
	                }
	                
	                if($registration_no == $identification_register_no)  
	                {
	                	$officer_info_row["registration_no"] = $registration_no;
	                	$officer_info_row["company_name"] = $company_name;
	                    $officer_info_data = $officer_info_row; 
	                    break;
	                }
	            }



				if($y[0]["field_type"] == "company")
				{
					$t = $this->db->query("select * from client_officers where officer_id = '".$y[0]["id"]."' AND field_type = '".$y[0]["field_type"]."' AND company_code = '".$company_code."'");

					if ($t->num_rows() > 0) 
					{
						echo json_encode(array("status" => 2));
					}
					else
					{
						echo json_encode(array("status" => 1, "info" => $officer_info_data,"type1"=>"true"));  
					}

				} 
				else
				{
					echo json_encode(array("status" => 1, "info" => $officer_info_data,"type2"=>$identification_no." - ".$register_no." - ".$registration_no." - ".$identification_register_no)); 
				}
			}
			else
			{
				echo json_encode(array("status" => 2));
			}
        } else echo json_encode(array("status" => 1, "info" => $officer_info_data,"type3"=>"true")); 
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
				$this->db->update("transaction_master",$edit_transaction,array("id" => $this->session->userdata('transaction_id')));

				$transaction_tasks_result = $this->db->query("select transaction_tasks.transaction_task from transaction_master left join transaction_tasks on transaction_tasks.id = transaction_master.transaction_task_id where transaction_master.id = '".$this->session->userdata('transaction_id')."'");
        		$transaction_tasks_array = $transaction_tasks_result->result_array();

        		if($transaction_tasks_array[0]["transaction_task"] == "Incorporation of new company")
        		{
        			$action = "Officers is deleted in incorporation services.";
        		}
        		else if($transaction_tasks_array[0]["transaction_task"] == "Appointment and Resign of Director")
        		{
        			$action = "Appointment of Director is deleted.";
        		}
        		else if($transaction_tasks_array[0]["transaction_task"] == "Appointment and Resign of Secretarial")
        		{
        			$action = "Appointment of Secretarial is deleted.";
        		}
        		else if($transaction_tasks_array[0]["transaction_task"] == "Appointment and Resign of Auditor")
        		{
        			$action = "Appointment of Auditor is deleted.";
        		}

				$this->save_audit_trail("Services", $transaction_tasks_array[0]["transaction_task"], $action);

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
		$identification_register_no = strtoupper($_POST['identification_register_no']);
		$position = $_POST['position'];
		$company_code = $_POST['company_code'];

		if ($position == "5")
		{
			$q = $this->db->query("select * from officer_company where user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."' ");//register_no='".$identification_register_no."' AND 

			if ($q->num_rows() > 0) {

				$officer_company_info = $q->result_array();

                foreach ($officer_company_info as $officer_company_info_row) {
                    if($this->encryption->decrypt($officer_company_info_row["register_no"]) == $identification_register_no)
                    {
                    	$officer_company_info_row["company_name"] = $this->encryption->decrypt($officer_company_info_row["company_name"]);
                        $officer_company_info_data = $officer_company_info_row;
                    }
                }

                if($officer_company_info_data != null)
		        {
		        	$chk_member = $this->db->query("select * from member_shares where officer_id='".$officer_company_info_data["id"]."' AND field_type = '".$officer_company_info_data["field_type"]."' AND company_code = '".$company_code."'");

					if ($chk_member->num_rows() > 0) {
						echo json_encode(array("status" => 5, "message" => "This person is a member for this company.", "title" => "Error"));
					}
					else
					{
						echo json_encode(array("status" => 1, "info" => $officer_company_info_data));//$q->result()[0]
					}
		        }
		        else 
		        {
		        	$q = $this->db->query("select * from officer where YEAR(CURDATE()) - YEAR(date_of_birth) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(date_of_birth), '-', DAY(date_of_birth)) ,'%Y-%c-%e') > CURDATE(), 1, 0) > 18 AND user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");//AND identification_no='".$identification_register_no."' 
					
					if ($q->num_rows() > 0) {
						$officer_info = $q->result_array();

	                    foreach ($officer_info as $officer_info_row) 
	                    {
	                        if($this->encryption->decrypt($officer_info_row["identification_no"]) == $identification_register_no)
	                        {
	                            $officer_info_data = $officer_info_row;
	                        }
	                    }
	                    if($officer_info_data != null)
		                {
							echo json_encode(array("status" => 4, "message" => "This person should be a company.", "title" => "Error"));
						}
						else
						{
							echo json_encode(array("status" => 3));
						}
			        } 
			        else echo json_encode(array("status" => 3));
		        }
	        } 
	        else 
	        {
	        	$q = $this->db->query("select * from officer where YEAR(CURDATE()) - YEAR(date_of_birth) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(date_of_birth), '-', DAY(date_of_birth)) ,'%Y-%c-%e') > CURDATE(), 1, 0) > 18 AND user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");//AND identification_no='".$identification_register_no."' 
					
				if ($q->num_rows() > 0) {
					$officer_info = $q->result_array();

                    foreach ($officer_info as $officer_info_row) 
                    {
                        if($this->encryption->decrypt($officer_info_row["identification_no"]) == $identification_register_no)
                        {
                            $officer_info_data = $officer_info_row;
                        }
                    }
                    if($officer_info_data != null)
	                {
						echo json_encode(array("status" => 4, "message" => "This person should be a company.", "title" => "Error"));
					}
					else
					{
						echo json_encode(array("status" => 3));
					}
		        } 
		        else echo json_encode(array("status" => 3));
	        }
		}
		else
		{
			if($officer_id == null)
			{
				$q = $this->db->query("select * from officer where YEAR(CURDATE()) - YEAR(date_of_birth) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(date_of_birth), '-', DAY(date_of_birth)) ,'%Y-%c-%e') > CURDATE(), 1, 0) > 18 AND user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");
				// AND identification_no='".$identification_register_no."'

				if ($q->num_rows() > 0) 
				{
					$officer_info = $q->result_array();

                    foreach ($officer_info as $officer_info_row) 
                    {
                        if($this->encryption->decrypt($officer_info_row["identification_no"]) == $identification_register_no)
                        {
                        	$officer_info_row["name"] = $this->encryption->decrypt($officer_info_row["name"]);
                            $officer_info_data = $officer_info_row;
                        }
                    }

                    if($officer_info_data != null)
                    {
                    	echo json_encode(array("status" => 1, "info" => $officer_info_data));//$q->result()[0]
                    }
                    else
                    {
                    	$q = $this->db->query("select * from officer_company where user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");// register_no='".$identification_register_no."' AND

						if ($q->num_rows() > 0) {
							$officer_company_info = $q->result_array();

		                    foreach ($officer_company_info as $officer_company_info_row) {
		                        if($this->encryption->decrypt($officer_company_info_row["register_no"]) == $identification_register_no)
		                        {
		                            $officer_company_info_data = $officer_company_info_row;
		                        }
		                    }
		                    if($officer_company_info_data != null)
		                    {
								echo json_encode(array("status" => 4, "message" => "This person should be an individual.", "title" => "Error"));
		                    }
		                    else
		                    { 
		                    	echo json_encode(array("status" => 3));
		                    }
				        } 
				        else echo json_encode(array("status" => 3));
                    }
		        } 
		        else
                {
                	$q = $this->db->query("select * from officer_company where user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");// register_no='".$identification_register_no."' AND

					if ($q->num_rows() > 0) {
						$officer_company_info = $q->result_array();

	                    foreach ($officer_company_info as $officer_company_info_row) {
	                        if($this->encryption->decrypt($officer_company_info_row["register_no"]) == $identification_register_no)
	                        {
	                            $officer_company_info_data = $officer_company_info_row;
	                        }
	                    }
	                    if($officer_company_info_data != null)
	                    {
							echo json_encode(array("status" => 4, "message" => "This person should be an individual.", "title" => "Error"));
	                    }
	                    else
	                    { 
	                    	echo json_encode(array("status" => 3));
	                    }
			        } 
			        else echo json_encode(array("status" => 3));
                }
			}
			else
			{
				$result = $this->db->query("select * from client_officers where id = '".$officer_id."'");

				$result = $result->result_array();

				$q = $this->db->query("select * from officer where YEAR(CURDATE()) - YEAR(date_of_birth) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(date_of_birth), '-', DAY(date_of_birth)) ,'%Y-%c-%e') > CURDATE(), 1, 0) > 18 AND user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");// AND identification_no='".$identification_register_no."'

				if ($q->num_rows() > 0) 
				{
					$officer_info = $q->result_array();

                    foreach ($officer_info as $officer_info_row) 
                    {
                        if($this->encryption->decrypt($officer_info_row["identification_no"]) == $identification_register_no)
                        {
                        	$officer_info_row["name"] = $this->encryption->decrypt($officer_info_row["name"]);
                            $officer_info_data = $officer_info_row;
                        }
                    }

                    if($officer_info_data != null)
                    {
                    	if($result[0]["officer_id"] == $officer_info_data["id"])
						{
							echo json_encode(array("status" => 2, "message" => "He/She can not be the alternate for his/her own.", "title" => "Error"));
						}
						else
						{
							echo json_encode(array("status" => 1, "info" => $officer_info_data));
						}
                    }
                    else 
			        {
			        	$q = $this->db->query("select * from officer_company where user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");// register_no='".$identification_register_no."' AND

			        	if ($q->num_rows() > 0) {
							$officer_company_info = $q->result_array();

		                    foreach ($officer_company_info as $officer_company_info_row) {
		                        if($this->encryption->decrypt($officer_company_info_row["register_no"]) == $identification_register_no)
		                        {
		                            $officer_company_info_data = $officer_company_info_row;
		                        }
		                    }
		                    if($officer_company_info_data != null)
		                    {
								echo json_encode(array("status" => 4, "message" => "This person should be an individual.", "title" => "Error"));
		                    }
		                    else
		                    { 
		                    	echo json_encode(array("status" => 3));
		                    }
				        } 
				        else echo json_encode(array("status" => 3));
			        }
                }
                else 
		        {
		        	$q = $this->db->query("select * from officer_company where user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");// register_no='".$identification_register_no."' AND

		        	if ($q->num_rows() > 0) {
						$officer_company_info = $q->result_array();

	                    foreach ($officer_company_info as $officer_company_info_row) {
	                        if($this->encryption->decrypt($officer_company_info_row["register_no"]) == $identification_register_no)
	                        {
	                            $officer_company_info_data = $officer_company_info_row;
	                        }
	                    }
	                    if($officer_company_info_data != null)
	                    {
							echo json_encode(array("status" => 4, "message" => "This person should be an individual.", "title" => "Error"));
	                    }
	                    else
	                    { 
	                    	echo json_encode(array("status" => 3));
	                    }
			        } 
			        else echo json_encode(array("status" => 3));
		        }
			}
		}
	}

	public function add_officer ()
	{
		$transaction_master_id = $_POST['transaction_master_id'];
		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		for($i = 0; $i < count($_POST['identification_register_no']); $i++ )
		{
			$data['transaction_id']=$transaction_master_id;
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

				$this->save_audit_trail("Services", "Incorporation of new company", "Officers is added in incorporation services.");
				
			} 
			else 
			{
				$this->db->update("transaction_client_officers",$data,array("id" => $_POST['client_officer_id'][$i]));
				
				$this->save_audit_trail("Services", "Incorporation of new company", "Officers is edited in incorporation services.");
			}

		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $transaction_master_id)); //$this->session->userdata('transaction_id')

		$this->data['transaction_client_officers'] = $this->transaction_model->getTransactionClientOfficer($transaction_master_id, $_POST['company_code']);

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_client_officers" => $this->data['transaction_client_officers']));
	}

	public function add_controller ()
	{
		$transaction_master_id = $_POST['transaction_master_id'];
		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		for($i = 0; $i < count($_POST['identification_register_no']); $i++ )
		{
			$data['transaction_id']=$transaction_master_id;
			$data['company_code']=$_POST['company_code'];
			$data['officer_id']=$_POST['officer_id'][$i];
			$data['field_type']=$_POST['officer_field_type'][$i];

			$q = $this->db->get_where("transaction_client_controller", array("id" => $_POST['client_controller_id'][$i]));

			if (!$q->num_rows())
			{
				$this->db->insert("transaction_client_controller",$data);
				$insert_client_controller_id = $this->db->insert_id();

				$this->save_audit_trail("Services", "Incorporation of new company", "Controller is added in incorporation services.");
			} 
			else 
			{
				$this->db->update("transaction_client_controller",$data,array("id" => $_POST['client_controller_id'][$i]));

				$this->save_audit_trail("Services", "Incorporation of new company", "Controller is edited in incorporation services.");
			}
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
				$this->db->update("transaction_master",$edit_transaction,array("id" =>  $transaction_master_id));

		$this->data['transaction_client_controller'] = $this->transaction_model->getTransactionClientController($transaction_master_id, $_POST['company_code']);

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_client_controller" => $this->data['transaction_client_controller']));
	}

	public function delete_controller ()
	{
		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		$this->save_audit_trail("Services", "Incorporation of new company", "Controller is deleted in incorporation services.");

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
		$transaction_master_id = $_POST['transaction_master_id'];
		$data['company_code'] = $_POST['company_code'];
		$data['transaction_id'] = $transaction_master_id;
		$data['year_end'] = $_POST['year_end'];
		$data['financial_year_period'] = $_POST['financial_year_period'];
		
		$q = $this->db->get_where("transaction_filing", array("transaction_id" => $transaction_master_id));

		if (!$q->num_rows())
		{
			$this->db->insert("transaction_filing",$data);
			$this->save_audit_trail("Services", "Incorporation of new company", "Filing is added in incorporation services.");
		} 
		else 
		{
			$this->db->update("transaction_filing",$data,array("transaction_id" => $transaction_master_id));
			$this->save_audit_trail("Services", "Incorporation of new company", "Filing is edited in incorporation services.");
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" => $transaction_master_id));

        echo json_encode(array("Status" => 1,'message' => 'Information Updated', 'title' => 'Updated'));
	}

	public function add_client_billing_info()
	{
		$transaction_master_id = $_POST['transaction_master_id'];
		$_POST['client_billing_info_id'] = array_values($_POST['client_billing_info_id']);
		$_POST['invoice_description'] = array_values($_POST['invoice_description']);
		$_POST['amount'] = array_values($_POST['amount']);
		$_POST['currency'] = array_values($_POST['currency']);
		$_POST['unit_pricing'] = array_values($_POST['unit_pricing']);
		$_POST['servicing_firm'] = array_values($_POST['servicing_firm']);

		$transaction_tasks_result = $this->db->query("select * from transaction_tasks where id = '".$_POST["transaction_task_id"]."'");
        $transaction_tasks_array = $transaction_tasks_result->result_array();

		if(count(json_decode($_POST['array_client_billing_info_id'])) != 0)
		{
			for($g = 0; $g < count(json_decode($_POST['array_client_billing_info_id'])); $g++ )
			{
				$deleted_client_billing_info['deleted'] = 1;

				$this->db->where(array("company_code" => $_POST['company_code'], "client_billing_info_id" => json_decode($_POST['array_client_billing_info_id'])[$g]));
				$this->db->update("transaction_client_billing_info",$deleted_client_billing_info);
			}

			$this->save_audit_trail("Services", $transaction_tasks_array[0]["transaction_task"], "Service Engagement is deleted.");
		}

		for($i = 0; $i < count($_POST['client_billing_info_id']); $i++ )
		{
			$transaction_client_billing_info['transaction_id'] = $transaction_master_id;
			$transaction_client_billing_info['company_code'] = $_POST['company_code'];
			$transaction_client_billing_info['client_billing_info_id'] = $_POST['client_billing_info_id'][$i];
			$transaction_client_billing_info['service'] = $_POST['service'][$i];
			$transaction_client_billing_info['invoice_description'] = $_POST['invoice_description'][$i];
			$transaction_client_billing_info['amount'] = (float)str_replace(',', '', $_POST['amount'][$i]);
			$transaction_client_billing_info['currency'] = (float)str_replace(',', '', $_POST['currency'][$i]);
			$transaction_client_billing_info['unit_pricing'] = (float)str_replace(',', '', $_POST['unit_pricing'][$i]);
			$transaction_client_billing_info['servicing_firm'] = $_POST['servicing_firm'][$i];
			
			$q = $this->db->get_where("transaction_client_billing_info", array("company_code" => $_POST['company_code'], "client_billing_info_id" => $_POST['client_billing_info_id'][$i], "service" => $_POST['service'][$i], "deleted =" => 0));

			if (!$q->num_rows())
			{				
				$this->db->insert("transaction_client_billing_info",$transaction_client_billing_info);

				$this->save_audit_trail("Services", $transaction_tasks_array[0]["transaction_task"], "Service Engagement is added.");
			} 
			else 
			{	
				
				$this->db->where(array("company_code" => $_POST['company_code'], "client_billing_info_id" => $_POST['client_billing_info_id'][$i], "service" => $_POST['service'][$i], "deleted =" => 0));
				$this->db->update("transaction_client_billing_info",$transaction_client_billing_info);

				$this->save_audit_trail("Services", $transaction_tasks_array[0]["transaction_task"], "Service Engagement is edited.");
			}
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $transaction_master_id));

		$this->data['transaction_billing'] = $this->transaction_model->getTransactionClientBilling($transaction_master_id, $_POST['company_code']);

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', 'transaction_client_billing' => $this->data['transaction_billing']));
	}

	public function get_billing_info_service()
	{
        $service = $_POST['service'];
		$company_code = $_POST['company_code'];

		$ci =& get_instance();

		$query = "select our_service_info.*, billing_info_service_category.category_description from our_service_info left join billing_info_service_category on billing_info_service_category.id = our_service_info.service_type where our_service_info.user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."' order by our_service_info.id";

		$selected_query = "select A.id from our_service_info AS A WHERE EXISTS (SELECT service from client_billing_info AS B WHERE company_code = '".$company_code."' AND A.id = B.service)";
		

		$selected_billing_info_service_category = "select billing_info_service_category.* from billing_info_service_category";

		$result = $ci->db->query($query);
		$selected_result = $ci->db->query($selected_query);
		$selected_billing_info_service_category = $ci->db->query($selected_billing_info_service_category);
		
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

        $selected_res = array();
        foreach($selected_result as $key => $row) {
            $selected_res[$key] = $row['id'];
        }

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

	public function check_number_of_share_person()
	{
		$field_type = $_POST["field_type"];
		$officer_id = $_POST["officer_id"];
		$transaction_master_id = $_POST["transaction_master_id"];
		$certID = $_POST["certID"];

		$latest_number_of_share = $this->transaction_model->checkLatestNumOfShare($field_type, $officer_id, $transaction_master_id, $certID);

		echo json_encode($latest_number_of_share);
	}

	public function check_edit_number_of_share_person()
	{
		$field_type = $_POST["field_type"];
		$officer_id = $_POST["officer_id"];
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_certificate_id = $_POST["transaction_certificate_id"];
		$certID = $_POST["certID"];

		$latest_number_of_share = $this->transaction_model->checkEditLatestNumOfShare($field_type, $officer_id, $transaction_master_id, $transaction_certificate_id, $certID);

		echo json_encode($latest_number_of_share);
	}

	public function save_share_transfer_latest_cert_number()
	{
		$transaction_master_id = $_POST["transaction_master_id"];
		$transaction_code = $_POST["transaction_code"];
		$transaction_task_id = $_POST["transaction_task_id"];
		$registration_no = $_POST["registration_no"];
		$company_code = $_POST["company_code"];

		$transferor_number_of_shares_to_transfer = $_POST["assign_number_of_share"];
		$transferor_new_number_of_share = $_POST["new_number_of_share"];
		$transferor_officer_id = $_POST["officer_id"];
		$transferor_field_type = $_POST["field_type"];
		$transferor_certificate_id = $_POST["certificate_id"];
		$transferor_sharetype = $_POST["sharetype"];
		$transferor_certificate = $_POST["certificate"];

		$transferee_new_number_of_share = $_POST["transferee_new_number_of_share"];
		$transferee_officer_id = $_POST["transferee_officer_id"];
		$transferee_field_type = $_POST["transferee_field_type"];
		$transferee_certificate_id = $_POST["transferee_certificate_id"];
		$transferee_sharetype = $_POST["transferee_sharetype"];
		$transferee_certificate = $_POST["transferee_certificate"];

		foreach($transferor_officer_id as $key => $value)
		{
			$transferor_item = array(
				'number_of_shares_to_transfer' => $transferor_number_of_shares_to_transfer[$key],
				'new_number_of_share' => $transferor_new_number_of_share[$key],
				'officer_id' => $transferor_officer_id[$key],
				'field_type' => $transferor_field_type[$key],
				'certificate_id' => $transferor_certificate_id[$key],
				'sharetype' => trim($transferor_sharetype[$key]),
				'certificate' => $transferor_certificate[$key]
			);
			$transferor_array[] = $transferor_item;
		}

		foreach($transferee_officer_id as $key => $value)
		{
			$transferee_item = array(
				'new_number_of_share' => $transferee_new_number_of_share[$key],
				'officer_id' => $transferee_officer_id[$key],
				'field_type' => $transferee_field_type[$key],
				'certificate_id' => $transferee_certificate_id[$key],
				'sharetype' => trim($transferee_sharetype[$key]),
				'certificate' => $transferee_certificate[$key]
			);
			$transferee_array[] = $transferee_item;
		}

		$transaction_share_transfer_record_query = $this->db->get_where("transaction_share_transfer_record", array("transaction_page_id" => $transaction_master_id));

		$transaction["transaction_page_id"] = $transaction_master_id;
		$transaction["transferor_array"] = json_encode($transferor_array);
		$transaction["transferee_array"] = json_encode($transferee_array);
		$transaction['last_edited_by'] = $this->session->userdata('user_id');

		if (!$transaction_share_transfer_record_query->num_rows())
		{
			$this->db->insert("transaction_share_transfer_record",$transaction);
			$this->save_audit_trail("Services", "Share Transfer", "Share transfer certificate no is added.");
		} 
		else 
		{
			$this->db->update("transaction_share_transfer_record",$transaction,array("transaction_page_id" => $transaction_master_id));
			$this->save_audit_trail("Services", "Share Transfer", "Share transfer certificate no is edited.");
		}

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
	}

	public function save_share_transfer()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$cancel_by_system["completed"] = 1;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = $this->encryption->encrypt(strtoupper($_POST['registration_no']));
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
			$this->db->update("transaction_master",$edit_transaction,array("id" => $_POST["transaction_master_id"]));
			$transaction_master_id = $_POST["transaction_master_id"]; //$this->session->userdata('transaction_id');
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
		$from_array = array();
		$to_array = array();

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
			if($_POST['officer_id'][$i] != null && $_POST['field_type'][$i] != null)
			{
				$new_number_of_share_from = (int)str_replace(',', '', $_POST['current_share'][$i]) - (int)str_replace(',', '', $_POST['share_transfer'][$i]);

				$buyback["transaction_page_id"] = $transaction_master_id; 
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
					array_push($from_array, $insert_transfer_from_id);

					$this->save_audit_trail("Services", "Share Transfer", "Share transfer member is added.");
				} 
				else 
				{	
					$this->db->delete('transaction_member_shares', array("id" => $_POST['transfer_id'][$i]));

					$buyback["transaction_id"] = $previous_member_share_info[0]["transaction_id"];
					$buyback["cert_status"] = $previous_member_share_info[0]["cert_status"];
					$this->db->insert("transaction_member_shares",$buyback);
					$insert_transfer_from_id = $this->db->insert_id();
					array_push($from_array, $insert_transfer_from_id);

					$this->save_audit_trail("Services", "Share Transfer", "Share transfer member is edited.");
				}

				$this->db->delete('transaction_transfer_member_id', array("transaction_id" => $transaction_master_id, "transfer_from_id" => $_POST['transfer_id'][$i]));

				$buyback_certificate["transaction_page_id"] = $transaction_master_id; 
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
				$buyback_certificate["previous_certificate_id"] = $_POST['certID'][$i];
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
		}

		for($i = 0; $i < count($_POST['to_officer_id']); $i++ )
		{
			if($_POST['to_officer_id'][$i] != null && $_POST['to_field_type'][$i] != null)
			{
				$allotment["transaction_page_id"] = $transaction_master_id; 
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
					array_push($to_array, $insert_transfer_to_id);
				} 
				else 
				{	
					
					$this->db->delete('transaction_member_shares', array("id" => $_POST['to_id'][$i]));

					$allotment["cert_status"] = $previous_member_share_info[0]["cert_status"];
					$allotment["transaction_id"] = $previous_member_share_info[0]["transaction_id"];
					$this->db->insert("transaction_member_shares",$allotment);
					$insert_transfer_to_id = $this->db->insert_id();
					array_push($to_array, $insert_transfer_to_id);
				}

				$this->db->delete('transaction_transfer_member_id', array("transaction_id" => $transaction_master_id, "transfer_to_id" => $_POST['to_id'][$i]));

				$certificate["transaction_page_id"] = $transaction_master_id; 
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
		}

		if(count($from_array) == 1)
		{
			if(count($to_array) == 1)
			{
				$transaction_transfer_member_id['transaction_id'] = $transaction_master_id;
				$transaction_transfer_member_id['transfer_from_id'] = $from_array[0];
				$transaction_transfer_member_id['transfer_to_id'] = $to_array[0];

				$this->db->insert("transaction_transfer_member_id",$transaction_transfer_member_id);
			}
			else if(count($to_array) > 1)
			{
				for($b = 0; $b < count($to_array); $b++)
				{
					$transaction_transfer_member_id['transaction_id'] = $transaction_master_id;
					$transaction_transfer_member_id['transfer_from_id'] = $from_array[0];
					$transaction_transfer_member_id['transfer_to_id'] = $to_array[$b];

					$this->db->insert("transaction_transfer_member_id",$transaction_transfer_member_id);
				}
			}
		}
		
		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		$this->data['transaction_member'] = $this->transaction_model->getTransactionClientTransferMemberInfo($transaction_master_id);

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', 'transaction_member' => $this->data['transaction_member'], "transaction_master_id" => $transaction_master_id, "transaction_code" => $transaction_code));
	}

	public function save_share_allotment()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$cancel_by_system["completed"] = 1;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = $this->encryption->encrypt(strtoupper($_POST['registration_no']));
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
			$this->db->update("transaction_master",$edit_transaction,array("id" => $_POST["transaction_master_id"]));
			$transaction_id = $_POST["transaction_master_id"]; //$this->session->userdata('transaction_id');
			$transaction_code = $_POST["transaction_code"];
		}

		$transaction_meeting_date['transaction_master_id'] = $transaction_id;
		$transaction_meeting_date['director_meeting_date'] = $_POST['director_meeting_date'];
		$transaction_meeting_date['director_meeting_time'] = $_POST['director_meeting_time'];
		$transaction_meeting_date['member_meeting_date'] = $_POST['member_meeting_date'];
		$transaction_meeting_date['member_meeting_time'] = $_POST['member_meeting_time'];
		$transaction_meeting_date['address_type'] = $_POST['address_type'];
		//registered_offis_address
		if(isset($_POST['registered_postal_code1']))
        {
            $transaction_meeting_date['registered_postal_code1'] = strtoupper($_POST['registered_postal_code1']);
        }
        else
        {
            $transaction_meeting_date['registered_postal_code1'] = "";
        }

        if(isset($_POST['registered_street_name1']))
        {
            $transaction_meeting_date['registered_street_name1'] = strtoupper($_POST['registered_street_name1']);
        }
        else
        {
            $transaction_meeting_date['registered_street_name1'] = "";
        }

        if(isset($_POST['registered_building_name1']))
        {
            $transaction_meeting_date['registered_building_name1'] = strtoupper($_POST['registered_building_name1']);
        }
        else
        {
            $transaction_meeting_date['registered_building_name1'] = "";
        }

        if(isset($_POST['registered_unit_no1']))
        {
            $transaction_meeting_date['registered_unit_no1'] = strtoupper($_POST['registered_unit_no1']);
        }
        else
        {
            $transaction_meeting_date['registered_unit_no1'] = "";
        }

        if(isset($_POST['registered_unit_no2']))
        {
            $transaction_meeting_date['registered_unit_no2'] = strtoupper($_POST['registered_unit_no2']);
        }
        else
        {
            $transaction_meeting_date['registered_unit_no2'] = "";
        }
        //Local_address
		if(isset($_POST['postal_code1']))
        {
            $transaction_meeting_date['postal_code1'] = strtoupper($_POST['postal_code1']);
        }
        else
        {
            $transaction_meeting_date['postal_code1'] = "";
        }

        if(isset($_POST['street_name1']))
        {
            $transaction_meeting_date['street_name1'] = strtoupper($_POST['street_name1']);
        }
        else
        {
            $transaction_meeting_date['street_name1'] = "";
        }
		$transaction_meeting_date['building_name1'] = strtoupper($_POST['building_name1']);
        $transaction_meeting_date['unit_no1'] = strtoupper($_POST['unit_no1']);
        $transaction_meeting_date['unit_no2'] = strtoupper($_POST['unit_no2']);
        //foreign_address
        if(isset($_POST['foreign_address1']))
        {
            $transaction_meeting_date['foreign_address1'] = strtoupper($_POST['foreign_address1']);
        }
        else
        {
            $transaction_meeting_date['foreign_address1'] = "";
        }
        if(isset($_POST['foreign_address2']))
        {
            $transaction_meeting_date['foreign_address2'] = strtoupper($_POST['foreign_address2']);
        }
        else
        {
            $transaction_meeting_date['foreign_address2'] = "";
        }
        if(isset($_POST['foreign_address3']))
        {
            $transaction_meeting_date['foreign_address3'] = strtoupper($_POST['foreign_address3']);
        }
        else
        {
            $transaction_meeting_date['foreign_address3'] = "";
        }

		$transaction_meeting_date_query = $this->db->get_where("transaction_meeting_date", array("transaction_master_id" => $transaction_id));

		if (!$transaction_meeting_date_query->num_rows())
		{
			$this->db->insert("transaction_meeting_date",$transaction_meeting_date);

		} 
		else 
		{
			$this->db->update("transaction_meeting_date",$transaction_meeting_date,array("transaction_master_id" => $transaction_id));
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

		$this->db->delete('transaction_client_member_share_capital', array("transaction_id" => $transaction_id));

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

			$transaction_share_capital = $this->db->get_where("transaction_client_member_share_capital", array("transaction_id" => $transaction_id, "company_code" => $_POST['company_code'], "class_id" => $_POST['class'][$i], "currency_id" => $_POST['currency'][$i], "other_class" => $share_capital["other_class"]));

			if (!$transaction_share_capital->num_rows())
			{
				$share_capital["transaction_id"] = $transaction_id;
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

			$allotment["transaction_page_id"] = $transaction_id; 
			$allotment["company_code"] = $_POST['company_code'];
			$allotment["client_member_share_capital_id"] = $transaction_share_capital_id;
			$allotment["officer_id"] = $_POST['officer_id'][$i];
			$allotment["field_type"] = $_POST['field_type'][$i];
			$allotment["transaction_type"] = $_POST['transaction_type'];
			$allotment["number_of_share"] = (int)str_replace(',', '', $_POST['number_of_share'][$i]);
			$allotment["amount_share"] = (float)str_replace(',', '', $_POST['amount_share'][$i]);
			$allotment["no_of_share_paid"] = (int)str_replace(',', '', $_POST['no_of_share_paid'][$i]);
			$allotment["amount_paid"] = (float)str_replace(',', '', $_POST['amount_paid'][$i]);

			$certificate["transaction_page_id"] = $transaction_id; 
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

			$this->save_audit_trail("Services", "Share Allotment", "Share allotment info is edited.");
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		$this->data['transaction_member'] = $this->transaction_model->getTransactionClientMember($transaction_id, $_POST['company_code']);

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', 'transaction_member' => $this->data['transaction_member'], "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function save_allotment()
	{
		$transaction_master_id = $_POST['transaction_master_id'];
		$_POST['officer_id'] = array_values($_POST['officer_id']);
		$_POST['class'] = array_values($_POST['class']);
		$_POST['currency'] = array_values($_POST['currency']);
		$_POST['number_of_share'] = array_values($_POST['number_of_share']);
		$_POST['amount_share'] = array_values($_POST['amount_share']);
		$_POST['amount_paid'] = array_values($_POST['amount_paid']);
		$_POST['field_type'] = array_values($_POST['field_type']);
		$_POST['no_of_share_paid'] = array_values($_POST['no_of_share_paid']);
		$_POST['member_share_id'] = array_values($_POST['member_share_id']);
		$_POST['certificate'] = array_values($_POST['certificate']);
		
		for($i = 0; $i < count($_POST['officer_id']); $i++ )
		{
			$transaction_id = "TR-".mt_rand(100000000, 999999999);

			if(isset($_POST['other_class'][$i]))
			{
				$share_capital["other_class"] = $_POST['other_class'][$i];
			}
			else
			{	
				$share_capital["other_class"] = "";
			}

			$transaction_share_capital = $this->db->get_where("transaction_client_member_share_capital", array("transaction_id" => $transaction_master_id, "company_code" => $_POST['company_code'], "class_id" => $_POST['class'][$i], "currency_id" => $_POST['currency'][$i], "other_class" => $share_capital["other_class"]));//$this->session->userdata("transaction_id")

			if (!$transaction_share_capital->num_rows())
			{
				$share_capital["transaction_id"] = $transaction_master_id;
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

			$allotment["transaction_page_id"] = $transaction_master_id; 
			$allotment["company_code"] = $_POST['company_code'];
			$allotment["client_member_share_capital_id"] = $transaction_share_capital_id;
			$allotment["officer_id"] = $_POST['officer_id'][$i];
			$allotment["field_type"] = $_POST['field_type'][$i];
			$allotment["transaction_type"] = $_POST['transaction_type'];
			$allotment["number_of_share"] = (int)str_replace(',', '', $_POST['number_of_share'][$i]);
			$allotment["amount_share"] = (float)str_replace(',', '', $_POST['amount_share'][$i]);
			$allotment["no_of_share_paid"] = (int)str_replace(',', '', $_POST['no_of_share_paid'][$i]);
			$allotment["amount_paid"] = (float)str_replace(',', '', $_POST['amount_paid'][$i]);

			$certificate["transaction_page_id"] = $transaction_master_id; 
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
			
			$allotment["transaction_id"] = $transaction_id;
			$allotment["cert_status"] = 1;
			$this->db->insert("transaction_member_shares",$allotment);

			$certificate["transaction_id"] = $transaction_id;
			$this->db->insert("transaction_certificate",$certificate);

			$this->save_audit_trail("Services", "Incorporation of new company", "Members is edited in incorporation services.");
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $transaction_master_id));

		$this->data['transaction_member'] = $this->transaction_model->getTransactionClientMember($transaction_master_id, $_POST['company_code']);

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

		$this->save_audit_trail("Services", "Incorporation of new company", "Members is deleted in incorporation services.");

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
		$client_billing_info_id = $_POST["client_billing_info_id"];
		$company_code = $_POST["transaction_company_code"];
		$transaction_master_id = $_POST["transaction_master_id"];

		$check_billing_service_id = $this->db->get_where("transaction_client_billing_info", array("transaction_id" => $transaction_master_id, "client_billing_info_id" => $client_billing_info_id, "company_code" => $company_code, "deleted"=> 0));

        if ($check_billing_service_id->num_rows())
        {	
        	$check_billing_service_id = $check_billing_service_id->result_array();

	        $check_billing_service_info = $this->db->get_where("billing_service", array("service" => $check_billing_service_id[0]["id"]));

	        if ($check_billing_service_info->num_rows())
	        {
	        	$check_billing_service_info = $check_billing_service_info->result_array();

	        	$check_billing_info = $this->db->get_where("billing", array("id" => $check_billing_service_info[0]["billing_id"], "company_code" => $company_code, "status" => 0));

	        	if(!$check_billing_info->num_rows())
	        	{
	        		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
					$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));
	        		echo json_encode(array("Status" => 1));
	        	}
	        	else
	        	{
	        		echo json_encode(array("Status" => 2));
	        	}
	  //       	$check_recur_billing_service_info = $this->db->get_where("recurring_billing_service", array("service" => $check_billing_service_id[0]["id"]));

	  //       	if (!$check_recur_billing_service_info->num_rows())
	  //       	{
	  //       		echo json_encode(array("Status" => 1));
	  //       	}
	  //       	else
	  //       	{
	  //       		echo json_encode(array("Status" => 2));
	  //       	}
			// }
	  //       else
	  //       {
	            //echo json_encode(array("Status" => 2));
	        }
	        else
	        {
	        	$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
				$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));
        		echo json_encode(array("Status" => 1));
	        }
	    }
	    else
	    {
	    	$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

	    	echo json_encode(array("Status" => 1));
	    }
	}

	public function add_setup_info()
	{
		$company_code = $_POST['company_code'];
		$transaction_master_id = $_POST['transaction_master_id'];
		$transaction_client_signing_info["transaction_id"] = $transaction_master_id;
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

		$transaction_client_contact_info["transaction_id"] = $transaction_master_id; 
		$transaction_client_contact_info['company_code'] = $_POST['company_code'];
		$transaction_client_contact_info['name'] = $_POST['contact_name'];

		$p = $this->db->get_where("transaction_client_signing_info", array("company_code" => $_POST['company_code'], "transaction_id" => $transaction_master_id));

		if (!$p->num_rows())
		{				
			$this->db->insert("transaction_client_signing_info",$transaction_client_signing_info);

			$this->save_audit_trail("Services", "Incorporation of new company", "Setup is added in incorporation services.");
		} 
		else 
		{	
			$this->db->where(array("company_code" => $_POST['company_code'], "transaction_id" => $transaction_master_id));
			$this->db->update("transaction_client_signing_info",$transaction_client_signing_info);

			$this->save_audit_trail("Services", "Incorporation of new company", "Setup is edited in incorporation services.");
		}

		$query = $this->db->get_where("transaction_client_contact_info", array("company_code" => $_POST['company_code'], "transaction_id" => $transaction_master_id));

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
			$this->db->where(array("company_code" => $_POST['company_code'], "transaction_id" => $transaction_master_id));
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

		$this->db->delete("transaction_client_setup_reminder",array('company_code'=>$_POST['company_code'], "transaction_id" => $transaction_master_id));

		if($_POST['select_reminder'] != null)
		{
			for($g = 0; $g < count($_POST['select_reminder']); $g++)
            {
            	$reminder["transaction_id"] = $transaction_master_id; 
            	$reminder['company_code'] = $_POST['company_code'];
            	$reminder['selected_reminder'] = $_POST['select_reminder'][$g];

            	$this->db->insert('transaction_client_setup_reminder', $reminder);
            }
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $transaction_master_id));

		if($this->session->userdata('qb_company_id') != "")
		{
			$client_query = $this->db->query("SELECT transaction_client.*, transaction_client_qb_id.currency_name FROM transaction_client LEFT JOIN transaction_client_qb_id ON transaction_client_qb_id.company_code = transaction_client.company_code WHERE transaction_client.company_code = '".$company_code."'");
			$client_array = $client_query->result_array();
			for($t = 0; $t < count($client_array); $t++)
			{
				$qb_status = $this->import_trans_client_to_quickbook($client_array[$t]["id"], $client_array[$t]["currency_name"]);
			}
		}
		else
		{
			$qb_status["Status"] = 1;
			$qb_status["message"] = 'Information Updated';
			$qb_status["title"] = 'Updated';
		}

		echo json_encode(array("Status" => $qb_status["Status"], 'message' => $qb_status["message"], 'title' => $qb_status["title"]));
	}

	public function get_transaction_share_transfer_record()
	{
		$transaction_master_id = $_POST['transaction_master_id'];

		$p = $this->db->query("select * from transaction_share_transfer_record where transaction_page_id = '".$transaction_master_id."'");

		if ($p->num_rows() > 0) 
		{
			echo json_encode(array("status" => 1));
		}
		else
		{
			echo json_encode(array("status" => 2));
		}
	}

	public function send_common_seal_email_under_services()
	{
		$transaction_master_id = $_POST["transaction_master_id"];

		$users_list = $this->db->get_where("users", array("id" => $this->session->userdata("user_id")));
		$users_list = $users_list->result_array();

		$this->db->select('transaction_master.*, transaction_purchase_common_seal_info.*, transaction_purchase_common_seal_customer_info.*, client.registration_no, client.company_name, transaction_common_seal_vendor.*');
        $this->db->from('transaction_master');
        $this->db->join('transaction_purchase_common_seal_info', 'transaction_purchase_common_seal_info.transaction_id = transaction_master.id', 'left');
        $this->db->join('transaction_purchase_common_seal_customer_info', 'transaction_purchase_common_seal_customer_info.transaction_id = transaction_master.id', 'left');
        $this->db->join('client', 'client.company_code = transaction_purchase_common_seal_customer_info.company_code', 'left');
        $this->db->join('transaction_common_seal_vendor', 'transaction_common_seal_vendor.id = transaction_purchase_common_seal_info.vendor', 'left');
        $this->db->where('transaction_master.id', $transaction_master_id);
		$transaction_master_list = $this->db->get();
		$transaction_master_list = $transaction_master_list->result_array();

		$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');

        $apiInstance = new SendinBlue\Client\Api\SMTPApi(
			// If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
			// This is optional, `GuzzleHttp\Client` will be used as default.
			new GuzzleHttp\Client(),
			$config
        );
		$sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail(); // \SendinBlue\Client\Model\SendSmtpEmail | Values to send a transactional email
        $sendSmtpEmail['subject'] = "Order of Self-inking round stamp or Common Seal (".$transaction_master_list[0]["transaction_code"].")";
        $sender_email = json_decode('{"name":"ACUMEN ALPHA ADVISORY","email":"corpsec@aaa-global.com"}', true);
        $sendSmtpEmail['sender'] = $sender_email;
        $sendSmtpEmail['to'] = array(array("email"=> trim($transaction_master_list[0]["vendor_email"]))); //json_decode('[{"email":"justin@aaa-global.com"}]', true);//$get_user_list[0]["email"]
        $sendSmtpEmail['cc'] = array(array("email" => trim($users_list[0]["email"]))); //$users_list[0]["email"]

        $tr_common_seal_detail = "";
        foreach($transaction_master_list as $key => $value)
    	{
			$tr_common_seal_detail = $tr_common_seal_detail . '<tr style="border: 1px solid black;">
                        <td style="border: 1px solid black; width: 484px; height: 20px;">
                            <p>'.$this->encryption->decrypt($value["company_name"]).'</p>
                        </td>
                        <td style="border: 1px solid black; width: 214px; height: 20px;">
                            <p>'.$this->encryption->decrypt($value["registration_no"]).'</p>
                        </td>
                        <td style="border: 1px solid black; width: 214px; height: 20px;">
                            <p>'.$value["order_for"].'</p>
                        </td>
                    </tr>';
        }

        $common_seal_detail = '
		            <table style="width: 609px; border: 1px solid black; border-collapse: collapse;">
		                <tbody>
		                    <tr style="border: 1px solid black;">
		                        <td style="border: 1px solid black; width: 484px; height: 20px;">
		                            <p><strong>Company Name</strong></p>
		                        </td>
		                        <td style="border: 1px solid black; width: 214px; height: 20px;">
		                            <p><strong>UEN</strong></p>
		                        </td>
		                        <td style="border: 1px solid black; width: 214px; height: 20px;">
		                            <p><strong>Order for</strong></p>
		                        </td>
		                    </tr>
		                    '.$tr_common_seal_detail.'
		                </tbody>
		            </table>';

		$parse_data = array(
            '$vendor_name' => $transaction_master_list[0]["vendor_name"],
            '$common_seal_table' => $common_seal_detail
        );
        $msg = file_get_contents('./themes/default/views/email_templates/acknowledgement_page_email.html');
        $message = $this->parser->parse_string($msg, $parse_data, true);

        $sendSmtpEmail['htmlContent'] = $message;
		try {
			$result = $apiInstance->sendTransacEmail($sendSmtpEmail);
			foreach($transaction_master_list as $key => $value)
    		{
    			$record["transaction_id"] = $transaction_master_id;
    			$record["company_name"] = $this->encryption->decrypt($value["company_name"]);
    			$record["uen"] = $this->encryption->decrypt($value["registration_no"]);
    			$record["order_for"] = $value["order_for"];
    			$record["send_by"] = $this->session->userdata("user_id");
    			$this->db->insert("purchase_common_seal_and_stamp_record", $record);
			}

			$master["status"] = 2;
			$master["service_status"] = 2;
			$master["effective_date"] = $transaction_master_list[0]["date"];
			$this->db->update("transaction_master", $master,array("id" => $transaction_master_id));

			$get_company_code_query = $this->db->query("SELECT transaction_purchase_common_seal_customer_info.company_code, client.company_name FROM transaction_purchase_common_seal_customer_info LEFT JOIN client ON client.company_code = transaction_purchase_common_seal_customer_info.company_code WHERE transaction_purchase_common_seal_customer_info.transaction_id = '".$transaction_master_id."' GROUP BY transaction_purchase_common_seal_customer_info.company_code");
            if ($get_company_code_query->num_rows() > 0) 
            {
                foreach (($get_company_code_query->result_array()) as $row) 
                {
					$this->update_service_engagment_for_other_services($row["company_code"], $transaction_master_id);
				}
			}

			$this->save_audit_trail("Services", "Purchase of Common Seal & Self inking stamp", "Order for purchase of common seal and self inking stamp email is sent.");

		 	echo json_encode(array('status' => 1));
		} catch (Exception $e) {
			echo json_encode(array('status' => 2));
			//echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
		}
	}

	public function send_common_seal_email()
	{
		$common_seal_and_stamp_array = $_POST["common_seal_and_stamp_array"];
		$common_seal_vendor_id = $_POST["common_seal_vendor_id"];
		$transaction_master_id_for_acknowledgement = $_POST["transaction_master_id_for_acknowledgement"];

		if($transaction_master_id_for_acknowledgement != "")
		{
			if(count($common_seal_and_stamp_array) != 0)
			{
				$users_list = $this->db->get_where("users", array("id" => $this->session->userdata("user_id")));
			    $users_list = $users_list->result_array();

				$transaction_common_seal_vendor_list = $this->db->get_where("transaction_common_seal_vendor", array("id" => $common_seal_vendor_id));
			    $transaction_common_seal_vendor_list = $transaction_common_seal_vendor_list->result_array();

			    $this->db->select('transaction_master.transaction_code, transaction_client.registration_no, transaction_client.company_name');
		        $this->db->from('transaction_master');
		        $this->db->join('transaction_client', 'transaction_client.transaction_id = transaction_master.id', 'left');
		        $this->db->where('transaction_master.id', $transaction_master_id_for_acknowledgement);
				$transaction_master_list = $this->db->get();
				$transaction_master_list = $transaction_master_list->result_array();

			    $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');

		        $apiInstance = new SendinBlue\Client\Api\SMTPApi(
					// If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
					// This is optional, `GuzzleHttp\Client` will be used as default.
					new GuzzleHttp\Client(),
					$config
		        );
				$sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail(); // \SendinBlue\Client\Model\SendSmtpEmail | Values to send a transactional email
		        $sendSmtpEmail['subject'] = "Order of Self-inking round stamp or Common Seal (".$transaction_master_list[0]["transaction_code"].")";
		        $sender_email = json_decode('{"name":"ACUMEN ALPHA ADVISORY","email":"corpsec@aaa-global.com"}', true);
		        $sendSmtpEmail['sender'] = $sender_email;
		        $sendSmtpEmail['to'] = array(array("email"=> trim($transaction_common_seal_vendor_list[0]["vendor_email"]))); //json_decode('[{"email":"justin@aaa-global.com"}]', true);//$get_user_list[0]["email"]
		        $sendSmtpEmail['cc'] = array(array("email" => trim($users_list[0]["email"]))); //$users_list[0]["email"]

		        $tr_common_seal_detail = "";
		        foreach($common_seal_and_stamp_array as $key => $value)
	        	{
					$tr_common_seal_detail = $tr_common_seal_detail . '<tr style="border: 1px solid black;">
		                        <td style="border: 1px solid black; width: 484px; height: 20px;">
		                            <p>'.$this->encryption->decrypt($transaction_master_list[0]["company_name"]).'</p>
		                        </td>
		                        <td style="border: 1px solid black; width: 214px; height: 20px;">
		                            <p>'.$this->encryption->decrypt($transaction_master_list[0]["registration_no"]).'</p>
		                        </td>
		                        <td style="border: 1px solid black; width: 214px; height: 20px;">
		                            <p>'.$value.'</p>
		                        </td>
		                    </tr>';
		        }

		        $common_seal_detail = '
		            <table style="width: 609px; border: 1px solid black; border-collapse: collapse;">
		                <tbody>
		                    <tr style="border: 1px solid black;">
		                        <td style="border: 1px solid black; width: 484px; height: 20px;">
		                            <p><strong>Company Name</strong></p>
		                        </td>
		                        <td style="border: 1px solid black; width: 214px; height: 20px;">
		                            <p><strong>UEN</strong></p>
		                        </td>
		                        <td style="border: 1px solid black; width: 214px; height: 20px;">
		                            <p><strong>Order for</strong></p>
		                        </td>
		                    </tr>
		                    '.$tr_common_seal_detail.'
		                </tbody>
		            </table>';

				$parse_data = array(
		            '$vendor_name' => $transaction_common_seal_vendor_list[0]["vendor_name"],
		            '$common_seal_table' => $common_seal_detail
		        );
		        $msg = file_get_contents('./themes/default/views/email_templates/acknowledgement_page_email.html');
		        $message = $this->parser->parse_string($msg, $parse_data, true);

		        $sendSmtpEmail['htmlContent'] = $message;
				try {
					$result = $apiInstance->sendTransacEmail($sendSmtpEmail);
					foreach($common_seal_and_stamp_array as $key => $value)
	        		{
	        			$record["transaction_id"] = $transaction_master_id_for_acknowledgement;
	        			$record["company_name"] = $this->encryption->decrypt($transaction_master_list[0]["company_name"]);
	        			$record["uen"] = $this->encryption->decrypt($transaction_master_list[0]["registration_no"]);
	        			$record["order_for"] = $value;
	        			$record["send_by"] = $this->session->userdata("user_id");
	        			$this->db->insert("purchase_common_seal_and_stamp_record", $record);
					}

					$transaction_tasks_result = $this->db->query("select transaction_tasks.transaction_task from transaction_master left join transaction_tasks on transaction_tasks.id = transaction_master.transaction_task_id where transaction_master.id = '".$_POST["transaction_master_id_for_acknowledgement"]."'");
        			$transaction_tasks_array = $transaction_tasks_result->result_array();
					
					$this->save_audit_trail("Services", $transaction_tasks_array[0]["transaction_task"], "Order for purchase of common seal and self inking stamp email is sent.");

				 	echo json_encode(array('status' => 1));
				} catch (Exception $e) {
					echo json_encode(array('status' => 2));
					echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
				}
			}
			else
			{
				echo json_encode(array('status' => 3));
			}
		}
		else
		{
			echo json_encode(array('status' => 4));
		}
	}

	public function open_acknowledgement_page()
	{
		$transaction_master_id_for_acknowledgement = $this->session->userdata('transaction_master_id_for_acknowledgement');
		$bc = array(array('link' => '#', 'page' => 'Acknowledgement'));
        $meta = array('page_title' => 'Acknowledgement', 'bc' => $bc, 'page_name' => 'Acknowledgement');

        $this->db->select('registration_no, company_name');
        $this->db->from('transaction_client');
        $this->db->where('transaction_id', $transaction_master_id_for_acknowledgement);
		$transaction_client = $this->db->get();
		$transaction_client_array = $transaction_client->result_array();

		$this->data["transaction_master_id"] = $transaction_master_id_for_acknowledgement;
		$this->data["client_name"] = $this->encryption->decrypt($transaction_client_array[0]["company_name"]);
		$this->data['transaction_common_seal_vendor_list'] = $this->transaction_model->getTransactionCommonSealVendorList();
		//$this->session->unset_userdata('transaction_master_id_for_acknowledgement'); //delete

        $this->page_construct('transaction/acknowledgement_page.php', $meta, $this->data);
	}

	public function set_transaction_master_id()
	{
		$transaction_master_id = $_POST['transaction_master_id'];
		if($transaction_master_id != NULL)
        {
        	$this->session->set_userdata('transaction_master_id_for_acknowledgement', $transaction_master_id);
	        
			echo json_encode(array("status" => 1));
		}
	}
	
	public function lodge_transaction()
	{
		$transaction_master_id = $_POST['transaction_master_id'];
		$company_code = $_POST['company_code'];
		$transaction_code = $this->getTransactionCode();
		$effective_date = $_POST['effective_date'];
		$lodgement_date = $_POST['lodgement_date'];
		$client_code = strtoupper($_POST['client_code']);
		$registration_no = $this->encryption->encrypt(trim(strtoupper($_POST['registration_no'])));
		$transaction_task = $_POST['transaction_task'];
		$transaction_status = $_POST['tran_status'];
		$cancellation_reason = $_POST['cancellation_reason'];
		//Controller
		$radio_confirm_registrable_controller = $_POST['radio_confirm_registrable_controller'];
		$date_of_the_conf_received = $_POST['date_of_the_conf_received'];
		$date_of_entry_or_update = $_POST['date_of_entry_or_update'];

		if($transaction_task == 1)
		{
			//client
			if($transaction_status == 2)
			{
				$check_client = $this->db->get_where("client", array("company_code" => $company_code, "firm_id" => $this->session->userdata('firm_id'), "deleted" => 0));

				$this->db->select('firm_id, acquried_by, company_code, client_code, registration_no, company_name, former_name, incorporation_date, company_type, status, activity1, description1, activity2, description2, registered_address, our_service_regis_address_id, postal_code, street_name, building_name, unit_no1, unit_no2, auto_generate, deleted, created_by, created_at');
		        $this->db->from('transaction_client');
		        $this->db->where('company_code', $company_code);
		        $this->db->where('transaction_id', $transaction_master_id);
				$check_transaction_client = $this->db->get();

				if (!$check_client->num_rows())
				{	
					foreach($check_transaction_client->result() as $r) {
						$r->client_code = $client_code;
						$r->registration_no = $registration_no;
						$r->change_name_effective_date = "";
						$r->incorporation_date = $lodgement_date;
						$r->client_country_of_incorporation = "SINGAPORE";
						$r->client_statutes_of = "COMPANIES ACT (CHAPTER 50)";
						$r->client_coporate_entity_name = "ACRA";
				        $this->db->insert("client",$r);
				    }
				} 
				else 
				{
					foreach($check_transaction_client->result() as $r) {
						$r->client_code = $client_code;
						$r->registration_no = $registration_no;
						$r->change_name_effective_date = "";
						$r->incorporation_date = $lodgement_date;
						$r->client_country_of_incorporation = "SINGAPORE";
						$r->client_statutes_of = "COMPANIES ACT (CHAPTER 50)";
						$r->client_coporate_entity_name = "ACRA";
				    	$this->db->update("client",$r,array("company_code" =>  $company_code, "firm_id" => $this->session->userdata('firm_id'), "deleted" => 0));
				    }
				}

				$this->db->select('qb_company_id, company_code, qb_customer_id, currency_name, qb_json_data, created_at');
		        $this->db->from('transaction_client_qb_id');
		        $this->db->where('company_code', $company_code);
				$check_transaction_client_qb_id = $this->db->get();

				if ($check_transaction_client_qb_id->num_rows())
				{	
					$this->db->delete("client_qb_id", array('company_code'=> $company_code));
					foreach($check_transaction_client_qb_id->result() as $r) {
						$this->db->insert("client_qb_id", $r);
				    }
				} 

				$transaction_client_master["client_code"] = $client_code;
				$transaction_client_master["registration_no"] = $registration_no;
				$this->db->update("transaction_client", $transaction_client_master,array("company_code" => $company_code, "transaction_id" => $transaction_master_id));
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
		        $this->db->where('class_id != 0');
		        $this->db->where('currency_id != 0');
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

				$this->db->select('company_code, officer_id, field_type, date_of_registration, date_of_notice, is_confirm_by_reg_controller, confirmation_received_date, date_of_entry, date_of_cessation, supporting_document, deleted'); //, date_of_birth, nationality_name, address
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

				if ($check_transaction_filing->num_rows())
				{
					$array = explode('/',$lodgement_date);
					$tmp = $array[0];
					$array[0] = $array[1];
					$array[1] = $tmp;
					unset($tmp);
					$date_2 = implode('/', $array);
					$time = strtotime($date_2);
					$newformat = date('m/d/Y',$time);

					$check_transaction_filing = $check_transaction_filing->result_array();

					$new_filing['company_code'] = $company_code;
					//$futureDate=date('d-m-Y', strtotime('+1 year', strtotime($_POST['year_end'])) );
					$new_filing['year_end'] = date('d F Y', strtotime($check_transaction_filing[0]['year_end']));
					$new_filing['ar_filing_date'] = "";
					$new_filing['financial_year_period_id'] = $check_transaction_filing[0]['financial_year_period'];
					$new_filing['financial_year_period1'] = date('d F Y', $time);
					$new_filing['financial_year_period2'] = date('d F Y', strtotime($check_transaction_filing[0]['year_end']));
					$new_filing['175_extended_to'] = 0;
					$new_filing["201_extended_to"] = 0;
					//$new_filing["197_extended_to"] = 0;

					$latest_year_end = date('Y-m-d', strtotime($check_transaction_filing[0]['year_end']));

					$year_end_date = new DateTime($latest_year_end);

					if(date('Y-m-d', strtotime("8/31/2018")) > date('Y-m-d', strtotime($check_transaction_filing[0]['year_end']))) 
					{
						$new_format_lodgement_date = new DateTime($newformat);
						// We extract the day of the month as $start_day
					    $new_due_date_175 = $this->MonthShifter($new_format_lodgement_date,15)->format(('Y-m-d'));
						$new_filing['due_date_175'] = date('d F Y', strtotime($new_due_date_175));

						// We extract the day of the month as $start_day
					    $date_201 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

						$new_filing['due_date_201'] =  date("t F Y", strtotime($date_201));

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
				}

			    //billing
			    $check_client_billing_info = $this->db->get_where("client_billing_info", array("company_code" => $company_code));

				$this->db->select('client_billing_info_id, company_code, service, invoice_description, amount, currency, unit_pricing, servicing_firm, deleted, created_at');
				$this->db->from('transaction_client_billing_info');
		        $this->db->where('company_code', $company_code);
		        $this->db->where('transaction_id', $transaction_master_id);
				$check_transaction_client_billing_info = $this->db->get();

				//$this->db->delete("client_billing_info",array('company_code'=>$company_code));

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
				
				if($check_transaction_client_contact_info->num_rows())
				{
					if (!$check_client_contact_info->num_rows())
					{
				        $this->db->insert("client_contact_info",$f);
				        $client_contact_info_id = $this->db->insert_id();
					} 
					else 
					{
				    	$this->db->update("client_contact_info",$f,array("company_code" =>  $company_code));
				    	$client_contact_info_id = $check_client_contact_info->result_array()[0]["id"];
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
				
				if (!$check_client_setup_reminder->num_rows())
				{
					foreach($check_transaction_client_setup_reminder->result() as $g) {
				        $this->db->insert("client_setup_reminder",$g);
				    }
				} 
				else 
				{
					foreach($check_transaction_client_setup_reminder->result() as $r) {
				    	$this->db->update("client_setup_reminder",$r,array("company_code" =>  $company_code));
				    }
					
				}

				$this->open_acknowledgement_page();
			}
		}
		else if($transaction_task == 2)
		{
			if($transaction_status == 2)
			{
			//officer
				$check_client_officers = $this->db->get_where("client_officers", array("company_code" => $company_code));

				$this->db->select('id, company_code, position, alternate_of, officer_id, field_type, date_of_appointment, date_of_cessation, retiring, created_at');
		        $this->db->from('transaction_client_officers');
		        $this->db->where('company_code', $company_code);
		        $this->db->where('transaction_id', $transaction_master_id);
				$check_transaction_client_officers = $this->db->get();

				foreach($check_transaction_client_officers->result() as $r) 
				{
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
		}
		else if($transaction_task == 3)
		{
			if($transaction_status == 2)
			{
				//officer
				$check_client_officers = $this->db->get_where("client_officers", array("company_code" => $company_code));

				$this->db->select('id, company_code, position, alternate_of, officer_id, field_type, date_of_appointment, date_of_cessation, retiring, created_at');
		        $this->db->from('transaction_client_officers');
		        $this->db->where('company_code', $company_code);
		        $this->db->where('transaction_id', $transaction_master_id);
				$check_transaction_client_officers = $this->db->get();

				//$this->db->delete("client_officers",array('company_code'=>$company_code));

				foreach($check_transaction_client_officers->result() as $r) 
				{
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
						$k['date_of_appointment'] = $lodgement_date;
						$this->db->insert("client_officers",$k);
					}
					else if($r->date_of_appointment != "")
					{
						$this->db->update("client_officers",$k,array("company_code" => $r->company_code, "officer_id" => $r->officer_id, "field_type" => $r->field_type, "date_of_cessation" => ''));
					}
			    }
			}
		}
		else if($transaction_task == 4)
		{
			if($transaction_status == 2)
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

				echo json_encode($data);

				$this->db->update("client", $data,array("company_code" => $company_code, "deleted" => 0)); //, "firm_id" => $this->session->userdata('firm_id')
				$this->update_service_engagment_for_other_services($company_code, $transaction_master_id);

				if($this->session->userdata('refresh_token_value'))
	    		{
					$client_query_qb = $this->db->query("SELECT client.*, client_qb_id.currency_name FROM client LEFT JOIN client_qb_id ON client_qb_id.company_code = client.company_code WHERE client.company_code = '".$company_code."'");
					$client_array_qb = $client_query_qb->result_array();

					for($v = 0; $v < count($client_array_qb); $v++)
					{
						$this->update_client_to_quickbook($client_array_qb[$v]["id"], $client_array_qb[$v]["currency_name"]);
					}
				}
			}
		}
		else if($transaction_task == 5)
		{
			if($transaction_status == 2)
			{
				$this->db->select('transaction_change_biz_activity.*');
		        $this->db->from('transaction_change_biz_activity');
		        $this->db->where('transaction_id', $transaction_master_id);
				$check_transaction_change_biz_activity = $this->db->get();

				$check_transaction_change_biz_activity = $check_transaction_change_biz_activity->result_array();

				$data["activity1"] = $check_transaction_change_biz_activity[0]["activity1"];
				$data["description1"] = $check_transaction_change_biz_activity[0]["description1"];
				$data["activity2"] = $check_transaction_change_biz_activity[0]["activity2"];
				$data["description2"] = $check_transaction_change_biz_activity[0]["description2"];

				$this->db->update("client", $data,array("company_code" => $company_code, "deleted" => 0)); //, "firm_id" => $this->session->userdata('firm_id')
				$this->update_service_engagment_for_other_services($company_code, $transaction_master_id);
			}
		}
		else if($transaction_task == 6)
		{
			if($transaction_status == 2)
			{
				$this->db->select('transaction_change_fye.*');
		        $this->db->from('transaction_change_fye');
		        $this->db->where('transaction_id', $transaction_master_id);
				$check_transaction_change_FYE = $this->db->get();

				$check_transaction_change_FYE = $check_transaction_change_FYE->result_array();

				$filing_info = $this->db->query("select company_code, year_end, agm, due_date_175, 175_extended_to, due_date_201, 201_extended_to from filing where company_code='".$company_code."' order by filing.id DESC LIMIT 2");
				$filing_info = $filing_info->result_array();
				if(count($filing_info) > 1)
				{
					$previous_year_end = date('d F Y', strtotime('+1 day', strtotime($filing_info[1]["year_end"])));
				}
				else
				{
					$client_info = $this->db->query("select * from client where company_code='".$company_code."'");
					$client_info = $client_info->result_array();
					$array = explode('/',$client_info[0]["incorporation_date"]);
					$tmp = $array[0];
					$array[0] = $array[1];
					$array[1] = $tmp;
					unset($tmp);
					$date_2 = implode('/', $array);
					$time = strtotime($date_2);
					$previous_year_end = date('d F Y', $time);;
				}

				$new_filing['company_code'] = $company_code;
				$new_filing['year_end'] = date('d F Y', strtotime($check_transaction_change_FYE[0]['new_year_end']));
				$new_filing['ar_filing_date'] = "";
				$new_filing['financial_year_period_id'] = $check_transaction_change_FYE[0]['financial_year_period'];
				$new_filing['financial_year_period1'] = $previous_year_end;
				$new_filing['financial_year_period2'] = date('d F Y', strtotime($check_transaction_change_FYE[0]['new_year_end']));
				$new_filing['175_extended_to'] = 0;
				$new_filing["201_extended_to"] = 0;
				$new_filing["change_info"] = 1;

				$latest_year_end = date('Y-m-d', strtotime($check_transaction_change_FYE[0]['new_year_end']));

				$year_end_date = new DateTime($latest_year_end);

				if(date('Y-m-d', strtotime("8/31/2018")) > date('Y-m-d', strtotime($check_transaction_change_FYE[0]['new_year_end'])))
				{

					$array = explode('/',$effective_date);
					$tmp = $array[0];
					$array[0] = $array[1];
					$array[1] = $tmp;
					unset($tmp);
					$date_2 = implode('/', $array);
					$time = strtotime($date_2);
					$newformat = date('m/d/Y',$time);

					$new_format_effective_date = new DateTime($newformat);
					// We extract the day of the month as $start_day
				    $new_due_date_175 = $this->MonthShifter($new_format_effective_date,15)->format(('Y-m-d'));
					$new_filing['due_date_175'] = date('d F Y', strtotime($new_due_date_175));

					// We extract the day of the month as $start_day
				    $date_201 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

					$new_filing['due_date_201'] =  date("t F Y", strtotime($date_201));

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

				$last_row_filing = $this->db->select('*')->where("company_code", $company_code)->order_by('id',"desc")->limit(1)->get('filing')->result_array();

				$this->db->update("filing", $new_filing,array("id" => $last_row_filing[0]["id"], "company_code" => $company_code));

				$this->update_service_engagment_for_other_services($company_code, $transaction_master_id);
			}
		}
		else if($transaction_task == 7)
		{
			if($transaction_status == 2)
			{
				$check_client_officers = $this->db->get_where("client_officers", array("company_code" => $company_code));

				$this->db->select('id, company_code, position, alternate_of, officer_id, field_type, date_of_appointment, date_of_cessation, retiring, appoint_resign_flag, created_at');
		        $this->db->from('transaction_client_officers');
		        $this->db->where('company_code', $company_code);
		        $this->db->where('transaction_id', $transaction_master_id);
				$check_transaction_client_officers = $this->db->get();

			    foreach($check_transaction_client_officers->result() as $r) 
			    {
					$k['company_code'] = $r->company_code;
					$k['position'] = $r->position;
					$k['alternate_of'] = $r->alternate_of;
					$k['officer_id'] = $r->officer_id;
					$k['field_type'] = $r->field_type;
					$k['date_of_appointment'] = $r->date_of_appointment;
					$k['date_of_cessation'] = $r->date_of_cessation;
					$k['retiring'] = $r->retiring;
					$k['created_at'] = $r->created_at;

					if($r->appoint_resign_flag == "appoint")
					{
						$this->db->insert("client_officers",$k);
					}
					else if($r->appoint_resign_flag == "resign")
					{
						$this->db->update("client_officers",$k,array("company_code" => $r->company_code, "officer_id" => $r->officer_id, "field_type" => $r->field_type, "date_of_cessation" => ''));
					}
			    }
			    $this->update_service_engagment_for_other_services($company_code, $transaction_master_id);
			}
		}
		else if($transaction_task == 8)
		{
			if($transaction_status == 2)
			{
				$this->db->select('transaction_issue_dividend.*');
		        $this->db->from('transaction_issue_dividend');
		        $this->db->where('transaction_id', $transaction_master_id);
				$check_transaction_issue_dividend = $this->db->get();

			    foreach($check_transaction_issue_dividend->result() as $r) 
			    {
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
					$issue_dividend_id = $this->db->insert_id();
			    }
			    $transaction_issue_dividend = $check_transaction_issue_dividend->result_array();
			    $transaction_issue_dividend_id = $transaction_issue_dividend[0]['id'];

			    $this->db->select('transaction_dividend_list.*');
		        $this->db->from('transaction_dividend_list');
		        $this->db->where('transaction_issue_dividend_id', $transaction_issue_dividend_id);
				$check_transaction_dividend_list = $this->db->get();

			    foreach($check_transaction_dividend_list->result() as $r) 
			    {
					$b['issue_dividend_id'] = $issue_dividend_id;
					$b['payment_voucher_no'] = $r->payment_voucher_no;
					$b['officer_id'] = $r->officer_id;
					$b['field_type'] = $r->field_type;
					$b['shareholder_name'] = $r->shareholder_name;
					$b['number_of_share'] = $r->number_of_share;
					$b['devidend_paid'] = $r->devidend_paid;

					$this->db->insert("issue_dividend_list",$b);
			    }
			}
		}
		else if($transaction_task == 9)
		{
			if($transaction_status == 2)
			{
				$this->db->select('transaction_issue_director_fee.*');
		        $this->db->from('transaction_issue_director_fee');
		        $this->db->where('transaction_id', $transaction_master_id);
				$check_transaction_issue_director_fee = $this->db->get();

			    foreach($check_transaction_issue_director_fee->result() as $r) 
			    {
					$k['registration_no'] = $r->registration_no;
					$k['declare_of_fye'] = $r->declare_of_fye;
					$k['resolution_date'] = $r->resolution_date;
					$k['meeting_date'] = $r->meeting_date;
					$k['notice_date'] = $r->notice_date;

					$this->db->insert("issue_director_fee",$k);
					$issue_director_fee_id = $this->db->insert_id();
			    }
			    $transaction_issue_director_fee = $check_transaction_issue_director_fee->result_array();
			    $transaction_issue_director_fee_id = $transaction_issue_director_fee[0]['id'];

			    $this->db->select('transaction_director_fee_list.*');
		        $this->db->from('transaction_director_fee_list');
		        $this->db->where('transaction_issue_director_fee_id', $transaction_issue_director_fee_id);
				$check_transaction_issue_director_fee_list = $this->db->get();

			    foreach($check_transaction_issue_director_fee_list->result() as $r) 
			    {
					$b['issue_director_fee_id'] = $issue_director_fee_id;
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

		}
		else if($transaction_task == 10)
		{
			if($transaction_status == 2)
			{
				//get all the share transfer record 
				$q = $this->db->query('select * from transaction_share_transfer_record where transaction_page_id = '.$transaction_master_id);

		        if ($q->num_rows() > 0) 
		        {
		            $q = $q->result_array();

		            $id = $q[0]["id"];
		            $transaction_page_id = $q[0]["transaction_page_id"];
		            $transferor_array = json_decode($q[0]["transferor_array"]);
		            $transferee_array = json_decode($q[0]["transferee_array"]);
		            $index = 0; $total_no_of_share = 0; $total_amount_share = 0; $per_share = 0;

		            //check the total number of share
		            $member_query = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid from member_shares left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id HAVING sum(member_shares.number_of_share) != 0');
		            $member_query = $member_query->result_array();

		            for($p = 0; $p < count($member_query); $p++)
		            {
		                $total_no_of_share += (int)str_replace(',', '',$member_query[$p]['number_of_share']);
		                $total_amount_share += (int)str_replace(',', '',$member_query[$p]['amount_share']);
		            }
		            //calculate how much per share
		            $per_share = $total_amount_share/$total_no_of_share;

		            //the share movement for transferor array
		            foreach (($transferor_array) as $row)
		            {
		                if($row->number_of_shares_to_transfer != "" && $row->number_of_shares_to_transfer != "0")
		                {
		                    if($row->new_number_of_share == "0")
		                    {
		                        $transaction_id = "TR-".mt_rand(100000000, 999999999);
		                        $previous_cert_query = $this->db->query('select * from certificate where id = '.$row->certificate_id);
		                        $previous_cert_query = $previous_cert_query->result_array();

		                        $previous_cert_status['status'] = 2;
		                        $this->db->update("certificate",$previous_cert_status,array("id" => $row->certificate_id));

		                        //member_shares
		                        $member_share_info["company_code"] = $previous_cert_query[0]["company_code"];
		                        $member_share_info["client_member_share_capital_id"] = $previous_cert_query[0]["client_member_share_capital_id"];
		                        $member_share_info["officer_id"] = $row->officer_id;
		                        $member_share_info["field_type"] = $row->field_type;
		                        $member_share_info["transaction_id"] = $transaction_id;
		                        $member_share_info["number_of_share"] = -(str_replace(',', '',$row->number_of_shares_to_transfer));
		                        $member_share_info["amount_share"] = -((float)str_replace(',', '',$row->number_of_shares_to_transfer) * $per_share);
		                        $member_share_info["no_of_share_paid"] = -(str_replace(',', '',$row->number_of_shares_to_transfer));
		                        $member_share_info["amount_paid"] = -((float)str_replace(',', '',$row->number_of_shares_to_transfer) * $per_share);
		                        $member_share_info["transaction_date"] = $lodgement_date;
		                        $member_share_info["transaction_type"] = "Transfer";
		                        $member_share_info["consideration"] = 0;
		                        $member_share_info["cert_status"] = 1;
		                        $this->db->insert("member_shares",$member_share_info);

		                        //certificate
		                        $cert_info["company_code"] = $previous_cert_query[0]["company_code"];
		                        $cert_info["client_member_share_capital_id"] = $previous_cert_query[0]["client_member_share_capital_id"];
		                        $cert_info["officer_id"] = $row->officer_id;
		                        $cert_info["field_type"] = $row->field_type;
		                        $cert_info["transaction_id"] = $transaction_id;
		                        $cert_info["number_of_share"] = 0;
		                        $cert_info["amount_share"] = 0;
		                        $cert_info["no_of_share_paid"] = 0;
		                        $cert_info["amount_paid"] = 0;
		                        $cert_info["certificate_no"] = $row->certificate;
		                        $cert_info["new_certificate_no"] = $row->certificate;
		                        $cert_info["status"] = 1;
		                        $this->db->insert("certificate",$cert_info);
		                    }
		                    else
		                    {
		                        $transaction_id = "TR-".mt_rand(100000000, 999999999);
		                        $previous_cert_query = $this->db->query('select * from certificate where id = '.$row->certificate_id);
		                        $previous_cert_query = $previous_cert_query->result_array();

		                        $previous_cert_status['status'] = 2;
		                        $this->db->update("certificate",$previous_cert_status,array("id" => $row->certificate_id));

		                        //member_shares
		                        $member_share_info["company_code"] = $previous_cert_query[0]["company_code"];
		                        $member_share_info["client_member_share_capital_id"] = $previous_cert_query[0]["client_member_share_capital_id"];
		                        $member_share_info["officer_id"] = $row->officer_id;
		                        $member_share_info["field_type"] = $row->field_type;
		                        $member_share_info["transaction_id"] = $transaction_id;
		                        $member_share_info["number_of_share"] = -(str_replace(',', '', $row->number_of_shares_to_transfer));
		                        $member_share_info["amount_share"] = -((float)str_replace(',', '',$row->number_of_shares_to_transfer) * $per_share);
		                        $member_share_info["no_of_share_paid"] = -(str_replace(',', '', $row->number_of_shares_to_transfer));
		                        $member_share_info["amount_paid"] = -((float)str_replace(',', '', $row->number_of_shares_to_transfer) * $per_share);
		                        $member_share_info["transaction_date"] = $lodgement_date;
		                        $member_share_info["transaction_type"] = "Transfer";
		                        $member_share_info["consideration"] = 0;
		                        $member_share_info["cert_status"] = 1;
		                        $this->db->insert("member_shares",$member_share_info);

		                        //certificate
		                        $cert_info["company_code"] = $previous_cert_query[0]["company_code"];
		                        $cert_info["client_member_share_capital_id"] = $previous_cert_query[0]["client_member_share_capital_id"];
		                        $cert_info["officer_id"] = $row->officer_id;
		                        $cert_info["field_type"] = $row->field_type;
		                        $cert_info["transaction_id"] = $transaction_id;
		                        $cert_info["number_of_share"] = str_replace(',', '', $row->new_number_of_share);
		                        $cert_info["amount_share"] = (float)str_replace(',', '', $row->new_number_of_share) * $per_share;
		                        $cert_info["no_of_share_paid"] = str_replace(',', '', $row->new_number_of_share);
		                        $cert_info["amount_paid"] = (float)str_replace(',', '', $row->new_number_of_share) * $per_share;
		                        $cert_info["certificate_no"] = $row->certificate;
		                        $cert_info["new_certificate_no"] = $row->certificate;
		                        $cert_info["status"] = 1;
		                        $this->db->insert("certificate",$cert_info);
		                    }
		                } 
		            }

		            //the share movement for transferee array
		            foreach (($transferee_array) as $row)
		            {
		                $transaction_id = "TR-".mt_rand(100000000, 999999999);
		                $previous_cert_query = $this->db->query('select * from transaction_certificate where id = '.$row->certificate_id);
		                $previous_cert_query = $previous_cert_query->result_array();

		                //member_shares
		                $member_share_info["company_code"] = $previous_cert_query[0]["company_code"];
		                $member_share_info["client_member_share_capital_id"] = $previous_cert_query[0]["client_member_share_capital_id"];
		                $member_share_info["officer_id"] = $row->officer_id;
		                $member_share_info["field_type"] = $row->field_type;
		                $member_share_info["transaction_id"] = $transaction_id;
		                $member_share_info["number_of_share"] = $row->new_number_of_share;
		                $member_share_info["amount_share"] = ((float)$row->new_number_of_share) * $per_share;
		                $member_share_info["no_of_share_paid"] = $row->new_number_of_share;
		                $member_share_info["amount_paid"] = ((float)$row->new_number_of_share) * $per_share;
		                $member_share_info["transaction_date"] = $lodgement_date;
		                $member_share_info["transaction_type"] = "Transfer";
		                $member_share_info["consideration"] = 0;
		                $member_share_info["cert_status"] = 1;
		                $this->db->insert("member_shares",$member_share_info);

		                //certificate
		                $cert_info["company_code"] = $previous_cert_query[0]["company_code"];
		                $cert_info["client_member_share_capital_id"] = $previous_cert_query[0]["client_member_share_capital_id"];
		                $cert_info["officer_id"] = $row->officer_id;
		                $cert_info["field_type"] = $row->field_type;
		                $cert_info["transaction_id"] = $transaction_id;
		                $cert_info["number_of_share"] = $row->new_number_of_share;
		                $cert_info["amount_share"] = ((float)$row->new_number_of_share) * $per_share;
		                $cert_info["no_of_share_paid"] = $row->new_number_of_share;
		                $cert_info["amount_paid"] = ((float)$row->new_number_of_share) * $per_share;
		                $cert_info["certificate_no"] = $row->certificate;
		                $cert_info["new_certificate_no"] = $row->certificate;
		                $cert_info["status"] = 1;
		                $this->db->insert("certificate",$cert_info);
		            }

		            //get the currant share transfer info
		            $share_transfer_info_query = $this->db->query("select transaction_transfer_member_id.id as transaction_transfer_member_id, client.company_code, client.company_name as client_company_name, transaction_transfer_member_id.transaction_id as transaction_id, from_officer.id as from_officer_id, from_officer.field_type as from_officer_field_type, from_officer.identification_no as from_officer_identification_no, from_officer.name as from_officer_name, from_officer_company.id as from_officer_company_id, from_officer_company.register_no as from_officer_company_register_no, from_officer_company.field_type as from_officer_company_field_type, from_officer_company.company_name as from_officer_company_name, from_client.id as from_client_company_id, from_client.registration_no as from_client_regis_no, 'client' as from_client_company_field_type, from_client.company_name as from_client_company_name, from_share_capital.id as share_capital_id, from_share_capital.class_id, from_share_capital.other_class, from_share_capital.currency_id, from_class.sharetype, from_currencies.currency, from_transaction_certificate.id as from_cert_id, from_transaction_certificate.certificate_no as from_certificate_no, from_transaction_certificate.new_certificate_no as from_new_certificate_no, from_transfer_member.id as from_transfer_member_id, from_transfer_member.number_of_share as from_number_of_share, from_transfer_member.consideration as from_consideration, to_officer.id as to_officer_id, to_officer.field_type as to_officer_field_type, to_officer.identification_no as to_officer_identification_no, to_officer.name as to_officer_name, to_officer_company.id as to_officer_company_id, to_officer_company.register_no as to_officer_company_register_no, to_officer_company.field_type as to_officer_company_field_type, to_officer_company.company_name as to_officer_company_name, to_client.id as to_client_company_id, to_client.registration_no as to_client_regis_no, 'client' as to_client_company_field_type, to_client.company_name as to_client_company_name, to_transaction_certificate.id as to_cert_id, to_transaction_certificate.certificate_no as to_certificate_no, to_transaction_certificate.new_certificate_no as to_new_certificate_no, to_transfer_member.id as to_transfer_member_id, to_transfer_member.number_of_share as to_number_of_share from transaction_transfer_member_id left join transaction_member_shares as from_transfer_member on from_transfer_member.id = transaction_transfer_member_id.transfer_from_id left join officer as from_officer on from_officer.id = from_transfer_member.officer_id and from_officer.field_type = from_transfer_member.field_type left join officer_company as from_officer_company on from_officer_company.id = from_transfer_member.officer_id and from_officer_company.field_type = from_transfer_member.field_type left join client as from_client on from_client.id = from_transfer_member.officer_id and from_client.deleted = 0 and from_transfer_member.field_type = 'client' left join client_member_share_capital as from_share_capital on from_transfer_member.client_member_share_capital_id = from_share_capital.id left join sharetype as from_class on from_class.id = from_share_capital.class_id left join currency as from_currencies on from_currencies.id = from_share_capital.currency_id left join transaction_certificate as from_transaction_certificate on from_transaction_certificate.officer_id = from_transfer_member.officer_id and from_transaction_certificate.company_code = from_transfer_member.company_code and from_transaction_certificate.field_type = from_transfer_member.field_type and from_transaction_certificate.transaction_id = from_transfer_member.transaction_id left join transaction_member_shares as to_transfer_member on to_transfer_member.id = transaction_transfer_member_id.transfer_to_id left join officer as to_officer on to_officer.id = to_transfer_member.officer_id and to_officer.field_type = to_transfer_member.field_type left join officer_company as to_officer_company on to_officer_company.id = to_transfer_member.officer_id and to_officer_company.field_type = to_transfer_member.field_type left join client as to_client on to_client.id = to_transfer_member.officer_id and to_client.deleted = 0 and to_transfer_member.field_type = 'client' left join transaction_certificate as to_transaction_certificate on to_transaction_certificate.officer_id = to_transfer_member.officer_id and to_transaction_certificate.company_code = to_transfer_member.company_code and to_transaction_certificate.field_type = to_transfer_member.field_type and to_transaction_certificate.transaction_id = to_transfer_member.transaction_id left join transaction_master on transaction_master.id = transaction_transfer_member_id.transaction_id left join client on client.company_code = transaction_master.company_code where transaction_transfer_member_id.transaction_id = '".$transaction_master_id."'");

		            //insert the register of transfer info to database 
		            if ($share_transfer_info_query->num_rows() > 0) 
		            {
		                $share_transfer_info_query = $share_transfer_info_query->result_array();
		                foreach (($share_transfer_info_query) as $row) 
		                {   
		                    $register_of_transfers["company_code"] = $row["company_code"];
		                    $register_of_transfers["date"] = $lodgement_date;

		                    if($row["from_officer_field_type"] == "individual")
		                    {
		                        $register_of_transfers["transferor_office_id"] = $row["from_officer_id"];
		                        $register_of_transfers["transferor_field_type"] = $row["from_officer_field_type"];
		                    }
		                    else if($row["from_officer_company_field_type"] == "company")
		                    {
		                        $register_of_transfers["transferor_office_id"] = $row["from_officer_company_id"];
		                        $register_of_transfers["transferor_field_type"] = $row["from_officer_company_field_type"];
		                    }
		                    else if($row["from_client_company_field_type"] == "client")
		                    {
		                        $register_of_transfers["transferor_office_id"] = $row["from_client_company_id"];
		                        $register_of_transfers["transferor_field_type"] = $row["from_client_company_field_type"];
		                    }

		                    if($row["to_officer_field_type"] == "individual")
		                    {
		                        $register_of_transfers["transferee_office_id"] = $row["to_officer_id"];
		                        $register_of_transfers["transferee_field_type"] = $row["to_officer_field_type"];
		                    }
		                    else if($row["to_officer_company_field_type"] == "company")
		                    {
		                        $register_of_transfers["transferee_office_id"] = $row["to_officer_company_id"];
		                        $register_of_transfers["transferee_field_type"] = $row["to_officer_company_field_type"];
		                    }
		                    else if($row["to_client_company_field_type"] == "client")
		                    {
		                        $register_of_transfers["transferee_office_id"] = $row["to_client_company_id"];
		                        $register_of_transfers["transferee_field_type"] = $row["to_client_company_field_type"];
		                    }
		                    $register_of_transfers["new_number_share"] = $row["to_number_of_share"];
		                    $register_of_transfers["new_amount_share"] = (float)$row["to_number_of_share"] * $per_share;
		                    $register_of_transfers["sharetype"] = $row["sharetype"];
		                    $register_of_transfers["other_class"] = $row["other_class"];
		                    $register_of_transfers["currency"] = $row["currency"];
		                    $this->db->insert("register_of_transfers",$register_of_transfers);
		                    $register_of_transfers_id = $this->db->insert_id();

		                    foreach (($transferee_array) as $transferee_row) 
		                    {
		                        if($register_of_transfers["transferee_office_id"] == $transferee_row->officer_id && $register_of_transfers["transferee_field_type"] == $transferee_row->field_type)
		                        {
		                            $new_cert_no["new_cert"] = $transferee_row->certificate;
		                            $this->db->update("register_of_transfers",$new_cert_no,array("id" => $register_of_transfers_id));
		                        }
		                    }

		                    foreach (($transferor_array) as $transferor_row) 
		                    {
		                        $cancel_cert_query = $this->db->query('select * from certificate where id = '.$transferor_row->certificate_id);
		                        $cancel_cert_query = $cancel_cert_query->result_array();

		                        if($register_of_transfers["transferor_office_id"] == $transferor_row->officer_id && $register_of_transfers["transferor_field_type"] == $transferor_row->field_type && $transferor_row->number_of_shares_to_transfer != "")
		                        {
                                    $register_of_transfers_info["register_of_transfers_id"] = $register_of_transfers_id;
                                    $register_of_transfers_info["old_cert_id"] = $cancel_cert_query[0]["id"];
                                    $register_of_transfers_info["old_number_share"] = $cancel_cert_query[0]["number_of_share"];
                                    $register_of_transfers_info["old_amount_share"] = (float)$cancel_cert_query[0]["number_of_share"] * $per_share;
                                    $register_of_transfers_info["old_cert"] = $cancel_cert_query[0]["certificate_no"];
                                    $register_of_transfers_info["balance_number_share"] = str_replace(',', '', $transferor_row->new_number_of_share);
                                    $register_of_transfers_info["balance_amount_share"] = (float)str_replace(',', '', $transferor_row->new_number_of_share) * $per_share;
                                    $register_of_transfers_info["balance_cert"] = $transferor_row->certificate;

                                    $this->db->insert("register_of_transfers_info",$register_of_transfers_info);
		                        }
		                    }
		                }
		            }
		        }

		        $this->master_model->check_client_company_type($company_code);

		        $this->transaction_word_model->getWordValue($transaction_master_id, "Member to Controller", $company_code, $q[0]["firm_id"], null, $q[0]["document_name"], $lodgement_date);
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

			    $this->transaction_word_model->getWordValue($transaction_master_id, "Member to Controller", $company_code, $q[0]["firm_id"], null, $q[0]["document_name"], $lodgement_date);

			    $this->master_model->check_client_company_type($company_code);
			}
		}
		else if($transaction_task == 12)
		{
			if($transaction_status == 2)
			{
				//transaction
				$this->db->select('transaction_change_company_name.*, transaction_master.effective_date');
		        $this->db->from('transaction_change_company_name');
		        $this->db->join('transaction_master', 'transaction_master.id = transaction_change_company_name.transaction_id', 'left');
		        $this->db->where('transaction_id', $transaction_master_id);
				$check_transaction_change_company_name = $this->db->get();

				$check_transaction_change_company_name = $check_transaction_change_company_name->result_array();

				$data["company_name"] = $this->encryption->encrypt($check_transaction_change_company_name[0]["new_company_name"]);
				$data["change_name_effective_date"] = $check_transaction_change_company_name[0]["effective_date"];
				//client
				$this->db->select('client.*');
		        $this->db->from('client');
		        $this->db->where('company_code', $company_code);
		        $this->db->where('deleted = 0');
				$check_client_old_company_name = $this->db->get();

				$check_client_old_company_name = $check_client_old_company_name->result_array();

				//change_subsidiary_name
				$change_company_name['subsidiary_name'] = strtoupper($check_transaction_change_company_name[0]['new_company_name']);

				$this->db->update("corporate_representative",$change_company_name,array("subsidiary_name" => $this->encryption->decrypt($check_client_old_company_name[0]["company_name"])));

				if($check_client_old_company_name[0]["change_name_effective_date"] != "")
				{
					$data['former_name'] = strtoupper($this->encryption->decrypt($check_client_old_company_name[0]["company_name"]))." (w.e.f.".$check_client_old_company_name[0]["change_name_effective_date"].")\r\n".$check_client_old_company_name[0]['former_name'];
				}
				else
				{
					$data['former_name'] = strtoupper($this->encryption->decrypt($check_client_old_company_name[0]["company_name"]))."\r\n".$check_client_old_company_name[0]['former_name'];
				}

				$this->db->update("client", $data,array("company_code" => $company_code, "deleted" => 0)); //, "firm_id" => $this->session->userdata('firm_id')

				$this->update_service_engagment_for_other_services($company_code, $transaction_master_id);

				if($this->session->userdata('refresh_token_value'))
	    		{
					$client_query_qb = $this->db->query("SELECT client.*, client_qb_id.currency_name FROM client LEFT JOIN client_qb_id ON client_qb_id.company_code = client.company_code WHERE client.company_code = '".$company_code."'");
					$client_array_qb = $client_query_qb->result_array();

					for($v = 0; $v < count($client_array_qb); $v++)
					{
						$this->update_client_to_quickbook($client_array_qb[$v]["id"], $client_array_qb[$v]["currency_name"]);
					}
				}
			}
		}
		else if($transaction_task == 15)
		{
			if($transaction_status == 2)
			{
				$this->db->select('transaction_agm_ar.*, transaction_master.company_code');
		        $this->db->from('transaction_agm_ar');
		        $this->db->join('transaction_master', 'transaction_master.id = transaction_agm_ar.transaction_id', 'left');
		        $this->db->where('transaction_id', $transaction_master_id);
				$check_transaction_agm_ar = $this->db->get();
				$check_transaction_agm_ar_array = $check_transaction_agm_ar->result_array();

				foreach($check_transaction_agm_ar->result() as $r) 
				{
					$k['transaction_id'] = $r->transaction_id;
					$k['is_first_agm_id'] = $r->is_first_agm_id;
					$k['year_end_date'] = $r->year_end_date;
					$k['agm_date'] = $r->agm_date;
					$k['reso_date'] = "";
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
				$this->db->update("filing",$new_filing_info);

				//---------check 28 or 29 February---------------------
				$original_fye_date = $check_transaction_agm_ar_array[0]['year_end_date'];

		        $dm = date('d F', strtotime($original_fye_date));

		        if($dm == "28 February")
		        {
		            $fye_date = date('d F Y', strtotime('+1 year', strtotime($original_fye_date)));

		            $dt = new DateTime($fye_date);

		            $dt->modify( 'first day of next month' );
		            $dt->modify('+' . (min($day, $dt->format('t')) - 1) . ' days');

		            $fye_dfy = $dt->format('d F Y');
		            $fye_ymd = $dt->format('Y-m-d');
		        }
		        else if($dm == "29 February")
		        {
		            $fye_date = date('d F Y', strtotime('+1 year', strtotime($original_fye_date)));

		            $dt = new DateTime($fye_date);

		            //$dt->modify( 'first day of next month' );
		            $dt->modify('+' . (min($day, $dt->format('t')) - 1) . ' days');

		            $fye_dfy = $dt->format('d F Y');
		            $fye_ymd = $dt->format('Y-m-d');
		        }
		        else
		        {
		        	$fye_dfy = date('d F Y', strtotime('+1 year', strtotime($original_fye_date)));
		            $fye_ymd = date('Y-m-d', strtotime('+1 year', strtotime($original_fye_date)));
		        }

				$latest_id = $this->db->query("select * from filing where company_code='".$_POST['company_code']."' ORDER BY id DESC LIMIT 1");

				//---------end check 28 or 29 February---------------------

			    if($check_transaction_agm_ar_array[0]['agm_date'] != null && $check_transaction_agm_ar_array[0]['agm_date'] != "dispensed")
				{
					$new_filing['company_code'] = $check_transaction_agm_ar_array[0]['company_code'];
					$new_filing['year_end'] = $fye_dfy;
					$new_filing['ar_filing_date'] = "";
					$new_filing['financial_year_period_id'] = 1;
					$new_filing['175_extended_to'] = 0;
					$new_filing["201_extended_to"] = 0;

					$latest_year_end = date('Y-m-d', strtotime($new_filing['year_end']));

					$year_end_date = new DateTime($latest_year_end);
					if(date('Y-m-d', strtotime("8/31/2018")) > $fye_ymd)
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
					
					$this->db->insert("filing",$new_filing);
				}
				elseif($check_transaction_agm_ar_array[0]['agm_date'] != null && $check_transaction_agm_ar_array[0]['agm_date'] == "dispensed")
				{
					$new_filing['company_code'] = $check_transaction_agm_ar_array[0]['company_code'];
					$new_filing['year_end'] = $fye_dfy; 
					$new_filing['ar_filing_date'] = "";
					$new_filing['financial_year_period_id'] = 1;
					$new_filing['175_extended_to'] = 0;
					$new_filing["201_extended_to"] = 0;
					$new_filing['due_date_175'] = "Not Applicable";

					$latest_year_end = date('Y-m-d', strtotime($new_filing['year_end']));

					$year_end_date = new DateTime($latest_year_end);

					if(date('Y-m-d', strtotime("8/31/2018")) > $fye_ymd)
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

					$this->db->insert("filing",$new_filing);
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
		}
		else if($transaction_task == 20)
		{
			if($transaction_status == 2)
			{
				$this->db->select('transaction_corporate_representative.*');
		        $this->db->from('transaction_corporate_representative');
		        $this->db->where('transaction_id', $transaction_master_id);
				$check_transaction_incorp_subsidiary = $this->db->get();

			    foreach($check_transaction_incorp_subsidiary->result() as $r) 
			    {
					$k['registration_no'] = $r->registration_no;
					$k['subsidiary_name'] = $r->subsidiary_name;
					$k['name_of_corp_rep'] = $r->name_of_corp_rep;
					$k['identity_number'] = $r->identity_number;
					$k['effective_date'] = $lodgement_date;


					$this->db->insert("corporate_representative",$k);
			    }
			}
		}
		else if($transaction_task == 24)
		{
			if($transaction_status == 2)
			{
				//officer
				$check_client_officers = $this->db->get_where("client_officers", array("company_code" => $company_code));

				$this->db->select('id, company_code, position, alternate_of, officer_id, field_type, date_of_appointment, date_of_cessation, retiring, created_at');
		        $this->db->from('transaction_client_officers');
		        $this->db->where('company_code', $company_code);
		        $this->db->where('transaction_id', $transaction_master_id);
				$check_transaction_client_officers = $this->db->get();

		    	foreach($check_transaction_client_officers->result() as $r) 
		    	{
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
		}
		else if($transaction_task == 26)
		{
			if($transaction_status == 2)
			{
				$this->db->select('transaction_strike_off.*, transaction_master.company_code');
		        $this->db->from('transaction_strike_off');
		        $this->db->join("transaction_master", "transaction_master.id = transaction_strike_off.transaction_id");
		        $this->db->where('transaction_id', $transaction_master_id);
				$check_transaction_strike_off = $this->db->get();

			    foreach($check_transaction_strike_off->result() as $r) {
					$k['company_code'] = $r->company_code;
					$k['reason_for_application_id'] = $r->reason_for_application_id;
					$k['ceased_date'] = $r->ceased_date;

					$this->db->insert("strike_off",$k);
			    }

			    $check_transaction_strike_off_array = $check_transaction_strike_off->result_array();

			    $client['status'] = 4;

			    $this->db->update("client", $client, array("company_code" => $check_transaction_strike_off_array[0]["company_code"], "deleted = " => 0));
			}
		}
		else if($transaction_task == 28)
		{
			if($transaction_status == 2)
			{
				//client
				$check_client = $this->db->get_where("client", array("company_code" => $company_code, "firm_id" => $this->session->userdata('firm_id'), "deleted" => 0));

				$this->db->select('firm_id, acquried_by, company_code, client_code, registration_no, company_name, former_name, incorporation_date, company_type, status, activity1, activity2, registered_address, our_service_regis_address_id, postal_code, street_name, building_name, unit_no1, unit_no2, auto_generate, deleted, created_by, created_at');
		        $this->db->from('transaction_client');
		        $this->db->where('company_code', $company_code);
		        $this->db->where('transaction_id', $transaction_master_id);
				$check_transaction_client = $this->db->get();

				if (!$check_client->num_rows())
				{	
					foreach($check_transaction_client->result() as $r) {
						$r->registration_no = $registration_no;
						$r->incorporation_date = $lodgement_date;
				        $this->db->insert("client",$r);
				    }
				} 
				else 
				{
					foreach($check_transaction_client->result() as $r) 
					{
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
				$new_filing['year_end'] = date('d F Y', strtotime($check_transaction_filing[0]['year_end']));
				$new_filing['ar_filing_date'] = "";
				$new_filing['financial_year_period_id'] = $check_transaction_filing[0]['financial_year_period'];
				$new_filing['175_extended_to'] = 0;
				$new_filing["201_extended_to"] = 0;

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
		        $this->db->where('transaction_service_proposal_info.transaction_id', $transaction_master_id);
				$check_is_potential_client = $this->db->get();

				if ($check_is_potential_client->num_rows())
				{	
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
						$check_unique_client = $this->db->get_where("client", array("deleted !=" => 1));
						$check_unique_client_array = $check_unique_client->result_array();
 
						$unique_client = true;
						$client_company_code = $check_is_potential_client_array[0]['company_code'];
						for($r = 0; $r < count($check_unique_client_array); $r++)
						{
							if($this->encryption->decrypt($check_unique_client_array[$r]["company_name"]) == strtoupper(trim($this->encryption->decrypt($check_is_potential_client_array[0]['client_name']))))
							{
								$unique_client = false;
								$client_company_code = $check_unique_client_array[$r]["company_code"];
								$check_is_potential_client_array[0]['company_code'] = $check_unique_client_array[$r]["company_code"];
							}
						}

						if($unique_client)
						{
							$new_client["firm_id"] = $this->session->userdata('firm_id');
							$new_client["acquried_by"] = 1;
							$new_client["company_type"] = 1;
							$new_client["status"] = 1;
							$new_client["company_code"] = $client_company_code;
							$new_client["client_code"] = $this->transaction_model->detect_client_code($this->encryption->decrypt($check_is_potential_client_array[0]['client_name']));
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
								$billing_info_id = $i + 1;
								$client_billing_info['company_code'] = $client_company_code;
								$client_billing_info['client_billing_info_id'] = $i + 1;
								$client_billing_info['service'] = $check_is_potential_client_array[$i]['our_service_id'];
								$client_billing_info['invoice_description'] = $check_is_potential_client_array[$i]['invoice_description'];
								$client_billing_info['amount'] = (float)str_replace(',', '', $check_is_potential_client_array[$i]['fee']);
								$client_billing_info['currency'] = $check_is_potential_client_array[$i]['currency_id'];
								$client_billing_info['unit_pricing'] = $check_is_potential_client_array[$i]['unit_pricing'];
								$client_billing_info['servicing_firm'] = $check_is_potential_client_array[$i]['servicing_firm'];

								$this->db->insert("client_billing_info",$client_billing_info);
							}

							$disbursements['company_code'] = $client_company_code;
							$disbursements['client_billing_info_id'] = $billing_info_id + 1;
							$disbursements['service'] = 26;
							$disbursements['invoice_description'] = "Printing, stationery and transport charges";
							$disbursements['amount'] = 0;
							$disbursements['currency'] = 1;
							$disbursements['unit_pricing'] = 6;
							$disbursements['servicing_firm'] = $this->session->userdata('firm_id');

							$this->db->insert("client_billing_info",$disbursements);

							$this->db->select('id, company_code, name, created_at');
					        $this->db->from('transaction_client_contact_info');
					        $this->db->where('company_code', $client_company_code);
					        $this->db->where('transaction_id', $check_is_potential_client_array[0]['transaction_id']);
							$check_transaction_client_contact_info = $this->db->get();
							
							foreach($check_transaction_client_contact_info->result() as $g) {
								$f['company_code'] = $g->company_code;
								$f['name'] = $g->name;
								$f['created_at'] = $g->created_at;
							}
							
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
					elseif(!$gotEngagement && $check_is_potential_client_array[0]['potential_client'] == '0')
					{
						$this->update_service_engagment($check_is_potential_client_array);
					}
					
				}
		 	}
		}
		else if($transaction_task == 30)
		{	
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

				if ($check_is_potential_client->num_rows())
				{	
					$check_is_potential_client_array = $check_is_potential_client->result_array();

					if($check_is_potential_client_array[0]['potential_client'] == '1')
					{
						$new_client["firm_id"] = $this->session->userdata('firm_id');
						$new_client["acquried_by"] = 1;
						$new_client["company_type"] = 1;
						$new_client["status"] = 1;
						$new_client["company_code"] = $check_is_potential_client_array[0]['company_code'];
						$new_client["client_code"] = $this->transaction_model->detect_client_code($this->encryption->decrypt($check_is_potential_client_array[0]['client_name']));
						$new_client["registration_no"] = $this->encryption->encrypt(strtoupper($check_is_potential_client_array[0]['uen']));
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
							$billing_info_id = $billing_info_id + 1;
							$client_billing_info['company_code'] = $check_is_potential_client_array[$i]['company_code'];
							$client_billing_info['client_billing_info_id'] = $i + 1;
							$client_billing_info['service'] = $check_is_potential_client_array[$i]['our_service_id'];
							$client_billing_info['invoice_description'] = $check_is_potential_client_array[$i]['invoice_description'];
							$client_billing_info['amount'] = (float)str_replace(',', '', $check_is_potential_client_array[$i]['fee']);
							$client_billing_info['currency'] = $check_is_potential_client_array[$i]['currency_id'];
							$client_billing_info['unit_pricing'] = $check_is_potential_client_array[$i]['unit_pricing'];
							$client_billing_info['servicing_firm'] = $check_is_potential_client_array[$i]['servicing_firm'];

							$this->db->insert("client_billing_info",$client_billing_info);
						}

						$disbursements['company_code'] = $check_is_potential_client_array[0]['company_code'];
						$disbursements['client_billing_info_id'] = $billing_info_id + 1;
						$disbursements['service'] = 26;
						$disbursements['invoice_description'] = "Printing, stationery and transport charges";
						$disbursements['amount'] = 0;
						$disbursements['currency'] = 1;
						$disbursements['unit_pricing'] = 6;
						$disbursements['servicing_firm'] = $this->session->userdata('firm_id');

						$this->db->insert("client_billing_info",$disbursements);

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

			        $this->update_service_engagment($check_is_potential_client_array);
				} 
			}
		}
		else if($transaction_task == 31)
		{
			if($transaction_status == 2)
			{
				$check_client_controller = $this->db->get_where("client_controller", array("company_code" => $company_code, "deleted" => 0));

				$this->db->select('transaction_client_controller.*');
		        $this->db->from('transaction_client_controller');
		        $this->db->where('company_code', $company_code);
		        $this->db->where('transaction_id', $transaction_master_id);
				$check_transaction_client_controller = $this->db->get();

				$insert = true;
			    foreach($check_transaction_client_controller->result() as $r) {
			    	foreach($check_client_controller->result() as $k)
			    	{
			    		if($k->officer_id == $r->officer_id && $k->field_type == $r->field_type && $k->company_code == $r->company_code && $k->date_of_cessation == "")
			    		{
			    			$update_data['company_code'] = $r->company_code;
					    	$update_data['officer_id'] = $r->officer_id;
					    	$update_data['field_type'] = $r->field_type;
					    	$update_data['date_of_registration'] = $r->date_of_registration;
					    	$update_data['date_of_notice'] = $r->date_of_notice;
					    	$update_data['date_of_cessation'] = $r->date_of_cessation;
					    	$update_data['supporting_document'] = $r->supporting_document;
					    	$update_data['deleted'] = $r->deleted;

			    			$this->db->update("client_controller",$update_data,array("company_code" => $r->company_code, "officer_id" => $r->officer_id, "field_type" => $r->field_type));
			    			$insert = false;
			    			break;
			    		}
			    	}

			    	if($insert)
			    	{
			    		$data['company_code'] = $r->company_code;
				    	$data['officer_id'] = $r->officer_id;
				    	$data['field_type'] = $r->field_type;
				    	$data['date_of_registration'] = $r->date_of_registration;
				    	$data['date_of_notice'] = $r->date_of_notice;
				    	$data['date_of_cessation'] = $r->date_of_cessation;
				    	$data['supporting_document'] = $r->supporting_document;
				    	$data['deleted'] = $r->deleted;
						$data['is_confirm_by_reg_controller'] = $radio_confirm_registrable_controller;
			    		$data['confirmation_received_date'] = $date_of_the_conf_received;
			    		$data['date_of_entry'] = $date_of_entry_or_update;

			    		$this->db->insert("client_controller",$data);
			    	}
			    	else
			    	{
			    		$insert = true;
			    	}
			    }
			    $this->update_service_engagment_for_other_services($company_code, $transaction_master_id);
			}
		}
		else if($transaction_task == 32)
		{
			if($transaction_status == 2)
			{
				$check_client_nominee_director = $this->db->get_where("client_nominee_director", array("company_code" => $company_code, "deleted" => 0));

				$this->db->select('transaction_client_nominee_director.*');
		        $this->db->from('transaction_client_nominee_director');
		        $this->db->where('company_code', $company_code);
		        $this->db->where('transaction_id', $transaction_master_id);
		        //$this->db->where('deleted', 0);
				$check_transaction_client_nominee_director = $this->db->get();

				$insert = true;
			    foreach($check_transaction_client_nominee_director->result() as $r) {
			    	foreach($check_client_nominee_director->result() as $k)
			    	{
			    		if($k->nomi_officer_id == $r->nomi_officer_id && $k->nomi_officer_field_type == $r->nomi_officer_field_type && $k->company_code == $r->company_code && $k->date_of_cessation == "")
			    		{
			    			$update_data['company_code'] = $r->company_code;
					    	$update_data['nd_officer_id'] = $r->nd_officer_id;
					    	$update_data['nd_officer_field_type'] = $r->nd_officer_field_type;
					    	$update_data['nomi_officer_id'] = $r->nomi_officer_id;
					    	$update_data['nomi_officer_field_type'] = $r->nomi_officer_field_type;
					    	$update_data['date_become_nominator'] = $r->date_become_nominator;
					    	$update_data['date_of_cessation'] = $r->date_of_cessation;
					    	$update_data['supporting_document'] = $r->supporting_document;
					    	$update_data['deleted'] = $r->deleted;

			    			$this->db->update("client_nominee_director",$update_data,array("company_code" => $r->company_code, "nomi_officer_id" => $r->nomi_officer_id, "nomi_officer_field_type" => $r->nomi_officer_field_type));
			    			$insert = false;
			    			break;
			    		}
			    	}

			    	if($insert)
			    	{
			    		$data['company_code'] = $r->company_code;
				    	$data['nd_officer_id'] = $r->nd_officer_id;
				    	$data['nd_officer_field_type'] = $r->nd_officer_field_type;
				    	$data['nomi_officer_id'] = $r->nomi_officer_id;
				    	$data['nomi_officer_field_type'] = $r->nomi_officer_field_type;
				    	$data['date_become_nominator'] = $r->date_become_nominator;
				    	$data['date_of_cessation'] = $r->date_of_cessation;
				    	$data['supporting_document'] = $r->supporting_document;
				    	$data['deleted'] = $r->deleted;
			    		$data['nd_date_entry'] = $date_of_entry_or_update;

			    		$this->db->insert("client_nominee_director",$data);
			    	}
			    	else
			    	{
			    		$insert = true;
			    	}
			    }
			    $this->update_service_engagment_for_other_services($company_code, $transaction_master_id);
			}
		}
		else if($transaction_task == 33)
		{
			if($transaction_status == 2)
			{
				//officer
				$check_client_officers = $this->db->get_where("client_officers", array("company_code" => $company_code));

				$this->db->select('id, company_code, position, alternate_of, officer_id, field_type, date_of_appointment, date_of_cessation, appoint_resign_flag, retiring, created_at');
		        $this->db->from('transaction_client_officers');
		        $this->db->where('company_code', $company_code);
		        $this->db->where('transaction_id', $transaction_master_id);
				$check_transaction_client_officers = $this->db->get();

				foreach($check_transaction_client_officers->result() as $r) 
				{
					$k['company_code'] = $r->company_code;
					$k['position'] = $r->position;
					$k['alternate_of'] = $r->alternate_of;
					$k['officer_id'] = $r->officer_id;
					$k['field_type'] = $r->field_type;
					$k['date_of_appointment'] = $r->date_of_appointment;
					$k['date_of_cessation'] = $r->date_of_cessation;
					$k['retiring'] = $r->retiring;
					$k['created_at'] = $r->created_at;

					if($r->appoint_resign_flag == "appoint")
					{
						$this->db->insert("client_officers",$k);
					}
					else if($r->appoint_resign_flag == "resign")
					{
						$this->db->update("client_officers",$k,array("company_code" => $r->company_code, "officer_id" => $r->officer_id, "field_type" => $r->field_type, "date_of_cessation" => ''));
					}
			    }

			    $this->db->select('transaction_client_nominee_director.*');
		        $this->db->from('transaction_client_nominee_director');
		        $this->db->where('company_code', $company_code);
		        $this->db->where('transaction_id', $transaction_master_id);
				$check_transaction_client_nominee_director = $this->db->get();

				if ($check_transaction_client_nominee_director->num_rows() > 0) {
					foreach($check_transaction_client_nominee_director->result() as $r) {
						$data['company_code'] = $r->company_code;
				    	$data['nd_officer_id'] = $r->nd_officer_id;
				    	$data['nd_officer_field_type'] = $r->nd_officer_field_type;
				    	$data['nomi_officer_id'] = $r->nomi_officer_id;
				    	$data['nomi_officer_field_type'] = $r->nomi_officer_field_type;
				    	$data['date_become_nominator'] = $r->date_become_nominator;
				    	$data['date_of_cessation'] = $r->date_of_cessation;
				    	$data['supporting_document'] = $r->supporting_document;
				    	$data['deleted'] = $r->deleted;
			    		$data['nd_date_entry'] = $lodgement_date; //$date_of_entry_or_update;

			    		$this->db->insert("client_nominee_director",$data);
					}
				}

			    $this->update_service_engagment_for_other_services($company_code, $transaction_master_id);
			}
		}
		else if($transaction_task == 34)
		{
			if($transaction_status == 2)
			{
				$this->db->select('id, company_code, position, alternate_of, officer_id, field_type, date_of_appointment, date_of_cessation, appoint_resign_flag, retiring, created_at');
		        $this->db->from('transaction_client_officers');
		        $this->db->where('company_code', $company_code);
		        $this->db->where('transaction_id', $transaction_master_id);
				$check_transaction_client_officers = $this->db->get();

			    foreach($check_transaction_client_officers->result() as $r) 
			    {
					$k['company_code'] = $r->company_code;
					$k['position'] = $r->position;
					$k['alternate_of'] = $r->alternate_of;
					$k['officer_id'] = $r->officer_id;
					$k['field_type'] = $r->field_type;
					$k['date_of_appointment'] = $r->date_of_appointment;
					$k['date_of_cessation'] = $r->date_of_cessation;
					$k['retiring'] = $r->retiring;
					$k['created_at'] = $r->created_at;

					if($r->appoint_resign_flag == "appoint")
					{
						$this->db->insert("client_officers",$k);
					}
					else if($r->appoint_resign_flag == "resign")
					{
						$this->db->update("client_officers",$k,array("company_code" => $r->company_code, "officer_id" => $r->officer_id, "field_type" => $r->field_type, "date_of_cessation" => ''));
					}
			    }

			    $g['acquried_by'] = 2;
			    $this->db->update("client",$g,array("company_code" => $company_code));

			    $this->update_service_engagment_for_other_services($company_code, $transaction_master_id);
			}
		}

		if($transaction_task == 29)
		{
			if($transaction_status == 3)
			{
				$master["status"] = 2;
				$master["lodgement_date"] = $lodgement_date;
				$master["service_status"] = $transaction_status;
				$master["remarks"] = $cancellation_reason;
				$master["completed"] = 1;
			}
			else if($transaction_status == 4)
			{
				$master["status"] = 5;
				$master["lodgement_date"] = $lodgement_date;
				$master["service_status"] = $transaction_status;
				$master["remarks"] = $cancellation_reason;
				$master["completed"] = 1;

				$this->db->delete("transaction_pending_documents",array('transaction_id'=>$this->session->userdata('transaction_id')));

				$this->db->delete("transaction_pending_documents_file",array('transaction_id'=>$this->session->userdata('transaction_id')));

				$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
				$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));
			}
			else if($transaction_status == 5)
			{
				$master["status"] = 7;
				$master["lodgement_date"] = $lodgement_date;
				$master["service_status"] = $transaction_status;
				$master["remarks"] = $cancellation_reason;
			}
			else
			{
				$master["status"] = 1;
				$master["lodgement_date"] = $lodgement_date;
				$master["service_status"] = $transaction_status;
				$master["remarks"] = $cancellation_reason;
			}
			$this->db->update("transaction_master", $master,array("id" => $_POST['transaction_master_id']));
		
		}
		else if($transaction_task == 1 || $transaction_task == 2 || $transaction_task == 3 || $transaction_task == 4 || $transaction_task == 5 || $transaction_task == 6 || $transaction_task == 7 || $transaction_task == 8 || $transaction_task == 9 || $transaction_task == 10 || $transaction_task == 11 || $transaction_task == 12  || $transaction_task == 15 || $transaction_task == 24 || $transaction_task == 26 || $transaction_task == 30 || $transaction_task == 31 || $transaction_task == 32 || $transaction_task == 33 || $transaction_task == 34 || $transaction_task == 35)
		{
			if($transaction_status == 2)
			{
				if($transaction_task == 30)
				{
					$master["status"] = 2;
					$master["lodgement_date"] = $lodgement_date;
					$master["service_status"] = $transaction_status;
					$master["remarks"] = $cancellation_reason;
					$master["completed"] = 1;
				}
				else
				{
					$master["status"] = 3;
					if($transaction_task == 31)
					{
						$master["radio_confirm_registrable_controller"] = $radio_confirm_registrable_controller;
			    		$master["date_of_the_conf_received"] = $date_of_the_conf_received;
			    		$master["date_of_entry_or_update"] = $date_of_entry_or_update;
					}
					else if($transaction_task == 32)
					{
			    		$master["date_of_entry_or_update"] = $date_of_entry_or_update;
					}
					else
					{
						$master["lodgement_date"] = $lodgement_date;
					}
					$master["service_status"] = $transaction_status;
					$master["remarks"] = $cancellation_reason;
					$master["completed"] = 1;
				}
			}
			else if($transaction_status == 3)
			{
				$master["status"] = 5;
				$master["lodgement_date"] = $lodgement_date;
				$master["service_status"] = $transaction_status;
				$master["remarks"] = $cancellation_reason;
				$master["completed"] = 1;

				$this->db->delete("transaction_pending_documents",array('transaction_id'=>$this->session->userdata('transaction_id')));

				$this->db->delete("transaction_pending_documents_file",array('transaction_id'=>$this->session->userdata('transaction_id')));

				$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
				$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

				if($transaction_task == 1)
				{
					$get_billing_data = $this->transaction_model->get_billing_data($_POST['transaction_master_id']);

					if(count($get_billing_data) > 0)
					{	
						$credit_note['credit_note_date'] = (($lodgement_date != null)?$lodgement_date:date('m/d/Y'));
			            $credit_note['credit_note_no'] = $this->transaction_model->get_credit_note_no();
			            $credit_note['total_amount_discounted'] = (float)str_replace(',', '', $get_billing_data[0]["outstanding"]);

			            $this->db->insert("credit_note",$credit_note);
			            $credit_note_id = $this->db->insert_id();

			            for($i = 0; $i < count($get_billing_data); $i++ )
			            {
			                if($get_billing_data[0]["outstanding"] > 0)
			                {
			                    $billing['outstanding'] = 0;
			                    $this->db->update("billing",$billing,array("id" => $get_billing_data[0]["id"]));
			                    $billing_credit_note_record['firm_id'] = $this->session->userdata("firm_id");
			                    $billing_credit_note_record['credit_note_id'] = (float)$credit_note_id;
			                    $billing_credit_note_record['billing_id'] = $get_billing_data[0]["id"];
			                    $billing_credit_note_record['received'] = (float)str_replace(',', '', $get_billing_data[0]["outstanding"]);
			                    $billing_credit_note_record['previous_outstanding'] = (float)str_replace(',', '', $get_billing_data[0]["outstanding"]);

			                    $this->db->insert("billing_credit_note_record",$billing_credit_note_record);
			                }
			            }
			        }
				}

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
			else if($transaction_status == 4)
			{
				$master["status"] = 7;
				$master["lodgement_date"] = $lodgement_date;
				$master["service_status"] = $transaction_status;
				$master["remarks"] = $cancellation_reason;
			}
			else
			{
				$master["status"] = 1;
				$master["lodgement_date"] = $lodgement_date;
				$master["service_status"] = $transaction_status;
				$master["remarks"] = $cancellation_reason;
			}
			if($transaction_task == 1)
			{
				$master["registration_no"] = $registration_no;
			}
			$this->db->update("transaction_master", $master,array("id" => $_POST['transaction_master_id']));
		}
		else
		{
			$master["status"] = 3;
			$master["completed"] = 1;
			if($transaction_task == 28)
			{
				$master["registration_no"] = $registration_no;
			}
			$master["lodgement_date"] = $lodgement_date;
			$this->db->update("transaction_master", $master,array("id" => $_POST['transaction_master_id']));
		}

		$transaction_tasks_result = $this->db->query("select * from transaction_tasks where id = '".$transaction_task."'");
        $transaction_tasks_array = $transaction_tasks_result->result_array();

		$this->save_audit_trail("Services", $transaction_tasks_array[0]["transaction_task"], "Transaction is completed.");

		redirect("transaction");
	}

	public function update_service_engagment_for_other_services($company_code, $transaction_master_id)
	{
		$billing_id_query = $this->db->query('select * from transaction_master_with_billing where transaction_master_id = "'.$transaction_master_id.'"');

		if ($billing_id_query->num_rows() > 0)
    	{
			$billings = $this->db_model->get_all_the_billing($transaction_master_id);

			$this->db->select('client_billing_info.*');
	        $this->db->from('client_billing_info');
	        $this->db->where('company_code', $company_code);
	        $this->db->where('deleted = 0');
			$client_billing_info_data = $this->db->get();

			$service_id_array = array();

			foreach($client_billing_info_data->result() as $g) 
			{
				array_push($service_id_array, $g->service);
			}

			$this->db->select('MAX(client_billing_info_id) as max_client_billing_id');
	        $this->db->from('client_billing_info');
	        $this->db->where('company_code', $company_code);
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

			if(count($billings) > 0)
			{
				for($i = 0; $i < count($billings); $i++ )
				{
					if(in_array($billings[$i]->service, $service_id_array)) //match
					{
						//echo json_encode("match: ".$check_is_potential_client_array[$i]['our_service_id']);
						$client_billing_info['amount'] = (float)str_replace(',', '', $billings[$i]->amount);
						$client_billing_info['currency'] = $billings[$i]->currency_id;
						$client_billing_info['unit_pricing'] = $billings[$i]->unit_pricing;
						$client_billing_info['servicing_firm'] = $this->session->userdata("firm_id");
						$client_billing_info['deactive'] = 0;

						$this->db->update("client_billing_info",$client_billing_info,array("company_code" =>  $billings[0]->company_code, "service" => $billings[$i]->service));
						//$haveService = false;
					}
					else //not match
					{
						//echo json_encode("not match: ".$billings[$i]['our_service_id']);
						$client_billing_info['company_code'] = $billings[$i]->company_code;
						$client_billing_info['client_billing_info_id'] = $max_id + 1;
						$client_billing_info['service'] = $billings[$i]->service;
						$client_billing_info['invoice_description'] = $billings[$i]->invoice_description;
						//(int)str_replace(',', '', $amount[$p]);
						$client_billing_info['amount'] = (float)str_replace(',', '', $billings[$i]->amount);
						$client_billing_info['currency'] = $billings[$i]->currency_id;
						$client_billing_info['unit_pricing'] = $billings[$i]->unit_pricing;
						$client_billing_info['servicing_firm'] = $this->session->userdata("firm_id");
						$this->db->insert("client_billing_info",$client_billing_info);

						$max_id = $max_id + 1;
					}
				}
			}
		}
	}

	public function update_service_engagment($check_is_potential_client_array)
	{
		$this->db->select('client_billing_info.*');
        $this->db->from('client_billing_info');
        $this->db->where('company_code', $check_is_potential_client_array[0]['company_code']);
        $this->db->where('deleted = 0');
		$client_billing_info_data = $this->db->get();

		$service_id_array = array();

		foreach($client_billing_info_data->result() as $g) 
		{
			array_push($service_id_array, $g->service);
		}

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

		for($i = 0; $i < count($check_is_potential_client_array); $i++ )
		{
			if(in_array($check_is_potential_client_array[$i]['our_service_id'], $service_id_array)) //match
			{
				$client_billing_info['amount'] = (float)str_replace(',', '', $check_is_potential_client_array[$i]['fee']);
				$client_billing_info['currency'] = $check_is_potential_client_array[$i]['currency_id'];
				$client_billing_info['unit_pricing'] = $check_is_potential_client_array[$i]['unit_pricing'];
				$client_billing_info['servicing_firm'] = $check_is_potential_client_array[$i]['servicing_firm'];
				$client_billing_info['deactive'] = 0;
				$this->db->update("client_billing_info",$client_billing_info,array("company_code" =>  $check_is_potential_client_array[0]['company_code'], "service" => $check_is_potential_client_array[$i]['our_service_id']));
			}
			else //not match
			{
				$client_billing_info['company_code'] = $check_is_potential_client_array[$i]['company_code'];
				$client_billing_info['client_billing_info_id'] = $max_id + 1;
				$client_billing_info['service'] = $check_is_potential_client_array[$i]['our_service_id'];
				$client_billing_info['invoice_description'] = $check_is_potential_client_array[$i]['invoice_description'];
				$client_billing_info['amount'] = (float)str_replace(',', '', $check_is_potential_client_array[$i]['fee']);
				$client_billing_info['currency'] = $check_is_potential_client_array[$i]['currency_id'];
				$client_billing_info['unit_pricing'] = $check_is_potential_client_array[$i]['unit_pricing'];
				$client_billing_info['servicing_firm'] = $check_is_potential_client_array[$i]['servicing_firm'];
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
	        $this->db->where('appoint_resign_flag = "appoint"');
			$check_transaction_client_officers = $this->db->get();

		foreach($check_transaction_client_officers->result() as $r) 
		{
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

			if(count($check_date) > 0){
				$date_of_cessation = $check_date[0]["date_of_cessation"];
				$date_of_appointment = $check_date[0]["date_of_appointment"];

				if($date_of_cessation == null && $k['date_of_cessation'] == null)
				{
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

		$q = $this->db->query("select * from client where deleted = 0"); //AND firm_id = '".$this->session->userdata('firm_id')."' // registration_no = '".$registration_no."' and

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
	            if($this->encryption->decrypt($row->registration_no) == $registration_no)
	            {
	                $row->registration_no = $this->encryption->decrypt($row->registration_no);
	                $row->company_name = $this->encryption->decrypt($row->company_name);
	                $data[] = $row;
	            }
            }
            echo json_encode($data);
        }
        echo FALSE;

	}

	public function check_filing_info()
	{
		$registration_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query("select client.*, filing.year_end, financial_year_period.period from client left join filing on filing.id = (select MAX(id) as filing_id from filing where filing.company_code = client.company_code) left join financial_year_period on financial_year_period.id = filing.financial_year_period_id where client.deleted = 0"); // client.registration_no = '".$registration_no."' and // client.firm_id = '".$this->session->userdata('firm_id')."' and 

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                if($this->encryption->decrypt($row->registration_no) == $registration_no)
	            {
	                $row->registration_no = $this->encryption->decrypt($row->registration_no);
	                $row->company_name = $this->encryption->decrypt($row->company_name);
	                $data[] = $row;
	            }
            }
            echo json_encode($data);
        }
        echo FALSE;

	}

	public function get_all_member()
	{
        $company_code = $_POST["company_code"];
        
        $q = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" and client.firm_id = "'.$this->session->userdata('firm_id').'" and client.deleted = 0 where member_shares.company_code="'.$company_code.'"and share_capital.class_id = 1 GROUP BY member_shares.field_type, member_shares.officer_id, member_shares.client_member_share_capital_id HAVING sum(member_shares.number_of_share) > 0');
        //,member_shares.client_member_share_capital_id
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
            	if($row->field_type == "individual")
                {
                    $row->identification_no = $this->encryption->decrypt($row->identification_no);
                    $row->name = $this->encryption->decrypt($row->name);
                }
                elseif($row->field_type == "company")
                {
                    $row->register_no = $this->encryption->decrypt($row->register_no);
                    $row->company_name = $this->encryption->decrypt($row->company_name);
                }
                else
                {
                    $row->registration_no = $this->encryption->decrypt($row->registration_no);
                    $row->client_company_name = $this->encryption->decrypt($row->client_company_name);
                }
                $data[] = $row;
            }
            echo json_encode($data);
        }
        echo FALSE;
    }

    public function delete_nominee_director()
    {
    	$transaction_master_id = $_POST["transaction_master_id"];
    	$id = $_POST["client_nominee_director_id"];
		$officer_id = $_POST["delete_nomi_officer_id"];
		$field_type = $_POST["delete_nomi_officer_field_type"];
		$delete_company_code = $_POST["delete_company_code"];
		$client_nominee_director_name = $_POST["client_nominee_director_name"];

		$data["deleted"] = 1;

		$latest_q = $this->db->query('select transaction_client_nominee_director.* from transaction_client_nominee_director where transaction_client_nominee_director.id ="'.$id.'" AND transaction_client_nominee_director.nomi_officer_id = "'.$officer_id.'" AND transaction_client_nominee_director.nomi_officer_field_type = "'.$field_type.'"');

        if ($latest_q->num_rows() > 0) {
			$this->db->update("transaction_client_nominee_director", $data, array("id" => $id));
		}
		else
		{
			$q = $this->db->query('select client_nominee_director.* from client_nominee_director where client_nominee_director.company_code ="'.$delete_company_code.'" AND client_nominee_director.nomi_officer_id = "'.$officer_id.'" AND client_nominee_director.nomi_officer_field_type = "'.$field_type.'"');

			if ($q->num_rows() > 0) {
				$current_nominee_director_info = $q->result_array();
				$data['transaction_id']=  $transaction_master_id;
				$data['company_code']=$current_nominee_director_info[0]['company_code'];
				$data['nd_officer_id']=$current_nominee_director_info[0]['nd_officer_id'];
				$data['nd_officer_field_type']=$current_nominee_director_info[0]['nd_officer_field_type'];
				$data['nd_date_entry']=$current_nominee_director_info[0]['nd_date_entry'];
				$data['nomi_officer_id']=$current_nominee_director_info[0]['nomi_officer_id'];
				$data['nomi_officer_field_type']=$current_nominee_director_info[0]['nomi_officer_field_type'];
				$data['date_become_nominator']=$current_nominee_director_info[0]['date_become_nominator'];
				$data['date_of_cessation']=$current_nominee_director_info[0]['date_of_cessation'];
				$data['supporting_document'] = $current_nominee_director_info[0]['supporting_document'];
				$data['deleted'] = 1;
				$this->db->insert("transaction_client_nominee_director",$data);
			}
		}

		$secretary_audit_trail["user_id"] = $this->session->userdata("user_id");
		$secretary_audit_trail["modules"] = "Services";
		$secretary_audit_trail["events"] = "Register of Nominee Director";
		$secretary_audit_trail["actions"] = "Delete ".$client_nominee_director_name." nominee director info.";
		$this->db->insert("secretary_audit_trail",$secretary_audit_trail);

		$get_current_client_nominee_director_data = $this->transaction_model->getCurrentClientNomineeDirector($delete_company_code, $transaction_master_id);

		$get_latest_client_nominee_director_data = $this->transaction_model->getLatestClientNomineeDirector($delete_company_code, $transaction_master_id);
 
        echo json_encode(array("status" => 1, "current_client_nominee_director_data" => $get_current_client_nominee_director_data, "latest_client_nominee_director_data" => $get_latest_client_nominee_director_data));
    }

    public function delete_register_controller ()
	{
		$id = $_POST["client_controller_id"];
		$officer_id = $_POST["client_controller_officer_id"];
		$field_type = $_POST["client_controller_field_type"];
		$delete_company_code = $_POST["delete_company_code"];
		$client_controller_name = $_POST["client_controller_name"];
		$data["deleted"] = 1;

		$latest_q = $this->db->query('select transaction_client_controller.* from transaction_client_controller where transaction_client_controller.id ="'.$id.'" AND transaction_client_controller.officer_id = "'.$officer_id.'" AND transaction_client_controller.field_type = "'.$field_type.'"');

        if ($latest_q->num_rows() > 0) {
			$this->db->update("transaction_client_controller", $data, array("id" => $id));
		}
		else
		{
			$q = $this->db->query('select client_controller.* from client_controller where client_controller.company_code ="'.$delete_company_code.'" AND client_controller.officer_id = "'.$officer_id.'" AND client_controller.field_type = "'.$field_type.'"');

			if ($q->num_rows() > 0) {
				$current_controller_info = $q->result_array();
				$data['transaction_id']=  $this->session->userdata('transaction_id');
				$data['company_code']=$current_controller_info[0]['company_code'];
				$data['officer_id']=$current_controller_info[0]['officer_id'];
				$data['field_type']=$current_controller_info[0]['field_type'];
				$data['date_of_registration']=$current_controller_info[0]['date_of_registration'];
				$data['date_of_notice']=$current_controller_info[0]['date_of_notice'];
				$data['is_confirm_by_reg_controller']=$current_controller_info[0]['is_confirm_by_reg_controller'];
				$data['confirmation_received_date']=$current_controller_info[0]['confirmation_received_date'];
				$data['date_of_entry']=$current_controller_info[0]['date_of_entry'];
				$data['date_of_cessation']=$current_controller_info[0]['date_of_cessation'];
				$data['supporting_document'] = $current_controller_info[0]['supporting_document'];
				$data['deleted'] = 1;
				$this->db->insert("transaction_client_controller",$data);
			}
		}

		$secretary_audit_trail["user_id"] = $this->session->userdata("user_id");
		$secretary_audit_trail["modules"] = "Services";
		$secretary_audit_trail["events"] = "Register of Controller";
		$secretary_audit_trail["actions"] = "Delete ".$client_controller_name." controller info.";

		$this->db->insert("secretary_audit_trail",$secretary_audit_trail);
		//$get_edit_client_controller_data = $this->db_model->getClientController($delete_company_code);
		$get_current_client_controller_data = $this->transaction_model->getCurrentClientController($delete_company_code, $this->session->userdata('transaction_id'));

		$get_latest_client_controller_data = $this->transaction_model->getLatestClientController($delete_company_code, $this->session->userdata('transaction_id'));
 
        echo json_encode(array("status" => 1, "current_client_controller_data" => $get_current_client_controller_data, "latest_client_controller_data" => $get_latest_client_controller_data));
	}

    public function get_controller_info()
    {
        $controller_id = $_POST["controller_id"];
        $transaction_id = $_POST["transaction_id"];
        $office_id = $_POST["office_id"];
        $field_type = $_POST["field_type"];

        $get_edit_client_controller_data = $this->transaction_model->getEditClientController($controller_id, $transaction_id, $office_id, $field_type);

        echo json_encode(array("status" => 1, "list_of_controller" => $get_edit_client_controller_data));
    }

    public function get_nominee_director_info()
	{
		$nominee_director_id = $_POST["nominee_director_id"];
		$transaction_id = $_POST["transaction_id"];
		$nomioffice_id = $_POST["nomioffice_id"];
        $nomifield_type = $_POST["nomifield_type"];

        $get_edit_client_nominee_director_data = $this->transaction_model->getEditClientNomineeDirector($nominee_director_id, $transaction_id, $nomioffice_id, $nomifield_type);

        echo json_encode(array("status" => 1, "list_of_nominee_director" => $get_edit_client_nominee_director_data));
	}

    public function add_register_controller ()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$cancel_by_system["completed"] = 1;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = $this->encryption->encrypt(strtoupper($_POST['registration_no']));
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
			$this->db->update("transaction_master",$edit_transaction,array("id" => $_POST['transaction_master_id']));
			$transaction_id = $_POST['transaction_master_id'];
			$transaction_code = $_POST['transaction_code'];
		}

		$data['transaction_id']= $transaction_id;
		$data['company_code']=$_POST['company_code'];
		$data['officer_id']=$_POST['officer_id'];
		$data['field_type']=$_POST['officer_field_type'];
		if(isset($_POST['entity_name']))
		{
			$controller_name=$_POST['entity_name'];
		}
		else if(isset($_POST['individual_controller_name']))
		{
			$controller_name=$_POST['individual_controller_name'];
		}

		$data['date_of_registration']=$_POST['date_appointed'];
		$data['date_of_notice']=$_POST['date_of_notice'];
		if(isset($_POST['radio_individual_confirm_registrable_controller']))
		{
			$data['is_confirm_by_reg_controller']=$_POST['radio_individual_confirm_registrable_controller'];
		}
		else if(isset($_POST['radio_corp_confirm_registrable_controller']))
		{
			$data['is_confirm_by_reg_controller']=$_POST['radio_corp_confirm_registrable_controller'];
		}
		$data['confirmation_received_date']=$_POST['date_confirmation'];
		$data['date_of_entry']=$_POST['date_of_entry'];
		$data['date_of_cessation']=$_POST['date_ceased'];

		$hidden_supporting_document = $_POST['hidden_supporting_document'];
		$filesCount = count($_FILES['supporting_document']['name']);
        $individual_attachment = array();

        $_FILES['supportDoc']['name'] = $_FILES['supporting_document']['name'];
        $_FILES['supportDoc']['type'] = $_FILES['supporting_document']['type'];
        $_FILES['supportDoc']['tmp_name'] = $_FILES['supporting_document']['tmp_name'];
        $_FILES['supportDoc']['error'] = $_FILES['supporting_document']['error'];
        $_FILES['supportDoc']['size'] = $_FILES['supporting_document']['size'];

        $uploadPath = './uploads/supporting_doc';
        $config['upload_path'] = $uploadPath;
        $config['allowed_types'] = '*';
        
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if($this->upload->do_upload('supportDoc'))
        {
            $fileData = $this->upload->data();
            $individual_attachment[] = $fileData['file_name'];
        }
        $attachment = json_encode($individual_attachment);

        if($hidden_supporting_document != "")
        {
            $data['supporting_document'] = $hidden_supporting_document;
        }
        else
        {
            $data['supporting_document'] = $attachment;
        }
        
		$q = $this->db->get_where("transaction_client_controller", array("id" => $_POST['transaction_client_controller_id'], "transaction_id" => $transaction_id));

		if (!$q->num_rows())
		{
			$this->db->insert("transaction_client_controller",$data);
			$insert_transaction_client_controller_id = $this->db->insert_id();
			$this->save_audit_trail("Services", "Register of Controller", $controller_name." controller is added.");
		}
		else
		{
			$this->db->update("transaction_client_controller",$data,array("id" => $_POST['transaction_client_controller_id']));
			$this->save_audit_trail("Services", "Register of Controller", $controller_name." controller is edited.");
		}

		$get_current_client_controller_data = $this->transaction_model->getCurrentClientController($_POST['company_code'], $transaction_id);

		$get_latest_client_controller_data = $this->transaction_model->getLatestClientController($_POST['company_code'], $transaction_id);

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "current_client_controller_data" => $get_current_client_controller_data, "latest_client_controller_data" => $get_latest_client_controller_data, "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function add_nominee_director()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['nomi_company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$cancel_by_system["completed"] = 1;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['nomi_company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['nomi_company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = $this->encryption->encrypt(strtoupper($_POST['registration_no']));
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
			$this->db->update("transaction_master",$edit_transaction,array("id" => $_POST['transaction_master_id']));
			$transaction_id = $_POST['transaction_master_id'];
			$transaction_code = $_POST['transaction_code'];
		}

		$transaction_tasks_result = $this->db->query("select * from transaction_tasks where id = '".$_POST['transaction_task_id']."'");
        $transaction_tasks_array = $transaction_tasks_result->result_array();

		$data['transaction_id']=$transaction_id;
		$data['company_code']=$_POST['nomi_company_code'];
		$data['nd_officer_id']=$_POST['nd_officer_id'];
		$data['nd_officer_field_type']=$_POST['nd_officer_field_type'];
		$data['nd_date_entry']=$_POST['nd_date_entry'];
		$nominee_director_name=$_POST['nd_name'];

		$data['nomi_officer_id']=$_POST['nomi_officer_id'];
		$data['nomi_officer_field_type']=$_POST['nomi_officer_field_type'];
		$data['date_become_nominator']=$_POST['date_become_nominator'];
		$data['date_of_cessation']=$_POST['date_ceased_nominator'];

		$hidden_supporting_document = $_POST['nd_hidden_supporting_document'];
		$filesCount = count($_FILES['nd_supporting_document']['name']);
        $individual_attachment = array();
 
        $_FILES['supportDoc']['name'] = $_FILES['nd_supporting_document']['name'];
        $_FILES['supportDoc']['type'] = $_FILES['nd_supporting_document']['type'];
        $_FILES['supportDoc']['tmp_name'] = $_FILES['nd_supporting_document']['tmp_name'];
        $_FILES['supportDoc']['error'] = $_FILES['nd_supporting_document']['error'];
        $_FILES['supportDoc']['size'] = $_FILES['nd_supporting_document']['size'];

        $uploadPath = './uploads/supporting_doc';
        $config['upload_path'] = $uploadPath;
        $config['allowed_types'] = '*';
        
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if($this->upload->do_upload('supportDoc'))
        {
            $fileData = $this->upload->data();
            $individual_attachment[] = $fileData['file_name'];
        }
        $attachment = json_encode($individual_attachment);

        if($hidden_supporting_document != "")
        {
            $data['supporting_document'] = $hidden_supporting_document;
        }
        else
        {
            $data['supporting_document'] = $attachment;
        }

		$q = $this->db->get_where("transaction_client_nominee_director", array("id" => $_POST['client_nominee_director_id'], "transaction_id" => $transaction_id));

		if (!$q->num_rows())
		{
			$this->db->insert("transaction_client_nominee_director",$data);
			$insert_client_nominee_director_id = $this->db->insert_id();

			$this->save_audit_trail("Services", $transaction_tasks_array[0]["transaction_task"], $nominee_director_name." nominee director is added.");
		}
		else
		{
			$this->db->update("transaction_client_nominee_director",$data,array("id" => $_POST['client_nominee_director_id']));

			$this->save_audit_trail("Services", $transaction_tasks_array[0]["transaction_task"], $nominee_director_name." nominee director is edited.");
		}
		
		$get_current_client_nominee_director_data = $this->transaction_model->getCurrentClientNomineeDirector($_POST['nomi_company_code'], $transaction_id);

		$get_latest_client_nominee_director_data = $this->transaction_model->getLatestClientNomineeDirector($_POST['nomi_company_code'], $transaction_id);

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "current_client_nominee_director_data" => $get_current_client_nominee_director_data, "latest_client_nominee_director_data" => $get_latest_client_nominee_director_data, "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function get_register_nominee_director_info()
	{
		$registration_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where deleted = 0');

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registration_no)
	            {
	            	$company_code = $client_info_row["company_code"];
	            	$client_info_row["registration_no"] = $this->encryption->decrypt($client_info_row["registration_no"]);
	            	$client_info_row["company_name"] = $this->encryption->decrypt($client_info_row["company_name"]);
	                $client_info_data = $client_info_row;
	            }
	        }
	        if($client_info_data != null)
	        {
	            $q = $this->db->query('select 
	                client_nominee_director.*, 
	                client_nominee_director.company_code as client_nominee_director_company_code, 
	                client_nominee_director.id as client_nominee_director_id, 
	                nd_officer.name as nd_officer_name, 
	                nomi_officer.*, 
	                nomi_officer.unit_no1 as nomi_officer_unit_no1, 
	                nomi_officer.unit_no2 as nomi_officer_unit_no2,
	                officer_company.*, 
	                officer_company.company_name as officer_company_company_name, 
	                client.*, client.company_name as client_company_name, 
	                client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2,
	                nationality.nationality as nomi_officer_nationality_name,
                    company_type.company_type as client_company_type 
	                from client_nominee_director 
	                left join officer as nd_officer on client_nominee_director.nd_officer_id = nd_officer.id and client_nominee_director.nd_officer_field_type = nd_officer.field_type 
	                left join officer as nomi_officer on client_nominee_director.nomi_officer_id = nomi_officer.id and client_nominee_director.nomi_officer_field_type = nomi_officer.field_type
	                left join officer_company on client_nominee_director.nomi_officer_id = officer_company.id and client_nominee_director.nomi_officer_field_type = officer_company.field_type 
	                left join client on client.id = client_nominee_director.nomi_officer_id AND client_nominee_director.nomi_officer_field_type = "client"
	                left join nationality on nationality.id = nomi_officer.nationality 
	                left join company_type on company_type.id = client.company_type where client_nominee_director.company_code ="'.$company_code.'" and client_nominee_director.deleted = 0');

	            if ($q->num_rows() > 0) {
	                foreach (($q->result()) as $row) {
	                    $row->nd_officer_name = $this->encryption->decrypt($row->nd_officer_name);
	                    $row->transaction_id = "";
	                    if($row->nomi_officer_field_type == "individual")
	                    {
	                        $row->identification_no = $this->encryption->decrypt($row->identification_no);
	                        $row->name = $this->encryption->decrypt($row->name);
	                    }
	                    elseif($row->nomi_officer_field_type == "company")
	                    {
	                        $row->register_no = $this->encryption->decrypt($row->register_no);
	                        $row->officer_company_company_name = $this->encryption->decrypt($row->officer_company_company_name);
	                    }
	                    elseif($row->nomi_officer_field_type == "client")
	                    {
	                        $row->registration_no = $this->encryption->decrypt($row->registration_no);
	                        $row->client_company_name = $this->encryption->decrypt($row->client_company_name);
	                    }
	                    
	                    $data[] = $row;
	                }
	                echo json_encode(array("nominee_director" => $data));
	            }

		        echo FALSE;
			}	
	        else
			{
				echo FALSE;
			}
		}
		else
		{
			echo FALSE;
		}
	}

    public function get_register_controller_info()
    {
    	$registration_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where deleted = 0');

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registration_no)
	            {
	            	$company_code = $client_info_row["company_code"];
	            	$client_info_row["registration_no"] = $this->encryption->decrypt($client_info_row["registration_no"]);
	            	$client_info_row["company_name"] = $this->encryption->decrypt($client_info_row["company_name"]);
	                $client_info_data = $client_info_row;
	            }
	        }
	        if($client_info_data != null)
	        {
	        	$q = $this->db->query('select client_controller.*, client_controller.company_code as client_controller_company_code, client_controller.id as client_controller_id, client_controller.field_type as client_controller_field_type, officer.*, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.company_name as officer_company_company_name, client.*, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, nationality.nationality as officer_nationality_name, company_type.company_type as client_company_type from client_controller left join officer on client_controller.officer_id = officer.id and client_controller.field_type = officer.field_type left join officer_company on client_controller.officer_id = officer_company.id and client_controller.field_type = officer_company.field_type left join client on client.id = client_controller.officer_id AND client_controller.field_type = "client" left join nationality on nationality.id = officer.nationality left join company_type on company_type.id = client.company_type where client_controller.company_code ="'.$company_code.'" and client_controller.deleted = 0 AND client_controller.date_of_cessation = ""');//AND client_controller.date_of_cessation = ""
	        	if ($q->num_rows() > 0) {
	                foreach (($q->result()) as $row) {
	                	$row->transaction_id = "";
	                    if($row->client_controller_field_type == "individual")
	                    {
	                        $row->identification_no = $this->encryption->decrypt($row->identification_no);
	                        $row->name = $this->encryption->decrypt($row->name);
	                    }
	                    elseif($row->client_controller_field_type == "company")
	                    {
	                        $row->register_no = $this->encryption->decrypt($row->register_no);
	                        $row->officer_company_company_name = $this->encryption->decrypt($row->officer_company_company_name);
	                    }
	                    elseif($row->client_controller_field_type == "client")
	                    {
	                        $row->registration_no = $this->encryption->decrypt($row->registration_no);
	                        $row->client_company_name = $this->encryption->decrypt($row->client_company_name);
	                    }
	                    $data[] = $row;
	                }
	                echo json_encode(array("controller" => $data));
	            }
		        echo FALSE;

		        // $current_controller_info = $this->transaction_model->getCurrentClientController($company_code);

		        // if($current_controller_info)
		        // {
		        // 	echo json_encode(array("controller" => $current_controller_info));
		        // }
		        // else
		        // {
		        // 	echo FALSE;
		        // }
			}	
	        else
			{
				echo FALSE;
			}
		}
		else
		{
			echo FALSE;
		}
    }

	public function get_resign_director_info()
	{
		$registration_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where deleted = 0');

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registration_no)
	            {
	            	$company_code = $client_info_row["company_code"];
	            	$client_info_row["registration_no"] = $this->encryption->decrypt($client_info_row["registration_no"]);
	            	$client_info_row["company_name"] = $this->encryption->decrypt($client_info_row["company_name"]);
	                $client_info_data = $client_info_row;
	            }
	        }
	        if($client_info_data != null)
	        {
	        	$q = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where client_officers.company_code = (select client.company_code from client where client.company_code = '".$company_code."' and client.deleted = 0) AND client_officers.position = '1' AND date_of_cessation = '' order by client_officers.id"); // and client.firm_id = '".$this->session->userdata('firm_id')."'

	        	// $q = $this->db->query('select 
	         //        client_nominee_director.*, 
	         //        client_nominee_director.company_code as client_nominee_director_company_code, 
	         //        client_nominee_director.id as client_nominee_director_id, 
	         //        nd_officer.name as nd_officer_name, 
	         //        nomi_officer.*, 
	         //        nomi_officer.unit_no1 as nomi_officer_unit_no1, 
	         //        nomi_officer.unit_no2 as nomi_officer_unit_no2,
	         //        officer_company.*, 
	         //        officer_company.company_name as officer_company_company_name, 
	         //        client.*, client.company_name as client_company_name, 
	         //        client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2,
	         //        nationality.nationality as nomi_officer_nationality_name,
          //           company_type.company_type as client_company_type 
	         //        from client_nominee_director 
	         //        left join officer as nd_officer on client_nominee_director.nd_officer_id = nd_officer.id and client_nominee_director.nd_officer_field_type = nd_officer.field_type 
	         //        left join officer as nomi_officer on client_nominee_director.nomi_officer_id = nomi_officer.id and client_nominee_director.nomi_officer_field_type = nomi_officer.field_type
	         //        left join officer_company on client_nominee_director.nomi_officer_id = officer_company.id and client_nominee_director.nomi_officer_field_type = officer_company.field_type 
	         //        left join client on client.id = client_nominee_director.nomi_officer_id AND client_nominee_director.nomi_officer_field_type = "client"
	         //        left join nationality on nationality.id = nomi_officer.nationality 
	         //        left join company_type on company_type.id = client.company_type where client_nominee_director.company_code ="'.$company_code.'" and client_nominee_director.deleted = 0');

		        if ($q->num_rows() > 0) {

		            foreach (($q->result()) as $row) {
		            	if($row->field_type == "individual")
		                {
		                    $row->identification_no = $this->encryption->decrypt($row->identification_no);
		                    $row->name = $this->encryption->decrypt($row->name);
		                }
		                elseif($row->field_type == "company")
		                {
		                    $row->register_no = $this->encryption->decrypt($row->register_no);
		                    $row->company_name = $this->encryption->decrypt($row->company_name);
		                }
		                $data[] = $row;
		            }

		            $currency = $this->transaction_model->get_currency_list();

		            echo json_encode(array("director" => $data, "currency" => $currency));
		        }
		        echo FALSE;
			}	
	        else
			{
				echo FALSE;
			}
		}
		else
		{
			echo FALSE;
		}
	}
	public function get_resign_auditor_info()
	{
		$registration_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where deleted = 0');

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registration_no)
	            {
	            	$company_code = $client_info_row["company_code"];
	            	$client_info_row["registration_no"] = $this->encryption->decrypt($client_info_row["registration_no"]);
	            	$client_info_row["company_name"] = $this->encryption->decrypt($client_info_row["company_name"]);
	                $client_info_data = $client_info_row;
	            }
	        }
	        //print_r($client_info_data);
	        if($client_info_data != null)
	        {
	        	$q = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type left join client on client.id = client_officers.officer_id and client_officers.field_type = 'client' where client_officers.company_code = (select client.company_code from client where client.company_code = '".$company_code."' and client.deleted = 0) AND client_officers.position = '5' AND date_of_cessation = '' order by client_officers.id");// and client.firm_id = '".$this->session->userdata('firm_id')."'
	        	//print_r($q->result());
		        if ($q->num_rows() > 0) {
		            foreach (($q->result()) as $row) {
		            	if($row->field_type == "individual")
		                {
		                    $row->identification_no = $this->encryption->decrypt($row->identification_no);
		                    $row->name = $this->encryption->decrypt($row->name);
		                }
		                elseif($row->field_type == "company")
		                {
		                    $row->register_no = $this->encryption->decrypt($row->register_no);
		                    $row->company_name = $this->encryption->decrypt($row->company_name);
		                }
		                elseif($row->field_type == "client")
		                {
		                    $row->registration_no = $this->encryption->decrypt($row->registration_no);
		                    $row->client_company_name = $this->encryption->decrypt($row->client_company_name);
		                }
		                $data[] = $row;
		            }
		            echo json_encode($data);
		        }
		        echo FALSE;
			}	
	        else
			{
				echo FALSE;
			}
		}
		else
		{
			echo FALSE;
		}
	}

	public function get_resign_secretarial_info()
	{
		$registration_no = strtoupper($_POST["registration_no"]);

		$q = $this->db->query('select * from client where deleted = 0');

		if ($q->num_rows() > 0) 
        {
        	$client_info = $q->result_array();
        	foreach ($client_info as $client_info_row) 
	        {
	            if($this->encryption->decrypt($client_info_row["registration_no"]) == $registration_no)
	            {
	            	$company_code = $client_info_row["company_code"];
	            	$client_info_row["registration_no"] = $this->encryption->decrypt($client_info_row["registration_no"]);
	            	$client_info_row["company_name"] = $this->encryption->decrypt($client_info_row["company_name"]);
	                $client_info_data = $client_info_row;
	            }
	        }
	        //print_r($client_info_data);
	        if($client_info_data != null)
	        {
	        	$q = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type left join client on client.id = client_officers.officer_id and client_officers.field_type = 'client' where client_officers.company_code = (select client.company_code from client where client.company_code = '".$company_code."' and client.deleted = 0) AND client_officers.position = '4' AND date_of_cessation = '' order by client_officers.id");// and client.firm_id = '".$this->session->userdata('firm_id')."'
	        	//print_r($q->result());
		        if ($q->num_rows() > 0) {
		            foreach (($q->result()) as $row) {
		            	if($row->field_type == "individual")
		                {
		                    $row->identification_no = $this->encryption->decrypt($row->identification_no);
		                    $row->name = $this->encryption->decrypt($row->name);
		                }
		                elseif($row->field_type == "company")
		                {
		                    $row->register_no = $this->encryption->decrypt($row->register_no);
		                    $row->company_name = $this->encryption->decrypt($row->company_name);
		                }
		                elseif($row->field_type == "client")
		                {
		                    $row->registration_no = $this->encryption->decrypt($row->registration_no);
		                    $row->client_company_name = $this->encryption->decrypt($row->client_company_name);
		                }
		                $data[] = $row;
		            }
		            echo json_encode($data);
		        }
		        echo FALSE;
			}	
	        else
			{
				echo FALSE;
			}
		}
		else
		{
			echo FALSE;
		}
	}

	public function get_all_director_retiring()
    {
    	$company_code = $_POST["company_code"];

        $q = $this->db->query('select client_officers.*, officer.identification_no, officer.name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type where position = 1 AND  date_of_cessation = "" AND company_code ="'.$company_code.'"');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
            	if($row->field_type == "individual")
                {
                    $row->identification_no = $this->encryption->decrypt($row->identification_no);
                    $row->name = $this->encryption->decrypt($row->name);
                }
                elseif($row->field_type == "company")
                {
                    $row->register_no = $this->encryption->decrypt($row->register_no);
                    $row->company_name = $this->encryption->decrypt($row->company_name);
                }
                $data[] = $row;
            }
            echo json_encode($data);
        }

        echo false;
    }

	public function add_appoint_resign_auditor()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$cancel_by_system["completed"] = 1;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = $this->encryption->encrypt(strtoupper($_POST['registration_no']));
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
			$this->db->update("transaction_master",$edit_transaction,array("id" => $_POST["transaction_master_id"]));
			$transaction_id = $_POST["transaction_master_id"];
			$transaction_code = $_POST["transaction_code"];
		}

		$transaction_meeting_date['transaction_master_id'] = $transaction_id;
		$transaction_meeting_date["notice_date"] = $_POST['notice_date'];
		$transaction_meeting_date['director_meeting_date'] = $_POST['director_meeting_date'];
		$transaction_meeting_date['director_meeting_time'] = $_POST['director_meeting_time'];
		$transaction_meeting_date['member_meeting_date'] = $_POST['member_meeting_date'];
		$transaction_meeting_date['member_meeting_time'] = $_POST['member_meeting_time'];
		$transaction_meeting_date['address_type'] = $_POST['address_type'];
		//registered_offis_address
		if(isset($_POST['registered_postal_code1']))
        {
            $transaction_meeting_date['registered_postal_code1'] = strtoupper($_POST['registered_postal_code1']);
        }
        else
        {
            $transaction_meeting_date['registered_postal_code1'] = "";
        }

        if(isset($_POST['registered_street_name1']))
        {
            $transaction_meeting_date['registered_street_name1'] = strtoupper($_POST['registered_street_name1']);
        }
        else
        {
            $transaction_meeting_date['registered_street_name1'] = "";
        }

        if(isset($_POST['registered_building_name1']))
        {
            $transaction_meeting_date['registered_building_name1'] = strtoupper($_POST['registered_building_name1']);
        }
        else
        {
            $transaction_meeting_date['registered_building_name1'] = "";
        }

        if(isset($_POST['registered_unit_no1']))
        {
            $transaction_meeting_date['registered_unit_no1'] = strtoupper($_POST['registered_unit_no1']);
        }
        else
        {
            $transaction_meeting_date['registered_unit_no1'] = "";
        }

        if(isset($_POST['registered_unit_no2']))
        {
            $transaction_meeting_date['registered_unit_no2'] = strtoupper($_POST['registered_unit_no2']);
        }
        else
        {
            $transaction_meeting_date['registered_unit_no2'] = "";
        }
        //Local_address
		if(isset($_POST['postal_code1']))
        {
            $transaction_meeting_date['postal_code1'] = strtoupper($_POST['postal_code1']);
        }
        else
        {
            $transaction_meeting_date['postal_code1'] = "";
        }

        if(isset($_POST['street_name1']))
        {
            $transaction_meeting_date['street_name1'] = strtoupper($_POST['street_name1']);
        }
        else
        {
            $transaction_meeting_date['street_name1'] = "";
        }
		$transaction_meeting_date['building_name1'] = strtoupper($_POST['building_name1']);
        $transaction_meeting_date['unit_no1'] = strtoupper($_POST['unit_no1']);
        $transaction_meeting_date['unit_no2'] = strtoupper($_POST['unit_no2']);
        //foreign_address
        if(isset($_POST['foreign_address1']))
        {
            $transaction_meeting_date['foreign_address1'] = strtoupper($_POST['foreign_address1']);
        }
        else
        {
            $transaction_meeting_date['foreign_address1'] = "";
        }
        if(isset($_POST['foreign_address2']))
        {
            $transaction_meeting_date['foreign_address2'] = strtoupper($_POST['foreign_address2']);
        }
        else
        {
            $transaction_meeting_date['foreign_address2'] = "";
        }
        if(isset($_POST['foreign_address3']))
        {
            $transaction_meeting_date['foreign_address3'] = strtoupper($_POST['foreign_address3']);
        }
        else
        {
            $transaction_meeting_date['foreign_address3'] = "";
        }

		$transaction_meeting_date_query = $this->db->get_where("transaction_appoint_auditor_date", array("transaction_master_id" => $transaction_id));

		if (!$transaction_meeting_date_query->num_rows())
		{
			$this->db->insert("transaction_appoint_auditor_date",$transaction_meeting_date);
		} 
		else 
		{
			$this->db->update("transaction_appoint_auditor_date",$transaction_meeting_date,array("transaction_master_id" => $transaction_id));
		}

		if(count($_POST['identification_register_no']) > 0)
		{
			$this->db->delete("transaction_client_officers",array('company_code'=>$_POST['company_code'], 'transaction_id'=>$transaction_id));
			for($i = 0; $i < count($_POST['identification_register_no']); $i++ )
			{
				if($_POST['identification_register_no'][$i] != "" && $_POST['identification_register_no'][$i] != null)
				{
					$data['transaction_id']=$transaction_id;
					$data['company_code']=$_POST['company_code'];
					$data['officer_id']=$_POST['officer_id'][$i];
					$data['field_type']=$_POST['officer_field_type'][$i];
					$data['position'] = 5;
					$data['alternate_of']='';
					$data['date_of_appointment'] = $_POST['date_of_appointment'][$i];
					$data['date_of_cessation'] = "";
					$data['appoint_resign_flag'] = "appoint";
					$data['retiring'] = 0;
					
					$q = $this->db->get_where("transaction_client_officers", array("id" => $_POST['client_officer_id'][$i], "transaction_id" => $transaction_id));

					if (!$q->num_rows())
					{
						$this->db->insert("transaction_client_officers",$data);
						$insert_client_officers_id = $this->db->insert_id();

						$this->save_audit_trail("Services", "Appointment and Resign of Auditor", "Appointment of Auditor is added.");
					} 
					else 
					{
						$this->db->update("transaction_client_officers",$data,array("id" => $_POST['client_officer_id'][$i], "transaction_id" => $transaction_id));

						$this->save_audit_trail("Services", "Appointment and Resign of Auditor", "Appointment of Auditor is edited.");
					}
				}
			}
		}

		if(count($_POST['hidden_resign_identification_register_no']) > 0)
		{
			for($i = 0; $i < count($_POST['hidden_resign_identification_register_no']); $i++ )
			{
				$data['transaction_id']=  $transaction_id;
				$data['company_code'] = $_POST['company_code'];
				$data['officer_id'] = $_POST['resign_officer_id'][$i];
				$data['field_type'] = $_POST['resign_officer_field_type'][$i];
				$data['position'] = 5;
				$data['alternate_of']='';
				$data['date_of_appointment'] = $_POST['hidden_date_of_appointment'][$i];
				$data['date_of_cessation'] = $_POST['hidden_date_of_cessation'][$i];
				$data['appoint_resign_flag'] = "resign";
				$data['retiring'] = 0;

				if($_POST["hidden_resign_auditor_reason"][$i] == undefined)
				{
					$reason["reason"] = "";
					$reason["reason_selected"] = (($_POST['hidden_resign_auditor_reason_selection'][$i] != "null" && $_POST['hidden_resign_auditor_reason_selection'][$i] != undefined)?$_POST['hidden_resign_auditor_reason_selection'][$i]:NULL);
				}
				else
				{
					$reason["reason"] = strtoupper($_POST["hidden_resign_auditor_reason"][$i]);
					$reason["reason_selected"] = (($_POST['hidden_resign_auditor_reason_selection'][$i] != "null" && $_POST['hidden_resign_auditor_reason_selection'][$i] != undefined)?$_POST['hidden_resign_auditor_reason_selection'][$i]:NULL);
				}
				
				$q = $this->db->get_where("transaction_client_officers", array("id" => $_POST['resign_client_officer_id'][$i], "transaction_id" => $transaction_id));

				if (!$q->num_rows())
				{
					$this->db->insert("transaction_client_officers",$data);
					$insert_client_officers_id = $this->db->insert_id();

					$reason["transaction_client_officers_id"] = $insert_client_officers_id;

					$this->db->insert("transaction_resign_officer_reason",$reason);

					$this->save_audit_trail("Services", "Appointment and Resign of Auditor", "Resignation of Auditor is added.");
				} 
				else 
				{
					$this->db->update("transaction_client_officers",$data,array("id" => $_POST['resign_client_officer_id'][$i], "transaction_id" => $transaction_id));

					$this->db->update("transaction_resign_officer_reason",$reason,array("transaction_client_officers_id" => $_POST['resign_client_officer_id'][$i]));

					$this->save_audit_trail("Services", "Appointment and Resign of Auditor", "Resignation of Auditor is edited.");
				}
			}
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $transaction_id));

		$this->data['transaction_client_officers'] = $this->transaction_model->getTransactionResignClientOfficer($transaction_id);

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_client_officers" => $this->data['transaction_client_officers'], "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function add_resign_director()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$cancel_by_system["completed"] = 1;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = $this->encryption->encrypt(strtoupper($_POST['registration_no']));
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
			$this->db->update("transaction_master",$edit_transaction,array("id" => $_POST["transaction_master_id"]));
			$transaction_id = $_POST["transaction_master_id"];
			$transaction_code = $_POST["transaction_code"];
		}

		if(count($_POST['identification_register_no']) > 0)
		{
			$this->db->delete("transaction_client_officers",array('company_code'=>$_POST['company_code'], 'transaction_id'=>$transaction_id));
			for($i = 0; $i < count($_POST['identification_register_no']); $i++ )
			{
				if($_POST['identification_register_no'][$i] != "" && $_POST['identification_register_no'][$i] != null)
				{
					$data['transaction_id']=$transaction_id;
					$data['company_code']=$_POST['company_code'];
					$data['officer_id']=$_POST['officer_id'][$i];
					$data['field_type']=$_POST['officer_field_type'][$i];
					$data['position'] = 1;
					$data['alternate_of']='';
					$data['date_of_appointment'] = $_POST['date_of_appointment'][$i];
					$data['date_of_cessation'] = "";
					$data['appoint_resign_flag'] = "appoint";
					$data['retiring'] = 0;
					
					$q = $this->db->get_where("transaction_client_officers", array("id" => $_POST['client_officer_id'][$i], "transaction_id" => $transaction_id));

					if (!$q->num_rows())
					{
						$this->db->insert("transaction_client_officers",$data);
						$insert_client_officers_id = $this->db->insert_id();
						$this->save_audit_trail("Services", "Appointment and Resign of Director", "Appointment of Director is added.");
					} 
					else 
					{
						$this->db->update("transaction_client_officers",$data,array("id" => $_POST['client_officer_id'][$i], "transaction_id" => $transaction_id));
						$this->save_audit_trail("Services", "Appointment and Resign of Director", "Appointment of Director is edited.");
					}
				}

			}
		}

		if(count($_POST['hidden_resign_identification_register_no']) > 0)
		{
			for($i = 0; $i < count($_POST['hidden_resign_identification_register_no']); $i++ )
			{
				$data['transaction_id']=  $transaction_id;
				$data['company_code'] = $_POST['company_code'];
				$data['officer_id'] = $_POST['resign_officer_id'][$i];
				$data['field_type'] = $_POST['resign_officer_field_type'][$i];
				$data['position'] = 1;
				$data['alternate_of']='';
				$data['date_of_appointment'] = $_POST['hidden_date_of_appointment'][$i];
				$data['date_of_cessation'] = $_POST['hidden_date_of_cessation'][$i];
				$data['appoint_resign_flag'] = "resign";
				$data['retiring'] = 0;

				if($_POST["hidden_resign_director_reason"][$i] == undefined)
				{
					$reason["reason"] = "";
					$reason["reason_selected"] = (($_POST['hidden_resign_director_reason_selected'][$i] != "null" && $_POST['hidden_resign_director_reason_selected'][$i] != undefined)?$_POST['hidden_resign_director_reason_selected'][$i]:NULL);
				}
				else
				{
					$reason["reason"] = strtoupper($_POST["hidden_resign_director_reason"][$i]);
					$reason["reason_selected"] = (($_POST['hidden_resign_director_reason_selected'][$i] != "null" && $_POST['hidden_resign_director_reason_selected'][$i] != undefined)?$_POST['hidden_resign_director_reason_selected'][$i]:NULL);
				}
				
				$q = $this->db->get_where("transaction_client_officers", array("id" => $_POST['resign_client_officer_id'][$i], "transaction_id" => $transaction_id));

				if (!$q->num_rows())
				{
					$this->db->insert("transaction_client_officers",$data);
					$insert_client_officers_id = $this->db->insert_id();

					$reason["transaction_client_officers_id"] = $insert_client_officers_id;

					$this->db->insert("transaction_resign_officer_reason",$reason);

					$this->save_audit_trail("Services", "Appointment and Resign of Director", "Resign of Director is added.");
				} 
				else 
				{
					$this->db->update("transaction_client_officers",$data,array("id" => $_POST['resign_client_officer_id'][$i], "transaction_id" => $transaction_id));

					$this->db->update("transaction_resign_officer_reason",$reason,array("transaction_client_officers_id" => $_POST['resign_client_officer_id'][$i]));
					
					$this->save_audit_trail("Services", "Appointment and Resign of Director", "Resign of Director is edited.");
				}
			}
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $transaction_id));

		$this->data['transaction_client_officers'] = $this->transaction_model->getTransactionResignClientOfficer($transaction_id);

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_client_officers" => $this->data['transaction_client_officers'], "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function add_appoint_new_secretarial()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$cancel_by_system["completed"] = 1;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = $this->encryption->encrypt(strtoupper($_POST['registration_no']));
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
			$this->db->update("transaction_master",$edit_transaction,array("id" => $_POST["transaction_master_id"]));
			$transaction_id = $_POST["transaction_master_id"];
			$transaction_code = $_POST['transaction_code'];
			
		}

		$resign_data['transaction_id']=$transaction_id;

		$resign_data['resignation_of_corporate_secretarial_agent']=strtoupper($_POST['resignation_of_corporate_secretarial_agent']);
		$resign_data['resignation_of_corporate_secretarial_agent_address']=strtoupper($_POST['resignation_of_corporate_secretarial_agent_address']);

		$resign_q = $this->db->get_where("transaction_resignation_of_company_secretary", array("transaction_id" => $transaction_id));

		if (!$resign_q->num_rows())
		{
			$this->db->insert("transaction_resignation_of_company_secretary",$resign_data);
		} 
		else 
		{
			$this->db->update("transaction_resignation_of_company_secretary", $resign_data, array("transaction_id" => $transaction_id));
		}

		if(count($_POST['identification_register_no']) > 0)
		{
			$this->db->delete("transaction_client_officers",array('company_code'=>$_POST['company_code'], 'transaction_id'=>$transaction_id));
			for($i = 0; $i < count($_POST['identification_register_no']); $i++ )
			{
				if($_POST['identification_register_no'][$i] != "" && $_POST['identification_register_no'][$i] != null)
				{
					$data['transaction_id']=$transaction_id;
					$data['company_code']=$_POST['company_code'];
					$data['officer_id']=$_POST['officer_id'][$i];
					$data['field_type']=$_POST['officer_field_type'][$i];
					$data['position']=4;
					$data['alternate_of']='';
					$data['date_of_appointment'] = $_POST['date_of_appointment'][$i];
					$data['date_of_cessation'] = "";
					$data['appoint_resign_flag'] = "appoint";
					$data['retiring'] = 0;
					
					$q = $this->db->get_where("transaction_client_officers", array("id" => $_POST['client_officer_id'][$i], "transaction_id" => $transaction_id));

					if (!$q->num_rows())
					{
						$this->db->insert("transaction_client_officers",$data);
						$insert_client_officers_id = $this->db->insert_id();
						$this->save_audit_trail("Services", "Appointment of Secretarial", "Appoint of Secretarial is added.");
					} 
					else 
					{
						$this->db->update("transaction_client_officers",$data,array("id" => $_POST['client_officer_id'][$i], "transaction_id" => $transaction_id));
						$this->save_audit_trail("Services", "Appointment of Secretarial", "Appoint of Secretarial is edited.");
					}
				}
			}
		}

		if(count($_POST['hidden_resign_identification_register_no']) > 0)
		{
			for($i = 0; $i < count($_POST['hidden_resign_identification_register_no']); $i++ )
			{
				$data['transaction_id']=  $transaction_id;
				$data['company_code'] = $_POST['company_code'];
				$data['officer_id'] = $_POST['resign_officer_id'][$i];
				$data['field_type'] = $_POST['resign_officer_field_type'][$i];
				$data['position'] = 4;
				$data['alternate_of']='';
				$data['date_of_appointment'] = $_POST['hidden_date_of_appointment'][$i];
				$data['date_of_cessation'] = $_POST['hidden_date_of_cessation'][$i];
				$data['appoint_resign_flag'] = "resign";
				$data['retiring'] = 0;

				if($_POST["hidden_resign_secretarial_reason"][$i] == undefined)
				{
					$reason["reason"] = "";
					$reason["reason_selected"] = (($_POST['hidden_resign_secretarial_reason_selection'][$i] != "null" && $_POST['hidden_resign_secretarial_reason_selection'][$i] != undefined)?$_POST['hidden_resign_secretarial_reason_selection'][$i]:NULL);
				}
				else
				{
					$reason["reason"] = strtoupper($_POST["hidden_resign_secretarial_reason"][$i]);
					$reason["reason_selected"] = (($_POST['hidden_resign_secretarial_reason_selection'][$i] != "null" && $_POST['hidden_resign_secretarial_reason_selection'][$i] != undefined)?$_POST['hidden_resign_secretarial_reason_selection'][$i]:NULL);
				}
				
				$q = $this->db->get_where("transaction_client_officers", array("id" => $_POST['resign_client_officer_id'][$i], "transaction_id" => $transaction_id));

				if (!$q->num_rows())
				{
					$this->db->insert("transaction_client_officers",$data);
					$insert_client_officers_id = $this->db->insert_id();

					$reason["transaction_client_officers_id"] = $insert_client_officers_id;
					$this->db->insert("transaction_resign_officer_reason",$reason);

					$this->save_audit_trail("Services", "Resignation of Secretarial", "Resign of Secretarial is added.");
				} 
				else 
				{
					$this->db->update("transaction_client_officers",$data,array("id" => $_POST['resign_client_officer_id'][$i], "transaction_id" => $transaction_id));

					$this->db->update("transaction_resign_officer_reason",$reason,array("transaction_client_officers_id" => $_POST['resign_client_officer_id'][$i]));
					
					$this->save_audit_trail("Services", "Resignation of Secretarial", "Resign of Secretarial is edited.");
				}
			}
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $transaction_id));

		$this->data['transaction_client_officers'] = $this->transaction_model->getTransactionResignClientOfficer($transaction_id);

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_client_officers" => $this->data['transaction_client_officers'], "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function add_appoint_new_director()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$cancel_by_system["completed"] = 1;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = $this->encryption->encrypt(strtoupper($_POST['registration_no']));
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
			$this->db->update("transaction_master",$edit_transaction,array("id" => $_POST['transaction_master_id']));
			$transaction_id = $_POST['transaction_master_id'];
			$transaction_code = $_POST['transaction_code'];
			
		}

		for($i = 0; $i < count($_POST['identification_register_no']); $i++ )
		{
			$data['transaction_id']=$transaction_id;
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
		$this->db->update("transaction_master",$edit_transaction,array("id" => $transaction_id));

		$this->data['transaction_client_officers'] = $this->transaction_model->getTransactionClientOfficer($transaction_id, $_POST['company_code']);

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_client_officers" => $this->data['transaction_client_officers'], "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function add_engagement_letter()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$cancel_by_system["completed"] = 1;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		if($_POST['registration_no'] != null)
		{
			$transaction['registration_no'] = $this->encryption->encrypt(strtoupper($_POST['registration_no']));
		}

		$str = utf8_decode($_POST['client_name']);
		$str = htmlentities($str, ENT_QUOTES);
		$transaction['client_name'] = $this->encryption->encrypt(trim(str_replace("(Potential Client)", "", $str)));
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
			$this->db->update("transaction_master",$edit_transaction,array("id" => $_POST["transaction_master_id"]));
			$transaction_id = $_POST["transaction_master_id"];
			$transaction_code = $_POST["transaction_code"];
		}

		if($_POST["trans_master_service_proposal_id"] != "")
		{
			$transaction_engagement_letter_info['transaction_id'] = $transaction_id;
			$transaction_engagement_letter_info['transaction_master_id'] = $_POST["trans_master_service_proposal_id"];

			$transaction_engagement_letter_info_query = $this->db->get_where("transaction_engagement_letter_info", array("transaction_id" => $transaction_id));

			if (!$transaction_engagement_letter_info_query->num_rows())
			{
				$this->db->insert("transaction_engagement_letter_info",$transaction_engagement_letter_info);
				$this->save_audit_trail("Services", "Engagement Letter", "Engagement Letter info is added.");
			}
			else
			{
				$this->db->update("transaction_engagement_letter_info",$transaction_engagement_letter_info,array("transaction_id" => $transaction_id));
				$this->save_audit_trail("Services", "Engagement Letter", "Engagement Letter info is edited.");
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
        		$transaction_engagement_letter_service_info['transaction_id'] = $transaction_id;
				$transaction_engagement_letter_service_info['engagement_letter_list_id'] = $_POST['hidden_selected_el_id'][$h];
				$transaction_engagement_letter_service_info['currency_id'] = $_POST['currency'][$h];
				$transaction_engagement_letter_service_info['fee'] = str_replace(',', '', $_POST['fee'][$h]);
				$transaction_engagement_letter_service_info['unit_pricing'] = $_POST['unit_pricing'][$h];
				$transaction_engagement_letter_service_info['servicing_firm'] = $_POST['servicing_firm'][$h];

				$this->db->insert('transaction_engagement_letter_service_info', $transaction_engagement_letter_service_info);
        	}
        }

        $edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" => $transaction_id));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function add_ml_quarterly_statements_info()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$cancel_by_system["completed"] = 1;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = $this->encryption->encrypt(strtoupper($_POST['registration_no']));
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

		$transaction_ml_quarterly_statements['transaction_id']=$this->session->userdata('transaction_id');
		$transaction_ml_quarterly_statements['postal_code']= $_POST['postal_code'];
		$transaction_ml_quarterly_statements['street_name']= $_POST['street_name'];
		$transaction_ml_quarterly_statements['building_name']= $_POST['building_name'];
		$transaction_ml_quarterly_statements['unit_no1']= $_POST['unit_no1'];
		$transaction_ml_quarterly_statements['unit_no2']= $_POST['unit_no2'];

		$q = $this->db->get_where("transaction_ml_quarterly_statements", array("transaction_id" => $transaction_id));

		if (!$q->num_rows())
		{
			$this->db->insert("transaction_ml_quarterly_statements",$transaction_ml_quarterly_statements);
		} 
		else 
		{
			$this->db->update("transaction_ml_quarterly_statements",$transaction_ml_quarterly_statements,array("transaction_id" => $transaction_id));
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function add_omp_grant()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "firm_id" => $this->session->userdata('firm_id'), "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$cancel_by_system["completed"] = 1;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "firm_id" => $this->session->userdata('firm_id'), "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		if($_POST['registration_no'] != null)
		{
			$transaction['registration_no'] = $this->encryption->encrypt(strtoupper($_POST['registration_no']));
		}
		$transaction['client_type_id'] = $_POST['client_type'];
		$transaction['client_name'] = $this->encryption->encrypt(trim($_POST['client_name']));
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
			$this->db->update("transaction_master",$edit_transaction,array("id" => $_POST["transaction_master_id"]));
			$transaction_id = $_POST["transaction_master_id"];
			$transaction_code = $_POST["transaction_code"];
		}

		$transaction_omp_grant_info['transaction_id']=$transaction_id;
		$transaction_omp_grant_info['date_of_quotation']=$_POST['date_of_quotation'];
		$transaction_omp_grant_info['postal_code']= strtoupper($_POST['postal_code']);
		$transaction_omp_grant_info['street_name']= strtoupper($_POST['street_name']);
		$transaction_omp_grant_info['building_name']= strtoupper($_POST['building_name']);
		$transaction_omp_grant_info['unit_no1']= strtoupper($_POST['unit_no1']);
		$transaction_omp_grant_info['unit_no2']= strtoupper($_POST['unit_no2']);
		$transaction_omp_grant_info['attention_name']=strtoupper($_POST['attention_name']);
		$transaction_omp_grant_info['attention_title']= strtoupper($_POST['attention_title']);
		$transaction_omp_grant_info['grant_date']= $_POST['grant_date'];
		$transaction_omp_grant_info['quotation_ref']= $_POST['quotation_ref'];
		$transaction_omp_grant_info['cash_deposit']= str_replace(',', '', $_POST['cash_deposit']);
		$transaction_omp_grant_info['success_fees']=$_POST['success_fees'];
		$transaction_omp_grant_info['less_the_cash_deposit']= str_replace(',', '', $_POST['less_the_cash_deposit']);

		$q = $this->db->get_where("transaction_omp_grant_info", array("transaction_id" => $transaction_id));

		if (!$q->num_rows())
		{
			$this->db->insert("transaction_omp_grant_info",$transaction_omp_grant_info);
			$this->save_audit_trail("Services", "OMP Grant", "Overseas Marketing Presence Grant is added.");
		} 
		else 
		{
			$this->db->update("transaction_omp_grant_info",$transaction_omp_grant_info,array("transaction_id" => $transaction_id));
			$this->save_audit_trail("Services", "OMP Grant", "Overseas Marketing Presence Grant is edited.");
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $transaction_id));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function add_service_proposal()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "firm_id" => $this->session->userdata('firm_id'), "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$cancel_by_system["completed"] = 1;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "firm_id" => $this->session->userdata('firm_id'), "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		if($_POST['registration_no'] != null)
		{
			$transaction['registration_no'] = $this->encryption->encrypt(strtoupper($_POST['registration_no']));
		}
		$transaction['client_type_id'] = $_POST['client_type'];
		$transaction['client_name'] = $this->encryption->encrypt(trim($_POST['client_name']));
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
			$this->db->update("transaction_master",$edit_transaction,array("id" => $_POST["transaction_master_id"]));
			$transaction_id = $_POST["transaction_master_id"];
			$transaction_code = $_POST["transaction_code"];
		}

		$transaction_service_proposal_info['transaction_id']=$transaction_id;
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
			$this->save_audit_trail("Services", "Service Proposal", "Service Proposal is added.");
		} 
		else 
		{
			$this->db->update("transaction_service_proposal_info",$transaction_service_proposal_info,array("transaction_id" => $transaction_id));
			$this->save_audit_trail("Services", "Service Proposal", "Service Proposal is edited.");
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
		$this->db->delete("transaction_service_proposal_sub_service_info",array('transaction_id'=>$this->session->userdata("transaction_id")));
		
		for($h = 0; $h < count($_POST['hidden_selected_service_id']); $h++)
        {
        	if($_POST['hidden_selected_service_id'][$h] != "")
        	{
        		$transaction_service_proposal_service_info['transaction_id'] = $transaction_id;
				$transaction_service_proposal_service_info['our_service_id'] = $_POST['hidden_selected_service_id'][$h];
				$transaction_service_proposal_service_info['service_proposal_description'] = $_POST['service_proposal_description'][$h];
				$transaction_service_proposal_service_info['currency_id'] = $_POST['currency'][$h];
				$transaction_service_proposal_service_info['fee'] = str_replace(',', '', $_POST['fee'][$h]);
				$transaction_service_proposal_service_info['unit_pricing'] = $_POST['unit_pricing'][$h];
				$transaction_service_proposal_service_info['servicing_firm'] = $_POST['servicing_firm'][$h];
				$transaction_service_proposal_service_info['sequence'] = $_POST['sequence'][$h];

				$this->db->insert('transaction_service_proposal_service_info', $transaction_service_proposal_service_info);
				$transaction_service_proposal_service_info_id = $this->db->insert_id();

				for($t = 0; $t < count($_POST['hidden_selected_service_id_for_sub']); $t++)
				{
					if($_POST['hidden_selected_service_id_for_sub'][$t] == $_POST['hidden_selected_service_id'][$h])
        			{
        				$transaction_service_proposal_sub_service_info['transaction_id'] = $transaction_id;
        				$transaction_service_proposal_sub_service_info['service_info_id'] = $transaction_service_proposal_service_info_id;
						$transaction_service_proposal_sub_service_info['our_service_name'] = $_POST['sub_service'][$t];
						$transaction_service_proposal_sub_service_info['sub_currency_id'] = $_POST['sub_currency'][$t];
						$transaction_service_proposal_sub_service_info['sub_fee'] = str_replace(',', '', $_POST['sub_fee'][$t]);
						$transaction_service_proposal_sub_service_info['sub_unit_pricing'] = $_POST['sub_unit_pricing'][$t];

						$this->db->insert('transaction_service_proposal_sub_service_info', $transaction_service_proposal_sub_service_info);
        			}
				}

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
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $transaction_id));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function add_new_fye()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$cancel_by_system["completed"] = 1;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = $this->encryption->encrypt(strtoupper($_POST['registration_no']));
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = $_POST['effective_date'];
		$transaction['lodgement_date'] = "";
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
			$edit_transaction['effective_date'] = $_POST['effective_date'];
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $_POST["transaction_master_id"]));
			$transaction_id = $_POST["transaction_master_id"];
			$transaction_code = $_POST["transaction_code"];
		}

		$data['transaction_id']=$transaction_id;
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
			$this->save_audit_trail("Services", "Change of Financial Year End", "New financial year end is added.");
		} 
		else 
		{
			$this->db->update("transaction_change_fye",$data,array("id" => $_POST['transaction_change_FYE_id']));
			$insert_transaction_change_FYE_id = $_POST['transaction_change_FYE_id'];
			$this->save_audit_trail("Services", "Change of Financial Year End", "New financial year end is edited.");
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" => $transaction_id));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_change_FYE_id" => $insert_transaction_change_FYE_id, "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function add_new_biz_activity()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$cancel_by_system["completed"] = 1;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = $this->encryption->encrypt(strtoupper($_POST['registration_no']));
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = $_POST['effective_date'];
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
			$edit_transaction['effective_date'] = $_POST['effective_date'];
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $_POST['transaction_master_id']));
			$transaction_id = $_POST['transaction_master_id'];
			$transaction_code = $_POST['transaction_code'];
		}

		$data['transaction_id']=$transaction_id;
		$data['company_name']=$_POST['company_name'];
		$data['old_activity1']= strtoupper($_POST['old_activity1']);
		$data['old_description1']= strtoupper($_POST['old_description1']);
		$data['old_activity2']= strtoupper($_POST['old_activity2']);
		$data['old_description2']= strtoupper($_POST['old_description2']);
		$data['activity1']= strtoupper($_POST['new_activity1']);
		$data['description1']= strtoupper($_POST['new_description1']);
		$data['remove_activity_2']= (isset($_POST['remove_activity_2'])) ? 1 : 0;
		$data['activity2']= strtoupper($_POST['new_activity2']);
		$data['description2']= isset($_POST['new_description2'])?strtoupper($_POST['new_description2']):"";
		
		$q = $this->db->get_where("transaction_change_biz_activity", array("id" => $_POST['transaction_change_biz_activity_id']));

		if (!$q->num_rows())
		{
			$this->db->insert("transaction_change_biz_activity",$data);
			$insert_transaction_change_biz_activity_id = $this->db->insert_id();

			$this->save_audit_trail("Services", "Change of Business Activity", "New business activity is added.");
		} 
		else 
		{
			$this->db->update("transaction_change_biz_activity",$data,array("id" => $_POST['transaction_change_biz_activity_id']));
			$insert_transaction_change_biz_activity_id = $_POST['transaction_change_biz_activity_id'];
			
			$this->save_audit_trail("Services", "Change of Business Activity", "New business activity is edited.");
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" => $transaction_id));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_change_biz_activity_id" => $insert_transaction_change_biz_activity_id, "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}
	//agm & ar
	public function save_company_info_and_status()
	{
		$save_transaction_master = $this->save_transaction_master($_POST['transaction_code'], $_POST['company_code'], $_POST['transaction_task_id'], $_POST['registration_no'], $_POST['transaction_master_id']);

		if($save_transaction_master)
		{
			$data['transaction_id'] = $save_transaction_master["transaction_id"];
			$data['company_type'] = $_POST['company_type'];
			$data['activity_status'] = $_POST['activity_status'];
			$data['solvency_status'] = $_POST['solvency_status'];
			$data['small_company'] = $_POST['small_company'];
			$data['xbrl'] = $_POST['xbrl'];

			$transaction_agm_ar_query = $this->db->get_where("transaction_agm_ar", array("id" => $_POST['transaction_agm_ar_id']));

			if (!$transaction_agm_ar_query->num_rows())
			{
				$this->db->insert("transaction_agm_ar",$data);
				$transaction_agm_ar_id = $this->db->insert_id();
				$this->save_audit_trail("Services", "AGM & AR", "Company Information and status is added.");
			}
			else
			{
				$this->db->update("transaction_agm_ar",$data,array("id" => $_POST['transaction_agm_ar_id']));
				$transaction_agm_ar_id = $_POST['transaction_agm_ar_id'];
				$this->save_audit_trail("Services", "AGM & AR", "Company Information and status is edited.");
			}

			echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_agm_ar_id" => $transaction_agm_ar_id, "transaction_master_id" => $save_transaction_master["transaction_id"], "transaction_code" => $save_transaction_master["transaction_code"]));
		}
	}

	public function save_notice()
	{
		$save_transaction_master = $this->save_transaction_master($_POST['transaction_code'], $_POST['company_code'], $_POST['transaction_task_id'], $_POST['registration_no'], $_POST['transaction_master_id']);

		if($save_transaction_master)
		{
			$data['transaction_id'] = $save_transaction_master["transaction_id"];
			$data['is_first_agm_id'] = $_POST['first_agm'];
			$data['year_end_date'] = $_POST['fye_date'];
			$data['shorter_notice'] = $_POST['shorter_notice'];
			$data['notice_date'] = $_POST['notice_date'];
			$data['require_hold_agm_list'] = $_POST['require_hold_agm_list'];
			if(isset($_POST['agm_date']))
	        {
	            $data['agm_date'] = $_POST['agm_date'];
	        }
	        else
	        {
	            $data['agm_date'] = "";
	        }
	        if(isset($_POST['date_fs_sent_to_member']))
	        {
	            $data['date_fs_sent_to_member'] = $_POST['date_fs_sent_to_member'];
	        }
	        else
	        {
	            $data['date_fs_sent_to_member'] = "";
	        }
			$data['agm_time'] = $_POST['agm_time'];
			$data['address_type'] = $_POST['address_type'];
			//registered_offis_address
			if(isset($_POST['registered_postal_code1']))
	        {
	            $data['registered_postal_code1'] = strtoupper($_POST['registered_postal_code1']);
	        }
	        else
	        {
	            $data['registered_postal_code1'] = "";
	        }

	        if(isset($_POST['registered_street_name1']))
	        {
	            $data['registered_street_name1'] = strtoupper($_POST['registered_street_name1']);
	        }
	        else
	        {
	            $data['registered_street_name1'] = "";
	        }

	        if(isset($_POST['registered_building_name1']))
	        {
	            $data['registered_building_name1'] = strtoupper($_POST['registered_building_name1']);
	        }
	        else
	        {
	            $data['registered_building_name1'] = "";
	        }

	        if(isset($_POST['registered_unit_no1']))
	        {
	            $data['registered_unit_no1'] = strtoupper($_POST['registered_unit_no1']);
	        }
	        else
	        {
	            $data['registered_unit_no1'] = "";
	        }

	        if(isset($_POST['registered_unit_no2']))
	        {
	            $data['registered_unit_no2'] = strtoupper($_POST['registered_unit_no2']);
	        }
	        else
	        {
	            $data['registered_unit_no2'] = "";
	        }
	        //Local_address
			if(isset($_POST['postal_code1']))
	        {
	            $data['postal_code1'] = strtoupper($_POST['postal_code1']);
	        }
	        else
	        {
	            $data['postal_code1'] = "";
	        }

	        if(isset($_POST['street_name1']))
	        {
	            $data['street_name1'] = strtoupper($_POST['street_name1']);
	        }
	        else
	        {
	            $data['street_name1'] = "";
	        }
			$data['building_name1'] = strtoupper($_POST['building_name1']);
	        $data['unit_no1'] = strtoupper($_POST['unit_no1']);
	        $data['unit_no2'] = strtoupper($_POST['unit_no2']);
	        //foreign_address
	        if(isset($_POST['foreign_address1']))
	        {
	            $data['foreign_address1'] = strtoupper($_POST['foreign_address1']);
	        }
	        else
	        {
	            $data['foreign_address1'] = "";
	        }
	        if(isset($_POST['foreign_address2']))
	        {
	            $data['foreign_address2'] = strtoupper($_POST['foreign_address2']);
	        }
	        else
	        {
	            $data['foreign_address2'] = "";
	        }
	        if(isset($_POST['foreign_address3']))
	        {
	            $data['foreign_address3'] = strtoupper($_POST['foreign_address3']);
	        }
	        else
	        {
	            $data['foreign_address3'] = "";
	        }

			$transaction_agm_ar_query = $this->db->get_where("transaction_agm_ar", array("id" => $_POST['transaction_agm_ar_id']));

			if (!$transaction_agm_ar_query->num_rows())
			{
				$this->db->insert("transaction_agm_ar",$data);
				$transaction_agm_ar_id = $this->db->insert_id();
				$this->save_audit_trail("Services", "AGM & AR", "Notice is added.");
			}
			else
			{
				$this->db->update("transaction_agm_ar",$data,array("id" => $_POST['transaction_agm_ar_id']));
				$transaction_agm_ar_id = $_POST['transaction_agm_ar_id'];
				$this->save_audit_trail("Services", "AGM & AR", "Notice is edited.");
			}

			echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_agm_ar_id" => $transaction_agm_ar_id, "transaction_master_id" => $save_transaction_master["transaction_id"], "transaction_code" => $save_transaction_master["transaction_code"]));
		}
	}

	public function save_agenda()
	{
		$save_transaction_master = $this->save_transaction_master($_POST['transaction_code'], $_POST['company_code'], $_POST['transaction_task_id'], $_POST['registration_no'], $_POST['transaction_master_id']);

		if($save_transaction_master)
		{
			$data['transaction_id'] = $save_transaction_master["transaction_id"];
			$data['chairman'] = $_POST['chairman'];
			$data['audited_fs'] = $_POST['audited_fs'];

			$transaction_agm_ar_query = $this->db->get_where("transaction_agm_ar", array("id" => $_POST['transaction_agm_ar_id']));

			if (!$transaction_agm_ar_query->num_rows())
			{
				$this->db->insert("transaction_agm_ar",$data);
				$transaction_agm_ar_id = $this->db->insert_id();
				$this->save_audit_trail("Services", "AGM & AR", "Agenda is added.");
			}
			else
			{
				$this->db->update("transaction_agm_ar",$data,array("id" => $_POST['transaction_agm_ar_id']));
				$transaction_agm_ar_id = $_POST['transaction_agm_ar_id'];
				$this->save_audit_trail("Services", "AGM & AR", "Agenda is edited.");
			}
			//transaction_agm_ar_reappoint_auditor
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
			//transaction_agm_ar_director_fee
			$this->db->delete("transaction_agm_ar_director_fee",array('transaction_agm_ar_id'=>$transaction_agm_ar_id));

			for($r = 0; $r < count($_POST['director_fee_name']); $r++)
			{
				$transaction_agm_ar_director_fee['transaction_agm_ar_id'] = $transaction_agm_ar_id;
				$transaction_agm_ar_director_fee['director_fee_name'] = $_POST['director_fee_name'][$r];
				$transaction_agm_ar_director_fee['director_fee_identification_no'] = $_POST['director_fee_identification_register_no'][$r];
				$transaction_agm_ar_director_fee['director_fee_officer_id'] = $_POST['director_fee_officer_id'][$r];
				$transaction_agm_ar_director_fee['director_fee_officer_field_type'] = $_POST['director_fee_officer_field_type'][$r];
				$transaction_agm_ar_director_fee['currency_id'] = str_replace(",", "", $_POST['currency'][$r]);
				$transaction_agm_ar_director_fee['salary'] = str_replace(",", "", $_POST['salary'][$r]);
				$transaction_agm_ar_director_fee['cpf'] = str_replace(",", "", $_POST['cpf'][$r]);
				$transaction_agm_ar_director_fee['director_fee'] = str_replace(",", "", $_POST['director_fee'][$r]);
				$transaction_agm_ar_director_fee['total_director_fee'] = str_replace(",", "", $_POST['total_director_fee'][$r]);

				$this->db->insert("transaction_agm_ar_director_fee",$transaction_agm_ar_director_fee);
			}

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

			echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_agm_ar_id" => $transaction_agm_ar_id, "transaction_master_id" => $save_transaction_master["transaction_id"], "transaction_code" => $save_transaction_master["transaction_code"]));
		}
	}

	public function save_ar_declaration()
	{
		$save_transaction_master = $this->save_transaction_master($_POST['transaction_code'], $_POST['company_code'], $_POST['transaction_task_id'], $_POST['registration_no'], $_POST['transaction_master_id']);

		if($save_transaction_master)
		{
			$data['transaction_id'] = $save_transaction_master["transaction_id"];
			$data['agm_share_transfer_id'] = $_POST['share_transfer'];
			$data['register_of_controller'] = $_POST['register_of_controller'];
			$data['register_of_nominee_director'] = $_POST['register_of_nominee_director'];
			
			$transaction_agm_ar_query = $this->db->get_where("transaction_agm_ar", array("id" => $_POST['transaction_agm_ar_id']));

			if (!$transaction_agm_ar_query->num_rows())
			{
				$this->db->insert("transaction_agm_ar",$data);
				$transaction_agm_ar_id = $this->db->insert_id();
				$this->save_audit_trail("Services", "AGM & AR", "Annual Return Declaration is added.");
			}
			else
			{
				$this->db->update("transaction_agm_ar",$data,array("id" => $_POST['transaction_agm_ar_id']));
				$transaction_agm_ar_id = $_POST['transaction_agm_ar_id'];
				$this->save_audit_trail("Services", "AGM & AR", "Annual Return Declaration is edited.");
			}

			echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_agm_ar_id" => $transaction_agm_ar_id, "transaction_master_id" => $save_transaction_master["transaction_id"], "transaction_code" => $save_transaction_master["transaction_code"]));
		}
	}

	public function save_transaction_master($transaction_code, $company_code, $transaction_task_id, $registration_no, $transaction_master_id)
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $transaction_code, "company_code" => $company_code, "transaction_task_id" => $transaction_task_id, "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$cancel_by_system["completed"] = 1;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $transaction_code, "company_code" => $company_code, "transaction_task_id" => $transaction_task_id, "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $company_code;
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $transaction_task_id;
		$transaction['registration_no'] = $this->encryption->encrypt(strtoupper($registration_no));
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = "";
		$transaction['last_edited_by'] = $this->session->userdata('user_id');

		$transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code" => $transaction_code));

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
			$this->db->update("transaction_master",$edit_transaction,array("id" => $transaction_master_id));
			$transaction_id = $transaction_master_id;
			$transaction_code = $_POST["transaction_code"];
		}

		return array('transaction_id' => $transaction_id, 'transaction_code' => $transaction_code);
	}

	public function save_agm_ar()
	{
		//echo json_encode($_POST);

		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$cancel_by_system["completed"] = 1;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = $this->encryption->encrypt(strtoupper($_POST['registration_no']));
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
		$data['agm_time'] = $_POST['agm_time'];
		$data['reso_date'] = $_POST['reso_date'];
		$data['reso_time'] = $_POST['resolution_time'];
		$data['cont_exemption_id'] = $_POST['controller_exempt'];
		$data['regis_controller_is_kept_id'] = $_POST['controller_kept'];
		$data['dir_exemption_id'] = $_POST['director_exempt'];
		$data['regis_nominee_dir_is_kept_id'] = $_POST['director_kept'];
		$data['activity_status'] = $_POST['activity_status'];
		$data['solvency_status'] = $_POST['solvency_status'];
		$data['epc_status_id'] = $_POST['epc_status'];
		$data['small_company'] = $_POST['small_company'];
		$data['audited_fs'] = $_POST['audited_fs'];
		$data['agm_share_transfer_id'] = $_POST['share_transfer'];
		$data['shorter_notice'] = $_POST['shorter_notice'];
		$data['chairman'] = $_POST['chairman'];

		$data['address_type'] = $_POST['address_type'];
		//registered_offis_address
		if(isset($_POST['registered_postal_code1']))
        {
            $data['registered_postal_code1'] = strtoupper($_POST['registered_postal_code1']);
        }
        else
        {
            $data['registered_postal_code1'] = "";
        }

        if(isset($_POST['registered_street_name1']))
        {
            $data['registered_street_name1'] = strtoupper($_POST['registered_street_name1']);
        }
        else
        {
            $data['registered_street_name1'] = "";
        }

        if(isset($_POST['registered_building_name1']))
        {
            $data['registered_building_name1'] = strtoupper($_POST['registered_building_name1']);
        }
        else
        {
            $data['registered_building_name1'] = "";
        }

        if(isset($_POST['registered_unit_no1']))
        {
            $data['registered_unit_no1'] = strtoupper($_POST['registered_unit_no1']);
        }
        else
        {
            $data['registered_unit_no1'] = "";
        }

        if(isset($_POST['registered_unit_no2']))
        {
            $data['registered_unit_no2'] = strtoupper($_POST['registered_unit_no2']);
        }
        else
        {
            $data['registered_unit_no2'] = "";
        }
        //Local_address
		if(isset($_POST['postal_code1']))
        {
            $data['postal_code1'] = strtoupper($_POST['postal_code1']);
        }
        else
        {
            $data['postal_code1'] = "";
        }

        if(isset($_POST['street_name1']))
        {
            $data['street_name1'] = strtoupper($_POST['street_name1']);
        }
        else
        {
            $data['street_name1'] = "";
        }
		$data['building_name1'] = strtoupper($_POST['building_name1']);
        $data['unit_no1'] = strtoupper($_POST['unit_no1']);
        $data['unit_no2'] = strtoupper($_POST['unit_no2']);
        //foreign_address
        if(isset($_POST['foreign_address1']))
        {
            $data['foreign_address1'] = strtoupper($_POST['foreign_address1']);
        }
        else
        {
            $data['foreign_address1'] = "";
        }
        if(isset($_POST['foreign_address2']))
        {
            $data['foreign_address2'] = strtoupper($_POST['foreign_address2']);
        }
        else
        {
            $data['foreign_address2'] = "";
        }
        if(isset($_POST['foreign_address3']))
        {
            $data['foreign_address3'] = strtoupper($_POST['foreign_address3']);
        }
        else
        {
            $data['foreign_address3'] = "";
        }

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
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$cancel_by_system["completed"] = 1;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = $this->encryption->encrypt(strtoupper($_POST['registration_no']));
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = $_POST['effective_date'];
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
			$edit_transaction['effective_date'] = $_POST['effective_date'];
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $_POST["transaction_master_id"]));
			$transaction_id = $_POST["transaction_master_id"];
			$transaction_code = $_POST["transaction_code"];
		}

		$transaction_meeting_date['transaction_master_id'] = $transaction_id;
		$transaction_meeting_date['director_meeting_date'] = $_POST['director_meeting_date'];
		$transaction_meeting_date['director_meeting_time'] = $_POST['director_meeting_time'];
		$transaction_meeting_date['member_meeting_date'] = $_POST['member_meeting_date'];
		$transaction_meeting_date['member_meeting_time'] = $_POST['member_meeting_time'];
		$transaction_meeting_date['address_type'] = $_POST['address_type'];
		//registered_offis_address
		if(isset($_POST['registered_postal_code1']))
        {
            $transaction_meeting_date['registered_postal_code1'] = strtoupper($_POST['registered_postal_code1']);
        }
        else
        {
            $transaction_meeting_date['registered_postal_code1'] = "";
        }

        if(isset($_POST['registered_street_name1']))
        {
            $transaction_meeting_date['registered_street_name1'] = strtoupper($_POST['registered_street_name1']);
        }
        else
        {
            $transaction_meeting_date['registered_street_name1'] = "";
        }

        if(isset($_POST['registered_building_name1']))
        {
            $transaction_meeting_date['registered_building_name1'] = strtoupper($_POST['registered_building_name1']);
        }
        else
        {
            $transaction_meeting_date['registered_building_name1'] = "";
        }

        if(isset($_POST['registered_unit_no1']))
        {
            $transaction_meeting_date['registered_unit_no1'] = strtoupper($_POST['registered_unit_no1']);
        }
        else
        {
            $transaction_meeting_date['registered_unit_no1'] = "";
        }

        if(isset($_POST['registered_unit_no2']))
        {
            $transaction_meeting_date['registered_unit_no2'] = strtoupper($_POST['registered_unit_no2']);
        }
        else
        {
            $transaction_meeting_date['registered_unit_no2'] = "";
        }
        //Local_address
		if(isset($_POST['postal_code1']))
        {
            $transaction_meeting_date['postal_code1'] = strtoupper($_POST['postal_code1']);
        }
        else
        {
            $transaction_meeting_date['postal_code1'] = "";
        }

        if(isset($_POST['street_name1']))
        {
            $transaction_meeting_date['street_name1'] = strtoupper($_POST['street_name1']);
        }
        else
        {
            $transaction_meeting_date['street_name1'] = "";
        }
		$transaction_meeting_date['building_name1'] = strtoupper($_POST['building_name1']);
        $transaction_meeting_date['unit_no1'] = strtoupper($_POST['unit_no1']);
        $transaction_meeting_date['unit_no2'] = strtoupper($_POST['unit_no2']);
        //foreign_address
        if(isset($_POST['foreign_address1']))
        {
            $transaction_meeting_date['foreign_address1'] = strtoupper($_POST['foreign_address1']);
        }
        else
        {
            $transaction_meeting_date['foreign_address1'] = "";
        }
        if(isset($_POST['foreign_address2']))
        {
            $transaction_meeting_date['foreign_address2'] = strtoupper($_POST['foreign_address2']);
        }
        else
        {
            $transaction_meeting_date['foreign_address2'] = "";
        }
        if(isset($_POST['foreign_address3']))
        {
            $transaction_meeting_date['foreign_address3'] = strtoupper($_POST['foreign_address3']);
        }
        else
        {
            $transaction_meeting_date['foreign_address3'] = "";
        }

		$transaction_meeting_date_query = $this->db->get_where("transaction_meeting_date", array("transaction_master_id" => $transaction_id));

		if (!$transaction_meeting_date_query->num_rows())
		{
			$this->db->insert("transaction_meeting_date",$transaction_meeting_date);

		} 
		else 
		{
			$this->db->update("transaction_meeting_date",$transaction_meeting_date,array("transaction_master_id" => $transaction_id));

		}

		$data['transaction_id']=$transaction_id;
		$data['company_name']=$_POST['company_name'];
		$data['new_company_name']= strtoupper($_POST['new_company_name']);
		
		$q = $this->db->get_where("transaction_change_company_name", array("id" => $_POST['transaction_change_company_name_id']));

		if (!$q->num_rows())
		{
			$this->db->insert("transaction_change_company_name",$data);
			$insert_transaction_change_company_name_id = $this->db->insert_id();
			$this->save_audit_trail("Services", "Change of Company Name", "New company name is added.");
		} 
		else 
		{
			$this->db->update("transaction_change_company_name",$data,array("id" => $_POST['transaction_change_company_name_id']));
			$insert_transaction_change_company_name_id = $_POST['transaction_change_company_name_id'];
			$this->save_audit_trail("Services", "Change of Company Name", "New company name is edited.");
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $transaction_id));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_change_company_name_id" => $insert_transaction_change_company_name_id, "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}
	public function save_strike_off_notice()
	{
		$save_transaction_master = $this->save_transaction_master($_POST['transaction_code'], $_POST['company_code'], $_POST['transaction_task_id'], $_POST['registration_no'], $_POST['transaction_master_id']);

		if($save_transaction_master)
		{
			$data['transaction_id'] = $save_transaction_master["transaction_id"];
			$data['shorter_notice'] = $_POST['shorter_notice'];
			$data['notice_date'] = $_POST['notice_date'];

			if(isset($_POST['agm_date']))
	        {
	            $data['agm_date'] = $_POST['agm_date'];
	        }
	        else
	        {
	            $data['agm_date'] = "";
	        }

			$data['agm_time'] = $_POST['agm_time'];
			$data['address_type'] = $_POST['address_type'];
			//registered_offis_address
			if(isset($_POST['registered_postal_code1']))
	        {
	            $data['registered_postal_code1'] = strtoupper($_POST['registered_postal_code1']);
	        }
	        else
	        {
	            $data['registered_postal_code1'] = "";
	        }

	        if(isset($_POST['registered_street_name1']))
	        {
	            $data['registered_street_name1'] = strtoupper($_POST['registered_street_name1']);
	        }
	        else
	        {
	            $data['registered_street_name1'] = "";
	        }

	        if(isset($_POST['registered_building_name1']))
	        {
	            $data['registered_building_name1'] = strtoupper($_POST['registered_building_name1']);
	        }
	        else
	        {
	            $data['registered_building_name1'] = "";
	        }

	        if(isset($_POST['registered_unit_no1']))
	        {
	            $data['registered_unit_no1'] = strtoupper($_POST['registered_unit_no1']);
	        }
	        else
	        {
	            $data['registered_unit_no1'] = "";
	        }

	        if(isset($_POST['registered_unit_no2']))
	        {
	            $data['registered_unit_no2'] = strtoupper($_POST['registered_unit_no2']);
	        }
	        else
	        {
	            $data['registered_unit_no2'] = "";
	        }
	        //Local_address
			if(isset($_POST['postal_code1']))
	        {
	            $data['postal_code1'] = strtoupper($_POST['postal_code1']);
	        }
	        else
	        {
	            $data['postal_code1'] = "";
	        }

	        if(isset($_POST['street_name1']))
	        {
	            $data['street_name1'] = strtoupper($_POST['street_name1']);
	        }
	        else
	        {
	            $data['street_name1'] = "";
	        }
			$data['building_name1'] = strtoupper($_POST['building_name1']);
	        $data['unit_no1'] = strtoupper($_POST['unit_no1']);
	        $data['unit_no2'] = strtoupper($_POST['unit_no2']);
	        //foreign_address
	        if(isset($_POST['foreign_address1']))
	        {
	            $data['foreign_address1'] = strtoupper($_POST['foreign_address1']);
	        }
	        else
	        {
	            $data['foreign_address1'] = "";
	        }
	        if(isset($_POST['foreign_address2']))
	        {
	            $data['foreign_address2'] = strtoupper($_POST['foreign_address2']);
	        }
	        else
	        {
	            $data['foreign_address2'] = "";
	        }
	        if(isset($_POST['foreign_address3']))
	        {
	            $data['foreign_address3'] = strtoupper($_POST['foreign_address3']);
	        }
	        else
	        {
	            $data['foreign_address3'] = "";
	        }

			$transaction_agm_ar_query = $this->db->get_where("transaction_strike_off", array("id" => $_POST['transaction_strike_off_id']));

			if (!$transaction_agm_ar_query->num_rows())
			{
				$this->db->insert("transaction_strike_off",$data);
				$transaction_strike_off_id = $this->db->insert_id();
				$this->save_audit_trail("Services", "Strike Off Application", "Strike off application is added.");
			}
			else
			{
				$this->db->update("transaction_strike_off",$data,array("id" => $_POST['transaction_strike_off_id']));
				$transaction_strike_off_id = $_POST['transaction_strike_off_id'];
				$this->save_audit_trail("Services", "Strike Off Application", "Strike off application is edited.");
			}

			echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_strike_off_id" => $transaction_strike_off_id, "transaction_master_id" => $save_transaction_master["transaction_id"], "transaction_code" => $save_transaction_master["transaction_code"]));
		}
	}

	public function add_strike_off()
	{
		$save_transaction_master = $this->save_transaction_master($_POST['transaction_code'], $_POST['company_code'], $_POST['transaction_task_id'], $_POST['registration_no'], $_POST['transaction_master_id']);

		if($save_transaction_master)
		{
			$strike_off['transaction_id'] = $save_transaction_master["transaction_id"];
	        $strike_off['reason_for_application_id'] = $_POST['reason_for_appication'];

	        if(isset($_POST['ceased_date']))
			{
				$strike_off['ceased_date'] = $_POST['ceased_date'];
			}
			else
			{	
				$strike_off['ceased_date'] = "";
			}

	        $transaction_strike_off_query = $this->db->get_where("transaction_strike_off", array("id" => $_POST['transaction_strike_off_id']));

			if (!$transaction_strike_off_query->num_rows())
			{
				$this->db->insert("transaction_strike_off",$strike_off);
				$transaction_strike_off_id = $this->db->insert_id();
				$this->save_audit_trail("Services", "Strike Off Application", "Strike off application is added.");
			}
			else
			{
				$this->db->update("transaction_strike_off",$strike_off,array("id" => $_POST['transaction_strike_off_id']));
				$transaction_strike_off_id = $_POST['transaction_strike_off_id'];
				$this->save_audit_trail("Services", "Strike Off Application", "Strike off application is edited.");
			}

			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

			echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_strike_off_id" => $transaction_strike_off_id, "transaction_master_id" => $save_transaction_master["transaction_id"], "transaction_code" => $transaction_code));
		}
	}

	public function add_issue_dividend()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$cancel_by_system["completed"] = 1;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = $this->encryption->encrypt(strtoupper($_POST['registration_no']));
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

		$transaction_meeting_date['transaction_master_id'] = $transaction_id;
		$transaction_meeting_date['director_meeting_date'] = $_POST['director_meeting_date'];
		$transaction_meeting_date['director_meeting_time'] = $_POST['director_meeting_time'];
		$transaction_meeting_date['member_meeting_date'] = $_POST['member_meeting_date'];
		$transaction_meeting_date['member_meeting_time'] = $_POST['member_meeting_time'];
		$transaction_meeting_date['address_type'] = $_POST['address_type'];
		//registered_offis_address
		if(isset($_POST['registered_postal_code1']))
        {
            $transaction_meeting_date['registered_postal_code1'] = strtoupper($_POST['registered_postal_code1']);
        }
        else
        {
            $transaction_meeting_date['registered_postal_code1'] = "";
        }

        if(isset($_POST['registered_street_name1']))
        {
            $transaction_meeting_date['registered_street_name1'] = strtoupper($_POST['registered_street_name1']);
        }
        else
        {
            $transaction_meeting_date['registered_street_name1'] = "";
        }

        if(isset($_POST['registered_building_name1']))
        {
            $transaction_meeting_date['registered_building_name1'] = strtoupper($_POST['registered_building_name1']);
        }
        else
        {
            $transaction_meeting_date['registered_building_name1'] = "";
        }

        if(isset($_POST['registered_unit_no1']))
        {
            $transaction_meeting_date['registered_unit_no1'] = strtoupper($_POST['registered_unit_no1']);
        }
        else
        {
            $transaction_meeting_date['registered_unit_no1'] = "";
        }

        if(isset($_POST['registered_unit_no2']))
        {
            $transaction_meeting_date['registered_unit_no2'] = strtoupper($_POST['registered_unit_no2']);
        }
        else
        {
            $transaction_meeting_date['registered_unit_no2'] = "";
        }
        //Local_address
		if(isset($_POST['postal_code1']))
        {
            $transaction_meeting_date['postal_code1'] = strtoupper($_POST['postal_code1']);
        }
        else
        {
            $transaction_meeting_date['postal_code1'] = "";
        }

        if(isset($_POST['street_name1']))
        {
            $transaction_meeting_date['street_name1'] = strtoupper($_POST['street_name1']);
        }
        else
        {
            $transaction_meeting_date['street_name1'] = "";
        }
		$transaction_meeting_date['building_name1'] = strtoupper($_POST['building_name1']);
        $transaction_meeting_date['unit_no1'] = strtoupper($_POST['unit_no1']);
        $transaction_meeting_date['unit_no2'] = strtoupper($_POST['unit_no2']);
        //foreign_address
        if(isset($_POST['foreign_address1']))
        {
            $transaction_meeting_date['foreign_address1'] = strtoupper($_POST['foreign_address1']);
        }
        else
        {
            $transaction_meeting_date['foreign_address1'] = "";
        }
        if(isset($_POST['foreign_address2']))
        {
            $transaction_meeting_date['foreign_address2'] = strtoupper($_POST['foreign_address2']);
        }
        else
        {
            $transaction_meeting_date['foreign_address2'] = "";
        }
        if(isset($_POST['foreign_address3']))
        {
            $transaction_meeting_date['foreign_address3'] = strtoupper($_POST['foreign_address3']);
        }
        else
        {
            $transaction_meeting_date['foreign_address3'] = "";
        }

		$transaction_meeting_date_query = $this->db->get_where("transaction_meeting_date", array("transaction_master_id" => $transaction_id));

		if (!$transaction_meeting_date_query->num_rows())
		{
			$this->db->insert("transaction_meeting_date",$transaction_meeting_date);

		} 
		else 
		{
			$this->db->update("transaction_meeting_date",$transaction_meeting_date,array("transaction_master_id" => $transaction_id));
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
			$this->save_audit_trail("Services", "Issue Dividend", "Issue dividend is added.");
		}
		else
		{
			$this->db->update("transaction_issue_dividend",$issue_dividend,array("transaction_id" => $transaction_id));
			$transaction_issue_dividend_id = $_POST["transaction_issue_dividend_id"];
			$this->save_audit_trail("Services", "Issue Dividend", "Issue dividend is edited.");
		}

        for($g = 0; $g < count($_POST['shareholder_name']); $g++)
        {
            if($_POST['shareholder_name'][$g] != "")
            {
            	$transaction_dividend_list_query = $this->db->get_where("transaction_dividend_list", array("shareholder_name" => $_POST['shareholder_name'][$g]));

            	if (!$transaction_issue_dividend_query->num_rows())
				{
	            	$current_year = date("Y");

			        $query_min_payment_voucher_no = $this->db->query("select MIN(CAST(SUBSTRING(payment_voucher_no, 1, 5) AS UNSIGNED)) as latest_payment_voucher_no, SUBSTRING(payment_voucher_no, -4) as latest_year from transaction_dividend_list where SUBSTRING(payment_voucher_no, -4) = '".$current_year."'");

			        if ($query_min_payment_voucher_no->num_rows() > 0) 
			        {
			            $query_min_payment_voucher_no = $query_min_payment_voucher_no->result_array();

			            if($query_min_payment_voucher_no[0]["latest_payment_voucher_no"] == 1)
			            {
			                $query_max_payment_voucher_no = $this->db->query("select CAST(SUBSTRING(payment_voucher_no, 1, 5) AS UNSIGNED) as latest_payment_voucher_no, SUBSTRING(payment_voucher_no, -4) as latest_year from transaction_dividend_list where SUBSTRING(payment_voucher_no, -4) = '".$current_year."' ORDER BY id DESC LIMIT 1");

			                if ($query_max_payment_voucher_no->num_rows() > 0) 
			                {
			                    $query_max_payment_voucher_no = $query_max_payment_voucher_no->result_array();

			                    $last_section_max_payment_voucher_no = $query_max_payment_voucher_no[0]["latest_payment_voucher_no"];

			                    $number = substr_replace($last_section_max_payment_voucher_no, "", -5).(str_pad((int)(substr($last_section_max_payment_voucher_no, -5)) + 1, 5, '0', STR_PAD_LEFT));

			                    $payment_voucher_number = $number . " / " . $current_year;
			                }
			            }
			            else
			            {
			                $payment_voucher_number = "00001 / ".$current_year;
			            }
			        }
			        else
			        {
			            $payment_voucher_number = "00001 / ".$current_year;
			        }

	            	$issue_dividend_list['transaction_issue_dividend_id'] = $transaction_issue_dividend_id;
	            	$issue_dividend_list['payment_voucher_no'] = $payment_voucher_number; //mt_rand(10000, 99999)." / ".date("Y");
	            	$issue_dividend_list['officer_id'] = $_POST['officer_id'][$g];
	            	$issue_dividend_list['field_type'] = $_POST['field_type'][$g];
	            	$issue_dividend_list['shareholder_name'] = $_POST['shareholder_name'][$g];
	            	$issue_dividend_list['number_of_share'] = $_POST['balance'][$g];
	            	$issue_dividend_list['devidend_paid'] = $_POST['devidend_paid'][$g];

	                $this->db->insert('transaction_dividend_list', $issue_dividend_list);
	            }
	            else
	            {
	            	$update_issue_dividend_list['number_of_share'] = $_POST['balance'][$g];
	            	$update_issue_dividend_list['devidend_paid'] = $_POST['devidend_paid'][$g];

	            	$this->db->update("transaction_dividend_list",$update_issue_dividend_list, array("shareholder_name" => $_POST['shareholder_name'][$g]));
	            }
            }
        }

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" =>  $this->session->userdata('transaction_id')));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code, 'transaction_issue_dividend_id' => $transaction_issue_dividend_id));
	}

	public function add_issue_director_fee()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$cancel_by_system["completed"] = 1;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = $this->encryption->encrypt(strtoupper($_POST['registration_no']));
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
			$this->db->update("transaction_master",$edit_transaction,array("id" => $_POST["transaction_master_id"]));
			$transaction_id = $_POST["transaction_master_id"];
			$transaction_code = $_POST["transaction_code"];
		}

		$transaction_meeting_date['transaction_master_id'] = $transaction_id;
		$transaction_meeting_date['director_meeting_date'] = $_POST['director_meeting_date'];
		$transaction_meeting_date['director_meeting_time'] = $_POST['director_meeting_time'];
		$transaction_meeting_date['member_meeting_date'] = $_POST['member_meeting_date'];
		$transaction_meeting_date['member_meeting_time'] = $_POST['member_meeting_time'];
		$transaction_meeting_date['address_type'] = $_POST['address_type'];
		//registered_offis_address
		if(isset($_POST['registered_postal_code1']))
        {
            $transaction_meeting_date['registered_postal_code1'] = strtoupper($_POST['registered_postal_code1']);
        }
        else
        {
            $transaction_meeting_date['registered_postal_code1'] = "";
        }

        if(isset($_POST['registered_street_name1']))
        {
            $transaction_meeting_date['registered_street_name1'] = strtoupper($_POST['registered_street_name1']);
        }
        else
        {
            $transaction_meeting_date['registered_street_name1'] = "";
        }

        if(isset($_POST['registered_building_name1']))
        {
            $transaction_meeting_date['registered_building_name1'] = strtoupper($_POST['registered_building_name1']);
        }
        else
        {
            $transaction_meeting_date['registered_building_name1'] = "";
        }

        if(isset($_POST['registered_unit_no1']))
        {
            $transaction_meeting_date['registered_unit_no1'] = strtoupper($_POST['registered_unit_no1']);
        }
        else
        {
            $transaction_meeting_date['registered_unit_no1'] = "";
        }

        if(isset($_POST['registered_unit_no2']))
        {
            $transaction_meeting_date['registered_unit_no2'] = strtoupper($_POST['registered_unit_no2']);
        }
        else
        {
            $transaction_meeting_date['registered_unit_no2'] = "";
        }
        //Local_address
		if(isset($_POST['postal_code1']))
        {
            $transaction_meeting_date['postal_code1'] = strtoupper($_POST['postal_code1']);
        }
        else
        {
            $transaction_meeting_date['postal_code1'] = "";
        }

        if(isset($_POST['street_name1']))
        {
            $transaction_meeting_date['street_name1'] = strtoupper($_POST['street_name1']);
        }
        else
        {
            $transaction_meeting_date['street_name1'] = "";
        }
		$transaction_meeting_date['building_name1'] = strtoupper($_POST['building_name1']);
        $transaction_meeting_date['unit_no1'] = strtoupper($_POST['unit_no1']);
        $transaction_meeting_date['unit_no2'] = strtoupper($_POST['unit_no2']);
        //foreign_address
        if(isset($_POST['foreign_address1']))
        {
            $transaction_meeting_date['foreign_address1'] = strtoupper($_POST['foreign_address1']);
        }
        else
        {
            $transaction_meeting_date['foreign_address1'] = "";
        }
        if(isset($_POST['foreign_address2']))
        {
            $transaction_meeting_date['foreign_address2'] = strtoupper($_POST['foreign_address2']);
        }
        else
        {
            $transaction_meeting_date['foreign_address2'] = "";
        }
        if(isset($_POST['foreign_address3']))
        {
            $transaction_meeting_date['foreign_address3'] = strtoupper($_POST['foreign_address3']);
        }
        else
        {
            $transaction_meeting_date['foreign_address3'] = "";
        }

		$transaction_meeting_date_query = $this->db->get_where("transaction_meeting_date", array("transaction_master_id" => $transaction_id));

		if (!$transaction_meeting_date_query->num_rows())
		{
			$this->db->insert("transaction_meeting_date",$transaction_meeting_date);

		} 
		else 
		{
			$this->db->update("transaction_meeting_date",$transaction_meeting_date,array("transaction_master_id" => $transaction_id));
		}

		$issue_director_fee['transaction_id'] = $transaction_id;
        $issue_director_fee['registration_no'] = strtoupper($_POST['registration_no']);
        $issue_director_fee['declare_of_fye'] = $_POST['declare_of_fye'];
        $issue_director_fee['notice_date'] = $_POST['notice_date'];

        $transaction_issue_director_fee_query = $this->db->get_where("transaction_issue_director_fee", array("transaction_id" => $transaction_id));

		if (!$transaction_issue_director_fee_query->num_rows())
		{
			$this->db->insert("transaction_issue_director_fee",$issue_director_fee);
			$transaction_issue_director_fee_id = $this->db->insert_id();
			$this->save_audit_trail("Services", "Issue Director Fee", "Issue director fee is added.");
		}
		else
		{
			$this->db->update("transaction_issue_director_fee",$issue_director_fee,array("transaction_id" => $transaction_id));
			$transaction_issue_director_fee_id = $_POST["transaction_issue_director_fee_id"];
			$this->save_audit_trail("Services", "Issue Director Fee", "Issue director fee is edited.");
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
		$this->db->update("transaction_master",$edit_transaction,array("id" => $transaction_id));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code, 'transaction_issue_director_fee_id' => $transaction_issue_director_fee_id));
	}

	public function add_incorp_subsidiary()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$cancel_by_system["completed"] = 1;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = $this->encryption->encrypt(strtoupper($_POST['registration_no']));
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
        $corp_rep['registration_no'] = $this->encryption->encrypt(strtoupper($_POST['registration_no']));
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
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));

		if ($previous_transaction_master_query->num_rows())
		{
			$cancel_by_system["status"] = 4;
			$cancel_by_system["completed"] = 1;
			$this->db->update("transaction_master",$cancel_by_system,array("transaction_code !=" => $_POST['transaction_code'], "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		}

		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = $_POST['company_code'];
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = $this->encryption->encrypt(strtoupper($_POST['registration_no']));
		$transaction['remarks'] = "";
		$transaction['status'] = 1;
		$transaction['effective_date'] = $_POST["effective_date"];
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
			$edit_transaction['effective_date'] = $_POST["effective_date"];
			$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
			$this->db->update("transaction_master",$edit_transaction,array("id" => $_POST["transaction_master_id"]));
			$transaction_id = $_POST["transaction_master_id"];
			$transaction_code = $_POST["transaction_code"];
		}

		$data['transaction_id']=$transaction_id;
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

			$this->save_audit_trail("Services", "Change of Registered Office Address", "New registration office address is added.");
		} 
		else 
		{
			$this->db->update("transaction_change_regis_ofis_address",$data,array("id" => $_POST['transaction_change_regis_ofis_address_id']));
			$insert_transaction_change_regis_ofis_address_id = $_POST['transaction_change_regis_ofis_address_id'];
			
			$this->save_audit_trail("Services", "Change of Registered Office Address", "New registration office address is edited.");
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" => $transaction_id));

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

	public function get_create_billing_interface()
	{
		$transaction_task_id = $_POST["transaction_task_id"];
		$company_code = $_POST["company_code"];
		$transaction_master_id = $_POST["transaction_master_id"];

		$billing_interface = $this->load->view('/views/transaction/transaction_create_billing.php', '', TRUE);

		$billing_id_query = $this->db->query('select * from transaction_master_with_billing where transaction_master_id = "'.$transaction_master_id.'"');

		if ($billing_id_query->num_rows() > 0)
    	{
    		$billing_id_query = $billing_id_query->result_array();

			$this->data['billings'] = $this->db_model->get_edit_unpaid_bill($transaction_master_id);
			$this->data['paid_billings'] = $this->db_model->get_edit_paid_bill($transaction_master_id);
	    }
	    else
	    {
	    	$this->data['billings'] = false;
	    	$this->data['paid_billings'] = false;
	    }

		echo json_encode(array("billing_interface" => $billing_interface, $this->data));
	}

	public function check_transaction_master_with_billing()
	{
		$transaction_master_id = $_POST["transaction_master_id"];

		$billing_id_query = $this->db->query('select * from transaction_master_with_billing where transaction_master_id = "'.$transaction_master_id.'"');

		if ($billing_id_query->num_rows() > 0) 
    	{
    		echo json_encode(array("status" => 1));
		}
	    else
	    {
	    	echo json_encode(array("status" => 2));
		}
	}

	public function check_previous_transaction()
	{
		$previous_transaction_master_query = $this->db->get_where("transaction_master", array("firm_id" => $this->session->userdata('firm_id'), "company_code" => $_POST['company_code'], "transaction_task_id" => $_POST['transaction_task_id'], "status" => "1"));
		//"transaction_code !=" => $_POST['transaction_code'], 
		if ($previous_transaction_master_query->num_rows())
		{
			$previous_transaction_master_query = $previous_transaction_master_query->result_array();

			echo json_encode(array("status" => 1, "transaction_master_id" => $previous_transaction_master_query[0]["id"]));
		}
		else
	    {
	    	echo json_encode(array("status" => 2));
		}
	}

	public function get_purchase_common_seal_page()
	{
		$transaction_id = $_POST["id"];
		$transaction_task_id = $_POST["transaction_task_id"];

    	if($transaction_id != null)
		{
			$this->data['transaction_purchase_common_seal_data'] = $this->transaction_model->getTransactionPurchaseCommonSeal($transaction_id);
		}

		$this->data['client_list'] = $this->transaction_model->getClientList();
		$this->data['transaction_common_seal_vendor_list'] = $this->transaction_model->getTransactionCommonSealVendorList();
		$interface = $this->load->view('/views/transaction/purchase_common_seal_page.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));	
	}

	public function add_purchase_common_seal()
	{
		$transaction['firm_id'] = $this->session->userdata('firm_id');
		$transaction['company_code'] = "";
		$transaction['transaction_code'] = $this->getTransactionCode();
		$transaction['transaction_task_id'] = $_POST['transaction_task_id'];
		$transaction['registration_no'] = "";
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
			$this->db->update("transaction_master",$edit_transaction,array("id" => $_POST['transaction_master_id']));
			$transaction_id = $_POST['transaction_master_id'];
			$transaction_code = $_POST['transaction_code'];
		}

		$purchase_common_seal_info['transaction_id']=$transaction_id;
		$purchase_common_seal_info['date']=$_POST['purchase_date'];
		$purchase_common_seal_info['vendor']= $_POST['common_seal_vendor'];

		$transaction_purchase_common_seal_info_q = $this->db->get_where("transaction_purchase_common_seal_info", array("transaction_id" => $transaction_id));

		if (!$transaction_purchase_common_seal_info_q->num_rows())
		{
			$this->db->insert("transaction_purchase_common_seal_info",$purchase_common_seal_info);
			$this->save_audit_trail("Services", "Purchase of Common Seal & Self inking stamp", "Purchase of common seal info is added.");
		} 
		else 
		{
			$this->db->update("transaction_purchase_common_seal_info",$purchase_common_seal_info,array("transaction_id" => $transaction_id));
			$this->save_audit_trail("Services", "Purchase of Common Seal & Self inking stamp", "Purchase of common seal info is edited.");
		}

		$this->db->where('transaction_id', $transaction_id);
		$this->db->delete('transaction_purchase_common_seal_customer_info'); 

		if(count($_POST['company_name']) > 0)
		{
			for($r = 0; $r < count($_POST['company_name']); $r++)
			{
				$purchase_common_seal_customer_info['transaction_id']= $transaction_id;
				$purchase_common_seal_customer_info['company_code']= $_POST['company_name'][$r];
				$purchase_common_seal_customer_info['order_for']= $_POST['product'][$r];

				$this->db->insert("transaction_purchase_common_seal_customer_info",$purchase_common_seal_customer_info);
			}
		}

		$edit_transaction['last_edited_by'] = $this->session->userdata('user_id');
		$this->db->update("transaction_master",$edit_transaction,array("id" => $transaction_id));

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "transaction_master_id" => $transaction_id, "transaction_code" => $transaction_code));
	}

	public function get_purchase_common_seal_info()
	{
		$transaction_master_id = $_POST["transaction_master_id"];

		$this->data['transaction_purchase_common_seal_data'] = $this->transaction_model->getTransactionPurchaseCommonSeal($transaction_master_id);

		$interface = $this->load->view('/views/transaction/confirmation_purchase_common_seal.php', '', TRUE);

		echo json_encode(array("interface" => $interface, $this->data));
	}

	public function save_audit_trail($modules, $events, $actions)
    {
        $secretary_audit_trail["user_id"] = $this->session->userdata("user_id");
        $secretary_audit_trail["modules"] = $modules;
        $secretary_audit_trail["events"] = $events;
        $secretary_audit_trail["actions"] = $actions;

        $this->db->insert("secretary_audit_trail",$secretary_audit_trail);
    }
}

			
