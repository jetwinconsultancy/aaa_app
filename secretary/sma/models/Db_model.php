<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Db_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('encryption','session'));
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

    public function check_acknowledgement($group_id)
    {
        if($group_id != 4)
        {
            $q = $this->db->get_where("acknowledgement", array('user_id' => $this->session->userdata('user_id')));
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
                return $data;
            }
            else
            {
                $user_login_queue = $this->db->query("SELECT * FROM user_logins WHERE DATE(user_logins.time) >= '2020-04-01' AND user_id = '".$this->session->userdata('user_id')."'");

                if($user_login_queue->num_rows() > 1)
                {
                    return "warning";
                }
                else if($user_login_queue->num_rows() == 1)
                {
                    return "normal";
                } 
            }
        }
        else
        {
            return false;
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
            $q = $this->db->query('select client_officers.*, client_officers_position.position as position_name, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type left join client_officers_position on client_officers.position = client_officers_position.id where company_code = "'.$id.'" ORDER BY client_officers.date_of_cessation DESC, position_name DESC');
            if ($q->num_rows() > 0) {
                foreach (($q->result_array()) as $row) {
                    if($row["field_type"] == "individual")
                    {
                        $row["identification_no"] = $this->encryption->decrypt($row["identification_no"]);
                        $row["name"] = $this->encryption->decrypt($row["name"]);
                    }
                    else
                    {
                        $row["register_no"] = $this->encryption->decrypt($row["register_no"]);
                        $row["company_name"] = $this->encryption->decrypt($row["company_name"]);
                    }
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
                $row->identification_no = $this->encryption->decrypt($row->identification_no);
                $row->name = $this->encryption->decrypt($row->name);
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
        }
        return false;
    }
    
    public function getEditClientNomineeDirector($nominee_director_id)
    {
        if ($nominee_director_id)
        {
            $q = $this->db->query('select client_nominee_director.*, 
                client_nominee_director.company_code as client_nominee_director_company_code, 
                client_nominee_director.id as client_nominee_director_id,
                nd_officer.identification_no as nd_officer_identification_no, 
                nd_officer.name as nd_officer_name, 
                nomi_officer.*, 
                nomi_officer.unit_no1 as nomi_officer_unit_no1, 
                nomi_officer.unit_no2 as nomi_officer_unit_no2,
                officer_company.*, 
                officer_company.company_name as officer_company_company_name, 
                client.*, client.company_name as client_company_name, 
                client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2,
                nationality.nationality as nomi_officer_nationality_name 
                from client_nominee_director 
                left join officer as nd_officer on client_nominee_director.nd_officer_id = nd_officer.id and client_nominee_director.nd_officer_field_type = nd_officer.field_type 
                left join officer as nomi_officer on client_nominee_director.nomi_officer_id = nomi_officer.id and client_nominee_director.nomi_officer_field_type = nomi_officer.field_type
                left join officer_company on client_nominee_director.nomi_officer_id = officer_company.id and client_nominee_director.nomi_officer_field_type = officer_company.field_type 
                left join client on client.id = client_nominee_director.nomi_officer_id AND client_nominee_director.nomi_officer_field_type = "client"
                left join nationality on nationality.id = nomi_officer.nationality where  client_nominee_director.id ="'.$nominee_director_id.'"');

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
        return false;
    }

    public function getEditClientController($controller_id)
    {
        if ($controller_id)
        {
            $q = $this->db->query('select client_controller.*, client_controller.company_code as client_controller_company_code, client_controller.id as client_controller_id, client_controller.officer_id as client_controller_officer_id, client_controller.field_type as client_controller_field_type, officer.*, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.company_name as officer_company_company_name, client.*, client.id as client_id, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, nationality.nationality as officer_nationality_name from client_controller left join officer on client_controller.officer_id = officer.id and client_controller.field_type = officer.field_type left join officer_company on client_controller.officer_id = officer_company.id and client_controller.field_type = officer_company.field_type left join client on client.id = client_controller.officer_id AND client_controller.field_type = "client" left join nationality on nationality.id = officer.nationality where client_controller.id ="'.$controller_id.'"');

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
                //print_r($data);
                return $data;

            }
        }
        return false;
    }

    public function getClientNomineeDirector($company_code)
    {
        if ($company_code)
        {
            $q = $this->db->query('select 
                client_nominee_director.*, 
                client_nominee_director.company_code as client_nominee_director_company_code, 
                client_nominee_director.id as client_nominee_director_id, 
                nd_officer.name as nd_officer_name, 
                nomi_officer.*, 
                nomi_officer.unit_no1 as nomi_officer_unit_no1, 
                nomi_officer.unit_no2 as nomi_officer_unit_no2,
                officer_company.*, 
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
                left join company_type on company_type.id = client.company_type where client_nominee_director.company_code ="'.$company_code.'" and client_nominee_director.deleted = 0');

            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $row->nd_officer_name = $this->encryption->decrypt($row->nd_officer_name);

                    if($row->nomi_officer_field_type == "individual")
                    {
                        $row->identification_no = $this->encryption->decrypt($row->identification_no);
                        $row->name = $this->encryption->decrypt($row->name);
                    }
                    elseif($row->nomi_officer_field_type == "company")
                    {
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
        return false;
    }

    public function getClientController($company_code)
    {
        if ($company_code)
        {
            // $q = $this->db->query('select client_controller.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.company_name as client_company_name, client.registration_no from client_controller left join officer on client_controller.officer_id = officer.id and client_controller.field_type = officer.field_type left join officer_company on client_controller.officer_id = officer_company.id and client_controller.field_type = officer_company.field_type left join client on client.id = client_controller.officer_id AND client_controller.field_type = "client" where client_controller.company_code ="'.$company_code.'"');
            $q = $this->db->query('select client_controller.*, client_controller.company_code as client_controller_company_code, client_controller.id as client_controller_id, client_controller.field_type as client_controller_field_type, officer.*, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.company_name as officer_company_company_name, client.*, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, company_type.company_type as client_company_type, nationality.nationality as officer_nationality_name, `client_officers`.`date_of_appointment`, MAX(client_officers.id) as client_officers_id from client_controller left join officer on client_controller.officer_id = officer.id and client_controller.field_type = officer.field_type left join officer_company on client_controller.officer_id = officer_company.id and client_controller.field_type = officer_company.field_type left join client on client.id = client_controller.officer_id AND client_controller.field_type = "client" left join nationality on nationality.id = officer.nationality left join company_type on company_type.id = client.company_type left join client_officers on client_officers.officer_id = client_controller.officer_id where client_controller.company_code ="'.$company_code.'" and client_controller.deleted = 0');

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
                        $row->officer_company_company_name = $this->encryption->decrypt($row->officer_company_company_name);
                    }
                    elseif($row->client_controller_field_type == "client")
                    {
                        $row->registration_no = $this->encryption->decrypt($row->registration_no);
                        $row->client_company_name = $this->encryption->decrypt($row->client_company_name);
                    }
                    $data[] = $row;
                }
                //print_r($data);
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

        $q = $this->db->query('select client_officers.*, client_officers_position.position as position_name, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type left join client_officers_position on client_officers.position = client_officers_position.id where '.$where.' company_code ="'.$company_code.'" ORDER BY client_officers.date_of_cessation DESC, position_name DESC');

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
        return false;
    }

    public function get_all_unpaid_billings_for_dashboard()
    {
        $this->db->select('billing.company_name, SUM(billing.amount) as total_unpaid_amount, currency.currency as currency_name, client.id as client_id');
        $this->db->from('billing');
        $this->db->join('client', 'client.company_code = billing.company_code AND  client.deleted = 0', 'left');
        $this->db->join('billing_receipt_record', 'billing_receipt_record.billing_id = billing.id', 'left');
        $this->db->join('receipt', 'receipt.id = billing_receipt_record.receipt_id', 'left');
        $this->db->join('currency', 'currency.id = billing.currency_id', 'left');
        $this->db->group_by(array("billing.company_name", "currency.currency"));
        $this->db->order_by('total_unpaid_amount', 'DESC');
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

    public function get_all_unpaid_billings($type=NULL,$keyword=NULL,$start=NULL,$end=NULL)
    {
        $this->db->select('billing.*, currency.currency as currency_name');
        $this->db->from('billing');
        $this->db->join('client', 'client.company_code = billing.company_code AND client.deleted = 0', 'left');
        //$this->db->join('billing_receipt_record', 'billing_receipt_record.billing_id = billing.id', 'left');
        //$this->db->join('receipt', 'receipt.id = billing_receipt_record.receipt_id', 'left');
        $this->db->join('currency', 'currency.id = billing.currency_id', 'left');
        if ($type != NULL)
        {
            if ($type != 'all')
            {
                if($type == "company_name")
                {
                    $this->db->like('billing.company_name', $keyword);
                }
                else
                {
                    $this->db->like($type, $keyword);
                }
            } 
            else 
            {
                $this->db->group_start();
                    //$this->db->or_like('client_code', $keyword);
                    $this->db->or_like('registration_no', $keyword);
                    $this->db->or_like('billing.invoice_no', $keyword);
                    $this->db->or_like('billing.company_name', $keyword);
                    // $this->db->or_like('former_name', $keyword);
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
        // if($this->session->userdata("firm_id") == 18 || $this->session->userdata("firm_id") == 26)
        // {
        //     $this->db->where('(billing.firm_id = 18 or billing.firm_id = 26)');
        // }
        // else
        // {
        //     $this->db->where('billing.firm_id', $this->session->userdata("firm_id"));
        // }
        $this->db->where('billing.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('outstanding != 0 AND billing.status != 1');
        //$this->db->where('billing.status !=', 1);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            //echo json_encode($data);
            return $data;
        }
        return FALSE;
    }

    public function get_all_paid_billings($type=NULL,$keyword=NULL,$start=NULL,$end=NULL)
    {
        $date = "01/01/".date("Y");

        $this->db->select('billing_receipt_record.receipt_id, billing_receipt_record.billing_id, billing_receipt_record.received, group_concat(receipt.receipt_no SEPARATOR "<br />") as receipt_no, billing.*, payment_mode.payment_mode, currency.currency as currency_name');
        $this->db->from('billing');
        $this->db->join('billing_receipt_record', 'billing_receipt_record.billing_id = billing.id', 'left');
        $this->db->join('receipt', 'receipt.id = billing_receipt_record.receipt_id', 'left');
        $this->db->join('client', 'client.company_code = billing.company_code AND client.deleted = 0', 'left');
        $this->db->join('payment_mode', 'payment_mode.id = receipt.payment_mode', 'left');
        $this->db->join('currency', 'currency.id = billing.currency_id', 'left');
        if ($type != NULL)
        {
            if ($type != 'all')
            {
                if($type == "company_name")
                {
                    $this->db->like('billing.company_name', $keyword);
                }
                else
                {
                    $this->db->like($type, $keyword);
                }
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('client_code', $keyword);
                    $this->db->or_like('registration_no', $keyword);
                    $this->db->or_like('billing.invoice_no', $keyword);
                    $this->db->or_like('billing.company_name', $keyword);
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
        // if($this->session->userdata("firm_id") == 18 || $this->session->userdata("firm_id") == 26)
        // {
        //     $this->db->where('(billing.firm_id = 18 or billing.firm_id = 26)');
        // }
        // else
        // {
        //     $this->db->where('billing.firm_id', $this->session->userdata("firm_id"));
        // }
        $this->db->where('billing.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('outstanding <=', 0);
        //$this->db->where("STR_TO_DATE(invoice_date,'%d/%m/%Y') >= STR_TO_DATE('".$date."','%d/%m/%Y')");
        //$this->db->where("'".$current_year."' > SUBSTRING(billing.invoice_date, -4)");
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_billings($type=NULL,$keyword=NULL,$start=NULL,$end=NULL)
    {
        $this->db->select('billing.*, currency.currency as currency_name');
        $this->db->from('billing');
        $this->db->join('client', 'client.company_code = billing.company_code AND client.deleted = 0', 'left');
        $this->db->join('currency', 'currency.id = billing.currency_id', 'left');
        if ($type != NULL)
        {
            if ($type != 'all')
            {
                if($type == "company_name")
                {
                    $this->db->like('billing.company_name', $keyword);
                }
                else
                {
                    $this->db->like($type, $keyword);
                }
            } 
            else 
            {
                $this->db->group_start();
                    //$this->db->or_like('client_code', $keyword);
                    $this->db->or_like('registration_no', $keyword);
                    $this->db->or_like('billing.invoice_no', $keyword);
                    $this->db->or_like('billing.company_name', $keyword);
                    // $this->db->or_like('former_name', $keyword);
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
        //$this->db->where('billing.status !=', 1);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            //echo json_encode($data);
            return $data;
        }
        return FALSE;
    }

    public function get_billing($id)
    {
        $this->db->select('billing.*, currency.currency as currency_name, client.company_name as company_name');
        $this->db->from('billing');
        $this->db->join('client', 'client.company_code = billing.company_code', 'left');
        $this->db->join('currency', 'currency.id = billing.currency_id', 'left');
        $this->db->where('billing.id =', $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            //echo json_encode($data);
            return $data;
        }
        return FALSE;
    }

    public function get_all_latest_credit_note($type=NULL,$keyword=NULL,$start=NULL,$end=NULL)
    {
        $this->db->select('billing_credit_note_gst.billing_id, billing_credit_note_gst.id as credit_note_id, billing_credit_note_gst.credit_note_no, billing_credit_note_gst.company_name as billing_credit_note_gst_company_name, credit_note_date, cn_out_of_balance, billing_credit_note_gst.total_amount_discounted, billing.*, currency.currency as currency_name'); //, billing_credit_note_gst_record.received
        $this->db->from('billing_credit_note_gst');
        //$this->db->join('billing_credit_note_gst_record', 'billing_credit_note_gst.id = billing_credit_note_gst_record.credit_note_id', 'left');
        $this->db->join('billing', 'billing_credit_note_gst.billing_id = billing.id', 'left'); // and billing.outstanding != billing.amount
        $this->db->join('currency', 'currency.id = billing_credit_note_gst.currency_id', 'left');
        $this->db->join('client', 'client.company_code = billing_credit_note_gst.company_code AND client.deleted = 0', 'left');
        if ($type != NULL)
        {
            if ($type != 'all')
            {
                if($type == "company_name")
                {
                    $this->db->like('billing_credit_note_gst.company_name', $keyword);
                }
                else
                {
                    $this->db->like($type, $keyword);
                }
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('client_code', $keyword);
                    $this->db->or_like('registration_no', $keyword);
                    $this->db->or_like('billing_credit_note_gst.credit_note_no', $keyword);
                    $this->db->or_like('billing.company_name', $keyword);
                    $this->db->or_like('former_name', $keyword);
                $this->db->group_end();
            }
        }
        if ($start != NULL)
        {
            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
            if ($end != NULL)
            {

                $this->db->where('STR_TO_DATE(credit_note_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
            }
            else
            {
                $this->db->where('credit_note_date = "'. $start.'"');
            }
        }
        $this->db->order_by('billing_credit_note_gst.id', 'asc');
        // if($this->session->userdata("firm_id") == 18 || $this->session->userdata("firm_id") == 26)
        // {
        //     $this->db->where('(billing_credit_note_record.firm_id = 18 or billing_credit_note_record.firm_id = 26)');
        // }
        // else
        // {
        //     $this->db->where('billing_credit_note_record.firm_id', $this->session->userdata("firm_id"));
        // }
        $this->db->where('billing_credit_note_gst.firm_id', $this->session->userdata("firm_id"));
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

    public function get_all_unassign_amount($type=NULL,$keyword=NULL,$start=NULL,$end=NULL)
    {
        $this->db->select('sum(billing_credit_note_gst.cn_out_of_balance) as total_cn_out_of_balance, GROUP_CONCAT(billing_credit_note_gst.credit_note_no) as group_credit_note_no, billing_credit_note_gst.billing_id, billing_credit_note_gst.company_code as billing_credit_note_gst_company_code, billing_credit_note_gst.id as credit_note_id, billing_credit_note_gst.company_name as billing_credit_note_gst_company_name, credit_note_date, cn_out_of_balance, billing_credit_note_gst.total_amount_discounted, currency.currency as currency_name');//, billing.*
        $this->db->from('billing_credit_note_gst');
        //$this->db->join('billing', 'billing_credit_note_gst.billing_id = billing.id', 'left');
        $this->db->join('currency', 'currency.id = billing_credit_note_gst.currency_id', 'left');
        $this->db->join('client', 'client.company_code = billing_credit_note_gst.company_code AND client.deleted = 0', 'left');
        if ($type != NULL)
        {
            if ($type != 'all')
            {
                if($type == "company_name")
                {
                    $this->db->like('billing_credit_note_gst.company_name', $keyword);
                }
                else
                {
                    $this->db->like($type, $keyword);
                }
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('client_code', $keyword);
                    $this->db->or_like('registration_no', $keyword);
                    $this->db->or_like('billing_credit_note_gst.company_name', $keyword);
                    $this->db->or_like('former_name', $keyword);
                $this->db->group_end();
            }
        }
        if ($start != NULL)
        {
            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
            if ($end != NULL)
            {
                $this->db->where('STR_TO_DATE(credit_note_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
            }
            else
            {
                $this->db->where('credit_note_date = "'. $start.'"');
            }
        }
        $this->db->where('billing_credit_note_gst.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('billing_credit_note_gst.cn_out_of_balance > 0');
        $this->db->group_by('billing_credit_note_gst.company_code, billing_credit_note_gst.currency_id');
        $this->db->order_by('billing_credit_note_gst.id', 'DESC');

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
        $this->db->select('billing_credit_note_record.credit_note_id, billing_credit_note_record.billing_id, billing_credit_note_record.received, credit_note.id, credit_note.credit_note_no, credit_note_date, credit_note.total_amount_discounted, billing.*');
        $this->db->from('billing_credit_note_record');
        $this->db->join('billing', 'billing_credit_note_record.billing_id = billing.id and billing.outstanding != billing.amount', 'left');
        $this->db->join('credit_note', 'credit_note.id = billing_credit_note_record.credit_note_id', 'left');
        $this->db->join('client', 'client.company_code = billing.company_code AND client.deleted = 0', 'left');
        if ($type != NULL)
        {
            if ($type != 'all')
            {
                if($type == "company_name")
                {
                    $this->db->like('billing.company_name', $keyword);
                }
                else
                {
                    $this->db->like($type, $keyword);
                }
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('client_code', $keyword);
                    $this->db->or_like('registration_no', $keyword);
                    $this->db->or_like('billing.company_name', $keyword);
                    $this->db->or_like('former_name', $keyword);
                $this->db->group_end();
            }
        }
        if ($start != NULL)
        {
            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
            if ($end != NULL)
            {

                $this->db->where('STR_TO_DATE(credit_note_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
            }
            else
            {
                $this->db->where('credit_note_date = "'. $start.'"');
            }
        }
        $this->db->order_by('billing_credit_note_record.id', 'asc');
        // if($this->session->userdata("firm_id") == 18 || $this->session->userdata("firm_id") == 26)
        // {
        //     $this->db->where('(billing_credit_note_record.firm_id = 18 or billing_credit_note_record.firm_id = 26)');
        // }
        // else
        // {
        //     $this->db->where('billing_credit_note_record.firm_id', $this->session->userdata("firm_id"));
        // }
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
        $date = "01/01/".date("Y");

        $this->db->select('billing_receipt_record.receipt_id, billing_receipt_record.billing_id, billing_receipt_record.received, receipt.id, receipt.receipt_no, receipt_date, receipt.reference_no, receipt.payment_mode, receipt.total_amount_received, receipt.out_of_balance, billing.*, payment_mode.payment_mode');
        $this->db->from('billing');
        $this->db->join('billing_receipt_record', 'billing_receipt_record.billing_id = billing.id', 'left');
        $this->db->join('receipt', 'receipt.id = billing_receipt_record.receipt_id', 'right');
        $this->db->join('client', 'client.company_code = billing.company_code AND client.deleted = 0', 'left');
        $this->db->join('payment_mode', 'payment_mode on payment_mode.id = receipt.payment_mode', 'left');
        if ($type != NULL)
        {
            if ($type != 'all')
            {
                if($type == "company_name")
                {
                    $this->db->like('billing.company_name', $keyword);
                }
                else
                {
                    $this->db->like($type, $keyword);
                }
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('client_code', $keyword);
                    $this->db->or_like('registration_no', $keyword);
                    $this->db->or_like('billing.invoice_no', $keyword);
                    $this->db->or_like('billing.company_name', $keyword);
                    $this->db->or_like('receipt.receipt_no', $keyword);
                    $this->db->or_like('former_name', $keyword);
                $this->db->group_end();
            }
        }
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
        $this->db->where('billing.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('billing.outstanding != billing.amount');
        $this->db->where('billing.status = 0');
        //$this->db->where("STR_TO_DATE(receipt_date,'%d/%m/%Y') >= STR_TO_DATE('".$date."','%d/%m/%Y')");
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
        $this->db->join('client', 'client.company_code = recurring_billing.company_code AND client.deleted = 0', 'left');
        // if ($type != NULL)
        // {
        //     if ($type != 'all')
        //     {
        //         $this->db->like($type, $keyword);
        //     } 
        //     else 
        //     {
        //         $this->db->group_start();
        //             $this->db->or_like('client_code', $keyword);
        //             $this->db->or_like('registration_no', $keyword);
        //             $this->db->or_like('company_name', $keyword);
        //             $this->db->or_like('former_name', $keyword);
        //         $this->db->group_end();
        //     }
        // }
        if ($start != NULL)
        {
            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
            if ($end != NULL)
            {

                $this->db->where('STR_TO_DATE(recu_invoice_issue_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
            }
            else
            {
                $this->db->where('recu_invoice_issue_date = "'. $start.'"');
            }
        }
        $this->db->order_by('id', 'asc');
        // if($this->session->userdata("firm_id") == 18 || $this->session->userdata("firm_id") == 26)
        // {
        //     $this->db->where('(recurring_billing.firm_id = 18 or recurring_billing.firm_id = 26)');
        // }
        // else
        // {
        //     $this->db->where('recurring_billing.firm_id', $this->session->userdata("firm_id"));
        // }
        $this->db->where('recurring_billing.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('outstanding !=', 0);
        $this->db->where('recurring_billing.status !=', 1);
        
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                if($row->registration_no != null)
                {
                    $row->registration_no = $this->encryption->decrypt($row->registration_no);
                    $row->company_name = $this->encryption->decrypt($row->company_name);
                }
                if($keyword != null)
                {
                    if(stripos($row->registration_no, $keyword) !== FALSE)
                    {
                        $data[] = $row;
                    }
                    else if(stripos($row->company_name, $keyword) !== FALSE)
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
        $q = $this->db->query("select recurring_billing.*,client.company_name, client.company_code, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, client.foreign_add_1, client.foreign_add_2, client.foreign_add_3, client.use_foreign_add_as_billing_add, currency.currency as currency_name from recurring_billing left join client on client.company_code = recurring_billing.company_code left join currency on recurring_billing.currency_id = currency.id where recurring_billing.id='".$id."'");


        if ($q->num_rows() > 0) {
            $this->session->set_userdata('billing_company_code', $q->result()[0]->company_code);
            $this->session->set_userdata('billing_currency', $q->result()[0]->currency_id);
            $this->session->set_userdata('billing_period', $q->result()[0]->billing_period);
            foreach (($q->result()) as $row) {
                $row->company_name = $this->encryption->decrypt($row->company_name);
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_pv_receipt($id)
    {
        $q = $this->db->query("select payment_receipt.*, currency.currency as currency_name from payment_receipt left join currency on payment_receipt.currency_id = currency.id where payment_receipt.id='".$id."'");

        if ($q->num_rows() > 0) {
            $this->session->set_userdata('billing_currency', $q->result()[0]->currency_id);
            $this->session->set_userdata('payment_voucher_bank_acc_id', $q->result()[0]->bank_acc_id);
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_claim($id)
    {
        $q = $this->db->query("select claim.*, currency.currency as currency_name from claim left join currency on claim.currency_id = currency.id where claim.id='".$id."'");

        if ($q->num_rows() > 0) {
            $this->session->set_userdata('claim_user_id', $q->result()[0]->user_id);
            $this->session->set_userdata('billing_currency', $q->result()[0]->currency_id);
            $this->session->set_userdata('payment_voucher_bank_acc_id', $q->result()[0]->bank_acc_id);
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_payment_voucher($id)
    {
        $q = $this->db->query("select payment_voucher.*, currency.currency as currency_name, previous_cn_currency, previous_total_cn_out_of_balance from payment_voucher left join currency on payment_voucher.currency_id = currency.id left join billing_credit_note_gst_with_pv on billing_credit_note_gst_with_pv.pv_id = payment_voucher.id where payment_voucher.id='".$id."'");

        if ($q->num_rows() > 0) {
            $this->session->set_userdata('payment_voucher_supplier_code', $q->result()[0]->supplier_code);
            $this->session->set_userdata('billing_currency', $q->result()[0]->currency_id);
            $this->session->set_userdata('payment_voucher_bank_acc_id', $q->result()[0]->bank_acc_id);
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_edit_paid_bill($transaction_master_id)
    {
        $q = $this->db->query("select billing_receipt_record.receipt_id, billing_receipt_record.billing_id, billing_receipt_record.received, group_concat(receipt.receipt_no SEPARATOR '<br />') as receipt_no, billing.*, payment_mode.payment_mode, currency.currency as currency_name from transaction_master_with_billing left join billing on billing.id = transaction_master_with_billing.billing_id left join billing_receipt_record on billing_receipt_record.billing_id = billing.id left join receipt on receipt.id = billing_receipt_record.receipt_id left join payment_mode on payment_mode.id = receipt.payment_mode left join currency on billing.currency_id = currency.id where transaction_master_with_billing.transaction_master_id = '".$transaction_master_id."' AND outstanding <= 0 GROUP BY billing.invoice_no");

        // $q = $this->db->query("select billing.*,client.company_name, client.company_code, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, client.foreign_add_1, client.foreign_add_2, client.foreign_add_3, client.use_foreign_add_as_billing_add, currency.currency as currency_name from billing left join client on client.company_code = billing.company_code left join currency on billing.currency_id = currency.id where billing.id='".$id."'");

        

        if ($q->num_rows() > 0) {
            // $this->session->set_userdata('billing_company_code', $q->result()[0]->company_code);
            // $this->session->set_userdata('billing_currency', $q->result()[0]->currency_id);
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_edit_unpaid_bill($transaction_master_id)
    {
        $q = $this->db->query("select billing.*, currency.currency as currency_name from transaction_master_with_billing left join billing on billing.id = transaction_master_with_billing.billing_id left join billing_receipt_record on billing_receipt_record.billing_id = billing.id left join receipt on receipt.id = billing_receipt_record.receipt_id left join currency on billing.currency_id = currency.id where transaction_master_with_billing.transaction_master_id = '".$transaction_master_id."' AND outstanding != 0 AND billing.status != 1");

        // $q = $this->db->query("select billing.*,client.company_name, client.company_code, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, client.foreign_add_1, client.foreign_add_2, client.foreign_add_3, client.use_foreign_add_as_billing_add, currency.currency as currency_name from billing left join client on client.company_code = billing.company_code left join currency on billing.currency_id = currency.id where billing.id='".$id."'");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_the_billing($transaction_master_id)
    {
        $q = $this->db->query("select billing.*, billing_service.*, currency.currency as currency_name from transaction_master_with_billing left join billing on billing.id = transaction_master_with_billing.billing_id left join billing_receipt_record on billing_receipt_record.billing_id = billing.id left join receipt on receipt.id = billing_receipt_record.receipt_id left join currency on billing.currency_id = currency.id left join billing_service on billing_service.billing_id = billing.id where transaction_master_with_billing.transaction_master_id = '".$transaction_master_id."' AND billing.status != 1");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_edit_recurring_bill_service($id)
    {
        $q = $this->db->query("select recurring_billing.*, recurring_billing_service.id as recurring_service_id, recurring_billing_service.billing_id, recurring_billing_service.service, recurring_billing_service.invoice_description, recurring_billing_service.amount as recurring_service_amount, recurring_billing_service.gst_rate, recurring_billing_service.unit_pricing, recurring_billing_service.period_start_date, recurring_billing_service.period_end_date, recurring_billing_service.gst_category_id, recurring_billing_service.gst_new_way, our_service_info.service_name from recurring_billing left join recurring_billing_service on recurring_billing_service.billing_id = recurring_billing.id left join client_billing_info on client_billing_info.id = recurring_billing_service.service left join our_service_info on our_service_info.id = client_billing_info.service where recurring_billing.id='".$id."' ORDER BY recurring_billing_service.id");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_edit_claim_service($id)
    {
        $q = $this->db->query("select claim.*, claim_service.id as claim_service_id, claim_service.claim_id, claim_service.company_code, claim_service.type_id, claim_service.claim_date, claim_service.client_name, claim_service.claim_description, claim_service.amount as claim_service_amount, claim_service.attachment, claim_service.billing_service_id from claim left join claim_service on claim_service.claim_id = claim.id where claim.id='".$id."' ORDER BY claim_service.id");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_edit_payment_voucher_service($id)
    {
        $q = $this->db->query("select payment_voucher.*, payment_voucher_service.id as payment_voucher_service_id, payment_voucher_service.payment_voucher_id, payment_voucher_service.type_id, payment_voucher_service.payment_voucher_date, payment_voucher_service.payment_voucher_description, payment_voucher_service.amount as payment_voucher_service_amount, payment_voucher_service.attachment from payment_voucher left join payment_voucher_service on payment_voucher_service.payment_voucher_id = payment_voucher.id where payment_voucher.id='".$id."' ORDER BY payment_voucher_service.id");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_edit_pv_receipt_service($id)
    {
        $q = $this->db->query("select payment_receipt.*, payment_receipt_service.id as payment_receipt_service_id, payment_receipt_service.payment_receipt_id, payment_receipt_service.type_id, payment_receipt_service.receipt_date, payment_receipt_service.payment_receipt_description, payment_receipt_service.amount as payment_receipt_service_amount, payment_receipt_service.attachment from payment_receipt left join payment_receipt_service on payment_receipt_service.payment_receipt_id = payment_receipt.id where payment_receipt.id='".$id."' ORDER BY payment_receipt_service.id");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function get_edit_bill($id)
    {

        $q = $this->db->query("select billing.*, currency.currency as currency_name, client.incorporation_date from billing left join currency on billing.currency_id = currency.id left join client on client.company_code = billing.company_code AND client.deleted = 0 where billing.id='".$id."'");

        // $q = $this->db->query("select billing.*,client.company_name, client.company_code, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, client.foreign_add_1, client.foreign_add_2, client.foreign_add_3, client.use_foreign_add_as_billing_add, currency.currency as currency_name from billing left join client on client.company_code = billing.company_code left join currency on billing.currency_id = currency.id where billing.id='".$id."'");

        

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

    public function get_billing_credit_note_gst_record($id)
    {
        $q = $this->db->query("select billing_credit_note_gst_record.billing_service_id, SUM(cn_amount) as total_cn_amount from billing left join billing_service on billing_service.billing_id = billing.id left join billing_credit_note_gst_record on billing_credit_note_gst_record.billing_service_id = billing_service.id where billing.id='".$id."' GROUP BY billing_credit_note_gst_record.billing_service_id ORDER BY billing_service.id");

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

        $q = $this->db->query("select billing.*, billing_service.id as billing_service_id, billing_service.billing_id, billing_service.service, billing_service.progress_billing_yes_no, billing_service.poc_percentage, billing_service.number_of_percent_poc, billing_service.radio_quantity_reading, billing_service.reading_at_begin, billing_service.reading_at_the_end, billing_service.number_of_rate, billing_service.unit_for_rate, billing_service.quantity_value, billing_service.invoice_description, billing_service.amount as billing_service_amount, billing_service.gst_rate, billing_service.gst_new_way, billing_service.gst_category_id, billing_service.unit_pricing, billing_service.period_start_date, billing_service.period_end_date, billing_service.claim_service_id, our_service_qb_info.qb_item_id, our_service_info.service_name, payroll_assignment_invoices.assignment_id, currency.currency as currency_name, client_qb_id.qb_customer_id, gst_category.category as gst_category_name
                from billing 
                left join billing_service on billing_service.billing_id = billing.id 
                left join client on client.company_code = billing.company_code 
                left join client_billing_info on client_billing_info.id = billing_service.service 
                left join our_service_info on our_service_info.id = client_billing_info.service
                LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."'
                left join payroll_assignment_invoices on payroll_assignment_invoices.billing_service_id = billing_service.id 
                left join currency on currency.id = billing.currency_id 
                left join gst_category on gst_category.id = billing_service.gst_category_id
                left join client_qb_id on client_qb_id.company_code = billing.company_code and client_qb_id.currency_name = currency.currency AND client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."'
                where billing.id='".$id."' ORDER BY billing_service.id");

        // echo "select billing.*, billing_service.id as billing_service_id, billing_service.billing_id, billing_service.service, billing_service.progress_billing_yes_no, billing_service.poc_percentage, billing_service.number_of_percent_poc, billing_service.radio_quantity_reading, billing_service.reading_at_begin, billing_service.reading_at_the_end, billing_service.number_of_rate, billing_service.unit_for_rate, billing_service.quantity_value, billing_service.invoice_description, billing_service.amount as billing_service_amount, billing_service.gst_rate, billing_service.gst_new_way, billing_service.gst_category_id, billing_service.unit_pricing, billing_service.period_start_date, billing_service.period_end_date, billing_service.claim_service_id, our_service_qb_info.qb_item_id, our_service_info.service_name, payroll_assignment_invoices.assignment_id, currency.currency as currency_name, client_qb_id.qb_customer_id, gst_category.category as gst_category_name
        // from billing 
        // left join billing_service on billing_service.billing_id = billing.id 
        // left join client on client.company_code = billing.company_code 
        // left join client_billing_info on client_billing_info.id = billing_service.service 
        // left join our_service_info on our_service_info.id = client_billing_info.service
        // LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."'
        // left join payroll_assignment_invoices on payroll_assignment_invoices.billing_service_id = billing_service.id 
        // left join currency on currency.id = billing.currency_id 
        // left join gst_category on gst_category.id = billing_service.gst_category_id
        // left join client_qb_id on client_qb_id.company_code = billing.company_code and client_qb_id.currency_name = currency.currency AND client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."'
        // where billing.id='".$id."' ORDER BY billing_service.id"; exit;

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_edit_transaction_bill_service($id, $transaction_task_id = NULL)
    {
        if($transaction_task_id == "1" || $transaction_task_id == "4" || $transaction_task_id == "33" || $transaction_task_id == "34")
        {
            if($transaction_task_id != "1")
            {
                $qb_customer_id = ", client_qb_id.qb_customer_id";
                $left_join_client = " left join client on client.company_code = billing.company_code LEFT JOIN client_qb_id ON client_qb_id.company_code = billing.company_code AND client_qb_id.currency_name = currency.currency AND client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";

                if($_POST["transaction_task_id"] == "4" || $_POST["transaction_task_id"] == "33" || $_POST["transaction_task_id"] == "34")
                {
                    $left_join_client = $left_join_client." LEFT JOIN transaction_client_billing_info ON transaction_client_billing_info.id = billing_service.service 
                    LEFT JOIN our_service_info ON our_service_info.id = transaction_client_billing_info.service 
                    LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";
                }
                else
                {
                    $left_join_client = $left_join_client." LEFT JOIN our_service_info ON our_service_info.id = billing_service.service 
                    LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";
                }
            }
            else
            {
                $qb_customer_id = ", transaction_client_qb_id.qb_customer_id, client_qb_id.qb_customer_id as client_qb_customer_id";
                $left_join_client = "left join transaction_client on transaction_client.company_code = billing.company_code
                LEFT JOIN transaction_client_billing_info ON transaction_client_billing_info.id = billing_service.service 
                LEFT JOIN our_service_info ON our_service_info.id = transaction_client_billing_info.service 
                LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."'
                LEFT JOIN transaction_client_qb_id ON transaction_client_qb_id.company_code = billing.company_code AND transaction_client_qb_id.currency_name = currency.currency AND transaction_client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."'
                LEFT JOIN client_qb_id ON client_qb_id.company_code = billing.company_code AND client_qb_id.currency_name = currency.currency AND client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."' ";
            }

            $q = $this->db->query("select billing.*, billing_service.id as billing_service_id, billing_service.billing_id, billing_service.service, billing_service.progress_billing_yes_no, billing_service.poc_percentage, billing_service.number_of_percent_poc, billing_service.radio_quantity_reading, billing_service.reading_at_begin, billing_service.reading_at_the_end, billing_service.number_of_rate, billing_service.unit_for_rate, billing_service.quantity_value, billing_service.invoice_description, billing_service.amount as billing_service_amount, billing_service.gst_rate, billing_service.gst_new_way, billing_service.gst_category_id, billing_service.unit_pricing, billing_service.period_start_date, billing_service.period_end_date, billing_service.claim_service_id, our_service_info.service_name, payroll_assignment_invoices.assignment_id, our_service_qb_info.qb_item_id, gst_category.category as gst_category_name, currency.currency as currency_name".$qb_customer_id." 
                    from billing 
                    left join billing_service on billing_service.billing_id = billing.id 
                    left join currency on currency.id = billing.currency_id 
                    ".$left_join_client." 
                    left join payroll_assignment_invoices on payroll_assignment_invoices.billing_service_id = billing_service.id 
                    left join gst_category on gst_category.id = billing_service.gst_category_id
                    where billing.id='".$id."' and transaction_client_billing_info.deleted = 0 ORDER BY billing_service.id");

            // echo "select billing.*, billing_service.id as billing_service_id, billing_service.billing_id, billing_service.service, billing_service.progress_billing_yes_no, billing_service.poc_percentage, billing_service.number_of_percent_poc, billing_service.radio_quantity_reading, billing_service.reading_at_begin, billing_service.reading_at_the_end, billing_service.number_of_rate, billing_service.unit_for_rate, billing_service.quantity_value, billing_service.invoice_description, billing_service.amount as billing_service_amount, billing_service.gst_rate, billing_service.gst_new_way, billing_service.gst_category_id, billing_service.unit_pricing, billing_service.period_start_date, billing_service.period_end_date, billing_service.claim_service_id, our_service_info.service_name, payroll_assignment_invoices.assignment_id, our_service_qb_info.qb_item_id, gst_category.category as gst_category_name, currency.currency as currency_name".$qb_customer_id." 
            // from billing 
            // left join billing_service on billing_service.billing_id = billing.id 
            // left join currency on currency.id = billing.currency_id 
            // ".$left_join_client." 
            // left join payroll_assignment_invoices on payroll_assignment_invoices.billing_service_id = billing_service.id 
            // left join gst_category on gst_category.id = billing_service.gst_category_id
            // where billing.id='".$id."' and transaction_client_billing_info.deleted = 0 ORDER BY billing_service.id";
        }
        else
        {
            $q = $this->db->query("select billing.*, billing_service.id as billing_service_id, billing_service.billing_id, billing_service.service, billing_service.progress_billing_yes_no, billing_service.poc_percentage, billing_service.number_of_percent_poc, billing_service.radio_quantity_reading, billing_service.reading_at_begin, billing_service.reading_at_the_end, billing_service.number_of_rate, billing_service.unit_for_rate, billing_service.quantity_value, billing_service.invoice_description, billing_service.amount as billing_service_amount, billing_service.gst_rate, billing_service.gst_new_way, billing_service.gst_category_id, billing_service.unit_pricing, billing_service.period_start_date, billing_service.period_end_date, billing_service.claim_service_id, our_service_info.service_name, payroll_assignment_invoices.assignment_id, our_service_qb_info.qb_item_id, currency.currency as currency_name, client_qb_id.qb_customer_id, gst_category.category as gst_category_name 
                    from billing 
                    left join billing_service on billing_service.billing_id = billing.id 
                    left join client on client.company_code = billing.company_code 
                    left join our_service_info on our_service_info.id = billing_service.service 
                    left join our_service_qb_info on our_service_qb_info.our_service_info_id = our_service_info.id and our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."'
                    left join payroll_assignment_invoices on payroll_assignment_invoices.billing_service_id = billing_service.id 
                    left join currency on currency.id = billing.currency_id 
                    left join gst_category on gst_category.id = billing_service.gst_category_id
                    LEFT JOIN client_qb_id ON client_qb_id.company_code = billing.company_code AND client_qb_id.currency_name = currency.currency AND client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."' 
                    where billing.id='".$id."' and our_service_info.approved = 1 ORDER BY billing_service.id"); // and our_service_info.deleted = 0

            // echo "select billing.*, billing_service.id as billing_service_id, billing_service.billing_id, billing_service.service, billing_service.progress_billing_yes_no, billing_service.poc_percentage, billing_service.number_of_percent_poc, billing_service.radio_quantity_reading, billing_service.reading_at_begin, billing_service.reading_at_the_end, billing_service.number_of_rate, billing_service.unit_for_rate, billing_service.quantity_value, billing_service.invoice_description, billing_service.amount as billing_service_amount, billing_service.gst_rate, billing_service.gst_new_way, billing_service.gst_category_id, billing_service.unit_pricing, billing_service.period_start_date, billing_service.period_end_date, billing_service.claim_service_id, our_service_info.service_name, payroll_assignment_invoices.assignment_id, our_service_qb_info.qb_item_id, currency.currency as currency_name, client_qb_id.qb_customer_id, gst_category.category as gst_category_name 
            // from billing 
            // left join billing_service on billing_service.billing_id = billing.id 
            // left join client on client.company_code = billing.company_code 
            // left join our_service_info on our_service_info.id = billing_service.service 
            // left join our_service_qb_info on our_service_qb_info.our_service_info_id = our_service_info.id and our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."'
            // left join payroll_assignment_invoices on payroll_assignment_invoices.billing_service_id = billing_service.id 
            // left join currency on currency.id = billing.currency_id 
            // left join gst_category on gst_category.id = billing_service.gst_category_id
            // LEFT JOIN client_qb_id ON client_qb_id.company_code = billing.company_code AND client_qb_id.currency_name = currency.currency AND client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."' 
            // where billing.id='".$id."' and our_service_info.approved = 1 ORDER BY billing_service.id";
            // if ($q->num_rows() > 0) {
            //     foreach (($q->result()) as $row) {
            //         $data[] = $row;
            //     }
            //     return $data;
            // }
        }

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                if($transaction_task_id == "1")
                {
                    if($row->qb_customer_id == null || $row->qb_customer_id == 0)
                    {
                        $row->qb_customer_id = $row->client_qb_customer_id;
                    }
                }
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

        // $q = $this->db->query("select client_billing_info.*, our_service_info.service_type, our_service_info.service_name, billing_info_service_category.category_description from client_billing_info left join our_service_info on our_service_info.id = client_billing_info.service left join billing_info_service_category on billing_info_service_category.id = our_service_info.service_type where client_billing_info.company_code = '".$get_billing_info[0]['company_code']."' and client_billing_info.currency = '".$get_billing_info[0]['currency_id']."' and client_billing_info.deleted = 0");

        // if ($q->num_rows() > 0) {
        //     foreach (($q->result()) as $row) {
        //         $data[] = $row;
        //     }
        //     return $data;
        // }
        // return FALSE;

        $check_gst_status_query = $this->db->query("SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = '".$this->session->userdata("firm_id")."'");

        if ($check_gst_status_query->num_rows() > 0) 
        {
            $check_gst_status_array = $check_gst_status_query->result_array();

            $p = $this->db->query("select client_billing_info.*, our_service_info.service_type, our_service_info.service_name, billing_info_service_category.category_description, gst_category_info.gst_category_id, gst_category_info.start_date as gst_start_date, gst_category_info.end_date as gst_end_date, gst_category_info.rate 
                FROM client_billing_info 
                LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service 
                LEFT JOIN billing_info_service_category on billing_info_service_category.id = our_service_info.service_type 
                LEFT JOIN our_service_gst ON our_service_gst.our_service_info_id = client_billing_info.service and our_service_gst.jurisdiction_id = '".$check_gst_status_array[0]["jurisdiction_id"]."' 
                LEFT JOIN gst_category_info ON gst_category_info.deleted = 0 AND gst_category_info.id = our_service_gst.category_id AND gst_category_info.start_date <= CURRENT_DATE() AND (gst_category_info.end_date >= CURRENT_DATE() OR gst_category_info.end_date IS NULL) 
                where client_billing_info.company_code = '".$get_billing_info[0]['company_code']."' and client_billing_info.currency = '".$get_billing_info[0]['currency_id']."' and client_billing_info.deleted = 0");

            if ($p->num_rows() > 0) 
            {
                foreach (($p->result_array()) as $row) 
                {
                    if($row["rate"] == NULL)
                    {
                        $row["rate"] = 0;
                    }
                    $row["gst_new_way"] = 1;
                    $data[] = $row;
                }
            }
            else
            {
                $data = false;
            }
        }
        else
        {
            $p = $this->db->query("select client_billing_info.*, our_service_info.service_type, our_service_info.service_name, billing_info_service_category.category_description 
                FROM client_billing_info 
                LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service 
                LEFT JOIN billing_info_service_category ON billing_info_service_category.id = our_service_info.service_type 
                WHERE client_billing_info.company_code = '".$get_billing_info[0]['company_code']."' and client_billing_info.currency = '".$get_billing_info[0]['currency_id']."' and client_billing_info.deleted = 0");

            if ($p->num_rows() > 0) 
            {
                foreach (($p->result_array()) as $row) 
                {
                    $row["rate"] = 0;
                    $row["gst_new_way"] = 0;
                    $data[] = $row;
                }
            }
            else
            {
                $data = false;
            }
        }

        return $data;
    }

    public function get_client_billing_info($id)
    {
        // $q = $this->db->query("select billing.*,client_billing_info.*, billing_info_service.service as service_name from billing left join client_billing_info on billing.company_code = client_billing_info.company_code left join billing_info_service on client_billing_info.service = billing_info_service.id where billing.id='".$id."'");

        // $q = $this->db->query("select billing_info_service.*, billing_info_service.service as service_name, billing_info_service_category.category_description from billing_info_service left join billing_info_service_category on billing_info_service_category.category_code = billing_info_service.category_code order by billing_info_service.id");

        $get_billing_info = $this->db->query("select * from billing where id = '".$id."'");

        $get_billing_info = $get_billing_info->result_array();

        // $q = $this->db->query("select client_billing_info.*, our_service_info.service_type, our_service_info.service_name, billing_info_service_category.category_description from client_billing_info left join our_service_info on our_service_info.id = client_billing_info.service left join billing_info_service_category on billing_info_service_category.id = our_service_info.service_type where client_billing_info.company_code = '".$get_billing_info[0]['company_code']."' and client_billing_info.currency = '".$get_billing_info[0]['currency_id']."' and client_billing_info.deleted = 0");

        $check_gst_status_query = $this->db->query("SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = '".$this->session->userdata("firm_id")."'");

        if ($check_gst_status_query->num_rows() > 0) 
        {
            $check_gst_status_array = $check_gst_status_query->result_array();

            $p = $this->db->query("select client_billing_info.*, our_service_info.service_type, our_service_info.service_name, our_service_info.calculate_by_quantity_rate, billing_info_service_category.category_description, gst_category_info.gst_category_id, gst_category_info.start_date as gst_start_date, gst_category_info.end_date as gst_end_date, gst_category_info.rate 
                FROM client_billing_info 
                LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service 
                LEFT JOIN billing_info_service_category on billing_info_service_category.id = our_service_info.service_type 
                LEFT JOIN our_service_gst ON our_service_gst.our_service_info_id = client_billing_info.service and our_service_gst.jurisdiction_id = '".$check_gst_status_array[0]["jurisdiction_id"]."' 
                LEFT JOIN gst_category_info ON gst_category_info.deleted = 0 AND gst_category_info.id = our_service_gst.category_id AND gst_category_info.start_date <= CURRENT_DATE() AND (gst_category_info.end_date >= CURRENT_DATE() OR gst_category_info.end_date IS NULL) 
                where client_billing_info.company_code = '".$get_billing_info[0]['company_code']."' and client_billing_info.currency = '".$get_billing_info[0]['currency_id']."' and client_billing_info.deleted = 0");

            if ($p->num_rows() > 0) 
            {
                foreach (($p->result_array()) as $row) 
                {
                    if($row["rate"] == NULL)
                    {
                        $row["rate"] = 0;
                    }
                    $row["gst_new_way"] = 1;
                    $data[] = $row;
                }
            }
            else
            {
                $data = false;
            }
        }
        else
        {
            $p = $this->db->query("select client_billing_info.*, our_service_info.service_type, our_service_info.service_name, our_service_info.calculate_by_quantity_rate, billing_info_service_category.category_description 
                FROM client_billing_info 
                LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service 
                LEFT JOIN billing_info_service_category ON billing_info_service_category.id = our_service_info.service_type 
                WHERE client_billing_info.company_code = '".$get_billing_info[0]['company_code']."' and client_billing_info.currency = '".$get_billing_info[0]['currency_id']."' and client_billing_info.deleted = 0");

            if ($p->num_rows() > 0) 
            {
                foreach (($p->result_array()) as $row) 
                {
                    $row["rate"] = 0;
                    $row["gst_new_way"] = 0;
                    $data[] = $row;
                }
            }
            else
            {
                $data = false;
            }
        }

        return $data;

        // if ($q->num_rows() > 0) {
        //     foreach (($q->result()) as $row) {
        //         $data[] = $row;
        //     }
        //     return $data;
        // }
        // return FALSE;
    }

    public function get_transaction_our_services_info($id)
    {
        $get_billing_info = $this->db->query("select * from billing where id = '".$id."'");

        $get_billing_info = $get_billing_info->result_array();
        
        $check_gst_status_query = $this->db->query("SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = '".$this->session->userdata("firm_id")."'");

        if ($check_gst_status_query->num_rows() > 0) 
        {
            $check_gst_status_array = $check_gst_status_query->result_array();
            //got gst
            $p = $this->db->query("select our_service_info.*, billing_info_service_category.category_description, gst_category_info.gst_category_id, gst_category_info.start_date as gst_start_date, gst_category_info.end_date as gst_end_date, gst_category_info.rate 
                FROM our_service_info 
                LEFT JOIN billing_info_service_category on billing_info_service_category.id = our_service_info.service_type 
                LEFT JOIN our_service_gst ON our_service_gst.our_service_info_id = our_service_info.id and our_service_gst.jurisdiction_id = '".$check_gst_status_array[0]["jurisdiction_id"]."' 
                LEFT JOIN gst_category_info ON gst_category_info.deleted = 0 AND gst_category_info.id = our_service_gst.category_id AND gst_category_info.start_date <= CURRENT_DATE() AND (gst_category_info.end_date >= CURRENT_DATE() OR gst_category_info.end_date IS NULL) 
                where our_service_info.user_admin_code_id = '".$this->session->userdata('user_admin_code_id')."' and our_service_info.approved = 1 and our_service_info.currency = '".$get_billing_info[0]['currency_id']."'");
            //'our_service_info.user_admin_code_id = '.$this->session->userdata('user_admin_code_id').'
            //LEFT JOIN our_service_info ON our_service_info.id = transaction_client_billing_info.service 
            // and our_service_info.deleted = 0

            if ($p->num_rows() > 0) 
            {
                foreach (($p->result_array()) as $row) 
                {
                    if($row["rate"] == NULL)
                    {
                        $row["rate"] = 0;
                    }
                    $row["gst_new_way"] = 1;
                    $data[] = $row;
                }
            }
            else
            {
                $data = false;
            }
        }
        else
        {
            //dont have gst
            $p = $this->db->query("select our_service_info.*, billing_info_service_category.category_description 
                FROM our_service_info 
                LEFT JOIN billing_info_service_category ON billing_info_service_category.id = our_service_info.service_type 
                WHERE our_service_info.user_admin_code_id = '".$this->session->userdata('user_admin_code_id')."' and our_service_info.currency = '".$get_billing_info[0]['currency_id']."' and our_service_info.approved = 1");
            //LEFT JOIN our_service_info ON our_service_info.id = transaction_client_billing_info.service 
            // and our_service_info.deleted = 0
            
            if ($p->num_rows() > 0) 
            {
                foreach (($p->result_array()) as $row) 
                {
                    $row["rate"] = 0;
                    $row["gst_new_way"] = 0;
                    $data[] = $row;
                }
            }
            else
            {
                $data = false;
            }
        }

        return $data;
    }

    public function get_transaction_client_billing_info($id, $transaction_master_id)
    {
        $get_billing_info = $this->db->query("select * from billing where id = '".$id."'");

        $get_billing_info = $get_billing_info->result_array();

        // $q = $this->db->query("select transaction_client_billing_info.*, our_service_info.service_type, our_service_info.service_name, billing_info_service_category.category_description from transaction_client_billing_info left join our_service_info on our_service_info.id = transaction_client_billing_info.service left join billing_info_service_category on billing_info_service_category.id = our_service_info.service_type where transaction_client_billing_info.company_code = '".$get_billing_info[0]['company_code']."' and transaction_client_billing_info.currency = '".$get_billing_info[0]['currency_id']."'");

        // if ($q->num_rows() > 0) {
        //     foreach (($q->result()) as $row) {
        //         $data[] = $row;
        //     }
        //     return $data;
        // }
        // return FALSE;
        

        $check_gst_status_query = $this->db->query("SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = '".$this->session->userdata("firm_id")."'");

        if ($check_gst_status_query->num_rows() > 0) 
        {
            $check_gst_status_array = $check_gst_status_query->result_array();

            $p = $this->db->query("select transaction_client_billing_info.*, our_service_info.service_type, our_service_info.service_name, our_service_info.calculate_by_quantity_rate, billing_info_service_category.category_description, gst_category_info.gst_category_id, gst_category_info.start_date as gst_start_date, gst_category_info.end_date as gst_end_date, gst_category_info.rate 
                FROM transaction_client_billing_info 
                LEFT JOIN our_service_info ON our_service_info.id = transaction_client_billing_info.service 
                LEFT JOIN billing_info_service_category on billing_info_service_category.id = our_service_info.service_type 
                LEFT JOIN our_service_gst ON our_service_gst.our_service_info_id = transaction_client_billing_info.service and our_service_gst.jurisdiction_id = '".$check_gst_status_array[0]["jurisdiction_id"]."' 
                LEFT JOIN gst_category_info ON gst_category_info.deleted = 0 AND gst_category_info.id = our_service_gst.category_id AND gst_category_info.start_date <= CURRENT_DATE() AND (gst_category_info.end_date >= CURRENT_DATE() OR gst_category_info.end_date IS NULL) 
                where transaction_client_billing_info.company_code = '".$get_billing_info[0]['company_code']."' and transaction_client_billing_info.currency = '".$get_billing_info[0]['currency_id']."' and transaction_client_billing_info.transaction_id = '".$transaction_master_id."' and transaction_client_billing_info.deleted = 0");

            if ($p->num_rows() > 0) 
            {
                foreach (($p->result_array()) as $row) 
                {
                    if($row["rate"] == NULL)
                    {
                        $row["rate"] = 0;
                    }
                    $row["gst_new_way"] = 1;
                    $data[] = $row;
                }
            }
            else
            {
                $data = false;
            }
        }
        else
        {
            $p = $this->db->query("select transaction_client_billing_info.*, our_service_info.service_type, our_service_info.service_name, our_service_info.calculate_by_quantity_rate, billing_info_service_category.category_description 
                FROM transaction_client_billing_info 
                LEFT JOIN our_service_info ON our_service_info.id = transaction_client_billing_info.service 
                LEFT JOIN billing_info_service_category ON billing_info_service_category.id = our_service_info.service_type 
                WHERE transaction_client_billing_info.company_code = '".$get_billing_info[0]['company_code']."' and transaction_client_billing_info.currency = '".$get_billing_info[0]['currency_id']."' and transaction_client_billing_info.transaction_id = '".$transaction_master_id."' and transaction_client_billing_info.deleted = 0");

            if ($p->num_rows() > 0) 
            {
                foreach (($p->result_array()) as $row) 
                {
                    $row["rate"] = 0;
                    $row["gst_new_way"] = 0;
                    $data[] = $row;
                }
            }
            else
            {
                $data = false;
            }
        }

        return $data;
    }

    public function get_history_client_billing_info($id)
    {
        // $q = $this->db->query("select billing.*,client_billing_info.*, billing_info_service.service as service_name from billing left join client_billing_info on billing.company_code = client_billing_info.company_code left join billing_info_service on client_billing_info.service = billing_info_service.id where billing.id='".$id."'");

        // $q = $this->db->query("select billing_info_service.*, billing_info_service.service as service_name, billing_info_service_category.category_description from billing_info_service left join billing_info_service_category on billing_info_service_category.category_code = billing_info_service.category_code order by billing_info_service.id");


        // $get_billing_info = $this->db->query("select * from billing where id = '".$id."'");

        // $get_billing_info = $get_billing_info->result_array();

        // $q = $this->db->query("select client_billing_info.*, our_service_info.service_type, our_service_info.service_name, billing_info_service_category.category_description from client_billing_info left join our_service_info on our_service_info.id = client_billing_info.service left join billing_info_service_category on billing_info_service_category.id = our_service_info.service_type where client_billing_info.company_code = '".$get_billing_info[0]['company_code']."' and client_billing_info.currency = '".$get_billing_info[0]['currency_id']."' and client_billing_info.deleted = 0");

        // if ($q->num_rows() > 0) {
        //     foreach (($q->result()) as $row) {
        //         $data[] = $row;
        //     }
        //     return $data;
        // }
        // return FALSE;



        $get_billing_info = $this->db->query("select * from billing where id = '".$id."'");

        $get_billing_info = $get_billing_info->result_array();

        $check_gst_status_query = $this->db->query("SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = '".$this->session->userdata("firm_id")."'");

        if ($check_gst_status_query->num_rows() > 0) 
        {
            $check_gst_status_array = $check_gst_status_query->result_array();

            $p = $this->db->query("select client_billing_info.*, our_service_info.service_type, our_service_info.service_name, our_service_info.calculate_by_quantity_rate, billing_info_service_category.category_description, gst_category_info.gst_category_id, gst_category_info.start_date as gst_start_date, gst_category_info.end_date as gst_end_date, gst_category_info.rate 
                FROM client_billing_info 
                LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service 
                LEFT JOIN billing_info_service_category on billing_info_service_category.id = our_service_info.service_type 
                LEFT JOIN our_service_gst ON our_service_gst.our_service_info_id = client_billing_info.service and our_service_gst.jurisdiction_id = '".$check_gst_status_array[0]["jurisdiction_id"]."' 
                LEFT JOIN gst_category_info ON gst_category_info.deleted = 0 AND gst_category_info.id = our_service_gst.category_id AND gst_category_info.start_date <= CURRENT_DATE() AND (gst_category_info.end_date >= CURRENT_DATE() OR gst_category_info.end_date IS NULL) 
                where client_billing_info.company_code = '".$get_billing_info[0]['company_code']."' and client_billing_info.currency = '".$get_billing_info[0]['currency_id']."' and client_billing_info.deleted = 0");

            if ($p->num_rows() > 0) 
            {
                foreach (($p->result_array()) as $row) 
                {
                    if($row["rate"] == NULL)
                    {
                        $row["rate"] = 0;
                    }
                    $row["gst_new_way"] = 1;
                    $data[] = $row;
                }
            }
            else
            {
                $data = false;
            }
        }
        else
        {
            $p = $this->db->query("select client_billing_info.*, our_service_info.service_type, our_service_info.service_name, billing_info_service_category.category_description 
                FROM client_billing_info 
                LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service 
                LEFT JOIN billing_info_service_category ON billing_info_service_category.id = our_service_info.service_type 
                WHERE client_billing_info.company_code = '".$get_billing_info[0]['company_code']."' and client_billing_info.currency = '".$get_billing_info[0]['currency_id']."' and client_billing_info.deleted = 0");

            if ($p->num_rows() > 0) 
            {
                foreach (($p->result_array()) as $row) 
                {
                    $row["rate"] = 0;
                    $row["gst_new_way"] = 0;
                    $data[] = $row;
                }
            }
            else
            {
                $data = false;
            }
        }

        return $data;
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
        $currency = $this->db->query("select * from currency order by id");

        if ($currency->num_rows() > 0) {
            foreach (($currency->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_firm_currency()
    {
        $firm_currency = $this->db->query("select firm_currency from firm where id = '".$this->session->userdata("firm_id")."'");

        if ($firm_currency->num_rows() > 0) {
            foreach (($firm_currency->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_claim($type=NULL,$keyword=NULL,$start=NULL,$end=NULL)
    {
        $user_query = $this->db->query("select * from users where id = '".$this->session->userdata("user_id")."'");
        $user_query = $user_query->result_array();
        if($_SESSION['group_id'] == 5 && $user_query[0]["username"] != "penny") 
        {
            $this->db->select('claim.*, currency.currency as currency_name');
            $this->db->from('claim');
            $this->db->join('users', 'users.id = claim.user_id', 'left');
            $this->db->join('currency', 'currency.id = claim.currency_id', 'left');

            if ($type != NULL)
            {
                if ($type != 'all')
                {
                    $this->db->like($type, $keyword);
                } 
                else 
                {
                    $this->db->group_start();
                        //$this->db->or_like('vendor_code', $keyword);
                        //$this->db->or_like('registration_no', $keyword);
                        $this->db->or_like('claim.user_name', $keyword);
                        //$this->db->or_like('former_name', $keyword);
                    $this->db->group_end();
                }
            }
            if ($start != NULL)
            {
                if ($end != NULL)
                {

                    $this->db->where('STR_TO_DATE(claim_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
                }
                else
                {
                    $this->db->where('claim_date = "'. $start.'"');
                }
            }
            $this->db->order_by('id', 'asc');
            $this->db->where('users.manager_in_charge', $this->session->userdata("user_id"));
            $this->db->where('claim.firm_id', $this->session->userdata("firm_id"));
            $this->db->where('claim.status !=', 1);
            $q = $this->db->get();
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
            }

            $this->db->select('claim.*, currency.currency as currency_name');
            $this->db->from('claim');
            $this->db->join('users', 'users.id = claim.user_id', 'left');
            $this->db->join('currency', 'currency.id = claim.currency_id', 'left');

            if ($type != NULL)
            {
                if ($type != 'all')
                {
                    $this->db->like($type, $keyword);
                } 
                else 
                {
                    $this->db->group_start();
                        //$this->db->or_like('vendor_code', $keyword);
                        //$this->db->or_like('registration_no', $keyword);
                        $this->db->or_like('claim.user_name', $keyword);
                        //$this->db->or_like('former_name', $keyword);
                    $this->db->group_end();
                }
            }
            if ($start != NULL)
            {
                if ($end != NULL)
                {

                    $this->db->where('STR_TO_DATE(claim_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
                }
                else
                {
                    $this->db->where('claim_date = "'. $start.'"');
                }
            }
            $this->db->order_by('id', 'asc');

            //$this->db->where('claim.user_id', $this->session->userdata("user_id"));
            if($user_query[0]["id"] == "62")
            {
                $this->db->where('(claim.user_id = '.$this->session->userdata("user_id").' OR claim.user_id = 67 OR claim.user_id = 68)');
            }
            else
            {
                $this->db->where('(claim.user_id = '.$this->session->userdata("user_id").' OR claim.user_id = 67)');
            }
            $this->db->where('claim.firm_id', $this->session->userdata("firm_id"));
            $this->db->where('claim.status !=', 1);
            $p = $this->db->get();

            if ($p->num_rows() > 0) {
                foreach (($p->result()) as $row) {
                    $data[] = $row;
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
        elseif($_SESSION['group_id'] == 2 || $_SESSION['group_id'] == 6 || ($_SESSION['group_id'] == 5 && $user_query[0]["username"] == "penny"))
        {
            $this->db->select('claim.*, currency.currency as currency_name');
            $this->db->from('claim');
            $this->db->join('users', 'users.id = claim.user_id', 'left');
            $this->db->join('currency', 'currency.id = claim.currency_id', 'left');

            if ($type != NULL)
            {
                if ($type != 'all')
                {
                    $this->db->like($type, $keyword);
                } 
                else 
                {
                    $this->db->group_start();
                        //$this->db->or_like('vendor_code', $keyword);
                        //$this->db->or_like('registration_no', $keyword);
                        $this->db->or_like('claim.user_name', $keyword);
                        //$this->db->or_like('former_name', $keyword);
                    $this->db->group_end();
                }
            }
            if ($start != NULL)
            {
                if ($end != NULL)
                {

                    $this->db->where('STR_TO_DATE(claim_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
                }
                else
                {
                    $this->db->where('claim_date = "'. $start.'"');
                }
            }
            $this->db->order_by('id', 'asc');
            $this->db->where('claim.firm_id', $this->session->userdata("firm_id"));
            $this->db->where('claim.status !=', 1);
            $p = $this->db->get();

            if ($p->num_rows() > 0) {
                foreach (($p->result()) as $row) {
                    $data[] = $row;
                }
                return $data;
            }
            return FALSE;
        }
        elseif($_SESSION['group_id'] == 3)
        {
            $this->db->select('claim.*, currency.currency as currency_name');
            $this->db->from('claim');
            $this->db->join('users', 'users.id = claim.user_id', 'left');
            $this->db->join('currency', 'currency.id = claim.currency_id', 'left');

            if ($type != NULL)
            {
                if ($type != 'all')
                {
                    $this->db->like($type, $keyword);
                } 
                else 
                {
                    $this->db->group_start();
                        //$this->db->or_like('vendor_code', $keyword);
                        //$this->db->or_like('registration_no', $keyword);
                        $this->db->or_like('claim.user_name', $keyword);
                        //$this->db->or_like('former_name', $keyword);
                    $this->db->group_end();
                }
            }
            if ($start != NULL)
            {
                if ($end != NULL)
                {

                    $this->db->where('STR_TO_DATE(claim_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
                }
                else
                {
                    $this->db->where('claim_date = "'. $start.'"');
                }
            }
            $this->db->order_by('id', 'asc');
            $this->db->where('(claim.user_id = '.$this->session->userdata("user_id").' OR claim.user_id = 67)');
            // /$this->db->or_where('claim.user_id = 67');
            $this->db->where('claim.firm_id', $this->session->userdata("firm_id"));
            $this->db->where('claim.status !=', 1);
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

    public function get_all_pv_receipt($type=NULL,$keyword=NULL,$start=NULL,$end=NULL)
    {
        $this->db->select('payment_receipt.*, currency.currency as currency_name');
        $this->db->from('payment_receipt');
        $this->db->join('currency', 'currency.id = payment_receipt.currency_id', 'left');

        if ($type != NULL)
        {
            if ($type != 'all')
            {
                $this->db->like($type, $keyword);
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('payment_receipt.client_name', $keyword);
                $this->db->group_end();
            }
        }
        if ($start != NULL)
        {
            if ($end != NULL)
            {

                $this->db->where('STR_TO_DATE(receipt_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
            }
            else
            {
                $this->db->where('receipt_date = "'. $start.'"');
            }
        }
        $this->db->order_by('id', 'asc');
        $this->db->where('payment_receipt.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('payment_receipt.status !=', 1);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_payment_voucher($type=NULL,$keyword=NULL,$start=NULL,$end=NULL)
    {
        $this->db->select('payment_voucher.*, currency.currency as currency_name');
        $this->db->from('payment_voucher');
        $this->db->join('vendor_info', 'vendor_info.supplier_code = payment_voucher.supplier_code', 'left');
        $this->db->join('currency', 'currency.id = payment_voucher.currency_id', 'left');

        if ($type != NULL)
        {
            if ($type != 'all')
            {
                $this->db->like($type, $keyword);
            } 
            else 
            {
                $this->db->group_start();
                    $this->db->or_like('vendor_code', $keyword);
                    $this->db->or_like('payment_voucher.vendor_name', $keyword);
                    $this->db->or_like('former_name', $keyword);
                $this->db->group_end();
            }
        }
        if ($start != NULL)
        {
            if ($end != NULL)
            {

                $this->db->where('STR_TO_DATE(payment_voucher_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
            }
            else
            {
                $this->db->where('payment_voucher_date = "'. $start.'"');
            }
        }
        $this->db->order_by('id', 'asc');
        $this->db->where('payment_voucher.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('payment_voucher.status !=', 1);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getVendor($group_id=NULL, $type=NULL,$keyword=NULL)
    {
        $this->db->select('vendor_info.*, vendor_contact_info.name, vendor_contact_info_phone.phone, vendor_contact_info_email.email');
        $this->db->from('vendor_info');
        if ($type != NULL)
        {
            if ($type != 'all')
            {
                $this->db->like($type, $keyword);
            } else {
                $this->db->group_start();
                    $this->db->or_like('vendor_code', $keyword);
                    $this->db->or_like('company_name', $keyword);
                    $this->db->or_like('postal_code', $keyword);
                    $this->db->or_like('street_name', $keyword);
                    $this->db->or_like('building_name', $keyword);
                    $this->db->or_like('unit_no1', $keyword);
                    $this->db->or_like('unit_no2', $keyword);
                    $this->db->or_like('former_name', $keyword);
                $this->db->group_end();
            }
        }
        $this->db->join('user_firm as a', 'a.user_id = "'.$this->session->userdata("user_id").'"', 'inner');
        $this->db->join('vendor_contact_info', 'vendor_contact_info.supplier_code = vendor_info.supplier_code', 'left');
        $this->db->join('vendor_contact_info_email', 'vendor_contact_info_email.vendor_contact_info_id = vendor_contact_info.id AND vendor_contact_info_email.primary_email = 1', 'left');
        $this->db->join('vendor_contact_info_phone', 'vendor_contact_info_phone.vendor_contact_info_id = vendor_contact_info.id AND vendor_contact_info_phone.primary_phone = 1', 'left');
        //$this->db->where('vendor.firm_id', $this->session->userdata('firm_id'));
        $this->db->where('a.firm_id = vendor_info.firm_id');
        $this->db->where('vendor_info.deleted', 0);
        $this->db->order_by('vendor_info.id', 'desc');
        $this->db->group_by('vendor_info.id');
        $q = $this->db->get();

        $client_info = $q->result_array();

        if ($q->num_rows() > 0) 
        {
            foreach (($client_info) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
    
	public function getClient($group_id=NULL, $tipe = NULL, $keyword = NULL, $service_category = NULL, $dt_start= NULL, $dt_length= NULL)
    {
        // if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
            // $this->db->where('created_by', $this->session->userdata('user_id'));
        // }
        /*$this->db->like('row_status', 0);*/
        //$this->db->select('*');
        //$this->db->from('client');
        $this->db->select('DISTINCT (client.company_name), client.*, client_contact_info.name, client_contact_info_phone.phone, client_contact_info_email.email, GROUP_CONCAT(client_qb_id.currency_name) as concat_currency_name');
        $this->db->from('client');
		// if ($tipe != NULL)
		// {
		// 	if ($tipe != 'All')
		// 	{
		// 		$this->db->like($tipe, $keyword);
		// 	} 
   //          else 
   //          {
			// 	$this->db->group_start();
   //  				$this->db->or_like('client_code', $keyword);
   //  				$this->db->or_like('registration_no', $keyword);
   //  				$this->db->or_like('incorporation_date', $keyword);
   //  				$this->db->or_like('company_name', $keyword);
   //  				$this->db->or_like('postal_code', $keyword);
   //  				$this->db->or_like('street_name', $keyword);
   //  				$this->db->or_like('building_name', $keyword);
   //  				$this->db->or_like('unit_no1', $keyword);
   //  				$this->db->or_like('unit_no2', $keyword);
   //  				$this->db->or_like('activity1', $keyword);
   //  				$this->db->or_like('activity2', $keyword);
   //  				$this->db->or_like('former_name', $keyword);
			// 	$this->db->group_end();
			// }
		//}
        $this->db->join('client_contact_info', 'client_contact_info.company_code = client.company_code', 'left');
        $this->db->join('client_contact_info_email', 'client_contact_info_email.client_contact_info_id = client_contact_info.id AND client_contact_info_email.primary_email = 1', 'left');
        $this->db->join('client_contact_info_phone', 'client_contact_info_phone.client_contact_info_id = client_contact_info.id AND client_contact_info_phone.primary_phone = 1', 'left');
        $this->db->join('client_qb_id', "client_qb_id.company_code = client.company_code AND client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."'", 'left');
       // $this->db->join('billing', 'billing.company_code = client.company_code', 'right');
        //$this->db->where('client.user_id', $this->session->userdata('user_id'));
        if($group_id == 4)
        {
            $this->db->join('user_client', 'user_client.client_id = client.id AND user_client.user_id = '.$this->session->userdata('user_id'), 'right');
        }

        if($service_category != "0")
        {
            $this->db->join('client_billing_info', 'client_billing_info.company_code = client.company_code AND client_billing_info.deleted = 0', 'right');
            $this->db->join('our_service_info', 'our_service_info.id = client_billing_info.service AND our_service_info.service_type = "'.$service_category.'"', 'right');
        }
        //echo json_encode($this->session->userdata('user_id'));
        //$this->db->where('client.firm_id', $this->session->userdata('firm_id'));
        $this->db->join('user_firm', 'user_firm.firm_id = client.firm_id', 'left');
        $this->db->where('user_firm.user_id = '.$this->session->userdata('user_id'));
        $this->db->where('client.deleted', 0);
        $this->db->order_by('client.id', 'desc');
        $this->db->group_by('client.company_code');
        // if ($service_category == "0" && $keyword == NULL) {
        //    $this->db->limit($dt_length, $dt_start);
        // }
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
                $row["registration_no"] = $this->encryption->decrypt($row["registration_no"]);
                $row["company_name"] = $this->encryption->decrypt($row["company_name"]);
                if($row["concat_currency_name"] != "")
                {
                    $row["concat_currency_name"] = explode(',', $row["concat_currency_name"]);
                }
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
    }
	
	public function getClient2($group_id=NULL, $tipe = NULL, $keyword = NULL, $service_category = NULL, $dt_start= NULL, $dt_length= NULL)
    {
    }
	
	public function getClient1($group_id=NULL, $tipe = NULL, $keyword = NULL, $service_category = NULL, $dt_start= NULL, $dt_length= NULL)
    {
        //if ($keyword != null)
        //{
            // Live version
            /*$this->db->select('DISTINCT (client.company_name), client.*');
            $this->db->from('client');
            
            $this->db->join('user_firm', 'user_firm.firm_id = client.firm_id', 'left');
            $this->db->where('user_firm.user_id = '.$this->session->userdata('user_id'));
            $this->db->where('client.deleted', 0);
            $this->db->order_by('client.id', 'desc');
            $this->db->group_by('client.company_code');*/

            $this->db->select('DISTINCT (client.company_name), client.*,client_contact_info.name, client_contact_info_phone.phone, client_contact_info_email.email, GROUP_CONCAT(client_qb_id.currency_name) as concat_currency_name, billing.outstanding, COUNT(pending_documents.id) as num_document');
            $this->db->from('client');
            
            $this->db->join('client_contact_info', 'client_contact_info.company_code = client.company_code', 'left');
            $this->db->join('client_contact_info_email', 'client_contact_info_email.client_contact_info_id = client_contact_info.id AND client_contact_info_email.primary_email = 1', 'left');
            $this->db->join('client_contact_info_phone', 'client_contact_info_phone.client_contact_info_id = client_contact_info.id AND client_contact_info_phone.primary_phone = 1', 'left');
            $this->db->join('client_qb_id', "client_qb_id.company_code = client.company_code AND client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."'", 'left');
            $this->db->join('billing', 'billing.company_code = client.company_code', 'left');
            $this->db->join('pending_documents', 'pending_documents.client_id = client.id', 'left');

            $this->db->join('user_firm', 'user_firm.firm_id = client.firm_id', 'left');
            $this->db->where('user_firm.user_id = '.$this->session->userdata('user_id'));
            $this->db->where('client.deleted', 0);
            $this->db->order_by('client.id', 'desc');
            $this->db->group_by('client.company_code');
            $q = $this->db->get();

            
    
            $client_info = $q->result_array();
    
            if ($q->num_rows() > 0) 
            {
    
                foreach (($client_info) as $row) {
                    $row["registration_no"] = $this->encryption->decrypt($row["registration_no"]);
                    $row["company_name"] = $this->encryption->decrypt($row["company_name"]);
                    if(!empty($row["concat_currency_name"]))
                    {
                        $row["concat_currency_name"] = explode(',', $row["concat_currency_name"]);
                    }
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
        //}
    }
	
	
    public function getVendorID($id)
    {
        $this->db->where('id', $id);
        $q = $this->db->get("vendor_info");
        
        if ($q->num_rows() > 0) {
            return $q->result()[0];
        }
    }

    public function getVendorContact($supplier_code)
    {
        if ($supplier_code)
        {
            $q = $this->db->query("select vendor_contact_info.*, GROUP_CONCAT(DISTINCT CONCAT(vendor_contact_info_phone.id,',', vendor_contact_info_phone.phone, ',', vendor_contact_info_phone.primary_phone)SEPARATOR ';') AS 'vendor_contact_info_phone', GROUP_CONCAT(DISTINCT CONCAT(vendor_contact_info_email.id,',', vendor_contact_info_email.email, ',', vendor_contact_info_email.primary_email)SEPARATOR ';') AS 'vendor_contact_info_email' from vendor_contact_info LEFT JOIN vendor_contact_info_phone ON vendor_contact_info_phone.vendor_contact_info_id = vendor_contact_info.id LEFT JOIN vendor_contact_info_email ON vendor_contact_info_email.vendor_contact_info_id = vendor_contact_info.id where supplier_code ='".$supplier_code."'");

            if($q->result()[0]->vendor_contact_info_phone != null)
            {
                $q->result()[0]->vendor_contact_info_phone = explode(';', $q->result()[0]->vendor_contact_info_phone);
            }

            if($q->result()[0]->vendor_contact_info_email != null)
            {
                $q->result()[0]->vendor_contact_info_email = explode(';', $q->result()[0]->vendor_contact_info_email);
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
            $q->result()[0]->registration_no = $this->encryption->decrypt($q->result()[0]->registration_no);
            $q->result()[0]->company_name = $this->encryption->decrypt($q->result()[0]->company_name);
            //$q->result()[0]->former_name = $this->encryption->decrypt($q->result()[0]->former_name);
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

    public function get_jurisdiction_info($user_admin_code_id)
    {
        $q = $this->db->query("select gst_jurisdiction.* from gst_jurisdiction where user_admin_code_id = '".$user_admin_code_id."' AND deleted = 0 ORDER BY id");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $key => $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_category_info($user_admin_code_id)
    {
        $q = $this->db->query("select gst_category.id as gst_category_id, gst_category.category, gst_category_info.*, gst_jurisdiction.jurisdiction from gst_category LEFT JOIN gst_category_info ON gst_category_info.gst_category_id = gst_category.id AND gst_category_info.deleted = 0 LEFT JOIN gst_jurisdiction ON gst_jurisdiction.id = gst_category_info.jurisdiction_id where gst_category.user_admin_code_id = '".$user_admin_code_id."' AND gst_category_info.start_date <= CURRENT_DATE() AND ( gst_category_info.end_date >= CURRENT_DATE OR gst_category_info.end_date IS NULL)");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $key => $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_payment_voucher_type($user_admin_code_id)
    {
        $q = $this->db->query("select * from payment_voucher_type");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $key => $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }


    public function gst_category_list($user_admin_code_id)
    {
        $q = $this->db->query("select gst_category.id as gst_category_id, gst_category.category from gst_category where gst_category.user_admin_code_id = '".$user_admin_code_id."'");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $key => $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_edit_category_info($gst_category_id)
    {
        $q = $this->db->query("select gst_category.id as gst_category_id, gst_category.category, gst_category_info.*, gst_jurisdiction.jurisdiction from gst_category LEFT JOIN gst_category_info ON gst_category_info.gst_category_id = gst_category.id AND gst_category_info.deleted = 0 LEFT JOIN gst_jurisdiction ON gst_jurisdiction.id = gst_category_info.jurisdiction_id where gst_category.id = '".$gst_category_id."'");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $key => $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_edit_payment_voucher_type($payment_voucher_type_id)
    {
        $q = $this->db->query("select * from payment_voucher_type where payment_voucher_type.id = '".$payment_voucher_type_id."'");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $key => $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_client_list()
    {
        //$query = 'SELECT client.id, client.company_code, client.company_name FROM client left join user_firm on user_id = "'.$this->session->userdata("user_id").'" where user_firm.firm_id = client.firm_id AND deleted != 1';

        // $query = 'SELECT billing.company_code, billing.company_name FROM billing where billing.firm_id = "'.$this->session->userdata("firm_id").'" AND status != 1 GROUP BY billing.company_name ORDER BY billing.id';
        $query = 'SELECT billing.company_code, billing.company_name FROM billing where status != 1 GROUP BY billing.company_name ORDER BY billing.id';

        $result = $this->db->query($query);
        if ($result->num_rows() > 0) 
        {
            $result = $result->result_array();

            if(!$result) {
              throw new exception("Client Name not found.");
            }

            $res = array();
            foreach($result as $row) {
                if($row['company_name'] != null)
                {
                    $res[$row['company_code']] = $row['company_name']; //$this->encryption->decrypt($row['company_name']);
                }
            }
            return $res;
        }
        else
        { 
            $res = array();

            return $res;
        }
        return FALSE;
    }

    public function get_all_billings_invoice_no($company_code)
    {
        $this->db->select('billing.*, currency.currency as currency_name');
        $this->db->from('billing');
        $this->db->join('client', 'client.company_code = billing.company_code AND client.deleted = 0', 'left');
        $this->db->join('currency', 'currency.id = billing.currency_id', 'left');
        // $this->db->where('billing.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('billing.company_code', $company_code);
        $this->db->where('billing.status != 1');
        $this->db->order_by('billing.id', 'asc');

        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_unassign_amount($company_code)
    {
        $this->db->select('sum(billing_credit_note_gst.cn_out_of_balance) as total_cn_out_of_balance, GROUP_CONCAT(billing_credit_note_gst.credit_note_no) as group_credit_note_no, billing_credit_note_gst.billing_id, billing_credit_note_gst.company_code as billing_credit_note_gst_company_code, billing_credit_note_gst.id as credit_note_id, billing_credit_note_gst.company_name as billing_credit_note_gst_company_name, credit_note_date, cn_out_of_balance, billing_credit_note_gst.total_amount_discounted, currency.currency as currency_name');//, billing.*
        $this->db->from('billing_credit_note_gst');
        $this->db->join('currency', 'currency.id = billing_credit_note_gst.currency_id', 'left');
        $this->db->join('client', 'client.company_code = billing_credit_note_gst.company_code AND client.deleted = 0', 'left');
        $this->db->where('billing_credit_note_gst.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('billing_credit_note_gst.cn_out_of_balance > 0');
        $this->db->where('billing_credit_note_gst.company_code', $company_code);
        $this->db->group_by('billing_credit_note_gst.company_code, billing_credit_note_gst.currency_id');
        $this->db->order_by('billing_credit_note_gst.id', 'DESC');
        $q = $this->db->get();
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_progress_billing_data($company_code, $service_value)
    {
        $this->db->select('billing.*, client.company_name, transaction_client.company_name as trans_company_name, client.registration_no, client.former_name, currency.currency as currency_name, billing_info_service_category.category_description, billing_info_service_category.id as billing_info_service_category_id, billing_service.amount as billing_service_amount, billing_service.period_start_date, billing_service.period_end_date, billing_service.invoice_description, billing_service.progress_billing_yes_no, billing_service.poc_percentage, billing_service.number_of_percent_poc, billing_service.radio_quantity_reading, billing_service.reading_at_begin, billing_service.reading_at_the_end, billing_service.number_of_rate, billing_service.unit_for_rate, billing_service.quantity_value, firm.name as firm_name, firm.branch_name, our_service_info.service_name, b.service_name as trans_service_name, c.category_description as trans_category_description, c.id as trans_billing_info_service_category_id, d.service_name as our_service_service_name, e.category_description as our_service_category_description, e.id as our_service_billing_info_service_category_id, transaction_master_with_billing.id as transaction_master_with_billing_id');
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
        $this->db->where('billing.firm_id', $this->session->userdata('firm_id'));
        $this->db->where('billing.company_code', $company_code);
        $this->db->where('billing_service.service', $service_value);
        $this->db->where('billing_service.progress_billing_yes_no = "yes"');
        $this->db->where('user_firm.firm_id = billing.firm_id');
        $this->db->where('billing.status', "0");
        //$this->db->group_by('billing.id');
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
}
