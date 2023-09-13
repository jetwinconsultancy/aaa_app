<?php defined('BASEPATH') OR exit('No direct script access allowed');

class user_billings extends MY_Controller
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
                // }
            } 
        }
        else
        {
            $this->data['billings'] = $this->db_model->get_all_unpaid_billings();

            
        }

        $this->data['paid_billings'] = $this->db_model->get_all_paid_billings();

        $this->data['receipt'] = $this->db_model->get_all_receipt();
        
        $this->data['template'] = $this->db_model->get_all_template();

        /*$billings = $this->db->query("select billing.*, client.company_name from billing left join client on client.company_code = billing.company_code");

        $billings = $billings->result_array();

        $this->data['billings'] = $billings;*/

        //echo json_encode($this->data['billings']);
		// $this->data['page_name'] = 'Clients';
        $this->page_construct('user_billings.php', $meta, $this->data);

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
        
        $this->data['template'] = $this->db_model->get_all_template();

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

    public function get_billing_info()
    {
        $company_code = $_POST["company_code"];

        $q = $this->db->query("select billing.*, client.company_name, client.incorporation_date from billing left join client on client.company_code = billing.company_code where outstanding > 0 AND billing.company_code = '".$_POST["company_code"]."'");

            //echo json_encode($q);
        if ($q->num_rows() > 0) {
            echo json_encode(array("status" => 1, 'result' => $q->result()));

            // foreach (($q->result()) as $row) {
                // $data[] = $row;
            // }
            // return $data;
        } else echo json_encode(array("status" => 0));
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

    public function save_template()
    {
        //echo json_encode($_POST);
        $id = $_POST["id"];
        $invoice_description = $_POST["invoice_description"];
        $amount = $_POST["amount"];


        for($i = 0; $i < count($_POST['id']); $i++ )
        {
            

                $template['invoice_description'] = $_POST["invoice_description"][$i];
                $template['amount'] = (float)str_replace(',', '', $_POST['amount'][$i]);

                $this->db->update("billing_template",$template,array("id" => $_POST['id'][$i], "firm_id" => $this->session->userdata("firm_id")));

            

        }
        echo json_encode(array("Status" => 1));

        /*foreach($invoice_description as $ind=>$val) 
        {
            $inv_des  = $invoice_description[$ind];
            $amot = $amount[$ind];

            $this->form_validation->set_rules("invoice_description[".$ind."]", "Invoice Description", "required");
            $this->form_validation->set_rules("amount[".$ind."]", "Amount", "required");

        }

        if ($this->form_validation->run() == FALSE)
        {
            $a = array();
            for($i = 0; $i < count($invoice_description); $i++) 
            {
                $arr = array(
                    'invoice_description'+$i+'' => strip_tags(form_error("invoice_description[".$i."]")),
                    'amount'+$i+'' => strip_tags(form_error("amount[".$i."]")),
                );
                $a = array_push($a,$arr);
            }

            echo json_encode(array("Status" => 0, "error" => $a));
        }*/
        /*$this->form_validation->set_rules('company_name', 'Company Name', 'required');
        $this->form_validation->set_rules('register_no', 'Register No', 'required');*/

        /*if ($this->form_validation->run() == FALSE)
        {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $bc = array(array('link' => '#', 'page' => lang('Add Person Profile')));
            $meta = array('page_title' => lang('Add Person Profile'), 'bc' => $bc, 'page_name' => 'Add Person Profile');
            $arr = array(
                'register_no' => strip_tags(form_error('register_no')),
                'company_name' => strip_tags(form_error('company_name')),
                'company_postal_code' => strip_tags(form_error('company_postal_code')),
                'company_foreign_address1' => strip_tags(form_error('company_foreign_address1')),
                'company_foreign_address2' => strip_tags(form_error('company_foreign_address2')),
            );
            echo json_encode(array("Status" => 0, "error" => $arr));

        }*/
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

                    /*if($new_outstanding == 0)
                    {
                        $this->db->delete("billing_receipt_record",array("receipt_id" => $_POST['receipt_id'], "billing_id" => $_POST['id'][$i], "id >" => $query[0]["id"]));
                    }
                    else
                    {*/
                        $query_other_receipt_record = $this->db->query("select * from billing_receipt_record where billing_id='".$_POST['id'][$i]."' AND id > '".$query[0]["id"]."' ORDER BY id");


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
                            $new_outstanding = (float)$query_billing[0]["amount"] - (float)str_replace(',', '', $_POST['received'][$i]);
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
        $query_invoice_no = $this->db->query("select max(invoice_no) as invoice_no from billing WHERE (RIGHT(invoice_no, 1) IN ('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'))");

        //echo json_encode($query_test);

        if ($query_invoice_no->num_rows() > 0) 
        {
            $query_invoice_no = $query_invoice_no->result_array();
            $array_invoice_no = explode('-', $query_invoice_no[0]["invoice_no"]);
            $last_section_invoice_no = (int)$array_invoice_no[2] + 1;
            $number = date("Y")."-ABC-".$last_section_invoice_no;

        }
        else
        {
            $number = date("Y")."-ABC-1";
        }

        echo json_encode(array("invoice_no" => $number));
    }

    public function save_billing()
    {
        //echo json_encode($_POST);

        $company_code = $_POST["client_name"];
        $billing_date = $_POST["billing_date"];
        $currency = $_POST["currency"];
        $invoice_no = $_POST["invoice_no"];
        $amount = array_values($_POST["amount"]);
        $invoice_description = array_values($_POST["invoice_description"]);
        $service = $_POST["service"];
        $rate = $_POST["rate"];
        $client_billing_info_id = array_values($_POST["client_billing_info_id"]);
        $grand_total = $_POST["grand_total"];
        $gst_rate = $_POST["gst_rate"];

        //$current_date = DATE("d/m/Y",$billing_date);

        $billing_result = $this->db->query("select * from billing where invoice_date = '".$billing_date."' AND company_code='".$company_code."' AND invoice_no = '".$invoice_no."'");

        $billing_result = $billing_result->result_array();

        //echo json_encode($billing_result);

        if($billing_result)
        {
           /* $billing['amount'] = $billing_result[0]['amount'];
            $billing['outstanding'] = $billing_result[0]['outstanding'];*/
            $new_amount = (float)str_replace(',', '', $grand_total);
            $new_outstanding = (float)str_replace(',', '', $grand_total) - ($billing_result[0]['amount'] - $billing_result[0]['outstanding']);

            $billing['currency_id'] = $currency;
            $billing['amount'] = $new_amount;
            $billing['rate'] = $rate;
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
        }
        else
        {
           /* $billing['amount'] = $billing_result[0]['amount'] + ((1+($billing_service['gst_rate'] / 100)) * $result[0]['amount']);
                $billing['outstanding'] = $billing_result[0]['outstanding'] + ((1+($billing_service['gst_rate'] / 100)) * $result[0]['amount']);*/


            $billing['invoice_no'] = $invoice_no;
            $billing['firm_id'] = $this->session->userdata("firm_id");
            $billing['company_code'] = $company_code;
            $billing['invoice_date'] = $billing_date;
            $billing['rate'] = $rate;
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

            
        }
        for($k = 0; $k < count($client_billing_info_id); $k++)
        {
            $billing_service['invoice_date'] = $billing_date;
            $billing_service['client_billing_info_id'] = $client_billing_info_id[$k];
            $billing_service['invoice_description'] = $invoice_description[$k];
            $billing_service['amount'] = (float)str_replace(',', '', $amount[$k]);
            $billing_service['gst_rate'] = $gst_rate;

            $this->db->insert("billing_service",$billing_service);
        }
        
        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
    }

    public function edit_bill($id)
    {
        $bc = array(array('link' => '#', 'page' => lang('Edit Billings')));
        $meta = array('page_title' => lang('Edit Billings'), 'bc' => $bc, 'page_name' => 'Edit Billings');

        $this->data['edit_bill'] =$this->db_model->get_edit_bill($id);
        $this->data['edit_bill_service'] =$this->db_model->get_edit_bill_service($id);
        $this->data['get_client_billing_info'] = $this->db_model->get_client_billing_info($id);

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Billing', base_url('billings'));
        $this->mybreadcrumb->add('Edit Billings - '.$this->data['edit_bill'][0]->company_name.'', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('client/create_billing.php', $meta, $this->data);
    }

    public function get_company_service()
    {
        $company_code = $_POST["company_code"];

        $q = $this->db->query("select client.company_name, client.company_code, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2 from client where company_code='".$company_code."'");

        $q = $q->result_array();

        $address = $q[0]["street_name"].'
#'.$q[0]["unit_no1"]."-".$q[0]["unit_no2"].' '.$q[0]["building_name"].' 
Singapore '.$q[0]["postal_code"];

        $p = $this->db->query('select client_billing_info.*, billing_info_service.service as service_name, billing_info_frequency.frequency as frequency_name from client_billing_info left join billing_info_service on client_billing_info.service = billing_info_service.id left join billing_info_frequency on client_billing_info.frequency = billing_info_frequency.id where company_code ="'.$company_code.'"');

        $p = $p->result_array();
/*            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
                return $data;
            }*/
        echo json_encode(array("Status" => 1, "address" => $address, "service" => $p));
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

    public function get_billing_info_admin_service()
    {
/*      $result = $this->db->query("select * from client_officers AS A where position = 'Director' AND company_code='".$company_code."' AND NOT EXISTS (SELECT alternate_of from client_officers AS B where A.id = B.alternate_of)");*/

        $service = $_POST['service'];
        $company_code = $_POST['company_code'];

        $ci =& get_instance();

        $query = "select * from billing_info_admin_service";

        $selected_query = "select A.id from billing_info_service AS A WHERE EXISTS (SELECT service from client_billing_info AS B WHERE company_code = '".$company_code."' AND A.id = B.service)";

        $result = $ci->db->query($query);
        $selected_result = $ci->db->query($selected_query);
        
        //echo json_encode($result->result_array());
        $result = $result->result_array();
        $selected_result = $selected_result->result_array();

        if (count($selected_result) == 0) {
            $selected_querys = "select A.id from billing_info_service AS A WHERE EXISTS (SELECT service from billing_template AS B WHERE A.id = B.service)";

            $selected_result = $ci->db->query($selected_querys);

            $selected_result = $selected_result->result_array();
        }

        if(!$result) {
          throw new exception("Service not found.");
        }

        $res = array();
        foreach($result as $row) {
            $res[$row['id']] = $row['service'];
        }

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
        

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Service fetched successfully.", 'result'=>$res, 'selected_service'=>$select_service, 'selected_query'=> $selected_res);

        echo json_encode($data);
    }
}
