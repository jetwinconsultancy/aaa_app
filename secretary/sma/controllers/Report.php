<?php defined('BASEPATH') OR exit('No direct script access allowed');

class report extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        $this->load->model('document_model');
        $this->load->library(array('encryption', 'session', 'form_validation', 'zip'));
    }

    public function index()
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

        /*$pending_documents_files_id = array();
        $this->session->set_userdata(array(
            'pending_documents_files_id'  =>  $pending_documents_files_id,
        ));*/

        $bc = array(array('link' => '#', 'page' => lang('Report')));
        $meta = array('page_title' => lang('Report'), 'bc' => $bc, 'page_name' => 'Report');

        // $this->db->select('firm.*, firm_telephone.telephone, firm_fax.fax, firm_email.email, user_firm.user_id, user_firm.default_company, user_firm.in_use')
        //         ->from('firm')
        //         ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
        //         ->join('firm_telephone', 'firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1', 'left')
        //         ->join('firm_fax', 'firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1', 'left')
        //         ->join('firm_email', 'firm_email.firm_id = firm.id AND firm_email.primary_email = 1', 'left')
        //         ->where('user_firm.user_id = '.$this->session->userdata('user_id'));

        $result_firm = $this->db->query("select firm.* from firm left join user_firm on user_firm.firm_id = firm.id AND user_firm.user_id = '".$this->session->userdata('user_id')."' where user_firm.user_id = '".$this->session->userdata('user_id')."'");
        $result_firm = $result_firm->result_array();
        if(count($result_firm) > 0)
        {
            for($j = 0; $j < count($result_firm); $j++)
            {
                if($result_firm[$j]['branch_name'] != null)
                {
                    $res[$result_firm[$j]['id']] = $result_firm[$j]['name'].' ('.$result_firm[$j]['branch_name'].')';
                }
                else
                {
                    $res[$result_firm[$j]['id']] = $result_firm[$j]['name'];
                }
            }
        }
        else
        {
            $res = null;
        }
        $this->data["firm"] = $res;

        $result_gst_firm = $this->db->query("select firm.*, gst_firm.register_date as gst_register_date, gst_firm.deregister_date as gst_deregister_date from firm left join user_firm on user_firm.firm_id = firm.id AND user_firm.user_id = '".$this->session->userdata('user_id')."' left join gst_firm on gst_firm.firm_id = firm.id where user_firm.user_id = '".$this->session->userdata('user_id')."' AND firm.gst_checkbox = 1");
        $result_gst_firm = $result_gst_firm->result_array();
        if(count($result_gst_firm) > 0)
        {
            for($j = 0; $j < count($result_gst_firm); $j++)
            {
                if($result_gst_firm[$j]['branch_name'] != null)
                {
                    $result_gst_firm[$j]['name'] = $result_gst_firm[$j]['name'].' ('.$result_gst_firm[$j]['branch_name'].')';
                }
            }
        }
        else
        {
            $result_gst_firm = null;
        }
        $this->data["gst_firm"] = $result_gst_firm;

        $service_category = $this->db->query("select * from billing_info_service_category");
        $service_category = $service_category->result_array();
        for($j = 0; $j < count($service_category); $j++)
        {
            $info[$service_category[$j]['id']] = $service_category[$j]['category_description'];
        }
        $this->data["service_category"] = $info;

