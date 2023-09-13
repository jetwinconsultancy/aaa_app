<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('encryption', 'session'));
        $this->load->model('transaction_word_model');
    }

	public function get_all_document($transaction_id, $company_code, $second_transaction_task_id, $hidden_selected_el_id = null, $transaction_master_id = NULL, $audited_fs = NULL, $activity_status = NULL, $shorter_notice = NULL, $require_hold_agm_list = NULL){
        
        if($transaction_id == 30)
        {
            // $this->db->select('transaction_document.*, document_master.id as document_master_id, document_master.document_content');
            // $this->db->from('transaction_document');
            // $this->db->join('document_master', 'document_master.firm_id = '.$this->session->userdata('firm_id').' AND document_master.document_name = transaction_document.document_name', 'left');
            // $this->db->where('transaction_document.transaction_task_id', $transaction_id);
            // $this->db->where('transaction_document.id != 8');
            // $this->db->where('transaction_document.id != 10');
            // $this->db->order_by("id", "asc");
            if($transaction_master_id != '')
            {
                $where = "";

                for($r = 0; $r < count($hidden_selected_el_id); $r++)
                {
                    if($hidden_selected_el_id[$r] == "1")
                    {
                        $where = " transaction_document.id = '80'";
                    }
                    if($hidden_selected_el_id[$r] == "2")
                    {
                        if($hidden_selected_el_id[0] != "")
                        {
                            $where = $where."or transaction_document.id = '81'";
                        }
                        else
                        {
                            $where = $where." transaction_document.id = '81'";
                        }
                    }
                    // if($hidden_selected_el_id[$r] == "3")
                    // {
                    //     if($hidden_selected_el_id[0] != "" || $hidden_selected_el_id[1] != "")
                    //     {
                    //         $where = $where."or transaction_document.id = '82'";
                    //     }
                    //     else
                    //     {
                    //         $where = $where." transaction_document.id = '82'";
                    //     }
                    // }
                    // if($hidden_selected_el_id[$r] == "4")
                    // {
                    //     if($hidden_selected_el_id[0] != "" || $hidden_selected_el_id[1] != "" || $hidden_selected_el_id[2] != "")
                    //     {
                    //         $where = $where."or transaction_document.id = '89'";
                    //     }
                    //     else
                    //     {
                    //         $where = $where." transaction_document.id = '89'";
                    //     }
                    // }
                    // if($hidden_selected_el_id[$r] == "5")
                    // {
                    //     if($hidden_selected_el_id[0] != "" || $hidden_selected_el_id[1] != "" || $hidden_selected_el_id[2] != "" || $hidden_selected_el_id[3] != "")
                    //     {
                    //         $where = $where."or transaction_document.id = '92'";
                    //     }
                    //     else
                    //     {
                    //         $where = $where." transaction_document.id = '92'";
                    //     }
                    // }
                    if($hidden_selected_el_id[$r] == "6")
                    {
                        // if($hidden_selected_el_id[0] != "" || $hidden_selected_el_id[1] != "" || $hidden_selected_el_id[2] != "" || $hidden_selected_el_id[3] != "" || $hidden_selected_el_id[4] != "")
                        if($hidden_selected_el_id[0] != "" || $hidden_selected_el_id[1] != "")
                        {
                            $where = $where."or transaction_document.id = '93'";
                        }
                        else
                        {
                            $where = $where." transaction_document.id = '93'";
                        }
                    }
                }

                $q = $this->db->query("select transaction_document.*, document_master.id as document_master_id, document_category.document_category_name from transaction_document left join document_master on document_master.firm_id = '".$this->session->userdata('firm_id')."' AND document_master.document_name = transaction_document.document_name left join document_category on document_category.id = document_master.document_category_id where ".$where." order by id");

                if ($q->num_rows() > 0) {
                    foreach (($q->result()) as $row) {
                        $data[] = $row;
                    }
                }
            
                for($g = 0; $g < count($hidden_selected_el_id); $g++)
                {
                    $p = $this->db->query("select firm.name as firm_name from transaction_engagement_letter_service_info left join firm on firm.id = transaction_engagement_letter_service_info.servicing_firm where transaction_engagement_letter_service_info.transaction_id = ".$transaction_master_id." AND transaction_engagement_letter_service_info.engagement_letter_list_id = '".$hidden_selected_el_id[$g]."'");

                    if ($p->num_rows() > 0) {
                        foreach (($data) as $key=>$document) {
                            if($document->id == 80 && $hidden_selected_el_id[$g] == "1")
                            {
                                $data[$key]->firm_name = $p->result()[0]->firm_name;
                            }
                            if($document->id == 81 && $hidden_selected_el_id[$g] == "2")
                            {
                                $data[$key]->firm_name = $p->result()[0]->firm_name;
                            }
                            if($document->id == 82 && $hidden_selected_el_id[$g] == "3")
                            {
                                $data[$key]->firm_name = $p->result()[0]->firm_name;
                            }
                        }
                    }
                }
            }
        }
        else
        {
            $get_directors_info = $this->db->query("select * from transaction_client_officers where company_code='".$company_code."' AND (position = 1 OR position = 8)");

            $get_directors_info = $get_directors_info->result_array();

            $get_secretary_info = $this->db->query("select * from transaction_client_officers where company_code='".$company_code."' AND position = 4");

            $get_secretary_info = $get_secretary_info->result_array();

            $get_nominee_director_info = $this->db->query("select * from transaction_client_officers where company_code='".$company_code."' AND position = 8");

            $get_nominee_director_info = $get_nominee_director_info->result_array();

            $get_member_shares_info = $this->db->query("select * from transaction_member_shares where company_code='".$company_code."'");

            $get_member_shares_info = $get_member_shares_info->result_array();

            $this->db->select('transaction_document.*, document_master.id as document_master_id, document_master.document_content, document_category.document_category_name');
            $this->db->from('transaction_document');
            $this->db->join('document_master', 'document_master.firm_id = "'.$this->session->userdata('firm_id').'" AND document_master.document_name = transaction_document.document_name', 'left');
            $this->db->join('document_category', 'document_master.document_category_id = document_category.id', 'left');
            $this->db->where('transaction_document.transaction_task_id', $transaction_id);
            //$this->db->where('transaction_document.id != 8');
            //$this->db->where('transaction_document.id != 10');
            $this->db->order_by("id", "asc");

            if($transaction_id == 1)
            {
                if(count($get_directors_info) == 1 && count($get_member_shares_info) == 1)
                {
                    $this->db->where('transaction_document.id != 8');
                    $this->db->where('transaction_document.id != 10');
                }
                else
                {
                    $this->db->where('transaction_document.id != 9');
                }

                if(count($get_secretary_info) == 0)
                {
                    $this->db->where('transaction_document.id != 5');
                }

                if(count($get_nominee_director_info) == 0)
                {
                    $this->db->where('transaction_document.id != 85');
                    $this->db->where('transaction_document.id != 86');
                }
            }

            if($transaction_id == 15)
            {
                // if($audited_fs == 1)
                // {
                //     $this->db->where('transaction_document.id != 43');  
                // }
                // else
                // {
                //     $this->db->where('transaction_document.id != 96');
                // }

                if($activity_status == 2 && $require_hold_agm_list == 3)
                {
                    $this->db->where('transaction_document.id != 95');  
                }
                else
                {
                    $this->db->where('transaction_document.id != 99');
                }
                //$this->db->where('transaction_document.id = 84');
                // if($shorter_notice == 2)
                // {
                //     $this->db->where('transaction_document.id != 98');
                // }
            }

            $q = $this->db->get();

            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
            }

            if($transaction_id == 10 || $transaction_id == 11)
            {
                $this->db->select('transaction_document.*, document_master.id as document_master_id, document_master.document_content, document_category.document_category_name');
                $this->db->from('transaction_document');
                $this->db->join('document_master', 'document_master.firm_id = '.$this->session->userdata('firm_id').' AND document_master.document_name = transaction_document.document_name', 'left');
                $this->db->join('document_category', 'document_master.document_category_id = document_category.id', 'left');
                $this->db->where('transaction_document.id = 84'); 
                $this->db->order_by("id", "asc");

                $notice_of_controller_query = $this->db->get();

                if ($notice_of_controller_query->num_rows() > 0) {
                    foreach (($notice_of_controller_query->result()) as $row) {
                        $data[] = $row;
                    }
                }
            }

            if($second_transaction_task_id != "")
            {
                $this->db->select('transaction_document.*, document_master.id as document_master_id, document_master.document_content, document_category.document_category_name');
                $this->db->from('transaction_document');
                $this->db->join('document_master', 'document_master.firm_id = '.$this->session->userdata('firm_id').' AND document_master.document_name = transaction_document.document_name', 'left');
                $this->db->join('document_category', 'document_master.document_category_id = document_category.id', 'left');
                $this->db->where('transaction_document.transaction_task_id', $second_transaction_task_id);
                $this->db->order_by("id", "asc");

                $p = $this->db->get();
                if ($q->num_rows() > 0) {
                    foreach (($p->result()) as $row) {
                        if(!($transaction_id == 3 && $row->document_name == "DRIW-Appt of Director"))
                        {
                            $data[] = $row;
                        }
                    }
                }
            }
        }

        if(count($data) > 0)
        {
            return $data;
        }
        else
        {
            return FALSE;
        }
    }

    public function get_all_document_category_list()
    {
        $q = $this->db->query('select * from document_category');

        if ($q->num_rows() > 0) {
            foreach (($q->result_array()) as $row) {
                $document_category[] = $row;
            }
        }
        
        return $document_category;
    }

    public function get_last_cert_no($transaction_company_code, $client_member_share_capital_id)
    {
        $q = $this->db->query('select MAX(CONVERT(certificate_no, SIGNED INTEGER)) as last_cert_no from certificate where certificate.company_code="'.$transaction_company_code.'" AND certificate.client_member_share_capital_id = "'.$client_member_share_capital_id.'"');

        if ($q->num_rows() > 0) {
            foreach (($q->result_array()) as $row) {

                $last_cert_no[] = $row;
            }
        }
        
        return $last_cert_no;
    }

    public function getLatestShareNumberForCert($transaction_company_code, $client_member_share_capital_id, $transaction_master_id)
    {
        // $q = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" and client.firm_id = "'.$this->session->userdata('firm_id').'" and client.deleted = 0 where member_shares.company_code="'.$transaction_company_code.'" AND member_shares.client_member_share_capital_id = "'.$client_member_share_capital_id.'" GROUP BY member_shares.field_type, member_shares.officer_id HAVING sum(member_shares.number_of_share) != 0 AND member_shares.cert_status = 1');

        $q = $this->db->query('select certificate.*, certificate.number_of_share as number_of_share, certificate.amount_share as amount_share, certificate.no_of_share_paid as no_of_share_paid, certificate.amount_paid as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from certificate left join officer on certificate.officer_id = officer.id and certificate.field_type = officer.field_type left join officer_company on certificate.officer_id = officer_company.id and certificate.field_type = officer_company.field_type left join client_member_share_capital as share_capital on certificate.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = certificate.officer_id and certificate.field_type = "client" and client.firm_id = "'.$this->session->userdata('firm_id').'" and client.deleted = 0 where certificate.company_code="'.$transaction_company_code.'" AND certificate.status = 1 ORDER BY certificate.id');
        // $q = $this->db->query('select certificate.*, (certificate.number_of_share) as number_of_share, (certificate.amount_share) as amount_share, (certificate.no_of_share_paid) as no_of_share_paid, (certificate.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from certificate left join officer on certificate.officer_id = officer.id and certificate.field_type = officer.field_type left join officer_company on certificate.officer_id = officer_company.id and certificate.field_type = officer_company.field_type left join client_member_share_capital as share_capital on certificate.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = certificate.officer_id and certificate.field_type = "client" where certificate.company_code="'.$transaction_company_code.'" AND certificate.client_member_share_capital_id = "'.$client_member_share_capital_id.'" AND certificate.number_of_share > 0 AND certificate.status = 1 ORDER BY officer_id, field_type');

        if ($q->num_rows() > 0) {
            $array_data_share = $q->result_array();
            foreach (($q->result()) as $row) {
                $data_share[] = $row;
            }
        }

        $this->data['transaction_current_share_member'] = $data_share;


        // $q = $this->db->query('(select transaction_certificate.*, sum(transaction_certificate.number_of_share) as number_of_share, sum(transaction_certificate.amount_share) as amount_share, sum(transaction_certificate.no_of_share_paid) as no_of_share_paid, sum(transaction_certificate.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from transaction_certificate left join officer on transaction_certificate.officer_id = officer.id and transaction_certificate.field_type = officer.field_type left join officer_company on transaction_certificate.officer_id = officer_company.id and transaction_certificate.field_type = officer_company.field_type left join client_member_share_capital as share_capital on transaction_certificate.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = transaction_certificate.officer_id and transaction_certificate.field_type = "client" and client.firm_id = "'.$this->session->userdata('firm_id').'" and client.deleted = 0 where transaction_certificate.company_code="'.$transaction_company_code.'" AND transaction_certificate.client_member_share_capital_id = "'.$client_member_share_capital_id.'" AND transaction_certificate.transaction_page_id = "'.$transaction_master_id.'" AND transaction_certificate.number_of_share < 0 GROUP BY transaction_certificate.field_type, transaction_certificate.officer_id HAVING sum(transaction_certificate.number_of_share) != 0) 
        //     UNION ALL
        //     (select transaction_certificate.*, (transaction_certificate.number_of_share) as number_of_share, (transaction_certificate.amount_share) as amount_share, (transaction_certificate.no_of_share_paid) as no_of_share_paid, (transaction_certificate.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from transaction_certificate left join officer on transaction_certificate.officer_id = officer.id and transaction_certificate.field_type = officer.field_type left join officer_company on transaction_certificate.officer_id = officer_company.id and transaction_certificate.field_type = officer_company.field_type left join client_member_share_capital as share_capital on transaction_certificate.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = transaction_certificate.officer_id and transaction_certificate.field_type = "client" and client.firm_id = "'.$this->session->userdata('firm_id').'" and client.deleted = 0 where transaction_certificate.company_code="'.$transaction_company_code.'" AND transaction_certificate.client_member_share_capital_id = "'.$client_member_share_capital_id.'" AND transaction_certificate.transaction_page_id = "'.$transaction_master_id.'" AND transaction_certificate.number_of_share > 0)');

        $q = $this->db->query('select transaction_certificate.*, MAX(transaction_certificate.id) as last_cert_id, (SELECT trans_cert2.transaction_id FROM transaction_certificate as trans_cert2 WHERE trans_cert2.id = MAX(transaction_certificate.id)) as last_transaction_id, (SELECT trans_cert3.certificate_no FROM transaction_certificate as trans_cert3 WHERE trans_cert3.id = MAX(transaction_certificate.id)) as last_certificate_no, sum(transaction_certificate.number_of_share) as number_of_share, sum(transaction_certificate.amount_share) as amount_share, sum(transaction_certificate.no_of_share_paid) as no_of_share_paid, sum(transaction_certificate.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency, transaction_member_shares.id as transaction_member_shares_id from transaction_certificate left join officer on transaction_certificate.officer_id = officer.id and transaction_certificate.field_type = officer.field_type left join officer_company on transaction_certificate.officer_id = officer_company.id and transaction_certificate.field_type = officer_company.field_type left join client_member_share_capital as share_capital on transaction_certificate.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = transaction_certificate.officer_id and transaction_certificate.field_type = "client" and client.firm_id = "'.$this->session->userdata('firm_id').'" and client.deleted = 0 left join transaction_member_shares on transaction_member_shares.transaction_page_id = transaction_certificate.transaction_page_id and transaction_member_shares.company_code = transaction_certificate.company_code and transaction_member_shares.officer_id = transaction_certificate.officer_id and transaction_member_shares.field_type = transaction_certificate.field_type and transaction_member_shares.transaction_id = transaction_certificate.transaction_id where transaction_certificate.company_code="'.$transaction_company_code.'" AND transaction_certificate.transaction_page_id = "'.$transaction_master_id.'" GROUP BY transaction_certificate.field_type, transaction_certificate.officer_id, transaction_certificate.previous_certificate_id, share_capital.currency_id HAVING sum(transaction_certificate.number_of_share) != 0 ORDER BY transaction_certificate.client_member_share_capital_id, transaction_member_shares.id');


        if ($q->num_rows() > 0) {
            $array_data_change_share = $q->result_array();
            foreach ($array_data_change_share as $key => $row) {
                if($row["field_type"] == "individual")
                {
                    $array_data_change_share[$key]["identification_no"] = $this->encryption->decrypt($row["identification_no"]);
                    $array_data_change_share[$key]["name"] = $this->encryption->decrypt($row["name"]);
                }
                elseif($row["field_type"] == "company")
                {
                    $array_data_change_share[$key]["register_no"] = $this->encryption->decrypt($row["register_no"]);
                    $array_data_change_share[$key]["company_name"] = $this->encryption->decrypt($row["company_name"]);
                }
                else
                {
                    $array_data_change_share[$key]["registration_no"] = $this->encryption->decrypt($row["registration_no"]);
                    $array_data_change_share[$key]["client_company_name"] = $this->encryption->decrypt($row["client_company_name"]);
                }
                //$data_change_share[] = $row;
            }
            // foreach (($q->result()) as $row) {
            //     if($row->field_type == "individual")
            //     {
            //         $row->identification_no = $this->encryption->decrypt($row->identification_no);
            //         $row->name = $this->encryption->decrypt($row->name);
            //     }
            //     elseif($row->field_type == "company")
            //     {
            //         $row->register_no = $this->encryption->decrypt($row->register_no);
            //         $row->company_name = $this->encryption->decrypt($row->company_name);
            //     }
            //     else
            //     {
            //         $row->registration_no = $this->encryption->decrypt($row->registration_no);
            //         $row->client_company_name = $this->encryption->decrypt($row->client_company_name);
            //     }
            //     $data_change_share[] = $row;
            // }
        }

        return $array_data_change_share;
        //print_r($data_change_share);
        //echo json_encode($array_data_change_share);
        // $this->data['transaction_change_share_member'] = $data_change_share;

        // //echo json_encode($array_data_share);
        
        // for($d = 0; $d < count($array_data_change_share); $d++)
        // {
        //     $number_of_share = $array_data_change_share[$d]["number_of_share"];
        //     $amount_share = $array_data_change_share[$d]["amount_share"];
        //     $no_of_share_paid = $array_data_change_share[$d]["no_of_share_paid"];
        //     $amount_paid = $array_data_change_share[$d]["amount_paid"];

        //     // $latest_share_number_for_cert[$d]['number_of_share'] = 0;
        //     // $latest_share_number_for_cert[$d]['amount_share'] = 0;
        //     // $latest_share_number_for_cert[$d]['no_of_share_paid'] = 0;
        //     // $latest_share_number_for_cert[$d]['amount_paid'] = 0;

        //     for($f = 0; $f < count($array_data_share); $f++)
        //     {
        //         $latest_share_number_for_cert[$d]['id'] = $array_data_change_share[$d]["transaction_member_shares_id"];
        //         $latest_share_number_for_cert[$d]['company_code'] = $array_data_change_share[$d]["company_code"];
        //         $latest_share_number_for_cert[$d]['certificate_id'] = $array_data_change_share[$d]["id"];
        //         $latest_share_number_for_cert[$d]['identification_no'] = $this->encryption->decrypt($array_data_change_share[$d]["identification_no"]);
        //         $latest_share_number_for_cert[$d]['name'] = $this->encryption->decrypt($array_data_change_share[$d]["name"]);
        //         $latest_share_number_for_cert[$d]['register_no'] = $this->encryption->decrypt($array_data_change_share[$d]["register_no"]);
        //         $latest_share_number_for_cert[$d]['company_name'] = $this->encryption->decrypt($array_data_change_share[$d]["company_name"]);
        //         $latest_share_number_for_cert[$d]['registration_no'] = $array_data_change_share[$d]["registration_no"];
        //         $latest_share_number_for_cert[$d]['client_company_name'] = $array_data_change_share[$d]["client_company_name"];
        //         $latest_share_number_for_cert[$d]['sharetype'] = $array_data_change_share[$d]["sharetype"];
        //         $latest_share_number_for_cert[$d]['officer_id'] = $array_data_change_share[$d]["officer_id"];
        //         $latest_share_number_for_cert[$d]['field_type'] = $array_data_change_share[$d]["field_type"];
        //         $latest_share_number_for_cert[$d]['currency'] = $array_data_change_share[$d]["currency"];
        //         $latest_share_number_for_cert[$d]['other_class'] = $array_data_change_share[$d]["other_class"];
        //         $latest_share_number_for_cert[$d]['certificate_no'] = $array_data_change_share[$d]["certificate_no"];
        //         $latest_share_number_for_cert[$d]['new_certificate_no'] = $array_data_change_share[$d]["new_certificate_no"];
                
        //         if($array_data_change_share[$d]["officer_id"] == $array_data_share[$f]["officer_id"] && $array_data_change_share[$d]["field_type"] == $array_data_share[$f]["field_type"] && 0 > $array_data_change_share[$d]["number_of_share"] && $array_data_change_share[$d]["previous_certificate_id"] == $array_data_share[$f]["id"])
        //         {
        //             // if($latest_number_of_share_for_cert < 0)
        //             // {
        //             //     $latest_share_number_for_cert[$d]['number_of_share'] = $array_data_change_share[$f]["number_of_share"] + $latest_share_number_for_cert[$d]['number_of_share'];
        //             //     $latest_share_number_for_cert[$d]['amount_share'] = $array_data_change_share[$f]["amount_share"] + $latest_share_number_for_cert[$d]['amount_share'];
        //             //     $latest_share_number_for_cert[$d]['no_of_share_paid'] = $array_data_change_share[$f]["no_of_share_paid"] + $latest_share_number_for_cert[$d]['no_of_share_paid'];
        //             //     $latest_share_number_for_cert[$d]['amount_paid'] = $array_data_change_share[$f]["amount_paid"] + $latest_share_number_for_cert[$d]['amount_paid'];
        //             // }
        //             $latest_number_of_share_for_cert = $array_data_share[$f]["number_of_share"] + $number_of_share;
        //             $latest_amount_share_for_cert = $array_data_share[$f]["amount_share"] + $amount_share;
        //             $latest_no_of_share_paid_for_cert = $array_data_share[$f]["no_of_share_paid"] + $no_of_share_paid;
        //             $latest_amount_paid_for_cert = $array_data_share[$f]["amount_paid"] + $amount_paid;


        //             if($latest_number_of_share_for_cert >= 0)
        //             {
        //                 $latest_share_number_for_cert[$d]['number_of_share'] = $latest_number_of_share_for_cert;
        //                 $latest_share_number_for_cert[$d]['amount_share'] = $array_data_share[$f]["amount_share"] + $amount_share;
        //                 $latest_share_number_for_cert[$d]['no_of_share_paid'] = $array_data_share[$f]["no_of_share_paid"] + $no_of_share_paid;
        //                 $latest_share_number_for_cert[$d]['amount_paid'] = $array_data_share[$f]["amount_paid"] + $amount_paid;
        //                 break;
        //             }
        //             else if($latest_number_of_share_for_cert < 0)
        //             {
        //                 $latest_share_number_for_cert[$d]['number_of_share'] = $latest_number_of_share_for_cert;
        //                 $latest_share_number_for_cert[$d]['amount_share'] = $latest_amount_share_for_cert;
        //                 $latest_share_number_for_cert[$d]['no_of_share_paid'] = $latest_no_of_share_paid_for_cert;
        //                 $latest_share_number_for_cert[$d]['amount_paid'] = $latest_amount_paid_for_cert;

        //                 $number_of_share = $latest_number_of_share_for_cert;
        //                 $amount_share = $latest_amount_share_for_cert;
        //                 $no_of_share_paid = $latest_no_of_share_paid_for_cert;
        //                 $amount_paid = $latest_amount_paid_for_cert;
        //             }
                    
        //         }
        //         else if(0 < $array_data_change_share[$d]["number_of_share"])
        //         {
        //             $latest_share_number_for_cert[$d]['number_of_share'] = $array_data_change_share[$d]["number_of_share"];
        //             $latest_share_number_for_cert[$d]['amount_share'] = $array_data_change_share[$d]["amount_share"];
        //             $latest_share_number_for_cert[$d]['no_of_share_paid'] = $array_data_change_share[$d]["no_of_share_paid"];
        //             $latest_share_number_for_cert[$d]['amount_paid'] = $array_data_change_share[$d]["amount_paid"];
        //         }
        //     }
        // }

        //return $latest_share_number_for_cert;

        
    }

    public function getLatestShareNumberForCertRecord($transaction_company_code, $client_member_share_capital_id, $transaction_master_id)
    {
        //20200306
        $q = $this->db->query('select certificate.*, (certificate.number_of_share) as number_of_share, (certificate.amount_share) as amount_share, (certificate.no_of_share_paid) as no_of_share_paid, (certificate.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from certificate left join officer on certificate.officer_id = officer.id and certificate.field_type = officer.field_type left join officer_company on certificate.officer_id = officer_company.id and certificate.field_type = officer_company.field_type left join client_member_share_capital as share_capital on certificate.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = certificate.officer_id and certificate.field_type = "client" where certificate.company_code="'.$transaction_company_code.'" AND certificate.number_of_share > 0 AND certificate.status = 1 ORDER BY officer_id, field_type'); // AND certificate.client_member_share_capital_id = "'.$client_member_share_capital_id.'"

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row)
            {
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
            return $data;
        }
        return FALSE;
    }

    public function getTransactionSharetransferRecord($transaction_master_id)
    {
        $q = $this->db->query('select * from transaction_share_transfer_record where transaction_page_id = '.$transaction_master_id);

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row)
            {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionShareTransferRecordForCert($transaction_master_id)
    {
        $q = $this->db->query('select * from transaction_share_transfer_record where transaction_page_id = '.$transaction_master_id);

        if ($q->num_rows() > 0) {
            $q = $q->result_array();

            $id = $q[0]["id"];
            $transaction_page_id = $q[0]["transaction_page_id"];
            $transferor_array = json_decode($q[0]["transferor_array"]);
            $transferee_array = json_decode($q[0]["transferee_array"]);
            $member_info = array_merge($transferor_array, $transferee_array);
            $index = 0;
            foreach (($member_info) as $row)
            {
                if($row->new_number_of_share != "" && $row->new_number_of_share != "0")
                {
                    if($row->field_type == "individual")
                    {
                        $officer_query = $this->db->query('select * from officer where id = '.$row->officer_id);
                        $officer_query = $officer_query->result_array();

                        $latest_share_number_for_cert[$index]['TransfereeCertificate'] = $row->certificate;
                        $latest_share_number_for_cert[$index]['TransfereeShareNumber(Number)'] = strtoupper(number_format(str_replace(',', '', $row->new_number_of_share)));
                        $latest_share_number_for_cert[$index]['TransfereeID'] = $this->encryption->decrypt($officer_query[0]["identification_no"]);
                        $latest_share_number_for_cert[$index]['TransfereeName'] = $this->encryption->decrypt($officer_query[0]["name"]);
                        $member_address_type = 'Local';
                        if($officer_query[0]["alternate_address"] == "1")
                        {
                            $member_unit_no1 = $officer_query[0]["unit_no3"];
                            $member_unit_no2 = $officer_query[0]["unit_no4"];
                            $member_street_name = $officer_query[0]["street_name2"];
                            $member_building_name = $officer_query[0]["building_name2"];
                            $member_postal_code = $officer_query[0]["postal_code2"];
                        }
                        else
                        {
                            $member_address_type = $officer_query[0]['address_type'];
                            $member_unit_no1 = $officer_query[0]["unit_no1"];
                            $member_unit_no2 = $officer_query[0]["unit_no2"];
                            $member_street_name = $officer_query[0]["street_name1"];
                            $member_building_name = $officer_query[0]["building_name1"];
                            $member_postal_code = $officer_query[0]["postal_code1"];
                            $foreign_address1 = $officer_query[0]["foreign_address1"];
                            $foreign_address2 = $officer_query[0]["foreign_address2"];
                            $foreign_address3 = $officer_query[0]["foreign_address3"];
                        }

                        $address = array(
                            'type'          => $member_address_type,
                            'street_name1'  => strtoupper($member_street_name),
                            'unit_no1'      => strtoupper($member_unit_no1),
                            'unit_no2'      => strtoupper($member_unit_no2),
                            'building_name1'=> strtoupper($member_building_name),
                            'postal_code1'  => strtoupper($member_postal_code),
                            'foreign_address1' => strtoupper($foreign_address1),
                            'foreign_address2' => strtoupper($foreign_address2),
                            'foreign_address3' => strtoupper($foreign_address3)
                        );
                        $latest_share_number_for_cert[$index]['TransfereeAddress'] = $this->transaction_word_model->write_address_local_foreign($address, "comma", "big_cap");

                        $latest_share_number_for_cert[$index]['TransfereeShareNumber'] = strtoupper($this->convert_number_to_word_model->convert_number_to_words(str_replace(',', '', $row->new_number_of_share)))." (".number_format(str_replace(',', '', $row->new_number_of_share)).")";
                        $latest_share_number_for_cert[$index]['TransfereeShareType'] = $row->sharetype;
                        $latest_share_number_for_cert[$index]['number_of_share'] = (int)(str_replace(',', '', $row->new_number_of_share));
                        
                    }
                    elseif($row->field_type == "company")
                    {
                        $officer_company_query = $this->db->query('select * from officer_company where id = '.$row->officer_id);
                        $officer_company_query = $officer_company_query->result_array();

                        $latest_share_number_for_cert[$index]['TransfereeCertificate'] = $row->certificate;
                        
                        $latest_share_number_for_cert[$index]['TransfereeShareNumber(Number)'] = strtoupper(number_format(str_replace(',', '', $row->new_number_of_share)));
                        $latest_share_number_for_cert[$index]['TransfereeID'] = $this->encryption->decrypt($officer_company_query[0]["register_no"]);
                        $latest_share_number_for_cert[$index]['TransfereeName'] = $this->encryption->decrypt($officer_company_query[0]["company_name"]);

                        $member_address_type = $officer_company_query[0]['address_type'];
                        $member_unit_no1 = $officer_company_query[0]["company_unit_no1"];
                        $member_unit_no2 = $officer_company_query[0]["company_unit_no2"];
                        $member_street_name = $officer_company_query[0]["company_street_name"];
                        $member_building_name = $officer_company_query[0]["company_building_name"];
                        $member_postal_code = $officer_company_query[0]["company_postal_code"];
                        $foreign_address1 = $officer_company_query[0]["company_foreign_address1"];
                        $foreign_address2 = $officer_company_query[0]["company_foreign_address2"];
                        $foreign_address3 = $officer_company_query[0]["company_foreign_address3"];

                        $address = array(
                            'type'          => $member_address_type,
                            'street_name1'  => strtoupper($member_street_name),
                            'unit_no1'      => strtoupper($member_unit_no1),
                            'unit_no2'      => strtoupper($member_unit_no2),
                            'building_name1'=> strtoupper($member_building_name),
                            'postal_code1'  => strtoupper($member_postal_code),
                            'foreign_address1' => strtoupper($foreign_address1),
                            'foreign_address2' => strtoupper($foreign_address2),
                            'foreign_address3' => strtoupper($foreign_address3)
                        );
                        $latest_share_number_for_cert[$index]['TransfereeAddress'] = $this->transaction_word_model->write_address_local_foreign($address, "comma", "big_cap");

                        $latest_share_number_for_cert[$index]['TransfereeShareNumber'] = strtoupper($this->convert_number_to_word_model->convert_number_to_words(str_replace(',', '', $row->new_number_of_share)))." (".number_format(str_replace(',', '', $row->new_number_of_share)).")";
                        $latest_share_number_for_cert[$index]['TransfereeShareType'] = $row->sharetype;
                        $latest_share_number_for_cert[$index]['number_of_share'] = (int)(str_replace(',', '', $row->new_number_of_share));
                    }
                    elseif($row->field_type == "client")
                    {
                        $client_query = $this->db->query('select * from client where id = '.$row->officer_id);
                        $client_query = $client_query->result_array();

                        $latest_share_number_for_cert[$index]['TransfereeCertificate'] = $row->certificate;
                        
                        $latest_share_number_for_cert[$index]['TransfereeShareNumber(Number)'] = strtoupper(number_format(str_replace(',', '', $row->new_number_of_share)));
                        $latest_share_number_for_cert[$index]['TransfereeID'] = $this->encryption->decrypt($client_query[0]["registration_no"]);
                        $latest_share_number_for_cert[$index]['TransfereeName'] = $this->encryption->decrypt($client_query[0]["company_name"]);

                        $member_address_type = 'Local';
                        $member_unit_no1 = $client_query[0]["unit_no1"];
                        $member_unit_no2 = $client_query[0]["unit_no2"];
                        $member_street_name = $client_query[0]["street_name"];
                        $member_building_name = $client_query[0]["building_name"];
                        $member_postal_code = $client_query[0]["postal_code"];
                        $foreign_address1 = "";
                        $foreign_address2 = "";
                        $foreign_address3 = "";

                        $address = array(
                            'type'          => $member_address_type,
                            'street_name1'  => strtoupper($member_street_name),
                            'unit_no1'      => strtoupper($member_unit_no1),
                            'unit_no2'      => strtoupper($member_unit_no2),
                            'building_name1'=> strtoupper($member_building_name),
                            'postal_code1'  => strtoupper($member_postal_code),
                            'foreign_address1' => strtoupper($foreign_address1),
                            'foreign_address2' => strtoupper($foreign_address2),
                            'foreign_address3' => strtoupper($foreign_address3)
                        );
                        $latest_share_number_for_cert[$index]['TransfereeAddress'] = $this->transaction_word_model->write_address_local_foreign($address, "comma", "big_cap");

                        $latest_share_number_for_cert[$index]['TransfereeShareNumber'] = strtoupper($this->convert_number_to_word_model->convert_number_to_words(str_replace(',', '', $row->new_number_of_share)))." (".number_format(str_replace(',', '', $row->new_number_of_share)).")";
                        $latest_share_number_for_cert[$index]['TransfereeShareType'] = $row->sharetype;
                        $latest_share_number_for_cert[$index]['number_of_share'] = (int)(str_replace(',', '', $row->new_number_of_share));
                    }

                    $words = explode(' ', $latest_share_number_for_cert[$index]['TransfereeAddress']);

                    $maxLineLength = 55;

                    $currentLength = 0;
                    $add_index = 0;

                    foreach ($words as $word) {
                        // +1 because the word will receive back the space in the end that it loses in explode()
                        $wordLength = strlen($word) + 1;

                        if (($currentLength + $wordLength) <= $maxLineLength) {
                            $output[$add_index] .= $word . ' ';
                            $currentLength += $wordLength;
                        } else {
                            $add_index += 1;
                            $currentLength = $wordLength;
                            $output[$add_index] = $word . ' ';
                        }
                    }

                    $latest_share_number_for_cert[$index]['membersAddressLine1'] = $output[0];
                    $latest_share_number_for_cert[$index]['membersAddressLine2'] = $output[1];
                    $output[0] = "";
                    $output[1] = "";
                    $index++;
                }
            }
            //return $data;
            return $latest_share_number_for_cert;
        }
        return FALSE;
    }

    public function get_document($transaction_id, $company_code){

        $this->db->select('transaction_document.*, document_master.id as document_master_id, document_master.document_content');
        $this->db->from('transaction_document');
        $this->db->join('document_master', 'document_master.firm_id = '.$this->session->userdata('firm_id').' AND document_master.document_name = transaction_document.document_name', 'left');
        $this->db->where('transaction_document.transaction_task_id', $transaction_id);
        $this->db->order_by("id", "asc");

        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function checkEditLatestNumOfShare($field_type, $officer_id, $transaction_master_id, $transaction_certificate_id, $certID)
    {
        $this->db->select('sum(number_of_share) as total_number_of_share');
        $this->db->from('transaction_certificate');
        $this->db->where('transaction_certificate.number_of_share < 0');
        $this->db->where('transaction_certificate.officer_id', $officer_id);
        $this->db->where('transaction_certificate.field_type', $field_type);
        $this->db->where('transaction_certificate.id !=', $transaction_certificate_id);
        //$this->db->where('transaction_certificate.previous_certificate_id', $certID);

        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function checkLatestNumOfShare($field_type, $officer_id, $transaction_master_id, $certID)
    {
        $this->db->select('sum(number_of_share) as total_number_of_share');
        $this->db->from('transaction_certificate');
        $this->db->where('transaction_certificate.number_of_share < 0');
        $this->db->where('transaction_certificate.officer_id', $officer_id);
        $this->db->where('transaction_certificate.field_type', $field_type);
        $this->db->where('transaction_certificate.transaction_page_id', $transaction_master_id);
        $this->db->where('transaction_certificate.previous_certificate_id', $certID);
        //$this->db->group_by('previous_certificate_id');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_transaction($group_id){
        if($group_id != 4)
        {
            $this->db->select('transaction_master.*, transaction_status.transaction_status, transaction_tasks.transaction_task, transaction_client.company_name');
            $this->db->from('transaction_master');
            $this->db->join('transaction_client', 'transaction_client.transaction_id = transaction_master.id', 'left');
            $this->db->join('transaction_status', 'transaction_status.id = transaction_master.status', 'left');
            $this->db->join('transaction_tasks', 'transaction_tasks.id = transaction_master.transaction_task_id', 'left');
            //$this->db->where('transaction_master.registration_no = ""');
            $this->db->where('transaction_master.transaction_task_id = 1');
            $this->db->where('transaction_master.firm_id = '.$this->session->userdata('firm_id').'');
            $this->db->order_by("id", "asc");
            $q = $this->db->get();

            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $row->registration_no = $this->encryption->decrypt($row->registration_no);
                    if($row->client_name != null)
                    {
                        $row->client_name = $this->encryption->decrypt($row->client_name);
                    }
                    if($row->company_name != null)
                    {
                        $row->company_name = $this->encryption->decrypt($row->company_name);
                    }
                    $data[] = $row;
                }
            }

            $this->db->select('transaction_master.*, transaction_status.transaction_status, transaction_tasks.transaction_task, transaction_client.company_name');
            $this->db->from('transaction_master');
            $this->db->join('transaction_client', 'transaction_client.transaction_id = transaction_master.id', 'left');
            $this->db->join('transaction_status', 'transaction_status.id = transaction_master.status', 'left');
            $this->db->join('transaction_tasks', 'transaction_tasks.id = transaction_master.transaction_task_id', 'left');
            //$this->db->where('transaction_master.registration_no = ""');
            $this->db->where('transaction_master.transaction_task_id = 28');
            $this->db->where('transaction_master.firm_id = '.$this->session->userdata('firm_id').'');
            $this->db->order_by("id", "asc");
            $q = $this->db->get();
            //echo json_encode($q->result_array());
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $row->registration_no = $this->encryption->decrypt($row->registration_no);
                    if($row->client_name != null)
                    {
                        $row->client_name = $this->encryption->decrypt($row->client_name);
                    }
                    if($row->company_name != null)
                    {
                        $row->company_name = $this->encryption->decrypt($row->company_name);
                    }
                    $data[] = $row;
                }
            }

            //echo json_encode($data);
        }

        $this->db->select('transaction_master.*, transaction_status.transaction_status, transaction_tasks.transaction_task, client.company_name');
        $this->db->from('transaction_master');
        $this->db->join('client', 'client.company_code = transaction_master.company_code', 'left');// and client.firm_id = "'.$this->session->userdata('firm_id').'" // AND client.deleted = 0
        $this->db->join('transaction_status', 'transaction_status.id = transaction_master.status', 'left');
        $this->db->join('transaction_tasks', 'transaction_tasks.id = transaction_master.transaction_task_id', 'left');
        //$this->db->where('transaction_master.registration_no != ""');
        $this->db->where('transaction_master.transaction_task_id != 1 AND transaction_master.transaction_task_id != 28 AND transaction_master.transaction_task_id != 29 AND transaction_master.transaction_task_id != 30 AND transaction_master.transaction_task_id != 35');
        $this->db->where('transaction_master.firm_id = '.$this->session->userdata('firm_id').'');
        $this->db->order_by("id", "asc");

        if($group_id == 4)
        {
            $this->db->join('user_client', 'client.id = user_client.client_id AND user_client.user_id = '.$this->session->userdata('user_id'), 'right');
        }
        $p = $this->db->get(); 

        if ($p->num_rows() > 0) {
            foreach (($p->result()) as $row) {
                if($row->company_name != null)
                {
                    $row->company_name = $this->encryption->decrypt($row->company_name);
                }
                $row->registration_no = $this->encryption->decrypt($row->registration_no);
                if($row->client_name != null)
                {
                    $row->client_name = $this->encryption->decrypt($row->client_name);
                }
                $data[] = $row;
            }
            
        }

        if($group_id == 4)
        {
            $this->db->select('client.*');
            $this->db->from('users');
            $this->db->join('user_client', 'user_client.user_id = users.id', 'right');
            $this->db->join('client', 'client.id = user_client.client_id', 'right');
            $this->db->where('users.user_deleted = 0 AND users.id = '.$this->session->userdata('user_id'));
            $access_client_list_query = $this->db->get(); 

            if ($access_client_list_query->num_rows() > 0) {
                foreach (($access_client_list_query->result()) as $row) {
                    $row->registration_no = $this->encryption->decrypt($row->registration_no);
                    $row->company_name = $this->encryption->decrypt($row->company_name);
                    $access_client_list_data[] = $row;
                }
            }
        }

        $this->db->select('transaction_master.*, transaction_status.transaction_status, transaction_tasks.transaction_task');
        $this->db->from('transaction_master');
        $this->db->join('transaction_status', 'transaction_status.id = transaction_master.status', 'left');
        $this->db->join('transaction_tasks', 'transaction_tasks.id = transaction_master.transaction_task_id', 'left');
        //$this->db->where('transaction_master.registration_no != ""');
        $this->db->where('transaction_master.transaction_task_id != 1 AND transaction_master.transaction_task_id != 28 AND (transaction_master.transaction_task_id = 29 OR transaction_master.transaction_task_id = 30 OR transaction_master.transaction_task_id = 35)');
        $this->db->where('transaction_master.firm_id = '.$this->session->userdata('firm_id').'');
        $this->db->order_by("id", "asc");

        $p = $this->db->get();

        if ($p->num_rows() > 0) {
            foreach (($p->result()) as $row) {
                $row->registration_no = $this->encryption->decrypt($row->registration_no);
                if($row->client_name != null)
                {
                    $row->client_name = $this->encryption->decrypt($row->client_name);
                }
                if($group_id == 4)
                {
                    foreach ($access_client_list_data as $client_list_row) 
                    {
                        if(trim($row->client_name) == trim($client_list_row->company_name))
                        {
                            $data[] = $row;
                        }
                    }
                }
                else
                {
                    $data[] = $row;
                }
            }
        }

        //echo json_encode($data);
        if(isset($data))
        {
            if(count($data) > 0)
            {
                return $data;
            }
            else
                return FALSE;
        }
        else
            return FALSE;
    }

    public function getTransactionMaster($id)
    {
        $this->db->select('transaction_master.*');
        $this->db->from('transaction_master');
        $this->db->where('id', $id);
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $row->registration_no = $this->encryption->decrypt($row->registration_no);
                $row->client_name = $this->encryption->decrypt($row->client_name);
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionAgmAr($id, $company_code)
    {
        $this->db->select('transaction_agm_ar.*, transaction_master.effective_date, client.company_name, xbrl_list_name, require_hold_agm_list.require_hold_agm_list_name, transaction_first_agm.is_first_agm, activity_status.activity_status_name, solvency_status.solvency_status_name, transaction_epc_status.is_epc_status, small_company.small_company_decision, audited_financial_statement.audited_fs_decision, transaction_agm_share_transfer.share_transfer_name, transaction_consent_for_shorter_notice.is_shorter_notice, y.exemption as cont_exemption_name, z.exemption as dir_exemption_name, regis_controller_is_kept.cont_is_kept_at, regis_nominee_dir_is_kept.dir_is_kept_at');
        $this->db->from('transaction_agm_ar');
        $this->db->where('transaction_agm_ar.transaction_id', $id);
        $this->db->join('transaction_master', 'transaction_master.id = transaction_agm_ar.transaction_id ', 'left');
        $this->db->join('client', 'client.company_code = transaction_master.company_code ', 'left');
        $this->db->join('transaction_first_agm', 'transaction_first_agm.id = transaction_agm_ar.is_first_agm_id ', 'left');
        $this->db->join('activity_status', 'activity_status.id = transaction_agm_ar.activity_status ', 'left');
        $this->db->join('solvency_status', 'solvency_status.id = transaction_agm_ar.solvency_status ', 'left');
        $this->db->join('transaction_epc_status', 'transaction_epc_status.id = transaction_agm_ar.epc_status_id ', 'left');
        $this->db->join('audited_financial_statement', 'audited_financial_statement.id = transaction_agm_ar.audited_fs ', 'left');
        $this->db->join('transaction_agm_share_transfer', '  transaction_agm_share_transfer.id = transaction_agm_ar.agm_share_transfer_id', 'left');
        $this->db->join('transaction_consent_for_shorter_notice', 'transaction_consent_for_shorter_notice.id = transaction_agm_ar.shorter_notice ', 'left');
        $this->db->join('small_company', 'small_company.id = transaction_agm_ar.small_company ', 'left');
        $this->db->join('exemption as y', 'y.id = transaction_agm_ar.cont_exemption_id ', 'left');
        $this->db->join('regis_controller_is_kept', 'regis_controller_is_kept.id = transaction_agm_ar.regis_controller_is_kept_id ', 'left');
        $this->db->join('exemption as z', 'z.id = transaction_agm_ar.dir_exemption_id ', 'left');
        $this->db->join('regis_nominee_dir_is_kept', 'regis_nominee_dir_is_kept.id = transaction_agm_ar.regis_nominee_dir_is_kept_id ', 'left');
        $this->db->join('xbrl_list', 'xbrl_list.id = transaction_agm_ar.xbrl', 'left');
        $this->db->join('require_hold_agm_list', 'require_hold_agm_list.id = transaction_agm_ar.require_hold_agm_list', 'left');
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            $data[0]->company_name = $this->encryption->decrypt($data[0]->company_name);
            $chairman_result_info = (explode("-",$data[0]->chairman));

            if($chairman_result_info[1] == "individual")
            {
                $officer_result = $this->db->query("select * from officer where id='".$chairman_result_info[0]."' AND field_type='".$chairman_result_info[1]."'");

                $officer_result = $officer_result->result_array();

                $name = $this->encryption->decrypt($officer_result[0]["name"]);
            }
            elseif($chairman_result_info[1] == "company")
            {
                $officer_company_result = $this->db->query("select * from officer_company where id='".$chairman_result_info[0]."' AND field_type='".$chairman_result_info[1]."'");

                $officer_company_result = $officer_company_result->result_array();

                $name = $this->encryption->decrypt($officer_company_result[0]["company_name"]);
            }
            elseif($chairman_result_info[1] == "corp_rep")
            {
                $corp_rep_result = $this->db->query("select * from corporate_representative where id='".$chairman_result_info[0]."'");

                $corp_rep_result = $corp_rep_result->result_array();

                $name = $corp_rep_result[0]["name_of_corp_rep"] .' - '. $corp_rep_result[0]["subsidiary_name"];
            }
            $data[0]->chairman_name = $name;

            return $data;
        }
        return FALSE;

    }

    public function getPreviousTransactionAgmAr($id, $company_code)
    {
        $this->db->select('transaction_agm_ar.cont_exemption_id, regis_controller_is_kept_id, dir_exemption_id, regis_nominee_dir_is_kept_id, activity_status, solvency_status, small_company, audited_fs, chairman');
        $this->db->from('transaction_agm_ar');
        $this->db->where('transaction_agm_ar.transaction_id', $id);
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    public function getTransactionDirectorFee($id, $company_code)
    {
        $this->db->select('transaction_agm_ar_director_fee.*, currency.currency');
        $this->db->from('transaction_agm_ar');
        $this->db->join('transaction_agm_ar_director_fee', 'transaction_agm_ar_director_fee.transaction_agm_ar_id = transaction_agm_ar.id', 'left');
        $this->db->join('currency', 'currency.id = transaction_agm_ar_director_fee.currency_id', 'left');
        $this->db->where('transaction_agm_ar.transaction_id', $id);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    // public function getTransactionDividend($id, $company_code)
    // {
    //     $this->db->select('transaction_agm_ar_dividend.*, transaction_agm_ar_total_dividend.total_dividend_declared');
    //     $this->db->from('transaction_agm_ar');
    //     $this->db->join('transaction_agm_ar_dividend', 'transaction_agm_ar_dividend.transaction_agm_ar_id = transaction_agm_ar.id', 'left');
    //     $this->db->join('transaction_agm_ar_total_dividend', 'transaction_agm_ar_total_dividend.transaction_agm_ar_id = transaction_agm_ar.id', 'left');
    //     $this->db->where('transaction_agm_ar.transaction_id', $id);
    //     $q = $this->db->get();
        
    //     if ($q->num_rows() > 0) {
    //         foreach (($q->result()) as $row) {
    //             $data[] = $row;
    //         }
    //         return $data;
    //     }
    //     return FALSE;

    // }

    public function getTransactionAmountDue($id, $company_code)
    {
        $this->db->select('transaction_agm_ar_amount_due.*');
        $this->db->from('transaction_agm_ar');
        $this->db->join('transaction_agm_ar_amount_due', 'transaction_agm_ar_amount_due.transaction_agm_ar_id = transaction_agm_ar.id', 'left');
        $this->db->where('transaction_agm_ar.transaction_id', $id);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    public function getTransactionDirectorRetire($id, $company_code)
    {
        $this->db->select('transaction_agm_ar_director_retire.*');
        $this->db->from('transaction_agm_ar');
        $this->db->join('transaction_agm_ar_director_retire', 'transaction_agm_ar_director_retire.transaction_agm_ar_id = transaction_agm_ar.id', 'left');
        $this->db->where('transaction_agm_ar.transaction_id', $id);
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    public function getTransactionReappointAuditor($id, $company_code)
    {
        $this->db->select('transaction_agm_ar_reappoint_auditor.*');
        $this->db->from('transaction_agm_ar');
        $this->db->join('transaction_agm_ar_reappoint_auditor', 'transaction_agm_ar_reappoint_auditor.transaction_agm_ar_id = transaction_agm_ar.id', 'left');
        $this->db->where('transaction_agm_ar.transaction_id', $id);
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    public function getTransactionClient($id, $company_code)
    {
        $this->db->select('transaction_client.*, company_type.company_type as company_type_name, transaction_master.effective_date, transaction_master.registration_no');
        $this->db->from('transaction_client');
        $this->db->join('company_type', 'company_type.id = transaction_client.company_type and transaction_client.company_type != 0', 'left');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_client.transaction_id ', 'left');
        $this->db->where('transaction_client.transaction_id', $id);
        $this->db->where('transaction_client.company_code', $company_code);
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $row->registration_no = $this->encryption->decrypt($row->registration_no);
                $row->company_name = $this->encryption->decrypt($row->company_name);
                $data[] = $row;
            }
            return $data;
        }
        else
        {
            $this->db->select('client.*, company_type.company_type as company_type_name');
            $this->db->from('client');
            $this->db->join('company_type', 'company_type.id = client.company_type and client.company_type != 0', 'left');
            $this->db->where('company_code', $company_code);
            //$this->db->where('firm_id', $this->session->userdata('firm_id'));
            $this->db->where('deleted = 0');
            $p = $this->db->get();

            if ($p->num_rows() > 0) {
                foreach (($p->result()) as $row) {
                    $row->registration_no = $this->encryption->decrypt($row->registration_no);
                    $row->company_name = $this->encryption->decrypt($row->company_name);
                    $data[] = $row;
                }
                return $data;
            }
            return FALSE;
        }
    }

    public function getTransactionResignOfCompanySecretary($id)
    {
        $this->db->select('*');
        $this->db->from('transaction_resignation_of_company_secretary');
        $this->db->where('transaction_id', $id);
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionResignClientOfficer($id)
    {
        $this->db->select('transaction_client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_officers_position.position as position_name, transaction_master.effective_date, transaction_resign_officer_reason.reason_selected, transaction_resign_officer_reason.reason, transaction_resign_officer_reason.is_resign');
        $this->db->from('transaction_client_officers');
        $this->db->join('officer', 'officer.id = transaction_client_officers.officer_id AND officer.field_type = transaction_client_officers.field_type', 'left');
        $this->db->join('officer_company', 'officer_company.id = transaction_client_officers.officer_id AND officer_company.field_type = transaction_client_officers.field_type', 'left');
        $this->db->join('client_officers_position', 'client_officers_position.id = transaction_client_officers.position', 'left');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_client_officers.transaction_id ', 'left');
        $this->db->join('transaction_resign_officer_reason', 'transaction_resign_officer_reason.transaction_client_officers_id = transaction_client_officers.id', 'left');
        $this->db->where('transaction_id', $id);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

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
            return $data;
        }
        return FALSE;
    }

	public function getTransactionClientOfficer($id, $transaction_company_code)
    {
        $this->db->select('transaction_client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_officers_position.position as position_name, transaction_master.effective_date');
        $this->db->from('transaction_client_officers');
        $this->db->join('officer', 'officer.id = transaction_client_officers.officer_id AND officer.field_type = transaction_client_officers.field_type', 'left');
        $this->db->join('officer_company', 'officer_company.id = transaction_client_officers.officer_id AND officer_company.field_type = transaction_client_officers.field_type', 'left');
        $this->db->join('client_officers_position', 'client_officers_position.id = transaction_client_officers.position', 'left');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_client_officers.transaction_id ', 'left');
        $this->db->where('transaction_client_officers.transaction_id', $id);
        $this->db->where('transaction_client_officers.company_code', $transaction_company_code);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

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
            return $data;
        }
        return FALSE;
    }

    public function get_lodge_status($transaction_master_id, $transaction_company_code)
    {
        $this->db->select('transaction_master.*');
        $this->db->from('transaction_master');
        $this->db->where('id', $transaction_master_id);
        $this->db->where('company_code', $transaction_company_code);
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function get_service_proposal_service_info_id($company_code)
    {
        $this->db->select('transaction_master.*');
        $this->db->from('transaction_master');
        
        $this->db->where('transaction_master.transaction_task_id', "29");
        $this->db->where('transaction_master.company_code', $company_code);
        $this->db->where('transaction_master.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('(transaction_master.status = 1 or transaction_master.status = 2)');
        $this->db->order_by("transaction_master.id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $row->client_name = $this->encryption->decrypt($row->client_name);
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_service_proposal_service_info($company_code)
    {
        $this->db->select('transaction_master.*, transaction_service_proposal_service_info.transaction_id, transaction_service_proposal_service_info.our_service_id, transaction_service_proposal_service_info.currency_id, transaction_service_proposal_service_info.fee, transaction_service_proposal_service_info.unit_pricing, transaction_service_proposal_service_info.servicing_firm, our_service_info.engagement_letter_list_id');
        $this->db->from('transaction_master');
        $this->db->join('transaction_service_proposal_service_info', 'transaction_service_proposal_service_info.transaction_id = transaction_master.id', 'left');
        $this->db->join('our_service_info', 'our_service_info.id = transaction_service_proposal_service_info.our_service_id', 'left');
        
        $this->db->where('transaction_master.transaction_task_id', "29");
        $this->db->where('transaction_master.company_code', $company_code);
        $this->db->where('transaction_master.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('(transaction_master.service_status = 1 or transaction_master.service_status = 3)');
        $this->db->order_by("transaction_master.id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $row->client_name = $this->encryption->decrypt($row->client_name);
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionEngagementLetterList()
    {
        $this->db->select('engagement_letter_list.*');
        $this->db->from('engagement_letter_list');
        $this->db->where('deleted != 1');
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    } 

    public function getTransactionOurServiceList()
    {
        $this->db->select('our_service_info.*');
        $this->db->from('our_service_info');
        $this->db->where('user_admin_code_id', $this->session->userdata("user_admin_code_id"));
        $this->db->where('sp_required_id', 1);
        $this->db->order_by("service_name", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionEngagementLetterAdditionalInfo($id)
    {
        $this->db->select('transaction_engagement_letter_additional_info.*');
        $this->db->from('transaction_engagement_letter_additional_info');
        $this->db->where('transaction_id', $id);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionEngagementLetter($id)
    {
        $this->db->select('transaction_engagement_letter_service_info.*, currency.currency as currency_name, unit_pricing.unit_pricing_name, firm.name as firm_name, firm.branch_name');
        $this->db->from('transaction_engagement_letter_service_info');
        $this->db->join('unit_pricing', 'unit_pricing.id = transaction_engagement_letter_service_info.unit_pricing', 'left');
        $this->db->join('currency', 'currency.id = transaction_engagement_letter_service_info.currency_id ', 'left');
        $this->db->join('firm', 'firm.id = transaction_engagement_letter_service_info.servicing_firm ', 'left');
        $this->db->where('transaction_id', $id);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionMlQuarterlyStatements($id)
    {
        $this->db->select('transaction_ml_quarterly_statements.*, transaction_master.company_code, transaction_master.client_name, transaction_master.effective_date, transaction_master.transaction_code');
        $this->db->from('transaction_ml_quarterly_statements');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_ml_quarterly_statements.transaction_id ', 'left');
        $this->db->where('transaction_id', $id);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionServiceProposal($id)
    {
        $this->db->select('transaction_service_proposal_info.*, transaction_master.company_code, transaction_master.effective_date, transaction_master.transaction_code');
        $this->db->from('transaction_service_proposal_info');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_service_proposal_info.transaction_id ', 'left');
        $this->db->where('transaction_id', $id);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionOMPGrant($id)
    {
        $this->db->select('transaction_omp_grant_info.*, transaction_master.company_code, transaction_master.effective_date, transaction_master.transaction_code');
        $this->db->from('transaction_omp_grant_info');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_omp_grant_info.transaction_id ', 'left');
        $this->db->where('transaction_id', $id);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionServiceProposalServiceInfo($id)
    {
        $this->db->select('transaction_service_proposal_service_info.*, currency.currency as currency_name, unit_pricing.unit_pricing_name, firm.name as firm_name, firm.branch_name');
        $this->db->from('transaction_service_proposal_service_info');
        $this->db->join('unit_pricing', 'unit_pricing.id = transaction_service_proposal_service_info.unit_pricing', 'left');
        $this->db->join('currency', 'currency.id = transaction_service_proposal_service_info.currency_id ', 'left');
        $this->db->join('firm', 'firm.id = transaction_service_proposal_service_info.servicing_firm ', 'left');
        $this->db->where('transaction_id', $id);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionServiceProposalSubServiceInfo($id)
    {
        $this->db->select('transaction_service_proposal_sub_service_info.*, currency.currency as currency_name, unit_pricing.unit_pricing_name');
        $this->db->from('transaction_service_proposal_sub_service_info');
        $this->db->join('unit_pricing', 'unit_pricing.id = transaction_service_proposal_sub_service_info.sub_unit_pricing', 'left');
        $this->db->join('currency', 'currency.id = transaction_service_proposal_sub_service_info.sub_currency_id ', 'left');
        $this->db->where('transaction_id', $id);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionChangeFYE($id)
    {
        $this->db->select('transaction_change_fye.*, transaction_master.company_code, transaction_master.effective_date, financial_year_period.period');
        $this->db->from('transaction_change_fye');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_change_fye.transaction_id', 'left');
        $this->db->join('financial_year_period', 'financial_year_period.id = transaction_change_fye.financial_year_period', 'left');
        $this->db->where('transaction_id', $id);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionChangeBizActivity($id)
    {
        $this->db->select('transaction_change_biz_activity.*, transaction_master.company_code, transaction_master.effective_date');
        $this->db->from('transaction_change_biz_activity');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_change_biz_activity.transaction_id ', 'left');
        $this->db->where('transaction_id', $id);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionIssueDividend($id)
    {
        $this->db->select('transaction_issue_dividend.*, transaction_master.company_code, transaction_master.effective_date, transaction_dividend_list.officer_id, transaction_dividend_list.field_type AS `officer_field_type`, transaction_dividend_list.payment_voucher_no, transaction_dividend_list.shareholder_name, transaction_dividend_list.number_of_share, transaction_dividend_list.devidend_paid, currency.currency as currency_name, nature.nature_name');
        $this->db->from('transaction_issue_dividend');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_issue_dividend.transaction_id ', 'right');
        $this->db->join('transaction_dividend_list', 'transaction_dividend_list.transaction_issue_dividend_id = transaction_issue_dividend.id ', 'left');
        $this->db->join('currency', 'currency.id = transaction_issue_dividend.currency', 'left');
        $this->db->join('nature', 'nature.id = transaction_issue_dividend.nature', 'left');
        $this->db->where('transaction_id', $id);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionIssueDirectorFee($id)
    {
        $this->db->select('transaction_issue_director_fee.*, transaction_master.company_code, transaction_master.effective_date, transaction_director_fee_list.officer_id, transaction_director_fee_list.officer_field_type, transaction_director_fee_list.identification_register_no, transaction_director_fee_list.director_name, transaction_director_fee_list.date_of_appointment, transaction_director_fee_list.currency, transaction_director_fee_list.director_fee, currency.currency as currency_name');
        $this->db->from('transaction_issue_director_fee');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_issue_director_fee.transaction_id ', 'right');
        $this->db->join('transaction_director_fee_list', 'transaction_director_fee_list.transaction_issue_director_fee_id = transaction_issue_director_fee.id ', 'left');
        $this->db->join('currency', 'currency.id = transaction_director_fee_list.currency', 'left');
        $this->db->where('transaction_id', $id);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionIncorporationSubsidiary($id)
    {
        $this->db->select('transaction_corporate_representative.*, transaction_master.company_code, transaction_master.effective_date, currency.currency as currency_name');
        $this->db->from('transaction_corporate_representative');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_corporate_representative.transaction_id ', 'right');
        $this->db->join('currency', 'currency.id = transaction_corporate_representative.currency', 'left');
        // $this->db->join('client', 'client.id = transaction_corporate_representative.client_id and client.deleted = 0', 'left');
        $this->db->where('transaction_id', $id);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionOpeningBankAccount($id)
    {
        $this->db->select('transaction_bank_account_info.*, bank_contact_person_info.banker_name, bank_contact_person_info.email, bank_contact_person_info.office_number, bank_contact_person_info.mobile_number, ');
        $this->db->from('transaction_bank_account_info');
        $this->db->join('bank_contact_person_info', 'bank_contact_person_info.id = transaction_bank_account_info.bank_contact_person_id', 'left');
        $this->db->join('bank_name', 'bank_name.id = bank_contact_person_info.bank_id', 'left');
        $this->db->where('transaction_id', $id);
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getBankName()
    {
        $this->db->select('bank_name.*');
        $this->db->from('bank_name');
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getMannerOfOperation()
    {
        $this->db->select('manner_of_operation.*');
        $this->db->from('manner_of_operation');
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    // public function getStatusCompany()
    // {
    //     $this->db->select('status.*');
    //     $this->db->from('status');
    //     $this->db->where('id != 3');
    //     $q = $this->db->get();

    //     if ($q->num_rows() > 0) {
    //         foreach (($q->result()) as $row) {
    //             $data[] = $row;
    //         }
    //         return $data;
    //     }
    //     return FALSE;
    // }

    public function getTransactionStrikeOff($id)
    {
        $this->db->select('transaction_strike_off.*, transaction_consent_for_shorter_notice.is_shorter_notice, transaction_master.company_code, transaction_master.effective_date, transaction_status_of_the_company.status_of_the_company_id, reason_for_application.reason_for_application_content, client.company_name');
        $this->db->from('transaction_strike_off');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_strike_off.transaction_id ', 'left');
        $this->db->join('transaction_status_of_the_company', 'transaction_master.id = transaction_status_of_the_company.transaction_id ', 'left');
        $this->db->join('status', 'status.id = transaction_status_of_the_company.status_of_the_company_id', 'left');
        $this->db->join('reason_for_application', 'reason_for_application.id = transaction_strike_off.reason_for_application_id', 'left');
        $this->db->join('client', 'client.company_code = transaction_master.company_code', 'left');
        $this->db->join('transaction_consent_for_shorter_notice', 'transaction_consent_for_shorter_notice.id = transaction_strike_off.shorter_notice ', 'left');
        $this->db->where('transaction_strike_off.transaction_id', $id);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $row->company_name = $this->encryption->decrypt($row->company_name);
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionChangeCompanyName($id)
    {
        $this->db->select('transaction_change_company_name.*, transaction_master.company_code, transaction_master.effective_date');
        $this->db->from('transaction_change_company_name');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_change_company_name.transaction_id ', 'left');
        $this->db->where('transaction_id', $id);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionChangeRegOfisAddress($id)
    {
        $this->db->select('transaction_change_regis_ofis_address.*, transaction_master.company_code, transaction_master.effective_date');
        $this->db->from('transaction_change_regis_ofis_address');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_change_regis_ofis_address.transaction_id ', 'left');
        $this->db->where('transaction_id', $id);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getTransactionClientController($id, $transaction_company_code)
    {
        // $this->db->select('transaction_client_controller.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.company_name as client_company_name, client.registration_no');
        // $this->db->from('transaction_client_controller');
        // $this->db->join('officer', 'officer.id = transaction_client_controller.officer_id AND officer.field_type = transaction_client_controller.field_type', 'left');
        // $this->db->join('officer_company', 'officer_company.id = transaction_client_controller.officer_id AND officer_company.field_type = transaction_client_controller.field_type', 'left');
        // $this->db->join('client', 'client.id = transaction_client_controller.officer_id AND transaction_client_controller.field_type = "client"', 'left');
        // $this->db->where('transaction_client_controller.transaction_id', $id);
        // $this->db->where('transaction_client_controller.company_code', $transaction_company_code);
        // $this->db->order_by("id", "asc");
        // $q = $this->db->get();

        $q = $this->db->query('select transaction_client_controller.*, transaction_client_controller.company_code as client_controller_company_code, transaction_client_controller.id as client_controller_id, transaction_client_controller.field_type as client_controller_field_type, officer.*, officer.address_type as officer_address_type, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.address_type as officer_company_address_type, officer_company.company_name as officer_company_company_name, client.*, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, nationality.nationality as officer_nationality_name from transaction_client_controller left join officer on transaction_client_controller.officer_id = officer.id and transaction_client_controller.field_type = officer.field_type left join officer_company on transaction_client_controller.officer_id = officer_company.id and transaction_client_controller.field_type = officer_company.field_type left join client on client.id = transaction_client_controller.officer_id AND transaction_client_controller.field_type = "client" left join nationality on nationality.id = officer.nationality where transaction_client_controller.company_code ="'.$transaction_company_code.'" AND transaction_client_controller.transaction_id = "'.$id.'" AND transaction_client_controller.deleted = 0');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                if($row->client_controller_field_type == "individual")
                {
                    $row->identification_no = $this->encryption->decrypt($row->identification_no);
                    $row->name = $this->encryption->decrypt($row->name);
                }
                elseif($row->client_controller_field_type == "company")
                {
                    $row->register_no = $this->encryption->decrypt($row->register_no);
                    $row->company_name = $this->encryption->decrypt($row->officer_company_company_name);
                }
                else
                {
                    $row->registration_no = $this->encryption->decrypt($row->registration_no);
                    $row->client_company_name = $this->encryption->decrypt($row->client_company_name);
                }
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getTransactionPreviousSecretarial($id, $transaction_company_code)
    {
        $this->db->select('transaction_previous_secretarial.*');
        $this->db->from('transaction_previous_secretarial');
        $this->db->where('transaction_id', $id);
        $this->db->where('company_code', $transaction_company_code);
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getTransactionClientFiling($id, $transaction_company_code)
    {
        $this->db->select('transaction_filing.*, financial_year_period.period');
        $this->db->from('transaction_filing');
        $this->db->join('financial_year_period', 'transaction_filing.financial_year_period = financial_year_period.id', 'left');
        $this->db->where('transaction_id', $id);
        $this->db->where('company_code', $transaction_company_code);
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getTransactionClientBilling($id, $transaction_company_code)
    {
        $q = $this->db->query('select transaction_client_billing_info.*, our_service_info.service_name, currency.currency as currency_name, unit_pricing.unit_pricing_name, firm.name as firm_name, firm.branch_name from transaction_client_billing_info left join our_service_info on transaction_client_billing_info.service = our_service_info.id left join currency on currency.id = transaction_client_billing_info.currency left join unit_pricing on unit_pricing.id = transaction_client_billing_info.unit_pricing left join firm on firm.id = transaction_client_billing_info.servicing_firm where transaction_client_billing_info.transaction_id ="'.$id.'" and transaction_client_billing_info.company_code = "'.$transaction_company_code.'" and transaction_client_billing_info.deleted = 0 order by client_billing_info_id');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getTransactionClientCapital($id){
        //$q = $this->db->get_where('client_member_share_capital',array('company_code'=>$company_code));
        $q = $this->db->query("select transaction_client_member_share_capital.*, transaction_member_shares.company_code, sum(transaction_member_shares.number_of_share) as number_of_shares, sum(transaction_member_shares.amount_share) as amount, sum(transaction_member_shares.amount_paid) as paid_up from transaction_client_member_share_capital left join transaction_member_shares on transaction_member_shares.client_member_share_capital_id = transaction_client_member_share_capital.id AND transaction_member_shares.company_code = transaction_client_member_share_capital.company_code where transaction_client_member_share_capital.transaction_id = '".$id."' group by transaction_client_member_share_capital.id");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    // public function getTransactionShareAllotment($id)
    // {
    //     $q = $this->db->query("select transaction_member_shares.*, transaction_certificate.id as cert_id, transaction_certificate.certificate_no, transaction_certificate.new_certificate_no, officer.id as officer_id, officer.field_type as officer_field_type, officer.identification_no, officer.name, officer_company.id as officer_company_id, officer_company.register_no, officer_company.field_type as officer_company_field_type, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency, client.id as client_company_id, client.registration_no, 'client' as client_company_field_type, client.company_name as client_company_name from transaction_member_shares left join transaction_certificate on transaction_certificate.officer_id = transaction_member_shares.officer_id and transaction_certificate.company_code = transaction_member_shares.company_code and transaction_certificate.field_type = transaction_member_shares.field_type and transaction_certificate.transaction_id = transaction_member_shares.transaction_id left join officer on officer.id = transaction_member_shares.officer_id and officer.field_type = transaction_member_shares.field_type left join officer_company on officer_company.id = transaction_member_shares.officer_id and officer_company.field_type = transaction_member_shares.field_type left join client_member_share_capital as share_capital on transaction_member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = 'client' where transaction_member_shares.transaction_type = 'Allotment' AND transaction_member_shares.transaction_page_id = '".$id."'ORDER BY transaction_member_shares.id");

    //     if ($q->num_rows() > 0) {
    //         foreach (($q->result()) as $row) {
    //             $data[] = $row;
    //         }
    //         return $data;
    //     }
    //     return FALSE;
    // }

    public function checkCertIdInfo($id, $transfer_from_id)
    {
        $q =  $this->db->query("select from_transfer_member.id as from_transfer_member_id, from_transaction_certificate.id as from_cert_id, from_transaction_certificate.certificate_no as from_certificate_no, to_transaction_certificate.id as to_cert_id, to_transfer_member.id as to_transfer_member_id from transaction_transfer_member_id left join transaction_member_shares as from_transfer_member on from_transfer_member.id = transaction_transfer_member_id.transfer_from_id left join officer as from_officer on from_officer.id = from_transfer_member.officer_id and from_officer.field_type = from_transfer_member.field_type left join officer_company as from_officer_company on from_officer_company.id = from_transfer_member.officer_id and from_officer_company.field_type = from_transfer_member.field_type left join client as from_client on from_client.id = from_transfer_member.officer_id and from_client.deleted = 0 and from_client.firm_id = '".$this->session->userdata('firm_id')."' and from_transfer_member.field_type = 'client' left join client_member_share_capital as from_share_capital on from_transfer_member.client_member_share_capital_id = from_share_capital.id left join sharetype as from_class on from_class.id = from_share_capital.class_id left join currency as from_currencies on from_currencies.id = from_share_capital.currency_id left join transaction_certificate as from_transaction_certificate on from_transaction_certificate.officer_id = from_transfer_member.officer_id and from_transaction_certificate.company_code = from_transfer_member.company_code and from_transaction_certificate.field_type = from_transfer_member.field_type and from_transaction_certificate.transaction_id = from_transfer_member.transaction_id left join transaction_member_shares as to_transfer_member on to_transfer_member.id = transaction_transfer_member_id.transfer_to_id left join officer as to_officer on to_officer.id = to_transfer_member.officer_id and to_officer.field_type = to_transfer_member.field_type left join officer_company as to_officer_company on to_officer_company.id = to_transfer_member.officer_id and to_officer_company.field_type = to_transfer_member.field_type left join client as to_client on to_client.id = to_transfer_member.officer_id and to_client.deleted = 0 and to_client.firm_id = '".$this->session->userdata('firm_id')."' and to_transfer_member.field_type = 'client' left join transaction_certificate as to_transaction_certificate on to_transaction_certificate.officer_id = to_transfer_member.officer_id and to_transaction_certificate.company_code = to_transfer_member.company_code and to_transaction_certificate.field_type = to_transfer_member.field_type and to_transaction_certificate.transaction_id = to_transfer_member.transaction_id where transaction_transfer_member_id.transaction_id = '".$id."' and transaction_transfer_member_id.transfer_from_id = '".$transfer_from_id."'");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    public function getTransactionClientTransferMemberInfo($id)
    {
        $q =  $this->db->query("select transaction_transfer_member_id.id as transaction_transfer_member_id, client.company_name as client_company_name, transaction_transfer_member_id.transaction_id as transaction_id, from_officer.id as from_officer_id, from_officer.field_type as from_officer_field_type, from_officer.identification_no as from_officer_identification_no, from_officer.name as from_officer_name, from_officer_company.id as from_officer_company_id, from_officer_company.register_no as from_officer_company_register_no, from_officer_company.field_type as from_officer_company_field_type, from_officer_company.company_name as from_officer_company_name, from_client.id as from_client_company_id, from_client.registration_no as from_client_regis_no, 'client' as from_client_company_field_type, from_client.company_name as from_client_company_name, from_share_capital.id as share_capital_id, from_share_capital.class_id, from_share_capital.other_class, from_share_capital.currency_id, from_class.sharetype, from_currencies.currency, from_transaction_certificate.id as from_cert_id, from_transaction_certificate.certificate_no as from_certificate_no, from_transaction_certificate.new_certificate_no as from_new_certificate_no, from_transfer_member.id as from_transfer_member_id, from_transfer_member.number_of_share as from_number_of_share, from_transfer_member.consideration as from_consideration, to_officer.id as to_officer_id, to_officer.field_type as to_officer_field_type, to_officer.identification_no as to_officer_identification_no, to_officer.name as to_officer_name, to_officer_company.id as to_officer_company_id, to_officer_company.register_no as to_officer_company_register_no, to_officer_company.field_type as to_officer_company_field_type, to_officer_company.company_name as to_officer_company_name, to_client.id as to_client_company_id, to_client.registration_no as to_client_regis_no, 'client' as to_client_company_field_type, to_client.company_name as to_client_company_name, to_transaction_certificate.id as to_cert_id, to_transaction_certificate.certificate_no as to_certificate_no, to_transaction_certificate.new_certificate_no as to_new_certificate_no, to_transfer_member.id as to_transfer_member_id, to_transfer_member.number_of_share as to_number_of_share, from_transfer_member.consideration as from_consideration from transaction_transfer_member_id left join transaction_member_shares as from_transfer_member on from_transfer_member.id = transaction_transfer_member_id.transfer_from_id left join officer as from_officer on from_officer.id = from_transfer_member.officer_id and from_officer.field_type = from_transfer_member.field_type left join officer_company as from_officer_company on from_officer_company.id = from_transfer_member.officer_id and from_officer_company.field_type = from_transfer_member.field_type left join client as from_client on from_client.id = from_transfer_member.officer_id and from_client.deleted = 0 and from_transfer_member.field_type = 'client' left join client_member_share_capital as from_share_capital on from_transfer_member.client_member_share_capital_id = from_share_capital.id left join sharetype as from_class on from_class.id = from_share_capital.class_id left join currency as from_currencies on from_currencies.id = from_share_capital.currency_id left join transaction_certificate as from_transaction_certificate on from_transaction_certificate.officer_id = from_transfer_member.officer_id and from_transaction_certificate.company_code = from_transfer_member.company_code and from_transaction_certificate.field_type = from_transfer_member.field_type and from_transaction_certificate.transaction_id = from_transfer_member.transaction_id left join transaction_member_shares as to_transfer_member on to_transfer_member.id = transaction_transfer_member_id.transfer_to_id left join officer as to_officer on to_officer.id = to_transfer_member.officer_id and to_officer.field_type = to_transfer_member.field_type left join officer_company as to_officer_company on to_officer_company.id = to_transfer_member.officer_id and to_officer_company.field_type = to_transfer_member.field_type left join client as to_client on to_client.id = to_transfer_member.officer_id and to_client.deleted = 0 and to_transfer_member.field_type = 'client' left join transaction_certificate as to_transaction_certificate on to_transaction_certificate.officer_id = to_transfer_member.officer_id and to_transaction_certificate.company_code = to_transfer_member.company_code and to_transaction_certificate.field_type = to_transfer_member.field_type and to_transaction_certificate.transaction_id = to_transfer_member.transaction_id left join transaction_master on transaction_master.id = transaction_transfer_member_id.transaction_id left join client on client.company_code = transaction_master.company_code where transaction_transfer_member_id.transaction_id = '".$id."'");
        // and from_client.firm_id = '".$this->session->userdata('firm_id')."'
        // and to_client.firm_id = '".$this->session->userdata('firm_id')."'
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $row->client_company_name = $this->encryption->decrypt($row->client_company_name);
                if($row->from_officer_field_type == "individual")
                {
                    $row->from_officer_identification_no = $this->encryption->decrypt($row->from_officer_identification_no);
                    $row->from_officer_name = $this->encryption->decrypt($row->from_officer_name);
                }
                if($row->from_officer_company_field_type == "company")
                {
                    $row->from_officer_company_register_no = $this->encryption->decrypt($row->from_officer_company_register_no);
                    $row->from_officer_company_name = $this->encryption->decrypt($row->from_officer_company_name);
                }
                if($row->from_client_company_field_type == "client")
                {   
                    if($row->from_client_regis_no != null)
                    {
                        $row->from_client_regis_no = $this->encryption->decrypt($row->from_client_regis_no);
                    }
                    if($row->from_client_company_name != null)
                    {
                        $row->from_client_company_name = $this->encryption->decrypt($row->from_client_company_name);
                    }
                }
                if($row->to_officer_field_type == "individual")
                {
                    $row->to_officer_identification_no = $this->encryption->decrypt($row->to_officer_identification_no);
                    $row->to_officer_name = $this->encryption->decrypt($row->to_officer_name);
                }
                if($row->to_officer_company_field_type == "company")
                {
                    $row->to_officer_company_register_no = $this->encryption->decrypt($row->to_officer_company_register_no);
                    $row->to_officer_company_name = $this->encryption->decrypt($row->to_officer_company_name);
                }
                if($row->to_client_company_field_type == "client")
                {
                    if($row->to_client_regis_no != null)
                    {
                        $row->to_client_regis_no = $this->encryption->decrypt($row->to_client_regis_no);
                    }
                    if($row->to_client_company_name != null)
                    {
                        $row->to_client_company_name = $this->encryption->decrypt($row->to_client_company_name);
                    }
                }
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    public function getTransactionClientTransferMember($id, $transaction_share_member_id)
    {
        $transfer_to_id = $this->db->query("select transfer_to_id from transaction_transfer_member_id where transaction_transfer_member_id.transaction_id = '".$id."' AND transaction_transfer_member_id.transfer_from_id = '".$transaction_share_member_id."'");

         if ($transfer_to_id->num_rows() > 0) {
            foreach (($transfer_to_id->result_array()) as $row) {
                $transaction_share_member_to_id[] = $row["transfer_to_id"];
            }
        }

        //echo json_encode($transaction_share_member_to_id);

        $q = $this->db->query("select transaction_member_shares.*, transaction_certificate.id as cert_id, transaction_certificate.certificate_no, transaction_certificate.new_certificate_no, transaction_certificate.previous_certificate_id, officer.id as person_officer_id, officer.field_type as officer_field_type, officer.identification_no, officer.name, officer_company.id as officer_company_id, officer_company.register_no, officer_company.field_type as officer_company_field_type, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency, client.id as client_company_id, client.registration_no, 'client' as client_company_field_type, client.company_name as client_company_name from transaction_member_shares left join transaction_certificate on transaction_certificate.officer_id = transaction_member_shares.officer_id and transaction_certificate.company_code = transaction_member_shares.company_code and transaction_certificate.field_type = transaction_member_shares.field_type and transaction_certificate.transaction_id = transaction_member_shares.transaction_id left join officer on officer.id = transaction_member_shares.officer_id and officer.field_type = transaction_member_shares.field_type left join officer_company on officer_company.id = transaction_member_shares.officer_id and officer_company.field_type = transaction_member_shares.field_type left join client_member_share_capital as share_capital on transaction_member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = transaction_member_shares.officer_id and client.deleted = 0 and transaction_member_shares.field_type = 'client' where transaction_member_shares.transaction_type = 'Transfer' AND ((transaction_member_shares.transaction_page_id = '".$id."' AND transaction_member_shares.id = '".$transaction_share_member_id."') or (transaction_member_shares.transaction_page_id = '".$id."' AND transaction_member_shares.id IN (".implode(',',$transaction_share_member_to_id)."))) ORDER BY transaction_member_shares.id");
        // and client.firm_id = '".$this->session->userdata('firm_id')."'
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
            return $data;
        }
        return FALSE;
    }

    public function getTransactionAuditorMeetingDate($id)
    {
        $q = $this->db->query("select * from transaction_appoint_auditor_date where transaction_master_id = '".$id."'");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionMeetingDate($id)
    {
        $q = $this->db->query("select * from transaction_meeting_date where transaction_master_id = '".$id."'");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getFollowUpHistory($id, $transaction_company_code)
    {
        $q = $this->db->query("select follow_up_history.*, users.first_name, users.last_name from follow_up_history left join users on users.id = follow_up_history.follow_by where follow_up_history.transaction_master_id = '".$id."' AND follow_up_history.company_code = '".$transaction_company_code."' AND follow_up_history.deleted = 0 order by id");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionClientMember($id, $transaction_company_code)
    {
        $q = $this->db->query("select transaction_member_shares.*, transaction_certificate.id as cert_id, transaction_certificate.certificate_no, transaction_certificate.new_certificate_no, officer.id as officer_id, officer.field_type as officer_field_type, officer.identification_no, officer.name, officer_company.id as officer_company_id, officer_company.register_no, officer_company.field_type as officer_company_field_type, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency, client.id as client_company_id, client.registration_no, 'client' as client_company_field_type, client.company_name as client_company_name from transaction_member_shares left join transaction_certificate on transaction_certificate.officer_id = transaction_member_shares.officer_id and transaction_certificate.company_code = transaction_member_shares.company_code and transaction_certificate.field_type = transaction_member_shares.field_type and transaction_certificate.transaction_id = transaction_member_shares.transaction_id left join officer on officer.id = transaction_member_shares.officer_id and officer.field_type = transaction_member_shares.field_type left join officer_company on officer_company.id = transaction_member_shares.officer_id and officer_company.field_type = transaction_member_shares.field_type left join transaction_client_member_share_capital as share_capital on transaction_member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = transaction_member_shares.officer_id and client.deleted = 0 and transaction_member_shares.field_type = 'client' where transaction_member_shares.transaction_type = 'Allotment' AND transaction_member_shares.transaction_page_id = '".$id."' AND transaction_member_shares.company_code = '".$transaction_company_code."' ORDER BY transaction_member_shares.id");
        // and client.firm_id = '".$this->session->userdata('firm_id')."'
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
            return $data;
        }
        return FALSE;
    }

    public function get_exemption()
    {
        $q = $this->db->query("select * from exemption");

        //echo json_encode($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_regis_controller_is_kept()
    {
        $q = $this->db->query("select * from regis_controller_is_kept");

        //echo json_encode($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_regis_nominee_dir_is_kept()
    {
        $q = $this->db->query("select * from regis_nominee_dir_is_kept");

        //echo json_encode($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_client_signing_info($company_code)
    {   
        if ($registra_no)
        {
            $q = $this->db->query('select client_signing_info.* from client left join client_signing_info on client_signing_info.company_code = client.company_code where client.company_code ="'.$company_code.'" AND client.firm_id = "'.$this->session->userdata('firm_id').'" AND client.deleted = 0');

            if ($q->num_rows() > 0) {
                //echo json_encode($q->result()[0]->id);
                $this->session->set_userdata('chairman', $q->result()[0]->chairman);
                $this->session->set_userdata('director_signature_1', $q->result()[0]->director_signature_1);
                $this->session->set_userdata('director_signature_2', $q->result()[0]->director_signature_2);

                foreach (($q->result()) as $row) {
                    $data[] = $row;

                    
                }
                //echo($data);
                return $data;
            }
        }
        return false;
    }

    public function get_company_type()
    {
        $q = $this->db->query("select * from company_type");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_first_agm()
    {
        $q = $this->db->query("select * from transaction_first_agm");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_xbrl_list()
    {
        $q = $this->db->query("select * from xbrl_list");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_currency_list()
    {
        $result_currency = $this->db->query("select * from currency order by currency");
        if ($result_currency->num_rows() > 0) {
            $result_currency = $result_currency->result_array();
            for($j = 0; $j < count($result_currency); $j++)
            {
                $res[$result_currency[$j]['id']] = $result_currency[$j]['currency'];
            }
            
            return $res;
        }
        return FALSE;
    }

    public function get_require_hold_agm_list()
    {
        $q = $this->db->query("select * from require_hold_agm_list");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_agm_share_transfer()
    {
        $q = $this->db->query("select * from transaction_agm_share_transfer");

        //echo json_encode($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_consent_for_shorter_notice()
    {
        $q = $this->db->query("select * from transaction_consent_for_shorter_notice");

        //echo json_encode($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_activity_status()
    {
        $q = $this->db->query("select * from activity_status");

        //echo json_encode($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_solvency_status()
    {
        $q = $this->db->query("select * from solvency_status");

        //echo json_encode($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_small_company()
    {
        $q = $this->db->query("select * from small_company");

        //echo json_encode($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_epc_status()
    {
        $q = $this->db->query("select * from transaction_epc_status");

        //echo json_encode($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function check_have_share_transfer($company_code)
    {
        $q = $this->db->query("select filing.id, filing.ar_filing_date, client.incorporation_date from client left join filing on filing.company_code = client.company_code where client.company_code = '".$company_code."' AND client.firm_id = '".$this->session->userdata('firm_id')."' AND client.deleted = 0 order by filing.id DESC LIMIT 2");

        $q = $q->result_array();

        if($q[1]['ar_filing_date'] == "" || $q[1]['ar_filing_date'] == null)
        {
            $query = $this->db->query('select member_shares.*, member_shares.number_of_share as number_of_share, member_shares.amount_share as amount_share, member_shares.no_of_share_paid as no_of_share_paid, member_shares.amount_paid as amount_paid, member_shares.transaction_type, DATE_FORMAT(STR_TO_DATE(member_shares.transaction_date, "%d/%m/%Y"), "%d/%m/%Y") as transaction_date, STR_TO_DATE(member_shares.transaction_date, "%d/%m/%Y") as trans_date from member_shares left join client on client.company_code = member_shares.company_code where STR_TO_DATE(member_shares.transaction_date,"%d/%m/%Y") >= STR_TO_DATE("'. $q[0]['incorporation_date']. '","%d/%m/%Y") AND client.registration_no = "'.$registration_no.'" AND client.firm_id = "'.$this->session->userdata('firm_id').'" AND member_shares.transaction_type = "Transfer" AND client.deleted = 0');
        }
        else
        {
            $query = $this->db->query('select member_shares.*, member_shares.number_of_share as number_of_share, member_shares.amount_share as amount_share, member_shares.no_of_share_paid as no_of_share_paid, member_shares.amount_paid as amount_paid, member_shares.transaction_type, DATE_FORMAT(STR_TO_DATE(member_shares.transaction_date, "%d/%m/%Y"), "%d/%m/%Y") as transaction_date, STR_TO_DATE(member_shares.transaction_date, "%d/%m/%Y") as trans_date from member_shares left join client on client.company_code = member_shares.company_code where STR_TO_DATE(member_shares.transaction_date,"%d/%m/%Y") >= STR_TO_DATE("'. $q[1]['ar_filing_date']. '","%d %M %Y") AND client.registration_no = "'.$registration_no.'" AND client.firm_id = "'.$this->session->userdata('firm_id').'" AND member_shares.transaction_type = "Transfer" AND client.deleted = 0');
        }

        if ($query->num_rows() > 0) {
            foreach (($query->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function check_client_info($company_code)
    {
        $q = $this->db->query("select client.*, filing.year_end, financial_year_period.period, client_signing_info.director_signature_1 from client left join filing on filing.id = (select MAX(id) as filing_id from filing where filing.company_code = client.company_code) left join financial_year_period on financial_year_period.id = filing.financial_year_period_id left join client_signing_info on client_signing_info.company_code = client.company_code where client.company_code = '".$company_code."' and client.deleted = 0");
        // and client.firm_id = '".$this->session->userdata('firm_id')."'

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                if($row->registration_no != "")
                {
                    $row->registration_no = $this->encryption->decrypt($row->registration_no);
                }
                $row->company_name = $this->encryption->decrypt($row->company_name);
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function check_filing_info($company_code)
    {
        //$registration_no = $_POST["registration_no"];

        $q = $this->db->query("select client.*, filing.year_end, financial_year_period.period from client left join filing on filing.id = (select MAX(id) as filing_id from filing where filing.company_code = client.company_code) left join financial_year_period on financial_year_period.id = filing.financial_year_period_id where client.company_code = '".$company_code."' AND client.deleted = 0"); // AND client.firm_id = '".$this->session->userdata('firm_id')."'

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    public function check_is_first_agm($company_code)
    {
        //$registration_no = $_POST["registration_no"];

        $q = $this->db->query("select filing.id, filing.year_end, filing.agm from client left join filing on filing.company_code = client.company_code where client.company_code = '".$company_code."' AND client.firm_id = '".$this->session->userdata('firm_id')."' AND client.deleted = 0 ORDER BY filing.id");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    public function get_all_audited_financial_statement()
    {
        $q = $this->db->query("select * from audited_financial_statement");

        //echo json_encode($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionClientSigningInfo($id, $transaction_company_code)
    {   
        if ($id)
        {
            $q = $this->db->query('select * from transaction_client_signing_info where transaction_id ="'.$id.'" AND  company_code ="'.$transaction_company_code.'"');


            if ($q->num_rows() > 0) {
                //echo json_encode($q->result()[0]->id);
                $this->session->set_userdata('transaction_chairman', $q->result()[0]->chairman);
                $this->session->set_userdata('transaction_director_signature_1', $q->result()[0]->director_signature_1);
                $this->session->set_userdata('transaction_director_signature_2', $q->result()[0]->director_signature_2);

                $transaction_client_signing_info_result = $q->result_array();

                $chairman_result_info = (explode("-",$transaction_client_signing_info_result[0]["chairman"]));

                if($chairman_result_info[1] == "individual")
                {
                    $officer_result = $this->db->query("select * from officer where id='".$chairman_result_info[0]."' AND field_type='".$chairman_result_info[1]."'");

                    $officer_result = $officer_result->result_array();

                    $name = $this->encryption->decrypt($officer_result[0]["name"]);
                }
                elseif($chairman_result_info[1] == "corp_rep")
                {
                    $officer_company_result = $this->db->query("select * from corporate_representative where id='".$chairman_result_info[0]."'");

                    $officer_company_result = $officer_company_result->result_array();

                    $name = $officer_company_result[0]["name_of_corp_rep"];
                }

                $director_result_1 = $this->db->query("select officer.* from transaction_client_officers left join officer on officer.id = transaction_client_officers.officer_id and officer.field_type = transaction_client_officers.field_type where transaction_client_officers.id='".$transaction_client_signing_info_result[0]['director_signature_1']."'");

                $director_result_1 = $director_result_1->result_array();

                $director_name_1 = $this->encryption->decrypt($director_result_1[0]["name"]);

                $director_result_2 = $this->db->query("select officer.* from transaction_client_officers left join officer on officer.id = transaction_client_officers.officer_id and officer.field_type = transaction_client_officers.field_type where transaction_client_officers.id='".$transaction_client_signing_info_result[0]['director_signature_2']."'");

                $director_result_2 = $director_result_2->result_array();

                $director_name_2 = $this->encryption->decrypt($director_result_2[0]["name"]);

                foreach (($q->result()) as $row) {
                    $data[] = $row;

                    
                }
                $data[0]->chairman_name = $name;
                $data[0]->director_name_1 = $director_name_1;
                $data[0]->director_name_2 = $director_name_2;
                //echo($data);
                return $data;
            }
        }
        return false;
    }

    public function getAllFirmInfo(){

        $this->db->select('firm.*, firm_telephone.telephone, firm_fax.fax, firm_email.email, user_firm.user_id, user_firm.default_company, user_firm.in_use')
            ->from('firm')
            ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
            ->join('firm_telephone', 'firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1', 'left')
            ->join('firm_fax', 'firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1', 'left')
            ->join('firm_email', 'firm_email.firm_id = firm.id AND firm_email.primary_email = 1', 'left')
            ->where('user_firm.user_id = '.$this->session->userdata('user_id'));

        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getClientContactInfo($company_code)
    {

        $q = $this->db->query("select client_contact_info.*, GROUP_CONCAT(DISTINCT CONCAT(client_contact_info_phone.id,',', client_contact_info_phone.phone, ',', client_contact_info_phone.primary_phone)SEPARATOR ';') AS 'client_contact_info_phone', GROUP_CONCAT(DISTINCT CONCAT(client_contact_info_email.id,',', client_contact_info_email.email, ',', client_contact_info_email.primary_email)SEPARATOR ';') AS 'client_contact_info_email' from client_contact_info LEFT JOIN client_contact_info_phone ON client_contact_info_phone.client_contact_info_id = client_contact_info.id LEFT JOIN client_contact_info_email ON client_contact_info_email.client_contact_info_id = client_contact_info.id where client_contact_info.company_code = '".$company_code."'");

        if($q->result()[0]->client_contact_info_phone != null)
        {
            $q->result()[0]->client_contact_info_phone = explode(';', $q->result()[0]->client_contact_info_phone);
        }

        if($q->result()[0]->client_contact_info_email != null)
        {
            $q->result()[0]->client_contact_info_email = explode(';', $q->result()[0]->client_contact_info_email);
        }

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;

                
            }
            //echo($data);
            return $data;
        }
        
        return false;
    }

    public function getTransactionClientContactInfo($id, $transaction_company_code)
    {
        if ($id)
        {
            $q = $this->db->query("select transaction_client_contact_info.*, GROUP_CONCAT(DISTINCT CONCAT(transaction_client_contact_info_phone.id,',', transaction_client_contact_info_phone.phone, ',', transaction_client_contact_info_phone.primary_phone)SEPARATOR ':') AS 'transaction_client_contact_info_phone', GROUP_CONCAT(DISTINCT CONCAT(transaction_client_contact_info_email.id,',', transaction_client_contact_info_email.email, ',', transaction_client_contact_info_email.primary_email)SEPARATOR ':') AS 'transaction_client_contact_info_email' from transaction_client_contact_info LEFT JOIN transaction_client_contact_info_phone ON transaction_client_contact_info_phone.client_contact_info_id = transaction_client_contact_info.id LEFT JOIN transaction_client_contact_info_email ON transaction_client_contact_info_email.client_contact_info_id = transaction_client_contact_info.id where transaction_client_contact_info.transaction_id ='".$id."' and transaction_client_contact_info.company_code = '".$transaction_company_code."'");
            //echo json_encode($q->result_array());
            if($q->result()[0]->transaction_client_contact_info_phone != null)
            {
                $q->result()[0]->transaction_client_contact_info_phone = explode(':', $q->result()[0]->transaction_client_contact_info_phone);
            }

            if($q->result()[0]->transaction_client_contact_info_email != null)
            {
                $q->result()[0]->transaction_client_contact_info_email = explode(':', $q->result()[0]->transaction_client_contact_info_email);
            }

            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;

                    
                }
                //echo($data);
                return $data;
            }
        }
        return false;
    }

    public function getTransactionClientReminderInfo($id, $transaction_company_code)
    {
        if ($id)
        {
            $q = $this->db->query("select  transaction_client_setup_reminder.*, reminder_tag.reminder_tag_name from  transaction_client_setup_reminder left join reminder_tag on reminder_tag.id =  transaction_client_setup_reminder.selected_reminder where transaction_client_setup_reminder.transaction_id = '".$id."' and transaction_client_setup_reminder.company_code = '".$transaction_company_code."'");

            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;

                    
                }
                //echo($data);
                return $data;
            }
        }
        return false;

    }

    public function get_all_pending_doc($transaction_master_id, $id, $transaction_task_id = NULL)
    {
        if($transaction_task_id == '29' || $transaction_task_id == '30')
        {
            $this->db->select('transaction_pending_documents.*, users.first_name, users.last_name');
            $this->db->from('transaction_pending_documents');
        }
        else
        {
            $this->db->select('transaction_pending_documents.*, transaction_client.company_name, users.first_name, users.last_name');
            $this->db->from('transaction_pending_documents');
            $this->db->join('transaction_client', 'transaction_client.id = transaction_pending_documents.client_id', 'left');
        }
        $this->db->join('users', 'users.id = transaction_pending_documents.created_by', 'left');
        $this->db->order_by('transaction_pending_documents.id', 'asc');
        if($id != NULL)
        {
            $this->db->where('transaction_pending_documents.client_id', $id);
        }
        $this->db->where('transaction_pending_documents.transaction_id', $transaction_master_id);
        $this->db->where('transaction_pending_documents.firm_id', $this->session->userdata("firm_id"));
        //$this->db->where('transaction_pending_documents.received_on = ""');
        $p = $this->db->get();



        if ($p->num_rows() > 0) {
            foreach (($p->result()) as $row) {
                $data[] = $row;
            }

            return $data;
            
        }
        return FALSE;
    }

    public function get_transaction_engagement_letter_status()
    {
        $this->db->select('*');
        $this->db->from('transaction_engagement_letter_status');
        $p = $this->db->get();

        if ($p->num_rows() > 0) {
            foreach (($p->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
        }
        return FALSE;
    }

    public function get_transaction_service_proposal_status()
    {
        $this->db->select('*');
        $this->db->from('transaction_service_proposal_status');
        $p = $this->db->get();

        if ($p->num_rows() > 0) {
            foreach (($p->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
        }
        return FALSE;
    }

    public function get_transaction_service_status()
    {
        $this->db->select('*');
        $this->db->from('transaction_service_status');
        $p = $this->db->get();

        if ($p->num_rows() > 0) {
            foreach (($p->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
        }
        return FALSE;
    }

    public function get_follow_up_outcome()
    {
        $this->db->select('*');
        $this->db->from('follow_up_outcome');
        $p = $this->db->get();

        if ($p->num_rows() > 0) {
            foreach (($p->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
        }
        return FALSE;
    }

    public function get_follow_up_action()
    {
        $this->db->select('*');
        $this->db->from('follow_up_action');
        $p = $this->db->get();

        if ($p->num_rows() > 0) {
            foreach (($p->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
        }
        return FALSE;
    }

    public function get_credit_note_no()
    {
        $query_credit_note_no = $this->db->query("SELECT credit_note_no FROM credit_note where credit_note.id = (SELECT max(credit_note_id) FROM billing_credit_note_record where billing_credit_note_record.firm_id = '".$this->session->userdata("firm_id")."')");

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

        return $credit_note_no;
    }

    public function get_billing_data($transaction_master_id)
    {
        $query_billing_data = $this->db->query("SELECT billing.* FROM transaction_master_with_billing LEFT JOIN billing on billing.id = transaction_master_with_billing.billing_id WHERE transaction_master_with_billing.transaction_master_id = '".$transaction_master_id."'");

        if ($query_billing_data->num_rows() > 0) 
        {
            $query_billing_data_array = $query_billing_data->result_array();
        }
        else
        {
            $query_billing_data_array = array();
        }
        return $query_billing_data_array;
    }
	
    public function detect_client_code($company_name)
    {
        $firstCharacter = strtoupper(substr($company_name, 0, 1));

        $q = $this->db->query("SELECT MAX(CAST(SUBSTRING(client_code, -5) AS UNSIGNED)) as latest_client_code FROM client WHERE client_code LIKE '".$firstCharacter."%' AND deleted = 0 ORDER BY latest_client_code DESC LIMIT 1");

        $q = $q->result_array();

        $num_padded = sprintf("%05d", $q[0]["latest_client_code"] + 1);

        return $firstCharacter.$num_padded;
    }

    public function detect_previous_client_code($company_name)
    {
        $firstCharacter = strtoupper(substr($company_name, 0, 1));

        $q = $this->db->query("SELECT MAX(CAST(SUBSTRING(client_code, -5) AS UNSIGNED)) as latest_client_code FROM client WHERE client_code LIKE '".$firstCharacter."%' AND deleted = 0 ORDER BY latest_client_code DESC LIMIT 1");

        $q = $q->result_array();

        $num_padded = sprintf("%05d", $q[0]["latest_client_code"]);

        return $firstCharacter.$num_padded;
    }

    public function getCurrentClientNomineeDirector($company_code, $transaction_id)
    {
        if ($company_code)
        {
            $q = $this->db->query('select 
                    client_nominee_director.*, 
                    client_nominee_director.company_code as client_nominee_director_company_code, 
                    client_nominee_director.id as client_nominee_director_id, 
                    nd_officer.name as nd_officer_name, 
                    nomi_officer.*,
                    nomi_officer.address_type as officer_address_type,  
                    nomi_officer.unit_no1 as nomi_officer_unit_no1, 
                    nomi_officer.unit_no2 as nomi_officer_unit_no2,
                    officer_company.*,
                    officer_company.address_type as officer_company_address_type, 
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
                    left join company_type on company_type.id = client.company_type where client_nominee_director.company_code ="'.$company_code.'" and client_nominee_director.deleted = 0 and client_nominee_director.date_of_cessation = ""');

            if ($q->num_rows() > 0) 
            {
                $current_q = $q->result_array();

                $latest_q = $this->db->query('select 
                                transaction_client_nominee_director.*, 
                                transaction_client_nominee_director.company_code as client_nominee_director_company_code, 
                                transaction_client_nominee_director.id as client_nominee_director_id, 
                                nd_officer.name as nd_officer_name, 
                                nomi_officer.*,
                                nomi_officer.address_type as officer_address_type,  
                                nomi_officer.unit_no1 as nomi_officer_unit_no1, 
                                nomi_officer.unit_no2 as nomi_officer_unit_no2,
                                officer_company.*, 
                                officer_company.address_type as officer_company_address_type,
                                officer_company.company_name as officer_company_company_name, 
                                client.*, client.company_name as client_company_name, 
                                client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, 
                                nationality.nationality as nomi_officer_nationality_name,
                                company_type.company_type as client_company_type 
                                from transaction_client_nominee_director left join officer as nd_officer on transaction_client_nominee_director.nd_officer_id = nd_officer.id and transaction_client_nominee_director.nd_officer_field_type = nd_officer.field_type left join officer as nomi_officer on transaction_client_nominee_director.nomi_officer_id = nomi_officer.id and transaction_client_nominee_director.nomi_officer_field_type = nomi_officer.field_type left join officer_company on transaction_client_nominee_director.nomi_officer_id = officer_company.id and transaction_client_nominee_director.nomi_officer_field_type = officer_company.field_type left join client on client.id = transaction_client_nominee_director.nomi_officer_id AND transaction_client_nominee_director.nomi_officer_field_type = "client" left join nationality on nationality.id = nomi_officer.nationality 
                                    left join company_type on company_type.id = client.company_type where transaction_client_nominee_director.company_code ="'.$company_code.'" AND transaction_client_nominee_director.transaction_id = "'.$transaction_id.'"');

                $latest_q = $latest_q->result_array();

                if(count($latest_q) > 0)
                {
                    foreach ($current_q as $i => $defArr) {
                        foreach ($latest_q as $j => $dayArr) {

                            if ($dayArr['nd_officer_id'] == $defArr['nd_officer_id'] && $dayArr['nd_officer_field_type'] == $defArr['nd_officer_field_type'] && $dayArr['nomi_officer_id'] == $defArr['nomi_officer_id'] && $dayArr['nomi_officer_field_type'] == $defArr['nomi_officer_field_type']) {
                                $current_q[$i] = $latest_q[$j];
                            }
                            else
                            {
                                $current_q[$i]['transaction_id'] = "";
                            }

                            $current_q[$i]['nd_officer_name'] = $this->encryption->decrypt($defArr['nd_officer_name']);
                            
                            if($current_q[$i]['nomi_officer_field_type'] == "individual")
                            {
                                $current_q[$i]['identification_no'] = $this->encryption->decrypt($defArr['identification_no']);
                                $current_q[$i]['name'] = $this->encryption->decrypt($defArr['name']);
                            }
                            elseif($current_q[$i]['nomi_officer_field_type'] == "company")
                            {
                                $current_q[$i]['register_no'] = $this->encryption->decrypt($defArr['register_no']);
                                $current_q[$i]['officer_company_company_name'] = $this->encryption->decrypt($defArr['officer_company_company_name']);
                            }
                            elseif($current_q[$i]['nomi_officer_field_type'] == "client")
                            {
                                $current_q[$i]['registration_no'] = $this->encryption->decrypt($defArr['registration_no']);
                                $current_q[$i]['client_company_name'] = $this->encryption->decrypt($defArr['client_company_name']);
                            }
                        }
                        if($current_q[$i]['deleted'] == 1)
                        {
                            unset($current_q[$i]);
                        }
                    }
                    //print_r($current_q);
                    return array_values($current_q);
                }
                else
                {
                    foreach ($current_q as $i => $defArr) {
                        $current_q[$i]['nd_officer_name'] = $this->encryption->decrypt($defArr['nd_officer_name']);
                        if($current_q[$i]['nomi_officer_field_type'] == "individual")
                        {
                            $current_q[$i]['identification_no'] = $this->encryption->decrypt($defArr['identification_no']);
                            $current_q[$i]['name'] = $this->encryption->decrypt($defArr['name']);
                        }
                        elseif($current_q[$i]['nomi_officer_field_type'] == "company")
                        {
                            $current_q[$i]['register_no'] = $this->encryption->decrypt($defArr['register_no']);
                            $current_q[$i]['officer_company_company_name'] = $this->encryption->decrypt($defArr['officer_company_company_name']);
                        }
                        elseif($current_q[$i]['nomi_officer_field_type'] == "client")
                        {
                            $current_q[$i]['registration_no'] = $this->encryption->decrypt($defArr['registration_no']);
                            $current_q[$i]['client_company_name'] = $this->encryption->decrypt($defArr['client_company_name']);
                        }
                    }
                    return $current_q;
                }
            }
            return false;
        }
        return false;
    }

    public function getLatestClientNomineeDirector($company_code, $transaction_id)
    {
        if ($company_code)
        {
            $latest_q = $this->db->query('select 
                                transaction_client_nominee_director.*, 
                                transaction_client_nominee_director.company_code as client_nominee_director_company_code, 
                                transaction_client_nominee_director.id as client_nominee_director_id,
                                nd_officer.identification_no as nd_officer_identification_no, 
                                nd_officer.name as nd_officer_name, 
                                nomi_officer.*, 
                                nomi_officer.address_type as officer_address_type, 
                                nomi_officer.unit_no1 as nomi_officer_unit_no1, 
                                nomi_officer.unit_no2 as nomi_officer_unit_no2,
                                officer_company.*,
                                officer_company.address_type as officer_company_address_type,  
                                officer_company.company_name as officer_company_company_name, 
                                client.*, 
                                client.company_name as client_company_name, 
                                client.unit_no1 as client_unit_no1, 
                                client.unit_no2 as client_unit_no2, 
                                nationality.nationality as nomi_officer_nationality_name,
                                company_type.company_type as client_company_type 
                                from transaction_client_nominee_director left join officer as nd_officer on transaction_client_nominee_director.nd_officer_id = nd_officer.id and transaction_client_nominee_director.nd_officer_field_type = nd_officer.field_type left join officer as nomi_officer on transaction_client_nominee_director.nomi_officer_id = nomi_officer.id and transaction_client_nominee_director.nomi_officer_field_type = nomi_officer.field_type left join officer_company on transaction_client_nominee_director.nomi_officer_id = officer_company.id and transaction_client_nominee_director.nomi_officer_field_type = officer_company.field_type left join client on client.id = transaction_client_nominee_director.nomi_officer_id AND transaction_client_nominee_director.nomi_officer_field_type = "client" left join nationality on nationality.id = nomi_officer.nationality 
                                    left join company_type on company_type.id = client.company_type where transaction_client_nominee_director.company_code ="'.$company_code.'" AND transaction_client_nominee_director.transaction_id = "'.$transaction_id.'" AND transaction_client_nominee_director.deleted = 0');

            if ($latest_q->num_rows() > 0) 
            {
                $latest_q = $latest_q->result_array();

                $q = $this->db->query('select 
                        client_nominee_director.*, 
                        client_nominee_director.company_code as client_nominee_director_company_code, 
                        client_nominee_director.id as client_nominee_director_id,
                        nd_officer.identification_no as nd_officer_identification_no, 
                        nd_officer.name as nd_officer_name, 
                        nomi_officer.*,
                        nomi_officer.address_type as officer_address_type, 
                        nomi_officer.unit_no1 as nomi_officer_unit_no1, 
                        nomi_officer.unit_no2 as nomi_officer_unit_no2,
                        officer_company.*,
                        officer_company.address_type as officer_company_address_type,  
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
                        left join company_type on company_type.id = client.company_type where client_nominee_director.company_code ="'.$company_code.'" and client_nominee_director.deleted = 0 and client_nominee_director.date_of_cessation = ""');

                $current_q = $q->result_array();

                if(count($current_q) > 0)
                {
                    foreach ($latest_q as $i => $defArr) {
                        $latest_q[$i]['nd_officer_identification_no'] = $this->encryption->decrypt($defArr['nd_officer_identification_no']);
                        $latest_q[$i]['nd_officer_name'] = $this->encryption->decrypt($defArr['nd_officer_name']);
                        if($latest_q[$i]['nomi_officer_field_type'] == "individual")
                        {
                            $latest_q[$i]['identification_no'] = $this->encryption->decrypt($defArr['identification_no']);
                            $latest_q[$i]['name'] = $this->encryption->decrypt($defArr['name']);
                        }
                        elseif($latest_q[$i]['nomi_officer_field_type'] == "company")
                        {
                            $latest_q[$i]['register_no'] = $this->encryption->decrypt($defArr['register_no']);
                            $latest_q[$i]['officer_company_company_name'] = $this->encryption->decrypt($defArr['officer_company_company_name']);
                        }
                        elseif($latest_q[$i]['nomi_officer_field_type'] == "client")
                        {
                            $latest_q[$i]['registration_no'] = $this->encryption->decrypt($defArr['registration_no']);
                            $latest_q[$i]['client_company_name'] = $this->encryption->decrypt($defArr['client_company_name']);
                        }
                        foreach ($current_q as $j => $dayArr) {
                            if ($dayArr['nd_officer_id'] == $defArr['nd_officer_id'] && $dayArr['nd_officer_field_type'] == $defArr['nd_officer_field_type'] && $dayArr['nomi_officer_id'] == $defArr['nomi_officer_id'] && $dayArr['nomi_officer_field_type'] == $defArr['nomi_officer_field_type'] && $dayArr['date_of_cessation'] == "") {
                                unset($latest_q[$i]);
                            }
                        }
                    }

                    return array_values($latest_q);
                }
                else
                {
                    foreach ($latest_q as $i => $defArr) {
                        $latest_q[$i]['nd_officer_identification_no'] = $this->encryption->decrypt($defArr['nd_officer_identification_no']);
                        $latest_q[$i]['nd_officer_name'] = $this->encryption->decrypt($defArr['nd_officer_name']);
                        if($latest_q[$i]['nomi_officer_field_type'] == "individual")
                        {
                            $latest_q[$i]['identification_no'] = $this->encryption->decrypt($defArr['identification_no']);
                            $latest_q[$i]['name'] = $this->encryption->decrypt($defArr['name']);
                        }
                        elseif($latest_q[$i]['nomi_officer_field_type'] == "company")
                        {
                            $latest_q[$i]['register_no'] = $this->encryption->decrypt($defArr['register_no']);
                            $latest_q[$i]['officer_company_company_name'] = $this->encryption->decrypt($defArr['officer_company_company_name']);
                        }
                        elseif($latest_q[$i]['nomi_officer_field_type'] == "client")
                        {
                            $latest_q[$i]['registration_no'] = $this->encryption->decrypt($defArr['registration_no']);
                            $latest_q[$i]['client_company_name'] = $this->encryption->decrypt($defArr['client_company_name']);
                        }
                    }
                    return $latest_q;
                }
            }
            return false;
        }
    }

    public function getEditClientNomineeDirector($nominee_director_id, $transaction_id, $nomioffice_id, $nomifield_type)
    {
        if ($nominee_director_id)
        {
            if ($transaction_id == "")
            {
                $q = $this->db->query('select 
                            client_nominee_director.*, 
                            client_nominee_director.company_code as client_nominee_director_company_code, 
                            client_nominee_director.id as client_nominee_director_id, 
                            nd_officer.identification_no as nd_officer_identification_no,
                            nd_officer.name as nd_officer_name, 
                            nomi_officer.*, 
                            nomi_officer.unit_no1 as nomi_officer_unit_no1, 
                            nomi_officer.unit_no2 as nomi_officer_unit_no2,
                            officer_company.*, 
                            officer_company.company_name as officer_company_company_name, 
                            client.*, client.id as client_id, client.company_name as client_company_name, 
                            client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2,
                            nationality.nationality as nomi_officer_nationality_name, 
                            company_type.company_type as client_company_type 
                            from client_nominee_director 
                            left join officer as nd_officer on client_nominee_director.nd_officer_id = nd_officer.id and client_nominee_director.nd_officer_field_type = nd_officer.field_type 
                            left join officer as nomi_officer on client_nominee_director.nomi_officer_id = nomi_officer.id and client_nominee_director.nomi_officer_field_type = nomi_officer.field_type
                            left join officer_company on client_nominee_director.nomi_officer_id = officer_company.id and client_nominee_director.nomi_officer_field_type = officer_company.field_type 
                            left join client on client.id = client_nominee_director.nomi_officer_id AND client_nominee_director.nomi_officer_field_type = "client"
                            left join nationality on nationality.id = nomi_officer.nationality 
                            left join company_type on company_type.id = client.company_type where client_nominee_director.id ="'.$nominee_director_id.'" AND client_nominee_director.nomi_officer_id = "'.$nomioffice_id.'" AND client_nominee_director.nomi_officer_field_type = "'.$nomifield_type.'"');

                if ($q->num_rows() > 0) {
                    foreach (($q->result()) as $row) {
                        $row->encrypt_nd_identification_no = $row->nd_officer_identification_no;
                        $row->nd_officer_identification_no = $this->encryption->decrypt($row->nd_officer_identification_no);
                        $row->nd_officer_name = $this->encryption->decrypt($row->nd_officer_name);

                        if($row->nomi_officer_field_type == "individual")
                        {
                            $row->encrypt_identification_no = $row->identification_no;
                            $row->identification_no = $this->encryption->decrypt($row->identification_no);
                            $row->name = $this->encryption->decrypt($row->name);
                        }
                        elseif($row->nomi_officer_field_type == "company")
                        {
                            $row->encrypt_register_no = $row->register_no;
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
                    return $data;
                }
            }
            else
            {
                $latest_q = $this->db->query('select 
                                transaction_client_nominee_director.*, 
                                transaction_client_nominee_director.company_code as client_nominee_director_company_code, 
                                transaction_client_nominee_director.id as client_nominee_director_id,
                                nd_officer.identification_no as nd_officer_identification_no, 
                                nd_officer.name as nd_officer_name, 
                                nomi_officer.*, 
                                nomi_officer.unit_no1 as nomi_officer_unit_no1, 
                                nomi_officer.unit_no2 as nomi_officer_unit_no2,
                                officer_company.*, 
                                officer_company.company_name as officer_company_company_name, 
                                client.*, client.id as client_id, client.company_name as client_company_name, 
                                client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, 
                                nationality.nationality as nomi_officer_nationality_name,
                                company_type.company_type as client_company_type 
                                from transaction_client_nominee_director left join officer as nd_officer on transaction_client_nominee_director.nd_officer_id = nd_officer.id and transaction_client_nominee_director.nd_officer_field_type = nd_officer.field_type left join officer as nomi_officer on transaction_client_nominee_director.nomi_officer_id = nomi_officer.id and transaction_client_nominee_director.nomi_officer_field_type = nomi_officer.field_type left join officer_company on transaction_client_nominee_director.nomi_officer_id = officer_company.id and transaction_client_nominee_director.nomi_officer_field_type = officer_company.field_type left join client on client.id = transaction_client_nominee_director.nomi_officer_id AND transaction_client_nominee_director.nomi_officer_field_type = "client" left join nationality on nationality.id = nomi_officer.nationality 
                                    left join company_type on company_type.id = client.company_type where  transaction_client_nominee_director.id ="'.$nominee_director_id.'" AND transaction_client_nominee_director.nomi_officer_id = "'.$nomioffice_id.'" AND transaction_client_nominee_director.nomi_officer_field_type = "'.$nomifield_type.'"');

                if ($latest_q->num_rows() > 0) {
                    foreach (($latest_q->result()) as $row) {
                        $row->encrypt_nd_identification_no = $row->nd_officer_identification_no;
                        $row->nd_officer_identification_no = $this->encryption->decrypt($row->nd_officer_identification_no);
                        $row->nd_officer_name = $this->encryption->decrypt($row->nd_officer_name);

                        if($row->nomi_officer_field_type == "individual")
                        {
                            $row->encrypt_identification_no = $row->identification_no;
                            $row->identification_no = $this->encryption->decrypt($row->identification_no);
                            $row->name = $this->encryption->decrypt($row->name);
                        }
                        elseif($row->nomi_officer_field_type == "company")
                        {
                            $row->encrypt_register_no = $row->register_no;
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
                    return $data;
                }
            }
        }
        return false;
    }

    public function getCurrentClientController($company_code, $transaction_id)
    {
        if ($company_code)
        {
            $q = $this->db->query('select client_controller.*, client_controller.company_code as client_controller_company_code, client_controller.id as client_controller_id, client_controller.field_type as client_controller_field_type, client_controller.deleted as client_controller_deleted, officer.*, officer.address_type as officer_address_type, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.address_type as officer_company_address_type, officer_company.company_name as officer_company_company_name, client.*, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, nationality.nationality as officer_nationality_name, company_type.company_type as client_company_type from client_controller left join officer on client_controller.officer_id = officer.id and client_controller.field_type = officer.field_type left join officer_company on client_controller.officer_id = officer_company.id and client_controller.field_type = officer_company.field_type left join client on client.id = client_controller.officer_id AND client_controller.field_type = "client" left join nationality on nationality.id = officer.nationality left join company_type on company_type.id = client.company_type where client_controller.company_code ="'.$company_code.'" and client_controller.deleted = 0 AND client_controller.date_of_cessation = ""'); //AND client_controller.date_of_cessation = ""

            if ($q->num_rows() > 0) 
            {
                $current_q = $q->result_array();

                $latest_q = $this->db->query('select transaction_client_controller.*, transaction_client_controller.company_code as client_controller_company_code, transaction_client_controller.id as client_controller_id, transaction_client_controller.field_type as client_controller_field_type, transaction_client_controller.deleted as client_controller_deleted, officer.*, officer.address_type as officer_address_type, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.address_type as officer_company_address_type, officer_company.company_name as officer_company_company_name, client.*, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, nationality.nationality as officer_nationality_name, company_type.company_type as client_company_type from transaction_client_controller left join officer on transaction_client_controller.officer_id = officer.id and transaction_client_controller.field_type = officer.field_type left join officer_company on transaction_client_controller.officer_id = officer_company.id and transaction_client_controller.field_type = officer_company.field_type left join client on client.id = transaction_client_controller.officer_id AND transaction_client_controller.field_type = "client" left join nationality on nationality.id = officer.nationality left join company_type on company_type.id = client.company_type where transaction_client_controller.company_code ="'.$company_code.'" AND transaction_client_controller.transaction_id = "'.$transaction_id.'"');

                $latest_q = $latest_q->result_array();

                if(count($latest_q) > 0)
                {
                    foreach ($current_q as $i => $defArr) {
                        foreach ($latest_q as $j => $dayArr) {

                            if ($dayArr['officer_id'] == $defArr['officer_id'] && $dayArr['client_controller_field_type'] == $defArr['client_controller_field_type'] && $defArr['date_of_cessation'] == "") {
                                $current_q[$i] = $latest_q[$j];
                            }
                            else
                            {
                                $current_q[$i]['transaction_id'] = "";
                            }

                            if($defArr['client_controller_field_type'] == "individual")
                            {
                                $current_q[$i]['identification_no'] = $this->encryption->decrypt($defArr['identification_no']);
                                $current_q[$i]['name'] = $this->encryption->decrypt($defArr['name']);
                            }
                            elseif($defArr['client_controller_field_type'] == "company")
                            {
                                $current_q[$i]['register_no'] = $this->encryption->decrypt($defArr['register_no']);
                                $current_q[$i]['officer_company_company_name'] = $this->encryption->decrypt($defArr['officer_company_company_name']);
                            }
                            elseif($defArr['client_controller_field_type'] == "client")
                            {
                                $current_q[$i]['registration_no'] = $this->encryption->decrypt($defArr['registration_no']);
                                $current_q[$i]['client_company_name'] = $this->encryption->decrypt($defArr['client_company_name']);
                            }
                        }
                        if($current_q[$i]['client_controller_deleted'] == 1)
                        {
                            unset($current_q[$i]);
                        }
                    }
                    //print_r($current_q);
                    return array_values($current_q);
                }
                else
                {
                    foreach ($current_q as $i => $defArr) {
                        if($defArr['client_controller_field_type'] == "individual")
                        {
                            $current_q[$i]['identification_no'] = $this->encryption->decrypt($defArr['identification_no']);
                            $current_q[$i]['name'] = $this->encryption->decrypt($defArr['name']);
                        }
                        elseif($defArr['client_controller_field_type'] == "company")
                        {
                            $current_q[$i]['register_no'] = $this->encryption->decrypt($defArr['register_no']);
                            $current_q[$i]['officer_company_company_name'] = $this->encryption->decrypt($defArr['officer_company_company_name']);
                        }
                        elseif($defArr['client_controller_field_type'] == "client")
                        {
                            $current_q[$i]['registration_no'] = $this->encryption->decrypt($defArr['registration_no']);
                            $current_q[$i]['client_company_name'] = $this->encryption->decrypt($defArr['client_company_name']);
                        }
                    }
                    return $current_q;
                }
            }
            return false;
        }
        return false;
    }

    public function getLatestClientController($company_code, $transaction_id)
    {
        if ($company_code)
        {
            $latest_q = $this->db->query('select transaction_client_controller.*, transaction_client_controller.company_code as client_controller_company_code, transaction_client_controller.id as client_controller_id, transaction_client_controller.field_type as client_controller_field_type, officer.*, officer.address_type as officer_address_type, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.address_type as officer_company_address_type, officer_company.company_name as officer_company_company_name, client.*, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, nationality.nationality as officer_nationality_name, company_type.company_type as client_company_type from transaction_client_controller left join officer on transaction_client_controller.officer_id = officer.id and transaction_client_controller.field_type = officer.field_type left join officer_company on transaction_client_controller.officer_id = officer_company.id and transaction_client_controller.field_type = officer_company.field_type left join client on client.id = transaction_client_controller.officer_id AND transaction_client_controller.field_type = "client" left join nationality on nationality.id = officer.nationality left join company_type on company_type.id = client.company_type where transaction_client_controller.company_code ="'.$company_code.'" AND transaction_client_controller.transaction_id = "'.$transaction_id.'" AND transaction_client_controller.deleted = 0');

            if ($latest_q->num_rows() > 0) 
            {
                $latest_q = $latest_q->result_array();

                $q = $this->db->query('select client_controller.*, client_controller.company_code as client_controller_company_code, client_controller.id as client_controller_id, client_controller.field_type as client_controller_field_type, officer.*, officer.address_type as officer_address_type, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.address_type as officer_company_address_type, officer_company.company_name as officer_company_company_name, client.*, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, nationality.nationality as officer_nationality_name, company_type.company_type as client_company_type from client_controller left join officer on client_controller.officer_id = officer.id and client_controller.field_type = officer.field_type left join officer_company on client_controller.officer_id = officer_company.id and client_controller.field_type = officer_company.field_type left join client on client.id = client_controller.officer_id AND client_controller.field_type = "client" left join nationality on nationality.id = officer.nationality left join company_type on company_type.id = client.company_type where client_controller.company_code ="'.$company_code.'" and client_controller.deleted = 0 AND client_controller.date_of_cessation = ""');// AND client_controller.date_of_cessation = ""

                $current_q = $q->result_array();

                if(count($current_q) > 0)
                {
                    foreach ($latest_q as $i => $defArr) {
                        if($defArr['client_controller_field_type'] == "individual")
                        {
                            $latest_q[$i]['identification_no'] = $this->encryption->decrypt($defArr['identification_no']);
                            $latest_q[$i]['name'] = $this->encryption->decrypt($defArr['name']);
                        }
                        elseif($defArr['client_controller_field_type'] == "company")
                        {
                            $latest_q[$i]['register_no'] = $this->encryption->decrypt($defArr['register_no']);
                            $latest_q[$i]['officer_company_company_name'] = $this->encryption->decrypt($defArr['officer_company_company_name']);
                        }
                        elseif($defArr['client_controller_field_type'] == "client")
                        {
                            $latest_q[$i]['registration_no'] = $this->encryption->decrypt($defArr['registration_no']);
                            $latest_q[$i]['client_company_name'] = $this->encryption->decrypt($defArr['client_company_name']);
                        }
                        foreach ($current_q as $j => $dayArr) {
                            if ($dayArr['officer_id'] == $defArr['officer_id'] && $dayArr['client_controller_field_type'] == $defArr['client_controller_field_type'] && $dayArr['date_of_cessation'] == "") {
                                unset($latest_q[$i]);
                            }
                        }
                    }

                    return array_values($latest_q);
                }
                else
                {
                    foreach ($latest_q as $i => $defArr) {
                        if($defArr['client_controller_field_type'] == "individual")
                        {
                            $latest_q[$i]['identification_no'] = $this->encryption->decrypt($defArr['identification_no']);
                            $latest_q[$i]['name'] = $this->encryption->decrypt($defArr['name']);
                        }
                        elseif($defArr['client_controller_field_type'] == "company")
                        {
                            $latest_q[$i]['register_no'] = $this->encryption->decrypt($defArr['register_no']);
                            $latest_q[$i]['officer_company_company_name'] = $this->encryption->decrypt($defArr['officer_company_company_name']);
                        }
                        elseif($defArr['client_controller_field_type'] == "client")
                        {
                            $latest_q[$i]['registration_no'] = $this->encryption->decrypt($defArr['registration_no']);
                            $latest_q[$i]['client_company_name'] = $this->encryption->decrypt($defArr['client_company_name']);
                        }
                    }
                    return $latest_q;
                }
            }
            return false;
        }
    }

    public function getEditClientController($controller_id, $transaction_id, $office_id, $field_type)
    {
        if ($controller_id)
        {
            if ($transaction_id == "")
            {
                $q = $this->db->query('select client_controller.*, client_controller.company_code as client_controller_company_code, client_controller.id as client_controller_id, client_controller.officer_id as client_controller_officer_id, client_controller.field_type as client_controller_field_type, officer.*, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.company_name as officer_company_company_name, client.*, client.id as client_id, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, nationality.nationality as officer_nationality_name, company_type.company_type as client_company_type from client_controller left join officer on client_controller.officer_id = officer.id and client_controller.field_type = officer.field_type left join officer_company on client_controller.officer_id = officer_company.id and client_controller.field_type = officer_company.field_type left join client on client.id = client_controller.officer_id AND client_controller.field_type = "client" left join nationality on nationality.id = officer.nationality left join company_type on company_type.id = client.company_type where client_controller.id ="'.$controller_id.'" AND client_controller.officer_id = "'.$office_id.'" AND client_controller.field_type = "'.$field_type.'"');

                if ($q->num_rows() > 0) {
                    foreach (($q->result()) as $row) {
                        if($row->client_controller_field_type == "individual")
                        {
                            $row->encrypt_identification_no = $row->identification_no;
                            $row->identification_no = $this->encryption->decrypt($row->identification_no);
                            $row->name = $this->encryption->decrypt($row->name);
                        }
                        elseif($row->client_controller_field_type == "company")
                        {
                            $row->encrypt_register_no = $row->register_no;
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
                    return $data;
                }
            }
            else
            {
                $latest_q = $this->db->query('select transaction_client_controller.*, transaction_client_controller.company_code as client_controller_company_code, transaction_client_controller.id as client_controller_id, transaction_client_controller.officer_id as client_controller_officer_id, transaction_client_controller.field_type as client_controller_field_type, officer.*, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.company_name as officer_company_company_name, client.*, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, nationality.nationality as officer_nationality_name, company_type.company_type as client_company_type from transaction_client_controller left join officer on transaction_client_controller.officer_id = officer.id and transaction_client_controller.field_type = officer.field_type left join officer_company on transaction_client_controller.officer_id = officer_company.id and transaction_client_controller.field_type = officer_company.field_type left join client on client.id = transaction_client_controller.officer_id AND transaction_client_controller.field_type = "client" left join nationality on nationality.id = officer.nationality left join company_type on company_type.id = client.company_type where transaction_client_controller.id ="'.$controller_id.'" AND transaction_client_controller.officer_id = "'.$office_id.'" AND transaction_client_controller.field_type = "'.$field_type.'"');

                if ($latest_q->num_rows() > 0) {
                    foreach (($latest_q->result()) as $row) {
                        if($row->client_controller_field_type == "individual")
                        {
                            $row->encrypt_identification_no = $row->identification_no;
                            $row->identification_no = $this->encryption->decrypt($row->identification_no);
                            $row->name = $this->encryption->decrypt($row->name);
                        }
                        elseif($row->client_controller_field_type == "company")
                        {
                            $row->encrypt_register_no = $row->register_no;
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
                    return $data;
                }
            }
        }
        return false;
    }

    public function getApptDirectorController($company_code, $transaction_id)
    {
        // print_r(array($company_code,$transaction_id));

        if ($company_code)
        {
            // $latest_q = $this->db->query('select transaction_client_controller.*, transaction_client_controller.company_code as client_controller_company_code, transaction_client_controller.id as client_controller_id, transaction_client_controller.field_type as client_controller_field_type, officer.*, officer.address_type as officer_address_type, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.address_type as officer_company_address_type, officer_company.company_name as officer_company_company_name, client.*, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, nationality.nationality as officer_nationality_name, company_type.company_type as client_company_type from transaction_client_controller left join officer on transaction_client_controller.officer_id = officer.id and transaction_client_controller.field_type = officer.field_type left join officer_company on transaction_client_controller.officer_id = officer_company.id and transaction_client_controller.field_type = officer_company.field_type left join client on client.id = transaction_client_controller.officer_id AND transaction_client_controller.field_type = "client" left join nationality on nationality.id = officer.nationality left join company_type on company_type.id = client.company_type where transaction_client_controller.company_code ="'.$company_code.'" AND transaction_client_controller.transaction_id = "'.$transaction_id.'" AND transaction_client_controller.deleted = 0');

             $latest_q = $this->db->query('select transaction_client_officers.*, transaction_client_officers.company_code as client_controller_company_code, transaction_client_officers.id as client_controller_id, transaction_client_officers.field_type as client_controller_field_type, officer.*, officer.address_type as officer_address_type, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.address_type as officer_company_address_type, officer_company.company_name as officer_company_company_name, client.*, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, nationality.nationality as officer_nationality_name, company_type.company_type as client_company_type from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id and transaction_client_officers.field_type = officer.field_type left join officer_company on transaction_client_officers.officer_id = officer_company.id and transaction_client_officers.field_type = officer_company.field_type left join client on client.id = transaction_client_officers.officer_id AND transaction_client_officers.field_type = "client" left join nationality on nationality.id = officer.nationality left join company_type on company_type.id = client.company_type where transaction_client_officers.company_code ="'.$company_code.'" AND transaction_client_officers.transaction_id = "'.$transaction_id.'" AND transaction_client_officers.appoint_resign_flag = "appoint" AND transaction_client_officers.date_of_cessation = ""');// AND transaction_client_officers.deleted = 0

            if ($latest_q->num_rows() > 0) 
            {
                $latest_q = $latest_q->result_array();

                $q = $this->db->query('select transaction_client_officers.*, transaction_client_officers.company_code as client_controller_company_code, transaction_client_officers.id as client_controller_id, transaction_client_officers.field_type as client_controller_field_type, officer.*, officer.address_type as officer_address_type, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.address_type as officer_company_address_type, officer_company.company_name as officer_company_company_name, client.*, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, nationality.nationality as officer_nationality_name, company_type.company_type as client_company_type from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id and transaction_client_officers.field_type = officer.field_type left join officer_company on transaction_client_officers.officer_id = officer_company.id and transaction_client_officers.field_type = officer_company.field_type left join client on client.id = transaction_client_officers.officer_id AND transaction_client_officers.field_type = "client" left join nationality on nationality.id = officer.nationality left join company_type on company_type.id = client.company_type where transaction_client_officers.company_code ="'.$company_code.'" AND transaction_client_officers.appoint_resign_flag = "appoint" AND transaction_client_officers.date_of_cessation = ""');// AND client_controller.date_of_cessation = ""

                $current_q = $q->result_array();

                if(count($current_q) > 0)
                {
                    foreach ($latest_q as $i => $defArr) {
                        if($defArr['client_controller_field_type'] == "individual")
                        {
                            $latest_q[$i]['identification_no'] = $this->encryption->decrypt($defArr['identification_no']);
                            $latest_q[$i]['name'] = $this->encryption->decrypt($defArr['name']);
                        }
                        elseif($defArr['client_controller_field_type'] == "company")
                        {
                            $latest_q[$i]['register_no'] = $this->encryption->decrypt($defArr['register_no']);
                            $latest_q[$i]['officer_company_company_name'] = $this->encryption->decrypt($defArr['officer_company_company_name']);
                        }
                        elseif($defArr['client_controller_field_type'] == "client")
                        {
                            $latest_q[$i]['registration_no'] = $this->encryption->decrypt($defArr['registration_no']);
                            $latest_q[$i]['client_company_name'] = $this->encryption->decrypt($defArr['client_company_name']);
                        }
                        foreach ($current_q as $j => $dayArr) {
                            // if ($dayArr['officer_id'] == $defArr['officer_id'] && $dayArr['client_controller_field_type'] == $defArr['client_controller_field_type'] && $dayArr['date_of_cessation'] == "")
                            if ($dayArr['officer_id'] != $defArr['officer_id'] && $dayArr['client_controller_field_type'] != $defArr['client_controller_field_type'] && $dayArr['date_of_cessation'] != "")
                            {
                                unset($latest_q[$i]);
                            }
                        }
                    }

                    return array_values($latest_q);
                }
                else
                {
                    foreach ($latest_q as $i => $defArr) {
                        if($defArr['client_controller_field_type'] == "individual")
                        {
                            $latest_q[$i]['identification_no'] = $this->encryption->decrypt($defArr['identification_no']);
                            $latest_q[$i]['name'] = $this->encryption->decrypt($defArr['name']);
                        }
                        elseif($defArr['client_controller_field_type'] == "company")
                        {
                            $latest_q[$i]['register_no'] = $this->encryption->decrypt($defArr['register_no']);
                            $latest_q[$i]['officer_company_company_name'] = $this->encryption->decrypt($defArr['officer_company_company_name']);
                        }
                        elseif($defArr['client_controller_field_type'] == "client")
                        {
                            $latest_q[$i]['registration_no'] = $this->encryption->decrypt($defArr['registration_no']);
                            $latest_q[$i]['client_company_name'] = $this->encryption->decrypt($defArr['client_company_name']);
                        }
                    }
                    return $latest_q;
                }
            }
            return false;
        }
    }

    public function getApptNomineeDirectorController($company_code, $transaction_id)
    {
        if ($company_code)
        {
            $latest_q = $this->db->query('

             select transaction_client_nominee_director.*, transaction_client_nominee_director.company_code as client_controller_company_code, transaction_client_nominee_director.id as client_controller_id, transaction_client_nominee_director.nd_officer_field_type as client_controller_field_type, officer.*, officer.address_type as officer_address_type, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.address_type as officer_company_address_type, officer_company.company_name as officer_company_company_name, client.*, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, nationality.nationality as officer_nationality_name, company_type.company_type as client_company_type

             from transaction_client_nominee_director 

             left join officer on transaction_client_nominee_director.nd_officer_id = officer.id AND transaction_client_nominee_director.nd_officer_field_type = officer.field_type 

             left join officer_company on transaction_client_nominee_director.nd_officer_id = officer_company.id and transaction_client_nominee_director.nd_officer_field_type = officer_company.field_type 

             left join client on client.id = transaction_client_nominee_director.nd_officer_id AND transaction_client_nominee_director.nd_officer_field_type = "client" 

             left join nationality on nationality.id = officer.nationality 
             left join company_type on company_type.id = client.company_type 

             where transaction_client_nominee_director.company_code ="'.$company_code.'" 
             AND transaction_client_nominee_director.transaction_id = "'.$transaction_id.'"
             AND transaction_client_nominee_director.deleted = 0

             ');

            if ($latest_q->num_rows() > 0) 
            {
                $latest_q = $latest_q->result_array();

                $q = $this->db->query('

                select transaction_client_nominee_director.*, transaction_client_nominee_director.company_code as client_controller_company_code, transaction_client_nominee_director.id as client_controller_id, transaction_client_nominee_director.nd_officer_field_type as client_controller_field_type, officer.*, officer.address_type as officer_address_type, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.address_type as officer_company_address_type, officer_company.company_name as officer_company_company_name, client.*, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, nationality.nationality as officer_nationality_name, company_type.company_type as client_company_type

                from transaction_client_nominee_director 

                left join officer on transaction_client_nominee_director.nd_officer_id = officer.id AND transaction_client_nominee_director.nd_officer_field_type = officer.field_type  

                left join officer_company on transaction_client_nominee_director.nd_officer_id = officer_company.id and transaction_client_nominee_director.nd_officer_field_type = officer_company.field_type 

                left join client on client.id = transaction_client_nominee_director.nd_officer_id AND transaction_client_nominee_director.nd_officer_field_type = "client" 

                left join nationality on nationality.id = officer.nationality 

                left join company_type on company_type.id = client.company_type 

                where transaction_client_nominee_director.company_code ="'.$company_code.'" 
                AND transaction_client_nominee_director.deleted = 0

                ');

                $current_q = $q->result_array();

                if(count($current_q) > 0)
                {
                    foreach ($latest_q as $i => $defArr) {
                        if($defArr['client_controller_field_type'] == "individual")
                        {
                            $latest_q[$i]['identification_no'] = $this->encryption->decrypt($defArr['identification_no']);
                            $latest_q[$i]['name'] = $this->encryption->decrypt($defArr['name']);
                        }
                        elseif($defArr['client_controller_field_type'] == "company")
                        {
                            $latest_q[$i]['register_no'] = $this->encryption->decrypt($defArr['register_no']);
                            $latest_q[$i]['officer_company_company_name'] = $this->encryption->decrypt($defArr['officer_company_company_name']);
                        }
                        elseif($defArr['client_controller_field_type'] == "client")
                        {
                            $latest_q[$i]['registration_no'] = $this->encryption->decrypt($defArr['registration_no']);
                            $latest_q[$i]['client_company_name'] = $this->encryption->decrypt($defArr['client_company_name']);
                        }
                        foreach ($current_q as $j => $dayArr) {
                            // if ($dayArr['officer_id'] == $defArr['officer_id'] && $dayArr['client_controller_field_type'] == $defArr['client_controller_field_type'] && $dayArr['date_of_cessation'] == "")
                            if ($dayArr['officer_id'] != $defArr['officer_id'] && $dayArr['client_controller_field_type'] != $defArr['client_controller_field_type'] && $dayArr['date_of_cessation'] != "")
                            {
                                unset($latest_q[$i]);
                            }
                        }
                    }

                    return array_values($latest_q);
                }
                else
                {
                    foreach ($latest_q as $i => $defArr) {
                        if($defArr['client_controller_field_type'] == "individual")
                        {
                            $latest_q[$i]['identification_no'] = $this->encryption->decrypt($defArr['identification_no']);
                            $latest_q[$i]['name'] = $this->encryption->decrypt($defArr['name']);
                        }
                        elseif($defArr['client_controller_field_type'] == "company")
                        {
                            $latest_q[$i]['register_no'] = $this->encryption->decrypt($defArr['register_no']);
                            $latest_q[$i]['officer_company_company_name'] = $this->encryption->decrypt($defArr['officer_company_company_name']);
                        }
                        elseif($defArr['client_controller_field_type'] == "client")
                        {
                            $latest_q[$i]['registration_no'] = $this->encryption->decrypt($defArr['registration_no']);
                            $latest_q[$i]['client_company_name'] = $this->encryption->decrypt($defArr['client_company_name']);
                        }
                    }
                    return $latest_q;
                }
            }
            return false;
        }
    }

    public function getTransactionCommonSealVendorList()
    {
        $q = $this->db->get('transaction_common_seal_vendor');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getClientList()
    {
        $q = $this->db->query("select * from client where client.acquried_by = '1' AND client.deleted != '1' AND client.status = '1'");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $row->registration_no = $this->encryption->decrypt($row->registration_no);
                $row->company_name = $this->encryption->decrypt($row->company_name);
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionPurchaseCommonSeal($id)
    {
        $this->db->select('transaction_purchase_common_seal_info.*, transaction_purchase_common_seal_customer_info.order_for, transaction_purchase_common_seal_customer_info.company_code, client.company_name, client.registration_no, transaction_common_seal_vendor.vendor_name, transaction_common_seal_vendor.vendor_email, transaction_master.service_status, transaction_master.status');
        $this->db->from('transaction_purchase_common_seal_info');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_purchase_common_seal_info.transaction_id ', 'left');
        $this->db->join('transaction_purchase_common_seal_customer_info', 'transaction_purchase_common_seal_customer_info.transaction_id = transaction_master.id ', 'left');
        $this->db->join('client', 'client.company_code = transaction_purchase_common_seal_customer_info.company_code', 'left');
        $this->db->join('transaction_common_seal_vendor', 'transaction_common_seal_vendor.id = transaction_purchase_common_seal_info.vendor', 'left');
        $this->db->where('transaction_purchase_common_seal_info.transaction_id', $id);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $row->registration_no = $this->encryption->decrypt($row->registration_no);
                $row->company_name = $this->encryption->decrypt($row->company_name);
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
}
