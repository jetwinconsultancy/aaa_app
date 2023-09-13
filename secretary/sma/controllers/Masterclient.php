<?php defined('BASEPATH') OR exit('No direct script access allowed');

use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Customer;

class Masterclient extends MY_Controller
{

    function __construct()
    {
        parent::__construct(); 

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }

        $this->load->model(array('db_model', 'master_model', 'transaction_model', 'transaction_word_model', 'quickbook_auth_model'));
        //$this->load->model('master_model');
        $this->load->library(array('encryption', 'session', 'form_validation'));
        $this->load->database();
        $this->digital_upload_path = 'files/';
        $this->upload_path = 'assets/uploads/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
        $this->allowed_file_size = '1024';
    }

    public function index()
    {	
       $bc = array(array('link' => '#', 'page' => lang('Clients')));
        $meta = array('page_title' => lang('Clients'), 'bc' => $bc, 'page_name' => 'Clients');

        if (isset($_POST['search'])) {
			if (isset($_POST['search']) && isset($_POST['tipe']))
			{
				$this->data['client'] = $this->db_model->getClient1($_SESSION['group_id'], $_POST['tipe'],$_POST['search'], $_POST['service_category']);
			} 
		}
		else
		{
			$this->data['client'] = $this->db_model->getClient1($_SESSION['group_id'], null, null, 0);
		}
		
        $this->db->select("*")
            ->from("users")
            ->where('id = "'.$this->session->userdata("user_id").'"');

        $user = $this->db->get();
        $user = $user->result_array();

        $currency_result = $this->db->query("select * from currency order by id");
		$currency_result = $currency_result->result_array();
		$currency_res = array();
        foreach($currency_result as $row) {
            $currency_res[$row['currency']] = $row['currency'];
        }
        $this->data["qb_company_id"] = $this->session->userdata('qb_company_id');
        $this->data["currency"] = $currency_res;
        $this->data["no_of_client"] = $user[0]["no_of_client"];
        $this->data["total_no_of_client"] = $user[0]["total_no_of_client"];
        $this->page_construct('client/masterclient.php', $meta, $this->data);
    }

	public function update_client() {
		
		if ($_POST['latest_client_id'] != "") {
			$sql = "UPDATE `client` SET 
				`acquried_by`= ".$_POST["acquried_by"].",
				`client_code`='".$_POST["client_code"]."',
				`registration_no`='".$this->encryption->encrypt($_POST["registration_no"])."',
				`company_name`='".$this->encryption->encrypt($_POST["company_name"])."',
				`change_name_effective_date`='".$_POST["change_name_effective_date"]."',
				`former_name`='".$_POST["former_name"]."',
				`incorporation_date`='".$_POST["incorporation_date"]."',
				`company_type`='".$_POST["company_type"]."',
				`status`=".$_POST["status"].",
				`activity1`='".$_POST["activity1"]."',
				`description1`='".$_POST["description1"]."',
				`activity2`='".$_POST["activity2"]."',
				`description2`='".$_POST["description2"]."',
				`postal_code`='".$_POST["postal_code"]."',
				`street_name`='".$_POST["street_name"]."',
				`building_name`='".$_POST["building_name"]."',
				`unit_no1`='".$_POST["unit_no1"]."',
				`unit_no2`='".$_POST["unit_no2"]."',
				`foreign_add_3`='".$_POST["foreign_add_3"]."'
				WHERE id = ".$_POST['latest_client_id'];

			$result = $this->db->query($sql);

			echo true;
		}
		echo false;
	}
    
	//test 
	public function test_index()
    {	
       $bc = array(array('link' => '#', 'page' => lang('Clients')));
        $meta = array('page_title' => lang('Clients'), 'bc' => $bc, 'page_name' => 'Clients');

        if (isset($_POST['search'])) {
			if (isset($_POST['search']) && isset($_POST['tipe']))
			{
				$this->data['client'] = $this->db_model->getClient1($_SESSION['group_id'], $_POST['tipe'],$_POST['search'], $_POST['service_category']);
			} 
		}
		else
		{
			$this->data['client'] = $this->db_model->getClient1($_SESSION['group_id'], null, null, 0);
		}
		
        $this->db->select("*")
            ->from("users")
            ->where('id = "'.$this->session->userdata("user_id").'"');

        $user = $this->db->get();
        $user = $user->result_array();

        $currency_result = $this->db->query("select * from currency order by id");
		$currency_result = $currency_result->result_array();
		$currency_res = array();
        foreach($currency_result as $row) {
            $currency_res[$row['currency']] = $row['currency'];
        }
        $this->data["qb_company_id"] = $this->session->userdata('qb_company_id');
        $this->data["currency"] = $currency_res;
        $this->data["no_of_client"] = $user[0]["no_of_client"];
        $this->data["total_no_of_client"] = $user[0]["total_no_of_client"];
        $this->page_construct('client/masterclient_test.php', $meta, $this->data);
    }
//test


    public function showClientDO()
    {
    	$Owner = $this->sma->in_group('owner') ? TRUE : NULL;
        $Client = $this->sma->in_group('client') ? TRUE : NULL;
        $Supplier = $this->sma->in_group('supplier') ? TRUE : NULL;
        $User = $this->sma->user_type('user') ? TRUE : NULL;
        $Admin = $this->sma->in_group('admin') ? TRUE : NULL;
        $Manager = $this->sma->in_group('manager') ? TRUE : NULL;
        $Bookkeeper = $this->sma->in_group('bookkeeper') ? TRUE : NULL;
        $Individual = $this->sma->user_type('Individual') ? TRUE : NULL;

    	$this->db->select('*')
                ->from('user_firm')
                ->where('user_firm.firm_id = '.$this->session->userdata('firm_id'))
                ->where('user_firm.user_id = '.$this->session->userdata('user_id'))
                ->where('user_firm.in_use = 1');
        $access_right = $this->db->get();
        $access_right = $access_right->result_array();

        if(count($access_right) > 0)
        {
            $filing_module = $access_right[0]["filing_module"];
            $billing_module = $access_right[0]["billing_module"];
            $unpaid_module = $access_right[0]["unpaid_module"];
        }
        else
        {
            $filing_module = null;
            $billing_module = null;
            $unpaid_module = null;
        }

    	$draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $column = intval($this->input->post("order[0][column]"));
        $order = $this->input->post("order[0][dir]");
        $service_category = $_POST['service_category'];
        $user_search = $_POST['user_search'];
        
        $total_client = $this->totalClient();
        $totalFiltered = $total_client;

        if (isset($user_search)) {
			if (isset($user_search))
			{
				$client = $this->db_model->getClient1($_SESSION['group_id'], null, $user_search, $service_category, $start, $length);
			} 
		}
		else
		{
			$client = $this->db_model->getClient1($_SESSION['group_id'], null, null, 0, $start, $length);
		}
		
	    if($column == 0)
	    {
	    	if($order != undefined)
	    	{
	    		usort($client,function($a,$b){
	    			$c = strnatcmp($a['registration_no'],$b['registration_no']);
	    			return $c;
				});
	    	}
	    }
	    else if($column == 1)
	    {
	    	usort($client,function($a,$b){
		    	$c = strcmp($a['company_name'],$b['company_name']);
		    	return $c;
			});
	    }
	    else if($column == 2)
	    {
	    	usort($client,function($a,$b){
		    	$c = strnatcasecmp($a['name'],$b['name']);
		    	return $c;
			});
	    }
	    else if($column == 3)
	    {
	    	usort($client,function($a,$b){
		    	$c = strnatcasecmp($a['phone'],$b['phone']);
		    	return $c;
			});
	    }
		else if($column == 4)
	    {
	    	usort($client,function($a,$b){
		    	$c = strnatcasecmp($a['email'],$b['email']);
		    	return $c;
			});
	    }
	    else if($column == 5)
	    {
	    	usort($client,function($a,$b){
		    	$c = strnatcasecmp($a["outstanding"],$b["outstanding"]);
		    	return $c;
			});
	    }
	    else if($column == 6)
	    {
	    	usort($client,function($a,$b){
		    	$c = strnatcmp($a["num_document"] + "Doc",$b["num_document"] + "Doc");
		    	return $c;
			});
	    }
	    else if($column == 8)
	    {
	    	usort($client,function($a,$b){
		    	$c = $a['concat_currency_name'] <=> $b['concat_currency_name'];
		    	return $c;
			});
	    }

		if($order == "desc")
		{
			$client = array_reverse($client);
		}
		if ($service_category == "0" && $user_search == NULL) {
			if($start > 0)
			{
				$start = $start + 1;
			}
			$client = array_slice($client, $start, ($start + $length - $start));
		}

		$i=1;
		$data = array();
		foreach($client as $key=>$c)
		{
			$alamat = ($c["street_name"]?$c["street_name"]:'').($c["unit_no1"]?" #".$c["unit_no1"]:'').($c["unit_no2"]?"-".$c["unit_no2"]:'').($c["building_name"]?" ".$c["building_name"]:'').($c["postal_code"]?" Singapore ".$c["postal_code"]:'');

			if(strlen($c['registration_no']) > 11)
			{
				$registration_no_readmore = '<a class="tonggle_readmore" data-id=p'.$i.'>...</a>';
			}
			else
			{
				$registration_no_readmore = '';
			}

			if(strlen($c['company_name']) > 24)
			{
				$company_name_readmore = '<a class="tonggle_readmore" data-id=f'.$i.'>...</a>';
			}
			else
			{
				$company_name_readmore = '';
			}

			if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) 
			{
				if(strlen($c['name']) > 12)
				{
					$contact_name_readmore = '<a class="tonggle_readmore" data-id=e'.$i.'>...</a>';
				}
				else
				{
					$contact_name_readmore = '';
				}

				if(strlen($c['phone']) > 12)
				{
					$contact_phone_readmore = '<a class="tonggle_readmore" data-id=k'.$i.'>...</a>';
				}
				else
				{
					$contact_phone_readmore = '';
				}

				if(strlen($c['email']) > 12)
				{
					$contact_email_readmore = '<a class="tonggle_readmore" data-id=n'.$i.'>...</a>';
				}
				else
				{
					$contact_email_readmore = '';
				}

				if($setup_module != null)
				{
					if($setup_module != "full" && !$Admin)
					{
						$contact_name = '<div style="width: 110px; word-break:break-all;">'.ucwords(substr($c["name"],0,12)).'<span id="e'.$i.'" style="display:none;">'.substr($c["name"],12,strlen($c["name"])).'</span>'.$contact_name_readmore.'</div>';
					}
					else
					{
						$contact_name = '<div style="width: 110px; word-break:break-all;"><a class="" href="'.site_url('masterclient/edit/'.$c["id"]."/setup").'" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Contact">'.ucwords(substr($c["name"],0,12)).'<span id="e'.$i.'" style="display:none;">'.substr($c["name"],12,strlen($c["name"])).'</span></a>'.$contact_name_readmore.'</div>';
					}
				}
				else
				{
					$contact_name = '<div style="width: 110px; word-break:break-all;"><a class="" href="'.site_url('masterclient/edit/'.$c["id"]."/setup").'" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Contact">'.ucwords(substr($c["name"],0,12)).'<span id="e'.$i.'" style="display:none;">'.substr($c["name"],12,strlen($c["name"])).'</span></a>'.$contact_name_readmore.'</div>';
				}

				if($setup_module != null)
				{
					if($setup_module != "full" && !$Admin)
					{
						$contact_phone = '<td><div style="width: 110px; word-break:break-all;">'.ucwords(substr($c["phone"],0,12)).'<span id="k'.$i.'" style="display:none;">'.substr($c["phone"],12,strlen($c["phone"])).'</span>'.$contact_phone_readmore.'</div></td>';
					}
					else
					{
						$contact_phone = '<td><div class="text-left" style="width: 110px; word-break:break-all;"><a class="" href="'.site_url('masterclient/edit/'.$c["id"]."/setup").'" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Contact">'.ucwords(substr($c["phone"],0,12)).'<span id="k'.$i.'" style="display:none;">'.substr($c["phone"],12,strlen($c["phone"])).'</span></a>'.$contact_phone_readmore.'</div></td>';
					}
				}
				else
				{
					$contact_phone = '<td><div class="text-left" style="width: 110px; word-break:break-all;"><a class="" href="'.site_url('masterclient/edit/'.$c["id"]."/setup").'" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Contact">'.ucwords(substr($c["phone"],0,12)).'<span id="k'.$i.'" style="display:none;">'.substr($c["phone"],12,strlen($c["phone"])).'</span></a>'.$contact_phone_readmore.'</div></td>';
				}

				if($setup_module != null)
				{
					if($setup_module != "full" && !$Admin)
					{
						$contact_email = '<td><div class="text-left" style="width: 110px; word-break:break-all;">'.ucwords(substr($c["email"],0,12)).'<span id="n'.$i.'" style="display:none;">'.substr($c["email"],12,strlen($c["email"])).'</span>'.$contact_email_readmore.'</div></td>';
					}
					else
					{
						$contact_email = '<td><div class="text-left" style="width: 110px; word-break:break-all;"><a class="" href="'.site_url('masterclient/edit/'.$c["id"]."/setup").'" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Contact">'.ucwords(substr($c["email"],0,12)).'<span id="n'.$i.'" style="display:none;">'.substr($c["email"],12,strlen($c["email"])).'</span></a>'.$contact_email_readmore.'</div></td>';
					}
				}
				else
				{
					$contact_email = '<td><div class="text-left" style="width: 110px; word-break:break-all;"><a class="" href="'.site_url('masterclient/edit/'.$c["id"]."/setup").'" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Contact">'.ucwords(substr($c["email"],0,12)).'<span id="n'.$i.'" style="display:none;">'.substr($c["email"],12,strlen($c["email"])).'</span></a>'.$contact_email_readmore.'</div></td>';
				}
			}

			if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && $Client) || (!$Individual && $Individual == null && !$Client && $Client == null)) 
			{
				if($billing_module != null)
				{
					if($billing_module != "full" && !$Admin)
					{
						$outstanding = '<td><div class="text-right" style="word-break:break-all;">'.number_format($c["outstanding"],2).'</div></td>';
					}
					else
					{
						$outstanding = '<td><div class="text-right" style="word-break:break-all;"><a class="" href="'.site_url('billings/index/'.$c["company_code"]).'" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Update Payment of This Balance">'.number_format($c["outstanding"],2).'</a></div></td>';
					}
				}
				else if($unpaid_module != "full" && !$Admin)
				{
					$outstanding = '<td><div class="text-right" style="word-break:break-all;">'.number_format($c["outstanding"],2).'</div></td>';
				}
				else
				{
					$outstanding = '<td><div class="text-right" style="word-break:break-all;"><a class="" href="'.site_url('billings/index/'.$c["company_code"]).'" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Update Payment of This Balance">'.number_format($c["outstanding"],2).'</a></div></td>';
				}
			}

			if($Individual || $Client) 
			{
				$document = '<div style="word-break:break-all;" class="text-right">'.number_format($c["num_document"],0).' Doc</div>';
			}
			else
			{
				$document = '<div style="word-break:break-all;" class="text-right"><a class="" href="'.base_url().'documents" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Update Documents Received">'.number_format($c["num_document"],0).' Doc</a></div>';
			}

			if(isset($c["175_extended_to"]))
			{
				if($c["175_extended_to"] != 0)
				{
					$due_date_175 = $c["175_extended_to"];
				}
				else
				{
					if(isset($c["due_date_175"]))
					{
						$due_date_175 = $c["due_date_175"];
					}
					else
					{
						$due_date_175 = '';
					}
				}
			}
			else
			{
				if(isset($c["due_date_175"]))
				{
					$due_date_175 = $c["due_date_175"];
				}
				else
				{
					$due_date_175 = '';
				}
			}

			if(isset($c["201_extended_to"]))
			{
				if($c["201_extended_to"] != 0)
				{
					$due_date_201 = $c["201_extended_to"];
				}
				else
				{
					if(isset($c["due_date_201"]))
					{
						$due_date_201 = $c["due_date_201"];
					}
					else
					{
						$due_date_201 = '';
					}
				}
			}
			else
			{
				if(isset($c["due_date_201"]))
				{
					$due_date_201 = $c["due_date_201"];
				}
				else
				{
					$due_date_201 = '';
				}
			}
			if( strtotime($due_date_175) > strtotime($due_date_201) || strtotime($due_date_175) == strtotime($due_date_201))
			{
				if($filing_module != null)
				{
					if($filing_module != "full" && !$Admin)
					{
						$due_date = ''.$due_date_201.'';
					}
					else
					{
						$due_date = '<a class="" href="'.site_url("masterclient/edit/".$c["id"]."/filing").'" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Update Filing Information">'.$due_date_201.'</a>';
					}
				}
				else if($Individual || $Client)
				{
					$due_date = ''.$due_date_201.'';
				}
				else
				{
					$due_date = '<a class="" href="'.site_url("masterclient/edit/".$c["id"]."/filing").'" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Update Filing Information">'.$due_date_201.'</a>';
				}
				
			}
			elseif( strtotime($due_date_201) > strtotime($due_date_175))
			{
				if($filing_module != null)
				{
					if($filing_module != "full" && !$Admin)
					{
						$due_date = ''.$due_date_201.'';
					}
					else
					{
						$due_date = '<a class="" href="'.site_url("masterclient/edit/".$c["id"]."/filing").'" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Update Filing Information">'.$due_date_201.'</a>';
					}
				}
				else if($Individual || $Client)
				{
					$due_date = ''.$due_date_175.'';
				}
				else
				{
					$due_date = '<a class="" href="'.site_url("masterclient/edit/".$c["id"]."/filing").'" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Update Filing Information">'.$due_date_201.'</a>';
				}
			}

			if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) 
			{
				$delete_button = '<input type="text" class="form-control hidden" name="each_client_id" id="each_client_id" value="'.$c["id"].'"/><button type="button" class="btn btn-primary delete_client" onclick="delete_client(this)">Delete</button>';
				if(count($c["concat_currency_name"]) > 0 && $c["concat_currency_name"] != "")
				{
					$client_qb_status = $c["concat_currency_name"];
				}
				else
				{
					$client_qb_status = "Not";
				}

				$client_quickbooks_status = $client_qb_status;
			}
			else
			{
				$delete_button = '';
				$client_quickbooks_status =  '';
			}

			$data[]= array(
                '<div style="width: 100px; word-break:break-all;">'.ucwords(substr($c["registration_no"],0,11)).'<span id="p'.$i.'" style="display:none;">'.substr($c["registration_no"],11,strlen($c["registration_no"])).'</span>'.$registration_no_readmore.'</div>',
                '<div style="width: 200px; word-break:break-all;"><a class="" href="'.site_url('masterclient/edit/'.$c["id"]).'" data-name="'.$c["company_name"].'" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Client">'.ucwords(substr($c["company_name"],0,24)).'<span id="f'.$i.'" style="display:none;cursor:pointer">'.substr($c["company_name"],24,strlen($c["company_name"])).'</span></a>'.$company_name_readmore.'</div>',
                $contact_name,
                $contact_phone,
                $contact_email,
                $outstanding,
                $document,
                $due_date,
                $client_quickbooks_status,
                $delete_button
            ); 

            $i++;
		}

		$output = array(
            "draw" => $draw,
            "recordsTotal" => $total_client,
            "recordsFiltered" => $totalFiltered,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    public function totalClient()
    {
    	$result = $this->master_model->get_ClientCount();
        if(isset($result)) return $result->num;
        return 0;
    }

    public function get_servicing_firm()
    {
    	$get_all_firm_info = $this->transaction_model->getAllFirmInfo();
		for($j = 0; $j < count($get_all_firm_info); $j++)
		{
			if($get_all_firm_info[$j]->branch_name != null)
			{
				$res_firm[$get_all_firm_info[$j]->id] = $get_all_firm_info[$j]->name.' ('.$get_all_firm_info[$j]->branch_name.')';
			}
			else
			{
				$res_firm[$get_all_firm_info[$j]->id] = $get_all_firm_info[$j]->name;
			}
		}

		$data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Frequency fetched successfully.", 'result'=>$res_firm);

        echo json_encode($data);
    }
	
	public function add ()
	{
        $bc = array(array('link' => '#', 'page' => lang('Create Client')));
        $meta = array('page_title' => lang('Create Client'), 'bc' => $bc, 'page_name' => 'Create Client');
		$this->data['sharetype'] = $this->master_model->get_all_share_type();
		$this->data['currency'] = $this->master_model->get_all_currency();
		//$this->data['citizen'] = $this->master_model->get_all_citizen();
		$this->data['person'] = $this->master_model->get_all_person();
		//$this->data['typeofdoc'] = $this->master_model->get_all_typeofdoc();
		//$this->data['doccategory'] = $this->master_model->get_all_doccategory();
		$this->data['client_service'] = $this->master_model->get_all_client_service();
		$this->data['client_billing_info'] = $this->master_model->get_all_default_client_service();
		$this->data['firm_info'] = $this->master_model->get_firm_info();

		$this->session->set_userdata(array(
            'company_type'  => null,
        ));
		$this->session->set_userdata(array(
            'company_code'  => null,
        ));
		$this->session->set_userdata(array(
            'acquried_by'  =>  null,
        ));
        $this->session->set_userdata(array(
            'status'  => null,
        ));

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Clients', base_url('masterclient'));
		$this->mybreadcrumb->add('Create Client', base_url());

		$this->data['breadcrumbs'] = $this->mybreadcrumb->render();

		$this->db->select('our_service_registration_address.id, our_service_info.service_name, our_service_registration_address.postal_code, our_service_registration_address.street_name, our_service_registration_address.building_name, our_service_registration_address.unit_no1, our_service_registration_address.unit_no2, our_service_registration_address.foreign_address_1, our_service_registration_address.foreign_address_2, our_service_registration_address.foreign_address_3, gst_jurisdiction.jurisdiction as jurisdiction_name')
                ->from('firm')
                ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
                ->join('our_service_info', 'our_service_info.user_admin_code_id = '.$this->session->userdata('user_admin_code_id').' and service_type = 7', 'left')
                ->join('our_service_registration_address', 'our_service_registration_address.our_service_info_id = our_service_info.id', 'left')
                ->join('gst_jurisdiction', 'gst_jurisdiction.id = our_service_registration_address.jurisdiction_id', 'left')
                ->where('user_firm.user_id = '.$this->session->userdata('user_id'))
                ->where('user_firm.in_use = 1 AND our_service_info.deleted = 0');
        $registered_address = $this->db->get();

        $registered_address_info = $registered_address->result_array();

        $this->data['first_time'] = false;
        $this->data['registered_address_info'] = $registered_address_info;

		//$this->data['officer'] =$this->db_model->getOfficerUC($unique_code);

        $this->page_construct('client/edit_client.php', $meta, $this->data);
		
	}

	public function check_client_data()
	{
		if ($_POST['registration_no'] != ""  && $_POST['latest_client_id'] == "") {

			$this->data['clients'] = $this->db->query("select `client`.`registration_no` from client where `client`.`deleted` = 0")->result();
			$found = false;
			foreach ($this->data['clients'] as $key => $value) {
				# code...
				// $this->data['clients'][$key]->company_name = $this->encryption->decrypt($value->company_name);
				$registration_no = $this->encryption->decrypt($value->registration_no);
				if ($registration_no != false && $registration_no != "" && $registration_no == $_POST['registration_no']) {
					$found = true;
				}
			}

			if ($found) {
				echo "Duplicate Registration No";
			}
		}

		$company_code=$_POST['company_code'];

		$check_address = [];
		$check_company_name = [];
		$check_company_type = [];
		$check_activity1 = [];
		$check_activity2 = [];
		

		$check_address[0]['postal_code']=$_POST['postal_code'];
		$check_address[0]['street_name']=$_POST['street_name'];
		$check_address[0]['building_name']=$_POST['building_name'];
		$check_address[0]['unit_no1']=$_POST['unit_no1'];
		$check_address[0]['unit_no2']=$_POST['unit_no2'];

		$check_company_name[0]['company_name']=$_POST['company_name'];

		$check_company_type[0]['company_type']=$_POST['company_type'];

		$check_activity1[0]["activity1"]=$_POST['activity1'];
		$check_activity2[0]["activity2"]=$_POST['activity2'];

		$query = $this->db->get_where("history_client", array("company_code" => $company_code));

		if (!$query->num_rows())//if don't have anythings
		{
			echo false;
		}
		else
		{
			$query = $query->result_array();

			$old_client_company_name_result = $this->db->query("select company_name from client where company_code='".$company_code."'");

			$old_client_company_name_result = $old_client_company_name_result->result_array();

			if(!($old_client_company_name_result == $check_company_name))
			{
				$pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $query[0]["id"], "received_on" => "", "triggered_by" => "2"));
				if($pending_documents_query->num_rows())
				{
					echo true;
				}
				else
				{
					echo false;
				}
			}
			else
			{
				echo false;
			}

			$old_client_address_result = $this->db->query("select postal_code, street_name, building_name, unit_no1, unit_no2 from client where company_code='".$company_code."'");

			$old_client_address_result = $old_client_address_result->result_array();

			if(!($old_client_address_result == $check_address))
			{
				$pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $query[0]["id"], "received_on" => "", "triggered_by" => "7"));

				if($pending_documents_query->num_rows())
				{
					echo true;
				}
				else
				{
					echo false;
				}
			}
			else
			{
				echo false;
			}

			$old_client_company_type_result = $this->db->query("select company_type from client where company_code='".$company_code."'");

			$old_client_company_type_result = $old_client_company_type_result->result_array();

			if(!($old_client_company_type_result == $check_company_type))
			{
				$pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $query[0]["id"], "received_on" => "", "triggered_by" => "3"));

				if($pending_documents_query->num_rows())
				{
					echo true;
				}
				else
				{
					echo false;
				}
			}
			else
			{
				echo false;
			}

			if($_POST['acquried_by'] == 1)
			{
				$pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $query[0]["id"], "received_on" => "", "triggered_by" => "1"));

				if($pending_documents_query->num_rows())
				{
					echo true;
				}
				else
				{
					echo false;
				}
			}

			if($_POST['status'] == 2 || $_POST['status'] == 3)
			{
				$pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $query[0]["id"], "received_on" => "", "triggered_by" => "4"));

				if($pending_documents_query->num_rows())
				{
					echo true;
				}
				else
				{
					echo false;
				}
			}

			$old_client_company_activity1_result = $this->db->query("select activity1 from client where company_code='".$company_code."'");

			$old_client_company_activity1_result = $old_client_company_activity1_result->result_array();

			if(!($old_client_company_activity1_result == $check_activity1))
			{
				$pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $query[0]["id"], "received_on" => "", "triggered_by" => "5"));

				if($pending_documents_query->num_rows())
				{
					echo true;
				}
				else
				{
					echo false;
				}
			}
			else
			{
				echo false;
			}

			$old_client_company_activity2_result = $this->db->query("select activity2 from client where company_code='".$company_code."'");

			$old_client_company_activity2_result = $old_client_company_activity2_result->result_array();

			if(!($old_client_company_activity2_result == $check_activity2))
			{
				$pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $query[0]["id"], "received_on" => "", "triggered_by" => "6"));

				if($pending_documents_query->num_rows())
				{
					echo true;
				}
				else
				{
					echo false;
				}
			}
			else
			{
				echo false;
			}
			
		}

	}

	public function save() //save_client
	{
		// print_r($_POST);
		// exit;
		if(!isset($_POST['client_code']) && !isset($_POST['registration_no']) && !isset($_POST['acquried_by']) && !isset($_POST['company_name']) && !isset($_POST['incorporation_date']) && !isset($_POST['company_type']) && !isset($_POST['activity1']) && !isset($_POST['postal_code']) && !isset($_POST['street_name']))
		{
			$data['status']=$_POST['status'];

			$this->db->update("client",$data,array("company_code" =>  $_POST['company_code']));

			echo json_encode(array("Status" => 3,'message' => 'Information Updated', 'title' => 'Updated'));

		}
		else
		{
			$this->form_validation->set_rules('client_code', 'Client Code', 'required');
	        $this->form_validation->set_rules('registration_no', 'Registration No', 'required');
	        $this->form_validation->set_rules('company_name', 'Company Name', 'required');
	        /*$this->form_validation->set_rules('former_name', 'Former Name', 'required');*/
	        $this->form_validation->set_rules('incorporation_date', 'Incorporation Date', 'required');
	        $this->form_validation->set_rules('company_type', 'Company Type', 'required');
	        $this->form_validation->set_rules('activity1', 'Activity 1', 'required');
	        /*$this->form_validation->set_rules('activity2', 'Activity 2', 'required');*/
	        if(isset($_POST['postal_code']) && !isset($_POST['use_foreign_add_as_billing_add']))
	        {
	        	$this->form_validation->set_rules('postal_code', 'Postal Code', 'required');
	        }

	        if(isset($_POST['street_name']) && !isset($_POST['use_foreign_add_as_billing_add']))
	        {
	        	$this->form_validation->set_rules('street_name', 'Street Name', 'required');
	        }

	        if(isset($_POST['foreign_add_1']))
	        {
	        	$this->form_validation->set_rules('foreign_add_1', 'Foreign Address 1', 'required');
	        }

	        if(isset($_POST['foreign_add_2']))
	        {
	        	$this->form_validation->set_rules('foreign_add_2', 'Foreign Address 2', 'required');
	        }

	        if ($this->form_validation->run() == FALSE || $_POST['company_type'] == "0" || $_POST['status'] == "0" || $_POST['acquried_by'] == "0")
	        {
	        	$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
				$bc = array(array('link' => '#', 'page' => lang('Add Clients')));
		        $meta = array('page_title' => lang('Add Clients'), 'bc' => $bc, 'page_name' => 'Add Clients');

	        	if($_POST['company_type'] == "0")
	            {
	                $validate_company_type = "*The Company Type field is required.";
	            }
	            else
	            {
	                $validate_company_type = "";
	            }
	            if($_POST['acquried_by'] == "0")
	            {
	                $validate_acquried_by = "The Acquried By field is required.";
	            }
	            else
	            {
	                $validate_acquried_by = "";
	            }
	            if($_POST['status'] == "0")
	            {
	                $validate_status = "The Status field is required.";
	            }
	            else
	            {
	                $validate_status = "";
	            }

	            $error = array(
	                'client_code' => strip_tags(form_error('client_code')),
	                'registration_no' => strip_tags(form_error('registration_no')),
	                'company_name' => strip_tags(form_error('company_name')),
	                'company_type' => $validate_company_type,
	                /*'former_name' => strip_tags(form_error('former_name')),*/
	                'incorporation_date' => strip_tags(form_error('incorporation_date')),
	                'activity1' => strip_tags(form_error('activity1')),
	                /*'activity2' => strip_tags(form_error('activity2')),*/
	                'postal_code' => strip_tags(form_error('postal_code')),
	                'street_name' => strip_tags(form_error('street_name')),
	                'foreign_add_1' => strip_tags(form_error('foreign_add_1')),
	                'foreign_add_2' => strip_tags(form_error('foreign_add_2')),
	                'status' => $validate_status,
	                'acquried_by' => $validate_acquried_by,
	            );
	            $this->data["company_type"] = $validate_company_type;    

	            $registered_address = $this->db->query("select postal_code, street_name, building_name, unit_no1, unit_no2 from firm ");
		        $registered_address_info = $registered_address->result_array();
		        $this->data['registered_address_info'] = $registered_address_info;  

		        $this->session->set_userdata(array(
	                    'company_type'  => $_POST['company_type'],
	                ));      
		        $this->session->set_userdata(array(
	                'company_type'  =>  $_POST['acquried_by'],
	            ));
	            $this->session->set_userdata(array(
	                'status'  => $_POST['status'],
	            ));

	            echo json_encode(array("Status" => 0, 'message' => 'Please complete all required field', 'title' => 'Error', "error" => $error, $this->data));
	        }
	        else
	        {
	        	$date_of_appointment = $this->db->query("select date_of_appointment from client_officers where company_code = '".$_POST['company_code']."' AND STR_TO_DATE(date_of_appointment,'%d/%m/%Y') < STR_TO_DATE('". $_POST['incorporation_date']. "','%d/%m/%Y') ");
	        	
		        if ($date_of_appointment->num_rows())
				{
					echo json_encode(array("Status" => 2,'message' => 'Appointment date of some officers are dated prior to incorporation date. Please change the date of appointment of all relevant officers first before changing the date of incorporation.', 'title' => 'Error'));
				}
				else
				{
					$duplicate_flag = true;
		        	$check_unique_client_code = $this->db->get_where("client", array("client_code" => $_POST['client_code'], "company_code !=" => $_POST['company_code'], "deleted !=" => 1));

					if (!$check_unique_client_code->num_rows()) {
						$duplicate_flag = false;
					}
					if (isset($_POST['client_code']) && $_POST['client_code'] != "") {
						$duplicate_flag = false;
					}

		        	if (!$duplicate_flag)
					{
						$check_unique_registration_no = $this->db->get_where("client", array("deleted !=" => 1));
						$check_unique_registration_no_array = $check_unique_registration_no->result_array();

						$unique_registration_no = true;
						for($r = 0; $r < count($check_unique_registration_no_array); $r++)
						{
							if($this->encryption->decrypt($check_unique_registration_no_array[$r]["registration_no"]) == trim($_POST['registration_no']) && $check_unique_registration_no_array[$r]["company_code"] != $_POST['company_code'])
							{
								$unique_registration_no = false;
							}
						}

						if ($unique_registration_no)
						{
							$change_cn = false; 
							$data['created_by']=$this->session->userdata('user_id');

							$data['acquried_by']=$_POST['acquried_by'];
							$data['company_code']=$_POST['company_code'];
							$company_code=$data['company_code'];
							$data['client_code']=strtoupper($_POST['client_code']);
							$data['registration_no']=$this->encryption->encrypt(trim(strtoupper($_POST['registration_no'])));
							$registration_no = $data['registration_no'];
							$data['company_name']=$this->encryption->encrypt(trim(strtoupper($_POST['company_name'])));
							$data['former_name']=strtoupper($_POST['former_name']);

							$data['incorporation_date']=$_POST['incorporation_date'];
							$data['company_type']=$_POST['company_type'];
							$data['status']=$_POST['status'];
							$data['activity1']=strtoupper($_POST['activity1']);
							$data['description1']=$_POST['description1'];
							$data['activity2']=strtoupper($_POST['activity2']);
							$data['description2']=$_POST['description2'];
							$data['registered_address']=(isset($_POST['use_registered_address'])) ? 1 : 0;
							$data['our_service_regis_address_id']= $_POST['service_reg_off'];

							$data['postal_code']=strtoupper($_POST['postal_code']);
							/*$data['city']=$_POST['city'];*/
							$data['street_name']=strtoupper($_POST['street_name']);
							$data['building_name']=strtoupper($_POST['building_name']);
							$data['unit_no1']=strtoupper($_POST['unit_no1']);
							$data['unit_no2']=strtoupper($_POST['unit_no2']);
							$data['foreign_add_1']=strtoupper($_POST['foreign_add_1']);
							$data['foreign_add_2']=strtoupper($_POST['foreign_add_2']);
							$data['foreign_add_3']=strtoupper($_POST['foreign_add_3']);
							$data['use_foreign_add_as_billing_add']=(isset($_POST['use_foreign_add_as_billing_add'])) ? 1 : 0;

							$q = $this->db->get_where("client", array("company_code" => $_POST['company_code']));

							if (!$q->num_rows())
							{
								if ($data['registration_no'] && $data['company_name'])
								{
									$data['firm_id']=$this->session->userdata('firm_id');
									$this->db->insert("client",$data);
									$client_id = $this->db->insert_id();
									$this->save_audit_trail("Clients", "Company Info", "New Company ".$_POST['company_name']." is added.");
									$this->recalculate();
								}
							} 
							else 
							{
								if ($data['registration_no'] && $data['company_name'])
								{
									$old_client_data = $q->result_array();

									if($this->encryption->decrypt($old_client_data[0]["company_name"]) != $_POST['company_name'])
									{
										if($old_client_data[0]["change_name_effective_date"] != "")
										{
											$data['former_name'] = strtoupper($this->encryption->decrypt($old_client_data[0]["company_name"]))." (w.e.f.".$old_client_data[0]["change_name_effective_date"].")\r\n".$data['former_name'];
										}
										else
										{
											$data['former_name'] = strtoupper($this->encryption->decrypt($old_client_data[0]["company_name"]))."\r\n".$data['former_name'];
										}
										
										$change_company_name['subsidiary_name'] = strtoupper($_POST['company_name']);

										$this->db->update("corporate_representative",$change_company_name,array("subsidiary_name" => $this->encryption->decrypt($old_client_data[0]["company_name"])));
										$change_cn = true; 
									}
									else
									{
										$change_cn = false; 
									}

									$check_address = [];
									$check_company_name = [];
									$check_company_type = [];
									$check_activity1 = [];
									$check_activity2 = [];

									$check_address[0]['postal_code']=$_POST['postal_code'];
									$check_address[0]['street_name']=$_POST['street_name'];
									$check_address[0]['building_name']=$_POST['building_name'];
									$check_address[0]['unit_no1']=$_POST['unit_no1'];
									$check_address[0]['unit_no2']=$_POST['unit_no2'];

									$check_company_name[0]['company_name']=$_POST['company_name'];

									$check_company_type[0]['company_type']=$_POST['company_type'];

									$check_acquried_by=$_POST['acquried_by'];

									$check_status=$_POST['status'];

									$check_activity1[0]["activity1"]=$_POST['activity1'];

									$check_activity2[0]["activity2"]=$_POST['activity2'];

									$old_client_address_result = $this->db->query("select postal_code, street_name, building_name, unit_no1, unit_no2 from client where company_code='".$company_code."'");

									$old_client_company_name_result = $this->db->query("select company_name from client where company_code='".$company_code."'");

									$old_client_company_type_result = $this->db->query("select company_type from client where company_code='".$company_code."'");

									$old_client_company_activity1_result = $this->db->query("select activity1 from client where company_code='".$company_code."'");

									$old_client_company_activity2_result = $this->db->query("select activity2 from client where company_code='".$company_code."'");

									$old_client_address_result = $old_client_address_result->result_array();

									$old_client_company_name_result = $old_client_company_name_result->result_array();

									$old_client_company_type_result = $old_client_company_type_result->result_array();

									$old_client_company_activity1_result = $old_client_company_activity1_result->result_array();

									$old_client_company_activity2_result = $old_client_company_activity2_result->result_array();

									$this->db->update("client",$data,array("company_code" =>  $_POST['company_code']));
									$this->save_audit_trail("Clients", "Company Info", "Company ".$_POST['company_name']." is edited.");
									$client_id = $_POST['latest_client_id'];

									if($check_acquried_by == 1 || $check_acquried_by == 2)
									{
										$check_history_client = $this->db->get_where("history_client", array("company_code" => $_POST['company_code']));

										$query_check_history_client = $check_history_client->result_array();

										
										if (!$check_history_client->num_rows())
										{
											$k = $q->result();

											foreach($k as $r) {
										        $this->db->insert("history_client",$r);
										    }
										} 
										else 
										{
											//$c = $q->result_array();

											$data_history['acquried_by'] = $check_acquried_by;

										    $this->db->update("history_client",$data_history,array("company_code" =>  $_POST['company_code']));
											
										}

										if($query_check_history_client[0]["acquried_by"] == 2)
										{
											//$this->create_document("acquried_by", $_POST['company_code']);
										}
									}

									if($check_status == 2 || $check_status == 3)
									{
										$check_history_client = $this->db->get_where("history_client", array("company_code" => $_POST['company_code']));
										if (!$check_history_client->num_rows())
										{
											$k = $q->result();

											foreach($k as $r) {
										        $this->db->insert("history_client",$r);
										    }
										} 
										else 
										{
											//$c = $q->result_array();

											$data_history['status'] = $check_status;

										    $this->db->update("history_client",$data_history,array("company_code" =>  $_POST['company_code']));
											
										}
									}

									if(!($old_client_company_activity1_result == $check_activity1))
									{
										$check_history_client = $this->db->get_where("history_client", array("company_code" => $_POST['company_code']));
										if (!$check_history_client->num_rows())
										{
											$w = $q->result();

											foreach($w as $r) {
										        $this->db->insert("history_client",$r);
										    }
										} 
										else 
										{
											$x = $q->result_array();

											$data_history['activity1'] = $x[0]["activity1"];

										    $this->db->update("history_client",$data_history,array("company_code" =>  $_POST['company_code']));
											
										}
									}

									if($old_client_company_activity2_result[0]["activity2"] != '')
									{
										if(!($old_client_company_activity2_result == $check_activity2))
										{
											$check_history_client = $this->db->get_where("history_client", array("company_code" => $_POST['company_code']));
											if (!$check_history_client->num_rows())
											{
												$w = $q->result();

												foreach($w as $r) {
											        $this->db->insert("history_client",$r);
											    }
											} 
											else 
											{
												$x = $q->result_array();

												$data_history['activity2'] = $x[0]["activity2"];

											    $this->db->update("history_client",$data_history,array("company_code" =>  $_POST['company_code']));
												
											}
										}
									}

									if(!($old_client_company_type_result == $check_company_type))
									{
										$check_history_client = $this->db->get_where("history_client", array("company_code" => $_POST['company_code']));
										if (!$check_history_client->num_rows())
										{
											$d = $q->result();

											foreach($d as $r) {
										        $this->db->insert("history_client",$r);
										    }
										} 
										else 
										{
											$c = $q->result_array();

											$data_history['company_type'] = $c[0]["company_type"];

										    $this->db->update("history_client",$data_history,array("company_code" =>  $_POST['company_code']));
											
										}
									}

									if(!($old_client_address_result == $check_address))
									{
										$check_history_client = $this->db->get_where("history_client", array("company_code" => $_POST['company_code']));
										if (!$check_history_client->num_rows())
										{
											$t = $q->result();

											foreach($t as $r) {
										        $this->db->insert("history_client",$r);
										    }
										} 
										else 
										{
											$h = $q->result_array();

											$data_history['registered_address'] = $h[0]["registered_address"];
											$data_history['our_service_regis_address_id'] = $h[0]["our_service_regis_address_id"];
											$data_history['postal_code'] = $h[0]["postal_code"];
											$data_history['street_name'] = $h[0]["street_name"];
											$data_history['building_name'] = $h[0]["building_name"];
											$data_history['unit_no1'] = $h[0]["unit_no1"];
											$data_history['unit_no2'] = $h[0]["unit_no2"];

										    $this->db->update("history_client",$data_history,array("company_code" =>  $_POST['company_code']));
											
										}
									}
									if(!($old_client_company_name_result == $check_company_name))
									{
										$check_history_client = $this->db->get_where("history_client", array("company_code" => $_POST['company_code']));
										if (!$check_history_client->num_rows())
										{
											$f = $q->result();

											foreach($f as $r) {
										        $this->db->insert("history_client",$r);
										    }
										} 
										else 
										{
											$d = $q->result_array();

											$data_history['company_name'] = $d[0]["company_name"];

										    $this->db->update("history_client",$data_history,array("company_code" =>  $_POST['company_code']));
											
										}
									}
								}
							}

							$this->data['client_billing_data'] = $this->master_model->get_all_client_billing_info($_POST['company_code']);

							$currency_result = $this->db->query("select * from currency order by id");
							$currency_result = $currency_result->result_array();
							$currency_res = array();
					        foreach($currency_result as $row) {
					            $currency_res[$row['currency']] = $row['currency'];
					        }

					        $check_client_qb_info = $this->db->query("select client.*, client_qb_id.currency_name, client_qb_id.qb_customer_id from client_qb_id left join client on client_qb_id.company_code = client.company_code where client_qb_id.company_code = '".$_POST['company_code']."' and client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."' and client.deleted = 0");
					        $check_client_qb_info = $check_client_qb_info->result_array();
					        if(count($check_client_qb_info) > 0)
					        {
						        foreach($check_client_qb_info as $key => $row) {
						            $check_client_qb_info[$key]["company_name"] = $this->encryption->decrypt($row["company_name"]);
						        }
						    }
							//$qb_status = $this->import_each_client_to_quickbook($client_id);

					        echo json_encode(array("Status" => 1,'message' => "Import Successfully", 'title' => "Success", 'client_billing' => $this->data, 'change_company_name' => $change_cn, 'client_id' => $client_id, "currency" => $currency_res, "check_client_qb_info" => $check_client_qb_info, "qb_company_id" => $this->session->userdata('qb_company_id')));

					    }
					    else
					    {
					    	echo json_encode(array("Status" => 2,'message' => 'The registration no. already in the system.', 'title' => 'Error'));
					    }

				    }
				    else
				    {
				    	echo json_encode(array("Status" => 2,'message' => 'The client code already in the system. -> '.$duplicate_flag, 'title' => 'Error'));
				    }
				}
		    }
		}
	}

	public function import_qb_client_to_quickbook()
	{
		$client_id = $_POST["client_id"];
		$currency_name = $_POST["client_qb_currency"];

		$qb_status = $this->import_each_client_to_quickbook($client_id, $currency_name);

		echo json_encode($qb_status);
	}

	public function import_each_client_to_quickbook($client_id, $currency_name = null)
    {
    	if($this->session->userdata('refresh_token_value'))
    	{
    		try {
		    	// Prep Data Services
				$dataService = DataService::Configure(array(
					'auth_mode' => 'oauth2',
					'ClientID' => $this->quickbook_clientID,
					'ClientSecret' => $this->quickbook_clientSecret,
					'accessTokenKey' => $this->session->userdata('access_token_value'),
					'refreshTokenKey' => $this->session->userdata('refresh_token_value'),
					'QBORealmID' => $this->session->userdata('qb_company_id'), //"The Company ID which the app wants to access"
					'baseUrl' => $this->quickbookBaseUrl
				));

				$dataService->throwExceptionOnError(true); 

				$client_query = $this->db->query("SELECT client.*, client_contact_info_email.email, client_qb_id.qb_customer_id FROM client LEFT JOIN client_contact_info ON client_contact_info.company_code = client.company_code LEFT JOIN client_contact_info_email ON client_contact_info_email.client_contact_info_id = client_contact_info.id AND client_contact_info_email.primary_email = 1 LEFT JOIN client_qb_id ON client_qb_id.company_code = client.company_code AND client_qb_id.currency_name = '".$currency_name."' WHERE client.deleted = 0  AND client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."' AND client.id = '".$client_id."'");
				// AND client.postal_code != ''
				if ($client_query->num_rows() == 0) 
		  		{
		  			$client_query = $this->db->query("SELECT client.*, client_contact_info_email.email FROM client LEFT JOIN client_contact_info ON client_contact_info.company_code = client.company_code LEFT JOIN client_contact_info_email ON client_contact_info_email.client_contact_info_id = client_contact_info.id AND client_contact_info_email.primary_email = 1 WHERE client.deleted = 0  AND client.id = '".$client_id."'");
		  			// AND client.postal_code != ''
		  		} 
  
		  		// echo "SELECT client.*, client_contact_info_email.email, client_qb_id.qb_customer_id FROM client LEFT JOIN client_contact_info ON client_contact_info.company_code = client.company_code LEFT JOIN client_contact_info_email ON client_contact_info_email.client_contact_info_id = client_contact_info.id AND client_contact_info_email.primary_email = 1 LEFT JOIN client_qb_id ON client_qb_id.company_code = client.company_code AND client_qb_id.currency_name = '".$currency_name."' WHERE client.deleted = 0 AND client.postal_code != '' AND client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."' AND client.id = '".$client_id."'";
		  		// print_r($client_query->result()); 
		  		// echo "SELECT client.*, client_contact_info_email.email FROM client LEFT JOIN client_contact_info ON client_contact_info.company_code = client.company_code LEFT JOIN client_contact_info_email ON client_contact_info_email.client_contact_info_id = client_contact_info.id AND client_contact_info_email.primary_email = 1 WHERE client.deleted = 0 AND client.postal_code != '' AND client.id = '".$client_id."'";

				if ($client_query->num_rows() > 0) 
		        {
					$client_query = $client_query->result_array();

					foreach ($client_query as $row) 
					{
						// Add unit
						if(!empty($row['unit_no1']) && !empty($row['unit_no2']))
						{
							$unit = '#' . $row['unit_no1'] . '-' . $row['unit_no2'];
						}

						// Add building
						if(!empty($row['building_name']) && !empty($unit))
						{
							$unit_building_name = $unit . ' ' . $row['building_name'];
						}
						elseif(!empty($unit))
						{
							$unit_building_name = $unit;
						}
						elseif(!empty($row['building_name']))
						{
							$unit_building_name = $row['building_name'];
						}

						if(!empty($row["currency_name"]))
						{
							$qb_currency_name = $row["currency_name"];
						}
						else
						{
							$qb_currency_name = $currency_name;
						}

						$customer_info = [
							    "BillAddr" => [
							        "Line1" => strtoupper(trim($row["street_name"])),
							        "City" => strtoupper(trim($unit_building_name)),
							        "Country" => "",
							        "CountrySubDivisionCode" => "SINGAPORE",
							        "PostalCode" => strtoupper(trim($row["postal_code"]))
							    ],
							    "CurrencyRef" => [
									"value" => $qb_currency_name
								],
							    "Notes" => "",
							    "Title" => "",
							    "GivenName" => "",
							    "MiddleName" => "",
							    "FamilyName" => "",
							    "Suffix" => "",
							    "FullyQualifiedName" => trim($this->encryption->decrypt($row["company_name"])." (".$qb_currency_name.")"),
							    "CompanyName" => trim($this->encryption->decrypt($row["company_name"])." (".$qb_currency_name.")"),
							    "DisplayName" => str_replace(':', "", trim($this->encryption->decrypt($row["company_name"])." (".$qb_currency_name.")"))
							];

						if(!empty($row['email']))
						{
							$email_to = explode(';',$row["email"]);
					        if(count($email_to) > 0)
					        {
					        	for($t = 0; $t < count($email_to); $t++)
					        	{
									$isvalid = filter_var(trim($email_to[$t]), FILTER_VALIDATE_EMAIL);
									if($isvalid != false)
									{
										if($t == 0)
										{
											$email_in_qb = trim($email_to[$t]);
										}
										else
										{
											$email_in_qb = $email_in_qb.', '.trim($email_to[$t]);
										}
									}
								}
							}

							$email_add = ["PrimaryEmailAddr" => [
									      	"Address" => $email_in_qb
									    ]];
							$customer_info = array_merge($customer_info, $email_add);
						}

						if(!empty($row["qb_customer_id"]))
						{
							$customer = $dataService->FindbyId('customer', $row["qb_customer_id"]);
							$theResourceObj = Customer::update($customer, $customer_info);
							$resultingObj = $dataService->Update($theResourceObj);
						}
						else
						{
							//Add a new Vendor
							$theResourceObj = Customer::create($customer_info);
							$resultingObj = $dataService->Add($theResourceObj);
						}

						$error = $dataService->getLastError();

						if ($error) {
							print_r($e);
							exit;
						    if($error->getHttpStatusCode() == "401")
						    {
						    	$refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting();
						    	if($refresh_token_status)
						    	{
						    		$this->import_each_client_to_quickbook($client_id, $currency_name);
						    	}
						    }
						    else
						    {
						    	return array("Status" => 3, 'message' => $error->getIntuitErrorMessage(), 'title' => 'Error');
						    }
						}
						else {
							$data["qb_company_id"] = $this->session->userdata('qb_company_id');
							$data["company_code"] = $row["company_code"];
						    $data["qb_customer_id"] = $resultingObj->Id;
						    $data["currency_name"] = $qb_currency_name;
						    $data["qb_json_data"] = json_encode($resultingObj);

						    if(!empty($row["qb_customer_id"]))
							{
								$this->db->update("client_qb_id",$data,array("qb_customer_id" => $row["qb_customer_id"], "qb_company_id" => $this->session->userdata('qb_company_id')));
							}
							else
							{
								$this->db->insert("client_qb_id",$data);
							}

							$this->save_audit_trail("Clients", "QuickBooks", $this->session->userdata('first_name'). " " . $this->session->userdata('last_name') . " import ".trim($this->encryption->decrypt($row["company_name"])." (".$qb_currency_name.")")." client to QuickBooks Online.");
						}
					}

					return array("Status" => 1, 'message' => "Import Successfully", 'title' => 'Success');
				}
			}
			catch (Exception $e){
				print_r($e);
				exit;
				$refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting();
		    	if($refresh_token_status)
		    	{
		    		$this->import_each_client_to_quickbook($client_id, $currency_name);
		    	}
			}
		}
		else
		{
			return array("Status" => 2, 'message' => 'Please login to Quickbook Online first before proceed this step.', 'title' => 'Warning');
		}
    }

	public function getShareTransferInfo()
	{
		$company_code = $_POST["company_code"];
		$client_member_share_capital_id = $_POST["client_member_share_capital_id"];

		$this->data['last_cert_no'] = $this->transaction_model->get_last_cert_no($company_code, $client_member_share_capital_id);

		$this->data['share_number_for_cert_record'] = $this->data['share_number_for_cert_record'] = $this->transaction_model->getLatestShareNumberForCertRecord($company_code, $client_member_share_capital_id, null);

		echo json_encode(array($this->data));
	}

	public function recalculate()
    {
        $this->db->select("users.id")
                ->from("users")
                ->join('groups', 'users.group_id = groups.id', 'inner')
                ->join('user_firm as a', 'a.user_id = "'.$this->session->userdata("user_id").'"', 'inner')
                ->join('user_firm as b', 'a.firm_id=b.firm_id', 'inner')
                ->where('b.user_id = users.id')
                ->where('b.user_id != "'.$this->session->userdata("user_id").'"')
                ->group_by('users.id');
        $test = $this->db->get();
        $test = $test->result_array();

        $data_user_id = array();

        foreach ($test as $rr) {
            array_push($data_user_id, $rr["id"]);
        }

        $this->db->select("firm_id")
        ->from("user_firm")
        ->where('user_id = "'.$this->session->userdata("user_id").'"');

        $firm_id = $this->db->get();
        $firm_id = $firm_id->result_array();

        $data_firm_id = array();

        foreach ($firm_id as $rows) {
            array_push($data_firm_id, $rows["firm_id"]);
        }

        $user["no_of_user"] = count($data_user_id);
        $user["no_of_firm"] = count($data_firm_id);
        $this->db->where('id', $this->session->userdata("user_id"));
        $this->db->update('users',$user);

        if(count($data_firm_id) != 0)
        {
            

            $this->db->select('id');
            $this->db->from('client');
            $this->db->where_in('firm_id', $data_firm_id);

            $num_client = $this->db->get();
            $num_client = $num_client->result_array();

            if(count($num_client) != 0)
            {   
                //echo json_encode($row["id"]);
                $users["no_of_client"] = count($num_client);
            }
            else
            {
                $users["no_of_client"] = 0;
            }

            $this->db->where('id', $this->session->userdata("user_id"));
            $this->db->update('users',$users);

            $data_user_id = array();

            foreach ($test as $r) {
                array_push($data_user_id, $r["id"]);
            }

            if(count($data_user_id) != 0)
            {  
	            $this->db->where_in('id', $data_user_id);
	            $this->db->update('users',$users);
	        }
        }
    }
//-------------------------------------------create_document_start------------------------------------------------------//

// 	public function create_document($type, $company_code, $officer_id = null, $controller_id = null, $charge_id = null, $filing_id = null, $guarantee_id = null, $allotment_id = null, $client_member_share_capital_id = null, $buyback_id = null, $transfer_id = null)
// 	{
// 		//echo json_encode($charge_id);
// 		$get_client = $this->db->query("select client.*, company_type.company_type as company_type_name from client left join company_type on client.company_type = company_type.id where company_code='".$company_code."'");

// 		$get_client = $get_client->result_array();

// 		//|| ($get_client[0]["acquried_by"] == 1 && $get_client[0]["auto_generate"] == 0)
// 		if($get_client[0]["auto_generate"] == 1 )
// 		{
// 			if($type == "change_company_name")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 2 AND firm_id='".$get_client[0]["firm_id"]."'");
// 			}
// 			elseif($type == "change_address")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 7 AND firm_id='".$get_client[0]["firm_id"]."'");
// 			}
// 			elseif($type == "change_company_type")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 3 AND firm_id='".$get_client[0]["firm_id"]."'");
// 			}
// 			elseif($type == "acquried_by")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 1 AND firm_id='".$get_client[0]["firm_id"]."'");
// 			}
// 			elseif($type == "status")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 4 AND firm_id='".$get_client[0]["firm_id"]."'");
// 			}
// 			elseif($type == "change_activity1")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 5 AND firm_id='".$get_client[0]["firm_id"]."'");
// 			}
// 			elseif($type == "change_activity2")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 6 AND firm_id='".$get_client[0]["firm_id"]."'");
// 			}
// 			elseif($type == "appointment_of_director")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 8 AND firm_id='".$get_client[0]["firm_id"]."'");

// 				$date_of_appointment = $this->db->get_where("client_officers", array("id" => $officer_id));

// 				$date_of_appointment = $date_of_appointment->result_array();

// 				//echo json_encode($officer_id);
// 			}
// 			elseif($type == "cessation_of_director")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 9 AND firm_id='".$get_client[0]["firm_id"]."'");

// 				$date_of_cessation = $this->db->get_where("client_officers", array("id" => $officer_id));

// 				$date_of_cessation = $date_of_cessation->result_array();
// 			}
// 			elseif($type == "appointment_of_manager")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 12 AND firm_id='".$get_client[0]["firm_id"]."'");

// 				$date_of_appointment = $this->db->get_where("client_officers", array("id" => $officer_id));

// 				$date_of_appointment = $date_of_appointment->result_array();

// 				//echo json_encode($officer_id);
// 			}
// 			elseif($type == "cessation_of_manager")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 13 AND firm_id='".$get_client[0]["firm_id"]."'");

// 				$date_of_cessation = $this->db->get_where("client_officers", array("id" => $officer_id));

// 				$date_of_cessation = $date_of_cessation->result_array();
// 			}
// 			elseif($type == "appointment_of_secretary")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 14 AND firm_id='".$get_client[0]["firm_id"]."'");

// 				$date_of_appointment = $this->db->get_where("client_officers", array("id" => $officer_id));

// 				$date_of_appointment = $date_of_appointment->result_array();

				
// 			}
// 			elseif($type == "cessation_of_secretary")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 15 AND firm_id='".$get_client[0]["firm_id"]."'");

// 				$date_of_cessation = $this->db->get_where("client_officers", array("id" => $officer_id));

// 				$date_of_cessation = $date_of_cessation->result_array();
// 			}
// 			elseif($type == "appointment_of_auditor")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 16 AND firm_id='".$get_client[0]["firm_id"]."'");

// 				$date_of_appointment = $this->db->get_where("client_officers", array("id" => $officer_id));

// 				$date_of_appointment = $date_of_appointment->result_array();

// 				//echo json_encode($officer_id);
// 			}
// 			elseif($type == "cessation_of_auditor")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 17 AND firm_id='".$get_client[0]["firm_id"]."'");

// 				$date_of_cessation = $this->db->get_where("client_officers", array("id" => $officer_id));

// 				$date_of_cessation = $date_of_cessation->result_array();
// 			}
// 			elseif($type == "appointment_of_ceo")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 10 AND firm_id='".$get_client[0]["firm_id"]."'");

// 				$date_of_appointment = $this->db->get_where("client_officers", array("id" => $officer_id));

// 				$date_of_appointment = $date_of_appointment->result_array();

// 				//echo json_encode($officer_id);
// 			}
// 			elseif($type == "cessation_of_ceo")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 11 AND firm_id='".$get_client[0]["firm_id"]."'");

// 				$date_of_cessation = $this->db->get_where("client_officers", array("id" => $officer_id));

// 				$date_of_cessation = $date_of_cessation->result_array();
// 			}
// 			elseif($type == "appointment_of_managing_director")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 18 AND firm_id='".$get_client[0]["firm_id"]."'");

// 				$date_of_appointment = $this->db->get_where("client_officers", array("id" => $officer_id));

// 				$date_of_appointment = $date_of_appointment->result_array();

// 				//echo json_encode($officer_id);
// 			}
// 			elseif($type == "cessation_of_managing_director")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 19 AND firm_id='".$get_client[0]["firm_id"]."'");

// 				$date_of_cessation = $this->db->get_where("client_officers", array("id" => $officer_id));

// 				$date_of_cessation = $date_of_cessation->result_array();
// 			}
// 			elseif($type == "appointment_of_alternate_director")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 20 AND firm_id='".$get_client[0]["firm_id"]."'");

// 				$date_of_appointment = $this->db->get_where("client_officers", array("id" => $officer_id));

// 				$date_of_appointment = $date_of_appointment->result_array();

// 				//echo json_encode($officer_id);
// 			}
// 			elseif($type == "cessation_of_alternate_director")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 21 AND firm_id='".$get_client[0]["firm_id"]."'");

// 				$date_of_cessation = $this->db->get_where("client_officers", array("id" => $officer_id));

// 				$date_of_cessation = $date_of_cessation->result_array();
// 			}
// 			elseif($type == "add_allotment_of_share")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 23 AND firm_id='".$get_client[0]["firm_id"]."'");

// 				$allot_transaction_date = $this->db->get_where("member_shares", array("id" => $allotment_id[0]));

// 				$allot_transaction_date = $allot_transaction_date->result_array();
// 			}
// 			elseif($type == "add_buyback_of_share")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 24 AND firm_id='".$get_client[0]["firm_id"]."'");

// 				$buyback_transaction_date = $this->db->get_where("member_shares", array("id" => $buyback_id[0]));

// 				$buyback_transaction_date = $buyback_transaction_date->result_array();

// 				//echo json_encode($buyback_id);
// 			}
// 			elseif($type == "add_transfer_of_share")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 25 AND firm_id='".$get_client[0]["firm_id"]."'");

// 				$transfer_transaction_date = $this->db->get_where("member_shares", array("id" => $transfer_id[0]));

// 				$transfer_transaction_date = $transfer_transaction_date->result_array();

// 				//echo json_encode($buyback_id);
// 			}
// 			elseif($type == "change_registration_of_charge")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 29 AND firm_id='".$get_client[0]["firm_id"]."'");

// 				$date_of_registration = $this->db->get_where("client_charges", array("id" => $charge_id));

// 				$date_of_registration = $date_of_registration->result_array();

// 				//echo json_encode($charges_id);
// 			}
// 			elseif($type == "change_satisfaction_of_charge")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 30 AND firm_id='".$get_client[0]["firm_id"]."'");

// 				$date_of_satisfaction = $this->db->get_where("client_charges", array("id" => $charge_id));

// 				$date_of_satisfaction = $date_of_satisfaction->result_array();
// 			}
// 			/*elseif($type == "change_date_of_appointment")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 8 AND firm_id='".$get_client[0]["firm_id"]."'");

// 				$date_of_appointment = $this->db->get_where("client_officers", array("id" => $officer_id));

// 				$date_of_appointment = $date_of_appointment->result_array();
// 			}
// 			elseif($type == "change_date_of_cessation")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 9 AND firm_id='".$get_client[0]["firm_id"]."'");
// 			}*/

// 			/*elseif($type == "add_new_class_of_share")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 11 AND firm_id='".$get_client[0]["firm_id"]."'");
// 			}
// 			elseif($type == "add_allotment_of_share")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 12 AND firm_id='".$get_client[0]["firm_id"]."'");
// 			}
// 			elseif($type == "add_buyback_of_share")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 13 AND firm_id='".$get_client[0]["firm_id"]."'");
// 			}
// 			elseif($type == "add_transfer_of_share")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 14 AND firm_id='".$get_client[0]["firm_id"]."'");
// 			}
// 			elseif($type == "add_guarantee")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 15 AND firm_id='".$get_client[0]["firm_id"]."'");
// 			}
// 			elseif($type == "change_guarantee")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 15 AND firm_id='".$get_client[0]["firm_id"]."'");
// 			}
// 			elseif($type == "change_registration_of_controller")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 16 AND firm_id='".$get_client[0]["firm_id"]."'");
// 			}
// 			elseif($type == "change_cessation_of_controller")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 17 AND firm_id='".$get_client[0]["firm_id"]."'");
// 			}*/
			
// 			elseif($type == "change_of_year_end")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 31 AND firm_id='".$get_client[0]["firm_id"]."'");
// 			}
// 			elseif($type == "agm_held")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 32 AND firm_id='".$get_client[0]["firm_id"]."'");
// 			}
// 			elseif($type == "dispense_agm")
// 			{
// 				$result = $this->db->query("select * from document_master where triggered_by = 33 AND firm_id='".$get_client[0]["firm_id"]."'");
// 			}
// 			//echo json_encode($result->result_array());
// 			if ($result->num_rows())
// 			{
// 				$result = $result->result_array();

// 				for($r = 0; $r < count($result); $r++)
// 				{
// 					$data['client_id']=$get_client[0]["id"];
// 	                $data['firm_id']=$this->session->userdata('firm_id');
// 	                if($officer_id != null)
// 	                {
// 	                	$data['officer_id']=json_encode(array((int)$officer_id));
// 	                }
// 	                else
// 	                {
// 	                	$data['officer_id']="";
// 	                }
// 	                if($controller_id != null)
// 	                {
// 	                	$data['controller_id']=$controller_id;
// 	                }
// 	                else
// 	                {
// 	                	$data['controller_id']="";
// 	                }
// 	                if($allotment_id != null)
// 	                {
// 	                	$data['allotment_id']=json_encode(array((int)$allotment_id));
// 	                }
// 	                else
// 	                {
// 	                	$data['allotment_id']="";
// 	                }
// 	                if($buyback_id != null)
// 	                {
// 	                	$data['buyback_id']=json_encode(array((int)$buyback_id));
// 	                }
// 	                else
// 	                {
// 	                	$data['buyback_id']="";
// 	                }
// 	                if($transfer_id != null)
// 	                {
// 	                	$data['transfer_id']=json_encode($transfer_id);
// 	                }
// 	                else
// 	                {
// 	                	$data['transfer_id']="";
// 	                }
// 	                if($charge_id != null)
// 	                {
// 	                	$data['charge_id']=json_encode(array((int)$charge_id));
// 	                }
// 	                else
// 	                {
// 	                	$data['charge_id']="";
// 	                }
// 	                if($filing_id != null)
// 	                {
// 	                	$data['filing_id']=$filing_id;
// 	                }
// 	                else
// 	                {
// 	                	$data['filing_id']="";
// 	                }
// 	                if($guarantee_id != null)
// 	                {
// 	                	$data['guarantee_id']=$guarantee_id;
// 	                }
// 	                else
// 	                {
// 	                	$data['guarantee_id']="";
// 	                }
// 	                $data['document_name']=$result[$r]["document_name"]." - ".DATE("Y",now());
// 	                $data['document_date_checkbox']=1;
// 	                if($officer_id != null)
// 	                {
// 	                	if($type == "appointment_of_director")
// 						{
// 	                		$data['transaction_date']=$date_of_appointment[0]["date_of_appointment"];
// 	                	}
// 	                	elseif($type == "cessation_of_director")
// 	                	{
// 	                		$data['transaction_date']=$date_of_cessation[0]["date_of_cessation"];
// 	                	}
// 	                	elseif($type == "appointment_of_secretary")
// 						{
// 	                		$data['transaction_date']=$date_of_appointment[0]["date_of_appointment"];
// 	                	}
// 	                	elseif($type == "cessation_of_secretary")
// 	                	{
// 	                		$data['transaction_date']=$date_of_cessation[0]["date_of_cessation"];
// 	                	}
// 	                	elseif($type == "appointment_of_auditor")
// 						{
// 	                		$data['transaction_date']=$date_of_appointment[0]["date_of_appointment"];
// 	                	}
// 	                	elseif($type == "cessation_of_auditor")
// 	                	{
// 	                		$data['transaction_date']=$date_of_cessation[0]["date_of_cessation"];
// 	                	}
// 	                	elseif($type == "appointment_of_manager")
// 						{
// 	                		$data['transaction_date']=$date_of_appointment[0]["date_of_appointment"];
// 	                	}
// 	                	elseif($type == "cessation_of_manager")
// 	                	{
// 	                		$data['transaction_date']=$date_of_cessation[0]["date_of_cessation"];
// 	                	}
// 	                	elseif($type == "appointment_of_ceo")
// 						{
// 	                		$data['transaction_date']=$date_of_appointment[0]["date_of_appointment"];
// 	                	}
// 	                	elseif($type == "cessation_of_ceo")
// 	                	{
// 	                		$data['transaction_date']=$date_of_cessation[0]["date_of_cessation"];
// 	                	}
// 	                	elseif($type == "appointment_of_managing_director")
// 						{
// 	                		$data['transaction_date']=$date_of_appointment[0]["date_of_appointment"];
// 	                	}
// 	                	elseif($type == "cessation_of_managing_director")
// 	                	{
// 	                		$data['transaction_date']=$date_of_cessation[0]["date_of_cessation"];
// 	                	}
// 	                	elseif($type == "appointment_of_alternate_director")
// 						{
// 	                		$data['transaction_date']=$date_of_appointment[0]["date_of_appointment"];
// 	                	}
// 	                	elseif($type == "cessation_of_alternate_director")
// 	                	{
// 	                		$data['transaction_date']=$date_of_cessation[0]["date_of_cessation"];
// 	                	}
// 	                }
// 	                elseif($charge_id != null)
// 	                {

// 	                	if($type == "change_registration_of_charge")
// 						{
// 	                		$data['transaction_date']=$date_of_registration[0]["date_registration"];
// 	                	}
// 	                	elseif($type == "change_satisfaction_of_charge")
// 	                	{
// 	                		$data['transaction_date']=$date_of_satisfaction[0]["date_satisfied"];


// 	                	}
// 	                	//echo json_encode($data['transaction_date']);
// 	                }
// 	                elseif($allotment_id != null)
// 	                {
// 	                	if($type == "add_allotment_of_share")
// 						{
// 	                		$data['transaction_date'] = $allot_transaction_date[0]["transaction_date"];
// 	                	}
// 	                	//echo json_encode($data['transaction_date']);
// 	                }
// 	                elseif($buyback_id != null)
// 	                {
// 	                	if($type == "add_buyback_of_share")
// 						{
// 	                		$data['transaction_date'] = $buyback_transaction_date[0]["transaction_date"];
// 	                	}
// 	                	//echo json_encode($data['transaction_date']);
// 	                }
// 	                elseif($transfer_id != null)
// 	                {
// 	                	if($type == "add_transfer_of_share")
// 						{
// 	                		$data['transaction_date'] = $transfer_transaction_date[0]["transaction_date"];
// 	                	}
// 	                	//echo json_encode($data['transaction_date']);
// 	                }
// 	                else
// 	                {
// 	                	$data['transaction_date']=DATE("d/m/Y",now());
// 	                }


// 	                $data['received_on']="";
// 	                $data['triggered_by']=$result[$r]["triggered_by"];
// 	                $officer_id_data = array($officer_id);
// 	                $charge_id_data = array($charge_id);
// 	                $allotment_id_data = array($allotment_id);
// 	                $buyback_id_data = array($buyback_id);
// 	                $transfer_id_data = $transfer_id;
// 	                $str = $result[$r]["document_content"];
// 					//$substr = '<span class="myclass mceNonEditable">{{Company old name}}</span>';
					
// 					if($result[$r]["document_name"] == "Form 45B")
// 					{
// 						$secretarys_appointment_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '4' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 						$previous_secretarys_appointment_result = $this->db->query("select client_officers.* from client_officers where position = '4' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");

// 						$previous_secretarys_appointment_result = $previous_secretarys_appointment_result->result_array();

// 						$secretarys_appointment_result = $secretarys_appointment_result->result_array();

// 						$loop_document_content = "";
// 						$latest_officer_id = array();
// 						for($e = 0; $e < count($secretarys_appointment_result); $e++)
// 						{	
// 							$document_content_str = $result[$r]["document_content"];

// 							if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Company current name}}</span>') !== false)
// 							{
// 								$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Company current name}}</span>', $get_client[0]["company_name"], $document_content_str);
// 							}

// 							if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Secretarys name - appointment}}</span>') !== false)
// 			                {
// 			                	$secretarys_name_appointment = "";

			                	
// 			                	$num_of_director_name_appointment = (int)(count($secretarys_appointment_result)) - 1;

// 			                	$secretarys_name_appointment = $secretarys_name_appointment.'<strong>'.$secretarys_appointment_result[$e]["name"].'</strong>';

// 			                	$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Secretarys name - appointment}}</span>', $secretarys_name_appointment, $document_content_str);
// 			                }

// 			                if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Secretarys ID - appointment}}</span>') !== false)
// 			                {

// 			                	$secretarys_id_appointment = "";

// 			                	$num_of_secretarys_id_appointment = (int)(count($secretarys_appointment_result)) - 1;

// 			                	$secretarys_id_appointment = $secretarys_id_appointment.'<strong>'.$secretarys_appointment_result[$e]["identification_no"].'</strong>';

// 			                	$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Secretarys ID - appointment}}</span>', $secretarys_id_appointment, $document_content_str);

// 			                }

// 			                array_push($latest_officer_id, (int)$secretarys_appointment_result[$e]["id"]);

// 			                $data['officer_id']=json_encode($latest_officer_id);

// 			                $loop_document_content = $loop_document_content.$document_content_str;
// 						}

// 						$officer_id_data = array();

// 						if(count($previous_secretarys_appointment_result) != 0)
// 	                	{
	                		
// 	                		for($f = 0; $f < count($previous_secretarys_appointment_result); $f++)
// 		                	{
// 		                		array_push($officer_id_data, (int)$previous_secretarys_appointment_result[$f]["id"]);
// 		                	}
// 	                	}

// 						$data['content'] = $loop_document_content;
// 					}
// 					elseif($result[$r]["document_name"] == "Form 45")
// 					{
// 						$director_appointment_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '1' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 						$previous_director_appointment_result = $this->db->query("select client_officers.* from client_officers where position = '1' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");

// 						$previous_director_appointment_result = $previous_director_appointment_result->result_array();

// 						$director_appointment_result = $director_appointment_result->result_array();

// 						$loop_document_content = "";
// 						$latest_officer_id = array();
// 						for($e = 0; $e < count($director_appointment_result); $e++)
// 						{	
// 							$document_content_str = $result[$r]["document_content"];

// 							if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Company current name}}</span>') !== false)
// 							{
// 								$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Company current name}}</span>', $get_client[0]["company_name"], $document_content_str);
// 							}

// 							if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Directors name - appointment}}</span>') !== false)
// 			                {
// 			                	$directors_name_appointment = "";
			                	
// 			                	$num_of_director_name_appointment = (int)(count($director_appointment_result)) - 1;

// 			                	$directors_name_appointment = $directors_name_appointment.'<strong>'.$director_appointment_result[$e]["name"].'</strong>';

// 			                	$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Directors name - appointment}}</span>', $directors_name_appointment, $document_content_str);

// 			                	//echo json_encode($directors_name_appointment);
// 			                }

// 			                if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Directors ID - appointment}}</span>') !== false)
// 			                {

// 			                	$directors_id_appointment = "";

// 			                	$num_of_secretarys_id_appointment = (int)(count($director_appointment_result)) - 1;

// 			                	$directors_id_appointment = $directors_id_appointment.'<strong>'.$director_appointment_result[$e]["identification_no"].'</strong>';

// 			                	$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Directors ID - appointment}}</span>', $directors_id_appointment, $document_content_str);

// 			                }

// 			                array_push($latest_officer_id, (int)$director_appointment_result[$e]["id"]);

// 			                $data['officer_id']=json_encode($latest_officer_id);

// 			                $loop_document_content = $loop_document_content.$document_content_str;
// 						}

// 						$officer_id_data = array();

// 						if(count($previous_director_appointment_result) != 0)
// 	                	{
	                		
// 	                		for($f = 0; $f < count($previous_director_appointment_result); $f++)
// 		                	{
// 		                		array_push($officer_id_data, (int)$previous_director_appointment_result[$f]["id"]);
// 		                	}
// 	                	}

// 						$data['content'] = $loop_document_content;
// 					}
// 					elseif($result[$r]["document_name"] == "Declaration-Strike Off")
// 					{
// 						$director_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '1' AND company_code='".$company_code."' AND ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y') AND client_officers.date_of_cessation = '') OR ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) AND (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') > STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')) AND client_officers.date_of_cessation != '')) GROUP BY officer.identification_no, officer.name");

// 						$director_result = $director_result->result_array();

// 						$loop_document_content = "";

// 						for($e = 0; $e < count($director_result); $e++)
// 						{
// 							$document_content_str = $result[$r]["document_content"];

// 							if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Company current name}}</span>') !== false)
// 							{
// 								$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Company current name}}</span>', $get_client[0]["company_name"], $document_content_str);
// 							}

// 							if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{UEN}}</span>') !== false)
// 							{
// 								$document_content_str = str_replace('<span class="myclass mceNonEditable">{{UEN}}</span>', $get_client[0]["registration_no"], $document_content_str);
// 							}

// 							if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Directors name - all}}</span>') !== false)
// 			                {
// 								$director_name = $director_result[$e]["name"];

// 								$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Directors name - all}}</span>', $director_name, $document_content_str);
// 			                }

// 			                if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Directors ID - all}}</span>') !== false)
// 			                {
// 								$director_id = $director_result[$e]["identification_no"];

// 								$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Directors ID - all}}</span>', $director_id, $document_content_str);
// 			                }

// 			                $loop_document_content = $loop_document_content.$document_content_str;
// 						}

// 						//echo json_encode($director_result);
// 						$data['content'] = $loop_document_content;
						
// 					}
// 					elseif($result[$r]["document_name"] == "Allotment-Share Application Form" || $result[$r]["document_name"] == "F24 - Return of allotment of shares" || $result[$r]["document_name"] == "Allotment-Share Cert")
// 					{
// 						$allotment_member_result = $this->db->query("select member_shares.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_member_share_capital.other_class, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name, certificate.certificate_no, certificate.new_certificate_no from member_shares left join certificate on certificate.officer_id = member_shares.officer_id and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id and certificate.client_member_share_capital_id = member_shares.client_member_share_capital_id left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital on client_member_share_capital.id = member_shares.client_member_share_capital_id left join sharetype on sharetype.id = client_member_share_capital.class_id left join currency on currency.id = client_member_share_capital.currency_id where member_shares.company_code='".$company_code."' AND member_shares.client_member_share_capital_id='".$client_member_share_capital_id."' AND member_shares.transaction_type='Allotment' AND STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY member_shares.id");

	                
// 	                	$this->db->select('member_shares.*')
// 	                	         ->order_by('member_shares.id');
// 						$this->db->where("company_code", $company_code);
// 						$this->db->where("STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')");
// 						$this->db->where("member_shares.client_member_share_capital_id", $client_member_share_capital_id);
// 						$this->db->where("member_shares.transaction_type = 'Allotment'");
// 						$this->db->where_not_in('id', $allotment_id);

// 						$previous_allotment_member_result = $this->db->get('member_shares');

// 						$allotment_member_result = $allotment_member_result->result_array();

// 	                	$previous_allotment_member_result = $previous_allotment_member_result->result_array();

// 	                	$loop_document_content = "";
// 	                	$latest_allotment_id = array();
// 						for($g = 0; $g < count($allotment_member_result); $g++)
// 		                {
// 		                	$document_content_str = $result[$r]["document_content"];

// 		                	if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Allotment - members}}</span>') !== false)
// 		                	{
// 		                		if($allotment_member_result[$g]["name"] != '')
// 	                			{
// 	                				$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Allotment - members}}</span>', $allotment_member_result[$g]["name"], $document_content_str);
	                				
// 	                			}
// 	                			elseif($allotment_member_result[$g]["company_name"] != '')
// 	                			{
// 	                				$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Allotment - members}}</span>', $allotment_member_result[$g]["company_name"], $document_content_str);
// 	                			}
// 	                		}

// 	                		if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Allotment - number of shares}}</span>') !== false)
// 	                		{
// 	                			$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Allotment - number of shares}}</span>', number_format($allotment_member_result[$g]["number_of_share"], 2), $document_content_str);
// 	                		}

// 	                		if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Allotment - amount of shares}}</span>') !== false)
// 	                		{
// 	                			$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Allotment - amount of shares}}</span>', number_format($allotment_member_result[$g]["amount_share"], 2), $document_content_str);
// 	                		}

// 	                		if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Allotment - per shared}}</span>') !== false)
// 	                		{
// 	                			$per_shared = $allotment_member_result[$g]["amount_share"] / $allotment_member_result[$g]["number_of_share"];

// 	                			$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Allotment - per shared}}</span>', number_format($per_shared, 2), $document_content_str);
// 	                		}


// 	                		if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Allotment - type of shares}}</span>') !== false)
// 	                		{
// 	                			if($allotment_member_result[$g]["sharetype_id"] == '1')
// 	                			{
// 	                				$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Allotment - type of shares}}</span>', $allotment_member_result[$g]["sharetype_name"], $document_content_str);

// 	                			}
// 	                			elseif($allotment_member_result[$g]["sharetype_id"] == '2')
// 	                			{
// 	                				$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Allotment - type of shares}}</span>', $allotment_member_result[$g]["other_class"], $document_content_str);
// 	                			}
// 	                		}

// 	                		if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Allotment - currency}}</span>') !== false)
// 	                		{
// 	                			$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Allotment - currency}}</span>', $allotment_member_result[$g]["currency_name"], $document_content_str);
// 	                		}

// 	                		if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Allotment - certificate}}</span>') !== false)
// 	                		{
// 	                			if($allotment_member_result[$g]["new_certificate_no"] != '')
// 	                			{
// 	                				$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Allotment - certificate}}</span>', $allotment_member_result[$g]["new_certificate_no"], $document_content_str);
// 	                			}
// 	                			else
// 	                			{
// 	                				$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Allotment - certificate}}</span>', $allotment_member_result[$g]["certificate_no"], $document_content_str);
// 	                			}
	                			
// 	                		}

// 		                	if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Company type}}</span>') !== false)
// 							{
// 								$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Company type}}</span>', $get_client[0]["company_type_name"], $document_content_str);
// 							}
// 		                	if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Company current name}}</span>') !== false)
// 							{
// 								$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Company current name}}</span>', $get_client[0]["company_name"], $document_content_str);
// 							}
// 							if(strpos($str, '<span class="myclass mceNonEditable">{{UEN}}</span>') !== false)
// 							{
// 								$document_content_str = str_replace('<span class="myclass mceNonEditable">{{UEN}}</span>', $get_client[0]["registration_no"], $document_content_str);
// 							}
// 							if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Address - new}}</span>') !== false)
// 							{
// 								if($get_client[0]["unit_no1"] != null || $get_client[0]["unit_no2"] != null)
// 								{
// 									$unit_no = '#'.$get_client[0]["unit_no1"].'-'.$get_client[0]["unit_no2"].'';
// 								}
// 								else
// 								{
// 									$unit_no = '';
// 								}

// 								$new_address = $get_client[0]["street_name"].',</br>'.$unit_no.' '.$get_client[0]["building_name"].', </br>Singapore '.$get_client[0]["postal_code"];

// 								$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Address - new}}</span>', $new_address, $document_content_str);
// 							}
// 							if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Directors name - all}}</span>') !== false)
// 			                {
// 			                	$director_name_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '1' AND company_code='".$company_code."' AND ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y') AND client_officers.date_of_cessation = '') OR ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) AND (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') > STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')) AND client_officers.date_of_cessation != '')) GROUP BY officer.identification_no, officer.name");
			                	
// 			                	$director_name_result = $director_name_result->result_array();

// 			                	//echo json_encode($director_name_result);
// 			                	//((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) OR (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') 
			                	

// 			                	if( $result[$r]["document_name"] == "Auditor-Notice of EGM" || $result[$r]["document_name"] == "Company Name-Notice of EGM" || $result[$r]["document_name"] == "Manager-Notice of EGM" || $result[$r]["document_name"] == "Allotment-Authority to Allot")
// 			                	{
// 			                		$director_name = '';

// 			                		$director_name = $director_name.'<p>&nbsp;</p><p>&nbsp;</p><p>_______________________________<br />'.$director_name_result[0]["name"].'<br />Director</p>';

// 			                	}
// 			                	elseif($result[$r]["document_name"] == "Form 11" || $result[$r]["document_name"] == "Strike Off EGM")
// 			                	{

// 			                		$director_name = $director_name_result[0]["name"];

// 			                	}
// 			                	elseif($result[$r]["document_name"] == "Strike Off-Minutes Of EGM")
// 			                	{
// 			                		$director_name = '';
// 			                		for($h = 0; $h < count($director_name_result); $h++)
// 				                	{	
// 				                		if($h == 0)
// 				                		{
// 				                			$director_name = $director_name.'<p><strong>'.$director_name_result[$h]["name"].'<br /></strong>';
// 				                		}
// 				                		elseif($h == (count($director_name_result) - 1))
// 				                		{
// 				                			$director_name = $director_name.'<strong>'.$director_name_result[$h]["name"].'<br /></strong></p>';
// 				                		}
// 				                		else
// 				                		{
// 				                			$director_name = $director_name.'<strong>'.$director_name_result[$h]["name"].'<br /></strong>';
// 				                		}
			                			
// 			                		}
// 			                	}
// 			                	else
// 			                	{
// 			                		$director_name = '<table style="width: 100%; border-collapse: collapse;"><tbody>';
// 			                		for($j = 0; $j < count($director_name_result); $j++)
// 				                	{
// 				                		$director_name = $director_name.'<tr style="height: 89px;"><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p>'.$director_name_result[$j]["name"].'</p></td><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p> _______________________________</p></td></tr>';
// 				                	}
// 				                	$director_name = $director_name.'</tbody></table>';
// 			                	}
			                	


// 			                	$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Directors name - all}}</span>', $director_name, $document_content_str);
// 			                }

// 			                if($client_member_share_capital_id != null)
// 			                {
// 			                	$client_member_share_capital_id_info = $this->db->query("select client_member_share_capital.*, member_shares.company_code, sum(member_shares.number_of_share) as number_of_shares, sum(member_shares.amount_share) as amount, sum(member_shares.no_of_share_paid) as number_of_shares_paid, sum(member_shares.amount_paid) as paid_up, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name from client_member_share_capital left join member_shares on member_shares.client_member_share_capital_id = client_member_share_capital.id AND member_shares.company_code = client_member_share_capital.company_code left join sharetype on sharetype.id = client_member_share_capital.class_id left join currency on currency.id = client_member_share_capital.currency_id where client_member_share_capital.company_code = '".$company_code."' group by client_member_share_capital.id");

// 			                	$client_member_share_capital_id_info = $client_member_share_capital_id_info->result_array();

// 			                	if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Class of shares - all}}</span>') !== false)
// 			                	{
// 		                			if($client_member_share_capital_id_info[0]["sharetype_id"] == '1')
// 		                			{
// 		                				$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Class of shares - all}}</span>', $client_member_share_capital_id_info[0]["sharetype_name"], $document_content_str);

// 		                			}
// 		                			elseif($client_member_share_capital_id_info[$g]["sharetype_id"] == '2')
// 		                			{
// 		                				$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Class of shares - all}}</span>', $client_member_share_capital_id_info[0]["other_class"], $document_content_str);
// 		                			}
// 			                	}

// 			                	if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Currency of shares - all}}</span>') !== false)
// 			                	{
// 			                		$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Currency of shares - all}}</span>', $client_member_share_capital_id_info[0]["currency_name"], $document_content_str);
// 			                	}

// 			                	if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{No of shares issued - all}}</span>') !== false)
// 			                	{
// 			                		$document_content_str = str_replace('<span class="myclass mceNonEditable">{{No of shares issued - all}}</span>', number_format($client_member_share_capital_id_info[0]["number_of_shares"], 2), $document_content_str);
// 			                	}

// 			                	if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Amount of shares issued - all}}</span>') !== false)
// 			                	{
// 			                		$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Amount of shares issued - all}}</span>', number_format($client_member_share_capital_id_info[0]["amount"], 2), $document_content_str);
// 			                	}

// 			                	if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{No of shares paid up - all}}</span>') !== false)
// 			                	{
// 			                		$document_content_str = str_replace('<span class="myclass mceNonEditable">{{No of shares paid up - all}}</span>', number_format($client_member_share_capital_id_info[0]["number_of_shares_paid"], 2), $document_content_str);
// 			                	}

// 			                	if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Amount of shares paid up - all}}</span>') !== false)
// 			                	{
// 			                		$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Amount of shares paid up - all}}</span>', number_format($client_member_share_capital_id_info[0]["paid_up"], 2), $document_content_str);
// 			                	}
// 			                }
		                	

// 		                	$loop_document_content = $loop_document_content.$document_content_str;
// 		                }
// 						for($h = 0; $h < count($allotment_member_result); $h++)
// 		                {
// 		                	array_push($latest_allotment_id, (int)$allotment_member_result[$h]["id"]);
// 		                }

// 	                	$data['allotment_id']=json_encode($latest_allotment_id);

// 						if(count($previous_allotment_member_result) != 0)
// 	                	{
// 	                		$allotment_id_data = array();
// 	                		for($f = 0; $f < count($previous_allotment_member_result); $f++)
// 		                	{
// 		                		array_push($allotment_id_data, (int)$previous_allotment_member_result[$f]["id"]);
// 		                	}
// 	                	}
// 	                	$data['content'] = $loop_document_content;
// 					}
// 					elseif($result[$r]["document_name"] == "Transfer Form" || $result[$r]["document_name"] == "Transferee-Share Cert")
// 					{
// 						$transfer_member_result = $this->db->query("select member_shares.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_member_share_capital.other_class, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name, certificate.certificate_no, certificate.new_certificate_no from member_shares left join certificate on certificate.officer_id = member_shares.officer_id and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id and certificate.client_member_share_capital_id = member_shares.client_member_share_capital_id left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital on client_member_share_capital.id = member_shares.client_member_share_capital_id left join sharetype on sharetype.id = client_member_share_capital.class_id left join currency on currency.id = client_member_share_capital.currency_id where member_shares.company_code='".$company_code."' AND member_shares.client_member_share_capital_id='".$client_member_share_capital_id."' AND member_shares.transaction_type='Transfer' AND STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY member_shares.id");

		                
// 		                	$this->db->select('member_shares.*')
// 		                	         ->order_by('member_shares.id');
// 							$this->db->where("company_code", $company_code);
// 							$this->db->where("STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')");
// 							$this->db->where("member_shares.client_member_share_capital_id", $client_member_share_capital_id);
// 							$this->db->where("member_shares.transaction_type = 'Transfer'");
// 							$this->db->where_in('id', $transfer_id);
// 							$previous_transfer_member_result = $this->db->get('member_shares');

// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$transfer_member_result = $transfer_member_result->result_array();

// 		                	$previous_transfer_member_result = $previous_transfer_member_result->result_array();
// 					}
// 					else
// 					{
// 						$history_client_info = $this->db->query("select * from history_client where company_code = '".$get_client[0]["company_code"]."'");

// 						$history_client_info = $history_client_info->result_array();

// 						if (strpos($str, '<span class="myclass mceNonEditable">{{Company old name}}</span>') !== false) {
// 						    $str = str_replace('<span class="myclass mceNonEditable">{{Company old name}}</span>', $history_client_info[0]["company_name"], $str);
// 						}
// 						if(strpos($str, '<span class="myclass mceNonEditable">{{Company new name}}</span>') !== false)
// 						{
// 							$str = str_replace('<span class="myclass mceNonEditable">{{Company new name}}</span>', $get_client[0]["company_name"], $str);
// 						}
// 						if(strpos($str, '<span class="myclass mceNonEditable">{{Company current name}}</span>') !== false)
// 						{
// 							$str = str_replace('<span class="myclass mceNonEditable">{{Company current name}}</span>', $get_client[0]["company_name"], $str);
// 						}
// 						if(strpos($str, '<span class="myclass mceNonEditable">{{UEN}}</span>') !== false)
// 						{
// 							$str = str_replace('<span class="myclass mceNonEditable">{{UEN}}</span>', $get_client[0]["registration_no"], $str);
// 						}
// 						if(strpos($str, '<span class="myclass mceNonEditable">{{Incorporation date}}</span>') !== false)
// 						{
// 							$str = str_replace('<span class="myclass mceNonEditable">{{Incorporation date}}</span>', $get_client[0]["incorporation_date"], $str);
// 						}
// 						if(strpos($str, '<span class="myclass mceNonEditable">{{Company type}}</span>') !== false)
// 						{
// 							$str = str_replace('<span class="myclass mceNonEditable">{{Company type}}</span>', $get_client[0]["company_type_name"], $str);
// 						}
// 						if($result[$r]["triggered_by"] == 5)
// 						{
// 							if(strpos($str, '<span class="myclass mceNonEditable">{{Principal activity - old}}</span>') !== false)
// 							{
// 								$str = str_replace('<span class="myclass mceNonEditable">{{Principal activity - old}}</span>', $history_client_info[0]["activity1"], $str);
// 							}

// 							if(strpos($str, '<span class="myclass mceNonEditable">{{Principal activity - new}}</span>') !== false)
// 							{
// 								$str = str_replace('<span class="myclass mceNonEditable">{{Principal activity - new}}</span>', $get_client[0]["activity1"], $str);
// 							}
// 						}

// 						if($result[$r]["triggered_by"] == 6)
// 						{
// 							if(strpos($str, '<span class="myclass mceNonEditable">{{Principal activity - old}}</span>') !== false)
// 							{
// 								$str = str_replace('<span class="myclass mceNonEditable">{{Principal activity - old}}</span>', $history_client_info[0]["activity2"], $str);
// 							}

// 							if(strpos($str, '<span class="myclass mceNonEditable">{{Principal activity - new}}</span>') !== false)
// 							{
// 								$str = str_replace('<span class="myclass mceNonEditable">{{Principal activity - new}}</span>', $get_client[0]["activity2"], $str);
// 							}
// 						}
// 						if(strpos($str, '<span class="myclass mceNonEditable">{{Address - old}}</span>') !== false)
// 						{
// 							if($history_client_info[0]["unit_no1"] != null || $history_client_info[0]["unit_no2"] != null)
// 							{
// 								$unit_no = '#'.$history_client_info[0]["unit_no1"].'-'.$history_client_info[0]["unit_no2"].'';
// 							}
// 							else
// 							{
// 								$unit_no = '';
// 							}

// 							$history_address = $history_client_info[0]["street_name"].', '.$unit_no.' '.$history_client_info[0]["building_name"].', Singapore '.$history_client_info[0]["postal_code"];

// 							$str = str_replace('<span class="myclass mceNonEditable">{{Address - old}}</span>', $history_address, $str);
// 						}
// 						if(strpos($str, '<span class="myclass mceNonEditable">{{Address - new}}</span>') !== false)
// 						{
// 							if($get_client[0]["unit_no1"] != null || $get_client[0]["unit_no2"] != null)
// 							{
// 								$unit_no = '#'.$get_client[0]["unit_no1"].'-'.$get_client[0]["unit_no2"].'';
// 							}
// 							else
// 							{
// 								$unit_no = '';
// 							}

// 							$new_address = $get_client[0]["street_name"].', '.$unit_no.' '.$get_client[0]["building_name"].', Singapore '.$get_client[0]["postal_code"];

// 							$str = str_replace('<span class="myclass mceNonEditable">{{Address - new}}</span>', $new_address, $str);
// 						}
// 						if(strpos($str, '<span class="myclass mceNonEditable">{{Directors name - all}}</span>') !== false)
// 		                {
// 		                	$director_name_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '1' AND company_code='".$company_code."' AND ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y') AND client_officers.date_of_cessation = '') OR ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) AND (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') > STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')) AND client_officers.date_of_cessation != '')) GROUP BY officer.identification_no, officer.name");
		                	
// 		                	$director_name_result = $director_name_result->result_array();

// 		                	//echo json_encode($director_name_result);
// 		                	//((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) OR (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') 
		                	

// 		                	if( $result[$r]["document_name"] == "Auditor-Notice of EGM" || $result[$r]["document_name"] ==  "Manager-Notice of EGM" || $result[$r]["document_name"] ==  "CEO-Notice of EGM" || $result[$r]["document_name"] ==  "Charge-Notice of EGM" || $result[$r]["document_name"] == "Company Name-Notice of EGM" || $result[$r]["document_name"] == "Allotment-Authority to Allot")
// 		                	{
// 		                		$director_name = '';

// 		                		$director_name = $director_name.'<p>&nbsp;</p><p>&nbsp;</p><p>_______________________________<br />'.$director_name_result[0]["name"].'<br />Director</p>';

// 		                	}
// 		                	elseif($result[$r]["document_name"] == "Form 11" || $result[$r]["document_name"] == "Strike Off EGM")
// 		                	{

// 		                		$director_name = $director_name_result[0]["name"];

// 		                	}
// 		                	elseif($result[$r]["document_name"] == "Strike Off-Minutes Of EGM")
// 		                	{
// 		                		$director_name = '';
// 		                		for($g = 0; $g < count($director_name_result); $g++)
// 			                	{	
// 			                		if($g == 0)
// 			                		{
// 			                			$director_name = $director_name.'<p><strong>'.$director_name_result[$g]["name"].'<br /></strong>';
// 			                		}
// 			                		elseif($g == (count($director_name_result) - 1))
// 			                		{
// 			                			$director_name = $director_name.'<strong>'.$director_name_result[$g]["name"].'<br /></strong></p>';
// 			                		}
// 			                		else
// 			                		{
// 			                			$director_name = $director_name.'<strong>'.$director_name_result[$g]["name"].'<br /></strong>';
// 			                		}
		                			
// 		                		}
// 		                	}
// 		                	else
// 		                	{
// 		                		$director_name = '<table style="width: 100%; border-collapse: collapse;"><tbody>';
// 		                		for($g = 0; $g < count($director_name_result); $g++)
// 			                	{
// 			                		$director_name = $director_name.'<tr style="height: 89px;"><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p>'.$director_name_result[$g]["name"].'</p></td><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p> _______________________________</p></td></tr>';
// 			                	}
// 			                	$director_name = $director_name.'</tbody></table>';
// 		                	}
		                	


// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Directors name - all}}</span>', $director_name, $str);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Directors ID - all}}</span>') !== false)
// 		                {
// 		                	$director_id_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '1' AND company_code='".$company_code."' AND ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y') AND client_officers.date_of_cessation = '') OR ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) AND (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') > STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')) AND client_officers.date_of_cessation != '')) GROUP BY officer.identification_no, officer.name");

// 		                	$director_id_result = $director_id_result->result_array();

// 		                	$director_id = "";

// 		                	for($g = 0; $g < count($director_id_result); $g++)
// 		                	{
// 		                		$director_id = $director_id.'<p>&nbsp;</p>'.$director_id_result[$g]["identification_no"].' ____________________________<br>';
// 		                	}

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Directors ID - all}}</span>', $director_id, $str);
// 		                }
		                
// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Directors name - resigning}}</span>') !== false)
// 		                {

// 		                	$director_name_resign_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '1' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_director_name_resign_result = $this->db->query("select client_officers.* from client_officers where position = '1' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$director_name_resign_result = $director_name_resign_result->result_array();

// 		                	$previous_director_name_resign_result = $previous_director_name_resign_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$director_name_resign = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_director_name_resign = (int)(count($director_name_resign_result)) - 1;

// 		                	for($g = 0; $g < count($director_name_resign_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$director_name_resign = $director_name_resign.'<strong>'.$director_name_resign_result[$g]["name"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_director_name_resign)
// 		                		{
// 		                			$director_name_resign = $director_name_resign.' and <strong>'.$director_name_resign_result[$g]["name"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$director_name_resign = $director_name_resign.', <strong>'.$director_name_resign_result[$g]["name"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$director_name_resign_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Directors name - resigning}}</span>', $director_name_resign, $str);

// 		                	if(count($previous_director_name_resign_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_director_name_resign_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_director_name_resign_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Directors ID - resigning}}</span>') !== false)
// 		                {
// 		                	$director_id_resign_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '1' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_director_id_resign_result = $this->db->query("select client_officers.* from client_officers where position = '1' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$director_id_resign_result = $director_id_resign_result->result_array();

// 		                	$previous_director_id_resign_result = $previous_director_id_resign_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$director_id_resign = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_director_id_resign = (int)(count($director_id_resign_result)) - 1;

// 		                	for($g = 0; $g < count($director_id_resign_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$director_id_resign = $director_id_resign.'<strong>'.$director_id_resign_result[$g]["identification_no"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_director_id_resign)
// 		                		{
// 		                			$director_id_resign = $director_id_resign.' and <strong>'.$director_id_resign_result[$g]["identification_no"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$director_id_resign = $director_id_resign.', <strong>'.$director_id_resign_result[$g]["identification_no"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$director_id_resign_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Directors ID - resigning}}</span>', $director_id_resign, $str);

// 		                	if(count($previous_director_id_resign_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_director_id_resign_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_director_id_resign_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Directors - cessation date}}</span>') !== false)
// 		                {
// 		                	$director_cessation_resign_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '1' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_director_cessation_resign_result = $this->db->query("select client_officers.* from client_officers where position = '1' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$director_cessation_resign_result = $director_cessation_resign_result->result_array();

// 		                	$previous_director_cessation_resign_result = $previous_director_cessation_resign_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$director_cessation_resign = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_director_cessation_resign = (int)(count($director_cessation_resign_result)) - 1;

// 		                	for($g = 0; $g < count($director_cessation_resign_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$director_cessation_resign = $director_cessation_resign.'<strong>'.$director_cessation_resign_result[$g]["date_of_cessation"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_director_cessation_resign)
// 		                		{
// 		                			$director_cessation_resign = $director_cessation_resign.' and <strong>'.$director_cessation_resign_result[$g]["date_of_cessation"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$director_cessation_resign = $director_cessation_resign.', <strong>'.$director_cessation_resign_result[$g]["date_of_cessation"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$director_cessation_resign_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Directors - cessation date}}</span>', $director_cessation_resign, $str);

// 		                	if(count($previous_director_cessation_resign_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_director_cessation_resign_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_director_cessation_resign_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

		                

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Directors name - appointment}}</span>') !== false)
// 		                {
// 		                	$director_name_appointment_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '1' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_director_name_appointment_result = $this->db->query("select client_officers.* from client_officers where position = '1' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$director_name_appointment_result = $director_name_appointment_result->result_array();

// 		                	$previous_director_name_appointment_result = $previous_director_name_appointment_result->result_array();
// 		                	//echo json_encode($director_name_appointment_result);

// 		                	$director_name_appointment = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_director_name_appointment = (int)(count($director_name_appointment_result)) - 1;


// 		                	for($g = 0; $g < count($director_name_appointment_result); $g++)
// 		                	{
// 		                		if(strpos($str, '(Identification No. <span class="myclass mceNonEditable">{{Directors ID - appointment}}</span>)') !== false)
// 		                		{
// 		                			if($g == 0)
// 			                		{
// 			                			$director_name_appointment = $director_name_appointment.$director_name_appointment_result[$g]["name"].' (Identification No. '.$director_name_appointment_result[$g]["identification_no"].')';
// 			                		}
// 			                		elseif($g == (int)$num_of_director_name_appointment)
// 			                		{
// 			                			$director_name_appointment = $director_name_appointment.' and '.$director_name_appointment_result[$g]["name"].' (Identification No. '.$director_name_appointment_result[$g]["identification_no"].')';
// 			                		}
// 			                		else
// 			                		{
// 			                			$director_name_appointment = $director_name_appointment.', '.$director_name_appointment_result[$g]["name"].' (Identification No. '.$director_name_appointment_result[$g]["identification_no"].')';
// 			                		}
// 		                		}
// 		                		else
// 		                		{
// 			                		if($g == 0)
// 			                		{
// 			                			$director_name_appointment = $director_name_appointment.'<strong>'.$director_name_appointment_result[$g]["name"].'</strong>';
// 			                		}
// 			                		elseif($g == (int)$num_of_director_name_appointment)
// 			                		{
// 			                			$director_name_appointment = $director_name_appointment.' and <strong>'.$director_name_appointment_result[$g]["name"].'</strong>';
// 			                		}
// 			                		else
// 			                		{
// 			                			$director_name_appointment = $director_name_appointment.', <strong>'.$director_name_appointment_result[$g]["name"].'</strong>';
// 			                		}
// 			                	}
		                		
// 		                		array_push($latest_officer_id, (int)$director_name_appointment_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Directors ID - appointment}}</span>') !== false)
// 		                	{
// 		                		$str = str_replace('<span class="myclass mceNonEditable">{{Directors name - appointment}}</span> (Identification No. <span class="myclass mceNonEditable">{{Directors ID - appointment}}</span>)', $director_name_appointment, $str);
// 		                	}
// 		                	else
// 		                	{
// 		                		$str = str_replace('<span class="myclass mceNonEditable">{{Directors name - appointment}}</span>', $director_name_appointment, $str);
// 		                	}

// 		                	if(count($previous_director_name_appointment_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_director_name_appointment_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_director_name_appointment_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Directors ID - appointment}}</span>') !== false)
// 		                {
// 		                	$director_id_appointment_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '1' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_director_id_appointment_result = $this->db->query("select client_officers.* from client_officers where position = '1' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$director_id_appointment_result = $director_id_appointment_result->result_array();

// 		                	$previous_director_id_appointment_result = $previous_director_id_appointment_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$director_id_appointment = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_director_id_appointment = (int)(count($director_id_appointment_result)) - 1;

// 		                	for($g = 0; $g < count($director_id_appointment_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$director_id_appointment = $director_id_appointment.'<strong>'.$director_id_appointment_result[$g]["identification_no"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_director_id_appointment)
// 		                		{
// 		                			$director_id_appointment = $director_id_appointment.' and <strong>'.$director_id_appointment_result[$g]["identification_no"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$director_id_appointment = $director_id_appointment.', <strong>'.$director_id_appointment_result[$g]["identification_no"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$director_id_appointment_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Directors ID - appointment}}</span>', $director_id_appointment, $str);

// 		                	if(count($previous_director_id_appointment_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_director_id_appointment_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_director_id_appointment_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Directors - appointment date}}</span>') !== false)
// 		                {
// 		                	$director_appointment_date_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '1' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_director_appointment_date_result = $this->db->query("select client_officers.* from client_officers where position = '1' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$director_appointment_date_result = $director_appointment_date_result->result_array();

// 		                	$previous_director_appointment_date_result = $previous_director_appointment_date_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$director_appointment_date = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_director_appointment_date = (int)(count($director_appointment_date_result)) - 1;

// 		                	for($g = 0; $g < count($director_appointment_date_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$director_appointment_date = $director_appointment_date.'<strong>'.$director_appointment_date_result[$g]["date_of_appointment"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_director_appointment_date)
// 		                		{
// 		                			$director_appointment_date = $director_appointment_date.' and <strong>'.$director_appointment_date_result[$g]["date_of_appointment"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$director_appointment_date = $director_appointment_date.', <strong>'.$director_appointment_date_result[$g]["date_of_appointment"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$director_appointment_date_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Directors - appointment date}}</span>', $director_appointment_date, $str);

// 		                	if(count($previous_director_appointment_date_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_director_appointment_date_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_director_appointment_date_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Secretarys name - all}}</span>') !== false)
// 		                {
// 		                	$secretarys_name_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '4' AND company_code='".$company_code."' AND ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y') AND client_officers.date_of_cessation = '') OR ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) AND (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') > STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')) AND client_officers.date_of_cessation != '')) GROUP BY officer.identification_no, officer.name");

// 		                	$secretarys_name_result = $secretarys_name_result->result_array();

// 		                	//echo json_encode($director_name_result);


// 		                	$secretarys_name = "";

// 		                	for($g = 0; $g < count($secretarys_name_result); $g++)
// 		                	{
// 		                		$secretarys_name = $secretarys_name.'<p>&nbsp;</p>'.$secretarys_name_result[$g]["name"].' ____________________________<br>';
// 		                	}

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Secretarys name - all}}</span>', $secretarys_name, $str);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Secretarys ID - all}}</span>') !== false)
// 		                {
// 		                	$secretarys_id_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '4' AND company_code='".$company_code."' AND ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y') AND client_officers.date_of_cessation = '') OR ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) AND (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') > STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')) AND client_officers.date_of_cessation != '')) GROUP BY officer.identification_no, officer.name");

// 		                	$secretarys_id_result = $secretarys_id_result->result_array();

// 		                	$secretarys_id = "";

// 		                	for($g = 0; $g < count($secretarys_id_result); $g++)
// 		                	{
// 		                		$secretarys_id = $secretarys_id.'<p>&nbsp;</p>'.$secretarys_id_result[$g]["identification_no"].' ____________________________<br>';
// 		                	}

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Secretarys ID - all}}</span>', $secretarys_id, $str);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Secretarys name - resigning}}</span>') !== false)
// 		                {

// 		                	$secretarys_name_resign_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '4' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_secretarys_name_resign_result = $this->db->query("select client_officers.* from client_officers where position = '4' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$secretarys_name_resign_result = $secretarys_name_resign_result->result_array();

// 		                	$previous_secretarys_name_resign_result = $previous_secretarys_name_resign_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$secretarys_name_resign = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_secretarys_name_resign = (int)(count($secretarys_name_resign_result)) - 1;

// 		                	for($g = 0; $g < count($secretarys_name_resign_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$secretarys_name_resign = $secretarys_name_resign.'<strong>'.$secretarys_name_resign_result[$g]["name"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_secretarys_name_resign)
// 		                		{
// 		                			$secretarys_name_resign = $secretarys_name_resign.' and <strong>'.$secretarys_name_resign_result[$g]["name"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$secretarys_name_resign = $secretarys_name_resign.', <strong>'.$secretarys_name_resign_result[$g]["name"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$secretarys_name_resign_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Secretarys name - resigning}}</span>', $secretarys_name_resign, $str);

// 		                	if(count($previous_secretarys_name_resign_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_secretarys_name_resign_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_secretarys_name_resign_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Secretarys ID - resigning}}</span>') !== false)
// 		                {
// 		                	$secretarys_id_resign_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '4' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_secretarys_id_resign_result = $this->db->query("select client_officers.* from client_officers where position = '4' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$secretarys_id_resign_result = $secretarys_id_resign_result->result_array();

// 		                	$previous_secretarys_id_resign_result = $previous_secretarys_id_resign_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$secretarys_id_resign = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_secretarys_id_resign = (int)(count($secretarys_id_resign_result)) - 1;

// 		                	for($g = 0; $g < count($secretarys_id_resign_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$secretarys_id_resign = $secretarys_id_resign.'<strong>'.$secretarys_id_resign_result[$g]["identification_no"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_secretarys_id_resign)
// 		                		{
// 		                			$secretarys_id_resign = $secretarys_id_resign.' and <strong>'.$secretarys_id_resign_result[$g]["identification_no"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$secretarys_id_resign = $secretarys_id_resign.', <strong>'.$secretarys_id_resign_result[$g]["identification_no"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$secretarys_id_resign_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Secretarys ID - resigning}}</span>', $secretarys_id_resign, $str);

// 		                	if(count($previous_secretarys_id_resign_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_secretarys_id_resign_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_secretarys_id_resign_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Secretarys - cessation date}}</span>') !== false)
// 		                {
// 		                	$secretarys_cessation_resign_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '4' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_secretarys_cessation_resign_result = $this->db->query("select client_officers.* from client_officers where position = '4' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$secretarys_cessation_resign_result = $secretarys_cessation_resign_result->result_array();

// 		                	$previous_secretarys_cessation_resign_result = $previous_secretarys_cessation_resign_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$secretarys_cessation_resign = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_secretarys_cessation_resign = (int)(count($secretarys_cessation_resign_result)) - 1;

// 		                	for($g = 0; $g < count($secretarys_cessation_resign_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$secretarys_cessation_resign = $secretarys_cessation_resign.'<strong>'.$secretarys_cessation_resign_result[$g]["date_of_cessation"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_secretarys_cessation_resign)
// 		                		{
// 		                			$secretarys_cessation_resign = $secretarys_cessation_resign.' and <strong>'.$secretarys_cessation_resign_result[$g]["date_of_cessation"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$secretarys_cessation_resign = $secretarys_cessation_resign.', <strong>'.$secretarys_cessation_resign_result[$g]["date_of_cessation"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$secretarys_cessation_resign_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Secretarys - cessation date}}</span>', $secretarys_cessation_resign, $str);

// 		                	if(count($previous_secretarys_cessation_resign_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_secretarys_cessation_resign_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_secretarys_cessation_resign_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }
// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Secretarys name - appointment}}</span>') !== false)
// 		                {
// 		                	$secretarys_name_appointment_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '4' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_secretarys_name_appointment_result = $this->db->query("select client_officers.* from client_officers where position = '4' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$secretarys_name_appointment_result = $secretarys_name_appointment_result->result_array();

// 		                	$previous_secretarys_name_appointment_result = $previous_secretarys_name_appointment_result->result_array();
// 		                	//echo json_encode($director_name_appointment_result);

// 		                	$secretarys_name_appointment = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_director_name_appointment = (int)(count($secretarys_name_appointment_result)) - 1;

// 		                	for($g = 0; $g < count($secretarys_name_appointment_result); $g++)
// 		                	{

// 		                		if(strpos($str, '(Identification No. <span class="myclass mceNonEditable">{{Secretarys ID - appointment}}</span>)') !== false)
// 		                		{
// 		                			if($g == 0)
// 			                		{
// 			                			$secretarys_name_appointment = $secretarys_name_appointment.$secretarys_name_appointment_result[$g]["name"].' (Identification No. '.$secretarys_name_appointment_result[$g]["identification_no"].')';
// 			                		}
// 			                		elseif($g == (int)$num_of_director_name_appointment)
// 			                		{
// 			                			$secretarys_name_appointment = $secretarys_name_appointment.' and '.$secretarys_name_appointment_result[$g]["name"].' (Identification No. '.$secretarys_name_appointment_result[$g]["identification_no"].')';
// 			                		}
// 			                		else
// 			                		{
// 			                			$secretarys_name_appointment = $secretarys_name_appointment.', '.$secretarys_name_appointment_result[$g]["name"].' (Identification No. '.$secretarys_name_appointment_result[$g]["identification_no"].')';
// 			                		}
// 		                		}
// 		                		else
// 		                		{
// 			                		if($g == 0)
// 			                		{
// 			                			$secretarys_name_appointment = $secretarys_name_appointment.'<strong>'.$secretarys_name_appointment_result[$g]["name"].'</strong>';
// 			                		}
// 			                		elseif($g == (int)$num_of_director_name_appointment)
// 			                		{
// 			                			$secretarys_name_appointment = $secretarys_name_appointment.' and <strong>'.$secretarys_name_appointment_result[$g]["name"].'</strong>';
// 			                		}
// 			                		else
// 			                		{
// 			                			$secretarys_name_appointment = $secretarys_name_appointment.', <strong>'.$secretarys_name_appointment_result[$g]["name"].'</strong>';
// 			                		}
// 			                	}
		                		
// 		                		array_push($latest_officer_id, (int)$secretarys_name_appointment_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Secretarys ID - appointment}}</span>') !== false)
// 		                	{
// 		                		$str = str_replace('<span class="myclass mceNonEditable">{{Secretarys name - appointment}}</span> (Identification No. <span class="myclass mceNonEditable">{{Secretarys ID - appointment}}</span>)', $secretarys_name_appointment, $str);
// 		                	}
// 		                	else
// 		                	{
// 		                		$str = str_replace('<span class="myclass mceNonEditable">{{Secretarys name - appointment}}</span>', $secretarys_name_appointment, $str);
// 		                	}

// 		                	$officer_id_data = array();
// 		                	if(count($previous_secretarys_name_appointment_result) != 0)
// 		                	{
		                		
// 		                		for($f = 0; $f < count($previous_secretarys_name_appointment_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_secretarys_name_appointment_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Secretarys ID - appointment}}</span>') !== false)
// 		                {
// 		                	$secretarys_id_appointment_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '4' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_secretarys_id_appointment_result = $this->db->query("select client_officers.* from client_officers where position = '4' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$secretarys_id_appointment_result = $secretarys_id_appointment_result->result_array();

// 		                	$previous_secretarys_id_appointment_result = $previous_secretarys_id_appointment_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$secretarys_id_appointment = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_secretarys_id_appointment = (int)(count($secretarys_id_appointment_result)) - 1;

// 		                	for($g = 0; $g < count($secretarys_id_appointment_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$secretarys_id_appointment = $secretarys_id_appointment.'<strong>'.$secretarys_id_appointment_result[$g]["identification_no"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_secretarys_id_appointment)
// 		                		{
// 		                			$secretarys_id_appointment = $secretarys_id_appointment.' and <strong>'.$secretarys_id_appointment_result[$g]["identification_no"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$secretarys_id_appointment = $secretarys_id_appointment.', <strong>'.$secretarys_id_appointment_result[$g]["identification_no"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$secretarys_id_appointment_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Secretarys ID - appointment}}</span>', $secretarys_id_appointment, $str);

// 		                	$officer_id_data = array();
// 		                	if(count($previous_secretarys_id_appointment_result) != 0)
// 		                	{
// 		                		for($f = 0; $f < count($previous_secretarys_id_appointment_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_secretarys_id_appointment_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Secretarys - appointment date}}</span>') !== false)
// 		                {
// 		                	$secretarys_appointment_date_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '4' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$secretarys_director_appointment_date_result = $this->db->query("select client_officers.* from client_officers where position = '4' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$secretarys_appointment_date_result = $secretarys_appointment_date_result->result_array();

// 		                	$secretarys_director_appointment_date_result = $secretarys_director_appointment_date_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$secretarys_appointment_date = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_secretarys_appointment_date = (int)(count($secretarys_appointment_date_result)) - 1;

// 		                	for($g = 0; $g < count($secretarys_appointment_date_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$secretarys_appointment_date = $secretarys_appointment_date.'<strong>'.$secretarys_appointment_date_result[$g]["date_of_appointment"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_secretarys_appointment_date)
// 		                		{
// 		                			$secretarys_appointment_date = $secretarys_appointment_date.' and <strong>'.$secretarys_appointment_date_result[$g]["date_of_appointment"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$secretarys_appointment_date = $secretarys_appointment_date.', <strong>'.$secretarys_appointment_date_result[$g]["date_of_appointment"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$secretarys_appointment_date_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Secretarys - appointment date}}</span>', $secretarys_appointment_date, $str);

// 		                	if(count($secretarys_director_appointment_date_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($secretarys_director_appointment_date_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$secretarys_director_appointment_date_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }
// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Auditors name - all}}</span>') !== false)
// 		                {
// 		                	$auditors_name_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '5' AND company_code='".$company_code."' AND ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y') AND client_officers.date_of_cessation = '') OR ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) AND (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') > STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')) AND client_officers.date_of_cessation != '')) GROUP BY officer_company.register_no, officer_company.company_name");

// 		                	$auditors_name_result = $auditors_name_result->result_array();

// 		                	//echo json_encode($director_name_result);

// 		                	$auditors_name = "";

// 		                	for($g = 0; $g < count($auditors_name_result); $g++)
// 		                	{
// 		                		$auditors_name = $auditors_name.'<p>&nbsp;</p>'.$auditors_name_result[$g]["company_name"].' ____________________________<br>';
// 		                	}

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Auditors name - all}}</span>', $auditors_name, $str);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Auditors ID - all}}</span>') !== false)
// 		                {
// 		                	$auditors_id_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '5' AND company_code='".$company_code."' AND ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y') AND client_officers.date_of_cessation = '') OR ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) AND (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') > STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')) AND client_officers.date_of_cessation != '')) GROUP BY officer_company.register_no, officer_company.company_name");

// 		                	$auditors_id_result = $auditors_id_result->result_array();

// 		                	$auditors_id = "";

// 		                	for($g = 0; $g < count($auditors_id_result); $g++)
// 		                	{
// 		                		$auditors_id = $auditors_id.'<p>&nbsp;</p>'.$auditors_id_result[$g]["register_no"].' ____________________________<br>';
// 		                	}

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Auditors ID - all}}</span>', $auditors_id, $str);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Auditors name - resigning}}</span>') !== false)
// 		                {

// 		                	$auditors_name_resign_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '5' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer_company.register_no, officer_company.company_name ORDER BY client_officers.id");

// 		                	$previous_auditors_name_resign_result = $this->db->query("select client_officers.* from client_officers where position = '5' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$auditors_name_resign_result = $auditors_name_resign_result->result_array();

// 		                	$previous_auditors_name_resign_result = $previous_auditors_name_resign_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$auditors_name_resign = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_auditors_name_resign = (int)(count($auditors_name_resign_result)) - 1;

// 		                	for($g = 0; $g < count($auditors_name_resign_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$auditors_name_resign = $auditors_name_resign.'<strong>'.$auditors_name_resign_result[$g]["company_name"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_auditors_name_resign)
// 		                		{
// 		                			$auditors_name_resign = $auditors_name_resign.' and <strong>'.$auditors_name_resign_result[$g]["company_name"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$auditors_name_resign = $auditors_name_resign.', <strong>'.$auditors_name_resign_result[$g]["company_name"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$auditors_name_resign_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Auditors name - resigning}}</span>', $auditors_name_resign, $str);

// 		                	if(count($previous_auditors_name_resign_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_auditors_name_resign_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_auditors_name_resign_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Auditors ID - resigning}}</span>') !== false)
// 		                {
// 		                	$auditors_id_resign_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '5' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer_company.register_no, officer_company.company_name ORDER BY client_officers.id");

// 		                	$previous_auditors_id_resign_result = $this->db->query("select client_officers.* from client_officers where position = '5' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$auditors_id_resign_result = $auditors_id_resign_result->result_array();

// 		                	$previous_auditors_id_resign_result = $previous_auditors_id_resign_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$auditors_id_resign = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_auditors_id_resign = (int)(count($auditors_id_resign_result)) - 1;

// 		                	for($g = 0; $g < count($auditors_id_resign_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$auditors_id_resign = $auditors_id_resign.'<strong>'.$auditors_id_resign_result[$g]["register_no"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_auditors_id_resign)
// 		                		{
// 		                			$auditors_id_resign = $auditors_id_resign.' and <strong>'.$auditors_id_resign_result[$g]["register_no"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$auditors_id_resign = $auditors_id_resign.', <strong>'.$auditors_id_resign_result[$g]["register_no"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$auditors_id_resign_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Auditors ID - resigning}}</span>', $auditors_id_resign, $str);

// 		                	if(count($previous_auditors_id_resign_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_auditors_id_resign_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_auditors_id_resign_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Auditors - cessation date}}</span>') !== false)
// 		                {
// 		                	$auditors_cessation_resign_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '5' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer_company.register_no, officer_company.company_name ORDER BY client_officers.id");

// 		                	$previous_auditors_cessation_resign_result = $this->db->query("select client_officers.* from client_officers where position = '5' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$auditors_cessation_resign_result = $auditors_cessation_resign_result->result_array();

// 		                	$previous_auditors_cessation_resign_result = $previous_auditors_cessation_resign_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$auditors_cessation_resign = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_auditors_cessation_resign = (int)(count($auditors_cessation_resign_result)) - 1;

// 		                	for($g = 0; $g < count($auditors_cessation_resign_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$auditors_cessation_resign = $auditors_cessation_resign.'<strong>'.$auditors_cessation_resign_result[$g]["date_of_cessation"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_auditors_cessation_resign)
// 		                		{
// 		                			$auditors_cessation_resign = $auditors_cessation_resign.' and <strong>'.$auditors_cessation_resign_result[$g]["date_of_cessation"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$auditors_cessation_resign = $auditors_cessation_resign.', <strong>'.$auditors_cessation_resign_result[$g]["date_of_cessation"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$auditors_cessation_resign_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Auditors - cessation date}}</span>', $auditors_cessation_resign, $str);

// 		                	if(count($previous_auditors_cessation_resign_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_auditors_cessation_resign_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_auditors_cessation_resign_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if($result[$r]["document_name"] == "Auditor-Shorter notice of EGM" || $result[$r]["document_name"] == "Auditor-Attendance List")
// 		                {
// 		                	$auditors_name_appointment_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '5' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer_company.register_no, officer_company.company_name ORDER BY client_officers.id");

// 		                	$previous_auditors_name_appointment_result = $this->db->query("select client_officers.* from client_officers where position = '5' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");

// 		                	$auditors_name_appointment_result = $auditors_name_appointment_result->result_array();

// 		                	$previous_auditors_name_appointment_result = $previous_auditors_name_appointment_result->result_array();

// 		                	$latest_officer_id = array();

// 		                	for($g = 0; $g < count($auditors_name_appointment_result); $g++)
// 		                	{
// 		                		array_push($latest_officer_id, (int)$auditors_name_appointment_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	if(count($previous_auditors_name_appointment_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_auditors_name_appointment_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_auditors_name_appointment_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Auditors name - appointment}}</span>') !== false)
// 		                {
// 		                	$auditors_name_appointment_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '5' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer_company.register_no, officer_company.company_name ORDER BY client_officers.id");

// 		                	$previous_auditors_name_appointment_result = $this->db->query("select client_officers.* from client_officers where position = '5' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$auditors_name_appointment_result = $auditors_name_appointment_result->result_array();

// 		                	$previous_auditors_name_appointment_result = $previous_auditors_name_appointment_result->result_array();
// 		                	//echo json_encode($auditors_name_appointment_result);

// 		                	$auditors_name_appointment = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_auditors_name_appointment = (int)(count($auditors_name_appointment_result)) - 1;

// 		                	for($g = 0; $g < count($auditors_name_appointment_result); $g++)
// 		                	{
// 		                		if(strpos($str, '(Identification No. <span class="myclass mceNonEditable">{{Auditors ID - appointment}}</span>)') !== false)
// 		                		{
// 		                			if($g == 0)
// 			                		{
// 			                			$auditors_name_appointment = $auditors_name_appointment.$auditors_name_appointment_result[$g]["company_name"].' (Identification No. '.$auditors_name_appointment_result[$g]["register_no"].')';
// 			                		}
// 			                		elseif($g == (int)$num_of_auditors_name_appointment)
// 			                		{
// 			                			$auditors_name_appointment = $auditors_name_appointment.' and '.$auditors_name_appointment_result[$g]["company_name"].' (Identification No. '.$auditors_name_appointment_result[$g]["register_no"].')';
// 			                		}
// 			                		else
// 			                		{
// 			                			$auditors_name_appointment = $auditors_name_appointment.', '.$auditors_name_appointment_result[$g]["company_name"].' (Identification No. '.$auditors_name_appointment_result[$g]["register_no"].')';
// 			                		}
// 		                		}
// 		                		else
// 		                		{
// 			                		if($g == 0)
// 			                		{
// 			                			$auditors_name_appointment = $auditors_name_appointment.'<strong>'.$auditors_name_appointment_result[$g]["company_name"].'</strong>';
// 			                		}
// 			                		elseif($g == (int)$num_of_auditors_name_appointment)
// 			                		{
// 			                			$auditors_name_appointment = $auditors_name_appointment.' and <strong>'.$auditors_name_appointment_result[$g]["company_name"].'</strong>';
// 			                		}
// 			                		else
// 			                		{
// 			                			$auditors_name_appointment = $auditors_name_appointment.', <strong>'.$auditors_name_appointment_result[$g]["company_name"].'</strong>';
// 			                		}
// 			                	}
		                		
// 		                		array_push($latest_officer_id, (int)$auditors_name_appointment_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Auditors ID - appointment}}</span>') !== false)
// 		                	{
// 		                		$str = str_replace('<span class="myclass mceNonEditable">{{Auditors name - appointment}}</span> (Identification No. <span class="myclass mceNonEditable">{{Auditors ID - appointment}}</span>)', $auditors_name_appointment, $str);
// 		                	}
// 		                	else
// 		                	{
// 		                		$str = str_replace('<span class="myclass mceNonEditable">{{Auditors name - appointment}}</span>', $auditors_name_appointment, $str);
// 		                	}

// 		                	if(count($previous_auditors_name_appointment_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_auditors_name_appointment_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_auditors_name_appointment_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Auditors ID - appointment}}</span>') !== false)
// 		                {
// 		                	$auditors_id_appointment_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '5' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer_company.register_no, officer_company.company_name ORDER BY client_officers.id");

// 		                	$previous_auditors_id_appointment_result = $this->db->query("select client_officers.* from client_officers where position = '5' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$auditors_id_appointment_result = $auditors_id_appointment_result->result_array();

// 		                	$previous_auditors_id_appointment_result = $previous_auditors_id_appointment_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$auditors_id_appointment = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_auditors_id_appointment = (int)(count($auditors_id_appointment_result)) - 1;

// 		                	for($g = 0; $g < count($auditors_id_appointment_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$auditors_id_appointment = $auditors_id_appointment.'<strong>'.$auditors_id_appointment_result[$g]["register_no"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_auditors_id_appointment)
// 		                		{
// 		                			$auditors_id_appointment = $auditors_id_appointment.' and <strong>'.$auditors_id_appointment_result[$g]["register_no"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$auditors_id_appointment = $auditors_id_appointment.', <strong>'.$auditors_id_appointment_result[$g]["register_no"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$auditors_id_appointment_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Auditors ID - appointment}}</span>', $auditors_id_appointment, $str);

// 		                	if(count($previous_auditors_id_appointment_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_auditors_id_appointment_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_auditors_id_appointment_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Auditors - appointment date}}</span>') !== false)
// 		                {
// 		                	$auditors_appointment_date_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '5' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer_company.register_no, officer_company.company_name ORDER BY client_officers.id");

// 		                	$auditors_director_appointment_date_result = $this->db->query("select client_officers.* from client_officers where position = '5' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$auditors_appointment_date_result = $auditors_appointment_date_result->result_array();

// 		                	$auditors_director_appointment_date_result = $auditors_director_appointment_date_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$auditors_appointment_date = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_auditors_appointment_date = (int)(count($auditors_appointment_date_result)) - 1;

// 		                	for($g = 0; $g < count($auditors_appointment_date_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$auditors_appointment_date = $auditors_appointment_date.'<strong>'.$auditors_appointment_date_result[$g]["date_of_appointment"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_auditors_appointment_date)
// 		                		{
// 		                			$auditors_appointment_date = $auditors_appointment_date.' and <strong>'.$auditors_appointment_date_result[$g]["date_of_appointment"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$auditors_appointment_date = $auditors_appointment_date.', <strong>'.$auditors_appointment_date_result[$g]["date_of_appointment"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$auditors_appointment_date_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Auditors - appointment date}}</span>', $auditors_appointment_date, $str);

// 		                	if(count($auditors_director_appointment_date_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($auditors_director_appointment_date_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$auditors_director_appointment_date_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Managers name - all}}</span>') !== false)
// 		                {
// 		                	$manager_name_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '3' AND company_code='".$company_code."' AND ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y') AND client_officers.date_of_cessation = '') OR ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) AND (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') > STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')) AND client_officers.date_of_cessation != '')) GROUP BY officer.identification_no, officer.name");
		                	
// 		                	$manager_name_result = $manager_name_result->result_array();

// 		                	//echo json_encode($director_name_result);
// 		                	//((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) OR (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') 

// 		                	$manager_name = "";

// 		                	for($g = 0; $g < count($manager_name_result); $g++)
// 		                	{
// 		                		$manager_name = $manager_name.'<p>&nbsp;</p>'.$manager_name_result[$g]["name"].' ____________________________<br>';
// 		                	}

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Managers name - all}}</span>', $manager_name, $str);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Managers ID - all}}</span>') !== false)
// 		                {
// 		                	$manager_id_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '3' AND company_code='".$company_code."' AND ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y') AND client_officers.date_of_cessation = '') OR ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) AND (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') > STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')) AND client_officers.date_of_cessation != '')) GROUP BY officer.identification_no, officer.name");

// 		                	$manager_id_result = $manager_id_result->result_array();

// 		                	$manager_id = "";

// 		                	for($g = 0; $g < count($manager_id_result); $g++)
// 		                	{
// 		                		$manager_id = $manager_id.'<p>&nbsp;</p>'.$manager_id_result[$g]["identification_no"].' ____________________________<br>';
// 		                	}

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Managers ID - all}}</span>', $manager_id, $str);
// 		                }
		                
// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Managers name - resigning}}</span>') !== false)
// 		                {

// 		                	$manager_name_resign_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '3' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_manager_name_resign_result = $this->db->query("select client_officers.* from client_officers where position = '3' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$manager_name_resign_result = $manager_name_resign_result->result_array();

// 		                	$previous_manager_name_resign_result = $previous_manager_name_resign_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$manager_name_resign = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_manager_name_resign = (int)(count($manager_name_resign_result)) - 1;

// 		                	for($g = 0; $g < count($manager_name_resign_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$manager_name_resign = $manager_name_resign.$manager_name_resign_result[$g]["name"];
// 		                		}
// 		                		elseif($g == (int)$num_of_manager_name_resign)
// 		                		{
// 		                			$manager_name_resign = $manager_name_resign.' and '.$manager_name_resign_result[$g]["name"];
// 		                		}
// 		                		else
// 		                		{
// 		                			$manager_name_resign = $manager_name_resign.', '.$manager_name_resign_result[$g]["name"];
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$manager_name_resign_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Managers name - resigning}}</span>', $manager_name_resign, $str);

// 		                	if(count($previous_manager_name_resign_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_manager_name_resign_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_manager_name_resign_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Managers ID - resigning}}</span>') !== false)
// 		                {
// 		                	$manager_id_resign_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '3' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_manager_id_resign_result = $this->db->query("select client_officers.* from client_officers where position = '3' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$manager_id_resign_result = $manager_id_resign_result->result_array();

// 		                	$previous_manager_id_resign_result = $previous_manager_id_resign_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$manager_id_resign = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_manager_id_resign = (int)(count($manager_id_resign_result)) - 1;

// 		                	for($g = 0; $g < count($manager_id_resign_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$manager_id_resign = $manager_id_resign.$manager_id_resign_result[$g]["identification_no"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_manager_id_resign)
// 		                		{
// 		                			$manager_id_resign = $manager_id_resign.' and '.$manager_id_resign_result[$g]["identification_no"];
// 		                		}
// 		                		else
// 		                		{
// 		                			$manager_id_resign = $manager_id_resign.', '.$manager_id_resign_result[$g]["identification_no"];
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$manager_id_resign_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Managers ID - resigning}}</span>', $manager_id_resign, $str);

// 		                	if(count($previous_manager_id_resign_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_manager_id_resign_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_manager_id_resign_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Managers - cessation date}}</span>') !== false)
// 		                {
// 		                	$manager_cessation_resign_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '3' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_manager_cessation_resign_result = $this->db->query("select client_officers.* from client_officers where position = '3' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$manager_cessation_resign_result = $manager_cessation_resign_result->result_array();

// 		                	$previous_manager_cessation_resign_result = $previous_manager_cessation_resign_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$manager_cessation_resign = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_manager_cessation_resign = (int)(count($manager_cessation_resign_result)) - 1;

// 		                	for($g = 0; $g < count($manager_cessation_resign_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$manager_cessation_resign = $manager_cessation_resign.'<strong>'.$manager_cessation_resign_result[$g]["date_of_cessation"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_manager_cessation_resign)
// 		                		{
// 		                			$manager_cessation_resign = $manager_cessation_resign.' and <strong>'.$manager_cessation_resign_result[$g]["date_of_cessation"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$manager_cessation_resign = $manager_cessation_resign.', <strong>'.$manager_cessation_resign_result[$g]["date_of_cessation"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$manager_cessation_resign_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Managers - cessation date}}</span>', $manager_cessation_resign, $str);

// 		                	if(count($previous_manager_cessation_resign_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_manager_cessation_resign_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_manager_cessation_resign_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if($result[$r]["document_name"] == "Manager-Shorter notice of EGM" || $result[$r]["document_name"] == "Manager-Attendance List")
// 		                {
// 		                	$manager_name_appointment_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '3' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_manager_name_appointment_result = $this->db->query("select client_officers.* from client_officers where position = '3' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");

// 		                	$manager_name_appointment_result = $manager_name_appointment_result->result_array();

// 		                	$previous_manager_name_appointment_result = $previous_manager_name_appointment_result->result_array();

// 		                	$latest_officer_id = array();

// 		                	for($g = 0; $g < count($manager_name_appointment_result); $g++)
// 		                	{
// 		                		array_push($latest_officer_id, (int)$manager_name_appointment_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	if(count($previous_manager_name_appointment_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_manager_name_appointment_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_manager_name_appointment_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Managers name - appointment}}</span>') !== false)
// 		                {
// 		                	$manager_name_appointment_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '3' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_manager_name_appointment_result = $this->db->query("select client_officers.* from client_officers where position = '3' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$manager_name_appointment_result = $manager_name_appointment_result->result_array();

// 		                	$previous_manager_name_appointment_result = $previous_manager_name_appointment_result->result_array();
// 		                	//echo json_encode($director_name_appointment_result);

// 		                	$manager_name_appointment = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_manager_name_appointment = (int)(count($manager_name_appointment_result)) - 1;

// 		                	for($g = 0; $g < count($manager_name_appointment_result); $g++)
// 		                	{
// 		                		if(strpos($str, '(Identification No. <span class="myclass mceNonEditable">{{Managers ID - appointment}}</span>)') !== false)
// 		                		{
// 		                			if($g == 0)
// 			                		{
// 			                			$manager_name_appointment = $manager_name_appointment.$manager_name_appointment_result[$g]["name"].' (Identification No. '.$manager_name_appointment_result[$g]["identification_no"].')';
// 			                		}
// 			                		elseif($g == (int)$num_of_manager_name_appointment)
// 			                		{
// 			                			$manager_name_appointment = $manager_name_appointment.' and '.$manager_name_appointment_result[$g]["name"].' (Identification No. '.$manager_name_appointment_result[$g]["identification_no"].')';
// 			                		}
// 			                		else
// 			                		{
// 			                			$manager_name_appointment = $manager_name_appointment.', '.$manager_name_appointment_result[$g]["name"].' (Identification No. '.$manager_name_appointment_result[$g]["identification_no"].')';
// 			                		}
// 		                		}
// 		                		else
// 		                		{
// 			                		if($g == 0)
// 			                		{
// 			                			$manager_name_appointment = $manager_name_appointment.'<strong>'.$manager_name_appointment_result[$g]["name"].'</strong>';
// 			                		}
// 			                		elseif($g == (int)$num_of_manager_name_appointment)
// 			                		{
// 			                			$manager_name_appointment = $manager_name_appointment.' and <strong>'.$manager_name_appointment_result[$g]["name"].'</strong>';
// 			                		}
// 			                		else
// 			                		{
// 			                			$manager_name_appointment = $manager_name_appointment.', <strong>'.$manager_name_appointment_result[$g]["name"].'</strong>';
// 			                		}
// 			                	}
		                		
// 		                		array_push($latest_officer_id, (int)$manager_name_appointment_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Managers ID - appointment}}</span>') !== false)
// 		                	{
// 		                		$str = str_replace('<span class="myclass mceNonEditable">{{Managers name - appointment}}</span> (Identification No. <span class="myclass mceNonEditable">{{Managers ID - appointment}}</span>)', $manager_name_appointment, $str);
// 		                	}
// 		                	else
// 		                	{
// 		                		$str = str_replace('<span class="myclass mceNonEditable">{{Managers name - appointment}}</span>', $manager_name_appointment, $str);
// 		                	}


// 		                	if(count($previous_manager_name_appointment_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_manager_name_appointment_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_manager_name_appointment_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Managers ID - appointment}}</span>') !== false)
// 		                {
// 		                	$manager_id_appointment_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '3' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_manager_id_appointment_result = $this->db->query("select client_officers.* from client_officers where position = '3' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$manager_id_appointment_result = $manager_id_appointment_result->result_array();

// 		                	$previous_manager_id_appointment_result = $previous_manager_id_appointment_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$manager_id_appointment = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_manager_id_appointment = (int)(count($manager_id_appointment_result)) - 1;

// 		                	for($g = 0; $g < count($manager_id_appointment_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$manager_id_appointment = $manager_id_appointment.'<strong>'.$manager_id_appointment_result[$g]["identification_no"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_manager_id_appointment)
// 		                		{
// 		                			$manager_id_appointment = $manager_id_appointment.' and <strong>'.$manager_id_appointment_result[$g]["identification_no"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$manager_id_appointment = $manager_id_appointment.', <strong>'.$manager_id_appointment_result[$g]["identification_no"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$manager_id_appointment_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Managers ID - appointment}}</span>', $manager_id_appointment, $str);

// 		                	if(count($previous_manager_id_appointment_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_manager_id_appointment_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_manager_id_appointment_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Managers - appointment date}}</span>') !== false)
// 		                {
// 		                	$manager_appointment_date_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '3' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_manager_appointment_date_result = $this->db->query("select client_officers.* from client_officers where position = '3' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$manager_appointment_date_result = $manager_appointment_date_result->result_array();

// 		                	$previous_manager_appointment_date_result = $previous_manager_appointment_date_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$manager_appointment_date = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_manager_appointment_date = (int)(count($manager_appointment_date_result)) - 1;

// 		                	for($g = 0; $g < count($manager_appointment_date_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$manager_appointment_date = $manager_appointment_date.'<strong>'.$manager_appointment_date_result[$g]["date_of_appointment"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_manager_appointment_date)
// 		                		{
// 		                			$manager_appointment_date = $manager_appointment_date.' and <strong>'.$manager_appointment_date_result[$g]["date_of_appointment"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$manager_appointment_date = $manager_appointment_date.', <strong>'.$manager_appointment_date_result[$g]["date_of_appointment"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$manager_appointment_date_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Managers - appointment date}}</span>', $manager_appointment_date, $str);

// 		                	if(count($previous_manager_appointment_date_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_manager_appointment_date_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_manager_appointment_date_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{CEO name - old}}</span>') !== false)
// 		                {

// 		                	$ceo_name_resign_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '2' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_ceo_name_resign_result = $this->db->query("select client_officers.* from client_officers where position = '2' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$ceo_name_resign_result = $ceo_name_resign_result->result_array();

// 		                	$previous_ceo_name_resign_result = $previous_ceo_name_resign_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$ceo_name_resign = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_ceo_name_resign = (int)(count($ceo_name_resign_result)) - 1;

// 		                	for($g = 0; $g < count($ceo_name_resign_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$ceo_name_resign = $ceo_name_resign.$ceo_name_resign_result[$g]["name"];
// 		                		}
// 		                		elseif($g == (int)$num_of_ceo_name_resign)
// 		                		{
// 		                			$ceo_name_resign = $ceo_name_resign.' and '.$ceo_name_resign_result[$g]["name"];
// 		                		}
// 		                		else
// 		                		{
// 		                			$ceo_name_resign = $ceo_name_resign.', '.$ceo_name_resign_result[$g]["name"];
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$ceo_name_resign_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{CEO name - old}}</span>', $ceo_name_resign, $str);

// 		                	if(count($previous_ceo_name_resign_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_ceo_name_resign_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_ceo_name_resign_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{CEO ID - old}}</span>') !== false)
// 		                {
// 		                	$ceo_id_resign_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '2' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_ceo_id_resign_result = $this->db->query("select client_officers.* from client_officers where position = '2' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$ceo_id_resign_result = $ceo_id_resign_result->result_array();

// 		                	$previous_ceo_id_resign_result = $previous_ceo_id_resign_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$ceo_id_resign = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_ceo_id_resign = (int)(count($ceo_id_resign_result)) - 1;

// 		                	for($g = 0; $g < count($ceo_id_resign_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$ceo_id_resign = $ceo_id_resign.$ceo_id_resign_result[$g]["identification_no"];
// 		                		}
// 		                		elseif($g == (int)$num_of_ceo_id_resign)
// 		                		{
// 		                			$ceo_id_resign = $ceo_id_resign.' and '.$ceo_id_resign_result[$g]["identification_no"];
// 		                		}
// 		                		else
// 		                		{
// 		                			$ceo_id_resign = $ceo_id_resign.', '.$ceo_id_resign_result[$g]["identification_no"];
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$ceo_id_resign_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{CEO ID - old}}</span>', $ceo_id_resign, $str);

// 		                	if(count($previous_ceo_id_resign_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_ceo_id_resign_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_ceo_id_resign_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{CEO date of cessation}}</span>') !== false)
// 		                {
// 		                	$ceo_cessation_resign_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '2' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_ceo_cessation_resign_result = $this->db->query("select client_officers.* from client_officers where position = '2' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$ceo_cessation_resign_result = $ceo_cessation_resign_result->result_array();

// 		                	$previous_ceo_cessation_resign_result = $previous_ceo_cessation_resign_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$ceo_cessation_resign = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_ceo_cessation_resign = (int)(count($ceo_cessation_resign_result)) - 1;

// 		                	for($g = 0; $g < count($ceo_cessation_resign_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$ceo_cessation_resign = $ceo_cessation_resign.'<strong>'.$ceo_cessation_resign_result[$g]["date_of_cessation"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_ceo_cessation_resign)
// 		                		{
// 		                			$ceo_cessation_resign = $ceo_cessation_resign.' and <strong>'.$ceo_cessation_resign_result[$g]["date_of_cessation"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$ceo_cessation_resign = $ceo_cessation_resign.', <strong>'.$ceo_cessation_resign_result[$g]["date_of_cessation"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$ceo_cessation_resign_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{CEO date of cessation}}</span>', $ceo_cessation_resign, $str);

// 		                	if(count($previous_ceo_cessation_resign_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_ceo_cessation_resign_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_ceo_cessation_resign_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if($result[$r]["document_name"] == "CEO-Shorter notice of EGM" || $result[$r]["document_name"] == "CEO-Attendance List")
// 		                {
// 		                	$ceo_name_appointment_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '2' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_ceo_name_appointment_result = $this->db->query("select client_officers.* from client_officers where position = '2' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");

// 		                	$ceo_name_appointment_result = $ceo_name_appointment_result->result_array();

// 		                	$previous_ceo_name_appointment_result = $previous_ceo_name_appointment_result->result_array();

// 		                	$latest_officer_id = array();

// 		                	for($g = 0; $g < count($ceo_name_appointment_result); $g++)
// 		                	{
// 		                		array_push($latest_officer_id, (int)$ceo_name_appointment_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	if(count($previous_ceo_name_appointment_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_ceo_name_appointment_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_ceo_name_appointment_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{CEO name - new}}</span>') !== false)
// 		                {
// 		                	$ceo_name_appointment_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '2' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_ceo_name_appointment_result = $this->db->query("select client_officers.* from client_officers where position = '2' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$ceo_name_appointment_result = $ceo_name_appointment_result->result_array();

// 		                	$previous_ceo_name_appointment_result = $previous_ceo_name_appointment_result->result_array();
// 		                	//echo json_encode($ceo_name_appointment_result);

// 		                	$ceo_name_appointment = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_ceo_name_appointment = (int)(count($ceo_name_appointment_result)) - 1;

// 		                	for($g = 0; $g < count($ceo_name_appointment_result); $g++)
// 		                	{
// 		                		if(strpos($str, '(Identification No. <span class="myclass mceNonEditable">{{CEO ID - new}}</span>)') !== false)
// 		                		{
// 		                			if($g == 0)
// 			                		{
// 			                			$ceo_name_appointment = $ceo_name_appointment.$ceo_name_appointment_result[$g]["name"].' (Identification No. '.$ceo_name_appointment_result[$g]["identification_no"].')';
// 			                		}
// 			                		elseif($g == (int)$num_of_ceo_name_appointment)
// 			                		{
// 			                			$ceo_name_appointment = $ceo_name_appointment.' and '.$ceo_name_appointment_result[$g]["name"].' (Identification No. '.$ceo_name_appointment_result[$g]["identification_no"].')';
// 			                		}
// 			                		else
// 			                		{
// 			                			$ceo_name_appointment = $ceo_name_appointment.', '.$ceo_name_appointment_result[$g]["name"].' (Identification No. '.$ceo_name_appointment_result[$g]["identification_no"].')';
// 			                		}
// 		                		}
// 		                		else
// 		                		{
// 			                		if($g == 0)
// 			                		{
// 			                			$ceo_name_appointment = $ceo_name_appointment.$ceo_name_appointment_result[$g]["name"];
// 			                		}
// 			                		elseif($g == (int)$num_of_ceo_name_appointment)
// 			                		{
// 			                			$ceo_name_appointment = $ceo_name_appointment.' and '.$ceo_name_appointment_result[$g]["name"];
// 			                		}
// 			                		else
// 			                		{
// 			                			$ceo_name_appointment = $ceo_name_appointment.', '.$ceo_name_appointment_result[$g]["name"];
// 			                		}
// 			                	}
		                		
// 		                		array_push($latest_officer_id, (int)$ceo_name_appointment_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	if(strpos($str, '<span class="myclass mceNonEditable">{{CEO ID - new}}</span>') !== false)
// 		                	{
// 		                		$str = str_replace('<span class="myclass mceNonEditable">{{CEO name - new}}</span> (Identification No. <span class="myclass mceNonEditable">{{CEO ID - new}}</span>)', $ceo_name_appointment, $str);
// 		                	}
// 		                	else
// 		                	{
// 		                		$str = str_replace('<span class="myclass mceNonEditable">{{CEO name - new}}</span>', $ceo_name_appointment, $str);
// 		                	}

// 		                	if(count($previous_ceo_name_appointment_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_ceo_name_appointment_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_ceo_name_appointment_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{CEO ID - new}}</span>') !== false)
// 		                {
// 		                	$ceo_id_appointment_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '2' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_ceo_id_appointment_result = $this->db->query("select client_officers.* from client_officers where position = '2' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$ceo_id_appointment_result = $ceo_id_appointment_result->result_array();

// 		                	$previous_ceo_id_appointment_result = $previous_ceo_id_appointment_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$ceo_id_appointment = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_ceo_id_appointment = (int)(count($ceo_id_appointment_result)) - 1;

// 		                	for($g = 0; $g < count($ceo_id_appointment_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$ceo_id_appointment = $ceo_id_appointment.$ceo_id_appointment_result[$g]["identification_no"];
// 		                		}
// 		                		elseif($g == (int)$num_of_ceo_id_appointment)
// 		                		{
// 		                			$ceo_id_appointment = $ceo_id_appointment.' and '.$ceo_id_appointment_result[$g]["identification_no"];
// 		                		}
// 		                		else
// 		                		{
// 		                			$ceo_id_appointment = $ceo_id_appointment.', '.$ceo_id_appointment_result[$g]["identification_no"];
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$ceo_id_appointment_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{CEO ID - new}}</span>', $ceo_id_appointment, $str);

// 		                	if(count($previous_ceo_id_appointment_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_ceo_id_appointment_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_ceo_id_appointment_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{CEO date of appointment}}</span>') !== false)
// 		                {
// 		                	$ceo_appointment_date_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '2' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_ceo_appointment_date_result = $this->db->query("select client_officers.* from client_officers where position = '2' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$ceo_appointment_date_result = $ceo_appointment_date_result->result_array();

// 		                	$previous_ceo_appointment_date_result = $previous_ceo_appointment_date_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$ceo_appointment_date = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_ceo_appointment_date = (int)(count($ceo_appointment_date_result)) - 1;

// 		                	for($g = 0; $g < count($ceo_appointment_date_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$ceo_appointment_date = $ceo_appointment_date.'<strong>'.$ceo_appointment_date_result[$g]["date_of_appointment"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_ceo_appointment_date)
// 		                		{
// 		                			$ceo_appointment_date = $ceo_appointment_date.' and <strong>'.$ceo_appointment_date_result[$g]["date_of_appointment"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$ceo_appointment_date = $ceo_appointment_date.', <strong>'.$ceo_appointment_date_result[$g]["date_of_appointment"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$ceo_appointment_date_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{CEO date of appointment}}</span>', $ceo_appointment_date, $str);

// 		                	if(count($previous_ceo_appointment_date_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_ceo_appointment_date_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_ceo_appointment_date_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{CEO name (current)}}</span>') !== false)
// 		                {
// 		                	$ceo_name_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '2' AND company_code='".$company_code."' AND ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y') AND client_officers.date_of_cessation = '') OR ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) AND (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') > STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')) AND client_officers.date_of_cessation != '')) GROUP BY officer.identification_no, officer.name");
		                	
// 		                	$ceo_name_result = $ceo_name_result->result_array();

// 		                	//echo json_encode($director_name_result);
// 		                	//((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) OR (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') 

// 		                	$ceo_name = "";

// 		                	for($g = 0; $g < count($ceo_name_result); $g++)
// 		                	{
// 		                		$ceo_name = $ceo_name.'<p>&nbsp;</p>'.$ceo_name_result[$g]["name"].' ____________________________<br>';
// 		                	}

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{CEO name (current)}}</span>', $ceo_name, $str);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{CEO ID (current)}}</span>') !== false)
// 		                {
// 		                	$ceo_id_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '2' AND company_code='".$company_code."' AND ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y') AND client_officers.date_of_cessation = '') OR ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) AND (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') > STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')) AND client_officers.date_of_cessation != '')) GROUP BY officer.identification_no, officer.name");

// 		                	$ceo_id_result = $ceo_id_result->result_array();

// 		                	$ceo_id = "";

// 		                	for($g = 0; $g < count($ceo_id_result); $g++)
// 		                	{
// 		                		$ceo_id = $ceo_id.'<p>&nbsp;</p>'.$ceo_id_result[$g]["identification_no"].' ____________________________<br>';
// 		                	}

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{CEO ID (current)}}</span>', $ceo_id, $str);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Managing Director name - old}}</span>') !== false)
// 		                {

// 		                	$managing_director_name_resign_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '6' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_managing_director_name_resign_result = $this->db->query("select client_officers.* from client_officers where position = '6' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$managing_director_name_resign_result = $managing_director_name_resign_result->result_array();

// 		                	$previous_managing_director_name_resign_result = $previous_managing_director_name_resign_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$managing_director_name_resign = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_managing_director_name_resign = (int)(count($managing_director_name_resign_result)) - 1;

// 		                	for($g = 0; $g < count($managing_director_name_resign_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$managing_director_name_resign = $managing_director_name_resign.'<strong>'.$managing_director_name_resign_result[$g]["name"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_managing_director_name_resign)
// 		                		{
// 		                			$managing_director_name_resign = $managing_director_name_resign.' and <strong>'.$managing_director_name_resign_result[$g]["name"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$managing_director_name_resign = $managing_director_name_resign.', <strong>'.$managing_director_name_resign_result[$g]["name"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$managing_director_name_resign_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Managing Director name - old}}</span>', $managing_director_name_resign, $str);

// 		                	if(count($previous_managing_director_name_resign_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_managing_director_name_resign_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_managing_director_name_resign_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Managing Director ID - old}}</span>') !== false)
// 		                {
// 		                	$managing_director_id_resign_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '6' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_managing_director_id_resign_result = $this->db->query("select client_officers.* from client_officers where position = '6' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$managing_director_id_resign_result = $managing_director_id_resign_result->result_array();

// 		                	$previous_managing_director_id_resign_result = $previous_managing_director_id_resign_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$managing_director_id_resign = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_managing_director_id_resign = (int)(count($managing_director_id_resign_result)) - 1;

// 		                	for($g = 0; $g < count($managing_director_id_resign_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$managing_director_id_resign = $managing_director_id_resign.'<strong>'.$managing_director_id_resign_result[$g]["identification_no"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_managing_director_id_resign)
// 		                		{
// 		                			$managing_director_id_resign = $managing_director_id_resign.' and <strong>'.$managing_director_id_resign_result[$g]["identification_no"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$managing_director_id_resign = $managing_director_id_resign.', <strong>'.$managing_director_id_resign_result[$g]["identification_no"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$managing_director_id_resign_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Managing Director ID - old}}</span>', $managing_director_id_resign, $str);

// 		                	if(count($previous_managing_director_id_resign_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_managing_director_id_resign_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_managing_director_id_resign_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Managing Director date of cessation}}</span>') !== false)
// 		                {
// 		                	$managing_director_cessation_resign_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '6' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_managing_director_cessation_resign_resultprevious_managing_director_cessation_resign_result = $this->db->query("select client_officers.* from client_officers where position = '6' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$managing_director_cessation_resign_result = $managing_director_cessation_resign_result->result_array();

// 		                	$previous_managing_director_cessation_resign_result = $previous_managing_director_cessation_resign_resultprevious_managing_director_cessation_resign_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$managing_director_cessation_resign = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_managing_director_cessation_resign = (int)(count($managing_director_cessation_resign_result)) - 1;

// 		                	for($g = 0; $g < count($managing_director_cessation_resign_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$managing_director_cessation_resign = $managing_director_cessation_resign.'<strong>'.$managing_director_cessation_resign_result[$g]["date_of_cessation"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_managing_director_cessation_resign)
// 		                		{
// 		                			$managing_director_cessation_resign = $managing_director_cessation_resign.' and <strong>'.$managing_director_cessation_resign_result[$g]["date_of_cessation"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$managing_director_cessation_resign = $managing_director_cessation_resign.', <strong>'.$managing_director_cessation_resign_result[$g]["date_of_cessation"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$managing_director_cessation_resign_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Managing Director date of cessation}}</span>', $managing_director_cessation_resign, $str);

// 		                	if(count($previous_managing_director_cessation_resign_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_managing_director_cessation_resign_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_managing_director_cessation_resign_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Managing Director name - new}}</span>') !== false)
// 		                {
// 		                	$managing_director_name_appointment_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '6' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_managing_director_name_appointment_result = $this->db->query("select client_officers.* from client_officers where position = '6' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$managing_director_name_appointment_result = $managing_director_name_appointment_result->result_array();

// 		                	$previous_managing_director_name_appointment_result = $previous_managing_director_name_appointment_result->result_array();
// 		                	//echo json_encode($ceo_name_appointment_result);

// 		                	$managing_director_name_appointment = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_managing_director_name_appointment = (int)(count($managing_director_name_appointment_result)) - 1;

// 		                	for($g = 0; $g < count($managing_director_name_appointment_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$managing_director_name_appointment = $managing_director_name_appointment.'<strong>'.$managing_director_name_appointment_result[$g]["name"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_managing_director_name_appointment)
// 		                		{
// 		                			$managing_director_name_appointment = $managing_director_name_appointment.' and <strong>'.$managing_director_name_appointment_result[$g]["name"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$managing_director_name_appointment = $managing_director_name_appointment.', <strong>'.$managing_director_name_appointment_result[$g]["name"].'</strong>';
// 		                		}
// 	                			array_push($latest_officer_id, (int)$managing_director_name_appointment_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Managing Director name - new}}</span>', $managing_director_name_appointment, $str);

// 		                	if(count($previous_managing_director_name_appointment_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_managing_director_name_appointment_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_managing_director_name_appointment_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Managing Director ID - new}}</span>') !== false)
// 		                {
// 		                	$managing_director_id_appointment_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '6' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_managing_director_id_appointment_result = $this->db->query("select client_officers.* from client_officers where position = '6' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$managing_director_id_appointment_result = $managing_director_id_appointment_result->result_array();

// 		                	$previous_managing_director_id_appointment_result = $previous_managing_director_id_appointment_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$managing_director_id_appointment = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_managing_director_id_appointment = (int)(count($managing_director_id_appointment_result)) - 1;

// 		                	for($g = 0; $g < count($managing_director_id_appointment_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$managing_director_id_appointment = $managing_director_id_appointment.'<strong>'.$managing_director_id_appointment_result[$g]["identification_no"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_managing_director_id_appointment)
// 		                		{
// 		                			$managing_director_id_appointment = $managing_director_id_appointment.' and <strong>'.$managing_director_id_appointment_result[$g]["identification_no"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$managing_director_id_appointment = $managing_director_id_appointment.', <strong>'.$managing_director_id_appointment_result[$g]["identification_no"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$managing_director_id_appointment_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Managing Director ID - new}}</span>', $managing_director_id_appointment, $str);

// 		                	if(count($previous_managing_director_id_appointment_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_managing_director_id_appointment_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_managing_director_id_appointment_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Managing Director date of appointment}}</span>') !== false)
// 		                {
// 		                	$managing_director_appointment_date_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '6' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_managing_director_appointment_date_result = $this->db->query("select client_officers.* from client_officers where position = '6' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$managing_director_appointment_date_result = $managing_director_appointment_date_result->result_array();

// 		                	$previous_managing_director_appointment_date_result = $previous_managing_director_appointment_date_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$managing_director_appointment_date = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_managing_director_appointment_date = (int)(count($managing_director_appointment_date_result)) - 1;

// 		                	for($g = 0; $g < count($managing_director_appointment_date_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$managing_director_appointment_date = $managing_director_appointment_date.'<strong>'.$managing_director_appointment_date_result[$g]["date_of_appointment"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_managing_director_appointment_date)
// 		                		{
// 		                			$managing_director_appointment_date = $managing_director_appointment_date.' and <strong>'.$managing_director_appointment_date_result[$g]["date_of_appointment"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$managing_director_appointment_date = $managing_director_appointment_date.', <strong>'.$managing_director_appointment_date_result[$g]["date_of_appointment"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$managing_director_appointment_date_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Managing Director date of appointment}}</span>', $managing_director_appointment_date, $str);

// 		                	if(count($previous_managing_director_appointment_date_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_managing_director_appointment_date_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_managing_director_appointment_date_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Managing Director name (current)}}</span>') !== false)
// 		                {
// 		                	$managing_director_name_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '6' AND company_code='".$company_code."' AND ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y') AND client_officers.date_of_cessation = '') OR ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) AND (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') > STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')) AND client_officers.date_of_cessation != '')) GROUP BY officer.identification_no, officer.name");
		                	
// 		                	$managing_director_name_result = $managing_director_name_result->result_array();

// 		                	//echo json_encode($director_name_result);
// 		                	//((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) OR (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') 

// 		                	$managing_director_name = "";

// 		                	for($g = 0; $g < count($managing_director_name_result); $g++)
// 		                	{
// 		                		$managing_director_name = $managing_director_name.'<p>&nbsp;</p>'.$managing_director_name_result[$g]["name"].' ____________________________<br>';
// 		                	}

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Managing Director name (current)}}</span>', $managing_director_name, $str);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Managing Director ID (current)}}</span>') !== false)
// 		                {
// 		                	$managing_director_id_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '6' AND company_code='".$company_code."' AND ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y') AND client_officers.date_of_cessation = '') OR ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) AND (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') > STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')) AND client_officers.date_of_cessation != '')) GROUP BY officer.identification_no, officer.name");

// 		                	$managing_director_id_result = $managing_director_id_result->result_array();

// 		                	$managing_director_id = "";

// 		                	for($g = 0; $g < count($managing_director_id_result); $g++)
// 		                	{
// 		                		$managing_director_id = $managing_director_id.'<p>&nbsp;</p>'.$managing_director_id_result[$g]["identification_no"].' ____________________________<br>';
// 		                	}

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Managing Director ID (current)}}</span>', $managing_director_id, $str);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Alternate director name - old}}</span>') !== false)
// 		                {

// 		                	$alternate_director_name_resign_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '7' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_alternate_director_name_resign_result = $this->db->query("select client_officers.* from client_officers where position = '7' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$alternate_director_name_resign_result = $alternate_director_name_resign_result->result_array();

// 		                	$previous_alternate_director_name_resign_result = $previous_alternate_director_name_resign_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$alternate_director_name_resign = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_alternate_director_name_resign = (int)(count($alternate_director_name_resign_result)) - 1;

// 		                	for($g = 0; $g < count($alternate_director_name_resign_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$alternate_director_name_resign = $alternate_director_name_resign.'<strong>'.$alternate_director_name_resign_result[$g]["name"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_alternate_director_name_resign)
// 		                		{
// 		                			$alternate_director_name_resign = $alternate_director_name_resign.' and <strong>'.$alternate_director_name_resign_result[$g]["name"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$alternate_director_name_resign = $alternate_director_name_resign.', <strong>'.$alternate_director_name_resign_result[$g]["name"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$alternate_director_name_resign_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Alternate director name - old}}</span>', $alternate_director_name_resign, $str);

// 		                	if(count($previous_alternate_director_name_resign_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_alternate_director_name_resign_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_alternate_director_name_resign_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Alternate director ID - old}}</span>') !== false)
// 		                {
// 		                	$alternate_director_id_resign_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '7' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_alternate_director_id_resign_result = $this->db->query("select client_officers.* from client_officers where position = '7' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$alternate_director_id_resign_result = $alternate_director_id_resign_result->result_array();

// 		                	$previous_alternate_director_id_resign_result = $previous_alternate_director_id_resign_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$alternate_director_id_resign = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_alternate_director_id_resign = (int)(count($alternate_director_id_resign_result)) - 1;

// 		                	for($g = 0; $g < count($alternate_director_id_resign_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$alternate_director_id_resign = $alternate_director_id_resign.'<strong>'.$alternate_director_id_resign_result[$g]["identification_no"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_alternate_director_id_resign)
// 		                		{
// 		                			$alternate_director_id_resign = $alternate_director_id_resign.' and <strong>'.$alternate_director_id_resign_result[$g]["identification_no"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$alternate_director_id_resign = $alternate_director_id_resign.', <strong>'.$alternate_director_id_resign_result[$g]["identification_no"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$alternate_director_id_resign_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Alternate director ID - old}}</span>', $alternate_director_id_resign, $str);

// 		                	if(count($previous_alternate_director_id_resign_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_alternate_director_id_resign_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_alternate_director_id_resign_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Alternate director cessation - old}}</span>') !== false)
// 		                {
// 		                	$alternate_director_cessation_resign_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '7' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_alternate_director_cessation_resign_result = $this->db->query("select client_officers.* from client_officers where position = '7' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$alternate_director_cessation_resign_result = $alternate_director_cessation_resign_result->result_array();

// 		                	$previous_alternate_director_cessation_resign_result = $previous_alternate_director_cessation_resign_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$alternate_director_cessation_resign = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_alternate_director_cessation_resign = (int)(count($alternate_director_cessation_resign_result)) - 1;

// 		                	for($g = 0; $g < count($alternate_director_cessation_resign_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$alternate_director_cessation_resign = $alternate_director_cessation_resign.'<strong>'.$alternate_director_cessation_resign_result[$g]["date_of_cessation"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_alternate_director_cessation_resign)
// 		                		{
// 		                			$alternate_director_cessation_resign = $alternate_director_cessation_resign.' and <strong>'.$alternate_director_cessation_resign_result[$g]["date_of_cessation"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$alternate_director_cessation_resign = $alternate_director_cessation_resign.', <strong>'.$alternate_director_cessation_resign_result[$g]["date_of_cessation"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$alternate_director_cessation_resign_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Alternate director cessation - old}}</span>', $alternate_director_cessation_resign, $str);

// 		                	if(count($previous_alternate_director_cessation_resign_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_alternate_director_cessation_resign_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_alternate_director_cessation_resign_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Alternate director name - new}}</span>') !== false)
// 		                {
// 		                	$alternate_director_name_appointment_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '7' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_alternate_director_name_appointment_result = $this->db->query("select client_officers.* from client_officers where position = '7' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$alternate_director_name_appointment_result = $alternate_director_name_appointment_result->result_array();

// 		                	$previous_alternate_director_name_appointment_result = $previous_alternate_director_name_appointment_result->result_array();
// 		                	//echo json_encode($ceo_name_appointment_result);

// 		                	$alternate_director_name_appointment = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_alternate_director_name_appointment = (int)(count($alternate_director_name_appointment_result)) - 1;

// 		                	for($g = 0; $g < count($alternate_director_name_appointment_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$alternate_director_name_appointment = $alternate_director_name_appointment.'<strong>'.$alternate_director_name_appointment_result[$g]["name"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_alternate_director_name_appointment)
// 		                		{
// 		                			$alternate_director_name_appointment = $alternate_director_name_appointment.' and <strong>'.$alternate_director_name_appointment_result[$g]["name"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$alternate_director_name_appointment = $alternate_director_name_appointment.', <strong>'.$alternate_director_name_appointment_result[$g]["name"].'</strong>';
// 		                		}
// 	                			array_push($latest_officer_id, (int)$alternate_director_name_appointment_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Alternate director name - new}}</span>', $alternate_director_name_appointment, $str);

// 		                	if(count($previous_alternate_director_name_appointment_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_alternate_director_name_appointment_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_alternate_director_name_appointment_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Alternate director ID - new}}</span>') !== false)
// 		                {
// 		                	$alternate_director_id_appointment_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '7' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_alternate_director_id_appointment_result = $this->db->query("select client_officers.* from client_officers where position = '7' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$alternate_director_id_appointment_result = $alternate_director_id_appointment_result->result_array();

// 		                	$previous_alternate_director_id_appointment_result = $previous_alternate_director_id_appointment_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$alternate_director_id_appointment = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_alternate_director_id_appointment = (int)(count($alternate_director_id_appointment_result)) - 1;

// 		                	for($g = 0; $g < count($alternate_director_id_appointment_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$alternate_director_id_appointment = $alternate_director_id_appointment.'<strong>'.$alternate_director_id_appointment_result[$g]["identification_no"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_alternate_director_id_appointment)
// 		                		{
// 		                			$alternate_director_id_appointment = $alternate_director_id_appointment.' and <strong>'.$alternate_director_id_appointment_result[$g]["identification_no"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$alternate_director_id_appointment = $alternate_director_id_appointment.', <strong>'.$alternate_director_id_appointment_result[$g]["identification_no"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$alternate_director_id_appointment_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Alternate director ID - new}}</span>', $alternate_director_id_appointment, $str);

// 		                	if(count($previous_alternate_director_id_appointment_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_alternate_director_id_appointment_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_alternate_director_id_appointment_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Alternate director appointment - new}}</span>') !== false)
// 		                {
// 		                	$alternate_director_appointment_date_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '7' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') GROUP BY officer.identification_no, officer.name ORDER BY client_officers.id");

// 		                	$previous_alternate_director_appointment_date_result = $this->db->query("select client_officers.* from client_officers where position = '7' AND company_code='".$company_code."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$officer_id."' ORDER BY client_officers.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$alternate_director_appointment_date_result = $alternate_director_appointment_date_result->result_array();

// 		                	$previous_alternate_director_appointment_date_result = $previous_alternate_director_appointment_date_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$alternate_director_appointment_date = "";

// 		                	$latest_officer_id = array();
// 		                	$num_of_alternate_director_appointment_date = (int)(count($alternate_director_appointment_date_result)) - 1;

// 		                	for($g = 0; $g < count($alternate_director_appointment_date_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$alternate_director_appointment_date = $alternate_director_appointment_date.'<strong>'.$alternate_director_appointment_date_result[$g]["date_of_appointment"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_alternate_director_appointment_date)
// 		                		{
// 		                			$alternate_director_appointment_date = $alternate_director_appointment_date.' and <strong>'.$alternate_director_appointment_date_result[$g]["date_of_appointment"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$alternate_director_appointment_date = $alternate_director_appointment_date.', <strong>'.$alternate_director_appointment_date_result[$g]["date_of_appointment"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_officer_id, (int)$alternate_director_appointment_date_result[$g]["id"]);
// 		                	}

// 		                	$data['officer_id']=json_encode($latest_officer_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Alternate director appointment - new}}</span>', $alternate_director_appointment_date, $str);

// 		                	if(count($previous_alternate_director_appointment_date_result) != 0)
// 		                	{
// 		                		$officer_id_data = array();
// 		                		for($f = 0; $f < count($previous_alternate_director_appointment_date_result); $f++)
// 			                	{
// 			                		array_push($officer_id_data, (int)$previous_alternate_director_appointment_date_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Alternated directors name}}</span>') !== false)
// 		                {
// 		                	/*$ceo_name_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '7' AND company_code='".$company_code."' AND ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y') AND client_officers.date_of_cessation = '') OR ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) AND (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') > STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')) AND client_officers.date_of_cessation != '')) GROUP BY officer.identification_no, officer.name");*/

// 		                	$alternated_directors_name_result = $this->db->query("select r.*, officer.identification_no, officer.name from client_officers as r left join client_officers as t on t.id = r.alternate_of left join officer on t.officer_id = officer.id and t.field_type = officer.field_type where r.position = '7' AND r.company_code='".$company_code."' AND ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(r.date_of_appointment,'%d/%m/%Y') AND r.date_of_cessation = '') OR ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(r.date_of_appointment,'%d/%m/%Y')) AND (STR_TO_DATE(r.date_of_cessation,'%d/%m/%Y') > STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')) AND r.date_of_cessation != '')) GROUP BY officer.identification_no, officer.name");
		                	
// 		                	$alternated_directors_name_result = $alternated_directors_name_result->result_array();

// 		                	//echo json_encode($director_name_result);
// 		                	//((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) OR (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') 

// 		                	$alternated_directors_name = "";

// 		                	for($g = 0; $g < count($alternated_directors_name_result); $g++)
// 		                	{
// 		                		$alternated_directors_name = $alternated_directors_name.'<p>&nbsp;</p>'.$alternated_directors_name_result[$g]["name"].' ____________________________<br>';
// 		                	}

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Alternated directors name}}</span>', $alternated_directors_name, $str);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Alternated directors ID}}</span>') !== false)
// 		                {
// 		                	$alternated_directors_id_result = $this->db->query("select r.*, officer.identification_no, officer.name from client_officers as r left join client_officers as t on t.id = r.alternate_of left join officer on t.officer_id = officer.id and t.field_type = officer.field_type where r.position = '7' AND r.company_code='".$company_code."' AND ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(r.date_of_appointment,'%d/%m/%Y') AND r.date_of_cessation = '') OR ((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(r.date_of_appointment,'%d/%m/%Y')) AND (STR_TO_DATE(r.date_of_cessation,'%d/%m/%Y') > STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')) AND r.date_of_cessation != '')) GROUP BY officer.identification_no, officer.name");

// 		                	$alternated_directors_id_result = $alternated_directors_id_result->result_array();

// 		                	$alternated_directors_id = "";

// 		                	for($g = 0; $g < count($alternated_directors_id_result); $g++)
// 		                	{
// 		                		$alternated_directors_id = $alternated_directors_id.'<p>&nbsp;</p>'.$alternated_directors_id_result[$g]["identification_no"].' ____________________________<br>';
// 		                	}

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Alternated directors ID}}</span>', $alternated_directors_id, $str);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Members name - all}}</span>') !== false)
// 		                {
// 		                	$member_name_result = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id HAVING sum(member_shares.number_of_share) != 0');
		                	
// 		                	$member_name_result = $member_name_result->result_array();


// 		                	$member_name = '<table style="width: 100%; border-collapse: collapse;"><tbody>';

// 		                	for($g = 0; $g < count($member_name_result); $g++)
// 		                	{
// 		                		if($member_name_result[$g]["name"] != null)
// 		                		{
// 		                			$member_name = $member_name.'<tr style="height: 89px;"><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p>'.$member_name_result[$g]["name"].'</p></td><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p> _______________________________</p></td></tr>';
// 		                		}
// 		                		elseif($member_name_result[$g]["company_name"] != null)
// 		                		{
// 		                			$member_name = $member_name.'<tr style="height: 89px;"><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p>'.$member_name_result[$g]["company_name"].'</p></td><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p> _______________________________</p></td></tr>';
// 		                		}
// 		                	}

// 		                	$member_name = $member_name.'</tbody></table>';

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Members name - all}}</span>', $member_name, $str);
// 		                }

// 		                if($client_member_share_capital_id != null)
// 		                {
// 		                	$client_member_share_capital_id_info = $this->db->query("select client_member_share_capital.*, member_shares.company_code, sum(member_shares.number_of_share) as number_of_shares, sum(member_shares.amount_share) as amount, sum(member_shares.no_of_share_paid) as number_of_shares_paid, sum(member_shares.amount_paid) as paid_up, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name from client_member_share_capital left join member_shares on member_shares.client_member_share_capital_id = client_member_share_capital.id AND member_shares.company_code = client_member_share_capital.company_code left join sharetype on sharetype.id = client_member_share_capital.class_id left join currency on currency.id = client_member_share_capital.currency_id where client_member_share_capital.company_code = '".$company_code."' group by client_member_share_capital.id");

// 		                	$client_member_share_capital_id_info = $client_member_share_capital_id_info->result_array();

// 		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Class of shares - all}}</span>') !== false)
// 		                	{
// 	                			if($client_member_share_capital_id_info[0]["sharetype_id"] == '1')
// 	                			{
// 	                				$str = str_replace('<span class="myclass mceNonEditable">{{Class of shares - all}}</span>', $client_member_share_capital_id_info[0]["sharetype_name"], $str);

// 	                			}
// 	                			elseif($client_member_share_capital_id_info[$g]["sharetype_id"] == '2')
// 	                			{
// 	                				$str = str_replace('<span class="myclass mceNonEditable">{{Class of shares - all}}</span>', $client_member_share_capital_id_info[0]["other_class"], $str);
// 	                			}
// 		                	}

// 		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Currency of shares - all}}</span>') !== false)
// 		                	{
// 		                		$str = str_replace('<span class="myclass mceNonEditable">{{Currency of shares - all}}</span>', $client_member_share_capital_id_info[0]["currency_name"], $str);
// 		                	}

// 		                	if(strpos($str, '<span class="myclass mceNonEditable">{{No of shares issued - all}}</span>') !== false)
// 		                	{
// 		                		$str = str_replace('<span class="myclass mceNonEditable">{{No of shares issued - all}}</span>', number_format($client_member_share_capital_id_info[0]["number_of_shares"], 2), $str);
// 		                	}

// 		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Amount of shares issued - all}}</span>') !== false)
// 		                	{
// 		                		$str = str_replace('<span class="myclass mceNonEditable">{{Amount of shares issued - all}}</span>', number_format($client_member_share_capital_id_info[0]["amount"], 2), $str);
// 		                	}

// 		                	if(strpos($str, '<span class="myclass mceNonEditable">{{No of shares paid up - all}}</span>') !== false)
// 		                	{
// 		                		$str = str_replace('<span class="myclass mceNonEditable">{{No of shares paid up - all}}</span>', number_format($client_member_share_capital_id_info[0]["number_of_shares_paid"], 2), $str);
// 		                	}

// 		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Amount of shares paid up - all}}</span>') !== false)
// 		                	{
// 		                		$str = str_replace('<span class="myclass mceNonEditable">{{Amount of shares paid up - all}}</span>', number_format($client_member_share_capital_id_info[0]["paid_up"], 2), $str);
// 		                	}
// 		                }

// 		                if($type == "add_allotment_of_share")
// 		                {
// 		                	$allotment_total_of_share_all_result = $this->db->query("select member_shares.*, sum(member_shares.number_of_share) as total_number_of_share, sum(member_shares.amount_share) as total_amount_of_share, client_member_share_capital.other_class, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name from member_shares left join client_member_share_capital on client_member_share_capital.id = member_shares.client_member_share_capital_id left join sharetype on sharetype.id = client_member_share_capital.class_id left join currency on currency.id = client_member_share_capital.currency_id where member_shares.company_code='".$company_code."' AND member_shares.client_member_share_capital_id='".$client_member_share_capital_id."' AND member_shares.transaction_type = 'Allotment' AND STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY member_shares.id");

// 		                	$allotment_member_result = $this->db->query("select member_shares.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_member_share_capital.other_class, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name, certificate.certificate_no, certificate.new_certificate_no from member_shares left join certificate on certificate.officer_id = member_shares.officer_id and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id and certificate.client_member_share_capital_id = member_shares.client_member_share_capital_id left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital on client_member_share_capital.id = member_shares.client_member_share_capital_id left join sharetype on sharetype.id = client_member_share_capital.class_id left join currency on currency.id = client_member_share_capital.currency_id where member_shares.company_code='".$company_code."' AND member_shares.client_member_share_capital_id='".$client_member_share_capital_id."' AND member_shares.transaction_type='Allotment' AND STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY member_shares.id");

		                
// 		                	$this->db->select('member_shares.*')
// 		                	         ->order_by('member_shares.id');
// 							$this->db->where("company_code", $company_code);
// 							$this->db->where("STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')");
// 							$this->db->where("member_shares.client_member_share_capital_id", $client_member_share_capital_id);
// 							$this->db->where("member_shares.transaction_type = 'Allotment'");
// 							$this->db->where_not_in('id', $allotment_id);

// 							$previous_allotment_member_result = $this->db->get('member_shares');

// 							$allotment_total_of_share_all_result = $allotment_total_of_share_all_result->result_array();
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$allotment_member_result = $allotment_member_result->result_array();

// 		                	$previous_allotment_member_result = $previous_allotment_member_result->result_array();
// 		                	//echo json_encode($director_name_result);
// 		                	//if($transferor_member_result[])
		                	
// 		                	/*if(strpos($str, '<span class="myclass mceNonEditable">{{Transferor - name}}</span>') !== false)
// 				            {
// 		                		$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - name}}</span>', $get_client[0]["company_type_name"], $str);
// 		                	}*/
		                	
// 	    					//$final_message = str_replace($final_name,$html,$final_message);
// 		                	//echo json_encode($m[0][0]);
// 							//echo json_encode('<p><span class="myclass mceNonEditable">{{Allotment - members}}</span>'.$m[1].'</p>');
// 		                	/*$transferor_member = "";
// 		                	$transferee_member = "";*/
// 		                	$allotment_member = "";
// 		                	$allotment_string = "";
// 		                	$latest_allotment_id = array();
// 		                	$num_of_allotment_member = (int)(count($allotment_member_result)) - 1;

// 		                	if(strpos($str, '<tr class="loop"') !== false)
// 		                	{
// 		                		preg_match_all ('/<tr class="loop"(.+?)<\/tr>/s', $str, $m);
		                	
// 			                	for($g = 0; $g < count($allotment_member_result); $g++)
// 			                	{

// 		                			$allotment_string = $m[0][0];
// 		                			//echo json_encode($m);
// 			                		if(strpos($allotment_string, '<span class="myclass mceNonEditable">{{Allotment - members}}</span>') !== false)
// 				                	{
// 				                		if($allotment_member_result[$g]["name"] != '')
// 			                			{
// 			                				$allotment_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - members}}</span>', $allotment_member_result[$g]["name"], $allotment_string);
			                				
// 			                			}
// 			                			elseif($allotment_member_result[$g]["company_name"] != '')
// 			                			{
// 			                				$allotment_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - members}}</span>', $allotment_member_result[$g]["company_name"], $allotment_string);
// 			                			}
// 			                		}

// 			                		if(strpos($allotment_string, '<span class="myclass mceNonEditable">{{Allotment - number of shares}}</span>') !== false)
// 			                		{
// 			                			$allotment_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - number of shares}}</span>', number_format($allotment_member_result[$g]["number_of_share"], 2), $allotment_string);
// 			                		}

// 			                		if(strpos($allotment_string, '<span class="myclass mceNonEditable">{{Allotment - amount of shares}}</span>') !== false)
// 			                		{
// 			                			$allotment_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - amount of shares}}</span>', number_format($allotment_member_result[$g]["amount_share"], 2), $allotment_string);
// 			                		}

// 			                		if(strpos($allotment_string, '<span class="myclass mceNonEditable">{{Allotment - type of shares}}</span>') !== false)
// 			                		{
// 			                			if($allotment_member_result[$g]["sharetype_id"] == '1')
// 			                			{
// 			                				$allotment_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - type of shares}}</span>', $allotment_member_result[$g]["sharetype_name"], $allotment_string);

// 			                			}
// 			                			elseif($allotment_member_result[$g]["sharetype_id"] == '2')
// 			                			{
// 			                				$allotment_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - type of shares}}</span>', $allotment_member_result[$g]["other_class"], $allotment_string);
// 			                			}
// 			                		}

// 			                		if(strpos($allotment_string, '<span class="myclass mceNonEditable">{{Allotment - currency}}</span>') !== false)
// 			                		{
// 			                			$allotment_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - currency}}</span>', $allotment_member_result[$g]["currency_name"], $allotment_string);
// 			                		}

// 			                		if(strpos($allotment_string, '<span class="myclass mceNonEditable">{{Allotment - certificate}}</span>') !== false)
// 			                		{
// 			                			if($allotment_member_result[$g]["new_certificate_no"] != '')
// 			                			{
// 			                				$allotment_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - certificate}}</span>', $allotment_member_result[$g]["new_certificate_no"], $allotment_string);
// 			                			}
// 			                			else
// 			                			{
// 			                				$allotment_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - certificate}}</span>', $allotment_member_result[$g]["certificate_no"], $allotment_string);
// 			                			}
			                			
// 			                		}

// 			                		$allotment_member = $allotment_member.$allotment_string;
			                		
				            		
// 			                	}


// 		                		$str = str_replace($m[0][0], $allotment_member, $str);
// 			                }
			                
			               	
// 			               	for($g = 0; $g < count($allotment_member_result); $g++)
// 			                {

// 			                	if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - members}}</span>') !== false)
// 			                	{
// 			                		if($allotment_member_result[$g]["name"] != '')
// 		                			{
// 		                				$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - members}}</span>', $allotment_member_result[$g]["name"], $str);
		                				
// 		                			}
// 		                			elseif($allotment_member_result[$g]["company_name"] != '')
// 		                			{
// 		                				$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - members}}</span>', $allotment_member_result[$g]["company_name"], $str);
// 		                			}
// 		                		}

// 		                		if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - number of shares}}</span>') !== false)
// 		                		{
// 		                			if($result[$r]["document_name"] == "DRIW-Allotment of Shares")
// 		                			{
// 		                				$doc_total_number_of_share = 0;
// 		                				for($y = 0; $y < count($allotment_member_result); $y++)
// 			                			{
// 			                				$doc_total_number_of_share = $doc_total_number_of_share + $allotment_member_result[$y]["number_of_share"];
// 			                			}
// 			                			$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - number of shares}}</span>', number_format($doc_total_number_of_share, 2), $str);
// 		                			}
// 		                			else
// 		                			{
// 		                				$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - number of shares}}</span>', number_format($allotment_member_result[$g]["number_of_share"], 2), $str);
// 		                			}
// 		                		}

// 		                		if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - amount of shares}}</span>') !== false)
// 		                		{
// 		                			$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - amount of shares}}</span>', number_format($allotment_member_result[$g]["amount_share"], 2), $str);
// 		                		}

// 		                		if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - per shared}}</span>') !== false)
// 		                		{
// 		                			if($result[$r]["document_name"] == "DRIW-Allotment of Shares")
// 		                			{
// 		                				$doc_amount_share = 0;
// 		                				$doc_number_of_share = 0;
// 		                				for($y = 0; $y < count($allotment_member_result); $y++)
// 			                			{
// 			                				$doc_amount_share = $doc_amount_share + $allotment_member_result[$y]["amount_share"];
// 			                				$doc_number_of_share = $doc_number_of_share + $allotment_member_result[$y]["number_of_share"];
// 			                			}

// 			                			$per_shared = $doc_amount_share / $doc_number_of_share;

// 		                				$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - per shared}}</span>', number_format($per_shared, 2), $str);
// 		                			}
// 		                			else
// 		                			{
// 		                				$per_shared = $allotment_member_result[$g]["amount_share"] / $allotment_member_result[$g]["number_of_share"];

// 		                				$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - per shared}}</span>', number_format($per_shared, 2), $str);
// 		                			}
		                			
// 		                		}


// 		                		if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - type of shares}}</span>') !== false)
// 		                		{
// 		                			if($allotment_member_result[$g]["sharetype_id"] == '1')
// 		                			{
// 		                				$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - type of shares}}</span>', $allotment_member_result[$g]["sharetype_name"], $str);

// 		                			}
// 		                			elseif($allotment_member_result[$g]["sharetype_id"] == '2')
// 		                			{
// 		                				$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - type of shares}}</span>', $allotment_member_result[$g]["other_class"], $str);
// 		                			}
// 		                		}

// 		                		if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - currency}}</span>') !== false)
// 		                		{
// 		                			$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - currency}}</span>', $allotment_member_result[$g]["currency_name"], $str);
// 		                		}

// 		                		if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - certificate}}</span>') !== false)
// 		                		{
// 		                			if($allotment_member_result[$g]["new_certificate_no"] != '')
// 		                			{
// 		                				$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - certificate}}</span>', $allotment_member_result[$g]["new_certificate_no"], $str);
// 		                			}
// 		                			else
// 		                			{
// 		                				$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - certificate}}</span>', $allotment_member_result[$g]["certificate_no"], $str);
// 		                			}
		                			
// 		                		}

			                		
			                	
// 			                }

// 			                if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - number of shares all}}</span>') !== false)
// 		                	{
// 		                		$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - number of shares all}}</span>', number_format($allotment_total_of_share_all_result[0]["total_number_of_share"], 2), $str);
// 		                	}

// 		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - amount of shares all}}</span>') !== false)
// 		                	{
// 		                		$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - amount of shares all}}</span>', number_format($allotment_total_of_share_all_result[0]["total_amount_of_share"], 2), $str);
// 		                	}

// 		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - type of shares all}}</span>') !== false)
// 		                	{
// 		                		if($allotment_total_of_share_all_result[0]["sharetype_id"] == '1')
// 	                			{
// 	                				$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - type of shares all}}</span>', $allotment_total_of_share_all_result[0]["sharetype_name"], $str);

// 	                			}
// 	                			elseif($allotment_total_of_share_all_result[0]["sharetype_id"] == '2')
// 	                			{
// 	                				$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - type of shares all}}</span>', $allotment_total_of_share_all_result[0]["other_class"], $str);
// 	                			}
// 		                	}

// 		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - currency all}}</span>') !== false)
// 	                		{
// 	                			$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - currency all}}</span>', $allotment_total_of_share_all_result[0]["currency_name"], $str);
// 	                		}
			                

// 			                for($h = 0; $h < count($allotment_member_result); $h++)
// 			                {
// 			                	array_push($latest_allotment_id, (int)$allotment_member_result[$h]["id"]);
// 			                }

// 		                	$data['allotment_id']=json_encode($latest_allotment_id);

		                	

// 		                	//$str = str_replace($r[0][0], $transferee_member, $str);

// 		                	if(count($previous_allotment_member_result) != 0)
// 		                	{
// 		                		$allotment_id_data = array();
// 		                		for($f = 0; $f < count($previous_allotment_member_result); $f++)
// 			                	{
// 			                		array_push($allotment_id_data, (int)$previous_allotment_member_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

		                
// 		                if($type == "add_buyback_of_share")
// 		                {
// 		                	$buyback_total_of_share_all_result = $this->db->query("select member_shares.*, sum(member_shares.number_of_share) as total_number_of_share, sum(member_shares.amount_share) as total_amount_of_share, client_member_share_capital.other_class, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name from member_shares left join client_member_share_capital on client_member_share_capital.id = member_shares.client_member_share_capital_id left join sharetype on sharetype.id = client_member_share_capital.class_id left join currency on currency.id = client_member_share_capital.currency_id where member_shares.company_code='".$company_code."' AND member_shares.client_member_share_capital_id='".$client_member_share_capital_id."' AND member_shares.transaction_type = 'Buyback' AND STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY member_shares.id");

// 		                	$buyback_member_result = $this->db->query("select member_shares.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_member_share_capital.other_class, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name, certificate.certificate_no, certificate.new_certificate_no from member_shares left join certificate on certificate.officer_id = member_shares.officer_id and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id and certificate.client_member_share_capital_id = member_shares.client_member_share_capital_id left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital on client_member_share_capital.id = member_shares.client_member_share_capital_id left join sharetype on sharetype.id = client_member_share_capital.class_id left join currency on currency.id = client_member_share_capital.currency_id where member_shares.company_code='".$company_code."' AND member_shares.client_member_share_capital_id='".$client_member_share_capital_id."' AND member_shares.transaction_type='Buyback' AND STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY member_shares.id");

		                
// 		                	$this->db->select('member_shares.*')
// 		                	         ->order_by('member_shares.id');
// 							$this->db->where("company_code", $company_code);
// 							$this->db->where("STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')");
// 							$this->db->where("member_shares.client_member_share_capital_id", $client_member_share_capital_id);
// 							$this->db->where("member_shares.transaction_type = 'Buyback'");
// 							$this->db->where_in('id', $buyback_id);

// 							$previous_buyback_member_result = $this->db->get('member_shares');

// 							$buyback_total_of_share_all_result = $buyback_total_of_share_all_result->result_array();
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$buyback_member_result = $buyback_member_result->result_array();

// 		                	$previous_buyback_member_result = $previous_buyback_member_result->result_array();

// 		                	$buyback_member = "";
// 		                	$buyback_string = "";
// 		                	$latest_buyback_id = array();
// 		                	$num_of_buyback_member = (int)(count($buyback_member_result)) - 1;

// 		                	if(strpos($str, '<tr class="loop"') !== false)
// 		                	{
// 		                		preg_match_all ('/<tr class="loop"(.+?)<\/tr>/s', $str, $m);
		                	
// 			                	for($g = 0; $g < count($buyback_member_result); $g++)
// 			                	{

// 			                			$buyback_string = $m[0][0];
// 			                			//echo json_encode($m);
// 				                		if(strpos($buyback_string, '<span class="myclass mceNonEditable">{{Buyback - members}}</span>') !== false)
// 					                	{
// 					                		if($buyback_member_result[$g]["name"] != '')
// 				                			{
// 				                				$buyback_string = str_replace('<span class="myclass mceNonEditable">{{Buyback - members}}</span>', $buyback_member_result[$g]["name"], $buyback_string);
				                				
// 				                			}
// 				                			elseif($buyback_member_result[$g]["company_name"] != '')
// 				                			{
// 				                				$buyback_string = str_replace('<span class="myclass mceNonEditable">{{Buyback - members}}</span>', $buyback_member_result[$g]["company_name"], $buyback_string);
// 				                			}
// 				                		}

// 				                		if(strpos($buyback_string, '<span class="myclass mceNonEditable">{{Buyback - number of shares}}</span>') !== false)
// 				                		{
// 				                			$buyback_string = str_replace('<span class="myclass mceNonEditable">{{Buyback - number of shares}}</span>', number_format(-($buyback_member_result[$g]["number_of_share"]), 2), $buyback_string);
// 				                		}

// 				                		if(strpos($buyback_string, '<span class="myclass mceNonEditable">{{Buyback - amount of shares}}</span>') !== false)
// 				                		{
// 				                			$buyback_string = str_replace('<span class="myclass mceNonEditable">{{Buyback - amount of shares}}</span>', number_format(-($buyback_member_result[$g]["amount_share"]), 2), $buyback_string);
// 				                		}

// 				                		if(strpos($buyback_string, '<span class="myclass mceNonEditable">{{Buyback - type of shares}}</span>') !== false)
// 				                		{
// 				                			if($buyback_member_result[$g]["sharetype_id"] == '1')
// 				                			{
// 				                				$buyback_string = str_replace('<span class="myclass mceNonEditable">{{Buyback - type of shares}}</span>', $buyback_member_result[$g]["sharetype_name"], $buyback_string);

// 				                			}
// 				                			elseif($buyback_member_result[$g]["sharetype_id"] == '2')
// 				                			{
// 				                				$buyback_string = str_replace('<span class="myclass mceNonEditable">{{Buyback - type of shares}}</span>', $buyback_member_result[$g]["other_class"], $buyback_string);
// 				                			}
// 				                		}

// 				                		if(strpos($buyback_string, '<span class="myclass mceNonEditable">{{Buyback - currency}}</span>') !== false)
// 				                		{
// 				                			$buyback_string = str_replace('<span class="myclass mceNonEditable">{{Buyback - currency}}</span>', $buyback_member_result[$g]["currency_name"], $buyback_string);
// 				                		}

// 				                		if(strpos($buyback_string, '<span class="myclass mceNonEditable">{{Buyback - certificate}}</span>') !== false)
// 				                		{
// 				                			if($buyback_member_result[$g]["new_certificate_no"] != '')
// 				                			{
// 				                				$buyback_string = str_replace('<span class="myclass mceNonEditable">{{Buyback - certificate}}</span>', $buyback_member_result[$g]["new_certificate_no"], $buyback_string);
// 				                			}
// 				                			else
// 				                			{
// 				                				$buyback_string = str_replace('<span class="myclass mceNonEditable">{{Buyback - certificate}}</span>', $buyback_member_result[$g]["certificate_no"], $buyback_string);
// 				                			}
				                			
// 				                		}

// 				                		$buyback_member = $buyback_member.$buyback_string;

// 			                	}


// 		                		$str = str_replace($m[0][0], $buyback_member, $str);
// 			                }
			                
			               	
// 			               	for($g = 0; $g < count($buyback_member_result); $g++)
// 			                {

// 				                	if(strpos($str, '<span class="myclass mceNonEditable">{{Buyback - members}}</span>') !== false)
// 				                	{
// 				                		if($buyback_member_result[$g]["name"] != '')
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - members}}</span>', $buyback_member_result[$g]["name"], $str);
			                				
// 			                			}
// 			                			elseif($buyback_member_result[$g]["company_name"] != '')
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - members}}</span>', $buyback_member_result[$g]["company_name"], $str);
// 			                			}
// 			                		}


// 			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Buyback - number of shares}}</span>') !== false)
// 			                		{
// 			                			$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - number of shares}}</span>', number_format(-($buyback_member_result[$g]["number_of_share"]), 2), $str);
// 			                		}

// 			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Buyback - amount of shares}}</span>') !== false)
// 			                		{
// 			                			$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - amount of shares}}</span>', number_format(-($buyback_member_result[$g]["amount_share"]), 2), $str);
// 			                		}

// 			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Buyback - type of shares}}</span>') !== false)
// 			                		{
// 			                			if($buyback_member_result[$g]["sharetype_id"] == '1')
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - type of shares}}</span>', $buyback_member_result[$g]["sharetype_name"], $str);

// 			                			}
// 			                			elseif($buyback_member_result[$g]["sharetype_id"] == '2')
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - type of shares}}</span>', $buyback_member_result[$g]["other_class"], $str);
// 			                			}
// 			                		}

// 			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Buyback - currency}}</span>') !== false)
// 			                		{
// 			                			$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - currency}}</span>', $buyback_member_result[$g]["currency_name"], $str);
// 			                		}

// 			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Buyback - certificate}}</span>') !== false)
// 			                		{
// 			                			if($buyback_member_result[$g]["new_certificate_no"] != '')
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - certificate}}</span>', $buyback_member_result[$g]["new_certificate_no"], $str);
// 			                			}
// 			                			else
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - certificate}}</span>', $buyback_member_result[$g]["certificate_no"], $str);
// 			                			}
			                			
// 			                		}

			                	
// 			                }

// 			                if(strpos($str, '<span class="myclass mceNonEditable">{{Buyback - number of shares all}}</span>') !== false)
// 		                	{
// 		                		$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - number of shares all}}</span>', number_format(-($buyback_total_of_share_all_result[0]["total_number_of_share"]), 2), $str);
// 		                	}

// 		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Buyback - amount of shares all}}</span>') !== false)
// 		                	{
// 		                		$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - amount of shares all}}</span>', number_format(-($buyback_total_of_share_all_result[0]["total_amount_of_share"]), 2), $str);
// 		                	}

// 		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Buyback - type of shares all}}</span>') !== false)
// 		                	{
// 		                		if($buyback_total_of_share_all_result[0]["sharetype_id"] == '1')
// 	                			{
// 	                				$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - type of shares all}}</span>', $buyback_total_of_share_all_result[0]["sharetype_name"], $str);

// 	                			}
// 	                			elseif($buyback_total_of_share_all_result[0]["sharetype_id"] == '2')
// 	                			{
// 	                				$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - type of shares all}}</span>', $buyback_total_of_share_all_result[0]["other_class"], $str);
// 	                			}
// 		                	}

// 		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Buyback - currency all}}</span>') !== false)
// 	                		{
// 	                			$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - currency all}}</span>', $buyback_total_of_share_all_result[0]["currency_name"], $str);
// 	                		}
			                

// 			                for($h = 0; $h < count($buyback_member_result); $h++)
// 			                {
// 			                	array_push($latest_buyback_id, (int)$buyback_member_result[$h]["id"]);
// 			                }

// 		                	$data['buyback_id']=json_encode($latest_buyback_id);

		                	

// 		                	//$str = str_replace($r[0][0], $transferee_member, $str);

// 		                	if(count($previous_buyback_member_result) != 0)
// 		                	{
// 		                		$transfer_id_data = array();
// 		                		for($f = 0; $f < count($previous_buyback_member_result); $f++)
// 			                	{
// 			                		array_push($transfer_id_data, (int)$previous_buyback_member_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if($type == "add_transfer_of_share")
// 		                {
// 		                	$transfer_member_result = $this->db->query("select member_shares.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_member_share_capital.other_class, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name, certificate.certificate_no, certificate.new_certificate_no from member_shares left join certificate on certificate.officer_id = member_shares.officer_id and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id and certificate.client_member_share_capital_id = member_shares.client_member_share_capital_id left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital on client_member_share_capital.id = member_shares.client_member_share_capital_id left join sharetype on sharetype.id = client_member_share_capital.class_id left join currency on currency.id = client_member_share_capital.currency_id where member_shares.company_code='".$company_code."' AND member_shares.client_member_share_capital_id='".$client_member_share_capital_id."' AND member_shares.transaction_type='Transfer' AND STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY member_shares.id");

		                

// 		                	$this->db->select('member_shares.*')
// 		                	         ->order_by('member_shares.id');
// 							$this->db->where("company_code", $company_code);
// 							$this->db->where("STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')");
// 							$this->db->where("member_shares.client_member_share_capital_id", $client_member_share_capital_id);
// 							$this->db->where("member_shares.transaction_type = 'Transfer'");
// 							$this->db->where_in('id', $transfer_id);
// 							$previous_transfer_member_result = $this->db->get('member_shares');

// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$transfer_member_result = $transfer_member_result->result_array();

// 		                	$previous_transfer_member_result = $previous_transfer_member_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$transferor_member = "";
// 		                	$transferor_string = "";
// 		                	$latest_transfer_id = array();
// 		                	$num_of_transferor_member = (int)(count($transfer_member_result)) - 1;

// 		                	if(strpos($str, '<tr class="loop"') !== false)
// 		                	{
// 		                		preg_match_all ('/<tr class="loop"(.+?)<\/tr>/s', $str, $m);
		                	
// 			                	for($g = 0; $g < count($transfer_member_result); $g++)
// 			                	{
// 			                		if($g%2==0)
// 								    {
// 								     	$transferor_string = $transferor_string.$m[0][0];
// 								    }
// 								   	else
// 								   	{
// 								   		$transferor_string = $transferor_string;
// 								   	}

// 				                	if(0 > $transfer_member_result[$g]["number_of_share"])
// 			                		{
// 				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - name}}</span>') !== false)
// 					                	{
// 					                		if($transfer_member_result[$g]["name"] != '')
// 				                			{
// 				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - name}}</span>', $transfer_member_result[$g]["name"], $transferor_string);
				                				
// 				                			}
// 				                			elseif($transfer_member_result[$g]["company_name"] != '')
// 				                			{
// 				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - name}}</span>', $transfer_member_result[$g]["company_name"], $transferor_string);
// 				                			}
// 				                		}

// 				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - ID}}</span>') !== false)
// 					                	{
// 					                		if($transfer_member_result[$g]["identification_no"] != '')
// 				                			{
// 				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - ID}}</span>', $transfer_member_result[$g]["identification_no"], $transferor_string);
				                				
// 				                			}
// 				                			elseif($transfer_member_result[$g]["register_no"] != '')
// 				                			{
// 				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - ID}}</span>', $transfer_member_result[$g]["register_no"], $transferor_string);
// 				                			}
// 				                		}

// 				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - share number}}</span>') !== false)
// 				                		{
// 				                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - share number}}</span>', number_format(-($transfer_member_result[$g]["number_of_share"]), 2), $transferor_string);
// 				                		}

// 				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - share amount}}</span>') !== false)
// 				                		{
// 				                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - share amount}}</span>', number_format(-($transfer_member_result[$g]["amount_share"]), 2), $transferor_string);
// 				                		}

// 				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - share type}}</span>') !== false)
// 				                		{
// 				                			if($transfer_member_result[$g]["sharetype_id"] == '1')
// 				                			{
// 				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - share type}}</span>', $transfer_member_result[$g]["sharetype_name"], $transferor_string);

// 				                			}
// 				                			elseif($transfer_member_result[$g]["sharetype_id"] == '2')
// 				                			{
// 				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - share type}}</span>', $transfer_member_result[$g]["other_class"], $transferor_string);
// 				                			}
// 				                		}

// 				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - currency}}</span>') !== false)
// 				                		{
// 				                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - currency}}</span>', $transfer_member_result[$g]["currency_name"], $transferor_string);
// 				                		}

// 				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - certificate}}</span>') !== false)
// 				                		{
// 				                			if($transfer_member_result[$g]["new_certificate_no"] != '')
// 				                			{
// 				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - certificate}}</span>', $transfer_member_result[$g]["new_certificate_no"], $transferor_string);
// 				                			}
// 				                			else
// 				                			{
// 				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - certificate}}</span>', $transfer_member_result[$g]["certificate_no"], $transferor_string);
// 				                			}
				                			
// 				                		}

// 				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - consideration}}</span>') !== false)
// 				                		{
// 				                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - consideration}}</span>', number_format($transfer_member_result[$g]["consideration"], 2), $transferor_string);
// 				                		}
// 				                	}
// 				                	elseif($transfer_member_result[$g]["number_of_share"] > 0)
// 				                	{
// 				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - name}}</span>') !== false)
// 					                	{
// 					                		if($transfer_member_result[$g]["name"] != '')
// 				                			{
// 				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - name}}</span>', $transfer_member_result[$g]["name"], $transferor_string);
				                				
// 				                			}
// 				                			elseif($transfer_member_result[$g]["company_name"] != '')
// 				                			{
// 				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - name}}</span>', $transfer_member_result[$g]["company_name"], $transferor_string);
// 				                			}
// 				                		}

// 				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - ID}}</span>') !== false)
// 					                	{
// 					                		if($transfer_member_result[$g]["identification_no"] != '')
// 				                			{
// 				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - ID}}</span>', $transfer_member_result[$g]["identification_no"], $transferor_string);
				                				
// 				                			}
// 				                			elseif($transfer_member_result[$g]["register_no"] != '')
// 				                			{
// 				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - ID}}</span>', $transfer_member_result[$g]["register_no"], $transferor_string);
// 				                			}
// 				                		}

// 				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - share number}}</span>') !== false)
// 				                		{
// 				                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - share number}}</span>', number_format($transfer_member_result[$g]["number_of_share"],2), $transferor_string);
// 				                		}

// 				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - share amount}}</span>') !== false)
// 				                		{
// 				                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - share amount}}</span>', number_format($transfer_member_result[$g]["amount_share"], 2), $transferor_string);
// 				                		}
// 				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - share type}}</span>') !== false)
// 				                		{
// 				                			if($transfer_member_result[$g]["sharetype_id"] == '1')
// 				                			{
// 				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - share type}}</span>', $transfer_member_result[$g]["sharetype_name"], $transferor_string);

// 				                			}
// 				                			elseif($transfer_member_result[$g]["sharetype_id"] == '2')
// 				                			{
// 				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - share type}}</span>', $transfer_member_result[$g]["other_class"], $transferor_string);
// 				                			}
// 				                		}

// 				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - currency}}</span>') !== false)
// 				                		{
// 				                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - currency}}</span>', $transfer_member_result[$g]["currency_name"], $transferor_string);
// 				                		}

// 				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - certificate}}</span>') !== false)
// 				                		{
// 				                			if($transfer_member_result[$g]["new_certificate_no"] != '')
// 				                			{
// 				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - certificate}}</span>', $transfer_member_result[$g]["new_certificate_no"], $transferor_string);
// 				                			}
// 				                			else
// 				                			{
// 				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - certificate}}</span>', $transfer_member_result[$g]["certificate_no"], $transferor_string);
// 				                			}
				                			
// 				                		}
// 				                	}
			                		
				            
// 			                	}
			                	

// 			                	if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - name}}</span>') !== false)
// 					            {
// 					            	$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - name}}</span>', '', $transferor_string);
// 		                		}

// 	                			if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - ID}}</span>') !== false)
// 			                	{
// 			                		$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - ID}}</span>', '', $transferor_string);
// 		                		}

// 		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - share number}}</span>') !== false)
// 		                		{
// 		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - share number}}</span>', '', $transferor_string);
// 		                		}

// 		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - share amount}}</span>') !== false)
// 		                		{
// 		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - share amount}}</span>', '', $transferor_string);
// 		                		}

// 		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - share type}}</span>') !== false)
// 		                		{
// 		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - share type}}</span>', '', $transferor_string);
// 		                		}

// 		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - currency}}</span>') !== false)
// 		                		{
// 		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - currency}}</span>', '', $transferor_string);
// 		                		}

// 		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - certificate}}</span>') !== false)
// 		                		{
// 		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - certificate}}</span>', '', $transferor_string);
// 		                		}

// 		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - consideration}}</span>') !== false)
// 		                		{
// 		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - consideration}}</span>', '', $transferor_string);
// 		                		}

// 		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - name}}</span>') !== false)
// 			                	{
// 			                		$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - name}}</span>', '', $transferor_string);
// 		                		}

// 		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - ID}}</span>') !== false)
// 			                	{
// 			                		$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - ID}}</span>', '', $transferor_string);
// 		                		}

// 		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - share number}}</span>') !== false)
// 		                		{
// 		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - share number}}</span>', '', $transferor_string);
// 		                		}

// 		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - share amount}}</span>') !== false)
// 		                		{
// 		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - share amount}}</span>', '', $transferor_string);
// 		                		}
// 		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - share type}}</span>') !== false)
// 		                		{
// 		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - share type}}</span>', '', $transferor_string);
// 		                		}

// 		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - currency}}</span>') !== false)
// 		                		{
// 		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - currency}}</span>', '', $transferor_string);
// 		                		}

// 		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - certificate}}</span>') !== false)
// 		                		{
// 		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - certificate}}</span>', '', $transferor_string);
// 		                		}

// 		                		$str = str_replace($m[0][0], $transferor_string, $str);
// 			                }
			                
			               	
// 			               	for($g = 0; $g < count($transfer_member_result); $g++)
// 			                {
// 			                	if(0 > $transfer_member_result[$g]["number_of_share"])
// 			                	{
// 				                	if(strpos($str, '<span class="myclass mceNonEditable">{{Transferor - name}}</span>') !== false)
// 						            {

// 				                		if($transfer_member_result[$g]["name"] != '')
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - name}}</span>', $transfer_member_result[$g]["name"], $str);
			                				
// 			                			}
// 			                			elseif($transfer_member_result[$g]["company_name"] != '')
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - name}}</span>', $transfer_member_result[$g]["company_name"], $str);
// 			                			}
// 			                		}

// 		                			if(strpos($str, '<span class="myclass mceNonEditable">{{Transferor - ID}}</span>') !== false)
// 				                	{
// 				                		if($transfer_member_result[$g]["identification_no"] != '')
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - ID}}</span>', $transfer_member_result[$g]["identification_no"], $str);
			                				
// 			                			}
// 			                			elseif($transfer_member_result[$g]["register_no"] != '')
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - ID}}</span>', $transfer_member_result[$g]["register_no"], $str);
// 			                			}
// 			                		}

// 			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferor - share number}}</span>') !== false)
// 			                		{
// 			                			$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - share number}}</span>', number_format(-($transfer_member_result[$g]["number_of_share"]), 2), $str);
// 			                		}

// 			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferor - share amount}}</span>') !== false)
// 			                		{
// 			                			$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - share amount}}</span>', number_format(-($transfer_member_result[$g]["amount_share"]), 2), $str);
// 			                		}

// 			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferor - share type}}</span>') !== false)
// 			                		{
// 			                			if($transfer_member_result[$g]["sharetype_id"] == '1')
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - share type}}</span>', $transfer_member_result[$g]["sharetype_name"], $str);

// 			                			}
// 			                			elseif($transfer_member_result[$g]["sharetype_id"] == '2')
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - share type}}</span>', $transfer_member_result[$g]["other_class"], $str);
// 			                			}
// 			                		}

// 			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferor - currency}}</span>') !== false)
// 			                		{
// 			                			$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - currency}}</span>', $transfer_member_result[$g]["currency_name"], $str);
// 			                		}

// 			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferor - certificate}}</span>') !== false)
// 			                		{
// 			                			if($transfer_member_result[$g]["new_certificate_no"] != '')
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - certificate}}</span>', $transfer_member_result[$g]["new_certificate_no"], $str);
// 			                			}
// 			                			else
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - certificate}}</span>', $transfer_member_result[$g]["certificate_no"], $str);
// 			                			}
			                			
// 			                		}

// 			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferor - consideration}}</span>') !== false)
// 			                		{
// 			                			$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - consideration}}</span>', number_format($transfer_member_result[$g]["consideration"], 2), $str);
// 			                		}
// 			                	}
// 			                	elseif($transfer_member_result[$g]["number_of_share"] > 0)
// 			                	{
// 			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferee - name}}</span>') !== false)
// 				                	{
// 				                		if($transfer_member_result[$g]["name"] != '')
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - name}}</span>', $transfer_member_result[$g]["name"], $str);
			                				
// 			                			}
// 			                			elseif($transfer_member_result[$g]["company_name"] != '')
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - name}}</span>', $transfer_member_result[$g]["company_name"], $str);
// 			                			}
// 			                		}

// 			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferee - ID}}</span>') !== false)
// 				                	{
// 				                		if($transfer_member_result[$g]["identification_no"] != '')
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - ID}}</span>', $transfer_member_result[$g]["identification_no"], $str);
			                				
// 			                			}
// 			                			elseif($transfer_member_result[$g]["register_no"] != '')
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - ID}}</span>', $transfer_member_result[$g]["register_no"], $str);
// 			                			}
// 			                		}

// 			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferee - share number}}</span>') !== false)
// 			                		{
// 			                			$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - share number}}</span>', number_format($transfer_member_result[$g]["number_of_share"], 2), $str);
// 			                		}

// 			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferee - share amount}}</span>') !== false)
// 			                		{
// 			                			$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - share amount}}</span>', number_format($transfer_member_result[$g]["amount_share"], 2), $str);
// 			                		}
// 			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferee - share type}}</span>') !== false)
// 			                		{
// 			                			if($transfer_member_result[$g]["sharetype_id"] == '1')
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - share type}}</span>', $transfer_member_result[$g]["sharetype_name"], $str);

// 			                			}
// 			                			elseif($transfer_member_result[$g]["sharetype_id"] == '2')
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - share type}}</span>', $transfer_member_result[$g]["other_class"], $str);
// 			                			}
// 			                		}

// 			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferee - currency}}</span>') !== false)
// 			                		{
// 			                			$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - currency}}</span>', $transfer_member_result[$g]["currency_name"], $str);
// 			                		}

// 			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferee - certificate}}</span>') !== false)
// 			                		{
// 			                			if($transfer_member_result[$g]["new_certificate_no"] != '')
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - certificate}}</span>', $transfer_member_result[$g]["new_certificate_no"], $str);
// 			                			}
// 			                			else
// 			                			{
// 			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - certificate}}</span>', $transfer_member_result[$g]["certificate_no"], $str);
// 			                			}
			                			
// 			                		}
// 			                	}
			                	
// 			                }
			                

// 			                for($h = 0; $h < count($transfer_member_result); $h++)
// 			                {
// 			                	array_push($latest_transfer_id, (int)$transfer_member_result[$h]["id"]);
// 			                }

// 		                	$data['transfer_id']=json_encode($latest_transfer_id);

		                	

// 		                	//$str = str_replace($r[0][0], $transferee_member, $str);

// 		                	if(count($previous_transfer_member_result) != 0)
// 		                	{
// 		                		$transfer_id_data = array();
// 		                		for($f = 0; $f < count($previous_transfer_member_result); $f++)
// 			                	{
// 			                		array_push($transfer_id_data, (int)$previous_transfer_member_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

		               
// 		                if($result[$r]["document_name"] == "Charge-Shorter notice of EGM" || $result[$r]["document_name"] == "Chargee-Attendance List")
// 		                {
// 		                	$charge_name_registration_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_registration,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY client_charges.id");

// 		                	$previous_charge_name_registration_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_registration,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$charge_id."' ORDER BY client_charges.id");

// 		                	$charge_name_registration_result = $charge_name_registration_result->result_array();

// 		                	$previous_charge_name_registration_result = $previous_charge_name_registration_result->result_array();

// 		                	$latest_charge_id = array();

// 		                	for($g = 0; $g < count($charge_name_registration_result); $g++)
// 		                	{
// 		                		array_push($latest_charge_id, (int)$charge_name_registration_result[$g]["id"]);
// 		                	}

// 		                	$data['charge_id']=json_encode($latest_charge_id);

// 		                	if(count($previous_charge_name_registration_result) != 0)
// 		                	{
// 		                		$charge_id_data = array();
// 		                		for($f = 0; $f < count($previous_charge_name_registration_result); $f++)
// 			                	{
// 			                		array_push($charge_id_data, (int)$previous_charge_name_registration_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Charge name - registration}}</span>') !== false)
// 		                {
// 		                	$charge_name_registration_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_registration,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY client_charges.id");

// 		                	$previous_charge_name_registration_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_registration,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$charge_id."' ORDER BY client_charges.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$charge_name_registration_result = $charge_name_registration_result->result_array();

// 		                	$previous_charge_name_registration_result = $previous_charge_name_registration_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$charge_name_registration = "";

// 		                	$latest_charge_id = array();
// 		                	$num_of_charge_name_registration = (int)(count($charge_name_registration_result)) - 1;

// 		                	for($g = 0; $g < count($charge_name_registration_result); $g++)
// 		                	{

// 			                		if($g == 0)
// 			                		{
// 			                			$charge_name_registration = $charge_name_registration.$charge_name_registration_result[$g]["charge"];
// 			                		}
// 			                		elseif($g == (int)$num_of_charge_name_registration)
// 			                		{
// 			                			$charge_name_registration = $charge_name_registration.' and '.$charge_name_registration_result[$g]["charge"];
// 			                		}
// 			                		else
// 			                		{
// 			                			$charge_name_registration = $charge_name_registration.', '.$charge_name_registration_result[$g]["charge"];
// 			                		}
// 			                	//}
		                		
// 		                		array_push($latest_charge_id, (int)$charge_name_registration_result[$g]["id"]);
// 		                	}

// 		                	$data['charge_id']=json_encode($latest_charge_id);

// 		                		$str = str_replace('<span class="myclass mceNonEditable">{{Charge name - registration}}</span>', $charge_name_registration, $str);

// */
// 		                	if(count($previous_charge_name_registration_result) != 0)
// 		                	{
// 		                		$charge_id_data = array();
// 		                		for($f = 0; $f < count($previous_charge_name_registration_result); $f++)
// 			                	{
// 			                		array_push($charge_id_data, (int)$previous_charge_name_registration_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Charge name - satisfaction}}</span>') !== false)
// 		                {
// 		                	$charge_name_satisfaction_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_satisfied,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY client_charges.id");

// 		                	$previous_charge_name_satisfaction_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_satisfied,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$charge_id."' ORDER BY client_charges.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$charge_name_satisfaction_result = $charge_name_satisfaction_result->result_array();

// 		                	$previous_charge_name_satisfaction_result = $previous_charge_name_satisfaction_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$charge_name_satisfaction = "";

// 		                	$latest_charge_id = array();
// 		                	$num_of_charge_name_satisfaction = (int)(count($charge_name_satisfaction_result)) - 1;

// 		                	for($g = 0; $g < count($charge_name_satisfaction_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$charge_name_satisfaction = $charge_name_satisfaction.$charge_name_satisfaction_result[$g]["charge"];
// 		                		}
// 		                		elseif($g == (int)$num_of_charge_name_satisfaction)
// 		                		{
// 		                			$charge_name_satisfaction = $charge_name_satisfaction.' and '.$charge_name_satisfaction_result[$g]["charge"];
// 		                		}
// 		                		else
// 		                		{
// 		                			$charge_name_satisfaction = $charge_name_satisfaction.', '.$charge_name_satisfaction_result[$g]["charge"];
// 		                		}
		                		
// 		                		array_push($latest_charge_id, (int)$charge_name_satisfaction_result[$g]["id"]);
// 		                	}

// 		                	$data['charge_id']=json_encode($latest_charge_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Charge name - satisfaction}}</span>', $charge_name_satisfaction, $str);

// 		                	if(count($previous_charge_name_satisfaction_result) != 0)
// 		                	{
// 		                		$charge_id_data = array();
// 		                		for($f = 0; $f < count($previous_charge_name_satisfaction_result); $f++)
// 			                	{
// 			                		array_push($charge_id_data, (int)$previous_charge_name_satisfaction_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Charge nature - registration}}</span>') !== false)
// 		                {
// 		                	$charge_nature_registration_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_registration,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY client_charges.id");

// 		                	$previous_charge_nature_registration_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_registration,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$charge_id."' ORDER BY client_charges.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$charge_nature_registration_result = $charge_nature_registration_result->result_array();

// 		                	$previous_charge_nature_registration_result = $previous_charge_nature_registration_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$charge_nature_registration = "";

// 		                	$latest_charge_id = array();
// 		                	$num_of_charge_nature_registration = (int)(count($charge_nature_registration_result)) - 1;

// 		                	for($g = 0; $g < count($charge_nature_registration_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$charge_nature_registration = $charge_nature_registration.'<strong>'.$charge_nature_registration_result[$g]["nature_of_charge"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_charge_nature_registration)
// 		                		{
// 		                			$charge_nature_registration = $charge_nature_registration.' and <strong>'.$charge_nature_registration_result[$g]["nature_of_charge"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$charge_nature_registration = $charge_nature_registration.', <strong>'.$charge_nature_registration_result[$g]["nature_of_charge"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_charge_id, (int)$charge_nature_registration_result[$g]["id"]);
// 		                	}

// 		                	$data['charge_id']=json_encode($latest_charge_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Charge nature - registration}}</span>', $charge_nature_registration, $str);

// 		                	if(count($previous_charge_nature_registration_result) != 0)
// 		                	{
// 		                		$charge_id_data = array();
// 		                		for($f = 0; $f < count($previous_charge_nature_registration_result); $f++)
// 			                	{
// 			                		array_push($charge_id_data, (int)$previous_charge_nature_registration_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Charge nature - satisfaction}}</span>') !== false)
// 		                {
// 		                	$charge_nature_satisfaction_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_satisfied,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY client_charges.id");

// 		                	$previous_charge_nature_satisfaction_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_satisfied,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$charge_id."' ORDER BY client_charges.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$charge_nature_satisfaction_result = $charge_nature_satisfaction_result->result_array();

// 		                	$previous_charge_nature_satisfaction_result = $previous_charge_nature_satisfaction_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$charge_nature_satisfaction = "";

// 		                	$latest_charge_id = array();
// 		                	$num_of_charge_nature_satisfaction = (int)(count($charge_nature_satisfaction_result)) - 1;

// 		                	for($g = 0; $g < count($charge_nature_satisfaction_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$charge_nature_satisfaction = $charge_nature_satisfaction.'<strong>'.$charge_nature_satisfaction_result[$g]["nature_of_charge"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_charge_nature_satisfaction)
// 		                		{
// 		                			$charge_nature_satisfaction = $charge_nature_satisfaction.' and <strong>'.$charge_nature_satisfaction_result[$g]["nature_of_charge"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$charge_nature_satisfaction = $charge_nature_satisfaction.', <strong>'.$charge_nature_satisfaction_result[$g]["nature_of_charge"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_charge_id, (int)$charge_nature_satisfaction_result[$g]["id"]);
// 		                	}

// 		                	$data['charge_id']=json_encode($latest_charge_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Charge nature - satisfaction}}</span>', $charge_nature_satisfaction, $str);

// 		                	if(count($previous_charge_nature_satisfaction_result) != 0)
// 		                	{
// 		                		$charge_id_data = array();
// 		                		for($f = 0; $f < count($previous_charge_nature_satisfaction_result); $f++)
// 			                	{
// 			                		array_push($charge_id_data, (int)$previous_charge_nature_satisfaction_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Charge registration number}}</span>') !== false)
// 		                {
// 		                	$charge_registration_number_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_registration,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY client_charges.id");

// 		                	$previous_charge_registration_number_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_registration,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$charge_id."' ORDER BY client_charges.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$charge_registration_number_result = $charge_registration_number_result->result_array();

// 		                	$previous_charge_registration_number_result = $previous_charge_registration_number_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$charge_registration_number = "";

// 		                	$latest_charge_id = array();
// 		                	$num_of_charge_registration_number = (int)(count($charge_registration_number_result)) - 1;

// 		                	for($g = 0; $g < count($charge_registration_number_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$charge_registration_number = $charge_registration_number.'<strong>'.$charge_registration_number_result[$g]["charge_no"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_charge_registration_number)
// 		                		{
// 		                			$charge_registration_number = $charge_registration_number.' and <strong>'.$charge_registration_number_result[$g]["charge_no"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$charge_registration_number = $charge_registration_number.', <strong>'.$charge_registration_number_result[$g]["charge_no"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_charge_id, (int)$charge_registration_number_result[$g]["id"]);
// 		                	}

// 		                	$data['charge_id']=json_encode($latest_charge_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Charge registration number}}</span>', $charge_registration_number, $str);

// 		                	if(count($previous_charge_registration_number_result) != 0)
// 		                	{
// 		                		$charge_id_data = array();
// 		                		for($f = 0; $f < count($previous_charge_registration_number_result); $f++)
// 			                	{
// 			                		array_push($charge_id_data, (int)$previous_charge_registration_number_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Charge registration date}}</span>') !== false)
// 		                {
// 		                	$charge_registration_date_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_registration,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY client_charges.id");

// 		                	$previous_charge_registration_date_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_registration,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$charge_id."' ORDER BY client_charges.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$charge_registration_date_result = $charge_registration_date_result->result_array();

// 		                	$previous_charge_registration_date_result = $previous_charge_registration_date_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$charge_registration_date = "";

// 		                	$latest_charge_id = array();
// 		                	$num_of_charge_registration_date = (int)(count($charge_registration_date_result)) - 1;

// 		                	for($g = 0; $g < count($charge_registration_date_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$charge_registration_date = $charge_registration_date.'<strong>'.$charge_registration_date_result[$g]["date_registration"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_charge_registration_date)
// 		                		{
// 		                			$charge_registration_date = $charge_registration_date.' and <strong>'.$charge_registration_date_result[$g]["date_registration"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$charge_registration_date = $charge_registration_date.', <strong>'.$charge_registration_date_result[$g]["date_registration"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_charge_id, (int)$charge_registration_date_result[$g]["id"]);
// 		                	}

// 		                	$data['charge_id']=json_encode($latest_charge_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Charge registration date}}</span>', $charge_registration_date, $str);

// 		                	if(count($previous_charge_registration_date_result) != 0)
// 		                	{
// 		                		$charge_id_data = array();
// 		                		for($f = 0; $f < count($previous_charge_registration_date_result); $f++)
// 			                	{
// 			                		array_push($charge_id_data, (int)$previous_charge_registration_date_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Charge satisfaction number}}</span>') !== false)
// 		                {
// 		                	$charge_satisfaction_number_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_registration,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY client_charges.id");

// 		                	$previous_charge_satisfaction_number_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_registration,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$charge_id."' ORDER BY client_charges.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$charge_satisfaction_number_result = $charge_satisfaction_number_result->result_array();

// 		                	$previous_charge_satisfaction_number_result = $previous_charge_satisfaction_number_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$charge_satisfaction_number = "";

// 		                	$latest_charge_id = array();
// 		                	$num_of_charge_satisfaction_number = (int)(count($charge_satisfaction_number_result)) - 1;

// 		                	for($g = 0; $g < count($charge_satisfaction_number_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$charge_satisfaction_number = $charge_satisfaction_number.'<strong>'.$charge_satisfaction_number_result[$g]["satisfactory_no"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_charge_satisfaction_number)
// 		                		{
// 		                			$charge_satisfaction_number = $charge_satisfaction_number.' and <strong>'.$charge_satisfaction_number_result[$g]["satisfactory_no"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$charge_satisfaction_number = $charge_satisfaction_number.', <strong>'.$charge_satisfaction_number_result[$g]["satisfactory_no"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_charge_id, (int)$charge_satisfaction_number_result[$g]["id"]);
// 		                	}

// 		                	$data['charge_id']=json_encode($latest_charge_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Charge satisfaction number}}</span>', $charge_satisfaction_number, $str);

// 		                	if(count($previous_charge_satisfaction_number_result) != 0)
// 		                	{
// 		                		$charge_id_data = array();
// 		                		for($f = 0; $f < count($previous_charge_satisfaction_number_result); $f++)
// 			                	{
// 			                		array_push($charge_id_data, (int)$previous_charge_satisfaction_number_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Charge satisfaction date}}</span>') !== false)
// 		                {
// 		                	$charge_satisfaction_date_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_registration,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY client_charges.id");

// 		                	$previous_charge_satisfaction_date_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_registration,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$charge_id."' ORDER BY client_charges.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$charge_satisfaction_date_result = $charge_satisfaction_date_result->result_array();

// 		                	$previous_charge_satisfaction_date_result = $previous_charge_satisfaction_date_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$charge_satisfaction_date = "";

// 		                	$latest_charge_id = array();
// 		                	$num_of_charge_satisfaction_date = (int)(count($charge_satisfaction_date_result)) - 1;

// 		                	for($g = 0; $g < count($charge_satisfaction_date_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$charge_satisfaction_date = $charge_satisfaction_date.'<strong>'.$charge_satisfaction_date_result[$g]["date_satisfied"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_charge_satisfaction_date)
// 		                		{
// 		                			$charge_satisfaction_date = $charge_satisfaction_date.' and <strong>'.$charge_satisfaction_date_result[$g]["date_satisfied"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$charge_satisfaction_date = $charge_satisfaction_date.', <strong>'.$charge_satisfaction_date_result[$g]["date_satisfied"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_charge_id, (int)$charge_satisfaction_date_result[$g]["id"]);
// 		                	}

// 		                	$data['charge_id']=json_encode($latest_charge_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Charge satisfaction date}}</span>', $charge_satisfaction_date, $str);

// 		                	if(count($previous_charge_satisfaction_date_result) != 0)
// 		                	{
// 		                		$charge_id_data = array();
// 		                		for($f = 0; $f < count($previous_charge_satisfaction_date_result); $f++)
// 			                	{
// 			                		array_push($charge_id_data, (int)$previous_charge_satisfaction_date_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Charge currency - registration}}</span>') !== false)
// 		                {
// 		                	$charge_currency_registration_result = $this->db->query("select client_charges.*, currency.currency as currency_name from client_charges left join currency on currency.id = client_charges.currency where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_satisfied,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY client_charges.id");

// 		                	$previous_charge_currency_registration_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_registration,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$charge_id."' ORDER BY client_charges.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$charge_currency_registration_result = $charge_currency_registration_result->result_array();

// 		                	$previous_charge_currency_registration_result = $previous_charge_currency_registration_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$charge_currency_registration = "";

// 		                	$latest_charge_id = array();
// 		                	$num_of_charge_currency_registration = (int)(count($charge_currency_registration_result)) - 1;

// 		                	for($g = 0; $g < count($charge_currency_registration_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$charge_currency_registration = $charge_currency_registration.'<strong>'.$charge_currency_registration_result[$g]["currency_name"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_charge_currency_registration)
// 		                		{
// 		                			$charge_currency_registration = $charge_currency_registration.' and <strong>'.$charge_currency_registration_result[$g]["currency_name"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$charge_currency_registration = $charge_currency_registration.', <strong>'.$charge_currency_registration_result[$g]["currency_name"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_charge_id, (int)$charge_currency_registration_result[$g]["id"]);
// 		                	}

// 		                	$data['charge_id']=json_encode($latest_charge_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Charge currency - registration}}</span>', $charge_currency_registration, $str);

// 		                	if(count($previous_charge_currency_registration_result) != 0)
// 		                	{
// 		                		$charge_id_data = array();
// 		                		for($f = 0; $f < count($previous_charge_currency_registration_result); $f++)
// 			                	{
// 			                		array_push($charge_id_data, (int)$previous_charge_currency_registration_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Charge currency - satisfaction}}</span>') !== false)
// 		                {
// 		                	$charge_currency_satisfaction_result = $this->db->query("select client_charges.*, currency.currency as currency_name from client_charges left join currency on currency.id = client_charges.currency where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_satisfied,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY client_charges.id");

// 		                	$previous_charge_currency_satisfaction_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_satisfied,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$charge_id."' ORDER BY client_charges.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$charge_currency_satisfaction_result = $charge_currency_satisfaction_result->result_array();

// 		                	$previous_charge_currency_satisfaction_result = $previous_charge_currency_satisfaction_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$charge_currency_satisfaction = "";

// 		                	$latest_charge_id = array();
// 		                	$num_of_charge_currency_satisfaction = (int)(count($charge_currency_satisfaction_result)) - 1;

// 		                	for($g = 0; $g < count($charge_currency_satisfaction_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$charge_currency_satisfaction = $charge_currency_satisfaction.'<strong>'.$charge_currency_satisfaction_result[$g]["currency_namecurrency_name"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_charge_currency_satisfaction)
// 		                		{
// 		                			$charge_currency_satisfaction = $charge_currency_satisfaction.' and <strong>'.$charge_currency_satisfaction_result[$g]["currency_namecurrency_name"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$charge_currency_satisfaction = $charge_currency_satisfaction.', <strong>'.$charge_currency_satisfaction_result[$g]["currency_namecurrency_name"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_charge_id, (int)$charge_currency_satisfaction_result[$g]["id"]);
// 		                	}

// 		                	$data['charge_id']=json_encode($latest_charge_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Charge currency - satisfaction}}</span>', $charge_currency_satisfaction, $str);

// 		                	if(count($previous_charge_currency_satisfaction_result) != 0)
// 		                	{
// 		                		$charge_id_data = array();
// 		                		for($f = 0; $f < count($previous_charge_currency_satisfaction_result); $f++)
// 			                	{
// 			                		array_push($charge_id_data, (int)$previous_charge_currency_satisfaction_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Charge amount - registration}}</span>') !== false)
// 		                {
// 		                	$charge_amount_registration_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_registration,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY client_charges.id");

// 		                	$previous_charge_amount_registration_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_registration,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$charge_id."' ORDER BY client_charges.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$charge_amount_registration_result = $charge_amount_registration_result->result_array();

// 		                	$previous_charge_amount_registration_result = $previous_charge_amount_registration_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$charge_amount_registration = "";

// 		                	$latest_charge_id = array();
// 		                	$num_of_charge_amount_registration = (int)(count($charge_amount_registration_result)) - 1;

// 		                	for($g = 0; $g < count($charge_amount_registration_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$charge_amount_registration = $charge_amount_registration.'<strong>'.number_format($charge_amount_registration_result[$g]["amount"], 2).'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_charge_amount_registration)
// 		                		{
// 		                			$charge_amount_registration = $charge_amount_registration.' and <strong>'.number_format($charge_amount_registration_result[$g]["amount"], 2).'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$charge_amount_registration = $charge_amount_registration.', <strong>'.number_format($charge_amount_registration_result[$g]["amount"], 2).'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_charge_id, (int)$charge_amount_registration_result[$g]["id"]);
// 		                	}

// 		                	$data['charge_id']=json_encode($latest_charge_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Charge amount - registration}}</span>', $charge_amount_registration, $str);

// 		                	if(count($previous_charge_amount_registration_result) != 0)
// 		                	{
// 		                		$charge_id_data = array();
// 		                		for($f = 0; $f < count($previous_charge_amount_registration_result); $f++)
// 			                	{
// 			                		array_push($charge_id_data, (int)$previous_charge_amount_registration_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Charge amount - satisfaction}}</span>') !== false)
// 		                {
// 		                	$charge_amount_satisfaction_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_satisfied,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY client_charges.id");

// 		                	$previous_amount_currency_satisfaction_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_satisfied,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$charge_id."' ORDER BY client_charges.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$charge_amount_satisfaction_result = $charge_amount_satisfaction_result->result_array();

// 		                	$previous_amount_currency_satisfaction_result = $previous_amount_currency_satisfaction_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$charge_amount_satisfaction = "";

// 		                	$latest_charge_id = array();
// 		                	$num_of_charge_amount_satisfaction = (int)(count($charge_amount_satisfaction_result)) - 1;

// 		                	for($g = 0; $g < count($charge_amount_satisfaction_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$charge_amount_satisfaction = $charge_amount_satisfaction.'<strong>'.number_format($charge_amount_satisfaction_result[$g]["amount"], 2).'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_charge_amount_satisfaction)
// 		                		{
// 		                			$charge_amount_satisfaction = $charge_amount_satisfaction.' and <strong>'.number_format($charge_amount_satisfaction_result[$g]["amount"], 2).'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$charge_amount_satisfaction = $charge_amount_satisfaction.', <strong>'.number_format($charge_amount_satisfaction_result[$g]["amount"], 2).'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_charge_id, (int)$charge_amount_satisfaction_result[$g]["id"]);
// 		                	}

// 		                	$data['charge_id']=json_encode($latest_charge_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Charge amount - satisfaction}}</span>', $charge_amount_satisfaction, $str);

// 		                	if(count($previous_amount_currency_satisfaction_result) != 0)
// 		                	{
// 		                		$charge_id_data = array();
// 		                		for($f = 0; $f < count($previous_amount_currency_satisfaction_result); $f++)
// 			                	{
// 			                		array_push($charge_id_data, (int)$previous_amount_currency_satisfaction_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Charge security - registration}}</span>') !== false)
// 		                {
// 		                	$charge_security_registration_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_registration,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY client_charges.id");

// 		                	$previous_charge_security_registration_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_registration,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$charge_id."' ORDER BY client_charges.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$charge_security_registration_result = $charge_security_registration_result->result_array();

// 		                	$previous_charge_security_registration_result = $previous_charge_security_registration_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$charge_security_registration = "";

// 		                	$latest_charge_id = array();
// 		                	$num_of_charge_security_registration = (int)(count($charge_security_registration_result)) - 1;

// 		                	for($g = 0; $g < count($charge_security_registration_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$charge_security_registration = $charge_security_registration.'<strong>'.$charge_security_registration_result[$g]["secured_by"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_charge_security_registration)
// 		                		{
// 		                			$charge_security_registration = $charge_security_registration.' and <strong>'.$charge_security_registration_result[$g]["secured_by"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$charge_security_registration = $charge_security_registration.', <strong>'.$charge_security_registration_result[$g]["secured_by"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_charge_id, (int)$charge_security_registration_result[$g]["id"]);
// 		                	}

// 		                	$data['charge_id']=json_encode($latest_charge_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Charge security - registration}}</span>', $charge_security_registration, $str);

// 		                	if(count($previous_charge_security_registration_result) != 0)
// 		                	{
// 		                		$charge_id_data = array();
// 		                		for($f = 0; $f < count($previous_charge_security_registration_result); $f++)
// 			                	{
// 			                		array_push($charge_id_data, (int)$previous_charge_security_registration_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Charge security - satisfaction}}</span>') !== false)
// 		                {
// 		                	$charge_security_satisfaction_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_satisfied,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') ORDER BY client_charges.id");

// 		                	$previous_charge_security_satisfaction_result = $this->db->query("select * from client_charges where company_code='".$company_code."' AND STR_TO_DATE(client_charges.date_satisfied,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') AND id != '".$charge_id."' ORDER BY client_charges.id");
// 		                	//echo json_encode($previous_director_name_appointment_result);
// 		                	$charge_security_satisfaction_result = $charge_security_satisfaction_result->result_array();

// 		                	$previous_charge_security_satisfaction_result = $previous_charge_security_satisfaction_result->result_array();
// 		                	//echo json_encode($director_name_result);

// 		                	$charge_security_satisfaction = "";

// 		                	$latest_charge_id = array();
// 		                	$num_of_charge_security_satisfaction = (int)(count($charge_security_satisfaction_result)) - 1;

// 		                	for($g = 0; $g < count($charge_security_satisfaction_result); $g++)
// 		                	{
// 		                		if($g == 0)
// 		                		{
// 		                			$charge_security_satisfaction = $charge_security_satisfaction.'<strong>'.$charge_security_satisfaction_result[$g]["secured_by"].'</strong>';
// 		                		}
// 		                		elseif($g == (int)$num_of_charge_security_satisfaction)
// 		                		{
// 		                			$charge_security_satisfaction = $charge_security_satisfaction.' and <strong>'.$charge_security_satisfaction_result[$g]["secured_by"].'</strong>';
// 		                		}
// 		                		else
// 		                		{
// 		                			$charge_security_satisfaction = $charge_security_satisfaction.', <strong>'.$charge_security_satisfaction_result[$g]["secured_by"].'</strong>';
// 		                		}
		                		
// 		                		array_push($latest_charge_id, (int)$charge_security_satisfaction_result[$g]["id"]);
// 		                	}

// 		                	$data['charge_id']=json_encode($latest_charge_id);

// 		                	$str = str_replace('<span class="myclass mceNonEditable">{{Charge security - satisfaction}}</span>', $charge_security_satisfaction, $str);

// 		                	if(count($previous_charge_security_satisfaction_result) != 0)
// 		                	{
// 		                		$charge_id_data = array();
// 		                		for($f = 0; $f < count($previous_charge_security_satisfaction_result); $f++)
// 			                	{
// 			                		array_push($charge_id_data, (int)$previous_charge_security_satisfaction_result[$f]["id"]);
// 			                	}
// 		                	}
// 		                	//echo json_encode($officer_id_data);
// 		                }

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Year end new}}</span>') !== false)
// 						{
// 							$year_end_result = $this->db->query("select year_end from filing where id='".$filing_id."'");
		                	
// 		                	if ($year_end_result->num_rows() > 0) {

// 			                	$year_end_result = $year_end_result->result_array();

// 			                	//$chairman_result_info = (explode("-",$chairman_result[0]["chairman"]));

// 								$str = str_replace('<span class="myclass mceNonEditable">{{Year end new}}</span>', $year_end_result[0]["year_end"], $str);
// 							}
// 						}

// 						if(strpos($str, '<span class="myclass mceNonEditable">{{Year end old}}</span>') !== false)
// 						{
// 							$history_year_end_result = $this->db->query("select year_end from history_filing where id='".$filing_id."'");
		                	
// 		                	if ($history_year_end_result->num_rows() > 0) {

// 			                	$history_year_end_result = $history_year_end_result->result_array();

// 			                	//$chairman_result_info = (explode("-",$chairman_result[0]["chairman"]));

// 								$str = str_replace('<span class="myclass mceNonEditable">{{Year end old}}</span>', $history_year_end_result[0]["year_end"], $str);
// 							}
// 						}

// 	                	if(strpos($str, '<span class="myclass mceNonEditable">{{AGM date}}</span>') !== false)
// 						{
		                	
// 		                	$agm_date_result = $this->db->query("select year_end, agm, due_date_175, 175_extended_to, due_date_201, 201_extended_to from filing where company_code='".$company_code."'ORDER BY id DESC LIMIT 1,1");
		                	
// 		                	if ($agm_date_result->num_rows() > 0) {

// 			                	$agm_date_result = $agm_date_result->result_array();

// 			                	//echo json_encode($agm_date_result);
// 			                	//$chairman_result_info = (explode("-",$chairman_result[0]["chairman"]));

// 								$str = str_replace('<span class="myclass mceNonEditable">{{AGM date}}</span>', $agm_date_result[0]["agm"], $str);
// 							}
// 						}

// 						if(strpos($str, '<span class="myclass mceNonEditable">{{Due date S175}}</span>') !== false)
// 						{
		                	
// 		                	$due_date_S175_result = $this->db->query("select year_end, agm, due_date_175, 175_extended_to, due_date_201, 201_extended_to from filing where agm = '' AND company_code='".$company_code."'");

// 		                	//echo json_encode($due_date_S175_result->result_array());
// 		                	if ($due_date_S175_result->num_rows() > 0) {

// 			                	$due_date_S175_result = $due_date_S175_result->result_array();

// 			                	//$chairman_result_info = (explode("-",$chairman_result[0]["chairman"]));
// 			                	if($due_date_S175_result[0]["175_extended_to"] != "" && $due_date_S175_result[0]["175_extended_to"] != '0')
// 			                	{
// 			                		$str = str_replace('<span class="myclass mceNonEditable">{{Due date S175}}</span>', $due_date_S175_result[0]["175_extended_to"], $str);
// 			                	}
// 			                	else 
// 			                	{
// 			                		$str = str_replace('<span class="myclass mceNonEditable">{{Due date S175}}</span>', $due_date_S175_result[0]["due_date_175"], $str);
// 			                	}
								
// 							}
// 						}

// 						if(strpos($str, '<span class="myclass mceNonEditable">{{Due date S201}}</span>') !== false)
// 						{
		                	
// 		                	$due_date_S201_result = $this->db->query("select year_end, agm, due_date_175, 175_extended_to, due_date_201, 201_extended_to from filing where agm = '' AND company_code='".$company_code."'");

// 		                	//echo json_encode($due_date_S175_result->result_array());
// 		                	if ($due_date_S201_result->num_rows() > 0) {

// 			                	$due_date_S201_result = $due_date_S201_result->result_array();

// 			                	//$chairman_result_info = (explode("-",$chairman_result[0]["chairman"]));
// 			                	if($due_date_S201_result[0]["201_extended_to"] != "" && $due_date_S201_result[0]["201_extended_to"] != '0')
// 			                	{
// 			                		$str = str_replace('<span class="myclass mceNonEditable">{{Due date S201}}</span>', $due_date_S201_result[0]["201_extended_to"], $str);
// 			                	}
// 			                	else 
// 			                	{
// 			                		$str = str_replace('<span class="myclass mceNonEditable">{{Due date S201}}</span>', $due_date_S201_result[0]["due_date_201"], $str);
// 			                	}
								
// 							}
// 						}

// 		                if(strpos($str, '<span class="myclass mceNonEditable">{{Chairman}}</span>') !== false)
// 						{
// 							$chairman_result = $this->db->query("select chairman from client_signing_info where company_code='".$company_code."'");
		                	
// 		                	if ($chairman_result->num_rows() > 0) {

// 			                	$chairman_result = $chairman_result->result_array();

// 			                	$chairman_result_info = (explode("-",$chairman_result[0]["chairman"]));

// 			                	if($chairman_result_info[1] == "individual")
// 			                	{
// 			                		$officer_result = $this->db->query("select * from officer where id='".$chairman_result_info[0]."' AND field_type='".$chairman_result_info[1]."'");

// 			                		$officer_result = $officer_result->result_array();

// 			                		$name = $officer_result[0]["name"];
// 			                	}
// 			                	elseif($chairman_result_info[1] == "company")
// 			                	{
// 			                		$officer_company_result = $this->db->query("select * from officer_company where id='".$chairman_result_info[0]."' AND field_type='".$chairman_result_info[1]."'");

// 			                		$officer_company_result = $officer_company_result->result_array();

// 			                		$name = $officer_company_result[0]["company_name"];
// 			                	}

// 								$str = str_replace('<span class="myclass mceNonEditable">{{Chairman}}</span>', $name, $str);
// 							}
// 						}

// 						if(strpos($str, '<span class="myclass mceNonEditable">{{Signing director}}</span>') !== false)
// 						{
// 							$chairman_result = $this->db->query("select director_signature_1, director_signature_2 from client_signing_info where company_code='".$company_code."'");
		                	
// 		                	if ($chairman_result->num_rows() > 0) 
// 		                	{

// 			                	$chairman_result = $chairman_result->result_array();

// 			                	if($chairman_result[0]["director_signature_1"] != 0)
// 			                	{
// 			                		$client_officers_result = $this->db->query("select client_officers.officer_id, client_officers.field_type, officer.name from client_officers left join officer on officer.id = client_officers.officer_id where client_officers.id='".$chairman_result[0]["director_signature_1"]."'");

// 			                		$client_officers_result = $client_officers_result->result_array();

// 			                		$officer_name = $client_officers_result [0]["name"];
// 			                	}
// 			                	else
// 			                	{
// 			                		$officer_name = "";
// 			                	}

// 			                	if($chairman_result[0]["director_signature_2"] != 0)
// 			                	{
// 			                		$client_officers_result = $this->db->query("select client_officers.officer_id, client_officers.field_type, officer.name from client_officers left join officer on officer.id = client_officers.officer_id where client_officers.id='".$chairman_result[0]["director_signature_2"]."'");

// 			                		$client_officers_result = $client_officers_result->result_array();

// 			                		$officer_name = $officer_name.' and '.$client_officers_result [0]["name"];
// 			                	}
// 			                	else
// 			                	{
// 			                		$officer_name = $officer_name;
// 			                	}

// 			                	$str = str_replace('<span class="myclass mceNonEditable">{{Signing director}}</span>', $officer_name, $str);
// 			                }
// 						}

// 						if(strpos($str, '<span class="myclass mceNonEditable">{{Retiring director}}</span>') !== false)
// 						{
// 							$client_officers_retiring_result = $this->db->query("select client_officers.officer_id, client_officers.field_type, officer.name from client_officers left join officer on officer.id = client_officers.officer_id where client_officers.retiring='1'");

// 							if ($client_officers_retiring_result->num_rows() > 0) 
// 		                	{	
// 		                		$client_officers_retiring_result = $client_officers_retiring_result->result_array();

// 		                		$client_officers_retiring_name = "";

// 		                		$num_of_client_officers_retiring_result = (int)(count($client_officers_retiring_result)) - 1;

// 			                	for($g = 0; $g < count($client_officers_retiring_result); $g++)
// 			                	{
// 			                		if($g == 0)
// 			                		{
// 			                			$client_officers_retiring_name = $client_officers_retiring_name.''.$client_officers_retiring_result[$g]["name"].'';
// 			                		}
// 			                		elseif($g == (int)$num_of_client_officers_retiring_result)
// 			                		{
// 			                			$client_officers_retiring_name = $client_officers_retiring_name.' and '.$client_officers_retiring_result[$g]["name"].'';
// 			                		}
// 			                		else
// 			                		{
// 			                			$client_officers_retiring_name = $client_officers_retiring_name.', '.$client_officers_retiring_result[$g]["name"].'';
// 			                		}

// 			                	}

// 			                	$str = str_replace('<span class="myclass mceNonEditable">{{Retiring director}}</span>', $client_officers_retiring_name, $str);
// 		                	}
// 						}

// 		                $data['content'] = $str;
// 		            }
// 	                $data['created_by']=$this->session->userdata('user_id');
// 	           		//echo json_encode($officer_id_data);
// 	           		if($officer_id != null)
// 	           		{
// 	           			$q = $this->db->get_where("pending_documents", array("triggered_by" => $result[$r]["triggered_by"], "client_id" => $get_client[0]["id"], "received_on" => "", "officer_id" => json_encode($officer_id_data), "transaction_date" => $data['transaction_date'], "document_name" => $data['document_name']));
	           			
// 	           		}
// 	           		elseif($controller_id != null)
// 	           		{
// 	           			$q = $this->db->get_where("pending_documents", array("triggered_by" => $result[$r]["triggered_by"], "client_id" => $get_client[0]["id"], "received_on" => "", "controller_id" => $controller_id, "document_name" => $data['document_name']));
// 	           		}
// 	           		elseif($allotment_id != null)
// 	           		{
// 	           			$q = $this->db->get_where("pending_documents", array("triggered_by" => $result[$r]["triggered_by"], "client_id" => $get_client[0]["id"], "received_on" => "", "allotment_id" =>json_encode($allotment_id_data), "transaction_date" => $data['transaction_date'], "document_name" => $data['document_name']));
// 	           		}
// 	           		elseif($buyback_id != null)
// 	           		{
// 	           			$q = $this->db->get_where("pending_documents", array("triggered_by" => $result[$r]["triggered_by"], "client_id" => $get_client[0]["id"], "received_on" => "", "buyback_id" =>json_encode($buyback_id_data), "transaction_date" => $data['transaction_date'], "document_name" => $data['document_name']));
// 	           		}
// 	           		elseif($transfer_id != null)
// 	           		{
// 	           			$q = $this->db->get_where("pending_documents", array("triggered_by" => $result[$r]["triggered_by"], "client_id" => $get_client[0]["id"], "received_on" => "", "transfer_id" =>json_encode($transfer_id_data), "transaction_date" => $data['transaction_date'], "document_name" => $data['document_name']));
// 	           		}
// 	           		elseif($charge_id != null)
// 	           		{
// 	           			$q = $this->db->get_where("pending_documents", array("triggered_by" => $result[$r]["triggered_by"], "client_id" => $get_client[0]["id"], "received_on" => "", "charge_id" =>  json_encode($charge_id_data), "transaction_date" => $data['transaction_date'], "document_name" => $data['document_name']));
// 	           		}
// 	           		elseif($filing_id != null)
// 	           		{
// 	           			$q = $this->db->get_where("pending_documents", array("triggered_by" => $result[$r]["triggered_by"], "client_id" => $get_client[0]["id"], "received_on" => "", "filing_id" => $filing_id, "document_name" => $data['document_name']));
// 	           		}
// 	           		elseif($guarantee_id != null)
// 	           		{
// 	           			$q = $this->db->get_where("pending_documents", array("triggered_by" => $result[$r]["triggered_by"], "client_id" => $get_client[0]["id"], "received_on" => "", "guarantee_id" => $guarantee_id, "document_name" => $data['document_name']));
// 	           		}
// 	           		elseif($officer_id == null && $controller_id == null && $allotment_id == null && $buyback_id == null && $transfer_id == null && $charge_id == null && $filing_id == null && $guarantee_id == null)
// 	           		{
// 	           			$q = $this->db->get_where("pending_documents", array("triggered_by" => $result[$r]["triggered_by"], "client_id" => $get_client[0]["id"], "received_on" => "", "document_name" => $data['document_name']));
// 	           		}
	           		

// 	                if (!$q->num_rows() || $type == "agm_held" || $type == "add_new_class_of_share" || $type == "add_buyback_of_share" || $type == "add_transfer_of_share" || $type == "add_guarantee" || $type == "dispense_agm")
// 	                {        
// 	                	if($type == "add_allotment_of_share")
// 						{
// 							$check_allot_data = $this->db->get_where("pending_documents", array("allotment_id" => $data['allotment_id'], "document_name" => $data['document_name']));

// 							if (!$check_allot_data->num_rows())
// 							{
// 								$this->db->insert("pending_documents",$data);
// 							}
// 						}  
// 						elseif($type == "add_buyback_of_share")
// 						{
// 							$check_buyback_data = $this->db->get_where("pending_documents", array("buyback_id" => $data['buyback_id'], "document_name" => $data['document_name']));

// 							if (!$check_buyback_data->num_rows())
// 							{
// 								$this->db->insert("pending_documents",$data);
// 							}
// 						}  
// 						elseif($type == "add_transfer_of_share")
// 						{
// 							$check_transfer_data = $this->db->get_where("pending_documents", array("transfer_id" => $data['transfer_id'], "document_name" => $data['document_name']));

// 							if (!$check_transfer_data->num_rows())
// 							{
// 								$this->db->insert("pending_documents",$data);
// 							}
// 						}     
// 						else
// 						{
// 							$this->db->insert("pending_documents",$data);
// 						}  
	                    
// 	                    /*$insert_client_charge_id = $this->db->insert_id();
// 	                    $this->create_invoice("change_charges", $_POST["company_code"]);*/
// 	                } 
// 	                else 
// 	                {   
// 	                	if($officer_id != null)
// 		           		{
// 		           			//echo json_encode($officer_id_data);
// 		           			$this->db->update("pending_documents",$data,array("triggered_by" => $result[$r]["triggered_by"], "client_id" => $get_client[0]["id"], "received_on" => "", "officer_id" => json_encode($officer_id_data), "document_name" => $data['document_name']));  
// 		           		}
// 		           		elseif($controller_id != null)
// 	           			{
// 	           				$this->db->update("pending_documents",$data,array("triggered_by" => $result[$r]["triggered_by"], "client_id" => $get_client[0]["id"], "received_on" => "", "controller_id" => $controller_id, "document_name" => $data['document_name'])); 
// 	           			}
// 	           			elseif($allotment_id != null)
// 	           			{
// 	           				$this->db->update("pending_documents",$data,array("triggered_by" => $result[$r]["triggered_by"], "client_id" => $get_client[0]["id"], "received_on" => "", "allotment_id" => json_encode($allotment_id_data), "document_name" => $data['document_name'])); 
// 	           			}
// 	           			elseif($buyback_id != null)
// 	           			{
// 	           				$this->db->update("pending_documents",$data,array("triggered_by" => $result[$r]["triggered_by"], "client_id" => $get_client[0]["id"], "received_on" => "", "buyback_id" => json_encode($buyback_id_data), "document_name" => $data['document_name'])); 
// 	           			}
// 	           			elseif($transfer_id != null)
// 	           			{
// 	           				$this->db->update("pending_documents",$data,array("triggered_by" => $result[$r]["triggered_by"], "client_id" => $get_client[0]["id"], "received_on" => "", "transfer_id" => json_encode($transfer_id_data), "document_name" => $data['document_name'])); 
// 	           			}
// 	           			elseif($charge_id != null)
// 	           			{
// 	           				$this->db->update("pending_documents",$data,array("triggered_by" => $result[$r]["triggered_by"], "client_id" => $get_client[0]["id"], "received_on" => "", "charge_id" => json_encode($charge_id_data), "document_name" => $data['document_name'])); 
// 	           			}
// 	           			elseif($filing_id != null)
// 	           			{
// 	           				$this->db->update("pending_documents",$data,array("triggered_by" => $result[$r]["triggered_by"], "client_id" => $get_client[0]["id"], "received_on" => "", "filing_id" => $filing_id)); 
// 	           			}
// 	           			elseif($guarantee_id != null)
// 	           			{
// 	           				$this->db->update("pending_documents",$data,array("triggered_by" => $result[$r]["triggered_by"], "client_id" => $get_client[0]["id"], "received_on" => "", "guarantee_id" => $guarantee_id)); 
// 	           			}
// 		           		elseif($officer_id == null && $controller_id == null && $allotment_id == null && $buyback_id == null && $transfer_id == null && $charge_id == null && $filing_id == null && $guarantee_id == null)
// 		           		{
// 		           			$this->db->update("pending_documents",$data,array("triggered_by" => $result[$r]["triggered_by"], "client_id" => $get_client[0]["id"], "received_on" => ""));  
// 		           		}
	                       
// 	                }

// 				}
// 			}
// 		}

// 	}
//-------------------------------------------create_document_end------------------------------------------------------//
	// public function create_invoice($type, $company_code, $position_id = null)
	// {
	// 	//$type = "change_charges";
	// 	if($type == "change_address")
	// 	{
	// 		$result = $this->db->query("select client_billing_info.* from client_billing_info left join client on client.company_code = client_billing_info.company_code where client_billing_info.service = 3 AND client_billing_info.company_code='".$company_code."' AND client.auto_generate = 1");
	// 	}
	// 	elseif($type == "change_charges")
	// 	{
	// 		$result = $this->db->query("select client_billing_info.* from client_billing_info left join client on client.company_code = client_billing_info.company_code where client_billing_info.service = 7 AND client_billing_info.company_code='".$company_code."' AND client.auto_generate = 1");
	// 	}
	// 	elseif($type == "change_company_name")
	// 	{
	// 		$result = $this->db->query("select client_billing_info.* from client_billing_info left join client on client.company_code = client_billing_info.company_code where client_billing_info.service = 8 AND client_billing_info.company_code='".$company_code."' AND client.auto_generate = 1");
	// 	}
	// 	elseif($type == "change_director")
	// 	{
	// 		$result = $this->db->query("select client_billing_info.* from client_billing_info left join client on client.company_code = client_billing_info.company_code where client_billing_info.service = 4 AND client_billing_info.company_code='".$company_code."' AND client.auto_generate = 1");
	// 	}
	// 	elseif($type == "change_secretary")
	// 	{
	// 		$result = $this->db->query("select client_billing_info.* from client_billing_info left join client on client.company_code = client_billing_info.company_code where client_billing_info.service = 6 AND client_billing_info.company_code='".$company_code."' AND client.auto_generate = 1");
	// 	}
	// 	elseif($type == "change_auditor") 
	// 	{
	// 		$result = $this->db->query("select client_billing_info.* from client_billing_info left join client on client.company_code = client_billing_info.company_code where client_billing_info.service = 5 AND client_billing_info.company_code='".$company_code."' AND client.auto_generate = 1");
	// 	}
	// 	elseif($type == "share_allotment")
	// 	{
	// 		$result = $this->db->query("select client_billing_info.* from client_billing_info left join client on client.company_code = client_billing_info.company_code where client_billing_info.service = 9 AND client_billing_info.company_code='".$company_code."' AND client.auto_generate = 1");
	// 	}
	// 	elseif($type == "share_transfer")
	// 	{
	// 		$result = $this->db->query("select client_billing_info.* from client_billing_info left join client on client.company_code = client_billing_info.company_code where client_billing_info.service = 10 AND client_billing_info.company_code='".$company_code."' AND client.auto_generate = 1");
	// 	}
	// 	elseif($type == "share_buyback")
	// 	{
	// 		$result = $this->db->query("select client_billing_info.* from client_billing_info left join client on client.company_code = client_billing_info.company_code where client_billing_info.service = 11 AND client_billing_info.company_code='".$company_code."' AND client.auto_generate = 1");
	// 	}
	// 	elseif($type == "newly_incorporated")
	// 	{
	// 		$result = $this->db->query("select client_billing_info.* from client_billing_info left join client on client.company_code = client_billing_info.company_code where client_billing_info.service = 12 AND client_billing_info.company_code='".$company_code."' AND client.auto_generate = 1");
	// 	}

	// 	$result = $result->result_array();
	// 	//echo json_encode($result);
	// 	if($result) 
	// 	{
	// 		//echo json_encode($result[0]['service']);
	// 		$now = getDate();

	// 		$current_date = DATE("Y-m-d",now());

	// 		$billing_result = $this->db->query("select * from billing where date_format(created_at, '%Y-%m-%d') = '".$current_date."' AND company_code='".$company_code."' AND outstanding != 0.00 AND status != 1");

	// 		$billing_result = $billing_result->result_array();

	// 		$client = $this->db->query("select * from client where company_code='".$company_code."'");

	// 		$client = $client->result_array();

	// 		$firm = $this->db->query("select * from firm where id = '".$client[0]["firm_id"]."'");

	// 		$firm = $firm->result_array();

	// 		if($firm[0]["gst_checkbox"] == 1)
	// 		{
	// 			if($firm[0]["gst_date"] != null)
	// 			{
	// 				$array = explode('/', $firm[0]["gst_date"]);
	// 				$tmp = $array[0];
	// 				$array[0] = $array[1];
	// 				$array[1] = $tmp;
	// 				unset($tmp);
	// 				$gst_date = implode('/', $array);
	// 				$time = strtotime($gst_date);
	// 				$gst_date = date('Y-m-d',$time);
 //                	$gst_date = strtotime($gst_date);
	// 			}

	// 			if($firm[0]["previous_gst_date"] != null)
	// 			{
	// 				$array = explode('/', $firm[0]["previous_gst_date"]);
	// 				$tmp = $array[0];
	// 				$array[0] = $array[1];
	// 				$array[1] = $tmp;
	// 				unset($tmp);
	// 				$previous_gst_date = implode('/', $array);
	// 				$time = strtotime($previous_gst_date);
	// 				$previous_gst_date = date('Y-m-d',$time);
 //                	$previous_gst_date = strtotime($gst_date);
	// 			}

	// 			//echo json_encode($previous_gst_date > $gst_date);
	// 			$invoice_date = DATE("Y-m-d",now());
	// 			$invoice_date = strtotime($invoice_date);

	// 			if($previous_gst_date == null && $gst_date != null)
	// 			{
	// 				if($invoice_date >= $gst_date)
	// 				{
	// 					$billing_service['gst_rate'] = $firm[0]["gst"];
	// 				}
	// 				else
	// 				{
	// 					$billing_service['gst_rate'] = 0;
	// 				}
	// 			}
	// 			else
	// 			{
	// 				if($previous_gst_date == $gst_date)
	// 				{
	// 					$billing_service['gst_rate'] = $firm[0]["gst"];
	// 				}
	// 				else if($previous_gst_date > $gst_date)
	// 				{
	// 					if($previous_gst_date > $invoice_date && $invoice_date >= $gst_date)
	// 					{
	// 						$billing_service['gst_rate'] = $firm[0]["gst"];
	// 					}
	// 					else if($invoice_date >= $previous_gst_date)
	// 					{
	// 						$billing_service['gst_rate'] = $firm[0]["previous_gst"];
	// 					}
	// 					else
	// 					{
	// 						$billing_service['gst_rate'] = 0;
	// 					}
	// 				}
	// 				else if($gst_date > $previous_gst_date)
	// 				{
	// 					if($gst_date > $invoice_date && $invoice_date >= $previous_gst_date)
	// 					{
	// 						$billing_service['gst_rate'] = $firm[0]["previous_gst"];
	// 					}
	// 					else if($invoice_date >= $gst_date)
	// 					{
	// 						$billing_service['gst_rate'] = $firm[0]["gst"];
	// 					}
	// 					else
	// 					{
	// 						$billing_service['gst_rate'] = 0;
	// 					}
	// 				}
	// 			}
				
	// 		}
	// 		else
	// 		{
	// 			$billing_service['gst_rate'] = 0;
	// 		}
			
	// 		if($billing_result)
	// 		{
	// 			$billing['amount'] = $billing_result[0]['amount'] + ((1+($billing_service['gst_rate'] / 100)) * $result[0]['amount']);
	// 			$billing['outstanding'] = $billing_result[0]['outstanding'] + ((1+($billing_service['gst_rate'] / 100)) * $result[0]['amount']);

	// 			$this->db->update("billing",$billing,array("id" => $billing_result[0]['id']));


	// 			$billing_service['billing_id'] = $billing_result[0]['id'];
	// 		}
	// 		else
	// 		{
	// 			//$num_row_billing_table = $this->db->query("select COUNT(*) from billing where company_code='".$company_code."'");
	// 			//echo json_encode($num_row_billing_table->result_array());

	// 			// $query_invoice_no = $this->db->query("SELECT invoice_no FROM billing where id = (SELECT max(id) FROM billing where status = '0' and firm_id = '".$this->session->userdata('firm_id')."')");
	// 	        //$id = $query->row()->id;

	// 	        $query_invoice_no = $this->db->query("select id, invoice_no, MAX(CAST(SUBSTRING(invoice_no, -4) AS UNSIGNED)) as latest_invoice_no from billing where status = '0' and firm_id = '".$this->session->userdata('firm_id')."' GROUP BY invoice_no ORDER BY latest_invoice_no DESC LIMIT 1");

	// 	        if ($query_invoice_no->num_rows() > 0) 
	// 	        {
	// 	            $query_invoice_no = $query_invoice_no->result_array();

	// 	            // $last_section_invoice_no = (int)$query_invoice_no[0]["invoice_no"] + 1;
	// 	            // $number = "AB-".date("Y")."-".str_pad($last_section_invoice_no,4,"0",STR_PAD_LEFT);

	// 	            $last_section_invoice_no = (string)$query_invoice_no[0]["invoice_no"];
	// 	            //echo (substr_replace($last_section_invoice_no, "", -1));
	// 	            //$number = substr_replace($last_section_invoice_no, "", -1).((int)($last_section_invoice_no[strlen($last_section_invoice_no)-1]) + 1);
	// 	            $number = substr_replace($last_section_invoice_no, "", -4).(str_pad((int)(substr($last_section_invoice_no, -4)) + 1, 4, '0', STR_PAD_LEFT));

	// 	        }
	// 	        else
 //                {
 //                    $number = "AB-".date("Y")."-".str_pad(1,4,"0",STR_PAD_LEFT);
 //                }

	// 			// $query_invoice_no = $this->db->query("select MAX(CAST(SUBSTRING(invoice_no,10, length(invoice_no)-9) AS UNSIGNED)) as invoice_no from billing");

 //    //             //echo json_encode($query_test);

 //    //             if ($query_invoice_no->num_rows() > 0) 
 //    //             {
 //    //                 $query_invoice_no = $query_invoice_no->result_array();
 //    //                 //$array_invoice_no = explode('-', $query_invoice_no[0]["invoice_no"]);
 //    //         		$last_section_invoice_no = (int)$query_invoice_no[0]["invoice_no"] + 1;
 //    //                 $number = date("Y")."-ABC-".$last_section_invoice_no;

 //    //             }
 //    //             else
 //    //             {
 //    //                 $number = date("Y")."-ABC-1";
 //    //             }
	// 			/*$number = sprintf('%02d', $now[0]);
	// 			$number = 'INV - '.$number;*/
	// 			$billing['firm_id'] = $client[0]["firm_id"];
	// 			$billing['invoice_no'] = $number;
	// 			$billing['currency_id'] = 1;
	// 			$billing['company_code'] = $company_code;
	// 			$billing['invoice_date'] = DATE("d/m/Y",now());
	// 			$billing['rate'] = 1.0000;
	// 			$billing['amount'] = ((1+($billing_service['gst_rate'] / 100)) * $result[0]['amount']);
	// 			$billing['outstanding'] = ((1+($billing_service['gst_rate'] / 100)) * $result[0]['amount']);

	// 			//$billing_service['client_billing_info_id'] = $result[0]['client_billing_info_id'];

	// 			$this->db->insert("billing",$billing);
	// 			$billing_service['billing_id'] = $this->db->insert_id();

				

	// 		}
	// 		$billing_service['service'] = $result[0]['service'];
	// 		$billing_service['invoice_date'] = DATE("d/m/Y",now());
	// 		//$billing_service['client_billing_info_id'] = $result[0]['client_billing_info_id'];
	// 		$billing_service['invoice_description'] = $result[0]['invoice_description'];
	// 		$billing_service['amount'] = $result[0]['amount'];

	// 		$this->db->insert("billing_service",$billing_service);
			

	// 		//echo true;

	// 	}
	// }

	public function save_capital(){
		$unique_code = $_POST['unique_code'];
		$issued_amount_member = $_POST['issued_amount_member'];
		$no_of_share_member = $_POST['no_of_share_member'];
		$issued_currency_member = $_POST['issued_currency_member'];
		$issued_sharetype_member = $_POST['issued_sharetype_member'];
		// echo count($issued_amount_member);
		
				$this->db->delete("issued_sharetype",array("unique_code" => $unique_code));
		for ($i=0;$i<count($issued_amount_member);$i++)
		{
			$issued_sharetype = [];
			$issued_sharetype['unique_code'] = $unique_code;
			$issued_sharetype['issued_amount_member'] = $this->sma->remove_comma($issued_amount_member[$i]);
			$issued_sharetype['no_of_share_member'] = $this->sma->remove_comma($no_of_share_member[$i]);
			$issued_sharetype['issued_currency_member'] = $issued_currency_member[$i];
			$issued_sharetype['issued_sharetype_member'] = $issued_sharetype_member[$i];
			// print_r($issued_sharetype);
			// $q = $this->db->get_where("issued_sharetype", array("unique_code" => $unique_code, "issued_amount_member" => $issued_amount_member[$i],"no_of_share_member" => $no_of_share_member[$i],"issued_currency_member" => $issued_currency_member[$i]));
			// if (!$q->num_rows())
			// {
				$this->db->insert("issued_sharetype",$issued_sharetype);
			// } else {
				// $this->db->update("issued_sharetype",$issued_sharetype,array("unique_code" => $unique_code, "issued_amount_member" => $issued_sharetype['issued_amount_member'],"no_of_share_member" => $issued_sharetype['no_of_share_member'],"issued_currency_member" => $issued_currency_member[$i]));
			// }
		}
		$paid_share= '';
		$paid_amount_member = $_POST['paid_amount_member'];
		$paid_no_of_share_member = $_POST['paid_no_of_share_member'];
		$paid_currency_member = $_POST['paid_currency_member'];
		$paid_sharetype_member = $_POST['paid_sharetype_member'];
				$this->db->delete("paid_share",array("unique_code" => $unique_code));
		for ($i=0;$i<count($paid_amount_member);$i++)
		{
			$paid_share = [];
			$paid_share['unique_code'] = $unique_code;
			$paid_share['paid_amount_member'] = $this->sma->remove_comma($paid_amount_member[$i]);
			$paid_share['paid_no_of_share_member'] = $this->sma->remove_comma($paid_no_of_share_member[$i]);
			$paid_share['paid_currency_member'] = $paid_currency_member[$i];
			$paid_share['paid_sharetype_member'] = $paid_sharetype_member[$i];
			// $q = $this->db->get_where("paid_share", array("unique_code" => $unique_code, "paid_amount_member" => $paid_share['paid_amount_member'],"paid_no_of_share_member" => $paid_share['paid_no_of_share_member'],"paid_currency_member" => $paid_currency_member[$i]));
			// if (!$q->num_rows())
			// {
				$this->db->insert("paid_share",$paid_share);
			// } else {
				// $this->db->update("paid_share",$paid_share,array("unique_code" => $unique_code, "paid_amount_member" => $paid_share['paid_amount_member'],"paid_no_of_share_member" => $paid_share['paid_no_of_share_member'],"paid_currency_member" => $paid_currency_member[$i]));
			// }
		}
		$member_capital= '';
		$nama_member_capital = $_POST['nama_member_capital'];
		$sharetype_member = $_POST['sharetype_member'];
		$shares_member_capital = $_POST['shares_member_capital'];
		$no_share_paid_member_capital = $_POST['no_share_paid_member_capital'];
		$gid_member_capital = $_POST['gid_member_capital'];
		$currency_member_capital = $_POST['currency_member_capital'];
		$amount_share_member_capital = $_POST['amount_share_member_capital'];
		$amount_share_paid_member_capital = $_POST['amount_share_paid_member_capital'];
				$this->db->delete("member_capital",array("unique_code" => $unique_code));
		for ($i=0;$i<count($nama_member_capital);$i++)
		{
			$member_capital = [];
			$member_capital['unique_code'] = $unique_code;
			$member_capital['nama_member_capital'] = $nama_member_capital[$i];
			$member_capital['sharetype_member'] = $sharetype_member[$i];
			$member_capital['shares_member_capital'] = $this->sma->remove_comma($shares_member_capital[$i]);
			$member_capital['no_share_paid_member_capital'] = $this->sma->remove_comma($no_share_paid_member_capital[$i]);
			$member_capital['gid_member_capital'] = $gid_member_capital[$i];
			$member_capital['currency_member_capital'] = $currency_member_capital[$i];
			$member_capital['amount_share_member_capital'] = $this->sma->remove_comma($amount_share_member_capital[$i]);
			$member_capital['amount_share_paid_member_capital'] = $this->sma->remove_comma($amount_share_paid_member_capital[$i]);
			// $q = $this->db->get_where("member_capital", array("unique_code" => $unique_code, "nama_member_capital" => $nama_member_capital[$i]));
			// if (!$q->num_rows())
			$pho = '';
            if ($_FILES['upload_certificate'.($i+1)]['size'] > 0) {
				$config['upload_path'] = './uploads/';
				$config['allowed_types'] = 'gif|jpg|png|pdf';
				// $config['max_size']     = '100000';
				// $config['max_width'] = '1024';
				// $config['max_height'] = '768';

				$this->load->library('upload', $config);

				// Alternately you can set preferences by calling the ``initialize()`` method. Useful if you auto-load the class:
				$this->upload->initialize($config);
				// print_r($this->upload->do_upload('upload_certificate'.($i+1)));
                if ($this->upload->do_upload("upload_certificate".($i+1)))
				{
					$pho=$this->upload->file_name;
				}
				$error = $this->upload->display_errors();
			}
				// print_r($pho);
				// print_r($error);
				// print_r($_FILES);
			if ($pho != '') $member_capital['certificate']=$pho;
			// {
				$this->db->insert("member_capital",$member_capital);
			// } else {
				// $this->db->update("member_capital",$member_capital,array("unique_code" => $unique_code, "nama_member_capital" => $nama_member_capital[$i]));
			// }
		}
		// $this->sma->print_arrays($member_capital);
        redirect("masterclient");
	}
	public function save_charges(){
		// $chargee= '';
		$unique_code = $_POST['unique_code'];
		$chargee_name = $_POST['chargee_name'];
		$chargee_nature_of = $_POST['chargee_nature_of'];
		$chargee_date_reg = $_POST['chargee_date_reg'];
		$chargee_no = $_POST['chargee_no'];
		$chargee_currency = $_POST['chargee_currency'];
		$chargee_amount = $_POST['chargee_amount'];
		$chargee_date_satisfied = $_POST['chargee_date_satisfied'];
		$chargee_satisfied_no = $_POST['chargee_satisfied_no'];
		$amount_share_paid_member_capital = $_POST['amount_share_paid_member_capital'];	
		$this->db->delete("chargee",array("unique_code" => $unique_code));
			
		for ($i=0;$i<count($chargee_name);$i++)
		{
			$chargee = [];
			$chargee['unique_code'] = $unique_code;
			$chargee['chargee_name'] = $chargee_name[$i];
			$chargee['chargee_nature_of'] = $chargee_nature_of[$i];
			$chargee['chargee_date_reg'] = $chargee_date_reg[$i];
			$chargee['chargee_no'] = $chargee_no[$i];
			$chargee['chargee_currency'] = $chargee_currency[$i];
			$chargee['chargee_amount'] = $chargee_amount[$i];
			$chargee['chargee_date_satisfied'] = $chargee_date_satisfied[$i];
			$chargee['chargee_satisfied_no'] = $chargee_satisfied_no[$i];
			// $q = $this->db->get_where("chargee", array("unique_code" => $unique_code, "chargee_name" => $chargee_name[$i]));
			// if (!$q->num_rows())
			// {
				$this->db->insert("chargee",$chargee);
			// } else {
				// $this->db->update("chargee",$chargee,array("unique_code" => $unique_code, "chargee_name" => $chargee_name[$i]));
			// }
		}
		// echo "<pre>";
		// print_r($chargee);
		// echo "</pre>";
		$this->session->set_userdata('open_unique_code',$unique_code);
        redirect("masterclient/edit");
	}
	public function save_other(){
		$unique_code = $_POST['unique_code'];
		// print_r($_POST);
		$client_others = [];
		$client_others['unique_code'] = $unique_code;
		$client_others['type_of_doc'] = $_POST['typeofdoc'];
		$client_others['others_category'] = $_POST['doccategory'];
		$client_others['others_remarks'] = $_POST['others_remarks'];
		
			$pho = '';
            if ($_FILES['upload_file_others']['size'] > 0) {
				$config['upload_path'] = './uploads/';
				$config['allowed_types'] = 'gif|jpg|png|pdf';
				// $config['max_size']     = '100000';
				// $config['max_width'] = '1024';
				// $config['max_height'] = '768';

				$this->load->library('upload', $config);

				// Alternately you can set preferences by calling the ``initialize()`` method. Useful if you auto-load the class:
				$this->upload->initialize($config);
				// print_r($this->upload->do_upload('upload_certificate'.($i+1)));
                if ($this->upload->do_upload("upload_file_others"))
				{
					$pho=$this->upload->file_name;
				}
				$error = $this->upload->display_errors();
			}
				// print_r($pho);
				// print_r($error);
				// print_r($_FILES);
			if ($pho != '') $client_others['files']=$pho;
				
		$this->db->delete("client_others",array("unique_code" => $unique_code));
			$this->db->insert("client_others",$client_others);
		// print_r($client_others);
		$this->session->set_userdata('open_unique_code',$unique_code);
        redirect("masterclient/edit/");
	}
	public function save_setup()
	{
		$unique_code = $_POST['unique_code'];
		// print_r($_POST);
		$client_setup = [];
		$client_setup['unique_code'] = $unique_code;
		$client_setup['setup_chairman'] = $_POST['setup_chairman']?$_POST['setup_chairman']:'-';
		$client_setup['setup_director_signature1'] = $_POST['setup_director_signature1']?$_POST['setup_director_signature1']:'-';
		$client_setup['setup_director_signature2'] = $_POST['setup_director_signature2']?$_POST['setup_director_signature2']:'-';
		$this->db->delete("client_setup",array("unique_code" => $unique_code));
			$this->db->insert("client_setup",$client_setup);
		$this->db->delete("client_service",array("unique_code" => $unique_code));
		for($i=0;$i<count($_POST['service_name']);$i++)
		{
			if ($_POST['service_amount'][$i])
			{
		$client_service = [];
		$client_service['unique_code'] = $unique_code;
		$client_service['service_name'] = $_POST['service_name'][$i];
		$client_service['service_start_recurring'] = $this->sma->fsd($_POST['service_start_recurring'][$i]);
		$client_service['service_end_recurring'] = $this->sma->fsd($_POST['service_end_recurring'][$i]);
		$client_service['service_frequency'] = $_POST['service_frequency'][$i];
		$client_service['service_amount'] = $_POST['service_amount'][$i]?str_replace(',','',$_POST['service_amount'][$i]):0;
		// print_r($client_service);
			$this->db->insert("client_service",$client_service);
			}
			
		}
		$this->session->set_userdata('open_unique_code',$unique_code);
        redirect("masterclient/edit");
		// print_r($client_others);
		// $this->sma->print_arrays($client_setup,$client_service);
	}

	public function check_officer_data()
	{
		$check_date_of_appointment = [];
		$check_date_of_appointment[0]['date_of_appointment']=$_POST['date_of_appointment'][0];

		$check_date_of_cessation = [];
		$check_date_of_cessation[0]['date_of_cessation']=$_POST['date_of_cessation'][0];

		$query = $this->db->get_where("history_client_officers", array("id" => $_POST['client_officer_id'][0]));

		if (!$query->num_rows())//if don't have anythings
		{
			echo false;
		}
		else
		{
			$query = $query->result_array();

			$old_office_date_of_appointment_result = $this->db->query("select date_of_appointment from client_officers where id='".$_POST['client_officer_id'][0]."'");

			$old_office_date_of_appointment_result = $old_office_date_of_appointment_result->result_array();

			$get_client_info = $this->db->query("select * from client where company_code='".$_POST['company_code']."'");

			$get_client_info = $get_client_info->result_array();

			if(!($old_office_date_of_appointment_result == $check_date_of_appointment))
			{
				$pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $get_client_info[0]["id"], "officer_id" => json_encode(array($_POST['client_officer_id'][0])), "received_on" => "", "triggered_by" => "8"));
				if($pending_documents_query->num_rows())
				{
					echo true;
				}
				else
				{
					echo false;
				}
			}
			else
			{
				echo false;
			}

			$old_office_date_of_cessation_result = $this->db->query("select date_of_cessation from client_officers where id='".$_POST['client_officer_id'][0]."'");

			$old_office_date_of_cessation_result = $old_office_date_of_cessation_result->result_array();

			if(!($old_office_date_of_cessation_result == $check_date_of_cessation))
			{
				$pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $get_client_info[0]["id"], "officer_id" => json_encode(array($_POST['client_officer_id'][0])), "received_on" => "", "triggered_by" => "9"));
				if($pending_documents_query->num_rows())
				{
					echo true;
				}
				else
				{
					echo false;
				}
			}
			else
			{
				echo false;
			}
		}
	}

	public function add_officer ()
	{
		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		for($i = 0; $i < count($_POST['identification_register_no']); $i++ )
		{
			$this->form_validation->set_rules('identification_register_no['.$i.']', 'Id', 'required');
			$this->form_validation->set_rules('name['.$i.']', 'Name', 'required');
			$this->form_validation->set_rules('date_of_appointment['.$i.']', 'Date of appointment', 'required');

			$date_of_appointment = strtotime(str_replace('/', '-',$_POST['date_of_appointment'][$i]));
			$date_of_cessation = strtotime(str_replace('/', '-',$_POST['date_of_cessation'][$i]));

			if(isset($_POST['alternate_of'][$i]))
			{
				$alternate_of = $_POST['alternate_of'][$i];
			}
			else
			{
				$alternate_of = null;
			}

	        if ($this->form_validation->run() == FALSE || $date_of_cessation < $date_of_appointment && $date_of_cessation != "" || $alternate_of == "0" || $_POST['position'][$i] == "0")
	        {
	        	$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

	        	if($date_of_cessation < $date_of_appointment && $date_of_cessation != "")
	        	{
	        		 $validate_date_of_cessation = "Date of cessation should be on or after date appointment of this officer.";
	        	}
	        	else
	        	{
	        		$validate_date_of_cessation = "";
	        	}

	        	if($alternate_of == "0")
	        	{
	        		 $validate_alternate_of = "*The Alternate of field is required.";
	        	}
	        	else
	        	{
	        		$validate_alternate_of = "";
	        	}

	        	if($_POST['position'][$i] == "0")
	        	{
	        		 $validate_position = "The Position field is required.";
	        	}
	        	else
	        	{
	        		 $validate_position = "";
	        	}


	        	$error = array(
	        					'alternate_of' => $validate_alternate_of,
				                'identification_register_no' => strip_tags(form_error('identification_register_no['.$i.']')),
				                'name' => strip_tags(form_error('name['.$i.']')),
				                'date_of_appointment' => strip_tags(form_error('date_of_appointment['.$i.']')),
				                'date_of_cessation' => $validate_date_of_cessation,
				                'position' => $validate_position,
				            );

	        	if($date_of_cessation < $date_of_appointment && $date_of_cessation != "")
	        	{
	        		echo json_encode(array("Status" => 0, 'message' => 'Date of cessation should be on or after date appointment of this officer.', 'title' => 'Error', "error" => $error));
	        	}
	        	else
	        	{
	        		echo json_encode(array("Status" => 0, 'message' => 'Please complete all required field.', 'title' => 'Error', "error" => $error));
	        	}

	        }
	        else
	        {

	        	if ($_POST['position'][$i] == "5")
				{
					$q = $this->db->query("select * from officer_company");
					// where register_no='".$_POST['identification_register_no'][$i]."'
					if ($q->num_rows() > 0) {

						$officer_company_info = $q->result_array();

                        foreach ($officer_company_info as $officer_company_info_row) {
                            if(strtoupper($this->encryption->decrypt($officer_company_info_row["register_no"])) == strtoupper($_POST['identification_register_no'][$i]))
                            {
                                $officer_company_info_data = $officer_company_info_row;
                            }
                        }

                        if($officer_company_info_data != null)
                        {
							$have_this_member = 1;
                        }
                        else
                        {
                        	$have_this_member = 0;
                        }
			        } 
			        else 
			       	{
			       		$have_this_member = 0;
			       	}
				}
				else
				{
					//$q = $this->db->query("select * from officer where identification_no='".$identification_register_no."'");

					$q = $this->db->query("select * from officer where YEAR(CURDATE()) - YEAR(date_of_birth) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(date_of_birth), '-', DAY(date_of_birth)) ,'%Y-%c-%e') > CURDATE(), 1, 0) > 18");
					// AND identification_no='".$_POST['identification_register_no'][$i]."'
					if ($q->num_rows() > 0) 
					{
						$officer_info = $q->result_array();

                        foreach ($officer_info as $officer_info_row) {
                            if(strtoupper($this->encryption->decrypt($officer_info_row["identification_no"])) == strtoupper($_POST['identification_register_no'][$i]))
                            {
                                $officer_info_data = $officer_info_row;
                            }
                        }

			            //$q = $q->result_array();
                        if($officer_info_data != null)
                        {
							list($day, $month, $year) = explode('/', $_POST['date_of_appointment'][$i]);
							$get_date_of_appointment = mktime(0, 0, 0, $month, $day, $year);

							$date_of_birth =  strtotime($officer_info_data["date_of_birth"]);
								
							if($date_of_birth > $get_date_of_appointment)
							{
								$have_this_member = 2;
							}
							else
							{
								$have_this_member = 1;
							}
						}
						else
						{
							$have_this_member = 0;
						}
			        } 
			        else 
			        {
			        	$have_this_member = 0;
			        }
				}

				if($have_this_member == 1)
				{
					$this->db->select('client_officers_position.position');
			        $this->db->from('client_officers_position');
			        $this->db->where('id', $_POST['position'][$i]);
					$office_position = $this->db->get();
					$office_position_array = $office_position->result_array();

					$data['company_code']=$_POST['company_code'];
					$data['officer_id']=$_POST['officer_id'];
					$data['field_type']=$_POST['officer_field_type'];
					$data['position']=$_POST['position'][$i];
					if($alternate_of == null)
					{
						$data['alternate_of']='';
						$check_alternate_of = '';
					}
					else
					{
						$data['alternate_of']=$alternate_of;
						$check_alternate_of = $alternate_of;
					}
					
					$data['date_of_appointment']=$_POST['date_of_appointment'][$i];
					$data['date_of_cessation']=$_POST['date_of_cessation'][$i];

					$data['retiring'] = 0;

						$q = $this->db->get_where("client_officers", array("id" => $_POST['client_officer_id'][$i]));

						if (!$q->num_rows())
						{
							$date_of_appointment_array = explode('/', $data['date_of_appointment']);
							$date_of_appointment_tmp = $date_of_appointment_array[0];
							$date_of_appointment_array[0] = $date_of_appointment_array[1];
							$date_of_appointment_array[1] = $date_of_appointment_tmp;
							unset($date_of_appointment_tmp);
							$post_date_of_appointment = implode('/', $date_of_appointment_array);

							if ($_POST['position'][$i] == "7")
							{
								$check_alternate_of_date = $this->db->query("select * from client_officers where id='".$data['alternate_of']."' ORDER BY STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') DESC");

								$check_alternate_of_date = $check_alternate_of_date->result_array();
								//echo json_encode($check_alternate_of_date);
								$array = explode('/', $check_alternate_of_date[0]["date_of_appointment"]);
								$tmp = $array[0];
								$array[0] = $array[1];
								$array[1] = $tmp;
								unset($tmp);
								$alternate_of_date_of_appointment = implode('/', $array);

								if($check_alternate_of_date[0]["date_of_cessation"] != "")
								{
									$array = explode('/', $check_alternate_of_date[0]["date_of_cessation"]);
									$tmp = $array[0];
									$array[0] = $array[1];
									$array[1] = $tmp;
									unset($tmp);
									$alternate_of_date_of_cessation = implode('/', $array);

									//if(strtotime($alternate_of_date_of_appointment) >= )
								}

								if(strtotime($alternate_of_date_of_appointment) > strtotime($post_date_of_appointment))
								{
									$overlapped = true;
								}
								else
								{
									$overlapped = false;
								}
								//$new_date_of_appointment = strtotime($new_date_of_appointment);

							}
							else
							{
								$overlapped = false;
							}

							if(!$overlapped)
							{
								$check_date = $this->db->query("select * from client_officers where position='".$data['position']."' AND alternate_of = '".$data['alternate_of']."' AND officer_id = '".$data['officer_id']."' AND field_type = '".$data['field_type']."' AND company_code = '".$data['company_code']."' ORDER BY STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') DESC LIMIT 2");

								$check_date = $check_date->result_array();
								//echo json_encode($check_date);
								if(count($check_date) > 0)
								{
									if(count($check_date) == 1)
									{
										$date_of_cessation = $check_date[0]["date_of_cessation"];
										$date_of_appointment = $check_date[0]["date_of_appointment"];
									}
									else
									{
										$date_of_cessation = $check_date[0]["date_of_cessation"];
										$date_of_appointment = $check_date[0]["date_of_appointment"];
									}
								}
								else
								{
									$date_of_cessation = null;
									$date_of_appointment = null;
								}

								if($date_of_cessation == null && count($check_date) != 0)
								{
									$array = explode('/', $date_of_appointment);
									$tmp = $array[0];
									$array[0] = $array[1];
									$array[1] = $tmp;
									unset($tmp);
									$new_date = implode('/', $array);
									$new_date_of_appointment = strtotime($new_date);
									//$new_date_of_registration = date('d/m/Y',$time);

									if($data['date_of_cessation'] != "")
									{
										$date_of_cessation_array = explode('/', $data['date_of_cessation']);
										$date_of_cessation_tmp = $date_of_cessation_array[0];
										$date_of_cessation_array[0] = $date_of_cessation_array[1];
										$date_of_cessation_array[1] = $date_of_cessation_tmp;
										unset($date_of_cessation_tmp);
										$post_date_of_cessation = implode('/', $date_of_cessation_array);
									}

									if($data['date_of_cessation'] != "")
									{
										if( strtotime($post_date_of_cessation) >= $new_date_of_appointment)
										{
											echo json_encode(array("Status" => 2, 'message' => 'This person has not ceased to hold the position in the company.', 'title' => 'Error', 'data' => $check_date));
										}
										else
										{
											$this->db->insert("client_officers",$data);
											$insert_client_officers_id = $this->db->insert_id();
											$this->save_audit_trail("Clients", "Officers", $office_position_array[0]["position"]." ".$name[0]." is added.", $_POST['company_code']);

											echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_client_officers_id" => $insert_client_officers_id));
										}
									}
									else
									{
										echo json_encode(array("Status" => 2, 'message' => 'This person has not ceased to hold the position in the company.', 'title' => 'Error', 'data' => $check_date));
									}
									
									
								}
								elseif($date_of_cessation != null && count($check_date) != 0)
								{

									$array = explode('/', $check_date[0]["date_of_cessation"]);
									$tmp = $array[0];
									$array[0] = $array[1];
									$array[1] = $tmp;
									unset($tmp);
									$new_date = implode('/', $array);
									/*$time = strtotime($new_date);
									$new_date = date('d/m/Y',$time);*/

									if(strtotime($new_date) > strtotime($post_date_of_appointment))
									{
										echo json_encode(array("Status" => 2, 'message' => 'Date of appointment cannot early than old date of cessation.', 'title' => 'Error', 'data' => $check_date));
									}
									else
									{
										$this->db->insert("client_officers",$data);
										$insert_client_officers_id = $this->db->insert_id();
										$this->save_audit_trail("Clients", "Officers", $office_position_array[0]["position"]." ".$name[0]." is added.", $_POST['company_code']);

										echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_client_officers_id" => $insert_client_officers_id));
									}
				                	
								}
								else
								{
									$this->db->insert("client_officers",$data);
									$insert_client_officers_id = $this->db->insert_id();
									$this->save_audit_trail("Clients", "Officers", $office_position_array[0]["position"]." ".$name[0]." is added.", $_POST['company_code']);

									echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_client_officers_id" => $insert_client_officers_id));
								}
							}
							else
							{
								echo json_encode(array("Status" => 2, 'message' => 'The director has not been appointed on the appointment date of this alternate director.', 'title' => 'Error', 'data' => ""));
							}
						} 
						else 
						{
							$date_of_appointment_array = explode('/', $data['date_of_appointment']);
							$date_of_appointment_tmp = $date_of_appointment_array[0];
							$date_of_appointment_array[0] = $date_of_appointment_array[1];
							$date_of_appointment_array[1] = $date_of_appointment_tmp;
							unset($date_of_appointment_tmp);
							$post_date_of_appointment = implode('/', $date_of_appointment_array);

							if ($_POST['position'][$i] == "7")
							{
								$check_alternate_of_date = $this->db->query("select * from client_officers where id='".$data['alternate_of']."' ORDER BY id DESC");

								$check_alternate_of_date = $check_alternate_of_date->result_array();

								$array = explode('/', $check_alternate_of_date[0]["date_of_appointment"]);
								$tmp = $array[0];
								$array[0] = $array[1];
								$array[1] = $tmp;
								unset($tmp);
								$alternate_of_date_of_appointment = implode('/', $array);

								if($check_alternate_of_date[0]["date_of_cessation"] != "")
								{
									$array = explode('/', $check_alternate_of_date[0]["date_of_cessation"]);
									$tmp = $array[0];
									$array[0] = $array[1];
									$array[1] = $tmp;
									unset($tmp);
									$alternate_of_date_of_cessation = implode('/', $array);

									//if(strtotime($alternate_of_date_of_appointment) >= )
								}

								if(strtotime($alternate_of_date_of_appointment) > strtotime($post_date_of_appointment))
								{
									$overlapped = true;
								}
								else
								{
									$overlapped = false;
								}
								//$new_date_of_appointment = strtotime($new_date_of_appointment);

							}
							else
							{
								$overlapped = false;
							}

							if(!$overlapped)
							{
								$check_officer = [];
								$check_date_of_appointment = [];
								$check_date_of_cessation = [];
								//$check_officer[0]['position']=$_POST['position'][$i];
								$check_officer[0]['alternate_of']=$check_alternate_of;
								$check_officer[0]['officer_id']=$_POST['officer_id'];
								$check_officer[0]['field_type']=$_POST['officer_field_type'];
								$check_officer[0]['date_of_appointment']=$_POST['date_of_appointment'][$i];
								$check_officer[0]['date_of_cessation']=$_POST['date_of_cessation'][$i];

								$check_date_of_appointment[0]['date_of_appointment']=$_POST['date_of_appointment'][$i];

								$check_date_of_cessation[0]['date_of_cessation']=$_POST['date_of_cessation'][$i];

								$old_client_officer_result = $this->db->query("select alternate_of, officer_id, field_type, date_of_appointment, date_of_cessation from client_officers where id='".$_POST['client_officer_id'][$i]."'");

								$old_client_officer_result = $old_client_officer_result->result_array();

								$old_office_date_of_appointment_result = $this->db->query("select date_of_appointment from client_officers where id='".$_POST['client_officer_id'][$i]."'");

								$old_office_date_of_appointment_result = $old_office_date_of_appointment_result->result_array();

								$old_office_date_of_cessation_result = $this->db->query("select date_of_cessation from client_officers where id='".$_POST['client_officer_id'][$i]."'");

								$old_office_date_of_cessation_result = $old_office_date_of_cessation_result->result_array();


								$check_date = $this->db->query("select * from client_officers where position='".$data['position']."' AND alternate_of = '".$data['alternate_of']."' AND officer_id = '".$data['officer_id']."' AND field_type = '".$data['field_type']."' AND company_code = '".$data['company_code']."' AND id != '".$_POST['client_officer_id'][$i]."' ORDER BY STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') DESC LIMIT 2");

								$check_date = $check_date->result_array();

								$get_client_information = $this->db->query("select client.*, company_type.company_type as company_type_name from client left join company_type on client.company_type = company_type.id where company_code='".$data['company_code']."' order by id");

								$get_client_information = $get_client_information->result_array();

								$previous_director_name_appointment_result = $this->db->query("select client_officers.* from client_officers where position = '".$_POST['position'][$i]."' AND company_code='".$data['company_code']."' AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = STR_TO_DATE('".$old_office_date_of_appointment_result[0]["date_of_appointment"]."','%d/%m/%Y') order by id");
								//echo json_encode($check_date);
								$previous_director_name_appointment_result = $previous_director_name_appointment_result->result_array();

								$previous_director_name_cessation_result = $this->db->query("select client_officers.* from client_officers where position = '".$_POST['position'][$i]."' AND company_code='".$data['company_code']."' AND STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') = STR_TO_DATE('".$old_office_date_of_cessation_result[0]["date_of_cessation"]."','%d/%m/%Y')  order by id");
								//echo json_encode($check_date);
								$previous_director_name_cessation_result = $previous_director_name_cessation_result->result_array();
								
								if(count($check_date) > 0)
								{
									if(count($check_date) == 1)
									{
										$date_of_cessation = $check_date[0]["date_of_cessation"];
										$date_of_appointment = $check_date[0]["date_of_appointment"];
									}
									else
									{
										$date_of_cessation = $check_date[0]["date_of_cessation"];
										$date_of_appointment = $check_date[0]["date_of_appointment"];
									}
								}
								else
								{
									$date_of_cessation = null;
									$date_of_appointment = null;
								}

								if($date_of_cessation == null && count($check_date) != 0)
								{
									$array = explode('/', $date_of_appointment);
									$tmp = $array[0];
									$array[0] = $array[1];
									$array[1] = $tmp;
									unset($tmp);
									$new_date = implode('/', $array);
									$new_date_of_appointment = strtotime($new_date);
									//$new_date_of_registration = date('d/m/Y',$time);

									$date_of_cessation_array = explode('/', $data['date_of_cessation']);
									$date_of_cessation_tmp = $date_of_cessation_array[0];
									$date_of_cessation_array[0] = $date_of_cessation_array[1];
									$date_of_cessation_array[1] = $date_of_cessation_tmp;
									unset($date_of_cessation_tmp);
									$post_date_of_cessation = implode('/', $date_of_cessation_array);

									if($data['date_of_cessation'] != "")
									{
										if( strtotime($post_date_of_cessation) >= $new_date_of_appointment)
										{
											echo json_encode(array("Status" => 2, 'message' => 'This person has not ceased to hold the position in the company.', 'title' => 'Error', 'data' => $check_date));
										}
										else
										{
											$this->db->update("client_officers",$data,array("id" => $_POST['client_officer_id'][$i]));

											$this->save_audit_trail("Clients", "Officers", $office_position_array[0]["position"]." ".$name[0]." is edited.", $_POST['company_code']);

											if(!($old_office_date_of_appointment_result == $check_date_of_appointment))
											{
												$check_history_client_office = $this->db->get_where("history_client_officers", array("id" => $_POST['client_officer_id'][$i]));
												if (!$check_history_client_office->num_rows())
												{
													$w = $q->result();

													foreach($w as $r) {
												        $this->db->insert("history_client_officers",$r);
												    }
												} 
												else 
												{
													$x = $q->result_array();

													$data_history['date_of_appointment'] = $x[0]["date_of_appointment"];

												    $this->db->update("history_client_officers",$data_history,array("id" => $_POST['client_officer_id'][$i]));
													
												}
												$old_office_date_result = $old_office_date_of_appointment_result[0]["date_of_appointment"];
											}

											if(!($old_office_date_of_cessation_result == $check_date_of_cessation))
											{
												$check_history_client_office = $this->db->get_where("history_client_officers", array("id" => $_POST['client_officer_id'][$i]));
												if (!$check_history_client_office->num_rows())
												{
													$g = $q->result();

													foreach($g as $r) {
												        $this->db->insert("history_client_officers",$r);
												    }
												} 
												else 
												{
													$z = $q->result_array();

													$data_history['date_of_cessation'] = $z[0]["date_of_cessation"];

												    $this->db->update("history_client_officers",$data_history,array("id" => $_POST['client_officer_id'][$i]));
													
												}
												$old_office_date_result = $old_office_date_of_cessation_result[0]["date_of_cessation"];
											}

											echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
										}
									}
									else
									{
										echo json_encode(array("Status" => 2, 'message' => 'This person has not ceased to hold the position in the company.', 'title' => 'Error', 'data' => $check_date));
									}
									
									
								}
								elseif($date_of_cessation != null && count($check_date) != 0)
								{

									$array = explode('/', $check_date[0]["date_of_cessation"]);
									$tmp = $array[0];
									$array[0] = $array[1];
									$array[1] = $tmp;
									unset($tmp);
									$new_date = implode('/', $array);
									/*$time = strtotime($new_date);
									$new_date = date('d/m/Y',$time);*/

									if(strtotime($new_date) > strtotime($post_date_of_appointment))
									{
										echo json_encode(array("Status" => 2, 'message' => 'Date of appointment cannot early than old date of cessation.', 'title' => 'Error', 'data' => $check_date));
									}
									else
									{
										$this->db->update("client_officers",$data,array("id" => $_POST['client_officer_id'][$i]));

										$this->save_audit_trail("Clients", "Officers", $office_position_array[0]["position"]." ".$name[0]." is edited.", $_POST['company_code']);

										if(!($old_office_date_of_appointment_result == $check_date_of_appointment))
										{
											$check_history_client_office = $this->db->get_where("history_client_officers", array("id" => $_POST['client_officer_id'][$i]));
											if (!$check_history_client_office->num_rows())
											{
												$w = $q->result();

												foreach($w as $r) {
											        $this->db->insert("history_client_officers",$r);
											    }
											} 
											else 
											{
												$x = $q->result_array();

												$data_history['date_of_appointment'] = $x[0]["date_of_appointment"];

											    $this->db->update("history_client_officers",$data_history,array("id" => $_POST['client_officer_id'][$i]));
												
											}
											$old_office_date_result = $old_office_date_of_appointment_result[0]["date_of_appointment"];
										}

										if(!($old_office_date_of_cessation_result == $check_date_of_cessation))
										{
											$check_history_client_office = $this->db->get_where("history_client_officers", array("id" => $_POST['client_officer_id'][$i]));
											if (!$check_history_client_office->num_rows())
											{
												$g = $q->result();

												foreach($g as $r) {
											        $this->db->insert("history_client_officers",$r);
											    }
											} 
											else 
											{
												$z = $q->result_array();

												$data_history['date_of_cessation'] = $z[0]["date_of_cessation"];

											    $this->db->update("history_client_officers",$data_history,array("id" => $_POST['client_officer_id'][$i]));
												
											}
											$old_office_date_result = $old_office_date_of_cessation_result[0]["date_of_cessation"];
										}
										
										echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
									}
				                	
								}
								else
								{
									$this->db->update("client_officers",$data,array("id" => $_POST['client_officer_id'][$i]));

									$this->save_audit_trail("Clients", "Officers", $office_position_array[0]["position"]." ".$name[0]." is edited.", $_POST['company_code']);

									if(!($old_office_date_of_appointment_result == $check_date_of_appointment))
									{
										$check_history_client_office = $this->db->get_where("history_client_officers", array("id" => $_POST['client_officer_id'][$i]));
										if (!$check_history_client_office->num_rows())
										{
											$w = $q->result();

											foreach($w as $r) {
										        $this->db->insert("history_client_officers",$r);
										    }
										} 
										else 
										{
											$x = $q->result_array();

											$data_history['date_of_appointment'] = $x[0]["date_of_appointment"];

										    $this->db->update("history_client_officers",$data_history,array("id" => $_POST['client_officer_id'][$i]));
											
										}
										$old_office_date_result = $old_office_date_of_appointment_result[0]["date_of_appointment"];
									}

									if(!($old_office_date_of_cessation_result == $check_date_of_cessation))
									{
										$check_history_client_office = $this->db->get_where("history_client_officers", array("id" => $_POST['client_officer_id'][$i]));
										if (!$check_history_client_office->num_rows())
										{
											$g = $q->result();

											foreach($g as $r) {
										        $this->db->insert("history_client_officers",$r);
										    }
										} 
										else 
										{
											$z = $q->result_array();

											$data_history['date_of_cessation'] = $z[0]["date_of_cessation"];

										    $this->db->update("history_client_officers",$data_history,array("id" => $_POST['client_officer_id'][$i]));
											
										}
										$old_office_date_result = $old_office_date_of_cessation_result[0]["date_of_cessation"];
									}

									echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
								}
							}
							else
							{
								echo json_encode(array("Status" => 2, 'message' => 'The director has not been appointed on the appointment date of this alternate director.', 'title' => 'Error', 'data' => ""));
							}
						}
						
					//The person is already holding similar position on the date}
					
				}
				else if($have_this_member == 2)
				{
					echo json_encode(array("Status" => 2, 'message' => 'The appointment date cannot smaller than date of birth.', 'title' => 'Error'));
				}
				else
				{
					//echo ($have_this_member);
					if ($_POST['position'][$i] == "5")
					{
						echo json_encode(array("Status" => 2, 'message' => 'This person cannot under this position.', 'title' => 'Error'));
					}
					else
					{
						echo json_encode(array("Status" => 2, 'message' => 'This company cannot under this position.', 'title' => 'Error'));
					}
				}
			}
		}
	}

	public function delete_officer ()
	{
		$id = $_POST["client_officer_id"];
		$client_officer_name = $_POST["client_officer_name"];

		if($id != "")
		{
			$query = $this->db->get_where("client_officers", array("alternate_of" => $_POST['client_officer_id']));

			if ($query->num_rows())//if don't have anythings
			{
				echo json_encode(array("Status" => 2));
			}
			else
			{
				$officers_array = $query->result_array();

				$this->db->select('client_officers_position.position');
		        $this->db->from('client_officers');
		        $this->db->join('client_officers_position', 'client_officers_position.id = client_officers.position', 'left');
		        $this->db->where('client_officers.id', $id);
				$office_position = $this->db->get();
				$office_position_array = $office_position->result_array();

				$this->db->delete("history_client_officers",array('id'=>$id));

				$this->db->delete("client_officers",array('id'=>$id));

				$this->save_audit_trail("Clients", "Officers", $office_position_array[0]["position"]." ".$client_officer_name." is deleted.", $officers_array[0]["company_code"]);

				echo json_encode(array("Status" => 1));
				
			}
		}
		else
		{
			echo json_encode(array("Status" => 1));
		}
	}

	public function get_nominee_director_info()
	{
		$nominee_director_id = $_POST["nominee_director_id"];

        $get_edit_client_nominee_director_data = $this->db_model->getEditClientNomineeDirector($nominee_director_id);

        echo json_encode(array("status" => 1, "list_of_nominee_director" => $get_edit_client_nominee_director_data));
	}

	public function add_nominee_director()
	{
		//echo json_encode($_POST);
		$data['company_code']=$_POST['nomi_company_code'];
		$data['nd_officer_id']=$_POST['nd_officer_id'];
		$data['nd_officer_field_type']=$_POST['nd_officer_field_type'];
		$data['nd_date_entry']=$_POST['nd_date_entry'];
		$nominee_director_name=$_POST['nd_name'];

		$data['nomi_officer_id']=$_POST['nomi_officer_id'];
		$data['nomi_officer_field_type']=$_POST['nomi_officer_field_type'];
		$data['date_become_nominator']=$_POST['date_become_nominator'];
		$data['date_of_cessation']=$_POST['date_ceased_nominator'];

		$hidden_supporting_document = $_POST['nd_hidden_supporting_document'];
		$filesCount = count($_FILES['nd_supporting_document']['name']);
        $individual_attachment = array();
 
        $_FILES['supportDoc']['name'] = $_FILES['nd_supporting_document']['name'];
        $_FILES['supportDoc']['type'] = $_FILES['nd_supporting_document']['type'];
        $_FILES['supportDoc']['tmp_name'] = $_FILES['nd_supporting_document']['tmp_name'];
        $_FILES['supportDoc']['error'] = $_FILES['nd_supporting_document']['error'];
        $_FILES['supportDoc']['size'] = $_FILES['nd_supporting_document']['size'];

        $uploadPath = './uploads/supporting_doc';
        $config['upload_path'] = $uploadPath;
        $config['allowed_types'] = '*';
        
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if($this->upload->do_upload('supportDoc'))
        {
            $fileData = $this->upload->data();
            $individual_attachment[] = $fileData['file_name'];
        }
        $attachment = json_encode($individual_attachment);

        if($hidden_supporting_document != "")
        {
            $data['supporting_document'] = $hidden_supporting_document;
        }
        else
        {
            $data['supporting_document'] = $attachment;
        }

		$q = $this->db->get_where("client_nominee_director", array("id" => $_POST['client_nominee_director_id']));

		if (!$q->num_rows())
		{
			$this->db->insert("client_nominee_director",$data);
			$insert_client_nominee_director_id = $this->db->insert_id();

			$this->save_audit_trail("Clients", "Register of Nominee Director", "Nominee director ".$nominee_director_name." is added.", $_POST['nomi_company_code']);
		}
		else
		{
			$this->db->update("client_nominee_director",$data,array("id" => $_POST['client_nominee_director_id']));

			$this->save_audit_trail("Clients", "Register of Nominee Director", "Nominee director ".$nominee_director_name." is updated.", $_POST['nomi_company_code']);
		}

		$get_client_nominee_director_data = $this->db_model->getClientNomineeDirector($_POST['nomi_company_code']);

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "list_of_nominee_director" => $get_client_nominee_director_data));
	}

	public function check_controller_data()
	{
		$check_date_of_registration = [];
		$check_date_of_registration[0]['date_of_registration']=$_POST['date_of_registration'][0];

		$check_date_of_cessation = [];
		$check_date_of_cessation[0]['date_of_cessation']=$_POST['date_of_cessation'][0];

		$query = $this->db->get_where("history_client_controller", array("id" => $_POST['client_controller_id'][0]));

		if (!$query->num_rows())//if don't have anythings
		{
			echo false;
		}
		else
		{
			$query = $query->result_array();

			$old_office_date_of_registration_result = $this->db->query("select date_of_registration from client_controller where id='".$_POST['client_controller_id'][0]."'");

			$old_office_date_of_registration_result = $old_office_date_of_registration_result->result_array();

			$get_client_info = $this->db->query("select * from client where company_code='".$_POST['company_code']."'");

			$get_client_info = $get_client_info->result_array();

			if(!($old_office_date_of_registration_result == $check_date_of_registration))
			{
				$pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $get_client_info[0]["id"], "controller_id" => $_POST['client_controller_id'][0], "received_on" => "", "triggered_by" => "15"));
				if($pending_documents_query->num_rows())
				{
					echo true;
				}
				else
				{
					echo false;
				}
			}
			else
			{
				echo false;
			}

			$old_office_date_of_cessation_result = $this->db->query("select date_of_cessation from client_controller where id='".$_POST['client_controller_id'][0]."'");

			$old_office_date_of_cessation_result = $old_office_date_of_cessation_result->result_array();

			if(!($old_office_date_of_cessation_result == $check_date_of_cessation))
			{
				$pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $get_client_info[0]["id"], "controller_id" => $_POST['client_controller_id'][0], "received_on" => "", "triggered_by" => "16"));
				if($pending_documents_query->num_rows())
				{
					echo true;
				}
				else
				{
					echo false;
				}
			}
			else
			{
				echo false;
			}
		}
	}

	public function get_controller_info()
    {
        $controller_id = $_POST["controller_id"];

        $get_edit_client_controller_data = $this->db_model->getEditClientController($controller_id);

        echo json_encode(array("status" => 1, "list_of_controller" => $get_edit_client_controller_data));
    }

	public function add_controller ()
	{
		//echo json_encode($_POST);
		$data['company_code']=$_POST['company_code'];
		//$data['identification_type']=$_POST['identification_type'];
		$data['officer_id']=$_POST['officer_id'];
		$data['field_type']=$_POST['officer_field_type'];
		if(isset($_POST['entity_name']))
		{
			$controller_name=$_POST['entity_name'];
		}
		else if(isset($_POST['individual_controller_name']))
		{
			$controller_name=$_POST['individual_controller_name'];
		}

		// $data['date_of_birth']=$_POST['date_of_birth'];
		// $data['nationality_name']=strtoupper($_POST['nationality']);
		// $data['address']=strtoupper($_POST['controller_address']);

		$data['date_of_registration']=$_POST['date_appointed'];
		$data['date_of_notice']=$_POST['date_of_notice'];
		if(isset($_POST['radio_individual_confirm_registrable_controller']))
		{
			$data['is_confirm_by_reg_controller']=$_POST['radio_individual_confirm_registrable_controller'];
		}
		else if(isset($_POST['radio_corp_confirm_registrable_controller']))
		{
			$data['is_confirm_by_reg_controller']=$_POST['radio_corp_confirm_registrable_controller'];
		}
		
		$data['confirmation_received_date']=$_POST['date_confirmation'];
		$data['date_of_entry']=$_POST['date_of_entry'];
		$data['date_of_cessation']=$_POST['date_ceased'];

		$hidden_supporting_document = $_POST['hidden_supporting_document'];
		$filesCount = count($_FILES['supporting_document']['name']);
        $individual_attachment = array();
        //echo json_encode($_FILES['supporting_document']['name']);
        // for($i = 0; $i < $filesCount; $i++)
        // {   
        $_FILES['supportDoc']['name'] = $_FILES['supporting_document']['name'];
        $_FILES['supportDoc']['type'] = $_FILES['supporting_document']['type'];
        $_FILES['supportDoc']['tmp_name'] = $_FILES['supporting_document']['tmp_name'];
        $_FILES['supportDoc']['error'] = $_FILES['supporting_document']['error'];
        $_FILES['supportDoc']['size'] = $_FILES['supporting_document']['size'];

        $uploadPath = './uploads/supporting_doc';
        $config['upload_path'] = $uploadPath;
        $config['allowed_types'] = '*';
        
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if($this->upload->do_upload('supportDoc'))
        {
            $fileData = $this->upload->data();
            $individual_attachment[] = $fileData['file_name'];
        }
        $attachment = json_encode($individual_attachment);
        //}

        if($hidden_supporting_document != "")
        {
            $data['supporting_document'] = $hidden_supporting_document;
        }
        else
        {
            $data['supporting_document'] = $attachment;
        }

		$q = $this->db->get_where("client_controller", array("id" => $_POST['client_controller_id']));

		if (!$q->num_rows())
		{
			$this->db->insert("client_controller",$data);
			$insert_client_controller_id = $this->db->insert_id();

			$this->save_audit_trail("Clients", "Register of Controller", "Controller ".$controller_name." is added.", $_POST['company_code']);
		}
		else
		{
			$this->db->update("client_controller",$data,array("id" => $_POST['client_controller_id']));

			$this->save_audit_trail("Clients", "Register of Controller", "Controller ".$controller_name." is updated.", $_POST['company_code']);
		}

		$get_client_controller_data = $this->db_model->getClientController($_POST['company_code']);

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "list_of_controller" => $get_client_controller_data));

		// $identification_register_no = $_POST['identification_register_no'];
		// $name = $_POST['name'];

		// for($i = 0; $i < count($_POST['identification_register_no']); $i++ )
		// {
		// 	$this->form_validation->set_rules('identification_register_no['.$i.']', 'Id', 'required');
		// 	$this->form_validation->set_rules('name['.$i.']', 'Name', 'required');
		// 	$this->form_validation->set_rules('address['.$i.']', 'Address', 'required');
		// 	$this->form_validation->set_rules('date_of_registration['.$i.']', 'Date of registration', 'required');
		// 	$this->form_validation->set_rules('date_of_notice['.$i.']', 'Date of notice', 'required');
		// 	$this->form_validation->set_rules('confirmation_received_date['.$i.']', 'Confirmation received date', 'required');
		// 	$this->form_validation->set_rules('date_of_entry['.$i.']', 'Date of entry', 'required');

		// 	$date_of_registration = strtotime(str_replace('/', '-',$_POST['date_of_registration'][$i]));
		// 	$date_of_cessation = strtotime(str_replace('/', '-',$_POST['date_of_cessation'][$i]));

			 
	 //        if ($this->form_validation->run() == FALSE || $date_of_cessation < $date_of_registration && $date_of_cessation != "")
	 //        {
	 //        	$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

	 //        	if($date_of_cessation < $date_of_registration && $date_of_cessation != "")
	 //        	{
	 //        		 $validate_date_of_cessation = "The Date of cessation field must larger than Date of appointment.";
	 //        	}
	 //        	else
	 //        	{
	 //        		$validate_date_of_cessation = "";
	 //        	}

	 //        	$error = array(
		// 		                'identification_register_no' => strip_tags(form_error('identification_register_no['.$i.']')),
		// 		                'name' => strip_tags(form_error('name['.$i.']')),
		// 		                'address' => strip_tags(form_error('address['.$i.']')),
		// 		                'date_of_registration' => strip_tags(form_error('date_of_registration['.$i.']')),
		// 		                'date_of_notice' => strip_tags(form_error('date_of_notice['.$i.']')),
		// 		                'confirmation_received_date' => strip_tags(form_error('confirmation_received_date['.$i.']')),
		// 		                'date_of_entry' => strip_tags(form_error('date_of_entry['.$i.']')),
		// 		                'date_of_cessation' => $validate_date_of_cessation,
				                
		// 		            );

	 //        	if($date_of_cessation < $date_of_registration && $date_of_cessation != "")
	 //        	{
	 //        		echo json_encode(array("Status" => 0, 'message' => 'The Date of cessation field must larger than Date of appointment.', 'title' => 'Error', "error" => $error));
	 //        	}
	 //        	else
	 //        	{
	 //        		echo json_encode(array("Status" => 0, 'message' => 'Please complete all required field.', 'title' => 'Error', "error" => $error));
	 //        	}
	 //        }
	 //        else
	 //        {
		// 		$data['company_code']=$_POST['company_code'];
		// 		$data['officer_id']=$_POST['officer_id'];
		// 		$data['field_type']=$_POST['officer_field_type'];
		// 		$data['date_of_birth']=$_POST['date_of_birth'][$i];
		// 		$data['nationality_name']=strtoupper($_POST['nationality'][$i]);
		// 		$data['address']=strtoupper($_POST['address'][$i]);
		// 		$data['date_of_registration']=$_POST['date_of_registration'][$i];
		// 		$data['date_of_notice']=$_POST['date_of_notice'][$i];
		// 		$data['confirmation_received_date']=$_POST['confirmation_received_date'][$i];
		// 		$data['date_of_entry']=$_POST['date_of_entry'][$i];
		// 		$data['date_of_cessation']=$_POST['date_of_cessation'][$i];

		// 		$q = $this->db->get_where("client_controller", array("id" => $_POST['client_controller_id'][$i]));

		// 		if (!$q->num_rows())
		// 		{
		// 			$arrays = explode('/', $data['date_of_registration']);
		// 			$tmps = $arrays[0];
		// 			$arrays[0] = $arrays[1];
		// 			$arrays[1] = $tmps;
		// 			unset($tmps);
		// 			$new_date_of_registrations = implode('/', $arrays);

		// 			if($data['date_of_cessation'] != "")
		// 			{
		// 				$date_of_cessation_arrays = explode('/', $data['date_of_cessation']);
		// 				$date_of_cessation_tmps = $date_of_cessation_arrays[0];
		// 				$date_of_cessation_arrays[0] = $date_of_cessation_arrays[1];
		// 				$date_of_cessation_arrays[1] = $date_of_cessation_tmps;
		// 				unset($date_of_cessation_tmps);
		// 				$new_date_of_cessations = implode('/', $date_of_cessation_arrays);
		// 			}

		// 			$check_previous_date_appointment = $this->db->query("select * from client_controller where officer_id = '".$data['officer_id']."' AND field_type = '".$data['field_type']."' AND company_code = '".$data['company_code']."' ORDER BY  STR_TO_DATE(client_controller.date_of_registration,'%d/%m/%Y') DESC");

		// 			if(!$check_previous_date_appointment->num_rows())
		// 			{
		// 				$not_overlapped = true;
		// 			}
		// 			else
		// 			{
		// 				$check_previous_date_appointment = $check_previous_date_appointment->result_array();

		// 				$arrays = explode('/', $check_previous_date_appointment[0]["date_of_registration"]);
		// 				$tmps = $arrays[0];
		// 				$arrays[0] = $arrays[1];
		// 				$arrays[1] = $tmps;
		// 				unset($tmps);
		// 				$new_previous_date_registration = implode('/', $arrays);

		// 				if(strtotime($new_previous_date_registration) > strtotime($new_date_of_registrations) && $data['date_of_cessation'] == "")
		// 				{
		// 					$not_overlapped = false;
		// 				}
		// 				elseif (strtotime($new_date_of_cessations) > strtotime($new_previous_date_registration) && $data['date_of_cessation'] != "")
		// 				{
		// 					//echo json_encode($new_date_of_cessations);
		// 					$not_overlapped = false;
		// 				}
		// 				else
		// 				{
		// 					$not_overlapped = true;
		// 				}
		// 			}
					

		// 			if($not_overlapped)
		// 			{
		// 				$check_date = $this->db->query("select * from client_controller where officer_id = '".$data['officer_id']."' AND field_type = '".$data['field_type']."' AND company_code = '".$data['company_code']."' ORDER BY STR_TO_DATE(client_controller.date_of_registration,'%d/%m/%Y') DESC LIMIT 2");

		// 				$check_date = $check_date->result_array();
		// 				if(count($check_date) == 1)
		// 				{
		// 					$date_of_cessation = $check_date[0]["date_of_cessation"];
		// 					$date_of_registration = $check_date[0]["date_of_registration"];
		// 				}
		// 				else
		// 				{
		// 					$date_of_cessation = $check_date[0]["date_of_cessation"];
		// 					$date_of_registration = $check_date[0]["date_of_registration"];
		// 				}

		// 				if($date_of_cessation == null && count($check_date) != 0)
		// 				{
		// 					$array = explode('/', $date_of_registration);
		// 					$tmp = $array[0];
		// 					$array[0] = $array[1];
		// 					$array[1] = $tmp;
		// 					unset($tmp);
		// 					$new_date = implode('/', $array);
		// 					$new_date_of_registration = strtotime($new_date_of_registration);

		// 					if($new_date_of_cessations != "")
		// 					{
		// 						if( strtotime($new_date_of_cessations) >= $new_date_of_registration)
		// 						{
		// 							echo json_encode(array("Status" => 2, 'message' => 'This person has not ceased to hold the position in the company.', 'title' => 'Error', 'data' => $check_date));
		// 						}
		// 						else
		// 						{
		// 							$this->db->insert("client_controller",$data);
		// 							$insert_client_controller_id = $this->db->insert_id();

		// 							echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_client_controller_id" => $insert_client_controller_id));
		// 						}
		// 					}
		// 					else
		// 					{
		// 						echo json_encode(array("Status" => 2, 'message' => 'This person has not ceased to hold the position in the company.', 'title' => 'Error', 'data' => $check_date));
		// 					}
							
							
		// 				}
		// 				elseif($date_of_cessation != null && count($check_date) != 0)
		// 				{
		// 					$array = explode('/', $date_of_cessation);
		// 					$tmp = $array[0];
		// 					$array[0] = $array[1];
		// 					$array[1] = $tmp;
		// 					unset($tmp);
		// 					$new_date_cess = implode('/', $array);

		// 					$old_date_registration_array = explode('/', $date_of_registration);
		// 					$old_date_registration_tmp = $old_date_registration_array[0];
		// 					$old_date_registration_array[0] = $old_date_registration_array[1];
		// 					$old_date_registration_array[1] = $old_date_registration_tmp;
		// 					unset($old_date_registration_tmp);
		// 					$old_date_registration = implode('/', $old_date_registration_array);

		// 					if(strtotime($new_date_cess) > strtotime($new_date_of_registrations) && strtotime($new_date_of_registrations) > strtotime($old_date_registration))
		// 					{	
		// 						echo json_encode(array("Status" => 2, 'message' => 'Date of register cannot early than old date of cessation.', 'title' => 'Error', 'data' => $check_date));
		// 					}
		// 					else
		// 					{
		// 						$this->db->insert("client_controller",$data);
		// 						$insert_client_controller_id = $this->db->insert_id();

								
		// 						echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_client_controller_id" => $insert_client_controller_id));
		// 					}
		                	
		// 				}
		// 				else
		// 				{
		// 					$this->db->insert("client_controller",$data);
		// 					$insert_client_controller_id = $this->db->insert_id();

							
		// 					echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_client_controller_id" => $insert_client_controller_id));
		// 				}
		// 			}
		// 			else
		// 			{
		// 				echo json_encode(array("Status" => 2, 'message' => 'The person is already registered on controller on the date.', 'title' => 'Error', 'data' => ""));
		// 			}
		// 		} 
		// 		else 
		// 		{
		// 			$arrays = explode('/', $data['date_of_registration']);
		// 			$tmps = $arrays[0];
		// 			$arrays[0] = $arrays[1];
		// 			$arrays[1] = $tmps;
		// 			unset($tmps);
		// 			$new_date_of_registrations = implode('/', $arrays);

		// 			if($data['date_of_cessation'] != "")
		// 			{
		// 				$date_of_cessation_arrays = explode('/', $data['date_of_cessation']);
		// 				$date_of_cessation_tmps = $date_of_cessation_arrays[0];
		// 				$date_of_cessation_arrays[0] = $date_of_cessation_arrays[1];
		// 				$date_of_cessation_arrays[1] = $date_of_cessation_tmps;
		// 				unset($date_of_cessation_tmps);
		// 				$new_date_of_cessations = implode('/', $date_of_cessation_arrays);
		// 			}

		// 			$check_previous_date_appointment = $this->db->query("select * from client_controller where officer_id = '".$data['officer_id']."' AND field_type = '".$data['field_type']."' AND company_code = '".$data['company_code']."' AND id != '".$_POST['client_controller_id'][$i]."' ORDER BY STR_TO_DATE(client_controller.date_of_registration,'%d/%m/%Y') DESC");

		// 			if(!$check_previous_date_appointment->num_rows())
		// 			{
		// 				$not_overlapped = true;
		// 			}
		// 			else
		// 			{
		// 				$check_previous_date_appointment = $check_previous_date_appointment->result_array();

		// 				$arrays = explode('/', $check_previous_date_appointment[0]["date_of_registration"]);
		// 				$tmps = $arrays[0];
		// 				$arrays[0] = $arrays[1];
		// 				$arrays[1] = $tmps;
		// 				unset($tmps);
		// 				$new_previous_date_registration = implode('/', $arrays);

		// 				if(strtotime($new_previous_date_registration) > strtotime($new_date_of_registrations) && $data['date_of_cessation'] == "")
		// 				{
		// 					$not_overlapped = false;
		// 				}
		// 				elseif (strtotime($new_date_of_cessations) > strtotime($new_previous_date_registration) && strtotime($new_previous_date_registration) > strtotime($new_date_of_registrations) && $data['date_of_cessation'] != "")
		// 				{
		// 					$not_overlapped = false;
		// 				}
		// 				else
		// 				{
		// 					$not_overlapped = true;
		// 				}
		// 			}
					

		// 			if($not_overlapped)
		// 			{

		// 				$check_date = $this->db->query("select * from client_controller where officer_id = '".$data['officer_id']."' AND field_type = '".$data['field_type']."' AND company_code = '".$data['company_code']."' AND id != '".$_POST['client_controller_id'][$i]."' ORDER BY STR_TO_DATE(client_controller.date_of_registration,'%d/%m/%Y') DESC LIMIT 2");

		// 				$check_date = $check_date->result_array();

		// 				if(count($check_date) == 1)
		// 				{
		// 					$date_of_cessation = $check_date[0]["date_of_cessation"];
		// 					$date_of_registration = $check_date[0]["date_of_registration"];
		// 				}
		// 				else
		// 				{
		// 					$date_of_cessation = $check_date[0]["date_of_cessation"];
		// 					$date_of_registration = $check_date[0]["date_of_registration"];
		// 				}

		// 				if($date_of_cessation == null && count($check_date) != 0)
		// 				{
		// 					$array = explode('/', $date_of_registration);
		// 					$tmp = $array[0];
		// 					$array[0] = $array[1];
		// 					$array[1] = $tmp;
		// 					unset($tmp);
		// 					$new_date = implode('/', $array);
		// 					$new_date_of_registration = strtotime($new_date_of_registration);

		// 					if($new_date_of_cessations != "")
		// 					{
		// 						if( strtotime($new_date_of_cessations) >= $new_date_of_registration)
		// 						{
		// 							echo json_encode(array("Status" => 2, 'message' => 'This person has not ceased to hold the position in the company.', 'title' => 'Error', 'data' => $check_date));
		// 						}
		// 						else
		// 						{
		// 							$this->db->insert("client_controller",$data);
		// 							$insert_client_controller_id = $this->db->insert_id();

		// 							echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_client_controller_id" => $insert_client_controller_id));
		// 						}
		// 					}
		// 					else
		// 					{
		// 						echo json_encode(array("Status" => 2, 'message' => 'This person has not ceased to hold the position in the company.', 'title' => 'Error', 'data' => $check_date));
		// 					}
							
							
		// 				}
		// 				elseif($date_of_cessation != null && count($check_date) != 0)
		// 				{
		// 					$array = explode('/', $date_of_cessation);
		// 					$tmp = $array[0];
		// 					$array[0] = $array[1];
		// 					$array[1] = $tmp;
		// 					unset($tmp);
		// 					$new_date_cess = implode('/', $array);

		// 					$old_date_registration_array = explode('/', $date_of_registration);
		// 					$old_date_registration_tmp = $old_date_registration_array[0];
		// 					$old_date_registration_array[0] = $old_date_registration_array[1];
		// 					$old_date_registration_array[1] = $old_date_registration_tmp;
		// 					unset($old_date_registration_tmp);
		// 					$old_date_registration = implode('/', $old_date_registration_array);

		// 					if(strtotime($new_date_cess) > strtotime($new_date_of_registrations) && strtotime($new_date_of_registrations) > strtotime($old_date_registration))
		// 					{	
		// 						echo json_encode(array("Status" => 2, 'message' => 'Date of register cannot early than old date of cessation.', 'title' => 'Error', 'data' => $check_date));
		// 					}
		// 					else
		// 					{
		// 						$check_date_of_registration = [];
		// 						$check_date_of_cessation = [];
		// 						$check_date_of_registration[0]['date_of_registration']=$_POST['date_of_registration'][$i];

		// 						$check_date_of_cessation[0]['date_of_cessation']=$_POST['date_of_cessation'][$i];

		// 						$old_office_date_of_registration_result = $this->db->query("select date_of_registration from client_controller where id='".$_POST['client_controller_id'][$i]."'");

		// 						$old_office_date_of_registration_result = $old_office_date_of_registration_result->result_array();

		// 						$old_office_date_of_cessation_result = $this->db->query("select date_of_cessation from client_controller where id='".$_POST['client_controller_id'][$i]."'");

		// 						$old_office_date_of_cessation_result = $old_office_date_of_cessation_result->result_array();

		// 						$this->db->update("client_controller",$data,array("id" => $_POST['client_controller_id'][$i]));

		// 						if(!($old_office_date_of_registration_result == $check_date_of_registration))
		// 						{
		// 							$check_history_client_controller = $this->db->get_where("history_client_controller", array("id" => $_POST['client_controller_id'][$i]));
		// 							if (!$check_history_client_controller->num_rows())
		// 							{
		// 								$w = $q->result();

		// 								foreach($w as $r) {
		// 							        $this->db->insert("history_client_controller",$r);
		// 							    }
		// 							} 
		// 							else 
		// 							{
		// 								$x = $q->result_array();

		// 								$data_history['date_of_registration'] = $x[0]["date_of_registration"];

		// 							    $this->db->update("history_client_controller",$data_history,array("id" => $_POST['client_controller_id'][$i]));
										
		// 							}
		// 						}

		// 						if(!($old_office_date_of_cessation_result == $check_date_of_cessation))
		// 						{
		// 							$check_history_client_controller = $this->db->get_where("history_client_controller", array("id" => $_POST['client_controller_id'][$i]));
		// 							if (!$check_history_client_controller->num_rows())
		// 							{
		// 								$g = $q->result();

		// 								foreach($g as $r) {
		// 							        $this->db->insert("history_client_controller",$r);
		// 							    }
		// 							} 
		// 							else 
		// 							{
		// 								$z = $q->result_array();

		// 								$data_history['date_of_cessation'] = $z[0]["date_of_cessation"];

		// 							    $this->db->update("history_client_controller",$data_history,array("id" => $_POST['client_controller_id'][$i]));
										
		// 							}
		// 						}

		// 						echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_client_controller_id" => $insert_client_controller_id));
		// 					}
		// 				}
		// 				else
		// 				{
		// 					$check_date_of_registration = [];
		// 					$check_date_of_cessation = [];
		// 					$check_date_of_registration[0]['date_of_registration']=$_POST['date_of_registration'][$i];

		// 					$check_date_of_cessation[0]['date_of_cessation']=$_POST['date_of_cessation'][$i];

		// 					$old_office_date_of_registration_result = $this->db->query("select date_of_registration from client_controller where id='".$_POST['client_controller_id'][$i]."'");

		// 					$old_office_date_of_registration_result = $old_office_date_of_registration_result->result_array();

		// 					$old_office_date_of_cessation_result = $this->db->query("select date_of_cessation from client_controller where id='".$_POST['client_controller_id'][$i]."'");

		// 					$old_office_date_of_cessation_result = $old_office_date_of_cessation_result->result_array();

		// 					$this->db->update("client_controller",$data,array("id" => $_POST['client_controller_id'][$i]));

							

		// 					if(!($old_office_date_of_registration_result == $check_date_of_registration))
		// 					{
		// 						$check_history_client_controller = $this->db->get_where("history_client_controller", array("id" => $_POST['client_controller_id'][$i]));
		// 						if (!$check_history_client_controller->num_rows())
		// 						{
		// 							$w = $q->result();

		// 							foreach($w as $r) {
		// 						        $this->db->insert("history_client_controller",$r);
		// 						    }
		// 						} 
		// 						else 
		// 						{
		// 							$x = $q->result_array();

		// 							$data_history['date_of_registration'] = $x[0]["date_of_registration"];

		// 						    $this->db->update("history_client_controller",$data_history,array("id" => $_POST['client_controller_id'][$i]));
									
		// 						}
		// 					}

		// 					if(!($old_office_date_of_cessation_result == $check_date_of_cessation))
		// 					{
		// 						$check_history_client_controller = $this->db->get_where("history_client_controller", array("id" => $_POST['client_controller_id'][$i]));
		// 						if (!$check_history_client_controller->num_rows())
		// 						{
		// 							$g = $q->result();

		// 							foreach($g as $r) {
		// 						        $this->db->insert("history_client_controller",$r);
		// 						    }
		// 						} 
		// 						else 
		// 						{
		// 							$z = $q->result_array();

		// 							$data_history['date_of_cessation'] = $z[0]["date_of_cessation"];

		// 						    $this->db->update("history_client_controller",$data_history,array("id" => $_POST['client_controller_id'][$i]));
									
		// 						}
		// 					}

							
							
		// 					echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_client_controller_id" => $insert_client_controller_id));
		// 				}
		// 			}
		// 			else
		// 			{
		// 				echo json_encode(array("Status" => 2, 'message' => 'The person is already registered on controller on the date.', 'title' => 'Error', 'data' => ""));
		// 			}
		// 		}
		// 	}
		// }
	}

	public function check_guarantee_data()
	{
		$check_guarantee = [];
							
		$check_guarantee[0]['company_code']=$_POST['company_code'];
		$check_guarantee[0]['officer_id']=$_POST['field_type'];
		$check_guarantee[0]['field_type']=$_POST['field_type'];
		$check_guarantee[0]['currency_id']=$_POST['currency_id'];
		$check_guarantee[0]['guarantee']=(float)str_replace(',', '', $_POST['guarantee'][0]);
		$check_guarantee[0]['guarantee_start_date']=$_POST['guarantee_start_date'][0];

		$query = $this->db->get_where("history_client_guarantee", array("id" => $_POST['client_guarantee_id'][0]));

		if (!$query->num_rows())//if don't have anythings
		{
			echo false;
		}
		else
		{
			$query = $query->result_array();

			$old_client_guarantee_result = $this->db->query("select * from client_guarantee where id='".$_POST['client_guarantee_id'][$i]."'");

			$old_client_guarantee_result = $old_client_guarantee_result->result_array();

			$get_client_info = $this->db->query("select * from client where company_code='".$_POST['company_code']."'");

			$get_client_info = $get_client_info->result_array();

			if(!($old_client_guarantee_result == $check_guarantee))
			{
				$pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $get_client_info[0]["id"], "guarantee_id" => $_POST['client_guarantee_id'][0], "received_on" => "", "triggered_by" => "14"));
				if($pending_documents_query->num_rows())
				{
					echo true;
				}
				else
				{
					echo false;
				}
			}
			else
			{
				echo false;
			}
		}
	}

	public function add_guarantee()
	{
		
		$identification_register_no = $_POST['identification_register_no'];
		$name = $_POST['name'];

		for($i = 0; $i < count($_POST['identification_register_no']); $i++ )
		{
			 $this->form_validation->set_rules('identification_register_no['.$i.']', 'Id', 'required');
			 $this->form_validation->set_rules('name['.$i.']', 'Name', 'required');
			 $this->form_validation->set_rules('guarantee['.$i.']', 'Limit Of Guarantee', 'required');
			 $this->form_validation->set_rules('guarantee_start_date['.$i.']', 'Start', 'required');

	        if ($this->form_validation->run() == FALSE || $_POST['currency'][$i] == "0" || $_POST['guarantee'][$i] == "0")
	        {
	        	$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

	        	if($_POST['currency'][$i] == "0")
	        	{
	        		$currency = "The Currency field is required.";
	        	}
	        	else
	        	{
	        		$currency = "";
	        	}

	        	if($_POST['guarantee'][$i] == "0")
	        	{
	        		$guarantee = "The Limit Of Guarantee field cannot be zero.";
	        	}
	        	else if($_POST['guarantee'][$i] == "")
	        	{
	        		$guarantee = strip_tags(form_error('guarantee['.$i.']'));
	        	}

	        	
	        	$error = array(
			                'identification_register_no' => strip_tags(form_error('identification_register_no['.$i.']')),
			                'name' => strip_tags(form_error('name['.$i.']')),
			                'currency' => $currency,
			                'guarantee' => $guarantee,
			                'guarantee_start_date' => strip_tags(form_error('guarantee_start_date['.$i.']')),
			            );

	        	echo json_encode(array("Status" => 0, 'message' => 'Please complete all required field', 'title' => 'Error', "error" => $error));

	        }
	        else
	        {
			    if($_POST['client_guarantee_id'][$i] != null)
			    {
			    	$check_guarantee = $this->db->query("select sum(guarantee) as total_guarantee from client_guarantee where company_code='".$_POST['company_code']."' AND officer_id = '".$_POST['officer_id']."' AND field_type = '".$_POST['officer_field_type']."' AND id !='".$_POST['client_guarantee_id'][$i]."'");
			    }
			    else
			    {
			    	$check_guarantee = $this->db->query("select sum(guarantee) as total_guarantee from client_guarantee where company_code='".$_POST['company_code']."' AND officer_id = '".$_POST['officer_id']."' AND field_type = '".$_POST['officer_field_type']."'");
			    }
			    
			    if ($check_guarantee->num_rows() > 0) 
			    {
					$check_guarantee = $check_guarantee->result_array();

					$guarantee_number = (float)$check_guarantee[0]["total_guarantee"] + (float)str_replace(',', '', $_POST['guarantee'][$i]);
					if($guarantee_number >= 0)
					{
						$not_exceed = 1;
					}
					else if(0 > $guarantee_number)
					{
						$not_exceed = 0;
					}
		            
		        } 
		        else 
		        {
		        	$not_exceed = 1;
		        }

				if($not_exceed)
				{
					$data['company_code']=$_POST['company_code'];
					$data['officer_id']=$_POST['officer_id'];
					$data['field_type']=$_POST['officer_field_type'];
					$data['currency_id']=$_POST['currency'][$i];
					$data['guarantee']=(float)str_replace(',', '', $_POST['guarantee'][$i]);
					
					$data['guarantee_start_date']=$_POST['guarantee_start_date'][$i];

					$q = $this->db->get_where("client_guarantee", array("id" => $_POST['client_guarantee_id'][$i]));

					if (!$q->num_rows())
					{
						$this->db->insert("client_guarantee",$data);
						$insert_client_guarantee_id = $this->db->insert_id();


						echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_client_guarantee_id" => $insert_client_guarantee_id));
					} 
					else 
					{	
						$check_guarantee = [];
						
						$check_guarantee[0]['company_code']=$_POST['company_code'];
						$check_guarantee[0]['officer_id']=$_POST['field_type'];
						$check_guarantee[0]['field_type']=$_POST['field_type'];
						$check_guarantee[0]['currency_id']=$_POST['currency_id'];
						$check_guarantee[0]['guarantee']=(float)str_replace(',', '', $_POST['guarantee'][$i]);
						$check_guarantee[0]['guarantee_start_date']=$_POST['guarantee_start_date'][$i];

						$old_client_guarantee_result = $this->db->query("select * from client_guarantee where id='".$_POST['client_guarantee_id'][$i]."'");

						$old_client_guarantee_result = $old_client_guarantee_result->result_array();

						$this->db->update("client_guarantee",$data,array("id" => $_POST['client_guarantee_id'][$i]));

						

						if(!($old_client_guarantee_result == $check_guarantee))
						{
							$check_history_client_guarantee = $this->db->get_where("history_client_guarantee", array("id" => $_POST['client_guarantee_id'][$i]));
							if (!$check_history_client_guarantee->num_rows())
							{
								$w = $q->result();

								foreach($w as $r) {
							        $this->db->insert("history_client_guarantee",$r);
							    }
							} 
							else 
							{
								$x = $q->result_array();

								$data_history['field_type'] = $x[0]["field_type"];
								$data_history['field_type'] = $x[0]["field_type"];
								$data_history['currency_id'] = $x[0]["currency_id"];
								$data_history['guarantee'] = $x[0]["guarantee"];
								$data_history['guarantee_start_date'] = $x[0]["guarantee_start_date"];

							    $this->db->update("history_client_guarantee",$data_history,array("id" => $_POST['client_guarantee_id'][$i]));
								
							}
						}

						echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_client_officers_id" => $insert_client_guarantee_id));
					}
				}
				else
				{
					echo json_encode(array("Status" => 2, 'message' => 'Guarantee amount cannot be negative.', 'title' => 'Error'));
				}
			}
		}
	}

	public function delete_guarantee ()
	{
		$id = $_POST["client_guarantee_id"];
		echo $this->db->delete("client_guarantee",array('id'=>$id));
	}

	public function delete_nominee_director ()
	{
		$id = $_POST["client_nominee_director_id"];
		$delete_company_code = $_POST["delete_company_code"];
		$client_nominee_director_name = $_POST["client_nominee_director_name"];

		$data["deleted"] = 1;
		$this->db->update("client_nominee_director", $data, array("id" => $id));

		$this->save_audit_trail("Clients", "Register of Nominee Director", "Nominee director ".$client_nominee_director_name." is deleted.", $delete_company_code);

		$get_edit_client_nominee_director_data = $this->db_model->getClientNomineeDirector($delete_company_code);
 
        echo json_encode(array("status" => 1, "list_of_nominee_director" => $get_edit_client_nominee_director_data));
	}

	public function delete_controller ()
	{
		$id = $_POST["client_controller_id"];
		$delete_company_code = $_POST["delete_company_code"];
		$client_controller_name = $_POST["client_controller_name"];
		//$this->db->delete("client_controller",array('id'=>$id));
		$data["deleted"] = 1;
		$this->db->update("client_controller", $data, array("id" => $id));

		$this->save_audit_trail("Clients", "Register of Controller", "Controller ".$client_controller_name." is deleted.", $delete_company_code);

		$get_edit_client_controller_data = $this->db_model->getClientController($delete_company_code);
 
        echo json_encode(array("status" => 1, "list_of_controller" => $get_edit_client_controller_data));
	}

	public function check_charge_data()
	{
		$check_date_registration = [];
		$check_date_satisfied = [];
		$check_date_registration[0]['date_registration']=$_POST['date_registration'][0];

		$check_date_satisfied[0]['date_satisfied']=$_POST['date_satisfied'][0];

		$query = $this->db->get_where("history_client_charges", array("id" => $_POST['client_charge_id'][0]));

		if (!$query->num_rows())//if don't have anythings
		{
			echo false;
		}
		else
		{
			$query = $query->result_array();

			$old_office_date_registration_result = $this->db->query("select date_registration from client_charges where id='".$_POST['client_charge_id'][0]."'");

			$old_office_date_registration_result = $old_office_date_registration_result->result_array();

			$get_client_info = $this->db->query("select * from client where company_code='".$_POST['company_code']."'");

			$get_client_info = $get_client_info->result_array();

			if(!($old_office_date_registration_result == $check_date_registration))
			{
				$pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $get_client_info[0]["id"], "charge_id" => $_POST['client_charge_id'][0], "received_on" => "", "triggered_by" => "17"));
				if($pending_documents_query->num_rows())
				{
					echo true;
				}
				else
				{
					echo false;
				}
			}
			else
			{
				echo false;
			}

			$old_office_date_satisfied_result = $this->db->query("select date_satisfied from client_charges where id='".$_POST['client_charges_id'][$i]."'");

			$old_office_date_satisfied_result = $old_office_date_satisfied_result->result_array();

			if(!($old_office_date_satisfied_result == $check_date_satisfied))
			{
				$pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $get_client_info[0]["id"], "charge_id" => $_POST['client_charge_id'][0], "received_on" => "", "triggered_by" => "18"));
				if($pending_documents_query->num_rows())
				{
					echo true;
				}
				else
				{
					echo false;
				}
			}
			else
			{
				echo false;
			}
		}
	}

	// callback function
	public function customAlpha($str) 
	{
	    if ( !preg_match('/^[0-9.,\-]+$/i',$str) )
	    {
	    	
	        return false;
	    }

	}

	public function add_charge ()
	{
		
		$charge = $_POST['charge'];
		//$name = $_POST['name'];

		for($i = 0; $i < count($_POST['charge']); $i++ )
		{
			 $this->form_validation->set_rules('charge['.$i.']', 'Charge', 'required');
			 $this->form_validation->set_rules('nature_of_charge['.$i.']', 'Nature of Charge', 'required');
			 $this->form_validation->set_rules('date_registration['.$i.']', 'Date Registration', 'required');
			 if($_POST['satisfactory_no'][$i] != "")
			 {
			 	$this->form_validation->set_rules('date_satisfied['.$i.']', 'Date Satisfied', 'required');
			 }
			 $this->form_validation->set_rules('charge_no['.$i.']', 'Charge No', 'required');

			 if($_POST['date_satisfied'][$i] != "")
			 {
			 	$this->form_validation->set_rules('satisfactory_no['.$i.']', 'Satisfactory No', 'required');
			 }
			 
			 //$this->form_validation->set_rules('currency['.$i.']', 'Currency', 'required');
			 $this->form_validation->set_rules('amount['.$i.']', 'Amount', 'required');
			 /*$this->form_validation->set_rules('secured_by['.$i.']', 'Secured by', 'required');*/
			 // custom error message
	         //$this->form_validation->set_message('customAlpha', 'error message');

			 $date_registration = strtotime(str_replace('/', '-',$_POST['date_registration'][$i]));
			 $date_satisfied = strtotime(str_replace('/', '-',$_POST['date_satisfied'][$i]));

			 $amount_validation = true;
			if ( !preg_match('/^[0-9.,\-]+$/i',$_POST['amount'][$i]) && $_POST['amount'][$i] != null)
		    {
		    	//echo json_encode(false);
		        $amount_validation = false;
		    }
		    

	       
	        //echo($date_satisfied);
	        if ($this->form_validation->run() == FALSE || $date_satisfied < $date_registration && $date_satisfied != "" || $_POST['currency'][$i] == "0" || $amount_validation == false)
	        {
	        	$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

	        	 

	        	/*if($date_satisfied == "")
	        	{
	        		$validate_date_satisfied = "*The Date Satisfied field is required.";
	        	}*/
	        	if($date_satisfied < $date_registration && $date_satisfied != "")
	        	{
	        		$validate_date_satisfied = "The Date Satisfied field must larger than Date Registration.";
	        	}
	        	else
	        	{
	        		$validate_date_satisfied = strip_tags(form_error('date_satisfied['.$i.']'));
	        	}

	        	if($_POST['currency'][$i] == "0")
	        	{
	        		 $validate_currency = "The Currency of field is required.";
	        	}
	        	else
	        	{
	        		$validate_currency = "";
	        	}

	        	if($amount_validation == false)
	        	{
	        		$validate_amount = "The Amount field must contain only numbers.";
	        	}
	        	else
	        	{
	        		$validate_amount = strip_tags(form_error('amount['.$i.']'));
	        	}

	        	$error = array(
	        					'currency' => $validate_currency,
				                'charge' => strip_tags(form_error('charge['.$i.']')),
				                'nature_of_charge' => strip_tags(form_error('nature_of_charge['.$i.']')),
				                'date_registration' => strip_tags(form_error('date_registration['.$i.']')),
				                'date_satisfied' => $validate_date_satisfied,
				                'charge_no' => strip_tags(form_error('charge_no['.$i.']')),
				                'satisfactory_no' => strip_tags(form_error('satisfactory_no['.$i.']')),
				                'amount' => $validate_amount,
				                /*'secured_by' => strip_tags(form_error('secured_by['.$i.']')),*/
				            );

	        	echo json_encode(array("Status" => 0, 'message' => 'Please complete all required field', 'title' => 'Error', "error" => $error));

	        }
	        else
	        {
	        	$data['company_code']=$_POST['company_code'];
				$data['charge']=$_POST['charge'][$i];
				$data['nature_of_charge']=$_POST['nature_of_charge'][$i];
				$data['date_registration']=$_POST['date_registration'][$i];
				$data['date_satisfied']=$_POST['date_satisfied'][$i];
				$data['charge_no']=$_POST['charge_no'][$i];
				$data['satisfactory_no']=$_POST['satisfactory_no'][$i];
				$data['currency']=$_POST['currency'][$i];
				$data['amount']=str_replace(',', '', $_POST['amount'][$i]);
				$data['secured_by']=$_POST['secured_by'][$i];


				$q = $this->db->get_where("client_charges", array("id" => $_POST['client_charge_id'][$i]));

				if (!$q->num_rows())
				{				
					$this->db->insert("client_charges",$data);
					$insert_client_charge_id = $this->db->insert_id();

					$this->save_audit_trail("Clients", "Charges", "Chargee ".$_POST['charge'][$i]." is added.", $_POST['company_code']);
				} 
				else 
				{	
					//track the fields is update or not
					$check_charge = [];
					$check_charge[0]['charge']=$_POST['charge'][$i];
					$check_charge[0]['nature_of_charge']=$_POST['nature_of_charge'][$i];
					$check_charge[0]['date_registration']=$_POST['date_registration'][$i];
					$check_charge[0]['date_satisfied']=$_POST['date_satisfied'][$i];
					$check_charge[0]['charge_no']=$_POST['charge_no'][$i];
					$check_charge[0]['satisfactory_no']=$_POST['satisfactory_no'][$i];
					$check_charge[0]['currency']=$_POST['currency'][$i];
					$check_charge[0]['amount']=str_replace(',', '', $_POST['amount'][$i]);
					$check_charge[0]['secured_by']=$_POST['secured_by'][$i];

					$old_client_charges_result = $this->db->query("select charge, nature_of_charge, date_registration, date_satisfied, charge_no, satisfactory_no, currency, amount, secured_by from client_charges where id='".$_POST['client_charge_id'][$i]."'");

					$old_client_charges_result = $old_client_charges_result->result_array();

					/*echo json_encode($check_address);
					echo json_encode($old_client_address_result);
					echo json_encode($old_client_address_result == $check_address);*/
					//echo(!($old_client_address_result == $check_address));

					$check_date_registration = [];
					$check_date_satisfied = [];
					$check_date_registration[0]['date_registration']=$_POST['date_registration'][$i];

					$check_date_satisfied[0]['date_satisfied']=$_POST['date_satisfied'][$i];

					$old_office_date_registration_result = $this->db->query("select date_registration from client_charges where id='".$_POST['client_charge_id'][$i]."'");

					$old_office_date_registration_result = $old_office_date_registration_result->result_array();

					$old_office_date_satisfied_result = $this->db->query("select date_satisfied from client_charges where id='".$_POST['client_charge_id'][$i]."'");

					$old_office_date_satisfied_result = $old_office_date_satisfied_result->result_array();

					$get_client_information = $this->db->query("select client.*, company_type.company_type as company_type_name from client left join company_type on client.company_type = company_type.id where company_code='".$data['company_code']."'");

					$get_client_information = $get_client_information->result_array();

					$previous_charge_registration_result = $this->db->query("select client_charges.* from client_charges where  company_code='".$data['company_code']."' AND STR_TO_DATE(client_charges.date_registration,'%d/%m/%Y') = STR_TO_DATE('".$old_office_date_registration_result[0]["date_registration"]."','%d/%m/%Y')");
					//echo json_encode($check_date);
					$previous_charge_registration_result = $previous_charge_registration_result->result_array();

					$previous_charge_satisfied_result = $this->db->query("select client_charges.* from client_charges where company_code='".$data['company_code']."' AND STR_TO_DATE(client_charges.date_satisfied,'%d/%m/%Y') = STR_TO_DATE('".$old_office_date_satisfied_result[0]["date_satisfied"]."','%d/%m/%Y')");
					//echo json_encode($check_date);
					$previous_charge_satisfied_result = $previous_charge_satisfied_result->result_array();


					$this->db->update("client_charges",$data,array("id" => $_POST['client_charge_id'][$i]));

					$this->save_audit_trail("Clients", "Charges", "Chargee ".$_POST['charge'][$i]." is edited.", $_POST['company_code']);

					if(!($old_office_date_registration_result == $check_date_registration))
					{
						$check_history_client_charges = $this->db->get_where("history_client_charges", array("id" => $_POST['client_charge_id'][$i]));
						if (!$check_history_client_charges->num_rows())
						{
							$w = $q->result();

							foreach($w as $r) {
						        $this->db->insert("history_client_charges",$r);
						    }
						} 
						else 
						{
							$x = $q->result_array();

							$data_history['date_registration'] = $x[0]["date_registration"];

						    $this->db->update("history_client_charges",$data_history,array("id" => $_POST['client_charge_id'][$i]));
							
						}
					}

					if(!($old_office_date_satisfied_result == $check_date_satisfied))
					{
						$check_history_client_charges = $this->db->get_where("history_client_charges", array("id" => $_POST['client_charge_id'][$i]));
						if (!$check_history_client_charges->num_rows())
						{
							$g = $q->result();

							foreach($g as $r) {
						        $this->db->insert("history_client_charges",$r);
						    }
						} 
						else 
						{
							$z = $q->result_array();

							$data_history['date_satisfied'] = $z[0]["date_satisfied"];

						    $this->db->update("history_client_charges",$data_history,array("id" => $_POST['client_charge_id'][$i]));
							
						}
					}
				}
				echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_client_charge_id" => $insert_client_charge_id));
			}
		}	
	}

	public function delete_charge ()
	{
		$id = $_POST["client_charge_id"];

		$q = $this->db->get_where("client_charges", array("id" => $id));

		if ($q->num_rows())
		{
			$charge_array = $q->result_array();
			$this->save_audit_trail("Clients", "Charges", "Chargee ".$charge_array[0]["charge"]." is deleted.", $charge_array[0]["company_code"]);
		}
		$this->db->delete("history_client_charges",array('id'=>$id));

		$this->db->delete("client_charges",array('id'=>$id));

		echo json_encode(array("Status" => 1));
	}

	public function add_share_capital ()
	{
		$class = $_POST['class'];

		for($i = 0; $i < count($_POST['class']); $i++ )
		{
			if(!empty($_POST['other_class']))
			{
				$this->form_validation->set_rules('other_class['.$i.']', 'Others', 'required');

				$valid = $this->form_validation->run();
			}
			else
			{
				$valid = true;
			}

	        if ($valid == FALSE || $_POST['class'][$i] == "0" || $_POST['currency'][$i] == "0")
	        {
	        	$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

	        	if($_POST['class'][$i] == "0")
	        	{
	        		 $validate_class = "The Class of field is required.";
	        	}
	        	else
	        	{
	        		$validate_class = "";
	        	}

	        	if($_POST['currency'][$i] == "0")
	        	{
	        		 $validate_currency = "*The Currency of field is required.";
	        	}
	        	else
	        	{
	        		$validate_currency = "";
	        	}

	        	$error = array(
	        					'class' => $validate_class,
	        					'currency' => $validate_currency,
				                'other_class' => strip_tags(form_error('other_class['.$i.']')),
				            );

	        	echo json_encode(array("Status" => 0, 'message' => 'Please complete all required field', 'title' => 'Error', "error" => $error));
	        }
	        else
	        {
	        	$shareType_query = $this->db->query("select sharetype.sharetype as sharetype_name from sharetype where sharetype.id = '".$_POST['class'][$i]."'");
	        	$shareType_array = $shareType_query->result_array();

	        	$currency_query = $this->db->query("select currency.currency as currency_name from currency where currency.id = '".$_POST['currency'][$i]."'");
	        	$currency_array = $currency_query->result_array();

	        	$data['company_code']=$_POST['company_code'];
				$data['class_id']=$_POST['class'][$i];
				if($_POST['other_class'][$i] == null)
				{
					$data['other_class']='';
					$class_name = $shareType_array[0]["sharetype_name"];
				}
				else
				{
					$data['other_class']=$_POST['other_class'][$i];
					$class_name = $_POST['other_class'][$i];
				}

				$data['currency_id']=$_POST['currency'][$i];

				$q = $this->db->get_where("client_member_share_capital", array("id" => $_POST['share_capital_id'][$i]));

				if (!$q->num_rows())
				{				
					$this->db->insert("client_member_share_capital",$data);
					$insert_share_capital_id = $this->db->insert_id();

					$this->save_audit_trail("Clients", "Members", "Class of shares ".$class_name." (".$currency_array[0]['currency_name'].") is added.", $_POST['company_code']);
				} 
				else 
				{	
					$this->db->update("client_member_share_capital",$data,array("id" => $_POST['share_capital_id'][$i]));

					$this->save_audit_trail("Clients", "Members", "Class of shares ".$class_name." (".$currency_array[0]['currency_name'].") is edited.", $_POST['company_code']);
				}
				echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_share_capital_id" => $insert_share_capital_id));
			}
		}	
	}

	public function delete_share_capital ()
	{
		$id = $_POST["share_capital_id"];
		$q = $this->db->get_where("client_member_share_capital", array("id" => $_POST['share_capital_id']));

		$shareType_query = $this->db->query("select client_member_share_capital.company_code, client_member_share_capital.other_class, sharetype.sharetype as sharetype_name, currency.currency as currency_name from client_member_share_capital left join sharetype on sharetype.id = client_member_share_capital.class_id left join currency on currency.id = client_member_share_capital.currency_id where client_member_share_capital.id = '".$_POST['share_capital_id']."'"); 

		if ($shareType_query->num_rows())
		{
			$shareType_array = $shareType_query->result_array();
			if($shareType_array[0]['other_class'] != "")
			{
				$class_name = $shareType_array[0]['other_class'];
			}
			else
			{
				$class_name = $shareType_array[0]['sharetype_name'];
			}
			$this->save_audit_trail("Clients", "Members", "Class of shares ".$class_name." (".$shareType_array[0]['currency_name'].") is deleted.", $shareType_array[0]['company_code']);
		}

		$this->db->delete("member_shares",array('client_member_share_capital_id'=>$id));
		$this->db->delete("certificate_merge",array('client_member_share_capital_id'=>$id));
		$this->db->delete("certificate",array('client_member_share_capital_id'=>$id));
		$this->db->delete("client_member_share_capital",array('id'=>$id));

		echo json_encode(array("Status" => 1, 'message' => 'Information Deleted', 'title' => 'Deleted'));
	}
	
	public function delete($id)
	{
		/*$this->db->update("client",array('row_status'=>'1'),array('id'=>$id));*/
		$this->db->delete("client",array('id'=>$id));
        redirect("masterclient");
	}

	public function check_next_recurring_date()
	{
		// $type_of_day = $_POST["type_of_day"];
		// $days = $_POST["days"];
		$frequency = $_POST["frequency"];
		$to_billing_cycle_date = $_POST["period_end_date"];
		$recurring_issue_date = $_POST["recurring_issue_date"];
		// $from_date = $_POST["from"];
		// $to_date = $_POST["to"];

		$current_date = DATE("Y-m-d",now());

		// if($from_date != null)
		// {
		// 	$date_from = str_replace('/', '-', $from_date);
		// 	$from = strtotime($date_from);
		// 	$new_from = date('Y-m-d',$from);
		// 	//echo ($new_to);
		// }
		// else
		// {
		// 	$new_from = null;
		// }

		// if($to_date != null)
		// {
		// 	$date_to = str_replace('/', '-', $to_date);
		// 	$to = strtotime($date_to);
		// 	$new_to = date('Y-m-d',$to);
		// 	//echo ($new_to);
		// }
		// else
		// {
		// 	$new_to = null;
		// }

		$date_to_billing_cycle = str_replace('/', '-', $to_billing_cycle_date);
		$date_for_issue = str_replace('/', '-', $recurring_issue_date);
		// $to_billing_cycle = strtotime($date_for_issue);
		// //echo ($new_to);
		// if($type_of_day == 1)
		// {
		// 	$new_to_billing_cycle = date('Y-m-d', strtotime('-'.$days.' days', $to_billing_cycle));
		// }
		// elseif($type_of_day == 2)
		// {
		// 	$new_to_billing_cycle = date('Y-m-d', strtotime('+'.$days.' days', $to_billing_cycle));
		// }
		// else
		// {
		// 	$new_to_billing_cycle = date('Y-m-d',$to_billing_cycle);
		// }
		
		$next_billing_cycle = new DateTime(date('Y-m-d', strtotime($date_to_billing_cycle)));
		$latest_date_for_issue = new DateTime(date('Y-m-d', strtotime($date_for_issue)));
		//echo json_encode($next_billing_cycle);
		// We extract the day of the month as $start_day

		if($frequency == 2)
		{
			//$last_recurring_date = date("Y-m-d", strtotime("+1 month", $new_from));
			$next_from_billing_cycle = date('Y-m-d', strtotime('+ 1 days', strtotime($date_to_billing_cycle)));
			$next_to_billing_cycle = $this->MonthShifter($next_billing_cycle,1)->format(('Y-m-d'));
			$latest_date_for_issue = $this->MonthShifter($latest_date_for_issue,1)->format(('Y-m-d'));
		}
		elseif($frequency == 3)
		{
			//$last_recurring_date = date("Y-m-d", strtotime("+3 months", $new_from));
			// $next_from_billing_cycle = $this->MonthShifter($next_from_billing_cycle,3)->format(('Y-m-d'));
			// $next_billing_cycle = new DateTime($next_from_billing_cycle);
			$next_from_billing_cycle = date('Y-m-d', strtotime('+ 1 days', strtotime($date_to_billing_cycle)));
			$next_to_billing_cycle = $this->MonthShifter($next_billing_cycle,3)->format(('Y-m-d'));
			$latest_date_for_issue = $this->MonthShifter($latest_date_for_issue,3)->format(('Y-m-d'));
		}
		elseif($frequency == 4)
		{
			//$last_recurring_date = date("Y-m-d", strtotime("+6 months", $new_from));
			// $next_from_billing_cycle = $this->MonthShifter($next_from_billing_cycle,6)->format(('Y-m-d'));
			// $next_billing_cycle = new DateTime($next_from_billing_cycle);
			$next_from_billing_cycle = date('Y-m-d', strtotime('+ 1 days', strtotime($date_to_billing_cycle)));
			$next_to_billing_cycle = $this->MonthShifter($next_billing_cycle,6)->format(('Y-m-d'));
			$latest_date_for_issue = $this->MonthShifter($latest_date_for_issue,6)->format(('Y-m-d'));
		}
		elseif($frequency == 5)
		{
			$next_from_billing_cycle = date('Y-m-d', strtotime('+ 1 days', strtotime($date_to_billing_cycle)));
			$next_to_billing_cycle = $this->MonthShifter($next_billing_cycle,12)->format(('Y-m-d'));
			$latest_date_for_issue = $this->MonthShifter($latest_date_for_issue,12)->format(('Y-m-d'));
		}

		echo json_encode(array("status" => 1, "issue_date" => date('d/m/Y', strtotime($latest_date_for_issue)), "next_from_billing_cycle" => date('d/m/Y', strtotime($next_from_billing_cycle)), 'next_to_billing_cycle' => date('d/m/Y', strtotime($next_to_billing_cycle))));
	}
	
	public function add_client_billing_info()
	{
		$_POST['client_billing_info_id'] = array_values($_POST['client_billing_info_id']);
		$_POST['service'] = array_values($_POST['service']);
		$_POST['invoice_description'] = array_values($_POST['invoice_description']);
		$_POST['amount'] = array_values($_POST['amount']);
		$_POST['currency'] = array_values($_POST['currency']);
		$_POST['unit_pricing'] = array_values($_POST['unit_pricing']);
		$_POST['servicing_firm'] = array_values($_POST['servicing_firm']);
		$_POST['deactive'] = array_values($_POST['hidden_deactive_switch']);

		if(count(json_decode($_POST['array_client_billing_info_id'])) != 0)
		{
			for($g = 0; $g < count(json_decode($_POST['array_client_billing_info_id'])); $g++ )
			{
				$deleted_client_billing_info['deleted'] = 1;

				$this->db->where(array("company_code" => $_POST['company_code'], "client_billing_info_id" => json_decode($_POST['array_client_billing_info_id'])[$g]));
				$this->db->update("client_billing_info",$deleted_client_billing_info);

				$client_billing_info_query = $this->db->query("select our_service_info.service_name from client_billing_info left join our_service_info on our_service_info.id = client_billing_info.service where client_billing_info_id = '".json_decode($_POST['array_client_billing_info_id'])[$g]."' AND company_code = '".$_POST['company_code']."'");

				if ($client_billing_info_query->num_rows())
				{
					$client_billing_info_array = $client_billing_info_query->result_array();
					$this->save_audit_trail("Clients", "Service Engagement", "Service ".$client_billing_info_array[0]['service_name']." is deleted.", $_POST['company_code']);
				}
			}
		}

		for($i = 0; $i < count($_POST['client_billing_info_id']); $i++ )
		{
			$client_billing_info['company_code'] = $_POST['company_code'];
			$client_billing_info['client_billing_info_id'] = $_POST['client_billing_info_id'][$i];
			$client_billing_info['service'] = $_POST['service'][$i];
			$client_billing_info['invoice_description'] = $_POST['invoice_description'][$i];
			$client_billing_info['amount'] = (float)str_replace(',', '', $_POST['amount'][$i]);
			$client_billing_info['currency'] = $_POST['currency'][$i];
			$client_billing_info['unit_pricing'] = $_POST['unit_pricing'][$i];
			$client_billing_info['servicing_firm'] = $_POST['servicing_firm'][$i];
			$client_billing_info['deactive'] = $_POST['deactive'][$i];

			$q = $this->db->get_where("client_billing_info", array("company_code" => $_POST['company_code'], "client_billing_info_id" => $_POST['client_billing_info_id'][$i], "deleted =" => 0));

			if (!$q->num_rows())
			{				
				$this->db->insert("client_billing_info",$client_billing_info);

				$our_service_info_query = $this->db->query("select our_service_info.service_name from our_service_info where our_service_info.id = '".$_POST['service'][$i]."'");

				if ($our_service_info_query->num_rows())
				{
					$our_service_info_array = $our_service_info_query->result_array();
				}

				$this->save_audit_trail("Clients", "Service Engagement", "Service ".$our_service_info_array[0]['service_name']." is added.", $_POST['company_code']);
			} 
			else 
			{	
				$this->db->where(array("company_code" => $_POST['company_code'], "client_billing_info_id" => $_POST['client_billing_info_id'][$i], "deleted =" => 0));
				$this->db->update("client_billing_info",$client_billing_info);

				$client_billing_info_query = $this->db->query("select our_service_info.service_name from client_billing_info left join our_service_info on our_service_info.id = client_billing_info.service where client_billing_info.client_billing_info_id = '".$_POST['client_billing_info_id'][$i]."' AND client_billing_info.company_code = '".$_POST['company_code']."' AND client_billing_info.deleted = 0");

				if ($client_billing_info_query->num_rows())
				{
					$client_billing_info_array = $client_billing_info_query->result_array();
				}

				$this->save_audit_trail("Clients", "Service Engagement", "Service ".$client_billing_info_array[0]['service_name']." is updated.", $_POST['company_code']);
			}
		}

		$client_info = $this->db->query("select * from client where company_code = '".$_POST['company_code']."'");

		$client_info = $client_info->result_array();

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', 'client_id' => $client_info[0]['id']));

	}

	public function get_client_list()
	{
		$this->db->select('client.*');
        $this->db->from('client');
        $this->db->where('client.deleted', 0);
        $this->db->order_by('client.id', 'desc');
        $q = $this->db->get();
        $result = $q->result_array();

        if(!$q) {
            throw new exception("Client not found.");
        }
        $res = array();

        for($j = 0; $j < count($result); $j++)
        {
            $res[$result[$j]['id']] = $this->encryption->decrypt($result[$j]['company_name']);
        }    

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Client fetched successfully.", 'result'=>$res);

        echo json_encode($data);
	}
	
	public function get_reminder()
    {
        $this->db->select('document_reminder.*, reminder_tag.reminder_tag_name, firm.name as firm_name, firm.branch_name')
                ->from('document_reminder')
                ->join('reminder_tag', 'reminder_tag.id = document_reminder.reminder_tag_id', 'left')
                ->join('firm', 'firm.id = document_reminder.firm_id', 'left');

        $get_all_reminder = $this->db->get();
        $result = $get_all_reminder->result_array();

        if(!$get_all_reminder) {
            throw new exception("Reminder not found.");
        }
        $res = array();

        for($j = 0; $j < count($result); $j++)
        {
        	if($result[$j]['branch_name'] != null)
        	{
        		$branch_name = ' ('.$result[$j]['branch_name'].')';
        	}
        	else
        	{
        		$branch_name = "";
        	}
            $res[$result[$j]['id']] = $result[$j]['reminder_name'].' ('.$result[$j]['firm_name'].$branch_name.')';
        }    

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Reminder fetched successfully.", 'result'=>$res);

        echo json_encode($data);
    }

    public function submit_signing_information()
    {
    	$client_signing_info['company_code'] = $_POST['company_code'];
		$client_signing_info['chairman'] = $_POST['chairman'];
		$client_signing_info['director_signature_1'] = $_POST['director_signature_1'];

		if(isset($_POST['director_signature_2']))
        {
            $client_signing_info['director_signature_2'] = $_POST['director_signature_2'];
        }
        else
        {
        	$client_signing_info['director_signature_2'] = 0;
        }
		for($i = 0; $i < count($_POST['director_retiring_client_officer_id']); $i++)
		{
			$director_retiring['retiring'] = $_POST['hidden_director_retiring_checkbox'][$i];
			$this->db->where(array("id" => $_POST['director_retiring_client_officer_id'][$i]));
			$this->db->update("client_officers",$director_retiring);

			$this->save_audit_trail("Clients", "Signing Information", "Director retiring info is updated.", $_POST['company_code']);
		}

		$p = $this->db->get_where("client_signing_info", array("company_code" => $_POST['company_code']));

		if (!$p->num_rows())
		{				
			$this->db->insert("client_signing_info",$client_signing_info);

			$this->save_audit_trail("Clients", "Signing Information", "Chairman/Director Signature info is added.", $_POST['company_code']);
		} 
		else 
		{	
			$this->db->where(array("company_code" => $_POST['company_code']));
			$this->db->update("client_signing_info",$client_signing_info);

			$this->save_audit_trail("Clients", "Signing Information", "Chairman/Director Signature info is updated.", $_POST['company_code']);
		}

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
    }

    public function submit_contact_information()
    {
    	//$this->form_validation->set_rules('contact_email[]', 'Email', 'valid_email');
        $this->form_validation->set_rules('contact_phone[]', 'Phone Number', 'numeric');

        if(count($_POST['hidden_contact_phone']) > 1 && $_POST['contact_phone_primary'] == null)
        {
            $validate_contact_phone_primary = FALSE;
        }
        else
        {
            $validate_contact_phone_primary = TRUE;
        }

        if(count($_POST['contact_email']) > 1 && $_POST['contact_email_primary'] == null)
        {
            $validate_contact_email_primary = FALSE;
        }
        else
        {
            $validate_contact_email_primary = TRUE;
        }

        if ($this->form_validation->run() == FALSE || $validate_contact_phone_primary == FALSE || $validate_contact_email_primary == FALSE)
        {
        	if($validate_contact_phone_primary == FALSE)
            {
                $validate_contact_phone = "Please set the primary field.";
            }
            else
            {
                $validate_contact_phone = strip_tags(form_error('contact_phone[]'));
            }

            if($validate_contact_email_primary == FALSE)
            {
                $validate_contact_email = "Please set the primary field.";
            }
            else
            {
                $validate_contact_email = strip_tags(form_error('contact_email[]'));
            }

            $arr = array(
                'contact_phone' => $validate_contact_phone,
                'contact_email' => $validate_contact_email,
            );

            echo json_encode(array("Status" => 0, "error" => $arr, 'message' => 'Please complete all required field', 'title' => 'Error'));
        }
        else
        {
        	$client_contact_info['company_code'] = $_POST['company_code'];
			$client_contact_info['name'] = strtoupper($_POST['contact_name']);
			$query = $this->db->get_where("client_contact_info", array("company_code" => $_POST['company_code']));

			if (!$query->num_rows())
			{				
				$this->db->insert("client_contact_info",$client_contact_info);
				$client_contact_info_id = $this->db->insert_id();
				$this->save_audit_trail("Clients", "Contact Information", "Contact information is added.", $_POST['company_code']);

				for($g = 0; $g < count($_POST['hidden_contact_phone']); $g++)
                {
                    if($_POST['hidden_contact_phone'][$g] != "")
                    {
                        $contactPhone['client_contact_info_id'] = $client_contact_info_id;
                        $contactPhone['phone'] = strtoupper($_POST['hidden_contact_phone'][$g]);
                        if($_POST['contact_phone_primary'] == $_POST['hidden_contact_phone'][$g])
                        {
                            $contactPhone['primary_phone'] = 1;
                        }
                        else
                        {
                            $contactPhone['primary_phone'] = 0;
                        }
                        $this->db->insert('client_contact_info_phone', $contactPhone);
                    }
                }

                for($g = 0; $g < count($_POST['contact_email']); $g++)
                {
                    if($_POST['contact_email'][$g] != "")
                    {
                        $contactEmail['client_contact_info_id'] = $client_contact_info_id;
                        $contactEmail['email'] = strtoupper($_POST['contact_email'][$g]);
                        if($_POST['contact_email_primary'] == $_POST['contact_email'][$g])
                        {
                            $contactEmail['primary_email'] = 1;
                        }
                        else
                        {
                            $contactEmail['primary_email'] = 0;
                        }
                        $this->db->insert('client_contact_info_email', $contactEmail);
                    }
                }
			} 
			else 
			{	
				$this->db->where(array("company_code" => $_POST['company_code']));
				$this->db->update("client_contact_info",$client_contact_info);
				$this->save_audit_trail("Clients", "Contact Information", "Contact information is updated.", $_POST['company_code']);

				$client_contact_information = $query->result_array(); 
				$client_contact_info_id = $client_contact_information[0]["id"];

				$this->db->delete("client_contact_info_phone",array('client_contact_info_id'=>$client_contact_info_id));

				for($g = 0; $g < count($_POST['hidden_contact_phone']); $g++)
                {
                    if($_POST['hidden_contact_phone'][$g] != "")
                    {
                        $contactPhone['client_contact_info_id'] = $client_contact_info_id;
                        $contactPhone['phone'] = strtoupper($_POST['hidden_contact_phone'][$g]);
                        if($_POST['contact_phone_primary'] == $_POST['hidden_contact_phone'][$g])
                        {
                            $contactPhone['primary_phone'] = 1;
                        }
                        else
                        {
                            $contactPhone['primary_phone'] = 0;
                        }
                        $this->db->insert('client_contact_info_phone', $contactPhone);
                    }
                }

                $this->db->delete("client_contact_info_email",array('client_contact_info_id'=>$client_contact_info_id));

                for($g = 0; $g < count($_POST['contact_email']); $g++)
                {
                    if($_POST['contact_email'][$g] != "")
                    {
                        $contactEmail['client_contact_info_id'] = $client_contact_info_id;
                        $contactEmail['email'] = strtoupper($_POST['contact_email'][$g]);
                        if($_POST['contact_email_primary'] == $_POST['contact_email'][$g])
                        {
                            $contactEmail['primary_email'] = 1;
                        }
                        else
                        {
                            $contactEmail['primary_email'] = 0;
                        }
                        $this->db->insert('client_contact_info_email', $contactEmail);
                    }
                }
			}

			if($this->session->userdata('qb_company_id') != "")
			{
				$client_query = $this->db->query("SELECT client.*, client_qb_id.currency_name FROM client LEFT JOIN client_qb_id ON client_qb_id.company_code = client.company_code WHERE client.company_code = '".$_POST['company_code']."'");
				$client_query = $client_query->result_array();

				for($t = 0; $t < count($client_query); $t++)
				{
					$qb_status = $this->import_each_client_to_quickbook($client_query[$t]["id"], $client_query[$t]["currency_name"]);
				}
			}
			else
			{
				$qb_status["Status"] = 1;
				$qb_status["message"] = 'Information Updated';
				$qb_status["title"] = 'Updated';
			}

			echo json_encode(array("Status" => $qb_status["Status"], 'message' => $qb_status["message"], 'title' => $qb_status["title"]));
        }
    }

    public function submit_reminder()
    {
    	$this->db->delete("client_setup_reminder",array('company_code'=>$_POST['company_code']));

		if($_POST['select_reminder'] != null)
		{
			for($g = 0; $g < count($_POST['select_reminder']); $g++)
            {
            	$reminder['company_code'] = $_POST['company_code'];
            	$reminder['selected_reminder'] = $_POST['select_reminder'][$g];

            	$this->db->insert('client_setup_reminder', $reminder);
            }

            $this->save_audit_trail("Clients", "Reminder", "Reminder is updated.", $_POST['company_code']);
		}

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
    }

    public function submit_related_group()
    {
    	$client_id = $_POST["related_group_client_id"];
    	$select_group_id = $_POST["select_group"];
    	$select_related_party_id = $_POST["select_related_party"];

    	$this->db->where('selected_head_client_id = '.$client_id.' OR selected_child_client_id = '.$client_id);
    	$this->db->delete("client_setup_group");

    	$this->db->where('selected_head_client_id = '.$client_id.' OR selected_child_client_id = '.$client_id);
    	$this->db->delete("client_setup_related_party");

		if($select_group_id != null)
		{
			for($g = 0; $g < count($select_group_id); $g++)
            {
            	$first_group['selected_head_client_id'] = $client_id;
            	$first_group['selected_child_client_id'] = $select_group_id[$g];
            	$this->db->insert('client_setup_group', $first_group);

            	$second_group['selected_head_client_id'] = $select_group_id[$g];
            	$second_group['selected_child_client_id'] = $client_id;
            	$this->db->insert('client_setup_group', $second_group);
            }
		}

		if($select_related_party_id != null)
		{
			for($g = 0; $g < count($select_related_party_id); $g++)
            {
            	$first_related_party['selected_head_client_id'] = $client_id;
            	$first_related_party['selected_child_client_id'] = $select_related_party_id[$g];
            	$this->db->insert('client_setup_related_party', $first_related_party);

            	$second_related_party['selected_head_client_id'] = $select_related_party_id[$g];
            	$second_related_party['selected_child_client_id'] = $client_id;
            	$this->db->insert('client_setup_related_party', $second_related_party);
            }
		}

		if($select_group_id != null || $select_related_party_id != null)
		{
			$this->save_audit_trail("Clients", "Group/Related Party", "Group/Related Party is updated.", $_POST['company_code']);
		}

		echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
    }

    public function submit_corporate_representative()
    {
    	$client_info = $this->db->query("select * from client where company_code = '".$_POST['company_code']."'");

		$client_info = $client_info->result_array();

		$this->db->delete("corporate_representative",array('registration_no'=>$this->encryption->decrypt($client_info[0]["registration_no"])));

		for($g = 0; $g < count($_POST['subsidiary_name']); $g++)
        {
            if($_POST['subsidiary_name'][$g] != "")
            {
                $corp_rep['registration_no'] = $this->encryption->decrypt($client_info[0]["registration_no"]);
                $corp_rep['subsidiary_name'] = strtoupper($_POST['subsidiary_name'][$g]);
                $corp_rep['name_of_corp_rep'] = strtoupper($_POST['corp_rep_name'][$g]);
                $corp_rep['identity_number'] = strtoupper($_POST['corp_rep_identity_number'][$g]);
                $corp_rep['effective_date'] = $_POST['date_of_appointment'][$g];
                $corp_rep['cessation_date'] = $_POST['date_of_cessation'][$g];

                $this->db->insert('corporate_representative', $corp_rep);
            }
        }

        if(count($_POST['subsidiary_name']) > 0)
        {
        	$this->save_audit_trail("Clients", "Corporate Representative", "Corporate Representative is updated.", $_POST['company_code']);
        }

        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
    }

	public function add_setup_info()
	{
		$this->form_validation->set_rules('contact_email[]', 'Email', 'valid_email');
        $this->form_validation->set_rules('contact_phone[]', 'Phone Number', 'numeric');

        if(count($_POST['hidden_contact_phone']) > 1 && $_POST['contact_phone_primary'] == null)
        {
            $validate_contact_phone_primary = FALSE;
        }
        else
        {
            $validate_contact_phone_primary = TRUE;
        }

        if(count($_POST['contact_email']) > 1 && $_POST['contact_email_primary'] == null)
        {
            $validate_contact_email_primary = FALSE;
        }
        else
        {
            $validate_contact_email_primary = TRUE;
        }

        if ($this->form_validation->run() == FALSE || $validate_contact_phone_primary == FALSE || $validate_contact_email_primary == FALSE)
        {
        	if($validate_contact_phone_primary == FALSE)
            {
                $validate_contact_phone = "Please set the primary field.";
            }
            else
            {
                $validate_contact_phone = strip_tags(form_error('contact_phone[]'));
            }

            if($validate_contact_email_primary == FALSE)
            {
                $validate_contact_email = "Please set the primary field.";
            }
            else
            {
                $validate_contact_email = strip_tags(form_error('contact_email[]'));
            }

            $arr = array(

                'contact_phone' => $validate_contact_phone,
                'contact_email' => $validate_contact_email,
            );

            echo json_encode(array("Status" => 0, "error" => $arr, 'message' => 'Please complete all required field', 'title' => 'Error'));
        }
        else
        {

			//echo json_encode($_POST['select_reminder']);

			
			

			$client_contact_info['company_code'] = $_POST['company_code'];
			$client_contact_info['name'] = strtoupper($_POST['contact_name']);
			// $client_contact_info['phone'] = $_POST['contact_phone'];
			// $client_contact_info['email'] = $_POST['contact_email'];

			//$billing['amount'] = 0;
			

			$query = $this->db->get_where("client_contact_info", array("company_code" => $_POST['company_code']));

			if (!$query->num_rows())
			{				
				$this->db->insert("client_contact_info",$client_contact_info);
				$client_contact_info_id = $this->db->insert_id();
				for($g = 0; $g < count($_POST['hidden_contact_phone']); $g++)
                {
                    if($_POST['hidden_contact_phone'][$g] != "")
                    {
                        $contactPhone['client_contact_info_id'] = $client_contact_info_id;
                        $contactPhone['phone'] = strtoupper($_POST['hidden_contact_phone'][$g]);
                        if($_POST['contact_phone_primary'] == $_POST['hidden_contact_phone'][$g])
                        {
                            $contactPhone['primary_phone'] = 1;
                        }
                        else
                        {
                            $contactPhone['primary_phone'] = 0;
                        }
                        $this->db->insert('client_contact_info_phone', $contactPhone);
                    }
                }

                for($g = 0; $g < count($_POST['contact_email']); $g++)
                {
                    if($_POST['contact_email'][$g] != "")
                    {
                        $contactEmail['client_contact_info_id'] = $client_contact_info_id;
                        $contactEmail['email'] = strtoupper($_POST['contact_email'][$g]);
                        if($_POST['contact_email_primary'] == $_POST['contact_email'][$g])
                        {
                            $contactEmail['primary_email'] = 1;
                        }
                        else
                        {
                            $contactEmail['primary_email'] = 0;
                        }
                        $this->db->insert('client_contact_info_email', $contactEmail);
                    }
                }
			} 
			else 
			{	
				$this->db->where(array("company_code" => $_POST['company_code']));
				$this->db->update("client_contact_info",$client_contact_info);
				$client_contact_information = $query->result_array(); 
				$client_contact_info_id = $client_contact_information[0]["id"];

				$this->db->delete("client_contact_info_phone",array('client_contact_info_id'=>$client_contact_info_id));

				for($g = 0; $g < count($_POST['hidden_contact_phone']); $g++)
                {
                    if($_POST['hidden_contact_phone'][$g] != "")
                    {
                        $contactPhone['client_contact_info_id'] = $client_contact_info_id;
                        $contactPhone['phone'] = strtoupper($_POST['hidden_contact_phone'][$g]);
                        if($_POST['contact_phone_primary'] == $_POST['hidden_contact_phone'][$g])
                        {
                            $contactPhone['primary_phone'] = 1;
                        }
                        else
                        {
                            $contactPhone['primary_phone'] = 0;
                        }
                        $this->db->insert('client_contact_info_phone', $contactPhone);
                    }
                }

                $this->db->delete("client_contact_info_email",array('client_contact_info_id'=>$client_contact_info_id));

                for($g = 0; $g < count($_POST['contact_email']); $g++)
                {
                    if($_POST['contact_email'][$g] != "")
                    {
                        $contactEmail['client_contact_info_id'] = $client_contact_info_id;
                        $contactEmail['email'] = strtoupper($_POST['contact_email'][$g]);
                        if($_POST['contact_email_primary'] == $_POST['contact_email'][$g])
                        {
                            $contactEmail['primary_email'] = 1;
                        }
                        else
                        {
                            $contactEmail['primary_email'] = 0;
                        }
                        $this->db->insert('client_contact_info_email', $contactEmail);
                    }
                }
			}

			$this->db->delete("client_setup_reminder",array('company_code'=>$_POST['company_code']));

			if($_POST['select_reminder'] != null)
			{
				for($g = 0; $g < count($_POST['select_reminder']); $g++)
                {
                	$reminder['company_code'] = $_POST['company_code'];
                	$reminder['selected_reminder'] = $_POST['select_reminder'][$g];

                	$this->db->insert('client_setup_reminder', $reminder);
                }
			}

			$client_info = $this->db->query("select * from client where company_code = '".$_POST['company_code']."'");

			$client_info = $client_info->result_array();

			$this->db->delete("corporate_representative",array('registration_no'=>$client_info[0]["registration_no"]));

			for($g = 0; $g < count($_POST['subsidiary_name']); $g++)
            {
                if($_POST['subsidiary_name'][$g] != "")
                {
                    $corp_rep['registration_no'] = $client_info[0]["registration_no"];
                    $corp_rep['subsidiary_name'] = strtoupper($_POST['subsidiary_name'][$g]);
                    $corp_rep['name_of_corp_rep'] = strtoupper($_POST['corp_rep_name'][$g]);
                    $corp_rep['identity_number'] = strtoupper($_POST['corp_rep_identity_number'][$g]);
                    $corp_rep['effective_date'] = $_POST['date_of_appointment'][$g];
                    $corp_rep['cessation_date'] = $_POST['date_of_cessation'][$g];

                    $this->db->insert('corporate_representative', $corp_rep);
                }
            }

			echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', 'client_id' => $client_info[0]['id']));
			//redirect("masterclient/edit/".$this->session->userdata('client_id')."");	
		}
	}
	public function check_incorporation_date()
	{
		$company_code = $_POST["company_code"];

		$q = $this->db->query("select incorporation_date from client where company_code = '".$company_code."'");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        echo FALSE;

	}
	public function change_auto_generate() 
	{
		$company_code = $_POST['company_code'];

		$this->db->set("auto_generate", 1);
		$this->db->where(array("company_code" => $company_code));
		$this->db->update("client");

		$this->db->where(array("company_code" => $company_code));
		$id = $this->db->get('client')->row()->id;


		echo json_encode(array('message' => 'Information Updated', 'title' => 'Updated', 'client_id' => $id));
	}

	public function search_client_billing()
	{
		$company_code = $_POST["company_code"];

		$service_category = $this->db->query("select * from billing_info_service_category");
        $service_category = $service_category->result_array();
        for($j = 0; $j < count($service_category); $j++)
        {
            $info[$service_category[$j]['id']] = $service_category[$j]['category_description'];
        }
        $this->data["service_category"] = $info;

        $this->data['client_billing_info'] = $this->master_model->get_all_client_billing_info($company_code);
		if($this->data['client_billing_info'] == false)
		{
			$this->data['client_billing_info'] = $this->master_model->get_all_default_client_service();
		}

        echo json_encode($this->data);
	}

	public function edit ($id = null, $tab = null) //edit_client
	{

		if(isset($_SESSION['open_unique_code']) && $_SESSION['open_unique_code'] !='')
		{
			$unique_code =$_SESSION['open_unique_code'];
			$this->data['client'] = $this->db_model->getClientbyUcode($unique_code);
			// exit();
			$this->session->set_userdata('open_unique_code','');
		}else{
			$this->data['client'] = $this->db_model->getClientbyID($id);
			//echo json_encode($this->data['client']);
			$company_code =$this->data['client']->company_code;
			$registration_no =$this->data['client']->registration_no;
			//$unique_code =$this->data['client']->unique_code;
		}
			// echo $unique_code;
		// print_r($_SESSION['open_unique_code']);
		if($tab == "filing")
		{
			$this->data['tab'] = "filing";
		}
		else if($tab == "setup")
		{
			$this->data['tab'] = "setup";
		}
		else if($tab == "billing")
		{
			$this->data['tab'] = "billing";
		}
		else
		{
			$this->data['tab'] = null;
		}
		//destroy session for setup tab
		$this->session->unset_userdata('chairman');
		$this->session->unset_userdata('director_signature_1');
		$this->session->unset_userdata('director_signature_2');

		$this->data['sharetype'] = $this->master_model->get_all_share_type();
		//$this->data['service'] = $this->master_model->get_all_service();
		$this->data['currency'] = $this->master_model->get_all_currency();
		$this->data['citizen'] = $this->master_model->get_all_citizen();
		$this->data['citizen'] = $this->master_model->get_all_citizen();
		$this->data['typeofdoc'] = $this->master_model->get_all_typeofdoc();
		//$this->data['doccategory'] = $this->master_model->get_all_doccategory();
		//$this->session->set_userdata('unique_code', $unique_code);
		$this->session->set_userdata('company_code', $company_code);
		$this->session->set_userdata('client_id', $id);

		$this->data['client_officers'] =$this->db_model->getClientOfficer($company_code);
		$this->data['client_guarantee'] =$this->db_model->getClientGuarantee($company_code);
		$this->data['client_controller'] =$this->db_model->getClientController($company_code);
		$this->data['client_nominee_director'] =$this->db_model->getClientNomineeDirector($company_code);
		$this->data['client_charges'] = $this->master_model->get_all_chargee($company_code);
		$this->data['client_share_capital'] = $this->master_model->get_all_client_share_capital($company_code);
		//$this->data['allotment'] = $this->master_model->get_all_allotment_group($company_code);
		$this->data['member'] = $this->master_model->get_all_member($company_code);
		$this->data['member_certificate'] = $this->master_model->get_all_member_certificate($company_code);
		$this->data['client_signing_info'] = $this->master_model->get_all_client_signing_info($company_code);
		$this->data['client_contact_info'] = $this->master_model->get_all_client_contact_info($company_code);
		$this->data['client_reminder_info'] = $this->master_model->get_all_client_reminder_info($company_code);
		$this->data['client_setup_group_info'] = $this->master_model->get_all_client_setup_group_info($id);
		$this->data['client_setup_related_party_info'] = $this->master_model->get_all_client_setup_related_party_info($id);
		$this->data['client_billing_info'] = $this->master_model->get_all_client_billing_info($company_code);
		if($this->data['client_billing_info'] == false)
		{
			$this->data['client_billing_info'] = $this->master_model->get_all_default_client_service();
		}
		$this->data['filing_data'] = $this->master_model->get_all_filing_data($company_code);
		$this->data['eci_filing_data'] = $this->master_model->get_all_eci_filing_data($company_code);
		$this->data['tax_filing_data'] = $this->master_model->get_all_tax_filing_data($company_code);
		$this->data['gst_filing_data'] = $this->master_model->get_all_gst_filing_data($company_code);
		$this->data['template'] = $this->master_model->get_all_template_data($company_code);
		$this->data['director_retiring'] = $this->db_model->get_all_director_retiring($company_code);
		$this->data['corp_rep_data'] = $this->db_model->get_all_corp_rep($registration_no);
		$this->data['transaction'] = $this->master_model->get_all_transaction_in_client_module($company_code, $_SESSION['group_id']);
		$this->data['list_of_confirmation_auditor'] = $this->master_model->get_all_list_of_confirmation_auditor($company_code, $_SESSION['group_id']);
		$this->data['list_of_company_document'] = $this->master_model->get_all_list_of_company_document($company_code);
		$this->data['firm_info'] = $this->master_model->get_firm_info();

		
		//$this->data['officer'] =$this->db_model->getOfficerUC($unique_code);
		// $this->data['issued_sharetype'] = $this->master_model->get_all_issued_sharetype($unique_code);
		//$this->data['paid_share'] = $this->master_model->get_all_paid_share($unique_code);
		//$this->data['member_capital'] = $this->master_model->get_all_member_capital($unique_code);
		$this->data['person'] = $this->master_model->get_all_person();
		//$this->data['chargee'] = $this->master_model->get_all_chargee($unique_code);
		//$this->data['client_others'] = $this->master_model->get_typeofdoc($unique_code);
		//$this->data['allotment_member'] = $this->master_model->get_all_alotment_member($unique_code);
		
		//$this->data['client_service'] = $this->master_model->get_all_client_service($unique_code);
		//$this->data['client_setup'] = $this->master_model->get_all_client_setup($unique_code);


		//$registered_address = $this->db->query("select postal_code, street_name, building_name, unit_no1, unit_no2 from firm ");

		$this->db->select('our_service_registration_address.id, our_service_info.service_name, our_service_registration_address.postal_code, our_service_registration_address.street_name, our_service_registration_address.building_name, our_service_registration_address.unit_no1, our_service_registration_address.unit_no2, our_service_registration_address.foreign_address_1, our_service_registration_address.foreign_address_2, our_service_registration_address.foreign_address_3, gst_jurisdiction.jurisdiction as jurisdiction_name')
                ->from('firm')
                ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
                ->join('our_service_info', 'our_service_info.user_admin_code_id = '.$this->session->userdata('user_admin_code_id').' and service_type = 7', 'left')
                ->join('our_service_registration_address', 'our_service_registration_address.our_service_info_id = our_service_info.id', 'left')
                ->join('gst_jurisdiction', 'gst_jurisdiction.id = our_service_registration_address.jurisdiction_id', 'left')
                ->where('user_firm.user_id = '.$this->session->userdata('user_id'))
                ->where('user_firm.in_use = 1 AND our_service_info.deleted = 0');
        $registered_address = $this->db->get();

        $registered_address_info = $registered_address->result_array();
        $this->data['registered_address_info'] = $registered_address_info;

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Clients', base_url('masterclient'));
		$this->mybreadcrumb->add('Edit Client - '.$this->data['client']->company_name.'', base_url());
		$this->data['breadcrumbs'] = $this->mybreadcrumb->render();

		$this->data['first_time'] = true;
		// $this->data['login_user_id'] = $this->session->userdata('user_id');
        //echo json_encode($this->data);
			// $this->sma->print_arrays($this->data['member_capital']);
        $bc = array(array('link' => '#', 'page' => lang('Edit Client')));
        $meta = array('page_title' => lang('Edit Client'), 'bc' => $bc, 'page_name' => 'Edit Client');
		// $this->data['page_name'] = 'Clients';
        $this->page_construct('client/edit_client.php', $meta, $this->data);
		
	}

	public function get_latest_retire_director()
	{
		$company_code = $_POST["company_code"];
		$this->data['director_retiring'] = $this->db_model->get_all_director_retiring($company_code);
		echo json_encode($this->data);
	}

	public function filter_position()
	{
		$position = $_POST["search_position"];
		$company_code = $_POST["company_code"];

		$this->data['client_officers'] =$this->db_model->getSearchClientOfficer($position, $company_code);

		echo json_encode($this->data);
	}

	public function refresh_nominee_director()
	{
		$company_code = $_POST["company_code"];
		$this->data['client_nominee_director'] =$this->db_model->getClientNomineeDirector($company_code);

		echo json_encode(array("Status" => 1, 'info' => $this->data));
	}

	public function refresh_controller()
	{
		$company_code = $_POST["company_code"];
		$this->data['client_controller'] =$this->db_model->getClientController($company_code);

		echo json_encode(array("Status" => 1, 'info' => $this->data));
	}

	/*This is for refresh member*/

	public function refresh_member()
	{
		$company_code = $_POST["company_code"];

		$this->data['client_share_capital'] = $this->master_model->get_all_client_share_capital($company_code);
		$this->data['member'] = $this->master_model->get_all_member($company_code);
		$this->data['member_certificate'] = $this->master_model->get_all_member_certificate($company_code);

		echo json_encode(array("Status" => 1, 'info' => $this->data));

	}

	public function buyback ($company_code)
	{
        $bc = array(array('link' => '#', 'page' => lang('Create BuyBack')));
        $meta = array('page_title' => lang('Create BuyBack'), 'bc' => $bc, 'page_name' => 'Create BuyBack');
		// $this->data['page_name'] = 'Clients';
		/*$this->data['unique_code'] = $unique_code;
		$this->data['sharetype'] = $this->master_model->get_all_share_type();
		$this->data['service'] = $this->master_model->get_all_service();
		$this->data['currency'] = $this->master_model->get_all_currency();*/
		$this->data['company_code'] = $company_code;
		//$this->data['sharetype'] = $this->master_model->get_all_share_type($company_code);
		$this->data['company_class'] = $this->master_model->get_all_exist_company_share_type($company_code);

		$r = $this->db->query("select status, company_name from client where company_code = '".$company_code."'");
        $r = $r->result_array();

		$this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Buyback', base_url('masterclient/view_buyback/'.$company_code.''));
		$this->mybreadcrumb->add('Create Buyback - '.$this->encryption->decrypt($r[0]["company_name"]).'', base_url());
		$this->data['breadcrumbs'] = $this->mybreadcrumb->render();

		$this->data["client_status"] = $r[0]["status"];

        $this->page_construct('client/buyback.php', $meta, $this->data);
		
	}

	public function view_buyback ($company_code)
	{
        $bc = array(array('link' => '#', 'page' => lang('Buyback')));
        $meta = array('page_title' => lang('Buyback'), 'bc' => $bc, 'page_name' => 'Buyback');
		
		if (isset($_POST['type'])) $type = $_POST['type'];
		if (isset($_POST['search'])) $search = $_POST['search'];

		if($type == null)
        {
            $type = "all";
        }
        else 
        {
            $type = $_POST['type'];
        }
        $this->data['type'] = $type;

		$this->data['company_code'] = $company_code;
		//$this->data['sharetype'] = $this->master_model->get_all_share_type($company_code);
		$this->data['company_class'] = $this->master_model->get_all_exist_company_share_type($company_code);
		//$this->data['client_share_capital'] = $this->master_model->get_all_client_share_capital($company_code);
		$this->data['buyback'] = $this->master_model->get_all_buyback_to_view($search,$type,$company_code);

		$r = $this->db->query("select status, company_name from client where company_code = '".$company_code."'");
        $r = $r->result_array();
		$this->data["client_status"] = $r[0]["status"];
		/*$allotment_id = array();
        $this->session->set_userdata(array(
            'allotment_id'  =>  $allotment_id,
        ));*/
		/*$this->data['service'] = $this->master_model->get_all_service();
		$this->data['currency'] = $this->master_model->get_all_currency();*/
        $this->page_construct('client/buyback_edit.php', $meta, $this->data);

		
	}

	public function edit_buyback ($transaction_id, $client_member_share_capital_id, $company_code) 
	{
		$bc = array(array('link' => '#', 'page' => lang('Edit Buyback')));
        $meta = array('page_title' => lang('Edit Buyback'), 'bc' => $bc, 'page_name' => 'Edit Buyback');
		// $this->data['page_name'] = 'Clients';
		//$this->data['unique_code'] = $unique_code;
		$this->data['company_code'] = $company_code;
		//$this->data['sharetype'] = $this->master_model->get_all_share_type($company_code);
		$this->data['company_class'] = $this->master_model->get_all_non_exist_company_share_type($client_member_share_capital_id,$company_code);
		$this->data['buyback'] = $this->master_model->get_edit_buyback_group($transaction_id, $company_code);

		$r = $this->db->query("select status, company_name from client where company_code = '".$company_code."'");
        $r = $r->result_array();

		$this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Buyback', base_url('masterclient/view_buyback/'.$company_code.''));
		$this->mybreadcrumb->add('Edit Buyback - '.$this->encryption->decrypt($r[0]["company_name"]).'', base_url());
		$this->data['breadcrumbs'] = $this->mybreadcrumb->render();

		$this->data["client_status"] = $r[0]["status"];
		/*$this->data['service'] = $this->master_model->get_all_service();
		$this->data['currency'] = $this->master_model->get_all_currency();*/
        $this->page_construct('client/buyback.php', $meta, $this->data);
	}

	public function get_amount_share()
	{
		$id = $_POST["id"];
		$officer_id = $_POST["officer_id"];
		$field_type = $_POST["field_type"];

		$q = $this->db->query('select sum(amount_share) as amount_share from member_shares where id < "'.$id.'" AND officer_id="'.$officer_id.'" AND field_type = "'.$field_type.'"');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        echo (FALSE);

	}
	public function view_transfer ($company_code)
	{
        $bc = array(array('link' => '#', 'page' => lang('Transfer')));
        $meta = array('page_title' => lang('Transfer'), 'bc' => $bc, 'page_name' => 'Transfer');
		
		if (isset($_POST['type'])) 
		{
			$type = $_POST['type'];
		}
		else
		{
			$type = null;
		}
		if (isset($_POST['search'])) 
		{
			$search = $_POST['search'];
		}
		else
		{
			$search = null;
		}

		if($type == null)
        {
            $type = "all";
        }
        else 
        {
            $type = $_POST['type'];
        }
        $this->data['type'] = $type;

		$this->data['company_code'] = $company_code;
		//$this->data['sharetype'] = $this->master_model->get_all_share_type($company_code);
		$this->data['company_class'] = $this->master_model->get_all_exist_company_share_type($company_code);
		//$this->data['client_share_capital'] = $this->master_model->get_all_client_share_capital($company_code);
		$this->data['transfer'] = $this->master_model->get_all_transfer_to_view($search,$type,$company_code);

		$r = $this->db->query("select status, company_name from client where company_code = '".$company_code."'");
        $r = $r->result_array();
		$this->data["client_status"] = $r[0]["status"];

		$transfer_id = array();
        $this->session->set_userdata(array(
            'transfer_id'  =>  $transfer_id,
        ));

        $to_id = array();
        $this->session->set_userdata(array(
            'to_id'  =>  $to_id,
        ));
		/*$this->data['service'] = $this->master_model->get_all_service();
		$this->data['currency'] = $this->master_model->get_all_currency();*/
        $this->page_construct('client/transfer_edit.php', $meta, $this->data);
		
	}

	public function transfer ($company_code)
	{
        $bc = array(array('link' => '#', 'page' => lang('Create Transfer')));
        $meta = array('page_title' => lang('Create Transfer'), 'bc' => $bc, 'page_name' => 'Create Transfer');
		// $this->data['page_name'] = 'Clients';
		$this->data['company_code'] = $company_code;
		//$this->data['sharetype'] = $this->master_model->get_all_share_type($company_code);
		$this->data['company_class'] = $this->master_model->get_all_exist_company_share_type($company_code);

		$r = $this->db->query("select status, company_name from client where company_code = '".$company_code."'");
        $r = $r->result_array();

		$this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Transfer', base_url('masterclient/view_transfer/'.$company_code.''));
		$this->mybreadcrumb->add('Create Transfer - '.$this->encryption->decrypt($r[0]["company_name"]).'', base_url());
		$this->data['breadcrumbs'] = $this->mybreadcrumb->render();

		$this->data["client_status"] = $r[0]["status"];

        $this->page_construct('client/transfer.php', $meta, $this->data);
		
	}

	public function edit_transfer ($transaction_id, $client_member_share_capital_id, $company_code) 
	{
		$bc = array(array('link' => '#', 'page' => lang('Edit Transfer')));
        $meta = array('page_title' => lang('Edit Transfer'), 'bc' => $bc, 'page_name' => 'Edit Transfer');
		// $this->data['page_name'] = 'Clients';
		//$this->data['unique_code'] = $unique_code;
		$this->data['company_code'] = $company_code;
		//$this->data['sharetype'] = $this->master_model->get_all_share_type($company_code);
		$this->data['company_class'] = $this->master_model->get_all_non_exist_company_share_type($client_member_share_capital_id,$company_code);
		$this->data['transfer'] = $this->master_model->get_edit_transfer_group($transaction_id, $company_code);
		/*$this->data['service'] = $this->master_model->get_all_service();
		$this->data['currency'] = $this->master_model->get_all_currency();*/

		$r = $this->db->query("select status, company_name from client where company_code = '".$company_code."'");
        $r = $r->result_array();


		$this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Transfer', base_url('masterclient/view_transfer/'.$company_code.''));
		$this->mybreadcrumb->add('Edit Transfer - '.$this->encryption->decrypt($r[0]["company_name"]).'', base_url());
		$this->data['breadcrumbs'] = $this->mybreadcrumb->render();

		$this->data["client_status"] = $r[0]["status"];
		$this->data["transaction_id"] = $transaction_id;

		$transfer_id = array();
        $this->session->set_userdata(array(
            'transfer_id'  =>  $transfer_id,
        ));

        $to_id = array();
        $this->session->set_userdata(array(
            'to_id'  =>  $to_id,
        ));

        $this->page_construct('client/transfer.php', $meta, $this->data);
	}


	public function get_allotment_people()
	{
		$client_member_share_capital_id = $_POST["client_member_share_capital_id"];
		$company_code = $_POST["company_code"];

		//echo json_encode($client_member_share_capital_id);
		
		// $q = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" where member_shares.company_code="'.$company_code.'" AND member_shares.client_member_share_capital_id = "'.$client_member_share_capital_id.'" AND member_shares.number_of_share < 0 GROUP BY member_shares.field_type, member_shares.officer_id HAVING sum(member_shares.number_of_share) != 0
		// 	UNION ALL select member_shares.*, (member_shares.number_of_share) as number_of_share, (member_shares.amount_share) as amount_share, (member_shares.no_of_share_paid) as no_of_share_paid, (member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" where member_shares.company_code="'.$company_code.'" AND member_shares.client_member_share_capital_id = "'.$client_member_share_capital_id.'" AND member_shares.number_of_share > 0');

		//$q = $this->db->query('select certificate.*, (certificate.number_of_share) as number_of_share, (certificate.amount_share) as amount_share, (certificate.no_of_share_paid) as no_of_share_paid, (certificate.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from certificate left join officer on certificate.officer_id = officer.id and certificate.field_type = officer.field_type left join officer_company on certificate.officer_id = officer_company.id and certificate.field_type = officer_company.field_type left join client_member_share_capital as share_capital on certificate.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = certificate.officer_id and certificate.field_type = "client" where certificate.company_code="'.$company_code.'" AND certificate.client_member_share_capital_id = "'.$client_member_share_capital_id.'" AND certificate.number_of_share > 0 AND certificate.status = 1');

		$q = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" and client.deleted = 0 where member_shares.company_code="'.$company_code.'" AND member_shares.client_member_share_capital_id = "'.$client_member_share_capital_id.'" GROUP BY member_shares.field_type, member_shares.officer_id HAVING sum(member_shares.number_of_share) != 0 AND member_shares.cert_status = 1');

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
                else
                {
                    $row->registration_no = $this->encryption->decrypt($row->registration_no);
                    $row->client_company_name = $this->encryption->decrypt($row->client_company_name);
                }
                $data[] = $row;
            }
            echo json_encode($data);
        }
        echo (FALSE);
	}

	public function get_buyback_people()
	{
		$client_member_share_capital_id = $_POST["client_member_share_capital_id"];
		$company_code = $_POST["company_code"];
		$transaction_date = $_POST["transaction_date"];

		//echo json_encode($client_member_share_capital_id);
		
		$q = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join certificate on certificate.officer_id = member_shares.officer_id AND certificate.field_type = member_shares.field_type AND certificate.transaction_id = member_shares.transaction_id left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" where member_shares.company_code="'.$company_code.'" AND member_shares.client_member_share_capital_id = "'.$client_member_share_capital_id.'" AND UNIX_TIMESTAMP(STR_TO_DATE("'.$transaction_date.'","%d/%m/%Y")) >= UNIX_TIMESTAMP(STR_TO_DATE(member_shares.transaction_date,"%d/%m/%Y")) GROUP BY member_shares.field_type, member_shares.officer_id HAVING sum(member_shares.number_of_share) != 0');
		//from_unixtime(UNIX_TIMESTAMP(STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y')),'%Y-%m-%d')
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
            echo json_encode($data);
        }
        echo (FALSE);
	}

	// public function get_transfer_people()
	// {
	// 	$client_member_share_capital_id = $_POST["client_member_share_capital_id"];
	// 	$company_code = $_POST["company_code"];
	// 	$transaction_id = $_POST["transaction_id"];
	// 	$transaction_date = $_POST["transaction_date"];

	// 	//echo json_encode($client_member_share_capital_id);
		
	// 	$q = $this->db->query('select certificate.*, (certificate.number_of_share) as number_of_share, (certificate.amount_share) as amount_share, (certificate.no_of_share_paid) as no_of_share_paid, (certificate.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from certificate left join officer on certificate.officer_id = officer.id and certificate.field_type = officer.field_type left join officer_company on certificate.officer_id = officer_company.id and certificate.field_type = officer_company.field_type left join client_member_share_capital as share_capital on certificate.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = certificate.officer_id and certificate.field_type = "client" where certificate.company_code="'.$company_code.'" AND certificate.client_member_share_capital_id = "'.$client_member_share_capital_id.'" AND certificate.number_of_share > 0 AND certificate.status = 1');

 //        if ($q->num_rows() > 0) {
 //            foreach (($q->result()) as $row) {
 //                $data[] = $row;
 //            }
 //            echo json_encode($data);
 //        }
 //        echo (FALSE);
	// }

	public function save_transfer()
	{
		$index = 0; $total_no_of_share = 0; $total_amount_share = 0; $per_share = 0;

		$transaction_task_id = $_POST["transaction_task_id"];
		$client_member_share_capital_id = $_POST["client_member_share_capital_id"];
		$registration_no = $_POST["registration_no"];
		$company_code = $_POST["company_code"];
		$date = $_POST["date"];

		$transferor_number_of_shares_to_transfer = $_POST["assign_number_of_share"];
		$transferor_new_number_of_share = $_POST["new_number_of_share"];
		$transferor_officer_id = array_values($_POST["cert_officer_id"]);
		$transferor_field_type = array_values($_POST["cert_field_type"]);
		$transferor_certificate_id = $_POST["certificate_id"];
		$transferor_sharetype = $_POST["sharetype"];
		$transferor_certificate = $_POST["certificate"];
		$person_name = json_decode($_POST["person_name"]);

		$transferee_new_number_of_share = $_POST["transferee_new_number_of_share"];
		$transferee_officer_id = array_values($_POST["transferee_officer_id"]);
		$transferee_field_type = array_values($_POST["transferee_field_type"]);
		$transferee_certificate_id = $_POST["transferee_certificate_id"];
		$transferee_sharetype = $_POST["transferee_sharetype"];
		$transferee_certificate = $_POST["transferee_certificate"];
		$shareTransferInfoArray = json_decode($_POST["shareTransferInfoArray"]);
		$to_person_name = json_decode($_POST["to_person_name"]);

		foreach($transferor_officer_id as $key => $value)
		{
			$transferor_item = array(
				'number_of_shares_to_transfer' => $transferor_number_of_shares_to_transfer[$key],
				'new_number_of_share' => $transferor_new_number_of_share[$key],
				'officer_id' => $transferor_officer_id[$key],
				'field_type' => $transferor_field_type[$key],
				'certificate_id' => $transferor_certificate_id[$key],
				'sharetype' => trim($transferor_sharetype[$key]),
				'certificate' => $transferor_certificate[$key],
				'person_name' => $person_name[$key]
			);
			$transferor_array[] = $transferor_item;
		}

		foreach($transferee_officer_id as $key => $value)
		{
			$transferee_item = array(
				'new_number_of_share' => $transferee_new_number_of_share[$key],
				'officer_id' => $transferee_officer_id[$key],
				'field_type' => $transferee_field_type[$key],
				'certificate_id' => $transferee_certificate_id[$key],
				'sharetype' => trim($transferee_sharetype[$key]),
				'certificate' => $transferee_certificate[$key],
				'to_person_name' => $to_person_name[$key]
			);
			$transferee_array[] = $transferee_item;
		}

        //check the total number of share
        $member_query = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid from member_shares left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id HAVING sum(member_shares.number_of_share) != 0');
        $member_query = $member_query->result_array();

        for($p = 0; $p < count($member_query); $p++)
        {
            $total_no_of_share += (int)str_replace(',', '',$member_query[$p]['number_of_share']);
            $total_amount_share += (int)str_replace(',', '',$member_query[$p]['amount_share']);
        }
        //calculate how much per share
        $per_share = $total_amount_share/$total_no_of_share;

        //the share movement for transferor array
        foreach (($transferor_array) as $row)
        {
            if($row["number_of_shares_to_transfer"] != "" && $row["number_of_shares_to_transfer"] != "0")
            {
                if($row["new_number_of_share"] == "0")
                {
                    $transaction_id = "TR-".mt_rand(100000000, 999999999);
                    $previous_cert_query = $this->db->query('select * from certificate where id = '.$row["certificate_id"]);
                    $previous_cert_query = $previous_cert_query->result_array();

                    $previous_cert_status['status'] = 2;
                    $this->db->update("certificate",$previous_cert_status,array("id" => $row["certificate_id"]));

                    //member_shares
                    $member_share_info["company_code"] = $previous_cert_query[0]["company_code"];
                    $member_share_info["client_member_share_capital_id"] = $previous_cert_query[0]["client_member_share_capital_id"];
                    $member_share_info["officer_id"] = $row["officer_id"];
                    $member_share_info["field_type"] = $row["field_type"];
                    $member_share_info["transaction_id"] = $transaction_id;
                    $member_share_info["number_of_share"] = -(str_replace(',', '',$row["number_of_shares_to_transfer"]));
                    $member_share_info["amount_share"] = -((float)str_replace(',', '',$row["number_of_shares_to_transfer"]) * $per_share);
                    $member_share_info["no_of_share_paid"] = -(str_replace(',', '',$row["number_of_shares_to_transfer"]));
                    $member_share_info["amount_paid"] = -((float)str_replace(',', '',$row["number_of_shares_to_transfer"]) * $per_share);
                    $member_share_info["transaction_date"] = $date;
                    $member_share_info["transaction_type"] = "Transfer";
                    $member_share_info["consideration"] = 0;
                    $member_share_info["cert_status"] = 1;
                    $this->db->insert("member_shares",$member_share_info);

                    //certificate
                    $cert_info["company_code"] = $previous_cert_query[0]["company_code"];
                    $cert_info["client_member_share_capital_id"] = $previous_cert_query[0]["client_member_share_capital_id"];
                    $cert_info["officer_id"] = $row["officer_id"];
                    $cert_info["field_type"] = $row["field_type"];
                    $cert_info["transaction_id"] = $transaction_id;
                    $cert_info["number_of_share"] = 0;
                    $cert_info["amount_share"] = 0;
                    $cert_info["no_of_share_paid"] = 0;
                    $cert_info["amount_paid"] = 0;
                    $cert_info["certificate_no"] = $row["certificate"];
                    $cert_info["new_certificate_no"] = $row["certificate"];
                    $cert_info["status"] = 1;
                    $this->db->insert("certificate",$cert_info);
                }
                else
                {
                    $transaction_id = "TR-".mt_rand(100000000, 999999999);
                    $previous_cert_query = $this->db->query('select * from certificate where id = '.$row["certificate_id"]);
                    $previous_cert_query = $previous_cert_query->result_array();

                    $previous_cert_status['status'] = 2;
                    $this->db->update("certificate",$previous_cert_status,array("id" => $row["certificate_id"]));

                    //member_shares
                    $member_share_info["company_code"] = $previous_cert_query[0]["company_code"];
                    $member_share_info["client_member_share_capital_id"] = $previous_cert_query[0]["client_member_share_capital_id"];
                    $member_share_info["officer_id"] = $row["officer_id"];
                    $member_share_info["field_type"] = $row["field_type"];
                    $member_share_info["transaction_id"] = $transaction_id;
                    $member_share_info["number_of_share"] = -(str_replace(',', '', $row["number_of_shares_to_transfer"]));
                    $member_share_info["amount_share"] = -((float)str_replace(',', '',$row["number_of_shares_to_transfer"]) * $per_share);
                    $member_share_info["no_of_share_paid"] = -(str_replace(',', '', $row["number_of_shares_to_transfer"]));
                    $member_share_info["amount_paid"] = -((float)str_replace(',', '', $row["number_of_shares_to_transfer"]) * $per_share);
                    $member_share_info["transaction_date"] = $date;
                    $member_share_info["transaction_type"] = "Transfer";
                    $member_share_info["consideration"] = 0;
                    $member_share_info["cert_status"] = 1;
                    $this->db->insert("member_shares",$member_share_info);

                    //certificate
                    $cert_info["company_code"] = $previous_cert_query[0]["company_code"];
                    $cert_info["client_member_share_capital_id"] = $previous_cert_query[0]["client_member_share_capital_id"];
                    $cert_info["officer_id"] = $row["officer_id"];
                    $cert_info["field_type"] = $row["field_type"];
                    $cert_info["transaction_id"] = $transaction_id;
                    $cert_info["number_of_share"] = str_replace(',', '', $row["new_number_of_share"]);
                    $cert_info["amount_share"] = (float)str_replace(',', '', $row["new_number_of_share"]) * $per_share;
                    $cert_info["no_of_share_paid"] = str_replace(',', '', $row["new_number_of_share"]);
                    $cert_info["amount_paid"] = (float)str_replace(',', '', $row["new_number_of_share"]) * $per_share;
                    $cert_info["certificate_no"] = $row["certificate"];
                    $cert_info["new_certificate_no"] = $row["certificate"];
                    $cert_info["status"] = 1;
                    $this->db->insert("certificate",$cert_info);
                }
            } 
        }

        //the share movement for transferee array
        foreach (($transferee_array) as $row)
        {
            $transaction_id = "TR-".mt_rand(100000000, 999999999);

            //member_shares
            $member_share_info["company_code"] = $company_code;
            $member_share_info["client_member_share_capital_id"] = $client_member_share_capital_id;
            $member_share_info["officer_id"] = $row["officer_id"];
            $member_share_info["field_type"] = $row["field_type"];
            $member_share_info["transaction_id"] = $transaction_id;
            $member_share_info["number_of_share"] = $row["new_number_of_share"];
            $member_share_info["amount_share"] = ((float)$row["new_number_of_share"]) * $per_share;
            $member_share_info["no_of_share_paid"] = $row["new_number_of_share"];
            $member_share_info["amount_paid"] = ((float)$row["new_number_of_share"]) * $per_share;
            $member_share_info["transaction_date"] = $date;
            $member_share_info["transaction_type"] = "Transfer";
            $member_share_info["consideration"] = 0;
            $member_share_info["cert_status"] = 1;
            $this->db->insert("member_shares",$member_share_info);

            //certificate
            $cert_info["company_code"] = $company_code;
            $cert_info["client_member_share_capital_id"] = $client_member_share_capital_id;
            $cert_info["officer_id"] = $row["officer_id"];
            $cert_info["field_type"] = $row["field_type"];
            $cert_info["transaction_id"] = $transaction_id;
            $cert_info["number_of_share"] = $row["new_number_of_share"];
            $cert_info["amount_share"] = ((float)$row["new_number_of_share"]) * $per_share;
            $cert_info["no_of_share_paid"] = $row["new_number_of_share"];
            $cert_info["amount_paid"] = ((float)$row['new_number_of_share']) * $per_share;
            $cert_info["certificate_no"] = $row["certificate"];
            $cert_info["new_certificate_no"] = $row["certificate"];
            $cert_info["status"] = 1;
            $this->db->insert("certificate",$cert_info);
        }

        foreach (($shareTransferInfoArray) as $row) 
        {   
            $register_of_transfers["company_code"] = $row->company_code;
            $register_of_transfers["date"] = $date;
            $register_of_transfers["transferor_office_id"] = $row->officer_id;
            $register_of_transfers["transferor_field_type"] = $row->field_type;
            $register_of_transfers["transferee_office_id"] = $row->to_officer_id;
            $register_of_transfers["transferee_field_type"] = $row->to_field_type;
            $register_of_transfers["new_number_share"] = (int)str_replace(',', '', $row->number_of_share_to);
            $register_of_transfers["new_amount_share"] = (float)str_replace(',', '', $row->number_of_share_to) * $per_share;
            $register_of_transfers["sharetype"] = $row->sharetype;
            $register_of_transfers["other_class"] = $row->otherclass;
            $register_of_transfers["currency"] = $row->currency;
            
            $this->db->insert("register_of_transfers",$register_of_transfers);
            $register_of_transfers_id = $this->db->insert_id();

            if($row->otherclass != "")
            {
            	$class_name = $row->otherclass;
            }
            else
            {
            	$class_name = $row->sharetype;
            }

            $this->save_audit_trail("Clients", "Transfer", $row->person_name." transfer ".$row->number_of_share_to." ".$class_name." (".$row->currency.") to ".$row->to_person_name.".", $row->company_code);

            foreach (($transferee_array) as $transferee_row) 
            {
                if($register_of_transfers["transferee_office_id"] == $transferee_row["officer_id"] && $register_of_transfers["transferee_field_type"] == $transferee_row["field_type"])
                {
                    $new_cert_no["new_cert"] = $transferee_row["certificate"];
                    $this->db->update("register_of_transfers",$new_cert_no,array("id" => $register_of_transfers_id));
                }
            }

            foreach (($transferor_array) as $transferor_row) 
            {
                $cancel_cert_query = $this->db->query('select * from certificate where id = "'.$transferor_row['certificate_id'].'"');
                $cancel_cert_query = $cancel_cert_query->result_array();
                if($register_of_transfers["transferor_office_id"] == $transferor_row["officer_id"] && $register_of_transfers["transferor_field_type"] == $transferor_row["field_type"] && $transferor_row["number_of_shares_to_transfer"] != "")
                {
                    $register_of_transfers_info["register_of_transfers_id"] = $register_of_transfers_id;
                    $register_of_transfers_info["old_cert_id"] = $cancel_cert_query[0]["id"];
                    $register_of_transfers_info["old_number_share"] = $cancel_cert_query[0]["number_of_share"];
                    $register_of_transfers_info["old_amount_share"] = (float)$cancel_cert_query[0]["number_of_share"] * $per_share;
                    $register_of_transfers_info["old_cert"] = $cancel_cert_query[0]["certificate_no"];
                    $register_of_transfers_info["balance_number_share"] = str_replace(',', '', $transferor_row["new_number_of_share"]);
                    $register_of_transfers_info["balance_amount_share"] = (float)str_replace(',', '', $transferor_row["new_number_of_share"]) * $per_share;
                    $register_of_transfers_info["balance_cert"] = $transferor_row["certificate"];

                    $this->db->insert("register_of_transfers_info",$register_of_transfers_info);
                }
            }
        }

        $this->master_model->update_controller_detail($_POST['company_code'], $_POST['date']);
        $this->master_model->check_client_company_type($_POST['company_code']);

        $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
		$link = $protocol . $_SERVER['SERVER_NAME'] . '/' .$this->systemName.'/masterclient/view_transfer/'.$_POST['company_code'];

        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', 'link' => $link));
	}

	public function change_member_document_info($type, $client_member_share_capital_id, $old_member_date_result, $firm_id, $company_code, $previous_member_result = null, $client_id)
	{
		if($type == "add_allotment_of_share")
		{
			$document_master_result = $this->db->query("select * from document_master where triggered_by = 23 AND firm_id='".$firm_id."'");
		}
		elseif($type == "add_buyback_of_share")
		{
			$document_master_result = $this->db->query("select * from document_master where triggered_by = 24 AND firm_id='".$firm_id."'");
		}
		elseif($type == "add_transfer_of_share")
		{
			$document_master_result = $this->db->query("select * from document_master where triggered_by = 25 AND firm_id='".$firm_id."'");
		}

		if ($document_master_result->num_rows() > 0) 
		{
			$get_client = $this->db->query("select client.*, company_type.company_type as company_type_name from client left join company_type on client.company_type = company_type.id where company_code='".$company_code."'");

			$get_client = $get_client->result_array();

			$history_client_info = $this->db->query("select * from history_client where company_code = '".$company_code."'");

			$history_client_info = $history_client_info->result_array();

			$document_master_result = $document_master_result->result_array();

			for($r = 0; $r < count($document_master_result); $r++)
			{
				$document_name = $document_master_result[$r]["document_name"]." - ".DATE("Y",now());

				$str = $document_master_result[$r]["document_content"];

				if($document_master_result[$r]["document_name"] == "Allotment-Share Application Form" || $document_master_result[$r]["document_name"] == "F24 - Return of allotment of shares" || $document_master_result[$r]["document_name"] == "Allotment-Share Cert")
					{
						$allotment_member_result = $this->db->query("select member_shares.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_member_share_capital.other_class, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name, certificate.certificate_no, certificate.new_certificate_no from member_shares left join certificate on certificate.officer_id = member_shares.officer_id and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id and certificate.client_member_share_capital_id = member_shares.client_member_share_capital_id left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital on client_member_share_capital.id = member_shares.client_member_share_capital_id left join sharetype on sharetype.id = client_member_share_capital.class_id left join currency on currency.id = client_member_share_capital.currency_id where member_shares.company_code='".$company_code."' AND member_shares.client_member_share_capital_id='".$client_member_share_capital_id."' AND member_shares.transaction_type='Allotment' AND STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$old_member_date_result."','%d/%m/%Y') ORDER BY member_shares.id");

						$allotment_member_result = $allotment_member_result->result_array();

	                	$loop_document_content = "";
	                	$latest_allotment_id = array();
						for($g = 0; $g < count($allotment_member_result); $g++)
		                {
		                	$document_content_str = $document_master_result[$r]["document_content"];

		                	if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Allotment - members}}</span>') !== false)
		                	{
		                		if($allotment_member_result[$g]["name"] != '')
	                			{
	                				$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Allotment - members}}</span>', $allotment_member_result[$g]["name"], $document_content_str);
	                				
	                			}
	                			elseif($allotment_member_result[$g]["company_name"] != '')
	                			{
	                				$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Allotment - members}}</span>', $allotment_member_result[$g]["company_name"], $document_content_str);
	                			}
	                		}

	                		if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Allotment - number of shares}}</span>') !== false)
	                		{
	                			$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Allotment - number of shares}}</span>', number_format($allotment_member_result[$g]["number_of_share"], 2), $document_content_str);
	                		}

	                		if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Allotment - amount of shares}}</span>') !== false)
	                		{
	                			$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Allotment - amount of shares}}</span>', number_format($allotment_member_result[$g]["amount_share"], 2), $document_content_str);
	                		}

	                		if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Allotment - per shared}}</span>') !== false)
	                		{
	                			$per_shared = $allotment_member_result[$g]["amount_share"] / $allotment_member_result[$g]["number_of_share"];

	                			$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Allotment - per shared}}</span>', number_format($per_shared, 2), $document_content_str);
	                		}


	                		if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Allotment - type of shares}}</span>') !== false)
	                		{
	                			if($allotment_member_result[$g]["sharetype_id"] == '1')
	                			{
	                				$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Allotment - type of shares}}</span>', $allotment_member_result[$g]["sharetype_name"], $document_content_str);

	                			}
	                			elseif($allotment_member_result[$g]["sharetype_id"] == '2')
	                			{
	                				$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Allotment - type of shares}}</span>', $allotment_member_result[$g]["other_class"], $document_content_str);
	                			}
	                		}

	                		if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Allotment - currency}}</span>') !== false)
	                		{
	                			$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Allotment - currency}}</span>', $allotment_member_result[$g]["currency_name"], $document_content_str);
	                		}

	                		if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Allotment - certificate}}</span>') !== false)
	                		{
	                			if($allotment_member_result[$g]["new_certificate_no"] != '')
	                			{
	                				$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Allotment - certificate}}</span>', $allotment_member_result[$g]["new_certificate_no"], $document_content_str);
	                			}
	                			else
	                			{
	                				$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Allotment - certificate}}</span>', $allotment_member_result[$g]["certificate_no"], $document_content_str);
	                			}
	                			
	                		}

		                	if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Company type}}</span>') !== false)
							{
								$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Company type}}</span>', $get_client[0]["company_type_name"], $document_content_str);
							}
		                	if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Company current name}}</span>') !== false)
							{
								$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Company current name}}</span>', $get_client[0]["company_name"], $document_content_str);
							}
							if(strpos($str, '<span class="myclass mceNonEditable">{{UEN}}</span>') !== false)
							{
								$document_content_str = str_replace('<span class="myclass mceNonEditable">{{UEN}}</span>', $get_client[0]["registration_no"], $document_content_str);
							}
							if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Address - new}}</span>') !== false)
							{
								if($get_client[0]["unit_no1"] != null || $get_client[0]["unit_no2"] != null)
								{
									$unit_no = '#'.$get_client[0]["unit_no1"].'-'.$get_client[0]["unit_no2"].'';
								}
								else
								{
									$unit_no = '';
								}

								$new_address = $get_client[0]["street_name"].',</br>'.$unit_no.' '.$get_client[0]["building_name"].', </br>Singapore '.$get_client[0]["postal_code"];

								$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Address - new}}</span>', $new_address, $document_content_str);
							}
							if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Directors name - all}}</span>') !== false)
			                {
			                	$director_name_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '1' AND company_code='".$company_code."' AND ((STR_TO_DATE('".$$old_member_date_result."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y') AND client_officers.date_of_cessation = '') OR ((STR_TO_DATE('".$$old_member_date_result."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) AND (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') > STR_TO_DATE('".$$old_member_date_result."','%d/%m/%Y')) AND client_officers.date_of_cessation != '')) GROUP BY officer.identification_no, officer.name");
			                	
			                	$director_name_result = $director_name_result->result_array();
			                	
			                	if( $document_master_result[$r]["document_name"] == "Auditor-Notice of EGM" || $document_master_result[$r]["document_name"] == "Company Name-Notice of EGM" || $document_master_result[$r]["document_name"] == "Allotment-Authority to Allot")
			                	{
			                		$director_name = '';

			                		$director_name = $director_name.'<p>&nbsp;</p><p>&nbsp;</p><p>_______________________________<br />'.$director_name_result[0]["name"].'<br />Director</p>';

			                	}
			                	elseif($document_master_result[$r]["document_name"] == "Form 11" || $document_master_result[$r]["document_name"] == "Strike Off EGM")
			                	{

			                		$director_name = $director_name_result[0]["name"];

			                	}
			                	elseif($document_master_result[$r]["document_name"] == "Strike Off-Minutes Of EGM")
			                	{
			                		$director_name = '';
			                		for($h = 0; $h < count($director_name_result); $h++)
				                	{	
				                		if($h == 0)
				                		{
				                			$director_name = $director_name.'<p><strong>'.$director_name_result[$h]["name"].'<br /></strong>';
				                		}
				                		elseif($h == (count($director_name_result) - 1))
				                		{
				                			$director_name = $director_name.'<strong>'.$director_name_result[$h]["name"].'<br /></strong></p>';
				                		}
				                		else
				                		{
				                			$director_name = $director_name.'<strong>'.$director_name_result[$h]["name"].'<br /></strong>';
				                		}
			                			
			                		}
			                	}
			                	else
			                	{
			                		$director_name = '<table style="width: 100%; border-collapse: collapse;"><tbody>';
			                		for($j = 0; $j < count($director_name_result); $j++)
				                	{
				                		$director_name = $director_name.'<tr style="height: 89px;"><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p>'.$director_name_result[$j]["name"].'</p></td><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p> _______________________________</p></td></tr>';
				                	}
				                	$director_name = $director_name.'</tbody></table>';
			                	}
			                	


			                	$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Directors name - all}}</span>', $director_name, $document_content_str);
			                }

			                if($client_member_share_capital_id != null)
			                {
			                	$client_member_share_capital_id_info = $this->db->query("select client_member_share_capital.*, member_shares.company_code, sum(member_shares.number_of_share) as number_of_shares, sum(member_shares.amount_share) as amount, sum(member_shares.no_of_share_paid) as number_of_shares_paid, sum(member_shares.amount_paid) as paid_up, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name from client_member_share_capital left join member_shares on member_shares.client_member_share_capital_id = client_member_share_capital.id AND member_shares.company_code = client_member_share_capital.company_code left join sharetype on sharetype.id = client_member_share_capital.class_id left join currency on currency.id = client_member_share_capital.currency_id where client_member_share_capital.company_code = '".$company_code."' group by client_member_share_capital.id");

			                	$client_member_share_capital_id_info = $client_member_share_capital_id_info->result_array();

			                	if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Class of shares - all}}</span>') !== false)
			                	{
		                			if($client_member_share_capital_id_info[0]["sharetype_id"] == '1')
		                			{
		                				$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Class of shares - all}}</span>', $client_member_share_capital_id_info[0]["sharetype_name"], $document_content_str);

		                			}
		                			elseif($client_member_share_capital_id_info[$g]["sharetype_id"] == '2')
		                			{
		                				$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Class of shares - all}}</span>', $client_member_share_capital_id_info[0]["other_class"], $document_content_str);
		                			}
			                	}

			                	if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Currency of shares - all}}</span>') !== false)
			                	{
			                		$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Currency of shares - all}}</span>', $client_member_share_capital_id_info[0]["currency_name"], $document_content_str);
			                	}

			                	if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{No of shares issued - all}}</span>') !== false)
			                	{
			                		$document_content_str = str_replace('<span class="myclass mceNonEditable">{{No of shares issued - all}}</span>', number_format($client_member_share_capital_id_info[0]["number_of_shares"], 2), $document_content_str);
			                	}

			                	if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Amount of shares issued - all}}</span>') !== false)
			                	{
			                		$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Amount of shares issued - all}}</span>', number_format($client_member_share_capital_id_info[0]["amount"], 2), $document_content_str);
			                	}

			                	if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{No of shares paid up - all}}</span>') !== false)
			                	{
			                		$document_content_str = str_replace('<span class="myclass mceNonEditable">{{No of shares paid up - all}}</span>', number_format($client_member_share_capital_id_info[0]["number_of_shares_paid"], 2), $document_content_str);
			                	}

			                	if(strpos($document_content_str, '<span class="myclass mceNonEditable">{{Amount of shares paid up - all}}</span>') !== false)
			                	{
			                		$document_content_str = str_replace('<span class="myclass mceNonEditable">{{Amount of shares paid up - all}}</span>', number_format($client_member_share_capital_id_info[0]["paid_up"], 2), $document_content_str);
			                	}
			                }
		                	

		                	$loop_document_content = $loop_document_content.$document_content_str;
		                }

	                	for($h = 0; $h < count($allotment_member_result); $h++)
		                {
		                	array_push($latest_allotment_id, (int)$allotment_member_result[$h]["id"]);
		                }

	                	$data_pending_document['allotment_id']=json_encode($latest_allotment_id);

						/*if(count($previous_allotment_member_result) != 0)
	                	{
	                		$allotment_id_data = array();
	                		for($f = 0; $f < count($previous_allotment_member_result); $f++)
		                	{
		                		array_push($allotment_id_data, (int)$previous_allotment_member_result[$f]["id"]);
		                	}
	                	}*/
	                	$data_pending_document['content'] = $loop_document_content;
					}
					else
					{

						if (strpos($str, '<span class="myclass mceNonEditable">{{Company old name}}</span>') !== false) {
						    $str = str_replace('<span class="myclass mceNonEditable">{{Company old name}}</span>', $history_client_info[0]["company_name"], $str);
						}
						if(strpos($str, '<span class="myclass mceNonEditable">{{Company new name}}</span>') !== false)
						{
							$str = str_replace('<span class="myclass mceNonEditable">{{Company new name}}</span>', $get_client[0]["company_name"], $str);
						}
						if(strpos($str, '<span class="myclass mceNonEditable">{{Company current name}}</span>') !== false)
						{
							$str = str_replace('<span class="myclass mceNonEditable">{{Company current name}}</span>', $get_client[0]["company_name"], $str);
						}
						if(strpos($str, '<span class="myclass mceNonEditable">{{UEN}}</span>') !== false)
						{
							$str = str_replace('<span class="myclass mceNonEditable">{{UEN}}</span>', $get_client[0]["registration_no"], $str);
						}
						if(strpos($str, '<span class="myclass mceNonEditable">{{Incorporation date}}</span>') !== false)
						{
							$str = str_replace('<span class="myclass mceNonEditable">{{Incorporation date}}</span>', $get_client[0]["incorporation_date"], $str);
						}
						if(strpos($str, '<span class="myclass mceNonEditable">{{Company type}}</span>') !== false)
						{
							$str = str_replace('<span class="myclass mceNonEditable">{{Company type}}</span>', $get_client[0]["company_type_name"], $str);
						}
						if($document_master_result[$r]["triggered_by"] == 5)
						{
							if(strpos($str, '<span class="myclass mceNonEditable">{{Principal activity - old}}</span>') !== false)
							{
								$str = str_replace('<span class="myclass mceNonEditable">{{Principal activity - old}}</span>', $history_client_info[0]["activity1"], $str);
							}

							if(strpos($str, '<span class="myclass mceNonEditable">{{Principal activity - new}}</span>') !== false)
							{
								$str = str_replace('<span class="myclass mceNonEditable">{{Principal activity - new}}</span>', $get_client[0]["activity1"], $str);
							}
						}

						if($document_master_result[$r]["triggered_by"] == 6)
						{
							if(strpos($str, '<span class="myclass mceNonEditable">{{Principal activity - old}}</span>') !== false)
							{
								$str = str_replace('<span class="myclass mceNonEditable">{{Principal activity - old}}</span>', $history_client_info[0]["activity2"], $str);
							}

							if(strpos($str, '<span class="myclass mceNonEditable">{{Principal activity - new}}</span>') !== false)
							{
								$str = str_replace('<span class="myclass mceNonEditable">{{Principal activity - new}}</span>', $get_client[0]["activity2"], $str);
							}
						}
						if(strpos($str, '<span class="myclass mceNonEditable">{{Address - old}}</span>') !== false)
						{
							if($history_client_info[0]["unit_no1"] != '' || $history_client_info[0]["unit_no2"] != '')
							{
								$unit_no = '#'.$history_client_info[0]["unit_no1"].$history_client_info[0]["unit_no2"].'';
							}
							else
							{
								$unit_no = '';
							}

							$history_address = $history_client_info[0]["street_name"].' '.$unit_no.' '.$history_client_info[0]["building_name"].' Singapore '.$history_client_info[0]["postal_code"];

							$str = str_replace('<span class="myclass mceNonEditable">{{Address - old}}</span>', $history_address, $str);
						}
						if(strpos($str, '<span class="myclass mceNonEditable">{{Address - new}}</span>') !== false)
						{
							if($get_client[0]["unit_no1"] != '' || $get_client[0]["unit_no2"] != '')
							{
								$unit_no = '#'.$get_client[0]["unit_no1"].$get_client[0]["unit_no2"].'';
							}
							else
							{
								$unit_no = '';
							}

							$new_address = $get_client[0]["street_name"].' '.$unit_no.' '.$get_client[0]["building_name"].' Singapore '.$get_client[0]["postal_code"];

							$str = str_replace('<span class="myclass mceNonEditable">{{Address - new}}</span>', $new_address, $str);
						}
						if(strpos($str, '<span class="myclass mceNonEditable">{{Directors name - all}}</span>') !== false)
		                {
		                	$director_name_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '1' AND company_code='".$company_code."' AND ((STR_TO_DATE('".$old_member_date_result."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y') AND client_officers.date_of_cessation = '') OR ((STR_TO_DATE('".$old_member_date_result."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) AND (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') > STR_TO_DATE('".$old_member_date_result."','%d/%m/%Y')) AND client_officers.date_of_cessation != '')) GROUP BY officer.identification_no, officer.name");
		                	
		                	$director_name_result = $director_name_result->result_array();

		                	//echo json_encode($director_name_result);
		                	//((STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) OR (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') 

		                	if( $document_master_result[$r]["document_name"] == "Auditor-Notice of EGM" || $document_master_result[$r]["document_name"] == "Company Name-Notice of EGM" || $document_master_result[$r]["document_name"] == "Allotment-Authority to Allot")
		                	{
		                		$director_name = '';

		                		$director_name = $director_name.'<p>&nbsp;</p><p>&nbsp;</p><p>_______________________________<br />'.$director_name_result[0]["name"].'<br />Director</p>';

		                	}
		                	elseif($document_master_result[$r]["document_name"] == "Form 11")
		                	{

		                		$director_name = $director_name_result[0]["name"];

		                	}
		                	else
		                	{
		                		$director_name = '<table style="width: 100%; border-collapse: collapse;"><tbody>';
		                		for($g = 0; $g < count($director_name_result); $g++)
			                	{
			                		$director_name = $director_name.'<tr style="height: 89px;"><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p>'.$director_name_result[$g]["name"].'</p></td><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p> _______________________________</p></td></tr>';
			                	}
			                	$director_name = $director_name.'</tbody></table>';
		                	}

		                	$str = str_replace('<span class="myclass mceNonEditable">{{Directors name - all}}</span>', $director_name, $str);
		                }

		                if(strpos($str, '<span class="myclass mceNonEditable">{{Directors ID - all}}</span>') !== false)
		                {
		                	$director_id_result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '1' AND company_code='".$company_code."' AND ((STR_TO_DATE('".$old_member_date_result."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y') AND client_officers.date_of_cessation = '') OR ((STR_TO_DATE('".$old_member_date_result."','%d/%m/%Y') > STR_TO_DATE(date_of_appointment,'%d/%m/%Y')) AND (STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y') > STR_TO_DATE('".$old_member_date_result."','%d/%m/%Y')) AND client_officers.date_of_cessation != '')) GROUP BY officer.identification_no, officer.name");

		                	$director_id_result = $director_id_result->result_array();

		                	$director_id = "";

		                	for($g = 0; $g < count($director_id_result); $g++)
		                	{
		                		$director_id = $director_id.'<p>&nbsp;</p>'.$director_id_result[$g]["identification_no"].' ____________________________<br>';
		                	}

		                	$str = str_replace('<span class="myclass mceNonEditable">{{Directors ID - all}}</span>', $director_id, $str);
		                }

		                if(strpos($str, '<span class="myclass mceNonEditable">{{Members name - all}}</span>') !== false)
		                {
		                	$member_name_result = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id HAVING sum(member_shares.number_of_share) != 0');
		                	
		                	$member_name_result = $member_name_result->result_array();


		                	$member_name = '<table style="width: 100%; border-collapse: collapse;"><tbody>';

		                	for($g = 0; $g < count($member_name_result); $g++)
		                	{
		                		if($member_name_result[$g]["name"] != null)
		                		{
		                			$member_name = $member_name.'<tr style="height: 89px;"><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p>'.$member_name_result[$g]["name"].'</p></td><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p> _______________________________</p></td></tr>';
		                		}
		                		elseif($member_name_result[$g]["company_name"] != null)
		                		{
		                			$member_name = $member_name.'<tr style="height: 89px;"><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p>'.$member_name_result[$g]["company_name"].'</p></td><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p> _______________________________</p></td></tr>';
		                		}
		                	}

		                	$member_name = $member_name.'</tbody></table>';

		                	$str = str_replace('<span class="myclass mceNonEditable">{{Members name - all}}</span>', $member_name, $str);
		                }

		                if($client_member_share_capital_id != null)
		                {
		                	$client_member_share_capital_id_info = $this->db->query("select client_member_share_capital.*, member_shares.company_code, sum(member_shares.number_of_share) as number_of_shares, sum(member_shares.amount_share) as amount, sum(member_shares.no_of_share_paid) as number_of_shares_paid, sum(member_shares.amount_paid) as paid_up, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name from client_member_share_capital left join member_shares on member_shares.client_member_share_capital_id = client_member_share_capital.id AND member_shares.company_code = client_member_share_capital.company_code left join sharetype on sharetype.id = client_member_share_capital.class_id left join currency on currency.id = client_member_share_capital.currency_id where client_member_share_capital.company_code = '".$company_code."' group by client_member_share_capital.id");

		                	$client_member_share_capital_id_info = $client_member_share_capital_id_info->result_array();

		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Class of shares - all}}</span>') !== false)
		                	{
		            			if($client_member_share_capital_id_info[0]["sharetype_id"] == '1')
		            			{
		            				$str = str_replace('<span class="myclass mceNonEditable">{{Class of shares - all}}</span>', $client_member_share_capital_id_info[0]["sharetype_name"], $str);

		            			}
		            			elseif($client_member_share_capital_id_info[$g]["sharetype_id"] == '2')
		            			{
		            				$str = str_replace('<span class="myclass mceNonEditable">{{Class of shares - all}}</span>', $client_member_share_capital_id_info[0]["other_class"], $str);
		            			}
		                	}

		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Currency of shares - all}}</span>') !== false)
		                	{
		                		$str = str_replace('<span class="myclass mceNonEditable">{{Currency of shares - all}}</span>', $client_member_share_capital_id_info[0]["currency_name"], $str);
		                	}

		                	if(strpos($str, '<span class="myclass mceNonEditable">{{No of shares issued - all}}</span>') !== false)
		                	{
		                		$str = str_replace('<span class="myclass mceNonEditable">{{No of shares issued - all}}</span>', number_format($client_member_share_capital_id_info[0]["number_of_shares"], 2), $str);
		                	}

		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Amount of shares issued - all}}</span>') !== false)
		                	{
		                		$str = str_replace('<span class="myclass mceNonEditable">{{Amount of shares issued - all}}</span>', number_format($client_member_share_capital_id_info[0]["amount"], 2), $str);
		                	}

		                	if(strpos($str, '<span class="myclass mceNonEditable">{{No of shares paid up - all}}</span>') !== false)
		                	{
		                		$str = str_replace('<span class="myclass mceNonEditable">{{No of shares paid up - all}}</span>', number_format($client_member_share_capital_id_info[0]["number_of_shares_paid"], 2), $str);
		                	}

		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Amount of shares paid up - all}}</span>') !== false)
		                	{
		                		$str = str_replace('<span class="myclass mceNonEditable">{{Amount of shares paid up - all}}</span>', number_format($client_member_share_capital_id_info[0]["paid_up"], 2), $str);
		                	}
		                }

		                if($type == "add_allotment_of_share")
		                {
		                	$allotment_total_of_share_all_result = $this->db->query("select member_shares.*, sum(member_shares.number_of_share) as total_number_of_share, sum(member_shares.amount_share) as total_amount_of_share, client_member_share_capital.other_class, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name from member_shares left join client_member_share_capital on client_member_share_capital.id = member_shares.client_member_share_capital_id left join sharetype on sharetype.id = client_member_share_capital.class_id left join currency on currency.id = client_member_share_capital.currency_id where member_shares.company_code='".$company_code."' AND member_shares.client_member_share_capital_id='".$client_member_share_capital_id."' AND member_shares.transaction_type = 'Allotment' AND STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$old_member_date_result."','%d/%m/%Y') ORDER BY member_shares.id");

		                	$allotment_member_result = $this->db->query("select member_shares.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_member_share_capital.other_class, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name, certificate.certificate_no, certificate.new_certificate_no from member_shares left join certificate on certificate.officer_id = member_shares.officer_id and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id and certificate.client_member_share_capital_id = member_shares.client_member_share_capital_id left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital on client_member_share_capital.id = member_shares.client_member_share_capital_id left join sharetype on sharetype.id = client_member_share_capital.class_id left join currency on currency.id = client_member_share_capital.currency_id where member_shares.company_code='".$company_code."' AND member_shares.client_member_share_capital_id='".$client_member_share_capital_id."' AND member_shares.transaction_type='Allotment' AND STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$old_member_date_result."','%d/%m/%Y') ORDER BY member_shares.id");

		                
		                	/*$this->db->select('member_shares.*')
		                	         ->order_by('member_shares.id');
							$this->db->where("company_code", $company_code);
							$this->db->where("STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')");
							$this->db->where("member_shares.client_member_share_capital_id", $client_member_share_capital_id);
							$this->db->where("member_shares.transaction_type = 'Allotment'");
							$this->db->where_not_in('id', $allotment_id);*/

							//$previous_allotment_member_result = $this->db->get('member_shares');

							$allotment_total_of_share_all_result = $allotment_total_of_share_all_result->result_array();
		                	//echo json_encode($previous_director_name_appointment_result);
		                	$allotment_member_result = $allotment_member_result->result_array();

		                	//$previous_allotment_member_result = $previous_allotment_member_result->result_array();
		                	//echo json_encode($director_name_result);
		                	//if($transferor_member_result[])
		                	
		                	/*if(strpos($str, '<span class="myclass mceNonEditable">{{Transferor - name}}</span>') !== false)
				            {
		                		$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - name}}</span>', $get_client[0]["company_type_name"], $str);
		                	}*/
		                	
							//$final_message = str_replace($final_name,$html,$final_message);
		                	//echo json_encode($m[0][0]);
							//echo json_encode('<p><span class="myclass mceNonEditable">{{Allotment - members}}</span>'.$m[1].'</p>');
		                	/*$transferor_member = "";
		                	$transferee_member = "";*/
		                	$allotment_member = "";
		                	$allotment_string = "";
		                	$latest_allotment_id = array();
		                	$num_of_allotment_member = (int)(count($allotment_member_result)) - 1;

		                	if(strpos($str, '<tr class="loop"') !== false)
		                	{
		                		preg_match_all ('/<tr class="loop"(.+?)<\/tr>/s', $str, $m);
		                	
			                	for($g = 0; $g < count($allotment_member_result); $g++)
			                	{

		                			$allotment_string = $m[0][0];
		                			//echo json_encode($m);
			                		if(strpos($allotment_string, '<span class="myclass mceNonEditable">{{Allotment - members}}</span>') !== false)
				                	{
				                		if($allotment_member_result[$g]["name"] != '')
			                			{
			                				$allotment_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - members}}</span>', $allotment_member_result[$g]["name"], $allotment_string);
			                				
			                			}
			                			elseif($allotment_member_result[$g]["company_name"] != '')
			                			{
			                				$allotment_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - members}}</span>', $allotment_member_result[$g]["company_name"], $allotment_string);
			                			}
			                		}

			                		if(strpos($allotment_string, '<span class="myclass mceNonEditable">{{Allotment - number of shares}}</span>') !== false)
			                		{
			                			$allotment_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - number of shares}}</span>', number_format($allotment_member_result[$g]["number_of_share"], 2), $allotment_string);
			                		}

			                		if(strpos($allotment_string, '<span class="myclass mceNonEditable">{{Allotment - amount of shares}}</span>') !== false)
			                		{
			                			$allotment_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - amount of shares}}</span>', number_format($allotment_member_result[$g]["amount_share"], 2), $allotment_string);
			                		}

			                		if(strpos($allotment_string, '<span class="myclass mceNonEditable">{{Allotment - type of shares}}</span>') !== false)
			                		{
			                			if($allotment_member_result[$g]["sharetype_id"] == '1')
			                			{
			                				$allotment_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - type of shares}}</span>', $allotment_member_result[$g]["sharetype_name"], $allotment_string);

			                			}
			                			elseif($allotment_member_result[$g]["sharetype_id"] == '2')
			                			{
			                				$allotment_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - type of shares}}</span>', $allotment_member_result[$g]["other_class"], $allotment_string);
			                			}
			                		}

			                		if(strpos($allotment_string, '<span class="myclass mceNonEditable">{{Allotment - currency}}</span>') !== false)
			                		{
			                			$allotment_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - currency}}</span>', $allotment_member_result[$g]["currency_name"], $allotment_string);
			                		}

			                		if(strpos($allotment_string, '<span class="myclass mceNonEditable">{{Allotment - certificate}}</span>') !== false)
			                		{
			                			if($allotment_member_result[$g]["new_certificate_no"] != '')
			                			{
			                				$allotment_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - certificate}}</span>', $allotment_member_result[$g]["new_certificate_no"], $allotment_string);
			                			}
			                			else
			                			{
			                				$allotment_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - certificate}}</span>', $allotment_member_result[$g]["certificate_no"], $allotment_string);
			                			}
			                			
			                		}

			                		$allotment_member = $allotment_member.$allotment_string;
			                		
				            		
			                	}


		                		$str = str_replace($m[0][0], $allotment_member, $str);
			                }
			                
			               	
			               	for($g = 0; $g < count($allotment_member_result); $g++)
			                {

			                	if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - members}}</span>') !== false)
			                	{
			                		if($allotment_member_result[$g]["name"] != '')
		                			{
		                				$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - members}}</span>', $allotment_member_result[$g]["name"], $str);
		                				
		                			}
		                			elseif($allotment_member_result[$g]["company_name"] != '')
		                			{
		                				$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - members}}</span>', $allotment_member_result[$g]["company_name"], $str);
		                			}
		                		}

		                		if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - number of shares}}</span>') !== false)
		                		{
		                			$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - number of shares}}</span>', number_format($allotment_member_result[$g]["number_of_share"], 2), $str);
		                		}

		                		if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - amount of shares}}</span>') !== false)
		                		{
		                			$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - amount of shares}}</span>', number_format($allotment_member_result[$g]["amount_share"], 2), $str);
		                		}

		                		if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - per shared}}</span>') !== false)
		                		{
		                			$per_shared = $allotment_member_result[$g]["amount_share"] / $allotment_member_result[$g]["number_of_share"];

		                			$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - per shared}}</span>', number_format($per_shared, 2), $str);
		                		}

		                		if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - type of shares}}</span>') !== false)
		                		{
		                			if($allotment_member_result[$g]["sharetype_id"] == '1')
		                			{
		                				$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - type of shares}}</span>', $allotment_member_result[$g]["sharetype_name"], $str);

		                			}
		                			elseif($allotment_member_result[$g]["sharetype_id"] == '2')
		                			{
		                				$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - type of shares}}</span>', $allotment_member_result[$g]["other_class"], $str);
		                			}
		                		}

		                		if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - currency}}</span>') !== false)
		                		{
		                			$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - currency}}</span>', $allotment_member_result[$g]["currency_name"], $str);
		                		}

		                		if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - certificate}}</span>') !== false)
		                		{
		                			if($allotment_member_result[$g]["new_certificate_no"] != '')
		                			{
		                				$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - certificate}}</span>', $allotment_member_result[$g]["new_certificate_no"], $str);
		                			}
		                			else
		                			{
		                				$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - certificate}}</span>', $allotment_member_result[$g]["certificate_no"], $str);
		                			}
		                			
		                		}

			                		
			                	
			                }

			                if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - number of shares all}}</span>') !== false)
		                	{
		                		$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - number of shares all}}</span>', number_format($allotment_total_of_share_all_result[0]["total_number_of_share"], 2), $str);
		                	}

		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - amount of shares all}}</span>') !== false)
		                	{
		                		$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - amount of shares all}}</span>', number_format($allotment_total_of_share_all_result[0]["total_amount_of_share"], 2), $str);
		                	}

		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - type of shares all}}</span>') !== false)
		                	{
		                		if($allotment_total_of_share_all_result[0]["sharetype_id"] == '1')
		            			{
		            				$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - type of shares all}}</span>', $allotment_total_of_share_all_result[0]["sharetype_name"], $str);

		            			}
		            			elseif($allotment_total_of_share_all_result[0]["sharetype_id"] == '2')
		            			{
		            				$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - type of shares all}}</span>', $allotment_total_of_share_all_result[0]["other_class"], $str);
		            			}
		                	}

		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Allotment - currency all}}</span>') !== false)
		            		{
		            			$str = str_replace('<span class="myclass mceNonEditable">{{Allotment - currency all}}</span>', $allotment_total_of_share_all_result[0]["currency_name"], $str);
		            		}
			                

			                for($h = 0; $h < count($allotment_member_result); $h++)
			                {
			                	array_push($latest_member_id, (int)$allotment_member_result[$h]["id"]);
			                }

		                	$data_pending_document['allotment_id']=json_encode($latest_member_id);

		                	
		                	//$str = str_replace($r[0][0], $transferee_member, $str);

		                	/*if(count($previous_allotment_member_result) != 0)
		                	{
		                		$allotment_id_data = array();
		                		for($f = 0; $f < count($previous_allotment_member_result); $f++)
			                	{
			                		array_push($allotment_id_data, (int)$previous_allotment_member_result[$f]["id"]);
			                	}
		                	}*/
		                }

		                if($type == "add_buyback_of_share")
		                {
		                	$buyback_total_of_share_all_result = $this->db->query("select member_shares.*, sum(member_shares.number_of_share) as total_number_of_share, sum(member_shares.amount_share) as total_amount_of_share, client_member_share_capital.other_class, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name from member_shares left join client_member_share_capital on client_member_share_capital.id = member_shares.client_member_share_capital_id left join sharetype on sharetype.id = client_member_share_capital.class_id left join currency on currency.id = client_member_share_capital.currency_id where member_shares.company_code='".$company_code."' AND member_shares.client_member_share_capital_id='".$client_member_share_capital_id."' AND member_shares.transaction_type = 'Buyback' AND STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$old_member_date_result."','%d/%m/%Y') ORDER BY member_shares.id");

		                	$buyback_member_result = $this->db->query("select member_shares.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_member_share_capital.other_class, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name, certificate.certificate_no, certificate.new_certificate_no from member_shares left join certificate on certificate.officer_id = member_shares.officer_id and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id and certificate.client_member_share_capital_id = member_shares.client_member_share_capital_id left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital on client_member_share_capital.id = member_shares.client_member_share_capital_id left join sharetype on sharetype.id = client_member_share_capital.class_id left join currency on currency.id = client_member_share_capital.currency_id where member_shares.company_code='".$company_code."' AND member_shares.client_member_share_capital_id='".$client_member_share_capital_id."' AND member_shares.transaction_type='Buyback' AND STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$old_member_date_result."','%d/%m/%Y') ORDER BY member_shares.id");

		                
		/*                	$this->db->select('member_shares.*')
		                	         ->order_by('member_shares.id');
							$this->db->where("company_code", $company_code);
							$this->db->where("STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')");
							$this->db->where("member_shares.client_member_share_capital_id", $client_member_share_capital_id);
							$this->db->where("member_shares.transaction_type = 'Buyback'");
							$this->db->where_in('id', $buyback_id);

							$previous_buyback_member_result = $this->db->get('member_shares');*/

							$buyback_total_of_share_all_result = $buyback_total_of_share_all_result->result_array();
		                	//echo json_encode($previous_director_name_appointment_result);
		                	$buyback_member_result = $buyback_member_result->result_array();

		                	//$previous_buyback_member_result = $previous_buyback_member_result->result_array();

		                	$buyback_member = "";
		                	$buyback_string = "";
		                	$latest_buyback_id = array();
		                	$num_of_buyback_member = (int)(count($buyback_member_result)) - 1;

		                	if(strpos($str, '<tr class="loop"') !== false)
		                	{
		                		preg_match_all ('/<tr class="loop"(.+?)<\/tr>/s', $str, $m);
		                	
			                	for($g = 0; $g < count($buyback_member_result); $g++)
			                	{

		                			$buyback_string = $m[0][0];
		                			//echo json_encode($m);
			                		if(strpos($buyback_string, '<span class="myclass mceNonEditable">{{Buyback - members}}</span>') !== false)
				                	{
				                		if($buyback_member_result[$g]["name"] != '')
			                			{
			                				$buyback_string = str_replace('<span class="myclass mceNonEditable">{{Buyback - members}}</span>', $buyback_member_result[$g]["name"], $buyback_string);
			                				
			                			}
			                			elseif($buyback_member_result[$g]["company_name"] != '')
			                			{
			                				$buyback_string = str_replace('<span class="myclass mceNonEditable">{{Buyback - members}}</span>', $buyback_member_result[$g]["company_name"], $buyback_string);
			                			}
			                		}

			                		if(strpos($buyback_string, '<span class="myclass mceNonEditable">{{Buyback - number of shares}}</span>') !== false)
			                		{
			                			$buyback_string = str_replace('<span class="myclass mceNonEditable">{{Buyback - number of shares}}</span>', number_format(-($buyback_member_result[$g]["number_of_share"]), 2), $buyback_string);
			                		}

			                		if(strpos($buyback_string, '<span class="myclass mceNonEditable">{{Buyback - amount of shares}}</span>') !== false)
			                		{
			                			$buyback_string = str_replace('<span class="myclass mceNonEditable">{{Buyback - amount of shares}}</span>', number_format(-($buyback_member_result[$g]["amount_share"]), 2), $buyback_string);
			                		}

			                		if(strpos($buyback_string, '<span class="myclass mceNonEditable">{{Buyback - type of shares}}</span>') !== false)
			                		{
			                			if($buyback_member_result[$g]["sharetype_id"] == '1')
			                			{
			                				$buyback_string = str_replace('<span class="myclass mceNonEditable">{{Buyback - type of shares}}</span>', $buyback_member_result[$g]["sharetype_name"], $buyback_string);

			                			}
			                			elseif($buyback_member_result[$g]["sharetype_id"] == '2')
			                			{
			                				$buyback_string = str_replace('<span class="myclass mceNonEditable">{{Buyback - type of shares}}</span>', $buyback_member_result[$g]["other_class"], $buyback_string);
			                			}
			                		}

			                		if(strpos($buyback_string, '<span class="myclass mceNonEditable">{{Buyback - currency}}</span>') !== false)
			                		{
			                			$buyback_string = str_replace('<span class="myclass mceNonEditable">{{Buyback - currency}}</span>', $buyback_member_result[$g]["currency_name"], $buyback_string);
			                		}

			                		if(strpos($buyback_string, '<span class="myclass mceNonEditable">{{Buyback - certificate}}</span>') !== false)
			                		{
			                			if($buyback_member_result[$g]["new_certificate_no"] != '')
			                			{
			                				$buyback_string = str_replace('<span class="myclass mceNonEditable">{{Buyback - certificate}}</span>', $buyback_member_result[$g]["new_certificate_no"], $buyback_string);
			                			}
			                			else
			                			{
			                				$buyback_string = str_replace('<span class="myclass mceNonEditable">{{Buyback - certificate}}</span>', $buyback_member_result[$g]["certificate_no"], $buyback_string);
			                			}
			                			
			                		}

			                		$buyback_member = $buyback_member.$buyback_string;
				            	
			                	}
			                	

		                		$str = str_replace($m[0][0], $buyback_member, $str);
			                }
			                
			               	
			               	for($g = 0; $g < count($buyback_member_result); $g++)
			                {

			                	if(strpos($str, '<span class="myclass mceNonEditable">{{Buyback - members}}</span>') !== false)
			                	{
			                		if($buyback_member_result[$g]["name"] != '')
		                			{
		                				$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - members}}</span>', $buyback_member_result[$g]["name"], $str);
		                				
		                			}
		                			elseif($buyback_member_result[$g]["company_name"] != '')
		                			{
		                				$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - members}}</span>', $buyback_member_result[$g]["company_name"], $str);
		                			}
		                		}

		                		if(strpos($str, '<span class="myclass mceNonEditable">{{Buyback - number of shares}}</span>') !== false)
		                		{
		                			$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - number of shares}}</span>', number_format(-($buyback_member_result[$g]["number_of_share"]), 2), $str);
		                		}

		                		if(strpos($str, '<span class="myclass mceNonEditable">{{Buyback - amount of shares}}</span>') !== false)
		                		{
		                			$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - amount of shares}}</span>', number_format(-($buyback_member_result[$g]["amount_share"]), 2), $str);
		                		}

		                		if(strpos($str, '<span class="myclass mceNonEditable">{{Buyback - type of shares}}</span>') !== false)
		                		{
		                			if($buyback_member_result[$g]["sharetype_id"] == '1')
		                			{
		                				$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - type of shares}}</span>', $buyback_member_result[$g]["sharetype_name"], $str);

		                			}
		                			elseif($buyback_member_result[$g]["sharetype_id"] == '2')
		                			{
		                				$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - type of shares}}</span>', $buyback_member_result[$g]["other_class"], $str);
		                			}
		                		}

		                		if(strpos($str, '<span class="myclass mceNonEditable">{{Buyback - currency}}</span>') !== false)
		                		{
		                			$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - currency}}</span>', $buyback_member_result[$g]["currency_name"], $str);
		                		}

		                		if(strpos($str, '<span class="myclass mceNonEditable">{{Buyback - certificate}}</span>') !== false)
		                		{
		                			if($buyback_member_result[$g]["new_certificate_no"] != '')
		                			{
		                				$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - certificate}}</span>', $buyback_member_result[$g]["new_certificate_no"], $str);
		                			}
		                			else
		                			{
		                				$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - certificate}}</span>', $buyback_member_result[$g]["certificate_no"], $str);
		                			}
		                			
		                		}

			                }

			                if(strpos($str, '<span class="myclass mceNonEditable">{{Buyback - number of shares all}}</span>') !== false)
		                	{
		                		$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - number of shares all}}</span>', number_format(-($buyback_total_of_share_all_result[0]["total_number_of_share"]), 2), $str);
		                	}

		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Buyback - amount of shares all}}</span>') !== false)
		                	{
		                		$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - amount of shares all}}</span>', number_format(-($buyback_total_of_share_all_result[0]["total_amount_of_share"]), 2), $str);
		                	}

		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Buyback - type of shares all}}</span>') !== false)
		                	{
		                		if($buyback_total_of_share_all_result[0]["sharetype_id"] == '1')
		            			{
		            				$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - type of shares all}}</span>', $buyback_total_of_share_all_result[0]["sharetype_name"], $str);

		            			}
		            			elseif($buyback_total_of_share_all_result[0]["sharetype_id"] == '2')
		            			{
		            				$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - type of shares all}}</span>', $buyback_total_of_share_all_result[0]["other_class"], $str);
		            			}
		                	}

		                	if(strpos($str, '<span class="myclass mceNonEditable">{{Buyback - currency all}}</span>') !== false)
		            		{
		            			$str = str_replace('<span class="myclass mceNonEditable">{{Buyback - currency all}}</span>', $buyback_total_of_share_all_result[0]["currency_name"], $str);
		            		}
			                

			                for($h = 0; $h < count($buyback_member_result); $h++)
			                {
			                	array_push($latest_member_id, (int)$buyback_member_result[$h]["id"]);
			                }

		                	$data_pending_document['buyback_id']=json_encode($latest_member_id);
		                }

						if($type == "add_transfer_of_share")
		                {
		                	$transfer_member_result = $this->db->query("select member_shares.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_member_share_capital.other_class, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name, certificate.certificate_no, certificate.new_certificate_no from member_shares left join certificate on certificate.officer_id = member_shares.officer_id and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id and certificate.client_member_share_capital_id = member_shares.client_member_share_capital_id left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital on client_member_share_capital.id = member_shares.client_member_share_capital_id left join sharetype on sharetype.id = client_member_share_capital.class_id left join currency on currency.id = client_member_share_capital.currency_id where member_shares.company_code='".$company_code."' AND member_shares.client_member_share_capital_id='".$client_member_share_capital_id."' AND member_shares.transaction_type='Transfer' AND STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$old_member_date_result."','%d/%m/%Y') ORDER BY member_shares.id");

		                

		                	/*$this->db->select('member_shares.*')
		                	         ->order_by('member_shares.id');
							$this->db->where("company_code", $company_code);
							$this->db->where("STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$data['transaction_date']."','%d/%m/%Y')");
							$this->db->where("member_shares.client_member_share_capital_id", $client_member_share_capital_id);
							$this->db->where("member_shares.transaction_type = 'Transfer'");
							$this->db->where_in('id', $transfer_id);
							$previous_transfer_member_result = $this->db->get('member_shares');*/

		                	$transfer_member_result = $transfer_member_result->result_array();

		                	//$previous_transfer_member_result = $previous_transfer_member_result->result_array();

		                	$transferor_member = "";
		                	$transferor_string = "";
		                	$latest_member_id = array();
		                	$num_of_transferor_member = (int)(count($transfer_member_result)) - 1;

		                	if(strpos($str, '<tr class="loop"') !== false)
		                	{
		                		preg_match_all ('/<tr class="loop"(.+?)<\/tr>/s', $str, $m);
		                	
			                	for($g = 0; $g < count($transfer_member_result); $g++)
			                	{
			                		if($g%2==0)
								    {
								     	$transferor_string = $transferor_string.$m[0][0];
								    }
								   	else
								   	{
								   		$transferor_string = $transferor_string;
								   	}
			                		/*if ($g % 2 === 0) 
			                		{*/
				                		//$transferor_string = $m[0][0];
				                	if(0 > $transfer_member_result[$g]["number_of_share"])
			                		{
				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - name}}</span>') !== false)
					                	{
					                		if($transfer_member_result[$g]["name"] != '')
				                			{
				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - name}}</span>', $transfer_member_result[$g]["name"], $transferor_string);
				                				
				                			}
				                			elseif($transfer_member_result[$g]["company_name"] != '')
				                			{
				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - name}}</span>', $transfer_member_result[$g]["company_name"], $transferor_string);
				                			}
				                		}

				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - ID}}</span>') !== false)
					                	{
					                		if($transfer_member_result[$g]["identification_no"] != '')
				                			{
				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - ID}}</span>', $transfer_member_result[$g]["identification_no"], $transferor_string);
				                				
				                			}
				                			elseif($transfer_member_result[$g]["register_no"] != '')
				                			{
				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - ID}}</span>', $transfer_member_result[$g]["register_no"], $transferor_string);
				                			}
				                		}

				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - share number}}</span>') !== false)
				                		{
				                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - share number}}</span>', number_format(-($transfer_member_result[$g]["number_of_share"]), 2), $transferor_string);
				                		}

				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - share amount}}</span>') !== false)
				                		{
				                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - share amount}}</span>', number_format(-($transfer_member_result[$g]["amount_share"]), 2), $transferor_string);
				                		}

				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - share type}}</span>') !== false)
				                		{
				                			if($transfer_member_result[$g]["sharetype_id"] == '1')
				                			{
				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - share type}}</span>', $transfer_member_result[$g]["sharetype_name"], $transferor_string);

				                			}
				                			elseif($transfer_member_result[$g]["sharetype_id"] == '2')
				                			{
				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - share type}}</span>', $transfer_member_result[$g]["other_class"], $transferor_string);
				                			}
				                		}

				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - currency}}</span>') !== false)
				                		{
				                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - currency}}</span>', $transfer_member_result[$g]["currency_name"], $transferor_string);
				                		}

				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - certificate}}</span>') !== false)
				                		{
				                			if($transfer_member_result[$g]["new_certificate_no"] != '')
				                			{
				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - certificate}}</span>', $transfer_member_result[$g]["new_certificate_no"], $transferor_string);
				                			}
				                			else
				                			{
				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - certificate}}</span>', $transfer_member_result[$g]["certificate_no"], $transferor_string);
				                			}
				                			
				                		}

				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - consideration}}</span>') !== false)
				                		{
				                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - consideration}}</span>', number_format($transfer_member_result[$g]["consideration"], 2), $transferor_string);
				                		}
				                	}
				                	elseif($transfer_member_result[$g]["number_of_share"] > 0)
				                	{
				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - name}}</span>') !== false)
					                	{
					                		if($transfer_member_result[$g]["name"] != '')
				                			{
				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - name}}</span>', $transfer_member_result[$g]["name"], $transferor_string);
				                				
				                			}
				                			elseif($transfer_member_result[$g]["company_name"] != '')
				                			{
				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - name}}</span>', $transfer_member_result[$g]["company_name"], $transferor_string);
				                			}
				                		}

				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - ID}}</span>') !== false)
					                	{
					                		if($transfer_member_result[$g]["identification_no"] != '')
				                			{
				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - ID}}</span>', $transfer_member_result[$g]["identification_no"], $transferor_string);
				                				
				                			}
				                			elseif($transfer_member_result[$g]["register_no"] != '')
				                			{
				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - ID}}</span>', $transfer_member_result[$g]["register_no"], $transferor_string);
				                			}
				                		}

				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - share number}}</span>') !== false)
				                		{
				                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - share number}}</span>', number_format($transfer_member_result[$g]["number_of_share"],2), $transferor_string);
				                		}

				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - share amount}}</span>') !== false)
				                		{
				                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - share amount}}</span>', number_format($transfer_member_result[$g]["amount_share"], 2), $transferor_string);
				                		}
				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - share type}}</span>') !== false)
				                		{
				                			if($transfer_member_result[$g]["sharetype_id"] == '1')
				                			{
				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - share type}}</span>', $transfer_member_result[$g]["sharetype_name"], $transferor_string);

				                			}
				                			elseif($transfer_member_result[$g]["sharetype_id"] == '2')
				                			{
				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - share type}}</span>', $transfer_member_result[$g]["other_class"], $transferor_string);
				                			}
				                		}

				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - currency}}</span>') !== false)
				                		{
				                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - currency}}</span>', $transfer_member_result[$g]["currency_name"], $transferor_string);
				                		}

				                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - certificate}}</span>') !== false)
				                		{
				                			if($transfer_member_result[$g]["new_certificate_no"] != '')
				                			{
				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - certificate}}</span>', $transfer_member_result[$g]["new_certificate_no"], $transferor_string);
				                			}
				                			else
				                			{
				                				$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - certificate}}</span>', $transfer_member_result[$g]["certificate_no"], $transferor_string);
				                			}
				                			
				                		}
				                	}
			                		
				            
			                	}

			                	if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - name}}</span>') !== false)
					            {
					            	$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - name}}</span>', '', $transferor_string);
		                		}

		            			if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - ID}}</span>') !== false)
			                	{
			                		$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - ID}}</span>', '', $transferor_string);
		                		}

		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - share number}}</span>') !== false)
		                		{
		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - share number}}</span>', '', $transferor_string);
		                		}

		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - share amount}}</span>') !== false)
		                		{
		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - share amount}}</span>', '', $transferor_string);
		                		}

		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - share type}}</span>') !== false)
		                		{
		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - share type}}</span>', '', $transferor_string);
		                		}

		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - currency}}</span>') !== false)
		                		{
		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - currency}}</span>', '', $transferor_string);
		                		}

		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - certificate}}</span>') !== false)
		                		{
		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - certificate}}</span>', '', $transferor_string);
		                		}

		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferor - consideration}}</span>') !== false)
		                		{
		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - consideration}}</span>', '', $transferor_string);
		                		}

		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - name}}</span>') !== false)
			                	{
			                		$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - name}}</span>', '', $transferor_string);
		                		}

		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - ID}}</span>') !== false)
			                	{
			                		$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - ID}}</span>', '', $transferor_string);
		                		}

		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - share number}}</span>') !== false)
		                		{
		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - share number}}</span>', '', $transferor_string);
		                		}

		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - share amount}}</span>') !== false)
		                		{
		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - share amount}}</span>', '', $transferor_string);
		                		}
		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - share type}}</span>') !== false)
		                		{
		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - share type}}</span>', '', $transferor_string);
		                		}

		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - currency}}</span>') !== false)
		                		{
		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - currency}}</span>', '', $transferor_string);
		                		}

		                		if(strpos($transferor_string, '<span class="myclass mceNonEditable">{{Transferee - certificate}}</span>') !== false)
		                		{
		                			$transferor_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - certificate}}</span>', '', $transferor_string);
		                		}

		                		$str = str_replace($m[0][0], $transferor_string, $str);
			                }
			                
			               	
			               	for($g = 0; $g < count($transfer_member_result); $g++)
			                {
			                	if(0 > $transfer_member_result[$g]["number_of_share"])
			                	{
				                	if(strpos($str, '<span class="myclass mceNonEditable">{{Transferor - name}}</span>') !== false)
						            {

				                		if($transfer_member_result[$g]["name"] != '')
			                			{
			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - name}}</span>', $transfer_member_result[$g]["name"], $str);
			                				
			                			}
			                			elseif($transfer_member_result[$g]["company_name"] != '')
			                			{
			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - name}}</span>', $transfer_member_result[$g]["company_name"], $str);
			                			}
			                		}

		                			if(strpos($str, '<span class="myclass mceNonEditable">{{Transferor - ID}}</span>') !== false)
				                	{
				                		if($transfer_member_result[$g]["identification_no"] != '')
			                			{
			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - ID}}</span>', $transfer_member_result[$g]["identification_no"], $str);
			                				
			                			}
			                			elseif($transfer_member_result[$g]["register_no"] != '')
			                			{
			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - ID}}</span>', $transfer_member_result[$g]["register_no"], $str);
			                			}
			                		}

			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferor - share number}}</span>') !== false)
			                		{
			                			$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - share number}}</span>', number_format(-($transfer_member_result[$g]["number_of_share"]), 2), $str);
			                		}

			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferor - share amount}}</span>') !== false)
			                		{
			                			$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - share amount}}</span>', number_format(-($transfer_member_result[$g]["amount_share"]), 2), $str);
			                		}

			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferor - share type}}</span>') !== false)
			                		{
			                			if($transfer_member_result[$g]["sharetype_id"] == '1')
			                			{
			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - share type}}</span>', $transfer_member_result[$g]["sharetype_name"], $str);

			                			}
			                			elseif($transfer_member_result[$g]["sharetype_id"] == '2')
			                			{
			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - share type}}</span>', $transfer_member_result[$g]["other_class"], $str);
			                			}
			                		}

			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferor - currency}}</span>') !== false)
			                		{
			                			$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - currency}}</span>', $transfer_member_result[$g]["currency_name"], $str);
			                		}

			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferor - certificate}}</span>') !== false)
			                		{
			                			if($transfer_member_result[$g]["new_certificate_no"] != '')
			                			{
			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - certificate}}</span>', $transfer_member_result[$g]["new_certificate_no"], $str);
			                			}
			                			else
			                			{
			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - certificate}}</span>', $transfer_member_result[$g]["certificate_no"], $str);
			                			}
			                			
			                		}

			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferor - consideration}}</span>') !== false)
			                		{
			                			$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - consideration}}</span>', number_format($transfer_member_result[$g]["consideration"], 2), $str);
			                		}
			                	}
			                	elseif($transfer_member_result[$g]["number_of_share"] > 0)
			                	{
			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferee - name}}</span>') !== false)
				                	{
				                		if($transfer_member_result[$g]["name"] != '')
			                			{
			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - name}}</span>', $transfer_member_result[$g]["name"], $str);
			                				
			                			}
			                			elseif($transfer_member_result[$g]["company_name"] != '')
			                			{
			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - name}}</span>', $transfer_member_result[$g]["company_name"], $str);
			                			}
			                		}

			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferee - ID}}</span>') !== false)
				                	{
				                		if($transfer_member_result[$g]["identification_no"] != '')
			                			{
			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - ID}}</span>', $transfer_member_result[$g]["identification_no"], $str);
			                				
			                			}
			                			elseif($transfer_member_result[$g]["register_no"] != '')
			                			{
			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - ID}}</span>', $transfer_member_result[$g]["register_no"], $str);
			                			}
			                		}

			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferee - share number}}</span>') !== false)
			                		{
			                			$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - share number}}</span>', number_format($transfer_member_result[$g]["number_of_share"], 2), $str);
			                		}

			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferee - share amount}}</span>') !== false)
			                		{
			                			$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - share amount}}</span>', number_format($transfer_member_result[$g]["amount_share"], 2), $str);
			                		}
			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferee - share type}}</span>') !== false)
			                		{
			                			if($transfer_member_result[$g]["sharetype_id"] == '1')
			                			{
			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - share type}}</span>', $transfer_member_result[$g]["sharetype_name"], $str);

			                			}
			                			elseif($transfer_member_result[$g]["sharetype_id"] == '2')
			                			{
			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - share type}}</span>', $transfer_member_result[$g]["other_class"], $str);
			                			}
			                		}

			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferee - currency}}</span>') !== false)
			                		{
			                			$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - currency}}</span>', $transfer_member_result[$g]["currency_name"], $str);
			                		}

			                		if(strpos($str, '<span class="myclass mceNonEditable">{{Transferee - certificate}}</span>') !== false)
			                		{
			                			if($transfer_member_result[$g]["new_certificate_no"] != '')
			                			{
			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - certificate}}</span>', $transfer_member_result[$g]["new_certificate_no"], $str);
			                			}
			                			else
			                			{
			                				$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - certificate}}</span>', $transfer_member_result[$g]["certificate_no"], $str);
			                			}
			                			
			                		}
			                	}
			                	
			                }
	                

			                for($h = 0; $h < count($transfer_member_result); $h++)
			                {
			                	array_push($latest_member_id, (int)$transfer_member_result[$h]["id"]);
			                }

		                	$data_pending_document['transfer_id']=json_encode($latest_member_id);

		                }

		                $data_pending_document['content'] = $str;
		            }

                if(count($previous_member_result) != 0 && $previous_member_result != null)
	        	{
	        		$member_id_data = array();
	        		for($f = 0; $f < count($previous_member_result); $f++)
	            	{
	            		array_push($member_id_data, (int)$previous_member_result[$f]["id"]);
	            	}
	        	}

	            
	            /*echo json_encode($member_id_data);
	            echo json_encode($type);*/
	            if($type == "add_allotment_of_share")
				{
		        	if(count($latest_member_id) == 0)
		        	{
		        		$this->db->where('triggered_by', $document_master_result[$r]["triggered_by"]);
		        		$this->db->where('client_id', $client_id);
		        		$this->db->where('received_on', "");
		        		$this->db->where('allotment_id', json_encode($member_id_data));
		        		$this->db->where('transaction_date', $old_member_date_result);
		        		$this->db->where('document_name', $document_name);
						$this->db->delete('pending_documents');
		        	}
		        	else
		        	{

		        		$this->db->update("pending_documents",$data_pending_document,array("triggered_by" => $document_master_result[$r]["triggered_by"], "client_id" => $client_id, "received_on" => "", "allotment_id" => json_encode($member_id_data), "transaction_date" => $old_member_date_result, "document_name" => $document_name));  
		        	}
		        }
		        elseif($type == "add_buyback_of_share")
				{
		        	if(count($latest_member_id) == 0)
		        	{
		        		$this->db->where('triggered_by', $document_master_result[$r]["triggered_by"]);
		        		$this->db->where('client_id', $client_id);
		        		$this->db->where('received_on', "");
		        		$this->db->where('buyback_id', json_encode($member_id_data));
		        		$this->db->where('transaction_date', $old_member_date_result);
		        		$this->db->where('document_name', $document_name);
						$this->db->delete('pending_documents');
		        	}
		        	else
		        	{

		        		$this->db->update("pending_documents",$data_pending_document,array("triggered_by" => $document_master_result[$r]["triggered_by"], "client_id" => $client_id, "received_on" => "", "buyback_id" => json_encode($member_id_data), "transaction_date" => $old_member_date_result, "document_name" => $document_name));  
		        	}
		        }
	            elseif($type == "add_transfer_of_share")
				{
					//echo json_encode($member_id_data);
		        	if(count($latest_member_id) == 0)
		        	{
		        		$this->db->where('triggered_by', $document_master_result[$r]["triggered_by"]);
		        		$this->db->where('client_id', $client_id);
		        		$this->db->where('received_on', "");
		        		$this->db->where('transfer_id', json_encode($member_id_data));
		        		$this->db->where('transaction_date', $old_member_date_result);
		        		$this->db->where('document_name', $document_name);
						$this->db->delete('pending_documents');
		        	}
		        	else
		        	{

		        		$this->db->update("pending_documents",$data_pending_document,array("triggered_by" => $document_master_result[$r]["triggered_by"], "client_id" => $client_id, "received_on" => "", "transfer_id" => json_encode($member_id_data), "transaction_date" => $old_member_date_result, "document_name" => $document_name));  
		        	}
		        }
            }
        }
    }

	public function view_allotment ($company_code)
	{
        $bc = array(array('link' => '#', 'page' => lang('Allotment')));
        $meta = array('page_title' => lang('Allotment'), 'bc' => $bc, 'page_name' => 'Allotment');
        $search = ''; $type = '';
		
		if (isset($_POST['type'])) $type = $_POST['type'];
		if (isset($_POST['search'])) $search = $_POST['search'];

		if($type == null)
        {
            $type = "all";
        }
        else 
        {
            $type = $_POST['type'];
        }

        $this->data['type'] = $type;
		$this->data['company_code'] = $company_code;
		$this->data['company_class'] = $this->master_model->get_all_company_share_type($company_code);
		$this->data['allotment'] = $this->master_model->get_all_allotment_to_view($search,$type,$company_code);

		$r = $this->db->query("select status, company_name from client where company_code = '".$company_code."'");
        $r = $r->result_array();
		$this->data["client_status"] = $r[0]["status"];

		$allotment_id = array();
        $this->session->set_userdata(array(
            'allotment_id'  =>  $allotment_id,
        ));

        $this->page_construct('client/allotment_edit.php', $meta, $this->data);
	}
	
	public function allotment ($company_code)
	{
        $bc = array(array('link' => '#', 'page' => lang('Create Allotment')));
        $meta = array('page_title' => lang('Create Allotment'), 'bc' => $bc, 'page_name' => 'Create Allotment');

		$this->data['company_code'] = $company_code;
		$this->data['company_class'] = $this->master_model->get_all_company_share_type($company_code);

		$r = $this->db->query("select status, company_name from client where company_code = '".$company_code."'");
        $r = $r->result_array();

		$this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Allotment', base_url('masterclient/view_allotment/'.$company_code.''));
		$this->mybreadcrumb->add('Create Allotment - '.$this->encryption->decrypt($r[0]["company_name"]).'', base_url());
		$this->data['breadcrumbs'] = $this->mybreadcrumb->render();

		$this->data["client_status"] = $r[0]["status"];

        $this->page_construct('client/allotment.php', $meta, $this->data);
	}

	public function edit_allotment ($transaction_id, $client_member_share_capital_id, $company_code) 
	{
		$bc = array(array('link' => '#', 'page' => lang('Edit Allotment')));
        $meta = array('page_title' => lang('Edit Allotment'), 'bc' => $bc, 'page_name' => 'Edit Allotment');

		$this->data['company_code'] = $company_code;
		$this->data['company_class'] = $this->master_model->get_all_non_exist_company_share_type($client_member_share_capital_id,$company_code);
		$this->data['allotment'] = $this->master_model->get_edit_allotment_group($transaction_id, $company_code);

		$r = $this->db->query("select status, company_name from client where company_code = '".$company_code."'");
        $r = $r->result_array();

		$this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Allotment', base_url('masterclient/view_allotment/'.$company_code.''));
		$this->mybreadcrumb->add('Edit Allotment - '.$this->encryption->decrypt($r[0]["company_name"]).'', base_url());
		$this->data['breadcrumbs'] = $this->mybreadcrumb->render();

		$this->data["client_status"] = $r[0]["status"];

		$allotment_id = array();
        $this->session->set_userdata(array(
            'allotment_id'  =>  $allotment_id,
        ));

        $this->page_construct('client/allotment.php', $meta, $this->data);
	}

	public function check_cert_no()
	{
		$certificate_no = $_POST["certificate_no"];
		$client_member_share_capital_id = $_POST["client_member_share_capital_id"];
		$cert_id = $_POST["cert_id"];

		if($certificate_no != "NA")
		{
			if($cert_id == "")
			{
				$q = $this->db->query("select * from certificate where client_member_share_capital_id = '".$client_member_share_capital_id."' AND certificate_no = '".$certificate_no."'");

				if ($q->num_rows() > 0) {
		            echo json_encode(false);
		        }
		        else
		        {
		        	echo json_encode(true);
		        }
		    }
		    else
		    {
		    	$q = $this->db->query("select * from certificate where client_member_share_capital_id = '".$client_member_share_capital_id."' AND certificate_no = '".$certificate_no."' AND id != '".$cert_id."'");

				if ($q->num_rows() > 0) {
		            echo json_encode(false);
		        }
		        else
		        {
		        	echo json_encode(true);
		        }
		    }
		}
		else
		{
			echo json_encode(true);
		}
	}

	public function get_allotment_certificate()
	{
		$client_member_share_capital_id = $_POST["client_member_share_capital_id"];
		$company_code = $_POST["company_code"];
		$officer_id = $_POST["officer_id"];
		$field_type = $_POST["field_type"];
		$transaction_type = $_POST["transaction_type"];
		$date = $_POST["date"];
		$id_member_share = $_POST["member_share_id"];

		$this->data['last_cert_no'] = $this->transaction_model->get_last_cert_no($company_code, $client_member_share_capital_id);

		echo json_encode(array($this->data));
	}

	public function get_transfer_certificate()
	{
		$client_member_share_capital_id = $_POST["client_member_share_capital_id"];
		$company_code = $_POST["company_code"];
		$officer_id = $_POST["officer_id"];
		$field_type = $_POST["field_type"];

		//echo json_encode($client_member_share_capital_id);
		
		$q = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where member_shares.company_code="'.$company_code.'" AND member_shares.client_member_share_capital_id = "'.$client_member_share_capital_id.'" GROUP BY member_shares.field_type, member_shares.officer_id HAVING sum(member_shares.number_of_share) != 0');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        echo (FALSE);
	}

	public function get_the_previous_certificate()
	{
		$client_member_share_capital_id = $_POST["client_member_share_capital_id"];
		$company_code = $_POST["company_code"];
		$officer_id = $_POST["officer_id"];
		$field_type = $_POST["field_type"];
		$transaction_type = $_POST["transaction_type"];

		$q = $this->db->query("select certificate.*, member_shares.cert_status from certificate left join member_shares on certificate.officer_id = member_shares.officer_id and certificate.company_code = member_shares.company_code and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id and member_shares.id = (
	           SELECT MAX(id) 
	           FROM member_shares as z 
	           WHERE z.officer_id = member_shares.officer_id and z.company_code = member_shares.company_code and z.field_type = member_shares.field_type and z.transaction_id = member_shares.transaction_id
	        ) where certificate.client_member_share_capital_id='".$client_member_share_capital_id."' AND certificate.company_code='".$company_code."' AND certificate.officer_id='".$officer_id."' AND certificate.field_type='".$field_type."' AND certificate.status = 1");

        //echo json_encode($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        else
        {
        	echo FALSE;
        }
	}

	public function get_the_previous_certificate_for_to()
	{
		$client_member_share_capital_id = $_POST["client_member_share_capital_id"];
		$company_code = $_POST["company_code"];
		$officer_id = $_POST["officer_id"];
		$field_type = $_POST["field_type"];
		$transaction_type = $_POST["transaction_type"];
		$date = $_POST["date"];
		$id_member_share = $_POST["member_share_id"];

		if(isset($id_member_share))
		{
			$p = $this->db->query('select merge from member_shares where member_shares.id = "'.$id_member_share.'"');

			if ($p->num_rows() > 0) 
			{
	        	$p = $p->result_array();
	        	$merge_info = $p[0]["merge"];
	        }
	        else
	        {
	        	$merge_info = 0;
	        }
		}
        else
        {
        	$merge_info = 0;
        }

        if($merge_info == 1)
        {
			$q = $this->db->query("select certificate.*, member_shares.cert_status from certificate left join member_shares on certificate.officer_id = member_shares.officer_id and certificate.company_code = member_shares.company_code and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id and member_shares.id = (
	           SELECT MAX(id) 
	           FROM member_shares as z 
	           WHERE z.officer_id = member_shares.officer_id and z.company_code = member_shares.company_code and z.field_type = member_shares.field_type and z.transaction_id = member_shares.transaction_id
	        ) where certificate.client_member_share_capital_id='".$client_member_share_capital_id."' AND certificate.company_code='".$company_code."' AND certificate.officer_id='".$officer_id."' AND certificate.field_type='".$field_type."'");
		}
		else if($merge_info == 0)
		{
			$q = $this->db->query("select certificate.*, member_shares.cert_status from certificate left join member_shares on certificate.officer_id = member_shares.officer_id and certificate.company_code = member_shares.company_code and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id and member_shares.id = (
	           SELECT MAX(id) 
	           FROM member_shares as z 
	           WHERE z.officer_id = member_shares.officer_id and z.company_code = member_shares.company_code and z.field_type = member_shares.field_type and z.transaction_id = member_shares.transaction_id
	        ) where certificate.client_member_share_capital_id='".$client_member_share_capital_id."' AND certificate.company_code='".$company_code."' AND certificate.officer_id='".$officer_id."' AND certificate.field_type='".$field_type."' AND STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') < STR_TO_DATE('".$date. "','%d/%m/%Y') AND member_shares.cert_status = 1 AND certificate.status = 1");
		}

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            echo json_encode(array("certificate_data" => $data, 'merge_status' => $merge_info));
        }
        else
        {
        	echo FALSE;
        }
	}

	public function save_buyback()
	{
		$_POST['buyback_id'] = array_values($_POST['buyback_id']);
		$_POST['officer_id'] = array_values($_POST['officer_id']);
		$_POST['field_type'] = array_values($_POST['field_type']);
		$_POST['member_name'] = array_values($_POST['member_name']);
		$_POST['current_number_of_share'] = array_values($_POST['current_number_of_share']);
		$_POST['current_amount_share'] = array_values($_POST['current_amount_share']);
		$_POST['share_buyback'] = array_values($_POST['share_buyback']);
		$_POST['new_number_of_share'] = array_values($_POST['new_number_of_share']);
		$_POST['hidden_certificate_no'] = array_values($_POST['hidden_certificate_no']);


		$transaction_id = "TR-".mt_rand(100000000, 999999999);

		$previous_member_shares_query = $this->db->get_where("member_shares", array("id" => $_POST['buyback_id'][0]));

		$previous_member_shares_query_data = $previous_member_shares_query->result_array();

		if ($previous_member_shares_query->num_rows())
		{
			$previous_member_share_result = $this->db->query("select member_shares.* from member_shares where company_code='".$_POST['company_code']."' AND member_shares.transaction_type = 'Buyback' AND member_shares.client_member_share_capital_id = '".$_POST['client_member_share_capital_id']."' AND STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$previous_member_shares_query_data[0]["transaction_date"]."','%d/%m/%Y') ORDER BY member_shares.id");

			$previous_member_share_result = $previous_member_share_result->result_array();
		}

		//echo json_encode($_POST);
		$new_buyback_id = array();

		for($i = 0; $i < count($_POST['officer_id']); $i++ )
		{
			$temperary_cert = "TEMPORARY".mt_rand(100000000, 999999999);
			$create_new_cert = "NC".mt_rand(100000000, 999999999);

			$per_share = (float)$_POST['current_amount_share'][$i] / (int)$_POST['current_number_of_share'][$i];

			$buyback["company_code"] = $_POST['company_code'];
			$buyback["transaction_date"] = $_POST['date'];
			$buyback["client_member_share_capital_id"] = $_POST['client_member_share_capital_id'];
			$buyback["officer_id"] = $_POST['officer_id'][$i];
			$buyback["field_type"] = $_POST['field_type'][$i];
			$buyback["transaction_type"] = $_POST['transaction_type'];
			$buyback["number_of_share"] = -((int)str_replace(',', '', $_POST['share_buyback'][$i]));
			$buyback["amount_share"] = -((float)str_replace(',', '', $_POST['share_buyback'][$i]) * $per_share);
			$buyback["no_of_share_paid"] = -((int)str_replace(',', '', $_POST['share_buyback'][$i]));
			$buyback["amount_paid"] = -((float)str_replace(',', '', $_POST['share_buyback'][$i]) * $per_share);

			$q = $this->db->get_where("member_shares", array("id" => $_POST['buyback_id'][$i]));

			$previous_member_share_info = $q->result_array();

			if (!$q->num_rows())
			{				
				$buyback["transaction_id"] = $transaction_id;
				$buyback["cert_status"] = 1;
				$this->db->insert("member_shares",$buyback);
				$insert_buyback_id = $this->db->insert_id();
				array_push($new_buyback_id, $insert_buyback_id);
				
			} 
			else 
			{	
				
				$this->db->delete('member_shares', array("id" => $_POST['buyback_id'][$i]));

				$buyback["transaction_id"] = $previous_member_share_info[0]["transaction_id"];
				$buyback["cert_status"] = $previous_member_share_info[0]["cert_status"];
				$this->db->insert("member_shares",$buyback);
				$insert_buyback_id = $this->db->insert_id();

				array_push($new_buyback_id, $insert_buyback_id);
				
			}

			$certificate["company_code"] = $_POST['company_code'];
			$certificate["client_member_share_capital_id"] = $_POST['client_member_share_capital_id'];
			$certificate["officer_id"] = $_POST['officer_id'][$i];
			$certificate["field_type"] = $_POST['field_type'][$i];

			if($_POST['buyback_id'][$i] == null)
			{
				$certificate["transaction_id"] = $transaction_id;

				
				$query = $this->db->query("select certificate.*, member_shares.company_code, member_shares.client_member_share_capital_id, member_shares.officer_id, member_shares.field_type, member_shares.transaction_id, member_shares.transaction_date from certificate left join member_shares on certificate.company_code = member_shares.company_code and certificate.officer_id = member_shares.officer_id and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id where certificate.client_member_share_capital_id='".$_POST['client_member_share_capital_id']."' AND certificate.company_code='".$_POST['company_code']."' AND certificate.officer_id='".$_POST['officer_id'][$i]."' AND certificate.field_type='".$_POST['field_type'][$i]."' AND UNIX_TIMESTAMP(STR_TO_DATE('".$_POST['date']."','%d/%m/%Y')) = UNIX_TIMESTAMP(STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y'))");

				$query = $query->result_array();

				for($k = 0; $k < count($query); $k++)
				{
					$this->db->set("status", 2);
					$this->db->where(array("id" => $query[$k]["id"]));
					$this->db->update("certificate");

					$merge_certificate["company_code"] = $query[$k]['company_code'];
					$merge_certificate["merge_date"] = date('d-m-Y',now());
					//$certificate["transaction_date"] = $_POST['date'];
					$merge_certificate["client_member_share_capital_id"] = $query[$k]['client_member_share_capital_id'];
					$merge_certificate["officer_id"] = $query[$k]['officer_id'];
					$merge_certificate["field_type"] = $query[$k]['field_type'];
					$merge_certificate["transaction_id"] = $transaction_id;
					$merge_certificate["number_of_share"] = $query[$k]["number_of_share"];
					$merge_certificate["amount_share"] = $query[$k]["amount_share"];
					$merge_certificate["no_of_share_paid"] = $query[$k]["no_of_share_paid"];
					$merge_certificate["amount_paid"] = $query[$k]["amount_paid"];
					$merge_certificate["certificate_no"] = $query[$k]["certificate_no"];

					if($_POST['hidden_certificate_no'][$i] != " ")
					{
						$merge_certificate["new_certificate_no"] = $_POST['hidden_certificate_no'][$i];
					}
					elseif((int)str_replace(',', '', $_POST['share_buyback'][$i]) == 0)
					{
						$merge_certificate["new_certificate_no"] = $create_new_cert;
					}
					else
					{
						$merge_certificate["new_certificate_no"] = $temperary_cert;
					}

					$this->db->insert("certificate_merge",$merge_certificate);
				}

				$merge_certificate["company_code"] = $_POST['company_code'];
				$merge_certificate["merge_date"] = date('d-m-Y',now());
				//$certificate["transaction_date"] = $_POST['date'];
				$merge_certificate["client_member_share_capital_id"] = $_POST['client_member_share_capital_id'];
				$merge_certificate["officer_id"] = $_POST['officer_id'][$i];
				$merge_certificate["field_type"] = $_POST['field_type'][$i];
				$merge_certificate["transaction_id"] = $transaction_id;
				$merge_certificate["number_of_share"] = -((int)str_replace(',', '', $_POST['share_buyback'][$i]));
				$merge_certificate["amount_share"] = -((float)str_replace(',', '', $_POST['share_buyback'][$i]) * $per_share);
				$merge_certificate["no_of_share_paid"] = -((int)str_replace(',', '', $_POST['share_buyback'][$i]));
				$merge_certificate["amount_paid"] = -((float)str_replace(',', '', $_POST['share_buyback'][$i]) * $per_share);
				$merge_certificate["certificate_no"] = "";

				if($_POST['hidden_certificate_no'][$i] != " ")
				{
					$merge_certificate["new_certificate_no"] = $_POST['hidden_certificate_no'][$i];
				}
				elseif((int)str_replace(',', '', $_POST['share_buyback'][$i]) == 0)
				{
					$merge_certificate["new_certificate_no"] = $create_new_cert;
				}
				else
				{
					$merge_certificate["new_certificate_no"] = $temperary_cert;
				}
				

				$this->db->insert("certificate_merge",$merge_certificate);

				$certificate["number_of_share"] = -((int)str_replace(',', '', $_POST['share_buyback'][$i]));
				if($_POST['hidden_certificate_no'][$i] != " ")
				{
					$certificate["certificate_no"] = $_POST['hidden_certificate_no'][$i];
				}
				elseif((int)str_replace(',', '', $_POST['share_buyback'][$i]) == 0)
				{
					$certificate["certificate_no"] = $create_new_cert;
				}
				else
				{
					$certificate["certificate_no"] = $temperary_cert;
				}
				$certificate["status"] = 1;	

				$this->db->insert("certificate",$certificate);

				if($_POST['hidden_certificate_no'][$i] != " ")
				{
					$query_recalculate_num_of_share = $this->db->query("select sum(number_of_share) as number_of_share, sum(amount_share) as amount_share, sum(no_of_share_paid) as no_of_share_paid, sum(amount_paid) as amount_paid from certificate_merge where client_member_share_capital_id='".$_POST['client_member_share_capital_id']."' AND company_code='".$_POST['company_code']."' AND officer_id='".$_POST['officer_id'][$i]."' AND field_type='".$_POST['field_type'][$i]."' AND new_certificate_no = '".$_POST['hidden_certificate_no'][$i]."'");

					$this->db->where(array("client_member_share_capital_id" => $_POST['client_member_share_capital_id'], "company_code" => $_POST['company_code'], "officer_id" =>$_POST['officer_id'][$i], "field_type" => $_POST['field_type'][$i], "certificate_no" => $_POST['hidden_certificate_no'][$i]));
				}
				elseif((int)str_replace(',', '', $_POST['share_buyback'][$i]) == 0)
				{
					$query_recalculate_num_of_share = $this->db->query("select sum(number_of_share) as number_of_share, sum(amount_share) as amount_share, sum(no_of_share_paid) as no_of_share_paid, sum(amount_paid) as amount_paid from certificate_merge where client_member_share_capital_id='".$_POST['client_member_share_capital_id']."' AND company_code='".$_POST['company_code']."' AND officer_id='".$_POST['officer_id'][$i]."' AND field_type='".$_POST['field_type'][$i]."' AND new_certificate_no = '".$create_new_cert."'");

					$this->db->where(array("client_member_share_capital_id" => $_POST['client_member_share_capital_id'], "company_code" => $_POST['company_code'], "officer_id" =>$_POST['officer_id'][$i], "field_type" => $_POST['field_type'][$i], "certificate_no" => $create_new_cert));
				}
				else
				{
					$query_recalculate_num_of_share = $this->db->query("select sum(number_of_share) as number_of_share, sum(amount_share) as amount_share, sum(no_of_share_paid) as no_of_share_paid, sum(amount_paid) as amount_paid from certificate_merge where client_member_share_capital_id='".$_POST['client_member_share_capital_id']."' AND company_code='".$_POST['company_code']."' AND officer_id='".$_POST['officer_id'][$i]."' AND field_type='".$_POST['field_type'][$i]."' AND new_certificate_no = '".$temperary_cert."'");

					$this->db->where(array("client_member_share_capital_id" => $_POST['client_member_share_capital_id'], "company_code" => $_POST['company_code'], "officer_id" =>$_POST['officer_id'][$i], "field_type" => $_POST['field_type'][$i], "certificate_no" => $temperary_cert));
				}

				$query_recalculate_num_of_share = $query_recalculate_num_of_share->result_array();

				$new_number_of_share["number_of_share"] = $query_recalculate_num_of_share[0]["number_of_share"];
				$new_number_of_share["amount_share"] = $query_recalculate_num_of_share[0]["amount_share"];
				$new_number_of_share["no_of_share_paid"] = $query_recalculate_num_of_share[0]["no_of_share_paid"];
				$new_number_of_share["amount_paid"] = $query_recalculate_num_of_share[0]["amount_paid"];
				
				$this->db->update("certificate",$new_number_of_share);

			}
			elseif($_POST['buyback_id'][$i] != null)
			{
				$company_code = $previous_member_share_info[0]["company_code"];
				$transaction_id = $previous_member_share_info[0]["transaction_id"];
				$officer_id = $previous_member_share_info[0]["officer_id"];
				$field_type = $previous_member_share_info[0]["field_type"];
				$client_member_share_capital_id = $previous_member_share_info[0]["client_member_share_capital_id"];

				$query_certificate = $this->db->query("select id, company_code, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, certificate_no, status from certificate where client_member_share_capital_id = '".$client_member_share_capital_id."' AND officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND transaction_id = '".$transaction_id."' AND field_type = '".$field_type."'");

				$query_certificate = $query_certificate->result_array();

				$certificate["transaction_id"] = $query_certificate[0]["transaction_id"];
				$certificate["number_of_share"] = -((int)str_replace(',', '', $_POST['share_buyback'][$i]));
				$certificate["amount_share"] = -((float)str_replace(',', '', $_POST['share_buyback'][$i]) * $per_share);
				$certificate["no_of_share_paid"] = -((int)str_replace(',', '', $_POST['share_buyback'][$i]));
				$certificate["amount_paid"] = -((float)str_replace(',', '', $_POST['share_buyback'][$i]) * $per_share);
				//$certificate["certificate_no"] = $_POST['new_certificate_no'][$i];

				if($_POST['hidden_certificate_no'][$i] != " ")
				{
					$certificate["certificate_no"] = $_POST['hidden_certificate_no'][$i];
				}
				elseif((int)str_replace(',', '', $_POST['share_buyback'][$i]) == 0)
				{
					$certificate["certificate_no"] = $create_new_cert;
				}
				else
				{
					$certificate["certificate_no"] = $temperary_cert;
				}

				$certificate["status"] = $query_certificate[0]["status"];
			
				$this->db->insert("certificate",$certificate);

				$this->db->delete('certificate', array("id" => $query_certificate[0]["id"]));

				$query_certificate_merge = $this->db->query("select id, company_code, merge_date, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, certificate_no, new_certificate_no from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND transaction_id = '".$transaction_id."' AND field_type = '".$field_type."' AND certificate_no = ''");

				
				if ($query_certificate_merge->num_rows() > 0) 
				{
					
					$query_certificate_merge = $query_certificate_merge->result_array();
					//echo json_encode($query_certificate_merge);

					if($officer_id == $_POST['officer_id'][$i] && $field_type == $_POST['field_type'][$i])
					{
						$merge_certificate["company_code"] = $_POST['company_code'];
						$merge_certificate["merge_date"] = $query_certificate_merge[0]["merge_date"];
						//$certificate["transaction_date"] = $_POST['date'];
						$merge_certificate["client_member_share_capital_id"] = $_POST['client_member_share_capital_id'];
						$merge_certificate["officer_id"] = $_POST['officer_id'][$i];
						$merge_certificate["field_type"] = $_POST['field_type'][$i];
						$merge_certificate["transaction_id"] = $query_certificate_merge[0]["transaction_id"];
						$merge_certificate["number_of_share"] = -((int)str_replace(',', '', $_POST['share_buyback'][$i]));
						$merge_certificate["amount_share"] = -((float)str_replace(',', '', $_POST['share_buyback'][$i]) * $per_share);
						$merge_certificate["no_of_share_paid"] = -((int)str_replace(',', '', $_POST['share_buyback'][$i]));
						$merge_certificate["amount_paid"] = -((float)str_replace(',', '', $_POST['share_buyback'][$i]) * $per_share);
						$merge_certificate["certificate_no"] = $query_certificate_merge[0]["certificate_no"];



						$query_check_new_certificate_merge = $this->db->query("select id, company_code, merge_date, client_member_share_capital_id, officer_id, field_type, transaction_id, sum(number_of_share) as total_number_of_share, certificate_no, new_certificate_no from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND transaction_id = '".$transaction_id."' AND field_type = '".$field_type."' AND new_certificate_no = '".$query_certificate[0]["certificate_no"]."' AND certificate_no = ''");

						if ($query_check_new_certificate_merge->num_rows() > 0) 
						{
							$query_check_new_certificate_merge = $query_check_new_certificate_merge->result_array();

							if($query_check_new_certificate_merge[0]["certificate_no"] == null)
							{
								if($_POST['hidden_certificate_no'][$i] != " ")
								{
									$new_certificate_number = $_POST['hidden_certificate_no'][$i];
									//$certificate["certificate_no"] = $_POST['hidden_certificate_no'][$i];
									$new_cert_num["new_certificate_no"] =  $_POST['hidden_certificate_no'][$i];
								}
								elseif((int)str_replace(',', '', $_POST['share_buyback'][$i]) == 0)
								{
									$new_certificate_number = $create_new_cert;
									//$certificate["certificate_no"] = $create_new_cert;
									$new_cert_num["new_certificate_no"] =  $create_new_cert;
								}
								else
								{
									$new_certificate_number = $temperary_cert;
									//$certificate["certificate_no"] = $temperary_cert;
									$new_cert_num["new_certificate_no"] =  $temperary_cert;
								}
								/*echo json_encode($total_sum_number_of_share);*/
								$this->db->where(array("new_certificate_no" => $query_check_new_certificate_merge[0]["new_certificate_no"]));
								$this->db->update("certificate_merge",$new_cert_num);

								if($_POST['hidden_certificate_no'][$i] != " ")
								{
									$merge_certificate["new_certificate_no"] =  $_POST['hidden_certificate_no'][$i];
								}
								elseif((int)str_replace(',', '', $_POST['share_buyback'][$i]) == 0)
								{
									$merge_certificate["new_certificate_no"] =  $create_new_cert;
								}
								else
								{
									$merge_certificate["new_certificate_no"] =  $temperary_cert;
								}

								$this->db->insert("certificate_merge",$merge_certificate);
								$this->db->delete('certificate_merge', array("id" => $query_certificate_merge[0]["id"]));

								$total_sum_number_of_share = $this->db->query("select sum(number_of_share) as total_number_of_share, sum(amount_share) as total_amount_share, sum(no_of_share_paid) as total_no_of_share_paid, sum(amount_paid) as total_amount_paid from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND field_type = '".$field_type."' AND transaction_id = '".$transaction_id."' AND new_certificate_no = '".$query_certificate[0]["certificate_no"]."'");

								if($total_sum_number_of_share->num_rows() > 0)
								{
									$total_sum_number_of_share = $total_sum_number_of_share->result_array();
									

									$total_number_of_share = $total_sum_number_of_share[0]["total_number_of_share"];
									$total_amount_share = $total_sum_number_of_share[0]["total_amount_share"];
									$total_no_of_share_paid = $total_sum_number_of_share[0]["total_no_of_share_paid"];
									$total_amount_paid = $total_sum_number_of_share[0]["total_amount_paid"];
								}

								$query_check_cert_num_with_cert_num = $this->db->query("select id, company_code, merge_date, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, certificate_no, new_certificate_no from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND field_type = '".$field_type."' AND certificate_no = '".$query_certificate[0]["certificate_no"]."'");

								if ($query_check_cert_num_with_cert_num->num_rows() > 0) 
								{
									$query_check_cert_num_with_cert_num = $query_check_cert_num_with_cert_num->result_array();
									$new_cert_no = $query_check_cert_num_with_cert_num[0]["new_certificate_no"];

									if((int)$total_number_of_share != (int)$query_check_cert_num_with_cert_num[0]["number_of_share"])
									{
										$query_new_number_of_share_in_cert_merge = (int)$total_number_of_share;
										$query_new_amount_share_in_cert_merge = (int)$total_amount_share;
										$query_new_no_of_share_paid_in_cert_merge = (int)$total_no_of_share_paid;
										$query_new_amount_paid_in_cert_merge = (int)$total_amount_paid;
									}
									else
									{
										$query_new_number_of_share_in_cert_merge = (int)$total_number_of_share;
										$query_new_amount_share_in_cert_merge = (int)$total_amount_share;
										$query_new_no_of_share_paid_in_cert_merge = (int)$total_no_of_share_paid;
										$query_new_amount_paid_in_cert_merge = (int)$total_amount_paid;
									}
									
								
									if($_POST['hidden_certificate_no'][$i] != " ")
									{
										$cert["certificate_no"] = $_POST['hidden_certificate_no'][$i];
									}
									elseif((int)str_replace(',', '', $_POST['share_buyback'][$i]) == 0)
									{
										$cert["certificate_no"] = $create_new_cert;
									}
									else
									{
										$cert["certificate_no"] = $temperary_cert;
									}

									$cert["number_of_share"] = $query_new_number_of_share_in_cert_merge;
									$cert["amount_share"] = $query_new_amount_share_in_cert_merge;
									$cert["no_of_share_paid"] = $query_new_no_of_share_paid_in_cert_merge;
									$cert["amount_paid"] = $query_new_amount_paid_in_cert_merge;

									$this->db->where(array("id" => $query_check_cert_num_with_cert_num[0]["id"]));
									$this->db->update("certificate_merge",$cert);

									$query_change_first_certificate = $this->db->query("select id, company_code, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, certificate_no, status from certificate where client_member_share_capital_id = '".$client_member_share_capital_id."' AND officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND field_type = '".$field_type."' AND certificate_no = '".$query_check_cert_num_with_cert_num[0]["new_certificate_no"]."'");

									if ($query_change_first_certificate->num_rows() > 0) 
									{
										$query_change_first_certificate = $query_change_first_certificate->result_array();


										$total_cert_sum_number_of_share = $this->db->query("select sum(number_of_share) as total_number_of_share, sum(amount_share) as total_amount_share, sum(no_of_share_paid) as total_no_of_share_paid, sum(amount_paid) as total_amount_paid from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND field_type = '".$field_type."' AND new_certificate_no = '".$query_check_cert_num_with_cert_num[0]["new_certificate_no"]."'");

										if($total_cert_sum_number_of_share->num_rows() > 0)
										{
											$total_cert_sum_number_of_share = $total_cert_sum_number_of_share->result_array();
											

											$new_cert_number_of_share = $total_cert_sum_number_of_share[0]["total_number_of_share"];
											$new_cert_amount_share = $total_cert_sum_number_of_share[0]["total_amount_share"];
											$new_cert_no_of_share_paid = $total_cert_sum_number_of_share[0]["total_no_of_share_paid"];
											$new_cert_amount_paid = $total_cert_sum_number_of_share[0]["total_amount_paid"];
										}

										$new_certs_number_of_share["number_of_share"] = $new_cert_number_of_share;
										$new_certs_number_of_share["amount_share"] = $new_cert_amount_share;
										$new_certs_number_of_share["no_of_share_paid"] = $new_cert_no_of_share_paid;
										$new_certs_number_of_share["amount_paid"] = $new_cert_amount_paid;

										$this->db->where(array("id" => $query_change_first_certificate[0]["id"]));
										$this->db->update("certificate",$new_certs_number_of_share);
									}

									while($new_cert_no != null)
									{
										$query_check_another_cert_num_with_cert_num = $this->db->query("select id, company_code, merge_date, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, certificate_no, new_certificate_no from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND field_type = '".$field_type."' AND certificate_no = '".$new_cert_no."'");

										if ($query_check_another_cert_num_with_cert_num->num_rows() > 0) 
										{
											$query_check_another_cert_num_with_cert_num = $query_check_another_cert_num_with_cert_num->result_array();

											if((int)$total_number_of_share != (int)$query_another_new_number_of_share_in_cert_merge[0]["number_of_share"])
											{
												$query_another_new_number_of_share_in_cert_merge = (int)$total_number_of_share;
												$query_another_new_amount_share_in_cert_merge = (int)$total_amount_share;
												$query_another_new_no_of_share_paid_in_cert_merge = (int)$total_no_of_share_paid;
												$query_another_new_amount_paid_in_cert_merge = (int)$total_amount_paid;
											}
											else
											{
												$query_another_new_number_of_share_in_cert_merge = (int)$total_number_of_share;
												$query_another_new_amount_share_in_cert_merge = (int)$total_amount_share;
												$query_another_new_no_of_share_paid_in_cert_merge = (int)$total_no_of_share_paid;
												$query_another_new_amount_paid_in_cert_merge = (int)$total_amount_paid;
											}

											$certs["number_of_share"] = $query_another_new_number_of_share_in_cert_merge;
											$certs["amount_share"] = $query_another_new_amount_share_in_cert_merge;
											$certs["no_of_share_paid"] = $query_another_new_no_of_share_paid_in_cert_merge;
											$certs["amount_paid"] = $query_another_new_amount_paid_in_cert_merge;

											$this->db->where(array("id" => $query_check_another_cert_num_with_cert_num[0]["id"]));
											$this->db->update("certificate_merge",$certs);

											$query_change_certificate = $this->db->query("select id, company_code, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, certificate_no, status from certificate where client_member_share_capital_id = '".$client_member_share_capital_id."' AND officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND field_type = '".$field_type."' AND certificate_no = '".$query_check_another_cert_num_with_cert_num[0]["new_certificate_no"]."'");

											if ($query_change_certificate->num_rows() > 0) 
											{
												$query_change_certificate = $query_change_certificate->result_array();

												$total_cert_sum_number_of_share = $this->db->query("select sum(number_of_share) as total_number_of_share, sum(amount_share) as total_amount_share, sum(no_of_share_paid) as total_no_of_share_paid, sum(amount_paid) as total_amount_paid from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND field_type = '".$field_type."' AND new_certificate_no = '".$query_check_another_cert_num_with_cert_num[0]["new_certificate_no"]."'");

												if($total_cert_sum_number_of_share->num_rows() > 0)
												{
													$total_cert_sum_number_of_share = $total_cert_sum_number_of_share->result_array();
													

													$new_cert_number_of_share = $total_cert_sum_number_of_share[0]["total_number_of_share"];
													$new_cert_amount_share = $total_cert_sum_number_of_share[0]["total_amount_share"];
													$new_cert_no_of_share_paid = $total_cert_sum_number_of_share[0]["total_no_of_share_paid"];
													$new_cert_amount_paid = $total_cert_sum_number_of_share[0]["total_amount_paid"];
												}

												$new_certs_number_of_share["number_of_share"] = $new_cert_number_of_share;
												$new_certs_number_of_share["amount_share"] = $new_cert_amount_share;
												$new_certs_number_of_share["no_of_share_paid"] = $new_cert_no_of_share_paid;
												$new_certs_number_of_share["amount_paid"] = $new_cert_amount_paid;

												$this->db->where(array("id" => $query_change_certificate[0]["id"]));
												$this->db->update("certificate",$new_certs_number_of_share);
											}

											$new_cert_no = $query_check_another_cert_num_with_cert_num[0]["new_certificate_no"];
										}
										else
										{
											$new_cert_no = null;
										}
									}
								}
							}
							else
							{

								if($_POST['hidden_certificate_no'][$i] != " ")
								{
									$merge_certificate["new_certificate_no"] =  $_POST['hidden_certificate_no'][$i];
								}
								elseif((int)str_replace(',', '', $_POST['share_buyback'][$i]) == 0)
								{
									$merge_certificate["new_certificate_no"] =  $create_new_cert;
								}
								else
								{
									$merge_certificate["new_certificate_no"] =  $temperary_cert;
								}

								$this->db->insert("certificate_merge",$merge_certificate);
								$this->db->delete('certificate_merge', array("id" => $query_certificate_merge[0]["id"]));
								
							}
						}
						else
						{
							if($_POST['hidden_certificate_no'][$i] != " ")
							{
								$merge_certificate["new_certificate_no"] =  $_POST['hidden_certificate_no'][$i];
							}
							elseif((int)str_replace(',', '', $_POST['share_buyback'][$i]) == 0)
							{
								$merge_certificate["new_certificate_no"] =  $create_new_cert;
							}
							else
							{
								$merge_certificate["new_certificate_no"] =  $temperary_cert;
							}

							$this->db->insert("certificate_merge",$merge_certificate);
							$this->db->delete('certificate_merge', array("id" => $query_certificate_merge[0]["id"]));
						}

						
					}
				}

				$query_recalculate_num_of_share = $this->db->query("select sum(number_of_share) as number_of_share, sum(amount_share) as amount_share, sum(no_of_share_paid) as no_of_share_paid, sum(amount_paid) as amount_paid from certificate_merge where new_certificate_no = '".$new_certificate_number."' AND client_member_share_capital_id = '".$_POST['client_member_share_capital_id']."'");

				$query_recalculate_num_of_share = $query_recalculate_num_of_share->result_array();

				$new_number_of_share["number_of_share"] = $query_recalculate_num_of_share[0]["number_of_share"];
				$new_number_of_share["amount_share"] = $query_recalculate_num_of_share[0]["amount_share"];
				$new_number_of_share["no_of_share_paid"] = $query_recalculate_num_of_share[0]["no_of_share_paid"];
				$new_number_of_share["amount_paid"] = $query_recalculate_num_of_share[0]["amount_paid"];

				$this->db->where(array("client_member_share_capital_id" =>$_POST['client_member_share_capital_id'], "certificate_no" => $new_certificate_number));
				$this->db->update("certificate",$new_number_of_share);
			}
		}

		$get_client_information = $this->db->query("select client.*, company_type.company_type as company_type_name from client left join company_type on client.company_type = company_type.id where company_code='".$_POST['company_code']."'");

		$get_client_information = $get_client_information->result_array();

		$this->change_member_document_info("add_buyback_of_share", $_POST['client_member_share_capital_id'], $previous_member_shares_query_data[0]["transaction_date"], $get_client_information[0]["firm_id"], $_POST['company_code'], $previous_member_share_result, $get_client_information[0]["id"]);
		
		redirect("masterclient/view_buyback/".$_POST['company_code']."");
	}

	public function save_allotment()
	{
		$client_member_share_capital['number_of_shares'] = 0;
		$client_member_share_capital['amount'] = 0;
		$client_member_share_capital['paid_up'] = 0;
		$new_certificate_number = null;
		$total_number_of_share = 0;

		$_POST['officer_id'] = array_values($_POST['officer_id']);
		$_POST['number_of_share'] = array_values($_POST['number_of_share']);
		$_POST['amount_share'] = array_values($_POST['amount_share']);
		$_POST['amount_paid'] = array_values($_POST['amount_paid']);
		$_POST['field_type'] = array_values($_POST['field_type']);
		$_POST['no_of_share_paid'] = array_values($_POST['no_of_share_paid']);
		$_POST['new_certificate_no'] = array_values($_POST['new_certificate_no']);
		$_POST['merge_certificate_no'] = array_values($_POST['merge_certificate_no']);
		$_POST['merge_number_of_share'] = array_values($_POST['merge_number_of_share']);
		$_POST['merge_amount_share'] = array_values($_POST['merge_amount_share']);
		$_POST['merge_no_of_share_paid'] = array_values($_POST['merge_no_of_share_paid']);
		$_POST['merge_amount_paid'] = array_values($_POST['merge_amount_paid']);
		$_POST['member_share_id'] = array_values($_POST['member_share_id']);
		$_POST['merge_status'] = array_values($_POST['merge_status']);
		$_POST['previous_merge_cert_num'] = array_values($_POST['previous_merge_cert_num']);
		$_POST['latest_merge_cert_no'] = array_values($_POST['latest_merge_cert_no']);
		$_POST['name'] = array_values($_POST['name']);
		$_POST['class'] = array_values($_POST['class']);
		$_POST['currency'] = array_values($_POST['currency']);
		$_POST['others'] = array_values($_POST['others']);

		$previous_member_shares_query = $this->db->get_where("member_shares", array("id" => $_POST['member_share_id'][0]));

		$previous_member_shares_query_data = $previous_member_shares_query->result_array();

		if ($previous_member_shares_query->num_rows())
		{
			$previous_member_share_result = $this->db->query("select member_shares.* from member_shares where company_code='".$_POST['company_code']."' AND member_shares.transaction_type = 'Allotment' AND member_shares.client_member_share_capital_id = '".$_POST['client_member_share_capital_id']."' AND STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') = STR_TO_DATE('".$previous_member_shares_query_data[0]["transaction_date"]."','%d/%m/%Y') ORDER BY member_shares.id");
						//echo json_encode($check_date);
			$previous_member_share_result = $previous_member_share_result->result_array();
		}

		if(count($this->session->userdata('allotment_id')) > 0)
		{
			$this->delete_allot();
		}
		
		$new_allotment_id = array();

		for($i = 0; $i < count($_POST['officer_id']); $i++ )
		{
			$transaction_id = "TR-".mt_rand(100000000, 999999999);
			
			$allotment["company_code"] = $_POST['company_code'];
			$allotment["transaction_date"] = $_POST['date'];
			$allotment["client_member_share_capital_id"] = $_POST['client_member_share_capital_id'];
			$allotment["officer_id"] = $_POST['officer_id'][$i];
			$allotment["field_type"] = $_POST['field_type'][$i];
			$allotment["transaction_type"] = $_POST['transaction_type'];
			$allotment["number_of_share"] = (int)str_replace(',', '', $_POST['number_of_share'][$i]);
			$allotment["amount_share"] = (float)str_replace(',', '', $_POST['amount_share'][$i]);
			$allotment["no_of_share_paid"] = (int)str_replace(',', '', $_POST['no_of_share_paid'][$i]);
			$allotment["amount_paid"] = (float)str_replace(',', '', $_POST['amount_paid'][$i]);

			$q = $this->db->get_where("member_shares", array("id" => $_POST['member_share_id'][$i]));

			$previous_member_share_info = $q->result_array();

			if (!$q->num_rows())
			{				
				$allotment["transaction_id"] = $transaction_id;
				$allotment["cert_status"] = 1;
				$this->db->insert("member_shares",$allotment);
				$insert_allotmant_id = $this->db->insert_id();
				array_push($new_allotment_id, $insert_allotmant_id);

				if($_POST['others'][$i] != "")
	            {
	            	$class_name = $_POST['others'][$i];
	            }
	            else
	            {
	            	$class_name = $_POST['class'][$i];
	            }
				
				$this->save_audit_trail("Clients", "Allotment", "Allot ".$_POST['number_of_share'][$i]." ".$class_name." (".$_POST['currency'][$i].") to ".$_POST['name'][$i].".", $_POST['company_code']);
			} 
			else 
			{	
				$this->db->delete('member_shares', array("id" => $_POST['member_share_id'][$i]));

				$allotment["cert_status"] = $previous_member_share_info[0]["cert_status"];
				$allotment["transaction_id"] = $previous_member_share_info[0]["transaction_id"];
				$allotment["merge"] = $previous_member_share_info[0]["merge"];

				$this->db->insert("member_shares",$allotment);
				$insert_allotmant_id = $this->db->insert_id();
				array_push($new_allotment_id, $insert_allotmant_id);
			}

			$certificate["company_code"] = $_POST['company_code'];
			$certificate["client_member_share_capital_id"] = $_POST['client_member_share_capital_id'];
			$certificate["officer_id"] = $_POST['officer_id'][$i];
			$certificate["field_type"] = $_POST['field_type'][$i];

			if($_POST['member_share_id'][$i] == null)
			{
				$certificate["transaction_id"] = $transaction_id;
				$certificate["number_of_share"] = (int)str_replace(',', '', $_POST['number_of_share'][$i]);
				$certificate["amount_share"] = (float)str_replace(',', '', $_POST['amount_share'][$i]);
				$certificate["no_of_share_paid"] = (int)str_replace(',', '', $_POST['no_of_share_paid'][$i]);
				$certificate["amount_paid"] = (float)str_replace(',', '', $_POST['amount_paid'][$i]);
				$certificate["certificate_no"] = $_POST['new_certificate_no'][$i];	
				$certificate["new_certificate_no"] = $_POST['new_certificate_no'][$i];	
				$certificate["status"] = 1;	

				$this->db->insert("certificate",$certificate);
			}
			else if($_POST['member_share_id'][$i] != null)
			{
				$company_code = $previous_member_share_info[0]["company_code"];
				$transaction_id = $previous_member_share_info[0]["transaction_id"];
				$officer_id = $previous_member_share_info[0]["officer_id"];
				$field_type = $previous_member_share_info[0]["field_type"];
				$client_member_share_capital_id = $previous_member_share_info[0]["client_member_share_capital_id"];

				$query_certificate = $this->db->query("select certificate.id, certificate.company_code, certificate.client_member_share_capital_id, certificate.officer_id, certificate.field_type, certificate.transaction_id, certificate.number_of_share, certificate.certificate_no, certificate.new_certificate_no, certificate.status, member_shares.cert_status from certificate left join member_shares on certificate.company_code = member_shares.company_code and certificate.officer_id = member_shares.officer_id and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id where certificate.client_member_share_capital_id = '".$client_member_share_capital_id."' AND certificate.officer_id = '".$officer_id."' AND certificate.company_code = '".$company_code."' AND certificate.transaction_id = '".$transaction_id."' AND certificate.field_type = '".$field_type."'");

				$query_certificate = $query_certificate->result_array();

				if(count($query_certificate == 0))
				{
					$certificate["transaction_id"] = $transaction_id;
				}
				else
				{
					$certificate["transaction_id"] = $query_certificate[0]["transaction_id"];
				}

				$certificate["number_of_share"] = (int)str_replace(',', '', $_POST['number_of_share'][$i]);
				$certificate["amount_share"] = (float)str_replace(',', '', $_POST['amount_share'][$i]);
				$certificate["no_of_share_paid"] = (int)str_replace(',', '', $_POST['no_of_share_paid'][$i]);
				$certificate["amount_paid"] = (float)str_replace(',', '', $_POST['amount_paid'][$i]);

				if($_POST['new_certificate_no'][$i] != null )
				{
					$certificate["certificate_no"] = $_POST['new_certificate_no'][$i];
					$certificate["new_certificate_no"] = $_POST['new_certificate_no'][$i];
				}
				else
				{
					$certificate["certificate_no"] = $query_certificate[0]["certificate_no"];
					$certificate["new_certificate_no"] = $query_certificate[0]["new_certificate_no"];
				}
				
				if($officer_id != $_POST['officer_id'][$i] || $field_type != $_POST['field_type'][$i])
				{
					$certificate["status"] = 1;
				}	
				else
				{
					if(count($query_certificate == 0))
					{
						$query_check_the_date_after_trans = $this->db->query("select member_shares.id from member_shares where company_code = '".$company_code."' AND STR_TO_DATE(member_shares.transaction_date,'%d/%m/%Y') > STR_TO_DATE('".$_POST['date']. "','%d/%m/%Y') AND (transaction_type = 'Transfer' OR transaction_type = 'Buyback')");

						if (!$query_check_the_date_after_trans->num_rows())
						{
							$certificate["status"] = 1;
						}
						else
						{
							$certificate["status"] = 2;
						}
					}
					else
					{
						$certificate["status"] = $query_certificate[0]["status"];
					}
				}

				$this->db->insert("certificate",$certificate);

				$this->db->delete('certificate', array("id" => $query_certificate[0]["id"]));

				$this->db->set("cert_status", $query_certificate[0]["cert_status"]);
				$this->db->where(array("transaction_id" => $transaction_id));
				$this->db->update("member_shares");

				$query_previous_cert = $this->db->query("select certificate.*, member_shares.company_code, member_shares.client_member_share_capital_id, member_shares.officer_id, member_shares.field_type, member_shares.transaction_id from certificate left join member_shares on certificate.company_code = member_shares.company_code and certificate.officer_id = member_shares.officer_id and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id where certificate.client_member_share_capital_id='".$_POST['client_member_share_capital_id']."' AND certificate.company_code='".$_POST['company_code']."' AND certificate.officer_id='".$_POST['officer_id'][$i]."' AND certificate.field_type='".$_POST['field_type'][$i]."' AND certificate.new_certificate_no = '".$_POST['previous_merge_cert_num'][$i]."' AND member_shares.cert_status = 2");

				$query_previous_cert = $query_previous_cert->result_array();

				for($t = 0; $t < count($query_previous_cert); $t++)
				{
					$this->db->set("new_certificate_no", $query_previous_cert[$t]["certificate_no"]);
					$this->db->set("status", 1);
					$this->db->where(array("id" => $query_previous_cert[$t]["id"]));
					$this->db->update("certificate");
					
					$this->db->set("cert_status", 1);
					$this->db->where(array("transaction_id" => $query_previous_cert[$t]["transaction_id"]));
					$this->db->update("member_shares");
				}

				$this->db->set("merge", 0);
				$this->db->set("cert_status", 1);
				$this->db->where(array("transaction_id" => $transaction_id));
				$this->db->update("member_shares");

				$query_active_certificate = $this->db->query("select certificate.id, certificate.certificate_no from certificate left join member_shares on certificate.company_code = member_shares.company_code and certificate.officer_id = member_shares.officer_id and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id where certificate.officer_id = '".$officer_id."' AND certificate.company_code = '".$company_code."' AND certificate.client_member_share_capital_id = '".$client_member_share_capital_id."' AND certificate.field_type = '".$field_type."' AND certificate.transaction_id = '".$previous_member_share_info[0]["transaction_id"]."' AND member_shares.cert_status = 1");

				$query_active_certificate = $query_active_certificate->result_array();

				if($new_certificate_number == null)
				{
					$query_new_certificate_no = $this->db->query("select new_certificate_no from certificate_merge where new_certificate_no = '".$query_active_certificate[0]["certificate_no"]."' and transaction_id = '".$previous_member_share_info[0]["transaction_id"]."'");

					$query_new_certificate_no = $query_new_certificate_no->result_array();

					$new_certificate_number = $query_new_certificate_no[0]["new_certificate_no"];
				}

				$query_recalculate_num_of_share = $this->db->query("select sum(number_of_share) as number_of_share, sum(amount_share) as amount_share, sum(no_of_share_paid) as no_of_share_paid, sum(amount_paid) as amount_paid from certificate_merge where new_certificate_no = '".$new_certificate_number."' AND client_member_share_capital_id = '".$_POST['client_member_share_capital_id']."'");

				$query_recalculate_num_of_share = $query_recalculate_num_of_share->result_array();

				$new_number_of_share["number_of_share"] = $query_recalculate_num_of_share[0]["number_of_share"];
				$new_number_of_share["amount_share"] = $query_recalculate_num_of_share[0]["amount_share"];
				$new_number_of_share["no_of_share_paid"] = $query_recalculate_num_of_share[0]["no_of_share_paid"];
				$new_number_of_share["amount_paid"] = $query_recalculate_num_of_share[0]["amount_paid"];

				$this->db->where(array("client_member_share_capital_id" => $_POST['client_member_share_capital_id'], "certificate_no" => $new_certificate_number));
				$this->db->update("certificate",$new_number_of_share);
			}
		}

		$this->master_model->update_controller_detail($_POST['company_code'], $_POST['date']);
		$this->master_model->check_client_company_type($_POST['company_code']);

		redirect("masterclient/view_allotment/".$_POST['company_code']."");
	}//end allotment

	public function check_share($company_code, $client_member_share_capital_id, $officer_id, $field_type, $certificate_no, $transaction_type, $each_transfer = null)
	{
		$bc = array(array('link' => '#', 'page' => "Member"));
        $meta = array('page_title' => "Member", 'bc' => $bc, 'page_name' =>  "Member");

		$query = $this->db->query('select member_shares.*, member_shares.number_of_share as number_of_share, member_shares.amount_share as amount_share, member_shares.no_of_share_paid as no_of_share_paid, member_shares.amount_paid as amount_paid, member_shares.transaction_type, DATE_FORMAT(STR_TO_DATE(member_shares.transaction_date, "%d/%m/%Y"), "%d/%m/%Y") as transaction_date, STR_TO_DATE(member_shares.transaction_date, "%d/%m/%Y") as trans_date, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency, certificate.certificate_no from member_shares left join certificate on certificate.officer_id = member_shares.officer_id and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where member_shares.company_code="'.$company_code.'" AND member_shares.client_member_share_capital_id="'.$client_member_share_capital_id.'" AND member_shares.officer_id="'.$officer_id.'" AND member_shares.field_type="'.$field_type.'" ORDER BY STR_TO_DATE(member_shares.transaction_date, "%d/%m/%Y")');

		if ($query->num_rows() > 0) {
            foreach (($query->result()) as $row) {
                $data[] = $row;
            }
            $this->data['member'] = $data;
        }

        $this->data['certificate_no'] = $certificate_no;
        $this->data['company_code'] = $company_code;
        $this->data['client_member_share_capital_id'] = $client_member_share_capital_id;
        $this->data['officer_id'] = $officer_id;
        $this->data['field_type'] = $field_type;
        $this->data['transaction_type'] = $transaction_type;
        $this->data['each_transfer'] = $each_transfer;

        $this->page_construct('client/check_share.php', $meta, $this->data);
	}

	public function check_transfer_share()
	{
		$bc = array(array('link' => '#', 'page' => "Member"));
        $meta = array('page_title' => "Member", 'bc' => $bc, 'page_name' =>  "Member");

		$this->session->set_userdata(array(
			'transfer_cert_data'  =>  array(),
            'transfer_cert_info'  =>  array(),
            'transfer_cert'  =>  array(),
        ));

		$cert_info = $_POST["cert_info"];
		for($t = 0; $t < count($cert_info); $t++)
		{
			$query = $this->db->query('select member_shares.*, member_shares.number_of_share as number_of_share, member_shares.amount_share as amount_share, member_shares.no_of_share_paid as no_of_share_paid, member_shares.amount_paid as amount_paid, member_shares.transaction_type, DATE_FORMAT(STR_TO_DATE(member_shares.transaction_date, "%d/%m/%Y"), "%d/%m/%Y") as transaction_date, STR_TO_DATE(member_shares.transaction_date, "%d/%m/%Y") as trans_date, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency, certificate.certificate_no from member_shares left join certificate on certificate.officer_id = member_shares.officer_id and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where member_shares.company_code="'.$cert_info[$t]["company_code"].'" AND member_shares.client_member_share_capital_id="'.$cert_info[$t]["client_member_share_capital_id"].'" AND member_shares.officer_id="'.$cert_info[$t]["officer_id"].'" AND member_shares.field_type="'.$cert_info[$t]["field_type"].'" ORDER BY STR_TO_DATE(member_shares.transaction_date, "%d/%m/%Y")');

			if ($query->num_rows() > 0) {
	            foreach (($query->result()) as $row) {
	                $data[] = $row;
	            }
	        }
	        $certificate[] = $cert_info[$t]["certificate_no"];
		}

		$this->session->set_userdata(array(
			'transfer_cert_data'  =>  $cert_info,
            'transfer_cert_info'  =>  $data,
            'transfer_cert'  =>  $certificate,
        ));
	}

	public function open_transfer_share()
	{
		$bc = array(array('link' => '#', 'page' => "Member"));
        $meta = array('page_title' => "Member", 'bc' => $bc, 'page_name' =>  "Member");

        $this->data['cert_data'] = $this->session->userdata("transfer_cert_data");
		$this->data['member'] = $this->session->userdata("transfer_cert_info");
		$this->data['certificate_no'] = $this->session->userdata("transfer_cert");

		$this->page_construct('client/check_transfer_share.php', $meta, $this->data);
	}

	public function delete_subsequent_allotment()
	{
		$certificate_no = $_POST["certificate_no"];
		$company_code = $_POST["company_code"];
		$client_member_share_capital_id = $_POST["client_member_share_capital_id"];
		$officer_id = $_POST["officer_id"];
		$field_type = $_POST["field_type"];
		$transaction_type = $_POST["transaction_type"];
		$each_transfer = $_POST["each_transfer"];

		if($transaction_type == "Transfer" || $transaction_type == "Buyback")
		{
			$query_check_previous_cert_num_with_cert_num = $this->db->query("select id, company_code, merge_date, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, amount_share, no_of_share_paid, amount_paid, certificate_no, new_certificate_no from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND field_type = '".$field_type."' AND new_certificate_no = '".$certificate_no."'");

			if ($query_check_previous_cert_num_with_cert_num->num_rows() > 0) 
			{
				$query_check_previous_cert_num_with_cert_num = $query_check_previous_cert_num_with_cert_num->result_array();

				if($query_check_previous_cert_num_with_cert_num[0]["certificate_no"] != null)
				{
					$this->db->set("status", 1);
					$this->db->where(array("certificate_no" => $query_check_previous_cert_num_with_cert_num[0]["certificate_no"]));
					$this->db->update("certificate");
				}

				$this->db->delete('certificate_merge', array("client_member_share_capital_id" => $query_check_previous_cert_num_with_cert_num[0]["client_member_share_capital_id"], "new_certificate_no" => $query_check_previous_cert_num_with_cert_num[0]["new_certificate_no"]));
			}
		}

		$query_check_cert_num_with_cert_num = $this->db->query("select id, company_code, merge_date, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, amount_share, no_of_share_paid, amount_paid, certificate_no, new_certificate_no from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND field_type = '".$field_type."' AND certificate_no = '".$certificate_no."'");

		if ($query_check_cert_num_with_cert_num->num_rows() > 0) 
		{
			$query_check_cert_num_with_cert_num = $query_check_cert_num_with_cert_num->result_array();

			$new_cert_no = $query_check_cert_num_with_cert_num[0]["new_certificate_no"];	

			if($each_transfer == null)
			{
				$query_change_first_certificate = $this->db->query("select id, company_code, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, amount_share, no_of_share_paid, amount_paid, certificate_no, status from certificate where client_member_share_capital_id = '".$client_member_share_capital_id."' AND officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND field_type = '".$field_type."' AND certificate_no = '".$query_check_cert_num_with_cert_num[0]["certificate_no"]."'");

				$query_change_first_certificate = $query_change_first_certificate->result_array();

				$this->db->delete('certificate', array("company_code" => $company_code, "client_member_share_capital_id" => $query_check_cert_num_with_cert_num[0]["client_member_share_capital_id"], "certificate_no" => $query_check_cert_num_with_cert_num[0]["certificate_no"]));

				$this->db->delete('member_shares', array("client_member_share_capital_id" => $client_member_share_capital_id, "officer_id" => $officer_id, "field_type" => $field_type, "transaction_id" => $query_change_first_certificate[0]['transaction_id']));
			}
			if($transaction_type != "Transfer")
			{
			 	$this->db->query("
			 						DELETE t1, t2
			 						FROM member_shares t1 JOIN certificate t2
			 						ON t1.transaction_id = t2.transaction_id
			 						WHERE t1.transaction_type = 'Transfer' AND t1.transaction_id = '".$query_change_first_certificate[0]['transaction_id']."'");
			}

			while($new_cert_no != null)
			{
				$query_check_another_cert_num_with_cert_num = $this->db->query("select id, company_code, merge_date, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, amount_share, no_of_share_paid, amount_paid, certificate_no, new_certificate_no from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND field_type = '".$field_type."' AND new_certificate_no = '".$new_cert_no."'");

				if ($query_check_another_cert_num_with_cert_num->num_rows() > 0) 
				{
					$query_check_another_cert_num_with_cert_num = $query_check_another_cert_num_with_cert_num->result_array();

					$this->db->delete('certificate_merge', array("client_member_share_capital_id" => $query_check_another_cert_num_with_cert_num[0]["client_member_share_capital_id"], "new_certificate_no" => $query_check_another_cert_num_with_cert_num[0]["new_certificate_no"]));

					$query_change_certificate = $this->db->query("select id, company_code, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, amount_share, no_of_share_paid, amount_paid, certificate_no, status from certificate where officer_id = '".$officer_id."' AND client_member_share_capital_id = '".$client_member_share_capital_id."' AND company_code = '".$company_code."' AND field_type = '".$field_type."' AND certificate_no = '".$query_check_another_cert_num_with_cert_num[0]["new_certificate_no"]."'");

					$query_change_certificate = $query_change_certificate->result_array();
					
					$this->db->delete('certificate', array("client_member_share_capital_id" => $query_check_another_cert_num_with_cert_num[0]["client_member_share_capital_id"], "certificate_no" => $query_check_another_cert_num_with_cert_num[0]["new_certificate_no"]));

					$this->db->delete('member_shares', array("company_code" => $company_code, "client_member_share_capital_id" => $client_member_share_capital_id, "officer_id" => $officer_id, "field_type" => $field_type, "transaction_id" => $query_change_certificate[0]['transaction_id']));

					if($transaction_type != "Transfer")
			 		{
						$this->db->query("
							DELETE t1, t2
							FROM member_shares t1 JOIN certificate t2
							ON t1.transaction_id = t2.transaction_id
							WHERE t1.transaction_type = 'Transfer' AND t1.transaction_id = '".$query_change_certificate[0]['transaction_id']."'");
					}

					$next_cert = $this->db->query("select id, company_code, merge_date, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, amount_share, no_of_share_paid, amount_paid, certificate_no, new_certificate_no from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND field_type = '".$field_type."' AND certificate_no = '".$new_cert_no."'");

					$next_cert = $next_cert->result_array();
					
					$new_cert_no = $next_cert[0]["new_certificate_no"];
				}
				else
				{
					$new_cert_no = null;
				}
			}
		}
		echo json_encode(array('message' => 'Information Updated', 'title' => 'Updated'));
	}

	public function check_negative_number_of_share()
	{
		$client_member_share_capital_id = $_POST["client_member_share_capital_id"];
		$number_of_share = $_POST["number_of_share"];
		$certificate_no = $_POST["certificate_no"];
		$company_code = $_POST["company_code"];
		$officer_id = $_POST["to_officer_id"];
		$field_type = $_POST["to_field_type"];
		$id = $_POST["id"];

		$query_check_cert_num_with_cert_num = $this->db->query("select sum(number_of_share) as number_of_share, sum(amount_share) as amount_share, sum(no_of_share_paid) as no_of_share_paid, sum(amount_paid) as amount_paid from member_shares where client_member_share_capital_id = '".$client_member_share_capital_id."' AND officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND field_type = '".$field_type."' AND (
			   select STR_TO_DATE(member_shares.transaction_date, '%d/%m/%Y')
			   from member_shares 
			   where id= '".$id."'
			 ) < STR_TO_DATE(member_shares.transaction_date, '%d/%m/%Y') GROUP BY member_shares.field_type, member_shares.officer_id");


		if ($query_check_cert_num_with_cert_num->num_rows() > 0) 
		{
			$query_check_cert_num_with_cert_num = $query_check_cert_num_with_cert_num->result_array();

			$total_no_of_share = (int)$query_check_cert_num_with_cert_num[0]["number_of_share"] + (int)$number_of_share;
		}
		else
		{
			$total_no_of_share = 0 + (int)$number_of_share;
		}

		if( 0 > $total_no_of_share)
		{
			echo json_encode(array('popup' => 1));
		}
		else
		{
			echo json_encode(array('popup' => 2));
		}
	}

	public function delete_subsequent_transfer()
	{
		$cert_data = $_POST["cert_data"];

		for($g = 0; $g < count($cert_data); $g++)
		{
			$certificate_no = $cert_data[$g]["certificate_no"];
			$company_code = $cert_data[$g]["company_code"];
			$client_member_share_capital_id = $cert_data[$g]["client_member_share_capital_id"];
			$officer_id = $cert_data[$g]["officer_id"];
			$field_type = $cert_data[$g]["field_type"];

			$query_check_previous_cert_num_with_cert_num = $this->db->query("select id, company_code, merge_date, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, amount_share, no_of_share_paid, amount_paid, certificate_no, new_certificate_no from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND field_type = '".$field_type."' AND new_certificate_no = '".$certificate_no."' order by id");

			if ($query_check_previous_cert_num_with_cert_num->num_rows() > 0) 
			{
				$query_check_previous_cert_num_with_cert_num = $query_check_previous_cert_num_with_cert_num->result_array();

				if($query_check_previous_cert_num_with_cert_num[0]["certificate_no"] != null)
				{
					$this->db->set("status", 1);
					$this->db->where(array("certificate_no" => $query_check_previous_cert_num_with_cert_num[0]["certificate_no"], "client_member_share_capital_id" => $query_check_previous_cert_num_with_cert_num[0]["client_member_share_capital_id"]));
					$this->db->update("certificate");
				}

				$this->db->delete('certificate_merge', array("client_member_share_capital_id" => $query_check_previous_cert_num_with_cert_num[0]["client_member_share_capital_id"], "new_certificate_no" => $query_check_previous_cert_num_with_cert_num[0]["new_certificate_no"]));
			}

			$query_check_cert_num_with_cert_num = $this->db->query("select id, company_code, merge_date, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, amount_share, no_of_share_paid, amount_paid, certificate_no, new_certificate_no from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND field_type = '".$field_type."' AND certificate_no = '".$certificate_no."'");

			if ($query_check_cert_num_with_cert_num->num_rows() > 0) 
			{
				$query_check_cert_num_with_cert_num = $query_check_cert_num_with_cert_num->result_array();

				$new_cert_no = $query_check_cert_num_with_cert_num[0]["new_certificate_no"];	

				$query_change_first_certificate = $this->db->query("select id, company_code, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, amount_share, no_of_share_paid, amount_paid, certificate_no, status from certificate where client_member_share_capital_id = '".$client_member_share_capital_id."' AND officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND field_type = '".$field_type."' AND certificate_no = '".$query_check_cert_num_with_cert_num[0]["certificate_no"]."'");

				$query_change_first_certificate = $query_change_first_certificate->result_array();

				$this->db->delete('certificate', array("company_code" => $company_code, "client_member_share_capital_id" => $query_check_cert_num_with_cert_num[0]["client_member_share_capital_id"], "certificate_no" => $query_check_cert_num_with_cert_num[0]["certificate_no"]));

				$this->db->delete('member_shares', array("client_member_share_capital_id" => $client_member_share_capital_id, "officer_id" => $officer_id, "field_type" => $field_type, "transaction_id" => $query_change_first_certificate[0]['transaction_id']));

				$this->db->query("
							DELETE t1, t2
							FROM member_shares t1 JOIN certificate t2
							ON t1.transaction_id = t2.transaction_id
							WHERE t1.transaction_type = 'Transfer' AND t1.transaction_id = '".$query_change_first_certificate[0]['transaction_id']."'");

				while($new_cert_no != null)
				{
					$query_check_another_cert_num_with_cert_num = $this->db->query("select id, company_code, merge_date, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, amount_share, no_of_share_paid, amount_paid, certificate_no, new_certificate_no from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND field_type = '".$field_type."' AND new_certificate_no = '".$new_cert_no."'");

					if ($query_check_another_cert_num_with_cert_num->num_rows() > 0) 
					{
						$query_check_another_cert_num_with_cert_num = $query_check_another_cert_num_with_cert_num->result_array();

						$this->db->delete('certificate_merge', array("client_member_share_capital_id" => $query_check_another_cert_num_with_cert_num[0]["client_member_share_capital_id"], "new_certificate_no" => $query_check_another_cert_num_with_cert_num[0]["new_certificate_no"]));

						$query_change_certificate = $this->db->query("select id, company_code, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, amount_share, no_of_share_paid, amount_paid, certificate_no, status from certificate where officer_id = '".$officer_id."' AND client_member_share_capital_id = '".$client_member_share_capital_id."' AND company_code = '".$company_code."' AND field_type = '".$field_type."' AND certificate_no = '".$query_check_another_cert_num_with_cert_num[0]["new_certificate_no"]."'");

						$query_change_certificate = $query_change_certificate->result_array();

						
						 $this->db->delete('certificate', array("client_member_share_capital_id" => $query_check_another_cert_num_with_cert_num[0]["client_member_share_capital_id"], "certificate_no" => $query_check_another_cert_num_with_cert_num[0]["new_certificate_no"]));

						 $this->db->delete('member_shares', array("company_code" => $company_code, "client_member_share_capital_id" => $client_member_share_capital_id, "officer_id" => $officer_id, "field_type" => $field_type, "transaction_id" => $query_change_certificate[0]['transaction_id']));

						$this->db->query("
							DELETE t1, t2
							FROM member_shares t1 JOIN certificate t2
							ON t1.transaction_id = t2.transaction_id
							WHERE t1.transaction_type = 'Transfer' AND t1.transaction_id = '".$query_change_certificate[0]['transaction_id']."'");

						$next_cert = $this->db->query("select id, company_code, merge_date, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, amount_share, no_of_share_paid, amount_paid, certificate_no, new_certificate_no from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND field_type = '".$field_type."' AND certificate_no = '".$new_cert_no."'");

						$next_cert = $next_cert->result_array();
						
						$new_cert_no = $next_cert[0]["new_certificate_no"];
					}
					else
					{
						$new_cert_no = null;
					}
				}
			}
		}

		echo json_encode(array('message' => 'Information Updated', 'title' => 'Updated'));
	}

	public function delete_allot_follow_by_cert()
	{
		$certificate_no = $_POST["certificate_no"];
		$client_member_share_capital_id = $_POST["client_member_share_capital_id"];

		$query_certificate_merge = $this->db->query("select * from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND certificate_no = '".$certificate_no."'");

		if ($query_certificate_merge->num_rows() > 0) 
		{
			$query_certificate_merge = $query_certificate_merge->result_array();

			echo json_encode(array('status' => '1', 'client_member_share_capital_id' => $query_certificate_merge[0]["client_member_share_capital_id"], 'officer_id' => $query_certificate_merge[0]["officer_id"], 'field_type' => $query_certificate_merge[0]["field_type"])); //ask_confirm_delete
		}
		else
		{
			$query_certificate = $this->db->query("select certificate.*, member_shares.id as member_shares_id from certificate left join member_shares on member_shares.transaction_id = certificate.transaction_id where certificate.client_member_share_capital_id = '".$client_member_share_capital_id."' AND certificate.certificate_no = '".$certificate_no."' order by id");

			if ($query_certificate->num_rows() > 0) 
			{
				$query_certificate = $query_certificate->result_array();

				$member_shares_id = array();
				for($t = 0; $t < count($query_certificate); $t++)
				{
					array_push($member_shares_id, (int)$query_certificate[$t]["member_shares_id"]);
				}
				$this->db->delete('member_shares', array("transaction_id" => $query_certificate[0]['transaction_id']));
				$this->db->delete('certificate', array("transaction_id" => $query_certificate[0]['transaction_id']));
				$this->db->delete('pending_documents', array("allotment_id" => json_encode($member_shares_id)));
			}
			echo json_encode(array('status' => '2', 'message' => 'Information Updated', 'title' => 'Updated'));
		}
	}

	public function delete_buyback_follow_by_cert()
	{
		$transaction_id = $_POST["transaction_id"];
		$certificate_no = $_POST["certificate_no"];
		$client_member_share_capital_id = $_POST["client_member_share_capital_id"];

		$query_certificate_merge = $this->db->query("select * from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND certificate_no = '".$certificate_no."'");

		if ($query_certificate_merge->num_rows() > 0) 
		{
			$query_certificate_merge = $query_certificate_merge->result_array();

			echo json_encode(array('status' => '1', 'client_member_share_capital_id' => $query_certificate_merge[0]["client_member_share_capital_id"], 'officer_id' => $query_certificate_merge[0]["officer_id"], 'field_type' => $query_certificate_merge[0]["field_type"]));
		}
		else
		{
			$query_certificate_merge = $this->db->query("select * from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND transaction_id = '".$transaction_id."'");

			if ($query_certificate_merge->num_rows() > 0) 
			{
				$query_certificate_merge = $query_certificate_merge->result_array();

				for($t = 0; $t < count($query_certificate_merge); $t++)
				{
					if($query_certificate_merge[$t]["certificate_no"] != null)
					{
						$this->db->set("status", 1);
						$this->db->where(array("certificate_no" => $query_certificate_merge[$t]["certificate_no"], "client_member_share_capital_id" => $query_certificate_merge[$t]["client_member_share_capital_id"]));
						$this->db->update("certificate");
					}

					$this->db->delete('member_shares', array("transaction_id" => $query_certificate_merge[$t]['transaction_id']));
					$this->db->delete('certificate', array("transaction_id" => $query_certificate_merge[$t]['transaction_id']));
					$this->db->delete('certificate_merge', array("transaction_id" => $query_certificate_merge[$t]['transaction_id']));
				}
			}
			else
			{
				$query_certificate = $this->db->query("select * from certificate where client_member_share_capital_id = '".$client_member_share_capital_id."' AND certificate_no = '".$certificate_no."'");

				if ($query_certificate->num_rows() > 0) 
				{
					$query_certificate = $query_certificate->result_array();

					$this->db->delete('member_shares', array("transaction_id" => $query_certificate[0]['transaction_id']));
					$this->db->delete('certificate', array("transaction_id" => $query_certificate[0]['transaction_id']));
				}
			}

			echo json_encode(array('status' => '2', 'message' => 'Information Updated', 'title' => 'Updated'));
		}
	}

	public function delete_transfer_follow_by_cert()
	{
		$transaction_id = $_POST["transaction_id"];
		$client_member_share_capital_id = $_POST["client_member_share_capital_id"];

		$query_certificate = $this->db->query("select * from certificate where client_member_share_capital_id = '".$client_member_share_capital_id."' AND transaction_id = '".$transaction_id."'");

		if ($query_certificate->num_rows() > 0) 
		{
			$query_certificate = $query_certificate->result_array();

			$cert_array = array();
			for($r = 0; $r < count($query_certificate); $r++)
			{
				
				array_push($cert_array,$query_certificate[$r]["certificate_no"]);
			}

			$this->db->where('client_member_share_capital_id', $client_member_share_capital_id);
			$this->db->where_in('certificate_no', $cert_array);
			$query_certificate_merge = $this->db->get('certificate_merge');

			if ($query_certificate_merge->num_rows() > 0) 
			{
				$query_certificate_merge = $query_certificate_merge->result_array();
				echo json_encode(array('status' => '1', 'query_certificate_merge' => $query_certificate_merge));
			}
			else
			{
				$query_certificate_merge = $this->db->query("select * from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND transaction_id = '".$transaction_id."'");

				if ($query_certificate_merge->num_rows() > 0) 
				{
					$query_certificate_merge = $query_certificate_merge->result_array();

					for($t = 0; $t < count($query_certificate_merge); $t++)
					{
						if($query_certificate_merge[$t]["certificate_no"] != null)
						{
							$this->db->set("status", 1);
							$this->db->where(array("certificate_no" => $query_certificate_merge[$t]["certificate_no"], "client_member_share_capital_id" => $query_certificate_merge[$t]["client_member_share_capital_id"]));
							$this->db->update("certificate");
						}

						$this->db->delete('member_shares', array("transaction_id" => $query_certificate_merge[$t]['transaction_id']));
						$this->db->delete('certificate', array("transaction_id" => $query_certificate_merge[$t]['transaction_id']));
						$this->db->delete('certificate_merge', array("transaction_id" => $query_certificate_merge[$t]['transaction_id']));
					}
				}
				else
				{
						$this->db->delete('member_shares', array("transaction_id" => $transaction_id));
						$this->db->delete('certificate', array("transaction_id" => $transaction_id));
				}

				echo json_encode(array('status' => '2', 'message' => 'Information Updated', 'title' => 'Updated'));
			}
		}
	}

	public function delete_allot()
	{
		$member_share_id = $this->session->userdata('allotment_id');

		for($i = 0; $i < count($member_share_id); $i++ )
		{
			$q = $this->db->get_where("member_shares", array("id" => $member_share_id[$i]));

			$this->db->delete('member_shares', array("id" => $member_share_id[$i]));

			$previous_member_share_info = $q->result_array();

			$company_code = $previous_member_share_info[0]["company_code"];
			$transaction_id = $previous_member_share_info[0]["transaction_id"];
			$officer_id = $previous_member_share_info[0]["officer_id"];
			$field_type = $previous_member_share_info[0]["field_type"];
			$client_member_share_capital_id = $previous_member_share_info[0]["client_member_share_capital_id"];

			$query_certificate = $this->db->query("select id, company_code, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, certificate_no, status from certificate where officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND transaction_id = '".$transaction_id."' AND field_type = '".$field_type."'");

			$query_certificate = $query_certificate->result_array();

			$this->db->delete('certificate', array("id" => $query_certificate[0]["id"]));

			$query_certificate_merge = $this->db->query("select id, company_code, merge_date, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, certificate_no, new_certificate_no from certificate_merge where officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND transaction_id = '".$transaction_id."' AND field_type = '".$field_type."'");

			if ($query_certificate_merge->num_rows() > 0) 
			{
				$query_certificate_merge = $query_certificate_merge->result_array();

				for($i = 0; $i < count($query_certificate_merge); $i++)
				{
					$this->db->delete('certificate_merge', array("id" => $query_certificate_merge[$i]["id"]));
				}
			}
		}

		$this->session->set_userdata(array(
            'allotment_id'  =>  array(),
        ));
	}

	public function delete_transfer_from()
	{
		$member_share_id = $this->session->userdata('transfer_id');

		for($i = 0; $i < count($member_share_id); $i++ )
		{
			$q = $this->db->get_where("member_shares", array("id" => $member_share_id[$i]));

			$this->db->delete('member_shares', array("id" => $member_share_id[$i]));

			$previous_member_share_info = $q->result_array();

			$company_code = $previous_member_share_info[0]["company_code"];
			$transaction_id = $previous_member_share_info[0]["transaction_id"];
			$officer_id = $previous_member_share_info[0]["officer_id"];
			$field_type = $previous_member_share_info[0]["field_type"];
			$client_member_share_capital_id = $previous_member_share_info[0]["client_member_share_capital_id"];

			$query_certificate = $this->db->query("select id, company_code, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, certificate_no, status from certificate where officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND transaction_id = '".$transaction_id."' AND field_type = '".$field_type."'");

			$query_certificate = $query_certificate->result_array();

			$this->db->delete('certificate', array("id" => $query_certificate[0]["id"]));

			$query_certificate_merge = $this->db->query("select id, company_code, merge_date, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, certificate_no, new_certificate_no from certificate_merge where officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND transaction_id = '".$transaction_id."' AND field_type = '".$field_type."'");

			if ($query_certificate_merge->num_rows() > 0) 
			{
				$query_certificate_merge = $query_certificate_merge->result_array();

				for($i = 0; $i < count($query_certificate_merge); $i++)
				{
					if($i == 0)
					{
						$this->db->set("status", 1);
						$this->db->where(array("certificate_no" => $query_certificate_merge[$i]["certificate_no"], "client_member_share_capital_id" => $query_certificate_merge[$i]["client_member_share_capital_id"]));
						$this->db->update("certificate");
					}
					$this->db->delete('certificate_merge', array("id" => $query_certificate_merge[$i]["id"]));
				}
			}
		}

		$this->session->set_userdata(array(
            'transfer_id'  =>  array(),
        ));
	}

	public function delete_transfer_to()
	{
		$member_share_id = $this->session->userdata('to_id');

		for($i = 0; $i < count($member_share_id); $i++ )
		{
			$q = $this->db->get_where("member_shares", array("id" => $member_share_id[$i]));

			$this->db->delete('member_shares', array("id" => $member_share_id[$i]));

			$previous_member_share_info = $q->result_array();

			$company_code = $previous_member_share_info[0]["company_code"];
			$transaction_id = $previous_member_share_info[0]["transaction_id"];
			$officer_id = $previous_member_share_info[0]["officer_id"];
			$field_type = $previous_member_share_info[0]["field_type"];
			$client_member_share_capital_id = $previous_member_share_info[0]["client_member_share_capital_id"];

			$query_certificate = $this->db->query("select id, company_code, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, certificate_no, status from certificate where officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND transaction_id = '".$transaction_id."' AND field_type = '".$field_type."'");

			$query_certificate = $query_certificate->result_array();

			$this->db->delete('certificate', array("id" => $query_certificate[0]["id"]));

			$query_certificate_merge = $this->db->query("select id, company_code, merge_date, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, certificate_no, new_certificate_no from certificate_merge where officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND transaction_id = '".$transaction_id."' AND field_type = '".$field_type."'");

			if ($query_certificate_merge->num_rows() > 0) 
			{
				$query_certificate_merge = $query_certificate_merge->result_array();

				for($i = 0; $i < count($query_certificate_merge); $i++)
				{
					$this->db->delete('certificate_merge', array("id" => $query_certificate_merge[$i]["id"]));
				}
			}
		}

		$this->session->set_userdata(array(
            'to_id'  =>  array(),
        ));
	}
	
	public function delete_allotment()
    {
    	$allotment_delete_id = $_POST["allotment_id"];

    	$q = $this->db->get_where("member_shares", array("id" => $allotment_delete_id));
		$previous_member_share_info = $q->result_array();

		if(count($previous_member_share_info) > 0)
		{
			$company_code = $previous_member_share_info[0]["company_code"];
			$transaction_id = $previous_member_share_info[0]["transaction_id"];
			$officer_id = $previous_member_share_info[0]["officer_id"];
			$field_type = $previous_member_share_info[0]["field_type"];
			$client_member_share_capital_id = $previous_member_share_info[0]["client_member_share_capital_id"];
			$transaction_type = $previous_member_share_info[0]["transaction_type"];

			$query_certificate = $this->db->query("select id, company_code, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, certificate_no, status from certificate where officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND transaction_id = '".$transaction_id."' AND field_type = '".$field_type."'");

			$previous_query_certificate = $query_certificate->result_array();

			$certificate_no = $previous_query_certificate[0]["certificate_no"];

			$query_certificate_merge = $this->db->query("select * from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND certificate_no = '".$certificate_no."'");

			if ($query_certificate_merge->num_rows() > 0) 
			{
				$query_certificate_merge = $query_certificate_merge->result_array();

				echo json_encode(array('status' => 2, 'company_code' => $company_code, 'client_member_share_capital_id' => $query_certificate_merge[0]["client_member_share_capital_id"], 'officer_id' => $query_certificate_merge[0]["officer_id"], 'field_type' => $query_certificate_merge[0]["field_type"], 'certificate_no' => $certificate_no, 'transaction_type' => $transaction_type));
			}
			else
			{
				$allotment_id = $this->session->userdata('allotment_id');

		        if($allotment_delete_id != null)
		        {
		        	array_push($allotment_id, $allotment_delete_id);
			        $this->session->set_userdata(array(
			            'allotment_id'  =>  $allotment_id,
			        ));
		        }
		        echo json_encode(array('status' => 1));
			}
		}
		else
		{
			$allotment_id = $this->session->userdata('allotment_id');

	        if($allotment_delete_id != null)
	        {
	        	array_push($allotment_id, $allotment_delete_id);
		        $this->session->set_userdata(array(
		            'allotment_id'  =>  $allotment_id,
		        ));
	        }
	        echo json_encode(array('status' => 1));
		}
    }

    public function delete_transfer()
    {
    	$transfer_delete_id = $_POST["transfer_id"];
    	
    	$q = $this->db->get_where("member_shares", array("id" => $transfer_delete_id));
		$previous_member_share_info = $q->result_array();

		$company_code = $previous_member_share_info[0]["company_code"];
		$transaction_id = $previous_member_share_info[0]["transaction_id"];
		$officer_id = $previous_member_share_info[0]["officer_id"];
		$field_type = $previous_member_share_info[0]["field_type"];
		$client_member_share_capital_id = $previous_member_share_info[0]["client_member_share_capital_id"];
		$transaction_type = $previous_member_share_info[0]["transaction_type"];

		$query_certificate = $this->db->query("select id, company_code, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, certificate_no, status from certificate where officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND transaction_id = '".$transaction_id."' AND field_type = '".$field_type."'");

		$previous_query_certificate = $query_certificate->result_array();

		$certificate_no = $previous_query_certificate[0]["certificate_no"];

		$query_certificate_merge = $this->db->query("select * from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND certificate_no = '".$certificate_no."'");

		if ($query_certificate_merge->num_rows() > 0) 
		{
			$query_certificate_merge = $query_certificate_merge->result_array();

			echo json_encode(array('status' => 2, 'company_code' => $company_code, 'client_member_share_capital_id' => $query_certificate_merge[0]["client_member_share_capital_id"], 'officer_id' => $query_certificate_merge[0]["officer_id"], 'field_type' => $query_certificate_merge[0]["field_type"], 'certificate_no' => $certificate_no, 'transaction_type' => $transaction_type));
		}
		else
		{
			$transfer_id = $this->session->userdata('transfer_id');

	        if($transfer_delete_id != null)
	        {
	        	array_push($transfer_id, $transfer_delete_id);
		        $this->session->set_userdata(array(
		            'transfer_id'  =>  $transfer_id,
		        ));
	        }
	        echo json_encode(array('status' => 1));
		}
    }

    public function delete_to()
    {
    	$to_delete_id = $_POST["to_id"];

    	$q = $this->db->get_where("member_shares", array("id" => $to_delete_id));
		$previous_member_share_info = $q->result_array();

		$company_code = $previous_member_share_info[0]["company_code"];
		$transaction_id = $previous_member_share_info[0]["transaction_id"];
		$officer_id = $previous_member_share_info[0]["officer_id"];
		$field_type = $previous_member_share_info[0]["field_type"];
		$client_member_share_capital_id = $previous_member_share_info[0]["client_member_share_capital_id"];
		$transaction_type = $previous_member_share_info[0]["transaction_type"];

		$query_certificate = $this->db->query("select id, company_code, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, certificate_no, status from certificate where officer_id = '".$officer_id."' AND company_code = '".$company_code."' AND transaction_id = '".$transaction_id."' AND field_type = '".$field_type."'");

		$previous_query_certificate = $query_certificate->result_array();

		$certificate_no = $previous_query_certificate[0]["certificate_no"];

		$query_certificate_merge = $this->db->query("select * from certificate_merge where client_member_share_capital_id = '".$client_member_share_capital_id."' AND certificate_no = '".$certificate_no."'");

		if ($query_certificate_merge->num_rows() > 0) 
		{
			$query_certificate_merge = $query_certificate_merge->result_array();

			echo json_encode(array('status' => 2, 'company_code' => $company_code, 'client_member_share_capital_id' => $query_certificate_merge[0]["client_member_share_capital_id"], 'officer_id' => $query_certificate_merge[0]["officer_id"], 'field_type' => $query_certificate_merge[0]["field_type"], 'certificate_no' => $certificate_no, 'transaction_type' => $transaction_type));
		}
		else
		{	        
	        $to_id = $this->session->userdata('to_id');

	        if($to_delete_id != null)
	        {
	        	array_push($to_id, $to_delete_id);
		        $this->session->set_userdata(array(
		            'to_id'  =>  $to_id,
		        ));
	        }
	        echo json_encode(array('status' => 1));
		}
    }

    public function delete_billing_service()
    {
    	$delete_billing_service_id = $_POST["billing_service_id"];
        $billing_service_id = $this->session->userdata('billing_service_id');

        if($delete_billing_service_id != null)
        {
        	array_push($billing_service_id, $delete_billing_service_id);

	        $this->session->set_userdata(array(
	            'billing_service_id'  =>  $billing_service_id,
	        ));
        }
        echo "success";
    }

    public function delete_client_billing_info()
    {
    	$client_billing_info_id = $_POST["client_billing_info_id"];
    	$company_code = $_POST["company_code"];

        $check_billing_service_id = $this->db->get_where("client_billing_info", array("client_billing_info_id" => $client_billing_info_id, "company_code" => $company_code, "deleted"=> 0));
        if ($check_billing_service_id->num_rows())
        {	
        	$check_billing_service_id = $check_billing_service_id->result_array();
	        $check_billing_service_info = $this->db->get_where("billing_service", array("service" => $check_billing_service_id[0]["id"]));

	        if (!$check_billing_service_info->num_rows())
	        {
	        	$check_recur_billing_service_info = $this->db->get_where("recurring_billing_service", array("service" => $check_billing_service_id[0]["id"]));

	        	if (!$check_recur_billing_service_info->num_rows())
	        	{
	        		echo json_encode(array("Status" => 1));
	        	}
	        	else
	        	{
	        		echo json_encode(array("Status" => 2));
	        	}
			}
	        else
	        {
	        	$check_billing_service_info = $check_billing_service_info->result_array();

	        	$check_billing_info = $this->db->get_where("billing", array("id" => $check_billing_service_info[0]["billing_id"], "status" => 0));

	        	if(!$check_billing_info->num_rows())
	        	{
	        		echo json_encode(array("Status" => 1));
	        	}
	        	else
	        	{
	        		echo json_encode(array("Status" => 2));
	        	}
	        }
	    }
	    else
	    {
	    	echo json_encode(array("Status" => 1));
	    }
    }

	public function hapus_allotment($id){
		$this->db->delete('allotment',array('id'=>$id));
		$this->db->delete('allotment_member',array('id_allotment'=>$id));
	}
	
	public function read_buyback($unique_code,$sharetype,$currency)
	{
		$a = $this->master_model->get_all_allot_members($unique_code,$sharetype,$currency);
		$i=1;
		foreach($a as $b)
		{
			echo '<div class="hidden">';
			echo '<input type="text" name="id[]" value="'.$b->id.'"/>';
			echo '<input type="text" name="gid[]" value="'.$b->gid.'"/>';
			echo '<input type="text" name="nama[]" value="'.$b->nama.'"/>';
			echo '</div>';
			echo '<tr><td>'.$i.'</td>
			<td>'.$b->nama.'</td>
			<td>'.$b->gid.'</td>
			<td><input type="text" id="shareori_bb2" class=" form-control  number text-right" name="share_allotment[]" value="'.$b->share_allotment.'" readonly></div></td>
			<td><input type="text" id="amountori_bb2" class=" form-control  number text-right" name="amount_allotment[]" value="'.$b->amount_allotment.'" readonly></td>
			<td><input type="text" class="share_bb form-control  number text-right" name="sharebb_allotment[]" data-id="'.$i.'" data-gid="'.$b->gid.'" data-nama="'.$b->nama.'" data-shareori="'.$b->share_allotment.'" value="" ></td>
			<td><input type="text" id="" class="amount_bb form-control number text-right"  name="amountbb_allotment[]" data-amountori="'.$b->amount_allotment.'"  data-id="'.$i.'" value="" ></td>
			<td><input type="text" class="certificate_bb form-control" value="" name="certificate_allotment[]" data-id="'.$i.'" ></td>
			</tr>';
			$i++;
		}
	}
	
	public function read_buybackplain($unique_code,$sharetype,$currency)
	{
		$a = $this->master_model->get_all_allot_members($unique_code,$sharetype,$currency);
		$i=1;
		foreach($a as $b)
		{
			echo '<tr><td>'.$i.'</td>
			<td>'.$b->nama.'</td>
			<td>'.$b->gid.'</td>
			<td>'.$b->share_allotment.'</td>
			<td>'.$b->amount_allotment.'</td>
			<td id="share_bb'.$i.'"></td>
			<td id="amount_bb'.$i.'"></td>
			<td id="total_share_left'.$i.'"></td>
			<td id="total_amount_left'.$i.'"></td>
			<td id="certificate'.$i.'"></td>
			</tr>';
			$i++;
		}
	}
	
	public function search_member($type,$term)
	{
		if ($type=='nama')
		{
			$a = $this->master_model->get_all_person($term);
		} else {
			$a = $this->master_model->get_all_person('','',$term);
		}
		foreach($a as $b)
		{
			echo "<tr><td>".$b->gid."<td>".$b->nama."</td><td><a class=\"add_director btn-default btn\" style=\"padding:2px 3px;\" data-gid='".$b->gid."' data-nama='".$b->nama."'>Add</a></td></tr>";
		}
		
	}
	
	public function get_certificate($unique_code)
	{
		$a = $this->master_model->get_all_certificate($unique_code);
		$i = 1;
		foreach($a as $b)
		{
			echo '<tr>
				<td>'.$i.'</td>
				<td>'.$this->sma->fed($b->tgl).'</td>
				<td>'.$b->nama.'<br/>'.$b->gid.'</td>
				<td>'.$b->share_allotment.'</td>
				<td><a>'.$b->certificate_allotment.'</a></td>
			</tr>';
			$i++;
		}
	}
	
	public function unpaid_invoice ()
	{
        $bc = array(array('link' => '#', 'page' => lang('Unpaid Invoice')));
        $meta = array('page_title' => lang('Unpaid Invoice'), 'bc' => $bc, 'page_name' => 'Unpaid Invoice');
        $this->page_construct('client/unpaid.php', $meta, $this->data);
	}
	
	public function unreceived_doc ()
	{
        $bc = array(array('link' => '#', 'page' => lang('Unreceived Document')));
        $meta = array('page_title' => lang('Unreceived Document'), 'bc' => $bc, 'page_name' => 'Unreceived Document');
        $this->page_construct('client/unreceived.php', $meta, $this->data);
	}
	
	public function setting_filing ()
	{
        $bc = array(array('link' => '#', 'page' => lang('Filing')));
        $meta = array('page_title' => lang('Filing'), 'bc' => $bc, 'page_name' => 'Filing');
        $this->page_construct('client/setting_filing.php', $meta, $this->data);
	}
	
	public function modal_next ()
	{
		print_r($_POST);
        $bc = array(array('link' => '#', 'page' => lang('Confirm Changes Clients')));
        $meta = array('page_title' => lang('Confirm Changes  Clients'), 'bc' => $bc, 'page_name' => 'Confirm Changes  Clients');
        $this->page_construct('client/confirm_changes.php', $meta, $this->data);
	}

	public function get_director_appointment_date()
	{
		$client_officers_id = $_POST['client_officers_id'];

		$result = $this->db->query("select date_of_appointment from client_officers where id = '".$client_officers_id."'");

		$result = $result->result_array();

		echo json_encode($result);
	}

	public function get_director()
	{
		$company_code = $_POST['company_code'];
		$date_of_appointment = $_POST['date_of_appointment'];
		$alternate_of = isset($_POST['alternate_of']) ? $_POST['alternate_of'] : '';

		$result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '1' AND company_code='".$company_code."' AND (date_of_cessation = '' OR from_unixtime(UNIX_TIMESTAMP(STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y')),'%Y-%m-%d') >= CURDATE() ) AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = (SELECT MAX(STR_TO_DATE(T.date_of_appointment,'%d/%m/%Y')) from client_officers as T where T.officer_id = client_officers.officer_id AND T.field_type = client_officers.field_type AND  T.position = '1' AND T.company_code='".$company_code."') AND UNIX_TIMESTAMP(STR_TO_DATE('".$date_of_appointment."','%d/%m/%Y')) >= UNIX_TIMESTAMP(STR_TO_DATE(date_of_appointment,'%d/%m/%Y'))");

		$result = $result->result_array();
		$res = array();
		foreach($result as $row) {
			if($row['name'] != null)
			{
				$res[$row['id']] = $this->encryption->decrypt($row['name']);
			}
			else if ($row['company_name'] != null)
			{
				$res[$row['id']] = $this->encryption->decrypt($row['company_name']);
			}
		}
		
		if ($alternate_of != "")
		{
			$selected_director = $alternate_of;
		}
		else
		{
			$selected_director = null;
		}
       	
		$data = array('status'=>'success', 'tp'=>1, 'msg'=>"Director fetched successfully.", 'result'=>$res, 'selected_director'=>$selected_director);

	    echo json_encode($data);
	}

	public function register_get_director()
	{
		$company_code = $_POST['company_code'];
		$alternate_of = $_POST['alternate_of'];

		$result = $this->db->query("select client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type where position = '1' AND company_code='".$company_code."' AND (date_of_cessation = '' OR from_unixtime(UNIX_TIMESTAMP(STR_TO_DATE(client_officers.date_of_cessation,'%d/%m/%Y')),'%Y-%m-%d') >= CURDATE() ) AND STR_TO_DATE(client_officers.date_of_appointment,'%d/%m/%Y') = (SELECT MAX(STR_TO_DATE(T.date_of_appointment,'%d/%m/%Y')) from client_officers as T where T.officer_id = client_officers.officer_id AND T.field_type = client_officers.field_type AND  T.position = '1' AND T.company_code='".$company_code."')");

		$result = $result->result_array();
		$res = array();

		foreach($result as $row) {
			if($row['name'] != null)
			{
				$res[$row['id']] = $this->encryption->decrypt($row['name']);
			}
			else if ($row['company_name'] != null)
			{
				$res[$row['id']] = $this->encryption->decrypt($row['company_name']);
			}
			
		}
		
		if ($alternate_of != "")
		{
			$selected_director = $alternate_of;
		}
		else
		{
			$selected_director = null;
		}
       	
		$data = array('status'=>'success', 'tp'=>1, 'msg'=>"Director fetched successfully.", 'result'=>$res, 'selected_director'=>$selected_director);

	    echo json_encode($data);
	}


	public function get_client_officers_position()
	{
		$position = isset($_POST['position']) ? $_POST['position'] : '';

		$result = $this->db->query("select * from client_officers_position");
		$result = $result->result_array();

		if(!$result) {
			throw new exception("Client officers position not found.");
		}
		$res = array();
		foreach($result as $row) {
			$res[$row['id']] = $row['position'];
		}

		if ($position != "")
		{
			$selected_client_officers_position = $position;
		}
		else
		{
			$selected_client_officers_position = null;
		}
       	

		$data = array('status'=>'success', 'tp'=>1, 'msg'=>"Client officers position fetched successfully.", 'result'=>$res, 'selected_client_officers_position'=>$selected_client_officers_position);

	    echo json_encode($data);
	}

	public function get_financial_year_period()
	{
		$result = $this->db->query("select * from financial_year_period");

		$result = $result->result_array();

		if(!$result) {
			throw new exception("Financial Year Period not found.");
		}
		$res = array();
		foreach($result as $row) {
			$res[$row['id']] = $row['period'];
		}

		$data = array('status'=>'success', 'tp'=>1, 'msg'=>"Financial Year Period fetched successfully.", 'result'=>$res);

	    echo json_encode($data);
	}

	public function get_gst_filing_cycle()
	{
		$result = $this->db->query("select * from gst_filing_cycle");

		$result = $result->result_array();

		if(!$result) {
			throw new exception("GST Filing Cycle not found.");
		}
		$res = array();
		foreach($result as $row) {
			$res[$row['id']] = $row['gst_filing_cycle_name'];
		}
		
		$data = array('status'=>'success', 'tp'=>1, 'msg'=>"GST Filing Cycle fetched successfully.", 'result'=>$res);

	    echo json_encode($data);
	}

	public function get_sharetype()
	{
		$class = isset($_POST['class']) ? $_POST['class'] : '';

		$result = $this->db->query("select * from sharetype");
		$result = $result->result_array();

		if(!$result) {
			throw new exception("Class not found.");
		}
		$res = array();
		foreach($result as $row) {
			$res[$row['id']] = $row['sharetype'];
		}
		
		if ($class != "")
		{
			$selected_class = $class;
		}
		else
		{
			$selected_class = null;
		}
       	

		$data = array('status'=>'success', 'tp'=>1, 'msg'=>"Class fetched successfully.", 'result'=>$res, 'selected_class'=>$selected_class);

	    echo json_encode($data);
		
	}
	public function get_nationality()
	{
		$nationality = $_POST['nationality'];

		$result_nationality = $this->db->query("select * from nationality");
		$result = $result_nationality->result_array();

		if(!$result_nationality) {
			throw new exception("Nationality not found.");
		}
		$res = array();

		for($j = 0; $j < count($result); $j++)
		{
			$res[$result[$j]['id']] = $result[$j]['nationality'];
		}
		
		if ($nationality != "")
		{
			$selected_nationality = $nationality;
		}
		else
		{
			$selected_nationality = null;
		}

		$data = array('status'=>'success', 'tp'=>1, 'msg'=>"Nationality fetched successfully.", 'result'=>$res, 'selected_nationality'=>$selected_nationality);

	    echo json_encode($data);
	}

	public function get_department()
	{
		$department = isset($_POST['department'])?$_POST['department']:'';

		$result_department = $this->db->query("select * from department");

		$result = $result_department->result_array();

		if(!$result_department) {
			throw new exception("Department not found.");
		}
		$res = array();

		for($j = 0; $j < count($result); $j++)
		{
			$res[$result[$j]['id']] = $result[$j]['department_name'];
		}
		
		if ($department != "")
		{
			$selected_department = $department;
		}
		else
		{
			$selected_department = null;
		}
       	
		$data = array('status'=>'success', 'tp'=>1, 'msg'=>"Department fetched successfully.", 'result'=>$res, 'selected_department'=>$selected_department);

	    echo json_encode($data);
	}

	public function get_currency()
	{
		$currency = isset($_POST['currency']) ? $_POST['currency'] : '';
		$result_currency = $this->db->query("select * from currency order by currency");
		$result = $result_currency->result_array();

		if(!$result_currency) {
			throw new exception("Currency not found.");
		}
		$res = array();

		for($j = 0; $j < count($result); $j++)
		{
			$res[$result[$j]['id']] = $result[$j]['currency'];
		}
		
		if ($currency != "")
		{
			$selected_currency = $currency;
		}
		else
		{
			$selected_currency = null;
		}

		$data = array('status'=>'success', 'tp'=>1, 'msg'=>"Currency fetched successfully.", 'result'=>$res, 'selected_currency'=>$selected_currency);

	    echo json_encode($data);
	}

	public function get_guarantee_officer()
	{
		$officer_info_id_data = [];
		$officer_company_info_id_data = [];
		$client_info_id_data = [];
		$identification_register_no = strtoupper($_POST['identification_register_no']);

		if($identification_register_no != "")
		{
			$p = $this->db->query("select client.*, client.id as client_id from client"); // AND firm_id = '".$this->session->userdata("firm_id")."' // where registration_no='".$identification_register_no."'
			if ($p->num_rows() > 0)
	        {
				$client_info = $p->result_array();

				foreach ($client_info as $client_info_row) {
			        if(strtoupper($this->encryption->decrypt($client_info_row["registration_no"])) == $identification_register_no)
			        {
			        	$client_info_row["registration_no"] = $this->encryption->decrypt($client_info_row["registration_no"]);
			        	$client_info_row["company_name"] = $this->encryption->decrypt($client_info_row["company_name"]);
			            $client_info_id_data[] = $client_info_row;
			        }
			    }
			}

			$q = $this->db->query("select * from officer_company where user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");//register_no='".$identification_register_no."' AND 

			$r = $this->db->query("select officer.*, nationality.nationality as nationality_name from officer left join nationality on nationality.id = officer.nationality where YEAR(CURDATE()) - YEAR(date_of_birth) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(date_of_birth), '-', DAY(date_of_birth)) ,'%Y-%c-%e') > CURDATE(), 1, 0) > 18 AND user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");
			// AND identification_no='".$identification_register_no."'
	        if ($r->num_rows() > 0)
	        {
	            $officer_info = $r->result_array();

	            foreach ($officer_info as $officer_info_row) {
	                if(strtoupper($this->encryption->decrypt($officer_info_row["identification_no"])) == $identification_register_no)
	                {
	                	$officer_info_row["encrypt_identification_no"] = $officer_info_row["identification_no"];
	                	$officer_info_row["identification_no"] = $this->encryption->decrypt($officer_info_row["identification_no"]);
	                	$officer_info_row["name"] = $this->encryption->decrypt($officer_info_row["name"]);
	                	
	                    $officer_info_id_data[] = $officer_info_row;
	                }
	            }
	        }

	        if ($q->num_rows() > 0) 
	        {
	            $officer_company_info = $q->result_array();

	            foreach ($officer_company_info as $officer_company_info_row) {
	                if(strtoupper($this->encryption->decrypt($officer_company_info_row["register_no"])) == $identification_register_no)
	                {
	                	$officer_company_info_row["encrypt_register_no"] = $officer_company_info_row["register_no"];
	                	$officer_company_info_row["register_no"] = $this->encryption->decrypt($officer_company_info_row["register_no"]);
	                	$officer_company_info_row["company_name"] = $this->encryption->decrypt($officer_company_info_row["company_name"]);
	                	
	                    $officer_company_info_id_data[] = $officer_company_info_row;
	                }
	            }
	        }
	        
	        $all = array_merge($officer_info_id_data, $officer_company_info_id_data, $client_info_id_data);
			if (count($all) > 0) {
	            echo json_encode($all[0]);
	        }
	        else echo 0;
	    }
	    else
	    {
	    	echo 0;
	    }
	}

	public function get_officer()
	{
		$officer_id = $_POST['officer_id'];
		$identification_register_no = strtoupper($_POST['identification_register_no']);
		$position = $_POST['position'];
		$company_code = $_POST['company_code'];

		if ($position == "5")
		{
			$q = $this->db->query("select * from officer_company where user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."' ");//register_no='".$identification_register_no."' AND 

			if ($q->num_rows() > 0) {

				$officer_company_info = $q->result_array();

                foreach ($officer_company_info as $officer_company_info_row) {
                    if($this->encryption->decrypt($officer_company_info_row["register_no"]) == $identification_register_no)
                    {
                    	$officer_company_info_row["company_name"] = $this->encryption->decrypt($officer_company_info_row["company_name"]);
                        $officer_company_info_data = $officer_company_info_row;
                    }
                }

                if($officer_company_info_data != null)
		        {
		        	$chk_member = $this->db->query("select * from member_shares where officer_id='".$officer_company_info_data["id"]."' AND field_type = '".$officer_company_info_data["field_type"]."' AND company_code = '".$company_code."'");

					if ($chk_member->num_rows() > 0) {
						echo json_encode(array("status" => 5, "message" => "This person is a member for this company.", "title" => "Error"));
					}
					else
					{
						echo json_encode(array("status" => 1, "info" => $officer_company_info_data));//$q->result()[0]
					}
		        }
		        else 
		        {
		        	$q = $this->db->query("select * from officer where YEAR(CURDATE()) - YEAR(date_of_birth) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(date_of_birth), '-', DAY(date_of_birth)) ,'%Y-%c-%e') > CURDATE(), 1, 0) > 18 AND user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");//AND identification_no='".$identification_register_no."' 
					
					if ($q->num_rows() > 0) {
						$officer_info = $q->result_array();

	                    foreach ($officer_info as $officer_info_row) 
	                    {
	                        if($this->encryption->decrypt($officer_info_row["identification_no"]) == $identification_register_no)
	                        {
	                            $officer_info_data = $officer_info_row;
	                        }
	                    }
	                    if($officer_info_data != null)
		                {
							echo json_encode(array("status" => 4, "message" => "This person should be a company.", "title" => "Error"));
						}
						else
						{
							echo json_encode(array("status" => 3));
						}
			        } 
			        else echo json_encode(array("status" => 3));
		        }
	        } 
	        else 
	        {
	        	$q = $this->db->query("select * from officer where YEAR(CURDATE()) - YEAR(date_of_birth) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(date_of_birth), '-', DAY(date_of_birth)) ,'%Y-%c-%e') > CURDATE(), 1, 0) > 18 AND user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");//AND identification_no='".$identification_register_no."' 
					
				if ($q->num_rows() > 0) {
					$officer_info = $q->result_array();

                    foreach ($officer_info as $officer_info_row) 
                    {
                        if($this->encryption->decrypt($officer_info_row["identification_no"]) == $identification_register_no)
                        {
                            $officer_info_data = $officer_info_row;
                        }
                    }
                    if($officer_info_data != null)
	                {
						echo json_encode(array("status" => 4, "message" => "This person should be a company.", "title" => "Error"));
					}
					else
					{
						echo json_encode(array("status" => 3));
					}
		        } 
		        else echo json_encode(array("status" => 3));
	        }
		}
		else
		{
			if($officer_id == null)
			{
				$q = $this->db->query("select * from officer where YEAR(CURDATE()) - YEAR(date_of_birth) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(date_of_birth), '-', DAY(date_of_birth)) ,'%Y-%c-%e') > CURDATE(), 1, 0) > 18 AND user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");
				// AND identification_no='".$identification_register_no."'

				if ($q->num_rows() > 0) 
				{
					$officer_info = $q->result_array();

                    foreach ($officer_info as $officer_info_row) 
                    {
                        if($this->encryption->decrypt($officer_info_row["identification_no"]) == $identification_register_no)
                        {
                        	$officer_info_row["name"] = $this->encryption->decrypt($officer_info_row["name"]);
                            $officer_info_data = $officer_info_row;
                        }
                    }

                    if($officer_info_data != null)
                    {
                    	echo json_encode(array("status" => 1, "info" => $officer_info_data));//$q->result()[0]
                    }
                    else
                    {
                    	$q = $this->db->query("select * from officer_company where user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");// register_no='".$identification_register_no."' AND

						if ($q->num_rows() > 0) {
							$officer_company_info = $q->result_array();

		                    foreach ($officer_company_info as $officer_company_info_row) {
		                        if($this->encryption->decrypt($officer_company_info_row["register_no"]) == $identification_register_no)
		                        {
		                            $officer_company_info_data = $officer_company_info_row;
		                        }
		                    }
		                    if($officer_company_info_data != null)
		                    {
								echo json_encode(array("status" => 4, "message" => "This person should be an individual.", "title" => "Error"));
		                    }
		                    else
		                    { 
		                    	echo json_encode(array("status" => 3));
		                    }
				        } 
				        else echo json_encode(array("status" => 3));
                    }
		        } 
		        else
                {
                	$q = $this->db->query("select * from officer_company where user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");// register_no='".$identification_register_no."' AND

					if ($q->num_rows() > 0) {
						$officer_company_info = $q->result_array();

	                    foreach ($officer_company_info as $officer_company_info_row) {
	                        if($this->encryption->decrypt($officer_company_info_row["register_no"]) == $identification_register_no)
	                        {
	                            $officer_company_info_data = $officer_company_info_row;
	                        }
	                    }
	                    if($officer_company_info_data != null)
	                    {
							echo json_encode(array("status" => 4, "message" => "This person should be an individual.", "title" => "Error"));
	                    }
	                    else
	                    { 
	                    	echo json_encode(array("status" => 3));
	                    }
			        } 
			        else echo json_encode(array("status" => 3));
                }
			}
			else
			{
				$result = $this->db->query("select * from client_officers where id = '".$officer_id."'");

				$result = $result->result_array();

				$q = $this->db->query("select * from officer where YEAR(CURDATE()) - YEAR(date_of_birth) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(date_of_birth), '-', DAY(date_of_birth)) ,'%Y-%c-%e') > CURDATE(), 1, 0) > 18 AND user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");// AND identification_no='".$identification_register_no."'

				if ($q->num_rows() > 0) 
				{
					$officer_info = $q->result_array();

                    foreach ($officer_info as $officer_info_row) 
                    {
                        if($this->encryption->decrypt($officer_info_row["identification_no"]) == $identification_register_no)
                        {
                        	$officer_info_row["name"] = $this->encryption->decrypt($officer_info_row["name"]);
                            $officer_info_data = $officer_info_row;
                        }
                    }

                    if($officer_info_data != null)
                    {
                    	if($result[0]["officer_id"] == $officer_info_data["id"])
						{
							echo json_encode(array("status" => 2, "message" => "He/She can not be the alternate for his/her own.", "title" => "Error"));
						}
						else
						{
							echo json_encode(array("status" => 1, "info" => $officer_info_data));
						}
                    }
                    else 
			        {
			        	$q = $this->db->query("select * from officer_company where user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");// register_no='".$identification_register_no."' AND

			        	if ($q->num_rows() > 0) {
							$officer_company_info = $q->result_array();

		                    foreach ($officer_company_info as $officer_company_info_row) {
		                        if($this->encryption->decrypt($officer_company_info_row["register_no"]) == $identification_register_no)
		                        {
		                            $officer_company_info_data = $officer_company_info_row;
		                        }
		                    }
		                    if($officer_company_info_data != null)
		                    {
								echo json_encode(array("status" => 4, "message" => "This person should be an individual.", "title" => "Error"));
		                    }
		                    else
		                    { 
		                    	echo json_encode(array("status" => 3));
		                    }
				        } 
				        else echo json_encode(array("status" => 3));
			        }
                }
                else 
		        {
		        	$q = $this->db->query("select * from officer_company where user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'");// register_no='".$identification_register_no."' AND

		        	if ($q->num_rows() > 0) {
						$officer_company_info = $q->result_array();

	                    foreach ($officer_company_info as $officer_company_info_row) {
	                        if($this->encryption->decrypt($officer_company_info_row["register_no"]) == $identification_register_no)
	                        {
	                            $officer_company_info_data = $officer_company_info_row;
	                        }
	                    }
	                    if($officer_company_info_data != null)
	                    {
							echo json_encode(array("status" => 4, "message" => "This person should be an individual.", "title" => "Error"));
	                    }
	                    else
	                    { 
	                    	echo json_encode(array("status" => 3));
	                    }
			        } 
			        else echo json_encode(array("status" => 3));
		        }
			}
		}
	}

	public function get_person()
	{
		$identification_register_no = strtoupper($_POST['identification_register_no']);
		$company_code = $_POST['company_code'];

		$query = "(select id, field_type, identification_no, name from officer where YEAR(CURDATE()) - YEAR(date_of_birth) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(date_of_birth), '-', DAY(date_of_birth)) ,'%Y-%c-%e') > CURDATE(), 1, 0) > 18 AND user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."') 
				UNION
		           	(select id, 'client' AS field_type, registration_no, company_name from client)
		        UNION
		           (select officer_company.id, officer_company.field_type, officer_company.register_no, officer_company.company_name from officer_company where user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."')";

		$q = $this->db->query($query);

		if ($q->num_rows() > 0) {
			if($identification_register_no != "")
			{
				$y = $q->result_array();

	            foreach ($y as $officer_info_row) 
	            {
	                if(strtoupper($this->encryption->decrypt($officer_info_row["identification_no"])) == $identification_register_no)
	                {
	                	$officer_info_row["identification_no"] = $this->encryption->decrypt($officer_info_row["identification_no"]);
	                	$officer_info_row["name"] = $this->encryption->decrypt($officer_info_row["name"]);
	                    $officer_info_data = $officer_info_row;
	                }
	                elseif(strtoupper($this->encryption->decrypt($officer_info_row["register_no"])) == $identification_register_no)
	                {
	                	$officer_info_row["register_no"] = $this->encryption->decrypt($officer_info_row["register_no"]);
	                	$officer_info_row["company_name"] = $this->encryption->decrypt($officer_info_row["company_name"]);
	                    $officer_info_data = $officer_info_row;
	                }
	                elseif(strtoupper($this->encryption->decrypt($officer_info_row["registration_no"])) == $identification_register_no)
	                {
	                	$officer_info_row["registration_no"] = $this->encryption->decrypt($officer_info_row["registration_no"]);
	                	$officer_info_row["company_name"] = $this->encryption->decrypt($officer_info_row["company_name"]);
	                    $officer_info_data = $officer_info_row;
	                }
	            }

				if($y[0]["field_type"] == "company")
				{
					$t = $this->db->query("select * from client_officers where officer_id = '".$y[0]["id"]."' AND field_type = '".$y[0]["field_type"]."' AND company_code = '".$company_code."'");

					if ($t->num_rows() > 0) 
					{
						echo json_encode(array("status" => 2));
					}
					else
					{
						echo json_encode(array("status" => 1, "info" => $officer_info_data));
					}

				}
				else
				{
					echo json_encode(array("status" => 1, "info" => $officer_info_data));
				}
			}
			else
			{
				echo json_encode(array("status" => 2));
			}
        } else echo json_encode(array("status" => 1, "info" => $officer_info_data)); //$q->result()[0]
	}

	public function check_first_due_date_175()
	{
		$company_code = $_POST["company_code"];
		$filing_id = $_POST["filing_id"];

		$query = "select * from filing where company_code = '".$company_code."' and id < '".$filing_id."' ORDER BY id DESC LIMIT 2";

		$query = $this->db->query($query);

		if ($query->num_rows() > 0) {

			$previous_agm = $query->result()[0]->agm;

			if($previous_agm != null && $previous_agm != 'dispensed')
			{
				$previous_agm = date("Y-m-d", strtotime($previous_agm));
				echo json_encode(array("way" => 2, "date" => $previous_agm, "date_after_fifteen_month" => $this->MonthShifter(new DateTime($previous_agm),15)->format(('Y-m-d'))));
			}
			elseif($previous_agm == 'dispensed')
			{
				echo json_encode(array("way" => 4, "date" => "Not Applicable"));
			}
			else
			{
				$q = "select * from client where company_code = '".$company_code."'";
	        	$q = $this->db->query($q);

	        	if ($q->num_rows() > 0) {

	        		$array = explode('/',$q->result()[0]->incorporation_date);
					$tmp = $array[0];
					$array[0] = $array[1];
					$array[1] = $tmp;
					unset($tmp);
					$date_2 = implode('/', $array);
					$time = strtotime($date_2);
					$newformat = date('Y-m-d',$time);

					echo json_encode(array("way" => 3, "date" => $this->MonthShifter(new DateTime($newformat),15)->format(('Y-m-d'))));
		        } else echo 0;
			}


        } 
        else
        {
        	$q = "select * from client where company_code = '".$company_code."'";
        	$q = $this->db->query($q);

        	if ($q->num_rows() > 0) {

        		$array = explode('/',$q->result()[0]->incorporation_date);
				$tmp = $array[0];
				$array[0] = $array[1];
				$array[1] = $tmp;
				unset($tmp);
				$date_2 = implode('/', $array);
				$time = strtotime($date_2);
				$newformat = date('Y-m-d',$time);

				echo json_encode(array("way" => 1, "date" => $this->MonthShifter(new DateTime($newformat),15)->format(('Y-m-d'))));
	        } else echo 0;
        }

	}

	public function delete_client ()
	{
		$id = $_POST["client_id"];

		$get_client_data = $this->db->query("select * from client where id='".$id."'");

		$get_client_data = $get_client_data->result_array();

		$company_code = $get_client_data[0]["company_code"];

  //       $this->db->delete("client_officers",array('company_code'=>$company_code));

  //       $this->db->delete("client_charges",array('company_code'=>$company_code));

  //       $this->db->delete("client_member_share_capital",array('company_code'=>$company_code));

  //       $this->db->delete("member_shares",array('company_code'=>$company_code));

  //       $this->db->delete("certificate",array('company_code'=>$company_code));

  //       $this->db->delete("certificate_merge",array('company_code'=>$company_code));

  //       $this->db->delete("filing",array('company_code'=>$company_code));

		// $this->db->delete("client_signing_info",array('company_code'=>$company_code));

		// $this->db->delete("client_contact_info",array('company_code'=>$company_code));

		// $this->db->delete("client_billing_info",array('company_code'=>$company_code));

		// $this->db->delete("record_billing_recurring",array('company_code'=>$company_code));

		$this->db->set("deleted", 1);
		$this->db->where('company_code', $company_code);
		$this->db->update("client");

		$this->save_audit_trail("Clients", "Index", "is deleted.", $get_client_data[0]["company_code"]);

		$this->recalculate();
    	echo json_encode(array('message' => 'Information Updated', 'title' => 'Updated'));
	}


	public function check_filing_data()
	{
		$check_year_end = [];

		$check_year_end[0]['year_end'] = $_POST['year_end'];

		$query = $this->db->get_where("history_filing", array("company_code" => $_POST['company_code'], "id" => $_POST['filing_id']));

		if (!$query->num_rows())//if don't have anythings
		{
			echo false;
		}
		else
		{
			$query = $query->result_array();

			$old_filing_result = $this->db->query("select year_end from filing where id='".$_POST['filing_id']."' AND company_code = '".$_POST['company_code']."'");

			$old_filing_result = $old_filing_result->result_array();

			$get_client_info = $this->db->query("select * from client where company_code='".$_POST['company_code']."'");

			$get_client_info = $get_client_info->result_array();

			if(!($old_filing_result == $check_year_end))
			{
				$pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $get_client_info[0]["id"], "filing_id" => $_POST['filing_id'], "received_on" => "", "triggered_by" => "19"));
				if($pending_documents_query->num_rows())
				{
					echo true;
				}
				else
				{
					echo false;
				}
			}
			else
			{
				echo false;
			}
		}
	}
	public function check_latest_fye_for_tax()
	{
		$company_code = $_POST["company_code"];

		$latest_fye = $this->db->query('select * FROM filing WHERE company_code = "'.$company_code.'" ORDER BY ID DESC LIMIT 1');

		$latest_fye = $latest_fye->result_array();

		$latest_year_end = new DateTime($latest_fye[0]['year_end']);

		if($latest_fye[0]['year_end'] != null)
		{
			// We extract the day of the month as $start_day
		    $latest_year_end = $this->MonthShifter($latest_year_end,12)->format(('Y-m-d'));

		    $array = explode('-',$latest_year_end);
			$year = $array[0];
			$month = $array[1];
			$day = $array[2];

			$tax_filing_period = "YA".$year;
		    $tax_filing_due_date = date('d F Y', strtotime((int)$year.'-11-30'));
		}
		else
		{
			$tax_filing_period = null;
			$tax_filing_due_date = null;
		}

		echo json_encode(array('latest_fye' => $latest_fye[0]['year_end'], 'tax_filing_period' => $tax_filing_period, 'tax_filing_due_date' => $tax_filing_due_date));
	}

	public function check_latest_fye_for_gst()
	{
		$company_code = $_POST["company_code"];

		$latest_fye = $this->db->query('select * FROM filing WHERE company_code = "'.$company_code.'" ORDER BY ID DESC LIMIT 1');

		$latest_fye = $latest_fye->result_array();

		echo json_encode(array('latest_fye' => $latest_fye[0]['year_end']));
	}
	public function check_latest_fye()
	{
		$company_code = $_POST["company_code"];

		$latest_fye = $this->db->query('select * FROM filing WHERE company_code = "'.$company_code.'" ORDER BY ID DESC LIMIT 1');

		$latest_fye = $latest_fye->result_array();

		$next_eci_filing_due_date = new DateTime($latest_fye[0]['year_end']);

		if($latest_fye[0]['year_end'] != null)
		{
			// We extract the day of the month as $start_day
		    $next_eci_filing_due_date = $this->MonthShifter($next_eci_filing_due_date,3)->format(('Y-m-d'));

		    $array = explode('-',$next_eci_filing_due_date);
			$year = $array[0];
			$month = $array[1];
			$day = $array[2];

		    $new_format_for_next_eci_due_date = date('d F Y', strtotime((int)$year.'-'.(int)$month.'-26'));
		}
		else
		{
			$new_format_for_next_eci_due_date = null;
		}

		echo json_encode(array('latest_fye' => $latest_fye[0]['year_end'], 'next_eci_filing_due_date' => $new_format_for_next_eci_due_date));
	}

	public function get_tax_period_due_date()
	{
		$company_code = $_POST["company_code"];
		$coporate_tax_period = $_POST["coporate_tax_period"];

		$coporate_tax_period = new DateTime($coporate_tax_period);
		// We extract the day of the month as $start_day
	    $coporate_tax_period = $this->MonthShifter($coporate_tax_period,12)->format(('Y-m-d'));

	    $array = explode('-',$coporate_tax_period);
		$year = $array[0];
		$month = $array[1];
		$day = $array[2];

	    $tax_filing_period = "YA".$year;
		$tax_filing_due_date = date('d F Y', strtotime((int)$year.'-11-30'));

		echo json_encode(array('tax_filing_period' => $tax_filing_period, 'tax_filing_due_date' => $tax_filing_due_date));
	}

	public function get_next_eci_filing_due_date()
	{
		$company_code = $_POST["company_code"];
		$eci_tax_period = $_POST["eci_tax_period"];

		$next_eci_filing_due_date = new DateTime($eci_tax_period);
		// We extract the day of the month as $start_day
	    $next_eci_filing_due_date = $this->MonthShifter($next_eci_filing_due_date,3)->format(('Y-m-d'));

	    $array = explode('-',$next_eci_filing_due_date);
		$year = $array[0];
		$month = $array[1];
		$day = $array[2];

	    $new_format_for_next_eci_due_date = date('d F Y', strtotime((int)$year.'-'.(int)$month.'-26'));

		echo json_encode(array('next_eci_filing_due_date' => $new_format_for_next_eci_due_date));
	}

	public function add_gst_filing_info()
	{
		if(isset($_POST["gst_year_end"]))
		{
			$this->form_validation->set_rules('gst_year_end', 'GST Year End', 'required');
		
			if ($this->form_validation->run() == FALSE)
	        {
	        	$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

	        	$error = array(
					'gst_year_end' => strip_tags(form_error('gst_year_end')),
	            );

	            echo json_encode(array("Status" => 0, 'message' => 'Please complete all required field', 'title' => 'Error', "error" => $error));
	        }
	        else
	        {
	        	$this->save_gst_filing_info($_POST);
	        }
	    }
	    else
	    {
	    	$this->form_validation->set_rules('gst_de_registration_date', 'De Registration Date', 'required');
		
			if ($this->form_validation->run() == FALSE)
	        {
	        	$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

	        	$error = array(
					'gst_de_registration_date' => strip_tags(form_error('gst_de_registration_date')),
	            );

	            echo json_encode(array("Status" => 0, 'message' => 'Please complete all required field', 'title' => 'Error', "error" => $error));
	        }
	        else
	        {
	        	$this->save_gst_filing_info($_POST);
	        }

	    }
	}

	public function save_gst_filing_info($data)
	{
		$gst_filing['company_code'] = $data['company_code'];
		$gst_filing['gst_year_end'] = $data['gst_year_end'];
		$gst_filing['gst_filing_cycle'] = $data['gst_filing_cycle'];
		$gst_filing['gst_filing_period1'] = $data['gst_filing_period1'];
		$gst_filing['gst_filing_period2'] = $data['gst_filing_period2'];
		$gst_filing['gst_filing_due_date'] = $data['gst_filing_due_date'];
		$gst_filing['gst_filing_date'] = $data['gst_filing_date'];
		$gst_filing['gst_de_registration_date'] = $data['gst_de_registration_date'];

		$q = $this->db->get_where("gst_filing", array("company_code" => $data['company_code'], "id" => $data['gst_id']));

		if (!$q->num_rows())
		{	
			$this->db->insert("gst_filing",$gst_filing);
			$this->save_audit_trail("Clients", "GST", "GST year end ".$data['gst_year_end']." is added.", $data['company_code']);
		}
		else 
		{	

			$this->db->where(array("company_code" => $data['company_code'], "id" => $data['gst_id']));
			$this->db->update("gst_filing",$gst_filing);
			$this->save_audit_trail("Clients", "GST", "GST year end ".$data['gst_year_end']." is edited.", $data['company_code']);
		}

		$latest_gst_filing_id = $this->db->query("select * from gst_filing where company_code='".$data['company_code']."' ORDER BY id DESC LIMIT 2");

		if($data['gst_filing_date'] != null)
		{
			$new_gst_filing['company_code'] = $data['company_code'];
			$new_gst_filing['gst_year_end'] = $data['gst_filing_period2'];
		    $new_gst_filing['gst_filing_cycle'] = $data['gst_filing_cycle'];
		    
		    if($data['gst_filing_cycle'] == 1)
			{
				$new_gst_filing['gst_filing_period1'] = date('d F Y', strtotime('+1 days', strtotime($data['gst_filing_period2'])));
			    $new_gst_filing['gst_filing_period2'] = $this->check_date($data['gst_filing_period2'], 3)->format(('d F Y'));
			    $new_gst_filing['gst_filing_due_date'] = $this->check_date($data['gst_filing_period2'], 4)->format(('d F Y'));
			}
			elseif($data['gst_filing_cycle'] == 2)
			{
				$new_gst_filing['gst_filing_period1'] = date('d F Y', strtotime('+1 days', strtotime($data['gst_filing_period2'])));
				$new_gst_filing['gst_filing_period2'] = $this->check_date($data['gst_filing_period2'], 12)->format(('d F Y'));
			    $new_gst_filing['gst_filing_due_date'] = $this->check_date($data['gst_filing_period2'], 13)->format(('d F Y'));
			}
			elseif($data['gst_filing_cycle'] == 3)
			{
				$new_gst_filing['gst_filing_period1'] = date('d F Y', strtotime('+1 days', strtotime($data['gst_filing_period2'])));
			    $new_gst_filing['gst_filing_period2'] = $this->check_date($data['gst_filing_period2'], 6)->format(('d F Y'));
			    $new_gst_filing['gst_filing_due_date'] = $this->check_date($data['gst_filing_period2'], 7)->format(('d F Y'));
			}

		    $new_gst_filing['gst_filing_date'] = "";
		    $new_gst_filing['gst_de_registration_date'] = "";

			if($latest_gst_filing_id->result()[0]->id != $data['gst_id'] && $data['gst_id'] != "")
			{
				// $this->db->where(array("id" => $latest_gst_filing_id->result()[0]->id));
				// $this->db->update("gst_filing",$new_gst_filing);
			}
			else
			{
				$this->db->insert("gst_filing",$new_gst_filing);
			}
		}
		elseif ($data['gst_de_registration_date'] != null)
		{
			$new_gst_filing['company_code'] = $data['company_code'];
			$new_gst_filing['gst_year_end'] = date('d F Y', strtotime('+1 days', strtotime($data['gst_de_registration_date'])));
		    $new_gst_filing['gst_filing_cycle'] = (($latest_gst_filing_id->result()[1]->gst_filing_cycle != null)?$latest_gst_filing_id->result()[1]->gst_filing_cycle:0);
		    if($latest_gst_filing_id->result()[1]->gst_filing_cycle == 1)
			{
				$new_gst_filing['gst_filing_period1'] = date('d F Y', strtotime('+1 days', strtotime($new_gst_filing['gst_year_end'])));
			    $new_gst_filing['gst_filing_period2'] = $this->check_date($new_gst_filing['gst_year_end'], 3)->format(('d F Y'));
			    $new_gst_filing['gst_filing_due_date'] = $this->check_date($new_gst_filing['gst_year_end'], 4)->format(('d F Y'));
			}
			elseif($latest_gst_filing_id->result()[1]->gst_filing_cycle == 2)
			{
				$new_gst_filing['gst_filing_period1'] = date('d F Y', strtotime('+1 days', strtotime($new_gst_filing['gst_year_end'])));
				$new_gst_filing['gst_filing_period2'] = $this->check_date($new_gst_filing['gst_year_end'], 12)->format(('d F Y'));
			    $new_gst_filing['gst_filing_due_date'] = $this->check_date($new_gst_filing['gst_year_end'], 13)->format(('d F Y'));
			}
			elseif($latest_gst_filing_id->result()[1]->gst_filing_cycle == 3)
			{
				$new_gst_filing['gst_filing_period1'] = date('d F Y', strtotime('+1 days', strtotime($new_gst_filing['gst_year_end'])));
			    $new_gst_filing['gst_filing_period2'] = $this->check_date($new_gst_filing['gst_year_end'], 6)->format(('d F Y'));
			    $new_gst_filing['gst_filing_due_date'] = $this->check_date($new_gst_filing['gst_year_end'], 7)->format(('d F Y'));
			}
		    $new_gst_filing['gst_filing_date'] = "";
		    $new_gst_filing['gst_de_registration_date'] = "";

			$this->db->insert("gst_filing",$new_gst_filing);
		}

		$this->data['gst_filing_data'] = $this->master_model->get_all_gst_filing_data($data['company_code']);

    	echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "gst_filing_data" => $this->data));
	}

	public function add_tax_filing_info()
	{
		$this->form_validation->set_rules('coporate_tax_period', 'Coporate Tax Period', 'required');

		if ($this->form_validation->run() == FALSE)
        {
        	$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

        	$error = array(
				'coporate_tax_period' => strip_tags(form_error('coporate_tax_period')),
            );

            echo json_encode(array("Status" => 0, 'message' => 'Please complete all required field', 'title' => 'Error', "error" => $error));
        }
        else
        {
        	$tax_filing['company_code'] = $_POST['company_code'];
			$tax_filing['coporate_tax_period'] = $_POST['coporate_tax_period'];
			$tax_filing['tax_filing_period'] = $_POST['tax_filing_period'];
			$tax_filing['filing_date'] = $_POST['tax_filing_date'];
			$tax_filing['tax_filing_due_date'] = $_POST['tax_filing_due_date'];

			$q = $this->db->get_where("tax_filing", array("company_code" => $_POST['company_code'], "id" => $_POST['tax_id']));

			if (!$q->num_rows())
			{	
				$this->db->insert("tax_filing",$tax_filing);
				$this->save_audit_trail("Clients", "Corporate Tax", "Corporate tax period ".$_POST['coporate_tax_period']." is added.", $_POST['company_code']);
			} 
			else 
			{	

				$this->db->where(array("company_code" => $_POST['company_code'], "id" => $_POST['tax_id']));
				$this->db->update("tax_filing",$tax_filing);
				$this->save_audit_trail("Clients", "Corporate Tax", "Corporate tax period ".$_POST['coporate_tax_period']." is edited.", $_POST['company_code']);
			}

			$latest_tax_filing_id = $this->db->query("select * from tax_filing where company_code='".$_POST['company_code']."' ORDER BY id DESC LIMIT 1");

			if($_POST['tax_filing_date'] != null)
			{
				$new_tax_filing['company_code'] = $_POST['company_code'];
				$new_tax_filing['coporate_tax_period'] = date('d F Y', strtotime('+1 year', strtotime($_POST['coporate_tax_period'])));
				$new_tax_filing['filing_date'] = "";

				$coporate_tax_period = new DateTime($new_tax_filing['coporate_tax_period']);
				// We extract the day of the month as $start_day
			    $coporate_tax_period = $this->MonthShifter($coporate_tax_period,12)->format(('Y-m-d'));

			    $array = explode('-',$coporate_tax_period);
				$year = $array[0];
				$month = $array[1];
				$day = $array[2];

			    $new_tax_filing['tax_filing_period'] = "YA".$year;
				$new_tax_filing['tax_filing_due_date'] = date('d F Y', strtotime((int)$year.'-11-30'));

				if($latest_tax_filing_id->result()[0]->id != $_POST['tax_id'] && $_POST['tax_id'] != "")
				{
					$this->db->where(array("id" => $latest_tax_filing_id->result()[0]->id));
					$this->db->update("tax_filing",$new_tax_filing);
				}
				else
				{
					$this->db->insert("tax_filing",$new_tax_filing);
				}
			}

			$this->data['tax_filing_data'] = $this->master_model->get_all_tax_filing_data($_POST['company_code']);

        	echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "tax_filing_data" => $this->data));
        }
	}

	public function add_eci_filing_info()
	{
		$this->form_validation->set_rules('eci_tax_period', 'ECI Tax Period', 'required');

		if ($this->form_validation->run() == FALSE)
        {
        	$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

        	$error = array(
				'eci_tax_period' => strip_tags(form_error('eci_tax_period')),
            );

            echo json_encode(array("Status" => 0, 'message' => 'Please complete all required field', 'title' => 'Error', "error" => $error));
        }
        else
        {
        	$eci_filing['company_code'] = $_POST['company_code'];
			$eci_filing['eci_tax_period'] = $_POST['eci_tax_period'];
			$eci_filing['eci_filing_date'] = $_POST['eci_filing_date'];
			$eci_filing['next_eci_filing_due_date'] = $_POST['next_eci_filing_due_date'];

			$q = $this->db->get_where("eci_filing", array("company_code" => $_POST['company_code'], "id" => $_POST['eci_id']));

			if (!$q->num_rows())
			{	
				$this->db->insert("eci_filing",$eci_filing);
				$this->save_audit_trail("Clients", "Estimated Chargeable Income (ECI)", "ECI tax period ".$_POST['eci_tax_period']." is added.", $_POST['company_code']);
			} 
			else 
			{	

				$this->db->where(array("company_code" => $_POST['company_code'], "id" => $_POST['eci_id']));
				$this->db->update("eci_filing",$eci_filing);
				$this->save_audit_trail("Clients", "Estimated Chargeable Income (ECI)", "ECI tax period ".$_POST['eci_tax_period']." is edited.", $_POST['company_code']);
			}

			$latest_eci_filing_id = $this->db->query("select * from eci_filing where company_code='".$_POST['company_code']."' ORDER BY id DESC LIMIT 1");

			if($_POST['eci_filing_date'] != null)
			{
				$new_eci_filing['company_code'] = $_POST['company_code'];
				$new_eci_filing['eci_tax_period'] = date('d F Y', strtotime('+1 year', strtotime($_POST['eci_tax_period'])));
				$new_eci_filing['eci_filing_date'] = "";

				$next_eci_filing_due_date = new DateTime($new_eci_filing['eci_tax_period']);
				// We extract the day of the month as $start_day
			    $next_eci_filing_due_date = $this->MonthShifter($next_eci_filing_due_date,3)->format(('Y-m-d'));

			    $array = explode('-',$next_eci_filing_due_date);
				$year = $array[0];
				$month = $array[1];
				$day = $array[2];

			    $new_eci_filing['next_eci_filing_due_date'] = date('d F Y', strtotime((int)$year.'-'.(int)$month.'-26'));

				if($latest_eci_filing_id->result()[0]->id != $_POST['eci_id'] && $_POST['eci_id'] != "")
				{
					$this->db->where(array("id" => $latest_eci_filing_id->result()[0]->id));
					$this->db->update("eci_filing",$new_eci_filing);
				}
				else
				{
					$this->db->insert("eci_filing",$new_eci_filing);
				}
			}

			$this->data['eci_filing_data'] = $this->master_model->get_all_eci_filing_data($_POST['company_code']);

        	echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "eci_filing_data" => $this->data));
        }
	}


	public function add_filing_info()
	{
		$this->form_validation->set_rules('year_end', 'Year End', 'required');

		if ($this->form_validation->run() == FALSE)
        {
        	$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

        	$error = array(
				'year_end' => strip_tags(form_error('year_end')),
            );

            echo json_encode(array("Status" => 0, 'message' => 'Please complete all required field', 'title' => 'Error', "error" => $error));
        }
        else
        {
        	$filing['company_code'] = $_POST['company_code'];
			$filing['year_end'] = $_POST['year_end'];
			$filing['agm'] = $_POST['agm'];
			$filing['ar_filing_date'] = $_POST['ar_filing_date'];
			$filing['financial_year_period_id'] = $_POST['financial_year_period'];
			$filing['financial_year_period1'] = $_POST['filing_financial_year_period1'];
			$filing['financial_year_period2'] = $_POST['filing_financial_year_period2'];
			$filing['due_date_175'] = $_POST['due_date_175'];
			$filing['175_extended_to'] = $_POST['extended_to_175'];
			$filing['due_date_201'] = $_POST['due_date_201'];
			$filing["201_extended_to"] = $_POST['extended_to_201'];
			$filing['due_date_197'] = $_POST['due_date_197'];
			$filing["197_extended_to"] = $_POST['extended_to_197'];

			$q = $this->db->get_where("filing", array("company_code" => $_POST['company_code'], "id" => $_POST['filing_id']));

			if (!$q->num_rows())
			{	
				if($_POST["agm"] != "" && $_POST['agm'] != "dispensed")
				{
					//$this->create_document("agm_held", $_POST['company_code'], null, null, null, null);
				}

				if($_POST["agm"] != "" && $_POST['agm'] == "dispensed")
				{
					//$this->create_document("dispense_agm", $_POST['company_code'], null, null, null, null);
				}			

				$this->db->insert("filing",$filing);
				$this->save_audit_trail("Clients", "AGM and Annual Return", "Year end ".$_POST['year_end']." is added.", $_POST['company_code']);
			} 
			else 
			{	
				$check_year_end = [];
				$check_agm = [];

				$check_year_end[0]['year_end'] = $_POST['year_end'];
				$check_agm[0]['agm'] = $_POST['agm'];

				$old_filing_result = $this->db->query("select year_end from filing where id='".$_POST['filing_id']."' AND company_code = '".$_POST['company_code']."'");

				$old_filing_result = $old_filing_result->result_array();

				$old_agm_result = $this->db->query("select agm from filing where id='".$_POST['filing_id']."' AND company_code = '".$_POST['company_code']."'");

				$old_agm_result = $old_agm_result->result_array();

				$filing["change_info"] = 1;

				$this->db->where(array("company_code" => $_POST['company_code'], "id" => $_POST['filing_id']));
				$this->db->update("filing",$filing);

				$this->save_audit_trail("Clients", "AGM and Annual Return", "Year end ".$_POST['year_end']." is edited.", $_POST['company_code']);

				$check_history_filing = $this->db->get_where("history_filing", array("company_code" => $_POST['company_code'], "id" => $_POST['filing_id']));

				if($old_filing_result[0]['year_end'] == $check_year_end[0]['year_end'])
				{
					if (!$check_history_filing->num_rows())
					{
						$w = $q->result();

						foreach($w as $r) {
					        $this->db->insert("history_filing",$r);
					    }
					} 
					else 
					{
						$x = $q->result_array();

						$data_history['year_end'] = $x[0]["year_end"];

					    $this->db->update("history_filing",$data_history,array("company_code" => $_POST['company_code'], "id" => $_POST['filing_id']));
					}
				}

				$next_filing_id = (int)$_POST['filing_id'] + 1;

				$check_due_date_175_not_empty = $this->db->get_where("filing", array("company_code" => $_POST['company_code'], "id" => $next_filing_id));

				if ($check_due_date_175_not_empty->num_rows())
				{				
					if($check_due_date_175_not_empty->result()[0]->due_date_175 == "")
					{
						$latest_two_digit_year_previous_agm = date('y', strtotime($_POST['agm']));
						$latest_two_digit_year_latest_agm = date('y', strtotime('+15 month', strtotime($_POST['agm'])));

						$new_due_date_175['due_date_175'] = date('d F Y', strtotime('+15 month', strtotime($_POST['agm'])));
						
						$latest_agm = date('Y-m-d', strtotime($_POST['agm']));

						$agm_date = new DateTime($latest_agm);
						// We extract the day of the month as $start_day
					    $agm_date = $this->MonthShifter($agm_date,15)->format(('Y-m-d'));
						$new_due_date_175['due_date_175'] = date('d F Y', strtotime($agm_date));


						$this->db->where(array("company_code" => $_POST['company_code'], "id" => $next_filing_id));
						$this->db->update("filing",$new_due_date_175);
					}
				} 
			}

			//---------check 28 or 29 February---------------------
			$original_fye_date = $_POST['year_end'];

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
	        	$fye_dfy = date('d F Y', strtotime('+1 year', strtotime($_POST['year_end'])));
	            $fye_ymd = date('Y-m-d', strtotime('+1 year', strtotime($_POST['year_end'])));
	        }

			$latest_id = $this->db->query("select * from filing where company_code='".$_POST['company_code']."' ORDER BY id DESC LIMIT 1");

			//---------end check 28 or 29 February---------------------

			if($_POST['agm'] != null && $_POST['agm'] != "dispensed")
			{
				$new_filing['company_code'] = $_POST['company_code'];
				//$futureDate=date('d-m-Y', strtotime('+1 year', strtotime($_POST['year_end'])) );
				$new_filing['year_end'] = $fye_dfy;
				$new_filing['ar_filing_date'] = "";
				$new_filing['financial_year_period_id'] = 1;
				$new_filing['financial_year_period1'] = date('d F Y', strtotime('+1 day', strtotime($_POST['filing_financial_year_period2'])));
				$new_filing['financial_year_period2'] = $fye_dfy;
				$new_filing['175_extended_to'] = 0;
				$new_filing["201_extended_to"] = 0;
				$new_filing["197_extended_to"] = 0;

				$latest_year_end = date('Y-m-d', strtotime($new_filing['year_end']));

				$year_end_date = new DateTime($latest_year_end);

				if(date('Y-m-d', strtotime("8/31/2018")) > $fye_ymd) 
				{
					$two_digit_year_previous_agm = date('y', strtotime($_POST['agm']));
					$two_digit_year_latest_agm = date('y', strtotime('+15 month', strtotime($_POST['agm'])));
					$new_filing['agm'] = "";

					$latest_agm = date('Y-m-d', strtotime($_POST['agm']));

					$agm_date = new DateTime($latest_agm);
					// We extract the day of the month as $start_day
				    $agm_date = $this->MonthShifter($agm_date,15)->format(('Y-m-d'));
					$new_filing['due_date_175'] = date('d F Y', strtotime($agm_date));

					$new_format_due_date_175 = new DateTime($new_filing['due_date_175']);
					
					// We extract the day of the month as $start_day
				    $date_201 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

					$new_filing['due_date_201'] =  date("t F Y", strtotime($date_201));

					$new_format_due_date_201 = new DateTime($new_filing['due_date_201']);

					if($new_format_due_date_175 >= $new_format_due_date_201)
					{
						$date_197 = $this->MonthShifter($new_format_due_date_201,1)->format(('Y-m-d'));

						$new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
					}
					else if($new_format_due_date_201 > $new_format_due_date_175)
					{
						$date_197 = $this->MonthShifter($new_format_due_date_175,1)->format(('Y-m-d'));

						$new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
					}
				}
				else
				{
					$new_filing['agm'] = "";

					$date_175 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

					$new_filing['due_date_175'] = ""; //date('d F Y', strtotime($date_175));

					$date_201 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

					$new_filing['due_date_201'] = date('t F Y', strtotime($date_201));

					$date_197 = $this->MonthShifter($year_end_date,7)->format(('Y-m-d'));

					$new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
				}
				
				if($latest_id->result()[0]->id != $_POST['filing_id'] && $_POST['filing_id'] != "")
				{
					$this->db->where(array("id" => $latest_id->result()[0]->id));
					$this->db->update("filing",$new_filing);
				}
				else
				{
					$this->db->insert("filing",$new_filing);
				}
			}
			elseif($_POST['agm'] != null && $_POST['agm'] == "dispensed")
			{
				$new_filing['company_code'] = $_POST['company_code'];
				$new_filing['year_end'] = $fye_dfy;
				$new_filing['ar_filing_date'] = "";
				$new_filing['financial_year_period_id'] = 1;
				$new_filing['financial_year_period1'] = date('d F Y', strtotime('+1 day', strtotime($_POST['filing_financial_year_period2'])));
				$new_filing['financial_year_period2'] = $fye_dfy;
				$new_filing['175_extended_to'] = 0;
				$new_filing["201_extended_to"] = 0;
				$new_filing["197_extended_to"] = 0;
				$new_filing['due_date_175'] = "Not Applicable";

				$latest_year_end = date('Y-m-d', strtotime($new_filing['year_end']));

				$year_end_date = new DateTime($latest_year_end);

				if(date('Y-m-d', strtotime("8/31/2018")) > $fye_ymd) 
				{
					$new_filing['agm'] = "";

					$latest_due_date_201 = date('Y-m-d', strtotime($new_filing['year_end']));

					$date1 = new DateTime($latest_due_date_201);
					// We extract the day of the month as $start_day
				    $date1 = $this->MonthShifter($date1,6)->format(('Y-m-d'));

					$new_filing['due_date_201'] =  date("t F Y", strtotime($date1));

					if($new_filing['due_date_175'] == "Not Applicable")
					{
						$new_filing['due_date_197'] = "Not Applicable";
					}
				}
				else
				{
					$new_filing['agm'] = "";

					$date_175 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

					$new_filing['due_date_175'] = "";

					$date_201 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

					$new_filing['due_date_201'] = date('t F Y', strtotime($date_201));

					$date_197 = $this->MonthShifter($year_end_date,7)->format(('Y-m-d'));

					$new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
				}

				if($latest_id->result()[0]->id != $_POST['filing_id'] && $_POST['filing_id'] != "")
				{
					$this->db->where(array("id" => $latest_id->result()[0]->id));
					$this->db->update("filing",$new_filing);
				}
				else
				{
					$this->db->insert("filing",$new_filing);
				}
			}
			

			$get_all_filing_data = $this->db->query("select filing.*, financial_year_period.period from filing left join financial_year_period on financial_year_period.id = filing.financial_year_period_id where company_code='".$_POST['company_code']."' order by id");

	        if ($get_all_filing_data->num_rows() > 0) {
	            foreach (($get_all_filing_data->result()) as $row) {
	                $data[] = $row;
	            }
	        }

	        $get_all_eci_filing_data = $this->db->query("select eci_filing.* from eci_filing where company_code='".$_POST['company_code']."' order by id");

	        $get_all_eci_filing_data = $get_all_eci_filing_data->result_array();

	        if(count($get_all_eci_filing_data) > 0)
	        {
	        	$have_eci = false;
	        }
	        else
	        {
	        	$have_eci = true;
	        }

	        $get_all_tax_filing_data = $this->db->query("select tax_filing.* from tax_filing where company_code='".$_POST['company_code']."' order by id");

	        $get_all_tax_filing_data = $get_all_tax_filing_data->result_array();

	        if(count($get_all_tax_filing_data) > 0)
	        {
	        	$have_tax = false;
	        }
	        else
	        {
	        	$have_tax = true;
	        }

        	echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "filing_data" => $data, 'have_eci' => $have_eci, 'have_tax' => $have_tax));
        }
	}

	public function MonthShifter (DateTime $aDate,$months){
        $dateA = clone($aDate);
        $dateB = clone($aDate);
        $plusMonths = clone($dateA->modify($months . ' Month'));
        //check whether reversing the month addition gives us the original day back
        if($dateB != $dateA->modify($months*-1 . ' Month')){ 
            $result = $plusMonths->modify('last day of last month');
        } elseif($aDate == $dateB->modify('last day of this month')){
            $result =  $plusMonths->modify('last day of this month');
        } else {
            $result = $plusMonths;
        }
        return $result;
    }

	public function delete_filing ()
	{
		$id = $_POST["filing_id"];

		$q = $this->db->get_where("filing", array("id" => $id));

		if ($q->num_rows())
		{
			$filing_array = $q->result_array();
			$this->save_audit_trail("Clients", "AGM and Annual Return", "Year end ".$filing_array[0]["year_end"]." is deleted.", $filing_array[0]["company_code"]);
		}

		$this->db->delete("filing",array('id'=>$id));

		$get_all_filing_data = $this->db->query("select filing.*, financial_year_period.period from filing left join financial_year_period on financial_year_period.id = filing.financial_year_period_id where company_code='".$_POST['company_code']."' order by id");

        if ($get_all_filing_data->num_rows() > 0) {
            foreach (($get_all_filing_data->result()) as $row) {
                $data[] = $row;
            }
        }

        $get_all_eci_filing_data = $this->db->query("select eci_filing.* from eci_filing where company_code='".$_POST['company_code']."' order by id");

        $get_all_eci_filing_data = $get_all_eci_filing_data->result_array();

        if(count($get_all_eci_filing_data) > 0)
        {
        	$have_eci = false;
        }
        else
        {
        	$have_eci = true;
        }

        $get_all_tax_filing_data = $this->db->query("select tax_filing.* from tax_filing where company_code='".$_POST['company_code']."' order by id");

        $get_all_tax_filing_data = $get_all_tax_filing_data->result_array();

        if(count($get_all_tax_filing_data) > 0)
        {
        	$have_tax = false;
        }
        else
        {
        	$have_tax = true;
        }

    	echo json_encode(array("filing_data" => $data, 'have_eci' => $have_eci, 'have_tax' => $have_tax));
	}

	public function delete_tax_filing()
	{
		$id = $_POST["tax_filing_id"];

		$q = $this->db->get_where("tax_filing", array("id" => $id));

		if ($q->num_rows())
		{
			$tax_filing_array = $q->result_array();
			$this->save_audit_trail("Clients", "Corporate Tax", "Corporate tax period ".$tax_filing_array[0]['coporate_tax_period']." is deleted.", $tax_filing_array[0]['company_code']);
		}

		$this->db->delete("tax_filing",array('id'=>$id));

		$this->data['tax_filing_data'] = $this->master_model->get_all_tax_filing_data($_POST['company_code']);

    	echo json_encode(array("tax_filing_data" => $this->data));
	}

	public function delete_gst_filing()
	{
		$id = $_POST["gst_filing_id"];

		$q = $this->db->get_where("gst_filing", array("id" => $id));

		if ($q->num_rows())
		{
			$gst_filing_array = $q->result_array();
			$this->save_audit_trail("Clients", "GST", "GST year end ".$gst_filing_array[0]['gst_year_end']." is deleted.", $gst_filing_array[0]['company_code']);
		}

		$this->db->delete("gst_filing", array('id'=>$id));

		$this->data['gst_filing_data'] = $this->master_model->get_all_gst_filing_data($_POST['company_code']);

    	echo json_encode(array("gst_filing_data" => $this->data));
	}

	public function delete_eci_filing()
	{
		$id = $_POST["eci_filing_id"];
		
		$q = $this->db->get_where("eci_filing", array("id" => $id));

		if ($q->num_rows())
		{
			$eci_filing_array = $q->result_array();
			$this->save_audit_trail("Clients", "Estimated Chargeable Income (ECI)", "ECI tax period ".$eci_filing_array[0]['eci_tax_period']." is deleted.", $eci_filing_array[0]['company_code']);
		}

		$this->db->delete("eci_filing",array('id'=>$id));

		$this->data['eci_filing_data'] = $this->master_model->get_all_eci_filing_data($_POST['company_code']);

    	echo json_encode(array("eci_filing_data" => $this->data));
	}

	public function get_billing_info_service_category()
	{
		$ci =& get_instance();
		$query = "select * from billing_info_service_category";

		$result = $ci->db->query($query);
		$result = $result->result_array();

		$res = array();
		foreach($result as $row) {
            $res[$row['id']] = $row['category_description'];
        }

        $data = array('status'=>'success', 'tp'=>1,'result'=>$res);

        echo json_encode($data);
	}

	public function get_service_proposal_letter_required()
	{
		$ci =& get_instance();
		$query = "select * from service_proposal_letter_required";

		$result = $ci->db->query($query);
		$result = $result->result_array();

		$res = array();
		foreach($result as $row) {
            $res[$row['id']] = $row['sp_required'];
        }

        $data = array('status'=>'success', 'tp'=>1,'result'=>$res);

        echo json_encode($data);
	}

	public function get_engagement_letter_required()
	{
		$ci =& get_instance();
		$query = "select * from engagement_letter_required";

		$result = $ci->db->query($query);
		$result = $result->result_array();

		$res = array();
		foreach($result as $row) {
            $res[$row['id']] = $row['el_required'];
        }

        $data = array('status'=>'success', 'tp'=>1,'result'=>$res);

        echo json_encode($data);
	}

	public function get_display_in_engagement_letter_list()
	{
		$ci =& get_instance();
		$query = "select * from engagement_letter_list";

		$result = $ci->db->query($query);
		$result = $result->result_array();

		$res = array();
		foreach($result as $row) {
            $res[$row['id']] = $row['engagement_letter_list_name'];
        }

        $data = array('status'=>'success', 'tp'=>1,'result'=>$res);

        echo json_encode($data);
	}

	public function get_display_in_service_engagement()
	{
		$ci =& get_instance();
		$query = "select * from display_in_service_engagement";

		$result = $ci->db->query($query);
		$result = $result->result_array();

		$res = array();
		foreach($result as $row) {
            $res[$row['id']] = $row['display_in_se'];
        }

        $data = array('status'=>'success', 'tp'=>1,'result'=>$res);

        echo json_encode($data);
	}

	public function get_unit_pricing()
	{
		$ci =& get_instance();
		$query = "select * from unit_pricing";

		$result = $ci->db->query($query);
		$result = $result->result_array();

		$res = array();
		foreach($result as $row) {
            $res[$row['id']] = $row['unit_pricing_name'];
        }

        $data = array('status'=>'success', 'tp'=>1,'result'=>$res);

        echo json_encode($data);
	}

	public function get_billing_service()
	{
		$service = $_POST['service'];
		$firm_id = $this->session->userdata("firm_id");

		$ci =& get_instance();

		$query = "select billing_info_service.*, billing_info_service_category.category_description from billing_info_service left join billing_info_service_category on billing_info_service_category.category_code = billing_info_service.category_code order by billing_info_service.id";

		$selected_query = "select A.id from billing_info_service AS A WHERE EXISTS (SELECT service from billing_template AS B WHERE firm_id = '".$firm_id."' AND A.id = B.service)";

		$result = $ci->db->query($query);
		$selected_result = $ci->db->query($selected_query);
        $result = $result->result_array();
        $selected_result = $selected_result->result_array();

        if(!$result) {
          throw new exception("Service not found.");
        }

        $selected_res = array();
        foreach($selected_result as $key => $row) {
            $selected_res[$key] = $row['id'];
        }

        if($service != "")
        {
        	$select_service = $service;
        }
        else
        {
        	$select_service = null;
        }
        
        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Service fetched successfully.", 'result'=>$result, 'selected_service'=>$select_service, 'selected_query'=> $selected_res);

        echo json_encode($data);
	}

	public function get_billing_info_service()
	{
		$service = (isset($_POST['service'])?$_POST['service']:"");
		$company_code = $_POST['company_code'];

		$ci =& get_instance();

		if($service == "")
		{
			$deleted = " AND our_service_info.deleted = 0 AND our_service_info.approved = 1";
		}
		else
		{
			$deleted = "";
		}

		$query = "select our_service_info.*, billing_info_service_category.category_description from our_service_info left join billing_info_service_category on billing_info_service_category.id = our_service_info.service_type where our_service_info.user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."'".$deleted." order by our_service_info.id";

		$selected_query = "select A.id from our_service_info AS A WHERE EXISTS (SELECT service from client_billing_info AS B WHERE company_code = '".$company_code."' AND A.id = B.service)";

		$selected_billing_info_service_category = "select billing_info_service_category.* from billing_info_service_category";

		$result = $ci->db->query($query);
		$selected_result = $ci->db->query($selected_query);
		$selected_billing_info_service_category = $ci->db->query($selected_billing_info_service_category);
		
        $result = $result->result_array();
        $selected_result = $selected_result->result_array();
        $selected_billing_info_service_category = $selected_billing_info_service_category->result_array();

        if (count($selected_result) == 0) {
            $selected_querys = "select A.id from our_service_info AS A WHERE EXISTS (SELECT service from billing_template AS B WHERE A.id = B.service)";

            $selected_result = $ci->db->query($selected_querys);

            $selected_result = $selected_result->result_array();
        }

        $selected_res = array();
        foreach($selected_result as $key => $row) {
            $selected_res[$key] = $row['id'];
        }

        if($service != "")
        {
        	$select_service = $service;
        }
        else
        {
        	$select_service = null;
        }
        

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Service fetched successfully.", 'result'=>$result, 'selected_service'=>$select_service, 'selected_query'=> $selected_res, 'selected_billing_info_service_category' => $selected_billing_info_service_category, 'firm_id'=>$this->session->userdata("firm_id"));

        echo json_encode($data);
	}

	public function get_billing_info_frequency()
	{
		$ci =& get_instance();

		$query = "select * from billing_info_frequency";

		$result = $ci->db->query($query);
        $result = $result->result_array();

        if(!$result) {
          throw new exception("Frequency not found.");
        }

        $res = array();
        foreach($result as $row) {
            $res[$row['id']] = $row['frequency'];
        }

        $ci =& get_instance();
        $selected_frequency = $ci->session->userdata('billing_period');
        $ci->session->unset_userdata('billing_period');
        

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Frequency fetched successfully.", 'result'=>$res, 'selected_frequency'=>$selected_frequency);

        echo json_encode($data);
	}

	public function get_type_of_day()
	{
		$type_of_day = $_POST['type_of_day'];

		$ci =& get_instance();

		$query = "select * from type_of_day";

		$result = $ci->db->query($query);
        $result = $result->result_array();

        if(!$result) {
          throw new exception("Type of Day not found.");
        }

        $res = array();
        foreach($result as $row) {
            $res[$row['id']] = $row['type_of_day'];
        }

        if($type_of_day != "")
        {
        	$selected_type_of_day = $type_of_day;
        }
        else
        {
        	$selected_type_of_day = null;
        }
        
        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Type of Day fetched successfully.", 'result'=>$res, 'selected_type_of_day'=>$selected_type_of_day);

        echo json_encode($data);
	}

	public function search_register()
	{
		$company_code = $_POST['company_code'];
		$register = $_POST['register'];

		$from = $_POST['from'];
		$to = $_POST['to'];

		$client_guarantee_query = $this->db->query('select * from client where company_code = "'.$company_code.'"');

		if ($client_guarantee_query->num_rows() > 0) {

			$client_guarantee_query = $client_guarantee_query->result_array();

			if($client_guarantee_query[0]["company_type"] == "4" || $client_guarantee_query[0]["company_type"] == "5" || $client_guarantee_query[0]["company_type"] == "6")
			{
				$check_member_state = "guarantee";
			}
			else
			{
				$check_member_state = "non-guarantee";
			}
		}

		if($register == "all")
		{
			echo json_encode(array("client_name" => $this->encryption->decrypt($client_guarantee_query[0]["company_name"]), "check_member_state" => $check_member_state, "register" => $register, "uen" => $this->encryption->decrypt($client_guarantee_query[0]["registration_no"]), $this->search_register_profile($company_code), $this->search_register_officer($company_code, $from, $to), $this->search_register_member($company_code, $from, $to), $this->search_register_charges($company_code, $from, $to), $this->search_register_filing($company_code, $from, $to), $this->search_register_controller($company_code, $from, $to), $this->search_register_nominee_director($company_code, $from, $to), $this->search_register_transfer($company_code, $from, $to)));
		}
		else if($register == "profile")
		{
			echo json_encode(array("register" => $register, $this->search_register_profile($company_code)));
		}
		else if($register == "member")
		{
			echo json_encode(array("client_name" => $this->encryption->decrypt($client_guarantee_query[0]["company_name"]), "check_member_state" => $check_member_state, "register" => $register, $this->search_register_member($company_code, $from, $to)));
		}
		else if($register == "filing")
		{
			echo json_encode(array("register" => $register, $this->search_register_filing($company_code, $from, $to)));
		}
		else if($register == "officer")
		{
            echo json_encode(array("register" => $register, $this->search_register_officer($company_code, $from, $to)));
		}
		else if($register == "nominee_director")
		{
            echo json_encode(array("register" => $register, $this->search_register_nominee_director($company_code, $from, $to)));
		}
		else if($register == "charges")
		{
			
	        echo json_encode(array("register" => $register, $this->search_register_charges($company_code, $from, $to)));
		}
		else if($register == "controller")
		{
			
	        echo json_encode(array("register" => $register, $this->search_register_controller($company_code, $from, $to)));
		}
		else if($register == "transfer")
		{
			
	        echo json_encode(array("client_name" => $this->encryption->decrypt($client_guarantee_query[0]["company_name"]), "register" => $register, "uen" => $this->encryption->decrypt($client_guarantee_query[0]["registration_no"]), $this->search_register_transfer($company_code, $from, $to)));
		}
		else
		{
			echo json_encode(array("register" => null));
		}
	}

	public function search_register_profile($company_code)
	{
		$this->db->select('client.*, acquried_by.acquried_by as acquried_by_name, company_type.company_type as company_type_name, status.status as status_name');
		$this->db->from('client');
		$this->db->join('acquried_by', 'acquried_by.id = client.acquried_by', 'left');
		$this->db->join('company_type', 'company_type.id = client.company_type', 'left');
		$this->db->join('status', 'status.id = client.status', 'left');
		$this->db->where('company_code', $company_code);
		$query = $this->db->get(); 

		if ($query->num_rows() > 0) {
            foreach (($query->result()) as $row) {
            	$row->registration_no = $this->encryption->decrypt($row->registration_no);
                $row->company_name = $this->encryption->decrypt($row->company_name);
                $data[] = $row;
            }
            $this->data['profile'] = $data;
            return $this->data;
        }
		
	}

	public function search_register_member($company_code, $from, $to)
	{
		$client_query = $this->db->query('select * from client where company_code = "'.$company_code.'"');

		if ($client_query->num_rows() > 0) {

			$client_query = $client_query->result_array();

			if($client_query[0]["company_type"] == "4" || $client_query[0]["company_type"] == "5" || $client_query[0]["company_type"] == "6")
			{
				$where = "";
				if($from == null && $to == null)
				{
					$where = "";
				}
				if($from != null && $to == null)
				{
					$where = 'STR_TO_DATE(client_guarantee.guarantee_start_date,"%d/%m/%Y") >= STR_TO_DATE("'. $from. '","%d/%m/%Y") AND';
				}
				else if($from != null && $to != null)
				{
					$where = 'STR_TO_DATE(client_guarantee.guarantee_start_date,"%d/%m/%Y") BETWEEN  STR_TO_DATE("'. $from. '","%d/%m/%Y") and STR_TO_DATE("'. $to.'","%d/%m/%Y") AND';
				}

				$query = $this->db->query('select client_guarantee.*, DATE_FORMAT(STR_TO_DATE(client_guarantee.guarantee_start_date, "%d/%m/%Y"), "%d/%m/%Y") as transaction_date, STR_TO_DATE(client_guarantee.guarantee_start_date, "%d/%m/%Y") as trans_date, officer.field_type as officer_field_type, officer.identification_no, officer.name, officer.address_type as officer_address_type, officer.postal_code1, officer.street_name1, officer.building_name1, officer.unit_no1, officer.unit_no2, officer.foreign_address1, officer.foreign_address2, officer.foreign_address3, officer_company.field_type as officer_company_field_type, officer_company.register_no, officer_company.company_name, officer_company.address_type as officer_company_address_type, officer_company.company_postal_code, officer_company.company_street_name, officer_company.company_building_name, officer_company.company_unit_no1, officer_company.company_unit_no2, officer_company.company_foreign_address1, officer_company.company_foreign_address2, officer_company.company_foreign_address3, currency.currency as currency_name from client_guarantee left join officer on client_guarantee.officer_id = officer.id and client_guarantee.field_type = officer.field_type left join officer_company on client_guarantee.officer_id = officer_company.id and client_guarantee.field_type = officer_company.field_type left join currency on currency.id = client_guarantee.currency_id where '.$where.' company_code ="'.$company_code.'" ORDER BY officer_company.company_name, officer.name, trans_date');

				if ($query->num_rows() > 0) {
		            foreach (($query->result()) as $row) {
		            	if($row->officer_field_type == "individual")
	                    {
	                        $row->identification_no = $this->encryption->decrypt($row->identification_no);
	                        $row->name = $this->encryption->decrypt($row->name);
	                    }
	                    elseif($row->officer_company_field_type == "company")
	                    {
	                        $row->register_no = $this->encryption->decrypt($row->register_no);
	                        $row->company_name = $this->encryption->decrypt($row->company_name);
	                    }
	                    
		                $data[] = $row;
		            }
		            $this->data['guarantee_member'] = $data;
		            return $this->data;
		        }
			}
			else
			{
				$where = "";
				if($from == null && $to == null)
				{
					$where = "";
				}
				if($from != null && $to == null)
				{
					$where = 'STR_TO_DATE(member_shares.transaction_date,"%d/%m/%Y") >= STR_TO_DATE("'. $from. '","%d/%m/%Y") AND';
				}
				else if($from != null && $to != null)
				{
					$where = 'STR_TO_DATE(member_shares.transaction_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $from. '","%d/%m/%Y") and STR_TO_DATE("'. $to.'","%d/%m/%Y") AND';
				}

				$query = $this->db->query('select member_shares.*, member_shares.number_of_share as number_of_share, member_shares.amount_share as amount_share, member_shares.no_of_share_paid as no_of_share_paid, member_shares.amount_paid as amount_paid, member_shares.transaction_type, DATE_FORMAT(STR_TO_DATE(member_shares.transaction_date, "%d/%m/%Y"), "%d/%m/%Y") as transaction_date, STR_TO_DATE(member_shares.transaction_date, "%d/%m/%Y") as trans_date, officer.field_type as officer_field_type, officer.identification_no, officer.name, officer.address_type as officer_address_type, officer.postal_code1, officer.street_name1, officer.building_name1, officer.unit_no1, officer.unit_no2, officer.foreign_address1, officer.foreign_address2, officer.foreign_address3, officer_company.field_type as officer_company_field_type, officer_company.register_no, officer_company.company_name, officer_company.address_type as officer_company_address_type, officer_company.company_postal_code, officer_company.company_street_name, officer_company.company_building_name, officer_company.company_unit_no1, officer_company.company_unit_no2, officer_company.company_foreign_address1, officer_company.company_foreign_address2, officer_company.company_foreign_address3, officer_company.country_of_incorporation, client.registration_no, "client" as client_field_type, client.company_name as client_company_name, client.postal_code as client_postal_code, client.street_name as client_street_name, client.building_name as client_building_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency, certificate.certificate_no, certificate.status, nationality.nationality as nationality_name from member_shares left join certificate on certificate.officer_id = member_shares.officer_id and certificate.field_type = member_shares.field_type and certificate.transaction_id = member_shares.transaction_id left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join nationality on nationality.id = officer.nationality where '.$where.' member_shares.company_code="'.$company_code.'" ORDER BY share_capital.id, officer_company.company_name, officer.name, trans_date, member_shares.id');

				if ($query->num_rows() > 0) {
		            foreach (($query->result()) as $row) {
		            	if($row->officer_field_type == "individual")
	                    {
	                        $row->identification_no = $this->encryption->decrypt($row->identification_no);
	                        $row->name = $this->encryption->decrypt($row->name);
	                    }
	                    elseif($row->officer_company_field_type == "company")
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
		            $this->data['member'] = $data;
		            return $this->data;
		        }
			}
		}
	}

	public function search_register_filing($company_code, $from, $to)
	{
		if($from == null && $to == null)
		{
			$get_all_filing_data = $this->db->query("select * from filing where company_code='".$company_code."' AND ar_filing_date != '' order by id");
		}
		else if($from != null && $to == null)
		{
			$get_all_filing_data = $this->db->query('select * from filing where STR_TO_DATE(year_end,"%d %M %Y") >= STR_TO_DATE("'.$from.'","%d/%m/%Y") AND company_code="'.$company_code.'" AND ar_filing_date != "" order by id');
		}
		else if($from != null && $to != null)
		{
			$get_all_filing_data = $this->db->query('select * from filing where STR_TO_DATE(year_end,"%d %M %Y") BETWEEN  STR_TO_DATE("'.$from.'","%d/%m/%Y") and STR_TO_DATE("'.$to.'","%d/%m/%Y") AND company_code="'.$company_code.'" AND ar_filing_date != "" order by id');
		}

        if ($get_all_filing_data->num_rows() > 0) {
            foreach (($get_all_filing_data->result()) as $row) {
                $data[] = $row;
            }
            $this->data['filing_data'] = $data;
        }
        else
        {
        	$this->data['filing_data'] = [];
        }
		return $this->data;
	}

	public function search_register_officer($company_code, $from, $to)
	{
		if($from == null && $to == null)
		{
			$q = $this->db->query('select client_officers.*, officer.field_type as officer_field_type, officer.identification_no, officer.name, officer.address_type as officer_address_type, officer.postal_code1, officer.street_name1, officer.building_name1, officer.unit_no1, officer.unit_no2, officer.foreign_address1, officer.foreign_address2, officer.foreign_address3, officer_company.field_type as officer_company_field_type, officer_company.register_no, officer_company.company_name, officer_company.address_type as officer_company_address_type, officer_company.company_postal_code, officer_company.company_street_name, officer_company.company_building_name, officer_company.company_unit_no1, officer_company.company_unit_no2, officer_company.company_foreign_address1, officer_company.company_foreign_address2, officer_company.company_foreign_address3, client_officers_position.position as position_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type left join client_officers_position on client_officers.position = client_officers_position.id where company_code ="'.$company_code.'" ORDER BY position_name');
		}
		else if($from != null && $to == null)
		{
			$q = $this->db->query('select client_officers.*, client_officers.date_of_appointment, officer.field_type as officer_field_type, officer.identification_no, officer.name, officer.address_type as officer_address_type, officer.postal_code1, officer.street_name1, officer.building_name1, officer.unit_no1, officer.unit_no2, officer.foreign_address1, officer.foreign_address2, officer.foreign_address3, officer_company.field_type as officer_company_field_type, officer_company.register_no, officer_company.company_name, officer_company.address_type as officer_company_address_type, officer_company.company_postal_code, officer_company.company_street_name, officer_company.company_building_name, officer_company.company_unit_no1, officer_company.company_unit_no2, officer_company.company_foreign_address1, officer_company.company_foreign_address2, officer_company.company_foreign_address3, client_officers_position.position as position_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type left join client_officers_position on client_officers.position = client_officers_position.id where STR_TO_DATE(client_officers.date_of_appointment,"%d/%m/%Y") >= STR_TO_DATE("'. $from. '","%d/%m/%Y") AND client_officers.company_code ="'.$company_code.'" ORDER BY position_name');
		}
		else if($from != null && $to != null)
		{
			$q = $this->db->query('select client_officers.*, client_officers.date_of_appointment, officer.field_type as officer_field_type, officer.identification_no, officer.name, officer.address_type as officer_address_type, officer.postal_code1, officer.street_name1, officer.building_name1, officer.unit_no1, officer.unit_no2, officer.foreign_address1, officer.foreign_address2, officer.foreign_address3, officer_company.field_type as officer_company_field_type, officer_company.register_no, officer_company.company_name, officer_company.address_type as officer_company_address_type, officer_company.company_postal_code, officer_company.company_street_name, officer_company.company_building_name, officer_company.company_unit_no1, officer_company.company_unit_no2, officer_company.company_foreign_address1, officer_company.company_foreign_address2, officer_company.company_foreign_address3, client_officers_position.position as position_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type left join client_officers_position on client_officers.position = client_officers_position.id where STR_TO_DATE(client_officers.date_of_appointment,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $from. '","%d/%m/%Y") and STR_TO_DATE("'. $to.'","%d/%m/%Y") AND client_officers.company_code ="'.$company_code.'" ORDER BY client_officers.date_of_appointment, position_name');
		}

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
            	if($row->officer_field_type == "individual")
                {
                    $row->identification_no = $this->encryption->decrypt($row->identification_no);
                    $row->name = $this->encryption->decrypt($row->name);
                }
                elseif($row->officer_company_field_type == "company")
                {
                    $row->register_no = $this->encryption->decrypt($row->register_no);
                    $row->company_name = $this->encryption->decrypt($row->company_name);
                }
                $data[] = $row;
            }

            $this->data['client_officers'] = $data;            
        }
        else
        {
        	$this->data['client_officers'] = [];
        }
		return $this->data;
	}

	public function search_register_controller($company_code, $from, $to)
	{
		if($from == null && $to == null)
		{
			$q = $this->db->query('select client_controller.*, client_controller.company_code as client_controller_company_code, client_controller.id as client_controller_id, client_controller.field_type as client_controller_field_type, officer.*, officer.address_type as officer_address_type, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.address_type as officer_company_address_type, officer_company.company_name as officer_company_company_name, client.*, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, nationality.nationality as officer_nationality_name, company_type.company_type as client_company_type 
				from client_controller left join officer on client_controller.officer_id = officer.id and client_controller.field_type = officer.field_type left join officer_company on client_controller.officer_id = officer_company.id and client_controller.field_type = officer_company.field_type left join client on client_controller.officer_id = client.id and client_controller.field_type = "client" left join nationality on nationality.id = officer.nationality left join company_type on company_type.id = client.company_type where client_controller.company_code ="'.$company_code.'" AND client_controller.deleted = 0');
		}
		else if($from != null && $to == null)
		{
			$q = $this->db->query('select client_controller.*, client_controller.company_code as client_controller_company_code, client_controller.id as client_controller_id, client_controller.field_type as client_controller_field_type, officer.*, officer.address_type as officer_address_type, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.address_type as officer_company_address_type, officer_company.company_name as officer_company_company_name, client.*, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, nationality.nationality as officer_nationality_name, company_type.company_type as client_company_type
				from client_controller left join officer on client_controller.officer_id = officer.id and client_controller.field_type = officer.field_type left join officer_company on client_controller.officer_id = officer_company.id and client_controller.field_type = officer_company.field_type left join client on  client_controller.officer_id = client.id and client_controller.field_type = "client" left join nationality on nationality.id = officer.nationality left join company_type on company_type.id = client.company_type where STR_TO_DATE(client_controller.date_of_registration,"%d/%m/%Y") >= STR_TO_DATE("'. $from. '","%d/%m/%Y") AND client_controller.company_code ="'.$company_code.'" AND client_controller.deleted = 0');
		}
		else if($from != null && $to != null)
		{
			$q = $this->db->query('select client_controller.*, client_controller.company_code as client_controller_company_code, client_controller.id as client_controller_id, client_controller.field_type as client_controller_field_type, officer.*, officer.address_type as officer_address_type, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.*, officer_company.address_type as officer_company_address_type, officer_company.company_name as officer_company_company_name, client.*, client.company_name as client_company_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, nationality.nationality as officer_nationality_name, company_type.company_type as client_company_type
				from client_controller left join officer on client_controller.officer_id = officer.id and client_controller.field_type = officer.field_type left join officer_company on client_controller.officer_id = officer_company.id and client_controller.field_type = officer_company.field_type left join client on  client_controller.officer_id = client.id and client_controller.field_type = "client" left join nationality on nationality.id = officer.nationality left join company_type on company_type.id = client.company_type where STR_TO_DATE(client_controller.date_of_registration,"%d/%m/%Y") BETWEEN  STR_TO_DATE("'. $from. '","%d/%m/%Y") and STR_TO_DATE("'. $to.'","%d/%m/%Y") AND client_controller.company_code ="'.$company_code.'" AND client_controller.deleted = 0');
		}

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

            $this->data['client_controller'] = $data;
        }
        else
        {
        	$this->data['client_controller'] = [];
        }
		return $this->data;
	}

	public function search_register_nominee_director($company_code, $from, $to)
	{
		if($from == null && $to == null)
		{
			$filter_date = '';
		}
		else if($from != null && $to == null)
		{
			$filter_date = ' STR_TO_DATE(client_nominee_director.date_become_nominator,"%d/%m/%Y") >= STR_TO_DATE("'. $from. '","%d/%m/%Y") AND';
		}
		else if($from != null && $to != null)
		{
			$filter_date = ' STR_TO_DATE(client_nominee_director.date_become_nominator,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $from. '","%d/%m/%Y") and STR_TO_DATE("'. $to.'","%d/%m/%Y") AND';
		}

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
                left join company_type on company_type.id = client.company_type where'.$filter_date.' client_nominee_director.company_code ="'.$company_code.'" and client_nominee_director.deleted = 0');

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
            $this->data['client_nominee_director'] = $data;
        }
        else
        {
        	$this->data['client_nominee_director'] = [];
        }
		return $this->data;
	}

	public function search_register_charges($company_code, $from, $to)
	{
		$this->db->select('client_charges.*, currency.currency as currency_name');
		$this->db->from('client_charges');
		$this->db->join('currency', 'currency.id = client_charges.currency', 'left');
		$this->db->where('company_code', $company_code);

		if($from == null && $to == null)
		{	
			$q = $this->db->get(); 
		}
		else if($from != null && $to == null)
		{
			$this->db->where('STR_TO_DATE(client_charges.date_registration,"%d/%m/%Y") >= STR_TO_DATE("'.$from. '","%d/%m/%Y")');
			$q = $this->db->get(); 
		}
		else if($from != null && $to != null)
		{
			$this->db->where('STR_TO_DATE(client_charges.date_registration,"%d/%m/%Y") BETWEEN STR_TO_DATE("'.$from. '","%d/%m/%Y") and STR_TO_DATE("'.$to.'","%d/%m/%Y")');
			$q = $this->db->get(); 
		}

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            $this->data['charges'] = $data;
        }
        else
        {
        	$this->data['charges'] = [];
        }
		return $this->data;
	}

	public function search_register_transfer($company_code, $from, $to)
	{		
		if($from == null && $to == null)
		{
			$q = $this->db->query('select register_of_transfers.*, register_of_transfers_info.old_number_share, register_of_transfers_info.old_cert, register_of_transfers_info.balance_number_share, register_of_transfers_info.balance_cert from register_of_transfers left join register_of_transfers_info on register_of_transfers_info.register_of_transfers_id = register_of_transfers.id where register_of_transfers.company_code ="'.$company_code.'" ORDER BY register_of_transfers_info.id');
		}
		else if($from != null && $to == null)
		{
			$q = $this->db->query('select register_of_transfers.*, register_of_transfers_info.old_number_share, register_of_transfers_info.old_cert, register_of_transfers_info.balance_number_share, register_of_transfers_info.balance_cert from register_of_transfers left join register_of_transfers_info on register_of_transfers_info.register_of_transfers_id = register_of_transfers.id where STR_TO_DATE(register_of_transfers.date,"%d/%m/%Y") >= STR_TO_DATE("'. $from. '","%d/%m/%Y") AND company_code ="'.$company_code.'" ORDER BY register_of_transfers_info.id');
		}
		else if($from != null && $to != null)
		{
			$q = $this->db->query('select register_of_transfers.*, register_of_transfers_info.old_number_share, register_of_transfers_info.old_cert, register_of_transfers_info.balance_number_share, register_of_transfers_info.balance_cert from register_of_transfers left join register_of_transfers_info on register_of_transfers_info.register_of_transfers_id = register_of_transfers.id where STR_TO_DATE(register_of_transfers.date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $from. '","%d/%m/%Y") and STR_TO_DATE("'. $to.'","%d/%m/%Y") AND register_of_transfers.company_code ="'.$company_code.'" ORDER BY register_of_transfers_info.id');
		}

		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) 
            {
            	$transferorInfo = $this->checkMemberInfo($row->transferor_office_id, $row->transferor_field_type);
            	//echo json_encode($row->transferor_field_type);
            	$row->transferor_name = $transferorInfo["name"];
            	$row->transferor_address = $transferorInfo["address"];

            	$transfereeInfo = $this->checkMemberInfo($row->transferee_office_id, $row->transferee_field_type);
            	$row->transferee_name = $transfereeInfo["name"];
            	$row->transferee_address = $transfereeInfo["address"];

                $data[] = $row;
            }

            $this->data['client_transfer'] = $data;
        }
        else
        {
        	$this->data['client_transfer'] = [];
        }
		return $this->data;
	}

	public function checkMemberInfo($office_id, $field_type)
	{
		if($field_type == "individual")
        {
        	$officer_query = $this->db->query('select * from officer where id = "'.$office_id.'"');
        	$officer_query = $officer_query->result_array();

            $name = $this->encryption->decrypt($officer_query[0]["name"]);
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
            $address = $this->transaction_word_model->write_address_local_foreign($address, "comma", "big_cap");
        }
        elseif($field_type == "company")
        {
        	$query = $this->db->query('select * from officer_company where id = "'.$office_id.'"');
        	$query = $query->result_array();

            $name = $this->encryption->decrypt($query[0]["company_name"]);

            $member_address_type = $query[0]['address_type'];
            $member_unit_no1 = $query[0]["company_unit_no1"];
            $member_unit_no2 = $query[0]["company_unit_no2"];
            $member_street_name = $query[0]["company_street_name"];
            $member_building_name = $query[0]["company_building_name"];
            $member_postal_code = $query[0]["company_postal_code"];
            $foreign_address1 = $query[0]["company_foreign_address1"];
            $foreign_address2 = $query[0]["company_foreign_address2"];
            $foreign_address3 = $query[0]["company_foreign_address3"];

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

            $address = $this->transaction_word_model->write_address_local_foreign($address, "comma", "big_cap");
        }
        elseif($field_type == "client")
        {
        	$query = $this->db->query('select * from client where id = "'.$office_id.'"');
        	$client_query = $query->result_array();

            $name = $this->encryption->decrypt($client_query[0]["company_name"]);

            $member_address_type = "Local";
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
            $address = $this->transaction_word_model->write_address_local_foreign($address, "comma", "big_cap");
        }

        return array("name" => $name, "address" => $address);
	}

	public function calculate_new_filing_date()
	{	
		$latest_year_end = $_POST["latest_year_end"];

		$year_end_date = new DateTime($latest_year_end);

		$date_175 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

		$new_filing['due_date_175'] =  date("t F Y", strtotime($date_175));

		$date_201 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

		$new_filing['due_date_201'] =  date("t F Y", strtotime($date_201));

		$date_197 = $this->MonthShifter($year_end_date,7)->format(('Y-m-d'));

		$new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));

		$data = array('status'=>'success', 'tp'=>1, 'msg'=>"Year End fetched successfully.", 'result'=>$new_filing);

		echo json_encode($data);
	    //echo ($year_end_date);
	}

	public function calculate_new_gst_date()
	{
		$latest_gst_year_end = $_POST["latest_gst_year_end"];
		$gst_filing_cycle = $_POST["gst_filing_cycle"];

		$gst_year_end_date = new DateTime($latest_gst_year_end);
		$gst_year_end_date = $gst_year_end_date->format(('Y-m-d'));
		$gst_year_end_dates = date('d F Y',strtotime($gst_year_end_date . "+1 days"));
		$new_gst_filing_date['from_filing_period'] = $gst_year_end_dates;

		if($gst_filing_cycle == 1)
		{
			$to_filing_period = $this->check_date($gst_year_end_date, 3)->format(('d F Y'));
			$filing_due_date = $this->check_date($to_filing_period, 1)->format(('d F Y'));
		}
		elseif($gst_filing_cycle == 2)
		{
			$to_filing_period = $this->check_date($gst_year_end_date, 12)->format(('d F Y'));
			$filing_due_date = $this->check_date($to_filing_period, 13)->format(('d F Y'));
		}
		elseif($gst_filing_cycle == 3)
		{
			$to_filing_period = $this->check_date($gst_year_end_date, 6)->format(('d F Y'));
			$filing_due_date = $this->check_date($to_filing_period, 7)->format(('d F Y'));
		}

		$new_gst_filing_date['to_filing_period'] = $to_filing_period;
		$new_gst_filing_date['filing_due_date'] = $filing_due_date;

		$data = array('status'=>'success', 'tp'=>1, 'msg'=>"GST Year End fetched successfully.", 'result'=>$new_gst_filing_date);

		echo json_encode($data);
	}

	public function check_date($date_str, $months)
	{
	    $date = new DateTime($date_str);

	    // We extract the day of the month as $start_day
	    $start_day = $date->format('j');

	    // We add 1 month to the given date
	    $date->modify("+{$months} month");

	    // We extract the day of the month again so we can compare
	    $end_day = $date->format('j');

	    if ($start_day != $end_day)
	    {
	        // The day of the month isn't the same anymore, so we correct the date
	        $date->modify('last day of last month');
	    }

	    return $date;
	}

	public function deactivateServiceEngagement()
    {
        $checked = $_POST["checked"];
        $client_billing_info_id = $_POST["client_billing_info_id"];
        $company_code = $_POST["company_code"];

		if($checked == "false") //false
        {
	        $data_checked["deactive"] = 0;
	    }
	    else
	    {
	    	$data_checked["deactive"] = 1;
	    }
	    $this->db->update("client_billing_info",$data_checked,array("client_billing_info_id" => $client_billing_info_id, "company_code" => $company_code));

	    echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
    }

    public function get_client_code()
    {
    	$company_name = $_POST["company_name"];

    	$previous_client_code = $this->transaction_model->detect_previous_client_code($company_name);
    	$latest_client_code = $this->transaction_model->detect_client_code($company_name);

		echo json_encode(array("previous_client_code" => $previous_client_code, "latest_client_code" => $latest_client_code));
    }

    public function get_business_activity_list()
    {
    	$business_activity_query = $this->db->query("select * from business_activity_list");

        $business_activity_query = $business_activity_query->result_array();
        foreach($business_activity_query as $row)
     	{
     		$array[] = $row["business_activity_name"];
    	}

        echo json_encode($array);
    }

    public function search_letter_of_conf_auditor_function()
	{
		$company_code = $_POST["company_code"];
		$from = $_POST["letter_conf_auditor_date_from"];
		$to = $_POST["letter_conf_auditor_date_to"];

		$this->data['list_of_confirmation_auditor'] = $this->master_model->get_all_list_of_confirmation_auditor($company_code, $_SESSION['group_id'], $from, $to);

		echo json_encode($this->data);
	}

	public function save_company_document()
    {
    	$company_code = $_POST["company_code"];
    	$document_type_id = $_POST["document_type_id"];
        $document_type = $_POST["document_type"];
        $document_others_name = $_POST["document_others_name"];
        $hidden_attachment = $_POST["hidden_attachment"];

        $company_document['company_code'] = $company_code;
    	$company_document['document_type'] = $document_type;
    	$company_document['document_others_name'] = $document_others_name;

        $filesCount = count($_FILES['attachment']['name']);
        $pv_attachment = array();

        for($i = 0; $i < $filesCount; $i++)
        {  
            $_FILES['uploadimage']['name'] = $_FILES['attachment']['name'][$i];
            $_FILES['uploadimage']['type'] = $_FILES['attachment']['type'][$i];
            $_FILES['uploadimage']['tmp_name'] = $_FILES['attachment']['tmp_name'][$i];
            $_FILES['uploadimage']['error'] = $_FILES['attachment']['error'][$i];
            $_FILES['uploadimage']['size'] = $_FILES['attachment']['size'][$i];

            $uploadPath = './uploads/company_document';
            $config['upload_path'] = $uploadPath;
            $config['allowed_types'] = '*';
            
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if($this->upload->do_upload('uploadimage'))
            {
                $fileData = $this->upload->data();
               
                $pv_attachment[] = $fileData['file_name'];
            }

            $attachment = json_encode($pv_attachment);
        }

        if($hidden_attachment != "")
        {
            $company_document['attachment'] = $hidden_attachment;
        }
        else
        {   
            if($attachment != NULL)
            {
                $company_document['attachment'] = $attachment;
            }
            else
            {
                $company_document['attachment'] = "[]";
            }
        }

        if($_POST["document_others_name"] != "")
        {
        	$document_name = $_POST["document_others_name"];
        }
        else
        {
        	$document_name = $_POST["document_type"];
        }

        $document_type_result = $this->db->query("select * from company_document where id='".$document_type_id."' AND status = '0'");

        if ($document_type_result->num_rows() > 0) 
        {
        	$this->db->update("company_document",$company_document,array("id" => $document_type_id));

        	$this->save_audit_trail("Clients", "Company Document", "Document ".$document_name." is updated.", $_POST['company_code']);
        }
        else
        {
			$this->db->insert("company_document",$company_document);

			$this->save_audit_trail("Clients", "Company Document", "Document ".$document_name." is added.", $_POST['company_code']);
        }

        $this->data['list_of_company_document'] = $this->master_model->get_all_list_of_company_document($company_code);

        echo json_encode($this->data);
    }

    public function delete_company_document()
    {
    	$company_document_id = $_POST["company_document_id"];
    	$company_code = $_POST["company_code"];

    	$q = $this->db->get_where("company_document", array("id" => $company_document_id));

		if ($q->num_rows())
		{
			$company_document_array = $q->result_array();
			if($company_document_array[0]["document_others_name"] != "")
	        {
	        	$document_name = $company_document_array[0]["document_others_name"];
	        }
	        else
	        {
	        	$document_name = $company_document_array[0]["document_type"];
	        }
			$this->save_audit_trail("Clients", "Company Document", "Document ".$document_name." is deleted.", $company_document_array[0]["company_code"]);
		}

    	$company_document["status"] = 1;
    	$this->db->update("company_document",$company_document,array("id" => $company_document_id));

    	$this->data['list_of_company_document'] = $this->master_model->get_all_list_of_company_document($company_code);

        echo json_encode($this->data);
    }

    public function save_audit_trail($modules, $events, $actions, $company_code = null)
    {
    	$secretary_audit_trail["user_id"] = $this->session->userdata("user_id");
		$secretary_audit_trail["modules"] = $modules;
		$secretary_audit_trail["events"] = $events;
		if($modules == "Clients" && $company_code != null)
		{
			$q = $this->db->get_where("client", array("company_code" => $company_code));
			$client_array = $q->result_array();
			$client_name = $this->encryption->decrypt($client_array[0]["company_name"])." ";
		}
		else
		{
			$client_name = "";
		}
		$secretary_audit_trail["actions"] = $client_name.$actions;
		$this->db->insert("secretary_audit_trail",$secretary_audit_trail);
    }

    public function import_client_to_quickbook()
    {
    	$currency_name = $_POST["client_qb_currency"];

    	if($this->session->userdata('refresh_token_value'))
    	{
    		try {
		    	// Prep Data Services
				$dataService = DataService::Configure(array(
					'auth_mode' => 'oauth2',
					'ClientID' => $this->quickbook_clientID,
					'ClientSecret' => $this->quickbook_clientSecret,
					'accessTokenKey' => $this->session->userdata('access_token_value'),
					'refreshTokenKey' => $this->session->userdata('refresh_token_value'),
					'QBORealmID' => $this->session->userdata('qb_company_id'), //"The Company ID which the app wants to access"
					'baseUrl' => $this->quickbookBaseUrl
				));

				$dataService->throwExceptionOnError(true);

				$client_query = $this->db->query("SELECT client.*, client_contact_info_email.email, client_qb_id.qb_customer_id FROM client LEFT JOIN client_contact_info ON client_contact_info.company_code = client.company_code LEFT JOIN client_contact_info_email ON client_contact_info_email.client_contact_info_id = client_contact_info.id AND client_contact_info_email.primary_email = 1 LEFT JOIN client_qb_id ON client_qb_id.company_code = client.company_code AND client_qb_id.currency_name = '".$currency_name."' WHERE client.deleted = 0 AND client.postal_code != '' AND client_qb_id.qb_customer_id IS NULL AND client_qb_id.qb_company_id = '".$this->session->userdata('qb_company_id')."' LIMIT 20"); //30 // AND client_qb_id.qb_customer_id = '' LIMIT 1
				//print_r($client_query->result_array());
				if ($client_query->num_rows() > 0) 
		        {
					$client_query = $client_query->result_array();
					//print_r($client_query);
					foreach ($client_query as $row) 
					{
						// Add unit
						if(!empty($row['unit_no1']) && !empty($row['unit_no2']))
						{
							$unit = '#' . $row['unit_no1'] . '-' . $row['unit_no2'];
						}

						// Add building
						if(!empty($row['building_name']) && !empty($unit))
						{
							$unit_building_name = $unit . ' ' . $row['building_name'];
						}
						elseif(!empty($unit))
						{
							$unit_building_name = $unit;
						}
						elseif(!empty($row['building_name']))
						{
							$unit_building_name = $row['building_name'];
						}

						if(!empty($row["currency_name"]))
						{
							$qb_currency_name = $row["currency_name"];
						}
						else
						{
							$qb_currency_name = $currency_name;
						}

						$customer_info = [
							    "BillAddr" => [
							        "Line1" => strtoupper(trim($row["street_name"])),
							        "City" => strtoupper(trim($unit_building_name)),
							        "Country" => "",
							        "CountrySubDivisionCode" => "SINGAPORE",
							        "PostalCode" => strtoupper(trim($row["postal_code"]))
							    ],
							    "CurrencyRef" => [
									"value" => $qb_currency_name
								],
							    "Notes" => "",
							    "Title" => "",
							    "GivenName" => "",
							    "MiddleName" => "",
							    "FamilyName" => "",
							    "Suffix" => "",
							    "FullyQualifiedName" => trim(trim($this->encryption->decrypt($row["company_name"]))." (".$qb_currency_name.")"),
							    "CompanyName" => trim(trim($this->encryption->decrypt($row["company_name"]))." (".$qb_currency_name.")"),
							    "DisplayName" => str_replace(':', "", trim(trim($this->encryption->decrypt($row["company_name"]))." (".$qb_currency_name.")"))
							];

						if(!empty($row['email']))
						{
							$email_to = explode(';', $row["email"]);
					        if(count($email_to) > 0)
					        {
					        	for($t = 0; $t < count($email_to); $t++)
					        	{
									$isvalid = filter_var(trim($email_to[$t]), FILTER_VALIDATE_EMAIL);
									if($isvalid != false)
									{
										if($t == 0)
										{
											$email_in_qb = trim($email_to[$t]);
										}
										else
										{
											$email_in_qb = $email_in_qb.', '.trim($email_to[$t]);
										}
									}
								}
							}

							$email_add = ["PrimaryEmailAddr" => [
									      	"Address" => $email_in_qb
									    ]];
							$customer_info = array_merge($customer_info, $email_add);
						}
						//print_r($customer_info);

						// if($row["qb_customer_id"] != 0)
						// {
							// $customer = $dataService->FindbyId('customer', $row["qb_customer_id"]);
							// $theResourceObj = Customer::update($customer, $customer_info);
							// $resultingObj = $dataService->Update($theResourceObj);
						// }
						// else
						// {
							//Add a new Vendor
							$theResourceObj = Customer::create($customer_info);
							$resultingObj = $dataService->Add($theResourceObj);
						//}

						//print_r($resultingObj);
						$error = $dataService->getLastError();

						if ($error) {
						    if($error->getHttpStatusCode() == "401")
						    {
						    	$refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
						    	if($refresh_token_status)
						    	{
						    		$this->import_client_to_quickbook();
						    	}
						    }
						    else
						    {
						    	echo json_encode(array("Status" => 3, 'message' => $error->getIntuitErrorMessage(), 'title' => 'Error'));
						    }
						}
						else {
						    $data["qb_company_id"] = $this->session->userdata('qb_company_id');
						    $data["company_code"] = $row["company_code"];
						    $data["qb_customer_id"] = $resultingObj->Id;
						    $data["currency_name"] = $qb_currency_name;
						    $data["qb_json_data"] = json_encode($resultingObj);

						    if(!empty($row["qb_customer_id"]))
							{
								$this->db->update("client_qb_id",$data,array("qb_customer_id" => $row["qb_customer_id"], "qb_company_id" => $this->session->userdata('qb_company_id')));
							}
							else
							{
								$this->db->insert("client_qb_id",$data);
							}
						}
					}

					$this->save_audit_trail("Clients", "QuickBooks", $this->session->userdata('first_name'). " " . $this->session->userdata('last_name') . " import 20 clients to QuickBooks Online.");

					echo json_encode(array("Status" => 1, 'message' => "Import Successfully", 'title' => 'Success'));
				}
			}
			catch (Exception $e){
				$refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
		    	if($refresh_token_status)
		    	{
		    		$this->import_client_to_quickbook();
		    	}
			}
		}
		else
		{
			echo json_encode(array("Status" => 2, 'message' => 'Please login to Quickbook Online first before proceed this step.', 'title' => 'Warning'));
		}
    }
}
