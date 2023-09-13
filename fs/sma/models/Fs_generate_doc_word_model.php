<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fs_generate_doc_word_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session', 'encryption'));
        $this->load->model('fs_notes_model');
        $this->load->model('fs_model');
        $this->load->model('fs_replace_content_model');
        $this->load->model('fs_account_category_model');

        ini_set('max_execution_time', 0); 
        ini_set('memory_limit','2048M');
    }

    public function get_fs_list_word_header_link_relationship_id()
    {
        $data = $this->db->query("SELECT * FROM fs_list_word_header_link_relationship_id");
        $data = $data->result_array();

        return $data;
    }

    public function getFS_report_list(){
        $url         = 'assets/json/fs_column_settings.json'; // path to your JSON file
        $data        = file_get_contents($url); // put the contents of the file into a variable
        $data_decode = json_decode($data); // decode the JSON feed

        return $data_decode[0];
    }

    public function get_fs_note_title_json()
    {
        $url         = 'assets/json/fs_note_title.json'; // path to your JSON file
        $data        = file_get_contents($url); // put the contents of the file into a variable
        $data_decode = json_decode($data); // decode the JSON feed

        return $data_decode[0];
    }

    public function get_fs_tables_info_json()
    {
        $url         = 'assets/json/fs_tables_info.json'; // path to your JSON file
        $data        = file_get_contents($url); // put the contents of the file into a variable
        $data_decode = json_decode($data); // decode the JSON feed

        return $data_decode[0];
    }

    public function remove_hidden_tags_part_or_insert_vanish_tag($replaced_wsdt_xml, $generate_docs_without_tags, $hide_needed)
    {
        if($generate_docs_without_tags) // remove hidden items (remove tag part)
        {
            $replaced_wsdt_xml = ''; 
        }
        else
        {
            $replaced_wsdt_xml = $this->vanish_template($replaced_wsdt_xml, 1); 
        }

        return $replaced_wsdt_xml;
    }

    public function remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name)
    {
        if($additional_info['generate_docs_without_tags'])
        {
            $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_value);   // get tr

            foreach ($tbl_tr[0] as $tbl_tr_key => $tbl_tr_value) 
            {
                $tr_name_type = $this->get_tr_name_type($tbl_tr_value);  // to extract the first column name so that we know it is title or account name and value insert template.

                if($tr_name_type == $table_name) 
                {
                    $replaced_tbl_value = str_replace($tbl_tr_value, '', $replaced_tbl_value);
                    break;
                }
            }
        }

        return $replaced_tbl_value;
    }

    public function remove_wsdt_tag($replaced_wsdt_xml, $additional_info)
    {
        if($additional_info['generate_docs_without_tags']) 
        {
            // inner part (remove wsdt from inner part)
            $inner_wsdt = $this->get_part_of_template_include_nested('w:sdtContent', $replaced_wsdt_xml);

            if(isset($inner_wsdt[0][0])) // take outer w:sdtContent to check inner wsdt
            {
                preg_match_all('/<w:sdt>.*?<\/w:sdt>/', $inner_wsdt[0][0], $target_half_wsdt_tag_inner);

                foreach ($target_half_wsdt_tag_inner[0] as $key => $value) 
                {
                    preg_match_all('/<w:vanish\/>/', $value, $vanish_list);

                    if(isset($vanish_list[0]) && count($vanish_list[0]) > 0)
                    {
                        $replaced_wsdt_xml = str_replace($value, '', $replaced_wsdt_xml);
                    }
                }
            }

            /* Remove wsdt tag for all */



            preg_match_all('/<w:vanish\/>/', $replaced_wsdt_xml, $vanish_list_main);

            // print_r($vanish_list_main);

            if(count($vanish_list_main[0]) > 0)  // Remove the tag for cases like sing/plu s
            {
                $replaced_wsdt_xml = '';
            }
            else
            {
                preg_match_all('/<w:sdt>.*?<w:sdtContent>/', $replaced_wsdt_xml, $target_half_wsdt_tag);

                if(isset($target_half_wsdt_tag[0]))
                {
                    foreach ($target_half_wsdt_tag[0] as $key => $value) 
                    {
                        $replaced_wsdt_xml = str_replace($target_half_wsdt_tag[0], '', $replaced_wsdt_xml);
                    }
                }
            }

            $replaced_wsdt_xml = str_replace('</w:sdtContent></w:sdt>', '', $replaced_wsdt_xml); 
        }

        // print_r(array($replaced_wsdt_xml));

        return $replaced_wsdt_xml;
    }

    public function get_header_value_col_template($table_name, $fs_company_info_id)
    {
        $data = [];

        if($table_name == "Note 6 - Investment in subsidiaries (ii) (table_1)")
        {
            $iis_p2_t1_titles_data = $this->db->query("SELECT * FROM fs_investment_in_subsidiaries_ntfs_p2_1_header WHERE fs_company_info_id=" . $fs_company_info_id);
            $iis_p2_t1_titles_data = $iis_p2_t1_titles_data->result_array();

            if(!empty($iis_p2_t1_titles_data[0]['header_titles']))
            {
                $iis_p2_t1_titles = explode(',', $iis_p2_t1_titles_data[0]['header_titles']);
            }

            $data = $iis_p2_t1_titles;
        }
        elseif($table_name == "Note 9 - Intangible assets (table_1)")
        {
            $ia_titles_data = $this->db->query("SELECT * FROM fs_intangible_assets_ntfs_1_header WHERE fs_company_info_id=" . $fs_company_info_id);
            $ia_titles_data = $ia_titles_data->result_array();

            if(!empty($ia_titles_data[0]['header_titles']))
            {
                $ia_t1_titles = explode(',', $ia_titles_data[0]['header_titles']);
            }
            else
            {
                $ia_t1_titles = array('', '');
            }

            $data = $ia_t1_titles;
        }
        elseif($table_name == "Note 11 - Investment properties cost_model (table_1)")
        {
            $ip_t1_titles_data = $this->db->query("SELECT * FROM fs_investment_properties_ntfs_1_header WHERE fs_company_info_id=" . $fs_company_info_id);
            $ip_t1_titles_data = $ip_t1_titles_data->result_array();

            if(!empty($ip_t1_titles_data[0]['header_titles']))
            {
                $ip_t1_titles = explode(',', $ip_t1_titles_data[0]['header_titles']);
            }

            $data = $ip_t1_titles;
        }
        elseif($table_name == "Note 11 - Investment properties (table_3)")
        {
            // for table 3
            $ip_t3_titles_data = $this->db->query("SELECT * FROM fs_investment_properties_ntfs_3_header WHERE fs_company_info_id=" . $fs_company_info_id);
            $ip_t3_titles_data = $ip_t3_titles_data->result_array();

            if(!empty($ip_t3_titles_data[0]['header_titles']))
            {
                $ip_t3_titles = explode(',', $ip_t3_titles_data[0]['header_titles']);
            }

            $data = $ip_t3_titles;
        }
        elseif($table_name == "Note 12 - Property, plant and equipment (table_1)")
        {
            $ppe_t1_titles_data = $this->db->query("SELECT * FROM fs_ppe_ntfs_1_header WHERE fs_company_info_id=" . $fs_company_info_id);
            $ppe_t1_titles_data = $ppe_t1_titles_data->result_array();

            if(!empty($ppe_t1_titles_data[0]['header_titles']))
            {
                $ppe_t1_titles = explode(',', $ppe_t1_titles_data[0]['header_titles']);
            }

            $data = $ppe_t1_titles;
        }
        elseif($table_name == "Note 29.4 - Financial Risk Management (table_1) (group)")
        {
            $frm_s4_t1_titles_data = $this->db->query("SELECT * FROM fs_financial_risk_management_s4_t1_header WHERE fs_company_info_id=" . $fs_company_info_id);
            $frm_s4_t1_titles_data = $frm_s4_t1_titles_data->result_array();

            if(!empty($frm_s4_t1_titles_data[0]['header_titles']))
            {
                $frm_s4_t1_titles = explode(',', $frm_s4_t1_titles_data[0]['header_titles']);
            }

            $data = $frm_s4_t1_titles;
        }
        elseif($table_name == "Note 29.4 - Financial Risk Management (table_1) (company)")
        {
            $frm_s4_t2_titles_data = $this->db->query("SELECT * FROM fs_financial_risk_management_s4_t2_header WHERE fs_company_info_id=" . $fs_company_info_id);
            $frm_s4_t2_titles_data = $frm_s4_t2_titles_data->result_array();

            if(!empty($frm_s4_t2_titles_data[0]['header_titles']))
            {
                $frm_s4_t2_titles = explode(',', $frm_s4_t2_titles_data[0]['header_titles']);
            }

            $data = $frm_s4_t2_titles;
        }

        return $data;
    }

    public function get_textboxes_ntfs_values($alias_value, $fs_company_info_id)
    {
        $final_report_type = $this->fs_model->get_final_document_type($fs_company_info_id);

        $data = [];

        if($alias_value == "Note 3 - Employee benefits expense (content)")
        {
            $data = $this->fs_notes_model->get_employee_benefits_expense_ntfs($fs_company_info_id);
        }
        elseif($alias_value == "Note 5 - Tax expense (textarea 1)")
        {
            $data = $this->fs_notes_model->get_fs_tax_expense_ntfs_info($fs_company_info_id, 1);
        }
        elseif($alias_value == "Note 5 - Tax expense (content - company has unabsorbed tax losses)")
        {
            $data = $this->fs_notes_model->get_fs_tax_expense_ntfs_info($fs_company_info_id, 2);
        }
        elseif($alias_value == "Note 6 - Investment in subsidiaries (i) (content)")
        {
            $data = $this->db->query("SELECT * FROM fs_investment_in_subsidiaries_ntfs_p1_info WHERE fs_company_info_id=" . $fs_company_info_id);
            $data = $data->result_array();
        }
        elseif($alias_value == "Note 7 - Investment in associates (1)")
        {
            $data = $this->db->query("SELECT * FROM fs_investment_in_associates_info WHERE fs_company_info_id =" . $fs_company_info_id);
            $data = $data->result_array();
        }
        elseif($alias_value == "Note 8 - Investment in joint venture (1)")
        {
            $data = $this->db->query("SELECT * FROM fs_investment_in_joint_venture_info WHERE fs_company_info_id =" . $fs_company_info_id);
            $data = $data->result_array();
        }
        elseif ($alias_value == "Note 9 - Intangible assets (textarea)") 
        {
            $data = $this->db->query("SELECT * FROM fs_intangible_assets_info WHERE fs_company_info_id=" . $fs_company_info_id);
            $data = $data->result_array();
        }
        elseif ($alias_value == "Note 9 - Intangible assets (1)") 
        {
            $data = $this->db->query("SELECT ia2.*, iad.section_name
                                        FROM fs_intangible_assets_info_2 ia2 
                                        LEFT JOIN fs_list_intangible_assets_content iad ON iad.id = ia2.fs_list_intangible_assets_content_id
                                        WHERE ia2.fs_company_info_id=" . $fs_company_info_id . " AND iad.section_name != '' ORDER BY iad.order_by");
            $data = $data->result_array();
        }
        elseif($alias_value == "Note 10 - Insured benefits (1)")
        {
            $data = $this->fs_notes_model->get_fs_insured_benefits_info($fs_company_info_id);
        }
        elseif($alias_value == "Note 11 - Investment properties (3)")
        {
            $data = [];

            if($final_report_type == 1)
            {
                $data = $this->db->query("SELECT p.*, pd.section_name 
                                        FROM fs_investment_properties_info p 
                                        LEFT JOIN fs_list_investment_properties_content pd ON pd.id = p.fs_list_investment_properties_content_id
                                        WHERE p.fs_company_info_id=" . $fs_company_info_id . " AND pd.fs_list_final_report_type_id=1 AND p.is_checked != 0 ORDER BY pd.order_by");
                $data = $data->result_array();
            }
        }
        elseif($alias_value == "Note 12 - Property, plant and equipment - 1")
        {
            $data = $this->db->query("SELECT * FROM fs_ppe_info WHERE fs_company_info_id=" . $fs_company_info_id . " AND fs_list_ppe_content_id = 5");
            $data = $data->result_array();
        }
        elseif($alias_value == "Note 12 - Property, plant and equipment (1)")
        {
            if($final_report_type == 1)
            {
                $data = $this->db->query("SELECT p.*, pd.section_name 
                                        FROM fs_ppe_info p 
                                        LEFT JOIN fs_list_ppe_content pd ON pd.id = p.fs_list_ppe_content_id
                                        WHERE p.fs_company_info_id=" . $fs_company_info_id . " AND p.fs_list_ppe_content_id != 5 AND (pd.fs_list_final_report_type_id=4 OR pd.fs_list_final_report_type_id=1) AND p.is_checked != 0 ORDER BY pd.order_by");
                $data = $data->result_array();
            }
            else
            {
                $data = $this->db->query("SELECT p.*, pd.section_name 
                                        FROM fs_ppe_info p 
                                        LEFT JOIN fs_list_ppe_content pd ON pd.id = p.fs_list_ppe_content_id
                                        WHERE p.fs_company_info_id=" . $fs_company_info_id . " AND p.fs_list_ppe_content_id != 5 AND (pd.fs_list_final_report_type_id=4 OR pd.fs_list_final_report_type_id=5) AND p.is_checked != 0 ORDER BY pd.order_by");
                $data = $data->result_array();
            }
        }
        elseif($alias_value == "Note 13 - Available for sale (1)")
        {
            $data = $this->db->query("SELECT * FROM fs_available_for_sale_info WHERE fs_company_info_id = " . $fs_company_info_id);
            $data = $data->result_array();
        }
        elseif($alias_value == "Note 14 - Inventories (1)")
        {
            $data = $this->db->query("SELECT * FROM fs_inventories_info WHERE fs_company_info_id=" . $fs_company_info_id);
            $data = $data->result_array();
        }
        elseif($alias_value == "Note 15 - Contract assets and contract liabilities (2)")
        {
            $data = $this->db->query("SELECT * FROM fs_contract_assets_and_contract_liabilities_info WHERE fs_company_info_id = " . $fs_company_info_id);
            $data = $data->result_array();
        }
        elseif($alias_value == "Note 16 - Trade and other receivables (1)")
        {
            $data = $this->db->query("SELECT lb.*, lbt.id AS `fs_list_trade_and_other_receivables_title_id`, lbt.section_name, lbd.is_fixed
                                        FROM fs_trade_and_other_receivables_info lb
                                        LEFT JOIN fs_list_trade_and_other_receivables_content lbd ON lbd.id = lb.fs_list_trade_and_other_receivables_content_id
                                        LEFT JOIN fs_list_trade_and_other_receivables_title lbt ON lbt.id = lbd.fs_list_trade_and_other_receivables_title_id
                                        WHERE lb.fs_company_info_id=" . $fs_company_info_id . " AND lbt.id IN (1,2) AND lb.is_checked = 1 ORDER BY lb.order_by");
            $data = $data->result_array();
        }
        elseif($alias_value == "Note 16 - Trade and other receivables (6)")
        {
            $data = $this->db->query("SELECT lb.*, lbt.id AS `fs_list_trade_and_other_receivables_title_id`, lbt.section_name, lbd.is_fixed
                                        FROM fs_trade_and_other_receivables_info lb
                                        LEFT JOIN fs_list_trade_and_other_receivables_content lbd ON lbd.id = lb.fs_list_trade_and_other_receivables_content_id
                                        LEFT JOIN fs_list_trade_and_other_receivables_title lbt ON lbt.id = lbd.fs_list_trade_and_other_receivables_title_id
                                        WHERE lb.fs_company_info_id=" . $fs_company_info_id . " AND lbt.id = 3 AND lb.is_checked = 1 ORDER BY lb.order_by");
            $data = $data->result_array();
        }
        elseif($alias_value == "Note 16 - Trade and other receivables (3)")
        {
            $data = $this->db->query("SELECT lb.*, lbt.id AS `fs_list_trade_and_other_receivables_title_id`, lbt.section_name, lbd.is_fixed
                                        FROM fs_trade_and_other_receivables_info lb
                                        LEFT JOIN fs_list_trade_and_other_receivables_content lbd ON lbd.id = lb.fs_list_trade_and_other_receivables_content_id
                                        LEFT JOIN fs_list_trade_and_other_receivables_title lbt ON lbt.id = lbd.fs_list_trade_and_other_receivables_title_id
                                        WHERE lb.fs_company_info_id=" . $fs_company_info_id . " AND lbt.id = 4 AND lb.is_checked = 1 ORDER BY lb.order_by");
            $data = $data->result_array();
        }
        elseif($alias_value == "Note 16 - Trade and other receivables (4)")
        {
            $data = $this->db->query("SELECT lb.*, lbt.id AS `fs_list_trade_and_other_receivables_title_id`, lbt.section_name, lbd.is_fixed
                                        FROM fs_trade_and_other_receivables_info lb
                                        LEFT JOIN fs_list_trade_and_other_receivables_content lbd ON lbd.id = lb.fs_list_trade_and_other_receivables_content_id
                                        LEFT JOIN fs_list_trade_and_other_receivables_title lbt ON lbt.id = lbd.fs_list_trade_and_other_receivables_title_id
                                        WHERE lb.fs_company_info_id=" . $fs_company_info_id . " AND lbt.id = 5 AND lb.is_checked = 1 ORDER BY lb.order_by");
            $data = $data->result_array();
        }
        elseif($alias_value == "Note 18 - Cash and short-term deposits (1)")
        {
            $data = $this->db->query("SELECT info.*
                                            FROM fs_cash_short_term_deposits_info info
                                            INNER JOIN 
                                            (SELECT fs_company_info_id, MAX(created_at) AS max_date
                                            FROM fs_cash_short_term_deposits_info
                                            WHERE fs_company_info_id = " . $fs_company_info_id . ") info1
                                            ON info1.fs_company_info_id = info.fs_company_info_id AND info1.max_date = info.created_at
                                            WHERE info.fs_company_info_id = " . $fs_company_info_id);
            $data = $data->result_array();
        }
        elseif($alias_value == "Note 19 - Share capital (1)")
        {
            $data = $this->fs_notes_model->get_fs_share_capital_info($fs_company_info_id);
        }
        elseif($alias_value == "Note 22 - Loans and borrowings (2)")
        {
            $data = $this->db->query("SELECT lb.*, lbd.is_fixed
                                        FROM fs_loans_and_borrowings_info lb
                                        LEFT JOIN fs_list_loans_and_borrowings lbd ON lbd.id = lb.fs_list_loans_and_borrowings_id
                                        WHERE lb.fs_company_info_id=" . $fs_company_info_id . " AND lbd.fs_list_loans_and_borrowings_title_id = 0 ORDER BY lb.order_by");
            $data = $data->result_array(); 
        }
        elseif($alias_value == "Note 22 - Loans and borrowings (3)")
        {
            // $data = $this->db->query("SELECT lb.*, lbt.id AS `fs_list_loans_and_borrowings_title_id`, lbt.section_name, lbd.is_fixed, lbd.content AS `fixed_content`
            //                             FROM fs_loans_and_borrowings_info lb
            //                             INNER JOIN (
            //                             SELECT max(created_at) as `MaxDate`  FROM fs_loans_and_borrowings_info WHERE fs_company_info_id = " . $fs_company_info_id . " ORDER BY created_at
            //                             ) lb2 ON lb.created_at = lb2.MaxDate
            //                             LEFT JOIN fs_list_loans_and_borrowings lbd ON lbd.id = lb.fs_list_loans_and_borrowings_id
            //                             LEFT JOIN fs_list_loans_and_borrowings_title lbt ON lbt.id = lbd.fs_list_loans_and_borrowings_title_id
            //                             WHERE lb.fs_company_info_id=" . $fs_company_info_id . ' AND lb.is_checked=1 AND lbd.fs_list_loans_and_borrowings_title_id <> 0 ORDER BY lb.order_by'); 

            $data = $this->db->query("SELECT lb.*, lbt.id AS `fs_list_loans_and_borrowings_title_id`, lbt.section_name, lbd.is_fixed, lbd.content AS `fixed_content`
                                        FROM fs_loans_and_borrowings_info lb
                                        LEFT JOIN fs_list_loans_and_borrowings lbd ON lbd.id = lb.fs_list_loans_and_borrowings_id
                                        LEFT JOIN fs_list_loans_and_borrowings_title lbt ON lbt.id = lbd.fs_list_loans_and_borrowings_title_id
                                        WHERE lb.fs_company_info_id=" . $fs_company_info_id . ' AND lb.is_checked=1 AND lbd.fs_list_loans_and_borrowings_title_id <> 0 ORDER BY lb.order_by'); 
            $data = $data->result_array();
        }
        elseif($alias_value == "Note 23 - Provision (1)")
        {
            if($final_report_type == 1)
            {
                $data = $this->db->query("SELECT p.*, pd.section_name 
                                        FROM fs_provision_info p 
                                        LEFT JOIN fs_list_provision_content pd ON pd.id = p.fs_list_provision_content_id
                                        WHERE p.fs_company_info_id=" . $fs_company_info_id . " AND p.fs_list_provision_content_id != 5 AND (pd.fs_list_final_report_type_id=4 OR pd.fs_list_final_report_type_id=1) AND p.is_checked != 0 ORDER BY pd.order_by");
                $data = $data->result_array();
            }
            else
            {
                $data = $this->db->query("SELECT p.*, pd.section_name 
                                        FROM fs_provision_info p 
                                        LEFT JOIN fs_list_provision_content pd ON pd.id = p.fs_list_provision_content_id
                                        WHERE p.fs_company_info_id=" . $fs_company_info_id . " AND p.fs_list_provision_content_id != 5 AND (pd.fs_list_final_report_type_id=4 OR pd.fs_list_final_report_type_id=5) AND p.is_checked != 0 ORDER BY pd.order_by");
                $data = $data->result_array();
            }
        }
        elseif ($alias_value == "Note 24 - Trade and other payables (1)") 
        {
            if($final_report_type == 1)
            {
                $data = $this->db->query("SELECT p.*, pd.section_name 
                                        FROM fs_trade_and_other_payables_info p 
                                        LEFT JOIN fs_list_trade_and_other_payables_content pd ON pd.id = p.fs_list_trade_and_other_payables_content_id
                                        WHERE p.fs_company_info_id=" . $fs_company_info_id . " AND p.fs_list_trade_and_other_payables_content_id != 5 AND (pd.fs_list_final_report_type_id=4 OR pd.fs_list_final_report_type_id=1) AND p.is_checked != 0 ORDER BY pd.order_by");
                $data = $data->result_array();
            }
            else
            {
                $data = $this->db->query("SELECT p.*, pd.section_name 
                                        FROM fs_trade_and_other_payables_info p 
                                        LEFT JOIN fs_list_trade_and_other_payables_content pd ON pd.id = p.fs_list_trade_and_other_payables_content_id
                                        WHERE p.fs_company_info_id=" . $fs_company_info_id . " AND p.fs_list_trade_and_other_payables_content_id != 5 AND (pd.fs_list_final_report_type_id=4 OR pd.fs_list_final_report_type_id=5) AND p.is_checked != 0 ORDER BY pd.order_by");
                $data = $data->result_array();
            }
        }
        elseif($alias_value == "Note 26 - Related party transactions (ii) - content")
        {
            $data = $this->db->query("SELECT * FROM fs_related_party_transactions_info WHERE fs_company_info_id=" . $fs_company_info_id);
            $data = $data->result_array();
        }
        elseif ($alias_value == "Note 27 - Commitments (ii) (content)") 
        {
            $data = $this->db->query("SELECT * FROM fs_commitment_2_ntfs_info WHERE fs_company_info_id = " . $fs_company_info_id);
            $data = $data->result_array();
        }
        elseif ($alias_value == "Note 27 - Commitments (iii) (content)") 
        {
            $data = $this->db->query("SELECT * FROM fs_commitment_3_ntfs_info WHERE fs_company_info_id = " . $fs_company_info_id);
            $data = $data->result_array();
        }
        elseif ($alias_value == "Note 28 - Contingencies (i) (1)") 
        {
            $data = $this->db->query("SELECT ci.*, cd.is_sub, cd.section_name, cd.is_fixed_title
                                        FROM fs_contingencies_info ci
                                        LEFT JOIN fs_list_contingencies_content cd ON cd.id = ci.fs_list_contingencies_content_id
                                        WHERE ci.fs_company_info_id=" . $fs_company_info_id . ' AND cd.part = 1 AND ci.is_checked = 1 ORDER BY ci.order_by');
            $data = $data->result_array();
        }
        elseif ($alias_value == "Note 28 - Contingencies (ii) (1)") 
        {
            $data = $this->db->query("SELECT ci.* 
                                        FROM fs_contingencies_info ci
                                        LEFT JOIN fs_list_contingencies_content cd ON cd.id = ci.fs_list_contingencies_content_id
                                        WHERE ci.fs_company_info_id=" . $fs_company_info_id . ' AND cd.part = 2 AND ci.is_checked = 1 ORDER BY ci.order_by');
            $data = $data->result_array();
        }
        elseif($alias_value == "Note 29.3 - Financial Risk Management (2)")
        {
            $data = $this->db->query("SELECT * FROM fs_financial_risk_management_s3_info WHERE fs_company_info_id=" . $fs_company_info_id . " AND is_checked=1 AND main_section=1 AND sub_section=0");
            $data = $data->result_array();
        }
        elseif($alias_value == "Note 29.3 - Financial Risk Management (3)")
        {
            $data = $this->db->query("SELECT * FROM fs_financial_risk_management_s3_info WHERE fs_company_info_id=" . $fs_company_info_id . " AND is_checked=1 AND main_section=2 AND sub_section=0");
            $data = $data->result_array();
        }
        elseif($alias_value == "Note 29.3 - Financial Risk Management (4)")
        {
            $data = $this->db->query("SELECT * FROM fs_financial_risk_management_s3_info WHERE fs_company_info_id=" . $fs_company_info_id . " AND is_checked=1 AND main_section=2 AND sub_section=1");
            $data = $data->result_array();
        }
        elseif($alias_value == "Note 29.3 - Financial Risk Management (5)")
        {
            $data = $this->db->query("SELECT * FROM fs_financial_risk_management_s3_info WHERE fs_company_info_id=" . $fs_company_info_id . " AND is_checked=1 AND main_section=3 AND sub_section=1");
            $data = $data->result_array();
        }
        elseif($alias_value == "Note 33 - Events occuring after the reporting period (1)")
        {
            $data = $this->fs_notes_model->get_fs_event_occur_after_rp_info($fs_company_info_id);
        }

        return $data;
    }

    public function get_t1_value_tr_template($table_name, $fs_company_info_id)
    {
        $data = [];

        if($table_name == "Employee benefits expense (not first set)" || $table_name == "Employee benefits expense (first set)")
        {
            // for table
            $fs_statements_list = $this->fs_statements_model->get_fs_statement_json();
            $fs_statements_list = json_decode(json_encode($fs_statements_list), true);

            $er_key = array_search("Employee benefits expense", array_column($fs_statements_list['ntfs'][0]['sections'], 'title'));
            $er_account_code = $fs_statements_list['ntfs'][0]['sections'][$er_key]['account_category_code'][0];

            $fca_id   = $this->fs_notes_model->get_fca_id($fs_company_info_id, array($er_account_code));
            $ebe_list = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $fca_id);

            $data = $ebe_list;
        }
        elseif($table_name == "Note 5 - Tax expense (table_1) (not first set)" || $table_name == "Note 5 - Tax expense (table_1) (first set)")
        {
            $data = $this->fs_notes_model->get_fs_tax_expense_ntfs($fs_company_info_id);
        }
        elseif($table_name == "Note 5 - Tax expense (table_2) (not first set)" || $table_name == "Note 5 - Tax expense (table_2) (first set)")
        {
            $data = $this->fs_notes_model->get_fs_tax_expense_reconciliation($fs_company_info_id);
        }
        elseif($table_name == "Note 6 - Investment in subsidiaries (i) (table_1)(Small FRS)") 
        {
            $data = $this->db->query("SELECT iis_t2.*, c.nicename 
                                        FROM fs_investment_in_subsidiaries_ntfs_2 iis_t2
                                        LEFT JOIN fs_country c ON c.id = iis_t2.country_id
                                        WHERE iis_t2.fs_company_info_id=" . $fs_company_info_id . ' ORDER BY iis_t2.order_by');
            $data = $data->result_array();
        }
        elseif($table_name == "Note 6 - Investment in subsidiaries (ii) (table_1)")
        {
            $iis_p2_t1_row_data = $this->db->query("SELECT * FROM fs_investment_in_subsidiaries_ntfs_p2_1 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $iis_p2_t1_row_data = $iis_p2_t1_row_data->result_array();

            foreach ($iis_p2_t1_row_data as $key => $value) 
            {
                $iis_p2_t1_row_data[$key]['row_item'] = explode(",", $value['row_item']);
            }

            $data = $iis_p2_t1_row_data;
        }
        elseif($table_name == "Note 7 - Investment in associates (table_1) (not first set)" || $table_name == "Note 7 - Investment in associates (table_1) (first set)")
        {
            $data = $this->db->query("SELECT * FROM fs_investment_in_associates_ntfs WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 7 - Investment in associates (table_2)")
        {
            $data = $this->db->query("SELECT iia_t2.*, c.nicename 
                                        FROM fs_investment_in_associates_ntfs_2 iia_t2
                                        LEFT JOIN fs_country c ON c.id = iia_t2.country_id
                                        WHERE iia_t2.fs_company_info_id=" . $fs_company_info_id . ' ORDER BY iia_t2.order_by');
            $data = $data->result_array();
        }
        elseif($table_name == "Note 7 - Investment in associates (table_3) (first set)" || $table_name == "Note 7 - Investment in associates (table_3) (not first set)")
        {
            $data = $this->db->query("SELECT * FROM fs_investment_in_associates_ntfs_3 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 8 - Investment in joint venture (table_1)")
        {
            $data = $this->db->query("SELECT iijv_t1.*, c.nicename 
                                        FROM fs_investment_in_joint_venture_ntfs iijv_t1
                                        LEFT JOIN fs_country c ON c.id = iijv_t1.country_id
                                        WHERE iijv_t1.fs_company_info_id=" . $fs_company_info_id . ' ORDER BY iijv_t1.order_by');
            $data = $data->result_array();
        }
        elseif($table_name == "Note 8 - Investment in joint venture (table_2) (first set)" || $table_name == "Note 8 - Investment in joint venture (table_2) (not first set)")
        {
            $data = $this->db->query("SELECT * FROM fs_investment_in_joint_venture_ntfs_2 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 9 - Intangible assets (table_1)")
        {
            $ia_row_data = $this->db->query("SELECT * FROM fs_intangible_assets_ntfs_1 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $ia_row_data = $ia_row_data->result_array();

            foreach ($ia_row_data as $key => $value) 
            {
                $ia_row_data[$key]['row_item'] = explode(",", $value['row_item']);
            }

            $data = $ia_row_data;
        }
        elseif($table_name == "Note 10 - Insured benefits (table_1) (first set)" || $table_name == "Note 10 - Insured benefits (table_1) (not first set)")
        {
            $data = $this->db->query("SELECT * FROM fs_insured_benefits_ntfs WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 11 - Investment properties cost_model (table_1)")
        {
            $ip_t1_row_data = $this->db->query("SELECT * FROM fs_investment_properties_ntfs_1 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $ip_t1_row_data = $ip_t1_row_data->result_array();

            foreach ($ip_t1_row_data as $key => $value) 
            {
                $ip_t1_row_data[$key]['row_item'] = explode(",", $value['row_item']);
            }
            $data = $ip_t1_row_data;
        }
        elseif($table_name == "Note 11 - Investment properties (table_2) (first set)" || $table_name == "Note 11 - Investment properties (table_2) (not first set)")
        {
            $data = $this->db->query("SELECT * FROM fs_investment_properties_ntfs_2 WHERE fs_company_info_id = " . $fs_company_info_id . " ORDER BY order_by");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 11 - Investment properties (table_3)")
        {
            $ip_t3_row_data = $this->db->query("SELECT * FROM fs_investment_properties_ntfs_3 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $ip_t3_row_data = $ip_t3_row_data->result_array();

            if(count($ip_t3_row_data) > 0)
            {
                foreach ($ip_t3_row_data as $key => $value) 
                {
                    $ip_t3_row_data[$key]['row_item'] = explode(",", $value['row_item']);
                }
                $data = $ip_t3_row_data;
            } 
        }
        elseif($table_name == "Note 11 - Investment properties (table_4)")
        {
            $data = $this->db->query("SELECT * FROM fs_investment_properties_ntfs_4 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 11 - Investment properties (table_5) (first set)" || $table_name == "Note 11 - Investment properties (table_5) (not first set)")
        {
            $data = $this->db->query("SELECT * FROM fs_investment_properties_ntfs_5 WHERE fs_company_info_id= " . $fs_company_info_id . " ORDER BY order_by");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 12 - Property, plant and equipment (table_1)")
        {
            $ppe_t1_row_data = $this->db->query("SELECT * FROM fs_ppe_ntfs_1 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $ppe_t1_row_data = $ppe_t1_row_data->result_array();

            foreach ($ppe_t1_row_data as $key => $value) 
            {
                $ppe_t1_row_data[$key]['row_item'] = explode(",", $value['row_item']);
            }
            $data = $ppe_t1_row_data;
        }
        elseif($table_name == "Note 13 - Available for sale (table_1) (first set)" || $table_name == "Note 13 - Available for sale (table_1) (not first set)")
        {
            $data = $this->db->query("SELECT * FROM fs_available_for_sale_ntfs WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 14 - Inventories (table_1) (first set)" || $table_name == "Note 14 - Inventories (table_1) (not first set)")
        {
            $data = $this->db->query("SELECT * FROM fs_inventories_ntfs_1 WHERE fs_company_info_id = " . $fs_company_info_id);
            $data = $data->result_array();
        }
        elseif($table_name == "Note 15 - Contract assets and contract liabilities (table_1) (first set)" || $table_name == "Note 15 - Contract assets and contract liabilities (table_1) (not first set)")
        {
            $data = $this->db->query("SELECT * FROM fs_contract_assets_and_contract_liabilities_ntfs WHERE fs_company_info_id = " . $fs_company_info_id);
            $data = $data->result_array();
        }
        elseif($table_name == "Note 16 - Trade and other receivables (table_1) (first set)" || $table_name == "Note 16 - Trade and other receivables (table_1) (not first set)")
        {
            $fs_note_title_json = $this->fs_generate_doc_word_model->get_fs_note_title_json();
            $fnt_ntac_key = array_search('Note 16 - Trade and other receivables', array_column((array)$fs_note_title_json->note_title_account_code, 'note_title'));
            $tor_account_code = $fs_note_title_json->note_title_account_code[$fnt_ntac_key]->account_code;

            $tor_fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, array($tor_account_code));
            $tor_data   = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $tor_fca_id);

            $data = $tor_data;
        }
        elseif($table_name == "Note 16 - Trade and other receivables (table_2) (not first set)" || $table_name == "Note 16 - Trade and other receivables (table_2) (first set)")
        {
            $data = $this->db->query("SELECT tor2.*, c.name AS `description`
                                        FROM fs_trade_and_other_receivables_ntfs_2 tor2
                                        LEFT JOIN currency c ON c.id = tor2.currency_id
                                        WHERE tor2.fs_company_info_id=" . $fs_company_info_id . " ORDER BY tor2.order_by");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 16 - Trade and other receivables (table_3) (not first set)" || $table_name == "Note 16 - Trade and other receivables (table_3) (first set)")
        {
            $data = $this->db->query("SELECT tor.*, tord.description 
                                    FROM fs_trade_and_other_receivables_ntfs_3 tor
                                    LEFT JOIN fs_list_trade_and_other_receivables_ntfs_3 tord ON tord.id = tor.fs_list_trade_and_other_receivables_ntfs_3_id
                                    WHERE tor.fs_company_info_id = " . $fs_company_info_id . " ORDER BY tor.order_by");
            $data = $data->result_array();

            $temp_data = $data;

            // remove line if all value is 0
            foreach ($temp_data as $key => $value) 
            {
                if(empty($value['value']) && empty($value['company_end_prev_ye_value']) && empty($value['group_end_this_ye_value']) && empty($value['group_end_prev_ye_value']))
                {
                    unset($data[$key]);
                }
            }
        }
        elseif($table_name == "Note 16 - Trade and other receivables (table_4) (first set)" || $table_name == "Note 16 - Trade and other receivables (table_4) (not first set)")
        {
            $data = $this->db->query("SELECT tor.*, tord.description, tord.part, tord.is_title
                                    FROM fs_trade_and_other_receivables_ntfs_4 tor
                                    LEFT JOIN fs_list_trade_and_other_receivables_ntfs_4 tord ON tord.id = tor.fs_list_trade_and_other_receivables_ntfs_4_id
                                    WHERE tor.fs_company_info_id = " . $fs_company_info_id . " ORDER BY tor.order_by");
            $data = $data->result_array();

            // remove line if all value is 0
            foreach ($temp_data as $key => $value) 
            {
                if(empty($value['value']) && empty($value['company_end_prev_ye_value']) && empty($value['group_end_this_ye_value']) && empty($value['group_end_prev_ye_value']))
                {
                    unset($data[$key]);
                }
            }
        }
        elseif($table_name == "Note 17 - Other current assets (table_1) (first set)" || $table_name == "Note 17 - Other current assets (table_1) (not first set)" )
        {
            $data = $this->db->query("SELECT * FROM fs_other_current_assets_ntfs WHERE fs_company_info_id=" . $fs_company_info_id);
            $data = $data->result_array();
        }
        elseif($table_name == "Note 18 - Cash and short-term deposits (table_1) (first set)" || $table_name == "Note 18 - Cash and short-term deposits (table_1) (not first set)")
        {
            $data = $this->db->query("SELECT * FROM fs_cash_short_term_deposits_ntfs_1 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $data = $data->result_array(); 
        }
        elseif($table_name == "Note 18 - Cash and short-term deposits (table_2) (first set)" || $table_name == "Note 18 - Cash and short-term deposits (table_2) (not first set)")
        {
            $data = $this->db->query("SELECT csd2.*, c.name AS `description`
                                        FROM fs_cash_short_term_deposits_ntfs_2 csd2
                                        LEFT JOIN currency c ON c.id = csd2.currency_id
                                        WHERE csd2.fs_company_info_id=" . $fs_company_info_id . " ORDER BY csd2.order_by");
            $data = $data->result_array(); 
        }
        elseif($table_name == "Note 18 - Cash and short-term deposits (table_3) (first set)" || $table_name == "Note 18 - Cash and short-term deposits (table_3) (not first set)")
        {
            $data = $this->db->query("SELECT * FROM fs_cash_short_term_deposits_ntfs_3 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $data = $data->result_array(); 
        }
        elseif($table_name == "Note 21 - Deferred tax liabilities (table_1) (first set)" || $table_name == "Note 21 - Deferred tax liabilities (table_1) (not first set)")
        {
            $data = $this->db->query("SELECT * FROM fs_deferred_tax_liabilities_ntfs WHERE fs_company_info_id=" . $fs_company_info_id);
            $data = $data->result_array();
        }
        elseif($table_name == "Note 22 - Loans and borrowings (table_1) (first set)" || $table_name == "Note 22 - Loans and borrowings (table_1) (not first set)")
        {
            $lb_t1 = $this->db->query("SELECT * FROM fs_loans_and_borrowings_ntfs_1 WHERE fs_company_info_id=" . $fs_company_info_id . " AND is_last_section = 0 ORDER BY order_by");
            $lb_t1 = $lb_t1->result_array();

            $lb_t1_ls = $this->db->query("SELECT * FROM fs_loans_and_borrowings_ntfs_1 WHERE fs_company_info_id=" . $fs_company_info_id . " AND is_last_section = 1 ORDER BY order_by");
            $lb_t1_ls = $lb_t1_ls->result_array();

            $data = array_merge($lb_t1, $lb_t1_ls);
        }
        elseif($table_name == "Note 22 - Loans and borrowings (table_2)")
        {
            $data = $this->db->query("SELECT * FROM fs_loans_and_borrowings_ntfs_2 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 22 - Loans and borrowings (table_3) (first set)" || $table_name == "Note 22 - Loans and borrowings (table_3) (not first set)")
        {
            $data = $this->db->query("SELECT lb3.*, c.name AS `description`
                                        FROM fs_loans_and_borrowings_ntfs_3 lb3
                                        LEFT JOIN currency c ON c.id = lb3.currency_id
                                        WHERE lb3.fs_company_info_id=" . $fs_company_info_id . " ORDER BY lb3.order_by");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 22 - Loans and borrowings (table 4) (first set)" || $table_name == "Note 22 - Loans and borrowings (table 4) (not first set)")
        {
            $data = $this->db->query("SELECT * FROM fs_loans_and_borrowings_ntfs_4 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 22 - Loans and borrowings (table_5) (first set)" || $table_name == "Note 22 - Loans and borrowings (table_5) (not first set)")
        {
            $data = $this->db->query("SELECT * FROM fs_loans_and_borrowings_ntfs_5 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 23 - Provision (table_1) (first set)" || $table_name == "Note 23 - Provision (table_1) (not first set)")
        {
            $data = $this->db->query("SELECT * FROM fs_provision_ntfs WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 24 - Trade and other payables (table_1) (first set)" || $table_name == "Note 24 - Trade and other payables (table_1) (not first set)")
        {
            $fs_note_title_json = $this->fs_generate_doc_word_model->get_fs_note_title_json();
            $fnt_ntac_key = array_search('Note 24 - Trade and other payables', array_column((array)$fs_note_title_json->note_title_account_code, 'note_title'));
            $top_account_code = $fs_note_title_json->note_title_account_code[$fnt_ntac_key]->account_code;

            $top_fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, array($top_account_code));
            $top_data   = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $top_fca_id);

            $data = $top_data;
        }
        elseif($table_name == "Note 24 - Trade and other payables (table_2) (first set)" || $table_name == "Note 24 - Trade and other payables (table_2) (not first set)")
        {
            $data = $this->db->query("SELECT top.*, c.name AS `description`
                                        FROM fs_trade_and_other_payables_ntfs_2 top
                                        LEFT JOIN currency c ON c.id = top.currency_id
                                        WHERE top.fs_company_info_id=" . $fs_company_info_id . " ORDER BY top.order_by");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 25 - Other current liabilities (table_1) (first set)" || $table_name == "Note 25 - Other current liabilities (table_1) (not first set)")
        {
            $data = $this->db->query("SELECT * FROM fs_other_current_liabilities_ntfs WHERE fs_company_info_id=" . $fs_company_info_id);
            $data = $data->result_array();
        }
        elseif($table_name == "Note 26 - Related party transactions (table_1) (first set)" || $table_name == "Note 26 - Related party transactions (table_1) (not first set)")
        {
            $data = $this->db->query("SELECT * FROM fs_related_party_transactions_ntfs_1 WHERE fs_company_info_id= " . $fs_company_info_id . " ORDER BY order_by");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 27 - Commitments (table_1) (first set)" || $table_name == "Note 27 - Commitments (table_1) (not first set)")
        {
            $data = $this->db->query("SELECT * FROM fs_commitment_2_ntfs_1 WHERE fs_company_info_id=" . $fs_company_info_id);
            $data = $data->result_array();
        }
        elseif($table_name == "Note 27 - Commitments (table_2) (first set)" || $table_name == "Note 27 - Commitments (table_2) (not first set)")
        {
            $data = $this->db->query("SELECT t1.*, t2.description
                                        FROM fs_commitment_2_ntfs_2 t1
                                        LEFT JOIN fs_list_commitment_2_ntfs_2 t2 ON t2.in_used = 1 AND t1.fs_list_commitment_2_ntfs_2_id = t2.id
                                        WHERE t1.fs_company_info_id=" . $fs_company_info_id . " AND t1.is_checked = 1");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 27 - Commitments (table_3) (first set)" || $table_name == "Note 27 - Commitments (table_3) (not first set)")
        {
            $data = $this->db->query("SELECT * FROM fs_commitment_3_ntfs_1 WHERE fs_company_info_id=" . $fs_company_info_id);
            $data = $data->result_array();
        }
        elseif($table_name == "Note 27 - Commitments (table_4) (first set)" || $table_name == "Note 27 - Commitments (table_4) (not first set)")
        {
            $data = $this->db->query("SELECT t1.*, t2.description
                                        FROM fs_commitment_3_ntfs_2 t1
                                        LEFT JOIN fs_list_commitment_2_ntfs_2 t2 ON t2.in_used = 1 AND t1.fs_list_commitment_2_ntfs_2_id = t2.id
                                        WHERE t1.fs_company_info_id=" . $fs_company_info_id . " AND t1.is_checked = 1");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 29.2 - Financial Risk Management (table_1) (group)")
        {
            $data = $this->db->query("SELECT * FROM fs_financial_risk_management_ntfs_s2_group WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 29.2 - Financial Risk Management (table_1) (company)")
        {
            $data = $this->db->query("SELECT * FROM fs_financial_risk_management_ntfs_s2_company WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 29.3 - Financial Risk Management (table_1) (first set)" || $table_name == "Note 29.3 - Financial Risk Management (table_1) (not first set)")
        {
            $data = $this->db->query("SELECT * FROM fs_financial_risk_management_ntfs_s3_floating WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 29.3 - Financial Risk Management (table_2) (first set)" || $table_name == "Note 29.3 - Financial Risk Management (table_2) (not first set)")
        {
            $data = $this->db->query("SELECT * FROM fs_financial_risk_management_ntfs_s3_fixed WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $data = $data->result_array();
        }
        elseif($table_name == "Note 29.4 - Financial Risk Management (table_1) (group)")
        {
            $frm_s4_t1_row_data = $this->db->query("SELECT * FROM fs_financial_risk_management_s4_t1 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $frm_s4_t1_row_data = $frm_s4_t1_row_data->result_array();

            foreach ($frm_s4_t1_row_data as $key => $value) 
            {
                $frm_s4_t1_row_data[$key]['row_item'] = explode(",", $value['row_item']);
            }

            $data = $frm_s4_t1_row_data;
        }
        elseif($table_name == "Note 29.4 - Financial Risk Management (table_1) (company)")
        {
            $frm_s4_t2_row_data = $this->db->query("SELECT * FROM fs_financial_risk_management_s4_t2 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $frm_s4_t2_row_data = $frm_s4_t2_row_data->result_array();

            foreach ($frm_s4_t2_row_data as $key => $value) 
            {
                $frm_s4_t2_row_data[$key]['row_item'] = explode(",", $value['row_item']);
            }

            $data = $frm_s4_t2_row_data;
        }
        elseif($table_name == "Note 29.4 - Financial Risk Management (table_2) (first set)" || $table_name == "Note 29.4 - Financial Risk Management (table_2) (not first set)")
        {
            $master_currency_item = $this->db->query("SELECT mc.*, c.currency, c.name, c.id AS `currency_id`
                                                        FROM fs_ntfs_master_currency mc
                                                        LEFT JOIN currency c ON c.id = mc.currency_id
                                                        WHERE mc.fs_company_info_id = " . $fs_company_info_id . " ORDER BY order_by");
            $master_currency_item = $master_currency_item->result_array();

            // retrieve table 1 data
            $frm_s4_t1_group = $this->db->query("SELECT * FROM fs_financial_risk_management_s4_t1 WHERE fs_company_info_id=" . $fs_company_info_id);
            $frm_s4_t1_group = $frm_s4_t1_group->result_array();

            // retrieve table 2 data
            $frm_s4_t2_company = $this->db->query("SELECT * FROM fs_financial_risk_management_s4_t2 WHERE fs_company_info_id=" . $fs_company_info_id);
            $frm_s4_t2_company = $frm_s4_t2_company->result_array();

            $data = [];

            // separate data into prior and current 
            $row_items_g_prior   = [];
            $row_items_g_current = [];
            $row_items_c_prior   = [];
            $row_items_c_current = [];

            // table 1
            foreach ($frm_s4_t1_group as $frm_s4_g_key => $frm_s4_g_value) 
            {
                if($frm_s4_g_value['prior_current'] == "prior")
                {
                    array_push($row_items_g_prior, $frm_s4_g_value['row_item']);
                }
                elseif($frm_s4_g_value['prior_current'] == "current")
                {
                    array_push($row_items_g_current, $frm_s4_g_value['row_item']);
                }
            }

            // table 2
            foreach ($frm_s4_t2_company as $frm_s4_c_key => $frm_s4_c_value) 
            {
                if($frm_s4_c_value['prior_current'] == "prior")
                {
                    array_push($row_items_c_prior, $frm_s4_c_value['row_item']);
                }
                elseif($frm_s4_c_value['prior_current'] == "current")
                {
                    array_push($row_items_c_current, $frm_s4_c_value['row_item']);
                }
            }

            // calculate total 
            foreach ($master_currency_item as $key => $value) 
            {
                $total_current_g = 0;
                $total_prior_g   = 0;
                $total_current_c = 0;
                $total_prior_c   = 0;

                /* --------------- for table 1 (Group) --------------- */
                // calculate total for current year
                foreach ($row_items_g_current as $c_key => $c_val) 
                {
                    $row_items_c = explode(',', $c_val);
                    $total_current_g += (int)$row_items_c[$key];
                }

                // calculate total for prior year
                foreach ($row_items_g_prior as $p_key => $p_val) 
                {
                    $row_items_p = explode(',', $p_val);
                    $total_prior_g += (int)$row_items_p[$key];
                }

                /* ---------------for table 2 (Company) --------------- */
                // calculate total for current year
                foreach ($row_items_c_current as $c_key => $c_val) 
                {
                    $row_items_c = explode(',', $c_val);
                    $total_current_c += (int)$row_items_c[$key];
                }

                // calculate total for prior year
                foreach ($row_items_c_prior as $p_key => $p_val) 
                {
                    $row_items_p = explode(',', $p_val);
                    $total_prior_c += (int)$row_items_p[$key];
                }

                array_push($data, 
                    array(
                        'currency_id'               => $value['currency_id'], 
                        'description'               => $value['name'], 
                        'group_end_this_ye_value'   => round($total_current_g * (10/100)), 
                        'group_end_prev_ye_value'   => round($total_prior_g * (10/100)), 
                        'value'                     => round($total_current_c * (10/100)),
                        'company_end_prev_ye_value' => round($total_prior_c * (10/100))
                    )
                );
            }

            return $data;
        }
        elseif($table_name == "Note 30 - Fair Value of assets (table_1) (group)" || $table_name == "Note 30 - Fair Value of assets (table_1) (company)")
        {
            if($table_name == "Note 30 - Fair Value of assets (table_1) (group)")
            {
                $data = $this->db->query("SELECT * FROM fs_fair_value_of_assets_ntfs WHERE fs_company_info_id = " . $fs_company_info_id . " AND group_company='group' ORDER BY order_by");
                $data = $data->result_array();
            }
            elseif($table_name == "Note 30 - Fair Value of assets (table_1) (company)")
            {
                $data = $this->db->query("SELECT * FROM fs_fair_value_of_assets_ntfs WHERE fs_company_info_id = " . $fs_company_info_id . " AND group_company='company' ORDER BY order_by");
                $data = $data->result_array();
            }
        }

        return $data;
    }

    public function store_header_xml_filename($data)
    {
        foreach ($data as $key => $value) 
        {
            $retrieve_data = $this->db->query("SELECT * FROM fs_word_link_header_xml_file WHERE fs_company_info_id = " . $value['fs_company_info_id'] . " AND fs_list_word_header_link_relationship_id=" . $value['fs_list_word_header_link_relationship_id']);
            $retrieve_data = $retrieve_data->result_array();

            $temp_data = array(
                                'info' => $value
                            );

            if(count($retrieve_data) > 0) // update
            {
                $temp_data['id'] = $retrieve_data[0]['id'];
                $this->fs_notes_model->update_tbl_data('fs_word_link_header_xml_file', array($temp_data));
            }
            else // create
            {
                $this->fs_notes_model->insert_tbl_data('fs_word_link_header_xml_file', array($temp_data));
            }
        }

        return true;
    }

    public function update_save_report_template($data)
    {
        $fs_company_info = $this->fs_model->get_fs_company_info($data['fs_company_info_id']);
        $year_of_YE = substr($fs_company_info[0]['current_fye_end'], -4);

        $target_dir = $data['client_filepath'] . $year_of_YE . '/';
        $path_filename_ext = $target_dir.$data['filename'];

        $this->create_year_folder_for_client_report_template($data['fs_company_info_id'], $year_of_YE);

        /* --------- Delete / create document --------- */
        $fs_doc_template_word = $this->get_fs_doc_template_word($data['fs_company_info_id']);

        if(count($fs_doc_template_word) > 0)    // delete previous template and update db data
        {
            $path_filename_ext = $target_dir.$fs_doc_template_word[0]['filename'];

            $file_pointer = '../fs/' . $fs_doc_template_word[0]['filepath'];

            if (!unlink($file_pointer)) 
            {  
                // $alert_msg = "$file_pointer cannot be deleted due to an error";  
                $alert_msg = "The previous file cannot be deleted due to an error. Therefore, the file is failed to upload."; 

                $result = false;
            }  
            else 
            {  
                copy($data['copy_from'], FCPATH . $path_filename_ext);

                $save_to_db_data = array(
                                        'id' => $fs_doc_template_word[0]['id'],
                                        'info' => array(
                                                    'last_document' => $data['filename'],
                                                    'filename' => $fs_doc_template_word[0]['filename'],
                                                    'filepath' => $path_filename_ext
                                                )
                                    );

                // update filepath and filename in db
                $result = $this->fs_notes_model->update_tbl_data('fs_doc_template_word', array($save_to_db_data));

                if(!$result)
                {
                    $alert_msg = "Something went wrong. Please try again later."; 
                }
                else
                {
                    $alert_msg = "Upload success. The previous file has been replaced.";  
                }
            }
        }
        else
        {
            $saved_file_path = $data['client_filepath'] . $year_of_YE . '/' . $data['filename'];

            copy($data['copy_from'], FCPATH . $saved_file_path);

            $save_to_db_data = array(
                                    'info' => array(
                                                'fs_company_info_id' => $data['fs_company_info_id'],
                                                'last_document' => $data['filename'],
                                                'filename' => $data['filename'],
                                                'filepath' => $saved_file_path
                                            )
                                );

            $result = $this->fs_notes_model->insert_tbl_data('fs_doc_template_word', array($save_to_db_data));
        }
        /* --------- END OF Delete / create document --------- */

        // echo json_encode(array("result" => $result, "alert_msg" => $alert_msg, "data" => $save_to_db_data));
    }

    public function create_year_folder_for_client_report_template($fs_company_info_id, $year_of_YE)  // create folder depend on year of year end such as 2018, 2019 ...
    {
        $sDirPath = '../fs/Document Templates/FS Template Client/' . $year_of_YE;

        if (!file_exists($sDirPath))
        {
            mkdir($sDirPath,0777,true); 
        }

        return true;
    }

    public function get_table_setting($table_name)
    {
        $list = $this->getFS_report_list();

        foreach($list->table_settings as $item)
        {
            if($item->table_name == $table_name)
            {
                $array = json_decode(json_encode($item->hide_column_data), True);
            }
        }
        return $array[0];
    }

    public function get_hide_content_condition($table_name, $fs_company_info)
    {
        $list = $this->getFS_report_list();
        $is_hide_content = false;

        foreach($list->table_settings as $item)
        {
            if($item->table_name == $table_name)
            {
                if($item->first_set == 2)
                {
                    $is_hide_content = false;
                }
                else
                {
                    if($fs_company_info[0]['first_set'])
                    {
                        if($item->first_set == 0)
                        {
                            $is_hide_content = true;
                        }
                    }
                    else
                    {
                        if($item->first_set == 1)
                        {
                            $is_hide_content = true;
                        }
                    }
                }
            }
        }
        return $is_hide_content;
    }

    public function get_table_fs_ntfs_info($table_name)
    {   
        $data = [];
        $list = $this->getFS_report_list();

        foreach($list->table_settings as $item)
        {
            if($item->table_name == $table_name)
            {
                $data = json_decode(json_encode($item), True);
            }
        }

        return $data;
    }

    public function get_fs_ntfs_template_id($fs_company_info_id, $table_name)
    {
        $data = [];
        $fs_ntfs_template_id = 0;
        $list = $this->getFS_report_list();

        foreach($list->table_settings as $item)
        {
            if($item->table_name == $table_name)
            {
                $data = json_decode(json_encode($item), True);

                // if($table_name == "Note 6 - Investment in subsidiaries (i) (table_1)")
                // {
                //     print_r(array($data));
                // }
                $main_is_checked = $this->get_checked_result_section($fs_company_info_id, $data['linked_fs_ntfs_template_id']);

                if($main_is_checked)
                {
                    if(!empty($data['is_sub_category']))
                    {
                        if($data['is_sub_category'] == 1)
                        {
                            $fs_ntfs_template_id = $data['actual_linked_fs_ntfs_template_id'];
                        }
                        else
                        {
                            $fs_ntfs_template_id = $data['linked_fs_ntfs_template_id'];
                        }
                    }
                    else
                    {
                        $fs_ntfs_template_id = $data['linked_fs_ntfs_template_id'];
                    }
                }
                else
                {
                    $fs_ntfs_template_id = $data['linked_fs_ntfs_template_id'];
                }
            }
        }

        return $fs_ntfs_template_id;
    }

    public function get_table_result_hide_show($fs_company_info_id, $table_name)
    {
        $data = [];
        $hide_table = true;
        $fs_ntfs_template_id = 0;
        $list = $this->getFS_report_list();

        foreach($list->table_settings as $item)
        {
            if($item->table_name == $table_name)
            {
                $data = json_decode(json_encode($item), True);

                $main_is_checked = $this->get_checked_result_section($fs_company_info_id, $data['linked_fs_ntfs_template_id']);

                if($main_is_checked)
                {
                    if(!empty($data['is_sub_category']))
                    {
                        if($data['is_sub_category'] == 1)
                        {
                            $fs_ntfs_template_id = $data['actual_linked_fs_ntfs_template_id'];
                        }
                        else
                        {
                            $fs_ntfs_template_id = $data['linked_fs_ntfs_template_id'];
                        }
                    }
                    else
                    {
                        $fs_ntfs_template_id = $data['linked_fs_ntfs_template_id'];
                    }
                }
                else
                {
                    $fs_ntfs_template_id = $data['linked_fs_ntfs_template_id'];
                }

                $main_content_checked = $this->get_checked_result_section($fs_company_info_id, $fs_ntfs_template_id);

                if(!$main_content_checked)
                {
                    $hide_table = true;
                }
                else
                {
                    if(!empty($data['is_group_table']))
                    {
                        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);   

                        if($data['is_group_table'] == 1 && $fs_company_info[0]['group_type'] != 1)
                        {
                            $hide_table = false;
                        }   
                        else
                        {
                            $hide_table = true;
                        }
                    }
                    else
                    {
                        $hide_table = false;
                    }
                }

                break;
            }
        }

        return $hide_table;
    }

    public function get_fs_doc_template_word($fs_company_info_id)
    {
        if(!empty($fs_company_info_id))
        {
            $q = $this->db->query("SELECT gdw.*
                                FROM fs_doc_template_word gdw
                                INNER JOIN (
                                    SELECT fs_company_info_id, max(created_at) as `MaxDate` 
                                    FROM fs_doc_template_word
                                    WHERE fs_company_info_id = " . $fs_company_info_id . " ORDER BY created_at LIMIT 1
                                ) gwd1 
                                ON gdw.fs_company_info_id = gwd1.fs_company_info_id AND gdw.created_at = gwd1.MaxDate
                                WHERE gdw.fs_company_info_id=" .  $fs_company_info_id . " LIMIT 1");

            return $q->result_array();
        }
        else
        {
            return [];
        }
    }

    public function get_fs_doc_template_word_base($final_document_type)
    {
        $q = $this->db->query("SELECT * FROM fs_doc_template_word_base WHERE fs_list_final_report_type_id=" . $final_document_type . " AND is_used = 1");
        $q = $q->result_array();

        return $q;
    }

    /* -------------------- Testing for simplexml ---------------------------------- */
    // public function update_using_simplexml($original_xml, $fs_company_info_id)
    // {
    //     $xml = simplexml_load_string($original_xml, null, 0, 'w', true);

    //     $sdt_list = $xml->body[0]->sdt;

    //     $tag_info = array();

    //     foreach($sdt_list as $sdt_key => $sdt_node)  // get all sdt list
    //     {
    //         // array_push($tag_info, $this->read_sdt_info($sdt_node));

    //         $sdt_node = $this->update_content($this->read_sdt_info($sdt_node), $sdt_node);
    //     }

    //     // echo json_encode($tag_info[1]['alias_name']);

    //     // echo json_encode($xml->asXML());

    //     return $xml->asXML();
    // }

    // public function read_sdt_info($sdt_node)
    // {
    //     $data = [];
    //     $alias_name = $sdt_node->sdtPr->alias['val'];

    //     if(!empty($alias_name))
    //     {
    //         $data = array
    //                 (
    //                     'alias_name' => $alias_name
    //                 );
    //     }

    //     return $data;
    // }

    // public function update_content($sdt_node_info, $sdt_node)
    // {
    //     $sdt_node = $sdt_node->sdtContent->p->r->t = 'ABC';

    //     return $sdt_node;
    // }

    /* -------------------- END OF Testing for simplexml ---------------------------------- */

    public function update_toggle($original_xml, $fs_company_info_id, $additional_info)
    {   
        $updated_xml = $original_xml;

        $wsdt_arr_content = $this->get_part_of_template_include_nested('w:sdt', $updated_xml);

        $numPr_tag_ids = '';
        $note_title_numPr_tag_ids = '';

        $note_title_account_code_list = json_decode(json_encode($this->get_fs_note_title_json()->note_title_account_code), true);
        $note_title_account_code_note_list = array_column($note_title_account_code_list, 'note_title');

        $used_add_note_list = $this->fs_notes_model->get_used_add_note_list($fs_company_info_id);
        $used_add_note_list_account_code = array_column($used_add_note_list, 'account_code'); 

        $i = 0;

        if(count($wsdt_arr_content[0]) > 0)
        {
            foreach ($wsdt_arr_content[0] as $key => $wsdt_xml) 
            {
                $replaced_wsdt_xml = $wsdt_xml;
                $is_section = $this->is_quick_part($wsdt_xml);

                if($is_section)
                {
                    $alias_tag   = $this->get_tag('w:alias', $wsdt_xml);
                    $alias_value = $this->get_attribute_value('w:val', $alias_tag[0][0]);   // get w:value attribute
                    $alias_value = $alias_value[1][0];

                    /* ----------------------------------- change show / hide content here ------------------------------------------*/
                    $hide_needed = false;

                    $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

                    // print_r(array($alias_value));

                    if($alias_value == "Statement of director - General (Option 1)" || $alias_value == "Statement of director - General (Option 2)") 
                    {
                        if($fs_company_info[0]['first_set'] == "1")
                        {
                            if($alias_value == "Statement of director - General (Option 1)")
                            {
                                $hide_needed = true;
                            }

                            if($alias_value == "Statement of director - General (Option 2)")
                            {
                                $hide_needed = false;
                            }
                        }
                        else
                        {
                            if($alias_value == "Statement of director - General (Option 1)")
                            {
                                $hide_needed = false;
                            }

                            if($alias_value == "Statement of director - General (Option 2)")
                            {
                                $hide_needed = true;
                            }
                        }
                    }

                    elseif($alias_value == "Statement of director - No director's shareholders (Option 1)" || $alias_value == "Statement of director - No director's shareholders (Option 2)")
                    {
                        if(!(int)$fs_company_info[0]['has_director_interest'])  // if no director interest
                        {
                            if($alias_value == "Statement of director - No director's shareholders (Option 1)")
                            {
                                $hide_needed = false;
                            }

                            if($alias_value == "Statement of director - No director's shareholders (Option 2)")
                            {
                                $hide_needed = true;
                            }
                        }
                        else
                        {
                            if($alias_value == "Statement of director - No director's shareholders (Option 1)")
                            {
                                $hide_needed = true;
                            }

                            if($alias_value == "Statement of director - No director's shareholders (Option 2)")
                            {
                                $hide_needed = false;
                            }
                        }

                        // $hide_needed = true;
                    }
                    // Notes to financial statement
                    elseif($alias_value == "Note 1 - Domicile and activities" || $alias_value == "Note 1 - Holding company" || $alias_value == "Note 1 - Change of company name" 
                    || $alias_value == "Note 2 Basis of preparation" || $alias_value == "Note 2 Basis of preparation (1)" || $alias_value == "Note 2.1 - company liquidated" || $alias_value == "Note 2 Basis of preparation (2)"
                    || $alias_value == "Note 2 Use of estimates and judgments" || $alias_value == "Note 2 Use of estimates and judgments (1)"
                    || $alias_value == "Note 2 Functional and presentation currency" || $alias_value == "Note 2.3 Functional and presentation currency - same" || $alias_value == "Note 2.3 Functional and presentation currency - different" 
                    || $alias_value == "Note 2 Foreign currency transactions and balances" || $alias_value == "Note 2 Foreign currency transactions and balances (1)" || $alias_value == "Note 2.4 Company has foreign subsidiaries"
                    || $alias_value == "Note 2.3 - Company has subsidiary" || $alias_value == "Note 2.3 Company change FC" 
                    || $alias_value == "Note 2 Basis of consolidation (title)" || $alias_value == "Note 2 Basis of consolidation (1)"
                    || $alias_value == "Note 2.4 Company has foreign subsidiaries"
                    || $alias_value == "Note 2 - Group accounting (title)" || $alias_value == "Note 2.5 - Group accounting (i)" || $alias_value == "Note 2.5 - Group accounting (ii)" || $alias_value == "Note 2.5 - Group accounting (iii)" || $alias_value == "Note 2.5 - Group accounting (iv)" || $alias_value == "Note 2.5 - Group accounting (v)" || $alias_value == "Note 2.5 - Group accounting (vi)" || $alias_value == "Note 2 - Group accounting (1)" || // sub of section 2 such as "2.5 Group Accounting"
                    $alias_value == "Note 2 - Revenue (title)" || $alias_value == "Note 2 - Revenue (1)" || $alias_value == "Note 2 - Revenue content" || $alias_value == "Note 2 - Revenue (i)" || $alias_value == "Note 2 - Revenue (ii)" || $alias_value == "Note 2 - Revenue (iii)" || $alias_value == "Note 2 - Revenue (iv)" || $alias_value == "Note 2 - Revenue (v)"
                    || $alias_value == "Note 2 - Employee benefits (1)"|| $alias_value == "Note 2 - Employee benefits (i)" || $alias_value == "Note 2 - Employee benefits (ii)" || $alias_value == "Note 2 - Employee benefits (iii)"
                    || $alias_value == "Note 2 - Leases" || $alias_value == "Note 2 - Leases (i)" || $alias_value == "Note 2 - Leases (ii)" || $alias_value == "Note 2 - Leases (1)"
                    || $alias_value == "Note 2 - Borrowing costs (title)" || $alias_value == "Note 2 - Borrowing costs"
                    || $alias_value == "Note 2 - Taxation (title)" || $alias_value == "Note 2 - Taxation"
                    || $alias_value == "Note 2 - Investment in associate and joint ventures (title)" || $alias_value == "Note 2 - Investment in associate and joint ventures"
                    || $alias_value == "Note 2 - Investment in associates" || $alias_value == "Note 2 - Investment in associates (1)"
                    || $alias_value == "Note 2 - Intangible assets (title)" || $alias_value == "Note 2 - Intangible assets" || $alias_value == "Note 2 - Intangible assets (i)" || $alias_value == "Note 2 - Intangible assets (ii)"
                    || $alias_value == "Note 2 - Intangible assets (1)" || $alias_value ==  "Note 2 - Intangible assets (2)" || $alias_value ==  "Note 2 - Intangible assets (3)"
                    || $alias_value == "Note 2 - Investment properties" || $alias_value == "Note 2 - Investment properties (1)" || $alias_value == "Note 2 - Investment properties (Model content)" || $alias_value == "Note 2 - Investment properties (2)"
                    || $alias_value == "Note 2 - Property, plant and equipment" || $alias_value == "Note 2 - Property, plant and equipment (1)" || $alias_value == "Note 2 - Property, plant and equipment (2)"
                    || $alias_value == "Note 2 - Impairment of non-financial assets" || $alias_value == "Note 2 - Impairment of non-financial assets (1)"
                    || $alias_value == "Note 2 - Impairment of assets" || $alias_value == "Note 2 - Impairment of assets (1)"
                    || $alias_value == "Note 2 - Inventories" || $alias_value == "Note 2 - Inventories (1)"
                    || $alias_value == "Note 2 - Trade and other receivables" || $alias_value == "Note 2 - Trade and other receivables (1)"
                    || $alias_value == "Note 2 - Trade payables" || $alias_value == "Note 2 - Trade payables (1)"
                    || $alias_value == "Note 2 - Financial instruments" || $alias_value == "Note 2 - Financial instruments (i)" || $alias_value == "Note 2 - Financial instruments (ii)"
                    || $alias_value == "Note 2 - Impairment of financial assets" || $alias_value == "Note 2 - Impairment of financial assets (1)"
                    || $alias_value == "Note 2 - Provision" || $alias_value == "Note 2 - Provision (1)" || $alias_value == "Note 2 - Provision content"
                    || $alias_value == "Note 2 - Contingencies" || $alias_value == "Note 2 - Contingencies (1)"
                    || $alias_value == "Note 2 - Share capital" || $alias_value == "Note 2 - Share capital (1)"
                    || $alias_value == "Note 2 - Financial guarantee" || $alias_value == "Note 2 - Financial guarantee (1)"
                    || $alias_value == "Note 2 - Convertible redeemable preference shares" || $alias_value == "Note 2 - Convertible redeemable preference shares (1)"
                    || $alias_value == "Note 2 - Cash and cash equivalents" || $alias_value == "Note 2 - Cash and cash equivalents (1)"
                    || $alias_value == "Note 2 - Related party (title)" || $alias_value == "Note 2 - Related party" || $alias_value == "Note 2 - Related party (i)" || $alias_value == "Note 2 - Related party (ii)"
                    || $alias_value == "Note 2 - Significant accounting estimates and judgments" || $alias_value == "Note 2 - Significant accounting estimates and judgments (1)" || $alias_value == "Note 2 - Significant accounting estimates and judgments (i)" || $alias_value == "Note 2 - Significant accounting estimates and judgments (ii)" || $alias_value == "Note 2 - Significant accounting estimates and judgments (iii)" || $alias_value == "Note 2 - Significant accounting estimates and judgments (iv)" || $alias_value == "Note 2 - Significant accounting estimates and judgments (v)"
                    || $alias_value == "Note 3 - Employee benefits expense" || $alias_value == "Note 3 - Employee benefits expense (content)" || $alias_value == "Note 3 - Employee benefits expense (table)"
                    || $alias_value == "Note 4 - Profit before tax" || $alias_value == "Note 4 - Profit before tax (title)" || $alias_value == "Note 4 - Profit before tax (table)"
                    || $alias_value == "Note 5 - Tax expense" || $alias_value == "Note 5 - Tax expense (table_1)" || $alias_value == "Note 5 - Tax expense (textarea 1)" || $alias_value == "Note 5 - Tax expense (1)" || $alias_value == "Note 5 - Tax expense (table_2)" || $alias_value == "Note 5 - Tax expense (content - company has unabsorbed tax losses)" 
                    // || $alias_value == "Note 5 - Tax expense (2)" 
                    || $alias_value == "Note 5 - Tax expense (table_3)" 
                    || $alias_value == "Note 6 - Investment in subsidiaries" || $alias_value == "Note 6 - Investment in subsidiaries (table_1)" 
                    || $alias_value == "Note 6 - Investment in subsidiaries (i)" || $alias_value == "Note 6 - Investment in subsidiaries (i) (table_1)" || $alias_value == "Note 6 - Investment in subsidiaries (i) (1)" || $alias_value == "Note 6 - Investment in subsidiaries (i) (content)"
                    || $alias_value == "Note 6 - Investment in subsidiaries (ii)" || $alias_value == "Note 6 - Investment in subsidiaries (ii) (table_1)" || $alias_value == "Note 6 - Investment in subsidiaries (1)"
                    || $alias_value == "Note 7 - Investment in associates" || $alias_value == "Note 7 - Investment in associates (table_1)" || $alias_value == "Note 7 - Investment in associates (1)" || $alias_value == "Note 7 - Investment in associates (table_2)" || $alias_value == "Note 7 - Investment in associates (2)" || $alias_value == "Note 7 - Investment in associates (table_3)"
                    || $alias_value == "Note 8 - Investment in joint venture" || $alias_value == "Note 8 - Investment in joint venture (1)" 
                    // || $alias_value == "Note 8 - Investment in joint venture (table_1)" || $alias_value == "Note 8 - Investment in joint venture (2)" || $alias_value == "Note 8 - Investment in joint venture (table_2)"
                    || $alias_value == "Note 9 - Intangible assets" || $alias_value == "Note 9 - Intangible assets (table_1)" || $alias_value == "Note 9 - Intangible assets (textarea)" || $alias_value == "Note 9 - Intangible assets (1)"
                    || $alias_value == "Note 10 - Insured benefits" || $alias_value == "Note 10 - Insured benefits (table_1)" || $alias_value == "Note 10 - Insured benefits (1)"
                    || $alias_value == "Note 11 - Investment properties" || $alias_value == "Note 11 - Investment properties cost_model (table_1)" || $alias_value == "Note 11 - Investment properties (1)" || $alias_value == "Note 11 - Investment properties (table_2)" || $alias_value == "Note 11 - Investment properties (2)" || $alias_value == "Note 11 - Investment properties (3)" || $alias_value == "Note 11 - Investment properties (6)" || $alias_value == "Note 11 - Investment properties (7)" || $alias_value == "Note 11 - Investment properties (table_3)"
                    || $alias_value == "Note 12 - Property, plant and equipment" || $alias_value == "Note 12 - Property, plant and equipment - 1" || $alias_value == "Note 12 - Property, plant and equipment (table_1)" || $alias_value == "Note 12 - Property, plant and equipment (1)"
                    || $alias_value == "Note 13 - Available for sale" || $alias_value == "Note 13 - Available for sale (table_1)" || $alias_value == "Note 13 - Available for sale (1)"
                    || $alias_value == "Note 14 - Inventories" || $alias_value == "Note 14 - Inventories (table_1)" || $alias_value == "Note 14 - Inventories (1)"
                    || $alias_value == "Note 15 - Contract assets and contract liabilities" || $alias_value == "Note 15 - Contract assets and contract liabilities (1)" || $alias_value == "Note 15 - Contract assets and contract liabilities (table_1)" || $alias_value == "Note 15 - Contract assets and contract liabilities (2)"
                    || $alias_value == "Note 16 - Trade and other receivables" || $alias_value == "Note 16 - Trade and other receivables (table_1)"  || $alias_value == "Note 16 - Trade and other receivables (1)"  || $alias_value == "Note 16 - Trade and other receivables (2)"  || $alias_value == "Note 16 - Trade and other receivables (table_2)" || $alias_value == "Note 16 - Trade and other receivables (3)" || $alias_value == "Note 16 - Trade and other receivables (4)" || $alias_value == "Note 16 - Trade and other receivables (table_3)" || $alias_value == "Note 16 - Trade and other receivables (6)"  || $alias_value == "Note 16 - Trade and other receivables (table_4)"  || $alias_value == "Note 16 - Trade and other receivables (7)"
                    || $alias_value == "Note 17 - Other currenct assets" || $alias_value == "Note 17 - Other currenct assets (1)"  || $alias_value == "Note 17 - Other currenct assets (table_1)"
                    || $alias_value == "Note 18 - Cash and short-term deposits" || $alias_value == "Note 18 - Cash and short-term deposits (table_1)" || $alias_value == "Note 18 - Cash and short-term deposits (1)" || $alias_value == "Note 18 - Cash and short-term deposits (2)" || $alias_value == "Note 18 - Cash and short-term deposits (table_2)" || $alias_value == "Note 18 - Cash and short-term deposits (3)" || $alias_value == "Note 18 - Cash and short-term deposits (table_3)"
                    || $alias_value == "Note 19 - Share capital" || $alias_value == "Note 19 - Share capital (1)" || $alias_value == "Note 19 - Share capital (2)"
                    || $alias_value == "Note 20 - Other reserves" || $alias_value == "Note 20 - Other reserves (i)" || $alias_value == "Note 20 - Other reserves (ii)" || $alias_value == "Note 20 - Other reserves (iii)" || $alias_value == "Note 20 - Other reserves (iv)" || $alias_value == "Note 20 - Other reserves (v)" || $alias_value == "Note 20 - Other reserves (vi)"
                    || $alias_value == "Note 21 - Deferred tax liabilities" || $alias_value == "Note 21 - Deferred tax liabilities (table_1)" || $alias_value == "Note 21 - Deferred tax liabilities (1)"
                    || $alias_value == "Note 22 - Loans and borrowings" || $alias_value == "Note 22 - Loans and borrowings (table_1)" || $alias_value == "Note 22 - Loans and borrowings (1)" || $alias_value == "Note 22 - Loans and borrowings (table_2)" || $alias_value == "Note 22 - Loans and borrowings (2)" || $alias_value == "Note 22 - Loans and borrowings (3)" || $alias_value == "Note 22 - Loans and borrowings (7)" || $alias_value == "Note 22 - Loans and borrowings (table_3)"
                    || $alias_value == "Note 23 - Provision" || $alias_value == "Note 23 - Provision (table_1)" || $alias_value == "Note 23 - Provision (1)"
                    || $alias_value == "Note 24 - Trade and other payables" || $alias_value == "Note 24 - Trade and other payables (table_1)" || $alias_value == "Note 24 - Trade and other payables (1)" || $alias_value == "Note 24 - Trade and other payables (3)" || $alias_value == "Note 24 - Trade and other payables (table_2)"
                    || $alias_value == "Note 25 - Other current liabilities" || $alias_value == "Note 25 - Other current liabilities (1)" || $alias_value == "Note 25 - Other current liabilities (table_1)"
                    || $alias_value == "Note 26 - Related party transactions" || $alias_value == "Note 26 - Related party transactions (i)" || $alias_value == "Note 26 - Related party transactions (table_1)" || $alias_value == "Note 26 - Related party transactions (ii)" || $alias_value == "Note 26 - Related party transactions (ii) - content"
                    || $alias_value == "Note 27 - Commitments" || $alias_value == "Note 27 - Commitments (i)" || $alias_value == "Note 27 - Commitments (ii)" || $alias_value == "Note 27 - Commitments (ii) (content)" || $alias_value == "Note 27 - Commitments (table_1)" || $alias_value == "Note 27 - Commitments (ii) (1)" || $alias_value == "Note 27 - Commitments (table_2)" || $alias_value == "Note 27 - Commitments (iii)" || $alias_value == "Note 27 - Commitments (iii) (1)" || $alias_value == "Note 27 - Commitments (iii) (content)" || $alias_value == "Note 27 - Commitments (table_3)"
                    || $alias_value == "Note 28 - Contingencies" || $alias_value == "Note 28 - Contingencies (i)" || $alias_value == "Note 28 - Contingencies (i) (1)" || $alias_value == "Note 28 - Contingencies (ii)" || $alias_value == "Note 28 - Contingencies (ii) (1)"
                    || $alias_value == "Note 29 - Financial Risk Management (title)" || $alias_value == "Note 29 - Financial Risk Management" || $alias_value == "Note 29.1 - Financial Risk Management (1)" || $alias_value == "Note 29.1 - Financial Risk Management (2)" || $alias_value == "Note 29.1 - Financial Risk Management (table_1)" || $alias_value == "Note 29.1 - Financial Risk Management (3)" || $alias_value == "Note 29.1 - Financial Risk Management (table_2)" || $alias_value == "Note 29.1 - Financial Risk Management (4)" || $alias_value == "Note 29.1 - Financial Risk Management (5)" || $alias_value == "Note 29.1 - Financial Risk Management (6)" || $alias_value == "Note 29.1 - Financial Risk Management (7)" || $alias_value == "Note 29.2 - Financial Risk Management (1)" || $alias_value == "Note 29.2 - Financial Risk Management (table_1)" 

                    || $alias_value == "Note 29.3 - Financial Risk Management (1)" || $alias_value == "Note 29.3 - Financial Risk Management (2)" || $alias_value == "Note 29.3 - Financial Risk Management (3)" || $alias_value == "Note 29.3 - Financial Risk Management (4)" || $alias_value == "Note 29.3 - Financial Risk Management (5)" 

                    || $alias_value == "Note 29.4 - Financial Risk Management (1)" || $alias_value == "Note 29.4 - Financial Risk Management (table_1) (group)" || $alias_value == "Note 29.4 - Financial Risk Management (table_1) (company)" || $alias_value == "Note 29.4 - Financial Risk Management (2)" || $alias_value == "Note 29.4 - Financial Risk Management (table_2)" || $alias_value == "Note 29.4 - Financial Risk Management (3)"
                    || $alias_value == "Note 30 - Fair Value of assets" || $alias_value == "Note 30 - Fair Value of assets (i)" || $alias_value == "Note 30 - Fair Value of assets (table_1)"
                    || $alias_value == "Note 31 - Financial Instrument by category (title)" || $alias_value == "Note 31 - Financial Instrument by category (1)" || $alias_value == "Note 31 - Financial Instrument by category (table_1)"
                    || $alias_value == "Note 32 - Capital Management" || $alias_value == "Note 32 - Capital Management (1)" || $alias_value == "Note 32 - Capital Management (table_1)" || $alias_value == "Note 32 - Capital Management (2)"
                    || $alias_value == "Note 33 - Events occuring after the reporting period" || $alias_value == "Note 33 - Events occuring after the reporting period (1)"
                    || $alias_value == "Note 34 - Comparative Figures" || $alias_value == "Note 34 - Comparative Figures (1)"
                    || $alias_value == "Note 35 - Prior year adjustment" || $alias_value == "Note 35 - Prior year adjustment (table_1)" || $alias_value == "Note 35 - Prior year adjustment (1)"
                    || $alias_value == "Note 36 - Loss per ordinary share" || $alias_value == "Note 36 - Loss per ordinary share (1)" || $alias_value == "Note 36 - Loss per ordinary share (table_1)"
                    || $alias_value == "Note 37 - Segmental Reporting" || $alias_value == "Note 37 - Segmental Reporting (1)" || $alias_value == "Note 37 - Segmental Reporting (2)" || $alias_value == "Note 37 - Segmental Reporting (table_1)"
                    || $alias_value == "Note 38 - Going concern" || $alias_value == "Note 38 - Going concern (1)"
                    || $alias_value == "Note 39 - Authorization of financial statements" || $alias_value == "Note 39 - Authorization of financial statements (1)"
                    /* for new line to separate into 2 tables */
                    || $alias_value == "table new line"
                    /* end of for new line to separate into 2 tables */
                    || $alias_value == "main category display" 
                    || in_array($alias_value, $this->get_fs_note_title_json()->audit_report)
                    )
                    {
                        // $q2 = $this->db->query('SELECT lyt.id, lytd.section_name, lytd.document_layout
                        //                 FROM fs_ntfs_layout_template lyt
                        //                 LEFT JOIN fs_ntfs_layout_template_default lytd ON lyt.fs_ntfs_layout_template_default_id = lytd.id
                        //                 WHERE lyt.fs_company_info_id=' . $fs_company_info_id . ' AND lyt.is_checked = 1 ORDER BY lyt.order_by');

                        // if($alias_value == "table new line")
                        // {
                        //     print_r('Note 29.1');
                        // }

                        $hide_needed = $this->note_show_hide_part($alias_value, $fs_company_info_id);

                        // if($alias_value == "Note 2 - Significant accounting estimates and judgments")
                        // {
                        //     // print_r("Note 2 - Leases (i) - " . $hide_needed);
                        //     print_r("Note 2 - Significant accounting estimates and judgments");
                        // }

                        // print_r(array($alias_value));
                    }
                    else
                    {
                        $hide_needed = false;
                    }
                    /* ----------------------------------- END OF change show / hide content here ------------------------------------------*/

                    // $replaced_wsdt_xml = $wsdt_xml; // temporary save wsdt template
                    // $replaced_wsdt_xml = $this->un_comment_numbering($wsdt_xml, $hide_needed);

                    // remove / add numbering tag for main title such as 3, 4, 5, 6 ...
                    if(in_array($alias_value, $this->get_fs_note_title_json()->note_title))
                    {   
                        if(empty($numPr_tag_ids))
                        {
                            $note_title_key = array_search($alias_value, $this->get_fs_note_title_json()->note_title, true);

                            if($note_title_key == 0) // surely get 1. Domicile and activities
                            {
                                $note_title_numPr_tag_ids = $this->get_part_of_template('<w:numPr>', 'w:numPr', $replaced_wsdt_xml);
                                $note_title_numPr_tag_ids = $note_title_numPr_tag_ids[0][0];
                            }
                        }

                        $replaced_wsdt_xml = $this->un_comment_numbering($replaced_wsdt_xml, $note_title_numPr_tag_ids, $hide_needed);
                    }

                    // remove / add numbering tag for sub title such as 2.2, 2.3, 2.4, 2.5 ...
                    // if(in_array($alias_value, $this->get_fs_note_title_json()->sub_note_title))
                    // {
                    //     if(empty($numPr_tag_ids))
                    //     {
                    //         $title_key = array_search($alias_value, $this->get_fs_note_title_json()->sub_note_title, true);

                    //         if($title_key == 1) // surely get 2.1 
                    //         {
                    //             $numPr_tag_ids = $this->get_part_of_template('<w:numPr>', 'w:numPr', $replaced_wsdt_xml);
                    //             $numPr_tag_ids = $numPr_tag_ids[0][0];
                    //         }
                    //     }

                    //     $replaced_wsdt_xml = $this->un_comment_numbering($replaced_wsdt_xml, $numPr_tag_ids, $hide_needed);
                    // }
                    
                    // $replaced_wsdt_xml = $this->un_comment_keepNext_keepLines($replaced_wsdt_xml, $hide_needed);

                    if($hide_needed == false)
                    {
                        $wsdt_inner = $this->get_template_nested_inner_content($replaced_wsdt_xml);

                        /* ***************************************** If and else got duplicate code (same process way but input different) ****************************************************** */
                        if(count($wsdt_inner[0]) > 0)   // for tag that has inner tag (Inside Quick part got tag)
                        {
                            // print_r(array($alias_value));
                            // print_r(array($wsdt_inner[0]));
                            // if($alias_value == "Note 2 - Revenue (ii)")
                            // {
                            //     // print_r(array($replaced_wsdt_xml));
                            // }

                            /* show/hide section */
                            foreach($wsdt_inner[0] as $wsdt_inner_key => $wsdt_inner_content)
                            {   
                                $wrPr_listTemplate = $this->get_part_of_template('<w:rPr>', 'w:rPr', $wsdt_inner_content);

                                /* insert or remove <vanish/> to show or hide content */
                                foreach ($wrPr_listTemplate[0] as $wrPr_key => $wrPr) 
                                {
                                    $removed_wrPr = preg_replace('/<w:rPr>/', '', $wrPr, 1);
                                    $removed_wrPr = preg_replace('/\<\/w:rPr>+$/', '', $removed_wrPr);  // remove w:rPr

                                    $replaced_wrPr = $this->insert_vanish_tag($removed_wrPr, $hide_needed);    

                                    $replaced_wsdt_xml = str_replace($wrPr, $replaced_wrPr, $replaced_wsdt_xml);

                                    if(!$hide_needed)
                                    {
                                        $removed_wsdt = preg_replace('/<w:sdt>/', '', $replaced_wsdt_xml, 1);
                                        $removed_wsdt = preg_replace('/\<\/w:sdt>+$/', '', $removed_wsdt);

                                        $wsdt_nested = $this->get_part_of_template('<w:sdt>', 'w:sdt', $removed_wsdt);

                                        // print_r($wsdt_nested);

                                        // update tag 
                                        foreach ($wsdt_nested[0] as $inner_wsdt_key => $inner_wsdt) 
                                        {
                                            $alias_inner_tag = $this->get_tag('w:alias', $inner_wsdt);
                                            
                                            $alias_inner_value = $this->get_attribute_value('w:val', $alias_inner_tag[0][0]);   // get w:value attribute

                                            if(count($alias_inner_value) > 0)    // if got w:alias
                                            {
                                                $replaced_wsdt_xml = $this->update_wsdt_xml_code($replaced_wsdt_xml, $inner_wsdt, $alias_inner_value[1], $fs_company_info_id); 
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        else
                        {
                            // print_r(array($alias_value));

                            // replace note title with account name
                            if(in_array($alias_value, $note_title_account_code_note_list))
                            {
                                // print_r(array($used_add_note_list_account_code));
                                $ntac_key = array_search($alias_value, $note_title_account_code_note_list);

                                $used_add_note_list_key = array_search($note_title_account_code_list[$ntac_key]['account_code'], $used_add_note_list_account_code);

                                if(!empty($used_add_note_list_key) || (string)$used_add_note_list_key == '0')
                                {
                                    $replaced_wsdt_xml = $this->replace_all_wr_in_wsdtContent($replaced_wsdt_xml, strtoupper($used_add_note_list[$used_add_note_list_key]['description']));
                                }
                            }

                            // if($alias_value == "Note 2 - Provision content" || $alias_value == "Note 2 - Investment properties (Model content)" || $alias_value == "Opinion" || $alias_value == "Opinion 2" || $alias_value == "Opinion content" || $alias_value == "Basis for opinion - content" || $alias_value == "Key audit matter" || $alias_value == "Other information" || $alias_value == "Other information content" || $alias_value == "Report on Other Legal and Regulatory Requirements")  
                            if(in_array($alias_value, $this->get_fs_note_title_json()->textarea_input_tag))
                            {
                                $replaced_wsdt_xml = $this->replace_textarea_wsdtContent($replaced_wsdt_xml, $alias_value, $fs_company_info_id);
                            }
                            elseif(in_array($alias_value, $this->get_fs_note_title_json()->page_break_tags))    // page break
                            {
                                $replaced_wsdt_xml = $this->remove_insert_page_break($replaced_wsdt_xml, $alias_value, $fs_company_info_id);
                            }
                            elseif(in_array($alias_value, $this->get_fs_note_title_json()->section_page_break_tags))    // section page break
                            {
                                $replaced_wsdt_xml = $this->remove_insert_section_page_break($replaced_wsdt_xml, $alias_value, $fs_company_info_id, $additional_info);
                            }
                            else
                            {
                                $wrPr_listTemplate = $this->get_part_of_template('<w:rPr>', 'w:rPr', $replaced_wsdt_xml);

                                /* insert or remove <vanish/> to show or hide content */
                                foreach ($wrPr_listTemplate[0] as $wrPr_key => $wrPr) 
                                {
                                    $removed_wrPr = preg_replace('/<w:rPr>/', '', $wrPr, 1);
                                    $removed_wrPr = preg_replace('/\<\/w:rPr>+$/', '', $removed_wrPr);  // remove w:rPr

                                    $replaced_wrPr = $this->insert_vanish_tag($removed_wrPr, $hide_needed);    

                                    $replaced_wsdt_xml = str_replace($wrPr, $replaced_wrPr, $replaced_wsdt_xml);

                                    if(!$hide_needed)
                                    {
                                        $removed_wsdt = preg_replace('/<w:sdt>/', '', $replaced_wsdt_xml, 1);
                                        $removed_wsdt = preg_replace('/\<\/w:sdt>+$/', '', $removed_wsdt);

                                        $wsdt_nested = $this->get_part_of_template('<w:sdt>', 'w:sdt', $removed_wsdt);

                                        // update tag 
                                        foreach ($wsdt_nested[0] as $inner_wsdt_key => $inner_wsdt) 
                                        {
                                            $alias_inner_tag = $this->get_tag('w:alias', $inner_wsdt);
                                            
                                            $alias_inner_value = $this->get_attribute_value('w:val', $alias_inner_tag[0][0]);   // get w:value attribute

                                            if(count($alias_inner_value) > 0)    // if got w:alias
                                            {
                                                $replaced_wsdt_xml = $this->update_wsdt_xml_code($replaced_wsdt_xml, $inner_wsdt, $alias_inner_value[1], $fs_company_info_id);  
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        
                        $replaced_wsdt_xml = $this->remove_wsdt_tag($replaced_wsdt_xml, $additional_info);
                        $updated_xml = str_replace($wsdt_xml, $replaced_wsdt_xml, $updated_xml);    // update xml code
                    }
                    else
                    {
                        $replaced_wsdt_xml = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_wsdt_xml, $additional_info['generate_docs_without_tags'], 1);
                        $replaced_wsdt_xml = $this->remove_tbl_tags_row($replaced_wsdt_xml, $additional_info, $table_name);
                        $updated_xml = str_replace($wsdt_xml, $replaced_wsdt_xml, $updated_xml);    // update xml code
                    }
                }
                else
                {
                    $alias_tag = $this->get_tag('w:alias', $wsdt_xml);

                    if(count($alias_tag[0]) > 0)    // if got w:alias
                    {
                        $alias_value = $this->get_attribute_value('w:val', $alias_tag[0][0]);   // get w:value attribute
                        $updated_xml = $this->build_xml_code_word($updated_xml, $wsdt_xml, $alias_value[1], $fs_company_info_id, $additional_info);
                    }
                }
            }

            // $updated_xml = $this->update_table($updated_xml, $fs_company_info_id);
        }
        // else
        // {
        // $updated_xml = $this->update_table($updated_xml, $fs_company_info_id, $additional_info);
        // $updated_xml = $this->convert_special_character($updated_xml);
        // }


        return $updated_xml;
    }

    public function get_template_wsdt($original_xml)
    {
        $wsdt_template = $this->get_part_of_template('<w:sdt', 'w:sdt', $original_xml);

        return $wsdt_template;
    }

    public function get_template_wsdtcontent($wsdt_xml)
    {
        $wsdtContent_template = $this->get_part_of_template('<w:sdtContent', 'w:sdtContent', $wsdt_xml);

        return $wsdtContent_template;
    }

    public function get_template_wsdtPrContent($wsdt_xml)
    {
        $wsdtPrContent_template = $this->get_part_of_template('<w:sdtPr>', 'w:sdtPr', $wsdt_xml);

        return $wsdtPrContent_template;
    }

    public function get_template_wrPrContent($wsdtPrContent)
    {
        $wrPrContent_template = $this->get_part_of_template('<w:rPr>', 'w:rPr', $wsdtPrContent);

        if(isset($wrPrContent_template[1][0]))
        {
            $wrPrContent_template[1][0] = str_replace('<w:rPr>', '', $wrPrContent_template[1][0]);
            $wrPrContent_template[1][0] = str_replace('</w:rPr>', '', $wrPrContent_template[1][0]);
        }

        return $wrPrContent_template;
    }

    public function get_template_wr($wsdt_xml_content)
    {
        preg_match_all ('/<w:r(?=\s|>)(?!(?:[^>=]|=([""])(?:(?!\1).)*\1)*?\shref=[""])[^>]*>(.*?)<\/w:r>/s', $wsdt_xml_content, $taken_template);

        return $taken_template;
    }

    public function loop_template($wsdtcontent, $alias_tag, $wr_arr, $fs_company_info_id)
    {
        $new_wsdtcontent = $wsdtcontent;

        foreach ($wr_arr as $wr_key => $wr_content) // loop all w:r
        {
            $updated_wr_content = $wr_content;

            $wrPrContent = $this->get_template_wrPrContent($wr_content);
            $replaced_wrPrContent = $this->vanish_content($alias_tag, $wrPrContent[1][0], $fs_company_info_id); 

            if($wr_key == 0)
            {
                if($alias_tag == "tense s" || $alias_tag == "sing/plu s")
                {
                    $updated_wr_content = str_replace($wrPrContent[0][0], $replaced_wrPrContent, $wr_content);
                }
                elseif($alias_tag == "title, directors signatures")
                {
                    $updated_wr_content = str_replace($wrPrContent[0][0], $replaced_wrPrContent, $wr_content);
                }
                elseif($alias_tag == "audited" || $alias_tag == "consolidated") // to keep the 'space' tag
                {
                    $updated_wr_content   = str_replace($wrPrContent[0][0], $replaced_wrPrContent, $wr_content);
                }

                $wt_template = $this->get_part_of_template('<w:t>', 'w:t', $updated_wr_content);    // get w:t (Display text)
                $wp_content = $this->get_part_of_template('<w:p>', 'w:p', $wsdtcontent);    // get w:p

                $replace_wr = $this->retrieve_xml_data($alias_tag, $fs_company_info_id, $wt_template, $updated_wr_content, $wp_content); // Content section to modify
            }
            elseif($wr_key == 1)
            {
                if($alias_tag == "audited" || $alias_tag == "consolidated") // for this case, their text w:t start from 2nd w:r because 1st w:r is a 'space'
                {
                    $updated_wr_content   = str_replace($wrPrContent[0][0], $replaced_wrPrContent, $wr_content);

                    $wt_template = $this->get_part_of_template('<w:t>', 'w:t', $updated_wr_content);    // get w:t (Display text)
                    $wp_content = $this->get_part_of_template('<w:p>', 'w:p', $wsdtcontent);    // get w:p

                    $replace_wr .= $this->retrieve_xml_data($alias_tag, $fs_company_info_id, $wt_template, $updated_wr_content, $wp_content); // Content section to modify
                }
            }

            $original_wr_content .= $wr_content;    // save the original code to replace all later
        }

        if(!empty($replace_wr))
        {
            $new_wsdtcontent = str_replace($original_wr_content, $replace_wr, $wsdtcontent);
        }

        return $new_wsdtcontent;
    }

    public function update_wsdt_xml_code($wsdt_xml, $inner_wsdt, $alias_tag, $fs_company_info_id)
    {
        $original_wr_content = '';
        $replace_wr_content  = '';

        $alias_tag = $alias_tag[0];

        $inner_wsdtcontent = $this->get_template_wsdtcontent($inner_wsdt);
        $wr_arr = $this->get_template_wr($inner_wsdtcontent[0][0]);

        $new_wsdt_xml = $wsdt_xml;

        // print_r(array($inner_wsdtcontent[0][0], $wr_arr[0]));

        // if($alias_tag == "tense s")
        // {
        //     print_r(array($wsdt_xml));
        // }

        $new_wsdtcontent = $this->loop_template($inner_wsdtcontent[0][0], $alias_tag, $wr_arr[0], $fs_company_info_id);

        if(!empty($new_wsdtcontent))
        {
            $replaced_inner_wsdt = str_replace($inner_wsdtcontent[0][0], $new_wsdtcontent, $inner_wsdt);    // tag inside quick part such as "tense s"

            $new_wsdt_xml = str_replace($inner_wsdt, $replaced_inner_wsdt, $wsdt_xml);
        }

        // if($alias_tag == "tense s")
        // {
        //     print_r(array($alias_tag, $new_wsdt_xml));
        // }

        return $new_wsdt_xml;
    }

    public function build_xml_code_word($updated_xml, $wsdt_xml, $alias_tag, $fs_company_info_id, $additional_info)
    {
        $original_wr_content = '';
        $replace_wr_content = '';

        $alias_tag = $alias_tag[0];

        $wsdtcontent = $this->get_template_wsdtcontent($wsdt_xml);

        $wr_arr = $this->get_template_wr($wsdtcontent[0][0]);
        $new_wsdtcontent = $this->loop_template($wsdtcontent[0][0], $alias_tag, $wr_arr[0], $fs_company_info_id); 

        if(!empty($new_wsdtcontent))
        {
            $replaced_wsdt_xml = str_replace($wsdtcontent[0][0], $new_wsdtcontent, $wsdt_xml);

            if($alias_tag != "Table name")
            {
                $replaced_wsdt_xml = $this->remove_wsdt_tag($replaced_wsdt_xml, $additional_info);
            }

            $updated_xml = str_replace($wsdt_xml, $replaced_wsdt_xml, $updated_xml);
        }

        return $updated_xml;
    }

    public function xml_code_template($type)
    {
        $template = '';

        if($type['br'])
        {
            $br = '<w:br/>';
        }
        
        if($type['bold'])
        {
            $bold = '<w:b/><w:bCs/>';
        }
        
        if($type['italic'])
        {
            $italic = '<w:i/><w:iCs/>';
        }

        if(!empty($type['underline']))
        {
            $underline = '<w:u w:val="single"/>';
        }
        
        if($type['text'])
        {
            $text = '<w:t>' . $type['text_content'] . '</w:t>';
        }

        if(!empty($type['wrsidR']))
        {
            $wrsidR = ' w:rsidR="' . $type['wrsidR'] . '"';
        }

        if(!empty($type['wrsidRPr']))
        {
            $wrsidRPr = ' w:rsidRPr="' . $type['wrsidRPr'] . '"';
        }

        if(!empty($type['wsz']))
        {
            $wsz = $type['wsz'];
        }
        else
        {
            $wsz = '<w:sz w:val="22"/>';
        }

        if(!empty($type['wszCs']))
        {
            $wszCs = $type['wszCs'];
        }
        else
        {
            $wszCs = '<w:szCs w:val="22"/>';
        }
      
        /* -- Reference --
            <w:r w:rsidR="006073C2">
                <w:rPr>
                    <w:rFonts w:ascii="Calibri" w:hAnsi="Calibri" w:cs="Calibri"/>
                    <w:b/>
                    <w:bCs/>
                    <w:sz w:val="22"/>
                    <w:szCs w:val="22"/>
                </w:rPr>
                <w:br/>
            </w:r>

        -- END OF Reference -- */
        $template .= '<w:r' . $wrsidR . $wrsidRPr . '>' .
                        '<w:rPr>' .
                            '<w:rFonts w:ascii="Calibri" w:hAnsi="Calibri" w:cs="Calibri"/>' .
                            $bold . 
                            $italic .
                            $wsz .
                            $wszCs .
                            $underline .
                        '</w:rPr>' . 
                        $br . $text .  
                    '</w:r>';

        return $template;
    }

    public function vanish_template($template, $insert_vanish_tag)
    {
        $updated_template = $template;

        $wrPrContent = $this->get_template_wrPrContent($updated_template);

        // print_r(array($wrPrContent));

        if($insert_vanish_tag)
        {
            foreach ($wrPrContent[0] as $key => $value) 
            {
                if(strpos($value, '<w:vanish/>') == false)
                {
                    $wrFonts_tag = $this->get_tag('w:sz', $value);

                    $replaced_wrPrContent = str_replace($wrFonts_tag[0][0], '<w:vanish/>' . $wrFonts_tag[0][0], $value);
                    $updated_template = str_replace($value, $replaced_wrPrContent, $updated_template);
                }
            }
        }
        else
        {
            foreach ($wrPrContent[0] as $key => $value) 
            {
                if(strpos($value, '<w:vanish') !== false)
                {
                    $updated_template = str_replace('<w:vanish/>', '', $updated_template);
                }
            }
        }

        return $updated_template;
    }

    public function vanish_content($alias_tag, $wrPrInnerContent, $fs_company_info_id)
    {
        // $vanish_xml_code = '<w:vanish/>';
        
        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $client_info = $this->db->query("SELECT * FROM client WHERE company_code='" . $fs_company_info[0]["company_code"] . "'");
        $client_info = $client_info->result_array();

        $directors = $this->fs_model->get_fs_dir_statement_director($fs_company_info_id);

        $isPlural = count($directors) > 1? true: false;

        if($alias_tag == "tense s")
        {
            if($isPlural)
            {
                // print_r(array('tense s - plural', $wrPrInnerContent));
                $wrPr = $this->insert_vanish_tag($wrPrInnerContent, 1);
            }
            else
            {
                // print_r(array('tense s - singular', $wrPrInnerContent));
                $wrPr= $this->insert_vanish_tag($wrPrInnerContent, 0);

                // print_r(array('line - 1021', $wrPr));
            }

            // print_r(array('tense s', $wrPr));
        }
        elseif($alias_tag == "sing/plu s")
        {
            if($isPlural)
            {
                // print_r(array('sing/plu s - plural', $wrPrInnerContent));
                $wrPr = $this->insert_vanish_tag($wrPrInnerContent, 0);
            }
            else
            {
                // print_r(array('sing/plu s - singular', $wrPrInnerContent));
                $wrPr= $this->insert_vanish_tag($wrPrInnerContent, 1);
            }

            // print_r(array('sing/plu s', $wrPr));
        }
        elseif($alias_tag == "title, directors signatures")
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

            if(count($directors) == 1)
            {
                $wrPr= $this->insert_vanish_tag($wrPrInnerContent, 1);

            }
            else
            {
                $wrPr= $this->insert_vanish_tag($wrPrInnerContent, 0);
            }
        }
        elseif($alias_tag == "audited")
        {
            if($fs_company_info[0]['is_audited'] == 1)
            {
                $wrPr = $this->insert_vanish_tag($wrPrInnerContent, 0);
            }
            else
            {
                $wrPr = $this->insert_vanish_tag($wrPrInnerContent, 1);
            }
        }
        elseif($alias_tag == "consolidated")
        {
            if($fs_company_info[0]['is_group_consolidated'] == 1)
            {
                $wrPr = $this->insert_vanish_tag($wrPrInnerContent, 0);
            }
            else
            {
                $wrPr = $this->insert_vanish_tag($wrPrInnerContent, 1);
            }
        }

        // print_r(array('<w:rPr>' . $wrPrInnerContent . '</w:rPr>', $wrPr));

        // return '<w:rPr>' . $wrPrInnerContent . '</w:rPr>';

        $wrPr = str_replace('<w:rPr><w:rPr>', '<w:rPr>', $wrPr);
        $wrPr = str_replace('</w:rPr></w:rPr>', '</w:rPr>', $wrPr);

        // print_r(array($wrPr));

        return $wrPr;
    }

    public function vanish_content_paragraph($xml_content)
    {
        $replaced_xml_content = $xml_content;

        // $wrPr = $this->insert_vanish_tag($replaced_xml_content, 1);

        $wrFonts_tag = $this->get_tag('w:sz', $replaced_xml_content);

        // print_r(array($wrFonts_tag));

        $wsz_wszCs = [];

        // collect all different <w:sz w:val="22"/> tags.
        foreach ($wrFonts_tag[0] as $key => $value) 
        {   
            if(!in_array($value, $wsz_wszCs))
            {
                array_push($wsz_wszCs, $value);
            }
        }

        // print_r($wsz_wszCs);
        $replaced_xml_content = str_replace('<w:vanish/>', '', $replaced_xml_content);

        foreach ($wsz_wszCs as $key => $value) 
        {
            if (strpos('w:szCs', $value) == FALSE) 
            { 
                $replaced_xml_content = str_replace($value, '<w:vanish/>' . $value, $replaced_xml_content);
            }
        }

        // $replaced_xml_content = str_replace($wrFonts_tag[0][0], '<w:vanish/>' . $wrFonts_tag[0][0], $replaced_xml_content);
        
        return $replaced_xml_content;
    }

    public function insert_vanish_tag($wrPrInnerContent, $insert_vanish_tag)
    {
        if($insert_vanish_tag)
        {
            if(strpos($wrPrInnerContent, '<w:vanish/>') == false)
            {
                $wrFonts_tag = $this->get_tag('w:sz', $wrPrInnerContent);
                $wrPrInnerContent = str_replace($wrFonts_tag[0][0], '<w:vanish/>' . $wrFonts_tag[0][0], $wrPrInnerContent);
            }
        }
        else
        {
            if(strpos($wrPrInnerContent, '<w:vanish') !== false)
            {
                $wrPrInnerContent = str_replace('<w:vanish/>', '', $wrPrInnerContent);
            }
        }

        $wrPrInnerContent = str_replace("<w:rPr>", "", $wrPrInnerContent);
        $wrPrInnerContent = str_replace("</w:rPr>", "", $wrPrInnerContent);

        return '<w:rPr>' . $wrPrInnerContent . '</w:rPr>';
    }

    public function un_comment_numbering($wsdt_xml, $numPr_template, $is_commented)
    {
        $replaced_wsdt_xml = $wsdt_xml;

        // print_r(array($numPr_template));

        if($is_commented)
        {
            // Remove numbering tag. <w:numPr>
            $wnumPr_template = $this->get_part_of_template('<w:numPr>', 'w:numPr', $replaced_wsdt_xml);

            // print_r($wnumPr_template);

            foreach($wnumPr_template[0] as $wnumPr_key => $wnumPr)
            {
                // if(!strpos($replaced_wsdt_xml, '<!-- ' . $wnumPr . ' -->'))
                // {
                    // $replaced_wsdt_xml = str_replace($wnumPr, '<!-- ' . $wnumPr . ' -->', $replaced_wsdt_xml);
                // }

                $replaced_wsdt_xml = str_replace($wnumPr, '', $replaced_wsdt_xml);
            }

            // Comment tags that related to numbering tag. <w:keepNext> & <w:keepLines>
            // if(!strpos($replaced_wsdt_xml, '<!-- <w:keepNext/> -->'))
            // {
                $replaced_wsdt_xml = str_replace('<w:keepNext/>', '', $replaced_wsdt_xml);
            // }

            // if(!strpos($replaced_wsdt_xml, '<!-- <w:keepLines/> -->'))
            // {
                $replaced_wsdt_xml = str_replace('<w:keepLines/>', '', $replaced_wsdt_xml);
            // }
        }
        else
        {
            // Uncomment numbering tag. <w:numPr>
            // preg_match_all ('/<!-- <w:numPr>(.+?)<\/w:numPr> -->/s', $replaced_wsdt_xml, $wnumPr_template);
            // preg_match_all ('/<w:pStyle w:val="ListParagraph"/>/s', $replaced_wsdt_xml, $wnumPr_template);

            // foreach($wnumPr_template[0] as $wnumPr_key => $wnumPr)
            // {
            //     // print_r($wnumPr);
            //     $replaced_wsdt_xml = str_replace('<!-- ', '', $replaced_wsdt_xml);
            //     $replaced_wsdt_xml = str_replace('-->', '', $replaced_wsdt_xml);

            //     // echo $replaced_wsdt_xml;
            // }

            // // Uncomment tags that related to numbering tag. <w:keepNext> & <w:keepLines>
            // $replaced_wsdt_xml = str_replace('<!-- <w:keepNext/> -->', '<w:keepNext/>', $replaced_wsdt_xml);
            // $replaced_wsdt_xml = str_replace('<!-- <w:keepLines/> -->', '<w:keepLines/>', $replaced_wsdt_xml);

            $replaced_wsdt_xml = str_replace('<w:pStyle w:val="ListParagraph"/>', '<w:pStyle w:val="ListParagraph"/>' . $numPr_template . '<w:keepNext/>' . '<w:keepLines/>', $replaced_wsdt_xml);
        }
        
        return $replaced_wsdt_xml;
    }

    public function un_comment_keepNext_keepLines($wsdt_xml, $is_commented)
    {
        $replaced_wsdt_xml = $wsdt_xml;

        if($is_commented)
        {
            if(!strpos($replaced_wsdt_xml, '<!-- <w:keepNext/> -->'))
            {
                $replaced_wsdt_xml = str_replace('<w:keepNext/>', '<!-- <w:keepNext/> -->', $replaced_wsdt_xml);
            }

            if(!strpos($replaced_wsdt_xml, '<!-- <w:keepLines/> -->'))
            {
                $replaced_wsdt_xml = str_replace('<w:keepLines/>', '<!-- <w:keepLines/> -->', $replaced_wsdt_xml);
            }
        }
        else
        {
            $replaced_wsdt_xml = str_replace('<!-- <w:keepNext/> -->', '<w:keepNext/>', $replaced_wsdt_xml);
            $replaced_wsdt_xml = str_replace('<!-- <w:keepLines/> -->', '<w:keepLines/>', $replaced_wsdt_xml);
        }

        return $replaced_wsdt_xml;
    }

    public function is_quick_part($wsdt_xml)
    {
        $wsdtPr = $this->get_part_of_template('<w:sdtPr>', 'w:sdtPr', $wsdt_xml);
        $wsdtPr = $wsdtPr[0][0];

        preg_match_all('/<w:docPartGallery w:val="Quick Parts"\/>/s', $wsdtPr, $quickParts);

        // print_r(empty($quickParts[0][0]));

        if(!empty($quickParts[0][0]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /* ------------------------------------------- GENERAL USE ------------------------------------------- */
    public function get_part_of_template_include_nested($tagType, $template)
    {
        // if(strpos($template, $related_part) !== false)
        // {
            // preg_match_all ('/' . $related_part . '(.+?)<\/' . $tagType . '>/s', $template, $taken_template);
            // preg_match_all ('/<w:sdt\b[^>]*>(?:(?:<w:sdt>.*?<\/w:sdt>)|(?:(?=([^<]+))\1|<(?!w:sdt\b[^>]*>)))*?<\/w:sdt>/sig', $template, $taken_template);
            // ini_set('pcre.backtrack_limit', '1048576');
            // ini_set('pcre.recursion_limit', '134217');
            // ini_set('memory_limit','16M');

            preg_match_all('/<' . $tagType . '\b[^>]*>(?:(?:<' . $tagType . '>.*?<\/' . $tagType . '>)|(?:(?=([^<]+))\1|<(?!' . $tagType . '\b[^>]*>)))*?<\/' . $tagType . '>/s', $template, $taken_template);

            // print_r($taken_template);

            return $taken_template;
        // }
        // else
        // {
            // return '';
        // }
    }

    public function get_template_nested_inner_content($template)
    {
        $template = preg_replace('/<w:sdt>/', '', $template, 1);
        $template = preg_replace('/\<\/w:sdt>+$/', '', $template);

        preg_match_all('/<w:sdt\b[^>]*>(?:(?:<w:sdt>.*?<\/w:sdt>)|(?:(?=([^<]+))\1|<(?!w:sdt\b[^>]*>)))*?<\/w:sdt>/s', $template, $taken_template);

        return $taken_template;
    }

    public function get_template_wsdtcontent_nested($template)
    {
        // $template = preg_replace('/<w:sdt>/', '', $template, 1);
        // $template = preg_replace('/\<\/w:sdt>+$/', '', $template);

        preg_match_all('/<w:sdtContent\b[^>]*>(?:(?:<w:sdtContent>.*?<\/w:sdtContent>)|(?:(?=([^<]+))\1|<(?!w:sdtContent\b[^>]*>)))*?<\/w:sdtContent>/s', $template, $taken_template);

        return $taken_template;
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

    public function get_content_tag_with_without_attribute($tagName, $wsdt_xml_content)
    {
        preg_match_all ('/<' . $tagName . '(?=\s|>)(?!(?:[^>=]|=([""])(?:(?!\1).)*\1)*?\shref=[""])[^>]*>(.*?)<\/' . $tagName . '>/s', $wsdt_xml_content, $taken_template);

        return $taken_template;
    }

    public function get_tag($related_part, $template)
    {
        if(strpos($template, '<' . $related_part) !== false)
        {   
            // match like <tag attribute="example'>
            preg_match_all ('/<' . $related_part . '([^>]+)\/>/', $template, $taken_template);
            
            // print_r($taken_template);

            return $taken_template;
        }
        else
        {
            return '';
        }
    }

    public function get_attribute_value($attribute_name, $template)
    {
        if(strpos($template, $attribute_name) !== false)
        {   
            // match like <tag attribute="example'>
            preg_match_all ('/' . $attribute_name . '="(.*?)"/', $template, $taken_template);
            
            return $taken_template;
        }
        else
        {
            return '';
        }
    }

    public function get_hidden_wsdt_tbl($template)  // get table code by table name. 
    {
        preg_match_all ('/<w:sdt>.*?w:val="Table name".*?<\/w:sdt>/s', $template, $taken_template);
            
        return $taken_template;
    }

    public function get_tbl_tr_template($tbl_template)
    {
        preg_match_all('/<w:tr.*?<\/w:tr>/s', $tbl_template, $taken_template);

        return $taken_template;
    }

    public function random_paraId_num($template)    // tr template, change the paraId
    {
        $updated_template = $template;

        // change w14:paraId
        preg_match_all ('/w14:paraId="(.*?)" /', $template, $taken_paraId);

        // print_r($taken_paraId);

        foreach ($taken_paraId[0] as $key => $value) 
        {
            $random_alphanumeric = $this->fs_replace_content_model->rand_string_without_special_char(8);

            $updated_template = str_replace($taken_paraId[1][$key], $random_alphanumeric, $updated_template);

            // $updated_template = str_replace($value, '', $updated_template);
        }

        // change w:id
        preg_match_all ('/<w:id w:val="(.*?)"\/>/', $template, $taken_wid);

        foreach ($taken_wid[0] as $key => $value) 
        {
            $random_num = $this->fs_replace_content_model->rand_num(9);

            $updated_template = str_replace($taken_wid[1][$key], $random_num, $updated_template);
        }

        // change w:docPart
        preg_match_all ('/<w:docPart w:val="(.*?)"\/>/', $template, $taken_wdocPart);

        foreach ($taken_wdocPart[0] as $key => $value) 
        {
            $random_alphanumeric = $this->fs_replace_content_model->rand_string_without_special_char(32);

            $updated_template = str_replace($taken_wdocPart[1][$key], $random_alphanumeric, $updated_template);
        }

        return $updated_template;
    }

    public function get_tbl_tc_template($tbl_template)
    { 
        preg_match_all('/<w:tc>.*?<\/w:tc>/s', $tbl_template, $taken_template);
            
        return $taken_template;
    }

    public function get_tr_name_type($tbl_tr_template)
    {
        $tbl_tc = $this->get_tbl_tc_template($tbl_tr_template);

        foreach ($tbl_tc[0] as $tbl_tc_key => $tbl_tc_value) 
        {
            preg_match_all ('/<w:t(?=\s|>)(.+?)<\/w:t>/s', $tbl_tc_value, $taken_template);

            $wt = $taken_template;
            $tr_name_type = '';

            foreach ($wt[1] as $wt_key => $wt_value) 
            {
                $tr_name_type .= str_replace(' xml:space="preserve"', '', str_replace('>', '', $wt_value));
            }

            return $tr_name_type;
        }
    }

    /* ---------------------------------------------------------------------------------------------------------- */

    public function hide_tr_columns($fs_company_info, $data, $tbl_tr, $replaced_tbl_template) 
    {
        // $data = array(
        //             'specific' => array(
        //                             array(
        //                                 'row' => 2,
        //                                 'hide_column' => array(3)
        //                             )
        //                         ), 
        //             'all'     => array(3, 4));

        $tbl_template = $replaced_tbl_template;

        if($fs_company_info[0]['group_type'] == 1)   // company only
        {
            foreach ($tbl_tr[0] as $tbl_tr_key => $tbl_tr_value)    // loop by tr
            {
                $replaced_tbl_tr_value = $tbl_tr_value;

                $temp_target_col = [];
                $remove_tr_hide_company_title = false;

                // get specific requested column to hide - check row if matched with specific row
                foreach ($data['specific'] as $specific_key => $specific_value) 
                {
                    if(($tbl_tr_key + 1) == $specific_value['row'])
                    {
                        $temp_target_col = $specific_value['hide_column'];
                        break;
                    }
                }

                // hide title row when "FIRST SET REPORT" with "NO GROUP" 
                // if($fs_company_info[0]['first_set'] == 1)
                // {
                    foreach ($data['hide_company_title'] as $hide_table_key => $hide_title_value) 
                    {
                        if(($tbl_tr_key + 1) == $hide_title_value['row'])
                        {
                            // print_r($data);

                            if(isset($data['generate_docs_without_tags']) && $data['generate_docs_without_tags'])
                            {
                                $replaced_tbl_tr_value = '';
                                $remove_tr_hide_company_title = true;
                            }
                            else
                            {
                                $temp_target_col = $hide_title_value['hide_column'];
                            }
                            break;
                        }
                    }
                // }

                if(empty($temp_target_col))
                {
                    $temp_target_col = $data['all'];
                }

                if(!$remove_tr_hide_company_title)
                {
                    // for column part
                    $tbl_tc = $this->get_tbl_tc_template($tbl_tr_value);

                    foreach ($tbl_tc[0] as $tbl_tc_key => $tbl_tc_value) // hide tc (column)
                    {
                        $replaced_tbl_tc_value = $tbl_tc_value;

                        foreach ($temp_target_col as $temp_target_col_key => $temp_target_col_value)    // to hide group column 
                        {   
                            if(($tbl_tc_key + 1) == $temp_target_col_value)
                            {
                                // preg_match_all ('/<!-- <w:tcBorders.*?<\/w:tcBorders> -->/', $replaced_tbl_tc_value, $taken_comment_wtcBorders);

                                // if(count($taken_comment_wtcBorders[0]) == 0)
                                // {
                                //     preg_match_all ('/<w:tcBorders><w:top w:val="nil"\/><w:left w:val="nil"\/><w:bottom w:val="nil"\/><w:right w:val="nil"\/><\/w:tcBorders>/', $replaced_tbl_tc_value, $taken_none_border_template);

                                //     if(count($taken_none_border_template[0]) == 0)  // if this column does not have border.
                                //     {
                                //         preg_match_all ('/<w:tcBorders.*?<\/w:tcBorders>/', $replaced_tbl_tc_value, $taken_wtcBorders);

                                //         $replaced_tbl_tc_value = str_replace($taken_wtcBorders[0][0], '<!-- ' . $taken_wtcBorders[0][0] . ' -->' . '<w:tcBorders><w:top w:val="nil"/><w:left w:val="nil"/><w:bottom w:val="nil"/><w:right w:val="nil"/></w:tcBorders>', $replaced_tbl_tc_value);
                                //     }
                                // }

                                // $replaced_tbl_tc_value = $this->vanish_template($replaced_tbl_tc_value, 1);
                                // $replaced_tbl_tr_value = str_replace($tbl_tc_value, $replaced_tbl_tc_value, $replaced_tbl_tr_value);

                                preg_match_all ('/<w:tcBorders>.*?<\/w:tcBorders>/', $replaced_tbl_tc_value, $taken_wtcBorders);
                                $taken_wtcBorders = $taken_wtcBorders[0][0];

                                $replaced_taken_wtcBorders = str_replace('w:color="auto"', 'w:color="FFFFFF"', $taken_wtcBorders);  // change border line to white color

                                $replaced_tbl_tc_value = str_replace($taken_wtcBorders, $replaced_taken_wtcBorders, $replaced_tbl_tc_value);

                                if(isset($data['generate_docs_without_tags']) && $data['generate_docs_without_tags'])
                                {
                                    preg_match_all('/<w:p.*?>(.*?)<\/w:p>/s', $replaced_tbl_tc_value, $remove_wp_contents);

                                    foreach ($remove_wp_contents[1] as $rwc_key => $rwc_value) 
                                    {
                                        $replaced_tbl_tc_value = str_replace($rwc_value, '', $replaced_tbl_tc_value);
                                    }
                                }
                                else
                                {
                                    $replaced_tbl_tc_value = $this->vanish_template($replaced_tbl_tc_value, 1);
                                }

                                $replaced_tbl_tr_value = str_replace($tbl_tc_value, $replaced_tbl_tc_value, $replaced_tbl_tr_value);

                            }
                        }

                        // $replaced_tbl_tr_value = $this->hide_column_by_first_set($fs_company_info, $data, $tbl_tr_key, $tbl_tc_key, $tbl_template, $replaced_tbl_tc_value, $replaced_tbl_tr_value, $tbl_tc_value);
                    }
                }

                $replaced_tbl_template = str_replace($tbl_tr_value, $replaced_tbl_tr_value, $replaced_tbl_template);
            }
        }
        else // group
        {
            foreach ($tbl_tr[0] as $tbl_tr_key => $tbl_tr_value)    // loop by tr
            {
                $replaced_tbl_tr_value = $tbl_tr_value;

                $temp_target_col = [];

                foreach ($data['specific'] as $specific_key => $specific_value) // get specific requested column to hide - check row if matched with specific row
                {
                    if(($tbl_tr_key + 1) == $specific_value['row'])
                    {
                        $temp_target_col = $specific_value['hide_column'];

                        break;
                    }
                }

                if(empty($temp_target_col))
                {
                    $temp_target_col = $data['all'];
                }

                $tbl_tc = $this->get_tbl_tc_template($tbl_tr_value);

                foreach ($tbl_tc[0] as $tbl_tc_key => $tbl_tc_value) // hide tc (column)
                {
                    $replaced_tbl_tc_value = $tbl_tc_value;

                    foreach ($temp_target_col as $temp_target_col_key => $temp_target_col_value) 
                    {   
                        if(($tbl_tc_key + 1) == $temp_target_col_value)
                        {
                            // // preg_match_all ('/<!-- <w:tcBorders.*?<\/w:tcBorders> -->/', $replaced_tbl_tc_value, $taken_comment_wtcBorders);    // get border tag

                            // // // print_r($taken_comment_wtcBorders);

                            // // if(count($taken_comment_wtcBorders[0]) > 0)
                            // // {
                            // //     $uncommented_wtcBorders = str_replace('<!-- ', '', $taken_comment_wtcBorders[0][0]);
                            // //     $uncommented_wtcBorders = str_replace(' -->', '', $uncommented_wtcBorders);

                            // //     // print_r($uncommented_wtcBorders);
                               
                            // //     $replaced_tbl_tc_value = str_replace('<w:tcBorders><w:top w:val="nil"/><w:left w:val="nil"/><w:bottom w:val="nil"/><w:right w:val="nil"/></w:tcBorders>', $uncommented_wtcBorders, $replaced_tbl_tc_value); // remove border
                            // // }

                            // preg_match_all ('/<w:tcBorders>.*?<\/w:tcBorders>/', $replaced_tbl_tc_value, $taken_wtcBorders);
                            // $taken_wtcBorders = $taken_wtcBorders[0][0];

                            // $replaced_taken_wtcBorders = str_replace('w:color="FFFFFF"', 'w:color="auto"', $taken_wtcBorders);  // change border line to auto color (black)

                            // $replaced_tbl_tc_value = str_replace($taken_wtcBorders, $replaced_taken_wtcBorders, $replaced_tbl_tc_value);

                            // // $replaced_tbl_tc_value = $this->vanish_template($replaced_tbl_tc_value, 1);
                            // // $replaced_tbl_tr_value = str_replace($tbl_tc_value, $replaced_tbl_tc_value, $replaced_tbl_tr_value);

                            preg_match_all ('/<w:tcBorders>.*?<\/w:tcBorders>/', $replaced_tbl_tc_value, $taken_wtcBorders);
                            $taken_wtcBorders = $taken_wtcBorders[0][0];

                            // if(strpos($taken_wtcBorders, 'w:color="auto"') !== false)
                            // {
                            //     $replaced_taken_wtcBorders = str_replace('w:color="auto"', 'w:color="FFFFFF"', $taken_wtcBorders);  // change border line colour (white)    
                            //     $replaced_tbl_tc_value = str_replace($taken_wtcBorders, $replaced_taken_wtcBorders, $replaced_tbl_tc_value);
                            //     $replaced_tbl_tc_value = $this->vanish_template($replaced_tbl_tc_value, 1); // show group columns                         
                            // }
                            // else
                            // {
                                $replaced_taken_wtcBorders = str_replace('w:color="FFFFFF"', 'w:color="auto"', $taken_wtcBorders);  // change border line to auto color (black)
                                $replaced_tbl_tc_value = str_replace($taken_wtcBorders, $replaced_taken_wtcBorders, $replaced_tbl_tc_value);
                                $replaced_tbl_tc_value = $this->vanish_template($replaced_tbl_tc_value, 0); // show group columns  
                            // }

                            $replaced_tbl_tr_value = str_replace($tbl_tc_value, $replaced_tbl_tc_value, $replaced_tbl_tr_value);
                        }
                    }

                    // print_r($tbl_tc_value);

                    // $replaced_tbl_tr_value = $this->hide_column_by_first_set($fs_company_info, $data, $tbl_tr_key, $tbl_tc_key, $tbl_template, $replaced_tbl_tc_value, $replaced_tbl_tr_value, $tbl_tc_value);
                }

                $replaced_tbl_template = str_replace($tbl_tr_value, $replaced_tbl_tr_value, $replaced_tbl_template);
            }
        }

        if(!empty($replaced_tbl_template))
        {
            return $replaced_tbl_template;
        }
        else
        {
            return $replaced_tbl_tr_value;
        }
    }

    public function hide_tbl_column($fs_company_info, $data, $tbl_template, $replaced_xml) 
    {
        $original_tbl_tr_value = '';
        $update_tbl_tr_value = '';

        $replaced_tbl_template = $tbl_template;

        $tbl = $this->get_part_of_template('<w:tbl>', 'w:tbl', $tbl_template);
        $tbl = $tbl[0][0];

        $tbl_tr = $this->get_tbl_tr_template($tbl); // get tr list

        return $this->hide_tr_columns($fs_company_info, $data, $tbl_tr, $replaced_tbl_template);
        // if($group_type == '1')
        // {
        //     $tbl_tr = $this->get_tbl_tr_template($tbl); // get tr list

        //     foreach ($tbl_tr[0] as $tbl_tr_key => $tbl_tr_value)    // loop by tr
        //     {
        //         $replaced_tbl_tr_value = $tbl_tr_value;

        //         $temp_target_col = [];

        //         foreach ($data['specific'] as $specific_key => $specific_value) // get specific requested column to hide - check row if matched with specific row
        //         {
        //             if(($tbl_tr_key + 1) == $specific_value['row'])
        //             {
        //                 $temp_target_col = $specific_value['hide_column'];

        //                 break;
        //             }
        //         }

        //         if(empty($temp_target_col))
        //         {
        //             $temp_target_col = $data['all'];
        //         }

        //         $tbl_tc = $this->get_tbl_tc_template($tbl_tr_value);

        //         foreach ($tbl_tc[0] as $tbl_tc_key => $tbl_tc_value) // hide tc (column)
        //         {
        //             $replaced_tbl_tc_value = $tbl_tc_value;

        //             foreach ($temp_target_col as $temp_target_col_key => $temp_target_col_value) 
        //             {   
        //                 if(($tbl_tc_key + 1) == $temp_target_col_value)
        //                 {
        //                     preg_match_all ('/<!-- <w:tcBorders.*?<\/w:tcBorders> -->/', $replaced_tbl_tc_value, $taken_comment_wtcBorders);

        //                     if(count($taken_comment_wtcBorders[0]) == 0)
        //                     {
        //                         preg_match_all ('/<w:tcBorders><w:top w:val="nil"\/><w:left w:val="nil"\/><w:bottom w:val="nil"\/><w:right w:val="nil"\/><\/w:tcBorders>/', $replaced_tbl_tc_value, $taken_none_border_template);

        //                         if(count($taken_none_border_template[0]) == 0)  // if this column does not have border.
        //                         {
        //                             preg_match_all ('/<w:tcBorders.*?<\/w:tcBorders>/', $replaced_tbl_tc_value, $taken_wtcBorders);

        //                             $replaced_tbl_tc_value = str_replace($taken_wtcBorders[0][0], '<!-- ' . $taken_wtcBorders[0][0] . ' -->' . '<w:tcBorders><w:top w:val="nil"/><w:left w:val="nil"/><w:bottom w:val="nil"/><w:right w:val="nil"/></w:tcBorders>', $replaced_tbl_tc_value);
        //                         }
        //                     }

        //                     $replaced_tbl_tc_value = $this->vanish_template($replaced_tbl_tc_value, 1);
        //                     $replaced_tbl_tr_value = str_replace($tbl_tc_value, $replaced_tbl_tc_value, $replaced_tbl_tr_value);
        //                 }
        //             }
        //         }

        //         $replaced_tbl_template = str_replace($tbl_tr_value, $replaced_tbl_tr_value, $replaced_tbl_template);
        //     }
        // }
        // else
        // {
        //     $tbl_tr = $this->get_tbl_tr_template($tbl); // get tr list

        //     foreach ($tbl_tr[0] as $tbl_tr_key => $tbl_tr_value)    // loop by tr
        //     {
        //         $replaced_tbl_tr_value = $tbl_tr_value;

        //         $temp_target_col = [];

        //         foreach ($data['specific'] as $specific_key => $specific_value) // get specific requested column to hide - check row if matched with specific row
        //         {
        //             if(($tbl_tr_key + 1) == $specific_value['row'])
        //             {
        //                 $temp_target_col = $specific_value['hide_column'];

        //                 break;
        //             }
        //         }

        //         if(empty($temp_target_col))
        //         {
        //             $temp_target_col = $data['all'];
        //         }

        //         $tbl_tc = $this->get_tbl_tc_template($tbl_tr_value);

        //         foreach ($tbl_tc[0] as $tbl_tc_key => $tbl_tc_value) // hide tc (column)
        //         {
        //             $replaced_tbl_tc_value = $tbl_tc_value;

        //             foreach ($temp_target_col as $temp_target_col_key => $temp_target_col_value) 
        //             {   
        //                 if(($tbl_tc_key + 1) == $temp_target_col_value)
        //                 {
        //                     preg_match_all ('/<!-- <w:tcBorders.*?<\/w:tcBorders> -->/', $replaced_tbl_tc_value, $taken_comment_wtcBorders);

        //                     // print_r($taken_comment_wtcBorders);

        //                     if(count($taken_comment_wtcBorders[0]) > 0)
        //                     {
        //                         $uncommented_wtcBorders = str_replace('<!-- ', '', $taken_comment_wtcBorders[0][0]);
        //                         $uncommented_wtcBorders = str_replace(' -->', '', $uncommented_wtcBorders);

        //                         print_r($uncommented_wtcBorders);
        //                         // preg_match_all ('/<w:tcBorders><w:top w:val="nil"\/><w:left w:val="nil"\/><w:bottom w:val="nil"\/><w:right w:val="nil"\/><\/w:tcBorders>/', $replaced_tbl_tc_value, $taken_none_border_template);

        //                         // if(count($taken_none_border_template[0]) == 0)  // if this column does not have border.
        //                         // {
        //                             // preg_match_all ('/<w:tcBorders.*?<\/w:tcBorders>/', $replaced_tbl_tc_value, $taken_wtcBorders);

        //                             $replaced_tbl_tc_value = str_replace('<w:tcBorders><w:top w:val="nil"/><w:left w:val="nil"/><w:bottom w:val="nil"/><w:right w:val="nil"/></w:tcBorders>', $uncommented_wtcBorders, $replaced_tbl_tc_value);
        //                         // }
        //                     }

        //                     $replaced_tbl_tc_value = $this->vanish_template($replaced_tbl_tc_value, 0);
        //                     $replaced_tbl_tr_value = str_replace($tbl_tc_value, $replaced_tbl_tc_value, $replaced_tbl_tr_value);
        //                 }
        //             }
        //         }

        //         $replaced_tbl_template = str_replace($tbl_tr_value, $replaced_tbl_tr_value, $replaced_tbl_template);
        //     }
        // }




        // if($group_type == '1')
        // {
        //     $tbl_tr = $this->get_tbl_tr_template($tbl); // get tr list

        //     foreach ($tbl_tr[0] as $tbl_tr_key => $tbl_tr_value) 
        //     {
        //         $replaced_tbl_tr_value = $tbl_tr_value;

        //         $temp_target_col = [];

        //         foreach ($data['specific'] as $specific_key => $specific_value) // get specific requested column to hide
        //         {
        //             if(($tbl_tr_key + 1) == $specific_value['row'])
        //             {
        //                 $temp_target_col = $specific_value['hide_column'];

        //                 break;
        //             }
        //         }

        //         if(empty($temp_target_col))
        //         {
        //             $temp_target_col = $data['all'];
        //         }

        //         $tbl_tc = $this->get_tbl_tc_template($tbl_tr_value);

        //         foreach ($tbl_tc[0] as $tbl_tc_key => $tbl_tc_value) // hide tc (column)
        //         {
        //             foreach ($temp_target_col as $temp_target_col_key => $temp_target_col_value) 
        //             {
        //                 if(($tbl_tc_key + 1) == $temp_target_col_value)
        //                 {
        //                     $replaced_tbl_tr_value = str_replace($tbl_tc_value, '<!-- ' . $tbl_tc_value . ' -->', $replaced_tbl_tr_value);
        //                 }
        //             }
        //         }

        //         $replaced_tbl_template = str_replace($tbl_tr_value, $replaced_tbl_tr_value, $replaced_tbl_template);
        //     }

        //     $tblGrid_template = $this->get_part_of_template('<w:tblGrid>', 'w:tblGrid', $replaced_tbl_template);
        //     $gridCol_template = $this->get_tag('w:gridCol', $tblGrid_template[0][0]);

        //     $replaced_tblGrid_template = '<w:tblGrid>';
        //     $replaced_gridCol_template = '';

        //     foreach ($gridCol_template[0] as $gridCol_template_key => $gridCol_template_value) 
        //     {
        //         $replaced_gridCol_template = $gridCol_template_value;

        //         foreach ($data['all'] as $target_key => $target_col_val) 
        //         {
        //             if($gridCol_template_key + 1 == $target_col_val)
        //             {
        //                 $replaced_gridCol_template = '<!-- ' . $replaced_gridCol_template . '-->';
        //             }
        //         }

        //         $replaced_tblGrid_template .= $replaced_gridCol_template;
        //     }

        //     $replaced_tblGrid_template .= '</w:tblGrid>';
        // }

        // $replaced_tbl_template = str_replace($tblGrid_template[0][0], $replaced_tblGrid_template, $replaced_tbl_template);

        // print_r($replaced_tbl_template);

        return $replaced_tbl_template;
    }

    public function hide_tbl($tbl_template, $hide_needed)
    {
        $replaced_tbl = $tbl_template;

        if($hide_needed)
        {
            $tbl_tr = $this->get_tbl_tr_template($tbl_template);   // tbl tr
        
            foreach ($tbl_tr[0] as $tr_key => $tr_value) 
            {
                $wtrPr = $this->get_part_of_template('<w:trPr>', 'w:trPr', $tr_value);

                if($wtrPr == '')   // if dont have <w:trPr> tag, create 1 with hide
                {
                    $wtr_opening_tag = $this->get_tag('w:tr', $tr_value);
                    $replaced_tr = str_replace($wtr_opening_tag[0][0], $wtr_opening_tag[0][0] . '<w:trPr><w:hidden/></w:trPr>', $tr_value);

                    $replaced_tbl = str_replace($tr_value, $replaced_tr, $replaced_tbl);
                }
                else
                { 
                    preg_match_all ('/<w:hidden\/>/s', $tr_value, $whidden);

                    if(count($whidden[0]) == 0)
                    {
                        $replaced_tr = str_replace('</w:trPr>', '<w:hidden/></w:trPr>', $tr_value);

                        $tbl_tc = $this->get_tbl_tc_template($replaced_tr);

                        foreach ($tbl_tc[0] as $tbl_tc_key => $tbl_tc_value) // hide all tc (column)
                        {
                            $replaced_tbl_tc_value = $tbl_tc_value;

                            // hide border colour
                            preg_match_all ('/<w:tcBorders>.*?<\/w:tcBorders>/', $replaced_tbl_tc_value, $taken_wtcBorders);
                            $taken_wtcBorders = $taken_wtcBorders[0][0];

                            $replaced_taken_wtcBorders = str_replace('w:color="auto"', 'w:color="FFFFFF"', $taken_wtcBorders);  // change border line to white color

                            $replaced_tbl_tc_value = str_replace($taken_wtcBorders, $replaced_taken_wtcBorders, $replaced_tbl_tc_value);
                            // end of hide border colour

                            $replaced_tbl_tc_value = $this->vanish_template($replaced_tbl_tc_value, 1);
                            $replaced_tr = str_replace($tbl_tc_value, $replaced_tbl_tc_value, $replaced_tr);
                        }

                        $replaced_tbl = str_replace($tr_value, $replaced_tr, $replaced_tbl);
                    }
                }
            }

            $replaced_tbl = $this->vanish_content_paragraph($replaced_tbl);
        }
        else
        {
            $tbl_tr = $this->get_tbl_tr_template($tbl_template);   // tbl tr
        
            foreach ($tbl_tr[0] as $tr_key => $tr_value) 
            {
                if($tr_key > 0)
                {
                    $replaced_tr = str_replace('<w:hidden/>', '', $tr_value);

                    $tbl_tc = $this->get_tbl_tc_template($replaced_tr);

                    foreach ($tbl_tc[0] as $tbl_tc_key => $tbl_tc_value) // hide all tc (column)
                    {
                        $replaced_tbl_tc_value = $tbl_tc_value;

                        // change border colour to auto (black)
                        preg_match_all ('/<w:tcBorders>.*?<\/w:tcBorders>/', $replaced_tbl_tc_value, $taken_wtcBorders);
                        $taken_wtcBorders = $taken_wtcBorders[0][0];

                        $replaced_taken_wtcBorders = str_replace('w:color="FFFFFF"', 'w:color="auto"', $taken_wtcBorders);  // change border line to white color

                        $replaced_tbl_tc_value = str_replace($taken_wtcBorders, $replaced_taken_wtcBorders, $replaced_tbl_tc_value);
                        // end of hide border colour

                        $replaced_tbl_tc_value = $this->vanish_template($replaced_tbl_tc_value, 0);
                        $replaced_tr = str_replace($tbl_tc_value, $replaced_tbl_tc_value, $replaced_tr);
                    }

                    $replaced_tbl = str_replace($tr_value, $replaced_tr, $replaced_tbl);
                }
            }
        }

        return $replaced_tbl;
    }

    // public function hide_column_by_first_set($fs_company_info, $data, $tbl_tr_key, $tbl_tc_key, $tbl_template, $replaced_tbl_tc_value, $replaced_tbl_tr_value, $tbl_tc_value)
    // {
    //     // print_r(array($tbl_tc_value));
    //     $tbl_tc_value = $replaced_tbl_tc_value; // keep the original one

    //     preg_match_all ('/<w:tblGrid>.*?<\/w:tblGrid>/', $tbl_template, $taken_wtblGrid);
    //     $taken_wtblGrid = $taken_wtblGrid[0][0];

    //     preg_match_all ('/<w:gridCol w:w="(.*?)"\/>/', $tbl_template, $taken_wgridCol);
    //     $taken_wgridCol = $taken_wgridCol[1];   // get the list of number of w:gridCol

    //     // print_r(array($fs_company_info));

    //     if($fs_company_info[0]['first_set'] && ((int)$tbl_tr_key >= (int)$data['merge_start_row_after']))
    //     {
    //         foreach($data['merge_column'] as $merge_col_key => $merge_col_value)
    //         {   
    //             if(($tbl_tc_key + 1) == $merge_col_value[0])    // merge the border number
    //             {
    //                 $merge_col_val = $taken_wgridCol[$merge_col_value[0] - 1] + $taken_wgridCol[$merge_col_value[1] - 1];

    //                 preg_match_all('/<w:tcW.*?\/>/s', $replaced_tbl_tc_value, $wtcw);
    //                 $wtcw = $wtcw[0][0];

    //                 preg_match_all('/w:w=".*?"/s', $wtcw, $ww);
    //                 $ww = $ww[0][0];

    //                 $replaced_wtcw = str_replace($ww, 'w:w="' . $merge_col_val . '"', $wtcw);

    //                 if($fs_company_info[0]['group_type'] == '1')
    //                 {
    //                     $replaced_tbl_tc_value = str_replace($wtcw, $replaced_wtcw, $replaced_tbl_tc_value);
    //                 }
    //                 else
    //                 {
    //                     if($merge_col_key == 0)
    //                     {
    //                         $replaced_tbl_tc_value = str_replace($wtcw, $replaced_wtcw . '<w:gridSpan w:val="2"/>', $replaced_tbl_tc_value);
    //                     }
    //                     else
    //                     {
    //                         $replaced_tbl_tc_value = str_replace($wtcw, $replaced_wtcw, $replaced_tbl_tc_value);
    //                     }
    //                 }

    //                 $replaced_tbl_tr_value = str_replace($tbl_tc_value, $replaced_tbl_tc_value, $replaced_tbl_tr_value);
    //             }

    //             if(($tbl_tc_key + 1) == $merge_col_value[1])    // hide the column
    //             {   
    //                 $replaced_tbl_tc_value = str_replace($replaced_tbl_tc_value, '<!-- ' . $replaced_tbl_tc_value . ' -->', $replaced_tbl_tc_value);
    //                 $replaced_tbl_tr_value = str_replace($tbl_tc_value, $replaced_tbl_tc_value, $replaced_tbl_tr_value);
    //             }
    //         }
    //     }
    //     else
    //     {
    //         if(((int)$tbl_tr_key >= (int)$data['merge_start_row_after']))
    //         {
    //             foreach($data['merge_column'] as $merge_col_key => $merge_col_value)
    //             {   
    //                 if(($tbl_tc_key + 1) == $merge_col_value[0])    // merge the border number
    //                 {
    //                     // $merge_col_val = $taken_wgridCol[$merge_col_value[0] - 1] + $taken_wgridCol[$merge_col_value[1] - 1];

    //                     preg_match_all('/<w:tcW.*?\/>/s', $replaced_tbl_tc_value, $wtcw);
    //                     $wtcw = $wtcw[0][0];

    //                     preg_match_all('/w:w=".*?"/s', $wtcw, $ww);
    //                     $ww = $ww[0][0];

    //                     // print_r(array($taken_wgridCol[$merge_col_value[0] - 1]));

    //                     $replaced_wtcw = str_replace($ww, 'w:w="' . $taken_wgridCol[$merge_col_value[0] - 1] . '"', $wtcw);

    //                     if($fs_company_info[0]['group_type'] == '1')
    //                     {
    //                         $replaced_tbl_tc_value = str_replace($wtcw, $replaced_wtcw, $replaced_tbl_tc_value);  // 

    //                         // print_r(array($replaced_tbl_tc_value));
    //                     }
    //                     else
    //                     {
    //                         if($merge_col_key == 0)
    //                         {
    //                             $replaced_tbl_tc_value = str_replace($wtcw, $replaced_wtcw . '<w:gridSpan w:val="2"/>', $replaced_tbl_tc_value);
    //                         }
    //                         else
    //                         {
    //                             $replaced_tbl_tc_value = str_replace($wtcw, $replaced_wtcw, $replaced_tbl_tc_value); // 
    //                         }
    //                     }

    //                     $replaced_tbl_tr_value = str_replace($tbl_tc_value, $replaced_tbl_tc_value, $replaced_tbl_tr_value);
    //                     // print_r(array($tbl_tc_value));
    //                     // print_r(array($replaced_tbl_tr_value));
    //                 }

    //                 if(($tbl_tc_key + 1) == $merge_col_value[1])    // hide the column
    //                 {   
    //                     // $replaced_tbl_tc_value = $this->vanish_template($replaced_tbl_tc_value, 1);
    //                     // $replaced_tbl_tc_value = str_replace('<!-- ', '', $replaced_tbl_tc_value);
    //                     // $replaced_tbl_tc_value = str_replace(' -->', '', $replaced_tbl_tc_value);

    //                     $replaced_tbl_tr_value = str_replace('<!-- ', '', $replaced_tbl_tr_value);
    //                     $replaced_tbl_tr_value = str_replace(' -->', '', $replaced_tbl_tr_value);
    //                 }
    //             }
    //         }
    //     }

    //     return $replaced_tbl_tr_value;
    // }

    public function remove_wr_from_tr($tr_template, $col_positions)
    {
        $tc_list = $this->get_tbl_tc_template($tr_template);

        foreach($tc_list[0] as $tc_list_key => $tc_list_value)
        {
            $replaced_tc_list_value = $tc_list_value;

            for ($x = 0; $x < count($col_positions); $x++) 
            {
                if($col_positions[$x] == (int)($tc_list_key + 1))
                {
                    /* ------- remove border line ------- */
                    preg_match_all ('/<w:tcBorders>.*?<\/w:tcBorders>/', $replaced_tc_list_value, $taken_wtcBorders);
                    $taken_wtcBorders = $taken_wtcBorders[0][0];

                    $replaced_taken_wtcBorders = str_replace('w:color="auto"', 'w:color="FFFFFF"', $taken_wtcBorders);  // change border line to white color

                    $replaced_tc_list_value = str_replace($taken_wtcBorders, $replaced_taken_wtcBorders, $replaced_tc_list_value);
                    /* ------- end of remove border line ------- */

                    $wr_list = $this->get_template_wr($replaced_tc_list_value);

                    foreach ($wr_list[0] as $wr_list_key => $wr_list_value) 
                    {
                        $replaced_tc_list_value = str_replace($wr_list_value, '', $replaced_tc_list_value); // remove wr
                    }

                }
            }

        //     foreach ($col_positions as $col_positions_key => $col_positions_value) 
        //     {   
        //         print_r((int)$col_positions_key == (int)($tc_list_key + 1));
        // //         if((int)$col_positions_key == (int)($tc_list_key + 1))
        // //         {
        // //             // print_r(array($tc_list_value));
        // //             $wr_list = $this->get_template_wr($tc_list_value);

        // //             // print_r($wr_list);
        // // //             // foreach ($wr_list[0] as $wr_list_key => $wr_list_value) 
        // // //             // {
        // // //             //     $replaced_tc_list_value = str_replace($wr_list_value, '', $tc_list_value);
        // // //             // }
        // //         }
        //     }

            $tr_template = str_replace($tc_list_value, $replaced_tc_list_value, $tr_template);
        }

        return $tr_template;
    }

    public function change_tr_tc_width($tr_template, $data)
    {
        // preg_match_all('/<w:tr (.*?)>/s', $tr_template, $wtr_opening_tag); 
        // preg_match_all('/w14:paraId="(.*?)"/s', $wtr_opening_tag[0][0], $paraId_value); 

        // $replaced_wtr_opening_tag = str_replace($paraId_value[0][0], '', $wtr_opening_tag[0][0]); 
        // $tr_template = str_replace($wtr_opening_tag[0][0], $replaced_wtr_opening_tag, $tr_template);

        // // print_r(array($tr_template));

        // print_r($data);

        $tbl_tc      = $this->get_tbl_tc_template($tr_template); 

        $ori_tbl_tc = $tbl_tc;
        $replace_tr  = '';
        $tc_template = '';  // save as temporary template
        $replaced_tcs = '';

        foreach ($tbl_tc[0] as $tbl_tc_key => $tbl_tc_value) 
        {
            // save template to do new date with template
            if(!in_array($tbl_tc_key + 1, $data['collect_template_col']))
            {
                $tc_template = $tbl_tc_value;
            }

            if(in_array($tbl_tc_key + 1, $data['gridspan_col']))
            {
                $gridspan_col_key = array_search($tbl_tc_key + 1, $data['gridspan_col']);

                // if(isset($data['table_name']))
                // {
                //     print_r(array($tbl_tc_key + 1, $data['gridspan_col'], $data['gridspan_value'][$gridspan_col_key]));
                // }

                if($data['gridspan_value'][$tbl_tc_key] != 1)
                {
                    preg_match_all('/<w:gridSpan w:val="(.*?)"\/>/s', $tc_template, $wgridSpan_tag_name);     // get tag <w:tblW w:w="9561" w:type="dxa"/>
                    
                    $replaced_wgridSpan_tag = preg_replace('/w:val="(.*?)"/s', 'w:val="' . $data['gridspan_value'][$gridspan_col_key] . '"', $wgridSpan_tag_name[0][0]);
                    $tbl_tc_value   = str_replace($wgridSpan_tag_name[0][0], $replaced_wgridSpan_tag, $tbl_tc_value);

                    // if(isset($data['table_name']))
                    // {
                    //     print_r(array($tbl_tc_value));
                    // }

                    // $gridspan = $this->change_gridspan($tc_template, $data['gridspan_value'][$gridspan_col_key]);

                    // print_r(array($tbl_tc_value));
                }
            }

            // change content if it is not skipped column
            if((count($data['width']) > $tbl_tc_key))
            {
                if(!in_array($tbl_tc_key + 1, $data['skip_col']))
                {
                    $replaced_tcs .= preg_replace('/w:w="(.*?)"/s', 'w:w="' . $data['width'][$tbl_tc_key] . '"', $tbl_tc_value);
                }
                else
                {
                    $replaced_tcs .= $tbl_tc_value;
                }
            }

            // change column gridspan
            // if(isset($data['gridspan_col']))
            // {

            // }
            
        }

        // // create columns if table does not have enough column
        // if(count($data['width']) > count($tbl_tc[0]))
        // {

        // }

        preg_match('/<w:tr.*?\/w:trPr>(.*?)<\/w:tr>/s', $tr_template, $ori_tbl_tc);

        // print_r($ori_tbl_tc[1]);

        $replace_tr = str_replace($ori_tbl_tc[1], $replaced_tcs, $tr_template);

        // print_r($replace_tr);

        return $replace_tr;
    }

    public function replace_tbl_tr_template($tr_template, $data, $counter)
    {
        // $replaced_tr_template = $this->vanish_template($tr_template, 0);
        $replaced_tr_template = $this->random_paraId_num($tr_template);

        $wsdt = $this->get_template_wsdt($replaced_tr_template);

        foreach ($wsdt[0] as $wsdt_key => $wsdt_xml) 
        {
            $tag      = $this->get_tag('w:alias', $wsdt_xml);   // get tag <w:alias ... />
            $attr_val = $this->get_attribute_value('w:val', $tag[0][0]);
            $tagName  = $attr_val[1][0];    // get tag name only

            if(!empty($counter))
            {
                $new_tag = str_replace($tagName, $tagName . '#' . $counter, $tag[0][0]);
                $updated_wsdt_xml = str_replace($tag[0][0], $new_tag, $wsdt_xml);
            }
            else
            {
                $updated_wsdt_xml = $wsdt_xml;
            }

            $wsdtcontent = $this->get_template_wsdtcontent($updated_wsdt_xml);

            $wr = $this->get_template_wr($wsdtcontent[0][0]);

            $replace_wr = '';
            $original_wr_content = '';

            foreach ($wr[0] as $wr_key => $wr_content) 
            {
                if($wr_key == 0)
                {
                    if(!empty($data[$tagName]))
                    {
                        $wt_template = $this->get_part_of_template('<w:t>', 'w:t', $wr_content);    // get w:t (Display text)
                        $replace_wr = str_replace($wt_template[0][0], '<w:t>' . $data[$tagName] . '</w:t>', $wr_content);
                    }
                    else
                    {
                        $wrPrContent = $this->get_template_wrPrContent($wr_content);

                        $replaced_wrPrContent = $this->insert_vanish_tag($wrPrContent[1][0], 1);
                        $replace_wr = str_replace($wrPrContent[1][0], $replaced_wrPrContent, $wr_content);
                    }
                }

                $original_wr_content .= $wr_content;    // save the original code to replace all later
            }

            if(!empty($replace_wr))
            {
                $new_wsdtcontent = str_replace($original_wr_content, $replace_wr, $wsdtcontent[0][0]);
                $replaced_wsdt_xml = str_replace($wsdtcontent[0][0], $new_wsdtcontent, $updated_wsdt_xml);
                $replaced_tr_template = str_replace($wsdt_xml, $replaced_wsdt_xml, $replaced_tr_template);
            }
        }

        return $replaced_tr_template;
    }

    public function replace_wt_in_wr($wt_template, $content, $wr_content)
    {
        $replace_wt  = '<w:t>' . $content . '</w:t>';
        $replace_wr = str_replace($wt_template, $replace_wt, $wr_content);    // replace the w:t inside w:r

        return $replace_wr;
    }

    public function replace_all_wr_in_wsdtContent($wsdt_template, $content)
    {
        $wr_template = $this->get_template_wr($wsdt_template);

        foreach ($wr_template[0] as $key => $value) // collect all wr 
        {
            if($key > 0)
            {
                $wsdt_template = str_replace($value, '', $wsdt_template);
            }
        }

        $wt_template = $this->get_part_of_template('<w:t', 'w:t', $wr_template[0][0]);  // get w:t
        $replaced_wr_template = $this->replace_wt_in_wr($wt_template[0][0], $content, $wr_template[0][0]);    // replace wt new new content

        return str_replace($wr_template[0][0], $replaced_wr_template, $wsdt_template);    // return replaced replaced_wsdt_template
    }

    public function retrieve_xml_data($alias_tag, $fs_company_info_id, $wt_template, $wr_content, $wp_content)
    {   
        /*  Template list
            -----------------
            1. $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $client_info[0]['company_name'], $wr_content); (Mostly used to update the text only) 
            2. 
                $type = array(
                    'br'           => true,
                    'bold'         => false,
                    'italic'       => true,
                    'text'         => true,
                    'text_content' => ' DISPLAY TEXT '
                );

                $this->xml_code_template($type);    // (Display text with styling such as newline, italic, bold)
        */

        if($alias_tag == "client name" || $alias_tag == "client company name" || $alias_tag == "Client - country" || $alias_tag == "Client - address" || $alias_tag == "Client name - Previous" || $alias_tag == "Change of company name – Date of resolution"
            || $alias_tag == "uen" || $alias_tag == "set of accounts" || $alias_tag == "firm name" || $alias_tag == "Current Year End - Beginning" || $alias_tag == "Current Year End - Ending" || $alias_tag == "title, directors signatures" || $alias_tag == "Report's Date" || $alias_tag == "audited" || $alias_tag == "consolidated")
        {
            $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);
            $client_info = $this->fs_model->get_client_info_by_companycode($fs_company_info[0]["company_code"]);
            $firm_info = $this->fs_model->get_firm_info_by_firmid($fs_company_info[0]["firm_id"]); 

            if($alias_tag == "client name")
            {   
                $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $this->encryption->decrypt($client_info[0]['company_name']), $wr_content);

                if(!empty($fs_company_info[0]['old_company_name']))
                {
                    $type = array(
                        'br'           => true,
                        'bold'         => false,
                        'italic'       => true,
                        'text'         => true,
                        'text_content' => '(Formerly known as ' . $fs_company_info[0]['old_company_name'] . ')'
                    );

                    $replace_wr .= $this->xml_code_template($type);
                }
            }
            elseif($alias_tag == "client company name")
            {
                $temp_content = $this->encryption->decrypt($client_info[0]['company_name']);
                $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $temp_content, $wr_content);
            }
            elseif($alias_tag == "title, directors signatures")
            {
                $directors = array();

                if($fs_company_info[0]['director_signature_1'] != "")
                {
                    array_push($directors, array('director_name' => $fs_company_info[0]['director_signature_1']));
                }

                if($fs_company_info[0]['director_signature_2'] != "")
                {
                    array_push($directors, array('director_name' => $fs_company_info[0]['director_signature_2']));
                }

                if(count($directors) == 1)
                {
                    $temp_content = $wr_content;
                }
                elseif(count($directors) == 2)
                {
                    $temp_content = 'The Board of Directors';
                }
                elseif(count($directors) > 2)
                {
                    $temp_content = 'On behalf of the Board of Directors';
                }
                $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $temp_content, $wr_content);
            }
            elseif($alias_tag == "Report's Date")
            {
                $temp_content = $fs_company_info[0]['report_date'];
                $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $temp_content, $wr_content);               
            }
            elseif($alias_tag == "Client name - Previous")
            {
                $temp_content = $fs_company_info[0]['old_company_name'];
                $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $temp_content, $wr_content);
            }
            elseif($alias_tag == "Client - country")
            {
                $temp_content = "Singapore";
                $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $temp_content, $wr_content);
            }
            elseif($alias_tag == "Client - address")
            {
                $address = array(
                        'type'             => "Local",
                        'street_name1'     => $client_info[0]["street_name"],
                        'unit_no1'         => $client_info[0]["unit_no1"],
                        'unit_no2'         => $client_info[0]["unit_no2"],
                        'building_name1'   => $client_info[0]["building_name"],
                        'postal_code1'     => $client_info[0]["postal_code"]
                    );

                $temp_content = $this->fs_replace_content_model->write_address_local_foreign($address, "comma", "");
                $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $temp_content, $wr_content);
            }
            elseif($alias_tag == "Change of company name – Date of resolution")
            {
                $temp_content = $fs_company_info[0]['date_of_resolution_for_change_of_name'];
                $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $temp_content, $wr_content);
            }
            elseif($alias_tag == "uen")
            {   
                $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], '(Registration no.: ' . $this->encryption->decrypt($client_info[0]['registration_no']) . ')', $wr_content);
            }
            elseif($alias_tag == "set of accounts")
            {
                $first_set      = $fs_company_info[0]['first_set'];
                $last_fye_begin = $fs_company_info[0]['current_fye_begin'];
                $final_year_end = $fs_company_info[0]['current_fye_end'];

                if($first_set)
                {
                    $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], strtoupper($last_fye_begin), $wr_content);

                    $DOI_display = array(
                        'br'           => true,
                        'text'         => true,
                        'text_content' => '(Date of incorporation)'
                    );

                    $replace_wr .= $this->xml_code_template($DOI_display);
                    $replace_wr .= $this->xml_code_template(array('br' => true));

                    $FYE_display = array(
                        'br'           => true,
                        'bold'         => true,
                        'text'         => true,
                        'text_content' => 'TO ' . strtoupper($final_year_end)
                    );

                    $replace_wr .= $this->xml_code_template($FYE_display);
                }
                else 
                {
                    $start_date_day = date('d', strtotime($fs_company_info[0]['current_fye_begin']));

                    $start_date = new DateTime(date('Y-m-d', strtotime($fs_company_info[0]['current_fye_begin'])));
                    $start_date->modify('-1 day');
                    $start_date  = $start_date->format('Y-m-d');

                    $start_date = date_create($start_date);
                    $end_date = date_create($fs_company_info[0]['current_fye_end']);

                    $interval   = date_diff($start_date, $end_date);
                    $interval_value_year = $interval->format('%y');

                    if($interval_value_year == 1)
                    {
                        $replace_wt = '<w:t>' . strtoupper($final_year_end) .'</w:t>';
                    }
                    else
                    {
                        $replace_wt = '<w:t>' . strtoupper($last_fye_begin) . '</w:t>';
                        // <strong><br/><br/> TO '. strtoupper($final_year_end) .'</strong>';
                    }

                    $replace_wr = str_replace($wt_template[0][0], $replace_wt, $wr_content);    // replace the w:t inside w:r
                }
            }
            elseif($alias_tag == "firm name")
            {
                $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $firm_info[0]['name'], $wr_content);
            }
            elseif($alias_tag == "Current Year End - Beginning")
            {
                $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $fs_company_info[0]['current_fye_begin'], $wr_content);
            }
            elseif($alias_tag == "Current Year End - Ending")
            {
                $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $fs_company_info[0]['current_fye_end'], $wr_content);
            }
            elseif($alias_tag == "audited")
            {
                // print_r(array($alias_tag, $fs_company_info[0]['is_audited']));

                if($fs_company_info[0]['is_audited'] == 1)
                {
                    $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], 'audited ', $wr_content);
                }
                else
                {
                    $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], '{audited}', $wr_content);
                }
            }
            elseif($alias_tag == "consolidated")
            {
                if($fs_company_info[0]['is_group_consolidated'] == 1)
                {
                    $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], 'consolidated ', $wr_content);
                }
                else
                {
                    $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], '{consolidated}', $wr_content);
                }
            }
        }
        elseif($alias_tag == "Ultimate Holding Company - name" || $alias_tag == "Ultimate Holding Company - country")
        {
            $ultimate_info = $this->db->query("SELECT fs_dir_statement_company.*, country.name, country.nicename
                                                    FROM fs_dir_statement_company 
                                                    LEFT JOIN fs_country country ON country.id = fs_dir_statement_company.country_id
                                                    WHERE fs_dir_statement_company.fs_company_info_id=" . $fs_company_info_id . " AND fs_dir_statement_company.fs_company_type_id=1");
            $ultimate_info = $ultimate_info->result_array();

            if($alias_tag == "Ultimate Holding Company - name")
            {
                $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $ultimate_info[0]['company_name'], $wr_content);
            }
            elseif($alias_tag == "Ultimate Holding Company - country")
            {
                $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $ultimate_info[0]['nicename'], $wr_content);
            }
        }
        elseif($alias_tag == "Group and the Company" || $alias_tag == "Group/Company" || $alias_tag == "sing/plu s" || $alias_tag == "sing/plu s'" || $alias_tag == "tense s" || $alias_tag == "is/are" || $alias_tag == "the/their")
        {
            $replace_wr = $this->replace_verbs_plural($alias_tag, $fs_company_info_id, $wt_template, $wr_content);
        }
        elseif($alias_tag == "Statement Year End" || $alias_tag == "Statement Year End (FP)" || $alias_tag == "Statement Last Year End - End" || $alias_tag == "Statement Last Year End - End (FP)" || $alias_tag == "Statement Year End NTA - LATEST" || $alias_tag == "Statement Year End NTA - EARLIER")  // statement information
        {
            $temp_content = '';

            $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

            if($alias_tag == "Statement Year End (FP)" || $alias_tag == "Statement Last Year End - End (FP)")
            {
                $current_last_year_end = $this->fs_model->calculate_difference_dates($fs_company_info_id, "FP");
            }
            else
            {
                $current_last_year_end = $this->fs_model->calculate_difference_dates($fs_company_info_id, "General");
            }

            $last_fye_end = $current_last_year_end['last_fye_end'];
            $current_fye_end = $current_last_year_end['current_fye_end'];
            $last_year_end_begining = '';

            if(empty($last_fye_end))    // prevent '' in date
            {
                $last_fye_end = date('d.m.Y', strtotime($last_fye_end));
            }

            if(empty($current_fye_end))    // prevent '' in date
            {
                $current_fye_end = date('d.m.Y', strtotime($current_fye_end));
            }

            $replace_string = $string_to_replace[0][$r];

            if($fs_company_info[0]['first_set'])
            {
                if($alias_tag == "Statement Year End (FP)")
                {
                    $temp_content = $current_last_year_end['current_fye_end'];

                    $replace_wr = $this->build_wr_with_br_tag_without_paragraph($temp_content);
                }
                else
                {
                    $temp_content = $current_fye_end;

                    if(empty($temp_content))
                    {
                        $temp_content = date('d.m.Y', strtotime($temp_content));
                    }

                    $replace_wr = $this->build_wr_with_br_tag_without_paragraph($temp_content);
                }
            }
            else
            {
                if($alias_tag == "Statement Year End")
                {
                    $temp_content = $current_fye_end;
                    $replace_wr = $this->build_wr_with_br_tag_without_paragraph($temp_content);
                }
                elseif($alias_tag == "Statement Year End (FP)")
                {
                    $temp_content = $current_fye_end;
                    $replace_wr = $this->build_wr_with_br_tag_without_paragraph($temp_content);
                }
            }
            
            if($alias_tag == "Statement Last Year End - End")
            {
                $temp_content = $last_fye_end;
                $replace_wr = $this->build_wr_with_br_tag_without_paragraph($temp_content);
            }
            elseif ($alias_tag == "Statement Last Year End - End (FP)") 
            {
                // $temp_content = date('Y', strtotime($fs_company_info[0]['last_fye_end']));
                // $temp_content = date('d.m.Y', strtotime($fs_company_info[0]['last_fye_end']));
                $temp_content = $last_fye_end;

                // $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $temp_content, $wr_content);
                $replace_wr = $this->build_wr_with_br_tag_without_paragraph($temp_content);
            }
            elseif($alias_tag == "Statement Last Year End - Beginning")
            {
                $temp_content = $last_year_end_begining;
                // $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $temp_content, $wr_content);
                $replace_wr = $this->build_wr_with_br_tag_without_paragraph($temp_content);
            }
            elseif($alias_tag == "Statement Year End NTA - LATEST" || $alias_tag == "Statement Year End NTA - EARLIER")
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

                if($alias_tag == "Statement Year End NTA - LATEST")
                {
                    $temp_content = $display_date_1;
                }
                elseif($alias_tag == "Statement Year End NTA - EARLIER")
                {
                    $temp_content = $display_date_2;
                }

                $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $temp_content, $wr_content);
            }
        }
        elseif($alias_tag == "Statement Year End 1" || $alias_tag == "Statement Year End 2")
        {
            $temp_content = '';

            $current_last_year_end = $this->fs_model->calculate_difference_dates($fs_company_info_id, "General");

            $last_fye_end = $current_last_year_end['last_fye_end'];
            $current_fye_end = $current_last_year_end['current_fye_end'];

            if(empty($last_fye_end))    // prevent '' in date
            {
                $last_fye_end = date('d.m.Y', strtotime($last_fye_end));
            }

            if(empty($current_fye_end))    // prevent '' in date
            {
                $current_fye_end = date('d.m.Y', strtotime($current_fye_end));
            }

            $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

            if($fs_company_info[0]['first_set'])
            {
                if($alias_tag == "Statement Year End 2")
                {
                    // $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], strtoupper($last_fye_begin), $wr_content);
                    $temp_content = $current_fye_end;
                    $replace_wr = $this->build_wr_with_br_tag_without_paragraph($temp_content);

                    // $sye_1 = array(
                    //     'text'         => true,
                    //     'text_content' => $last_fye_end
                    // );

                    // $replace_wr .= $this->xml_code_template($sye_1);

                    // $to = array(
                    //     'br'           => true,
                    //     'text'         => true,
                    //     'text_content' => 'to'
                    // );

                    // $replace_wr .= $this->xml_code_template($to);

                    // $sye_2 = array(
                    //     'br'           => true,
                    //     'text'         => true,
                    //     'text_content' => $current_fye_end
                    // );

                    // $replace_wr .= $this->xml_code_template($sye_2);
                }
            }
            else
            {
                if($alias_tag == "Statement Year End 1")
                {
                    $temp_content = $current_fye_end;

                    // print_r(array($temp_content));
                }
                elseif($alias_tag == "Statement Year End 2")
                {
                    $temp_content = $last_fye_end;
                    // print_r(array($temp_content));
                }

                // $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $temp_content, $wr_content);
                $replace_wr = $this->build_wr_with_br_tag_without_paragraph($temp_content);
            }

            // $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $temp_content, $wr_content);
        }
        /* -- Statement of directors -- */
        elseif($alias_tag == "Directors name and date of appointment")
        {
            $temp_content = '';
            $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);
            // $client_info  = $this->fs_model->get_client_info_by_companycode($fs_company_info[0]["company_code"]);
            $fs_directors = $this->fs_model->get_fs_appt_directors($fs_company_info_id);

            // print_r($fs_directors[0]);

            foreach($fs_directors as $key => $director)
            {
                // print_r($director);
                if($director['show_appt_date'])
                {
                    $temp_content = $this->encryption->decrypt($director['name']) . ' ' . '(appointed on ' . date('d.m.Y', strtotime($director['date_of_appointment'])) . ')';
                }
                else
                {
                    $temp_content = $this->encryption->decrypt($director['name']) . ' ';
                }

                // print_r(array($temp_content));

                if($key == 0)
                {
                    $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $temp_content, $wr_content);
                }
                else
                {
                    $type = array(
                        'br'           => true,
                        'bold'         => false,
                        'italic'       => false,
                        'text'         => true,
                        'text_content' => $temp_content
                    );

                    $replace_wr .= $this->xml_code_template($type);
                }
            }

            if(count($fs_directors) == 0)   // should have at least a director, jz incase no record, we put "NOT APPLICABLE
            {
                $type = array(
                        'br'           => true,
                        'bold'         => false,
                        'italic'       => false,
                        'text'         => true,
                        'text_content' => "NOT AVAILABLE"
                    );

                    $replace_wr .= $this->xml_code_template($type);
            }
        }
        elseif($alias_tag == "Functional Presentation Currency" || $alias_tag == "Functional Currency - Last Year" || $alias_tag == "Functional Currency - Current Year" || $alias_tag == "Presentation Curreny - Last Year" || $alias_tag == "Presentation Curreny Country - Last Year" || $alias_tag == "Functional Currency - Reason of changing")
        {
            // GET FP CURRENCY INFO
            $fp_currency_info = $this->fs_model->get_fs_fp_currency_details($fs_company_info_id);

            if($alias_tag == "Functional Presentation Currency")
            {
                $temp_content = $this->fs_model->get_currency_info($fp_currency_info[0]['current_year_pc_currency_id'])[0]['name'];
            }

            if($alias_tag == "Functional Currency - Last Year")
            {
                $temp_content = $this->fs_model->get_currency_info($fp_currency_info[0]['last_year_fc_currency_id'])[0]['name'];
            }

            if($alias_tag == "Functional Currency - Current Year")
            {
                $temp_content = $this->fs_model->get_currency_info($fp_currency_info[0]['current_year_fc_currency_id'])[0]['name'];
            }

            if($alias_tag == "Presentation Curreny - Last Year")
            {
                $temp_content = $this->fs_model->get_currency_info($fp_currency_info[0]['last_year_pc_currency_id'])[0]['name'];
            }

            if($alias_tag == "Functional Currency - Reason of changing")
            {
                $temp_content = $fp_currency_info[0]['reason_of_changing_fc'];
            }

            if($alias_tag == "Presentation Curreny Country - Last Year")
            {
                $temp_content = $this->fs_model->get_currency_info($fp_currency_info[0]['current_year_fc_currency_id'])[0]['country_name'];
            }

            $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $temp_content, $wr_content);
        }
        elseif($alias_tag == "Taxation - Deferred Tax Arising") // Note 2 - Taxation
        {
            $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

            if($fs_company_info[0]['group_type'] != '1') // hide content if no group
            {
                $temp_content = "Deferred tax arising from a business combination is adjusted against goodwill on acquisition.";
            }
            else
            {
                $temp_content = ' ';
            }

            $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $temp_content, $wr_content);
        }
        elseif($alias_tag == "PPE - Depreciation method")
        {
            $ppe_info_data = $this->fs_notes_model->get_fs_sub_ppe_info($fs_company_info_id);

            if(count($ppe_info_data) > 0)
            {
                $temp_content = $ppe_info_data[0]['method_name'];
            }
            else
            {
                $temp_content = '______________';
            }

            $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $temp_content, $wr_content);
        }
        elseif($alias_tag == "Inventories - Net Realizable Value")
        {
            $inventories_info = $this->fs_notes_model->get_fs_sub_inventories_info($fs_company_info_id);

            if(isset($inventories_info[0]['name']))
            {
                $temp_content = $inventories_info[0]['name'];
            }
            else
            {
                $temp_content = '_______________________';
            }

            $replace_wr = $this->replace_wt_in_wr($wt_template[0][0], $temp_content, $wr_content);
        }
        // elseif($alias_tag == "Provision - Model Content")
        // {
        //     $p = $this->fs_notes_model->get_fs_provision($fs_company_info_id);

        //     $temp_p = '';

        //     if(count($p) > 0)
        //     {
        //         $wsz_template = $this->get_tag('w:sz', $wr_content);

        //         $br_type = array(
        //                         'br'           => true,
        //                         'bold'         => false,
        //                         'text'         => false,
        //                         // 'wrsidR'       => '00BB4CB4',
        //                         // 'wrsidRPr'     => '00056540',
        //                         'wsz'          => '<w:sz w:val="22"/>',
        //                         'wszCs'        => '<w:szCs w:val="22"/>'
        //                     );

        //         foreach ($p as $p_key => $p_value) 
        //         {
        //             if($p_value['is_shown'] == '1')
        //             {
        //                 // print_r($p_value);
        //                 // if($key < count($p) - 1) // if not last counter, add in <br/><br/>
        //                 // {
        //                     $model_content = nl2br($p_value['content']);
        //                     $br_check_model_content = explode("<br />", $model_content);

        //                     // print_r($br_check_model_content);

        //                     if(count($br_check_model_content) > 1)
        //                     {
        //                         $temp_wr = '';

        //                         foreach ($br_check_model_content as $key => $value) 
        //                         {
        //                             $value = trim(preg_replace('/\s\s+/', ' ', $value));

        //                             // if(!empty($value))
        //                             // {
        //                             //     $type = array(
        //                             //         'br'           => false,
        //                             //         'bold'         => false,
        //                             //         'text'         => true,
        //                             //         'text_content' => $value,
        //                             //         // 'wsz'          => $wsz_template[0][0],
        //                             //         'wsz'          => '<w:sz w:val="22"/>',
        //                             //         'wszCs'        => '<w:szCs w:val="22"/>'
        //                             //     );

        //                             //     $temp_wr .= $this->xml_code_template($type);
        //                             // }
        //                             // else
        //                             // {
        //                             //     if($key != (count($br_check_model_content) - 1))
        //                             //     {
        //                             //         $temp_wr .= $this->xml_code_template($br_type);
        //                             //         $temp_wr .= $this->xml_code_template($br_type);
        //                             //     }
        //                             // }
        //                         }
        //                     }
        //                     else
        //                     {
        //                         $temp_wr = '';
        //                         $value = trim(preg_replace('/\s\s+/', ' ', $p_value['content']));

        //                         $type = array(
        //                             'br'           => false,
        //                             'bold'         => false,
        //                             'text'         => true,
        //                             'text_content' => $value,
        //                             'wsz'          => '<w:sz w:val="22"/>',
        //                             'wszCs'        => '<w:szCs w:val="22"/>',
        //                             // 'wrsidRPr'     => '00056540'
        //                         );

        //                         $temp_wr = $this->replace_wt_in_wr($wt_template[0][0], $value, $wr_content);

        //                         if($p_key != (count($p) - 1))
        //                         {
        //                             $temp_wr .= $this->xml_code_template($br_type);
        //                         }
        //                     }

        //                     $temp_p .= $temp_wr;
        //             }
        //             else
        //             {
        //                 $temp_p = $this->replace_wt_in_wr($wt_template[0][0], $p_value['content'], $wr_content);
        //             }
        //         }

        //         $replace_wr = $temp_p;
        //     }
        // }
        // elseif($alias_tag == "Tag_name")
        // {

        // }
        else
        {
            $replace_wr = $wr_content;
        }

        return $replace_wr;
    }

    public function replace_verbs_plural($alias_tag, $fs_company_info_id, $wt_template, $wr_content)
    {
        $content = '';

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $client_info = $this->db->query("SELECT * FROM client WHERE company_code='" . $fs_company_info[0]["company_code"] . "'");
        $client_info = $client_info->result_array();

        $directors = $this->fs_model->get_fs_dir_statement_director($fs_company_info_id);

        $isPlural = count($directors) > 1? true: false;

        if($alias_tag == "Group and the Company")
        {     
            if($fs_company_info[0]['group_type'] != 1)
            {
                $content = 'Group and the Company';
            }
            else 
            {
                $content = 'Company';
            }
        }
        elseif($alias_tag == "Group/Company")
        {     
            if($fs_company_info[0]['group_type'] != 1)
            {
                $content = 'Group';
            }
            else 
            {
                $content = 'Company';
            }
        }
        elseif($alias_tag == "sing/plu s")
        {
            if($isPlural)
            {
                $content = "s";
            }
            else
            {
                $content = "{s}";
            }
        }
        elseif($alias_tag == "sing/plu s'")
        {     
            if($isPlural)
            {
                $content = "s'";
            }
            else
            {
                $content = "'s";
            }
        }
        elseif($alias_tag == "tense s")
        {
            if(!$isPlural)
            {
                $content = "s";
            }
            else
            {
                $content = "{s}";
            }
        }
        elseif($alias_tag == "is/are")
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
        elseif($alias_tag == "the/their")
        {
            if(!$isPlural)
            {
                $content = "the";
            }
            else
            {
                $content = "their";
            }
        }

        $replace_wt = '<w:t>' . $content . '</w:t>';
        $replace_wr = str_replace($wt_template[0][0], $replace_wt, $wr_content);    // replace the w:t inside w:r

        if($alias_tag == "sing/plu s")
        {
            // print_r($replace_wr);
        }

        return $replace_wr;
    }

    public function note_show_hide_part($alias_value, $fs_company_info_id)   // hide_needed result for Notes to financial statements section
    {
        $hide_needed = true;

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);
        $final_report_type  = $this->fs_model->get_final_document_type($fs_company_info_id);

        if(in_array($alias_value, $this->get_fs_note_title_json()->audit_report))
        {
            $independent_aud_report_data = $this->fs_model->get_this_independent_aud_report($fs_company_info_id);

            if($alias_value == "Key audit matter")
            {
                if(count($independent_aud_report_data) > 0)
                {
                    if($independent_aud_report_data[0]['fs_opinion_type_id'] != 4 && !empty($independent_aud_report_data[0]["key_audit_matter"]))
                    {
                        $hide_needed = false;
                    }
                }
            }
            elseif($alias_value == "Other information")
            {
                if(count($independent_aud_report_data) > 0)
                {
                    if($independent_aud_report_data[0]['fs_opinion_type_id'] == 1)
                    {
                        $hide_needed = false;
                    }
                }
            }
            elseif($alias_value == "Other information content")
            {
                if(count($independent_aud_report_data) > 0)
                {
                    if($independent_aud_report_data[0]['fs_opinion_type_id'] == 1)
                    {
                        $hide_needed = false;
                    }
                }
            }
        }
        elseif($alias_value == "Note 1 - Domicile and activities")
        {
            $hide_needed = false;
        }
        elseif($alias_value == "Note 1 - Holding company")
        {
            $ultimate_info = $this->db->query("SELECT fs_dir_statement_company.*, country.name, country.nicename
                                                    FROM fs_dir_statement_company 
                                                    LEFT JOIN fs_country country ON country.id = fs_dir_statement_company.country_id
                                                    WHERE fs_dir_statement_company.fs_company_info_id=" . $fs_company_info_id . " AND fs_dir_statement_company.fs_company_type_id=1");
            $ultimate_info = $ultimate_info->result_array();

            if(count($ultimate_info) > 0)
            {
                $hide_needed = false;
            }
            else // hide ultimate company part
            {   
                $hide_needed = true;
            }
        }
        elseif($alias_value == "Note 1 - Change of company name")
        {
            if(!empty($fs_company_info[0]['old_company_name']))  // hide change company name part
            {   
                $hide_needed = false;
            }
            else
            {
                $hide_needed = true;
            }
        }
        elseif($alias_value == "Note 2 Basis of preparation" || $alias_value == "Note 2 Basis of preparation (1)" || $alias_value == "Note 2.1 - company liquidated" || $alias_value == "Note 2 Basis of preparation (2)")
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 3); // id = 3 for "Note 2 Basis of preparation"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                if($alias_value == "Note 2.1 - company liquidated")
                {
                    if($fs_company_info[0]['company_liquidated'])
                    {
                        $hide_needed = false;
                    }
                    else
                    {
                        $hide_needed = true;
                    }
                }
                else
                {
                    $hide_needed = !($main_content_checked);
                }
            }
        }
        elseif($alias_value == "Note 2 Use of estimates and judgments" || $alias_value == "Note 2 Use of estimates and judgments (1)")
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 4); // id = 4 for "Note 2 Use of estimates and judgments"
            $hide_needed = !($main_content_checked);
        }
        elseif($alias_value == "Note 2 Functional and presentation currency" || $alias_value == "Note 2.3 Functional and presentation currency - same" || $alias_value == "Note 2.3 Functional and presentation currency - different" || $alias_value == "Note 2.3 - Company has subsidiary" || $alias_value == "Note 2.3 Company change FC")
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 5);

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                if($alias_value == "Note 2.3 Functional and presentation currency - same" || $alias_value == "Note 2.3 Functional and presentation currency - different")
                {
                    $fp_currency_info = $this->fs_model->get_fs_fp_currency_details($fs_company_info_id);

                    // DISPLAY PART IF FC AND PC ARE SAME
                    if($fp_currency_info[0]['last_year_fc_currency_id'] == $fp_currency_info[0]['last_year_pc_currency_id'])
                    {
                        if($alias_value == "Note 2.3 Functional and presentation currency - same")
                        {
                            $hide_needed = false;
                        }
                        elseif($alias_value == "Note 2.3 Functional and presentation currency - different")
                        {
                            $hide_needed = true;
                        }
                    }
                    else
                    {
                        if($alias_value == "Note 2.3 Functional and presentation currency - same")
                        {
                            $hide_needed = true;
                        }
                        elseif($alias_value == "Note 2.3 Functional and presentation currency - different")
                        {
                            $hide_needed = false;
                        }
                    }
                }
                elseif($alias_value == "Note 2.3 - Company has subsidiary")
                {
                    if($fs_company_info[0]['group_type'] != 1) 
                    {
                        $hide_needed = false;
                    }
                    else
                    {
                        $hide_needed = true;
                    }
                }
                elseif($alias_value == "Note 2.3 Company change FC")
                {
                    if($fp_currency_info[0]['last_year_fc_currency_id'] != $fp_currency_info[0]['current_year_fc_currency_id'])
                    {
                        $hide_needed = false;
                    }
                    else
                    {
                        $hide_needed = true;
                    }
                }
                else
                {
                    $hide_needed = !($main_content_checked);
                }
            }
        }
        elseif($alias_value == "Note 2 Basis of consolidation (title)" || $alias_value == "Note 2 Basis of consolidation (1)") // for Small FRS (audited) 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 115);
            $hide_needed = !($main_content_checked);
        }
        elseif($alias_value == "Note 2 Foreign currency transactions and balances" || $alias_value == "Note 2 Foreign currency transactions and balances (1)" || $alias_value == "Note 2.4 Company has foreign subsidiaries")
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 6);

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                if($alias_value == "Note 2.4 Company has foreign subsidiaries")
                {
                    if($fs_company_info[0]['group_type'] == '1') 
                    {
                        $hide_needed = true;
                    }
                    else
                    {
                        $iis_db = $this->db->query("SELECT * FROM fs_investment_in_subsidiaries iis 
                                                    LEFT JOIN fs_note_details nd ON nd.id = iis.fs_note_details_id
                                                    LEFT JOIN fs_categorized_account_round_off fca ON fca.id = nd.fs_categorized_account_round_off_id
                                                    LEFT JOIN fs_country c ON c.id = iis.country_id
                                                    WHERE iis.parent_id = 0 AND c.name!= 'SINGAPORE' AND fca.fs_company_info_id =" . $fs_company_info_id);

                        $iis_db = $iis_db->result_array();

                        if(count($iis_db) == 0)
                        {
                            // $company_has_foreign_subsidiary_template = $this->get_part_of_template('<div class="company_foreign_subsidiaries"', 'div', $content_template);
                            // $content_template = preg_replace('/' . $company_has_foreign_subsidiary_template[0][0] . '/', "", $content_template, 1);
                            $hide_needed = true;
                        }
                        else
                        {
                            $hide_needed = false;
                        }
                    }
                }
                else
                {
                    $hide_needed = !($main_content_checked);
                }
            }
        }
        // Group accounting
        elseif($alias_value == "Note 2 - Group accounting (title)" || $alias_value == "Note 2.5 - Group accounting (i)" || $alias_value == "Note 2.5 - Group accounting (ii)" || $alias_value == "Note 2.5 - Group accounting (iii)" || $alias_value == "Note 2.5 - Group accounting (iv)" || $alias_value == "Note 2.5 - Group accounting (v)" || $alias_value == "Note 2.5 - Group accounting (vi)" || $alias_value == "Note 2 - Group accounting (1)")
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 7);

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                $fs_ntfs_layout_template_default_id = 0;

                if($alias_value == "Note 2 - Group accounting (title)")
                {
                    $fs_ntfs_layout_template_default_id = 7; 
                }
                if($alias_value == "Note 2.5 - Group accounting (i)")
                {
                    $fs_ntfs_layout_template_default_id = 8;
                }
                elseif($alias_value == "Note 2.5 - Group accounting (ii)")
                {
                    $fs_ntfs_layout_template_default_id = 9;
                }
                elseif($alias_value == "Note 2.5 - Group accounting (iii)")
                {
                    $fs_ntfs_layout_template_default_id = 10;
                }
                elseif($alias_value == "Note 2.5 - Group accounting (iv)")
                {
                    $fs_ntfs_layout_template_default_id = 11;
                }
                elseif($alias_value == "Note 2.5 - Group accounting (v)")
                {
                    $fs_ntfs_layout_template_default_id = 12;
                }
                elseif($alias_value == "Note 2.5 - Group accounting (vi)")
                {
                    $fs_ntfs_layout_template_default_id = 13;
                }
                elseif($alias_value == "Note 2 - Group accounting (1)")
                {
                    $fs_ntfs_layout_template_default_id = 14;
                }

                $content_checked = $this->get_checked_result_section($fs_company_info_id, $fs_ntfs_layout_template_default_id);
                $hide_needed = !($content_checked);
            }
        }
        // Revenue
        elseif ($alias_value == "Note 2 - Revenue (title)" || $alias_value == "Note 2 - Revenue (1)" || $alias_value == "Note 2 - Revenue content" || $alias_value == "Note 2 - Revenue (i)" || $alias_value == "Note 2 - Revenue (ii)" || $alias_value == "Note 2 - Revenue (iii)" || $alias_value == "Note 2 - Revenue (iv)" || $alias_value == "Note 2 - Revenue (v)") 
        {
            $fs_ntfs_layout_template_default_id = 0;

            if($alias_value == "Note 2 - Revenue (title)" || $alias_value == "Note 2 - Revenue (1)" || $alias_value == "Note 2 - Revenue content")
            {
                $fs_ntfs_layout_template_default_id = 15; 
            }
            elseif($alias_value == "Note 2 - Revenue (i)")
            {
                $fs_ntfs_layout_template_default_id = 16;
            }
            elseif($alias_value == "Note 2 - Revenue (ii)")
            {
                $fs_ntfs_layout_template_default_id = 17;
            }
            elseif($alias_value == "Note 2 - Revenue (iii)")
            {
                $fs_ntfs_layout_template_default_id = 18;
            }
            elseif($alias_value == "Note 2 - Revenue (iv)")
            {
                $fs_ntfs_layout_template_default_id = 19;
            }
            elseif($alias_value == "Note 2 - Revenue (v)")
            {
                $fs_ntfs_layout_template_default_id = 20;
            }

            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 15);

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                $content_checked = $this->get_checked_result_section($fs_company_info_id, $fs_ntfs_layout_template_default_id);
                $hide_needed = !($content_checked);
            }
        }
        // Emplyee benefits
        elseif ($alias_value == "Note 2 - Employee benefits (1)"|| $alias_value == "Note 2 - Employee benefits (i)" || $alias_value == "Note 2 - Employee benefits (ii)" || $alias_value == "Note 2 - Employee benefits (iii)")
        {
            $fs_ntfs_layout_template_default_id = 0;

            if($alias_value == "Note 2 - Employee benefits (1)")
            {
                $fs_ntfs_layout_template_default_id = 21;
            }
            elseif($alias_value == "Note 2 - Employee benefits (i)")
            {
                $fs_ntfs_layout_template_default_id = 22;
            }
            elseif($alias_value == "Note 2 - Employee benefits (ii)")
            {
                $fs_ntfs_layout_template_default_id = 23;
            }
            elseif($alias_value == "Note 2 - Employee benefits (iii)")
            {
                $fs_ntfs_layout_template_default_id = 24;
            }

            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 21);

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                $content_checked = $this->get_checked_result_section($fs_company_info_id, $fs_ntfs_layout_template_default_id);
                $hide_needed = !($content_checked);
            }
        }
        // Leases
        elseif ($alias_value == "Note 2 - Leases" || $alias_value == "Note 2 - Leases (i)" || $alias_value == "Note 2 - Leases (ii)" || $alias_value == "Note 2 - Leases (1)") 
        {
            $fs_ntfs_layout_template_default_id = 0;

            if($alias_value == "Note 2 - Leases" || $alias_value == "Note 2 - Leases (1)")
            {
                $fs_ntfs_layout_template_default_id = 25;
            }
            elseif($alias_value == "Note 2 - Leases (i)")
            {
                $fs_ntfs_layout_template_default_id = 26;
            }
            elseif($alias_value == "Note 2 - Leases (ii)")
            {
                $fs_ntfs_layout_template_default_id = 27;
            }

            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 25);

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                $content_checked = $this->get_checked_result_section($fs_company_info_id, $fs_ntfs_layout_template_default_id);
                $hide_needed = !($content_checked);
            }
        }
        elseif ($alias_value == "Note 2 - Borrowing costs (title)" || $alias_value == "Note 2 - Borrowing costs") 
        {
            $content_checked = $this->get_checked_result_section($fs_company_info_id, 28);
            $hide_needed = !($content_checked);
        }
        elseif ($alias_value == "Note 2 - Taxation (title)" || $alias_value == "Note 2 - Taxation") 
        {
            $content_checked = $this->get_checked_result_section($fs_company_info_id, 29);
            $hide_needed = !($content_checked);
        }
        elseif ($alias_value == "Note 2 - Investment in associate and joint ventures (title)" || $alias_value == "Note 2 - Investment in associate and joint ventures") 
        {
            $content_checked = $this->get_checked_result_section($fs_company_info_id, 30);
            $hide_needed = !($content_checked);
        }
        elseif ($alias_value == "Note 2 - Investment in associates" || $alias_value == "Note 2 - Investment in associates (1)")     // for Small FRS (audited) 
        {
            $content_checked = $this->get_checked_result_section($fs_company_info_id, 116);
            $hide_needed = !($content_checked);
        }
        elseif ($alias_value == "Note 2 - Intangible assets (title)" || $alias_value == "Note 2 - Intangible assets" || $alias_value == "Note 2 - Intangible assets (i)" || $alias_value == "Note 2 - Intangible assets (ii)"
            || $alias_value ==  "Note 2 - Intangible assets (1)" || $alias_value ==  "Note 2 - Intangible assets (2)" || $alias_value ==  "Note 2 - Intangible assets (3)") // Note 2 - Intangible assets (1 - 3) for Small FRS
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 31); // id = 31 for "Note 2 - Intangible assets"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                if($alias_value == "Note 2 - Intangible assets (i)" || $alias_value == "Note 2 - Intangible assets (ii)")
                {
                    $fs_ntfs_layout_template_default_id = 0;

                    if($alias_value == "Note 2 - Intangible assets (i)")
                    {
                        $fs_ntfs_layout_template_default_id = 32;
                    }
                    elseif($alias_value == "Note 2 - Intangible assets (ii)")
                    {
                        $fs_ntfs_layout_template_default_id = 33;
                    }

                    $content_checked = $this->get_checked_result_section($fs_company_info_id, $fs_ntfs_layout_template_default_id);
                    $hide_needed = !($content_checked);
                }
                else
                {
                    $hide_needed = false;
                }
            }
        }
        elseif($alias_value == "Note 2 - Investment properties" || $alias_value == "Note 2 - Investment properties (1)" || $alias_value == "Note 2 - Investment properties (Model content)" || $alias_value == "Note 2 - Investment properties (2)")
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 34); // id = 34 for "Note 2 - Investment properties"
            $hide_needed = !($main_content_checked);
        }
        elseif ($alias_value == "Note 2 - Property, plant and equipment" || $alias_value == "Note 2 - Property, plant and equipment (1)" || $alias_value == "Note 2 - Property, plant and equipment (2)")
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 35); // id = 35 for "Note 2 - Property, plant and equipment"
            $hide_needed = !($main_content_checked);
        }
        elseif ($alias_value == "Note 2 - Impairment of non-financial assets" || $alias_value == "Note 2 - Impairment of non-financial assets (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 36); // id = 36 for "Note 2 - Impairment of non-financial assets"
            $hide_needed = !($main_content_checked);
        }
        elseif ($alias_value == "Note 2 - Impairment of assets" || $alias_value == "Note 2 - Impairment of assets (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 117); 
            $hide_needed = !($main_content_checked);
        }
        elseif ($alias_value == "Note 2 - Inventories" || $alias_value == "Note 2 - Inventories (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 37); // id = 37 for "Note 2 - Inventories"
            $hide_needed = !($main_content_checked);
        }
        elseif ($alias_value == "Note 2 - Trade and other receivables" || $alias_value == "Note 2 - Trade and other receivables (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 113); 
            $hide_needed = !($main_content_checked);
        }
        elseif ($alias_value == "Note 2 - Trade payables" || $alias_value == "Note 2 - Trade payables (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 118); 
            $hide_needed = !($main_content_checked);
        }
        elseif ($alias_value == "Note 2 - Financial instruments" || $alias_value == "Note 2 - Financial instruments (i)" || $alias_value == "Note 2 - Financial instruments (ii)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 38); // id = 38 for "Note 2 - Financial instruments"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                $fs_ntfs_layout_template_default_id = 0;

                if($alias_value == "Note 2 - Financial instruments")
                {
                    $fs_ntfs_layout_template_default_id = 38;
                }
                elseif($alias_value == "Note 2 - Financial instruments (i)")
                {
                    $fs_ntfs_layout_template_default_id = 39;
                }
                elseif($alias_value == "Note 2 - Financial instruments (ii)")
                {
                    $fs_ntfs_layout_template_default_id = 40;
                }

                $content_checked = $this->get_checked_result_section($fs_company_info_id, $fs_ntfs_layout_template_default_id);
                $hide_needed = !($content_checked);
            }
        }
        elseif ($alias_value == "Note 2 - Impairment of financial assets" || $alias_value == "Note 2 - Impairment of financial assets (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 41); // id = 41 for "Note 2 - Impairment of financial assets"
            $hide_needed = !($main_content_checked);
        }
        elseif ($alias_value == "Note 2 - Provision" || $alias_value == "Note 2 - Provision (1)" || $alias_value == "Note 2 - Provision content") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 42); // id = 42 for "Note 2 - Provision"
            $hide_needed = !($main_content_checked);

            $p_content = $this->fs_notes_model->get_fs_provision_content_list($fs_company_info_id);

            // print_r($p_content);

            if((count($p_content)  == 0) && ($alias_value == "Note 2 - Provision content"))
            {
                $hide_needed = true;
            }
        }
        elseif ($alias_value == "Note 2 - Contingencies" || $alias_value == "Note 2 - Contingencies (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 43); // id = 43 for "Note 2 - Contingencies"
            $hide_needed = !($main_content_checked);
        }
        elseif ($alias_value == "Note 2 - Share capital" || $alias_value == "Note 2 - Share capital (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 44); // id = 44 for "Note 2 - Share capital"
            $hide_needed = !($main_content_checked);
        }
        elseif ($alias_value == "Note 2 - Financial guarantee" || $alias_value == "Note 2 - Financial guarantee (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 45); // id = 45 for "Note 2 - Financial guarantee"
            $hide_needed = !($main_content_checked);
        }
        elseif ($alias_value == "Note 2 - Convertible redeemable preference shares" || $alias_value == "Note 2 - Convertible redeemable preference shares (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 46); // id = 46 for "Note 2 - Convertible redeemable preference shares"
            $hide_needed = !($main_content_checked);
        }
        elseif ($alias_value == "Note 2 - Cash and cash equivalents" || $alias_value == "Note 2 - Cash and cash equivalents (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 47); // id = 47 for "Note 2 - Cash and cash equivalents"
            $hide_needed = !($main_content_checked);
        }
        elseif ($alias_value == "Note 2 - Related party (title)" || $alias_value == "Note 2 - Related party" || $alias_value == "Note 2 - Related party (i)" || $alias_value == "Note 2 - Related party (ii)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 48); // id = 48 for "Note 2 - Related party"
            $hide_needed = !($main_content_checked);
        }
        elseif ($alias_value == "Note 2 - Significant accounting estimates and judgments" || $alias_value == "Note 2 - Significant accounting estimates and judgments (1)" || $alias_value == "Note 2 - Significant accounting estimates and judgments (i)" || $alias_value == "Note 2 - Significant accounting estimates and judgments (ii)" || $alias_value == "Note 2 - Significant accounting estimates and judgments (iii)" || $alias_value == "Note 2 - Significant accounting estimates and judgments (iv)" || $alias_value == "Note 2 - Significant accounting estimates and judgments (v)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 49); // id = 49 for "Note 2 - Significant accounting estimates and judgments"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                $fs_ntfs_layout_template_default_id = 0;

                if($alias_value == "Note 2 - Significant accounting estimates and judgments" || $alias_value == "Note 2 - Significant accounting estimates and judgments (1)")
                {
                    $fs_ntfs_layout_template_default_id = 49;
                }
                elseif($alias_value == "Note 2 - Significant accounting estimates and judgments (i)")
                {
                    $fs_ntfs_layout_template_default_id = 50;
                }
                elseif($alias_value == "Note 2 - Significant accounting estimates and judgments (ii)")
                {
                    $fs_ntfs_layout_template_default_id = 51;
                }
                elseif($alias_value == "Note 2 - Significant accounting estimates and judgments (iii)")
                {
                    $fs_ntfs_layout_template_default_id = 52;
                }
                elseif($alias_value == "Note 2 - Significant accounting estimates and judgments (iv)")
                {
                    $fs_ntfs_layout_template_default_id = 53;
                }
                elseif($alias_value == "Note 2 - Significant accounting estimates and judgments (v)")
                {
                    $fs_ntfs_layout_template_default_id = 54;
                }

                $content_checked = $this->get_checked_result_section($fs_company_info_id, $fs_ntfs_layout_template_default_id);
                $hide_needed = !($content_checked);
            }
        }
        elseif ($alias_value == "Note 3 - Employee benefits expense" || $alias_value == "Note 3 - Employee benefits expense (content)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 55); // id = 55 for "Note 3 - Employee benefits expense"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                if($alias_value == "Note 3 - Employee benefits expense (content)")
                {
                    $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

                    if(count($p) == 0)
                    {
                        $hide_needed = true;    // hide if content checked is false or not saved.
                    }
                    else
                    {
                        $hide_needed = false;
                    }
                }
                else
                {
                    $hide_needed = false;
                }
            }
        }
        elseif ($alias_value == "Note 4 - Profit before tax (title)" || $alias_value == "Note 4 - Profit before tax" || $alias_value == "Note 4 - Profit before tax (table)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 56); // id = 56 for "Note 4 - Profit before tax"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                $hide_needed = false;
            }
        }
        elseif ($alias_value == "Note 5 - Tax expense" || $alias_value == "Note 5 - Tax expense (table_1)" || $alias_value == "Note 5 - Tax expense (1)" || $alias_value == "Note 5 - Tax expense (table_2)" || $alias_value == "Note 5 - Tax expense (textarea 1)" || $alias_value == "Note 5 - Tax expense (content - company has unabsorbed tax losses)" || 
            // $alias_value == "Note 5 - Tax expense (2)" || 
            $alias_value == "Note 5 - Tax expense (table_3)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 57); // id = 57 for "Note 5 - Tax expense"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                if($alias_value == "Note 5 - Tax expense (textarea 1)" || $alias_value == "Note 5 - Tax expense (content - company has unabsorbed tax losses)")
                {
                    $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

                    if(count($p) == 0)
                    {
                        $hide_needed = true;    // hide if content checked is false or not saved.
                    }
                    else
                    {
                        if($p[0]['is_shown'])
                        {
                            $hide_needed = false;
                        }
                        else
                        {
                            $hide_needed = true;
                        }
                    }
                }
                else
                {
                    $hide_needed = false;
                }
            }
        }
        elseif ($alias_value == "Note 6 - Investment in subsidiaries" || $alias_value == "Note 6 - Investment in subsidiaries (table_1)" 
            || $alias_value == "Note 6 - Investment in subsidiaries (i)" || $alias_value == "Note 6 - Investment in subsidiaries (i) (table_1)" || $alias_value == "Note 6 - Investment in subsidiaries (i) (1)" || $alias_value == "Note 6 - Investment in subsidiaries (i) (content)"
            || $alias_value == "Note 6 - Investment in subsidiaries (ii)" || $alias_value == "Note 6 - Investment in subsidiaries (ii) (table_1)" || $alias_value == "Note 6 - Investment in subsidiaries (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 58); // id = 58 for "Note 6 - Investment in subsidiaries"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                $fs_ntfs_layout_template_default_id = 0;

                if($alias_value == "Note 6 - Investment in subsidiaries" || $alias_value == "Note 6 - Investment in subsidiaries (1)")
                {
                    $fs_ntfs_layout_template_default_id = 58;
                }
                if($alias_value == "Note 6 - Investment in subsidiaries (i)" || $alias_value == "Note 6 - Investment in subsidiaries (i) (table_1)" || $alias_value == "Note 6 - Investment in subsidiaries (i) (1)" || $alias_value == "Note 6 - Investment in subsidiaries (i) (content)")
                {
                    $fs_ntfs_layout_template_default_id = 59;
                }
                elseif($alias_value == "Note 6 - Investment in subsidiaries (ii)" || $alias_value == "Note 6 - Investment in subsidiaries (ii) (table_1)")
                {
                    $fs_ntfs_layout_template_default_id = 60;
                }

                $content_checked = $this->get_checked_result_section($fs_company_info_id, $fs_ntfs_layout_template_default_id);
                $hide_needed = !($content_checked);
            }
        }
        elseif ($alias_value == "Note 7 - Investment in associates" || $alias_value == "Note 7 - Investment in associates (table_1)" || $alias_value == "Note 7 - Investment in associates (1)" || $alias_value == "Note 7 - Investment in associates (table_2)" || $alias_value == "Note 7 - Investment in associates (2)" || $alias_value == "Note 7 - Investment in associates (table_3)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 61); // id = 61 for "Note 7 - Investment in associates"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                if($alias_value == "Note 7 - Investment in associates (1)")
                {
                    if($final_report_type != 1) // for small FRS
                    {
                        $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

                        if(count($p) == 0)
                        {
                            $hide_needed = true;    // hide if content checked is false or not saved.
                        }
                        else
                        {
                            $hide_needed = false;
                        }
                    }
                    else // for Full FRS
                    {
                        $hide_needed = false;
                    }
                }
                else
                {
                    $hide_needed = false;
                }
            }
        }
        elseif ($alias_value == "Note 8 - Investment in joint venture" || $alias_value == "Note 8 - Investment in joint venture (1)" || $alias_value == "Note 8 - Investment in joint venture (table_1)" || $alias_value == "Note 8 - Investment in joint venture (2)" 
            // || $alias_value == "Note 8 - Investment in joint venture (table_2)"
        ) 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 62); // id = 62 for "Note 8 - Investment in joint venture"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                if($alias_value == "Note 8 - Investment in joint venture (1)")
                {
                    if($final_report_type != 1)
                    {
                        $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

                        if(count($p) == 0)
                        {
                            $hide_needed = true;    // hide if content checked is false or not saved.
                        }
                        else
                        {
                            $hide_needed = false;
                        }
                    }
                }
                else
                {
                    $hide_needed = false;
                }
            }
        }
        elseif ($alias_value == "Note 9 - Intangible assets" || $alias_value == "Note 9 - Intangible assets (table_1)" || $alias_value == "Note 9 - Intangible assets (textarea)" || $alias_value == "Note 9 - Intangible assets (1)")  
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 63); // id = 63 for "Note 9 - Intangible assets"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                if($alias_value == "Note 9 - Intangible assets (textarea)" || $alias_value == "Note 9 - Intangible assets (1)")
                {
                    $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

                    if(count($p) == 0)
                    {
                        $hide_needed = true;    // hide if content checked is false or not saved.
                    }
                    else
                    {
                        $hide_needed = false;
                    }
                }
                else
                {
                    $hide_needed = false;
                }
            }
        }
        elseif ($alias_value == "Note 10 - Insured benefits" || $alias_value == "Note 10 - Insured benefits (table_1)" || $alias_value == "Note 10 - Insured benefits (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 64); // id = 64 for "Note 10 - Insured benefits"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                if($alias_value == "Note 10 - Insured benefits (1)")
                {
                    $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

                    if(count($p) == 0)
                    {
                        $hide_needed = true;    // hide if content checked is false or not saved.
                    }
                    else
                    {
                        $hide_needed = false;
                    }
                }
                else
                {
                    $hide_needed = false;
                }
            }
        }
        elseif ($alias_value == "Note 11 - Investment properties" || $alias_value == "Note 11 - Investment properties cost_model (table_1)" || $alias_value == "Note 11 - Investment properties (1)" || $alias_value == "Note 11 - Investment properties (table_2)" || $alias_value == "Note 11 - Investment properties (2)" || $alias_value == "Note 11 - Investment properties (3)" || $alias_value == "Note 11 - Investment properties (6)" || $alias_value == "Note 11 - Investment properties (7)" || $alias_value == "Note 11 - Investment properties (table_3)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 65); // id = 65 for "Note 11 - Investment properties"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                if($alias_value == "Note 11 - Investment properties (3)")
                {
                    $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

                    if(count($p) == 0)
                    {
                        $hide_needed = true;    // hide if content checked is false or not saved.
                    }
                    else
                    {
                        $hide_needed = false;
                    }
                }
                else
                {
                    $hide_needed = false;
                }
            }
        }
        elseif ($alias_value == "Note 12 - Property, plant and equipment" || $alias_value == "Note 12 - Property, plant and equipment (table_1)" || $alias_value == "Note 12 - Property, plant and equipment (1)" || $alias_value == "Note 12 - Property, plant and equipment - 1") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 66); // id = 66 for "Note 12 - Property, plant and equipment"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                if($alias_value == "Note 12 - Property, plant and equipment - 1" || $alias_value == "Note 12 - Property, plant and equipment (1)")
                {
                    $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

                    if(count($p) == 0)
                    {
                        $hide_needed = true;    // hide if content checked is false or not saved.
                    }
                    else
                    {
                        $hide_needed = false;
                    }
                }
                elseif($alias_value == "Note 12 - Property, plant and equipment (table_1)")
                {
                    $p = $this->get_t1_value_tr_template($alias_value, $fs_company_info_id);

                    if(count($p) > 0)
                    {
                        $hide_needed = false;
                    }
                    else
                    {
                        $hide_needed = true;
                    }
                }
                else
                {
                    $hide_needed = false;
                }
            }
        }
        elseif ($alias_value == "Note 13 - Available for sale" || $alias_value == "Note 13 - Available for sale (table_1)" || $alias_value == "Note 13 - Available for sale (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 67); // id = 67 for "Note 13 - Available for sale"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                if($alias_value == "Note 13 - Available for sale (1)")
                {
                    $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

                    if(count($p) == 0)
                    {
                        $hide_needed = true;    // hide if content checked is false or not saved.
                    }
                    else
                    {
                        $hide_needed = false;
                    }
                }
                else
                {
                    $hide_needed = false;
                }
            }
        }
        elseif ($alias_value == "Note 14 - Inventories" || $alias_value == "Note 14 - Inventories (table_1)" || $alias_value == "Note 14 - Inventories (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 68); // id = 68 for "Note 14 - Inventories"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                if($alias_value == "Note 14 - Inventories (1)")
                {
                    $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

                    if(count($p) == 0)
                    {
                        $hide_needed = true;    // hide if content checked is false or not saved.
                    }
                    else
                    {
                        if($p[0]['is_shown'])
                        {
                            $hide_needed = false;
                        }
                        else
                        {
                            $hide_needed = true;
                        }
                    }
                }
                else
                {
                    $hide_needed = false;
                }
            }
        }
        elseif ($alias_value == "Note 15 - Contract assets and contract liabilities" || $alias_value == "Note 15 - Contract assets and contract liabilities (1)" || $alias_value == "Note 15 - Contract assets and contract liabilities (table_1)" || $alias_value == "Note 15 - Contract assets and contract liabilities (2)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 69); // id = 69 for "Note 15 - Contract assets and contract liabilities"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                $hide_needed = false;
            }
        }
        elseif ($alias_value == "Note 16 - Trade and other receivables" || $alias_value == "Note 16 - Trade and other receivables (table_1)"  || $alias_value == "Note 16 - Trade and other receivables (1)" || $alias_value == "Note 16 - Trade and other receivables (2)"  || $alias_value == "Note 16 - Trade and other receivables (table_2)" || $alias_value == "Note 16 - Trade and other receivables (3)" || $alias_value == "Note 16 - Trade and other receivables (4)" || $alias_value == "Note 16 - Trade and other receivables (table_3)" || $alias_value == "Note 16 - Trade and other receivables (6)"  || $alias_value == "Note 16 - Trade and other receivables (table_4)"  || $alias_value == "Note 16 - Trade and other receivables (7)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 70); // id = 70 for "Note 16 - Trade and other receivables"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                if($alias_value == "Note 16 - Trade and other receivables (3)" || $alias_value == "Note 16 - Trade and other receivables (4)") 
                {
                    $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

                    if(count($p) == 0)
                    {
                        $hide_needed = true;    // hide if content checked is false or not saved.
                    }
                    else
                    {
                        if($p[0]['is_checked'])
                        {
                            $hide_needed = false;
                        }
                        else
                        {
                            $hide_needed = true;
                        }
                    }
                }
                elseif($alias_value == "Note 16 - Trade and other receivables (1)" || $alias_value == "Note 16 - Trade and other receivables (6)" || $alias_value == "Note 16 - Trade and other receivables (7)")
                {
                    if($final_report_type != 1)
                    {
                        $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

                        if(count($p) == 0)
                        {
                            $hide_needed = true;    // hide if content checked is false or not saved.
                        }
                        else
                        {
                            if($p[0]['is_checked'])
                            {
                                $hide_needed = false;
                            }
                            else
                            {
                                $hide_needed = true;
                            }
                        }
                    }
                    else
                    {
                        $p1 = $this->db->query("SELECT lb.*
                                                FROM fs_trade_and_other_receivables_info lb
                                                LEFT JOIN fs_list_trade_and_other_receivables_content lbd ON lbd.id = lb.fs_list_trade_and_other_receivables_content_id
                                                LEFT JOIN fs_list_trade_and_other_receivables_title lbt ON lbt.id = lbd.fs_list_trade_and_other_receivables_title_id
                                                WHERE lb.fs_company_info_id=" . $fs_company_info_id . " AND lbt.id = 1 AND lb.is_checked = 1 ORDER BY lb.order_by");
                        $p1 = $p1->result_array();

                        if(count($p1) > 0)
                        {
                            if($alias_value == "Note 16 - Trade and other receivables (1)")
                            {
                                $hide_needed = false;
                            }
                            elseif($alias_value == "Note 16 - Trade and other receivables (6)" || $alias_value == "Note 16 - Trade and other receivables (7)")
                            {
                                $p2 = $this->db->query("SELECT lb.*
                                                FROM fs_trade_and_other_receivables_info lb
                                                LEFT JOIN fs_list_trade_and_other_receivables_content lbd ON lbd.id = lb.fs_list_trade_and_other_receivables_content_id
                                                LEFT JOIN fs_list_trade_and_other_receivables_title lbt ON lbt.id = lbd.fs_list_trade_and_other_receivables_title_id
                                                WHERE lb.fs_company_info_id=" . $fs_company_info_id . " AND lbt.id = 3 AND lb.is_checked = 1 ORDER BY lb.order_by");
                                $p2 = $p2->result_array();

                                if(count($p2) > 0)
                                {
                                    $hide_needed = false;
                                }
                                else
                                {
                                    $hide_needed = true;
                                }
                            }
                        }
                        else
                        {
                            $hide_needed = true;
                        }
                    }
                }
                else
                {
                    $hide_needed = false;
                }
            }
        }
        elseif ($alias_value == "Note 17 - Other currenct assets" || $alias_value == "Note 17 - Other currenct assets (1)"  || $alias_value == "Note 17 - Other currenct assets (table_1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 71); // id = 71 for "Note 17 - Other currenct assets"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                $hide_needed = false;
            }
        }
        elseif ($alias_value == "Note 18 - Cash and short-term deposits" || $alias_value == "Note 18 - Cash and short-term deposits (table_1)" || $alias_value == "Note 18 - Cash and short-term deposits (1)" || $alias_value == "Note 18 - Cash and short-term deposits (2)" || $alias_value == "Note 18 - Cash and short-term deposits (table_2)" || $alias_value == "Note 18 - Cash and short-term deposits (3)" || $alias_value == "Note 18 - Cash and short-term deposits (table_3)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 72); // id = 72 for "Note 18 - Cash and short-term deposits"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                if($alias_value == "Note 18 - Cash and short-term deposits (1)")
                {
                    $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

                    if(count($p) == 0)
                    {
                        $hide_needed = true;    // hide if content checked is false or not saved.
                    }
                    else
                    {
                        $hide_needed = false;
                    }
                }
                else
                {
                    $hide_needed = false;
                }
            }
        }
        elseif ($alias_value == "Note 19 - Share capital" || $alias_value == "Note 19 - Share capital (1)" || $alias_value == "Note 19 - Share capital (2)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 73); // id = 73 for "Note 6 - Investment in subsidiaries"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                if($alias_value == "Note 19 - Share capital (1)")
                {
                    $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

                    if(count($p) == 0)
                    {
                        $hide_needed = true;    // hide if content checked is false or not saved.
                    }
                    else
                    {
                        $hide_needed = false;
                    }
                }
                else
                {
                    $hide_needed = false;
                }
            }
        }
        elseif ($alias_value == "Note 20 - Other reserves" || $alias_value == "Note 20 - Other reserves (i)" || $alias_value == "Note 20 - Other reserves (ii)" || $alias_value == "Note 20 - Other reserves (iii)" || $alias_value == "Note 20 - Other reserves (iv)" || $alias_value == "Note 20 - Other reserves (v)" || $alias_value == "Note 20 - Other reserves (vi)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 74); // id = 74 for "Note 20 - Other reserves"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                $fs_ntfs_layout_template_default_id = 0;

                if($alias_value == "Note 20 - Other reserves")
                {
                    $fs_ntfs_layout_template_default_id = 74;
                }
                elseif($alias_value == "Note 20 - Other reserves (i)")
                {
                    $fs_ntfs_layout_template_default_id = 75;
                }
                elseif($alias_value == "Note 20 - Other reserves (ii)")
                {
                    $fs_ntfs_layout_template_default_id = 76;
                }
                elseif($alias_value == "Note 20 - Other reserves (iii)")
                {
                    $fs_ntfs_layout_template_default_id = 77;
                }
                elseif($alias_value == "Note 20 - Other reserves (iv)")
                {
                    $fs_ntfs_layout_template_default_id = 78;
                }
                elseif($alias_value == "Note 20 - Other reserves (v)")
                {
                    $fs_ntfs_layout_template_default_id = 79;
                }
                elseif($alias_value == "Note 20 - Other reserves (vi)")
                {
                    $fs_ntfs_layout_template_default_id = 80;
                }

                $content_checked = $this->get_checked_result_section($fs_company_info_id, $fs_ntfs_layout_template_default_id);
                $hide_needed = !($content_checked);
            }
        }
        elseif ($alias_value == "Note 21 - Deferred tax liabilities" || $alias_value == "Note 21 - Deferred tax liabilities (table_1)" || $alias_value == "Note 21 - Deferred tax liabilities (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 81); // id = 81 for "Note 21 - Deferred tax liabilities"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                $hide_needed = false;
            }
        }
        elseif ($alias_value == "Note 22 - Loans and borrowings" || $alias_value == "Note 22 - Loans and borrowings (table_1)" || $alias_value == "Note 22 - Loans and borrowings (1)" || $alias_value == "Note 22 - Loans and borrowings (table_2)" || $alias_value == "Note 22 - Loans and borrowings (2)" || $alias_value == "Note 22 - Loans and borrowings (3)" || $alias_value == "Note 22 - Loans and borrowings (7)" || $alias_value == "Note 22 - Loans and borrowings (table_3)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 82); // id = 82 for "Note 22 - Loans and borrowings"

            if($alias_value == "Note 22 - Loans and borrowings (2)" || $alias_value == "Note 22 - Loans and borrowings (3)")
            {
                $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

                if(count($p) == 0)
                {
                    $hide_needed = true;    // hide if content checked is false or not saved.
                }
                else
                {
                    $hide_needed = false;
                }
            }
            else
            {
                // $hide_needed = !($content_checked);
                if(!$main_content_checked)
                {
                    $hide_needed = true;
                }
                else
                {
                    $hide_needed = false;
                }
            }
        }
        elseif ($alias_value == "Note 23 - Provision" || $alias_value == "Note 23 - Provision (table_1)" || $alias_value == "Note 23 - Provision (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 83); // id = 83 for "Note 23 - Provision"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                $hide_needed = false;
            }
        }
        elseif ($alias_value == "Note 24 - Trade and other payables" || $alias_value == "Note 24 - Trade and other payables (table_1)" || $alias_value == "Note 24 - Trade and other payables (1)" || $alias_value == "Note 24 - Trade and other payables (3)" || $alias_value == "Note 24 - Trade and other payables (table_2)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 84); // id = 84 for "Note 24 - Trade and other payables"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                if($alias_value == "Note 24 - Trade and other payables (1)" && $final_report_type == 1)
                {
                    $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

                    if(count($p) == 0)
                    {
                        $hide_needed = true;    // hide if content checked is false or not saved.
                    }
                    else
                    {
                        $hide_needed = false;
                    }
                }
                else
                {
                    $hide_needed = false;
                }
            }
        }
        elseif ($alias_value == "Note 25 - Other current liabilities" || $alias_value == "Note 25 - Other current liabilities (1)" || $alias_value == "Note 25 - Other current liabilities (table_1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 85); // id = 85 for "Note 25 - Other current liabilities"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                $hide_needed = false;
            }
        }
        elseif ($alias_value == "Note 26 - Related party transactions" || $alias_value == "Note 26 - Related party transactions (i)" || $alias_value == "Note 26 - Related party transactions (table_1)" || $alias_value == "Note 26 - Related party transactions (ii)" || $alias_value == "Note 26 - Related party transactions (ii) - content") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 86); // id = 86 for "Note 26 - Related party transactions"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {   
                $fs_ntfs_layout_template_default_id = 0;

                if($alias_value == "Note 26 - Related party transactions")
                {
                    $fs_ntfs_layout_template_default_id = 86;
                }
                elseif($alias_value == "Note 26 - Related party transactions (i)")
                {
                    $fs_ntfs_layout_template_default_id = 87;
                }
                elseif($alias_value == "Note 26 - Related party transactions (ii)" || $alias_value == "Note 26 - Related party transactions (ii) - content")
                {
                    $fs_ntfs_layout_template_default_id = 88;
                }

                $content_checked = $this->get_checked_result_section($fs_company_info_id, $fs_ntfs_layout_template_default_id);
                $hide_needed = !($content_checked);
            }
        }
        elseif ($alias_value == "Note 27 - Commitments" || $alias_value == "Note 27 - Commitments (i)" || $alias_value == "Note 27 - Commitments (ii)" || $alias_value == "Note 27 - Commitments (ii) (content)" || $alias_value == "Note 27 - Commitments (table_1)" || $alias_value == "Note 27 - Commitments (ii) (1)" || $alias_value == "Note 27 - Commitments (table_2)" || $alias_value == "Note 27 - Commitments (iii)" || $alias_value == "Note 27 - Commitments (iii) (1)" || $alias_value == "Note 27 - Commitments (iii) (content)" || $alias_value == "Note 27 - Commitments (table_3)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 89); // id = 89 for "Note 27 - Commitments"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                $fs_ntfs_layout_template_default_id = 0;

                if($alias_value == "Note 27 - Commitments")
                {
                    $fs_ntfs_layout_template_default_id = 89;
                }
                elseif($alias_value == "Note 27 - Commitments (i)")
                {
                    $fs_ntfs_layout_template_default_id = 90;
                }
                elseif($alias_value == "Note 27 - Commitments (ii)" || $alias_value == "Note 27 - Commitments (ii) (1)" || $alias_value == "Note 27 - Commitments (ii) (content)")
                {
                    $fs_ntfs_layout_template_default_id = 91;
                }
                elseif($alias_value == "Note 27 - Commitments (iii)" || $alias_value == "Note 27 - Commitments (iii) (1)" || $alias_value == "Note 27 - Commitments (iii) (content)")
                {
                    $fs_ntfs_layout_template_default_id = 92;
                }

                $content_checked = $this->get_checked_result_section($fs_company_info_id, $fs_ntfs_layout_template_default_id);

                if($alias_value == "Note 27 - Commitments (ii) (content)" || $alias_value == "Note 27 - Commitments (iii) (content)")
                {
                    $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

                    if(count($p) == 0)
                    {
                        $hide_needed = true;    // hide if content checked is false or not saved.
                    }
                    else
                    {
                        $hide_needed = false;
                    }
                }
                else
                {
                    $hide_needed = !($content_checked);
                }
            }
        }
        elseif ($alias_value == "Note 28 - Contingencies" || $alias_value == "Note 28 - Contingencies (i)" || $alias_value == "Note 28 - Contingencies (i) (1)" || $alias_value == "Note 28 - Contingencies (ii)" || $alias_value == "Note 28 - Contingencies (ii) (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 93); // id = 93 for "Note 28 - Contingencies"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                $fs_ntfs_layout_template_default_id = 0;

                if($alias_value == "Note 28 - Contingencies")
                {
                    $fs_ntfs_layout_template_default_id = 93;
                }
                elseif($alias_value == "Note 28 - Contingencies (i)" || $alias_value == "Note 28 - Contingencies (i) (1)")
                {
                    $fs_ntfs_layout_template_default_id = 94;
                }
                elseif($alias_value == "Note 28 - Contingencies (ii)" || $alias_value == "Note 28 - Contingencies (ii) (1)")
                {
                    $fs_ntfs_layout_template_default_id = 95;
                }

                $content_checked = $this->get_checked_result_section($fs_company_info_id, $fs_ntfs_layout_template_default_id);
                
                if($alias_value == "Note 28 - Contingencies (i) (1)" || $alias_value == "Note 28 - Contingencies (ii) (1)")
                {
                    $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

                    if(count($p) == 0)
                    {
                        $hide_needed = true;    // hide if content checked is false or not saved.
                    }
                    else
                    {
                        $hide_needed = false;
                    }
                }
                else
                {
                    $hide_needed = !($content_checked);
                }
            }
        }
        elseif($alias_value == "Note 29 - Financial Risk Management (title)" || $alias_value == "Note 29 - Financial Risk Management" || $alias_value == "Note 29.1 - Financial Risk Management (1)" || $alias_value == "Note 29.1 - Financial Risk Management (2)" || $alias_value == "Note 29.1 - Financial Risk Management (table_1)" || $alias_value == "Note 29.1 - Financial Risk Management (3)" || $alias_value == "Note 29.1 - Financial Risk Management (table_2)" || $alias_value == "Note 29.1 - Financial Risk Management (4)" || $alias_value == "Note 29.2 - Financial Risk Management (1)" || $alias_value == "Note 29.2 - Financial Risk Management (table_1)" 

            || $alias_value == "Note 29.3 - Financial Risk Management (1)" || $alias_value == "Note 29.3 - Financial Risk Management (2)" || $alias_value == "Note 29.3 - Financial Risk Management (3)" || $alias_value == "Note 29.3 - Financial Risk Management (4)"  || $alias_value == "Note 29.3 - Financial Risk Management (5)" 
            || $alias_value == "Note 29.4 - Financial Risk Management (1)" || $alias_value == "Note 29.4 - Financial Risk Management (table_1) (group)" || $alias_value == "Note 29.4 - Financial Risk Management (table_1) (company)" || $alias_value == "Note 29.4 - Financial Risk Management (2)" || $alias_value == "Note 29.4 - Financial Risk Management (table_2)" || $alias_value == "Note 29.4 - Financial Risk Management (3)")
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 96); // id = 96 for "Note 29 - Financial Risk Management"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                $fs_ntfs_layout_template_default_id = 0;

                if($alias_value == "Note 29 - Financial Risk Management (title)" || $alias_value == "Note 29 - Financial Risk Management")
                {
                    $fs_ntfs_layout_template_default_id = 96;
                }
                elseif($alias_value == "Note 29.1 - Financial Risk Management (1)" || $alias_value == "Note 29.1 - Financial Risk Management (2)" || $alias_value == "Note 29.1 - Financial Risk Management (table_1)" || $alias_value == "Note 29.1 - Financial Risk Management (3)" || $alias_value == "Note 29.1 - Financial Risk Management (table_2)" || $alias_value == "Note 29.1 - Financial Risk Management (4)" || $alias_value == "Note 29.1 - Financial Risk Management (5)" || $alias_value == "Note 29.1 - Financial Risk Management (6)" || $alias_value == "Note 29.1 - Financial Risk Management (7)")
                {
                    $fs_ntfs_layout_template_default_id = 97;
                }
                elseif($alias_value == "Note 29.2 - Financial Risk Management (1)" || $alias_value == "Note 29.2 - Financial Risk Management (table_1)")
                {
                    $fs_ntfs_layout_template_default_id = 98;
                }
                elseif($alias_value == "Note 29.3 - Financial Risk Management (1)" || $alias_value == "Note 29.3 - Financial Risk Management (2)" || $alias_value == "Note 29.3 - Financial Risk Management (3)" || $alias_value == "Note 29.3 - Financial Risk Management (4)" || $alias_value == "Note 29.3 - Financial Risk Management (5)")
                {
                    $fs_ntfs_layout_template_default_id = 99;
                }
                elseif($alias_value == "Note 29.4 - Financial Risk Management (1)" || $alias_value == "Note 29.4 - Financial Risk Management (table_1) (group)" || $alias_value == "Note 29.4 - Financial Risk Management (table_1) (company)" || $alias_value == "Note 29.4 - Financial Risk Management (2)" || $alias_value == "Note 29.4 - Financial Risk Management (table_2)" || $alias_value == "Note 29.4 - Financial Risk Management (3)")
                {
                    $fs_ntfs_layout_template_default_id = 100;
                }

                $content_checked = $this->get_checked_result_section($fs_company_info_id, $fs_ntfs_layout_template_default_id);
                $hide_needed = !($content_checked);

                if(!$hide_needed)
                {
                    if(
                        $alias_value == "Note 29.3 - Financial Risk Management (2)" || 
                        $alias_value == "Note 29.3 - Financial Risk Management (3)" || 
                        $alias_value == "Note 29.3 - Financial Risk Management (4)" || 
                        $alias_value == "Note 29.3 - Financial Risk Management (5)"
                    )
                    {
                        $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

                        // print_r(array($p));

                        if(count($p) == 0)
                        {
                            $hide_needed = true;
                        }
                    }
                    elseif($alias_value == "Note 29.4 - Financial Risk Management (table_1) (group)")
                    {
                        if($fs_company_info[0]['group_type'] == 1)
                        {
                            $hide_needed = true;
                        }
                    }
                }
            }
        }
        elseif ($alias_value == "Note 30 - Fair Value of assets" || $alias_value == "Note 30 - Fair Value of assets (i)" || $alias_value == "Note 30 - Fair Value of assets (table_1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 101); // id = 101 for "Note 30 - Fair Value of assets"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                $hide_needed = false;
            }
        }
        elseif ($alias_value == "Note 31 - Financial Instrument by category (title)" || $alias_value == "Note 31 - Financial Instrument by category (1)" || $alias_value == "Note 31 - Financial Instrument by category (table_1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 102); // id = 102 for "Note 31 - Financial Instrument by category (1)"

            $hide_needed = !($main_content_checked);
        }
        elseif ($alias_value == "Note 32 - Capital Management" || $alias_value == "Note 32 - Capital Management (1)" || $alias_value == "Note 32 - Capital Management (table_1)" || $alias_value == "Note 32 - Capital Management (2)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 103); // id = 58 for "Note 6 - Investment in subsidiaries"

            $hide_needed = !($main_content_checked);
        }
        elseif ($alias_value == "Note 33 - Events occuring after the reporting period" || $alias_value == "Note 33 - Events occuring after the reporting period (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 104); // id = 58 for "Note 6 - Investment in subsidiaries"

            if(!$main_content_checked)
            {
                $hide_needed = true;
            }
            else
            {
                if($alias_value == "Note 33 - Events occuring after the reporting period (1)")
                {
                    $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

                    if(count($p) == 0)
                    {
                        $hide_needed = true;    // hide if content checked is false or not saved.
                    }
                    else
                    {
                        $hide_needed = false;
                    }
                }
                else
                {
                    $hide_needed = false;
                }
            }
        }
        elseif ($alias_value == "Note 34 - Comparative Figures" || $alias_value == "Note 34 - Comparative Figures (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 105); // id = 58 for "Note 6 - Investment in subsidiaries"

            $hide_needed = !($main_content_checked);
        }
        elseif ($alias_value == "Note 35 - Prior year adjustment" || $alias_value == "Note 35 - Prior year adjustment (table_1)" || $alias_value == "Note 35 - Prior year adjustment (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 106); // id = 58 for "Note 6 - Investment in subsidiaries"

            $hide_needed = !($main_content_checked);
        }
        elseif ($alias_value == "Note 36 - Loss per ordinary share" || $alias_value == "Note 36 - Loss per ordinary share (1)" || $alias_value == "Note 36 - Loss per ordinary share (table_1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 107); // id = 58 for "Note 6 - Investment in subsidiaries"

            $hide_needed = !($main_content_checked);
        }
        elseif ($alias_value == "Note 37 - Segmental Reporting" || $alias_value == "Note 37 - Segmental Reporting (1)" || $alias_value == "Note 37 - Segmental Reporting (2)" || $alias_value == "Note 37 - Segmental Reporting (table_1)(group)" || $alias_value == "Note 37 - Segmental Reporting (table_1)(company)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 108); // id = 58 for "Note 6 - Investment in subsidiaries"

            $hide_needed = !($main_content_checked);
        }
        elseif ($alias_value == "Note 38 - Going concern" || $alias_value == "Note 38 - Going concern (1)") 
        {
            $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 109); // id = 58 for "Note 6 - Investment in subsidiaries"

            $hide_needed = !($main_content_checked);
        }
        /* for remove new line to separate into 2 tables. */
        elseif($alias_value == "table new line" || $alias_value == "main category display")
        {
            $hide_needed = true;
        }
        // elseif ($alias_value == "Alias_value" || $alias_value == "Alias_value") 
        // {
        //     $main_content_checked = $this->get_checked_result_section($fs_company_info_id, 58); // id = 58 for "Note 6 - Investment in subsidiaries"

        //     if(!$main_content_checked)
        //     {
        //         $hide_needed = true;
        //     }
        // }

        // elseif ($alias_value == "Alias_value") 
        // {
        //     # code...
        // }

        // if($alias_value == "table new line")
        // {
        //     print_r('Note 29.1');
        // }
        
        return $hide_needed;
    }


    /* --------------------------------------- END OF GENERAL USE --------------------------------------- */

    // public function show_hide_paragraph($updated_xml, $alias_tag, $wsdt_xml, $fs_company_info_id)
    // {
    //     $wsdtcontent = $this->get_part_of_template_include_nested('<w:sdtContent>', 'w:sdtContent', $wsdt_xml);  // get w:sdtContent;

    //     $wpPr = $this->get_part_of_template('<w:pPr>', 'w:pPr', $wsdtcontent[0][0]);    // get w:pPr;

    //     $wrPrContent = $this->get_template_wrPrContent($wsdtcontent[0][0]);

    //     $replaced_wrPrContent = $this->vanish_content_paragraph($alias_tag, $wrPrContent[1][0], $fs_company_info_id);   // add or remove <vanish/> tag

    //     $updated_wpPr_content = str_replace($wrPrContent[1][0], $replaced_wrPrContent, $wpPr);  // replaced content in w:pPr
    //     $updated_wsdtcontent = str_replace($wpPr[0][0], $updated_wpPr_content, $wsdtcontent[0][0]); // replaced content in w:sdtContent

    //     $updated_wsdt_xml = str_replace($wsdtcontent[0][0], $updated_wsdtcontent, $wsdt_xml);   // replaced content in w:sdt

    //     $updated_xml = str_replace($wsdt_xml, $updated_wsdt_xml, $updated_xml); // replaced content in xml

    //     return $updated_xml;

    // }
    public function get_checked_result_section($fs_company_info_id, $fs_ntfs_layout_template_default_id)    // for notes to financial statement (ntfs)
    {
        $hide_needed = false;

        $q = $this->db->query('SELECT lyt.is_checked, lytd.section_name 
                                    FROM fs_ntfs_layout_template lyt 
                                    LEFT JOIN fs_ntfs_layout_template_default lytd ON lyt.fs_ntfs_layout_template_default_id = lytd.id 
                                    WHERE lyt.fs_company_info_id=' . $fs_company_info_id . ' AND lytd.id = ' . $fs_ntfs_layout_template_default_id . ' ORDER BY lyt.order_by');
        $q = $q->result_array();

        return $q[0]['is_checked'];
    }

    public function update_table($updated_xml, $fs_company_info_id, $additional_info) 
    {
        $replaced_xml = $updated_xml;

        preg_match_all ('/<w:tbl>.*?<\/w:tbl>/s', $updated_xml, $tbl_arr);

        $total_mc_company_ye  = 0.00;
        $total_mc_company_lye = 0.00;
        $total_mc_group_ye    = 0.00;
        $total_mc_group_lye   = 0.00;

        $total_expenses_group_ye    = 0.00;
        $total_expenses_group_lye   = 0.00;
        $total_expenses_company_ye  = 0.00;
        $total_expenses_company_lye = 0.00;

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        // print_r(array($tbl_arr));

        foreach ($tbl_arr[0] as $tbl_key => $tbl_value)  // get template "<w:tbl>"
        {
            // if($tbl_key < 80)
            // {
                // print_r(array($tbl_value));
            // }

            $wsdt_tbl = $this->get_hidden_wsdt_tbl($tbl_value); // get table named "Table name"
            $replaced_tbl_value = $tbl_value;

            foreach ($wsdt_tbl[0] as $wsdt_tbl_key => $wsdt_tbl_value)  // get tag to check table name
            {
                preg_match_all('/<w:tag w:val="(.*?)"\/>/s', $wsdt_tbl_value, $tbl_tag_name);  // get table name

                $table_name = $tbl_tag_name[1][0];  

                if($wsdt_tbl_key < 10)
                {
                    // print_r(array($table_name));
                }
                // print_r(array($table_name));

                // print_r(array("hi"));

                if($table_name == "Statement by director" || $table_name == "Statement by director (header)")
                {
                    $replaced_tbl_template = $replaced_tbl_value;
                    $replaced_tbl_template_2 = $replaced_tbl_template;

                    $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr

                    $company_type_tr_template   = '';
                    $director_tr_template       = '';
                    $company_name_tr_template   = '';
                    $subdirector_tr_template    = '';
                    $newline_template           = '';
                    $tr_item_display_content    = '';

                    $ori_all_template = [];

                    // get templates and remove all the info first
                    foreach ($tbl_tr[0] as $tbl_tr_key => $tbl_tr_value) 
                    {
                        $tr_name_type = $this->get_tr_name_type($tbl_tr_value);  // to extract the first column name so that we know it is title or account name and value insert template.

                        if($tr_name_type == "{New Line}")
                        {
                            array_push($ori_all_template, $tbl_tr_value);
                            $newline_template = $this->vanish_template($tbl_tr_value, 0);
                            $newline_template = $this->remove_wr_from_tr($newline_template, [1]);
                        }
                        elseif($tr_name_type == "{Company type}")
                        {
                            array_push($ori_all_template, $tbl_tr_value);
                            $company_type_tr_template = $this->vanish_template($tbl_tr_value, 0); 
                        }
                        elseif($tr_name_type == "{Director}")
                        {
                            array_push($ori_all_template, $tbl_tr_value);
                            $director_tr_template = $this->vanish_template($tbl_tr_value, 0);

                            // if($fs_company_info[0]['first_set'])
                            // {
                            //     $subtotal_display_tr_template = $this->remove_wr_from_tr($subtotal_display_tr_template, [1, 2]);
                            // }
                            // else
                            // {
                            //     $subtotal_display_tr_template = $this->remove_wr_from_tr($subtotal_display_tr_template, [1]);
                            // }
                        }
                        elseif($tr_name_type == "{Company name}")
                        {
                            array_push($ori_all_template, $tbl_tr_value);
                            $company_name_tr_template = $this->vanish_template($tbl_tr_value, 0);

                            // if($fs_company_info[0]['first_set'])
                            // {
                            //     $last_category_or_subtotal_2_template = $this->remove_wr_from_tr($last_category_or_subtotal_2_template, [2]);
                            // }
                        }
                        elseif($tr_name_type == "{Sub-director}")
                        {
                            array_push($ori_all_template, $tbl_tr_value);
                            $subdirector_template_hide_ori = $tbl_tr_value;
                            $subdirector_tr_template = $this->vanish_template($tbl_tr_value, 0);

                            // if($fs_company_info[0]['first_set'])
                            // {
                            //     $last_category_or_subtotal_2_template = $this->remove_wr_from_tr($last_category_or_subtotal_2_template, [2]);
                            // }
                        }
                        elseif($tr_name_type == $table_name)
                        {
                            array_push($ori_all_template, $tbl_tr_value);
                        }
                        elseif($tr_name_type != "Statement by director")
                        {
                            $replaced_tbl_template_2 = str_replace($tbl_tr_value, '', $replaced_tbl_template_2);
                        }
                    }// end of for loop (loop template)

                    if($fs_company_info[0]['has_director_interest'])
                    {
                        $all_companytype = array();
                        $fs_company_director = $this->db->query("
                            SELECT fs_dir_statement_director.fs_dir_statement_company_id, fs_company_type.company_type, fs_dir_statement_company.company_name, 
                                GROUP_CONCAT(fs_dir_statement_director.director_name) AS `directors_name`, 
                                GROUP_CONCAT(fs_dir_statement_director.dir_begin_fy_no_of_share) AS `dir_begin_fy_no_of_share`, 
                                GROUP_CONCAT(fs_dir_statement_director.dir_end_fy_no_of_share) AS `dir_end_fy_no_of_share`, 
                                GROUP_CONCAT(fs_dir_statement_director.deem_begin_fy_no_of_share) AS `deem_begin_fy_no_of_share`, 
                                GROUP_CONCAT(fs_dir_statement_director.deem_end_fy_no_of_share) AS `deem_end_fy_no_of_share`
                                FROM `fs_dir_statement_company` 
                                LEFT JOIN fs_company_type ON fs_company_type.id = fs_dir_statement_company.fs_company_type_id
                                LEFT JOIN fs_dir_statement_director ON fs_dir_statement_director.fs_dir_statement_company_id = fs_dir_statement_company.id 
                                WHERE fs_dir_statement_company.fs_company_info_id = ". $fs_company_info_id ."
                                GROUP BY fs_dir_statement_company.fs_company_type_id, fs_dir_statement_company.company_name 
                                ORDER BY fs_dir_statement_director.id");
                        // print_r($fs_company_director->result_array());
                        $fs_company_director = $fs_company_director->result_array();
                        $fs_directors    = $this->fs_model->get_fs_appt_directors($fs_company_info_id);

                        foreach($fs_company_director as $each){
                            array_push($all_companytype, $each['company_type']);
                        }

                        $temp_content_trs_directors = "";
                        $temp_content_trs_ult = ""; //ultimate holding company
                        $temp_content_trs_int = ""; //intermediate holding company
                        $temp_content_trs_imm = ""; //immediate holding company
                        $temp_content_trs_corp = ""; //corporate shareholders
                        $temp_content_trs_other = ""; //other company

                        for($x = 0; $x < count($fs_directors); $x++){
                            $x_director = $this->fs_model->get_shares((int)$fs_directors[$x]['id'], $fs_company_info);
                            $total_begin_FY += $x_director[0]['begin_FY'];
                            $total_end_FY   += $x_director[0]['end_FY'];
                        }

                        if($total_begin_FY > 0 || $total_end_FY > 0)
                        {
                            $percent_begin_FY = 0;
                            $percent_end_FY = 0;

                            foreach ($fs_directors as $key => $fs_director) 
                            {
                                $director = $this->fs_model->get_shares((int)$fs_director['id'], $fs_company_info);

                                $percent_begin_FY = (float)$director[0]['begin_FY'] / $total_begin_FY * 100;
                                $percent_end_FY = (float)$director[0]['end_FY'] / $total_end_FY * 100;

                                if($percent_begin_FY > 20 || $percent_end_FY > 20){
                                    // print_r($director);
                                    $director_item = array(
                                                        $this->encryption->decrypt($director[0]['name']),
                                                        $this->fs_replace_content_model->negative_bracket($director[0]['begin_FY']),
                                                        $this->fs_replace_content_model->negative_bracket($director[0]['end_FY']),
                                                        '-',
                                                        '-'
                                                    );
                                    $tr_item = $this->replace_tr_template_item($director_tr_template, $director_item);

                                    $temp_content_trs_directors .= $tr_item;

                                }

                            }
                        }

                        // display titles 
                        if(count($fs_company_director) > 0){
                            if(in_array("Ultimate Holding Company", $all_companytype))
                            {
                                $ultimate_header = array("Ultimate Holding Company");
                                $tr_item = $this->replace_tr_template_item($company_type_tr_template, $ultimate_header);

                                $temp_content_trs_ult = $tr_item;
                            }

                            if(in_array("Intermediate Holding Company", $all_companytype))
                            {
                                $intermediate_header = array("Intermediate Holding Company");
                                $tr_item = $this->replace_tr_template_item($company_type_tr_template, $intermediate_header);

                                $temp_content_trs_int = $tr_item;
                            }

                            if(in_array("Immediate Holding Company", $all_companytype))
                            {
                                $immediate_header = array("Immediate Holding Company");
                                $tr_item = $this->replace_tr_template_item($company_type_tr_template, $immediate_header);

                                $temp_content_trs_imm = $tr_item;
                            }

                            if(in_array("Corporate Shareholders", $all_companytype))
                            {
                                if(array_count_values($array)["Corporate Shareholders"] > 1)
                                {
                                    $corporate_header = array("Corporate Shareholders");
                                }
                                else
                                {
                                    $corporate_header = array("Corporate Shareholder");
                                }
                                
                                $tr_item = $this->replace_tr_template_item($company_type_tr_template, $corporate_header);

                                $temp_content_trs_corp = $tr_item;
                            }
                            if(in_array("Others", $all_companytype))
                            {
                                $other_header = array("Others");
                                $tr_item = $this->replace_tr_template_item($company_type_tr_template, $other_header);

                                $temp_content_trs_other= $tr_item;
                            }
                        }

                        // print_r($fs_company_director);

                        // display director under company
                        foreach($fs_company_director as $company_director)
                        {
                            if($company_director["company_type"] == "Ultimate Holding Company")
                            {
                                // $ultimate_name = array(
                                //                         $company_director["company_name"],
                                //                          $this->fs_replace_content_model->negative_bracket($company_director['direct_begin']),
                                //                          $this->fs_replace_content_model->negative_bracket($company_director['direct_end']),
                                //                          $this->fs_replace_content_model->negative_bracket($company_director['deem_begin']),
                                //                          $this->fs_replace_content_model->negative_bracket($company_director['deem_end']));

                                $ultimate_name = array($company_director["company_name"]);

                                $tr_item = $this->replace_tr_template_item($subdirector_tr_template, $ultimate_name);

                                $temp_content_trs_ult .= $tr_item;

                                $sub_directors = explode(",", $company_director["directors_name"]);
                                $dir_begin_fy_no_of_share  = explode(",", $company_director["dir_begin_fy_no_of_share"]);
                                $dir_end_fy_no_of_share    = explode(",", $company_director["dir_end_fy_no_of_share"]);
                                $deem_begin_fy_no_of_share = explode(",", $company_director["deem_begin_fy_no_of_share"]);
                                $deem_end_fy_no_of_share   = explode(",", $company_director["deem_end_fy_no_of_share"]);

                                foreach($sub_directors as $key => $director)
                                {
                                     $ultimate_director = array(
                                                            $director, 
                                                            $dir_begin_fy_no_of_share[$key], 
                                                            $dir_end_fy_no_of_share[$key], 
                                                            $deem_begin_fy_no_of_share[$key], 
                                                            $deem_end_fy_no_of_share[$key]
                                                        ); 

                                     $tr_item = $this->replace_tr_template_item($director_tr_template, $ultimate_director);

                                     $temp_content_trs_ult .= $tr_item;                   
                                }

                                $temp_content_trs_ult .= $newline_template;

                            }
                            elseif($company_director["company_type"] == "Intermediate Holding Company"){
                                $intermediate_name = array($company_director["company_name"]);

                                // print_r($ultimate_name);
                                $tr_item = $this->replace_tr_template_item($subdirector_tr_template, $intermediate_name);

                                $temp_content_trs_int .= $tr_item;

                                $sub_directors = explode(",", $company_director["directors_name"]);
                                $dir_begin_fy_no_of_share  = explode(",", $company_director["dir_begin_fy_no_of_share"]);
                                $dir_end_fy_no_of_share    = explode(",", $company_director["dir_end_fy_no_of_share"]);
                                $deem_begin_fy_no_of_share = explode(",", $company_director["deem_begin_fy_no_of_share"]);
                                $deem_end_fy_no_of_share   = explode(",", $company_director["deem_end_fy_no_of_share"]);

                                foreach($sub_directors as $key => $director)
                                {
                                     $intermediate_director = array(
                                                                $director,
                                                                $dir_begin_fy_no_of_share[$key], 
                                                                $dir_end_fy_no_of_share[$key], 
                                                                $deem_begin_fy_no_of_share[$key], 
                                                                $deem_end_fy_no_of_share[$key]
                                                            );

                                     $tr_item = $this->replace_tr_template_item($director_tr_template, $intermediate_director);

                                     $temp_content_trs_int .= $tr_item;                   
                                }

                                $temp_content_trs_int .= $newline_template;

                            }
                            elseif($company_director["company_type"] == "Immediate Holding Company"){
                                $immidiate_name = array($company_director["company_name"]);

                                // print_r($ultimate_name);
                                $tr_item = $this->replace_tr_template_item($subdirector_tr_template, $immidiate_name);

                                $temp_content_trs_imm .= $tr_item;

                                $sub_directors = explode(",", $company_director["directors_name"]);
                                $dir_begin_fy_no_of_share  = explode(",", $company_director["dir_begin_fy_no_of_share"]);
                                $dir_end_fy_no_of_share    = explode(",", $company_director["dir_end_fy_no_of_share"]);
                                $deem_begin_fy_no_of_share = explode(",", $company_director["deem_begin_fy_no_of_share"]);
                                $deem_end_fy_no_of_share   = explode(",", $company_director["deem_end_fy_no_of_share"]);

                                foreach($sub_directors as $key => $director)
                                {
                                     $immidiate_director = array(
                                                            $director,
                                                            $dir_begin_fy_no_of_share[$key], 
                                                            $dir_end_fy_no_of_share[$key], 
                                                            $deem_begin_fy_no_of_share[$key], 
                                                            $deem_end_fy_no_of_share[$key]
                                                        );

                                     $tr_item = $this->replace_tr_template_item($subdirector_tr_template, $immidiate_director);

                                     $temp_content_trs_imm .= $tr_item;                   
                                }

                                $temp_content_trs_imm .= $newline_template;

                            }
                            elseif($company_director["company_type"] == "Corporate Shareholders"){
                                $corporate_name = array(
                                                        $company_director["company_name"],
                                                         '',
                                                         '',
                                                         '',
                                                         ''
                                                     );

                                // print_r($ultimate_name);
                                $tr_item = $this->replace_tr_template_item($subdirector_tr_template, $corporate_name);

                                $temp_content_trs_corp .= $tr_item;

                                $sub_directors = explode(",", $company_director["directors_name"]);
                                $dir_begin_fy_no_of_share  = explode(",", $company_director["dir_begin_fy_no_of_share"]);
                                $dir_end_fy_no_of_share    = explode(",", $company_director["dir_end_fy_no_of_share"]);
                                $deem_begin_fy_no_of_share = explode(",", $company_director["deem_begin_fy_no_of_share"]);
                                $deem_end_fy_no_of_share   = explode(",", $company_director["deem_end_fy_no_of_share"]);

                                foreach($sub_directors as $key => $director)
                                {
                                     $corp_director = array(
                                                        $director,
                                                        $dir_begin_fy_no_of_share[$key], 
                                                        $dir_end_fy_no_of_share[$key], 
                                                        $deem_begin_fy_no_of_share[$key], 
                                                        $deem_end_fy_no_of_share[$key]
                                                    );

                                     $tr_item = $this->replace_tr_template_item($director_tr_template, $corp_director);

                                     $temp_content_trs_corp .= $tr_item;                   
                                }

                                $temp_content_trs_corp .= $newline_template;
                            }
                            elseif($company_director["company_type"] == "Others"){
                                $others_name = array(
                                                    $company_director["company_name"],
                                                    '',
                                                    '',
                                                    '',
                                                    ''
                                                );

                                // print_r($ultimate_name);
                                $tr_item = $this->replace_tr_template_item($subdirector_tr_template, $others_name);

                                $temp_content_trs_others .= $tr_item;

                                $sub_directors = explode(",", $company_director["directors_name"]);
                                $dir_begin_fy_no_of_share  = explode(",", $company_director["dir_begin_fy_no_of_share"]);
                                $dir_end_fy_no_of_share    = explode(",", $company_director["dir_end_fy_no_of_share"]);
                                $deem_begin_fy_no_of_share = explode(",", $company_director["deem_begin_fy_no_of_share"]);
                                $deem_end_fy_no_of_share   = explode(",", $company_director["deem_end_fy_no_of_share"]);

                                foreach($sub_directors as $key => $director)
                                {
                                     $others_director = array(
                                                            $director, 
                                                            $dir_begin_fy_no_of_share[$key], 
                                                            $dir_end_fy_no_of_share[$key], 
                                                            $deem_begin_fy_no_of_share[$key], 
                                                            $deem_end_fy_no_of_share[$key]
                                                        );

                                     $tr_item = $this->replace_tr_template_item($director_tr_template, $others_director);

                                     $temp_content_trs_others .= $tr_item;                   
                                }

                                $temp_content_trs_others .= $newline_template;

                            }
                        }
                        
                        // print_r($fs_directors);
                        $tr_item_display_content = $temp_content_trs_directors. $newline_template;

                        if($temp_content_trs_ult != "")
                        {
                            $tr_item_display_content .= $temp_content_trs_ult;
                        }

                        if($temp_content_trs_int != "")
                        {
                            $tr_item_display_content .= $temp_content_trs_int;
                        }

                        if($temp_content_trs_imm != "")
                        {
                            $tr_item_display_content .= $temp_content_trs_imm;
                        }

                        if($temp_content_trs_corp != "")
                        {
                            $tr_item_display_content .= $temp_content_trs_corp;
                        }

                        if($temp_content_trs_others != "")
                        {
                            $tr_item_display_content .= $temp_content_trs_others;
                        }
                    }

                    // remove hidden template in table
                    if($additional_info['generate_docs_without_tags'])
                    {
                        // $replaced_tbl_template_2 = str_replace($subdirector_template_hide_ori, $tr_item_display_content, $replaced_tbl_template_2);
                        $replaced_tbl_template_2 = str_replace($subdirector_template_hide_ori, $subdirector_template_hide_ori . $tr_item_display_content, $replaced_tbl_template_2);

                        // print_r(array($table_name));

                        foreach ($ori_all_template as $at_key => $at_value) 
                        {
                            $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                        }
                        $replaced_tbl_template_2 = $this->remove_tbl_tags_row($replaced_tbl_template_2, $additional_info, $table_name);
                    }
                    else
                    {
                        $replaced_tbl_template_2 = str_replace($subdirector_template_hide_ori, $subdirector_template_hide_ori . $tr_item_display_content, $replaced_tbl_template_2);
                    }

                    $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                }
                elseif($table_name == "Signature statement by director")
                {
                    $replaced_tbl_template = $replaced_tbl_value;

                    $replaced_tbl_template_2 = $replaced_tbl_template;

                    $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr

                    $ori_single_colummn_template = '';
                    $ori_double_column_template  = '';

                    $single_colummn_template = '';
                    $double_column_template  = '';
                    // print_r($tbl_tr);

                    // get templates and remove all the info first
                    foreach ($tbl_tr[0] as $tbl_tr_key => $tbl_tr_value) 
                    {
                        $tr_name_type = $this->get_tr_name_type($tbl_tr_value);  // to extract the first column name so that we know it is title or account name and value insert template.
                        // echo $tr_name_type;
                        
                        if($tbl_key > 2)
                        {
                            $replaced_tbl_template_2 = str_replace($tbl_tr_value, '', $replaced_tbl_template_2);
                        }
                        elseif($tr_name_type == "{one-column}")
                        {
                            $ori_single_colummn_template = $tbl_tr_value;
                            $single_colummn_template = $this->vanish_template($tbl_tr_value, 0);
                            // $newline_template = $this->remove_wr_from_tr($newline_template, [1]);
                        }
                        elseif($tr_name_type == "{two-column}")
                        {
                            $ori_double_column_template = $tbl_tr_value;
                            $double_column_template = $this->vanish_template($tbl_tr_value, 0); 
                        }
                        elseif($additional_info['generate_docs_without_tags'] && $tr_name_type == $table_name) // remove hidden tags
                        {
                            $replaced_tbl_template_2 = str_replace($tbl_tr_value, '', $replaced_tbl_template_2);
                        }
                        elseif($tr_name_type != "Signature statement by director")
                        {
                            // echo $tr_name_type;
                            $replaced_tbl_template_2 = str_replace($tbl_tr_value, '', $replaced_tbl_template_2);
                        }
                    }// end of for loop (loop template)

                    $directors = array();

                    // print_r(array($this->encryption->decrypt($fs_company_info[0]['director_signature_1']), $this->encryption->decrypt($fs_company_info[0]['director_signature_2'])));

                    if($fs_company_info[0]['director_signature_1'] != "")
                    {
                        // array_push($directors, array('director_name' => $this->fs_model->get_directors($fs_company_info[0]['director_signature_1'])[0]->name));
                        array_push($directors, array('director_name' => $this->encryption->decrypt($fs_company_info[0]['director_signature_1'])));
                    }

                    if($fs_company_info[0]['director_signature_2'] != "")
                    {
                        // array_push($directors, array('director_name' => $this->fs_model->get_directors($fs_company_info[0]['director_signature_2'])[0]->name));
                        array_push($directors, array('director_name' => $this->encryption->decrypt($fs_company_info[0]['director_signature_2'])));
                    }

                    $temp_content_trs = '';

                    if(count($directors) == 1){
                        $signature_line = array("__________________________");
                        $tr_item = $this->replace_tr_template_item($single_colummn_template, $signature_line);
                        $temp_content_trs = $tr_item;

                        $signature_name = array($directors[0]['director_name']);
                        $tr_item = $this->replace_tr_template_item($single_colummn_template, $signature_name);
                        $temp_content_trs .= $tr_item;

                        $signature_title = array("Sole Director");
                        $tr_item = $this->replace_tr_template_item($single_colummn_template, $signature_title);
                        $temp_content_trs .= $tr_item;
                    }
                    else
                    {
                        $signature_line = array("__________________________","__________________________");
                        $tr_item = $this->replace_tr_template_item($double_column_template, $signature_line);
                        $temp_content_trs = $tr_item;
                        $signature_name = array($directors[0]['director_name'], $directors[1]['director_name']);
                        $tr_item = $this->replace_tr_template_item($double_column_template, $signature_name);
                        $temp_content_trs .= $tr_item;

                    }

                    $tr_item_display_content = $temp_content_trs;

                    // remove hidden template in table
                    $replaced_tbl_template_2 = str_replace($ori_single_colummn_template, $ori_single_colummn_template . $tr_item_display_content, $replaced_tbl_template_2);

                    if($additional_info['generate_docs_without_tags'])
                    {
                        $replaced_tbl_template_2 = str_replace($ori_single_colummn_template, '', $replaced_tbl_template_2);
                        $replaced_tbl_template_2 = str_replace($ori_double_column_template, '', $replaced_tbl_template_2);
                        $replaced_tbl_template_2 = $this->remove_tbl_tags_row($replaced_tbl_template_2, $additional_info, $table_name);
                    }
                    
                    $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                }
                elseif
                (
                    $table_name == "Statement of comprehensive income (header)(group)(not first set)"    || 
                    $table_name == "Statement of comprehensive income (header)(company)(not first set)"  || 
                    $table_name == "Statement of comprehensive income (header)(group)(first set)"        || 
                    $table_name == "Statement of comprehensive income (header)(company)(first set)"
                )
                {
                    if( ($fs_company_info[0]['group_type'] != 1 && $table_name == "Statement of comprehensive income (header)(group)(not first set)" && $fs_company_info[0]['first_set'] == 0)    ||
                        ($fs_company_info[0]['group_type'] == 1 && $table_name == "Statement of comprehensive income (header)(company)(not first set)" && $fs_company_info[0]['first_set'] == 0)  ||
                        ($fs_company_info[0]['group_type'] != 1 && $table_name == "Statement of comprehensive income (header)(group)(first set)" && $fs_company_info[0]['first_set'] == 1)        ||
                        ($fs_company_info[0]['group_type'] == 1 && $table_name == "Statement of comprehensive income (header)(company)(first set)" && $fs_company_info[0]['first_set'] == 1)
                    )
                    {
                        $replaced_tbl_value = $this->hide_tbl($replaced_tbl_value, false);   // $hide_needed = false;
                        $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                        $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);  
                    }
                    else
                    {
                        // $replaced_tbl_value = $this->hide_tbl($replaced_tbl_value, true);   // $hide_needed = true;

                        $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                        $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                        $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                    }
                    // $hide_table = false;

                    // if($fs_company_info[0]['first_set'] == '1')
                    // {
                    //     if($table_name == "Statement of comprehensive income (header)(company)(not first set)" || $table_name == "Statement of comprehensive income (header)(group)(not first set)")
                    //     {
                    //         $hide_table = true;
                    //     }
                    //     else
                    //     {
                    //         if($fs_company_info[0]['group_type'] == 1)
                    //         {
                    //             if($table_name == "Statement of comprehensive income (header)(group)(first set)")
                    //             {
                    //                 $hide_table = true;
                    //             }
                    //         }
                    //         else
                    //         {
                    //             if($table_name == "Statement of comprehensive income (header)(company)(first set)")
                    //             {
                    //                 $hide_table = true;
                    //             }
                    //         }
                    //     }
                    // }
                    // else
                    // {
                    //     if($table_name == "Statement of comprehensive income (header)(company)(first set)" || $table_name == "Statement of comprehensive income (header)(group)(first set)")
                    //     {
                    //         $hide_table = true;
                    //     }
                    //     else
                    //     {
                    //         if($fs_company_info[0]['group_type'] == 1)
                    //         {
                    //             if($table_name == "Statement of comprehensive income (header)(group)(not first set)")
                    //             {
                    //                 $hide_table = true;
                    //             }
                    //         }
                    //         else
                    //         {
                    //             if($table_name == "Statement of comprehensive income (header)(company)(not first set)")
                    //             {
                    //                 $hide_table = true;
                    //             }
                    //         }
                    //     }
                    // }

                    // if($hide_table)
                    // {
                    //     $replaced_tbl_value = $this->hide_tbl($replaced_tbl_value, true);   // $hide_needed = true;
                    //     $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                    // }
                }
                elseif
                (
                    $table_name == "Statement of comprehensive income (group)(not first set)"   || 
                    $table_name == "Statement of comprehensive income (company)(not first set)" || 
                    $table_name == "Statement of comprehensive income (group)(first set)"       || 
                    $table_name == "Statement of comprehensive income (company)(first set)")  // Table for Statement of comprehensive income
                {
                    $replaced_tbl_value = $tbl_value;
                    $tr_item_display_content = '';
                    $temp_content_trs = '';

                    $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

                    if($fs_company_info[0]['group_type'] == 1)
                    {
                        if($fs_company_info[0]['first_set'] == 1)
                        {
                            $total_arr_template = array(
                                                'value_c_ty' => 0
                                            );
                        }
                        else
                        {
                            $total_arr_template = array(
                                                'value_c_ty' => 0,
                                                'value_c_ly' => 0
                                            );
                        }
                    }
                    else
                    {
                        if($fs_company_info[0]['first_set'] == 1)
                        {
                            $total_arr_template = array(
                                                'value_c_ty' => 0,
                                                'value_g_ty' => 0
                                            );
                        }
                        else
                        {
                            $total_arr_template = array(
                                                'value_c_ty' => 0,
                                                'value_c_ly' => 0,
                                                'value_g_ty' => 0,
                                                'value_g_ly' => 0
                                            );
                        }
                    }

                    if( ($fs_company_info[0]['group_type'] != 1 && $table_name == "Statement of comprehensive income (group)(not first set)" && $fs_company_info[0]['first_set'] == 0)   ||
                        ($fs_company_info[0]['group_type'] == 1 && $table_name == "Statement of comprehensive income (company)(not first set)" && $fs_company_info[0]['first_set'] == 0) ||
                        ($fs_company_info[0]['group_type'] != 1 && $table_name == "Statement of comprehensive income (group)(first set)" && $fs_company_info[0]['first_set'] == 1)       ||
                        ($fs_company_info[0]['group_type'] == 1 && $table_name == "Statement of comprehensive income (company)(first set)" && $fs_company_info[0]['first_set'] == 1)
                    )
                    {
                        $replaced_tbl_template   = $replaced_tbl_value;    // group_type, $hide_column_data, ...
                        $replaced_tbl_template_2 = $replaced_tbl_template;

                        $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                        $ori_all_template = [];

                        $description_normal_tr_template = '';
                        $description_italic_tr_template = '';
                        $subtotal_tr_template           = '';
                        $newline_template               = '';
                        $last_description_tr_template   = '';
                        $overall_total_tr_template      = '';
                        $last_line_space_tr_template    = '';

                        // get templates and remove all the info first
                        foreach ($tbl_tr[0] as $tbl_tr_key => $tbl_tr_value) 
                        {
                            array_push($ori_all_template, $tbl_tr_value);

                            $tr_name_type = $this->get_tr_name_type($tbl_tr_value);  // to extract the first column name so that we know it is title or account name and value insert template.

                            if($tr_name_type == "{Description – normal}")
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $description_normal_tr_template = $this->vanish_template($tbl_tr_value, 0); 
                            }
                            elseif($tr_name_type == "{Description – italic}")
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $description_italic_tr_template = $this->vanish_template($tbl_tr_value, 0);
                            }
                            elseif($tr_name_type == "{Description – italic with top line}")
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $description_italic_w_top_line_tr_template = $this->vanish_template($tbl_tr_value, 0);
                            }
                            elseif($tr_name_type == "{Subtotal}")
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $subtotal_tr_template = $this->vanish_template($tbl_tr_value, 0);
                                $subtotal_tr_template = $this->remove_wr_from_tr($subtotal_tr_template, [1]);
                            }
                            elseif($tr_name_type == "{New Line}")
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $newline_template = $this->vanish_template($tbl_tr_value, 0);
                                $newline_template = $this->remove_wr_from_tr($newline_template, [1]);
                            }
                            elseif($tr_name_type == "{Last description}")
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $last_description_tr_template = $this->vanish_template($tbl_tr_value, 0);
                            }
                            elseif($tr_name_type == "{Overall total}")
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $overall_total_tr_template = $this->vanish_template($tbl_tr_value, 0);
                            }
                            elseif($tr_name_type == "{Overall total 2}")
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $overall_total_double_line_bottom_tr_template = $this->vanish_template($tbl_tr_value, 0);
                            }
                            elseif($tr_name_type == "{Last Line space}") 
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $last_line_space_tr_template = $tbl_tr_value;   // for replace value later
                            }
                            elseif($tr_name_type == $table_name)
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                            }
                            elseif($tr_name_type != "Statement of comprehensive income" && $tbl_tr_key > 3)
                            {
                                $replaced_tbl_template_2 = str_replace($tbl_tr_value, '', $replaced_tbl_template_2);
                            }
                        }

                        // retrieve data
                        $income_list                 = [];
                        $changes_in_inventories      = [];
                        $purchases_and_related_costs = [];
                        $pl_be4_tax                  = [];
                        $pl_after_tax                = [];
                        $expense_list                = [];
                        $additional_list             = [];
                        $other_list                  = [];

                        $fs_statement_list = $this->fs_statements_model->get_fs_statement();    // get list of code from json
                        $fs_state_comp_list = $this->fs_statements_model->get_fs_state_comp_income($fs_company_info_id);    // if fs_state_comp_income has the list, load the list else setup the values.

                        // print_r($fs_state_comp_list);

                        // foreach ($fs_statement_list->statement_comprehensive_income[0]->sections as $sci_json_key => $sci_json_value) 
                        // {
                        //     $data = [];
                        //     $fca_id = [];

                        //     if($sci_json_value->list_name == "income_list")
                        //     {
                        //         // $income_list = $this->fs_account_category_model->get_account_with_sub_round_off($fs_company_info_id, $sci_json_value->account_category_code);

                        //         $income_fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, $sci_json_value->account_category_code);

                        //         $income_data = [];

                        //         foreach ($income_fca_id as $income_fca_id_key => $income_fca_id_value) 
                        //         {
                        //             array_push($income_data, $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, array($income_fca_id_value)));
                        //         }

                        //         $income_list = $income_data;
                        //     }
                        //     elseif($sci_json_value->list_name == "changes_in_inventories")
                        //     {
                        //         // $key = array_search('1', array_column($fs_state_comp_list, 'fs_list_state_comp_income_section_id'), true);

                        //         // if((string)$key != '')
                        //         // {
                        //         //     $changes_in_inventories = $fs_state_comp_list[$key];
                        //         // }

                        //         $key = array_search('1', array_column($fs_state_comp_list, 'fs_list_state_comp_income_section_id'), true);

                        //         if((string)$key != '')
                        //         {
                        //             $changes_in_inventories = $fs_state_comp_list[$key];
                        //         }
                        //     }
                        //     elseif($sci_json_value->list_name == "purchases_and_related_costs")
                        //     {
                        //         // $key = array_search('2', array_column($fs_state_comp_list, 'fs_list_state_comp_income_section_id'), true);

                        //         // if((string)$key != '')
                        //         // {
                        //         //     $purchases_and_related_costs = $fs_state_comp_list[$key];
                        //         // }

                        //         $key = array_search('2', array_column($fs_state_comp_list, 'fs_list_state_comp_income_section_id'), true);

                        //         if((string)$key != '')
                        //         {
                        //             $purchases_and_related_costs = $fs_state_comp_list[$key];
                        //         }
                        //     }
                        //     elseif($sci_json_value->list_name == "pl_be4_tax")
                        //     {
                        //         $key = array_search('3', array_column($fs_state_comp_list, 'fs_list_state_comp_income_section_id'), true);

                        //         if((string)$key != '')
                        //         {
                        //             $pl_be4_tax = $fs_state_comp_list[$key];
                        //         }
                        //     }
                        //     elseif($sci_json_value->list_name == "pl_after_tax")
                        //     {
                        //         $key = array_search('4', array_column($fs_state_comp_list, 'fs_list_state_comp_income_section_id'), true);

                        //         if((string)$key != '')
                        //         {
                        //             $pl_after_tax = $fs_state_comp_list[$key];
                        //         }
                        //     }
                        //     elseif($sci_json_value->list_name == "expense_list")
                        //     {
                        //         // // get sub account codes list
                        //         // $expense_sub_list = $this->fs_account_category_model->get_sub_categories($fs_company_info_id, $sci_json_value->account_category_code[0]);
                        //         // $expense_list = $this->fs_account_category_model->get_account_with_sub_round_off($fs_company_info_id, $expense_sub_list);

                        //         // get sub account codes list
                        //         $expense_sub_list_ids = $this->fs_account_category_model->get_sub_categories($fs_company_info_id, $sci_json_value->account_category_code[0]);

                        //         foreach ($expense_sub_list_ids as $fca_id_key => $fca_id_value) 
                        //         {
                        //             array_push($data, $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, array($fca_id_value)));
                        //         }

                        //         $expense_list = $data;
                        //     }
                        //     elseif($sci_json_value->list_name == "additional_list")
                        //     {
                        //         // $additional_list = $this->fs_account_category_model->get_account_with_sub_round_off($fs_company_info_id, $sci_json_value->account_category_code);    // TAXATION

                        //         $fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, $sci_json_value->account_category_code);

                        //         foreach ($fca_id as $fca_id_key => $fca_id_value) 
                        //         {
                        //             array_push($data, $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, array($fca_id_value)));
                        //         }

                        //         $additional_list = $data;
                        //     }
                        //     elseif($sci_json_value->list_name == "soa_pl_list") // Share of associates profit or loss
                        //     {
                        //         // $soa_pl_list = $this->fs_account_category_model->get_account_with_sub_round_off($fs_company_info_id, $sci_json_value->account_category_code);    // TAXATION
                        //         $temp_data = [];

                        //         $fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, $sci_json_value->account_category_code);

                        //         foreach ($fca_id as $fca_id_key => $fca_id_value) 
                        //         {
                        //             $temp_data = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, array($fca_id_value));

                        //             if(count($temp_data[0]['child_array']) > 0)
                        //             {
                        //                 array_push($data, $temp_data);
                        //             }
                        //         }

                        //         $soa_pl_list = $data;
                        //     }
                        //     elseif($sci_json_value->list_name == "other_list")
                        //     {
                        //         // $other_list = $this->fs_account_category_model->get_account_with_sub_round_off($fs_company_info_id, $sci_json_value->account_category_code); // OTHER COMPREHENSIVE INCOME
                        //         /*----- rearrange array -----*/
                        //         $fs_list_state_comp_income_section_id_list =  array_column($fs_state_comp_list, 'fs_list_state_comp_income_section_id');

                        //         $id_list = [];

                        //         foreach ($fs_list_state_comp_income_section_id_list as $key => $value) 
                        //         {
                        //             array_push($id_list, $value);
                        //         }

                        //         /*----- END OF rearrange array -----*/

                        //         $keys = array_keys($id_list, 5);    // get all keys matched with 5 in fs_list_state_comp_income_section_id

                        //         foreach ($keys as $key => $value) 
                        //         {
                        //             array_push($other_list, $fs_state_comp_list[$value]);
                        //         }
                        //     } 
                        // }

                        $data = $this->fs_statements_model->get_all_adjusted_state_comp_income($fs_company_info_id);

                        $income_list                 = $data['income_list'][0];
                        $other_income_list           = $data['other_income_list'];
                        $changes_in_inventories      = $data['changes_in_inventories'];
                        $purchases_and_related_costs = $data['purchases_and_related_costs'];
                        $pl_be4_tax                  = $data['pl_be4_tax'];
                        $pl_after_tax                = $data['pl_after_tax'];
                        $expense_list                = $data['expense_list'];
                        $additional_list             = $data['additional_list'];
                        $soa_pl_list                 = $data['soa_pl_list'];
                        $other_list                  = $data['other_list'];

                        // start to write content table
                        /* --- for income account (Revenue) --- */
                        $built_income_template = $this->build_tr_template_with_data($fs_company_info, $description_normal_tr_template, $income_list, $subtotal_tr_template);

                        $temp_content_trs .= $built_income_template['temp_content_trs'] . $newline_template; 
                        // $income_subtotal   = $built_income_template['subtotal']; 

                        // $temp_content_trs .= $this->build_subtotal_template($fs_company_info, $income_subtotal, $subtotal_tr_template) . $newline_template;   // display subtotal
                        /* --- END OF for income account (Revenue) --- */


                        /* --- other income account --- */
                        $built_other_income_template = $this->build_tr_template_with_data($fs_company_info, $description_normal_tr_template, $other_income_list, $subtotal_tr_template);

                        $temp_content_trs .= $built_other_income_template['temp_content_trs']; 
                        $other_income_subtotal  = $built_other_income_template['subtotal'];
                        /* --- END OF for other income account --- */


                        /* --- for changes in inventories --- */
                        $built_cii_template = $this->build_tr_template_with_data_fs_sci($fs_company_info, $description_normal_tr_template, array($changes_in_inventories), array());

                        $temp_content_trs .= $built_cii_template['temp_content_trs']; 
                        $cii_subtotal  = $built_cii_template['subtotal'];
                        /* --- END OF for changes in inventories --- */


                        /* --- for purchases and related costs --- */
                        $built_prc_template = $this->build_tr_template_with_data_fs_sci($fs_company_info, $description_normal_tr_template, array($purchases_and_related_costs), array());

                        $temp_content_trs .= $built_prc_template['temp_content_trs']; 
                        $prc_subtotal  = $built_prc_template['subtotal'];
                        /* --- END OF for purchases and related costs --- */


                        /* --- for expenses --- */
                        $built_expense_template = $this->build_tr_template_with_data($fs_company_info, $description_normal_tr_template, $expense_list, $subtotal_tr_template);

                        $temp_content_trs .= $built_expense_template['temp_content_trs']; 
                        $expense_subtotal  = $built_expense_template['subtotal'];

                        if($fs_company_info[0]['first_set'] == 1)
                        {
                            $cii_prc_expense_total['value_c_ty'] = $other_income_subtotal['value_c_ty'] + $cii_subtotal['value_c_ty'] + $prc_subtotal['value_c_ty'] + $expense_subtotal['value_c_ty'];
                        }
                        else
                        {
                            $cii_prc_expense_total['value_c_ty'] = $other_income_subtotal['value_c_ty'] + $cii_subtotal['value_c_ty'] + $prc_subtotal['value_c_ty'] + $expense_subtotal['value_c_ty'];
                            $cii_prc_expense_total['value_c_ly'] = $other_income_subtotal['value_c_ly'] + $cii_subtotal['value_c_ly'] + $prc_subtotal['value_c_ly'] + $expense_subtotal['value_c_ly'];
                        }

                        // if got group
                        if($fs_company_info[0]['group_type'] != 1)
                        {
                            if($fs_company_info[0]['first_set'] == 1)
                            {
                                $cii_prc_expense_total['value_g_ty'] = $other_income_subtotal['value_g_ty'] + $cii_subtotal['value_g_ty'] + $prc_subtotal['value_g_ty'] + $expense_subtotal['value_g_ty'];
                            }
                            else
                            {
                                $cii_prc_expense_total['value_g_ty'] = $other_income_subtotal['value_g_ty'] + $cii_subtotal['value_g_ty'] + $prc_subtotal['value_g_ty'] + $expense_subtotal['value_g_ty'];
                                $cii_prc_expense_total['value_g_ly'] = $other_income_subtotal['value_g_ly'] + $cii_subtotal['value_g_ly'] + $prc_subtotal['value_g_ly'] + $expense_subtotal['value_g_ly'];
                            }
                        }

                        $temp_content_trs .= $this->build_subtotal_template($fs_company_info, $cii_prc_expense_total, $subtotal_tr_template) . $newline_template;
                        /* --- END OF for expenses --- */


                        /* --- for profit before tax --- */
                        if(count($additional_list[0][0]['child_array']) > 0 || count($soa_pl_list) > 0)
                        {
                            $built_pl_b4_tax_template = $this->build_tr_template_with_data_fs_sci($fs_company_info, $description_italic_tr_template, array($pl_be4_tax), '');
                        }
                        else
                        {
                            $built_pl_b4_tax_template = $this->build_tr_template_with_data_fs_sci($fs_company_info, $overall_total_double_line_bottom_tr_template, array($pl_be4_tax), '');
                        }

                        $temp_content_trs   .= $built_pl_b4_tax_template['temp_content_trs']; 
                        $pl_b4_tax_subtotal  = $built_pl_b4_tax_template['subtotal'];
                        /* --- END OF for profit before tax --- */


                        /* --- for addtional --- */
                        if(count($additional_list[0][0]['child_array']) > 0)
                        {
                            // calculate all subtotal first
                            $built_additional_template = $this->build_tr_template_with_data($fs_company_info, $description_normal_tr_template, $additional_list, '');

                            $temp_content_trs   .= $built_additional_template['temp_content_trs']; 
                            $addtional_subtotal  = $built_additional_template['subtotal'];

                            // // create complete template with data (exclude last row)
                            // $built_additional_template = $this->build_tr_template_with_data($fs_company_info, $description_normal_tr_template, array($additional_list), '');
                            // $temp_content_trs   .= $built_additional_template['temp_content_trs']; 

                            // if(count($additional_list) > 0) // temp save last row data, remove last row data from array
                            // {
                            //     $lr_additional_list = $additional_list[count($additional_list) - 1];
                            //     unset($additional_list[count($additional_list) - 1]);   // remove last row data from array

                            //     if(count($additional_list) > 0)
                            //     {
                            //         // create complete template with data (exclude last row)
                            //         $built_additional_template = $this->build_tr_template_with_data($fs_company_info, $description_normal_tr_template, array($additional_list), '');
                            //         $temp_content_trs   .= $built_additional_template['temp_content_trs']; 
                            //     }

                            //     if(count($lr_additional_list) > 0)
                            //     {
                            //         // print_r($lr_additional_list);
                            //         $built_lr_additional_template = $this->build_tr_template_with_data($fs_company_info, $last_description_tr_template, array($lr_additional_list), '');
                            //         $temp_content_trs   .= $built_lr_additional_template['temp_content_trs']; 
                            //     }
                            // }
                        }
                        else
                        {
                            $built_additional_template = $this->build_tr_template_with_data($fs_company_info, $description_normal_tr_template, $additional_list, '');
                            $addtional_subtotal        = $built_additional_template['subtotal'];
                        }
                        /* --- END OF for addtional --- */

                        /* --- for share of associates profit or loss --- */
                        if(count($soa_pl_list) > 0)
                        {
                            $built_soa_pl_template = $this->build_tr_template_with_data($fs_company_info, $last_description_tr_template, $soa_pl_list, '');

                            $temp_content_trs   .= $built_soa_pl_template['temp_content_trs']; 
                            $soa_pl_subtotal     = $built_soa_pl_template['subtotal'];
                        }
                        
                        /* --- END OF for share of associates profit or loss --- */

                        /* --- for profit after tax --- */
                        // print_r($pl_after_tax);
                        if(count($additional_list[0][0]['child_array']) > 0 || count($soa_pl_list) > 0)  // if got tax and share of associates, show profit/loss after tax
                        {
                            if(count($pl_after_tax) > 0)
                            {
                                $pl_after_tax = array($pl_after_tax);
                            }

                            if(count($other_list) > 0)  // if got other list 
                            {
                                $built_pl_after_tax_template = $this->build_tr_template_with_data_fs_sci($fs_company_info, $description_italic_w_top_line_tr_template, $pl_after_tax, '');

                                $temp_content_trs      .= $built_pl_after_tax_template['temp_content_trs']; 
                                $pl_after_tax_subtotal  = $built_pl_after_tax_template['subtotal'];
                            }
                            else
                            {
                                $built_pl_after_tax_template = $this->build_tr_template_with_data_fs_sci($fs_company_info, $overall_total_tr_template, $pl_after_tax, '');

                                $temp_content_trs      .= $built_pl_after_tax_template['temp_content_trs']; 
                                $pl_after_tax_subtotal  = $built_pl_after_tax_template['subtotal'];
                            }
                        }
                        /* --- END OF for profit after tax --- */

                        /* --- for other --- */
                        // $built_other_template = $this->build_tr_template_with_data_fs_sci($fs_company_info, $description_normal_tr_template, $other_list, $subtotal_tr_template);

                        // $temp_content_trs .= $built_other_template['temp_content_trs']; 
                        // $other_subtotal    = $built_other_template['subtotal'];
                        /* --- END OF for other --- */

                        /* --- Total comprehensive income for the year (Overall total) --- */
                        if(count($other_list) > 0)
                        {
                            // calculate subtotal
                            $overall_total = $total_arr_template;

                            $overall_total['description'] = "Total comprehensive income for the year";

                            if($fs_company_info[0]['first_set'] == 1)
                            {
                                $overall_total['value_c_ty']  = $pl_after_tax_subtotal['value_c_ty'] + $other_subtotal['value_c_ty'];
                            }
                            else
                            {
                                $overall_total['value_c_ty']  = $pl_after_tax_subtotal['value_c_ty'] + $other_subtotal['value_c_ty'];
                                $overall_total['value_c_ly']  = $pl_after_tax_subtotal['value_c_ly'] + $other_subtotal['value_c_ly'];
                            }

                            if($fs_company_info[0]['group_type'] != 1)
                            {
                                if($fs_company_info[0]['first_set'] == 1)
                                {
                                    $overall_total['value_g_ty']  = $pl_after_tax_subtotal['value_g_ty'] + $other_subtotal['value_g_ty'];
                                }
                                else
                                {
                                    $overall_total['value_g_ty']  = $pl_after_tax_subtotal['value_g_ty'] + $other_subtotal['value_g_ty'];
                                    $overall_total['value_g_ly']  = $pl_after_tax_subtotal['value_g_ly'] + $other_subtotal['value_g_ly'];
                                }
                            }

                            $temp_content_trs .= $this->build_subtotal_template($fs_company_info, $overall_total, $overall_total_tr_template);
                        }
                        /* --- END OF Total comprehensive income for the year (Overall total) --- */

                        $tr_item_display_content = $temp_content_trs;
                        $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $newline_template . $tr_item_display_content, $replaced_tbl_template_2);

                        if($additional_info['generate_docs_without_tags'])
                        {
                            foreach ($ori_all_template as $at_key => $at_value) 
                            {
                                $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                            }
                        }

                        $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                    }
                    else
                    {
                        // $replaced_tbl_value = $this->hide_tbl($replaced_tbl_value, true);   // $hide_needed = true;
                        $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                        $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                        $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                    }
                }
                elseif
                (
                    $table_name == "Statement of financial position (header)(group)(not first set)"    || 
                    $table_name == "Statement of financial position (header)(company)(not first set)"  || 
                    $table_name == "Statement of financial position (header)(group)(first set)"        || 
                    $table_name == "Statement of financial position (header)(company)(first set)"
                )
                {
                    if( ($fs_company_info[0]['group_type'] != 1 && $table_name == "Statement of financial position (header)(group)(not first set)" && $fs_company_info[0]['first_set'] == 0)    ||
                        ($fs_company_info[0]['group_type'] == 1 && $table_name == "Statement of financial position (header)(company)(not first set)" && $fs_company_info[0]['first_set'] == 0)  ||
                        ($fs_company_info[0]['group_type'] != 1 && $table_name == "Statement of financial position (header)(group)(first set)" && $fs_company_info[0]['first_set'] == 1)        ||
                        ($fs_company_info[0]['group_type'] == 1 && $table_name == "Statement of financial position (header)(company)(first set)" && $fs_company_info[0]['first_set'] == 1)
                    )
                    {
                        $replaced_tbl_value = $this->hide_tbl($replaced_tbl_value, false);   // $hide_needed = false;
                        $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);  
                        $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                    }
                    else
                    {
                        // $replaced_tbl_value = $this->hide_tbl($replaced_tbl_value, true);   // $hide_needed = true;
                        $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                        $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                        $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                    }
                }
                elseif 
                (
                    $table_name == "Statement of financial position (group)(not first set)"    || 
                    $table_name == "Statement of financial position (company)(not first set)"  || 
                    $table_name == "Statement of financial position (group)(first set)"        || 
                    $table_name == "Statement of financial position (company)(first set)"
                ) 
                {
                    if( ($fs_company_info[0]['group_type'] != 1 && $table_name == "Statement of financial position (group)(not first set)" && $fs_company_info[0]['first_set'] == 0)    ||
                        ($fs_company_info[0]['group_type'] == 1 && $table_name == "Statement of financial position (company)(not first set)" && $fs_company_info[0]['first_set'] == 0)  ||
                        ($fs_company_info[0]['group_type'] != 1 && $table_name == "Statement of financial position (group)(first set)" && $fs_company_info[0]['first_set'] == 1)        ||
                        ($fs_company_info[0]['group_type'] == 1 && $table_name == "Statement of financial position (company)(first set)" && $fs_company_info[0]['first_set'] == 1)
                    )
                    {
                        $replaced_tbl_template   = $replaced_tbl_value;    // group_type, $hide_column_data, ...
                        $replaced_tbl_template_2 = $replaced_tbl_template;

                        $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                        $description_bold_tr_template   = '';
                        $description_bold_italic_tr_template = '';
                        $description_normal_tr_template = '';
                        $description_italic_tr_template = '';
                        $subtotal_tr_template           = '';
                        $newline_template               = '';
                        $last_description_tr_template   = '';
                        $overall_total_tr_template      = '';
                        $last_line_space_tr_template    = '';

                        $all_template     = [];
                        $ori_all_template = [];

                        // get templates and remove all the info first
                        foreach ($tbl_tr[0] as $tbl_tr_key => $tbl_tr_value) 
                        {
                            $tr_name_type = $this->get_tr_name_type($tbl_tr_value);  // to extract the first column name so that we know it is title or account name and value insert template.

                            if($tr_name_type == "{Description – bold}") 
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $all_template['description_bold_tr_template'] = $this->vanish_template($tbl_tr_value, 0);
                            }
                            elseif($tr_name_type == "{Description – bold italic}")
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $all_template['description_bold_italic_tr_template'] = $this->vanish_template($tbl_tr_value, 0);
                            }
                            elseif($tr_name_type == "{Description – normal}")
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $all_template['description_normal_tr_template'] = $this->vanish_template($tbl_tr_value, 0); 
                            }
                            elseif($tr_name_type == "{Last description}")
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $all_template['last_description_tr_template'] = $this->vanish_template($tbl_tr_value, 0);
                            }
                            elseif($tr_name_type == "{Last description - bold}")
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $all_template['last_description_bold_tr_template'] = $this->vanish_template($tbl_tr_value, 0);
                            }
                            elseif($tr_name_type == "{Subtotal}")
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $all_template['subtotal_tr_template'] = $this->vanish_template($tbl_tr_value, 0);
                                $all_template['subtotal_tr_template'] = $this->remove_wr_from_tr($all_template['subtotal_tr_template'], [1]);
                            }
                            elseif($tr_name_type == "{New Line}")
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $all_template['newline_template'] = $this->vanish_template($tbl_tr_value, 0);
                                $all_template['newline_template'] = $this->remove_wr_from_tr($all_template['newline_template'], [1]);
                            }
                            elseif($tr_name_type == "{Overall total}")
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $all_template['overall_total_tr_template'] = $this->vanish_template($tbl_tr_value, 0);
                            }
                            elseif($tr_name_type == "{Last Line space}") 
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $last_line_space_tr_template = $tbl_tr_value;   // for replace value later
                            }
                            elseif($tr_name_type == $table_name)
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                            }
                            elseif($tbl_tr_key > 2) // exception for table name tag so that can reuse it later.
                            {
                                $replaced_tbl_template_2 = str_replace($tbl_tr_value, '', $replaced_tbl_template_2);
                            }
                        }

                        // start to write content table
                        $fs_ntfs_list = $this->fs_notes_model->get_fs_ntfs_json();
                        $fp_key = array_search("Statement of financial position", array_column($fs_ntfs_list['statements'], 'document_name'));

                        if($fp_key || (string)$fp_key == '0')
                        {
                            $fp_account_code = $fs_ntfs_list['statements'][$fp_key]['reference_id']; // get account code
                            $fs_ntfs_list    = $fs_ntfs_list['statements'][$fp_key];
                        }

                        $fp_data = [];

                        $fp_fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, $fp_account_code);
                        $fp_data   = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $fp_fca_id); // get data

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
                            $total_for_revenue_reserve['total_g']       += $rr_value['parent_array'][0]['total_g'];
                            $total_for_revenue_reserve['total_g_lye']   += $rr_value['parent_array'][0]['total_g_lye'];
                        }

                        $fp_data = $this->fs_account_category_model->operate_account_value($fp_data, array('Q103'), array(array('operator' => '+', 'insert_values_arr' => $total_for_revenue_reserve)));

                        // print_r($data_for_revenue_reserve);

                        $fs_fp_template = $this->build_tr_template_with_data_fs_fp($fs_company_info, $all_template, $fp_data);  // build table with data
                        $temp_content_trs = $fs_fp_template['temp_content_trs'];

                        $tr_item_display_content = $temp_content_trs;
                        $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $newline_template . $tr_item_display_content, $replaced_tbl_template_2);

                        if($additional_info['generate_docs_without_tags'])
                        {
                            foreach ($ori_all_template as $at_key => $at_value) 
                            {
                                $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                            }
                        }
                        
                        $replaced_tbl_template_2 = $this->remove_tbl_tags_row($replaced_tbl_template_2, $additional_info, $table_name);
                        $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                    }
                    else
                    {
                        // $replaced_tbl_value = $this->hide_tbl($replaced_tbl_value, true);   // $hide_needed = true;
                        $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                        $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                        $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                    }
                }
                elseif(
                    $table_name == "Statement of cash flows (header)(group)(not first set)"    ||
                    $table_name == "Statement of cash flows (header)(company)(not first set)"  || 
                    $table_name == "Statement of cash flows (header)(group)(first set)"        || 
                    $table_name == "Statement of cash flows (header)(company)(first set)"
                )
                {
                    if( ($fs_company_info[0]['group_type'] != 1 && $table_name == "Statement of cash flows (header)(group)(not first set)" && $fs_company_info[0]['first_set'] == 0)    ||
                        ($fs_company_info[0]['group_type'] == 1 && $table_name == "Statement of cash flows (header)(company)(not first set)" && $fs_company_info[0]['first_set'] == 0)  ||
                        ($fs_company_info[0]['group_type'] != 1 && $table_name == "Statement of cash flows (header)(group)(first set)" && $fs_company_info[0]['first_set'] == 1)        ||
                        ($fs_company_info[0]['group_type'] == 1 && $table_name == "Statement of cash flows (header)(company)(first set)" && $fs_company_info[0]['first_set'] == 1)
                    )
                    {    
                        $replaced_tbl_value = $this->hide_tbl($replaced_tbl_value, false);   // $hide_needed = false;
                        $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                        $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                    }
                    else
                    {
                        // $replaced_tbl_value = $this->hide_tbl($replaced_tbl_value, true);   // $hide_needed = true;
                        $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                        $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                        $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                    }
                }
                elseif(
                    $table_name == "Statement of cash flows (group)(not first set)"    || 
                    $table_name == "Statement of cash flows (company)(not first set)"  || 
                    $table_name == "Statement of cash flows (group)(first set)"        || 
                    $table_name == "Statement of cash flows (company)(first set)"
                )
                {
                    if( ($fs_company_info[0]['group_type'] != 1 && $table_name == "Statement of cash flows (group)(not first set)" && $fs_company_info[0]['first_set'] == 0)   ||
                        ($fs_company_info[0]['group_type'] == 1 && $table_name == "Statement of cash flows (company)(not first set)" && $fs_company_info[0]['first_set'] == 0) ||
                        ($fs_company_info[0]['group_type'] != 1 && $table_name == "Statement of cash flows (group)(first set)" && $fs_company_info[0]['first_set'] == 1)       ||
                        ($fs_company_info[0]['group_type'] == 1 && $table_name == "Statement of cash flows (company)(first set)" && $fs_company_info[0]['first_set'] == 1)
                    )
                    {
                        $replaced_tbl_template   = $replaced_tbl_value;    // group_type, $hide_column_data, ...
                        $replaced_tbl_template_2 = $replaced_tbl_template;

                        $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                        $ori_all_template = [];

                        $newline_template                    = '';
                        $description_normal_tr_template      = '';
                        $last_description_normal_tr_template = '';
                        $description_italic_tr_template      = '';
                        $sub_description_normal_tr_template  = '';
                        $last_sub_description_normal_tr_template = '';
                        $overall_total_tr_template           = '';
                        $last_line_space_tr_template         = '';

                        // get templates and remove all the info first
                        foreach ($tbl_tr[0] as $tbl_tr_key => $tbl_tr_value) 
                        {
                            $tr_name_type = $this->get_tr_name_type($tbl_tr_value);  // to extract the first column name so that we know it is title or account name and value insert template.

                            if($tr_name_type == "{New Line}")
                            {
                                array_push($ori_all_template, $tbl_tr_value);

                                $newline_template = $this->vanish_template($tbl_tr_value, 0); 
                                $newline_template = $this->remove_wr_from_tr($newline_template, [1]);
                            }
                            elseif($tr_name_type == "{Description – normal}")
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $description_normal_tr_template = $this->vanish_template($tbl_tr_value, 0); 
                            }
                            elseif($tr_name_type == "{Last Description – normal}")
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $last_description_normal_tr_template = $this->vanish_template($tbl_tr_value, 0); 
                            }
                            elseif($tr_name_type == "{Description – italic}")
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $description_italic_tr_template = $this->vanish_template($tbl_tr_value, 0);
                            }
                            elseif($tr_name_type == "{Sub Description – normal}")   // with indent
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $sub_description_normal_tr_template = $this->vanish_template($tbl_tr_value, 0);
                            }
                            elseif($tr_name_type == "{Last Sub Description – normal}")   // with indent
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $last_sub_description_normal_tr_template = $this->vanish_template($tbl_tr_value, 0);
                            }
                            elseif($tr_name_type == "{Overall total}")   // with indent
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $overall_total_tr_template = $this->vanish_template($tbl_tr_value, 0);
                            }
                            elseif($tr_name_type == "{Last Line space}") 
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                                $last_line_space_tr_template = $tbl_tr_value;   // for replace value later
                            }
                            elseif($tr_name_type == $table_name)
                            {
                                array_push($ori_all_template, $tbl_tr_value);
                            }
                            elseif($tbl_tr_key > 3)
                            {
                                $replaced_tbl_template_2 = str_replace($tbl_tr_value, '', $replaced_tbl_template_2);
                            }
                        }

                        // display content with data
                        /* get data from database */
                        $temp_all_state_cash_flows_fixed = $this->fs_statements_model->get_fs_state_cash_flows_fixed($fs_company_info_id);
                        $temp_arr = array();

                        // get profit before tax values from statement of comprehensive income
                        $pl_be4_tax_values_from_sci = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_company_info_id=" . $fs_company_info_id . " AND fs_list_state_comp_income_section_id=3");
                        $pl_be4_tax_values_from_sci = $pl_be4_tax_values_from_sci->result_array(); 

                        if(count($pl_be4_tax_values_from_sci) > 0)
                        {
                            $temp_arr['profit_before_tax']['note_id']         = $pl_be4_tax_values_from_sci[0]['note_id'];
                            $temp_arr['profit_before_tax']['group_ye']        = $pl_be4_tax_values_from_sci[0]['value_group_ye'];
                            $temp_arr['profit_before_tax']['group_lye_end']   = $pl_be4_tax_values_from_sci[0]['value_group_lye_end'];
                            $temp_arr['profit_before_tax']['company_ye']      = $pl_be4_tax_values_from_sci[0]['value_company_ye'];
                            $temp_arr['profit_before_tax']['company_lye_end'] = $pl_be4_tax_values_from_sci[0]['value_company_lye_end'];
                        }

                        
                        // get others fixed
                        foreach ($temp_all_state_cash_flows_fixed as $key => $each) 
                        {
                            $temp_arr[$each['fixed_tag']]['note_id']         = $each['note_id'];
                            $temp_arr[$each['fixed_tag']]['group_ye']        = $each['value_group_ye'];
                            $temp_arr[$each['fixed_tag']]['group_lye_end']   = $each['value_group_lye_end'];
                            $temp_arr[$each['fixed_tag']]['company_ye']      = $each['value_company_ye'];
                            $temp_arr[$each['fixed_tag']]['company_lye_end'] = $each['value_company_lye_end'];
                            if($each['note_id'] != null){
                                $temp_arr[$each['fixed_tag']]['note_display_num'] = $this->fs_notes_model->get_input_note_num($fs_company_info_id, $each['note_id']);
                            }
                        }

                        $fs_state_cash_flows = $this->fs_statements_model->get_fs_state_cash_flows($fs_company_info_id);
                        $fs_state_cash_flows_fixed = $temp_arr;
                        $check_operating_act = $this->fs_statements_model->get_fs_state_cash_flows_section($fs_company_info_id, 1);
                        $check_investing_act = $this->fs_statements_model->get_fs_state_cash_flows_section($fs_company_info_id, 2);
                        $check_financing_act = $this->fs_statements_model->get_fs_state_cash_flows_section($fs_company_info_id, 3);
                        /* END OF get data from database */

                        // print_r($fs_state_cash_flows_fixed);

                        $temp_content_trs = '';
                        $tr_item = '';

                        // Operating activities
                        if($check_operating_act[0]['status'])
                        {
                            // Fixed title for Operating activities
                            $fixed_title_operating_expenses = array('Operating activities');
                            $tr_item = $this->replace_tr_template_item($description_italic_tr_template, $fixed_title_operating_expenses);

                            $temp_content_trs .= $tr_item;

                            // display profit before tax
                            $data = [];
                            $template = [];

                            $data['fs_cf_type']                = 'profit_before_tax';
                            $data['fs_cf_type_title']          = $pl_be4_tax_values_from_sci[0]['description'];
                            $data['fs_state_cash_flows_fixed'] = $fs_state_cash_flows_fixed;

                            $template['display_tr_template']   = $description_normal_tr_template;

                            $temp_content_trs .= $this->build_tr_template_with_data_fs_cf($fs_company_info, $template, $data);

                            // END OF Fixed title for Profit before tax

                            /* Adjustments for:- */
                            if(in_array('#adjustment', array_column($fs_state_cash_flows, 'parent_id')))
                            {
                                $data     = [];
                                $template = [];

                                $data['fixed_title']         = array('Adjustments for:-');
                                $data['fs_state_cash_flows'] = $fs_state_cash_flows;
                                $data['parent_id']           = '#adjustment';
                                $data['category_id']         = 1;

                                $template['fixed_title'] = '';
                                $template['fixed_title'] = $this->remove_wr_from_tr($description_normal_tr_template, [2,3,4,5,6,7]);

                                $template['sub_description_normal_tr_template'] = '';
                                $template['sub_description_normal_tr_template'] = $sub_description_normal_tr_template;

                                $template['last_sub_description_normal_tr_template'] = '';

                                $temp_content_trs .= $this->build_tr_template_with_data_fs_cf_subs($fs_company_info, $template, $data);
                            }
                            /* END OF Adjustments for:- */

                            /* Changes in working capital */
                            if(in_array('#changes', array_column($fs_state_cash_flows, 'parent_id')))
                            {
                                $data     = [];
                                $template = [];

                                $data['fixed_title']         = array('Changes in working capital');
                                $data['fs_state_cash_flows'] = $fs_state_cash_flows;
                                $data['parent_id']           = '#changes';
                                $data['category_id']         = 1;

                                $template['fixed_title'] = '';
                                $template['fixed_title'] = $this->remove_wr_from_tr($description_normal_tr_template, [2,3,4,5,6,7]);

                                $template['sub_description_normal_tr_template'] = '';
                                $template['sub_description_normal_tr_template'] = $sub_description_normal_tr_template;

                                $template['last_sub_description_normal_tr_template'] = $last_sub_description_normal_tr_template;

                                $temp_content_trs .= $this->build_tr_template_with_data_fs_cf_subs($fs_company_info, $template, $data);
                            }
                            /* END OF Changes in working capital */
                            
                            /* Net cash from operations */
                            // if(in_array('#net_cash', array_column($fs_state_cash_flows, 'parent_id')))
                            // {
                                // display
                                $data           = [];
                                $template       = [];
                                $net_cash_frm_opt = array(
                                                    'group_ye'        => 0,
                                                    'group_lye_end'   => 0,
                                                    'company_ye'      => 0,
                                                    'company_lye_end' => 0
                                                );

                                /* calculate for the values */
                                if(count($pl_be4_tax_values_from_sci) > 0) // add up profit before tax values
                                {
                                    $net_cash_frm_opt['group_ye']        += $pl_be4_tax_values_from_sci[0]['value_group_ye'];
                                    $net_cash_frm_opt['group_lye_end']   += $pl_be4_tax_values_from_sci[0]['value_group_lye_end'];
                                    $net_cash_frm_opt['company_ye']      += $pl_be4_tax_values_from_sci[0]['value_company_ye'];
                                    $net_cash_frm_opt['company_lye_end'] += $pl_be4_tax_values_from_sci[0]['value_company_lye_end'];
                                }

                                foreach ($fs_state_cash_flows as $key_cfs => $value_cfs)
                                {
                                    if($value_cfs['parent_id'] == "#adjustment" || $value_cfs['parent_id'] == "#changes")
                                    {
                                        $net_cash_frm_opt['group_ye']        += $value_cfs['value_group_ye'];
                                        $net_cash_frm_opt['group_lye_end']   += $value_cfs['value_group_lye_end'];
                                        $net_cash_frm_opt['company_ye']      += $value_cfs['value_company_ye'];
                                        $net_cash_frm_opt['company_lye_end'] += $value_cfs['value_company_lye_end'];
                                    }
                                }
                                /* calculate for the values */

                                // insert fixed title data
                                if($fs_company_info[0]['group_type'] != 1)
                                {   
                                    if($fs_company_info[0]['first_set'] == 1)
                                    {
                                        $temp_data = array(
                                                        $fs_cf_type_title,
                                                        $note_no,
                                                        $this->fs_replace_content_model->negative_bracket($net_cash_frm_opt[$fs_cf_type]['group_ye']),
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket($net_cash_frm_opt[$fs_cf_type]['company_ye'])
                                                    );

                                        $data['fixed_title'] = array(
                                                            'Net cash from operations',
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($net_cash_frm_opt['group_ye']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($net_cash_frm_opt['company_ye'])
                                                        );
                                    }
                                    else
                                    {
                                        $data['fixed_title'] = array(
                                                            'Net cash from operations',
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($net_cash_frm_opt['group_ye']),
                                                            $this->fs_replace_content_model->negative_bracket($net_cash_frm_opt['group_lye_end']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($net_cash_frm_opt['company_ye']),
                                                            $this->fs_replace_content_model->negative_bracket($net_cash_frm_opt['company_lye_end'])
                                                        );
                                    }
                                }
                                else
                                {
                                    if($fs_company_info[0]['first_set'] == 1)
                                    {
                                         $data['fixed_title'] = array(
                                                            'Net cash from operations',
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($net_cash_frm_opt['company_ye'])
                                                        );
                                    }
                                    else
                                    {
                                        $data['fixed_title'] = array(
                                                            'Net cash from operations',
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($net_cash_frm_opt['company_ye']),
                                                            $this->fs_replace_content_model->negative_bracket($net_cash_frm_opt['company_lye_end'])
                                                        );
                                    }
                                }

                                $data['fs_state_cash_flows'] = $fs_state_cash_flows;
                                $data['parent_id']           = '#net_cash';
                                $data['category_id']         = 1;

                                $template['fixed_title'] = '';
                                $template['fixed_title'] = $this->remove_wr_from_tr($description_normal_tr_template, [2]);

                                $template['sub_description_normal_tr_template'] = '';
                                $template['sub_description_normal_tr_template'] = $sub_description_normal_tr_template;

                                $template['last_sub_description_normal_tr_template'] = $last_sub_description_normal_tr_template;

                                $temp_content_trs .= $this->build_tr_template_with_data_fs_cf_subs($fs_company_info, $template, $data);
                            // }
                            /* END OF Net cash from operations */

                            /* Net cash movement in operating activities */
                            $data = [];
                            $template = [];
                            $net_cash_opt = array(
                                                'net_cash_movement_op' => array(
                                                                            'group_ye'         => $net_cash_frm_opt['group_ye'],
                                                                            'group_lye_end'    => $net_cash_frm_opt['group_lye_end'],
                                                                            'company_ye'       => $net_cash_frm_opt['company_ye'],
                                                                            'company_lye_end'  => $net_cash_frm_opt['company_lye_end'],
                                                                            'note_display_num' => []
                                                                        )
                                            );

                            // Calculate for the values
                            foreach ($fs_state_cash_flows as $key_cfs_1 => $value_cfs_1)
                            {
                                if($value_cfs_1['parent_id'] == "#net_cash")
                                {
                                    $net_cash_opt['net_cash_movement_op']['group_ye']        += $value_cfs_1['value_group_ye'];
                                    $net_cash_opt['net_cash_movement_op']['group_lye_end']   += $value_cfs_1['value_group_lye_end'];
                                    $net_cash_opt['net_cash_movement_op']['company_ye']      += $value_cfs_1['value_company_ye'];
                                    $net_cash_opt['net_cash_movement_op']['company_lye_end'] += $value_cfs_1['value_company_lye_end'];
                                }
                            }

                            $data['fs_cf_type']                = 'net_cash_movement_op';
                            $data['fs_cf_type_title']          = 'Net cash movement in operating activities';
                            $data['fs_state_cash_flows_fixed'] = $net_cash_opt; 

                            $template['display_tr_template']   = $last_description_normal_tr_template;

                            $temp_content_trs .= $this->build_tr_template_with_data_fs_cf($fs_company_info, $template, $data);
                            /* END OF Net cash movement in operating activities */

                            $temp_content_trs .= $newline_template;
                        }
                        // END OF Operating activities

                        // Investing activities
                        if($check_investing_act[0]['status'])
                        {
                            // Fixed title for Investing activities
                            $fixed_title_operating_expenses = array('Investing activities');
                            $tr_item = $this->replace_tr_template_item($description_italic_tr_template, $fixed_title_operating_expenses);

                            $temp_content_trs .= $tr_item;

                            /* Sub of investing activities */
                            if(in_array('#investing', array_column($fs_state_cash_flows, 'parent_id')))
                            {
                                $data     = [];
                                $template = [];

                                $data['fs_state_cash_flows'] = $fs_state_cash_flows;
                                $data['parent_id']           = '#investing';
                                $data['category_id']         = 2;

                                $template['fixed_title'] = '';

                                $template['sub_description_normal_tr_template'] = '';
                                $template['sub_description_normal_tr_template'] = $sub_description_normal_tr_template;

                                $template['last_sub_description_normal_tr_template'] = $last_sub_description_normal_tr_template;

                                $temp_content_trs .= $this->build_tr_template_with_data_fs_cf_subs($fs_company_info, $template, $data);
                            }
                            /* END OF Sub of investing activities */

                            /* Net cash movement in investing activities */
                            $data = [];
                            $template = [];
                            $net_cash_inv = array(
                                                'net_cash_movement_inv' => array(
                                                                            'group_ye'         => 0,
                                                                            'group_lye_end'    => 0,
                                                                            'company_ye'       => 0,
                                                                            'company_lye_end'  => 0,
                                                                            'note_display_num' => []
                                                                        )
                                            );

                            // Calculate for the values
                            foreach ($fs_state_cash_flows as $key_cfs_2 => $value_cfs_2)
                            {
                                if($value_cfs_2['parent_id'] == "#investing")
                                {
                                    $net_cash_inv['net_cash_movement_inv']['group_ye']        += $value_cfs_2['value_group_ye'];
                                    $net_cash_inv['net_cash_movement_inv']['group_lye_end']   += $value_cfs_2['value_group_lye_end'];
                                    $net_cash_inv['net_cash_movement_inv']['company_ye']      += $value_cfs_2['value_company_ye'];
                                    $net_cash_inv['net_cash_movement_inv']['company_lye_end'] += $value_cfs_2['value_company_lye_end'];
                                }
                            }

                            $data['fs_cf_type']                = 'net_cash_movement_inv';
                            $data['fs_cf_type_title']          = 'Net cash movement in investing activities';
                            $data['fs_state_cash_flows_fixed'] = $net_cash_inv;

                            $template['display_tr_template']   = $last_description_normal_tr_template;

                            $temp_content_trs .= $this->build_tr_template_with_data_fs_cf($fs_company_info, $template, $data);
                            /* END OF Net cash movement in investing activities */

                            $temp_content_trs .= $newline_template;
                        }

                        // Financing activities
                        if($check_financing_act[0]['status'])
                        {
                            // Fixed title for Financing activities
                            $fixed_title_operating_expenses = array('Financing activities');
                            $tr_item = $this->replace_tr_template_item($description_italic_tr_template, $fixed_title_operating_expenses);

                            $temp_content_trs .= $tr_item;

                            /* Sub of financing activities */
                            if(in_array('#financing', array_column($fs_state_cash_flows, 'parent_id')))
                            {
                                $data     = [];
                                $template = [];

                                $data['fs_state_cash_flows'] = $fs_state_cash_flows;
                                $data['parent_id']           = '#financing';
                                $data['category_id']         = 3;

                                $template['fixed_title'] = '';

                                $template['sub_description_normal_tr_template'] = '';
                                $template['sub_description_normal_tr_template'] = $sub_description_normal_tr_template;

                                $template['last_sub_description_normal_tr_template'] = $last_sub_description_normal_tr_template;

                                $temp_content_trs .= $this->build_tr_template_with_data_fs_cf_subs($fs_company_info, $template, $data);
                            }
                            /* END OF Sub of financing activities */

                            /* Net cash movement in financing activities */
                            $data = [];
                            $template = [];
                            $net_cash_fin = array(
                                                'net_cash_movement_fin' => array(
                                                                            'group_ye'         => 0,
                                                                            'group_lye_end'    => 0,
                                                                            'company_ye'       => 0,
                                                                            'company_lye_end'  => 0,
                                                                            'note_display_num' => []
                                                                        )
                                            );

                            // Calculate for the values
                            foreach ($fs_state_cash_flows as $key_cfs_3 => $value_cfs_3)
                            {
                                if($value_cfs_3['parent_id'] == "#financing")
                                {
                                    $net_cash_fin['net_cash_movement_fin']['group_ye']        += $value_cfs_3['value_group_ye'];
                                    $net_cash_fin['net_cash_movement_fin']['group_lye_end']   += $value_cfs_3['value_group_lye_end'];
                                    $net_cash_fin['net_cash_movement_fin']['company_ye']      += $value_cfs_3['value_company_ye'];
                                    $net_cash_fin['net_cash_movement_fin']['company_lye_end'] += $value_cfs_3['value_company_lye_end'];
                                }
                            }

                            $data['fs_cf_type']                = 'net_cash_movement_fin';
                            $data['fs_cf_type_title']          = 'Net cash movement in financing activities';
                            $data['fs_state_cash_flows_fixed'] = $net_cash_fin;

                            $template['display_tr_template']   = $last_description_normal_tr_template; 

                            $temp_content_trs .= $this->build_tr_template_with_data_fs_cf($fs_company_info, $template, $data);
                            /* END OF Net cash movement in financing activities */

                            $temp_content_trs .= $newline_template;
                        }

                        /* Changes in cash and cash equivalents */
                        $data = [];
                        $template = [];
                        $changes_in_cash_n_cash_eq = array(
                                                        'changes_cash_equivalent' => array(
                                                                                    'group_ye'         => 0,
                                                                                    'group_lye_end'    => 0,
                                                                                    'company_ye'       => 0,
                                                                                    'company_lye_end'  => 0,
                                                                                    'note_id' => []
                                                                                )
                                                    );

                        // Calculate for the values
                        if($check_operating_act[0]['status'])
                        {
                            $changes_in_cash_n_cash_eq['changes_cash_equivalent']['group_ye']        += $net_cash_opt['net_cash_movement_op']['group_ye'];
                            $changes_in_cash_n_cash_eq['changes_cash_equivalent']['group_lye_end']   += $net_cash_opt['net_cash_movement_op']['group_lye_end'];
                            $changes_in_cash_n_cash_eq['changes_cash_equivalent']['company_ye']      += $net_cash_opt['net_cash_movement_op']['company_ye'];
                            $changes_in_cash_n_cash_eq['changes_cash_equivalent']['company_lye_end'] += $net_cash_opt['net_cash_movement_op']['company_lye_end'];
                        }

                        if($check_investing_act[0]['status'])
                        {
                            $changes_in_cash_n_cash_eq['changes_cash_equivalent']['group_ye']        += $net_cash_inv['net_cash_movement_inv']['group_ye'];
                            $changes_in_cash_n_cash_eq['changes_cash_equivalent']['group_lye_end']   += $net_cash_inv['net_cash_movement_inv']['group_lye_end'];
                            $changes_in_cash_n_cash_eq['changes_cash_equivalent']['company_ye']      += $net_cash_inv['net_cash_movement_inv']['company_ye'];
                            $changes_in_cash_n_cash_eq['changes_cash_equivalent']['company_lye_end'] += $net_cash_inv['net_cash_movement_inv']['company_lye_end'];
                        }
                        
                        if($check_financing_act[0]['status'])
                        {
                            $changes_in_cash_n_cash_eq['changes_cash_equivalent']['group_ye']        += $net_cash_fin['net_cash_movement_fin']['group_ye'];
                            $changes_in_cash_n_cash_eq['changes_cash_equivalent']['group_lye_end']   += $net_cash_fin['net_cash_movement_fin']['group_lye_end'];
                            $changes_in_cash_n_cash_eq['changes_cash_equivalent']['company_ye']      += $net_cash_fin['net_cash_movement_fin']['company_ye'];
                            $changes_in_cash_n_cash_eq['changes_cash_equivalent']['company_lye_end'] += $net_cash_fin['net_cash_movement_fin']['company_lye_end'];
                        }

                        $data['fs_cf_type']                = 'changes_cash_equivalent';
                        $data['fs_cf_type_title']          = 'Changes in cash and cash equivalents';
                        $data['fs_state_cash_flows_fixed'] = $changes_in_cash_n_cash_eq;

                        $template['display_tr_template']   = $description_normal_tr_template;

                        $temp_content_trs .= $this->build_tr_template_with_data_fs_cf($fs_company_info, $template, $data);
                        /* END OF Changes in cash and cash equivalents */

                        /* Cash and equivalent at beginning of the year */
                        $data = [];
                        $template = [];

                        $data['fs_cf_type']                = 'cash_equivalent_begin';
                        $data['fs_cf_type_title']          = 'Cash and equivalent at beginning of the year';
                        $data['fs_state_cash_flows_fixed'] = $fs_state_cash_flows_fixed;

                        $template['display_tr_template']   = $last_description_normal_tr_template;

                        $temp_content_trs .= $this->build_tr_template_with_data_fs_cf($fs_company_info, $template, $data);
                        /* END OF Cash and equivalent at beginning of the year */

                        /* Cash and equivalent at end of the year */
                        $data = [];
                        $template = [];
                        // $cash_n_eq_end = array(
                        //                     'cash_equivalent_end' => array(
                        //                                                 'group_ye'         => 0,
                        //                                                 'group_lye_end'    => 0,
                        //                                                 'company_ye'       => 0,
                        //                                                 'company_lye_end'  => 0,
                        //                                                 'note_display_num' => []
                        //                                             )
                        //                 );

                        // foreach ($fs_state_cash_flows_fixed as $key1 => $value1) 
                        // {
                            // print_r($fs_state_cash_flows_fixed['cash_equivalent_begin']);
                        // }

                        if(count($fs_state_cash_flows_fixed) > 0)
                        {
                            $fs_state_cash_flows_fixed['cash_equivalent_end']['group_ye']        = $changes_in_cash_n_cash_eq['changes_cash_equivalent']['group_ye'] + $fs_state_cash_flows_fixed['cash_equivalent_begin']['group_ye'];
                            $fs_state_cash_flows_fixed['cash_equivalent_end']['group_lye_end']   = $changes_in_cash_n_cash_eq['changes_cash_equivalent']['group_lye_end'] + $fs_state_cash_flows_fixed['cash_equivalent_begin']['group_lye_end'];
                            $fs_state_cash_flows_fixed['cash_equivalent_end']['company_ye']      = $changes_in_cash_n_cash_eq['changes_cash_equivalent']['company_ye'] + $fs_state_cash_flows_fixed['cash_equivalent_begin']['company_ye'];
                            $fs_state_cash_flows_fixed['cash_equivalent_end']['company_lye_end'] = $changes_in_cash_n_cash_eq['changes_cash_equivalent']['company_lye_end'] + $fs_state_cash_flows_fixed['cash_equivalent_begin']['company_lye_end'];
                        }

                        $data['fs_cf_type']                = 'cash_equivalent_end';
                        $data['fs_cf_type_title']          = 'Cash and equivalent at end of the year';
                        $data['fs_state_cash_flows_fixed'] = $fs_state_cash_flows_fixed;

                        $template['display_tr_template']   = $overall_total_tr_template;

                        $temp_content_trs .= $this->build_tr_template_with_data_fs_cf($fs_company_info, $template, $data);
                        /* END OF Cash and equivalent at end of the year */

                        $tr_item_display_content = $temp_content_trs;
                        $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item_display_content, $replaced_tbl_template_2);

                        if($additional_info['generate_docs_without_tags'])
                        {
                            foreach ($ori_all_template as $at_key => $at_value) 
                            {
                                $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                            }
                        }

                        $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                    }
                    else
                    {
                        // $replaced_tbl_value = $this->hide_tbl($replaced_tbl_value, true);   // $hide_needed = true;
                        $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                        $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                        $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                    }
                }
                elseif($table_name == "Statement of changes in equity (group)" || $table_name == "Statement of changes in equity (company)") 
                {
                    $data_cie = array('fs_company_info_id' => $fs_company_info_id);

                    if($table_name == "Statement of changes in equity (group)" && $fs_company_info[0]['group_type'] == '1')
                    {
                        // $replaced_tbl_value = $this->hide_tbl($replaced_tbl_value, true);   // $hide_needed = true;
                        $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                        $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                        $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                    }
                    else
                    {
                        if($fs_company_info[0]['group_type'] == '1')
                        {
                            $data_cie = [];
                            $data_cie = $this->get_state_changes_in_equity_data($fs_company_info_id, 'company');
                            $data_cie['group_company'] = 'company';
                        }
                        else
                        {
                            if($table_name == "Statement of changes in equity (group)")
                            {
                                $data_cie = [];
                                $data_cie = $this->get_state_changes_in_equity_data($fs_company_info_id, 'group'); 
                                $data_cie['group_company'] = 'group';
                            }
                            elseif($table_name == "Statement of changes in equity (company)")
                            {
                                $data_cie = [];
                                $data_cie = $this->get_state_changes_in_equity_data($fs_company_info_id, 'company');
                                $data_cie['group_company'] = 'company';
                            }
                        }

                        $data_cie['fs_company_info_id']         = $fs_company_info_id;
                        $data_cie['generate_docs_without_tags'] = $additional_info['generate_docs_without_tags'];
                        $data_cie['table_name']                 = $table_name;

                        $replaced_tbl_value = $this->build_template_with_data_fs_changes_in_equity($tbl_value, $data_cie);

                        $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                        $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                    }
                }
                elseif($table_name == "Note 2 - Intangible assets (table)")
                {
                    $replaced_tbl_template   = $replaced_tbl_value; 
                    $replaced_tbl_template_2 = $replaced_tbl_template;

                    $is_hide_needed = $this->db->query("SELECT * FROM fs_ntfs_layout_template WHERE fs_company_info_id=" . $fs_company_info_id . " AND fs_ntfs_layout_template_default_id=31");
                    $is_hide_needed = $is_hide_needed->result_array();

                    // if(!$is_hide_needed[0]['is_checked'])
                    if(!$is_hide_needed[0]['is_checked'])
                    {
                        // $replaced_tbl_value = $this->hide_tbl($replaced_tbl_value, true);   // $hide_needed = false;
                        $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                        $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                        $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                    }
                    else
                    {
                        $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                        $description_tr_template = '';
                        $newline_template = '';
                        $last_line_space_tr_template = '';

                        // get templates and remove all the info first
                        foreach ($tbl_tr[0] as $tbl_tr_key => $tbl_tr_value) 
                        {
                            $tr_name_type = $this->get_tr_name_type($tbl_tr_value);  // to extract the first column name so that we know it is title or account name and value insert template.

                            if($tr_name_type == "{Description}")
                            {
                                $description_tr_template = $this->vanish_template($tbl_tr_value, 0);
                            }
                            elseif($tr_name_type == "{New Line}")
                            {
                                $newline_template = $this->vanish_template($tbl_tr_value, 0);
                                $newline_template = $this->remove_wr_from_tr($newline_template, [1]);

                                $last_line_space_tr_template = $tbl_tr_value;
                            }
                            elseif($tr_name_type != "Note 2 - Intangible assets (table)")
                            {
                                $replaced_tbl_template_2 = str_replace($tbl_tr_value, '', $replaced_tbl_template_2);
                            }
                        }

                        // load data and write in table
                        $sub_ia = $this->fs_notes_model->get_fs_intangible_assets($fs_company_info_id);

                        $tr_item = '';

                        if(count($sub_ia) > 0)
                        {
                            foreach ($sub_ia as $sia_key => $sia_value) 
                            {
                                $tr_item .= $this->replace_tr_template_item($description_tr_template, array($sia_value['name'], $sia_value['duration']));
                            }

                            $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item . $newline_template, $replaced_tbl_template_2);
                            $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                        }
                    }
                }
                elseif($table_name == "Note 2 - Property,plant and equipment (table)")
                {
                    $replaced_tbl_template   = $replaced_tbl_value; 
                    $replaced_tbl_template_2 = $replaced_tbl_template;

                    $is_hide_needed = $this->db->query("SELECT * FROM fs_ntfs_layout_template WHERE fs_company_info_id=" . $fs_company_info_id . " AND fs_ntfs_layout_template_default_id=35");
                    $is_hide_needed = $is_hide_needed->result_array();

                    // if(!$is_hide_needed[0]['is_checked'])
                    if(!$is_hide_needed[0]['is_checked'])
                    {
                        $replaced_tbl_value = $this->hide_tbl($replaced_tbl_value, false);   // $hide_needed = false;
                        $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                    }
                    else
                    {
                        $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                        // print_r($tbl_tr);

                        $description_tr_template = '';
                        $newline_template = '';
                        $last_line_space_tr_template = '';

                        // get templates and remove all the info first
                        foreach ($tbl_tr[0] as $tbl_tr_key => $tbl_tr_value) 
                        {
                            $tr_name_type = $this->get_tr_name_type($tbl_tr_value);  // to extract the first column name so that we know it is title or account name and value insert template.

                            if($tr_name_type == "{Description}")
                            {
                                $description_tr_template = $this->vanish_template($tbl_tr_value, 0);
                            }
                            elseif($tr_name_type == "{New Line}")
                            {
                                $newline_template = $this->vanish_template($tbl_tr_value, 0);
                                $newline_template = $this->remove_wr_from_tr($newline_template, [1]);

                                $last_line_space_tr_template = $tbl_tr_value;
                            }
                            elseif($tr_name_type != "Note 2 - Property,plant and equipment (table)")
                            {
                                $replaced_tbl_template_2 = str_replace($tbl_tr_value, '', $replaced_tbl_template_2);
                            }
                        }

                        // load data and write in table
                        $sub_ia = $this->fs_notes_model->get_fs_sub_ppe($fs_company_info_id);

                        $tr_item = '';

                        foreach ($sub_ia as $sia_key => $sia_value) 
                        {
                            $tr_item .= $this->replace_tr_template_item($description_tr_template, array($sia_value['name'], $sia_value['duration']));
                        }

                        $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item . $newline_template, $replaced_tbl_template_2);
                        $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                    }
                }
                elseif ($table_name == "Statement of detailed profit or loss (header)" || $table_name == "Schedule of operating expenses (header)") 
                {
                    if($fs_company_info[0]['first_set'])
                    {
                        $hide_column_data = array('all' => array(2), 'table_name' => $table_name);
                    }
                    else
                    {
                        $hide_column_data = [];
                    }

                    // print_r(array($fs_company_info[0]['first_set'], $hide_column_data));

                    $replaced_tbl_template = $this->hide_tbl_column($fs_company_info, $hide_column_data, $replaced_tbl_value, $replaced_xml);    // group_type, $hide_column_data, ... 
                    $replaced_tbl_template = $this->remove_tbl_tags_row($replaced_tbl_template, $additional_info, $table_name);

                    // print_r(array($replaced_tbl_template));

                    $replaced_xml = str_replace($tbl_value, $replaced_tbl_template, $replaced_xml);

                    $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                    foreach ($tbl_tr[0] as $tbl_tr_key => $tbl_tr_value) 
                    {
                        $wsdt_tbl_tr = $this->get_template_wsdt($tbl_tr_value);   

                        foreach ($wsdt_tbl_tr[0] as $wsdt_tbl_tr_key => $wsdt_tbl_tr_value) // loop tr
                        {
                            $alias_inner_tag = $this->get_tag('w:alias', $wsdt_tbl_tr_value);
                            $alias_inner_value = $this->get_attribute_value('w:val', $alias_inner_tag[0][0]);

                            $inner_tagName = $alias_inner_value[1][0];

                            $wsdtcontent = $this->get_template_wsdtcontent($wsdt_tbl_tr_value);  // get w:sdtContent

                            if($inner_tagName == "Statement Year End 1" || $inner_tagName == "Statement Year End 2")
                            {
                                $wr_arr = $this->get_template_wr($wsdtcontent[0][0]);
                                $new_wsdtcontent = $this->loop_template($wsdtcontent[0][0], $inner_tagName, $wr_arr[0], $fs_company_info_id);
                                $new_wsdt_tbl_tr_value = str_replace($wsdtcontent[0][0], $new_wsdtcontent, $wsdt_tbl_tr_value);

                                $replaced_xml = str_replace($wsdt_tbl_tr_value, $new_wsdt_tbl_tr_value, $replaced_xml);
                            }
                        }
                    }
                }
                elseif ($table_name == "Statement of detailed profit or loss") 
                {
                    $hide_column_data = [];

                    $replaced_tbl_template = $this->hide_tbl_column($fs_company_info, $hide_column_data, $replaced_tbl_value, $replaced_xml);    // group_type, $hide_column_data, ...
                    $replaced_xml = str_replace($tbl_value, $replaced_tbl_template, $replaced_xml);

                    $replaced_tbl_template_2 = $replaced_tbl_template;

                    $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                    $main_account_category_tr_template = '';
                    $sub_account_category_tr_template  = '';
                    $total_operating_expenses_template = '';
                    $subtotal_display_tr_template      = '';

                    $ori_all_template = [];

                    // get templates and remove all the info first
                    foreach ($tbl_tr[0] as $tbl_tr_key => $tbl_tr_value) 
                    {
                        $tr_name_type = $this->get_tr_name_type($tbl_tr_value);  // to extract the first column name so that we know it is title or account name and value insert template.

                        if($tr_name_type == "{New Line}")
                        {
                            array_push($ori_all_template, $tbl_tr_value);
                            $newline_template = $this->vanish_template($tbl_tr_value, 0);
                            $newline_template = $this->remove_wr_from_tr($newline_template, [1]);
                        }
                        elseif($tr_name_type == "{Main Account Category}")
                        {
                            array_push($ori_all_template, $tbl_tr_value);
                            $main_account_category_tr_template = $this->vanish_template($tbl_tr_value, 0); 
                        }
                        elseif($tr_name_type == "{Sub Account Category}")
                        {
                            array_push($ori_all_template, $tbl_tr_value);
                            $sub_account_category_tr_template = $this->vanish_template($tbl_tr_value, 0);

                            if($fs_company_info[0]['first_set'])
                            {
                                $sub_account_category_tr_template = $this->remove_wr_from_tr($sub_account_category_tr_template, [2]);
                            }
                        }
                        elseif($tr_name_type == "{Subtotal display}")
                        {
                            array_push($ori_all_template, $tbl_tr_value);
                            $subtotal_display_tr_template = $this->vanish_template($tbl_tr_value, 0);

                            if($fs_company_info[0]['first_set'])
                            {
                                $subtotal_display_tr_template = $this->remove_wr_from_tr($subtotal_display_tr_template, [1, 2]);
                            }
                            else
                            {
                                $subtotal_display_tr_template = $this->remove_wr_from_tr($subtotal_display_tr_template, [1]);
                            }
                        }
                        elseif($tr_name_type == "{Last Category / Subtotal Display 2}")
                        {
                            array_push($ori_all_template, $tbl_tr_value);
                            $last_category_or_subtotal_2_template = $this->vanish_template($tbl_tr_value, 0);

                            if($fs_company_info[0]['first_set'])
                            {
                                $last_category_or_subtotal_2_template = $this->remove_wr_from_tr($last_category_or_subtotal_2_template, [2]);
                            }
                        }
                        elseif($tr_name_type == "{Final total}")
                        {
                            array_push($ori_all_template, $tbl_tr_value);
                            $total_final_template_hide_ori = $tbl_tr_value;
                            $total_final_template = $this->vanish_template($tbl_tr_value, 0);

                            if($fs_company_info[0]['first_set'])
                            {
                                $total_final_template = $this->remove_wr_from_tr($total_final_template, [2]);
                            }
                        }
                        elseif($tr_name_type == $table_name)
                        {
                            array_push($ori_all_template, $tbl_tr_value);
                        }
                        elseif($tr_name_type != "Statement of detailed profit or loss")
                        {
                            $replaced_tbl_template_2 = str_replace($tbl_tr_value, '', $replaced_tbl_template_2);
                        }
                    }

                    $fs_ntfs_list = $this->fs_notes_model->get_fs_ntfs_json();

                    $dpl_key = array_search("Statement of detailed profit or loss", array_column($fs_ntfs_list['statements'], 'document_name'));
                    $dpl_ref_id = '';

                    if($dpl_key || (string)$dpl_key == '0')
                    {
                        $dpl_account_code = $fs_ntfs_list['statements'][$dpl_key]['reference_id']; // get account code
                        $fs_ntfs_list     = $fs_ntfs_list['statements'][$dpl_key];
                    }

                    /* get closing inventories account code (reference id) */
                    $closing_inventories_ac = "";
                    $closing_inventories_key = array_search("Closing inventories", array_column($fs_ntfs_list['description_reference_id'], "description")); // get key

                    if(!empty($closing_inventories_key) || (string)$closing_inventories_key == 0)
                    {
                        $closing_inventories_ac = $fs_ntfs_list['description_reference_id'][$closing_inventories_key]['account_code'];  // get description from fs_ntfs_list json from document name "Statement of detailed profit or loss"
                    }
                    /* END OF get closing inventories account code (reference id) */

                    $dpl_fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, $dpl_account_code);

                    $state_detailed_pro_loss = [];

                    foreach ($dpl_fca_id as $dpl_fca_id_key => $dpl_fca_id_value) 
                    {
                        array_push($state_detailed_pro_loss, $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, array($dpl_fca_id_value)));
                    }

                    $tr_item = '';

                    $temp_content_trs = "";
                    $temp_content_trs_other_income = "";
                    $revenue_exist              = false;
                    $cost_of_sales_exist        = false;

                    $revenue_subtotal_1 = 0;
                    $revenue_subtotal_2 = 0;

                    $cost_of_sale_subtotal_1 = 0;
                    $cost_of_sale_subtotal_2 = 0;

                    $other_income_subtotal_1 = 0;
                    $other_income_subtotal_2 = 0;

                    // print_r($state_detailed_pro_loss);

                    foreach($state_detailed_pro_loss as $key => $main_category)
                    {
                        $subtotal_1 = 0;
                        $subtotal_2 = 0;

                        $tr_item = '';

                        $tr_category_name = $template_category_name[0][0];
                        $add_less_display = "";
                        $main_account_code = $main_category[0]['parent_array'][0]['account_code'];
                    
                        /* Settings */
                        // check using this account code > take the default description > do checking using description (list take from json)
                        $fs_ntfs_list_key = array_search($main_account_code, array_column($fs_ntfs_list['description_reference_id'], "account_code"));  // get key

                        if(!empty($fs_ntfs_list_key) || (string)$fs_ntfs_list_key == 0)
                        {
                            $main_account_description = $fs_ntfs_list['description_reference_id'][$fs_ntfs_list_key]['description'];    // get description from fs_ntfs_list json from document name "Statement of detailed profit or loss"
                        }

                        if($main_account_description == "Revenue")  // revenue
                        {
                            $revenue_exist = true;
                        }
                        elseif($main_account_description == "Cost of Sales")    // cost of sale
                        {
                            $cost_of_sales_exist = true;
                            $add_less_display    = "Less: ";
                        }
                        elseif($main_account_description == "Income")   // other income
                        {
                            $add_less_display = "Add: ";
                        }

                        // Display main category 
                        if(count($main_category[0]['child_array']) > 0)
                        {
                            // if got sub, use category name
                            // For Category Name tr
                            $main_account_category_items = array($add_less_display . ucfirst(strtolower($main_category[0]['parent_array'][0]['description'])));
                            $tr_item = $this->replace_tr_template_item($main_account_category_tr_template, $main_account_category_items);

                            // keep other income template for later use.
                            if($main_account_description == "Income")    // other income
                            {
                                $temp_content_trs_other_income .= $tr_item;
                            }
                            else
                            {
                                $temp_content_trs .= $tr_item;
                            }

                            // For account under category (child)
                            foreach($main_category[0]['child_array'] as $key => $value_1)
                            {
                                $tr_item = '';

                                if($value_1['parent_array'] != null && $value_1['child_array'] != null)
                                {
                                    foreach ($value_1['parent_array'] as $vp1_key => $vp1_value) 
                                    {
                                        if($main_account_description == "Revenue" || $main_account_description == "Income")
                                        {
                                            $vp1_value['total_c']       = $vp1_value['total_c'] * -1;
                                            $vp1_value['total_c_lye']   = $vp1_value['total_c_lye'] * -1;
                                        }

                                        /* ------ Add in Display "Less:" for Closing inventories ------ */
                                        if($vp1_value['account_code'] == $closing_inventories_ac)
                                        {
                                            $vp1_value['description'] = $add_less_display . $vp1_value['description'];
                                        }
                                        /* ------ END OF Add in Display "Less:" for Closing inventories ------ */

                                        if($fs_company_info[0]['first_set'])
                                        {
                                            $sub_category_items = array(
                                                        $vp1_value['description'], 
                                                        '', 
                                                        $this->fs_replace_content_model->negative_bracket($vp1_value['total_c'])
                                                    );
                                        }
                                        else
                                        {
                                            $sub_category_items = array(
                                                        $vp1_value['description'], 
                                                        $this->fs_replace_content_model->negative_bracket($vp1_value['total_c']), 
                                                        $this->fs_replace_content_model->negative_bracket($vp1_value['total_c_lye'])
                                                    );
                                        }
                                        
                                        $tr_item = $this->replace_tr_template_item($sub_account_category_tr_template, $sub_category_items);
                                    }
                                }
                                elseif($value_1['child_array'] != null)
                                {
                                    if($main_account_description == "Revenue" || $main_account_description == "Income")
                                    {
                                        $value_1['child_array']['value']                     = $value_1['child_array']['value'] * -1;
                                        $value_1['child_array']['company_end_prev_ye_value'] = $value_1['child_array']['company_end_prev_ye_value'] * -1;
                                    }

                                    if($fs_company_info[0]['first_set'])
                                    {
                                        $sub_category_items = array(
                                                            $value_1['child_array']['description'], 
                                                            '', 
                                                            $this->fs_replace_content_model->negative_bracket($value_1['child_array']['value'])
                                                        );
                                    }
                                    else
                                    {
                                        $sub_category_items = array(
                                                            $value_1['child_array']['description'], 
                                                            $this->fs_replace_content_model->negative_bracket($value_1['child_array']['value']), 
                                                            $this->fs_replace_content_model->negative_bracket($value_1['child_array']['company_end_prev_ye_value'])
                                                        );
                                    }
                                    
                                    $tr_item = $this->replace_tr_template_item($sub_account_category_tr_template, $sub_category_items);
                                }

                                // keep other income template for later use.
                                if($main_account_description == "Income")    // other income
                                {
                                    $temp_content_trs_other_income .= $tr_item;
                                }
                                else
                                {
                                    $temp_content_trs .= $tr_item;
                                }
                            }
                        }
                        else
                        {
                            // if got sub, use category name
                            // For Category Name tr
                            $tr_item = '';
                            $main_account_category_items = array(
                                                                $add_less_display . ucfirst(strtolower($main_category[0]['parent_array'][0]['description'])),
                                                                '-',
                                                                '-'
                                                            );
                            $tr_item = $this->replace_tr_template_item($last_category_or_subtotal_2_template, $main_account_category_items);

                            // keep other income template for later use.
                            if($main_account_description == "Income")   // other income
                            {
                                $temp_content_trs_other_income .= $tr_item . $newline_template;
                            }
                            else
                            {
                                $temp_content_trs .= $tr_item . $newline_template;
                            }
                        }



                        // display subtotal
                        if(count($main_category[0]['child_array']) > 0)
                        {
                            $tr_item = '';

                            $subtotal_1 = $main_category[0]['parent_array'][0]['total_c'];
                            $subtotal_2 = $main_category[0]['parent_array'][0]['total_c_lye'];

                            if($main_account_description == "Revenue")    // revenue
                            {
                                $subtotal_1 = $subtotal_1 * -1;
                                $subtotal_2 = $subtotal_2 * -1;

                                $revenue_subtotal_1 = $subtotal_1;
                                $revenue_subtotal_2 = $subtotal_2;
                            }
                            elseif($main_account_description == "Cost of Sales")    // cost of sale
                            {
                                $cost_of_sale_subtotal_1 = $subtotal_1;
                                $cost_of_sale_subtotal_2 = $subtotal_2;
                            }
                            elseif($main_account_description == "Income")    // Other income
                            {
                                $subtotal_1 = $subtotal_1 * -1;
                                $subtotal_2 = $subtotal_2 * -1;

                                $other_income_subtotal_1 = $subtotal_1;
                                $other_income_subtotal_2 = $subtotal_2;
                            }

                            if($fs_company_info[0]['first_set'])
                            {
                                $sub_category_items = array(
                                                    $value_1['child_array']['description'], 
                                                    '', 
                                                    $this->fs_replace_content_model->negative_bracket($subtotal_1)
                                                );
                            }
                            else
                            {
                                $sub_category_items = array(
                                                    $value_1['child_array']['description'], 
                                                    $this->fs_replace_content_model->negative_bracket($subtotal_1), 
                                                    $this->fs_replace_content_model->negative_bracket($subtotal_2)
                                                );
                            }
                            
                            $tr_item = $this->replace_tr_template_item($subtotal_display_tr_template, $sub_category_items);

                            // keep other income template for later use.
                            if($main_account_description == "Income")    // other income
                            {
                                $temp_content_trs_other_income .= $tr_item;
                            }
                            else
                            {
                                $temp_content_trs .= $tr_item;
                            }
                        }
                    }

                    /* ---------------------------- for gross profit ---------------------------- */

                    // $tr_gross_profit = str_replace('{{Gross Profit}}', ucfirst(strtolower($this->fs_replace_content_model->negative_bracket($state_detailed_pro_loss_main_total[0]['gross_profit']))), $tr_gross_profit);

                    $gross_profit_1 = 0.00;
                    $gross_profit_2 = 0.00;

                    $tr_gross_profit = '';

                    // print_r(array($revenue_subtotal_1, $cost_of_sale_subtotal_1));

                    if($fs_company_info[0]['first_set'])
                    {
                        $gross_profit_items = array(
                                            'Gross Profit', 
                                            '',
                                            $this->fs_replace_content_model->negative_bracket($revenue_subtotal_1 - $cost_of_sale_subtotal_1)
                                            // $this->fs_replace_content_model->negative_bracket($gross_profit_1), 
                                            // $this->fs_replace_content_model->negative_bracket($gross_profit_2)
                                        );
                    }
                    else
                    {
                        $gross_profit_items = array(
                                            'Gross Profit', 
                                            $this->fs_replace_content_model->negative_bracket($revenue_subtotal_1 - $cost_of_sale_subtotal_1),
                                            $this->fs_replace_content_model->negative_bracket($revenue_subtotal_2 - $cost_of_sale_subtotal_2)
                                            // $this->fs_replace_content_model->negative_bracket($gross_profit_1), 
                                            // $this->fs_replace_content_model->negative_bracket($gross_profit_2)
                                        );
                    }
                    
                    $tr_gross_profit = $this->replace_tr_template_item($last_category_or_subtotal_2_template, $gross_profit_items);

                    if($revenue_exist && $cost_of_sales_exist)
                    {
                        $gross_profit_final_template = $tr_gross_profit;
                    }
                    /* ---------------------------- END OF for gross profit ---------------------------- */

                    $tr_item_display_content = $temp_content_trs;   // Add first part first (revenue & cost of sale)
                    $tr_item_display_content .= $gross_profit_final_template;

                    // Add new line
                    if(!empty($temp_content_trs_other_income))
                    {
                        $tr_item_display_content .= $newline_template;
                    }

                    // Add second part (other income)
                    $tr_item_display_content .= $temp_content_trs_other_income . $newline_template;

                    // Add in Less: Operating Expenses
                    $fs_ntfs_list_soe = $this->fs_notes_model->get_fs_ntfs_json();

                    $er_key = array_search("Schedule of operating expenses", array_column($fs_ntfs_list_soe['statements'], 'document_name'));
                    $dpl_ref_id = '';

                    if($er_key || (string)$er_key == '0')
                    {
                        $er_account_code = $fs_ntfs_list_soe['statements'][$er_key]['reference_id']; // get account code
                    }

                    $fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, $er_account_code);
                    $operating_expenses = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $fca_id);

                    // print_r($operating_expenses);

                    // $get_expenses = $this->fs_account_category_model->get_account_with_sub_round_off($fs_company_info_id, array('M1006'));
                    $total_operating_expenses_current = $operating_expenses[0]['parent_array'][0];

                    if($fs_company_info[0]['first_set'])
                    {
                        $total_operating_expenses_current = array(
                                                            '',
                                                            'total_operating_expenses'      => $total_operating_expenses_current['total_c']
                                                        );
                    }
                    else
                    {
                        $total_operating_expenses_current = array(
                                                            'total_operating_expenses'      => $total_operating_expenses_current['total_c'],
                                                            'total_operating_expenses_ly'   => $total_operating_expenses_current['total_c_lye']
                                                        );
                    }

                    // print_r($total_operating_expenses_current);
                    if($fs_company_info[0]['first_set'])
                    {
                        $tr_item_display_content .= $this->replace_tr_template_item($last_category_or_subtotal_2_template, 
                                                    array(
                                                        'Less: Operating expenses (As per schedule)',
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket($total_operating_expenses_current['total_operating_expenses'])
                                                    )
                                                );
                    }
                    else
                    {
                        $tr_item_display_content .= $this->replace_tr_template_item($last_category_or_subtotal_2_template, 
                                                    array(
                                                        'Less: Operating expenses (As per schedule)',
                                                        $this->fs_replace_content_model->negative_bracket($total_operating_expenses_current['total_operating_expenses']),
                                                        $this->fs_replace_content_model->negative_bracket($total_operating_expenses_current['total_operating_expenses_ly'])
                                                    )
                                                );
                    }
                    
                    // Add in Profit for the year 
                    if($fs_company_info[0]['first_set'])
                    {
                        $tr_item_display_content .= $this->replace_tr_template_item($total_final_template, 
                                                    array(
                                                        'Profit for the year',
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket(($revenue_subtotal_1 - $cost_of_sale_subtotal_1) + $other_income_subtotal_1 - $total_operating_expenses_current['total_operating_expenses'])
                                                    )
                                                );
                    }
                    else
                    {
                        $tr_item_display_content .= $this->replace_tr_template_item($total_final_template, 
                                                    array(
                                                        'Profit for the year',
                                                        $this->fs_replace_content_model->negative_bracket(($revenue_subtotal_1 - $cost_of_sale_subtotal_1) + $other_income_subtotal_1 - $total_operating_expenses_current['total_operating_expenses']),
                                                        $this->fs_replace_content_model->negative_bracket(($revenue_subtotal_2 - $cost_of_sale_subtotal_2) + $other_income_subtotal_2 - $total_operating_expenses_current['total_operating_expenses_ly'])
                                                    )
                                                );
                    }

                    // replace and add in values.
                    $replaced_tbl_template_2 = str_replace($total_final_template_hide_ori, $total_final_template_hide_ori . $tr_item_display_content, $replaced_tbl_template_2);

                    // remove hidden templates
                    if($additional_info['generate_docs_without_tags'])
                    {
                        foreach ($ori_all_template as $at_key => $at_value) 
                        {
                            $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                        }
                    }
                    $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                }
                elseif ($table_name == "Schedule of operating expenses") 
                {
                    $hide_column_data = [];

                    $replaced_tbl_template = $this->hide_tbl_column($fs_company_info, $hide_column_data, $replaced_tbl_value, $replaced_xml);    // group_type, $hide_column_data, ...
                    $replaced_xml = str_replace($tbl_value, $replaced_tbl_template, $replaced_xml);

                    $replaced_tbl_template_2 = $replaced_tbl_template;

                    $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                    $main_account_category_tr_template = '';
                    $sub_account_category_tr_template  = '';
                    $total_operating_expenses_template = '';
                    $subtotal_display_tr_template      = '';

                    $ori_all_template = [];

                    // get templates and remove all the info first
                    foreach ($tbl_tr[0] as $tbl_tr_key => $tbl_tr_value) 
                    {
                        $tr_name_type = $this->get_tr_name_type($tbl_tr_value);  // to extract the first column name so that we know it is title or account name and value insert template.

                        if($tr_name_type == "{Main Account Category}")
                        {
                            array_push($ori_all_template, $tbl_tr_value);
                            $main_account_category_tr_template = $this->vanish_template($tbl_tr_value, 0);
                        }
                        elseif($tr_name_type == "{Sub Account Category}")
                        {
                            array_push($ori_all_template, $tbl_tr_value);
                            $sub_account_category_tr_template = $this->vanish_template($tbl_tr_value, 0);

                            // print_r(array($fs_company_info[0]['first_set']));
                            if($fs_company_info[0]['first_set'])
                            {
                                $sub_account_category_tr_template = $this->remove_wr_from_tr($sub_account_category_tr_template, [2]);
                            }
                        }
                        elseif($tr_name_type == "{Subtotal display}")
                        {
                            array_push($ori_all_template, $tbl_tr_value);
                            // $subtotal_display_tr_template_hide_ori = $tbl_tr_value;
                            $subtotal_display_tr_template = $this->vanish_template($tbl_tr_value, 0);

                            if($fs_company_info[0]['first_set'])
                            {
                                $subtotal_display_tr_template = $this->remove_wr_from_tr($subtotal_display_tr_template, [1, 2]);
                            }
                            else
                            {
                                $subtotal_display_tr_template = $this->remove_wr_from_tr($subtotal_display_tr_template, [1]);
                            }
                        }
                        // elseif($tr_name_type == "{New Line}")
                        // {
                        //     $newline_template = $this->vanish_template($tbl_tr_value, 0);
                        //     $newline_template = $this->remove_wr_from_tr($newline_template, [1]);

                        //     // print_r($newline_template);
                        // }
                        elseif($tr_name_type == "{Total operating expenses}")
                        {
                            array_push($ori_all_template, $tbl_tr_value);
                            $total_operating_expenses_template_hide_ori = $tbl_tr_value;
                            $total_operating_expenses_template = $this->vanish_template($tbl_tr_value, 0);

                            if($fs_company_info[0]['first_set'])
                            {
                                $total_operating_expenses_template = $this->remove_wr_from_tr($total_operating_expenses_template, [2]);
                            }
                        }
                        elseif($tr_name_type == $table_name)
                        {
                            array_push($ori_all_template, $tbl_tr_value);
                        }
                        elseif($tr_name_type != "Schedule of operating expenses")
                        {
                            $replaced_tbl_template_2 = str_replace($tbl_tr_value, '', $replaced_tbl_template_2);
                        }
                    }

                    // get data from schedule of operating expenses
                    $fs_ntfs_settings_list = $this->fs_notes_model->get_fs_ntfs_json();
                    $er_key = array_search("Schedule of operating expenses", array_column($fs_ntfs_settings_list['statements'], 'document_name'));

                    $er_ref_id = '';

                    if($er_key || (string)$er_key == '0')
                    {
                        $er_account_code = $fs_ntfs_settings_list['statements'][$er_key]['reference_id']; // get account code
                    }

                    $fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, $er_account_code);
                    $operating_expenses = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $fca_id);

                    // $operating_expenses = $this->fs_statements_model->get_account_category_item_round_off_list($fs_company_info_id, array('S0006', 'S0007', 'S0008'));
                    $tr_item = '';
                    $total_operating_expenses = 0.00;
                    $total_operating_expenses_last_year = 0.00;

                    // write details by row
                    if(count($operating_expenses) > 0)
                    {
                        foreach($operating_expenses[0]['child_array'] as $key => $value)
                        {
                            $main_category_name = array(ucfirst(strtolower($value['parent_array'][0]['description'])));
                            $tr_item .= $this->replace_tr_template_item($main_account_category_tr_template, $main_category_name); 
                            $subtotal = 0.00;
                            $subtotal_last_year = 0.00;

                            foreach($value['child_array'] as $key_1 => $value_1)
                            {
                                if(!empty($value_1['parent_array']))
                                {
                                    if($fs_company_info[0]['first_set'])
                                    {
                                        $sub_category_items = array(
                                                            $value_1['parent_array'][0]['description'], 
                                                            '', 
                                                            // $this->fs_replace_content_model->negative_bracket($value_1['parent_array'][0]['value'])
                                                            $this->fs_replace_content_model->negative_bracket($value_1['parent_array'][0]['total_c'])
                                                        );
                                    }
                                    else
                                    {
                                        $sub_category_items = array(
                                                            $value_1['parent_array'][0]['description'], 
                                                            // $this->fs_replace_content_model->negative_bracket($value_1['parent_array'][0]['value']), 
                                                            // $this->fs_replace_content_model->negative_bracket($value_1['parent_array'][0]['company_end_prev_ye_value'])
                                                            $this->fs_replace_content_model->negative_bracket($value_1['parent_array'][0]['total_c']),
                                                            $this->fs_replace_content_model->negative_bracket($value_1['parent_array'][0]['total_c_lye'])
                                                        );
                                    }

                                    // $subtotal += $value_1['parent_array'][0]['value'];
                                    // $subtotal_last_year += $value_1['parent_array'][0]['company_end_prev_ye_value'];

                                    $subtotal += $value_1['parent_array'][0]['total_c'];
                                    $subtotal_last_year += $value_1['parent_array'][0]['total_c_lye'];
                                }
                                else
                                {
                                    if($fs_company_info[0]['first_set'])
                                    {
                                        $sub_category_items = array(
                                                            $value_1['child_array']['description'], 
                                                            '', 
                                                            $this->fs_replace_content_model->negative_bracket($value_1['child_array']['value'])
                                                        );
                                    }
                                    else
                                    {
                                        $sub_category_items = array(
                                                            $value_1['child_array']['description'], 
                                                            $this->fs_replace_content_model->negative_bracket($value_1['child_array']['value']), 
                                                            $this->fs_replace_content_model->negative_bracket($value_1['child_array']['company_end_prev_ye_value'])
                                                        );
                                    }
                                    

                                    $subtotal += $value_1['child_array']['value'];
                                    $subtotal_last_year += $value_1['parent_array'][0]['company_end_prev_ye_value'];
                                }

                                $tr_item .= $this->replace_tr_template_item($sub_account_category_tr_template, $sub_category_items);
                            }

                            if($fs_company_info[0]['first_set'])
                            {
                                $subtotal_items = array(
                                                    'Subtotal:',
                                                    '-',
                                                    $this->fs_replace_content_model->negative_bracket($subtotal)
                                                );
                            }
                            else
                            {
                                $subtotal_items = array(
                                                    'Subtotal:',
                                                    $this->fs_replace_content_model->negative_bracket($subtotal),
                                                    $this->fs_replace_content_model->negative_bracket($subtotal_last_year)
                                                );
                            }

                            $tr_item .= $this->replace_tr_template_item($subtotal_display_tr_template, $subtotal_items);

                            $total_operating_expenses += $subtotal;
                            $total_operating_expenses_last_year += $subtotal_last_year;
                        }
                    }
                    
                    if($fs_company_info[0]['first_set'])
                    {
                        $total_operating_expenses_items = array(
                                                    'Total operating expenses', 
                                                    '-', 
                                                    $this->fs_replace_content_model->negative_bracket($total_operating_expenses)
                                                );
                    }
                    else
                    {
                        $total_operating_expenses_items = array(
                                                    'Total operating expenses', 
                                                    $this->fs_replace_content_model->negative_bracket($total_operating_expenses), 
                                                    $this->fs_replace_content_model->negative_bracket($total_operating_expenses_last_year)
                                                );
                    }

                    $tr_item .= $this->replace_tr_template_item($total_operating_expenses_template, $total_operating_expenses_items);

                    $replaced_tbl_template_2 = str_replace($total_operating_expenses_template_hide_ori, $total_operating_expenses_template_hide_ori . $tr_item, $replaced_tbl_template_2);

                    // remove hidden templates
                    if($additional_info['generate_docs_without_tags'])
                    {
                        foreach ($ori_all_template as $at_key => $at_value) 
                        {
                            $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                        }
                    }
                    $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                }
                elseif($table_name == "Employee benefits expense (not first set)" || $table_name == "Employee benefits expense (first set)")  // Table for Employee benefits expense
                {
                    $replaced_tbl_template   = $replaced_tbl_value; 
                    $replaced_tbl_template_2 = $replaced_tbl_template;

                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name);
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));

                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name);

                    $ori_all_template = [];

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                        if($is_hide_content)
                        {
                            if($additional_info['generate_docs_without_tags'])
                            {
                                $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                                $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                                $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                            }   
                            else
                            {
                                // $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                            }
                        }
                        else
                        {
                            $newline_template            = '';
                            $description_tr_template     = '';
                            $last_line_space_tr_template = '';

                            $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                            // get templates and remove all the info first
                            $extra_info = array(
                                'hide_group_column'       => $hide_column_data['all'],
                                'template_num_of_line'    => $hide_column_data['clear_tr_after_row'],
                                'replaced_tbl_template_2' => $replaced_tbl_template_2,
                                'group_type'              => $fs_company_info[0]['group_type'],
                                'table_name'              => $table_name 
                            );

                            $tr_template_data = $this->build_tbl_template_tr_data($tbl_tr, $extra_info); 

                            $replaced_tbl_template_2     = $tr_template_data['replaced_tbl_template_2'];
                            $description_tr_template     = $tr_template_data['description'];
                            $last_line_space_tr_template = $tr_template_data['last_line_space'];
                            $newline_template            = $tr_template_data['newline'];

                            // set value to template
                            $tr_item = '';

                            $data = $this->get_t1_value_tr_template($table_name, $fs_company_info_id); 

                            $total = array(
                                        'group_cy'   => 0,
                                        'group_ly'   => 0,
                                        'company_cy' => 0,
                                        'company_ly' => 0
                                    );

                            // data from table "fs_tax_expense_reconciliation"
                            $tr_item .= $newline_template;

                            // print_r($data);

                            foreach ($data[0]['child_array'] as $key => $value) 
                            {
                                $show_content = $this->verify_line_no_value(array($value['parent_array'][0]['group_end_this_ye_value'], $value['parent_array'][0]['group_end_prev_ye_value'], $value['parent_array'][0]['value'], $value['parent_array'][0]['company_end_prev_ye_value']), $fs_company_info);

                                if($show_content) 
                                {
                                    $display_data = [];

                                    // overall_total_desc
                                    if($fs_company_info[0]['first_set'])
                                    {
                                        $display_data = array(
                                                            $value['parent_array'][0]['description'],
                                                            $this->fs_replace_content_model->negative_bracket($value['parent_array'][0]['group_end_this_ye_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($value['parent_array'][0]['total_c'])
                                                        );
                                    }
                                    else
                                    {
                                        $display_data = array(
                                                            $value['parent_array'][0]['description'],
                                                            $this->fs_replace_content_model->negative_bracket($value['parent_array'][0]['group_end_this_ye_value']),
                                                            $this->fs_replace_content_model->negative_bracket($value['parent_array'][0]['group_end_prev_ye_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($value['parent_array'][0]['total_c']),
                                                            $this->fs_replace_content_model->negative_bracket($value['parent_array'][0]['total_c_lye'])
                                                        );
                                    }

                                    $total['group_cy']   += $value['parent_array'][0]['group_end_this_ye_value'];
                                    $total['group_ly']   += $value['parent_array'][0]['group_end_prev_ye_value'];
                                    $total['company_cy'] += $value['parent_array'][0]['total_c'];
                                    $total['company_ly'] += $value['parent_array'][0]['total_c_lye'];

                                    $tr_item .= $this->replace_tr_template_item($description_tr_template, $display_data);
                                }
                            }

                            // overall_total_desc
                            $display_data = [];

                            if($fs_company_info[0]['first_set'])
                            {
                                $display_data = array(
                                                    "",
                                                    $this->fs_replace_content_model->negative_bracket($total['group_cy']),
                                                    '',
                                                    $this->fs_replace_content_model->negative_bracket($total['company_cy'])
                                                );
                            }
                            else
                            {
                                $display_data = array(
                                                    "",
                                                    $this->fs_replace_content_model->negative_bracket($total['group_cy']),
                                                    $this->fs_replace_content_model->negative_bracket($total['group_ly']),
                                                    '',
                                                    $this->fs_replace_content_model->negative_bracket($total['company_cy']),
                                                    $this->fs_replace_content_model->negative_bracket($total['company_ly'])
                                                );
                            }
                            $tr_item .= $this->replace_tr_template_item($tr_template_data['overall_total'], $display_data);
                            $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item . $newline_template, $replaced_tbl_template_2);

                            // remove hidden templates
                            if($additional_info['generate_docs_without_tags'])
                            {
                                foreach ($tr_template_data['ori_all_template'] as $at_key => $at_value) 
                                {
                                    $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                }
                            }
                            $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                        }
                    }
                }
                elseif($table_name == "Note 4 - Profit Before Tax (not first set)" || $table_name == "Note 4 - Profit Before Tax (first set)") 
                {
                    $replaced_tbl_template   = $replaced_tbl_value; 
                    $replaced_tbl_template_2 = $replaced_tbl_template; 

                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name);
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));

                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name);

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                        if($is_hide_content)
                        {
                            if($additional_info['generate_docs_without_tags'])
                            {
                                $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                                $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                                $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                            }   
                            else
                            {
                                // $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                            }
                        }
                        else
                        {
                            $title_italic_tr_template    = '';
                            $description_tr_template     = '';
                            $last_line_desc_tr_template  = '';
                            $newline_template            = '';
                            $last_line_space_tr_template = '';

                            $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                            // get templates and remove all the info first
                            $extra_info = array(
                                'hide_group_column'       => $hide_column_data['all'],
                                'template_num_of_line'    => 5,
                                'replaced_tbl_template_2' => $replaced_tbl_template_2,
                                'group_type' => $fs_company_info[0]['group_type'],
                                'table_name' => $table_name
                            );

                            $tr_template_data = $this->build_tbl_template_tr_data($tbl_tr, $extra_info);

                            $replaced_tbl_template_2     = $tr_template_data['replaced_tbl_template_2'];
                            $last_line_space_tr_template = $tr_template_data['last_line_space'];

                            // set value to template
                            $tr_item = '';

                            /* get data */
                            $income_fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, array('I000'));
                            $income_list = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $income_fca_id);

                            $expense_fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, array('E101'));
                            $expenses_list = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $expense_fca_id);

                            $this->data['income_list']   = $income_list;
                            $this->data['expenses_list'] = $expenses_list;

                            $profit_b4_tax_ntfs = $this->fs_notes_model->get_fs_profit_be4_tax($fs_company_info_id);

                            foreach ($profit_b4_tax_ntfs as $key => $value) 
                            {
                                if($value['type'] == 'Branch')
                                {
                                    if($value['income_expense_type'] == 'income')
                                    {
                                        if(isset($income_list[0]))
                                        {
                                            if(isset($income_list[0]['child_array']))
                                            {
                                                foreach ($income_list[0]['child_array'] as $ikey => $ivalue) 
                                                {
                                                    if(isset($ivalue['parent_array']))
                                                    {
                                                        if($ivalue['parent_array'][0]['fca_id'] == $value['fs_categorized_account_id'])
                                                        {
                                                            $profit_b4_tax_ntfs[$key]['total_c']     = $ivalue['parent_array'][0]['total_c'];
                                                            $profit_b4_tax_ntfs[$key]['total_c_lye'] = $ivalue['parent_array'][0]['total_c_lye'];
                                                            $profit_b4_tax_ntfs[$key]['total_g']     = $ivalue['parent_array'][0]['total_g'];
                                                            $profit_b4_tax_ntfs[$key]['total_g_lye'] = $ivalue['parent_array'][0]['total_g_lye'];
                                                        }
                                                    }
                                                }
                                            }
                                            
                                        }
                                    }
                                    elseif($value['income_expense_type'] == 'expense')
                                    {
                                        if(isset($expenses_list[0]))
                                        {
                                            if(isset($expenses_list[0]['child_array']))
                                            {
                                                foreach ($expenses_list[0]['child_array'] as $ekey => $evalue) 
                                                {
                                                    if(isset($evalue['parent_array']))
                                                    {
                                                        if($evalue['parent_array'][0]['fca_id'] == $value['fs_categorized_account_id'])
                                                        {
                                                            $profit_b4_tax_ntfs[$key]['total_c']     = $evalue['parent_array'][0]['total_c'];
                                                            $profit_b4_tax_ntfs[$key]['total_c_lye'] = $evalue['parent_array'][0]['total_c_lye'];
                                                            $profit_b4_tax_ntfs[$key]['total_g']     = $evalue['parent_array'][0]['total_g'];
                                                            $profit_b4_tax_ntfs[$key]['total_g_lye'] = $evalue['parent_array'][0]['total_g_lye'];
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            /* END OF get data */

                            $temp_income_expense_type = '';

                            foreach ($profit_b4_tax_ntfs as $pb4t_key => $pb4t_value) 
                            {
                                if($pb4t_value['income_expense_type'] != $temp_income_expense_type)
                                {
                                    if($pb4t_key != 0)
                                    {
                                        $tr_item .= $tr_template_data['newline'];
                                    }
                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['title_italic'], array(ucfirst($pb4t_value['income_expense_type'] . ':')));
                                }

                                if($fs_company_info[0]['first_set'])
                                {
                                    $item_data = array(
                                                $pb4t_value['description'],
                                                $this->fs_replace_content_model->negative_bracket($pb4t_value['group_end_this_ye_value']),
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($pb4t_value['value']),
                                            );
                                }
                                else
                                {
                                    $item_data = array(
                                                $pb4t_value['description'],
                                                $this->fs_replace_content_model->negative_bracket($pb4t_value['group_end_this_ye_value']),
                                                $this->fs_replace_content_model->negative_bracket($pb4t_value['group_end_prev_ye_value']),
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($pb4t_value['value']),
                                                $this->fs_replace_content_model->negative_bracket($pb4t_value['company_end_prev_ye_value']),
                                            );
                                }

                                if($pb4t_key+1 == count($profit_b4_tax_ntfs)) // last line item
                                {
                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['last_line_desc'], $item_data);
                                }
                                else
                                {
                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['description'], $item_data);
                                }
                                $temp_income_expense_type = $pb4t_value['income_expense_type'];
                            }

                            $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item . $tr_template_data['newline'], $replaced_tbl_template_2);

                            // remove hidden templates
                            if($additional_info['generate_docs_without_tags'])
                            {
                                foreach ($tr_template_data['ori_all_template'] as $at_key => $at_value) 
                                {
                                    $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                }
                                $replaced_tbl_template_2 = $this->remove_tbl_tags_row($replaced_tbl_template_2, $additional_info, $table_name);
                            }
                            $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                        }
                    }
                    // $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                }
                elseif(
                    $table_name == "Employee benefits expense (header)(not first set)" || $table_name == "Employee benefits expense (header)(first set)" ||
                    $table_name == "Note 4 - Profit Before Tax (header)(not first set)" || $table_name == "Note 4 - Profit Before Tax (header)(first set)" ||
                    $table_name == "Note 5 - Tax expense (table_1)(header)(not first set)" || $table_name == "Note 5 - Tax expense (table_1)(header)(first set)" ||
                    $table_name == "Note 5 - Tax expense (table_2)(header)(not first set)" || $table_name == "Note 5 - Tax expense (table_2)(header)(first set)" || 
                    $table_name == "Note 7 - Investment in associates (table_1)(header)(not first set)" || $table_name == "Note 7 - Investment in associates (table_1)(header)(first set)" || 
                    $table_name == "Note 7 - Investment in associates (table_3)(header)(first set)" || $table_name == "Note 7 - Investment in associates (table_3)(header)(not first set)" || 
                    $table_name == "Note 8 - Investment in joint venture (table_2)(header)(first set)" || $table_name == "Note 8 - Investment in joint venture (table_2)(header)(not first set)" || 
                    $table_name == "Note 10 - Insured benefits (table_1)(header)(first set)" || $table_name == "Note 10 - Insured benefits (table_1)(header)(not first set)" ||
                    $table_name == "Note 11 - Investment properties (table_2)(header)(first set)" || $table_name == "Note 11 - Investment properties (table_2)(header)(not first set)" ||
                    $table_name == "Note 11 - Investment properties (table_5)(header)(first set)" || $table_name == "Note 11 - Investment properties (table_5)(header)(not first set)" ||
                    $table_name == "Note 13 - Available for sale (table_1)(header)(first set)" || $table_name == "Note 13 - Available for sale (table_1)(header)(not first set)" ||
                    $table_name == "Note 14 - Inventories (table_1)(header)(first set)" || $table_name == "Note 14 - Inventories (table_1)(header)(not first set)" ||
                    $table_name == "Note 15 - Contract assets and contract liabilities (table_1)(header)(first set)" || $table_name == "Note 15 - Contract assets and contract liabilities (table_1)(header)(not first set)" ||
                    $table_name == "Note 16 - Trade and other receivables (table_1)(header)(first set)" || $table_name == "Note 16 - Trade and other receivables (table_1)(header)(not first set)" ||
                    $table_name == "Note 16 - Trade and other receivables (table_2)(header)(not first set)" || $table_name == "Note 16 - Trade and other receivables (table_2)(header)(first set)" ||
                    $table_name == "Note 16 - Trade and other receivables (table_3)(header)(not first set)" || $table_name == "Note 16 - Trade and other receivables (table_3)(header)(first set)" ||
                    $table_name == "Note 16 - Trade and other receivables (table_4)(header)(first set)" || $table_name == "Note 16 - Trade and other receivables (table_4)(header)(not first set)" || 
                    $table_name == "Note 17 - Other current assets (table_1)(header)(first set)" || $table_name == "Note 17 - Other current assets (table_1)(header)(not first set)" ||
                    $table_name == "Note 18 - Cash and short-term deposits (table_1)(header)(first set)" || $table_name == "Note 18 - Cash and short-term deposits (table_1)(header)(not first set)" || 
                    $table_name == "Note 18 - Cash and short-term deposits (table_2)(header)(first set)" || $table_name == "Note 18 - Cash and short-term deposits (table_2)(header)(not first set)" ||
                    $table_name == "Note 18 - Cash and short-term deposits (table_3)(header)(first set)" || $table_name == "Note 18 - Cash and short-term deposits (table_3)(header)(not first set)" ||
                    $table_name == "Note 21 - Deferred tax liabilities (table_1)(header)(first set)" || $table_name == "Note 21 - Deferred tax liabilities (table_1)(header)(not first set)" ||
                    $table_name == "Note 22 - Loans and borrowings (table_1)(header)(first set)" || $table_name == "Note 22 - Loans and borrowings (table_1)(header)(not first set)" ||
                    $table_name == "Note 22 - Loans and borrowings (table_2)(header)" ||
                    $table_name == "Note 22 - Loans and borrowings (table_3)(header)(first set)" || $table_name == "Note 22 - Loans and borrowings (table_3)(header)(not first set)" ||
                    $table_name == "Note 22 - Loans and borrowings (table 4)(header)(first set)" || $table_name == "Note 22 - Loans and borrowings (table 4)(header)(not first set)" ||
                    $table_name == "Note 22 - Loans and borrowings (table_5)(header)(first set)" || $table_name == "Note 22 - Loans and borrowings (table_5)(header)(not first set)" ||
                    $table_name == "Note 23 - Provision (table_1)(header)(first set)" || $table_name == "Note 23 - Provision (table_1)(header)(not first set)" ||
                    $table_name == "Note 24 - Trade and other payables (table_1)(header)(first set)" || $table_name == "Note 24 - Trade and other payables (table_1)(header)(not first set)" ||
                    $table_name == "Note 24 - Trade and other payables (table_2)(header)(first set)" || $table_name == "Note 24 - Trade and other payables (table_2)(header)(not first set)" ||
                    $table_name == "Note 25 - Other current liabilities (table_1)(header)(first set)" || $table_name == "Note 25 - Other current liabilities (table_1)(header)(not first set)" ||
                    $table_name == "Note 26 - Related party transactions (table_1)(header)(first set)" || $table_name == "Note 26 - Related party transactions (table_1)(header)(not first set)" ||
                    $table_name == "Note 27 - Commitments (table_1)(header)(first set)" || $table_name == "Note 27 - Commitments (table_1)(header)(not first set)" || $table_name == "Note 27 - Commitments (table_2)(header)(first set)" ||
                    $table_name == "Note 27 - Commitments (table_2)(header)(not first set)" || $table_name == "Note 27 - Commitments (table_3)(header)(first set)" || $table_name == "Note 27 - Commitments (table_3)(header)(not first set)" || $table_name == "Note 27 - Commitments (table_4)(header)(first set)" || $table_name == "Note 27 - Commitments (table_4)(header)(not first set)" ||
                    $table_name == "Note 29.2 - Financial Risk Management (table_1)(header)(group)" || $table_name == "Note 29.2 - Financial Risk Management (table_1)(header)(company)" ||
                    $table_name == "Note 29.3 - Financial Risk Management (table_1)(header)(first set)" || $table_name == "Note 29.3 - Financial Risk Management (table_1)(header)(not first set)" || $table_name == "Note 29.3 - Financial Risk Management (table_2)(header)(first set)" || $table_name == "Note 29.3 - Financial Risk Management (table_2)(header)(not first set)" ||
                    $table_name == "Note 29.4 - Financial Risk Management (table_2)(header)(first set)" || $table_name == "Note 29.4 - Financial Risk Management (table_2)(header)(not first set)"
                )
                {
                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name);
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));

                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name); 

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                        if(!$is_hide_content)
                        {
                            if($table_name == "Note 29.3 - Financial Risk Management (table_1)(header)(first set)" || $table_name == "Note 29.3 - Financial Risk Management (table_1)(header)(not first set)")
                            {
                                $p = $this->db->query("SELECT * FROM fs_financial_risk_management_s3_info WHERE fs_company_info_id=" . $fs_company_info_id . " AND is_checked=1 AND main_section=2 AND sub_section=0");
                                $p = $p->result_array();

                                if(count($p) == 0)
                                {
                                    $is_hide_content = true;
                                }
                            }
                            elseif($table_name == "Note 29.3 - Financial Risk Management (table_2)(header)(first set)" || $table_name == "Note 29.3 - Financial Risk Management (table_2)(header)(not first set)")
                            {
                                $p = $this->db->query("SELECT * FROM fs_financial_risk_management_s3_info WHERE fs_company_info_id=" . $fs_company_info_id . " AND is_checked=1 AND main_section=3 AND sub_section=0");
                                $p = $p->result_array();

                                if(count($p) == 0)
                                {
                                    $is_hide_content = true;
                                }
                            }
                            elseif(
                                $table_name == "Note 16 - Trade and other receivables (table_3)(header)(not first set)" || $table_name == "Note 16 - Trade and other receivables (table_3)(header)(first set)" || 
                                $table_name == "Note 16 - Trade and other receivables (table_4)(header)(first set)" || $table_name == "Note 16 - Trade and other receivables (table_4)(header)(not first set)"
                            )
                            {
                                $p1 = $this->db->query("SELECT lb.*
                                                FROM fs_trade_and_other_receivables_info lb
                                                LEFT JOIN fs_list_trade_and_other_receivables_content lbd ON lbd.id = lb.fs_list_trade_and_other_receivables_content_id
                                                LEFT JOIN fs_list_trade_and_other_receivables_title lbt ON lbt.id = lbd.fs_list_trade_and_other_receivables_title_id
                                                WHERE lb.fs_company_info_id=" . $fs_company_info_id . " AND lbt.id = 1 AND lb.is_checked = 1 ORDER BY lb.order_by");
                                $p1 = $p1->result_array();

                                if(count($p1) > 0)
                                {
                                    $p2 = $this->db->query("SELECT lb.*
                                                    FROM fs_trade_and_other_receivables_info lb
                                                    LEFT JOIN fs_list_trade_and_other_receivables_content lbd ON lbd.id = lb.fs_list_trade_and_other_receivables_content_id
                                                    LEFT JOIN fs_list_trade_and_other_receivables_title lbt ON lbt.id = lbd.fs_list_trade_and_other_receivables_title_id
                                                    WHERE lb.fs_company_info_id=" . $fs_company_info_id . " AND lbt.id = 3 AND lb.is_checked = 1 ORDER BY lb.order_by");
                                    $p2 = $p2->result_array();

                                    if(count($p2) > 0)
                                    {
                                        $is_hide_content = false;
                                    }
                                    else
                                    {
                                        $is_hide_content = true;
                                    }
                                }
                                else
                                {
                                    $is_hide_content = true;
                                }
                            }
                        }

                        // remove or hide hidden text
                        if($additional_info['generate_docs_without_tags'] && $is_hide_content)
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            // $is_hide_content = true;
                            // $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name); 
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);  
                        }
                    }
                }
                elseif(
                    $table_name == "Note 5 - Tax expense (table_1) (not first set)" || $table_name == "Note 5 - Tax expense (table_1) (first set)" || 
                    $table_name == "Note 7 - Investment in associates (table_1) (not first set)" || $table_name == "Note 7 - Investment in associates (table_1) (first set)" || 
                    $table_name == "Note 10 - Insured benefits (table_1) (first set)" || $table_name == "Note 10 - Insured benefits (table_1) (not first set)" || 
                    $table_name == "Note 16 - Trade and other receivables (table_2) (not first set)" || $table_name == "Note 16 - Trade and other receivables (table_2) (first set)" || 
                    $table_name == "Note 16 - Trade and other receivables (table_3) (not first set)" || $table_name == "Note 16 - Trade and other receivables (table_3) (first set)" || 
                    $table_name == "Note 17 - Other current assets (table_1) (first set)" || $table_name == "Note 17 - Other current assets (table_1) (not first set)" || 
                    $table_name == "Note 18 - Cash and short-term deposits (table_1) (first set)" || $table_name == "Note 18 - Cash and short-term deposits (table_1) (not first set)" || 
                    $table_name == "Note 18 - Cash and short-term deposits (table_2) (first set)" || $table_name == "Note 18 - Cash and short-term deposits (table_2) (not first set)" || 
                    $table_name == "Note 21 - Deferred tax liabilities (table_1) (first set)" || $table_name == "Note 21 - Deferred tax liabilities (table_1) (not first set)" || 
                    $table_name == "Note 22 - Loans and borrowings (table_3) (first set)" || $table_name == "Note 22 - Loans and borrowings (table_3) (not first set)" || 
                    $table_name == "Note 23 - Provision (table_1) (first set)" || $table_name == "Note 23 - Provision (table_1) (not first set)" || 
                    $table_name == "Note 24 - Trade and other payables (table_2) (first set)" || $table_name == "Note 24 - Trade and other payables (table_2) (not first set)" || 
                    $table_name == "Note 25 - Other current liabilities (table_1) (first set)" || $table_name == "Note 25 - Other current liabilities (table_1) (not first set)" || 
                    $table_name == "Note 29.3 - Financial Risk Management (table_1) (first set)" || $table_name == "Note 29.3 - Financial Risk Management (table_1) (not first set)" || 
                    $table_name == "Note 29.3 - Financial Risk Management (table_2) (first set)" || $table_name == "Note 29.3 - Financial Risk Management (table_2) (not first set)" || 
                    $table_name == "Note 29.4 - Financial Risk Management (table_2) (first set)" || $table_name == "Note 29.4 - Financial Risk Management (table_2) (not first set)"
                )
                {
                    $replaced_tbl_template   = $replaced_tbl_value; 
                    $replaced_tbl_template_2 = $replaced_tbl_template;

                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name);
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));

                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name);

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                        if($is_hide_content)
                        {
                            if($additional_info['generate_docs_without_tags'])
                            {
                                $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                                $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                                $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                            }   
                            else
                            {
                                // $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                                $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1); 
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                            }
                        }
                        else
                        {
                            // for tables depend on checkbox
                            if($table_name == "Note 29.3 - Financial Risk Management (table_1) (first set)" || $table_name == "Note 29.3 - Financial Risk Management (table_1) (not first set)")
                            {
                                $p = $this->db->query("SELECT * FROM fs_financial_risk_management_s3_info WHERE fs_company_info_id=" . $fs_company_info_id . " AND is_checked=1 AND main_section=2 AND sub_section=0");
                                $p = $p->result_array();

                                if(count($p) == 0)
                                {
                                    $is_hide_content = true;
                                }
                            }
                            elseif($table_name == "Note 29.3 - Financial Risk Management (table_2) (first set)" || $table_name == "Note 29.3 - Financial Risk Management (table_2) (not first set)")
                            {
                                $p = $this->db->query("SELECT * FROM fs_financial_risk_management_s3_info WHERE fs_company_info_id=" . $fs_company_info_id . " AND is_checked=1 AND main_section=3 AND sub_section=0");
                                $p = $p->result_array();

                                if(count($p) == 0)
                                {
                                    $is_hide_content = true;
                                }
                            }
                            elseif($table_name == "Note 16 - Trade and other receivables (table_3) (not first set)" || $table_name == "Note 16 - Trade and other receivables (table_3) (first set)")
                            {
                                $p1 = $this->db->query("SELECT lb.*
                                                FROM fs_trade_and_other_receivables_info lb
                                                LEFT JOIN fs_list_trade_and_other_receivables_content lbd ON lbd.id = lb.fs_list_trade_and_other_receivables_content_id
                                                LEFT JOIN fs_list_trade_and_other_receivables_title lbt ON lbt.id = lbd.fs_list_trade_and_other_receivables_title_id
                                                WHERE lb.fs_company_info_id=" . $fs_company_info_id . " AND lbt.id = 1 AND lb.is_checked = 1 ORDER BY lb.order_by");
                                $p1 = $p1->result_array();

                                if(count($p1) > 0)
                                {
                                    $p2 = $this->db->query("SELECT lb.*
                                                    FROM fs_trade_and_other_receivables_info lb
                                                    LEFT JOIN fs_list_trade_and_other_receivables_content lbd ON lbd.id = lb.fs_list_trade_and_other_receivables_content_id
                                                    LEFT JOIN fs_list_trade_and_other_receivables_title lbt ON lbt.id = lbd.fs_list_trade_and_other_receivables_title_id
                                                    WHERE lb.fs_company_info_id=" . $fs_company_info_id . " AND lbt.id = 2 AND lb.is_checked = 1 ORDER BY lb.order_by");
                                    $p2 = $p2->result_array();

                                    if(count($p2) > 0)
                                    {
                                        $is_hide_content = false;
                                    }
                                    else
                                    {
                                        $is_hide_content = true;
                                    }
                                }
                                else
                                {
                                    $is_hide_content = true;
                                }
                            }

                            /* ---------- Collect templates ---------- */
                            $ori_all_template = [];

                            $newline_template            = '';
                            $description_tr_template     = '';
                            $last_line_desc_template     = '';
                            $overall_total_tr_template   = '';
                            $last_line_space_tr_template = '';

                            $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                            // get templates and remove all the info first
                            foreach ($tbl_tr[0] as $tbl_tr_key => $tbl_tr_value) 
                            {
                                $tr_name_type = $this->get_tr_name_type($tbl_tr_value);  // to extract the first column name so that we know it is title or account name and value insert template.

                                if($tr_name_type == "{New line}")
                                {
                                    array_push($ori_all_template, $tbl_tr_value);
                                    $newline_template = $this->vanish_template($tbl_tr_value, 0);
                                    $newline_template = $this->remove_wr_from_tr($newline_template, [1]);
                                }
                                elseif($tr_name_type == "{Title}")
                                {
                                    array_push($ori_all_template, $tbl_tr_value);
                                    $title_tr_template = $this->vanish_template($tbl_tr_value, 0);
                                }
                                elseif($tr_name_type == "{Description}")
                                {
                                    array_push($ori_all_template, $tbl_tr_value);
                                    $description_tr_template = $this->vanish_template($tbl_tr_value, 0);

                                    if($fs_company_info[0]['group_type'] == 1)
                                    {
                                        $description_tr_template = $this->remove_wr_from_tr($description_tr_template, $hide_column_data['all']);
                                    }
                                }
                                elseif($tr_name_type == "{Last line description}")
                                {
                                    array_push($ori_all_template, $tbl_tr_value);
                                    $last_line_desc_template = $this->vanish_template($tbl_tr_value, 0);

                                    if($fs_company_info[0]['group_type'] == 1)
                                    {
                                        $last_line_desc_template = $this->remove_wr_from_tr($last_line_desc_template, $hide_column_data['all']); 
                                    }
                                }
                                elseif($tr_name_type == "{Overall total}")
                                {
                                    array_push($ori_all_template, $tbl_tr_value);
                                    $overall_total_tr_template = $this->vanish_template($tbl_tr_value, 0);
                                    $overall_total_tr_template = $this->remove_wr_from_tr($overall_total_tr_template, [1]);

                                    if($fs_company_info[0]['group_type'] == 1)
                                    {
                                        $overall_total_tr_template = $this->remove_wr_from_tr($overall_total_tr_template, $hide_column_data['all']);
                                    }
                                }
                                elseif($tr_name_type == "{Last line space}")
                                {
                                    array_push($ori_all_template, $tbl_tr_value);
                                    $last_line_space_tr_template = $tbl_tr_value;   // for replace value later
                                }
                                elseif($tr_name_type == $table_name)
                                {
                                    array_push($ori_all_template, $tbl_tr_value);
                                }
                                elseif(!($tr_name_type == "Note 5 - Tax expense (table_1) (not first set)" || $tr_name_type == "Note 5 - Tax expense (table_1) (first set)" ||
                                        $tr_name_type == "Note 7 - Investment in associates (table_1) (not first set)" || $tr_name_type == "Note 7 - Investment in associates (table_1) (first set)" ||
                                        $tr_name_type == "Note 10 - Insured benefits (table_1) (first set)" || $tr_name_type == "Note 10 - Insured benefits (table_1) (not first set)" ||
                                        $tr_name_type == "Note 16 - Trade and other receivables (table_2) (not first set)" || $tr_name_type == "Note 16 - Trade and other receivables (table_2) (first set)" ||
                                        $tr_name_type == "Note 16 - Trade and other receivables (table_3) (not first set)" || $tr_name_type == "Note 16 - Trade and other receivables (table_3) (first set)" ||
                                        $tr_name_type == "Note 17 - Other current assets (table_1) (first set)" || $tr_name_type == "Note 17 - Other current assets (table_1) (not first set)" ||
                                        $tr_name_type == "Note 21 - Deferred tax liabilities (table_1) (first set)" || $tr_name_type == "Note 21 - Deferred tax liabilities (table_1) (not first set)" || 
                                        $tr_name_type == "Note 22 - Loans and borrowings (table_3) (first set)" || $tr_name_type == "Note 22 - Loans and borrowings (table_3) (not first set)" || 
                                        $tr_name_type == "Note 24 - Trade and other payables (table_2) (first set)" || $tr_name_type == "Note 24 - Trade and other payables (table_2) (not first set)" ||
                                        $tr_name_type == "Note 25 - Other current liabilities (table_1) (first set)" || $tr_name_type == "Note 25 - Other current liabilities (table_1) (not first set)"
                                    ))
                                {
                                    $replaced_tbl_template_2 = str_replace($tbl_tr_value, '', $replaced_tbl_template_2);
                                }
                            }
                            /* ---------- END OF Collect templates ---------- */

                            if($is_hide_content) // if checkbox value is 0, $is_hide_content is 0
                            {
                                if($additional_info['generate_docs_without_tags'])
                                {
                                    foreach ($ori_all_template as $at_key => $at_value) 
                                    {
                                        $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                    }
                                }
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_template_2, $tbl_value, $replaced_xml);
                            }
                            else
                            {
                                $tr_item = '';

                                /* set value on template */
                                $data = $this->get_t1_value_tr_template($table_name, $fs_company_info_id);  
                                $temp_data = $data[count($data)-1];

                                // remove last row for different template use later
                                if(
                                    $table_name == "Note 7 - Investment in associates (table_1) (not first set)" || $table_name == "Note 7 - Investment in associates (table_1) (first set)" || 
                                    $table_name == "Note 10 - Insured benefits (table_1) (first set)" || $table_name == "Note 10 - Insured benefits (table_1) (not first set)" || 
                                    $table_name == "Note 23 - Provision (table_1) (first set)" || $table_name == "Note 23 - Provision (table_1) (not first set)" || 
                                    $table_name == "Note 29.3 - Financial Risk Management (table_2) (first set)" || $table_name == "Note 29.3 - Financial Risk Management (table_2) (not first set)" 
                                )
                                {
                                    unset($data[count($data)-1]);
                                }

                                if(
                                    $table_name == "Note 29.3 - Financial Risk Management (table_1) (first set)" || $table_name == "Note 29.3 - Financial Risk Management (table_1) (not first set)" || 
                                    $table_name == "Note 29.3 - Financial Risk Management (table_2) (first set)" || $table_name == "Note 29.3 - Financial Risk Management (table_2) (not first set)" || 
                                    $table_name == "Note 29.4 - Financial Risk Management (table_2) (first set)" || $table_name == "Note 29.4 - Financial Risk Management (table_2) (not first set)"
                                ) 
                                // insert title row
                                {
                                    if($table_name == "Note 29.3 - Financial Risk Management (table_1) (first set)" || $table_name == "Note 29.3 - Financial Risk Management (table_1) (not first set)")
                                    {
                                        $input_value = array("Floating rate instruments");
                                    }
                                    elseif($table_name == "Note 29.3 - Financial Risk Management (table_2) (first set)" || $table_name == "Note 29.3 - Financial Risk Management (table_2) (not first set)")
                                    {
                                        $input_value = array("Fixed rate instruments");
                                    }
                                    elseif($table_name == "Note 29.4 - Financial Risk Management (table_2) (first set)" || $table_name == "Note 29.4 - Financial Risk Management (table_2) (not first set)")
                                    {
                                        $input_value = array("Increase/(Decrease) profit:");
                                    }

                                    $tr_item .= $this->replace_tr_template_item($title_tr_template, $input_value);
                                }
                                else
                                {
                                    // insert new line
                                    $tr_item .= $newline_template;
                                }

                                $overall_total = array(
                                                        'group_ty_value'   => 0,
                                                        'group_ly_value'   => 0,
                                                        'company_ty_value' => 0,
                                                        'company_ly_value' => 0
                                                    );

                                foreach ($data as $key => $value) 
                                {
                                    $input_value = [];

                                    if($fs_company_info[0]['first_set'])
                                    {
                                        $input_value = array(
                                                            $value['description'],
                                                            $this->fs_replace_content_model->negative_bracket($value['group_end_this_ye_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($value['value'])
                                                        );

                                        $overall_total['group_ty_value']    += $value['group_end_this_ye_value'];
                                        $overall_total['company_ty_value']  += $value['value'];
                                    }
                                    else
                                    {
                                        $input_value = array(
                                                            $value['description'],
                                                            $this->fs_replace_content_model->negative_bracket($value['group_end_this_ye_value']),
                                                            $this->fs_replace_content_model->negative_bracket($value['group_end_prev_ye_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($value['value']),
                                                            $this->fs_replace_content_model->negative_bracket($value['company_end_prev_ye_value'])
                                                        );

                                        $overall_total['group_ty_value']   += $value['group_end_this_ye_value'];
                                        $overall_total['group_ly_value']   += $value['group_end_prev_ye_value'];
                                        $overall_total['company_ty_value'] += $value['value'];
                                        $overall_total['company_ly_value'] += $value['company_end_prev_ye_value'];
                                    }

                                    $tr_item .= $this->replace_tr_template_item($description_tr_template, $input_value);
                                }

                                if(
                                    $table_name == "Note 7 - Investment in associates (table_1) (not first set)" || $table_name == "Note 7 - Investment in associates (table_1) (first set)" || 
                                    $table_name == "Note 10 - Insured benefits (table_1) (first set)" || $table_name == "Note 10 - Insured benefits (table_1) (not first set)" || 
                                    $table_name == "Note 23 - Provision (table_1) (first set)" || $table_name == "Note 23 - Provision (table_1) (not first set)" || 
                                    $table_name == "Note 29.3 - Financial Risk Management (table_2) (first set)" || $table_name == "Note 29.3 - Financial Risk Management (table_2) (not first set)" 
                                )
                                {
                                    if($fs_company_info[0]['first_set'])
                                    {
                                        $overall_array = array(
                                                            $temp_data['description'],
                                                            $this->fs_replace_content_model->negative_bracket($temp_data['group_end_this_ye_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($temp_data['value'])
                                                        );
                                    }
                                    else
                                    {
                                        $overall_array = array(
                                                            $temp_data['description'],
                                                            $this->fs_replace_content_model->negative_bracket($temp_data['group_end_this_ye_value']),
                                                            $this->fs_replace_content_model->negative_bracket($temp_data['group_end_prev_ye_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($temp_data['value']),
                                                            $this->fs_replace_content_model->negative_bracket($temp_data['company_end_prev_ye_value'])
                                                        );
                                    }

                                    $tr_item .= $this->replace_tr_template_item($last_line_desc_template, $overall_array);
                                }
                                elseif($table_name == "Note 29.3 - Financial Risk Management (table_1) (first set)" || $table_name == "Note 29.3 - Financial Risk Management (table_1) (not first set)") 
                                // show subtotal with description
                                {
                                    if($fs_company_info[0]['first_set'])
                                    {
                                        $overall_array = array(
                                                            'Net exposure',
                                                            $this->fs_replace_content_model->negative_bracket($overall_total['group_ty_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($overall_total['company_ty_value'])
                                                        );
                                    }
                                    else
                                    {
                                        $overall_array = array(
                                                            'Net exposure',
                                                            $this->fs_replace_content_model->negative_bracket($overall_total['group_ty_value']),
                                                            $this->fs_replace_content_model->negative_bracket($overall_total['group_ly_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($overall_total['company_ty_value']),
                                                            $this->fs_replace_content_model->negative_bracket($overall_total['company_ly_value'])
                                                        );
                                    }

                                    $tr_item .= $this->replace_tr_template_item($last_line_desc_template, $overall_array);
                                }
                                else
                                {
                                    if($fs_company_info[0]['first_set'])
                                    {
                                        $overall_array = array(
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($overall_total['group_ty_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($overall_total['company_ty_value'])
                                                        );
                                    }
                                    else
                                    {
                                        $overall_array = array(
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($overall_total['group_ty_value']),
                                                            $this->fs_replace_content_model->negative_bracket($overall_total['group_ly_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($overall_total['company_ty_value']),
                                                            $this->fs_replace_content_model->negative_bracket($overall_total['company_ly_value'])
                                                        );
                                    }

                                    $tr_item .= $this->replace_tr_template_item($overall_total_tr_template, $overall_array);
                                }
                                /* END OF set value on template */

                                $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item . $newline_template, $replaced_tbl_template_2);
                                // remove hidden templates
                                if($additional_info['generate_docs_without_tags'])
                                {
                                    foreach ($ori_all_template as $at_key => $at_value) 
                                    {
                                        $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                    }
                                }
                                $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                            }
                        }    
                    }
                }
                elseif(
                    $table_name == "Note 5 - Tax expense (table_2) (not first set)" || $table_name == "Note 5 - Tax expense (table_2) (first set)" 
                    // || $table_name == "Note 5 - Tax expense (table_3) (not first set)" || $table_name == "Note 5 - Tax expense (table_3) (first set)"
                )
                {
                    $replaced_tbl_template   = $replaced_tbl_value; 
                    $replaced_tbl_template_2 = $replaced_tbl_template;

                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name);
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));

                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name);

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                        if($is_hide_content)
                        {
                            if($additional_info['generate_docs_without_tags'])
                            {
                                $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                                $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                                $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                            }   
                            else
                            {
                                // $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                            }
                        }
                        else
                        {
                            $newline_template            = '';
                            $description_tr_template     = '';
                            $last_line_space_tr_template = '';

                            $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                            // get templates and remove all the info first
                            $extra_info = array(
                                'hide_group_column'       => $hide_column_data['all'],
                                'template_num_of_line'    => $hide_column_data['clear_tr_after_row'],
                                'replaced_tbl_template_2' => $replaced_tbl_template_2,
                                'group_type'              => $fs_company_info[0]['group_type'],
                                'table_name'              => $table_name
                            );

                            $tr_template_data = $this->build_tbl_template_tr_data($tbl_tr, $extra_info);

                            $replaced_tbl_template_2     = $tr_template_data['replaced_tbl_template_2'];
                            $description_tr_template     = $tr_template_data['description'];
                            $last_line_space_tr_template = $tr_template_data['last_line_space'];
                            $newline_template            = $tr_template_data['newline'];

                            // set value to template
                            $tr_item = '';

                            $data = $this->get_t1_value_tr_template($table_name, $fs_company_info_id); 

                            $total = array(
                                        'group_cy'   => 0,
                                        'group_ly'   => 0,
                                        'company_cy' => 0,
                                        'company_ly' => 0
                                    );

                            // for P/L before tax
                            $pl_b4_data = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_company_info_id=" . $fs_company_info_id . " AND fs_list_state_comp_income_section_id=3 AND in_use=1");
                            $pl_b4_data = $pl_b4_data->result_array();

                            // print_r($pl_b4_data);

                            if(count($pl_b4_data) > 0)
                            {
                                $tr_item .= $newline_template;

                                $display_data = [];

                                if($fs_company_info[0]['first_set'])
                                {
                                    $display_data = array(
                                                        $pl_b4_data[0]['description'],
                                                        $this->fs_replace_content_model->negative_bracket($pl_b4_data[0]['value_group_ye']),
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket($pl_b4_data[0]['value_company_ye'])
                                                    );
                                }
                                else
                                {
                                    $display_data = array(
                                                        $pl_b4_data[0]['description'],
                                                        $this->fs_replace_content_model->negative_bracket($pl_b4_data[0]['value_group_ye']),
                                                        $this->fs_replace_content_model->negative_bracket($pl_b4_data[0]['value_group_lye_end']),
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket($pl_b4_data[0]['value_company_ye']),
                                                        $this->fs_replace_content_model->negative_bracket($pl_b4_data[0]['value_company_lye_end'])
                                                    );
                                }

                                $tr_item .= $this->replace_tr_template_item($tr_template_data['last_line_desc'], $display_data);
                            }

                            // data from table "fs_tax_expense_reconciliation"
                            $tr_item .= $newline_template;

                            foreach ($data as $key => $value) 
                            {
                                $show_content = $this->verify_line_no_value(array($value['group_end_this_ye_value'], $value['group_end_prev_ye_value'], $value['value'], $value['company_end_prev_ye_value']), $fs_company_info);

                                if($show_content) 
                                {
                                    $display_data = [];

                                    // overall_total_desc
                                    if($fs_company_info[0]['first_set'])
                                    {
                                        $display_data = array(
                                                            $value['description'],
                                                            $this->fs_replace_content_model->negative_bracket($value['group_end_this_ye_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($value['value'])
                                                        );
                                    }
                                    else
                                    {
                                        $display_data = array(
                                                            $value['description'],
                                                            $this->fs_replace_content_model->negative_bracket($value['group_end_this_ye_value']),
                                                            $this->fs_replace_content_model->negative_bracket($value['group_end_prev_ye_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($value['value']),
                                                            $this->fs_replace_content_model->negative_bracket($value['company_end_prev_ye_value'])
                                                        );
                                    }

                                    $total['group_cy']   += $value['group_end_this_ye_value'];
                                    $total['group_ly']   += $value['group_end_prev_ye_value'];
                                    $total['company_cy'] += $value['value'];
                                    $total['company_ly'] += $value['company_end_prev_ye_value'];

                                    $tr_item .= $this->replace_tr_template_item($description_tr_template, $display_data);
                                }
                            }

                            // overall_total_desc
                            $display_data = [];

                            if($fs_company_info[0]['first_set'])
                            {
                                $display_data = array(
                                                    "Current tax expense",
                                                    $this->fs_replace_content_model->negative_bracket($total['group_cy']),
                                                    '',
                                                    $this->fs_replace_content_model->negative_bracket($total['company_cy'])
                                                );
                            }
                            else
                            {
                                $display_data = array(
                                                    "Current tax expense",
                                                    $this->fs_replace_content_model->negative_bracket($total['group_cy']),
                                                    $this->fs_replace_content_model->negative_bracket($total['group_ly']),
                                                    '',
                                                    $this->fs_replace_content_model->negative_bracket($total['company_cy']),
                                                    $this->fs_replace_content_model->negative_bracket($total['company_ly'])
                                                );
                            }
                            $tr_item .= $this->replace_tr_template_item($tr_template_data['overall_total_desc'], $display_data);


                            $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item . $newline_template, $replaced_tbl_template_2);
                            if($additional_info['generate_docs_without_tags'])
                            {
                                foreach ($tr_template_data['ori_all_template'] as $at_key => $at_value) 
                                {
                                    $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                }
                            }
                            $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                        }
                    }
                }
                elseif($table_name == "Note 6 - Investment in subsidiaries (table_1) (first set)" || $table_name == "Note 6 - Investment in subsidiaries (table_1) (not first set)")
                {
                    $replaced_tbl_template   = $replaced_tbl_value; 
                    $replaced_tbl_template_2 = $replaced_tbl_template;
                    
                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name);
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));

                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name); 

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                        if($is_hide_content)
                        {
                            if($additional_info['generate_docs_without_tags'])
                            {
                                $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                                $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                                $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                            }   
                            else
                            {
                                $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                            }
                        }
                        else
                        {
                            $newline_template            = '';
                            $description_tr_template     = '';
                            $last_line_space_tr_template = '';

                            $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                            // get templates and remove all the info first
                            $extra_info = array(
                                'hide_group_column'       => $hide_column_data['all'],
                                'template_num_of_line'    => 7,
                                'replaced_tbl_template_2' => $replaced_tbl_template_2,
                                'group_type'              => $fs_company_info[0]['group_type'],
                                'table_name'              => $table_name
                            );

                            $tr_template_data = $this->build_tbl_template_tr_data($tbl_tr, $extra_info);

                            $replaced_tbl_template_2     = $tr_template_data['replaced_tbl_template_2'];
                            $description_tr_template     = $tr_template_data['description'];
                            $last_line_space_tr_template = $tr_template_data['last_line_space'];

                            // set value to template
                            $tr_item = '';
                            $iis = $this->fs_notes_model->get_tbl_name_leftjoin_tbl_list($fs_company_info_id, "fs_investment_in_subsidiaries_ntfs", "fs_list_investment_in_subsidiaries");

                            // print_r($iis);

                            $overall_total = array(
                                                    'value_ty' => 0,
                                                    'value_ly' => 0
                                                );

                            $tr_item .= $newline_template;

                            foreach ($iis as $iis_key => $iis_value) 
                            {
                                $input_value = [];

                                if(!(empty($iis_value['value']) && empty($iis_value['company_end_prev_ye_value'])))
                                {
                                    if($fs_company_info[0]['first_set'])
                                    {
                                        $input_value = array(
                                                            $iis_value['description'],
                                                            '',
                                                            '',
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($iis_value['value'])
                                                        );

                                        $overall_total['value_ty'] += $iis_value['value'];
                                    }
                                    else
                                    {
                                        $input_value = array(
                                                            $iis_value['description'],
                                                            '',
                                                            '',
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($iis_value['value']),
                                                            $this->fs_replace_content_model->negative_bracket($iis_value['company_end_prev_ye_value']),
                                                        );

                                        $overall_total['value_ty']   += $iis_value['value'];
                                        $overall_total['value_ly']   += $iis_value['company_end_prev_ye_value'];
                                    }

                                    $tr_item .= $this->replace_tr_template_item($description_tr_template, $input_value);
                                }
                            }

                            if(count($iis) > 0)
                            {
                                if($fs_company_info[0]['first_set'])
                                {
                                    $overall_array = array(
                                                        '',
                                                        '',
                                                        '',
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket($overall_total['value_ty'])
                                                    );
                                }
                                else
                                {
                                    $overall_array = array(
                                                        '',
                                                        '',
                                                        '',
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket($overall_total['value_ty']),
                                                        $this->fs_replace_content_model->negative_bracket($overall_total['value_ly'])
                                                    );
                                }

                                $tr_item .= $this->replace_tr_template_item($tr_template_data['overall_total'], $overall_array);
                            }

                            $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item . $tr_template_data['newline'], $replaced_tbl_template_2);
                            $replaced_tbl_template_2 = $this->hide_tbl_column($fs_company_info, $hide_column_data, $replaced_tbl_template_2, $replaced_tbl_template_2);     // hide company title
                            if($additional_info['generate_docs_without_tags'])
                            {
                                foreach ($tr_template_data['ori_all_template'] as $at_key => $at_value) 
                                {
                                    $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                }
                            }
                            $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                        }
                    }
                }
                elseif(
                    $table_name == "Note 6 - Investment in subsidiaries (i) (table_1)(Small FRS)" || 
                    $table_name == "Note 7 - Investment in associates (table_2)" || 
                    $table_name == "Note 8 - Investment in joint venture (table_1)" || 
                    $table_name == "Note 11 - Investment properties (table_4)"
                )
                {
                    $replaced_tbl_template   = $replaced_tbl_value; 
                    $replaced_tbl_template_2 = $replaced_tbl_template;
                    
                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name);
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));
                    
                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name); 

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                        if($is_hide_content)
                        {
                            if($additional_info['generate_docs_without_tags'])
                            {
                                $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                                $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                                $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                            }   
                            else
                            {
                                $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                            }
                        }
                        else
                        {
                            $description_tr_template     = '';
                            $overall_total_tr_template   = '';
                            $last_line_space_tr_template = '';

                            $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                            // get templates and remove all the info first
                            $extra_info = array(
                                'hide_group_column'       => $hide_column_data['all'],
                                'template_num_of_line'    => 6,
                                'replaced_tbl_template_2' => $replaced_tbl_template_2,
                                'group_type'              => $fs_company_info[0]['group_type'],
                                'table_name'              => $table_name
                            );

                            $tr_template_data = $this->build_tbl_template_tr_data($tbl_tr, $extra_info);

                            $replaced_tbl_template_2     = $tr_template_data['replaced_tbl_template_2'];
                            $description_tr_template     = $tr_template_data['description'];
                            $last_line_space_tr_template = $tr_template_data['last_line_space'];

                            // set value in template
                            $tr_item = '';
                            // $tr_item .= $tr_template_data['newline'];

                            $data = $this->get_t1_value_tr_template($table_name, $fs_company_info_id);

                            // if($table_name == "Note 11 - Investment properties (table_4)")
                            // {
                            //     print_r($data);
                            // }

                            foreach ($data as $key => $value) 
                            {
                                $input_value_arr = [];

                                if($table_name == "Note 11 - Investment properties (table_4)")
                                {
                                    $input_value_arr = array(
                                                        $value['description_and_location'],
                                                        $value['existing_use'],
                                                        $value['tenure'],
                                                        $value['unexpired_lease_term']
                                                    );

                                    // print_r($input_value_arr);
                                }
                                else
                                {
                                    $input_value_arr = array(
                                                        $value['name_of_entity'],
                                                        $value['nicename'],
                                                        $value['principal_activities'],
                                                        $value['interest_val_1'],
                                                        $value['interest_val_2']
                                                    );
                                }

                                if($key+1 != count($data))
                                {
                                    $tr_item .= $this->replace_tr_template_item($description_tr_template, $input_value_arr);
                                }
                                else
                                {
                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['last_item_tr'], $input_value_arr);
                                }
                            }

                            $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item . $tr_template_data['newline'], $replaced_tbl_template_2);
                            if($additional_info['generate_docs_without_tags'])
                            {
                                foreach ($tr_template_data['ori_all_template'] as $at_key => $at_value) 
                                {
                                    $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                }
                            }
                            $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                        }
                    }
                }
                // elseif($table_name == "Note 6 - Investment in subsidiaries (ii) (table_1)")
                // {
                //     $is_hide_content = false;

                //     $hide_column_data = $this->get_table_setting($table_name);
                //     $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name); 

                //     if($hide_table)  // hide content
                //     {
                //         $is_hide_content = true;
                //         $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                //     }
                //     else // show content
                //     {
                //         $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                //         if($is_hide_content)
                //         {
                //             $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                //             $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                //         }
                //         else
                //         {
                //             /* set value on template */
                //             $header_data = $this->get_header_value_col_template($table_name, $fs_company_info_id);
                //             $body_data = $this->get_t1_value_tr_template($table_name, $fs_company_info_id); 

                //             $col_data = array(
                //                             'fs_company_info_id'       => $fs_company_info[0]['id'],
                //                             'table_name'               => $table_name,
                //                             'header_data'              => $header_data,
                //                             'body_data'                => $body_data,
                //                             'clear_tr_after_row'       => 0,
                //                             'max_col_num_for_portrait' => 0
                //                         );

                //             // if($table_name == "Note 9 - Intangible assets (table_1)")
                //             // {
                //             //     $col_data['clear_tr_after_row']       = 4;
                //             //     $col_data['max_col_num_for_portrait'] = 4;
                //             // }
                //             // elseif($table_name == "Note 11 - Investment properties (table_3)")
                //             // {
                //             //     $col_data['clear_tr_after_row']       = 8;
                //             //     $col_data['max_col_num_for_portrait'] = 3;
                //             // }
                //             // elseif($table_name == "Note 12 - Property, plant and equipment (table_1)" || $table_name == "Note 11 - Investment properties cost_model (table_1)")
                //             // {
                //             //     $col_data['clear_tr_after_row']       = 9;
                //             //     $col_data['max_col_num_for_portrait'] = 3;
                //             // }

                //             $replaced_tbl_template_2 = $this->modify_tbl_dynamic_col_landscape_portrait($replaced_tbl_template, $col_data); 
                //             $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                //         }
                //     }
                // }
                elseif($table_name == "Note 6 - Investment in subsidiaries (ii) (table_1)" || $table_name == "Note 9 - Intangible assets (table_1)" || $table_name == "Note 11 - Investment properties (table_3)" || $table_name == "Note 12 - Property, plant and equipment (table_1)" || $table_name == "Note 11 - Investment properties cost_model (table_1)" || $table_name == "Note 29.4 - Financial Risk Management (table_1) (group)" || $table_name == "Note 29.4 - Financial Risk Management (table_1) (company)")
                {
                    $replaced_tbl_template   = $replaced_tbl_value;
                    $replaced_tbl_template_2 = $replaced_tbl_template;

                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name); 
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));

                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name); 

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml    = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                        if($is_hide_content)
                        {
                            if($additional_info['generate_docs_without_tags'])
                            {
                                $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                                $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                                $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                            }   
                            else
                            {
                                $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                            }
                        }
                        else
                        {
                            /* set value on template */
                            $header_data = $this->get_header_value_col_template($table_name, $fs_company_info_id);
                            $body_data = $this->get_t1_value_tr_template($table_name, $fs_company_info_id); 

                            $col_data = array(
                                            'fs_company_info_id'       => $fs_company_info[0]['id'],
                                            'table_name'               => $table_name,
                                            'header_data'              => $header_data,
                                            'body_data'                => $body_data,
                                            'clear_tr_after_row'       => 0,
                                            'max_col_num_for_portrait' => 0,
                                            'generate_docs_without_tags' => $additional_info['generate_docs_without_tags']
                                        );

                            if($table_name == "Note 6 - Investment in subsidiaries (ii) (table_1)")
                            {
                                $col_data['clear_tr_after_row']       = 7;
                                $col_data['max_col_num_for_portrait'] = 4;
                            }
                            elseif($table_name == "Note 9 - Intangible assets (table_1)")
                            {
                                $col_data['clear_tr_after_row']       = 10;
                                $col_data['max_col_num_for_portrait'] = 4;
                            }
                            elseif($table_name == "Note 11 - Investment properties (table_3)")
                            {
                                $col_data['clear_tr_after_row']       = 8;
                                $col_data['max_col_num_for_portrait'] = 3;
                            }
                            elseif($table_name == "Note 12 - Property, plant and equipment (table_1)" || $table_name == "Note 11 - Investment properties cost_model (table_1)")
                            {
                                $col_data['clear_tr_after_row']       = 9;
                                $col_data['max_col_num_for_portrait'] = 3;
                            }
                            elseif($table_name == "Note 29.4 - Financial Risk Management (table_1) (group)" || $table_name == "Note 29.4 - Financial Risk Management (table_1) (company)")
                            {
                                $col_data['clear_tr_after_row']       = 10;
                                $col_data['max_col_num_for_portrait'] = 3;
                                // $col_data['keep_line']                = [10];
                                $col_data['fs_company_info_id']       = $fs_company_info_id;
                            }

                            $replaced_tbl_template_2 = $this->modify_tbl_dynamic_col_landscape_portrait($replaced_tbl_template, $col_data);  
                            if($additional_info['generate_docs_without_tags'])
                            {
                                foreach ($tr_template_data['ori_all_template'] as $at_key => $at_value) 
                                {
                                    $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                }
                            }
                            $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                        }
                    }
                }
                elseif($table_name == "Note 11 - Investment properties (table_5) (first set)" || $table_name == "Note 11 - Investment properties (table_5) (not first set)")
                {
                    $replaced_tbl_template   = $replaced_tbl_value; 
                    $replaced_tbl_template_2 = $replaced_tbl_template;
                    
                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name);
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));

                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name); 

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                        if($is_hide_content)
                        {
                            if($additional_info['generate_docs_without_tags'])
                            {
                                $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                                $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                                $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                            }   
                            else
                            {
                                $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                            }
                        }
                        else
                        {
                            $description_tr_template     = '';
                            $overall_total_tr_template   = '';
                            $last_line_space_tr_template = '';

                            $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                            // get templates and remove all the info first
                            $extra_info = array(
                                            'hide_group_column'       => $hide_column_data['all'],
                                            'template_num_of_line'    => $hide_column_data['clear_tr_after_row'],
                                            'replaced_tbl_template_2' => $replaced_tbl_template_2,
                                            'group_type'              => $fs_company_info[0]['group_type'],
                                            'table_name'              => $table_name
                                        );

                            $tr_template_data = $this->build_tbl_template_tr_data($tbl_tr, $extra_info); 

                            $replaced_tbl_template_2     = $tr_template_data['replaced_tbl_template_2'];
                            $last_line_space_tr_template = $tr_template_data['last_line_space'];

                            // set value to template
                            $tr_item = '';

                            $data = $this->get_t1_value_tr_template($table_name, $fs_company_info_id);

                            if(count($data) > 0)
                            {
                                $tr_item .= $tr_template_data['newline'];
                            }

                            foreach ($data as $ip_t5_key => $ip_t5_value) 
                            {
                                $input_value = [];

                                if($fs_company_info[0]['first_set'])
                                {
                                    $input_value = array(
                                                        $ip_t5_value['description'],
                                                        $ip_t5_value['group_end_this_ye_value'],
                                                        '',
                                                        $ip_t5_value['value']
                                                    );
                                }
                                else
                                {
                                    $input_value = array(
                                                        $ip_t5_value['description'],
                                                        $ip_t5_value['group_end_this_ye_value'],
                                                        $ip_t5_value['group_end_prev_ye_value'],
                                                        '',
                                                        $ip_t5_value['value'],
                                                        $ip_t5_value['company_end_prev_ye_value'],
                                                    );
                                }

                                if(count($data) == ($ip_t5_key + 1))
                                {
                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['last_line_desc'], $input_value);
                                }
                                elseif($ip_t5_value['title_item'] == "Title")
                                {
                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['title_normal'], $input_value);
                                }
                                else
                                {
                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['description'], $input_value);
                                }
                            }

                            $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item . $tr_template_data['newline'], $replaced_tbl_template_2);
                            if($additional_info['generate_docs_without_tags'])
                            {
                                foreach ($tr_template_data['ori_all_template'] as $at_key => $at_value) 
                                {
                                    $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                }
                            }
                            $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                        }
                    }
                }
                elseif($table_name == "Note 13 - Available for sale (table_1) (first set)" || $table_name == "Note 13 - Available for sale (table_1) (not first set)")
                {
                    $replaced_tbl_template   = $replaced_tbl_value; 
                    $replaced_tbl_template_2 = $replaced_tbl_template;
                    
                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name);
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));

                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name); 

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                        if($is_hide_content)
                        {
                            if($additional_info['generate_docs_without_tags'])
                            {
                                $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                                $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                                $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                            }   
                            else
                            {
                                $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                            }
                        }
                        else
                        {
                            $description_tr_template     = '';
                            $overall_total_tr_template   = '';
                            $last_line_space_tr_template = '';

                            $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                            // get templates and remove all the info first
                            $extra_info = array(
                                            'hide_group_column'       => $hide_column_data['all'],
                                            'template_num_of_line'    => $hide_column_data['clear_tr_after_row'],
                                            'replaced_tbl_template_2' => $replaced_tbl_template_2,
                                            'group_type'              => $fs_company_info[0]['group_type'],
                                            'table_name'              => $table_name
                                        );

                            $tr_template_data = $this->build_tbl_template_tr_data($tbl_tr, $extra_info);  

                            $replaced_tbl_template_2     = $tr_template_data['replaced_tbl_template_2'];
                            $last_line_space_tr_template = $tr_template_data['last_line_space'];

                            // set value to template
                            $tr_item = '';
                            $overall = array(
                                            'group_cy' => 0,
                                            'group_ly' => 0,
                                            'company_cy' => 0,
                                            'company_ly' => 0
                                        );
                            $total_p1 = $overall;
                            $total_p2 = $overall;

                            $data = $this->get_t1_value_tr_template($table_name, $fs_company_info_id);

                            $data_p1 = [];
                            $data_p2 = [];

                            foreach ($data as $key => $value) 
                            {
                                if($value['part'] == 1)
                                {
                                    array_push($data_p1, $value);
                                }
                                elseif($value['part'] == 2)
                                {
                                    array_push($data_p2, $value);
                                }
                            }

                            $tr_item .= $this->replace_tr_template_item($tr_template_data['description_underline'], array('Quoted equity investment at fair value'));

                            // $total_p1_group_cy     = 0;
                            // $total_p1_group_ly     = 0;
                            // $total_p1_company_cy   = 0;
                            // $total_p1_company_ly   = 0;



                            // display first part
                            foreach ($data_p1 as $key_p1 => $value_p1) 
                            {
                                if($fs_company_info[0]['first_set'])
                                {
                                    $input_value = array(
                                                        $value_p1['description'],
                                                        $this->fs_replace_content_model->negative_bracket($value_p1['group_end_this_ye_value']),
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket($value_p1['value'])
                                                    );

                                    $total_p1['group_cy']   += (int)$value_p1['group_end_this_ye_value'];
                                    $total_p1['company_cy'] += (int)$value_p1['value'];

                                    // $total_p1_group_cy     += $value_p1['group_end_this_ye_value'];
                                    // $total_p1_company_cy   += $value_p1['value'];
                                }
                                else
                                {
                                    $input_value = array(
                                                        $value_p1['description'],
                                                        $this->fs_replace_content_model->negative_bracket($value_p1['group_end_this_ye_value']),
                                                        $this->fs_replace_content_model->negative_bracket($value_p1['group_end_prev_ye_value']),
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket($value_p1['value']),
                                                        $this->fs_replace_content_model->negative_bracket($value_p1['company_end_prev_ye_value'])
                                                    );

                                    $total_p1['group_cy']   += (int)$value_p1['group_end_this_ye_value'];
                                    $total_p1['group_ly']   += (int)$value_p1['group_end_prev_ye_value'];
                                    $total_p1['company_cy'] += (int)$value_p1['value'];
                                    $total_p1['company_ly'] += (int)$value_p1['company_end_prev_ye_value'];
                                }
                                $tr_item .= $this->replace_tr_template_item($tr_template_data['description'], $input_value);
                            }

                            // subtotal (part 1)
                            $input_value = array(
                                                'Balance c/f',
                                                $this->fs_replace_content_model->negative_bracket($total_p1['group_cy']),
                                                $this->fs_replace_content_model->negative_bracket($total_p1['group_ly']),
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_p1['company_cy']),
                                                $this->fs_replace_content_model->negative_bracket($total_p1['company_ly'])
                                            );

                            // remove last year values if report is first set
                            if($fs_company_info[0]['first_set'])
                            {
                                unset($input_value[2]);
                                unset($input_value[5]);

                                $input_value = array_values($input_value); // rearrange array
                            }

                            $tr_item .= $this->replace_tr_template_item($tr_template_data['last_line_desc'], $input_value);

                            $tr_item .= $tr_template_data['newline'];
                            $tr_item .= $this->replace_tr_template_item($tr_template_data['description_underline'], array('Unquoted equity investment at cost'));

                            // $total_p2_group_cy     = 0;
                            // $total_p2_group_ly     = 0;
                            // $total_p2_company_cy   = 0;
                            // $total_p2_company_ly   = 0;

                            // display second part
                            foreach ($data_p2 as $key_p1 => $value_2) 
                            {
                                if($fs_company_info[0]['first_set'])
                                {
                                    $input_value = array(
                                                        $value_2['description'],
                                                        $this->fs_replace_content_model->negative_bracket($value_2['group_end_this_ye_value']),
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket($value_2['value'])
                                                    );

                                    // $total_p2_group_cy     += $value_2['group_end_this_ye_value'];
                                    // $total_p2_company_cy   += $value_2['value'];

                                    $total_p2['group_cy']   += (int)$value_2['group_end_this_ye_value'];
                                    $total_p2['company_cy'] += (int)$value_2['value'];
                                }
                                else
                                {
                                    $input_value = array(
                                                        $value_2['description'],
                                                        $this->fs_replace_content_model->negative_bracket($value_2['group_end_this_ye_value']),
                                                        $this->fs_replace_content_model->negative_bracket($value_2['group_end_prev_ye_value']),
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket($value_2['value']),
                                                        $this->fs_replace_content_model->negative_bracket($value_2['company_end_prev_ye_value'])
                                                    );

                                    $total_p2['group_cy']   += (int)$value_2['group_end_this_ye_value'];
                                    $total_p2['group_ly']   += (int)$value_2['group_end_prev_ye_value'];
                                    $total_p2['company_cy'] += (int)$value_2['value'];
                                    $total_p2['company_ly'] += (int)$value_2['company_end_prev_ye_value'];

                                    // $total_p2_group_cy     += $value_2['group_end_this_ye_value'];
                                    // $total_p2_group_ly     += $value_2['group_end_prev_ye_value'];
                                    // $total_p2_company_cy   += $value_2['value'];
                                    // $total_p2_company_ly   += $value_2['company_end_prev_ye_value'];
                                }
                                $tr_item .= $this->replace_tr_template_item($tr_template_data['description'], $input_value);
                            }

                            // subtotal (part 2)
                            $input_value = array(
                                                'Balance c/f',
                                                $this->fs_replace_content_model->negative_bracket($total_p2['group_cy']),
                                                $this->fs_replace_content_model->negative_bracket($total_p2['group_ly']),
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_p2['company_cy']),
                                                $this->fs_replace_content_model->negative_bracket($total_p2['company_ly'])
                                            );

                            // remove last year values if report is first set
                            if($fs_company_info[0]['first_set'])
                            {
                                unset($input_value[2]);
                                unset($input_value[5]);

                                $input_value = array_values($input_value); // rearrange array
                            }

                            $tr_item .= $this->replace_tr_template_item($tr_template_data['last_line_desc'], $input_value);

                            // overall total
                            $input_value = array(
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_p1['group_cy'] + $total_p2['group_cy']),
                                                $this->fs_replace_content_model->negative_bracket($total_p1['group_cy'] + $total_p2['group_ly']),
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_p1['group_cy'] + $total_p2['company_cy']),
                                                $this->fs_replace_content_model->negative_bracket($total_p1['group_cy'] + $total_p2['company_ly'])
                                            );
                            
                            // remove last year values if report is first set
                            if($fs_company_info[0]['first_set'])
                            {
                                unset($input_value[2]);
                                unset($input_value[5]);

                                $input_value = array_values($input_value); // rearrange array
                            }

                            $tr_item .= $this->replace_tr_template_item($tr_template_data['overall_total'], $input_value);

                            $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item . $tr_template_data['newline'], $replaced_tbl_template_2);
                            if($additional_info['generate_docs_without_tags'])
                            {
                                foreach ($tr_template_data['ori_all_template'] as $at_key => $at_value) 
                                {
                                    $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                }
                            }
                            $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                        }
                    }
                }
                elseif($table_name == "Note 14 - Inventories (table_1) (first set)" || $table_name == "Note 14 - Inventories (table_1) (not first set)")
                {
                    $replaced_tbl_template   = $replaced_tbl_value; 
                    $replaced_tbl_template_2 = $replaced_tbl_template;
                    
                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name);
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));

                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name); 

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                        if($is_hide_content)
                        {
                            if($additional_info['generate_docs_without_tags'])
                            {
                                $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                                $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                                $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                            }   
                            else
                            {
                                $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                            }
                        }
                        else
                        {
                            $description_tr_template     = '';
                            $overall_total_tr_template   = '';
                            $last_line_space_tr_template = '';

                            $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                            // get templates and remove all the info first
                            $extra_info = array(
                                            'hide_group_column'       => $hide_column_data['all'],
                                            'template_num_of_line'    => $hide_column_data['clear_tr_after_row'],
                                            'replaced_tbl_template_2' => $replaced_tbl_template_2,
                                            'group_type'              => $fs_company_info[0]['group_type'],
                                            'table_name'              => $table_name
                                        );

                            $tr_template_data = $this->build_tbl_template_tr_data($tbl_tr, $extra_info); 

                            $replaced_tbl_template_2     = $tr_template_data['replaced_tbl_template_2'];
                            $last_line_space_tr_template = $tr_template_data['last_line_space'];

                            // set value to template
                            $tr_item = '';

                            $data = $this->get_t1_value_tr_template($table_name, $fs_company_info_id);

                            if($fs_company_info[0]['first_set'])
                            {
                                $input_value = array(
                                                    $data[0]['description'],
                                                    $this->fs_replace_content_model->negative_bracket($data[0]['group_end_this_ye_value']),
                                                    '',
                                                    $this->fs_replace_content_model->negative_bracket($data[0]['value'])
                                                );
                            }
                            else
                            {
                                $input_value = array(
                                                    $data[0]['description'],
                                                    $this->fs_replace_content_model->negative_bracket($data[0]['group_end_this_ye_value']),
                                                    $this->fs_replace_content_model->negative_bracket($data[0]['group_end_prev_ye_value']),
                                                    '',
                                                    $this->fs_replace_content_model->negative_bracket($data[0]['value']),
                                                    $this->fs_replace_content_model->negative_bracket($data[0]['company_end_prev_ye_value']),
                                                );
                            }

                            $tr_item .= $this->replace_tr_template_item($tr_template_data['last_line_desc'], $input_value);

                            $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item . $tr_template_data['newline'], $replaced_tbl_template_2);
                            if($additional_info['generate_docs_without_tags'])
                            {
                                foreach ($tr_template_data['ori_all_template'] as $at_key => $at_value) 
                                {
                                    $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                }
                            }
                            $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                        }
                    }
                }
                elseif($table_name == "Note 16 - Trade and other receivables (table_1) (first set)" || $table_name == "Note 16 - Trade and other receivables (table_1) (not first set)" || 
                    $table_name == "Note 24 - Trade and other payables (table_1) (first set)" || $table_name == "Note 24 - Trade and other payables (table_1) (not first set)"
                )
                {
                    $replaced_tbl_template   = $replaced_tbl_value; 
                    $replaced_tbl_template_2 = $replaced_tbl_template;
                    
                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name);
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));

                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name); 

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                        if($is_hide_content)
                        {
                            if($additional_info['generate_docs_without_tags'])
                            {
                                $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                                $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                                $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                            }   
                            else
                            {
                                $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                            }
                        }
                        else
                        {
                            $description_tr_template     = '';
                            $overall_total_tr_template   = '';
                            $last_line_space_tr_template = '';

                            $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                            // get templates and remove all the info first
                            $extra_info = array(
                                            'hide_group_column'       => $hide_column_data['all'],
                                            'template_num_of_line'    => $hide_column_data['clear_tr_after_row'],
                                            'replaced_tbl_template_2' => $replaced_tbl_template_2,
                                            'group_type'              => $fs_company_info[0]['group_type'],
                                            'table_name'              => $table_name
                                        );

                            $tr_template_data = $this->build_tbl_template_tr_data($tbl_tr, $extra_info); 

                            $replaced_tbl_template_2     = $tr_template_data['replaced_tbl_template_2'];
                            $last_line_space_tr_template = $tr_template_data['last_line_space'];

                            // set value to template
                            $tr_item = '';

                            $data = $this->get_t1_value_tr_template($table_name, $fs_company_info_id);

                            if(count($data[0]['child_array']) > 0)
                            {
                                $tr_item .= $tr_template_data['newline'];
                            }

                            $o_value_group_ty   = 0.00;
                            $o_value_group_ly   = 0.00;
                            $o_value_company_ty = 0.00;
                            $o_value_company_ly = 0.00;

                            foreach ($data[0]['child_array'] as $t1_key => $t1_value) 
                            {
                                $value_group_ty   = 0.00;
                                $value_group_ly   = 0.00;
                                $value_company_ty = 0.00;
                                $value_company_ly = 0.00;

                                $tr_item .= $this->replace_tr_template_item($tr_template_data['description_underline'], array($t1_value['parent_array'][0]['description']));

                                foreach ($t1_value['child_array'] as $t1_child_key => $t1_child_value) 
                                {
                                    $input_value = [];

                                    if($fs_company_info[0]['first_set'])
                                    {
                                        $input_value = array(
                                                            $t1_child_value['child_array']['description'],
                                                            $this->fs_replace_content_model->negative_bracket($t1_child_value['child_array']['group_end_this_ye_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($t1_child_value['child_array']['value']),
                                                        );
                                    }
                                    else
                                    {
                                        $input_value = array(
                                                            $t1_child_value['child_array']['description'],
                                                            $this->fs_replace_content_model->negative_bracket($t1_child_value['child_array']['group_end_this_ye_value']),
                                                            $this->fs_replace_content_model->negative_bracket($t1_child_value['child_array']['group_end_prev_ye_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($t1_child_value['child_array']['value']),
                                                            $this->fs_replace_content_model->negative_bracket($t1_child_value['child_array']['company_end_prev_ye_value'])
                                                        );
                                    }

                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['description'], $input_value);

                                    $value_group_ty   += $t1_child_value['child_array']['group_end_this_ye_value'];
                                    $value_group_ly   += $t1_child_value['child_array']['group_end_prev_ye_value'];
                                    $value_company_ty += $t1_child_value['child_array']['value'];
                                    $value_company_ly += $t1_child_value['child_array']['company_end_prev_ye_value'];
                                }

                                /* for subtotal */
                                $subtotal_input_value = [];

                                if($fs_company_info[0]['first_set'])
                                {
                                    $subtotal_input_value = array(
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket($value_group_ty),
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket($value_company_ty)
                                                    );
                                }
                                else
                                {
                                    $subtotal_input_value = array(
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket($value_group_ty),
                                                        $this->fs_replace_content_model->negative_bracket($value_group_ly),
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket($value_company_ty),
                                                        $this->fs_replace_content_model->negative_bracket($value_company_ly)
                                                    );
                                }

                                $tr_item .= $this->replace_tr_template_item($tr_template_data['subtotal'], $subtotal_input_value);
                                /* END OF for subtotal */

                                if($t1_key + 1 != count($data[0]['child_array']))
                                {
                                    $tr_item .= $tr_template_data['newline'];
                                }
                                
                                $o_value_group_ty   += $value_group_ty;
                                $o_value_group_ly   += $value_group_ly;
                                $o_value_company_ty += $value_company_ty;
                                $o_value_company_ly += $value_company_ly;
                            }

                            /* for overall total */
                            $overall_input_value = [];

                            if($fs_company_info[0]['first_set'])
                            {
                                $overall_input_value = array(
                                                    '',
                                                    $this->fs_replace_content_model->negative_bracket($o_value_group_ty),
                                                    '',
                                                    $this->fs_replace_content_model->negative_bracket($o_value_company_ty),
                                                );
                            }
                            else
                            {
                                $overall_input_value = array(
                                                    '',
                                                    $this->fs_replace_content_model->negative_bracket($o_value_group_ty),
                                                    $this->fs_replace_content_model->negative_bracket($o_value_group_ly),
                                                    '',
                                                    $this->fs_replace_content_model->negative_bracket($o_value_company_ty),
                                                    $this->fs_replace_content_model->negative_bracket($o_value_company_ly)
                                                );
                            }

                            $tr_item .= $this->replace_tr_template_item($tr_template_data['overall_total'], $overall_input_value);
                            /* END OF for overall total */

                            // print_r(array($replaced_tbl_template_2));

                            $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item . $tr_template_data['newline'], $replaced_tbl_template_2);
                            if($additional_info['generate_docs_without_tags'])
                            {
                                foreach ($tr_template_data['ori_all_template'] as $at_key => $at_value) 
                                {
                                    $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                }
                            }
                            $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                        }
                    }
                }
                elseif($table_name == "Note 16 - Trade and other receivables (table_4) (first set)" || $table_name == "Note 16 - Trade and other receivables (table_4) (not first set)")
                {
                    $replaced_tbl_template   = $replaced_tbl_value; 
                    $replaced_tbl_template_2 = $replaced_tbl_template;
                    
                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name);
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));

                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name); 

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                        if($is_hide_content)
                        {
                            if($additional_info['generate_docs_without_tags'])
                            {
                                $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                                $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                                $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                            }   
                            else
                            {
                                // $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                            }
                        }
                        else
                        {
                            $p1 = $this->db->query("SELECT lb.*
                                            FROM fs_trade_and_other_receivables_info lb
                                            LEFT JOIN fs_list_trade_and_other_receivables_content lbd ON lbd.id = lb.fs_list_trade_and_other_receivables_content_id
                                            LEFT JOIN fs_list_trade_and_other_receivables_title lbt ON lbt.id = lbd.fs_list_trade_and_other_receivables_title_id
                                            WHERE lb.fs_company_info_id=" . $fs_company_info_id . " AND lbt.id = 1 AND lb.is_checked = 1 ORDER BY lb.order_by");
                            $p1 = $p1->result_array();

                            if(count($p1) > 0)
                            {
                                $p2 = $this->db->query("SELECT lb.*
                                                FROM fs_trade_and_other_receivables_info lb
                                                LEFT JOIN fs_list_trade_and_other_receivables_content lbd ON lbd.id = lb.fs_list_trade_and_other_receivables_content_id
                                                LEFT JOIN fs_list_trade_and_other_receivables_title lbt ON lbt.id = lbd.fs_list_trade_and_other_receivables_title_id
                                                WHERE lb.fs_company_info_id=" . $fs_company_info_id . " AND lbt.id = 3 AND lb.is_checked = 1 ORDER BY lb.order_by");
                                $p2 = $p2->result_array();

                                if(count($p2) > 0)
                                {
                                    $is_hide_content = false;
                                }
                                else
                                {
                                    $is_hide_content = true;
                                }
                            }
                            else
                            {
                                $is_hide_content = true;
                            }

                            /* ---------- Collect templates ---------- */
                            $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                            // get templates and remove all the info first
                            $extra_info = array(
                                            'hide_group_column'       => $hide_column_data['all'],
                                            'template_num_of_line'    => $hide_column_data['clear_tr_after_row'],
                                            'replaced_tbl_template_2' => $replaced_tbl_template_2,
                                            'group_type'              => $fs_company_info[0]['group_type'],
                                            'table_name'              => $table_name
                                        );

                            $tr_template_data = $this->build_tbl_template_tr_data($tbl_tr, $extra_info); 
                            /* ---------- END OF Collect templates ---------- */

                            if($is_hide_content)
                            {
                                if($additional_info['generate_docs_without_tags'])
                                {
                                    foreach ($tr_template_data['ori_all_template'] as $at_key => $at_value) 
                                    {
                                        $tr_template_data['replaced_tbl_value_2'] = str_replace($at_value, '', $tr_template_data['replaced_tbl_value_2']);
                                    }
                                }
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $tr_template_data['replaced_tbl_value_2'], $tbl_value, $replaced_xml);
                            }
                            else
                            {
                                $description_tr_template     = '';
                                $description_underline_tr_template = '';
                                $last_line_desc_tr_template   = '';
                                $last_line_space_tr_template = '';

                                $data = $this->get_t1_value_tr_template($table_name, $fs_company_info_id);
                                $show_table = false;

                                foreach ($data as $key_1 => $value_1) 
                                {
                                    if($value_1['part'] == 2)
                                    {
                                        $show_table = true;
                                    }
                                }

                                $replaced_tbl_template_2     = $tr_template_data['replaced_tbl_template_2'];
                                $last_line_space_tr_template = $tr_template_data['last_line_space'];

                                $description_tr_template            = $tr_template_data['description'];
                                $description_underline_tr_template  = $tr_template_data['description_underline'];
                                $last_line_desc_tr_template         = $tr_template_data['last_line_desc']; 

                                if($show_table) // show table if have part 2
                                {
                                    // set value to template
                                    $tr_item = '';

                                    if(count($data) > 0)
                                    {
                                        $tr_item .= $tr_template_data['newline'];
                                    }

                                    foreach ($data as $key => $value) 
                                    {
                                        $data_array = [];

                                        if(!empty($value['value']) || !empty($value['company_end_prev_ye_value']) || !empty($value['group_end_this_ye_value']) || !empty($value['group_end_prev_ye_value']))
                                        {
                                            if($fs_company_info[0]['first_set'])
                                            {
                                                $data_array = array(
                                                                    $value['description'],
                                                                    $this->fs_replace_content_model->negative_bracket($value['group_end_this_ye_value']),
                                                                    '',
                                                                    $this->fs_replace_content_model->negative_bracket($value['value'])
                                                                );
                                            }
                                            else
                                            {
                                                $data_array = array(
                                                                    $value['description'],
                                                                    $this->fs_replace_content_model->negative_bracket($value['group_end_this_ye_value']),
                                                                    $this->fs_replace_content_model->negative_bracket($value['group_end_prev_ye_value']),
                                                                    '',
                                                                    $this->fs_replace_content_model->negative_bracket($value['value']),
                                                                    $this->fs_replace_content_model->negative_bracket($value['company_end_prev_ye_value'])
                                                                );
                                            }
                                        }

                                        if(!empty($data_array))
                                        {
                                            if($value['is_title']) // underline title
                                            {
                                                $tr_item .= $this->replace_tr_template_item($description_underline_tr_template, $data_array);
                                            }
                                            elseif($key == count($data)-1 || $value['part'] != $data[$key + 1]['part'])
                                            {
                                                $tr_item .= $this->replace_tr_template_item($last_line_desc_tr_template, $data_array);
                                                $tr_item .= $tr_template_data['newline'];
                                            }
                                            else
                                            {
                                                $tr_item .= $this->replace_tr_template_item($description_tr_template, $data_array);
                                            }
                                        }
                                    }

                                    $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item . $tr_template_data['newline'], $replaced_tbl_template_2);
                                    if($additional_info['generate_docs_without_tags'])
                                    {
                                        foreach ($tr_template_data['ori_all_template'] as $at_key => $at_value) 
                                        {
                                            $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                        }
                                    }
                                    $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                                }
                                else
                                {
                                    if($additional_info['generate_docs_without_tags'])
                                    {
                                        foreach ($tr_template_data['ori_all_template'] as $at_key => $at_value) 
                                        {
                                            $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                elseif($table_name == "Note 18 - Cash and short-term deposits (table_3) (first set)" || $table_name == "Note 18 - Cash and short-term deposits (table_3) (not first set)")
                {
                    $replaced_tbl_template   = $replaced_tbl_value; 
                    $replaced_tbl_template_2 = $replaced_tbl_template;

                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name);
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));

                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name);

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                        if($is_hide_content)
                        {
                            if($additional_info['generate_docs_without_tags'])
                            {
                                $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                                $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                                $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                            }   
                            else
                            {
                                $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                            }
                        }
                        else
                        {
                            $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                            // get templates and remove all the info first
                            $extra_info = array(
                                'hide_group_column'       => $hide_column_data['all'],
                                'template_num_of_line'    => $hide_column_data['clear_tr_after_row'],
                                'replaced_tbl_template_2' => $replaced_tbl_template_2,
                                'group_type'              => $fs_company_info[0]['group_type'],
                                'table_name' => $table_name
                            );

                            $tr_template_data = $this->build_tbl_template_tr_data($tbl_tr, $extra_info);

                            $replaced_tbl_template_2     = $tr_template_data['replaced_tbl_template_2'];
                            $last_line_space_tr_template = $tr_template_data['last_line_space'];
                            $newline_template            = $tr_template_data['newline'];

                            // set value to template
                            $tr_item = '';
                            $temp_last_tr_row = '';

                            $total = array(
                                        'group_cy' => 0,
                                        'group_ly' => 0,
                                        'company_cy' => 0,
                                        'company_ly' => 0
                                    );
                            $overall = $total;

                            $data = $this->get_t1_value_tr_template($table_name, $fs_company_info_id); 

                            foreach ($data as $key => $value) 
                            {
                                if($value['part'] == 1)
                                {
                                    if($fs_company_info[0]['first_set'])
                                    {
                                        $input_value = array(
                                                            $value['description'],
                                                            $this->fs_replace_content_model->negative_bracket($value['group_end_this_ye_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($value['value'])
                                                        );
                                    }
                                    else
                                    {
                                        $input_value = array(
                                                            $value['description'],
                                                            $this->fs_replace_content_model->negative_bracket($value['group_end_this_ye_value']),
                                                            $this->fs_replace_content_model->negative_bracket($value['group_end_prev_ye_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($value['value']),
                                                            $this->fs_replace_content_model->negative_bracket($value['company_end_prev_ye_value'])
                                                        );
                                    }

                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['description'], $input_value);

                                    // calculate subtotal
                                    $total['group_cy']   += (int)$value['group_end_this_ye_value'];
                                    $total['group_ly']   += (int)$value['group_end_prev_ye_value'];
                                    $total['company_cy'] += (int)$value['value'];
                                    $total['company_ly'] += (int)$value['company_end_prev_ye_value'];
                                }
                                elseif ($value['part'] == 2) 
                                {
                                    if($fs_company_info[0]['first_set'])
                                    {
                                        $input_value = array(
                                                            $value['description'],
                                                            $this->fs_replace_content_model->negative_bracket($value['group_end_this_ye_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($value['value'])
                                                        );
                                    }
                                    else
                                    {
                                        $input_value = array(
                                                            $value['description'],
                                                            $this->fs_replace_content_model->negative_bracket($value['group_end_this_ye_value']),
                                                            $this->fs_replace_content_model->negative_bracket($value['group_end_prev_ye_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($value['value']),
                                                            $this->fs_replace_content_model->negative_bracket($value['company_end_prev_ye_value'])
                                                        );
                                    }

                                    $temp_last_tr_row .= $this->replace_tr_template_item($tr_template_data['description'], $input_value);
                                }

                                // calculate overall total
                                $overall['group_cy']   += (int)$value['group_end_this_ye_value'];
                                $overall['group_ly']   += (int)$value['group_end_prev_ye_value'];
                                $overall['company_cy'] += (int)$value['value'];
                                $overall['company_ly'] += (int)$value['company_end_prev_ye_value'];
                            }

                            // add subtotal tr
                            $total_value   = [];
                            $overall_value = [];

                            if($fs_company_info[0]['first_set'])
                            {
                                $total_value = array(
                                                    '',
                                                    $this->fs_replace_content_model->negative_bracket($total['group_cy']),
                                                    '',
                                                    $this->fs_replace_content_model->negative_bracket($total['company_cy'])
                                                );

                                $overall_value = array(
                                                    '',
                                                    $this->fs_replace_content_model->negative_bracket($overall['group_cy']),
                                                    '',
                                                    $this->fs_replace_content_model->negative_bracket($overall['company_cy'])
                                                );
                            }
                            else
                            {
                                $total_value = array(
                                                    '',
                                                    $this->fs_replace_content_model->negative_bracket($total['group_cy']),
                                                    $this->fs_replace_content_model->negative_bracket($total['group_ly']),
                                                    '',
                                                    $this->fs_replace_content_model->negative_bracket($total['company_cy']),
                                                    $this->fs_replace_content_model->negative_bracket($total['company_ly'])
                                                );

                                $overall_value = array(
                                                    '',
                                                    $this->fs_replace_content_model->negative_bracket($overall['group_cy']),
                                                    $this->fs_replace_content_model->negative_bracket($overall['group_ly']),
                                                    '',
                                                    $this->fs_replace_content_model->negative_bracket($overall['company_cy']),
                                                    $this->fs_replace_content_model->negative_bracket($overall['company_ly'])
                                                );
                            }

                            $tr_item .= $this->replace_tr_template_item($tr_template_data['subtotal'], $total_value);
                            $tr_item .= $temp_last_tr_row;
                            $tr_item .= $this->replace_tr_template_item($tr_template_data['overall_total'], $overall_value);

                            $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item . $newline_template, $replaced_tbl_template_2);
                            if($additional_info['generate_docs_without_tags'])
                            {
                                foreach ($tr_template_data['ori_all_template'] as $at_key => $at_value) 
                                {
                                    $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                }
                            }
                            $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                        }
                    }
                }
                elseif($table_name == "Note 22 - Loans and borrowings (table_1) (first set)" || $table_name == "Note 22 - Loans and borrowings (table_1) (not first set)")
                {
                    $replaced_tbl_template   = $replaced_tbl_value; 
                    $replaced_tbl_template_2 = $replaced_tbl_template;
                    
                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name);
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));

                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name); 

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                        if($is_hide_content)
                        {
                            if($additional_info['generate_docs_without_tags'])
                            {
                                $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                                $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                                $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                            }   
                            else
                            {
                                $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                            }
                        }
                        else
                        {
                            $description_tr_template     = '';
                            $overall_total_tr_template   = '';
                            $last_line_space_tr_template = '';

                            $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                            // get templates and remove all the info first
                            $extra_info = array(
                                            'hide_group_column'       => $hide_column_data['all'],
                                            'template_num_of_line'    => $hide_column_data['clear_tr_after_row'],
                                            'replaced_tbl_template_2' => $replaced_tbl_template_2,
                                            'group_type' => $fs_company_info[0]['group_type'],
                                            'table_name' => $table_name
                                        );

                            $tr_template_data = $this->build_tbl_template_tr_data($tbl_tr, $extra_info); 

                            $replaced_tbl_template_2     = $tr_template_data['replaced_tbl_template_2'];
                            $last_line_space_tr_template = $tr_template_data['last_line_space'];

                            // set value to template
                            $tr_item = '';

                            $data = $this->get_t1_value_tr_template($table_name, $fs_company_info_id);

                            if(count($data) > 0)
                            {
                                $tr_item .= $tr_template_data['newline'];
                            }

                            foreach ($data as $lb_t1_key => $lb_t1_value) 
                            {
                                $input_value = [];

                                if($fs_company_info[0]['first_set'])
                                {
                                    $input_value = array(
                                                        $lb_t1_value['description'],
                                                        $lb_t1_value['group_end_this_ye_value'],
                                                        '',
                                                        $lb_t1_value['value']
                                                    );
                                }
                                else
                                {
                                    $input_value = array(
                                                        $lb_t1_value['description'],
                                                        $lb_t1_value['group_end_this_ye_value'],
                                                        $lb_t1_value['group_end_prev_ye_value'],
                                                        '',
                                                        $lb_t1_value['value'],
                                                        $lb_t1_value['company_end_prev_ye_value'],
                                                    );
                                }

                                if($lb_t1_value['is_last_section'])
                                {
                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['overall_total'], $input_value);
                                }
                                else
                                {
                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['description'], $input_value);
                                }
                            }

                            $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item . $tr_template_data['newline'], $replaced_tbl_template_2);
                            if($additional_info['generate_docs_without_tags'])
                            {
                                foreach ($tr_template_data['ori_all_template'] as $at_key => $at_value) 
                                {
                                    $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                }
                            }
                            $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                        }
                    }
                }
                elseif($table_name == "Note 22 - Loans and borrowings (table_2)")
                {
                    $replaced_tbl_template   = $replaced_tbl_value; 
                    $replaced_tbl_template_2 = $replaced_tbl_template;

                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name);
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));

                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name);

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                        if($is_hide_content)
                        {
                            if($additional_info['generate_docs_without_tags'])
                            {
                                $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                                $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                                $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                            }   
                            else
                            {
                                $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml); 
                            }
                        }
                        else
                        {
                            $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                            // get templates and remove all the info first
                            $extra_info = array(
                                'hide_group_column'       => $hide_column_data['all'],
                                'template_num_of_line'    => $hide_column_data['clear_tr_after_row'],
                                'replaced_tbl_template_2' => $replaced_tbl_template_2,
                                'group_type'              => $fs_company_info[0]['group_type'],
                                'table_name'              => $table_name
                            );

                            $tr_template_data = $this->build_tbl_template_tr_data($tbl_tr, $extra_info);

                            $replaced_tbl_template_2     = $tr_template_data['replaced_tbl_template_2'];
                            $last_line_space_tr_template = $tr_template_data['last_line_space'];
                            $newline_template            = $tr_template_data['newline'];

                            // set value to template
                            $tr_item = '';

                            $subtotal = array(
                                        'value_1' => 0,
                                        'value_2' => 0,
                                        'value_3' => 0,
                                        'value_4' => 0
                                    );

                            $data = $this->get_t1_value_tr_template($table_name, $fs_company_info_id); 

                            foreach ($data as $key => $value) 
                            {
                                if($value['is_title'])
                                {
                                    $input_value = array(
                                                        $value['description'],
                                                        $value['value_1'],
                                                        $value['value_2'],
                                                        '',
                                                        $value['value_3'],
                                                        $value['value_4']
                                                    );

                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['title_bold'], $input_value);
                                }
                                elseif($value['is_subtotal'])
                                {
                                    $input_value = array(
                                                        $value['description'],
                                                        $this->fs_replace_content_model->negative_bracket($subtotal['value_1']),
                                                        $this->fs_replace_content_model->negative_bracket($subtotal['value_2']),
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket($subtotal['value_3']),
                                                        $this->fs_replace_content_model->negative_bracket($subtotal['value_4'])
                                                    );

                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['subtotal'], $input_value);
                                }
                                elseif($value['is_last_section'])
                                {
                                    $input_value = array(
                                                        $value['description'],
                                                        $this->fs_replace_content_model->negative_bracket($subtotal['value_1']),
                                                        $this->fs_replace_content_model->negative_bracket($subtotal['value_2']),
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket($subtotal['value_3']),
                                                        $this->fs_replace_content_model->negative_bracket($subtotal['value_4'])
                                                    );

                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['overall_total_desc'], $input_value);
                                }
                                else
                                {
                                    $input_value = array(
                                                        $value['description'],
                                                        $this->fs_replace_content_model->negative_bracket($value['value_1']),
                                                        $this->fs_replace_content_model->negative_bracket($value['value_2']),
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket($value['value_3']),
                                                        $this->fs_replace_content_model->negative_bracket($value['value_4'])
                                                    );

                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['description'], $input_value);
                                }

                                // new line
                                if($key == count($data) - 1 || $data[$key+1]['prior_current'] != $value['prior_current'])
                                {
                                    $tr_item .= $newline_template;
                                }

                                // reset to 0
                                if($key !=0 && $key != count($data) - 1 && $data[$key-1]['prior_current'] != $value['prior_current'])
                                {
                                    $subtotal['value_1'] = 0;
                                    $subtotal['value_2'] = 0;
                                    $subtotal['value_3'] = 0;
                                    $subtotal['value_4'] = 0;
                                }

                                if(!($value['is_subtotal'] || $value['is_last_section'])) // exclude subtotal and last section
                                {
                                    $subtotal['value_1'] += $value['value_1'];
                                    $subtotal['value_2'] += $value['value_2'];
                                    $subtotal['value_3'] += $value['value_3'];
                                    $subtotal['value_4'] += $value['value_4'];
                                }
                            }

                            $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item . $newline_template, $replaced_tbl_template_2);
                            if($additional_info['generate_docs_without_tags'])
                            {
                                foreach ($tr_template_data['ori_all_template'] as $at_key => $at_value) 
                                {
                                    $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                }
                            }
                            $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                        }
                    }
                }
                elseif($table_name == "Note 22 - Loans and borrowings (table 4) (first set)" || $table_name == "Note 22 - Loans and borrowings (table 4) (not first set)")
                {
                    $replaced_tbl_template   = $replaced_tbl_value; 
                    $replaced_tbl_template_2 = $replaced_tbl_template;
                    
                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name);
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));

                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name); 

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                        if($is_hide_content)
                        {
                            if($additional_info['generate_docs_without_tags'])
                            {
                                $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                                $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                                $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                            }   
                            else
                            {
                                $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                            }
                        }
                        else
                        {
                            $description_tr_template     = '';
                            $overall_total_tr_template   = '';
                            $last_line_space_tr_template = '';

                            $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                            // get templates and remove all the info first
                            $extra_info = array(
                                            'hide_group_column'       => $hide_column_data['all'],
                                            'template_num_of_line'    => $hide_column_data['clear_tr_after_row'],
                                            'replaced_tbl_template_2' => $replaced_tbl_template_2,
                                            'group_type'              => $fs_company_info[0]['group_type'],
                                            'table_name'              => $table_name
                                        );

                            $tr_template_data = $this->build_tbl_template_tr_data($tbl_tr, $extra_info); 

                            $replaced_tbl_template_2     = $tr_template_data['replaced_tbl_template_2'];
                            $last_line_space_tr_template = $tr_template_data['last_line_space'];

                            // set value to template
                            $tr_item = '';

                            $data = $this->get_t1_value_tr_template($table_name, $fs_company_info_id);

                            foreach ($data as $key => $value) 
                            {
                                $input_value = [];

                                if($fs_company_info[0]['first_set'])
                                {
                                    $input_value = array(
                                                        $value['description'],
                                                        $this->fs_replace_content_model->negative_bracket($value['group_end_this_ye_value']),
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket($value['value'])
                                                    );
                                }
                                else
                                {
                                    $input_value = array(
                                                        $value['description'],
                                                        $this->fs_replace_content_model->negative_bracket($value['group_end_this_ye_value']),
                                                        $this->fs_replace_content_model->negative_bracket($value['group_end_prev_ye_value']),
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket($value['value']),
                                                        $this->fs_replace_content_model->negative_bracket($value['company_end_prev_ye_value'])
                                                    );
                                }

                                if($value['is_title'])
                                {
                                    $subtotal_group_ty   = 0.00;
                                    $subtotal_group_ly   = 0.00;
                                    $subtotal_company_ty = 0.00;
                                    $subtotal_company_ly = 0.00;

                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['description_underline'], array($value['description']));
                                }
                                elseif(count($data) == $key+1)
                                {
                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['overall_total'], $input_value);
                                }
                                else
                                {
                                    $subtotal_group_ty   += $value['group_end_this_ye_value'];
                                    $subtotal_group_ly   += $value['group_end_prev_ye_value'];
                                    $subtotal_company_ty += $value['value'];
                                    $subtotal_company_ly += $value['company_end_prev_ye_value'];

                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['description'], $input_value);
                                }

                                // for subtotal
                                if(!$is_last_section && $data[$key+1]['is_title'])
                                {
                                    $subtotal_input_value = [];

                                    if($fs_company_info[0]['first_set'])
                                    {
                                        $subtotal_input_value = array(
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($subtotal_group_ty),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($subtotal_company_ty)
                                                        );
                                    }
                                    else
                                    {
                                        $subtotal_input_value = array(
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($subtotal_group_ty),
                                                            $this->fs_replace_content_model->negative_bracket($subtotal_group_ly),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($subtotal_company_ty),
                                                            $this->fs_replace_content_model->negative_bracket($subtotal_company_ly)
                                                        );
                                    }

                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['subtotal'], $subtotal_input_value);
                                }
                            }

                            $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item . $tr_template_data['newline'], $replaced_tbl_template_2);
                            if($additional_info['generate_docs_without_tags'])
                            {
                                foreach ($tr_template_data['ori_all_template'] as $at_key => $at_value) 
                                {
                                    $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                }
                            }
                            $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                        }
                    }
                }
                elseif($table_name == "Note 22 - Loans and borrowings (table_5) (first set)" || $table_name == "Note 22 - Loans and borrowings (table_5) (not first set)")
                {
                    $replaced_tbl_template   = $replaced_tbl_value; 
                    $replaced_tbl_template_2 = $replaced_tbl_template;

                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name);
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));

                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name);

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                        if($is_hide_content)
                        {
                            if($additional_info['generate_docs_without_tags'])
                            {
                                $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                                $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                                $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                            }   
                            else
                            {
                                $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                            }
                        }
                        else
                        {
                            $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                            // get templates and remove all the info first
                            $extra_info = array(
                                'hide_group_column'       => $hide_column_data['all'],
                                'template_num_of_line'    => $hide_column_data['clear_tr_after_row'],
                                'replaced_tbl_template_2' => $replaced_tbl_template_2,
                                'group_type'              => $fs_company_info[0]['group_type'],
                                'table_name'              => $table_name
                            );

                            $tr_template_data = $this->build_tbl_template_tr_data($tbl_tr, $extra_info); 

                            $replaced_tbl_template_2     = $tr_template_data['replaced_tbl_template_2'];
                            $last_line_space_tr_template = $tr_template_data['last_line_space'];
                            $newline_template            = $tr_template_data['newline'];

                            // set value to template
                            $tr_item = '';

                            $template_tr = array("", "", "", "", "", "");
                            $input_value = $template_tr;

                            $total = array(
                                        'group_cy'   => 0,
                                        'group_ly'   => 0,
                                        'company_cy' => 0,
                                        'company_ly' => 0
                                    );
                            $section_total = $total;
                            $overall       = $total;

                            $data = $this->get_t1_value_tr_template($table_name, $fs_company_info_id); 

                            foreach ($data as $key => $value) 
                            {
                                $input_value[0] = $value['description'];

                                if($value['is_main_title'])
                                {
                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['title_bold'], $input_value);
                                }
                                elseif($value['is_subtitle'])
                                {
                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['title_italic'], $input_value);
                                }
                                else
                                {
                                    $input_value[1] = $this->fs_replace_content_model->negative_bracket($value['group_end_this_ye_value']);
                                    $input_value[4] = $this->fs_replace_content_model->negative_bracket($value['value']);

                                    if($fs_company_info[0]['first_set']) // remove last year column
                                    {
                                        unset($input_value[2]);
                                        unset($input_value[5]);

                                        $input_value = array_values($input_value);
                                    }
                                    else
                                    {
                                        $input_value[2] = $this->fs_replace_content_model->negative_bracket($value['group_end_this_ye_value']);
                                        $input_value[5] = $this->fs_replace_content_model->negative_bracket($value['company_end_prev_ye_value']);
                                    }

                                    $total['group_cy']   += $value['group_end_this_ye_value'];
                                    $total['group_ly']   += $value['group_end_prev_ye_value'];
                                    $total['company_cy'] += $value['value'];
                                    $total['company_ly'] += $value['company_end_prev_ye_value'];

                                    // add up section total
                                    $section_total['group_cy']   += $value['group_end_this_ye_value'];
                                    $section_total['group_ly']   += $value['group_end_prev_ye_value'];
                                    $section_total['company_cy'] += $value['value'];
                                    $section_total['company_ly'] += $value['company_end_prev_ye_value'];

                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['description'], $input_value);
                                }

                                // dsplay subtotal
                                if($key == count($data) - 1 || $data[$key+1]['is_subtitle'] && !$data[$key]['is_main_title'])
                                {
                                    $input_value[0] = "";
                                    $input_value[1] = $this->fs_replace_content_model->negative_bracket($total['group_cy']);
                                    $input_value[4] = $this->fs_replace_content_model->negative_bracket($total['company_cy']);

                                    if($fs_company_info[0]['first_set']) // remove last year column
                                    {
                                        unset($input_value[2]);
                                        unset($input_value[5]);

                                        $input_value = array_values($input_value);
                                    }
                                    else
                                    {
                                        $input_value[2] = $this->fs_replace_content_model->negative_bracket($total['group_ly']);
                                        $input_value[5] = $this->fs_replace_content_model->negative_bracket($total['company_ly']);
                                    }

                                    // reset to 0
                                    $total['group_cy']   = 0;
                                    $total['group_ly']   = 0;
                                    $total['company_cy'] = 0;
                                    $total['company_ly'] = 0;

                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['subtotal'], $input_value);
                                }

                                // display section total
                                if($key == count($data) - 1 || ($data[$key+1]['is_main_title']))
                                {
                                    if($value['main_title'] == "Current")
                                    {
                                        $input_value[0] = "Total current loans and borrowings";
                                    }
                                    elseif($value['main_title'] == "Non-current")
                                    {
                                        $input_value[0] = "Total non-current loans and borrowings"; 
                                    }

                                    $input_value[1] = $this->fs_replace_content_model->negative_bracket($section_total['group_cy']);
                                    $input_value[4] = $this->fs_replace_content_model->negative_bracket($section_total['company_cy']);

                                    if($fs_company_info[0]['first_set']) // remove last year column
                                    {
                                        unset($input_value[2]);
                                        unset($input_value[5]);

                                        $input_value = array_values($input_value);
                                    }
                                    else
                                    {
                                        $input_value[2] = $this->fs_replace_content_model->negative_bracket($section_total['group_ly']);
                                        $input_value[5] = $this->fs_replace_content_model->negative_bracket($section_total['company_ly']);
                                    }

                                    // add up overall
                                    $overall['group_cy']   += $section_total['group_cy'];
                                    $overall['group_ly']   += $section_total['group_ly'];
                                    $overall['company_cy'] += $section_total['company_cy'];
                                    $overall['company_ly'] += $section_total['company_ly'];

                                    // reset to 0
                                    $total['group_cy']   = 0;
                                    $total['group_ly']   = 0;
                                    $total['company_cy'] = 0;
                                    $total['company_ly'] = 0;

                                    // rest ot 0
                                    $section_total['group_cy']   = 0;
                                    $section_total['group_ly']   = 0;
                                    $section_total['company_cy'] = 0;
                                    $section_total['company_ly'] = 0;

                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['subtotal_desc'], $input_value);

                                    if($key != count($data) - 1)
                                    {
                                        $tr_item .= $newline_template;
                                    }
                                }

                                // display overall
                                if($key == count($data) - 1)
                                {
                                    $input_value[0] = "Total loans and borrowings";
                                    $input_value[1] = $this->fs_replace_content_model->negative_bracket($overall['group_cy']);
                                    $input_value[4] = $this->fs_replace_content_model->negative_bracket($overall['company_cy']);

                                    if($fs_company_info[0]['first_set']) // remove last year column
                                    {
                                        unset($input_value[2]);
                                        unset($input_value[5]);

                                        $input_value = array_values($input_value);
                                    }
                                    else
                                    {
                                        $input_value[2] = $this->fs_replace_content_model->negative_bracket($overall['group_ly']);
                                        $input_value[5] = $this->fs_replace_content_model->negative_bracket($overall['company_ly']);
                                    }

                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['overall_total_desc'], $input_value);
                                }
                            }

                            $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item . $newline_template, $replaced_tbl_template_2);
                            if($additional_info['generate_docs_without_tags'])
                            {
                                foreach ($tr_template_data['ori_all_template'] as $at_key => $at_value) 
                                {
                                    $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                }
                            }
                            $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                        }
                    }
                }
                elseif(
                    $table_name == "Note 7 - Investment in associates (table_3) (first set)" || $table_name == "Note 7 - Investment in associates (table_3) (not first set)" || 
                    $table_name == "Note 8 - Investment in joint venture (table_2) (first set)" || $table_name == "Note 8 - Investment in joint venture (table_2) (not first set)" || 
                    $table_name == "Note 11 - Investment properties (table_2) (first set)" || $table_name == "Note 11 - Investment properties (table_2) (not first set)" ||
                    $table_name == "Note 15 - Contract assets and contract liabilities (table_1) (first set)" || $table_name == "Note 15 - Contract assets and contract liabilities (table_1) (not first set)" || 
                    $table_name == "Note 26 - Related party transactions (table_1) (first set)" || $table_name == "Note 26 - Related party transactions (table_1) (not first set)" || 
                    $table_name == "Note 27 - Commitments (table_1) (first set)" || $table_name == "Note 27 - Commitments (table_1) (not first set)" || 
                    $table_name == "Note 27 - Commitments (table_2) (first set)" || $table_name == "Note 27 - Commitments (table_2) (not first set)" || 
                    $table_name == "Note 27 - Commitments (table_3) (first set)" || $table_name == "Note 27 - Commitments (table_3) (not first set)" || 
                    $table_name == "Note 27 - Commitments (table_4) (first set)" || $table_name == "Note 27 - Commitments (table_4) (not first set)")
                {
                    $replaced_tbl_template   = $replaced_tbl_value; 
                    $replaced_tbl_template_2 = $replaced_tbl_template;

                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name);
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));

                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name);

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                        if($is_hide_content)
                        {
                            if($additional_info['generate_docs_without_tags'])
                            {
                                $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                                $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                                $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                            }   
                            else
                            {
                                $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                            }
                        }
                        else
                        {
                            $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                            // get templates and remove all the info first
                            $extra_info = array(
                                'hide_group_column'       => $hide_column_data['all'],
                                'template_num_of_line'    => $hide_column_data['clear_tr_after_row'],
                                'replaced_tbl_template_2' => $replaced_tbl_template_2,
                                'group_type'              => $fs_company_info[0]['group_type'],
                                'table_name'              => $table_name
                            );

                            $tr_template_data = $this->build_tbl_template_tr_data($tbl_tr, $extra_info);

                            $replaced_tbl_template_2     = $tr_template_data['replaced_tbl_template_2'];
                            $last_line_space_tr_template = $tr_template_data['last_line_space'];
                            $newline_template            = $tr_template_data['newline'];

                            // set value to template
                            $tr_item = '';

                            $data = $this->get_t1_value_tr_template($table_name, $fs_company_info_id); 

                            if(
                                $table_name == "Note 27 - Commitments (table_1) (first set)" || $table_name == "Note 27 - Commitments (table_1) (not first set)" ||
                                $table_name == "Note 27 - Commitments (table_3) (first set)" || $table_name == "Note 27 - Commitments (table_3) (not first set)" ||
                                $table_name == "Note 7 - Investment in associates (table_3) (first set)" || $table_name == "Note 7 - Investment in associates (table_3) (not first set)" ||
                                $table_name == "Note 8 - Investment in joint venture (table_2) (first set)" || $table_name == "Note 8 - Investment in joint venture (table_2) (not first set)"
                                // $table_name == "Note 15 - Contract assets and contract liabilities (table_1) (first set)" || $table_name == "Note 15 - Contract assets and contract liabilities (table_1) (not first set)"
                            )
                            {
                                if($fs_company_info[0]['first_set'])
                                {
                                    $input_value = array(
                                                        $data[0]['description'],
                                                        $this->fs_replace_content_model->negative_bracket($data[0]['group_end_this_ye_value']),
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket($data[0]['value'])
                                                    );
                                }
                                else
                                {
                                    $input_value = array(
                                                        $data[0]['description'],
                                                        $this->fs_replace_content_model->negative_bracket($data[0]['group_end_this_ye_value']),
                                                        $this->fs_replace_content_model->negative_bracket($data[0]['group_end_prev_ye_value']),
                                                        '',
                                                        $this->fs_replace_content_model->negative_bracket($data[0]['value']),
                                                        $this->fs_replace_content_model->negative_bracket($data[0]['company_end_prev_ye_value']),
                                                    );
                                }

                                $tr_item .= $this->replace_tr_template_item($tr_template_data['last_line_desc'], $input_value);
                            }
                            elseif($table_name == "Note 26 - Related party transactions (table_1) (first set)" || $table_name == "Note 26 - Related party transactions (table_1) (not first set)")
                            {
                                foreach ($data as $key => $value) 
                                {
                                    if($fs_company_info[0]['first_set'])
                                    {
                                        $input_value = array(
                                                            $value['description'],
                                                            $this->fs_replace_content_model->negative_bracket($value['group_end_this_ye_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($value['value'])
                                                        );
                                    }
                                    else
                                    {
                                        $input_value = array(
                                                            $value['description'],
                                                            $this->fs_replace_content_model->negative_bracket($value['group_end_this_ye_value']),
                                                            $this->fs_replace_content_model->negative_bracket($value['group_end_prev_ye_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($value['value']),
                                                            $this->fs_replace_content_model->negative_bracket($value['company_end_prev_ye_value']),
                                                        );
                                    }

                                    if(($key+1) == count($data))
                                    {
                                        $tr_item .= $this->replace_tr_template_item($tr_template_data['last_line_desc'], $input_value);
                                    }
                                    elseif($value['title_item'] == "Title")
                                    {
                                        $tr_item .= $this->replace_tr_template_item($tr_template_data['title_normal'], $input_value);
                                    }
                                    elseif($value['title_item'] == "Item")
                                    {
                                        $tr_item .= $this->replace_tr_template_item($tr_template_data['description'], $input_value);
                                    }
                                }
                            }
                            else
                            {
                                foreach ($data as $key => $value) 
                                {
                                    if($fs_company_info[0]['first_set'])
                                    {
                                        $input_value = array(
                                                            $value['description'],
                                                            $this->fs_replace_content_model->negative_bracket($value['group_end_this_ye_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($value['value'])
                                                        );
                                    }
                                    else
                                    {
                                        $input_value = array(
                                                            $value['description'],
                                                            $this->fs_replace_content_model->negative_bracket($value['group_end_this_ye_value']),
                                                            $this->fs_replace_content_model->negative_bracket($value['group_end_prev_ye_value']),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($value['value']),
                                                            $this->fs_replace_content_model->negative_bracket($value['company_end_prev_ye_value'])
                                                        );
                                    }

                                    if(($key+1) == count($data))
                                    {
                                        $tr_item .= $this->replace_tr_template_item($tr_template_data['last_line_desc'], $input_value);
                                    }
                                    else
                                    {
                                        $tr_item .= $this->replace_tr_template_item($tr_template_data['description'], $input_value);
                                    }
                                }
                            }
                            $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item . $newline_template, $replaced_tbl_template_2);
                            if($additional_info['generate_docs_without_tags'])
                            {
                                foreach ($tr_template_data['ori_all_template'] as $at_key => $at_value) 
                                {
                                    $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                }
                            }
                            $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                        }
                    }
                }
                elseif($table_name == "Note 29.2 - Financial Risk Management (table_1) (group)" || $table_name == "Note 29.2 - Financial Risk Management (table_1) (company)")
                {
                    $replaced_tbl_template   = $replaced_tbl_value; 
                    $replaced_tbl_template_2 = $replaced_tbl_template;

                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name);
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));

                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name);

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                        if($is_hide_content)
                        {
                            if($additional_info['generate_docs_without_tags'])
                            {
                                $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                                $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                                $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                            }   
                            else
                            {
                                $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                            }
                        }
                        else
                        {
                            $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                            // get templates and remove all the info first
                            $extra_info = array(
                                'hide_group_column'       => [],
                                'template_num_of_line'    => $hide_column_data['clear_tr_after_row'],
                                'replaced_tbl_template_2' => $replaced_tbl_template_2,
                                'group_type'              => $fs_company_info[0]['group_type'],
                                'table_name'              => $table_name
                            );

                            $tr_template_data = $this->build_tbl_template_tr_data($tbl_tr, $extra_info);

                            $replaced_tbl_template_2     = $tr_template_data['replaced_tbl_template_2'];
                            $last_line_space_tr_template = $tr_template_data['last_line_space'];
                            $newline_template            = $tr_template_data['newline'];

                            // set value to template
                            $tr_item = '';

                            $data = $this->get_t1_value_tr_template($table_name, $fs_company_info_id);  
                            $temp_arr = [];

                            $total_template = array(
                                            'value_1' => 0,
                                            'value_2' => 0,
                                            'value_3' => 0,
                                            'value_4' => 0
                                        );
                            $subtotal = $total_template;
                            $overall  = $total_template;

                            $temp_section = '';

                            foreach ($data as $key => $value) 
                            {
                                if(!($value['prior_current'] == 'prior' && $fs_company_info[0]['first_set'] == '1'))
                                {
                                    // display date title
                                    if($key == 0)
                                    {
                                        $tr_item .= $this->replace_tr_template_item($tr_template_data['title_italic'], array($fs_company_info[0]['current_fye_end']));
                                    }
                                    elseif($key != count($data) - 1 && $data[$key - 1]['prior_current'] != $data[$key]['prior_current'])
                                    {
                                        $tr_item .= $this->replace_tr_template_item($tr_template_data['title_italic'], array($fs_company_info[0]['last_fye_end']));
                                    }

                                    if($value['is_title'])
                                    {
                                        $temp_section = $value['section'];
                                        $tr_item .= $this->replace_tr_template_item($tr_template_data['title_bold'], array($value['description']));
                                    }
                                    else
                                    {
                                        // $subtotal
                                        if($table_name == "Note 29.2 - Financial Risk Management (table_1) (group)")
                                        {
                                            $temp_arr = array(
                                                            $value['description'],
                                                            $this->fs_replace_content_model->negative_bracket($value['within_12_months']),
                                                            $this->fs_replace_content_model->negative_bracket($value['within_2_to_5_years']),
                                                            $this->fs_replace_content_model->negative_bracket($value['more_than_5_years']),
                                                            $this->fs_replace_content_model->negative_bracket($value['total'])
                                                        );
                                        }
                                        else
                                        {
                                            $temp_arr = array(
                                                            $value['description'],
                                                            $this->fs_replace_content_model->negative_bracket($value['less_than_a_year']),
                                                            $this->fs_replace_content_model->negative_bracket($value['between_1_to_5_years']),
                                                            $this->fs_replace_content_model->negative_bracket($value['more_than_5_years']),
                                                            $this->fs_replace_content_model->negative_bracket($value['total'])
                                                        );
                                        }

                                        $tr_item .= $this->replace_tr_template_item($tr_template_data['description'], $temp_arr);

                                        // calculate subtotal
                                        if($table_name == "Note 29.2 - Financial Risk Management (table_1) (group)")
                                        {
                                            $subtotal['value_1'] += $value['within_12_months'];
                                            $subtotal['value_2'] += $value['within_2_to_5_years'];
                                            $subtotal['value_3'] += $value['more_than_5_years'];
                                            $subtotal['value_4'] += $value['total'];
                                        }
                                        elseif($table_name == "Note 29.2 - Financial Risk Management (table_1) (company)")
                                        {
                                            $subtotal['value_1'] += $value['less_than_a_year'];
                                            $subtotal['value_2'] += $value['between_1_to_5_years'];
                                            $subtotal['value_3'] += $value['more_than_5_years'];
                                            $subtotal['value_4'] += $value['total'];
                                        }

                                        // display subtotal
                                        if($key!= count($data) && $data[$key]['section'] != $data[$key+1]['section'])
                                        {
                                            $temp_arr = array(
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($subtotal['value_1']),
                                                            $this->fs_replace_content_model->negative_bracket($subtotal['value_2']),
                                                            $this->fs_replace_content_model->negative_bracket($subtotal['value_3']),
                                                            $this->fs_replace_content_model->negative_bracket($subtotal['value_4'])
                                                        );

                                            $tr_item .= $this->replace_tr_template_item($tr_template_data['subtotal'], $temp_arr);

                                            // sum up for overall
                                            $overall['value_1'] += $subtotal['value_1'];
                                            $overall['value_2'] += $subtotal['value_2'];
                                            $overall['value_3'] += $subtotal['value_3'];
                                            $overall['value_4'] += $subtotal['value_4'];

                                            $subtotal = $total_template;    // reset subtotal
                                        }
                                        
                                        // display overall 
                                        if($key!= count($data) && $data[$key]['prior_current'] != $data[$key+1]['prior_current'])
                                        {
                                            $temp_arr = array(
                                                            'Total net undiscounted financial assets/ (liabilities)',
                                                            $this->fs_replace_content_model->negative_bracket($overall['value_1']),
                                                            $this->fs_replace_content_model->negative_bracket($overall['value_2']),
                                                            $this->fs_replace_content_model->negative_bracket($overall['value_3']),
                                                            $this->fs_replace_content_model->negative_bracket($overall['value_4'])
                                                        );

                                            $tr_item .= $this->replace_tr_template_item($tr_template_data['overall_total_desc'], $temp_arr);

                                            if($key != count($data)-1)
                                            {
                                                if(!($data[$key+1]['prior_current'] == 'prior' && $fs_company_info[0]['first_set'] == '1'))
                                                {
                                                    $tr_item .= $newline_template;
                                                }
                                            }

                                            $overall = $total_template;  // reset overall
                                        }

                                        // temporary save section name
                                        $temp_section = $value['section']; 
                                    }
                                }
                            }

                            $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item . $newline_template, $replaced_tbl_template_2);
                            if($additional_info['generate_docs_without_tags'])
                            {
                                foreach ($tr_template_data['ori_all_template'] as $at_key => $at_value) 
                                {
                                    $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                }
                            }
                            $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                        }
                    }
                }
                elseif($table_name == "Note 30 - Fair Value of assets (table_1) (group)" || $table_name == "Note 30 - Fair Value of assets (table_1) (company)")
                {
                    $replaced_tbl_template   = $replaced_tbl_value; 
                    $replaced_tbl_template_2 = $replaced_tbl_template;

                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name);
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));

                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name);

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);

                        if($is_hide_content)
                        {
                            if($additional_info['generate_docs_without_tags'])
                            {
                                $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                                $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                                $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                            }   
                            else
                            {
                                $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                                $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                            }
                        }
                        else
                        {
                            $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

                            // get templates and remove all the info first
                            $extra_info = array(
                                'hide_group_column'       => [],
                                'template_num_of_line'    => $hide_column_data['clear_tr_after_row'],
                                'replaced_tbl_template_2' => $replaced_tbl_template_2,
                                'group_type'              => $fs_company_info[0]['group_type'],
                                'table_name'              => $table_name
                            );

                            $tr_template_data = $this->build_tbl_template_tr_data($tbl_tr, $extra_info);

                            $replaced_tbl_template_2     = $tr_template_data['replaced_tbl_template_2'];
                            $last_line_space_tr_template = $tr_template_data['last_line_space'];
                            $newline_template            = $tr_template_data['newline'];

                            // set value to template
                            $tr_item = '';

                            $data = $this->get_t1_value_tr_template($table_name, $fs_company_info_id); 
                            $temp_arr = [];

                            $subtotal = array(
                                            '',
                                            0,
                                            0,
                                            0,
                                            0
                                        );

                            foreach ($data as $key => $value) 
                            {
                                if($value['is_title'])
                                {
                                    $tr_item .= $this->replace_tr_template_item($tr_template_data['title_italic'], array($value['description']));
                                }
                                else
                                {
                                    if(count($data)-1 == $key || $value['part'] != $data[$key+1]['part']) // use overall total template (show subtotal)
                                    {
                                        if(!empty($value['value_1']) && !empty($value['value_2']) && !empty($value['value_3']) && !empty($value['value_4']))
                                        {
                                            $subtotal[1] += $value['value_1'];
                                            $subtotal[2] += $value['value_2'];
                                            $subtotal[3] += $value['value_3'];
                                            $subtotal[4] += $value['value_4'];

                                            $temp_arr = array(
                                                            $value['description'],
                                                            $this->fs_replace_content_model->negative_bracket($value['value_1']),
                                                            $this->fs_replace_content_model->negative_bracket($value['value_2']),
                                                            $this->fs_replace_content_model->negative_bracket($value['value_3']),
                                                            $this->fs_replace_content_model->negative_bracket($value['value_4'])
                                                        );

                                            $tr_item .= $this->replace_tr_template_item($tr_template_data['description'], $temp_arr);
                                        }

                                        $temp_arr = array(
                                                        $subtotal[0],
                                                        $this->fs_replace_content_model->negative_bracket($subtotal[1]),
                                                        $this->fs_replace_content_model->negative_bracket($subtotal[2]),
                                                        $this->fs_replace_content_model->negative_bracket($subtotal[3]),
                                                        $this->fs_replace_content_model->negative_bracket($subtotal[4])
                                                    );

                                        $tr_item .= $this->replace_tr_template_item($tr_template_data['overall_total'], $temp_arr);

                                        $subtotal = array('', 0, 0, 0, 0);
                                    }
                                    else
                                    {
                                        if(!empty($value['value_1']) && !empty($value['value_2']) && !empty($value['value_3']) && !empty($value['value_4']))
                                        {
                                            $subtotal[1] += $value['value_1'];
                                            $subtotal[2] += $value['value_2'];
                                            $subtotal[3] += $value['value_3'];
                                            $subtotal[4] += $value['value_4'];

                                            $temp_arr = array(
                                                            $value['description'],
                                                            $this->fs_replace_content_model->negative_bracket($value['value_1']),
                                                            $this->fs_replace_content_model->negative_bracket($value['value_2']),
                                                            $this->fs_replace_content_model->negative_bracket($value['value_3']),
                                                            $this->fs_replace_content_model->negative_bracket($value['value_4'])
                                                        );

                                            $tr_item .= $this->replace_tr_template_item($tr_template_data['description'], $temp_arr);
                                        }
                                    }
                                }
                            }

                            $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $tr_item . $newline_template, $replaced_tbl_template_2);
                            if($additional_info['generate_docs_without_tags'])
                            {
                                foreach ($tr_template_data['ori_all_template'] as $at_key => $at_value) 
                                {
                                    $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
                                }
                            }
                            $replaced_xml = str_replace($replaced_tbl_template, $replaced_tbl_template_2, $replaced_xml);
                        }
                    }
                }
                elseif(
                    $table_name == "Note 23 - Provision (table_1) (first set)" || $table_name == "Note 23 - Provision (table_1) (not first set)" ||

                    $table_name == "Note 24 - Trade and other payables (table_2) (first set)" || $table_name == "Note 24 - Trade and other payables (table_2) (not first set)" ||

                    $table_name == "Note 30 - Fair Value of assets (table_1)(header)(group)" || $table_name == "Note 30 - Fair Value of assets (table_1)(header)(company)" ||

                    $table_name == "Note 31 - Financial Instrument by category (table_1) (first set)" || $table_name == "Note 31 - Financial Instrument by category (table_1) (not first set)" || 

                    $table_name == "Note 32 - Capital Management (table_1) (first set)" || $table_name == "Note 32 - Capital Management (table_1) (not first set)" ||

                    $table_name == "Note 35 - Prior year adjustment (table_1)" || 

                    $table_name == "Note 36 - Loss per ordinary share (table_1) (first set)" || $table_name == "Note 36 - Loss per ordinary share (table_1) (not first set)" ||

                    $table_name == "Note 37 - Segmental Reporting (table_1)(group)" || $table_name == "Note 37 - Segmental Reporting (table_1)(company)"
                )
                {
                    $is_hide_content = false;

                    $hide_column_data = $this->get_table_setting($table_name); 
                    $hide_column_data = array_merge($hide_column_data, $additional_info);
                    $hide_column_data = array_merge($hide_column_data, array('table_name' => $table_name));

                    $hide_table       = $this->get_table_result_hide_show($fs_company_info_id, $table_name);
                    // $fs_ntfs_template_id = $this->get_fs_ntfs_template_id($fs_company_info_id, $table_name);
                    // $main_content_checked = $this->get_checked_result_section($fs_company_info_id, $fs_ntfs_template_id);

                    if($hide_table)  // hide content
                    {
                        if($additional_info['generate_docs_without_tags'])
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = true;
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                    else // show content
                    {
                        if($additional_info['generate_docs_without_tags'] && !$is_hide_content)
                        {
                            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
                            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
                            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                        }   
                        else
                        {
                            $is_hide_content = $this->get_hide_content_condition($table_name, $fs_company_info);
                            $replaced_xml = $this->replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml);
                        }
                    }
                }
                // elseif($table_name == "Financial Risk Management - Static Table")  // Table for Financial Risk Management - Static Table
                // {
                //     // $replaced_tbl_tr = $this->replace_tbl_tr_template($tbl_tr_value, $data, '');

                //     $replaced_tbl_value = $this->hide_tbl($tbl_value);

                //     $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
                // }
            }
        }

        return $replaced_xml;
    }

    public function add_table_new_column($template, $data)
    {
        $replaced_template = $template;
        $tbl_tc = $this->get_tbl_tc_template($replaced_template);
        $replaced_new_tc = '';

        if(count($tbl_tc[0]) < $data['columns_needed'])
        {
            $new_tc = $tbl_tc[0][$data['copy_column'] - 1];

            for($i = $data['columns_needed']; $i > count($tbl_tc[0]); $i--)
            {
                preg_match_all ('/w14:paraId="(.*?)" /', $new_tc, $taken_paraId); // get w14:paraId="1234"
                $random_alphanumeric = $this->fs_replace_content_model->rand_string_without_special_char(8);    // random 8 alphanumeric

                $replaced_new_tc .= str_replace($taken_paraId[1][0], $random_alphanumeric, $new_tc);
            }

            $replaced_template = str_replace($tbl_tc[0][count($tbl_tc[0]) - 1], $tbl_tc[0][count($tbl_tc[0]) - 1] . $replaced_new_tc, $replaced_template);
        }

        return $replaced_template;
    }

    public function replace_tr_template_item($template, $item_array)
    {
        $replaced_template = $template;
        $tbl_tc = $this->get_tbl_tc_template($replaced_template);

        foreach ($tbl_tc[0] as $tbl_tc_key => $tbl_tc_value) 
        {
            $replaced_tbl_tc = $tbl_tc_value;

            if(!empty($item_array[$tbl_tc_key]))
            {
                // get wr
                $wr = $this->get_template_wr($tbl_tc_value);
                $wr_template = $wr[0];

                foreach ($wr_template as $wr_key => $wr_value) 
                {
                    $replaced_wr = $wr_value;

                    if($wr_key == 0)
                    {
                        // get wt where it is inside wr
                        $wt = $this->get_part_of_template('<w:t', 'w:t', $wr_value);

                        foreach ($wt[0] as $wt_key => $wt_value) 
                        {
                            if($wt_key == 0)
                            {
                                $replaced_wr = str_replace($wt_value, '<w:t>' . $item_array[$tbl_tc_key] . '</w:t>', $replaced_wr);
                            }
                            else
                            {
                                $replaced_wr = str_replace($wt_value, '', $replaced_wr);
                            }
                        }
                    }
                    else
                    {
                        $replaced_wr = '';
                    }   

                    $replaced_tbl_tc = str_replace($wr_value, $replaced_wr, $replaced_tbl_tc);
                }

                $replaced_template = str_replace($tbl_tc_value, $replaced_tbl_tc, $replaced_template);
            }
        }

        return $replaced_template;
    }

    public function replace_table_layout($is_hide_content, $fs_company_info, $hide_column_data, $replaced_tbl_value, $tbl_value, $replaced_xml)
    {
        // hide or show the content
        if($is_hide_content) 
        {
            // $replaced_tbl_value = $this->hide_tbl($replaced_tbl_value, true);   // $hide_needed = true;
            $replaced_tbl_value = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_tbl_value, $additional_info['generate_docs_without_tags'], 1);
            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $additional_info, $table_name);
            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);
        }
        else
        {
            $wsdt_tbl_tr = $this->get_template_wsdt($replaced_tbl_value);   

            foreach ($wsdt_tbl_tr[0] as $wsdt_tbl_tr_key => $wsdt_tbl_tr_value) // loop tr
            {
                $alias_inner_tag = $this->get_tag('w:alias', $wsdt_tbl_tr_value);
                $alias_inner_value = $this->get_attribute_value('w:val', $alias_inner_tag[0][0]);

                $inner_tagName = $alias_inner_value[1][0];

                $wsdtcontent = $this->get_template_wsdtcontent($wsdt_tbl_tr_value);  // get w:sdtContent

                if($inner_tagName == "Statement Year End" || $inner_tagName == "Statement Last Year End - End" || $inner_tagName == "Statement Year End NTA - LATEST" || $inner_tagName == "Statement Year End NTA - EARLIER")
                {
                    $new_wsdt_tbl_tr_value = '';

                    $wr_arr = $this->get_template_wr($wsdtcontent[0][0]);
                    $new_wsdtcontent = $this->loop_template($wsdtcontent[0][0], $inner_tagName, $wr_arr[0], $fs_company_info[0]['id']);
                    $new_wsdt_tbl_tr_value = str_replace($wsdtcontent[0][0], $new_wsdtcontent, $wsdt_tbl_tr_value);

                    $replaced_tbl_value = str_replace($wsdt_tbl_tr_value, $new_wsdt_tbl_tr_value, $replaced_tbl_value);
                }
            }

            $replaced_tbl_value = $this->hide_tbl($replaced_tbl_value, false);  // $hide_needed = false;
            $replaced_tbl_value = $this->hide_tbl_column($fs_company_info, $hide_column_data, $replaced_tbl_value, $replaced_xml);    // group_type, $hide_column_data, ...
            $replaced_tbl_value = $this->remove_tbl_tags_row($replaced_tbl_value, $hide_column_data, $hide_column_data['table_name']); 

            // print_r(array($replaced_tbl_value));

            $replaced_xml = str_replace($tbl_value, $replaced_tbl_value, $replaced_xml);  
        }

        return $replaced_xml;
    }

    public function replace_textarea_wsdtContent($replaced_wsdt_xml, $alias_value, $fs_company_info_id) 
    {
        // print_r($alias_value);
        $independent_aud_report_data = $this->fs_model->get_this_independent_aud_report($fs_company_info_id);
        $final_report_type  = $this->fs_model->get_final_document_type($fs_company_info_id);

        $wsdtContent = $this->get_template_wsdtcontent($replaced_wsdt_xml);

        preg_match('/<w:p .*?>/s', $wsdtContent[0][0], $wp_opening_tag);
        preg_match('/<w:pPr>.*?<\/w:pPr>/s', $wsdtContent[0][0], $wppr);

        $wp_opening_tag = $wp_opening_tag[0];   
        $wppr_tag = $wppr[0];

        $wp_template_without_closing_tag = $wp_opening_tag . $wppr_tag;

        if($alias_value == "Note 2 - Revenue content")
        {
            $p = $this->db->query("SELECT src.content
                                    FROM fs_sub_revenue sr 
                                    LEFT JOIN fs_list_sub_revenue_content src ON src.id = sr.fs_list_sub_revenue_content_id
                                    WHERE sr.fs_company_info_id = " . $fs_company_info_id . ' ORDER BY src.id');
            $p = $p->result_array();
            $wrsidRPr_id = $this->fs_replace_content_model->rand_string_without_special_char(8);

            $temp_p = '';

            if(count($p) > 0)
            {
                foreach ($p as $p_key => $p_value) 
                {
                    if(!empty($p_value['content']))
                    {
                        $temp_wr = $this->replace_paragraph_content_in_single_tag($p_value['content'], $wrsidRPr_id, $wp_template_without_closing_tag, false, false, true); 
                        $temp_p .= $temp_wr;
                    }
                }
            }
        }
        elseif($alias_value == "Note 2 - Provision content")
        {
            $p = $this->fs_notes_model->get_fs_provision_content_list($fs_company_info_id);
            $wrsidRPr_id = $this->fs_replace_content_model->rand_string_without_special_char(8);

            $temp_p = '';

            if(count($p) > 0)
            {
                foreach ($p as $p_key => $p_value) 
                {
                    if($p_value['is_shown'] == '1')
                    {
                        $temp_wr = $this->replace_paragraph_content_in_single_tag($p_value['content'], $wrsidRPr_id, $wp_template_without_closing_tag, false, false, true); 
                        $temp_p .= $temp_wr;
                    }
                }
            }
        }
        elseif($alias_value == "Note 2 - Investment properties (Model content)") // for full set FS
        {
            
        }
        elseif($alias_value == "Opinion" || $alias_value == "Opinion 2")
        {
            $wrsidRPr_id = $this->fs_replace_content_model->rand_string_without_special_char(8);

            $temp_p = '';

            if(count($independent_aud_report_data) > 0)
            {
                
                if($independent_aud_report_data[0]['fs_opinion_type_id'] == 4)
                {
                    $content = $independent_aud_report_data[0]['opinion_name'] . ' of Opinion';
                }
                elseif($independent_aud_report_data[0]['fs_opinion_type_id'] == 1)
                {
                    $content = "Opinion";
                }
                else
                {
                    $content = $independent_aud_report_data[0]['opinion_name'] . ' ';
                }


                $temp_wr = $this->replace_paragraph_content_in_single_tag($content, $wrsidRPr_id, $wp_template_without_closing_tag, false, true, true); 
                $temp_p .= $temp_wr;
            }
        }
        elseif($alias_value == "Opinion content")
        {
            $wrsidRPr_id = $this->fs_replace_content_model->rand_string_without_special_char(8);

            $temp_p = '';

            if(count($independent_aud_report_data) > 0)
            {
                // $content = $data[0]['opinion_fixed'] . "............2............." . $data[0]['opinion_fixed_2'];
                if($independent_aud_report_data[0]['opinion_fixed_2']){
                    $content = strip_tags($independent_aud_report_data[0]['opinion_fixed']).$this->in_quickpart_new_paragraph(strip_tags($independent_aud_report_data[0]['opinion_fixed_2']));
                }
                else
                {
                    $content = strip_tags($independent_aud_report_data[0]['opinion_fixed']);
                }

                $temp_wr = $this->replace_paragraph_content_in_single_tag($content, $wrsidRPr_id, $wp_template_without_closing_tag, false, false, true); 
                $temp_p .= $temp_wr;
            
            }
        }
        elseif($alias_value == "Basis for opinion - content")
        {
            $wrsidRPr_id = $this->fs_replace_content_model->rand_string_without_special_char(8);

            $temp_p = '';

            if(count($independent_aud_report_data) > 0)
            {
                if($independent_aud_report_data[0]['fs_opinion_type_id'] == 1)
                {
                    $content = $independent_aud_report_data[0]['basic_for_opinion_fixed'];
                }
                elseif($independent_aud_report_data[0]['fs_opinion_type_id'] == 4)
                {
                    $content = $independent_aud_report_data[0]['basic_for_opinion'] . ' ' . $independent_aud_report_data[0]['basic_for_opinion_fixed'];
                }
                else
                {
                    $content = $independent_aud_report_data[0]['basic_for_opinion'] .$this->in_quickpart_new_paragraph($independent_aud_report_data[0]['basic_for_opinion_fixed']) ;
                }

                $temp_wr = $this->replace_paragraph_content_in_single_tag($content, $wrsidRPr_id, $wp_template_without_closing_tag, false, false, true); 
                $temp_p .= $temp_wr;
            
            }
            
        }
        elseif($alias_value == "Key audit matter")
        {
            $wrsidRPr_id = $this->fs_replace_content_model->rand_string_without_special_char(8);

            $temp_p = '';

            if(count($independent_aud_report_data) > 0)
            {
                if($independent_aud_report_data[0]['fs_opinion_type_id'] != 4)
                {
                    if($independent_aud_report_data[0]['fs_opinion_type_id'] == 3)
                    {
                        $content = 'Key Audit Matters'.$this->in_quickpart_new_paragraph($independent_aud_report_data[0]['key_audit_matter']);
                    }
                    else
                    {
                        if(!empty($independent_aud_report_data[0]['key_audit_matter_input']))
                        {
                            $content = 'Key Audit Matter'.$this->in_quickpart_new_paragraph($independent_aud_report_data[0]['key_audit_matter']).$this->in_quickpart_new_paragraph($independent_aud_report_data[0]['key_audit_matter_input']);
                        }
                        else
                        {
                            $content = 'Key Audit Matters'.$this->in_quickpart_new_paragraph($independent_aud_report_data[0]['key_audit_matter']);
                        }
                    }

                    $temp_wr = $this->replace_paragraph_content_in_single_tag($content, $wrsidRPr_id, $wp_template_without_closing_tag, false, true, true); 
                    $temp_p .= $temp_wr;
                }
                // else
                // {
                //     $content = '';
                // }
            }
        }
        elseif($alias_value == "Other information")
        {
            $wrsidRPr_id = $this->fs_replace_content_model->rand_string_without_special_char(8);

            $temp_p = '';

            if(count($independent_aud_report_data) > 0)
            {
                if($independent_aud_report_data[0]['fs_opinion_type_id'] == 1)
                {
                    $temp_from_db = $this->fs_model->get_fs_doc_template_master(3, 'Other information');

                    $content = "Other information";

                    $temp_wr = $this->replace_paragraph_content_in_single_tag($content, $wrsidRPr_id, $wp_template_without_closing_tag, false, true, true); 
                    $temp_p .= $temp_wr;
                }
                // else
                // {
                //     $content = '';
                // }
            
            }

        }
        elseif($alias_value == "Other information content")
        {
            $wrsidRPr_id = $this->fs_replace_content_model->rand_string_without_special_char(8);

            $temp_p = '';

            if(count($independent_aud_report_data) > 0)
            {
                if($independent_aud_report_data[0]['fs_opinion_type_id'] == 1)
                {
                    $temp_from_db = $this->fs_model->get_fs_doc_template_master(3, 'Other information');

                    $content = $temp_from_db[0]->content_word;
                    // echo $content;

                    if(!empty($independent_aud_report_data[0]['emphasis_of_matter']))
                    {
                        $content .= $this->in_quickpart_new_paragraph($independent_aud_report_data[0]['emphasis_of_matter']);
                    }

                    if(!empty($data[0]['other_matters']))
                    {
                        $content .= $this->in_quickpart_new_paragraph( $independent_aud_report_data[0]['other_matters']);
                    }

                    if(!empty($content))
                    {
                        $temp_wr = $this->replace_paragraph_content_in_single_tag($content, $wrsidRPr_id, $wp_template_without_closing_tag, false, false, true); 
                        $temp_p .= $temp_wr;
                    }
                }
            }

        }
        elseif($alias_value == "Report on Other Legal and Regulatory Requirements")
        {
            $wrsidRPr_id = $this->fs_replace_content_model->rand_string_without_special_char(8);
            $temp_from_db = $this->fs_model->get_fs_doc_template_master(3, 'Report on Other Legal and Regulatory Requirements');

            $temp_p = '';

            if(count($independent_aud_report_data) > 0)
            {
                if($independent_aud_report_data[0]['fs_opinion_type_id'] == 4)
                {
                    if($fs_company_info[0]['group_type'] == 1)
                    {
                        $content = $temp_from_db[2]->content_word;
                    }
                    else
                    {
                        $content = $temp_from_db[3]->content_word;
                    }
                }
                else
                {
                    if($fs_company_info[0]['group_type'] == 1)
                    {
                        $content = $temp_from_db[0]->content_word;
                    }
                    else
                    {
                        $content = $temp_from_db[1]->content_word;
                    }
                }
                // if($independent_aud_report_data[0]['fs_opinion_type_id'] == 1)
                // {
                //     $temp_from_db = $this->fs_model->get_fs_doc_template_master(3, 'Other information');

                //     $content = $temp_from_db[0]->content_word;

                //     if(!empty($independent_aud_report_data[0]['emphasis_of_matter']))
                //     {
                //         $content .= $this->in_quickpart_new_paragraph($independent_aud_report_data[0]['emphasis_of_matter']);
                //     }

                //     if(!empty($data[0]['other_matters']))
                //     {
                //         $content .= $this->in_quickpart_new_paragraph( $independent_aud_report_data[0]['other_matters']);
                //     }
                // }
                // else
                // {
                //     $content = '';
                // }
                              
                $temp_wr = $this->replace_paragraph_content_in_single_tag($content, $wrsidRPr_id, $wp_template_without_closing_tag, false, false, true); 
                $temp_p .= $temp_wr;
            }
        }
        elseif(
            $alias_value == "Note 3 - Employee benefits expense (content)" 
            || $alias_value == "Note 5 - Tax expense (textarea 1)" 
            || $alias_value == "Note 5 - Tax expense (content - company has unabsorbed tax losses)"
            || $alias_value == "Note 6 - Investment in subsidiaries (i) (content)"
            || ($alias_value == "Note 7 - Investment in associates (1)" && $final_report_type != 1)
            || $alias_value == "Note 8 - Investment in joint venture (1)" 
            || $alias_value == "Note 9 - Intangible assets (textarea)"
            || $alias_value == "Note 10 - Insured benefits (1)" 
            || $alias_value == "Note 12 - Property, plant and equipment - 1"
            || $alias_value == "Note 13 - Available for sale (1)"
            || $alias_value == "Note 14 - Inventories (1)"
            || $alias_value == "Note 15 - Contract assets and contract liabilities (2)"
            || $alias_value == "Note 18 - Cash and short-term deposits (1)"
            || $alias_value == "Note 19 - Share capital (1)" 
            || $alias_value == "Note 33 - Events occuring after the reporting period (1)"
            || $alias_value == "Note 27 - Commitments (ii) (content)"
            || $alias_value == "Note 27 - Commitments (iii) (content)"
            || $alias_value == "Note 28 - Contingencies (ii) (1)"
            || $alias_value == "Note 26 - Related party transactions (ii) - content"
            || $alias_value == "Note 29.3 - Financial Risk Management (2)" 
            || $alias_value == "Note 29.3 - Financial Risk Management (3)" 
            || $alias_value == "Note 29.3 - Financial Risk Management (4)" 
            || $alias_value == "Note 29.3 - Financial Risk Management (5)" 
        )
        {
            $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);
            $wrsidRPr_id = $this->fs_replace_content_model->rand_string_without_special_char(8);

            $insert_newline_at_last = true;

            $temp_p = '';

            if(count($p) > 0)
            {
                foreach ($p as $p_key => $p_value) 
                {
                    $content = '';

                    if($alias_value == "Note 3 - Employee benefits expense (content)")
                    {
                        $content = $p_value['share_option_plans_content'];
                    }
                    elseif($alias_value == "Note 5 - Tax expense (textarea 1)" || $alias_value == "Note 5 - Tax expense (content - company has unabsorbed tax losses)")
                    {
                        $content = $p_value['text_content'];
                    }
                    else
                    {
                        $content = $p_value['content'];
                    }

                    $temp_wr = $this->replace_paragraph_content_in_single_tag($content, $wrsidRPr_id, $wp_template_without_closing_tag, false, false, $insert_newline_at_last); 
                    $temp_p .= $temp_wr;
                }

                if($alias_value == "Note 28 - Contingencies (ii) (1)")
                {
                    // remove <w:pStyle w:val="ListParagraph"\/> from the last <w:p>
                    preg_match_all('/<w:p .*?<\/w:p>/s', $temp_p, $wp_opening_tag_specific);
                    preg_match('/<w:pPr>.*?<\/w:pPr>/s', $wp_opening_tag_specific[0][count($wp_opening_tag_specific[0]) - 1], $wppr_specific);

                    $wppr_specific_replaced = preg_replace('/<w:pStyle w:val="ListParagraph"\/>/', '', $wppr_specific[0]);
                    $wppr_specific_replaced = preg_replace('/<w:numPr>(.*?)<\/w:numPr>/s', '', $wppr_specific_replaced);

                    $wp_opening_tag_specific_replaced = str_replace($wppr_specific[0], $wppr_specific_replaced, $wp_opening_tag_specific[0][count($wp_opening_tag_specific[0]) - 1]);

                    $temp_p = str_replace($wp_opening_tag_specific[0][count($wp_opening_tag_specific[0]) - 1], $wp_opening_tag_specific_replaced, $temp_p);
                }
            }
        }
        elseif(
            $alias_value == "Note 9 - Intangible assets (1)"              || 
            $alias_value == "Note 11 - Investment properties (3)"         || 
            $alias_value == "Note 12 - Property, plant and equipment (1)" || 
            $alias_value == "Note 16 - Trade and other receivables (1)"   || 
            $alias_value == "Note 16 - Trade and other receivables (6)"   || 
            $alias_value == "Note 16 - Trade and other receivables (4)"   || 
            $alias_value == "Note 22 - Loans and borrowings (2)"          || 
            $alias_value == "Note 23 - Provision (1)") 
        {
            $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

            // if($alias_value == "Note 22 - Loans and borrowings (2)")
            // {
            //     print_r($p);
            // }

            $wrsidRPr_id = $this->fs_replace_content_model->rand_string_without_special_char(8);

            $temp_p = '';

            if(count($p) > 0)
            {
                foreach ($p as $p_key => $p_value) 
                {
                    $temp_wr = '';
                    $wrsidRPr_id = $this->fs_replace_content_model->rand_string_without_special_char(8);

                    // for title
                    if(!empty($p_value['section_name']))
                    {
                        $type = array(
                            'br'           => false,
                            'bold'         => false,
                            'italic'       => false,
                            'text'         => true,
                            'text_content' => $p_value['section_name'],
                            'wrsidRPr'     => $wrsidRPr_id,
                            'underline'    => true
                        );

                        $temp_wr .= $wp_template_without_closing_tag;
                        $temp_wr .= $this->xml_code_template($type) . '</w:p>';

                        $temp_p .= $temp_wr;
                    }

                    $content = '';
                    $content = $p_value['content'];

                    $temp_wr = $this->replace_paragraph_content_in_single_tag($content, $wrsidRPr_id, $wp_template_without_closing_tag, false, false, true); 

                    if($alias_value == "Note 16 - Trade and other receivables (1)" || $alias_value == "Note 16 - Trade and other receivables (6)")
                    {
                        if($p_key == (count($p)-1)) // remove new paragraph
                        {
                            preg_match_all('/<w:p .*?<\/w:p>/s', $temp_wr, $wp_tag_newline);
                            $temp_wr = str_replace($wp_tag_newline[0][count($wp_tag_newline[0]) - 1], '', $temp_wr);
                        }
                    }

                    $temp_p .= $temp_wr;
                }
            }
        }
        elseif($alias_value == "Note 16 - Trade and other receivables (3)")
        {
            $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

            $wrsidRPr_id = $this->fs_replace_content_model->rand_string_without_special_char(8);

            $temp_p = '';

            if(count($p) > 0)
            {
                $temp_section_name = '';

                foreach ($p as $p_key => $p_value) 
                {
                    $temp_wr = '';
                    $content = '';
                    $wrsidRPr_id = $this->fs_replace_content_model->rand_string_without_special_char(8);

                    // for title
                    if(!empty($p_value['section_name']) && $temp_section_name != $p_value['section_name'])
                    {
                        $type = array(
                            'br'           => false,
                            'bold'         => false,
                            'italic'       => false,
                            'text'         => true,
                            'text_content' => $p_value['section_name'],
                            'wrsidRPr'     => $wrsidRPr_id,
                            'underline'    => true
                        );

                        if($p[$p_key]['is_fixed'])
                        {
                            $type['underline'] = false;
                        }

                        $temp_wr .= $wp_template_without_closing_tag;
                        $temp_wr .= $this->xml_code_template($type) . '</w:p>';

                        $temp_p .= $temp_wr;
                    }
                    
                    if($p_value['is_fixed'])
                    {
                        $content = $p_value['fixed_content'];
                    }
                    else
                    {
                        $content = $p_value['content'];
                    }

                    $temp_wr = $this->replace_paragraph_content_in_single_tag($content, $wrsidRPr_id, $wp_template_without_closing_tag, false, false, true); 

                    if($p_value['is_checked'])
                    {   
                        if($p_key != (count($p)-1)) // remove new paragraph
                        {
                            preg_match_all('/<w:p .*?<\/w:p>/s', $temp_wr, $wp_tag_newline);
                            $temp_wr = str_replace($wp_tag_newline[0][count($wp_tag_newline[0]) - 1], '', $temp_wr);

                            if(!$p_value['is_fixed'])
                            {
                                $temp_wr = str_replace('<w:pPr>', '<w:pPr><w:pStyle w:val="ListParagraph"/><w:keepNext/><w:keepLines/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="16"/></w:numPr><w:adjustRightInd/><w:ind w:left="1080"/><w:jc w:val="both"/><w:rPr>
                            <w:rFonts w:asciiTheme="majorHAnsi" w:hAnsiTheme="majorHAnsi" w:cstheme="majorHAnsi"/><w:sz w:val="22"/><w:szCs w:val="22"/></w:rPr>', $temp_wr);    // add ListParagraph for sub items
                            }
                        }
                        else
                        {
                            $temp_wr = str_replace('<w:pPr>', '<w:pPr><w:pStyle w:val="ListParagraph"/><w:keepNext/><w:keepLines/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="16"/></w:numPr><w:adjustRightInd/><w:ind w:left="1080"/><w:jc w:val="both"/><w:rPr>
                            <w:rFonts w:asciiTheme="majorHAnsi" w:hAnsiTheme="majorHAnsi" w:cstheme="majorHAnsi"/><w:sz w:val="22"/><w:szCs w:val="22"/></w:rPr>', $temp_wr);    // add ListParagraph for sub items

                            // replace ListParagraph for last new line
                            preg_match_all('/<w:p .*?<\/w:p>/s', $temp_wr, $wp_tag_line);
                            $replaced_last_wp_tag = str_replace('<w:pStyle w:val="ListParagraph"/><w:keepNext/><w:keepLines/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="16"/></w:numPr><w:adjustRightInd/><w:ind w:left="1080"/>', '', $wp_tag_line[0][count($wp_tag_line[0]) - 1]);

                            $temp_wr = str_replace($wp_tag_line[0][count($wp_tag_line[0]) - 1], $replaced_last_wp_tag, $temp_wr);
                        }
                    }
                    $temp_p .= $temp_wr;

                    $temp_section_name = $p_value['section_name'];  // save temp section name
                }
            }
        }
        elseif($alias_value == "Note 22 - Loans and borrowings (3)")
        {
            $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

            $wrsidRPr_id = $this->fs_replace_content_model->rand_string_without_special_char(8);

            $temp_p = '';

            if(count($p) > 0)
            {
                $temp_section_name = '';

                foreach ($p as $p_key => $p_value) 
                {
                    $temp_wr = '';
                    $content = '';
                    $wrsidRPr_id = $this->fs_replace_content_model->rand_string_without_special_char(8);

                    // for title
                    if(!empty($p_value['section_name']) && $temp_section_name != $p_value['section_name'])
                    {
                        $type = array(
                            'br'           => false,
                            'bold'         => false,
                            'italic'       => false,
                            'text'         => true,
                            'text_content' => $p_value['section_name'],
                            'wrsidRPr'     => $wrsidRPr_id,
                            'underline'    => true
                        );

                        if($p[$p_key]['is_fixed'])
                        {
                            $type['underline'] = false;
                        }

                        $temp_wr .= $wp_template_without_closing_tag;
                        $temp_wr .= $this->xml_code_template($type) . '</w:p>';

                        $temp_p .= $temp_wr;
                    }
                    
                    if($p_value['is_fixed'])
                    {
                        $content = $p_value['fixed_content'];
                    }
                    else
                    {
                        $content = $p_value['content'];
                    }

                    $temp_wr = $this->replace_paragraph_content_in_single_tag($content, $wrsidRPr_id, $wp_template_without_closing_tag, false, false, true); 

                    if($p_value['is_fixed'])
                    {
                        if($p_key != (count($p)-1)) // remove new paragraph
                        {
                            preg_match_all('/<w:p .*?<\/w:p>/s', $temp_wr, $wp_tag_newline);
                            $temp_wr = str_replace($wp_tag_newline[0][count($wp_tag_newline[0]) - 1], '', $temp_wr);

                            if(!$p_value['is_fixed_title'])
                            {
                                $temp_wr = str_replace('<w:pPr>', '<w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="4"/></w:numPr><w:adjustRightInd/><w:ind w:left="1440" w:hanging="709"/>', $temp_wr);    // add ListParagraph for sub items
                            }
                        }
                        else
                        {
                            $temp_wr = str_replace('<w:pPr>', '<w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="4"/></w:numPr><w:adjustRightInd/><w:ind w:left="1440" w:hanging="709"/>', $temp_wr);    // add ListParagraph for sub items

                            // replace ListParagraph for last new line
                            preg_match_all('/<w:p .*?<\/w:p>/s', $temp_wr, $wp_tag_line);
                            $replaced_last_wp_tag = str_replace('<w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="4"/></w:numPr><w:adjustRightInd/><w:ind w:left="1440" w:hanging="709"/>', '', $wp_tag_line[0][count($wp_tag_line[0]) - 1]);

                            $temp_wr = str_replace($wp_tag_line[0][count($wp_tag_line[0]) - 1], $replaced_last_wp_tag, $temp_wr);
                        }
                    }
                    $temp_p .= $temp_wr;

                    $temp_section_name = $p_value['section_name'];  // save temp section name
                }
            }
        }
        elseif($alias_value == "Note 28 - Contingencies (i) (1)") 
        {
            $p = $this->get_textboxes_ntfs_values($alias_value, $fs_company_info_id);

            $wrsidRPr_id = $this->fs_replace_content_model->rand_string_without_special_char(8);

            $temp_p = '';

            if(count($p) > 0)
            {
                $temp_section_name = '';

                foreach ($p as $p_key => $p_value) 
                {
                    $temp_wr = '';
                    $content = '';
                    $wrsidRPr_id = $this->fs_replace_content_model->rand_string_without_special_char(8);

                    // for title
                    if(!empty($p_value['section_name']) && $temp_section_name != $p_value['section_name'])
                    {
                        $type = array(
                            'br'           => false,
                            'bold'         => false,
                            'italic'       => false,
                            'text'         => true,
                            'text_content' => $p_value['section_name'],
                            'wrsidRPr'     => $wrsidRPr_id,
                            'underline'    => true
                        );

                        $temp_wr .= $wp_template_without_closing_tag;
                        $temp_wr .= $this->xml_code_template($type) . '</w:p>';

                        $temp_p .= $temp_wr;
                    }

                    $content = $p_value['content'];

                    $temp_wr = $this->replace_paragraph_content_in_single_tag($content, $wrsidRPr_id, $wp_template_without_closing_tag, false, false, true); 

                    if($p_value['is_sub'])
                    {
                        if($p_key != (count($p)-1)) // remove new paragraph
                        {
                            preg_match_all('/<w:p .*?<\/w:p>/s', $temp_wr, $wp_tag_newline);
                            $temp_wr = str_replace($wp_tag_newline[0][count($wp_tag_newline[0]) - 1], '', $temp_wr);

                            if(!$p_value['is_fixed_title'])
                            {
                                $temp_wr = str_replace('<w:pPr>', '<w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="17"/></w:numPr><w:adjustRightInd/><w:ind w:left="1080"/>', $temp_wr);    // add ListParagraph for sub items
                            }
                        }
                        else
                        {
                            $temp_wr = str_replace('<w:pPr>', '<w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="17"/></w:numPr><w:adjustRightInd/><w:ind w:left="1080"/>', $temp_wr);    // add ListParagraph for sub items

                            // replace ListParagraph for last new line
                            preg_match_all('/<w:p .*?<\/w:p>/s', $temp_wr, $wp_tag_line);
                            $replaced_last_wp_tag = str_replace('<w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="17"/></w:numPr><w:adjustRightInd/><w:ind w:left="1080"/>', '', $wp_tag_line[0][count($wp_tag_line[0]) - 1]);

                            $temp_wr = str_replace($wp_tag_line[0][count($wp_tag_line[0]) - 1], $replaced_last_wp_tag, $temp_wr);
                        }
                    }
                    $temp_p .= $temp_wr;
                    
                    $temp_section_name = $p_value['section_name'];  // save temp section name
                }
            }
        }
        elseif($alias_value == "Note 34 - Comparative Figures (1)")
        {
            $p = $this->db->query("SELECT * FROM fs_comparative_figures WHERE fs_company_info_id=" . $fs_company_info_id);
            $p = $p->result_array();
            
            $wrsidRPr_id = $this->fs_replace_content_model->rand_string_without_special_char(8);

            $temp_p = '';

            if(count($p) > 0)
            {
                if(!empty($p[0]['content']))
                {
                    $temp_wr = $this->replace_paragraph_content_in_single_tag($p[0]['content'], $wrsidRPr_id, $wp_template_without_closing_tag, false, false, true); 
                    $temp_p .= $temp_wr;
                }
                else
                {
                    $temp_wr = $this->replace_paragraph_content_in_single_tag("<Empty content>", $wrsidRPr_id, $wp_template_without_closing_tag, false, false, true); 
                    $temp_p .= $temp_wr;
                }
            }
            else
            {
                $temp_wr = $this->replace_paragraph_content_in_single_tag("<Empty content>", $wrsidRPr_id, $wp_template_without_closing_tag, false, false, true); 
                $temp_p .= $temp_wr;
            }
        }

        if(!empty($temp_p))
        {
            $replaced_wsdt_xml = str_replace($wsdtContent[0][0], '<w:sdtContent>' . $temp_p . '</w:sdtContent>', $replaced_wsdt_xml);
        }

        return $replaced_wsdt_xml;
    } 

    public function replace_paragraph_content_in_single_tag($content, $wrsidRPr_id, $wp_template_without_closing_tag, $bold, $italic=false, $newline_at_last) 
    {
        $model_content = nl2br($content);
        $br_check_model_content = explode("<br />", $model_content);

        $wp_closing_tag = '';

        if(!empty($wp_template_without_closing_tag))
        {
            $wp_closing_tag = '</w:p>';
        }

        if(count($br_check_model_content) > 1)
        {
            $temp_wr = '';

            foreach ($br_check_model_content as $key => $value) 
            {
                $value = trim(preg_replace('/\s\s+/', ' ', $value));

                if(!empty($value))
                {
                    $type = array(
                        'br'           => false,
                        'bold'         => $bold,
                        'italic'       => $italic,
                        'text'         => true,
                        'text_content' => $value,
                        // 'wrsidRPr'     => '00F27FAE'
                        'wrsidRPr'     => $wrsidRPr_id,
                        'underline'    => false
                    );

                    $temp_wr .= $wp_template_without_closing_tag;
                    $temp_wr .= $this->xml_code_template($type) . $wp_closing_tag;

                    if($key == (count($br_check_model_content) - 1))
                    {
                        if($newline_at_last)
                        {
                            $temp_wr .= $wp_template_without_closing_tag . $wp_closing_tag;    // new line
                        }
                    }
                }
                else
                {
                    if($newline_at_last)
                    {
                        $temp_wr .= $wp_template_without_closing_tag . $wp_closing_tag;    // new line
                    }
                }
            }
        }
        else
        {
            $temp_wr = '';
            $value = trim(preg_replace('/\s\s+/', ' ', $content));

            $type = array(
                'br'           => false,
                'bold'         => $bold,
                'italic'       => $italic,
                'text'         => true,
                'text_content' => $value,
                // 'wrsidRPr'     => '00F27FAE'
                'wrsidRPr'     => $wrsidRPr_id,
                'underline'    => false
            );

            $temp_wr  = $wp_template_without_closing_tag;
            $temp_wr .= $this->xml_code_template($type) . $wp_closing_tag;
            $temp_wr .= $wp_template_without_closing_tag . $wp_closing_tag;    // new line

            // if(($p_key != (count($p) - 1)) && ($key != (count($br_check_model_content) - 1)))
            // {
            //     $temp_wr .= $wp_template_without_closing_tag . '</w:p>';    // new line
            // }
        }

        return $temp_wr;
    }  

    public function build_wr_with_br_tag_without_paragraph($content) // without paragraph
    {
        $model_content = nl2br($content);
        $br_check_model_content = explode("<br />", $model_content);

        if(count($br_check_model_content) > 1)
        {
            $temp_wr = '';

            foreach ($br_check_model_content as $key => $value) 
            {
                $value = trim(preg_replace('/\s\s+/', ' ', $value));

                if(!empty($value))
                {
                    $type = array(
                        'br'           => true,
                        'bold'         => $bold,
                        'italic'       => $italic,
                        'text'         => true,
                        'text_content' => $value,
                        'wrsidRPr'     => $wrsidRPr_id,
                        'underline'    => false
                    );

                    $temp_wr .= $this->xml_code_template($type);
                }
            }
        }
        else
        {
            $temp_wr = '';
            $value = trim(preg_replace('/\s\s+/', ' ', $content));

            $type = array(
                'br'           => false,
                'bold'         => $bold,
                'italic'       => $italic,
                'text'         => true,
                'text_content' => $value,
                // 'wrsidRPr'     => '00F27FAE'
                'wrsidRPr'     => $wrsidRPr_id,
                'underline'    => false
            );

            $temp_wr .= $this->xml_code_template($type);
        }

        return $temp_wr;
    }

    public function in_quickpart_new_paragraph($content){
        return '</w:t></w:r></w:p><w:p><w:pPr><w:pStyle w:val="BlockText"/><w:ind w:left="0"/><w:rPr><w:rFonts w:asciiTheme="majorHAnsi" w:hAnsiTheme="majorHAnsi" w:cstheme="majorHAnsi"/><w:szCs w:val="22"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Calibri" w:hAnsi="Calibri" w:cs="Calibri"/><w:sz w:val="22"/><w:szCs w:val="22"/></w:rPr><w:t><w:br/>' . $content;
    }

    public function remove_insert_page_break($wsdt_xml, $alias_value, $fs_company_info_id)
    {
        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);
        $replaced_wsdt_xml = $wsdt_xml;
        $is_page_break = true;   // if false, we remove the page break, else insert if not exist page break tag

        if($alias_value == "Page break - Statement of changes in equity")
        {
            if($fs_company_info[0]['group_type'] == 1)
            {
                $is_page_break = false;
            }
        }

        // Add or remove page break.
        preg_match_all('/<w:br.*?\/>/', $replaced_wsdt_xml, $br_tag);

        if($is_page_break == true)
        {
            // print_r(array('true - remove_insert_page_break function' ));
            if(count($br_tag[0]) > 0) {}
            else
            {
                $replaced_wsdt_xml = preg_replace('/<w:p .*?<\/w:p>/s', '', $replaced_wsdt_xml);
                $replaced_wsdt_xml = str_replace('</w:sdtContent>', '<w:p ><w:r><w:br w:type="page" /></w:r></w:p></w:sdtContent>', $replaced_wsdt_xml);
            }

            $replaced_wsdt_xml = $this->vanish_template($replaced_wsdt_xml, 0);
        }
        else
        {
            // print_r(array('false'));
            $replaced_wsdt_xml = preg_replace('/<w:p .*?<\/w:p>/s', '', $replaced_wsdt_xml);
            $replaced_wsdt_xml = str_replace('</w:sdtContent>', '<w:p ><w:pPr><w:rPr><w:vanish/></w:rPr></w:pPr><w:r><w:rPr><w:vanish/></w:rPr><w:t>Page break tag</w:t></w:r></w:p></w:sdtContent>', $replaced_wsdt_xml);
            $replaced_wsdt_xml = $this->vanish_template($replaced_wsdt_xml, 1);
        }

        // print_r(array($replaced_wsdt_xml));

        return $replaced_wsdt_xml;
    }

    public function remove_insert_section_page_break($wsdt_xml, $alias_value, $fs_company_info_id, $additional_info)
    {
        // print_r(array($alias_value));
        // print_r(array($wsdt_xml));

        // $portrait_template  = '<w:p><w:pPr><w:sectPr><w:pgSz w:w="11909" w:h="16834"/></w:sectPr></w:pPr></w:p>';

        // $landscape_template = '<w:p><w:pPr><w:sectPr><w:pgSz w:w="16834" w:h="11909" w:orient="landscape"/></w:sectPr></w:pPr></w:p>';

        $final_document_type = $this->fs_model->get_final_document_type($fs_company_info_id);

        $remove_section_break = true;

        $portrait_template  = '<w:pgSz w:w="11909" w:h="16834"/>';
        $landscape_template = '<w:pgSz w:w="16834" w:h="11909" w:orient="landscape"/>';

        $page_margin = '<w:pgMar w:top="1350" w:right="1109" w:bottom="1800" w:left="1440" w:header="720" w:footer="720" w:gutter="0"/>';

        $replaced_wsdt_xml  = $wsdt_xml;
        $section_page_break = $portrait_template;

        if($alias_value == "Section Break - Statement of changes in equity")
        {   
            $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);
            $remove_section_break = false;

            if($fs_company_info[0]['group_type'] == 1)  // for company
            {
                $data_company = $this->get_state_changes_in_equity_data($fs_company_info_id, 'company');

                if(count($data_company['fs_state_changes_in_equity_header']) > 5)
                {
                    $section_page_break = $landscape_template;
                }
                else
                {
                    $section_page_break = $portrait_template;
                }
            }   
            else // for group
            {
                $data_group = $this->get_state_changes_in_equity_data($fs_company_info_id, 'group');
                $data_company = $this->get_state_changes_in_equity_data($fs_company_info_id, 'company');

                if(count($data_group['fs_state_changes_in_equity_header']) > 5 || count($data_company['fs_state_changes_in_equity_header']) > 5)
                {
                    $section_page_break = $landscape_template;
                }
                else
                {
                    $section_page_break = $portrait_template;
                }
            }

            $replaced_wsdt_xml = preg_replace('/<w:pgSz([^>]+)\/>/s', $section_page_break, $wsdt_xml);
        }
        else
        {
            $checked = $this->note_part_is_checked($alias_value, $fs_company_info_id);

            if($checked)
            {
                if($alias_value == "Section Break - (Note 9 - Intangible assets) - start" || $alias_value == "Section Break - (Note 9 - Intangible assets) - end")
                {   
                    $data = $this->db->query("SELECT * FROM fs_intangible_assets_ntfs_1_header WHERE fs_company_info_id=" . $fs_company_info_id);
                    $data = $data->result_array();

                    if(count($data) > 0)
                    {
                        $remove_section_break = false;
                        $data_titles = explode(',', $data[0]['header_titles']);

                        if(count($data_titles) > 4)
                        {
                            $section_page_break = $landscape_template;
                        }
                        else
                        {
                            $section_page_break = $portrait_template;
                            $remove_section_break = true;
                        }
                    }
                }
                elseif($alias_value == "Section Break - (Note 11 - Investment properties) - start" || $alias_value == "Section Break - (Note 11 - Investment properties) - end")
                {
                    $data = $this->db->query("SELECT * FROM fs_investment_properties_ntfs_1_header WHERE fs_company_info_id=" . $fs_company_info_id);
                    $data = $data->result_array();

                    if(count($data) > 0)
                    {
                        $remove_section_break = false;
                        $data_titles = explode(',', $data[0]['header_titles']);

                        if(count($data_titles) > 3)
                        {
                            $section_page_break = $landscape_template;
                        }
                        else
                        {
                            $section_page_break = $portrait_template;
                            $remove_section_break = true;
                        }
                    }
                }
                elseif($alias_value == "Section Break - (Note 11 - Investment properties - table 3) - start" || $alias_value == "Section Break - (Note 11 - Investment properties - table 3) - end")
                {
                    $data = $this->db->query("SELECT * FROM fs_investment_properties_ntfs_3_header WHERE fs_company_info_id=" . $fs_company_info_id);
                    $data = $data->result_array();

                    if(count($data) > 0)
                    {
                        $remove_section_break = false;
                        $data_titles = explode(',', $data[0]['header_titles']);

                        if(count($data_titles) > 3)
                        {
                            $section_page_break = $landscape_template;
                        }
                        else
                        {
                            $section_page_break = $portrait_template;
                            $remove_section_break = true;
                        }
                    }
                }
                elseif($alias_value == "Section Break - (Note 12 - Property, plant and equipment) - start" || $alias_value == "Section Break - (Note 12 - Property, plant and equipment) - end")
                {
                    $data = $this->db->query("SELECT * FROM fs_ppe_ntfs_1_header WHERE fs_company_info_id=" . $fs_company_info_id);
                    $data = $data->result_array();

                    if(count($data) > 0)
                    {
                        $remove_section_break = false;
                        $data_titles = explode(',', $data[0]['header_titles']);

                        if(count($data_titles) > 3)
                        {
                            $section_page_break = $landscape_template;
                        }
                        else
                        {
                            $section_page_break = $portrait_template;
                            $remove_section_break = true;
                        }
                    }
                }
                elseif($alias_value == "Section Break - (Note 29.4 - Financial Risk Management (table_1) (group)) - start" || $alias_value == "Section Break - (Note 29.4 - Financial Risk Management (table_1) (group)) - end")
                {
                    $data = $this->db->query("SELECT * FROM fs_financial_risk_management_s4_t1_header WHERE fs_company_info_id=" . $fs_company_info_id);
                    $data = $data->result_array();

                    if(count($data) > 0)
                    {
                        $remove_section_break = false;
                        $data_titles = explode(',', $data[0]['header_titles']);

                        if(count($data_titles) > 3)
                        {
                            $section_page_break = $landscape_template;
                        }
                        else
                        {
                            $section_page_break = $portrait_template;
                            $remove_section_break = true;
                        }
                    }
                }
                elseif($alias_value == "Section Break - (Note 29.4 - Financial Risk Management (table_1) (company)) - start" || $alias_value == "Section Break - (Note 29.4 - Financial Risk Management (table_1) (company)) - end")
                {
                    $data = $this->db->query("SELECT * FROM fs_financial_risk_management_s4_t2_header WHERE fs_company_info_id=" . $fs_company_info_id);
                    $data = $data->result_array();

                    if(count($data) > 0)
                    {
                        $remove_section_break = false;
                        $data_titles = explode(',', $data[0]['header_titles']);

                        if(count($data_titles) > 3)
                        {
                            $section_page_break = $landscape_template;
                        }
                        else
                        {
                            $section_page_break = $portrait_template;
                            $remove_section_break = true;
                        }
                    }
                }
            }
        }

        // remove section break or add section break
        if($alias_value != "Section Break - Statement of changes in equity")
        {
            if($remove_section_break) // remove section break
            {
                $sb_content = '<w:sdtContent><w:p><w:pPr><w:keepNext/><w:keepLines/><w:rPr><w:sz w:val="22"/><w:szCs w:val="22"/></w:rPr></w:pPr><w:r><w:rPr><w:sz w:val="22"/><w:szCs w:val="22"/></w:rPr><w:t>&lt; Section break &gt;</w:t></w:r></w:p></w:sdtContent>';
            }
            else // update landscape or portrait page
            {
                if(
                    $alias_value == "Section Break - (Note 9 - Intangible assets) - start"                                || 
                    $alias_value == "Section Break - (Note 11 - Investment properties) - start"                           || 
                    $alias_value == "Section Break - (Note 11 - Investment properties - table 3) - start"                 || 
                    $alias_value == "Section Break - (Note 12 - Property, plant and equipment) - start"                   || 
                    $alias_value == "Section Break - (Note 29.4 - Financial Risk Management (table_1) (group)) - start"   || 
                    $alias_value == "Section Break - (Note 29.4 - Financial Risk Management (table_1) (company)) - start" 
                )
                {
                    // set header reference 
                    if($final_document_type == 1)
                    {
                        $header_reference = '<w:headerReference w:type="even" r:id="rId30"/><w:headerReference w:type="default" r:id="rId31"/><w:footerReference w:type="default" r:id="rId32"/>';
                    }
                    else
                    {
                        $header_reference = '<w:headerReference w:type="even" r:id="rId30"/><w:headerReference w:type="default" r:id="rId31"/>';
                    }
                    
                    // add section page break (portrait only)
                    $sb_content = '<w:sdtContent><w:p><w:pPr><w:keepNext/><w:keepLines/><w:tabs><w:tab w:val="clear" w:pos="576"/></w:tabs><w:adjustRightInd/><w:sectPr>' . $header_reference . $portrait_template . $page_margin . '</w:sectPr></w:pPr><w:r><w:rPr><w:sz w:val="22"/><w:szCs w:val="22"/></w:rPr><w:t>&lt; Section break &gt;</w:t></w:r></w:p></w:sdtContent>';
                }
                else
                {   
                    $sb_content = '<w:sdtContent><w:p><w:pPr><w:keepNext/><w:keepLines/><w:tabs><w:tab w:val="clear" w:pos="576"/></w:tabs><w:adjustRightInd/><w:sectPr>' . $header_reference . $section_page_break . $page_margin . '</w:sectPr></w:pPr><w:r><w:rPr><w:sz w:val="22"/><w:szCs w:val="22"/></w:rPr><w:t>&lt; Section break &gt;</w:t></w:r></w:p></w:sdtContent>';
                    // $sb_content .= '<w:p><w:pPr><w:keepNext/><w:keepLines/><w:rPr><w:rFonts w:asciiTheme="majorHAnsi" w:hAnsiTheme="majorHAnsi" w:cstheme="majorHAnsi"/><w:sz w:val="22"/><w:szCs w:val="22"/></w:rPr></w:pPr></w:p></w:sdtContent>';
                }
            }

            // $replaced_wsdt_xml = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_wsdt_xml, $additional_info['generate_docs_without_tags'], 1);

            if($remove_section_break)
            {
                // remove hidden tags
                $replaced_wsdt_xml = $this->remove_hidden_tags_part_or_insert_vanish_tag($replaced_wsdt_xml, $additional_info['generate_docs_without_tags'], 1);
            }
            else
            {
                $replaced_wsdt_xml = preg_replace('/<w:sdtContent>(.*?)<\/w:sdtContent>/s', $sb_content, $wsdt_xml);
                $replaced_wsdt_xml = $this->vanish_template($replaced_wsdt_xml, 1);
            }
        }

        return $replaced_wsdt_xml;
    }

    public function note_part_is_checked($alias_value, $fs_company_info_id)
    {
        $alias_value_part = '';

        if($alias_value == "Section Break - (Note 9 - Intangible assets) - start" || $alias_value == "Section Break - (Note 9 - Intangible assets) - end")
        {
            $alias_value_part = 'Note 9 - Intangible assets (table_1)';
        }
        elseif($alias_value == "Section Break - (Note 11 - Investment properties) - start" || $alias_value == "Section Break - (Note 11 - Investment properties) - end")
        {
            $alias_value_part = 'Note 11 - Investment properties cost_model (table_1)';
        }
        elseif($alias_value == "Section Break - (Note 11 - Investment properties - table 3) - start" || $alias_value == "Section Break - (Note 11 - Investment properties - table 3) - end")
        {
            $alias_value_part = 'Note 11 - Investment properties (table_3)';
        }
        elseif($alias_value == "Section Break - (Note 12 - Property, plant and equipment) - start" || $alias_value == "Section Break - (Note 12 - Property, plant and equipment) - end")
        {
            $alias_value_part = 'Note 12 - Property, plant and equipment (table_1)';
        }
        elseif($alias_value == "Section Break - (Note 29.4 - Financial Risk Management (table_1) (group)) - start" || $alias_value == "Section Break - (Note 29.4 - Financial Risk Management (table_1) (group)) - end")
        {
            $alias_value_part = 'Note 29.4 - Financial Risk Management (table_1) (group)';
        }
        elseif($alias_value == "Section Break - (Note 29.4 - Financial Risk Management (table_1) (company)) - start" || $alias_value == "Section Break - (Note 29.4 - Financial Risk Management (table_1) (company)) - end")
        {
            $alias_value_part = 'Note 29.4 - Financial Risk Management (table_1) (company)';
        }

        return !$this->note_show_hide_part($alias_value_part, $fs_company_info_id);
    }

    public function replace_wgridCol_val($replaced_tbl_template, $col_data) 
    {
        $wgridcol_list = [];
        $tc_accumulated_width = [];

        $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

        foreach ($tbl_tr[0] as $tr_key => $tr_value) 
        {
            preg_match_all('/<w:tcW w:w="(.*?)".*?\/>/', $tr_value, $tc_widths);

            $temp_tc_accumulated = [];

            foreach ($tc_widths[1] as $tc_key => $tc_value) 
            {
                if($tc_key != 0)
                {
                    $tc_value = $temp_tc_accumulated[$tc_key - 1] + $tc_value;
                }

                if(!in_array($tc_value, $tc_accumulated_width))
                {
                    array_push($tc_accumulated_width, $tc_value);  // save all tc width with accumulated values (all)
                }

                array_push($temp_tc_accumulated, $tc_value);    // save all tc width with accumulated values (this tr)
            }
        }

        sort($tc_accumulated_width);

        // calculate and save in $wgridcol_list
        foreach ($tc_accumulated_width as $tc_key_1 => $tc_value_1) 
        {
            if($tc_key_1 != 0)
            {
                $tc_value_1 = $tc_value_1 - $tc_accumulated_width[$tc_key_1 - 1];
            }

            array_push($wgridcol_list, $tc_value_1);
        }

        // modify overall table width (tblW) to new width value
        preg_match_all ('/<w:tblW w:w="(.*?)"\/>/s', $replaced_tbl_template, $wtblW_tag_name);     // get tag <w:tblW w:w="9561" w:type="dxa"/>
        // preg_match('/w:w="(.*?)"/s', $wtblW_tag_name[0][0], $overall_tblW_width_default); // get value "9561" in w:w="9561" <-- can be removed

        // build w:gridCol (<w:tblGrid><w:gridCol w:w="3063"/></w:tblGrid>)
        $build_wgridcol = '';

        foreach ($wgridcol_list as $wg_key => $wg_value) 
        {
            $build_wgridcol .= '<w:gridCol w:w="' . $wg_value . '"/>';
        }

        $replaced_wtblGrid_tag = '<w:tblGrid>' . $build_wgridcol . '</w:tblGrid>';

        

        // replace tbl content
        // $replaced_tbl_template = preg_replace('/' . $overall_tblW_width_default[0] . '/s', 'w:w="0"', $replaced_tbl_template); // delete this part

        $replaced_tbl_template = preg_replace('/<w:tblW(.*?)\/>/', '<w:tblW w:w="' . array_sum($wgridcol_list) . '" w:type="dxa"/>', $replaced_tbl_template); 
        $replaced_tbl_template = preg_replace('/<w:tblGrid>(.*?)<\/w:tblGrid>/s', $replaced_wtblGrid_tag, $replaced_tbl_template); 

        return $replaced_tbl_template;
    }

    // public function replace_wtrPr_val_rows($replaced_tbl_template, $col_data) 
    // {
    //     preg_match_all('/<w:tblW w:w="(.*?)" w:type="dxa"\/>/', $replaced_tbl_template, $tbl_width); // get table width
    //     $tbl_width = (int)$tbl_width[1][0];

    //     // print_r(array($tbl_width));

    //     $tbl_tr          = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 
    //     // $replaced_tbl_tr = $tbl_tr;

    //     foreach ($tbl_tr[0] as $tr_key => $tr_value) 
    //     {
    //         $whidden = '<w:hidden/>'; // template of hidden tr
    //         $tr_total_tc_width = 0;
    //         $new_wtrPr_content = '';

    //         preg_match_all('/<w:tcW w:w="(.*?)".*?\/>/', $tr_value, $tc_widths); // take each tc width

    //         // print_r($tc_widths);

    //         // calculate total of tc width in 1 tr
    //         foreach ($tc_widths[1] as $tc_key => $tc_value) 
    //         {
    //             $tr_total_tc_width += (int)$tc_value;
    //         }

    //         // print_r(array($tbl_width, $tr_total_tc_width));

    //         // if this tr width < tbl_width, set 'w:gridAfter' and 'w:wAfter'
    //         if($tr_total_tc_width < $tbl_width)
    //         {
    //             preg_match_all('/<w:trPr>(.*?)<\/w:trPr>/', $tr_value, $wtrPr_arr); // take each tc width

    //             // exclude <w:hidden/> template if not exist
    //             if (strpos($wtrPr_arr[0][0], $whidden) == false)
    //             {
    //                 $whidden = '';
    //             }

    //             // add w:gridAfter
    //             if(count($tc_widths[1]) > 1)
    //             {
    //                 $new_wtrPr_content .= '<w:gridAfter w:val="' . count($tc_widths[1]) . '"/>';
    //             }

    //             // add w:wAfter
    //             $new_wtrPr_content .= '<w:wAfter w:w="' . ($tbl_width - $tr_total_tc_width) . '" w:type="dxa"/>';
    //             $new_wtrPr_content .= $whidden;

    //             // update wtrPr
    //             $replaced_wtrPr = str_replace($wtrPr_arr[1][0], $new_wtrPr_content, $wtrPr_arr[0][0]);

    //             // print_r(array($wtrPr_arr, $new_wtrPr_content, $replaced_wtrPr));

    //             $replaced_tr_value = str_replace($wtrPr_arr[0][0], $replaced_wtrPr, $tr_value); // replace this tr
    //             // $replaced_tbl_template   = str_replace($tr_value, $replaced_tr_value, $replaced_tbl_template);
    //             $replaced_tbl_template = preg_replace('/' . $tr_value . '/s', $replaced_tr_value, $replaced_tbl_template);

    //             // print_r(array($replaced_tr_value));
    //         }
    //     }

    //     print_r($replaced_tbl_template);

    //     return $replaced_tbl_template;
    // }

    public function modify_tbl_dynamic_col_landscape_portrait($tbl_template, $col_data)
    {
        // $col_data = array(
        //                 'fs_company_info_id'       => 0,
        //                 'table_name'               => '',
        //                 'header_data'              => [],
        //                 'body_data'                => [],
        //                 'clear_tr_after_row'       => 0,
        //                 'max_col_num_for_portrait' => 0
        //             );

        $ori_all_template = [];

        $temp_content_trs = '';
        $replaced_tbl_template = $tbl_template;

        $replaced_tbl_template_2 = $tbl_template;
        
        preg_match_all ('/<w:tblGrid>(.*?)<\/w:tblGrid>/s', $tbl_template, $wtblGrid_tag_name);         // get w:tblGrid tag
        preg_match_all ('/<w:gridCol w:w="(.*?)"\/>/s', $wtblGrid_tag_name[0][0], $wgridCol_tag_name);  // get w:gridCol tag

        $dynamic_col_num = count($col_data['header_data']);   // number of column from database (dynamic).

        if($dynamic_col_num == 0)
        {
            $dynamic_col_num = 1;
        }

        // print_r(array($wgridCol_tag_name));

        if($dynamic_col_num <= $col_data['max_col_num_for_portrait'])  // portrait
        {
            $tblW_default = 8740;   // we set table width = 9560

            $tblW_first_col_desc_width  = $wgridCol_tag_name[1][0];    // get first column width from template
            $tbl_col_dynamic_cols_width = ceil(($tblW_default - $tblW_first_col_desc_width) / $dynamic_col_num);    // set new column width for the dynamic columns
            $overall_tblW_used_width    = $tblW_first_col_desc_width + ($tbl_col_dynamic_cols_width * $dynamic_col_num);   // set new table width
        }
        elseif($dynamic_col_num > $col_data['max_col_num_for_portrait']) // landscape
        {
            $tblW_default = 13000;   // we set table width = 13460

            $tblW_first_col_desc_width  = $wgridCol_tag_name[1][0];    // get first column width from template
            $tbl_col_dynamic_cols_width = ceil(($tblW_default - $tblW_first_col_desc_width) / $dynamic_col_num);    // set new column width for the dynamic columns
            $overall_tblW_used_width    = $tblW_first_col_desc_width + ($tbl_col_dynamic_cols_width * $dynamic_col_num);   // set new table width
        }

        $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr       

        /* Build template */
        // get templates and remove all the info first
        $width_data = [];
        $tr_template_data = [];

        // $count_keep_line = 0;

        foreach ($tbl_tr[0] as $tbl_tr_key => $tbl_tr_value) 
        {
            $tr_name_type = $this->get_tr_name_type($tbl_tr_value);  // to extract the first column name so that we know it is title or account name and value insert template.
            $width_list = [$tblW_first_col_desc_width];

            for($i = 0; $i < $dynamic_col_num;  $i++)
            {
                array_push($width_list, $tbl_col_dynamic_cols_width);
            }

            $width_data = array(
                            'width'         => $width_list,
                            'skip_col'      => [1],
                            'collect_template_col' => [2]
                        );

            if($tr_name_type == "{New Line}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $newline_template = $this->vanish_template($tbl_tr_value, 0); 
                $newline_template = $this->remove_wr_from_tr($newline_template, [1]); 
                $newline_template = $this->add_table_new_column($newline_template, array('columns_needed' => $dynamic_col_num + 1, 'copy_column' => 2)); 
                $newline_template = $this->change_tr_tc_width($newline_template, $width_data);  

                $tr_template_data['newline'] = $newline_template;
            }
            elseif($tr_name_type == "{Header display}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $width_data = array(
                            'width'          => array($tblW_first_col_desc_width, $overall_tblW_used_width - $tblW_first_col_desc_width),
                            'skip_col'       => [1],
                            'gridspan_col'   => [2],
                            'gridspan_value' => [$dynamic_col_num],
                            'table_name'     => $col_data['table_name']
                        );

                $header_display_tr_template = $this->vanish_template($tbl_tr_value, 0); 
                $header_display_tr_template = $this->remove_wr_from_tr($header_display_tr_template, [1]);
                $header_display_tr_template = $this->add_table_new_column($header_display_tr_template, array('columns_needed' => $dynamic_col_num + 1, 'copy_column' => 2)); 
                $header_display_tr_template = $this->change_tr_tc_width($header_display_tr_template, $width_data);

                $tr_template_data['header_display'] = $header_display_tr_template;
            }
            elseif($tr_name_type == "{Header title}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $header_title_tr_template = $this->vanish_template($tbl_tr_value, 0); 
                $header_title_tr_template = $this->remove_wr_from_tr($header_title_tr_template, [1]); 
                $header_title_tr_template = $this->add_table_new_column($header_title_tr_template, array('columns_needed' => $dynamic_col_num + 1, 'copy_column' => 2));
                $header_title_tr_template = $this->change_tr_tc_width($header_title_tr_template, $width_data);  

                $tr_template_data['header_title'] = $header_title_tr_template;
            }
            elseif($tr_name_type == "{Dollar Sign}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $dollar_sign_tr_template = $this->vanish_template($tbl_tr_value, 0); 
                $dollar_sign_tr_template = $this->remove_wr_from_tr($dollar_sign_tr_template, [1]); 
                $dollar_sign_tr_template = $this->add_table_new_column($dollar_sign_tr_template, array('columns_needed' => $dynamic_col_num + 1, 'copy_column' => 2));
                $dollar_sign_tr_template = $this->change_tr_tc_width($dollar_sign_tr_template, $width_data); 

                $tr_template_data['dollar_sign'] = $dollar_sign_tr_template;
            }
            elseif($tr_name_type == "{Title - italic}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $title_italic_tr_template = $this->vanish_template($tbl_tr_value, 0);
                $title_italic_tr_template = $this->add_table_new_column($title_italic_tr_template, array('columns_needed' => $dynamic_col_num + 1, 'copy_column' => 2));
                $title_italic_tr_template = $this->change_tr_tc_width($title_italic_tr_template, $width_data);

                $tr_template_data['title_italic'] = $title_italic_tr_template; 
            }
            elseif($tr_name_type == "{Title - bold}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $title_bold_tr_template = $this->vanish_template($tbl_tr_value, 0);

                if(!($col_data['table_name'] == "Note 9 - Intangible assets (table_1)"))
                {
                    $title_bold_tr_template = $this->add_table_new_column($title_bold_tr_template, array('columns_needed' => $dynamic_col_num + 1, 'copy_column' => 2));
                }
                $title_bold_tr_template = $this->change_tr_tc_width($title_bold_tr_template, $width_data);

                $tr_template_data['title_bold'] = $title_bold_tr_template; 
            }
            elseif($tr_name_type == "{Description - normal}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $description_normal_tr_template = $this->vanish_template($tbl_tr_value, 0);
                $description_normal_tr_template = $this->add_table_new_column($description_normal_tr_template, array('columns_needed' => $dynamic_col_num + 1, 'copy_column' => 2));
                $description_normal_tr_template = $this->change_tr_tc_width($description_normal_tr_template, $width_data); 

                $tr_template_data['description'] = $description_normal_tr_template;
            }
            elseif($tr_name_type == "{Top border description}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $top_border_description_tr_template = $this->vanish_template($tbl_tr_value, 0);
                $top_border_description_tr_template = $this->add_table_new_column($top_border_description_tr_template, array('columns_needed' => $dynamic_col_num + 1, 'copy_column' => 2));
                $top_border_description_tr_template = $this->change_tr_tc_width($top_border_description_tr_template, $width_data); 

                $tr_template_data['top_border_desc'] = $top_border_description_tr_template;
            }
            elseif($tr_name_type == "{Last line description}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $last_line_description_tr_template = $this->vanish_template($tbl_tr_value, 0);
                $last_line_description_tr_template = $this->add_table_new_column($last_line_description_tr_template, array('columns_needed' => $dynamic_col_num + 1, 'copy_column' => 2));
                $last_line_description_tr_template = $this->change_tr_tc_width($last_line_description_tr_template, $width_data); 

                $tr_template_data['last_line_desc'] = $last_line_description_tr_template;
            }
            elseif($tr_name_type == "{Subtotal}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $subtotal_tr_template = $this->vanish_template($tbl_tr_value, 0);

                // remove description
                if($col_data['table_name'] == "Note 29.4 - Financial Risk Management (table_1) (group)" || $col_data['table_name'] == "Note 29.4 - Financial Risk Management (table_1) (company)")
                {
                    $subtotal_tr_template = $this->remove_wr_from_tr($subtotal_tr_template, [1]);
                }

                $subtotal_tr_template = $this->add_table_new_column($subtotal_tr_template, array('columns_needed' => $dynamic_col_num + 1, 'copy_column' => 2));
                $subtotal_tr_template = $this->change_tr_tc_width($subtotal_tr_template, $width_data); 

                $tr_template_data['subtotal'] = $subtotal_tr_template;
            }
            elseif($tr_name_type == "{Overall total}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $overall_total_tr_template = $this->vanish_template($tbl_tr_value, 0);
                $overall_total_tr_template = $this->add_table_new_column($overall_total_tr_template, array('columns_needed' => $dynamic_col_num + 1, 'copy_column' => 2));
                $overall_total_tr_template = $this->change_tr_tc_width($overall_total_tr_template, $width_data); 

                $tr_template_data['overall_total'] = $overall_total_tr_template;
            }
            elseif($tr_name_type == "{Last Line space}") 
            {
                array_push($ori_all_template, $tbl_tr_value);
                $last_line_space_tr_template = $tbl_tr_value;   // for replace value later

                $tr_template_data['last_line_space'] = $last_line_space_tr_template;
            }
            elseif($tr_name_type == $col_data['table_name'])
            {
                array_push($ori_all_template, $tbl_tr_value);
            }
            // elseif(isset($col_data['keep_line']) && in_array($tbl_tr_key, $col_data['keep_line']))
            // {
            //     $tr_template_data['keep_line'][$count_keep_line] = $tbl_tr_value;

            //     $count_keep_line++;
            // }
            elseif($tbl_tr_key >= $col_data['clear_tr_after_row'])
            {
                $replaced_tbl_template_2 = str_replace($tbl_tr_value, '', $replaced_tbl_template_2);
            }
        }
        /* END OF Build template */

        $temp_content_trs = '';

        if($col_data['table_name'] != "Note 6 - Investment in subsidiaries (ii) (table_1)")
        {
            $temp_content_trs .= $tr_template_data['header_display'];
        }

        // set header data
        $info_data = [''];
        foreach ($col_data['header_data'] as $h_key => $h_value) {    array_push($info_data, $h_value);    }
        if(count($col_data['header_data']) > 0)
        {
            $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['header_title'], $info_data);
        }

        // set body data
        if($col_data['table_name'] == "Note 6 - Investment in subsidiaries (ii) (table_1)") 
        {
            $temp_content_trs .= $tr_template_data['dollar_sign'];

            foreach ($col_data['body_data'] as $b_key => $b_value) 
            {
                if($b_value['is_title'])
                {
                    if($b_key != 0) // not first row data
                    {
                        if($col_data['body_data'][$b_key - 1]['section'] != $b_value['section']) // if section (current) not same as section (previous)
                        {
                            $temp_content_trs .= $tr_template_data['newline']; // add newline
                        }
                    }
                    $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['title_bold'], array($b_value['description']));
                }
                else
                {
                    $input_value = [];

                    // thousand separator
                    foreach ($b_value['row_item'] as $key => $value) 
                    {
                        $b_value['row_item'][$key] = $this->fs_replace_content_model->negative_bracket($value);
                    }

                    $input_value = array_merge(array($b_value['description']), $b_value['row_item']);

                    if($b_key == count($col_data['body_data']) - 1) // last description
                    {
                        $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['last_line_desc'], $input_value);
                    }
                    else
                    {
                        $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['description'], $input_value);
                    }
                }
            }
        }
        elseif($col_data['table_name'] == "Note 9 - Intangible assets (table_1)") 
        {
            $temp_content_trs .= $tr_template_data['dollar_sign'];
            $section = 'section_start';

            // set body data
            foreach ($col_data['body_data'] as $b_key => $b_value) 
            {
                // bold title data
                if($section != $b_value['section'] && !(strpos($b_value['section'], $section) !== false))
                {
                    $bold_title_name = '';

                    if($b_value['section'] == "cost")
                    {
                        $bold_title_name = "COST";
                    }
                    elseif($b_value['section'] == "accumulated")
                    {
                        $bold_title_name = "ACCUMULATED AMORTIZATION AND IMPAIRMENT";
                    }
                    elseif($b_value['section'] == "last carrying" || $b_value['section'] == "carrying")
                    {
                        if(!($section == "carrying" && $b_value['section'] == "last carrying"))
                        {
                            $bold_title_name = "CARRYING AMOUNT";
                        }
                    }

                    $temp_content_trs .= $tr_template_data['newline'];
                    $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['title_bold'], array($bold_title_name));
                }

                // set data
                $input_value = [];

                // thousand separator
                foreach ($b_value['row_item'] as $key => $value) 
                {
                    $b_value['row_item'][$key] = $this->fs_replace_content_model->negative_bracket($value);
                }

                $input_value = array_merge(array($b_value['description']), $b_value['row_item']);

                if(strpos($b_value['section'], 'last') !== false || $b_value['section'] == "carrying")   // last item of the section
                {
                    if($b_value['section'] == "last carrying" || $b_value['section'] == "carrying")
                    {
                        if($b_value['section'] == "carrying")
                        {
                            $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['description'], $input_value);
                        }
                        else
                        {
                            $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['overall_total'], $input_value);
                        }
                    }
                    else
                    {
                        $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['subtotal'], $input_value);
                    }
                }
                elseif($b_value['is_checked'])
                {   
                    $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['top_border_desc'], $input_value);
                }
                else
                {
                    $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['description'], $input_value);
                }
                $section = $b_value['section'];
            }
        }
        elseif($col_data['table_name'] == "Note 11 - Investment properties (table_3)")
        {
            $temp_content_trs .= $tr_template_data['dollar_sign'];
            $temp_content_trs .= $tr_template_data['newline'];

            foreach ($col_data['body_data'] as $b_key => $b_value) 
            {
                // set data
                $input_value = [];

                // thousand separator
                foreach ($b_value['row_item'] as $key => $value) 
                {
                    $b_value['row_item'][$key] = $this->fs_replace_content_model->negative_bracket($value);
                }

                $input_value = array_merge(array($b_value['description']), $b_value['row_item']);

                if($b_value['section'] == "normal")
                {
                    if($b_value['is_checked'])
                    {
                        $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['top_border_desc'], $input_value);
                    }
                    else
                    {
                        $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['description'], $input_value);
                    }
                }
                elseif($b_value['section'] == "last")
                {
                    $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['overall_total'], $input_value);
                }
            }
        }
        elseif($col_data['table_name'] == "Note 12 - Property, plant and equipment (table_1)" || $col_data['table_name'] == "Note 11 - Investment properties cost_model (table_1)")
        {
            $temp_content_trs .= $tr_template_data['dollar_sign'];
            $section = 'section_start';

            // set body data
            foreach ($col_data['body_data'] as $b_key => $b_value) 
            {
                // bold title data
                if($section != $b_value['section'] && !(strpos($b_value['section'], $section) !== false))
                {
                    $bold_title_name = '';

                    if($b_value['section'] == "cost")
                    {
                        $bold_title_name = "COST";
                    }
                    elseif($b_value['section'] == "accumulated")
                    {
                        $bold_title_name = "ACCUMULATED AMORTIZATION AND IMPAIRMENT";
                    }
                    elseif($b_value['section'] == "carrying" || ($section != 'carrying' && $b_value['section'] == "last carrying"))
                    {
                        $bold_title_name = "CARRYING AMOUNT";
                    }

                    $temp_content_trs .= $tr_template_data['newline'];
                    $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['title_italic'], array($bold_title_name));
                }

                // set data
                $input_value = [];

                // thousand separator
                foreach ($b_value['row_item'] as $key => $value) 
                {
                    $b_value['row_item'][$key] = $this->fs_replace_content_model->negative_bracket($value);
                }
                    
                $input_value = array_merge(array($b_value['description']), $b_value['row_item']);

                if(strpos($b_value['section'], 'last') !== false || $b_value['section'] == "carrying")   // last item of the section
                {
                    if($b_value['section'] == "last carrying" || $b_value['section'] == "carrying")
                    {
                        $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['overall_total'], $input_value);
                    }
                    else
                    {
                        $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['subtotal'], $input_value);
                    }
                }
                elseif($b_value['is_checked'])
                {   
                    $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['top_border_desc'], $input_value);
                }
                else
                {
                    $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['description'], $input_value);
                }
                $section = $b_value['section'];
            }
        }
        elseif($col_data['table_name'] == "Note 29.4 - Financial Risk Management (table_1) (group)" || $col_data['table_name'] == "Note 29.4 - Financial Risk Management (table_1) (company)")
        {
            // $temp_content_trs
            $temp_content_trs .= $tr_template_data['dollar_sign'];

            // set body data
            $fs_company_info = $this->fs_model->get_fs_company_info($col_data['fs_company_info_id']);

            $total = [];

            foreach ($col_data['body_data'] as $b_key => $b_value) 
            {
                if($b_key == 0)
                {
                    if($b_value['prior_current'] == "current")
                    {
                        $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['title_italic'], array('As at ' . $fs_company_info[0]['current_fye_end']));
                    }
                }
                elseif($b_key != count($col_data['body_data']) - 1 && $col_data['body_data'][$b_key]['prior_current'] != $col_data['body_data'][$b_key - 1]['prior_current'])
                {
                    if($col_data['body_data'][$b_key + 1]['prior_current'] == "prior")
                    {
                        $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['title_italic'], array('As at ' . $fs_company_info[0]['last_fye_end']));
                    }
                }

                // set data
                $input_value = [];

                // thousand separator
                foreach ($b_value['row_item'] as $key => $value) 
                {
                    // calculate subtotal
                    if($b_value['prior_current'] == 'current')
                    {
                        $total['subtotal_c'][$key] += (int)$value;
                        $total['overall_c'][$key]  += (int)$value;
                    }
                    elseif($b_value['prior_current'] == 'prior')
                    {
                        $total['subtotal_p'][$key] += (int)$value;
                        $total['overall_p'][$key]  += (int)$value;
                    }

                    $b_value['row_item'][$key] = $this->fs_replace_content_model->negative_bracket($value);
                }
                    
                $input_value = array_merge(array($b_value['description']), $b_value['row_item']);

                if($b_value['is_checked'])
                {   
                    $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['top_border_desc'], $input_value);
                }
                else
                {
                    $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['description'], $input_value);
                }

                // if current row data is fixed, display subtotal
                if(($b_key != count($col_data['body_data']) - 1 && $col_data['body_data'][$b_key + 1]['is_fixed']))
                {
                    // for current year
                    for ($i=0; $i < count($total['subtotal_c']); $i++) 
                    {
                        $total['subtotal_c'][$i] = $this->fs_replace_content_model->negative_bracket($total['subtotal_c'][$i]);
                    }

                    // for prior year
                    for ($i=0; $i < count($total['subtotal_p']); $i++) 
                    {
                        $total['subtotal_p'][$i] = $this->fs_replace_content_model->negative_bracket($total['subtotal_p'][$i]);
                    }

                    // display subtotal
                    if($b_value['prior_current'] == 'current')
                    {
                        $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['subtotal'], array_merge(array(''), $total['subtotal_c']));
                    }
                    elseif($b_value['prior_current'] == 'prior')
                    {
                        $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['subtotal'], array_merge(array(''), $total['subtotal_p']));
                    }
                }
                
                // display overall total
                if($b_value['is_fixed'])
                {
                    // for current year
                    for ($i=0; $i < count($total['overall_c']); $i++) 
                    {
                        $total['overall_c'][$i] = $this->fs_replace_content_model->negative_bracket($total['overall_c'][$i]);
                    }

                    // for prior year
                    for ($i=0; $i < count($total['overall_p']); $i++) 
                    {
                        $total['overall_p'][$i] = $this->fs_replace_content_model->negative_bracket($total['overall_p'][$i]);
                    }

                    if($b_value['prior_current'] == 'current')
                    {
                        $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['overall_total'], array_merge(array('Currency exposure'), $total['overall_c']));
                    }
                    elseif($b_value['prior_current'] == 'prior')
                    {
                        $temp_content_trs .= $this->replace_tr_template_item($tr_template_data['overall_total'], array_merge(array('Currency exposure'), $total['overall_p']));
                    }

                    if($b_key != count($col_data['body_data']) - 1)
                    {
                        $temp_content_trs .= $tr_template_data['newline'];
                    }
                }
            }
        }

        // replace and add in values.
        $replaced_tbl_template_2 = str_replace($tr_template_data['last_line_space'], $tr_template_data['last_line_space'] . $temp_content_trs . $tr_template_data['newline'], $replaced_tbl_template_2);

        if($col_data['generate_docs_without_tags'])
        {
            foreach ($ori_all_template as $at_key => $at_value) 
            {
                $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
            }
        }

        $replaced_tbl_template_2 = $this->replace_wgridCol_val($replaced_tbl_template_2, $col_data); // adjust w:gridCol values
        // $replaced_tbl_template_2 = $this->replace_wtrPr_val_rows($replaced_tbl_template_2, $col_data);

        return $replaced_tbl_template_2;
    }

    public function build_tbl_template_tr_data($tbl_tr, $extra_info)
    {
        $data = [];
        $ori_all_template = [];

        $data['replaced_tbl_template_2'] = $extra_info['replaced_tbl_template_2'];

        foreach ($tbl_tr[0] as $tbl_tr_key => $tbl_tr_value) 
        {
            $tr_name_type = $this->get_tr_name_type($tbl_tr_value);  // to extract the first column name so that we know it is title or account name and value insert template.

            if($tr_name_type == "{Title - normal}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $data['title_normal'] = $this->vanish_template($tbl_tr_value, 0);
            }
            elseif($tr_name_type == "{Title - Italic}" || $tr_name_type == "{Title - italic}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $data['title_italic'] = $this->vanish_template($tbl_tr_value, 0);
            }
            elseif($tr_name_type == "{Title - bold}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $data['title_bold'] = $this->vanish_template($tbl_tr_value, 0);
            }
            elseif($tr_name_type == "{Description- normal}" || $tr_name_type == "{Description}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $data['description'] = $this->vanish_template($tbl_tr_value, 0);

                if($extra_info['group_type'] == 1)
                { 
                    $data['description'] = $this->remove_wr_from_tr($data['description'], $extra_info['hide_group_column']);
                }
            }
            elseif($tr_name_type == "{Description - underline}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $data['description_underline'] = $this->vanish_template($tbl_tr_value, 0);
            }
            elseif($tr_name_type == "{Subtotal}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $data['subtotal'] = $this->vanish_template($tbl_tr_value, 0);
                // $data['subtotal'] = $this->remove_wr_from_tr($data['subtotal'], array_merge([1], $extra_info['hide_group_column']));
                $data['subtotal'] = $this->remove_wr_from_tr($data['subtotal'], [1]);

                if($extra_info['group_type'] == 1)
                {
                    $data['subtotal'] = $this->remove_wr_from_tr($data['subtotal'], $extra_info['hide_group_column']);
                }
            }
            elseif($tr_name_type == "{Subtotal with description}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $data['subtotal_desc'] = $this->vanish_template($tbl_tr_value, 0);

                if($extra_info['group_type'] == 1)
                {
                    $data['subtotal_desc'] = $this->remove_wr_from_tr($data['subtotal_desc'], $extra_info['hide_group_column']);
                }
            }
            elseif($tr_name_type == "{Overall total}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $data['overall_total'] = $this->vanish_template($tbl_tr_value, 0);
                $data['overall_total'] = $this->remove_wr_from_tr($data['overall_total'], [1]);

                if($extra_info['group_type'] == 1)
                {
                    $data['overall_total'] = $this->remove_wr_from_tr($data['overall_total'], $extra_info['hide_group_column']);
                }
            }
            elseif($tr_name_type == "{Last line description}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $data['last_line_desc'] = $this->vanish_template($tbl_tr_value, 0);

                if($extra_info['group_type'] == 1)
                {
                    $data['last_line_desc'] = $this->remove_wr_from_tr($data['last_line_desc'], $extra_info['hide_group_column']);
                }
            }
            elseif($tr_name_type == "{Overall total with description}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $data['overall_total_desc'] = $this->vanish_template($tbl_tr_value, 0);

                if($extra_info['group_type'] == 1)
                {
                    $data['overall_total_desc'] = $this->remove_wr_from_tr($data['overall_total_desc'], $extra_info['hide_group_column']);
                }
            }
            elseif($tr_name_type == "{Last item tr}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $data['last_item_tr'] = $this->vanish_template($tbl_tr_value, 0);
            }
            elseif($tr_name_type == "{New line}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $data['newline'] = $this->vanish_template($tbl_tr_value, 0);
                $data['newline'] = $this->remove_wr_from_tr($data['newline'], [1]);
            }
            elseif($tr_name_type == "{Last line space}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $data['last_line_space'] = $tbl_tr_value;   // for replace value later
            }
            elseif($tr_name_type == $extra_info['table_name'])
            {
                array_push($ori_all_template, $tbl_tr_value);
            }
            elseif($tbl_tr_key > $extra_info['template_num_of_line'])
            {
                $data['replaced_tbl_template_2'] = str_replace($tbl_tr_value, '', $data['replaced_tbl_template_2']);
            }
        }

        $data['ori_all_template'] = $ori_all_template;

        return $data;
    }

    public function build_tr_template_with_data($fs_company_info, $selected_tr_template, $data)   // for statement fs_categorized_account_round_off use.
    {
        $temp_content_trs = '';

        foreach ($data as $key => $value) 
        {
            $tr_item  = '';
            $note_no  = '';

            $template = $selected_tr_template;  

            // get note number if it exists
            $fs_notes_details = $this->fs_notes_model->get_selected_note($fs_company_info[0]['id'], $value[0]['parent_array'][0]['id']);


            if(count($fs_notes_details) > 0)
            {
                $note_no = $fs_notes_details[0]['note_num_displayed'];
            }
            else
            {
                $template = $this->remove_wr_from_tr($template, [2]); 
            }

            // if($value[0]['parent_array'][0]['description'] == "Tax")
            // {
            //     print_r(array("hello", $data, $note_no, $fs_notes_details, $template, $selected_tr_template, "fs_generate_doc_word_model - line 10319"));
            // }

            if($fs_company_info[0]['group_type'] == 1)
            {   
                if($fs_company_info[0]['first_set'] == 1)
                {
                    $display_data = array(
                                        $value[0]['parent_array'][0]['description'],
                                        $note_no,
                                        $this->fs_replace_content_model->negative_bracket($value[0]['parent_array'][0]['total_c'])
                                    );

                    // calculate subtotal for company
                    $subtotal['value_c_ty'] += $value[0]['parent_array'][0]['total_c'];
                }
                else
                {
                    $display_data = array(
                                        $value[0]['parent_array'][0]['description'],
                                        $note_no,
                                        $this->fs_replace_content_model->negative_bracket($value[0]['parent_array'][0]['total_c']),
                                        $this->fs_replace_content_model->negative_bracket($value[0]['parent_array'][0]['total_c_lye'])
                                    );

                    // calculate subtotal for company
                    $subtotal['value_c_ty'] += $value[0]['parent_array'][0]['total_c'];
                    $subtotal['value_c_ly'] += $value[0]['parent_array'][0]['total_c_lye'];
                }
            }
            else
            {
                if($fs_company_info[0]['first_set'] == 1)
                {
                    $display_data = array(
                                        $value[0]['parent_array'][0]['description'],
                                        $note_no,
                                        $this->fs_replace_content_model->negative_bracket($value[0]['parent_array'][0]['group_end_this_ye_value']),
                                        '', 
                                        $this->fs_replace_content_model->negative_bracket($value[0]['parent_array'][0]['total_c'])
                                    );

                    // calculate subtotal for group
                    $subtotal['value_g_ty'] += $value[0]['parent_array'][0]['group_end_this_ye_value'];

                    // calculate subtotal for company
                    $subtotal['value_c_ty'] += $value[0]['parent_array'][0]['total_c'];
                }
                else
                {
                    $display_data = array(
                                        $value[0]['parent_array'][0]['description'],
                                        $note_no,
                                        $this->fs_replace_content_model->negative_bracket($value[0]['parent_array'][0]['group_end_this_ye_value']),
                                        $this->fs_replace_content_model->negative_bracket($value[0]['parent_array'][0]['group_end_prev_ye_value']),
                                        '', 
                                        $this->fs_replace_content_model->negative_bracket($value[0]['parent_array'][0]['total_c']),
                                        $this->fs_replace_content_model->negative_bracket($value[0]['parent_array'][0]['total_c_lye'])
                                    );

                    // calculate subtotal for group
                    $subtotal['value_g_ty'] += $value[0]['parent_array'][0]['group_end_this_ye_value'];
                    $subtotal['value_g_ly'] += $value[0]['parent_array'][0]['group_end_prev_ye_value'];

                    // calculate subtotal for company
                    $subtotal['value_c_ty'] += $value[0]['parent_array'][0]['total_c'];
                    $subtotal['value_c_ly'] += $value[0]['parent_array'][0]['total_c_lye'];
                }
            }

            
            $tr_item = $this->replace_tr_template_item($template, $display_data);
            $temp_content_trs .= $tr_item;
        }

        return 
            array(
                'temp_content_trs' => $temp_content_trs,
                'subtotal'         => $subtotal
            );
    }

    public function build_tr_template_with_data_fs_sci($fs_company_info, $selected_tr_template, $data_list)   // for statement fs_state_comp_income use.
    {
        $temp_content_trs = '';

        $tr_item  = '';
        $note_no  = '';
        $template = $selected_tr_template;

        // print_r(array($data_list[0]));

        if(count($data_list) > 0 && !empty($data_list[0]))
        {
            foreach ($data_list as $key => $data) 
            {
                // get note number if it exists
                $fs_notes_details = $this->fs_notes_model->get_selected_note_for_fs_state_comp_income($fs_company_info[0]['id'], $data['id']);

                if(count($fs_notes_details) > 0)
                {
                    $note_no = $fs_notes_details[0]['note_num_displayed'];
                }
                else
                {
                    $template = $this->remove_wr_from_tr($template, [2]);
                }

                if($fs_company_info[0]['group_type'] == 1)
                {
                    if($fs_company_info[0]['first_set'] == 1)
                    {
                        $display_data = array(
                                            $data['description'],
                                            $note_no,
                                            $this->fs_replace_content_model->negative_bracket($data['value_company_ye'])
                                        );

                        // calculate subtotal for company
                        $subtotal['value_c_ty'] += $data['value_company_ye'];
                    }
                    else
                    {
                        $display_data = array(
                                            $data['description'],
                                            $note_no,
                                            $this->fs_replace_content_model->negative_bracket($data['value_company_ye']),
                                            $this->fs_replace_content_model->negative_bracket($data['value_company_lye_end'])
                                        );

                        // calculate subtotal for company
                        $subtotal['value_c_ty'] += $data['value_company_ye'];
                        $subtotal['value_c_ly'] += $data['value_company_lye_end'];
                    }
                }
                else
                {
                    if($fs_company_info[0]['first_set'] == 1)
                    {
                        $display_data = array(
                                            $data['description'],
                                            $note_no,
                                            $this->fs_replace_content_model->negative_bracket($data['value_group_ye']),
                                            '', 
                                            $this->fs_replace_content_model->negative_bracket($data['value_company_ye'])
                                        );

                        // calculate subtotal for group
                        $subtotal['value_g_ty'] += $data['value_group_ye'];

                        // calculate subtotal for company
                        $subtotal['value_c_ty'] += $data['value_company_ye'];
                    }
                    else
                    {
                        $display_data = array(
                                            $data['description'],
                                            $note_no,
                                            $this->fs_replace_content_model->negative_bracket($data['value_group_ye']),
                                            $this->fs_replace_content_model->negative_bracket($data['value_group_lye_end']),
                                            '', 
                                            $this->fs_replace_content_model->negative_bracket($data['value_company_ye']),
                                            $this->fs_replace_content_model->negative_bracket($data['value_company_lye_end'])
                                        );

                        // calculate subtotal for group
                        $subtotal['value_g_ty'] += $data['value_group_ye'];
                        $subtotal['value_g_ly'] += $data['value_group_lye_end'];

                        // calculate subtotal for company
                        $subtotal['value_c_ty'] += $data['value_company_ye'];
                        $subtotal['value_c_ly'] += $data['value_company_lye_end'];
                    }
                }

                $tr_item = $this->replace_tr_template_item($template, $display_data);
                $temp_content_trs .= $tr_item;
            }
        }
        else
        {
            $subtotal['value_g_ty'] += 0;
            $subtotal['value_g_ly'] += 0;

             // calculate subtotal for company
            $subtotal['value_c_ty'] += 0;
            $subtotal['value_c_ly'] += 0;
        }

        return 
            array(
                'temp_content_trs' => $temp_content_trs,
                'subtotal'         => $subtotal
            );
    }

    public function build_tr_template_with_data_fs_fp($fs_company_info, $template, $data)   // for statement fs_financial_position use.
    {
        $temp_content_trs = '';

        $fs_ntfs_list = $this->fs_notes_model->get_fs_ntfs_json();
        $fp_key = array_search("Statement of financial position", array_column($fs_ntfs_list['statements'], 'document_name'));
        $fp_ref_id = '';

         /* ------------------------ Set "Equity and liabilities" title ------------------------ */
        $e_l_title      = '';
        $eq_liabi_title_list = [];

        foreach ($data as $key => $value) 
        {
            $a_description = '';
            $a_key = array_search($value['parent_array'][0]['account_code'], array_column($fs_ntfs_list['statements'][$fp_key]['description_reference_id'], "account_code"));

            if($a_key || (string)$a_key == '0')
            {
                $a_description = $fs_ntfs_list['statements'][$fp_key]['description_reference_id'][$a_key]['description'];
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
        /* ------------------------ END OF Set "Equity and liabilities" title ------------------------ */

        if($fp_key || (string)$fp_key == '0')
        {
            $fp_account_code               = $fs_ntfs_list['statements'][$fp_key]['reference_id']; // get account code
            $description_reference_id_list = $fs_ntfs_list['statements'][$fp_key]['description_reference_id'];
            $fs_ntfs_list                  = $fs_ntfs_list['statements'][$fp_key]['description_reference_id'];
        }

        if($fs_company_info[0]['group_type'] != 1)
        {
            $total_assets_g          = 0.00;
            $total_assets_g_end      = 0.00;
            // $total_assets_g_beg      = 0.00;

            $total_equity_g          = 0.00;
            $total_equity_g_end      = 0.00;
            // $total_equity_g_beg      = 0.00;

            $total_liabilities_g     = 0.00;
            $total_liabilities_g_end = 0.00;
            // $total_liabilities_g_beg = 0.00;
        }

        $total_assets_c          = 0.00;
        $total_assets_c_end      = 0.00;
        // $total_assets_c_beg      = 0.00;

        $total_equity_c          = 0.00;
        $total_equity_c_end      = 0.00;
        // $total_equity_c_beg      = 0.00;

        $total_liabilities_c     = 0.00;
        $total_liabilities_c_end = 0.00;
        // $total_liabilities_c_beg = 0.00;

        // print_r($data);

        $displayed_eq_liabi_title = false;

        // print_r($data);

        foreach ($data as $level_1_key => $level_1) 
        {
            $hide_main_title = false;
            $level_1_description = "";

            $fs_ntfs_list_key = array_search($level_1['parent_array'][0]['account_code'], array_column($fs_ntfs_list, "account_code")); // get key

            if(!empty($fs_ntfs_list_key) || (string)$fs_ntfs_list_key == 0)
            {
                $level_1_description = $fs_ntfs_list[$fs_ntfs_list_key]['description']; // get description from fs_ntfs_list json from document name "Statement of financial position"
            }

            /* ---------------------------- Display main category (Level 1). eg. Assets, Equity and Liabilities ---------------------------- */
            if(count($level_1['child_array']) == 1)
            {
                foreach ($level_1['child_array'] as $key => $value) 
                {
                    // print_r($value);
                    if(empty($value['parent_array']))
                    {
                        $hide_main_title = true;
                    }
                }
            }

            if($level_1_description == "Assets" && (count($level_1['child_array']) > 0) && !$hide_main_title)
            {
                $fp_level_1_description = array(ucfirst(strtolower($level_1['parent_array'][0]['description'])));
                $tr_item = $this->replace_tr_template_item($template['description_bold_tr_template'], $fp_level_1_description);

                $temp_content_trs .= $tr_item;
            }

            if(($level_1_description == "Equity" || $level_1_description == "Liabilities"))
            {
                $empty_inner_item = false;

                if($level_1_description == "Equity")
                {
                    $level_1['child_array'] = array($level_1);
                }
                elseif($level_1_description == "Liabilities")
                {
                    if($level_1['child_array'][0]['parent_array'] == null)
                    {
                        // $level_1['child_array'] = array(array('child_array' => $level_1['child_array']));
                        $level_1['child_array'] = [];
                        $empty_inner_item = true;
                    }
                }

                if(!$displayed_eq_liabi_title && !$empty_inner_item && !empty($e_l_title))
                {
                    $fp_level_1_description = array($e_l_title);
                    $tr_item = $this->replace_tr_template_item($template['description_bold_tr_template'], $fp_level_1_description);

                    $temp_content_trs .= $tr_item;

                    $displayed_eq_liabi_title = true;
                }
            }

            /* ---------------------------- END OF Display main category (Level 1). eg. Assets, Equity and Liabilities ---------------------------- */

            if(!empty($level_1['parent_array']))
            {
                foreach ($level_1['child_array'] as $level_2_key => $level_2) 
                {
                    /* DISPLAY 1 LINE ONLY IF NO CHILD UNDER LEVEL 2 */
                    if(count($level_2['child_array']) > 0)
                    {
                        if(!empty($level_2['parent_array']))
                        {
                            // add level 3 parent description
                            $tr_item = $this->replace_tr_template_item($template['description_bold_italic_tr_template'], array(ucfirst(strtolower($level_2['parent_array'][0]['description']))));
                            $temp_content_trs .= $tr_item;

                            foreach ($level_2['child_array'] as $level_3_key => $level_3)
                            {
                                $temp_template = $template['description_normal_tr_template'];

                                /* DISPLAY LEVEL 3 THAT HAS SUBCATEGORY */
                                if(!empty($level_3['parent_array']))
                                {
                                    $fp_level_3_data = [];

                                    // print_r($level_3['parent_array'][0]['description']);
                                    // get note number if it exists
                                    $fs_notes_details = $this->fs_notes_model->get_selected_note($fs_company_info[0]['id'], $level_3['parent_array'][0]['id']); 

                                    // print_r(array($level_3));

                                    if(count($fs_notes_details) > 0)
                                    {
                                        $note_no = $fs_notes_details[0]['note_num_displayed'];
                                    }
                                    else
                                    {
                                        $temp_template = $this->remove_wr_from_tr($template['description_normal_tr_template'], [2]);
                                    }

                                    if($fs_company_info[0]['group_type'] != 1)
                                    {   
                                        if($fs_company_info[0]['first_set'] == 1)
                                        {
                                            $fp_level_3_data = array(
                                                                    $level_3['parent_array'][0]['description'],
                                                                    $note_no,
                                                                    $this->fs_replace_content_model->negative_bracket($level_3['parent_array'][0]['group_end_this_ye_value']),
                                                                    '',
                                                                    $this->fs_replace_content_model->negative_bracket($level_3['parent_array'][0]['total_c'])
                                                                );
                                        }
                                        else
                                        {
                                            $fp_level_3_data = array(
                                                                    $level_3['parent_array'][0]['description'],
                                                                    $note_no,
                                                                    $this->fs_replace_content_model->negative_bracket($level_3['parent_array'][0]['group_end_this_ye_value']),
                                                                    $this->fs_replace_content_model->negative_bracket($level_3['parent_array'][0]['group_end_prev_ye_value']),
                                                                    '',
                                                                    $this->fs_replace_content_model->negative_bracket($level_3['parent_array'][0]['total_c']),
                                                                    $this->fs_replace_content_model->negative_bracket($level_3['parent_array'][0]['total_c_lye'])
                                                                );
                                        }

                                        $temp_total_g     += $level_3['parent_array'][0]['group_end_this_ye_value'];
                                        $temp_total_g_end += $level_3['parent_array'][0]['group_end_prev_ye_value'];
                                        // $temp_total_g_beg += $level_3['parent_array'][0]['group_beg_prev_ye_value'];
                                    }
                                    else
                                    {
                                        if($fs_company_info[0]['first_set'] == 1)
                                        {
                                            $fp_level_3_data = array(
                                                                    $level_3['parent_array'][0]['description'],
                                                                    $note_no,
                                                                    $this->fs_replace_content_model->negative_bracket($level_3['parent_array'][0]['total_c'])
                                                                );
                                        }
                                        else
                                        {
                                            $fp_level_3_data = array(
                                                                    $level_3['parent_array'][0]['description'],
                                                                    $note_no,
                                                                    $this->fs_replace_content_model->negative_bracket($level_3['parent_array'][0]['total_c']),
                                                                    $this->fs_replace_content_model->negative_bracket($level_3['parent_array'][0]['total_c_lye'])
                                                                );
                                        }
                                        // $temp_total_c_beg += $fp_level_3['parent_array'][0]['company_beg_prev_ye_value'];
                                    }

                                    $temp_total_c     += $level_3['parent_array'][0]['total_c'];
                                    $temp_total_c_end += $level_3['parent_array'][0]['total_c_lye'];

                                    // print_r(array($temp_total_c, $temp_total_c_end));
                                    // print_r($level_3['parent_array']);

                                    // add level 3 parent description
                                    $tr_item = $this->replace_tr_template_item($temp_template, $fp_level_3_data);
                                    $temp_content_trs .= $tr_item;
                                }
                                /* END OF DISPLAY LEVEL 3 THAT HAS SUBCATEGORY */

                                /* DISPLAY LEVEL 3 WITHOUT SUBCATEGORY UNDER IT */
                                elseif($level_1_description == "Liabilities" || $level_1_description == "Assets")
                                {
                                    // print_r(array($level_3['child_array']['description']));
                                    $fp_level_3_data = [];

                                    // get note number if it exists
                                    $fs_notes_details = $this->fs_notes_model->get_selected_note($fs_company_info[0]['id'], $level_3['child_array']['id']);

                                    if(count($fs_notes_details) > 0)
                                    {
                                        $note_no = $fs_notes_details[0]['note_num_displayed'];
                                    }
                                    else
                                    {
                                        $temp_template = $this->remove_wr_from_tr($template['description_normal_tr_template'], [2]);
                                    }


                                    if($fs_company_info[0]['group_type'] != 1)
                                    {   
                                        if($fs_company_info[0]['first_set'] == 1)
                                        {
                                            $fp_level_3_data = array(
                                                                    $level_3['child_array']['description'],
                                                                    $note_no,
                                                                    $this->fs_replace_content_model->negative_bracket($level_3['child_array']['group_end_this_ye_value']),
                                                                    '',
                                                                    $this->fs_replace_content_model->negative_bracket($level_3['child_array']['value'])
                                                                );
                                        }
                                        else
                                        {
                                            $fp_level_3_data = array(
                                                                    $level_3['child_array']['description'],
                                                                    $note_no,
                                                                    $this->fs_replace_content_model->negative_bracket($level_3['child_array']['group_end_this_ye_value']),
                                                                    $this->fs_replace_content_model->negative_bracket($level_3['child_array']['group_end_prev_ye_value']),
                                                                    '',
                                                                    $this->fs_replace_content_model->negative_bracket($level_3['child_array']['value']),
                                                                    $this->fs_replace_content_model->negative_bracket($level_3['child_array']['company_end_prev_ye_value'])
                                                                );
                                        }

                                        $temp_total_g     += $level_3['child_array']['group_end_this_ye_value'];
                                        $temp_total_g_end += $level_3['child_array']['group_end_prev_ye_value'];
                                        // $temp_total_g_beg += $level_3['parent_array'][0]['group_beg_prev_ye_value'];
                                    }
                                    else
                                    {
                                        if($fs_company_info[0]['first_set'] == 1)
                                        {
                                            $fp_level_3_data = array(
                                                                    $level_3['child_array']['description'],
                                                                    $note_no,
                                                                    $this->fs_replace_content_model->negative_bracket($level_3['child_array']['value'])
                                                                );
                                        }
                                        else
                                        {
                                            $fp_level_3_data = array(
                                                                    $level_3['child_array']['description'],
                                                                    $note_no,
                                                                    $this->fs_replace_content_model->negative_bracket($level_3['child_array']['value']),
                                                                    $this->fs_replace_content_model->negative_bracket($level_3['child_array']['company_end_prev_ye_value'])
                                                                );
                                        }
                                        // $temp_total_c_beg += $fp_level_3['parent_array'][0]['company_beg_prev_ye_value'];
                                    }

                                    // print_r($level_3);

                                    $temp_total_c     += $level_3['child_array']['value'];
                                    $temp_total_c_end += $level_3['child_array']['company_end_prev_ye_value'];

                                    // add level 3 parent description
                                    $tr_item = $this->replace_tr_template_item($temp_template, $fp_level_3_data);
                                    $temp_content_trs .= $tr_item;
                                }
                                /* END OF DISPLAY LEVEL 3 WITHOUT SUBCATEGORY UNDER IT */
                            }
                                    
                            // print_r("CONTINUE TOTAL - 6/3/2020");

                            /* DISPLAY TOTAL FOR EACH CATEGORY  */
                            $temp_template = $template['subtotal_tr_template'];

                            if($fs_company_info[0]['group_type'] != 1)   // FOR GROUP
                            {
                                $total      = $temp_total_g;
                                $total_end  = $temp_total_g_end;
                                // $total_beg  = $temp_total_g_beg;
                            }
                            else    // FOR COMPANY
                            {
                                $total      = $temp_total_c;
                                $total_end  = $temp_total_c_end;
                                // $total_beg  = $temp_total_c_beg;
                            }

                            /* CALCULATE TOTAL ASSETS, TOTAL EQUITY, LIABILITIES - GROUP */
                            if($level_1_description == "Assets")    // NON-CURRENT ASSETS || CURRENT ASSETS
                            {
                                $total_assets_g     += $total;
                                $total_assets_g_end += $total_end;
                                // $total_assets_g_beg += $total_beg;

                                $total_assets_c     += $temp_total_c;
                                $total_assets_c_end += $temp_total_c_end;
                                // $total_assets_c_beg += $temp_total_c_beg;
                            }
                            elseif($level_1_description == "Equity") // EQUITY
                            {
                                $total_equity_g     += $total;
                                $total_equity_g_end += $total_end;
                                // $total_equity_g_beg += $total_beg;

                                $total_equity_c     += $temp_total_c;
                                $total_equity_c_end += $temp_total_c_end;
                                // $total_equity_c_beg += $temp_total_c_beg;
                            }
                            elseif($level_1_description == "Liabilities") // NON-CURRENT LIABILITIES || CURRENT LIABILITIES
                            {
                                $total_liabilities_g     += $total;
                                $total_liabilities_g_end += $total_end;
                                // $total_liabilities_g_beg += $total_beg;

                                $total_liabilities_c     += $temp_total_c;
                                $total_liabilities_c_end += $temp_total_c_end;
                                // $total_liabilities_c_beg += $temp_total_c_beg;
                            }
                            /* END OF CALCULATE TOTAL ASSETS, TOTAL EQUITY, LIABILITIES */

                            if($fs_company_info[0]['group_type'] != 1)
                            {   
                                if($fs_company_info[0]['first_set'] == 1)
                                {
                                    $fp_subtotal_data = array(
                                                            '',
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($temp_total_g),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($temp_total_c)
                                                        );
                                }
                                else
                                {
                                    $fp_subtotal_data = array(
                                                            '',
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($temp_total_g),
                                                            $this->fs_replace_content_model->negative_bracket($temp_total_g_end),
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($temp_total_c),
                                                            $this->fs_replace_content_model->negative_bracket($temp_total_c_end)
                                                        );
                                }
                            }
                            else
                            {
                                if($fs_company_info[0]['first_set'] == 1)
                                {
                                    $fp_subtotal_data = array(
                                                            '',
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($temp_total_c)
                                                        );
                                }
                                else
                                {
                                    $fp_subtotal_data = array(
                                                            '',
                                                            '',
                                                            $this->fs_replace_content_model->negative_bracket($temp_total_c),
                                                            $this->fs_replace_content_model->negative_bracket($temp_total_c_end)
                                                        );
                                }
                            }

                            // display subtotal
                            $tr_item = $this->replace_tr_template_item($temp_template, $fp_subtotal_data);

                            $temp_content_trs .= $tr_item;

                            /* END OF DISPLAY TOTAL FOR EACH CATEGORY */

                            // if($fs_company_info[0]['group_type'] != 1)
                            // {
                            //     /* CALCULATE TOTAL ASSETS, TOTAL EQUITY, LIABILITIES - GROUP */
                            //     if($level_1_description == "Assets")    // NON-CURRENT ASSETS || CURRENT ASSETS
                            //     {
                            //         $total_assets_g     += $temp_total_g;
                            //         $total_assets_g_end += $temp_total_g_end;
                            //         // $total_assets_g_beg += $temp_total_g_beg;
                            //     }
                            //     elseif($level_1_description == "Equity") // EQUITY
                            //     {
                            //         $total_equity_g     += $temp_total_g;
                            //         $total_equity_g_end += $temp_total_g_end;
                            //         // $total_equity_g_beg += $temp_total_g_beg;
                            //     }
                            //     elseif($level_1_description == "Liabilities") // NON-CURRENT LIABILITIES || CURRENT LIABILITIES
                            //     {
                            //         $total_liabilities_g     += $temp_total_g;
                            //         $total_liabilities_g_end += $temp_total_g_end;
                            //         // $total_liabilities_g_beg += $temp_total_g_beg;
                            //     }
                            //     /* END OF CALCULATE TOTAL ASSETS, TOTAL EQUITY, LIABILITIES */

                            //     $temp_total_g     = 0.00;
                            //     $temp_total_g_end = 0.00;
                            //     $temp_total_g_beg = 0.00;
                            // }
                            // else
                            // {
                            //     /* CALCULATE TOTAL ASSETS, TOTAL EQUITY, LIABILITIES - COMPANY */
                            //     if($level_1_description == "Assets")    // NON-CURRENT ASSETS || CURRENT ASSETS
                            //     {
                            //         $total_assets_c     += $temp_total_c;
                            //         $total_assets_c_end += $temp_total_c_end;
                            //         // $total_assets_c_beg += $temp_total_c_beg;
                            //     }
                            //     elseif($level_1_description == "Equity") // EQUITY
                            //     {
                            //         $total_equity_c     += $temp_total_c;
                            //         $total_equity_c_end += $temp_total_c_end;
                            //         // $total_equity_c_beg += $temp_total_c_beg;
                            //     }
                            //     elseif($level_1_description == "Liabilities") // NON-CURRENT LIABILITIES || CURRENT LIABILITIES
                            //     {
                            //         $total_liabilities_c     += $temp_total_c;
                            //         $total_liabilities_c_end += $temp_total_c_end;
                            //         // $total_liabilities_c_beg += $temp_total_c_beg;
                            //     }
                            //     /* END OF CALCULATE TOTAL ASSETS, TOTAL EQUITY, LIABILITIES */

                            //     $temp_total_c     = 0.00;
                            //     $temp_total_c_end = 0.00;
                            //     $temp_total_c_beg = 0.00;
                            // }

                            if($fs_company_info[0]['group_type'] != 1)
                            {
                                $temp_total_g     = 0.00;
                                $temp_total_g_end = 0.00;
                                $temp_total_g_beg = 0.00;
                            }

                            $temp_total_c     = 0.00;
                            $temp_total_c_end = 0.00;
                            $temp_total_c_beg = 0.00;
                        }
                    }
                }
            }

            /* DISPLAY TOTAL ASSETS */
            if($level_1_key == 0 && $level_1_description == "Assets")
            {
                $temp_template = $template['overall_total_tr_template'];

                if($fs_company_info[0]['group_type'] != 1)
                {   
                    if($fs_company_info[0]['first_set'] == 1)
                    {
                        $fp_total_assets_data = array(
                                                'Total assets',
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_assets_g),
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_assets_c)
                                            );
                    }
                    else
                    {
                        $fp_total_assets_data = array(
                                                'Total assets',
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_assets_g),
                                                $this->fs_replace_content_model->negative_bracket($total_assets_g_end),
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_assets_c),
                                                $this->fs_replace_content_model->negative_bracket($total_assets_c_end)
                                            );
                    }
                }
                else
                {
                    if($fs_company_info[0]['first_set'] == 1)
                    {
                        $fp_total_assets_data = array(
                                                'Total assets',
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_assets_c)
                                            );
                    }
                    else
                    {
                        $fp_total_assets_data = array(
                                                'Total assets',
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_assets_c),
                                                $this->fs_replace_content_model->negative_bracket($total_assets_c_end)
                                            );
                    }
                }

                // display subtotal
                $tr_item = $this->replace_tr_template_item($temp_template, $fp_total_assets_data);

                $temp_content_trs .= $tr_item .  $template['newline_template'];  
            }
            /* END OF DISPLAY TOTAL ASSETS */

            /* DISPLAY TOTAL LIABILITIES */
            if($level_1_description == "Liabilities")
            {
                $temp_template = $this->remove_wr_from_tr($template['last_description_bold_tr_template'], [2]);

                $total_liabilities     = 0.00;
                $total_liabilities_end = 0.00;
                $total_liabilities_beg = 0.00;

                if($fs_company_info[0]['first_set'] == 1)
                {
                    $total_liabilities     = $total_liabilities_g;
                    $total_liabilities_end = $total_liabilities_g_end;
                    $total_liabilities_beg = $total_liabilities_g_beg;
                }

                $total_liabilities     = $total_liabilities_c;
                $total_liabilities_end = $total_liabilities_c_end;
                $total_liabilities_beg = $total_liabilities_c_beg;


                if($fs_company_info[0]['group_type'] != 1)
                {   
                    if($fs_company_info[0]['first_set'] == 1)
                    {
                        $fp_total_liabilities_data = array(
                                                'Total liabilities',
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_liabilities_g),
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_liabilities_c)
                                            );
                    }
                    else
                    {
                        $fp_total_liabilities_data = array(
                                                'Total liabilities',
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_liabilities_g),
                                                $this->fs_replace_content_model->negative_bracket($total_liabilities_g_end),
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_liabilities_c),
                                                $this->fs_replace_content_model->negative_bracket($total_liabilities_c_end)
                                            );
                    }
                }
                else
                {
                    if($fs_company_info[0]['first_set'] == 1)
                    {
                        $fp_total_liabilities_data = array(
                                                'Total liabilities',
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_liabilities_c)
                                            );
                    }
                    else
                    {
                        $fp_total_liabilities_data = array(
                                                'Total liabilities',
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_liabilities_c),
                                                $this->fs_replace_content_model->negative_bracket($total_liabilities_c_end)
                                            );
                    }
                }

                // display total liabilities
                $tr_item = $this->replace_tr_template_item($temp_template, $fp_total_liabilities_data);

                $temp_content_trs .= $tr_item . $template['newline_template'];
            }
            /* END OF DISPLAY TOTAL LIABILITIES */

            /* ------------------------- DISPLAY TOTAL LIABILITES & TOTAL EQUITY & LIABILITIES ------------------------- */
            elseif($level_1_key == count($data) - 1)
            {
                

                // display total equity liabilities
                $total_equity_liabilities     = 0.00;
                $total_equity_liabilities_end = 0.00;
                $total_equity_liabilities_beg = 0.00;

                if($fs_company_info[0]['group_type'] != 1)
                {
                    $total_equity_liabilities_g     = $total_equity_g     + $total_liabilities_g;
                    $total_equity_liabilities_end_g = $total_equity_g_end + $total_liabilities_g_end;
                    $total_equity_liabilities_beg_g = $total_equity_g_beg + $total_liabilities_g_beg;

                    // print_r(array($total_equity_g, $total_liabilities_g));
                }

                $total_equity_liabilities_c     = $total_equity_c     + $total_liabilities_c;
                $total_equity_liabilities_end_c = $total_equity_c_end + $total_liabilities_c_end;
                $total_equity_liabilities_beg_c = $total_equity_c_beg + $total_liabilities_c_beg;

                $temp_template = $template['overall_total_tr_template'];

                if($fs_company_info[0]['group_type'] != 1)
                {   
                    if($fs_company_info[0]['first_set'] == 1)
                    {
                        $fp_total_equity_liabilities_data = array(
                                                'Total equity and liabilities',
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_equity_liabilities_g),
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_equity_liabilities_c)
                                            );
                    }
                    else
                    {
                        $fp_total_equity_liabilities_data = array(
                                                'Total equity and liabilities',
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_equity_liabilities_g),
                                                $this->fs_replace_content_model->negative_bracket($total_equity_liabilities_end_g),
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_equity_liabilities_c),
                                                $this->fs_replace_content_model->negative_bracket($total_equity_liabilities_end_c)
                                            );
                    }
                }
                else
                {
                    if($fs_company_info[0]['first_set'] == 1)
                    {
                        $fp_total_equity_liabilities_data = array(
                                                'Total equity and liabilities',
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_equity_liabilities_c)
                                            );
                    }
                    else
                    {
                        $fp_total_equity_liabilities_data = array(
                                                'Total equity and liabilities',
                                                '',
                                                $this->fs_replace_content_model->negative_bracket($total_equity_liabilities_c),
                                                $this->fs_replace_content_model->negative_bracket($total_equity_liabilities_end_c)
                                            );
                    }
                }

                // display total liabilities
                $tr_item = $this->replace_tr_template_item($temp_template, $fp_total_equity_liabilities_data);

                $temp_content_trs .= $tr_item;  
            }
            /* ------------------------- END OF DISPLAY TOTAL LIABILITES & TOTAL EQUITY & LIABILITIES ------------------------- */
        }

        return 
            array(
                'temp_content_trs' => $temp_content_trs
            );
    }

    public function build_tr_template_with_data_fs_cf($fs_company_info, $template, $data)
    {
        // $fixed_title_operating_expenses = array('Operating activities');

        // $tr_item = $this->replace_tr_template_item($template['fixed_title'], $data['fixed_title']);

        // $temp_content_trs .= $tr_item;

        $temp_content_trs = '';
        $tr_item = '';
        $note_no = '';

        $fs_cf_type                = $data['fs_cf_type'];
        $fs_cf_type_title          = $data['fs_cf_type_title'];
        $fs_state_cash_flows_fixed = $data['fs_state_cash_flows_fixed'];

        $temp_data = [];

        $display_tr_template = '';
        $display_tr_template = $template['display_tr_template'];

        if(count($fs_state_cash_flows_fixed[$fs_cf_type]['note_display_num']) == 0)
        {
            $display_tr_template = $this->remove_wr_from_tr($display_tr_template, [2]);
        }
        else
        {
            $note_no = $fs_state_cash_flows_fixed[$fs_cf_type]['note_display_num'][0]['note_num_displayed'];
        }

        // insert data
        if($fs_company_info[0]['group_type'] != 1)
        {   
            if($fs_company_info[0]['first_set'] == 1)
            {
                $temp_data = array(
                                $fs_cf_type_title,
                                $note_no,
                                $this->fs_replace_content_model->negative_bracket($fs_state_cash_flows_fixed[$fs_cf_type]['group_ye']),
                                '',
                                $this->fs_replace_content_model->negative_bracket($fs_state_cash_flows_fixed[$fs_cf_type]['company_ye']),
                            );
            }
            else
            {
                $temp_data = array(
                                $fs_cf_type_title,
                                $note_no,
                                $this->fs_replace_content_model->negative_bracket($fs_state_cash_flows_fixed[$fs_cf_type]['group_ye']),
                                $this->fs_replace_content_model->negative_bracket($fs_state_cash_flows_fixed[$fs_cf_type]['group_lye_end']),
                                '',
                                $this->fs_replace_content_model->negative_bracket($fs_state_cash_flows_fixed[$fs_cf_type]['company_ye']),
                                $this->fs_replace_content_model->negative_bracket($fs_state_cash_flows_fixed[$fs_cf_type]['company_lye_end'])
                            );
            }
        }
        else
        {
            if($fs_company_info[0]['first_set'] == 1)
            {
                 $temp_data = array(
                                $fs_cf_type_title,
                                $note_no,
                                $this->fs_replace_content_model->negative_bracket($fs_state_cash_flows_fixed[$fs_cf_type]['company_ye'])
                            );
            }
            else
            {
                $temp_data = array(
                                $fs_cf_type_title,
                                $note_no,
                                $this->fs_replace_content_model->negative_bracket($fs_state_cash_flows_fixed[$fs_cf_type]['company_ye']),
                                $this->fs_replace_content_model->negative_bracket($fs_state_cash_flows_fixed[$fs_cf_type]['company_lye_end'])
                            );
            }
        }

        $tr_item = $this->replace_tr_template_item($display_tr_template, $temp_data);

        $temp_content_trs .= $tr_item;

        return $temp_content_trs;
    }

    public function build_tr_template_with_data_fs_cf_subs($fs_company_info, $template, $data)
    {
        $sub_changes = [];
        $temp_content_trs = '';

        // display title
        if(!empty($data['fixed_title']))
        {   
            $fixed_title = $data['fixed_title'];
            $tr_template_for_fixed_title = $template['fixed_title'];

            $tr_item = $this->replace_tr_template_item($tr_template_for_fixed_title, $fixed_title);

            $temp_content_trs .= $tr_item;
        }

        // collect data
        foreach ($data['fs_state_cash_flows'] as $fscf_key => $fscf_value) 
        {
            $temp_data = [];

            if($fscf_value['parent_id'] == $data['parent_id'] && $fscf_value['category_id'] == $data['category_id'])
            {
                // if($fs_company_info[0]['group_type'] != 1)
                // {
                //     if($fs_company_info[0]['first_set'] != 1)
                //     {
                //         $temp_data = array(
                //                         $fscf_value['description'],
                //                         $fscf_value['note_num_displayed'],
                //                         $this->fs_replace_content_model->negative_bracket($fscf_value['value_group_ye']),
                //                         $this->fs_replace_content_model->negative_bracket($fscf_value['value_group_lye_end']),
                //                         '',
                //                         $this->fs_replace_content_model->negative_bracket($fscf_value['value_company_ye']),
                //                         $this->fs_replace_content_model->negative_bracket($fscf_value['value_company_lye_end'])
                //                     );

                //         array_push($sub_changes, array('temp_data' => $temp_data, 'note_num_displayed' => $fscf_value['note_num_displayed']));
                //     }
                // }

                // insert data
                if($fs_company_info[0]['group_type'] != 1)
                {   
                    if($fs_company_info[0]['first_set'] == 1)
                    {
                        $temp_data = array(
                                        $fscf_value['description'],
                                        $fscf_value['note_num_displayed'],
                                        $this->fs_replace_content_model->negative_bracket($fscf_value['value_group_ye']),
                                        '',
                                        $this->fs_replace_content_model->negative_bracket($fscf_value['value_company_ye'])
                                    );

                        array_push($sub_changes, array('temp_data' => $temp_data, 'note_num_displayed' => $fscf_value['note_num_displayed']));
                    }
                    else
                    {
                        $temp_data = array(
                                        $fscf_value['description'],
                                        $fscf_value['note_num_displayed'],
                                        $this->fs_replace_content_model->negative_bracket($fscf_value['value_group_ye']),
                                        $this->fs_replace_content_model->negative_bracket($fscf_value['value_group_lye_end']),
                                        '',
                                        $this->fs_replace_content_model->negative_bracket($fscf_value['value_company_ye']),
                                        $this->fs_replace_content_model->negative_bracket($fscf_value['value_company_lye_end'])
                                    );

                        array_push($sub_changes, array('temp_data' => $temp_data, 'note_num_displayed' => $fscf_value['note_num_displayed']));
                    }
                }
                else
                {
                    if($fs_company_info[0]['first_set'] == 1)
                    {
                         $temp_data = array(
                                        $fs_cf_type_title,
                                        $note_no,
                                        $this->fs_replace_content_model->negative_bracket($fs_state_cash_flows_fixed[$fs_cf_type]['company_ye'])
                                    );

                         $temp_data = array(
                                        $fscf_value['description'],
                                        $fscf_value['note_num_displayed'],
                                        $this->fs_replace_content_model->negative_bracket($fscf_value['value_company_ye'])
                                    );

                        array_push($sub_changes, array('temp_data' => $temp_data, 'note_num_displayed' => $fscf_value['note_num_displayed']));
                    }
                    else
                    {
                        $temp_data = array(
                                        $fs_cf_type_title,
                                        $note_no,
                                        $this->fs_replace_content_model->negative_bracket($fs_state_cash_flows_fixed[$fs_cf_type]['company_ye']),
                                        $this->fs_replace_content_model->negative_bracket($fs_state_cash_flows_fixed[$fs_cf_type]['company_lye_end'])
                                    );

                        $temp_data = array(
                                        $fscf_value['description'],
                                        $fscf_value['note_num_displayed'],
                                        $this->fs_replace_content_model->negative_bracket($fscf_value['value_company_ye']),
                                        $this->fs_replace_content_model->negative_bracket($fscf_value['value_company_lye_end'])
                                    );

                        array_push($sub_changes, array('temp_data' => $temp_data, 'note_num_displayed' => $fscf_value['note_num_displayed']));
                    }
                }
            }
        }

        // display sub
        for ($count = count($sub_changes) - 1; $count >= 0; $count--) 
        {
            $display_tr_template = '';
            $tr_item = '';

            // if($sub_c_key + 1 != count($sub_changes))
            if($count != 0)
            {
                $display_tr_template = $template['sub_description_normal_tr_template'];
            }
            else
            {   
                if(!empty($template['last_sub_description_normal_tr_template']))
                {
                    $display_tr_template = $template['last_sub_description_normal_tr_template'];
                }
                else
                {
                    $display_tr_template = $template['sub_description_normal_tr_template'];
                }
            }

            if(empty($sub_changes[$count]['note_num_displayed']))
            {
                $display_tr_template = $this->remove_wr_from_tr($display_tr_template, [2]);
            }

            $tr_item = $this->replace_tr_template_item($display_tr_template, $sub_changes[$count]['temp_data']);
            
            $temp_content_trs .= $tr_item;
        }

        return $temp_content_trs;
        
    }

    public function build_subtotal_template($fs_company_info, $data, $total_tr_template)
    {
        $temp_content_trs = '';

         /* display subtotal */
        if(!empty($total_tr_template))
        {
            $tr_item = '';

            $subtotal_data = [];

            if($fs_company_info[0]['group_type'] == 1)    // for company
            {
                if($fs_company_info[0]['first_set'] == 1)
                {
                    $subtotal_data = array(
                                        isset($data['description']) ? $data['description'] : '',
                                        '',
                                        $this->fs_replace_content_model->negative_bracket($data['value_c_ty'])
                                    );
                }
                else
                {
                    $subtotal_data = array(
                                        isset($data['description']) ? $data['description'] : '',
                                        '',
                                        $this->fs_replace_content_model->negative_bracket($data['value_c_ty']),
                                        $this->fs_replace_content_model->negative_bracket($data['value_c_ly'])
                                    );
                }
            }
            else // for group
            {
                if($fs_company_info[0]['first_set'] == 1)
                {
                    $subtotal_data = array(
                                        isset($data['description']) ? $data['description'] : '',
                                        '',
                                        $this->fs_replace_content_model->negative_bracket($data['value_g_ty']),
                                        '', 
                                        $this->fs_replace_content_model->negative_bracket($data['value_c_ty'])
                                    );
                }
                else
                {
                    $subtotal_data = array(
                                        isset($data['description']) ? $data['description'] : '',
                                        '',
                                        $this->fs_replace_content_model->negative_bracket($data['value_g_ty']),
                                        $this->fs_replace_content_model->negative_bracket($data['value_g_ly']),
                                        '', 
                                        $this->fs_replace_content_model->negative_bracket($data['value_c_ty']),
                                        $this->fs_replace_content_model->negative_bracket($data['value_c_ly'])
                                    );
                }
            }
            
            $tr_item = $this->replace_tr_template_item($total_tr_template, $subtotal_data);
            $temp_content_trs .= $tr_item;
        }

        return $temp_content_trs;
    }

    public function get_state_changes_in_equity_data($fs_company_info_id, $group_company)
    {
        // $temp_fs_state_changes_in_equity_current = $this->fs_statements_model->get_fs_state_changes_in_equity($fs_company_info_id, "current", $group_company);
        // foreach ($temp_fs_state_changes_in_equity_current as $key => &$row) 
        // {
        //     $temp_row = $row['row_item'];
        //     $temp_row = explode(",", $temp_row);

        //     $row['row_item'] = $temp_row;
        // }

        // $temp_fs_state_changes_in_equity_prior = $this->fs_statements_model->get_fs_state_changes_in_equity($fs_company_info_id, "prior", $group_company);
        // foreach ($temp_fs_state_changes_in_equity_prior as $key => &$row) 
        // {
        //     $temp_row = $row['row_item'];
        //     $temp_row = explode(",", $temp_row);

        //     $row['row_item'] = $temp_row;
        // }

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

        return array(
                    // 'fs_state_changes_in_equity_current' => $temp_fs_state_changes_in_equity_current,
                    // 'fs_state_changes_in_equity_prior'   => $temp_fs_state_changes_in_equity_prior,
                    'fs_state_changes_in_equity_header'  => $temp_header,
                    'fs_state_changes_in_equity_footer'  => $temp_fs_state_changes_in_equity_footer
                );
    }

    public function build_template_with_data_fs_changes_in_equity($tbl_template, $data)
    {
        $ori_all_template = [];
        $temp_content_trs = '';
        $replaced_tbl_template = $tbl_template;

        $fs_company_info = $this->fs_model->get_fs_company_info($data['fs_company_info_id']);

        // $replaced_tbl_template = $this->hide_tbl_column($fs_company_info, $hide_column_data, $replaced_tbl_value, $replaced_xml);    // group_type, $hide_column_data, ...
        // $replaced_xml = str_replace($tbl_template, $replaced_tbl_template, $replaced_xml);
        $replaced_tbl_template_2 = $replaced_tbl_template;
        
        preg_match_all ('/<w:tblGrid>(.*?)<\/w:tblGrid>/s', $tbl_template, $wtblGrid_tag_name);         // get w:tblGrid tag
        preg_match_all ('/<w:gridCol w:w="(.*?)"\/>/s', $wtblGrid_tag_name[0][0], $wgridCol_tag_name);  // get w:gridCol tag

        $dynamic_col_num = count($data['fs_state_changes_in_equity_header']);   // number of column from database (dynamic).

        if($dynamic_col_num <= 5)  // portrait
        {
            $tblW_default = 9560;   // we set table width = 9560

            $tblW_first_col_desc_width  = $wgridCol_tag_name[1][0];    // get first column width from template
            $tbl_col_dynamic_cols_width = ceil(($tblW_default - $tblW_first_col_desc_width) / $dynamic_col_num);    // set new column width for the dynamic columns
            $overall_tblW_used_width    = $tblW_first_col_desc_width + ($tbl_col_dynamic_cols_width * $dynamic_col_num);   // set new table width

            // if($data['group_company'] == 'group')
            // {
            //     // print_r($tbl_col_dynamic_cols_width);
            // }
        }
        elseif($dynamic_col_num > 5) // landscape
        {
            // print_r($dynamic_col_num);

            $tblW_default = 13460;   // we set table width = 13460

            $tblW_first_col_desc_width  = $wgridCol_tag_name[1][0];    // get first column width from template
            $tbl_col_dynamic_cols_width = ceil(($tblW_default - $tblW_first_col_desc_width) / $dynamic_col_num);    // set new column width for the dynamic columns
            $overall_tblW_used_width    = $tblW_first_col_desc_width + ($tbl_col_dynamic_cols_width * $dynamic_col_num);   // set new table width
        }

        // modify overall table width (tblW) to new width value
        preg_match_all ('/<w:tblW w:w="(.*?)"\/>/s', $tbl_template, $wtblW_tag_name);     // get tag <w:tblW w:w="9561" w:type="dxa"/>
        preg_match('/w:w="(.*?)"/s', $wtblW_tag_name[0][0], $overall_tblW_width_default); // get value "9561" in w:w="9561"

        // build <w:tblGrid><w:gridCol w:w="3063"/></w:tblGrid>
        $replaced_wtblGrid_tag = '<w:tblGrid>' . '<w:gridCol w:w="' . $tblW_first_col_desc_width . '"/>' . str_repeat('<w:gridCol w:w="' . $tbl_col_dynamic_cols_width . '"/>', $dynamic_col_num) . '</w:tblGrid>';

        // replace tbl content
        $replaced_tbl_template = preg_replace('/' . $overall_tblW_width_default[0] . '/s', 'w:w="' . $overall_tblW_used_width . '"', $replaced_tbl_template); 
        $replaced_tbl_template = preg_replace('/<w:tblGrid>(.*?)<\/w:tblGrid>/s', $replaced_wtblGrid_tag, $replaced_tbl_template); 

        $tbl_tr = $this->get_tbl_tr_template($replaced_tbl_template);   // get tr 

        $taken_last_line_space = false;

        // get templates and remove all the info first
        foreach ($tbl_tr[0] as $tbl_tr_key => $tbl_tr_value) 
        {
            $tr_name_type = $this->get_tr_name_type($tbl_tr_value);  // to extract the first column name so that we know it is title or account name and value insert template.
            $width_list = [$tblW_first_col_desc_width];

            for($i = 0; $i < $dynamic_col_num;  $i++)
            {
                array_push($width_list, $tbl_col_dynamic_cols_width);
            }

            $width_data = array(
                            'width'         => $width_list,
                            'skip_col'      => [1],
                            'collect_template_col' => [2]
                        );

            if($taken_last_line_space)
            {
                $replaced_tbl_template_2 = str_replace($tbl_tr_value, '', $replaced_tbl_template_2);
            }
            elseif($tr_name_type == "{New Line}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $width_data = array(
                            'width'     => array($tblW_first_col_desc_width, $overall_tblW_used_width - $tblW_first_col_desc_width),
                            'skip_col'  => [1],
                            'gridspan_col'   => [2],
                            'gridspan_value' => [$dynamic_col_num]
                        );

                $newline_template = $this->vanish_template($tbl_tr_value, 0); 
                $newline_template = $this->remove_wr_from_tr($newline_template, [1]); 
                $newline_template = $this->add_table_new_column($newline_template, array('columns_needed' => $dynamic_col_num + 1, 'copy_column' => 2)); 
                $newline_template = $this->change_tr_tc_width($newline_template, $width_data); 
            }
            elseif($tr_name_type == "{Header display}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $width_data = array(
                            'width'     => array($tblW_first_col_desc_width, $overall_tblW_used_width - $tblW_first_col_desc_width),
                            'skip_col'  => [1],
                            'gridspan_col'   => [2],
                            'gridspan_value' => [$dynamic_col_num]
                        );

                $header_display_tr_template = $this->vanish_template($tbl_tr_value, 0); 
                $header_display_tr_template = $this->remove_wr_from_tr($header_display_tr_template, [1]);
                $header_display_tr_template = $this->add_table_new_column($header_display_tr_template, array('columns_needed' => $dynamic_col_num + 1, 'copy_column' => 2));
                $header_display_tr_template = $this->change_tr_tc_width($header_display_tr_template, $width_data);
            }
            elseif($tr_name_type == "{Header title}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $header_title_tr_template = $this->vanish_template($tbl_tr_value, 0); 
                $header_title_tr_template = $this->remove_wr_from_tr($header_title_tr_template, [1]); 
                $header_title_tr_template = $this->add_table_new_column($header_title_tr_template, array('columns_needed' => $dynamic_col_num + 1, 'copy_column' => 2));
                $header_title_tr_template = $this->change_tr_tc_width($header_title_tr_template, $width_data); 
            }
            elseif($tr_name_type == "{Dollar Sign}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $dollar_sign_tr_template = $this->vanish_template($tbl_tr_value, 0); 
                $dollar_sign_tr_template = $this->remove_wr_from_tr($dollar_sign_tr_template, [1]); 
                $dollar_sign_tr_template = $this->add_table_new_column($dollar_sign_tr_template, array('columns_needed' => $dynamic_col_num + 1, 'copy_column' => 2));
                $dollar_sign_tr_template = $this->change_tr_tc_width($dollar_sign_tr_template, $width_data); 
            }
            elseif($tr_name_type == "{Description – bold}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $description_bold_tr_template = $this->vanish_template($tbl_tr_value, 0);
                $description_bold_tr_template = $this->add_table_new_column($description_bold_tr_template, array('columns_needed' => $dynamic_col_num + 1, 'copy_column' => 2));
                $description_bold_tr_template = $this->change_tr_tc_width($description_bold_tr_template, $width_data); 
            }
            elseif($tr_name_type == "{Description – normal}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $description_normal_tr_template = $this->vanish_template($tbl_tr_value, 0);
                $description_normal_tr_template = $this->add_table_new_column($description_normal_tr_template, array('columns_needed' => $dynamic_col_num + 1, 'copy_column' => 2));
                $description_normal_tr_template = $this->change_tr_tc_width($description_normal_tr_template, $width_data); 

                // print_r($description_normal_tr_template);
            }
            elseif($tr_name_type == "{Last description – normal}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $last_description_normal_tr_template = $this->vanish_template($tbl_tr_value, 0);
                $last_description_normal_tr_template = $this->add_table_new_column($last_description_normal_tr_template, array('columns_needed' => $dynamic_col_num + 1, 'copy_column' => 2));
                $last_description_normal_tr_template = $this->change_tr_tc_width($last_description_normal_tr_template, $width_data); 
            }
            elseif($tr_name_type == "{Overall total}")
            {
                array_push($ori_all_template, $tbl_tr_value);
                $overall_total_tr_template = $this->vanish_template($tbl_tr_value, 0);
                $overall_total_tr_template = $this->add_table_new_column($overall_total_tr_template, array('columns_needed' => $dynamic_col_num + 1, 'copy_column' => 2));
                $overall_total_tr_template = $this->change_tr_tc_width($overall_total_tr_template, $width_data); 
            }
            elseif($tr_name_type == "{Last Line space}") 
            {
                array_push($ori_all_template, $tbl_tr_value);
                $last_line_space_tr_template = $tbl_tr_value;   // for replace value later
                $taken_last_line_space = true;
            }
            elseif($tr_name_type == $data['table_name'])
            {
                array_push($ori_all_template, $tbl_tr_value);
            }
            else
            {
                // incase $taken_last_line_space is not working
                $replaced_tbl_template_2 = str_replace($tbl_tr_value, '', $replaced_tbl_template_2);
            }
        }

        /* Display header (Group/Company) */
        $info_data = [''];
        $info_data = ['', ucfirst($data['group_company'])];
        $temp_content_trs .= $this->replace_tr_template_item($header_display_tr_template, $info_data); 
        /* END OF Display header (Group/Company) */

        /* Display header title with data */
        $info_data = [''];
        foreach ($data['fs_state_changes_in_equity_header'] as $cie_key => $cie_value) {    array_push($info_data, $cie_value);    }
        $temp_content_trs .= $this->replace_tr_template_item($header_title_tr_template, $info_data);
        /* END OF Display header title with data */

        /* Display Dollar Sign */
        $temp_content_trs .= $dollar_sign_tr_template;
        /* END OF Display Dollar Sign */

        $temp_fs_state_changes_in_equity_footer = $this->fs_statements_model->get_fs_state_changes_in_equity_footer($data['fs_company_info_id'], $data['group_company']);   // get footer data for both year

        /* --------------------- Display data rows for prior year --------------------- */

        $cie_prior_group = $this->fs_statements_model->get_fs_state_changes_in_equity($data['fs_company_info_id'], "prior", $data['group_company']);    // get row data from database (current year)

        if(count($cie_prior_group) > 0 && !$fs_company_info[0]['first_set'])
        {
            $temp_content_trs .= $this->replace_tr_template_item($description_bold_tr_template, ['Prior year']);  // display title "Current year"

            foreach ($cie_prior_group as $p_key => $p_value) 
            {
                $info_data = [];
                array_push($info_data, $p_value['description']);

                $temp_row = explode(",", $p_value['row_item']);

                foreach ($temp_row as $temp_row_key => $temp_row_value) 
                {
                    array_push($info_data, $this->fs_replace_content_model->negative_bracket($temp_row_value));
                }

                if($p_value['is_subtotal'])
                {
                    $temp_content_trs .= $this->replace_tr_template_item($last_description_normal_tr_template, $info_data);
                }
                else
                {
                    $temp_content_trs .= $this->replace_tr_template_item($description_normal_tr_template, $info_data);
                }
            }

            // Display footer for prior year
            $info_data = [];
            array_push($info_data, $temp_fs_state_changes_in_equity_footer[0]['description']);

            $footer_row_2 = explode(",", $temp_fs_state_changes_in_equity_footer[0]['footer_item']);

            foreach ($footer_row_2 as $ftr_key => $ftr_value) 
            {
                array_push($info_data, $this->fs_replace_content_model->negative_bracket($ftr_value));
            }

            $temp_content_trs .= $this->replace_tr_template_item($overall_total_tr_template, $info_data);
        }
        /* --------------------- END OF Display data rows for prior year --------------------- */

        /* --------------------- Display data rows for current year --------------------- */
        $cie_current_group = $this->fs_statements_model->get_fs_state_changes_in_equity($data['fs_company_info_id'], "current", $data['group_company']);    // get row data from database (current year)
        $temp_content_trs .= $this->replace_tr_template_item($description_bold_tr_template, ['Current year']);  // display title "Current year"

        foreach ($cie_current_group as $c_key => $c_value) 
        {
            $info_data = [];
            array_push($info_data, $c_value['description']);

            $temp_row = explode(",", $c_value['row_item']);

            foreach ($temp_row as $temp_row_key => $temp_row_value) 
            {
                array_push($info_data, $this->fs_replace_content_model->negative_bracket($temp_row_value));
            }

            if($c_value['is_subtotal'])
            {
                $temp_content_trs .= $this->replace_tr_template_item($last_description_normal_tr_template, $info_data);
            }
            else
            {
                $temp_content_trs .= $this->replace_tr_template_item($description_normal_tr_template, $info_data);
            }
        }

        // Display footer for current year
        $info_data = [];
        array_push($info_data, $temp_fs_state_changes_in_equity_footer[1]['description']);

        $footer_row_1 = explode(",", $temp_fs_state_changes_in_equity_footer[1]['footer_item']);

        foreach ($footer_row_1 as $footer_key => $footer_value) 
        {
            array_push($info_data, $this->fs_replace_content_model->negative_bracket($footer_value));
        }

        $temp_content_trs .= $this->replace_tr_template_item($overall_total_tr_template, $info_data);
        /* --------------------- END OF Display data rows for current year --------------------- */

        $temp_content_trs .= $newline_template; // new line

        // replace and add in values.
        $replaced_tbl_template_2 = str_replace($last_line_space_tr_template, $last_line_space_tr_template . $temp_content_trs, $replaced_tbl_template_2);

        // remove hidden template
        if($data['generate_docs_without_tags'])
        {
            foreach ($ori_all_template as $at_key => $at_value) 
            {
                $replaced_tbl_template_2 = str_replace($at_value, '', $replaced_tbl_template_2);
            }
        }

        return $replaced_tbl_template_2;
    }

    public function verify_line_no_value($data, $fs_company_info)
    {   
        // // DO NOT DELETE THIS
        // // data value
        // $data = array(
        //             'group_cy', 
        //             'group_ly', 
        //             'company_cy', 
        //             'company_ly'
        //         );

        $show = true;

        if($fs_company_info[0]['group_type'] == 1) // no group
        {
            if($fs_company_info[0]['first_set'] == 1) // first set with company
            {
                if(empty($data[2])) // company_cy
                {
                    $show = false;
                }
            }
            else
            {
                if(empty($data[2]) && empty($data[3])) // company_cy & company_ly
                {
                    $show = false;
                }
            }
        }
        else
        {
            if($fs_company_info[0]['first_set'] == 1) // first set with company
            {
                // print_r(array($data[0], $data[2]));

                if(empty($data[0]) && empty($data[2])) // group_cy & company_cy
                {
                    $show = false;
                }
            }
            else
            {
                if(empty($data[0]) && empty($data[1]) && empty($data[2]) && empty($data[3])) // group_cy & group_ly & company_cy & company_ly
                {
                    $show = false;
                }
            }
        }

        return $show;
    }

    public function convert_special_character($item)    // convert & to '&amp'
    {
        $item = str_replace('&', '&amp;', $item);
        $item = str_replace('&amp;lt;', '&lt;', $item);
        $item = str_replace('&amp;gt;', '&gt;', $item);
        
        return $item;
    }
}