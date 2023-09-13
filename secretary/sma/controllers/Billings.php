<?php defined('BASEPATH') OR exit('No direct script access allowed');

use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;
use QuickBooksOnline\API\Facades\CreditMemo;

class Billings extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        $this->load->library(array('session'));
        $this->load->model(array('db_model', 'master_model', 'quickbook_auth_model'));
        //$this->load->model(array('db_model', 'master_model'));

        $this->quickbook_clientID =  "ABn7HP2hPrTyXlM4nyqn3RNXY1PIWODdV1XggsTkVZYcXCCgfb";
        $this->quickbook_clientSecret = "ftM3Zl2nJZ29ihf1lZ1K55IBN6VgTPqE9vDQAyjX";
    }

    public function index($company_code = null)
    {
        $bc = array(array('link' => '#', 'page' => lang('Billings')));
        $meta = array('page_title' => lang('Billings'), 'bc' => $bc, 'page_name' => 'Billings');

        if($company_code != null)
        {
             $this->data['open_receipt'] = true;
             $this->data['company_code'] = $company_code;
        }
        else
        {
            $this->data['open_receipt'] = false;
        }

        $this->data['check_qb_token'] = $this->check_qb_token();

        if (isset($_POST['search'])) {
            if (isset($_POST['search']) && isset($_POST['type']))
            {
                $this->data['billings'] = $this->db_model->get_all_unpaid_billings($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                $this->data['paid_billings'] = $this->db_model->get_all_paid_billings($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                $this->data['receipt'] = $this->db_model->get_all_receipt($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                $this->data['credit_note'] = $this->db_model->get_all_latest_credit_note($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                $this->data['unassign_amount'] = $this->db_model->get_all_unassign_amount($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                $this->data['recurring_billing'] = $this->db_model->get_all_recurring_billing($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                $this->data['old_credit_note'] = $this->db_model->get_all_credit_note();
            } 
        }
        else
        {
            $this->data['billings'] = $this->db_model->get_all_unpaid_billings();
            $this->data['paid_billings'] = $this->db_model->get_all_paid_billings();
            $this->data['receipt'] = $this->db_model->get_all_receipt();
            $this->data['credit_note'] = $this->db_model->get_all_latest_credit_note();
            $this->data['unassign_amount'] = $this->db_model->get_all_unassign_amount();
            $this->data['recurring_billing'] = $this->db_model->get_all_recurring_billing();
            $this->data['old_credit_note'] = $this->db_model->get_all_credit_note();
        }

        $this->data["qb_company_id"] = $this->session->userdata('qb_company_id');
        $this->data['currency'] = $this->db_model->get_currency();
        $this->page_construct('billings.php', $meta, $this->data);

    }

    public function billing_email($company_code = null)
    {
        $bc = array(array('link' => '#', 'page' => lang('Billings')));
        $meta = array('page_title' => lang('Billings'), 'bc' => $bc, 'page_name' => 'Billings');

        if($company_code != null)
        {
             $this->data['open_receipt'] = true;
             $this->data['company_code'] = $company_code;
        }
        else
        {
            $this->data['open_receipt'] = false;
        }

        $this->data['check_qb_token'] = $this->check_qb_token();

        if (isset($_POST['search'])) {
            if (isset($_POST['search']) && isset($_POST['type']))
            {
                $this->data['billings'] = $this->db_model->get_all_billings($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                // $this->data['paid_billings'] = $this->db_model->get_all_paid_billings($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                // $this->data['receipt'] = $this->db_model->get_all_receipt($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                // $this->data['credit_note'] = $this->db_model->get_all_latest_credit_note($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                // $this->data['unassign_amount'] = $this->db_model->get_all_unassign_amount($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                // $this->data['recurring_billing'] = $this->db_model->get_all_recurring_billing($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                // $this->data['old_credit_note'] = $this->db_model->get_all_credit_note();
            } 
        }
        else
        {
            $this->data['billings'] = $this->db_model->get_all_billings();
            // $this->data['paid_billings'] = $this->db_model->get_all_paid_billings();
            // $this->data['receipt'] = $this->db_model->get_all_receipt();
            // $this->data['credit_note'] = $this->db_model->get_all_latest_credit_note();
            // $this->data['unassign_amount'] = $this->db_model->get_all_unassign_amount();
            // $this->data['recurring_billing'] = $this->db_model->get_all_recurring_billing();
            // $this->data['old_credit_note'] = $this->db_model->get_all_credit_note();
        }

        $this->data["qb_company_id"] = $this->session->userdata('qb_company_id');
        $this->data['currency'] = $this->db_model->get_currency();
        $this->page_construct('billing_email.php', $meta, $this->data);

    }

    public function resendEmail() {

        $billing_email_list = [];
        $invoice = $this->db_model->get_billing($_POST["id"]);

        $query = $this->db->query("select firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax,
       								currency.currency as currency_name from firm 
									LEFT JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
									LEFT JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
									LEFT JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
									LEFT JOIN currency ON currency.id = firm.firm_currency
									where firm.id = '".$invoice[0]->firm_id."'");
		$query = $query->result_array();

        $select_contact_persons = $this->db->query("select client_contact_info.*, client_contact_info_email.email from client_contact_info left join client_contact_info_email on client_contact_info_email.client_contact_info_id = client_contact_info.id and primary_email = 1 where client_contact_info.company_code = '".$invoice[0]->company_code."'");

        if ($select_contact_persons->num_rows() > 0) 
		{
			$select_contact_person = $select_contact_persons->result_array();

			for($t = 0; $t < count($select_contact_person); $t++)
			{
				if($firm_id == '26')
				{
					$notify_email = "karnlee@aaa-global.com";
					$clarifi_email = "karnlee@aaa-global.com";
					$call_us = "(65) 6222 0028";
					$cc_email = 'karnlee@aaa-global.com';
				}
				else
				{
					$notify_email = "admin@aaa-global.com";
					$clarifi_email = "looi@aaa-global.com or admin@aaa-global.com";
					$call_us = "(65) 6246 8801";
					$cc_email = 'corpsec@aaa-global.com';
				}
                $parse_data = array(
                	'$notify_email' => $notify_email,
                	'$clarifi_email' => $clarifi_email,
                	'$call_us' => $call_us,
                	'firm_name' => $query[0]["name"],
                	'firm_email' => $query[0]["email"],
                    'user_name' => $select_contact_person[$t]["name"],
                    'email' => $select_contact_person[$t]["email"],
                    'total_amount' => number_format($invoice[0]->amount,2),
                    'issue_date' => $invoice[0]->invoice_date,
                    'currency_name' => $invoice[0]->currency_name,
                    'company_name' => $this->encryption->decrypt($invoice[0]->company_name)
                );
                $msg = file_get_contents('./themes/default/views/email_templates/recurring_invoice.html');
                $message = $this->parser->parse_string($msg, $parse_data);


                $subject =  'INVOICE FOR '.$this->encryption->decrypt($invoice[0]->company_name);

                $undersigned = base_url().'img/acumen_bizcorp_header.jpg';
                array_push($billing_email_list, $select_contact_person[$t]["email"]);
                // $check_email_send_to_contact_person = $this->sma->send_email($select_contact_person[$t]["email"], $subject, $message.'<p>Best regards,<br />Management on behalf of Acumen Alpha Advisory Group<br />ACUMENBIZCORP PTE. LTD.<br />Address: 18 Howard Road, #08-06 Novelty BizCentre, Singapore 369585<br />Tel: (+65) 62468801 / (+ 65) 62468802</p>', 'admin@aaa-global.com', 'ACT Secretary', $invoice_pdf_link, 'corpsec@aaa-global.com,justin@aaa-global.com,then.k.w@hotmail.com');
            }
        }
        
        //$select_all_directors->num_rows() > 0 || 
        if ($select_contact_persons->num_rows() > 0) 
		{
	        //check multiple email by special character
	        $unique_email = array_unique($billing_email_list);
	        $email_to = explode(';', $unique_email[0]); // your email address 
	        if(count($email_to) > 0)
	        {
	        	$email_arr = array();
	        	for($t = 0; $t < count($email_to); $t++)
	        	{
	        		$arr_email = array("email"=> trim($email_to[$t]));
	        		array_push($email_arr, $arr_email);
	        	}

	        	$email_detail['email'] = json_encode($email_arr);
	        }
	        else
	        {
	        	$email_detail['email'] = json_encode(array(array("email"=> trim($unique_email[0]))));
	        }
			//check multiple email by special character

            $email_detail['subject'] = $subject;
            if($firm_id == '26')
			{
				$email_detail['message'] = $message.'<p>Best regards,<br />Karn Lee<br />ACUMEN ALPHA ADVISORY PTE. LTD.<br />Address: 160 Robinson Road, #26-10 Singapore Business Federation Center (SBF Center), Singapore 068914<br />Tel: (+65) 6222 0028</p>';
			}
			else
			{
            	$email_detail['message'] = $message.'<p>Best regards,<br />Management on behalf of Acumen Alpha Advisory Group<br />'.$query[0]['name'].'<br />Address: 18 Howard Road, #08-06 Novelty BizCentre, Singapore 369585<br />Tel: (+65) 6246 8801 / (+ 65) 6246 8802</p>';
            }

            $invoice_pdf_link = array();
            $invoice_pdf_link['content'] = base64_encode(file_get_contents($_SERVER["DOCUMENT_ROOT"] .'/secretary/pdf/invoice/'.$invoice[0]->invoice_no.'.pdf'));
            $invoice_pdf_link['name'] = $$invoice[0]->invoice_no.'.pdf';

            //add on
            $attach = array();
            array_push($attach, $invoice_pdf_link);
            //-----------------------

            $email_detail['from_email'] = json_encode(array("name" => $query[0]['name'], "email" => "admin@aaa-global.com"));//'admin@bizfiles.com.sg';
            $email_detail['from_name'] = $query[0]['name'];
            $email_detail['attachment'] = json_encode($attach);
            $email_detail['cc'] = json_encode(array(array("email" => $cc_email)));
            //looi@aaa-global.com, corpsec@aaa-global.com
            $email_detail['bcc'] = null;
            $email_detail['sended'] = 0;
            $email_detail['type'] = 'billing';
			$this->db->insert("email_queue",$email_detail);
			//corpsec@aaa-global.com,justin@aaa-global.com
        }

        echo json_encode(true);
        exit;
    }

    public function create_billing ()
    {
        $bc = array(array('link' => '#', 'page' => lang('Create Billing')));
        $meta = array('page_title' => lang('Create Billing'), 'bc' => $bc, 'page_name' => 'Create Billing');
        $this->session->unset_userdata('billing_company_code');
        $this->session->unset_userdata('billing_currency');

        $this->data['firm_info'] = $this->master_model->get_firm_info();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Billing', base_url('billings'));
        $this->mybreadcrumb->add('Create Billing', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        $this->page_construct('client/create_billing.php', $meta, $this->data);
    }

    public function create_recurring()
    {
        $bc = array(array('link' => '#', 'page' => lang('Create Recurring')));
        $meta = array('page_title' => lang('Create Recurring'), 'bc' => $bc, 'page_name' => 'Create Recurring');
        $this->session->unset_userdata('billing_company_code');
        $this->session->unset_userdata('billing_currency');

        $this->data['firm_info'] = $this->master_model->get_firm_info();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Recurring', base_url('billings'));
        $this->mybreadcrumb->add('Create Recurring', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        $this->page_construct('client/create_recurring.php', $meta, $this->data);
    }

    public function get_billing_info()
    {
        $company_code = $_POST["company_code"];

        $current_year = date("Y");
        if($this->session->userdata("firm_id") == 18 || $this->session->userdata("firm_id") == 26)
        {
            $where = '(billing_credit_note_record.firm_id = 18 or billing_credit_note_record.firm_id = 26)';
        }
        else
        {
            $where = "billing_credit_note_record.firm_id = '".$this->session->userdata('firm_id')."'";
        }

        $query_credit_note_no = $this->db->query("SELECT credit_note_no FROM credit_note where credit_note.id = (SELECT max(credit_note_id) FROM billing_credit_note_record where YEAR(credit_note.created_at) = ".$current_year." and ".$where.")");

        if ($query_credit_note_no->num_rows() > 0) 
        {
            $query_credit_note_no = $query_credit_note_no->result_array();

            $last_section_credit_note_no = (string)$query_credit_note_no[0]["credit_note_no"];

            $credit_note_no = substr_replace($last_section_credit_note_no, "", -4).(str_pad((int)(substr($last_section_credit_note_no, -4)) + 1, 4, '0', STR_PAD_LEFT));
        }
        else
        {
            $credit_note_no = "CN-"."AB-".date("Y").str_pad(1,5,"0",STR_PAD_LEFT);
        }

        $value_for_receipt = "RV-".date("Y")."-";

        if($this->session->userdata("firm_id") == 18 || $this->session->userdata("firm_id") == 26)
        {
            $where = '(billing_receipt_record.firm_id = 18 or billing_receipt_record.firm_id = 26)';
        }
        else
        {
            $where = "billing_receipt_record.firm_id = '".$this->session->userdata('firm_id')."'";
        }

        $query_payment_receipt_no = $this->db->query("select receipt.id, receipt_no, MAX(CAST(SUBSTRING(receipt_no, -4) AS UNSIGNED)) as latest_receipt_no from receipt left join billing_receipt_record on billing_receipt_record.receipt_id = receipt.id where YEAR(receipt.created_at) = ".$current_year." and ".$where." and receipt.receipt_no LIKE '".$value_for_receipt."%' GROUP BY receipt_no ORDER BY latest_receipt_no DESC LIMIT 1");

        if ($query_payment_receipt_no->num_rows() > 0) 
        {
            $query_payment_receipt_no = $query_payment_receipt_no->result_array();

            $last_section_payment_receipt_no = (string)$query_payment_receipt_no[0]["receipt_no"];

            if(substr_replace($last_section_payment_receipt_no, "", -4) != "RV-".date("Y")."-")
            {
                $number = "RV-".date("Y")."-".str_pad(1,4,"0",STR_PAD_LEFT);
            }
            else
            {
                $number = substr_replace($last_section_payment_receipt_no, "", -4).(str_pad((int)(substr($last_section_payment_receipt_no, -4)) + 1, 4, '0', STR_PAD_LEFT));
            }
        }
        else
        {
            $number = "RV-".date("Y")."-".str_pad(1,4,"0",STR_PAD_LEFT);
        }

        $currency_result = $this->db->query("select * from currency");
        $currency_result = $currency_result->result_array();
        $currency_res = array();
        foreach($currency_result as $row) {
            $currency_res[$row['id']] = $row['currency'];
        }

        $where = "billing.firm_id = '". $this->session->userdata("firm_id")."'";
        $q = $this->db->query("select billing.*, client.company_name as client_company_name, client.incorporation_date, transaction_client.company_name as transaction_client_company_name, transaction_client.incorporation_date as transaction_client_incorporation_date, currency.currency as currency_name from billing left join client on client.company_code = billing.company_code left join transaction_client on transaction_client.company_code = billing.company_code left join currency on billing.currency_id = currency.id where ".$where." AND outstanding > 0 AND billing.company_code = '".$_POST["company_code"]."' AND billing.status = '0' ORDER BY STR_TO_DATE(billing.invoice_date,'%d/%m/%Y')"); // AND client.deleted = 0

        if ($q->num_rows() > 0) 
        {
            $billing_currency = array();
            foreach (($q->result()) as $row) 
            {
                $billing_currency[$row->currency_id] = $row->currency_name;
            }

            if($q->result()[0]->transaction_client_company_name != null)
            {
                $q->result()[0]->transaction_client_company_name = $this->encryption->decrypt($q->result()[0]->transaction_client_company_name);
            }
            echo json_encode(array("status" => 1, 'result' => $q->result(), 'credit_note_no' => $credit_note_no, "receipt_no" => $number, 'billing_currency' => array_unique($billing_currency), 'currency_result' => $currency_res));
        } else echo json_encode(array("status" => 0, 'credit_note_no' => $credit_note_no, "receipt_no" => $number));
    }

    public function get_receipt_info()
    {
        $receipt_id = $_POST["receipt_id"];

        $currency_result = $this->db->query("select * from currency");
        $currency_result = $currency_result->result_array();
        $currency_res = array();
        foreach($currency_result as $row) {
            $currency_res[$row['id']] = $row['currency'];
        }

        $q = $this->db->query("select billing_receipt_record.receipt_id, billing_receipt_record.billing_id, billing_receipt_record.received, billing_receipt_record.equival_amount, billing_receipt_record.previous_outstanding, billing_receipt_record.is_from_cn, receipt.id, receipt.receipt_no, receipt_date, receipt.reference_no, receipt.payment_mode as payment_mode_id, receipt.bank_account_id, receipt.total_amount_received, receipt.currency_total_amount_received, receipt.out_of_balance, billing.*, client.company_name, client.incorporation_date, payment_mode.payment_mode, currency.currency as currency_name from billing left join billing_receipt_record on billing_receipt_record.billing_id = billing.id left join receipt on receipt.id = billing_receipt_record.receipt_id left join client on client.company_code = billing.company_code left join payment_mode on payment_mode.id = receipt.payment_mode left join currency on billing.currency_id = currency.id where billing_receipt_record.receipt_id = '".$receipt_id."' AND billing.outstanding != billing.amount");
        // AND billing.firm_id = '". $this->session->userdata("firm_id")."'  AND  client.deleted = 0
        if ($q->num_rows() > 0) 
        {
            $q->result()[0]->company_name = $this->encryption->decrypt($q->result()[0]->company_name);
            if($q->result()[0]->is_from_cn == 1)
            {
                $q_billing_credit_note_gst_with_receipt = $this->db->query("SELECT * FROM billing_credit_note_gst_with_receipt WHERE receipt_id = '".$q->result()[0]->receipt_id."' GROUP BY receipt_id");
                $q_billing_credit_note_gst_with_receipt = $q_billing_credit_note_gst_with_receipt->result();
            }
            else
            {
                $q_billing_credit_note_gst_with_receipt = "";
            }
            echo json_encode(array("status" => 1, 'result' => $q->result(), 'currency_result' => $currency_res, 'billing_credit_note_gst_with_receipt' => $q_billing_credit_note_gst_with_receipt));
        } else echo json_encode(array("status" => 0));
    }

    public function get_out_of_balance_receipt_info()
    {
        $receipt_id = $_POST["receipt_id"];

        $currency_result = $this->db->query("select * from currency");
        $currency_result = $currency_result->result_array();
        $currency_res = array();
        foreach($currency_result as $row) {
            $currency_res[$row['id']] = $row['currency'];
        }

        $q = $this->db->query("select billing_receipt_record.receipt_id, billing_receipt_record.billing_id, billing_receipt_record.received, billing_receipt_record.equival_amount, billing_receipt_record.previous_outstanding, receipt.id, receipt.receipt_no, receipt_date, receipt.reference_no, receipt.payment_mode as payment_mode_id, receipt.bank_account_id, receipt.total_amount_received, receipt.currency_total_amount_received, receipt.out_of_balance, billing.*, client.incorporation_date, payment_mode.payment_mode, currency.currency as currency_name from billing right join billing_receipt_record on billing_receipt_record.billing_id = billing.id left join receipt on receipt.id = billing_receipt_record.receipt_id left join client on client.company_code = billing.company_code AND  client.deleted = 0 left join payment_mode on payment_mode.id = receipt.payment_mode left join currency on billing.currency_id = currency.id where billing_receipt_record.receipt_id = '".$receipt_id."' AND billing.outstanding != billing.amount");
        // AND billing.firm_id = '". $this->session->userdata("firm_id")."'
        if ($q->num_rows() > 0) 
        {
            //$q->result()[0]->company_name = $this->encryption->decrypt($q->result()[0]->company_name);

            $where = "billing.firm_id = '". $this->session->userdata("firm_id")."'";
            $billing_query = $this->db->query("select billing.*, client.company_name, client.incorporation_date, transaction_client.company_name as transaction_client_company_name, transaction_client.incorporation_date as transaction_client_incorporation_date, currency.currency as currency_name from billing left join client on client.company_code = billing.company_code AND client.deleted = 0 left join transaction_client on transaction_client.company_code = billing.company_code left join currency on billing.currency_id = currency.id where ".$where." AND billing.outstanding > 0 AND billing.company_code = '".$q->result()[0]->company_code."' AND billing.status = '0' ORDER BY STR_TO_DATE(billing.invoice_date,'%d/%m/%Y')");

            echo json_encode(array("status" => 1, 'result' => $q->result(), 'currency_result' => $currency_res, 'billing_result' => $billing_query->result()));
        } else echo json_encode(array("status" => 0));
    }

    public function get_credit_note_info()
    {
        $credit_note_id = $_POST["credit_note_id"];

        $q = $this->db->query("select billing_credit_note_record.credit_note_id, billing_credit_note_record.billing_id, billing_credit_note_record.received, billing_credit_note_record.previous_outstanding, credit_note.id, credit_note.credit_note_no, credit_note_date, credit_note.total_amount_discounted, billing.*, client.incorporation_date from billing left join billing_credit_note_record on billing_credit_note_record.billing_id = billing.id left join credit_note on credit_note.id = billing_credit_note_record.credit_note_id left join client on client.company_code = billing.company_code AND  client.deleted = 0 where billing_credit_note_record.credit_note_id = '".$credit_note_id."' AND billing.outstanding != billing.amount");

        if ($q->num_rows() > 0) {
            echo json_encode(array("status" => 1, 'result' => $q->result()));
        } else echo json_encode(array("status" => 0));
    }

    public function save_template()
    {
        $id = array_values($_POST["id"]);
        $service = array_values($_POST["service"]);
        $invoice_description = array_values($_POST["invoice_description"]);
        $amount = array_values($_POST["amount"]);

        $this->db->where('firm_id = '.$this->session->userdata("firm_id"));
        $this->db->delete("billing_template");

        for($i = 0; $i < count($_POST['service']); $i++ )
        { 
            $template['firm_id'] = $this->session->userdata("firm_id");
            $template['service'] = $service[$i];
            $template['invoice_description'] = $invoice_description[$i];
            $template['amount'] = (float)str_replace(',', '', $amount[$i]);

            if($service[$i] == 1)
            {
                $template['frequency'] = 4;
            }
            elseif($service[$i] == 2)
            {
                $template['frequency'] = 5;
            }
            else
            {
                $template['frequency'] = 1;
            }

            $this->db->insert("billing_template",$template);
        }
        echo json_encode(array("Status" => 1));
    }

    public function get_payment_mode()
    {
        $ci =& get_instance();

        $query = "select * from payment_mode";

        $result = $ci->db->query($query);
        $result = $result->result_array();

        if(!$result) {
          throw new exception("Payment Mode not found.");
        }

        $res = array();
        foreach($result as $row) {
            $res[$row['id']] = $row['payment_mode'];
        }        

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Payment Mode fetched successfully.", 'result'=>$res, 'selected_frequency'=>null);

        echo json_encode($data);
    }

    public function get_bank_account()
    {
        $ci =& get_instance();

        $query = "select bank_info.*, currency.currency as currency_name from bank_info left join currency on currency.id = bank_info.currency where firm_id = '".$this->session->userdata('firm_id')."'";

        $result = $ci->db->query($query);
        $result = $result->result_array();

        if(!$result) {
          //throw new exception("Bank Account Mode not found.");
            echo false;
        }

        $res = array();
        foreach($result as $row) {
            $res[$row['id']] = $row['banker']." (".$row['currency_name'].")";
        }        

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Payment Mode fetched successfully.", 'result'=>$res, 'selected_frequency'=>null);

        echo json_encode($data);
    }

    public function save_credit_note()
    {

        if($_POST['credit_note_id'] == "")
        {
            $creditNoteAldExist = $this->db->query("select * from credit_note where `credit_note`.`credit_note_no` = '".$_POST['latest_credit_note_no']."'")->result();
            
            if (count($creditNoteAldExist) > 0) {
                echo "Duplicate Credit Note";
                exit;
            } else {
                $credit_note['firm_id'] = $this->session->userdata("firm_id");
                $credit_note['billing_id'] = $_POST["latest_invoice_no_for_cn_id"];
                $credit_note['company_code'] = $_POST["client_company_code"];
                $credit_note['company_name'] = $_POST["company_name"];
                $credit_note['postal_code'] = $_POST["hidden_postal_code"];
                $credit_note['street_name'] = $_POST["hidden_street_name"];
                $credit_note['building_name'] = $_POST["hidden_building_name"];
                $credit_note['unit_no1'] = $_POST["hidden_unit_no1"];
                $credit_note['unit_no2'] = $_POST["hidden_unit_no2"];
                $credit_note['foreign_address1'] = $_POST["hidden_foreign_address1"];
                $credit_note['foreign_address2'] = $_POST["hidden_foreign_address2"];
                $credit_note['foreign_address3'] = $_POST["hidden_foreign_address3"];
                $credit_note['currency_id'] = $_POST["currency"];
                $credit_note['cn_rate'] = $_POST["cn_rate"];
                $credit_note['credit_note_date'] = $_POST["latest_credit_note_date"];
                $credit_note['credit_note_no'] = $_POST["latest_credit_note_no"];
                $credit_note['total_amount_discounted'] = (float)str_replace(',', '', $_POST["latest_total_amount_discounted"]);
                $credit_note['billing_outstanding'] = (float)$_POST["billing_outstanding"];
                $credit_note['total_cn_amount'] = (float)$_POST["latest_total_cn_amount"];
                $credit_note['cn_out_of_balance'] = (isset($_POST["latest_cn_out_of_balance"]))?(float)$_POST["latest_cn_out_of_balance"]:(float)str_replace(',', '', $_POST["latest_total_amount_discounted"]);
                $credit_note['previous_cn_out_of_balance'] = (isset($_POST["latest_cn_out_of_balance"]))?(float)$_POST["latest_cn_out_of_balance"]:(float)str_replace(',', '', $_POST["latest_total_amount_discounted"]);
                
                if (isset($_POST["latest_credit_note_date"]) && !empty($_POST["latest_credit_note_date"])) {
                    $this->db->insert("billing_credit_note_gst",$credit_note);
                    $credit_note_id = $this->db->insert_id();
                }

                $this->save_audit_trail("Billings", "Credit Note", $_POST["latest_credit_note_no"]." credit no is added.");

                $total_cn_amount_with_gst = 0;

                for($i = 0; $i < count($_POST['billing_service_id']); $i++ )
                {
                    $billing_credit_note_record['credit_note_id'] = $credit_note_id;
                    $billing_credit_note_record['billing_service_id'] = $_POST['billing_service_id'][$i];
                    $billing_credit_note_record['cn_amount'] = (float)str_replace(',', '', $_POST['received'][$i]);
                    $billing_credit_note_record['previous_invoice_amount'] = (float)str_replace(',', '', $_POST['invoice_amount'][$i]);
                    $billing_credit_note_record['gst_rate'] = (int)$_POST['gst_rate'][$i];

                    $this->db->insert("billing_credit_note_gst_record",$billing_credit_note_record);
                }

                $latest_billing_outstanding = (float)$_POST['billing_outstanding'] - (float)$_POST["latest_total_cn_amount"];

                if($latest_billing_outstanding > 0)
                {
                    $billing['outstanding'] = $latest_billing_outstanding;
                }
                else
                {
                    $billing['outstanding'] = 0;
                }

                $this->db->update("billing",$billing,array("id" => $_POST['latest_invoice_no_for_cn_id']));
            }
        }
        else
        {
            $credit_note_id = $_POST['credit_note_id'];
            $credit_note['credit_note_date'] = $_POST["latest_credit_note_date"];
            $credit_note['cn_rate'] = $_POST["cn_rate"];
            $credit_note['total_amount_discounted'] = (float)str_replace(',', '', $_POST["latest_total_amount_discounted"]);
            $credit_note['billing_outstanding'] = (float)$_POST["billing_outstanding"];
            $credit_note['total_cn_amount'] = (float)$_POST["latest_total_cn_amount"];
            $credit_note['cn_out_of_balance'] = (isset($_POST["latest_cn_out_of_balance"]))?(float)$_POST["latest_cn_out_of_balance"]:(float)str_replace(',', '', $_POST["latest_total_amount_discounted"]);
            $credit_note['previous_cn_out_of_balance'] = (isset($_POST["latest_cn_out_of_balance"]))?(float)$_POST["latest_cn_out_of_balance"]:(float)str_replace(',', '', $_POST["latest_total_amount_discounted"]);

            $this->db->update("billing_credit_note_gst",$credit_note,array("id" => $_POST['credit_note_id']));

            $this->save_audit_trail("Billings", "Credit Note", $_POST["latest_credit_note_no"]." credit no is edited.");
            
            for($i = 0; $i < count($_POST['billing_service_id']); $i++ )
            {
                $billing_credit_note_record['credit_note_id'] = $_POST['credit_note_id'];
                $billing_credit_note_record['billing_service_id'] = $_POST['billing_service_id'][$i];
                $billing_credit_note_record['cn_amount'] = (float)str_replace(',', '', $_POST['received'][$i]);
                $billing_credit_note_record['previous_invoice_amount'] = (float)str_replace(',', '', $_POST['invoice_amount'][$i]);
                $billing_credit_note_record['gst_rate'] = (int)$_POST['gst_rate'][$i];

                $where = "id='".$_POST['billing_credit_note_gst_record_id'][$i]."'";
                $this->db->where($where);
                $this->db->update("billing_credit_note_gst_record",$billing_credit_note_record);
            }
            $query_billing = $this->db->query("select * from billing where id='".$_POST['latest_invoice_no_for_cn_id']."'");

            $query_billing = $query_billing->result_array();

            $invoice_outstanding = $query_billing[0]["outstanding"];

            if((float)$_POST["billing_outstanding"] > 0)
            {
                $difference_btw_cn_ammount = (float)$_POST["previous_total_cn_amount"] - (float)$_POST["latest_total_cn_amount"];

                $latest_outstanding = $invoice_outstanding + $difference_btw_cn_ammount; 

                $billing['outstanding'] = $latest_outstanding;
                $this->db->update("billing",$billing,array("id" => $_POST['latest_invoice_no_for_cn_id']));
            }
        }

        if($this->session->userdata('qb_company_id') != "")
        {
            $credit_note_array = $this->check_cn_data_to_qb($credit_note_id);
            $can_import_to_qb = true;
            $service_not_in_qb = "";

            for($k = 0; $k < count($credit_note_array); $k++)
            {
                if($credit_note_array[$k]["qb_item_id"] == 0)
                {
                    $service_not_in_qb = $credit_note_array[$k]["service_name"];
                    $can_import_to_qb = false;
                    break;
                }
            }

            if($can_import_to_qb)
            {
                $cn_submit_status = $this->import_cn_to_qb($credit_note_id, $credit_note_array);
                echo json_encode($cn_submit_status);
            }
            else
            {
                echo json_encode(array("Status" => 2, 'message' => 'This invoice cannot be import to Quickbook Online because ' . $service_not_in_qb .' services is not in Quickbook Online.', 'title' => 'Warning'));
            }
        }
        else
        {
            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Success'));
        }
    }

    public function import_cn_to_qb($credit_note_id, $credit_note_array)
    {
        $check_gst_status_query = $this->db->query("SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox, gst_jurisdiction.jurisdiction as jurisdiction_name FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id LEFT JOIN gst_jurisdiction ON gst_jurisdiction.id = firm.jurisdiction_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = '".$this->session->userdata("firm_id")."'");

        $firm_info = $this->db->query("SELECT firm.*, currency.currency as currency_name FROM firm LEFT JOIN currency ON currency.id = firm.firm_currency WHERE firm.id = '".$this->session->userdata("firm_id")."'");
        $firm_info_arr = $firm_info->result_array();

        $line_array = [];   
        $billing_services_id = [];
        $sub_total = 0;
        $gst = 0;
        $netAmountTaxable = 0;
        $taxRate = 0;
        $curdate = strtotime('01-01-2021');

        for($k = 0; $k < count($credit_note_array); $k++)
        {
            $tax_code_ref = $this->query_qb_tax_code($credit_note_array[$k]["gst_category_name"]);

            $line_info = array("Amount" => $credit_note_array[$k]["cn_amount"], "Description" => $credit_note_array[$k]["invoice_description"], "DetailType" => "SalesItemLineDetail", "SalesItemLineDetail" => ["TaxCodeRef" => ["value" => $tax_code_ref], "ItemRef" => ["value" => $credit_note_array[$k]["qb_item_id"]]]);

            $sub_total += (float)$credit_note_array[$k]["cn_amount"];
            $gst += round((($credit_note_array[$k]['gst_rate'] / 100) * (float)$credit_note_array[$k]["cn_amount"]), 2);

            if($credit_note_array[$k]['gst_rate'] > 0)
            {
                $netAmountTaxable += round((float)$credit_note_array[$k]["cn_amount"], 2);
                $taxRate = $credit_note_array[$k]['gst_rate'];
            }

            array_push($line_array, $line_info);
            array_push($billing_services_id, $credit_note_array[$k]["billing_service_id"]);
        }
        $total = $sub_total + $gst;

        $format_invoice_date = explode('/', $credit_note_array[0]["credit_note_date"]);
        $year = $format_invoice_date[2];
        $month = $format_invoice_date[1];
        $day = $format_invoice_date[0];

        $new_format_txn_date = $year.'/'.$month.'/'.$day;

        if ($check_gst_status_query->num_rows() > 0) 
        {
            if($firm_info_arr[0]["currency_name"] != $credit_note_array[0]["currency_name"])
            {
                $converted_total = $total * $credit_note_array[0]["cn_rate"];
                $creditmemo_info = [
                                    "Line" => $line_array,
                                    "DocNumber" => $credit_note_array[0]["credit_note_no"],
                                    "TxnDate" => $new_format_txn_date,
                                    "CustomerRef"=> [
                                          "value"=>  $credit_note_array[0]["qb_customer_id"]
                                    ],
                                    "CurrencyRef"=> [
                                        "value" => $credit_note_array[0]["currency_name"]
                                    ],
                                    "ExchangeRate" => $credit_note_array[0]["cn_rate"],
                                    "HomeTotalAmt" => $converted_total,
                                    "TxnTaxDetail" => [
                                        "TotalTax" => $gst
                                    ]
                                ];
            }
            else
            {
                $creditmemo_info = [
                                    "Line" => $line_array,
                                    "DocNumber" => $credit_note_array[0]["credit_note_no"],
                                    "TxnDate" => $new_format_txn_date,
                                    "CustomerRef"=> [
                                          "value"=>  $credit_note_array[0]["qb_customer_id"]
                                    ],
                                    "CurrencyRef"=> [
                                        "value" => $credit_note_array[0]["currency_name"]
                                    ],
                                    "TxnTaxDetail" => [
                                        "TotalTax" => $gst
                                    ]
                                ];
            }
        }
        else
        {
            $creditmemo_info = [
                                "Line" => $line_array,
                                "DocNumber" => $credit_note_array[0]["credit_note_no"],
                                "TxnDate" => $new_format_txn_date,
                                "CustomerRef"=> [
                                      "value"=>  $credit_note_array[0]["qb_customer_id"]
                                ],
                                "CurrencyRef"=> [
                                    "value" => $credit_note_array[0]["currency_name"]
                                ]
                            ];
        }

        if($this->session->userdata('refresh_token_value'))
        {
            if($credit_note_array[0]["credit_note_no"] != null && $credit_note_array[0]["qb_customer_id"] != null && $credit_note_array[0]["currency_name"] != null && $credit_note_array[0]["qb_customer_id"] != 0)
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
                         'baseUrl' => $this->quickbookBaseUrl,
                    ));

                    $dataService->throwExceptionOnError(true);

                    if($credit_note_array[0]["qb_cn_id"] != 0)
                    {
                        $creditmemo = $dataService->FindbyId('creditmemo', $credit_note_array[0]["qb_cn_id"]);

                        $theResourceObj = CreditMemo::update($creditmemo, $creditmemo_info);

                        $resultingObj = $dataService->Update($theResourceObj);
                    }
                    else
                    {
                        $theResourceObj = CreditMemo::create($creditmemo_info);

                        $resultingObj = $dataService->Add($theResourceObj);
                    }
                    
                    $error = $dataService->getLastError();

                    if ($error) {
                        if($error->getHttpStatusCode() == "401")
                        {
                            $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                            if($refresh_token_status)
                            {
                                $this->import_cn_to_qb($credit_note_id, $credit_note_array);
                            }
                        }
                        else
                        {
                            return array("Status" => 3, 'message' => $error->getIntuitErrorMessage(), 'title' => 'Error');
                        }
                    }
                    else {
                        $creditmemo_data["qb_cn_id"] = $resultingObj->Id;
                        $creditmemo_data["qb_cn_json"] = json_encode($resultingObj);
                        $this->db->update("billing_credit_note_gst", $creditmemo_data, array("id" => $credit_note_id));

                        $salesItemLineReturnArr = $resultingObj->Line;

                        for($h = 0; $h < count($salesItemLineReturnArr); $h++)
                        {
                            if($salesItemLineReturnArr[$h]->Id)
                            {
                                $cn_services_data["qb_cn_service_id"] = $salesItemLineReturnArr[$h]->Id;
                                $this->db->update("billing_service", $cn_services_data, array("id" => $billing_services_id[$h]));
                            }
                        }

                        //------------------------ Add Payment for CN -----------------------------
                        if($total > 0)
                        {
                            $format_invoice_date = explode('/', $credit_note_array[0]["invoice_date"]);
                            $year = $format_invoice_date[2];
                            $month = $format_invoice_date[1];
                            $day = $format_invoice_date[0];
                            $new_format_inv_date = $month.'/'.$day.'/'.$year;
                            $mydate = strtotime($new_format_inv_date);

                            if($mydate >= $curdate)
                            {
                                $cn_status = $this->link_invoice_with_cn($credit_note_array, $resultingObj->Id, $new_format_txn_date, $credit_note_id, $firm_info_arr);
                            }
                            else
                            {
                                $cn_status = array("Status" => 1, 'message' => "Information Updated", 'title' => 'Success');
                            }
                        }   
                        else
                        {
                            if($credit_note_array[0]["qb_payment_id"] != 0)
                            {
                                $cn_status = $this->delete_payment_with_cn($credit_note_array, $credit_note_id);
                            }
                            else
                            {
                                $cn_status = array("Status" => 1, 'message' => "Information Updated", 'title' => 'Success');
                            }
                        }

                        $this->save_audit_trail("Billings", "QuickBooks", $this->session->userdata('first_name'). " " . $this->session->userdata('last_name') . " import ".$credit_note_array[0]["credit_note_no"]." credit note to QuickBooks Online.");

                        return $cn_status;
                        //------------------------------------------------------------------------------------
                    }
                }
                catch (Exception $e){
                    $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                    if($refresh_token_status)
                    {
                        $this->import_cn_to_qb($credit_note_id, $credit_note_array);
                    }
                }
            }
            else
            {
                if($credit_note_array[0]["credit_note_no"] == null)
                {
                    $missing_name = $credit_note_array[0]["credit_note_no"];
                }
                else if($credit_note_array[0]["qb_customer_id"] == null || $credit_note_array[0]["qb_customer_id"] == 0)
                {
                    $missing_name = $credit_note_array[0]["company_name"] . " (".$credit_note_array[0]["currency_name"].")";
                }
                else if($credit_note_array[0]["currency_name"] == null)
                {
                    $missing_name = $credit_note_array[0]["currency_name"];
                }

                return array("Status" => 2, 'message' => 'This Credit Note cannot be import to Quickbook Online because ' . $missing_name .' is not in Quickbook Online.', 'title' => 'Warning');
            }
        }
        else
        {
            return array("Status" => 2, 'message' => 'Please login to Quickbook Online to save this invoice to Quickbook Online.', 'title' => 'Warning');
        }
    }

    public function delete_payment_with_cn($credit_note_array, $credit_note_id)
    {
        if($this->session->userdata('refresh_token_value'))
        {
            try 
            {
                // Prep Data Services
                $dataService = DataService::Configure(array(
                     'auth_mode' => 'oauth2',
                     'ClientID' => $this->quickbook_clientID,
                     'ClientSecret' => $this->quickbook_clientSecret,
                     'accessTokenKey' => $this->session->userdata('access_token_value'),
                     'refreshTokenKey' => $this->session->userdata('refresh_token_value'),
                     'QBORealmID' => $this->session->userdata('qb_company_id'), //"The Company ID which the app wants to access"
                     'baseUrl' => $this->quickbookBaseUrl,
                ));

                $dataService->throwExceptionOnError(true);
                
                $payment = $dataService->FindbyId('payment', $credit_note_array[0]["qb_payment_id"]);
                $resultingObj = $dataService->Delete($payment);
    
                $error = $dataService->getLastError();

                if ($error) {
                    if($error->getHttpStatusCode() == "401")
                    {
                        $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                        if($refresh_token_status)
                        {
                            $this->delete_payment_with_cn($credit_note_array, $credit_note_id);
                        }
                    }
                    else
                    {
                        return array("Status" => 3, 'message' => $error->getIntuitErrorMessage(), 'title' => 'Error');
                    }
                }
                else {
                    $payment_qb_data["qb_payment_id"] = 0;
                    $this->db->update("billing_credit_note_gst", $payment_qb_data, array("id" => $credit_note_id));

                    return array("Status" => 1, 'message' => "Information Updated", 'title' => 'Success');
                }
            }
            catch (Exception $e){
                $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                if($refresh_token_status)
                {
                    $this->delete_payment_with_cn($credit_note_array, $credit_note_id);
                }
            }
        }
        else
        {
            return array("Status" => 2, 'message' => 'Please login to Quickbook Online to delete this receipt in Quickbook Online.', 'title' => 'Warning');
        }
    }

    public function link_invoice_with_cn($credit_note_array, $qb_cn_id, $new_format_txn_date, $credit_note_id, $firm_info_arr)
    {
        if($credit_note_array[0]["cn_billing_outstanding"] > 0)
        {
            if($this->session->userdata('refresh_token_value'))
            {   
                $resultingObj = "";
                try 
                {
                    $py_dataService = DataService::Configure(array(
                         'auth_mode' => 'oauth2',
                         'ClientID' => $this->quickbook_clientID,
                         'ClientSecret' => $this->quickbook_clientSecret,
                         'accessTokenKey' => $this->session->userdata('access_token_value'),
                         'refreshTokenKey' => $this->session->userdata('refresh_token_value'),
                         'QBORealmID' => $this->session->userdata('qb_company_id'), //"The Company ID which the app wants to access"
                         'baseUrl' => $this->quickbookBaseUrl,
                    ));

                    $py_dataService->throwExceptionOnError(true);

                    $payment_line_info = array();
                    $payment_invoice_line_info = array(array("Amount" => $credit_note_array[0]["total_amount_discounted"], "LinkedTxn" => [["TxnId" => $credit_note_array[0]["qb_invoice_id"], "TxnType" => "Invoice"]]));
                    $payment_cn_line_info = array(array("Amount" => $credit_note_array[0]["total_amount_discounted"], "LinkedTxn" => [["TxnId" => $qb_cn_id, "TxnType" => "CreditMemo"]]));
                    $payment_line_info = array_merge($payment_line_info, $payment_invoice_line_info);
                    $payment_line_info = array_merge($payment_line_info, $payment_cn_line_info);

                    if($firm_info_arr[0]["currency_name"] != $credit_note_array[0]["currency_name"])
                    {
                        $payment_info = [
                                "Line" => $payment_line_info,
                                "TotalAmt" => 0,
                                "TxnDate" => $new_format_txn_date,
                                "CustomerRef"=> [
                                    "value"=>  $credit_note_array[0]["qb_customer_id"]
                                ],
                                "CurrencyRef"=> [
                                    "value" => $credit_note_array[0]["currency_name"]
                                ],
                                "ExchangeRate" => $credit_note_array[0]["cn_rate"]
                            ]; 
                    }
                    else
                    {
                        $payment_info = [
                                "Line" => $payment_line_info,
                                "TotalAmt" => 0,
                                "TxnDate" => $new_format_txn_date,
                                "CustomerRef"=> [
                                    "value"=>  $credit_note_array[0]["qb_customer_id"]
                                ],
                                "CurrencyRef"=> [
                                    "value" => $credit_note_array[0]["currency_name"]
                                ]
                            ]; 
                    }
                    
                    //print_r($payment_info);
                    if($credit_note_array[0]["qb_payment_id"] != 0)
                    {
                        $payment = $py_dataService->FindbyId('payment', $credit_note_array[0]["qb_payment_id"]);

                        $theResourceObj = Payment::update($payment, $payment_info);

                        $resultingObj = $py_dataService->Update($theResourceObj);
                    }
                    else
                    {
                        $theResourceObj = Payment::create($payment_info);

                        $resultingObj = $py_dataService->Add($theResourceObj);
                    }
                    
                    $error = $py_dataService->getLastError();

                    if ($error) {
                        if($error->getHttpStatusCode() == "401")
                        {
                            $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                            if($refresh_token_status)
                            {
                                $this->link_invoice_with_cn($credit_note_array, $qb_cn_id, $new_format_txn_date, $credit_note_id, $firm_info_arr);
                            }
                        }
                        else
                        {
                            return array("Status" => 3, 'message' => $error->getIntuitErrorMessage(), 'title' => 'Error');
                        }
                    }
                    else {
                        $payment_qb_data["qb_payment_id"] = $resultingObj->Id;
                        $this->db->update("billing_credit_note_gst", $payment_qb_data, array("id" => $credit_note_id));

                        return array("Status" => 1, 'message' => "Information Updated", 'title' => 'Success');
                    }
                }
                catch (Exception $e){
                    //$error = $py_dataService->getLastError();
                    $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                    if($refresh_token_status)
                    {
                        $this->link_invoice_with_cn($credit_note_array, $qb_cn_id, $new_format_txn_date, $credit_note_id, $firm_info_arr);
                    }
                }
            }
        }
        return array("Status" => 1, 'message' => "Information Updated", 'title' => 'Success');
    }

    public function save_receipt()
    {
        if(!isset($_POST['receipt_id']))
        {
            $check_receipt_id_result = $this->db->query("select * from receipt left join billing_receipt_record on billing_receipt_record.receipt_id = receipt.id where receipt_no = '".$_POST["receipt_no"]."' and billing_receipt_record.firm_id = '".$this->session->userdata("firm_id")."'");

            $check_receipt_id_result = $check_receipt_id_result->result_array();

            if($check_receipt_id_result)
            {
                $can_insert_receipt_service = false;
            }
            else
            {
                $can_insert_receipt_service = true;
            }

            if($can_insert_receipt_service)
            {
                $cn_total_received = 0;
                $_POST['received'] = array_values($_POST['received']);
                $receipt['receipt_no'] = $_POST["receipt_no"];
                $receipt['receipt_date'] = $_POST["receipt_date"];
                $receipt['reference_no'] = $_POST["reference_no"];
                $receipt['payment_mode'] = $_POST["payment_mode"];
                $receipt['bank_account_id'] = $_POST["bank_account"];
                $receipt['currency_total_amount_received'] = $_POST["currency_total_amount_received"];
                $receipt['total_amount_received'] = (float)str_replace(',', '', $_POST["total_amount_received"]);

                if((float)str_replace(',', '', $_POST["out_of_balance_equival_amount"]) > 0)
                {
                    $receipt['out_of_balance'] = (float)str_replace(',', '', $_POST["out_of_balance_equival_amount"]);
                }
                else
                {
                    $receipt['out_of_balance'] = (float)str_replace(',', '', $_POST["out_of_balance_original_amount"]);
                }

                $this->db->insert("receipt",$receipt);
                $receipt_id = $this->db->insert_id();

                $this->save_audit_trail("Billings", "Receipt", $_POST["receipt_no"]." receipt is added.");

                for($i = 0; $i < count($_POST['id']); $i++ )
                {
                    $received = (float)str_replace(',', '', $_POST['received'][$i]);

                    if($received > 0)
                    {
                        $new_outstanding = (float)$_POST['outstanding'][$i] - $received;

                        if(0 > $new_outstanding)
                        {
                            $billing["outstanding"] = 0;
                            $billing["pay_additional"] = -($new_outstanding);
                        }
                        else
                        {
                            $billing["outstanding"] = $new_outstanding;
                            $billing["pay_additional"] = 0;
                        } 
                        $this->db->update("billing",$billing,array("id" => $_POST['id'][$i]));
                        $billing_receipt_record['firm_id'] = $this->session->userdata("firm_id");
                        $billing_receipt_record['receipt_id'] = (float)$receipt_id;
                        $billing_receipt_record['billing_id'] = $_POST['id'][$i];
                        $billing_receipt_record['received'] = (float)str_replace(',', '', $_POST['received'][$i]);
                        $billing_receipt_record['equival_amount'] = (float)str_replace(',', '', $_POST['equival_amount'][$i]);
                        $billing_receipt_record['previous_outstanding'] = (float)str_replace(',', '', $_POST['outstanding'][$i]);
                        if((float)str_replace(',', '', $_POST["unassign_amt"]) > 0)
                        {
                            $cn_total_received += (float)str_replace(',', '', $_POST['received'][$i]);
                            $billing_receipt_record['is_from_cn'] = 1;
                        }

                        $this->db->insert("billing_receipt_record",$billing_receipt_record);
                    }
                }

                $amunt_left = 0;
                if((float)str_replace(',', '', $_POST["unassign_amt"]) > 0)
                {
                    $cn_total_amount_received = (float)$cn_total_received;
                }
                else
                {
                    $cn_total_amount_received = (float)str_replace(',', '', $_POST["total_amount_received"]);
                }
                
                if((float)str_replace(',', '', $_POST["unassign_amt"]) > 0)
                {
                    $query_billing_credit_note_gst = $this->db->query("select * from billing_credit_note_gst where company_code ='".$_POST['unassign_company_code']."' AND credit_note_no = '".$_POST["hidden_reference_no"]."' AND cn_out_of_balance != 0 ORDER BY id");

                    $query_billing_credit_note_gst = $query_billing_credit_note_gst->result_array();

                    foreach ($query_billing_credit_note_gst as $key => $value) {

                        if($cn_total_amount_received > 0)
                        {
                            $amunt_left = $cn_total_amount_received - (float)$value["cn_out_of_balance"];
                            $cn_total_amount_received = $amunt_left;

                            if($amunt_left >= 0)
                            {
                                $billing_credit_note_gst["cn_out_of_balance"] = 0;
                                //$billing_credit_note_gst["previous_cn_out_of_balance"] = $value["cn_out_of_balance"];
                            }
                            else
                            {
                                $billing_credit_note_gst["cn_out_of_balance"] = -($amunt_left);
                            }
                        }
                        else
                        {
                            $amunt_left = (float)$value["cn_out_of_balance"];
                            $cn_total_amount_received = 0;

                            $billing_credit_note_gst["cn_out_of_balance"] = $amunt_left;
                        }
                        
                        $this->db->update("billing_credit_note_gst", $billing_credit_note_gst, array("id" => $value['id']));

                        $billing_credit_note_gst_with_receipt["billing_credit_note_gst_id"] = $value['id'];
                        $billing_credit_note_gst_with_receipt["receipt_id"] = $receipt_id;
                        $billing_credit_note_gst_with_receipt["previous_cn_currency"] = $_POST["unassign_ccy"];
                        $billing_credit_note_gst_with_receipt["previous_cn_out_of_balance_amt"] = $value["cn_out_of_balance"];
                        $billing_credit_note_gst_with_receipt["previous_total_cn_out_of_balance"] = (float)str_replace(',', '', $_POST["unassign_amt"]);
                        $this->db->insert("billing_credit_note_gst_with_receipt",$billing_credit_note_gst_with_receipt);
                    }
                }
            }
            else
            {
                echo json_encode(array("Status" => 3, 'message' => 'This Receipt No is already use.', 'title' => 'Error'));
            }
        }
        else
        {
            $new_outstanding = 0;
            $cn_total_received = 0;
            $_POST['received'] = array_values($_POST['received']);
            $receipt_id = $_POST['receipt_id'];
            $receipt['receipt_no'] = $_POST["receipt_no"];
            $receipt['receipt_date'] = $_POST["receipt_date"];
            $receipt['reference_no'] = $_POST["reference_no"];
            $receipt['payment_mode'] = $_POST["payment_mode"];
            $receipt['bank_account_id'] = $_POST["bank_account"];
            $receipt['currency_total_amount_received'] = $_POST["currency_total_amount_received"];
            $receipt['total_amount_received'] = (float)str_replace(',', '', $_POST["total_amount_received"]);
            if((float)str_replace(',', '', $_POST["out_of_balance_equival_amount"]) > 0)
            {
                $receipt['out_of_balance'] = (float)str_replace(',', '', $_POST["out_of_balance_equival_amount"]);
            }
            else
            {
                $receipt['out_of_balance'] = (float)str_replace(',', '', $_POST["out_of_balance_original_amount"]);
            }

            $this->db->update("receipt",$receipt,array("id" => $_POST['receipt_id']));
            $this->save_audit_trail("Billings", "Receipt", $_POST["receipt_no"]." receipt is edited.");

            for($i = 0; $i < count($_POST['id']); $i++ )
            {
                $received = (float)str_replace(',', '', $_POST['received'][$i]);

                $query_billing = $this->db->query("select * from billing where id='".$_POST['id'][$i]."'");

                $query_billing = $query_billing->result_array();

                $query = $this->db->query("select * from billing_receipt_record where receipt_id = '".$_POST['receipt_id']."' AND billing_id='".$_POST['id'][$i]."'");

                $query = $query->result_array();

                $query_billing_credit_note_record = $this->db->query("select * from billing_credit_note_record where billing_id='".$_POST['id'][$i]."'");

                $query_billing_credit_note_record = $query_billing_credit_note_record->result_array();

                $check_inv_cn = $this->db->query("select SUM(billing_credit_note_gst.total_cn_amount) as total_cn_amount from billing_credit_note_gst where billing_credit_note_gst.billing_id = ".$_POST['id'][$i]." ORDER BY id");

                $check_inv_cn = $check_inv_cn->result_array();

                if($received > 0)
                {
                    if(count($query) > 0)
                    {
                        $billing_receipt_record['received'] = (float)str_replace(',', '', $_POST['received'][$i]);
                        $billing_receipt_record['equival_amount'] = (float)str_replace(',', '', $_POST['equival_amount'][$i]);

                        $where = "receipt_id='".$_POST['receipt_id']."' AND billing_id='".$_POST['id'][$i]."'";
                        $this->db->where($where);
                        $this->db->update("billing_receipt_record",$billing_receipt_record);

                        if((float)str_replace(',', '', $_POST["unassign_amt"]) > 0)
                        {
                            $cn_total_received += (float)str_replace(',', '', $_POST['received'][$i]);
                        }

                        $query_other_receipt_record = $this->db->query("select * from billing_receipt_record where billing_id='".$_POST['id'][$i]."' AND id != '".$query[0]["id"]."' ORDER BY id");

                        if ($query_other_receipt_record->num_rows())
                        {
                            $query_other_receipt_record = $query_other_receipt_record->result_array();

                            $query_billing = $this->db->query("select * from billing where id='".$_POST['id'][$i]."'");

                            $query_billing = $query_billing->result_array();

                            $new_outstanding = (float)$new_outstanding + (float)str_replace(',', '', $_POST['received'][$i]);

                            for($r = 0; $r < count($query_other_receipt_record); $r++)
                            {
                                $new_outstanding = (float)$new_outstanding + (float)$query_other_receipt_record[$r]['received'];
                            }

                            if(count($check_inv_cn) > 0)
                            {
                                $new_outstanding = (float)$query_billing[0]["amount"] - (float)$new_outstanding - $check_inv_cn[0]["total_cn_amount"];
                            }
                            else
                            {
                                $new_outstanding = (float)$query_billing[0]["amount"] - (float)$new_outstanding;
                            }

                            if(0 > $new_outstanding)
                            {
                                $change_outstanding["outstanding"] = 0;
                                $change_outstanding["pay_additional"] = -($new_outstanding);
                            }
                            else
                            {
                                $change_outstanding["outstanding"] = $new_outstanding;
                                $change_outstanding["pay_additional"] = 0;
                            }
                            
                            $this->db->update("billing",$change_outstanding,array("id" => $_POST['id'][$i]));
                        }
                        else
                        {
                            if(count($check_inv_cn) > 0)
                            {
                                $new_outstanding = (float)$query_billing[0]["amount"] - (float)$query_billing_credit_note_record[0]["received"] - (float)str_replace(',', '', $_POST['received'][$i]) - $check_inv_cn[0]["total_cn_amount"];
                            }
                            else
                            {
                                $new_outstanding = (float)$query_billing[0]["amount"] - (float)$query_billing_credit_note_record[0]["received"] - (float)str_replace(',', '', $_POST['received'][$i]);
                            }

                            if(0 > $new_outstanding)
                            {
                                $change_outstanding["outstanding"] = 0;
                                $change_outstanding["pay_additional"] = -($new_outstanding);
                            }
                            else
                            {
                                $change_outstanding["outstanding"] = $new_outstanding;
                                $change_outstanding["pay_additional"] = 0;
                            } 
                            $this->db->update("billing",$change_outstanding,array("id" => $_POST['id'][$i]));
                        }
                    }
                    else
                    {
                        $new_outstanding = (float)$_POST['outstanding'][$i] - $received;

                        if(0 > $new_outstanding)
                        {
                            $billing["outstanding"] = 0;
                            $billing["pay_additional"] = -($new_outstanding);
                        }
                        else
                        {
                            $billing["outstanding"] = $new_outstanding;
                            $billing["pay_additional"] = 0;
                        } 
                        $this->db->update("billing",$billing,array("id" => $_POST['id'][$i]));
                        $billing_receipt_record['firm_id'] = $this->session->userdata("firm_id");
                        $billing_receipt_record['receipt_id'] = $_POST['receipt_id'];
                        $billing_receipt_record['billing_id'] = $_POST['id'][$i];
                        $billing_receipt_record['received'] = (float)str_replace(',', '', $_POST['received'][$i]);
                        $billing_receipt_record['equival_amount'] = (float)str_replace(',', '', $_POST['equival_amount'][$i]);
                        $billing_receipt_record['previous_outstanding'] = (float)str_replace(',', '', $_POST['outstanding'][$i]);
                        if((float)str_replace(',', '', $_POST["unassign_amt"]) > 0)
                        {
                            $cn_total_received += (float)str_replace(',', '', $_POST['received'][$i]);
                            $billing_receipt_record['is_from_cn'] = 1;
                        }
                        $this->db->insert("billing_receipt_record",$billing_receipt_record);
                    }
                }
                else
                {
                    $new_outstanding = (float)$query_billing[0]["amount"] - (float)$query_billing_credit_note_record[0]["received"] - (float)str_replace(',', '', $_POST['received'][$i]);

                    if(0 > $new_outstanding)
                    {
                        $change_outstanding["outstanding"] = 0;
                        $change_outstanding["pay_additional"] = -($new_outstanding);
                    }
                    else
                    {
                        $change_outstanding["outstanding"] = $new_outstanding;
                        $change_outstanding["pay_additional"] = 0;
                    } 
                    $this->db->update("billing",$change_outstanding,array("id" => $_POST['id'][$i]));

                    $this->db->where('id', $query[0]["id"]);
                    $this->db->delete("billing_receipt_record");
                }
            }

            $amunt_left = 0;
            if((float)str_replace(',', '', $_POST["unassign_amt"]) > 0)
            {
                $cn_total_amount_received = $cn_total_received;
            }
            else
            {
                $cn_total_amount_received = (float)str_replace(',', '', $_POST["total_amount_received"]);
            }

            if((float)str_replace(',', '', $_POST["unassign_amt"]) > 0)
            {
                $query_billing_credit_note_gst_with_receipt = $this->db->query("select billing_credit_note_gst_with_receipt.*, billing_credit_note_gst.previous_cn_out_of_balance from billing_credit_note_gst_with_receipt LEFT JOIN billing_credit_note_gst ON billing_credit_note_gst.id = billing_credit_note_gst_with_receipt.billing_credit_note_gst_id where billing_credit_note_gst_with_receipt.receipt_id ='".$_POST['receipt_id']."' ORDER BY billing_credit_note_gst_with_receipt.id");

                $query_billing_credit_note_gst_with_receipt = $query_billing_credit_note_gst_with_receipt->result_array();

                foreach ($query_billing_credit_note_gst_with_receipt as $key => $value) {

                    if($cn_total_amount_received > 0)
                    {
                        $amunt_left = $cn_total_amount_received - (float)$value["previous_cn_out_of_balance_amt"];
                        $cn_total_amount_received = $amunt_left;

                        if($amunt_left >= 0)
                        {
                            $billing_credit_note_gst["cn_out_of_balance"] = 0;
                        }
                        else
                        {
                            $billing_credit_note_gst["cn_out_of_balance"] = -($amunt_left);
                        }
                    }
                    else
                    {
                        $amunt_left = (float)$value["previous_cn_out_of_balance_amt"];
                        $cn_total_amount_received = 0;

                        $billing_credit_note_gst["cn_out_of_balance"] = $amunt_left;
                    }

                    $this->db->update("billing_credit_note_gst", $billing_credit_note_gst, array("id" => $value['billing_credit_note_gst_id']));
                }
            }
        }

        if($this->session->userdata('qb_company_id') != "")
        {
            if((float)str_replace(',', '', $_POST["unassign_amt"]) > 0)
            {
                $receipt_submit_status = $this->import_receipt_to_qb($receipt_id, true, $_POST["hidden_reference_no"]);
            }
            else
            {
                $receipt_submit_status = $this->import_receipt_to_qb($receipt_id, false);
            }

            echo $receipt_submit_status;
        }
        else
        {
            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Success'));
        }
    }

    public function retrieve_receipt_for_qb($receipt_id)
    {
        $check_is_come_from_services = $this->db->query('select transaction_master_with_billing.*, transaction_master.transaction_task_id 
            from transaction_master_with_billing 
            left join billing_receipt_record on billing_receipt_record.billing_id = transaction_master_with_billing.billing_id 
            left join transaction_master on transaction_master.id =  transaction_master_with_billing.transaction_master_id 
            where billing_receipt_record.receipt_id = "'.$receipt_id.'"');

        if ($check_is_come_from_services->num_rows() > 0) 
        {
            $check_is_come_from_services = $check_is_come_from_services->result_array();

            if($check_is_come_from_services[0]["transaction_task_id"] != "1")
            {
                $qb_customer_id = ", client_qb_id.qb_customer_id, client.company_name, client.incorporation_date";
                $left_join_client = " LEFT JOIN client ON client.company_code = billing.company_code LEFT JOIN client_qb_id ON client_qb_id.company_code = billing.company_code AND client_qb_id.currency_name = currency.currency AND client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";
            }
            else
            {
                $qb_customer_id = ", transaction_client_qb_id.qb_customer_id, transaction_client.company_name, transaction_client.incorporation_date";
                $left_join_client = " LEFT JOIN transaction_client ON transaction_client.company_code = billing.company_code LEFT JOIN transaction_client_qb_id ON transaction_client_qb_id.company_code = billing.company_code AND transaction_client_qb_id.currency_name = currency.currency AND transaction_client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";
            }

            $receipt_query = $this->db->query("select billing_receipt_record.receipt_id, billing_receipt_record.billing_id, billing_receipt_record.received, billing_receipt_record.equival_amount, billing_receipt_record.previous_outstanding, billing_receipt_record.is_from_cn, receipt.id, receipt.receipt_no, receipt_date, receipt.reference_no, receipt.payment_mode as payment_mode_id, receipt.bank_account_id, receipt.total_amount_received, receipt.currency_total_amount_received, receipt.out_of_balance, billing.*, payment_mode.payment_mode, currency.currency as currency_name, bank_info.qb_bank_name, receipt_qb_record.qb_receipt_id".$qb_customer_id."
                from billing 
                left join billing_receipt_record on billing_receipt_record.billing_id = billing.id 
                left join receipt on receipt.id = billing_receipt_record.receipt_id 
                left join currency on billing.currency_id = currency.id 
                ".$left_join_client."
                left join payment_mode on payment_mode.id = receipt.payment_mode 
                left join bank_info on bank_info.id = receipt.bank_account_id
                left join receipt_qb_record on receipt_qb_record.receipt_id = receipt.id and receipt_qb_record.currency_name = currency.currency
                where billing_receipt_record.receipt_id = '".$receipt_id."' AND billing.outstanding != billing.amount");
        }
        else
        {
            $receipt_query = $this->db->query("select billing_receipt_record.receipt_id, billing_receipt_record.billing_id, billing_receipt_record.received, billing_receipt_record.equival_amount, billing_receipt_record.previous_outstanding, billing_receipt_record.is_from_cn, receipt.id, receipt.receipt_no, receipt_date, receipt.reference_no, receipt.payment_mode as payment_mode_id, receipt.bank_account_id, receipt.total_amount_received, receipt.currency_total_amount_received, receipt.out_of_balance, billing.*, client.company_name, client.incorporation_date, client_qb_id.qb_customer_id, payment_mode.payment_mode, currency.currency as currency_name, bank_info.qb_bank_name, receipt_qb_record.qb_receipt_id
                from billing 
                left join billing_receipt_record on billing_receipt_record.billing_id = billing.id 
                left join receipt on receipt.id = billing_receipt_record.receipt_id 
                left join client on client.company_code = billing.company_code 
                left join payment_mode on payment_mode.id = receipt.payment_mode 
                left join currency on billing.currency_id = currency.id 
                left join bank_info on bank_info.id = receipt.bank_account_id
                LEFT JOIN client_qb_id ON client_qb_id.company_code = billing.company_code AND client_qb_id.currency_name = currency.currency AND client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."'
                left join receipt_qb_record on receipt_qb_record.receipt_id = receipt.id and receipt_qb_record.currency_name = currency.currency
                where billing_receipt_record.receipt_id = '".$receipt_id."' AND billing.outstanding != billing.amount");
        }

        return $receipt_query;
    }

    public function import_receipt_to_qb($receipt_id, $is_from_cn, $reference_no = null)
    {
        $receipt_query = $this->retrieve_receipt_for_qb($receipt_id);

        $firm_info = $this->db->query("SELECT firm.*, currency.currency as currency_name FROM firm LEFT JOIN currency ON currency.id = firm.firm_currency WHERE firm.id = '".$this->session->userdata("firm_id")."'");
        $firm_info_arr = $firm_info->result_array();

        if ($receipt_query->num_rows() > 0) 
        {
            $receipt_array = $receipt_query->result_array();

            $gotInvoiceNo = true;
            $check_currency_arr = [];
            $current_currency = "";
            $current_qb_client = "";
            $can_import = false;
            $curdate = strtotime('01-01-2021');
            
            for($p = 0; $p < count($receipt_array); $p++)
            {
                $success = true;
                $line_array = [];
                $total_receipt_amount = 0;
                
                if(!in_array($receipt_array[$p]["currency_name"], $check_currency_arr))
                {
                    array_push($check_currency_arr, $receipt_array[$p]["currency_name"]);
                    $current_currency = $receipt_array[$p]["currency_name"];
                    $current_qb_client = $receipt_array[$p]["qb_customer_id"];
                    $qb_receipt_id = $receipt_array[$p]["qb_receipt_id"];
                    $can_import = true;
                }
                else
                {
                    $can_import = false;
                }

                if($can_import)
                {
                    for($k = 0; $k < count($receipt_array); $k++)
                    {
                        if($current_currency == $receipt_array[$k]["currency_name"])
                        {
                            $format_invoice_date = explode('/', $receipt_array[$k]["invoice_date"]);
                            $year = $format_invoice_date[2];
                            $month = $format_invoice_date[1];
                            $day = $format_invoice_date[0];
                            $new_format_inv_date = $month.'/'.$day.'/'.$year;
                            $mydate = strtotime($new_format_inv_date);

                            if($receipt_array[$k]["qb_invoice_id"] != 0 && $mydate >= $curdate)
                            {
                                $line_info = array("Amount" => $receipt_array[$k]["received"], "LinkedTxn" => [["TxnId" => $receipt_array[$k]["qb_invoice_id"], "TxnType" => "Invoice"]]);
                                $total_receipt_amount += $receipt_array[$k]["received"];
                                array_push($line_array, $line_info);
                            }
                            else if($curdate > $mydate)
                            {
                                $total_receipt_amount += $receipt_array[$k]["received"];
                            }
                            else if($mydate >= $curdate)
                            {
                                $gotInvoiceNo = false;
                                $invoice_no_not_in_qb = $receipt_array[$k]["invoice_no"];
                                break;
                            }
                        }
                    }

                    if($is_from_cn)
                    {
                        $billing_credit_note_gst_info = $this->db->query("SELECT billing_credit_note_gst.* FROM billing_credit_note_gst WHERE credit_note_no = '".$reference_no."'");
                        $billing_credit_note_gst_arr = $billing_credit_note_gst_info->result_array();

                        $payment_cn_line_info = array("Amount" => $total_receipt_amount, "LinkedTxn" => [["TxnId" => $billing_credit_note_gst_arr[0]["qb_cn_id"], "TxnType" => "CreditMemo"]]);
                        array_push($line_array, $payment_cn_line_info);
                        $total_receipt_amount = 0;
                    }
                    
                    if($this->session->userdata('refresh_token_value'))
                    {
                        if($gotInvoiceNo)
                        {
                            $format_receipt_date = explode('/', $receipt_array[0]["receipt_date"]);
                            $year = $format_receipt_date[2];
                            $month = $format_receipt_date[1];
                            $day = $format_receipt_date[0];

                            $new_format_txn_date = $year.'/'.$month.'/'.$day;
                            $bank_name_id = $this->get_income_account($receipt_array[0]["qb_bank_name"]);

                            $payment_method_id = $this->get_payment_method($receipt_array[0]["payment_mode"]);

                            if($firm_info_arr[0]["currency_name"] != $current_currency && count($line_array) > 0)
                            {
                                $exchangeRate = round((float)$receipt_array[$p]["equival_amount"] / (float)$receipt_array[$p]["received"], 7);

                                $receipt_info = [
                                                    "Line" => $line_array,
                                                    "TotalAmt" => $total_receipt_amount + $receipt_array[0]["out_of_balance"],
                                                    "TxnDate" => $new_format_txn_date,
                                                    "PaymentRefNum" => $receipt_array[0]["receipt_no"],
                                                    "CustomerRef"=> [
                                                        "value"=>  $current_qb_client
                                                    ],
                                                    "CurrencyRef"=> [
                                                        "value" => $current_currency
                                                    ],
                                                    "ExchangeRate" => $exchangeRate
                                                ]; 
                            }
                            else if (count($line_array) > 0)
                            {
                                $receipt_info = [
                                                    "Line" => $line_array,
                                                    "TotalAmt" => $total_receipt_amount + $receipt_array[0]["out_of_balance"],
                                                    "TxnDate" => $new_format_txn_date,
                                                    "PaymentRefNum" => $receipt_array[0]["receipt_no"],
                                                    "CustomerRef"=> [
                                                        "value"=>  $current_qb_client
                                                    ],
                                                    "CurrencyRef"=> [
                                                        "value" => $current_currency
                                                    ]
                                                    
                                                ]; 
                            }
                            else
                            {
                                $receipt_info = [
                                                    "TotalAmt" => $total_receipt_amount + $receipt_array[0]["out_of_balance"],
                                                    "TxnDate" => $new_format_txn_date,
                                                    "PaymentRefNum" => $receipt_array[0]["receipt_no"],
                                                    "CustomerRef"=> [
                                                        "value"=>  $current_qb_client
                                                    ],
                                                    "CurrencyRef"=> [
                                                        "value" => $current_currency
                                                    ]
                                                    
                                                ]; 
                            }

                            if($bank_name_id != null)
                            {
                                $bank_name_add = ["DepositToAccountRef" => [
                                                "value" => $bank_name_id,
                                                "name" => $receipt_array[0]["qb_bank_name"]
                                            ]];
                                $receipt_info = array_merge($receipt_info, $bank_name_add);
                            }

                            if($payment_method_id != null)
                            {
                                $payment_method_add = ["PaymentMethodRef" => [
                                                "value" => $payment_method_id
                                            ]];
                                $receipt_info = array_merge($receipt_info, $payment_method_add);
                            }

                            //print_r($receipt_info);

                            if($receipt_array[0]["receipt_no"] != null && $current_qb_client != null && $current_currency != null && $current_qb_client != 0)
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
                                         'baseUrl' => $this->quickbookBaseUrl,
                                    ));

                                    $dataService->throwExceptionOnError(true);

                                    if(!empty($qb_receipt_id))
                                    {
                                        $receipt = $dataService->FindbyId('payment', $qb_receipt_id);

                                        $theResourceObj = Payment::update($receipt, $receipt_info);

                                        $resultingObj = $dataService->Update($theResourceObj);
                                    }
                                    else
                                    {
                                        $theResourceObj = Payment::create($receipt_info);

                                        $resultingObj = $dataService->Add($theResourceObj);
                                    }
                                    
                                    $error = $dataService->getLastError();

                                    if ($error) {
                                        if($error->getHttpStatusCode() == "401")
                                        {
                                            $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                                            if($refresh_token_status)
                                            {
                                                $this->import_receipt_to_qb($receipt_id, $is_from_cn, $reference_no);
                                            }
                                        }
                                        else
                                        {
                                            return json_encode(array("Status" => 3, 'message' => $error->getIntuitErrorMessage(), 'title' => 'Error'));
                                        }
                                    }
                                    else {
                                        $receipt_data["receipt_id"] = $receipt_id;
                                        $receipt_data["qb_receipt_id"] = $resultingObj->Id;
                                        $receipt_data["currency_name"] = $current_currency;
                                        $receipt_data["qb_receipt_json"] = json_encode($resultingObj);

                                        if(!empty($qb_receipt_id))
                                        {
                                            $this->db->update("receipt_qb_record", $receipt_data, array("qb_receipt_id" => $qb_receipt_id));
                                        }
                                        else
                                        {
                                            $this->db->insert("receipt_qb_record",$receipt_data);
                                        }
                                    }
                                }
                                catch (Exception $e){
                                    $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                                    if($refresh_token_status)
                                    {
                                        $this->import_receipt_to_qb($receipt_id, $is_from_cn, $reference_no);
                                    }
                                }
                            }
                            else
                            {
                                if($receipt_array[0]["receipt_no"] == null)
                                {
                                    $missing_name = $receipt_array[0]["receipt_no"];
                                }
                                else if($current_qb_client == null || $current_qb_client == 0)
                                {
                                    $missing_name = $receipt_array[0]["company_name"] . " (".$current_currency.")";
                                }
                                else if($current_currency == null)
                                {
                                    $missing_name = $current_currency;
                                }
                                $success = false;
                                echo json_encode(array("Status" => 2, 'message' => 'This receipt cannot be import to Quickbook Online because ' . $missing_name .' is not in Quickbook Online.', 'title' => 'Warning'));
                                break;
                            }
                        }
                        else
                        {
                            $success = false;
                            return json_encode(array("Status" => 2, 'message' => 'Invoice No. '.$invoice_no_not_in_qb.' not in Quickbook Online.', 'title' => 'Warning'));
                        }
                    }
                    else
                    {
                        $success = false;
                        return json_encode(array("Status" => 2, 'message' => 'Please login to Quickbook Online to save this receipt to Quickbook Online.', 'title' => 'Warning'));
                    }
                }
            }

            if($success == true)
            {
                $this->save_audit_trail("Billings", "QuickBooks", $this->session->userdata('first_name'). " " . $this->session->userdata('last_name') . " import ".$receipt_array[0]["receipt_no"]." receipt to QuickBooks Online.");
                return json_encode(array("Status" => 1, 'message' => "Information Updated", 'title' => 'Success'));
            }
        }
    }

    public function get_invoice_no()
    {
        $current_year = date("Y");

        if($this->session->userdata("firm_id") == 18 || $this->session->userdata("firm_id") == 26)
        {
            $where = '(firm_id = 18 or firm_id = 26)';
        }
        else
        {
            $where = "firm_id = '".$this->session->userdata('firm_id')."'";
        }
        $query_invoice_no = $this->db->query("select id, invoice_no, MAX(CAST(SUBSTRING(invoice_no, -4) AS UNSIGNED)) as latest_invoice_no from billing where status = '0' and YEAR(STR_TO_DATE(invoice_date,'%d/%m/%Y')) = ".$current_year." and ".$where." GROUP BY invoice_no ORDER BY latest_invoice_no DESC LIMIT 1");

        if ($query_invoice_no->num_rows() > 0) 
        {
            $query_invoice_no = $query_invoice_no->result_array();

            $last_section_invoice_no = (string)$query_invoice_no[0]["invoice_no"];

            if($this->session->userdata("firm_id") == 24) // short year format for AAASB
            {
                $number = "AAASB-".date("y")."-".(str_pad((int)(substr($last_section_invoice_no, -4)) + 1, 4, '0', STR_PAD_LEFT));
            }
            elseif($this->session->userdata("firm_id") == 25) // short year format for AAT
            {
                $number = "AAT-".date("y")."-".(str_pad((int)(substr($last_section_invoice_no, -4)) + 1, 4, '0', STR_PAD_LEFT));
            }
            else
            {
                $number = substr_replace($last_section_invoice_no, "", -4).(str_pad((int)(substr($last_section_invoice_no, -4)) + 1, 4, '0', STR_PAD_LEFT));
            }
        }
        else
        {
            if($this->session->userdata("firm_id") == 6)
            {
                $number = "AB-".date("Y")."S-0001";
            }
            elseif($this->session->userdata("firm_id") == 7)
            {
                $number = "AG-".date("Y")."0001";
            }
            elseif($this->session->userdata("firm_id") == 8)
            {
                $number = "SC-".date("Y")."S-0001";
            }
            elseif($this->session->userdata("firm_id") == 9)
            {
                $number = "VC-".date("Y")."-0001";
            }
            elseif($this->session->userdata("firm_id") == 15)
            {
                $number = "SYA-".date("Y")."0001";
            }
            elseif($this->session->userdata("firm_id") == 16)
            {
                $number = "AA-".date("Y")."0001";
            }
            elseif($this->session->userdata("firm_id") == 17)
            {
                $number = "AALLP-".date("Y")."-0001";
            }
            elseif($this->session->userdata("firm_id") == 18 || $this->session->userdata("firm_id") == 26)
            {
                $number = "AAA-".date("Y")."-0001";
            }
            elseif($this->session->userdata("firm_id") == 21)
            {
                $number = "AN-".date("Y")."-0001";
            }
            elseif($this->session->userdata("firm_id") == 23)
            {
                $number = "ACT-".date("Y")."-0001";
            }
            elseif($this->session->userdata("firm_id") == 24)
            {
                $number = "AAASB-".date("y")."-0001";
            }
            elseif($this->session->userdata("firm_id") == 25)
            {
                $number = "AAT-".date("y")."-0001";
            }
            else
            {
                $number = "AAA-".date("Y")."-".str_pad(1,4,"0",STR_PAD_LEFT);
            }
        }

        echo json_encode(array("invoice_no" => $number));
    }

    public function get_recurring_invoice_no()
    {
        $query_invoice_no = $this->db->query("select MAX(CAST(SUBSTRING(invoice_no,10, length(invoice_no)-9) AS UNSIGNED)) as invoice_no from recurring_billing"); //invoice_number excluding the 9 first characters, converts to int, and selects max from it.

        if ($query_invoice_no->num_rows() > 0) 
        {
            $query_invoice_no = $query_invoice_no->result_array();

            $last_section_invoice_no = (int)$query_invoice_no[0]["invoice_no"] + 1;
            $number = "REC-".date("Y")."-".str_pad($last_section_invoice_no,4,"0",STR_PAD_LEFT);

        }
        else
        {
            $number = "REC-".date("Y")."-".str_pad(1,4,"0",STR_PAD_LEFT);
        }

        echo json_encode(array("invoice_no" => $number));
    }

    public function save_recurring()
    {
        $company_code = $_POST["client_name"];
        $billing_date = $_POST["billing_date"];
        $currency = $_POST["currency"];
        $invoice_no = $_POST["invoice_no"];
        $recurring_checkbox = $_POST["hidden_recurring_checkbox"];
        $frequency = $_POST["frequency"];
        if(!isset($_POST["recurring_cancel_date"]))
        {
            $recurring_cancel_date = "";
        }
        else
        {
            $recurring_cancel_date = $_POST["recurring_cancel_date"];
        }
        if(!isset($_POST["recurring_issue_date"]))
        {
            $recurring_issue_date = "";
        }
        else
        {
            $recurring_issue_date = $_POST["recurring_issue_date"];
        }
        $amount = array_values($_POST["amount"]);
        $invoice_description = array_values($_POST["invoice_description"]);
        $service = array_values($_POST["service"]);
        $period_start_date = array_values($_POST["period_start_date"]);
        $period_end_date = array_values($_POST["period_end_date"]);
        $unit_pricing = array_values($_POST["unit_pricing"]);
        $rate = $_POST["rate"];
        $own_letterhead_checkbox = $_POST["hidden_own_letterhead_checkbox"];
        $grand_total = $_POST["grand_total"];
        //$gst_rate = $_POST["gst_rate"];

        $billing_result = $this->db->query("select * from recurring_billing where invoice_date = '".$billing_date."' AND company_code='".$company_code."' AND invoice_no = '".$invoice_no."'");

        $billing_result = $billing_result->result_array();

        if($billing_result)
        {
            $new_amount = (float)str_replace(',', '', $grand_total);
            $new_outstanding = (float)str_replace(',', '', $grand_total) - ($billing_result[0]['amount'] - $billing_result[0]['outstanding']);

            $billing['currency_id'] = $currency;
            $billing['amount'] = $new_amount;
            $billing['rate'] = $rate;
            $billing['own_letterhead_checkbox'] = $own_letterhead_checkbox;
            $billing['outstanding'] = $new_outstanding;
            $billing['recurring_status'] = $recurring_checkbox;
            $billing['recurring_cancel_date'] = $recurring_cancel_date;
            $billing['billing_period'] = $frequency;
            $billing['recu_invoice_issue_date'] = $recurring_issue_date;

            $this->db->delete('recurring_billing_service', array('billing_id' => $billing_result[0]['id']));

            $this->db->update("recurring_billing",$billing,array("id" => $billing_result[0]['id']));
            $this->save_audit_trail("Billings", "Edit Recurring", $_POST["invoice_no"]." receipt is edited.");

            $billing_service['billing_id'] = $billing_result[0]['id'];
        }
        else
        {
            $billing['invoice_no'] = $invoice_no;
            $billing['firm_id'] = $this->session->userdata("firm_id");
            $billing['company_code'] = $company_code;
            $billing['invoice_date'] = $billing_date;
            $billing['rate'] = $rate;
            $billing['own_letterhead_checkbox'] = $own_letterhead_checkbox;
            $billing['recurring_status'] = $recurring_checkbox;
            $billing['recurring_cancel_date'] = $recurring_cancel_date;
            $billing['billing_period'] = $frequency;
            $billing['recu_invoice_issue_date'] = $recurring_issue_date;
            $billing['amount'] = 0;
            $billing['outstanding'] = 0;
            for($p = 0; $p < count($amount); $p++)
            {
                if($_POST["old_gst_rate"] != "false")
                {
                    $gst_rate = $_POST["old_gst_rate"];
                }
                else
                {
                    $gst_rate = $_POST["gst_rate"][$p];
                }

                $billing['amount'] = $billing['amount'] + ((1+($gst_rate / 100)) * (float)str_replace(',', '', $amount[$p]));
                $billing['outstanding'] = $billing['outstanding'] + ((1+($gst_rate / 100)) * (float)str_replace(',', '', $amount[$p]));
            }
            
            $billing['currency_id'] = $currency;

            $this->db->insert("recurring_billing",$billing);
            $billing_service['billing_id'] = $this->db->insert_id();

            $this->save_audit_trail("Billings", "Create Recurring", $_POST["invoice_no"]." recurring is added.");
        }
        for($k = 0; $k < count($amount); $k++)
        {
            $billing_service['invoice_date'] = $billing_date;
            $billing_service['service'] = $service[$k];
            $billing_service['invoice_description'] = $invoice_description[$k];
            $billing_service['amount'] = (float)str_replace(',', '', $amount[$k]);
            $billing_service['unit_pricing'] = $unit_pricing[$k];
            $billing_service['period_start_date'] = $period_start_date[$k];
            $billing_service['period_end_date'] = $period_end_date[$k];
            //$billing_service['gst_rate'] = $gst_rate;
            if($_POST["old_gst_rate"] != "false")
            {
                $billing_service['gst_category_id'] = 0;
                $billing_service['gst_rate'] = $_POST["old_gst_rate"];
                $billing_service['gst_new_way'] = 0;
            }
            else
            {
                $billing_service['gst_category_id'] = $_POST["gst_category_id"][$k];
                $billing_service['gst_rate'] = $_POST["gst_rate"][$k];
                $billing_service['gst_new_way'] = $_POST["gst_new_way"][$k];
            }

            $this->db->insert("recurring_billing_service",$billing_service);
        }
        
        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
    }

    public function save_billing()
    {
        //echo json_encode($_POST);
        if($_POST["client_name"] != "0")
        {
            $company_code = $_POST["client_name"];
        }
        else
        {
            $company_code = $_POST["services_company_code"];
        }
        $billing_date = $_POST["billing_date"];
        $currency = $_POST["currency"];
        $invoice_no = $_POST["invoice_no"];
        $previous_invoice_no = $_POST["previous_invoice_no"];
        $amount = array_values($_POST["amount"]);
        $invoice_description = array_values($_POST["invoice_description"]);
        $service = array_values($_POST["service"]);
        $progress_billing_yes_no = array_values($_POST["progress_billing_yes_no"]);
        $poc_percentage = array_values($_POST["poc_percentage"]);
        $number_of_percent_poc = array_values($_POST["hidden_number_of_percent_poc"]);
        $assignment_yes_no = array_values($_POST["assignment_yes_no"]);
        $assignment = array_values($_POST["assignment"]);
        $radio_quantity_reading = array_values($_POST["radio_quantity_reading"]);
        $reading_at_begin = array_values($_POST["reading_at_begin"]);
        $reading_at_the_end = array_values($_POST["reading_at_the_end"]);
        $number_of_rate = array_values($_POST["number_of_rate"]);
        $unit_for_rate = array_values($_POST["unit_for_rate"]);
        $quantity_value = array_values($_POST["quantity_value"]);
        $period_start_date = array_values($_POST["period_start_date"]);
        $period_end_date = array_values($_POST["period_end_date"]);
        $unit_pricing = array_values($_POST["unit_pricing"]);
        $claim_service_id = array_values($_POST["claim_service_id"]);
        $rate = $_POST["rate"];
        $grand_total = $_POST["grand_total"];
        $arr_for_check_no_assignment = json_decode($_POST["arr_for_check_no_assignment"]);
        //$gst_rate = $_POST["gst_rate"];

        $company_name = $_POST["company_name"];
        $postal_code = $_POST["hidden_postal_code"];
        $street_name = $_POST["hidden_street_name"];
        $building_name = $_POST["hidden_building_name"];
        $unit_no1 = $_POST["hidden_unit_no1"];
        $unit_no2 = $_POST["hidden_unit_no2"];
        $foreign_address1 = $_POST["hidden_foreign_address1"];
        $foreign_address2 = $_POST["hidden_foreign_address2"];
        $foreign_address3 = $_POST["hidden_foreign_address3"];

        $billing_result = $this->db->query("select * from billing where company_code='".$company_code."' AND invoice_no = '".$previous_invoice_no."' AND status = '0'");

        $billing_result = $billing_result->result_array();

        if($billing_result)
        {
            $check_billing_id_result = $this->db->query("select * from billing where invoice_no = '".$invoice_no."' AND status = '0' AND id != '".$billing_result[0]['id']."'");

            $check_billing_id_result = $check_billing_id_result->result_array();

            if(!$check_billing_id_result)
            {
                $new_amount = (float)str_replace(',', '', $grand_total);
                $new_outstanding = (float)str_replace(',', '', $grand_total) - ($billing_result[0]['amount'] - $billing_result[0]['outstanding']);

                $billing['invoice_no'] = $invoice_no;
                $billing['currency_id'] = $currency;
                $billing['amount'] = $new_amount;
                $billing['rate'] = $rate;
                $billing['outstanding'] = $new_outstanding;

                $previous_billing_service_result = $this->db->query("select * from billing_service where billing_id='".$billing_result[0]['id']."'");

                $previous_billing_service_result = $previous_billing_service_result->result_array();
                if($previous_billing_service_result)
                {
                    for($r = 0; $r < count($previous_billing_service_result); $r++)
                    {   
                        if($previous_billing_service_result[$r]["claim_service_id"] != NULL)
                        {    
                            for($t = 0; $t < count(json_decode($previous_billing_service_result[$r]["claim_service_id"])); $t++)
                            {
                                $claim_service['billing_service_id'] = 0;
                                $this->db->update("claim_service",$claim_service,array("id" => json_decode($previous_billing_service_result[$r]["claim_service_id"])[$t]));
                            }
                        }

                        $this->db->delete('payroll_assignment_invoices', array('billing_service_id' => $previous_billing_service_result[$r]['id']));
                    }
                }
                $this->db->delete('billing_service', array('billing_id' => $billing_result[0]['id']));

                $this->db->update("billing",$billing,array("id" => $billing_result[0]['id']));

                $this->save_audit_trail("Billings", "Edit Billing", $invoice_no." invoice is edited.");

                $billing_service['billing_id'] = $billing_result[0]['id'];

                $can_insert_billing_service = true;
            }
            else
            {
                $can_insert_billing_service = false;
            }
        }
        else
        {
            $check_billing_id_result = $this->db->query("select * from billing where invoice_no = '".$invoice_no."' AND status = '0'");

            $check_billing_id_result = $check_billing_id_result->result_array();

            if(!$check_billing_id_result)
            {
                $billing['invoice_no'] = $invoice_no;
                $billing['firm_id'] = $this->session->userdata("firm_id");
                $billing['company_code'] = $company_code;
                $billing['company_name'] = $company_name;
                $billing['postal_code'] = $postal_code;
                $billing['building_name'] = $building_name;
                $billing['street_name'] = $street_name;
                $billing['unit_no1'] = $unit_no1;
                $billing['unit_no2'] = $unit_no2;
                $billing['foreign_address1'] = $foreign_address1;
                $billing['foreign_address2'] = $foreign_address2;
                $billing['foreign_address3'] = $foreign_address3;
                $billing['invoice_date'] = $billing_date;
                $billing['rate'] = $rate;
                $billing['amount'] = 0;
                $billing['outstanding'] = 0;
                for($p = 0; $p < count($amount); $p++)
                {
                    if($_POST["old_gst_rate"] != "false")
                    {
                        $gst_rate = $_POST["old_gst_rate"];
                    }
                    else
                    {
                        $gst_rate = $_POST["gst_rate"][$p];
                    }

                    $billing['amount'] = $billing['amount'] + ((1+($gst_rate / 100)) * (float)str_replace(',', '', $amount[$p]));
                    $billing['outstanding'] = $billing['outstanding'] + ((1+($gst_rate / 100)) * (float)str_replace(',', '', $amount[$p]));
                }
                
                $billing['currency_id'] = $currency;

                $this->db->insert("billing",$billing);
                $billing_service['billing_id'] = $this->db->insert_id();

                $this->save_audit_trail("Billings", "Create Billing", $invoice_no." invoice is added."); 

                $can_insert_billing_service = true;
            }
            else
            {
                $can_insert_billing_service = false;
            }
        }

        if($can_insert_billing_service)
        {
            for($k = 0; $k < count($amount); $k++)
            {
                $billing_service['invoice_date'] = $billing_date;
                $billing_service['service'] = $service[$k];
                $billing_service['invoice_description'] = $invoice_description[$k];
                $billing_service['progress_billing_yes_no'] = $progress_billing_yes_no[$k];
                if($poc_percentage[$k] != "")
                {
                    $billing_service['poc_percentage'] = $poc_percentage[$k];
                }
                else
                {
                    $billing_service['poc_percentage'] = NULL;
                }

                if($number_of_percent_poc[$k] != "")
                {
                    $billing_service['number_of_percent_poc'] = $number_of_percent_poc[$k];
                }
                else
                {
                    $billing_service['number_of_percent_poc'] = NULL;
                }

                $billing_service['radio_quantity_reading'] = $radio_quantity_reading[$k];

                if($reading_at_begin[$k] != "")
                {
                    $billing_service['reading_at_begin'] = $reading_at_begin[$k];
                }
                else
                {
                    $billing_service['reading_at_begin'] = NULL;
                }

                if($reading_at_the_end[$k] != "")
                {
                    $billing_service['reading_at_the_end'] = $reading_at_the_end[$k];
                }
                else
                {
                    $billing_service['reading_at_the_end'] = NULL;
                }

                if($number_of_rate[$k] != "")
                {
                    $billing_service['number_of_rate'] = $number_of_rate[$k];
                }
                else
                {
                    $billing_service['number_of_rate'] = NULL;
                }

                if($unit_for_rate[$k] != "")
                {
                    $billing_service['unit_for_rate'] = $unit_for_rate[$k];
                }
                else
                {
                    $billing_service['unit_for_rate'] = NULL;
                }

                if($quantity_value[$k] != "")
                {
                    $billing_service['quantity_value'] = $quantity_value[$k];
                }
                else
                {
                    $billing_service['quantity_value'] = NULL;
                }

                $billing_service['amount'] = (float)str_replace(',', '', $amount[$k]);
                $billing_service['unit_pricing'] = $unit_pricing[$k];
                $billing_service['period_start_date'] = $period_start_date[$k];
                $billing_service['period_end_date'] = $period_end_date[$k];
                $billing_service['claim_service_id'] = $claim_service_id[$k];

                if($_POST["old_gst_rate"] != "false")
                {
                    $billing_service['gst_category_id'] = 0;
                    $billing_service['gst_rate'] = $_POST["old_gst_rate"];
                    $billing_service['gst_new_way'] = 0;
                }
                else
                {
                    $billing_service['gst_category_id'] = $_POST["gst_category_id"][$k];
                    $billing_service['gst_rate'] = $_POST["gst_rate"][$k];
                    $billing_service['gst_new_way'] = $_POST["gst_new_way"][$k];
                }

                $this->db->insert("billing_service",$billing_service);
                $billing_service_id = $this->db->insert_id();

                if($assignment_yes_no[$k] == "yes")
                {
                    $payroll_assignment_invoices["assignment_id"] = $assignment[$k];
                    $payroll_assignment_invoices["billing_service_id"] = $billing_service_id;

                    $this->db->insert("payroll_assignment_invoices",$payroll_assignment_invoices);

                    $this->check_assingment_billings_invoice_no($assignment);
                }
                else
                {
                    $this->check_assingment_billings_invoice_no($arr_for_check_no_assignment);
                }

                if($claim_service_id[$k] != "")
                {
                    for($t = 0; $t < count(json_decode($claim_service_id[$k])); $t++)
                    {
                        $claim_service['billing_service_id'] = $billing_service_id;
                        $this->db->update("claim_service",$claim_service,array("id" => json_decode($claim_service_id[$k])[$t]));
                    }
                }
            }
            
            if($this->session->userdata('qb_company_id') != "")
            {
                $check_is_come_from_services = $this->db->query('select transaction_master_with_billing.*, transaction_master.transaction_task_id from transaction_master_with_billing left join transaction_master on transaction_master.id =  transaction_master_with_billing.transaction_master_id where billing_id = "'.$billing_service['billing_id'].'"');

                if ($check_is_come_from_services->num_rows() > 0) 
                {
                    $check_is_come_from_services = $check_is_come_from_services->result_array();

                    if($check_is_come_from_services[0]["transaction_task_id"] != "1")
                    {
                        $qb_customer_id = ", client_qb_id.qb_customer_id";
                        $left_join_client = " LEFT JOIN client ON client.company_code = billing.company_code LEFT JOIN client_qb_id ON client_qb_id.company_code = billing.company_code AND client_qb_id.currency_name = currency.currency AND client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";

                        if($check_is_come_from_services[0]["transaction_task_id"] == "4" || $check_is_come_from_services[0]["transaction_task_id"] == "33" || $check_is_come_from_services[0]["transaction_task_id"] == "34")
                        {
                            $left_join_client = $left_join_client." LEFT JOIN transaction_client_billing_info ON transaction_client_billing_info.id = billing_service.service 
                                LEFT JOIN our_service_info ON our_service_info.id = transaction_client_billing_info.service 
                                LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";
                        }
                        else
                        {
                            $left_join_client = $left_join_client." LEFT JOIN our_service_info ON our_service_info.id = billing_service.service LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";
                        }
                    }
                    else
                    {
                        $qb_customer_id = ", transaction_client_qb_id.qb_customer_id";
                        $left_join_client = " LEFT JOIN transaction_client ON transaction_client.company_code = billing.company_code 
                        LEFT JOIN transaction_client_billing_info ON transaction_client_billing_info.id = billing_service.service 
                        LEFT JOIN our_service_info ON our_service_info.id = transaction_client_billing_info.service
                        LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."'
                        LEFT JOIN transaction_client_qb_id ON transaction_client_qb_id.company_code = billing.company_code AND transaction_client_qb_id.currency_name = currency.currency AND transaction_client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";
                    }

                    $billing_info = $this->db->query("SELECT billing.*, billing_service.*, billing_service.id as billing_service_id, billing_service.amount as billing_service_amount, our_service_qb_info.qb_item_id, our_service_info.service_name, gst_category.category as gst_category_name, currency.currency as currency_name".$qb_customer_id."
                        FROM billing 
                        LEFT JOIN billing_service ON billing_service.billing_id = billing.id 
                        LEFT JOIN currency ON currency.id = billing.currency_id
                        ".$left_join_client."
                        LEFT JOIN gst_category ON gst_category.id = billing_service.gst_category_id 
                        WHERE billing.id = '".$billing_service['billing_id']."' ORDER BY billing_service.id");
                }
                else
                {
                    $billing_info = $this->db->query("SELECT billing.*, billing_service.*, billing_service.id as billing_service_id, billing_service.amount as billing_service_amount, client_qb_id.qb_customer_id, our_service_qb_info.qb_item_id, our_service_info.service_name, currency.currency as currency_name, gst_category.category as gst_category_name FROM billing 
                        LEFT JOIN billing_service ON billing_service.billing_id = billing.id 
                        LEFT JOIN    ON client.company_code = billing.company_code 
                        LEFT JOIN client_billing_info ON client_billing_info.id = billing_service.service 
                        LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service 
                        LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."'
                        LEFT JOIN currency ON currency.id = billing.currency_id 
                        LEFT JOIN gst_category ON gst_category.id = billing_service.gst_category_id 
                        LEFT JOIN client_qb_id ON client_qb_id.company_code = billing.company_code AND client_qb_id.currency_name = currency.currency AND client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."'
                        WHERE billing.id = '".$billing_service['billing_id']."' ORDER BY billing_service.id");
                }

                $billing_info_array = $billing_info->result_array(); 
                $can_import_to_qb = true;
                $service_not_in_qb = "";

                for($k = 0; $k < count($billing_info_array); $k++)
                {
                    if($billing_info_array[$k]["qb_item_id"] == 0)
                    {
                        $service_not_in_qb = $billing_info_array[$k]["service_name"];
                        $can_import_to_qb = false;
                        break;
                    }
                }

                if($can_import_to_qb)
                {
                    $billing_submit_status = $this->import_invoice_to_qb($billing_service['billing_id'], $billing_info_array);
                    echo json_encode($billing_submit_status);
                }
                else
                {
                    echo json_encode(array("Status" => 3, 'message' => 'This invoice cannot be import to Quickbook Online because ' . $service_not_in_qb .' services is not in Quickbook Online.', 'title' => 'Warning'));
                }
            }
            else
            {
                echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
            }
        }
        else
        {
            echo json_encode(array("Status" => 2, 'message' => 'This Invoice No is already use.', 'title' => 'Warning'));
        }
    }

    public function import_invoice_to_qb($billing_id, $billing_info_array)
    {
        $check_gst_status_query = $this->db->query("SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox, gst_jurisdiction.jurisdiction as jurisdiction_name FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id LEFT JOIN gst_jurisdiction ON gst_jurisdiction.id = firm.jurisdiction_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = '".$this->session->userdata("firm_id")."'");
        
        // print_r("SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox, gst_jurisdiction.jurisdiction as jurisdiction_name FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id LEFT JOIN gst_jurisdiction ON gst_jurisdiction.id = firm.jurisdiction_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = '".$this->session->userdata("firm_id")."'"); exit;
        // print_r($check_gst_status_query); exit;
        // echo "SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox, gst_jurisdiction.jurisdiction as jurisdiction_name FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id LEFT JOIN gst_jurisdiction ON gst_jurisdiction.id = firm.jurisdiction_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = '".$this->session->userdata("firm_id")."'";
        // echo "\n\t\n";

        $firm_info = $this->db->query("SELECT firm.*, currency.currency as currency_name FROM firm LEFT JOIN currency ON currency.id = firm.firm_currency WHERE firm.id = '".$this->session->userdata("firm_id")."'");
        $firm_info_arr = $firm_info->result_array();

        $line_array = [];
        $billing_services_id = [];
        $sub_total = 0;
        $gst = 0;        
        
        for($k = 0; $k < count($billing_info_array); $k++)
        {
            if ($check_gst_status_query->num_rows() > 0) 
            {
                $tax_code_ref = $this->query_qb_tax_code($billing_info_array[$k]["gst_category_name"]);
                
                $line_info = array("Amount" => $billing_info_array[$k]["billing_service_amount"], "Description" => $billing_info_array[$k]["invoice_description"], "DetailType" => "SalesItemLineDetail", "SalesItemLineDetail" => ["TaxCodeRef" => ["value" => $tax_code_ref], "ItemRef" => ["value" => $billing_info_array[$k]["qb_item_id"]]]);
            }
            else
            {
                $line_info = array("Amount" => $billing_info_array[$k]["billing_service_amount"], "Description" => $billing_info_array[$k]["invoice_description"], "DetailType" => "SalesItemLineDetail", "SalesItemLineDetail" => ["ItemRef" => ["value" => $billing_info_array[$k]["qb_item_id"]]]);
            }

            $sub_total += (float)$billing_info_array[$k]["billing_service_amount"]; echo "  ";
            $gst += round((($billing_info_array[$k]['gst_rate'] / 100) * (float)$billing_info_array[$k]['billing_service_amount']), 2); echo "  ";

            array_push($line_array, $line_info);
            array_push($billing_services_id, $billing_info_array[$k]["billing_service_id"]);
        }
        $total = $sub_total + $gst;

        $format_invoice_date = explode('/', $billing_info_array[0]["invoice_date"]);
        $year = $format_invoice_date[2];
        $month = $format_invoice_date[1];
        $day = $format_invoice_date[0];

        $new_format_txn_date = $year.'/'.$month.'/'.$day;
        $new_format_due_date = date('Y/m/d', strtotime("+1 day", strtotime($new_format_txn_date)));

        if ($check_gst_status_query->num_rows() > 0) 
        {
            if($firm_info_arr[0]["currency_name"] != $billing_info_array[0]["currency_name"])
            {
                $converted_total = $total * $billing_info_array[0]["rate"];
                $invoice_info = [
                                    "Line" => $line_array,
                                    "DocNumber" => $billing_info_array[0]["invoice_no"],
                                    "TxnDate" => $new_format_txn_date,
                                    "DueDate" => $new_format_due_date,
                                    "CustomerRef"=> [
                                          "value"=>  $billing_info_array[0]["qb_customer_id"]
                                    ],
                                    "CurrencyRef"=> [
                                        "value" => $billing_info_array[0]["currency_name"]
                                    ],
                                    "ExchangeRate" => $billing_info_array[0]["rate"],
                                    "HomeTotalAmt" => $converted_total,
                                    "TxnTaxDetail" => [
                                        "TotalTax" => $gst
                                    ]
                                ];
            }
            else
            {
                $invoice_info = [
                                "Line" => $line_array,
                                "DocNumber" => $billing_info_array[0]["invoice_no"],
                                "TxnDate" => $new_format_txn_date,
                                "DueDate" => $new_format_due_date,
                                "CustomerRef"=> [
                                      "value"=>  $billing_info_array[0]["qb_customer_id"]
                                ],
                                "CurrencyRef"=> [
                                    "value" => $billing_info_array[0]["currency_name"]
                                ],
                                "TxnTaxDetail" => [
                                    "TotalTax" => $gst
                                ]
                            ];
            }
        }
        else
        {
            if($firm_info_arr[0]["currency_name"] != $billing_info_array[0]["currency_name"])
            {
                $invoice_info = [
                                    "Line" => $line_array,
                                    "DocNumber" => $billing_info_array[0]["invoice_no"],
                                    "TxnDate" => $new_format_txn_date,
                                    "DueDate" => $new_format_due_date,
                                    "CustomerRef"=> [
                                          "value"=>  $billing_info_array[0]["qb_customer_id"]
                                    ],
                                    "CurrencyRef"=> [
                                        "value" => $billing_info_array[0]["currency_name"]
                                    ],
                                    "ExchangeRate" => $billing_info_array[0]["rate"]
                                ];
            }
            else
            {
                $invoice_info = [
                                    "Line" => $line_array,
                                    "DocNumber" => $billing_info_array[0]["invoice_no"],
                                    "TxnDate" => $new_format_txn_date,
                                    "DueDate" => $new_format_due_date,
                                    "CustomerRef"=> [
                                          "value"=>  $billing_info_array[0]["qb_customer_id"]
                                    ],
                                    "CurrencyRef"=> [
                                        "value" => $billing_info_array[0]["currency_name"]
                                    ]
                                ];
            }
        }

        // print_r($billing_info_array); exit;

        if($billing_info_array[0]["invoice_no"] != null && $billing_info_array[0]["qb_customer_id"] != null && $billing_info_array[0]["currency_name"] != null && $billing_info_array[0]["qb_customer_id"] != 0)
        {
            if($this->session->userdata('refresh_token_value'))
            {
                // try {
                    // Prep Data Services
                    $dataService = DataService::Configure(array(
                         'auth_mode' => 'oauth2',
                         'ClientID' => $this->quickbook_clientID,
                         'ClientSecret' => $this->quickbook_clientSecret,
                         'accessTokenKey' => $this->session->userdata('access_token_value'),
                         'refreshTokenKey' => $this->session->userdata('refresh_token_value'),
                         'QBORealmID' => $this->session->userdata('qb_company_id'), //"The Company ID which the app wants to access"
                         'baseUrl' => $this->quickbookBaseUrl,
                    ));
                    
                    $dataService->throwExceptionOnError(true);

                    if($billing_info_array[0]["qb_invoice_id"] != 0)
                    {
                        $invoice = $dataService->FindbyId('invoice', $billing_info_array[0]["qb_invoice_id"]);

                        $theResourceObj = Invoice::update($invoice, $invoice_info);

                        $resultingObj = $dataService->Update($theResourceObj);
                    }
                    else
                    {
                        // print_r($invoice_info); exit;
                        $theResourceObj = Invoice::create($invoice_info);
                        // print_r($theResourceObj); exit;
                        $resultingObj = $dataService->Add($theResourceObj);
                    }
                    
                    $error = $dataService->getLastError();

                    

                    // print_r("resultingObj"); 
                    // print_r($resultingObj); exit;

                    if ($error) {
                        print_r("error"); echo "                                   ";
                        print_r($error);
                        exit;
                        if($error->getHttpStatusCode() == "401")
                        {
                            $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                            if($refresh_token_status)
                            {
                                $this->import_invoice_to_qb($billing_id, $billing_info_array);
                            }
                        }
                        else
                        {
                            return array("Status" => 2, 'message' => $error->getIntuitErrorMessage(), 'title' => 'Error');
                        }
                    }
                    else {
                        $invoice_data["qb_invoice_id"] = $resultingObj->Id;
                        $invoice_data["qb_invoice_json"] = json_encode($resultingObj);
                        $this->db->update("billing", $invoice_data, array("id" => $billing_id));

                        $salesItemLineReturnArr = $resultingObj->Line;

                        for($h = 0; $h < count($salesItemLineReturnArr); $h++)
                        {
                            if($salesItemLineReturnArr[$h]->Id)
                            {
                                $invoice_services_data["qb_invoice_services_id"] = $salesItemLineReturnArr[$h]->Id;
                                $this->db->update("billing_service", $invoice_services_data, array("id" => $billing_services_id[$h]));
                            }
                        }

                        $this->save_audit_trail("Billings", "QuickBooks", $this->session->userdata('first_name'). " " . $this->session->userdata('last_name') . " import ".$billing_info_array[0]["invoice_no"]." invoice to QuickBooks Online.");

                        return array("Status" => 1, 'message' => "Information Updated", 'title' => 'Success');
                    }
                // }
                // catch (Exception $e){
                //     print_r("catch error"); echo "                                   ";
                //     print_r($e);
                //     exit;
                //     $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                //     if($refresh_token_status)
                //     {
                //         $this->import_invoice_to_qb($billing_id, $billing_info_array);
                //     }
                // }
            }
            else
            {
                return array("Status" => 2, 'message' => 'Please login to Quickbook Online to save this invoice to Quickbook Online.', 'title' => 'Warning');
            }
        }
        else
        {
            if($billing_info_array[0]["invoice_no"] == null)
            {
                $missing_name = $billing_info_array[0]["invoice_no"];
            }
            else if($billing_info_array[0]["qb_customer_id"] == null || $billing_info_array[0]["qb_customer_id"] == 0)
            {
                $missing_name = $billing_info_array[0]["company_name"] . " (".$billing_info_array[0]["currency_name"].")";
            }
            else if($billing_info_array[0]["currency_name"] == null)
            {
                $missing_name = $billing_info_array[0]["currency_name"];
            }

            return array("Status" => 4, 'message' => 'This invoice cannot be import to Quickbook Online because ' . $missing_name .' is not in Quickbook Online.', 'title' => 'Warning');
        }
    }

    public function import_all_invoice_to_qb()
    {
        $start = $_POST["import_start_date"];
        $end = $_POST["import_end_date"];

        if ($start != NULL)
        {
            if ($end != NULL)
            {
                $date_filter = 'AND STR_TO_DATE(billing.invoice_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")';

                $check_gst_status_query = $this->db->query("SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox, gst_jurisdiction.jurisdiction as jurisdiction_name FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id LEFT JOIN gst_jurisdiction ON gst_jurisdiction.id = firm.jurisdiction_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = '".$this->session->userdata("firm_id")."'");

                $firm_info = $this->db->query("SELECT firm.*, currency.currency as currency_name FROM firm LEFT JOIN currency ON currency.id = firm.firm_currency WHERE firm.id = '".$this->session->userdata("firm_id")."'");
                $firm_info_arr = $firm_info->result_array();

                $billing_data = $this->db->query("SELECT billing.* FROM billing WHERE billing.firm_id = ".$this->session->userdata("firm_id")." AND billing.qb_invoice_id = 0 AND billing.status = 0 ".$date_filter." LIMIT 10");// LIMIT 2

                if ($billing_data->num_rows())
                {
                    $billing_data_array = $billing_data->result_array();
                    $success = true;

                    for($p = 0; $p < count($billing_data_array); $p++)
                    {
                        $check_is_come_from_services_list = $this->db->query('select transaction_master_with_billing.*, transaction_master.transaction_task_id from transaction_master_with_billing left join transaction_master on transaction_master.id =  transaction_master_with_billing.transaction_master_id where billing_id = "'.$billing_data_array[$p]['id'].'"');

                        if ($check_is_come_from_services_list->num_rows() > 0) 
                        {
                            $check_is_come_from_services = $check_is_come_from_services_list->result_array();

                            if($check_is_come_from_services[0]["transaction_task_id"] != "1")
                            {
                                $qb_customer_id = ", client_qb_id.qb_customer_id";
                                $left_join_client = " LEFT JOIN client ON client.company_code = billing.company_code LEFT JOIN client_qb_id ON client_qb_id.company_code = billing.company_code AND client_qb_id.currency_name = currency.currency AND client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";

                                if($check_is_come_from_services[0]["transaction_task_id"] == "4" || $check_is_come_from_services[0]["transaction_task_id"] == "33" || $check_is_come_from_services[0]["transaction_task_id"] == "34")
                                {
                                    $left_join_client = $left_join_client." LEFT JOIN transaction_client_billing_info ON transaction_client_billing_info.id = billing_service.service 
                                        LEFT JOIN our_service_info ON our_service_info.id = transaction_client_billing_info.service 
                                        LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";
                                }
                                else
                                {
                                    $left_join_client = $left_join_client." LEFT JOIN our_service_info ON our_service_info.id = billing_service.service LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";
                                }
                            }
                            else
                            {
                                $qb_customer_id = ", transaction_client_qb_id.qb_customer_id";
                                $left_join_client = " LEFT JOIN transaction_client ON transaction_client.company_code = billing.company_code 
                                LEFT JOIN transaction_client_billing_info ON transaction_client_billing_info.id = billing_service.service 
                                LEFT JOIN our_service_info ON our_service_info.id = transaction_client_billing_info.service
                                LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."'
                                LEFT JOIN transaction_client_qb_id ON transaction_client_qb_id.company_code = billing.company_code AND transaction_client_qb_id.currency_name = currency.currency AND transaction_client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";
                            }

                            $billing_service_info = $this->db->query("SELECT billing.*, billing_service.*, billing_service.id as billing_service_id, billing_service.amount as billing_service_amount, our_service_qb_info.qb_item_id, our_service_info.service_name, gst_category.category as gst_category_name, currency.currency as currency_name".$qb_customer_id."
                                FROM billing 
                                LEFT JOIN billing_service ON billing_service.billing_id = billing.id 
                                LEFT JOIN currency ON currency.id = billing.currency_id
                                ".$left_join_client."
                                LEFT JOIN gst_category ON gst_category.id = billing_service.gst_category_id 
                                WHERE billing.id = '".$billing_data_array[$p]['id']."' ORDER BY billing_service.id");
                        }
                        else
                        {
                            $billing_service_info = $this->db->query("SELECT billing.*, billing_service.*, billing_service.id as billing_service_id, billing_service.amount as billing_service_amount, client_qb_id.qb_customer_id, our_service_qb_info.qb_item_id, our_service_info.service_name, currency.currency as currency_name, gst_category.category as gst_category_name FROM billing 
                                LEFT JOIN billing_service ON billing_service.billing_id = billing.id 
                                LEFT JOIN client ON client.company_code = billing.company_code 
                                LEFT JOIN client_billing_info ON client_billing_info.id = billing_service.service 
                                LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service 
                                LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."'
                                LEFT JOIN currency ON currency.id = billing.currency_id 
                                LEFT JOIN gst_category ON gst_category.id = billing_service.gst_category_id 
                                LEFT JOIN client_qb_id ON client_qb_id.company_code = billing.company_code AND client_qb_id.currency_name = currency.currency AND client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."' 
                                WHERE billing.id = '".$billing_data_array[$p]['id']."' ORDER BY billing_service.id");
                        }
                        
                        $billing_service_info_array = $billing_service_info->result_array(); 
                        if ($check_is_come_from_services_list->num_rows() > 0) 
                        {
                            if($check_is_come_from_services[0]["transaction_task_id"] == "1")
                            {
                                if($billing_service_info_array[0]["qb_customer_id"] == null || $billing_service_info_array[0]["qb_customer_id"] == 0)
                                {
                                    $billing_service_info_array[0]["qb_customer_id"] = $billing_service_info_array[0]["client_qb_customer_id"];
                                }
                            }
                        }

                        $can_import_to_qb = true;
                        $service_not_in_qb = "";
                        $billing_id = $billing_data_array[$p]['id'];

                        for($k = 0; $k < count($billing_service_info_array); $k++)
                        {
                            if($billing_service_info_array[$k]["qb_item_id"] == 0)
                            {
                                $invoice_no_not_in_qb = $billing_service_info_array[$k]["invoice_no"];
                                $service_not_in_qb = $billing_service_info_array[$k]["service_name"];
                                $can_import_to_qb = false;
                                break;
                            }
                        }

                        if($can_import_to_qb)
                        {
                            $line_array = [];
                            $billing_services_id = [];
                            $sub_total = 0;
                            $gst = 0;

                            for($k = 0; $k < count($billing_service_info_array); $k++)
                            {
                                $tax_code_ref = $this->query_qb_tax_code($billing_service_info_array[$k]["gst_category_name"]);

                                $line_info = array("Amount" => $billing_service_info_array[$k]["billing_service_amount"], "Description" => $billing_service_info_array[$k]["invoice_description"], "DetailType" => "SalesItemLineDetail", "SalesItemLineDetail" => ["TaxCodeRef" => ["value" => $tax_code_ref], "ItemRef" => ["value" => $billing_service_info_array[$k]["qb_item_id"]]]);

                                $sub_total += (float)$billing_service_info_array[$k]["billing_service_amount"];
                                $gst += round((($billing_service_info_array[$k]['gst_rate'] / 100) * (float)$billing_service_info_array[$k]['billing_service_amount']), 2);

                                array_push($line_array, $line_info);
                                array_push($billing_services_id, $billing_service_info_array[$k]["billing_service_id"]);
                            }
                            $total = $sub_total + $gst;

                            $format_invoice_date = explode('/', $billing_service_info_array[0]["invoice_date"]);
                            $year = $format_invoice_date[2];
                            $month = $format_invoice_date[1];
                            $day = $format_invoice_date[0];

                            $new_format_txn_date = $year.'/'.$month.'/'.$day;
                            $new_format_due_date = $new_format_due_date = date('Y/m/d', strtotime("+1 day", strtotime($new_format_txn_date)));

                            if($this->session->userdata('refresh_token_value'))
                            {
                                if ($check_gst_status_query->num_rows() > 0) 
                                {
                                    if($firm_info_arr[0]["currency_name"] != $billing_service_info_array[0]["currency_name"])
                                    {
                                        $converted_total = $total * $billing_service_info_array[0]["rate"];
                                        $invoice_info = [
                                                            "Line" => $line_array,
                                                            "DocNumber" => $billing_service_info_array[0]["invoice_no"],
                                                            "TxnDate" => $new_format_txn_date,
                                                            "DueDate" => $new_format_due_date,
                                                            "CustomerRef"=> [
                                                                "value"=>  $billing_service_info_array[0]["qb_customer_id"]
                                                            ],
                                                            "CurrencyRef"=> [
                                                                "value" => $billing_service_info_array[0]["currency_name"]
                                                            ],
                                                            "ExchangeRate" => $billing_service_info_array[0]["rate"],
                                                            "HomeTotalAmt" => $converted_total,
                                                            "TxnTaxDetail" => [
                                                                "TotalTax" => $gst
                                                            ]
                                                        ];
                                    }
                                    else
                                    {
                                        $invoice_info = [
                                                "Line" => $line_array,
                                                "DocNumber" => $billing_service_info_array[0]["invoice_no"],
                                                "TxnDate" => $new_format_txn_date,
                                                "DueDate" => $new_format_due_date,
                                                "CustomerRef"=> [
                                                    "value"=>  $billing_service_info_array[0]["qb_customer_id"]
                                                ],
                                                "CurrencyRef"=> [
                                                    "value" => $billing_service_info_array[0]["currency_name"]
                                                ],
                                                "TxnTaxDetail" => [
                                                    "TotalTax" => $gst
                                                ]
                                        ];
                                    }
                                }
                                else
                                {
                                    if($firm_info_arr[0]["currency_name"] != $billing_service_info_array[0]["currency_name"])
                                    {
                                        $invoice_info = [
                                                            "Line" => $line_array,
                                                            "DocNumber" => $billing_service_info_array[0]["invoice_no"],
                                                            "TxnDate" => $new_format_txn_date,
                                                            "DueDate" => $new_format_due_date,
                                                            "CustomerRef"=> [
                                                                  "value"=>  $billing_service_info_array[0]["qb_customer_id"]
                                                            ],
                                                            "CurrencyRef"=> [
                                                                "value" => $billing_service_info_array[0]["currency_name"]
                                                            ],
                                                            "ExchangeRate" => $billing_info_array[0]["rate"]
                                                        ];
                                    }
                                    else
                                    {
                                        $invoice_info = [
                                                            "Line" => $line_array,
                                                            "DocNumber" => $billing_service_info_array[0]["invoice_no"],
                                                            "TxnDate" => $new_format_txn_date,
                                                            "DueDate" => $new_format_due_date,
                                                            "CustomerRef"=> [
                                                                  "value"=>  $billing_service_info_array[0]["qb_customer_id"]
                                                            ],
                                                            "CurrencyRef"=> [
                                                                "value" => $billing_service_info_array[0]["currency_name"]
                                                            ]
                                                        ];
                                    }
                                }
                                //print_r($invoice_info);
                                try {
                                    if($billing_service_info_array[0]["invoice_no"] != null && $billing_service_info_array[0]["qb_customer_id"] != null && $billing_service_info_array[0]["currency_name"] != null && $billing_service_info_array[0]["qb_customer_id"] != 0)
                                    {
                                        // Prep Data Services
                                        $dataService = DataService::Configure(array(
                                             'auth_mode' => 'oauth2',
                                             'ClientID' => $this->quickbook_clientID,
                                             'ClientSecret' => $this->quickbook_clientSecret,
                                             'accessTokenKey' => $this->session->userdata('access_token_value'),
                                             'refreshTokenKey' => $this->session->userdata('refresh_token_value'),
                                             'QBORealmID' => $this->session->userdata('qb_company_id'), //"The Company ID which the app wants to access"
                                             'baseUrl' => $this->quickbookBaseUrl, //$this->quickbookBaseUrl
                                        ));

                                        $dataService->throwExceptionOnError(true);

                                        if($billing_service_info_array[0]["qb_invoice_id"] != 0)
                                        {
                                            $invoice = $dataService->FindbyId('invoice', $billing_service_info_array[0]["qb_invoice_id"]);

                                            $theResourceObj = Invoice::update($invoice, $invoice_info);

                                            $resultingObj = $dataService->Update($theResourceObj);
                                        }
                                        else
                                        {
                                            $theResourceObj = Invoice::create($invoice_info);

                                            $resultingObj = $dataService->Add($theResourceObj);
                                        }
                                        
                                        $error = $dataService->getLastError();

                                        if ($error) 
                                        {
                                            if($error->getHttpStatusCode() == "401")
                                            {
                                                $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                                                if($refresh_token_status)
                                                {
                                                    $this->import_all_invoice_to_qb();
                                                }
                                            }
                                            else
                                            {
                                                $success = false;
                                                echo json_encode(array("Status" => 3, 'message' => $error->getIntuitErrorMessage(), 'title' => 'Error'));
                                            }
                                        }
                                        else 
                                        {
                                            $invoice_data["qb_invoice_id"] = $resultingObj->Id;
                                            $invoice_data["qb_invoice_json"] = json_encode($resultingObj);
                                            $this->db->update("billing", $invoice_data, array("id" => $billing_id));
                                            $salesItemLineReturnArr = $resultingObj->Line;

                                            for($h = 0; $h < count($salesItemLineReturnArr); $h++)
                                            {
                                                if($salesItemLineReturnArr[$h]->Id)
                                                {
                                                    $invoice_services_data["qb_invoice_services_id"] = $salesItemLineReturnArr[$h]->Id;
                                                    $this->db->update("billing_service", $invoice_services_data, array("id" => $billing_services_id[$h]));
                                                }
                                            }
                                        }
                                    }
                                    else
                                    {
                                        if($billing_service_info_array[0]["invoice_no"] == null)
                                        {
                                            $missing_name = $billing_service_info_array[0]["invoice_no"];
                                        }
                                        else if($billing_service_info_array[0]["qb_customer_id"] == null || $billing_service_info_array[0]["qb_customer_id"] == 0)
                                        {
                                            $missing_name = $billing_service_info_array[0]["company_name"] . " (".$billing_service_info_array[0]["currency_name"].")";
                                        }
                                        else if($billing_service_info_array[0]["currency_name"] == null)
                                        {
                                            $missing_name = $billing_service_info_array[0]["currency_name"];
                                        }
                                        $success = false;
                                        echo json_encode(array("Status" => 5, 'message' => 'This invoice cannot be import to Quickbook Online because ' . $missing_name .' is not in Quickbook Online.', 'title' => 'Warning'));
                                        break;
                                    }
                                }
                                catch (Exception $e)
                                {
                                    $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                                    if($refresh_token_status)
                                    {
                                        $this->import_all_invoice_to_qb();
                                    }
                                }
                            }
                            else
                            {
                                $success = false;
                                echo json_encode(array("Status" => 2, 'message' => 'Please login to Quickbook Online to save this invoice to Quickbook Online.', 'title' => 'Warning'));
                            }
                        }
                        else
                        {
                            $success = false;
                            echo json_encode(array("Status" => 6, 'message' => 'This invoice cannot be import to Quickbook Online because ' . $invoice_no_not_in_qb . '(' . $service_not_in_qb .') services is not in Quickbook Online.', 'title' => 'Warning'));
                            break;
                        }
                    }

                    if($success == true)
                    {
                        $this->save_audit_trail("Billings", "QuickBooks", $this->session->userdata('first_name'). " " . $this->session->userdata('last_name') . " import 10 invoices to QuickBooks Online.");

                        echo json_encode(array("Status" => 1, 'message' => "Information Updated", 'title' => 'Success'));
                    }
                }
                else
                {
                    echo json_encode(array("Status" => 2, 'message' => 'Nothing can import to Quickbook Online.', 'title' => 'Warning'));
                }
            }
            else
            {
                echo json_encode(array("Status" => 3, 'message' => 'Please select one End Date.', 'title' => 'Error'));
            }
        }
        else
        {
            echo json_encode(array("Status" => 3, 'message' => 'Please select one Start Date.', 'title' => 'Error'));
        }
    }

    public function import_all_receipt_to_qb()
    {
        $start = $_POST["import_start_date"];
        $end = $_POST["import_end_date"];

        if ($start != NULL)
        {
            if ($end != NULL)
            {
                $date_filter = 'AND STR_TO_DATE(receipt.receipt_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")';

                $receipt_data = $this->db->query("SELECT receipt.id, receipt.receipt_no, bb.company_name, group_concat(bb.invoice_date) as invoice_date, bb.qb_invoice_id, group_concat(bb.qb_invoice_id) as arr_qb_invoice_id, receipt_qb_record.id as receipt_qb_record_id FROM receipt 
                        LEFT JOIN billing_receipt_record ON billing_receipt_record.receipt_id = receipt.id 
                        LEFT JOIN (SELECT id, company_name, invoice_date, qb_invoice_id FROM billing ORDER BY billing.id DESC) AS bb ON bb.id = billing_receipt_record.billing_id
                        LEFT JOIN currency ON receipt.currency_total_amount_received = currency.id 
                        LEFT JOIN receipt_qb_record ON receipt_qb_record.receipt_id = receipt.id AND receipt_qb_record.currency_name = currency.currency
                        WHERE billing_receipt_record.firm_id = ".$this->session->userdata("firm_id")." AND receipt_qb_record.qb_receipt_id IS NULL ".$date_filter."
                        GROUP BY receipt.id LIMIT 10"); // AND billing.qb_invoice_id != 0 // HAVING arr_qb_invoice_id NOT LIKE '%0%'
                //print_r($receipt_data->result_array());
                if ($receipt_data->num_rows())
                {
                    $receipt_data_array = $receipt_data->result_array();
                    for($i = 0; $i < count($receipt_data_array); $i++)
                    {
                        $success = true;
                        $receipt_id = $receipt_data_array[$i]["id"];
                        $receipt_query = $this->retrieve_receipt_for_qb($receipt_data_array[$i]["id"]);
                        $firm_info = $this->db->query("SELECT firm.*, currency.currency as currency_name FROM firm LEFT JOIN currency ON currency.id = firm.firm_currency WHERE firm.id = '".$this->session->userdata("firm_id")."'");
                        $firm_info_arr = $firm_info->result_array();
                        
                        if ($receipt_query->num_rows() > 0) 
                        {
                            $receipt_array = $receipt_query->result_array();
                            $gotInvoiceNo = true;
                            $check_currency_arr = [];
                            $current_currency = "";
                            $current_qb_client = "";
                            $can_import = false;
                            $curdate = strtotime('01-01-2021');
                            

                            for($p = 0; $p < count($receipt_array); $p++)
                            {
                                $line_array = [];
                                $total_receipt_amount = 0;

                                if(!in_array($receipt_array[$p]["currency_name"], $check_currency_arr))
                                {
                                    array_push($check_currency_arr, $receipt_array[$p]["currency_name"]);
                                    $current_currency = $receipt_array[$p]["currency_name"];
                                    $current_qb_client = $receipt_array[$p]["qb_customer_id"];
                                    $qb_receipt_id = $receipt_array[$p]["qb_receipt_id"];
                                    $can_import = true;
                                }
                                else
                                {
                                    $can_import = false;
                                }

                                if($can_import)
                                {
                                    for($k = 0; $k < count($receipt_array); $k++)
                                    {
                                        if($current_currency == $receipt_array[$k]["currency_name"])
                                        {
                                            $format_invoice_date = explode('/', $receipt_array[$k]["invoice_date"]);
                                            $year = $format_invoice_date[2];
                                            $month = $format_invoice_date[1];
                                            $day = $format_invoice_date[0];
                                            $new_format_inv_date = $month.'/'.$day.'/'.$year;
                                            $mydate = strtotime($new_format_inv_date);

                                            if($receipt_array[$k]["qb_invoice_id"] != 0 && $mydate >= $curdate)
                                            {
                                                $line_info = array("Amount" => $receipt_array[$k]["received"], "LinkedTxn" => [["TxnId" => $receipt_array[$k]["qb_invoice_id"], "TxnType" => "Invoice"]]);
                                                $total_receipt_amount += $receipt_array[$k]["received"];
                                                array_push($line_array, $line_info);
                                            }
                                            else if($curdate > $mydate)
                                            {
                                                // $gotInvoiceNo = false;
                                                // $invoice_no_not_in_qb = $receipt_array[$k]["invoice_no"];
                                                //break;
                                                $total_receipt_amount += $receipt_array[$k]["received"];
                                            }
                                        }
                                    }

                                    if($gotInvoiceNo)
                                    {
                                        $format_receipt_date = explode('/', $receipt_array[0]["receipt_date"]);
                                        $year = $format_receipt_date[2];
                                        $month = $format_receipt_date[1];
                                        $day = $format_receipt_date[0];

                                        $new_format_txn_date = $year.'/'.$month.'/'.$day;

                                        $bank_name_id = $this->get_income_account($receipt_array[0]["qb_bank_name"]);
                                        $payment_method_id = $this->get_payment_method($receipt_array[0]["payment_mode"]);

                                        if($firm_info_arr[0]["currency_name"] != $current_currency && count($line_array) > 0)
                                        {
                                            $exchangeRate = round((float)$receipt_array[$p]["equival_amount"] / (float)$receipt_array[$p]["received"], 7);

                                            $receipt_info = [
                                                                "Line" => $line_array,
                                                                "TotalAmt" => $total_receipt_amount + $receipt_array[0]["out_of_balance"],
                                                                "TxnDate" => $new_format_txn_date,
                                                                "PaymentRefNum" => $receipt_array[0]["receipt_no"],
                                                                "CustomerRef"=> [
                                                                    "value"=>  $current_qb_client
                                                                ],
                                                                "CurrencyRef"=> [
                                                                    "value" => $current_currency
                                                                ],
                                                                "ExchangeRate" => $exchangeRate
                                                            ]; 
                                        }
                                        else if (count($line_array) > 0)
                                        {
                                            $receipt_info = [
                                                                "Line" => $line_array,
                                                                "TotalAmt" => $total_receipt_amount + $receipt_array[0]["out_of_balance"],
                                                                "TxnDate" => $new_format_txn_date,
                                                                "PaymentRefNum" => $receipt_array[0]["receipt_no"],
                                                                "CustomerRef"=> [
                                                                    "value"=>  $current_qb_client
                                                                ],
                                                                "CurrencyRef"=> [
                                                                    "value" => $current_currency
                                                                ]
                                                                
                                                            ]; 
                                        }
                                        else
                                        {
                                            $receipt_info = [
                                                                "TotalAmt" => $total_receipt_amount + $receipt_array[0]["out_of_balance"],
                                                                "TxnDate" => $new_format_txn_date,
                                                                "PaymentRefNum" => $receipt_array[0]["receipt_no"],
                                                                "CustomerRef"=> [
                                                                    "value"=>  $current_qb_client
                                                                ],
                                                                "CurrencyRef"=> [
                                                                    "value" => $current_currency
                                                                ]
                                                                
                                                            ]; 
                                        }

                                        if($bank_name_id != null)
                                        {
                                            $bank_name_add = ["DepositToAccountRef" => [
                                                            "value" => $bank_name_id,
                                                            "name" => $receipt_array[0]["qb_bank_name"]
                                                        ]];
                                            $receipt_info = array_merge($receipt_info, $bank_name_add);
                                        }

                                        if($payment_method_id != null)
                                        {
                                            $payment_method_add = ["PaymentMethodRef" => [
                                                            "value" => $payment_method_id
                                                        ]];
                                            $receipt_info = array_merge($receipt_info, $payment_method_add);
                                        }
                                        //print_r($receipt_info);
                                        if($receipt_array[0]["receipt_no"] != null && $current_qb_client != null && $current_currency != null && $current_qb_client != 0)
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
                                                         'baseUrl' => $this->quickbookBaseUrl,
                                                    ));

                                                    $dataService->throwExceptionOnError(true);

                                                    if(!empty($qb_receipt_id))
                                                    {
                                                        $receipt = $dataService->FindbyId('payment', $qb_receipt_id);

                                                        $theResourceObj = Payment::update($receipt, $receipt_info);

                                                        $resultingObj = $dataService->Update($theResourceObj);
                                                    }
                                                    else
                                                    {
                                                        $theResourceObj = Payment::create($receipt_info);

                                                        $resultingObj = $dataService->Add($theResourceObj);
                                                    }
                                                    
                                                    $error = $dataService->getLastError();
      
                                                    if ($error) {
                                                        if($error->getHttpStatusCode() == "401")
                                                        {
                                                            $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                                                            if($refresh_token_status)
                                                            {
                                                                $this->import_all_receipt_to_qb();
                                                            }
                                                        }
                                                        else
                                                        {
                                                            return json_encode(array("Status" => 3, 'message' => $error->getIntuitErrorMessage(), 'title' => 'Error'));
                                                        }
                                                    }
                                                    else 
                                                    {
                                                        $receipt_save_data["receipt_id"] = $receipt_id;
                                                        $receipt_save_data["qb_receipt_id"] = $resultingObj->Id;
                                                        $receipt_save_data["currency_name"] = $current_currency;
                                                        $receipt_save_data["qb_receipt_json"] = json_encode($resultingObj);

                                                        if(!empty($qb_receipt_id))
                                                        {
                                                            $this->db->update("receipt_qb_record", $receipt_save_data, array("qb_receipt_id" => $qb_receipt_id));
                                                        }
                                                        else
                                                        {
                                                            $this->db->insert("receipt_qb_record",$receipt_save_data);
                                                        }
                                                    }
                                                }
                                                catch (Exception $e){
                                                    $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                                                    if($refresh_token_status)
                                                    {
                                                        $this->import_all_receipt_to_qb();
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                $success = false;
                                                echo json_encode(array("Status" => 2, 'message' => 'Please login to Quickbook Online to save this receipt to Quickbook Online.', 'title' => 'Warning'));
                                                break;
                                            }
                                        }
                                        else
                                        {
                                            if($receipt_array[0]["receipt_no"] == null)
                                            {
                                                $missing_name = $receipt_array[0]["receipt_no"];
                                            }
                                            else if($current_qb_client == null || $current_qb_client == 0)
                                            {
                                                $missing_name = $receipt_array[0]["company_name"] . " (".$current_currency.")";
                                            }
                                            else if($current_currency == null)
                                            {
                                                $missing_name = $current_currency;
                                            }
                                            $success = false;
                                            echo json_encode(array("Status" => 2, 'message' => 'This receipt cannot be import to Quickbook Online because ' . $missing_name .' is not in Quickbook Online.', 'title' => 'Warning'));
                                            break;
                                        }
                                    }
                                    // else
                                    // {
                                    //     $success = false;
                                    //     return json_encode(array("Status" => 2, 'message' => 'Invoice No. '.$invoice_no_not_in_qb.' not in Quickbook Online.', 'title' => 'Warning'));
                                    // }
                                }
                            }
                        }
                    }
                    if($success == true)
                    {
                        $this->save_audit_trail("Billings", "QuickBooks", $this->session->userdata('first_name'). " " . $this->session->userdata('last_name') . " import 10 receipts to QuickBooks Online.");
                        echo json_encode(array("Status" => 1, 'message' => "Information Updated", 'title' => 'Success'));
                    }
                }
                else
                {
                    echo json_encode(array("Status" => 2, 'message' => 'No more receipt can import to Quickbook Online.', 'title' => 'Warning'));
                }
            }
            else
            {
                echo json_encode(array("Status" => 3, 'message' => 'Please select one End Date.', 'title' => 'Error'));
            }
        }
        else
        {
            echo json_encode(array("Status" => 3, 'message' => 'Please select one Start Date.', 'title' => 'Error'));
        }
    }

    public function import_all_cn_to_qb()
    {
        $start = $_POST["import_start_date"];
        $end = $_POST["import_end_date"];

        if ($start != NULL)
        {
            if ($end != NULL)
            {
                $date_filter = ' AND STR_TO_DATE(billing_credit_note_gst.credit_note_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y") '; //  AND STR_TO_DATE(billing.invoice_date,"%d/%m/%Y") >= STR_TO_DATE("01/01/2021","%d/%m/%Y")

                $check_gst_status_query = $this->db->query("SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox, gst_jurisdiction.jurisdiction as jurisdiction_name FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id LEFT JOIN gst_jurisdiction ON gst_jurisdiction.id = firm.jurisdiction_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = '".$this->session->userdata("firm_id")."'");

                $firm_info = $this->db->query("SELECT firm.*, currency.currency as currency_name FROM firm LEFT JOIN currency ON currency.id = firm.firm_currency WHERE firm.id = '".$this->session->userdata("firm_id")."'");
                $firm_info_arr = $firm_info->result_array();

                $cn_data = $this->db->query("SELECT billing_credit_note_gst.* FROM billing_credit_note_gst
                    LEFT JOIN billing ON billing.id = billing_credit_note_gst.billing_id 
                    WHERE billing_credit_note_gst.qb_cn_id = 0 AND billing_credit_note_gst.firm_id = ".$this->session->userdata("firm_id").$date_filter." LIMIT 10");// LIMIT 2

                if ($cn_data->num_rows())
                {
                    $cn_data_array = $cn_data->result_array();
                    $success = true;
                    $curdate = strtotime('01-01-2021');

                    for($p = 0; $p < count($cn_data_array); $p++)
                    {
                        $credit_note_array = $this->check_cn_data_to_qb($cn_data_array[$p]["id"]);
                        $can_import_to_qb = true;
                        $service_not_in_qb = "";
                        $cn_id = $cn_data_array[$p]['id'];
                        
                        for($k = 0; $k < count($credit_note_array); $k++)
                        {
                            if($credit_note_array[$k]["qb_item_id"] == 0)
                            {
                                $invoice_no_not_in_qb = $credit_note_array[$k]["credit_note_no"];
                                $service_not_in_qb = $credit_note_array[$k]["service_name"];
                                $can_import_to_qb = false;
                                break;
                            }
                        }

                        if($can_import_to_qb)
                        {
                            $line_array = [];   
                            $billing_services_id = [];
                            $sub_total = 0;
                            $gst = 0;
                            $netAmountTaxable = 0;
                            $taxRate = 0;

                            for($k = 0; $k < count($credit_note_array); $k++)
                            {
                                $tax_code_ref = $this->query_qb_tax_code($credit_note_array[$k]["gst_category_name"]);

                                $line_info = array("Amount" => $credit_note_array[$k]["cn_amount"], "Description" => $credit_note_array[$k]["invoice_description"], "DetailType" => "SalesItemLineDetail", "SalesItemLineDetail" => ["TaxCodeRef" => ["value" => $tax_code_ref], "ItemRef" => ["value" => $credit_note_array[$k]["qb_item_id"]]]);

                                $sub_total += (float)$credit_note_array[$k]["cn_amount"];
                                $gst += round((($credit_note_array[$k]['gst_rate'] / 100) * (float)$credit_note_array[$k]["cn_amount"]), 2);

                                if($credit_note_array[$k]['gst_rate'] > 0)
                                {
                                    $netAmountTaxable += round((float)$credit_note_array[$k]["cn_amount"], 2);
                                    $taxRate = $credit_note_array[$k]['gst_rate'];
                                }

                                array_push($line_array, $line_info);
                                array_push($billing_services_id, $credit_note_array[$k]["billing_service_id"]);
                            }
                            $total = $sub_total + $gst;

                            $format_invoice_date = explode('/', $credit_note_array[0]["credit_note_date"]);
                            $year = $format_invoice_date[2];
                            $month = $format_invoice_date[1];
                            $day = $format_invoice_date[0];

                            $new_format_txn_date = $year.'/'.$month.'/'.$day;

                            if($this->session->userdata('refresh_token_value'))
                            {
                                if ($check_gst_status_query->num_rows() > 0) 
                                {
                                    if($firm_info_arr[0]["currency_name"] != $credit_note_array[0]["currency_name"])
                                    {
                                        $converted_total = $total * $credit_note_array[0]["cn_rate"];
                                        $creditmemo_info = [
                                                            "Line" => $line_array,
                                                            "DocNumber" => $credit_note_array[0]["credit_note_no"],
                                                            "TxnDate" => $new_format_txn_date,
                                                            "CustomerRef"=> [
                                                                  "value"=>  $credit_note_array[0]["qb_customer_id"]
                                                            ],
                                                            "CurrencyRef"=> [
                                                                "value" => $credit_note_array[0]["currency_name"]
                                                            ],
                                                            "ExchangeRate" => $credit_note_array[0]["cn_rate"],
                                                            "HomeTotalAmt" => $converted_total,
                                                            "TxnTaxDetail" => [
                                                                "TotalTax" => $gst
                                                            ]
                                                        ];
                                    }
                                    else
                                    {
                                        $creditmemo_info = [
                                                            "Line" => $line_array,
                                                            "DocNumber" => $credit_note_array[0]["credit_note_no"],
                                                            "TxnDate" => $new_format_txn_date,
                                                            "CustomerRef"=> [
                                                                  "value"=>  $credit_note_array[0]["qb_customer_id"]
                                                            ],
                                                            "CurrencyRef"=> [
                                                                "value" => $credit_note_array[0]["currency_name"]
                                                            ],
                                                            "TxnTaxDetail" => [
                                                                "TotalTax" => $gst
                                                            ]
                                                        ];
                                    }
                                }
                                else
                                {
                                    $creditmemo_info = [
                                                        "Line" => $line_array,
                                                        "DocNumber" => $credit_note_array[0]["credit_note_no"],
                                                        "TxnDate" => $new_format_txn_date,
                                                        "CustomerRef"=> [
                                                              "value"=>  $credit_note_array[0]["qb_customer_id"]
                                                        ],
                                                        "CurrencyRef"=> [
                                                            "value" => $credit_note_array[0]["currency_name"]
                                                        ]
                                                    ];
                                }
                                //print_r($creditmemo_info);
                                if($credit_note_array[0]["credit_note_no"] != null && $credit_note_array[0]["qb_customer_id"] != null && $credit_note_array[0]["currency_name"] != null && $credit_note_array[0]["qb_customer_id"] != 0)
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
                                             'baseUrl' => $this->quickbookBaseUrl,
                                        ));

                                        $dataService->throwExceptionOnError(true);

                                        if($credit_note_array[0]["qb_cn_id"] != 0)
                                        {
                                            $creditmemo = $dataService->FindbyId('creditmemo', $credit_note_array[0]["qb_cn_id"]);

                                            $theResourceObj = CreditMemo::update($creditmemo, $creditmemo_info);

                                            $resultingObj = $dataService->Update($theResourceObj);
                                        }
                                        else
                                        {
                                            $theResourceObj = CreditMemo::create($creditmemo_info);

                                            $resultingObj = $dataService->Add($theResourceObj);
                                        }
                                        
                                        $error = $dataService->getLastError();

                                        if ($error) {
                                            if($error->getHttpStatusCode() == "401")
                                            {
                                                $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                                                if($refresh_token_status)
                                                {
                                                    $this->import_all_cn_to_qb();
                                                }
                                            }
                                            else
                                            {
                                                echo json_encode(array("Status" => 3, 'message' => $error->getIntuitErrorMessage(), 'title' => 'Error'));
                                            }
                                        }
                                        else {
                                            $creditmemo_data["qb_cn_id"] = $resultingObj->Id;
                                            $creditmemo_data["qb_cn_json"] = json_encode($resultingObj);
                                            $this->db->update("billing_credit_note_gst", $creditmemo_data, array("id" => $cn_data_array[$p]["id"]));

                                            $salesItemLineReturnArr = $resultingObj->Line;

                                            for($h = 0; $h < count($salesItemLineReturnArr); $h++)
                                            {
                                                if($salesItemLineReturnArr[$h]->Id)
                                                {
                                                    $cn_services_data["qb_cn_service_id"] = $salesItemLineReturnArr[$h]->Id;
                                                    $this->db->update("billing_service", $cn_services_data, array("id" => $billing_services_id[$h]));
                                                }
                                            }

                                            $format_invoice_date = explode('/', $credit_note_array[0]["invoice_date"]);
                                            $year = $format_invoice_date[2];
                                            $month = $format_invoice_date[1];
                                            $day = $format_invoice_date[0];
                                            $new_format_inv_date = $month.'/'.$day.'/'.$year;
                                            $mydate = strtotime($new_format_inv_date);

                                            if($mydate >= $curdate)
                                            {
                                                $this->link_invoice_with_cn($credit_note_array, $resultingObj->Id, $new_format_txn_date, $cn_data_array[$p]["id"], $firm_info_arr);
                                            }
                                        }
                                    }
                                    catch (Exception $e){
                                        $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                                        if($refresh_token_status)
                                        {
                                            $this->import_all_cn_to_qb();
                                        }
                                    }
                                }
                                else
                                {
                                    if($credit_note_array[0]["credit_note_no"] == null)
                                    {
                                        $missing_name = $credit_note_array[0]["credit_note_no"];
                                    }
                                    else if($credit_note_array[0]["qb_customer_id"] == null || $credit_note_array[0]["qb_customer_id"] == 0)
                                    {
                                        $missing_name = $credit_note_array[0]["company_name"] . " (".$credit_note_array[0]["currency_name"].")";
                                    }
                                    else if($credit_note_array[0]["currency_name"] == null)
                                    {
                                        $missing_name = $credit_note_array[0]["currency_name"];
                                    }
                                    $success = false;
                                    echo json_encode(array("Status" => 2, 'message' => 'This Credit Note cannot be import to Quickbook Online because ' . $missing_name .' is not in Quickbook Online.', 'title' => 'Warning'));
                                    break;
                                }
                            }
                            else
                            {
                                $success = false;
                                echo json_encode(array("Status" => 2, 'message' => 'Please login to Quickbook Online to save this invoice to Quickbook Online.', 'title' => 'Warning'));
                            }
                        }
                        else
                        {
                            $success = false;
                            echo json_encode(array("Status" => 2, 'message' => 'This Credit Note cannot be import to Quickbook Online because ' . $service_not_in_qb .' services is not in Quickbook Online.', 'title' => 'Warning'));
                            break;
                        }
                    }

                    if($success == true)
                    {
                        $this->save_audit_trail("Billings", "QuickBooks", $this->session->userdata('first_name'). " " . $this->session->userdata('last_name') . " import 10 credit note to QuickBooks Online.");
                        echo json_encode(array("Status" => 1, 'message' => "Information Updated", 'title' => 'Success'));
                    }
                }
                else
                {
                    echo json_encode(array("Status" => 2, 'message' => 'Nothing can import to Quickbook Online.', 'title' => 'Warning'));
                }
            }
            else
            {
                echo json_encode(array("Status" => 3, 'message' => 'Please select one End Date.', 'title' => 'Error'));
            }
        }
        else
        {
            echo json_encode(array("Status" => 3, 'message' => 'Please select one Start Date.', 'title' => 'Error'));
        }
    }

    public function query_qb_tax_code($gst_category_name)
    {
        $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
        
        if($refresh_token_status)
        {
            //set_time_limit(0);
            /* API URL */
            $url = $this->quickbookURL.'/v3/company/'.$this->session->userdata('qb_company_id').'/query?query=select%20*%20from%20TaxCode%20where%20Active=true';

            /* Init cURL resource */
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            /* Array Parameter Data */
            //$data = ['Authorization' => 'Bearer '.$this->session->userdata('access_token_value')];
            $authorization = "Authorization:Bearer ".$this->session->userdata('access_token_value');

            curl_setopt($ch, CURLOPT_POST, false);

            /* pass encoded JSON string to the POST fields */
            //curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(""));
                
            /* set the content type json */
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type:text/plain', $authorization));
            //curl_setopt($ch, CURLOPT_TIMEOUT,500); // 500 seconds
            /* set return type json */
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                
            /* execute request */
            $result = curl_exec($ch);

            /* close cURL resource */
            curl_close($ch);

            $xml_snippet = simplexml_load_string( $result );
            $json_convert = json_encode( $xml_snippet );
            $json = json_decode( $json_convert );

            

            $taxCodeID = 21; // Default Out of Scope from Quickbook
            $taxCode = $json->QueryResponse->TaxCode;
            for($t = 0; $t < count($taxCode); $t++)
            {
                if(trim($taxCode[$t]->Name) == trim($gst_category_name))
                {
                    $taxCodeID = $taxCode[$t]->Id;
                }
            }
            return $taxCodeID;
        }
    }

    //for HRM assignment table part
    public function check_assingment_billings_invoice_no($assignment)
    {
        for($k = 0; $k < count($assignment); $k++)
        {
            $q = $this->db->query(" SELECT * FROM payroll_assignment_invoices WHERE assignment_id = '".$assignment[$k]."' ");

            if ($q->num_rows() > 0) {
                $this->db->where('assignment_id', $assignment[$k]);
                $this->db->update('payroll_assignment', array('invoice_flag' => 1));
            }
            else
            {
                $this->db->where('assignment_id', $assignment[$k]);
                $this->db->update('payroll_assignment', array('invoice_flag' => 0));
            }
        }
    }
        
    public function edit_recurring_bill($id)
    {
        $bc = array(array('link' => '#', 'page' => lang('Edit Billings')));
        $meta = array('page_title' => lang('Edit Billings'), 'bc' => $bc, 'page_name' => 'Edit Billings');

        $this->data['edit_recurring_bill'] =$this->db_model->get_edit_recurring_bill($id);
        $this->data['edit_recurring_bill_service'] =$this->db_model->get_edit_recurring_bill_service($id);
        $this->data['get_client_recurring_billing_info'] = $this->db_model->get_client_recurring_billing_info($id);
        $this->data['get_service_category'] = $this->db_model->get_service_category($id);
        $this->data['get_unit_pricing'] = $this->db_model->get_unit_pricing($id);

        $this->data['firm_info'] = $this->master_model->get_firm_info();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Recurring', base_url('billings'));
        $this->mybreadcrumb->add('Edit Recurrings - '.$this->data['edit_recurring_bill'][0]->company_name.'', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('client/create_recurring.php', $meta, $this->data);
    }

    public function get_edit_billing_info()
    {

        $id = $_POST['billing_id'];
        $transaction_task_id = $_POST['transaction_task_id'];
        $transaction_master_id = $_POST['transaction_master_id'];

        $this->data['edit_bill'] = $this->db_model->get_edit_bill($id);
        $this->data['edit_bill_service'] = $this->db_model->get_edit_transaction_bill_service($id, $transaction_task_id);
        if($transaction_task_id == 1 || $transaction_task_id == 4 || $transaction_task_id == 33 || $transaction_task_id == 34)
        {
            $this->data['get_client_billing_info'] = $this->db_model->get_transaction_client_billing_info($id, $transaction_master_id);
        }
        else
        {
            $this->data['get_client_billing_info'] = $this->db_model->get_transaction_our_services_info($id);
        }
        $this->data['get_service_category'] = $this->db_model->get_service_category($id);
        $this->data['get_unit_pricing'] = $this->db_model->get_unit_pricing($id);

        echo json_encode(array("Status" => 1, $this->data));
    }

    public function edit_bill($id)
    {
        $bc = array(array('link' => '#', 'page' => lang('Edit Billings')));
        $meta = array('page_title' => lang('Edit Billings'), 'bc' => $bc, 'page_name' => 'Edit Billings');

        $check_is_come_from_services = $this->db->query('select transaction_master_with_billing.*, transaction_master.transaction_task_id from transaction_master_with_billing left join transaction_master on transaction_master.id =  transaction_master_with_billing.transaction_master_id where billing_id = "'.$id.'"');

        if ($check_is_come_from_services->num_rows() > 0) 
        {
            $check_is_come_from_services = $check_is_come_from_services->result_array();
            $this->data['edit_bill'] = $this->db_model->get_edit_bill($id);
            $this->data['edit_bill_service'] = $this->db_model->get_edit_transaction_bill_service($id, $check_is_come_from_services[0]["transaction_task_id"]);

            if($check_is_come_from_services[0]["transaction_task_id"] == 1 || $check_is_come_from_services[0]["transaction_task_id"] == 4 || $check_is_come_from_services[0]["transaction_task_id"] == 33 || $check_is_come_from_services[0]["transaction_task_id"] == 34)
            {
                $this->data['get_client_billing_info'] = $this->db_model->get_transaction_client_billing_info($id, $check_is_come_from_services[0]["transaction_master_id"]);
            }
            else //if($this->data['get_client_billing_info'] == FALSE)
            {
                $this->data['get_client_billing_info'] = $this->db_model->get_transaction_our_services_info($id);
            }
            $this->data['get_service_category'] = $this->db_model->get_service_category($id);
            $this->data['get_unit_pricing'] = $this->db_model->get_unit_pricing($id);
        }
        else
        {
            $this->data['edit_bill'] =$this->db_model->get_edit_bill($id);
            $this->data['edit_bill_service'] =$this->db_model->get_edit_bill_service($id);
            $this->data['get_client_billing_info'] = $this->db_model->get_client_billing_info($id);
            $this->data['get_service_category'] = $this->db_model->get_service_category($id);
            $this->data['get_unit_pricing'] = $this->db_model->get_unit_pricing($id);
        }

        $this->data['firm_info'] = $this->master_model->get_firm_info();

        $assignment_result = $this->db->query("SELECT payroll_assignment.assignment_id, payroll_assignment.id, payroll_assignment.FYE, payroll_assignment_jobs.type_of_job FROM payroll_assignment LEFT JOIN payroll_assignment_jobs ON payroll_assignment_jobs.id = payroll_assignment.type_of_job AND payroll_assignment_jobs.deleted = 0 WHERE payroll_assignment.type_of_job != 'NULL' AND payroll_assignment.client_id = '".$this->data['edit_bill'][0]->company_code."'");

        $this->data['assignment_result'] = $assignment_result->result_array();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Billing', base_url('billings'));
        $this->mybreadcrumb->add('Edit Billings - '.$this->data['edit_bill'][0]->company_name.'', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        //print_r($this->data);
        $this->page_construct('client/create_billing.php', $meta, $this->data);
    }

    public function review_paid_bill($id)
    {
        $bc = array(array('link' => '#', 'page' => lang('Details Billings')));
        $meta = array('page_title' => lang('Details Billings'), 'bc' => $bc, 'page_name' => 'Details Billings');

        $check_is_come_from_services = $this->db->query('select transaction_master_with_billing.*, transaction_master.transaction_task_id from transaction_master_with_billing left join transaction_master on transaction_master.id =  transaction_master_with_billing.transaction_master_id where billing_id = "'.$id.'"');

        if ($check_is_come_from_services->num_rows() > 0) 
        {
            $check_is_come_from_services = $check_is_come_from_services->result_array();
            $this->data['edit_bill'] = $this->db_model->get_edit_bill($id);
            $this->data['edit_bill_service'] = $this->db_model->get_edit_transaction_bill_service($id, $check_is_come_from_services[0]["transaction_task_id"]);

            if($check_is_come_from_services[0]["transaction_task_id"] == 1 || $check_is_come_from_services[0]["transaction_task_id"] == 4 || $check_is_come_from_services[0]["transaction_task_id"] == 33 || $check_is_come_from_services[0]["transaction_task_id"] == 34)
            {
                $this->data['get_client_billing_info'] = $this->db_model->get_transaction_client_billing_info($id, $check_is_come_from_services[0]["transaction_master_id"]);
            }
            else //if($this->data['get_client_billing_info'] == FALSE)
            {
                $this->data['get_client_billing_info'] = $this->db_model->get_transaction_our_services_info($id);
            }
            $this->data['get_service_category'] = $this->db_model->get_service_category($id);
            $this->data['get_unit_pricing'] = $this->db_model->get_unit_pricing($id);
        }
        else
        {
            $this->data['edit_bill'] =$this->db_model->get_edit_bill($id);
            $this->data['edit_bill_service'] =$this->db_model->get_edit_bill_service($id);
            $this->data['get_client_billing_info'] = $this->db_model->get_history_client_billing_info($id);
            $this->data['get_service_category'] = $this->db_model->get_service_category($id);
            $this->data['get_unit_pricing'] = $this->db_model->get_unit_pricing($id);
        }

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Billing', base_url('billings'));
        $this->mybreadcrumb->add('Details Billings - '.$this->data['edit_bill'][0]->company_name.'', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('client/review_paid_billing.php', $meta, $this->data);
    }

    public function delete_qb_invoice($qb_invoice_id, $billing_id, $invoice_no)
    {
        if($this->session->userdata('refresh_token_value'))
        {
            try 
            {
                // Prep Data Services
                $dataService = DataService::Configure(array(
                     'auth_mode' => 'oauth2',
                     'ClientID' => $this->quickbook_clientID,
                     'ClientSecret' => $this->quickbook_clientSecret,
                     'accessTokenKey' => $this->session->userdata('access_token_value'),
                     'refreshTokenKey' => $this->session->userdata('refresh_token_value'),
                     'QBORealmID' => $this->session->userdata('qb_company_id'), //"The Company ID which the app wants to access"
                     'baseUrl' => $this->quickbookBaseUrl,
                ));

                $dataService->throwExceptionOnError(true);
                
                $invoice = $dataService->FindbyId('invoice', $qb_invoice_id);
                $resultingObj = $dataService->Delete($invoice);
    
                $error = $dataService->getLastError();

                if ($error) {
                    if($error->getHttpStatusCode() == "401")
                    {
                        $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                        if($refresh_token_status)
                        {
                            $this->delete_qb_invoice($qb_invoice_id, $billing_id, $invoice_no);
                        }
                    }
                    else
                    {
                        return json_encode(array("Status" => 2, 'message' => $error->getIntuitErrorMessage(), 'title' => 'Error'));
                    }
                }
                else {
                    $this->delete_sec_invoice($billing_id, $invoice_no);

                    return json_encode(array("Status" => 1, 'message' => "Information Updated", 'title' => 'Success'));
                }
            }
            catch (Exception $e){
                $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                if($refresh_token_status)
                {
                    $this->delete_qb_invoice($qb_invoice_id, $billing_id, $invoice_no);
                }
            }
        }
        else
        {
            return json_encode(array("Status" => 2, 'message' => 'Please login to Quickbook Online to save this invoice in Quickbook Online.', 'title' => 'Error'));
        }
    }

    public function delete_sec_invoice($billing_id, $invoice_no)
    {
        $this->db->set('status', 1);
        $this->db->where('id', $billing_id);
        $this->db->update('billing');

        $billing_service_result = $this->db->query("select * from billing_service where billing_id = '".$billing_id."' and claim_service_id != '".NULL."'");

        $billing_service_result = $billing_service_result->result_array();
        $latest_array_claim_service_id = json_decode($billing_service_result[0]["claim_service_id"]);
        //echo json_encode($billing_service_result);
        for($r = 0; $r < count($latest_array_claim_service_id); $r++)
        {
            $claim_service["billing_service_id"] = 0;
            $this->db->update("claim_service",$claim_service,array("id" => $latest_array_claim_service_id[$r]));
        }

        $this->save_audit_trail("Billings", "Index", $invoice_no." invoice is deleted.");
    }

    public function delete_qb_receipt($qb_receipt_id, $billing_receipt_record_arr, $receipt_info, $receipt_id, $billing_id, $num)
    {
        if($this->session->userdata('refresh_token_value'))
        {
            try 
            {
                // Prep Data Services
                $dataService = DataService::Configure(array(
                     'auth_mode' => 'oauth2',
                     'ClientID' => $this->quickbook_clientID,
                     'ClientSecret' => $this->quickbook_clientSecret,
                     'accessTokenKey' => $this->session->userdata('access_token_value'),
                     'refreshTokenKey' => $this->session->userdata('refresh_token_value'),
                     'QBORealmID' => $this->session->userdata('qb_company_id'), //"The Company ID which the app wants to access"
                     'baseUrl' => $this->quickbookBaseUrl,
                ));

                $dataService->throwExceptionOnError(true);
                
                if($num == 0)
                {
                    $payment = $dataService->FindbyId('payment', $qb_receipt_id);
                    $resultingObj = $dataService->Delete($payment);
                }
    
                $error = $dataService->getLastError();

                if ($error) {
                    if($error->getHttpStatusCode() == "401")
                    {
                        $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                        if($refresh_token_status)
                        {
                            $this->delete_qb_receipt($qb_receipt_id, $billing_receipt_record_arr, $receipt_info, $receipt_id, $billing_id);
                        }
                    }
                    else
                    {
                        return json_encode(array("Status" => 2, 'message' => $error->getIntuitErrorMessage(), 'title' => 'Error'));
                    }
                }
                else {
                    $this->delete_sec_receipt($qb_receipt_id, $billing_receipt_record_arr, $receipt_info, $receipt_id, $billing_id);

                    return json_encode(array("Status" => 1, 'message' => "Information Updated", 'title' => 'Success'));
                }
            }
            catch (Exception $e){
                $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                if($refresh_token_status)
                {
                    $this->delete_qb_receipt($qb_receipt_id, $billing_receipt_record_arr, $receipt_info, $receipt_id, $billing_id);
                }
            }
        }
        else
        {
            return json_encode(array("Status" => 2, 'message' => 'Please login to Quickbook Online to delete this receipt in Quickbook Online.', 'title' => 'Error'));
        }
    }

    public function delete_sec_receipt($qb_receipt_id = null, $billing_receipt_record_arr, $receipt_info, $receipt_id, $billing_id)
    {
        $query_billing = $this->db->query('select * from billing where id ="'.$billing_receipt_record_arr["billing_id"].'" AND status = 0 AND firm_id = "'.$this->session->userdata("firm_id").'"');

        if ($query_billing->num_rows())
        {
            $query_billing = $query_billing->result_array();
            $outstanding = (float)$query_billing[0]["outstanding"] + (float)$billing_receipt_record_arr["received"] - (float)$query_billing[0]["pay_additional"];

            $billing_data = array(
                        'outstanding' => $outstanding,
                        'pay_additional' => 0
                    );
            $this->db->set($billing_data);
            $this->db->where('id', $billing_receipt_record_arr["billing_id"]);
            $this->db->update('billing');
        }

        $this->db->delete('billing_receipt_record', array("receipt_id" => $receipt_id, "firm_id" => $this->session->userdata("firm_id")));

        if((float)$billing_receipt_record_arr["equival_amount"] > 0)
        {
            if($billing_receipt_record_arr["is_from_cn"] == 1)
            {
                $total_out_of_balance = (float)$receipt_info["out_of_balance"] + (float)$billing_receipt_record_arr["received"];
            }
            else
            {
                $total_out_of_balance = (float)$receipt_info["out_of_balance"] + (float)$billing_receipt_record_arr["equival_amount"];
            }
        }
        else
        {
            $total_out_of_balance = (float)$receipt_info["out_of_balance"] + (float)$billing_receipt_record_arr["received"];
        }

        if($total_out_of_balance == (float)$receipt_info["total_amount_received"])
        {
            $receipt_q = $this->db->get_where("receipt", array("id" => $receipt_id));

            if ($receipt_q->num_rows())
            {
                $receipt_array = $receipt_q->result_array();
                $this->save_audit_trail("Billings", "Index", $receipt_array[0]["receipt_no"]." receipt is deleted.");
            }

            $this->db->delete('receipt', array("id" => $receipt_id));
        }
        else
        {
            $this->db->set('out_of_balance', $total_out_of_balance);
            $this->db->where('id', $receipt_id);
            $this->db->update('receipt');
        }

        if($qb_receipt_id != null)
        {
            $this->db->delete('receipt_qb_record', array("qb_receipt_id" => $qb_receipt_id));
        }

        $q_billing_credit_note_gst_with_receipt = $this->db->query('select * from billing_credit_note_gst_with_receipt where receipt_id = "'.$receipt_id.'" order by id');

        if ($q_billing_credit_note_gst_with_receipt->num_rows())
        {
            $q_billing_credit_note_gst_with_receipt = $q_billing_credit_note_gst_with_receipt->result_array();
            $cn_received = (float)$billing_receipt_record_arr["received"];
            foreach ($q_billing_credit_note_gst_with_receipt as $key => $value) {
                $query_billing_credit_note_gst = $this->db->query('select * from billing_credit_note_gst where id ="'.$value["billing_credit_note_gst_id"].'"');
                $query_billing_credit_note_gst = $query_billing_credit_note_gst->result_array();

                $cn_out_of_balance = $cn_received - (float)$query_billing_credit_note_gst[0]["previous_cn_out_of_balance"];
                if($cn_out_of_balance > 0)
                {
                    $cn_received = $cn_out_of_balance;
                    $this->db->set('cn_out_of_balance', (float)$query_billing_credit_note_gst[0]["previous_cn_out_of_balance"]);
                }
                else
                {
                    $cn_out_of_balance = $cn_received + (float)$query_billing_credit_note_gst[0]["cn_out_of_balance"];
                    $cn_received = 0;
                    $this->db->set('cn_out_of_balance', $cn_out_of_balance);
                }
                $this->db->where('id', $value["billing_credit_note_gst_id"]);
                $this->db->update('billing_credit_note_gst');

                $this->db->delete('billing_credit_note_gst_with_receipt', array("id" => $value["id"]));
            }
        }
    }

    public function delete_billing()
    {
        if($_POST["tab"] == "billing")
        {
            $billing_id = $_POST["billing_id"];

            if(count($billing_id) != 0)
            {
                for($i = 0; $i < count($billing_id); $i++)
                {
                    $q = $this->db->get_where("billing", array("id" => $billing_id[$i]));

                    if ($q->num_rows())
                    {
                        $billing_array = $q->result_array();
                        
                        if($billing_array[0]["qb_invoice_id"] != 0)
                        {
                            $delete_status = $this->delete_qb_invoice($billing_array[0]["qb_invoice_id"], $billing_id[$i], $billing_array[0]["invoice_no"]);
                        }
                        else if($billing_array[0]["qb_invoice_id"] == 0)
                        {
                            $this->delete_sec_invoice($billing_id[$i], $billing_array[0]["invoice_no"]);

                            $delete_status = json_encode(array("Status" => 1, 'message' => 'Information Deleted', 'title' => 'Deleted'));
                        }
                    }
                }

                echo $delete_status;
            }
        }
        else if($_POST["tab"] == "receipt")
        {
            $receipt_id = $_POST["receipt_id"];
            $billing_id = $_POST["billing_id"];

            if(count($receipt_id) != 0)
            {
                for($i = 0; $i < count($receipt_id); $i++)
                {
                    $billing_receipt_record_query = $this->db->query('select * from billing_receipt_record where receipt_id = "'.$receipt_id[$i].'" AND firm_id = "'.$this->session->userdata("firm_id").'" ORDER BY billing_receipt_record.id');

                    if ($billing_receipt_record_query->num_rows())
                    {
                        $billing_receipt_record_arr = $billing_receipt_record_query->result_array();

                        $receipt_info = $this->db->query('select receipt.*, receipt_qb_record.qb_receipt_id from receipt left join receipt_qb_record on receipt_qb_record.receipt_id = receipt.id where receipt.id ="'.$receipt_id[$i].'" ORDER BY receipt_qb_record.id');

                        $receipt_info = $receipt_info->result_array();

                        for($k = 0; $k < count($billing_receipt_record_arr); $k++)
                        {
                            if(!empty($receipt_info[$i]["qb_receipt_id"]))
                            {

                                $delete_status = $this->delete_qb_receipt($receipt_info[$i]["qb_receipt_id"], $billing_receipt_record_arr[$k], $receipt_info[$i], $receipt_id[$i], $billing_id[$i], $k);
                                
                            }
                            else
                            {
                                
                                $this->delete_sec_receipt(null, $billing_receipt_record_arr[$k], $receipt_info[$i], $receipt_id[$i], $billing_id[$i]);

                                $delete_status = json_encode(array("Status" => 1, 'message' => 'Information Deleted', 'title' => 'Deleted'));
                            }
                        }
                    }
                }

                echo $delete_status;
            }
        }
        else if($_POST["tab"] == "credit_note")
        {
            $credit_note_id = $_POST["credit_note_id"];
            $billing_id = $_POST["billing_id"];

            if(count($credit_note_id) != 0)
            {
                for($i = 0; $i < count($credit_note_id); $i++)
                {
                    $q = $this->db->query('select * from billing_credit_note_record where credit_note_id = "'.$credit_note_id[$i].'" AND billing_id = "'.$billing_id[$i].'" AND firm_id = "'.$this->session->userdata("firm_id").'"');

                    if ($q->num_rows())
                    {
                        $q = $q->result_array();

                        $query_billing = $this->db->query('select * from billing where id ="'.$q[0]["billing_id"].'" AND status = 0 AND firm_id = "'.$this->session->userdata("firm_id").'"');

                        if ($query_billing->num_rows())
                        {
                            $query_billing = $query_billing->result_array();
                            $outstanding = (float)$query_billing[0]["outstanding"] + (float)$q[0]["received"];

                            $this->db->set('outstanding', $outstanding);
                            $this->db->where('id', $q[0]["billing_id"]);
                            $this->db->update('billing');
                        }

                        $this->db->delete('billing_credit_note_record', array("credit_note_id" => $credit_note_id[$i], "billing_id" => $billing_id[$i], "firm_id" => $this->session->userdata("firm_id")));

                        $credit_note_info = $this->db->query('select * from credit_note where id ="'.$credit_note_id[$i].'"');

                        $credit_note_info = $credit_note_info->result_array();

                        $total_amount_received = (float)$credit_note_info[0]["total_amount_received"] - (float)$q[0]["received"];

                        if($total_amount_received > 0)
                        {
                            $this->db->set('total_amount_received', $total_amount_received);
                            $this->db->where('id', $credit_note_id[$i]);
                            $this->db->update('credit_note');
                        }
                        else
                        {
                            $this->db->delete('credit_note', array("id" => $credit_note_id[$i]));
                        }
                        
                    }
                }
                echo json_encode(array("Status" => 1, 'message' => 'Information Deleted', 'title' => 'Deleted'));
            }
        }
        else if($_POST["tab"] == "recurring")
        {
            $recurring_billing_id = $_POST["recurring_billing_id"];

            if(count($recurring_billing_id) != 0)
            {
                for($i = 0; $i < count($recurring_billing_id); $i++)
                {
                    $q = $this->db->get_where("recurring_billing", array("id" => $recurring_billing_id[$i]));

                    if ($q->num_rows())
                    {
                        $recurring_array = $q->result_array();
                        $this->save_audit_trail("Billings", "Index", $recurring_array[0]["invoice_no"]." recurring is deleted.");
                    }

                    $this->db->delete('recurring_billing', array("id" => $recurring_billing_id[$i], "firm_id" => $this->session->userdata("firm_id")));
                    $this->db->delete('recurring_billing_service', array("billing_id" => $recurring_billing_id[$i]));
                }

                echo json_encode(array("Status" => 1, 'message' => 'Information Deleted', 'title' => 'Deleted'));
            }
        }
    }

    public function get_client_address()
    {
        $company_code = $_POST["company_code"];
        $currency = $_POST["currency"];

        $q = $this->db->query("select client.company_name, client.company_code, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, client.foreign_add_1, client.foreign_add_2, client.foreign_add_3, client.use_foreign_add_as_billing_add from client where company_code='".$company_code."'");

        $q = $q->result_array();

        if($q[0]["use_foreign_add_as_billing_add"] == 1)
        {
            if(!empty($q[0]["foreign_add_1"]))
            {
                $comma1 = $q[0]["foreign_add_1"] .'';
            }
            else
            {
                $comma1 = '';
            }

            if(!empty($q[0]["foreign_add_2"]))
            {
                $comma2 = $comma1 . $q[0]["foreign_add_2"] .'';
            }
            else
            {
                $comma2 = $comma1 . '';
            }
            $address = $comma2.$q[0]["foreign_add_3"];
        }
        else
        {
            if($q[0]["unit_no1"] != "" && $q[0]["unit_no2"] != "")
        {
            if($q[0]["building_name"] != "")
            {
                $unit_no = '#'.$q[0]["unit_no1"]."-".$q[0]["unit_no2"].' '.$q[0]["building_name"].'SINGAPORE '.$q[0]["postal_code"];
            }
            else
            {
                $unit_no = '#'.$q[0]["unit_no1"]."-".$q[0]["unit_no2"].'SINGAPORE '.$q[0]["postal_code"];
            }
        }
        else
        {
            if($q[0]["building_name"] != "")
            {
                $unit_no = $q[0]["building_name"].'SINGAPORE '.$q[0]["postal_code"];
            }
            else
            {
                $unit_no = 'SINGAPORE '.$q[0]["postal_code"];
            }
        }

        $address = $q[0]["street_name"].''.$unit_no;
        }

        echo json_encode(array("Status" => 1, "address" => $address, 'postal_code' => $q[0]["postal_code"], 'street_name' => $q[0]["street_name"], 'building_name' => $q[0]["building_name"], 'unit_no1' => $q[0]["unit_no1"], 'unit_no2' => $q[0]["unit_no2"], 'foreign_add_1' => $q[0]["foreign_add_1"], 'foreign_add_2' => $q[0]["foreign_add_2"], 'foreign_add_3' => $q[0]["foreign_add_3"]));
    }

    public function get_company_service()
    {
        $company_code = $_POST["company_code"];
        $currency = $_POST["currency"];

        $q = $this->db->query("select client.company_name, client.company_code, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, client.foreign_add_1, client.foreign_add_2, client.foreign_add_3, client.use_foreign_add_as_billing_add from client where company_code='".$company_code."'");

        $q = $q->result_array();

        if($q[0]["use_foreign_add_as_billing_add"] == 1)
        {
            if(!empty($q[0]["foreign_add_1"]))
            {
                $comma1 = $q[0]["foreign_add_1"] .'';
            }
            else
            {
                $comma1 = '';
            }

            if(!empty($q[0]["foreign_add_2"]))
            {
                $comma2 = $comma1 . $q[0]["foreign_add_2"] .'';
            }
            else
            {
                $comma2 = $comma1 . '';
            }
            $address = $comma2.$q[0]["foreign_add_3"];
        }
        else
        {
            if($q[0]["unit_no1"] != "" && $q[0]["unit_no2"] != "")
        {
            if($q[0]["building_name"] != "")
            {
                $unit_no = '#'.$q[0]["unit_no1"]."-".$q[0]["unit_no2"].' '.$q[0]["building_name"].'SINGAPORE '.$q[0]["postal_code"];
            }
            else
            {
                $unit_no = '#'.$q[0]["unit_no1"]."-".$q[0]["unit_no2"].'SINGAPORE '.$q[0]["postal_code"];
            }
        }
        else
        {
            if($q[0]["building_name"] != "")
            {
                $unit_no = $q[0]["building_name"].'SINGAPORE '.$q[0]["postal_code"];
            }
            else
            {
                $unit_no = 'SINGAPORE '.$q[0]["postal_code"];
            }
        }

        $address = $q[0]["street_name"].'
        '.$unit_no;
        }

        $check_gst_status_query = $this->db->query("SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = '".$this->session->userdata("firm_id")."'");

        if ($check_gst_status_query->num_rows() > 0) 
        {
            $check_gst_status_array = $check_gst_status_query->result_array();

            $p = $this->db->query("select client_billing_info.*, our_service_info.service_type, our_service_info.service_name, our_service_info.calculate_by_quantity_rate, billing_info_service_category.category_description, gst_category_info.gst_category_id, gst_category_info.start_date as gst_start_date, gst_category_info.end_date as gst_end_date, gst_category_info.rate 
                FROM client_billing_info 
                LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service 
                LEFT JOIN billing_info_service_category on billing_info_service_category.id = our_service_info.service_type 
                LEFT JOIN our_service_gst ON our_service_gst.our_service_info_id = client_billing_info.service and our_service_gst.jurisdiction_id = '".$check_gst_status_array[0]["jurisdiction_id"]."' 
                LEFT JOIN gst_category_info ON gst_category_info.deleted = 0 AND gst_category_info.id = our_service_gst.category_id AND gst_category_info.start_date <= CURRENT_DATE() AND (gst_category_info.end_date >= CURRENT_DATE() OR gst_category_info.end_date IS NULL) 
                where client_billing_info.company_code = '".$company_code."' and client_billing_info.currency = '".$currency."' and client_billing_info.deleted = 0");

            if ($p->num_rows() > 0) 
            {
                foreach (($p->result_array()) as $row) 
                {
                    if($row["rate"] == NULL)
                    {
                        $row["rate"] = 0;
                    }
                    if($row["gst_category_id"] == NULL)
                    {
                        $row["gst_category_id"] = 0;
                    }
                    $row["gst_new_way"] = 1;
                    $data[] = $row;
                }
            }
            else
            {
                $data = false;
            }
        }
        else
        {
            $p = $this->db->query("select client_billing_info.*, our_service_info.service_type, our_service_info.service_name, our_service_info.calculate_by_quantity_rate, billing_info_service_category.category_description 
                FROM client_billing_info 
                LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service 
                LEFT JOIN billing_info_service_category ON billing_info_service_category.id = our_service_info.service_type 
                WHERE client_billing_info.company_code = '".$company_code."' and client_billing_info.currency = '".$currency."' and client_billing_info.deleted = 0");

            if ($p->num_rows() > 0) 
            {
                foreach (($p->result_array()) as $row) 
                {
                    $row["rate"] = 0;
                    $row["gst_new_way"] = 0;
                    $row["gst_category_id"] = 0;
                    $data[] = $row;
                }
            }
            else
            {
                $data = false;
            }
        }

        $services = $data;

        $selected_billing_info_service_category = $this->db->query("select billing_info_service_category.* from billing_info_service_category");

        $unit_pricing_query = $this->db->query("select * from unit_pricing");

        $selected_billing_info_service_category = $selected_billing_info_service_category->result_array();
        $unit_pricing_query = $unit_pricing_query->result_array();

        $claim_result = $this->db->query("select claim_service.*, currency.currency as currency_name, payment_voucher_type.type_name from claim left join claim_service on claim_service.claim_id = claim.id left join payment_voucher_type on payment_voucher_type.id = claim_service.type_id left join currency on currency.id = claim.currency_id where claim_service.company_code = '".$company_code."' AND claim.status != 1 AND claim_service.billing_service_id = 0 AND claim.currency_id = '".$currency."' AND claim.firm_id = '".$this->session->userdata('firm_id')."'");

        $claim_result = $claim_result->result_array();

        $assignment_result = $this->db->query("SELECT payroll_assignment.assignment_id, payroll_assignment.id, payroll_assignment.FYE, payroll_assignment_jobs.type_of_job FROM payroll_assignment LEFT JOIN payroll_assignment_jobs ON payroll_assignment_jobs.id = payroll_assignment.type_of_job AND payroll_assignment_jobs.deleted = 0 WHERE payroll_assignment.type_of_job != 'NULL' AND payroll_assignment.client_id = '".$company_code."'");

        $assignment_result = $assignment_result->result_array();

        echo json_encode(array("Status" => 1, "address" => $address, "service" => $services, 'selected_billing_info_service_category' => $selected_billing_info_service_category, 'unit_pricing' => $unit_pricing_query, 'postal_code' => $q[0]["postal_code"], 'street_name' => $q[0]["street_name"], 'building_name' => $q[0]["building_name"], 'unit_no1' => $q[0]["unit_no1"], 'unit_no2' => $q[0]["unit_no2"], 'foreign_add_1' => $q[0]["foreign_add_1"], 'foreign_add_2' => $q[0]["foreign_add_2"], 'foreign_add_3' => $q[0]["foreign_add_3"], 'claim_result' => $claim_result, 'assignment_result' => $assignment_result));
    }

    public function get_gst_rate()
    {
        $array = explode('/', $_POST["billing_date"]);
        $tmp = $array[0];
        $array[0] = $array[1];
        $array[1] = $tmp;
        unset($tmp);
        $invoice_date = implode('/', $array);
        $time = strtotime($invoice_date);
        $invoice_date = date('Y-m-d',$time);
        $invoice_date = strtotime($invoice_date);

        $this->db->select('firm.*')
                ->from('firm')
                ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
                ->where('user_firm.user_id = '.$this->session->userdata('user_id'))
                ->where('user_firm.in_use = 1');

        $firm = $this->db->get();
        $firm = $firm->result_array();

        if(count($firm) > 0)
        {
            if($firm[0]["gst_checkbox"] == 1)
            {
                if($firm[0]["gst_date"] != null)
                {
                    $array = explode('/', $firm[0]["gst_date"]);
                    $tmp = $array[0];
                    $array[0] = $array[1];
                    $array[1] = $tmp;
                    unset($tmp);
                    $gst_date = implode('/', $array);
                    $time = strtotime($gst_date);
                    $gst_date = date('Y-m-d',$time);
                    $gst_date = strtotime($gst_date);
                }

                if($firm[0]["previous_gst_date"] != null)
                {
                    $array = explode('/', $firm[0]["previous_gst_date"]);
                    $tmp = $array[0];
                    $array[0] = $array[1];
                    $array[1] = $tmp;
                    unset($tmp);
                    $previous_gst_date = implode('/', $array);
                    $time = strtotime($previous_gst_date);
                    $previous_gst_date = date('Y-m-d',$time);
                    $previous_gst_date = strtotime($previous_gst_date);
                }

                if($previous_gst_date == null && $gst_date != null)
                {
                    if($invoice_date >= $gst_date)
                    {
                        $get_gst_rate = $firm[0]["gst"];
                    }
                    else
                    {
                        $get_gst_rate = 0;
                    }
                }
                else
                {
                    if($previous_gst_date == $gst_date)
                    {
                        $get_gst_rate = false;//$billing_service['gst_rate'] = $firm[0]["gst"];
                    }
                    else if($previous_gst_date > $gst_date)
                    {
                        if($previous_gst_date > $invoice_date && $invoice_date >= $gst_date)
                        {
                            $get_gst_rate = $firm[0]["gst"];
                        }
                        else if($invoice_date >= $previous_gst_date)
                        {
                            $get_gst_rate = $firm[0]["previous_gst"];
                        }
                        else
                        {
                            $get_gst_rate = 0;
                        }
                    }
                    else if($gst_date > $previous_gst_date)
                    {
                        if($gst_date > $invoice_date && $invoice_date >= $previous_gst_date)
                        {
                            $get_gst_rate = $firm[0]["previous_gst"];
                        }
                        else if($invoice_date > $gst_date) // >=
                        {
                            $get_gst_rate = false;//$get_gst_rate = $firm[0]["gst"]; //false
                        }
                        // else
                        // {
                        //     $get_gst_rate = 0;
                        // }
                    }
                }
                
            }
            else
            {
                $get_gst_rate = 0;
                $gst_date = null;
                $previous_gst_date = null;
            }
        }
        else
        {
            $get_gst_rate = 0;
            $gst_date = null;
            $previous_gst_date = null;
        }

        echo json_encode(array("Status" => 1, "get_gst_rate" => $get_gst_rate, "invoice_date" => $invoice_date, "previous_gst_date" => $previous_gst_date, "gst_date" => $gst_date));
    }
    //info take from client module but the service engagement take from transaction
    public function get_client_transaction_company_service()
    {
        $company_code = $_POST["company_code"];
        $currency = $_POST["currency"];
        $transaction_master_id = $_POST["transaction_master_id"];

        $q = $this->db->query("select client.company_name, client.company_code, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2 from client where company_code='".$company_code."'");

        $q = $q->result_array();

        if(count($q) > 0)
        {
            $company_name = $this->encryption->decrypt($q[0]["company_name"]);
            $postal_code = $q[0]["postal_code"];
            $street_name = $q[0]["street_name"];
            $building_name = $q[0]["building_name"];
            $unit_no1 = $q[0]["unit_no1"];
            $unit_no2 = $q[0]["unit_no2"];

            if($q[0]["unit_no1"] != "" && $q[0]["unit_no2"] != "")
            {
                if($q[0]["building_name"] != "")
                {
                    $unit_no = '#'.$q[0]["unit_no1"]."-".$q[0]["unit_no2"].' '.$q[0]["building_name"].'
        SINGAPORE '.$q[0]["postal_code"];
                }
                else
                {
                    $unit_no = '#'.$q[0]["unit_no1"]."-".$q[0]["unit_no2"].'
        SINGAPORE '.$q[0]["postal_code"];
                }
            }
            else
            {
                if($q[0]["building_name"] != "")
                {
                    $unit_no = $q[0]["building_name"].'
        SINGAPORE '.$q[0]["postal_code"];
                }
                else
                {
                    $unit_no = 'SINGAPORE '.$q[0]["postal_code"];
                }
            }

            $address = $q[0]["street_name"].'
        '.$unit_no;
        }
        else
        {
            $company_name = '';
            $address = '';
            $postal_code = '';
            $street_name = '';
            $building_name = '';
            $unit_no1 = '';
            $unit_no2 = '';
        }

        $check_gst_status_query = $this->db->query("SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = '".$this->session->userdata("firm_id")."'");

        if ($check_gst_status_query->num_rows() > 0) 
        {
            $check_gst_status_array = $check_gst_status_query->result_array();
            //got gst
            $p = $this->db->query("select transaction_client_billing_info.*, our_service_info.service_type, our_service_info.service_name, our_service_info.calculate_by_quantity_rate, billing_info_service_category.category_description, gst_category_info.gst_category_id, gst_category_info.start_date as gst_start_date, gst_category_info.end_date as gst_end_date, gst_category_info.rate 
                FROM transaction_client_billing_info 
                LEFT JOIN our_service_info ON our_service_info.id = transaction_client_billing_info.service 
                LEFT JOIN billing_info_service_category on billing_info_service_category.id = our_service_info.service_type 
                LEFT JOIN our_service_gst ON our_service_gst.our_service_info_id = transaction_client_billing_info.service and our_service_gst.jurisdiction_id = '".$check_gst_status_array[0]["jurisdiction_id"]."' 
                LEFT JOIN gst_category_info ON gst_category_info.deleted = 0 AND gst_category_info.id = our_service_gst.category_id AND gst_category_info.start_date <= CURRENT_DATE() AND (gst_category_info.end_date >= CURRENT_DATE() OR gst_category_info.end_date IS NULL) 
                where transaction_client_billing_info.company_code = '".$company_code."' and transaction_client_billing_info.currency = '".$currency."' and transaction_client_billing_info.transaction_id = '".$transaction_master_id."' and transaction_client_billing_info.deleted = 0");
            
            if ($p->num_rows() > 0) 
            {
                foreach (($p->result_array()) as $row) 
                {
                    if($row["rate"] == NULL)
                    {
                        $row["rate"] = 0;
                    }
                    if($row["gst_category_id"] == NULL)
                    {
                        $row["gst_category_id"] = 0;
                    }
                    $row["gst_new_way"] = 1;
                    $data[] = $row;
                }
            }
            else
            {
                $data = false;
            }
        }
        else
        {
            //dont have gst
            $p = $this->db->query("select transaction_client_billing_info.*, our_service_info.service_type, our_service_info.service_name, billing_info_service_category.category_description 
                FROM transaction_client_billing_info 
                LEFT JOIN our_service_info ON our_service_info.id = transaction_client_billing_info.service 
                LEFT JOIN billing_info_service_category ON billing_info_service_category.id = our_service_info.service_type 
                WHERE transaction_client_billing_info.company_code = '".$company_code."' and transaction_client_billing_info.currency = '".$currency."' and transaction_client_billing_info.transaction_id = '".$transaction_master_id."' and transaction_client_billing_info.deleted = 0");

            if ($p->num_rows() > 0) 
            {
                foreach (($p->result_array()) as $row) 
                {
                    $row["rate"] = 0;
                    $row["gst_new_way"] = 0;
                    $row["gst_category_id"] = 0;
                    $data[] = $row;
                }
            }
            else
            {
                $data = false;
            }
        }

        $services = $data;

        $selected_billing_info_service_category = $this->db->query("select billing_info_service_category.* from billing_info_service_category");

        $unit_pricing_query = $this->db->query("select * from unit_pricing");

        $selected_billing_info_service_category = $selected_billing_info_service_category->result_array();
        $unit_pricing_query = $unit_pricing_query->result_array();

        echo json_encode(array("Status" => 1,"company_name" => $company_name, "address" => $address, "service" => $services, 'selected_billing_info_service_category' => $selected_billing_info_service_category, 'unit_pricing' => $unit_pricing_query, 'postal_code' => $postal_code, 'street_name' => $street_name, 'building_name' => $building_name, 'unit_no1' => $unit_no1, 'unit_no2' => $unit_no2));
    }
    //info take from client module but the service engagement didnt take from anywhere
    public function get_our_service_info_for_transaction()
    {
        $company_code = $_POST["company_code"];
        $currency = $_POST["currency"];
        $transaction_task_id = $_POST["transaction_task_id"];
        $transaction_master_id = $_POST["transaction_master_id"];

        if($transaction_task_id == "36")
        {   
            $q = $this->db->query("SELECT transaction_purchase_common_seal_customer_info.company_code, client.company_name FROM transaction_purchase_common_seal_customer_info LEFT JOIN client ON client.company_code = transaction_purchase_common_seal_customer_info.company_code WHERE transaction_purchase_common_seal_customer_info.transaction_id = '".$transaction_master_id."' GROUP BY transaction_purchase_common_seal_customer_info.company_code");
            if ($q->num_rows() > 0) 
            {
                foreach (($q->result_array()) as $row) 
                {
                    $row["company_name"] = $this->encryption->decrypt($row["company_name"]);
                    $client_list[] = $row;
                }
            }
            else
            {
                $client_list[] = false;
            }
        }
        else
        {
            $q = $this->db->query("select client.company_name, client.company_code, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2 from client where company_code='".$company_code."'");

            $q = $q->result_array();

            if(count($q) > 0)
            {
                $company_name = $this->encryption->decrypt($q[0]["company_name"]);
                $postal_code = $q[0]["postal_code"];
                $street_name = $q[0]["street_name"];
                $building_name = $q[0]["building_name"];
                $unit_no1 = $q[0]["unit_no1"];
                $unit_no2 = $q[0]["unit_no2"];

                if($q[0]["unit_no1"] != "" && $q[0]["unit_no2"] != "")
                {
                    if($q[0]["building_name"] != "")
                    {
                        $unit_no = '#'.$q[0]["unit_no1"]."-".$q[0]["unit_no2"].' '.$q[0]["building_name"].'
        SINGAPORE '.$q[0]["postal_code"];
                    }
                    else
                    {
                        $unit_no = '#'.$q[0]["unit_no1"]."-".$q[0]["unit_no2"].'
        SINGAPORE '.$q[0]["postal_code"];
                    }
                }
                else
                {
                    if($q[0]["building_name"] != "")
                    {
                        $unit_no = $q[0]["building_name"].'
        SINGAPORE '.$q[0]["postal_code"];
                    }
                    else
                    {
                        $unit_no = 'SINGAPORE '.$q[0]["postal_code"];
                    }
                }

                $address = $q[0]["street_name"].'
        '.$unit_no;
            }
            else
            {
                $company_name = '';
                $address = '';
                $postal_code = '';
                $street_name = '';
                $building_name = '';
                $unit_no1 = '';
                $unit_no2 = '';
            }
        }

        $check_gst_status_query = $this->db->query("SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = '".$this->session->userdata("firm_id")."'");

        if ($check_gst_status_query->num_rows() > 0) 
        {
            $check_gst_status_array = $check_gst_status_query->result_array();
            //got gst
            $p = $this->db->query("select our_service_info.*, billing_info_service_category.category_description, gst_category_info.gst_category_id, gst_category_info.start_date as gst_start_date, gst_category_info.end_date as gst_end_date, gst_category_info.rate 
                FROM our_service_info 
                LEFT JOIN billing_info_service_category on billing_info_service_category.id = our_service_info.service_type 
                LEFT JOIN our_service_gst ON our_service_gst.our_service_info_id = our_service_info.id and our_service_gst.jurisdiction_id = '".$check_gst_status_array[0]["jurisdiction_id"]."' 
                LEFT JOIN gst_category_info ON gst_category_info.deleted = 0 AND gst_category_info.id = our_service_gst.category_id AND gst_category_info.start_date <= CURRENT_DATE() AND (gst_category_info.end_date >= CURRENT_DATE() OR gst_category_info.end_date IS NULL) 
                where our_service_info.user_admin_code_id = '".$this->session->userdata('user_admin_code_id')."' and our_service_info.currency = '".$currency."' and our_service_info.deleted = 0 and our_service_info.approved = 1");

            if ($p->num_rows() > 0) 
            {
                foreach (($p->result_array()) as $row) 
                {
                    if($row["rate"] == NULL)
                    {
                        $row["rate"] = 0;
                    }
                    if($row["gst_category_id"] == NULL)
                    {
                        $row["gst_category_id"] = 0;
                    }
                    $row["gst_new_way"] = 1;
                    $data[] = $row;
                }
            }
            else
            {
                $data = false;
            }
        }
        else
        {
            //dont have gst
            $p = $this->db->query("select our_service_info.*, billing_info_service_category.category_description 
                FROM our_service_info 
                LEFT JOIN billing_info_service_category ON billing_info_service_category.id = our_service_info.service_type 
                WHERE our_service_info.user_admin_code_id = '".$this->session->userdata('user_admin_code_id')."' and our_service_info.currency = '".$currency."' and our_service_info.deleted = 0 and our_service_info.approved = 1");
            //LEFT JOIN our_service_info ON our_service_info.id = transaction_client_billing_info.service 
            if ($p->num_rows() > 0) 
            {
                foreach (($p->result_array()) as $row) 
                {
                    $row["rate"] = 0;
                    $row["gst_new_way"] = 0;
                    $row["gst_category_id"] = 0;
                    $data[] = $row;
                }
            }
            else
            {
                $data = false;
            }
        }

        $services = $data;

        $selected_billing_info_service_category = $this->db->query("select billing_info_service_category.* from billing_info_service_category");

        $unit_pricing_query = $this->db->query("select * from unit_pricing");

        $selected_billing_info_service_category = $selected_billing_info_service_category->result_array();
        $unit_pricing_query = $unit_pricing_query->result_array();

        if($transaction_task_id == "36")
        {
            echo json_encode(array("Status" => 1,"client_list" => $client_list, "service" => $services, 'selected_billing_info_service_category' => $selected_billing_info_service_category, 'unit_pricing' => $unit_pricing_query));
        }
        else
        {
            echo json_encode(array("Status" => 1,"company_name" => $company_name, "address" => $address, "service" => $services, 'selected_billing_info_service_category' => $selected_billing_info_service_category, 'unit_pricing' => $unit_pricing_query, 'postal_code' => $postal_code, 'street_name' => $street_name, 'building_name' => $building_name, 'unit_no1' => $unit_no1, 'unit_no2' => $unit_no2));
        }
    }
    //info take from transaction client module and the service engagement take from transaction
    public function get_transaction_company_service()
    {
        $company_code = $_POST["company_code"];
        $currency = $_POST["currency"];

        $q = $this->db->query("select transaction_client.company_name, transaction_client.company_code, transaction_client.postal_code, transaction_client.street_name, transaction_client.building_name, transaction_client.unit_no1, transaction_client.unit_no2 from transaction_client where company_code='".$company_code."'");

        $q = $q->result_array();

        if(count($q) > 0)
        {
            $company_name = $this->encryption->decrypt($q[0]["company_name"]);
            $postal_code = $q[0]["postal_code"];
            $street_name = $q[0]["street_name"];
            $building_name = $q[0]["building_name"];
            $unit_no1 = $q[0]["unit_no1"];
            $unit_no2 = $q[0]["unit_no2"];

            if($q[0]["unit_no1"] != "" && $q[0]["unit_no2"] != "")
            {
                if($q[0]["building_name"] != "")
                {
                    $unit_no = '#'.$q[0]["unit_no1"]."-".$q[0]["unit_no2"].' '.$q[0]["building_name"].'SINGAPORE '.$q[0]["postal_code"];
                }
                else
                {
                    $unit_no = '#'.$q[0]["unit_no1"]."-".$q[0]["unit_no2"].'SINGAPORE '.$q[0]["postal_code"];
                }
            }
            else
            {
                if($q[0]["building_name"] != "")
                {
                    $unit_no = $q[0]["building_name"].'SINGAPORE '.$q[0]["postal_code"];
                }
                else
                {
                    $unit_no = 'SINGAPORE '.$q[0]["postal_code"];
                }
            }

            $address = $q[0]["street_name"].''.$unit_no;
        }
        else
        {
            $company_name = '';
            $address = '';
            $postal_code = '';
            $street_name = '';
            $building_name = '';
            $unit_no1 = '';
            $unit_no2 = '';
        }

        $check_gst_status_query = $this->db->query("SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = '".$this->session->userdata("firm_id")."'");

        if ($check_gst_status_query->num_rows() > 0) 
        {
            $check_gst_status_array = $check_gst_status_query->result_array();
            //got gst
            $p = $this->db->query("select transaction_client_billing_info.*, our_service_info.service_type, our_service_info.service_name, billing_info_service_category.category_description, gst_category_info.gst_category_id, gst_category_info.start_date as gst_start_date, gst_category_info.end_date as gst_end_date, gst_category_info.rate 
                FROM transaction_client_billing_info 
                LEFT JOIN our_service_info ON our_service_info.id = transaction_client_billing_info.service 
                LEFT JOIN billing_info_service_category on billing_info_service_category.id = our_service_info.service_type 
                LEFT JOIN our_service_gst ON our_service_gst.our_service_info_id = transaction_client_billing_info.service and our_service_gst.jurisdiction_id = '".$check_gst_status_array[0]["jurisdiction_id"]."' 
                LEFT JOIN gst_category_info ON gst_category_info.deleted = 0 AND gst_category_info.id = our_service_gst.category_id AND gst_category_info.start_date <= CURRENT_DATE() AND (gst_category_info.end_date >= CURRENT_DATE() OR gst_category_info.end_date IS NULL) 
                where transaction_client_billing_info.company_code = '".$company_code."' and transaction_client_billing_info.currency = '".$currency."' and transaction_client_billing_info.deleted = 0");
            
            if ($p->num_rows() > 0) 
            {
                foreach (($p->result_array()) as $row) 
                {
                    if($row["rate"] == NULL)
                    {
                        $row["rate"] = 0;
                    }
                    if($row["gst_category_id"] == NULL)
                    {
                        $row["gst_category_id"] = 0;
                    }
                    $row["gst_new_way"] = 1;
                    $data[] = $row;
                }
            }
            else
            {
                $data = false;
            }
        }
        else
        {
            //dont have gst
            $p = $this->db->query("select transaction_client_billing_info.*, our_service_info.service_type, our_service_info.service_name, billing_info_service_category.category_description 
                FROM transaction_client_billing_info 
                LEFT JOIN our_service_info ON our_service_info.id = transaction_client_billing_info.service 
                LEFT JOIN billing_info_service_category ON billing_info_service_category.id = our_service_info.service_type 
                WHERE transaction_client_billing_info.company_code = '".$company_code."' and transaction_client_billing_info.currency = '".$currency."' and transaction_client_billing_info.deleted = 0");

            if ($p->num_rows() > 0) 
            {
                foreach (($p->result_array()) as $row) 
                {
                    $row["rate"] = 0;
                    $row["gst_new_way"] = 0;
                    $row["gst_category_id"] = 0;
                    $data[] = $row;
                }
            }
            else
            {
                $data = false;
            }
        }

        $services = $data;

        $selected_billing_info_service_category = $this->db->query("select billing_info_service_category.* from billing_info_service_category");

        $unit_pricing_query = $this->db->query("select * from unit_pricing");

        $selected_billing_info_service_category = $selected_billing_info_service_category->result_array();
        $unit_pricing_query = $unit_pricing_query->result_array();

        echo json_encode(array("Status" => 1,"company_name" => $company_name, "address" => $address, "service" => $services, 'selected_billing_info_service_category' => $selected_billing_info_service_category, 'unit_pricing' => $unit_pricing_query, 'postal_code' => $postal_code, 'street_name' => $street_name, 'building_name' => $building_name, 'unit_no1' => $unit_no1, 'unit_no2' => $unit_no2));
    }

    public function save_transaction_create_billing()
    {
        if($this->session->userdata("firm_id") == 18 || $this->session->userdata("firm_id") == 26)
        {
            $where = '(firm_id = 18 or firm_id = 26)';
        }
        else
        {
            $where = "firm_id = '".$this->session->userdata('firm_id')."'";
        }

        $current_year = date("Y");
        
        $query_invoice_no = $this->db->query("select id, invoice_no, MAX(CAST(SUBSTRING(invoice_no, -4) AS UNSIGNED)) as latest_invoice_no from billing where YEAR(STR_TO_DATE(invoice_date,'%d/%m/%Y')) = ".$current_year." and status = '0' and ".$where." GROUP BY invoice_no ORDER BY latest_invoice_no DESC LIMIT 1");

        if ($query_invoice_no->num_rows() > 0) 
        {
            $query_invoice_no = $query_invoice_no->result_array();

            $last_section_invoice_no = (string)$query_invoice_no[0]["invoice_no"];

            $number = substr_replace($last_section_invoice_no, "", -4).(str_pad((int)(substr($last_section_invoice_no, -4)) + 1, 4, '0', STR_PAD_LEFT));
        }
        else
        {
            $number = "AB-".date("Y")."-".str_pad(1,4,"0",STR_PAD_LEFT);
        }

        $transaction_master_id = $_POST["transaction_master_id"];
        if($_POST["transaction_task_id"] == "36")
        {
            $company_code = $_POST["transaction_drop_client_name"];
            $company_name = $_POST["company_name"];
        }
        else
        {
            $company_code = $_POST["company_code"];
            $company_name = $_POST["transaction_client_name"];
        }

        $transaction_tasks_result = $this->db->query("select * from transaction_tasks where id = '".$_POST["transaction_task_id"]."'");
        $transaction_tasks_array = $transaction_tasks_result->result_array();

        $billing_date = $_POST["billing_date"];
        $currency = $_POST["currency"];
        $invoice_no = $number;
        $previous_invoice_no = $_POST["previous_invoice_no"];
        $amount = array_values($_POST["amount"]);
        $invoice_description = array_values($_POST["invoice_description"]);
        $service = array_values($_POST["service"]);
        $progress_billing_yes_no = array_values($_POST["progress_billing_yes_no"]);
        $poc_percentage = array_values($_POST["poc_percentage"]);
        $number_of_percent_poc = array_values($_POST["hidden_number_of_percent_poc"]);
        $radio_quantity_reading = array_values($_POST["radio_quantity_reading"]);
        $reading_at_begin = array_values($_POST["reading_at_begin"]);
        $reading_at_the_end = array_values($_POST["reading_at_the_end"]);
        $number_of_rate = array_values($_POST["number_of_rate"]);
        $unit_for_rate = array_values($_POST["unit_for_rate"]);
        $quantity_value = array_values($_POST["quantity_value"]);
        $period_start_date = array_values($_POST["period_start_date"]);
        $period_end_date = array_values($_POST["period_end_date"]);
        $unit_pricing = array_values($_POST["unit_pricing"]);
        $rate = $_POST["rate"];
        $grand_total = $_POST["grand_total"];

        $postal_code = $_POST["hidden_postal_code"];
        $street_name = $_POST["hidden_street_name"];
        $building_name = $_POST["hidden_building_name"];
        $unit_no1 = $_POST["hidden_unit_no1"];
        $unit_no2 = $_POST["hidden_unit_no2"];

        $billing_result = $this->db->query("select * from billing where company_code='".$company_code."' AND invoice_no = '".$previous_invoice_no."' AND status = '0'");

        $billing_result = $billing_result->result_array();

        if($billing_result)
        {
            $check_billing_id_result = $this->db->query("select * from billing where invoice_no = '".$previous_invoice_no."' AND status = '0' AND id != '".$billing_result[0]['id']."'");

            $check_billing_id_result = $check_billing_id_result->result_array();

            if(!$check_billing_id_result)
            {
                $new_amount = (float)str_replace(',', '', $grand_total);
                $new_outstanding = (float)str_replace(',', '', $grand_total) - ($billing_result[0]['amount'] - $billing_result[0]['outstanding']);

                $billing['invoice_no'] = $previous_invoice_no;
                $billing['currency_id'] = $currency;
                $billing['amount'] = $new_amount;
                $billing['rate'] = $rate;
                $billing['outstanding'] = $new_outstanding;

                $this->db->delete('billing_service', array('billing_id' => $billing_result[0]['id']));

                $this->db->update("billing",$billing,array("id" => $billing_result[0]['id']));

                $this->save_audit_trail("Services", $transaction_tasks_array[0]["transaction_task"], "Billing is edited.");

                $billing_service['billing_id'] = $billing_result[0]['id'];

                $can_insert_billing_service = true;
            }
            else
            {
                $can_insert_billing_service = false;
            }
        }
        else
        {
            $check_billing_id_result = $this->db->query("select * from billing where invoice_no = '".$invoice_no."' AND status = '0'");

            $check_billing_id_result = $check_billing_id_result->result_array();

            if(!$check_billing_id_result)
            {
                $billing['invoice_no'] = $invoice_no;
                $billing['firm_id'] = $this->session->userdata("firm_id");
                $billing['company_code'] = $company_code;
                $billing['company_name'] = $company_name;
                $billing['postal_code'] = $postal_code;
                $billing['building_name'] = $building_name;
                $billing['street_name'] = $street_name;
                $billing['unit_no1'] = $unit_no1;
                $billing['unit_no2'] = $unit_no2;
                $billing['invoice_date'] = $billing_date;
                $billing['rate'] = $rate;
                $billing['amount'] = 0;
                $billing['outstanding'] = 0;
                for($p = 0; $p < count($amount); $p++)
                {
                    if($_POST["old_gst_rate"] != "false")
                    {
                        $gst_rate = $_POST["old_gst_rate"];
                    }
                    else
                    {
                        $gst_rate = $_POST["gst_rate"][$p];
                    }

                    $billing['amount'] = $billing['amount'] + ((1+($gst_rate / 100)) * (float)str_replace(',', '', $amount[$p]));
                    $billing['outstanding'] = $billing['outstanding'] + ((1+($gst_rate / 100)) * (float)str_replace(',', '', $amount[$p]));
                }
                
                $billing['currency_id'] = $currency;

                $this->db->insert("billing",$billing);
                $billing_id = $this->db->insert_id();
                $billing_service['billing_id'] = $billing_id;

                $transaction_master_with_billing["transaction_master_id"] = $transaction_master_id;
                $transaction_master_with_billing["billing_id"] = $billing_id;

                $this->db->insert("transaction_master_with_billing",$transaction_master_with_billing);

                $this->save_audit_trail("Services", $transaction_tasks_array[0]["transaction_task"], "Billing is created.");

                $can_insert_billing_service = true;
            }
            else
            {
                $can_insert_billing_service = false;
            }
        }

        if($can_insert_billing_service)
        {
            for($k = 0; $k < count($amount); $k++)
            {
                $billing_service['invoice_date'] = $billing_date;
                $billing_service['service'] = $service[$k];
                $billing_service['invoice_description'] = $invoice_description[$k];
                $billing_service['progress_billing_yes_no'] = $progress_billing_yes_no[$k];

                if($poc_percentage[$k] != "")
                {
                    $billing_service['poc_percentage'] = $poc_percentage[$k];
                }
                else
                {
                    $billing_service['poc_percentage'] = NULL;
                }

                if($number_of_percent_poc[$k] != "")
                {
                    $billing_service['number_of_percent_poc'] = $number_of_percent_poc[$k];
                }
                else
                {
                    $billing_service['number_of_percent_poc'] = 0.00;
                }

                $billing_service['radio_quantity_reading'] = $radio_quantity_reading[$k];

                if($reading_at_begin[$k] != "")
                {
                    $billing_service['reading_at_begin'] = $reading_at_begin[$k];
                }
                else
                {
                    $billing_service['reading_at_begin'] = NULL;
                }

                if($reading_at_the_end[$k] != "")
                {
                    $billing_service['reading_at_the_end'] = $reading_at_the_end[$k];
                }
                else
                {
                    $billing_service['reading_at_the_end'] = NULL;
                }

                if($number_of_rate[$k] != "")
                {
                    $billing_service['number_of_rate'] = $number_of_rate[$k];
                }
                else
                {
                    $billing_service['number_of_rate'] = NULL;
                }

                if($unit_for_rate[$k] != "")
                {
                    $billing_service['unit_for_rate'] = $unit_for_rate[$k];
                }
                else
                {
                    $billing_service['unit_for_rate'] = NULL;
                }

                if($quantity_value[$k] != "")
                {
                    $billing_service['quantity_value'] = $quantity_value[$k];
                }
                else
                {
                    $billing_service['quantity_value'] = NULL;
                }
                $billing_service['amount'] = (float)str_replace(',', '', $amount[$k]);
                $billing_service['unit_pricing'] = $unit_pricing[$k];
                $billing_service['period_start_date'] = $period_start_date[$k];
                $billing_service['period_end_date'] = $period_end_date[$k];

                if($_POST["old_gst_rate"] != "false")
                {
                    $billing_service['gst_category_id'] = 0;
                    $billing_service['gst_rate'] = $_POST["old_gst_rate"];
                    $billing_service['gst_new_way'] = 0;
                }
                else
                {
                    $billing_service['gst_category_id'] = $_POST["gst_category_id"][$k];
                    $billing_service['gst_rate'] = $_POST["gst_rate"][$k];
                    $billing_service['gst_new_way'] = $_POST["gst_new_way"][$k];
                }

                $this->db->insert("billing_service",$billing_service);
                $billing_service_id = $this->db->insert_id();
            }

            $this->data['billings'] = $this->db_model->get_edit_unpaid_bill($transaction_master_id);
            $this->data['paid_billings'] = $this->db_model->get_edit_paid_bill($transaction_master_id);

            if($this->session->userdata('qb_company_id') != "")
            {
                if($_POST["transaction_task_id"] != "1")
                {
                    $qb_customer_id = ", client_qb_id.qb_customer_id";
                    $left_join_client = " LEFT JOIN client ON client.company_code = billing.company_code LEFT JOIN client_qb_id ON client_qb_id.company_code = billing.company_code AND client_qb_id.currency_name = currency.currency AND client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";

                    if($_POST["transaction_task_id"] == "4" || $_POST["transaction_task_id"] == "33" || $_POST["transaction_task_id"] == "34")
                    {
                        $left_join_client = $left_join_client." LEFT JOIN transaction_client_billing_info ON transaction_client_billing_info.id = billing_service.service 
                        LEFT JOIN our_service_info ON our_service_info.id = transaction_client_billing_info.service 
                        LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";
                    }
                    else
                    {
                        $left_join_client = $left_join_client." LEFT JOIN our_service_info ON our_service_info.id = billing_service.service LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";
                    }
                }
                else
                {
                    $qb_customer_id = ", transaction_client_qb_id.qb_customer_id";
                    $left_join_client = " LEFT JOIN transaction_client ON transaction_client.company_code = billing.company_code 
                    LEFT JOIN transaction_client_billing_info ON transaction_client_billing_info.id = billing_service.service 
                    LEFT JOIN our_service_info ON our_service_info.id = transaction_client_billing_info.service 
                    LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."'
                    LEFT JOIN transaction_client_qb_id ON transaction_client_qb_id.company_code = billing.company_code AND transaction_client_qb_id.currency_name = currency.currency AND transaction_client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";
                }

                $billing_info = $this->db->query("SELECT billing.*, billing_service.*, billing_service.id as billing_service_id, billing_service.amount as billing_service_amount, our_service_qb_info.qb_item_id, our_service_info.service_name,gst_category.category as gst_category_name, currency.currency as currency_name".$qb_customer_id."
                    FROM billing 
                    LEFT JOIN billing_service ON billing_service.billing_id = billing.id 
                    LEFT JOIN currency ON currency.id = billing.currency_id
                    ".$left_join_client."
                    LEFT JOIN gst_category_info ON gst_category_info.id = billing_service.gst_category_id
                    LEFT JOIN gst_category ON gst_category.id = gst_category_info.gst_category_id
                    WHERE billing.id = '".$billing_service['billing_id']."' ORDER BY billing_service.id");

                $billing_info_array = $billing_info->result_array(); 
                $can_import_to_qb = true;
                $service_not_in_qb = "";

                for($k = 0; $k < count($billing_info_array); $k++)
                {
                    if($billing_info_array[$k]["qb_item_id"] == 0)
                    {
                        $service_not_in_qb = $billing_info_array[$k]["service_name"];
                        $can_import_to_qb = false;
                        break;
                    }
                }

                if($can_import_to_qb)
                {
                    $billing_submit_status = $this->import_invoice_to_qb($billing_service['billing_id'], $billing_info_array);
                    $merge_result_array = array_merge($billing_submit_status, array('previous_invoice_no' => $previous_invoice_no, $this->data));
                    echo json_encode($merge_result_array);
                }
                else
                {
                    echo json_encode(array("Status" => 2, 'message' => 'This invoice cannot be import to Quickbook Online because ' . $service_not_in_qb .' services is not in Quickbook Online.', 'title' => 'Warning', 'previous_invoice_no' => $previous_invoice_no, $this->data));
                }
            }
            else
            {
                echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', 'previous_invoice_no' => $previous_invoice_no, $this->data));
            }
            // echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', 'previous_invoice_no' => $previous_invoice_no, $this->data));
        }
        else
        {
            echo json_encode(array("Status" => 3, 'message' => 'This Invoice No is already use.', 'title' => 'Error'));
        }
    }

    public function get_previous_credit_note()
    {
        $this->data['credit_note'] = $this->db_model->get_all_credit_note();

        echo json_encode($this->data);
    }

    public function get_credit_note_no()
    {
        $current_year = date("Y");
        if($this->session->userdata("firm_id") == 18 || $this->session->userdata("firm_id") == 26)
        {
            $where = '(billing_credit_note_gst.firm_id = 18 or billing_credit_note_gst.firm_id = 26)';
        }
        else
        {
            $where = "billing_credit_note_gst.firm_id = '".$this->session->userdata('firm_id')."'";
        }

        $query_credit_note_no_gst = $this->db->query("SELECT credit_note_no FROM billing_credit_note_gst where billing_credit_note_gst.id = (SELECT max(id) FROM billing_credit_note_gst where YEAR(billing_credit_note_gst.created_at) = ".$current_year." and ".$where.")");

        if ($query_credit_note_no_gst->num_rows() > 0) 
        {
            $query_credit_note_no_gst = $query_credit_note_no_gst->result_array();

            $last_section_credit_note_no_gst = (string)$query_credit_note_no_gst[0]["credit_note_no"];

            $credit_note_no = substr_replace($last_section_credit_note_no_gst, "", -4).(str_pad((int)(substr($last_section_credit_note_no_gst, -4)) + 1, 4, '0', STR_PAD_LEFT));
        }
        else
        {
            if($this->session->userdata("firm_id") == 18 || $this->session->userdata("firm_id") == 26)
            {
                $where = '(billing_credit_note_record.firm_id = 18 or billing_credit_note_record.firm_id = 26)';
            }
            else
            {
                $where = "billing_credit_note_record.firm_id = '".$this->session->userdata('firm_id')."'";
            }

            $query_credit_note_no = $this->db->query("SELECT credit_note_no FROM credit_note where credit_note.id = (SELECT max(credit_note_id) FROM billing_credit_note_record where YEAR(credit_note.created_at) = ".$current_year." and ".$where.")");

            if ($query_credit_note_no->num_rows() > 0) 
            {
                $query_credit_note_no = $query_credit_note_no->result_array();

                $last_section_credit_note_no = (string)$query_credit_note_no[0]["credit_note_no"];

                $credit_note_no = substr_replace($last_section_credit_note_no, "", -4).(str_pad((int)(substr($last_section_credit_note_no, -4)) + 1, 4, '0', STR_PAD_LEFT));
            }
            else
            {
                $credit_note_no = "CN-"."AB-".date("Y").str_pad(1,5,"0",STR_PAD_LEFT);
            }
        }

        $this->data['credit_note_no'] = $credit_note_no;
        $this->data['client_list'] = $this->db_model->get_client_list();
        $this->data['currency'] = $this->db_model->get_currency();
        $this->data['firm_currency'] = $this->db_model->get_firm_currency();

        echo json_encode($this->data);
    }

    public function get_client_invoice()
    {
        $company_code = $_POST["company_code"];
        $this->data['billings_invoice_no'] = $this->db_model->get_all_billings_invoice_no($company_code);

        $q = $this->db->query("select client.company_name, client.company_code, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, client.foreign_add_1, client.foreign_add_2, client.foreign_add_3, client.use_foreign_add_as_billing_add from client where company_code='".$company_code."'");

        if (0 >= $q->num_rows())
        {
            $q = $this->db->query("select transaction_client.company_name, transaction_client.company_code, transaction_client.postal_code, transaction_client.street_name, transaction_client.building_name, transaction_client.unit_no1, transaction_client.unit_no2 from transaction_client where company_code='".$company_code."'");
        }

        $q = $q->result_array();

        $this->data['postal_code'] = $q[0]["postal_code"];
        $this->data['street_name'] = $q[0]["street_name"];
        $this->data['building_name'] = $q[0]["building_name"];
        $this->data['unit_no1'] = $q[0]["unit_no1"];
        $this->data['unit_no2'] = $q[0]["unit_no2"];
        if($q[0]["foreign_add_1"] != null)
        {
            $this->data['foreign_add_1'] = $q[0]["foreign_add_1"];
        }
        else
        {
            $this->data['foreign_add_1'] = "";
        }
        if($q[0]["foreign_add_2"] != null)
        {
            $this->data['foreign_add_2'] = $q[0]["foreign_add_2"];
        }
        else
        {
            $this->data['foreign_add_2'] = "";
        }
        if($q[0]["foreign_add_3"] != null)
        {
            $this->data['foreign_add_3'] = $q[0]["foreign_add_3"];
        }
        else
        {
            $this->data['foreign_add_3'] = "";
        }
        
        echo json_encode($this->data);
    }

    public function get_latest_credit_note_info()
    {
        $credit_note_id = $_POST["credit_note_id"];
        $company_code = $_POST["company_code"];

        // $q = $this->db->query("select 
        //     billing_credit_note_gst.*, billing_credit_note_gst_record.*, client.incorporation_date, billing.company_name, billing.invoice_no, our_service_info.service_name, billing_credit_note_gst.id as billing_credit_note_gst_id, billing_credit_note_gst_record.id as billing_credit_note_gst_record_id
        //     from billing_credit_note_gst
        //     LEFT JOIN billing_credit_note_gst_record ON billing_credit_note_gst_record.credit_note_id = billing_credit_note_gst.id
        //     LEFT JOIN billing ON billing.id = billing_credit_note_gst.billing_id
        //     LEFT JOIN billing_service ON billing_service.id = billing_credit_note_gst_record.billing_service_id
        //     LEFT JOIN client_billing_info ON client_billing_info.id = billing_service.service
        //     LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service
        //     LEFT JOIN client ON client.company_code = billing.company_code AND client.deleted = 0
        //     WHERE billing_credit_note_gst.id = '".$credit_note_id."'");
        $q = $this->check_cn_data_to_qb($credit_note_id, true);

        if ($q->num_rows() > 0) {
            echo json_encode(array("status" => 1, 'result' => $q->result(), 'client_list' => $this->db_model->get_client_list(), 'billings_invoice_no' => $this->db_model->get_all_billings_invoice_no($company_code), "currency" => $this->db_model->get_currency()));
        } else echo json_encode(array("status" => 0));
    }

    public function get_a_billing_info()
    {
        $id = $_POST["billing_id"];

        $check_is_come_from_services = $this->db->query('select transaction_master_with_billing.*, transaction_master.transaction_task_id from transaction_master_with_billing left join transaction_master on transaction_master.id =  transaction_master_with_billing.transaction_master_id where billing_id = "'.$id.'"');

        if ($check_is_come_from_services->num_rows() > 0) 
        {
            $check_is_come_from_services = $check_is_come_from_services->result_array();
            $this->data['edit_bill'] = $this->db_model->get_edit_bill($id);
            $this->data['edit_bill_service'] = $this->db_model->get_edit_transaction_bill_service($id, $check_is_come_from_services[0]["transaction_task_id"]);
            $this->data['get_client_billing_info'] = $this->db_model->get_transaction_client_billing_info($id, $check_is_come_from_services[0]["transaction_master_id"]);
            if($this->data['get_client_billing_info'] == FALSE)
            {
                $this->data['get_client_billing_info'] = $this->db_model->get_transaction_our_services_info($id);
            }
            $this->data['get_service_category'] = $this->db_model->get_service_category($id);
            $this->data['get_unit_pricing'] = $this->db_model->get_unit_pricing($id);
            $this->data['get_billing_credit_note_gst_record'] = $this->db_model->get_billing_credit_note_gst_record($id);
        }
        else
        {
            $this->data['edit_bill'] = $this->db_model->get_edit_bill($id);
            $this->data['edit_bill_service'] = $this->db_model->get_edit_bill_service($id);
            $this->data['get_client_billing_info'] = $this->db_model->get_client_billing_info($id);
            $this->data['get_service_category'] = $this->db_model->get_service_category($id);
            $this->data['get_unit_pricing'] = $this->db_model->get_unit_pricing($id);
            $this->data['get_billing_credit_note_gst_record'] = $this->db_model->get_billing_credit_note_gst_record($id);
        }

        echo json_encode($this->data);
    }

    public function check_progress_billing_data()
    {
        $company_code = $_POST["company_code"];
        $service_value = $_POST["serviceValue"];

        $this->data['progress_billing_data'] = $this->db_model->get_progress_billing_data($company_code, $service_value);

        echo json_encode($this->data);
    }

    public function save_audit_trail($modules, $events, $actions)
    {
        $secretary_audit_trail["user_id"] = $this->session->userdata("user_id");
        $secretary_audit_trail["modules"] = $modules;
        $secretary_audit_trail["events"] = $events;
        $secretary_audit_trail["actions"] = $actions;

        $this->db->insert("secretary_audit_trail",$secretary_audit_trail);
    }

    public function check_qb_token()
    {
        if ($this->session->userdata('refresh_token_value')) {
            return true;
        } else {
            return false;
        } 
    }

    public function check_qb_token_after_login()
    {
        if ($this->session->userdata('refresh_token_value')) {
            echo true;
        } else {
            echo false;
        } 
    }

    public function create_invoice_in_qb()
    {
        // $logDate = date("j.n.Y");
        // file_put_contents('./log/billing_'.$logDate.'.log', "create_invoice_in_qb", FILE_APPEND);

        if($_POST["tab"] == "billing")
        {
            $billing_id = $_POST["billing_id"];

            if(count($billing_id) != 0)
            {
                for($i = 0; $i < count($billing_id); $i++)
                {
                    $check_is_come_from_services = $this->db->query('select transaction_master_with_billing.*, transaction_master.transaction_task_id from transaction_master_with_billing left join transaction_master on transaction_master.id =  transaction_master_with_billing.transaction_master_id where billing_id = "'.$billing_id[$i].'"');

                    if ($check_is_come_from_services->num_rows() > 0) 
                    {
                        $check_is_come_from_services = $check_is_come_from_services->result_array();
                        $billing_info_array = $this->db_model->get_edit_transaction_bill_service($billing_id[$i], $check_is_come_from_services[0]["transaction_task_id"]); //incorp havent finish
                    }
                    else
                    {
                        $billing_info_array =$this->db_model->get_edit_bill_service($billing_id[$i]);
                    }

                    $can_import_to_qb = true;
                    $service_not_in_qb = "";

                    for($k = 0; $k < count($billing_info_array); $k++)
                    {
                        $billing_info_array[$k] = (array)$billing_info_array[$k];
                        if($billing_info_array[$k]["qb_item_id"] == 0)
                        {
                            $service_not_in_qb = $billing_info_array[$k]["service_name"];
                            $can_import_to_qb = false;
                            break;
                        }
                    }

                    // print_r($billing_info_array);   
                    // exit; 

                    if($can_import_to_qb) {
                        //Save string to log, use FILE_APPEND to append.
                        // file_put_contents('./log/billing_'.$logDate.'.log', $billing_info_array, FILE_APPEND);

                        $billing_submit_status = $this->import_invoice_to_qb($billing_id[$i], $billing_info_array);
                        echo json_encode($billing_submit_status);
                    }
                    else
                    {
                        echo json_encode(array("Status" => 7, 'message' => 'This invoice cannot be import to Quickbook Online because ' . $service_not_in_qb .' services is not in Quickbook Online.', 'title' => 'Warning'));
                    }
                }
            }
        }
    }

    public function create_receipt_in_qb()
    {
        if($_POST["tab"] == "receipt")
        {
            $receipt_id = $_POST["receipt_id"];

            if(count($receipt_id) != 0)
            {
                for($i = 0; $i < count($receipt_id); $i++)
                {
                    $receipt_submit_status = $this->import_receipt_to_qb($receipt_id[$i], false);
                    echo $receipt_submit_status;
                }
            }
        }
    }

    public function check_cn_data_to_qb($credit_note_id, $import = null)
    {
        $check_is_come_from_services = $this->db->query('SELECT transaction_master_with_billing.*, transaction_master.transaction_task_id 
            FROM transaction_master_with_billing
            LEFT JOIN billing_credit_note_gst ON billing_credit_note_gst.id = "'.$credit_note_id.'" 
            LEFT JOIN transaction_master ON transaction_master.id =  transaction_master_with_billing.transaction_master_id
            WHERE transaction_master_with_billing.billing_id = billing_credit_note_gst.billing_id');

        if ($check_is_come_from_services->num_rows() > 0) 
        {
            $check_is_come_from_services = $check_is_come_from_services->result_array();

            if($check_is_come_from_services[0]["transaction_task_id"] != "1")
            {
                $qb_customer_id = ", client_qb_id.qb_customer_id";
                $left_join_client = " LEFT JOIN client ON client.company_code = billing.company_code LEFT JOIN client_qb_id ON client_qb_id.company_code = billing.company_code AND client_qb_id.currency_name = currency.currency AND client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";

                if($check_is_come_from_services[0]["transaction_task_id"] == "4" || $check_is_come_from_services[0]["transaction_task_id"] == "33" || $check_is_come_from_services[0]["transaction_task_id"] == "34")
                {
                    $left_join_client = $left_join_client." LEFT JOIN transaction_client_billing_info ON transaction_client_billing_info.id = billing_service.service 
                    LEFT JOIN our_service_info ON our_service_info.id = transaction_client_billing_info.service
                    LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";
                }
                else
                {
                    $left_join_client = $left_join_client." LEFT JOIN our_service_info ON our_service_info.id = billing_service.service LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";
                }
            }
            else
            {
                $qb_customer_id = ", transaction_client_qb_id.qb_customer_id";
                $left_join_client = " LEFT JOIN transaction_client ON transaction_client.company_code = billing.company_code AND transaction_client.deleted = 0
                LEFT JOIN transaction_client_billing_info ON transaction_client_billing_info.id = billing_service.service 
                LEFT JOIN our_service_info ON our_service_info.id = transaction_client_billing_info.service
                LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."'
                LEFT JOIN transaction_client_qb_id ON transaction_client_qb_id.company_code = billing.company_code AND transaction_client_qb_id.currency_name = currency.currency AND transaction_client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";
            }

            $credit_note_query = $this->db->query("SELECT 
            billing_credit_note_gst.*, billing_credit_note_gst_record.*, billing_credit_note_gst.billing_outstanding as cn_billing_outstanding, billing.qb_invoice_id, billing.company_name, billing.invoice_no, billing.amount as billing_amount, billing.outstanding as billing_outstanding, our_service_info.service_name, billing_credit_note_gst.id as billing_credit_note_gst_id, billing_credit_note_gst_record.id as billing_credit_note_gst_record_id, billing_service.invoice_description, our_service_qb_info.qb_item_id, gst_category.category as gst_category_name, currency.currency as currency_name".$qb_customer_id."
                FROM billing_credit_note_gst
                LEFT JOIN billing_credit_note_gst_record ON billing_credit_note_gst_record.credit_note_id = billing_credit_note_gst.id
                LEFT JOIN billing ON billing.id = billing_credit_note_gst.billing_id
                LEFT JOIN billing_service ON billing_service.id = billing_credit_note_gst_record.billing_service_id
                LEFT JOIN currency ON currency.id = billing_credit_note_gst.currency_id
                ".$left_join_client."
                LEFT JOIN gst_category ON gst_category.id = billing_service.gst_category_id
                WHERE billing_credit_note_gst.id = '".$credit_note_id."'");
        }
        else
        {
            $credit_note_query = $this->db->query("SELECT 
            billing_credit_note_gst.*, billing_credit_note_gst_record.*, billing_credit_note_gst.billing_outstanding as cn_billing_outstanding, billing.qb_invoice_id, client_qb_id.qb_customer_id, billing.company_name, billing.invoice_no, billing.amount as billing_amount, billing.outstanding as billing_outstanding, our_service_info.service_name, billing_credit_note_gst.id as billing_credit_note_gst_id, billing_credit_note_gst_record.id as billing_credit_note_gst_record_id, billing_service.invoice_description, our_service_qb_info.qb_item_id, currency.currency as currency_name,gst_category.category as gst_category_name
                FROM billing_credit_note_gst
                LEFT JOIN billing_credit_note_gst_record ON billing_credit_note_gst_record.credit_note_id = billing_credit_note_gst.id
                LEFT JOIN billing ON billing.id = billing_credit_note_gst.billing_id
                LEFT JOIN billing_service ON billing_service.id = billing_credit_note_gst_record.billing_service_id
                LEFT JOIN client_billing_info ON client_billing_info.id = billing_service.service
                LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service
                LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."'
                LEFT JOIN client ON client.company_code = billing.company_code AND client.deleted = 0
                LEFT JOIN currency ON currency.id = billing_credit_note_gst.currency_id
                LEFT JOIN gst_category ON gst_category.id = billing_service.gst_category_id
                LEFT JOIN client_qb_id ON client_qb_id.company_code = billing.company_code AND client_qb_id.currency_name = currency.currency AND client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."'
                WHERE billing_credit_note_gst.id = '".$credit_note_id."'");
        }

        if($import)   
        {
            return $credit_note_query;
        } 
        else
        {
            $credit_note_array = $credit_note_query->result_array();
            return $credit_note_array;
        }
    }

    public function create_credit_note_in_qb()
    {
        if($_POST["tab"] == "credit_note")
        {
            $credit_note_id = $_POST["credit_note_id"];

            if(count($credit_note_id) != 0)
            {
                for($i = 0; $i < count($credit_note_id); $i++)
                {
                    $credit_note_array = $this->check_cn_data_to_qb($credit_note_id[$i]);
                    
                    $can_import_to_qb = true;
                    $service_not_in_qb = "";

                    for($k = 0; $k < count($credit_note_array); $k++)
                    {
                        if($credit_note_array[$k]["qb_item_id"] == 0)
                        {
                            $service_not_in_qb = $credit_note_array[$k]["service_name"];
                            $can_import_to_qb = false;
                            break;
                        }
                    }

                    if($can_import_to_qb)
                    {
                        $cn_submit_status = $this->import_cn_to_qb($credit_note_id[$i], $credit_note_array);
                        echo json_encode($cn_submit_status);
                    }
                    else
                    {
                        echo json_encode(array("Status" => 8, 'message' => 'This invoice cannot be import to Quickbook Online because ' . $service_not_in_qb .' services is not in Quickbook Online.', 'title' => 'Error'));
                    }
                }
            }
        }
    }

    public function get_income_account($bank_name) //DepositToAccountRef
    {
        if($this->session->userdata('refresh_token_value'))
        {
            $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
            if($refresh_token_status)
            {
                /* API URL */
                if($bank_name == "SECURITY DEPOSIT")
                {
                    $url = $this->quickbookURL.'/v3/company/'.$this->session->userdata('qb_company_id')."/query?query=select%20*%20from%20Account%20WHERE%20Name='SECURITY%20DEPOSIT'";
                }
                else
                {
                    $url = $this->quickbookURL.'/v3/company/'.$this->session->userdata('qb_company_id').'/query?query=select%20*%20from%20Account%20WHERE%20Active=true%20STARTPOSITION%201%20MAXRESULTS%201000';
                }
                /* Init cURL resource */
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $url);
                /* Array Parameter Data */
                //$data = ['Authorization' => 'Bearer '.$this->session->userdata('access_token_value')];
                $authorization = "Authorization:Bearer ".$this->session->userdata('access_token_value');

                curl_setopt($ch, CURLOPT_POST, false);

                /* pass encoded JSON string to the POST fields */
                //curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(""));
                    
                /* set the content type json */
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type:text/plain', $authorization));
                    
                /* set return type json */
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    
                /* execute request */
                $result = curl_exec($ch);
                // print_r($result);
                /* close cURL resource */
                curl_close($ch);

                $xml_snippet = simplexml_load_string( $result );
                $json_convert = json_encode( $xml_snippet );
                $json = json_decode( $json_convert );

                $account = $json->QueryResponse->Account;

                if($bank_name == "SECURITY DEPOSIT")
                {
                    return $account->Id;
                }
                else
                {
                    for($t = 0; $t < count($account); $t++)
                    {
                        if(strpos($account[$t]->Name, $bank_name) !== false)
                        {
                            $accountID = $account[$t]->Id;
                            $accountName = $account[$t]->Name;
                        }
                    }

                    return $accountID;
                }
            }
        }
    }

    public function get_payment_method($payment_method_name) //PaymentMethodRef 
    {
        if($this->session->userdata('refresh_token_value'))
        {
            $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
            if($refresh_token_status)
            {
                /* API URL */
                $url = $this->quickbookURL.'/v3/company/'.$this->session->userdata('qb_company_id').'/query?query=select%20*%20from%20PaymentMethod';
                /* Init cURL resource */
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $url);
                /* Array Parameter Data */
                //$data = ['Authorization' => 'Bearer '.$this->session->userdata('access_token_value')];
                $authorization = "Authorization:Bearer ".$this->session->userdata('access_token_value');

                curl_setopt($ch, CURLOPT_POST, false);

                /* pass encoded JSON string to the POST fields */
                //curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(""));
                    
                /* set the content type json */
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type:text/plain', $authorization));
                    
                /* set return type json */
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    
                /* execute request */
                $result = curl_exec($ch);
                //print_r($result);
                /* close cURL resource */
                curl_close($ch);

                $xml_snippet = simplexml_load_string( $result );
                $json_convert = json_encode( $xml_snippet );
                $json = json_decode( $json_convert );
                
                $paymentMethod = $json->QueryResponse->PaymentMethod;

                for($t = 0; $t < count($paymentMethod); $t++)
                {
                    if($paymentMethod[$t]->Name === $payment_method_name)
                    {
                        $paymentMethodID = $paymentMethod[$t]->Id;
                        $paymentMethodName = $paymentMethod[$t]->Name;
                    }
                }

                return $paymentMethodID;
            }
        }
    }

    public function resend() 
   	{
   		$this->load->library('encryption');
   		$now = getDate();
		$current_date = DATE("Y-m-d",now());
		$number_of_invoice = 0;
   		//$current_date = '2020-03-01'; //16

   		// $q = $this->db->query("select recurring_billing.firm_id, recurring_billing.id, recurring_billing.company_code, recurring_billing.invoice_date, recurring_billing.invoice_no, recurring_billing.currency_id, recurring_billing.amount, recurring_billing.rate, recurring_billing.outstanding, recurring_billing.status, recurring_billing.recurring_status, recurring_billing.billing_period, recurring_billing.recu_invoice_issue_date, client.acquried_by, client.company_name, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, client.foreign_add_1, client.foreign_add_2, client.foreign_add_3, client.use_foreign_add_as_billing_add, currency.currency from recurring_billing left join client on client.company_code = recurring_billing.company_code left join currency on currency.id = recurring_billing.currency_id where client.acquried_by = '1' AND client.deleted != '1' AND client.status = '1' AND recurring_billing.status = '0' AND recurring_billing.amount != '0.00' and recurring_billing.id = 264");

   		// $q = $this->db->query("select recurring_billing.firm_id, recurring_billing.id, recurring_billing.company_code, recurring_billing.invoice_date, recurring_billing.invoice_no, recurring_billing.currency_id, recurring_billing.amount, recurring_billing.rate, recurring_billing.outstanding, recurring_billing.status, recurring_billing.recurring_status, recurring_billing.billing_period, recurring_billing.recu_invoice_issue_date, client.acquried_by, client.company_name, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, client.foreign_add_1, client.foreign_add_2, client.foreign_add_3, client.use_foreign_add_as_billing_add, currency.currency from recurring_billing left join client on client.company_code = recurring_billing.company_code left join currency on currency.id = recurring_billing.currency_id where client.acquried_by = '1' AND client.deleted != '1' AND client.status = '1' AND recurring_billing.status = '0' AND recurring_billing.amount != '0.00' and recurring_billing.id IN('71','98','356','349','318','88','110','141','338','329','58','76','95','119','343','325','63','81','102','129','361','348','321','70','87','107','138','336','355','312','116','94','118','342','324','62','80','100','128','313','347','320','69','86','106','136','335','354','315','74','93','114','341','326','61','79','125','346','319','68','115','105','135','334','352','316','73','92','113','340','327','60','78','97','122','345','322','67','83','104','134','333','350','317','72','89','112','142','339','328','59','77','96','120','344','323','64','82','103','130')");
		   $q = $this->db->query("select recurring_billing.firm_id, recurring_billing.id, recurring_billing.company_code, recurring_billing.invoice_date, recurring_billing.invoice_no, recurring_billing.currency_id, recurring_billing.amount, recurring_billing.rate, recurring_billing.outstanding, recurring_billing.status, recurring_billing.recurring_status, recurring_billing.billing_period, recurring_billing.recu_invoice_issue_date, client.acquried_by, client.company_name, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, client.foreign_add_1, client.foreign_add_2, client.foreign_add_3, client.use_foreign_add_as_billing_add, currency.currency from recurring_billing left join client on client.company_code = recurring_billing.company_code left join currency on currency.id = recurring_billing.currency_id where client.acquried_by = '1' AND client.deleted != '1' AND client.status = '1' AND recurring_billing.status = '0' AND recurring_billing.amount != '0.00' and recurring_billing.id IN('".$_POST["id"]."')");

   		 //send on = 17/1 // AND recurring_billing.recu_invoice_issue_date = '01/02/2020' // AND recurring_billing.firm_id = '18' // AND recurring_billing.firm_id = 21//  recurring_billing.recu_invoice_issue_date = '22/07/2020' AND //  AND recurring_billing.recu_invoice_issue_date = '01/01/2021' LIMIT 10

		$q = $q->result_array();

		//echo json_encode($q);

		for($t= 0; $t < count($q); $t++)
		{
			if($q[$t]["recurring_status"] != 0 && $q[$t]["billing_period"] != 1)
			{
				$invoice_issue_date = str_replace('/', '-', $q[$t]["recu_invoice_issue_date"]);
				$time_invoice_issue_date = strtotime($invoice_issue_date);
				$new_invoice_issue_date = date('Y-m-d',$time_invoice_issue_date);

				$invoice_issue_date_time = new DateTime($new_invoice_issue_date);
				//print_r($new_invoice_issue_date);
				///print_r(' Current Date: '.strtotime($current_date));
				//print_r(' Issue Date: '.strtotime($new_invoice_issue_date));
				//echo($new_invoice_issue_date == "2020-01-01");


				if(strtotime($current_date) >= strtotime($new_invoice_issue_date) && 100 > $number_of_invoice)
				{
					$q[$t]["company_name"] = $this->encryption->decrypt($q[$t]["company_name"]);
					$this->send_recurring_bill($q[$t], $q[$t]["id"], $q[$t]["firm_id"], $new_invoice_issue_date, $q[$t]["company_code"], $q[$t]["company_name"], $q[$t]["currency"], $q[$t]["billing_period"]); //$new_invoice_issue_date
					$number_of_invoice = $number_of_invoice + 1;
				}


				//print_r($q[$t]["company_name"]);
				// else 
				// {
				// 	if($q[$t]["billing_period"] == 2)
				// 	{
				// 		$after_one_month_issue_date = $this->MonthShifter($invoice_issue_date_time,1)->format(('Y-m-d'));
				// 		if($after_one_month_issue_date == $current_date)
				// 		{
				// 			$this->send_recurring_bill($q[$t], $q[$t]["id"], $q[$t]["firm_id"], $after_one_month_issue_date, $q[$t]["company_code"], $q[$t]["company_name"], $q[$t]["currency"], $q[$t]["billing_period"]);
				// 		}
				// 	}
				// 	else if($q[$t]["billing_period"] == 3)
				// 	{
				// 		$after_quarter_year_issue_date = $this->MonthShifter($invoice_issue_date_time,3)->format(('Y-m-d'));
				// 		if($after_quarter_year_issue_date == $current_date)
				// 		{
				// 			$this->send_recurring_bill($q[$t], $q[$t]["id"], $q[$t]["firm_id"], $after_quarter_year_issue_date, $q[$t]["company_code"], $q[$t]["company_name"], $q[$t]["currency"], $q[$t]["billing_period"]);
				// 		}
				// 	}
				// 	else if($q[$t]["billing_period"] == 4)
				// 	{
				// 		$after_half_year_issue_date = $this->MonthShifter($invoice_issue_date_time,6)->format(('Y-m-d'));
				// 		if($after_half_year_issue_date == $current_date)
				// 		{
				// 			$this->send_recurring_bill($q[$t], $q[$t]["id"], $q[$t]["firm_id"], $after_half_year_issue_date, $q[$t]["company_code"], $q[$t]["company_name"], $q[$t]["currency"], $q[$t]["billing_period"]);
				// 		}
				// 	}
				// 	else if($q[$t]["billing_period"] == 5)
				// 	{
				// 		$after_one_year_issue_date = $this->MonthShifter($invoice_issue_date_time,12)->format(('Y-m-d'));
				// 		if($after_one_year_issue_date == $current_date)
				// 		{
				// 			$this->send_recurring_bill($q[$t], $q[$t]["id"], $q[$t]["firm_id"], $after_one_year_issue_date, $q[$t]["company_code"], $q[$t]["company_name"], $q[$t]["currency"], $q[$t]["billing_period"]);
				// 		}
				// 	}
				// }
			}
		}
		
   	}
}
