<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Db_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function getLatestSales()
    {
        if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
        $this->db->order_by('id', 'desc');
        $q = $this->db->get("sales", 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getLastestQuotes()
    {
        if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
        $this->db->order_by('id', 'desc');
        $q = $this->db->get("quotes", 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getLatestPurchases()
    {
        if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
        $this->db->order_by('id', 'desc');
        $q = $this->db->get("purchases", 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getLatestTransfers()
    {
        if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
        $this->db->order_by('id', 'desc');
        $q = $this->db->get("trans", 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getLatestCustomers()
    {
        $this->db->order_by('id', 'desc');
        $q = $this->db->get_where("companies", array('group_name' => 'customer'), 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getLatestSuppliers()
    {
        $this->db->order_by('id', 'desc');
        $q = $this->db->get_where("companies", array('group_name' => 'supplier'), 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getChartData()
    {
        $myQuery = "SELECT S.month,
        COALESCE(S.sales, 0) as sales,
        COALESCE( P.purchases, 0 ) as purchases,
        COALESCE(S.tax1, 0) as tax1,
        COALESCE(S.tax2, 0) as tax2,
        COALESCE( P.ptax, 0 ) as ptax
        FROM (  SELECT  date_format(date, '%Y-%m') Month,
                SUM(total) Sales,
                SUM(product_tax) tax1,
                SUM(order_tax) tax2
                FROM " . $this->db->dbprefix('sales') . "
                WHERE date >= date_sub( now( ) , INTERVAL 12 MONTH )
                GROUP BY date_format(date, '%Y-%m')) S
            LEFT JOIN ( SELECT  date_format(date, '%Y-%m') Month,
                        SUM(product_tax) ptax,
                        SUM(order_tax) otax,
                        SUM(total) purchases
                        FROM " . $this->db->dbprefix('purchases') . "
                        GROUP BY date_format(date, '%Y-%m')) P
            ON S.Month = P.Month
            GROUP BY S.Month
            ORDER BY S.Month";
        $q = $this->db->query($myQuery);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getStockValue()
    {
        $q = $this->db->query("SELECT SUM(qty*price) as stock_by_price, SUM(qty*cost) as stock_by_cost
        FROM (
            Select sum(COALESCE(" . $this->db->dbprefix('warehouses_products') . ".quantity, 0)) as qty, price, cost
            FROM " . $this->db->dbprefix('products') . "
            JOIN " . $this->db->dbprefix('warehouses_products') . " ON " . $this->db->dbprefix('warehouses_products') . ".product_id=" . $this->db->dbprefix('products') . ".id
            GROUP BY " . $this->db->dbprefix('warehouses_products') . ".id ) a");
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getBestSeller($start_date = NULL, $end_date = NULL)
    {
        if (!$start_date) {
            $start_date = date('Y-m-d', strtotime('first day of this month')) . ' 00:00:00';
        }
        if (!$end_date) {
            $end_date = date('Y-m-d', strtotime('last day of this month')) . ' 23:59:59';
        }
        $sp = "( SELECT si.product_id, SUM( si.quantity ) soldQty, s.date as sdate from " . $this->db->dbprefix('sales') . " s JOIN " . $this->db->dbprefix('sale_items') . " si on s.id = si.sale_id where s.date >= '{$start_date}' and s.date < '{$end_date}' group by si.product_id ) PSales";
        $this->db
            ->select("CONCAT(" . $this->db->dbprefix('products') . ".name, ' (', " . $this->db->dbprefix('products') . ".code, ')') as name, COALESCE( PSales.soldQty, 0 ) as SoldQty", FALSE)
            ->from('products', FALSE)
            ->join($sp, 'products.id = PSales.product_id', 'left')
            ->order_by('PSales.soldQty desc')
            ->limit(10);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getUserList()
    {
        $q = $this->db->query('select * from '.$this->db->dbprefix('users'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

	public function getTask($id = NULL)
	{
		if ($id)
		{
			$q = $this->db->query('select * from user_task where assign_to like "%|'.$id.'\%"');
			if ($q->num_rows() > 0) {
				foreach (($q->result()) as $row) {
					$data[] = $row;
				}
				return $data;
			}
		} else {
			$q = $this->db->query('select * from user_task');
			if ($q->num_rows() > 0) {
				foreach (($q->result()) as $row) {
					$data[] = $row;
				}
				return $data;
			}
		}
		return false;
	}
	public function getUncompleteTask($id)
	{
		if ($id)
		{
			$q = $this->db->query('select sum(id) as total from user_task where status = 0 and assign_to like "%|'.$id.'\%"');
			if ($q->num_rows() > 0) {
				return $q->result()[0];
			}
		}
		return false;
	}
	public function getcompleteTask($id)
	{
		if ($id)
		{
			$q = $this->db->query('select sum(id) as total from user_task where status = 1 and  assign_to like "%|'.$id.'\%"');
			if ($q->num_rows() > 0) {
				return $q->result()[0];
			}
		}
		return false;
	}
	
	public function getOfficerGID($id = NULL)
	{
		if ($id)
		{
			$q = $this->db->query('select * from officer where gid ="'.$id.'"');
			if ($q->num_rows() > 0) {
				foreach (($q->result()) as $row) {
					$data[] = $row;
				}
				return $data;
			} else return false;
		} else {
			$q = $this->db->query('select * from officer');
			if ($q->num_rows() > 0) {
				foreach (($q->result()) as $row) {
					$data[] = $row;
				}
				return $data;
			}
		}
		return false;
	}
	public function getOfficerUC($id)
	{
		if ($id)
		{
            /*change on 5/3/2018 by justin*/
			/*$q = $this->db->query('select * from officer where unique_code ="'.$id.'"');*/
            $q = $this->db->query('select * from officer');
			if ($q->num_rows() > 0) {
				foreach (($q->result()) as $row) {
					$data[] = $row;
				}
				return $data;
			}
		}
		return false;
	}

    public function getClientOfficer($id)
    {   
        if ($id)
        {
            $q = $this->db->query('select client_officers.*, client_officers_position.position as position_name, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type left join client_officers_position on client_officers.position = client_officers_position.id where company_code ="'.$id.'"');
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
                return $data;
            }
        }
        return false;
    }

    public function get_all_director_retiring($company_code)
    {

        $q = $this->db->query('select client_officers.*, officer.identification_no, officer.name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type where position = 1 AND  date_of_cessation = "" AND company_code ="'.$company_code.'"');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }

        return false;
    }

    public function get_all_corp_rep($registration_no)
    {
         // $corp_rep_info = $this->db->query("select corporate_representative.*, client.company_name from corporate_representative LEFT JOIN client on client.id = corporate_representative.client_id and client.deleted = 0 where corporate_representative.registration_no = '".$registration_no."'");
        $corp_rep_info = $this->db->query("select corporate_representative.* from corporate_representative where corporate_representative.registration_no = '".$registration_no."'");

        if ($corp_rep_info->num_rows() > 0) {
            foreach (($corp_rep_info->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        
        return false;
    }

    public function getClientGuarantee($company_code)
    {
        if ($company_code)
        {
            $q = $this->db->query('select client_guarantee.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, currency.currency as currency_name from client_guarantee left join officer on client_guarantee.officer_id = officer.id and client_guarantee.field_type = officer.field_type left join officer_company on client_guarantee.officer_id = officer_company.id and client_guarantee.field_type = officer_company.field_type left join currency on currency.id = client_guarantee.currency_id where company_code ="'.$company_code.'"');
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
                return $data;
            }
        }
        return false;
    }

    public function getClientController($company_code)
    {
        if ($company_code)
        {
            $q = $this->db->query('select client_controller.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.company_name as client_company_name, client.registration_no from client_controller left join officer on client_controller.officer_id = officer.id and client_controller.field_type = officer.field_type left join officer_company on client_controller.officer_id = officer_company.id and client_controller.field_type = officer_company.field_type left join client on client.id = client_controller.officer_id AND client_controller.field_type = "client" where client_controller.company_code ="'.$company_code.'"');
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
                return $data;
            }
        }
        return false;
    }

    public function getSearchClientOfficer($position, $company_code)
    {
        $where = "";
        if($position == "all")
        {
            $where = "";
        }
        else if($position == "director")
        {
            $where = "client_officers_position.position = 'Director' AND";
        }
        else if($position == "ceo")
        {
            $where = "client_officers_position.position = 'CEO' AND";
        }
        else if($position == "manager")
        {
            $where = "client_officers_position.position = 'Manager' AND";
        }
        else if($position == "secretary")
        {
            $where = "client_officers_position.position = 'Secretary' AND";
        }
        else if($position == "auditor")
        {
            $where = "client_officers_position.position = 'Auditor' AND";
        }
        else if($position == "managing_director")
        {
            $where = "client_officers_position.position = 'Managing Director' AND";
        }
        else if($position == "alternate_director")
        {
            $where = "client_officers_position.position = 'Alternate Director' AND";
        }

        $q = $this->db->query('select client_officers.*, client_officers_position.position as position_name, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type left join client_officers_position on client_officers.position = client_officers_position.id where '.$where.' company_code ="'.$company_code.'"');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_all_unpaid_billings($type=NULL,$keyword=NULL,$start=NULL,$end=NULL)
    {

        /*$q = $this->db->query("select billing.*, client.company_name from billing left join client on client.company_code = billing.company_code where outstanding > 0");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }*/
        
        $this->db->select('billing.*, client.company_name, client.registration_no, client.former_name, currency.currency as currency_name, group_concat(receipt.receipt_no SEPARATOR "<br />") as receipt_no');
        $this->db->from('billing');
        $this->db->join('client', 'client.company_code = billing.company_code', 'left');
        $this->db->join('billing_receipt_record', 'billing_receipt_record.billing_id = billing.id', 'left');
        $this->db->join('receipt', 'receipt.id = billing_receipt_record.receipt_id', 'left');
        $this->db->join('currency', 'currency.id = billing.currency_id', 'left');
        if ($type != NULL)
        {
            if ($type != 'all')
            {
                $this->db->like($type, $keyword);
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('client_code', $keyword);
                    $this->db->or_like('registration_no', $keyword);
                    $this->db->or_like('company_name', $keyword);
                    $this->db->or_like('former_name', $keyword);
                $this->db->group_end();
            }
        }
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
        $this->db->group_by('billing.invoice_no'); 
        $this->db->order_by('id', 'asc');
        $this->db->where('billing.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('outstanding !=', 0);
        $this->db->where('billing.status !=', 1);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_paid_billings($type=NULL,$keyword=NULL,$start=NULL,$end=NULL)
    {

        /*$q = $this->db->query("select  from billing left join billing_receipt_record on billing_receipt_record.billing_id = billing.id left join receipt on receipt.id = billing_receipt_record.receipt_id left join client on client.company_code = billing.company_code left join payment_mode on payment_mode.id = receipt.payment_mode where outstanding <= 0 AND billing.firm_id = '". $this->session->userdata("firm_id")."'");*/

        // $q = $this->db->query("select billing.*, client.company_name from billing left join client on client.company_code = billing.company_code where outstanding <= 0 AND billing.user_id = '". $this->session->userdata("user_id")."'");
        $this->db->select('billing_receipt_record.receipt_id, billing_receipt_record.billing_id, billing_receipt_record.received, group_concat(receipt.receipt_no SEPARATOR "<br />") as receipt_no, billing.*, client.company_name, payment_mode.payment_mode, currency.currency as currency_name');
        $this->db->from('billing');
        $this->db->join('billing_receipt_record', 'billing_receipt_record.billing_id = billing.id', 'left');
        $this->db->join('receipt', 'receipt.id = billing_receipt_record.receipt_id', 'left');
        $this->db->join('client', 'client.company_code = billing.company_code', 'left');
        $this->db->join('payment_mode', 'payment_mode on payment_mode.id = receipt.payment_mode', 'left');
        $this->db->join('currency', 'currency.id = billing.currency_id', 'left');
        if ($type != NULL)
        {
            if ($type != 'all')
            {
                $this->db->like($type, $keyword);
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('client_code', $keyword);
                    $this->db->or_like('registration_no', $keyword);
                    $this->db->or_like('company_name', $keyword);
                    $this->db->or_like('former_name', $keyword);
                $this->db->group_end();
            }
        }
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
        $this->db->group_by('billing.invoice_no'); 
        $this->db->order_by('billing.id', 'asc');
        $this->db->where('billing.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('outstanding <=', 0);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_credit_note($type=NULL,$keyword=NULL,$start=NULL,$end=NULL)
    {
        /*$q = $this->db->query("select  from billing left join billing_receipt_record on billing_receipt_record.billing_id = billing.id left join receipt on receipt.id = billing_receipt_record.receipt_id left join client on client.company_code = billing.company_code left join payment_mode on payment_mode.id = receipt.payment_mode where billing.outstanding != billing.amount AND billing.firm_id = '". $this->session->userdata("firm_id")."'");*/


        $this->db->select('billing_credit_note_record.credit_note_id, billing_credit_note_record.billing_id, billing_credit_note_record.received, credit_note.id, credit_note.credit_note_no, credit_note_date, credit_note.total_amount_discounted, billing.*, client.company_name');
        $this->db->from('billing_credit_note_record');
        $this->db->join('billing', 'billing_credit_note_record.billing_id = billing.id and billing.outstanding != billing.amount and billing.status = 0', 'left');
        $this->db->join('credit_note', 'credit_note.id = billing_credit_note_record.credit_note_id', 'left');
        $this->db->join('client', 'client.company_code = billing.company_code', 'left');
        if ($type != NULL)
        {
            if ($type != 'all')
            {
                $this->db->like($type, $keyword);
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('client_code', $keyword);
                    $this->db->or_like('registration_no', $keyword);
                    $this->db->or_like('company_name', $keyword);
                    $this->db->or_like('former_name', $keyword);
                $this->db->group_end();
            }
        }
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
        $this->db->where('billing_credit_note_record.firm_id', $this->session->userdata("firm_id"));
        // $this->db->where('billing.outstanding != billing.amount');
        // $this->db->where('billing.status = 0');
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_receipt($type=NULL,$keyword=NULL,$start=NULL,$end=NULL)
    {
        /*$q = $this->db->query("select  from billing left join billing_receipt_record on billing_receipt_record.billing_id = billing.id left join receipt on receipt.id = billing_receipt_record.receipt_id left join client on client.company_code = billing.company_code left join payment_mode on payment_mode.id = receipt.payment_mode where billing.outstanding != billing.amount AND billing.firm_id = '". $this->session->userdata("firm_id")."'");*/


        $this->db->select('billing_receipt_record.receipt_id, billing_receipt_record.billing_id, billing_receipt_record.received, receipt.id, receipt.receipt_no, receipt_date, receipt.reference_no, receipt.payment_mode, receipt.total_amount_received, billing.*, client.company_name, payment_mode.payment_mode');
        $this->db->from('billing');
        $this->db->join('billing_receipt_record', 'billing_receipt_record.billing_id = billing.id', 'left');
        $this->db->join('receipt', 'receipt.id = billing_receipt_record.receipt_id', 'left');
        $this->db->join('client', 'client.company_code = billing.company_code', 'left');
        $this->db->join('payment_mode', 'payment_mode on payment_mode.id = receipt.payment_mode', 'left');
        if ($type != NULL)
        {
            if ($type != 'all')
            {
                $this->db->like($type, $keyword);
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('client_code', $keyword);
                    $this->db->or_like('registration_no', $keyword);
                    $this->db->or_like('company_name', $keyword);
                    $this->db->or_like('former_name', $keyword);
                $this->db->group_end();
            }
        }
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
        $this->db->order_by('billing.id', 'asc');
        $this->db->where('billing.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('billing.outstanding != billing.amount');
        $this->db->where('billing.status = 0');
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_recurring_billing($type=NULL,$keyword=NULL,$start=NULL,$end=NULL)
    {
        $this->db->select('recurring_billing.*, client.company_name, client.registration_no, client.former_name');
        $this->db->from('recurring_billing');
        $this->db->join('client', 'client.company_code = recurring_billing.company_code', 'left');
        if ($type != NULL)
        {
            if ($type != 'all')
            {
                $this->db->like($type, $keyword);
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('client_code', $keyword);
                    $this->db->or_like('registration_no', $keyword);
                    $this->db->or_like('company_name', $keyword);
                    $this->db->or_like('former_name', $keyword);
                $this->db->group_end();
            }
        }
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
        $this->db->order_by('id', 'asc');
        $this->db->where('recurring_billing.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('outstanding !=', 0);
        $this->db->where('recurring_billing.status !=', 1);
        
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_template()
    {

        $q = $this->db->query('select billing_template.*, billing_info_service.service as service_name from billing_template left join billing_info_service on billing_template.service = billing_info_service.id where firm_id = "'.$this->session->userdata("firm_id").'" AND billing_template.service != "13"');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }

        return false;
    }

    public function get_edit_recurring_bill($id)
    {
        $q = $this->db->query("select recurring_billing.*,client.company_name, client.company_code, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, currency.currency as currency_name from recurring_billing left join client on client.company_code = recurring_billing.company_code left join currency on recurring_billing.currency_id = currency.id where recurring_billing.id='".$id."'");


        if ($q->num_rows() > 0) {
            $this->session->set_userdata('billing_company_code', $q->result()[0]->company_code);
            $this->session->set_userdata('billing_currency', $q->result()[0]->currency_id);
            $this->session->set_userdata('billing_period', $q->result()[0]->billing_period);
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_edit_bill($id)
    {
        /*$q = $this->db->query("select billing.*,client.company_name, client.company_code, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, billing_service.billing_id, billing_service.client_billing_info_id, billing_service.invoice_description, billing_service.amount from billing left join client on client.company_code = billing.company_code left join billing_service on billing_service.billing_id = billing.id where billing.id='".$id."'");*/

        $q = $this->db->query("select billing.*,client.company_name, client.company_code, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, currency.currency as currency_name from billing left join client on client.company_code = billing.company_code left join currency on billing.currency_id = currency.id where billing.id='".$id."'");

        /*$q = $this->db->query("select client.company_name, client.company_code, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2 from client where company_code='".$company_code."'");

        $q = $q->result_array();

        $address = $q[0]["street_name"].'
#'.$q[0]["unit_no1"].$q[0]["unit_no2"].' '.$q[0]["building_name"].' 
Singapore '.$q[0]["postal_code"];

        $p = $this->db->query('select client_billing_info.*, billing_info_service.service as service_name, billing_info_frequency.frequency as frequency_name from client_billing_info left join billing_info_service on client_billing_info.service = billing_info_service.id left join billing_info_frequency on client_billing_info.frequency = billing_info_frequency.id where company_code ="'.$company_code.'"');*/

        if ($q->num_rows() > 0) {
            $this->session->set_userdata('billing_company_code', $q->result()[0]->company_code);
            $this->session->set_userdata('billing_currency', $q->result()[0]->currency_id);
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_edit_recurring_bill_service($id)
    {
        $q = $this->db->query("select recurring_billing.*, recurring_billing_service.id as recurring_service_id, recurring_billing_service.billing_id, recurring_billing_service.service, recurring_billing_service.invoice_description, recurring_billing_service.amount as recurring_service_amount, recurring_billing_service.gst_rate, recurring_billing_service.unit_pricing, recurring_billing_service.period_start_date, recurring_billing_service.period_end_date, our_service_info.service_name from recurring_billing left join recurring_billing_service on recurring_billing_service.billing_id = recurring_billing.id left join client_billing_info on client_billing_info.id = recurring_billing_service.service left join our_service_info on our_service_info.id = client_billing_info.service where recurring_billing.id='".$id."'");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_edit_bill_service($id)
    {
        // $q = $this->db->query("select billing.*, billing_service.id as billing_service_id, billing_service.billing_id, billing_service.client_billing_info_id, billing_service.invoice_description, billing_service.amount as billing_service_amount, billing_service.gst_rate, client_billing_info.service, billing_info_service.service as service_name from billing left join billing_service on billing_service.billing_id = billing.id left join client_billing_info on client_billing_info.company_code = billing.company_code AND client_billing_info.client_billing_info_id = billing_service.client_billing_info_id left join billing_info_service on client_billing_info.service = billing_info_service.id where billing.id='".$id."'");

        $q = $this->db->query("select billing.*, billing_service.id as billing_service_id, billing_service.billing_id, billing_service.service, billing_service.invoice_description, billing_service.amount as billing_service_amount, billing_service.gst_rate, billing_service.unit_pricing, billing_service.period_start_date, billing_service.period_end_date, our_service_info.service_name from billing left join billing_service on billing_service.billing_id = billing.id left join client_billing_info on client_billing_info.id = billing_service.service left join our_service_info on our_service_info.id = client_billing_info.service where billing.id='".$id."' ORDER BY billing_service.id");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_client_recurring_billing_info($id)
    {
        $get_billing_info = $this->db->query("select * from recurring_billing where id = '".$id."'");

        $get_billing_info = $get_billing_info->result_array();

        $q = $this->db->query("select client_billing_info.*, our_service_info.service_type, our_service_info.service_name, billing_info_service_category.category_description from client_billing_info left join our_service_info on our_service_info.id = client_billing_info.service left join billing_info_service_category on billing_info_service_category.id = our_service_info.service_type where client_billing_info.company_code = '".$get_billing_info[0]['company_code']."' and client_billing_info.currency = '".$get_billing_info[0]['currency_id']."' and client_billing_info.deleted = 0");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_client_billing_info($id)
    {
        // $q = $this->db->query("select billing.*,client_billing_info.*, billing_info_service.service as service_name from billing left join client_billing_info on billing.company_code = client_billing_info.company_code left join billing_info_service on client_billing_info.service = billing_info_service.id where billing.id='".$id."'");

        // $q = $this->db->query("select billing_info_service.*, billing_info_service.service as service_name, billing_info_service_category.category_description from billing_info_service left join billing_info_service_category on billing_info_service_category.category_code = billing_info_service.category_code order by billing_info_service.id");

        $get_billing_info = $this->db->query("select * from billing where id = '".$id."'");

        $get_billing_info = $get_billing_info->result_array();

        $q = $this->db->query("select client_billing_info.*, our_service_info.service_type, our_service_info.service_name, billing_info_service_category.category_description from client_billing_info left join our_service_info on our_service_info.id = client_billing_info.service left join billing_info_service_category on billing_info_service_category.id = our_service_info.service_type where client_billing_info.company_code = '".$get_billing_info[0]['company_code']."' and client_billing_info.currency = '".$get_billing_info[0]['currency_id']."' and client_billing_info.deleted = 0");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_history_client_billing_info($id)
    {
        // $q = $this->db->query("select billing.*,client_billing_info.*, billing_info_service.service as service_name from billing left join client_billing_info on billing.company_code = client_billing_info.company_code left join billing_info_service on client_billing_info.service = billing_info_service.id where billing.id='".$id."'");

        // $q = $this->db->query("select billing_info_service.*, billing_info_service.service as service_name, billing_info_service_category.category_description from billing_info_service left join billing_info_service_category on billing_info_service_category.category_code = billing_info_service.category_code order by billing_info_service.id");

        $get_billing_info = $this->db->query("select * from billing where id = '".$id."'");

        $get_billing_info = $get_billing_info->result_array();

        $q = $this->db->query("select client_billing_info.*, our_service_info.service_type, our_service_info.service_name, billing_info_service_category.category_description from client_billing_info left join our_service_info on our_service_info.id = client_billing_info.service left join billing_info_service_category on billing_info_service_category.id = our_service_info.service_type where client_billing_info.company_code = '".$get_billing_info[0]['company_code']."' and client_billing_info.currency = '".$get_billing_info[0]['currency_id']."' and client_billing_info.deleted = 0");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_service_category($id)
    {
        $selected_billing_info_service_category = $this->db->query("select billing_info_service_category.* from billing_info_service_category");

        if ($selected_billing_info_service_category->num_rows() > 0) {
            foreach (($selected_billing_info_service_category->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_unit_pricing($id)
    {
        $unit_pricing_query = $this->db->query("select * from unit_pricing");

        if ($unit_pricing_query->num_rows() > 0) {
            foreach (($unit_pricing_query->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_currency()
    {
        $currency = $this->db->query("select * from currency");

        if ($currency->num_rows() > 0) {
            foreach (($currency->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
	public function getClient($group_id=NULL, $tipe = NULL, $keyword = NULL, $service_category = NULL)
    {
        $this->db->select('DISTINCT (client.company_name), client.*, client_contact_info.name, client_contact_info_phone.phone, client_contact_info_email.email');
        $this->db->from('client');
        if ($tipe != NULL)
        {
            
            if ($tipe != 'All')
            {
                $this->db->like($tipe, $keyword);
            } else {
                $this->db->group_start();
                    $this->db->or_like('client_code', $keyword);
                    $this->db->or_like('registration_no', $keyword);
                    $this->db->or_like('incorporation_date', $keyword);
                    $this->db->or_like('company_name', $keyword);
                    $this->db->or_like('postal_code', $keyword);
                    $this->db->or_like('street_name', $keyword);
                    $this->db->or_like('building_name', $keyword);
                    $this->db->or_like('unit_no1', $keyword);
                    $this->db->or_like('unit_no2', $keyword);
                    $this->db->or_like('activity1', $keyword);
                    $this->db->or_like('activity2', $keyword);
                    $this->db->or_like('former_name', $keyword);
                $this->db->group_end();
            }
        }
        $this->db->join('client_contact_info', 'client_contact_info.company_code = client.company_code', 'left');
        $this->db->join('client_contact_info_email', 'client_contact_info_email.client_contact_info_id = client_contact_info.id AND client_contact_info_email.primary_email = 1', 'left');
        $this->db->join('client_contact_info_phone', 'client_contact_info_phone.client_contact_info_id = client_contact_info.id AND client_contact_info_phone.primary_phone = 1', 'left');
       // $this->db->join('billing', 'billing.company_code = client.company_code', 'right');
        //$this->db->where('client.user_id', $this->session->userdata('user_id'));
        if($group_id == 4)
        {
            $this->db->join('user_client', 'user_client.client_id = client.id AND user_client.user_id = '.$this->session->userdata('user_id'), 'right');
        }

        if($service_category != "0")
        {
            $this->db->join('client_billing_info', 'client_billing_info.company_code = client.company_code', 'right');
            $this->db->join('our_service_info', 'our_service_info.id = client_billing_info.service AND our_service_info.service_type = "'.$service_category.'"', 'right');
        }
        //echo json_encode($this->session->userdata('user_id'));
        //$this->db->where('client.firm_id', $this->session->userdata('firm_id'));
        $this->db->join('user_firm', 'user_firm.firm_id = client.firm_id', 'left');
        $this->db->where('user_firm.user_id = '.$this->session->userdata('user_id'));
        $this->db->where('client.deleted', 0);
        $this->db->order_by('client.id', 'desc');
        $q = $this->db->get();


        // $this->db->select('firm.*')
        //         ->from('firm')
        //         ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
        //         ->where('user_firm.user_id = '.$this->session->userdata('user_id'));

        $client_info = $q->result_array();

        if ($q->num_rows() > 0) 
        {
            for($i = 0; $i < count($client_info); $i++)
            {
                $query = $this->db->query("select year_end, agm, due_date_175, 175_extended_to, due_date_201, 201_extended_to from filing where (agm = '') AND company_code='".$client_info[$i]["company_code"]."'");
                // or agm = 'dispensed'
                $filing_info = $query->result_array();

                if ($query->num_rows() > 0) {
                    $client_info[$i] = array_merge($client_info[$i], $filing_info[0]);
                }

                $query_pending_documents = $this->db->query("select COUNT(*) as num_document from pending_documents where received_on = '' AND client_id ='".$client_info[$i]["id"]."'");

                $pending_documents_info = $query_pending_documents->result_array();

                if ($query_pending_documents->num_rows() > 0) {
                    $client_info[$i] = array_merge($client_info[$i], $pending_documents_info[0]);
                }

                $query_unpaid = $this->db->query("select sum(outstanding) as outstanding from billing where company_code = '".$client_info[$i]["company_code"]."' AND status != 1");

                $unpaid_info = $query_unpaid->result_array();

                if ($query_unpaid->num_rows() > 0) {
                    $client_info[$i] = array_merge($client_info[$i], $unpaid_info[0]);
                }
            }

            foreach (($client_info) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getClientbyID($id)
    {
        // if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
        //     $this->db->where('firm_id', $this->session->userdata('firm_id'));
        // }
            $this->db->where('id', $id);
            /*change on 5/3/2018 by justin*/
            /*$this->db->where('row_status', 0);*/
        // $this->db->order_by('id', 'desc');
        $q = $this->db->get("client");
        if ($q->num_rows() > 0) {
            // foreach (($q->result()) as $row) {
                // $data[] = $row;
            // }
            $this->session->set_userdata(array(
                'company_type'  => $q->result()[0]->company_type,
            ));
            $this->session->set_userdata(array(
                'acquried_by'  => $q->result()[0]->acquried_by,
            ));
            $this->session->set_userdata(array(
                'status'  => $q->result()[0]->status,
            ));
            return $q->result()[0];
        }
    }
	public function getClientbyUcode($unique_code)
    {
        if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
            //$this->db->where('unique_code', $unique_code);
			//$this->db->where('row_status', 0);
        // $this->db->order_by('id', 'desc');
        $q = $this->db->get("client");
        if ($q->num_rows() > 0) {
            // foreach (($q->result()) as $row) {
                // $data[] = $row;
            // }
            return $q->result()[0];
        }
    }
	
	public function getMainInformation()
    {
        // if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
            // $this->db->where('created_by', $this->session->userdata('user_id'));
        // }
            // $this->db->where('id', $id);
			// $this->db->where('row_status', 0);
        // $this->db->order_by('id', 'desc');
        $q = $this->db->get("company");
        if ($q->num_rows() > 0) {
            // foreach (($q->result()) as $row) {
                // $data[] = $row;
            // }
            return $q->result()[0];
        }
    }

}
