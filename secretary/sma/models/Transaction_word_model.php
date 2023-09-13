<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_word_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('encryption', 'session'));
        $this->load->model(array('convert_number_to_word_model'));
    }

    public function unique_multidim_array($array, $key) {
	    $temp_array = array();
	    $i = 0;
	    $key_array = array();
	   
	    foreach($array as $val) {
	    	
	        if (!in_array($val[$key], $key_array)) {
	        	if($val[$key] != "")
		    	{
		            $key_array[$i] = $val[$key];
		        }
	            array_push($temp_array, $val);
	        }
	        $i++;
		    
	    }
	    return $temp_array;
	}

	public function data_exists($officer_id, $field_type, $array) {
        $result = -1;
        for($i=0; $i<sizeof($array); $i++) {
            if ($array[$i]['officer_id'] == $officer_id && $array[$i]['field_type'] == $field_type) {
                $result = $i;
                break;
            }
        }
        return $result;
    }

    public function getWordValue($transaction_master_id, $string2, $company_code, $firm_id, $id = null, $document_name = null, $value = null, $document_category_id = null)
    {
    	if($string2 == "Allotment - members" || $string2 == "identity_name" || $string2 == "nation_name" || $string2 == "corp_rep_name" || $string2 == "corp_rep_id" || $string2 == "acra_registra_no" || $string2 == "acra_registra_no_dot" || $string2 == "member_type" || $string2 == "Allotment - sole share cert")
		{
			if($document_name == "First Director Resolutions (One)")
			{
				$get_member_name = $this->db->query("select transaction_member_shares.*, officer.name, officer_company.company_name, client.company_name as client_company_name, transaction_certificate.certificate_no from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id AND transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id AND transaction_member_shares.field_type = officer_company.field_type left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = 'client' AND client.deleted != 1 right join transaction_certificate on transaction_certificate.officer_id = transaction_member_shares.officer_id and transaction_certificate.field_type = transaction_member_shares.field_type and transaction_certificate.transaction_id = transaction_member_shares.transaction_id and transaction_certificate.client_member_share_capital_id = transaction_member_shares.client_member_share_capital_id where transaction_member_shares.company_code='".$company_code."' AND transaction_member_shares.transaction_page_id='".$transaction_master_id."'");

				$get_member_name = $get_member_name->result_array();

				for($r = 0; $r < count($get_member_name); $r++)
        		{
        			if($get_member_name[$r]["name"] != null)
					{
						$member_name = $this->encryption->decrypt($get_member_name[$r]["name"]);
					}
					else if($get_member_name[$r]["company_name"] != null)
					{
						$member_name = $this->encryption->decrypt($get_member_name[$r]["company_name"]);
					}
					else if($get_member_name[$r]["client_company_name"] != null)
					{
						$member_name = $this->encryption->decrypt($get_member_name[$r]["client_company_name"]);
					}

        			if($r == 0)
        			{
        				if($string2 == "Allotment - sole share cert")
        				{
        					$content = $get_member_name[$r]["certificate_no"];
        				}
        				else
        				{
        					$content = $member_name;
        				}
        			}
        			else if($r == (count($get_member_name) - 1))
        			{
        				$content = $content.", and ".$member_name;
        			}
        			else
        			{
        				$content = $content.", ".$member_name;
        			}
        		}
			}
			else
			{
				$get_member_name = $this->db->query("select transaction_member_shares.*, z.company_name as tr_client_company_name, officer.name, officer_company.company_name, officer_company.register_no, client.company_name as client_company_name, client.registration_no from transaction_member_shares left join transaction_client as z on z.company_code = transaction_member_shares.company_code left join officer on transaction_member_shares.officer_id = officer.id AND transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id AND transaction_member_shares.field_type = officer_company.field_type left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = 'client' AND client.deleted != 1 where transaction_member_shares.company_code='".$company_code."' AND transaction_page_id='".$transaction_master_id."' AND transaction_member_shares.id = '".$id."'");

				$get_member_name = $get_member_name->result_array();

				if($get_member_name[0]["name"] != null)
				{
					if($string2 == "identity_name")
					{
						$content = "Identification No";
					}
					else if($string2 == "nation_name")
					{
						$content = "Nationality";
					}
					else if($string2 == "corp_rep_name")
					{
						$content = "";
					}
					else if($string2 == "corp_rep_id")
					{
						$content = "";
					}
					else if($string2 == "acra_registra_no" || $string2 == "acra_registra_no_dot")
					{
						$content = "";
					}
					else if($string2 == "member_type")
					{
						$content = "Individual";
					}
					else
					{
						$content = $this->encryption->decrypt($get_member_name[0]["name"]);
					}
				}
				else if($get_member_name[0]["company_name"] != null)
				{
					$get_corp_rep_info = $this->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" and corporate_representative.registration_no = "'.$this->encryption->decrypt($get_member_name[0]['register_no']).'" and corporate_representative.subsidiary_name = "'.$this->encryption->decrypt($get_member_name[0]['tr_client_company_name']).'"');
					$get_corp_rep_info = $get_corp_rep_info->result_array();

					if($string2 == "identity_name")
					{
						$content = "Registration No";
					}
					else if($string2 == "nation_name")
					{
						$content = "Country of Incorporation";
					}
					else if($string2 == "corp_rep_name")
					{
						$content = "Corporate Representative Name: ".$get_corp_rep_info[0]["name_of_corp_rep"];
					}
					else if($string2 == "corp_rep_id")
					{
						$content = "Corporate Representative ID: ".$get_corp_rep_info[0]["identity_number"];
					}
					else if($string2 == "acra_registra_no")
					{
						$content = "ACRA Registration No.";
					}
					else if($string2 == "acra_registra_no_dot")
					{
						$content = ":";
					}
					else if($string2 == "member_type")
					{
						$content = "Corporate";
					}
					else
					{
						$content = $this->encryption->decrypt($get_member_name[0]["company_name"]);
					}
				}
				else if($get_member_name[0]["client_company_name"] != null)
				{
					$get_corp_rep_info = $this->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" and corporate_representative.registration_no = "'.$this->encryption->decrypt($get_member_name[0]['registration_no']).'" and corporate_representative.subsidiary_name = "'.$this->encryption->decrypt($get_member_name[0]['tr_client_company_name']).'"');
					$get_corp_rep_info = $get_corp_rep_info->result_array();

					if($string2 == "identity_name")
					{
						$content = "Registration No";
					}
					else if($string2 == "nation_name")
					{
						$content = "Country of Incorporation";
					}
					else if($string2 == "corp_rep_name")
					{
						$content = "Corporate Representative Name: ".$get_corp_rep_info[0]["name_of_corp_rep"];
					}
					else if($string2 == "corp_rep_id")
					{
						$content = "Corporate Representative ID: ".$get_corp_rep_info[0]["identity_number"];
					}
					else if($string2 == "acra_registra_no")
					{
						$content = "ACRA Registration No.";
					}
					else if($string2 == "acra_registra_no_dot")
					{
						$content = ":";
					}
					else if($string2 == "member_type")
					{
						$content = "Corporate";
					}
					else
					{
						$content = $this->encryption->decrypt($get_member_name[0]["client_company_name"]);
					}
				}
			}

			return $content;
		}
		elseif($string2 == "Existing Member more than 25 percent")
		{
			$q = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id HAVING sum(member_shares.number_of_share) != 0');

	        if ($q->num_rows() > 0) {
	            foreach (($q->result()) as $key=>$row) {
	                if($row->field_type == "individual")
	                {
	                    $row->identification_no = $this->encryption->decrypt($row->identification_no);
	                    $row->name = $this->encryption->decrypt($row->name);

	                    $member_info[$key]['identification_no_or_uen'] = $row->identification_no;
	                    $member_info[$key]['officer_id'] = $row->officer_id;
	                    $member_info[$key]['field_type'] = $row->field_type;
	                }
	                elseif($row->field_type == "company")
	                {
	                    $row->register_no = $this->encryption->decrypt($row->register_no);
	                    $row->company_name = $this->encryption->decrypt($row->company_name);

	                    $member_info[$key]['identification_no_or_uen'] = $row->register_no;
	                    $member_info[$key]['officer_id'] = $row->officer_id;
	                    $member_info[$key]['field_type'] = $row->field_type;
	                }
	                else
	                {
	                    $row->registration_no = $this->encryption->decrypt($row->registration_no);
	                    $row->client_company_name = $this->encryption->decrypt($row->client_company_name);

	                    $member_info[$key]['identification_no_or_uen'] = $row->registration_no;
	                    $member_info[$key]['officer_id'] = $row->officer_id;
	                    $member_info[$key]['field_type'] = $row->field_type;
	                }
	                $member_info[$key]['share_number'] = $row->number_of_share;
	                $member_shares_detail[] = $member_info[$key];
	            }
	        }

	        $total_number_of_share = 0;
	        for($e = 0; $e < count($member_shares_detail); $e++ )
	        {
	            $number_of_share_info = (int)str_replace(',', '', $member_shares_detail[$e]["share_number"]);
	            $total_number_of_share = $total_number_of_share + $number_of_share_info;
	        }

	        for($h = 0; $h < count($member_shares_detail); $h++ )
	        {
	        	$this->db->select('
                    member_shares.*, 
                    z.company_name as tr_client_company_name, 
                    officer.identification_no, 
                    officer.name, 
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
                    nationality.nationality, 
                    officer_company.register_no, 
                    officer_company.company_name as officer_company_company_name, 
                    officer_company.entity_issued_by_registrar, 
                    officer_company.date_of_incorporation,
                    officer_company.country_of_incorporation,
                    officer_company.statutes_of,
                    officer_company.legal_form_entity,
                    officer_company.coporate_entity_name,
                    officer_company.address_type as officer_company_address_type,
                    officer_company.company_postal_code,
                    officer_company.company_street_name,
                    officer_company.company_building_name,
                    officer_company.company_unit_no1,
                    officer_company.company_unit_no2,
                    officer_company.company_foreign_address1,
                    officer_company.company_foreign_address2,
                    officer_company.company_foreign_address3, 
                    client.company_name as client_company_name, 
                    client.registration_no, 
                    client.incorporation_date,
                    client.client_country_of_incorporation,
                    company_type.company_type as company_type_name,
                    client.postal_code as client_postal_code, 
                    client.street_name as client_street_name,
                    client.building_name as client_builing_name,
                    client.unit_no1 as client_unit_no1,
                    client.unit_no2 as client_unit_no2,
                    client.client_statutes_of,
                    client.client_coporate_entity_name
                    ');
                $this->db->from('member_shares');
                $this->db->join('officer', 'officer.id = member_shares.officer_id AND officer.field_type = member_shares.field_type', 'left');
                $this->db->join('officer_company', 'officer_company.id = member_shares.officer_id AND officer_company.field_type = member_shares.field_type', 'left');
                $this->db->join('client', 'client.id = member_shares.officer_id AND member_shares.field_type = "client"', 'left');
                $this->db->join('company_type', 'company_type.id = client.company_type', 'left');
                $this->db->join('client as z', 'z.company_code = member_shares.company_code', 'left');
                $this->db->join('nationality', 'nationality.id = officer.nationality', 'left');
                $this->db->where('member_shares.company_code', $company_code);
                $this->db->where('member_shares.officer_id', $member_shares_detail[$h]["officer_id"]);
                $this->db->where('member_shares.field_type', $member_shares_detail[$h]["field_type"]);
                $this->db->order_by("id", "asc");

                $controller_query = $this->db->get();
                $controller_content = $controller_query->result_array();
                if($controller_content[0]["name"] != null)
                {
                    $controller_content[0]["officer_nationality_name"] = $controller_content[0]["nationality"];
                    $address = array(
                        'type'          => $controller_content[0]["officer_address_type"],
                        'street_name1'  => $controller_content[0]["officer_street_name"],
                        'unit_no1'      => $controller_content[0]["officer_unit_no1"],
                        'unit_no2'      => $controller_content[0]["officer_unit_no2"],
                        'building_name1'=> $controller_content[0]["officer_builing_name"],
                        'postal_code1'  => $controller_content[0]["officer_postal_code"],
                        'foreign_address1' => $controller_content[0]["officer_foreign_address1"],
                        'foreign_address2' => $controller_content[0]["officer_foreign_address2"],
                        'foreign_address3' => $controller_content[0]["officer_foreign_address3"]
                    );
                    $controller_content[0]["address"] = $this->write_address_local_foreign($address, "comma", "big_cap");
                }
                else if($controller_content[0]["officer_company_company_name"] != null)
                {
                    $controller_content[0]["officer_nationality_name"] = $controller_content[0]["country_of_incorporation"];
                    $address = array(
                        'type'          => $controller_content[0]["officer_company_address_type"],
                        'street_name1'  => $controller_content[0]["company_street_name"],
                        'unit_no1'      => $controller_content[0]["company_unit_no1"],
                        'unit_no2'      => $controller_content[0]["company_unit_no2"],
                        'building_name1'=> $controller_content[0]["company_building_name"],
                        'postal_code1'  => $controller_content[0]["company_postal_code"],
                        'foreign_address1' => $controller_content[0]["company_foreign_address1"],
                        'foreign_address2' => $controller_content[0]["company_foreign_address2"],
                        'foreign_address3' => $controller_content[0]["company_foreign_address3"]
                    );
                    $controller_content[0]["address"] = $this->write_address_local_foreign($address, "comma", "big_cap");
                }
                else if($controller_content[0]["client_company_name"] != null)
                {
                    $controller_content[0]["officer_nationality_name"] = "";
                    $address = array(
                        'type'          => "Local",
                        'street_name1'  => $controller_content[0]["client_street_name"],
                        'unit_no1'      => $controller_content[0]["client_unit_no1"],
                        'unit_no2'      => $controller_content[0]["client_unit_no2"],
                        'building_name1'=> $controller_content[0]["client_builing_name"],
                        'postal_code1'  => $controller_content[0]["client_postal_code"]
                    );
                    $controller_content[0]["address"] = $this->write_address_local_foreign($address, "comma", "big_cap");
                }

				$percentage_of_controller = ((int)$member_shares_detail[$h]["share_number"]/(int)$total_number_of_share) * 100;

	            if($percentage_of_controller >= 25)
	            {
					if(count($controller_content[0]) > 0)
                	{
                		$controller_content[0]["share_number"] = (int)$member_shares_detail[$h]["share_number"];
                		$controller_content[0]["percentage_share_number"] = $percentage_of_controller;
                    	$content[] = $controller_content[0];
					}
				}
                else
                {
                    //print_r("smaller");
                }
			}
	        return $content;
		}
		elseif($string2 == "Member to Controller" || $string2 == "Corp Rep Or Person Controller Name for Share Member" || $string2 == "Corp Rep Or Person Controller identification no for Share Member")
		{
			$get_member_info = $this->db->query("select transaction_member_shares.*, z.company_name as tr_client_company_name, officer.name, officer.identification_no, officer_company.company_name, officer_company.register_no, client.company_name as client_company_name, client.registration_no, transaction_certificate.new_certificate_no from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id AND transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id AND transaction_member_shares.field_type = officer_company.field_type left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = 'client' AND client.deleted != 1 left join client as z on z.company_code = transaction_member_shares.company_code right join transaction_certificate on transaction_certificate.officer_id = transaction_member_shares.officer_id and transaction_certificate.field_type = transaction_member_shares.field_type and transaction_certificate.transaction_id = transaction_member_shares.transaction_id and transaction_certificate.client_member_share_capital_id = transaction_member_shares.client_member_share_capital_id where transaction_member_shares.company_code='".$company_code."' AND transaction_member_shares.transaction_page_id='".$transaction_master_id."'");// AND transaction_member_shares.number_of_share > 0

	        $get_member_info = $get_member_info->result_array();

	        if(count($get_member_info) > 0)
	        {
	            for($t = 0 ; $t < count($get_member_info) ; $t++)
	            {
                    $get_member_info[$t]['tr_client_company_name'] = $this->encryption->decrypt($get_member_info[$t]['tr_client_company_name']);
                    if($get_member_info[$t]['field_type'] == "individual")
                    {
                        $get_member_info[$t]['identification_no'] = $this->encryption->decrypt($get_member_info[$t]['identification_no']);
                        $get_member_info[$t]['name'] = $this->encryption->decrypt($get_member_info[$t]['name']);

                        $transaction_member_info[$t]['identification_no_or_uen'] = $get_member_info[$t]['identification_no'];
                        $transaction_member_info[$t]['officer_id'] = $get_member_info[$t]['officer_id'];
                        $transaction_member_info[$t]['field_type'] = $get_member_info[$t]['field_type'];
                    }
                    elseif($get_member_info[$t]['field_type'] == "company")
                    {
                        $get_member_info[$t]['register_no'] = $this->encryption->decrypt($get_member_info[$t]['register_no']);
                        $get_member_info[$t]['company_name'] = $this->encryption->decrypt($get_member_info[$t]['company_name']);

                        $transaction_member_info[$t]['identification_no_or_uen'] = $get_member_info[$t]['register_no'];
                        $transaction_member_info[$t]['officer_id'] = $get_member_info[$t]['officer_id'];
                        $transaction_member_info[$t]['field_type'] = $get_member_info[$t]['field_type'];
                    }
                    elseif($get_member_info[$t]['field_type'] == "client")
                    {
                        $get_member_info[$t]['registration_no'] = $this->encryption->decrypt($get_member_info[$t]['registration_no']);
                        $get_member_info[$t]['client_company_name'] = $this->encryption->decrypt($get_member_info[$t]['client_company_name']);

                        $transaction_member_info[$t]['identification_no_or_uen'] = $get_member_info[$t]['registration_no'];
                        $transaction_member_info[$t]['officer_id'] = $get_member_info[$t]['officer_id'];
                        $transaction_member_info[$t]['field_type'] = $get_member_info[$t]['field_type'];
                    }
                    $transaction_member_info[$t]['share_number'] = $get_member_info[$t]['number_of_share'];
                    $datas[] = $transaction_member_info[$t];
	            }
	        }

	        $q = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id HAVING sum(member_shares.number_of_share) != 0');

	        if ($q->num_rows() > 0) {
	            foreach (($q->result()) as $key=>$row) {
	                if($row->field_type == "individual")
	                {
	                    $row->identification_no = $this->encryption->decrypt($row->identification_no);
	                    $row->name = $this->encryption->decrypt($row->name);

	                    $member_info[$key]['identification_no_or_uen'] = $row->identification_no;
	                    $member_info[$key]['officer_id'] = $row->officer_id;
	                    $member_info[$key]['field_type'] = $row->field_type;
	                }
	                elseif($row->field_type == "company")
	                {
	                    $row->register_no = $this->encryption->decrypt($row->register_no);
	                    $row->company_name = $this->encryption->decrypt($row->company_name);

	                    $member_info[$key]['identification_no_or_uen'] = $row->register_no;
	                    $member_info[$key]['officer_id'] = $row->officer_id;
	                    $member_info[$key]['field_type'] = $row->field_type;
	                }
	                else
	                {
	                    $row->registration_no = $this->encryption->decrypt($row->registration_no);
	                    $row->client_company_name = $this->encryption->decrypt($row->client_company_name);

	                    $member_info[$key]['identification_no_or_uen'] = $row->registration_no;
	                    $member_info[$key]['officer_id'] = $row->officer_id;
	                    $member_info[$key]['field_type'] = $row->field_type;
	                }
	                $member_info[$key]['share_number'] = $row->number_of_share;
	                $datas[] = $member_info[$key];
	            }
	        }

	        // begin the iteration for grouping data name and calculate the amount
	        $member_shares_detail = array();
	        foreach($datas as $data) {
	            $index = $this->data_exists($data['officer_id'], $data['field_type'], $member_shares_detail);
	            if ($index < 0) {
	                $member_shares_detail[] = $data;
	            }
	            else {
	                $member_shares_detail[$index]['share_number'] +=  (int)$data['share_number'];
	            }
	        }

	        $total_number_of_share = 0;
	        for($e = 0; $e < count($member_shares_detail); $e++ )
	        {
	            $number_of_share_info = (int)str_replace(',', '', $member_shares_detail[$e]["share_number"]);
	            $total_number_of_share = $total_number_of_share + $number_of_share_info;
	        }

	        for($h = 0; $h < count($member_shares_detail); $h++ )
	        {
	        	$this->db->select('
                    transaction_member_shares.*, 
                    z.company_name as tr_client_company_name, 
                    officer.identification_no, 
                    officer.name, 
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
                    nationality.nationality, 
                    officer_company.register_no, 
                    officer_company.company_name as officer_company_company_name, 
                    officer_company.entity_issued_by_registrar, 
                    officer_company.date_of_incorporation,
                    officer_company.country_of_incorporation,
                    officer_company.statutes_of,
                    officer_company.legal_form_entity,
                    officer_company.coporate_entity_name,
                    officer_company.address_type as officer_company_address_type,
                    officer_company.company_postal_code,
                    officer_company.company_street_name,
                    officer_company.company_building_name,
                    officer_company.company_unit_no1,
                    officer_company.company_unit_no2,
                    officer_company.company_foreign_address1,
                    officer_company.company_foreign_address2,
                    officer_company.company_foreign_address3, 
                    client.company_name as client_company_name, 
                    client.registration_no, 
                    client.incorporation_date,
                    client.client_country_of_incorporation,
                    company_type.company_type as company_type_name,
                    client.postal_code as client_postal_code, 
                    client.street_name as client_street_name,
                    client.building_name as client_builing_name,
                    client.unit_no1 as client_unit_no1,
                    client.unit_no2 as client_unit_no2,
                    client.client_statutes_of,
                    client.client_coporate_entity_name
                    ');
                $this->db->from('transaction_member_shares');
                $this->db->join('officer', 'officer.id = transaction_member_shares.officer_id AND officer.field_type = transaction_member_shares.field_type', 'left');
                $this->db->join('officer_company', 'officer_company.id = transaction_member_shares.officer_id AND officer_company.field_type = transaction_member_shares.field_type', 'left');
                $this->db->join('client', 'client.id = transaction_member_shares.officer_id AND transaction_member_shares.field_type = "client"', 'left');
                $this->db->join('company_type', 'company_type.id = client.company_type', 'left');
                $this->db->join('client as z', 'z.company_code = transaction_member_shares.company_code', 'left');
                $this->db->join('nationality', 'nationality.id = officer.nationality', 'left');
                $this->db->where('transaction_member_shares.transaction_page_id', $transaction_master_id);
                $this->db->where('transaction_member_shares.company_code', $company_code);
                $this->db->where('transaction_member_shares.officer_id', $member_shares_detail[$h]["officer_id"]);
                $this->db->where('transaction_member_shares.field_type', $member_shares_detail[$h]["field_type"]);
                $this->db->order_by("id", "asc");

                $controller_query = $this->db->get();
                $controller_content = $controller_query->result_array();
                if($controller_content[0]["name"] != null)
                {
                    $controller_content[0]["officer_nationality_name"] = $controller_content[0]["nationality"];
                    $address = array(
                        'type'          => $controller_content[0]["officer_address_type"],
                        'street_name1'  => $controller_content[0]["officer_street_name"],
                        'unit_no1'      => $controller_content[0]["officer_unit_no1"],
                        'unit_no2'      => $controller_content[0]["officer_unit_no2"],
                        'building_name1'=> $controller_content[0]["officer_builing_name"],
                        'postal_code1'  => $controller_content[0]["officer_postal_code"],
                        'foreign_address1' => $controller_content[0]["officer_foreign_address1"],
                        'foreign_address2' => $controller_content[0]["officer_foreign_address2"],
                        'foreign_address3' => $controller_content[0]["officer_foreign_address3"]
                    );
                    $controller_content[0]["address"] = $this->write_address_local_foreign($address, "comma", "big_cap");
                }
                else if($controller_content[0]["officer_company_company_name"] != null)
                {
                    $controller_content[0]["officer_nationality_name"] = $controller_content[0]["country_of_incorporation"];
                    $address = array(
                        'type'          => $controller_content[0]["officer_company_address_type"],
                        'street_name1'  => $controller_content[0]["company_street_name"],
                        'unit_no1'      => $controller_content[0]["company_unit_no1"],
                        'unit_no2'      => $controller_content[0]["company_unit_no2"],
                        'building_name1'=> $controller_content[0]["company_building_name"],
                        'postal_code1'  => $controller_content[0]["company_postal_code"],
                        'foreign_address1' => $controller_content[0]["company_foreign_address1"],
                        'foreign_address2' => $controller_content[0]["company_foreign_address2"],
                        'foreign_address3' => $controller_content[0]["company_foreign_address3"]
                    );
                    $controller_content[0]["address"] = $this->write_address_local_foreign($address, "comma", "big_cap");
                }
                else if($controller_content[0]["client_company_name"] != null)
                {
                    $controller_content[0]["officer_nationality_name"] = "";
                    $address = array(
                        'type'          => "Local",
                        'street_name1'  => $controller_content[0]["client_street_name"],
                        'unit_no1'      => $controller_content[0]["client_unit_no1"],
                        'unit_no2'      => $controller_content[0]["client_unit_no2"],
                        'building_name1'=> $controller_content[0]["client_builing_name"],
                        'postal_code1'  => $controller_content[0]["client_postal_code"]
                    );
                    $controller_content[0]["address"] = $this->write_address_local_foreign($address, "comma", "big_cap");
                }
                
	            $check_controller_query = $this->db->query("SELECT client_controller.*, client_controller.company_code as client_controller_company_code, client_controller.id as client_controller_id, client_controller.field_type as client_controller_field_type, officer.*, officer.address_type as officer_address_type, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.address_type as officer_company_address_type, officer_company.company_name as officer_company_company_name, client.*, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, nationality.nationality as officer_nationality_name FROM client_controller LEFT JOIN officer ON officer.id = client_controller.officer_id AND officer.field_type = client_controller.field_type LEFT JOIN officer_company ON officer_company.id = client_controller.officer_id AND officer_company.field_type = client_controller.field_type LEFT JOIN client ON client.id = client_controller.officer_id AND client_controller.field_type = 'client' LEFT JOIN nationality ON nationality.id = officer.nationality WHERE client_controller.company_code = '".$company_code."' AND client_controller.officer_id = '".$member_shares_detail[$h]["officer_id"]."' AND client_controller.field_type = '".$member_shares_detail[$h]["field_type"]."' AND client_controller.date_of_cessation = '' AND client_controller.deleted = 0");


	            if ($check_controller_query->num_rows() > 0) 
	            {
	                //print_r($check_controller_query->result_array());
	                $check_controller_query = $check_controller_query->result_array();
	                $percentage_of_controller = ((int)$member_shares_detail[$h]["share_number"]/(int)$total_number_of_share) * 100;
	                if($percentage_of_controller >= 25)
	                {
	                	if($string2 == "Member to Controller")
	                    {
	                    	if(count($controller_content[0]) > 0)
	                    	{
	                    		$controller_content[0]["share_number"] = (int)$member_shares_detail[$h]["share_number"];
	                    		$controller_content[0]["percentage_share_number"] = $percentage_of_controller;
	                        	$content[] = $controller_content[0];
	                    	}
						}
	                    //print_r("bigger");
	                    //print_r($transaction_member_info);
	                    //for($d = 0; $d < count($transaction_member_info); $d++)
	                    //{
	                        //if($transaction_member_info[$d]["officer_id"] == $member_shares_detail[$h]["officer_id"] && $transaction_member_info[$d]["field_type"] == $member_shares_detail[$h]["field_type"])
	                        //{
	                            //need to sign notice of controller, because more than 25%
	                            //$controller_detail[] = $member_shares_detail[$h];
	                        //}
	                    //}
	                }
	                else
	                {
	                    //print_r("smaller");
	                    if($value != NULL)
	                    {
	                    	$client_controller['date_of_cessation'] = $value;
		                    $this->db->update("client_controller",$client_controller,array("id" => $check_controller_query[0]["id"]));
	                    }
	                }
	                
	            }
	            else
	            {
	                //print_r($member_shares_detail[$h]);
	                $percentage_of_controller = ((int)$member_shares_detail[$h]["share_number"]/(int)$total_number_of_share) * 100;
	                if($percentage_of_controller >= 25)
	                {
	                    //$controller_detail[] = $member_shares_detail[$h];
	                    
	                    //print_r($controller_content);

	                    if($string2 == "Member to Controller")
	                    {
	                    	if(count($controller_content[0]) > 0)
	                    	{
	                    		$controller_content[0]["share_number"] = (int)$member_shares_detail[$h]["share_number"];
	                    		$controller_content[0]["percentage_share_number"] = $percentage_of_controller;
	                        	$content[] = $controller_content[0];

		                        if($value != NULL)
		                        {
		                        	$insert_client_controller["company_code"] = $company_code;
		                        	$insert_client_controller["officer_id"] = $member_shares_detail[$h]["officer_id"];
		                        	$insert_client_controller["field_type"] = $member_shares_detail[$h]["field_type"];
		                        	//$insert_client_controller["date_of_birth"] = (($controller_content[0]["date_of_birth"] != null)? date("d/m/Y", strtotime($controller_content[0]["date_of_birth"])):(($controller_content[0]["date_of_incorporation"] != null)?$controller_content[0]["date_of_incorporation"]:(($controller_content[0]["incorporation_date"] != null)?$controller_content[0]["incorporation_date"]:"")));
		                        	//$insert_client_controller["nationality_name"] = $controller_content[0]["nationality_name"];
		                        	//$insert_client_controller["address"] = $controller_content[0]["address"];
		                        	$insert_client_controller["date_of_registration"] = $value;
		                        	$insert_client_controller["date_of_notice"] = $value;
		                        	$insert_client_controller["is_confirm_by_reg_controller"] = "yes";
		                        	$insert_client_controller["confirmation_received_date"] = $value;
		                        	$insert_client_controller["date_of_entry"] = $value;
		                        	$insert_client_controller["supporting_document"] = "[]";

		                        	$this->db->insert("client_controller",$insert_client_controller);
		                        }
		                    }
	                    }
	                    else
	                    {
	                        $controller_query = $controller_content;

	                        if($controller_query[0]["company_name"] != null)
	                        {
	                            $get_corp_rep_info = $this->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" and corporate_representative.registration_no = "'.$this->encryption->decrypt($controller_query[0]['register_no']).'" and corporate_representative.subsidiary_name = "'.$this->encryption->decrypt($controller_query[0]['tr_client_company_name']).'"');

	                            $get_corp_rep_info = $get_corp_rep_info->result_array();

	                            if($string2 == "Corp Rep Or Person Controller Name for Share Member")
	                            {
	                                $content = $get_corp_rep_info[0]["name_of_corp_rep"];
	                            }
	                            else if($string2 == "Corp Rep Or Person Controller identification no for Share Member")
	                            {
	                                $content = $get_corp_rep_info[0]["identity_number"];
	                            }
	                        }
	                        elseif($controller_query[0]['client_company_name'] != null)
	                        {
	                            $get_corp_rep_info = $this->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" and corporate_representative.registration_no = "'.$this->encryption->decrypt($controller_query[0]['registration_no']).'" and corporate_representative.subsidiary_name = "'.$this->encryption->decrypt($controller_query[0]['tr_client_company_name']).'"');

	                            $get_corp_rep_info = $get_corp_rep_info->result_array();

	                            if($string2 == "Corp Rep Or Person Controller Name for Share Member")
	                            {
	                                $content = $get_corp_rep_info[0]["name_of_corp_rep"];
	                            }
	                            else if($string2 == "Corp Rep Or Person Controller identification no for Share Member")
	                            {
	                                $content = $get_corp_rep_info[0]["identity_number"];
	                            }
	                        }
	                    }
	                }
	                else
	                {
	                    //print_r("smaller");
	                }

	            }
	        }
	        return $content;
		}
		elseif($string2 == "high_share_coporate_rep")
		{
			$q = $this->db->query('select member_shares.*, z.company_name as tr_client_company_name, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" left join client as z on z.company_code = member_shares.company_code where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id HAVING sum(member_shares.number_of_share) != 0');
			
	        if ($q->num_rows() > 0) {
	            foreach (($q->result()) as $key=>$row) {
	                if($row->field_type == "individual")
	                {
	                    $row->identification_no = $this->encryption->decrypt($row->identification_no);
	                    $row->name = $this->encryption->decrypt($row->name);
	                    $member_info[$key]['name'] = $row->name;
	                    $member_info[$key]['identification_no_or_uen'] = $row->identification_no;
	                    $member_info[$key]['officer_id'] = $row->officer_id;
	                    $member_info[$key]['field_type'] = $row->field_type;
	                }
	                elseif($row->field_type == "company")
	                {
	                    $row->register_no = $this->encryption->decrypt($row->register_no);
	                    $row->company_name = $this->encryption->decrypt($row->company_name);
	                    $member_info[$key]['name'] = $row->company_name;
	                    $member_info[$key]['identification_no_or_uen'] = $row->register_no;
	                    $member_info[$key]['officer_id'] = $row->officer_id;
	                    $member_info[$key]['field_type'] = $row->field_type;
	                }
	                else
	                {
	                    $row->registration_no = $this->encryption->decrypt($row->registration_no);
	                    $row->client_company_name = $this->encryption->decrypt($row->client_company_name);
	                    $member_info[$key]['name'] = $row->client_company_name;
	                    $member_info[$key]['identification_no_or_uen'] = $row->registration_no;
	                    $member_info[$key]['officer_id'] = $row->officer_id;
	                    $member_info[$key]['field_type'] = $row->field_type;
	                }
	                $member_info[$key]['share_number'] = $row->number_of_share;
	                $member_info[$key]['tr_client_company_name'] = $this->encryption->decrypt($row->tr_client_company_name);
	                $member_shares_detail[] = $member_info[$key];
	            }
	        }

	        $total_number_of_share = 0;
	        for($e = 0; $e < count($member_shares_detail); $e++ )
	        {
	            $number_of_share_info = (int)str_replace(',', '', $member_shares_detail[$e]["share_number"]);
	            $total_number_of_share = $total_number_of_share + $number_of_share_info;
	        }

	        $final_cop_rep = array();
	        for($h = 0; $h < count($member_shares_detail); $h++ )
	        {
	        	if($member_shares_detail[$h]["field_type"] != "individual")
	        	{
					$percentage_of_controller = ((int)$member_shares_detail[$h]["share_number"]/(int)$total_number_of_share) * 100;
		            if($h == 0)
		            {
		            	array_push($final_cop_rep, $member_shares_detail[$h]);
		            }
		            else
		            {
		            	if((int)$member_shares_detail[$h]["share_number"] > (int)$final_cop_rep[0]["share_number"])
		            	{
		            		$final_cop_rep[0] = $member_shares_detail[$h];
		            	}
		            }
		        }
	        }

            $get_corp_rep_info = $this->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" and corporate_representative.registration_no = "'.$final_cop_rep[0]['identification_no_or_uen'].'" and corporate_representative.subsidiary_name = "'.$final_cop_rep[0]['tr_client_company_name'].'"');

            $get_corp_rep_info = $get_corp_rep_info->result_array();

            $get_corp_rep_info[0]["name"] = $final_cop_rep[0]["name"];
   
	        return $get_corp_rep_info;
		}
		elseif($string2 == "Transaction Client Controller Info" || $string2 == "Corp Rep Or Person Controller Name" || $string2 == "Corp Rep Or Person Controller identification no")
		{
			$this->db->select('transaction_client_controller.*, z.company_name as tr_client_company_name, transaction_client_controller.company_code as client_controller_company_code, transaction_client_controller.id as client_controller_id, transaction_client_controller.field_type as client_controller_field_type, officer.*, officer.address_type as officer_address_type, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.address_type as officer_company_address_type, officer_company.company_name as officer_company_company_name, client.*, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, nationality.nationality as officer_nationality_name');
	        $this->db->from('transaction_client_controller');
	        $this->db->join('officer', 'officer.id = transaction_client_controller.officer_id AND officer.field_type = transaction_client_controller.field_type', 'left');
	        $this->db->join('officer_company', 'officer_company.id = transaction_client_controller.officer_id AND officer_company.field_type = transaction_client_controller.field_type', 'left');
	        $this->db->join('client', 'client.id = transaction_client_controller.officer_id AND transaction_client_controller.field_type = "client"', 'left');
	       	$this->db->join('company_type', 'company_type.id = client.company_type', 'left');
	        $this->db->join('transaction_client as z', 'z.company_code = transaction_client_controller.company_code', 'left');
	        $this->db->join('nationality', 'nationality.id = officer.nationality', 'left');
	        $this->db->where('transaction_client_controller.transaction_id', $transaction_master_id);
	        $this->db->where('transaction_client_controller.company_code', $company_code);

	        //officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, officer_company.country_of_incorporation, client.company_name as client_company_name, client.registration_no, company_type.company_type as company_type_name

	        if($string2 != "Transaction Client Controller Info")
	        {
	        	$this->db->where('transaction_client_controller.id', $id);
	        }
	        $this->db->order_by("client_controller_id", "asc");

	        $controller_query = $this->db->get();

	        if($string2 == "Transaction Client Controller Info")
	        {
	        	$content = $controller_query->result_array();
	        }
	        else
	        {
	        	$controller_query = $controller_query->result_array();

	        	if($controller_query[0]["company_name"] != null)
				{
					$get_corp_rep_info = $this->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" and corporate_representative.registration_no = "'.$this->encryption->decrypt($controller_query[0]['register_no']).'" and corporate_representative.subsidiary_name = "'.$this->encryption->decrypt($controller_query[0]['tr_client_company_name']).'"');

					$get_corp_rep_info = $get_corp_rep_info->result_array();

					if($string2 == "Corp Rep Or Person Controller Name")
					{
						$content = $get_corp_rep_info[0]["name_of_corp_rep"];
					}
					else if($string2 == "Corp Rep Or Person Controller identification no")
					{
						$content = $get_corp_rep_info[0]["identity_number"];
					}
				}
				elseif($controller_query[0]['client_company_name'] != null)
				{
					$get_corp_rep_info = $this->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" and corporate_representative.registration_no = "'.$this->encryption->decrypt($controller_query[0]['registration_no']).'" and corporate_representative.subsidiary_name = "'.$this->encryption->decrypt($controller_query[0]['tr_client_company_name']).'"');

					$get_corp_rep_info = $get_corp_rep_info->result_array();

					if($string2 == "Corp Rep Or Person Controller Name")
					{
						$content = $get_corp_rep_info[0]["name_of_corp_rep"];
					}
					else if($string2 == "Corp Rep Or Person Controller identification no")
					{
						$content = $get_corp_rep_info[0]["identity_number"];
					}
				}
	        }
	       
	        return $content;
		}
		elseif($string2 == "Secretary Info")
		{
			$get_secretary_info = $this->db->query("select transaction_client_officers.*, officer.name, officer.identification_no from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 4");

			$content = $get_secretary_info->result_array();

			return $content;
		}
		elseif($string2 == "Client Secretary Info")
		{
			$get_secretary_info = $this->db->query("select client_officers.*, officer.name, officer.identification_no from client_officers left join officer on client_officers.officer_id = officer.id AND client_officers.field_type = officer.field_type where client_officers.company_code='".$company_code."' AND client_officers.position = 4");

			$content = $get_secretary_info->result_array();

			return $content;
		}
		elseif($string2 == "Allotment - members ID")
		{
			$get_member_name = $this->db->query("select transaction_member_shares.*, officer.identification_no, officer_company.register_no, client.registration_no from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id AND transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id AND transaction_member_shares.field_type = officer_company.field_type left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = 'client' AND client.deleted != 1 where transaction_member_shares.company_code='".$company_code."' AND transaction_member_shares.id = '".$id."' AND transaction_page_id='".$transaction_master_id."'");

			$get_member_name = $get_member_name->result_array();

			if($get_member_name[0]["identification_no"] != null)
			{
				$content = $this->encryption->decrypt($get_member_name[0]["identification_no"]);
			}
			else if($get_member_name[0]["register_no"] != null)
			{
				$content = $this->encryption->decrypt($get_member_name[0]["register_no"]);
			}
			else if($get_member_name[0]["registration_no"] != null)
			{
				$content = $this->encryption->decrypt($get_member_name[0]["registration_no"]);
			}

			return $content;
		}
		elseif($string2 == "Allotment - members address" || $string2 == "Transferee - members address" || $string2 == "Transferee - Address" || $string2 == "Allotment - members address line 1" || $string2 == "Allotment - members address line 2" || $string2 == "Allotment - members address(letter)" || $string2 == "Transferee - members address line 1" || $string2 == "Transferee - members address line 2")
		{
			$member_address_type = 'Local';
			$foreign_address1 = "";
			$foreign_address2 = "";
			$foreign_address3 = "";

			$get_member_address = $this->db->query('select transaction_member_shares.*, officer.address_type as officer_address_type, officer.alternate_address, officer.postal_code1, officer.street_name1, officer.building_name1, officer.unit_no1, officer.unit_no2, officer.postal_code2 as officer_postal_code2, officer.building_name2 as officer_building_name2, officer.street_name2 as officer_street_name2, officer.unit_no3 as officer_unit_no3, officer.unit_no4 as officer_unit_no4, officer.foreign_address1, officer.foreign_address2, officer.foreign_address3, officer_company.field_type as officer_company_field_type, officer_company.register_no, officer_company.address_type as officer_company_address_type, officer_company.company_postal_code, officer_company.company_street_name, officer_company.company_building_name, officer_company.company_unit_no1, officer_company.company_unit_no2, officer_company.company_foreign_address1, officer_company.company_foreign_address2, officer_company.company_foreign_address3, client.postal_code as client_postal_code, client.street_name as client_street_name, client.building_name as client_building_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2 from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id and transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id and transaction_member_shares.field_type = officer_company.field_type left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = "client" AND client.deleted != 1 where transaction_member_shares.company_code="'.$company_code.'" AND transaction_member_shares.id = "'.$id.'" AND transaction_page_id="'.$transaction_master_id.'"');

			$get_member_address = $get_member_address->result_array();

			if($get_member_address[0]['officer_address_type'] != null)
			{
				if($get_member_address[0]["alternate_address"] == "1")
				{
					$member_unit_no1 = $get_member_address[0]["officer_unit_no3"];
					$member_unit_no2 = $get_member_address[0]["officer_unit_no4"];
					$member_street_name = $get_member_address[0]["officer_street_name2"];
					$member_building_name = $get_member_address[0]["officer_building_name2"];
					$member_postal_code = $get_member_address[0]["officer_postal_code2"];
				}
				else
				{
					$member_address_type = $get_member_address[0]['officer_address_type'];
					$member_unit_no1 = $get_member_address[0]["unit_no1"];
					$member_unit_no2 = $get_member_address[0]["unit_no2"];
					$member_street_name = $get_member_address[0]["street_name1"];
					$member_building_name = $get_member_address[0]["building_name1"];
					$member_postal_code = $get_member_address[0]["postal_code1"];
					$foreign_address1 = $get_member_address[0]["foreign_address1"];
					$foreign_address2 = $get_member_address[0]["foreign_address2"];
					$foreign_address3 = $get_member_address[0]["foreign_address3"];
				}
			}
			elseif($get_member_address[0]['officer_company_address_type'] != null)
			{
				$member_address_type = $get_member_address[0]['officer_company_address_type'];
				$member_unit_no1 = $get_member_address[0]["company_unit_no1"];
				$member_unit_no2 = $get_member_address[0]["company_unit_no2"];
				$member_street_name = $get_member_address[0]["company_street_name"];
				$member_building_name = $get_member_address[0]["company_building_name"];
				$member_postal_code = $get_member_address[0]["company_postal_code"];
				$foreign_address1 = $get_member_address[0]["company_foreign_address1"];
				$foreign_address2 = $get_member_address[0]["company_foreign_address2"];
				$foreign_address3 = $get_member_address[0]["company_foreign_address3"];
			}
			else
			{
				$member_unit_no1 = $get_member_address[0]["client_unit_no1"];
				$member_unit_no2 = $get_member_address[0]["client_unit_no2"];
				$member_street_name = $get_member_address[0]["client_street_name"];
				$member_building_name = $get_member_address[0]["client_building_name"];
				$member_postal_code = $get_member_address[0]["client_postal_code"];
			}

			$address = array(
				'type' 			=> $member_address_type,
				'street_name1' 	=> strtoupper($member_street_name),
				'unit_no1'		=> strtoupper($member_unit_no1),
				'unit_no2'		=> strtoupper($member_unit_no2),
				'building_name1'=> strtoupper($member_building_name),
				'postal_code1'	=> strtoupper($member_postal_code),
				'foreign_address1' => strtoupper($foreign_address1),
				'foreign_address2' => strtoupper($foreign_address2),
				'foreign_address3' => strtoupper($foreign_address3)
			);

			if($string2 == "Transferee - Address" || $string2 == "Allotment - members address(letter)")
			{
				if($document_name == "Transfer Form" && $document_category_id == 2)
				{
					$content = $this->write_address_local_foreign($address, "comma", "big_cap");
				}
				else
				{
					$content = $this->write_address_local_foreign($address, "letter", "big_cap");
				}
			}
			else
			{
				$content = $this->write_address_local_foreign($address, "comma", "big_cap");
			}

			$words = explode(' ', $content);

			$maxLineLength = 55;

			$currentLength = 0;
			$index = 0;

			foreach ($words as $word) {
			    // +1 because the word will receive back the space in the end that it loses in explode()
			    $wordLength = strlen($word) + 1;

			    if (($currentLength + $wordLength) <= $maxLineLength) {
			        $output[$index] .= $word . ' ';
			        $currentLength += $wordLength;
			    } else {
			        $index += 1;
			        $currentLength = $wordLength;
			        $output[$index] = $word . ' ';
			    }
			}

			if($string2 == "Allotment - members address line 1" || $string2 == "Transferee - members address line 1")
			{
				$content = $output[0];
			}
			else if($string2 == "Allotment - members address line 2" || $string2 == "Transferee - members address line 2")
			{
				$content = $output[1];
			}

			return $content;
		}
		elseif($string2 == "Allotment - members nationality")
		{
			$get_member_nationality = $this->db->query('select transaction_member_shares.*, nationality.nationality as nationality_name, officer_company.country_of_incorporation from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id and transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id and transaction_member_shares.field_type = officer_company.field_type left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = "client" AND client.deleted != 1 left join nationality on nationality.id = officer.nationality where transaction_member_shares.company_code="'.$company_code.'" AND transaction_member_shares.id = "'.$id.'" AND transaction_page_id="'.$transaction_master_id.'"');

			$get_member_nationality = $get_member_nationality->result_array();

			if($get_member_nationality[0]["nationality_name"] != null)
			{
				$content = $get_member_nationality[0]["nationality_name"];
			}
			else if($get_member_nationality[0]["country_of_incorporation"] != null)
			{
				$content = $get_member_nationality[0]["country_of_incorporation"];
			}
			else
			{
				$content = "";
			}
			return $content;
		}
		elseif($string2 == "Allotment - members telephone")
		{
			$get_member_telephone = $this->db->query('select transaction_member_shares.*, officer_mobile_no.mobile_no, officer_company_phone_number.phone_number from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id and transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id and transaction_member_shares.field_type = officer_company.field_type left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = "client" AND client.deleted != 1 left join officer_mobile_no on officer_mobile_no.officer_id = officer.id left join officer_company_phone_number on officer_company_phone_number.officer_company_id = officer_company.id where transaction_member_shares.company_code="'.$company_code.'" AND transaction_member_shares.id = "'.$id.'" AND transaction_page_id="'.$transaction_master_id.'"');

			$get_member_telephone = $get_member_telephone->result_array();

			if($get_member_telephone[0]["mobile_no"] != null)
			{
				$content = $this->encryption->decrypt($get_member_telephone[0]["mobile_no"]);
			}
			else if($get_member_telephone[0]["phone_number"] != null)
			{
				$content = $this->encryption->decrypt($get_member_telephone[0]["phone_number"]);
			}
			else
			{
				$content = "";
			}
			return $content;
		}
		elseif($string2 == "Allotment - members email")
		{
			$get_member_email = $this->db->query('select transaction_member_shares.*, officer_email.email as officer_email, officer_company_email.email as officer_company_email from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id and transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id and transaction_member_shares.field_type = officer_company.field_type left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = "client" AND client.deleted != 1 left join officer_email on officer_email.officer_id = officer.id AND officer_email.primary_email = 1 left join officer_company_email on officer_company_email.officer_company_id = officer_company.id AND officer_company_email.primary_email = 1 where transaction_member_shares.company_code="'.$company_code.'" AND transaction_member_shares.id = "'.$id.'" AND transaction_page_id="'.$transaction_master_id.'"');

			$get_member_email = $get_member_email->result_array();

			if($get_member_email[0]["officer_email"] != null)
			{
				$content = $this->encryption->decrypt($get_member_email[0]["officer_email"]);
			}
			else if($get_member_telephone[0]["officer_company_email"] != null)
			{
				$content = $this->encryption->decrypt($get_member_email[0]["officer_company_email"]);
			}
			else
			{
				$content = "";
			}
			return $content;
		}
		elseif($string2 == "Allotment - members officers occupation")
		{
			$get_member_occupation = $this->db->query('select client_officers_position.position as client_officers_position
				from transaction_member_shares 
				left join transaction_client_officers on transaction_client_officers.officer_id = transaction_member_shares.officer_id and transaction_client_officers.field_type = transaction_member_shares.field_type
				left join client_officers_position on client_officers_position.id = transaction_client_officers.position
				where transaction_member_shares.company_code="'.$company_code.'" AND transaction_member_shares.id = "'.$id.'" AND transaction_page_id="'.$transaction_master_id.'"');

			if ($get_member_occupation->num_rows() > 0) 
			{
                foreach (($get_member_occupation->result_array()) as $key => $row) 
                {
                	if($key == 0)
                	{
                		$content = $row["client_officers_position"];
                	}
                	elseif(($key + 1) == count($get_member_occupation->result_array()))
                	{
                		$content = $content. " & " . $row["client_officers_position"];
                	}
                	else
                	{
                		$content = $content. ", " . $row["client_officers_position"];
                	}
                }
            }
            else
			{
				$content = "";
			}
            return $content;
		}
		elseif($string2 == "Allotment - members designation")
		{
			$get_member_designation = $this->db->query('select client_officers_position.position as client_officers_position
				from transaction_member_shares 
				left join transaction_client_officers on transaction_client_officers.officer_id = transaction_member_shares.officer_id and transaction_client_officers.field_type = transaction_member_shares.field_type
				left join client_officers_position on client_officers_position.id = transaction_client_officers.position
				where transaction_member_shares.company_code="'.$company_code.'" AND transaction_member_shares.id = "'.$id.'" AND transaction_page_id="'.$transaction_master_id.'"');

			if ($get_member_occupation->num_rows() > 0) 
			{
                foreach (($get_member_occupation->result_array()) as $key => $row) 
                {
                	if($key == 0)
                	{
                		$content = $row["client_officers_position"];
                	}
                	elseif(($key + 1) == count($get_member_occupation->result_array()))
                	{
                		$content = $content. " & " . $row["client_officers_position"];
                	}
                	else
                	{
                		$content = $content. ", " . $row["client_officers_position"];
                	}
                }
            }
            else
			{
				$content = "";
			}
            return $content;
		}
		elseif($string2 == "Allotment - number of shares" || $string2 == "Allotment - number of shares(number)")
		{
			if($document_name == "First Director Resolutions (One)" || $document_name == "Allotment-Share Cert")
			{
				if($document_name == "Allotment-Share Cert")
				{
					$get_member_shares = $this->db->query('select transaction_member_shares.* from transaction_member_shares where transaction_member_shares.company_code="'.$company_code.'" AND transaction_page_id="'.$transaction_master_id.'" AND transaction_member_shares.id = "'.$id.'"');
				}
				else
				{
					$get_member_shares = $this->db->query('select transaction_member_shares.* from transaction_member_shares where transaction_member_shares.company_code="'.$company_code.'" AND transaction_page_id="'.$transaction_master_id.'"');
				}

				$get_member_shares = $get_member_shares->result_array();

				if($document_name == "Allotment-Share Cert" && $string2 == "Allotment - number of shares(number)")
				{
					$content = number_format($get_member_shares[0]["number_of_share"]);
				}
				else
				{
					if($document_name == "First Director Resolutions (One)")
					{
						for($r = 0; $r < count($get_member_shares); $r++)
		        		{
		        			if($r == 0)
		        			{
		        				if($document_category_id == 2)
		        				{
		        					$content = number_format($get_member_shares[$r]["number_of_share"]);
		        				}
		        				else
		        				{
		        					$content = strtoupper($this->convert_number_to_word_model->convert_number_to_words($get_member_shares[$r]["number_of_share"]))." (".number_format($get_member_shares[$r]["number_of_share"]).")";
		        				}
		        			}
		        			else if($r == (count($get_member_shares) - 1))
		        			{
		        				$content = $content.", and ".strtoupper($this->convert_number_to_word_model->convert_number_to_words($get_member_shares[$r]["number_of_share"]))." (".number_format($get_member_shares[$r]["number_of_share"]).")";
		        			}
		        			else
		        			{
		        				$content = $content.", ".strtoupper($this->convert_number_to_word_model->convert_number_to_words($get_member_shares[$r]["number_of_share"]))." (".number_format($get_member_shares[$r]["number_of_share"]).")";
		        			}
		        		}
					}
					else
					{
						$content = strtoupper($this->convert_number_to_word_model->convert_number_to_words($get_member_shares[0]["number_of_share"]))." (".number_format($get_member_shares[0]["number_of_share"]).")";
					}
				}
			}
			else
			{
				$get_member_shares = $this->db->query('select transaction_member_shares.* from transaction_member_shares where transaction_member_shares.company_code="'.$company_code.'" AND transaction_member_shares.id = "'.$id.'" AND transaction_page_id="'.$transaction_master_id.'"');

				$get_member_shares = $get_member_shares->result_array();

				if($document_name == "Allotment-Share Application Form" || ($document_name == "Shares allotment form" && $document_category_id == 2) || $document_name == "Information and verification of clients")
				{
					$content = number_format($get_member_shares[0]["number_of_share"]);
				}
				else
				{
					$content = number_format($get_member_shares[0]["number_of_share"])."/-";
				}
			}
			return $content;
		}
		elseif($string2 == "Allotment - amount of shares" || $string2 == "Allotment - amount of paid")
		{
			if($document_name == "Shares allotment form" || $document_name = "Allotment-Share Application Form")
			{
				$get_member_amount_shares = $this->db->query('select transaction_member_shares.*, currencies.currency from transaction_member_shares left join transaction_client_member_share_capital as share_capital on transaction_member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where transaction_member_shares.company_code="'.$company_code.'" AND transaction_page_id="'.$transaction_master_id.'" AND transaction_member_shares.id = "'.$id.'"');
			}
			else
			{
				$get_member_amount_shares = $this->db->query('select transaction_member_shares.*, currencies.currency from transaction_member_shares left join client_member_share_capital as share_capital on transaction_member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where transaction_member_shares.company_code="'.$company_code.'" AND transaction_page_id="'.$transaction_master_id.'" AND transaction_member_shares.id = "'.$id.'"');
			}

			$get_member_amount_shares = $get_member_amount_shares->result_array();

			if($string2 == "Allotment - amount of shares")
			{
				if($document_name == "Allotment-Share Cert")
				{
					$content = number_format($get_member_amount_shares[0]["amount_share"],2)."/-";
				}
				else if($document_name == "Shares allotment form" && $document_category_id == 2)
				{
					$content = $get_member_amount_shares[0]["currency"].number_format($get_member_amount_shares[0]["amount_share"],2);
				}
				else
				{
					$content = $get_member_amount_shares[0]["currency"].number_format($get_member_amount_shares[0]["amount_share"],2)."/-";
				}
			}
			else if($string2 == "Allotment - amount of paid")
			{
				$content = $get_member_amount_shares[0]["currency"].number_format($get_member_amount_shares[0]["amount_paid"],2)."/-";
			}
			return $content;
			
		}
		elseif($string2 == "check_latest_amount_share") {
			$get_member_total_amount_share = $this->db->query('select sum(amount_share) as total_amount_share from member_shares where member_shares.company_code="'.$company_code.'"');

			$get_member_total_amount_share = $get_member_total_amount_share->result_array();

			$content = $get_member_total_amount_share[0]["total_amount_share"];

			return $content;
		}
		elseif($string2 == "Allotment - amount share all" || $string2 == "Allotment - amount of share all with currency")
		{
			$get_member_total_amount_share = $this->db->query('select sum(amount_share) as total_amount_share, currencies.currency from transaction_member_shares left join client_member_share_capital as share_capital on transaction_member_shares.client_member_share_capital_id = share_capital.id left join currency as currencies on currencies.id = share_capital.currency_id where transaction_page_id="'.$transaction_master_id.'" AND transaction_member_shares.company_code="'.$company_code.'"');

			$get_member_total_amount_share = $get_member_total_amount_share->result_array();

			if($string2 == "Allotment - amount of share all with currency")
			{
				$content = $get_member_total_amount_share[0]["currency"].number_format($get_member_total_amount_share[0]["total_amount_share"],2);
			}
			else
			{
				$content = $get_member_total_amount_share[0]["total_amount_share"];
			}

			return $content;
		}
		elseif($string2 == "Allotment - number of shares all")
		{
			$get_member_total_shares = $this->db->query('select sum(number_of_share) as total_number_of_share from transaction_member_shares where transaction_page_id="'.$transaction_master_id.'" AND transaction_member_shares.company_code="'.$company_code.'"');

			$get_member_total_shares = $get_member_total_shares->result_array();

			if($document_name == "First Director Resolutions (Many)")
			{
				$total_share = $get_member_total_shares[0]["total_number_of_share"];
				$content = strtoupper($this->convert_number_to_word_model->convert_number_to_words($total_share))." (".number_format($get_member_total_shares[0]["total_number_of_share"]).")";
			}
			else if($document_name == "DRIW-Allotment of Shares" || $document_name = 'F24 - Return of allotment of shares')
			{
				$content = number_format($get_member_total_shares[0]["total_number_of_share"]);
			}
			else
			{
				$content = number_format($get_member_total_shares[0]["total_number_of_share"])."/-";
			}

			return $content;
		}
		elseif($string2 == "Company current name")
		{
			$get_client_name = $this->db->query("select company_name from transaction_client where company_code='".$company_code."' AND transaction_id='".$transaction_master_id."'");

			$get_client_name = $get_client_name->result_array();
			
			if(0 == count($get_client_name))
			{
				$get_client_name = $this->db->query("select company_name from client where company_code='".$company_code."' AND client.deleted != 1");

				$get_client_name = $get_client_name->result_array();
			}

			$content = $this->encryption->decrypt($get_client_name[0]["company_name"]);

			return $content;
		}
		elseif($string2 == "client_country_of_incorporation")
		{
			$get_client_name = $this->db->query("select client_country_of_incorporation from transaction_client where company_code='".$company_code."' AND transaction_id='".$transaction_master_id."'");

			$get_client_name = $get_client_name->result_array();

			$content = $get_client_name[0]["client_country_of_incorporation"];

			return $content;
		}
		else if($string2 == "transaction_client_activity1")
		{
			$get_client_activity1 = $this->db->query("select activity1 from transaction_client where company_code='".$company_code."' AND transaction_id='".$transaction_master_id."'");

			$get_client_activity1 = $get_client_activity1->result_array();

			$content = $get_client_activity1[0]["activity1"];

			return $content;
		}
		elseif($string2 == "Firm name" || $string2 == "Firm address" || $string2 == "Firm address letter"  || $string2 == "Firm UEN")
		{
			$query = $this->db->query("select firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax from firm 
									JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
									JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
									JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
									where firm.id = '".$this->session->userdata("firm_id")."'");

			$query = $query->result_array();

			if($string2 == "Firm name")
			{
				$content = $query[0]["name"];
			}
			elseif($string2 == "Firm address" || $string2 == "Firm address letter")
			{
				$firm_address = array(
					'type' 			=> 'Local',
					'street_name1' 	=> strtoupper($query[0]["street_name"]),
					'unit_no1'		=> $query[0]["unit_no1"],
					'unit_no2'		=> $query[0]["unit_no2"],
					'building_name1'=> strtoupper($query[0]["building_name"]),
					'postal_code1'	=> $query[0]["postal_code"]
				);
				
				if($string2 == "Firm address letter")
				{
					$content = $this->write_address_local_foreign($firm_address, "letter", "big_cap");
				}
				else
				{	
					$content = $this->write_address_local_foreign($firm_address, "comma", "big_cap");
				}
			}
			elseif($string2 == "Firm UEN")
			{
				$content = $query[0]["registration_no"];
			}

			return $content;
		}
		elseif($string2 == "Address - new")
		{
			if($document_name == "Allotment-Share Cert" || $document_name == "Transferee-Share Cert" || $document_name == "Dividend voucher" || $document_name == "01 Letter of Authorisation" || $document_name == "02 Letter to IRAS for Striking Off" || $document_name == "04 DRIW-Strike-Off & EGM - Shareholder" || $document_name == "04 Strike-Off-Notice Of EGM" || $document_name == "04 Strike-Off-Minutes Of EGM" || $document_name == "04 Strike-Off-Attendance List" || $document_name == "DRIW - Appt of Co Sec" || $document_name == "Declaration directors' interest S165 &156" || $document_name == "Nominee Director Agreement")
			{
				$get_transaction_master = $this->db->query("select transaction_task_id from transaction_master where id = '".$transaction_master_id."'");

				$get_transaction_master = $get_transaction_master->result_array();

				if($get_transaction_master[0]["transaction_task_id"] == 1)
				{
					$get_client_address = $this->db->query("select postal_code, building_name, street_name, unit_no1, unit_no2 from transaction_client where company_code='".$company_code."' AND transaction_id='".$transaction_master_id."'");
				}
				else
				{
					$get_client_address = $this->db->query("select postal_code, building_name, street_name, unit_no1, unit_no2 from client where company_code='".$company_code."' AND client.deleted != 1");
				}

				$get_client_address = $get_client_address->result_array();

				$client_unit_no1 = $get_client_address[0]["unit_no1"];
				$client_unit_no2 = $get_client_address[0]["unit_no2"];
				$client_street_name = $get_client_address[0]["street_name"];
				$client_building_name = $get_client_address[0]["building_name"];
				$client_postal_code = $get_client_address[0]["postal_code"];

				$address = array(
					'type' 			=> "Local",
					'street_name1' 	=> strtoupper($client_street_name),
					'unit_no1'		=> strtoupper($client_unit_no1),
					'unit_no2'		=> strtoupper($client_unit_no2),
					'building_name1'=> strtoupper($client_building_name),
					'postal_code1'	=> strtoupper($client_postal_code)
				);

				if($document_name == "Dividend voucher" || $document_name == "01 Letter of Authorisation" || $document_name == "Declaration directors' interest S165 &156" || $document_name == "Nominee Director Agreement"){
					$content = $this->write_address_local_foreign($address, "letter", "big_cap");
				}else{
					$content = $this->write_address_local_foreign($address, "comma", "big_cap");
				}
			}
			else if($document_name == "DRIW-Change of Reg Ofis")
			{
				$get_client_address = $this->db->query("select transaction_change_regis_ofis_address.postal_code, transaction_change_regis_ofis_address.building_name, transaction_change_regis_ofis_address.street_name, transaction_change_regis_ofis_address.unit_no1, transaction_change_regis_ofis_address.unit_no2 from transaction_change_regis_ofis_address left join transaction_master on transaction_master.id = transaction_change_regis_ofis_address.transaction_id where transaction_master.company_code='".$company_code."' AND transaction_change_regis_ofis_address.transaction_id='".$transaction_master_id."'");

				$get_client_address = $get_client_address->result_array();

				$address = array(
					'type' 			=> "Local",
					'street_name1' 	=> strtoupper($get_client_address[0]["street_name"]),
					'unit_no1'		=> strtoupper($get_client_address[0]["unit_no1"]),
					'unit_no2'		=> strtoupper($get_client_address[0]["unit_no2"]),
					'building_name1'=> strtoupper($get_client_address[0]["building_name"]),
					'postal_code1'	=> strtoupper($get_client_address[0]["postal_code"])
				);

				$content = $this->write_address_local_foreign($address, "comma", "big_cap");
			}
			else if($document_name == "DRIW-Appt and Resign of Director" || $document_name == "Ltr - Resignation of Director" || $document_name == "DRIW-Appt of Auditor" || $document_name == "Auditor-Shorter notice of EGM" || $document_name == "Auditor-Notice of EGM" || $document_name == "Auditor-Minutes of EGM" || $document_name == "Auditor-Attendance List" || $document_name == "Company Name-Notice of EGM" || $document_name == "Company Name-Attendance List" || $document_name == "Company Name-Minutes of EGM" || $document_name == "Company Name-Shorter notice of EGM" || $document_name == "Allotment-Minutes of EGM" || $document_name == "Allotment-Authority to Allot" || $document_name == "Allotment-Authority to EGM" || $document_name == "Allotment-Attendance List" || $document_name == "Allotment-Shorter notice of EGM" || $document_name == "Allotment-Share Application Form" || $document_name == "Ltrs Transfer of Shares" || $document_name == "AGM & AR - DRIW" || $document_name == "AGM & AR - Notice for AGM" || $document_name == "AGM & AR - Minutes of AGM" || $document_name == "AGM & AR - Attendance List" || $document_name == "DRIW-Change Biz Activity" || $document_name == "DRIW-Add Biz Activity" || $document_name == "DRIW-Incorp of subsidiary" || $document_name == "Subsidiary-Cert of Appt Company Representative" || $document_name == "DRIW-Issue Director Fee & EGM" || $document_name == "Director Fee-Notice of EGM" || $document_name == "Director Fee-Minutes of EGM" || $document_name == "Director Fee-Attendance List" || $document_name == "DRIW-Dividends" || $document_name == "Dividends-Notice Of EGM" || $document_name == "Dividends-Minutes Of EGM" || $document_name == "Dividends-Attendance List" || $document_name == "Proxy form")
			{
				$get_client_address = $this->db->query("select postal_code, building_name, street_name, unit_no1, unit_no2 from client where company_code='".$company_code."' AND client.deleted != 1");

				$get_client_address = $get_client_address->result_array();

				$client_unit_no1 = $get_client_address[0]["unit_no1"];
				$client_unit_no2 = $get_client_address[0]["unit_no2"];
				$client_street_name = $get_client_address[0]["street_name"];
				$client_building_name = $get_client_address[0]["building_name"];
				$client_postal_code = $get_client_address[0]["postal_code"];

				$address = array(
					'type' 			=> "Local",
					'street_name1' 	=> strtoupper($client_street_name),
					'unit_no1'		=> strtoupper($client_unit_no1),
					'unit_no2'		=> strtoupper($client_unit_no2),
					'building_name1'=> strtoupper($client_building_name),
					'postal_code1'	=> strtoupper($client_postal_code)
				);

				if($document_name == "DRIW-Appt and Resign of Director" || $document_name == "Ltr - Resignation of Director" || $document_name == "Allotment-Share Application Form" || $document_name == "Ltrs Transfer of Shares")
				{	
					$content = $this->write_address_local_foreign($address, "letter", "big_cap");
				}
				elseif($document_name == "DRIW-Dividends")
				{
					$content = ucwords(strtolower($this->write_address($get_client_address[0]["street_name"], $get_client_address[0]["unit_no1"], $get_client_address[0]["unit_no2"], $get_client_address[0]["building_name"], $$get_client_address[0]["postal_code"], "comma")));
				}
				else
				{
					$content = $this->write_address_local_foreign($address, "comma", "big_cap");
				}

				if($document_name == "AGM & AR - Notice for AGM" || $document_name == "AGM & AR - DRIW")
				{
					$content = ucwords(strtolower($content));
				}
				
			}
			else
			{
				$get_client_address = $this->db->query("select postal_code, building_name, street_name, unit_no1, unit_no2 from transaction_client where company_code='".$company_code."' AND transaction_id='".$transaction_master_id."'");

				if($document_name == "Letter of Authorisation")
				{
					if($get_client_address->num_rows() == 0)
					{
						$get_client_address = $this->db->query("select postal_code, building_name, street_name, unit_no1, unit_no2 from client where company_code='".$company_code."'");
					}
				}

				$get_client_address = $get_client_address->result_array();

				$client_unit_no1 = $get_client_address[0]["unit_no1"];
				$client_unit_no2 = $get_client_address[0]["unit_no2"];
				$client_street_name = $get_client_address[0]["street_name"];
				$client_building_name = $get_client_address[0]["building_name"];
				$client_postal_code = $get_client_address[0]["postal_code"];

				$address = array(
					'type' 			=> "Local",
					'street_name1' 	=> strtoupper($client_street_name),
					'unit_no1'		=> strtoupper($client_unit_no1),
					'unit_no2'		=> strtoupper($client_unit_no2),
					'building_name1'=> strtoupper($client_building_name),
					'postal_code1'	=> strtoupper($client_postal_code)
				);

				if($document_name == "Letter of Authorisation" || $document_name == "Letter of Appointment" || $document_name == "Ltr - Resignation of Director" || $document_name == "CSS Proposal" || $document_name == "Letter taking over of Secretarial Services")
				{
					$content = $this->write_address_local_foreign($address, "letter", "big_cap");

				}
				else
				{
					$content = $this->write_address_local_foreign($address, "comma", "big_cap");
				}//print_r($content);
			}

			return $content;
		}
		elseif($string2 == "Nominee Director Fee")
		{
			$q = $this->db->query("select transaction_client_billing_info.*, unit_pricing.unit_pricing_name, currency.currency as currency_name from transaction_client_billing_info left join our_service_info on our_service_info.id = transaction_client_billing_info.service left join unit_pricing on unit_pricing.id = transaction_client_billing_info.unit_pricing left join currency on currency.id = transaction_client_billing_info.currency where transaction_client_billing_info.company_code = '".$company_code."' AND transaction_client_billing_info.transaction_id = '".$transaction_master_id."' AND our_service_info.service_name = 'NOMINEE DIRECTOR'");

			if ($q->num_rows() > 0) {
				$q = $q->result_array();

				$content = $q[0]['currency_name'].number_format($q[0]['amount'],2)."/".$q[0]['unit_pricing_name'];
			}
			else
			{
				$content = "______________";
			}

			return $content;
		}
		elseif($string2 == "Nominee Director Deposit")
		{
			$q = $this->db->query("select transaction_client_billing_info.*, unit_pricing.unit_pricing_name, currency.currency as currency_name from transaction_client_billing_info left join our_service_info on our_service_info.id = transaction_client_billing_info.service left join unit_pricing on unit_pricing.id = transaction_client_billing_info.unit_pricing left join currency on currency.id = transaction_client_billing_info.currency where transaction_client_billing_info.company_code = '".$company_code."' AND transaction_client_billing_info.transaction_id = '".$transaction_master_id."' AND our_service_info.service_name = 'NOMINEE DIRECTOR (DEPOSIT)'");

			if ($q->num_rows() > 0) {
				$q = $q->result_array();

				$content = $q[0]['currency_name'].number_format($q[0]['amount'],2);
			}
			else
			{
				$content = "______________";
			}

			return $content;
		}
		elseif($string2 == "Firm Name")
		{
			$query = $this->db->query("select firm.* from firm where firm.id = '".$this->session->userdata("firm_id")."'");

			$query = $query->result_array();

			$content = $query[0]['name'];

			return $content;
		}
		elseif($string2 == "Firm Address")
		{
			$query = $this->db->query("select firm.* from firm where firm.id = '".$this->session->userdata("firm_id")."'");

			$query = $query->result_array();

			$unit_no1 = $query[0]["unit_no1"];
			$unit_no2 = $query[0]["unit_no2"];
			$street_name = $query[0]["street_name"];
			$building_name = $query[0]["building_name"];
			$postal_code = $query[0]["postal_code"];

			$address = array(
				'type' 			=> "Local",
				'street_name1' 	=> strtoupper($street_name),
				'unit_no1'		=> strtoupper($unit_no1),
				'unit_no2'		=> strtoupper($unit_no2),
				'building_name1'=> strtoupper($building_name),
				'postal_code1'	=> $postal_code
			);

			if($document_name == "DRIW-Appt of Co Sec (Take Over)" || $document_name == "Notice of Controller" || $document_name == "Financial Support Letter" || $document_name == "Declaration for register of controller")
			{
				$content = $this->write_address_local_foreign($address, "comma", "big_cap");
			}
			else
			{	
				$content = $this->write_address_local_foreign($address, "letter", "big_cap");
			}
			return $content;
		}
		elseif($string2 == "Firm Tel")
		{
			$query = $this->db->query("select firm.*, firm_telephone.telephone from firm left join firm_telephone on firm_telephone.firm_id = firm.id and firm_telephone.primary_telephone = 1 where firm.id = '".$this->session->userdata("firm_id")."'");

			$query = $query->result_array();

			if(count($query) > 0)
			{
				$content = $query[0]['telephone'];
			}
			else
			{
				$content = '';
			}
			return $content;
		}
		elseif($string2 == "Firm Fax")
		{
			$query = $this->db->query("select firm.*, firm_fax.fax from firm left join firm_fax on firm_fax.firm_id = firm.id and firm_fax.primary_fax = 1 where firm.id = '".$this->session->userdata("firm_id")."'");

			$query = $query->result_array();

			if(count($query) > 0)
			{
				$content = $query[0]['fax'];
			}
			else
			{
				$content = '';
			}
			return $content;
		}
		elseif($string2 == "Director Signature 1")
		{
			$content = '';

			if($document_name == "Allotment-Authority to Allot" || $document_name == "Allotment-Authority to EGM" || $document_name == "EGM-Appt of Auditor" || $document_name == "Auditor-Notice of EGM" || $document_name == "Dividends-Notice Of EGM" || $document_name == "Dividend voucher" || $document_name == "AGM & AR - Annual Return" || $document_name == "AGM & AR - Notice for AGM" || $document_name == "AGM & AR - DRIW" || $document_name == "Declaration For Controller" || $document_name == "F24 - Return of allotment of shares" || $document_name == "Director Fee-Notice of EGM" || $document_name == "DRIW-Issue Director Fee & EGM" || $document_name == "DRIW-Dividends" || $document_name == "Form 11" || $document_name == "DRIW-Change of name of company" || $document_name == "AGM & AR - Annual Return (Audit)" || $document_name == "AGM & AR - DRIW (Dormant)" || $document_name == "filing XBRL" || $document_name == "AR document" || $document_name == "AGM - DRIW" || $document_name == "AGM - DRIW (Dormant)" || $document_name == "Letter taking over of Secretarial Services" || $document_name == "Form 44" || $document_name == "02 DRIW-Strike Off")
			{
				$director_signature_1_result = $this->db->query("select director_signature_1 from client_signing_info where company_code='".$company_code."'");
			
				if ($director_signature_1_result->num_rows() > 0) {

                	$director_signature_1_result = $director_signature_1_result->result_array();

                	$client_officer = $this->db->query("select * from client_officers where id='".$director_signature_1_result[0]["director_signature_1"]."'");

            		$client_officer = $client_officer->result_array();

            		$officer_result = $this->db->query("select * from officer where id='".$client_officer[0]["officer_id"]."' AND field_type='".$client_officer[0]["field_type"]."'");

            		$officer_result = $officer_result->result_array();

            		$name = $this->encryption->decrypt($officer_result[0]["name"]);
                }
                else
                {
                	if($document_name == "F24 - Return of allotment of shares" || $document_name == "Form 44")
                	{
	                	$director_signature_1_result = $this->db->query("select director_signature_1 from transaction_client_signing_info where company_code='".$company_code."'");
					
						if ($director_signature_1_result->num_rows() > 0) {

		                	$director_signature_1_result = $director_signature_1_result->result_array();

		                	$client_officer = $this->db->query("select * from transaction_client_officers where id='".$director_signature_1_result[0]["director_signature_1"]."'");

		            		$client_officer = $client_officer->result_array();

		            		$officer_result = $this->db->query("select * from officer where id='".$client_officer[0]["officer_id"]."' AND field_type='".$client_officer[0]["field_type"]."'");

		            		$officer_result = $officer_result->result_array();

		            		$name = $this->encryption->decrypt($officer_result[0]["name"]);
		                }
		            }
		            else
		            {
		            	$name= "";
		            }
                }

                $content = $name;
            }
			elseif($document_name == "Letter of Authorisation" || $document_name == "First Director Resolutions (Many)"  || $document_name == "First Director Resolutions (One)")
			{
				$director_signature_1_result = $this->db->query("select director_signature_1 from transaction_client_signing_info where company_code='".$company_code."'");
			
				if ($director_signature_1_result->num_rows() > 0) {

                	$director_signature_1_result = $director_signature_1_result->result_array();

                	$client_officer = $this->db->query("select * from transaction_client_officers where id='".$director_signature_1_result[0]["director_signature_1"]."'");

            		$client_officer = $client_officer->result_array();

            		$officer_result = $this->db->query("select * from officer where id='".$client_officer[0]["officer_id"]."' AND field_type='".$client_officer[0]["field_type"]."'");

            		$officer_result = $officer_result->result_array();

            		$name = $this->encryption->decrypt($officer_result[0]["name"]);
                }

                $content = $name;
            }

            return $content;
		}
		elseif($string2 == "Secretarys name - appointment" || $string2 == "Secretarys name - resignation")
		{
			if($document_name == "First Director Resolutions (One)" || $document_name == "First Director Resolutions (Many)")
			{
				$get_secretarys = $this->db->query("select officer.name, officer.identification_no from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position=4");

				$get_secretarys = $get_secretarys->result_array();

				for($j = 0; $j < count($get_secretarys); $j++)
				{
					if($j == 0)
					{
						if($document_category_id == 2)
						{
							$secretary_name = $this->encryption->decrypt($get_secretarys[$j]["name"]);
						}
						else
						{
							$secretary_name = $this->encryption->decrypt($get_secretarys[$j]["name"])." (Identification No: ".$this->encryption->decrypt($get_secretarys[$j]["identification_no"]).")";
						}
					}
					elseif($j == (count($get_secretarys)-1))
					{
						if($document_category_id == 2)
						{
							$secretary_name = $secretary_name." and ".$this->encryption->decrypt($get_secretarys[$j]["name"]);
						}
						else
						{
							$secretary_name = $secretary_name." and ".$this->encryption->decrypt($get_secretarys[$j]["name"])." (Identification No: ".$this->encryption->decrypt($get_secretarys[$j]["identification_no"]).")";
						}
					}
					else
					{
						if($document_category_id == 2)
						{
							$secretary_name = $secretary_name.", ".$this->encryption->decrypt($get_secretarys[$j]["name"]);
						}
						else
						{
							$secretary_name = $secretary_name.", ".$this->encryption->decrypt($get_secretarys[$j]["name"])." (Identification No: ".$this->encryption->decrypt($get_secretarys[$j]["identification_no"]).")";
						}
					}
				}
				$content = $secretary_name;
			}
			else if($document_name == "DRIW - Resignation of Co Sec")
			{
				$get_resign_secretary_info = $this->db->query("select transaction_client_officers.*, officer.name, transaction_resign_officer_reason.reason_selected from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type left join transaction_resign_officer_reason on transaction_resign_officer_reason.transaction_client_officers_id = transaction_client_officers.id where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 4 AND transaction_client_officers.appoint_resign_flag = 'resign'");

				$get_resign_secretary_info = $get_resign_secretary_info->result_array();

				if(count($get_resign_secretary_info) > 0)
				{
					for($j = 0; $j < count($get_resign_secretary_info); $j++)
					{
						if($get_resign_secretary_info[$j]["reason_selected"] != NULL)
						{
							if($j == 0)
							{
								$secretary_name = $this->encryption->decrypt($get_resign_secretary_info[$j]["name"]);
							}
							elseif($j == (count($get_resign_secretary_info)-1))
							{
								$secretary_name = $secretary_name." and ".$this->encryption->decrypt($get_resign_secretary_info[$j]["name"]);
							}
							else
							{
								$secretary_name = $secretary_name.", ".$this->encryption->decrypt($get_resign_secretary_info[$j]["name"]);
							}
						}
					}

					$content = $secretary_name;
				}
				else
				{
					$content = "";
				}
			}
			else
			{
				$get_secretarys = $this->db->query("select officer.name, officer.identification_no from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position=4 AND transaction_client_officers.id='".$id."'");

				$get_secretarys = $get_secretarys->result_array();

				$content = $this->encryption->decrypt($get_secretarys[0]["name"]);
			}

			return $content;
		}
		elseif($string2 == "Secretarys ID - appointment" || $string2 == "Secretarys ID - resignation")
		{
			$get_secretarys_ID = $this->db->query("select officer.identification_no from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position=4 AND transaction_client_officers.id='".$id."'");
			

			$get_secretarys_ID = $get_secretarys_ID->result_array();

			$content = $this->encryption->decrypt($get_secretarys_ID[0]["identification_no"]);

			return $content;
		}
		elseif($string2 == "Secretarys address - appointment" || $string2 == "Secretarys address - resignation")
		{
			$secretary_address_type = 'Local';
			$foreign_address1 = "";
			$foreign_address2 = "";
			$foreign_address3 = "";

			$get_secretarys_address = $this->db->query("SELECT officer.* from transaction_client_officers LEFT JOIN officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type WHERE transaction_client_officers.position=4 AND transaction_client_officers.id=".$id);

			$get_secretarys_address = $get_secretarys_address->result_array();

			if($get_secretarys_address[0]["alternate_address"] == "1")
			{
				$secretary_unit_no1 = $get_secretarys_address[0]["unit_no3"];
				$secretary_unit_no2 = $get_secretarys_address[0]["unit_no4"];
				$secretary_street_name = $get_secretarys_address[0]["street_name2"];
				$secretary_building_name = $get_secretarys_address[0]["building_name2"];
				$secretary_postal_code = $get_secretarys_address[0]["postal_code2"];
			}
			else
			{
				$secretary_address_type = $get_secretarys_address[0]['address_type'];
				$secretary_unit_no1 = $get_secretarys_address[0]["unit_no1"];
				$secretary_unit_no2 = $get_secretarys_address[0]["unit_no2"];
				$secretary_street_name = $get_secretarys_address[0]["street_name1"];
				$secretary_building_name = $get_secretarys_address[0]["building_name1"];
				$secretary_postal_code = $get_secretarys_address[0]["postal_code1"];
				$foreign_address1 = $get_secretarys_address[0]["foreign_address1"];
				$foreign_address2 = $get_secretarys_address[0]["foreign_address2"];
				$foreign_address3 = $get_secretarys_address[0]["foreign_address3"];
			}
			
			$address = array(
				'type' 			=> $secretary_address_type,
				'street_name1' 	=> strtoupper($secretary_street_name),
				'unit_no1'		=> strtoupper($secretary_unit_no1),
				'unit_no2'		=> strtoupper($secretary_unit_no2),
				'building_name1'=> strtoupper($secretary_building_name),
				'postal_code1'	=> strtoupper($secretary_postal_code),
				'foreign_address1' => strtoupper($foreign_address1),
				'foreign_address2' => strtoupper($foreign_address2),
				'foreign_address3' => strtoupper($foreign_address3)
			);

			if($document_name == "Ltr of Indemnity" || $document_name == "Ltr - Resignation of Co Sec" || $document_name == "Form 49")
			{
				$content = $this->write_address_local_foreign($address, "letter", "big_cap");
			}
			else
			{
				$content = $this->write_address_local_foreign($address, "comma", "big_cap");
			}
			
			return $content;
		}
		elseif($string2 == "Secretarys nationality - appointment")
		{
			$get_secretarys_nationality = $this->db->query("select nationality.nationality as nationality_name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type left join nationality on nationality.id = officer.nationality where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position=4 AND transaction_client_officers.id='".$id."'");

			$get_secretarys_nationality = $get_secretarys_nationality->result_array();

			$content = $get_secretarys_nationality[0]["nationality_name"];

			return $content;
		}
		elseif($string2 == "Chairman" || $string2 == "MINUTESPOSITION")
		{
			if($document_name == "Auditor-Minutes of EGM" || $document_name == "EGM-Appt of Auditor" || $document_name == "Company Name-Minutes of EGM" || $document_name == "Allotment-Minutes of EGM" || $document_name == "Dividends-Minutes Of EGM" || $document_name == "Director Fee-Minutes of EGM" || $document_name == "DRIW-Issue Director Fee & EGM" || $document_name == "04 Strike-Off-Minutes Of EGM" || $document_name == "Allotment-Authority to EGM" || $document_name == "DRIW-Dividends" || $document_name == "DRIW-Change of name of company" || $document_name == "02 DRIW-Strike Off")
			{
				$chairman_result = $this->db->query("select chairman from client_signing_info where company_code='".$company_code."'");
			}
			elseif($document_name == "AGM & AR - Minutes of AGM" || $document_name == "AGM & AR - DRIW" || $document_name == "AGM & AR - DRIW (Dormant)" || $document_name == "AGM - DRIW" || $document_name == "AGM - DRIW (Dormant)")
			{
				$chairman_result = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');
			}
			else
			{
				$chairman_result = $this->db->query("select chairman from transaction_client_signing_info where company_code='".$company_code."' AND transaction_id='".$transaction_master_id."'");
			}
        	
        	if ($chairman_result->num_rows() > 0) {

            	$chairman_result = $chairman_result->result_array();

            	$chairman_result_info = (explode("-",$chairman_result[0]["chairman"]));

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

            		if($string2 == "Chairman")
            		{
            			$name = $officer_company_result[0]["name_of_corp_rep"];
            		}
            		else if($string2 == "MINUTESPOSITION")
            		{	
            			$name = "";

            			$r = $this->db->query("select * from officer_company");

                        if ($r->num_rows() > 0) 
                        {
                            $officer_company_info = $r->result_array();

                            foreach ($officer_company_info as $officer_company_info_row) {
                                if(stripos($this->encryption->decrypt($officer_company_info_row["register_no"]), $officer_company_result[0]["registration_no"]) !== FALSE)
                                {
                                    $name = $this->encryption->decrypt($officer_company_info_row["company_name"]);
                                    break;
                                }
                            }
                        }

                        if($name == "")
                        {
                        	$client_q = $this->db->query("select * from client");

                        	if ($client_q->num_rows() > 0) 
                        	{
                        		$client_info = $client_q->result_array();
                        		foreach ($client_info as $client_info_row) {
                        			if(stripos($this->encryption->decrypt($client_info_row["registration_no"]), $officer_company_result[0]["registration_no"]) !== FALSE)
                                	{
                                		$name = $this->encryption->decrypt($client_info_row["company_name"]);
                                		break;
                                	}
                        		}
                        	}
                        }
            		}
            	}

				$content = $name;
			}

			return $content;
		}
		elseif($string2 == "Next Year end")
		{
			if($document_name == "DRIW-Change of FYE")
			{
				$get_transaction_change_fye_info = $this->db->query("SELECT * FROM transaction_change_fye WHERE transaction_id=". $transaction_master_id);

				$get_transaction_change_fye_info = $get_transaction_change_fye_info->result_array();
				
				if(count($get_transaction_change_fye_info) > 0)
				{
					if(!empty($get_transaction_change_fye_info[0]["new_year_end"]))
					{
						$original_fye_date = $get_transaction_change_fye_info[0]["new_year_end"];

				        $dm = date('d F', strtotime($original_fye_date));

				        if($dm == "28 February")
				        {
				            $fye_date = date('d F Y', strtotime('+1 year', strtotime($original_fye_date)));

				            $dt = new DateTime($fye_date);

				            $dt->modify( 'first day of next month' );
				            $dt->modify('+' . (min($day, $dt->format('t')) - 1) . ' days');

				            $fye_dfy = $dt->format('d F Y');
				            $fye_ymd = $dt->format('Y-m-d');
				        }
				        else if($dm == "29 February")
				        {
				            $fye_date = date('d F Y', strtotime('+1 year', strtotime($original_fye_date)));

				            $dt = new DateTime($fye_date);

				            //$dt->modify( 'first day of next month' );
				            $dt->modify('+' . (min($day, $dt->format('t')) - 1) . ' days');

				            $fye_dfy = $dt->format('d F Y');
				            $fye_ymd = $dt->format('Y-m-d');
				        }
				        else
				        {
				        	$fye_dfy = date('d F Y', strtotime('+1 year', strtotime($original_fye_date)));
				            $fye_ymd = date('Y-m-d', strtotime('+1 year', strtotime($original_fye_date)));
				        }

				        $content = $fye_dfy;
					}
				}
			}

			return $content;
		}
		elseif($string2 == "Year end new" || $string2 == "Year end new(big_cap)")
		{	
			if($document_name == "filing XBRL")
			{
				$content = "";
			}
			else
			{
				$content = "___________________";
			}

			if($document_name == "AGM & AR - DRIW" || $document_name == "AGM & AR - Minutes of AGM" || $document_name == "AGM & AR - Notice for AGM" || $document_name == "AGM & AR - DRIW (Dormant)" || $document_name == "filing XBRL" || $document_name == "AGM Voting" || $document_name == "AGM - DRIW" || $document_name == "AGM - DRIW (Dormant)")
			{
				$agm_year_end = $this->db->query('select transaction_agm_ar.*, client.incorporation_date from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id left join client on client.company_code = transaction_master.company_code where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

				$agm_year_end = $agm_year_end->result_array();

				if(count($agm_year_end) > 0)
				{
					if($string2 == "Year end new")
					{
						if($document_name == "AGM - DRIW" || $document_name == "AGM - DRIW (Dormant)" || $document_name == "AGM Voting")
						{
							if($agm_year_end[0]["is_first_agm_id"] == 1)
							{
								$incorporation_date = date('d F Y', strtotime(str_replace('/', '-', $agm_year_end[0]["incorporation_date"])));
								$content = "financial year from " . $incorporation_date ." (Date of Incorporation) to ". $agm_year_end[0]["year_end_date"];
							}
							elseif($agm_year_end[0]["is_first_agm_id"] == 2)
							{
								$check_change_year_end = $this->db->query('select * from filing where filing.company_code="'.$company_code.'" AND filing.year_end="'.$agm_year_end[0]["year_end_date"].'"');
								$check_change_year_end = $check_change_year_end->result_array();

								if($check_change_year_end[0]["change_info"] == 1)
								{
									$content = "financial year from " . $check_change_year_end[0]["financial_year_period1"] ." to ". $check_change_year_end[0]["financial_year_period2"];
								}
								else
								{
									$content = "financial year ended ". $agm_year_end[0]["year_end_date"];
								}
							}
						}
						else
						{
							$content = $agm_year_end[0]["year_end_date"];
						}
					}
					elseif($string2 == "Year end new(big_cap)")
					{
						if($document_name == "AGM - DRIW" || $document_name == "AGM - DRIW (Dormant)" || $document_name == "AGM Voting")
						{
							if($agm_year_end[0]["is_first_agm_id"] == 1)
							{
								$incorporation_date = date('d F Y', strtotime(str_replace('/', '-', $agm_year_end[0]["incorporation_date"])));
								$content = strtoupper("financial period from " . $incorporation_date ." (Date of Incorporation) to ". $agm_year_end[0]["year_end_date"]);
							}
							elseif($agm_year_end[0]["is_first_agm_id"] == 2)
							{
								$check_change_year_end = $this->db->query('select * from filing where filing.company_code="'.$company_code.'" AND filing.year_end="'.$agm_year_end[0]["year_end_date"].'"');
								$check_change_year_end = $check_change_year_end->result_array();

								if($check_change_year_end[0]["change_info"] == 1)
								{
									$content = strtoupper("financial year from " . $check_change_year_end[0]["financial_year_period1"] ." to ". $check_change_year_end[0]["financial_year_period2"]);
								}
								else
								{
									$content = strtoupper("financial year ended ". $agm_year_end[0]["year_end_date"]);
								}
							}
						}
						else
						{
							$year_end_date = strtoupper($agm_year_end[0]["year_end_date"]);
							$content = $year_end_date;
						}
					}
				}
			}
			else if($document_name == "DRIW-Change of FYE")
			{
				$get_transaction_change_fye_info = $this->db->query("SELECT * FROM transaction_change_fye WHERE transaction_id=". $transaction_master_id);

				$get_transaction_change_fye_info = $get_transaction_change_fye_info->result_array();

				if(count($get_transaction_change_fye_info) > 0)
				{
					if(!empty($get_transaction_change_fye_info[0]["new_year_end"]))
					{
						$content = $get_transaction_change_fye_info[0]["new_year_end"];
					}
				}
			}
			else
			{
				$get_year_end = $this->db->query('select year_end from transaction_filing where company_code="'.$company_code.'" AND transaction_id="'.$transaction_master_id.'"');

				$get_year_end = $get_year_end->result_array();

				if(count($get_year_end) > 0)
				{
					$content = $get_year_end[0]["year_end"];
				}
			}
			return $content;
		}
		elseif($string2 == "Year end new (No Year)")
		{
			$content = "___________________";

			if($document_name == "DRIW-Change of FYE")
			{
				$get_transaction_change_fye_info = $this->db->query("SELECT * FROM transaction_change_fye WHERE transaction_id=". $transaction_master_id);

				$get_transaction_change_fye_info = $get_transaction_change_fye_info->result_array();

				if(count($get_transaction_change_fye_info) > 0)
				{
					if(!empty($get_transaction_change_fye_info[0]["new_year_end"]))
					{
						$content = date('d F', strtotime($get_transaction_change_fye_info[0]["new_year_end"]));
					}
				}
			}
			else
			{
				$get_year_end = $this->db->query('select year_end from transaction_filing where company_code="'.$company_code.'" AND transaction_id="'.$transaction_master_id.'"');

				$get_year_end = $get_year_end->result_array();

				if(count($get_year_end) > 0)
				{
					$date = explode(' ', $get_year_end[0]["year_end"]);
					$day = $date[0];
					$month   = $date[1];
					$year  = $date[2];
					$content = $day.' '.$month;
				}
				
			}
			return $content;
		}
		elseif($string2 == "Members name - all")
		{
			if($document_name == "AGM & AR - DRIW" || $document_name == "DRIW-Dividends" || $document_name == "DRIW-Issue Director Fee & EGM" || $document_name == "EGM-Appt of Auditor" || $document_name == "Auditor-Shorter notice of EGM" || $document_name == "DRIW-Change of name of company" || $document_name = "Company Name-Shorter notice of EGM" || $document_name == "Proxy form" || $document_name == "AGM - DRIW" || $document_name == "AGM - DRIW (Dormant)")
			{
				$get_member_info = $this->db->query('select member_shares.*, z.company_name as tr_client_company_name, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency, y.registration_no as client_registration_no, y.company_name as client_company_name from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client as y on y.id = member_shares.officer_id and member_shares.field_type = "client" AND y.deleted <> 1 left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client as z on z.company_code = member_shares.company_code where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id HAVING sum(member_shares.number_of_share) != 0');
			}
			else
			{
				$get_member_info = $this->db->query('select transaction_member_shares.*, transaction_client.company_name as tr_client_company_name, sum(transaction_member_shares.number_of_share) as number_of_share, sum(transaction_member_shares.amount_share) as amount_share, sum(transaction_member_shares.no_of_share_paid) as no_of_share_paid, sum(transaction_member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency, client.registration_no as client_registration_no, client.company_name as client_company_name from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id and transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id and transaction_member_shares.field_type = officer_company.field_type left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = "client" AND client.deleted != 1 left join client_member_share_capital as share_capital on transaction_member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join transaction_client on transaction_client.company_code = transaction_member_shares.company_code  where transaction_member_shares.company_code="'.$company_code.'" AND transaction_page_id="'.$transaction_master_id.'" GROUP BY transaction_member_shares.field_type, transaction_member_shares.officer_id HAVING sum(transaction_member_shares.number_of_share) != 0');
			}

			$get_member_info = $get_member_info->result_array();

			return $get_member_info;
		}
		elseif($string2 == "get_transaction_member_list")
		{
			$get_member_info = $this->db->query("select transaction_member_shares.*, z.company_name as tr_client_company_name, officer.name, officer_company.company_name, officer_company.register_no, client.company_name as client_company_name, client.registration_no, transaction_certificate.new_certificate_no from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id AND transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id AND transaction_member_shares.field_type = officer_company.field_type left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = 'client' AND client.deleted != 1 left join client as z on z.company_code = transaction_member_shares.company_code right join transaction_certificate on transaction_certificate.officer_id = transaction_member_shares.officer_id and transaction_certificate.field_type = transaction_member_shares.field_type and transaction_certificate.transaction_id = transaction_member_shares.transaction_id and transaction_certificate.client_member_share_capital_id = transaction_member_shares.client_member_share_capital_id where transaction_member_shares.company_code='".$company_code."' AND transaction_member_shares.transaction_page_id='".$transaction_master_id."' AND transaction_member_shares.number_of_share > 0");

			$get_member_info = $get_member_info->result_array();

			return $get_member_info;
		}
		elseif($string2 == "Title Position")
		{
			$title_position = $this->db->query("SELECT client_officers_position.position FROM transaction_client_officers LEFT JOIN client_officers_position ON client_officers_position.id = transaction_client_officers.position WHERE transaction_client_officers.company_code='".$company_code."' AND (transaction_client_officers.position = 1 OR transaction_client_officers.position = 3 OR transaction_client_officers.position = 4 OR transaction_client_officers.position = 5) GROUP BY transaction_client_officers.position");

			if ($title_position->num_rows() > 0) {
	            // foreach ($title_position->result() as $row) {
	            //     $data[] = $row;
	            // }
	            return $title_position->result_array();
	        }
	        return FALSE;
		}
		elseif($string2 == "get_transaction_officers_director_list")
		{
			$get_directors = $this->db->query("select transaction_client_officers.id, officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_client_officers.date_of_cessation = '' AND transaction_client_officers.position = 1");

			if ($get_directors->num_rows() > 0) 
            {
            	foreach ($get_directors->result_array() as $row)
	            {
	                $row["name"] = $this->encryption->decrypt($row["name"]);
	                $data[] = $row;
	            }
	            return $data;
            }
            return FALSE;
		}
		elseif($string2 == "Directors name - appointment")
		{
			if($document_name == "Letter of Authorisation")
			{
				$get_directors = $this->db->query("select officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1");

				$get_directors = $get_directors->result_array();
			}
			else
			{
				$get_directors = $this->db->query("select officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND (transaction_client_officers.position = 1 OR transaction_client_officers.position = 8) AND transaction_client_officers.id='".$id."'");

				$get_directors = $get_directors->result_array();

				// print_r(array($this->encryption->decrypt($get_directors[0]["name"])));
			}

			$content = $this->encryption->decrypt($get_directors[0]["name"]);

			return $content;
		}
		elseif($string2 == "Directors name - all appointment")
		{
			$director = "";
			$content = '';

			$temp_document_name = $document_name;	// due to $document_name will change to 1 after if condition, therefore we use this to avoid the problem

			$director = "";

			$get_directors = $this->db->query("select officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1 AND transaction_client_officers.appoint_resign_flag='appoint'");

			$get_directors = $get_directors->result_array();

			for($i = 0; $i < count($get_directors); $i++)
			{	
				if($i == 0)
				{
					$director = $this->encryption->decrypt($get_directors[$i]["name"]);
				}
				else if($i == count($get_directors) - 1)
				{
					$director = $director . ' and ' . $this->encryption->decrypt($get_directors[$i]["name"]);
				}
				else
				{	
					$director = $director . ', ' . $this->encryption->decrypt($get_directors[$i]["name"]);
				}
			}
			$content = $director;
			return $content;
		}
		elseif($string2 == "Directors name - all resign")
		{
			$director = "";
			$content = '';

			$temp_document_name = $document_name;	// due to $document_name will change to 1 after if condition, therefore we use this to avoid the problem

			$director = "";

			$get_directors = $this->db->query("select officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1 AND transaction_client_officers.appoint_resign_flag='resign'");

			$get_directors = $get_directors->result_array();

			for($i = 0; $i < count($get_directors); $i++)
			{	
				if($i == 0)
				{
					$director = $this->encryption->decrypt($get_directors[$i]["name"]);
				}
				else if($i == count($get_directors) - 1)
				{
					$director = $director . ' and ' . $this->encryption->decrypt($get_directors[$i]["name"]);
				}
				else
				{	
					$director = $director . ', ' . $this->encryption->decrypt($get_directors[$i]["name"]);
				}
			}
			$content = $director;
			return $content;
		}
		elseif($string2 == "Directors ID - appointment")
		{
			$get_directors_ID = $this->db->query("select officer.identification_no from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND (transaction_client_officers.position = 1 OR transaction_client_officers.position = 8) AND transaction_client_officers.id='".$id."'");

			$get_directors_ID = $get_directors_ID->result_array();

			$content = $this->encryption->decrypt($get_directors_ID[0]["identification_no"]);

			return $content;
		}
		elseif($string2 == "Directors address - appointment")
		{
			$get_directors_address = $this->db->query("select officer.* from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND (transaction_client_officers.position = 1 OR transaction_client_officers.position = 8) AND transaction_client_officers.id='".$id."'");

			$get_directors_address = $get_directors_address->result_array();

			if($get_directors_address[0]["alternate_address"] == "1")
			{
				$director_unit_no1 = $get_directors_address[0]["unit_no3"];
				$director_unit_no2 = $get_directors_address[0]["unit_no4"];
				$director_street_name = $get_directors_address[0]["street_name2"];
				$director_building_name = $get_directors_address[0]["building_name2"];
				$director_postal_code = $get_directors_address[0]["postal_code2"];
			}
			else
			{
				$director_unit_no1 = $get_directors_address[0]["unit_no1"];
				$director_unit_no2 = $get_directors_address[0]["unit_no2"];
				$director_street_name = $get_directors_address[0]["street_name1"];
				$director_building_name = $get_directors_address[0]["building_name1"];
				$director_postal_code = $get_directors_address[0]["postal_code1"];
			}

			$address = array(
				'type' 			=> $get_directors_address[0]['address_type'],
				'street_name1' 	=> strtoupper($director_street_name),
				'unit_no1'		=> strtoupper($director_unit_no1),
				'unit_no2'		=> strtoupper($director_unit_no2),
				'building_name1'=> strtoupper($director_building_name),
				'postal_code1'	=> strtoupper($director_postal_code),
				'foreign_address1' => strtoupper($get_directors_address[0]["foreign_address1"]),
				'foreign_address2' => strtoupper($get_directors_address[0]["foreign_address2"]),
				'foreign_address3' => strtoupper($get_directors_address[0]["foreign_address3"])
			);

			if($document_name == "Form 49")
			{
				$address = $this->write_address_local_foreign($address, "letter", "big_cap");
			}
			else
			{
				$address = $this->write_address_local_foreign($address, "comma", "big_cap");
			}

			$content = $address;

			return $content;
		}
		elseif($string2 == "Directors nationality - appointment")
		{
			$get_directors_nationality = $this->db->query("select nationality.nationality as nationality_name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type left join nationality on nationality.id = officer.nationality where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND (transaction_client_officers.position = 1 OR transaction_client_officers.position = 8) AND transaction_client_officers.id='".$id."'");

			$get_directors_nationality = $get_directors_nationality->result_array();

			$content = $get_directors_nationality[0]["nationality_name"];

			return $content;
		}
		elseif($string2 == "Directors date of birth - appointment")
		{
			$get_directors_dob = $this->db->query("select date_of_birth from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND (transaction_client_officers.position = 1 OR transaction_client_officers.position = 8) AND transaction_client_officers.id='".$id."'");

			$get_directors_dob = $get_directors_dob->result_array();

			$content = $get_directors_dob[0]["date_of_birth"];

			return $content;
		}
		elseif($string2 == "Directors contact and email - appointment")
		{
			$get_directors_contact = $this->db->query("select officer_mobile_no.mobile_no, officer_email.email from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type left join officer_mobile_no on officer_mobile_no.officer_id = transaction_client_officers.id left join officer_email on officer_email.officer_id = transaction_client_officers.id where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND (transaction_client_officers.position = 1 OR transaction_client_officers.position = 8) AND transaction_client_officers.id='".$id."'");

			$get_directors_contact = $get_directors_contact->result_array();

			$director_mobile_no = $this->encryption->decrypt($get_directors_contact[0]["mobile_no"]);
			$director_email = $this->encryption->decrypt($get_directors_contact[0]["email"]);

			if($director_email != null)
			{
				$content = $director_mobile_no ."; ". $director_email;
			}
			else
			{
				$content = $director_mobile_no;
			}

			return $content;
		}
		elseif($string2 == "get_transaction_officers_manager_list")
		{
			$get_directors = $this->db->query("select transaction_client_officers.id, officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_client_officers.date_of_cessation = '' AND transaction_client_officers.position = 3");

			if ($get_directors->num_rows() > 0) 
            {
            	foreach ($get_directors->result_array() as $row)
	            {
	                $row["name"] = $this->encryption->decrypt($row["name"]);
	                $data[] = $row;
	            }
	            return $data;
            }
            return FALSE;
		}
		elseif($string2 == "Managers address - appointment")
		{
			$get_managers_address = $this->db->query("select officer.* from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 3 AND transaction_client_officers.id='".$id."'");

			$get_managers_address = $get_managers_address->result_array();

			if($get_managers_address[0]["alternate_address"] == "1")
			{
				$manager_unit_no1 = $get_managers_address[0]["unit_no3"];
				$manager_unit_no2 = $get_managers_address[0]["unit_no4"];
				$manager_street_name = $get_managers_address[0]["street_name2"];
				$manager_building_name = $get_managers_address[0]["building_name2"];
				$manager_postal_code = $get_managers_address[0]["postal_code2"];
			}
			else
			{
				$manager_unit_no1 = $get_managers_address[0]["unit_no1"];
				$manager_unit_no2 = $get_managers_address[0]["unit_no2"];
				$manager_street_name = $get_managers_address[0]["street_name1"];
				$manager_building_name = $get_managers_address[0]["building_name1"];
				$manager_postal_code = $get_managers_address[0]["postal_code1"];
			}

			$address = array(
				'type' 			=> $get_managers_address[0]['address_type'],
				'street_name1' 	=> strtoupper($manager_street_name),
				'unit_no1'		=> strtoupper($manager_unit_no1),
				'unit_no2'		=> strtoupper($manager_unit_no2),
				'building_name1'=> strtoupper($manager_building_name),
				'postal_code1'	=> strtoupper($manager_postal_code),
				'foreign_address1' => strtoupper($get_managers_address[0]["foreign_address1"]),
				'foreign_address2' => strtoupper($get_managers_address[0]["foreign_address2"]),
				'foreign_address3' => strtoupper($get_managers_address[0]["foreign_address3"])
			);

			if($document_name == "Form 49")
			{
				$address = $this->write_address_local_foreign($address, "letter", "big_cap");
			}
			else
			{
				$address = $this->write_address_local_foreign($address, "comma", "big_cap");
			}

			$content = $address;

			return $content;
		}
		elseif($string2 == "Managers name - appointment")
		{
			$get_managers = $this->db->query("select officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 3 AND transaction_client_officers.id='".$id."'");

			$get_managers = $get_managers->result_array();

			$content = $this->encryption->decrypt($get_managers[0]["name"]);

			return $content;
		}
		elseif($string2 == "Managers ID - appointment")
		{
			$get_managers_ID = $this->db->query("select officer.identification_no from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 3 AND transaction_client_officers.id='".$id."'");

			$get_managers_ID = $get_managers_ID->result_array();

			$content = $this->encryption->decrypt($get_managers_ID[0]["identification_no"]);

			return $content;
		}
		elseif($string2 == "Managers nationality - appointment")
		{
			$get_managers_nationality = $this->db->query("select nationality.nationality as nationality_name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type left join nationality on nationality.id = officer.nationality where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 3 AND transaction_client_officers.id='".$id."'");

			$get_managers_nationality = $get_managers_nationality->result_array();

			$content = $get_managers_nationality[0]["nationality_name"];

			return $content;
		}
		elseif($string2 == "get_transaction_officers_secretary_list")
		{
			$get_directors = $this->db->query("select transaction_client_officers.id, officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_client_officers.date_of_cessation = '' AND transaction_client_officers.position = 4");

			if ($get_directors->num_rows() > 0) 
            {
            	foreach ($get_directors->result_array() as $row)
	            {
	                $row["name"] = $this->encryption->decrypt($row["name"]);
	                $data[] = $row;
	            }
	            return $data;
            }
            return FALSE;
		}
		elseif($string2 == "Secretarys name - appointment")
		{
			$get_secretarys = $this->db->query("select officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 4 AND transaction_client_officers.id='".$id."'");

			$get_secretarys = $get_secretarys->result_array();

			$content = $this->encryption->decrypt($get_secretarys[0]["name"]);

			return $content;
		}
		elseif($string2 == "Secretarys ID - appointment")
		{
			$get_secretarys_ID = $this->db->query("select officer.identification_no from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 4 AND transaction_client_officers.id='".$id."'");

			$get_secretarys_ID = $get_secretarys_ID->result_array();

			$content = $this->encryption->decrypt($get_secretarys_ID[0]["identification_no"]);

			return $content;
		}
		elseif($string2 == "Secretarys nationality - appointment")
		{
			$get_secretarys_nationality = $this->db->query("select nationality.nationality as nationality_name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type left join nationality on nationality.id = officer.nationality where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 4 AND transaction_client_officers.id='".$id."'");

			$get_secretarys_nationality = $get_secretarys_nationality->result_array();

			$content = $get_secretarys_nationality[0]["nationality_name"];

			return $content;
		}
		elseif($string2 == "get_transaction_officers_auditor_list")
		{
			$get_directors = $this->db->query("select transaction_client_officers.id, officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_client_officers.date_of_cessation = '' AND transaction_client_officers.position = 5");

			if ($get_directors->num_rows() > 0) 
            {
            	foreach ($get_directors->result_array() as $row)
	            {
	                $row["name"] = $this->encryption->decrypt($row["name"]);
	                $data[] = $row;
	            }
	            return $data;
            }
            return FALSE;
		}
		elseif($string2 == "Auditors address - appointment")
		{
			$get_auditor_address = $this->db->query("select officer_company.* from transaction_client_officers left join officer_company on transaction_client_officers.officer_id = officer_company.id AND transaction_client_officers.field_type = officer_company.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 5 AND transaction_client_officers.id='".$id."'");

			$get_auditor_address = $get_auditor_address->result_array();

			// if($get_managers_address[0]["alternate_address"] == "1")
			// {
			// 	$manager_unit_no1 = $get_managers_address[0]["unit_no3"];
			// 	$manager_unit_no2 = $get_managers_address[0]["unit_no4"];
			// 	$manager_street_name = $get_managers_address[0]["street_name2"];
			// 	$manager_building_name = $get_managers_address[0]["building_name2"];
			// 	$manager_postal_code = $get_managers_address[0]["postal_code2"];
			// }
			// else
			// {
				$auditor_unit_no1 = $get_auditor_address[0]["company_unit_no1"];
				$auditor_unit_no2 = $get_auditor_address[0]["company_unit_no2"];
				$auditor_street_name = $get_auditor_address[0]["company_street_name"];
				$auditor_building_name = $get_auditor_address[0]["company_building_name"];
				$auditor_postal_code = $get_auditor_address[0]["company_postal_code"];
			//}

			$address = array(
				'type' 			=> $get_auditor_address[0]['address_type'],
				'street_name1' 	=> strtoupper($auditor_street_name),
				'unit_no1'		=> strtoupper($auditor_unit_no1),
				'unit_no2'		=> strtoupper($auditor_unit_no2),
				'building_name1'=> strtoupper($auditor_building_name),
				'postal_code1'	=> strtoupper($auditor_postal_code),
				'foreign_address1' => strtoupper($get_auditor_address[0]["company_foreign_address1"]),
				'foreign_address2' => strtoupper($get_auditor_address[0]["company_foreign_address2"]),
				'foreign_address3' => strtoupper($get_auditor_address[0]["company_foreign_address3"])
			);

			if($document_name == "Form 49")
			{
				$address = $this->write_address_local_foreign($address, "letter", "big_cap");
			}
			else
			{
				$address = $this->write_address_local_foreign($address, "comma", "big_cap");
			}

			$content = $address;

			return $content;
		}
		elseif($string2 == "Auditors name - appointment")
		{
			$get_auditors = $this->db->query("select officer_company.company_name from transaction_client_officers left join officer_company on transaction_client_officers.officer_id = officer_company.id AND transaction_client_officers.field_type = officer_company.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 5 AND transaction_client_officers.id='".$id."'");

			$get_auditors = $get_auditors->result_array();

			$content = $this->encryption->decrypt($get_auditors[0]["company_name"]);

			return $content;
		}
		elseif($string2 == "Auditors ID - appointment")
		{
			$get_auditors_ID = $this->db->query("select officer_company.register_no from transaction_client_officers left join officer_company on transaction_client_officers.officer_id = officer_company.id AND transaction_client_officers.field_type = officer_company.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 5 AND transaction_client_officers.id='".$id."'");

			$get_auditors_ID = $get_auditors_ID->result_array();

			$content = $this->encryption->decrypt($get_auditors_ID[0]["register_no"]);

			return $content;
		}
		elseif($string2 == "Auditors nationality - appointment")
		{
			$get_auditors_nationality = $this->db->query("select officer_company.country_of_incorporation from transaction_client_officers left join officer_company on transaction_client_officers.officer_id = officer_company.id AND transaction_client_officers.field_type = officer_company.field_type  where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 5 AND transaction_client_officers.id='".$id."'");

			$get_auditors_nationality = $get_auditors_nationality->result_array();

			$content = $get_auditors_nationality[0]["country_of_incorporation"];

			return $content;
		}
		elseif($string2 == "effective_date" || $string2 == "underline_effective_date" || $string2 == "empty_effective_date")
		{
			$get_master_info = $this->db->query("select lodgement_date from transaction_master where company_code='".$company_code."' AND id = '".$transaction_master_id."'");

			$get_master_info = $get_master_info->result_array();

			if(count($get_master_info) > 0)
			{
				if($get_master_info[0]["lodgement_date"] != "")
				{
					$content = $get_master_info[0]["lodgement_date"];
				}
				else
				{
					if($string2 == "underline_effective_date")
					{
						$content = '________________';
					}
					elseif($string2 == "empty_effective_date")
					{
						$content = '';
					}
					else
					{
						$content = '<w:t xml:space="preserve">                      </w:t>';
					}
				}
				
			}
			return $content;
		}
		elseif($string2 == "UEN" || $string2 == "underline_UEN" || $string2 == "empty_UEN")
		{
			$get_client_registration_no = $this->db->query("select registration_no from transaction_client where company_code='".$company_code."' AND transaction_id='".$transaction_master_id."'");

			$get_client_registration_no = $get_client_registration_no->result_array();

			if(0 == count($get_client_registration_no))
			{
				$get_client_registration_no = $this->db->query("select registration_no from client where company_code='".$company_code."' AND client.deleted != 1");

				$get_client_registration_no = $get_client_registration_no->result_array();
			}

			if($get_client_registration_no[0]["registration_no"] != null)
			{
				$content = $this->encryption->decrypt($get_client_registration_no[0]["registration_no"]);
			}
			else
			{
				$get_master_info = $this->db->query("select registration_no from transaction_master where company_code='".$company_code."' AND id = '".$transaction_master_id."'");

				$get_master_info = $get_master_info->result_array();

				if(count($get_master_info) > 0)
				{
					if($get_master_info[0]["registration_no"] != null)
					{
						$content = $this->encryption->decrypt($get_master_info[0]["registration_no"]);
					}
					else
					{
						if($string2 == "underline_UEN")
						{
							$content = '________________';
						}
						elseif($string2 == "empty_UEN")
						{
							$content = '';
						}
						else
						{
							$content = '<w:t xml:space="preserve">                      </w:t>';
						}
					}
				}
			}
			return $content;
		}
		elseif($string2 == "Incorporation date")
		{
			$get_client_incorporation_date = $this->db->query("select incorporation_date from client where company_code='".$company_code."' AND client.deleted != 1");

			$get_client_incorporation_date = $get_client_incorporation_date->result_array();

			if($document_name == "Form 45" || $document_name == "Form 45B")
			{
				$get_transaction_master = $this->db->query("select transaction_task_id from transaction_master where id = '".$transaction_master_id."'");

				$get_transaction_master = $get_transaction_master->result_array();

				if($get_transaction_master[0]["transaction_task_id"] == 1)
				{
					$content = 'date of incorporation';
				}
				else
				{
					$content = '';
				}
			}
			else
			{
				$content = $get_client_incorporation_date[0]["incorporation_date"];
			}
			return $content;
		}
		elseif($string2 == "Allotment - certificate")
		{
			$get_certificate = $this->db->query('select transaction_certificate.* from transaction_certificate left join transaction_member_shares on transaction_member_shares.id = "'.$id.'" where transaction_certificate.company_code="'.$company_code.'"AND transaction_member_shares.officer_id = transaction_certificate.officer_id AND transaction_member_shares.field_type = transaction_certificate.field_type AND transaction_certificate.transaction_page_id="'.$transaction_master_id.'"');

			$get_certificate = $get_certificate->result_array();

			$content = $get_certificate[0]["new_certificate_no"];

			return $content;
		}
		elseif($string2 == "Allotment - type of shares")
		{
			if($document_name == "DRIW-Allotment of Shares" || $document_name == "F24 - Return of allotment of shares")
			{
				$get_type_shares = $this->db->query('select transaction_member_shares.*, transaction_client_member_share_capital.class_id, transaction_client_member_share_capital.other_class, sharetype.sharetype as class_name from transaction_member_shares left join transaction_client_member_share_capital on transaction_client_member_share_capital.id = transaction_member_shares.client_member_share_capital_id left join sharetype on sharetype.id = transaction_client_member_share_capital.class_id where transaction_member_shares.company_code="'.$company_code.'" AND transaction_page_id="'.$transaction_master_id.'"');
			}
			else
			{
				$get_type_shares = $this->db->query('select transaction_member_shares.*, transaction_client_member_share_capital.class_id, transaction_client_member_share_capital.other_class, sharetype.sharetype as class_name from transaction_member_shares left join transaction_client_member_share_capital on transaction_client_member_share_capital.id = transaction_member_shares.client_member_share_capital_id left join sharetype on sharetype.id = transaction_client_member_share_capital.class_id where transaction_member_shares.company_code="'.$company_code.'" AND transaction_page_id="'.$transaction_master_id.'" AND transaction_member_shares.id = "'.$id.'"');
			}

			$get_type_shares = $get_type_shares->result_array();

			if($get_type_shares[0]["class_id"] == 2)
			{
				$content = $get_type_shares[0]["other_class"];
			}
			else
			{
				if($get_type_shares[0]["class_name"] == "Ordinary Share")
				{
					$content = "Ordinary Share(s)";
				}
				else
				{
					$content = $get_type_shares[0]["class_name"];
				}
				
			}
			return $content;
		}
		elseif($string2 == "Allotment - currency")
		{
			if($document_name == "DRIW-Allotment of Shares" || $document_name == "F24 - Return of allotment of shares")
			{
				$get_currency = $this->db->query('select transaction_member_shares.*, currency.currency as currency_name from transaction_member_shares left join transaction_client_member_share_capital on transaction_client_member_share_capital.id = transaction_member_shares.client_member_share_capital_id left join currency on currency.id = transaction_client_member_share_capital.currency_id where transaction_member_shares.company_code="'.$company_code.'" AND transaction_page_id="'.$transaction_master_id.'"');
			}
			else
			{
				$get_currency = $this->db->query('select transaction_member_shares.*, currency.currency as currency_name from transaction_member_shares left join transaction_client_member_share_capital on transaction_client_member_share_capital.id = transaction_member_shares.client_member_share_capital_id left join currency on currency.id = transaction_client_member_share_capital.currency_id where transaction_member_shares.company_code="'.$company_code.'" AND transaction_page_id="'.$transaction_master_id.'" AND transaction_member_shares.id = "'.$id.'"');
			}

			$get_currency = $get_currency->result_array();

			$content = $get_currency[0]["currency_name"];

			return $content;
		}
		elseif($string2 == "All Director info without signature")
		{
			$get_directors_info_with_address = $this->db->query("select officer.* from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1 AND transaction_client_officers.date_of_appointment =''");

			$content = $get_directors_info_with_address->result_array();

			return $content;
		}
		elseif($string2 == "Nominee Director Address Format")
		{
			$get_directors_info_with_address = $value;

			// if($get_directors_info_with_address["alternate_address"] == "1")
			// {
			// 	$director_unit_no1 = $get_directors_info_with_address["unit_no3"];
			// 	$director_unit_no2 = $get_directors_info_with_address["unit_no4"];
			// 	$director_street_name = $get_directors_info_with_address["street_name2"];
			// 	$director_building_name = $get_directors_info_with_address["building_name2"];
			// 	$director_postal_code = $get_directors_info_with_address["postal_code2"];
			// }
			// else
			// {
				$director_unit_no1 = $get_directors_info_with_address["unit_no1"];
				$director_unit_no2 = $get_directors_info_with_address["unit_no2"];
				$director_street_name = $get_directors_info_with_address["street_name1"];
				$director_building_name = $get_directors_info_with_address["building_name1"];
				$director_postal_code = $get_directors_info_with_address["postal_code1"];
			//}

			$address = array(
				'type' 			=> $get_directors_info_with_address['address_type'],
				'street_name1' 	=> strtoupper($director_street_name),
				'unit_no1'		=> strtoupper($director_unit_no1),
				'unit_no2'		=> strtoupper($director_unit_no2),
				'building_name1'=> strtoupper($director_building_name),
				'postal_code1'	=> strtoupper($director_postal_code),
				'foreign_address1' => strtoupper($get_directors_info_with_address["foreign_address1"]),
				'foreign_address2' => strtoupper($get_directors_info_with_address["foreign_address2"]),
				'foreign_address3' => strtoupper($get_directors_info_with_address["foreign_address3"])
			);

			$address = $this->write_address_local_foreign($address, "letter", "big_cap");

			return $address;
		}
		elseif($string2 == "All Director info with signature")
		{
			$get_directors_info_with_signature = $this->db->query("select officer.* from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1 AND transaction_client_officers.date_of_appointment =''");

			$content = $get_directors_info_with_signature->result_array();

			return $content;
		}
		elseif($string2 == "CDD_member_result")
		{
			$allotment_member_result = $this->db->query("select transaction_member_shares.*, officer.identification_no, officer.name, officer.address_type as officer_address_type, officer.postal_code1, officer.street_name1, officer.building_name1, officer.unit_no1, officer.unit_no2, officer.foreign_address1, officer.foreign_address2, officer.foreign_address3, nationality.nationality AS `officer_nationality`, officer_email.email as officer_email_address, officer_mobile_no.mobile_no, officer_company.register_no, officer_company.country_of_incorporation, officer_company.company_name, officer_company.address_type as officer_company_address_type, officer_company.company_postal_code, officer_company.company_street_name, officer_company.company_building_name, officer_company.company_unit_no1, officer_company.company_unit_no2, officer_company.company_foreign_address1, officer_company.company_foreign_address2, officer_company.company_foreign_address3, client.registration_no, client.company_name as client_company_name, client.postal_code as client_postal_code, client.street_name as client_street_name, client.building_name as client_building_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, transaction_client_member_share_capital.other_class, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name, transaction_certificate.certificate_no, transaction_certificate.new_certificate_no from transaction_member_shares left join transaction_certificate on transaction_certificate.officer_id = transaction_member_shares.officer_id and transaction_certificate.field_type = transaction_member_shares.field_type and transaction_certificate.transaction_id = transaction_member_shares.transaction_id and transaction_certificate.client_member_share_capital_id = transaction_member_shares.client_member_share_capital_id left join officer on transaction_member_shares.officer_id = officer.id and transaction_member_shares.field_type = officer.field_type left join officer_email on officer_email.officer_id = officer.id and officer_email.primary_email = 1 left join officer_mobile_no on officer_mobile_no.officer_id = officer.id and officer_mobile_no.primary_mobile_no = 1 left join officer_company on transaction_member_shares.officer_id = officer_company.id and transaction_member_shares.field_type = officer_company.field_type left join client on transaction_member_shares.officer_id = client.id and transaction_member_shares.field_type = 'client' AND client.deleted != 1 left join transaction_client_member_share_capital on transaction_client_member_share_capital.id = transaction_member_shares.client_member_share_capital_id left join sharetype on sharetype.id = transaction_client_member_share_capital.class_id left join currency on currency.id = transaction_client_member_share_capital.currency_id left join nationality on nationality.id = officer.nationality where transaction_member_shares.company_code='".$company_code."' AND transaction_member_shares.transaction_page_id='".$transaction_master_id."'");

			$allot_result = $allotment_member_result->result_array();

			$get_directors = $this->db->query("select officer.identification_no, officer.name, officer.address_type as officer_address_type, officer.postal_code1, officer.street_name1, officer.building_name1, officer.unit_no1, officer.unit_no2, officer.foreign_address1, officer.foreign_address2, officer.foreign_address3, nationality.nationality AS `officer_nationality`, officer_email.email as officer_email_address, officer_mobile_no.mobile_no from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type left join nationality on nationality.id = officer.nationality left join officer_email on officer_email.officer_id = officer.id and officer_email.primary_email = 1 left join officer_mobile_no on officer_mobile_no.officer_id = officer.id and officer_mobile_no.primary_mobile_no = 1 where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND (transaction_client_officers.position = 1 OR transaction_client_officers.position = 8)");

			$get_directors = $get_directors->result_array();

			$array_CDD_member_result = array_merge($allot_result, $get_directors);
			
			$content = $this->unique_multidim_array($array_CDD_member_result,'name');

			return $content;
		}
		// elseif($string2 == "Declaration for Controller - Director")
		// {
		// 	if ($company_code)
	 //        {
	 //            $latest_q = $this->db->query('select transaction_client_officers.*, transaction_client_officers.company_code as client_controller_company_code, transaction_client_officers.id as client_controller_id, transaction_client_officers.field_type as client_controller_field_type, officer.*, officer.address_type as officer_address_type, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.address_type as officer_company_address_type, officer_company.company_name as officer_company_company_name, client.*, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, nationality.nationality as officer_nationality_name, company_type.company_type as client_company_type from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id and transaction_client_officers.field_type = officer.field_type left join officer_company on transaction_client_officers.officer_id = officer_company.id and transaction_client_officers.field_type = officer_company.field_type left join client on client.id = transaction_client_officers.officer_id AND transaction_client_officers.field_type = "client" left join nationality on nationality.id = officer.nationality left join company_type on company_type.id = client.company_type where transaction_client_officers.company_code ="'.$company_code.'" AND transaction_client_officers.transaction_id = "'.$transaction_id.'"');// AND transaction_client_officers.deleted = 0

	 //            if ($latest_q->num_rows() > 0) 
	 //            {
	 //                $latest_q = $latest_q->result_array();

	 //                $q = $this->db->query('select client_controller.*, client_controller.company_code as client_controller_company_code, client_controller.id as client_controller_id, client_controller.field_type as client_controller_field_type, officer.*, officer.address_type as officer_address_type, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.address_type as officer_company_address_type, officer_company.company_name as officer_company_company_name, client.*, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, nationality.nationality as officer_nationality_name, company_type.company_type as client_company_type from client_controller left join officer on client_controller.officer_id = officer.id and client_controller.field_type = officer.field_type left join officer_company on client_controller.officer_id = officer_company.id and client_controller.field_type = officer_company.field_type left join client on client.id = client_controller.officer_id AND client_controller.field_type = "client" left join nationality on nationality.id = officer.nationality left join company_type on company_type.id = client.company_type where client_controller.company_code ="'.$company_code.'" and client_controller.deleted = 0 AND client_controller.date_of_cessation = ""');// AND client_controller.date_of_cessation = ""

	 //                $current_q = $q->result_array();

	 //                if(count($current_q) > 0)
	 //                {
	 //                    foreach ($latest_q as $i => $defArr) {
	 //                        if($defArr['client_controller_field_type'] == "individual")
	 //                        {
	 //                            $latest_q[$i]['identification_no'] = $this->encryption->decrypt($defArr['identification_no']);
	 //                            $latest_q[$i]['name'] = $this->encryption->decrypt($defArr['name']);
	 //                        }
	 //                        elseif($defArr['client_controller_field_type'] == "company")
	 //                        {
	 //                            $latest_q[$i]['register_no'] = $this->encryption->decrypt($defArr['register_no']);
	 //                            $latest_q[$i]['officer_company_company_name'] = $this->encryption->decrypt($defArr['officer_company_company_name']);
	 //                        }
	 //                        elseif($defArr['client_controller_field_type'] == "client")
	 //                        {
	 //                            $latest_q[$i]['registration_no'] = $this->encryption->decrypt($defArr['registration_no']);
	 //                            $latest_q[$i]['client_company_name'] = $this->encryption->decrypt($defArr['client_company_name']);
	 //                        }
	 //                        foreach ($current_q as $j => $dayArr) {
	 //                            if ($dayArr['officer_id'] == $defArr['officer_id'] && $dayArr['client_controller_field_type'] == $defArr['client_controller_field_type'] && $dayArr['date_of_cessation'] == "") {
	 //                                unset($latest_q[$i]);
	 //                            }
	 //                        }
	 //                    }

	 //                    return array_values($latest_q);
	 //                }
	 //                else
	 //                {
	 //                    foreach ($latest_q as $i => $defArr) {
	 //                        if($defArr['client_controller_field_type'] == "individual")
	 //                        {
	 //                            $latest_q[$i]['identification_no'] = $this->encryption->decrypt($defArr['identification_no']);
	 //                            $latest_q[$i]['name'] = $this->encryption->decrypt($defArr['name']);
	 //                        }
	 //                        elseif($defArr['client_controller_field_type'] == "company")
	 //                        {
	 //                            $latest_q[$i]['register_no'] = $this->encryption->decrypt($defArr['register_no']);
	 //                            $latest_q[$i]['officer_company_company_name'] = $this->encryption->decrypt($defArr['officer_company_company_name']);
	 //                        }
	 //                        elseif($defArr['client_controller_field_type'] == "client")
	 //                        {
	 //                            $latest_q[$i]['registration_no'] = $this->encryption->decrypt($defArr['registration_no']);
	 //                            $latest_q[$i]['client_company_name'] = $this->encryption->decrypt($defArr['client_company_name']);
	 //                        }
	 //                    }
	 //                    return $latest_q;
	 //                }
	 //            }
	 //            return false;
	 //        }
		// }
		elseif ($string2 == "check_member_or_director") 
		{
			$officer_info = $this->db->query('select client_officers.*, client.company_name as client_company_name, client_officers_position.position as position_name, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type left join client_officers_position on client_officers.position = client_officers_position.id left join client on client_officers.company_code = client.company_code where client_officers.company_code ="'.$company_code.'" AND client_officers.date_of_cessation = "" AND (client_officers.position = 1 OR client_officers.position = 8)');

            if($officer_info != null)
            {
                if ($officer_info->num_rows() > 0) 
                {
                    foreach (($officer_info->result()) as $row) 
                    {
                        if($value != null)
                        {
                            if(stripos($this->encryption->decrypt($row->identification_no), $value) !== FALSE)
                            {
                                $is_director = "director";
                                break;
                            }
                            else
                            {
                            	$is_director = null;
                            }
                        }
                    }
                }
            }

			$q = $this->db->query('select member_shares.*, client.registration_no, client.company_name as client_company_name, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on member_shares.company_code = client.company_code where member_shares.company_code ="'.$company_code.'" AND number_of_share != 0 GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id');

            if($officer_info != null)
            {
                if ($q->num_rows() > 0) 
                {
                    //$q = $q->result_array();
                    foreach ($q->result() as $member_row) 
                    {
                        if($value != null)
                        {
                            if(stripos($this->encryption->decrypt($member_row->register_no), $value) !== FALSE)
                            {   //print_r($member_row);
                                $is_shareholder = "shareholder";
                                break;
                            }
                            else if(stripos($this->encryption->decrypt($member_row->identification_no), $value) !== FALSE)
                            {
                                $is_shareholder = "shareholder";
                                break;
                            }
                            else if(stripos($this->encryption->decrypt($member_row->registration_no), $value) !== FALSE)
                            {
                                $is_shareholder = "shareholder";
                                break;
                            }
                            else
                            {
                            	$is_shareholder = null;
                            }
                        }
                    }
                }
            }

            if($is_director != null && $is_shareholder != null)
            {
            	$content = "director and shareholder";
            }
            elseif($is_director != null && $is_shareholder == null)
            {
            	$content = "director";
            }
            elseif($is_director == null && $is_shareholder != null)
            {
            	$content = "shareholder";
            }
            else
            {
            	$content = "";
            }

            return $content;
		}
		elseif($string2 == "get_director_member_result")
		{
			$allotment_member_result = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, z.company_name as ms_client_company_name, officer.identification_no, officer.name, officer.address_type as officer_address_type, officer.postal_code1, officer.street_name1, officer.building_name1, officer.unit_no1, officer.unit_no2, officer.foreign_address1, officer.foreign_address2, officer.foreign_address3, nationality.nationality AS `officer_nationality`, officer_email.email as officer_email_address, officer_mobile_no.mobile_no, officer_company.register_no, officer_company.country_of_incorporation, officer_company.company_name, officer_company.address_type as officer_company_address_type, officer_company.company_postal_code, officer_company.company_street_name, officer_company.company_building_name, officer_company.company_unit_no1, officer_company.company_unit_no2, officer_company.company_foreign_address1, officer_company.company_foreign_address2, officer_company.company_foreign_address3, client.registration_no, client.company_name as client_company_name, client.postal_code as client_postal_code, client.street_name as client_street_name, client.building_name as client_building_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2 from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join nationality on nationality.id = officer.nationality left join officer_email on officer_email.officer_id = officer.id and officer_email.primary_email = 1 left join officer_mobile_no on officer_mobile_no.officer_id = officer.id and officer_mobile_no.primary_mobile_no = 1 left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" left join client as z on z.company_code = member_shares.company_code where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id HAVING sum(member_shares.number_of_share) != 0'); //, client_member_share_capital.other_class, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name

			$allot_result = $allotment_member_result->result_array();

			$get_directors = $this->db->query("select officer.identification_no, officer.name, officer.address_type as officer_address_type, officer.postal_code1, officer.street_name1, officer.building_name1, officer.unit_no1, officer.unit_no2, officer.foreign_address1, officer.foreign_address2, officer.foreign_address3, nationality.nationality AS `officer_nationality`, officer_email.email as officer_email_address, officer_mobile_no.mobile_no, z.company_name as ms_client_company_name from client_officers left join officer on client_officers.officer_id = officer.id AND client_officers.field_type = officer.field_type left join nationality on nationality.id = officer.nationality left join officer_email on officer_email.officer_id = officer.id and officer_email.primary_email = 1 left join officer_mobile_no on officer_mobile_no.officer_id = officer.id and officer_mobile_no.primary_mobile_no = 1 left join client as z on z.company_code = client_officers.company_code where client_officers.company_code='".$company_code."' AND client_officers.date_of_cessation = '' AND (client_officers.position = 1 OR client_officers.position = 8)");

			$get_directors = $get_directors->result_array();

			$array_director_member_result = array_merge($allot_result, $get_directors);
			
			$content = $this->unique_multidim_array($array_director_member_result,'name');

			return $content;
		}
		elseif($string2 == "get_director_with_member_result")
		{
			$allotment_member_result = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, MAX(STR_TO_DATE(transaction_date,"%d/%m/%Y")) as latest_transaction_date, z.company_name as client_company_name, officer.id as officer_id, officer.identification_no, officer.name, officer.address_type as officer_address_type, officer.postal_code1, officer.street_name1, officer.building_name1, officer.unit_no1, officer.unit_no2, officer.foreign_address1, officer.foreign_address2, officer.foreign_address3, nationality.nationality AS `officer_nationality`, officer_email.email as officer_email_address, officer_mobile_no.mobile_no, officer_company.register_no, officer_company.country_of_incorporation, officer_company.company_name, officer_company.address_type as officer_company_address_type, officer_company.company_postal_code, officer_company.company_street_name, officer_company.company_building_name, officer_company.company_unit_no1, officer_company.company_unit_no2, officer_company.company_foreign_address1, officer_company.company_foreign_address2, officer_company.company_foreign_address3, client.registration_no, client.company_name as client_company_name, client.postal_code as client_postal_code, client.street_name as client_street_name, client.building_name as client_building_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2 from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join nationality on nationality.id = officer.nationality left join officer_email on officer_email.officer_id = officer.id and officer_email.primary_email = 1 left join officer_mobile_no on officer_mobile_no.officer_id = officer.id and officer_mobile_no.primary_mobile_no = 1 left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" left join client as z on z.company_code = member_shares.company_code where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id HAVING sum(member_shares.number_of_share) != 0'); //, client_member_share_capital.other_class, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name

			$allot_result = $allotment_member_result->result_array();

			$get_directors = $this->db->query("select z.company_name as client_company_name, officer.id as officer_id, officer.identification_no, officer.name, officer.address_type as officer_address_type, officer.postal_code1, officer.street_name1, officer.building_name1, officer.unit_no1, officer.unit_no2, officer.foreign_address1, officer.foreign_address2, officer.foreign_address3, nationality.nationality AS `officer_nationality`, officer_email.email as officer_email_address, officer_mobile_no.mobile_no from client_officers left join officer on client_officers.officer_id = officer.id AND client_officers.field_type = officer.field_type left join nationality on nationality.id = officer.nationality left join officer_email on officer_email.officer_id = officer.id and officer_email.primary_email = 1 left join officer_mobile_no on officer_mobile_no.officer_id = officer.id and officer_mobile_no.primary_mobile_no = 1 left join client as z on z.company_code = client_officers.company_code where client_officers.company_code = '".$company_code."' AND client_officers.date_of_cessation = '' AND (client_officers.position = 1 OR client_officers.position = 8)");

			$get_directors = $get_directors->result_array();

			if(count($allot_result) > 0)
			{
				foreach($allot_result as $allot_key => $allot_value)
				{
					foreach($get_directors as $director_key => $director_value)
					{
						if($allot_value["officer_id"] == $director_value["officer_id"])
						{
							//$$allot_value["share_member_or_director"] = "director and shareholder";
							$data[] = $allot_value;
						}
					}
				}
			}
			else
			{
				$data = [];
			}

			return $data;
		}
		elseif($string2 == "check_latest_share" || $string2 == "share_date_acquired")
		{
			$content = "";

			$allotment_member_result = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, MAX(STR_TO_DATE(transaction_date,"%d/%m/%Y")) as latest_transaction_date, officer.id as officer_id, officer.identification_no, officer.name, officer.address_type as officer_address_type, officer.postal_code1, officer.street_name1, officer.building_name1, officer.unit_no1, officer.unit_no2, officer.foreign_address1, officer.foreign_address2, officer.foreign_address3, nationality.nationality AS `officer_nationality`, officer_email.email as officer_email_address, officer_mobile_no.mobile_no, officer_company.register_no, officer_company.country_of_incorporation, officer_company.company_name, officer_company.address_type as officer_company_address_type, officer_company.company_postal_code, officer_company.company_street_name, officer_company.company_building_name, officer_company.company_unit_no1, officer_company.company_unit_no2, officer_company.company_foreign_address1, officer_company.company_foreign_address2, officer_company.company_foreign_address3, client.registration_no, client.company_name as client_company_name, client.postal_code as client_postal_code, client.street_name as client_street_name, client.building_name as client_building_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2 from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join nationality on nationality.id = officer.nationality left join officer_email on officer_email.officer_id = officer.id and officer_email.primary_email = 1 left join officer_mobile_no on officer_mobile_no.officer_id = officer.id and officer_mobile_no.primary_mobile_no = 1 left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id HAVING sum(member_shares.number_of_share) != 0');

			$allot_result = $allotment_member_result->result_array();

			if(count($allot_result) > 0)
			{
				foreach($allot_result as $allot_key => $allot_value)
				{
					if($allot_value["officer_id"] == $value)
					{
						if($string2 == "check_latest_share")
						{
							$content = $allot_value["number_of_share"];
						}
						else if($string2 == "share_date_acquired")
						{
							$content = $allot_value["latest_transaction_date"];
						}
					}
				}
			}
			else
			{
				$content = "";
			}

			return $content;
		}
		elseif($string2 == "director_declaration_company_name" || $string2 == "director_declaration_nature" || $string2 == "number_of_officer_info")
		{
			$officer_info = $this->db->query('select client_officers.*, client.company_name as client_company_name, client_officers_position.position as position_name, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type left join client_officers_position on client_officers.position = client_officers_position.id left join client on client_officers.company_code = client.company_code where client_officers.date_of_cessation = "" AND client_officers.position = 1 AND client.acquried_by = "1" AND client.deleted != "1" AND client.status = "1"');

            if($officer_info != null)
            {
                if ($officer_info->num_rows() > 0) 
                {	
                	if($string2 == "number_of_officer_info")
                	{
                		$content = count($officer_info->result_array());
                	}
                	else
                	{
	                    foreach (($officer_info->result()) as $row) 
	                    {
	                        $row->client_company_name = $this->encryption->decrypt($row->client_company_name);
	                        if($row->identification_no != null)
	                        {
	                            $row->identification_no = $this->encryption->decrypt($row->identification_no);
	                            $row->name = $this->encryption->decrypt($row->name);
	                        }

	                        if($value != null)
	                        {
								if($row->officer_id == $value)
	                            {
	                            	if($string2 == "director_declaration_company_name")
	                            	{
	                                	$content[] = $row->client_company_name;
	                            	}
	                            	elseif($string2 == "director_declaration_nature")
	                            	{
	                                	$content = "DIRECTOR";
	                            	}
	                            }
	                        }
	                    }
	                }
                }
            }

            return $content;
		}
		elseif($string2 == "allotment_member_result")
		{
			$allotment_member_result = $this->db->query("select transaction_member_shares.*, officer.identification_no, officer.name, officer.address_type as officer_address_type, officer.postal_code1, officer.street_name1, officer.building_name1, officer.unit_no1, officer.unit_no2, officer.foreign_address1, officer.foreign_address2, officer.foreign_address3, nationality.nationality AS `officer_nationality`, officer_company.register_no, officer_company.country_of_incorporation, officer_company.company_name, officer_company.address_type as officer_company_address_type, officer_company.company_postal_code, officer_company.company_street_name, officer_company.company_building_name, officer_company.company_unit_no1, officer_company.company_unit_no2, officer_company.company_foreign_address1, officer_company.company_foreign_address2, officer_company.company_foreign_address3, client.registration_no, client.company_name as client_company_name, client.postal_code as client_postal_code, client.street_name as client_street_name, client.building_name as client_building_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, transaction_client_member_share_capital.other_class, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name, currency.name as currency_short_name, transaction_certificate.certificate_no, transaction_certificate.new_certificate_no from transaction_member_shares left join transaction_certificate on transaction_certificate.officer_id = transaction_member_shares.officer_id and transaction_certificate.field_type = transaction_member_shares.field_type and transaction_certificate.transaction_id = transaction_member_shares.transaction_id and transaction_certificate.client_member_share_capital_id = transaction_member_shares.client_member_share_capital_id left join officer on transaction_member_shares.officer_id = officer.id and transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id and transaction_member_shares.field_type = officer_company.field_type left join client on transaction_member_shares.officer_id = client.id and transaction_member_shares.field_type = 'client' AND client.deleted != 1 left join transaction_client_member_share_capital on transaction_client_member_share_capital.id = transaction_member_shares.client_member_share_capital_id left join sharetype on sharetype.id = transaction_client_member_share_capital.class_id left join currency on currency.id = transaction_client_member_share_capital.currency_id left join nationality on nationality.id = officer.nationality where transaction_member_shares.company_code='".$company_code."' AND transaction_member_shares.transaction_page_id='".$transaction_master_id."'");

			$content = $allotment_member_result->result_array();

			return $content;
		}
		elseif($string2 == "Display title type for board of director" || $string2 == "Display board of director - content" || $string2 == "Display All members of the")
		{
			$count_director = 1;

			if($document_name == "Online Filing to ACRA")
			{
				$director = "";

				$get_directors = $this->db->query("select officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_client_officers.date_of_cessation = '' AND transaction_client_officers.position = 1");

				$get_directors = $get_directors->result_array();

				$count_director = count($get_directors);
			}
			elseif($document_name == "DRIW-Appt of Director" || $document_name == "DRIW-Change of Reg Ofis" || $document_name == "DRIW - Resignation of Director" || $document_name == "DRIW-Appt of Auditor" || $document_name == "DRIW - Resignation Of Auditor" || $document_name == "DRIW - Change of Auditor" || $document_name == "DRIW-Change of name of company" || $document_name == "DRIW-Allotment of Shares" || $document_name == "Allotment-Authority to EGM" || $document_name == "DRIW - Transfer of Shares" || $document_name == "AGM & AR - DRIW" || $document_name == "DRIW-Change of FYE" || $document_name == "DRIW-Change Biz Activity" || $document_name == "DRIW-Dividends" || $document_name == "DRIW-Incorp of subsidiary" || $document_name = "Subsidiary-Cert of Appt Company Representative" || $document_name == "DRIW-Issue Director Fee & EGM" || $document_name == "Director Fee-Notice Of EGM" || $document_name == "Director Fee-Minutes Of EGM" || $document_name == "01 Letter of Authorisation" || $document_name == "Letter of Appointment" || $document_name == "Auditor-Notice of EGM" || $document_name == "Company Name-Notice of EGM" || $document_name == "Form 11")
			{
				$director = "";

				$get_directors = $this->db->query("select officer.name from client_officers left join officer on client_officers.officer_id = officer.id AND client_officers.field_type = officer.field_type where client_officers.company_code='".$company_code."' AND client_officers.date_of_cessation = '' AND client_officers.position = 1");

				$get_directors = $get_directors->result_array();

				$count_director = count($get_directors);
			}

			if($count_director == 1)
			{
				if($string2 == "Display title type for board of director")
				{
					$content = 'SOLE DIRECTOR';
				}
				elseif($string2 == "Display board of director - content")
				{
					$content = 'sole director';
				}
				elseif($string2 == "Display All members of the")	// Display words of "All members of the " before board of director
				{
					$content = 'The ';
				}
			}
			elseif($count_director == 2)
			{
				if($string2 == "Display title type for board of director")
				{
					$content = 'DIRECTORS'; //THE BOARD OF DIRECTORS
				}
				elseif($string2 == "Display board of director - content")
				{
					$content = 'board of directors';
				}
				elseif($string2 == "Display All members of the")	// Display words of "All members of the " before board of director
				{
					$content = "All members of the ";
				}
			}
			elseif($count_director > 2)
			{
				if($string2 == "Display title type for board of director")
				{
					$content = 'DIRECTORS'; //ON BEHALF OF THE BOARD OF DIRECTORS
				}
				elseif($string2 == "Display board of director - content")
				{
					$content = 'board of directors';
				}
				elseif($string2 == "Display All members of the") // Display words of "All members of the " before board of director
				{
					$content = "All members of the ";
				}

			}
			return $content;
		}
		if($string2 == "sing/plu s" || $string2 == "sing/plu s'" || $string2 == "tense's" || $string2 == "is/are")	// eg. director(s)
		{
			if($document_name == "AGM & AR - DRIW")
			{
				$get_directors = $this->db->query('select transaction_agm_ar_director_retire.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id left join transaction_agm_ar_director_retire on transaction_agm_ar_director_retire.transaction_agm_ar_id = transaction_agm_ar.id and transaction_agm_ar_director_retire.director_retiring_checkbox = 1 where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');
			}
			else
			{
				$get_directors = $this->db->query("select officer.name from client_officers left join officer on client_officers.officer_id = officer.id AND client_officers.field_type = officer.field_type where client_officers.company_code='".$company_code."' AND client_officers.date_of_cessation = '' AND client_officers.position = 1");
			}

			$get_directors = $get_directors->result_array();

			$count_director = count($get_directors);

			$isPlural = $count_director > 1? true: false;

			if($string2 == "sing/plu s")
			{
				if($isPlural)
				{
					$content = "rs";
				}
				else
				{
					$content = 'r';
				}
			}
			elseif($string2 == "sing/plu s'") // eg. director(s')
			{
				if($isPlural)
				{
					$content = "s";
				}
				else
				{
					$content = "'s";
				}
			}
			elseif($string2 == "tense's")	// eg. director('s)
			{
				if(!$isPlural)
				{
					$content = "s";
				}
			}
			elseif($string2 == "is/are")
			{
				if(!$isPlural)
				{
					$content = "is";
				}
				else
				{
					$content = "are";
				}
			}

			return $content;
		}
		elseif($string2 == "count_sing/plu s")
		{
			$count_director = count($value);

			$isPlural = $count_director > 1? true: false;

			if($string2 == "count_sing/plu s")
			{
				if($isPlural)
				{
					$content = "rs";
				}
				else
				{
					$content = 'r';
				}
			}
			elseif($string2 == "sing/plu s'") // eg. director(s')
			{
				if($isPlural)
				{
					$content = "s";
				}
				else
				{
					$content = "'s";
				}
			}
			elseif($string2 == "tense's")	// eg. director('s)
			{
				if(!$isPlural)
				{
					$content = "s";
				}
			}
			elseif($string2 == "is/are")
			{
				if(!$isPlural)
				{
					$content = "is";
				}
				else
				{
					$content = "are";
				}
			}

			return $content;
		}
		elseif($string2 == "Auditors Members' Meeting Date" || $string2 == "Auditors Underline Members' Meeting Date")
		{
			if($string2 == "Auditors Underline Members' Meeting Date")
			{
				$content = '__________________';
			}
			else
			{
				$content = '<w:t xml:space="preserve">                    </w:t>';
			}

			$transaction_meeting_date_info = $this->db->query('select transaction_appoint_auditor_date.* from transaction_appoint_auditor_date where transaction_master_id="'.$transaction_master_id.'"');

			$transaction_meeting_date_info = $transaction_meeting_date_info->result_array();

			if(count($transaction_meeting_date_info) > 0)
			{
				if(!empty($transaction_meeting_date_info[0]["member_meeting_date"]))
				{
					$content = date('d F Y', strtotime(str_replace('/', '-', $transaction_meeting_date_info[0]["member_meeting_date"])));
				}
			}

			return $content;
		}
		elseif($string2 == "Auditors Members' Meeting Time" || $string2 == "Auditors Underline Members' Meeting Time")
		{
			if($string2 == "Auditors Underline Members' Meeting Time")
			{
				$content = '__________________';
			}
			else
			{
				$content = '<w:t xml:space="preserve">                    </w:t>';
			}

			$transaction_meeting_date_info = $this->db->query('select transaction_appoint_auditor_date.* from transaction_appoint_auditor_date where transaction_master_id="'.$transaction_master_id.'"');

			$transaction_meeting_date_info = $transaction_meeting_date_info->result_array();

			if(count($transaction_meeting_date_info) > 0)
			{
				if(!empty($transaction_meeting_date_info[0]["member_meeting_time"]))
				{
					$content = $transaction_meeting_date_info[0]["member_meeting_time"];
				}
			}

			return $content;
		}
		elseif($string2 == "Auditors Meeting's Venue" || $string2 == "Auditors Underline Meeting's Venue")
		{
			if($string2 == "Auditors Underline Meeting's Venue")
			{
				$content = '_________________________________________________________________';
			}
			else
			{
				$content = '<w:t xml:space="preserve">                    </w:t>';
			}

			$transaction_meeting_date_info = $this->db->query('select transaction_appoint_auditor_date.* from transaction_appoint_auditor_date where transaction_master_id="'.$transaction_master_id.'"');

			$transaction_meeting_date_info = $transaction_meeting_date_info->result_array();

			if(count($transaction_meeting_date_info) > 0 && $transaction_meeting_date_info[0]["address_type"] != null)
			{
				$meeting_unit_no1 = "";
				$meeting_unit_no2 = "";
				$meeting_street_name = "";
				$meeting_building_name = "";
				$meeting_postal_code = "";
				$foreign_address1 = "";
				$foreign_address2 = "";
				$foreign_address3 = "";

				if($transaction_meeting_date_info[0]["address_type"] == "Registered Office Address")
				{
					$meeting_address_type = "Local";
					$meeting_unit_no1 = $transaction_meeting_date_info[0]["registered_unit_no1"];
					$meeting_unit_no2 = $transaction_meeting_date_info[0]["registered_unit_no2"];
					$meeting_street_name = $transaction_meeting_date_info[0]["registered_street_name1"];
					$meeting_building_name = $transaction_meeting_date_info[0]["registered_building_name1"];
					$meeting_postal_code = $transaction_meeting_date_info[0]["registered_postal_code1"];

				}
				else if($transaction_meeting_date_info[0]["address_type"] == "Local")
				{
					$meeting_address_type = "Local";
					$meeting_unit_no1 = $transaction_meeting_date_info[0]["unit_no1"];
					$meeting_unit_no2 = $transaction_meeting_date_info[0]["unit_no2"];
					$meeting_street_name = $transaction_meeting_date_info[0]["street_name1"];
					$meeting_building_name = $transaction_meeting_date_info[0]["building_name1"];
					$meeting_postal_code = $transaction_meeting_date_info[0]["postal_code1"];
				}
				elseif($transaction_meeting_date_info[0]["address_type"] == "Foreign")
				{
					$meeting_address_type = "Foreign";
					$foreign_address1 = $transaction_meeting_date_info[0]["foreign_address1"];
					$foreign_address2 = $transaction_meeting_date_info[0]["foreign_address2"];
					$foreign_address3 = $transaction_meeting_date_info[0]["foreign_address3"];
				}
				
				$address = array(
					'type' 			=> $meeting_address_type,
					'street_name1' 	=> strtoupper($meeting_street_name),
					'unit_no1'		=> strtoupper($meeting_unit_no1),
					'unit_no2'		=> strtoupper($meeting_unit_no2),
					'building_name1'=> strtoupper($meeting_building_name),
					'postal_code1'	=> strtoupper($meeting_postal_code),
					'foreign_address1' => strtoupper($foreign_address1),
					'foreign_address2' => strtoupper($foreign_address2),
					'foreign_address3' => strtoupper($foreign_address3)
				);

				$content = $this->write_address_local_foreign($address, "comma", "big_cap");
			}
			return $content;
		}
		elseif($string2 == "Members' Meeting Date" || $string2 == "Underline Members' Meeting Date")
		{
			if($document_name == "F24 - Return of allotment of shares" || $document_name == "Allotment-Share Application Form" || $document_name == "Transfer Form")
			{
				$content = '';
			}
			else
			{
				if($string2 == "Underline Members' Meeting Date")
				{
					$content = '__________________';
				}
				else
				{
					$content = '<w:t xml:space="preserve">                    </w:t>';
				}
			}

			$transaction_meeting_date_info = $this->db->query('select transaction_meeting_date.* from transaction_meeting_date where transaction_master_id="'.$transaction_master_id.'"');

			$transaction_meeting_date_info = $transaction_meeting_date_info->result_array();

			if(count($transaction_meeting_date_info) > 0)
			{
				if(!empty($transaction_meeting_date_info[0]["member_meeting_date"]))
				{
					$content = date('d F Y', strtotime(str_replace('/', '-', $transaction_meeting_date_info[0]["member_meeting_date"])));
				}
			}

			return $content;
		}
		elseif($string2 == "Members' Meeting Time" || $string2 == "Underline Members' Meeting Time")
		{
			if($string2 == "Underline Members' Meeting Time")
			{
				$content = '__________________';
			}
			else
			{
				$content = '<w:t xml:space="preserve">                    </w:t>';
			}

			$transaction_meeting_date_info = $this->db->query('select transaction_meeting_date.* from transaction_meeting_date where transaction_master_id="'.$transaction_master_id.'"');

			$transaction_meeting_date_info = $transaction_meeting_date_info->result_array();

			if(count($transaction_meeting_date_info) > 0)
			{
				if(!empty($transaction_meeting_date_info[0]["member_meeting_time"]))
				{
					$content = $transaction_meeting_date_info[0]["member_meeting_time"];
				}
			}

			return $content;
		}
		elseif($string2 == "Meeting's Venue" || $string2 == "AGM Meeting's Venue" || $string2 == "Underline Meeting's Venue" || $string2 == "Underline AGM Meeting's Venue" || $string2 == "Strike Off AGM Meeting's Venue")
		{
			if($string2 == "Underline Meeting's Venue" || $string2 == "Underline AGM Meeting's Venue")
			{
				$content = '_______________________________________________________________________________';
			}
			else
			{
				$content = '<w:t xml:space="preserve">                    </w:t>';
			}

			if($string2 == "Meeting's Venue" || $string2 == "Underline Meeting's Venue")
			{
				$transaction_meeting_date_info = $this->db->query('select transaction_meeting_date.* from transaction_meeting_date where transaction_master_id="'.$transaction_master_id.'"');
			}
			elseif($string2 == "AGM Meeting's Venue" || $string2 == "Underline AGM Meeting's Venue")
			{
				$transaction_meeting_date_info = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar where transaction_id="'.$transaction_master_id.'"');
			}
			elseif($string2 == "Strike Off AGM Meeting's Venue")
			{
				$transaction_meeting_date_info = $this->db->query("SELECT * FROM transaction_strike_off WHERE transaction_id = ". $transaction_master_id);
			}

			$transaction_meeting_date_info = $transaction_meeting_date_info->result_array();

			if(count($transaction_meeting_date_info) > 0)
			{
				$meeting_unit_no1 = "";
				$meeting_unit_no2 = "";
				$meeting_street_name = "";
				$meeting_building_name = "";
				$meeting_postal_code = "";
				$foreign_address1 = "";
				$foreign_address2 = "";
				$foreign_address3 = "";

				if($transaction_meeting_date_info[0]["address_type"] == "Registered Office Address")
				{
					$meeting_address_type = "Local";
					$meeting_unit_no1 = $transaction_meeting_date_info[0]["registered_unit_no1"];
					$meeting_unit_no2 = $transaction_meeting_date_info[0]["registered_unit_no2"];
					$meeting_street_name = $transaction_meeting_date_info[0]["registered_street_name1"];
					$meeting_building_name = $transaction_meeting_date_info[0]["registered_building_name1"];
					$meeting_postal_code = $transaction_meeting_date_info[0]["registered_postal_code1"];

				}
				else if($transaction_meeting_date_info[0]["address_type"] == "Local")
				{
					$meeting_address_type = "Local";
					$meeting_unit_no1 = $transaction_meeting_date_info[0]["unit_no1"];
					$meeting_unit_no2 = $transaction_meeting_date_info[0]["unit_no2"];
					$meeting_street_name = $transaction_meeting_date_info[0]["street_name1"];
					$meeting_building_name = $transaction_meeting_date_info[0]["building_name1"];
					$meeting_postal_code = $transaction_meeting_date_info[0]["postal_code1"];
				}
				elseif($transaction_meeting_date_info[0]["address_type"] == "Foreign")
				{
					$meeting_address_type = "Foreign";
					$foreign_address1 = $transaction_meeting_date_info[0]["foreign_address1"];
					$foreign_address2 = $transaction_meeting_date_info[0]["foreign_address2"];
					$foreign_address3 = $transaction_meeting_date_info[0]["foreign_address3"];
				}
				
				$address = array(
					'type' 			=> $meeting_address_type,
					'street_name1' 	=> strtoupper($meeting_street_name),
					'unit_no1'		=> strtoupper($meeting_unit_no1),
					'unit_no2'		=> strtoupper($meeting_unit_no2),
					'building_name1'=> strtoupper($meeting_building_name),
					'postal_code1'	=> strtoupper($meeting_postal_code),
					'foreign_address1' => strtoupper($foreign_address1),
					'foreign_address2' => strtoupper($foreign_address2),
					'foreign_address3' => strtoupper($foreign_address3)
				);

				$content = $this->write_address_local_foreign($address, "comma", "big_cap");
			}
			return $content;
		}
		elseif($string2 == "AGM date" || $string2 == "AGM time" || $string2 == "Underline AGM date" || $string2 == "Underline AGM time")
		{
			if($string2 == "Underline AGM date" || $string2 == "Underline AGM time")
			{
				$content = '__________________';
			}
			elseif($string2 == "AGM date" || $string2 == "AGM time")
			{
				$content = '&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;';
			}

			$transaction_meeting_date_info = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar where transaction_id="'.$transaction_master_id.'"');

			$transaction_meeting_date_info = $transaction_meeting_date_info->result_array();

			if(count($transaction_meeting_date_info) > 0)
			{
				if($string2 == "AGM date" || $string2 == "Underline AGM date")
				{
					if(!empty($transaction_meeting_date_info[0]["agm_date"]))
					{
						$content = $transaction_meeting_date_info[0]["agm_date"];
					}
				}
				elseif($string2 == "AGM time" || $string2 == "Underline AGM time")
				{
					if(!empty($transaction_meeting_date_info[0]["agm_time"]))
					{
						$content = $transaction_meeting_date_info[0]["agm_time"];
					}
				}
			}

			return $content;
		}
		elseif($string2 == "Resolution Date")
		{
			$content = '<w:r><w:t xml:space="preserve">                      </w:t></w:r>';

			$reso_date = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

			$reso_date = $reso_date->result_array();

			if(count($reso_date) > 0)
			{
				$content = $reso_date[0]["notice_date"];

				if(empty($reso_date[0]["notice_date"]))
				{
					$content = '<w:r><w:t xml:space="preserve">                      </w:t></w:r>';
				}
			}

			return $content;
		}
		elseif($string2 == "Allotment - members_details")
		{
			$allotment_member_result = $value;

			if($allotment_member_result["name"] != '')
			{
				if($allotment_member_result['officer_address_type'] == "Local")
				{
					if($allotment_member_result["unit_no1"] != "" || $allotment_member_result["unit_no2"] != "")
					{
						$client_unit = ' #'.$allotment_member_result["unit_no1"] .' - '.$allotment_member_result["unit_no2"];
					}
					else
					{
						$client_unit = "";
					}

					if($allotment_member_result["building_name1"] != "")
					{
						$members_building_name_1 = $client_unit.' '.$allotment_member_result["building_name1"].',<w:br/>';
					}
					else
					{
						if($client_unit != "")
						{
							$members_building_name_1 = $client_unit.',<w:br/>';
						}
						else
						{
							$members_building_name_1 = "";
						}
					}

					$offis_address_content = $allotment_member_result["street_name1"].',<w:br/>'.$members_building_name_1.'SINGAPORE '.$allotment_member_result["postal_code1"];
				}
				else if($allotment_member_result['officer_address_type'] == "Foreign")
				{
					$foreign_address1 = !empty($allotment_member_result["foreign_address1"])?$allotment_member_result["foreign_address1"]: '';
					$foreign_address2 = !empty($allotment_member_result["foreign_address2"])?',<w:br/>'. $allotment_member_result["foreign_address2"]: '';
					$foreign_address3 = !empty($allotment_member_result["foreign_address3"])?',<w:br/>'.$allotment_member_result["foreign_address3"]: '';

					$offis_address_content = $foreign_address1.$foreign_address2.$foreign_address3;
				}

				if($document_category_id == 2)
				{
					$content =  $this->encryption->decrypt($allotment_member_result["name"]);
				}
				else
				{
					$content =  $this->encryption->decrypt($allotment_member_result["name"]).'<w:br/>(Identification no: '.$this->encryption->decrypt($allotment_member_result["identification_no"]).')<w:br/>('.$offis_address_content.')<w:br/>'. htmlspecialchars($allotment_member_result["officer_nationality"]) .'<w:br/>';
				}
			}
			elseif($allotment_member_result["company_name"] != '')
			{
				if($allotment_member_result['officer_company_address_type'] != null)
				{
					if($allotment_member_result['officer_company_address_type'] == "Local")
					{
						if($allotment_member_result["company_unit_no1"] != "" || $allotment_member_result["company_unit_no2"] != "")
						{
							$client_unit = ' #'.$allotment_member_result["company_unit_no1"].' - '.$allotment_member_result["company_unit_no2"];
						}
						else
						{
							$client_unit = "";
						}

						if($allotment_member_result["building_name1"] != "")
						{
							$members_building_name_2 = $client_unit.' '.$allotment_member_result["building_name1"].',<w:br/>';
						}
						else
						{
							if($client_unit != "")
							{
								$members_building_name_2 = $client_unit.',<w:br/>';
							}
							else
							{
								$members_building_name_2 = "";
							}
						}

						$offis_company_address = $allotment_member_result["company_street_name"].',<w:br/>'. $members_building_name_2.'SINGAPORE '.$allotment_member_result["company_postal_code"];
					}
					else if($allotment_member_result['officer_company_address_type'] == "Foreign")
					{
						$company_foreign_address1 = !empty($allotment_member_result["company_foreign_address1"])?$allotment_member_result["company_foreign_address1"]: '';
						$company_foreign_address2 = !empty($allotment_member_result["company_foreign_address2"])?',<w:br/>'.$allotment_member_result["company_foreign_address2"]: '';
						$company_foreign_address3 = !empty($allotment_member_result["company_foreign_address3"])? ',<w:br/>'.$allotment_member_result["company_foreign_address3"]: '';

						$offis_company_address = $company_foreign_address1 . $company_foreign_address2 . $company_foreign_address3;
					}
				}
				$content = $this->encryption->decrypt($allotment_member_result["company_name"]).'<w:br/>(Identification no: '.$this->encryption->decrypt($allotment_member_result["register_no"]).')<w:br/>('.$offis_company_address.')<w:br/>'. $allotment_member_result["country_of_incorporation"] .'<w:br/>';

				if($document_category_id == 2)
				{
					$content =  $this->encryption->decrypt($allotment_member_result["company_name"]);
				}
				else
				{
					$content = $this->encryption->decrypt($allotment_member_result["company_name"]).'<w:br/>(Identification no: '.$this->encryption->decrypt($allotment_member_result["register_no"]).')<w:br/>('.$offis_company_address.')<w:br/>'. $allotment_member_result["country_of_incorporation"] .'<w:br/>';
				
				}
			}
			elseif($allotment_member_result["client_company_name"] != '')
			{
				if($allotment_member_result["client_unit_no1"] != "" || $allotment_member_result["client_unit_no2"] != "")
				{
					$client_unit = ' #'.$allotment_member_result["client_unit_no1"].' - '.$allotment_member_result["client_unit_no2"];
				}
				else
				{
					$client_unit = "";
				}

				if($allotment_member_result["building_name1"] != "")
				{
					$members_building_name_3 = ' '.$allotment_member_result["building_name1"];
				}

				$client_street_name = !empty($allotment_member_result["client_street_name"])?$allotment_member_result["client_street_name"]: '';
				if(!empty($client_unit) || !empty($members_building_name_3))
				{
					$break = ',<w:br/>';
				}
				else
				{
					$break = '';
				}

				$client_address = $client_street_name . $break . $client_unit.''.$members_building_name_3.',<w:br/>SINGAPORE '.$allotment_member_result["client_postal_code"];	

				if($document_category_id == 2)
				{
					$content =  $this->encryption->decrypt($allotment_member_result["client_company_name"]);
				}
				else
				{
					$content =  $this->encryption->decrypt($allotment_member_result["client_company_name"]).'<w:br/>(Identification no: '.$this->encryption->decrypt($allotment_member_result["registration_no"]).')<w:br/>('.$client_address.')<w:br/>';
				}
			}
			return $content;
		}
		elseif($string2 == "Directors name - all")
		{
			$director = "";
			$content = '';

			$temp_document_name = $document_name;	// due to $document_name will change to 1 after if condition, therefore we use this to avoid the problem

			if($document_name == "Auditor-Notice of EGM" || $document_name == "Company Name-Notice of EGM" || $document_name == "Form 11")
			{
				$get_directors = $this->db->query("select officer.name from client_officers left join officer on client_officers.officer_id = officer.id AND client_officers.field_type = officer.field_type where client_officers.company_code='".$company_code."' AND client_officers.date_of_cessation = '' AND client_officers.position = 1");

				$get_directors = $get_directors->result_array();

				$content = $this->encryption->decrypt($get_directors[0]["name"]);
			}
			elseif($document_name == "Online Filing to ACRA")
			{
				$get_directors = $this->db->query("select officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_client_officers.date_of_cessation = '' AND transaction_client_officers.position = 1");

				$get_directors = $get_directors->result_array();

				$content = $get_directors;
			}
			elseif($document_name == "DRIW-Appt of Director" || $document_name == "DRIW-Change of Reg Ofis" || $document_name == "DRIW - Resignation of Director" || $document_name == "DRIW-Appt of Auditor" || $document_name == "DRIW - Resignation Of Auditor" || $document_name == "DRIW - Change of Auditor" || $document_name == "DRIW-Change of name of company" || $document_name == "DRIW-Allotment of Shares" || $document_name == "Allotment-Authority to EGM" || $document_name == "DRIW - Transfer of Shares" || $document_name == "AGM & AR - DRIW" || $document_name == "DRIW-Change of FYE" || $document_name == "DRIW-Change Biz Activity" || $document_name == "DRIW-Dividends" || $document_name == "DRIW-Incorp of subsidiary" || $document_name = "Subsidiary-Cert of Appt Company Representative" || $document_name == "DRIW-Issue Director Fee & EGM" || $document_name == "Director Fee-Notice Of EGM" || $document_name == "Director Fee-Minutes Of EGM" || $document_name == "DRIW-Issue Director Fee & EGM" || $document_name == "01 Letter of Authorisation" || $document_name == "Letter of Appointment" || $document_name == "DRIW - Appt of Co Sec")
			{
				$director = "";

				$get_directors = $this->db->query("select officer.name from client_officers left join officer on client_officers.officer_id = officer.id AND client_officers.field_type = officer.field_type where client_officers.company_code='".$company_code."' AND client_officers.date_of_cessation = '' AND client_officers.position = 1");

				$get_directors = $get_directors->result_array();

				if($temp_document_name == "01 Letter of Authorisation")
				{
					$content = $get_directors;
				}
				else if($temp_document_name == "AGM & AR - Notice for AGM")
				{
					for($i = 0; $i < count($get_directors); $i++)
					{	
						if($i == 0)
						{
							$director = $this->encryption->decrypt($get_directors[$i]["name"]);
						}
						else if($i == count($get_directors) - 1)
						{
							$director = $director . ' and ' . $this->encryption->decrypt($get_directors[$i]["name"]);
						}
						else
						{	
							$director = $director . ', ' . $this->encryption->decrypt($get_directors[$i]["name"]);
						}
					}
					$content = $director;
				}
				elseif($temp_document_name == "AGM & AR - DRIW")
				{
					$content = $get_directors;
				}
				else
				{
					$content = $get_directors;
				}
			}
			
			return $content;
		}
		elseif($string2 == "Auditors Directors' Meeting Date")
		{
			$transaction_meeting_date_info = $this->db->query('select transaction_appoint_auditor_date.* from transaction_appoint_auditor_date where transaction_master_id="'.$transaction_master_id.'"');

			$transaction_meeting_date_info = $transaction_meeting_date_info->result_array();

			if(count($transaction_meeting_date_info) > 0)
			{
				if(!empty($transaction_meeting_date_info[0]["director_meeting_date"]))
				{
					$content = date('d F Y', strtotime(str_replace('/', '-', $transaction_meeting_date_info[0]["director_meeting_date"])));
				}
			}

			return $content;
		}
		elseif($string2 == "Directors' Meeting Date")
		{
			$transaction_meeting_date_info = $this->db->query('select transaction_meeting_date.* from transaction_meeting_date where transaction_master_id="'.$transaction_master_id.'"');

			$transaction_meeting_date_info = $transaction_meeting_date_info->result_array();

			if(count($transaction_meeting_date_info) > 0)
			{
				if(!empty($transaction_meeting_date_info[0]["director_meeting_date"]))
				{
					$content = date('d F Y', strtotime(str_replace('/', '-', $transaction_meeting_date_info[0]["director_meeting_date"])));
				}
			}

			return $content;
		}
		elseif($string2 == "Transaction Company type")
		{	
			$get_transaction_client_company_type = $this->db->query("select company_type.company_type as company_type_name from transaction_client left join company_type on company_type.id = transaction_client.company_type where company_code='".$company_code."' AND transaction_client.deleted != 1");

			$get_transaction_client_company_type = $get_transaction_client_company_type->result_array();

			$content = $get_transaction_client_company_type[0]["company_type_name"];

			return $content;
		}
		elseif($string2 == "Company type")
		{	
			$get_client_company_type = $this->db->query("select company_type.company_type as company_type_name from client left join company_type on company_type.id = client.company_type where company_code='".$company_code."' AND client.deleted != 1");

			$get_client_company_type = $get_client_company_type->result_array();

			$content = $get_client_company_type[0]["company_type_name"];

			return $content;
		}
		elseif($string2 == "Class of shares - all" || $string2 == "Currency of shares - all" || $string2 == "No of shares issued - all" || $string2 == "Amount of shares issued - all" || $string2 == "Amount of shares paid up - all")
		{
			$get_transaction_client_member_share_capital = $this->db->query('select transaction_client_member_share_capital.*, currency.currency as currency_name, sharetype.sharetype from transaction_client_member_share_capital left join currency on currency.id = transaction_client_member_share_capital.currency_id left join sharetype on sharetype.id = transaction_client_member_share_capital.class_id where transaction_client_member_share_capital.company_code="'.$company_code.'" AND transaction_id="'.$transaction_master_id.'"');

			$get_transaction_client_member_share_capital = $get_transaction_client_member_share_capital->result_array();

			$client_transaction_member_share_capital_id_info = $this->db->query("select transaction_client_member_share_capital.*, sum(transaction_member_shares.number_of_share) as transaction_number_of_shares, sum(transaction_member_shares.amount_share) as transaction_amount, sum(transaction_member_shares.no_of_share_paid) as transaction_number_of_shares_paid, sum(transaction_member_shares.amount_paid) as transaction_paid_up from transaction_client_member_share_capital left join transaction_member_shares on transaction_member_shares.client_member_share_capital_id = transaction_client_member_share_capital.id where transaction_client_member_share_capital.company_code = '".$company_code."' AND transaction_client_member_share_capital.transaction_id='".$transaction_master_id."' group by transaction_client_member_share_capital.id");

			$client_transaction_member_share_capital_id_info = $client_transaction_member_share_capital_id_info->result_array();

			$client_member_share_capital_id_info = $this->db->query("select client_member_share_capital.*, sum(member_shares.number_of_share) as number_of_shares, sum(member_shares.amount_share) as amount, sum(member_shares.no_of_share_paid) as number_of_shares_paid, sum(member_shares.amount_paid) as paid_up 
				from client_member_share_capital 
				left join member_shares on member_shares.client_member_share_capital_id = client_member_share_capital.id AND member_shares.company_code = client_member_share_capital.company_code 
				where client_member_share_capital.company_code = '".$get_transaction_client_member_share_capital[0]["company_code"]."' 
				AND client_member_share_capital.class_id='".$get_transaction_client_member_share_capital[0]["class_id"]."' 
				AND client_member_share_capital.other_class='".$get_transaction_client_member_share_capital[0]["other_class"]."' 
				AND client_member_share_capital.currency_id='".$get_transaction_client_member_share_capital[0]["currency_id"]."' 
				group by client_member_share_capital.id");

	        $client_member_share_capital_id_info = $client_member_share_capital_id_info->result_array();

	        if($string2 == "Currency of shares - all")
	        {
	        	$content = $get_transaction_client_member_share_capital[0]["currency_name"];
	        }
	        else if($string2 == "Class of shares - all")
	        {
		        if($get_transaction_client_member_share_capital[0]["class_id"] == 2)
				{
					$content = $get_transaction_client_member_share_capital[0]["other_class"];
				}
				else
				{
					$content = $get_transaction_client_member_share_capital[0]["sharetype"];
				}
			}
			else if($string2 == "No of shares issued - all")
			{
				if(count($client_member_share_capital_id_info) > 0)
				{
					$content = number_format($client_member_share_capital_id_info[0]["number_of_shares"] + $client_transaction_member_share_capital_id_info[0]["transaction_number_of_shares"]);
				}
				else
				{
					$content = number_format($client_transaction_member_share_capital_id_info[0]["transaction_number_of_shares"]);
				}
			}
			else if($string2 == "Amount of shares issued - all")
			{
				if(count($client_member_share_capital_id_info) > 0)
				{
					$content = $get_transaction_client_member_share_capital[0]["currency_name"].number_format($client_member_share_capital_id_info[0]["amount"] + $client_transaction_member_share_capital_id_info[0]["transaction_amount"]);
				}
				else
				{
					$content = $get_transaction_client_member_share_capital[0]["currency_name"].number_format($client_transaction_member_share_capital_id_info[0]["transaction_amount"]);
				}
				
			}
			else if($string2 == "Amount of shares paid up - all")
			{
				if(count($client_member_share_capital_id_info) > 0)
				{
					$content = $get_transaction_client_member_share_capital[0]["currency_name"].number_format($client_member_share_capital_id_info[0]["paid_up"] + $client_transaction_member_share_capital_id_info[0]["transaction_paid_up"]);
				}
				else
				{
					$content = $get_transaction_client_member_share_capital[0]["currency_name"].number_format($client_transaction_member_share_capital_id_info[0]["transaction_paid_up"]);
				}
			}
			return $content;
		}
		elseif($string2 == "Transferor - share number" || $string2 == "Transferee - name"  || $string2 == "Transferee - ID" || $string2 == "Transferor - name" || $string2 == "Transferor - ID" || $string2 == "Transferee - share number" || $string2 == "Transferee - share number(number)" || $string2 == "Transferee - share type" || $string2 == "Transferee - currency" || $string2 == "Transferee - share amount" || $string2 == "Transferee - certificate" || $string2 == "Transferor - consideration" || $string2 == "Transferor - Address")
		{
			if($document_name == "Ltrs Transfer of Shares")
			{
				$get_member_shares = $this->db->query('select transaction_member_shares.*, client_member_share_capital.class_id, client_member_share_capital.other_class, sharetype.sharetype as class_name, officer.name, officer.identification_no, officer.alternate_address, officer.address_type as officer_address_type, officer.postal_code1 as officer_postal_code1, officer.building_name1 as officer_building_name1, officer.street_name1 as officer_street_name1, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer.postal_code2 as officer_postal_code2, officer.building_name2 as officer_building_name2, officer.street_name2 as officer_street_name2, officer.unit_no3 as officer_unit_no3, officer.unit_no4 as officer_unit_no4, officer.foreign_address1, officer.foreign_address2, officer.foreign_address3, officer_company.company_name, officer_company.register_no, officer_company.address_type as officer_company_address_type, officer_company.company_postal_code, officer_company.company_building_name, officer_company.company_street_name, officer_company.company_unit_no1, officer_company.company_unit_no2, officer_company.company_foreign_address1, officer_company.company_foreign_address2, officer_company.company_foreign_address3, client.company_name as client_company_name, client.registration_no, client.postal_code as client_postal_code, client.building_name as client_building_name, client.street_name as client_street_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, currency.currency as currency_name from transaction_member_shares left join client_member_share_capital on client_member_share_capital.id = transaction_member_shares.client_member_share_capital_id left join sharetype on sharetype.id = client_member_share_capital.class_id left join officer on transaction_member_shares.officer_id = officer.id AND transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id AND transaction_member_shares.field_type = officer_company.field_type left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = "client" AND client.deleted != 1 left join currency on currency.id = client_member_share_capital.currency_id where transaction_member_shares.id="'.$value.'"');

				$get_member_shares = $get_member_shares->result_array();

				for($i = 0; $i < count($get_member_shares); $i++)
				{
					if($string2 == "Transferor - Address")
					{
						$member_address_type = 'Local';
						$foreign_address1 = "";
						$foreign_address2 = "";
						$foreign_address3 = "";

						if($get_member_shares[$i]["name"] != null)
						{
							if($get_member_shares[$i]["alternate_address"] == "1")
							{
								$member_unit_no1 = $get_member_shares[$i]["officer_unit_no3"];
								$member_unit_no2 = $get_member_shares[$i]["officer_unit_no4"];
								$member_street_name = $get_member_shares[$i]["officer_street_name2"];
								$member_building_name = $get_member_shares[$i]["officer_building_name2"];
								$member_postal_code = $get_member_shares[$i]["officer_postal_code2"];
							}
							else
							{
								$member_address_type = $get_member_shares[$i]['officer_address_type'];
								$member_unit_no1 = $get_member_shares[$i]["officer_unit_no1"];
								$member_unit_no2 = $get_member_shares[$i]["officer_unit_no2"];
								$member_street_name = $get_member_shares[$i]["officer_street_name1"];
								$member_building_name = $get_member_shares[$i]["officer_building_name1"];
								$member_postal_code = $get_member_shares[$i]["officer_postal_code1"];
								$foreign_address1 = $get_member_shares[$i]["foreign_address1"];
								$foreign_address2 = $get_member_shares[$i]["foreign_address2"];
								$foreign_address3 = $get_member_shares[$i]["foreign_address3"];
							}
						}
						elseif($get_member_shares[$i]["company_name"] != null)
						{
							$member_address_type = $get_member_shares[$i]['officer_company_address_type'];
							$member_unit_no1 = $get_member_shares[$i]["company_unit_no1"];
							$member_unit_no2 = $get_member_shares[$i]["company_unit_no2"];
							$member_street_name = $get_member_shares[$i]["company_street_name"];
							$member_building_name = $get_member_shares[$i]["company_building_name"];
							$member_postal_code = $get_member_shares[$i]["company_postal_code"];
							$foreign_address1 = $get_member_shares[$i]["company_foreign_address1"];
							$foreign_address2 = $get_member_shares[$i]["company_foreign_address2"];
							$foreign_address3 = $get_member_shares[$i]["company_foreign_address3"];
						}
						elseif($get_member_shares[$i]["client_company_name"] != null)
						{
							$member_unit_no1 = $get_member_shares[$i]["client_unit_no1"];
							$member_unit_no2 = $get_member_shares[$i]["client_unit_no2"];
							$member_street_name = $get_member_shares[$i]["client_street_name"];
							$member_building_name = $get_member_shares[$i]["client_building_name"];
							$member_postal_code = $get_member_shares[$i]["client_postal_code"];
						}

						$address = array(
							'type' 			=> $member_address_type,
							'street_name1' 	=> strtoupper($member_street_name),
							'unit_no1'		=> strtoupper($member_unit_no1),
							'unit_no2'		=> strtoupper($member_unit_no2),
							'building_name1'=> strtoupper($member_building_name),
							'postal_code1'	=> strtoupper($member_postal_code),
							'foreign_address1' => strtoupper($foreign_address1),
							'foreign_address2' => strtoupper($foreign_address2),
							'foreign_address3' => strtoupper($foreign_address3)
						);

						$content = $this->write_address_local_foreign($address, "letter", "big_cap");
						
					}

					if($string2 == "Transferor - name")
					{
						if($get_member_shares[$i]["name"] != null)
						{
							$content = $this->encryption->decrypt($get_member_shares[$i]["name"]);
						}
						else if($get_member_shares[$i]["company_name"] != null)
						{
							$content = $this->encryption->decrypt($get_member_shares[$i]["company_name"]);
						}
						else if($get_member_shares[$i]["client_company_name"] != null)
						{
							$content = $this->encryption->decrypt($get_member_shares[$i]["client_company_name"]);
						}
			
					}

					if($string2 == "Transferor - ID")
					{
						if($get_member_shares[$i]["identification_no"] != null)
						{
							$content = $this->encryption->decrypt($get_member_shares[$i]["identification_no"]);
						}
						else if($get_member_shares[$i]["register_no"] != null)
						{
							$content = $this->encryption->decrypt($get_member_shares[$i]["register_no"]);
						}
						else if($get_member_shares[$i]["registration_no"] != null)
						{
							$content = $this->encryption->decrypt($get_member_shares[$i]["registration_no"]);
						}
					}

					if($string2 == "Transferor - consideration")
					{
						$content = $get_member_shares[$i]["currency_name"].number_format($get_member_shares[$i]["consideration"], 2);
					}
				}
			}
			else
			{
				if($document_name == "Transferee-Share Cert")
				{
					$share_capital = "client_member_share_capital";
				}
				else
				{
					$share_capital = "transaction_client_member_share_capital";
				}

				$q = $this->db->query('select transaction_certificate.*, transaction_certificate.number_of_share, transaction_certificate.amount_share, transaction_certificate.no_of_share_paid, transaction_certificate.amount_paid, officer.identification_no, officer.name, officer.alternate_address, officer.address_type as officer_address_type, officer.postal_code1 as officer_postal_code1, officer.building_name1 as officer_building_name1, officer.street_name1 as officer_street_name1, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer.postal_code2 as officer_postal_code2, officer.building_name2 as officer_building_name2, officer.street_name2 as officer_street_name2, officer.unit_no3 as officer_unit_no3, officer.unit_no4 as officer_unit_no4, officer.foreign_address1, officer.foreign_address2, officer.foreign_address3, officer_company.register_no, officer_company.company_name, officer_company.address_type as officer_company_address_type, officer_company.company_postal_code, officer_company.company_building_name, officer_company.company_street_name, officer_company.company_unit_no1, officer_company.company_unit_no2, officer_company.company_foreign_address1, officer_company.company_foreign_address2, officer_company.company_foreign_address3, client.registration_no, client.company_name as client_company_name, client.postal_code as client_postal_code, client.building_name as client_building_name, client.street_name as client_street_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency, transaction_member_shares.id as transaction_member_shares_id, transaction_member_shares.consideration from transaction_certificate left join officer on transaction_certificate.officer_id = officer.id and transaction_certificate.field_type = officer.field_type left join officer_company on transaction_certificate.officer_id = officer_company.id and transaction_certificate.field_type = officer_company.field_type left join '.$share_capital.' as share_capital on transaction_certificate.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = transaction_certificate.officer_id and transaction_certificate.field_type = "client" and client.deleted = 0 left join transaction_member_shares on  transaction_member_shares.transaction_page_id = transaction_certificate.transaction_page_id and transaction_member_shares.company_code = transaction_certificate.company_code and transaction_member_shares.officer_id = transaction_certificate.officer_id and transaction_member_shares.field_type = transaction_certificate.field_type and transaction_member_shares.transaction_id = transaction_certificate.transaction_id where transaction_certificate.id = "'.$id.'" AND transaction_certificate.company_code="'.$company_code.'" AND transaction_certificate.transaction_page_id = "'.$transaction_master_id.'"');

				$get_member_shares = $q->result_array();

				if($string2 == "Transferor - Address")
				{
					$member_address_type = 'Local';
					$foreign_address1 = "";
					$foreign_address2 = "";
					$foreign_address3 = "";
					
					if($get_member_shares[0]["name"] != null)
					{
						if($get_member_shares[0]["alternate_address"] == "1")
						{
							$member_unit_no1 = $get_member_shares[0]["officer_unit_no3"];
							$member_unit_no2 = $get_member_shares[0]["officer_unit_no4"];
							$member_street_name = $get_member_shares[0]["officer_street_name2"];
							$member_building_name = $get_member_shares[0]["officer_building_name2"];
							$member_postal_code = $get_member_shares[0]["officer_postal_code2"];
						}
						else
						{
							$member_address_type = $get_member_shares[0]['officer_address_type'];
							$member_unit_no1 = $get_member_shares[0]["officer_unit_no1"];
							$member_unit_no2 = $get_member_shares[0]["officer_unit_no2"];
							$member_street_name = $get_member_shares[0]["officer_street_name1"];
							$member_building_name = $get_member_shares[0]["officer_building_name1"];
							$member_postal_code = $get_member_shares[0]["officer_postal_code1"];
							$foreign_address1 = $get_member_shares[0]["foreign_address1"];
							$foreign_address2 = $get_member_shares[0]["foreign_address2"];
							$foreign_address3 = $get_member_shares[0]["foreign_address3"];
						}
					}
					elseif($get_member_shares[0]["company_name"] != null)
					{
						$member_address_type = $get_member_shares[0]['officer_company_address_type'];
						$member_unit_no1 = $get_member_shares[0]["company_unit_no1"];
						$member_unit_no2 = $get_member_shares[0]["company_unit_no2"];
						$member_street_name = $get_member_shares[0]["company_street_name"];
						$member_building_name = $get_member_shares[0]["company_building_name"];
						$member_postal_code = $get_member_shares[0]["company_postal_code"];
						$foreign_address1 = $get_member_shares[0]["company_foreign_address1"];
						$foreign_address2 = $get_member_shares[0]["company_foreign_address2"];
						$foreign_address3 = $get_member_shares[0]["company_foreign_address3"];
					}
					elseif($get_member_shares[0]["client_company_name"] != null)
					{
						$member_unit_no1 = $get_member_shares[0]["client_unit_no1"];
						$member_unit_no2 = $get_member_shares[0]["client_unit_no2"];
						$member_street_name = $get_member_shares[0]["client_street_name"];
						$member_building_name = $get_member_shares[0]["client_building_name"];
						$member_postal_code = $get_member_shares[0]["client_postal_code"];
					}

					$address = array(
						'type' 			=> $member_address_type,
						'street_name1' 	=> strtoupper($member_street_name),
						'unit_no1'		=> strtoupper($member_unit_no1),
						'unit_no2'		=> strtoupper($member_unit_no2),
						'building_name1'=> strtoupper($member_building_name),
						'postal_code1'	=> strtoupper($member_postal_code),
						'foreign_address1' => strtoupper($foreign_address1),
						'foreign_address2' => strtoupper($foreign_address2),
						'foreign_address3' => strtoupper($foreign_address3)
					);

					if($document_name == "Transfer Form" && $document_category_id == 2)
					{
						$content = $this->write_address_local_foreign($address, "comma", "big_cap");
					}
					else
					{
						$content = $this->write_address_local_foreign($address, "letter", "big_cap");
					}
				}
				// if($document_name == "Transferee-Share Cert")
				// {
				// 	$where = 'transaction_certificate.new_certificate_no="'.$value.'" where transaction_member_shares.company_code="'.$company_code.'" AND transaction_member_shares.transaction_page_id="'.$transaction_master_id.'" AND transaction_member_shares.id="'.$id.'"';
				// 	// AND transaction_member_shares.number_of_share > 0
				// 	$share_capital = "client_member_share_capital";
				// }
				// else
				// {
				// 	$where = 'transaction_certificate.company_code="'.$company_code.'"AND transaction_member_shares.officer_id = transaction_certificate.officer_id AND transaction_member_shares.field_type = transaction_certificate.field_type AND transaction_certificate.transaction_page_id="'.$transaction_master_id.'" AND transaction_certificate.number_of_share > 0 where transaction_member_shares.company_code="'.$company_code.'" AND transaction_member_shares.transaction_page_id="'.$transaction_master_id.'" AND transaction_member_shares.id="'.$id.'"';

				// 	$share_capital = "transaction_client_member_share_capital";
				// }

				// $q = $this->db->query('select certificate.*, certificate.number_of_share as number_of_share, certificate.amount_share as amount_share, certificate.no_of_share_paid as no_of_share_paid, certificate.amount_paid as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from certificate left join officer on certificate.officer_id = officer.id and certificate.field_type = officer.field_type left join officer_company on certificate.officer_id = officer_company.id and certificate.field_type = officer_company.field_type left join '.$share_capital.' as share_capital on certificate.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = certificate.officer_id and certificate.field_type = "client" and client.deleted = 0 where certificate.company_code="'.$company_code.'" AND certificate.status = 1 ORDER BY certificate.id'); //and client.firm_id = "'.$this->session->userdata('firm_id').'"

		  //       if ($q->num_rows() > 0) {
		  //           $array_data_share = $q->result_array();
		  //           foreach (($q->result()) as $row) {
		  //               $data_share[] = $row;
		  //           }
		  //       }

				// $q = $this->db->query('select transaction_certificate.*, MAX(transaction_certificate.id) as last_cert_id, (SELECT trans_cert2.transaction_id FROM transaction_certificate as trans_cert2 WHERE trans_cert2.id = MAX(transaction_certificate.id)) as last_transaction_id, (SELECT trans_cert3.certificate_no FROM transaction_certificate as trans_cert3 WHERE trans_cert3.id = MAX(transaction_certificate.id)) as last_certificate_no, sum(transaction_certificate.number_of_share) as number_of_share, sum(transaction_certificate.amount_share) as amount_share, sum(transaction_certificate.no_of_share_paid) as no_of_share_paid, sum(transaction_certificate.amount_paid) as amount_paid, officer.identification_no, officer.name, officer.alternate_address, officer.address_type as officer_address_type, officer.postal_code1 as officer_postal_code1, officer.building_name1 as officer_building_name1, officer.street_name1 as officer_street_name1, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer.postal_code2 as officer_postal_code2, officer.building_name2 as officer_building_name2, officer.street_name2 as officer_street_name2, officer.unit_no3 as officer_unit_no3, officer.unit_no4 as officer_unit_no4, officer.foreign_address1, officer.foreign_address2, officer.foreign_address3, officer_company.register_no, officer_company.company_name, officer_company.address_type as officer_company_address_type, officer_company.company_postal_code, officer_company.company_building_name, officer_company.company_street_name, officer_company.company_unit_no1, officer_company.company_unit_no2, officer_company.company_foreign_address1, officer_company.company_foreign_address2, officer_company.company_foreign_address3, client.registration_no, client.company_name as client_company_name, client.postal_code as client_postal_code, client.building_name as client_building_name, client.street_name as client_street_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency, transaction_member_shares.id as transaction_member_shares_id, transaction_member_shares.consideration from transaction_certificate left join officer on transaction_certificate.officer_id = officer.id and transaction_certificate.field_type = officer.field_type left join officer_company on transaction_certificate.officer_id = officer_company.id and transaction_certificate.field_type = officer_company.field_type left join '.$share_capital.' as share_capital on transaction_certificate.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = transaction_certificate.officer_id and transaction_certificate.field_type = "client" and client.deleted = 0 left join transaction_member_shares on transaction_member_shares.transaction_page_id = transaction_certificate.transaction_page_id and transaction_member_shares.company_code = transaction_certificate.company_code and transaction_member_shares.officer_id = transaction_certificate.officer_id and transaction_member_shares.field_type = transaction_certificate.field_type and transaction_member_shares.transaction_id = transaction_certificate.transaction_id where transaction_certificate.company_code="'.$company_code.'" AND transaction_certificate.transaction_page_id = "'.$transaction_master_id.'" GROUP BY transaction_certificate.field_type, transaction_certificate.officer_id, transaction_certificate.previous_certificate_id HAVING sum(transaction_certificate.number_of_share) != 0 ORDER BY transaction_certificate.client_member_share_capital_id'); // and client.firm_id = "'.$this->session->userdata('firm_id').'"

		  //       if ($q->num_rows() > 0) {
		  //           $array_data_change_share = $q->result_array();
		  //           foreach (($q->result()) as $row) {
		  //               $data_change_share[] = $row;
		  //           }
		  //       }

		        // for($d = 0; $d < count($array_data_change_share); $d++)
		        // {
		        //     $number_of_share = $array_data_change_share[$d]["number_of_share"];
		        //     $amount_share = $array_data_change_share[$d]["amount_share"];
		        //     $no_of_share_paid = $array_data_change_share[$d]["no_of_share_paid"];
		        //     $amount_paid = $array_data_change_share[$d]["amount_paid"];

		        //     for($f = 0; $f < count($array_data_share); $f++)
		        //     {
		        //         $latest_share_number_for_cert[$d]['id'] = $array_data_change_share[$d]["transaction_member_shares_id"];
		        //         $latest_share_number_for_cert[$d]['company_code'] = $array_data_change_share[$d]["company_code"];
		        //         $latest_share_number_for_cert[$d]['certificate_id'] = $array_data_change_share[$d]["id"];
		        //         $latest_share_number_for_cert[$d]['transaction_page_id'] = $array_data_change_share[$d]["transaction_page_id"];
		        //         $latest_share_number_for_cert[$d]['identification_no'] = $array_data_change_share[$d]["identification_no"];
		        //         $latest_share_number_for_cert[$d]['name'] = $array_data_change_share[$d]["name"];
		        //         $latest_share_number_for_cert[$d]['alternate_address'] = $array_data_change_share[$d]["alternate_address"];
		        //         $latest_share_number_for_cert[$d]['officer_address_type'] = $array_data_change_share[$d]["officer_address_type"];
		        //         $latest_share_number_for_cert[$d]['officer_postal_code1'] = $array_data_change_share[$d]["officer_postal_code1"];
		        //         $latest_share_number_for_cert[$d]['officer_building_name1'] = $array_data_change_share[$d]["officer_building_name1"];
		        //         $latest_share_number_for_cert[$d]['officer_street_name1'] = $array_data_change_share[$d]["officer_street_name1"];
		        //         $latest_share_number_for_cert[$d]['officer_unit_no1'] = $array_data_change_share[$d]["officer_unit_no1"];
		        //         $latest_share_number_for_cert[$d]['officer_unit_no2'] = $array_data_change_share[$d]["officer_unit_no2"];
		        //         $latest_share_number_for_cert[$d]['officer_postal_code2'] = $array_data_change_share[$d]["officer_postal_code2"];
		        //         $latest_share_number_for_cert[$d]['officer_building_name2'] = $array_data_change_share[$d]["officer_building_name2"];
		        //         $latest_share_number_for_cert[$d]['officer_street_name2'] = $array_data_change_share[$d]["officer_street_name2"];
		        //         $latest_share_number_for_cert[$d]['officer_unit_no3'] = $array_data_change_share[$d]["officer_unit_no3"];
		        //         $latest_share_number_for_cert[$d]['officer_unit_no4'] = $array_data_change_share[$d]["officer_unit_no4"];
		        //         $latest_share_number_for_cert[$d]['foreign_address1'] = $array_data_change_share[$d]["foreign_address1"];
		        //         $latest_share_number_for_cert[$d]['foreign_address2'] = $array_data_change_share[$d]["foreign_address2"];
		        //         $latest_share_number_for_cert[$d]['foreign_address3'] = $array_data_change_share[$d]["foreign_address3"];
		        //         $latest_share_number_for_cert[$d]['register_no'] = $array_data_change_share[$d]["register_no"];
		        //         $latest_share_number_for_cert[$d]['company_name'] = $array_data_change_share[$d]["company_name"];

		        //         $latest_share_number_for_cert[$d]['officer_company_address_type'] = $array_data_change_share[$d]["officer_company_address_type"];
		        //         $latest_share_number_for_cert[$d]['company_postal_code'] = $array_data_change_share[$d]["company_postal_code"];
		        //         $latest_share_number_for_cert[$d]['company_building_name'] = $array_data_change_share[$d]["company_building_name"];
		        //         $latest_share_number_for_cert[$d]['company_street_name'] = $array_data_change_share[$d]["company_street_name"];
		        //         $latest_share_number_for_cert[$d]['company_unit_no1'] = $array_data_change_share[$d]["company_unit_no1"];
		        //         $latest_share_number_for_cert[$d]['company_unit_no2'] = $array_data_change_share[$d]["company_unit_no2"];
		        //         $latest_share_number_for_cert[$d]['company_foreign_address1'] = $array_data_change_share[$d]["company_foreign_address1"];
		        //         $latest_share_number_for_cert[$d]['company_foreign_address2'] = $array_data_change_share[$d]["company_foreign_address2"];
		        //         $latest_share_number_for_cert[$d]['company_foreign_address3'] = $array_data_change_share[$d]["company_foreign_address3"];
		        //         $latest_share_number_for_cert[$d]['registration_no'] = $array_data_change_share[$d]["registration_no"];
		        //         $latest_share_number_for_cert[$d]['client_company_name'] = $array_data_change_share[$d]["client_company_name"];
		        //         $latest_share_number_for_cert[$d]['client_postal_code'] = $array_data_change_share[$d]["client_postal_code"];
		        //         $latest_share_number_for_cert[$d]['client_building_name'] = $array_data_change_share[$d]["client_building_name"];
		        //         $latest_share_number_for_cert[$d]['client_street_name'] = $array_data_change_share[$d]["client_street_name"];
		        //         $latest_share_number_for_cert[$d]['client_unit_no1'] = $array_data_change_share[$d]["client_unit_no1"];
		        //         $latest_share_number_for_cert[$d]['client_unit_no2'] = $array_data_change_share[$d]["client_unit_no2"];
		        //         $latest_share_number_for_cert[$d]['sharetype'] = $array_data_change_share[$d]["sharetype"];
		        //         $latest_share_number_for_cert[$d]['officer_id'] = $array_data_change_share[$d]["officer_id"];
		        //         $latest_share_number_for_cert[$d]['field_type'] = $array_data_change_share[$d]["field_type"];
		        //         $latest_share_number_for_cert[$d]['currency'] = $array_data_change_share[$d]["currency"];
		        //         $latest_share_number_for_cert[$d]['other_class'] = $array_data_change_share[$d]["other_class"];
		        //         $latest_share_number_for_cert[$d]['certificate_no'] = $array_data_change_share[$d]["certificate_no"];
		        //         $latest_share_number_for_cert[$d]['new_certificate_no'] = $array_data_change_share[$d]["new_certificate_no"];
		        //         $latest_share_number_for_cert[$d]['class_id'] = $array_data_change_share[$d]["class_id"];
		        //         $latest_share_number_for_cert[$d]['class_name'] = $array_data_change_share[$d]["sharetype"];
		        //         $latest_share_number_for_cert[$d]['currency_name'] = $array_data_change_share[$d]["currency"];
		        //         $latest_share_number_for_cert[$d]['consideration'] = $array_data_change_share[$d]["consideration"];
		                
		        //         if($array_data_change_share[$d]["officer_id"] == $array_data_share[$f]["officer_id"] && $array_data_change_share[$d]["field_type"] == $array_data_share[$f]["field_type"] && 0 > $array_data_change_share[$d]["number_of_share"] && $array_data_change_share[$d]["previous_certificate_id"] == $array_data_share[$f]["id"])
		        //         {
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
		        //                 //break;
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

				// $get_member_shares = $array_data_change_share; //$latest_share_number_for_cert;
				// return $get_member_shares;
				// for($i = 0; $i < count($get_member_shares); $i++)
				// {
				// 	if($get_member_shares[$i]["number_of_share"] > 0 && $latest_share_number_for_cert[$i]["new_certificate_no"] == $value)
				// 	{
				// 		if($string2 == "Transferor - share number")
				// 		{
				// 			$content = strtoupper($this->convert_number_to_word_model->convert_number_to_words($get_member_shares[$i]["number_of_share"]))." (".number_format($get_member_shares[$i]["number_of_share"]).")";
				// 		}

				// 		if($string2 == "Transferee - share number" || $string2 == "Transferee - share number(number)")
				// 		{
				// 			if($document_name == "Transferee-Share Cert" && $string2 == "Transferee - share number(number)")
				// 			{
				// 				$content = strtoupper(number_format($get_member_shares[$i]["number_of_share"]));
				// 			}
				// 			else
				// 			{
				// 				$content = strtoupper($this->convert_number_to_word_model->convert_number_to_words($get_member_shares[$i]["number_of_share"]))." (".number_format($get_member_shares[$i]["number_of_share"]).")";
				// 			}
				// 		}

				// 		if($string2 == "Transferee - name")
				// 		{
				// 			if($get_member_shares[$i]["name"] != null)
				// 			{
				// 				$content = $this->encryption->decrypt($get_member_shares[$i]["name"]);
				// 			}
				// 			else if($get_member_shares[$i]["company_name"] != null)
				// 			{
				// 				$content = $this->encryption->decrypt($get_member_shares[$i]["company_name"]);
				// 			}
				// 			else if($get_member_shares[$i]["client_company_name"] != null)
				// 			{
				// 				$content = $this->encryption->decrypt($get_member_shares[$i]["client_company_name"]);
				// 			}
				
				// 		}

				// 		if($string2 == "Transferee - ID")
				// 		{
				// 			if($get_member_shares[$i]["identification_no"] != null)
				// 			{
				// 				$content = $this->encryption->decrypt($get_member_shares[$i]["identification_no"]);
				// 			}
				// 			else if($get_member_shares[$i]["register_no"] != null)
				// 			{
				// 				$content = $this->encryption->decrypt($get_member_shares[$i]["register_no"]);
				// 			}
				// 			else if($get_member_shares[$i]["registration_no"] != null)
				// 			{
				// 				$content = $this->encryption->decrypt($get_member_shares[$i]["registration_no"]);
				// 			}
				// 		}

				// 		if($string2 == "Transferee - share type")
				// 		{
				// 			if($get_member_shares[$i]["class_id"] == 2)
				// 			{
				// 				$content = $get_member_shares[$i]["other_class"];
				// 			}
				// 			else
				// 			{
				// 				$content = $get_member_shares[$i]["class_name"];
				// 			}
				// 		}

				// 		if($string2 == "Transferee - currency")
				// 		{
				// 			$content = $get_member_shares[$i]["currency_name"];
				// 		}

				// 		if($string2 == "Transferee - share amount")
				// 		{
				// 			$content = $get_member_shares[$i]["amount_share"];
				// 		}

				// 		if($string2 == "Transferee - certificate")
				// 		{
				// 			$content = $get_member_shares[$i]["new_certificate_no"];
				// 		}
				// 	}
				// 	else if(0 >= $get_member_shares[$i]["number_of_share"] || ($get_member_shares[$i]["number_of_share"] >= 0 && $document_name == "Transferee-Share Cert"))
				// 	{
				// 		if($string2 == "Transferor - Address" && $id == $get_member_shares[$i]["id"])
				// 		{
				// 			$member_address_type = 'Local';
				// 			$foreign_address1 = "";
				// 			$foreign_address2 = "";
				// 			$foreign_address3 = "";
							
				// 			if($get_member_shares[$i]["name"] != null)
				// 			{
				// 				if($get_member_shares[$i]["alternate_address"] == "1")
				// 				{
				// 					$member_unit_no1 = $get_member_shares[$i]["officer_unit_no3"];
				// 					$member_unit_no2 = $get_member_shares[$i]["officer_unit_no4"];
				// 					$member_street_name = $get_member_shares[$i]["officer_street_name2"];
				// 					$member_building_name = $get_member_shares[$i]["officer_building_name2"];
				// 					$member_postal_code = $get_member_shares[$i]["officer_postal_code2"];
				// 				}
				// 				else
				// 				{
				// 					$member_address_type = $get_member_shares[$i]['officer_address_type'];
				// 					$member_unit_no1 = $get_member_shares[$i]["officer_unit_no1"];
				// 					$member_unit_no2 = $get_member_shares[$i]["officer_unit_no2"];
				// 					$member_street_name = $get_member_shares[$i]["officer_street_name1"];
				// 					$member_building_name = $get_member_shares[$i]["officer_building_name1"];
				// 					$member_postal_code = $get_member_shares[$i]["officer_postal_code1"];
				// 					$foreign_address1 = $get_member_shares[$i]["foreign_address1"];
				// 					$foreign_address2 = $get_member_shares[$i]["foreign_address2"];
				// 					$foreign_address3 = $get_member_shares[$i]["foreign_address3"];
				// 				}
				// 			}
				// 			elseif($get_member_shares[$i]["company_name"] != null)
				// 			{
				// 				$member_address_type = $get_member_shares[$i]['officer_company_address_type'];
				// 				$member_unit_no1 = $get_member_shares[$i]["company_unit_no1"];
				// 				$member_unit_no2 = $get_member_shares[$i]["company_unit_no2"];
				// 				$member_street_name = $get_member_shares[$i]["company_street_name"];
				// 				$member_building_name = $get_member_shares[$i]["company_building_name"];
				// 				$member_postal_code = $get_member_shares[$i]["company_postal_code"];
				// 				$foreign_address1 = $get_member_shares[$i]["company_foreign_address1"];
				// 				$foreign_address2 = $get_member_shares[$i]["company_foreign_address2"];
				// 				$foreign_address3 = $get_member_shares[$i]["company_foreign_address3"];
				// 			}
				// 			elseif($get_member_shares[$i]["client_company_name"] != null)
				// 			{
				// 				$member_unit_no1 = $get_member_shares[$i]["client_unit_no1"];
				// 				$member_unit_no2 = $get_member_shares[$i]["client_unit_no2"];
				// 				$member_street_name = $get_member_shares[$i]["client_street_name"];
				// 				$member_building_name = $get_member_shares[$i]["client_building_name"];
				// 				$member_postal_code = $get_member_shares[$i]["client_postal_code"];
				// 			}

				// 			$address = array(
				// 				'type' 			=> $member_address_type,
				// 				'street_name1' 	=> strtoupper($member_street_name),
				// 				'unit_no1'		=> strtoupper($member_unit_no1),
				// 				'unit_no2'		=> strtoupper($member_unit_no2),
				// 				'building_name1'=> strtoupper($member_building_name),
				// 				'postal_code1'	=> strtoupper($member_postal_code),
				// 				'foreign_address1' => strtoupper($foreign_address1),
				// 				'foreign_address2' => strtoupper($foreign_address2),
				// 				'foreign_address3' => strtoupper($foreign_address3)
				// 			);

				// 			//$content = $this->write_address_local_foreign($address, "letter", "big_cap");
				// 			$content = $get_member_shares;
				// 		}

				// 		if($string2 == "Transferor - name")
				// 		{
				// 			if($get_member_shares[$i]["name"] != null)
				// 			{
				// 				$content = $this->encryption->decrypt($get_member_shares[$i]["name"]);
				// 			}
				// 			else if($get_member_shares[$i]["company_name"] != null)
				// 			{
				// 				$content = $this->encryption->decrypt($get_member_shares[$i]["company_name"]);
				// 			}
				// 			else if($get_member_shares[$i]["client_company_name"] != null)
				// 			{
				// 				$content = $this->encryption->decrypt($get_member_shares[$i]["client_company_name"]);
				// 			}
				
				// 		}

				// 		if($string2 == "Transferor - ID")
				// 		{
				// 			if($get_member_shares[$i]["identification_no"] != null)
				// 			{
				// 				$content = $this->encryption->decrypt($get_member_shares[$i]["identification_no"]);
				// 			}
				// 			else if($get_member_shares[$i]["register_no"] != null)
				// 			{
				// 				$content = $this->encryption->decrypt($get_member_shares[$i]["register_no"]);
				// 			}
				// 			else if($get_member_shares[$i]["registration_no"] != null)
				// 			{
				// 				$content = $this->encryption->decrypt($get_member_shares[$i]["registration_no"]);
				// 			}
				// 		}

				// 		if($string2 == "Transferor - consideration")
				// 		{
				// 			$content = $get_member_shares[$i]["currency_name"].number_format($get_member_shares[$i]["consideration"], 2);
				// 		}
				// 	}
				// }
			}

			return $content;
		}
		elseif($string2 == "Director Signature 1's identification no")
		{
			$content = '';

			if($document_name == "AGM & AR - Annual Return" || $document_name == "Declaration For Controller" || $document_name == "AGM & AR - Annual Return (Audit)" || $document_name == "AR document" || $document_name == "filing XBRL")
			{
				$director_signature_1_result = $this->db->query("select director_signature_1 from client_signing_info where company_code='".$company_code."'");
			
				if ($director_signature_1_result->num_rows() > 0) {

                	$director_signature_1_result = $director_signature_1_result->result_array();

                	$client_officer = $this->db->query("select * from client_officers where id='".$director_signature_1_result[0]["director_signature_1"]."'");

            		$client_officer = $client_officer->result_array();

            		$officer_result = $this->db->query("select * from officer where id='".$client_officer[0]["officer_id"]."' AND field_type='".$client_officer[0]["field_type"]."'");

            		$officer_result = $officer_result->result_array();

            		$name = $this->encryption->decrypt($officer_result[0]["identification_no"]);
                }

                $content = $name;
            }

            return $content;
		}
		elseif($string2 == "have been registered / have not been registered / have not taken place")
		{
			$content = "";
			$share_transfer = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

			$share_transfer = $share_transfer->result_array();

			if(count($share_transfer) > 0)
			{
				if($share_transfer[0]["agm_share_transfer_id"] == 1)
				{
					$content = "have been registered";
				}
				else if($share_transfer[0]["agm_share_transfer_id"] == 2)
				{
					$content = "have not been registered";
				}
				else if($share_transfer[0]["agm_share_transfer_id"] == 3)
				{
					$content = "have not taken place";
				}
			}
			return $content;
		}
		elseif($string2 == "the last annual return / the incorporation of the company" || $string2 == "from the date of incorporation / since the end of the previous financial year")
		{
			$content = "";

			$first_agm = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

			$first_agm = $first_agm->result_array();

			if(count($first_agm) > 0)
			{
				if($string2 == "the last annual return / the incorporation of the company")
				{
					if($first_agm[0]["is_first_agm_id"] == 1)
					{
						$content = "the incorporation of the company";
					}
					else if($first_agm[0]["is_first_agm_id"] == 2)
					{
						$content = "the last main return";
					}
					else
					{
						$content = "___________________";
					}
				}
				else if($string2 == "from the date of incorporation / since the end of the previous financial year")
				{
					if($first_agm[0]["is_first_agm_id"] == 1)
					{
						$content = "from the date of incorporation";
					}
					else if($first_agm[0]["is_first_agm_id"] == 2)
					{
						$content = "since the end of the previous financial year";
					}
				}
			}

			return $content;
		}
		elseif($string2 == "annual general meeting / by way of a resolution")
		{
			$content = "";
			$agm_dispense = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

			$agm_dispense = $agm_dispense->result_array();

			if(count($agm_dispense) > 0)
			{
				if($agm_dispense[0]["agm_date"] == "dispensed")
				{
					$content = "by way of a resolution";
				}
				else if($agm_dispense[0]["agm_date"] != "dispensed")
				{
					$content = "annual general meeting";
				}
			}
			return $content;
		}
		elseif($string2 == "where the register of controllers is kept")
		{
			$content = "";
			$where_the_register_of_controllers_is_kept = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

			$where_the_register_of_controllers_is_kept = $where_the_register_of_controllers_is_kept->result_array();

			if(count($where_the_register_of_controllers_is_kept) > 0)
			{
				if($where_the_register_of_controllers_is_kept[0]["register_of_controller"] == "Exempted from the requirement to keep a register")
				{
					$content = "exempted from the requirement to keep a register";
				}
				else if($where_the_register_of_controllers_is_kept[0]["register_of_controller"] == "Registered office of a registered filing agent appointed by the company")
				{
					$content = "not exempted from the requirement to keep a register of controllers and the register of controller is kept at the registered office of a registered filing agent appointed by the company";
				}
				else
				{
					$content = "not exempted from the requirement to keep a register of controllers and the register of controller is kept at the registered office of the company";
				}
			}
			return $content;
		}
		elseif($string2 == "where the register of nominee directors is kept")
		{
			$content = "";
			$where_the_register_of_nominee_directors_is_kept = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

			$where_the_register_of_nominee_directors_is_kept = $where_the_register_of_nominee_directors_is_kept->result_array();

			if(count($where_the_register_of_nominee_directors_is_kept) > 0)
			{
				if($where_the_register_of_nominee_directors_is_kept[0]["register_of_nominee_director"] == "Exempted from the requirement to keep a register")
				{
					$content = "exempted from the requirement to keep a register";
				}
				else if($where_the_register_of_nominee_directors_is_kept[0]["register_of_nominee_director"] == "Registered office of a registered filing agent appointed by the company")
				{
					$content = "not exempted from the requirement to keep a register of nominee directors and the register of nominee directors is kept at the registered office of a registered filing agent appointed by the company";
				}
				else
				{
					$content = "not exempted from the requirement to keep a register of nominee directors and the register of nominee directors is kept at the registered office of the company";
				}
			}
			return $content;
		}
		elseif($string2 == "not / exempt")
		{
			$content = "";
			$small_company = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

			$small_company = $small_company->result_array();

			if(count($small_company) > 0)
			{
				if($small_company[0]["small_company"] == 1)
				{
					$content = "exempted";
				}
				else if($small_company[0]["small_company"] == 2)
				{
					$content = "not exempted";
				}
			}
			return $content;
		}
		elseif($string2 == "not / considered")
		{
			$content = "";

			$small_company1 = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

			$small_company1 = $small_company1->result_array();

			if(count($small_company1) > 0)
			{
				if($small_company1[0]["small_company"] == 1)
				{
					$content = "considered";
				}
				else if($small_company1[0]["small_company"] == 2)
				{
					$content = "not";
				}
			}
			return $content;
		}
		elseif($string2 == "tick full set" || $string2 == "tick highlight" || $string2 == "tick filing")
		{
			$content = "";

			$xbrl = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

			$xbrl = $xbrl->result_array();

			if(count($xbrl) > 0)
			{
				if($xbrl[0]["xbrl"] == 1 && $string2 == "tick full set")
				{
					$content = "X";
				}
				else if($xbrl[0]["xbrl"] == 2 && $string2 == "tick highlight")
				{
					$content = "X";
				}
				else if($xbrl[0]["xbrl"] == 3 && $string2 == "tick filing")
				{
					$content = "X";
				}
				else
				{
					$content = "";
				}
			}
			return $content;
		}
		elseif($string2 == "a duly audited / an unaudited profit" || $string2 == "un/audited" || $string2 == "un/audited(big_cap)")
		{
			$content = "";

			$audited_fs = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

			$audited_fs = $audited_fs->result_array();

			if(count($audited_fs) > 0)
			{
				if($audited_fs[0]["audited_fs"] == 1)
				{
					if($document_name == "AGM & AR - DRIW" || $document_name == "AGM & AR - Notice for AGM" || $document_name == "AGM & AR - Minutes of AGM" || $document_name == "AGM - DRIW" || $document_name == "AGM - DRIW (Dormant)" || $document_name == "AGM Voting")
					{
						if($string2 == "un/audited")
						{
							$content = "Audited";
						}
						else
						{
							$content = "AUDITED";
						}
					}
					elseif($document_name == "AGM & AR - Annual Return")
					{
						$content = "a duly audited profit";
					}
					else
					{
						$content = "a duly audited";
					}
					
				}
				else if($audited_fs[0]["audited_fs"] == 2)
				{
					if($document_name == "AGM & AR - DRIW" || $document_name == "AGM & AR - Notice for AGM" || $document_name == "AGM & AR - Minutes of AGM" || $document_name == "AGM - DRIW" || $document_name == "AGM - DRIW (Dormant)" || $document_name == "AGM Voting")
					{
						if($string2 == "un/audited")
						{
							$content = "Unaudited";
						}
						else
						{
							$content = "UNAUDITED";
						}
					}
					else
					{
						$content = "an unaudited profit";
					}
				}
			}
			return $content;
		}
		elseif($string2 == "Auditors ID - resigning")
		{
			$get_auditor_id = $this->db->query("select officer_company.register_no from transaction_client_officers left join officer_company on transaction_client_officers.officer_id = officer_company.id AND transaction_client_officers.field_type = officer_company.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.appoint_resign_flag = 'resign' AND transaction_client_officers.position = 5 AND transaction_client_officers.id='".$id."'");

			$get_auditor_id = $get_auditor_id->result_array();

			$content = $this->encryption->decrypt($get_auditor_id[0]["register_no"]);

			return $content;
		}
		elseif($string2 == "Auditors name - resigning")
		{
			$get_auditor_name = $this->db->query("select officer_company.company_name from transaction_client_officers left join officer_company on transaction_client_officers.officer_id = officer_company.id AND transaction_client_officers.field_type = officer_company.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.appoint_resign_flag = 'resign' AND transaction_client_officers.position = 5 AND transaction_client_officers.id='".$id."'");

			$get_auditor_name = $get_auditor_name->result_array();

			$content = $this->encryption->decrypt($get_auditor_name[0]["company_name"]);

			return $content;
		}
		elseif($string2 == "Auditors ID - appointment")
		{
			$get_auditor_id = $this->db->query("select officer_company.register_no from transaction_client_officers left join officer_company on transaction_client_officers.officer_id = officer_company.id AND transaction_client_officers.field_type = officer_company.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.appoint_resign_flag = 'appoint' AND transaction_client_officers.position = 5 AND transaction_client_officers.id='".$id."'");

			$get_auditor_id = $get_auditor_id->result_array();

			$content = $this->encryption->decrypt($get_auditor_id[0]["register_no"]);

			return $content;
		}
		elseif($string2 == "Auditors name - appointment")
		{
			$get_auditor_name = $this->db->query("select officer_company.company_name from transaction_client_officers left join officer_company on transaction_client_officers.officer_id = officer_company.id AND transaction_client_officers.field_type = officer_company.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.appoint_resign_flag = 'appoint' AND transaction_client_officers.position = 5 AND transaction_client_officers.id='".$id."'");

			$get_auditor_name = $get_auditor_name->result_array();

			$content = $this->encryption->decrypt($get_auditor_name[0]["company_name"]);

			return $content;
		}
		elseif($string2 == "Auditor name")
		{
			$get_auditor_name = $this->db->query("select officer_company.company_name from client_officers left join officer_company on client_officers.officer_id = officer_company.id AND client_officers.field_type = officer_company.field_type where client_officers.company_code='".$company_code."' AND client_officers.date_of_cessation = '' AND client_officers.position = 5");

			$get_auditor_name = $get_auditor_name->result_array();

			$content = $this->encryption->decrypt($get_auditor_name[0]["company_name"]);

			return $content;
		}
		elseif($string2 == "Auditors Company Venue")
		{
			$content = '<w:t xml:space="preserve">                    </w:t>';

			$transaction_meeting_date_info = $this->db->query("SELECT officer_company.* from transaction_client_officers LEFT JOIN officer_company on transaction_client_officers.officer_id = officer_company.id AND transaction_client_officers.field_type = officer_company.field_type WHERE transaction_client_officers.position=5 AND transaction_client_officers.officer_id=".$id);

			$transaction_meeting_date_info = $transaction_meeting_date_info->result_array();

			if(count($transaction_meeting_date_info) > 0 && $transaction_meeting_date_info[0]["address_type"] != null)
			{
				$meeting_unit_no1 = "";
				$meeting_unit_no2 = "";
				$meeting_street_name = "";
				$meeting_building_name = "";
				$meeting_postal_code = "";
				$foreign_address1 = "";
				$foreign_address2 = "";
				$foreign_address3 = "";

				if($transaction_meeting_date_info[0]["address_type"] == "Local")
				{
					$meeting_address_type = "Local";
					$meeting_unit_no1 = $transaction_meeting_date_info[0]["company_unit_no1"];
					$meeting_unit_no2 = $transaction_meeting_date_info[0]["company_unit_no2"];
					$meeting_street_name = $transaction_meeting_date_info[0]["company_street_name"];
					$meeting_building_name = $transaction_meeting_date_info[0]["company_building_name"];
					$meeting_postal_code = $transaction_meeting_date_info[0]["company_postal_code"];
				}
				elseif($transaction_meeting_date_info[0]["address_type"] == "Foreign")
				{
					$meeting_address_type = "Foreign";
					$foreign_address1 = $transaction_meeting_date_info[0]["company_foreign_address1"];
					$foreign_address2 = $transaction_meeting_date_info[0]["company_foreign_address2"];
					$foreign_address3 = $transaction_meeting_date_info[0]["company_foreign_address3"];
				}
				
				$address = array(
					'type' 			=> $meeting_address_type,
					'street_name1' 	=> strtoupper($meeting_street_name),
					'unit_no1'		=> strtoupper($meeting_unit_no1),
					'unit_no2'		=> strtoupper($meeting_unit_no2),
					'building_name1'=> strtoupper($meeting_building_name),
					'postal_code1'	=> strtoupper($meeting_postal_code),
					'foreign_address1' => strtoupper($foreign_address1),
					'foreign_address2' => strtoupper($foreign_address2),
					'foreign_address3' => strtoupper($foreign_address3)
				);

				$content = $this->write_address_local_foreign($address, "comma", "big_cap");
			}
			return $content;
		}
		// elseif($string2 == "Secretarys address - appointment" || $string2 == "Secretarys address - resignation")
		// {
		// 	$secretary_address_type = 'Local';
		// 	$foreign_address1 = "";
		// 	$foreign_address2 = "";
		// 	$foreign_address3 = "";

		// 	$get_secretarys_address = $this->db->query("SELECT officer.* from transaction_client_officers LEFT JOIN officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type WHERE transaction_client_officers.position=4 AND transaction_client_officers.id=".$id);

		// 	$get_secretarys_address = $get_secretarys_address->result_array();

		// 	if($get_secretarys_address[0]["alternate_address"] == "1")
		// 	{
		// 		$secretary_unit_no1 = $get_secretarys_address[0]["unit_no3"];
		// 		$secretary_unit_no2 = $get_secretarys_address[0]["unit_no4"];
		// 		$secretary_street_name = $get_secretarys_address[0]["street_name2"];
		// 		$secretary_building_name = $get_secretarys_address[0]["building_name2"];
		// 		$secretary_postal_code = $get_secretarys_address[0]["postal_code2"];
		// 	}
		// 	else
		// 	{
		// 		$secretary_address_type = $get_secretarys_address[0]['address_type'];
		// 		$secretary_unit_no1 = $get_secretarys_address[0]["unit_no1"];
		// 		$secretary_unit_no2 = $get_secretarys_address[0]["unit_no2"];
		// 		$secretary_street_name = $get_secretarys_address[0]["street_name1"];
		// 		$secretary_building_name = $get_secretarys_address[0]["building_name1"];
		// 		$secretary_postal_code = $get_secretarys_address[0]["postal_code1"];
		// 		$foreign_address1 = $get_secretarys_address[0]["foreign_address1"];
		// 		$foreign_address2 = $get_secretarys_address[0]["foreign_address2"];
		// 		$foreign_address3 = $get_secretarys_address[0]["foreign_address3"];
		// 	}
			
		// 	$address = array(
		// 		'type' 			=> $secretary_address_type,
		// 		'street_name1' 	=> strtoupper($secretary_street_name),
		// 		'unit_no1'		=> strtoupper($secretary_unit_no1),
		// 		'unit_no2'		=> strtoupper($secretary_unit_no2),
		// 		'building_name1'=> strtoupper($secretary_building_name),
		// 		'postal_code1'	=> strtoupper($secretary_postal_code),
		// 		'foreign_address1' => strtoupper($foreign_address1),
		// 		'foreign_address2' => strtoupper($foreign_address2),
		// 		'foreign_address3' => strtoupper($foreign_address3)
		// 	);

		// 	if($document_name == "Ltr of Indemnity" || $document_name == "Ltr - Resignation of Co Sec")
		// 	{
		// 		$content = $this->write_address_local_foreign($address, "letter", "big_cap");
		// 	}
		// 	else
		// 	{
		// 		$content = $this->write_address_local_foreign($address, "comma", "big_cap");
		// 	}
			
		// 	return $content;
		// }
		elseif($string2 == "Directors name and IC")
		{
			$get_directors_info = $value;
			$number_directors_resign = $this->db->query("select transaction_resign_officer_reason.reason_selected from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type left join transaction_resign_officer_reason on transaction_resign_officer_reason.transaction_client_officers_id = transaction_client_officers.id where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1 AND transaction_resign_officer_reason.reason_selected != 'NULL'");
			$number_directors_resign = $number_directors_resign->result_array();

			if(count($get_directors_info) > 0)
			{
				$num_of_director = 0;
				for($count = 0 ; $count < count($get_directors_info) ; $count++)
				{
					$get_directors = $this->db->query("select officer.name, officer.identification_no, transaction_resign_officer_reason.reason_selected from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type left join transaction_resign_officer_reason on transaction_resign_officer_reason.transaction_client_officers_id = transaction_client_officers.id where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1 AND transaction_client_officers.id='".$get_directors_info[$count]["id"]."'");
					$get_directors = $get_directors->result_array();

					if($get_directors[0]["reason_selected"] != NULL)
					{
						$director_name_ic = $this->encryption->decrypt($get_directors[0]["name"]) . " (Identification No. " . $this->encryption->decrypt($get_directors[0]["identification_no"]) . ")";

						if($num_of_director == 0)
						{
							$resignation_director_name_ic_temp = $director_name_ic;
						}
						elseif($num_of_director == count($number_directors_resign) - 1)
						{
							$resignation_director_name_ic_temp = $resignation_director_name_ic_temp . " and " . $director_name_ic;
						}
						else
						{
							$resignation_director_name_ic_temp = $resignation_director_name_ic_temp . ", " . $director_name_ic;
						}
						$num_of_director++;
					}
				}
			}
			else
			{
				$resignation_director_name_ic_temp = "";
			}

			return $resignation_director_name_ic_temp;
		}
		elseif($string2 == "Directors name - resigning")
		{
			$get_directors = $this->db->query("select officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1 AND transaction_client_officers.id='".$id."'");

			$get_directors = $get_directors->result_array();

			$content = $this->encryption->decrypt($get_directors[0]["name"]);

			// print_r(array($content));

			return $content;
		}
		elseif($string2 == "Directors ID - resigning")
		{
			$get_directors_ID = $this->db->query("select officer.identification_no from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1 AND transaction_client_officers.id='".$id."'");

			$get_directors_ID = $get_directors_ID->result_array();

			$content = $this->encryption->decrypt($get_directors_ID[0]["identification_no"]);

			return $content;
		}
		elseif($string2 == "Directors address - resigning")
		{
			// $content = '<w:t xml:space="preserve">                    </w:t>';

			$get_directors_address = $this->db->query("select officer.postal_code1, officer.street_name1, officer.building_name1, officer.unit_no1, officer.unit_no2 from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1 AND transaction_client_officers.id='".$id."'");

			$get_directors_address = $get_directors_address->result_array();

			$directors_unit_no1 = $get_directors_address[0]["unit_no1"];
			$directors_unit_no2 = $get_directors_address[0]["unit_no2"];
			$directors_street_name = $get_directors_address[0]["street_name1"];
			$directors_building_name = $get_directors_address[0]["building_name1"];
			$directors_postal_code = $get_directors_address[0]["postal_code1"];

			$address = array(
				'type' 			=> "Local",
				'street_name1' 	=> strtoupper($directors_street_name),
				'unit_no1'		=> strtoupper($directors_unit_no1),
				'unit_no2'		=> strtoupper($directors_unit_no2),
				'building_name1'=> strtoupper($directors_building_name),
				'postal_code1'	=> strtoupper($directors_postal_code)
			);

			$content = $this->write_address_local_foreign($address, "letter", "big_cap");
			return $content;
		}
		// elseif($string2 == "Company address")
		// {
		// 	$get_client_name = $this->db->query("select company_name from transaction_client where company_code='".$company_code."' AND transaction_id='".$transaction_master_id."'");

		// 	$get_client_name = $get_client_name->result_array();
			
		// 	if(0 == count($get_client_name))
		// 	{
		// 		$get_client_name = $this->db->query("select company_name from client where company_code='".$company_code."' AND client.deleted != 1");

		// 		$get_client_name = $get_client_name->result_array();
		// 	}

		// 	$content = $this->encryption->decrypt($get_client_name[0]["company_name"]);

		// 	return $content;
		// }
		elseif($string2 == "Company old name")
		{
			$get_company_name = $this->db->query("select company_name from transaction_change_company_name where transaction_id='".$transaction_master_id."'");

			$get_company_name = $get_company_name->result_array();

			$content = $get_company_name[0]["company_name"];

			return $content;
		}
		elseif($string2 == "Company new name")
		{
			$get_new_company_name = $this->db->query("select new_company_name from transaction_change_company_name where transaction_id='".$transaction_master_id."'");

			$get_new_company_name = $get_new_company_name->result_array();

			$content = $get_new_company_name[0]["new_company_name"];
			
			return $content;
		}
    }

    public function getShareholderAddress($transaction_master_id, $string2, $company_code, $firm_id, $id = null, $document_name = null, $field_type, $officer_id)
    {
    	$individual_address = '';

		// get individual address
		if ($field_type == "individual")
		{
			$individual_unit = '';
			$individual_building_name_1 = '';

			$individual_info = $this->db->query("SELECT * FROM officer WHERE id ='". $officer_id . "'");
			$individual_info = $individual_info->result_array()[0];

			$address = array(
				'type' 			=> $individual_info['address_type'],
				'street_name1' 	=> ucwords(strtolower($individual_info["street_name1"])),
				'unit_no1'		=> ucwords(strtolower($individual_info["unit_no1"])),
				'unit_no2'		=> ucwords(strtolower($individual_info["unit_no2"])),
				'building_name1'=> ucwords(strtolower($individual_info["building_name1"])),
				'postal_code1'	=> ucwords(strtolower($individual_info["postal_code1"])),
				'foreign_address1' => ucwords(strtolower($individual_info["foreign_address1"])),
				'foreign_address2' => ucwords(strtolower($individual_info["foreign_address2"])),
				'foreign_address3' => ucwords(strtolower($individual_info["foreign_address3"]))
			);
		}
		elseif($field_type == "client")
		{
			$client_unit = '';
			$client_building_name_1 = '';

			$individual_info = $this->db->query("SELECT * FROM client WHERE id ='". $officer_id . "' AND client.deleted != 1");
			$individual_info = $individual_info->result_array()[0];

			$address = array(
				'type' 			=> "Local",
				'street_name1' 	=> ucwords(strtolower($individual_info["street_name"])),
				'unit_no1'		=> ucwords(strtolower($individual_info["unit_no1"])),
				'unit_no2'		=> ucwords(strtolower($individual_info["unit_no2"])),
				'building_name1'=> ucwords(strtolower($individual_info["building_name"])),
				'postal_code1'	=> ucwords(strtolower($individual_info["postal_code"]))
			);
		}
		elseif($field_type == "company")
		{
			$company_unit = '';
			$company_building_name_1 = '';

			$individual_info = $this->db->query("SELECT * FROM officer_company WHERE id ='". $officer_id . "'");
			$individual_info = $individual_info->result_array()[0];

			$address = array(
				'type' 			=> $individual_info['address_type'],
				'street_name1' 	=> ucwords(strtolower($individual_info["company_street_name"])),
				'unit_no1'		=> ucwords(strtolower($individual_info["company_unit_no1"])),
				'unit_no2'		=> ucwords(strtolower($individual_info["company_unit_no2"])),
				'building_name1'=> ucwords(strtolower($individual_info["company_building_name"])),
				'postal_code1'	=> ucwords(strtolower($individual_info["company_postal_code"])),
				'foreign_address1' => ucwords(strtolower($individual_info["company_foreign_address1"])),
				'foreign_address2' => ucwords(strtolower($individual_info["company_foreign_address2"])),
				'foreign_address3' => ucwords(strtolower($individual_info["company_foreign_address3"]))
			);
		}

		$individual_address = $this->write_address_local_foreign($address, "letter", "small_cap");

		return $individual_address;
    }

    public function write_header($firm_id, $use_own_header)
	{
		$query = $this->db->query("select firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax from firm 
												JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
												JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
												JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
												where firm.id = '".$firm_id."'");
		$query = $query->result_array();

		// Calling getimagesize() function 
		list($width, $height, $type, $attr) = getimagesize("uploads/logo/" . $query[0]["file_name"]); 

		$different_w_h = (float)$width - (float)$height;

		if((float)$width > (float)$height && $different_w_h > 100)
		{
			$td_width = 25;
			$td_height = 73.75;
		}
		else
		{
			$td_width = 15;
			$td_height = 83.75;
		}

		if(!$use_own_header){
			if(!empty($query[0]["file_name"]))
			{
				$img = '<img src="uploads/logo/'. $query[0]["file_name"] .'" height="55" />';
			}
			else
			{
				$img = '';
			}
		}

		if(!$use_own_header)
		{
			$header_content = '<table style="width: 100%; border-collapse: collapse; height: 60px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
					<tbody>
					<tr style="height: 60px;">
						<td style="width: '. $td_width .'%; text-align: left; height: 60px; padding: 5%;" align="center">
							<table style="border-collapse: collapse; width: 100%;" border="0">
							<tbody>
							<tr>
							<td style="text-align: left; height: 60px;" align="center"><p>'. $img .'  </p></td>
							</tr>
							</tbody>
							</table>
						</td>
						<td style="width: 1.25%; text-align: left;">&nbsp;</td>
						<td style="width: '. $td_height .'%; height: 60px;"><span style="font-size: 18pt;">'.$query[0]["name"].'</span><br /><span style="font-size: 8pt; text-align: left;">UEN: '. $query[0]["registration_no"] .'<br />Address: '. $query[0]["street_name"] .', #'. $query[0]["unit_no1"] .'-'.$query[0]["unit_no2"].' '. $query[0]["building_name"] .', Singapore '. $query[0]["postal_code"] .'<br />Tel: '. $query[0]["telephone"] .' &nbsp; Fax: '. $query[0]["fax"] .'&nbsp;</span></td>
					</tr>
					</tbody>
					</table>';
		}
		else
		{
			$header_content = '<table style="width: 100%; border-collapse: collapse; height: 60px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
								<tbody>
								<tr style="height: 80px;"><td style="height: 60px;"></td></tr>
								</tbody>
							   </table>';
		}

		return $header_content;
	}

	public function receiver_info($document_type, $gst_number_display, $q)
	{
		$address = $this->write_address(ucwords(strtolower($q[0]["street_name"])), $q[0]["unit_no1"], $q[0]["unit_no2"], ucwords(strtolower($q[0]['building_name'])), $q[0]["postal_code"], 'billing letter with comma');

		$add_info = '';
		$description_title = '';

		if($document_type == "invoice")
		{
			$document_date = date('d F Y', strtotime(str_replace('/', '-', $q[0]["invoice_date"])));
			$document_num  = $q[0]["invoice_no"];

			$description_title = "Description";
			$title_font_size = '22pt';

			$add_info = '<tr><td style="width: 73%; text-align:left;"><strong style="font-size: 10pt;">Bill To:</strong></td>' . $gst_number_display . '</tr>';
		}

		$receiver_info = '<table style="width: 100%; border-collapse: collapse; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
						<tbody>'
						.$add_info.
						'<tr>
						<td style="width: 64.7477%; font-weight: normal; font-size: 12px;">
						<table style="width: 107.191%; border-collapse: collapse; height: 81px;" border="0">
						<tbody>
						<tr style="height: 20px;">
						<td style="width: 100%; height: 20px; text-align: left;"><strong><span style="font-size: 10pt; font-family: arial, helvetica, sans-serif;">'.$q[0]["company_name"].'</span></strong></td>
						</tr>
						<tr style="height: 116px;">
						<td style="width: 100%; height: 41px; text-align: left;"><span style="font-size: 9pt; font-family: arial, helvetica, sans-serif;"><span style="font-family: arial, helvetica, sans-serif;">'. $address .'</span></span></td>
						</tr>
						<tr style="height: 20px;">
						<td style="width: 100%; height: 20px; text-align: left;"><span style="font-size: 9pt; font-family: arial, helvetica, sans-serif;">ATTN: Director / Finance Department</span></td>
						</tr>
						</tbody>
						</table>
						</td>
						<td style="width: 10.889%;">&nbsp;</td>
						<td style="width: 25.753%;">
						<table style="height: 34px; width: 100%; border-collapse: collapse;" border="0">
						<tbody>
						<tr style="height: 25px;">
						<td style="width: 93.077%; height: 34px;"><span style="text-align: left; font-size: '. $title_font_size .'; font-family: arial, helvetica, sans-serif;">'. strtoupper($document_type) .'</span></td>
						<td style="width: 6.92303%; text-align: right; height: 34px;"><span style="text-align: right; font-weight: normal; font-size: 20px;">&nbsp;</span></td>
						</tr>
						</tbody>
						</table>
						<table style="height: 72px; width: 99.1372%; border-collapse: collapse;" border="0">
						<tbody>
						<tr style="height: 18px;">
						<td style="width: 94.3111%; height: 18px; text-align: left;"><span style="font-family: arial, helvetica, sans-serif; font-size: 9pt; font-weight: normal;">'. $document_num .'</span></td>
						<td style="width: 27.8761%; text-align: left;">&nbsp;</td>
						</tr>
						<tr style="height: 18px;">
						<td style="width: 94.3111%; height: 18px; text-align: left;"><span style="font-family: arial, helvetica, sans-serif; font-size: 9pt; font-weight: normal;">'. $document_date .'</span></td>
						<td style="width: 27.8761%; text-align: left;">&nbsp;</td>
						</tr>
						<tr style="height: 18px;">
						<td style="width: 94.3111%; height: 18px; text-align: left;">&nbsp;</td>
						<td style="width: 27.8761%; text-align: left;">&nbsp;</td>
						</tr>
						</tbody>
						</table>
						</td>
						</tr>
						</tbody>
						</table>
						<hr style="height: 1px; border: none; color: #333; background-color: #333;" />
						<table style="width: 100%; border-collapse: collapse; font-family: arial, helvetica, sans-serif; font-size: 10pt; height: 34px;" border="0">
						<tbody>
						<tr style="height: 17px;">
						<td style="width: 86.9273%; height: 17px;" colspan="2">&nbsp;</td>
						<td style="width: 6.68348%; text-align: center; height: 17px;">&nbsp;</td>
						</tr>
						<tr style="height: 17px;">
						<td style="width: 86.9273%; height: 17px;" colspan="2" align="left"><strong><span style="text-decoration: underline;">'.$description_title.'</span></strong></td>
						<td style="width: 15.5%; height: 17px;"><strong><span style="text-decoration: underline;">'. $q[0]["currency"] .'</span></strong></td>
						</tr>
						</tbody>
					</table>
					';

		return $receiver_info;
	}

	public function spell_number_dollar($number){
        	$tempNum = explode( '.' , $number );
        	// $convertedNumber = "12345";
        	$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);

			$convertedNumber = ( isset( $tempNum[0] ) ? $f->format($tempNum[0]) . ' dollars' : '' );

			//  Use the below line if you don't want 'and' in the number before decimal point
			// $convertedNumber = str_replace( ' and ' ,' ' ,$convertedNumber );

			//  In the below line if you want you can replace ' and ' with ' , '
			$convertedNumber .= ( ( isset( $tempNum[0] ) and isset( $tempNum[1] ) )  ? ' and ' : '' );

			$convertedNumber .= ( isset( $tempNum[1] ) ? $f->format( $tempNum[1] ) .' cents' : '' );

			$convertedNumber .= " only";

			return ucfirst($convertedNumber);
	}

    // this is will removed after all address have use the write_address_local_foreign function.
	public function write_address($street_name, $unit_no1, $unit_no2, $building_name, $postal_code, $type)
	{
		$unit = '';
		$unit_building_name = '';

		if($type == "normal")
		{
			$br1 = '';
			$br2 = '';
		}
		elseif($type == "letter")
		{
			$br1 = ' <w:br/>';
			$br2 = ' <w:br/>';
		}
		elseif($type == "comma")
		{
			$br1 = ', ';
			$br2 = ', ';
		}
		elseif($type == "billing letter with comma")
		{
			$br1 = ', <br/>';
			$br2 = ', <br/>';
		}	

		// Add unit
		if(!empty($unit_no1) && !empty($unit_no2))
		{
			$unit = '#' . $unit_no1 . '-' . $unit_no2;
		}
		

		// Add building
		if(!empty($building_name) && !empty($unit))
		{
			$unit_building_name = $unit . ' ' . $building_name;
		}
		elseif(!empty($unit))
		{
			$unit_building_name = $unit;
		}
		elseif(!empty($building_name))
		{
			$unit_building_name = $building_name;
		}
		else
		{
			if($type != "letter")
			{
				$br2 = '';
			}
		}

		return $street_name . $br1 . $unit_building_name . $br2 . 'Singapore ' . $postal_code;
	}

	// latest version include foreign address. 
	public function write_omp_grant_address_local_foreign($address, $type, $style, $line)
	{
		$unit = '';
		$unit_building_name = '';

		if($type == "normal")
		{
			$br1 = '';
			$br2 = '';
		}
		elseif($type == "letter")
		{
			$br1 = ', <w:br/>';
			$br2 = ', <w:br/>';
		}
		elseif($type == "comma")
		{
			$br1 = ', ';
			$br2 = ', ';
		}

		if($address['type'] == "Local")
		{
			// Add unit
			if(!empty($address['unit_no1']) && !empty($address['unit_no2']))
			{
				$unit = '#' . $address['unit_no1'] . '-' . $address['unit_no2'];
			}
			else
			{
				if($type != "letter")
				{
					$br2 = '';
				}
			}

			// Add building
			if(!empty($address['building_name1']) && !empty($unit))
			{
				$unit_building_name = $unit . ' ' . $address['building_name1'] . $br2;
			}
			elseif(!empty($unit))
			{
				$unit_building_name = $unit . $br2;
			}
			elseif(!empty($address['building_name1']))
			{
				$unit_building_name = $address['building_name1'] . $br2;
			}

			if($style == "big_cap")
			{
				$sg_word = 'SINGAPORE ';
			}
			else
			{
				$sg_word = 'Singapore ';
			}

			if($line == "1")
			{
				return $address['street_name1'] . $br1 . $unit_building_name;
			}
			else
			{
				return $sg_word . $address['postal_code1'];
			}
		}
	}

	// latest version include foreign address. 
	public function write_omp_grant_company_address_local_foreign($address, $type, $style)
	{
		$unit = '';
		$unit_building_name = '';

		if($type == "normal")
		{
			$br1 = '';
			$br2 = '';
		}
		elseif($type == "letter")
		{
			$br1 = ', ';
			$br2 = ', ';
		}
		elseif($type == "comma")
		{
			$br1 = ', ';
			$br2 = ', ';
		}

		if($address['type'] == "Local")
		{
			// Add unit
			if(!empty($address['unit_no1']) && !empty($address['unit_no2']))
			{
				$unit = '#' . $address['unit_no1'] . '-' . $address['unit_no2'];
			}

			// Add building
			if(!empty($address['building_name1']) && !empty($unit))
			{
				$unit_building_name = $unit . ' ' . $address['building_name1'] . $br2;
			}
			elseif(!empty($unit))
			{
				$unit_building_name = $unit . $br2;
			}
			elseif(!empty($address['building_name1']))
			{
				$unit_building_name = $address['building_name1'] . $br2;
			}

			if($style == "big_cap")
			{
				$sg_word = 'SINGAPORE ';
			}
			else
			{
				$sg_word = 'Singapore ';
			}
			$latest_street_name = '<w:p w14:paraId="22DF3372" w14:textId="7ABFAEED" w:rsidR="006F1592" w:rsidRPr="009024C8" w:rsidRDefault="00C115EA" w:rsidP="006F1592"><w:pPr><w:spacing w:after="80" w:line="240" w:lineRule="auto"/><w:rPr><w:rFonts w:asciiTheme="majorHAnsi" w:hAnsiTheme="majorHAnsi" w:cstheme="majorHAnsi"/></w:rPr></w:pPr><w:r w:rsidRPr="009024C8"><w:rPr><w:rFonts w:asciiTheme="majorHAnsi" w:hAnsiTheme="majorHAnsi" w:cstheme="majorHAnsi"/></w:rPr><w:t>'.$address['street_name1']. $br1 .'</w:t></w:r></w:p>';
			if($unit_building_name != '')
			{
				$latest_unit_building_name = '<w:p w14:paraId="22DF3372" w14:textId="7ABFAEED" w:rsidR="006F1592" w:rsidRPr="009024C8" w:rsidRDefault="00C115EA" w:rsidP="006F1592"><w:pPr><w:spacing w:after="80" w:line="240" w:lineRule="auto"/><w:rPr><w:rFonts w:asciiTheme="majorHAnsi" w:hAnsiTheme="majorHAnsi" w:cstheme="majorHAnsi"/></w:rPr></w:pPr><w:r w:rsidRPr="009024C8"><w:rPr><w:rFonts w:asciiTheme="majorHAnsi" w:hAnsiTheme="majorHAnsi" w:cstheme="majorHAnsi"/></w:rPr><w:t>'.$unit_building_name.'</w:t></w:r></w:p>';
			}
			else
			{
				$latest_unit_building_name = '';
			}

			$latest_postal_code = '<w:p w14:paraId="22DF3372" w14:textId="7ABFAEED" w:rsidR="006F1592" w:rsidRPr="009024C8" w:rsidRDefault="00C115EA" w:rsidP="006F1592"><w:pPr><w:spacing w:after="80" w:line="240" w:lineRule="auto"/><w:rPr><w:rFonts w:asciiTheme="majorHAnsi" w:hAnsiTheme="majorHAnsi" w:cstheme="majorHAnsi"/></w:rPr></w:pPr><w:r w:rsidRPr="009024C8"><w:rPr><w:rFonts w:asciiTheme="majorHAnsi" w:hAnsiTheme="majorHAnsi" w:cstheme="majorHAnsi"/></w:rPr><w:t>'.$sg_word . $address['postal_code1'] .'</w:t></w:r></w:p>';

			return $latest_street_name . $latest_unit_building_name . $latest_postal_code;
		}
	}

	// latest version include foreign address. 
	public function write_address_local_foreign($address, $type, $style)
	{
		$unit = '';
		$unit_building_name = '';

		if($type == "normal")
		{
			$br1 = '';
			$br2 = '';
		}
		elseif($type == "letter")
		{
			$br1 = ', <w:br/>';
			$br2 = ', <w:br/>';
		}
		elseif($type == "comma")
		{
			$br1 = ', ';
			$br2 = ', ';
		}

		if($address['type'] == "Local")
		{
			// Add unit
			if(!empty($address['unit_no1']) && !empty($address['unit_no2']))
			{
				$unit = '#' . $address['unit_no1'] . '-' . $address['unit_no2'];
			}

			// Add building
			if(!empty($address['building_name1']) && !empty($unit))
			{
				$unit_building_name = $unit . ' ' . $address['building_name1'] . $br2;
			}
			elseif(!empty($unit))
			{
				$unit_building_name = $unit . $br2;
			}
			elseif(!empty($address['building_name1']))
			{
				$unit_building_name = $address['building_name1'] . $br2;
			}

			if($style == "big_cap")
			{
				$sg_word = 'SINGAPORE ';
			}
			else
			{
				$sg_word = 'Singapore ';
			}

			return $address['street_name1'] . $br1 . $unit_building_name . $sg_word . $address['postal_code1'];
		}
		else if($address['type'] == "Foreign")
		{
			$foreign_address1 = !empty($address["foreign_address1"])? $address["foreign_address1"]: '';

			if(!empty($address["foreign_address1"]))
			{
				if(substr($address["foreign_address1"], -1) == ",")
				{
					$foreign_address1 = rtrim($address["foreign_address1"],',');	// remove , if there is any at last character
				}
				else
				{
					$foreign_address1 = $address["foreign_address1"];
				}
			}

			if(!empty($address["foreign_address2"]))
			{
				if(substr($address["foreign_address2"], -1) == ",")
				{
					$foreign_address2 = $br1 . rtrim($address["foreign_address2"],',');		// remove , if there is any at last character
				}
				else
				{
					$foreign_address2 = $br1 . $address["foreign_address2"];
				}
			}
			else
			{
				$foreign_address2 = '';
			}

			$foreign_address3 = !empty($address["foreign_address3"])? $br2 . $address["foreign_address3"]: '';

			return $foreign_address1.$foreign_address2.$foreign_address3;
		}
	}
}