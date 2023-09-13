<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fs_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'encryption'));

        $this->load->model('fs_notes_model');
    }

    public function getFS_list()
    {
        $url         = 'assets/json/fs.json'; // path to your JSON file
        $data        = file_get_contents($url); // put the contents of the file into a variable
        $data_decode = json_decode($data); // decode the JSON feed

        return $data_decode[0];
    }

    public function get_accountant_compilation_report()
    {
        $url         = 'assets/json/accountants_compilation_report.json'; // path to your JSON file
        $data        = file_get_contents($url); // put the contents of the file into a variable

        return $data;
    }

    public function get_fs_doc_checklist()
    {
        $url         = 'assets/json/fs_doc_checklist.json'; // path to your JSON file
        $data        = file_get_contents($url); // put the contents of the file into a variable
        $data_decode = json_decode($data); // decode the JSON feed

        return (array)$data_decode[0];
    }

    public function decrypt_client_info($client_info, $keyword)
    {
        foreach (($client_info) as $row) 
        {
            $row["registration_no"] = $this->encryption->decrypt($row["registration_no"]);
            $row["company_name"] = $this->encryption->decrypt($row["company_name"]);
            if($keyword != null)
            {
                if(stripos($row["registration_no"], $keyword) !== FALSE)
                {
                    $data[] = $row;
                }
                else if(stripos($row["company_name"], $keyword) !== FALSE)
                {
                    $data[] = $row;
                }
            }
            else
            {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function is_small_FRS_not_audited($fs_company_info_id)
    {
        $fs_company_info = $this->get_fs_company_info($fs_company_info_id);
        $is_small_frs_not_audited = false;

        if($fs_company_info[0]['accounting_standard_used'] == 4)
        {
            if(!$fs_company_info[0]['is_audited'])
            {
                $is_small_frs_not_audited = true;
            }
        }

        return $is_small_frs_not_audited;
    }

    public function get_group_type()
    {  // get dropdown value list for opinion
        $list = $this->getFS_list();

        $group_type_list = array();

        foreach($list->group_type as $item){
            $group_type_list[$item->value] = $item->name; 
        }

        return $group_type_list;
    }

    public function get_accounting_standard_list()
    {  
        // // get dropdown value list for opinion
        // $list = $this->getFS_list();

        // $new_list = array();
        // // $new_list[""] = "-- Select a standard --";

        // foreach($list->accounting_standard_list as $item)
        // {
        //     $new_list[$item->value] = $item->name; 
        // }

        // return $new_list;

        $list = $this->db->query("SELECT * FROM fs_accounting_standard");
        $list = $list->result_array();

        $new_list = array();
        $new_list[""] = "-- Select a standard --";

        foreach ($list as $key => $item) 
        {
            $new_list[$item['id']] = $item['name']; 
        }

        return $new_list;
    }

    public function get_json_act_applicable_list()
    {  // get dropdown value list for opinion
        $list = $this->getFS_list();

        $new_list = array();
        // $new_list[""] = "-- Select a type --";

        foreach($list->act_applicable_list as $item)
        {
            $new_list[$item->value] = $item->name; 
        }

        return $new_list;
    }

    // public function get_key_audit_matter(){  // get dropdown value list for key audit matter
    //     $list = $this->getFS_list();

    //     $key_audit_matter_list = array();

    //     foreach($list->key_audit_matter as $item){
    //         $key_audit_matter[$item->value] = $item->name; 
    //     }

    //     return $key_audit_matter;
    // }

    public function get_country_list()
    {
        $this->db->select('*');
        $this->db->from('fs_country');

        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            return $q->result();
        }
        else
        {
            return '';
        }
    }

    public function get_dp_country_list()
    {
        $list = $this->get_country_list();

        $dp_list = array();

        $dp_list[''] = "-- Select a country --";

        foreach ($list as $key => $value) 
        {
            $dp_list[$value->id] = $value->nicename;
        }

        return $dp_list;
    }

    public function get_currency_list()
    {
        $this->db->select('*');
        $this->db->from('currency');

        $q = $this->db->query("SELECT * FROM currency ORDER BY id");

        if ($q->num_rows() > 0) {
            $q = $q->result();

            $currency_list = array();

            $currency_list[""] = "-- Select a currency --";

            foreach($q as $item){
                $currency_list[$item->id] = $item->currency; 
            }

            return $currency_list;
        }
        else
        {
            return '';
        }
    }

    public function get_final_document_type($fs_company_info_id)
    {
        $fs_company_info = $this->get_fs_company_info($fs_company_info_id);
        $final_doc_type = 0;

        if($fs_company_info[0]['accounting_standard_used'] != 4)
        {
            $final_doc_type = 1;
        }
        else
        {
            if($fs_company_info[0]['is_audited'])
            {
                $final_doc_type = 2;
            }
            else
            {
                $final_doc_type = 3;
            }
        }

        return $final_doc_type;
    }

    public function get_currency_info($currency_id)
    {
        $q = $this->db->query("SELECT currency.*, country.nicename AS `country_name` FROM currency LEFT JOIN fs_country country ON country.id = currency.country_id WHERE currency.id=" . $currency_id);
        $q = $q->result_array();

        return $q;
    }

    public function get_directors($director_id)
    {
        $this->db->select('*');
        $this->db->from('officer');
        $this->db->where('id', $director_id);
        
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            return $q->result();
        }
        else
        {
            return '';
        }
    }

    public function get_company_list()  // get dropdown value lsit for company list.
    {
        $company_list = $this->db->query("SELECT * FROM client");
        $company_list = $company_list->result();

        $company_list_dp = array();

        $company_list_dp[''] = '- Select a firm - ';

        foreach($company_list as $item){
            $company_list_dp[$item->company_code] = $item->company_name; 
        }

        return $company_list_dp;
    }

    public function get_fs_report_list($firm_id)
    {
        $q = $this->db->query("SELECT client.company_name, fs_company_info.* FROM fs_company_info
                               LEFT JOIN client ON client.company_code = fs_company_info.company_code 
                               WHERE fs_company_info.firm_id=" . $firm_id);

        return $q->result_array();
    }

    public function get_fs_report_details($id)
    {
        if($id != "")
        {
            $this->db->select('*');
            $this->db->from('fs_company_info');
            $this->db->where('id', $id);

            $q = $this->db->get();

            if ($q->num_rows() > 0) {
                return $q->result();
            }
            else
            {
                return '';
            }
        }
    }

    public function get_new_FYE_date($company_code)
    {
        $q = $this->db->query("SELECT * FROM fs_company_info WHERE company_code='" . $company_code . "' ORDER BY created_at");
        $q = $q->result_array();

        $mostRecent     = '';
        $selected_index = '';

        foreach ($q as $key => $value) 
        {
            if(!empty($value['current_fye_end']))
            {
                if(empty($mostRecent))
                {
                    $selected_index = $key;
                    $mostRecent = strtotime($value['current_fye_end']);
                }

                $curDate = strtotime($value['current_fye_end']);

                if ($curDate > $mostRecent) 
                {
                    $mostRecent = $curDate;
                    $selected_index = $key;
                }
            }
        }

        if(count($q) > 0)
        {
            $data = $q[$selected_index];

            // print_r($data);

            // $data['id']                 = 0;
            $data['last_fye_begin']     = $data['current_fye_begin'];
            $data['last_fye_end']       = $data['current_fye_end'];

            // total difference of 2 dates


            $data['current_fye_begin']  = date("d F Y", strtotime(date("Y-m-d", strtotime($data['current_fye_begin'])) . " + 1 year"));
            $data['current_fye_end']    = date("d F Y", strtotime(date("Y-m-d", strtotime($data['current_fye_end'])) . " + 1 year"));
            $data['report_date']        = '';
            $data['first_set']          = 0;
            $data['director_signature_1'] = ''; // retrieve from secretary
            $data['director_signature_2'] = ''; // retrieve from secretary
            $data['accounting_standard_used'] = 1;
        }
        else
        {
            $data = [];
        }

        return $data;
    }

    public function get_firm_info_by_firmid($firm_id)
    {
        $q = $this->db->query("SELECT * FROM firm WHERE id='" . $firm_id . "'");
        $q = $q->result_array();

        return $q;
    }

	public function get_client_info($company_code)
    {
        if($company_code != "")
        {
            $this->db->select('*');
            $this->db->from('client');
            $this->db->where('company_code', $company_code);

            $q = $this->db->get();

            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
            }
        }

        if(count($data) > 0)
        {
            return json_encode($data);
        }
        else
        {
            return FALSE;
        }
    }

    public function get_client_info_by_companycode($company_code)
    {
        $q = $this->db->query("SELECT * FROM client WHERE company_code='" . $company_code . "'");
        $q = $q->result_array();

        return $q;
    }

    public function get_this_independent_aud_report($fs_company_info_id)
    {
        $q = $this->db->query("SELECT fs_independent_audit_report.*, fs_opinion_type.name AS `opinion_name` FROM fs_independent_audit_report 
                                LEFT JOIN fs_opinion_type ON fs_opinion_type.id = fs_independent_audit_report.fs_opinion_type_id
                                WHERE fs_company_info_id='" . $fs_company_info_id . "'");
        $q = $q->result_array();

        return $q;
    }

    public function get_fs_company_info($id)
    {
        $q = $this->db->query("SELECT fs_company_info.*, client.company_name, fs_act_applicable_type.name AS `act_applicable_type_name`, fs_accounting_standard.name AS `accounting_standard_used_name`, director_signature_1 AS `director_signature_id_1`, director_signature_2 AS `director_signature_id_2`
                                FROM fs_company_info 
                                LEFT JOIN client ON client.company_code = fs_company_info.company_code
                                LEFT JOIN fs_accounting_standard ON fs_accounting_standard.id = fs_company_info.accounting_standard_used
                                LEFT JOIN fs_act_applicable_type ON fs_act_applicable_type.id = fs_company_info.act_applicable_type
                                WHERE fs_company_info.id='" . $id . "'");
        $q = $q->result_array();

        // update director signature 1 & director signature 1 from id to name
        if($q[0]['director_signature_1'] != 0)
        {
            $director_signature_1 = $this->db->query("SELECT * FROM client_officers co 
                                                    LEFT JOIN officer o ON o.id = co.officer_id
                                                    WHERE co.id =" . $q[0]['director_signature_1']);
            $director_signature_1 = $director_signature_1->result_array();
            $director_signature_1 = $director_signature_1[0]['name'];
        }
        else
        {
            $director_signature_1 = '';
        }

        if($q[0]['director_signature_2'] != 0)
        {
            $director_signature_2 = $this->db->query("SELECT * FROM client_officers co 
                                                    LEFT JOIN officer o ON o.id = co.officer_id
                                                    WHERE co.id =" . $q[0]['director_signature_2']);
            $director_signature_2 = $director_signature_2->result_array();
            $director_signature_2 = $director_signature_2[0]['name'];
        }
        else
        {
            $director_signature_2 = '';
        }

        $q[0]['director_signature_1'] = $director_signature_1;
        $q[0]['director_signature_2'] = $director_signature_2;

        return $q;
    }

    public function get_fs_company_info_by_company_code($company_code)
    {
        $q = $this->db->query("SELECT fs_company_info.*, client.company_name, fs_act_applicable_type.name AS `act_applicable_type_name`, fs_accounting_standard.name AS `accounting_standard_used_name`
                                FROM fs_company_info 
                                LEFT JOIN client ON client.company_code = fs_company_info.company_code
                                LEFT JOIN fs_accounting_standard ON fs_accounting_standard.id = fs_company_info.accounting_standard_used
                                LEFT JOIN fs_act_applicable_type ON fs_act_applicable_type.id = fs_company_info.act_applicable_type
                                WHERE fs_company_info.company_code='" . $company_code . "'");
        $q = $q->result_array();

        return $q;
    }

    public function get_fs_company_info_last_year($fs_company_info_id)
    {
        $fs_company_info         = $this->get_fs_company_info($fs_company_info_id);
        $fs_company_info_this_ye = $this->get_fs_company_info($fs_company_info_id);

        $fs_company_info_last_ye = $this->db->query("SELECT * FROM fs_company_info WHERE company_code='" . $fs_company_info_this_ye[0]['company_code'] . "' AND id <> '" . $fs_company_info_id . "' AND firm_id=" . $fs_company_info[0]['firm_id']);
        $fs_company_info_last_ye = $fs_company_info_last_ye->result_array();

        $last_ye_fs_company_info_id = 0;

        if(count($fs_company_info_last_ye) > 0)
        {
            $temp_target_ye = $fs_company_info_this_ye[0]['current_fye_end'];
            $shortest_day = 1000000; // set initial for more days to prevent taking wrong date.

            foreach ($fs_company_info_last_ye as $key => $value)
            {
                $startTimeStamp = new DateTime(date('Y-m-d', strtotime($value['current_fye_end'])));
                $startTimeStamp  = $startTimeStamp->format('Y-m-d');
                $startTimeStamp = date_create($startTimeStamp);

                $endTimeStamp = new DateTime(date('Y-m-d', strtotime($temp_target_ye)));
                $endTimeStamp  = $endTimeStamp->format('Y-m-d');
                $endTimeStamp = date_create($endTimeStamp);

                if($startTimeStamp < $endTimeStamp)
                {
                    $temp_shortest_day = $this->fs_model->compare_date_latest($temp_target_ye, $value['current_fye_end'], $shortest_day);
                
                    if((int)$shortest_day > 0)
                    {
                        if((int)$shortest_day > (int)$temp_shortest_day)
                        {
                            $last_ye_fs_company_info_id = $value['id'];
                            $shortest_day = $temp_shortest_day;
                        }
                    }
                }
            }
        }

        return $last_ye_fs_company_info_id;
    }

    public function get_fs_company_info_next_year($fs_company_info_id)
    {
        $fs_company_info_this_ye = $this->get_fs_company_info($fs_company_info_id);

        $fs_company_info_other_ye = $this->db->query("SELECT * FROM fs_company_info WHERE company_code='" . $fs_company_info_this_ye[0]['company_code'] . "' AND id <> '" . $fs_company_info_id . "' AND firm_id=" . $fs_company_info_this_ye[0]['firm_id']);
        $fs_company_info_other_ye = $fs_company_info_other_ye->result_array();

        $next_ye_fs_company_info_id = 0;

        if(count($fs_company_info_other_ye) > 0)
        {
            $temp_target_ye = $fs_company_info_this_ye[0]['current_fye_end'];
            $shortest_day = 367; // set initial for more days to prevent taking wrong date.

            foreach ($fs_company_info_other_ye as $key => $value)
            {
                $startTimeStamp = new DateTime(date('Y-m-d', strtotime($value['current_fye_end'])));
                $startTimeStamp  = $startTimeStamp->format('Y-m-d');
                $startTimeStamp = date_create($startTimeStamp);

                $endTimeStamp = new DateTime(date('Y-m-d', strtotime($temp_target_ye)));
                $endTimeStamp  = $endTimeStamp->format('Y-m-d');
                $endTimeStamp = date_create($endTimeStamp);

                if($startTimeStamp > $endTimeStamp) // if next year date is latest than this year end
                {
                    $temp_shortest_day = $this->fs_model->compare_date_latest($value['current_fye_end'], $temp_target_ye, $shortest_day);

                    if((int)$temp_shortest_day > 0)
                    {
                        if((int)$shortest_day > (int)$temp_shortest_day)
                        {
                            $next_ye_fs_company_info_id = $value['id'];
                            $shortest_day = $temp_shortest_day;
                        }
                    }
                }
            }
        }

        return $next_ye_fs_company_info_id;
    }

    public function get_fs_fp_currency_details($id)
    {
        $q = $this->db->query("SELECT * FROM fs_fp_currency WHERE fs_company_info_id=" . $id);
        $q = $q->result_array();

        return $q;
    }

    public function get_fs_doc_template_master($fs_document_master_id, $section)
    {
        $q = $this->db->query("SELECT fs_doc_template_master.*, fs_opinion_type.name FROM fs_doc_template_master 
                            LEFT JOIN fs_opinion_type ON fs_opinion_type.id = fs_doc_template_master.fs_opinion_type_id
                            WHERE fs_document_master_id = ". $fs_document_master_id ." AND section = '" . $section . "' ORDER BY order_by");

        if ($q->num_rows() > 0) 
        {
            return $q->result();
        }
        else
        {
            return '';
        }
    }

    // public function get_fs_appt_directors_by_company_code($company_code, $report_date)
    // {
    //     $get_directors = $this->db->query("SELECT officer.id, officer.name, client_officers.date_of_appointment FROM client_officers 
    //                                     LEFT JOIN officer on client_officers.officer_id = officer.id AND client_officers.field_type = officer.field_type
    //                                     WHERE client_officers.company_code='".$company_code ."' AND (client_officers.date_of_cessation > STR_TO_DATE('". date('d/m/Y', strtotime($report_date)) ."', '%d/%m/%Y') OR client_officers.date_of_cessation = '') AND client_officers.position = 1");

    //     $get_directors = $get_directors->result_array();

    //     return $get_directors;
    // }

    public function get_fs_appt_directors($fs_company_info_id)
    {
        $fs_company_info = $this->get_fs_company_info($fs_company_info_id);
        $get_directors = $this->db->query("SELECT officer.id, officer.name, client_officers.date_of_appointment 
                                            FROM client_officers 
                                            LEFT JOIN officer on client_officers.officer_id = officer.id AND client_officers.field_type = officer.field_type
                                            WHERE client_officers.company_code='".$fs_company_info[0]['company_code'] ."' AND (client_officers.date_of_cessation > STR_TO_DATE('". date('d/m/Y', strtotime($fs_company_info[0]["report_date"])) ."', '%d/%m/%Y') OR client_officers.date_of_cessation = '') AND client_officers.position = 1");

        $get_directors = $get_directors->result_array();

        $fs_directors = array();

        // echo date('Y-m-d', strtotime($fs_company_info[0]["report_date"]));
        // echo json_encode($get_directors);

        foreach($get_directors as $row)
        {
            // return json_encode(date('Y-m-d', strtotime($fs_company_info[0]['current_fye_begin'])));
            if(date('Y-d-m', strtotime($row['date_of_appointment'])) <= date('Y-m-d', strtotime($fs_company_info[0]['current_fye_begin'])))
            {
                array_push($fs_directors, array('id' => $row['id'], 
                                                'name' => $row['name'], 
                                                'date_of_appointment' => $row['date_of_appointment'],
                                                'show_appt_date' => 0
                                            ));
            }
            else
            {
                array_push($fs_directors, array('id' => $row['id'], 
                                                'name' => $row['name'], 
                                                'date_of_appointment' => $row['date_of_appointment'],
                                                'show_appt_date' => 1
                                            ));
            }
        }
        // echo json_encode($fs_directors);

        return $fs_directors;
    }

    public function get_shares($officer_id, $fs_company_info)
    {
        $last_FYE = new DateTime($fs_company_info[0]['last_fye_end']);
        $last_FYE  = $last_FYE->format('d/m/Y');

        $current_FYE = new DateTime($fs_company_info[0]['current_fye_end']);
        $current_FYE  = $current_FYE->format('d/m/Y');

        // $last_FYE = new DateTime($date_of_fye);
        // $last_FYE->modify('-1 year');
        // $last_FYE  = $last_FYE->format('d/m/Y');

        $q = $this->db->query('SELECT officer.name,
                                SUM(IF(STR_TO_DATE(member_shares.transaction_date, "%d/%m/%Y") <= STR_TO_DATE("'. $last_FYE .'", "%d/%m/%Y"), member_shares.amount_share, 0)) AS begin_FY,
                                SUM(IF(STR_TO_DATE(member_shares.transaction_date, "%d/%m/%Y") <= STR_TO_DATE("'. $current_FYE .'", "%d/%m/%Y"), member_shares.amount_share, 0)) AS end_FY
                                FROM member_shares
                                LEFT JOIN officer ON officer.id = member_shares.officer_id
                                WHERE member_shares.officer_id='. $officer_id .' AND member_shares.field_type = "individual"');

        return $q->result_array();
    }

    public function get_fs_dir_state_company($fs_company_info_id)
    {
        $q = $this->db->query("SELECT fs_dir_statement_company.*, fs_company_type.id AS fs_company_type_id, fs_company_type.company_type FROM fs_dir_statement_company LEFT JOIN fs_company_type ON fs_company_type.id = fs_dir_statement_company.fs_company_type_id WHERE fs_company_info_id =" . $fs_company_info_id);

        return $q->result_array();
    }

    public function get_fs_dir_statement_director($fs_company_info_id)
    {
        $q = $this->db->query("SELECT fs_dir_statement_director.* FROM fs_dir_statement_company LEFT JOIN fs_dir_statement_director ON fs_dir_statement_director.fs_dir_statement_company_id = fs_dir_statement_company.id WHERE fs_dir_statement_company.fs_company_info_id=" . $fs_company_info_id);
        return $q->result_array();
    }

    public function getClient($group_id=NULL, $tipe = NULL, $keyword = NULL, $service_category = NULL)
    {
        $firm_id = $this->session->userdata('firm_id');

        // $this->db->select('DISTINCT (client.company_name), client.*, client_contact_info.name, client_contact_info_phone.phone, client_contact_info_email.email');
        $this->db->select('DISTINCT (client.company_name), client.*');
        $this->db->from('client');

        if ($tipe != NULL)
        {
            if ($tipe != 'All')
            {
                $this->db->like($tipe, $keyword);
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('client_code', $keyword);
                    $this->db->or_like('registration_no', $keyword);
                    $this->db->or_like('incorporation_date', $keyword);
                    $this->db->or_like('company_name', $keyword);
                    $this->db->or_like('postal_code', $keyword);
                    $this->db->or_like('street_name', $keyword);
                    // $this->db->or_like('building_name', $keyword);
                    // $this->db->or_like('unit_no1', $keyword);
                    // $this->db->or_like('unit_no2', $keyword);
                    // $this->db->or_like('activity1', $keyword);
                    // $this->db->or_like('activity2', $keyword);
                    $this->db->or_like('former_name', $keyword);
                $this->db->group_end();
            }
        }
        // $this->db->join('client_contact_info', 'client_contact_info.company_code = client.company_code', 'left');
        // $this->db->join('client_contact_info_email', 'client_contact_info_email.client_contact_info_id = client_contact_info.id AND client_contact_info_email.primary_email = 1', 'left');
        // $this->db->join('client_contact_info_phone', 'client_contact_info_phone.client_contact_info_id = client_contact_info.id AND client_contact_info_phone.primary_phone = 1', 'left');
       // $this->db->join('billing', 'billing.company_code = client.company_code', 'right');
        //$this->db->where('client.user_id', $this->session->userdata('user_id'));
        if($group_id == 4)
        {
            $this->db->join('user_client', 'user_client.client_id = client.id AND user_client.user_id = '.$this->session->userdata('user_id'), 'right');
        }

        // if($service_category != "0")
        // {
        //     $this->db->join('client_billing_info', 'client_billing_info.company_code = client.company_code', 'right');
        //     $this->db->join('our_service_info', 'our_service_info.id = client_billing_info.service AND our_service_info.service_type = "'.$service_category.'"', 'right');
        // }
        //echo json_encode($this->session->userdata('user_id'));
        //$this->db->where('client.firm_id', $this->session->userdata('firm_id'));
        // $this->db->join('user_firm', 'user_firm.firm_id = client.firm_id', 'left');
        // $this->db->where('user_firm.user_id = '.$this->session->userdata('user_id'));
        $this->db->where('client.deleted', 0);
        $this->db->order_by('client.id', 'desc');
        $q = $this->db->get();

        // $this->db->select('firm.*')
        //         ->from('firm')
        //         ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
        //         ->where('user_firm.user_id = '.$this->session->userdata('user_id'));

        $client_info = $q->result_array();

        // print_r($client_info[0]);

        if ($q->num_rows() > 0) 
        {
            for($i = 0; $i < count($client_info); $i++)
            {   
                // print_r($client_info);
                // $query = $this->db->query("select current_fye_begin, last_fye_end from fs_company_info where firm_id ='".$client_info[$i]["firm_id"] . "' AND company_code='" . $client_info[$i]["company_code"] . "'");
                $query = $this->db->query("select id AS `fs_company_info_id`, current_fye_end, last_fye_end, fs_list_report_status_id from fs_company_info where company_code='" . $client_info[$i]["company_code"] . "' AND firm_id = " . $firm_id . " ORDER BY created_at DESC");
                // or agm = 'dispensed'
                $fs_company_info = $query->result_array();

                // print_r(array('firm_id' => $client_info[$i]["firm_id"], 'company_code' => $client_info[$i]["company_code"]));

                if ($query->num_rows() > 0) 
                {   
                    // foreach ($fs_company_info as $key => $value) 
                    // {
                    //     if(!empty($value['current_fye_end']))
                    //     {
                            
                    //         if(empty($mostRecent))
                    //         {
                    //             $selected_index = $key;
                    //             $mostRecent = strtotime($value['current_fye_end']);
                    //         }

                    //         $curDate = strtotime($value['current_fye_end']);

                    //         if ($curDate > $mostRecent) 
                    //         {
                    //             $mostRecent = $curDate;
                    //             $selected_index = $key;
                    //         }
                    //     }
                    // }

                    $client_info[$i] = array_merge($client_info[$i], $fs_company_info[0]);
                    // print_r($fs_company_info[0]);
                }
                else
                {
                    $client_info[$i] = array_merge($client_info[$i], array('fs_company_info_id' => 0, 'current_fye_end' => '-', 'last_fye_end' => '-'));
                }

                // $query_pending_documents = $this->db->query("select COUNT(*) as num_document from pending_documents where received_on = '' AND client_id ='".$client_info[$i]["id"]."'");

                // $pending_documents_info = $query_pending_documents->result_array();

                // if ($query_pending_documents->num_rows() > 0) {
                //     $client_info[$i] = array_merge($client_info[$i], $pending_documents_info[0]);
                // }

                // $query_unpaid = $this->db->query("select sum(outstanding) as outstanding from billing where company_code = '".$client_info[$i]["company_code"]."' AND status != 1");

                // $unpaid_info = $query_unpaid->result_array();

                // if ($query_unpaid->num_rows() > 0) {
                //     $client_info[$i] = array_merge($client_info[$i], $unpaid_info[0]);
                // }
            }

            foreach (($client_info) as $row) 
            {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function get_all_client_signing_info($company_code)
    {   
        if ($company_code)
        {
            $q = $this->db->query('select * from client_signing_info where company_code ="'.$company_code.'"');

            if ($q->num_rows() > 0) 
            {
                $this->session->set_userdata('chairman', $q->result()[0]->chairman);
                $this->session->set_userdata('director_signature_1', $q->result()[0]->director_signature_1);
                $this->session->set_userdata('director_signature_2', $q->result()[0]->director_signature_2);

                foreach (($q->result()) as $row) 
                {
                    $data[] = $row;
                }
                
                return $data;
            }
        }
        return false;
    }

    public function show_investment_in_associate_and_join_ventures($fs_company_info_id)
    {
        $show_content = false;

        $q = $this->db->query("SELECT * FROM audit_categorized_account WHERE fs_company_info_id =" . $fs_company_info_id);

        $retreived_data = $q->result_array();

        // check if there is any value in tree
        if(count($retreived_data) > 0)
        {
            foreach ($retreived_data as $key => $value) 
            {
               if(!empty($value['value']) || !empty($value['company_end_prev_ye_value']) || !empty($value['company_beg_prev_ye_value']) || 
                !empty($value['group_end_this_ye_value']) || !empty($value['group_end_prev_ye_value']) || !empty($value['group_beg_prev_ye_value']))
                {
                    $show_content = true;

                    break;
                }
            }

            if($show_content)
            {
                $q2 = $this->db->query('SELECT fnd_main.* 
                                        FROM fs_note_details fnd_main 
                                        INNER JOIN 
                                            (SELECT fnd.fs_categorized_account_round_off_id, max(fnd.created_at) as `MaxDate` 
                                             FROM audit_categorized_account fca 
                                             JOIN fs_note_details fnd 
                                             WHERE fca.fs_company_info_id = ' . $fs_company_info_id . ' 
                                             GROUP BY fnd.fs_categorized_account_round_off_id) fnd_max_date 
                                        ON fnd_main.fs_categorized_account_round_off_id = fnd_max_date.fs_categorized_account_round_off_id 
                                        AND fnd_main.created_at = fnd_max_date.MaxDate 
                                        AND fnd_main.in_use = 1 
                                        AND fnd_main.fs_company_info_id = ' . $fs_company_info_id . ' 
                                        GROUP BY fnd_main.fs_categorized_account_round_off_id
                                        ORDER BY fnd_main.id');

                $note_data = $q2->result_array();

                if(count($note_data) > 0)
                {
                    $show_content = true;
                }
            }
        }

        return $show_content;
    }

    public function get_fs_settings($fs_company_info_id)
    {
        $q = $this->db->query("SELECT * FROM fs_settings WHERE fs_company_info_id = " . $fs_company_info_id);
        $q = $q->result_array();

        if(count($q) > 0)
        {
            return $q;
        }
        else
        {
            $data = array(
                        'info' => array('fs_company_info_id' => $fs_company_info_id)
                    );

            $this->fs_notes_model->insert_tbl_data('fs_settings', array($data));

            $q = $this->db->query("SELECT * FROM fs_settings WHERE fs_company_info_id = " . $fs_company_info_id);
            $q = $q->result_array();

            return $q;
        }
    }

    public function get_fs_signing_report($fs_company_info_id)
    {
        $q = $this->db->query("SELECT * FROM fs_signing_report WHERE fs_company_info_id=" . $fs_company_info_id);
        $q = $q->result_array();

        return $q;
    }

    public function calculate_difference_dates($fs_company_info_id, $statement_type)
    {
        $fs_company_info = $this->get_fs_company_info($fs_company_info_id);

        if($statement_type == "FP")
        {
            $current_fye_begin = $fs_company_info[0]["last_fye_end"];
            $current_fye_end   = $fs_company_info[0]["current_fye_end"];

            $start_date = new DateTime(date('Y-m-d', strtotime($current_fye_begin)));    // current_fye_begin
            // $start_date->modify('-1 day');
            $start_date  = $start_date->format('Y-m-d');
        }
        elseif($statement_type == "General")
        {
            $current_fye_begin = $fs_company_info[0]["current_fye_begin"];
            $current_fye_end   = $fs_company_info[0]["current_fye_end"];

            $start_date = new DateTime(date('Y-m-d', strtotime($current_fye_begin)));    // current_fye_begin
            $start_date->modify('-1 day');
            $start_date  = $start_date->format('Y-m-d');
        }

        // for calculate date (Statement of Financial Position) | calculate differences between this year end (beg) and this year end (end) (Statement of Comprhensive Income)
        $start_date = date_create($start_date);
        $end_date = date_create($current_fye_end);   // current_fye_end

        $interval = date_diff($start_date, $end_date);
        $interval_value_year = $interval->format('%y');
        $interval_value_month = $interval->format('%m');
        $interval_value_day = $interval->format('%d');

        if($statement_type != "FP")
        {
            // calculate differences between last year end (beg) and last year end (end) (Statement of Comprhensive Income)
            $ly_start_date = new DateTime(date('Y-m-d', strtotime($fs_company_info[0]['last_fye_begin'])));    // current_fye_begin
            $ly_start_date->modify('-1 day');
            $ly_start_date  = $ly_start_date->format('Y-m-d');

            $ly_start_date = date_create($ly_start_date);
            $ly_end_date = date_create($fs_company_info[0]['last_fye_end']);

            $ly_interval = date_diff($ly_start_date, $ly_end_date);
            $ly_interval_value_year = $ly_interval->format('%y');
            $ly_interval_value_month = $ly_interval->format('%m');
            $ly_interval_value_day = $ly_interval->format('%d');
        }

        $data = [];

        if($fs_company_info[0]['first_set'])
        {
            $data["last_fye_end"]    = '';  // no last year

            if($statement_type == "FP")
            {
                $data["current_fye_end"] = date('Y', strtotime($current_fye_end));
            }
            elseif($statement_type == "General")
            {
                if($interval_value_year == 1 && $interval_value_month == 0 && $interval_value_day == 0) // if this year differences is actual 1 year, show year
                {
                    $data["current_fye_end"] = date('Y', strtotime($fs_company_info[0]['current_fye_end']));
                }
                else
                {
                    $data["current_fye_end"] = date('d.m.Y', strtotime($fs_company_info[0]['current_fye_begin'])) . "<br />to<br />" . date('d.m.Y', strtotime($fs_company_info[0]['current_fye_end']));
                }
            }
        }
        else
        {
            /* Current year part */
            if($interval_value_year == 1 && $interval_value_month == 0 && $interval_value_day == 0) // if this year differences is actual 1 year, show year
            {
                if($statement_type == "FP")
                {
                    $data["last_fye_end"]    = date('Y', strtotime($current_fye_begin));
                    $data["current_fye_end"] = date('Y', strtotime($current_fye_end));
                }
                else
                {
                    $data["current_fye_end"] = date('Y', strtotime($fs_company_info[0]['current_fye_end']));
                }
            }
            else
            {
                if($statement_type == "FP")
                {
                    $data["last_fye_end"]    = date('d.m.Y', strtotime($current_fye_begin));
                    $data["current_fye_end"] = date('d.m.Y', strtotime($current_fye_end));
                }
                else
                {
                    $data["current_fye_end"] = date('d.m.Y', strtotime($fs_company_info[0]['current_fye_begin'])) . "<br />to<br />" . date('d.m.Y', strtotime($fs_company_info[0]['current_fye_end']));
                }
            }
            /* END OF Current year part */


            /* Last year part (Statement of Comprehensive Income) */
            if($statement_type != "FP")
            {
                if($ly_interval_value_year == 1 && $ly_interval_value_month == 0 && $ly_interval_value_day == 0) // if this year differences is actual 1 year, show year
                {
                    $data["last_fye_end"] = date('Y', strtotime($fs_company_info[0]['last_fye_end']));
                }
                else
                {
                    $data["last_fye_end"] = date('d.m.Y', strtotime($fs_company_info[0]['last_fye_begin'])) . "<br />to<br />" . date('d.m.Y', strtotime($fs_company_info[0]['last_fye_end']));
                }
            }
            /* END OF Last year part */
        }

        // print_r($data);

        return $data;
        // return array('year' => $interval_value_year, 'month' => $interval_value_month, 'day' => $interval_value_day);
    }

    public function compare_date_latest($date_1, $date_2, $shortest_day) // used to calculate days to get last year end
    {   
        $startTimeStamp = new DateTime(date('Y-m-d', strtotime($date_1)));
        $startTimeStamp  = $startTimeStamp->format('Y-m-d');
        $startTimeStamp = date_create($startTimeStamp);

        $endTimeStamp = new DateTime(date('Y-m-d', strtotime($date_2)));
        $endTimeStamp  = $endTimeStamp->format('Y-m-d');
        $endTimeStamp = date_create($endTimeStamp);

        $interval = date_diff($startTimeStamp, $endTimeStamp);  // compare 2 dates

        return $interval->days;
    }

    public function compare_date_period($date_1, $date_2, $add_day) // used to calculate days to get last year end
    {   
        $startTimeStamp = new DateTime(date('Y-m-d', strtotime($date_1)));
        $startTimeStamp->modify($add_day); // eg. '-1 day'
        $startTimeStamp  = $startTimeStamp->format('Y-m-d');
        $startTimeStamp = date_create($startTimeStamp);

        $endTimeStamp = new DateTime(date('Y-m-d', strtotime($date_2)));
        $endTimeStamp  = $endTimeStamp->format('Y-m-d');
        $endTimeStamp = date_create($endTimeStamp);

        $interval = date_diff($startTimeStamp, $endTimeStamp);  // compare 2 dates

        return $interval;
    }

    /* ADD OR SAVE DATA */
    public function save_fs_company_info($data)
    {
        $fs_company_info_id = $data['id'];

        $q = $this->db->get_where("fs_company_info", array("id" => $fs_company_info_id));

        if (!$q->num_rows())
        {
            $this->db->insert("fs_company_info",$data);
            $fs_company_info_id = $this->db->insert_id();
        } 
        else 
        {
            $q = $q->result_array();

            $this->db->update("fs_company_info",$data,array("id" => $fs_company_info_id));
        }

        return $fs_company_info_id;
    }

    public function save_fs_fp_currency_info($data)
    {
        $fs_fp_currency_id = $data['id'];

        $q = $this->db->get_where("fs_fp_currency", array("id" => $fs_fp_currency_id));

        if (!$q->num_rows())
        {
            $this->db->insert("fs_fp_currency",$data);
            $fs_fp_currency_id = $this->db->insert_id();
        } 
        else 
        {
            $q = $q->result_array();

            $this->db->update("fs_fp_currency",$data,array("id" => $fs_fp_currency_id));
        }

        return $fs_fp_currency_id;
    }
	
    public function save_fs_independent_audit_report($data)
    {   
        $fs_company_info_id = $data['fs_company_info_id'];

        $q = $this->db->get_where("fs_independent_audit_report", array("fs_company_info_id" => $fs_company_info_id));

        if (!$q->num_rows())
        {
            $this->db->insert("fs_independent_audit_report",$data);
            return array('status' => 'success');
        } 
        else 
        {
            $q = $q->result_array();

            $this->db->update("fs_independent_audit_report", $data, array("fs_company_info_id" => $fs_company_info_id));
            return array('status' => 'success');
        }

        return array('status' => 'failed');
    }

    public function save_bundle_fs_director_statement($data)
    {
        // print_r($data);
        foreach($data as $row)
        {
            // insert or update fs_dir_statement_company
            foreach($row[0] as $fs_director_statement)
            {
                $temp_fs_dir_state_company = 
                    array(
                        'id'                 => $fs_director_statement['id'],
                        'fs_company_info_id' => $fs_director_statement['fs_company_info_id'],
                        'company_name'       => $fs_director_statement['company_name'],
                        'country_id'         => $fs_director_statement['country_id'],
                        'fs_company_type_id' => $fs_director_statement['fs_company_type_id']      
                    );

                if(!empty($temp_fs_dir_state_company['id']))
                {
                    $q = $this->db->get_where("fs_dir_statement_company", array("id" => $temp_fs_dir_state_company['id']));
                    $q = $q->result_array();

                    $this->db->update("fs_dir_statement_company", $temp_fs_dir_state_company, array("id" => $temp_fs_dir_state_company['id']));
                }
                else
                {
                    $id = $this->db->insert("fs_dir_statement_company",$temp_fs_dir_state_company);
                    $temp_fs_dir_state_company['id'] = $this->db->insert_id();
                }

                // insert or update fs_dir_statement_director
                foreach($row[1] as $fs_director)
                {
                    if($fs_director_statement['index'] == $fs_director['company_index'])
                    {
                        $temp_fs_director = 
                            array(
                                'id'                          => $fs_director['id'],
                                'fs_dir_statement_company_id' => $temp_fs_dir_state_company['id'],
                                'director_name'               => $fs_director['director_name'],
                                'dir_begin_fy_no_of_share'    => $fs_director['dir_begin_fy_no_of_share'],
                                'dir_end_fy_no_of_share'      => $fs_director['dir_end_fy_no_of_share'],
                                'deem_begin_fy_no_of_share'   => $fs_director['deem_begin_fy_no_of_share'],
                                'deem_end_fy_no_of_share'     => $fs_director['deem_end_fy_no_of_share']
                            );

                        if(!empty($temp_fs_director['id']))
                        {
                            $q = $this->db->get_where("fs_dir_statement_director", array("id" => $temp_fs_director['id']));
                            $q = $q->result_array();

                            $this->db->update("fs_dir_statement_director", $temp_fs_director, array("id" => $temp_fs_director['id']));
                        }
                        else
                        {
                            $id = $this->db->insert("fs_dir_statement_director",$temp_fs_director);
                            $temp_fs_director['id'] = $this->db->insert_id();
                        }
                    }
                }
            }
        }
    }

    public function save_fs_settings($data)
    {
        $q = $this->db->query("SELECT * FROM fs_settings WHERE fs_company_info_id = " . $data['fs_company_info_id']);
        $q = $q->result_array();

        $result = false;

        if(count($q) > 0)
        {
            $db_result      = $this->db->update("fs_settings", $data, array("id" => $q[0]['id']));
            $fs_settings_id = $q[0]['id'];
        }
        else
        {
            $db_result      = $this->db->insert("fs_settings",$data);
            $fs_settings_id = $this->db->insert_id();
        }

        $result = array(
                    'result'         => $db_result,
                    'fs_settings_id' => $fs_settings_id
                );

        return $result;
    }

    public function save_fs_signing_report($fs_signing_report_id, $data)
    {
        if(!empty($fs_signing_report_id))
        {
            $q = $this->db->query("SELECT * FROM fs_signing_report WHERE id = " . $fs_signing_report_id);
            $q = $q->result_array();

            if(count($q) > 0)
            {
                $result = $this->db->update("fs_signing_report", $data, array("id" => $q[0]['id']));
            }
            else
            {
                $result =  $this->db->insert("fs_signing_report",$data);
            }
            
        }
        else
        {
            $result =  $this->db->insert("fs_signing_report",$data);
        }

        return $result;
    }
    /* END OF ADD OR SAVE DATA */

    /* UPDATE OR EDIT DATA */
    public function update_fs_company_info($fs_company_info_id, $update_data)
    {
        $q = $this->db->get_where(" fs_company_info", array("id" => $fs_company_info_id));
        $q = $q->result_array();

        $result = $this->db->update(" fs_company_info", $update_data, array("id" => $fs_company_info_id));

        return $result;
    }
    /* END OF UPDATE OR EDIT DATA */

    /* DELETE DATA */ 
    public function delete_company_directors($arr_company_id, $arr_director_id)
    {   
        // delete directors
        if(count($arr_director_id) > 0)
        {
            // foreach ($arr_director_id as $id) {
            //     # code...
            //     $this->db->delete('fs_dir_statement_director', array('id' => $id));
            // }
            // echo $arr_director_id;
            $arr_director_id = explode(',',$arr_director_id);
            $this->db->where_in('id', $arr_director_id);
            $this->db->delete('fs_dir_statement_director');
            
        }

        // delete company and directors under the company
        if(count($arr_company_id) > 0)
        {
            // foreach ($arr_company_id as $id) {
            //     // delete company
            //     $this->db->delete('fs_dir_statement_company', array('id' => $id));

            //     // delete director
            //     $this->db->delete('fs_dir_statement_director', array('fs_dir_statement_company_id' => $id));
            // }
            // delete company
            echo $arr_company_id;
            $arr_company_id = explode(',',$arr_company_id);
            $this->db->where_in('id', $arr_company_id);
            $this->db->delete('fs_dir_statement_company');

            // delete director
            $this->db->where_in('fs_dir_statement_company_id', $arr_company_id);
            $this->db->delete('fs_dir_statement_director');
            
        }
    }
    /* END OF DELETE DATA */
}
