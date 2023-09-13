<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

	public function save_share($data = array()){
		if ($this->db->insert('sharetype', $data)) {
            $cid = $this->db->insert_id();
            return $cid;
        }
        return false;
	}
	public function edit_share($data = array(),$id){
		$this->db->where('id', $id);
        if ($this->db->update('sharetype', $data)) {
            return true;
        }
        return false;
	}
	public function remove_share($id){
        if ($this->db->delete('sharetype', array('id' => $id))) {
            return true;
        }
        return false;
	}
	public function get_all_share_type($data = array()){
		$q = $this->db->get('sharetype');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function save_currency($data = array()){
		if ($this->db->insert('currency', $data)) {
            $cid = $this->db->insert_id();
            return $cid;
        }
        return false;
	}
	public function edit_currency($data = array(),$id){
		$this->db->where('id', $id);
        if ($this->db->update('currency', $data)) {
            return true;
        }
        return false;
	}
	public function remove_currency($id){
        if ($this->db->delete('currency', array('id' => $id))) {
            return true;
        }
        return false;
	}
	public function get_all_currency($data = array()){
		$q = $this->db->get('currency');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
		
	public function save_kolom($data = array()){
		if ($this->db->insert('kolom', $data)) {
            $cid = $this->db->insert_id();
            return $cid;
        }
        return false;
	}
	public function edit_kolom($data = array(),$id){
		$this->db->where('id', $id);
        if ($this->db->update('kolom', $data)) {
            return true;
        }
        return false;
	}
	public function remove_kolom($id){
        if ($this->db->delete('kolom', array('id' => $id))) {
            return true;
        }
        return false;
	}
	public function get_all_kolom($data = array()){
		$q = $this->db->get('kolom');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function save_citizen($data = array()){
		if ($this->db->insert('citizen', $data)) {
            $cid = $this->db->insert_id();
            return $cid;
        }
        return false;
	}
	public function edit_citizen($data = array(),$id){
		$this->db->where('id', $id);
        if ($this->db->update('citizen', $data)) {
            return true;
        }
        return false;
	}
	public function remove_citizen($id){
        if ($this->db->delete('citizen', array('id' => $id))) {
            return true;
        }
        return false;
	}
	public function get_all_citizen($data = array()){
		$q = $this->db->get('citizen');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function save_typeofdoc($data = array()){
		if ($this->db->insert('typeofdoc', $data)) {
            $cid = $this->db->insert_id();
            return $cid;
        }
        return false;
	}
	public function edit_typeofdoc($data = array(),$id){
		$this->db->where('id', $id);
        if ($this->db->update('typeofdoc', $data)) {
            return true;
        }
        return false;
	}
	public function remove_typeofdoc($id){
        if ($this->db->delete('typeofdoc', array('id' => $id))) {
            return true;
        }
        return false;
	}
	public function get_all_typeofdoc($data = array()){
		$q = $this->db->get('typeofdoc');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function save_doccategory($data = array()){
		if ($this->db->insert('doccategory', $data)) {
            $cid = $this->db->insert_id();
            return $cid;
        }
        return false;
	}
	public function edit_doccategory($data = array(),$id){
		$this->db->where('id', $id);
        if ($this->db->update('doccategory', $data)) {
            return true;
        }
        return false;
	}
	public function remove_doccategory($id){
        if ($this->db->delete('doccategory', array('id' => $id))) {
            return true;
        }
        return false;
	}
	public function get_all_doccategory($data = array()){
		$q = $this->db->get('doccategory');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function get_draft_add_company($keyword){
		$this->db->select('*')
            ->order_by('id', 'desc');
		$this->db->where("status = 0");
		$this->db->group_start();
		$this->db->like('unique_code',$keyword);
		$this->db->group_end();
		$q = $this->db->get('officer');
		// $q = $this->db->get_where('officer',array('status'=>0), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function get_all_issued_sharetype($unique_code){
		$q = $this->db->get_where('issued_sharetype',array('unique_code'=>$unique_code));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function get_all_paid_share($unique_code){
		$q = $this->db->get_where('paid_share',array('unique_code'=>$unique_code));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function get_all_member_capital($unique_code){
		$q = $this->db->get_where('member_capital',array('unique_code'=>$unique_code));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function get_all_chargee($unique_code){
		$q = $this->db->get_where('chargee',array('unique_code'=>$unique_code));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function get_typeofdoc($unique_code){
		$q = $this->db->get_where('client_others',array('unique_code'=>$unique_code));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function get_all_service($data = array()){
		$q = $this->db->get('service');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function save_service($data = array()){
		
		if ($this->db->insert('service', $data)) {
            $cid = $this->db->insert_id();
            return $cid;
        }
        return false;
	}
	public function edit_service($data = array(),$id){
		$this->db->where('id', $id);
        if ($this->db->update('service', $data)) {
            return true;
        }
        return false;
	}
	public function remove_service($id){
        if ($this->db->delete('service', array('id' => $id))) {
            return true;
        }
        return false;
	}

	public function get_all_person($term = null,$type = null,$gid = null){
		$where = '';
		if ($term)
		{
			$where = ' where ';
			$where .= ' nama like "%'.$term.'%" ';
			if ($type) $where .= ' and tipe like "%'.$type.'%" ';
		}
		if ($gid)
		{
			if ($where == '') $where = ' where gid like "%'.$gid.'%"'; else $where .= ' and gid like "%'.$gid.'%" ';
		}
        $q = $this->db->query("select gid,nama,address,alternate_address,nationality,citizen, date_of_birth, phone,email,tipe from officer ".$where." group by gid");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function get_all_allotment($unique_code){
		$q = $this->db->get_where('allotment',array('unique_code'=>$unique_code));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function get_all_allotment_group($unique_code){
		$q = $this->db->query('select currency,unique_code,sharetype_allotment,sum(allotment_share) as allotment_share,sum(allotment_share_amount) as allotment_share_amount from allotment where unique_code="'.$unique_code.'" group by currency');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function get_allotment_member($id){
		$q = $this->db->get_where('allotment_member',array('id_allotment'=>$id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function get_all_alotment_member($unique_code){
		$q = $this->db->get_where('allotment_member',array('unique_code'=>$unique_code));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function get_all_allot_members($unique_code,$sharetype,$currency){
		$q = $this->db->query('select B.id, currency,sharetype_allotment,gid,nama,share_allotment,amount_allotment,sharepaid_allotment,amountpaid_allotment,certificate_allotment from allotment A,allotment_member B where A.id = B.id_allotment and A.unique_code = "'.$unique_code.'" and A.sharetype_allotment='.$sharetype.' and A.currency='.$currency);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function get_all_certificate($unique_code){
		$q = $this->db->query('select B.id, tgl,gid,nama,certificate_allotment,share_allotment from allotment A,allotment_member B where A.id = B.id_allotment and A.unique_code = "'.$unique_code.'"');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function get_all_client_service($unique_code = null){
		if ($unique_code)
		{
			$q = $this->db->get_where('client_service',array('unique_code'=>$unique_code));
		} else {
			$q = $this->db->get('client_service');
		}
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function get_all_client_setup($unique_code){
		$q = $this->db->get_where('client_setup',array('unique_code'=>$unique_code));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	
}
