<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

	public function get_all_document($transaction_id, $company_code, $second_transaction_task_id, $hidden_selected_el_id = null, $transaction_master_id = NULL){
        
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
                    if($hidden_selected_el_id[$r] == "3")
                    {
                        if($hidden_selected_el_id[0] != "" || $hidden_selected_el_id[1] != "")
                        {
                            $where = $where."or transaction_document.id = '82'";
                        }
                        else
                        {
                            $where = $where." transaction_document.id = '82'";
                        }
                    }
                }
                //echo json_encode("transaction_document.transaction_task_id = '".$transaction_id."'".$where." order by id");, document_master.document_content
                $q = $this->db->query("select transaction_document.*, document_master.id as document_master_id from transaction_document left join document_master on document_master.firm_id = '".$this->session->userdata('firm_id')."' AND document_master.document_name = transaction_document.document_name where ".$where." order by id");
                //echo json_encode($q->result_array());
                if ($q->num_rows() > 0) {
                    foreach (($q->result()) as $row) {
                        $data[] = $row;
                    }
                }
            
                for($g = 0; $g < count($hidden_selected_el_id); $g++)
                {
                    $p = $this->db->query("select firm.name as firm_name from transaction_engagement_letter_service_info left join firm on firm.id = transaction_engagement_letter_service_info.servicing_firm where transaction_engagement_letter_service_info.transaction_id = ".$transaction_master_id." AND transaction_engagement_letter_service_info.engagement_letter_list_id = '".$hidden_selected_el_id[$g]."'");
                    //echo json_encode($p->result_array());
                    if ($p->num_rows() > 0) {
                        //foreach (($p->result()) as $row) {
                            foreach (($data) as $key=>$document) {
                                //echo $document->id;
                                if($document->id == 80 && $hidden_selected_el_id[$g] == "1")
                                {
                                    $data[$key]->firm_name = $p->result()[0]->firm_name;
                                    //echo json_encode($p->result()[0]->firm_name);
                                }
                                if($document->id == 81 && $hidden_selected_el_id[$g] == "2")
                                {
                                    $data[$key]->firm_name = $p->result()[0]->firm_name;
                                    //echo json_encode($p->result()[0]->firm_name);
                                }
                                if($document->id == 82 && $hidden_selected_el_id[$g] == "3")
                                {
                                    $data[$key]->firm_name = $p->result()[0]->firm_name;
                                    //echo json_encode($p->result()[0]->firm_name);
                                }
                            }
                            
                        //}
                    }
                }
            }
            

            
        }
        else
        {
            $get_directors_info = $this->db->query("select * from transaction_client_officers where company_code='".$company_code."' AND position = 1");

            $get_directors_info = $get_directors_info->result_array();

            if(count($get_directors_info) > 1)
            {
                $this->db->select('transaction_document.*, document_master.id as document_master_id, document_master.document_content');
                $this->db->from('transaction_document');
                $this->db->join('document_master', 'document_master.firm_id = '.$this->session->userdata('firm_id').' AND document_master.document_name = transaction_document.document_name', 'left');
                $this->db->where('transaction_document.transaction_task_id', $transaction_id);
                $this->db->where('transaction_document.id != 9');
                $this->db->order_by("id", "asc");
            }
            else
            {
                $this->db->select('transaction_document.*, document_master.id as document_master_id, document_master.document_content');
                $this->db->from('transaction_document');
                $this->db->join('document_master', 'document_master.firm_id = '.$this->session->userdata('firm_id').' AND document_master.document_name = transaction_document.document_name', 'left');
                $this->db->where('transaction_document.transaction_task_id', $transaction_id);
                $this->db->where('transaction_document.id != 8');
                $this->db->where('transaction_document.id != 10');
                $this->db->order_by("id", "asc");
            }
            $q = $this->db->get();

            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
            }

            if($second_transaction_task_id != "")
            {
                $this->db->select('transaction_document.*, document_master.id as document_master_id, document_master.document_content');
                $this->db->from('transaction_document');
                $this->db->join('document_master', 'document_master.firm_id = '.$this->session->userdata('firm_id').' AND document_master.document_name = transaction_document.document_name', 'left');
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
            // /$q = $this->db->get('document_master');
            
            
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

    // public function get_firm_name($transaction_master_id, $hidden_selected_el_id)
    // {
    //     $get_firm_name_info = $this->db->query("select * from transaction_engagement_letter_service_info where transaction_id='".$transaction_engagement_letter_service_info."' AND engagement_letter_list_id IN '".$hidden_selected_el_id."'");

    //     if ($get_firm_name_info->num_rows() > 0) {
    //         foreach (($get_firm_name_info->result()) as $row) {
    //             $data[] = $row;
    //         }

    //         return $data;
    //     }
    //     return FALSE;


    // }

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
                    $data[] = $row;
                }
            }

            //echo json_encode($data);
        }

        $this->db->select('transaction_master.*, transaction_status.transaction_status, transaction_tasks.transaction_task, client.company_name');
        $this->db->from('transaction_master');
        $this->db->join('client', 'client.registration_no = transaction_master.registration_no and client.firm_id = "'.$this->session->userdata('firm_id').'" AND client.deleted = 0', 'left');
        $this->db->join('transaction_status', 'transaction_status.id = transaction_master.status', 'left');
        $this->db->join('transaction_tasks', 'transaction_tasks.id = transaction_master.transaction_task_id', 'left');
        //$this->db->where('transaction_master.registration_no != ""');
        $this->db->where('transaction_master.transaction_task_id != 1 AND transaction_master.transaction_task_id != 28 AND transaction_master.transaction_task_id != 29 AND transaction_master.transaction_task_id != 30');
        $this->db->where('transaction_master.firm_id = '.$this->session->userdata('firm_id').'');
        $this->db->order_by("id", "asc");

        if($group_id == 4)
        {
            $this->db->join('user_client', 'client.id = user_client.client_id AND user_client.user_id = '.$this->session->userdata('user_id'), 'right');
        }
        $p = $this->db->get();

        if ($p->num_rows() > 0) {
            foreach (($p->result()) as $row) {
                $data[] = $row;
            }
            
        }

        $this->db->select('transaction_master.*, transaction_status.transaction_status, transaction_tasks.transaction_task');
        $this->db->from('transaction_master');
        $this->db->join('transaction_status', 'transaction_status.id = transaction_master.status', 'left');
        $this->db->join('transaction_tasks', 'transaction_tasks.id = transaction_master.transaction_task_id', 'left');
        //$this->db->where('transaction_master.registration_no != ""');
        $this->db->where('transaction_master.transaction_task_id != 1 AND transaction_master.transaction_task_id != 28 AND (transaction_master.transaction_task_id = 29 OR transaction_master.transaction_task_id = 30)');
        $this->db->where('transaction_master.firm_id = '.$this->session->userdata('firm_id').'');
        $this->db->order_by("id", "asc");

        if($group_id == 4)
        {
            $this->db->join('user_client', 'client.id = user_client.client_id AND user_client.user_id = '.$this->session->userdata('user_id'), 'right');
        }
        $p = $this->db->get();

        if ($p->num_rows() > 0) {
            foreach (($p->result()) as $row) {
                $data[] = $row;
            }
            
        }

        //echo json_encode($data);
        if(count($data) > 0)
        {
            return $data;
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
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionAgmAr($id, $company_code)
    {
        $this->db->select('transaction_agm_ar.*, transaction_master.effective_date, transaction_first_agm.is_first_agm, activity_status.activity_status_name, solvency_status.solvency_status_name, transaction_epc_status.is_epc_status, small_company.small_company_decision, audited_financial_statement.audited_fs_decision, transaction_agm_share_transfer.share_transfer_name, transaction_consent_for_shorter_notice.is_shorter_notice');
        $this->db->from('transaction_agm_ar');
        $this->db->where('transaction_agm_ar.transaction_id', $id);
        $this->db->join('transaction_master', 'transaction_master.id = transaction_agm_ar.transaction_id ', 'left');
        $this->db->join('transaction_first_agm', 'transaction_first_agm.id = transaction_agm_ar.is_first_agm_id ', 'left');
        $this->db->join('activity_status', 'activity_status.id = transaction_agm_ar.activity_status ', 'left');
        $this->db->join('solvency_status', 'solvency_status.id = transaction_agm_ar.solvency_status ', 'left');
        $this->db->join('transaction_epc_status', 'transaction_epc_status.id = transaction_agm_ar.epc_status_id ', 'left');
        $this->db->join('audited_financial_statement', 'audited_financial_statement.id = transaction_agm_ar.audited_fs ', 'left');
        $this->db->join('   transaction_agm_share_transfer', '  transaction_agm_share_transfer.id = transaction_agm_ar.agm_share_transfer_id', 'left');
        $this->db->join('transaction_consent_for_shorter_notice', 'transaction_consent_for_shorter_notice.id = transaction_agm_ar.shorter_notice ', 'left');
        $this->db->join('small_company', 'small_company.id = transaction_agm_ar.small_company ', 'left');
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            $chairman_result_info = (explode("-",$data[0]->chairman));

            if($chairman_result_info[1] == "individual")
            {
                $officer_result = $this->db->query("select * from officer where id='".$chairman_result_info[0]."' AND field_type='".$chairman_result_info[1]."'");

                $officer_result = $officer_result->result_array();

                $name = $officer_result[0]["name"];
            }
            elseif($chairman_result_info[1] == "company")
            {
                $officer_company_result = $this->db->query("select * from officer_company where id='".$chairman_result_info[0]."' AND field_type='".$chairman_result_info[1]."'");

                $officer_company_result = $officer_company_result->result_array();

                $name = $officer_company_result[0]["company_name"];
            }
            $data[0]->chairman_name = $name;

            return $data;
        }
        return FALSE;

    }

    public function getTransactionDirectorFee($id, $company_code)
    {
        $this->db->select('transaction_agm_ar_director_fee.*');
        $this->db->from('transaction_agm_ar');
        $this->db->join('transaction_agm_ar_director_fee', 'transaction_agm_ar_director_fee.transaction_agm_ar_id = transaction_agm_ar.id', 'left');
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

    public function getTransactionDividend($id, $company_code)
    {
        $this->db->select('transaction_agm_ar_dividend.*, transaction_agm_ar_total_dividend.total_dividend_declared');
        $this->db->from('transaction_agm_ar');
        $this->db->join('transaction_agm_ar_dividend', 'transaction_agm_ar_dividend.transaction_agm_ar_id = transaction_agm_ar.id', 'left');
        $this->db->join('transaction_agm_ar_total_dividend', 'transaction_agm_ar_total_dividend.transaction_agm_ar_id = transaction_agm_ar.id', 'left');
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

    public function getTransactionAmountDue($id, $company_code)
    {
        $this->db->select('transaction_agm_ar_amount_due.*');
        $this->db->from('transaction_agm_ar');
        $this->db->join('transaction_agm_ar_amount_due', 'transaction_agm_ar_amount_due.transaction_agm_ar_id = transaction_agm_ar.id', 'left');
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
            $this->db->where('firm_id', $this->session->userdata('firm_id'));
            $this->db->where('deleted = 0');
            $p = $this->db->get();

            if ($p->num_rows() > 0) {
                foreach (($p->result()) as $row) {
                    $data[] = $row;
                }
                return $data;
            }
            return FALSE;
        }
        
    }
    public function getTransactionResignClientOfficer($id)
    {
        $this->db->select('transaction_client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_officers_position.position as position_name, transaction_master.effective_date, transaction_resign_officer_reason.reason, transaction_resign_officer_reason.is_resign, transaction_appoint_auditor_date.meeting_date, transaction_appoint_auditor_date.notice_date');
        $this->db->from('transaction_client_officers');
        $this->db->join('officer', 'officer.id = transaction_client_officers.officer_id AND officer.field_type = transaction_client_officers.field_type', 'left');
        $this->db->join('officer_company', 'officer_company.id = transaction_client_officers.officer_id AND officer_company.field_type = transaction_client_officers.field_type', 'left');
        $this->db->join('client_officers_position', 'client_officers_position.id = transaction_client_officers.position', 'left');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_client_officers.transaction_id ', 'left');
        $this->db->join('transaction_resign_officer_reason', 'transaction_resign_officer_reason.transaction_client_officers_id = transaction_client_officers.id', 'left');
        $this->db->join('transaction_appoint_auditor_date', 'transaction_appoint_auditor_date.transaction_client_officers_id = transaction_client_officers.id', 'left');
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
        $this->db->where('(transaction_master.status = 1 or transaction_master.status = 2)');
        $this->db->order_by("transaction_master.id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
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
        $this->db->where('(transaction_master.service_status = 1 or transaction_master.service_status = 3)');
        $this->db->order_by("transaction_master.id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
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
        $this->db->select('transaction_engagement_letter_service_info.*, currency.currency as currency_name, unit_pricing.unit_pricing_name, firm.name as firm_name');
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

    public function getTransactionServiceProposalServiceInfo($id)
    {
        $this->db->select('transaction_service_proposal_service_info.*, currency.currency as currency_name, unit_pricing.unit_pricing_name, firm.name as firm_name');
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

    public function getTransactionChangeFYE($id)
    {
        $this->db->select('transaction_change_fye.*, transaction_master.company_code, transaction_master.effective_date');
        $this->db->from('transaction_change_fye');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_change_fye.transaction_id ', 'left');
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
        $this->db->select('transaction_strike_off.*, transaction_master.company_code, transaction_master.effective_date, transaction_status_of_the_company.status_of_the_company_id, reason_for_application.reason_for_application_content');
        $this->db->from('transaction_strike_off');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_strike_off.transaction_id ', 'left');
        $this->db->join('transaction_status_of_the_company', 'transaction_master.id = transaction_status_of_the_company.transaction_id ', 'left');
        $this->db->join('status', 'status.id = transaction_status_of_the_company.status_of_the_company_id', 'left');
        $this->db->join('reason_for_application', 'reason_for_application.id = transaction_strike_off.reason_for_application_id', 'left');
        $this->db->where('transaction_strike_off.transaction_id', $id);
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
        $this->db->select('transaction_client_controller.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.company_name as client_company_name, client.registration_no');
        $this->db->from('transaction_client_controller');
        $this->db->join('officer', 'officer.id = transaction_client_controller.officer_id AND officer.field_type = transaction_client_controller.field_type', 'left');
        $this->db->join('officer_company', 'officer_company.id = transaction_client_controller.officer_id AND officer_company.field_type = transaction_client_controller.field_type', 'left');
        $this->db->join('client', 'client.id = transaction_client_controller.officer_id AND transaction_client_controller.field_type = "client"', 'left');
        $this->db->where('transaction_client_controller.transaction_id', $id);
        $this->db->where('transaction_client_controller.company_code', $transaction_company_code);
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
        $q = $this->db->query('select transaction_client_billing_info.*, our_service_info.service_name, currency.currency as currency_name, unit_pricing.unit_pricing_name from transaction_client_billing_info left join our_service_info on transaction_client_billing_info.service = our_service_info.id left join currency on currency.id = transaction_client_billing_info.currency left join unit_pricing on unit_pricing.id = transaction_client_billing_info.unit_pricing where transaction_id ="'.$id.'" and company_code = "'.$transaction_company_code.'" order by client_billing_info_id');

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
        $q =  $this->db->query("select transaction_transfer_member_id.id as transaction_transfer_member_id, client.company_name as client_company_name, transaction_transfer_member_id.transaction_id as transaction_id, from_officer.id as from_officer_id, from_officer.field_type as from_officer_field_type, from_officer.identification_no as from_officer_identification_no, from_officer.name as from_officer_name, from_officer_company.id as officer_company_id, from_officer_company.register_no as from_officer_company_register_no, from_officer_company.field_type as from_officer_company_field_type, from_officer_company.company_name as from_officer_company_name, from_client.id as from_client_company_id, from_client.registration_no as from_client_regis_no, 'client' as from_client_company_field_type, from_client.company_name as from_client_company_name, from_share_capital.id as share_capital_id, from_share_capital.class_id, from_share_capital.other_class, from_share_capital.currency_id, from_class.sharetype, from_currencies.currency, from_transaction_certificate.id as from_cert_id, from_transaction_certificate.certificate_no as from_certificate_no, from_transaction_certificate.new_certificate_no as from_new_certificate_no, from_transfer_member.id as from_transfer_member_id,  from_transfer_member.number_of_share as from_number_of_share, from_transfer_member.consideration as from_consideration, to_officer.id as to_officer_id, to_officer.field_type as to_officer_field_type, to_officer.identification_no as to_officer_identification_no, to_officer.name as to_officer_name, to_officer_company.id as officer_company_id, to_officer_company.register_no as to_officer_company_register_no, to_officer_company.field_type as to_officer_company_field_type, to_officer_company.company_name as to_officer_company_name, to_client.id as to_client_company_id, to_client.registration_no as to_client_regis_no, 'client' as to_client_company_field_type, to_client.company_name as to_client_company_name, to_transaction_certificate.id as to_cert_id, to_transaction_certificate.certificate_no as to_certificate_no, to_transaction_certificate.new_certificate_no as to_new_certificate_no, to_transfer_member.id as to_transfer_member_id, to_transfer_member.number_of_share as to_number_of_share from transaction_transfer_member_id left join transaction_member_shares as from_transfer_member on from_transfer_member.id = transaction_transfer_member_id.transfer_from_id left join officer as from_officer on from_officer.id = from_transfer_member.officer_id and from_officer.field_type = from_transfer_member.field_type left join officer_company as from_officer_company on from_officer_company.id = from_transfer_member.officer_id and from_officer_company.field_type = from_transfer_member.field_type left join client as from_client on from_client.id = from_transfer_member.officer_id and from_client.deleted = 0 and from_client.firm_id = '".$this->session->userdata('firm_id')."' and from_transfer_member.field_type = 'client' left join client_member_share_capital as from_share_capital on from_transfer_member.client_member_share_capital_id = from_share_capital.id left join sharetype as from_class on from_class.id = from_share_capital.class_id left join currency as from_currencies on from_currencies.id = from_share_capital.currency_id left join transaction_certificate as from_transaction_certificate on from_transaction_certificate.officer_id = from_transfer_member.officer_id and from_transaction_certificate.company_code = from_transfer_member.company_code and from_transaction_certificate.field_type = from_transfer_member.field_type and from_transaction_certificate.transaction_id = from_transfer_member.transaction_id left join transaction_member_shares as to_transfer_member on to_transfer_member.id = transaction_transfer_member_id.transfer_to_id left join officer as to_officer on to_officer.id = to_transfer_member.officer_id and to_officer.field_type = to_transfer_member.field_type left join officer_company as to_officer_company on to_officer_company.id = to_transfer_member.officer_id and to_officer_company.field_type = to_transfer_member.field_type left join client as to_client on to_client.id = to_transfer_member.officer_id and to_client.deleted = 0 and to_client.firm_id = '".$this->session->userdata('firm_id')."' and to_transfer_member.field_type = 'client' left join transaction_certificate as to_transaction_certificate on to_transaction_certificate.officer_id = to_transfer_member.officer_id and to_transaction_certificate.company_code = to_transfer_member.company_code and to_transaction_certificate.field_type = to_transfer_member.field_type and to_transaction_certificate.transaction_id = to_transfer_member.transaction_id left join transaction_master on transaction_master.id = transaction_transfer_member_id.transaction_id left join client on client.company_code = transaction_master.company_code where transaction_transfer_member_id.transaction_id = '".$id."'");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
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

        $q = $this->db->query("select transaction_member_shares.*, transaction_certificate.id as cert_id, transaction_certificate.certificate_no, transaction_certificate.new_certificate_no, officer.id as officer_id, officer.field_type as officer_field_type, officer.identification_no, officer.name, officer_company.id as officer_company_id, officer_company.register_no, officer_company.field_type as officer_company_field_type, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency, client.id as client_company_id, client.registration_no, 'client' as client_company_field_type, client.company_name as client_company_name from transaction_member_shares left join transaction_certificate on transaction_certificate.officer_id = transaction_member_shares.officer_id and transaction_certificate.company_code = transaction_member_shares.company_code and transaction_certificate.field_type = transaction_member_shares.field_type and transaction_certificate.transaction_id = transaction_member_shares.transaction_id left join officer on officer.id = transaction_member_shares.officer_id and officer.field_type = transaction_member_shares.field_type left join officer_company on officer_company.id = transaction_member_shares.officer_id and officer_company.field_type = transaction_member_shares.field_type left join client_member_share_capital as share_capital on transaction_member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = transaction_member_shares.officer_id and client.deleted = 0 and client.firm_id = '".$this->session->userdata('firm_id')."' and transaction_member_shares.field_type = 'client' where transaction_member_shares.transaction_type = 'Transfer' AND ((transaction_member_shares.transaction_page_id = '".$id."' AND transaction_member_shares.id = '".$transaction_share_member_id."') or (transaction_member_shares.transaction_page_id = '".$id."' AND transaction_member_shares.id IN (".implode(',',$transaction_share_member_to_id)."))) ORDER BY transaction_member_shares.id");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTransactionShareAllotmentDate($id)
    {
        $q = $this->db->query("select * from transaction_share_allotment_date where transaction_master_id = '".$id."'");

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
        $q = $this->db->query("select transaction_member_shares.*, transaction_certificate.id as cert_id, transaction_certificate.certificate_no, transaction_certificate.new_certificate_no, officer.id as officer_id, officer.field_type as officer_field_type, officer.identification_no, officer.name, officer_company.id as officer_company_id, officer_company.register_no, officer_company.field_type as officer_company_field_type, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency, client.id as client_company_id, client.registration_no, 'client' as client_company_field_type, client.company_name as client_company_name from transaction_member_shares left join transaction_certificate on transaction_certificate.officer_id = transaction_member_shares.officer_id and transaction_certificate.company_code = transaction_member_shares.company_code and transaction_certificate.field_type = transaction_member_shares.field_type and transaction_certificate.transaction_id = transaction_member_shares.transaction_id left join officer on officer.id = transaction_member_shares.officer_id and officer.field_type = transaction_member_shares.field_type left join officer_company on officer_company.id = transaction_member_shares.officer_id and officer_company.field_type = transaction_member_shares.field_type left join transaction_client_member_share_capital as share_capital on transaction_member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = transaction_member_shares.officer_id and client.deleted = 0 and client.firm_id = '".$this->session->userdata('firm_id')."' and transaction_member_shares.field_type = 'client' where transaction_member_shares.transaction_type = 'Allotment' AND transaction_member_shares.transaction_page_id = '".$id."' AND transaction_member_shares.company_code = '".$transaction_company_code."' ORDER BY transaction_member_shares.id");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_client_signing_info($registra_no)
    {   
        if ($registra_no)
        {
            $q = $this->db->query('select client_signing_info.* from client left join client_signing_info on client_signing_info.company_code = client.company_code where client.registration_no ="'.$registra_no.'" AND client.firm_id = "'.$this->session->userdata('firm_id').'" AND client.deleted = 0');

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

    public function get_all_first_agm()
    {
        $q = $this->db->query("select * from transaction_first_agm");

        //echo json_encode($q->result());

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

    public function check_client_info($company_code)
    {
        $q = $this->db->query("select client.*, filing.year_end, financial_year_period.period, client_signing_info.director_signature_1 from client left join filing on filing.id = (select MAX(id) as filing_id from filing where filing.company_code = client.company_code) left join financial_year_period on financial_year_period.id = filing.financial_year_period_id left join client_signing_info on client_signing_info.company_code = client.company_code where client.company_code = '".$company_code."' and client.firm_id = '".$this->session->userdata('firm_id')."' and client.deleted = 0");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function check_filing_info($registration_no)
    {
        //$registration_no = $_POST["registration_no"];

        $q = $this->db->query("select client.*, filing.year_end, financial_year_period.period from client left join filing on filing.id = (select MAX(id) as filing_id from filing where filing.company_code = client.company_code) left join financial_year_period on financial_year_period.id = filing.financial_year_period_id where client.registration_no = '".$registration_no."' AND client.firm_id = '".$this->session->userdata('firm_id')."' AND client.deleted = 0");

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

                    $name = $officer_result[0]["name"];
                }
                elseif($chairman_result_info[1] == "corp_rep")
                {
                    $officer_company_result = $this->db->query("select * from corporate_representative where id='".$chairman_result_info[0]."'");

                    $officer_company_result = $officer_company_result->result_array();

                    $name = $officer_company_result[0]["name_of_corp_rep"];
                }

                $director_result_1 = $this->db->query("select officer.* from transaction_client_officers left join officer on officer.id = transaction_client_officers.officer_id and officer.field_type = transaction_client_officers.field_type where transaction_client_officers.id='".$transaction_client_signing_info_result[0]['director_signature_1']."'");

                $director_result_1 = $director_result_1->result_array();

                $director_name_1 = $director_result_1[0]["name"];

                $director_result_2 = $this->db->query("select officer.* from transaction_client_officers left join officer on officer.id = transaction_client_officers.officer_id and officer.field_type = transaction_client_officers.field_type where transaction_client_officers.id='".$transaction_client_signing_info_result[0]['director_signature_2']."'");

                $director_result_2 = $director_result_2->result_array();

                $director_name_2 = $director_result_2[0]["name"];

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
            $q = $this->db->query("select transaction_client_contact_info.*, GROUP_CONCAT(DISTINCT CONCAT(transaction_client_contact_info_phone.id,',', transaction_client_contact_info_phone.phone, ',', transaction_client_contact_info_phone.primary_phone)SEPARATOR ';') AS 'transaction_client_contact_info_phone', GROUP_CONCAT(DISTINCT CONCAT(transaction_client_contact_info_email.id,',', transaction_client_contact_info_email.email, ',', transaction_client_contact_info_email.primary_email)SEPARATOR ';') AS 'transaction_client_contact_info_email' from transaction_client_contact_info LEFT JOIN transaction_client_contact_info_phone ON transaction_client_contact_info_phone.client_contact_info_id = transaction_client_contact_info.id LEFT JOIN transaction_client_contact_info_email ON transaction_client_contact_info_email.client_contact_info_id = transaction_client_contact_info.id where transaction_client_contact_info.transaction_id ='".$id."' and transaction_client_contact_info.company_code = '".$transaction_company_code."'");

            if($q->result()[0]->transaction_client_contact_info_phone != null)
            {
                $q->result()[0]->transaction_client_contact_info_phone = explode(';', $q->result()[0]->transaction_client_contact_info_phone);
            }

            if($q->result()[0]->transaction_client_contact_info_email != null)
            {
                $q->result()[0]->transaction_client_contact_info_email = explode(';', $q->result()[0]->transaction_client_contact_info_email);
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
	
}
