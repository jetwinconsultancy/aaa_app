<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_voucher extends MY_Controller
{

    function __construct()
    {
    	parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        //$this->load->library('form_validation');
        $this->load->library(array('encryption', 'session', 'form_validation', 'zip'));
        $this->load->model(array('db_model', 'master_model'));
    }

    public function index($tab = NULL)
    {
        $bc = array(array('link' => '#', 'page' => lang('Payment')));
        $meta = array('page_title' => "Payment", 'bc' => $bc, 'page_name' => 'Payment');

        $this->data['active_tab'] = $this->session->userdata('tab_active');
        $this->session->unset_userdata('tab_active');

        if (isset($_POST['search'])) {
			if (isset($_POST['search']) && isset($_POST['type']))
			{
				$this->data['vendor'] = $this->db_model->getVendor($_SESSION['group_id'], $_POST['type'],$_POST['search']);
                $this->data['payment_voucher'] = $this->db_model->get_all_payment_voucher($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                $this->data['claim'] = $this->db_model->get_all_claim($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                $this->data['pv_receipt'] = $this->db_model->get_all_pv_receipt($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
			} 
		}
		else
		{
			$this->data['vendor'] = $this->db_model->getVendor($_SESSION['group_id']);
            $this->data['payment_voucher'] = $this->db_model->get_all_payment_voucher();
            $this->data['claim'] = $this->db_model->get_all_claim();
            $this->data['pv_receipt'] = $this->db_model->get_all_pv_receipt();
		}

        $this->page_construct('payment_voucher/payment_voucher_index', $meta, $this->data);
    }

    public function create_vendor()
    {
    	$bc = array(array('link' => '#', 'page' => lang('Create Vendor')));
        $meta = array('page_title' => 'Create Vendor', 'bc' => $bc, 'page_name' => 'Create Vendor');

        $this->session->set_userdata(array(
            'supplier_code'  => null,
        ));

        $p = new stdClass;
        
        $p->local_status = 'checked';
        $p->address_type = 'Local';

        $this->data['vendor'] = $p;

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Payment', base_url('payment_voucher'));
		$this->mybreadcrumb->add('Create Payment', base_url());

		$this->data['breadcrumbs'] = $this->mybreadcrumb->render();

		$this->page_construct('payment_voucher/create_edit_vendor.php', $meta, $this->data);
    }

    public function save_vendor()
    {
    	$this->form_validation->set_rules('vendor_code', 'Vendor Code', 'required');
        $this->form_validation->set_rules('vendor_company_name', 'Company Name', 'required');

        if(isset($_POST['vendor_postal_code']))
        {
            $this->form_validation->set_rules('vendor_postal_code', 'Postal Code', 'required');
        }
        if(isset($_POST['vendor_street_name']))
        {
            $this->form_validation->set_rules('vendor_street_name', 'Street Name', 'required');
        }
        if(isset($_POST['vendor_foreign_address1']))
        {
            $this->form_validation->set_rules('vendor_foreign_address1', 'Foreign Address', 'required');
        }

        if ($this->form_validation->run() == FALSE)
        {
        	$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $error = array(
                'vendor_code' => strip_tags(form_error('vendor_code')),
                //'vendor_registration_no' => strip_tags(form_error('vendor_registration_no')),
                'vendor_company_name' => strip_tags(form_error('vendor_company_name')),
                'vendor_postal_code' => strip_tags(form_error('vendor_postal_code')),
                'vendor_street_name' => strip_tags(form_error('vendor_street_name')),
                'vendor_foreign_address1' => strip_tags(form_error('vendor_foreign_address1')),

            );

            echo json_encode(array("Status" => 0, 'message' => 'Please complete all required field', 'title' => 'Error', "error" => $error, $this->data));
        }
        else
        {
        	$data['created_by']=$this->session->userdata('user_id');
			$data['firm_id']=$this->session->userdata('firm_id');

			$data['supplier_code']=$_POST['supplier_code'];
			$data['vendor_code']=strtoupper($_POST['vendor_code']);
			$data['company_name']=strtoupper($_POST['vendor_company_name']);
			$data['former_name']=strtoupper($_POST['vendor_former_name']);
            $data['address_type'] = $_POST['address_type'];
			$data['postal_code']=strtoupper($_POST['vendor_postal_code']);
			$data['street_name']=strtoupper($_POST['vendor_street_name']);
			$data['building_name']=strtoupper($_POST['vendor_building_name']);
			$data['unit_no1']=strtoupper($_POST['vendor_unit_no1']);
			$data['unit_no2']=strtoupper($_POST['vendor_unit_no2']);

            $data['foreign_address1']=strtoupper($_POST['vendor_foreign_address1']);
            $data['foreign_address2']=strtoupper($_POST['vendor_foreign_address2']);
            $data['foreign_address3']=strtoupper($_POST['vendor_foreign_address3']);

			$q = $this->db->get_where("vendor_info", array("supplier_code" => $_POST['supplier_code']));

			if (!$q->num_rows())
			{
				$this->db->insert("vendor_info",$data);	
				$change_cn = false; 
                $this->save_audit_trail("Payment", "Create Vendor", strtoupper($_POST['vendor_company_name'])." vendor is added.");
			} 
			else 
			{
				$old_vendor_data = $q->result_array();

				if($old_vendor_data[0]["company_name"] != $data['company_name'])
				{
					$data['former_name'] = $old_vendor_data[0]["company_name"]."\r\n".$data['former_name'];

					$change_cn = true; 
				}
				else
				{
					$change_cn = false; 
				}

				$this->db->update("vendor_info",$data,array("supplier_code" =>  $_POST['supplier_code']));
                $this->save_audit_trail("Payment", "Edit Vendor", strtoupper($_POST['vendor_company_name'])." vendor is edited.");
			}

			echo json_encode(array("Status" => 1,'message' => 'Information Updated', 'title' => 'Updated', 'change_company_name' => $change_cn));
        }
    }

    public function add_vendor_setup_info()
    {
    	$this->form_validation->set_rules('contact_email[]', 'Email', 'valid_email');
        $this->form_validation->set_rules('contact_phone[]', 'Phone Number', 'numeric');

        if(count($_POST['hidden_contact_phone']) > 1 && $_POST['contact_phone_primary'] == null)
        {
            $validate_contact_phone_primary = FALSE;
        }
        else
        {
            $validate_contact_phone_primary = TRUE;
        }

        if(count($_POST['contact_email']) > 1 && $_POST['contact_email_primary'] == null)
        {
            $validate_contact_email_primary = FALSE;
        }
        else
        {
            $validate_contact_email_primary = TRUE;
        }

        if ($this->form_validation->run() == FALSE || $validate_contact_phone_primary == FALSE || $validate_contact_email_primary == FALSE)
        {
        	if($validate_contact_phone_primary == FALSE)
            {
                $validate_contact_phone = "Please set the primary field.";
            }
            else
            {
                $validate_contact_phone = strip_tags(form_error('contact_phone[]'));
            }

            if($validate_contact_email_primary == FALSE)
            {
                $validate_contact_email = "Please set the primary field.";
            }
            else
            {
                $validate_contact_email = strip_tags(form_error('contact_email[]'));
            }

            $arr = array(

                'contact_phone' => $validate_contact_phone,
                'contact_email' => $validate_contact_email,
            );

            echo json_encode(array("Status" => 0, "error" => $arr, 'message' => 'Please complete all required field', 'title' => 'Error'));
        }
        else
        {
        	$vendor_contact_info['supplier_code'] = $_POST['supplier_code'];
			$vendor_contact_info['name'] = strtoupper($_POST['contact_name']);

			$query = $this->db->get_where("vendor_contact_info", array("supplier_code" => $_POST['supplier_code']));

			if (!$query->num_rows())
			{				
				$this->db->insert("vendor_contact_info",$vendor_contact_info);
				$vendor_contact_info_id = $this->db->insert_id();

                $q = $this->db->get_where("vendor_info", array("supplier_code" => $_POST['supplier_code']));

                if ($q->num_rows())
                {
                    $vendor_array = $q->result_array();
                    $this->save_audit_trail("Payment", "Create Vendor", strtoupper($vendor_array[0]['company_name'])." vendor contact information is added.");
                }

				for($g = 0; $g < count($_POST['hidden_contact_phone']); $g++)
                {
                    if($_POST['hidden_contact_phone'][$g] != "")
                    {
                        $contactPhone['vendor_contact_info_id'] = $vendor_contact_info_id;
                        $contactPhone['phone'] = strtoupper($_POST['hidden_contact_phone'][$g]);
                        if($_POST['contact_phone_primary'] == $_POST['hidden_contact_phone'][$g])
                        {
                            $contactPhone['primary_phone'] = 1;
                        }
                        else
                        {
                            $contactPhone['primary_phone'] = 0;
                        }
                        $this->db->insert('vendor_contact_info_phone', $contactPhone);
                    }
                }

                for($g = 0; $g < count($_POST['contact_email']); $g++)
                {
                    if($_POST['contact_email'][$g] != "")
                    {
                        $contactEmail['vendor_contact_info_id'] = $vendor_contact_info_id;
                        $contactEmail['email'] = strtoupper($_POST['contact_email'][$g]);
                        if($_POST['contact_email_primary'] == $_POST['contact_email'][$g])
                        {
                            $contactEmail['primary_email'] = 1;
                        }
                        else
                        {
                            $contactEmail['primary_email'] = 0;
                        }
                        $this->db->insert('vendor_contact_info_email', $contactEmail);
                    }
                }
			} 
			else 
			{	
				$this->db->where(array("supplier_code" => $_POST['supplier_code']));
				$this->db->update("vendor_contact_info",$vendor_contact_info);

                $q = $this->db->get_where("vendor_info", array("supplier_code" => $_POST['supplier_code']));

                if ($q->num_rows())
                {
                    $vendor_array = $q->result_array();
                    $this->save_audit_trail("Payment", "Edit Vendor", strtoupper($vendor_array[0]['company_name'])." vendor contact information is edited.");
                }

				$vendor_contact_information = $query->result_array(); 
				$vendor_contact_info_id = $vendor_contact_information[0]["id"];

				$this->db->delete("vendor_contact_info_phone",array('vendor_contact_info_id'=>$vendor_contact_info_id));

				for($g = 0; $g < count($_POST['hidden_contact_phone']); $g++)
                {
                    if($_POST['hidden_contact_phone'][$g] != "")
                    {
                        $contactPhone['vendor_contact_info_id'] = $vendor_contact_info_id;
                        $contactPhone['phone'] = strtoupper($_POST['hidden_contact_phone'][$g]);
                        if($_POST['contact_phone_primary'] == $_POST['hidden_contact_phone'][$g])
                        {
                            $contactPhone['primary_phone'] = 1;
                        }
                        else
                        {
                            $contactPhone['primary_phone'] = 0;
                        }
                        $this->db->insert('vendor_contact_info_phone', $contactPhone);
                    }
                }

                $this->db->delete("vendor_contact_info_email",array('vendor_contact_info_id'=>$vendor_contact_info_id));

                for($g = 0; $g < count($_POST['contact_email']); $g++)
                {
                    if($_POST['contact_email'][$g] != "")
                    {
                        $contactEmail['vendor_contact_info_id'] = $vendor_contact_info_id;
                        $contactEmail['email'] = strtoupper($_POST['contact_email'][$g]);
                        if($_POST['contact_email_primary'] == $_POST['contact_email'][$g])
                        {
                            $contactEmail['primary_email'] = 1;
                        }
                        else
                        {
                            $contactEmail['primary_email'] = 0;
                        }
                        $this->db->insert('vendor_contact_info_email', $contactEmail);
                    }
                }
			}

			echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
        }
    }

    public function edit_vendor($id = null, $tab = null)
    {
    	$this->data['vendor'] = $this->db_model->getVendorID($id);
    	$supplier_code =$this->data['vendor']->supplier_code;
    	$this->data['vendor_contact_info'] = $this->db_model->getVendorContact($supplier_code);

		if($tab == "vendor_setup")
		{
			$this->data['tab'] = "vendorSetup";
		}

        if ($this->data['vendor']->address_type == "Local")
        {
            $this->data['vendor']->local_status = 'checked';
        }
        else
        {
            $this->data['vendor']->foreign_status = 'checked';
        }

		$this->session->set_userdata('supplier_code', $this->data['vendor']->supplier_code);

		$this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Vendor', base_url('payment_voucher'));
		$this->mybreadcrumb->add('Edit Vendor - '.$this->data['vendor']->company_name.'', base_url());
		$this->data['breadcrumbs'] = $this->mybreadcrumb->render();

		$this->data['first_time'] = true;

        $bc = array(array('link' => '#', 'page' => 'Edit Vendor'));
        $meta = array('page_title' => 'Edit Vendor', 'bc' => $bc, 'page_name' => 'Edit Vendor');

        $this->page_construct('payment_voucher/create_edit_vendor.php', $meta, $this->data);
    }

    public function approve_claim()
    {
        if($_POST["tab"] == "claim")
        {
            $firm_result = $this->db->query("select * from firm where id = '".$this->session->userdata('firm_id')."'");
            $firm_array = $firm_result->result_array();

            if($firm_array[0]["branch_name"] != "")
            {
                $branch_name = "(".$firm_array[0]["branch_name"].")";
            }
            else
            {
                $branch_name = "";
            }

            $claim_id = $_POST["claim_id"];
            // 3 is approve
            $this->db->set('status', 3);
            $this->db->set('approved_by', $this->session->userdata('user_id'));
            $this->db->where('id', $claim_id);
            $this->db->update('claim');

            $q = $this->db->get_where("claim", array("id" => $_POST['claim_id']));

            if ($q->num_rows())
            {
                $claim_array = $q->result_array();

                $this->save_audit_trail("Payment", "Index", $claim_array[0]['claim_no']." claim is approved in ".$firm_array[0]["name"].$branch_name.".");
            }

            echo json_encode(array("Status" => 1, 'message' => 'Information Approved', 'title' => 'Deleted'));
        }
        $this->session->set_userdata("tab_active",$_POST["tab"]);
    }

    public function approve_pv()
    {
        if($_POST["tab"] == "payment_voucher")
        {
            $firm_result = $this->db->query("select * from firm where id = '".$this->session->userdata('firm_id')."'");
            $firm_array = $firm_result->result_array();

            if($firm_array[0]["branch_name"] != "")
            {
                $branch_name = "(".$firm_array[0]["branch_name"].")";
            }
            else
            {
                $branch_name = "";
            }

            $payment_voucher_id = $_POST["payment_voucher_id"];
            // 3 is approve
            $this->db->set('status', 3);
            $this->db->set('approved_by', $this->session->userdata('user_id'));
            $this->db->where('id', $payment_voucher_id);
            $this->db->update('payment_voucher');

            $q = $this->db->get_where("payment_voucher", array("id" => $_POST['payment_voucher_id']));

            if ($q->num_rows())
            {
                $payment_voucher_array = $q->result_array();

                $this->save_audit_trail("Payment", "Index", $payment_voucher_array[0]['payment_voucher_no']." payment voucher is approved in ".$firm_array[0]["name"].$branch_name.".");
            }

            echo json_encode(array("Status" => 1, 'message' => 'Information Approved', 'title' => 'Deleted'));
        }
        $this->session->set_userdata("tab_active",$_POST["tab"]);
    }

    public function approve_pv_receipt()
    {
        if($_POST["tab"] == "pv_receipt")
        {
            $firm_result = $this->db->query("select * from firm where id = '".$this->session->userdata('firm_id')."'");
            $firm_array = $firm_result->result_array();

            if($firm_array[0]["branch_name"] != "")
            {
                $branch_name = "(".$firm_array[0]["branch_name"].")";
            }
            else
            {
                $branch_name = "";
            }

            $payment_receipt_id = $_POST["payment_receipt_id"];
            // 3 is approve
            $this->db->set('status', 3);
            $this->db->set('approved_by', $this->session->userdata('user_id'));
            $this->db->where('id', $payment_receipt_id);
            $this->db->update('payment_receipt');

            $q = $this->db->get_where("payment_receipt", array("id" => $_POST['payment_receipt_id']));

            if ($q->num_rows())
            {
                $payment_receipt_array = $q->result_array();

                $this->save_audit_trail("Payment", "Index", $payment_receipt_array[0]['receipt_no']." payment receipt is approved in ".$firm_array[0]["name"].$branch_name.".");
            }

            echo json_encode(array("Status" => 1, 'message' => 'Information Approved', 'title' => 'Deleted'));
        }
        $this->session->set_userdata("tab_active",$_POST["tab"]);
    }

    public function cancel_claim()
    {
        if($_POST["tab"] == "claim")
        {
            $firm_result = $this->db->query("select * from firm where id = '".$this->session->userdata('firm_id')."'");
            $firm_array = $firm_result->result_array();

            if($firm_array[0]["branch_name"] != "")
            {
                $branch_name = "(".$firm_array[0]["branch_name"].")";
            }
            else
            {
                $branch_name = "";
            }

            $claim_id = $_POST["claim_id"];
            // 2 is cancel
            $this->db->set('status', 2);
            $this->db->set('cancel_reason', $_POST["cancel_reason"]);
            $this->db->where('id', $claim_id);
            $this->db->update('claim');

            $q = $this->db->get_where("claim", array("id" => $_POST['claim_id']));

            if ($q->num_rows())
            {
                $claim_array = $q->result_array();

                $this->save_audit_trail("Payment", "Index", $claim_array[0]['claim_no']." claim is cancelled in ".$firm_array[0]["name"].$branch_name.".");
            }

            echo json_encode(array("Status" => 1, 'message' => 'Information Cancelled', 'title' => 'Deleted'));
        }
        $this->session->set_userdata("tab_active",$_POST["tab"]);
    }

    public function cancel_pv()
    {
        if($_POST["tab"] == "payment_voucher")
        {
            $firm_result = $this->db->query("select * from firm where id = '".$this->session->userdata('firm_id')."'");
            $firm_array = $firm_result->result_array();

            if($firm_array[0]["branch_name"] != "")
            {
                $branch_name = "(".$firm_array[0]["branch_name"].")";
            }
            else
            {
                $branch_name = "";
            }

            $payment_voucher_id = $_POST["payment_voucher_id"];
            // 2 is cancel
            $this->db->set('status', 2);
            $this->db->set('cancel_reason', $_POST["cancel_reason"]);
            $this->db->where('id', $payment_voucher_id);
            $this->db->update('payment_voucher');

            $q = $this->db->get_where("payment_voucher", array("id" => $_POST['payment_voucher_id']));

            if ($q->num_rows())
            {
                $payment_voucher_array = $q->result_array();

                $this->save_audit_trail("Payment", "Index", $payment_voucher_array[0]['payment_voucher_no']." payment voucher is rejected in ".$firm_array[0]["name"].$branch_name.".");
            }

            $query_billing_credit_note_gst_with_pv = $this->db->query("select billing_credit_note_gst_with_pv.*, billing_credit_note_gst.cn_out_of_balance, billing_credit_note_gst.previous_cn_out_of_balance from billing_credit_note_gst_with_pv LEFT JOIN billing_credit_note_gst ON billing_credit_note_gst.id = billing_credit_note_gst_with_pv.billing_credit_note_gst_id where billing_credit_note_gst_with_pv.pv_id ='".$payment_voucher_id."' ORDER BY billing_credit_note_gst_with_pv.id");

            if($query_billing_credit_note_gst_with_pv->num_rows() > 0)
            {
                $query_billing_credit_note_gst_with_pv = $query_billing_credit_note_gst_with_pv->result_array();

                foreach ($query_billing_credit_note_gst_with_pv as $key => $value) {
                    
                    $billing_credit_note_gst["cn_out_of_balance"] = $value['previous_cn_out_of_balance_amt'];

                    $this->db->update("billing_credit_note_gst", $billing_credit_note_gst, array("id" => $value['billing_credit_note_gst_id']));
                }
            }

            echo json_encode(array("Status" => 1, 'message' => 'Information Cancelled', 'title' => 'Deleted'));
        }
        $this->session->set_userdata("tab_active",$_POST["tab"]);
    }

    public function cancel_pv_receipt()
    {
        if($_POST["tab"] == "pv_receipt")
        {
            $firm_result = $this->db->query("select * from firm where id = '".$this->session->userdata('firm_id')."'");
            $firm_array = $firm_result->result_array();

            if($firm_array[0]["branch_name"] != "")
            {
                $branch_name = "(".$firm_array[0]["branch_name"].")";
            }
            else
            {
                $branch_name = "";
            }

            $payment_receipt_id = $_POST["payment_receipt_id"];
            // 2 is cancel
            $this->db->set('status', 2);
            $this->db->set('cancel_reason', $_POST["cancel_reason"]);
            $this->db->where('id', $payment_receipt_id);
            $this->db->update('payment_receipt');

            $q = $this->db->get_where("payment_receipt", array("id" => $_POST['payment_receipt_id']));

            if ($q->num_rows())
            {
                $payment_receipt_array = $q->result_array();

                $this->save_audit_trail("Payment", "Index", $payment_receipt_array[0]['receipt_no']." payment receipt is cancelled in ".$firm_array[0]["name"].$branch_name.".");
            }

            echo json_encode(array("Status" => 1, 'message' => 'Information Cancelled', 'title' => 'Deleted'));
        }
        $this->session->set_userdata("tab_active",$_POST["tab"]);
    }

    public function delete_vendor()
    {   
        $firm_result = $this->db->query("select * from firm where id = '".$this->session->userdata('firm_id')."'");
        $firm_array = $firm_result->result_array();

        if($firm_array[0]["branch_name"] != "")
        {
            $branch_name = "(".$firm_array[0]["branch_name"].")";
        }
        else
        {
            $branch_name = "";
        }

        if($_POST["tab"] == "vendor_info")
        {
            $vendor_id = $_POST["vendor_id"];

            $this->db->set('deleted', 1);
            $this->db->where('id', $vendor_id);
            $this->db->update('vendor_info');

            $q = $this->db->get_where("vendor_info", array("id" => $_POST['vendor_id']));

            if ($q->num_rows())
            {
                $vendor_array = $q->result_array();
                $this->save_audit_trail("Payment", "Index", strtoupper($vendor_array[0]['company_name'])." vendor is deleted.");
            }

            echo json_encode(array("Status" => 1, 'message' => 'Information Deleted', 'title' => 'Deleted'));
        }
        elseif($_POST["tab"] == "payment_voucher")
        {
            $payment_voucher_id = $_POST["payment_voucher_id"];

            $this->db->set('status', 1);
            $this->db->where('id', $payment_voucher_id);
            $this->db->update('payment_voucher');

            $q = $this->db->get_where("payment_voucher", array("id" => $_POST['payment_voucher_id']));

            if ($q->num_rows())
            {
                $payment_voucher_array = $q->result_array();

                $this->save_audit_trail("Payment", "Index", $payment_voucher_array[0]['payment_voucher_no']." payment voucher is deleted in ".$firm_array[0]["name"].$branch_name.".");
            }

            echo json_encode(array("Status" => 1, 'message' => 'Information Deleted', 'title' => 'Deleted'));
        }
        elseif($_POST["tab"] == "claim")
        {
            $claim_id = $_POST["claim_id"];

            $this->db->set('status', 1);
            $this->db->where('id', $claim_id);
            $this->db->update('claim');

            $q = $this->db->get_where("claim", array("id" => $_POST['claim_id']));

            if ($q->num_rows())
            {
                $claim_array = $q->result_array();

                $this->save_audit_trail("Payment", "Index", $claim_array[0]['claim_no']." claim is deleted in ".$firm_array[0]["name"].$branch_name.".");
            }

            echo json_encode(array("Status" => 1, 'message' => 'Information Deleted', 'title' => 'Deleted'));
        }
        elseif($_POST["tab"] == "pv_receipt")
        {
            $payment_receipt_id = $_POST["payment_receipt_id"];

            $this->db->set('status', 1);
            $this->db->where('id', $payment_receipt_id);
            $this->db->update('payment_receipt');

            $q = $this->db->get_where("payment_receipt", array("id" => $_POST['payment_receipt_id']));

            if ($q->num_rows())
            {
                $payment_receiptm_array = $q->result_array();

                $this->save_audit_trail("Payment", "Index", $payment_receiptm_array[0]['receipt_no']." payment receipt is deleted in ".$firm_array[0]["name"].$branch_name.".");
            }

            echo json_encode(array("Status" => 1, 'message' => 'Information Deleted', 'title' => 'Deleted'));
        }

        $this->session->set_userdata("tab_active",$_POST["tab"]);
    }

    public function create_pv_receipt()
    {
        $meta = array('page_title' => "Create Receipt", 'page_name' => 'Create Receipt');
        //$this->session->unset_userdata('billing_company_code');
        $this->session->unset_userdata('billing_currency');

        $this->session->set_userdata("tab_active", "pv_receipt");

        $this->data['firm_info'] = $this->master_model->get_firm_info();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Payment', base_url('payment_voucher'));
        $this->mybreadcrumb->add('Create Receipt', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        // $this->data['page_name'] = 'Clients';
        $this->page_construct('payment_voucher/create_payment_receipt.php', $meta, $this->data);
    }

    public function create_payment_voucher()
    {
        $bc = array(array('link' => '#', 'page' => 'Create Payment'));
        $meta = array('page_title' => "Create Payment", 'bc' => $bc, 'page_name' => 'Create Payment');
        //$this->session->unset_userdata('billing_company_code');
        $this->session->unset_userdata('billing_currency');

        $this->session->set_userdata("tab_active", "payment_voucher");
        $this->data['firm_info'] = $this->master_model->get_firm_info();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Payment', base_url('payment_voucher'));
        $this->mybreadcrumb->add('Create Payment', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        // $this->data['page_name'] = 'Clients';
        $this->page_construct('payment_voucher/create_payment_voucher.php', $meta, $this->data);
    }

    public function create_claim()
    {
        $bc = array(array('link' => '#', 'page' => 'Create Claim'));
        $meta = array('page_title' => "Create Claim", 'bc' => $bc, 'page_name' => 'Create Claim');
        //$this->session->unset_userdata('billing_company_code');
        $this->session->unset_userdata('billing_currency');

        $this->session->set_userdata("tab_active", "claim");
        $this->data['firm_info'] = $this->master_model->get_firm_info();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Claim', base_url('payment_voucher'));
        $this->mybreadcrumb->add('Create Claim', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        // $this->data['page_name'] = 'Clients';
        $this->page_construct('payment_voucher/create_claim.php', $meta, $this->data);
    }

    public function get_payment_receipt_no()
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

        $query_payment_receipt_no = $this->db->query("select id, receipt_no, MAX(CAST(SUBSTRING(receipt_no, -4) AS UNSIGNED)) as latest_receipt_no from payment_receipt where YEAR(payment_receipt.created_at) = ".$current_year." and ".$where." AND status != '1' GROUP BY receipt_no ORDER BY latest_receipt_no DESC LIMIT 1");

        if ($query_payment_receipt_no->num_rows() > 0) 
        {
            $query_payment_receipt_no = $query_payment_receipt_no->result_array();

            $last_section_payment_receipt_no = (string)$query_payment_receipt_no[0]["receipt_no"];

            $number = substr_replace($last_section_payment_receipt_no, "", -4).(str_pad((int)(substr($last_section_payment_receipt_no, -4)) + 1, 4, '0', STR_PAD_LEFT));
        }
        else
        {
            $number = "RV-".date("Y")."-M".str_pad(1,4,"0",STR_PAD_LEFT);
        }

        echo json_encode(array("receipt_no" => $number));
    }

    public function get_payment_voucher_no()
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
        
        $query_payment_voucher_no = $this->db->query("select id, payment_voucher_no, MAX(CAST(SUBSTRING(payment_voucher_no, -4) AS UNSIGNED)) as latest_payment_voucher_no from payment_voucher where YEAR(payment_voucher.created_at) = ".$current_year." and ".$where." AND status != '1' GROUP BY payment_voucher_no ORDER BY latest_payment_voucher_no DESC LIMIT 1");

        if ($query_payment_voucher_no->num_rows() > 0) 
        {
            $query_payment_voucher_no = $query_payment_voucher_no->result_array();

            $last_section_payment_voucher_no = (string)$query_payment_voucher_no[0]["payment_voucher_no"];

            $number = substr_replace($last_section_payment_voucher_no, "", -4).(str_pad((int)(substr($last_section_payment_voucher_no, -4)) + 1, 4, '0', STR_PAD_LEFT));
        }
        else
        {
            $number = "PV-".date("Y")."-".str_pad(1,4,"0",STR_PAD_LEFT);
        }

        echo json_encode(array("payment_voucher_no" => $number));
    }

    public function get_claim_no()
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

        $query_claim_no = $this->db->query("select id, claim_no, MAX(CAST(SUBSTRING(claim_no, -4) AS UNSIGNED)) as latest_claim_no from claim where YEAR(claim.created_at) = ".$current_year." and ".$where." AND status != '1' GROUP BY claim_no ORDER BY latest_claim_no DESC LIMIT 1");

        if ($query_claim_no->num_rows() > 0) 
        {
            $query_claim_no = $query_claim_no->result_array();

            $last_section_claim_no = (string)$query_claim_no[0]["claim_no"];

            $number = substr_replace($last_section_claim_no, "", -4).(str_pad((int)(substr($last_section_claim_no, -4)) + 1, 4, '0', STR_PAD_LEFT));
        }
        else
        {
            $number = "PV-".date("Y")."-C".str_pad(1,4,"0",STR_PAD_LEFT);
        }

        echo json_encode(array("claim_no" => $number));
    }

    public function get_client_address()
    {   
        $company_code = $_POST["company_code"];

         $q = $this->db->query("select client.company_name, client.company_code, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, client.foreign_add_1, client.foreign_add_2, client.foreign_add_3, client.use_foreign_add_as_billing_add from client where company_code='".$company_code."'");

        $q = $q->result_array();

        if($q[0]["use_foreign_add_as_billing_add"] == 1)
        {
            if(!empty($q[0]["foreign_add_1"]))
            {
                $comma1 = $q[0]["foreign_add_1"] .'
';
            }
            else
            {
                $comma1 = '';
            }

            if(!empty($q[0]["foreign_add_2"]))
            {
                $comma2 = $comma1 . $q[0]["foreign_add_2"] .'
';
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
        

        echo json_encode(array("Status" => 1, "address" => $address, 'postal_code' => $q[0]["postal_code"], 'street_name' => $q[0]["street_name"], 'building_name' => $q[0]["building_name"], 'unit_no1' => $q[0]["unit_no1"], 'unit_no2' => $q[0]["unit_no2"], 'foreign_add_1' => $q[0]["foreign_add_1"], 'foreign_add_2' => $q[0]["foreign_add_2"], 'foreign_add_3' => $q[0]["foreign_add_3"], 'unassign_amount' => $this->db_model->get_unassign_amount($company_code)));
    }

    public function get_vendor_address()
    {
        $supplier_code = $_POST["supplier_code"];

         $q = $this->db->query("select vendor_info.company_name, vendor_info.supplier_code, vendor_info.postal_code, vendor_info.street_name, vendor_info.building_name, vendor_info.unit_no1, vendor_info.unit_no2, vendor_info.foreign_address1, vendor_info.foreign_address2, vendor_info.foreign_address3, vendor_info.address_type from vendor_info where supplier_code='".$supplier_code."'");

        $q = $q->result_array();

        if($q[0]["address_type"] == "Foreign")
        {
            if(!empty($q[0]["foreign_address1"]))
            {
                $comma1 = $q[0]["foreign_address1"] .'
';
            }
            else
            {
                $comma1 = '';
            }

            if(!empty($q[0]["foreign_address2"]))
            {
                $comma2 = $comma1 . $q[0]["foreign_address2"] .'
';
            }
            else
            {
                $comma2 = $comma1 . '';
            }
            $address = $comma2.$q[0]["foreign_address3"];
        }
        else
        {
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
        

        echo json_encode(array("Status" => 1, "address" => $address, 'postal_code' => $q[0]["postal_code"], 'street_name' => $q[0]["street_name"], 'building_name' => $q[0]["building_name"], 'unit_no1' => $q[0]["unit_no1"], 'unit_no2' => $q[0]["unit_no2"], 'foreign_add_1' => $q[0]["foreign_address1"], 'foreign_add_2' => $q[0]["foreign_address2"], 'foreign_add_3' => $q[0]["foreign_address3"]));
    }

    public function save_receipt_cheque()
    {
        $receipt_id = $_POST["receipt_cheque_id"];
        $receipt['bank_acc_id'] = $_POST["bank_account"];
        $receipt['cheque_number'] = $_POST["cheque_number"];
        $receipt['status'] = 4;

        $receipt_result = $this->db->query("select * from payment_receipt where id='".$receipt_id."'");

        $receipt_result = $receipt_result->result_array();

        if($receipt_result)
        {
            $this->db->update("payment_receipt",$receipt,array("id" => $receipt_result[0]['id']));
        }

        $this->session->set_userdata("tab_active", "pv_receipt");

        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
    }

    public function save_claim_cheque()
    {
        $claim_id = $_POST["claim_cheque_id"];
        $claim['bank_acc_id'] = $_POST["bank_account"];
        $claim['cheque_number'] = $_POST["cheque_number"];
        $claim['status'] = 4;

        $claim_result = $this->db->query("select * from claim where id='".$claim_id."'");

        $claim_result = $claim_result->result_array();

        if($claim_result)
        {
            $this->db->update("claim",$claim,array("id" => $claim_result[0]['id']));
        }

        $this->session->set_userdata("tab_active", "claim");

        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
    }

    public function save_payment_cheque()
    {
        $payment_id = $_POST["payment_cheque_id"];
        $payment['bank_acc_id'] = $_POST["bank_account"];
        $payment['cheque_number'] = $_POST["cheque_number"];
        $payment['status'] = 4;

        $payment_result = $this->db->query("select * from payment_voucher where id='".$payment_id."'");

        $payment_result = $payment_result->result_array();

        if($payment_result)
        {
            $this->db->update("payment_voucher",$payment,array("id" => $payment_result[0]['id']));
        }

        $this->session->set_userdata("tab_active", "payment_voucher");

        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
    }

    public function save_claim()
    {
        $firm_result = $this->db->query("select * from firm where id = '".$this->session->userdata('firm_id')."'");
        $firm_array = $firm_result->result_array();

        if($firm_array[0]["branch_name"] != "")
        {
            $branch_name = "(".$firm_array[0]["branch_name"].")";
        }
        else
        {
            $branch_name = "";
        }

        $user_id = $_POST["user_name"];
        $claim_date = $_POST["claim_date"];
        $currency = $_POST["currency"];
        $bank_acc_id = $_POST["bank_account"];
        $cheque_number = $_POST["cheque_number"];
        $claim_no = $_POST["claim_no"];
        $previous_claim_no = $_POST["previous_claim_no"];
        $claim_service_id = $_POST["claim_service_id"];
        $billing_service_id = array_values($_POST["billing_service_id"]);
        $amount = array_values($_POST["amount"]);
        $claim_description = array_values($_POST["claim_description"]);
        $type = array_values($_POST["type"]);
        $client = array_values($_POST["client"]);
        $client_name = array_values($_POST["hidden_client_name"]);
        $rate = $_POST["rate"];
        $hidden_attachment = array_values($_POST["hidden_attachment"]);
        $grand_total = $_POST["grand_total"];
        $user_name = $_POST["user_name_text"];

        $claim_result = $this->db->query("select claim.*, claim_service.id as claim_service_id from claim left join claim_service on claim_service.claim_id = claim.id where user_id='".$user_id."' AND claim_no = '".$previous_claim_no."' AND status = '0' AND firm_id = '".$this->session->userdata('firm_id')."'");

        $claim_result = $claim_result->result_array();

        if($claim_result)
        {
            $check_claim_id_result = $this->db->query("select * from claim where claim_no = '".$claim_no."' AND status = '0' AND id != '".$claim_result[0]['id']."' AND firm_id = '".$this->session->userdata('firm_id')."'");

            $check_claim_id_result = $check_claim_id_result->result_array();

            if(!$check_claim_id_result)
            {
                $new_amount = (float)str_replace(',', '', $grand_total);

                $claim['claim_no'] = $claim_no;
                $claim['currency_id'] = $currency;
                $claim['amount'] = $new_amount;
                $claim['rate'] = $rate;
                $claim['bank_acc_id'] = $bank_acc_id;
                $claim['cheque_number'] = $cheque_number;

                $this->db->delete('claim_service', array('claim_id' => $claim_result[0]['id']));

                $this->db->update("claim",$claim,array("id" => $claim_result[0]['id']));

                $this->save_audit_trail("Payment", "Edit Claim", $claim_no." claim is edited in ".$firm_array[0]["name"].$branch_name.".");

                $claim_service['claim_id'] = $claim_result[0]['id'];

                $can_insert_claim_service = true;
            }
            else
            {
                $can_insert_claim_service = false;
            }
        }
        else
        {
            $check_claim_id_result = $this->db->query("select * from claim where claim_no = '".$claim_no."' AND status = '0' AND firm_id = '".$this->session->userdata('firm_id')."'");

            $check_claim_id_result = $check_claim_id_result->result_array();

            if(!$check_claim_id_result)
            {
                $claim['claim_no'] = $claim_no;
                $claim['firm_id'] = $this->session->userdata("firm_id");
                $claim['user_id'] = $user_id;
                $claim['user_name'] = $user_name;
                $claim['claim_date'] = $claim_date;
                $claim['rate'] = $rate;
                $claim['bank_acc_id'] = $bank_acc_id;
                $claim['cheque_number'] = $cheque_number;
                $claim['amount'] = 0;

                for($p = 0; $p < count($amount); $p++)
                {
                    $claim['amount'] = $claim['amount'] + ((1+($gst_rate / 100)) * (float)str_replace(',', '', $amount[$p]));
                }
                
                $claim['currency_id'] = $currency;

                $this->db->insert("claim",$claim);
                $claim_service['claim_id'] = $this->db->insert_id();

                $this->save_audit_trail("Payment", "Create Claim", $claim_no." claim is added in ".$firm_array[0]["name"].$branch_name.".");

                $can_insert_claim_service = true;
            }
            else
            {
                $can_insert_claim_service = false;
            } 
        }

        if($can_insert_claim_service)
        {
            for($k = 0; $k < count($amount); $k++)
            {
                $claim_service['claim_date'] = $claim_date;
                $claim_service['type_id'] = $type[$k];
                $claim_service['company_code'] = $client[$k];
                $claim_service['client_name'] = $client_name[$k];
                $claim_service['claim_description'] = $claim_description[$k];
                $claim_service['amount'] = (float)str_replace(',', '', $amount[$k]);
                $filesCount = count($_FILES['attachment']['name'][$k]);
                $pv_attachment = array();

                for($i = 0; $i < $filesCount; $i++)
                {   
                    $_FILES['uploadimage']['name'] = $_FILES['attachment']['name'][$k][$i];
                    $_FILES['uploadimage']['type'] = $_FILES['attachment']['type'][$k][$i];
                    $_FILES['uploadimage']['tmp_name'] = $_FILES['attachment']['tmp_name'][$k][$i];
                    $_FILES['uploadimage']['error'] = $_FILES['attachment']['error'][$k][$i];
                    $_FILES['uploadimage']['size'] = $_FILES['attachment']['size'][$k][$i];

                    $uploadPath = './uploads/claim_receipt';
                    $config['upload_path'] = $uploadPath;
                    $config['allowed_types'] = '*';
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('uploadimage'))
                    {
                        $fileData = $this->upload->data();
                        $pv_attachment[] = $fileData['file_name'];

                        chmod($_SERVER['DOCUMENT_ROOT'].$this->systemName.'/uploads/claim_receipt/'.$fileData['file_name'],0644);
                    }

                    $attachment = json_encode($pv_attachment);
                }

                if($hidden_attachment[$k] != "")
                {
                    $claim_service['attachment'] = $hidden_attachment[$k];
                }
                else
                {   
                    if($attachment != NULL)
                    {
                        $claim_service['attachment'] = $attachment;
                    }
                    else
                    {
                        $claim_service['attachment'] = "[]";
                    }
                }
                $claim_service['billing_service_id'] = $billing_service_id[$k];
                $this->db->insert("claim_service",$claim_service);
                $claim_service_insert_id = $this->db->insert_id();

                $array_claim_service_id = array();

                if($billing_service_id[$k] != 0)
                {
                    $billing_service_result = $this->db->query("select * from billing_service where id = '".$billing_service_id[$k]."'");

                    $billing_service_result = $billing_service_result->result_array();
                    $latest_array_claim_service_id = json_decode($billing_service_result[0]["claim_service_id"]);

                    for($r = 0; $r < count($latest_array_claim_service_id); $r++)
                    {
                        if($latest_array_claim_service_id[$r] == $claim_service_id[$k])
                        {
                            array_push($array_claim_service_id, strval($claim_service_insert_id));
                        }
                        else
                        {
                            array_push($array_claim_service_id, strval($latest_array_claim_service_id[$r]));
                        }
                    }

                    $billing_service["claim_service_id"] = json_encode($array_claim_service_id);
                    $this->db->update("billing_service",$billing_service,array("id" => $billing_service_id[$k]));
                }
                
            }

            if($billing_service_id[0] != 0)
            {
                $billing_service_result = $this->db->query("select * from billing_service where id = '".$billing_service_id[0]."'");
                $billing_service_result = $billing_service_result->result_array();
                $latest_array_claim_service_id = json_decode($billing_service_result[0]["claim_service_id"]);

                for($r = 0; $r < count($latest_array_claim_service_id); $r++)
                {
                    $check_claim_id_result = $this->db->query("select * from claim_service where id = '".$latest_array_claim_service_id[$r]."'");

                    $check_claim_id_result = $check_claim_id_result->result_array();

                    if($check_claim_id_result)
                    {
                        $claim_service_id_data[] = $latest_array_claim_service_id[$r];
                    }
                }
                $latest_billing_service["claim_service_id"] = json_encode($claim_service_id_data);
                $this->db->update("billing_service",$latest_billing_service,array("id" => $billing_service_id[0]));
            }
            
            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
        }
        else
        {
            echo json_encode(array("Status" => 2, 'message' => 'This Payment Voucher No is already use.', 'title' => 'Error'));
        }
    }

    public function save_payment_voucher()
    {
        $firm_result = $this->db->query("select * from firm where id = '".$this->session->userdata('firm_id')."'");
        $firm_array = $firm_result->result_array();

        if($firm_array[0]["branch_name"] != "")
        {
            $branch_name = "(".$firm_array[0]["branch_name"].")";
        }
        else
        {
            $branch_name = "";
        }

        $client_type = $_POST["client_type"];
        if($client_type == 1)
        {
            $supplier_code = $_POST["vendor_name"];
            $vendor_name = $_POST["vendor_name_text"];
            $unassign_ccy = "";
            $unassign_amt = 0;
        }
        else if($client_type == 2)
        {
            $supplier_code = $_POST["client_name"];
            $vendor_name = $_POST["client_name_text"];
            $unassign_ccy = $_POST["unassign_ccy"];
            $unassign_amt = $_POST["unassign_amt"];
        }
        
        $payment_voucher_date = $_POST["payment_voucher_date"];
        $currency = $_POST["currency"];
        $bank_acc_id = $_POST["bank_account"];
        $cheque_number = $_POST["cheque_number"];
        $payment_voucher_no = $_POST["payment_voucher_no"];
        $previous_payment_voucher_no = $_POST["previous_payment_voucher_no"];
        $amount = array_values($_POST["amount"]);
        $payment_voucher_description = array_values($_POST["payment_voucher_description"]);
        $type = array_values($_POST["type"]);
        $rate = $_POST["rate"];
        $hidden_attachment = array_values($_POST["hidden_attachment"]);
        $grand_total = $_POST["grand_total"];

        $postal_code = $_POST["hidden_postal_code"];
        $street_name = $_POST["hidden_street_name"];
        $building_name = $_POST["hidden_building_name"];
        $unit_no1 = $_POST["hidden_unit_no1"];
        $unit_no2 = $_POST["hidden_unit_no2"];
        $foreign_address1 = $_POST["hidden_foreign_address1"];
        $foreign_address2 = $_POST["hidden_foreign_address2"];
        $foreign_address3 = $_POST["hidden_foreign_address3"];

        $payment_voucher_result = $this->db->query("select * from payment_voucher where supplier_code='".$supplier_code."' AND payment_voucher_no = '".$previous_payment_voucher_no."' AND status = '0'");

        $payment_voucher_result = $payment_voucher_result->result_array();

        if($payment_voucher_result)
        {
            $check_payment_voucher_id_result = $this->db->query("select * from payment_voucher where payment_voucher_no = '".$payment_voucher_no."' AND status = '0' AND id != '".$payment_voucher_result[0]['id']."' AND firm_id = '".$this->session->userdata('firm_id')."'");

            $check_payment_voucher_id_result = $check_payment_voucher_id_result->result_array();

            if(!$check_payment_voucher_id_result)
            {
                $new_amount = (float)str_replace(',', '', $grand_total);

                $payment_voucher['payment_voucher_no'] = $payment_voucher_no;
                $payment_voucher['currency_id'] = $currency;
                $payment_voucher['amount'] = $new_amount;
                $payment_voucher['bank_acc_id'] = $bank_acc_id;
                $payment_voucher['cheque_number'] = $cheque_number;
                $payment_voucher['rate'] = $rate;

                $this->db->delete('payment_voucher_service', array('payment_voucher_id' => $payment_voucher_result[0]['id']));

                $this->db->update("payment_voucher",$payment_voucher,array("id" => $payment_voucher_result[0]['id']));

                $this->save_audit_trail("Payment", "Edit Payment", $payment_voucher_no." payment voucher is edited in ".$firm_array[0]["name"].$branch_name.".");

                $payment_voucher_service['payment_voucher_id'] = $payment_voucher_result[0]['id'];

                $can_insert_payment_voucher_service = true;
            }
            else
            {
                $can_insert_payment_voucher_service = false;
            }

            $amunt_left = 0;
            $cn_total_amount_received = (float)str_replace(',', '', $new_amount);
            if((float)str_replace(',', '', $unassign_amt) > 0)
            {
                $query_billing_credit_note_gst_with_pv = $this->db->query("select billing_credit_note_gst_with_pv.*, billing_credit_note_gst.cn_out_of_balance, billing_credit_note_gst.previous_cn_out_of_balance from billing_credit_note_gst_with_pv LEFT JOIN billing_credit_note_gst ON billing_credit_note_gst.id = billing_credit_note_gst_with_pv.billing_credit_note_gst_id where billing_credit_note_gst_with_pv.pv_id ='".$payment_voucher_result[0]['id']."' ORDER BY billing_credit_note_gst_with_pv.id");

                $query_billing_credit_note_gst_with_pv = $query_billing_credit_note_gst_with_pv->result_array();

                foreach ($query_billing_credit_note_gst_with_pv as $key => $value) 
                {
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
        else
        {
            $check_payment_voucher_id_result = $this->db->query("select * from payment_voucher where payment_voucher_no = '".$payment_voucher_no."' AND status = '0' AND firm_id = '".$this->session->userdata('firm_id')."'");

            $check_payment_voucher_id_result = $check_payment_voucher_id_result->result_array();

            if(!$check_payment_voucher_id_result)
            {
                $payment_voucher['payment_voucher_no'] = $payment_voucher_no;
                $payment_voucher['firm_id'] = $this->session->userdata("firm_id");
                $payment_voucher['client_type'] = $client_type;
                $payment_voucher['supplier_code'] = $supplier_code;
                $payment_voucher['vendor_name'] = $vendor_name;
                $payment_voucher['postal_code'] = $postal_code;
                $payment_voucher['building_name'] = $building_name;
                $payment_voucher['street_name'] = $street_name;
                $payment_voucher['unit_no1'] = $unit_no1;
                $payment_voucher['unit_no2'] = $unit_no2;
                $payment_voucher['foreign_address1'] = $foreign_address1;
                $payment_voucher['foreign_address2'] = $foreign_address2;
                $payment_voucher['foreign_address3'] = $foreign_address3;
                $payment_voucher['payment_voucher_date'] = $payment_voucher_date;
                $payment_voucher['rate'] = $rate;
                $payment_voucher['bank_acc_id'] = $bank_acc_id;
                $payment_voucher['cheque_number'] = $cheque_number;
                $payment_voucher['amount'] = 0;

                for($p = 0; $p < count($amount); $p++)
                {
                    $payment_voucher['amount'] = $payment_voucher['amount'] + ((1+($gst_rate / 100)) * (float)str_replace(',', '', $amount[$p]));
                }
                
                $payment_voucher['currency_id'] = $currency;

                $this->db->insert("payment_voucher",$payment_voucher);
                $payment_voucher_service['payment_voucher_id'] = $this->db->insert_id();

                $this->save_audit_trail("Payment", "Create Payment", $payment_voucher_no." payment voucher is added in ".$firm_array[0]["name"].$branch_name.".");

                $can_insert_payment_voucher_service = true;
            }
            else
            {
                $can_insert_payment_voucher_service = false;
            } 

            $amunt_left = 0;
            $cn_total_amount_received = (float)str_replace(',', '', $payment_voucher['amount']);
            if((float)str_replace(',', '', $unassign_amt) > 0)
            {
                $query_billing_credit_note_gst = $this->db->query("select * from billing_credit_note_gst where company_code ='".$supplier_code."' AND cn_out_of_balance != 0 ORDER BY id");

                $query_billing_credit_note_gst = $query_billing_credit_note_gst->result_array();

                foreach ($query_billing_credit_note_gst as $key => $value) {

                    if($cn_total_amount_received > 0)
                    {
                        $amunt_left = $cn_total_amount_received - (float)$value["cn_out_of_balance"];
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
                        $amunt_left = (float)$value["cn_out_of_balance"];
                        $cn_total_amount_received = 0;

                        $billing_credit_note_gst["cn_out_of_balance"] = $amunt_left;
                    }
                    
                    $this->db->update("billing_credit_note_gst", $billing_credit_note_gst, array("id" => $value['id']));

                    $billing_credit_note_gst_with_receipt["billing_credit_note_gst_id"] = $value['id'];
                    $billing_credit_note_gst_with_receipt["pv_id"] = $payment_voucher_service['payment_voucher_id'];
                    $billing_credit_note_gst_with_receipt["previous_cn_currency"] = $unassign_ccy;
                    $billing_credit_note_gst_with_receipt["previous_cn_out_of_balance_amt"] = $value["cn_out_of_balance"];
                    $billing_credit_note_gst_with_receipt["previous_total_cn_out_of_balance"] = (float)str_replace(',', '', $unassign_amt);
                    $this->db->insert("billing_credit_note_gst_with_pv",$billing_credit_note_gst_with_receipt);
                }
            }
        }

        if($can_insert_payment_voucher_service)
        {
            for($k = 0; $k < count($amount); $k++)
            {
                $payment_voucher_service['payment_voucher_date'] = $payment_voucher_date;
                $payment_voucher_service['type_id'] = $type[$k];
                $payment_voucher_service['payment_voucher_description'] = $payment_voucher_description[$k];
                $payment_voucher_service['amount'] = (float)str_replace(',', '', $amount[$k]);
                $filesCount = count($_FILES['attachment']['name'][$k]);
                $pv_attachment = array();

                for($i = 0; $i < $filesCount; $i++)
                {   
                    $_FILES['uploadimage']['name'] = $_FILES['attachment']['name'][$k][$i];
                    $_FILES['uploadimage']['type'] = $_FILES['attachment']['type'][$k][$i];
                    $_FILES['uploadimage']['tmp_name'] = $_FILES['attachment']['tmp_name'][$k][$i];
                    $_FILES['uploadimage']['error'] = $_FILES['attachment']['error'][$k][$i];
                    $_FILES['uploadimage']['size'] = $_FILES['attachment']['size'][$k][$i];

                    $uploadPath = './uploads/pv_receipt';
                    $config['upload_path'] = $uploadPath;
                    $config['allowed_types'] = '*';
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('uploadimage'))
                    {
                        $fileData = $this->upload->data();
                        $pv_attachment[] = $fileData['file_name'];
                        chmod($_SERVER['DOCUMENT_ROOT'].$this->systemName.'/uploads/pv_receipt/'.$fileData['file_name'],0644);
                    }
                    $attachment = json_encode($pv_attachment);
                }

                if($hidden_attachment[$k] != "")
                {
                    $payment_voucher_service['attachment'] = $hidden_attachment[$k];
                }
                else
                {
                    $payment_voucher_service['attachment'] = $attachment;
                }

                $this->db->insert("payment_voucher_service",$payment_voucher_service);
            }
            
            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
        }
        else
        {
            echo json_encode(array("Status" => 2, 'message' => 'This Paymeent Voucher No is already use.', 'title' => 'Error'));
        }
    }

    public function save_payment_receipt()
    {
        $firm_result = $this->db->query("select * from firm where id = '".$this->session->userdata('firm_id')."'");
        $firm_array = $firm_result->result_array();

        if($firm_array[0]["branch_name"] != "")
        {
            $branch_name = "(".$firm_array[0]["branch_name"].")";
        }
        else
        {
            $branch_name = "";
        }

        $receipt_date = $_POST["payment_receipt_date"];
        $currency = $_POST["currency"];
        $bank_acc_id = $_POST["bank_account"];
        $cheque_number = $_POST["cheque_number"];
        $receipt_no = $_POST["payment_receipt_no"];
        $previous_receipt_no = $_POST["previous_payment_receipt_no"];
        $amount = array_values($_POST["amount"]);
        $payment_receipt_description = array_values($_POST["payment_receipt_description"]);
        $type = array_values($_POST["type"]);
        $rate = $_POST["rate"];
        $hidden_attachment = array_values($_POST["hidden_attachment"]);
        $grand_total = $_POST["grand_total"];
        $client_name = $_POST["client_name"];
        $address = $_POST["address"];

        $payment_receipt_result = $this->db->query("select * from payment_receipt where receipt_no = '".$previous_receipt_no."' AND status = '0'");

        $payment_receipt_result = $payment_receipt_result->result_array();

        if($payment_receipt_result)
        {
            $check_payment_receipt_id_result = $this->db->query("select * from payment_receipt where receipt_no = '".$receipt_no."' AND status = '0' AND id != '".$payment_receipt_result[0]['id']."' AND firm_id = '".$this->session->userdata('firm_id')."'");

            $check_payment_receipt_id_result = $check_payment_receipt_id_result->result_array();

            if(!$check_payment_receipt_id_result)
            {
                $new_amount = (float)str_replace(',', '', $grand_total);

                $payment_receipt['receipt_no'] = $receipt_no;
                $payment_receipt['currency_id'] = $currency;
                $payment_receipt['amount'] = $new_amount;
                $payment_receipt['bank_acc_id'] = $bank_acc_id;
                $payment_receipt['cheque_number'] = $cheque_number;
                $payment_receipt['rate'] = $rate;
                $payment_receipt['address'] = $address;

                $this->db->delete('payment_receipt_service', array('payment_receipt_id' => $payment_receipt_result[0]['id']));

                $this->db->update("payment_receipt",$payment_receipt,array("id" => $payment_receipt_result[0]['id']));

                $this->save_audit_trail("Payment", "Edit Receipt", $receipt_no." payment receipt is edited in ".$firm_array[0]["name"].$branch_name.".");

                $payment_receipt_service['payment_receipt_id'] = $payment_receipt_result[0]['id'];

                $can_insert_payment_receipt_service = true;
            }
            else
            {
                $can_insert_payment_receipt_service = false;
            }
        }
        else
        {
            $check_payment_receipt_id_result = $this->db->query("select * from payment_receipt where receipt_no = '".$receipt_no."' AND status = '0' AND firm_id = '".$this->session->userdata('firm_id')."'");

            $check_payment_receipt_id_result = $check_payment_receipt_id_result->result_array();

            if(!$check_payment_receipt_id_result)
            {
                $payment_receipt['receipt_no'] = $receipt_no;
                $payment_receipt['firm_id'] = $this->session->userdata("firm_id");
                $payment_receipt['client_name'] = $client_name;
                $payment_receipt['address'] = $address;
                $payment_receipt['receipt_date'] = $receipt_date;
                $payment_receipt['rate'] = $rate;
                $payment_receipt['bank_acc_id'] = $bank_acc_id;
                $payment_receipt['cheque_number'] = $cheque_number;
                $payment_receipt['amount'] = 0;

                for($p = 0; $p < count($amount); $p++)
                {
                    $payment_receipt['amount'] = $payment_receipt['amount'] + ((1+($gst_rate / 100)) * (float)str_replace(',', '', $amount[$p]));
                }
                
                $payment_receipt['currency_id'] = $currency;

                $this->db->insert("payment_receipt",$payment_receipt);
                $payment_receipt_service['payment_receipt_id'] = $this->db->insert_id();

                $this->save_audit_trail("Payment", "Create Receipt", $receipt_no." payment receipt is added in ".$firm_array[0]["name"].$branch_name.".");

                $can_insert_payment_receipt_service = true;
            }
            else
            {
                $can_insert_payment_receipt_service = false;
            } 
        }

        if($can_insert_payment_receipt_service)
        {
            for($k = 0; $k < count($amount); $k++)
            {
                $payment_receipt_service['receipt_date'] = $receipt_date;
                $payment_receipt_service['type_id'] = $type[$k];
                $payment_receipt_service['payment_receipt_description'] = $payment_receipt_description[$k];
                $payment_receipt_service['amount'] = (float)str_replace(',', '', $amount[$k]);
                $filesCount = count($_FILES['attachment']['name'][$k]);
                $pv_attachment = array();

                for($i = 0; $i < $filesCount; $i++)
                {   
                    $_FILES['uploadimage']['name'] = $_FILES['attachment']['name'][$k][$i];
                    $_FILES['uploadimage']['type'] = $_FILES['attachment']['type'][$k][$i];
                    $_FILES['uploadimage']['tmp_name'] = $_FILES['attachment']['tmp_name'][$k][$i];
                    $_FILES['uploadimage']['error'] = $_FILES['attachment']['error'][$k][$i];
                    $_FILES['uploadimage']['size'] = $_FILES['attachment']['size'][$k][$i];

                    $uploadPath = './uploads/pv_receipt_receipt';
                    $config['upload_path'] = $uploadPath;
                    $config['allowed_types'] = '*';
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('uploadimage'))
                    {
                        $fileData = $this->upload->data();
                        $pv_attachment[] = $fileData['file_name'];
                        chmod($_SERVER['DOCUMENT_ROOT'].$this->systemName.'/uploads/pv_receipt_receipt/'.$fileData['file_name'],0644);
                    }

                    $attachment = json_encode($pv_attachment);
                }

                if($hidden_attachment[$k] != "")
                {
                    $payment_receipt_service['attachment'] = $hidden_attachment[$k];
                }
                else
                {
                    $payment_receipt_service['attachment'] = $attachment;
                }

                $this->db->insert("payment_receipt_service",$payment_receipt_service);
            }
            
            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
        }
        else
        {
            echo json_encode(array("Status" => 2, 'message' => 'This Receipt No is already use.', 'title' => 'Error'));
        }
    }

    public function edit_pv_receipt($id)
    {
        $bc = array(array('link' => '#', 'page' => 'Edit Receipt'));
        $meta = array('page_title' => 'Edit Receipt', 'bc' => $bc, 'page_name' => 'Edit Receipt');

        $this->session->set_userdata("tab_active", "pv_receipt");

        $this->data['edit_payment_receipt'] =$this->db_model->get_pv_receipt($id);
        $this->data['edit_payment_receipt_service'] =$this->db_model->get_edit_pv_receipt_service($id);
        $this->data['firm_info'] = $this->master_model->get_firm_info();

        $result = $this->db->query("select payment_receipt_type.* from payment_receipt_type");

        $result = $result->result_array();
        //echo json_encode($result);
        if(!$result) {
            throw new exception("Type not found.");
        }
        $res = array();
        foreach($result as $row) {
            $res[$row['id']] = $row['type_name'];
        }

        $this->data['type_list'] = $res;

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Payment', base_url('payment_voucher'));
        $this->mybreadcrumb->add('Edit Payment - '.$this->data['edit_payment_receipt'][0]->client_name.'', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('payment_voucher/create_payment_receipt.php', $meta, $this->data);
    }

    public function edit_payment_voucher($id)
    {
        $bc = array(array('link' => '#', 'page' => 'Edit Payment'));
        $meta = array('page_title' => 'Edit Payment', 'bc' => $bc, 'page_name' => 'Edit Payment');

        $this->session->set_userdata("tab_active", "payment_voucher");

        $this->data['edit_payment_voucher'] =$this->db_model->get_payment_voucher($id);
        $this->data['edit_payment_voucher_service'] =$this->db_model->get_edit_payment_voucher_service($id);
        $this->data['firm_info'] = $this->master_model->get_firm_info();

        $result = $this->db->query("select payment_voucher_type.* from payment_voucher_type");

        $result = $result->result_array();

        $this->data['type_list'] = $result;

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Payment', base_url('payment_voucher'));
        $this->mybreadcrumb->add('Edit Payment - '.$this->data['edit_payment_voucher'][0]->vendor_name.'', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('payment_voucher/create_payment_voucher.php', $meta, $this->data);
    }

    public function edit_claim($id)
    {
        $bc = array(array('link' => '#', 'page' => 'Edit Claim'));
        $meta = array('page_title' => 'Edit Claim', 'bc' => $bc, 'page_name' => 'Edit Claim');

        $this->session->set_userdata("tab_active", "claim");

        $this->data['edit_claim'] =$this->db_model->get_claim($id);

        $this->data['edit_claim_service'] =$this->db_model->get_edit_claim_service($id);

        $this->data['firm_info'] = $this->master_model->get_firm_info();

        $result = $this->db->query("select payment_voucher_type.* from payment_voucher_type");

        $result = $result->result_array();

        $this->data['type_list'] = $result;

        $client_query = 'SELECT client.id, client.company_code, client.company_name FROM client left join user_firm on user_id = "'.$this->session->userdata("user_id").'" where user_firm.firm_id = client.firm_id AND deleted != 1';

        $client_result = $this->db->query($client_query);

        if ($client_result->num_rows() > 0) 
        {

            $client_result = $client_result->result_array();

            if(!$client_result) {
              throw new exception("Client Name not found.");
            }

            $client_res = array();
            foreach($client_result as $client_row) {
                if($client_row['company_name'] != null)
                {
                    $client_res[$client_row['company_code']] = $this->encryption->decrypt($client_row['company_name']);
                }
              
            }
        }
        else
        {
            $client_res = null;
        }

        $this->data['client_list'] = $client_res;

        $claim_for_transport_query = 'SELECT claim.user_name, claim.claim_no, currency.currency, b.*, (SELECT SUM(a.amount) FROM claim_service as a WHERE a.claim_id = claim.id AND a.type_id = "1" AND a.company_code != "0" AND a.company_code = b.company_code) as total_ammount FROM claim left join claim_service as b on b.claim_id = claim.id left join currency on currency.id = claim.currency_id where b.billing_service_id = 0 And claim.status = 0 AND b.type_id = 1 AND firm_id = "'.$this->session->userdata("firm_id").'"';

        $claim_for_transport_query = $this->db->query($claim_for_transport_query);

        $this->data['claim_for_transport_query'] = $claim_for_transport_query->result_array();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Payment', base_url('payment_voucher'));
        $this->mybreadcrumb->add('Edit Claim - '.$this->data['edit_claim'][0]->user_name.'', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('payment_voucher/create_claim.php', $meta, $this->data);
    }

    public function get_the_transport_claim()
    {
        $claim_for_transport_query = 'SELECT claim.user_name, claim.claim_no, currency.currency, b.*, (SELECT SUM(a.amount) FROM claim_service as a WHERE a.claim_id = claim.id AND a.type_id = "1" AND a.company_code != "0" AND a.company_code = b.company_code) as total_ammount FROM claim left join claim_service as b on b.claim_id = claim.id left join currency on currency.id = claim.currency_id where b.billing_service_id = 0 And claim.status = 0 AND b.type_id = 1 AND firm_id = "'.$this->session->userdata("firm_id").'"';

        $claim_for_transport_query = $this->db->query($claim_for_transport_query);

        $this->data['claim_for_transport_query'] =  $claim_for_transport_query->result_array();

        echo json_encode($this->data);
    }

    public function export_excel()
    {
        $search_name_where = "";
        $search_date_where = "";

        $tab = $_POST["tab"];
        $search_name = $_POST["search_name"];
        $start_date = $_POST["start_date"];
        $end_date = $_POST["end_date"];

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        $spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."/secretary/assets/uploads/file/Payment.xls");
        $sheet = $spreadsheet->getActiveSheet();

        if($tab == "payment_voucher")
        {
            if ($search_name != NULL)
            {
                $search_name_where = '(payment_voucher.payment_voucher_no like "%'.$search_name.'%" OR payment_voucher.vendor_name like "%'.$search_name.'%") AND';
            }
            if ($start_date != NULL)
            {
                if ($end_date != NULL)
                {
                    $search_date_where = 'STR_TO_DATE(payment_voucher.payment_voucher_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start_date. '","%d/%m/%Y") and STR_TO_DATE("'. $end_date.'","%d/%m/%Y") AND';
                }
                else
                {
                    $search_date_where = 'payment_voucher.payment_voucher_date = "'. $start_date.'" AND';
                }
            }

            $q = $this->db->query("select payment_voucher.*, currency.currency as currency_name, payment_voucher_service.id as payment_voucher_service_id, payment_voucher_service.payment_voucher_id, payment_voucher_service.type_id, payment_voucher_service.payment_voucher_date, payment_voucher_service.payment_voucher_description, payment_voucher_service.amount as payment_voucher_service_amount, payment_voucher_service.attachment, firm.name as firm_name, payment_voucher_type.type_name, bank_info.banker from payment_voucher left join payment_voucher_service on payment_voucher_service.payment_voucher_id = payment_voucher.id left join currency on payment_voucher.currency_id = currency.id left join firm on firm.id = payment_voucher.firm_id left join payment_voucher_type on payment_voucher_type.id = payment_voucher_service.type_id left join bank_info on bank_info.id = payment_voucher.bank_acc_id where ".$search_name_where. " ".$search_date_where." payment_voucher.firm_id='".$this->session->userdata("firm_id")."' AND payment_voucher.status != 1 ORDER BY payment_voucher_service.id");
        }
        elseif($tab == "claim")
        {
            if ($search_name != NULL)
            {
                $search_name_where = '(claim.claim_no like "%'.$search_name.'%" OR claim.user_name like "%'.$search_name.'%") AND';
            }
            if ($start_date != NULL)
            {
                if ($end_date != NULL)
                {
                    $search_date_where = 'STR_TO_DATE(claim.claim_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start_date. '","%d/%m/%Y") and STR_TO_DATE("'. $end_date.'","%d/%m/%Y") AND';
                }
                else
                {
                    $search_date_where = 'claim.claim_date = "'. $start_date.'" AND';
                }
            }

            $q = $this->db->query("select claim.*, currency.currency as currency_name, claim_service.id as claim_service_id, claim_service.claim_id, claim_service.company_code, claim_service.type_id, claim_service.claim_date, claim_service.client_name, claim_service.claim_description, claim_service.amount as claim_service_amount, claim_service.attachment, firm.name as firm_name, payment_voucher_type.type_name, bank_info.banker from claim left join claim_service on claim_service.claim_id = claim.id left join currency on claim.currency_id = currency.id left join firm on firm.id = claim.firm_id left join payment_voucher_type on payment_voucher_type.id = claim_service.type_id left join bank_info on bank_info.id = claim.bank_acc_id where ".$search_name_where. " ".$search_date_where." claim.firm_id='".$this->session->userdata("firm_id")."' AND claim.status != 1 ORDER BY claim_service.id");
        }
        elseif($tab == "pv_receipt")
        {
            if ($search_name != NULL)
            {
                $search_name_where = '(payment_receipt.receipt_no like "%'.$search_name.'%" OR payment_receipt.client_name like "%'.$search_name.'%") AND';
            }
            if ($start_date != NULL)
            {
                if ($end_date != NULL)
                {
                    $search_date_where = 'STR_TO_DATE(payment_receipt.receipt_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start_date. '","%d/%m/%Y") and STR_TO_DATE("'. $end_date.'","%d/%m/%Y") AND';
                }
                else
                {
                    $search_date_where = 'payment_receipt.receipt_date = "'. $start_date.'" AND';
                }
            }

            $q = $this->db->query("select payment_receipt.*, currency.currency as currency_name, payment_receipt_service.id as payment_receipt_service_id, payment_receipt_service.payment_receipt_id, payment_receipt_service.type_id, payment_receipt_service.receipt_date, payment_receipt_service.payment_receipt_description, payment_receipt_service.amount as payment_receipt_service_amount, payment_receipt_service.attachment, firm.name as firm_name, payment_receipt_type.type_name, bank_info.banker from payment_receipt left join payment_receipt_service on payment_receipt_service.payment_receipt_id = payment_receipt.id left join currency on payment_receipt.currency_id = currency.id left join firm on firm.id = payment_receipt.firm_id left join payment_receipt_type on payment_receipt_type.id = payment_receipt_service.type_id left join bank_info on bank_info.id = payment_receipt.bank_acc_id where ".$search_name_where. " ".$search_date_where." payment_receipt.firm_id='".$this->session->userdata("firm_id")."' AND payment_receipt.status != 1 ORDER BY payment_receipt_service.id");
        }

        if ($q->num_rows() > 0) {
            $q = $q->result_array();
            $r = 2;

            for($g = 0; $g < count($q); $g++)
            {
                if($tab == "payment_voucher")
                {
                    $sheet->setCellValue('A'.$r, ($g + 1));
                    $sheet->setCellValue('B'.$r, $q[$g]['vendor_name']);
                    $sheet->setCellValue('C'.$r, $q[$g]['payment_voucher_date']);
                    $sheet->setCellValue('D'.$r, $q[$g]['payment_voucher_no']);
                    $sheet->setCellValue('E'.$r, $q[$g]['type_name']);
                    $sheet->setCellValue('F'.$r, $q[$g]['currency_name']);
                    $sheet->setCellValue('G'.$r, $q[$g]['banker']);
                    $sheet->setCellValue('H'.$r, $q[$g]['payment_voucher_service_amount']);
                    if($q[$g]['status'] == 0)
                    {
                        $sheet->setCellValue('I'.$r, "Pending");
                    }
                    else if($q[$g]['status'] == 1)
                    {
                        $sheet->setCellValue('I'.$r, "Deleted");
                    }
                    else if($q[$g]['status'] == 2)
                    {
                        $sheet->setCellValue('I'.$r, "Rejected");
                    }
                    else if($q[$g]['status'] == 3)
                    {
                        $sheet->setCellValue('I'.$r, "Approved - Unpaid");
                    }
                    else if($q[$g]['status'] == 4)
                    {
                        $sheet->setCellValue('I'.$r, "Approved & Paid");
                    }

                    $fileExcelName = "Payment";
                }
                elseif($tab == "claim")
                {
                    $sheet->setCellValue('A'.$r, ($g + 1));
                    $sheet->setCellValue('B'.$r, $q[$g]['user_name']);
                    $sheet->setCellValue('C'.$r, $q[$g]['claim_date']);
                    $sheet->setCellValue('D'.$r, $q[$g]['claim_no']);
                    $sheet->setCellValue('E'.$r, $q[$g]['type_name']);
                    $sheet->setCellValue('F'.$r, $q[$g]['currency_name']);
                    $sheet->setCellValue('G'.$r, $q[$g]['banker']);
                    $sheet->setCellValue('H'.$r, $q[$g]['claim_service_amount']);
                    if($q[$g]['status'] == 0)
                    {
                        $sheet->setCellValue('I'.$r, "Pending");
                    }
                    else if($q[$g]['status'] == 1)
                    {
                        $sheet->setCellValue('I'.$r, "Deleted");
                    }
                    else if($q[$g]['status'] == 2)
                    {
                        $sheet->setCellValue('I'.$r, "Rejected");
                    }
                    else if($q[$g]['status'] == 3)
                    {
                        $sheet->setCellValue('I'.$r, "Approved - Unpaid");
                    }
                    else if($q[$g]['status'] == 4)
                    {
                        $sheet->setCellValue('I'.$r, "Approved & Paid");
                    }

                    $fileExcelName = "Claim";
                }
                elseif($tab == "pv_receipt")
                {
                    $sheet->setCellValue('A'.$r, ($g + 1));
                    $sheet->setCellValue('B'.$r, $q[$g]['client_name']);
                    $sheet->setCellValue('C'.$r, $q[$g]['receipt_date']);
                    $sheet->setCellValue('D'.$r, $q[$g]['receipt_no']);
                    $sheet->setCellValue('E'.$r, $q[$g]['type_name']);
                    $sheet->setCellValue('F'.$r, $q[$g]['currency_name']);
                    $sheet->setCellValue('G'.$r, $q[$g]['banker']);
                    $sheet->setCellValue('H'.$r, $q[$g]['payment_receipt_service_amount']);
                    if($q[$g]['status'] == 0)
                    {
                        $sheet->setCellValue('I'.$r, "Pending");
                    }
                    else if($q[$g]['status'] == 1)
                    {
                        $sheet->setCellValue('I'.$r, "Deleted");
                    }
                    else if($q[$g]['status'] == 2)
                    {
                        $sheet->setCellValue('I'.$r, "Rejected");
                    }
                    else if($q[$g]['status'] == 3)
                    {
                        $sheet->setCellValue('I'.$r, "Approved - Unpaid");
                    }
                    else if($q[$g]['status'] == 4)
                    {
                        $sheet->setCellValue('I'.$r, "Approved & Paid");
                    }

                    $fileExcelName = "Receipt";
                }
                
                $r = $r + 1;
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
             
            $filename = $_SERVER['DOCUMENT_ROOT'].'/secretary/assets/uploads/excel/' .$fileExcelName. ' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $q[0]["firm_name"]).'.xls';
            $writer->save($filename);

            $this->zip->read_file($filename);

            $this->zip->archive($_SERVER['DOCUMENT_ROOT'].'/secretary/assets/uploads/excel/' .$fileExcelName. ' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $q[0]["firm_name"]).'.zip');

            //$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
            $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
            $link = $protocol . $_SERVER['SERVER_NAME'] .'/secretary/assets/uploads/excel/' .$fileExcelName. ' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $q[0]["firm_name"]).'.zip';

            $data = array('status'=>'success', 'link'=>$link);
            
        }
        else
        {
            $data = array('status'=>'fail');
        }

        echo json_encode($data);
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