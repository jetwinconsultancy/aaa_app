<?php defined('BASEPATH') OR exit('No direct script access allowed');

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
        $this->load->model(array('master_model', 'db_model'));
    }

    public function index()
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $bc = array(array('link' => '#', 'page' => lang('Our Services')));
        $meta = array('page_title' => lang('Our Services'), 'bc' => $bc, 'page_name' => 'Our Services');

        $this->data['template'] = $this->db_model->get_all_template();
         $this->data['user_admin_code_id'] = $this->session->userdata("user_admin_code_id");
        $this->page_construct('our_services.php', $meta, $this->data);
        
    }

    public function get_our_service_data($id)
    {
        //$this->data['our_service_info'] = $this->master_model->get_our_service_info($id);

        $data = $this->master_model->get_our_service_info($id);

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

    // public function numeric_wcomma ($str)
    // {
    //     //$this->form_validation->set_message('numeric_wcomma', 'The is not valid!');

    //     return preg_match('/^[0-9]{1,3}(,[0-9]{3})*(.[0-9]{1,2})*$/', $str);
    // }

    public function save_our_service_data()
    {
        for($i = 0; $i < count($_POST['user_admin_code_id']); $i++ )
        {   
            //$this->form_validation->set_rules('bank_id['.$i.']', 'Bank Id', 'required');
            //$this->form_validation->set_rules('service_type['.$i.']', 'Service Type', 'required');
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
            // if(isset($_POST['engagement_letter_description'][$i]))
            // {
            //     $this->form_validation->set_rules('engagement_letter_description['.$i.']', 'Engagement Letter Description', 'required');
            // }

            if ($this->form_validation->run() == FALSE || $_POST['service_proposal_letter_required'][$i] == 0 || $_POST['engagement_letter_required'][$i] == 0 || $_POST['currency'][$i] == 0 || $_POST['service_type'][$i] == 0 || $_POST['unit_pricing'][$i] == 0 || (isset($_POST['display_in_se'][$i]) && $_POST['display_in_se'][$i] == 0) || (isset($_POST['engagement_letter_list'][$i]) && $_POST['engagement_letter_list'][$i] == 0))
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
                    'service_name' => strip_tags(form_error('service_name['.$i.']')),
                    'invoice_description' => strip_tags(form_error('invoice_description['.$i.']')),
                    'amount' => strip_tags(form_error('amount['.$i.']')),
                    'currency' => $currency_error,
                    'unit_pricing' => $unit_pricing_error,
                    'service_postal_code' => strip_tags(form_error('service_postal_code['.$i.']')),
                    'service_street_name' => strip_tags(form_error('service_street_name['.$i.']')),
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
                //$data['our_service_id']=$_POST['our_service_id'][$i];
                $data['service_type']=$_POST['service_type'][$i];
                $data['service_name']=$_POST['service_name'][$i];
                $data['invoice_description']=$_POST['invoice_description'][$i];
                $data['amount']=round((float)(str_replace(',', '', $_POST['amount'][$i])), 2);
                $data['unit_pricing']=$_POST['unit_pricing'][$i];
                $data['currency']=$_POST['currency'][$i];
                $data['sp_required_id']=$_POST['service_proposal_letter_required'][$i];
                $data['el_required_id']=$_POST['engagement_letter_required'][$i];

                if(isset($_POST['service_postal_code'][$i]))
                {
                    $register_address['postal_code']=$_POST['service_postal_code'][$i];
                }

                if(isset($_POST['service_street_name'][$i]))
                {
                    $register_address['street_name']=$_POST['service_street_name'][$i];
                }

                if(isset($_POST['service_building_name'][$i]))
                {
                    $register_address['building_name']=$_POST['service_building_name'][$i];
                }

                if(isset($_POST['service_unit_no1'][$i]))
                {
                    $register_address['unit_no1']=$_POST['service_unit_no1'][$i];
                }

                if(isset($_POST['service_unit_no2'][$i]))
                {
                    $register_address['unit_no2']=$_POST['service_unit_no2'][$i];
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

                //echo $data['amount'];

                $q = $this->db->get_where("our_service_info", array("id" => $_POST['our_service_id'][$i]));

                if (!$q->num_rows())
                {   
                    $check_service_name = $this->db->get_where("our_service_info", array("service_name" => $_POST['service_name'][$i], "user_admin_code_id" => $_POST['user_admin_code_id'][$i]));

                    if (!$check_service_name->num_rows())
                    {
                        $this->db->insert("our_service_info",$data);
                        $insert_our_service_info_id = $this->db->insert_id();

                        if(isset($_POST['service_postal_code'][$i]))
                        {
                            $register_address['our_service_info_id'] = $insert_our_service_info_id;
                            $this->db->insert("our_service_registration_address",$register_address);
                        }

                        $this->db->select('our_service_info.*, billing_info_service_category.category_description as service_type_name, unit_pricing.unit_pricing_name, our_service_registration_address.postal_code, our_service_registration_address.street_name, our_service_registration_address.building_name, our_service_registration_address.unit_no1, our_service_registration_address.unit_no2');
                        $this->db->from('our_service_info');
                        $this->db->join('billing_info_service_category', 'billing_info_service_category.id = our_service_info.service_type', 'left');
                        $this->db->join('unit_pricing', 'unit_pricing.id = our_service_info.unit_pricing', 'left');
                        $this->db->join('our_service_registration_address', 'our_service_registration_address.our_service_info_id = our_service_info.id', 'left');
                        $this->db->where('our_service_info.id', $insert_our_service_info_id);
                        $row_of_our_service_info = $this->db->get();
                        //$row_of_our_service_info = $transaction_our_service_info->result_array();

                        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_our_service_info_id" => $insert_our_service_info_id, "row_of_our_service_info" => $row_of_our_service_info->result(), "enable_add_row" => true));
                    }
                    else
                    {
                        echo json_encode(array("Status" => 2, 'message' => 'Cannot have same service name under this firm.', 'title' => 'Error'));
                    }

                }
                else
                {
                    $check_service_name = $this->db->get_where("our_service_info", array("service_name" => $_POST['service_name'][$i], "user_admin_code_id" => $_POST['user_admin_code_id'][$i], "id !=" => $_POST['our_service_id'][$i]));

                    if (!$check_service_name->num_rows())
                    {
                        $this->db->update("our_service_info",$data,array("id" => $_POST['our_service_id'][$i]));

                        if(isset($_POST['service_postal_code'][$i]))
                        {
                            $register_address['our_service_info_id'] = $_POST['our_service_id'][$i];
                            $this->db->update("our_service_registration_address",$register_address,array("our_service_info_id" => $_POST['our_service_id'][$i]));
                        }

                        $this->db->select('our_service_info.*, billing_info_service_category.category_description as service_type_name, unit_pricing.unit_pricing_name, our_service_registration_address.postal_code, our_service_registration_address.street_name, our_service_registration_address.building_name, our_service_registration_address.unit_no1, our_service_registration_address.unit_no2');
                        $this->db->from('our_service_info');
                        $this->db->join('billing_info_service_category', 'billing_info_service_category.id = our_service_info.service_type', 'left');
                        $this->db->join('unit_pricing', 'unit_pricing.id = our_service_info.unit_pricing', 'left');
                        $this->db->join('our_service_registration_address', 'our_service_registration_address.our_service_info_id = our_service_info.id', 'left');
                        $this->db->where('our_service_info.id', $_POST['our_service_id'][$i]);
                        $row_of_our_service_info = $this->db->get();
                        //$row_of_our_service_info = $transaction_our_service_info->result_array();

                        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "row_of_our_service_info" => $row_of_our_service_info->result(), "enable_add_row" => false));
                    }
                    else
                    {
                        echo json_encode(array("Status" => 2, 'message' => 'Cannot have same service name under this firm.', 'title' => 'Error'));
                    }

                }
            }
        }
    }

    public function delete_our_service_data()
    {
       
        $id = $_POST['id'];
        $user_admin_code_id = $_POST['user_admin_code_id'];

        $check_client_billing_info = $this->db->get_where("client_billing_info", array("service" => $id, "deleted != " => 1));

        if (!$check_client_billing_info->num_rows())
        {
            $this->db->delete("our_service_info",array('id'=>$id));

            $this->db->delete("our_service_registration_address",array('our_service_info_id'=>$id));

            echo json_encode(array("Status" => 1));
        }
        else
        {
            echo json_encode(array("Status" => 2));
        }
    }
}