/*        $this->data['pending_documents'] = $this->document_model->get_all_pending();
        $this->data['all_documents'] = $this->document_model->get_all_document();
        $this->data['document_master'] = $this->document_model->get_all_document_master();
        $this->data['document_reminder'] = $this->document_model->get_all_document_reminder();*/
		// $this->data['page_name'] = 'Clients';
        $this->page_construct('report.php', $meta, $this->data);

        //SELECT `claim`.*, `claim_service`.* FROM `claim_service` LEFT JOIN `claim` ON `claim`.`id` = `claim_service`.`claim_id` WHERE `claim_service`.`id` IN ("2655","2867","3647")
    }

    public function search_report()
    {
        $firm_id = $this->session->userdata('firm_id');
        $register = $_POST['report_to_generate'];
        $client_id = $_POST['client_id'];
        $service_category = $_POST['service_category'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $selected_csp_report_type = $_POST['csp_report_type'];
        $selected_firm_id = $_POST['firm'];
        $selected_gst_report_firm_id = $_POST['gst_report_firm'];
        $selected_type_of_due_date = $_POST['type_of_due_date'];
        $selected_type_of_payment= $_POST['type_of_payment'];
        $selected_payment_status= $_POST['payment_status'];
        $selected_payment_username= $_POST['payment_username'];
        $selected_bank_account_id = $_POST['bank_account'];


/*  
        $client_guarantee_query = $this->db->query('select * from client where company_code = "'.$company_code.'"');

        if ($client_guarantee_query->num_rows() > 0) {

            $client_guarantee_query = $client_guarantee_query->result_array();

            if($client_guarantee_query[0]["company_type"] == "4" || $client_guarantee_query[0]["company_type"] == "5" || $client_guarantee_query[0]["company_type"] == "6")
            {
                $check_member_state = "guarantee";
            }
            else
            {
                $check_member_state = "non-guarantee";
            }
        }*/

        /*if($register == "all")
        {
            echo json_encode(array("check_member_state" => $check_member_state, "register" => $register, $this->search_register_profile($company_code), $this->search_register_officer($company_code, $from, $to), $this->search_register_member($company_code, $from, $to), $this->search_register_charges($company_code, $from, $to), $this->search_register_filing($company_code), $this->search_register_controller($company_code)));
        }
        else */
        if($register == "person_profile")
        {
            echo json_encode(array("register" => $register, $this->search_person_profile($firm_id, $client_id, $from, $to)));
        }
        else if($register == "client_list")
        {
            echo json_encode(array("register" => $register, $this->search_client_list($firm_id, $service_category)));
        }
        else if($register == "due_date")
        {
            echo json_encode(array("register" => $register, $this->search_due_date($firm_id, $selected_type_of_due_date, $from, $to)));
        }
        else if($register == "list_of_invoice")
        {
            echo json_encode(array("register" => $register, $this->search_list_of_invoice($selected_firm_id, $from, $to)));
        }
        else if($register == "list_of_credit_note")
        {
            echo json_encode(array("register" => $register, $this->search_list_of_credit_note($selected_firm_id, $from, $to)));
        }
        else if($register == "invoice_period")
        {
            echo json_encode(array("register" => $register, $this->search_invoice_period()));
        }
        else if($register == "payment")
        {
            echo json_encode(array("register" => $register, $this->search_payment($selected_firm_id, $selected_type_of_payment, $selected_payment_status, $selected_payment_username)));
        }
        else if($register == "bank_transaction")
        {
            echo json_encode(array("register" => $register, $this->search_bank_transaction($selected_firm_id, $selected_bank_account_id, $from, $to)));
        }
        else if($register == "sales_report")
        {
            echo json_encode(array("register" => $register, $this->search_sales_report($selected_firm_id, $from, $to)));
        }
        else if($register == "register_contorller")
        {
            echo json_encode(array("register" => $register, $this->search_register_controller($selected_firm_id)));
        }
        else if($register == "list_of_recurring")
        {
            echo json_encode(array("register" => $register, $this->search_list_of_recurring($selected_firm_id, $from, $to)));
        }
        else if($register == "list_of_receipt")
        {
            echo json_encode(array("register" => $register, $this->search_list_of_receipt($selected_firm_id, $from, $to)));
        }
        else if($register == "list_of_document")
        {
            echo json_encode(array("register" => $register, $this->search_list_of_document($selected_firm_id, $from, $to)));
        }
        else if($register == "gst_report")
        {
            echo json_encode(array("register" => $register, $this->search_list_of_gst_report($selected_gst_report_firm_id, $from, $to)));
        }
        else if($register == "csp_report")
        {
            echo json_encode(array("register" => $register, $this->search_list_of_csp_report($selected_csp_report_type, $selected_firm_id, $from, $to)));
        }
        else if($register == "progress_bill_report")
        {
            echo json_encode(array("register" => $register, $this->search_progress_bill_report($selected_firm_id, $from, $to)));
        }
        /* else if($register == "filing")
        {
            echo json_encode(array("register" => $register, $this->search_register_filing($company_code)));
        }
        else if($register == "officer")
        {
            echo json_encode(array("register" => $register, $this->search_register_officer($company_code, $from, $to)));
        }
        else if($register == "charges")
        {
            
            echo json_encode(array("register" => $register, $this->search_register_charges($company_code, $from, $to)));
        }
        else if($register == "controller")
        {
            
            echo json_encode(array("register" => $register, $this->search_register_controller($company_code, $from, $to)));
        }
        else
        {
            echo json_encode(array("register" => null));
        }*/
    }

    public function search_person_profile($firm_id, $client_id, $start = null, $end = null)
    {
        $company_code = $this->db->query("select company_code from client where deleted != 1");

        $company_code = $company_code->result_array();

        $where = "";

        if ($start != NULL)
        {
            if ($end != NULL)
            {

                $where = ' AND STR_TO_DATE(date_of_appointment,"%d/%m/%Y") >= STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y") >= STR_TO_DATE(date_of_cessation,"%d/%m/%Y")';
            }
            else
            {
                $where = ' AND STR_TO_DATE(date_of_appointment,"%d/%m/%Y") >= STR_TO_DATE("'. $start. '","%d/%m/%Y")';
            }
        }
        //echo $where;
        $data = array();
        for($r = 0; $r < count($company_code); $r++)
        {
            $officer_info = $this->db->query('select client_officers.*, client.company_name as client_company_name, client_officers_position.position as position_name, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type left join client_officers_position on client_officers.position = client_officers_position.id left join client on client_officers.company_code = client.company_code where client_officers.company_code ="'.$company_code[$r]["company_code"].'"'.$where.'');
            // AND (officer_company.register_no = "'.$client_id.'" OR officer.identification_no = "'.$client_id.'")
            if($officer_info != null)
            {
                if ($officer_info->num_rows() > 0) 
                {
                    foreach (($officer_info->result()) as $row) 
                    {
                        $row->client_company_name = $this->encryption->decrypt($row->client_company_name);
                        if($row->identification_no != null)
                        {
                            $row->identification_no = $this->encryption->decrypt($row->identification_no);
                            $row->name = $this->encryption->decrypt($row->name);
                        }
                        elseif($row->register_no != null)
                        {
                            $row->register_no = $this->encryption->decrypt($row->register_no);
                            $row->company_name = $this->encryption->decrypt($row->company_name);
                        }
                        if($client_id != null)
                        {
                            if(stripos($row->register_no, $client_id) !== FALSE)
                            {
                                $data_officer[] = $row;
                            }
                            else if(stripos($row->identification_no, $client_id) !== FALSE)
                            {
                                $data_officer[] = $row;
                            }
                        }
                    }
                }
            }
        }
        
        if(isset($data_officer))
        {
            $data = array_merge($data, $data_officer);
        }

        for($i = 0; $i < count($company_code); $i++)
        {
           $q = $this->db->query('select member_shares.*, client.company_name as client_company_name, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on member_shares.company_code = client.company_code where member_shares.company_code ="'.$company_code[$i]["company_code"].'" AND number_of_share != 0 GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id');
            //AND (officer_company.register_no = "'.$client_id.'" OR officer.identification_no = "'.$client_id.'")
           //echo json_encode($officer_info->num_rows());

            if($officer_info != null)
            {
                if ($q->num_rows() > 0) 
                {
                    //$q = $q->result_array();
                    foreach ($q->result() as $member_row) 
                    {
                        //$data_member[] = $row;
                            $member_row->client_company_name = $this->encryption->decrypt($member_row->client_company_name);
                            if($member_row->identification_no != null)
                            {
                                $member_row->identification_no = $this->encryption->decrypt($member_row->identification_no);
                                $member_row->name = $this->encryption->decrypt($member_row->name);
                            }
                            elseif($member_row->register_no != null)
                            {
                                $member_row->register_no = $this->encryption->decrypt($member_row->register_no);
                                $member_row->company_name = $this->encryption->decrypt($member_row->company_name);
                            }

                            $member_row->position_name = "Member";
                            $member_row->date_of_appointment = "";
                            $member_row->date_of_cessation = "";

                            // $member[0]["position_name"] = "Member";
                            // $member[0]["date_of_appointment"] = "";
                            // $member[0]["date_of_cessation"] = "";
                            //$q[$key] = array_merge($q[$key], $member[0]);
                            if($client_id != null)
                            {
                                if(stripos($member_row->register_no, $client_id) !== FALSE)
                                {   //print_r($member_row);
                                    $data_member[] = $member_row;
                                }
                                else if(stripos($member_row->identification_no, $client_id) !== FALSE)
                                {
                                    $data_member[] = $member_row;
                                }
                            }
                    }
                }
            }
        }

        if(isset($data_member))
        {
            $data = array_merge($data, $data_member);
            $this->data['person_profile'] = $data;
        }
        else
        {
            $this->data['person_profile'] = $data;
        }
        return $this->data;
    }

    public function search_client_list($firm_id, $service_category)
    {
        /*$this->data['filing_data'] = $this->master_model->get_all_filing_data($user_id);
        return $this->data;*/
        if($service_category != "0")
        {
            $this->db->select('DISTINCT (client.company_name), client.client_code, client.firm_id, client.company_code, client_billing_info.servicing_firm');
        }
        else
        {
            $this->db->select('DISTINCT (client.company_name), client.client_code, client.firm_id, client.company_code');
        }
        $this->db->from('client');
        //$this->db->where('client.firm_id', $firm_id);
        //$this->db->join('user_firm as a', 'a.user_id = "'.$this->session->userdata("user_id").'"', 'inner');
        $this->db->join('user_firm', 'user_firm.firm_id = client.firm_id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left');
        if($service_category != "0")
        {
            $this->db->join('client_billing_info', 'client_billing_info.company_code = client.company_code AND client_billing_info.deactive = 0 AND client_billing_info.deleted = 0', 'right');
            $this->db->join('our_service_info', 'our_service_info.id = client_billing_info.service AND our_service_info.service_type = "'.$service_category.'"', 'right');
        }
        $this->db->where('user_firm.firm_id = client.firm_id');
        $this->db->where('client.deleted = 0');
        $this->db->order_by('client.id', 'desc');
        $this->db->group_by('client.id');
        $q = $this->db->get();
        
        if ($q->num_rows() > 0) 
        {
            $client_info = $q->result_array();

            for($i = 0; $i < count($client_info); $i++)
            {
                $query = $this->db->query("select year_end, agm, due_date_175, 175_extended_to, due_date_201, 201_extended_to from filing where company_code='".$client_info[$i]["company_code"]."' order by id DESC limit 1");

                $filing_info = $query->result_array();

                if ($query->num_rows() > 0) {
                    $client_info[$i] = array_merge($client_info[$i], $filing_info[0]);
                }

                if(isset($client_info[$i]["servicing_firm"]))
                {
                    $query_firm = $this->db->query("select firm.name, firm.branch_name from firm where id = '".$client_info[$i]["servicing_firm"]."'");

                    $firm_info = $query_firm->result_array();

                    if ($query_firm->num_rows() > 0) {
                        $client_info[$i] = array_merge($client_info[$i], $firm_info[0]);
                    }
                }
            }

            foreach (($client_info) as $row) {
                $row["company_name"] = $this->encryption->decrypt($row["company_name"]);
                $data[] = $row;
            }

            $this->data['client_list'] = $data;
            return $this->data;
        }
    }

    public function search_due_date($firm_id, $selected_type_of_due_date, $start = null, $end = null)
    {
        /*$this->data['filing_data'] = $this->master_model->get_all_filing_data($user_id);
        return $this->data;*/

        $this->db->select('client.*');
        $this->db->from('client');
        //$this->db->where('client.firm_id', $firm_id);
        $this->db->join('user_firm', 'user_firm.firm_id = client.firm_id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left');
        $this->db->where('user_firm.firm_id = client.firm_id');
        $this->db->where('client.deleted = 0');
        $this->db->order_by('client.id', 'desc');
        $this->db->group_by('client.id');
        $q = $this->db->get();

        if ($q->num_rows() > 0) 
        {
            $client_info = $q->result_array();

            for($i = 0; $i < count($client_info); $i++)
            {
                $client_info[$i]["registration_no"] = $this->encryption->decrypt($client_info[$i]["registration_no"]);
                $client_info[$i]["company_name"] = $this->encryption->decrypt($client_info[$i]["company_name"]);
                $query = $this->db->query("select year_end, agm, due_date_175, 175_extended_to, due_date_201, 201_extended_to, due_date_197, 197_extended_to from filing where company_code='".$client_info[$i]["company_code"]."' order by id DESC limit 1");

                // $this->db->select('year_end, agm, due_date_175, 175_extended_to, due_date_201, 201_extended_to, due_date_197, 197_extended_to');
                // $this->db->from('filing');
                // if ($start != NULL)
                // {
                //     //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
                //     if ($end != NULL)
                //     {
                //         //$this->db->group_start(); //this will start grouping
                //             $this->db->where('STR_TO_DATE(due_date_175,"%d %M %Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y") OR STR_TO_DATE(175_extended_to,"%d %M %Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
                //             //$this->db->or_where('');
                //         //$this->db->group_end(); //this will end grouping
                //     }
                //     else
                //     {
                //         //$this->db->group_start(); //this will start grouping
                //         $this->db->where('(STR_TO_DATE("'. $start. '","%d/%m/%Y") = STR_TO_DATE(due_date_175,"%d %M %Y")) OR (STR_TO_DATE("'. $start. '","%d/%m/%Y") = STR_TO_DATE(175_extended_to,"%d %M %Y") and 175_extended_to != 0)');
                //         //$this->db->or_where('STR_TO_DATE("'. $start. '","%d/%m/%Y") = STR_TO_DATE(175_extended_to,"%d %M %Y") and 175_extended_to != 0');
                //         //$this->db->group_end(); //this will end grouping
                //     }
                // }
                // $this->db->where('agm', '');
                // $this->db->where('company_code', $client_info[$i]["company_code"]);

                // $query = $this->db->get();


                $filing_info = $query->result_array();

                if ($query->num_rows() > 0) {
                    $client_info[$i] = array_merge($client_info[$i], $filing_info[0]);
                }

                // function date_sort($a, $b) {
                //     return strtotime($b["year_end"]) - strtotime($a["year_end"]);
                // }
                // usort($client_info, "date_sort");

                // if($client_info[$i]["175_extended_to"] != "0")
                // {
                //     $due_date_175 = strtotime($client_info[$i]["175_extended_to"]);
                // }
                // else if($client_info[$i]["due_date_175"] != "Not Applicable")
                // {
                //     $due_date_175 = strtotime($client_info[$i]["due_date_175"]);
                // }
                // else if($client_info[$i]["due_date_175"] == "Not Applicable")
                // {
                //     $due_date_175 = $client_info[$i]["due_date_175"];
                // }

                // if($client_info[$i]["201_extended_to"] != "0")
                // {
                //     $due_date_201 = strtotime($client_info[$i]["201_extended_to"]);
                // }
                // else
                // {
                //     $due_date_201 = strtotime($client_info[$i]["due_date_201"]);
                // }

                // if($due_date_175 == "Not Applicable")
                // {
                //     $now = time(); // or your date as well
                //     $your_date = $due_date_201;
                //     $datediff = $your_date - $now;

                //     $client_info[$i]["days"] = round($datediff / (60 * 60 * 24));
                // }
                // else if($due_date_175 > $due_date_201)
                // {
                //     $now = time(); // or your date as well
                //     $your_date = $due_date_201;
                //     $datediff = $your_date - $now;

                //     $client_info[$i]["days"] = round($datediff / (60 * 60 * 24));
                // }
                // else if($due_date_201 > $due_date_175)
                // {
                //     $now = time(); // or your date as well
                //     $your_date = $due_date_175;
                //     $datediff = $your_date - $now;

                //     $client_info[$i]["days"] = round($datediff / (60 * 60 * 24));
                // }
                // else if($due_date_175 == $due_date_201 && $due_date_175 != '' && $due_date_201 != '')
                // {
                //     $now = time(); // or your date as well
                //     $your_date = $due_date_201;
                //     $datediff = $your_date - $now;

                //     $client_info[$i]["days"] = round($datediff / (60 * 60 * 24));
                // }
                //$latest_agm = date('Y-m-d', strtotime($_POST['agm']));
            }

            foreach (($client_info) as $row) {
                $data[] = $row;
            }
            $this->data['due_date'] = $data;
            return $this->data;
        }
    }

    public function search_list_of_invoice($selected_firm_id, $start = null, $end = null)
    {
        $newBillingListInfo = [];
        $newBillingListKey = [];
        $newKey = 0;
        $data = [];

        $this->db->select('billing_info_service_category.*');
        $this->db->from('billing_info_service_category');
        $category_query = $this->db->get();

        $category_query = $category_query->result_array();
        
        $this->db->select('billing.*, client.company_name, transaction_client.company_name as trans_company_name, client.registration_no, client.former_name, currency.currency as currency_name, billing_info_service_category.category_description, billing_info_service_category.id as billing_info_service_category_id, billing_service.amount as billing_service_amount, billing_service.period_end_date, billing_service.invoice_description, firm.name as firm_name, firm.branch_name, our_service_info.service_name, b.service_name as trans_service_name, c.category_description as trans_category_description, c.id as trans_billing_info_service_category_id, d.service_name as our_service_service_name, e.category_description as our_service_category_description, e.id as our_service_billing_info_service_category_id, transaction_master_with_billing.id as transaction_master_with_billing_id');
        $this->db->from('billing');
        $this->db->join('client', 'client.company_code = billing.company_code', 'left');
        $this->db->join('user_firm', 'user_firm.firm_id = billing.firm_id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left');
        $this->db->join('transaction_client', 'transaction_client.company_code = billing.company_code', 'left');
        $this->db->join('currency', 'billing.currency_id = currency.id', 'left');
        $this->db->join('billing_service', 'billing_service.billing_id = billing.id', 'left');
        $this->db->join('transaction_master_with_billing', 'transaction_master_with_billing.billing_id = billing.id', 'left');

        $this->db->join('transaction_client_billing_info as a', 'a.id = billing_service.service', 'left');
        $this->db->join('our_service_info as b', 'b.id = a.service', 'left');
        $this->db->join('billing_info_service_category as c ', 'c.id = b.service_type', 'left');

        $this->db->join('our_service_info as d', 'd.id = billing_service.service', 'left');
        $this->db->join('billing_info_service_category as e ', 'e.id = d.service_type', 'left');

        $this->db->join('client_billing_info', 'client_billing_info.id = billing_service.service', 'left');
        $this->db->join('our_service_info', 'our_service_info.id = client_billing_info.service', 'left');
        $this->db->join('billing_info_service_category', 'billing_info_service_category.id = our_service_info.service_type', 'left');

        $this->db->join('firm', 'firm.id = billing.firm_id', 'left');
        if ($start != NULL)
        {
            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
            if ($end != NULL)
            {

                $this->db->where('STR_TO_DATE(billing.invoice_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
            }
            else
            {
                $this->db->where('STR_TO_DATE(billing.invoice_date,"%d/%m/%Y") >= STR_TO_DATE("'. $start. '","%d/%m/%Y")');
            }
        }
        $this->db->order_by('id', 'desc');
        if($selected_firm_id != "all")
        {
            $this->db->where('billing.firm_id', $selected_firm_id);
        }
        $this->db->where('user_firm.firm_id = billing.firm_id');
        $this->db->where('billing.status', "0");
        //$this->db->group_by('billing.id');
        $q = $this->db->get();

        if ($q->num_rows() > 0) 
        {
            foreach (($q->result_array()) as $row => $rowValue) 
            {
                //$data[] = $row;
                if($rowValue["company_name"] != null)
                {
                    $rowValue["company_name"] = $this->encryption->decrypt($rowValue["company_name"]);
                }
                else
                {
                    $rowValue["trans_company_name"] = $this->encryption->decrypt($rowValue["trans_company_name"]);
                }
                if(!in_array($rowValue["id"],$newBillingListKey)){
                    ++$newKey;
                    $newBillingListInfo[$newKey]["id"] = $rowValue["id"];
                    $newBillingListInfo[$newKey]["invoice_date"] = $rowValue["invoice_date"];
                    $newBillingListInfo[$newKey]["invoice_no"] = $rowValue["invoice_no"];
                    $newBillingListInfo[$newKey]["company_name"] = (($rowValue["company_name"] != null)?$rowValue["company_name"]:$rowValue["trans_company_name"]);
                    $newBillingListInfo[$newKey]["currency_name"] = $rowValue["currency_name"];
                    $newBillingListInfo[$newKey]["amount"] = $rowValue["amount"];
                    $newBillingListInfo[$newKey]["outstanding"] = $rowValue["outstanding"];
                    $newBillingListInfo[$newKey]["firm_name"] = $rowValue["firm_name"];
                    $newBillingListInfo[$newKey]["branch_name"] = $rowValue["branch_name"];
                    $newBillingListInfo[$newKey]["period_end_date"] = [];
                    $newBillingListInfo[$newKey]["invoice_description"] = [];
                    $newBillingListInfo[$newKey]["service_name"] = [];
                    $newBillingListInfo[$newKey]["billing_service_amount"] = [];
                    $newBillingListInfo[$newKey]["incorp_amount"] = 0.00;
                    $newBillingListInfo[$newKey]["discount_amount"] = 0.00;
                    $newBillingListInfo[$newKey]["training_amount"] = 0.00;
                    $newBillingListInfo[$newKey]["compilation_amount"] = 0.00;

                    for($t = 0; $t < count($category_query); $t++)
                    {
                        $newBillingListInfo[$newKey]["category"][$category_query[$t]["id"]-1] = number_format(0.00, 2);
                    }
                }
                
                array_push($newBillingListInfo[$newKey]["period_end_date"], $rowValue["period_end_date"]);
                array_push($newBillingListInfo[$newKey]["invoice_description"], htmlspecialchars(strtoupper($rowValue["invoice_description"])));
                array_push($newBillingListInfo[$newKey]["billing_service_amount"], $rowValue["billing_service_amount"]);
                if($rowValue["transaction_master_with_billing_id"] != null && ($rowValue["trans_billing_info_service_category_id"] != null || $rowValue["our_service_billing_info_service_category_id"] != null))
                {
                    if($rowValue["trans_billing_info_service_category_id"] != null)
                    {
                        $newBillingListInfo[$newKey]["category"][$rowValue["trans_billing_info_service_category_id"]-1] = number_format(((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"][$rowValue["trans_billing_info_service_category_id"]-1]) + $rowValue["billing_service_amount"]), 2);
                        array_push($newBillingListInfo[$newKey]["service_name"], strtoupper($rowValue["trans_service_name"]));
                    }
                    else if($rowValue["our_service_billing_info_service_category_id"] != null)
                    {
                        $newBillingListInfo[$newKey]["category"][$rowValue["our_service_billing_info_service_category_id"]-1] = number_format(((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"][$rowValue["our_service_billing_info_service_category_id"]-1]) + $rowValue["billing_service_amount"]), 2);
                        array_push($newBillingListInfo[$newKey]["service_name"], strtoupper($rowValue["our_service_service_name"]));
                    }

                    $newBillingListInfo[$newKey]["incorp_amount"] = number_format((float)$this->sumSimilarValue($rowValue["trans_service_name"], 'Incorporation', $newBillingListInfo[$newKey]["incorp_amount"], $rowValue["billing_service_amount"]), 2, '.', '');
                    $newBillingListInfo[$newKey]["discount_amount"] = number_format((float)$this->sumSimilarValue($rowValue["trans_service_name"], 'Discount', $newBillingListInfo[$newKey]["discount_amount"], $rowValue["billing_service_amount"]), 2, '.', '');
                    $newBillingListInfo[$newKey]["training_amount"] = number_format((float)$this->sumSimilarValue($rowValue["trans_service_name"], 'Training', $newBillingListInfo[$newKey]["training_amount"], $rowValue["billing_service_amount"]), 2, '.', '');
                    $newBillingListInfo[$newKey]["compilation_amount"] = number_format((float)$this->sumSimilarValue($rowValue["trans_service_name"], 'Compilation', $newBillingListInfo[$newKey]["compilation_amount"], $rowValue["billing_service_amount"]), 2, '.', '');
                }
                else
                {
                    $newBillingListInfo[$newKey]["category"][$rowValue["billing_info_service_category_id"]-1] = number_format(((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"][$rowValue["billing_info_service_category_id"]-1]) + $rowValue["billing_service_amount"]), 2);
                    array_push($newBillingListInfo[$newKey]["service_name"], strtoupper($rowValue["service_name"]));
                    $newBillingListInfo[$newKey]["incorp_amount"] = number_format((float)$this->sumSimilarValue($rowValue["service_name"], 'Incorporation', $newBillingListInfo[$newKey]["incorp_amount"], $rowValue["billing_service_amount"]), 2, '.', '');
                    $newBillingListInfo[$newKey]["discount_amount"] = number_format((float)$this->sumSimilarValue($rowValue["service_name"], 'Discount', $newBillingListInfo[$newKey]["discount_amount"], $rowValue["billing_service_amount"]), 2, '.', '');
                    $newBillingListInfo[$newKey]["training_amount"] = number_format((float)$this->sumSimilarValue($rowValue["service_name"], 'Training', $newBillingListInfo[$newKey]["training_amount"], $rowValue["billing_service_amount"]), 2, '.', '');
                    $newBillingListInfo[$newKey]["compilation_amount"] = number_format((float)$this->sumSimilarValue($rowValue["service_name"], 'Compilation', $newBillingListInfo[$newKey]["compilation_amount"], $rowValue["billing_service_amount"]), 2, '.', '');
                }

                $newBillingListKey[]  = $rowValue["id"];
            }

            foreach (($newBillingListInfo) as $row) {
                $data[] = $row;
            }

            $this->data['list_of_invoice'] = $data;
            return $this->data;
        }
        else
        {
            $this->data['list_of_invoice'] = $data;
            return $this->data;
        }
    }

    public function sumSimilarValue($service_name, $string, $total_amount, $billing_service_amount)
    {
        $a = $service_name;
        $search = $string;
        if (preg_match("/{$search}/i", $a)) {
            (float)$total_amount += (float)$billing_service_amount;
        }

        return $total_amount;
    }

    public function search_list_of_credit_note($selected_firm_id, $start = null, $end = null)
    {
        $this->db->select('billing_credit_note_record.credit_note_id, billing_credit_note_record.billing_id, billing_credit_note_record.received, credit_note.id, credit_note.credit_note_no, credit_note_date, credit_note.total_amount_discounted, billing.*, currency.currency as currency_name');
        $this->db->from('billing_credit_note_record');
        $this->db->join('billing', 'billing_credit_note_record.billing_id = billing.id and billing.outstanding != billing.amount and billing.status = 0', 'left');
        $this->db->join('user_firm', 'user_firm.firm_id = billing_credit_note_record.firm_id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left');
        $this->db->join('currency', 'billing.currency_id = currency.id', 'left');
        $this->db->join('credit_note', 'credit_note.id = billing_credit_note_record.credit_note_id', 'left');
        $this->db->join('client', 'client.company_code = billing.company_code', 'left');
        if ($start != NULL)
        {
            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
            if ($end != NULL)
            {

                $this->db->where('STR_TO_DATE(invoice_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
            }
            else
            {
                $this->db->where('invoice_date = "'. $start.'"');
            }
        }
        $this->db->order_by('billing_credit_note_record.id', 'asc');
        if($selected_firm_id != "all")
        {
            $this->db->where('billing_credit_note_record.firm_id', $selected_firm_id);
        }
        //$this->db->where('billing_credit_note_record.firm_id', $this->session->userdata("firm_id"));
        // $this->db->where('billing.outstanding != billing.amount');
        // $this->db->where('billing.status = 0');
        $this->db->where('user_firm.firm_id = billing_credit_note_record.firm_id');
        $this->db->group_by('billing_credit_note_record.id');
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            $this->data['list_of_credit_note'] = $data;
            return $this->data;
        }
    }

    public function search_invoice_period()
    {
        $this->db->select('billing.*, billing_service.*, client.company_name, client.registration_no, client.former_name, currency.currency as currency_name, billing_info_service_category.category_description, our_service_info.service_type, our_service_info.service_name');
        $this->db->from('billing');
        $this->db->join('user_firm', 'user_firm.firm_id = billing.firm_id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left');
        $this->db->join('billing_service', 'billing.id = billing_service.billing_id', 'left');
        $this->db->join('client_billing_info', 'client_billing_info.id = billing_service.service', 'left');
        $this->db->join('our_service_info', 'our_service_info.id = client_billing_info.service', 'left');
        $this->db->join('billing_info_service_category', 'billing_info_service_category.id = our_service_info.service_type', 'left');
        $this->db->join('client', 'client.company_code = billing.company_code', 'left');
        $this->db->join('currency', 'billing.currency_id = currency.id', 'left');
        // if ($start != NULL)
        // {
        //     if ($end != NULL)
        //     {

        //         $this->db->where('STR_TO_DATE(invoice_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
        //     }
        //     else
        //     {
        //         $this->db->where('STR_TO_DATE(invoice_date,"%d/%m/%Y") >= STR_TO_DATE("'. $start. '","%d/%m/%Y")');
        //     }
        // }
        $this->db->order_by('billing_info_service_category.category_description', 'asc');
        $this->db->order_by('billing.id', 'desc');
        //$this->db->where('billing.firm_id', $selected_firm_id);
        $this->db->where('billing.status', "0");
        //$this->db->where('outstanding !=', 0);
        $this->db->where('user_firm.firm_id = billing.firm_id');
        $this->db->group_by('billing.id');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $row->registration_no = $this->encryption->decrypt($row->registration_no);
                $row->company_name = $this->encryption->decrypt($row->company_name);
                $data[] = $row;
            }

            $this->data['invoice_period'] = $data;
        }
        else
        {
            $this->data['invoice_period'] = false;
        }
        return $this->data;
    }

    public function search_payment($selected_firm_id, $selected_type_of_payment, $selected_payment_status, $selected_payment_username)
    {
        if($selected_type_of_payment == "supplier" || $selected_type_of_payment == "client")
        {
            $this->db->select('payment_voucher.*, currency.currency as currency_name, firm.name as firm_name, firm.branch_name');
            $this->db->from('payment_voucher');
            $this->db->join('user_firm', 'user_firm.firm_id = payment_voucher.firm_id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left');
            $this->db->join('vendor_info', 'vendor_info.supplier_code = payment_voucher.supplier_code', 'left');
            $this->db->join('currency', 'currency.id = payment_voucher.currency_id', 'left');
            $this->db->join('firm', 'firm.id = payment_voucher.firm_id', 'left');
            $this->db->order_by('status', 'asc');
            if($selected_firm_id != "all")
            {
                $this->db->where('payment_voucher.firm_id', $selected_firm_id);
            }

            if($selected_payment_status == "all")
            {
                $this->db->where('payment_voucher.status !=', 1);
            }
            elseif($selected_payment_status != "all")
            {
                $this->db->where('payment_voucher.status', $selected_payment_status);
            }

            if($selected_payment_username != "all")
            {
                $this->db->where('payment_voucher.supplier_code', $selected_payment_username);
            }

            if($selected_type_of_payment == "supplier")
            {
                $this->db->where('payment_voucher.client_type = 1');
            }
            elseif($selected_type_of_payment == "client")
            {
                $this->db->where('payment_voucher.client_type = 2');
            }
            
            $this->db->where('user_firm.firm_id = payment_voucher.firm_id');
            $this->db->group_by('payment_voucher.id');
            $q = $this->db->get();
        }
        elseif($selected_type_of_payment == "claim")
        {
            $this->db->select('claim.*, currency.currency as currency_name, firm.name as firm_name, firm.branch_name');
            $this->db->from('claim');
            $this->db->join('user_firm', 'user_firm.firm_id = claim.firm_id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left');
            $this->db->join('users', 'users.id = claim.user_id', 'left');
            $this->db->join('currency', 'currency.id = claim.currency_id', 'left');
            $this->db->join('firm', 'firm.id = claim.firm_id', 'left');
            if($selected_firm_id != "all")
            {
                $this->db->where('claim.firm_id', $selected_firm_id);
            }

            if($selected_payment_status == "all")
            {
                $this->db->where('claim.status !=', 1);
            }
            elseif($selected_payment_status != "all")
            {
                $this->db->where('claim.status', $selected_payment_status);
            }

            if($selected_payment_username != "all")
            {
                $this->db->where('claim.user_id', $selected_payment_username);
            }
            $this->db->where('user_firm.firm_id = claim.firm_id');
            $this->db->order_by('status', 'asc');
            $this->db->group_by('claim.id');
            $q = $this->db->get();
        }

        if ($q->num_rows() > 0) 
        {
            $payment_info = $q->result_array();

            foreach (($payment_info) as $row) {
                $data[] = $row;
            }

            $this->data['payment_list'] = $data;
            return $this->data;
        }
    }

    public function search_bank_transaction($selected_firm_id, $selected_bank_account_id, $start_date, $end_date)
    {
        if($selected_bank_account_id != 0)
        {
            //payment_voucher
            $search_date_where = "";
            $search_bank_acc_id = "";

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

            // if($selected_firm_id != "all")
            // {
                $search_bank_acc_id = "payment_voucher.bank_acc_id='".$selected_bank_account_id."'";
            //}

            $q = $this->db->query("select payment_voucher.*, currency.currency as currency_name, payment_voucher_service.id as payment_voucher_service_id, payment_voucher_service.payment_voucher_id, payment_voucher_service.type_id, payment_voucher_service.payment_voucher_date, payment_voucher_service.payment_voucher_description, payment_voucher_service.amount as payment_voucher_service_amount, payment_voucher_service.attachment, firm.name as firm_name, payment_voucher_type.type_name, bank_info.banker from payment_voucher left join payment_voucher_service on payment_voucher_service.payment_voucher_id = payment_voucher.id left join currency on payment_voucher.currency_id = currency.id left join firm on firm.id = payment_voucher.firm_id left join payment_voucher_type on payment_voucher_type.id = payment_voucher_service.type_id left join bank_info on bank_info.id = payment_voucher.bank_acc_id where ".$search_date_where." ".$search_bank_acc_id." AND payment_voucher.status != 1 ORDER BY payment_voucher_service.id");

            if ($q->num_rows() > 0) 
            {
                $payment_voucher_info = $q->result_array();

                foreach (($payment_voucher_info) as $row) {
                    $data[] = $row;
                }

                $this->data['payment_voucher_list'] = $data;
            }

            //claim
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

            // if($selected_firm_id != "all")
            // {
                $search_bank_acc_id = "claim.bank_acc_id='".$selected_bank_account_id."'";
            //}

            $claim_q = $this->db->query("select claim.*, currency.currency as currency_name, claim_service.id as claim_service_id, claim_service.claim_id, claim_service.company_code, claim_service.type_id, claim_service.claim_date, claim_service.client_name, claim_service.claim_description, claim_service.amount as claim_service_amount, claim_service.attachment, firm.name as firm_name, payment_voucher_type.type_name, bank_info.banker from claim left join claim_service on claim_service.claim_id = claim.id left join currency on claim.currency_id = currency.id left join firm on firm.id = claim.firm_id left join payment_voucher_type on payment_voucher_type.id = claim_service.type_id left join bank_info on bank_info.id = claim.bank_acc_id where ".$search_date_where." ".$search_bank_acc_id." AND claim.status != 1 ORDER BY claim_service.id");

            if ($claim_q->num_rows() > 0) 
            {
                $claim_info = $claim_q->result_array();

                foreach (($claim_info) as $claim_row) {
                    $claim_data[] = $claim_row;
                }

                $this->data['claim_list'] = $claim_data;
            }

            //payment_receipt
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

            // if($selected_firm_id != "all")
            // {
                $search_bank_acc_id = "payment_receipt.bank_acc_id='".$selected_bank_account_id."'";
            //}
            
            $payment_receipt_q = $this->db->query("select payment_receipt.*, currency.currency as currency_name, payment_receipt_service.id as payment_receipt_service_id, payment_receipt_service.payment_receipt_id, payment_receipt_service.type_id, payment_receipt_service.receipt_date, payment_receipt_service.payment_receipt_description, payment_receipt_service.amount as payment_receipt_service_amount, payment_receipt_service.attachment, firm.name as firm_name, payment_receipt_type.type_name, bank_info.banker from payment_receipt left join payment_receipt_service on payment_receipt_service.payment_receipt_id = payment_receipt.id left join currency on payment_receipt.currency_id = currency.id left join firm on firm.id = payment_receipt.firm_id left join payment_receipt_type on payment_receipt_type.id = payment_receipt_service.type_id left join bank_info on bank_info.id = payment_receipt.bank_acc_id where ".$search_date_where." ".$search_bank_acc_id." AND payment_receipt.status != 1 ORDER BY payment_receipt_service.id");

            if ($payment_receipt_q->num_rows() > 0) 
            {
                $payment_receipt_info = $payment_receipt_q->result_array();

                foreach (($payment_receipt_info) as $payment_receipt_row) {
                    $payment_receipt_data[] = $payment_receipt_row;
                }

                $this->data['payment_receipt_list'] = $payment_receipt_data;
            }

            //receipt
            $this->db->select('billing_receipt_record.receipt_id, billing_receipt_record.billing_id, billing_receipt_record.received, receipt.id, receipt.receipt_no, receipt_date, receipt.reference_no, receipt.payment_mode, receipt.total_amount_received, billing.*, payment_mode.payment_mode, bank_info.banker, currency.currency as currency_name');
            $this->db->from('billing');
            $this->db->join('billing_receipt_record', 'billing_receipt_record.billing_id = billing.id', 'right');
            $this->db->join('receipt', 'receipt.id = billing_receipt_record.receipt_id', 'left');
            $this->db->join('client', 'client.company_code = billing.company_code', 'left');
            $this->db->join('payment_mode', 'payment_mode on payment_mode.id = receipt.payment_mode', 'left');
            $this->db->join('bank_info', 'bank_info.id = receipt.bank_account_id', 'left');
            $this->db->join('currency', 'currency.id = billing.currency_id', 'left');
            if ($start_date != NULL)
            {
                //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
                if ($end_date != NULL)
                {

                    $this->db->where('STR_TO_DATE(receipt_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start_date. '","%d/%m/%Y") and STR_TO_DATE("'. $end_date.'","%d/%m/%Y")');
                }
                else
                {
                    $this->db->where('receipt_date = "'. $start_date.'"');
                }
            }
            $this->db->order_by('billing.id', 'asc');
            $this->db->where('billing.outstanding != billing.amount');
            $this->db->where("receipt.bank_account_id='".$selected_bank_account_id."'");
            $this->db->where('billing.status = 0');

            $billing_receipt_q = $this->db->get();

            if ($billing_receipt_q->num_rows() > 0) 
            {
                $billing_receipt_info = $billing_receipt_q->result_array();

                foreach (($billing_receipt_info) as $billing_receipt_row) {
                    $billing_receipt_data[] = $billing_receipt_row;
                }

                $this->data['receipt_list'] = $billing_receipt_data;
            }
        }

        return $this->data;
    }

    public function search_sales_report($selected_firm_id, $start_date, $end_date)
    {
        $this->db->select('billing.*, client.company_name, client.registration_no, client.former_name, currency.currency as currency_name, billing_info_service_category.category_description, billing_info_service_category.id as billing_info_service_category_id, sum(billing_service.amount) as total_billing_service_amount, billing_service.gst_rate');
        $this->db->from('billing');
        $this->db->join('user_firm', 'user_firm.firm_id = billing.firm_id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left');
        $this->db->join('client', 'client.company_code = billing.company_code', 'left');
        $this->db->join('currency', 'billing.currency_id = currency.id', 'left');
        $this->db->join('billing_service', 'billing_service.billing_id = billing.id', 'left');
        $this->db->join('client_billing_info', 'client_billing_info.id = billing_service.service', 'left');
        $this->db->join('our_service_info', 'our_service_info.id = client_billing_info.service', 'left');
        $this->db->join('billing_info_service_category', 'billing_info_service_category.id = our_service_info.service_type', 'left');
        $this->db->group_by('invoice_no');
        if($selected_firm_id != "all")
        {
            $this->db->where('billing.firm_id', $selected_firm_id);
        }
        $this->db->where('user_firm.firm_id = billing.firm_id');
        $this->db->where('billing.status', "0");
        if ($start_date != NULL)
        {
            if ($end_date != NULL)
            {
                $this->db->group_start();
                    $this->db->where('date_format(billing.created_at, "%Y-%m-%d") BETWEEN STR_TO_DATE("'. $start_date. '","%d/%m/%Y") and STR_TO_DATE("'. $end_date.'","%d/%m/%Y")');
                    $this->db->or_where('date_format(billing.updated_at,"%Y-%m-%d") BETWEEN STR_TO_DATE("'. $start_date. '","%d/%m/%Y") and STR_TO_DATE("'. $end_date.'","%d/%m/%Y")');
                $this->db->group_end();
            }
            else
            {
                $this->db->group_start();
                    $this->db->where('date_format(billing.created_at,"%Y-%m-%d") >= STR_TO_DATE("'. $start_date. '","%d/%m/%Y")');
                    $this->db->or_where('date_format(billing.updated_at,"%Y-%m-%d") >= STR_TO_DATE("'. $start_date. '","%d/%m/%Y")');
                $this->db->group_end();
            }
        }
        $this->db->order_by('id', 'desc');
        $q = $this->db->get();

        if ($q->num_rows() > 0) 
        {
            $sales_report_info = $q->result_array();

            foreach (($sales_report_info) as $row) {
                $row["registration_no"] = $this->encryption->decrypt($row["registration_no"]);
                $row['company_name'] = $this->encryption->decrypt($row['company_name']);
                $data[] = $row;
            }

            $this->data['sales_report_list'] = $data;
            return $this->data;
        }
    }

    public function export_sales_report()
    {
        $start_date = $_POST['from'];
        $end_date = $_POST['to'];
        $selected_firm_id = $_POST['firm'];

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        $spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."/secretary/assets/uploads/file/Sales Report.xls");
        $sheet = $spreadsheet->getActiveSheet();

        $this->db->select('billing.*, STR_TO_DATE(billing.invoice_date,"%d/%m/%Y") as billing_invoice_date,  client.company_name, client.registration_no, client.former_name, currency.currency as currency_name, billing_info_service_category.category_description, billing_info_service_category.id as billing_info_service_category_id, sum(billing_service.amount) as total_billing_service_amount, billing_service.gst_rate');
        $this->db->from('billing');
        $this->db->join('user_firm', 'user_firm.firm_id = billing.firm_id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left');
        $this->db->join('client', 'client.company_code = billing.company_code', 'left');
        $this->db->join('currency', 'billing.currency_id = currency.id', 'left');
        $this->db->join('billing_service', 'billing_service.billing_id = billing.id', 'left');
        $this->db->join('client_billing_info', 'client_billing_info.id = billing_service.service', 'left');
        $this->db->join('our_service_info', 'our_service_info.id = client_billing_info.service', 'left');
        $this->db->join('billing_info_service_category', 'billing_info_service_category.id = our_service_info.service_type', 'left');
        $this->db->group_by('invoice_no');
        if($selected_firm_id != "all")
        {
            $this->db->where('billing.firm_id', $selected_firm_id);
        }
        $this->db->where('user_firm.firm_id = billing.firm_id');
        $this->db->where('billing.status', "0");
        if ($start_date != NULL)
        {
            if ($end_date != NULL)
            {
                $this->db->group_start();
                    $this->db->where('date_format(billing.created_at, "%Y-%m-%d") BETWEEN STR_TO_DATE("'. $start_date. '","%d/%m/%Y") and STR_TO_DATE("'. $end_date.'","%d/%m/%Y")');
                    $this->db->or_where('date_format(billing.updated_at,"%Y-%m-%d") BETWEEN STR_TO_DATE("'. $start_date. '","%d/%m/%Y") and STR_TO_DATE("'. $end_date.'","%d/%m/%Y")');
                $this->db->group_end();
            }
            else
            {
                $this->db->group_start();
                    $this->db->where('date_format(billing.created_at,"%Y-%m-%d") >= STR_TO_DATE("'. $start_date. '","%d/%m/%Y")');
                    $this->db->or_where('date_format(billing.updated_at,"%Y-%m-%d") >= STR_TO_DATE("'. $start_date. '","%d/%m/%Y")');
                $this->db->group_end();
            }
        }
        $this->db->order_by('billing_invoice_date', 'asc');
        $q = $this->db->get();

        if ($q->num_rows() > 0) 
        {
            $sales_report_info = $q->result_array();
            $r = 2;

            for($g = 0; $g < count($sales_report_info); $g++)
            {
                $sales_report_info[$g]["registration_no"] = $this->encryption->decrypt($sales_report_info[$g]["registration_no"]);
                $sales_report_info[$g]['company_name'] = $this->encryption->decrypt($sales_report_info[$g]['company_name']);
                //CASH
                $sheet->setCellValue('B'.$r, "GL");
                $sheet->setCellValue('E'.$r, $sales_report_info[$g]['invoice_date']);
                $sheet->setCellValue('F'.$r, $sales_report_info[$g]['invoice_no']);
                $sheet->setCellValue('I'.$r, 'CASH - '.$sales_report_info[$g]['company_name']);
                $sheet->setCellValue('M'.$r, $sales_report_info[$g]['amount']);
                $sheet->setCellValue('N'.$r, $sales_report_info[$g]['amount']);
                $sheet->setCellValue('T'.$r, $sales_report_info[$g]['currency_name']);
                $sheet->setCellValue('Y'.$r, "I");
                $r = $r + 1;

                //SALES
                $sheet->setCellValue('B'.$r, "GL");
                $sheet->setCellValue('E'.$r, $sales_report_info[$g]['invoice_date']);
                $sheet->setCellValue('F'.$r, $sales_report_info[$g]['invoice_no']);
                $sheet->setCellValue('I'.$r, 'SALES');
                $sheet->setCellValue('M'.$r, '-'.$sales_report_info[$g]['total_billing_service_amount']);
                $sheet->setCellValue('O'.$r, '-'.$sales_report_info[$g]['total_billing_service_amount']);
                $sheet->setCellValue('T'.$r, $sales_report_info[$g]['currency_name']);
                $sheet->setCellValue('Y'.$r, "I");
                $r = $r + 1;

                //GST OUTPUT TAX
                if($sales_report_info[$g]["gst_rate"] != 0)
                {
                    $gst = 0;
                    $before_gst = (($sales_report_info[$g]["gst_rate"] / 100) * $sales_report_info[$g]["total_billing_service_amount"]);
                    $gst += $before_gst;

                    $sheet->setCellValue('B'.$r, "GL");
                    $sheet->setCellValue('E'.$r, $sales_report_info[$g]['invoice_date']);
                    $sheet->setCellValue('F'.$r, $sales_report_info[$g]['invoice_no']);
                    $sheet->setCellValue('I'.$r, 'GST OUTPUT TAX');
                    $sheet->setCellValue('M'.$r, '-'.$gst);
                    $sheet->setCellValue('O'.$r, '-'.$gst);
                    $sheet->setCellValue('T'.$r, $sales_report_info[$g]['currency_name']);
                    $sheet->setCellValue('Y'.$r, "I");
                    $r = $r + 1;
                }
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
             
            $filename = $_SERVER['DOCUMENT_ROOT'].'/secretary/assets/uploads/excel/Sales Report - '.date("Ymd").'.xls';
            $writer->save($filename);

            //$this->zip->read_file($filename);

            //$this->zip->archive($_SERVER['DOCUMENT_ROOT'].'/secretary/assets/uploads/excel/Sales Report - '.date("Y/m/d").'.zip');

            //$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
            $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
            $link = $protocol . $_SERVER['SERVER_NAME'] .'/secretary/assets/uploads/excel/Sales Report - '.date("Ymd").'.xls';

            $data = array('status'=>'success', 'link'=>$link);
        }
        else
        {
            $data = array('status'=>'fail');
        }

        echo json_encode($data);
    }

    public function search_register_controller($selected_firm_id)
    {
        if($selected_firm_id != "all")
        {
            $where = 'a_client.firm_id = "'.$selected_firm_id.'" AND ';
        }
        else
        {
            $where = '';
        }

        $q = $this->db->query('select 
            a_client.registration_no as uen, 
            client_controller.id as client_controller_id,
            client_controller.date_of_registration as date_appointed, 
            client_controller.date_of_cessation as date_ceased, 
            officer.field_type as officer_field_type, 
            officer.identification_type as officer_identification_type, 
            officer.identification_no, officer.name,
            officer.alias, 
            officer.date_of_birth, 
            officer.address_type as officer_address_type, 
            officer.postal_code1 as officer_postal_code, 
            officer.street_name1 as officer_street_name,
            officer.building_name1 as officer_builing_name,
            officer.unit_no1 as officer_unit_no1,
            officer.unit_no2 as officer_unit_no2,
            officer.foreign_address1 as officer_foreign_address1,
            officer.foreign_address2 as officer_foreign_address2,
            officer.foreign_address3 as officer_foreign_address3,
            nationality.code,
            officer_company.field_type as officer_company_field_type,
            officer_company.company_name,
            officer_company.register_no,
            officer_company.entity_issued_by_registrar,
            officer_company.legal_form_entity,
            officer_company.country_of_incorporation,
            officer_company.statutes_of,
            officer_company.coporate_entity_name,
            company_nationality.code as company_nationality_code,
            b_client.registration_no,
            b_client.company_name as client_company_name, 
            company_type.company_type as client_company_type,
            b_client.client_country_of_incorporation,
            b_client.client_statutes_of,
            b_client.client_coporate_entity_name
            from client as a_client 
            left join client_controller on client_controller.company_code = a_client.company_code 
            left join officer on client_controller.officer_id = officer.id and client_controller.field_type = officer.field_type 
            left join nationality on nationality.id = officer.nationality 
            left join officer_company on client_controller.officer_id = officer_company.id and client_controller.field_type = officer_company.field_type 
            left join company_jurisdiction as company_nationality on company_nationality.jurisdiction = officer_company.country_of_incorporation 
            left join client as b_client on b_client.id = client_controller.officer_id AND client_controller.field_type = "client" 
            left join company_type on company_type.id = a_client.company_type 
            where '.$where.' a_client.acquried_by = "1" AND a_client.deleted != "1" AND a_client.status = "1" AND client_controller.deleted = 0');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $row->uen = $this->encryption->decrypt($row->uen);
                if($row->officer_field_type == "individual")
                {
                    $row->identification_no = $this->encryption->decrypt($row->identification_no);
                    $row->name = $this->encryption->decrypt($row->name);
                }
                elseif($row->officer_company_field_type == "company")
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
            //print_r($data);
            //echo json_encode($data);
            $this->data['register_controller_list'] = $data;
            return $this->data;
        }
    }

    public function search_list_of_recurring($selected_firm_id, $start = null, $end = null)
    {   
        $this->db->select('billing_info_service_category.*');
        $this->db->from('billing_info_service_category');
        $category_query = $this->db->get();
        $category_query = $category_query->result_array();
        
        $this->db->select('recurring_billing.*, client.company_name, client.registration_no, client.former_name, currency.currency as currency_name, billing_info_service_category.category_description, billing_info_service_category.id as billing_info_service_category_id, recurring_billing_service.amount as recurring_billing_service_amount, our_service_info.service_name as our_service_name, firm.name as firm_name, firm.branch_name, client_contact_info.name as contact_name, client_contact_info_email.email as contact_email');
        $this->db->from('recurring_billing');
        $this->db->join('client', 'client.company_code = recurring_billing.company_code', 'left');
        $this->db->join('user_firm', 'user_firm.firm_id = recurring_billing.firm_id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left');
        $this->db->join('currency', 'recurring_billing.currency_id = currency.id', 'left');
        $this->db->join('recurring_billing_service', 'recurring_billing_service.billing_id = recurring_billing.id', 'right');
        $this->db->join('client_billing_info', 'client_billing_info.id = recurring_billing_service.service', 'left');
        $this->db->join('our_service_info', 'our_service_info.id = client_billing_info.service', 'left');
        $this->db->join('billing_info_service_category', 'billing_info_service_category.id = our_service_info.service_type', 'left');
        $this->db->join('firm', 'firm.id = recurring_billing.firm_id', 'left');
        $this->db->join('client_contact_info', 'client_contact_info.company_code = client.company_code', 'left');
        $this->db->join('client_contact_info_email', 'client_contact_info_email.client_contact_info_id = client_contact_info.id and primary_email = 1', 'left');

        if ($start != NULL)
        {
            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
            if ($end != NULL)
            {
                $this->db->where('STR_TO_DATE(recurring_billing.recu_invoice_issue_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
            }
            else
            {
                $this->db->where('STR_TO_DATE(recurring_billing.recu_invoice_issue_date,"%d/%m/%Y") = STR_TO_DATE("'. $start. '","%d/%m/%Y")');
            }
        }
        $this->db->order_by('id', 'desc');
        if($selected_firm_id != "all")
        {
            $this->db->where('recurring_billing.firm_id', $selected_firm_id);
        }
        $this->db->where('user_firm.firm_id = recurring_billing.firm_id');
        $this->db->where('client.acquried_by', "1");
        $this->db->where('client.deleted != "1"');
        $this->db->where('client.status', "1");
        $this->db->where('recurring_billing.status', "0");
        $this->db->where('recurring_billing.amount != "0.00"');
        $this->db->where('recurring_billing.recurring_status', "1");
        $q = $this->db->get();

        $newBillingListInfo = [];
        $newBillingListKey = [];
        $newKey = 0;
        $data = [];

        if ($q->num_rows() > 0) {
            foreach (($q->result_array()) as $row => $rowValue) {
                //$data[] = $row;
                if($rowValue["company_name"] != null)
                {
                    $rowValue["company_name"] = $this->encryption->decrypt($rowValue["company_name"]);
                }

                if(!in_array($rowValue["id"],$newBillingListKey)){
                    ++$newKey;
                    $newBillingListInfo[$newKey]["id"] = $rowValue["id"];
                    $newBillingListInfo[$newKey]["recu_invoice_issue_date"] = $rowValue["recu_invoice_issue_date"];
                    $newBillingListInfo[$newKey]["invoice_no"] = $rowValue["invoice_no"];
                    $newBillingListInfo[$newKey]["company_name"] = $rowValue["company_name"];
                    $newBillingListInfo[$newKey]["currency_name"] = $rowValue["currency_name"];
                    $newBillingListInfo[$newKey]["amount"] = $rowValue["amount"];
                    $newBillingListInfo[$newKey]["outstanding"] = $rowValue["outstanding"];
                    $newBillingListInfo[$newKey]["firm_name"] = $rowValue["firm_name"];
                    $newBillingListInfo[$newKey]["branch_name"] = $rowValue["branch_name"];
                    $newBillingListInfo[$newKey]["contact_name"] = $rowValue["contact_name"];
                    $newBillingListInfo[$newKey]["contact_email"] = $rowValue["contact_email"];
                    $newBillingListInfo[$newKey]["our_service_name"] = [];

                    for($t = 0; $t < count($category_query); $t++)
                    {
                        $newBillingListInfo[$newKey]["category"][$category_query[$t]["id"]-1] = number_format(0.00, 2);
                    }
                }
                $newBillingListInfo[$newKey]["category"][$rowValue["billing_info_service_category_id"]-1] = number_format(((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"][$rowValue["billing_info_service_category_id"]-1]) + $rowValue["recurring_billing_service_amount"]), 2);
                array_push($newBillingListInfo[$newKey]["our_service_name"], strtoupper($rowValue["our_service_name"]));
                $newBillingListKey[]  = $rowValue["id"];
            }

            foreach (($newBillingListInfo) as $row) {
                $data[] = $row;
            }

            $this->data['list_of_recurring'] = $data;
            return $this->data;
        }
    }

    public function search_list_of_receipt($selected_firm_id, $start = null, $end = null)
    {   
        // $this->db->select('billing_info_service_category.*');
        // $this->db->from('billing_info_service_category');
        // $category_query = $this->db->get();
        // $category_query = $category_query->result_array();

        $this->db->select('billing_receipt_record.receipt_id, billing_receipt_record.billing_id, billing_receipt_record.received, billing_receipt_record.equival_amount, receipt.id, receipt.receipt_no, receipt_date, receipt.reference_no, receipt.payment_mode, receipt.total_amount_received, receipt.out_of_balance, billing.*, payment_mode.payment_mode, firm.name as firm_name, firm.branch_name, bank_info.banker, currency.currency as bank_currency_name, billing_currency.currency as billing_currency_name');
        $this->db->from('billing');
        $this->db->join('billing_receipt_record', 'billing_receipt_record.billing_id = billing.id', 'left');
        $this->db->join('receipt', 'receipt.id = billing_receipt_record.receipt_id', 'right');
        $this->db->join('client', 'client.company_code = billing.company_code AND client.deleted = 0', 'left');
        $this->db->join('payment_mode', 'payment_mode on payment_mode.id = receipt.payment_mode', 'left');
        $this->db->join('firm', 'firm on firm.id = billing_receipt_record.firm_id', 'left');
        $this->db->join('bank_info', 'bank_info on bank_info.id = receipt.bank_account_id', 'left');
        $this->db->join('currency', 'currency on currency.id = bank_info.currency', 'left');
        $this->db->join('currency as billing_currency', 'billing_currency on billing_currency.id = billing.currency_id', 'left');


        if ($start != NULL)
        {
            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
            if ($end != NULL)
            {
                $this->db->where('STR_TO_DATE(receipt_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
            }
            else
            {
                $this->db->where('receipt_date = "'. $start.'"');
            }
        }

        $this->db->order_by('billing.id', 'asc');
        // if($this->session->userdata("firm_id") == 18 || $this->session->userdata("firm_id") == 26)
        // {
        //     $this->db->where('(billing.firm_id = 18 or billing.firm_id = 26)');
        // }
        // else
        // {
        //     $this->db->where('billing.firm_id', $this->session->userdata("firm_id"));
        // }
        if($selected_firm_id != "all")
        {
            $this->db->where('billing.firm_id', $selected_firm_id);
        }
        
        $this->db->where('billing.outstanding != billing.amount');
        $this->db->where('billing.status = 0');
        //$this->db->where("STR_TO_DATE(receipt_date,'%d/%m/%Y') >= STR_TO_DATE('".$date."','%d/%m/%Y')");
        $q = $this->db->get();

        $data = [];

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
        }
        $this->data['list_of_receipt'] = $data;
        return $this->data;
    }

    public function search_list_of_document($selected_firm_id, $start = null, $end = null)
    {  
        $this->data['list_of_document'] = $this->document_model->get_all_document($_SESSION['group_id'], null , null, $start, $end);
        //$this->data['all_documents'] = $this->document_model->get_all_document($_SESSION['group_id']);
        return $this->data;
    }

    public function search_list_of_gst_report ($selected_gst_report_firm_id, $start = null, $end = null)
    {
        $newBillingListInfo = [];
        $newBillingListKey = [];
        $newKey = 0;
        $data = [];

        $newCNListInfo = [];
        $newCNListKey = [];
        $newCNKey = 0;
        $dataCN = [];

        $this->db->select('gst_category.id as gst_category_id, gst_category.category, currency.currency as currency_name');//gst_category.category
        $this->db->from('firm');
        $this->db->join('gst_category_info', 'gst_category_info.jurisdiction_id = firm.jurisdiction_id', 'right');
        $this->db->join('gst_category', 'gst_category.id = gst_category_info.gst_category_id', 'right');
        $this->db->join('currency', 'currency.id = firm.firm_currency', 'right');
        $this->db->group_by('gst_category.category');
        if($selected_gst_report_firm_id != "aaa_all")
        {
            $this->db->where('firm.id', $selected_gst_report_firm_id);
        }
        else
        {
            $this->db->where('firm.id = 18');
        }
        $this->db->where('gst_category_info.end_date', NULL);
        $this->db->order_by('gst_category.id', 'asc');
        $q_gst_category = $this->db->get();

        if ($q_gst_category->num_rows() > 0) {
            $arr_gst_category = $q_gst_category->result_array();
            foreach (($q_gst_category->result()) as $row) {
                $list_of_category_data[] = $row;
            }
        }
        $this->data['list_of_category'] = $list_of_category_data;
        
        $this->db->select('billing.*, client.company_name as client_company_name, transaction_client.company_name as trans_company_name, client.registration_no, client.former_name, currency.currency as currency_name, billing_info_service_category.category_description, billing_info_service_category.id as billing_info_service_category_id, billing_service.amount as billing_service_amount, billing_service.period_end_date, billing_service.invoice_description, billing_service.gst_rate, billing_service.gst_new_way, billing_service.gst_category_id, firm.name as firm_name, firm.branch_name, our_service_info.service_name, b.service_name as trans_service_name, c.category_description as trans_category_description, c.id as trans_billing_info_service_category_id, d.service_name as our_service_service_name, e.category_description as our_service_category_description, e.id as our_service_billing_info_service_category_id, transaction_master_with_billing.id as transaction_master_with_billing_id, gst_category.category as gst_category_name');
        $this->db->from('billing');
        $this->db->join('client', 'client.company_code = billing.company_code', 'left');
        $this->db->join('user_firm', 'user_firm.firm_id = billing.firm_id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left');
        $this->db->join('transaction_client', 'transaction_client.company_code = billing.company_code', 'left');
        $this->db->join('currency', 'billing.currency_id = currency.id', 'left');
        $this->db->join('billing_service', 'billing_service.billing_id = billing.id', 'left');
        $this->db->join('transaction_master_with_billing', 'transaction_master_with_billing.billing_id = billing.id', 'left');
        $this->db->join('gst_category', 'gst_category.id = billing_service.gst_category_id', 'left');

        $this->db->join('transaction_client_billing_info as a', 'a.id = billing_service.service', 'left');
        $this->db->join('our_service_info as b', 'b.id = a.service', 'left');
        $this->db->join('billing_info_service_category as c ', 'c.id = b.service_type', 'left');

        $this->db->join('our_service_info as d', 'd.id = billing_service.service', 'left');
        $this->db->join('billing_info_service_category as e ', 'e.id = d.service_type', 'left');

        $this->db->join('client_billing_info', 'client_billing_info.id = billing_service.service', 'left');
        $this->db->join('our_service_info', 'our_service_info.id = client_billing_info.service', 'left');
        $this->db->join('billing_info_service_category', 'billing_info_service_category.id = our_service_info.service_type', 'left');

        $this->db->join('firm', 'firm.id = billing.firm_id', 'left');

        if ($start != "")
        {
            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
            if ($end != "")
            {
                $this->db->where('STR_TO_DATE(billing.invoice_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
            }
            else
            {
                $this->db->where('STR_TO_DATE(billing.invoice_date,"%d/%m/%Y") >= STR_TO_DATE("'. $start. '","%d/%m/%Y")');
            }
        }
        
        if($selected_gst_report_firm_id != "aaa_all")
        {
            $this->db->where('billing.firm_id', $selected_gst_report_firm_id);
        }
        else
        {
            $this->db->where('(billing.firm_id = 18 OR billing.firm_id = 26)');
        }
        $this->db->where('user_firm.firm_id = billing.firm_id');
        $this->db->where('billing.status', "0");
        $this->db->order_by('id', 'desc');
        $q_billing_list = $this->db->get();

        if ($q_billing_list->num_rows() > 0) 
        {
            foreach (($q_billing_list->result_array()) as $row => $rowValue) 
            {
                // if($rowValue["company_name"] != null)
                // {
                //     $rowValue["company_name"] = $this->encryption->decrypt($rowValue["company_name"]);
                // }
                // else
                // {
                //     $rowValue["trans_company_name"] = $this->encryption->decrypt($rowValue["trans_company_name"]);
                // }
                if(!in_array($rowValue["id"],$newBillingListKey)){
                    ++$newKey;
                    $newBillingListInfo[$newKey]["id"] = $rowValue["id"];
                    $newBillingListInfo[$newKey]["invoice_date"] = $rowValue["invoice_date"];
                    $newBillingListInfo[$newKey]["invoice_no"] = $rowValue["invoice_no"];
                    //$newBillingListInfo[$newKey]["company_name"] = (($rowValue["client_company_name"] != null)?$rowValue["client_company_name"]:$rowValue["trans_company_name"]);
                    $newBillingListInfo[$newKey]["company_name"] = $rowValue["company_name"];
                    $newBillingListInfo[$newKey]["currency_name"] = $rowValue["currency_name"];
                    $newBillingListInfo[$newKey]["gst_category_name"] = [];
                    $newBillingListInfo[$newKey]["total_gst"] = 0.00;
                    $newBillingListInfo[$newKey]["total"] = $rowValue["amount"];

                    for($t = 0; $t < count($arr_gst_category) + 1; $t++)
                    {
                        if($t == 0)
                        {
                            $newBillingListInfo[$newKey]["gst_category"][$t] = number_format(0.00, 2);
                        }
                        else
                        {
                            $newBillingListInfo[$newKey]["gst_category"][$arr_gst_category[$t - 1]["gst_category_id"]] = number_format(0.00, 2);
                        }
                    }
                }

                $newBillingListInfo[$newKey]["gst_category"][$rowValue["gst_category_id"]] = number_format(((float)str_replace( ',', '',$newBillingListInfo[$newKey]["gst_category"][$rowValue["gst_category_id"]]) + $rowValue["billing_service_amount"]), 2);

                array_push($newBillingListInfo[$newKey]["gst_category_name"], strtoupper($rowValue["gst_category_name"]));

                $newBillingListInfo[$newKey]["total_gst"] = number_format((float)str_replace( ',', '', $newBillingListInfo[$newKey]["total_gst"]) + (float)$rowValue["billing_service_amount"] * ($rowValue["gst_rate"]/100), 2);

                $newBillingListKey[]  = $rowValue["id"];
            }

            $this->data['newBillingListKey'] = $newBillingListKey;

            foreach (($newBillingListInfo) as $row) {
                $data[] = $row;
            }

            $this->data['gst_report'] = $data;
            //return $this->data;
        }
        else
        {
            $this->data['gst_report'] = $data;
            //return $this->data;
        }


        $this->db->select('billing_credit_note_gst_record.credit_note_id, billing_credit_note_gst.billing_id, billing_credit_note_gst_record.cn_amount, billing_credit_note_gst.id, billing_credit_note_gst.credit_note_no, billing_credit_note_gst.credit_note_date, billing_credit_note_gst.total_amount_discounted, billing.*, currency.currency as currency_name, billing_service.gst_category_id, gst_category.category as gst_category_name, billing_credit_note_gst_record.gst_rate');
        $this->db->from('billing_credit_note_gst_record');
        $this->db->join('billing_credit_note_gst', 'billing_credit_note_gst.id = billing_credit_note_gst_record.credit_note_id', 'left');
        $this->db->join('billing', 'billing_credit_note_gst.billing_id = billing.id and billing.outstanding != billing.amount and billing.status = 0', 'left');
        $this->db->join('user_firm', 'user_firm.firm_id = billing_credit_note_gst.firm_id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left');
        $this->db->join('currency', 'billing.currency_id = currency.id', 'left');
        $this->db->join('billing_service', 'billing_service.id = billing_credit_note_gst_record.billing_service_id', 'left');
        $this->db->join('gst_category', 'gst_category.id = billing_service.gst_category_id', 'left');
        $this->db->join('client', 'client.company_code = billing.company_code', 'left');
        if ($start != NULL)
        {
            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
            if ($end != NULL)
            {

                $this->db->where('STR_TO_DATE(credit_note_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
            }
            else
            {
                $this->db->where('STR_TO_DATE(credit_note_date,"%d/%m/%Y") >= STR_TO_DATE("'. $start. '","%d/%m/%Y")');
            }
        }
        $this->db->order_by('billing_credit_note_gst_record.id', 'asc');

        if($selected_gst_report_firm_id != "aaa_all")
        {
            $this->db->where('billing_credit_note_gst.firm_id', $selected_gst_report_firm_id);
        }
        else
        {
            $this->db->where('(billing_credit_note_gst.firm_id = 18 OR billing_credit_note_gst.firm_id = 26)');
        }
        //$this->db->where('billing_credit_note_gst_record.firm_id', $this->session->userdata("firm_id"));
        // $this->db->where('billing.outstanding != billing.amount');
        // $this->db->where('billing.status = 0');
        $this->db->where('user_firm.firm_id = billing_credit_note_gst.firm_id');
        $this->db->where('billing_credit_note_gst.delete_flag = false');
        $this->db->group_by('billing_credit_note_gst_record.id');
        $q_cn_list = $this->db->get();

        if ($q_cn_list->num_rows() > 0) {
            foreach (($q_cn_list->result_array()) as $row => $rowValue) 
            {
                if(!in_array($rowValue["id"],$newCNListKey)){
                    ++$newCNKey;
                    $newCNListInfo[$newCNKey]["id"] = $rowValue["id"];
                    $newCNListInfo[$newCNKey]["credit_note_date"] = $rowValue["credit_note_date"];
                    $newCNListInfo[$newCNKey]["credit_note_no"] = $rowValue["credit_note_no"];
                    //$newCNListInfo[$newKey]["company_name"] = (($rowValue["client_company_name"] != null)?$rowValue["client_company_name"]:$rowValue["trans_company_name"]);
                    $newCNListInfo[$newCNKey]["company_name"] = $rowValue["company_name"];
                    $newCNListInfo[$newCNKey]["currency_name"] = $rowValue["currency_name"];
                    $newCNListInfo[$newCNKey]["gst_category_name"] = [];
                    $newCNListInfo[$newCNKey]["total_gst"] = 0.00;
                    $newCNListInfo[$newCNKey]["total"] = $rowValue["total_amount_discounted"];

                    for($t = 0; $t < count($arr_gst_category) + 1; $t++)
                    {
                        if($t == 0)
                        {
                            $newCNListInfo[$newCNKey]["gst_category"][$t] = number_format(0.00, 2);
                        }
                        else
                        {
                            $newCNListInfo[$newCNKey]["gst_category"][$arr_gst_category[$t - 1]["gst_category_id"]] = number_format(0.00, 2);
                        }
                    }
                }

                $newCNListInfo[$newCNKey]["gst_category"][$rowValue["gst_category_id"]] = number_format(((float)str_replace( ',', '',$newCNListInfo[$newCNKey]["gst_category"][$rowValue["gst_category_id"]]) + $rowValue["cn_amount"]), 2);

                array_push($newCNListInfo[$newCNKey]["gst_category_name"], strtoupper($rowValue["gst_category_name"]));

                $newCNListInfo[$newCNKey]["total_gst"] = number_format((float)str_replace( ',', '', $newCNListInfo[$newCNKey]["total_gst"]) + (float)$rowValue["cn_amount"] * ($rowValue["gst_rate"]/100), 2);

                $newCNListKey[]  = $rowValue["id"];
            }

            foreach (($newCNListInfo) as $row) {
                $dataCN[] = $row;
            }

            $this->data['cn_gst_report'] = $dataCN;

            //return $this->data;
            // foreach (($q_cn_list->result()) as $row) {
            //     $data[] = $row;
            // }

            // $this->data['list_of_credit_note'] = $data;
            // return $this->data;
        }
        else
        {
            $this->data['cn_gst_report'] = $dataCN;
            //return $this->data;
        }
        return $this->data;
    }

    public function search_list_of_csp_report($selected_csp_report_type, $selected_firm_id, $start = null, $end = null)
    {
        if($selected_csp_report_type == "csp_auto_billing_report")
        {
            $default_corporate_sec_row = 5;
            $default_registered_offis_row = 9;
            $default_nomi_director_row = 13;

            $cancel_default_corporate_sec_row = 5;
            $cancel_default_registered_offis_row = 9;
            $cancel_default_nomi_director_row = 13;

            $start_array = explode('/',$start);
            $start_year = $start_array[2];
            $start_month = $start_array[1];
            $start_day = $start_array[0];
            $formatted_start_date = $start_array[1].'/'.$start_array[0].'/'.$start_array[2];

            $end_array = explode('/',$end);
            $end_year = $end_array[2];
            $end_month = $end_array[1];
            $end_day = $end_array[0];
            $formatted_end_date = $end_array[1].'/'.$end_array[0].'/'.$end_array[2];

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."/secretary/assets/uploads/file/CSP Auto Billing Report.xlsx");
            //$sheet = $spreadsheet->getActiveSheet();
            for($tab_num = 0; $tab_num < 2; $tab_num++)
            {
                if($tab_num == 0)
                {
                    $sheet = $spreadsheet->getSheet(0);

                    if ($start != "")
                    {
                        if ($end != "")
                        {
                            $sheet->setCellValue('A1', "Auto Billing from ".date_format(date_create($formatted_start_date), "d F Y")." to ".date_format(date_create($formatted_end_date), "d F Y"));

                            $recurring_where = ' AND date_format(recurring_billing.created_at, "%Y-%m-%d") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")';
                        }
                        else
                        {
                            $sheet->setCellValue('A1', "Auto Billing from ".date_format(date_create($formatted_start_date), "d F Y"));

                            $recurring_where = ' AND date_format(recurring_billing.created_at,"%Y-%m-%d") >= STR_TO_DATE("'. $start. '","%d/%m/%Y")';
                        }
                    }

                    if($selected_firm_id != "all")
                    {
                        $recurring_firm_where = ' AND recurring_billing.firm_id = "'.$selected_firm_id.'"';
                    }
                    else
                    {
                        $recurring_firm_where = '';
                    }

                    $p = $this->db->query("select recurring_billing.id, recurring_billing.firm_id, recurring_billing.company_code, recurring_billing.own_letterhead_checkbox, recurring_billing.created_at, recurring_billing.recurring_cancel_date, recurring_billing.recu_invoice_issue_date, recurring_billing_service.id as recurring_billing_service_id, recurring_billing_service.billing_id as billing_service_billing_id, recurring_billing_service.invoice_description, recurring_billing_service.amount, recurring_billing_service.service, recurring_billing_service.unit_pricing, recurring_billing_service.gst_rate, recurring_billing_service.period_start_date, recurring_billing_service.period_end_date, client_billing_info.service as client_billing_info_service, recurring_billing_service.gst_new_way, client.company_name, our_service_info.service_name, our_service_info.service_type
                        FROM recurring_billing 
                        LEFT JOIN client ON client.company_code = recurring_billing.company_code
                        LEFT JOIN recurring_billing_service ON recurring_billing_service.billing_id = recurring_billing.id 
                        LEFT JOIN client_billing_info ON client_billing_info.id = recurring_billing_service.service 
                        LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service 
                        where recurring_billing.recurring_status = 1".$recurring_where.$recurring_firm_where."  ORDER BY recurring_billing_service.id");

                    if ($p->num_rows() > 0) 
                    {
                        //$p = $p->result_array();
                        foreach (($p->result()) as $row) 
                        {
                            if($row->recu_invoice_issue_date != "")
                            {
                                $recu_invoice_issue_date_array = explode('/',$row->recu_invoice_issue_date);
                                $recu_invoice_issue_date_year = $recu_invoice_issue_date_array[2];
                                $recu_invoice_issue_date_month = $recu_invoice_issue_date_array[1];
                                $recu_invoice_issue_date_day = $recu_invoice_issue_date_array[0];
                                $formatted_recu_invoice_issue_date_date = $recu_invoice_issue_date_array[1].'/'.$recu_invoice_issue_date_array[0].'/'.$recu_invoice_issue_date_array[2];
                                $recu_invoice_issue_date_array_excel = date_format(date_create($formatted_recu_invoice_issue_date_date), "d M Y");
                            }
                            else
                            {
                                $recu_invoice_issue_date_array_excel = "";
                            }

                            if(strpos($row->service_name, "CORPORATE SECRETARY SERVICE") !== false)
                            {
                                $sheet->setCellValue('A'.$default_corporate_sec_row, date_format(date_create($row->created_at), "d M Y"));
                                $sheet->setCellValue('B'.$default_corporate_sec_row, $this->encryption->decrypt($row->company_name));
                                $sheet->setCellValue('C'.$default_corporate_sec_row, $recu_invoice_issue_date_array_excel);
                                $sheet->setCellValue('D'.$default_corporate_sec_row, $row->amount);
                                //it pushes row 5 down to row 6
                                $sheet->insertNewRowBefore($default_corporate_sec_row + 1, 1);
                                $default_corporate_sec_row += 1;
                                $default_registered_offis_row += 1;
                                $default_nomi_director_row += 1;
                            }
                            else if($row->service_type == "7")
                            {
                                $sheet->setCellValue('A'.$default_registered_offis_row, date_format(date_create($row->created_at), "d M Y"));
                                $sheet->setCellValue('B'.$default_registered_offis_row, $this->encryption->decrypt($row->company_name));
                                $sheet->setCellValue('C'.$default_registered_offis_row, $recu_invoice_issue_date_array_excel);
                                $sheet->setCellValue('D'.$default_registered_offis_row, $row->amount);
                                //it pushes row 5 down to row 6
                                $sheet->insertNewRowBefore($default_registered_offis_row + 1, 1);
                                //$default_corporate_sec_row += 1;
                                $default_registered_offis_row += 1;
                                $default_nomi_director_row += 1;
                            }
                            else if(strpos($row->service_name, "NOMINEE DIRECTOR") !== false)
                            {
                                $sheet->setCellValue('A'.$default_nomi_director_row, date_format(date_create($row->created_at), "d M Y"));
                                $sheet->setCellValue('B'.$default_nomi_director_row, $this->encryption->decrypt($row->company_name));
                                $sheet->setCellValue('C'.$default_nomi_director_row, $recu_invoice_issue_date_array_excel);
                                $sheet->setCellValue('D'.$default_nomi_director_row, $row->amount);
                                //it pushes row 5 down to row 6
                                $sheet->insertNewRowBefore($default_nomi_director_row + 1, 1);
                                //$default_corporate_sec_row += 1;
                                //$default_registered_offis_row += 1;
                                $default_nomi_director_row += 1;
                            }
                        }
                    }
                    // else
                    // {
                    //     $this->data['status'] = "unsucessful";
                    // }
                }
                else if($tab_num == 1)
                {
                    $sheet = $spreadsheet->getSheet(1);

                    if ($start != "")
                    {
                        if ($end != "")
                        {
                            $sheet->setCellValue('A1', "Auto Billing from ".date_format(date_create($formatted_start_date), "d F Y")." to ".date_format(date_create($formatted_end_date), "d F Y"));

                            $recurring_where = ' AND STR_TO_DATE(recurring_billing.recurring_cancel_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")';
                        }
                        else
                        {
                            $sheet->setCellValue('A1', "Auto Billing from ".date_format(date_create($formatted_start_date), "d F Y"));

                            $recurring_where = ' AND STR_TO_DATE(recurring_billing.recurring_cancel_date,"%d/%m/%Y") >= STR_TO_DATE("'. $start. '","%d/%m/%Y")';
                        }
                    }

                    if($selected_firm_id != "all")
                    {
                        $recurring_firm_where = ' AND recurring_billing.firm_id = "'.$selected_firm_id.'"';
                    }
                    else
                    {
                        $recurring_firm_where = '';
                    }

                    $p = $this->db->query("select recurring_billing.id, recurring_billing.firm_id, recurring_billing.company_code, recurring_billing.own_letterhead_checkbox, recurring_billing.created_at, recurring_billing.recurring_cancel_date, recurring_billing.recu_invoice_issue_date, recurring_billing_service.id as recurring_billing_service_id, recurring_billing_service.billing_id as billing_service_billing_id, recurring_billing_service.invoice_description, recurring_billing_service.amount, recurring_billing_service.service, recurring_billing_service.unit_pricing, recurring_billing_service.gst_rate, recurring_billing_service.period_start_date, recurring_billing_service.period_end_date, client_billing_info.service as client_billing_info_service, recurring_billing_service.gst_new_way, client.company_name, our_service_info.service_name, our_service_info.service_type
                        FROM recurring_billing 
                        LEFT JOIN client ON client.company_code = recurring_billing.company_code
                        LEFT JOIN recurring_billing_service ON recurring_billing_service.billing_id = recurring_billing.id 
                        LEFT JOIN client_billing_info ON client_billing_info.id = recurring_billing_service.service 
                        LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service 
                        where recurring_billing.recurring_status = 0".$recurring_where.$recurring_firm_where." ORDER BY recurring_billing_service.id");

                    if ($p->num_rows() > 0) 
                    {
                        //$p = $p->result_array();
                        foreach (($p->result()) as $row) 
                        {
                            if($row->recurring_cancel_date != "")
                            {
                                $recurring_cancel_date_array = explode('/',$row->recurring_cancel_date);
                                $recu_invoice_issue_date_year = $recurring_cancel_date_array[2];
                                $recu_invoice_issue_date_month = $recurring_cancel_date_array[1];
                                $recu_invoice_issue_date_day = $recurring_cancel_date_array[0];
                                $formatted_recu_invoice_issue_date_date = $recurring_cancel_date_array[1].'/'.$recurring_cancel_date_array[0].'/'.$recurring_cancel_date_array[2];
                                $recurring_cancel_date_array_excel = date_format(date_create($formatted_recu_invoice_issue_date_date), "d M Y");
                            }
                            else
                            {
                                $recurring_cancel_date_array_excel = "";
                            }

                            if($row->recu_invoice_issue_date != "")
                            {
                                $recu_invoice_issue_date_array = explode('/',$row->recu_invoice_issue_date);
                                $recu_invoice_issue_date_year = $recu_invoice_issue_date_array[2];
                                $recu_invoice_issue_date_month = $recu_invoice_issue_date_array[1];
                                $recu_invoice_issue_date_day = $recu_invoice_issue_date_array[0];
                                $formatted_recu_invoice_issue_date_date = $recu_invoice_issue_date_array[1].'/'.$recu_invoice_issue_date_array[0].'/'.$recu_invoice_issue_date_array[2];
                                $recu_invoice_issue_date_array_excel = date_format(date_create($formatted_recu_invoice_issue_date_date), "d M Y");
                            }
                            else
                            {
                                $recu_invoice_issue_date_array_excel = "";
                            }

                            if(strpos($row->service_name, "CORPORATE SECRETARY SERVICE") !== false)
                            {
                                $sheet->setCellValue('A'.$cancel_default_corporate_sec_row, $recurring_cancel_date_array_excel);
                                $sheet->setCellValue('B'.$cancel_default_corporate_sec_row, $this->encryption->decrypt($row->company_name));
                                $sheet->setCellValue('C'.$cancel_default_corporate_sec_row, $recu_invoice_issue_date_array_excel);
                                $sheet->setCellValue('D'.$cancel_default_corporate_sec_row, $row->amount);
                                //it pushes row 5 down to row 6
                                $sheet->insertNewRowBefore($cancel_default_corporate_sec_row + 1, 1);
                                $cancel_default_corporate_sec_row += 1;
                                $cancel_default_registered_offis_row += 1;
                                $cancel_default_nomi_director_row += 1;
                            }
                            else if($row->service_type == "7")
                            {
                                $sheet->setCellValue('A'.$cancel_default_registered_offis_row, $recurring_cancel_date_array_excel);
                                $sheet->setCellValue('B'.$cancel_default_registered_offis_row, $this->encryption->decrypt($row->company_name));
                                $sheet->setCellValue('C'.$cancel_default_registered_offis_row, $recu_invoice_issue_date_array_excel);
                                $sheet->setCellValue('D'.$cancel_default_registered_offis_row, $row->amount);
                                //it pushes row 5 down to row 6
                                $sheet->insertNewRowBefore($cancel_default_registered_offis_row + 1, 1);
                                //$default_corporate_sec_row += 1;
                                $cancel_default_registered_offis_row += 1;
                                $cancel_default_nomi_director_row += 1;
                            }
                            else if(strpos($row->service_name, "NOMINEE DIRECTOR") !== false)
                            {
                                $sheet->setCellValue('A'.$cancel_default_nomi_director_row, $recurring_cancel_date_array_excel);
                                $sheet->setCellValue('B'.$cancel_default_nomi_director_row, $this->encryption->decrypt($row->company_name));
                                $sheet->setCellValue('C'.$cancel_default_nomi_director_row, $recu_invoice_issue_date_array_excel);
                                $sheet->setCellValue('D'.$cancel_default_nomi_director_row, $row->amount);
                                //it pushes row 5 down to row 6
                                $sheet->insertNewRowBefore($cancel_default_nomi_director_row + 1, 1);
                                //$default_corporate_sec_row += 1;
                                //$default_registered_offis_row += 1;
                                $cancel_default_nomi_director_row += 1;
                            }
                        }
                    }
                }
            }
            $sheet = $spreadsheet->setActiveSheetIndex(0);

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                         
            $filename = $_SERVER['DOCUMENT_ROOT'].'/secretary/assets/uploads/excel/CSP Auto Billing Report.xlsx';
            $writer->save($filename);

            // $this->zip->read_file($filename);

            // $this->zip->archive($_SERVER['DOCUMENT_ROOT'].'/secretary/assets/uploads/excel/registrable_controller_details.zip');

            //$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
            $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
            $link = $protocol . $_SERVER['SERVER_NAME'] .'/secretary/assets/uploads/excel/CSP Auto Billing Report.xlsx';

            $this->data['status'] = "success";
            $this->data['link'] = $link;
        }
        else if($selected_csp_report_type == "csp_revenue_report")
        {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."/secretary/assets/uploads/file/CSP Revenue Report.xlsx");
            $sheet = $spreadsheet->getSheet(0);

            $start_array = explode('/',$start);
            $start_year = $start_array[2];
            $start_month = $start_array[1];
            $start_day = $start_array[0];
            $formatted_start_date = $start_array[1].'/'.$start_array[0].'/'.$start_array[2];

            $end_array = explode('/',$end);
            $end_year = $end_array[2];
            $end_month = $end_array[1];
            $end_day = $end_array[0];
            $formatted_end_date = $end_array[1].'/'.$end_array[0].'/'.$end_array[2];

            $newBillingListInfo = [];
            $newBillingListKey = [];
            $newKey = 0;
            $data = [];

            $this->db->select('billing_info_service_category.*');
            $this->db->from('billing_info_service_category');
            $category_query = $this->db->get();

            $category_query = $category_query->result_array();
            //, client.company_name, transaction_client.company_name as trans_company_name
            $this->db->select('billing.*, client.registration_no, client.former_name, currency.currency as currency_name, billing_info_service_category.category_description, billing_info_service_category.id as billing_info_service_category_id, billing_service.amount as billing_service_amount, billing_service.period_end_date, billing_service.invoice_description, firm.name as firm_name, firm.branch_name, our_service_info.service_name, b.service_name as trans_service_name, c.category_description as trans_category_description, c.id as trans_billing_info_service_category_id, d.service_name as our_service_service_name, e.category_description as our_service_category_description, e.id as our_service_billing_info_service_category_id, transaction_master_with_billing.id as transaction_master_with_billing_id');
            $this->db->from('billing');
            $this->db->join('client', 'client.company_code = billing.company_code', 'left');
            $this->db->join('user_firm', 'user_firm.firm_id = billing.firm_id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left');
            $this->db->join('transaction_client', 'transaction_client.company_code = billing.company_code', 'left');
            $this->db->join('currency', 'billing.currency_id = currency.id', 'left');
            $this->db->join('billing_service', 'billing_service.billing_id = billing.id', 'left');
            $this->db->join('transaction_master_with_billing', 'transaction_master_with_billing.billing_id = billing.id', 'left');
            //take transaction our service info
            $this->db->join('transaction_client_billing_info as a', 'a.id = billing_service.service', 'left');
            $this->db->join('our_service_info as b', 'b.id = a.service', 'left');
            $this->db->join('billing_info_service_category as c', 'c.id = b.service_type', 'left');
            //take our service info
            $this->db->join('our_service_info as d', 'd.id = billing_service.service', 'left');
            $this->db->join('billing_info_service_category as e', 'e.id = d.service_type', 'left');
            //take client our service info
            $this->db->join('client_billing_info', 'client_billing_info.id = billing_service.service', 'left');
            $this->db->join('our_service_info', 'our_service_info.id = client_billing_info.service', 'left');
            $this->db->join('billing_info_service_category', 'billing_info_service_category.id = our_service_info.service_type', 'left');

            $this->db->join('firm', 'firm.id = billing.firm_id', 'left');
            if ($start != NULL)
            {
                //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
                if ($end != NULL)
                {
                    $sheet->setCellValue('A1', "Revenue Report from ".date_format(date_create($formatted_start_date), "d F Y")." to ".date_format(date_create($formatted_end_date), "d F Y"));

                    $this->db->where('STR_TO_DATE(billing.invoice_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
                }
                else
                {
                    $sheet->setCellValue('A1', "Revenue Report from ".date_format(date_create($formatted_start_date), "d F Y"));

                    $this->db->where('STR_TO_DATE(billing.invoice_date,"%d/%m/%Y") >= STR_TO_DATE("'. $start. '","%d/%m/%Y")');
                }
            }
            else
            {
                $sheet->setCellValue('A1', "Revenue Report");
            }

            $this->db->order_by('id', 'desc');
            if($selected_firm_id != "all")
            {
                $this->db->where('billing.firm_id', $selected_firm_id);
            }
            $this->db->where('user_firm.firm_id = billing.firm_id');
            $this->db->where('billing.status', "0");
            //$this->db->group_by('billing.id');
            $q = $this->db->get();

            if ($q->num_rows() > 0) 
            {   
                // /print_r($q->result_array());
                foreach (($q->result_array()) as $row => $rowValue) 
                {
                    //$data[] = $row;
                    // if($rowValue["company_name"] != null)
                    // {
                        //$rowValue["company_name"] = $this->encryption->decrypt($rowValue["company_name"]);
                    //}
                    // else
                    // {
                    //     $rowValue["trans_company_name"] = $this->encryption->decrypt($rowValue["trans_company_name"]);
                    // }
                    if(!in_array($rowValue["id"],$newBillingListKey)){
                        ++$newKey;
                        $newBillingListInfo[$newKey]["id"] = $rowValue["id"];
                        $newBillingListInfo[$newKey]["invoice_date"] = $rowValue["invoice_date"];
                        $newBillingListInfo[$newKey]["invoice_no"] = $rowValue["invoice_no"];
                        $newBillingListInfo[$newKey]["company_name"] = $rowValue["company_name"];//(($rowValue["company_name"] != null)?$rowValue["company_name"]:$rowValue["trans_company_name"]);
                        $newBillingListInfo[$newKey]["currency_name"] = $rowValue["currency_name"];
                        $newBillingListInfo[$newKey]["amount"] = $rowValue["amount"];
                        $newBillingListInfo[$newKey]["outstanding"] = $rowValue["outstanding"];
                        $newBillingListInfo[$newKey]["firm_name"] = $rowValue["firm_name"];
                        $newBillingListInfo[$newKey]["branch_name"] = $rowValue["branch_name"];
                        $newBillingListInfo[$newKey]["period_end_date"] = [];
                        $newBillingListInfo[$newKey]["invoice_description"] = [];
                        $newBillingListInfo[$newKey]["service_name"] = [];
                        $newBillingListInfo[$newKey]["billing_service_amount"] = [];
                        // $newBillingListInfo[$newKey]["incorp_amount"] = 0.00;
                        // $newBillingListInfo[$newKey]["discount_amount"] = 0.00;
                        // $newBillingListInfo[$newKey]["training_amount"] = 0.00;
                        // $newBillingListInfo[$newKey]["compilation_amount"] = 0.00;

                        $newBillingListInfo[$newKey]["category"]["Corporate Secretary"] = number_format(0.00, 2);
                        $newBillingListInfo[$newKey]["category"]["Registered Office Address"] = number_format(0.00, 2);
                        $newBillingListInfo[$newKey]["category"]["Nominee Director"] = number_format(0.00, 2);
                        $newBillingListInfo[$newKey]["category"]["Incorporation"] = number_format(0.00, 2);
                        $newBillingListInfo[$newKey]["category"]["Share Transfers"] = number_format(0.00, 2);
                        $newBillingListInfo[$newKey]["category"]["Share Allotment"] = number_format(0.00, 2);
                        $newBillingListInfo[$newKey]["category"]["Strike Off"] = number_format(0.00, 2);
                        $newBillingListInfo[$newKey]["category"]["Disbursement"] = number_format(0.00, 2);
                        $newBillingListInfo[$newKey]["category"]["Others"] = number_format(0.00, 2);

                    }
                    
                    array_push($newBillingListInfo[$newKey]["period_end_date"], $rowValue["period_end_date"]);
                    array_push($newBillingListInfo[$newKey]["invoice_description"], htmlspecialchars(strtoupper($rowValue["invoice_description"])));
                    array_push($newBillingListInfo[$newKey]["billing_service_amount"], $rowValue["billing_service_amount"]);

                    if($rowValue["trans_billing_info_service_category_id"] != null)
                    {
                        array_push($newBillingListInfo[$newKey]["service_name"], strtoupper($rowValue["trans_service_name"]));
                    }
                    else if($rowValue["our_service_billing_info_service_category_id"] != null)
                    {
                        array_push($newBillingListInfo[$newKey]["service_name"], strtoupper($rowValue["our_service_service_name"]));
                    }
                    else if($rowValue["billing_info_service_category_id"] != null)
                    {
                        array_push($newBillingListInfo[$newKey]["service_name"], strtoupper($rowValue["service_name"]));
                    }

                    if($rowValue["billing_info_service_category_id"] == 4 || $rowValue["trans_billing_info_service_category_id"] == 4 || $rowValue["our_service_billing_info_service_category_id"] == 4 || $rowValue["billing_info_service_category_id"] == 7 || $rowValue["trans_billing_info_service_category_id"] == 7 || $rowValue["our_service_billing_info_service_category_id"] == 7 || $rowValue["billing_info_service_category_id"] == 8 || $rowValue["trans_billing_info_service_category_id"] == 8 || $rowValue["our_service_billing_info_service_category_id"] == 8)
                    {
                        if($rowValue["trans_billing_info_service_category_id"] != null)
                        {
                            if(preg_match("/CORPORATE SECRETARY/i", $rowValue["trans_service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Corporate Secretary"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Corporate Secretary"]) + $rowValue["billing_service_amount"]);
                            }
                            else if(preg_match("/REGISTERED OFFICE/i", $rowValue["trans_service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Registered Office Address"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Registered Office Address"]) + $rowValue["billing_service_amount"]);
                            }
                            else if(preg_match("/NOMINEE DIRECTOR/i", $rowValue["trans_service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Nominee Director"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Nominee Director"]) + $rowValue["billing_service_amount"]);
                            }
                            else if(preg_match("/INCORPORATION/i", $rowValue["trans_service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Incorporation"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Incorporation"]) + $rowValue["billing_service_amount"]);
                            }
                            else if(preg_match("/SHARE TRANSFER/i", $rowValue["trans_service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Share Transfers"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Share Transfers"]) + $rowValue["billing_service_amount"]);
                            }
                            else if(preg_match("/SHARE ALLOTMENT/i", $rowValue["trans_service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Share Allotment"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Share Allotment"]) + $rowValue["billing_service_amount"]);
                            }
                            else if(preg_match("/STRIKE OFF/i", $rowValue["trans_service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Strike Off"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Strike Off"]) + $rowValue["billing_service_amount"]);
                            }
                            else if(preg_match("/DISBURSEMENT/i", $rowValue["trans_service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Disbursement"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Disbursement"]) + $rowValue["billing_service_amount"]);
                            }
                            else
                            {
                                $newBillingListInfo[$newKey]["category"]["Others"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Others"]) + $rowValue["billing_service_amount"]);
                            }
                        }
                        else if($rowValue["our_service_billing_info_service_category_id"] != null)
                        {
                            if(preg_match("/CORPORATE SECRETARY/i", $rowValue["our_service_service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Corporate Secretary"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Corporate Secretary"]) + $rowValue["billing_service_amount"]);
                            }
                            else if(preg_match("/REGISTERED OFFICE/i", $rowValue["our_service_service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Registered Office Address"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Registered Office Address"]) + $rowValue["billing_service_amount"]);
                            }
                            else if(preg_match("/NOMINEE DIRECTOR/i", $rowValue["our_service_service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Nominee Director"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Nominee Director"]) + $rowValue["billing_service_amount"]);
                            }
                            else if(preg_match("/INCORPORATION/i", $rowValue["our_service_service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Incorporation"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Incorporation"]) + $rowValue["billing_service_amount"]);
                            }
                            else if(preg_match("/SHARE TRANSFER/i", $rowValue["our_service_service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Share Transfers"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Share Transfers"]) + $rowValue["billing_service_amount"]);
                            }
                            else if(preg_match("/SHARE ALLOTMENT/i", $rowValue["our_service_service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Share Allotment"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Share Allotment"]) + $rowValue["billing_service_amount"]);
                            }
                            else if(preg_match("/STRIKE OFF/i", $rowValue["our_service_service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Strike Off"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Strike Off"]) + $rowValue["billing_service_amount"]);
                            }
                            else if(preg_match("/DISBURSEMENT/i", $rowValue["our_service_service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Disbursement"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Disbursement"]) + $rowValue["billing_service_amount"]);
                            }
                            else
                            {
                                $newBillingListInfo[$newKey]["category"]["Others"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Others"]) + $rowValue["billing_service_amount"]);
                            }
                        }
                        else if($rowValue["billing_info_service_category_id"] != null)
                        {
                            if(preg_match("/CORPORATE SECRETARY/i", $rowValue["service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Corporate Secretary"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Corporate Secretary"]) + $rowValue["billing_service_amount"]);
                            }
                            else if(preg_match("/REGISTERED OFFICE/i", $rowValue["service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Registered Office Address"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Registered Office Address"]) + $rowValue["billing_service_amount"]);
                            }
                            else if(preg_match("/NOMINEE DIRECTOR/i", $rowValue["service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Nominee Director"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Nominee Director"]) + $rowValue["billing_service_amount"]);
                            }
                            else if(preg_match("/INCORPORATION/i", $rowValue["service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Incorporation"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Incorporation"]) + $rowValue["billing_service_amount"]);
                            }
                            else if(preg_match("/SHARE TRANSFER/i", $rowValue["service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Share Transfers"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Share Transfers"]) + $rowValue["billing_service_amount"]);
                            }
                            else if(preg_match("/SHARE ALLOTMENT/i", $rowValue["service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Share Allotment"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Share Allotment"]) + $rowValue["billing_service_amount"]);
                            }
                            else if(preg_match("/STRIKE OFF/i", $rowValue["service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Strike Off"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Strike Off"]) + $rowValue["billing_service_amount"]);
                            }
                            else if(preg_match("/DISBURSEMENT/i", $rowValue["service_name"]))
                            {
                                $newBillingListInfo[$newKey]["category"]["Disbursement"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Disbursement"]) + $rowValue["billing_service_amount"]);
                            }
                            else
                            {
                                $newBillingListInfo[$newKey]["category"]["Others"] = ((float)str_replace( ',', '',$newBillingListInfo[$newKey]["category"]["Others"]) + $rowValue["billing_service_amount"]);
                            }
                        }
                    }

                    $newBillingListKey[]  = $rowValue["id"];
                }

                $num_row = 4;
                foreach (($newBillingListInfo) as $row) 
                {
                    $data[] = $row;
                    //echo json_encode($row["category"]);
                    foreach ($row["category"] as $key => $value) 
                    {
                        if((float)$value > 0)
                        {
                            if($row["invoice_date"] != "")
                            {
                                $invoice_date_array = explode('/',$row["invoice_date"]);
                                $invoice_date_year = $invoice_date_array[2];
                                $invoice_date_month = $invoice_date_array[1];
                                $invoice_date_day = $invoice_date_array[0];
                                $formatted_invoice_date_date = $invoice_date_array[1].'/'.$invoice_date_array[0].'/'.$invoice_date_array[2];
                                $invoice_date_array_excel = date_format(date_create($formatted_invoice_date_date), "d M Y");
                            }
                            else
                            {
                                $invoice_date_array_excel = "";
                            }

                            $sheet->setCellValue('A'.$num_row, $invoice_date_array_excel);
                            $sheet->setCellValue('B'.$num_row, $row["company_name"]);
                            $sheet->setCellValue('C'.$num_row, $key);
                            $sheet->setCellValue('D'.$num_row, $value);

                            if((float)$row["outstanding"] > 0)
                            {
                                $sheet->setCellValue('E'.$num_row, "Unpaid");
                            }
                            else
                            {
                                $sheet->setCellValue('E'.$num_row, "Paid");
                            }

                            $num_row += 1;
                        }
                    }
                }

                //$sheet = $spreadsheet->setActiveSheetIndex(0);

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                             
                $filename = $_SERVER['DOCUMENT_ROOT'].'/secretary/assets/uploads/excel/CSP Revenue Report.xlsx';
                $writer->save($filename);
                //$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
                $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
                $link = $protocol . $_SERVER['SERVER_NAME'] .'/secretary/assets/uploads/excel/CSP Revenue Report.xlsx';

                $this->data['status'] = "success";
                $this->data['link'] = $link;

                $this->data['list_of_invoice'] = $data;
                //return $this->data;
            }
            else
            {
                $this->data['status'] = "unsucessful";
                //$this->data['list_of_invoice'] = $data;
                //return $this->data;
            }
        }

        return $this->data;
    }

    public function search_progress_bill_report($selected_firm_id, $start = null, $end = null)
    {
        $this->db->select('billing.*, client.registration_no, client.former_name, currency.currency as currency_name, billing_info_service_category.category_description, billing_info_service_category.id as billing_info_service_category_id, billing_service.amount as billing_service_amount, billing_service.period_start_date, billing_service.period_end_date, billing_service.invoice_description, billing_service.progress_billing_yes_no, billing_service.poc_percentage, billing_service.number_of_percent_poc, billing_service.radio_quantity_reading, billing_service.reading_at_begin, billing_service.reading_at_the_end, billing_service.number_of_rate, billing_service.unit_for_rate, billing_service.quantity_value, firm.name as firm_name, firm.branch_name, our_service_info.service_name, b.service_name as trans_service_name, c.category_description as trans_category_description, c.id as trans_billing_info_service_category_id, d.service_name as our_service_service_name, e.category_description as our_service_category_description, e.id as our_service_billing_info_service_category_id, transaction_master_with_billing.id as transaction_master_with_billing_id');
        $this->db->from('billing');
        $this->db->join('client', 'client.company_code = billing.company_code', 'left');
        $this->db->join('user_firm', 'user_firm.firm_id = billing.firm_id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left');
        $this->db->join('transaction_client', 'transaction_client.company_code = billing.company_code', 'left');
        $this->db->join('currency', 'billing.currency_id = currency.id', 'left');
        $this->db->join('billing_service', 'billing_service.billing_id = billing.id', 'left');
        $this->db->join('transaction_master_with_billing', 'transaction_master_with_billing.billing_id = billing.id', 'left');

        $this->db->join('transaction_client_billing_info as a', 'a.id = billing_service.service', 'left');
        $this->db->join('our_service_info as b', 'b.id = a.service', 'left');
        $this->db->join('billing_info_service_category as c ', 'c.id = b.service_type', 'left');

        $this->db->join('our_service_info as d', 'd.id = billing_service.service', 'left');
        $this->db->join('billing_info_service_category as e ', 'e.id = d.service_type', 'left');

        $this->db->join('client_billing_info', 'client_billing_info.id = billing_service.service', 'left');
        $this->db->join('our_service_info', 'our_service_info.id = client_billing_info.service', 'left');
        $this->db->join('billing_info_service_category', 'billing_info_service_category.id = our_service_info.service_type', 'left');

        $this->db->join('firm', 'firm.id = billing.firm_id', 'left');
        $this->db->order_by('id', 'desc');
        if ($start != NULL)
        {
            if ($end != NULL)
            {

                $this->db->where('STR_TO_DATE(billing.invoice_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
            }
            else
            {
                $this->db->where('STR_TO_DATE(billing.invoice_date,"%d/%m/%Y") >= STR_TO_DATE("'. $start. '","%d/%m/%Y")');
            }
        }

        if($selected_firm_id != "all")
        {
            $this->db->where('billing.firm_id', $selected_firm_id);
        }
        //$this->db->where('billing.company_code', $company_code);
        //$this->db->where('billing_service.service', $service_value);
        $this->db->where('billing_service.progress_billing_yes_no = "yes"');
        $this->db->where('user_firm.firm_id = billing.firm_id');
        $this->db->where('billing.status', "0");
        //$this->db->group_by('billing.id');
        $q = $this->db->get();

        $data = [];

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
        }
        $this->data['progress_bill_report'] = $data;
        return $this->data;
    }

    public function export_register_of_controller()
    {
        $selected_firm_id = $_POST['firm'];
        $number_of_data = 1;
        $number_of_excel = 1;
        $r = 2;

        if($selected_firm_id != "all")
        {
            $where = 'a_client.firm_id = "'.$selected_firm_id.'" AND ';
        }
        else
        {
            $where = '';
        }

        $q = $this->db->query('select 
            a_client.registration_no as uen, 
            client_controller.id as client_controller_id,
            client_controller.date_of_registration as date_appointed, 
            client_controller.date_of_cessation as date_ceased, 
            officer.field_type as officer_field_type, 
            officer.identification_type as officer_identification_type, 
            officer.identification_no, officer.name,
            officer.alias, 
            officer.date_of_birth, 
            officer.address_type as officer_address_type, 
            officer.postal_code1 as officer_postal_code, 
            officer.street_name1 as officer_street_name,
            officer.building_name1 as officer_builing_name,
            officer.unit_no1 as officer_unit_no1,
            officer.unit_no2 as officer_unit_no2,
            officer.foreign_address1 as officer_foreign_address1,
            officer.foreign_address2 as officer_foreign_address2,
            officer.foreign_address3 as officer_foreign_address3,
            nationality.code,
            officer_company.field_type as officer_company_field_type,
            officer_company.company_name,
            officer_company.register_no,
            officer_company.entity_issued_by_registrar,
            officer_company.legal_form_entity,
            officer_company.country_of_incorporation,
            officer_company.statutes_of,
            officer_company.coporate_entity_name,
            company_nationality.code as company_nationality_code,
            b_client.registration_no,
            b_client.company_name as client_company_name, 
            company_type.company_type as client_company_type,
            b_client.client_country_of_incorporation,
            b_client.client_statutes_of,
            b_client.client_coporate_entity_name
            from client as a_client 
            left join client_controller on client_controller.company_code = a_client.company_code 
            left join officer on client_controller.officer_id = officer.id and client_controller.field_type = officer.field_type 
            left join nationality on nationality.id = officer.nationality 
            left join officer_company on client_controller.officer_id = officer_company.id and client_controller.field_type = officer_company.field_type 
            left join company_jurisdiction as company_nationality on company_nationality.jurisdiction = officer_company.country_of_incorporation 
            left join client as b_client on b_client.id = client_controller.officer_id AND client_controller.field_type = "client" 
            left join company_type on company_type.id = a_client.company_type 
            where '.$where.' a_client.acquried_by = "1" AND a_client.deleted != "1" AND a_client.status = "1" AND client_controller.deleted = 0');

        if ($q->num_rows() > 0) 
        {
            foreach (($q->result()) as $row) 
            {
                if($row->client_controller_id != null)
                {
                    if($number_of_data == 1)
                    {
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                        $spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."/secretary/assets/uploads/file/registrable_controller_details.xlsx");
                        //$sheet = $spreadsheet->getActiveSheet();
                        $sheet = $spreadsheet->getSheet(1);
                    }

                    $row->uen = $this->encryption->decrypt($row->uen);
                    if($row->officer_field_type == "individual")
                    {
                        $row->identification_no = $this->encryption->decrypt($row->identification_no);
                        $row->name = $this->encryption->decrypt($row->name);
                    }
                    elseif($row->officer_company_field_type == "company")
                    {
                        $row->register_no = $this->encryption->decrypt($row->register_no);
                        $row->company_name = $this->encryption->decrypt($row->company_name);
                    }
                    else
                    {
                        $row->registration_no = $this->encryption->decrypt($row->registration_no);
                        $row->client_company_name = $this->encryption->decrypt($row->client_company_name);
                    }

                    if($row->officer_field_type == "individual")
                    {
                        $category_type = "I";
                        $name = $row->name;
                        // if($row->officer_identification_type == "NRIC (Singapore citizen)")
                        // {
                        //     $identification_type = "1";
                        // }
                        // else if($row->officer_identification_type == "NRIC (PR)")
                        // {
                        //     $identification_type = "2";
                        // }
                        // else if($row->officer_identification_type == "FIN Number")
                        // {
                        //     $identification_type = "3";
                        // }
                        // else if($row->officer_identification_type == "Passport/ Others")
                        // {
                        //     $identification_type = "4";
                        // }
                        $identification_no = $row->identification_no;
                        $uen = "";
                        $acra_uen = "";
                        $nation_code = $row->code;
                        $alias = $row->alias;
                        $date_of_birth = DATE("d/m/Y", strtotime($row->date_of_birth));
                        if($row->officer_address_type == "Local")
                        {
                            $address_type = "S";
                            $postal_code = $row->officer_postal_code;
                            $firstChar = substr($row->officer_street_name, 0, 1);
                            //echo json_encode($firstChar);
                            if( (int)$firstChar <=9 && (int)$firstChar >=0) {
                                //do your stuff
                                $firstWord = strtok($row->officer_street_name, " ");
                                $block = $firstWord;
                            }
                            else
                            {
                                $block = "";
                            }
                            $level = $row->officer_unit_no1;
                            $unit = $row->officer_unit_no2;
                            $foreign_add1 = "";
                            $foreign_add2 = "";
                        }
                        else
                        {
                            $address_type = "F";
                            $postal_code = "";
                            $block = "";
                            $level = "";
                            $unit = "";
                            $foreign_add1 = $row->officer_foreign_address1;
                            $foreign_add2 = $row->officer_foreign_address2." ".$row->officer_foreign_address3;
                        }

                        $legal_form = "";
                        $juridiction = "";
                        $statutes_of = "";
                        $corporate_entity_name = "";
                    }
                    else
                    {
                        $category_type = "C";
                        //$identification_type = "";
                        $identification_no = "";
                        if($row->company_name != null)
                        {
                            $name = $row->company_name;
                        }
                        else if($row->client_company_name != null)
                        {
                            $name = $row->client_company_name;
                        }

                        if($row->register_no != null && $row->country_of_incorporation != "SINGAPORE")
                        {
                            $uen = $row->register_no;
                        }
                        else if($row->entity_issued_by_registrar != null)
                        {
                            $uen = $row->entity_issued_by_registrar;
                        }
                        else
                        {
                            $uen = "";
                        }
                        
                        if($row->register_no != null && $row->country_of_incorporation == "SINGAPORE")
                        {
                            $acra_uen = $row->register_no;
                        }
                        else if($row->registration_no != null)
                        {
                            $acra_uen = $row->registration_no;
                        }
                        else if($row->entity_issued_by_registrar != null)
                        {
                            $acra_uen = $row->entity_issued_by_registrar;
                        }

                        if($row->legal_form_entity != null)
                        {
                            $legal_form = $row->legal_form_entity;
                        }
                        else if($row->client_company_type != null)
                        {
                            $legal_form = $row->client_company_type;
                        }

                        if($row->company_nationality_code != null)
                        {
                            $juridiction = $row->company_nationality_code;
                        }
                        else if($row->client_country_of_incorporation != null)
                        {
                            $juridiction = "SG";
                        }

                        if($row->statutes_of != null)
                        {
                            $statutes_of = $row->statutes_of;
                        }
                        else if($row->client_statutes_of != null)
                        {
                            $statutes_of = $row->client_statutes_of;
                        }

                        if($row->coporate_entity_name != null)
                        {
                            $corporate_entity_name = $row->coporate_entity_name;
                        }
                        else if($row->client_coporate_entity_name != null)
                        {
                            $corporate_entity_name = $row->client_coporate_entity_name;
                        }

                        $nation_code = "";
                        $date_of_birth = "";
                        $address_type = "";
                        $postal_code = "";
                        $block = "";
                        $level = "";
                        $unit = "";
                        $foreign_add1 = "";
                        $foreign_add2 = "";
                    }
                    
                    $sheet->setCellValue('A'.$r, $row->uen);
                    $sheet->setCellValue('B'.$r, $category_type);
                    $sheet->setCellValue('C'.$r, $name);
                    //$sheet->setCellValue('D'.$r, $identification_type);
                    $sheet->setCellValue('D'.$r, $identification_no);
                    $sheet->setCellValue('E'.$r, $nation_code);
                    $sheet->setCellValue('F'.$r, $date_of_birth);
                    $sheet->setCellValue('G'.$r, $alias);
                    $sheet->setCellValue('H'.$r, $acra_uen);
                    $sheet->setCellValue('I'.$r, $uen);
                    $sheet->setCellValue('J'.$r, $legal_form);
                    $sheet->setCellValue('K'.$r, $juridiction);
                    $sheet->setCellValue('L'.$r, $statutes_of);
                    $sheet->setCellValue('M'.$r, $corporate_entity_name);
                    $sheet->setCellValue('N'.$r, $address_type);
                    $sheet->setCellValue('O'.$r, $postal_code);
                    $sheet->setCellValue('P'.$r, $block);
                    $sheet->setCellValue('Q'.$r, $level);
                    $sheet->setCellValue('R'.$r, $unit);
                    $sheet->setCellValue('S'.$r, $foreign_add1);
                    $sheet->setCellValue('T'.$r, $foreign_add2);
                    $sheet->setCellValue('U'.$r, $row->date_appointed);
                    $sheet->setCellValue('V'.$r, $row->date_ceased);
                    $sheet->setCellValue('W'.$r, "Y");
                    $r = $r + 1;
                    $number_of_data = $number_of_data + 1;

                    if($number_of_data == 500)
                    {
                        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                 
                        $filename = $_SERVER['DOCUMENT_ROOT'].'/secretary/assets/uploads/excel/registrable_controller_details'.$number_of_excel.'.xlsx';
                        $writer->save($filename);
                        $this->zip->read_file($filename);

                        $number_of_excel = $number_of_excel + 1;
                        $number_of_data = 1;
                        $r = 2;
                        //$this->zip->archive($_SERVER['DOCUMENT_ROOT'].'/secretary/assets/uploads/excel/registrable_controller_details.zip');
                    }
                }
            }
        }
        
        if(500 > $number_of_data)
        {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                 
            $filename = $_SERVER['DOCUMENT_ROOT'].'/secretary/assets/uploads/excel/registrable_controller_details'.$number_of_excel.'.xlsx';
            $writer->save($filename);
            $this->zip->read_file($filename);
        }

        $this->zip->archive($_SERVER['DOCUMENT_ROOT'].'/secretary/assets/uploads/excel/registrable_controller_details.zip');

        //$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
        $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
        $link = $protocol . $_SERVER['SERVER_NAME'] .'/secretary/assets/uploads/excel/registrable_controller_details.zip';

        $data = array('status'=>'success', 'link'=>$link);

        echo json_encode($data);
    }

    public function get_name()
    {
        $selected_firm_id= $_POST['firm'];
        $selected_type_of_payment= $_POST['type_of_payment'];

        //echo json_encode($selected_firm);

        if($selected_type_of_payment == "supplier")
        {
            $this->db->select('vendor_info.*');
            $this->db->from('vendor_info');
            $this->db->join('user_firm as a', 'a.user_id = "'.$this->session->userdata("user_id").'"', 'inner');
            if($selected_firm_id != "all")
            {
                $this->db->where('vendor_info.firm_id', $selected_firm_id);
            }
            $this->db->where('vendor_info.deleted !=', 1);
            $this->db->where('a.firm_id = vendor_info.firm_id');
            $this->db->group_by('vendor_info.id');
            $q = $this->db->get();

            if ($q->num_rows() > 0) 
            {
                $result_vendor_info = $q->result_array();
                for($j = 0; $j < count($result_vendor_info); $j++)
                {
                    $res[$result_vendor_info[$j]['supplier_code']] = $result_vendor_info[$j]['company_name'];
                }
            }
            else
            {
                $res = [];
            }

            $this->data["name"] = $res;

        }
        elseif($selected_type_of_payment == "client")
        {
            $this->db->select('client.*');
            $this->db->from('client');
            $this->db->join('user_firm as a', 'a.user_id = "'.$this->session->userdata("user_id").'"', 'inner');
            if($selected_firm_id != "all")
            {
                $this->db->where('client.firm_id', $selected_firm_id);
            }
            $this->db->where('client.deleted !=', 1);
            $this->db->where('a.firm_id = client.firm_id');
            $this->db->group_by('client.id');
            $q = $this->db->get();

            if ($q->num_rows() > 0) 
            {
                $result_client_info = $q->result_array();
                for($j = 0; $j < count($result_client_info); $j++)
                {
                    $res[$result_client_info[$j]['company_code']] = $this->encryption->decrypt($result_client_info[$j]['company_name']);
                }
            }
            else
            {
                $res = [];
            }

            $this->data["name"] = $res;

        }
        elseif($selected_type_of_payment == "claim")
        {
            $ci =& get_instance();

            $query = 'SELECT users.id, users.last_name, users.first_name FROM users left join user_firm as a on a.user_id = "'.$ci->session->userdata("user_id").'" left join user_firm as b on b.firm_id = a.firm_id where b.user_id = users.id AND users.id != 1 AND users.user_deleted = 0 AND users.active = 1 GROUP BY users.id';

            $result = $ci->db->query($query);

            if ($result->num_rows() > 0) 
            {
                $result = $result->result_array();

                if(!$result) {
                  throw new exception("Users not found.");
                }

                $res = array();
                foreach($result as $row) {
                    if($row['first_name'] != null)
                    {
                        $res[$row['id']] = $row['last_name']." ".$row['first_name'];
                    }
                  
                }
                //$this->data["name"] = $res;
            }
            else
            { 
                $res = array();

            }

            $this->data["name"] = $res;
        }   

        echo json_encode($this->data);
    }

    public function get_bank_info()
    {
        $selected_firm_id = $_POST['firm'];

        if($selected_firm_id != "all")
        {
            $result = $this->db->query("select bank_info.*, currency.currency as currency_name from bank_info left join currency on currency.id = bank_info.currency where firm_id = '".$selected_firm_id."'");
        }
        else
        {
            $result = $this->db->query("select bank_info.*, currency.currency as currency_name from bank_info inner join user_firm as a on a.user_id = '".$this->session->userdata("user_id")."' left join currency on currency.id = bank_info.currency where a.firm_id = bank_info.firm_id group by bank_info.id");
        }

        $result = $result->result_array();

        if(!$result) {
            //throw new exception("Currency not found.");
            echo false;
        }
        $res = array();
        foreach($result as $row) {
            $res[$row['id']] = $row['banker']." (".$row['currency_name'].")(".$row['account_number'].")";
        }

        $ci =& get_instance();
        $selected_bank_acc = $ci->session->userdata('payment_voucher_bank_acc_id');
        $ci->session->unset_userdata('payment_voucher_bank_acc_id');

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Currency fetched successfully.", 'result'=>$res, 'selected_bank_acc'=>$selected_bank_acc);

        echo json_encode($data);
    }


}