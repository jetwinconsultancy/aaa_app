<?php defined('BASEPATH') OR exit('No direct script access allowed');

class billings extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        //$this->load->library('form_validation');
        $this->load->library(array('session'));
        $this->load->model('db_model');
    }

    public function index()
    {
        //$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        // $this->data['sales'] = $this->db_model->getLatestSales();
        // $this->data['quotes'] = $this->db_model->getLastestQuotes();
        // $this->data['purchases'] = $this->db_model->getLatestPurchases();
        // $this->data['transfers'] = $this->db_model->getLatestTransfers();
        // $this->data['customers'] = $this->db_model->getLatestCustomers();
        // $this->data['suppliers'] = $this->db_model->getLatestSuppliers();
        // $this->data['chatData'] = $this->db_model->getChartData();
        // $this->data['stock'] = $this->db_model->getStockValue();
        // $this->data['bs'] = $this->db_model->getBestSeller();
        // $this->data['users'] = $this->db_model->getUserList();
        // $lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
        // $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
        // $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
        $bc = array(array('link' => '#', 'page' => lang('Billings')));
        $meta = array('page_title' => lang('Billings'), 'bc' => $bc, 'page_name' => 'Billings');

        if($_POST["company_code"] != null)
        {
             $this->data['open_receipt'] = true;
             $this->data['company_code'] = $_POST["company_code"];
        }
        else
        {
            $this->data['open_receipt'] = false;
        }

        if (isset($_POST['search'])) {
            if (isset($_POST['search']) && isset($_POST['type']))
            {

                // if ($_POST['pencarian'] != '')
                // {
                    $this->data['billings'] = $this->db_model->get_all_unpaid_billings($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                    $this->data['paid_billings'] = $this->db_model->get_all_paid_billings($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                    $this->data['receipt'] = $this->db_model->get_all_receipt($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                    $this->data['credit_note'] = $this->db_model->get_all_credit_note($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                    $this->data['recurring_billing'] = $this->db_model->get_all_recurring_billing($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                // }
            } 
        }
        else
        {
            $this->data['billings'] = $this->db_model->get_all_unpaid_billings();
            $this->data['paid_billings'] = $this->db_model->get_all_paid_billings();
            $this->data['receipt'] = $this->db_model->get_all_receipt();
            $this->data['credit_note'] = $this->db_model->get_all_credit_note();
            //echo json_encode($this->data['credit_note']);
            $this->data['recurring_billing'] = $this->db_model->get_all_recurring_billing();
            
        }

         $this->data['currency'] = $this->db_model->get_currency();
        //$this->data['template'] = $this->db_model->get_all_template();

        /*$billings = $this->db->query("select billing.*, client.company_name from billing left join client on client.company_code = billing.company_code");

        $billings = $billings->result_array();

        $this->data['billings'] = $billings;*/

        //echo json_encode($this->data['billings']);
		// $this->data['page_name'] = 'Clients';
        $this->page_construct('billings.php', $meta, $this->data);

    }

    public function receipt($company_code = null)
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

        if (isset($_POST['search'])) {
            if (isset($_POST['search']) && isset($_POST['type']))
            {

                // if ($_POST['pencarian'] != '')
                // {
                    $this->data['billings'] = $this->db_model->get_all_unpaid_billings($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                // }
            } 
        }
        else
        {
            $this->data['billings'] = $this->db_model->get_all_unpaid_billings();

            
        }

        $this->data['paid_billings'] = $this->db_model->get_all_paid_billings();
        
        //$this->data['template'] = $this->db_model->get_all_template();

        /*$billings = $this->db->query("select billing.*, client.company_name from billing left join client on client.company_code = billing.company_code");

        $billings = $billings->result_array();

        $this->data['billings'] = $billings;*/

        //echo json_encode($this->data['billings']);
        // $this->data['page_name'] = 'Clients';
        $this->page_construct('billings.php', $meta, $this->data);
    }

    public function create_billing ()
    {
        $bc = array(array('link' => '#', 'page' => lang('Create Billing')));
        $meta = array('page_title' => lang('Create Billing'), 'bc' => $bc, 'page_name' => 'Create Billing');
        $this->session->unset_userdata('billing_company_code');
        $this->session->unset_userdata('billing_currency');

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Billing', base_url('billings'));
        $this->mybreadcrumb->add('Create Billings', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        // $this->data['page_name'] = 'Clients';
        $this->page_construct('client/create_billing.php', $meta, $this->data);
    }

    public function create_recurring()
    {
        $bc = array(array('link' => '#', 'page' => lang('Create Recurring')));
        $meta = array('page_title' => lang('Create Recurring'), 'bc' => $bc, 'page_name' => 'Create Recurring');
        $this->session->unset_userdata('billing_company_code');
        $this->session->unset_userdata('billing_currency');

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Recurring', base_url('billings'));
        $this->mybreadcrumb->add('Create Recurrings', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        // $this->data['page_name'] = 'Clients';
        $this->page_construct('client/create_recurring.php', $meta, $this->data);
    }

    public function get_billing_info()
    {
        $company_code = $_POST["company_code"];


        $q = $this->db->query("select billing.*, client.company_name, client.incorporation_date from billing left join client on client.company_code = billing.company_code where outstanding > 0 AND billing.company_code = '".$_POST["company_code"]."' AND billing.firm_id = '". $this->session->userdata("firm_id")."' AND billing.status = '0' ORDER BY STR_TO_DATE(billing.invoice_date,'%d/%m/%Y')");

        $query_credit_note_no = $this->db->query("SELECT credit_note_no FROM credit_note where credit_note.id = (SELECT max(credit_note_id) FROM billing_credit_note_record where billing_credit_note_record.firm_id = '".$this->session->userdata("firm_id")."')");
        //$id = $query->row()->id;

        if ($query_credit_note_no->num_rows() > 0) 
        {
            $query_credit_note_no = $query_credit_note_no->result_array();

            // $last_section_invoice_no = (int)$query_invoice_no[0]["invoice_no"] + 1;
            // $number = "AB-".date("Y")."-".str_pad($last_section_invoice_no,4,"0",STR_PAD_LEFT);

            $last_section_credit_note_no = (string)$query_credit_note_no[0]["credit_note_no"];
            //echo (substr_replace($last_section_invoice_no, "", -1));
            //$credit_note_no = substr_replace($last_section_credit_note_no, "", -1).((int)($last_section_credit_note_no[strlen($last_section_credit_note_no)-1]) + 1);

            $credit_note_no = substr_replace($last_section_credit_note_no, "", -4).(str_pad((int)(substr($last_section_credit_note_no, -4)) + 1, 4, '0', STR_PAD_LEFT));

        }
        else
        {
            $credit_note_no = "CN-"."AB-".date("Y").str_pad(1,5,"0",STR_PAD_LEFT);
        }

        $q = $this->db->query("select billing.*, client.company_name, client.incorporation_date from billing left join client on client.company_code = billing.company_code where outstanding > 0 AND billing.company_code = '".$_POST["company_code"]."' AND billing.firm_id = '". $this->session->userdata("firm_id")."' AND billing.status = '0' ORDER BY STR_TO_DATE(billing.invoice_date,'%d/%m/%Y')");


            //echo json_encode($q);
        if ($q->num_rows() > 0) {
            echo json_encode(array("status" => 1, 'result' => $q->result(), 'credit_note_no' => $credit_note_no));

            // foreach (($q->result()) as $row) {
                // $data[] = $row;
            // }
            // return $data;
        } else echo json_encode(array("status" => 0, 'credit_note_no' => $credit_note_no));
    }

    public function get_receipt_info()
    {
        $receipt_id = $_POST["receipt_id"];

        $q = $this->db->query("select billing_receipt_record.receipt_id, billing_receipt_record.billing_id, billing_receipt_record.received, billing_receipt_record.previous_outstanding, receipt.id, receipt.receipt_no, receipt_date, receipt.reference_no, receipt.payment_mode as payment_mode_id, receipt.total_amount_received, billing.*, client.company_name, client.incorporation_date, payment_mode.payment_mode from billing left join billing_receipt_record on billing_receipt_record.billing_id = billing.id left join receipt on receipt.id = billing_receipt_record.receipt_id left join client on client.company_code = billing.company_code left join payment_mode on payment_mode.id = receipt.payment_mode where billing_receipt_record.receipt_id = '".$receipt_id."' AND billing.outstanding != billing.amount AND billing.firm_id = '". $this->session->userdata("firm_id")."'");

        if ($q->num_rows() > 0) {
            echo json_encode(array("status" => 1, 'result' => $q->result()));

            // foreach (($q->result()) as $row) {
                // $data[] = $row;
            // }
            // return $data;
        } else echo json_encode(array("status" => 0));
    }

    public function get_credit_note_info()
    {
        $credit_note_id = $_POST["credit_note_id"];

        $q = $this->db->query("select billing_credit_note_record.credit_note_id, billing_credit_note_record.billing_id, billing_credit_note_record.received, billing_credit_note_record.previous_outstanding, credit_note.id, credit_note.credit_note_no, credit_note_date, credit_note.total_amount_discounted, billing.*, client.company_name, client.incorporation_date from billing left join billing_credit_note_record on billing_credit_note_record.billing_id = billing.id left join credit_note on credit_note.id = billing_credit_note_record.credit_note_id left join client on client.company_code = billing.company_code where billing_credit_note_record.credit_note_id = '".$credit_note_id."' AND billing.outstanding != billing.amount AND billing.firm_id = '". $this->session->userdata("firm_id")."'");

        if ($q->num_rows() > 0) {
            echo json_encode(array("status" => 1, 'result' => $q->result()));

            // foreach (($q->result()) as $row) {
                // $data[] = $row;
            // }
            // return $data;
        } else echo json_encode(array("status" => 0));
    }

    public function save_template()
    {
        //echo json_encode($_POST);
        $id = array_values($_POST["id"]);
        $service = array_values($_POST["service"]);
        $invoice_description = array_values($_POST["invoice_description"]);
        $amount = array_values($_POST["amount"]);

        //$this->db->where('id IN ('.implode(',',$id).')', NULL, FALSE);
        $this->db->where('firm_id = '.$this->session->userdata("firm_id"));
        $this->db->delete("billing_template");

        //echo json_encode($id);
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

                // $this->db->update("billing_template",$template,array("id" => $_POST['id'][$i], "firm_id" => $this->session->userdata("firm_id")));
                $this->db->insert("billing_template",$template);

            

        }
        echo json_encode(array("Status" => 1));
    }

    public function get_payment_mode()
    {
        $ci =& get_instance();

        $query = "select * from payment_mode";

        $result = $ci->db->query($query);
        //echo json_encode($result->result_array());
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

    public function save_credit_note()
    {
        if(!isset($_POST['credit_note_id']))
        {
            //$credit_note['credit_note_no'] = $_POST["credit_note_no"];
            $credit_note['credit_note_date'] = $_POST["credit_note_date"];
            $credit_note['credit_note_no'] = $_POST["credit_note_no"];
            $credit_note['total_amount_discounted'] = (float)str_replace(',', '', $_POST["total_amount_discounted"]);

            //echo json_encode($receipt);

            $this->db->insert("credit_note",$credit_note);
            $credit_note_id = $this->db->insert_id();

            for($i = 0; $i < count($_POST['id']); $i++ )
            {
                $received = (float)str_replace(',', '', $_POST['received'][$i]);
                //echo $received;
                if($received > 0)
                {
                    $billing['outstanding'] = (float)$_POST['outstanding'][$i] - $received;
                    //echo $new_outstanding;
                    $this->db->update("billing",$billing,array("id" => $_POST['id'][$i]));
                    $billing_credit_note_record['firm_id'] = $this->session->userdata("firm_id");
                    $billing_credit_note_record['credit_note_id'] = (float)$credit_note_id;
                    $billing_credit_note_record['billing_id'] = $_POST['id'][$i];
                    $billing_credit_note_record['received'] = (float)str_replace(',', '', $_POST['received'][$i]);
                    $billing_credit_note_record['previous_outstanding'] = (float)str_replace(',', '', $_POST['outstanding'][$i]);

                    $this->db->insert("billing_credit_note_record",$billing_credit_note_record);

                }

            }
            echo json_encode(array("Status" => 1));
        }
        else
        {
            $new_outstanding = 0;

            $credit_note['credit_note_date'] = $_POST["credit_note_date"];
            $credit_note['credit_note_no'] = $_POST["credit_note_no"];
            $credit_note['total_amount_discounted'] = (float)str_replace(',', '', $_POST["total_amount_discounted"]);

            $this->db->update("credit_note",$credit_note,array("id" => $_POST['credit_note_id']));

            for($i = 0; $i < count($_POST['id']); $i++ )
            {
                $received = (float)str_replace(',', '', $_POST['received'][$i]);
                //echo $received;
                if($received > 0)
                {
                    $query = $this->db->query("select * from billing_credit_note_record where credit_note_id = '".$_POST['credit_note_id']."' AND billing_id='".$_POST['id'][$i]."'");

                    $query = $query->result_array();

                    $billing_credit_note_record['received'] = (float)str_replace(',', '', $_POST['received'][$i]);

                    //$new_outstanding = (float)str_replace(',', '', $_POST['outstanding'][$i]) - (float)str_replace(',', '', $_POST['received'][$i]);

                    $where = "credit_note_id='".$_POST['credit_note_id']."' AND billing_id='".$_POST['id'][$i]."'";
                    $this->db->where($where);
                    $this->db->update("billing_credit_note_record",$billing_credit_note_record);

                    $query_billing = $this->db->query("select * from billing where id='".$_POST['id'][$i]."'");

                    $query_billing = $query_billing->result_array();

                    $query_billing_receipt_record = $this->db->query("select * from billing_receipt_record where billing_id='".$_POST['id'][$i]."'");

                    $query_billing_receipt_record = $query_billing_receipt_record->result_array();

                    /*if($new_outstanding == 0)
                    {
                        $this->db->delete("billing_receipt_record",array("receipt_id" => $_POST['receipt_id'], "billing_id" => $_POST['id'][$i], "id >" => $query[0]["id"]));
                    }
                    else
                    {*/
                        $query_other_credit_note_record = $this->db->query("select * from billing_credit_note_record where billing_id='".$_POST['id'][$i]."' AND id != '".$query[0]["id"]."' ORDER BY id");


                        if ($query_other_credit_note_record->num_rows())
                        {
                            $query_other_credit_note_record = $query_other_credit_note_record->result_array();

                            $query_billing = $this->db->query("select * from billing where id='".$_POST['id'][$i]."'");

                            $query_billing = $query_billing->result_array();

                            $new_outstanding = (float)$new_outstanding + (float)str_replace(',', '', $_POST['received'][$i]);

                            for($r = 0; $r < count($query_other_credit_note_record); $r++)
                            {
                                //echo json_encode($query_other_credit_note_record[$r]['id']);
                                /*$data["previous_outstanding"] = $new_outstanding;
                                $this->db->update("billing_credit_note_record",$data,array("id" => $query_other_credit_note_record[$r]['id']));*/

                                $new_outstanding = (float)$new_outstanding + (float)$query_other_credit_note_record[$r]['received'];


                            }

                            $new_outstanding = (float)$query_billing[0]["amount"] - (float)$new_outstanding;

                            /*if(0 > $new_outstanding)
                            {
                                $change_outstanding["outstanding"] = 0;
                            }  */ 
                            $change_outstanding["outstanding"] = $new_outstanding;
                            $this->db->update("billing",$change_outstanding,array("id" => $_POST['id'][$i]));
                            
                        }
                        else
                        {
                            $new_outstanding = (float)$query_billing[0]["amount"] - (float)$query_billing_receipt_record[0]["received"] - (float)str_replace(',', '', $_POST['received'][$i]);
                            $change_outstanding["outstanding"] = $new_outstanding;
                            $this->db->update("billing",$change_outstanding,array("id" => $_POST['id'][$i]));
                        }
                    //}
                    


                }
            }
            echo json_encode(array("Status" => 1));
            
        }
    }

    public function save_receipt()
    {
        //echo json_encode($_POST);
        if(!isset($_POST['receipt_id']))
        {
            $receipt['receipt_no'] = $_POST["receipt_no"];
            $receipt['receipt_date'] = $_POST["receipt_date"];
            $receipt['reference_no'] = $_POST["reference_no"];
            $receipt['payment_mode'] = $_POST["payment_mode"];
            $receipt['total_amount_received'] = (float)str_replace(',', '', $_POST["total_amount_received"]);

            //echo json_encode($receipt);

            $this->db->insert("receipt",$receipt);
            $receipt_id = $this->db->insert_id();

            for($i = 0; $i < count($_POST['id']); $i++ )
            {
                $received = (float)str_replace(',', '', $_POST['received'][$i]);
                //echo $received;
                if($received > 0)
                {
                    $billing['outstanding'] = (float)$_POST['outstanding'][$i] - $received;
                    //echo $new_outstanding;
                    $this->db->update("billing",$billing,array("id" => $_POST['id'][$i]));
                    $billing_receipt_record['firm_id'] = $this->session->userdata("firm_id");
                    $billing_receipt_record['receipt_id'] = (float)$receipt_id;
                    $billing_receipt_record['billing_id'] = $_POST['id'][$i];
                    $billing_receipt_record['received'] = (float)str_replace(',', '', $_POST['received'][$i]);
                    $billing_receipt_record['previous_outstanding'] = (float)str_replace(',', '', $_POST['outstanding'][$i]);

                    $this->db->insert("billing_receipt_record",$billing_receipt_record);

                }

            }
            echo json_encode(array("Status" => 1));
        }
        else
        {
            $new_outstanding = 0;

            $receipt['receipt_no'] = $_POST["receipt_no"];
            $receipt['receipt_date'] = $_POST["receipt_date"];
            $receipt['reference_no'] = $_POST["reference_no"];
            $receipt['payment_mode'] = $_POST["payment_mode"];
            $receipt['total_amount_received'] = (float)str_replace(',', '', $_POST["total_amount_received"]);

            $this->db->update("receipt",$receipt,array("id" => $_POST['receipt_id']));

            for($i = 0; $i < count($_POST['id']); $i++ )
            {
                $received = (float)str_replace(',', '', $_POST['received'][$i]);
                //echo $received;
                if($received > 0)
                {
                    $query = $this->db->query("select * from billing_receipt_record where receipt_id = '".$_POST['receipt_id']."' AND billing_id='".$_POST['id'][$i]."'");

                    $query = $query->result_array();

                    $billing_receipt_record['received'] = (float)str_replace(',', '', $_POST['received'][$i]);

                    //$new_outstanding = (float)str_replace(',', '', $_POST['outstanding'][$i]) - (float)str_replace(',', '', $_POST['received'][$i]);

                    $where = "receipt_id='".$_POST['receipt_id']."' AND billing_id='".$_POST['id'][$i]."'";
                    $this->db->where($where);
                    $this->db->update("billing_receipt_record",$billing_receipt_record);

                    $query_billing = $this->db->query("select * from billing where id='".$_POST['id'][$i]."'");

                    $query_billing = $query_billing->result_array();

                    $query_billing_credit_note_record = $this->db->query("select * from billing_credit_note_record where billing_id='".$_POST['id'][$i]."'");

                    $query_billing_credit_note_record = $query_billing_credit_note_record->result_array();

                    /*if($new_outstanding == 0)
                    {
                        $this->db->delete("billing_receipt_record",array("receipt_id" => $_POST['receipt_id'], "billing_id" => $_POST['id'][$i], "id >" => $query[0]["id"]));
                    }
                    else
                    {*/
                        $query_other_receipt_record = $this->db->query("select * from billing_receipt_record where billing_id='".$_POST['id'][$i]."' AND id != '".$query[0]["id"]."' ORDER BY id");


                        if ($query_other_receipt_record->num_rows())
                        {
                            $query_other_receipt_record = $query_other_receipt_record->result_array();

                            $query_billing = $this->db->query("select * from billing where id='".$_POST['id'][$i]."'");

                            $query_billing = $query_billing->result_array();

                            $new_outstanding = (float)$new_outstanding + (float)str_replace(',', '', $_POST['received'][$i]);

                            for($r = 0; $r < count($query_other_receipt_record); $r++)
                            {
                                //echo json_encode($query_other_receipt_record[$r]['id']);
                                /*$data["previous_outstanding"] = $new_outstanding;
                                $this->db->update("billing_receipt_record",$data,array("id" => $query_other_receipt_record[$r]['id']));*/

                                $new_outstanding = (float)$new_outstanding + (float)$query_other_receipt_record[$r]['received'];


                            }

                            $new_outstanding = (float)$query_billing[0]["amount"] - (float)$new_outstanding;

                            /*if(0 > $new_outstanding)
                            {
                                $change_outstanding["outstanding"] = 0;
                            }  */ 
                            $change_outstanding["outstanding"] = $new_outstanding;
                            $this->db->update("billing",$change_outstanding,array("id" => $_POST['id'][$i]));
                            
                        }
                        else
                        {
                            $new_outstanding = (float)$query_billing[0]["amount"] - (float)$query_billing_credit_note_record[0]["received"] - (float)str_replace(',', '', $_POST['received'][$i]);
                            $change_outstanding["outstanding"] = $new_outstanding;
                            $this->db->update("billing",$change_outstanding,array("id" => $_POST['id'][$i]));
                        }
                    //}
                    


                }
            }
            echo json_encode(array("Status" => 1));
            
        }
    }
    public function get_invoice_no()
    {
        //$query_invoice_no_test = $this->db->query("select MAX(CAST(SUBSTRING(invoice_no, -4) AS UNSIGNED)) as invoice_no from billing where status = '0' and firm_id = '".$this->session->userdata('firm_id')."')"); //invoice_number excluding the 9 first characters, converts to int, and selects max from it.

        //echo json_encode($query_invoice_no_test);

        // $query_invoice_no = $this->db->query("SELECT invoice_no FROM billing where id = (SELECT max(id) FROM billing where status = '0' and firm_id = '".$this->session->userdata('firm_id')."')");
        //$id = $query->row()->id;

        $query_invoice_no = $this->db->query("select id, invoice_no, MAX(CAST(SUBSTRING(invoice_no, -4) AS UNSIGNED)) as latest_invoice_no from billing where status = '0' and firm_id = '".$this->session->userdata('firm_id')."' GROUP BY invoice_no ORDER BY latest_invoice_no DESC LIMIT 1");

        //echo json_encode($query_invoice_no->result_array());

        if ($query_invoice_no->num_rows() > 0) 
        {
            $query_invoice_no = $query_invoice_no->result_array();

            // $last_section_invoice_no = (int)$query_invoice_no[0]["invoice_no"] + 1;
            // $number = "AB-".date("Y")."-".str_pad($last_section_invoice_no,4,"0",STR_PAD_LEFT);

            $last_section_invoice_no = (string)$query_invoice_no[0]["invoice_no"];
            //echo (substr_replace($last_section_invoice_no, "", -1));
            //echo ((int)(substr($last_section_invoice_no, -4)) + 1);
            
            // if(strlen((int)($last_section_invoice_no[strlen($last_section_invoice_no)-1]) + 1) == 2)
            // {
            //     $number = substr_replace($last_section_invoice_no, "", -1).((int)($last_section_invoice_no[strlen($last_section_invoice_no)-1]) + 1);
            // }
            $number = substr_replace($last_section_invoice_no, "", -4).(str_pad((int)(substr($last_section_invoice_no, -4)) + 1, 4, '0', STR_PAD_LEFT));


        }
        else
        {
            $number = "AB-".date("Y")."-".str_pad(1,4,"0",STR_PAD_LEFT);
        }

        echo json_encode(array("invoice_no" => $number));
    }

    public function get_recurring_invoice_no()
    {
        // $query_invoice_no = $this->db->query("select MAX(CAST(SUBSTRING(invoice_no,10, length(invoice_no)-9) AS UNSIGNED)) as invoice_no from recurring_billing"); //invoice_number excluding the 9 first characters, converts to int, and selects max from it.

        // //MAX(CAST(SUBSTRING(invoice_number,11, length(invoice_number)-10) AS UNSIGNED)) 

        // //echo json_encode($query_invoice_no->result_array());

        // if ($query_invoice_no->num_rows() > 0) 
        // {
        //     $query_invoice_no = $query_invoice_no->result_array();
        //     //$array_invoice_no = explode('-', $query_invoice_no[0]["invoice_no"]);
        //     $last_section_invoice_no = (int)$query_invoice_no[0]["invoice_no"] + 1;
        //     $number = "REC-".date("Y")."-".$last_section_invoice_no;

        // }
        // else
        // {
        //     $number = "REC-".date("Y")."-1";
        // }

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
        if(!isset($_POST["recurring_issue_date"]))
        {
            $recurring_issue_date = "";
        }
        else
        {
            $recurring_issue_date = $_POST["recurring_issue_date"];
        }
        //echo json_encode(isset($_POST["recurring_issue_date"]));
        $amount = array_values($_POST["amount"]);
        $invoice_description = array_values($_POST["invoice_description"]);
        $service = array_values($_POST["service"]);
        $period_start_date = array_values($_POST["period_start_date"]);
        $period_end_date = array_values($_POST["period_end_date"]);
        $unit_pricing = array_values($_POST["unit_pricing"]);
        $rate = $_POST["rate"];
        $own_letterhead_checkbox = $_POST["hidden_own_letterhead_checkbox"];
        //$client_billing_info_id = array_values($_POST["client_billing_info_id"]);
        $grand_total = $_POST["grand_total"];
        $gst_rate = $_POST["gst_rate"];

        //$current_date = DATE("d/m/Y",$billing_date);

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
            $billing['billing_period'] = $frequency;
            $billing['recu_invoice_issue_date'] = $recurring_issue_date;

            $this->db->delete('recurring_billing_service', array('billing_id' => $billing_result[0]['id']));

            $this->db->update("recurring_billing",$billing,array("id" => $billing_result[0]['id']));

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
            $billing['billing_period'] = $frequency;
            $billing['recu_invoice_issue_date'] = $recurring_issue_date;
            $billing['amount'] = 0;
            $billing['outstanding'] = 0;
            for($p = 0; $p < count($amount); $p++)
            {
                $billing['amount'] = $billing['amount'] + ((1+($gst_rate / 100)) * (float)str_replace(',', '', $amount[$p]));
                $billing['outstanding'] = $billing['outstanding'] + ((1+($gst_rate / 100)) * (float)str_replace(',', '', $amount[$p]));
            }
            
            $billing['currency_id'] = $currency;

            $this->db->insert("recurring_billing",$billing);
            $billing_service['billing_id'] = $this->db->insert_id();

            
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
            $billing_service['gst_rate'] = $gst_rate;

            $this->db->insert("recurring_billing_service",$billing_service);
        }
        
        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
    }

    public function save_billing()
    {
        //echo json_encode($_POST);

        $company_code = $_POST["client_name"];
        $billing_date = $_POST["billing_date"];
        $currency = $_POST["currency"];
        $invoice_no = $_POST["invoice_no"];
        $previous_invoice_no = $_POST["previous_invoice_no"];
        $amount = array_values($_POST["amount"]);
        $invoice_description = array_values($_POST["invoice_description"]);
        $service = array_values($_POST["service"]);
        $period_start_date = array_values($_POST["period_start_date"]);
        $period_end_date = array_values($_POST["period_end_date"]);
        $unit_pricing = array_values($_POST["unit_pricing"]);
        $rate = $_POST["rate"];
        //$own_letterhead_checkbox = $_POST["hidden_own_letterhead_checkbox"];
        //$client_billing_info_id = array_values($_POST["client_billing_info_id"]);
        $grand_total = $_POST["grand_total"];
        $gst_rate = $_POST["gst_rate"];

        //$current_date = DATE("d/m/Y",$billing_date);
        

        // if($previous_invoice_no == null || $previous_invoice_no == "")
        // {
        //     $billing_result = $this->db->query("select * from billing where company_code='".$company_code."' AND invoice_no = '".$invoice_no."' AND status = '0'");
        // }
        // else
        // {
            $billing_result = $this->db->query("select * from billing where company_code='".$company_code."' AND invoice_no = '".$previous_invoice_no."' AND status = '0'");
        //}

        $billing_result = $billing_result->result_array();

        // $check_billing_id_result = $this->db->query("select * from billing where invoice_no = '".$invoice_no."' AND status = '0' AND id != '".$billing_result[0]['id']."'");

        // $check_billing_id_result = $check_billing_id_result->result_array();

        //echo json_encode($check_billing_id_result);

        // if(!$check_billing_id_result)
        // {
            if($billing_result)
            {
               /* $billing['amount'] = $billing_result[0]['amount'];
                $billing['outstanding'] = $billing_result[0]['outstanding'];*/
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
                    //$billing['own_letterhead_checkbox'] = $own_letterhead_checkbox;
                    $billing['outstanding'] = $new_outstanding;

                    //$billing_receipt_record['previous_outstanding'] = $new_amount;

                    $this->db->delete('billing_service', array('billing_id' => $billing_result[0]['id']));
                    /*for($i = 0; $i < count($amount); $i++)
                    {
                        $billing['amount'] = $billing['amount'] + (int)str_replace(',', '', $amount[$i]);
                    }*/
                    
                    //echo json_encode($billing);
                    /*$this->db->update("billing_receipt_record",$billing_receipt_record,array("billing_id" => $billing_result[0]['id']));*/
                    $this->db->update("billing",$billing,array("id" => $billing_result[0]['id']));

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
               /* $billing['amount'] = $billing_result[0]['amount'] + ((1+($billing_service['gst_rate'] / 100)) * $result[0]['amount']);
                    $billing['outstanding'] = $billing_result[0]['outstanding'] + ((1+($billing_service['gst_rate'] / 100)) * $result[0]['amount']);*/
                $check_billing_id_result = $this->db->query("select * from billing where invoice_no = '".$invoice_no."' AND status = '0'");

                $check_billing_id_result = $check_billing_id_result->result_array();
                //echo json_encode($check_billing_id_result);
                if(!$check_billing_id_result)
                {
                    $billing['invoice_no'] = $invoice_no;
                    $billing['firm_id'] = $this->session->userdata("firm_id");
                    $billing['company_code'] = $company_code;
                    $billing['invoice_date'] = $billing_date;
                    $billing['rate'] = $rate;
                    //$billing['own_letterhead_checkbox'] = $own_letterhead_checkbox;
                    $billing['amount'] = 0;
                    $billing['outstanding'] = 0;
                    for($p = 0; $p < count($amount); $p++)
                    {
                        $billing['amount'] = $billing['amount'] + ((1+($gst_rate / 100)) * (float)str_replace(',', '', $amount[$p]));
                        $billing['outstanding'] = $billing['outstanding'] + ((1+($gst_rate / 100)) * (float)str_replace(',', '', $amount[$p]));
                    }
                    
                    $billing['currency_id'] = $currency;

                    //$billing_service['client_billing_info_id'] = $result[0]['client_billing_info_id'];

                    $this->db->insert("billing",$billing);
                    $billing_service['billing_id'] = $this->db->insert_id();

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
                    $billing_service['amount'] = (float)str_replace(',', '', $amount[$k]);
                    $billing_service['unit_pricing'] = $unit_pricing[$k];
                    $billing_service['period_start_date'] = $period_start_date[$k];
                    $billing_service['period_end_date'] = $period_end_date[$k];
                    $billing_service['gst_rate'] = $gst_rate;

                    $this->db->insert("billing_service",$billing_service);
                }
                
                echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
            }
            else
            {
                echo json_encode(array("Status" => 2, 'message' => 'This Invoice No is already use.', 'title' => 'Error'));
            }
        // }
        // else
        // {
        //     echo json_encode(array("Status" => 2, 'message' => 'This Invoice No is in used.', 'title' => 'Error'));
        // }
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

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Recurring', base_url('billings'));
        $this->mybreadcrumb->add('Edit Recurrings - '.$this->data['edit_recurring_bill'][0]->company_name.'', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('client/create_recurring.php', $meta, $this->data);
    }

    public function edit_bill($id)
    {
        $bc = array(array('link' => '#', 'page' => lang('Edit Billings')));
        $meta = array('page_title' => lang('Edit Billings'), 'bc' => $bc, 'page_name' => 'Edit Billings');

        $this->data['edit_bill'] =$this->db_model->get_edit_bill($id);
        $this->data['edit_bill_service'] =$this->db_model->get_edit_bill_service($id);
        $this->data['get_client_billing_info'] = $this->db_model->get_client_billing_info($id);
        $this->data['get_service_category'] = $this->db_model->get_service_category($id);
        $this->data['get_unit_pricing'] = $this->db_model->get_unit_pricing($id);

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Billing', base_url('billings'));
        $this->mybreadcrumb->add('Edit Billings - '.$this->data['edit_bill'][0]->company_name.'', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('client/create_billing.php', $meta, $this->data);
    }

    public function review_paid_bill($id)
    {
        $bc = array(array('link' => '#', 'page' => lang('Details Billings')));
        $meta = array('page_title' => lang('Details Billings'), 'bc' => $bc, 'page_name' => 'Details Billings');

        $this->data['edit_bill'] =$this->db_model->get_edit_bill($id);
        $this->data['edit_bill_service'] =$this->db_model->get_edit_bill_service($id);
        $this->data['get_client_billing_info'] = $this->db_model->get_history_client_billing_info($id);
        $this->data['get_service_category'] = $this->db_model->get_service_category($id);
        $this->data['get_unit_pricing'] = $this->db_model->get_unit_pricing($id);

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Billing', base_url('billings'));
        $this->mybreadcrumb->add('Details Billings - '.$this->data['edit_bill'][0]->company_name.'', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('client/review_paid_billing.php', $meta, $this->data);
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
                    $this->db->set('status', 1);
                    $this->db->where('id', $billing_id[$i]);
                    $this->db->update('billing');

                    //$this->db->delete('billing_service', array("billing_id" => $billing_id[$i]));

                    // $q = $this->db->query('select * from billing_receipt_record where billing_id ="'.$billing_id[$i].'"');

                    // if ($q->num_rows())
                    // {
                    //     $q = $q->result_array();

                    //     for($j = 0; $j <count($q); $j++)
                    //     {
                    //         $this->db->delete('receipt', array("id" => $q[$j]["receipt_id"]));
                    //     }
                    // }
                    

                    // $this->db->delete('billing_receipt_record', array("billing_id" => $billing_id[$i]));
                }

                echo json_encode(array("Status" => 1, 'message' => 'Information Deleted', 'title' => 'Deleted'));
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
                    $q = $this->db->query('select * from billing_receipt_record where receipt_id = "'.$receipt_id[$i].'" AND billing_id = "'.$billing_id[$i].'" AND firm_id = "'.$this->session->userdata("firm_id").'"');

                    if ($q->num_rows())
                    {
                        $q = $q->result_array();

                        $query_billing = $this->db->query('select * from billing where id ="'.$q[0]["billing_id"].'" AND status = 0 AND firm_id = "'.$this->session->userdata("firm_id").'"');

                        if ($query_billing->num_rows())
                        {
                            $query_billing = $query_billing->result_array();
                            $outstanding = (float)$query_billing[0]["outstanding"] + (float)$q[0]["received"];

                            //echo json_encode($query_billing[0]);

                            $this->db->set('outstanding', $outstanding);
                            $this->db->where('id', $q[0]["billing_id"]);
                            $this->db->update('billing');
                        }

                        $this->db->delete('billing_receipt_record', array("receipt_id" => $receipt_id[$i], "billing_id" => $billing_id[$i], "firm_id" => $this->session->userdata("firm_id")));

                        $receipt_info = $this->db->query('select * from receipt where id ="'.$receipt_id[$i].'"');

                        $receipt_info = $receipt_info->result_array();

                        $total_amount_received = (float)$receipt_info[0]["total_amount_received"] - (float)$q[0]["received"];

                        if($total_amount_received > 0)
                        {
                            $this->db->set('total_amount_received', $total_amount_received);
                            $this->db->where('id', $receipt_id[$i]);
                            $this->db->update('receipt');
                        }
                        else
                        {
                            $this->db->delete('receipt', array("id" => $receipt_id[$i]));
                        }
                        
                    }
                }

                echo json_encode(array("Status" => 1, 'message' => 'Information Deleted', 'title' => 'Deleted'));
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

                            //echo json_encode($query_billing[0]);

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
                    // $this->db->set('status', 1);
                    // $this->db->where('id', $recurring_billing_id[$i]);
                    // $this->db->update('billing');
                    $this->db->delete('recurring_billing', array("id" => $recurring_billing_id[$i], "firm_id" => $this->session->userdata("firm_id")));
                    $this->db->delete('recurring_billing_service', array("billing_id" => $recurring_billing_id[$i]));

                    // $q = $this->db->query('select * from billing_receipt_record where billing_id ="'.$billing_id[$i].'"');

                    // if ($q->num_rows())
                    // {
                    //     $q = $q->result_array();

                    //     for($j = 0; $j <count($q); $j++)
                    //     {
                    //         $this->db->delete('receipt', array("id" => $q[$j]["receipt_id"]));
                    //     }
                    // }
                    

                    // $this->db->delete('billing_receipt_record', array("billing_id" => $billing_id[$i]));
                }

                echo json_encode(array("Status" => 1, 'message' => 'Information Deleted', 'title' => 'Deleted'));
            }
        
        }

    }

    public function get_company_service()
    {
        $company_code = $_POST["company_code"];
        $currency = $_POST["currency"];

        $q = $this->db->query("select client.company_name, client.company_code, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2 from client where company_code='".$company_code."'");

        $q = $q->result_array();

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

        // $p = $this->db->query('select client_billing_info.*, billing_info_service.service as service_name, billing_info_frequency.frequency as frequency_name from client_billing_info left join billing_info_service on client_billing_info.service = billing_info_service.id left join billing_info_frequency on client_billing_info.frequency = billing_info_frequency.id where company_code ="'.$company_code.'"');

        // $p = $this->db->query("select billing_info_service.*, billing_info_service.service as service_name, billing_info_service_category.category_description from billing_info_service left join billing_info_service_category on billing_info_service_category.category_code = billing_info_service.category_code order by billing_info_service.id");

        $p = $this->db->query("select client_billing_info.*, our_service_info.service_type, our_service_info.service_name, billing_info_service_category.category_description from client_billing_info left join our_service_info on our_service_info.id = client_billing_info.service left join billing_info_service_category on billing_info_service_category.id = our_service_info.service_type where client_billing_info.company_code = '".$company_code."' and client_billing_info.currency = '".$currency."' and client_billing_info.deleted = 0");

        $selected_billing_info_service_category = $this->db->query("select billing_info_service_category.* from billing_info_service_category");

        $unit_pricing_query = $this->db->query("select * from unit_pricing");

        $p = $p->result_array();
        $selected_billing_info_service_category = $selected_billing_info_service_category->result_array();
        $unit_pricing_query = $unit_pricing_query->result_array();
/*            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
                return $data;
            }*/
        echo json_encode(array("Status" => 1, "address" => $address, "service" => $p, 'selected_billing_info_service_category' => $selected_billing_info_service_category, 'unit_pricing' => $unit_pricing_query));
        //echo json_encode($address);
    }

    public function get_gst_rate()
    {
        // /$invoice_date = $_POST["billing_date"];

        $array = explode('/', $_POST["billing_date"]);
        $tmp = $array[0];
        $array[0] = $array[1];
        $array[1] = $tmp;
        unset($tmp);
        $invoice_date = implode('/', $array);
        $time = strtotime($invoice_date);
        $invoice_date = date('Y-m-d',$time);
        $invoice_date = strtotime($invoice_date);

        //$firm = $this->db->query("select * from firm");

        $this->db->select('firm.*')
                ->from('firm')
                ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
                ->where('user_firm.user_id = '.$this->session->userdata('user_id'))
                ->where('user_firm.in_use = 1');

        $firm = $this->db->get();
        $firm = $firm->result_array();

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

            //echo json_encode($previous_gst_date > $gst_date);
            

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
                    $billing_service['gst_rate'] = $firm[0]["gst"];
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
                    else if($invoice_date >= $gst_date)
                    {
                        $get_gst_rate = $firm[0]["gst"];
                    }
                    else
                    {
                        $get_gst_rate = 0;
                    }
                }
            }
            
        }
        else
        {
            $get_gst_rate = 0;
        }

        echo json_encode(array("Status" => 1, "get_gst_rate" => $get_gst_rate, "invoice_date" => $invoice_date, "previous_gst_date" => $previous_gst_date, "gst_date" => $gst_date));
    }
}
