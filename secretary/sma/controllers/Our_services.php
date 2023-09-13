<?php defined('BASEPATH') OR exit('No direct script access allowed');

use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Item;

class Our_services extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        $this->load->library(array('session', 'form_validation'));
        $this->load->model(array('master_model', 'db_model', 'extra_model', 'quickbook_auth_model'));
    }

    public function index()
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $bc = array(array('link' => '#', 'page' => lang('Our Services')));
        $meta = array('page_title' => lang('Our Services'), 'bc' => $bc, 'page_name' => 'Our Services');

        $this->data["qb_company_id"] = $this->session->userdata('qb_company_id');
        $this->data['template'] = $this->db_model->get_all_template();  
        $this->data['user_admin_code_id'] = $this->session->userdata("user_admin_code_id");

        $this->page_construct('our_services.php', $meta, $this->data);
    }

    public function get_our_service_data($id, $isAdmin, $isIndividual)
    {
        $data = $this->master_model->get_our_service_info($id, $isAdmin, $isIndividual);

        if($data == false)
        {
            $total = 0;
        }
        else
        {
            $total = count($data);
        }

        echo json_encode(array("draw" => 1, "recordsTotal" => $total,"recordsFiltered" => $total, "data" => $data));
    }

    public function save_our_service_data()
    {
        for($i = 0; $i < count($_POST['user_admin_code_id']); $i++ )
        {   
            $this->form_validation->set_rules('service_name['.$i.']', 'Account Number', 'required');
            $this->form_validation->set_rules('invoice_description['.$i.']', 'Invoice Description', 'required');
            $this->form_validation->set_rules('amount['.$i.']', 'Amount', 'required');

            if(isset($_POST['service_postal_code'][$i]))
            {
                $this->form_validation->set_rules('service_postal_code['.$i.']', 'Postal Code', 'required|numeric');
            }
            if(isset($_POST['service_street_name'][$i]))
            {
                $this->form_validation->set_rules('service_street_name['.$i.']', 'Street Name', 'required');
            }
            if(isset($_POST['service_proposal_description'][$i]))
            {
                $this->form_validation->set_rules('service_proposal_description['.$i.']', 'Service Proposal Description', 'required');
            }
            if(isset($_POST['foreign_address_1'][$i]))
            {
                $this->form_validation->set_rules('foreign_address_1['.$i.']', 'Foreign Address 1', 'required');
            }
            if(isset($_POST['foreign_address_2'][$i]))
            {
                $this->form_validation->set_rules('foreign_address_2['.$i.']', 'Foreign Address 2', 'required');
            }

            if ($this->form_validation->run() == FALSE || $_POST['calculate_by_quantity_rate'][$i] == 0 || $_POST['service_proposal_letter_required'][$i] == 0 || $_POST['engagement_letter_required'][$i] == 0 || $_POST['currency'][$i] == 0 || $_POST['service_type'][$i] == 0 || $_POST['unit_pricing'][$i] == 0 || (isset($_POST['display_in_se'][$i]) && $_POST['display_in_se'][$i] == 0) || (isset($_POST['engagement_letter_list'][$i]) && $_POST['engagement_letter_list'][$i] == 0))
            {
                $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

                if($_POST['service_type'][$i] == 0)
                {
                    $service_type_error = "The Service Type field is required.";
                }
                else
                {
                    $service_type_error = "";
                }

                if($_POST['unit_pricing'][$i] == 0)
                {
                    $unit_pricing_error = "The Unit Pricing field is required.";
                }
                else
                {
                    $unit_pricing_error = "";
                }

                if($_POST['currency'][$i] == 0)
                {
                    $currency_error = "The Currency field is required.";
                }
                else
                {
                    $currency_error = "";
                }

                if($_POST['calculate_by_quantity_rate'][$i] == 0)
                {
                    $cal_by_quantity_rate_error = "The Calculate By Quantity/Rate field is required.";
                }
                else
                {
                    $cal_by_quantity_rate_error = "";
                }

                if($_POST['service_proposal_letter_required'][$i] == 0)
                {
                    $sp_letter_required_error = "The Service Proposal Letter Required field is required.";
                }
                else
                {
                    $sp_letter_required_error = "";
                }

                if($_POST['engagement_letter_required'][$i] == 0)
                {
                    $el_required_error = "The Engagement Letter Required field is required.";
                }
                else
                {
                    $el_required_error = "";
                }

                if(isset($_POST['engagement_letter_list'][$i]))
                {
                    if($_POST['engagement_letter_list'][$i] == 0)
                    {
                        $engagement_letter_list_error = "The Engagement Letter List is required.";
                    }
                    else
                    {
                        $engagement_letter_list_error = "";
                    }
                }
                else
                {
                    $engagement_letter_list_error = "";
                }

                if(isset($_POST['display_in_se'][$i]))
                {
                    if($_POST['display_in_se'][$i] == 0)
                    {
                        $display_in_se_error = "The Display in Service Engagement field is required.";
                    }
                    else
                    {
                        $display_in_se_error = "";
                    }
                }
                else
                {
                    $display_in_se_error = "";
                }

                // if(!preg_match('/^[0-9]{1,3}(,[0-9]{3})*(.[0-9])*$/', $_POST['amount'][$i]) && $_POST['amount'][$i] != null)
                // {
                //     $amount_error = "The Amount field must be numeric.";
                // }
                // else
                // {
                //     $amount_error = strip_tags(form_error('amount['.$i.']'));
                // }

                $error = array(
                    'service_type_name' => $service_type_error,
                    'calculate_by_quantity_rate' => $cal_by_quantity_rate_error,
                    'service_name' => strip_tags(form_error('service_name['.$i.']')),
                    'invoice_description' => strip_tags(form_error('invoice_description['.$i.']')),
                    'amount' => strip_tags(form_error('amount['.$i.']')),
                    'currency' => $currency_error,
                    'unit_pricing' => $unit_pricing_error,
                    'service_postal_code' => strip_tags(form_error('service_postal_code['.$i.']')),
                    'service_street_name' => strip_tags(form_error('service_street_name['.$i.']')),
                    'foreign_address_1' => strip_tags(form_error('foreign_address_1['.$i.']')),
                    'foreign_address_2' => strip_tags(form_error('foreign_address_2['.$i.']')),
                    'service_proposal_description' => strip_tags(form_error('service_proposal_description['.$i.']')),
                    'engagement_letter_list' => $engagement_letter_list_error,
                    'sp_letter_required_error' => $sp_letter_required_error,
                    'el_required_error' => $el_required_error,
                    'display_in_se' => $display_in_se_error,
                );

                echo json_encode(array("Status" => 0, 'message' => 'Please complete all required field.', 'title' => 'Error', "error" => $error));
            }
            else
            {
                $data['user_admin_code_id'] = $_POST['user_admin_code_id'][$i];
                $data['calculate_by_quantity_rate']=$_POST['calculate_by_quantity_rate'][$i];
                $data['service_type']=$_POST['service_type'][$i];
                $data['service_name']=$_POST['service_name'][$i];
                $data['invoice_description']=$_POST['invoice_description'][$i];
                $data['amount']=round((float)(str_replace(',', '', $_POST['amount'][$i])), 2);
                $data['unit_pricing']=$_POST['unit_pricing'][$i];
                $data['currency']=$_POST['currency'][$i];
                $data['sp_required_id']=$_POST['service_proposal_letter_required'][$i];
                $data['el_required_id']=$_POST['engagement_letter_required'][$i];
                $data['created_by']=$this->session->userdata('user_id');

                if(isset($_POST['jurisdiction'][$i]))
                {
                    $register_address['jurisdiction_id']=$_POST['jurisdiction'][$i];
                }

                if(isset($_POST['service_postal_code'][$i]))
                {
                    $register_address['postal_code']=$_POST['service_postal_code'][$i];
                }
                else
                {
                    $register_address['postal_code']="";
                }

                if(isset($_POST['service_street_name'][$i]))
                {
                    $register_address['street_name']=$_POST['service_street_name'][$i];
                }
                else
                {
                    $register_address['street_name']="";
                }

                if(isset($_POST['service_building_name'][$i]))
                {
                    $register_address['building_name']=$_POST['service_building_name'][$i];
                }
                else
                {
                    $register_address['building_name']="";
                }

                if(isset($_POST['service_unit_no1'][$i]))
                {
                    $register_address['unit_no1']=$_POST['service_unit_no1'][$i];
                }
                else
                {
                    $register_address['unit_no1']="";
                }

                if(isset($_POST['service_unit_no2'][$i]))
                {
                    $register_address['unit_no2']=$_POST['service_unit_no2'][$i];
                }
                else
                {
                    $register_address['unit_no2']="";
                }

                if(isset($_POST['foreign_address_1'][$i]))
                {
                    $register_address['foreign_address_1']=$_POST['foreign_address_1'][$i];
                }
                else
                {
                    $register_address['foreign_address_1']="";
                }

                if(isset($_POST['foreign_address_2'][$i]))
                {
                    $register_address['foreign_address_2']=$_POST['foreign_address_2'][$i];
                }
                else
                {
                    $register_address['foreign_address_2']="";
                }

                if(isset($_POST['foreign_address_3'][$i]))
                {
                    $register_address['foreign_address_3']=$_POST['foreign_address_3'][$i];
                }
                else
                {
                    $register_address['foreign_address_3']="";
                }

                if(isset($_POST['service_proposal_description'][$i]))
                {
                    $data['service_proposal_description']=$_POST['service_proposal_description'][$i];
                }

                if(isset($_POST['engagement_letter_list'][$i]))
                {
                    $data['engagement_letter_list_id']=$_POST['engagement_letter_list'][$i];
                }

                if(isset($_POST['display_in_se'][$i]))
                {
                    $data['display_in_se_id']=$_POST['display_in_se'][$i];
                }

                $jurisdiction_id = $_POST["jurisdiction_id"];
                $jurisdiction = $_POST["jurisdiction"];
                $category = $_POST["category"];

                $q = $this->db->get_where("our_service_info", array("id" => $_POST['our_service_id'][$i]));

                if (!$q->num_rows())
                {   
                    $check_service_name = $this->db->get_where("our_service_info", array("service_name" => $_POST['service_name'][$i], "user_admin_code_id" => $_POST['user_admin_code_id'][$i], "deleted" => 0));

                    if (!$check_service_name->num_rows())
                    {
                        if ($this->Admin && !$this->Individual)
                        {
                            $data['approved'] = 1;
                            $data['click_button_approve_or_reject'] = 1;
                        }

                        $this->db->insert("our_service_info", $data);
                        $insert_our_service_info_id = $this->db->insert_id();

                        $this->save_audit_trail("Our Services", "QuickBooks", $this->session->userdata('first_name'). " " . $this->session->userdata('last_name') . " add ".$_POST['service_name'][$i]." services.");

                        if (!$this->Admin && !$this->Individual)
                        {
                            $this->sendEmailApproval($insert_our_service_info_id, $_POST);
                        }

                        if(isset($_POST['service_postal_code'][$i]) || isset($_POST['foreign_address_1'][$i]) || isset($_POST['foreign_address_2'][$i]))
                        {
                            $register_address['our_service_info_id'] = $insert_our_service_info_id;
                            $this->db->insert("our_service_registration_address",$register_address);
                        }

                        //GST
                        foreach($jurisdiction_id as $key => $value)
                        {
                            $gst['our_service_info_id'] = $insert_our_service_info_id;
                            $gst['jurisdiction_id'] = $value;
                            $gst['category_id'] = $category[$key];

                            $this->db->insert("our_service_gst",$gst);
                        }

                        $this->db->select('our_service_info.*, billing_info_service_category.category_description as service_type_name, unit_pricing.unit_pricing_name, our_service_registration_address.postal_code, our_service_registration_address.street_name, our_service_registration_address.building_name, our_service_registration_address.unit_no1, our_service_registration_address.unit_no2,
                            our_service_registration_address.jurisdiction_id,
                            our_service_registration_address.foreign_address_1,
                            our_service_registration_address.foreign_address_3,
                            our_service_registration_address.foreign_address_2,
                            gst_jurisdiction.jurisdiction as jurisdiction_name, our_service_qb_info.qb_item_id');
                        $this->db->from('our_service_info');
                        $this->db->join('billing_info_service_category', 'billing_info_service_category.id = our_service_info.service_type', 'left');
                        $this->db->join('unit_pricing', 'unit_pricing.id = our_service_info.unit_pricing', 'left');
                        $this->db->join('our_service_registration_address', 'our_service_registration_address.our_service_info_id = our_service_info.id', 'left');
                        $this->db->join('gst_jurisdiction', 'gst_jurisdiction.id = our_service_registration_address.jurisdiction_id', 'left');
                        $this->db->join('our_service_qb_info', "our_service_qb_info.our_service_info_id = our_service_info.id and our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."'", 'left');
                        $this->db->where('our_service_info.id', $insert_our_service_info_id);
                        $row_of_our_service_info = $this->db->get();
                        $row_of_our_service_info_result = $row_of_our_service_info->result();
                        //$row_of_our_service_info = $transaction_our_service_info->result_array();

                        $this->db->select('our_service_gst.*');
                        $this->db->from('our_service_gst');
                        $this->db->where('our_service_gst.our_service_info_id', $insert_our_service_info_id);
                        $row_of_our_service_gst = $this->db->get();
                        $row_of_our_service_gst_result = $row_of_our_service_gst->result();

                        $row_of_our_service_info_result[0]->our_service_gst = $row_of_our_service_gst_result;

                        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_our_service_info_id" => $insert_our_service_info_id, "row_of_our_service_info" => $row_of_our_service_info_result, "enable_add_row" => true));
                    }
                    else
                    {
                        echo json_encode(array("Status" => 2, 'message' => 'Cannot have same service name under this firm.', 'title' => 'Error'));
                    }
                }
                else
                {
                    $check_service_name = $this->db->get_where("our_service_info", array("service_name" => $_POST['service_name'][$i], "user_admin_code_id" => $_POST['user_admin_code_id'][$i], "id !=" => $_POST['our_service_id'][$i], "deleted" => 0));

                    if (!$check_service_name->num_rows())
                    {
                        $this->db->update("our_service_info",$data,array("id" => $_POST['our_service_id'][$i]));

                        $this->save_audit_trail("Our Services", "QuickBooks", $this->session->userdata('first_name'). " " . $this->session->userdata('last_name') . " edit ".$_POST['service_name'][$i]." services.");

                        if(isset($_POST['service_postal_code'][$i]) || isset($_POST['foreign_address_1'][$i]) || isset($_POST['foreign_address_2'][$i]))
                        {
                            $register_address['our_service_info_id'] = $_POST['our_service_id'][$i];
                            $this->db->update("our_service_registration_address",$register_address,array("our_service_info_id" => $_POST['our_service_id'][$i]));
                        }

                        foreach($jurisdiction_id as $key => $value)
                        {
                            $gst['our_service_info_id'] = $_POST['our_service_id'][$i];
                            $gst['jurisdiction_id'] = $value;
                            $gst['category_id'] = $category[$key];

                            $check_gst = $this->db->get_where("our_service_gst", array("our_service_info_id" => $_POST['our_service_id'][$i], "jurisdiction_id" => $value));

                            if (!$check_gst->num_rows())
                            {
                                $this->db->insert("our_service_gst",$gst);
                            }
                            else
                            {
                                $this->db->update("our_service_gst", $gst, array("our_service_info_id" => $_POST['our_service_id'][$i], "jurisdiction_id" => $value));
                            }
                        }

                        $this->db->select('our_service_info.*, billing_info_service_category.category_description as service_type_name, unit_pricing.unit_pricing_name, our_service_registration_address.postal_code, our_service_registration_address.street_name, our_service_registration_address.building_name, our_service_registration_address.unit_no1, our_service_registration_address.unit_no2,
                            our_service_registration_address.jurisdiction_id,
                            our_service_registration_address.foreign_address_1,
                            our_service_registration_address.foreign_address_3,
                            our_service_registration_address.foreign_address_2,
                            gst_jurisdiction.jurisdiction as jurisdiction_name, our_service_qb_info.qb_item_id');
                        $this->db->from('our_service_info');
                        $this->db->join('billing_info_service_category', 'billing_info_service_category.id = our_service_info.service_type', 'left');
                        $this->db->join('unit_pricing', 'unit_pricing.id = our_service_info.unit_pricing', 'left');
                        $this->db->join('our_service_registration_address', 'our_service_registration_address.our_service_info_id = our_service_info.id', 'left');
                        $this->db->join('gst_jurisdiction', 'gst_jurisdiction.id = our_service_registration_address.jurisdiction_id', 'left');
                        $this->db->join('our_service_qb_info', "our_service_qb_info.our_service_info_id = our_service_info.id and our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."'", 'left');
                        $this->db->where('our_service_info.id', $_POST['our_service_id'][$i]);
                        $row_of_our_service_info = $this->db->get();
                        $row_of_our_service_info_result = $row_of_our_service_info->result();
                        //$row_of_our_service_info = $transaction_our_service_info->result_array();

                        $this->db->select('our_service_gst.*');
                        $this->db->from('our_service_gst');
                        $this->db->where('our_service_gst.our_service_info_id', $_POST['our_service_id'][$i]);
                        $row_of_our_service_gst = $this->db->get();
                        $row_of_our_service_gst_result = $row_of_our_service_gst->result();

                        $row_of_our_service_info_result[0]->our_service_gst = $row_of_our_service_gst_result;

                        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "row_of_our_service_info" => $row_of_our_service_info_result, "enable_add_row" => false));
                    }
                    else
                    {
                        echo json_encode(array("Status" => 2, 'message' => 'Cannot have same service name under this firm.', 'title' => 'Error'));
                    }

                }
            }
        }
    }

    public function approve_our_service_data()
    {
        $data["approved"] = 1;
        $data["click_button_approve_or_reject"] = 1;

        $this->db->update("our_service_info",$data,array("id" => $_POST['our_service_id'][0]));

        $this->extra_model->send_approval_result($_POST['our_service_id'][0], "Approve");

        $this->db->select('our_service_info.*, billing_info_service_category.category_description as service_type_name, unit_pricing.unit_pricing_name, our_service_registration_address.postal_code, our_service_registration_address.street_name, our_service_registration_address.building_name, our_service_registration_address.unit_no1, our_service_registration_address.unit_no2');
        $this->db->from('our_service_info');
        $this->db->join('billing_info_service_category', 'billing_info_service_category.id = our_service_info.service_type', 'left');
        $this->db->join('unit_pricing', 'unit_pricing.id = our_service_info.unit_pricing', 'left');
        $this->db->join('our_service_registration_address', 'our_service_registration_address.our_service_info_id = our_service_info.id', 'left');
        $this->db->where('our_service_info.id', $_POST['our_service_id'][0]);
        $row_of_our_service_info = $this->db->get();
        $row_of_our_service_info_result = $row_of_our_service_info->result();

        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "row_of_our_service_info" => $row_of_our_service_info_result, "enable_add_row" => true));
    }

    public function reject_our_service_data()
    {
        $data["approved"] = 0;
        $data["click_button_approve_or_reject"] = 1;

        $this->db->update("our_service_info",$data,array("id" => $_POST['our_service_id'][0]));

        $this->extra_model->send_approval_result($_POST['our_service_id'][0], "Reject");

        $this->db->select('our_service_info.*, billing_info_service_category.category_description as service_type_name, unit_pricing.unit_pricing_name, our_service_registration_address.postal_code, our_service_registration_address.street_name, our_service_registration_address.building_name, our_service_registration_address.unit_no1, our_service_registration_address.unit_no2');
        $this->db->from('our_service_info');
        $this->db->join('billing_info_service_category', 'billing_info_service_category.id = our_service_info.service_type', 'left');
        $this->db->join('unit_pricing', 'unit_pricing.id = our_service_info.unit_pricing', 'left');
        $this->db->join('our_service_registration_address', 'our_service_registration_address.our_service_info_id = our_service_info.id', 'left');
        $this->db->where('our_service_info.id', $_POST['our_service_id'][0]);
        $row_of_our_service_info = $this->db->get();
        $row_of_our_service_info_result = $row_of_our_service_info->result();

        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "row_of_our_service_info" => $row_of_our_service_info_result, "enable_add_row" => true));
    }

    public function sendEmailApproval($insert_our_service_info_id = null, $data = null)
    {
        $get_our_service_info_list = $this->db->get_where("our_service_info", array("id" => $insert_our_service_info_id));
        $get_our_service_info_list = $get_our_service_info_list->result_array();

        $get_user_list = $this->db->get_where("users", array("id" => $get_our_service_info_list[0]["created_by"]));
        $get_user_list = $get_user_list->result_array();

        $requested_by = $get_user_list[0]["last_name"]." ".$get_user_list[0]["first_name"];

        $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');

        $apiInstance = new SendinBlue\Client\Api\SMTPApi(
          // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
          // This is optional, `GuzzleHttp\Client` will be used as default.
          new GuzzleHttp\Client(),
          $config
        );

        $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail(); // \SendinBlue\Client\Model\SendSmtpEmail | Values to send a transactional email
        $sendSmtpEmail['subject'] = "Approval for Services";
        $sender_email = json_decode('{"name":"ACUMEN ALPHA ADVISORY","email":"admin@aaa-global.com"}', true);//{"name":"SIMPEX CONSULTING (S) PTE. LTD.","email":"admin@aaa-global.com"}
        $sendSmtpEmail['sender'] = $sender_email;
        $sendSmtpEmail['to'] = json_decode('[{"email":"woellywilliam@aaa-global.com"}]', true);
        // if($email_queue_info[$i]['cc'] != null)
        // {
        // $sendSmtpEmail['cc'] = json_decode($email_queue_info[$i]['cc'], true);
        // }
        $get_billing_info_service_category = $this->db->get_where("billing_info_service_category", array("id" => $data['service_type'][0]));
        $get_billing_info_service_category = $get_billing_info_service_category->result_array();
        $service_type = $get_billing_info_service_category[0]["category_description"];

        if($data['calculate_by_quantity_rate'][0] == "1")
        {
            $calculate_by_quantity_rate = "Yes";
        }
        else
        {
            $calculate_by_quantity_rate = "No";
        }

        if($data['service_proposal_letter_required'][0] == "1")
        {
            $service_proposal_letter_required = "Yes";
        }
        else
        {
            $service_proposal_letter_required = "No";
        }

        if($data['engagement_letter_required'][0] == "1")
        {
            $engagement_letter_required = "Yes";
        }
        else
        {
            $engagement_letter_required = "No";
        }

        if(isset($data['engagement_letter_list'][0]))
        {
            $get_engagement_letter_list = $this->db->get_where("engagement_letter_list", array("id" => $data['engagement_letter_list'][0]));
            $get_engagement_letter_list = $get_engagement_letter_list->result_array();
            $engagement_letter_list_name = $get_engagement_letter_list[0]["engagement_letter_list_name"];
        }
        else
        {
            $engagement_letter_list_name = "-";
        }

        $get_currency_list = $this->db->get_where("currency", array("id" => $data['currency'][0]));
        $get_currency_list = $get_currency_list->result_array();
        $currency_name = $get_currency_list[0]["currency"];

        $get_unit_pricing_list = $this->db->get_where("unit_pricing", array("id" => $data['unit_pricing'][0]));
        $get_unit_pricing_list = $get_unit_pricing_list->result_array();
        $unit_pricing_name = $get_unit_pricing_list[0]["unit_pricing_name"];

        if(isset($data['display_in_se'][0]))
        {
            if($data['display_in_se'][0] == "1")
            {
                $display_in_se = "Always Display";
            }
            else if($data['display_in_se'][0] == "2")
            {
                $display_in_se = "Upon Selection";
            }
            else
            {
                $display_in_se = "";
            }
        }

        $our_services_detail = '
        <p><strong>Service Detail</strong>:</p>
        <table style="height: 202px; width: 609px; border: 1px solid black; border-collapse: collapse;">
            <tbody>
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black; width: 214px;">
                        <p><strong>Service Type</strong></p>
                    </td>
                    <td style="border: 1px solid black; width: 484px;">
                        <p>'.$service_type.'</p>
                    </td>
                </tr>
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black; width: 214px;">
                        <p><strong>Calculate by Quantity/Rate</strong></p>
                    </td>
                    <td style="border: 1px solid black; width: 484px;">
                        <p>'.$calculate_by_quantity_rate.'</p>
                    </td>
                </tr>
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black; width: 214px;">
                        <p><strong>Service Name</strong></p>
                    </td>
                    <td style="border: 1px solid black; width: 484px;">
                        <p>'.$data['service_name'][0].'</p>
                    </td>
                </tr>
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black; width: 214px;">
                        <p><strong>Invoice Description</strong></p>
                    </td>
                    <td style="border: 1px solid black; width: 484px;">
                        <p>'.nl2br($data['invoice_description'][0]).'</p>
                    </td>
                </tr>
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black; width: 214px;">
                        <p><strong>Service Proposal Letter Required</strong></p>
                    </td>
                    <td style="border: 1px solid black; width: 484px;">
                        <p>'.$service_proposal_letter_required.'</p>
                    </td>
                </tr>
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black; width: 214px;">
                        <p><strong>Service Proposal Description</strong></p>
                    </td>
                    <td style="border: 1px solid black; width: 484px;">
                        <p>'.(($data['service_proposal_description'][0] != '')?nl2br($data['service_proposal_description'][0]):'-').'</p>
                    </td>
                </tr>
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black; width: 214px;">
                        <p><strong>Engagement Letter Required</strong></p>
                    </td>
                    <td style="border: 1px solid black; width: 484px;">
                        <p>'.$engagement_letter_required.'</p>
                    </td>
                </tr>
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black; width: 214px;">
                        <p><strong>Engagement Letter List</strong></p>
                    </td>
                    <td style="border: 1px solid black; width: 484px;">
                        <p>'.$engagement_letter_list_name.'</p>
                    </td>
                </tr>
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black; width: 214px;">
                        <p><strong>Currency</strong></p>
                    </td>
                    <td style="border: 1px solid black; width: 484px;">
                        <p>'.$currency_name.'</p>
                    </td>
                </tr>
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black; width: 214px;">
                        <p><strong>Amount</strong></p>
                    </td>
                    <td style="border: 1px solid black; width: 484px;">
                        <p>'.number_format(round((float)(str_replace(',', '', $data['amount'][0])), 2),2).'</p>
                    </td>
                </tr>
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black; width: 214px;">
                        <p><strong>Unit Pricing (Per)</strong></p>
                    </td>
                    <td style="border: 1px solid black; width: 484px;">
                        <p>'.$unit_pricing_name.'</p>
                    </td>
                </tr>
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black; width: 214px;">
                        <p><strong>Display in Service Engagement</strong></p>
                    </td>
                    <td style="border: 1px solid black; width: 484px;">
                        <p>'.$display_in_se.'</p>
                    </td>
                </tr>
            </tbody>
        </table>';

        if(isset($data['service_postal_code'][0]))
        {
            $postal_code = $_POST['service_postal_code'][0];
        }

        if(isset($data['service_street_name'][0]))
        {
            $street_name=$_POST['service_street_name'][0];
        }

        if(isset($data['service_building_name'][0]))
        {
            $building_name=$_POST['service_building_name'][0];
        }
        else
        {
            $building_name = "-";
        }

        if(isset($data['service_unit_no1'][0]))
        {
            $unit_no1=$_POST['service_unit_no1'][0];
        }
        else
        {
            $unit_no1 = "";
        }

        if(isset($data['service_unit_no2'][0]))
        {
            $unit_no2=$_POST['service_unit_no2'][0];
        }
        else
        {
            $unit_no2 = "";
        }

        if(isset($data['service_postal_code'][0]))
        {
            $address_detail = '
            <p><strong>Registered Office Address Detail</strong>:</p>
            <table style="width: 609px; border: 1px solid black; border-collapse: collapse;">
                <tbody>
                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black; width: 214px; height: 20px;">
                            <p><strong>Postal Code</strong></p>
                        </td>
                        <td style="border: 1px solid black; width: 484px; height: 20px;">
                            <p>'.$postal_code.'</p>
                        </td>
                    </tr>
                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black; width: 214px; height: 20px;">
                            <p><strong>Street Name</strong></p>
                        </td>
                        <td style="border: 1px solid black; width: 484px; height: 20px;">
                            <p>'.$street_name.'</p>
                        </td>
                    </tr>
                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black; width: 214px; height: 20px;">
                            <p><strong>Building Name</strong></p>
                        </td>
                        <td style="border: 1px solid black; width: 484px; height: 20px;">
                            <p>'.$building_name.'</p>
                        </td>
                    </tr>
                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black; width: 214px; height: 20px;">
                            <p><strong>Unit No</strong></p>
                        </td>
                        <td style="border: 1px solid black; width: 484px; height: 20px;">
                            <p>'.$unit_no1 .' - '.$unit_no2.'</p>
                        </td>
                    </tr>
                </tbody>
            </table>';
        }
        else
        {
            $address_detail = '';
        }

        $our_services_detail = $our_services_detail . $address_detail;

        $tr_gst_detail_table = "";
        foreach($data["jurisdiction_id"] as $key => $value)
        {
            $gst['category_id'] = $data["category"][$key];
            if($data["category"][$key] != "0")
            {
                $gst_category_query = $this->db->query("select gst_category.id as gst_category_id, gst_category.category, gst_category_info.*, gst_jurisdiction.jurisdiction from gst_category LEFT JOIN gst_category_info ON gst_category_info.gst_category_id = gst_category.id AND gst_category_info.deleted = 0 LEFT JOIN gst_jurisdiction ON gst_jurisdiction.id = gst_category_info.jurisdiction_id where gst_category.id = '".$data["category"][$key]."'");
                $gst_category_query = $gst_category_query->result_array();
                $gst_category = $gst_category_query[0]["category"];
            }
            else
            {
                $gst_category = "";
            }

            $tr_gst_detail_table = $tr_gst_detail_table . '<tr style="border: 1px solid black;">
                        <td style="border: 1px solid black; width: 214px; height: 20px;">
                            <p><strong>'.$data["jurisdiction"][$key].'</strong></p>
                        </td>
                        <td style="border: 1px solid black; width: 484px; height: 20px;">
                            <p>'.$gst_category.'</p>
                        </td>
                    </tr>';
        }

        $gst_detail = '
            <p><strong>GST Detail</strong>:</p>
            <table style="width: 609px; border: 1px solid black; border-collapse: collapse;">
                <tbody>
                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black; width: 214px; height: 20px;">
                            <p><strong>Jurisdiction</strong></p>
                        </td>
                        <td style="border: 1px solid black; width: 484px; height: 20px;">
                            <p><strong>Category</strong></p>
                        </td>
                    </tr>
                    '.$tr_gst_detail_table.'
                </tbody>
            </table>';
        
        $our_services_detail = $our_services_detail . $gst_detail;

        $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';

        $parse_data = array(
            '$our_services_details' => $our_services_detail,
            '$user_name' => $requested_by,
            '$approval_link' => $protocol . $_SERVER['SERVER_NAME'] ."/secretary/extra/approval/".$insert_our_service_info_id,
            '$reject_link' => $protocol . $_SERVER['SERVER_NAME'] ."/secretary/extra/reject/".$insert_our_service_info_id,
            '$approve_here_pic' => $protocol . $_SERVER['SERVER_NAME'] ."/secretary/img/approve_here.png",
            '$reject_here_pic' => $protocol . $_SERVER['SERVER_NAME'] ."/secretary/img/reject_here.png"
        );
        $msg = file_get_contents('./themes/default/views/email_templates/approval_our_services_email.html');
        $message = $this->parser->parse_string($msg, $parse_data, true);

        $sendSmtpEmail['htmlContent'] = $message;

        try {
          $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
        } catch (Exception $e) {
          echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
        }
    }

    public function delete_our_service_data()
    {
        $id = $_POST['id'];
        $user_admin_code_id = $_POST['user_admin_code_id'];
        if($id != "" && $id != null)
        {
            $check_client_billing_info = $this->db->get_where("client_billing_info", array("service" => $id, "deleted != " => 1));

            if (!$check_client_billing_info->num_rows())
            {
                $deleted["deleted"] = 1;

                $this->db->update("our_service_info", $deleted, array('id'=>$id));
                $this->db->update("our_service_registration_address", $deleted, array('our_service_info_id'=>$id));

                $get_our_service_info_list = $this->db->get_where("our_service_info", array("id" => $id));
                $get_our_service_info_list = $get_our_service_info_list->result_array();

                $this->save_audit_trail("Our Services", "QuickBooks", $this->session->userdata('first_name'). " " . $this->session->userdata('last_name') . " delete ".$get_our_service_info_list[0]["service_name"]." services.");

                echo json_encode(array("Status" => 1));
            }
            else
            {
                echo json_encode(array("Status" => 2));
            }
        }
        else
        {
            echo json_encode(array("Status" => 1));
        }
    }



    public function import_test() 
    {
        // $id = $_POST['id'];
        // $acc_selector_id = $_POST['acc_selector_id'];
        $id = 20;
        $acc_selector_id = 234;

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

                $check_gst_status_query = $this->db->query("SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox, gst_jurisdiction.jurisdiction as jurisdiction_name FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id LEFT JOIN gst_jurisdiction ON gst_jurisdiction.id = firm.jurisdiction_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = '".$this->session->userdata("firm_id")."'");

                if ($check_gst_status_query->num_rows() > 0) 
                {
                    $check_gst_status_arr = $check_gst_status_query->result_array();
                    $our_service_info = $this->db->query("SELECT our_service_info.*, gst_category.category, our_service_qb_info.qb_item_id, billing_info_service_category_qb.qb_category_id FROM our_service_info LEFT JOIN our_service_gst ON our_service_gst.our_service_info_id = our_service_info.id AND our_service_gst.jurisdiction_id = '".$check_gst_status_arr[0]["jurisdiction_id"]."' LEFT JOIN gst_category ON gst_category.id = our_service_gst.category_id LEFT JOIN billing_info_service_category_qb ON billing_info_service_category_qb.billing_info_service_category_id = our_service_info.service_type AND billing_info_service_category_qb.qb_company_id = '".$this->session->userdata('qb_company_id')."' LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."' WHERE our_service_info.id = '".$id."'");
                }
                else
                {
                    $our_service_info = $this->db->query("SELECT our_service_info.*, our_service_qb_info.qb_item_id, billing_info_service_category_qb.qb_category_id FROM our_service_info LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."' LEFT JOIN billing_info_service_category_qb ON billing_info_service_category_qb.billing_info_service_category_id = our_service_info.service_type AND billing_info_service_category_qb.qb_company_id = '".$this->session->userdata('qb_company_id')."' WHERE our_service_info.id = '".$id."'");
                    //$this->db->get_where("our_service_info", array("id" => $id));
                }

                // print_r($our_service_info->result());
                // echo"11--";
                // exit;

                if ($our_service_info->num_rows())
                {
                    $our_service_info = $our_service_info->result_array();

                    $qb_account_id = $acc_selector_id;

                    if ($check_gst_status_query->num_rows() > 0) 
                    {
                        $taxCode = $this->query_qb_tax_code($our_service_info[0]["category"]);

                        $item_info = [
                                        "TrackQtyOnHand" => false,
                                        "SubItem" => true,
                                        "Name" => $our_service_info[0]["service_name"],
                                        "UnitPrice" => $our_service_info[0]["amount"],
                                        "Description" => $our_service_info[0]["invoice_description"],
                                        "IncomeAccountRef" => [
                                            "value" => $qb_account_id,
                                        ],
                                        "Type" => "Service",
                                        "SalesTaxCodeRef" => [
                                            "value" => $taxCode
                                        ],
                                        "ParentRef" => [
                                            "value" => $our_service_info[0]["qb_category_id"]
                                        ]
                                    ];
                    }
                    else
                    {
                        $item_info = [
                                        "TrackQtyOnHand" => false,
                                        "SubItem" => true,
                                        "Name" => $our_service_info[0]["service_name"],
                                        "UnitPrice" => $our_service_info[0]["amount"],
                                        "Description" => $our_service_info[0]["invoice_description"],
                                        "IncomeAccountRef" => [
                                            "value" => $qb_account_id,
                                        ],
                                        "Type" => "Service",
                                        "ParentRef" => [
                                            "value" => $our_service_info[0]["qb_category_id"]
                                        ]
                                    ];
                    }

                    // print_r($item_info);
                    // echo "\n\n\n";

                    // print_r($our_service_info);
                    // echo "\n\n\n";
                    // print_r($check_gst_status_query->result());
                    // echo "\n\n\n";
                    // exit;

                    if($our_service_info[0]["qb_item_id"] != "")
                    {
                        $item = $dataService->FindbyId('item', $our_service_info[0]["qb_item_id"]);

                        $sparse_update = ["sparse" => true];
                        $item_info = array_merge($item_info, $sparse_update);

                        $theResourceObj = Item::update($item, $item_info);

                        $resultingObj = $dataService->Add($theResourceObj);
                    }
                    else
                    {
                        $theResourceObj = Item::create($item_info);
                        $resultingObj = $dataService->Add($theResourceObj);

                    }

                    
                    $error = $dataService->getLastError();


                    // echo "firstone---";
                    // print_r($error);
                    // exit;

                    if ($error) {
                        if($error->getHttpStatusCode() == "401")
                        {
                            // echo "firstone---";
                            // print_r($error);
                            // exit;
                            $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                            if($refresh_token_status)
                            {
                                $this->import_service_to_qb();
                            }
                        }
                        else
                        {
                            echo json_encode(array("Status" => 2, 'message' => $error->getIntuitErrorMessage(), 'title' => 'Error'));
                        }
                    }
                    else {
                        $data["qb_company_id"] = $this->session->userdata('qb_company_id');
                        $data["our_service_info_id"] = $id;
                        $data["qb_item_id"] = $resultingObj->Id;
                        $data["qb_account_id"] = $qb_account_id;
                        $data["qb_json_data"] = json_encode($resultingObj);

                        if($our_service_info[0]["qb_item_id"] != "")
                        {
                            $this->db->update("our_service_qb_info", $data, array("qb_company_id" => $this->session->userdata('qb_company_id'), "our_service_info_id" => $id));
                            
                            $this->save_audit_trail("Our Services", "QuickBooks", $this->session->userdata('first_name'). " " . $this->session->userdata('last_name') . " update ".$our_service_info[0]["service_name"]." services into QuickBooks Online.");
                        }
                        else
                        {
                            $this->db->insert('our_service_qb_info', $data);

                            $this->save_audit_trail("Our Services", "QuickBooks", $this->session->userdata('first_name'). " " . $this->session->userdata('last_name') . " import ".$our_service_info[0]["service_name"]." services to QuickBooks Online.");
                        }

                        echo json_encode(array("Status" => 1, 'message' => "Import Successfully", 'title' => 'Success', 'qb_item_id' => $resultingObj->Id));
                    }
                }
            }
            catch (Exception $e){
                // echo "2ndone---";
                // print_r($e);
                // exit;
                $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                if($refresh_token_status)
                {
                    $this->import_service_to_qb();
                }
            }
        }
        else
        {
            echo json_encode(array("Status" => 2, 'message' => 'Please login to Quickbook Online first before proceed this step.', 'title' => 'Error'));
        }
    }

    public function import_service_to_qb()
    {
        $id = $_POST['id'];
        $acc_selector_id = $_POST['acc_selector_id'];

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

                $check_gst_status_query = $this->db->query("SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox, gst_jurisdiction.jurisdiction as jurisdiction_name FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id LEFT JOIN gst_jurisdiction ON gst_jurisdiction.id = firm.jurisdiction_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = '".$this->session->userdata("firm_id")."'");

                if ($check_gst_status_query->num_rows() > 0) 
                {
                    $check_gst_status_arr = $check_gst_status_query->result_array();
                    $our_service_info = $this->db->query("SELECT our_service_info.*, gst_category.category, our_service_qb_info.qb_item_id, billing_info_service_category_qb.qb_category_id FROM our_service_info LEFT JOIN our_service_gst ON our_service_gst.our_service_info_id = our_service_info.id AND our_service_gst.jurisdiction_id = '".$check_gst_status_arr[0]["jurisdiction_id"]."' LEFT JOIN gst_category_info ON gst_category_info.id = our_service_gst.category_id LEFT JOIN gst_category ON gst_category.id = gst_category_info.gst_category_id LEFT JOIN billing_info_service_category_qb ON billing_info_service_category_qb.billing_info_service_category_id = our_service_info.service_type AND billing_info_service_category_qb.qb_company_id = '".$this->session->userdata('qb_company_id')."' LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."' WHERE our_service_info.id = '".$id."'");
                }
                else
                {
                    $our_service_info = $this->db->query("SELECT our_service_info.*, our_service_qb_info.qb_item_id, billing_info_service_category_qb.qb_category_id FROM our_service_info LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."' LEFT JOIN billing_info_service_category_qb ON billing_info_service_category_qb.billing_info_service_category_id = our_service_info.service_type AND billing_info_service_category_qb.qb_company_id = '".$this->session->userdata('qb_company_id')."' WHERE our_service_info.id = '".$id."'");
                    //$this->db->get_where("our_service_info", array("id" => $id));
                }

                if ($our_service_info->num_rows())
                {
                    $our_service_info = $our_service_info->result_array();
                        // print_r($our_service_info); 
                        // exit;

                    $qb_account_id = $acc_selector_id;

                    if ($check_gst_status_query->num_rows() > 0) 
                    {
                        $taxCode = $this->query_qb_tax_code($our_service_info[0]["category"]);

                        $item_info = [
                                        "TrackQtyOnHand" => false,
                                        "SubItem" => true,
                                        "Name" => $our_service_info[0]["service_name"],
                                        "UnitPrice" => $our_service_info[0]["amount"],
                                        "Description" => $our_service_info[0]["invoice_description"],
                                        "IncomeAccountRef" => [
                                            "value" => $qb_account_id,
                                        ],
                                        "Type" => "Service",
                                        "SalesTaxCodeRef" => [
                                            "value" => $taxCode
                                        ],
                                        "ParentRef" => [
                                            "value" => $our_service_info[0]["qb_category_id"]
                                        ]
                                    ];
                    }
                    else
                    {
                        $item_info = [
                                        "TrackQtyOnHand" => false,
                                        "SubItem" => true,
                                        "Name" => $our_service_info[0]["service_name"],
                                        "UnitPrice" => $our_service_info[0]["amount"],
                                        "Description" => $our_service_info[0]["invoice_description"],
                                        "IncomeAccountRef" => [
                                            "value" => $qb_account_id,
                                        ],
                                        "Type" => "Service",
                                        "ParentRef" => [
                                            "value" => $our_service_info[0]["qb_category_id"]
                                        ]
                                    ];
                    }

                    //echo json_encode($item_info);
                    
                    if($our_service_info[0]["qb_item_id"] != "")
                    {
                        $item = $dataService->FindbyId('item', $our_service_info[0]["qb_item_id"]);

                        $sparse_update = ["sparse" => true];
                        $item_info = array_merge($item_info, $sparse_update);

                        $theResourceObj = Item::update($item, $item_info);

                        $resultingObj = $dataService->Add($theResourceObj);
                    }
                    else
                    {
                        $theResourceObj = Item::create($item_info);

                        $resultingObj = $dataService->Add($theResourceObj);
                    }

                    
                    $error = $dataService->getLastError();

                    if ($error) {
                        if($error->getHttpStatusCode() == "401")
                        {
                            $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                            if($refresh_token_status)
                            {
                                $this->import_service_to_qb();
                            }
                        }
                        else
                        {
                            echo json_encode(array("Status" => 2, 'message' => $error->getIntuitErrorMessage(), 'title' => 'Error'));
                        }
                    }
                    else {
                        $data["qb_company_id"] = $this->session->userdata('qb_company_id');
                        $data["our_service_info_id"] = $id;
                        $data["qb_item_id"] = $resultingObj->Id;
                        $data["qb_account_id"] = $qb_account_id;
                        $data["qb_json_data"] = json_encode($resultingObj);

                        if($our_service_info[0]["qb_item_id"] != "")
                        {
                            $this->db->update("our_service_qb_info", $data, array("qb_company_id" => $this->session->userdata('qb_company_id'), "our_service_info_id" => $id));
                            
                            $this->save_audit_trail("Our Services", "QuickBooks", $this->session->userdata('first_name'). " " . $this->session->userdata('last_name') . " update ".$our_service_info[0]["service_name"]." services into QuickBooks Online.");
                        }
                        else
                        {
                            $this->db->insert('our_service_qb_info', $data);

                            $this->save_audit_trail("Our Services", "QuickBooks", $this->session->userdata('first_name'). " " . $this->session->userdata('last_name') . " import ".$our_service_info[0]["service_name"]." services to QuickBooks Online.");
                        }

                        echo json_encode(array("Status" => 1, 'message' => "Import Successfully", 'title' => 'Success', 'qb_item_id' => $resultingObj->Id));
                    }
                }
            }
            catch (Exception $e){ 

                print_r($e);
                exit;
                $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                if($refresh_token_status)
                {
                    $this->import_service_to_qb();
                }
            }
        }
        else
        {
            echo json_encode(array("Status" => 2, 'message' => 'Please login to Quickbook Online first before proceed this step.', 'title' => 'Error'));
        }
    }

    public function import_all_service_to_qb()
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

                $check_gst_status_query = $this->db->query("SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox, gst_jurisdiction.jurisdiction as jurisdiction_name FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id LEFT JOIN gst_jurisdiction ON gst_jurisdiction.id = firm.jurisdiction_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = '".$this->session->userdata("firm_id")."'");

                if ($check_gst_status_query->num_rows() > 0) 
                {
                    $check_gst_status_arr = $check_gst_status_query->result_array();
                    $our_service_info = $this->db->query("SELECT our_service_info.*, gst_category.category, our_service_qb_info.qb_item_id, our_service_qb_info.qb_account_id, billing_info_service_category_qb.qb_category_id FROM our_service_info LEFT JOIN our_service_gst ON our_service_gst.our_service_info_id = our_service_info.id AND our_service_gst.jurisdiction_id = '".$check_gst_status_arr[0]["jurisdiction_id"]."' LEFT JOIN gst_category ON gst_category.id = our_service_gst.category_id LEFT JOIN billing_info_service_category_qb ON billing_info_service_category_qb.billing_info_service_category_id = our_service_info.service_type AND billing_info_service_category_qb.qb_company_id = '".$this->session->userdata('qb_company_id')."' LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."' WHERE our_service_qb_info.qb_item_id IS NULL AND our_service_info.deleted = 0 AND our_service_info.approved = 1 LIMIT 5"); // AND our_service_info.deleted = 0 AND our_service_info.approved = 1
                }
                else
                {
                    // $our_service_info = $this->db->get_where("our_service_info", array("qb_item_id = " => 0,"qb_account_id != " => 0, "deleted" => 0, "approved" => 1), 5);

                    $our_service_info = $this->db->query("SELECT our_service_info.*, our_service_qb_info.qb_item_id, our_service_qb_info.qb_account_id, billing_info_service_category_qb.qb_category_id FROM our_service_info LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."' LEFT JOIN billing_info_service_category_qb ON billing_info_service_category_qb.billing_info_service_category_id = our_service_info.service_type AND billing_info_service_category_qb.qb_company_id = '".$this->session->userdata('qb_company_id')."' WHERE our_service_qb_info.qb_item_id IS NULL AND our_service_info.deleted = 0 AND our_service_info.approved = 1 LIMIT 5");
                }

                if ($our_service_info->num_rows())
                {
                    $our_service_info = $our_service_info->result_array();

                    for($t = 0; $t < count($our_service_info); $t++)
                    {
                        if ($check_gst_status_query->num_rows() > 0) 
                        {
                            $taxCode = $this->query_qb_tax_code($our_service_info[0]["category"]);

                            $item_info = [
                                            "TrackQtyOnHand" => false,
                                            "Name" => $our_service_info[0]["service_name"],
                                            "UnitPrice" => $our_service_info[0]["amount"],
                                            "Description" => $our_service_info[0]["invoice_description"],
                                            "IncomeAccountRef" => [
                                                "value" => $our_service_info[$t]["qb_account_id"],
                                                "name" => "Services"
                                            ],
                                            "Type" => "Service",
                                            "SalesTaxCodeRef" => [
                                                "value" => $taxCode
                                            ],
                                            "ParentRef" => [
                                                "value" => $our_service_info[0]["qb_category_id"]
                                            ]
                                        ];
                        }
                        else
                        {
                            $item_info = [
                                            "TrackQtyOnHand" => false,
                                            "Name" => $our_service_info[$t]["service_name"],
                                            "UnitPrice" => $our_service_info[$t]["amount"],
                                            "Description" => $our_service_info[$t]["invoice_description"],
                                            "IncomeAccountRef" => [
                                                "value" => $our_service_info[$t]["qb_account_id"],
                                                "name" => "Services"
                                            ],
                                            "Type" => "Service",
                                            "ParentRef" => [
                                                "value" => $our_service_info[0]["qb_category_id"]
                                            ]
                                        ];
                        }
                        //print_r($item_info);
                        if($our_service_info[$t]["qb_item_id"] != "")
                        {
                            $item = $dataService->FindbyId('item', $our_service_info[$t]["qb_item_id"]);

                            $theResourceObj = Item::update($item, $item_info);

                            $resultingObj = $dataService->Add($theResourceObj);
                        }
                        else
                        {
                            $theResourceObj = Item::create($item_info);

                            $resultingObj = $dataService->Add($theResourceObj);
                        }

                        
                        $error = $dataService->getLastError();

                        if ($error) {
                            if($error->getHttpStatusCode() == "401")
                            {

                                $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                                if($refresh_token_status)
                                {
                                    $this->import_all_service_to_qb();
                                }
                            }
                            else
                            {
                                echo json_encode(array("Status" => 2, 'message' => $error->getIntuitErrorMessage(), 'title' => 'Error'));
                            }
                        }
                        else {
                            $data["qb_company_id"] = $this->session->userdata('qb_company_id');
                            $data["our_service_info_id"] = $our_service_info[$t]["id"];
                            $data["qb_item_id"] = $resultingObj->Id;
                            $data["qb_account_id"] = $our_service_info[$t]["qb_account_id"];
                            $data["qb_json_data"] = json_encode($resultingObj);

                            if($our_service_info[$t]["qb_item_id"] != "")
                            {
                                $this->db->update("our_service_qb_info", $data, array("qb_company_id" => $this->session->userdata('qb_company_id'), "id" => $our_service_info[$t]["id"]));
                            }
                            else
                            {
                                $this->db->insert('our_service_qb_info', $data);
                            }

                            echo json_encode(array("Status" => 1, 'message' => "Import Successfully", 'title' => 'Success'));
                        }
                    }
                }
            }
            catch (Exception $e){
                print_r($e);
                exit;
                $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
                if($refresh_token_status)
                {
                    $this->import_all_service_to_qb();
                }
            }
        }
        else
        {
            echo json_encode(array("Status" => 2, 'message' => 'Please login to Quickbook Online first before proceed this step.', 'title' => 'Error'));
        }
    }

    public function get_income_account()
    {
        $id = $_POST['id'];

        $our_service_info = $this->db->query("SELECT our_service_info.*, our_service_qb_info.qb_item_id, our_service_qb_info.qb_account_id FROM our_service_info LEFT JOIN our_service_qb_info ON our_service_qb_info.our_service_info_id = our_service_info.id AND our_service_qb_info.qb_company_id = '".$this->session->userdata('qb_company_id')."' WHERE our_service_info.id = '".$id."'");

        if ($our_service_info->num_rows())
        {
            $our_service_arr = $our_service_info->result_array();
            if($our_service_arr[0]["qb_account_id"] != "")
            {
                $our_service_id = $our_service_arr[0]["qb_account_id"];
            }
            else
            {
                $our_service_id = 0;
            }
        }
        else
        {
            $our_service_id = 0;
        }

        if($this->session->userdata('refresh_token_value'))
        {
            $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
            if($refresh_token_status)
            {
                /* API URL */
                $url = $this->quickbookURL.'/v3/company/'.$this->session->userdata('qb_company_id').'/query?query=select%20*%20from%20Account%20WHERE%20Active=true%20STARTPOSITION%201%20MAXRESULTS%201000';
                /* Init cURL resource */
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $url);
                /* Array Parameter Data */
                //$data = ['Authorization' => 'Bearer '.$this->session->userdata('access_token_value')];
                $authorization = "Authorization:Bearer ".$this->session->userdata('access_token_value');

                curl_setopt($ch, CURLOPT_POST, false);

                /* pass encoded JSON string to the POST fields */
                //curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(""));
                    
                /* set the content type json */
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type:text/plain', $authorization));
                    
                /* set return type json */
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    
                /* execute request */
                $result = curl_exec($ch);
                //print_r($result);
                /* close cURL resource */
                curl_close($ch);

                $xml_snippet = simplexml_load_string( $result );
                $json_convert = json_encode( $xml_snippet );
                $json = json_decode( $json_convert );
                //print_r($json);
                echo json_encode(array("Status" => 1, 'data' => json_encode($json), 'qb_acc_id' => $our_service_id));
            }
        }
        else
        {
            echo json_encode(array("Status" => 2, 'message' => 'Please login to Quickbook Online to save this invoice to Quickbook Online.', 'title' => 'Error'));
        }
    }

    public function query_qb_tax_code($tax_name = null)
    {
        $refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
        if($refresh_token_status)
        {
            /* API URL */
            $url = $this->quickbookURL.'/v3/company/'.$this->session->userdata('qb_company_id').'/query?query=select%20*%20from%20TaxCode%20where%20Active=true';
            /* Init cURL resource */
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            /* Array Parameter Data */
            //$data = ['Authorization' => 'Bearer '.$this->session->userdata('access_token_value')];
            $authorization = "Authorization:Bearer ".$this->session->userdata('access_token_value');

            curl_setopt($ch, CURLOPT_POST, false);

            /* pass encoded JSON string to the POST fields */
            //curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(""));
                
            /* set the content type json */
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type:text/plain', $authorization));
                
            /* set return type json */
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                
            /* execute request */
            $result = curl_exec($ch);
            
            /* close cURL resource */
            curl_close($ch);

            $xml_snippet = simplexml_load_string( $result );
            $json_convert = json_encode( $xml_snippet );
            $json = json_decode( $json_convert ); 
            $taxCode = $json->QueryResponse->TaxCode;
            for($t = 0; $t < count($taxCode); $t++)
            {
            // print_r($taxCode[$t]->Name);
            // echo "\n";
            // print_r($tax_name);
            // echo "\n";
            // echo "\n"; 
                if($taxCode[$t]->Name == $tax_name)
                {
                    $taxCodeID = $taxCode[$t]->Id;
                }
            }
            return ($taxCodeID);
        }
    }

    public function save_audit_trail($modules, $events, $actions)
    {
        $secretary_audit_trail["user_id"] = $this->session->userdata("user_id");
        $secretary_audit_trail["modules"] = $modules;
        $secretary_audit_trail["events"] = $events;
        $secretary_audit_trail["actions"] = $actions;

        $this->db->insert("secretary_audit_trail",$secretary_audit_trail);
    }
}
