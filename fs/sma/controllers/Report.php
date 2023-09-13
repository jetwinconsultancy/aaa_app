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
        //$this->load->model('report_model');
        $this->load->library(array('session', 'form_validation'));
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
        for($j = 0; $j < count($result_firm); $j++)
        {
            $res[$result_firm[$j]['id']] = $result_firm[$j]['name'];
        }
        $this->data["firm"] = $res;

/*        $this->data['pending_documents'] = $this->document_model->get_all_pending();
        $this->data['all_documents'] = $this->document_model->get_all_document();
        $this->data['document_master'] = $this->document_model->get_all_document_master();
        $this->data['document_reminder'] = $this->document_model->get_all_document_reminder();*/
		// $this->data['page_name'] = 'Clients';
        $this->page_construct('report.php', $meta, $this->data);

    }

    public function search_report()
    {
        $firm_id = $this->session->userdata('firm_id');
        $register = $_POST['report_to_generate'];
        $client_id = $_POST['client_id'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $selected_firm_id = $_POST['firm'];
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
            echo json_encode(array("register" => $register, $this->search_client_list($firm_id)));
        }
        else if($register == "due_date")
        {
            echo json_encode(array("register" => $register, $this->search_due_date($firm_id)));
        }
        else if($register == "list_of_invoice")
        {
            echo json_encode(array("register" => $register, $this->search_list_of_invoice($selected_firm_id, $from, $to)));
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
        $company_code = $this->db->query("select company_code from client where firm_id = '".$firm_id."'");

        $company_code = $company_code->result_array();

        //echo json_encode($company_code);

        $where = "";

        if ($start != NULL)
        {
            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
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
            $officer_info = $this->db->query('select client_officers.*, client.company_name as client_company_name, client_officers_position.position as position_name, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type left join client_officers_position on client_officers.position = client_officers_position.id left join client on client_officers.company_code = client.company_code where client_officers.company_code ="'.$company_code[$r]["company_code"].'" AND (officer_company.register_no = "'.$client_id.'" OR officer.identification_no = "'.$client_id.'")'.$where.'');

            if($officer_info != null)
            {
                if ($officer_info->num_rows() > 0) {
                    foreach (($officer_info->result()) as $row) {
                        $data_officer[] = $row;
                    }
                    //$data = array_merge($data, $data_officer);

                }
            }
        }
        $data = array_merge($data, $data_officer);
        //echo json_encode($company_code);
        for($i = 0; $i < count($company_code); $i++)
        {
           $q = $this->db->query('select member_shares.*, client.company_name as client_company_name, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on member_shares.company_code = client.company_code where member_shares.company_code ="'.$company_code[$i]["company_code"].'" AND number_of_share != 0 AND (officer_company.register_no = "'.$client_id.'" OR officer.identification_no = "'.$client_id.'") GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id');

            if($officer_info != null)
            {
                if ($q->num_rows() > 0) {
                    $q = $q->result_array();
                    foreach ($q as $key => $row) {
                        //$data_member[] = $row;

                        $member[0]["position_name"] = "Member";
                        $member[0]["date_of_appointment"] = "";
                        $member[0]["date_of_cessation"] = "";
                        $q[$key] = array_merge($q[$key], $member[0]);
                    }

                    $data = array_merge($data, $q);
                    $this->data['person_profile'] = $data;
                    // return $this->data;
                }
                else
                {
                    $this->data['person_profile'] = $data;
                    // return $this->data;
                }
            }
            else
            {
                $this->data['person_profile'] = $data;
                // return $this->data;
            }

        }

        return $this->data;
        

        
    }

    public function search_client_list($firm_id)
    {
        /*$this->data['filing_data'] = $this->master_model->get_all_filing_data($user_id);
        return $this->data;*/

        $this->db->select('client.*');
        $this->db->from('client');
        //$this->db->where('client.firm_id', $firm_id);
        $this->db->where('client.deleted = 0');
        $this->db->order_by('client.id', 'desc');
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

                $query_firm = $this->db->query("select firm.name from firm where id = '".$client_info[$i]["firm_id"]."'");

                $firm_info = $query_firm->result_array();

                if ($query_firm->num_rows() > 0) {
                    $client_info[$i] = array_merge($client_info[$i], $firm_info[0]);
                }
            }

            foreach (($client_info) as $row) {
                $data[] = $row;
            }

            $this->data['client_list'] = $data;
            return $this->data;
        }
    }

    public function search_due_date($firm_id)
    {
        /*$this->data['filing_data'] = $this->master_model->get_all_filing_data($user_id);
        return $this->data;*/

        $this->db->select('client.*');
        $this->db->from('client');
        $this->db->where('client.firm_id', $firm_id);
        $this->db->where('client.deleted = 0');
        $this->db->order_by('client.id', 'desc');
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

                if($client_info[$i]["175_extended_to"] != "0")
                {
                    $due_date_175 = strtotime($client_info[$i]["175_extended_to"]);
                }
                else if($client_info[$i]["due_date_175"] != "Not Applicable")
                {
                    $due_date_175 = strtotime($client_info[$i]["due_date_175"]);
                }
                else if($client_info[$i]["due_date_175"] == "Not Applicable")
                {
                    $due_date_175 = $client_info[$i]["due_date_175"];
                }

                if($client_info[$i]["201_extended_to"] != "0")
                {
                    $due_date_201 = strtotime($client_info[$i]["201_extended_to"]);
                }
                else
                {
                    $due_date_201 = strtotime($client_info[$i]["due_date_201"]);
                }

                if($due_date_175 == "Not Applicable")
                {
                    $now = time(); // or your date as well
                    $your_date = $due_date_201;
                    $datediff = $your_date - $now;

                    $client_info[$i]["days"] = round($datediff / (60 * 60 * 24));
                }
                else if($due_date_175 > $due_date_201)
                {
                    $now = time(); // or your date as well
                    $your_date = $due_date_201;
                    $datediff = $your_date - $now;

                    $client_info[$i]["days"] = round($datediff / (60 * 60 * 24));
                }
                else if($due_date_201 > $due_date_175)
                {
                    $now = time(); // or your date as well
                    $your_date = $due_date_175;
                    $datediff = $your_date - $now;

                    $client_info[$i]["days"] = round($datediff / (60 * 60 * 24));
                }
                else if($due_date_175 == $due_date_201 && $due_date_175 != '' && $due_date_201 != '')
                {
                    $now = time(); // or your date as well
                    $your_date = $due_date_201;
                    $datediff = $your_date - $now;

                    $client_info[$i]["days"] = round($datediff / (60 * 60 * 24));
                }
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

        /*$q = $this->db->query("select billing.*, client.company_name from billing left join client on client.company_code = billing.company_code where outstanding > 0");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }*/
        
        $this->db->select('billing.*, client.company_name, client.registration_no, client.former_name, currency.currency as currency_name');
        $this->db->from('billing');
        $this->db->join('client', 'client.company_code = billing.company_code', 'left');
        $this->db->join('currency', 'billing.currency_id = currency.id', 'left');
        if ($start != NULL)
        {
            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
            if ($end != NULL)
            {

                $this->db->where('STR_TO_DATE(invoice_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
            }
            else
            {
                $this->db->where('STR_TO_DATE(invoice_date,"%d/%m/%Y") >= STR_TO_DATE("'. $start. '","%d/%m/%Y")');
            }
        }
        $this->db->order_by('id', 'desc');
        $this->db->where('billing.firm_id', $selected_firm_id);
        $this->db->where('billing.status', "0");
        //$this->db->where('outstanding !=', 0);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            $this->data['list_of_invoice'] = $data;
            return $this->data;
        }
        
    }


}