<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Financial_statement extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        //$this->load->library('form_validation');
        $this->load->library(array('session', 'encryption'));
        $this->load->model('db_model');
        $this->load->model('fs_model');
        $this->load->model('fs_account_category_model');
        $this->load->model('fs_replace_content_model');
        $this->load->model('fs_notes_model');
        $this->load->model('fs_generate_doc_word_model');
        $this->load->model('master_model');
    }

    public function get_fs_list_report_status()  // get dropdown value lsit for company list.
    {
        $fs_list_report_status = $this->db->query("SELECT * FROM fs_list_report_status ORDER BY id");
        $fs_list_report_status = $fs_list_report_status->result();

        $fs_list_report_status_dp = array();

        // $company_list_dp[''] = '- Select a firm - ';

        foreach($fs_list_report_status as $item)
        {
            $fs_list_report_status_dp[$item->name] = $item->name; 
        }

        return $fs_list_report_status_dp;
    }

    public function document_checklist()
    {
        $form_data          = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $fs_doc_checklist = $this->fs_model->get_fs_doc_checklist();    // get all document name for checklist
        $doc_status_list  = [];

        foreach ($fs_doc_checklist['document_checklist'] as $key => $value) 
        {
            $status = 1;

            if($value == "Corporate Information > Information")
            {
                if(empty($fs_company_info_id))
                {
                    $status = 0;
                }

                if(empty($fs_company_info[0]['director_signature_1']))
                {
                    $status = 0;
                    $value = "Corporate Information > Information (No Signing Director)";
                }
            }
            // elseif($value == "Corporate Information > Director Share Holding")
            // {

            // }
            elseif($value == "Corporate Information > Our Report")
            {
                $audit_report = $this->fs_model->get_this_independent_aud_report($fs_company_info_id); 

                if(count($audit_report) == 0)
                {
                    $is_small_FRS_not_audited = $this->fs_model->is_small_FRS_not_audited($fs_company_info_id);

                    if($is_small_FRS_not_audited)
                    {
                        $status = 1;
                    }
                    else
                    {
                        $status = 0;
                    }
                }
            }
            elseif($value == "Financial Statement > Account Category" || $value == "Note to Financial Statement (NTA)")
            {
                $account_category = $this->fs_statements_model->is_saved_fs_categorized_account_round_off($fs_company_info_id);
                $status = $account_category;
            }
            elseif($value == "Financial Statement > Statement of Comprehensive Income")
            {
                $fs_statement_list = $this->fs_statements_model->get_fs_statement();    // get list of code from json 
                $fs_state_comp_list = $this->fs_statements_model->get_fs_state_comp_income($fs_company_info_id);

                foreach ($fs_statement_list->statement_comprehensive_income[0]->sections as $sci_json_key => $sci_json_value) 
                {
                    $data = [];
                    $fca_id = [];

                    if($sci_json_value->list_name == "income_list")
                    {
                        $income_fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, $sci_json_value->account_category_code);

                        $income_data = [];

                        foreach ($income_fca_id as $income_fca_id_key => $income_fca_id_value) 
                        {
                            array_push($income_data, $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, array($income_fca_id_value)));
                        }

                        $income_list = array($income_data);
                    }
                    elseif($sci_json_value->list_name == "changes_in_inventories")
                    {
                        $key = array_search('1', array_column($fs_state_comp_list, 'fs_list_state_comp_income_section_id'), true);

                        if((string)$key != '')
                        {
                            $changes_in_inventories = $fs_state_comp_list[$key];
                        }
                    }
                    elseif($sci_json_value->list_name == "purchases_and_related_costs")
                    {
                        $key = array_search('2', array_column($fs_state_comp_list, 'fs_list_state_comp_income_section_id'), true);

                        if((string)$key != '')
                        {
                            $purchases_and_related_costs = $fs_state_comp_list[$key];
                        }
                    }
                    elseif($sci_json_value->list_name == "pl_be4_tax")
                    {
                        // profit / loss before tax
                        $key = array_search('3', array_column($fs_state_comp_list, 'fs_list_state_comp_income_section_id'), true);

                        if((string)$key != '')
                        {
                            $pl_be4_tax = $fs_state_comp_list[$key];
                        }
                    }
                    elseif($sci_json_value->list_name == "pl_after_tax")
                    {
                        // profit / loss before tax
                        $key = array_search('4', array_column($fs_state_comp_list, 'fs_list_state_comp_income_section_id'), true);

                        if((string)$key != '')
                        {
                            $pl_after_tax = $fs_state_comp_list[$key];
                        }
                    }
                    elseif($sci_json_value->list_name == "expense_list")
                    {
                        // get sub account codes list
                        $expense_sub_list_ids = $this->fs_account_category_model->get_sub_categories($fs_company_info_id, $sci_json_value->account_category_code[0]);

                        foreach ($expense_sub_list_ids as $fca_id_key => $fca_id_value) 
                        {
                            array_push($data, $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, array($fca_id_value)));
                        }

                        $expense_list = $data;
                    }
                    elseif($sci_json_value->list_name == "additional_list")
                    {
                        $fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, $sci_json_value->account_category_code);

                        foreach ($fca_id as $fca_id_key => $fca_id_value) 
                        {
                            array_push($data, $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, array($fca_id_value)));
                        }

                        $additional_list = $data;
                    }
                    elseif($sci_json_value->list_name == "soa_pl_list") // Share of associates profit or loss
                    {
                        $temp_data = [];

                        $fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, $sci_json_value->account_category_code);

                        foreach ($fca_id as $fca_id_key => $fca_id_value) 
                        {
                            $temp_data = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, array($fca_id_value));

                            if(count($temp_data[0]['child_array']) > 0)
                            {
                                array_push($data, $temp_data);
                            }
                        }

                        if(count($data) > 0)
                        {
                            $soa_pl_list = $data;
                        }
                        else
                        {
                            $soa_pl_list = [];
                        }
                    }
                    elseif($sci_json_value->list_name == "other_list")
                    {
                        /*----- rearrange array -----*/
                        $fs_list_state_comp_income_section_id_list =  array_column($fs_state_comp_list, 'fs_list_state_comp_income_section_id');

                        $id_list = []; 

                        foreach ($fs_list_state_comp_income_section_id_list as $key => $value1) 
                        {
                            array_push($id_list, $value1);
                        }

                        /*----- END OF rearrange array -----*/

                        $keys = array_keys($id_list, 5);    // get all keys matched with 5 in fs_list_state_comp_income_section_id

                        foreach ($keys as $key => $value2) 
                        {
                            array_push($other_list, $fs_state_comp_list[$value2]);
                        }
                    } 
                }

                $income_list            = $income_list[0];
                $changes_in_inventories = $changes_in_inventories;
                $purchases              = $purchases_and_related_costs;
                $pl_be4_tax             = $pl_be4_tax;
                $pl_after_tax           = $pl_after_tax;
                $expense_list           = $expense_list;
                $additional_list        = $additional_list;
                $soa_pl_list            = $soa_pl_list;
                $other_list             = $other_list;

                if(count($income_list) == 0 && count($changes_in_inventories) == 0 && count($purchases) == 0 && count($pl_be4_tax) == 0 && count($pl_after_tax) == 0 && 
                    count($expense_list) == 0 && count($additional_list) == 0 && count($soa_pl_list) == 0 && count($other_list) == 0)
                {
                    $status = 0;
                }
            }
            elseif($value == "Financial Statement > Statement of Financial Position")
            {
                $fs_ntfs_list = $this->fs_notes_model->get_fs_ntfs_json();

                $nd_key = array_search("Statement of financial position", array_column($fs_ntfs_list['statements'], 'document_name'));
                $nd_ref_id = '';
                $description_reference_id_list = [];

                if($nd_key || (string)$nd_key == '0')
                {
                    $nd_account_code = $fs_ntfs_list['statements'][$nd_key]['reference_id']; // get account code

                    $description_reference_id_list = $fs_ntfs_list['statements'][$nd_key]['description_reference_id'];
                }

                $fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, $nd_account_code);
                $data = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $fca_id);

                if(count($data) == 0)
                {
                    $status = 0;
                }
            }
            elseif($value == "Financial Statement > Statement of Changes in Equity")
            {
                $group_company = '';
                if($fs_company_info[0]['group_type'] == 1)
                {
                    $group_company = 'company';
                }
                else
                {
                    $group_company = 'group';
                }

                $temp_fs_state_changes_in_equity_current_group = $this->fs_statements_model->get_fs_state_changes_in_equity($fs_company_info_id, "current", $group_company);

                foreach ($temp_fs_state_changes_in_equity_current_group as $key => &$row) 
                {
                    $temp_row = $row['row_item'];
                    $temp_row = explode(",", $temp_row);

                    $row['row_item'] = $temp_row;
                }

                $fs_state_changes_in_equity_current_group = $temp_fs_state_changes_in_equity_current_group;

                if(count($fs_state_changes_in_equity_current_group) == 0)
                {
                    $status = 0;
                }
            }
            elseif($value == "Financial Statement > Statement of Cash Flows")
            {
                // $fs_state_cash_flows = $this->fs_statements_model->get_fs_state_cash_flows($fs_company_info_id);

                $temp_all_state_cash_flows_fixed = $this->fs_statements_model->get_fs_state_cash_flows_fixed($fs_company_info_id);

                foreach ($temp_all_state_cash_flows_fixed as $key => $each) 
                {
                    $temp_arr[$each['fixed_tag']]['fs_note_details_id'] = $each['fs_note_details_id'];
                    $temp_arr[$each['fixed_tag']]['group_ye'] = $each['value_group_ye'];
                    $temp_arr[$each['fixed_tag']]['group_lye_end'] = $each['value_group_lye_end'];
                    $temp_arr[$each['fixed_tag']]['company_ye'] = $each['value_company_ye'];
                    $temp_arr[$each['fixed_tag']]['company_lye_end'] = $each['value_company_lye_end'];

                    if($each['fs_note_details_id'] != null)
                    {
                        $temp_arr[$each['fixed_tag']]['note_display_num'] = $this->fs_notes_model->get_input_note_num($fs_company_info_id, $each['fs_note_details_id']);
                    }
                }

                if(count($temp_arr) == 0)
                {
                    $status = 0;
                }
            }
            elseif($value == "Financial Statement > Statement of Detailed Profit or Loss")
            {
                /* ---------------------- get statement of detailed profit or loss data ---------------------- */
                $fs_ntfs_list = $this->fs_notes_model->get_fs_ntfs_json();

                $dpl_key = array_search("Statement of detailed profit or loss", array_column($fs_ntfs_list['statements'], 'document_name'));
                $dpl_ref_id = '';

                if($dpl_key || (string)$dpl_key == '0')
                {
                    $dpl_account_code = $fs_ntfs_list['statements'][$dpl_key]['reference_id']; // get account code
                }

                $dpl_fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, $dpl_account_code);

                $dpl_data = [];

                foreach ($dpl_fca_id as $dpl_fca_id_key => $dpl_fca_id_value) 
                {
                    array_push($dpl_data, $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, array($dpl_fca_id_value)));
                }

                $state_detailed_pro_loss = $dpl_data;

                /* ---------------------- END OF get statement of detailed profit or loss data ---------------------- */

                if(count($state_detailed_pro_loss) == 0)
                {
                    $status = 0;
                }
            }
            elseif($value == "Financial Statement > Schedule of Operating Expenses")
            {
                $fs_ntfs_settings_list = $this->fs_notes_model->get_fs_ntfs_json();
                $er_key = array_search("Schedule of operating expenses", array_column($fs_ntfs_settings_list['statements'], 'document_name'));

                $er_ref_id = '';

                if($er_key || (string)$er_key == '0')
                {
                    $er_account_code = $fs_ntfs_settings_list['statements'][$er_key]['reference_id']; // get account code
                }

                $fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, $er_account_code);

                $schedule_operating_expense = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $fca_id);

                if(count($schedule_operating_expense) == 0)
                {
                    $status = 0;
                }
            }

            array_push($doc_status_list, 
                array(
                    'doc_name' => $value, 
                    'status' => $status
                )
            );
        }

        /* Tick and cross template (html) */ 
        $tick = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">' .
                        '<circle class="path circle" fill="none" stroke="#73AF55" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>' .
                        '<polyline class="path check" fill="none" stroke="#73AF55" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>' .
                    '</svg>';

        $cross = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">' .
                        '<circle class="path circle" fill="none" stroke="#D06079" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>' .
                        '<line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3"/>' .
                        '<line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" x2="34.4" y2="92.2"/>' .
                    '</svg>';
        /* END OF Tick and cross template (html) */ 

        /* write document's name and status */
        $tr_templates = '';
        $generate_report_approved = true;

        foreach ($doc_status_list as $dsl_key => $dsl_value) 
        {
            $tr = '<tr><td>' . $dsl_value['doc_name'] . '</td>';

            if($dsl_value['status'])
            {
                $tr .= '<td>' . $tick . '</td>';
            }
            else
            {
                $tr .= '<td>' . $cross . '</td>';
                $generate_report_approved = false;
            }

            $tr .= '</tr>';

            if(!empty($tr))
            {
                $tr_templates .= $tr;
            }
        }
        /* END OF write document's name and status */

        // print_r($doc_status_list);

        echo json_encode(array('tr_template' => $tr_templates, 'generate_report_approved' => $generate_report_approved));
    }

    public function index()
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['fs_list_report_status'] = $this->get_fs_list_report_status();
        
        $this->data['client'] = $this->fs_model->getClient($_SESSION['group_id'], null, null, '1');
        $this->data['client'] = $this->fs_model->decrypt_client_info($this->data['client'], $_POST['search']);

        $bc = array(array('link' => '#', 'page' => lang('Report')));
        $meta = array('page_title' => lang('Report'), 'bc' => $bc, 'page_name' => 'Report');

        $this->page_construct('financial_statement/index.php', $meta, $this->data);
    }

    public function update_fs_report_status()
    {
        $form_data = $this->input->post();

        $fs_company_info_id       = $form_data['fs_company_info_id'];
        $fs_list_report_status_id = $form_data['fs_list_report_status_id'];

        $result = $this->fs_model->update_fs_company_info($fs_company_info_id, array('fs_list_report_status_id' => $fs_list_report_status_id));

        // print_r($form_data);
        echo json_encode(array('result' => $result));
    }

    public function update_selected_generate_docs_without_tags()
    {
        $form_data = $this->input->post();

        $fs_settings_id     = $form_data['fs_settings_id'];
        $checked            = $form_data['checked'];
        $fs_company_info_id = $form_data['fs_company_info_id'];

        if($checked == 'true')
        {
            $checked = 1;
        }
        else
        {
            $checked = 0;
        }

        // setup data, then insert/update data
        $fs_settings_data = array(
                                'fs_company_info_id'         => $fs_company_info_id,
                                'generate_docs_without_tags' => $checked
                            );
        $result = $this->fs_model->save_fs_settings($fs_settings_data); // save fs_settings

        if($result['result'])
        {
            if($fs_settings_data['generate_docs_without_tags'])
            {
                $msg = "Turned on generate without tags.";
            }
            else
            {
                $msg = "Turned off generate without tags.";
            }
        }
        else
        {
            $msg = "Status is not updated. Please try again later.";
        }

        echo json_encode(array('status' => $result['result'], 'fs_settings_id' => $result['fs_settings_id'], 'msg' => $msg));
    }


    public function create($client_id, $fs_company_info_id = NULL)
    {
        $bc   = array(array('link' => '#', 'page' => lang('Report')));
        $meta = array('page_title' => lang('Report'), 'bc' => $bc, 'page_name' => 'Report');

        // $this->data["dp_opinion"]        = $this->fs_model->get_opinion();
        // $this->data["dp_key_aud_matter"] = $this->fs_model->get_key_audit_matter();
        $client_info  = $this->db->query("SELECT * FROM client WHERE id = " . $client_id);
        $client_info  = $client_info->result_array();
        $client_info = $this->fs_model->decrypt_client_info($client_info, '');  // keyword become '' to get all data list only
        $company_code = $client_info[0]['company_code'];

        $this->data['client_id']    = $client_id;
        $this->data['company_code'] = $client_info[0]['company_code'];
        $this->data["firm_list"]    = $this->fs_model->get_company_list();
        // echo json_encode($this->data["group_type_list"]);

        // $this->session->set_userdata(array('fs_report_id'  => $fs_report_id));

        // $this->data["fs_report_details"] = $this->fs_model->get_fs_report_details($fs_report_id)[0];
        $this->data["fs_report_details"] = $this->fs_model->get_fs_report_details($fs_company_info_id)[0];
        $this->data["firm_details"]      = $this->fs_model->get_client_info($client_info[0]['company_code']);
        // $this->data["firm_details"]      = $this->fs_model->get_client_info($this->data["fs_report_details"]->company_code);

        $this->data['client_signing_info'] = $this->fs_model->get_all_client_signing_info($company_code);
        // $client_info = json_decode($this->data["firm_details"])[0];

        /* ------------------ Report Template ------------------ */
        
        $this->data['last_document'] = "";
        $this->data['current_report_template_used'] = array('filename' => '', 'rt_id' => 0);

        // get selected generate without tag option
        $generate_without_tag    = false;
        $db_generate_without_tag = $this->fs_model->get_fs_settings($fs_company_info_id);

        if(!empty($fs_company_info_id))
        {
            $fs_doc_template_word = $this->fs_generate_doc_word_model->get_fs_doc_template_word($fs_company_info_id);

            if(count($fs_doc_template_word) == 0)   // load default file
            {
                $last_ye_fs_company_info_id     = $this->fs_model->get_fs_company_info_last_year($fs_company_info_id);
                $last_year_fs_doc_template_word = $this->fs_generate_doc_word_model->get_fs_doc_template_word($last_ye_fs_company_info_id);

                if(count($last_year_fs_doc_template_word) > 0)
                {
                    $this->data['current_report_template_used']['filename'] = $last_year_fs_doc_template_word[0]['filename'];
                }
                else
                {
                    $final_document_type = $this->fs_model->get_final_document_type($fs_company_info_id);
                    $fs_doc_template_word_base = $this->fs_generate_doc_word_model->get_fs_doc_template_word_base($final_document_type);

                    $this->data['current_report_template_used']['filename'] = $fs_doc_template_word_base[0]['filename'];
                }
                $this->data['current_report_template_used']['rt_id']    = '';
            }
            else
            {
                $this->data['current_report_template_used']['filepath'] = $fs_doc_template_word[0]['filepath'];
                $this->data['current_report_template_used']['filename'] = $fs_doc_template_word[0]['filename'];
                $this->data['current_report_template_used']['rt_id']    = $fs_doc_template_word[0]['id'];

                $this->data['last_document'] = $fs_doc_template_word[0]['last_document'];
            }
        }

        // disable toogle if don't have report template used.
        $this->data['fs_settings_id'] = '';

        if(empty($this->data['current_report_template_used']['rt_id']))
        {
            $this->data['generate_without_tag'] = null;

            // get fs_settings_id
            if(count($db_generate_without_tag) > 0)
            {
                $this->data['fs_settings_id'] = $db_generate_without_tag[0]['id'];
                // $this->data['generate_without_tag'] = $db_generate_without_tag[0]['generate_docs_without_tags'];
            }
        }
        else
        {   
            // get fs_settings_id
            if(count($db_generate_without_tag) > 0)
            {
                $this->data['fs_settings_id'] = $db_generate_without_tag[0]['id'];
                $this->data['generate_without_tag'] = $db_generate_without_tag[0]['generate_docs_without_tags'];
            }
        }
        /* ------------------ END OF Report Template ------------------ */

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Report', base_url('financial_statement'));

        if($fs_company_info_id != 0)
        {
            $this->mybreadcrumb->add('Edit FS - ' . $client_info[0]['company_name'], base_url('financial_statement/partial_fs_report_list/' . $client_id));
            $this->mybreadcrumb->add($this->data["fs_report_details"]->current_fye_end, base_url());

            $this->data["fs_signing_report"] = $this->fs_model->get_fs_signing_report($fs_company_info_id);
        }
        else
        {
            $this->mybreadcrumb->add('Create FS - ' . $client_info[0]['company_name'], base_url('financial_statement/partial_fs_report_list/' . $client_id));
            $this->mybreadcrumb->add('Create New', base_url());
        }

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('financial_statement/create_edit_fs.php', $meta, $this->data);
    }

    public function partial_fs_report_list($client_id = NULL)
    {
        // print_r($company_code);

        $client_info = $this->db->query("SELECT * FROM client WHERE id = " . $client_id);
        $client_info = $client_info->result_array();
        $client_info = $this->fs_model->decrypt_client_info($client_info, '');  // keyword become '' to get all data list only

        $fs_company_info_list = $this->db->query("SELECT * FROM fs_company_info WHERE company_code='" . $client_info[0]['company_code'] . "' AND firm_id=" . $this->session->userdata('firm_id') . " ORDER BY created_at DESC");
        $fs_company_info_list = $fs_company_info_list->result_array();

        $this->data['fs_company_info_list'] = $fs_company_info_list;
        $this->data['client_id'] = $client_id;

        // print_r($fs_company_info_list);

        $bc = array(array('link' => '#', 'page' => lang('Report')));
        $meta = array('page_title' => lang('Report'), 'bc' => $bc, 'page_name' => 'Report');

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Report', base_url('financial_statement'));
        $this->mybreadcrumb->add('Create FS - ' . $client_info[0]['company_name'], base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('financial_statement/partial_fs_report_list.php', $meta, $this->data);
    }

    public function partial_corporate_information()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $is_small_frs_not_audited = $this->fs_model->is_small_FRS_not_audited($fs_company_info_id);

        $this->data['is_small_frs_not_audited'] = $is_small_frs_not_audited;

        $interface = $this->load->view('/views/financial_statement/template/partial_fs_corporate_info.php', $this->data);

        echo $interface;
    }

    public function partial_auditor_report()
    {
        $form_data          = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $this_independ_aud_report = $this->fs_model->get_this_independent_aud_report($fs_company_info_id);

        // opinion part (Fixed template)
        $report_opinion_template_fixed_1 = $this->fs_model->get_fs_doc_template_master(3, 'Opinion details - fixed (paragraph 1)');
        $report_opinion_template_fixed_1 = $this->replace_tagging_verbs($report_opinion_template_fixed_1, $fs_company_info_id);

        $report_opinion_template_fixed_2 = $this->fs_model->get_fs_doc_template_master(3, 'Opinion details - fixed (paragraph 2)');
        $report_opinion_template_fixed_2 = $this->replace_tagging_verbs($report_opinion_template_fixed_2, $fs_company_info_id);

        // opinion part (Textarea dynamic template)
        $report_basic_opinion_template_input  = $this->fs_model->get_fs_doc_template_master(3, 'Basic for opinion - input');
         $report_basic_opinion_template_input = $this->replace_tagging_verbs($report_basic_opinion_template_input, $fs_company_info_id);
        $report_basic_opinion_template_fixed  = $this->fs_model->get_fs_doc_template_master(3, 'Basic for opinion - fixed');

        $key_aud_matter_template = $this->fs_model->get_fs_doc_template_master(3, 'Key audit matter');

        $emphasis_of_matter_template = $this->fs_model->get_fs_doc_template_master(3, 'Emphasis of matter');
        $emphasis_of_matter_template = $this->replace_tagging($emphasis_of_matter_template, $fs_company_info_id);

        $other_matter_template = $this->fs_model->get_fs_doc_template_master(3, 'Other matters');
        $other_matter_template = $this->replace_tagging($other_matter_template, $fs_company_info_id);

        $disclaimer_0f_opinion_template = $this->fs_model->get_fs_doc_template_master(3, 'Disclaimer of opinion');

        for($count = 0; $count < count($report_opinion_template_fixed_2); $count++)
        {
            $opinion_list[$report_opinion_template_fixed_2[$count]->fs_opinion_type_id] = $report_opinion_template_fixed_2[$count]->name;
        }

        $this->data['dp_opinion'] = $opinion_list;
        $this->data['report_opinion_template_fixed_1'] = $report_opinion_template_fixed_1;
        $this->data['report_opinion_template_fixed_2'] = $report_opinion_template_fixed_2;
        $this->data['report_basic_opinion_template_input'] = $report_basic_opinion_template_input;
        $this->data['report_basic_opinion_template_fixed'] = $report_basic_opinion_template_fixed;
        $this->data['key_aud_matter_template'] = $key_aud_matter_template;
        $this->data['emphasis_of_matter_template'] = $emphasis_of_matter_template;
        $this->data['other_matter_template'] = $other_matter_template;
        $this->data['disclaimer_of_opinion_template'] = $disclaimer_0f_opinion_template;

        $this->data['this_independ_aud_report'] = $this_independ_aud_report;
        $this->data['fs_company_info'] = $fs_company_info;

        $this->data['accountant_compilation_report'] = "";

        $is_small_frs_not_audited = $this->fs_model->is_small_FRS_not_audited($fs_company_info_id);
        $this->data['is_small_frs_not_audited'] = $is_small_frs_not_audited;

        if($is_small_frs_not_audited)
        {
            $accountant_compilation_report = array((object)array('content' => $this->fs_model->get_accountant_compilation_report()));
            $accountant_compilation_report = $this->replace_tagging($accountant_compilation_report, $fs_company_info_id);
            $this->data['accountant_compilation_report'] = $accountant_compilation_report[0]->content;
        }

        $interface = $this->load->view('/views/financial_statement/partial_aud_report.php', $this->data);

        echo $interface;
    }

    public function partial_company_particular()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];
        $company_code       = $form_data['company_code'];

        $this->data["group_type_list"]          = $this->fs_model->get_group_type();
        $this->data['currency_list']            = $this->fs_model->get_currency_list();
        $this->data["accounting_standard_list"] = $this->fs_model->get_accounting_standard_list();
        $this->data["act_applicable_list"]      = $this->fs_model->get_json_act_applicable_list();

        if(!empty($fs_company_info_id)) // edit / load existing data 
        {
            $this->data['fs_report_details']        = $this->fs_model->get_fs_company_info($fs_company_info_id); // set dropdown value
            $this->data['fs_fp_currency_details']   = $this->fs_model->get_fs_fp_currency_details($fs_company_info_id);

            $fs_company_info = $this->data['fs_report_details'];

            $this->data['effect_of_restatement_since_dp'] = array(
                                                            $fs_company_info[0]['last_fye_begin'] => $fs_company_info[0]['last_fye_begin'],
                                                            $fs_company_info[0]['last_fye_end']   => $fs_company_info[0]['last_fye_end']
                                                        );

            // print_r($this->data['fs_report_details']);
        }
        else // create new
        {
            $fs_company_info = $this->fs_model->get_fs_company_info_by_company_code($company_code);
            $client_signing_info = $this->master_model->get_all_client_signing_info($company_code);

            if(count($fs_company_info) > 0) // if not first
            {
                $data = $this->fs_model->get_new_FYE_date($company_code);

                // retrieve last year functional currency (fc) and presentation currency (pc) and set default values.
                $fs_fp_currency_details = $this->fs_model->get_fs_fp_currency_details($data['id']); // $data['id'] is last year's fs_company_info_id
                $fs_fp_currency_details[0]['last_year_fc_currency_id'] = $fs_fp_currency_details[0]['current_year_fc_currency_id'];
                $fs_fp_currency_details[0]['last_year_pc_currency_id'] = $fs_fp_currency_details[0]['current_year_pc_currency_id'];

                $this->data['fs_fp_currency_details'] = $fs_fp_currency_details;

                $this->data["fs_report_details"][0]['id'] = 0;
                $this->data["fs_report_details"]          = array($data);

                $this->data["fs_report_details"][0]['director_signature_1'] = $client_signing_info[0]->director_signature_1;
                $this->data["fs_report_details"][0]['director_signature_2'] = $client_signing_info[0]->director_signature_2;
            }
            else
            {
                $this->data["fs_report_details"] = [
                                                    array(
                                                        'first_set' => 1,
                                                        'accounting_standard_used' => 4,
                                                        'director_signature_1' => $client_signing_info[0]->director_signature_1,
                                                        'director_signature_2' => $client_signing_info[0]->director_signature_2
                                                    )];
            }

            // check company change name 
            $company_change_name = $this->db->query("SELECT tccn.company_name, tccn.new_company_name
                                                        FROM transaction_master tm
                                                        LEFT JOIN transaction_change_company_name tccn ON tccn.transaction_id = tm.id
                                                        WHERE tm.transaction_task_id = 12 AND tm.company_code = '" . $company_code . "' AND (tm.created_at BETWEEN '" . $this->data["fs_report_details"][0]['current_fye_begin'] . "'AND '" . $this->data["fs_report_details"][0]['current_fye_end'] . "')
                                                        ORDER BY tm.created_at");
            $company_change_name = $company_change_name->result_array();

            $this->data["fs_report_details"][0]['old_company_name'] = $company_change_name[0]['company_name'];
        }

        $this->data['is_prior_year_restated_dp'] = array(
                                                        '0' => 'No',
                                                        '1' => 'Yes'
                                                    );
        

        $interface = $this->load->view('/views/financial_statement/partial_company_particular.php', $this->data);

        echo $interface;
    }

    public function partial_director_interest_share()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];
        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $this->data['fs_company_info']           = $fs_company_info;
        $this->data['fs_dir_state_company']      = $this->fs_model->get_fs_dir_state_company($fs_company_info_id);
        $this->data['fs_dir_statement_director'] = $this->fs_model->get_fs_dir_statement_director($fs_company_info_id);

        $this->data['country_list'] = $this->fs_model->get_country_list();

        /* ---------------------------- Directors' share ---------------------------- */
        $fs_directors_20_percent = [];
        $fs_directors = $this->fs_model->get_fs_appt_directors($fs_company_info_id);

        for($x = 0; $x < count($fs_directors); $x++)
        {
            $x_director = $this->fs_model->get_shares((int)$fs_directors[$x]['id'], $fs_company_info);
            $total_begin_FY += $x_director[0]['begin_FY'];
            $total_end_FY   += $x_director[0]['end_FY'];
        }

        // display directors with more than 20% over total no. of share. 
        if($total_begin_FY > 0 || $total_end_FY > 0)
        {
            $percent_begin_FY = 0;
            $percent_end_FY = 0;

            for($g = 0; $g < count($fs_directors); $g++)
            {
                $director_html_string = $abstract_string_array[0][0];

                $director = $this->fs_model->get_shares((int)$fs_directors[$g]['id'], $fs_company_info);

                $percent_begin_FY = (float)$director[0]['begin_FY'] / $total_begin_FY * 100;
                $percent_end_FY = (float)$director[0]['end_FY'] / $total_end_FY * 100;

                if($percent_begin_FY > 20 || $percent_end_FY > 20)
                {
                    array_push($fs_directors_20_percent, 
                        array(
                            'director_name' => $this->encryption->decrypt($director[0]['name']),
                            'beg_direct_interest' => $this->fs_replace_content_model->negative_bracket($director[0]['begin_FY']),
                            'end_direct_interest' => $this->fs_replace_content_model->negative_bracket($director[0]['end_FY']),
                            'beg_deemed_interest' => '-',
                            'end_deemed_interest' => '-'
                        )
                    );
                }
            }
        }

        /* ---------------------------- END OF Directors' share ---------------------------- */

        $this->data['director_interest_list'] = $fs_directors_20_percent;

        $interface = $this->load->view('/views/financial_statement/partial_director_interest_share.php', $this->data);

        echo $interface;
    }

    public function partial_document_setup()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];
        $firm_id = $form_data['firm_id'];
        $client_id = $form_data['client_id'];
        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $this->data['fs_company_info'] = $fs_company_info;

        // $show_ly_TB_btn = false;

        // if(!$fs_company_info[0]['first_set'])
        // {
        //     $ly_tb_record = $this->db->query("SELECT * FROM fs_ly_trial_balance WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
        //     $ly_tb_record = $ly_tb_record->result_array();

        //     if(count($ly_tb_record) > 0)
        //     {
        //         $show_ly_TB_btn = true;
        //     }
        //     else
        //     {
        //         $q = $this->db->query("SELECT * FROM fs_company_info WHERE company_code = '" . $fs_company_info[0]['company_code'] . "' AND current_fye_end='" . $fs_company_info[0]['last_fye_end'] . "' AND firm_id=" . $firm_id);
        //         $q = $q->result_array();

        //         if(count($q) == 0)
        //         {
        //             $show_ly_TB_btn = true;
        //         }
        //     }
        // }

        // $this->data['show_ly_TB_btn'] = $show_ly_TB_btn;

        $this->data['dp_country_list'] = $this->fs_model->get_currency_list();

        $fs_ntfs_mc_data = $this->db->query("SELECT mc.*, c.name AS `currency_name`
                                                FROM fs_ntfs_master_currency mc
                                                LEFT JOIN currency c ON c.id = mc.currency_id
                                                WHERE fs_company_info_id=" . $fs_company_info_id);
        $fs_ntfs_mc_data = $fs_ntfs_mc_data->result_array();

        $this->data['fs_ntfs_mc_data'] = $fs_ntfs_mc_data;
        $this->data['firm_id']         = $firm_id;
        $this->data['client_id']       = $client_id;

        // $this->data['sub_account_list'] = $this->fs_account_category_model->get_default_sub_account_list();
        $this->data['group_type'] = $fs_company_info[0]['group_type'];
        $interface = $this->load->view('/views/financial_statement/partial_fs_doc_setup.php', $this->data);

        echo $interface;
    }

    public function partial_ntfs_layout()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $data = $this->fs_notes_model->get_insert_update_ntfs_layout_template($fs_company_info_id, 0);

        // print_r($data);

        // $data = $this->fs_notes_model->get_ntfs_layout_default();

        $this->data['layouts'] = $data;

        $interface = $this->load->view('/views/financial_statement/partial_ntfs_layout.php', $this->data);

        echo $interface;
    }

    public function get_firm_info()
    {
        $company_code = $_POST["company_code"];

        echo $this->fs_model->get_client_info($company_code);
    }

    public function get_this_directors_list()
    {
        $form_data = $this->input->post();

        // echo json_encode($form_data['fs_company_info_id']);
        $director_list = $this->fs_model->get_fs_appt_directors($form_data['company_code']);
        $arr_director_list = array();
        $arr_director_list[''] = '-- Select a director --';

        foreach($director_list as $director)
        {
            $arr_director_list[$director['id']] = $director['name'];
        }

        echo json_encode($arr_director_list);
    }

    public function upload_signing_report()
    {
        $fs_signing_report_id   = $_POST['fs_signing_report_id'];
        $fs_company_info_id     = $_POST['fs_company_info_id'];

        // print_r($fs_signing_report_id);

        if($_FILES['sr_input_file']['name']!="")
        {
            $target_dir = "documents/Signing Report/";

            $file = $_FILES['sr_input_file']['name'];
            $path = pathinfo($file);
            $filename = $path['filename'];
            $ext = $path['extension'];
            $temp_name = $_FILES['sr_input_file']['tmp_name'];
            $path_filename_ext = $target_dir.$filename.".".$ext;

            // print_r($_FILES['sr_input_file']);
            move_uploaded_file($temp_name,$path_filename_ext);

            // save file name
            $result = $this->fs_model->save_fs_signing_report($fs_signing_report_id, array('file_name' => $_FILES['sr_input_file']['name'], 'fs_company_info_id' => $fs_company_info_id));
        }
        else
        {
            $result = false;
        }

        echo json_encode($result);
    }

    public function submit_aud_report()
    {
        $form_data = $this->input->post();

        $data = array(
            'fs_company_info_id'        => $form_data['fs_company_info_id'],
            'fs_opinion_type_id'        => $form_data['fs_aud_report_opinion'],
            'opinion_fixed'             => $form_data['opinion_fixed'],
            'opinion_fixed_2'           => $form_data['opinion_fixed_2'],
            'basic_for_opinion'         => $form_data['basic_for_opinion'],
            'basic_for_opinion_fixed'   => $form_data['basic_for_opinion_fixed'],
            'key_audit_matter'          => $form_data['key_audit_matter'],
            'key_audit_matter_input'    => $form_data['key_audit_matter_input'],
            'emphasis_of_matter'        => $form_data['emphasis_of_matter'],
            'other_matters_checkbox'    => $form_data['other_matter_checkbox'],
            'last_year_not_audited'     => $form_data['ly_not_aud_checkbox'],
            'last_year_audited_by_other_company'    => $form_data['ly_other_aud_checkbox'],
            'last_year_audit_report_opinion_type'   => $form_data['fs_ly_report_opinion'],
            'date_of_auditors_report'   => $form_data['date_of_auditors_report'],
            'other_matters'             => $form_data['other_matters']
        );

        $status = $this->fs_model->save_fs_independent_audit_report($data);

        echo json_encode($status);
    }

    public function submit_company_particular()
    {
        $form_data           = $this->input->post();
        // print_r($form_data);

        // $firm_id             = $form_data['firm_id'];
        // $company_code        = $form_data["company_code"];
        // $fye_date            = $form_data["new_FYE"];
        // $date_of_incorporation = $form_data['date_of_incorporation'];
        // $is_group            = $form_data["group_checkbox"];
        // $is_first_set        = $form_data["first_set_checkbox"];
        $fs_company_info_id  = $form_data['fs_company_info_id'];
        $company_change_name = $form_data["change_com_name_checkbox"];
        $pre_company_name    = '';

        if($company_change_name)    // if company has old name
        {
            $pre_company_name = $form_data["prev_com_name"];
        }
        else
        {
            $pre_company_name = '';
        }

        // set id as 0 if director_signature is null
        if(is_null($form_data['director_signature_2']))
        {
            $director_signature_2 = 0;
        }
        else
        {
            $director_signature_2 = $form_data['director_signature_2'];
        }

        $fs_company_info = array(
            'id'                    => $fs_company_info_id,
            'firm_id'               => $form_data['firm_id'],
            'company_code'          => $form_data["company_code"],
            'company_liquidated'    => $form_data["company_liquidated"],
            'old_company_name'      => $pre_company_name,
            'date_of_resolution_for_change_of_name' => $form_data["date_resol_change_com_name"],
            'first_set'             => $form_data["first_set_checkbox"],
            'last_fye_begin'        => $form_data['last_fye_begin'],
            'last_fye_end'          => $form_data['last_fye_end'],
            'current_fye_begin'     => $form_data['current_fye_begin'],
            'current_fye_end'       => $form_data['current_fye_end'],
            'report_date'           => $form_data['report_date'],
            'is_audited'            => $form_data['is_audited_checkbox'],
            'group_type'            => $form_data['group_type'],
            'is_group_consolidated' => $form_data['is_group_consolidated'],
            'director_signature_1'     => $form_data['director_signature_1'],
            'director_signature_2'     => $director_signature_2,
            'accounting_standard_used' => $form_data['accounting_standard_used'],
            'act_applicable_type'      => $form_data['act_applicable_type']
            // 'is_prior_year_amount_restated'=> $form_data['is_prior_year_amount_restated'],
            // 'effect_of_restatement_since'  => $form_data['effect_of_restatement_since']
        );

        // print_r($fs_company_info);

        $fs_fp_currency_info = array(
            'id'                          => $form_data['fs_fp_currency_id'],
            'fs_company_info_id'          => $fs_company_info_id,
            'last_year_fc_currency_id'    => $form_data['last_year_fc_currency_id'],
            'current_year_fc_currency_id' => $form_data['current_year_fc_currency_id'],
            'reason_of_changing_fc'       => $form_data['reason_of_changing_fc'],
            'last_year_pc_currency_id'    => $form_data['last_year_pc_currency_id'],
            'current_year_pc_currency_id' => $form_data['current_year_pc_currency_id'],
            // 'reason_changing_fc_pc'       => $form_data['reason_changing_fc_pc']
        );

        // print_r($pre_company_name);

        $final_document_status = [];
        $final_document_status['is_create_report'] = true;
        $final_document_status['changed_final_document_type'] = false;

        if(empty($pre_company_name) || (!empty($pre_company_name) && !empty($form_data["date_resol_change_com_name"])))
        {
            // update "Statement Comprehensive Income" (profit/loss before after tax's description) if group type is changed
            if(!empty($fs_company_info_id))
            {
                $final_document_status['is_create_report'] = false;
                
                $result = $this->fs_statements_model->insert_update_fs_state_comp_income($fs_company_info_id);

                if($fs_company_info['first_set'])
                {
                    $this->fs_statements_model->delete_state_changes_in_equity_with_prior($fs_company_info_id, $fs_company_info['group_type']); // remove prior year data from "statement of changes in equity"
                }

                /* ----------------- Check if accounting standard is changed or not ----------------- */ 
                $fs_company_info_from_db = $this->fs_model->get_fs_company_info($fs_company_info_id);

                // check which document is using from db (1. Normal FRS, 2. Small FRS - Audited, 3. Small FRS - Non Audited)
                if($fs_company_info_from_db[0]['accounting_standard_used'] == 4)
                {
                    if($fs_company_info_from_db[0]['is_audited'])
                    {
                        $final_report_type_db = 2;
                    }
                    else
                    {
                        $final_report_type_db = 3;
                    }
                }
                else
                {
                    $final_report_type_db = 1;
                }

                // check current selected document 
                 if($fs_company_info['accounting_standard_used'] == 4)
                {
                    if($fs_company_info['is_audited'])
                    {
                        $final_report_type = 2;
                    }
                    else
                    {
                        $final_report_type = 3;
                    }
                }
                else
                {
                    $final_report_type = 1;
                }

                if($final_report_type != $final_report_type_db)
                {
                    $final_document_status['changed_final_document_type'] = true;
                }
                /* ----------------- END OF Check if accounting standard is changed or not ----------------- */ 
            }

            $fs_company_info_id = $this->fs_model->save_fs_company_info($fs_company_info);

            if(empty($fs_fp_currency_info['fs_company_info_id']))
            {
                $fs_fp_currency_info['fs_company_info_id'] = $fs_company_info_id;
            }

            $fs_fp_currency = $this->fs_model->save_fs_fp_currency_info($fs_fp_currency_info);

            $fs_company_info['id'] = $fs_company_info_id;

            $this->fs_notes_model->get_insert_update_ntfs_layout_template($fs_company_info_id, $final_document_status); // to change tick or untick for group accounting
            $this->fs_notes_model->update_related_notes($fs_company_info_id, $final_document_status);

            echo json_encode(array('result' => true, 'errormsg' => '', 'fs_company_info' => $fs_company_info));
        }
        else
        {
            if(!empty($pre_company_name) && empty($form_data["date_resol_change_com_name"]))
            {
                echo json_encode(array('result' => false, 'errormsg' => 'Something went wrong. Please try again later.', 'popup_msg' => '"Date of resolution for change of name" cannot be empty!'));
            }
            else
            {
                echo json_encode(array('result' => false, 'errormsg' => 'Something went wrong. Please try again later.', 'popup_msg' => ''));
            }
        }
    }

    public function submit_director_statement()
    {
        $form_data = $this->input->post();

        $arr_fs_deleted_company_id  = $form_data['arr_deleted_company'];
        $arr_fs_deleted_director_id = $form_data['arr_deleted_directors'];

        // echo $form_data['director_interest_checkbox'];
        $this->fs_model->update_fs_company_info($form_data['fs_company_info_id'], array('has_director_interest' => $form_data['director_interest_checkbox']));

        $this->fs_model->delete_company_directors($arr_fs_deleted_company_id, $arr_fs_deleted_director_id);

        // echo json_encode($arr_fs_deleted_company_id);
        // echo json_encode($arr_fs_deleted_director_id);

        // echo json_encode($form_data);

        $fs_company_info_id   = $form_data['fs_company_info_id']; 
        $company_director     = $form_data['company_director'];
        $fs_director_name     = $form_data['fs_director_name'];
        $fs_director_id       = $form_data['fs_director_id'];
        $fs_dir_begin_fy_nos  = $form_data['fs_dir_begin_fy_nos'];
        $fs_dir_end_fy_nos    = $form_data['fs_dir_end_fy_nos'];
        $fs_deem_begin_fy_nos = $form_data['fs_deem_begin_fy_nos'];
        $fs_deem_end_fy_nos   = $form_data['fs_deem_end_fy_nos'];
        
        $ultimate_id                        = $form_data['ultimate_id'];
        $ultimate_company                   = $form_data['ultimate_company'];
        $ultimate_country_id                = $form_data['ultimate_country_id'];
        // $ultimate_input_deem_begin_no_share = $form_data['ultimate_input_deem_begin_no_share'];
        // $ultimate_input_deem_end_no_share   = $form_data['ultimate_input_deem_end_no_share'];
        // $ultimate_input_dir_begin_no_share  = $form_data['ultimate_input_dir_begin_no_share'];
        // $ultimate_input_dir_end_no_share    = $form_data['ultimate_input_dir_end_no_share'];

        $intermediate_id                        = $form_data['intermediate_id'];
        $intermediate_company                   = $form_data['intermediate_company'];
        $intermediate_country_id                = $form_data['intermediate_country_id'];
        // $intermediate_input_deem_begin_no_share = $form_data['intermediate_input_deem_begin_no_share'];
        // $intermediate_input_deem_end_no_share   = $form_data['intermediate_input_deem_end_no_share'];
        // $intermediate_input_dir_begin_no_share  = $form_data['intermediate_input_dir_begin_no_share'];
        // $intermediate_input_dir_end_no_share    = $form_data['intermediate_input_dir_end_no_share'];

        $immediate_id                        = $form_data['immediate_id'];
        $immediate_company                   = $form_data['immediate_company'];
        $immediate_country_id                = $form_data['immediate_country_id'];
        // $immediate_input_deem_begin_no_share = $form_data['immediate_input_deem_begin_no_share'];
        // $immediate_input_deem_end_no_share   = $form_data['immediate_input_deem_end_no_share'];
        // $immediate_input_dir_begin_no_share  = $form_data['immediate_input_dir_begin_no_share'];
        // $immediate_input_dir_end_no_share    = $form_data['immediate_input_dir_end_no_share'];

        $corporate_id                        = $form_data['corporate_id'];
        $corporate_company                   = $form_data['corporate_company'];
        $corporate_country_id                = $form_data['corporate_country_id'];
        // $corporate_input_deem_begin_no_share = $form_data['corporate_input_deem_begin_no_share'];
        // $corporate_input_deem_end_no_share   = $form_data['corporate_input_deem_end_no_share'];
        // $corporate_input_dir_begin_no_share  = $form_data['corporate_input_dir_begin_no_share'];
        // $corporate_input_dir_end_no_share    = $form_data['corporate_input_dir_end_no_share'];

        $others_id                        = $form_data['others_id'];
        $others_company                   = $form_data['others_company'];
        $others_country_id                = $form_data['others_country_id'];
        // $others_input_deem_begin_no_share = $form_data['others_input_deem_begin_no_share'];
        // $others_input_deem_end_no_share   = $form_data['others_input_deem_end_no_share'];
        // $others_input_dir_begin_no_share  = $form_data['others_input_dir_begin_no_share'];
        // $others_input_dir_end_no_share    = $form_data['others_input_dir_end_no_share'];

        $ultimate_array = $this->build_dynamic_array($ultimate_id, $fs_company_info_id, $ultimate_company, $ultimate_country_id, 1, 
                                                    $fs_director_id, $company_director, $fs_director_name,
                                                    $fs_dir_begin_fy_nos, $fs_dir_end_fy_nos, $fs_deem_begin_fy_nos, $fs_deem_end_fy_nos
                                                );

        $intermediate_array = $this->build_dynamic_array($intermediate_id, $fs_company_info_id, $intermediate_company, $intermediate_country_id, 2,
                                                        $fs_director_id, $company_director, $fs_director_name,
                                                        $fs_dir_begin_fy_nos, $fs_dir_end_fy_nos, $fs_deem_begin_fy_nos, $fs_deem_end_fy_nos
                                                    );
        // print_r($intermediate_array);

        $immediate_array    = $this->build_dynamic_array($immediate_id, $fs_company_info_id, $immediate_company, $immediate_country_id, 3,
                                                      $fs_director_id, $company_director, $fs_director_name,
                                                    $fs_dir_begin_fy_nos, $fs_dir_end_fy_nos, $fs_deem_begin_fy_nos, $fs_deem_end_fy_nos
                                                );

        $corporate_array    = $this->build_dynamic_array($corporate_id, $fs_company_info_id, $corporate_company, $corporate_country_id, 4,
                                                        $fs_director_id, $company_director, $fs_director_name,
                                                        $fs_dir_begin_fy_nos, $fs_dir_end_fy_nos, $fs_deem_begin_fy_nos, $fs_deem_end_fy_nos
                                                    );

        $others_array       = $this->build_dynamic_array($others_id, $fs_company_info_id, $others_company, $others_country_id, 5,
                                                        $fs_director_id, $company_director, $fs_director_name,
                                                        $fs_dir_begin_fy_nos, $fs_dir_end_fy_nos, $fs_deem_begin_fy_nos, $fs_deem_end_fy_nos
                                                    );

        $fs_dir_state_company = array($ultimate_array, $intermediate_array, $immediate_array, $corporate_array, $others_array);

        $result = $this->fs_model->save_bundle_fs_director_statement($fs_dir_state_company);

        echo json_encode(array('status' => true));
    }

    public function build_dynamic_array($id, $fs_company_info_id, $company_name, $country_id, $fs_company_type_id, 
                                        $fs_director_id, $company_director, $fs_director_name,
                                        $fs_dir_begin_fy_nos, $fs_dir_end_fy_nos, $fs_deem_begin_fy_nos, $fs_deem_end_fy_nos)
    {
        $temp_array = array();
        $dir_array  = array();

        foreach($company_name as $company_key=>$company)
        {
            $i = $company_key;

            if(!($company_name[$i] == ''))
            {
                array_push(
                    $temp_array, array(
                        'id'                        => $id[$i],
                        'fs_company_info_id'        => $fs_company_info_id,
                        'company_name'              => $company_name[$i],
                        'country_id'                => $country_id[$i],
                        'fs_company_type_id'        => $fs_company_type_id,
                        'index'                     => $i                   // mark company for director purpose.
                    )
                );

                foreach($company_director as $director_key=>$director)
                {
                    if($director == $company_key)
                    {
                        if(!empty($fs_director_name[$director_key]) && !empty($fs_dir_begin_fy_nos[$director_key]) && !empty($fs_dir_end_fy_nos[$director_key]) && !empty($fs_deem_begin_fy_nos[$director_key]) && !empty($fs_deem_end_fy_nos[$director_key]))
                        {
                            array_push($dir_array,
                                array(
                                    'id'            => $fs_director_id[$director_key],
                                    'company_index' => $company_key,    // so that can find back the director belong to which company.
                                    'director_name' => $fs_director_name[$director_key],
                                    'dir_begin_fy_no_of_share'  => $fs_dir_begin_fy_nos[$director_key],
                                    'dir_end_fy_no_of_share'    => $fs_dir_end_fy_nos[$director_key],
                                    'deem_begin_fy_no_of_share' => $fs_deem_begin_fy_nos[$director_key],
                                    'deem_end_fy_no_of_share'   => $fs_deem_end_fy_nos[$director_key]
                                )
                            );
                        }

                        
                    }
                }
            }
        }

        return array($temp_array, $dir_array);
    }

    public function replace_tagging($fs_doc_template, $fs_company_info_id)
    {
        // print_r(array($fs_doc_template));
        // echo "replace_tagging = " . $fs_company_info_id;
        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);
        // $fs_company_info = $fs_company_info->result_array();

        foreach($fs_doc_template as $item)
        {
            $pattern = "/{{[^}}]*}}/";
            $subject = $item->content;
            preg_match_all($pattern, $subject, $matches);

            $new_contents = $subject;
            // echo json_encode($matches);
            if(count($matches[0]) != 0)
            {
                for($r = 0; $r < count($matches[0]); $r++)
                {
                    $string1 = (str_replace('{{', '',$matches[0][$r]));
                    $string2 = (str_replace('}}', '',$string1));

                    if($string2 == "client name")
                    {
                        $replace_string = $matches[0][$r];

                        $content = $this->encryption->decrypt($fs_company_info[0]['company_name']);
                    }
                    elseif($string2 == "Current Year End - Ending")
                    {
                        $replace_string = $matches[0][$r];
                        $content = $fs_company_info[0]['current_fye_end'];
                    }
                    elseif($string2 == "Act Applicable")
                    {
                        $replace_string = $matches[0][$r];
                        $content = $fs_company_info[0]['act_applicable_type_name']; // temporary put as empty.
                    }
                    elseif($string2 == "Accounting Standard used")
                    {
                        $replace_string = $matches[0][$r];
                        $content = $fs_company_info[0]['accounting_standard_used_name']; // temporary put as empty.
                    }

                    $new_contents = str_replace($replace_string, $content, $new_contents);
                }

                $item->content = $new_contents;
            }
        }
        return $fs_doc_template;
    }

    public function replace_verbs_plural($fs_doc_template, $fs_company_info_id)
    {
        foreach($fs_doc_template as $item)
        {
            // echo json_encode($item);
            $pattern = "/{_[^}}]*_}/";
            $subject = $item->content;
            preg_match_all($pattern, $subject, $match);

            // echo json_encode($new_contents);
            $new_contents = $subject;

            $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

            $client_info = $this->db->query("SELECT * FROM client WHERE company_code='" . $fs_company_info[0]["company_code"] . "'");
            $client_info = $client_info->result_array();

            $directors = $this->fs_model->get_fs_dir_statement_director($fs_company_info_id);

            $isPlural = count($directors) > 1? true: false;

            if(count($match[0]) != 0)
            {
                for($r = 0; $r < count($match[0]); $r++)
                {
                    // echo "hi " . $match[0][$r];
                    $string1 = (str_replace('{_', '',$match[0][$r]));
                    $string2 = (str_replace('_}', '',$string1));

                    $content = '';

                    if($string2 == "Group/Company")
                    {
                        // echo json_encode("GROUP ? COMPANY");
                        $replace_string = $match[0][$r];

                        if($fs_company_info[0]['group_type']!= 0)
                        {
                            $content = 'Group';
                        }
                        else 
                        {
                            $content = 'Company';
                        }
                    }
                    elseif($string2 == "and its subsidiaries")
                    {
                        $replace_string = $match[0][$r];

                        if($fs_company_info[0]['group_type'] == 2)
                        {
                            $content = 'and its subsidiary';
                        }
                        elseif($fs_company_info[0]['group_type'] == 3)
                        {
                            $content = 'and its subsidiaries';
                        }
                        else 
                        {
                            $content = '';
                        }
                    }
                    elseif($string2 == "consolidated")
                    {
                        $replace_string = $match[0][$r];

                        if($fs_company_info[0]['group_type'] != 0)
                        {
                            $content = 'consolidated';
                        }
                        else 
                        {
                            $content = 'the';
                        }
                    }
                    elseif($string2 == "sing/plu s")
                    {
                        $replace_string = $match[0][$r];

                        if($isPlural)
                        {
                            $content = "s";
                        }
                    }
                    elseif($string2 == "sing/plu s'")
                    {
                        $replace_string = $match[0][$r];
                        
                        if($isPlural)
                        {
                            $content = "s'";
                        }
                        else
                        {
                            $content = "'s";
                        }
                    }
                    elseif($string2 == "tense's")
                    {
                        $replace_string = $match[0][$r];

                        if(!$isPlural)
                        {
                            $content = "s";
                        }
                    }
                    elseif($string2 == "is/are")
                    {
                        $replace_string = $match[0][$r];

                        if(!$isPlural)
                        {
                            $content = "is";
                        }
                        else
                        {
                            $content = "are";
                        }
                    }
                    elseif($string2 == "the/their")
                    {
                        $replace_string = $match[0][$r];

                        if(!$isPlural)
                        {
                            $content = "the";
                        }
                        else
                        {
                            $content = "their";
                        }
                    }

                    $new_contents = str_replace($replace_string, $content, $new_contents);
                }

                $item->content = $new_contents;
                // echo json_encode($fs_doc_template);
            }
        }
        return $fs_doc_template;
    }

    public function replace_tagging_verbs($content, $fs_company_info_id)  // replace tagging and then replace verbs plural
    {
        $content = $this->replace_tagging($content, $fs_company_info_id);
        $content = $this->replace_verbs_plural($content, $fs_company_info_id);

        return $content;
    }

    public function get_fs_company_info()
    {
        $form_data = $this->input->post();

        $fs_company_info_id = $form_data['fs_company_info_id'];

        echo json_encode($this->fs_model->get_fs_report_details($fs_company_info_id));
    }

    public function replace_dynamic_content()
    {
        $form_data = $this->input->post();

        // $fs_company_info_id = $form_data['fs_company_info_id'];
        $template = $form_data['template'];
        $choice  = $form_data['choice'];

        $pattern = "/{{[^}}]*}}/";
        $subject = $template;
        preg_match_all($pattern, $subject, $matches);

        $new_contents = $subject;

        if(count($matches[0]) != 0)
        {
            for($r = 0; $r < count($matches[0]); $r++)
            {
                $string1 = (str_replace('{{', '',$matches[0][$r]));
                $string2 = (str_replace('}}', '',$string1));

                if($string2 == "have audited/were engaged to audit")
                {
                    $replace_string = $matches[0][$r];

                    if($choice == 4)
                    {
                        $content = "were engaged to audit";
                    }
                    else
                    {
                        $content = "have audited";
                    }
                }
                // elseif($string2 == "Current Year End - Ending")
                // {
                //     $replace_string = $matches[0][$r];
                //     $content = $fs_company_info[0]['current_fye_end'];
                // }

                $new_contents = str_replace($replace_string, $content, $new_contents);
            }

            echo $new_contents;
        }
    }

    // public function open_link()
    // {
    //     $form_data = $this->input->post();

    //     $filename = $form_data['filename'];
    //     $location = $form_data['location'];

    //     echo json_encode(array('link' => $location))

    //     print_r($form_data);
    // }
}