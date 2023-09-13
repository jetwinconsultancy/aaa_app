<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fs_statements extends MY_Controller
{
    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        //$this->load->library('form_validation');
        $this->load->library(array('session'));
        $this->load->model('fs_model');
        $this->load->model('fs_statements_model');
        $this->load->model('fs_account_category_model');

        $this->load->model('fs_notes_model');
    }

    // public function index()
    // {
    //     $bc   = array(array('link' => '#', 'page' => lang('Documents')));
    //     $meta = array('page_title' => lang('Documents'), 'bc' => $bc, 'page_name' => 'Documents');

    //     $firm_id = $this->session->userdata("firm_id");

    //     $this->data['fs_report_list'] = $this->fs_model->get_fs_report_list($firm_id);

    //     $this->page_construct('financial_statement/index.php', $meta, $this->data);
    // }

    public function state_detailed_pro_loss()
    {
        $form_data = $this->input->post();

        $fs_company_info_id = $form_data['fs_company_info_id'];
        $fs_company_info    = $this->fs_model->get_fs_company_info($fs_company_info_id);

        // $current_last_year_end = $this->fs_model->calculate_difference_dates($fs_company_info_id);

         // initialise
        $this->data["current_fye_end"]  = '';
        $this->data["last_fye_end"]     = '';
        $this->data["display_restated"] = false;

        /* Don't delete this */
        // if($fs_company_info[0]['is_prior_year_amount_restated'])
        // {
        //     if(strcmp($fs_company_info[0]['effect_of_restatement_since'], $fs_company_info[0]['last_fye_begin']) == 0)
        //     {
        //         $this->data["display_restated"] = true;
        //     }
        // }
        /* Don't delete this */

        // display column(s) for years
        $current_last_year_end = $this->fs_model->calculate_difference_dates($fs_company_info_id, "General");

        $this->data["current_fye_end"]  = $current_last_year_end['current_fye_end'];    // this year end eg. 31/12/2019
        $this->data["last_fye_end"]     = $current_last_year_end['last_fye_end'];       // end of previous year end

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

        $this->data['state_detailed_pro_loss_data'] = $dpl_data;

        /* ---------------------- END OF get statement of detailed profit or loss data ---------------------- */

        /* -------------------------------- Get total amount of Schedule of Operating Expenses -------------------------------- */
        $er_key = array_search("Schedule of operating expenses", array_column($fs_ntfs_list['statements'], 'document_name'));
        $er_ref_id = '';

        if($er_key || (string)$er_key == '0')
        {
            $er_account_code = $fs_ntfs_list['statements'][$er_key]['reference_id']; // get account code
        }

        $fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, $er_account_code);

        $get_expenses                       = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $fca_id);
        $total_operating_expenses_current   = $get_expenses[0]['parent_array'][0];
        /* -------------------------------- END OF Get total amount of Schedule of Operating Expenses -------------------------------- */

        $this->data['total_operating_expenses_current'] = array(
                                                            'total_operating_expenses'      => $total_operating_expenses_current['total_c'],
                                                            'total_operating_expenses_ly'   => $total_operating_expenses_current['total_c_lye']
                                                        );
        $this->data['show_data_content'] = $this->fs_statements_model->is_saved_fs_categorized_account_round_off($fs_company_info_id);
        $this->data['fs_ntfs_list'] = $fs_ntfs_list['statements'][$dpl_key]['description_reference_id'];

        $interface = $this->load->view('/views/financial_statement/template/fs_statements/state_detailed_pro_loss.php', $this->data);
    }

    public function schedule_operating_expense()
    {
        $form_data = $this->input->post();

        $fs_company_info_id = $form_data['fs_company_info_id'];
        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        // echo json_encode($fs_company_info);

        // initialise
        $this->data["current_fye_end"]  = '';
        $this->data["last_fye_end"]     = '';
        $this->data["display_restated"] = false;

        /* Don't delete this */
        // if($fs_company_info[0]['is_prior_year_amount_restated'])
        // {
        //     if(strcmp($fs_company_info[0]['effect_of_restatement_since'], $fs_company_info[0]['last_fye_begin']) == 0)
        //     {
        //         $this->data["display_restated"] = true;
        //     }
        // }
        /* Don't delete this */

        // display column(s) for years
        $current_last_year_end = $this->fs_model->calculate_difference_dates($fs_company_info_id, "General");

        $this->data["current_fye_end"]  = $current_last_year_end['current_fye_end'];    // this year end eg. 31/12/2019
        $this->data["last_fye_end"]     = $current_last_year_end['last_fye_end'];      // end of previous year end

        
        // $this->data['schedule_operating_expense_data'] = $this->fs_statements_model->get_account_category_item_round_off_list($fs_company_info_id, array('S0006', 'S0007', 'S0008'));
        $fs_ntfs_settings_list = $this->fs_notes_model->get_fs_ntfs_json();
        $er_key = array_search("Schedule of operating expenses", array_column($fs_ntfs_settings_list['statements'], 'document_name'));

        $er_ref_id = '';

        if($er_key || (string)$er_key == '0')
        {
            $er_account_code = $fs_ntfs_settings_list['statements'][$er_key]['reference_id']; // get account code
        }

        $fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, $er_account_code);

        $this->data['schedule_operating_expense_data'] = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $fca_id);
        $this->data['show_data_content'] = $this->fs_statements_model->is_saved_fs_categorized_account_round_off($fs_company_info_id);

        // print_r($this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $fca_id));

        $interface = $this->load->view('/views/financial_statement/template/fs_statements/schedule_operating_expense.php', $this->data);
    }

    public function partial_state_comp_income()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $fs_company_info       = $this->fs_model->get_fs_company_info($fs_company_info_id);
        $current_last_year_end = $this->fs_model->calculate_difference_dates($fs_company_info_id, "General");

        if($fs_company_info[0]["group_type"] == '1')
        {
            $this->data['is_group'] = false;
        }
        else
        {
            $this->data['is_group'] = true;
        }

        $this->data["last_fye_end"]     = $current_last_year_end['last_fye_end'];
        $this->data["current_fye_end"]  = $current_last_year_end['current_fye_end'];

        // // IF REPORT IS FIRST SET
        // if($fs_company_info[0]['first_set'])
        // {
        //     $this->data["current_fye_end"]  = date('Y', strtotime($fs_company_info[0]['current_fye_end']));    // this year end eg. 31/12/2019
        //     $this->data["last_fye_end"]     = '';       // end of previous year end
        // }

        // if fs_state_comp_income has the list, load the list else setup the values.
        // $fs_state_comp_list = $this->fs_statements_model->get_fs_state_comp_income($fs_company_info_id);

        /* setup values for statement of comprehensive income */ 
        $fs_statement_list = $this->fs_statements_model->get_fs_statement();    // get list of code from json 
        $data = $this->fs_statements_model->get_all_adjusted_state_comp_income($fs_company_info_id); 

        // print_r($data['pl_after_tax']);

        $this->data["income_list"]            = $data['income_list'];
        $this->data["other_income_list"]      = $data['other_income_list'];
        $this->data["changes_in_inventories"] = $data['changes_in_inventories'];
        $this->data["purchases"]              = $data['purchases_and_related_costs'];
        $this->data["pl_be4_tax"]             = $data['pl_be4_tax'];
        $this->data["pl_after_tax"]           = $data['pl_after_tax'];
        $this->data["expense_list"]           = $data['expense_list'];
        $this->data["additional_list"]        = $data['additional_list'];
        $this->data["soa_pl_list"]            = $data['soa_pl_list'];
        $this->data["other_list"]             = $data['other_list'];
        // $this->data['involved_note_list']     = $linked_note;
        $this->data['fs_notes_details']             = $this->fs_notes_model->get_fs_note_details_for_state_comp_income($fs_company_info_id, 1);
        $this->data['fs_notes_details_state_2']     = $this->fs_notes_model->get_fs_note_details_for_state_comp_income($fs_company_info_id, 2);
        $this->data["fs_ntfs_layout_template_list"] = $this->fs_notes_model->get_ntfs_layout_template_parents($fs_company_info_id);
        $this->data['show_data_content']            = $this->fs_statements_model->is_saved_fs_categorized_account_round_off($fs_company_info_id);

        // get auto rearrange notes from fs_settings
        $auto_rearrange_data = $this->fs_model->get_fs_settings($fs_company_info_id);
        $on_rearrange_notes = 1;

        if(count($auto_rearrange_data) > 0)
        {
            $on_rearrange_notes = $auto_rearrange_data[0]['auto_rearrange_notes_no'];
        }

        $this->data['auto_rearrange_value'] = $on_rearrange_notes;

        // print_r($this->data["additional_list"]);
        // print_r($this->data['fs_notes_details']);

        $interface = $this->load->view('/views/financial_statement/template/fs_statements/partial_state_comp_income.php', $this->data);

        // echo $interface;
    }

    public function partial_state_changes_in_equity()
    {
        $form_data             = $this->input->post();

        $fs_company_info_id    = $form_data['fs_company_info_id'];
        $group_company         = $form_data['group_company'];

        $fs_company_info       = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $this->data['first_set'] = $fs_company_info[0]['first_set'];
        $this->data['group_company'] = ucfirst(strtolower($group_company));

        $temp_fs_state_changes_in_equity_current_group = $this->fs_statements_model->get_fs_state_changes_in_equity($fs_company_info_id, "current", $group_company);

        foreach ($temp_fs_state_changes_in_equity_current_group as $key => &$row) 
        {
            $temp_row = $row['row_item'];
            $temp_row = explode(",", $temp_row);

            $row['row_item'] = $temp_row;
        }

        if($fs_company_info[0]['first_set'])
        {
            $temp_fs_state_changes_in_equity_prior_group = [];
        }
        else
        {
            $temp_fs_state_changes_in_equity_prior_group = $this->fs_statements_model->get_fs_state_changes_in_equity($fs_company_info_id, "prior", $group_company);
            foreach ($temp_fs_state_changes_in_equity_prior_group as $key => &$row) 
            {
                $temp_row = $row['row_item'];
                $temp_row = explode(",", $temp_row);

                $row['row_item'] = $temp_row;
            }
        }

        $temp_fs_state_changes_in_equity_header = $this->fs_statements_model->get_fs_state_changes_in_equity_header($fs_company_info_id, $group_company);
        $temp_header = $temp_fs_state_changes_in_equity_header[0]['header_titles'];
        if( $temp_header )
        {
            $temp_header = explode(',',$temp_header);
        }
        
        $temp_fs_state_changes_in_equity_footer = $this->fs_statements_model->get_fs_state_changes_in_equity_footer($fs_company_info_id, $group_company);
        foreach ($temp_fs_state_changes_in_equity_footer as $key => &$row) 
        {
            $temp_row = $row['footer_item'];
            $temp_row = explode(",", $temp_row);

            $row['footer_item'] = $temp_row;
        }

        /* --------------- prior values --------------- */
        if(count($temp_fs_state_changes_in_equity_prior_group) == 0 && !$fs_company_info[0]['first_set'])
        {   
            $ly_fs_company_info_id = $this->fs_model->get_fs_company_info_last_year($fs_company_info_id);

            /* Profit after tax (Current Year) */
            $ly_total_pl = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_company_info_id=" . $ly_fs_company_info_id . " AND fs_list_state_comp_income_section_id=4");
            $ly_total_pl = $ly_total_pl->result_array();
            /* END OF Profit after tax (Current Year) */

            /* Total comprehensive income for the year (Current Year) */
            $ly_total_comprehensive_income_data = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_company_info_id=" . $ly_fs_company_info_id . " AND fs_list_state_comp_income_section_id=5");
            $ly_total_comprehensive_income_data = $ly_total_comprehensive_income_data->result_array();

            $ly_total_ci = array(
                            'total_c'     => 0.00,
                            'total_c_lye' => 0.00,
                            'total_g'     => 0.00,
                            'total_g_lye' => 0.00
                        );

            foreach ($ly_total_comprehensive_income_data as $key => $value) 
            {
                $ly_total_ci['total_c']     += $value['value_company_ye'];
                $ly_total_ci['total_c_lye'] += $value['value_company_lye_end'];
                $ly_total_ci['total_g']     += $value['value_group_ye'];
                $ly_total_ci['total_g_lye'] += $value['value_group_lye_end'];
            }
            /* END OF Total comprehensive income for the year (Current Year) */

             /* ------------------ Sum up value to get "Share Capital (Q101)" ------------------ */
            $sc_fca_id            = $this->fs_notes_model->get_fca_id($ly_fs_company_info_id, array('Q101'));
            $share_capital_values = $this->fs_account_category_model->get_account_with_sub_round_off_ids($ly_fs_company_info_id, $sc_fca_id);
            /* ------------------ END OF Sum up value to get "Share Capital (Q101)" ------------------ */

            /* ------------------ Sum up value to get "revenue reserve (Q103)" ------------------ */
            $fs_statement_list = $this->fs_statements_model->get_fs_statement();

            $for_revenue_reserve_acc_code = $fs_statement_list->statement_financial_position[0]->for_revenue_reserve;
            array_push($for_revenue_reserve_acc_code, 'Q103');

            $ly_fca_ids_for_revenue_reserve = $this->fs_notes_model->get_fca_id($ly_fs_company_info_id, $for_revenue_reserve_acc_code);
            $ly_data_for_revenue_reserve = $this->fs_account_category_model->get_account_with_sub_round_off_ids($ly_fs_company_info_id, $ly_fca_ids_for_revenue_reserve);

            $ly_total_for_revenue_reserve = array(
                                            'total_c'     => 0.00,
                                            'total_c_lye' => 0.00,
                                            'total_g'     => 0.00,
                                            'total_g_lye' => 0.00
                                        );

            foreach ($ly_data_for_revenue_reserve as $rr_key => $rr_value) 
            {
                $ly_total_for_revenue_reserve['total_c']       += $rr_value['parent_array'][0]['total_c'];
                $ly_total_for_revenue_reserve['total_c_lye']   += $rr_value['parent_array'][0]['total_c_lye'];
                $ly_total_for_revenue_reserve['total_g']       += $rr_value['parent_array'][0]['total_g'];
                $ly_total_for_revenue_reserve['total_g_lye']   += $rr_value['parent_array'][0]['total_g_lye'];
            }

            /* ------------------ END OF Sum up value to get "revenue reserve (Q103)" ------------------ */

            $ly_rr_value = 0.00;
            $ly_sc_value = 0.00;

            /* Profit after tax (Prior Year) */
            $ly_tci = 0.00;
            $ly_pl  = 0.00;
            /* END OF Profit after tax (Prior Year) */

            if($group_company == 'group')
            {
                $ly_rr_value = $ly_total_for_revenue_reserve['total_g_lye'] * -1;
                $ly_sc_value = $share_capital_values[0]['parent_array'][0]['total_g_lye'];

                $ly_rr_value_end = $ly_total_for_revenue_reserve['total_g'] * -1;
                $ly_sc_value_end = $share_capital_values[0]['parent_array'][0]['total_g'];

                $ly_tci = $ly_total_ci['total_g'];     // total comprehensive income
                $ly_pl  = $ly_total_ci['total_g'] + $ly_total_pl[0]['value_group_ye'];  // profit after tax
            }
            else
            {
                $ly_rr_value = $ly_total_for_revenue_reserve['total_c_lye'] * -1;
                $ly_sc_value = $share_capital_values[0]['parent_array'][0]['total_c_lye'];

                $ly_rr_value_end = $ly_total_for_revenue_reserve['total_c'] * -1;
                $ly_sc_value_end = $share_capital_values[0]['parent_array'][0]['total_c'];

                $ly_tci = $ly_total_ci['total_c'];         // total comprehensive income
                $ly_pl  = $ly_total_ci['total_c'] + $ly_total_pl[0]['value_company_ye'];    // profit after tax
            }

            array_push($temp_fs_state_changes_in_equity_prior_group, 
                array(
                    'current_prior' => "prior",
                    'description'   => "Balance at " . $fs_company_info[0]['last_fye_begin'],
                    'fs_company_info_id' => $fs_company_info_id,
                    'group_company' => $group_company,
                    'is_subtotal'   => 0,
                    'row_item'      => array(
                                            $ly_sc_value,
                                            $ly_rr_value,
                                            $ly_rr_value + $ly_sc_value
                                        ),
                    'row_order'     => 0
                )
            );

            array_push($temp_fs_state_changes_in_equity_prior_group, 
                array(
                    'current_prior' => "prior",
                    'description'   => "Total comprehensive income for the year",
                    'fs_company_info_id' => $fs_company_info_id,
                    'group_company' => $group_company,
                    'is_subtotal'   => 0,
                    'row_item'      => array(
                                            $ly_tci,
                                            $ly_pl,
                                            $ly_tci + $ly_pl
                                        ),
                    'row_order'     => 1
                )
            );
        }
        /* --------------- END OF prior values --------------- */

        /* --------------- current values --------------- */

        /* Profit after tax (Current Year) */
        $total_pl = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_company_info_id=" . $fs_company_info_id . " AND fs_list_state_comp_income_section_id=4");
        $total_pl = $total_pl->result_array();

        // print_r($total_pl);
        /* END OF Profit after tax (Current Year) */

        /* Total comprehensive income for the year (Current Year) */
        $total_comprehensive_income_data = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_company_info_id=" . $fs_company_info_id . " AND fs_list_state_comp_income_section_id=5");
        $total_comprehensive_income_data = $total_comprehensive_income_data->result_array();

        $total_ci = array(
                        'total_c'     => 0.00,
                        'total_c_lye' => 0.00,
                        'total_g'     => 0.00,
                        'total_g_lye' => 0.00
                    );

        foreach ($total_comprehensive_income_data as $key => $value) 
        {
            $total_ci['total_c']     += $value['value_company_ye'];
            $total_ci['total_c_lye'] += $value['value_company_lye_end'];
            $total_ci['total_g']     += $value['value_group_ye'];
            $total_ci['total_g_lye'] += $value['value_group_lye_end'];
        }
        /* END OF Total comprehensive income for the year (Current Year) */

        if(count($temp_fs_state_changes_in_equity_current_group) == 0)
        {   
            /* ------------------ Sum up value to get "revenue reserve (Q103)" ------------------ */
            $fs_statement_list           = $this->fs_statements_model->get_fs_statement();

            $for_revenue_reserve_acc_code = $fs_statement_list->statement_financial_position[0]->for_revenue_reserve;
            array_push($for_revenue_reserve_acc_code, 'Q103');

            $fca_ids_for_revenue_reserve = $this->fs_notes_model->get_fca_id($fs_company_info_id, $for_revenue_reserve_acc_code);
            $data_for_revenue_reserve    = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $fca_ids_for_revenue_reserve);

            $total_for_revenue_reserve = array(
                                            'total_c'     => 0.00,
                                            'total_c_lye' => 0.00,
                                            'total_g'     => 0.00,
                                            'total_g_lye' => 0.00
                                        );

            foreach ($data_for_revenue_reserve as $rr_key => $rr_value) 
            {
                $total_for_revenue_reserve['total_c']       += $rr_value['parent_array'][0]['total_c'];
                $total_for_revenue_reserve['total_c_lye']   += $rr_value['parent_array'][0]['total_c_lye'];
                $total_for_revenue_reserve['total_g']       += $rr_value['parent_array'][0]['total_g'];
                $total_for_revenue_reserve['total_g_lye']   += $rr_value['parent_array'][0]['total_g_lye'];
            }
            /* ------------------ END OF Sum up value to get "revenue reserve (Q103)" ------------------ */

            /* ------------------ Sum up value to get "Share Capital (Q101)" ------------------ */
            $sc_fca_id            = $this->fs_notes_model->get_fca_id($fs_company_info_id, array('Q101'));
            $share_capital_values = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $sc_fca_id);
            /* ------------------ END OF Sum up value to get "revenue reserve (Q101)" ------------------ */

            $rr_value_data = 0.00;
            $sc_value_data = 0.00;

            if($group_company == 'group')
            {
                $rr_value_data = $total_for_revenue_reserve['total_g_lye'] * -1;
                $sc_value_data = $share_capital_values[0]['parent_array'][0]['total_g_lye'];

                $rr_value_data_end = $total_for_revenue_reserve['total_g'] * -1;
                $sc_value_data_end = $share_capital_values[0]['parent_array'][0]['total_g'];
            }
            else
            {
                $rr_value_data = $total_for_revenue_reserve['total_c_lye'] * -1;
                $sc_value_data = $share_capital_values[0]['parent_array'][0]['total_c_lye'];

                $rr_value_data_end = $total_for_revenue_reserve['total_c'] * -1;
                $sc_value_data_end = $share_capital_values[0]['parent_array'][0]['total_c'];
            }

            array_push($temp_fs_state_changes_in_equity_current_group, 
                array(
                    'current_prior' => "current",
                    'description'   => "Balance at " . $fs_company_info[0]['current_fye_begin'],
                    'fs_company_info_id' => $fs_company_info_id,
                    'group_company' => $group_company,
                    'is_subtotal'   => 0,
                    'row_item'      => array(
                                            $sc_value_data,
                                            $rr_value_data,
                                            $sc_value_data + $rr_value_data
                                        ),
                    'row_order'     => 0
                )
            );

            if($group_company == 'group')
            {
                $tci = (int)$total_ci['total_g_ye'];
                $pl  = (int)$total_ci['total_g_ye'] + (int)$total_pl[0]['value_group_ye'];
            }
            else
            {
                $tci = (int)$total_ci['total_c_ye'];
                $pl  = (int)$total_ci['total_c_ye'] + (int)$total_pl[0]['value_company_ye'];
            }

            // print_r(array($total_pl, $tci, $pl));

            array_push($temp_fs_state_changes_in_equity_current_group, 
                array(
                    'current_prior' => "current",
                    'description'   => "Total comprehensive income for the year",
                    'fs_company_info_id' => $fs_company_info_id,
                    'group_company' => $group_company,
                    'is_subtotal'   => 0,
                    'row_item'      => array(
                                            $tci,
                                            $pl,
                                            $tci + $pl
                                        ),
                    'row_order'     => 1
                )
            );
        }
        /* --------------- END OF current values --------------- */

        /* --------------- Footer values --------------- */
        if(count($temp_fs_state_changes_in_equity_footer) < 2)
        {
            if(count($temp_fs_state_changes_in_equity_footer) == 0)
            {
                array_push($temp_fs_state_changes_in_equity_footer, 
                    array(
                        'current_prior' => "current",
                        'description'   => "Balance at " . $fs_company_info[0]['current_fye_end'],
                        'fs_company_info_id' => $fs_company_info_id,
                        'group_company' => $group_company,
                        'footer_item'   => array(
                                                $sc_value_data_end,
                                                $rr_value_data_end,
                                                ($sc_value_data_end + $rr_value_data_end)
                                            )
                    )
                );
            }

            if(!$fs_company_info[0]['first_set'])
            {
                array_push($temp_fs_state_changes_in_equity_footer, 
                    array(
                        'current_prior' => "prior",
                        'description'   => "Balance at " . $fs_company_info[0]['last_fye_end'],
                        'fs_company_info_id' => $fs_company_info_id,
                        'group_company' => $group_company,
                        'footer_item'   => array(
                                                // $ly_sc_value + $ly_tci,
                                                // $ly_rr_value + $ly_pl,
                                                // ($ly_rr_value + $ly_sc_value) + ($ly_tci + $ly_pl)
                                                $ly_sc_value_end,
                                                $ly_rr_value_end,
                                                ($ly_rr_value_end + $ly_sc_value_end)   // don't total up because we are retrieve data from other part. So that auditor can check it.
                                            )
                    )
                );
            }
        }
        /* --------------- END OF Footer values --------------- */

        // print_r($temp_fs_state_changes_in_equity_footer);
        // print_r($temp_fs_state_changes_in_equity_current_group);
        // print_r($temp_fs_state_changes_in_equity_prior_group);

        $this->data['fs_state_changes_in_equity_current_group'] = $temp_fs_state_changes_in_equity_current_group;
        $this->data['fs_state_changes_in_equity_prior_group']   = $temp_fs_state_changes_in_equity_prior_group;
        $this->data['fs_state_changes_in_equity_header']        = $temp_header;
        $this->data['fs_state_changes_in_equity_footer']        = $temp_fs_state_changes_in_equity_footer;
        $this->data['show_data_content'] = $this->fs_statements_model->is_saved_fs_categorized_account_round_off($fs_company_info_id);

        // print_r($this->fs_statements_model->get_fs_state_changes_in_equity($fs_company_info_id, "current", "group"));


        $interface = $this->load->view('/views/financial_statement/template/fs_statements/partial_state_changes_in_equity.php', $this->data);
    }

    public function partial_state_cash_flows() 
    {   
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $fs_company_info                 = $this->fs_model->get_fs_company_info($fs_company_info_id);
        $temp_all_state_cash_flows_fixed = $this->fs_statements_model->get_fs_state_cash_flows_fixed($fs_company_info_id);
        $temp_arr = array();

        $pl_be4_tax_values_from_sci = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_company_info_id=" . $fs_company_info_id . " AND fs_list_state_comp_income_section_id=3");
        $pl_be4_tax_values_from_sci = $pl_be4_tax_values_from_sci->result_array(); 

        if(count($temp_all_state_cash_flows_fixed) == 0) // Pre-defined value for "Cash and equivalent at beginning of the year"
        {
            // retrieve data for "Cash and cash equivalents"
            $parent_fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, array('A205'));
            $account_list  = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $parent_fca_id); 

            if(count($account_list) > 0)
            {
                $temp_arr['cash_equivalent_begin']['fs_note_details_id'] = '';
                $temp_arr['cash_equivalent_begin']['group_ye']           = 0;
                $temp_arr['cash_equivalent_begin']['group_lye_end']      = 0;
                $temp_arr['cash_equivalent_begin']['company_ye']         = $account_list[0]['parent_array'][0]['total_c_lye'];
                $temp_arr['cash_equivalent_begin']['company_lye_end']    = 0;
                $temp_arr['cash_equivalent_begin']['note_display_num']   = '';
            }
        }
        else
        {
            foreach ($temp_all_state_cash_flows_fixed as $key => $each) 
            {
                $temp_arr[$each['fixed_tag']]['fs_note_details_id'] = $each['fs_note_details_id'];
                $temp_arr[$each['fixed_tag']]['group_ye']           = $each['value_group_ye'];
                $temp_arr[$each['fixed_tag']]['group_lye_end']      = $each['value_group_lye_end'];
                $temp_arr[$each['fixed_tag']]['company_ye']         = $each['value_company_ye'];
                $temp_arr[$each['fixed_tag']]['company_lye_end']    = $each['value_company_lye_end'];
                
                if($each['fs_note_details_id'] != null)
                {
                    $temp_arr[$each['fixed_tag']]['note_display_num'] = $this->fs_notes_model->get_input_note_num($fs_company_info_id, $each['fs_note_details_id']);
                }
            }
        }

        // get fs_state_cash_flows data
        $fs_state_cash_flows = $this->fs_statements_model->get_fs_state_cash_flows($fs_company_info_id);

        if(count($fs_state_cash_flows) == 0)
        {
            $this->fs_statements_model->create_state_cash_flows_w_ly_val($fs_company_info_id);
            $fs_state_cash_flows = $this->fs_statements_model->get_fs_state_cash_flows($fs_company_info_id);
        }

        $this->data['fs_state_cash_flows'] = $fs_state_cash_flows;
        $this->data['fs_state_cash_flows_fixed'] = $temp_arr;
        $this->data['check_operating_act'] = $this->fs_statements_model->get_fs_state_cash_flows_section($fs_company_info_id, 1);
        $this->data['check_investing_act'] = $this->fs_statements_model->get_fs_state_cash_flows_section($fs_company_info_id, 2);
        $this->data['check_financing_act'] = $this->fs_statements_model->get_fs_state_cash_flows_section($fs_company_info_id, 3); 

        if($fs_company_info[0]["group_type"] == '1')
        {
            $this->data['is_group'] = false;
        }
        else
        {
            $this->data['is_group'] = true;
        }

        $current_last_year_end = $this->fs_model->calculate_difference_dates($fs_company_info_id, "General");

        $this->data["last_fye_end"]     = $current_last_year_end['last_fye_end'];
        $this->data["current_fye_end"]  = $current_last_year_end['current_fye_end'];

        // IF REPORT IS FIRST SET
        // if($fs_company_info[0]['first_set'])
        // {
        //     $this->data["current_fye_end"]  = date('Y', strtotime($fs_company_info[0]['current_fye_end']));    // this year end eg. 31/12/2019
        //     $this->data["last_fye_end"]     = '';       // end of previous year end
        // }

        $this->data['show_data_content'] = $this->fs_statements_model->is_saved_fs_categorized_account_round_off($fs_company_info_id);
        $this->data['pl_be4_tax_values_from_sci'] = $pl_be4_tax_values_from_sci;

        $interface = $this->load->view('/views/financial_statement/template/fs_statements/partial_state_cash_flows.php', $this->data);

        // echo $interface;
    }

    public function partial_financial_position()
    {
        $form_data             = $this->input->post();

        $fs_company_info_id    = $form_data['fs_company_info_id'];
        $group_company         = $form_data['group_company'];

        $fs_company_info       = $this->fs_model->get_fs_company_info($fs_company_info_id);
        $current_last_year_end = $this->fs_model->calculate_difference_dates($fs_company_info_id, "FP");

        $fs_ntfs_list = $this->fs_notes_model->get_fs_ntfs_json();

        $this->data['show_third_col']   = false;
        $this->data["current_fye_end"]  = $current_last_year_end['current_fye_end'];
        $this->data["last_fye_end"]     = $current_last_year_end['last_fye_end'];
        $this->data['is_group']         = false;
        
        if($group_company == 'group')
        {
            $this->data['is_group'] = true;
        }

        /* Setting for column width */
        $col_width = array(
                            'account_desc' => 75,
                            'note'         => 5,
                            'value'        => 20
                        );

        if(!$fs_company_info[0]['first_set'])
        {
            $col_width = array(
                            'account_desc' => 65,
                            'note'         => 5,
                            'value'        => 15
                        );

            if($fs_company_info[0]['is_prior_year_amount_restated'])
            {
                if($fs_company_info[0]['effect_of_restatement_since'] == $fs_company_info[0]['last_fye_begin'])
                {
                    $col_width = array(
                            'account_desc' => 50,
                            'note'         => 5,
                            'value'        => 15
                        );
                }
            }
        }
        /* END OF Setting for column width */

        if($group_company == 'company')
        {
            $this->data['is_group'] = false;
        }
        else
        {
            $this->data['is_group'] = true;
        }

        if($fs_company_info[0]['is_prior_year_amount_restated'])
        {
            if($fs_company_info[0]['effect_of_restatement_since'] == $fs_company_info[0]['last_fye_begin'])
            {
                $this->data['show_third_col']   = true;

                $this->data["current_fye_end"]  = date('d.m.Y', strtotime($fs_company_info[0]['current_fye_end']));
                $this->data["last_fye_end"]     = date('d.m.Y', strtotime($fs_company_info[0]['last_fye_end']));
                $this->data['last_fye_beg']     = date('d.m.Y', strtotime($fs_company_info[0]['last_fye_begin']));
            }
        }

        $this->data['width'] = array(
                                    'account_desc' => $col_width['account_desc'],
                                    'note'         => $col_width['note'],
                                    'value'        => $col_width['value']
                                );
        $this->data['group_company'] = ucfirst(strtolower($group_company));

        /* get account list for statement of financial position */
        $this->data['fs_notes_details'] = $this->fs_notes_model->get_fs_note_details($fs_company_info_id, 2); 

        // get reference id for statement of financial position (Assets, Liabilities, Equity)
        $nd_key = array_search("Statement of financial position", array_column($fs_ntfs_list['statements'], 'document_name'));
        $nd_ref_id = '';
        $description_reference_id_list = [];
        $nd_account_code = [];

        if($nd_key || (string)$nd_key == '0')
        {
            $nd_account_code = $fs_ntfs_list['statements'][$nd_key]['reference_id']; // get account code
            $description_reference_id_list = $fs_ntfs_list['statements'][$nd_key]['description_reference_id'];
        }

        // get data for statement of financial position (Assets, Liabilities, Equity)
        $fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, $nd_account_code);
        $data = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $fca_id);

        /* ------------------ Sum up value to get "revenue reserve (Q103)" ------------------ */
        $fs_statement_list = $this->fs_statements_model->get_fs_statement();
        $fca_ids_for_revenue_reserve = $this->fs_notes_model->get_fca_id($fs_company_info_id, $fs_statement_list->statement_financial_position[0]->for_revenue_reserve);
        $data_for_revenue_reserve = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $fca_ids_for_revenue_reserve);

        $total_for_revenue_reserve = array(
                                        'total_c'     => 0.00,
                                        'total_c_lye' => 0.00,
                                        'total_g'     => 0.00,
                                        'total_g_lye' => 0.00
                                    );

        foreach ($data_for_revenue_reserve as $rr_key => $rr_value) 
        {
            $total_for_revenue_reserve['total_c']       += $rr_value['parent_array'][0]['total_c'];
            $total_for_revenue_reserve['total_c_lye']   += $rr_value['parent_array'][0]['total_c_lye'];
            // $total_for_revenue_reserve['total_g']       += $rr_value['parent_array'][0]['total_g'];
            // $total_for_revenue_reserve['total_g_lye']   += $rr_value['parent_array'][0]['total_g_lye'];
        }

        $data = $this->fs_account_category_model->operate_account_value($data, array('Q103'), array(array('operator' => '+', 'insert_values_arr' => $total_for_revenue_reserve)));
        /* ------------------ END OF Sum up value to get "revenue reserve (Q103)" ------------------ */

        /* ------------------------ Set "Equity and liabilities" title ------------------------ */
        $e_l_title      = '';
        $eq_liabi_title_list = [];

        foreach ($data as $key => $value) 
        {
            $a_description = '';
            $a_key = array_search($value['parent_array'][0]['account_code'], array_column($description_reference_id_list, "account_code"));

            if($a_key || (string)$a_key == '0')
            {
                $a_description = $description_reference_id_list[$a_key]['description'];
            }

            if($a_description != "Assets")
            {
                $hide_title = true;

                $data[$key] = $this->fs_account_category_model->change_sign_in_account(array($value))[0];   // Change sign (+/-)

                foreach($value['child_array'] as $child_key => $child_value)
                {
                    if(!empty($child_value['parent_array']))
                    {
                        $hide_title = false;
                    }
                }

                if(!empty($value['child_array']) && !$hide_title) 
                {
                    if(empty($e_l_title))
                    {
                        $e_l_title = $value['parent_array'][0]['description'];
                    }
                    else
                    {
                        $e_l_title .= ' and ' . $value['parent_array'][0]['description'];
                    }

                    array_push($eq_liabi_title_list, $a_description);    // for checking later
                }
            }
        }

        if(count($eq_liabi_title_list) == 1)  
        {
            if($eq_liabi_title_list[0] == "Equity")
            {
                $e_l_title = '';    // remove title if got equity only
            }
        }

        // print_r($data);

        $this->data['data']      = $data;
        $this->data['e_l_title'] = $e_l_title;

        // print_r($data);
        /* ------------------------ END OF Set "Equity and liabilities" title ------------------------ */

        // $this->data['data'] = $this->fs_account_category_model->get_account_with_sub_round_off($fs_company_info_id, array('M1001', 'M1002'));

        // get auto rearrange notes from fs_settings
        $auto_rearrange_data = $this->fs_model->get_fs_settings($fs_company_info_id);
        $on_rearrange_notes = 1;

        if(count($auto_rearrange_data) > 0)
        {
            $on_rearrange_notes = $auto_rearrange_data[0]['auto_rearrange_notes_no'];
        }

        $this->data['fs_notes_details_state_2']     = $this->fs_notes_model->get_fs_note_details_for_state_comp_income($fs_company_info_id, 1);
        $this->data['eq_liabi_title_list']          = $eq_liabi_title_list;
        $this->data['fs_ntfs_list']                 = $fs_ntfs_list['statements'][$nd_key]['description_reference_id'];
        $this->data['auto_rearrange_value']         = $on_rearrange_notes;
        $this->data["fs_ntfs_layout_template_list"] = $this->fs_notes_model->get_ntfs_layout_template_parents($fs_company_info_id);
        $this->data['show_data_content']            = $this->fs_statements_model->is_saved_fs_categorized_account_round_off($fs_company_info_id);

        $interface = $this->load->view('/views/financial_statement/template/fs_statements/partial_financial_position.php', $this->data);
    }

    /* ----------- Setup Statement documents ----------- */
    public function setup_cfs($fs_company_info_id, $client_id)
    {   
        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $client_info  = $this->db->query("SELECT * FROM client WHERE id = " . $client_id);
        $client_info  = $client_info->result_array();
        $client_info = $this->fs_model->decrypt_client_info($client_info, '');  // keyword become '' to get all data list only

        /* for breadcrumb */
        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Report', base_url('financial_statement'));
        $this->mybreadcrumb->add('Edit FS - ' . $client_info[0]['company_name'], base_url('financial_statement/partial_fs_report_list/' . $client_id));
        $this->mybreadcrumb->add($fs_company_info[0]["current_fye_end"], base_url('financial_statement/create/'.$client_id.'/'. $fs_company_info_id));

        $this->mybreadcrumb->add('Setup Statement of Cash Flows', base_url());
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        /* END OF for breadcrumb */

        // setup Statement of Cash Flows data (retrieve data)
        $cfs_header = $this->fs_statements_model->get_fs_setup_state_cash_flows_header($fs_company_info_id); // get header data
        $cfs_body_data   = $this->fs_statements_model->get_fs_setup_state_cash_flows($fs_company_info_id); // get body data

        // // for profit before tax
        // $pl_be4_tax_values_from_sci = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_company_info_id=" . $fs_company_info_id . " AND fs_list_state_comp_income_section_id=3");
        // $pl_be4_tax_values_from_sci = $pl_be4_tax_values_from_sci->result_array(); 

        // setup variable to pass to layout
        $this->data['fs_company_info']  = $fs_company_info;
        $this->data['cfs_header']       = $cfs_header;
        $this->data['cfs_body_data']    = $cfs_body_data;
        // $this->data['pl_b4_tax']        = $pl_be4_tax_values_from_sci;

        $this->data['check_operating_act'] = $this->fs_statements_model->get_fs_state_cash_flows_section($fs_company_info_id, 1);
        $this->data['check_investing_act'] = $this->fs_statements_model->get_fs_state_cash_flows_section($fs_company_info_id, 2);
        $this->data['check_financing_act'] = $this->fs_statements_model->get_fs_state_cash_flows_section($fs_company_info_id, 3);

        $this->page_construct('/financial_statement/template/fs_statements_setup/partial_setup_state_cash_flows.php', $meta, $this->data);
    }
    /* ----------- END OF Setup Statement documents ----------- */

    public function save_category_value()
    {
        // INITIALISE 
        $company_end_prev_ye_value = [];
        $company_beg_prev_ye_value = [];
        $group_end_this_ye_value   = [];
        $group_end_prev_ye_value   = [];
        $group_beg_prev_ye_value   = [];

        $form_data = $this->input->post();

        $fs_company_info_id       = $form_data['fs_company_info_id'];
        $fs_statement_doc_type_id = $form_data['statement_doc_type'];
        $client_id                = $form_data['client_id'];
        $is_group                 = $form_data['is_group'];

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        if ($fs_statement_doc_type_id == 1 || $fs_statement_doc_type_id == 2) 
        {
            /* ---- DO NOT DELETE THIS ---- */
            // $auto_rearrange_value     = $form_data['auto_rearrange_value'];

            // $fs_settings_data = array(
            //                         'fs_company_info_id' => $fs_company_info_id,
            //                         'auto_rearrange_notes_no' => $auto_rearrange_value
            //                     );
            // $result = $this->fs_model->save_fs_settings($fs_settings_data); // save fs_settings
            /* ---- END OF DO NOT DELETE THIS ---- */

            if($fs_statement_doc_type_id == 1)  // statement of comprehensive income
            {
                // for fs_categorized_account_round_off
                $SCI_C_sub_fs_categorized_account_id = $form_data['SCI_C_sub_fs_categorized_account_id'];
                $SCI_C_value_group_ye                = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['SCI_C_value_group_ye']);   // use array_map to loop array and change 0 to ''
                $SCI_C_value_group_lye_end           = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['SCI_C_value_group_lye_end']); 

                $SCI_C_fs_note_details_id            = $form_data['SCI_C_fs_note_details_id'];
                $SCI_C_fs_note_templates_master_id   = $form_data['SCI_C_fs_note_templates_master_id'];
                $SCI_C_fs_note_num_displayed         = $form_data['SCI_C_fs_note_num_displayed'];

                // for fs_state_comp_income
                $fs_state_comp_id                     = $form_data['fs_state_comp_id'];
                $fs_list_state_comp_income_section_id = $form_data['fs_list_state_comp_income_section_id'];
                $SCI_description                      = $form_data['SCI_description'];
                $SCI_value_group_ye                   = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['SCI_value_group_ye']);
                $SCI_value_group_lye_end              = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['SCI_value_group_lye_end']);

                // for note no
                $SCI_fs_note_details_id               = $form_data['SCI_fs_note_details_id'];
                $SCI_fs_note_templates_master_id      = $form_data['SCI_fs_note_templates_master_id'];
                $SCI_fs_note_num_displayed            = $form_data['SCI_fs_note_num_displayed'];

                // for dynamic add row eg. "Other comprehensive income; net of tax"
                $sci_other_id          = $form_data['sci_other_id'];
                $sci_other_description = $form_data['sci_other_description'];

                if($is_group)
                {
                    $sci_other_g_ye  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['sci_other_g_ye']);
                    $sci_other_g_lye = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['sci_other_g_lye']);
                }
                
                $sci_other_c_ye  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['sci_other_c_ye']);
                $sci_other_c_lye = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['sci_other_c_lye']);

                $deleted_dynamic_ids = $form_data['deleted_dynamic_ids'];

                // update for fs_categorized_account_round_off
                $fs_categorized_account_round_off_data = [];

                foreach ($SCI_C_sub_fs_categorized_account_id as $key => $value) 
                {
                    array_push($fs_categorized_account_round_off_data, 
                                array(
                                    'id' => $SCI_C_sub_fs_categorized_account_id[$key],
                                    'group_end_this_ye_value' => $SCI_C_value_group_ye[$key],
                                    'group_end_prev_ye_value' => $SCI_C_value_group_lye_end[$key]
                                ));
                }

                // print_r($fs_categorized_account_round_off_data);

                $result = $this->fs_account_category_model->update_categorized_account_round_off_batch(array($fs_categorized_account_round_off_data));

                // update for fs_categorized_account_round_off
                $fs_state_comp_income_data = [];

                foreach ($fs_state_comp_id as $sci_key => $sci_value) 
                {
                    array_push($fs_state_comp_income_data, 
                                array(
                                    'id'    => $fs_state_comp_id[$sci_key],
                                    'info'  => array(
                                                'description'         => $SCI_description[$sci_key],
                                                'value_group_ye'      => $SCI_value_group_ye[$sci_key],
                                                'value_group_lye_end' => $SCI_value_group_lye_end[$sci_key]
                                            )
                                ));
                }

                $sci_result = $this->fs_statements_model->insert_fs_state_comp_income($fs_state_comp_income_data);

                // delete & update/insert dynamic add row (other) 
                $deleted_dynamic_sci_result = $this->fs_statements_model->delete_row_from_table('fs_state_comp_income', $deleted_dynamic_ids);  // delete dynamic row (other)

                $fs_sci_data = [];

                foreach ($sci_other_id as $key => $value) 
                {
                    if($fs_company_info[0]['first_set'] == 0)
                    {
                        if($is_group && !empty($sci_other_description[$key]) && !empty($sci_other_c_ye[$key]) && !empty($sci_other_c_lye[$key]) && !empty($sci_other_g_ye[$key]) && !empty($sci_other_g_lye[$key]))
                        {   
                            array_push($fs_sci_data, 
                                array(
                                    'id'   => $sci_other_id[$key],
                                    'info' => array(
                                                'fs_company_info_id'    => $fs_company_info_id,
                                                'fs_list_state_comp_income_section_id' => 5,
                                                'description'           => $sci_other_description[$key],
                                                'value_company_ye'      => $sci_other_c_ye[$key],
                                                'value_company_lye_end' => $sci_other_c_lye[$key],
                                                'value_group_ye'        => $sci_other_g_ye[$key],
                                                'value_group_lye_end'   => $sci_other_g_lye[$key],
                                                'order_by' => $key + 1,
                                                'in_use'   => 1
                                            )
                                )
                            );
                        }
                        elseif(!empty($sci_other_description[$key]) && !empty($sci_other_c_ye[$key]) && !empty($sci_other_c_lye[$key]))
                        {
                            array_push($fs_sci_data, 
                                array(
                                    'id'   => $sci_other_id[$key],
                                    'info' => array(
                                                'fs_company_info_id'    => $fs_company_info_id,
                                                'fs_list_state_comp_income_section_id' => 5,
                                                'description'           => $sci_other_description[$key],
                                                'value_company_ye'      => $sci_other_c_ye[$key],
                                                'value_company_lye_end' => $sci_other_c_lye[$key],
                                                'order_by' => $key + 1,
                                                'in_use'   => 1
                                            )
                                )
                            );
                        }
                    }
                    else
                    {
                        if($is_group && !empty($sci_other_description[$key]) && !empty($sci_other_c_ye[$key]) && !empty($sci_other_g_ye[$key]))
                        {   
                            array_push($fs_sci_data, 
                                array(
                                    'id'   => $sci_other_id[$key],
                                    'info' => array(
                                                'fs_company_info_id'    => $fs_company_info_id,
                                                'fs_list_state_comp_income_section_id' => 5,
                                                'description'           => $sci_other_description[$key],
                                                'value_company_ye'      => $sci_other_c_ye[$key],
                                                'value_company_lye_end' => '',
                                                'value_group_ye'        => $sci_other_g_ye[$key],
                                                'value_group_lye_end'   => '',
                                                'order_by' => $key + 1,
                                                'in_use'   => 1
                                            )
                                )
                            );
                        }
                        elseif(!empty($sci_other_description[$key]) && !empty($sci_other_c_ye[$key]))
                        {
                            array_push($fs_sci_data, 
                                array(
                                    'id'   => $sci_other_id[$key],
                                    'info' => array(
                                                'fs_company_info_id'    => $fs_company_info_id,
                                                'fs_list_state_comp_income_section_id' => 5,
                                                'description'           => $sci_other_description[$key],
                                                'value_company_ye'      => $sci_other_c_ye[$key],
                                                'value_company_lye_end' => '',
                                                'order_by' => $key + 1,
                                                'in_use'   => 1
                                            )
                                )
                            );
                        }
                    }
                }

                // print_r($fs_sci_data);

                $dynamic_sci_result = $this->fs_statements_model->insert_fs_state_comp_income($fs_sci_data);

                // insert / update / delete fs_note_details
                $fs_note_details = [];

                // for tree first
                foreach ($SCI_C_fs_note_details_id as $sci_note_key => $sci_note_value) 
                {
                    array_push($fs_note_details, 
                        array(
                            'id'   => $SCI_C_fs_note_details_id[$sci_note_key],
                            'info' => array(
                                        'fs_categorized_account_round_off_id' => $SCI_C_sub_fs_categorized_account_id[$sci_note_key],
                                        'fs_company_info_id'                  => $fs_company_info_id,
                                        'fs_note_templates_master_id'         => $SCI_C_fs_note_templates_master_id[$sci_note_key],
                                        'fs_list_statement_doc_type_id'       => $fs_statement_doc_type_id,
                                        'note_num_displayed'                  => $SCI_C_fs_note_num_displayed[$sci_note_key],
                                        'in_use'                              => 1
                                    )
                        )
                    );
                }

                // for fs_state_comp_income
                foreach ($SCI_fs_note_details_id as $sci_c_note_key => $sci_c_note_value) 
                {
                    array_push($fs_note_details, 
                        array(
                            'id'   => $SCI_fs_note_details_id[$sci_c_note_key],
                            'info' => array(
                                        'fs_categorized_account_round_off_id' => 0,
                                        'fs_company_info_id'                  => $fs_company_info_id,
                                        'fs_note_templates_master_id'         => $SCI_fs_note_templates_master_id[$sci_c_note_key],
                                        'fs_list_statement_doc_type_id'       => $fs_statement_doc_type_id,
                                        'note_num_displayed'                  => $SCI_fs_note_num_displayed[$sci_c_note_key],
                                        'in_use'                              => 1
                                    ),
                            'fs_state_comp_income_id' => $fs_state_comp_id[$sci_c_note_key]
                        )
                    );
                }

                // print_r($fs_note_details);
                $result_note_details = $this->fs_notes_model->insert_note_details($fs_note_details, 1);
                $result_update_fnltd = $this->fs_notes_model->update_checked_fs_ntfs_layout_template($fs_company_info_id);

                // /* ----------------- rearrange note no in statement of financial position ----------------- */
                // $fnd_data_doc1 = $this->fs_notes_model->get_fs_note_details($fs_company_info_id, 1); // get all note no from statement doc 1 (statement of comprehensive income)
                // $fnd_data_doc2 = $this->fs_notes_model->get_fs_note_details($fs_company_info_id, 2);

                // // print_r(max(array_column($fnd_data_doc1, 'note_num_displayed')));

                // $fnd_data_doc1_max_num = max(array_column($fnd_data_doc1, 'note_num_displayed')); // get biggest note no from "Statement of comprehensive income"
                // $fnd_data_doc2_ids = []; 
                // $temp_info = $fnd_data_doc2;

                // foreach ($fnd_data_doc2 as $doc2_key => $doc2_value) 
                // {
                //     $fnd_data_doc1_max_num++;

                //     $fnd_data_doc2[$doc2_key] = $fnd_data_doc1_max_num;

                //     // array_push($temp_info, $fnd_data_doc2[$doc2_key]);
                //     unset($temp_info[$doc2_key]['id']);
                //     unset($temp_info[$doc2_key]['fs_ntfs_layout_template_default_id']);

                //     array_push($fnd_data_doc2_ids,
                //         array(
                //             'id' => $doc2_value['id']
                //         )
                //     );
                // }

                // foreach ($temp_info as $temp_doc2_key => $temp_doc2_value) 
                // {
                //     $fnd_data_doc1_max_num++;

                //     $temp_info[$temp_doc2_key]['note_num_displayed'] = $fnd_data_doc1_max_num;
                // }

                // print_r($temp_info);
                // /* ----------------- END OF rearrange note no in statement of financial position ----------------- */

                // $result_note_details_doc2 = $this->fs_notes_model->insert_note_details($fs_note_details, 1);

                if($result && $sci_result && $result_note_details && $deleted_dynamic_sci_result && $dynamic_sci_result)
                {
                    echo json_encode(array('result' => 1, 'client_id' => $client_id, 'fs_company_info_id' => $fs_company_info_id));
                }
                else
                {
                    echo json_encode(array('result' => 0));
                }
            }
            elseif($fs_statement_doc_type_id == 2)  // statement of financial position
            {
                // print_r($form_data);

                $data = [];

                $group_company_type = $form_data['group_company'];

                if($group_company_type == 'company')
                {
                    $sub_fs_categorized_account_id  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['FP_sub_fs_categorized_account_id']);
                    $value_company_lye_beg          = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['FP_company_lye_beg_value']);

                    foreach ($value_company_lye_beg as $lye_beg_key => $lye_beg_value) 
                    {
                        array_push($data,
                            array(
                                'id'    => $sub_fs_categorized_account_id[$lye_beg_key],
                                'company_beg_prev_ye_value' => $lye_beg_value
                            )
                        );
                    }

                    if(count($value_company_lye_beg) > 0)
                    {
                        $this->fs_account_category_model->update_categorized_account_round_off_batch(array($data));
                    }
                }
                elseif($group_company_type == 'group')
                {
                    $sub_fs_categorized_account_id  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['FP_sub_fs_categorized_account_id']);
                    $group_end_this_ye_value        = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['FP_group_ye_end_value']);
                    $group_end_prev_ye_value        = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['FP_group_lye_end_value']);
                    $group_beg_prev_ye_value        = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['FP_group_lye_beg_value']);

                    foreach ($group_end_this_ye_value as $key => $value) 
                    {
                        array_push($data,
                            array(
                                'id'    => $sub_fs_categorized_account_id[$key],
                                'group_end_this_ye_value' => $group_end_this_ye_value[$key],
                                'group_end_prev_ye_value' => $group_end_prev_ye_value[$key],
                                'group_beg_prev_ye_value' => $group_beg_prev_ye_value[$key]
                            )
                        );
                    }

                    if(count($group_end_this_ye_value) > 0)
                    {
                        $this->fs_account_category_model->update_categorized_account_round_off_batch(array($data));
                    }
                }

                // save notes insert
                $fs_note_details = [];

                $fs_note_details_id = $form_data['fs_note_details_id']; 

                $fs_note_templates_master_id = $form_data['FP_fs_note_templates_master_id'];
                $note_num_displayed          = $form_data['FP_fs_note_num_displayed'];

                foreach ($fs_note_templates_master_id as $key => $value) 
                {
                    array_push($fs_note_details,  
                        array(
                            'id'   => $fs_note_details_id[$key],
                            'info' => array(
                                        'fs_categorized_account_round_off_id'     => $sub_fs_categorized_account_id[$key],
                                        'fs_company_info_id'            => $fs_company_info_id,
                                        'fs_note_templates_master_id'   => $fs_note_templates_master_id[$key],
                                        'fs_list_statement_doc_type_id' => $fs_statement_doc_type_id,
                                        'note_num_displayed'            => $note_num_displayed[$key],
                                        'in_use'                        => 1
                                    )
                        )
                    );
                }

                $result_note_details = $this->fs_notes_model->insert_note_details($fs_note_details, 2); 
                $result_update_fnltd = $this->fs_notes_model->update_checked_fs_ntfs_layout_template($fs_company_info_id);

                // echo json_encode(array('result' => $result_note_details));
                echo json_encode(array('result' => true));
            }
        }
        elseif($fs_statement_doc_type_id == 3)  // statement of cash flows
        {
            $arr_deleted_row  = $form_data['arr_deleted_row'];
            $section_flag = json_decode($form_data['section_flag']);

            $this->fs_statements_model->delete_state_cash_flows($arr_deleted_row);

            $id = $form_data['input_id'];
            $desc = $form_data['input_desc'];
            $note_id = $form_data['input_note_id'];
            // $value_company_ye  = $form_data['input_value_company_ye'];
            $value_company_lye_end = $form_data['input_value_company_lye_end'];
            $value_group_ye  = $form_data['input_value_group_ye'];
            $value_group_lye_end = $form_data['input_value_group_lye_end'];
            $parent = $form_data['input_parent'];
            $category = $form_data['input_category'];

            //fixed field
            $cash_equivalent_end_note_id = $form_data['input_note_0'];

            $fixed_category_id = $form_data['fixed_category_id'];
            $group_ye = $form_data['group_ye'];
            $group_lye_end = $form_data['group_lye_end'];
            $company_ye = $form_data['company_ye'];
            $company_lye_end = $form_data['company_lye_end'];

            // print_r($cash_equivalent_end_note_id);
            // print_r($company_ye);
            // print_r($company_lye_end);
            $all_state_cash_flows_fixed = $this->build_state_cash_flows_fixed_arr($cash_equivalent_end_note_id, $fixed_category_id, $group_ye, $group_lye_end,
                                                                                  $company_ye, $company_lye_end, $fs_company_info_id); 

            // print_r($all_state_cash_flows_fixed);

            // print_r($form_data['input_id']);
            $all_state_cash_flows = $this->build_state_cash_flows_arr($id, $desc, $note_id, $value_company_lye_end, 
                                                                      $value_group_ye, $value_group_lye_end, 
                                                                      $parent, $category, $fs_company_info_id);

            $all_state_cash_flows_hide_section = $this->build_state_cash_flows_section_arr($section_flag, $fs_company_info_id);

            // print_r($all_state_cash_flows_hide_section);

            foreach ($all_state_cash_flows_fixed as $key => $state_cash_flows_fixed) 
            {
                $result = $this->fs_statements_model->save_fs_state_cash_flows_fixed($state_cash_flows_fixed);
            }

            foreach ($all_state_cash_flows as $key => $state_cash_flows) 
            {
                $result = $this->fs_statements_model->insert_fs_state_cash_flows($state_cash_flows);
            }

            foreach ($all_state_cash_flows_hide_section as $key => $state_cash_flows_hide_section) 
            {
                $result = $this->fs_statements_model->save_fs_state_cash_flows_hide_section($state_cash_flows_hide_section);
                
                if($state_cash_flows_hide_section['status'] == 0)
                {
                    $this->fs_statements_model->delete_state_cash_flows_category($state_cash_flows_hide_section['section_id'], $state_cash_flows_hide_section['fs_company_info_id']);
                    $this->fs_statements_model->delete_state_cash_flows_fixed_category($state_cash_flows_hide_section['section_id'], $state_cash_flows_hide_section['fs_company_info_id']);
                }
            }

            // return result message (not so completed)
            if($result)
            {
                echo json_encode(array('result' => 1));
            }
            else
            {
                echo json_encode(array('result' => 0));
            }
        }
        else if($fs_statement_doc_type_id == 4) // statement of changes in equity
        {
            $header_titles = $form_data['header'];
            $all_footer = $form_data['footer'];
            $curr_yr_rows = $form_data['curr_yr_row'];
            $prior_yr_rows = $form_data['prior_yr_row'];
            $group_company_type = $form_data['group_company'];
            $arr_deleted_row  = $form_data['arr_deleted_row'];

            // print_r($arr_deleted_row);
            $this->fs_statements_model->delete_state_changes_in_equity($arr_deleted_row);

            $header_titles_str = implode(',', $header_titles);
            $header_rows = array("fs_company_info_id"    => $fs_company_info_id,
                                 "group_company"         => $group_company_type,
                                 "header_titles"         => $header_titles_str);
            $this->fs_statements_model->insert_changes_in_equity_header_titles($header_rows);

            $all_footer_arr      = $this->build_state_changes_in_equity_footer_arr($all_footer, $group_company_type, $fs_company_info_id);
            $all_current_yr_rows = $this->build_state_changes_in_equity_arr($curr_yr_rows, $group_company_type, "current", $fs_company_info_id);
            $all_prior_yr_rows   = $this->build_state_changes_in_equity_arr($prior_yr_rows, $group_company_type, "prior", $fs_company_info_id);

            foreach ($all_current_yr_rows as $key => $current_yr_row) 
            {
                // print_r($state_cash_flows);
                $result = $this->fs_statements_model->insert_fs_state_changes_in_equity($current_yr_row);
            }

            foreach ($all_prior_yr_rows as $key => $prior_yr_row) 
            {
                // print_r($state_cash_flows);
                $result = $this->fs_statements_model->insert_fs_state_changes_in_equity($prior_yr_row);
            }

            foreach($all_footer_arr as $key => $footer_row)
            {
                $result = $this->fs_statements_model->insert_changes_in_equity_footer($footer_row);
            }

            // return result message (not so completed)
            if($result)
            {
                echo json_encode(array('result' => 1));
            }
            else
            {
                echo json_encode(array('result' => 0));
            }

            // print_r($all_current_yr_rows);

            // print_r($curr_yr_rows);

            // echo "-------------------------Prior-------------------------";

            // print_r($prior_yr_rows);

            // print_r($all_prior_yr_rows);
        }

        // // update fs_ntfs_layout_template
        // if($fs_statement_doc_type_id == 1 || $fs_statement_doc_type_id == 2)
        // {
        //     $fs_ntfs_layout_template_list = json_decode($form_data['fs_ntfs_layout_template_list']);

        //     foreach ($fs_ntfs_layout_template_list as $fnlt_key => $fnlt_value) 
        //     {
        //         $this->fs_notes_model->update_fs_ntfs_layout_template_content($fnlt_value->id, array('is_checked' => $fnlt_value->is_checked));
        //     }

        //     $this->fs_notes_model->update_fs_note_details_note_num_displayed($fs_company_info_id, $fs_ntfs_layout_template_list);
        // }
    }

    public function build_state_changes_in_equity_footer_arr($rows_arr, $group_company_type, $fs_company_info_id)
    {
        $temp_array = array();

        foreach ($rows_arr as $key => $value) {
            array_push(
                    $temp_array, array(
                        'fs_company_info_id'        => $fs_company_info_id,
                        'group_company'             => $group_company_type,
                        'current_prior'             => $key,
                        'description'               => $value[0],
                        'footer_item'               => implode(",",array_slice($value,1))
                    )
                );
        }

        return $temp_array;
    }

    public function build_state_changes_in_equity_arr($rows_arr, $group_company_type, $prior_current, $fs_company_info_id)
    {
        $arranged_array     = array();
        $temp_array         = array();
        $temp_inner_array   = array();

        foreach ($rows_arr as $key => $row) 
        {
            //rearrange column to uniform column key
            if(!array_key_exists(1,$row))
            {
                array_splice($row, 1, 0, array('1' => 0));
            }

            foreach ($row as $inner_key => $row_details) {
               array_push($temp_inner_array, $row_details);
            }
            array_push($arranged_array, $temp_inner_array);
            $temp_inner_array = array();
        }

        foreach ($arranged_array as $key => $value) {
            $row_item = array_slice($value, 3); 
            array_push(
                    $temp_array, array(
                        'id'                        => $value[0],
                        'fs_company_info_id'        => $fs_company_info_id,
                        'group_company'             => $group_company_type,
                        'current_prior'             => $prior_current,
                        'is_subtotal'               => $value[1],
                        'description'               => $value[2],
                        'row_item'                  => implode(",",$row_item),
                        'row_order'                 => $key
                    )
                );
        }
        return $temp_array;
    }

    public function save_setup_state_cash_flows() // due to fixed column bootstrap table, there will be duplicate array appear. 
    {
        $form_data = $this->input->post();

        $fs_company_info_id = $form_data['fs_company_info_id'];

        // cfs header data
        $header_id              = $form_data['header_id'];
        $header_items_fcaro_id  = array_slice($form_data['header_items_fcaro_id'], 0, (count($form_data['header_items_fcaro_id'])/2));

        // checkbox data
        $hide_show_id_opt = $form_data['hide_show_id_opt'];
        $hide_show_id_inv = $form_data['hide_show_id_inv'];
        $hide_show_id_fin = $form_data['hide_show_id_fin'];

        $check_operating_act     = $form_data['check_operating_act'];
        $check_investing_act     = $form_data['check_investing_act'];
        $check_financing_act     = $form_data['check_financing_act'];

        $check_operating_section_id = $form_data['check_operating_section_id'];
        $check_investing_section_id = $form_data['check_investing_section_id'];
        $check_financing_section_id = $form_data['check_financing_section_id'];

        // // net cash parts
        // $net_cash = array(
        //                 'net_cash_frm_opt' => $form_data['net_cash_frm_opt'],
        //                 'net_cash_opt' => $form_data['net_cash_opt'],
        //                 'net_cash_inv' => $form_data['net_cash_inv'],
        //                 'net_cash_fin' => $form_data['net_cash_fin']
        //             );

        // cfs body data
        $setup_cfs_id           = array_slice($form_data['setup_cfs_id'], 0, (count($form_data['setup_cfs_id'])/2));
        $setup_cfs_is_checked   = array_slice($form_data['setup_cfs_is_checked'], 0, (count($form_data['setup_cfs_is_checked'])/2));
        $setup_cfs_parent_id    = array_slice($form_data['setup_cfs_parent_id'], 0, (count($form_data['setup_cfs_parent_id'])/2));
        $setup_cfs_category_id  = array_slice($form_data['setup_cfs_category_id'], 0, (count($form_data['setup_cfs_category_id'])/2));
        $setup_cfs_description  = array_slice($form_data['setup_cfs_description'], 0, (count($form_data['setup_cfs_description'])/2));
        $setup_cfs_main_val     = array_slice($form_data['setup_cfs_main_val'], 0, (count($form_data['setup_cfs_main_val'])/2));
        // $setup_cfs_dyn_val      = array_slice($form_data['setup_cfs_dyn_val'], 0, (count($form_data['setup_cfs_dyn_val'])/2));
        $setup_cfs_is_adj_val   = $form_data['setup_cfs_is_adj_val'];
        $setup_cfs_dyn_val      = $form_data['setup_cfs_dyn_val'];

        $arranged_setup_cfs_is_adj_val = [];
        $arranged_setup_cfs_dyn_val = [];

        // rearrange setup_cfs_dyn_val
        for ($i=0; $i < count($setup_cfs_parent_id); $i++) 
        {
            if($i == 0)
            {
                $start_index = 0;
                $end_index   = count($header_items_fcaro_id);
            }
            else
            {
                $start_index = $end_index;
                $end_index   = $end_index + count($header_items_fcaro_id);
            }
            array_push($arranged_setup_cfs_is_adj_val, array_slice($setup_cfs_is_adj_val, $start_index, count($header_items_fcaro_id)));
            array_push($arranged_setup_cfs_dyn_val, array_slice($setup_cfs_dyn_val, $start_index, count($header_items_fcaro_id)));
        }

        // build header array
        $header_data = array(
                            'id'    => $header_id[0],
                            'info'  => array(
                                            'fs_company_info_id' => $fs_company_info_id,
                                            'header_titles' => implode(",",$header_items_fcaro_id)
                                        )
                        );
        $header_id = $this->fs_notes_model->insert_update_tbl_data_2('fs_setup_state_cash_flows_header', array($header_data));

        // build body array 
        $body_data = [];

        foreach ($setup_cfs_id as $key => $value) 
        {
            array_push($body_data,
                array(
                    'id'    => $value,
                    'info'  => array(
                                    'fs_company_info_id'   => $fs_company_info_id,
                                    'is_checked'           => $setup_cfs_is_checked[$key],
                                    'parent_id'            => $setup_cfs_parent_id[$key],
                                    'category_id'          => $setup_cfs_category_id[$key],
                                    'description'          => $setup_cfs_description[$key],
                                    'main_value'           => $setup_cfs_main_val[$key],
                                    'is_adjustment_values' => implode(",",$arranged_setup_cfs_is_adj_val[$key]),
                                    'row_item'             => implode(",",$arranged_setup_cfs_dyn_val[$key]),
                                    'order_by'             => $key + 1
                                )
                )
            );
        }
        $body_id = $this->fs_notes_model->insert_update_tbl_data_2('fs_setup_state_cash_flows', $body_data);

        // build checkbox array
        $checkbox_data = [];

        // operating activities
        array_push($checkbox_data, 
            array(
                'id' => $hide_show_id_opt,
                'info' => array(
                                'fs_company_info_id' => $fs_company_info_id,
                                'section_id'         => $check_operating_section_id,
                                'status'             => $check_operating_act
                            )
            )
        );

        // investing activities
        array_push($checkbox_data, 
            array(
                'id' => $hide_show_id_inv,
                'info' => array(
                                'fs_company_info_id' => $fs_company_info_id,
                                'section_id'         => $check_investing_section_id,
                                'status'             => $check_investing_act
                            )
            )
        );

        // financing activities
        array_push($checkbox_data, 
            array(
                'id' => $hide_show_id_fin,
                'info' => array(
                                'fs_company_info_id' => $fs_company_info_id,
                                'section_id'         => $check_financing_section_id,
                                'status'             => $check_financing_act
                            )
            )
        );

        $checkbox_id = $this->fs_notes_model->insert_update_tbl_data_2('fs_state_cash_flows_hide_section', $checkbox_data);

        // insert / update for 'fs_state_cash_flows'
        $fs_state_cash_flows = $this->fs_statements_model->get_fs_state_cash_flows($fs_company_info_id);

        $fs_cfs_data = [];
        $index = 1;

        foreach ($body_data as $key => $value) 
        {
            if($value['info']['parent_id'] != '#pl_be4_tax') // exclude profit before tax
            {
                $matched = false;

                foreach ($fs_state_cash_flows as $key_db => $value_db) // load cfs data and compare list
                {
                    if($value_db['fs_setup_state_cash_flows_id'] == $body_id[$key]) // if exist before
                    {
                        array_push($fs_cfs_data,
                            array(
                                'id'   => $value_db['id'],
                                'info' => array(
                                                'fs_company_info_id'            => $fs_company_info_id,
                                                'fs_setup_state_cash_flows_id'  => $body_id[$key],
                                                'order_by'                      => $index
                                            )
                            )
                        );
                        $matched = true;
                        break;
                    }
                }

                if(!$matched)
                {
                    array_push($fs_cfs_data,
                        array(
                            'id'   => '',
                            'info' => array(
                                            'fs_company_info_id'            => $fs_company_info_id,
                                            'fs_setup_state_cash_flows_id'  => $body_id[$key],
                                            'order_by'                      => $index
                                        )
                        )
                    );
                }
                $index++;
            }
        }

        $this->fs_notes_model->insert_update_tbl_data_2('fs_state_cash_flows', $fs_cfs_data);

        // // insert/update "fs_state_cash_flows_fixed" (Net cash values)
        // $cfs_fixed_data = [];

        // array_push($cfs_fixed_data, 
        //     array(
        //         'id'    => '',
        //         'info'  => array(
        //                         'fs_company_info_id' => $fs_company_info_id,
        //                         'category_id'        => 1,
        //                         'fixed_tag'          => 'net_cash_operation',
        //                         'value_company_ye'   => $net_cash['net_cash_frm_opt']
        //                     )
        //     )
        // );

        // array_push($cfs_fixed_data, 
        //     array(
        //         'id'    => '',
        //         'info'  => array(
        //                         'fs_company_info_id' => $fs_company_info_id,
        //                         'category_id'        => 1,
        //                         'fixed_tag'          => 'net_cash_movement_op',
        //                         'value_company_ye'   => $net_cash['net_cash_opt']
        //                     )
        //     )
        // );

        // array_push($cfs_fixed_data, 
        //     array(
        //         'id'    => '',
        //         'info'  => array(
        //                         'fs_company_info_id' => $fs_company_info_id,
        //                         'category_id'        => 2,
        //                         'fixed_tag'          => 'net_cash_movement_fin',
        //                         'value_company_ye'   => $net_cash['net_cash_inv']
        //                     )
        //     )
        // );

        // array_push($cfs_fixed_data, 
        //     array(
        //         'id'    => '',
        //         'info'  => array(
        //                         'fs_company_info_id' => $fs_company_info_id,
        //                         'category_id'        => 3,
        //                         'fixed_tag'          => 'net_cash_movement_inv',
        //                         'value_company_ye'   => $net_cash['net_cash_fin']
        //                     )
        //     )
        // );

        // // $this->fs_notes_model->insert_update_tbl_data_2('fs_state_cash_flows_fixed', $cfs_fixed_data);

        echo json_encode(array('status' => 1, 'header_id' => $header_id[0], 'body_id' => $body_id, 'checkbox_id' => $checkbox_id));
    }

    public function build_state_cash_flows_arr($id, $desc, $note_id, $value_company_lye_end, 
                                              $value_group_ye, $value_group_lye_end, 
                                              $parent, $category, $fs_company_info_id)
    {
        $temp_arr = array();

        foreach ($desc as $key => $each_desc) 
        {
            if(!($desc[$key] == '' && $value_company_lye_end[$key] == '' && $value_group_ye[$key] == '' && $value_group_lye_end[$key] == '' && $parent[$key] == '' && $category[$key] == ''))
            {
                $value_company_lye_end[$key] = is_null($value_company_lye_end[$key])? '': $value_company_lye_end[$key];
                $value_group_ye[$key]        = is_null($value_group_ye[$key])? '': $value_group_ye[$key];
                $value_group_lye_end[$key]   = is_null($value_group_lye_end[$key])? '': $value_group_lye_end[$key];

                array_push(
                    $temp_arr, array(
                        'id'                        => $id[$key],
                        'fs_company_info_id'        => $fs_company_info_id,
                        // 'parent_id'                 => $parent[$key],
                        // 'category_id'               => $category[$key],
                        // 'description'               => $desc[$key],
                        'fs_note_details_id'        => $note_id[$key],
                        // 'value_company_ye'          => $value_company_ye[$key],
                        'value_company_lye_end'     => $value_company_lye_end[$key], 
                        'value_group_ye'            => $value_group_ye[$key],
                        'value_group_lye_end'       => $value_group_lye_end[$key],
                        
                    )
                );

            }
        }

        return $temp_arr;
    }

    public function build_state_cash_flows_fixed_arr($note_id, $category_id, $group_ye, $group_lye_end, 
                                                     $company_ye, $company_lye_end, $fs_company_info_id)
    {
        $temp_arr = array();

        foreach ($company_ye as $key => $each_company_ye) 
        {
            if(!($group_ye[$key] == '' && $group_lye_end[$key] == '' && $company_lye_end[$key] == '' && $company_ye[$key] == '') || $key == "cash_equivalent_end")
            {
                if($key != "cash_equivalent_end")
                {
                    $temp_note_id = '';
                }
                else
                {
                    $temp_note_id = $note_id;
                }

                $company_lye_end[$key] = is_null($company_lye_end[$key])? '': $company_lye_end[$key];
                $group_ye[$key]        = is_null($group_ye[$key])? '': $group_ye[$key];
                $group_lye_end[$key]   = is_null($group_lye_end[$key])? '': $group_lye_end[$key];

                array_push(
                    $temp_arr, array(
    
                        'fs_company_info_id'        => $fs_company_info_id,
                        'fs_note_details_id'        => $temp_note_id,
                        'category_id'               => $category_id[$key],
                        'fixed_tag'                 => $key,
                        'value_company_ye'          => $company_ye[$key],
                        'value_company_lye_end'     => $company_lye_end[$key], 
                        'value_group_ye'            => $group_ye[$key],
                        'value_group_lye_end'       => $group_lye_end[$key],               
                    )
                );
            }
            
        }

        return $temp_arr;
    }


    public function build_state_cash_flows_section_arr($section_flag, $fs_company_info_id)
    {
        $temp_arr = array();

        foreach ($section_flag as $key => $section) {

           array_push(
                $temp_arr, array(
                    'fs_company_info_id'        => $fs_company_info_id,
                    'section_id'                => $key,
                    'status'               => $section                    
                )
            );
            
        }

        return $temp_arr;
    }

    public function test()
    {
        // $data = $this->fs_notes_model->rearrange_ntfs_template_layout(2);

        // echo json_encode($data);

        // $data = $this->fs_notes_model->get_insert_update_ntfs_layout_template(18, 0);

        // print_r($data);

        // move fs_state_cash_flows to 'fs_setup_cash_flows'

        $fs_company_info_id = 46;

        $fs_state_cash_flows = $this->db->query("SELECT * FROM fs_state_cash_flows WHERE fs_company_info_id=" . $fs_company_info_id);
        $fs_state_cash_flows = $fs_state_cash_flows->result_array();

        $fs_setup_state_cash_flows = $this->db->query("SELECT * FROM fs_setup_state_cash_flows WHERE fs_company_info_id=" . $fs_company_info_id);
        $fs_setup_state_cash_flows = $fs_setup_state_cash_flows->result_array();

        if(count($fs_setup_state_cash_flows) == 0)
        {
            foreach ($fs_state_cash_flows as $key => $value) 
            {
                array_push($fs_setup_state_cash_flows, 
                    array(
                        'id'    => '',
                        'info'  => array(
                                    'fs_company_info_id' => $fs_company_info_id,
                                    'is_checked'         => 1,
                                    'parent_id'          => $value['parent_id'],
                                    'category_id'        => $value['category_id'],
                                    'description'        => $value['description'],
                                    'main_value'         => $value['value_company_ye'],
                                    'order_by'           => $key + 1 
                                )
                    )
                );
            }

            $return_ids = $this->fs_notes_model->insert_update_tbl_data_2('fs_setup_state_cash_flows', $fs_setup_state_cash_flows);

            // print_r($return_ids);

            $update_fs_setup_state_cash_flows_id = [];

            foreach ($fs_state_cash_flows as $key => $value) 
            {
                array_push($update_fs_setup_state_cash_flows_id,
                    array(
                        'id'    => $value['id'],
                        'info'  => array(
                                        'fs_setup_state_cash_flows_id' => $return_ids[$key],
                                        'order_by' => $key + 1
                                    )
                    )
                );
            }

            $return_ids = $this->fs_notes_model->insert_update_tbl_data_2('fs_state_cash_flows', $update_fs_setup_state_cash_flows_id);

            // fs_setup_state_cash_flows_id
        }


    }
}