<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
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

    public function get_firm_info(){
        //$q = $this->db->get('firm');
        //$q = $this->db->get_where('firm',array('firm_id'=>$this->session->userdata('firm_id')));
        /*$this->db->select('firm.*, user_firm.user_id, user_firm.default_company, user_firm.in_use')
                ->from('firm')
                ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
                ->where('user_firm.user_id = '.$this->session->userdata('user_id'));*/
        $this->db->select('firm.*')
                ->from('firm')
                ->where('id = '.$this->session->userdata('firm_id'));
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_firm_info($search = null){

        if($search == null)
        {
            $this->db->select('firm.*, firm_telephone.telephone, firm_fax.fax, firm_email.email, user_firm.user_id, user_firm.default_company, user_firm.in_use')
                ->from('firm')
                ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
                ->join('firm_telephone', 'firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1', 'left')
                ->join('firm_fax', 'firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1', 'left')
                ->join('firm_email', 'firm_email.firm_id = firm.id AND firm_email.primary_email = 1', 'left')
                ->where('user_firm.user_id = '.$this->session->userdata('user_id'));
        }
        else
        {
            $this->db->select('firm.*, firm_telephone.telephone, firm_fax.fax, firm_email.email, user_firm.user_id, user_firm.default_company, user_firm.in_use');
            $this->db->from('firm');
            $this->db->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left');
            $this->db->join('firm_telephone', 'firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1', 'left');
            $this->db->join('firm_fax', 'firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1', 'left');
            $this->db->join('firm_email', 'firm_email.firm_id = firm.id AND firm_email.primary_email = 1', 'left');
            $this->db->where('user_firm.user_id = '.$this->session->userdata('user_id'));
            $this->db->group_start();
                $this->db->or_like('firm.name',$search);
                $this->db->or_like('firm_telephone.telephone',$search);
                $this->db->or_like('firm_fax.fax',$search);
                $this->db->or_like('firm_email.email',$search);
            $this->db->group_end();
        }

        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_bank_info($id){
        $q = $this->db->query("select bank_info.* from bank_info where firm_id = '".$id."'");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_our_service_info($id){
        $q = $this->db->query("select our_service_info.*, billing_info_service_category.category_description as service_type_name, unit_pricing.unit_pricing_name, our_service_registration_address.postal_code, our_service_registration_address.street_name, our_service_registration_address.building_name, our_service_registration_address.unit_no1, our_service_registration_address.unit_no2 from our_service_info left join billing_info_service_category on billing_info_service_category.id = our_service_info.service_type left join unit_pricing on unit_pricing.id = our_service_info.unit_pricing left join our_service_registration_address on our_service_registration_address.our_service_info_id = our_service_info.id where user_admin_code_id = '".$id."'");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $key => $row) {
                $data[] = $row;
                $data[$key]->index = $key;
            }
            return $data;
        }
        return FALSE;
    }

    public function edit_firm_info($id){
        //$q = $this->db->get('firm');
        //$q = $this->db->get_where('firm',array('id'=>$id));

        $q = $this->db->query("select firm.*, GROUP_CONCAT(DISTINCT CONCAT(firm_telephone.id,',', firm_telephone.telephone, ',', firm_telephone.primary_telephone)SEPARATOR ';') AS 'firm_telephone', GROUP_CONCAT(DISTINCT CONCAT(firm_fax.id,',', firm_fax.fax, ',', firm_fax.primary_fax)SEPARATOR ';') AS 'firm_fax', GROUP_CONCAT(DISTINCT CONCAT(firm_email.id,',', firm_email.email, ',', firm_email.primary_email)SEPARATOR ';') AS 'firm_email' from firm left join firm_telephone on firm_telephone.firm_id = firm.id left join firm_fax on firm_fax.firm_id = firm.id left join firm_email on firm_email.firm_id = firm.id where firm.id = '".$id."' ORDER BY firm_telephone.primary_telephone DESC, firm_fax.primary_fax DESC, firm_email.primary_email DESC");

        //echo json_encode($q->result()[0]->files);
        if($q->result()[0]->firm_telephone != null)
        {
            $q->result()[0]->firm_telephone = explode(';', $q->result()[0]->firm_telephone);
        }

        if($q->result()[0]->firm_fax != null)
        {
            $q->result()[0]->firm_fax = explode(';', $q->result()[0]->firm_fax);
        }

        if($q->result()[0]->firm_email != null)
        {
            $q->result()[0]->firm_email = explode(';', $q->result()[0]->firm_email);
        }

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_company_share_type($company_code){

        $q = $this->db->query("select share_capital.class_id,share_capital.other_class, share_capital.id, share_capital.company_code,  share_capital.currency_id, class.sharetype, currencies.currency from client_member_share_capital as share_capital left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where company_code='".$company_code."'");

        //echo json_encode($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_filing_data($company_code) 
    {
        $get_all_filing_data = $this->db->query("select filing.*, financial_year_period.period from filing left join financial_year_period on financial_year_period.id = filing.financial_year_period_id where company_code='".$company_code."' order by id");

        //echo json_encode($q->result());

        if ($get_all_filing_data->num_rows() > 0) {
            foreach (($get_all_filing_data->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_eci_filing_data($company_code) 
    {
        $get_all_eci_filing_data = $this->db->query("select eci_filing.* from eci_filing where company_code='".$company_code."' order by id");

        //echo json_encode($q->result());

        if ($get_all_eci_filing_data->num_rows() > 0) {
            foreach (($get_all_eci_filing_data->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_tax_filing_data($company_code) 
    {
        $get_all_tax_filing_data = $this->db->query("select tax_filing.* from tax_filing where company_code='".$company_code."' order by id");

        //echo json_encode($q->result());

        if ($get_all_tax_filing_data->num_rows() > 0) {
            foreach (($get_all_tax_filing_data->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_non_exist_company_share_type($id, $company_code){
        /*$this->db->where('company_code', $company_code);
        $q = $this->db->get('client_member_share_capital');*/
        /*$q = $this->db->query("select share_capital.*, class.sharetype, currencies.currency from client_member_share_capital as share_capital left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where not exists (select client_member_share_capital_id from allotment where client_member_share_capital_id = share_capital.id && client_member_share_capital_id != $id) AND company_code='".$company_code."'");*/

        $q = $this->db->query("select share_capital.*, class.sharetype, currencies.currency from client_member_share_capital as share_capital left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where company_code='".$company_code."'");

        //echo json_encode($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_exist_company_share_type($company_code){
        /*$this->db->where('company_code', $company_code);
        $q = $this->db->get('client_member_share_capital');*/
        $q = $this->db->query("select share_capital.*, class.sharetype, currencies.currency from client_member_share_capital as share_capital left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where exists (select client_member_share_capital_id from member_shares where client_member_share_capital_id = share_capital.id) AND company_code='".$company_code."'");

        //echo json_encode($q->result());

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
	public function get_all_chargee($company_code){
		$q = $this->db->get_where('client_charges',array('company_code'=>$company_code));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}

    public function get_all_client_share_capital($company_code){
        //$q = $this->db->get_where('client_member_share_capital',array('company_code'=>$company_code));
        $q = $this->db->query("select client_member_share_capital.*, member_shares.company_code, sum(member_shares.number_of_share) as number_of_shares, sum(member_shares.amount_share) as amount, sum(member_shares.amount_paid) as paid_up from client_member_share_capital left join member_shares on member_shares.client_member_share_capital_id = client_member_share_capital.id AND member_shares.company_code = client_member_share_capital.company_code where client_member_share_capital.company_code = '".$company_code."' group by client_member_share_capital.id");
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

	public function get_all_person($term = null,$type = null,$identification_no = null){
		$where = '';
        $officer_left_join = '';
        $member1_where = '';
        $officer1_where = '';
        $member2_where = '';
        $officer2_where = '';
        //echo ($type);

        /*$identification_no = $term;
        $register_no = $term;*/
        if($_SESSION['group_id'] == 4)
        {
            $officer_left_join = 'left join user_client on user_id = '.$this->session->userdata('user_id').' left join client on client.id = user_client.client_id join member_shares join client_officers';

            $member1_where = 'client.company_code = member_shares.company_code AND member_shares.officer_id = officer.id AND member_shares.field_type = officer.field_type';

            $officer1_where = ' OR client.company_code = client_officers.company_code AND client_officers.officer_id = officer.id AND client_officers.field_type = officer.field_type AND ';

            $officer_company_left_join = 'left join user_client on user_id = '.$this->session->userdata('user_id').' left join client on client.id = user_client.client_id join member_shares join client_officers';

            $member2_where = 'client.company_code = member_shares.company_code AND member_shares.officer_id = officer_company.id AND member_shares.field_type = officer_company.field_type';

            $officer2_where = ' OR client.company_code = client_officers.company_code AND client_officers.officer_id = officer_company.id AND client_officers.field_type = officer_company.field_type AND ';
        }

        if($type == "all")
        {
            if ($term)
            {
               // $p_where = ' where ';
                $p_where = ' name like "%'.$term.'%" AND ';

                //$r_where = ' where ';
                $r_where = ' company_name like "%'.$term.'%" AND ';
                //if ($type) $where .= ' and field_type like "%'.$type.'%" ';

                $p = $this->db->query("select officer.field_type, identification_no, name, non_verify, date_of_birth, officer_mobile_no.mobile_no as local_mobile, officer_email.email from officer ".$officer_left_join." left join officer_mobile_no on officer_mobile_no.officer_id = officer.id and officer_mobile_no.primary_mobile_no = 1 left join officer_email on officer_email.officer_id = officer.id and officer_email.primary_email = 1 where ".$p_where.$member1_where.$officer1_where."user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."' group by identification_no");

                $r = $this->db->query("select officer_company.field_type, register_no, officer_company.company_name, non_verify, officer_company_phone_number.phone_number as company_phone_number, officer_company_email.email as company_email from officer_company ".$officer_company_left_join." left join officer_company_email on officer_company_email.officer_company_id = officer_company.id and officer_company_email.primary_email = 1 left join officer_company_phone_number on officer_company_phone_number.officer_company_id = officer_company.id and officer_company_phone_number.primary_phone_number = 1 where ".$r_where.$member2_where.$officer2_where."user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."' group by register_no");

                $officer_info = $p->result_array();
                $officer_company_info = $r->result_array();

                $all = array_merge($officer_info, $officer_company_info);
                //$all = $p;

                if(count($all) == 0)
                {
                    /*$identification_no = $term;
                    if ($identification_no)
                    {*/
                        /*if ($where == '') $where = ' where identification_no like "%'.$identification_no.'%"'; else $where .= ' and identification_no like "%'.$identification_no.'%" ';*/
                        $where_p = ' identification_no like "%'.$term.'%" AND ';

                        $p = $this->db->query("select officer.field_type, identification_no, name, non_verify, date_of_birth, officer_mobile_no.mobile_no as local_mobile, officer_email.email from officer ".$officer_left_join." left join officer_mobile_no on officer_mobile_no.officer_id = officer.id and officer_mobile_no.primary_mobile_no = 1 left join officer_email on officer_email.officer_id = officer.id and officer_email.primary_email = 1 where ".$where_p.$member1_where.$officer1_where."user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."' group by identification_no");

                        $where_r = ' register_no like "%'.$term.'%" AND ';
                        $r = $this->db->query("select officer_company.field_type, register_no, officer_company.company_name, non_verify, officer_company_phone_number.phone_number as company_phone_number, officer_company_email.email as company_email from officer_company ".$officer_company_left_join." left join officer_company_email on officer_company_email.officer_company_id = officer_company.id and officer_company_email.primary_email = 1 left join officer_company_phone_number on officer_company_phone_number.officer_company_id = officer_company.id and officer_company_phone_number.primary_phone_number = 1 where".$where_r.$member2_where.$officer2_where."user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."' group by register_no");

                        $officer_info = $p->result_array();
                        $officer_company_info = $r->result_array();
                        $all = array_merge($officer_info, $officer_company_info);
                    //}
                }
            }
            else
            {
                /*$q = $this->db->query("select officer.field_type, officer.identification_no, officer.name, officer.date_of_birth, officer.local_mobile, officer.email, officer_company.field_type as officer_company_field_type, officer_company.register_no as officer_company_register_no, officer_company.company_name as officer_company_company_name from officer join officer_company ".$where."");*/
                
                
                $p = $this->db->query("select officer.field_type, identification_no, name, non_verify, date_of_birth, officer_mobile_no.mobile_no as local_mobile, officer_email.email from officer ".$officer_left_join." left join officer_mobile_no on officer_mobile_no.officer_id = officer.id and officer_mobile_no.primary_mobile_no = 1 left join officer_email on officer_email.officer_id = officer.id and officer_email.primary_email = 1 where ".$where.$member1_where.$officer1_where."officer.user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."' group by identification_no");

                $r = $this->db->query("select officer_company.field_type, register_no, officer_company.company_name, non_verify, officer_company_phone_number.phone_number as company_phone_number, officer_company_email.email as company_email from officer_company ".$officer_company_left_join." left join officer_company_email on officer_company_email.officer_company_id = officer_company.id and officer_company_email.primary_email = 1 left join officer_company_phone_number on officer_company_phone_number.officer_company_id = officer_company.id and officer_company_phone_number.primary_phone_number = 1 where ".$where.$member2_where.$officer2_where."officer_company.user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."' group by register_no");

                $officer_info = $p->result_array();
                $officer_company_info = $r->result_array();
                $all = array_merge($officer_info, $officer_company_info);
                    
                
            }
            

            if (count($all) > 0) {
                foreach (($all) as $row) {
                    $data[] = $row;
                }
                
                return $data;
            }
            return FALSE;
        }
        elseif($type == "individual")
        {
    		if ($term)
    		{
    			//$where = ' where ';
    			$where = ' name like "%'.$term.'%" ';
    			if ($type) $where .= ' and field_type like "%'.$type.'%" AND ';

                $q = $this->db->query("select officer.field_type, identification_no, name, non_verify, date_of_birth, officer_mobile_no.mobile_no as local_mobile, officer_email.email from officer ".$officer_left_join." left join officer_mobile_no on officer_mobile_no.officer_id = officer.id and officer_mobile_no.primary_mobile_no = 1 left join officer_email on officer_email.officer_id = officer.id and officer_email.primary_email = 1 where ".$where.$member1_where.$officer1_where."user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."' group by identification_no");

                if(count($q->result()) == 0)
                {
                    $identification_no = $term;
                    if ($identification_no)
                    {
                        /*if ($where == '') $where = ' where identification_no like "%'.$identification_no.'%"'; else $where .= ' and identification_no like "%'.$identification_no.'%" ';*/
                        $where = ' identification_no like "%'.$identification_no.'%" AND ';

                        $q = $this->db->query("select officer.field_type, identification_no, name, non_verify, date_of_birth, officer_mobile_no.mobile_no as local_mobile, officer_email.email from officer ".$officer_left_join." left join officer_mobile_no on officer_mobile_no.officer_id = officer.id and officer_mobile_no.primary_mobile_no = 1 left join officer_email on officer_email.officer_id = officer.id and officer_email.primary_email = 1 where ".$where.$member1_where.$officer1_where."user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."' group by identification_no");
                    }
                }
    		}
            else
            {
                $q = $this->db->query("select officer.field_type, identification_no, name, non_verify, date_of_birth, officer_mobile_no.mobile_no as local_mobile, officer_email.email from officer ".$officer_left_join." left join officer_mobile_no on officer_mobile_no.officer_id = officer.id and officer_mobile_no.primary_mobile_no = 1 left join officer_email on officer_email.officer_id = officer.id and officer_email.primary_email = 1 where ".$where.$member1_where.$officer1_where."user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."' group by identification_no");
            }

            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
                
                return $data;
            }
            return FALSE;
        }
        elseif($type == "company")
        {
            if ($term)
            {
                //$where = ' where ';
                $where = ' company_name like "%'.$term.'%" AND ';
                if ($type) $where .= ' and field_type like "%'.$type.'%" AND ';

                $q = $this->db->query("select officer_company.field_type, register_no, officer_company.company_name, non_verify, officer_company_phone_number.phone_number as company_phone_number, officer_company_email.email as company_email from officer_company ".$officer_company_left_join." left join officer_company_email on officer_company_email.officer_company_id = officer_company.id and officer_company_email.primary_email = 1 left join officer_company_phone_number on officer_company_phone_number.officer_company_id = officer_company.id and officer_company_phone_number.primary_phone_number = 1 where ".$where.$member2_where.$officer2_where."user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."' group by register_no");

                if(count($q->result()) == 0)
                {
                    $register_no = $term;
                    if ($register_no)
                    {
                        /*if ($where == '') $where = ' where register_no like "%'.$register_no.'%"'; else $where .= ' and register_no like "%'.$register_no.'%" ';*/
                        $where = ' register_no like "%'.$register_no.'%" AND ';
                        $q = $this->db->query("select officer_company.field_type, register_no, officer_company.company_name, non_verify, officer_company_phone_number.phone_number as company_phone_number, officer_company_email.email as company_email from officer_company ".$officer_company_left_join." left join officer_company_email on officer_company_email.officer_company_id = officer_company.id and officer_company_email.primary_email = 1 left join officer_company_phone_number on officer_company_phone_number.officer_company_id = officer_company.id and officer_company_phone_number.primary_phone_number = 1 where ".$where.$member2_where.$officer2_where."user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."' group by register_no");
                    }
                }
            }
            else
            {
                $q = $this->db->query("select officer_company.field_type, register_no, officer_company.company_name, non_verify, officer_company_phone_number.phone_number as company_phone_number, officer_company_email.email as company_email from officer_company ".$officer_company_left_join." left join officer_company_email on officer_company_email.officer_company_id = officer_company.id and officer_company_email.primary_email = 1 left join officer_company_phone_number on officer_company_phone_number.officer_company_id = officer_company.id and officer_company_phone_number.primary_phone_number = 1 where ".$where.$member2_where.$officer2_where."user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."' group by register_no");
            }
            
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
                //echo json_encode($data);
                return $data;
            }
            return FALSE;
        }
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
    public function get_all_allotment_to_view($search = null,$type = null,$company_code){

        /*$q = $this->db->query("select member_shares.*, member_shares_with_certificate.member_shares_id, member_shares_with_certificate.certificate_id, certificate.certificate_no, officer.id as officer_id, officer.field_type, officer.name, officer_company.id as officer_company_id, officer_company.field_type, officer_company.company_name from member_shares left join member_shares_with_certificate on member_shares_with_certificate.member_shares_id = member_shares.id left join certificate on certificate.id = member_shares_with_certificate.certificate_id left join officer on officer.id = member_shares.officer_id and officer.field_type = member_shares.field_type left join officer_company on officer_company.id = member_shares.officer_id and officer_company.field_type = member_shares.field_type where member_shares.  transaction_type = 'Allotment' AND member_shares.company_code = '".$company_code."'");*/

        $where = '';
        if($search)
        {
            //$where = ' where ';
            if ($type == "member_name")
            {
                $where .= ' AND officer.name like "%'.$search.'%" OR officer_company.company_name like "%'.$search.'%" OR client.company_name like "%'.$search.'%"';
            }
            elseif ($type == "class")
            {
                $where .= ' AND sharetype.sharetype like "%'.$search.'%" OR client_member_share_capital.other_class like "%'.$search.'%"';
            }
            elseif ($type == "certificate_number")
            {
                $where .= ' AND certificate.certificate_no like "%'.$search.'%"';
            }
            elseif($type == "all")
            {
                $where .= ' AND officer.name like "%'.$search.'%" OR officer_company.company_name like "%'.$search.'%" AND sharetype.sharetype like "%'.$search.'%" OR client_member_share_capital.other_class like "%'.$search.'%" AND certificate.certificate_no like "%'.$search.'%"';
                
            }

            
        }
        
        /*if($type == "all")
        {*/
           $q = $this->db->query("select member_shares.*, DATE_FORMAT(STR_TO_DATE(member_shares.transaction_date, '%d/%m/%Y'), '%Y/%m/%d') as transaction_date, client_member_share_capital.class_id, client_member_share_capital.other_class, sharetype.sharetype as sharetype_name, certificate.certificate_no, certificate.new_certificate_no, certificate.number_of_share as cert_number_of_share, certificate.amount_share as cert_amount_share, certificate.no_of_share_paid as cert_no_of_share_paid, certificate.amount_paid as cert_amount_paid, officer.id as officer_id, officer.field_type, officer.name, officer_company.id as officer_company_id, officer_company.field_type, officer_company.company_name, client.id as client_id, 'client' as client_field_type, client.company_name as client_company_name from member_shares left join certificate on certificate.officer_id = member_shares.officer_id and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id left join officer on officer.id = member_shares.officer_id and officer.field_type = member_shares.field_type left join officer_company on officer_company.id = member_shares.officer_id and officer_company.field_type = member_shares.field_type left join client_member_share_capital on member_shares.client_member_share_capital_id = client_member_share_capital.id left join sharetype on sharetype.id = client_member_share_capital.class_id left join client on client.id = member_shares.officer_id and member_shares.field_type = 'client' where member_shares.transaction_type = 'Allotment' AND member_shares.company_code = '".$company_code."'".$where." ORDER BY STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y'), member_shares.id"); 
        //}
        /*else if ($type == "member_name")
        {

        }*/
        

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_buyback_to_view($search = null,$type = null,$company_code){

        $where = '';
        if($search)
        {
            //$where = ' where ';
            if ($type == "member_name")
            {
                $where .= ' AND officer.name like "%'.$search.'%" OR officer_company.company_name like "%'.$search.'%" OR client.company_name like "%'.$search.'%"';
            }
            elseif ($type == "class")
            {
                $where .= ' AND sharetype.sharetype like "%'.$search.'%" OR client_member_share_capital.other_class like "%'.$search.'%"';
            }
            elseif ($type == "certificate_number")
            {
                $where .= ' AND certificate.certificate_no like "%'.$search.'%"';
            }
            elseif($type == "all")
            {
                $where .= ' AND officer.name like "%'.$search.'%" OR officer_company.company_name like "%'.$search.'%" AND sharetype.sharetype like "%'.$search.'%" OR client_member_share_capital.other_class like "%'.$search.'%" AND certificate.certificate_no like "%'.$search.'%"';
                
            }

            
        }
        $q = $this->db->query("select member_shares.*, DATE_FORMAT(STR_TO_DATE(member_shares.transaction_date, '%d/%m/%Y'), '%Y/%m/%d') as transaction_date, client_member_share_capital.class_id, client_member_share_capital.other_class, sharetype.sharetype as sharetype_name, certificate.certificate_no, officer.id as officer_id, officer.field_type, officer.name, officer_company.id as officer_company_id, officer_company.field_type, officer_company.company_name, client.id as client_id, 'client' as client_field_type, client.company_name as client_company_name from member_shares left join certificate on certificate.officer_id = member_shares.officer_id and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id left join officer on officer.id = member_shares.officer_id and officer.field_type = member_shares.field_type left join officer_company on officer_company.id = member_shares.officer_id and officer_company.field_type = member_shares.field_type left join client_member_share_capital on member_shares.client_member_share_capital_id = client_member_share_capital.id left join sharetype on sharetype.id = client_member_share_capital.class_id left join client on client.id = member_shares.officer_id and member_shares.field_type = 'client' where member_shares.transaction_type = 'Buyback' AND member_shares.company_code = '".$company_code."'".$where." ORDER BY STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y'), member_shares.id");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

     public function get_all_transfer_to_view($search = null,$type = null,$company_code){

        $where = '';
        if($search)
        {
            //$where = ' where ';
            if ($type == "member_name")
            {
                $where .= ' AND officer.name like "%'.$search.'%" OR officer_company.company_name like "%'.$search.'%" OR client.company_name like "%'.$search.'%"';
            }
            elseif ($type == "class")
            {
                $where .= ' AND sharetype.sharetype like "%'.$search.'%" OR client_member_share_capital.other_class like "%'.$search.'%"';
            }
            elseif ($type == "certificate_number")
            {
                $where .= ' AND certificate.certificate_no like "%'.$search.'%"';
            }
            elseif($type == "all")
            {
                $where .= ' AND officer.name like "%'.$search.'%" OR officer_company.company_name like "%'.$search.'%" AND sharetype.sharetype like "%'.$search.'%" OR client_member_share_capital.other_class like "%'.$search.'%" AND certificate.certificate_no like "%'.$search.'%"';
                
            }

            
        }

        $q = $this->db->query("select member_shares.*, DATE_FORMAT(STR_TO_DATE(member_shares.transaction_date, '%d/%m/%Y'), '%Y/%m/%d') as transaction_date, client_member_share_capital.class_id, client_member_share_capital.other_class, sharetype.sharetype as sharetype_name, certificate.certificate_no, officer.id as officer_id, officer.field_type, officer.name, officer_company.id as officer_company_id, officer_company.field_type, officer_company.company_name, client.id as client_id, 'client' as client_field_type, client.company_name as client_company_name from member_shares left join certificate on certificate.officer_id = member_shares.officer_id and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id left join officer on officer.id = member_shares.officer_id and officer.field_type = member_shares.field_type left join officer_company on officer_company.id = member_shares.officer_id and officer_company.field_type = member_shares.field_type left join client_member_share_capital on member_shares.client_member_share_capital_id = client_member_share_capital.id left join sharetype on sharetype.id = client_member_share_capital.class_id left join client on client.id = member_shares.officer_id and member_shares.field_type = 'client' where member_shares.number_of_share != 0 AND member_shares.transaction_type = 'Transfer' AND member_shares.company_code = '".$company_code."'".$where." ORDER BY STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y'), member_shares.id");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_edit_allotment_group($transaction_id, $company_code){
        /*$q = $this->db->query('select currency,unique_code,sharetype_allotment,sum(allotment_share) as allotment_share,sum(allotment_share_amount) as allotment_share_amount from allotment where unique_code="'.$unique_code.'" group by currency');*/

        /*$q = $this->db->query("select share_capital.*, class.sharetype, currencies.currency from client_member_share_capital as share_capital left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where company_code='".$company_code."'");*/

       /*$q = $this->db->query('select allotment.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from allotment left join officer on allotment.officer_id = officer.id and allotment.field_type = officer.field_type left join officer_company on allotment.officer_id = officer_company.id and allotment.field_type = officer_company.field_type left join client_member_share_capital as share_capital on allotment.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where allotment.company_code="'.$company_code.'" AND allotment.client_member_share_capital_id = "'.$client_member_share_capital_id.'"');*/

        $q = $this->db->query("select member_shares.*, certificate.id as cert_id, certificate.certificate_no, certificate.new_certificate_no, officer.id as officer_id, officer.field_type as officer_field_type, officer.identification_no, officer.name, officer_company.id as officer_company_id, officer_company.register_no, officer_company.field_type as officer_company_field_type, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency, client.id as client_company_id, client.registration_no, 'client' as client_company_field_type, client.company_name as client_company_name from member_shares left join certificate on certificate.officer_id = member_shares.officer_id and certificate.company_code = member_shares.company_code and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id left join officer on officer.id = member_shares.officer_id and officer.field_type = member_shares.field_type left join officer_company on officer_company.id = member_shares.officer_id and officer_company.field_type = member_shares.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = 'client' where member_shares.transaction_type = 'Allotment' AND member_shares.transaction_id = '".$transaction_id."' AND member_shares.company_code = '".$company_code."' ORDER BY member_shares.id");

        //return($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_edit_buyback_group($transaction_id, $company_code){
        $q = $this->db->query("select member_shares.*, member_shares.officer_id as member_officer_id, certificate.id as cert_id, certificate.certificate_no, officer.id as officer_id, officer.field_type as officer_field_type, officer.identification_no, officer.name, officer_company.id as officer_company_id, officer_company.register_no, officer_company.field_type as officer_company_field_type, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency, client.id as client_company_id, client.registration_no, 'client' as client_company_field_type, client.company_name as client_company_name from member_shares left join certificate on certificate.officer_id = member_shares.officer_id and certificate.company_code = member_shares.company_code and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id left join officer on officer.id = member_shares.officer_id and officer.field_type = member_shares.field_type left join officer_company on officer_company.id = member_shares.officer_id and officer_company.field_type = member_shares.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = 'client' where member_shares.transaction_type = 'Buyback' AND member_shares.transaction_id = '".$transaction_id."' AND member_shares.company_code = '".$company_code."' ORDER BY member_shares.id");

        //return($q->result());

        if ($q->num_rows() > 0) {

            //$buyback_info = $q->result_array();
            foreach (($q->result()) as $rows) {
                $member_shares = $q = $this->db->query("select sum(member_shares.number_of_share) as number_of_share from member_shares where member_shares.officer_id = '".$rows->member_officer_id."' and member_shares.field_type = '".$rows->field_type."' AND member_shares.company_code = '".$company_code."' AND STR_TO_DATE('".$rows->transaction_date."', '%d/%m/%Y') > STR_TO_DATE(member_shares.transaction_date, '%d/%m/%Y') ORDER BY member_shares.id");

                $member_shares = $member_shares->result_array();

                //foreach (($q->result()) as $row) {
                    $rows->certificate_number_of_share = $member_shares[0]['number_of_share'];
                    $data[] = $rows;
                //}
            }
            return $data;
        }
        return FALSE;
    }

    public function get_edit_transfer_group($transaction_id, $company_code){

        $q = $this->db->query("select member_shares.*, certificate.id as cert_id, certificate.certificate_no, certificate.new_certificate_no, officer.id as officers_id, officer.field_type as officer_field_type, officer.identification_no, officer.name, officer_company.id as officer_company_id, officer_company.register_no, officer_company.field_type as officer_company_field_type, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency, client.id as client_company_id, client.registration_no, 'client' as client_company_field_type, client.company_name as client_company_name from member_shares left join certificate on certificate.officer_id = member_shares.officer_id and certificate.company_code = member_shares.company_code and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id left join officer on officer.id = member_shares.officer_id and officer.field_type = member_shares.field_type left join officer_company on officer_company.id = member_shares.officer_id and officer_company.field_type = member_shares.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = 'client' where member_shares.transaction_type = 'Transfer' AND member_shares.transaction_id = '".$transaction_id."' AND member_shares.company_code = '".$company_code."' ORDER BY member_shares.id");

        //return($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }


	public function get_all_allotment_group($company_code){
		/*$q = $this->db->query('select currency,unique_code,sharetype_allotment,sum(allotment_share) as allotment_share,sum(allotment_share_amount) as allotment_share_amount from allotment where unique_code="'.$unique_code.'" group by currency');*/

        /*$q = $this->db->query("select share_capital.*, class.sharetype, currencies.currency from client_member_share_capital as share_capital left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where company_code='".$company_code."'");*/
        $q = $this->db->query('select allotment.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, share_capital.id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from allotment left join officer on allotment.officer_id = officer.id and allotment.field_type = officer.field_type left join officer_company on allotment.officer_id = officer_company.id and allotment.field_type = officer_company.field_type left join client_member_share_capital as share_capital on allotment.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where allotment.company_code="'.$company_code.'"');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}

    public function get_all_member($company_code){
        //$client_member_share_capital_id = $_POST["client_member_share_capital_id"];
        $company_code = $company_code;

        //echo json_encode($client_member_share_capital_id);
        
        $q = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id HAVING sum(member_shares.number_of_share) != 0');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_member_certificate($company_code){
        //$client_member_share_capital_id = $_POST["client_member_share_capital_id"];
        $company_code = $company_code;

        //echo json_encode($client_member_share_capital_id);

        $q = $this->db->query('select certificate.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from certificate left join officer on certificate.officer_id = officer.id and certificate.field_type = officer.field_type left join officer_company on certificate.officer_id = officer_company.id and certificate.field_type = officer_company.field_type left join client_member_share_capital as share_capital on certificate.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = certificate.officer_id and certificate.field_type = "client" where certificate.company_code="'.$company_code.'" AND certificate.status = 1 AND number_of_share != 0');
        
        /*$q = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id');*/

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

    public function get_all_template_data($company_code)
    {
        // $q = $this->db->query('select billing_template.*, billing_info_frequency.frequency as frequency_name from billing_template left join billing_info_frequency on billing_template.frequency = billing_info_frequency.id where firm_id = "'.$this->session->userdata("firm_id").'" AND billing_template.service != 13 order by id');

        // if ($q->num_rows() > 0) 
        // {
        //     foreach (($q->result()) as $row) 
        //     {
        //         $data[] = $row;
        //     }

        //     for($a = 0; $a < count($data); $a++)
        //     {
        //         $query_id = array();

        //         $query_id[0]["client_billing_info_id"] = $data[$a]->id;

        //         $data[$a] = (object) array_merge((array) $data[$a], (array)$query_id[0]);

        //         if($data[$a]->frequency_name == "Annually" || $data[$a]->frequency_name == "Bi-annually")
        //         {
        //             $query = $this->db->query('select incorporation_date from client where company_code ="'.$company_code.'" AND firm_id = "'.$this->session->userdata("firm_id").'"');

        //             $query = $query->result_array();

        //             $query[0]["from"] = $query[0]["incorporation_date"];

        //             $query[0]["to"] = '';

        //             $query[0]["days"] = '';

        //             $query[0]["from_billing_cycle"] = '';

        //             $query[0]["to_billing_cycle"] = '';

        //             $data[$a] = (object) array_merge((array) $data[$a], (array)$query[0]);
        //         }
        //         else
        //         {
        //             $query = array();

        //             $query[0]["from"] = '';

        //             $query[0]["to"] = '';

        //             $query[0]["days"] = '';

        //             $query[0]["from_billing_cycle"] = '';

        //             $query[0]["to_billing_cycle"] = '';

        //             $data[$a] = (object) array_merge((array) $data[$a], (array)$query[0]);
        //         }
        //     }
            
        // }

        $q = $this->db->query('select our_service_info.* from our_service_info where user_admin_code_id = "'.$this->session->userdata("user_admin_code_id").'" order by id');

        if ($q->num_rows() > 0) 
        {
            foreach (($q->result()) as $row) 
            {
                $data[] = $row;
            }

            for($a = 0; $a < count($data); $a++)
            {
                $query_id = array();

                $query_id[0]["client_billing_info_id"] = $data[$a]->id;

                $data[$a] = (object) array_merge((array) $data[$a], (array)$query_id[0]);

                // if($data[$a]->frequency_name == "Annually" || $data[$a]->frequency_name == "Bi-annually")
                // {
                //     $query = $this->db->query('select incorporation_date from client where company_code ="'.$company_code.'" AND firm_id = "'.$this->session->userdata("firm_id").'"');

                //     $query = $query->result_array();

                //     $query[0]["from"] = $query[0]["incorporation_date"];

                //     $query[0]["to"] = '';

                //     $query[0]["days"] = '';

                //     $query[0]["from_billing_cycle"] = '';

                //     $query[0]["to_billing_cycle"] = '';

                //     $data[$a] = (object) array_merge((array) $data[$a], (array)$query[0]);
                // }
                // else
                // {
                    $query = array();

                    $query[0]["service"] = $data[$a]->id;

                    $query[0]["from"] = '';

                    $query[0]["to"] = '';

                    $query[0]["days"] = '';

                    $query[0]["from_billing_cycle"] = '';

                    $query[0]["to_billing_cycle"] = '';

                    $data[$a] = (object) array_merge((array) $data[$a], (array)$query[0]);
                //}
            }
            
        }
        return $data;
    }

    public function get_all_default_client_service()
    {   
        $q = $this->db->query('select our_service_info.*, our_service_info.id as service from our_service_info where user_admin_code_id = "'.$this->session->userdata('user_admin_code_id').'" and display_in_se_id = 1 order by id');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_all_client_billing_info($company_code)
    {   
        /*if ($company_code)
        {*/
            $q = $this->db->query('select client_billing_info.*, our_service_info.service_name from client_billing_info left join our_service_info on client_billing_info.service = our_service_info.id and our_service_info.user_admin_code_id = "'.$this->session->userdata('user_admin_code_id').'" where company_code ="'.$company_code.'" and client_billing_info.deleted = 0 order by client_billing_info_id');

            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
                return $data;
            }
            /*else
            {
                $q = $this->db->query('select billing_template.*, billing_info_frequency.frequency as frequency_name from billing_template left join billing_info_frequency on billing_template.frequency = billing_info_frequency.id where firm_id = "'.$this->session->userdata("firm_id").'" AND billing_template.service != "13" order by id');

                if ($q->num_rows() > 0) {
                    //$template = $q->result_array();
                    
                    foreach (($q->result()) as $row) {
                        $data[] = $row;
                    }

                    for($a = 0; $a < count($data); $a++)
                    {
                        $query_id = array();

                        $query_id[0]["client_billing_info_id"] = $data[$a]->id;

                        $data[$a] = (object) array_merge((array) $data[$a], (array)$query_id[0]);

                        if($data[$a]->frequency_name == "Annually" || $data[$a]->frequency_name == "Bi-annually")
                        {
                            $query = $this->db->query('select incorporation_date from client where company_code ="'.$company_code.'"');

                            $query = $query->result_array();

                            $query[0]["from"] = $query[0]["incorporation_date"];

                            $query[0]["to"] = '';

                            $data[$a] = (object) array_merge((array) $data[$a], (array)$query[0]);
                            //array_merge($data[$a], $query[0]["from"]);
                        }
                        else
                        {
                            $query = array();

                            $query[0]["from"] = '';

                            $query[0]["to"] = '';

                            $data[$a] = (object) array_merge((array) $data[$a], (array)$query[0]);
                        }
                    }
                    return $data;
                }
            }*/
        //}
        return false;
    }

    public function get_all_client_signing_info($company_code)
    {   
        if ($company_code)
        {
            $q = $this->db->query('select * from client_signing_info where company_code ="'.$company_code.'"');

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

    public function get_all_client_contact_info($company_code)
    {
        if ($company_code)
        {
            $q = $this->db->query("select client_contact_info.*, GROUP_CONCAT(DISTINCT CONCAT(client_contact_info_phone.id,',', client_contact_info_phone.phone, ',', client_contact_info_phone.primary_phone)SEPARATOR ';') AS 'client_contact_info_phone', GROUP_CONCAT(DISTINCT CONCAT(client_contact_info_email.id,',', client_contact_info_email.email, ',', client_contact_info_email.primary_email)SEPARATOR ';') AS 'client_contact_info_email' from client_contact_info LEFT JOIN client_contact_info_phone ON client_contact_info_phone.client_contact_info_id = client_contact_info.id LEFT JOIN client_contact_info_email ON client_contact_info_email.client_contact_info_id = client_contact_info.id where company_code ='".$company_code."'");

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
        }
        return false;
    }

    public function get_all_client_reminder_info($company_code)
    {
        if ($company_code)
        {
            $q = $this->db->query("select client_setup_reminder.*, reminder_tag.reminder_tag_name from client_setup_reminder left join reminder_tag on reminder_tag.id = client_setup_reminder.selected_reminder where client_setup_reminder.company_code = '".$company_code."'");

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
	
	
}
