<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Document_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function get_all_pending($type=NULL,$keyword=NULL,$start=NULL,$end=NULL){

       /* $q = $this->db->query("select pending_documents.*, client.company_name, users.first_name, users.last_name from pending_documents left join client on client.id = pending_documents.client_id left join users on users.id = pending_documents.created_by where pending_documents.firm_id='".$this->session->userdata('firm_id')."' AND pending_documents.received_on = '' order by id");

        //echo json_encode($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;*/

        $this->db->select('pending_documents.*, client.company_name, users.first_name, users.last_name');
        $this->db->from('pending_documents');
        $this->db->join('client', 'client.id = pending_documents.client_id', 'left');
        $this->db->join('users', 'users.id = pending_documents.created_by', 'left');

        if ($type != NULL)
        {
            if ($type != 'all')
            {
                $this->db->like($type, $keyword);
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('pending_documents.document_name', $keyword);
                    $this->db->or_like('client.company_name', $keyword);
                $this->db->group_end();
            }
        }
        if ($start != NULL)
        {
            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
            if ($end != NULL)
            {

                $this->db->where('date(pending_documents.created_at) BETWEEN date(STR_TO_DATE("'. $start. '","%d/%m/%Y")) and date(STR_TO_DATE("'. $end.'","%d/%m/%Y"))');
            }
            else
            {
                $this->db->where('DATE_FORMAT(pending_documents.created_at, "%d/%m/%Y") = "'. $start.'"');
            }
        }
        $this->db->order_by('pending_documents.id', 'asc');
        $this->db->where('pending_documents.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('pending_documents.received_on = ""');
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            //return $data;
        }

        $this->db->select('transaction_pending_documents.*, transaction_client.company_name, users.first_name, users.last_name');
        $this->db->from('transaction_pending_documents');
        //$this->db->join('transaction_client', 'transaction_client.id = transaction_pending_documents.client_id', 'left');
        $this->db->join('users', 'users.id = transaction_pending_documents.created_by', 'left');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_pending_documents.transaction_id', 'left');
        $this->db->join('transaction_client', 'transaction_client.id = transaction_pending_documents.client_id AND transaction_client.company_code = transaction_master.company_code', 'left');
        if ($type != NULL)
        {
            if ($type != 'all')
            {
                $this->db->like($type, $keyword);
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('transaction_pending_documents.document_name', $keyword);
                    $this->db->or_like('transaction_client.company_name', $keyword);
                $this->db->group_end();
            }
        }
        if ($start != NULL)
        {
            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
            if ($end != NULL)
            {

                $this->db->where('date(transaction_pending_documents.created_at) BETWEEN date(STR_TO_DATE("'. $start. '","%d/%m/%Y")) and date(STR_TO_DATE("'. $end.'","%d/%m/%Y"))');
            }
            else
            {
                $this->db->where('DATE_FORMAT(transaction_pending_documents.created_at, "%d/%m/%Y") = "'. $start.'"');
            }
        }
        $this->db->order_by('transaction_pending_documents.id', 'asc');
        $this->db->where('transaction_pending_documents.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('transaction_pending_documents.received_on = ""');
        $this->db->where('transaction_master.transaction_task_id = 1 and transaction_master.transaction_task_id != 29 and transaction_master.transaction_task_id != 30');
        $p = $this->db->get();

        if ($p->num_rows() > 0) {
            foreach (($p->result()) as $row) {
                $data[] = $row;
            }
            
        }

        $this->db->select('transaction_pending_documents.*, transaction_master.client_name as company_name, users.first_name, users.last_name');
        $this->db->from('transaction_pending_documents');
        //$this->db->join('transaction_client', 'transaction_client.id = transaction_pending_documents.client_id', 'left');
        $this->db->join('users', 'users.id = transaction_pending_documents.created_by', 'left');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_pending_documents.transaction_id', 'left');
        //$this->db->join('transaction_client', 'transaction_client.id = transaction_pending_documents.client_id AND transaction_client.company_code = transaction_master.company_code', 'left');
        if ($type != NULL)
        {
            if ($type != 'all')
            {
                $this->db->like($type, $keyword);
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('transaction_pending_documents.document_name', $keyword);
                    $this->db->or_like('transaction_master.client_name', $keyword);
                $this->db->group_end();
            }
        }
        if ($start != NULL)
        {
            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
            if ($end != NULL)
            {

                $this->db->where('date(transaction_pending_documents.created_at) BETWEEN date(STR_TO_DATE("'. $start. '","%d/%m/%Y")) and date(STR_TO_DATE("'. $end.'","%d/%m/%Y"))');
            }
            else
            {
                $this->db->where('DATE_FORMAT(transaction_pending_documents.created_at, "%d/%m/%Y") = "'. $start.'"');
            }
        }
        $this->db->order_by('transaction_pending_documents.id', 'asc');
        $this->db->where('transaction_pending_documents.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('transaction_pending_documents.received_on = ""');
        $this->db->where('transaction_master.transaction_task_id = 29 or transaction_master.transaction_task_id = 30');
        $p = $this->db->get();

        if ($p->num_rows() > 0) {
            foreach (($p->result()) as $row) {
                $data[] = $row;
            }
            
        }


        $this->db->select('transaction_pending_documents.*, client.company_name, users.first_name, users.last_name');
        $this->db->from('transaction_pending_documents');
        $this->db->join('users', 'users.id = transaction_pending_documents.created_by', 'left');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_pending_documents.transaction_id', 'left');
        $this->db->join('client', 'client.id = transaction_pending_documents.client_id AND client.company_code = transaction_master.company_code', 'left');
        if ($type != NULL)
        {
            if ($type != 'all')
            {
                $this->db->like($type, $keyword);
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('transaction_pending_documents.document_name', $keyword);
                    $this->db->or_like('client.company_name', $keyword);
                $this->db->group_end();
            }
        }
        if ($start != NULL)
        {
            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
            if ($end != NULL)
            {

                $this->db->where('date(transaction_pending_documents.created_at) BETWEEN date(STR_TO_DATE("'. $start. '","%d/%m/%Y")) and date(STR_TO_DATE("'. $end.'","%d/%m/%Y"))');
            }
            else
            {
                $this->db->where('DATE_FORMAT(transaction_pending_documents.created_at, "%d/%m/%Y") = "'. $start.'"');
            }
        }
        $this->db->order_by('transaction_pending_documents.id', 'asc');
        $this->db->where('transaction_pending_documents.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('transaction_pending_documents.received_on = ""');
        $this->db->where('transaction_master.transaction_task_id != 1 and transaction_master.transaction_task_id != 29 and transaction_master.transaction_task_id != 30');
        $p = $this->db->get();

        if ($p->num_rows() > 0) {
            foreach (($p->result()) as $row) {
                $data[] = $row;
            }
            
        }
        //print_r($data);
        if(count($data) > 0)
        {
            return $data;
        }
        else
            return FALSE;
    }

    public function get_all_document($group_id=NULL,$type=NULL,$keyword=NULL,$start=NULL,$end=NULL){

        /*$q = $this->db->query("select pending_documents.*, client.company_name, created_by_user.first_name as created_by_first_name, created_by_user.last_name as created_by_last_name, received_by_user.first_name as received_by_first_name, received_by_user.last_name as received_by_last_name from pending_documents left join client on client.id = pending_documents.client_id left join users as created_by_user on created_by_user.id = pending_documents.created_by left join users as received_by_user on received_by_user.id = pending_documents.received_by where pending_documents.firm_id='".$this->session->userdata('firm_id')."' order by id");

        //echo json_encode($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;*/

        $this->db->select('pending_documents.*, client.company_name, created_by_user.first_name as created_by_first_name, created_by_user.last_name as created_by_last_name, received_by_user.first_name as received_by_first_name, received_by_user.last_name as received_by_last_name');
        $this->db->from('pending_documents');
        
        $this->db->join('users as created_by_user', 'created_by_user.id = pending_documents.created_by', 'left');
        $this->db->join('users as received_by_user', 'received_by_user.id = pending_documents.received_by', 'left');
        $this->db->join('client', 'client.id = pending_documents.client_id', 'left');
        if($group_id == 4)
        {
            $this->db->join('user_client', 'client.id = user_client.client_id AND user_client.user_id = '.$this->session->userdata('user_id'), 'right');
        }
        // else
        // {
            
        // }
        if ($type != NULL)
        {
            if ($type != 'all')
            {
                $this->db->like($type, $keyword);
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('pending_documents.document_name', $keyword);
                    $this->db->or_like('client.company_name', $keyword);
                $this->db->group_end();
            }
        }
        if ($start != NULL)
        {
            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
            if ($end != NULL)
            {

                $this->db->where('date(pending_documents.created_at) BETWEEN date(STR_TO_DATE("'. $start. '","%d/%m/%Y")) and date(STR_TO_DATE("'. $end.'","%d/%m/%Y"))');
            }
            else
            {
                $this->db->where('DATE_FORMAT(pending_documents.created_at, "%d/%m/%Y") = "'. $start.'"');
            }
        }
        $this->db->order_by('pending_documents.id', 'asc');
        $this->db->where('pending_documents.firm_id', $this->session->userdata("firm_id"));
        
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            //return $data;
        }
        //return FALSE;
        if($group_id != 4)
        {
            $this->db->select('transaction_pending_documents.*, transaction_client.company_name, created_by_user.first_name as created_by_first_name, created_by_user.last_name as created_by_last_name, received_by_user.first_name as received_by_first_name, received_by_user.last_name as received_by_last_name');
            $this->db->from('transaction_pending_documents');
            //$this->db->join('transaction_client', 'transaction_client.id = transaction_pending_documents.client_id', 'left');
            $this->db->join('users as created_by_user', 'created_by_user.id = transaction_pending_documents.created_by', 'left');
            $this->db->join('users as received_by_user', 'received_by_user.id = transaction_pending_documents.received_by', 'left');
            $this->db->join('transaction_master', 'transaction_master.id = transaction_pending_documents.transaction_id', 'left');
            $this->db->join('transaction_client', 'transaction_client.id = transaction_pending_documents.client_id AND transaction_client.company_code = transaction_master.company_code', 'left');
            if ($type != NULL)
            {
                if ($type != 'all')
                {
                    $this->db->like($type, $keyword);
                } 
                else 
                {
                    $this->db->group_start();
                        $this->db->or_like('transaction_pending_documents.document_name', $keyword);
                        $this->db->or_like('transaction_client.company_name', $keyword);
                    $this->db->group_end();
                }
            }
            if ($start != NULL)
            {
                //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
                if ($end != NULL)
                {

                    $this->db->where('date(transaction_pending_documents.created_at) BETWEEN date(STR_TO_DATE("'. $start. '","%d/%m/%Y")) and date(STR_TO_DATE("'. $end.'","%d/%m/%Y"))');
                }
                else
                {
                    $this->db->where('DATE_FORMAT(transaction_pending_documents.created_at, "%d/%m/%Y") = "'. $start.'"');
                }
            }
            $this->db->order_by('transaction_pending_documents.id', 'asc');
            $this->db->where('transaction_pending_documents.firm_id', $this->session->userdata("firm_id"));
            $this->db->where('transaction_master.transaction_task_id = 1');
            $q = $this->db->get();

            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
                //return $data;
            }
        }

        $this->db->select('transaction_pending_documents.*, transaction_master.client_name as company_name, created_by_user.first_name as created_by_first_name, created_by_user.last_name as created_by_last_name, received_by_user.first_name as received_by_first_name, received_by_user.last_name as received_by_last_name');
        $this->db->from('transaction_pending_documents');
        // $this->db->join('client', 'client.id = transaction_pending_documents.client_id', 'left');
        $this->db->join('users as created_by_user', 'created_by_user.id = transaction_pending_documents.created_by', 'left');
        $this->db->join('users as received_by_user', 'received_by_user.id = transaction_pending_documents.received_by', 'left');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_pending_documents.transaction_id', 'left');
        if ($type != NULL)
        {
            if ($type != 'all')
            {
                $this->db->like($type, $keyword);
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('transaction_pending_documents.document_name', $keyword);
                    $this->db->or_like('transaction_master.client_name', $keyword);
                $this->db->group_end();
            }
        }
        if ($start != NULL)
        {
            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
            if ($end != NULL)
            {

                $this->db->where('date(transaction_pending_documents.created_at) BETWEEN date(STR_TO_DATE("'. $start. '","%d/%m/%Y")) and date(STR_TO_DATE("'. $end.'","%d/%m/%Y"))');
            }
            else
            {
                $this->db->where('DATE_FORMAT(transaction_pending_documents.created_at, "%d/%m/%Y") = "'. $start.'"');
            }
        }
        $this->db->order_by('transaction_pending_documents.id', 'asc');
        $this->db->where('transaction_pending_documents.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('transaction_master.transaction_task_id = 29 or transaction_master.transaction_task_id = 30');
        $p = $this->db->get();

        if ($p->num_rows() > 0) {
            foreach (($p->result()) as $row) {
                $data[] = $row;
            }
            
        }

        $this->db->select('transaction_pending_documents.*, client.company_name, created_by_user.first_name as created_by_first_name, created_by_user.last_name as created_by_last_name, received_by_user.first_name as received_by_first_name, received_by_user.last_name as received_by_last_name');
        $this->db->from('transaction_pending_documents');
        // $this->db->join('client', 'client.id = transaction_pending_documents.client_id', 'left');
        $this->db->join('users as created_by_user', 'created_by_user.id = transaction_pending_documents.created_by', 'left');
        $this->db->join('users as received_by_user', 'received_by_user.id = transaction_pending_documents.received_by', 'left');
        $this->db->join('transaction_master', 'transaction_master.id = transaction_pending_documents.transaction_id', 'left');
        $this->db->join('client', 'client.id = transaction_pending_documents.client_id AND client.company_code = transaction_master.company_code', 'left');
        if($group_id == 4)
        {
            $this->db->join('user_client', 'user_client.client_id = client.id AND user_client.user_id = '.$this->session->userdata('user_id'), 'right');
            
        }
        // else
        // {
        //     $this->db->join('client', 'client.id = transaction_pending_documents.client_id AND client.company_code = transaction_master.company_code', 'left');
        // }

        if ($type != NULL)
        {
            if ($type != 'all')
            {
                $this->db->like($type, $keyword);
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('transaction_pending_documents.document_name', $keyword);
                    $this->db->or_like('client.company_name', $keyword);
                $this->db->group_end();
            }
        }
        if ($start != NULL)
        {
            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
            if ($end != NULL)
            {

                $this->db->where('date(transaction_pending_documents.created_at) BETWEEN date(STR_TO_DATE("'. $start. '","%d/%m/%Y")) and date(STR_TO_DATE("'. $end.'","%d/%m/%Y"))');
            }
            else
            {
                $this->db->where('DATE_FORMAT(transaction_pending_documents.created_at, "%d/%m/%Y") = "'. $start.'"');
            }
        }
        $this->db->order_by('transaction_pending_documents.id', 'asc');
        $this->db->where('transaction_pending_documents.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('transaction_master.transaction_task_id != 1 and transaction_master.transaction_task_id != 29 and transaction_master.transaction_task_id != 30');
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            //return $data;
        }

        if(count($data) > 0)
        {
            return $data;
        }
        else
            return FALSE;
    }

    public function get_all_document_master($type=NULL,$keyword=NULL,$start=NULL,$end=NULL)
    {

    	/*$q = $this->db->query("select document_master.*, triggered_by.triggered_by from document_master left join triggered_by on triggered_by.id = document_master.triggered_by where document_master.firm_id='".$this->session->userdata('firm_id')."' order by id");

        //echo json_encode($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;*/

        $this->db->select('document_master.*, triggered_by.triggered_by');
        $this->db->from('document_master');
        $this->db->join('triggered_by', 'triggered_by.id = document_master.triggered_by', 'left');

        if ($type != NULL)
        {
            if ($type != 'all')
            {
                $this->db->like($type, $keyword);
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('document_master.document_name', $keyword);
                    //$this->db->or_like('client.company_name', $keyword);
                $this->db->group_end();
            }
        }
        if ($start != NULL)
        {
            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
            if ($end != NULL)
            {

                $this->db->where('date(document_master.created_at) BETWEEN date(STR_TO_DATE("'. $start. '","%d/%m/%Y")) and date(STR_TO_DATE("'. $end.'","%d/%m/%Y"))');
            }
            else
            {
                $this->db->where('DATE_FORMAT(document_master.created_at, "%d/%m/%Y") = "'. $start.'"');
            }
        }
        $this->db->order_by('document_master.id', 'asc');
        $this->db->where('document_master.firm_id', $this->session->userdata("firm_id"));
        
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_document_reminder($type=NULL,$keyword=NULL,$start=NULL,$end=NULL){

    	/*$q = $this->db->query("select document_reminder.* from document_reminder where document_reminder.firm_id='".$this->session->userdata('firm_id')."'");

        //echo json_encode($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;*/

        $this->db->select('document_reminder.*, reminder_tag.reminder_tag_name');
        $this->db->from('document_reminder');
        $this->db->join('reminder_tag', 'reminder_tag.id = document_reminder.reminder_tag_id', 'left');

        if ($type != NULL)
        {
            if ($type != 'all')
            {
                if($type == "document_name")
                {
                    $this->db->like('document_reminder.reminder_name', $keyword);
                }
                else
                {
                    $this->db->like($type, $keyword);
                }
                
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('document_reminder.reminder_name', $keyword);
                    //$this->db->or_like('client.company_name', $keyword);
                $this->db->group_end();
            }
        }
        if ($start != NULL)
        {
            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
            if ($end != NULL)
            {

                $this->db->where('date(document_reminder.created_at) BETWEEN date(STR_TO_DATE("'. $start. '","%d/%m/%Y")) and date(STR_TO_DATE("'. $end.'","%d/%m/%Y"))');
            }
            else
            {
                $this->db->where('DATE_FORMAT(document_reminder.created_at, "%d/%m/%Y") = "'. $start.'"');
            }
        }
        $this->db->order_by('document_reminder.id', 'asc');
        $this->db->where('document_reminder.firm_id', $this->session->userdata("firm_id"));
        
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_reminder_document($id)
    {
    	$q = $this->db->query("select document_reminder.* from document_reminder where document_reminder.firm_id='".$this->session->userdata('firm_id')."' AND document_reminder.id = '".$id."'");

        //echo json_encode($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_document_master($id)
    {
    	$q = $this->db->query("select document_master.*, billing_info_service.service from document_master left join billing_info_service on billing_info_service.id = document_master.triggered_by where document_master.firm_id='".$this->session->userdata('firm_id')."' AND document_master.id = '".$id."'");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_pending_document($id, $type = null){

        if($type != null)
        {
            $q = $this->db->query("select transaction_pending_documents.*, transaction_client.company_name, users.first_name, users.last_name, transaction_master.client_name from transaction_pending_documents left join transaction_client on transaction_client.id = transaction_pending_documents.client_id left join users on users.id = transaction_pending_documents.created_by left join transaction_master on transaction_master.id = transaction_pending_documents.transaction_id where transaction_pending_documents.firm_id='".$this->session->userdata('firm_id')."' AND transaction_pending_documents.id = '".$id."' AND transaction_master.registration_no = ''");

            if (0 == $q->num_rows()) {

                $q = $this->db->query("select transaction_pending_documents.*, client.company_name, users.first_name, users.last_name, transaction_master.client_name from transaction_pending_documents left join client on client.id = transaction_pending_documents.client_id left join users on users.id = transaction_pending_documents.created_by left join transaction_master on transaction_master.id = transaction_pending_documents.transaction_id where transaction_pending_documents.firm_id='".$this->session->userdata('firm_id')."' AND transaction_pending_documents.id = '".$id."' AND transaction_master.registration_no != ''");
            }
        }
        else
        {
    	   $q = $this->db->query("select pending_documents.*, client.company_name, users.first_name, users.last_name from pending_documents left join client on client.id = pending_documents.client_id left join users on users.id = pending_documents.created_by where pending_documents.firm_id='".$this->session->userdata('firm_id')."' AND pending_documents.id = '".$id."'");
        }

        //echo json_encode($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_pending_document_file($id, $type = null){

        //one to many relationship
        if($type != null)
        {
            $q = $this->db->query("select transaction_pending_documents.*, transaction_client.company_name, transaction_master.client_name, GROUP_CONCAT(DISTINCT CONCAT(transaction_pending_documents_file.id,',', transaction_pending_documents_file.file_name)SEPARATOR ';') AS 'company_files' from transaction_pending_documents left join transaction_client on transaction_client.id = transaction_pending_documents.client_id LEFT JOIN transaction_pending_documents_file ON transaction_pending_documents_file.pending_documents_id = transaction_pending_documents.id left join transaction_master on transaction_master.id = transaction_pending_documents.transaction_id where transaction_pending_documents.id='".$id."' AND transaction_master.registration_no = '' GROUP BY transaction_pending_documents.id");

            if (0 == $q->num_rows()) {

                $q = $this->db->query("select transaction_pending_documents.*, client.company_name, transaction_master.client_name, GROUP_CONCAT(DISTINCT CONCAT(transaction_pending_documents_file.id,',', transaction_pending_documents_file.file_name)SEPARATOR ';') AS 'company_files' from transaction_pending_documents left join client on client.id = transaction_pending_documents.client_id LEFT JOIN transaction_pending_documents_file ON transaction_pending_documents_file.pending_documents_id = transaction_pending_documents.id left join transaction_master on transaction_master.id = transaction_pending_documents.transaction_id where transaction_pending_documents.id='".$id."' AND transaction_master.registration_no != '' GROUP BY transaction_pending_documents.id");
            }
        }
        else
        {
            $q = $this->db->query("select pending_documents.*, client.company_name, GROUP_CONCAT(DISTINCT CONCAT(pending_documents_file.id,',', pending_documents_file.file_name)SEPARATOR ';') AS 'company_files' from pending_documents left join client on client.id = pending_documents.client_id LEFT JOIN pending_documents_file ON pending_documents_file.pending_documents_id = pending_documents.id where pending_documents.id='".$id."' GROUP BY pending_documents.id");
        }
        

        
        if($q->result()[0]->company_files != null)
        {
            $q->result()[0]->company_files = explode(';', $q->result()[0]->company_files);
        }

        //echo json_encode($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
}