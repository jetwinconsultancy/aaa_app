<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Teamworks extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model(array('db_model', 'master_model', 'transaction_model', 'transaction_word_model', 'quickbook_auth_model'));

		if (!$this->loggedIn) {
			$this->session->set_userdata('requested_page', $this->uri->uri_string());
			redirect('login');
		}

		define('USERNAME', 'AAA-GLOBAL');
		define('PASSWORD', 'WAT@000145');
	}

	public function index() {
		// $client = $this->db->query("select * from client where id IN (1287, 621, 625, 516, 628)")->result();
		// $clientData = [];
		// foreach ($client as $key => $value) {
		// 	// $clientData[$key]->id = $this->db_model->getClientbyID($value->id);
		// 	$client[$key]->dbid = $value->id;
		// 	$client[$key]->company_name = $this->encryption->decrypt($value->company_name);
		// 	$client[$key]->registration_no = $this->encryption->decrypt($value->registration_no);
		// }
		// print_r($client);
		// exit;

		// $client = $this->db->query("select * from officer where id IN (1223, 96, 1073, 47, 1134)")->result();
		// foreach ($client as $key => $value) {
		// 	// $clientData[$key]->id = $this->db_model->getClientbyID($value->id);
		// 	// $client[$key]->dbid = $value->id;
		// 	$client[$key]->identification_no = $this->encryption->decrypt($value->identification_no);
		// 	$client[$key]->name = $this->encryption->decrypt($value->name);
		// }
		// print_r($client);
		// exit;

		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$bc = array(array('link' => '#', 'page' => lang('Teamworks')));
		$meta = array('page_title' => lang('Teamworks'), 'bc' => $bc, 'page_name' => 'Teamworks');

		$this->page_construct2('teamwork/teamworks.php', $meta, $this->data);
	}

	public function export() {
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$bc = array(array('link' => '#', 'page' => lang('Teamworks')));
		$meta = array('page_title' => lang('Teamworks'), 'bc' => $bc, 'page_name' => 'Teamworks');
		
		$this->data['clients'] = $this->db->query("select `client`.`id` as `client_row_id`, `client`.*,`filing`.`year_end`,`filing`.`financial_year_period1`,`filing`.`financial_year_period2` from client left join `filing` on `filing`.`company_code` = `client`.`company_code` AND `filing`.`id` = (SELECT MAX(`id`) as id from `filing` where `filing`.`company_code` = `client`.`company_code`) where `client`.`deleted` = 0")->result();

		foreach ($this->data['clients'] as $key => $value) {
			# code...
			$this->data['clients'][$key]->company_name = $this->encryption->decrypt($value->company_name);
			$this->data['clients'][$key]->registration_no = $this->encryption->decrypt($value->registration_no);
		}

		usort($this->data['clients'], function($a, $b)
		{
			// return $a->company_name - $b->company_name;
			return strcmp($a->company_name, $b->company_name);
		});

		// print_r($client);
		// exit;
		$this->page_construct2('teamwork/teamworkexport.php', $meta, $this->data);
	}

	public function getClientOfficer() {
		$clientOfficers = $this->db->query("select `client_officers`.*, `officer`.*, `officer_files`.*, `officer_mobile_no`.*, `officer_fixed_line_no`.*, `officer_email`.*, `nationality`.`nationality` as nationality_text from client_officers LEFT JOIN `officer` ON `client_officers`.`officer_id` = `officer`.`id` LEFT JOIN officer_files ON officer_files.officer_id = officer.id left join officer_mobile_no on officer_mobile_no.officer_id = officer.id left join officer_fixed_line_no on officer_fixed_line_no.officer_id = officer.id left join officer_email on officer_email.officer_id = officer.id left join nationality on `officer`.`nationality` = `nationality`.`id` where `client_officers`.`company_code` = '".$_POST["company_code"]."' AND `client_officers`.`position` != '5' GROUP BY `client_officers`.`officer_id`;")->result();
		foreach ($clientOfficers as $key => $value) {
			# code...
			$clientOfficers[$key]->identification_no = $this->encryption->decrypt($value->identification_no);
			$clientOfficers[$key]->name = $this->encryption->decrypt($value->name);
			$clientOfficers[$key]->email = $this->encryption->decrypt($value->email);
			$clientOfficers[$key]->fixed_line_no = $this->encryption->decrypt($value->fixed_line_no);
			$clientOfficers[$key]->mobile_no = $this->encryption->decrypt($value->mobile_no);
		}
		$data["clientOfficers"] = $clientOfficers;

		$auditor = $this->db_model->getClientOfficer($_POST['company_code']);
		foreach ($auditor as $key => $value) {
			if ($value["position"] == "5") {
				array_push($data["clientOfficers"], $value);
			}
		}

		$q = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.id as officer_id, officer.date_of_birth, officer.identification_no, officer.identification_type, officer.name, officer.foreign_address1, officer.foreign_address2, officer.foreign_address3, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" where member_shares.company_code="'.$_POST["company_code"].'" GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id HAVING sum(member_shares.number_of_share) != 0');
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                //echo json_encode($row->field_type);
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
                $shareHolder[] = $row;
            }
            $data["shareHolder"] = $shareHolder;
        }

		$q = $this->db->query('SELECT *, `client_contact_info`.`id` as client_contact_info_id, `client_officers`.`date_of_appointment`,  `client_officers`.`date_of_cessation` FROM `client_contact_info` LEFT JOIN `client_contact_info_email` ON `client_contact_info_email`.`client_contact_info_id` = `client_contact_info`.`id` LEFT JOIN `client_contact_info_phone` ON `client_contact_info_phone`.`client_contact_info_id` = `client_contact_info`.`id` LEFT JOIN `client_officers` ON `client_officers`.`company_code` = `client_contact_info`.`company_code` WHERE `client_contact_info`.`company_code` = "'.$_POST["company_code"].'" LIMIT 1;');
		if ($q->num_rows() > 0) {
            $data["contactInfo"] = $q->result();
        }

		// $q = $this->db->query('SELECT *, `nationality`.`nationality` as nationality_text FROM `client_controller` LEFT JOIN `officer` ON `officer`.`id` = `client_controller`.`id` left join nationality on `officer`.`nationality` = `nationality`.`id` WHERE `company_code` = "'.$_POST["company_code"].'" LIMIT 1;');
		// if ($q->num_rows() > 0) {
		// 	foreach (($q->result()) as $row) {
		// 		$row->identification_no = $this->encryption->decrypt($row->identification_no);
		// 		$row->name = $this->encryption->decrypt($row->name);
		// 		$controller[] = $row;
		// 	}
		// 	$data["controller"] = $controller;
        // }
		$data["controller"] = $this->db_model->getClientController($_POST['company_code']);

		echo json_encode($data);
	}

	public function companyexport($reg_no) {
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$bc = array(array('link' => '#', 'page' => lang('Teamworks')));
		$meta = array('page_title' => lang('Teamworks'), 'bc' => $bc, 'page_name' => 'Teamworks');
		
		$clients = $this->db->query("select `client`.*,`filing`.`year_end`,`filing`.`financial_year_period1`,`filing`.`financial_year_period2` from client left join `filing` on `filing`.`company_code` = `client`.`company_code` where `client`.`deleted` = 0;")->result();

		$this->data['reg_no'] = $reg_no;

		foreach ($clients as $key => $value) {
			# code...
			$value->company_name = $this->encryption->decrypt($value->company_name);
			$value->registration_no = $this->encryption->decrypt($value->registration_no);

			if ($value->registration_no == $reg_no) {
				$this->data['clients'][0] = $value;
			}
		}

		// print_r($client);
		// exit;
		$this->page_construct2('teamwork/teamworkexport.php', $meta, $this->data);
	}

	public function detail($id) {
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$bc = array(array('link' => '#', 'page' => lang('Teamworks')));
		$meta = array('page_title' => lang('Teamworks'), 'bc' => $bc, 'page_name' => 'Teamworks');

		$this->page_construct2('teamwork/detail.php', $meta, $this->data);
	}

	public function syncupdate() {
		$TWCSS = json_decode($this->getCompaniesJSON());

		$this->db->where(array("deleted" => 0));
		$client = $this->db->get('client')->result();

		print_r($TWCSS);

		echo count($client);

		echo "Update 1";
	}

	public function officer() {
		$this->db->where(array("deleted" => 0));
		$this->db->order_by('created_at DESC');
		$this->db->limit(20, 0);
		$client = $this->db->get('client')->result();
		$update = array();

		$i = 0;

		foreach ($client as $key => $value) {
			$client[$key] = $this->db_model->getClientbyID($value->id);

			$client[$key]->company_name = $this->encryption->decrypt($value->company_name);
			$client[$key]->registration_no = $this->encryption->decrypt($value->registration_no);

			$client[$key]->client_officers = $this->db_model->getClientOfficer($company_code);
			$client[$key]->client_nominee_director = $this->db_model->getClientNomineeDirector($company_code);

			if (count($agm)) {
				$update[$i] = array(
					'registration_no' => $client[$key]->registration_no,
				);
				// $this->insertDateCompany($update[$key]);
				$i++;
			}

		}
		print_r($client);
		print_r($update);

	}

	public function dates() {
		$this->db->where(array("deleted" => 0, "id" => 1378));
		$this->db->order_by('created_at DESC');
		$this->db->limit(20, 0);
		$client = $this->db->get('client')->result();
		$update = array();

		$i = 0;

		foreach ($client as $key => $value) {
			$client[$key] = $this->db_model->getClientbyID($value->id);

			$client[$key]->company_name = $this->encryption->decrypt($value->company_name);
			$client[$key]->registration_no = $this->encryption->decrypt($value->registration_no);
			$agm = $this->db->order_by('created_at DESC')->get_where('filing', array('company_code' => $client[$key]->company_code))->result();
			$eci = $this->db->order_by('created_at DESC')->get_where('eci_filing', array('company_code' => $client[$key]->company_code))->result();
			$tax = $this->db->order_by('created_at DESC')->get_where('tax_filing', array('company_code' => $client[$key]->company_code))->result();

			if (count($agm)) {
				$update[$i] = array(
					'registration_no' => $client[$key]->registration_no,
					'type_of_event' => "1,2",
					'year' => date_format(date_create($agm[0]->year_end), 'Y'),
					'actual_fye' => date_format(date_create($agm[0]->year_end), 'd/m/Y'),
					'fye_range_from' => date_format(date_create($agm[0]->financial_year_period1), 'd/m/Y'),
					'fye_range_to' => date_format(date_create($agm[0]->financial_year_period2), 'd/m/Y'),

					'held_date_1' => date_format(date_create($agm[0]->due_date_201), 'd/m/Y'),

					'held_date_2' => date_format(date_create($agm[0]->due_date_197), 'd/m/Y'),
				);

				if ($agm[0]->agm) {
					$update[$i]["filling_date_1"] = date_format(date_create($agm[0]->agm), 'd/m/Y');
				}

				if ($agm[0]->ar_filing_date) {
					$update[$i]["filling_date_2"] = date_format(date_create($agm[0]->ar_filing_date), 'd/m/Y');
				}

				$this->insertDateCompany($update[$i]);
				$i++;
			}

			if (count($eci)) {
				$update[$i] = array(
					'registration_no' => $client[$key]->registration_no,
					'type_of_event' => "3",
					'year' => date_format(date_create($agm[0]->year_end), 'Y'),
					'actual_fye' => date_format(date_create($agm[0]->year_end), 'd/m/Y'),

					'held_date_3' => date_format(date_create($eci[0]->next_eci_filing_due_date), 'd/m/Y'),
					'filling_date_3' => date_format(date_create($eci[0]->eci_filing_date), 'd/m/Y'),
				);
				$this->insertDateCompany($update[$i]);
				$i++;
			}

			if (count($tax)) {
				$update[$i] = array(
					'registration_no' => $client[$key]->registration_no,
					'type_of_event' => "4",
					'year' => date_format(date_create($agm[0]->year_end), 'Y'),
					'actual_fye' => date_format(date_create($agm[0]->year_end), 'd/m/Y'),

					'held_date_4' => date_format(date_create($tax[0]->tax_filing_due_date), 'd/m/Y'),
					'filling_date_4' => date_format(date_create($tax[0]->filing_date), 'd/m/Y'),
				);
				$this->insertDateCompany($update[$i]);
				$i++;
			}

		}
		print_r($client);
		print_r($update);

	}

	public function sync() {

		$client = $this->db->query("select * from client where id in('1385','1387')")->result();
		// $this->db->where(array("deleted"=>0));
		// $this->db->order_by('created_at ASC');
		// $this->db->limit(500,300);
		// $client = $this->db->get('client')->result();

		$update = array();

		foreach ($client as $key => $value) {
			$client[$key] = $this->db_model->getClientbyID($value->id);
			// $company_code = $value->company_code;
			// $client[$key]->client_guarantee =$this->db_model->getClientGuarantee($company_code);
			// $client[$key]->client_controller =$this->db_model->getClientController($company_code);
			// $client[$key]->client_nominee_director =$this->db_model->getClientNomineeDirector($company_code);
			// $client[$key]->client_charges = $this->master_model->get_all_chargee($company_code);
			// $client[$key]->client_share_capital = $this->master_model->get_all_client_share_capital($company_code);
			// //$client[$key]->allotment = $this->master_model->get_all_allotment_group($company_code);
			// $client[$key]->member = $this->master_model->get_all_member($company_code);
			// $client[$key]->member_certificate = $this->master_model->get_all_member_certificate($company_code);
			// $client[$key]->client_signing_info = $this->master_model->get_all_client_signing_info($company_code);
			// $client[$key]->client_contact_info = $this->master_model->get_all_client_contact_info($company_code);
			// $client[$key]->client_reminder_info = $this->master_model->get_all_client_reminder_info($company_code);
			// $client[$key]->client_setup_group_info = $this->master_model->get_all_client_setup_group_info($id);
			// $client[$key]->client_setup_related_party_info = $this->master_model->get_all_client_setup_related_party_info($id);
			// $client[$key]->client_billing_info = $this->master_model->get_all_client_billing_info($company_code);

			//   if($client[$key]->client_billing_info == false)
			//   {
			//     $client[$key]->client_billing_info = $this->master_model->get_all_default_client_service();
			//   }

			// $client[$key]->filing_data = $this->master_model->get_all_filing_data($company_code);
			// $client[$key]->eci_filing_data = $this->master_model->get_all_eci_filing_data($company_code);
			// $client[$key]->tax_filing_data = $this->master_model->get_all_tax_filing_data($company_code);
			// $client[$key]->gst_filing_data = $this->master_model->get_all_gst_filing_data($company_code);
			// $client[$key]->template = $this->master_model->get_all_template_data($company_code);
			// $client[$key]->director_retiring = $this->db_model->get_all_director_retiring($company_code);
			// $client[$key]->corp_rep_data = $this->db_model->get_all_corp_rep($registration_no);
			// $client[$key]->transaction = $this->master_model->get_all_transaction_in_client_module($company_code, $_SESSION['group_id']);
			// $client[$key]->list_of_confirmation_auditor = $this->master_model->get_all_list_of_confirmation_auditor($company_code, $_SESSION['group_id']);
			// $client[$key]->list_of_company_document = $this->master_model->get_all_list_of_company_document($company_code);
			// $client[$key]->firm_info = $this->master_model->get_firm_info();

			// $this->delCompanies($value);
			// $this->insertcompany($value);

			$clinet[$key]->company_name = $this->encryption->decrypt($value->company_name);
			$clinet[$key]->registration_no = $this->encryption->decrypt($value->registration_no);

			$b = $this->db->query("select client_contact_info_email.email
       from client_contact_info
       left join client_contact_info_email on client_contact_info_email.client_contact_info_id = client_contact_info.id
       where
       client_contact_info.company_code='" . $value->company_code . "' and client_contact_info_email.primary_email=1")->result();

			$phone = $this->db->query("select client_contact_info_phone.phone
       from client_contact_info
       left join client_contact_info_phone on client_contact_info_phone.client_contact_info_id = client_contact_info.id
       where
       client_contact_info.company_code='" . $value->company_code . "' and client_contact_info_phone.primary_phone=1")->result();

			// $client[$key]->client_officers =$this->db_model->getClientOfficer($value->company_code);

			$companytype = array();
			$companytype[1] = 2;
			$companytype[2] = 1;
			$companytype[3] = 8;
			$companytype[4] = 11;
			$companytype[5] = 12;
			$companytype[6] = 7;
			$companytype[7] = 13;
			$companytype[8] = 14;

			$companystatus = array();
			$companystatus[1] = 5;
			$companystatus[2] = 8;
			$companystatus[3] = 11;
			$companystatus[4] = 7;

			$exp = explode("] ", $value->activity1);
			if (count($exp) > 1) {
				$exp = $exp[1];
			} else {
				$exp = $exp[0];
			}
			$exp = explode(" - ", $exp);

			$exp1 = explode("] ", $value->activity2);
			if (count($exp1) > 1) {
				$exp1 = $exp1[1];
			} else {
				$exp1 = $exp1[0];
			}
			$exp1 = explode(" - ", $exp1);

			$newstr = substr_replace($phone[0]->phone, "-", 3, 0);

			$string = preg_replace('/\s+/', '', $b[0]->email);
			$string = explode(";", $string);

			if (count($string) > 1) {
				$string_new = $string[0];
				array_shift($string);
				$string_remarks = implode(";", $string);
			} else {
				$string_new = $string[0];
				$string_remarks = "";
			}

			$string2 = preg_replace('/\s+/', '', $b[0]->email);
			$string2 = explode("<", $string2);
			if (count($string2) > 1) {
				$string_new = $string2[0];
			}

			if ($string_new == "") {
				$string_new = "email@company.com";
			}

			$update[$key] = array(
				'css_client' => 1,
				'entity_name' => $clinet[$key]->company_name,
				'former_name_if_any' => str_replace("\n", "", $value->former_name),
				'company_id' => $value->id,
				'entity_type' => $companytype[$value->company_type],
				'registration_no' => $clinet[$key]->registration_no,
				'acra_uen' => " ",
				'region' => "",
				'country' => $value->client_country_of_incorporation,
				'entity_status' => $value->status,
				'risk_assessment_rating' => "",
				'incorporation_date' => $value->incorporation_date,

				'internal_css_status' => "", //--
				'Articles_constitution' => "", //--
				'article_regulation_no' => "", //--
				'article_description' => "", //--
				'common_seal' => "2",
				'company_stamp' => "2",

				'statute_registrable_corporate_controller' => $value->client_statutes_of,
				'incorporated' => $value->client_country_of_incorporation,

				'ssic_code_activity_I' => $exp[0],
				'default_ssic_description_I' => $exp[1],
				'user_described_activity_I' => $value->description1,

				'ssic_code_activity_II' => $exp1[0],
				'default_ssic_description_II' => $exp1[1],
				'user_described_activity_II' => $value->description2,

				'website' => "",
				'company_phone_1' => $newstr,
				'company_phone_2' => "",
				'fax' => "",
				'company_email_address' => $string_new,
				'remarks' => $string_remarks,
				'additional_remarks' => "",

				'default_address' => "0",
			);

			if ($value->status == 99) {
				$update[$key]["dormant_date"] = "";
			}

			if ($value->status == 99) {
				$update[$key]["dissolved_struck_off_date"] = "";
			}

			if ($value->status == 99) {
				$update[$key]["liquidation_striking_off_date"] = "";
			}

			if ($value->status == 99) {
				$update[$key]["termination_date"] = "";
			}

			/*ADDRESS*/

			if ($value->default_address == 0) {
				$update[$key]["block_0"] = " ";
				$update[$key]["street_name_0"] = (strlen($value->street_name) == 0) ? " " : $value->street_name;
				$update[$key]["building_0"] = (strlen($value->building_name) == 0) ? " " : $value->building_name;
				$update[$key]["level_0"] = (strlen($value->unit_no1) == 0) ? " " : $value->unit_no1;
				$update[$key]["unit_no_0"] = (strlen($value->unit_no2) == 0) ? " " : $value->unit_no2;
				$update[$key]["country_0"] = (strlen($value->client_country_of_incorporation) == 0) ? " " : $value->client_country_of_incorporation;
				$update[$key]["state_0"] = " ";
				$update[$key]["city_0"] = " ";
				$update[$key]["postal_code_0"] = (strlen($value->postal_code) == 0) ? " " : $value->postal_code;
			}

			if ($value->default_address == 1) {
				$update[$key]["block_1"] = "";
				$update[$key]["street_name_1"] = "";
				$update[$key]["building_1"] = "";
				$update[$key]["level_1"] = "";
				$update[$key]["unit_no_1"] = "";
				$update[$key]["state_1"] = "";
				$update[$key]["city_1"] = "";
				$update[$key]["postal_code_1"] = "";
			}

			if ($value->default_address == 2) {
				$update[$key]["block_2"] = "";
				$update[$key]["street_name_2"] = "";
				$update[$key]["building_2"] = "";
				$update[$key]["level_2"] = "";
				$update[$key]["unit_no_2"] = "";
				$update[$key]["state_2"] = "";
				$update[$key]["city_2"] = "";
				$update[$key]["postal_code_2"] = "";
			}

			if ($value->default_address == 3) {
				$update[$key]["address_line1_3"] = "";
				$update[$key]["address_line2_3"] = "";
			}

			if ($value->default_address == 4) {
				$update[$key]["block_2"] = "";
				$update[$key]["street_name_2"] = "";
				$update[$key]["building_2"] = "";
				$update[$key]["level_2"] = "";
				$update[$key]["unit_no_2"] = "";
				$update[$key]["state_2"] = "";
				$update[$key]["city_2"] = "";
				$update[$key]["postal_code_2"] = "";
			}

			if ($value->default_address == 5) {
				$update[$key]["block_2"] = "";
				$update[$key]["street_name_2"] = "";
				$update[$key]["building_2"] = "";
				$update[$key]["level_2"] = "";
				$update[$key]["unit_no_2"] = "";
				$update[$key]["state_2"] = "";
				$update[$key]["city_2"] = "";
				$update[$key]["postal_code_2"] = "";
			}

			$this->insertcompany($update[$key]);
		}

		// print_r($client);
		print_r($update);
		// echo count($client);
	}

	public function UpdateClient($company_registration_Num, $update) {
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://apps.teamworkcss.com/aaa_global/api/index/edit_company?registration_no=' . $company_registration_Num,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
			CURLOPT_USERPWD => USERNAME . ":" . PASSWORD,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $update,
			CURLOPT_HTTPHEADER => array(
				'x-api-key: 91bcec91-ddf0-402c-b287-a03d3563c320',
				'Cookie: ci_session_twcss=22daacb0debc7649b7c988c9779d6293a8169158',
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	public function add() {
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$bc = array(array('link' => '#', 'page' => lang('Teamworks')));
		$meta = array('page_title' => lang('Teamworks'), 'bc' => $bc, 'page_name' => 'Teamworks');

		$this->load->library('mybreadcrumb');
		$this->mybreadcrumb->add('Teamworks', base_url('teamworks'));
		$this->mybreadcrumb->add('Create Company', base_url());
		$this->data['breadcrumbs'] = $this->mybreadcrumb->render();

		$this->page_construct2('teamwork/add.php', $meta, $this->data);
	}

	public function insertDateCompany($client) {
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://apps.teamworkcss.com/aaa_global/api/index/event_date',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
			CURLOPT_USERPWD => USERNAME . ":" . PASSWORD,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $client,
			CURLOPT_HTTPHEADER => array(
				'x-api-key: 91bcec91-ddf0-402c-b287-a03d3563c320',
				'Cookie: ci_session_twcss=22daacb0debc7649b7c988c9779d6293a8169158',
			),
		));

		$response = curl_exec($curl);

		print_r($response);
		echo "\n";

		curl_close($curl);
	}

	public function insertcompany($client) {
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://apps.teamworkcss.com/aaa_global/api/index/add_company',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
			CURLOPT_USERPWD => USERNAME . ":" . PASSWORD,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $client,
			CURLOPT_HTTPHEADER => array(
				'x-api-key: 91bcec91-ddf0-402c-b287-a03d3563c320',
				'Cookie: ci_session_twcss=22daacb0debc7649b7c988c9779d6293a8169158',
			),
		));

		$response = curl_exec($curl);

		print_r($response);
		echo "\n";

		curl_close($curl);
	}

	public function addcompany() {
		$curl = curl_init();

		//incorporation_date
		$orgincorporation_date=$_POST["incorporation_date"];
		$incorporation_date = str_replace('-"', '/', $orgincorporation_date);
		$newincorporation_date = date("d/m/Y", strtotime($incorporation_date));

		//dormant_date
		$orgdormant_date=$_POST["dormant_date"];
		$dormant_date = str_replace('-"', '/', $orgdormant_date);
		$newdormant_date = date("d/m/Y", strtotime($dormant_date));

		//dissolved_struck_off_date
		$orgdissolved_struck_off_date=$_POST["dissolved_struck_off_date"];
		$dissolved_struck_off_date = str_replace('-"', '/', $orgdissolved_struck_off_date);
		$newdissolved_struck_off_date = date("d/m/Y", strtotime($dissolved_struck_off_date));


		//liquid_strike_off_date
		$orgliquid_strike_off_date=$_POST["liquid_strike_off_date"];
		$liquid_strike_off_date = str_replace('-"', '/', $orgliquid_strike_off_date);
		$newliquid_strike_off_date = date("d/m/Y", strtotime($liquid_strike_off_date));


		//termination_date
		$orgtermination_date=$_POST["termination_date"];
		$termination_date = str_replace('-"', '/', $orgtermination_date);
		$newtermination_date = date("d/m/Y", strtotime($termination_date));

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://apps.teamworkcss.com/aaa_global/api/index/add_company',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
			CURLOPT_USERPWD => USERNAME . ":" . PASSWORD,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array(
				'ccs_client' 									=> $_POST["ccs_client"],
				'entity_name' 									=> '"'.$_POST["entity_name"].'"',
				'former_name_if_any' 							=> '"'.$_POST["former_name_if_any"].'"', //Codes for Companies Data of Teamwork API
				'company_id' 									=> $_POST["company_id"],
				'entity_type' 									=> $_POST["entity_type"],
				'registration_no' 								=> '"'.$_POST["registration_no"].'"',
				'acra_uen' 										=> '"'.$_POST["acra_uen"].'"',
				'country' 										=> '"'.'"'.$_POST["country"].'"',
				'region' 										=> '"'.$_POST["region"].'"',
				'entity_status' 								=> $_POST["entity_status"],
				'risk_assessment_rating' 						=> $_POST["risk_assessment_rating"],
				'incorporation_date' 							=> '"'.$_POST["incorporation_date"].'"',
				'internal_css_status' 							=> $_POST["internal_css_status"],
				'Articles_constitution' 						=> $_POST["Articles_constitution"],
				'article_regulation_no' 						=> '"'.$_POST["article_regulation_no"].'"',
				'article_description' 							=> '"'.$_POST["article_description"].'"',
				//'company_name' => $_POST["company_name"],
				'dormant_date' 									=> '"'.$_POST["dormant_date"].'"',
				'dissolved_struck_off_date' 					=> '"'.$_POST["dissolved_struck_off_date"].'"',
				'liquid_strike_off_date' 						=> '"'.$_POST["liquid_strike_off_date"].'"',
				'termination_date' 								=> '"'.$_POST["termination_date"].'"',
				'common_seal' 									=> $_POST["common_seal"],
				'company_stamp' 								=> $_POST["company_stamp"],
				'statute_registrable_corporate_controller' 		=> '"'.$_POST["statute_registrable_corporate_controller"].'"',
				'incorporated' 									=> '"'.$_POST["incorporated"].'"',
				'ssic_code_activity_I' 							=> '"'.$_POST["ssic_code_activity_I"].'"',
				'default_ssic_description_I' 					=> '"'.$_POST["default_ssic_description_I"].'"',
				'user_described_activity_I' 					=> '"'.$_POST["user_described_activity_I"].'"',
				'ssic_code_activity_II' 						=> '"'.$_POST["ssic_code_activity_II"].'"',
				'default_ssic_description_II' 					=> '"'.$_POST["default_ssic_description_II"].'"',
				'user_described_activity_II' 					=> '"'.$_POST["user_described_activity_II"].'"',
				'website' 										=> '"'.$_POST["website"].'"',
				'company_phone_1' 								=> '"'.$_POST["company_phone_1"].'"',
				'company_phone_2' 								=> '"'.$_POST["company_phone_2"].'"',
				'fax' 											=> '"'.$_POST["fax"].'"',
				'company_email_address' 						=> '"'.$_POST["company_email_address"].'"',
				'remarks' 										=> '"'.$_POST["remarks"].'"',
				'additional_remarks' 							=> '"'.$_POST["additional_remarks"].'"',
				'default_address' 								=> '"'.$_POST["default_address"].'"',
				'block_0' 										=> '"'.$_POST["block_0"].'"',
				'street_name_0' 								=> '"'.$_POST["street_name_0"].'"',
				'building_0' 									=> '"'.$_POST["building_0"].'"',
				'level_0' 										=> '"'.$_POST["level_0"].'"',
				'unit_no_0' 									=> '"'.$_POST["unit_no_0"].'"',
				'country_0' 									=> '"'.$_POST["country_0"].'"',
				'state_0' 										=> '"'.$_POST["state_0"].'"',
				'city_0' 										=> '"'.$_POST["city_0"].'"',
				'postal_code_0' 								=> '"'.$_POST["postal_code_0"].'"',
				'block_1' 										=> '"'.$_POST["block_1"].'"',
				'street_name_1' 								=> '"'.$_POST["street_name_1"].'"',
				'building_1' 									=> '"'.$_POST["building_1"].'"',
				'level_1' 										=> '"'.$_POST["level_1"].'"',
				'unit_no_1' 									=> '"'.$_POST["unit_no_1"].'"',
				'country_1' 									=> '"'.$_POST["country_1"].'"',
				'state_1' 										=> '"'.$_POST["state_1"].'"',
				'city_1' 										=> '"'.$_POST["city_1"].'"',
				'postal_code_1' 								=> '"'.$_POST["postal_code_1"].'"',
				'block_2' 										=> '"'.$_POST["block_2"].'"',
				'street_name_2' 								=> '"'.$_POST["street_name_2"].'"',
				'building_2' 									=> '"'.$_POST["building_2"].'"',
				'level_2' 										=> '"'.$_POST["level_2"].'"',
				'unit_no_2' 									=> '"'.$_POST["unit_no_2"].'"',
				'country_2' 									=> '"'.$_POST["country_2"].'"',
				'state_2' 										=> '"'.$_POST["state_2"].'"',
				'city_2' 										=> '"'.$_POST["city_2"].'"',
				'postal_code_2' 								=> '"'.$_POST["postal_code_2"].'"',
				'address_line1_3' 								=> '"'.$_POST["address_line1_3"].'"',
				'address_line2_3' 								=> '"'.$_POST["address_line2_3"].'"',
				'block_4' 										=> '"'.$_POST["block_4"].'"',
				'street_name_4' 								=> '"'.$_POST["street_name_4"].'"',
				'building_4' 									=> '"'.$_POST["building_4"].'"',
				'level_4' 										=> '"'.$_POST["level_4"].'"',
				'unit_no_4' 									=> '"'.$_POST["unit_no_4"].'"',
				'country_4' 									=> '"'.$_POST["country_4"].'"',
				'state_4' 										=> '"'.$_POST["state_4"].'"',
				'city_4' 										=> '"'.$_POST["city_4"].'"',
				'postal_code_4' 								=> '"'.$_POST["postal_code_4"].'"',
				'block_5' 										=> '"'.$_POST["block_5"].'"',
				'street_name_5'	 								=> '"'.$_POST["street_name_5"].'"',
				'building_5' 									=> '"'.$_POST["building_5"].'"',
				'level_5' 										=> '"'.$_POST["level_5"].'"',
				'unit_no_5' 									=> '"'.$_POST["unit_no_5"].'"',
				'country_5' 									=> '"'.$_POST["country_5"].'"',
				'state_5' 										=> '"'.$_POST["state_5"].'"',
				'city_5' 										=> '"'.$_POST["city_5"].'"',
				'postal_code_5' 								=> '"'.$_POST["postal_code_5"].'"',
			),
			CURLOPT_HTTPHEADER => array(
				'x-api-key: 91bcec91-ddf0-402c-b287-a03d3563c320',
				'Cookie: ci_session_twcss=22daacb0debc7649b7c988c9779d6293a8169158',
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;

		redirect(base_url('teamworks'));
	}

	public function pushKeyDate() {
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://apps.teamworkcss.com/aaa_global/api/index/event_date',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
			CURLOPT_USERPWD => USERNAME . ":" . PASSWORD,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array(
				'registration_no' 	=> '"'.$_POST["registration_no"].'"',
				'type_of_event' 	=> '"'.$_POST["type_of_event"].'"',
				'year' 				=> '"'.$_POST["year"].'"',
				'actual_fye' 		=> '"'.$_POST["actual_fye"].'"',
				'fye_range_from' 	=> '"'.$_POST["fye_range_from"].'"',
				'fye_range_to' 		=> '"'.$_POST["fye_range_to"].'"',
				'held_date_1' 		=> '"'.$_POST["held_date_1"].'"',
				'filling_date_1' 	=> '"'.$_POST["filling_date_1"].'"',
				'held_date_2' 		=> '"'.$_POST["held_date_2"].'"',
				'filling_date_2' 	=> '"'.$_POST["filling_date_2"].'"',
				'held_date_3' 		=> '"'.$_POST["held_date_3"].'"',
				'filling_date_3' 	=> '"'.$_POST["filling_date_3"].'"',
				'held_date_4' 		=> '"'.$_POST["held_date_4"].'"',
				'filling_date_4' 	=> '"'.$_POST["filling_date_4"].'"',
				'held_date_5' 		=> '"'.$_POST["held_date_5"].'"',
				'filling_date_5' 	=> '"'.$_POST["filling_date_5"].'"',
				'held_date_6' 		=> '"'.$_POST["held_date_6"].'"',
				'filling_date_6' 	=> '"'.$_POST["filling_date_6"].'"',
			),
			CURLOPT_HTTPHEADER => array(
				'x-api-key: 91bcec91-ddf0-402c-b287-a03d3563c320',
				'Cookie: ci_session_twcss=22daacb0debc7649b7c988c9779d6293a8169158',
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;

		redirect(base_url('teamworks'));
	}

	public function pushIndividual() {
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://apps.teamworkcss.com/aaa_global/api/index/add_individual',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
			CURLOPT_USERPWD => USERNAME . ":" . PASSWORD,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array(
				'registration_no' 			=> '"'.$_POST["registration_no"].'"',
				'official' 					=> '"'.$_POST["official"].'"',
				'individual_name' 			=> '"'.$_POST["individual_name"].'"',
				'risk_assessment_rating' 	=> '"'.$_POST["risk_assessment_rating"].'"',
				'individual_former_name' 	=> '"'.$_POST["individual_former_name"].'"',
				'gender' 					=> '"'.$_POST["gender"].'"',
				'alias' 					=> '"'.$_POST["alias"].'"',
				'date_of_birth' 			=> '"'.$_POST["date_of_birth"].'"',
				'country_of_birth' 			=> '"'.$_POST["country_of_birth"].'"',
				'nationality' 				=> '"'.$_POST["nationality"].'"',
				'status' 					=> '"'.$_POST["status"].'"',
				'additional_notes' 			=> '"'.$_POST["additional_notes"].'"',
				'identification_type' 		=> '"'.$_POST["identification_type"].'"',
				'id_no' 					=> '"'.$_POST["id_no"].'"',
				'id_issued_country' 		=> '"'.$_POST["id_issued_country"].'"',
				'id_expiry_date' 			=> '"'.$_POST["id_expiry_date"].'"',
				'id_issued_date' 			=> '"'.$_POST["id_issued_date"].'"',
				'identification_type2' 		=> '"'.$_POST["identification_type2"].'"',

				'id_no2' 					=> '"'.$_POST["id_no2"].'"',
				'id_issued_country2' 		=> '"'.$_POST["id_issued_country2"].'"',
				'id_expiry_date2' 			=> '"'.$_POST["id_expiry_date2"].'"',
				'id_issued_date2' 			=> '"'.$_POST["id_issued_date2"].'"',
				'preferred_contact_mode' 	=> '"'.$_POST["preferred_contact_mode"].'"',
				'email_address' 			=> '"'.$_POST["email_address"].'"',
				'skype_id' 					=> '"'.$_POST["skype_id"].'"',
				'mobile_number' 			=> '"'.$_POST["mobile_number"].'"',
				'telephone_number' 			=> '"'.$_POST["telephone_number"].'"',
				'fax' 						=> '"'.$_POST["fax"].'"',
				'default_address' 			=> '"'.$_POST["default_address"].'"',
				'block_0' 					=> '"'.$_POST["block_0"].'"',
				'street_name_0' 			=> '"'.$_POST["street_name_0"].'"',
				'building_0' 				=> '"'.$_POST["building_0"].'"',
				'level_0' 					=> '"'.$_POST["level_0"].'"',
				'unit_no_0' 				=> '"'.$_POST["unit_no_0"].'"',
				'country_0' 				=> '"'.$_POST["country_0"].'"',
				'state_0' 					=> '"'.$_POST["state_0"].'"',
				'city_0' 					=> '"'.$_POST["city_0"].'"',
				'postal_code_0' 			=> '"'.$_POST["postal_code_0"].'"',
				'address_line1_1' 			=> '"'.$_POST["address_line1_1"].'"',
				'address_line2_1' 			=> '"'.$_POST["address_line2_1"].'"',
				'block_2' 					=> '"'.$_POST["block_2"].'"',
				'street_name_2' 			=> '"'.$_POST["street_name_2"].'"',
				'building_2' 				=> '"'.$_POST["building_2"].'"',
				'level_2' 					=> '"'.$_POST["level_2"].'"',
				'unit_no_2' 				=> '"'.$_POST["unit_no_2"].'"',
				'country_2' 				=> '"'.$_POST["country_2"].'"',
				'state_2' 					=> '"'.$_POST["state_2"].'"',
				'city_2' 					=> '"'.$_POST["city_2"].'"',
				'postal_code_2' 			=> '"'.$_POST["postal_code_2"].'"',
				'address_line1_3' 			=> '"'.$_POST["address_line1_3"].'"',
				'address_line2_3' 			=> '"'.$_POST["address_line2_3"].'"',
				'date_of_appointment_1' 	=> '"'.$_POST["date_of_appointment_1"].'"',
				'date_of_cessation_1' 		=> '"'.$_POST["date_of_cessation_1"].'"',

				'date_of_appointment_3' 	=> '"'.$_POST["date_of_appointment_3"].'"',
				'date_of_cessation_3' 		=> '"'.$_POST["date_of_cessation_3"].'"',
				'date_of_appointment_4' 	=> '"'.$_POST["date_of_appointment_4"].'"',
				'date_of_cessation_4' 		=> '"'.$_POST["date_of_cessation_4"].'"',
				'date_of_appointment_5' 	=> '"'.$_POST["date_of_appointment_5"].'"',
				'date_of_cessation_5' 		=> '"'.$_POST["date_of_cessation_5"].'"',
				'date_of_appointment_6' 	=> '"'.$_POST["date_of_appointment_6"].'"',
				'date_of_cessation_6' 		=> '"'.$_POST["date_of_cessation_6"].'"',
				'date_of_appointment_7' 	=> '"'.$_POST["date_of_appointment_7"].'"',
				'date_of_cessation_7' 		=> '"'.$_POST["date_of_cessation_7"].'"',
				'date_of_appointment_8' 	=> '"'.$_POST["date_of_appointment_8"].'"',
				'date_of_cessation_8' 		=> '"'.$_POST["date_of_cessation_8"].'"',
				'date_of_appointment_9' 	=> '"'.$_POST["date_of_appointment_9"].'"',
				'date_of_cessation_9' 		=> '"'.$_POST["date_of_cessation_9"].'"',
				'date_of_appointment_10' 	=> '"'.$_POST["date_of_appointment_10"].'"',
				'date_of_cessation_10' 		=> '"'.$_POST["date_of_cessation_10"].'"',
				'date_of_appointment_11' 	=> '"'.$_POST["date_of_appointment_11"].'"',
				'date_of_cessation_11' 		=> '"'.$_POST["date_of_cessation_11"].'"',
				'date_of_appointment_12' 	=> '"'.$_POST["date_of_appointment_12"].'"',
				'date_of_cessation_12' 		=> '"'.$_POST["date_of_cessation_12"].'"',

			),
			CURLOPT_HTTPHEADER => array(
				'x-api-key: 91bcec91-ddf0-402c-b287-a03d3563c320',
				'Cookie: ci_session_twcss=22daacb0debc7649b7c988c9779d6293a8169158',
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;

		redirect(base_url('teamworks'));
	}

	public function pushCorporateOfficer() {
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://apps.teamworkcss.com/aaa_global/api/index/',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
			CURLOPT_USERPWD => USERNAME . ":" . PASSWORD,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array(
				'registration_no' 			=> '"'.$_POST["registration_no"].'"',
				'official' 					=> '"'.$_POST["official"].'"',
				'corporate_registration_no' => '"'.$_POST["corporate_registration_no"].'"',
				'entity_name' 				=> '"'.$_POST["entity_name"].'"',
				'date_of_appointment_1' 	=> '"'.$_POST["date_of_appointment_1"].'"',
				'date_of_cessation_1' 		=> '"'.$_POST["date_of_cessation_1"].'"',
				'date_of_appointment_3' 	=> '"'.$_POST["date_of_appointment_3"].'"',
				'date_of_cessation_3' 		=> '"'.$_POST["date_of_cessation_3"].'"',
				'date_of_appointment_4' 	=> '"'.$_POST["date_of_appointment_4"].'"',
				'date_of_cessation_4' 		=> '"'.$_POST["date_of_cessation_4"].'"',
				'date_of_appointment_5' 	=> '"'.$_POST["date_of_appointment_5"].'"',
				'date_of_cessation_5' 		=> '"'.$_POST["date_of_cessation_5"].'"',
				'date_of_appointment_6' 	=> '"'.$_POST["date_of_appointment_6"].'"',
				'date_of_cessation_6' 		=> '"'.$_POST["date_of_cessation_6"].'"',
				'date_of_appointment_7' 	=> '"'.$_POST["date_of_appointment_7"].'"',
				'date_of_cessation_7' 		=> '"'.$_POST["date_of_cessation_7"].'"',
				'date_of_appointment_8' 	=> '"'.$_POST["date_of_appointment_8"].'"',
			),
			CURLOPT_HTTPHEADER => array(
				'x-api-key: 91bcec91-ddf0-402c-b287-a03d3563c320',
				'Cookie: ci_session_twcss=22daacb0debc7649b7c988c9779d6293a8169158',
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;

		redirect(base_url('teamworks'));
	}

	public function delete() {
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$bc = array(array('link' => '#', 'page' => lang('Teamworks')));
		$meta = array('page_title' => lang('Teamworks'), 'bc' => $bc, 'page_name' => 'Teamworks');

		$this->page_construct2('teamwork/add.php', $meta, $this->data);
	}

	public function delCompanies($client) {
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://apps.teamworkcss.com/aaa_global/api/index/delete_company?registration_no=' . $this->encryption->decrypt($client->registration_no),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
			CURLOPT_USERPWD => USERNAME . ":" . PASSWORD,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_HTTPHEADER => array(
				'x-api-key: 91bcec91-ddf0-402c-b287-a03d3563c320',
				'Cookie: ci_session_twcss=22daacb0debc7649b7c988c9779d6293a8169158',
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
	}

	public function deletecompanies() {
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://apps.teamworkcss.com/aaa_global/api/index/delete_company?registration_no=' . $this->input->post("registration_no"),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
			CURLOPT_USERPWD => USERNAME . ":" . PASSWORD,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_HTTPHEADER => array(
				'x-api-key: 91bcec91-ddf0-402c-b287-a03d3563c320',
				'Cookie: ci_session_twcss=22daacb0debc7649b7c988c9779d6293a8169158',
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	public function editCompanies() {
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://apps.teamworkcss.com/aaa_global/api/index/edit_company?registration_no=' . $this->input->post("company_registration_Num"),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
			CURLOPT_USERPWD => USERNAME . ":" . PASSWORD,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array(
				'entity_name' => $this->input->post("company_name"),
				//'company_name' => $this->input->post("company_name"),
				'registration_no' => $this->input->post("company_registration_Num"),
				'former_name_if_any' => $this->input->post("former_name_if_any"), //Codes for Companies Data of Teamwork API
				'company_id' => $this->input->post("company_id"),
				'entity_type' => $this->input->post("entity_type"),
				'acra_uen' => $this->input->post("acra_uen"),
				'country' => $this->input->post("country"),
				'region' => $this->input->post("region"),
				'entity_status' => $this->input->post("entity_status"),
				'risk_assessment_rating' => $this->input->post("risk_assessment_rating"),
				'incorporation_date' => $newincorporation_date,
				'internal_css_status' => $this->input->post("internal_css_status"),
				'Articles_constitution' => $this->input->post("Articles_constitution"),
				'article_regulation_no' => $this->input->post("article_regulation_no"),
				'article_description' => $this->input->post("article_description"),
				'dormant_date' => $newdormant_date,
				'dissolved_struck_off_date' => $newdissolved_struck_off_date,
				'liquid_strike_off_date' => $newliquid_strike_off_date,
				'termination_date' => $newtermination_date,
				'common_seal' => $this->input->post("common_seal"),
				'company_stamp' => $this->input->post("company_stamp"),
				'statute_registrable_corporate_controller' => $this->input->post("statute_registrable_corporate_controller"),
				'incorporated' => $this->input->post("incorporated"),
				'ssic_code_activity_I' => $this->input->post("ssic_code_activity_I"),
				'default_ssic_description_I' => $this->input->post("default_ssic_description_I"),
				'user_described_activity_I' => $this->input->post("user_described_activity_I"),
				'ssic_code_activity_II' => $this->input->post("ssic_code_activity_II"),
				'default_ssic_description_II' => $this->input->post("default_ssic_description_II"),
				'user_described_activity_II' => $this->input->post("user_described_activity_II"),
				'website' => $this->input->post("website"),
				'company_phone_1' => $this->input->post("company_phone_1"),
				'company_phone_2' => $this->input->post("company_phone_2"),
				'fax' => $this->input->post("fax"),
				'company_email_address' => $this->input->post("company_email_address"),
				'remarks' => $this->input->post("remarks"),
				'additional_remarks' => $this->input->post("additional_remarks"),
				'default_address' => $this->input->post("default_address"),
				'block_0' => $this->input->post("block_0"),
				'street_name_0' => $this->input->post("street_name_0"),
				'building_0' => $this->input->post("building_0"),
				'level_0' => $this->input->post("level_0"),
				'unit_no_0' => $this->input->post("unit_no_0"),
				'country_0' => $this->input->post("country_0"),
				'state_0' => $this->input->post("state_0"),
				'city_0' => $this->input->post("city_0"),
				'postal_code_0' => $this->input->post("postal_code_0"),
				'block_1' => $this->input->post("block_1"),
				'street_name_1' => $this->input->post("street_name_1"),
				'building_1' => $this->input->post("building_1"),
				'level_1' => $this->input->post("level_1"),
				'unit_no_1' => $this->input->post("unit_no_1"),
				'country_1' => $this->input->post("country_1"),
				'state_1' => $this->input->post("state_1"),
				'city_1' => $this->input->post("city_1"),
				'postal_code_1' => $this->input->post("postal_code_1"),
				'block_2' => $this->input->post("block_2"),
				'street_name_2' => $this->input->post("street_name_2"),
				'building_2' => $this->input->post("building_2"),
				'level_2' => $this->input->post("level_2"),
				'unit_no_2' => $this->input->post("unit_no_2"),
				'country_2' => $this->input->post("country_2"),
				'state_2' => $this->input->post("state_2"),
				'city_2' => $this->input->post("city_2"),
				'postal_code_2' => $this->input->post("postal_code_2"),
				'address_line1_3' => $this->input->post("address_line1_3"),
				'address_line2_3' => $this->input->post("address_line2_3"),
				'block_4' => $this->input->post("block_4"),
				'street_name_4' => $this->input->post("street_name_4"),
				'building_4' => $this->input->post("building_4"),
				'level_4' => $this->input->post("level_4"),
				'unit_no_4' => $this->input->post("unit_no_4"),
				'country_4' => $this->input->post("country_4"),
				'state_4' => $this->input->post("state_4"),
				'city_4' => $this->input->post("city_4"),
				'postal_code_4' => $this->input->post("postal_code_4"),
				'block_5' => $this->input->post("block_5"),
				'street_name_5' => $this->input->post("street_name_5"),
				'building_5' => $this->input->post("building_5"),
				'level_5' => $this->input->post("level_5"),
				'unit_no_5' => $this->input->post("unit_no_5"),
				'country_5' => $this->input->post("country_5"),
				'state_5' => $this->input->post("state_5"),
				'city_5' => $this->input->post("city_5"),
				'postal_code_5' => $this->input->post("postal_code_5"),
			),
			CURLOPT_POSTFIELDS => array(
				'entity_name' => $this->input->post("company_name"),
				'company_name' => $this->input->post("company_name"),
			),
			CURLOPT_HTTPHEADER => array(
				'x-api-key: 91bcec91-ddf0-402c-b287-a03d3563c320',
				'Cookie: ci_session_twcss=22daacb0debc7649b7c988c9779d6293a8169158',
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	public function getCompaniesJSON() {

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://apps.teamworkcss.com/aaa_global/api/index/companies',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
			CURLOPT_USERPWD => USERNAME . ":" . PASSWORD,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'x-api-key: 91bcec91-ddf0-402c-b287-a03d3563c320',
				'Cookie: ci_session_twcss=22daacb0debc7649b7c988c9779d6293a8169158',
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

	public function getCompanies() {

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://apps.teamworkcss.com/aaa_global/api/index/companies',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
			CURLOPT_USERPWD => USERNAME . ":" . PASSWORD,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'x-api-key: 91bcec91-ddf0-402c-b287-a03d3563c320',
				'Cookie: ci_session_twcss=22daacb0debc7649b7c988c9779d6293a8169158',
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		echo $response;
	}

	public function addcompanykeydate() {
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$bc = array(array('link' => '#', 'page' => lang('Teamworks')));
		$meta = array('page_title' => lang('Teamworks'), 'bc' => $bc, 'page_name' => 'Teamworks');

		$this->load->library('mybreadcrumb');
		$this->mybreadcrumb->add('Teamworks', base_url('teamworks'));
		$this->mybreadcrumb->add('Add Company Key Date', base_url());
		$this->data['breadcrumbs'] = $this->mybreadcrumb->render();

		$this->page_construct2('teamwork/addcompanykeydate.php', $meta, $this->data);
	}

	public function addcompanykeydateaction()
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://apps.teamworkcss.com/aaa_global/api/index/event_date',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
			CURLOPT_USERPWD => USERNAME . ":" . PASSWORD,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array(
				'registration_no' => $this->input->post("registration_no"),
                'type_of_event' => $this->input->post("type_of_event"),
                'year' => $this->input->post("year"),
                'actual_fye' => $this->input->post("actual_fye"),
                'fye_range_from' => $this->input->post("fye_range_from"),
                'fye_range_to' => $this->input->post("fye_range_to"),
                'held_date_1' => $this->input->post("held_date_1"),
                'filling_date_1' => $this->input->post("filling_date_1"),
                'held_date_2' => $this->input->post("held_date_2"),
                'filling_date_2' => $this->input->post("filling_date_2"),
                'held_date_3' => $this->input->post("held_date_3"),
                'filling_date_3' => $this->input->post("filling_date_3"),
                'held_date_4' => $this->input->post("held_date_4"),
                'filling_date_4' => $this->input->post("filling_date_4"),
                'held_date_5' => $this->input->post("held_date_5"),
                'filling_date_5' => $this->input->post("filling_date_5"),
                'held_date_6' => $this->input->post("held_date_6"),
                'filling_date_6' => $this->input->post("filling_date_6"),
			),
			CURLOPT_HTTPHEADER => array(
				'x-api-key: 91bcec91-ddf0-402c-b287-a03d3563c320',
				'Cookie: ci_session_twcss=22daacb0debc7649b7c988c9779d6293a8169158',
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	public function addofficer() {
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$bc = array(array('link' => '#', 'page' => lang('Teamworks')));
		$meta = array('page_title' => lang('Teamworks'), 'bc' => $bc, 'page_name' => 'Teamworks');

		$this->load->library('mybreadcrumb');
		$this->mybreadcrumb->add('Teamworks', base_url('teamworks'));
		$this->mybreadcrumb->add('Add Officer', base_url());
		$this->data['breadcrumbs'] = $this->mybreadcrumb->render();

		$this->page_construct2('teamwork/addofficer.php', $meta, $this->data);
	}

	public function addofficeraction()
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://apps.teamworkcss.com/aaa_global/api/index/add_corporate',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPAUTH => CURLAUTH_DIGEST,
			CURLOPT_USERPWD => USERNAME . ":" . PASSWORD,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array(
				'registration_no' => $this->input->post("registration_no"),
				'official' => $this->input->post("official"),
				'corporate_registration_no' => $this->input->post("corporate_registration_no"),
				'entity_name' => $this->input->post("entity_name"),
				'date_of_appointment_1' => $this->input->post("date_of_appointment_1"),
				'date_of_cessation_1' => $this->input->post("date_of_cessation_1"),
				'date_of_appointment_3' => $this->input->post("date_of_appointment_3"),
				'date_of_cessation_3' => $this->input->post("date_of_cessation_3"),
				'date_of_appointment_4' => $this->input->post("date_of_appointment_4"),
				'date_of_cessation_4' => $this->input->post("date_of_cessation_4"),
				'date_of_appointment_5' => $this->input->post("date_of_appointment_5"),
				'date_of_cessation_5' => $this->input->post("date_of_cessation_5"),
				'date_of_appointment_6' => $this->input->post("date_of_appointment_6"),
				'date_of_cessation_6' => $this->input->post("date_of_cessation_6"),
				'date_of_appointment_7' => $this->input->post("date_of_appointment_7"),
				'date_of_cessation_7' => $this->input->post("date_of_cessation_7"),
				'date_of_appointment_8' => $this->input->post("date_of_appointment_8"),
			),
			CURLOPT_HTTPHEADER => array(
				'x-api-key: 91bcec91-ddf0-402c-b287-a03d3563c320',
				'Cookie: ci_session_twcss=22daacb0debc7649b7c988c9779d6293a8169158',
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	public function importkeydate() {
		$regNo = $this->encryption->encrypt($this->input->post("registration_no"));
		$client = $this->db->query("select * from client where registration_no = '".$regNo."'")->result();
		
		foreach ($client as $key => $value) {
			// $clientData[$key]->id = $this->db_model->getClientbyID($value->id);
			$client[$key]->dbid = $value->id;
			$client[$key]->company_name = $this->encryption->decrypt($value->company_name);
			$client[$key]->registration_no = $this->encryption->decrypt($value->registration_no);
		}
		echo "select * from client where registration_no = '".$this->input->post("registration_no")."'";
		// print_r(json_encode($client));
	}
}