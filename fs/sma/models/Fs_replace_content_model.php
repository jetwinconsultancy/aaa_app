<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fs_replace_content_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'encryption'));

        $this->load->model('fs_model');
        $this->load->model('fs_notes_model');
        $this->load->model('fs_statements_model');
    }

    public function ntfs_replace_toggle($document_layout, $section_name, $fs_company_info_id)
    {
        $temp_template = '';

        // $section_name = "";
        // $content_template = "";

        $fs_company_info    = $this->fs_model->get_fs_company_info($fs_company_info_id);
        $final_report_type  = $this->fs_model->get_final_document_type($fs_company_info_id);

        $pattern = "/{{[^}}]*}}/";
        $content_template = $document_layout;
        // $content_template = $data;
        preg_match_all($pattern, $content_template, $matches_content);

        // SECTION: Domicile and activities
        if($section_name == "DOMICILE AND ACTIVITIES")
        {
            for($r = 0; $r < count($matches_content[0]); $r++)
            {
                $string1 = (str_replace('{{', '',$matches_content[0][$r]));
                $string2 = (str_replace('}}', '',$string1));

                $replace_string_c = $matches_content[0][$r];

                // ULTIMATE COMPANY PART
                $ultimate_info = $this->db->query("SELECT fs_dir_statement_company.*, country.name, country.nicename
                                                    FROM fs_dir_statement_company 
                                                    LEFT JOIN fs_country country ON country.id = fs_dir_statement_company.country_id
                                                    WHERE fs_dir_statement_company.fs_company_info_id=" . $fs_company_info_id . " AND fs_dir_statement_company.fs_company_type_id=1");
                $ultimate_info = $ultimate_info->result_array();

                // return json_encode($ultimate_info);

                if(count($ultimate_info) > 0)
                {
                    if($string2 == "ultimate company - name")
                    {
                        $content_template = str_replace($replace_string_c, $ultimate_info[0]['company_name'], $content_template);
                    }

                    if($string2 == "ultimate company - country")
                    {
                        $content_template = str_replace($replace_string_c, $ultimate_info[0]['nicename'], $content_template);
                    }
                }
                else // remove ultimate company part
                {   
                    $ultimate_template = $this->get_part_of_template('<p class="ultimate_company"', 'p', $content_template);

                    $content_template = preg_replace('(' . $ultimate_template[0][0] . ')', "", $content_template, 1);
                }
                // END OF ULTIMATE COMPANY PART

                // CHANGE COMPANY NAME PART
                if(empty($fs_company_info[0]['old_company_name']))  // remove change company name part
                {   
                    $change_name_template = $this->get_part_of_template('<p class="change_company_name"', 'p', $content_template);

                    $content_template = preg_replace('(' . $change_name_template[0][0] . ')', "", $content_template, 1);
                }
                // END OF CHANGE COMPANY NAME PART
            }
            
            $temp_template .= $content_template;
        }

        // SECTION: Basis of preparation
        elseif ($section_name == "Basis of preparation")
        {   
            if(!$fs_company_info[0]['company_liquidated'])
            {
                $company_liquidated_template = $this->get_part_of_template('<p class="company_liquidated"', 'p', $content_template);

                $content_template = preg_replace('(' . $company_liquidated_template[0][0] . ')', "", $content_template, 1);
            }
            $temp_template .= $content_template;
        }

        // SECTION: Functional and presentation currency
        elseif ($section_name == "Functional and presentation currency")
        {   
            // GET FP CURRENCY INFO
            $fp_currency_info = $this->fs_model->get_fs_fp_currency_details($fs_company_info_id);

            // DISPLAY PART IF FC AND PC ARE SAME
            if($fp_currency_info[0]['last_year_fc_currency_id'] == $fp_currency_info[0]['last_year_pc_currency_id'])
            {
                $fc_pc_same_template = $this->get_part_of_template('<div class="fc_pc_same"', 'div', $content_template);

                // remove div tag
                $removed_div_fc_pc_same_template = preg_replace('(<div class="fc_pc_same">)', "", $fc_pc_same_template[0][0], 1);
                $removed_div_fc_pc_same_template = preg_replace('(</div>)', "", $removed_div_fc_pc_same_template, 1);

                $content_template = preg_replace('(' . $fc_pc_same_template[0][0] . ')', $removed_div_fc_pc_same_template, $content_template, 1);

                // remove fc_pc_different
                $fc_pc_different_template = $this->get_part_of_template('<div class="fc_pc_different"', 'div', $content_template);
                $content_template = preg_replace('(' . $fc_pc_different_template[0][0] . ')', "", $content_template, 1);
            }
            else // DISPLAY PART IF FC AND PC ARE DIFFERENT
            {
                $fc_pc_different_template = $this->get_part_of_template('<div class="fc_pc_different"', 'div', $content_template);

                // remove div tag
                $removed_div_fc_pc_different_template = preg_replace('(<div class="fc_pc_different">)', "", $fc_pc_different_template[0][0], 1);
                $removed_div_fc_pc_different_template = preg_replace('(</div>)', "", $removed_div_fc_pc_different_template, 1);

                $content_template = preg_replace('(' . $fc_pc_different_template[0][0] . ')', $removed_div_fc_pc_different_template, $content_template, 1);

                // remove fc_pc_same
                $fc_pc_same_template = $this->get_part_of_template('<div class="fc_pc_same"', 'div', $content_template);
                $content_template    = preg_replace('(' . $fc_pc_same_template[0][0] . ')', "", $content_template, 1);
            }

            // if got subsidiary/ies
            if($fs_company_info[0]['group_type'] == 1) 
            {
                $company_has_subsidiary_template = $this->get_part_of_template('<p class="company_has_subsidiary"', 'p', $content_template);

                $content_template = preg_replace('(' . $company_has_subsidiary_template[0][0] . ')', "", $content_template, 1);
            }

            // REMOVE PART IF OLD FC AND CURRENT FC ARE SAME
            if($fp_currency_info[0]['last_year_fc_currency_id'] == $fp_currency_info[0]['current_year_fc_currency_id'])
            {
                $company_change_fc_template = $this->get_part_of_template('<p class="company_change_fc"', 'p', $content_template);
                $content_template = preg_replace('(' . $company_change_fc_template[0][0] . ')', "", $content_template, 1);
            }

            $pattern = "/{{[^}}]*}}/";
            preg_match_all($pattern, $content_template, $matches_sub_part);

            for($x = 0; $x < count($matches_sub_part[0]); $x++)
            {
                $string1 = (str_replace('{{', '',$matches_sub_part[0][$x]));
                $string2 = (str_replace('}}', '',$string1));

                $replace_string_sub_part = $matches_sub_part[0][$x];

                if($string2 == "Functional Presentation Currency")
                {
                    $content_template = str_replace($replace_string_sub_part, $this->fs_model->get_currency_info($fp_currency_info[0]['current_year_pc_currency_id'])[0]['name'], $content_template);
                }

                if($string2 == "Functional Currency - Last Year")
                {
                    $content_template = str_replace($replace_string_sub_part, $this->fs_model->get_currency_info($fp_currency_info[0]['last_year_fc_currency_id'])[0]['name'], $content_template);
                }

                if($string2 == "Functional Currency - Current Year")
                {
                    $content_template = str_replace($replace_string_sub_part, $this->fs_model->get_currency_info($fp_currency_info[0]['current_year_fc_currency_id'])[0]['name'], $content_template);
                }

                if($string2 == "Presentation Curreny - Last Year")
                {
                    $content_template = str_replace($replace_string_sub_part, $this->fs_model->get_currency_info($fp_currency_info[0]['last_year_pc_currency_id'])[0]['name'], $content_template);
                }

                if($string2 == "Functional Currency - Reason of changing")
                {
                    $content_template = str_replace($replace_string_sub_part, $fp_currency_info[0]['reason_of_changing_fc'], $content_template);
                }

                if($string2 == "Presentation Curreny Country - Last Year")
                {
                    $content_template = str_replace($replace_string_sub_part, $this->fs_model->get_currency_info($fp_currency_info[0]['current_year_fc_currency_id'])[0]['country_name'], $content_template);
                }
            }

            $temp_template .= $content_template;
        }
        // SECTION: Foreign currency transactions and balances
        elseif ($section_name == "Foreign currency transactions and balances")
        {
            // if got subsidiary/ies
            if($fs_company_info[0]['group_type'] == '1') 
            {
                $company_has_foreign_subsidiary_template = $this->get_part_of_template('<div class="company_foreign_subsidiaries"', 'div', $content_template);

                $content_template = preg_replace('(' . $company_has_foreign_subsidiary_template[0][0] . ')', "", $content_template, 1);
            }
            else
            {
                $iis_db = $this->db->query("SELECT * FROM fs_investment_in_subsidiaries iis 
                                            LEFT JOIN fs_note_details nd ON nd.id = iis.fs_note_details_id
                                            LEFT JOIN audit_categorized_account fca ON fca.id = nd.fs_categorized_account_round_off_id
                                            LEFT JOIN fs_country c ON c.id = iis.country_id
                                            WHERE iis.parent_id = 0 AND c.name!= 'SINGAPORE' AND fca.fs_company_info_id =" . $fs_company_info_id);

                $iis_db = $iis_db->result_array();

                if(count($iis_db) == 0)
                {
                    $company_has_foreign_subsidiary_template = $this->get_part_of_template('<div class="company_foreign_subsidiaries"', 'div', $content_template);
                    $content_template = preg_replace('/' . $company_has_foreign_subsidiary_template[0][0] . '/', "", $content_template, 1);
                }
            }

            $temp_template .= $content_template;
        }
        elseif ($section_name == "Group accounting - content")
        {
            // if got subsidiary/ies
            if($fs_company_info[0]['group_type'] == '1') 
            {
                $group_not_consolidated = $this->get_part_of_template('<p class="group_not_consolidated"', 'p', $content_template);

                $content_template = preg_replace('/' . $group_not_consolidated[0][0] . '/', "", $content_template, 1);
            }
            else
            {
                if($fs_company_info[0]['is_group_consolidated'] == '1')
                {
                    $group_not_consolidated = $this->get_part_of_template('<p class="group_not_consolidated"', 'p', $content_template);

                    // $content_template = json_encode($group_not_consolidated);

                    $content_template = preg_replace('/' . $group_not_consolidated[0][0] . '/', "", $content_template, 1);
                }
                else
                {
                    
                }
            }

            $temp_template .= $content_template;
        }
        // SECTION: Employee benefits
        elseif ($section_name == "Share-based payment transactions")
        {
            $eb = $this->fs_notes_model->get_fs_employee_benefits($fs_company_info_id);

            if(strpos($content_template, '{{shared-based payment transaction content}}') !== false)
            {
                if(isset($eb[0]['share_based_payment_transaction']))
                {
                    $content_template = str_replace('{{shared-based payment transaction content}}', nl2br($eb[0]['share_based_payment_transaction']), $content_template);
                }
            }
            // echo $content_template;
            $temp_template .= $content_template;
        }
        elseif ($section_name == "Taxation")
        {
            $template_show_if_got_group = $this->get_part_of_template('<span class="show_if_got_group"', 'span', $content_template);

            // if(strpos($content_template, '{{model content}}') !== false)
            // {
            //     if(isset($ip[0]['model_content']))
            //     {
            //         $content_template = str_replace('{{model content}}', nl2br($ip[0]['model_content']), $content_template);
            //     }
            // }
            if($fs_company_info[0]['group_type'] == '1') // hide content if no group
            {
                $content_template = str_replace($template_show_if_got_group[0][0], '', $content_template);
            }

            $temp_template .= $content_template;
        }
        elseif ($section_name == "Investment in associate and joint ventures")
        {
            $show_content = $this->fs_model->show_investment_in_associate_and_join_ventures($fs_company_info_id);

            if($show_content)
            {
                $temp_template .= $content_template;
            }
        }
        elseif ($section_name == "Research and development costs")
        {
            $ia_info = $this->fs_notes_model->get_fs_sub_intangible_assets_info($fs_company_info_id);

            if(strpos($content_template, '{{Range of year}}') !== false)
            {
                if(isset($ia_info[0]['range_of_year']))
                {
                    $content_template = str_replace('{{Range of year}}', nl2br($ia_info[0]['range_of_year']), $content_template);
                }
                else
                {
                    $content_template = str_replace('{{Range of year}}', '______________', $content_template);
                }
            }

            $temp_template .= $content_template;
        }
        elseif ($section_name == "Other intangible assets")
        {
            $template_intangible_assets = $this->get_part_of_template('<tr class="assets_data"', 'tr', $content_template);

            $intangible_assets_data = $this->fs_notes_model->get_fs_sub_intangible_assets($fs_company_info_id);

            $trs_replace_combined = '';

            foreach ($intangible_assets_data as $key => $ia) 
            {
                $tr_intangible_assets = $template_intangible_assets[0][0];

                // For account name tr
                if(strpos($tr_intangible_assets, '{{Asset name}}') !== false)
                {
                    $tr_intangible_assets = str_replace('{{Asset name}}', $ia['name'], $tr_intangible_assets);
                }
                
                if(strpos($tr_intangible_assets, '{{Duration}}') !== false)
                {
                    $tr_intangible_assets = str_replace('{{Duration}}', $ia['duration'], $tr_intangible_assets);
                }

                $trs_replace_combined .= $tr_intangible_assets;
            }

            $content_template = str_replace($template_intangible_assets[0][0], $trs_replace_combined, $content_template);

            $temp_template .= $content_template;
        }
        elseif ($section_name == "Investment properties")
        {
            $ip = $this->fs_notes_model->get_fs_investment_properties($fs_company_info_id);

            if(strpos($content_template, '{{model content}}') !== false)
            {
                if(isset($ip[0]['model_content']))
                {
                    $content_template = str_replace('{{model content}}', nl2br($ip[0]['model_content']), $content_template);
                }
            }

            $temp_template .= $content_template;
        }
        elseif ($section_name == "Property, plant and equipment")
        {
            $ppe_info_data = $this->fs_notes_model->get_fs_sub_ppe_info($fs_company_info_id);

            if(strpos($content_template, '{{Depreciation method}}') !== false)
            {
                if(isset($ppe_info_data[0]['method_name']))
                {
                    $content_template = str_replace('{{Depreciation method}}', nl2br($ppe_info_data[0]['method_name']), $content_template);
                }
                else
                {
                    $content_template = str_replace('{{Depreciation method}}', '______________', $content_template);
                }
            }

            // display asset list
            $template_ppe = $this->get_part_of_template('<tr class="assets_data"', 'tr', $content_template);

            $ppe_data = $this->fs_notes_model->get_fs_sub_ppe($fs_company_info_id);

            $trs_replace_combined = '';

            foreach ($ppe_data as $key => $ppe) 
            {
                $tr_ppe = $template_ppe[0][0];

                // For account name tr
                if(strpos($tr_ppe, '{{Asset name}}') !== false)
                {
                    $tr_ppe = str_replace('{{Asset name}}', $ppe['name'], $tr_ppe);
                }
                
                if(strpos($tr_ppe, '{{Duration}}') !== false)
                {
                    $tr_ppe = str_replace('{{Duration}}', $ppe['duration'], $tr_ppe);
                }

                $trs_replace_combined .= $tr_ppe;
            }

            $content_template = str_replace($template_ppe[0][0], $trs_replace_combined, $content_template);

            $temp_template .= $content_template;
        }
        elseif ($section_name == "Inventories") 
        {
            $inventories_info = $this->fs_notes_model->get_fs_sub_inventories_info($fs_company_info_id);

            if(strpos($content_template, '{{Net realizable value}}') !== false)
            {
                if(isset($inventories_info[0]['name']))
                {
                    $content_template = str_replace('{{Net realizable value}}', nl2br($inventories_info[0]['name']), $content_template);
                }
                else
                {
                    $content_template = str_replace('{{Net realizable value}}', '_______________________', $content_template);
                }
            }
            
            $temp_template .= $content_template;
        }
        elseif ($section_name == "Provision")
        {
            $p = $this->fs_notes_model->get_fs_sub_provision($fs_company_info_id);

            if(strpos($content_template, '{{provision content}}') !== false)
            {
                $temp_p = '';

                if(count($p) > 0)
                {
                    foreach ($p as $key => $value) 
                    {
                        if($value['is_shown'] == '1')
                        {
                            if($key < count($p) - 1) // if not last counter, add in <br/><br/>
                            {
                                $temp_p .= $value['content'] . '<br/><br/>';
                            }
                            else
                            {
                                $temp_p .= $value['content'];
                            }
                            // $content_template = str_replace('{{provision content}}', nl2br($value['content']), $content_template);
                        }
                    }
                }

                $content_template = str_replace('{{provision content}}', nl2br($temp_p), $content_template);

                // if(isset($p[0]['content']))
                // {
                //     $content_template = str_replace('{{provision content}}', nl2br($p[0]['content']), $content_template);
                // }
            }

            $temp_template .= $content_template;
        }
        elseif ($section_name == "EMPLOYEE BENEFITS EXPENSE")
        {
            // if no group then remove group column from tr template
            if($fs_company_info[0]['group_type'] == 1)
            {
                $tr_group_cols = $this->get_part_of_template('<td class="group_col"', 'td', $content_template);

                foreach ($tr_group_cols[0] as $key => $group_col) 
                {
                    $content_template = str_replace($group_col, '', $content_template);
                }

                // set width for td
                $width_tds = array(
                                'width_description' => '74',
                                'width_merge_2_td'  => '35',
                                'width_td'          => '17.5',
                );

            }
            else
            {
                // set width for td
                $width_tds = array(
                                'width_description' => '39',
                                'width_merge_2_td'  => '35',
                                'width_td'          => '17.5',
                );
            }

            // display employee benefits expense
            $template_ebe = $this->get_part_of_template('<tr class="expense_list"', 'tr', $content_template);
            $tr_ebe       = $template_ebe[0][0];

            // $ebe_account_list = $this->fs_statements_model->get_account_category_item_list($fs_company_info_id, array('E101'));

            $fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, array('E101'));
            $ebe_account_list = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $fca_id);
            $ebe_ntfs         = $this->fs_notes_model->get_employee_benefits_expense_ntfs($fs_company_info_id);

            // print_r($ebe_account_list);

            $data = [];

            foreach($ebe_account_list[0]['child_array'] as $key => $value)
            {
                $subtotal = 0.00;
                $subtotal_last_year = 0.00;

                if(!empty($value['parent_array']))
                {
                    array_push($data, $value['parent_array'][0]);

                    $subtotal += $value['parent_array'][0]['total_c'];
                    $subtotal_last_year += $value['parent_array'][0]['total_c_lye'];
                }
                // else
                // {
                //     array_push($data, $value['child_array'][0]);

                //     $subtotal += $value['child_array']['value'];
                //     $subtotal_last_year += $value['parent_array'][0]['company_end_prev_ye_value'];
                // }

                $total_operating_expenses += $subtotal;
                $total_operating_expenses_last_year += $subtotal_last_year;
            }

            $ebe_account_list_data = array($data);

            $trs_replace_combined = '';

            $total_cye_group   = 0;
            $total_lye_group   = 0;
            $total_cye_company = 0;
            $total_lye_company = 0;

            foreach($ebe_account_list_data as $ebe_key => $ebe_account)
            {
                foreach($ebe_account as $ebe_key => $ebe_account_item)
                {
                    $tr_ebe = $template_ebe[0][0];
                    
                    // For account name tr
                    if(strpos($tr_ebe, '{{Expense name}}') !== false)
                    {
                        $tr_ebe = str_replace('{{Expense name}}', $ebe_account_item['description'], $tr_ebe);
                    }
                    
                    if(strpos($tr_ebe, '{{Current Year End Value - Group}}') !== false)
                    {
                        if(empty($ebe_account_item['group_end_this_ye_value']) || $ebe_account_item['group_end_this_ye_value'] == 0)
                        {
                            $tr_ebe = str_replace('{{Current Year End Value - Group}}', '-', $tr_ebe);
                        }
                        else
                        {
                            $tr_ebe = str_replace('{{Current Year End Value - Group}}', $this->negative_bracket($ebe_account_item['group_end_this_ye_value']), $tr_ebe);
                        }

                        // Sum up total
                        $total_cye_group += $ebe_account_item['group_end_this_ye_value'];
                    }

                    if(strpos($tr_ebe, '{{Last Year End Value - Group}}') !== false)
                    {
                        if(empty($ebe_account_item['group_end_prev_ye_value']) || $ebe_account_item['group_end_prev_ye_value'] == 0)
                        {
                            $tr_ebe = str_replace('{{Last Year End Value - Group}}', '-', $tr_ebe);
                        }
                        else
                        {
                            $tr_ebe = str_replace('{{Last Year End Value - Group}}', $this->negative_bracket($ebe_account_item['group_end_prev_ye_value']), $tr_ebe);
                        }

                        // Sum up total
                        $total_lye_group += $ebe_account_item['group_end_prev_ye_value'];
                    }

                    if(strpos($tr_ebe, '{{Current Year End Value - Company}}') !== false)
                    {
                        if(empty($ebe_account_item['total_c']) || $ebe_account_item['total_c'] == 0)
                        {
                            $tr_ebe = str_replace('{{Current Year End Value - Company}}', '-', $tr_ebe);
                        }
                        else
                        {
                            $tr_ebe = str_replace('{{Current Year End Value - Company}}', $this->negative_bracket($ebe_account_item['total_c']), $tr_ebe);
                        }

                        // Sum up total
                        $total_cye_company += $ebe_account_item['total_c'];
                    }

                    if(strpos($tr_ebe, '{{Last Year End Value - Company}}') !== false)
                    {
                        if(empty($ebe_account_item['total_c_lye']) || $ebe_account_item['total_c_lye'] == 0)
                        {
                            $tr_ebe = str_replace('{{Last Year End Value - Company}}', '-', $tr_ebe);
                        }
                        else
                        {
                            $tr_ebe = str_replace('{{Last Year End Value - Company}}', $this->negative_bracket($ebe_account_item['total_c_lye']), $tr_ebe);
                        }

                        // Sum up total
                        $total_lye_company += $ebe_account_item['total_c_lye'];
                    }

                    $trs_replace_combined .= $tr_ebe;
                }
            }

            /* ----------------------------------------------------- Display total ----------------------------------------------------- */
            if(strpos($content_template, '{{Total for group current year}}') !== false)
            {
                if(empty($total_cye_group) || $total_cye_group == 0)
                {
                    $content_template = str_replace('{{Total for group current year}}', '-', $content_template);
                }
                else
                {
                    $content_template = str_replace('{{Total for group current year}}', $this->negative_bracket($total_cye_group), $content_template);
                }
            }

            if(strpos($content_template, '{{Total for group last year}}') !== false)
            {
                if(empty($total_lye_group) || $total_lye_group == 0)
                {
                    $content_template = str_replace('{{Total for group last year}}', '-', $content_template);
                }
                else
                {
                    $content_template = str_replace('{{Total for group last year}}', $this->negative_bracket($total_lye_group), $content_template);
                }
            }

            if(strpos($content_template, '{{Total for company current year}}') !== false)
            {
                if(empty($total_cye_company) || $total_cye_company == 0)
                {
                    $content_template = str_replace('{{Total for company current year}}', '-', $content_template);
                }
                else
                {
                    $content_template = str_replace('{{Total for company current year}}', $this->negative_bracket($total_cye_company), $content_template);
                }
            }

            if(strpos($content_template, '{{Total for company last year}}') !== false)
            {
                if(empty($total_lye_company) || $total_lye_company == 0)
                {
                    $content_template = str_replace('{{Total for company last year}}', '-', $content_template);
                }
                else
                {
                    $content_template = str_replace('{{Total for company last year}}', $this->negative_bracket($total_lye_company), $content_template);
                }
            }

            $content_template = str_replace($template_ebe[0][0], $trs_replace_combined, $content_template);
            
            /* ----------------------------------------------------- Set width for td ----------------------------------------------------- */
            if(strpos($content_template, '{{width_description}}') !== false)
            {
                $content_template = str_replace('{{width_description}}', $width_tds['width_description'], $content_template);
            }

            if(strpos($content_template, '{{width_merge_2_td}}') !== false)
            {
                $content_template = str_replace('{{width_merge_2_td}}', $width_tds['width_merge_2_td'], $content_template);
            }

            if(strpos($content_template, '{{width_td}}') !== false)
            {
                $content_template = str_replace('{{width_td}}', $width_tds['width_td'], $content_template);
            }

            // // Display "Share Option Plans Content"
            // if(strpos($content_template, '{{Share Option Plans Content}}') !== false)
            // {
            //     if(isset($ebe_ntfs[0]['share_option_plans_content']))
            //     {
            //         $content_template = str_replace('{{Share Option Plans Content}}', nl2br($ebe_ntfs[0]['share_option_plans_content']), $content_template);
            //     }
            //     else
            //     {
            //         $temp_content_text = '';

            //         if($final_report_type == 1)
            //         {
            //             $temp_content_text = 'Included in the employee benefits expense is a CPF contribution by employer amounted to $1,000 (2017: $900).';
            //         }
            //         else
            //         {
            //             $temp_content_text = 'Included in the employee benefits expense is a CPF contribution by employer amounted to $1,000 (2017: $900).';
            //         }

            //         $content_template = str_replace('{{Share Option Plans Content}}', $temp_content_text, $content_template);
            //     }
            // }

            $temp_template .= $content_template;
        }
        elseif ($section_name == "PROFIT BEFORE TAX")
        {
            // if no group then remove group column from tr template
            if($fs_company_info[0]['group_type'] == 1)
            {
                $tr_group_cols = $this->get_part_of_template('<td class="group_col"', 'td', $content_template);

                foreach ($tr_group_cols[0] as $key => $group_col) 
                {
                    $content_template = str_replace($group_col, '', $content_template);
                }

                // set width for td
                $width_tds = array(
                                'width_description' => '74',
                                'width_merge_2_td'  => '35',
                                'width_td'          => '17.5',
                );

            }
            else
            {
                // set width for td
                $width_tds = array(
                                'width_description' => '39',
                                'width_merge_2_td'  => '35',
                                'width_td'          => '17.5',
                );
            }

            $pbt = $this->fs_notes_model->get_fs_profit_be4_tax($fs_company_info_id);

            // echo json_encode($pbt);

            // display profit before tax (income)
            $template_pbt_income = $this->get_part_of_template('<tr class="income_list"', 'tr', $content_template);
            $trs_replace_combined = '';

            foreach($pbt as $pbt_key => $pbt_account_item)
            {
                if($pbt_account_item['income_expense_type'] == "income")
                {
                    $tr_pbt_income = $template_pbt_income[0][0];

                    $trs_replace_combined .= $this->replace_table_content_by_tr($tr_pbt_income, $pbt_account_item);
                }
            }

            $content_template = str_replace($template_pbt_income[0][0], $trs_replace_combined, $content_template);

            // display profit before tax (income)
            $template_pbt_expense = $this->get_part_of_template('<tr class="expenses_list"', 'tr', $content_template);
            $trs_replace_combined = '';

            foreach($pbt as $pbt_key => $pbt_account_item)
            {
                if($pbt_account_item['income_expense_type'] == "expense")
                {
                    $tr_pbt_income = $template_pbt_expense[0][0];

                    $trs_replace_combined .= $this->replace_table_content_by_tr($tr_pbt_income, $pbt_account_item);
                }
            }

            $content_template = str_replace($template_pbt_expense[0][0], $trs_replace_combined, $content_template);


            /* ----------------------------------------------------- Set width for td ----------------------------------------------------- */
            if(strpos($content_template, '{{width_description}}') !== false)
            {
                $content_template = str_replace('{{width_description}}', $width_tds['width_description'], $content_template);
            }

            if(strpos($content_template, '{{width_merge_2_td}}') !== false)
            {
                $content_template = str_replace('{{width_merge_2_td}}', $width_tds['width_merge_2_td'], $content_template);
            }

            if(strpos($content_template, '{{width_td}}') !== false)
            {
                $content_template = str_replace('{{width_td}}', $width_tds['width_td'], $content_template);
            }

            // // Display "Share Option Plans Content"
            // if(strpos($content_template, '{{Share Option Plans Content}}') !== false)
            // {
            //     if(isset($ebe_ntfs[0]['share_option_plans_content']))
            //     {
            //         $content_template = str_replace('{{Share Option Plans Content}}', nl2br($ebe_ntfs[0]['share_option_plans_content']), $content_template);
            //     }
            //     else
            //     {
            //         $content_template = str_replace('{{Share Option Plans Content}}', '', $content_template);
            //     }
            // }

            $temp_template .= $content_template;
        }
        elseif ($section_name == "TAX EXPENSE")
        {
            if(strpos($content_template, '{{Section Name}}') !== false)
            {
                $content_template = str_replace('{{Section Name}}', "TAX EXPENSE", $content_template);
            }

            // if no group then remove group column from tr template
            if($fs_company_info[0]['group_type'] == 1)
            {
                $tr_group_cols = $this->get_part_of_template('<td class="group_col"', 'td', $content_template);

                foreach ($tr_group_cols[0] as $key => $group_col) 
                {
                    $content_template = str_replace($group_col, '', $content_template);
                }

                // set width for td
                $width_tds = array(
                                'width_description' => '74',
                                'width_merge_2_td'  => '35',
                                'width_td'          => '17.5',
                );

            }
            else
            {
                // set width for td
                $width_tds = array(
                                'width_description' => '39',
                                'width_merge_2_td'  => '35',
                                'width_td'          => '17.5',
                );
            }

            /* ----------------------------------------------------- Set width for td ----------------------------------------------------- */
            $content_template = $this->replace_width_td($content_template, $width_tds);

            /* ------------------------------------------- Display tax expense list ------------------------------------------- */
            $template_te = $this->get_part_of_template('<tr class="te_list"', 'tr', $content_template);
            $te = $this->fs_notes_model->get_fs_tax_expense_ntfs($fs_company_info_id);
            $trs_replace_combined = '';

            $total_cye_group   = 0.00;
            $total_lye_group   = 0.00;
            $total_cye_company = 0.00;
            $total_lye_company = 0.00;

            foreach($te as $te_key => $te_account_item)
            {
                $tr_te = $template_te[0][0];

                $trs_replace_combined .= $this->replace_table_content_by_tr($tr_te, $te_account_item);
                
                if(!empty($te_account_item['group_end_this_ye_value']))
                {
                    $total_cye_group += $te_account_item['group_end_this_ye_value'];
                }
                
                if(!empty($te_account_item['group_end_prev_ye_value']))
                {
                    $total_lye_group += $te_account_item['group_end_prev_ye_value'];
                }

                if(!empty($te_account_item['value']))
                {
                    $total_cye_company += $te_account_item['value'];
                }

                if(!empty($te_account_item['company_end_prev_ye_value']))
                {
                    $total_lye_company += $te_account_item['company_end_prev_ye_value'];
                }
            }

            $content_template = str_replace($template_te[0][0], $trs_replace_combined, $content_template);

            /* ----------------------------------------------------- Display total ----------------------------------------------------- */
            $content_template = $this->replace_totals($content_template, 
                                    array(
                                        'total_cye_group'   => $total_cye_group,
                                        'total_lye_group'   => $total_lye_group,
                                        'total_cye_company' => $total_cye_company,
                                        'total_lye_company' => $total_lye_company
                                ));

            /* ------------------------------------------- Display tax expense list reconciliation ------------------------------------------- */
            $template_ter = $this->get_part_of_template('<tr class="ter_list"', 'tr', $content_template);
            $ter = $this->fs_notes_model->get_fs_tax_expense_reconciliation($fs_company_info_id);
            $trs_replace_combined = '';

            $total_cye_group   = 0.00;
            $total_lye_group   = 0.00;
            $total_cye_company = 0.00;
            $total_lye_company = 0.00;

            foreach($ter as $ter_key => $ter_account_item)
            {
                $tr_ter = $template_ter[0][0];

                if((!empty($ter_account_item['group_end_this_ye_value'])    && !($ter_account_item['group_end_this_ye_value'] == 0))   && 
                    (!empty($ter_account_item['group_end_prev_ye_value'])   && !($ter_account_item['group_end_prev_ye_value'] == 0))   && 
                    (!empty($ter_account_item['value'])                     && !($ter_account_item['value'] == 0))                     && 
                    (!empty($ter_account_item['company_end_prev_ye_value']) && !($ter_account_item['company_end_prev_ye_value'] == 0)) 
                )
                $trs_replace_combined .= $this->replace_table_content_by_tr($tr_ter, $ter_account_item);
                
                if(!empty($ter_account_item['group_end_this_ye_value']))
                {
                    $total_cye_group += $ter_account_item['group_end_this_ye_value'];
                }
                
                if(!empty($ter_account_item['group_end_prev_ye_value']))
                {
                    $total_lye_group += $ter_account_item['group_end_prev_ye_value'];
                }

                if(!empty($ter_account_item['value']))
                {
                    $total_cye_company += $ter_account_item['value'];
                }

                if(!empty($ter_account_item['company_end_prev_ye_value']))
                {
                    $total_lye_company += $ter_account_item['company_end_prev_ye_value'];
                }
            }

            $content_template = str_replace($template_ter[0][0], $trs_replace_combined, $content_template);

            /* ----------------------------------------------- Display profit or loss before tax ----------------------------------------------- */
            $fs_sci_data = $this->fs_statements_model->get_fs_state_comp_income($fs_company_info_id);
            $pl_b4_tax   = [];

            foreach ($fs_sci_data as $pl_b4_key => $pl_b4_value) 
            {
                if($pl_b4_value['fs_list_state_comp_income_section_id'] == 3)
                {
                    array_push($pl_b4_tax, $fs_sci_data[$pl_b4_key]);
                }
            }

            if(strpos($content_template, '{{Profit/Loss before tax display}}') !== false)
            {
                if(isset($pl_b4_tax[0]['description']))
                {
                    $content_template = str_replace('{{Profit/Loss before tax display}}', $pl_b4_tax[0]['description'], $content_template);
                }
                else
                {
                    $content_template = str_replace('{{Profit/Loss before tax display}}', '________________', $content_template);
                }
            }

            if(strpos($content_template, '{{P/L Current Year End Value - Group}}') !== false)
            {
                if(isset($pl_b4_tax[0]['value_group_ye']))
                {
                    $content_template = str_replace('{{P/L Current Year End Value - Group}}', $this->negative_bracket($pl_b4_tax[0]['value_group_ye']), $content_template);
                }
                else
                {
                    $content_template = str_replace('{{P/L Current Year End Value - Group}}', '________________', $content_template);
                }
            }

            if(strpos($content_template, '{{P/L Last Year End Value - Group}}') !== false)
            {
                if(isset($pl_b4_tax[0]['value_group_lye_end']))
                {
                    $content_template = str_replace('{{P/L Last Year End Value - Group}}', $this->negative_bracket($pl_b4_tax[0]['value_group_lye_end']), $content_template);
                }
                else
                {
                    $content_template = str_replace('{{P/L Last Year End Value - Group}}', '________________', $content_template);
                }
            }

            if(strpos($content_template, '{{P/L Current Year End Value - Company}}') !== false)
            {
                if(isset($pl_b4_tax[0]['value_company_ye']))
                {
                    $content_template = str_replace('{{P/L Current Year End Value - Company}}', $this->negative_bracket($pl_b4_tax[0]['value_company_ye']), $content_template);
                }
                else
                {
                    $content_template = str_replace('{{P/L Current Year End Value - Company}}', '________________', $content_template);
                }
            }

            if(strpos($content_template, '{{P/L Last Year End Value - Company}}') !== false)
            {
                if(isset($pl_b4_tax[0]['value_company_lye_end']))
                {
                    $content_template = str_replace('{{P/L Last Year End Value - Company}}', $this->negative_bracket($pl_b4_tax[0]['value_company_lye_end']), $content_template);
                }
                else
                {
                    $content_template = str_replace('{{P/L Last Year End Value - Company}}', '________________', $content_template);
                }
            }

            /* ----------------------------------------------------- Display total for ter ----------------------------------------------------- */
            $content_template = $this->replace_totals($content_template, 
                                    array(
                                        'total_cye_group'   => $total_cye_group,
                                        'total_lye_group'   => $total_lye_group,
                                        'total_cye_company' => $total_cye_company,
                                        'total_lye_company' => $total_lye_company
                                ));

            // if(strpos($content_template, '{{Total for group current year}}') !== false)
            // {
            //     $content_template = str_replace('{{Total for group current year}}', $this->negative_bracket($total_cye_group), $content_template);
            // }

            // if(strpos($content_template, '{{Total for group last year}}') !== false)
            // {
            //     $content_template = str_replace('{{Total for group last year}}', $this->negative_bracket($total_lye_group), $content_template);
            // }

            // if(strpos($content_template, '{{Total for company current year}}') !== false)
            // {
            //     $content_template = str_replace('{{Total for company current year}}', $this->negative_bracket($total_cye_company), $content_template);
            // }

            // if(strpos($content_template, '{{Total for company last year}}') !== false)
            // {
            //     $content_template = str_replace('{{Total for company last year}}', $this->negative_bracket($total_lye_company), $content_template);
            // }

            /* ------------------------------------------- Display content ------------------------------------------- */
            $te_info = $this->fs_notes_model->get_fs_tax_expense_ntfs_info($fs_company_info_id, 2);

            if(strpos($content_template, '{{Company has unabsorbed tax losses - content}}') !== false)
            {
                if(isset($te_info[0]['text_content']))
                {
                    $content_template = str_replace('{{Company has unabsorbed tax losses - content}}', nl2br($te_info[0]['text_content']), $content_template);
                }
                else
                {
                    $content_template = str_replace('{{Company has unabsorbed tax losses - content}}', '', $content_template);
                }
            }

            $temp_template .= $content_template;
        }
        else
        {
            $temp_template .= $content_template;
        }

        return $temp_template;
    } 

    public function retrieve_content($alias_value, $template, $fs_company_info_id)
    {
        $new_contents = $template;

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);
        $client_info = $this->fs_model->get_client_info_by_companycode($fs_company_info[0]["company_code"]);
        $firm_info   = $this->fs_model->get_firm_info_by_firmid($fs_company_info[0]["firm_id"]);

        $data = '';

        if($alias_value[0] == "client name")
        {   
            $get_company_name = $this->encryption->decrypt($client_info[0]['company_name']);
            $old_company_name = $fs_company_info[0]['old_company_name'];

            $wt_template = $this->get_part_of_template('<w:r>', 'w:r', $template);

            $wt_template = $this->get_part_of_template('<w:t>', 'w:t', $template);

            foreach ($wt_template[0] as $key => $value) 
            {
                $replace_with_string = '';

                if($key == 0)
                {
                    $replace_with_string = '<w:t>' . $get_company_name  . '</w:t>';

                    if(!empty($old_company_name))
                    {
                        $replace_with_string .= '<w:br/><w:t>(Formerly known as ' . $old_company_name . ')</w:t>';
                    }
                }

                // if($key == 1)
                // {
                //     if(!empty($old_company_name))
                //     {
                //         $replace_with_string = "<w:br>(Formerly known as " . $old_company_name . ")";
                //     }
                // }

                $new_contents = str_replace($value, $replace_with_string, $new_contents);
            }
        }
        elseif($alias_value[0] == "old client name")
        {
            $new_contents = str_replace($value, '', $new_contents);
        }
        elseif($alias_value[0] == "uen")
        {
            $new_contents = str_replace($value, '', $new_contents);
        }
        // else
        // {
        //     $data = 'ABC';
        // }

        return $new_contents;
    }

    public function replace_toggle($match, $new_contents, $document_name, $fs_company_info_id)
    {
        $settings = $this->get_settings($document_name);

        // echo json_encode($settings['font-size_company-name']);

        $pattern = "/{{[^}}]*}}/";
        $template = $new_contents;
        preg_match_all($pattern, $template, $string_to_replace);

        // echo json_encode($string_to_replace[0]);

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);
        // echo json_encode($fs_company_info);

        $client_info = $this->db->query("SELECT * FROM client WHERE company_code='" . $fs_company_info[0]["company_code"] . "'");
        $client_info = $client_info->result_array();

        $firm_info = $this->db->query("SELECT * FROM firm WHERE id='" . $fs_company_info[0]["firm_id"] . "'");
        $firm_info = $firm_info->result_array();

        if(count($string_to_replace[0]) != 0)
        {
            // echo json_encode($string_to_replace[0]);
            for($r = 0; $r < count($string_to_replace[0]); $r++)
            {
                $string1 = (str_replace('{{', '',$string_to_replace[0][$r]));
                $string2 = (str_replace('}}', '',$string1));

                if($string2 == "client name")
                {
                    $replace_string = $string_to_replace[0][$r];

                    $get_company_name = $this->encryption->decrypt($client_info[0]['company_name']);
                    $old_company_name = $fs_company_info[0]['old_company_name'];

                    // if($document_name == "Independent Auditors' Report")
                    // {
                        if(!empty($old_company_name))
                        {
                            $get_company_name_old = '<br/><span><em style="font-size:'. $settings['font-size_old-company-name'] .'pt;">(Formerly known as '. $old_company_name . ')</em>';

                            $temp_content = '<strong style="font-size:'. $settings['font-size_company-name'] .'pt;">' . $get_company_name . '</strong></span>' . $get_company_name_old;
                        }
                        else
                        {
                            $temp_content = '<strong style="font-size:'. $settings['font-size_company-name'] .'pt;">' . $get_company_name . '</strong>';
                        }
                    // }
                    // else
                    // {
                    //  if(!empty($old_company_name))
                    //  {
                    //      $get_company_name_old = '<br/><span><em style="font-size:'. $settings['font-size_old-company-name'] .'pt;">(Formerly known as '. $old_company_name . ')</em>';

                    //      $temp_content = '<strong style="font-size:14pt;">' . $get_company_name . '</strong></span>' . $get_company_name_old;

                    //      // echo $get_company_name_old;
                    //  }
                    //  else
                    //  {
                    //      $temp_content = '<strong style="font-size:14pt;">' . $get_company_name . '</strong>';
                    //  }
                    // }
                    

                    // $temp_content = $get_company_name;
                }
                elseif($string2 == "client name - current")
                {
                    $replace_string = $string_to_replace[0][$r];
                    $temp_content = $this->encryption->decrypt($client_info[0]['company_name']);
                }
                elseif($string2 == "date of resolution for change of name")
                {
                    $replace_string = $string_to_replace[0][$r];
                    $temp_content = $fs_company_info[0]['date_of_resolution_for_change_of_name'];
                }
                elseif($string2 == "client name - old")
                {
                    $replace_string = $string_to_replace[0][$r];
                    $temp_content = $fs_company_info[0]['old_company_name'];
                }
                elseif($string2 == "client - country")
                {
                    $replace_string = $string_to_replace[0][$r];
                    $temp_content = "Singapore";
                }
                elseif($string2 == "client - address")
                {
                    $replace_string = $string_to_replace[0][$r];

                    $address = array(
                        'type'             => "Local",
                        'street_name1'     => $client_info[0]["street_name"],
                        'unit_no1'         => $client_info[0]["unit_no1"],
                        'unit_no2'         => $client_info[0]["unit_no2"],
                        'building_name1'   => $client_info[0]["building_name"],
                        'postal_code1'     => $client_info[0]["postal_code"]
                    );

                    $temp_content = $this->write_address_local_foreign($address, "comma", "");
                }
                elseif($string2 == "firm name")
                {
                    $replace_string = $string_to_replace[0][$r];
                    $temp_content = $firm_info[0]['name'];
                }
                elseif($string2 == "uen")
                {
                    $replace_string = $string_to_replace[0][$r];

                    $uen = $this->encryption->decrypt($client_info[0]['registration_no']);

                    if($document_name == "Front page")
                    {
                        $temp_content = "(UEN: " . $uen . ")";

                        $group_type = $fs_company_info[0]['group_type'];

                        if($group_type == 1)    // No group
                        {
                            $temp_content .= "";
                        }
                        elseif($group_type == 2)    // 1 subsidiary only
                        {
                            $temp_content .= "<br/><br/><strong>AND ITS SUBSIDIARY</strong>";
                        }
                        elseif($group_type == 3)    // more than 1 subsidiary
                        {
                            $temp_content .= "<br/><br/><strong>AND ITS SUBSIDIARIES</strong>";
                        }
                    }
                    else 
                    {   
                        $temp_content = $uen;
                    }
                }
                elseif($string2 == "audited")
                {
                    $replace_string = $string_to_replace[0][$r];

                    if($fs_company_info[0]['is_audited'])
                    {
                        $temp_content = "audited";
                    }   
                    else
                    {
                        $temp_content = "";
                    }
                }
                elseif($string2 == "Current Year End - Beginning")
                {
                    $replace_string = $string_to_replace[0][$r];
                    $temp_content = $fs_company_info[0]['current_fye_begin'];
                }
                elseif($string2 == "Current Year End - Ending")
                {
                    $replace_string = $string_to_replace[0][$r];
                    $temp_content = $fs_company_info[0]['current_fye_end'];
                }
                elseif($string2 == "Last Year End - Ending")
                {
                    $replace_string = $string_to_replace[0][$r];
                    $temp_content = $fs_company_info[0]['last_fye_end'];
                }
                elseif($string2 == "Statement Year End" || $string2 == "Statement Last Year End - End" || $string2 == "Statement Last Year End - Beginning" || $string2 == "Statement Year End NTA - LATEST" || $string2 == "Statement Year End NTA - EARLIER")
                {
                    $current_last_year_end = $this->fs_model->calculate_difference_dates($fs_company_info_id, "General");

                    $last_fye_end    = $current_last_year_end['last_fye_end'];
                    $current_fye_end = $current_last_year_end['current_fye_end'];
                    $last_year_end_begining = '';

                    if($document_name == "Statement of financial position")
                    {
                        if($fs_company_info[0]['effect_of_restatement_since'] == $fs_company_info[0]['last_fye_begin'])
                        {
                            $this->data['show_third_col']   = true;

                            $current_fye_end        = date('d.m.Y', strtotime($fs_company_info[0]['current_fye_end']));
                            $last_fye_end           = date('d.m.Y', strtotime($fs_company_info[0]['last_fye_end']));
                            $last_year_end_begining = date('d.m.Y', strtotime($fs_company_info[0]['last_fye_begin']));
                        }
                    }
                    

                    $replace_string = $string_to_replace[0][$r];

                    if($string2 == "Statement Year End")
                    {
                        $temp_content = $current_fye_end;
                    }
                    elseif($string2 == "Statement Last Year End - End")
                    {
                        $temp_content = $last_fye_end;
                    }
                    elseif($string2 == "Statement Last Year End - Beginning")
                    {
                        $temp_content = $last_year_end_begining;
                    }

                    if($string2 == "Statement Year End NTA - LATEST" || $string2 == "Statement Year End NTA - EARLIER") // for NTA Note - INVESTMENT IN SUBSIDIARIES
                    {
                        $display_date_1 = '';
                        $display_date_2 = '';

                        $year_current_fye_end = date('Y', strtotime($fs_company_info[0]['current_fye_end']));
                        $year_last_fye_end    = date('Y', strtotime($fs_company_info[0]['last_fye_end']));

                        if($year_current_fye_end == $year_last_fye_end || empty($fs_company_info[0]['last_fye_end']) || $fs_company_info[0]['first_set'])
                        {
                            $display_date_1 = date('d.m.Y', strtotime($fs_company_info[0]['current_fye_end']));
                            $display_date_2 = date('d.m.Y', strtotime($fs_company_info[0]['current_fye_begin']));
                        }
                        else
                        {
                            $display_date_1 = $year_current_fye_end;
                            $display_date_2 = $year_last_fye_end;
                        }

                        if($string2 == "Statement Year End NTA - LATEST")
                        {
                            $temp_content = $display_date_1;
                        }
                        elseif($string2 == "Statement Year End NTA - EARLIER")
                        {
                            $temp_content = $display_date_2;
                        }
                    }
                }
                elseif($string2 == "title, directors signatures")
                {
                    $directors = array();

                    if($fs_company_info[0]['director_signature_1'] != "")
                    {
                        // array_push($directors, array('director_name' => $this->fs_model->get_directors($fs_company_info[0]['director_signature_1'])[0]->name));
                        array_push($directors, array('director_name' => $fs_company_info[0]['director_signature_1']));
                    }

                    if($fs_company_info[0]['director_signature_2'] != "")
                    {
                        // array_push($directors, array('director_name' => $this->fs_model->get_directors($fs_company_info[0]['director_signature_2'])[0]->name));
                        array_push($directors, array('director_name' => $fs_company_info[0]['director_signature_2']));
                    }

                    $replace_string = $string_to_replace[0][$r];
                    
                    if(count($directors) == 1)
                    {
                        $temp_content = '<span>Sole director</span><br/>';
                    }
                    elseif(count($directors) == 2)
                    {
                        $temp_content = '<span>The Board of Directors</span><br/>';
                    }
                    elseif(count($directors) > 2)
                    {
                        $temp_content = '<span>On behalf of the Board of Directors</span><br/>';
                    }

                    $sign_board_template_start = '<table style="width: 100%; border-collapse: collapse;" border="0"><tbody>';
                    $sign_board_template_end   = '</tbody></table>';
                    $signature_board_template  = '';

                    foreach($directors as $key=>$director)
                    {
                        if(!(((int)$key) % 2))
                        {
                            $signature_board_template .= '<tr><td style="width: 50%;"><br /><br /><br /><br /><br /><br />_____________________________<br />'. $director["director_name"] .'</td>';
                        }
                        else
                        {
                            $signature_board_template .= '<td style="width: 50%;"><br /><br /><br /><br /><br /><br />_____________________________<br />'. $director["director_name"] .'</td></tr>';
                        }

                        if($key == count($directors)-1 && !(((int)$key) % 2))
                        {
                            $signature_board_template .= '</tr>'; 
                        }
                    }

                    $sign_board_complete = $sign_board_template_start . $signature_board_template . $sign_board_template_end;

                    $temp_content = $temp_content . $sign_board_complete;
                }
                elseif($string2 == "Report's Date")
                {
                    $replace_string = $string_to_replace[0][$r];
                    $temp_content = $fs_company_info[0]['report_date'];
                }
                elseif($string2 == "Act Applicable")
                {
                    $replace_string = $string_to_replace[0][$r];
                    $temp_content = $fs_company_info[0]['act_applicable_type'];
                }
                elseif($string2 == "Accounting Standard used")
                {
                    $replace_string = $string_to_replace[0][$r];
                    $temp_content = $fs_company_info[0]['accounting_standard_used'];
                }

                // WIDTH of td from table
                elseif($string2 == "width_description")
                {
                    $replace_string = $string_to_replace[0][$r];
                    $temp_content = $settings['width_description'];
                }
                elseif($string2 == "width_merge_2_td")
                {
                    $replace_string = $string_to_replace[0][$r];
                    $temp_content = $settings['width_merge_2_td'];
                }
                elseif($string2 == "width_td")
                {
                    $replace_string = $string_to_replace[0][$r];
                    $temp_content = $settings['width_td'];
                }

                $new_contents = str_replace($replace_string, $temp_content, $new_contents);
            }

            return $new_contents;
        }
        else 
        {
            return $new_contents;
        }
    }

    public function replace_verbs_plural($new_contents, $document_name, $fs_company_info_id)
    {
        $pattern = "/{_[^}}]*_}/";
        preg_match_all($pattern, $new_contents, $match);

        // echo json_encode($match);

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $client_info = $this->db->query("SELECT * FROM client WHERE company_code='" . $fs_company_info[0]["company_code"] . "'");
        $client_info = $client_info->result_array();

        $directors = $this->fs_model->get_fs_dir_statement_director($fs_company_info_id);

        $isPlural = count($directors) > 1? true: false;

        if(count($match[0]) != 0)
        {
            for($r = 0; $r < count($match[0]); $r++)
            {
                $string1 = (str_replace('{_', '',$match[0][$r]));
                $string2 = (str_replace('_}', '',$string1));

                $content = '';

                if($string2 == "Group/Company")
                {
                    // echo json_encode("GROUP ? COMPANY");
                    $replace_string = $match[0][$r];

                    if($fs_company_info[0]['group_type'] != 1)
                    {
                        $content = 'Group and the Company';
                    }
                    else 
                    {
                        $content = 'Company';
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

            return $new_contents;
        }
        else 
        {
            return $new_contents;
        }
    }

    public function get_settings($document_name)
    {
        $settings = array(
            'font-size_old-company-name' => 11,
            'font-size_company-name' => 14
        );

        if($document_name == "Independent Auditors' Report")
        {
            $settings['font-size_company-name'] = 11;
        }
        elseif($document_name == "Schedule of operating expenses" || $document_name == "Statement of detailed profit or loss" || $document_name == "Statement of comprehensive income")
        {
            $settings['font-size_company-name'] = 11;
        }

        return $settings;
    }

    public function negative_bracket($number)
    {
        if($number == 0 || $number == "" || empty($number))
        {
            return "-";
        }
        elseif($number < 0)
        {
            return "(" . number_format(abs($number)) . ")";
        }
        else
        {
            return number_format($number);
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
            $br1 = ' <br/>';
            $br2 = ' <br/>';
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
                $unit_building_name = $unit . ' ' . $address['building_name1'];
            }
            elseif(!empty($unit))
            {
                $unit_building_name = $unit;
            }
            elseif(!empty($address['building_name1']))
            {
                $unit_building_name = $address['building_name1'];
            }

            if($style == "big_cap")
            {
                $sg_word = ' SINGAPORE ';
            }
            else
            {
                $sg_word = ' Singapore ';
            }

            return $address['street_name1'] . $br1 . $unit_building_name . $br2 . $sg_word . $address['postal_code1'];
        }
        else if($address['type'] == "Foreign")
        {
            $foreign_address1 = !empty($address["foreign_address1"])? $address["foreign_address1"]: '';

            if(!empty($address["foreign_address1"]))
            {
                if(substr($address["foreign_address1"], -1) == ",")
                {
                    $foreign_address1 = rtrim($address["foreign_address1"],',');    // remove , if there is any at last character
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
                    $foreign_address2 = $br1 . rtrim($address["foreign_address2"],',');     // remove , if there is any at last character
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
    
    public function write_list_number($content)
    {
        // echo $content;
        $temp = '';
        $pattern = "/{{[^}}]*}}/";
        $subject = $content;
        preg_match_all($pattern, $subject, $matches_no);
        $counter = 0;
        $sub_counter = 1;
        $roman_counter = 1;

        for($c = 0; $c < count($matches_no[0]); $c++)
        {
            $string1 = (str_replace('{{', '',$matches_no[0][$c]));
            $string2 = (str_replace('}}', '',$string1));

            $replace_string = $matches_no[0][$c];

            // echo $replace_string;
            if($string2 == "no") // eg. no.
            {   
                $counter++;

                $temp = $counter;
                $content = preg_replace('/{{no}}/', $temp, $content, 1);

                $sub_counter = 1;   // reset sub_counter from 0
            }

            if($string2 == "sub no")
            {
                $temp = $counter . '.' . $sub_counter;
                $content = preg_replace('/{{sub no}}/', $temp, $content, 1);

                $sub_counter++;
                $roman_counter = 1;
            }

            if($string2 == "roman no")
            {
                $temp = strtolower($this->integerToRoman($roman_counter));
                $content = preg_replace('/{{roman no}}/', $temp, $content, 1);

                $roman_counter++;
            }
        }

        return $content;
    }

    

    

    public function replace_table_content_by_tr($tr_template, $data)
    {           
        // For account name tr
        if(strpos($tr_template, '{{Account name}}') !== false)
        {
            $tr_template = str_replace('{{Account name}}', $data['description'], $tr_template);
        }
        
        if(strpos($tr_template, '{{Current Year End Value - Group}}') !== false)
        {
            $tr_template = str_replace('{{Current Year End Value - Group}}', $this->negative_bracket($data['group_end_this_ye_value']), $tr_template);
        }

        if(strpos($tr_template, '{{Last Year End Value - Group}}') !== false)
        {
            $tr_template = str_replace('{{Last Year End Value - Group}}', $this->negative_bracket($data['group_end_prev_ye_value']), $tr_template);
        }

        if(strpos($tr_template, '{{Current Year End Value - Company}}') !== false)
        {
            $tr_template = str_replace('{{Current Year End Value - Company}}', $this->negative_bracket($data['value']), $tr_template);
        }

        if(strpos($tr_template, '{{Last Year End Value - Company}}') !== false)
        {
            $tr_template = str_replace('{{Last Year End Value - Company}}', $this->negative_bracket($data['company_end_prev_ye_value']), $tr_template);
        }

        return $tr_template;
    }

    public function replace_width_td($template, $width_tds)
    {
        if(strpos($template, '{{width_description}}') !== false)
        {
            $template = str_replace('{{width_description}}', $width_tds['width_description'], $template);
        }

        if(strpos($template, '{{width_merge_2_td}}') !== false)
        {
            $template = str_replace('{{width_merge_2_td}}', $width_tds['width_merge_2_td'], $template);
        }

        if(strpos($template, '{{width_td}}') !== false)
        {
            $template = str_replace('{{width_td}}', $width_tds['width_td'], $template);
        }

        return $template;
    }

    public function replace_totals($template, $data)
    {
        if(strpos($template, '{{Total for group current year}}') !== false)
        {
            $template = str_replace('{{Total for group current year}}', $this->negative_bracket($data['total_cye_group']), $template);
        }

        if(strpos($template, '{{Total for group last year}}') !== false)
        {
            $template = str_replace('{{Total for group last year}}', $this->negative_bracket($data['total_lye_group']), $template);
        }

        if(strpos($template, '{{Total for company current year}}') !== false)
        {
            $template = str_replace('{{Total for company current year}}', $this->negative_bracket($data['total_cye_company']), $template);
        }

        if(strpos($template, '{{Total for company last year}}') !== false)
        {
            $template = str_replace('{{Total for company last year}}', $this->negative_bracket($data['total_lye_company']), $template);
        }

        return $template;
    }

    public function replace_special_sign($match, $new_contents)
    {
        $pattern = "/{{[^}}]*}}/";
        $template = $new_contents;
        preg_match_all($pattern, $template, $string_to_replace);

        if(count($string_to_replace[0]) != 0)
        {
            // echo json_encode($string_to_replace[0]);
            for($r = 0; $r < count($string_to_replace[0]); $r++)
            {
                $string1 = (str_replace('{{', '',$string_to_replace[0][$r]));
                $string2 = (str_replace('}}', '',$string1));

                if($string2 == "dollar sign")
                {
                    $replace_string = $string_to_replace[0][$r];
                    $temp_content = '$';
                }
                elseif($string2 == "open bracket")
                {
                    $replace_string = $string_to_replace[0][$r];
                    $temp_content = '(';
                }
                elseif($string2 == "close bracket")
                {
                    $replace_string = $string_to_replace[0][$r];
                    $temp_content = ')';
                }

                $new_contents = str_replace($replace_string, $temp_content, $new_contents);
            }
        }

        return $new_contents;
    }

    public function integerToRoman($integer)    // Convert number to roman numbering eg. I, II, III and so on
    {
         // Convert the integer into an integer (just to make sure)
         $integer = intval($integer);
         $result = '';
         
         // Create a lookup array that contains all of the Roman numerals.
         $lookup = array('M' => 1000,
         'CM' => 900,
         'D' => 500,
         'CD' => 400,
         'C' => 100,
         'XC' => 90,
         'L' => 50,
         'XL' => 40,
         'X' => 10,
         'IX' => 9,
         'V' => 5,
         'IV' => 4,
         'I' => 1);
         
         foreach($lookup as $roman => $value){
          // Determine the number of matches
          $matches = intval($integer/$value);
         
          // Add the same number of characters to the string
          $result .= str_repeat($roman,$matches);
         
          // Set the integer to be the remainder of the integer and the value
          $integer = $integer % $value;
         }
         
         // The Roman numeral should be built, return it
         return $result;
    }

    public function rand_string( $length ) {  
        $str   = '';
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz@#$&*";  
        $size  = strlen( $chars );  

        // echo "Random string =";  
        for( $i = 0; $i < $length; $i++ ) {  
            $str .= $chars[ rand( 0, $size - 1 ) ];  
            // echo $str;  
        } 

        return $str; 
    } 

    public function rand_string_without_special_char( $length ) {  
        $str   = '';
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";  
        $size  = strlen( $chars );  

        // echo "Random string =";  
        for( $i = 0; $i < $length; $i++ ) {  
            $str .= $chars[ rand( 0, $size - 1 ) ];  
            // echo $str;  
        } 

        return $str; 
    }

    public function rand_num( $length ) {  
        $str   = '';
        $chars = "0123456789";  
        $size  = strlen( $chars );  

        // echo "Random string =";  
        for( $i = 0; $i < $length; $i++ ) {  
            $str .= $chars[ rand( 0, $size - 1 ) ];  
            // echo $str;  
        } 

        return $str; 
    } 

    public function get_part_of_template($related_part, $tagType, $template)
    {
        if(strpos($template, $related_part) !== false)
        {
            // preg_match_all ('/' . $related_part . '(.+?)<\/' . $tagType . '>/s', $template, $taken_template);
            preg_match_all ('/' . $related_part . '(.+?)<\/' . $tagType . '>/s', $template, $taken_template);

            return $taken_template;
        }
        else
        {
            return '';
        }
    }
}
